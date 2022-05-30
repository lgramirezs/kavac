<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;
use Modules\Finance\Models\FinanceSettingBankReconciliationFiles;
use Modules\Accounting\Models\AccountingAccount;
use Modules\Accounting\Models\AccountingEntryCategory;
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
        /**
         * [$accountingList contiene las cuentas patrimoniales]
         * @var [Json]
         */
        $accountingList = json_encode($this->getRecordsAccounting());

        /**
         * [$categories contendra las categorias]
         * @var array
         */
        $categories = [];
        array_push($categories, [
            'id'      => '',
            'text'    => 'Seleccione...',
            'acronym' => ''
        ]);

        foreach (AccountingEntryCategory::all() as $category) {
            array_push($categories, [
                'id'      => $category->id,
                'text'    => $category->name,
                'acronym' => $category->acronym,
            ]);
        }

        /**
         * se convierte array a JSON
         */
        $categories = json_encode($categories);

        return view('finance::movements.create', compact('accountingList', 'categories'));
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
     * Consulta los registros del modelo AccountingAccount que posean conversión
     * @author Juan Rosas <jrosas@cenditel.gob.ve | juan.rosasr01@gmail.com>
     * @param  Request $request [array con listado de cuentas a convertir]
     *                             true= todo, false=solo sin conversiones
     * @return Array
     */
    public function getRecordsAccounting()
    {
        /**
         * [$records contendra registros]
         * @var array
         */
        $records = [];
        $index = 0;
        array_push($records, [
            'id'   => '',
            'text' =>   "Seleccione..."
        ]);

        /**
         * ciclo para almacenar en array cuentas patrimoniales disponibles para conversiones
        */
        foreach (AccountingAccount::with('accountable')
                ->where('active', true)
                ->orderBy('group', 'ASC')
                ->orderBy('subgroup', 'ASC')
                ->orderBy('item', 'ASC')
                ->orderBy('generic', 'ASC')
                ->orderBy('specific', 'ASC')
                ->orderBy('subspecific', 'ASC')
                ->orderBy('denomination', 'ASC')
                ->cursor() as $AccountingAccount) {
            array_push($records, [
                    'id'   => $AccountingAccount->id,
                    'text' =>   "{$AccountingAccount->getCodeAttribute()} - {$AccountingAccount->denomination}"
                ]);
            $index++;
        }

        $records[0]['index'] = $index;

        /**
         * se convierte array a JSON
         */
        return $records;
    }
}
