<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Modules\Finance\Models\FinanceSettingBankReconciliationFiles;
use DB;

/**
 * @class FinanceSettingBankReconciliationFilesController
 * 
 * @brief Configuraciones de los archivos de conciliación bancaria
 *
 * Clase que gestiona las configuraciones de archivos de conciliación bancarias.
 *
 * @author Ing. Argenis Osorio <aosorio@cenditel.gob.ve>
 *
 * @license <a href='http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/'>
 *              LICENCIA DE SOFTWARE CENDITEL
 *          </a>
 */
class FinanceSettingBankReconciliationFilesController extends Controller
{

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return 'Hola!';
    }

    /**
     * Almacena un registro recién creado en la base de datos.
     *
     * @method store
     *
     * @author Argenis Osorio <aosorio@cenditel.gob.ve>
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = DB::transaction(function () use ($request) {
            $data = FinanceSettingBankReconciliationFiles::create([
                'bank_id' => $request->bank_id,
                'read_start_line' => $request->read_start_line,
                'read_end_line' => $request->read_end_line,
                'position_reference_column' => $request->position_reference_column,
                'position_date_column' => $request->position_date_column,
                'position_debit_amount_column' => $request->position_debit_amount_column,
                'position_credit_amount_column' => $request->position_credit_amount_column,
                'position_description_column' => $request->position_description_column,
                'separated_by' => $request->separated_by,
                'date_format' => $request->date_format,
                'thousands_separator' => $request->thousands_separator,
                'decimal_separator' => $request->decimal_separator,
            ]);
            return $data;
        });
        // return $this->success($data);
        return response()->json(['record' => $data, 'message' => 'Success'], 200);
    }

    /**
     * Show the specified resource.
     * @return Renderable
     */
    public function show()
    {
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
    }
}
