<?php

namespace Modules\Asset\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Repositories\UploadDocRepository;
use Illuminate\Support\Facades\Auth;
use App\Models\CodeSetting;
use Session;
use Modules\Asset\Models\AssetRequestDelivery;
use Modules\Asset\Models\AssetRequestExtension;
use Modules\Asset\Models\AssetRequestAsset;
use Modules\Asset\Models\AssetRequest;
use Modules\Asset\Models\Asset;
use App\Models\Profile;

/**
 * @class      AssetRequestController
 * @brief      Controlador de las solicitudes de bienes institucionales
 *
 * Clase que gestiona las solicitudes de bienes institucionales
 *
 * @author     Henry Paredes <hparedes@cenditel.gob.ve>
 * @license
 *     [LICENCIA DE SOFTWARE CENDITEL](http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/)
 */
class AssetRequestController extends Controller
{
    use ValidatesRequests;

    /** @var array Lista de elementos a mostrar */
    protected $data = [];

    /**
     * Define la configuración de la clase
     *
     * @author Henry Paredes <hparedes@cenditel.gob.ve>
     */
    public function __construct()
    {

        /** Establece permisos de acceso para cada método del controlador */
        $this->middleware('permission:asset.request.list', ['only' => 'index']);
        $this->middleware('permission:asset.request.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:asset.request.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:asset.request.delete', ['only' => 'destroy']);

        $this->data[0] = [
            'id' => '',
            'text' => 'Seleccione...'
        ];

        $this->customAttributes = [
            'code'              => 'código', 
            'type'              => 'tipo de solicitud', 
            'motive'            => 'motivo de la solicitud', 
            'state'             => 'estado', 
            'delivery_date'     => 'fecha de entreg',
            
            'country_id'        => 'país',
            'estate_id'         => 'estado',
            'municipality_id'   => 'municipio',
            'parish_id'         => 'parroquia',
            'address'           => 'dirección',

            'agent_name'        => 'nombre del agente externo', 
            'agent_telf'        => 'teléfono del agente externo', 
            'agent_email'       => 'correo del agente externo', 
        ];
    }

    /**
     * Muestra un listado de las solicitudes de bienes institucionales
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @return    Renderable
     */
    public function index()
    {
        return view('asset::requests.list');
    }

    /**
     * Muestra el formulario para registrar una nueva solicitud
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @return    Renderable
     */
    public function create()
    {
        return view('asset::requests.create');
    }

    /**
     * Valida y registra una nueva solicitud de bienes institucionales
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @param     \Illuminate\Http\Request                 $request    Datos de la petición
     * @param     \App\Repositories\UploadDocRepository    $upDoc      Instancia del documento a subir
     * @return    \Illuminate\Http\JsonResponse            Objeto con los registros a mostrar
     */
    public function store(Request $request, UploadDocRepository $upDoc)
    {
        $this->validate($request, [
            'type'       => ['required'],
            'motive'        => ['required'],
            'delivery_date' => ['required'],
            'files.*'       => ['max:5000', 'mimes:pdf,docx,doc,odt']
        ], [
            'type.required' => 'El campo tipo de solicitud es obligatorio.',
            'motive.required' => 'El campo motivo de la solicitud es obligatorio.',
            'delivery_date.required' => 'El campo fecha de entrega es obligatorio.',
        ], $this->customAttributes);

        if ($request->type == 2) {
            $this->validate($request, [
                'country_id'        => ['required'],
                'estate_id'         => ['required'],
                'municipality_id'   => ['required'],
                'parish_id'         => ['required'],
                'address'           => ['required'],
            ], [], $this->customAttributes);
        }
        if ($request->type == 3) {
            $this->validate($request, [
                'country_id'        => ['required'],
                'estate_id'         => ['required'],
                'municipality_id'   => ['required'],
                'parish_id'         => ['required'],
                'address'           => ['required'],
                'agent_name' => ['required', 'regex:/^[\D][a-zA-ZÁ-ÿ\s]*/', 'max:100'],
                'agent_telf' => ['required', 'regex:/[0-9\-]*/'],
                'agent_email' => ['required'],
            ], [], $this->customAttributes);
        }

        $codeSetting = CodeSetting::where('table', 'asset_requests')->first();
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

        $user_profile = Profile::where('user_id', auth()->user()->id)->first();
        $institution_id = isset($user_profile->institution_id)
            ? $user_profile->institution_id
            : null;

        /**
         * Objeto asociado al modelo AssetRequest
         *
         * @var Object $asset_request
         */
        $asset_request = AssetRequest::create([
            'code' => $code,
            'type' => $request->type,
            'motive' => $request->motive,
            'state' => 'Pendiente',
            'delivery_date' => $request->delivery_date,
            'country_id'        => $request->country_id,
            'estate_id'         => $request->estate_id,
            'municipality_id'   => $request->municipality_id,
            'parish_id'         => $request->parish_id,
            'address'           => $request->address,
            'agent_name' => $request->agent_name,
            'agent_telf' => $request->agent_telf,
            'agent_email' => $request->agent_email,
            'user_id' => Auth::id(),
            'institution_id' => $institution_id
        ]);

        $assets = explode(",", $request->assets);
        foreach ($assets as $asset_id) {
            $asset = Asset::find($asset_id);
            $asset->asset_status_id = 6;
            $asset->save();
            $asset_request_asset = AssetRequestAsset::create([
                'asset_id' => $asset->id,
                'asset_request_id' => $asset_request->id,
            ]);
        }

        if ($request->has('files')) {
            foreach ($request->file('files') as $file) {
                $upDoc->uploadDoc(
                    $file,
                    'documents',
                    AssetRequest::class,
                    $asset_request->id,
                    null,
                    false,
                    false,
                    true
                );
            }
        }

        $request->session()->flash('message', ['type' => 'store']);
        return response()->json(['result' => true, 'redirect' => route('asset.request.index')], 200);
    }

