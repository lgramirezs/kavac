<?php
/** [descripción del namespace] */
namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Nwidart\Modules\Facades\Module;
use Modules\Finance\Models\FinancePayOrder;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Modules\Budget\Models\BudgetCompromise;

/**
 * @class FinancePayOrderController
 * @brief [descripción detallada]
 *
 * [descripción corta]
 *
 * @author [autor de la clase] [correo del autor]
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
     * @author    [nombre del autor] [correo del autor]
     *
     * @return    Renderable    [descripción de los datos devueltos]
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
     * @author    [nombre del autor] [correo del autor]
     *
     * @return    Renderable    [descripción de los datos devueltos]
     */
    public function create()
    {
        return view('finance::pay_orders.create-edit-form');
    }

    /**
     * [descripción del método]
     *
     * @method    store
     *
     * @author    [nombre del autor] [correo del autor]
     *
     * @param     object    Request    $request    Objeto con información de la petición
     *
     * @return    Renderable    [descripción de los datos devueltos]
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->validate_rules);

        $financePayOrder = DB::transaction(function () use ($request) {
            return FinancePayOrder::create($request);
        });

        return response()->json(['record' => $financePayOrder, 'message' => 'Success'], 200);
    }

    /**
     * [descripción del método]
     *
     * @method    show
     *
     * @author    [nombre del autor] [correo del autor]
     *
     * @param     integer    $id    Identificador del registro
     *
     * @return    Renderable    [descripción de los datos devueltos]
     */
    public function show($id)
    {
        //return view('finance::show');
    }

    /**
     * [descripción del método]
     *
     * @method    edit
     *
     * @author    [nombre del autor] [correo del autor]
     *
     * @param     integer    $id    Identificador del registro
     *
     * @return    Renderable    [descripción de los datos devueltos]
     */
    public function edit($id)
    {
        return view('finance::pay_orders.create-edit-form');
    }

    /**
     * [descripción del método]
     *
     * @method    update
     *
     * @author    [nombre del autor] [correo del autor]
     *
     * @param     object    Request    $request         Objeto con datos de la petición
     * @param     integer   $id        Identificador del registro
     *
     * @return    Renderable    [descripción de los datos devueltos]
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
     * [descripción del método]
     *
     * @method    destroy
     *
     * @author    [nombre del autor] [correo del autor]
     *
     * @param     integer    $id    Identificador del registro
     *
     * @return    Renderable    [descripción de los datos devueltos]
     */
    public function destroy($id)
    {
        $financePayOrder = FinancePayOrder::find($id);

        if ($financePayOrder) {
            if ($financePayOrder->restrictDelete()) {
                return response()->json(['error' => true, 'message' => 'El registro no se puede eliminar'], 200);
            }
            $financePayOrder->delete();
        }

        return response()->json(['record' => $financePayOrder, 'message' => 'Success'], 200);
    }

    /**
     * Obtiene un listado de documentos para los cuales ordenar pago
     *
     * @author Ing. Roldan Vargas <roldandvg at gmail.com> | <rvargas at cenditel.gob.ve>
     *
     * @param  \Illuminate\Http\Request $request      
     * @param  String                   $type         Tipo de órden de pago
     * @param  String                   $request->documentType Tipo de documento de origen a buscar
     *
     * @return void                                   
     */
    public function getSourceDocuments(Request $request)
    {
        list($year,$month,$day) = explode('-', $request->ordered_at);
        $data = [['id' => '', 'text' => 'Seleccione...']];
        if ($request->type === 'PR' && Module::has('Budget')) {
            $compromises = $compromises = BudgetCompromise::whereHas(
                'budgetCompromiseDetails', 
                function($q) use ($year) {
                    $q->whereHas('budgetSubSpecificFormulation', function($qq) use ($year) {
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
            foreach ($compromises as $compromise) {
                $data[] = ['id' => $compromise->id, 'text' => $compromise->document_number];
            }
        }
        return response()->json(['records' => $data], 200);
    }
}
