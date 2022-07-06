<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;
use Modules\Finance\Models\FinanceSettingBankReconciliationFiles;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Models\Institution;
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

    /**
     * Obtiene los datos de la organización asociada al usuario autenticado o en
     * su defecto, la organización activa y por defecto.
     *
     * @method  getInstitution
     *
     * @author  Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     *
     * @param  Institution $institution Objeto con información asociada a un organismo
     *
     * @return JsonResponse JSON con información del organismo
     */
    public function getInstitution()
    {
        if(isset(auth()->user()->profile)){
            if (isset(auth()->user()->profile->institution_id)) {
                $institution = Institution::where(['id' => auth()->user()->profile->institution_id])->first();
            }
            else {
                $institution = Institution::where(['active' => true, 'default' => true])->first();
            }
        }
        else {
            $institution = Institution::where(['active' => true, 'default' => true])->first();
        }
        $inst = Institution::where('id', $institution->id)->with(['municipality' => function ($q) {
            return $q->with(['estate' => function ($qq) {
                return $qq->with('country');
            }]);
        }, 'banner', 'logo'])->first();

        return response()->json(['result' => true, 'institution' => $inst], 200);
    }
}
