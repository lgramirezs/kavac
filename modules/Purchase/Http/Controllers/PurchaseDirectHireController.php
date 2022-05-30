<?php
/** [descripción del namespace] */
namespace Modules\Purchase\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;

use Modules\Purchase\Models\HistoryTax;
use Modules\Purchase\Models\TaxUnit;
use Modules\Purchase\Models\Department;

use Modules\Purchase\Models\PurchaseOrder;
use Modules\Purchase\Models\PurchaseRequirement;
use Modules\Purchase\Models\PurchasePivotModelsToRequirementItem;
use Modules\Purchase\Models\PurchaseSupplierObject;
use Modules\Purchase\Models\PurchaseDirectHire;

use App\Repositories\UploadDocRepository;
use App\Models\CodeSetting;
use App\Rules\CodeSetting as CodeSettingRule;

use Nwidart\Modules\Facades\Module;

/**
 * @class PurchaseDirectHireController
 * @brief Clase para gestionar las contrationes directas
 *
 * Clase para gestionar las contrationes directas
 *
 * @author Juan Rosas <jrosas@cenditel.gob.ve | juan.rosasr01@gmail.com>
 *
 * @license
 *     [LICENCIA DE SOFTWARE CENDITEL](http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/)
 */
class PurchaseDirectHireController extends Controller
{
    use ValidatesRequests;
    /**
     * [descripción del método]
     *
     * @method    index
     *
     * @author Juan Rosas <jrosas@cenditel.gob.ve | juan.rosasr01@gmail.com>
     *
     * @return    Renderable    [description de los datos devueltos]
     */
    public function index()
    {
        return view('purchase::purchase_order.index', [
            'records' => PurchaseOrder::with('purchaseSupplier', 'currency', 'relatable')->orderBy('id', 'ASC')->get(),
        ]);
    }

    /**
     * [descripción del método]
     *
     * @method    create
     *
     * @author Juan Rosas <jrosas@cenditel.gob.ve | juan.rosasr01@gmail.com>
     *
     * @return    Renderable    [description de los datos devueltos]
     */
    public function create()
    {
        $suppliers       = template_choices('Modules\Purchase\Models\PurchaseSupplier', ['rif','-', 'name'], [], true);

        $currencies      = template_choices('Modules\Purchase\Models\Currency', ['name'], [], true);

        $department_list = template_choices('App\Models\Department', 'name', [], true);

        $historyTax = HistoryTax::with('tax')->whereHas('tax', function ($query) {
            $query->where('active', true);
        })->where('operation_date', '<=', date('Y-m-d'))->orderBy('operation_date', 'DESC')->first();

        $taxUnit    = TaxUnit::where('active', true)->first();

        $purchase_supplier_objects = [];
        
        array_push($purchase_supplier_objects, 
            [
                'id' => '',
                'text' => 'Seleccione...',
            ],
        );

        foreach (PurchaseSupplierObject::all() as $record) {
            $type = $record->type;
            if ($type == 'B') {
                $type = 'Bienes';
            }else if ($type == 'O') {
                $type = 'Obras';
            }else if ($type == 'S') {
                $type = 'Servivios';
            }
            array_push($purchase_supplier_objects, 
                [
                    'id' => $record->id,
                    'text' => $type.' - '.$record->name,
                ],
            );
        }

        $requirements = PurchaseRequirement::with(
            'contratingDepartment',
            'userDepartment',
            'purchaseRequirementItems.warehouseProduct.measurementUnit',
            'purchaseBaseBudget.currency',
            'purchaseBaseBudget.tax.histories',
        )->where('requirement_status', 'PROCESSED')
        ->orderBy('id', 'ASC')->get();

        return view('purchase::purchase_order.direct_hire_form', [
            'requirements'    => $requirements,
            'currencies'                => json_encode($currencies),
            'tax'                       => json_encode($historyTax),
            'tax_unit'                  => json_encode($taxUnit),
            'department_list'           => json_encode($department_list),
            'purchase_supplier_objects' => json_encode($purchase_supplier_objects),
            'suppliers'                 => json_encode($suppliers),
        ]);
    }

