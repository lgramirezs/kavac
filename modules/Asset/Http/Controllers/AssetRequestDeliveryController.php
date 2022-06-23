<?php

namespace Modules\Asset\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Auth;
use Modules\Asset\Models\AssetRequest;
use Modules\Asset\Models\AssetRequestAsset;
use Modules\Asset\Models\AssetRequestDelivery;

/**
 * @class      AssetRequestDeliveryController
 * @brief      Controlador de las solicitudes de entrega de equipos
 *
 * Clase que gestiona las solicitudes de entrega de equipos
 *
 * @author     Henry Paredes <hparedes@cenditel.gob.ve>
 * @license
 *     [LICENCIA DE SOFTWARE CENDITEL](http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/)
 */
class AssetRequestDeliveryController extends Controller
{
    use ValidatesRequests;

    /**
     * Muestra un listado de las solicitudes de entrega bienes institucionales
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @return    \Illuminate\Http\JsonResponse    Objeto con los registros a mostrar
     */
    public function index($perPage = 10, $page = 1)
    {
        $assetRequestDelivery = AssetRequestDelivery::with('assetRequest', 'user');
        
        $total = $assetRequestDelivery->count();
        $assetRequestDelivery = $assetRequestDelivery->offset(($page - 1) * $perPage)->limit($perPage)->get();
        $lastPage = max((int) ceil($total / $perPage), 1);
        
        return response()->json(
            [
                'records'  => $assetRequestDelivery,
                'total'    => $total,
                'lastPage' => $lastPage,
            ],
            200
        );
    }

    /**
     * Valida y registra una nueva solicitud de entrega
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @param     \Illuminate\Http\Request         $request    Datos de la petición
     * @return    \Illuminate\Http\JsonResponse    Objeto con los registros a mostrar
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'asset_request_id' => ['required']
        ]);

        /**
         * Objeto asociado al modelo AssetRequestDelivery
         *
         * @var Object $request_delivery
         */
        $request_delivery = AssetRequestDelivery::create([
            'state' => 'Pendiente',
            'asset_request_id' => $request->input('asset_request_id'),
            'user_id' => Auth::id(),
        ]);

        $request->session()->flash('message', ['type' => 'store']);
        return response()->json(['result' => true, 'redirect' => route('asset.request.index')], 200);
    }

    /**
     * Actualiza la información de las solicitudes de entrega de bienes institucionales
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @param     \Illuminate\Http\Request                     $request     Datos de la petición
     * @param     Modules\Asset\Models\AssetRequestDelivery    $delivery    Datos de la solicitud
     * @return    \Illuminate\Http\JsonResponse                Objeto con los registros a mostrar
     */
    public function update(Request $request, AssetRequestDelivery $delivery)
    {
        $this->validate($request, [
            'state' => ['required'],
            'asset_request_id' => ['required']
        ]);

        $delivery->state = $request->input('state');
        $delivery->observation = $request->input('observation');
        $delivery->save();
        if ($request->state == 'Aprobado') {
            $asset_request = AssetRequest::find($request->asset_request_id);
            $asset_request->state = 'Entregados';
            $asset_request->save();

            $assets_requested = AssetRequestAsset::where('asset_request_id', $asset_request->id)->get();

            foreach ($assets_requested as $requested) {
                $asset = $requested->asset;
                $asset->asset_status_id = 10;
                $asset->save();
                
                //$requested->delete();
            }
        } elseif ($request->state == 'Rechazado') {
            $asset_request = AssetRequest::find($request->asset_request_id);
            $asset_request->state = 'Pendiente por entrega';
            $asset_request->save();
        }

        $request->session()->flash('message', ['type' => 'update']);
        return response()->json(['result' => true, 'redirect' => route('asset.request.index')], 200);
    }

    /**
     * Elimina una solicitud de entrega
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @param     Modules\Asset\Models\AssetRequestDelivery    $delivery    Datos de la solicitud
     * @return    \Illuminate\Http\JsonResponse                Objeto con los registros a mostrar
     */
    public function destroy(AssetRequestDelivery $delivery)
    {
        if ($delivery->state == 'Pendiente') {
            $asset_request = AssetRequest::find($delivery->asset_request_id);
            $asset_request->state = 'Pendiente por entrega';
            $asset_request->save();
        }
        $delivery->delete();
        return response()->json(['message' => 'Success'], 200);
    }
}
