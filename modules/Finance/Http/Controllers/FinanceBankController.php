<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Modules\Finance\Models\FinanceBank;

/**
 * @class FinanceBankController
 * @brief Controlador para las entidades bancarias
 * 
 * Clase que gestiona las entidades bancarias
 * 
 * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve | roldandvg@gmail.com>
 * @copyright <a href='http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/'>LICENCIA DE SOFTWARE CENDITEL</a>
 */
class FinanceBankController extends Controller
{
    use ValidatesRequests;

    /** @var array Lista de elementos a mostrar */
    protected $data = [];

    /**
     * Método constructor de la clase
     *
     * @author  Ing. Roldan Vargas <rvargas@cenditel.gob.ve | roldandvg@gmail.com>
     */
    public function __construct() {
        $this->data[0] = [
            'id' => '',
            'text' => 'Seleccione...'
        ];
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return response()->json(['records' => FinanceBank::orderBy('code')->get()], 200);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('finance::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'code' => 'required|max:4|unique:finance_banks,code',
            'name' => 'required|max:100|unique:finance_banks,name',
            'short_name' => 'required|max:50|unique:finance_banks,short_name'
        ]);

        $financeBank = FinanceBank::create([
            'code' => $request->input('code'),
            'name' => $request->input('name'),
            'short_name' => $request->input('short_name'),
            'website' => (!empty($request->input('website')))?$request->input('website'):null
        ]);

        return response()->json(['record' => $financeBank, 'message' => 'Success'], 200);
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('finance::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('finance::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $financeBank = FinanceBank::find($id);
        
        $this->validate($request, [
            'code' => 'required|max:4|unique:finance_banks,code,' . $financeBank->id,
            'name' => 'required|max:100|unique:finance_banks,name,' . $financeBank->id,
            'short_name' => 'required|max:50|unique:finance_banks,short_name,' . $financeBank->id
        ]);
 
        $financeBank->code = $request->input('code');
        $financeBank->name = $request->input('name');
        $financeBank->short_name = $request->input('short_name');
        $financeBank->website = (!empty($request->input('website')))?$request->input('website'):null;
        $financeBank->save();
 
        return response()->json(['message' => 'Registro actualizado correctamente'], 200);
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        $financeBank = FinanceBank::find($id);
        $financeBank->delete();
        return response()->json(['record' => $financeBank, 'message' => 'Success'], 200);
    }

    /**
     * Obtiene los datos de las entidades bancarias
     *
     * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve | roldandvg@gmail.com>
     * @return JSON Devuelve un JSON con listado de las entidades bancarias
     */
    public function getBanks()
    {
        foreach (FinanceBank::all() as $bank) {
            $this->data[] = [
                'id' => $bank->id,
                'text' => $bank->name
            ];
        }

        return response()->json($this->data);
    }

    /**
     * Obtiene información de una determinada entidad bancaria
     *
     * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve | roldandvg@gmail.com>
     * @param  integer $bank_id Identificador de la entidad bancaria
     * @return JSON             Devuelve un JSON con los datos de la entidad bancaria consultada
     */
    public function getBankInfo($bank_id)
    {
        return response()->json(['result' => true, 'bank' => FinanceBank::find($bank_id)], 200);
    }
}
