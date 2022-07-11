<?php
/** [descripción del namespace] */
namespace Modules\Finance\Http\Controllers;

use App\Models\Receiver;
use App\Models\CodeSetting;
use Illuminate\Http\Request;
use App\Models\DocumentStatus;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Repositories\ReportRepository;
use Modules\Finance\Models\FinancePayOrder;
use Illuminate\Contracts\Support\Renderable;
use Modules\Accounting\Models\AccountingEntry;
use Modules\Accounting\Models\AccountingAccount;
use Modules\Finance\Models\FinancePaymentExecute;
use Modules\Accounting\Models\AccountingEntryable;
use Modules\Accounting\Models\AccountingEntryAccount;
use Modules\Accounting\Models\AccountingEntryCategory;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Modules\Finance\Models\FinancePayOrderFinancePaymentExecute;

/**
 * @class FinancePaymentExecuteController
 * @brief [descripción detallada]
 *
 * Contiene los métodos necesarios para la gestión de la ejecución de pagos
 *
 * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
 *
 * @license
 *     [LICENCIA DE SOFTWARE CENDITEL](http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/)
 */
class FinancePaymentExecuteController extends Controller
{
    use ValidatesRequests;

    /** @var array Arreglo con las reglas de validación sobre los datos de un formulario */
    public $validate_rules;

    /**
     * Define la configuración de la clase
     *
     * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     */
    public function __construct()
    {
        $this->validate_rules = [
            'paid_at' => ['required', 'date'],
            'source_amount' => ['required'],
            'paid_amount' => ['required'],
        ];
    }

    /**
     * Muestra la plantilla con los registros de ejecuciones de pago
     *
     * @method    index
     *
     * @author    Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     *
     * @return    Renderable    Retorna la plantilla que mostrará el listado de órdenes de pago
     */
    public function index()
    {
        return view('finance::payments_execute.list');
    }

    /**
     * Muestra la plantilla con el formulario para el registro de ejecuciones de pago
     *
     * @method    create
     *
     * @author    Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     *
     * @return    Renderable    Retorna la plantilla con el formulario de registro de ejecuciones de pago
     */
    public function create()
    {
        $accountingAccounts = $this->getAccountingAccounts();
        return view('finance::payments_execute.create-edit-form', compact('accountingAccounts'));
    }

    /**
     * Realiza las acciones para almacenar una órden de pago
     *
     * @method    store
     *
     * @author    Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     *
     * @param     object    Request    $request    Objeto con información de la petición
     *
     * @return    Renderable    Retorna la ejecución de pago registrada
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->validate_rules);

        $codeSetting = CodeSetting::where("model", FinancePaymentExecute::class)->first();

        if (!$codeSetting) {
            return response()->json(['result' => false, 'message' => [
                'type' => 'custom', 'title' => 'Alerta', 'icon' => 'screen-error', 'class' => 'danger',
                'text' => 'Debe configurar previamente el formato para el código a generar',
            ]], 200);
        }

        list($year, $month, $day) = explode("-", $request->paid_at);
        $code = generate_registration_code(
            $codeSetting->format_prefix,
            strlen($codeSetting->format_digits),
            (strlen($codeSetting->format_year) === 2) ? substr($year, 0, 2) : $year,
            FinancePaymentExecute::class,
            'code'
        );
        $documentStatus = DocumentStatus::where('action', 'AP')->first();
        //dd($request->deductions[0]);
        $financePaymentExecute = DB::transaction(function () use ($request, $code, $documentStatus) {
            $pendingAmount = $request->source_amount - $request->paid_amount;
            $payOrder = FinancePayOrder::find($request->reference_selected);
            $financePaymentExecute = FinancePaymentExecute::create([
                'code' => $code,
                'paid_at' => $request->paid_at,
                'has_budget' => true,
                'is_partial' => $request->is_partial ?? false,
                'source_amount' => $request->source_amount,
                'deduction_amount' => $request->deduction_amount,
                'paid_amount' => $request->paid_amount,
                'pending_amount' => $pendingAmount,
                'completed' => ($pendingAmount > 0)?false:true,
                'observations' => $request->observations,
                'status' => ($pendingAmount > 0)?'PP':'PA',
                'document_status_id' => $documentStatus->id,
                'currency_id' => $payOrder->currency_id
            ]);
            foreach ($request->deductions as $deduction) {
                $financePaymentExecute->financePaymentDeductions()->create([
                    'amount' => $request->deduction_amount ?? 0,
                    'deduction_id' => $deduction['id'],
                    'finance_payment_execute_id' => $financePaymentExecute->id
                ]);
            }
            FinancePayOrderFinancePaymentExecute::create([
                'finance_pay_order_id' => $payOrder->id,
                'finance_payment_execute_id' => $financePaymentExecute->id
            ]);

            /** Asiento contable */
            $accountingCategory = AccountingEntryCategory::where('acronym', 'PAG')->first();
            $accountEntry = AccountingEntry::create([
                'from_date'                      => $request->paid_at,
                'reference'                      => $code, //Código de la ejecución de pago como referencia
                'concept'                        => $request->observations,
                'observations'                   => $request->observations,
                'accounting_entry_category_id'   => $accountingCategory->id,
                'institution_id'                 => $payOrder->institution_id,
                'currency_id'                    => $payOrder->currency_id,
                'tot_debit'                      => $request->accounting['totDebit'],
                'tot_assets'                     => $request->accounting['totAssets'],
                'approved'                       => false
            ]);
            
