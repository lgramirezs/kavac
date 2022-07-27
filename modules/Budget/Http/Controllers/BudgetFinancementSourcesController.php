<?php

namespace Modules\Budget\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Foundation\Validation\ValidatesRequests;
// use Modules\Budget\Models\BudgetFinancementSources;

/**
 * @class BudgetFinancementSourcesController
 *
 * @brief Gestión de las fuentes de financiamiento.
 *
 * Clase que gestiona las fuentes de financiamiento.
 *
 * @author Ing. Argenis Osorio <aosorio@cenditel.gob.ve>
 *
 * @license <a href='http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/'>
 *              LICENCIA DE SOFTWARE CENDITEL
 *          </a>
 */
class BudgetFinancementSourcesController extends Controller
{
    use ValidatesRequests;

    /**
     * Define la configuración inicial de la clase.
     *
     * @author Ing. Argenis Osorio <aosorio@cenditel.gob.ve>
     */
    public function __construct()
    {
    }

    /**
     * Obtiene un listado de los registros almacenados.
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
        // return response()->json(['records' => BudgetFinancementSources::orderBy('id')->get()], 200);
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
    }

    /**
     * Show the specified resource.
     * @return Renderable
     */
    public function show()
    {
    }

    /**
     * Actualiza un registro específico de la base de datos.
     *
     * @method update
     *
     * @author Argenis Osorio <aosorio@cenditel.gob.ve>
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Elimina un registro específico de la base de datos.
     *
     * @method destroy
     *
     * @author Argenis Osorio <aosorio@cenditel.gob.ve>
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
}
