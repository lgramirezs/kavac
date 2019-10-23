<?php

namespace Modules\Accounting\Http\Controllers\Reports;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\Accounting\Models\AccountingReportHistory;
use Modules\Accounting\Models\AccountingAccount;
use Modules\Accounting\Models\AccountingEntry;
use Modules\Accounting\Models\Currency;
use Modules\Accounting\Models\Setting;
use Modules\Accounting\Models\ExchangeRate;
use App\Repositories\ReportRepository;
use App\Models\Institution;
use Auth;
use DateTime;

/**
 * @class AccountingReportPdfStateOfResultsController
 * @brief Controlador para la generación del reporte de estado de resultados
 *
 * Clase que gestiona el reporte de estado de resultados
 *
 * @author Juan Rosas <jrosas@cenditel.gob.ve | juan.rosasr01@gmail.com>
 * @copyright <a href='http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/'>LICENCIA DE SOFTWARE CENDITEL</a>
 */

class AccountingStateOfResultsController extends Controller
{
    /**
     * Define la configuración de la clase
     *
     * @author Juan Rosas <jrosas@cenditel.gob.ve | juan.rosasr01@gmail.com>
     */
    public function __construct()
    {
        /** Establece permisos de acceso para cada método del controlador */
        $this->middleware('permission:accounting.report.stateofresults', ['only' => ['pdf']]);
    }

    /**
     * [$convertions lista de conversiones validas]
     * @var array
     */
    protected $convertions = [];

    /**
     * [$currency moneda en la que se expresara el reporte]
     * @var [type]
     */
    protected $currency = null;

    public function getConvertions()
    {
        return $this->convertions;
    }

    public function setConvertions($convertions)
    {
        $this->convertions = $convertions;
    }

    public function getCurrencyId()
    {
        return $this->currency->id;
    }

    /**
     * [setCurrency]
     * @author Juan Rosas <jrosas@cenditel.gob.ve | juan.rosasr01@gmail.com>
     * @param  Currency $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * [pdf genera el reporte en pdf de balance general]
     * @author Juan Rosas <jrosas@cenditel.gob.ve | juan.rosasr01@gmail.com>
     * @param  string   $date     [fecha]
     * @param  string   $level    [nivel de sub cuentas maximo a mostrar]
     * @param  Currency $currency [moneda en que se expresara el reporte]
     * @param  boolean  $zero     [si se tomaran cuentas con saldo cero]
     */
    public function pdfVue($date, $level, Currency $currency, $zero = false)
    {
        /**
         * [$day ultimo dia correspondiente al mes]
         * @var date
         */
        $day = date('d', (mktime(0, 0, 0, explode('-', $date)[1]+1, 1, explode('-', $date)[0])-1));

        /**
         * [$endDate formatea la fecha final de busqueda, (YYYY-mm-dd HH:mm:ss)]
         * @var [type]
         */
        $endDate = $date.'-'.$day;

        /**
         * consulta de cada cuenta y asiento que pertenezca a ACTIVO, PASIVO, PATRIMONIO y CUENTA DE ORDEN
         * [$query registros de las cuentas patrimoniales seleccionadas]
         * @var Modules\Accounting\Models\AccountingAccount
         */
        $query = AccountingAccount::with('entryAccount.entries.currency')
            ->with(['entryAccount.entries' => function ($query) use ($endDate, $date) {
                if ($query->whereBetween('from_date', [explode('-', $date)[0].'-01-01', $endDate])->where('approved', true)) {
                    $query->whereBetween('from_date', [explode('-', $date)[0].'-01-01', $endDate])->where('approved', true);
                }
            }])
            ->whereHas('entryAccount.entries', function ($query) use ($endDate, $date) {
                $query->whereBetween('from_date', [explode('-', $date)[0].'-01-01', $endDate])->where('approved', true);
            })
            ->whereBetween('group', [5, 6])
            ->orderBy('group', 'ASC')
            ->orderBy('subgroup', 'ASC')
            ->orderBy('item', 'ASC')
            ->orderBy('generic', 'ASC')
            ->orderBy('specific', 'ASC')
            ->orderBy('subspecific', 'ASC')
            ->orderBy('denomination', 'ASC')->get();

        $convertions = [];

        /*
         * Se recorre y evalua la relacion en las conversiones necesarias a realizar
         */
        foreach ($query as $record) {
            foreach ($record['entryAccount'] as $entryAccount) {
                if (!array_key_exists($entryAccount['entries']['currency']['id'], $convertions)) {
                    $convertions = $this->calculateExchangeRates(
                        $convertions,
                        $entryAccount['entries'],
                        $currency['id']
                    );

                    if (!array_key_exists($entryAccount['entries']['currency']['id'], $convertions)
                        && $entryAccount['entries']['currency']['id'] != $currency['id']) {
                        return response()->json([
                                    'result'=>false,
                                    'message'=>'Imposible expresar '.$entryAccount['entries']['currency']['symbol']
                                                .' en '.$currency['symbol'].'('.$currency['name'].')'.
                                                ', verificar tipos de cambio configurados.'
                                ], 200);
                    }
                }
            }
        }

        return response()->json(['result'=>true], 200);
    }

