<?php

namespace Modules\Sale\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;

use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Rules\CodeSetting as CodeSettingRule;
use App\Models\CodeSetting;

class SaleSettingController extends Controller
{
    use ValidatesRequests;

    /**
     * Define la configuración de la clase
     *
     * @author Daniel Contreras <dcontreras@cenditel.gob.ve>
     */
    public function __construct()
    {
        /** Establece permisos de acceso para cada método del controlador */
        $this->middleware('permission:sale.setting', ['only' => 'index']);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $codeSettings = CodeSetting::where('module', 'sale')->get();
        $pdCode = $codeSettings->where('table', 'sale_warehouse_inventory_products')->first();
        $mvCode = $codeSettings->where('table', 'sale_warehouse_movements')->first();
        $billCode = $codeSettings->where('table', 'sale_bills')->first();
        $serviceCode = $codeSettings->where('table', 'sale_services')->first();
        $saleWarehouseReportCode = $codeSettings->where('table', 'sale_warehouse_reports')->first();

        return view(
            'sale::settings', compact('codeSettings', 'pdCode', 'mvCode', 'billCode', 'serviceCode', 'saleWarehouseReportCode')
        );
        //return view('sale::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        //   return view('sale::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        /** Reglas de validación para la configuración de códigos */
        $this->validate($request, [
            'products_code' => [new CodeSettingRule],
            'movements_code' => [new CodeSettingRule],
            'bills_code' => [new CodeSettingRule],
            'services_code' => [new CodeSettingRule],
            'warehouse_reports_code' => [new CodeSettingRule]
        ]);

        /** @var array $codes Arreglo con información de los campos de códigos configurados */
        $codes = $request->input();
        /** @var boolean $saved Define el estatus verdadero para indicar que no se ha registrado información */
        $saved = false;

        foreach ($codes as $key => $value) {
            /** @var string $model Define el modelo al cual hace referencia el código */
            $model = '';

            if ($key !== '_token' && !is_null($value)) {
                list($table, $field) = explode("_", $key);
                list($prefix, $digits, $sufix) = CodeSetting::divideCode($value);

                if ($table === "products") {
                    /** @var string $table Define la tabla asociado a los productos inventariados */
                    $table = "warehouse_inventory_products";

                    /** @var string $model Define el modelo asociado a los productos inventariados */
                    $model = \Modules\Sale\Models\SaleWarehouseInventoryProduct::class;
                } elseif ($table === "movements") {
                    /** @var string $table Define la tabla asociado a los moviemientos de almacén */
                    $table = "warehouse_movements";
                    /** @var string $model Define el modelo para asociado a los movimientos de almacén */
                    $model = \Modules\Sale\Models\SaleWarehouseMovement::class;
                } elseif ($table === "bills") {
                    /** @var string $table Define la tabla asociado a las facturas */
                    $table = "bills";
                    /** @var string $model Define el modelo de las facturas */
                    $model = \Modules\Sale\Models\SaleBill::class;
                } elseif ($table === "services") {
                    /** @var string $table Define la tabla asociado a las facturas */
                    $table = "services";
                    /** @var string $model Define el modelo de las facturas */
                    $model = \Modules\Sale\Models\SaleService::class;
                } elseif ($table === "reports") {
                    /** @var string $table Define la tabla asociado a los reportes */
                    $table = "warehouse_reports";
                    /** @var string $model Define el modelo de los reportes */
                    $model = \Modules\Sale\Models\SaleWarehouseReport::class;
                }

                CodeSetting::updateOrCreate([
                    'module' => 'sale',
                    'table' => 'sale_'. $table,
                    'field' => $field,
                ], [
                    'format_prefix' => $prefix,
                    'format_digits' => $digits,
                    'format_year' => $sufix,
                    'model' => $model,
                ]);

                /** @var boolean $saved Define el estado verdadero para indicar que se ha registrado información */
                $saved = true;
            }
        }

        if ($saved) {
            $request->session()->flash('message', ['type' => 'store']);
        }

        return redirect()->route('sale.settings.index');
    }

    /**
     * Show the specified resource.
     * @return Renderable
     */
    public function show()
    {
        //  return view('sale::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Renderable
     */
    public function edit()
    {
        //  return view('sale::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Renderable
     */
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     * @return Renderable
     */
    public function destroy()
    {
    }
}
