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
use App\Models\Profile;
use Attribute;

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
        
        foreach($assets_asignation_assets as $assets_asignation){
            $asset = Asset::find($assets_asignation->asset_id);
            $asset->asset_status_id = 10;
            $asset->save();

            $assets_asignation->delete();
        }
        
        $asignation->delete();
        return response()->json(['message' => 'destroy'], 200);
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
                ->with(['payrollStaff', 'assetAsignationAssets' =>
                function ($query) {
                    $query->with(
                        ['asset' => function ($query) {
                            $query->with(
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
                ->with(['payrollStaff', 'assetAsignationAssets' =>
                function ($query) {
                    $query->with(
                        ['asset' => function ($query) {
                            $query->with(
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
            $assetAsignations = AssetAsignation::with('payrollStaff')->get();
        } else {
            $assetAsignations = AssetAsignation::where('institution_id', $institution_id)
                ->with('payrollStaff')->get();
        }

        return response()->json(['records'  => $assetAsignations], 200); 
    }
}