    /**
     * Muestra el formulario para actualizar la información de las solicitudes de bienes institucionales
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @param     Integer                          $id    Identificador único de la solicitud
     * @return    Renderable    Objeto con los datos a mostrar
     */
    public function edit($id)
    {
        $request = AssetRequest::find($id);
        return view('asset::requests.create', compact('request'));
    }

    /**
     * Actualiza la información de las solicitudes de bienes institucionales
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @param     \Illuminate\Http\Request         $request    Datos de la petición
     * @param     Integer                          $id         Identificador único de la solicitud
     * @return    \Illuminate\Http\JsonResponse    Objeto con los registros a mostrar
     */
    public function update(Request $request, $id)
    {
        $asset_request = AssetRequest::find($id);

        $this->validate($request, [
            'type' => ['required'],
            'motive' => ['required'],
            'delivery_date' => ['required'],
        ], [], $this->customAttributes);

        if ($request->type == 2) {
            $this->validate($request, [
                'country_id'        => ['required'],
                'estate_id'         => ['required'],
                'municipality_id'   => ['required'],
                'parish_id'         => ['required'],
                'address'           => ['required'],
            ], [], $this->customAttributes);
        }
        if ($request->type == 3) {
            $this->validate($request, [
                'country_id'        => ['required'],
                'estate_id'         => ['required'],
                'municipality_id'   => ['required'],
                'parish_id'         => ['required'],
                'address'           => ['required'],
                'agent_name'        => ['required'],
                'agent_telf'        => ['required'],
                'agent_email'       => ['required'],
            ], [], $this->customAttributes);
        }

        $asset_request->type = $request->type;
        $asset_request->motive = $request->motive;
        $asset_request->delivery_date = $request->delivery_date;
        $asset_request->country_id = $request->country_id;
        $asset_request->estate_id = $request->estate_id;
        $asset_request->municipality_id = $request->municipality_id;
        $asset_request->parish_id = $request->parish_id;
        $asset_request->address = $request->address;
        $asset_request->agent_name = $request->agent_name;
        $asset_request->agent_telf = $request->agent_telf;
        $asset_request->agent_email = $request->agent_email;
        $asset_request->save();

        /** Se eliminan los demas elementos de la solicitud */
        $asset_request_assets = AssetRequestAsset::where('asset_request_id', $asset_request->id)->get();

        foreach ($asset_request_assets as $asset_request_asset) {
            $asset = Asset::find($asset_request_asset->asset_id);
            $asset->asset_status_id = 10;
            $asset->save();

            $asset_request_asset->delete();
        }
       
        /** Se agregan los nuevos elementos a la solicitud */
        foreach ($request->assets as $asset_id) {
            $asset = Asset::find($asset_id);
            $asset->asset_status_id = 6;
            $asset->save();
            $requested = AssetRequestAsset::updateOrCreate([
                    'asset_id' => $asset->id,
                    'asset_request_id' => $asset_request->id,
                    
                ]);
        }
        

        $request->session()->flash('message', ['type' => 'update']);
        return response()->json(['result' => true, 'redirect' => route('asset.request.index')], 200);
    }

    /**
     * Elimina una solicitud de bienes institucionales
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @param     \Modules\Asset\Models\AssetRequest    $request    Datos de la solicitud de un bien
     * @return    \Illuminate\Http\JsonResponse         Objeto con los registros a mostrar
     */
    public function destroy(AssetRequest $request)
    {
        $assetRequestExtensions = AssetRequestExtension::where('asset_request_id', $request->id)->get();
        foreach ($assetRequestExtensions as $assetRequestExtension) {
            $assetRequestExtension->delete();
        }
        $assetRequestDeliveries = AssetRequestDelivery::where('asset_request_id', $request->id)->get();
        foreach ($assetRequestDeliveries as $assetRequestDelivery) {
            $assetRequestDelivery->delete();
        }

        $assets_request_assets = AssetRequestAsset::where('asset_request_id', $request->id)->get();
        foreach($assets_request_assets as $assets_request_asset){
            $asset = Asset::find($assets_request_asset->asset_id);
            $asset->asset_status_id = 10;
            $asset->save();

            $assets_request_asset->delete();
        }
        $request->delete();
        session()->flash('message', ['type' => 'destroy']);
        return response()->json(['redirect' => route('asset.request.index')], 200);
    }

