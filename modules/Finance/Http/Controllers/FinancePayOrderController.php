<?php
/** [descripción del namespace] */
namespace Modules\Finance\Http\Controllers;

use Carbon\Carbon;
use App\Models\Receiver;
use App\Models\CodeSetting;
use Illuminate\Http\Request;
use App\Models\DocumentStatus;
use Illuminate\Validation\Rule;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Nwidart\Modules\Facades\Module;
use App\Repositories\ReportRepository;
use Modules\Budget\Models\BudgetStage;
use Modules\Budget\Models\BudgetCompromise;
use Modules\Finance\Models\FinancePayOrder;
use Illuminate\Contracts\Support\Renderable;
use Modules\Accounting\Models\AccountingEntry;
use Modules\Accounting\Models\AccountingAccount;
use Modules\Accounting\Models\AccountingEntryable;
use Modules\Accounting\Models\AccountingEntryAccount;
use Modules\Accounting\Models\AccountingEntryCategory;
use Illuminate\Foundation\Validation\ValidatesRequests;

/**
 * @class FinancePayOrderController
 * @brief [descripción detallada]
 *
 * Establece los métodos a implementar en la gestión de órdenes de pago
 *
 * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
 *
 * @license
 *     [LICENCIA DE SOFTWARE CENDITEL](http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/)
 */