    /**
     * [descripción del método]
     *
     * @method    store
     *
     * @author Juan Rosas <jrosas@cenditel.gob.ve | juan.rosasr01@gmail.com>
     *
     * @param     object    Request    $request    Objeto con información de la petición
     *
     * @return    Renderable    [description de los datos devueltos]
     */
    public function store(Request $request, UploadDocRepository $upDoc)
    {
        dd($request->all());
        $this->validate($request, [
            'institution_id'                => 'required|integer',
            'contracting_department_id'     => 'required|integer',
            'user_department_id'            => 'required|integer',
            'purchase_supplier_id'          => 'required|integer',
            'purchase_supplier_object_id'   => 'required|integer',
            'fiscal_year_id'                => 'required|integer',
            'currency_id'                   => 'required|integer',
            'funding_source'                => 'required',
            'description'                   => 'required',
            
            // Archivos
            'start_minutes'                 => 'required|mimes:pdf',
            'company_invitation'            => 'required|mimes:pdf',
            'certificate_receipt_of_offer'  => 'required|mimes:pdf',
            'motivated_act'                 => 'required|mimes:pdf',
            'budget_availability'           => 'required|mimes:pdf',
        ], [
            'institution_id.required'                => 'El campo institución es obligatorio',
            'contracting_department_id.required'     => 'El campo unidad contratante es obligatorio',
            'user_department_id.required'            => 'El campo unidad usuaria es obligatorio',
            'purchase_supplier_id.required'          => 'El campo proveedor es obligatorio',
            'purchase_supplier_object_id.required'   => 'El campo denominación del requerimiento es obligatorio',
            'fiscal_year_id.required'                => 'El campo año de ejercicio económico es obligatorio',
            'currency_id.required'                   => 'El campo tipo de moneda es obligatorio',
            'funding_source.required'                => 'El campo fuente de financiamiento es obligatorio',
            'description.required'                   => 'El campo denominación especifica del requerimiento es obligatorio',

            // Archivos
            'start_minutes.required'                => 'El archivo de acta de inicio es obligatorio.',
            'start_minutes.mimes'                   => 'El archivo de acta de inicio debe estar en formato pdf.',
            'company_invitation.required'           => 'El archivo de invitación de la empresa es obligatorio.',
            'company_invitation.mimes'              => 'El archivo de invitación de la empresa debe estar en formato pdf.',
            'certificate_receipt_of_offer.required' => 'El archivo de acta de recepción de la oferta es obligatorio.',
            'certificate_receipt_of_offer.mimes'    => 'El archivo de acta de recepción de la oferta debe estar en formato pdf.',
            'motivated_act.required'                => 'El archivo de acto motivado es obligatorio.',
            'motivated_act.mimes'                   => 'El archivo de acto motivado debe estar en formato pdf.',
            'budget_availability.required'          => 'El archivo de disponibilidad presupuestaria es obligatorio.',
            'budget_availability.mimes'             => 'El archivo de disponibilidad presupuestaria debe estar en formato pdf.',
        ]);

        $all = $request->all();
        
        $purchaseDirectHire = PurchaseDirectHire::create($request->all());

        /** Registro y asociación de documentos */
        $documentFormat = ['pdf'];

        $documentListName = [
            'start_minutes', 
            'company_invitation', 
            'certificate_receipt_of_offer', 
            'motivated_act', 
            'budget_availability'
        ];

        foreach ($documentListName as $nameFile) {
            if ($request->file($nameFile)) {
                $file = $request->file($nameFile);
                $extensionFile = $file->getClientOriginalExtension();
    
                if (in_array($extensionFile, $documentFormat)) {
                    /**
                     * Se guarda el archivo y se almacena
                     */
                    $upDoc->uploadDoc(
                        $file,
                        'documents',
                        PurchaseDirectHire::class,
                        $purchaseDirectHire->id
                    );
                }
            }
        }

        /**
		 * [$has_budget determina si esta instalado y habilitado el modulo Budget]
		 * @var [boolean]
		 */
		$has_budget = (Module::has('Budget') && Module::isEnabled('Budget'));
		if (!Module::has('Budget') || !Module::isEnabled('Budget')) {
			// 
		}

        return response()->json(['message' => 'Success'], 200);
    }

    /**
     * [descripción del método]
     *
     * @method    show
     *
     * @author Juan Rosas <jrosas@cenditel.gob.ve | juan.rosasr01@gmail.com>
     *
     * @param     integer    $id    Identificador del registro
     *
     * @return    Renderable    [description de los datos devueltos]
     */
    public function show($id)
    {
        return view('purchase::show');
    }

    /**
     * [descripción del método]
     *
     * @method    edit
     *
     * @author Juan Rosas <jrosas@cenditel.gob.ve | juan.rosasr01@gmail.com>
     *
     * @param     integer    $id    Identificador del registro
     *
     * @return    Renderable    [description de los datos devueltos]
     */
    public function edit($id)
    {
        return view('purchase::edit');
    }

