<?php

namespace Modules\Asset\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Modules\Asset\Jobs\AssetCreateAssets;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Asset\Exports\AssetExport;
use Modules\Asset\Imports\AssetImport;
use Modules\Asset\Rules\AcquisitionYear;
use Modules\Asset\Rules\RequiredItem;
use Modules\Asset\Models\AssetRequest;
use Modules\Asset\Models\AssetAsignation;
use Modules\Asset\Models\AssetDisincorporation;
use Modules\Asset\Models\AssetRequiredItem;
use Modules\Asset\Models\Asset;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;

/**
 * @class      AssetController
 * @brief      Controlador de bienes institucionales
 *
 * Clase que gestiona los bienes institucionales
 *
 * @author     Henry Paredes <hparedes@cenditel.gob.ve>
 * @license
 *     [LICENCIA DE SOFTWARE CENDITEL](http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/)
 */
class AssetController extends Controller
{
    use ValidatesRequests;

    /**
     * Arreglo con las reglas de validación sobre los datos de un formulario
     * @var Array $validateRules
     */
    protected $validateRules;

    /**
     * Arreglo con los mensajes para las reglas de validación
     * @var Array $messages
     */
    protected $messages;
    /**
     * Define la configuración de la clase
     * @author    Yennifer Ramirez <yramirez@cenditel.gob.ve>
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     */
    public function __construct()
    {
        /** Establece permisos de acceso para cada método del controlador */
        $this->validateRules = [
            'asset_type_id' => ['required'],
            'asset_category_id' => ['required'],
            'asset_subcategory_id' => ['required'],
            'asset_specific_category_id' => ['required'],
            'asset_acquisition_type_id' => ['required'],
            'acquisition_date' => [new AcquisitionYear(Date("Y"))],
            'asset_status_id' => ['required'],
            'asset_condition_id' => ['required'],
            'institution_id' => ['required'],
        ];

        /** Define los mensajes de validación para las reglas del formulario */
        $this->messages = [
            'institution_id.required'                       => 'El campo organización es obligatorio.',
            'asset_type_id.required'                        => 'El campo tipo de bien es obligatorio.',
            'asset_category_id.required'                    => 'El campo categoria general es obligatorio.',
            'asset_subcategory_id.required'                 => 'El campo subcategoria es obligatorio.',
            'asset_specific_category_id.required'           => 'El campo categoria especifica es obligatorio.',
            'asset_acquisition_type_id.required'            => 'El campo forma de adquisición es obligatorio.',
            'asset_status_id.required'                      => 'El campo estatus de uso es obligatorio.',
            'serial.required'                               => 'El campo serial es obligatorio.',
            'serial.unique'                                 => 'El campo serial ya existe',
            'marca.required'                                => 'El campo marca es obligatorio.',
            'model.required'                                => 'El campo modelo es obligatorio.',
            'value.regex'                                   => 'El formato de valor es inválido.',
            'asset_use_function_id.required'                => 'El campo función de uso es obligatorio.',
            'parish_id.required'                            => 'El campo país es obligatorio.',
            'address.required'                              => 'El campo dirección es obligatorio.',
            'asset_condition_id.required'                   => 'El campo condición física es obligatorio.',
            'asset_institutional_code.required'             => 'El campo código de bien organizacional es obligatorio.',
            'asset_institutional_code.unique'               => 'El campo código de bien organizacional ya existe',
        ];

        $this->attributes = [
            'value' => 'valor'
        ];
    }

    /**
     * Muestra un listado de los bienes institucionales
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @return    Renderable
     */
    public function index()
    {
        return view('asset::registers.list');
    }

    /**
     * Muestra el formulario para registrar un nuevo bien institucional
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @return    Renderable
     */
    public function create()
    {
        return view('asset::registers.create');
    }