class FinancePayOrderController extends Controller
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
        /** Establece permisos de acceso para cada método del controlador */
        $this->middleware('permission:finance.payorder.list', ['only' => 'index', 'vueList']);
        $this->middleware('permission:finance.payorder.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:finance.payorder.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:finance.payorder.delete', ['only' => 'destroy']);

        $this->validate_rules = [
            'ordered_at' => ['required', 'date'],
            'type' => ['required', Rule::in(['PR', 'NP'])],
            'source_amount' => ['required', 'numeric'],
            'amount' => ['required', 'numeric'],
            'concept' => ['required'],
            'observations' => ['required'],
            'finance_payment_method_id' => ['required'],
            'finance_bank_account_id' => ['required']
        ];
    }

    /**
     * [descripción del método]
     *
     * @method    index
     *
     * @author    Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     *
     * @return    Renderable    Retorna la plantilla con la información a mostrar
     */
    public function index()
    {
        return view('finance::pay_orders.list');
    }

    /**
     * [descripción del método]
     *
     * @method    create
     *
     * @author    Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     *
     * @return    Renderable   Retorna el formulario de registro de órdenes d pago
     */
    public function create()
    {
        $accountingAccounts = $this->getAccountingAccounts();
        return view('finance::pay_orders.create-edit-form', compact('accountingAccounts'));
    }

    /**
     * [descripción del método]
     *
     * @method    store
     *
     * @author    Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     *
     * @param     object    Request    $request    Objeto con información de la petición
     *
     * @return    Renderable    Retorna información del registro almacenado
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->validate_rules);

        $codeSetting = CodeSetting::where("model", FinancePayOrder::class)->first();

        if (!$codeSetting) {
            return response()->json(['result' => false, 'message' => [
                'type' => 'custom', 'title' => 'Alerta', 'icon' => 'screen-error', 'class' => 'danger',
                'text' => 'Debe configurar previamente el formato para el código a generar',
            ]], 200);
        }

        list($year, $month, $day) = explode("-", $request->ordered_at);

        $code = generate_registration_code(
            $codeSetting->format_prefix,
            strlen($codeSetting->format_digits),
            (strlen($codeSetting->format_year) === 2) ? substr($year, 0, 2) : $year,
            FinancePayOrder::class,
            'code'
        );
        $compromise = BudgetCompromise::find($request->budget_compromise_id);
        $specificActionId = null;
        if ($compromise) {
            foreach ($compromise->budgetCompromiseDetails as $compromiseDetail) {
                $specificActionId = $compromiseDetail->budgetSubSpecificFormulation->specificAction->id;
                break;
            }
            $codeStage = generate_registration_code('STG', 8, 4, BudgetStage::class, 'code');
            
            $compromise->budgetStages()->create([
                'code' => $codeStage,
                'registered_at' => Carbon::now(),
                'type' => 'CAU',
                'amount' => $request->amount
            ]);
        }

        $documentStatus = DocumentStatus::where('action', 'PR')->first(); // Estatus Por revisar = Por aprobar
        $receiver = Receiver::find($request->name_sourceable_id);

        $financePayOrder = DB::transaction(
            function () use (
                $request,
                $code,
                $compromise,
                $specificActionId,
                $documentStatus,
                $receiver
            ) {
                $pendingAmount = $request->source_amount - $request->amount;
                $financePayOrder = FinancePayOrder::create([
                'code' => $code,
                'ordered_at' => $request->ordered_at,
                'type' => $request->type,
                'is_partial' => ($request->is_partial!==null)?true:false,
                'pending_amount' => $pendingAmount,
                'completed' => ($pendingAmount > 0)?false:true,
                'document_type' => $request->documentType,
                'document_number' => $request->document_number ?? null,
                'source_amount' => $request->source_amount,
                'amount' => $request->amount,
                'concept' => $request->concept,
                'observations' => $request->observations,
                'status' => 'PE', //Estatus pendiente por defecto, este estatus lo modifica la ejecución de pago
                'budget_specific_action_id' => $specificActionId,
                'finance_payment_method_id' => $request->finance_payment_method_id,
                'finance_bank_account_id' => $request->finance_bank_account_id,
                'institution_id' => $request->institution_id,
                'document_status_id' => $documentStatus->id,
                'currency_id' => $request->accounting['currency']['id'],
                'name_sourceable_type' => str_replace("modules", "Modules", $receiver->receiverable_type),
                'name_sourceable_id' => $receiver->receiverable_id,
                'document_sourceable_id' => $request->document_sourceable_id ?? null,
                'document_sourceable_type' => BudgetCompromise::class ?? null
            ]);
                $accountingCategory = AccountingEntryCategory::where('acronym', 'SOP')->first();
                $accountEntry = AccountingEntry::create([
                'from_date'                      => $request->ordered_at,
                'reference'                      => $code, //Código de la órden de pago como referencia
                'concept'                        => $request->concept,
                'observations'                   => $request->observations,
                'accounting_entry_category_id'   => $accountingCategory->id,
                'institution_id'                 => $request->institution_id,
                'currency_id'                    => $request->accounting['currency']['id'],
                'tot_debit'                      => $request->accounting['totDebit'],
                'tot_assets'                     => $request->accounting['totAssets'],
                'approved'                       => false
            ]);
            
                foreach ($request->accountingItems as $account) {
                    /**
                     * Se crea la relación de cuenta a ese asiento si ya existe lo actualiza,
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
                'accounting_entryable_type' => FinancePayOrder::class,
                'accounting_entryable_id' => $financePayOrder->id
            ]);

                return $financePayOrder;
            }
        );

        $request->session()->flash('message', ['type' => 'store']);

        return response()->json(['record' => $financePayOrder, 'message' => 'Success'], 200);
    }

    /**
     * Muestra detalles de una órden de pago
     *
     * @method    show
     *
     * @author    Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     *
     * @param     integer    $id    Identificador del registro
     *
     * @return    Renderable    Retorna la plantilla que mostrará los detalles de la órden de pago
     */
    public function show($id)
    {
        //return view('finance::show');
    }

    /**
     * Muestra el formulario para la actualización de datos de la órden de pago
     *
     * @method    edit
     *
     * @author    Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     *
     * @param     integer    $id    Identificador del registro
     *
     * @return    Renderable    Retorna el formulario para la actualización de datos de la órden de pago
     */
    public function edit($id)
    {
        $accountingAccounts = $this->getAccountingAccounts();
        return view('finance::pay_orders.create-edit-form', compact('accountingAccounts'));
    }

    /**
     * [descripción del método]
     *
     * @method    update
     *
     * @author    Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     *
     * @param     object    Request    $request         Objeto con datos de la petición
     * @param     integer   $id        Identificador del registro
     *
     * @return    Renderable    Retorna el registro actualizado
     */
    public function update(Request $request, $id)
    {
        $financePayOrder = FinancePayOrder::find($id);

        $this->validate($request, $this->validate_rules);

        DB::transaction(function () use ($request, $financePayOrder) {
            $financePayOrder->update($request);
        });

        return response()->json(['record' => $financePayOrder, 'message' => 'Success'], 200);
    }

    /**
     * Elimina una órden de pago
     *
     * @method    destroy
     *
     * @author    Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     *
     * @param     integer    $id    Identificador del registro
     *
     * @return    Renderable    Retorna el registro eliminado
     */
    public function destroy($id)
    {
        $financePayOrder = FinancePayOrder::find($id);

        if ($financePayOrder) {
            /*if ($financePayOrder->restrictDelete()) {
                return response()->json(['error' => true, 'message' => 'El registro no se puede eliminar'], 200);
            }*/
            $financePayOrder->delete();
        }

        return response()->json(['record' => $financePayOrder, 'message' => 'Success'], 200);
    }

    /**
     * Obtiene un listado de documentos para los cuales ordenar pago
     *
     * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     *
     * @param  \Illuminate\Http\Request $request
     * @param  String                   $type         Tipo de órden de pago
     * @param  String                   $request->documentType Tipo de documento de origen a buscar
     *
     * @return void
     */
    public function getSourceDocuments(Request $request)
    {
        list($year, $month, $day) = explode('-', $request->ordered_at);
        
        $data = [['id' => '', 'text' => 'Seleccione...']];
        if ($request->type === 'PR' && Module::has('Budget')) {
            $compromises = $compromises = BudgetCompromise::whereHas(
                'budgetCompromiseDetails',
                function ($q) use ($year) {
                    $q->whereHas('budgetSubSpecificFormulation', function ($qq) use ($year) {
                        $qq->where(['assigned' => true, 'year' => $year]);
                    });
                }
            );
            if ($request->documentType === 'M') {
                $compromises = $compromises->doesnthave('sourceable');
            } else {
                $compromises = $compromises->whereNotNull('sourceable_type')->whereNotNull('sourceable_id');
            }
            $compromises = $compromises->get();
            $currency = '';
            
            foreach ($compromises as $compromise) {
                $compromiseDetails = $compromise->budgetCompromiseDetails()->get();
                $total = 0;
                foreach ($compromiseDetails as $detail) {
                    if (empty($currency)) {
                        $currency = $detail->budgetSubSpecificFormulation->currency;
                    }
                    $total += $detail->total;
                }
                $data[] = [
                    'id' => $compromise->id, 'text' => $compromise->document_number,
                    'budget_compromise_id' => $compromise->id, 'budget_total_amount' => $total,
                    'currency' => $currency
                ];
            }
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
            'records' => FinancePayOrder::with(
                'budgetSpecificAction',
                'financePaymentMethod',
                'institution',
                'documentStatus',
                'nameSourceable',
                'documentSourceable',
                'currency'
            )->with(['financeBankAccount' => function ($q) {
                $q->with(['financeBankingAgency' => function ($qq) {
                    $qq->with('financeBank');
                }]);
            }])->orderBy('ordered_at')->get(),
        ], 200);
    }

    /**
     * Listado con órdenes de pago pendientes por cancelar
     *
     * @author Ing. Roldan Vargas <roldandvg at gmail.com> | <rvargas at cenditel.gob.ve>
     *
     * @return void
     */
    public function getPendingPayOrders($receiver_id = null)
    {
        if ($receiver_id === null) {
            $financePayOrders = FinancePayOrder::with(
                'budgetSpecificAction',
                'financePaymentMethod',
                'institution',
                'documentStatus',
                'nameSourceable',
                'documentSourceable',
                'currency'
            )->with(['financeBankAccount' => function ($q) {
                $q->with('accountingAccount');
            }])->where('status', 'PE')->orderBy('code')->get();
        } else {
            $receiver = Receiver::find($receiver_id);
            
            $financePayOrders = FinancePayOrder::with(
                'budgetSpecificAction',
                'financePaymentMethod',
                'institution',
                'documentStatus',
                'nameSourceable',
                'documentSourceable',
                'currency'
            )->with(['financeBankAccount' => function ($q) {
                $q->with('accountingAccount');
            }])->where([
                'status' => 'PE',
                'name_sourceable_type' => $receiver->receiverable_type,
                'name_sourceable_id' => $receiver->receiverable_id
            ])->orderBy('code')->get();
        }

        $options = [['id'   => '', 'text' => 'Seleccione...']];

        foreach ($financePayOrders as $financePayOrder) {
            array_push($options, [
                'id' => $financePayOrder->id,
                'text' => $financePayOrder->code,
                'amount' => $financePayOrder->amount,
                'budgetSpecificAction' => $financePayOrder->budgetSpecificAction,
                'financePaymentMethod' => $financePayOrder->financePaymentMethod,
                'institution' => $financePayOrder->institution,
                'documentStatus' => $financePayOrder->documentStatus,
                'nameSourceable' => $financePayOrder->nameSourceable,
                'documentSourceable' => $financePayOrder->documentSourceable,
                'currency' => $financePayOrder->currency,
                'financeBankAccount' => $financePayOrder->financeBankAccount
            ]);
        }

        return response()->json(['records' => $options], 200);
    }

    /**
     * Establece el nuevo estatus del documento
     *
     * @author Ing. Roldan Vargas <roldandvg at gmail.com> | <rvargas at cenditel.gob.ve>
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function changeDocumentStatus(Request $request)
    {
        $financePayOrder = FinancePayOrder::find($request->id);
        $documentStatus = DocumentStatus::where('action', $request->action)->first();
        $financePayOrder->document_status_id = $documentStatus->id;
        $financePayOrder->save();
        $financePayOrder = FinancePayOrder::with(
            'budgetSpecificAction',
            'financePaymentMethod',
            'financeBankAccount',
            'institution',
            'documentStatus'
        )->where('id', $request->id)->first();
        return response()->json(['record' => $financePayOrder, 'message' => 'Success'], 200);
    }

    public function pdf($id)
    {
        $financePayOrder = FinancePayOrder::with(
            'institution',
            'currency',
            'financePaymentMethod',
            'budgetSpecificAction'
        )->find($id);
        
        $budjetProjectAcc = null;
        $specificAction = null;
        if ($financePayOrder) {
            if ($financePayOrder->budgetSpecificAction) {
                $budjetProjectAcc = $financePayOrder->budgetSpecificAction->specificable->getTable();
                $specificAction = [
                    'type' => ($budjetProjectAcc==='budget_projects')?'Proyecto':'Acción Centralizada',
                    'code' => $financePayOrder->budgetSpecificAction->specificable->code . ' - ' .
                              $financePayOrder->budgetSpecificAction->code
                ];
            }
            $accountingEntry = AccountingEntry::with(['accountingAccounts' => function ($q) {
                $q->with('account');
            }])->where('reference', $financePayOrder->code)->first();
            $pdf = new ReportRepository;
            $filename = "pay-order-$financePayOrder->code.pdf";
            $file = storage_path() . '/reports/' . $filename;
            list($year, $month, $day) = explode("-", $financePayOrder->ordered_at);
            $pdf->setConfig(
                [
                    'institution' => $financePayOrder->institution,
                    'urlVerify'   => url(''),
                    'orientation' => 'P',
                    'filename'    => $filename
                ]
            );
            $pdf->setHeader("ORDEN DE PAGO Nº $financePayOrder->code", "En ejercicio fiscal: $year", true, false, '', 'C', 'C');
            $pdf->setFooter();
            $pdf->setBody(
                'finance::pay_orders.report',
                true,
                compact('financePayOrder', 'specificAction', 'accountingEntry')
            );
        }
    }
}