            foreach ($request->accountingItems as $account) {
                /**
                 * Se crea la relación de cuenta a ese asiento si ya existe existe lo actualiza,
                 * de lo contrario crea el nuevo registro de cuenta
                 */
                AccountingEntryAccount::create([
                    'accounting_entry_id' => $accountEntry->id,
                    'accounting_account_id' => $account['id'],
                    'debit' => $account['debit'],
                    'assets' => $account['assets'],
                ]);
            }

            /** Crea la relación entre el asiento contable y el registro de orden de pago */
            AccountingEntryable::create([
                'accounting_entry_id' => $accountEntry->id,
                'accounting_entryable_type' => FinancePaymentExecute::class,
                'accounting_entryable_id' => $financePaymentExecute->id
            ]);
            return $financePaymentExecute;
        });

        $request->session()->flash('message', ['type' => 'store']);

        return response()->json(['record' => $financePaymentExecute, 'message' => 'Success'], 200);
    }

    /**
     * Muestra los detalles de una ejecución de pago
     *
     * @method    show
     *
     * @author    Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     *
     * @param     integer    $id    Identificador del registro
     *
     * @return    Renderable    Retorna los detalles de la ejecución de pago
     */
    public function show($id)
    {
        return view('finance::show');
    }

    /**
     * Muestra el formulario para la actualización de datos de la ejecución de pago
     *
     * @method    edit
     *
     * @author    Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     *
     * @param     integer    $id    Identificador del registro
     *
     * @return    Renderable    Retorna la plantilla con el formulario para la actualización de datos de la ejecución de pago
     */
    public function edit($id)
    {
        $accountingAccounts = $this->getAccountingAccounts();
        return view('finance::payments_execute.create-edit-form', compact('accountingAccounts'));
    }

    /**
     * Actualiza información de una ejecución de pago
     *
     * @method    update
     *
     * @author    Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     *
     * @param     object    Request    $request         Objeto con datos de la petición
     * @param     integer   $id        Identificador del registro
     *
     * @return    Renderable    Retorna los datos actualizados de la ejecución de pago
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Elimina una ejecución de pago
     *
     * @method    destroy
     *
     * @author    Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     *
     * @param     integer    $id    Identificador del registro
     *
     * @return    Renderable    Retorna la ejecución de pago eliminada
     */
    public function destroy($id)
    {
        $financePaymentExecute = FinancePaymentExecute::find($id);

        if ($financePaymentExecute) {
            /*if ($financePaymentExecute->restrictDelete()) {
                return response()->json(['error' => true, 'message' => 'El registro no se puede eliminar'], 200);
            }*/
            $financePaymentExecute->delete();
        }

        return response()->json(['record' => $financePaymentExecute, 'message' => 'Success'], 200);
    }

    /**
     * Obtiene un listado de receptores de las órdenes de pago que aún están pendientes por cancelar
     *
     * @author Ing. Roldan Vargas <roldandvg at gmail.com> | <rvargas at cenditel.gob.ve>
     *
     * @return void
     */
    public function getPayOrderReceivers()
    {
        $nameSources = FinancePayOrder::select(
            'name_sourceable_type',
            'name_sourceable_id'
        )->whereIn('status', ['PE', 'PP'])->groupBy(
            'name_sourceable_type',
            'name_sourceable_id'
        )->get()->toArray();
        
        $data = [['id' => '', 'text' => 'Seleccione...']];
        $groups = Receiver::select('group')->whereIn(
            'receiverable_type',
            array_column($nameSources, 'name_sourceable_type')
        )->whereIn(
            'receiverable_id',
            array_column($nameSources, 'name_sourceable_id')
        )->groupBy('group')->orderBy('group')->get();
        foreach ($groups as $g) {
            $childrens = Receiver::select('id', 'description AS text')
                                 ->where('group', $g->group)->toBase()->get()->toArray();
            array_push($data, ['text' => $g->group, 'children' => $childrens]);
        }

        return response()->json(['records' => $data], 200);
    }

    /**
     * Obtiene los registros de las cuentas patrimoniales
     * @author  Juan Rosas <jrosas@cenditel.gob.ve | juan.rosasr01@gmail.com>
     * @return json [JSON con la información de las cuentas formateada]
    */
    public function getAccountingAccounts()
    {
        /**
         * [$records listado de registros]
         * @var array
         */
        $records = [];
        array_push($records, [
                'id'   => '',
                'text' => 'Seleccione...'
            ]);
        /**
         * ciclo para almecenar y formatear en array las cuentas patrimoniales
         */
        foreach (AccountingAccount::orderBy('group', 'ASC')
                                    ->orderBy('subgroup', 'ASC')
                                    ->orderBy('item', 'ASC')
                                    ->orderBy('generic', 'ASC')
                                    ->orderBy('specific', 'ASC')
                                    ->orderBy('subspecific', 'ASC')
                                    ->get() as $account) {
            if ($account->active) {
                array_push($records, [
                    'id'   => $account->id,
                    'text' => "{$account->getCodeAttribute()} - {$account->denomination}"
                ]);
            }
        };
        /**
         * se convierte array a JSON
         */
        return json_encode($records);
    }

    /**
     * Obtiene los registros a mostrar en listados de componente Vue
     *
     * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     * @return \Illuminate\Http\JsonResponse Devuelve un JSON con la información de las formulaciones
     */
    public function vueList()
    {
        return response()->json([
            'records' => FinancePaymentExecute::with(
                'financePayOrders',
                'financePaymentDeductions',
                'documentStatus'
            )->orderBy('paid_at')->get(),
        ], 200);
    }

    public function pdf($id)
    {
        $financePaymentExecute = FinancePaymentExecute::with([
            'financePaymentDeductions' => function ($q) {
                $q->with('deduction');
            }
        ])->find($id);
        
        /*$budjetProjectAcc = null;
        $specificAction = null;*/
        if ($financePaymentExecute) {
            /*if ($financePaymentExecute->budgetSpecificAction) {
                $budjetProjectAcc = $financePaymentExecute->budgetSpecificAction->specificable->getTable();
                $specificAction = [
                    'type' => ($budjetProjectAcc==='budget_projects')?'Proyecto':'Acción Centralizada',
                    'code' => $financePaymentExecute->budgetSpecificAction->specificable->code . ' - ' .
                              $financePaymentExecute->budgetSpecificAction->code
                ];
            }*/
            $accountingEntry = AccountingEntry::with(['accountingAccounts' => function ($q) {
                $q->with('account');
            }])->where('reference', $financePaymentExecute->code)->first();
            $payOrderPaymentExecute = FinancePayOrderFinancePaymentExecute::where([
                'finance_payment_execute_id' => $financePaymentExecute->id
            ])->first();
            $payOrder = $payOrderPaymentExecute->financePayOrder()->with('institution')->first();
            $pdf = new ReportRepository;
            $filename = "payment-execute-$financePaymentExecute->code.pdf";
            $file = storage_path() . '/reports/' . $filename;
            list($year, $month, $day) = explode("-", $financePaymentExecute->paid_at);
            $pdf->setConfig(
                [
                    'institution' => $payOrder->institution,
                    'urlVerify'   => url(''),
                    'orientation' => 'P',
                    'filename'    => $filename
                ]
            );
            $pdf->setHeader("COMPROBANTE DE EMISIÓN Nº $financePaymentExecute->code", "ACUSE DE PAGO RECIBIDO", true, false, '', 'C', 'C');
            $pdf->setFooter();
            $pdf->setBody(
                'finance::payments_execute.report',
                true,
                compact('payOrder', 'financePaymentExecute', 'accountingEntry')
            );
        }
    }
}
