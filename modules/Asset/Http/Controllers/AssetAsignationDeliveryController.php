<?php
/** [descripción del namespace] */
namespace Modules\Asset\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Auth;
use Modules\Asset\Models\AssetAsignation;
use Modules\Asset\Models\AssetAsignationAsset;
use Modules\Asset\Models\AssetAsignationDelivery;

/**
 * @class AssetAsignationDeliveryController
 * @brief      Controlador de las solicitudes de entrega de equipos asignados
 *
 * Clase que gestiona las solicitudes de entrega de equipos asignados
 *
 * @author     Francisco J. P. Ruiz <fjpenya@cenditel.gob.ve / javierrupe19@gmail.com>
 * @license
 *     [LICENCIA DE SOFTWARE CENDITEL](http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/)
 */
class AssetAsignationDeliveryController extends Controller
{
    use ValidatesRequests;
    /**
     * Muestra un listado de las solicitudes de entrega bienes institucionales asignados
     *
     * @author  
     * @return    \Illuminate\Http\JsonResponse    Objeto con los registros a mostrar
     */
    public function index()
    { 
        return response()->json(['records' => AssetAsignationDelivery::with(['assetAsignation' => function ($query){
                                                                                $query->with('payrollStaff');
                                                                                } , 'user'])->get()], 200);
    }

    /**
     * Valida y registra una nueva solicitud de entrega de bienes institucionales asignados
     *
     * @author    
     * @param     \Illuminate\Http\Request         $request    Datos de la petición
     * @return    \Illuminate\Http\JsonResponse    Objeto con los registros a mostrar
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'asset_asignation_id' => ['required']
        ]);

        /**
         * Objeto asociado al modelo AssetAsignationDelivery
         *
         * @var Object $asignation_delivery
         */
        $asignation_delivery = AssetAsignationDelivery::create([
            'state' => 'Pendiente',
            'asset_request_id' => $request->input('asset_asignation_id'),
            'user_id' => Auth::id(),
        ]);

        $request->session()->flash('message', ['type' => 'store']);
        return response()->json(['result' => true, 'redirect' => route('asset.asignation.index')], 200);
    }

    /**
     * Actualiza la información de las solicitudes de entrega de bienes institucionales asignados
     *
     * @author   
     * @param     \Illuminate\Http\Request                     $request     Datos de la petición
     * @param     Modules\Asset\Models\AssetRequestDelivery    $delivery    Datos de la solicitud
     * @return    \Illuminate\Http\JsonResponse                Objeto con los registros a mostrar
     */
    public function update(Request $request, AssetAsignationDelivery $delivery)
    {
        $this->validate($request, [
            'state' => ['required'],
            'asset_asignation_id' => ['required']
        ]);

        $delivery->state = $request->input('state');
        $delivery->observation = $request->input('observation');
        $delivery->save();
        if ($request->state == 'Aprobado') {
            $asset_asignation = AssetAsignation::find($request->asset_asignation_id);
            $asset_asignation->state = 'Entregados';
            $asset_asignation->save();

            $assets_requested = AssetAsignationAsset::where('asset_asignation_id', $asset_asignation->id)->get();

            foreach ($assets_requested as $requested) {
                $asset = $requested->asset;
                $asset->asset_status_id = 10;
                $asset->save();
            }
        } elseif ($request->state == 'Rechazado') {
            $asset_asignation = AssetAsignation::find($request->asset_asignation_id);
            $asset_asignation->state = 'Pendiente por entrega';
            $asset_asignation->save();
        }

        $request->session()->flash('message', ['type' => 'update']);
        return response()->json(['result' => true, 'redirect' => route('asset.asignation.index')], 200);
    }

    /**
     * Elimina una solicitud de entrega de bienes institucionales asignados
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @param     Modules\Asset\Models\AssetRequestDelivery    $delivery    Datos de la solicitud
     * @return    \Illuminate\Http\JsonResponse                Objeto con los registros a mostrar
     */
    public function destroy($id)
    {
        $delivery = AssetAsignationDelivery::find($id);
       
        $asset_asignation = AssetAsignation::find($delivery->asset_asignation_id);
        $asset_asignation->state = 'Asignado';
        $asset_asignation->save();

        $delivery->delete();
        return response()->json(['message' => 'Success', 'redirect' => route('asset.asignation.index')], 200);
    }
}
