<?php

namespace Modules\Asset\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Auth;
use App\Models\CodeSetting;
use Modules\Asset\Models\AssetAsignationAsset;
use Modules\Asset\Models\AssetAsignation;
use Modules\Asset\Models\Asset;
use Modules\Asset\Models\AssetAsignationDelivery;
use App\Models\Profile;
use App\Repositories\ReportRepository;
use App\Models\Institution;
use Attribute;
use Psr\Http\Message\ResponseInterface;

/**
 * @class      AssetAsignationController
 * @brief      Controlador de asignaciones de bienes institucionales
 *
 * Clase que gestiona las asignaciones de bienes institucionales
 *
 * @author     Henry Paredes <hparedes@cenditel.gob.ve>
 * @license
 *     [LICENCIA DE SOFTWARE CENDITEL](http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/)
 */
class AssetAsignationController extends Controller
{
    use ValidatesRequests;

    /**
     * Define la configuración de la clase
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @author    Yennifer Ramirez <yramirez@cenditel.gob.ve>
     */
    public function __construct()
    {
        /** Establece permisos de acceso para cada método del controlador */
        $this->validateRules = [
            'location_place' => ['required']
        ];

        /** Define los mensajes de validación para las reglas del formulario */
        $this->messages = [
            'location_place.required'    => 'El campo lugar de ubicación es obligatorio.'

        ];
    }

    /**
     * Muestra el listado de las asignaciones de bienes institucionales
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @return    Renderable
     */
    public function index()
    {
        return view('asset::asignations.list');
    }

    /**
     * Muestra el formulario para registrar una nueva asignación de bienes institucionales
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @return    Renderable
     */
    public function create()
    {
        return view('asset::asignations.create');
    }

    /**
     * Muestra el formulario para registrar una nueva asignación de un bien nstitucional
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @param     Integer                  $id    Identificador único del bien a asignar
     * @return    Renderable
     */
    public function assetAssign($id)
    {
        $asset = Asset::find($id);
        return view('asset::asignations.create', compact('asset'));
    }

    /**
     * Valida y registra una nueva asignación de bienes institucionales
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @param     \Illuminate\Http\Request         $request    Datos de la petición
     * @return    \Illuminate\Http\JsonResponse    Objeto con los registros a mostrar
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->validateRules, $this->messages);

        $codeSetting = CodeSetting::where('table', 'asset_asignations')->first();
        if (is_null($codeSetting)) {
            $request->session()->flash('message', [
                'type' => 'other', 'title' => 'Alerta', 'icon' => 'screen-error', 'class' => 'growl-danger',
                'text' => 'Debe configurar previamente el formato para el código a generar'
            ]);
            return response()->json(['result' => false, 'redirect' => route('asset.setting.index')], 200);
        }

        $code  = generate_registration_code(
            $codeSetting->format_prefix,
            strlen($codeSetting->format_digits),
            (strlen($codeSetting->format_year) == 2) ? date('y') : date('Y'),
            $codeSetting->model,
            $codeSetting->field
        );

        /**
         * Objeto asociado al modelo AssetAsignation
         *
         * @var Object $asignation
         */
        $asignation = AssetAsignation::create([
            'code' => $code,
            'department_id' => $request->input('department_id'),
            'institution_id' => $request->input('institution_id'),
            'payroll_staff_id' => $request->input('payroll_staff_id'),
            'location_place' => $request->input('location_place'),
            'state' => 'Asignado',
            'ids_assets_delivered' => null,
            'user_id' => Auth::id()
        ]);