    /**
     * Valida y registra un nuevo bien institucional
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @author    Yennifer Ramirez <yramirez@cenditel.gob.ve>
     * @param     \Illuminate\Http\Request         $request    Datos de la petición
     * @return    \Illuminate\Http\JsonResponse    Objeto con los registros a mostrar
     */
    public function store(Request $request)
    {
        $item_required = AssetRequiredItem::where('asset_specific_category_id', $request->asset_specific_category_id)
            ->first();

            $validateRules  = $this->validateRules;
            if($request->value){
                $validateRules  = array_merge(
                    $validateRules,
                    [
                        'value' => ['regex:/^\d+(\.\d+)?$/u']
                    ]
                );
            }
            if (!is_null($item_required)){
                if ($request->asset_type_id == 1) {
                    $validateRules  = array_merge(
                        $validateRules,
                        [
                            'serial' => [new RequiredItem($item_required->serial), 'unique:assets,serial'],
                            'marca'  => new RequiredItem($item_required->marca),
                            'model' => new RequiredItem($item_required->model),
                            'asset_institutional_code' => ['required', 'unique:assets,asset_institutional_code']
        
                        ]
                    );
                    $this->validate($request, $validateRules, $this->messages, $this->attributes);
                } elseif ($request->type_id == 2) {
                    $validateRules  = array_merge(
                        $validateRules,
                        [
                            'asset_use_function_id' => new RequiredItem($item_required->use_function),
                            'parish_id' => new RequiredItem($item_required->address),
                            'address' => new RequiredItem($item_required->address),
        
                        ]
                    );
                    $this->validate($request, $validateRules, $this->messages, $this->attributes);
                }
            } else {
                $this->validate($request, $this->validateRules, $this->messages, $this->attributes);
            }
            
        $asset = Asset::create([
            'asset_type_id'              => $request->asset_type_id,
            'asset_category_id'          => $request->asset_category_id,
            'asset_subcategory_id'       => $request->asset_subcategory_id,
            'asset_specific_category_id' => $request->asset_specific_category_id,
            'specifications'             => $request->specifications,
            'asset_condition_id'         => $request->asset_condition_id,
            'asset_acquisition_type_id'  => $request->asset_acquisition_type_id,
            'acquisition_date'           => $request->acquisition_date,
            'asset_status_id'            => $request->asset_status_id,
            'serial'                     => $request->serial,
            'marca'                      => $request->marca,
            'model'                      => $request->model,
            'value'                      => $request->value,
            'currency_id'                => $request->currency_id,
            'institution_id'             => $request->institution_id,
            'asset_use_function_id'      => $request->asset_use_function_id,
            'parish_id'                  => $request->parish_id,
            'address'                    => $request->address,
            'purchase_supplier_id'       => $request->purchase_supplier_id,
            'color'                      => $request->color,
            'asset_institutional_code'   => $request->asset_institutional_code

        ]);
        $asset->inventory_serial = $asset->getCode();
        $asset->save();

        $request->session()->flash('message', ['type' => 'store']);
        return response()->json(['result' => true, 'redirect' => route('asset.register.index')], 200);
    }

    /**
     * Muestra el formulario para actualizar la información de los bienes institucionales
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @author    Yennifer Ramirez <yramirez@cenditel.gob.ve>
     * @param     \Modules\Asset\Models\Asset    $asset    Datos del Bien
     * @return    Renderable
     */
    public function edit($id)
    {
        $asset = Asset::find($id);
        return view('asset::registers.create', compact('asset'));
    }

