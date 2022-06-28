<?php
/** [descripción del namespace] */
namespace Modules\Purchase\Http\Controllers;

use App\Models\Tax;
use App\Models\Profile;
use App\Models\Receiver;
use App\Models\CodeSetting;

use Illuminate\Http\Request;
use App\Models\DocumentStatus;
use Illuminate\Routing\Controller;

use Illuminate\Support\Facades\DB;
use Nwidart\Modules\Facades\Module;
use Modules\Purchase\Models\TaxUnit;
use Modules\Purchase\Models\Department;
use Modules\Purchase\Models\HistoryTax;
use App\Repositories\UploadDocRepository;

use Modules\Purchase\Models\PurchaseOrder;

use Illuminate\Contracts\Support\Renderable;
use App\Rules\CodeSetting as CodeSettingRule;
use Modules\Payroll\Models\PayrollEmployment;

use Modules\Purchase\Models\PurchaseSupplier;
use Modules\Purchase\Models\PurchaseBaseBudget;
use Modules\Purchase\Models\PurchaseDirectHire;

use Modules\Purchase\Models\PurchaseRequirement;

use Modules\Purchase\Models\PurchaseSupplierObject;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Modules\Purchase\Models\PurchasePivotModelsToRequirementItem;

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
        $user_profile = Profile::with('institution')->where('user_id', auth()->user()->id)->first();

        $suppliers = template_choices('Modules\Purchase\Models\PurchaseSupplier', ['rif','-', 'name'], [], true);

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
            'purchaseRequirementItems.pivotPurchase',
            'purchaseRequirementItems.purchaseRequirement',
            'purchaseBaseBudget.currency',
            'purchaseBaseBudget.tax.histories',
        )->whereHas('purchaseBaseBudget', function ($query){
            $query->where('orderable_id', null);
        })->where('requirement_status', 'PROCESSED')
        ->orderBy('id', 'ASC')->get();

        /**
         * Se obtienen los datos laborales
         */
        $employments = [
            [
                'id'=>'',
                'text'=>'Seleccione...'
            ]
        ];

        if ($user_profile && $user_profile->institution !== null) {
            foreach (PayrollEmployment::with('payrollStaff', 'profile')
                    ->whereHas('profile', function($query) use ($user_profile){
                        $query->where('institution_id', $user_profile->institution);
                    })->get() as $key => $employment) {
                $text = '';
                if ($employment->payrollStaff->id_number) {
                    $text = $employment->payrollStaff->id_number.' - '.
                            $employment->payrollStaff->first_name.' '.$employment->payrollStaff->last_name;
                } else {
                    $text = $employment->payrollStaff->passport.' - '.
                            $employment->payrollStaff->first_name.' '.$employment->payrollStaff->last_name;
                }
                array_push($employments, [
                    'id'   => $employment->id,
                    'text' => $text
                ]);
            }
        } else {
            foreach (PayrollEmployment::with('payrollStaff')->get() as $key => $employment) {
                $text = '';
                if ($employment->payrollStaff->id_number) {
                    $text = $employment->payrollStaff->id_number.' - '.
                            $employment->payrollStaff->first_name.' '.$employment->payrollStaff->last_name;
                } else {
                    $text = $employment->payrollStaff->passport.' - '.
                            $employment->payrollStaff->first_name.' '.$employment->payrollStaff->last_name;
                }
                array_push($employments, [
                    'id'   => $employment->id,
                    'text' => $text
                ]);
            }
        }
        return view('purchase::purchase_order.direct_hire_form', [
            'requirements'    => $requirements,
            'currencies'                => json_encode($currencies),
            'tax'                       => json_encode($historyTax),
            'tax_unit'                  => json_encode($taxUnit),
            'department_list'           => json_encode($department_list),
            'employments'               => json_encode($employments),
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
            'requirement_list'              => 'required',

            // Firmas
            'prepared_by_id'                => 'required',
            'reviewed_by_id'                => 'required',
            'verified_by_id'                => 'required',
            'first_signature_id'            => 'required',
            'second_signature_id'           => 'required',
            
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
            'requirement_list.required'              => 'Debe seleccionar al menos un presupuesto base.',

            // firmas
            'prepared_by_id.required'                => 'El campo preparado por es obligatorio',
            'reviewed_by_id.required'                => 'El campo revisado por es obligatorio',
            'verified_by_id.required'                => 'El campo verificado por es obligatorio',
            'first_signature_id.required'            => 'El campo firmado por es obligatorio',
            'second_signature_id.required'           => 'El campo firmado por es obligatorio',

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

        $codeSetting = CodeSetting::where("model", PurchaseDirectHire::class)->first();

        if (!$codeSetting) {
            return response()->json(['result' => false, 'message' => [
                'type' => 'custom', 'title' => 'Alerta', 'icon' => 'screen-error', 'class' => 'danger',
                'text' => 'Debe configurar previamente el formato para el código a generar',
            ]], 200);
        }

        $year = $request->fiscal_year ?? date("Y");

        $codeDirectHire = generate_registration_code(
            $codeSetting->format_prefix,
            strlen($codeSetting->format_digits),
            (strlen($codeSetting->format_year) === 2) ? date("y") : $year,
            PurchaseDirectHire::class,
            'code'
        );

        // DB::transaction(function () use ($request, $upDoc, $codeDirectHire) {

            $data = $request->all();
            $data['code'] = $codeDirectHire;

            $purchaseDirectHire = PurchaseDirectHire::create($data);

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
                            $purchaseDirectHire->id,
                            $code = null,
                            $sign = false,
                            $public_url = false,
                            $originalName = true,
                        );
                    }
                }
            }

            /**
             * Se relaciona los presupuestos base con la orden de contratación directa
             */
            $requirement_list = json_decode(json_encode($request->all()['requirement_list']));
    
            $purchaseBaseBudgetsID = [];
            $tax = null;
            foreach ($requirement_list as $requirement) {
                $req = json_decode($requirement, true);
                $baseBudget = PurchaseBaseBudget::find($req['purchase_base_budget_id']);
                $baseBudget->orderable_type = PurchaseDirectHire::class;
                $baseBudget->orderable_id = $purchaseDirectHire->id;
                $baseBudget->save();

                if (!$tax) {
                    $tax = Tax::find($baseBudget->tax_id);
                }

                array_push($purchaseBaseBudgetsID, $req['purchase_base_budget_id']);
            }

            /**
             * [$has_budget determina si esta instalado y habilitado el modulo Budget]
             * @var [boolean]
             */

            $has_budget = (Module::has('Budget') && Module::isEnabled('Budget'));
            if ($has_budget) {
                $codeSetting = CodeSetting::where("model", \Modules\Budget\Models\BudgetCompromise::class)->first();

                if (!$codeSetting) {
                    return response()->json(['result' => false, 'message' => [
                        'type' => 'custom', 'title' => 'Alerta', 'icon' => 'screen-error', 'class' => 'danger',
                        'text' => 'Debe configurar previamente el formato para el código a generar',
                    ]], 200);
                }

                $year = $request->fiscal_year ?? date("Y");
                
                $codeCompromise = generate_registration_code(
                    $codeSetting->format_prefix,
                    strlen($codeSetting->format_digits),
                    (strlen($codeSetting->format_year) === 2) ? date("y") : $year,
                    \Modules\Budget\Models\BudgetCompromise::class,
                    'code'
                );

                $compromisedYear = explode("-", $request->date)[0];

                /** @var Object Estado inicial del compromiso establecido a elaborado */
                $documentStatus = DocumentStatus::where('action', 'EL')->first();

                /** @var Object Datos del compromiso */
                $compromise = \Modules\Budget\Models\BudgetCompromise::create([
                    'document_number' => $codeDirectHire,
                    'institution_id' => $request->institution_id,
                    'compromised_at' => $request->date,
                    'sourceable_type' => PurchaseDirectHire::class,
                    'sourceable_id' => $purchaseDirectHire->id,
                    'description' => $request->description,
                    'code' => $codeCompromise,
                    'document_status_id' => $documentStatus->id,
                ]);

                $total = 0;

                /** Gestiona los ítems del compromiso */
                foreach ($request->record_items as $product) {
                    $prod = json_decode($product, true);
                    $amount = 0;
                    foreach ($prod['pivot_purchase'] as $pivot) {
                        if (in_array($pivot['relatable_id'], $purchaseBaseBudgetsID)) {
                            $amount = $pivot['unit_price'] * $prod['quantity'];
                        }
                    }

                    $taxHistory = ($tax) ? $tax->histories()->orderBy('operation_date', 'desc')->first() : new Tax();
                    $taxAmount = ($amount * (($taxHistory) ? $taxHistory->percentage : 0)) / 100;
                    $compromise->budgetCompromiseDetails()->create([
                        'description' => $prod['description'],
                        'amount' => $amount,
                        'tax_amount' => $taxAmount,
                        'tax_id' => $tax->id,
                        // 'budget_account_id' => $account['account_id'],
                        // 'budget_sub_specific_formulation_id' => $formulation->id,
                    ]);
                    $total += ($amount + $taxAmount);
                }

                $compromise->budgetStages()->create([
                    'code' => $codeCompromise,
                    'registered_at' => $request->date,
                    'type' => 'PRE',
                    'amount' => $total,
                ]);
            }
        // });
        
        $supplier = PurchaseSupplier::find($request->purchase_supplier_id);
        Receiver::firstOrCreate(
            ['receiverable_id' => $request->purchase_supplier_id, 'receiverable_type' => PurchaseSupplier::class],
            ['group' => 'Proveedores', 'description' => $supplier->referential_name]
        );

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
        return response()->json([
            'records' => PurchaseDirectHire::with(
                'contratingDepartment',
                'currency',
                'documents',
                'firstSignature.payrollStaff',
                'fiscalYear',
                'institution',
                'preparedBy.payrollStaff',
                'purchaseSupplier',
                'purchaseSupplierObject',
                'reviewedBy.payrollStaff',
                'secondSignature.payrollStaff',
                'userDepartment',
                'verifiedBy.payrollStaff'
            )->find($id)
        ], 200);
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

        $supplier = PurchaseSupplier::find($request->purchase_supplier_id);
        Receiver::firstOrCreate(
            ['receiverable_id' => $request->purchase_supplier_id, 'receiverable_type' => PurchaseSupplier::class],
            ['group' => 'Proveedores', 'description' => $supplier->referential_name]
        );

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
