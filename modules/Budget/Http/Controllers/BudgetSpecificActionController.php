<?php

namespace Modules\Budget\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Crypt;
use Modules\Budget\Models\BudgetProject;
use Illuminate\Contracts\Support\Renderable;

use Modules\Budget\Models\BudgetAccountOpen;
use Modules\Budget\Models\BudgetSpecificAction;
use Modules\Budget\Models\BudgetCentralizedAction;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Modules\Budget\Models\BudgetSubSpecificFormulation;

/**
 * @class BudgetSpecificActionController
 * @brief Controlador de Acciones Específicas
 *
 * Clase que gestiona las Acciones Específicas
 *
 * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
 * @license <a href='http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/'>
 *              LICENCIA DE SOFTWARE CENDITEL
 *          </a>
 */
class BudgetSpecificActionController extends Controller
{
    use ValidatesRequests;

    /** @var array Arreglo con información de los proyectos registrados */
    public $projects;
    /** @var array Arreglo con información de las acciones centralizadas registradas */
    public $centralized_actions;
    /** @var array Arreglo con las reglas de validación sobre los datos de un formulario */
    public $validate_rules;

    /** @var array Arreglo con los mensajes de error de cada campo de un formulario */
    public $validate_messages;

    /**
     * Define la configuración de la clase
     *
     * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     */
    public function __construct()
    {
        /** Establece permisos de acceso para cada método del controlador */
        $this->middleware('permission:budget.specificaction.list', ['only' => 'index', 'vueList']);
        $this->middleware('permission:budget.specificaction.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:budget.specificaction.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:budget.specificaction.delete', ['only' => 'destroy']);

        /** @var array Arreglo de opciones de proyectos a representar en la plantilla para su selección */
        $this->projects = template_choices(BudgetProject::class, ['code', '-', 'name'], ['active' => true]);

        /** @var array Arreglo de opciones de acciones centralizadas a representar en la plantilla para su selección */
        $this->centralized_actions = template_choices(
            BudgetCentralizedAction::class,
            ['code', '-', 'name'],
            ['active' => true]
        );
        
        /** @var array Define las reglas de validación para el formulario */
        $this->validate_rules = [
            'from_date' => ['required', 'date'],
            'to_date' => ['required', 'date'],
            'code' => ['required'],
            'name' => ['required'],
            'description' => ['required'],
            'project_centralized_action' => ['required'],
            'project_id' => ['required_if:project_centralized_action,project'],
            'centralized_action_id' => ['required_if:project_centralized_action,centralized_action']
        ];

        /** @var array Define los mensajes de error para el formulario */
        $this->validate_messages = [
            'from_date.required' => 'El campo fecha de inicio es obligatorio.',
            'from_date.date' => 'El campo fecha de inicio no tiene un formato válido.',
            'to_date.required' => 'El campo fecha final es obligatorio.',
            'to_date.date' => 'El campo fecha final no tiene un formato válido.',
            'code.required' => 'El campo código es obligatorio.',
            'project_centralized_action.required' => 'Debe indicar si el registro es para un proyecto o acción centralizada.',
            'project_id.required_if' => 'Debe seleccionar un proyecto.',
            'centralized_action_id.required_if' => 'Debe seleccionar una acción centralizada'
        ];
    }

    /**
     * Muestra un listado de acciones específicas
     *
     * @author  Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     * @return Renderable
     */
    public function index()
    {
        return view('budget::index');
    }

    /**
     * Muestra el formulario para crear una acción específica
     *
     * @author  Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     * @return Renderable
     */
    public function create()
    {
        /** @var array Arreglo de opciones a implementar en el formulario */
        $header = [
            'route' => 'budget.specific-actions.store',
            'method' => 'POST',
            'role' => 'form',
            'class' => 'form-horizontal',
        ];

        /** @var array Arreglo de opciones de proyectos a representar en la plantilla para su selección */
        $projects = $this->projects;
        /** @var array Arreglo de opciones de acciones centralizadas a representar en la plantilla para su selección */
        $centralized_actions = $this->centralized_actions;

        return view('budget::specific_actions.create-edit-form', compact(
            'header',
            'projects',
            'centralized_actions'
        ));
    }