    /**
     * Actualiza la información de los bienes institucionales
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @param     \Illuminate\Http\Request         $request    Datos de la petición
     * @param     Integer                          $id         Identificador único del bien
     * @return    \Illuminate\Http\JsonResponse    Objeto con los registros a mostrar
     */
    public function update(Request $request, $id)
    {
        $asset = Asset::find($id);

        $validateRules  = $this->validateRules;
        if($request->value){
            $validateRules  = array_merge(
                $validateRules,
                [
                    'value' => ['regex:/^\d+(\.\d+)?$/u']
                ]
            );
        }
        if ($request->asset_type_id == 1) {
            $validateRules  = $this->validateRules;
            $validateRules  = array_merge(
                $validateRules,
                [
                    'serial' => ['required', 'unique:assets,serial'. $asset->id, 'max:50'],
                    'marca'  => ['required', 'max:50'],
                    'model' => ['required', 'max:50'],
                    'asset_institutional_code' => ['required', 'unique:assets,asset_institutional_code'.$asset->id]

                ]
            );
        } elseif ($request->type_id == 2) {
            $validateRules  = $this->validateRules;
            $validateRules  = array_merge(
                $validateRules,
                [
                    'asset_use_function_id' => ['required'],
                    'parish_id' => ['required'],
                    'address' => ['required'],

                ]
            );
        }

        $asset->update([
            'asset_type_id'              => $request->asset_type_id,
            'asset_category_id'          => $request->asset_category_id,
            'asset_subcategory_id'       => $request->asset_subcategory_id,
            'asset_specific_category_id' => $request->asset_specific_category_id,
            'specifications'             => $request->specifications,
            'asset_condition_id'         => $request->asset_condition_id,
            'asset_acquisition_type_id'  => $request->asset_acquisition_type_id,
            'acquisition_date'           => $request->acquisition_date,
            'asset_status_id'            => $request->asset_status_id,
            'serial'                     => $request->serial,
            'marca'                      => $request->marca,
            'model'                      => $request->model,
            'value'                      => $request->value,
            'currency_id'                => $request->currency_id,
            'institution_id'             => $request->institution_id,
            'asset_use_function_id'      => $request->asset_use_function_id,
            'parish_id'                  => $request->parish_id,
            'address'                    => $request->address,
            'purchase_supplier_id'       => $request->purchase_supplier_id,
            'color'                      => $request->color,
            'asset_institutional_code'   => $request->asset_institutional_code

        ]);

        $request->session()->flash('message', ['type' => 'update']);
        return response()->json(['result' => true, 'redirect' => route('asset.register.index')], 200);
    }

    /**
     * Elimina un bien institucional
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @param     \Modules\Asset\Models\Asset      $asset    Datos del Bien
     * @return    \Illuminate\Http\JsonResponse    Objeto con los registros a mostrar
     */
    public function destroy(Asset $asset)
    {
        $asset->delete();
        return response()->json(['message' => 'destroy'], 200);
    }

    /**
     * Obtiene la información del bien institucional registrado
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @param     \Modules\Asset\Models\Asset      $asset    Datos del bien institucional
     * @return    \Illuminate\Http\JsonResponse    Objeto con los registros a mostrar
     */
    public function vueInfo($id)
    {
        $asset = Asset::where('id', $id)->with(
            [
                'assetType',
                'assetCategory',
                'assetSubcategory',
                'assetSpecificCategory',
                'assetAcquisitionType',
                'assetCondition',
                'assetStatus',
                'assetUseFunction',
                'institution',
                'parish' => function ($query) {
                    $query->with(['municipality' => function ($query) {
                        $query->with(['estate' => function ($query) {
                            $query->with('country')->get();
                        }])->get();
                    }])->get();
                },
                'assetDisincorporationAsset' => function ($query) {
                    $query->with(['assetDisincorporation' => function ($query) {
                        $query->with('assetDisincorporationMotive')->get();
                    }])->get();
                }
            ])->first();
        
        return response()->json(['records' => $asset], 200);
    }