    /**
     * [pdf genera el reporte en pdf de estado de resultados]
     * @author Juan Rosas <jrosas@cenditel.gob.ve | juan.rosasr01@gmail.com>
     * @param  string   $date     [fecha]
     * @param  string   $level    [nivel de sub cuentas maximo a mostrar]
     * @param  Currency $currency [moneda en que se expresara el reporte]
     * @param  boolean  $zero     [si se tomaran cuentas con saldo cero]
     */
    public function pdf($date, $level, Currency $currency, $zero = false)
    {
        /**
        * Se guarda un registro cada vez que se genera un reporte, en caso de que ya exista se actualiza
        */
        $url = 'stateOfResults/pdf/'.$date.'/'.$level.'/'.$zero;

        $currentDate = new DateTime;
        $currentDate = $currentDate->format('Y-m-d');

        /**
         * [$report almacena el registro del reporte del dia si existe]
         * @var [type]
         */
        $report = AccountingReportHistory::whereBetween('updated_at', [
                                                                        $currentDate.' 00:00:00',
                                                                        $currentDate.' 23:59:59'
                                                                    ])
                                        ->where('report', 'Estado de Resultados')->first();

        /*
        * se crea o actualiza el registro del reporte
        */
        if (!$report) {
            AccountingReportHistory::create(
                [
                    'report' => 'Estado de Resultados',
                    'url' => $url,
                ]
            );
        } else {
            $report->url = $url;
            $report->save();
        }
        
        /** @var Object String en que se almacena el ultimo dia correspondiente al mes */
        $day = date('d', (mktime(0, 0, 0, explode('-', $date)[1]+1, 1, explode('-', $date)[0])-1));

        /** @var Object String en el que se formatea la fecha final de busqueda, (YYYY-mm-dd HH:mm:ss) */
        $endDate = $date.'-'.$day;

        /** @var Object String en el que se establece la consulta de ralación que se desean realizar  */
        $level_1 = 'entryAccount.entries';

        /** @var Object String en el que se establece la consulta de ralación que se desean realizar  */
        $level_2 = 'children.entryAccount.entries';

        /** @var Object String en el que se establece la consulta de ralación que se desean realizar  */
        $level_3 = 'children.children.entryAccount.entries';

        /** @var Object String en el que se establece la consulta de ralación que se desean realizar  */
        $level_4 = 'children.children.children.entryAccount.entries';

        /** @var Object String en el que se establece la consulta de ralación que se desean realizar  */
        $level_5 = 'children.children.children.children.entryAccount.entries';

        /** @var Object String en el que se establece la consulta de ralación que se desean realizar  */
        $level_6 = 'children.children.children.children.children.entryAccount.entries';

        /**
        * Se realiza la consulta de cada cuenta y asiento que pertenezca a INGRESOS Y GASTOS
        */
        $records = AccountingAccount::with($level_1, $level_2, $level_3, $level_4, $level_5, $level_6)
            ->with([$level_1 => function ($query) use ($endDate) {
                $query->where('from_date', '<=', $endDate)->where('approved', true);
            }])
            ->with([$level_2 => function ($query) use ($endDate) {
                $query->where('from_date', '<=', $endDate)->where('approved', true);
            }])
            ->with([$level_3 => function ($query) use ($endDate) {
                $query->where('from_date', '<=', $endDate)->where('approved', true);
            }])
            ->with([$level_4 => function ($query) use ($endDate) {
                $query->where('from_date', '<=', $endDate)->where('approved', true);
            }])
            ->with([$level_5 => function ($query) use ($endDate) {
                $query->where('from_date', '<=', $endDate)->where('approved', true);
            }])
            ->with([$level_6 => function ($query) use ($endDate) {
                $query->where('from_date', '<=', $endDate)->where('approved', true);
            }])
            ->whereBetween('group', [5, 6])
            ->where('subgroup', 0)
            ->orderBy('group', 'ASC')
            ->orderBy('subgroup', 'ASC')
            ->orderBy('item', 'ASC')
            ->orderBy('generic', 'ASC')
            ->orderBy('specific', 'ASC')
            ->orderBy('subspecific', 'ASC')->get();

        $this->setCurrency($currency);

        /**
         * [$records con los registros de las cuentas]
         * @var array
         */
        $records = $this->FormatDataInArray($records, $date, $endDate);
        
        /** @var Object configuración general de la apliación */
        $setting = Setting::all()->first();

        /**
         * [$pdf base para generar el pdf]
         * @var [Modules\Accounting\Pdf\Pdf]
         */
        $pdf = new ReportRepository();

        /*
         *  Definicion de las caracteristicas generales de la página pdf
         */
        $institution = Institution::find(1);
        $pdf->setConfig(['institution' => $institution, 'urlVerify' => 'www.google.com']);
        $pdf->setHeader('Reporte de Contabilidad', 'Reporte de estado de resultados');
        $pdf->setFooter();
        $pdf->setBody('accounting::pdf.state_of_results', true, [
            'pdf' => $pdf,
            'records' => $records,
            'currency' => $currency,
            'level' => $level,
            'zero' => $zero,
            'endDate' => $endDate,
        ]);
    }
    