    /**
     * [descripción del método]
     *
     * @method    update
     *
     * @author Juan Rosas <jrosas@cenditel.gob.ve | juan.rosasr01@gmail.com>
     *
     * @param     object    Request    $request         Objeto con datos de la petición
     * @param     integer   $id        Identificador del registro
     *
     * @return    Renderable    [description de los datos devueltos]
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'institution_id'                => 'required|integer',
            'contracting_department_id'     => 'required|integer',
            'user_department_id'            => 'required|integer',
            'purchase_supplier_id'          => 'required|integer',
            'purchase_supplier_object_id'   => 'required|integer',
            'fiscal_year_id'                => 'required|integer',
            'currency_id'                   => 'required|integer',
            'funding_source'                => 'required',
            'description'                   => 'required',
            'presupuesto_base_estimado'     => 'required|mimes:pdf',
            'disponibilidad_presupuestaria' => 'required|mimes:pdf',
        ], [
            'presupuesto_base_estimado.required'     => 'El archivo de presupuesto base estimado es obligatorio.',
            'presupuesto_base_estimado.mimes'        => 'El archivo de presupuesto base estimado debe estar en formato pdf.',
            'disponibilidad_presupuestaria.required' => 'El archivo de disponibilidad presupuestaria es obligatorio.',
            'disponibilidad_presupuestaria.mimes'    => 'El archivo de disponibilidad presupuestaria debe estar en formato pdf.',
            'institution_id.required'                => 'El campo institución es obligatorio',
            'contracting_department_id.required'     => 'El campo unidad contratante es obligatorio',
            'user_department_id.required'            => 'El campo unidad usuaria es obligatorio',
            'purchase_supplier_id.required'          => 'El campo proveedor es obligatorio',
            'purchase_supplier_object_id.required'   => 'El campo denominación del requerimiento es obligatorio',
            'fiscal_year_id.required'                => 'El campo año de ejercicio económico es obligatorio',
            'currency_id.required'                   => 'El campo tipo de moneda es obligatorio',
            'funding_source.required'                => 'El campo fuente de financiamiento es obligatorio',
            'description.required'                   => 'El campo denominación especifica del requerimiento es obligatorio',
        ]);

        /**
		 * [$has_budget determina si esta instalado y habilitado el modulo Budget]
		 * @var [boolean]
		 */
		$has_budget = (Module::has('Budget') && Module::isEnabled('Budget'));
		if (!Module::has('Budget') || !Module::isEnabled('Budget')) {
			// 
		}

        return response()->json(['message' => 'Success'], 200);
    }

    /**
     * [descripción del método]
     *
     * @method    destroy
     *
     * @author Juan Rosas <jrosas@cenditel.gob.ve | juan.rosasr01@gmail.com>
     *
     * @param     integer    $id    Identificador del registro
     *
     * @return    Renderable    [description de los datos devueltos]
     */
    public function destroy(UploadDocRepository $upDoc, $id)
    {
        $record = PurchaseDirectHire::find($id);
        if ($record) {
            /** Se elimina la relacion y los documentos previos **/
            $supp_docs = $record->documents()->get();
            if (count($supp_docs) > 0) {
                foreach ($supp_docs as $doc) {
                    $upDoc->deleteDoc($doc->file, 'documents');
                    $doc->delete();
                }
            }

            $record->delete();
        }
        return response()->json(['message' => 'Success'], 200);
    }

    /**
     * Obtiene listado de registros
     *
     * @author Juan Rosas <jrosas@cenditel.gob.ve | juan.rosasr01@gmail.com>
     * @return \Illuminate\Http\JsonResponse
     */
    public function vueList()
    {
        return response()->json(['records' => PurchaseDirectHire::with('fiscalYear')->orderBy('id', 'asc')->get()], 200);
    }

    public function updatePurchaseOrder($id)
    {
        //
    }

    /**
     * [generateCodeAvailable genera el código disponible]
     * @author Juan Rosas <jrosas@cenditel.gob.ve | juan.rosasr01@gmail.com>
     * @return string [código que se asignara]
     */
    public function generateCodeAvailable()
    {
        $codeSetting = CodeSetting::where('table', 'minutes_code')
                                    ->first();

        if (!$codeSetting) {
            $codeSetting = CodeSetting::where('table', 'minutes_code')
                                    ->first();
        }

        if ($codeSetting) {
            $code  = generate_registration_code(
                $codeSetting->format_prefix,
                strlen($codeSetting->format_digits),
                (strlen($codeSetting->format_year) == 2) ? date('y') : date('Y'),
                PurchaseDirectHire::class,
                $codeSetting->field
            );
        } else {
            $code = 'Error al generar código de la contratación directa';
        }

        return $code;
    }
}
