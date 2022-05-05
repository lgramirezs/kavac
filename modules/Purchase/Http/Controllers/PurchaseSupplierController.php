<?php

namespace Modules\Purchase\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Models\Country;
use App\Models\Estate;
use App\Models\RequiredDocument;
use App\Models\Phone;
use App\Models\Contact;
use App\Repositories\UploadDocRepository;
use App\Rules\Rif as RifRule;
use Modules\Purchase\Models\PurchaseSupplierBranch;
use Modules\Purchase\Models\PurchaseSupplierObject;
use Modules\Purchase\Models\PurchaseSupplierSpecialty;
use Modules\Purchase\Models\PurchaseSupplierType;
use Modules\Purchase\Models\PurchaseSupplier;
use Modules\Purchase\Models\City;

class PurchaseSupplierController extends Controller
{
    use ValidatesRequests;

    protected $countries;
    protected $estates;
    protected $cities;
    protected $supplier_types;
    protected $supplier_branches;
    protected $supplier_specialties;
    protected $supplier_objects;
    protected $requiredDocuments;

    public function __construct()
    {
        $this->countries = template_choices(Country::class);
        $this->estates = template_choices(Estate::class);
        $this->cities = template_choices(City::class);
        $this->supplier = template_choices(PurchaseSupplier::class);

        $this->supplier_types = template_choices(PurchaseSupplierType::class);
        $this->supplier_branches = template_choices(PurchaseSupplierBranch::class);
        $this->supplier_specialties = template_choices(PurchaseSupplierSpecialty::class);

        $supplier_objects = ['Bienes' => [], 'Obras' => [], 'Servicios' => []];
        $assets = $works = $services = [];

        foreach (PurchaseSupplierObject::all() as $so) {
            $type = ($so->type === 'B') ? 'Bienes' : (($so->type === 'O') ? 'Obras' : 'Servicios');
            $supplier_objects[$type][$so->id] = $so->name;
        }

        $this->supplier_objects = $supplier_objects;
        $this->requiredDocuments = RequiredDocument::where(['model' => 'supplier', 'module' => 'purchase'])->get();
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('purchase::suppliers.list');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $header = [
            'route' => 'purchase.suppliers.store',
            'method' => 'POST',
            'role' => 'form',
            'enctype'=>'multipart/form-data'
        ];

        return view('purchase::suppliers.create-edit-form', [
            'countries' => $this->countries, 'estates' => $this->estates, 'cities' => $this->cities,
            'supplier_types' => $this->supplier_types, 'supplier_objects' => $this->supplier_objects,
            'supplier_branches' => $this->supplier_branches, 'supplier_specialties' => $this->supplier_specialties,
            'header' => $header, 'requiredDocuments' => $this->requiredDocuments
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Renderable
     */
    public function store(Request $request, UploadDocRepository $upDoc)
    {
        // dd($request->all());
        $rules = [
            'person_type'                    => ['required'],
            'company_type'                   => ['required'],
            'rif'                            => ['required', 'size:10', new RifRule],
            'name'                           => ['required'],
            'purchase_supplier_type_id'      => ['required'],
            'purchase_supplier_object_id'    => ['required'],
            'purchase_supplier_branch_id'    => ['required'],
            'purchase_supplier_specialty_id' => ['required'],
            'country_id'                     => ['required'],
            'estate_id'                      => ['required'],
            'city_id'                        => ['required'],
            'direction'                      => ['required'],
            'rnc_certificate_number'         => ['required_with:rnc_status'],
            'contact_names'                  => ['array'],
            'contact_emails'                 => ['array'],
            'phone_type'                     => ['array'],
            'phone_area_code'                => ['array'],
            'phone_number'                   => ['array'],
            'phone_extension'                => ['array'],
        ];

        $messages = [
            'person_type.required'                    => 'El campo tipo de persona es obligatorio.',
            'company_type.required'                   => 'El campo tipo de empresa es obligatorio.',
            'rif.required'                            => 'El campo rif es obligatorio.',
            'name.required'                           => 'El campo nombre es obligatorio.',
            'purchase_supplier_type_id.required'      => 'El campo denominación comercial es obligatorio.',
            'purchase_supplier_object_id.required'    => 'El campo objeto principal es obligatorio.',
            'purchase_supplier_branch_id.required'    => 'El campo rama es obligatorio.',
            'purchase_supplier_specialty_id.required' => 'El campo especialidad es obligatorio.',
            'country_id.required'                     => 'El campo pais es obligatorio.',
            'estate_id.required'                      => 'El campo estado es obligatorio.',
            'city_id.required'                        => 'El campo ciudad es obligatorio.',
            'direction.required'                      => 'El campo dirección fiscal es obligatorio.',
            'empty_contact_info.required'             => 'Los campos de datos de contacto son obligatorios.',
            'empty_phone_info.required'               => 'Los campos de nùmeros telefònicos son obligatorios.',
        ];
        
        /**
         * Se verifica que no tenga informaciòn en los campos de nùmeros telefònicos
         */
        if (array_key_exists("phone_type", $request->all())) {
            foreach ($request->phone_type as $key => $value) {
                if (!$value) {
                    $rules['empty_phone_info'] = ['required'];
                    $request->empty_phone_info = null;
                    break;
                }
                if (!$request->phone_area_code[$key]) {
                    $rules['empty_phone_info'] = ['required'];
                    $request->empty_phone_info = null;
                    break;
                }
                if (!$request->phone_number[$key]) {
                    $rules['empty_phone_info'] = ['required'];
                    $request->empty_phone_info = null;
                    break;
                }
                if (!$request->phone_extension[$key]) {
                    $rules['empty_phone_info'] = ['required'];
                    $request->empty_phone_info = null;
                    break;
                }
            }
        }

        /**
         * Se verifica que no tenga informaciòn en los campos de contacto
         */
        if (array_key_exists("contact_names", $request->all())) {
            foreach ($request->contact_names as $key => $value) {
                if (!$value) {
                    $rules['empty_contact_info'] = ['required'];
                    $request->empty_contact_info = null;
                    break;
                }
                if (!$request->contact_emails[$key]) {
                    $rules['empty_contact_info'] = ['required'];
                    $request->empty_contact_info = null;
                    break;
                }
            }
        }

        $this->validate($request, $rules, $messages);
        
        //$supplier = PurchaseSupplier::first();
        $supplier = PurchaseSupplier::create([
            'person_type'                    => $request->person_type,
            'company_type'                   => $request->company_type,
            'rif'                            => $request->rif,
            'code'                           => generate_code(PurchaseSupplier::class, 'code'),
            'name'                           => $request->name,
            'direction'                      => $request->direction,
            'website'                        => $request->website ?? null,
            'active'                         => $request->active ? true : false,
            // 'purchase_supplier_object_id'    => $request->purchase_supplier_object_id,
            'purchase_supplier_branch_id'    => $request->purchase_supplier_branch_id,
            'purchase_supplier_specialty_id' => $request->purchase_supplier_specialty_id,
            'purchase_supplier_type_id'      => $request->purchase_supplier_type_id,
            'country_id'                     => $request->country_id,
            'estate_id'                      => $request->estate_id,
            'city_id'                        => $request->city_id,
            'rnc_status'                     => $request->rnc_status ?? 'NOI',
            'rnc_certificate_number'         => $request->rnc_certificate_number ?? null,
            'social_purpose'                 => $request->social_purpose,
        ]);

        /** sincroniza la relacion en la tabla pivote de purchase_object_supplier **/
        $supplier->purchaseSupplierObjects()->sync($request->purchase_supplier_object_id);

        //dd($request->all());
        /** Registros asociados a contactos */
        if ($request->contact_names && !empty($request->contact_names)) {
            foreach ($request->contact_names as $key => $contact) {
                $supplier->contacts()->save(new Contact([
                    'name' => $request->contact_names[$key],
                    'email' => $request->contact_emails[$key],
                ]));
            }
        }

        /** Asociación de números telefónicos */
        if ($request->phone_type && !empty($request->phone_type)) {
            foreach ($request->phone_type as $key => $phone_type) {
                $supplier->phones()->save(new Phone([
                    'type' => $phone_type,
                    'area_code' => $request->phone_area_code[$key],
                    'number' => $request->phone_number[$key],
                    'extension' => $request->phone_extension[$key]
                ]));
            }
        }

        /** Registro y asociación de documentos */
        $documentFormat = ['doc', 'docx', 'pdf', 'odt'];
        if ($request->file('docs')) {
            foreach ($request->file('docs') as $file) {
                
                $extensionFile = $file->getClientOriginalExtension();

                if (in_array($extensionFile, $documentFormat)) {
                    $upDoc->uploadDoc(
                        $file,
                        'documents',
                        PurchaseSupplier::class,
                        $supplier->id
                    );
                }
            }
        }

        session()->flash('message', ['type' => 'store']);

        return redirect()->route('purchase.suppliers.index');
    }

    /**
     * Show the specified resource.
     * @return Renderable
     */
    public function show($id)
    {
        return response()->json(['records' => PurchaseSupplier::find($id)], 200);
    }
  
     /**
     * Show the specified resource.
     * @return Renderable
     */
    public function showall()
    {
  
      return template_choices(PurchaseSupplier::class, 'name', '', true);

  
    }
    /**
     * Show the form for editing the specified resource.
     * @return Renderable
     */
    public function edit($id)
    {
        $model = PurchaseSupplier::with('documents')->find($id);
        //dd($model->documents);
        $purchase_supplier_objects = [];

        foreach ($model->purchaseSupplierObjects as $record) {
            array_push($purchase_supplier_objects, $record->id);
        }
        $header = [
            'route' => ['purchase.suppliers.update', $model->id],
            'method' => 'PUT',
            'role' => 'form',
            'enctype'=>'multipart/form-data'
        ];

        return view('purchase::suppliers.create-edit-form', [
            'countries' => $this->countries, 'estates' => $this->estates, 'cities' => $this->cities,
            'supplier_types' => $this->supplier_types, 'supplier_objects' => $this->supplier_objects,
            'supplier_branches' => $this->supplier_branches, 'supplier_specialties' => $this->supplier_specialties,
            'header' => $header, 'requiredDocuments' => $this->requiredDocuments, 'model' => $model, 
            'model_supplier_objects' => $purchase_supplier_objects
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Renderable
     */
    public function update(Request $request, UploadDocRepository $upDoc, $id)
    {
        //dd($request->all());
        $this->validate($request, [
            'person_type'                    => ['required'],
            'company_type'                   => ['required'],
            'rif'                            => ['required', 'size:10', new RifRule],
            'name'                           => ['required'],
            'purchase_supplier_type_id'      => ['required'],
            'purchase_supplier_object_id'    => ['required'],
            'purchase_supplier_branch_id'    => ['required'],
            'purchase_supplier_specialty_id' => ['required'],
            'country_id'                     => ['required'],
            'estate_id'                      => ['required'],
            'city_id'                        => ['required'],
            'direction'                      => ['required'],
            'contact_names'                   => ['required'],
            'contact_emails'                  => ['required'],
            'rnc_certificate_number'         => ['required'],
            'phone_type'                     => ['array'],
            'phone_area_code'                => ['array'],
            'phone_number'                   => ['array'],
            'phone_extension'                => ['array'],
        ],
        [
            'person_type.required'                    => 'El campo tipo de persona es obligatorio.',
            'company_type.required'                   => 'El campo tipo de empresa es obligatorio.',
            'rif.required'                            => 'El campo rif es obligatorio.',
            'name.required'                           => 'El campo nombre es obligatorio.',
            'purchase_supplier_type_id.required'      => 'El campo denominación comercial es obligatorio.',
            'purchase_supplier_object_id.required'    => 'El campo objeto principal es obligatorio.',
            'purchase_supplier_branch_id.required'    => 'El campo rama es obligatorio.',
            'purchase_supplier_specialty_id.required' => 'El campo especialidad es obligatorio.',
            'country_id.required'                     => 'El campo pais es obligatorio.',
            'estate_id.required'                      => 'El campo estado es obligatorio.',
            'city_id.required'                        => 'El campo ciudad es obligatorio.',
            'direction.required'                      => 'El campo dirección fiscal es obligatorio.',
            'contact_names.required'                   => 'El campo nombre de contacto es obligatorio.',
            'contact_emails.required'                  => 'El campo correo electrónico de contacto es obligatorio.',
        ]);

        $supplier = PurchaseSupplier::find($id);

        $supplier->person_type                    = $request->person_type;
        $supplier->company_type                   = $request->company_type;
        $supplier->rif                            = $request->rif;
        $supplier->code                           = $supplier->code;
        $supplier->name                           = $request->name;
        $supplier->direction                      = $request->direction;
        $supplier->website                        = $request->website ?? null;
        $supplier->active                         = $request->active ? true : false;
        //$supplier->purchase_supplier_object_id    = $request->purchase_supplier_object_id;
        $supplier->purchase_supplier_branch_id    = $request->purchase_supplier_branch_id;
        $supplier->purchase_supplier_specialty_id = $request->purchase_supplier_specialty_id;
        $supplier->purchase_supplier_type_id      = $request->purchase_supplier_type_id;
        $supplier->country_id                     = $request->country_id;
        $supplier->estate_id                      = $request->estate_id;
        $supplier->city_id                        = $request->city_id;
        $supplier->rnc_status                     = $request->rnc_status ?? 'NOI';
        $supplier->rnc_certificate_number         = $request->rnc_certificate_number ?? null;
        $supplier->social_purpose                 = $request->social_purpose;

        $supplier->save();

        /** sincroniza la relacion en la tabla pivote de purchase_object_supplier **/
        $supplier->purchaseSupplierObjects()->sync($request->purchase_supplier_object_id);

        /** Se elimina la relacion de proveedor con los contactos anteriores **/
        $supp_contacts = $supplier->contacts()->get();
        if (count($supp_contacts) > 0) {
            foreach ($supp_contacts as $value) {
                $value->delete();
            }
        }

        /** Registros asociados a contactos */
        if ($request->contact_names && !empty($request->contact_names)) {
            foreach ($request->contact_names as $key => $contact) {
                $supplier->contacts()->save(new Contact([
                    'name' => $request->contact_names[$key],
                    'email' => $request->contact_emails[$key],
                ]));
            }
        }

        /** Se elimina la relacion de proveedor con los telefonos anteriores **/
        $supp_ph = $supplier->phones()->get();
        if (count($supp_ph) > 0) {
            foreach ($supp_ph as $value) {
                $value->delete();
            }
        }

        /** Asociación de números telefónicos */
        if ($request->phone_type && !empty($request->phone_type)) {
            foreach ($request->phone_type as $key => $phone_type) {

                $supplier->phones()->save(new Phone([
                    'type' => $phone_type,
                    'area_code' => $request->phone_area_code[$key],
                    'number' => $request->phone_number[$key],
                    'extension' => $request->phone_extension[$key]
                ]));
            }
        }


        /** Se elimina la relacion y los documentos previos **/
        $supp_docs = $supplier->documents()->get();
        if (count($supp_docs) > 0) {
            foreach ($supp_docs as $value) {
                $upDoc->deleteDoc(
                    $value->file,
                    'documents'
                );
                $value->delete();
            }
        }

        /** Registro y asociación de documentos */
        $documentFormat = ['doc', 'docx', 'pdf', 'odt'];
        if ($request->file('docs')) {
            foreach ($request->file('docs') as $file) {
                
                $extensionFile = $file->getClientOriginalExtension();

                if (in_array($extensionFile, $documentFormat)) {
                    $upDoc->uploadDoc(
                        $file,
                        'documents',
                        PurchaseSupplier::class,
                        $supplier->id
                    );
                }
            }
        }

        session()->flash('message', ['type' => 'store']);

        return redirect()->route('purchase.suppliers.index');
    }

    /**
     * Remove the specified resource from storage.
     * @return Renderable
     */
    public function destroy($id)
    {
        /**
         * Objeto con la información asociada al modelo PurchaseSupplier
         * @var Object $supplier
         */
        $supplier = PurchaseSupplier::with('purchaseOrder')->find($id);

        if ($supplier && count($supplier->purchaseOrder) > 0) {
            return response()->json([
                'error'   => true,
                'message' => 'El registro no se puede eliminar, debido a que esta siendo usado por ordenes de compra.'
            ], 200);
        }
        if ($supplier) {
            /** Se elimina la relacion de proveedor con los contactos anteriores **/
            $supp_contacts = $supplier->contacts()->get();
            if (count($supp_contacts) > 0) {
                foreach ($supp_contacts as $value) {
                    $value->delete();
                }
            }

            /** Se elimina la relacion de proveedor con los telefonos anteriores **/
            $supp_ph = $supplier->phones()->get();
            if (count($supp_ph) > 0) {
                foreach ($supp_ph as $value) {
                    $value->delete();
                }
            }

            /** Se elimina la relacion y los documentos previos **/
            $supp_docs = $supplier->documents()->get();
            if (count($supp_docs) > 0) {
                foreach ($supp_docs as $value) {
                    $upDoc->deleteDoc($value->file, 'documents');
                    $value->delete();
                }
            }
            $supplier->delete();
        }
        return response()->json(['records' => PurchaseSupplier::orderBy('id')->get(),
        'message'=>'Success'], 200);
    }

    /**
     * Obtiene listado de registros
     *
     * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     * @return \Illuminate\Http\JsonResponse
     */
    public function vueList()
    {
        return response()->json(['records' => PurchaseSupplier::all()], 200);
    }
}