    /**
     * [FormatDataInArray sintetiza la información de una cuenta en un array con sus respectivas subcuentas]
     * @author Juan Rosas <jrosas@cenditel.gob.ve | juan.rosasr01@gmail.com>
     * @param  Modules\Accounting\Models\AccountingAccount $records registro de una cuenta o subcuenta patrimonial
     * @param  int $level [contador que indica el nivel de profundidad de la recursividad
     *                       para obtener subcuentas de una cuenta]
     */
    public function FormatDataInArray(records, $initD, $endD, $level = 1)
    {
        /**
         * [$parent información pertinente de la consultar]
         * @var array
         */
        $parent = [];

        /**
         * [$pos posición de la cuenta base]
         * @var integer
         */
        $pos = 0;

        /**
         * condición de parada del ultimo nivel
         */
        if ($level > 6) {
            return [];
        }

        $lastOfThePreviousMonth = date('d', (mktime(0, 0, 0, explode('-', $initD)[1], 1, explode('-', $initD)[0])-1));

        if (count($records) > 0) {
            foreach ($records as $account) {
                array_push($parent, [
                    'code' => $account->getCodeAttribute(),
                    'denomination' => $account->denomination,
                    // mes seleccionado
                    'balance' => $this->calculateValuesInEntries(
                        $account,
                        explode('-', $endD)[0].'-'.explode('-', $endD)[1].'-01',
                        $endD
                    ),
                    // acumulado de los meses anteriores
                    'beginningBalance' => $this->calculateValuesInEntries(
                        $account,
                        explode('-', $initD)[0].'-01-01',
                        explode('-', $endD)[0].'-'.(explode('-', $endD)[1]-1).'-'.$lastOfThePreviousMonth,
                    ),
                    'level' => $level,
                    'children' => [],
                    'show_children' => false,
                ]);
                $parent[$pos]['children'] = $this->FormatDataInArray($account->children, $initD, $endD, $level+1);

                /**
                * El atributo 'show_children' se establece que si la cuenta tiene hijos estos se mostraran por omisión
                * aun si no se deseanmostrar cuentas con saldo 0
                */
                $parent[$pos]['show_children'] = (count($parent[$pos]['children']) > 0)?false:true;
                $pos++;
            }
            return $parent;
        }
        return [];
    }