    /**
     * Otiene un listado de las solicitudes registradas
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
            $assetRequests = AssetRequest::where('id', '>', 0)->get();
        } else {
            $assetRequests = AssetRequest::where('institution_id', $institution_id)->get();
        }
        
        return response()->json([ 'records'  => $assetRequests,],200);
    }

    /**
     * Otiene un listado de las solicitudes pendientes por ejecutar
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @return    \Illuminate\Http\JsonResponse    Objeto con los registros a mostrar
     */
    public function vuePendingList()
    {
        $user_profile = Profile::where('user_id', auth()->user()->id)->first();
        $institution_id = isset($user_profile->institution_id)
            ? $user_profile->institution_id
            : null;

        if (Auth()->user()->isAdmin()) {
            $assetRequests = AssetRequest::with('user')->where('state', 'Pendiente')->get();
        } else {
            $assetRequests = AssetRequest::with('user')
                ->where('institution_id', $institution_id)
                ->where('state', 'Pendiente')->get();
        }
        
        return response()->json(['records'  => $assetRequests,], 200 );
    }

    /**
     * Actualiza el estado de una solicitud a aprovado
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @param     \Illuminate\Http\Request         $request    Datos de la petición
     * @param     Integer                          $id         Identificador único de la solicitud
     * @return    \Illuminate\Http\JsonResponse    Objeto con los registros a mostrar
     */
    public function approved(Request $request, $id)
    {
        $asset_request = AssetRequest::find($id);
        $asset_request->state = 'Aprobado';
        $assets_requested = AssetRequestAsset::where('asset_request_id', $asset_request->id)->get();

        foreach ($assets_requested as $requested) {
            $asset = $requested->asset;
            $asset->asset_status_id = 1;
            $asset->save();
        }
        $asset_request->save();

        $request->session()->flash('message', ['type' => 'update']);
        return response()->json(['result' => true, 'redirect' => route('asset.request.index')], 200);
    }

    /**
     * Actualiza el estado de una solicitud a rechazado
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @param     \Illuminate\Http\Request         $request    Datos de la petición
     * @param     Integer                          $id         Identificador único de la solicitud
     * @return    \Illuminate\Http\JsonResponse    Objeto con los registros a mostrar
     */
    public function rejected(Request $request, $id)
    {
        $asset_request = AssetRequest::find($id);
        $asset_request->state = 'Rechazado';
        $assets_requested = AssetRequestAsset::where('asset_request_id', $asset_request->id)->get();

        foreach ($assets_requested as $requested) {
            $asset = $requested->asset;
            $asset->asset_status_id = 10;
            $asset->save();
        }
        $asset_request->save();

        $request->session()->flash('message', ['type' => 'update']);
        return response()->json(['result' => true, 'redirect' => route('asset.request.index')], 200);
    }

    /**
     * Actualiza el estado de una solicitud a entregado
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @param     \Illuminate\Http\Request         $request    Datos de la petición
     * @param     Integer                          $id         Identificador único de la solicitud
     * @return    \Illuminate\Http\JsonResponse    Objeto con los registros a mostrar
     */
    public function deliver(Request $request, $id)
    {
        $asset_request = AssetRequest::find($id);
        $asset_request->state = 'Procesando entrega';
        $asset_request->save();

        $request_delivery = AssetRequestDelivery::create([
            'state' => 'Pendiente',
            'asset_request_id' => $asset_request->id,
            'user_id' => Auth::id(),
        ]);

        $request->session()->flash('message', ['type' => 'update']);
        return response()->json(['result' => true, 'redirect' => route('asset.request.index')], 200);
    }

    /**
     * Obtiene la información de la solicitud registrada
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @param     Integer                          $id    Identificador único de la solicitud
     * @return    \Illuminate\Http\JsonResponse    Objeto con los registros a mostrar
     */
    public function vueInfo($id)
    {
        $asset_request = AssetRequest::where('id', $id)->with(
            ['assetRequestAssets' => function ($query) {
                $query->with('asset');
            }]
        )->first();

        return response()->json(['records' => $asset_request], 200);
    }

    /**
     * Obtiene el listado de tipos de eventos de los bienes institucionales a implementar en elementos select
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @return    Array Arreglo con los registros a mostrar
     */
    public function getTypes()
    {
        $this->data[1] = [
            'id' => 1,
            'text' => 'AVERIADO'
        ];
        $this->data[2] = [
            'id' => 2,
            'text' => 'PERDIDO'
        ];
        return response()->json($this->data);
    }

    /**
     * Obtiene el listado de los bienes institucionales asociados a una solicitud
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @param     Integer                          $id    Identificador único de la solicitud
     * @return    \Illuminate\Http\JsonResponse    Objeto con los registros a mostrar
     */
    public function getEquipments($id)
    {
        $assetRequest = AssetRequest::where('id', $id)->with(
            ['assetRequestAssets' => function ($query) {
                $query->with('asset');
            }]
        )->first();
        return $assetRequest->assetRequestAssets;
    }
}