    /**
     * Otiene un listado de los bienes registradas
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @param     String                           $operation       Tipo de operación realizada
     * @param     Integer                          $operation_id    Identificador único de la operación
     * @return    \Illuminate\Http\JsonResponse    Objeto con los registros a mostrar
     */
    public function vueList($operation = null, $operation_id = null)
    {
        $user_profile = Profile::where('user_id', auth()->user()->id)->first();
        $institution_id = isset($user_profile->institution_id)
            ? $user_profile->institution_id
            : null;

        if ($operation == null) {
            if (Auth()->user()->isAdmin()) {
                $assets = Asset::with(['institution', 'assetCondition', 'assetStatus'
                                        , 'assetAsignationAsset' => function ($query){
                                            $query->with('assetAsignation');
                                            }
                                        , 'assetDisincorporationAsset' => function ($query) {
                                            $query->with(['assetDisincorporation' => function ($query) {
                                                $query->with('assetDisincorporationMotive');
                                            }]);}
                                        , 'assetRequestAsset' => function ($query){
                                            $query->with('assetRequest');
                                        }
                                        ])->orderBy('id');
            } else {
                $assets = Asset::where('institution_id', $institution_id)
                    ->with([
                        'institution',
                        'assetCondition',
                        'assetStatus',
                        'assetAsignationAsset' => function ($query){
                            $query->with('assetAsignation');
                        },
                        'assetDisincorporationAsset' => function ($query) {
                            $query->with(['assetDisincorporation' => function ($query) {
                                $query->with('assetDisincorporationMotive');
                            }]);
                        }, 
                        'assetRequestAsset' => function ($query){
                            $query->with('assetRequest');
                        }
                    ])->orderBy('id');
            }
        } elseif ($operation_id == null) {
            if ($operation == 'asignations' || $operation == 'requests') {
                if (Auth()->user()->isAdmin()) {
                    $assets_list = Asset::with(['institution', 'assetCondition', 'assetStatus'
                                        , 'assetAsignationAsset'=> function ($query){
                                            $query->with('assetAsignation');
                                            }
                                        , 'assetDisincorporationAsset'
                                        , 'assetRequestAsset' => function($query){
                                                $query->with('assetRequest');
                                        }])
                                        ->where('asset_condition_id', 1)->where('asset_status_id', 10)
                                        ->where('asset_type_id', 1)
                                        ->orderBy('id')->get();
                    
                    $selected = [];
                    foreach($assets_list as $asset_index){
                        if($asset_index->assetDisincorporationAsset == null){
                                if($asset_index->assetRequestAsset == null
                                    && $asset_index->assetAsignationAsset == null){
                                    array_push($selected, $asset_index->id);
                                }
                                elseif($asset_index->assetRequestAsset){
                                    if($asset_index->assetRequestAsset->assetRequest->state == 'Entregados'
                                        || $asset_index->assetRequestAsset->assetRequest->state == 'Rechazado'){
                                    array_push($selected, $asset_index->id);
                                    }
                                }
                                elseif($asset_index->assetAsignationAsset){
                                    if($asset_index->assetAsignationAsset->assetAsignation->state == 'Entregados'
                                        && $asset_index->assetAsignationAsset->assetAsignation->state == 'Entrega parcial'){
                                        array_push($selected, $asset_index->id);
                                    }
                                }
                            }    
                    }
                   
                    $assets = Asset::with('institution', 'assetCondition', 'assetStatus')
                                    ->whereIn('id', $selected)->orderBy('id');
                } else {
                    $assets_list = Asset::with(['institution', 'assetCondition', 'assetStatus'
                                        , 'assetAsignationAsset' => function ($query){
                                            $query->with('assetAsignation');
                                            }
                                        , 'assetDisincorporationAsset'
                                        , 'assetRequestAsset'=> function($query){
                                            $query->with('assetRequest');
                                        }])
                                        ->where('institution_id', $institution_id)
                                        ->where('asset_condition_id', 1)->where('asset_status_id', 10)
                                        ->where('asset_type_id', 1)
                                        ->orderBy('id')->get();
                    $selected = [];
                    foreach($assets_list as $asset_index){
                        if($asset_index->assetDisincorporationAsset == null){
                            if($asset_index->assetRequestAsset == null
                                && $asset_index->assetAsignationAsset == null){
                                array_push($selected, $asset_index->id);
                            }
                            elseif($asset_index->assetRequestAsset){
                                if($asset_index->assetRequestAsset->assetRequest->state == 'Entregados'
                                    || $asset_index->assetRequestAsset->assetRequest->state == 'Rechazado'){
                                array_push($selected, $asset_index->id);
                                }
                            }
                            elseif($asset_index->assetAsignationAsset){
                                if($asset_index->assetAsignationAsset->assetAsignation->state == 'Entregados'
                                    && $asset_index->assetAsignationAsset->assetAsignation->state == 'Entrega parcial'){
                                    array_push($selected, $asset_index->id);
                                }
                            }
                        }    
                    }
                   
                    $assets = Asset::with('institution', 'assetCondition', 'assetStatus')
                                    ->whereIn('id', $selected)->orderBy('id');
                }
            } elseif ($operation == 'disincorporations') {
                if (Auth()->user()->isAdmin()) {
                    
                    $assets_list = Asset::with(['institution', 'assetCondition', 'assetStatus'
                                        , 'assetAsignationAsset', 'assetDisincorporationAsset'
                                        , 'assetRequestAsset'=> function($query){
                                            $query->with('assetRequest');
                                        }])
                                        ->where('asset_type_id', 1)
                                        ->orderBy('id')->get();
                    
                    $selected = [];
                    foreach($assets_list as $asset_index){
                        if($asset_index->assetDisincorporationAsset == null){
                            if($asset_index->assetRequestAsset == null
                                && $asset_index->assetAsignationAsset == null){
                                array_push($selected, $asset_index->id);
                            }
                            elseif($asset_index->assetRequestAsset){
                                if($asset_index->assetRequestAsset->assetRequest->state == 'Entregados'
                                    || $asset_index->assetRequestAsset->assetRequest->state == 'Rechazado'){
                                array_push($selected, $asset_index->id);
                                }
                            }
                            elseif($asset_index->assetAsignationAsset){
                                if($asset_index->assetAsignationAsset->assetAsignation->state == 'Entregados'
                                    && $asset_index->assetAsignationAsset->assetAsignation->state == 'Entrega parcial'){
                                    array_push($selected, $asset_index->id);
                                }
                            }
                        }    
                    }
                   
                    $assets = Asset::with('institution', 'assetCondition', 'assetStatus')
                                    ->whereIn('id', $selected)->orderBy('id');
                   
                } else {
                    $assets_list = Asset::with(['institution', 'assetCondition', 'assetStatus'
                                        , 'assetAsignationAsset', 'assetDisincorporationAsset'
                                        , 'assetRequestAsset'=> function($query){
                                            $query->with('assetRequest');
                                        }])
                                        ->where('institution_id', $institution_id)
                                        ->where('asset_type_id', 1)
                                        ->orderBy('id')->get();
                    
                    $selected = [];
                    foreach($assets_list as $asset_index){
                        if($asset_index->assetDisincorporationAsset == null){
                            if($asset_index->assetRequestAsset == null
                                && $asset_index->assetAsignationAsset == null){
                                array_push($selected, $asset_index->id);
                            }
                            elseif($asset_index->assetRequestAsset){
                                if($asset_index->assetRequestAsset->assetRequest->state == 'Entregados'
                                    || $asset_index->assetRequestAsset->assetRequest->state == 'Rechazado'){
                                array_push($selected, $asset_index->id);
                                }
                            }
                            elseif($asset_index->assetAsignationAsset){
                                if($asset_index->assetAsignationAsset->assetAsignation->state == 'Entregados'
                                    && $asset_index->assetAsignationAsset->assetAsignation->state == 'Entrega parcial'){
                                    array_push($selected, $asset_index->id);
                                }
                            }
                        }    
                    }
                   
                    $assets = Asset::with('institution', 'assetCondition', 'assetStatus')
                                    ->whereIn('id', $selected)->orderBy('id');
                }
            } 
                // elseif ($operation == 'requests') {
            //     if (Auth()->user()->isAdmin()) {
            //         $assets = Asset::with('institution', 'assetCondition', 'assetStatus')->orderBy('id')
            //             ->where('asset_condition_id', 1)->where('asset_status_id', 10);
            //     } else {
            //         $assets = Asset::with('institution', 'assetCondition', 'assetStatus')->orderBy('id')
            //             ->where('institution_id', $institution_id)
            //             ->where('asset_condition_id', 1)->where('asset_status_id', 10);
            //     }
            // }
        } else {
            if ($operation == 'asignations') {
                $selected = [];
                $assetAsignationAssets = AssetAsignation::find($operation_id)->assetAsignationAssets()->get();
                foreach ($assetAsignationAssets as $assetAsignationAsset) {
                    array_push($selected, $assetAsignationAsset->asset_id);
                }
                if (Auth()->user()->isAdmin()) {
                    $assets = Asset::with('institution', 'assetCondition', 'assetStatus')->orderBy('id')
                        ->whereIn('id', $selected)
                        ->orWhere('asset_status_id', 10)
                        ->where('asset_condition_id', 1)
                        ->where('asset_type_id', 1);
                } else {
                    $assets = Asset::with('institution', 'assetCondition', 'assetStatus')->orderBy('id')
                        ->whereIn('id', $selected)
                        ->orWhere('asset_status_id', 10)
                        ->where('asset_condition_id', 1)
                        ->where('asset_type_id', 1)
                        ->where('institution_id', $institution_id);
                }
            } elseif ($operation == 'disincorporations') {
                $selected = [];
                $assets_list = Asset::with(['institution', 'assetCondition', 'assetStatus'
                                        , 'assetAsignationAsset', 'assetDisincorporationAsset'
                                        , 'assetRequestAsset'=> function($query){
                                            $query->with('assetRequest');
                                        }])
                                        ->where('asset_type_id', 1)
                                        ->orderBy('id')->get();

                foreach($assets_list as $asset_index){
                    if($asset_index->assetDisincorporationAsset == null){
                        if($asset_index->assetRequestAsset == null
                            && $asset_index->assetAsignationAsset == null){
                            array_push($selected, $asset_index->id);
                        }
                        elseif($asset_index->assetRequestAsset){
                            if($asset_index->assetRequestAsset->assetRequest->state == 'Entregados'
                                || $asset_index->assetRequestAsset->assetRequest->state == 'Rechazado'){
                            array_push($selected, $asset_index->id);
                            }
                        }
                        elseif($asset_index->assetAsignationAsset){
                            if($asset_index->assetAsignationAsset->assetAsignation->state == 'Entregados'
                                && $asset_index->assetAsignationAsset->assetAsignation->state == 'Entrega parcial'){
                                array_push($selected, $asset_index->id);
                            }
                        }
                    }     
                }
                   
                $assetDisincorporationAssets = AssetDisincorporation::find($operation_id)
                    ->assetDisincorporationAssets()->get();
                foreach ($assetDisincorporationAssets as $assetDisincorporationAsset) {
                    array_push($selected, $assetDisincorporationAsset->asset_id);
                }

                if (Auth()->user()->isAdmin()) {
                    $assets = Asset::with('institution', 'assetCondition', 'assetStatus')->orderBy('id')
                        ->whereIn('id', $selected);
                } else {
                    $assets = Asset::with('institution', 'assetCondition', 'assetStatus')->orderBy('id')
                        ->whereIn('id', $selected)
                        ->where('institution_id', $institution_id);
                }
            } elseif ($operation == 'requests') {
                $selected = [];
                $assetRequestAssets = AssetRequest::find($operation_id)->assetRequestAssets()->get();
                foreach ($assetRequestAssets as $assetRequestAsset) {
                    array_push($selected, $assetRequestAsset->asset_id);
                }
                if (Auth()->user()->isAdmin()) {
                    $assets = Asset::with('institution', 'assetCondition', 'assetStatus')->orderBy('id')
                        ->whereIn('id', $selected)
                        ->orWhere('asset_status_id', 10)
                        ->where('asset_condition_id', 1)
                        ->where('asset_type_id', 1);
                } else {
                    $assets = Asset::with('institution', 'assetCondition', 'assetStatus')->orderBy('id')
                        ->whereIn('id', $selected)
                        ->orWhere('asset_status_id', 10)
                        ->where('asset_condition_id', 1)
                        ->where('asset_type_id', 1)
                        ->where('institution_id', $institution_id);
                }
            }
        }
        
        return response()->json( ['records'  => $assets->get()], 200);
    }

