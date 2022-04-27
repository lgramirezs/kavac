<?php
/** [descripción del namespace] */
namespace Modules\Asset\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Repositories\ReportRepository;
use App\Models\Profile;
use App\Models\Institution;

use Modules\Asset\Models\AssetInventory;

use Auth;

/**
 * @class AssetInventoryReportController
 * @brief Controlador para la emision de un pdf
 *
 * Clase que gestiona de la emision de un pdf
 *
 * @author Daniel Contreras <dcontreras@cenditel.gob.ve | exodiadaniel@gmail.com>
 * @copyright <a href='http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/'>
 *                LICENCIA DE SOFTWARE CENDITEL</a>
 */
class AssetInventoryReportController extends Controller
{
    /**
     * Define la configuración de la clase
     *
     * @author Daniel Contreras <dcontreras@cenditel.gob.ve | exodiadaniel@gmail.com>
     */
    public function __construct()
    {
        /** Establece permisos de acceso para cada método del controlador */
    }

        /**
     * vista en la que se genera la emisión de la factura en pdf
     *
     * @author Daniel Contreras <dcontreras@cenditel.gob.ve | exodiadaniel@gmail.com>
     * @param Int $id id de la factura
    */
    public function pdf($type, $code)
    {

        // Validar acceso para el registro

        $is_admin = auth()->user()->isAdmin();


        $assets = AssetInventory::where('code', $code)->with(['assetInventoryAssets' => function ($query) {
            $query->with(['asset' => function ($query) {
                $query->with(['institution', 'assetCondition', 'assetStatus',
                'assetAsignationAsset' => function ($query) {
                    $query->with(['assetAsignation' => function ($query){
                        $query->with('payrollStaff');
                    }]);
                },
                'assetDisincorporationAsset' => function ($query) {
                    $query->with(['assetDisincorporation' => function ($query) {
                        $query->with('assetDisincorporationMotive');
                    }]);
                }]);
            }]);
        }])->get();


        if (!auth()->user()->isAdmin()) {
            if ($requirement && $requirement->queryAccess($user_profile['institution']['id'])) {
                return view('errors.403');
            }
        }

        /**
         * [$pdf base para generar el pdf]
         * @var [Modules\Accounting\Pdf\Pdf]
         */
        $pdf = new ReportRepository();

        /*
         *  Definicion de las caracteristicas generales de la página pdf
         */
        $institution = null;

        /*
         *  Definicion de las caracteristicas generales de la página pdf
         */
        if (auth()->user()->isAdmin()) {
            $institution = Institution::first();
        } else {
            $institution = Institution::find($user_profile->institution->id);
        }

        $pdf->setConfig(['institution' => Institution::first()]);
        $pdf->setHeader('Reporte de Historial de Inventario de Bienes');
        $pdf->setFooter(true, $institution->name);
        $pdf->setBody('asset::pdf.asset_inventario', true, [
            'pdf'         => $pdf,
            'assets' => $assets
        ]);
    }

}