    /**
     * [calculateValuesInEntries calculo de saldo de la cuenta tomando en cuenta todos sus subcuentas,
     *                             hasta llegar al ultimo nivel de parentela solo se sumaran los valores
     *                             de los asientos contables aprobados]
     * @author Juan Rosas <jrosas@cenditel.gob.ve | juan.rosasr01@gmail.com>
     * @param Object $records registro de una cuenta o subcuenta patrimonial
     * @return Float resultado de realizar la operaciones de suma y resta
     */
    public function calculateValuesInEntries($account, $initD, $endD, $cal = false)
    {
        if (explode('-', $endD)[1]<10) {
            $endD = explode('-', $endD)[0].'-0'.explode('-', $endD)[1].'-'.explode('-', $endD)[2];
        }
        if (explode('-', $initD)[1]<10) {
            $initD = explode('-', $initD)[0].'-0'.explode('-', $initD)[1].'-'.explode('-', $initD)[2];
        }
        /**
         * [$debit saldo total en el debe de la cuenta]
         * @var float
         */
        $debit = 0;

        /**
         * [$assets saldo total en el haber de la cuenta]
         * @var float
         */
        $assets = 0;

        /**
         * [$balanceChildren saldo total de la suma de los saldos de sus cuentas hijo]
         * @var float
         */
        $balanceChildren = 0;

        foreach ($account->entryAccount as $entryAccount) {
            if ($entryAccount->entries['from_date'] >= $initD && $entryAccount->entries['from_date'] <= $endD &&
                $entryAccount->entries['approved']) {
                if (!array_key_exists($entryAccount['entries']['currency']['id'], $this->getConvertions())) {
                    $this->setConvertions($this->calculateExchangeRates(
                        $this->getConvertions(),
                        $entryAccount['entries'],
                        $this->getCurrencyId()
                            ));
                }

                $debit += ($entryAccount['debit'] != 0)?
                            $this->calculateOperation(
                                $this->getConvertions(),
                                $entryAccount['entries']['currency']['id'],
                                $entryAccount['debit'],
                                ($entryAccount['entries']['currency']['id'] == $this->getCurrencyId())??false
                            ):0;

                $assets += ($entryAccount['assets'] != 0)?
                            $this->calculateOperation(
                                $this->getConvertions(),
                                $entryAccount['entries']['currency']['id'],
                                $entryAccount['assets'],
                                ($entryAccount['entries']['currency']['id'] == $this->getCurrencyId())??false
                            ):0;
            }
        }

        if (count($account->children) > 0) {
            foreach ($account->children as $child) {
                /**
                * llamada recursiva y acumulación
                */
                $balanceChildren += $this->calculateValuesInEntries($child, $initD, $endD);
            }
        }
        
        return (($debit - $assets) + $balanceChildren);
    }

    /**
     * [calculateOperation realiza la conversion de saldo]
     * @author Juan Rosas <jrosas@cenditel.gob.ve | juan.rosasr01@gmail.com>
     * @param  array   $convertions   [lista de tipos cambios para la moneda]
     * @param  integer $entry_id      [identificador del asiento]
     * @param  float   $value         [saldo del asiento]
     * @param  boolean $equalCurrency [bandera que indica si el tipo de moneda en el que esta el asiento es las misma
     *                                que la que se desea expresar]
     * @return float                  [resultdado de la operacion]
     */
    public function calculateOperation($convertions, $currency_id, $value, $equalCurrency)
    {
        if (!$equalCurrency) {
            return $value;
        }
        if ($currency_id && array_key_exists($currency_id, $convertions) && $convertions[$currency_id]) {
            if ($convertions[$currency_id]['operator'] == 'to') {
                return ($value * $convertions[$currency_id]['amount']);
            } else {
                return ($value / $convertions[$currency_id]['amount']);
            }
        }
        return -1;
    }

    /**
     * [calculateExchangeRates encuentra los tipos de cambio]
     * @author Juan Rosas <jrosas@cenditel.gob.ve | juan.rosasr01@gmail.com>
     * @param  array           $convertions [lista de conversiones]
     * @param  AccountingEntry $entry       [asiento contable]
     * @param  integer         $currency_id [identificador de la moneda a la cual se realizara la conversion]
     * @return array                        [lista de conversiones actualizada]
     */
    public function calculateExchangeRates($convertions, $entry, $currency_id)
    {
        $exchangeRate = ExchangeRate::where('start_at', '<=', $entry['from_date'])
                             ->where('end_at', '>=', $entry['from_date'])
                             ->where('active', true)
                             ->where('from_currency_id', $entry['currency']['id'])
                             ->where('to_currency_id', $currency_id)
                             ->orderBy('end_at', 'DESC')->first();
        if (!$exchangeRate) {
            $exchangeRate = ExchangeRate::where('start_at', '<=', $entry['from_date'])
                         ->where('end_at', '>=', $entry['from_date'])
                         ->where('active', true)
                         ->where('to_currency_id', $entry['currency']['id'])
                         ->where('from_currency_id', $currency_id)
                         ->orderBy('end_at', 'DESC')->first();
            if ($exchangeRate) {
                if (!array_key_exists($entry['currency']['id'], $convertions)) {
                    $convertions[$entry['currency']['id']] = [
                                                'amount'   => $exchangeRate->amount,
                                                'operator' => 'from'
                                            ];
                }
            }
        } else {
            if (!array_key_exists($entry['currency']['id'], $convertions)) {
                $convertions[$entry['currency']['id']] = [
                                                'amount'   => $exchangeRate->amount,
                                                'operator' => 'to'
                                            ];
            }
        }
        return $convertions;
    }

    public function get_checkBreak()
    {
        return $this->PageBreakTrigger;
    }
}