    /**
     * Filtra por su código de clasificación los bienes registradas
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @param     \Illuminate\Http\Request         $request    Datos de la petición
     * @return    \Illuminate\Http\JsonResponse    Objeto con los registros a mostrar
     */
    public function searchClasification(Request $request, $perPage = 10, $page = 1)
    {
        $assets = Asset::CodeClasification(
            $request->asset_type,
            $request->asset_category,
            $request->asset_subcategory,
            $request->asset_specific_category
        )->with(['institution', 'assetCondition', 'assetStatus'
        , 'assetDisincorporationAsset' => function ($query) {
            $query->with(['assetDisincorporation' => function ($query) {
                $query->with('assetDisincorporationMotive');
            }]);
        }])->where('institution_id', $request->institution);

        if ($request->asset_status > 0) {
            $assets = $assets->where('asset_status_id', $request->asset_status);
        }

        $total = $assets->count();
        $assets = $assets->offset(($page - 1) * $perPage)->limit($perPage)->get();
        $lastPage = max((int) ceil($total / $perPage), 1);
        return response()->json(
            [
                'records'  => $assets,
                'total'    => $total,
                'lastPage' => $lastPage,
            ],
            200
        );
    }

    /**
     * Filtra por su fecha de registro los bienes registradas
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @author    Daniel Contreras <dcontreras@cenditel.gob.ve>
     * @param     \Illuminate\Http\Request         $request    Datos de la petición
     * @return    \Illuminate\Http\JsonResponse    Objeto con los registros a mostrar
     */
    public function searchGeneral(Request $request, $perPage = 10, $page = 1)
    {
        $assets = Asset::where('institution_id', $request->institution)
                                ->with(['institution', 'assetCondition', 'assetStatus'
                                , 'assetDisincorporationAsset' => function ($query) {
                                    $query->with(['assetDisincorporation' => function ($query) {
                                        $query->with('assetDisincorporationMotive');
                                    }]);
                                }]);

        if ($request->start_date || $request->end_date) {
            if ($request->start_date != '' && !is_null($request->start_date)) {
                if ($request->end_date != '' && !is_null($request->end_date)) {
                    $assets = $assets->whereBetween("created_at", [$request->start_date,$request->end_date]);
                } else {
                    $assets = $assets->whereBetween("created_at", [$request->start_date,now()]);
                }
            }
            if ($request->asset_status > 0) {
                $assets = $assets->where('asset_status_id', $request->asset_status);
            }
        } elseif ($request->year || $request->mes_id) {
            if ($request->mes_id != '' && !is_null($request->mes_id)) {
                if ($request->year != '' && !is_null($request->year)) {
                    $assets = $assets->whereMonth('created_at', $request->mes_id)
                                 ->whereYear('created_at', $request->year);
                } else {
                    $assets = $assets->whereMonth('created_at', $request->mes_id);
                }
            }

            if ($request->year != '' && !is_null($request->year) && $request->mes_id == '') {
                $assets = $assets->whereYear('created_at', $request->year);
            } else {
                $assets = $assets;
            }

            if ($request->asset_status > 0) {
                $assets = $assets->where('asset_status_id', $request->asset_status);
            }
        } else {
            if ($request->asset_status > 0) {
                $assets = Asset::with(['institution', 'assetCondition', 'assetStatus'
                                        , 'assetDisincorporationAsset' => function ($query) {
                                            $query->with(['assetDisincorporation' => function ($query) {
                                                $query->with('assetDisincorporationMotive');
                                            }]);
                                        }])
                                    ->where('asset_status_id', $request->asset_status)
                                    ->where('institution_id', $request->institution);
            } else {
                $assets = Asset::with(['institution', 'assetCondition', 'assetStatus'
                                        , 'assetDisincorporationAsset' => function ($query) {
                                            $query->with(['assetDisincorporation' => function ($query) {
                                                $query->with('assetDisincorporationMotive');
                                            }]);
                                        }])
                                    ->where('institution_id', $request->institution);
            }
        }

        $total = $assets->count();
        $assets = $assets->offset(($page - 1) * $perPage)->limit($perPage)->get();
        $lastPage = max((int) ceil($total / $perPage), 1);
        return response()->json(
            [
                'records'  => $assets,
                'total'    => $total,
                'lastPage' => $lastPage,
            ],
            200
        );
    }

    /**
     * Filtra por su ubicación en la institución los bienes registradas
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @param     \Illuminate\Http\Request         $request    Datos de la petición
     * @return    \Illuminate\Http\JsonResponse    Objeto con los registros a mostrar
     */
    public function searchDependence(Request $request)
    {
        /*
         *  Falta filtrar por dependencia solicitante
         *  Validar tambien para múltiples instituciones
         *
         */
        return response()->json(['records' => []], 200);
    }

    /**
     * Realiza la acción necesaria para exportar los datos del modelo Asset
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @return    object    Objeto que permite descargar el archivo con la información a ser exportada
     */
    public function export()
    {
        return Excel::download(new AssetExport, 'assets.xlsx');
    }

    /**
     * Realiza la acción necesaria para importar los datos suministrados en un archivo para el modelo Asset
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @return    object    Objeto que permite descargar el archivo con la información a ser exportada
     */
    public function import(Request $request)
    {
        Excel::import(new AssetImport, request()->file('file'));
        return response()->json(['result' => true], 200);
    }
}