    /**
     * Registra información de la acción específica
     *
     * @author  Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     * @param  Request $request Objeto con datos de la petición realizada
     * @return Renderable
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->validate_rules, $this->validate_messages);

        /** @var object Crea una acción específica */
        $budgetSpecificAction = new BudgetSpecificAction([
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
            'code' => $request->code,
            'name' => $request->name,
            'description' => $request->description
        ]);

        if ($request->project_centralized_action === "project") {
            /** @var object Objeto que contiene información de un proyecto */
            $pry_acc = BudgetProject::find($request->project_id);
        } elseif ($request->project_centralized_action === "centralized_action") {
            /** @var object Objeto que contiene información de una acción centralizada */
            $pry_acc = BudgetCentralizedAction::find($request->centralized_action_id);
        }
        $pry_acc->specificActions()->save($budgetSpecificAction);

        $request->session()->flash('message', ['type' => 'store']);
        return redirect()->route('budget.settings.index');
    }

    /**
     * Muestra información de una acción específica
     *
     * @author  Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     * @return Renderable
     */
    public function show()
    {
        return view('budget::show');
    }

    /**
     * Muestra el formulario para la edición de una acción específica
     *
     * @author  Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     * @param  integer $id Identificador de la acción específica a modificar
     * @return Renderable
     */
    public function edit($id)
    {
        /** @var object Objeto con información de la acción específica a modificar */
        $BudgetSpecificAction = BudgetSpecificAction::find($id);

        /** @var array Arreglo de opciones a implementar en el formulario */
        $header = [
            'route' => ['budget.specific-actions.update', $BudgetSpecificAction->id],
            'method' => 'PUT',
            'role' => 'form'
        ];
        /** @var object Objeto con datos del modelo a modificar */
        $model = $BudgetSpecificAction;
        /** @var array Arreglo de opciones de proyectos a representar en la plantilla para su selección */
        $projects = $this->projects;
        /** @var array Arreglo de opciones de acciones centralizadas a representar en la plantilla para su selección */
        $centralized_actions = $this->centralized_actions;

        return view('budget::specific_actions.create-edit-form', compact(
            'header',
            'model',
            'projects',
            'centralized_actions'
        ));
    }

    /**
     * Actualiza información de la acción específica
     *
     * @author  Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     * @param  Request $request Objeto con datos de la petición realizada
     * @param  integer $id Identificador de la acción específica a modificar
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, $this->validate_rules, $this->validate_messages);

        if ($request->project_centralized_action === "project") {
            /** @var object Objeto que contiene información de un proyecto */
            $pry_acc = BudgetProject::find($request->project_id);
            $specificable_type = BudgetProject::class;
        } elseif ($request->project_centralized_action === "centralized_action") {
            /** @var object Objeto que contiene información de una acción centralizada */
            $pry_acc = BudgetCentralizedAction::find($request->centralized_action_id);
            $specificable_type = BudgetCentralizedAction::class;
        }

        /** @var object Objeto con información de la acción específica a modificar */
        $budgetSpecificAction = BudgetSpecificAction::find($id);
        $budgetSpecificAction->fill($request->all());
        $budgetSpecificAction->specificable_type = $specificable_type;
        $budgetSpecificAction->specificable_id = $pry_acc->id;
        $budgetSpecificAction->save();

        $request->session()->flash('message', ['type' => 'update']);
        return redirect()->route('budget.settings.index');
    }

    /**
     * Elimina una acción específica
     *
     * @author  Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     * @param  integer $id Identificador de la acción específica a eliminar
     * @return Renderable
     */
    public function destroy($id)
    {
        /** @var object Objeto con información de la acción específica a eliminar */
        $budgetSpecificAction = BudgetSpecificAction::find($id);

        if ($budgetSpecificAction) {
            $budgetSpecificAction->delete();
        }

        return response()->json(['record' => $budgetSpecificAction, 'message' => 'Success'], 200);
    }

    /**
     * Obtiene listado de registros
     *
     * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     * @param  [boolean] $active Filtrar por estatus del registro, valores permitidos true o false,
     *                           este parámetro es opcional.
     * @return \Illuminate\Http\JsonResponse
     */
    public function vueList($active = null)
    {
        /** @var object Objeto con información de las acciones específicas registradas */
        $specificActions = ($active !== null)
                           ? BudgetSpecificAction::where('active', $active)->with('specificable')->get()
                           : BudgetSpecificAction::with('specificable')->get();

        return response()->json(['records' => $specificActions], 200);
    }

    /**
     * Obtiene las acciones específicas registradas
     *
     * @author  Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     * @param  string  $type   Identifica si la acción a buscar es por proyecto o acción centralizada
     * @param  integer $id     Identificador de la acción centralizada a buscar, este parámetro es opcional
     * @param  string  $source Fuente de donde se realiza la consulta
     * @return JSON        JSON con los datos de las acciones específicas
     */
    public function getSpecificActions($type, $id, $source = null)
    {
        /** @var array Arreglo con información de las acciones específicas */
        $data = [['id' => '', 'text' => 'Seleccione...']];
        
        if ($type==="Project") {
            /** @var object Objeto con las acciones específicas asociadas a un proyecto */
            $specificActions = BudgetProject::find($id)->specificActions()->get();
        } elseif ($type == "CentralizedAction") {
            /** @var object Objeto con las acciones específicas asociadas a una acción centralizada */
            $specificActions = BudgetCentralizedAction::find($id)->specificActions()->get();
        }

        foreach ($specificActions as $specificAction) {
            /** @var object Objeto que determina si la acción específica ya fue formulada para el último presupuesto */
            $existsFormulation = BudgetSubSpecificFormulation::where([
                'budget_specific_action_id' => $specificAction->id,
                'assigned' => true
            ])->orderBy('year', 'desc')->first();

            if (!$existsFormulation) {
                array_push($data, [
                    'id' => $specificAction->id,
                    'text' => $specificAction->code . " - " . $specificAction->name
                ]);
            }
        }

        return response()->json($data);
    }

    /**
     * Obtiene todas las acciones específicas agrupadas por Proyectos y Acciones Centralizadas
     *
     * @author  Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     * @param string $formulated_year Año de formulación por el cual filtrar la información
     * @param boolean $formulated     Indica si se debe validar con una formulación de presupuesto
     * @param  integer $institutionId Identificador de la institución
     * @param string $selDate         Fecha en la cual se esta realizando la consulta
     * @return JSON                   JSON con los datos de las acciones específicas
     */
    public function getGroupAllSpecificActions(
        $formulated_year = '',
        $formulated = null,
        $institutionId = null,
        $selDate = null
    ) {
        if ($formulated_year && strlen($formulated_year) > 4) {
            try {
                $formulated_year = Crypt::decrypt($formulated_year);
            } catch (DecryptException $e) {
                //
            }
        }
        /** @var array Arreglo que contiene las acciones específicas agrupadas por proyectos */
        $dataProjects = ['text' => 'Proyectos', 'children' => []];
        /** @var array Arreglo que contiene las acciones específicas agrupadas por acciones centralizadas */
        $dataCentralizedActions = ['text' => 'Acciones Centralizadas', 'children' => []];

        /** @var array Arreglo que contiene las acciones específicas */
        $data = [['id' => '', 'text' => 'Seleccione...']];

        /** @var Object Objeto con información de las acciones específicas a consultar */
        $budgetSpecificAction = (is_null($institutionId))
                                ? BudgetSpecificAction::with('specificable')
                                : BudgetSpecificAction::with(['specificable' => function ($q) use ($institutionId) {
                                    $q->whereHas('department', function ($qq) use ($institutionId) {
                                        $qq->where('institution_id', $institutionId);
                                    });
                                }]);

        if (!is_null($selDate)) {
            $budgetSpecificAction = $budgetSpecificAction->where(
                'from_date',
                '>=',
                $selDate
            )->where('to_date', '<=', $selDate);
        }

        /** @var object Objeto que contiene información de las acciones específicas */
        $sp_accs = ($formulated_year)
                    ? $budgetSpecificAction->whereYear('from_date', $formulated_year)
                                           ->orWhereYear('to_date', $formulated_year)
                                           ->get()
                    : $budgetSpecificAction->get();

        /** Agrega las acciones específicas para cada grupo */
        foreach ($sp_accs as $sp_acc) {
            $filter = (!is_null($formulated) && $formulated) ? BudgetSubSpecificFormulation::where(
                [
                    'budget_specific_action_id' => $sp_acc->specificable_id,
                    'assigned' => true
                ]
            )->first() : '';

            if (str_contains($sp_acc->specificable_type, 'BudgetProject') && !is_null($filter)) {
                array_push($dataProjects['children'], [
                    'id' => $sp_acc->id,
                    'text' => "{$sp_acc->specificable->code} - {$sp_acc->code} | {$sp_acc->name}"
                ]);
            } elseif (str_contains($sp_acc->specificable_type, 'BudgetCentralizedAction') && !is_null($filter)) {
                array_push($dataCentralizedActions['children'], [
                    'id' => $sp_acc->id,
                    'text' => "{$sp_acc->specificable->code} - {$sp_acc->code} | {$sp_acc->name}"
                ]);
            } elseif (!is_null($formulated) && $formulated && is_null($filter)) {
                array_push($data, ['text' => 'Sin formulaciones registradas', 'children' => []]);
            }
        }

        /** Si el grupo Proyectos contiene registros los agrega a la lista */
        if (count($dataProjects['children']) > 0) {
            array_push($data, $dataProjects);
        }
        /** Si el grupo Acciones Centralizadas contiene registros los agrega a la lista */
        if (count($dataCentralizedActions['children']) > 0) {
            array_push($data, $dataCentralizedActions);
        }

        return response()->json($data);
    }

    /**
     * Obtiene detalles de una acción específica
     *
     * @author  Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     * @param  integer $id Identificar de la acción específica de la cual se requiere información
     * @return JSON        JSON con los datos de la acción específica
     */
    public function getDetail($id)
    {
        return response()->json([
            'result' => true, 'record' => BudgetSpecificAction::where('id', $id)->with('specificable')->first()
        ], 200);
    }

    /**
     * Listado de cuentas presupuestarias aperturadas para una acción específica
     *
     * @method    getOpenedAccounts
     *
     * @author     Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     *
     * @param     integer              $specificActionId    Identificador de la acción específica
     * @param     string               $selDate             Fecha a partir de la cual buscar las cuentas aperturadas
     *
     * @return    JsonResponse         Objeto con información de las cuentas aperturadas
     */
    public function getOpenedAccounts($specificActionId, $selDate)
    {
        list($year, $month, $day) = explode("-", $selDate);
        $records = [['id' => '', 'text' => 'Seleccione...']];
        $monthField = listMonths(true)[(int)$month] . "_amount";
        $openedAccounts = BudgetAccountOpen::with([
            'budgetAccount', 'subSpecificFormulation'
        ])->whereHas('budgetAccount', function ($q) {
            $q->where('specific', '<>', '00')->where('disaggregate_tax', false);
        })->whereHas('subSpecificFormulation', function ($q) use ($year, $specificActionId) {
            $q->where('year', $year)->whereHas('specificAction', function ($qq) use ($specificActionId) {
                $qq->where('id', $specificActionId);
            });
        })->where($monthField, '>', 0)->get();

        foreach ($openedAccounts as $openAccount) {
            $records[] = [
                'id' => $openAccount->id,
                'text' => $openAccount->budgetAccount->code . ' - ' .
                          $openAccount->budgetAccount->denomination . ' (' .
                          $openAccount->subSpecificFormulation->currency->symbol . " " .
                          number_format(
                              $openAccount->total_year_amount,
                              $openAccount->subSpecificFormulation->currency->decimal_places,
                              ",",
                              "."
                          ) . ')'
            ];
        }

        return response()->json(['result' => true, 'records' => $records], 200);
    }
}