        foreach ($request->assets as $product) {
            $asset = Asset::find($product);
            $asset->asset_status_id = 1;
            $asset->save();

            /**
             * Objeto asociado al modelo AssetAsignationAsset
             *
             * @var Object $asset_asignation
             */
            $asset_asignation = AssetAsignationAsset::create([
                'asset_id' => $asset->id,
                'asset_asignation_id' => $asignation->id,
            ]);
        }
        $request->session()->flash('message', ['type' => 'store']);
        return response()->json(['result' => true, 'redirect' => route('asset.asignation.index')], 200);
    }

    /**
     * Muestra el formulario para actualizar la información de las asignaciones de bienes institucionales
     *
     * @author     Henry Paredes <hparedes@cenditel.gob.ve>
     * @param      \Modules\Asset\Models\AssetAsignation    $asignation    Datos de la asignación de un bien
     * @return     Renderable
     */
    public function edit($id)
    {
        $asignation = AssetAsignation::find($id);
        return view('asset::asignations.create', compact('asignation'));
    }

    /**
     * Actualiza la información de las asignaciones de bienes institucionales
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @param     \Illuminate\Http\Request     $request    Datos de la petición
     * @param     Integer                      $id         Identificador único de la asignación
     * @return    \Illuminate\Http\JsonResponse    JSON con los registros a mostrar
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, $this->validateRules, $this->messages);

        $asignation = AssetAsignation::where('id', $id)->with('assetAsignationAssets')->first();
        $asignation->payroll_staff_id = $request->payroll_staff_id;
        $asignation->location_place = $request->location_place;
        $asignation->ids_assets_delivered = null;
        $asignation->save();

        /** Se eliminan los demas elementos de la solicitud */
        $assets_asignation = AssetAsignationAsset::where('asset_asignation_id', $asignation->id)->get();

        foreach ($assets_asignation as $asset_asignation) {
            $asset = Asset::find($asset_asignation->asset_id);
            $asset->asset_status_id = 10;
            $asset->save();

            $asset_asignation->delete();
        }
        /** Se agregan los nuevos elementos a la solicitud */
        foreach ($request->assets as $asset_id) {
            $asset = Asset::find($asset_id);
            $asset->asset_status_id = 1;
            $asset->save();

            /**
             * Objeto asociado al modelo AssetAsignationAsset
             *
             * @var Object $asset_asignation
             */
            $asset_asignation = AssetAsignationAsset::updateOrCreate([
                'asset_id' => $asset->id,
                'asset_asignation_id' => $asignation->id,
            ]);
        }

        $request->session()->flash('message', ['type' => 'update']);
        return response()->json(['result' => true, 'redirect' => route('asset.asignation.index')], 200);
    }

    /**
     * Elimina una asignación de un bien institucional
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @param     \Modules\Asset\Models\AssetAsignation    $asignation    Datos de la asignación de un bien
     * @return    \Illuminate\Http\JsonResponse            Objeto con los registros a mostrar
     */
    public function destroy(AssetAsignation $asignation)
    {
        $assets_asignation_assets = AssetAsignationAsset::where('asset_asignation_id', $asignation->id)->get();
        $assets_asignation_delivery = AssetAsignationDelivery::where('asset_asignation_id', $asignation->id);
        foreach($assets_asignation_assets as $assets_asignation){
            $asset = Asset::find($assets_asignation->asset_id);
            $asset->asset_status_id = 10;
            $asset->save();

            $assets_asignation->delete();
        }
        
        $assets_asignation_delivery->delete();
        $asignation->delete();
        return response()->json(['message' => 'destroy', 'redirect' => route('asset.asignation.index')], 200);
    }

    /**
     * Obtiene la información de la asignación de un bien institucional
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @param     \Modules\Asset\Models\AssetAsignation    $asignation    Datos de la asignación de un bien
     * @return    \Illuminate\Http\JsonResponse            Objeto con los registros a mostrar
     */
    public function vueInfo($id)
    {
        $ids = explode(',', $id);
        if (count($ids) > 1) {
            $asignation = AssetAsignation::whereIn('id', $ids)
                ->with(['payrollStaff', 'institution' => function($query){
                    $query->with(['fiscalYears' => function($query){
                        $query->where(['active' => true])->first();
                    }, 
                    'municipality' => function($query){
                            $query->with('estate');
                    }]);
                }
                , 'assetAsignationAssets' =>
                function ($query) {
                    $query->with(
                        ['asset' => function ($query) {
                            $query->with(
                                'institution',
                                'assetType',
                                'assetCategory',
                                'assetSubcategory',
                                'assetSpecificCategory',
                                'assetAcquisitionType',
                                'assetCondition',
                                'assetStatus',
                                'assetUseFunction'
                            );
                        }]
                    );
                }])->get();
        } else {
            $asignation = AssetAsignation::where('id', $id)
                ->with(['payrollStaff', 'institution' => function($query){
                    $query->with(['fiscalYears' => function($query){
                        $query->where(['active' => true])->first();
                    }, 
                    'municipality' => function($query){
                            $query->with('estate');
                    }]);
                }
                , 'assetAsignationAssets' =>
                function ($query) {
                    $query->with(
                        ['asset' => function ($query) {
                            $query->with(
                                'institution',
                                'assetType',
                                'assetCategory',
                                'assetSubcategory',
                                'assetSpecificCategory',
                                'assetAcquisitionType',
                                'assetCondition',
                                'assetStatus',
                                'assetUseFunction'
                            );
                        }]
                    );
                }])->first();
        }

        return response()->json(['records' => $asignation], 200);
    }

    /**
     * Actualiza el estado de una solicitud de entrega
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @param     \Illuminate\Http\Request         $request    Datos de la petición
     * @param     Integer                          $id         Identificador único de la asignación
     * @return    \Illuminate\Http\JsonResponse    Objeto con los registros a mostrar
     */
    public function deliver(Request $request, $id)
    {   
        $asset_asignation = AssetAsignation::find($id);
        $asset_asignation->state = 'Procesando entrega';

        if(isset($asset_asignation->ids_assets)){
            $asset_asignation->ids_assets = json_decode($asset_asignation->ids_assets); 
            $asset_asignation->ids_assets->assigned = $request->equipments['assigned'];
            $asset_asignation->ids_assets->possible_deliveries = $request->equipments['possible_deliveries'];

            $asset_asignation->ids_assets = json_encode($asset_asignation->ids_assets);
        }else{
            $asset_asignation->ids_assets = json_encode($request->equipments);
        }
        $asset_asignation->save();

        $asignation_delivery = AssetAsignationDelivery::create([
            'state' => 'Pendiente',
            'asset_asignation_id' => $asset_asignation->id,
            'user_id' => Auth::id(),
        ]);

        $request->session()->flash('message', ['type' => 'update']);
        return response()->json(['result' => true, 'redirect' => route('asset.asignation.index')], 200);
    }
    /**
     * Otiene un listado de las asignaciones registradas
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @return    \Illuminate\Http\JsonResponse    Objeto con los registros a mostrar
     */
    public function vueList()
    {
        $user_profile = Profile::where('user_id', auth()->user()->id)->first();
        $institution_id = isset($user_profile->institution_id)
            ? $user_profile->institution_id
            : null;

        if (Auth()->user()->isAdmin()) {
            $assetAsignations = AssetAsignation::with(['payrollStaff', 
                                                        'institution' => function($query){
                                                            $query->with(['fiscalYears' => function($query){
                                                                $query->where(['active' => true])->first();
                                                            }, 
                                                            'municipality' => function($query){
                                                                    $query->with('estate');
                                                            }]);
                                                        }])->orderBy('id')->get();
        } else {
            $assetAsignations = AssetAsignation::where('institution_id', $institution_id)
                ->with(['payrollStaff', 'institution' => function($query){
                    $query->with(['fiscalYears'
                    , 'municipality' => function($query){
                            $query->with('estate');
                    }]);
                }])->orderBy('id')->get();
        }

        return response()->json(['records'  => $assetAsignations], 200); 
    }

    public function managePdf(Request $request)
    {
        //dd(json_decode($request->data));
        //dd(json_decode($request->data, true));

        // Validar acceso para el registro

        // $asignation = AssetAsignation::where('id', $id)
        //         ->with(['assetAsignationAssets' =>
        //         function ($query) {
        //             $query->with(
        //                 ['asset' => function ($query) {
        //                     $query->with(
        //                         'institution',
        //                         'assetType',
        //                         'assetCategory',
        //                         'assetSubcategory',
        //                         'assetSpecificCategory',
        //                         'assetAcquisitionType',
        //                         'assetCondition',
        //                         'assetStatus',
        //                         'assetUseFunction'
        //                     );
        //                 }]
        //             );
        //         }])->first();

        $user_profile = Profile::find(auth()->user()->id);

        // if (!auth()->user()->isAdmin) {
        //     if ($requirement && $requirement->queryAccess($user_profile['institution']['id'])) {
        //         return view('errors.403');
        //     }
        // }

        /**
         * [$pdf base para generar el pdf]
         */
        $pdf = new ReportRepository();

        /*
         *  Definicion de las caracteristicas generales de la página pdf
         */
        $institution = null;

        /*
         *  Definicion de las caracteristicas generales de la página pdf
         */
        if (!auth()->user()->isAdmin) {
            $institution = Institution::find($user_profile->institution->id);
        }

        $pdf->setConfig(['institution' => $institution]);
        $pdf->setHeader($title = '',
                        $subTitle = 'Bienes asignados',
                        $hasQR = false,
                        $hasBarCode = false,
                        $logoAlign = 'L',
                        $titleAlign = 'L',
                        $subTitleAlign = 'L');
        $pdf->setFooter(false, $institution->name);
        $pdf->setBody('asset::pdf.asset_acta', true, [
            'pdf'       => $pdf,
            'request'    => json_decode($request->data, true),
        ],'D');
        $pdf->show($file = 'acta_de_asignacion_de_bienes.pdf', $outputMethod = 'FI');
    }
}
