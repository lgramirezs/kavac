<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;
use Modules\Finance\Models\FinanceSettingBankReconciliationFiles;
use Illuminate\Foundation\Validation\ValidatesRequests;
use DB;

/**
 * @class FinanceMovementsController
 * 
 * @brief Gestión de Finanzas > Banco > Movimientos.
 *
 * Clase que gestiona lo referente a Conciliaciones bancarias.
 *
 * @author Ing. Argenis Osorio <aosorio@cenditel.gob.ve>
 *
 * @license <a href='http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/'>
 *              LICENCIA DE SOFTWARE CENDITEL
 *          </a>
 */
class FinanceMovementsController extends Controller
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
        $this->middleware('permission:finance.movements.list', ['only' => ['index', 'vueList']]);
        $this->middleware('permission:finance.movements.create', ['only' => 'store']);
        $this->middleware('permission:finance.movements.edit', ['only' => ['create', 'update']]);
        $this->middleware('permission:finance.movements.delete', ['only' => 'destroy']);
    }

    /**
     * Muestra la plantilla del módulo Finanzas > Banco > Movimientos.
     *
     * @method index
     *
     * @author Argenis Osorio <aosorio@cenditel.gob.ve>
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('finance::movements.list');
    }

    /**
     * Muestra el formulario de registro de movimientos bancarios
     *
     * @method    create
     *
     * @author    Daniel Contreras <dcontreras@cenditel.gob.ve>
     *
     * @return    Renderable    Vista con el formulario
     */
    public function create()
    {
        return view('finance::movements.create');
    }

    public function store(Request $request)
    {
    }

    public function show()
    {
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }
}
