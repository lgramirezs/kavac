<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;
use Modules\Finance\Models\FinanceSettingBankReconciliationFiles;
use Illuminate\Foundation\Validation\ValidatesRequests;
use DB;

/**
 * @class FinanceConciliationController
 * 
 * @brief Gestión de Finanzas > Banco > Conciliación.
 *
 * Clase que gestiona lo referente a Conciliaciones bancarias.
 *
 * @author Ing. Argenis Osorio <aosorio@cenditel.gob.ve>
 *
 * @license <a href='http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/'>
 *              LICENCIA DE SOFTWARE CENDITEL
 *          </a>
 */
class FinanceConciliationController extends Controller
{

    use ValidatesRequests;

    /**
     * Muestra la plantilla del módulo Finanzas > Banco > Conciliación.
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
        return view('finance::conciliation.index');
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
