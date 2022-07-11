<?php

namespace Modules\Budget\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Models\CodeSetting;
use App\Models\DocumentStatus;
use Modules\Budget\Models\BudgetModification;
use Modules\Budget\Models\BudgetModificationAccount;
use Modules\Budget\Models\BudgetSubSpecificFormulation;
use Modules\Budget\Models\BudgetAccountOpen;

/**
 * @class BudgetModificationController
 * @brief Controlador para las modificaciones presupuestarias del módulo de Presupuesto
 *
 * Clase que gestiona información de las modificaciones presupuestarias del módulo de Presupuesto
 *
 * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
 * @license <a href='http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/'>
 *              LICENCIA DE SOFTWARE CENDITEL
 *          </a>
 */
class BudgetModificationController extends Controller
{
    use ValidatesRequests;

    /** @var array Arreglo con los datos a implementar en los atributos del formulario */
    public $header;

    /**
     * Define la configuración de la clase
     *
     * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     */
    public function __construct()
    {
        /** Establece permisos de acceso para cada método del controlador */
        //$this->middleware('permission:budget.modificacions.list', ['only' => 'index', 'vueList']);
        /*$this->middleware('permission:budget.modificacions.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:budget.modificacions.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:budget.modificacions.delete', ['only' => 'destroy']);*/

        /** @var array Arreglo de opciones a implementar en el formulario */
        $this->header = [
            'route' => 'budget.modifications.store',
            'method' => 'POST',
            'role' => 'form',
            'class' => 'form-horizontal',
        ];
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('budget::modifications.list');
    }

    /**
     * Muestra el formulario para crear un crédito adicional
     *
     * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     * @return Renderable
     */
    public function create($type)
    {
        $viewTemplate = ($type==="AC")
                        ? 'aditional_credits'
                        : (($type==='RE')
                          ? 'reductions'
                          : (($type==="TR")
                            ? 'transfers' : ''));

        return view("budget::$viewTemplate.create-edit-form", compact('type'));
    }

    /**
     * Registra información de la modificación presupuestaria
     *
     * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     * @param  Request $request Objeto con datos de la petición realizada
     * @return Renderable
     */
    public function store(Request $request)
    {
        /** @var array Arreglo con las reglas de validación para el registro */
        $rules = [
            'approved_at' => ['required', 'date'],
            'description' => ['required'],
            'document' => ['required'],
            'institution_id' => ['required'],
            'budget_account_id' => ['required', 'array', 'min:1']
        ];

        /** @var array Arreglo con los mensajes para las reglas de validación */
        $messages = [
            'budget_account_id.required' => 'Las cuentas presupestarias son obligatorias.',
        ];

        /** @var object Contiene la configuración del código establecido para el registro */
        if (!is_null($request->type)) {
            switch ($request->type) {
                case 'AC':
                    $codeFilter = 'budget.aditional-credits';
                    break;
                case 'RE':
                    $codeFilter = 'budget.reductions';
                    break;
                case 'TR':
                    $codeFilter = 'budget.transfers';
                    break;
                default:
                    $codeFilter = '';
                    break;
            }
            /** @var object Contiene la configuración del código establecido para el registro */
            $codeSetting = CodeSetting::getSetting(BudgetModification::class, $codeFilter)->first();
        }

        if (!isset($codeSetting) || !$codeSetting) {
            $rules['code'] = 'required';
            $message['code.required'] = 'Debe configurar previamente el formato para el código a generar';
        }

        $this->validate($request, $rules, $messages);

        /** @var object Obtiene el registro del documento con estatus aprobado */
        $documentStatus = DocumentStatus::getStatus('AP');
        /** @var string Contiene el código generado para el registro a crear */
        $code = generate_registration_code(
            $codeSetting->format_prefix,
            strlen($codeSetting->format_digits),
            date((strlen($codeSetting->format_year) === 2) ? "y" : "Y"),
            BudgetModification::class,
            'code'
        );

        DB::transaction(function () use ($request, $code, $documentStatus) {
            $type = ($request->type==="AC") ? 'C' : (($request->type==="RE") ? 'R' : 'T');

            /** @var object Objeto que contiene los datos de la modificación presupuestaria creada */
            $budgetModification = BudgetModification::create([
                'type' => $type,
                'code' => $code,
                'approved_at' => $request->approved_at,
                'description' => $request->description,
                'document' => $request->document,
                'institution_id' => $request->institution_id,
                'document_status_id' => $documentStatus->id
            ]);

            foreach ($request->budget_account_id as $account) {

                /** @var object Obtiene la formulación correspondiente a la acción específica seleccionada */
                $formulation = BudgetSubSpecificFormulation::where('budget_specific_action_id', $account['from_specific_action_id'])
                     ->where('document_status_id', $documentStatus->id)
                     ->where('assigned', true)
                     ->orderBy('year', 'desc')->first();

                if ($formulation) {
                    $budgetAccountOpen = BudgetAccountOpen::where('budget_sub_specific_formulation_id', $formulation->id)
                                                            ->where('budget_account_id', $account['from_account_id'])
                                                            ->first();
                    if ($budgetAccountOpen) {
                        $modificationType = ($type==="C") ? 'I' : 'D';

                        if ($modificationType == 'D') {
                            $budgetAccountOpen->total_year_amount_m = $budgetAccountOpen->total_year_amount_m - $account['from_amount'];
                            $budgetAccountOpen->save();
                        }

                        if ($modificationType == 'I') {
                            $budgetAccountOpen->total_year_amount_m = $budgetAccountOpen->total_year_amount_m + $account['from_amount'];
                            $budgetAccountOpen->save();
                        }
                    }

                    BudgetModificationAccount::create([
                        'amount' => $account['from_amount'],
                        'operation' => ($type==="C") ? 'I' : 'D',
                        'budget_sub_specific_formulation_id' => $formulation->id,
                        'budget_account_id' => $account['from_account_id'],
                        'budget_modification_id' => $budgetModification->id
                    ]);
                }

                if (isset($account['to_account_id'])) {
                    /** @var object Obtiene la formulación correspondiente a la acción específica a donde transferir
                    los recursos */
                    $formulation_transfer = BudgetSubSpecificFormulation::currentFormulation(
                        $account['to_specific_action_id']
                    );
                    $budgetAccountOpen = BudgetAccountOpen::where('budget_sub_specific_formulation_id', $formulation_transfer->id)
                                                            ->where('budget_account_id', $account['to_account_id'])
                                                            ->first();
                    if ($budgetAccountOpen) {
                        $modificationType = ($type==="C") ? 'I' : 'D';
                        if ($modificationType == 'D') {
                            $budgetAccountOpen->total_year_amount_m = $budgetAccountOpen->total_year_amount_m + $account['to_amount'];
                            $budgetAccountOpen->save();
                        }
                    }

                    if ($formulation_transfer) {
                        BudgetModificationAccount::create([
                            'amount' => $account['to_amount'],
                            'operation' => 'I',
                            'budget_sub_specific_formulation_id' => $formulation_transfer->id,
                            'budget_account_id' => $account['to_account_id'],
                            'budget_modification_id' => $budgetModification->id
                        ]);
                    }
                }
            }
        });

        $request->session()->flash('message', ['type' => 'store']);
        return response()->json([
            'result' => true, 'redirect' => route('budget.modifications.index')
        ], 200);
    }

    /**
     * Muestra el formulario de actualización de datos según el tipo de modificación presupuestaria
     *
     * @method     edit
     *
     * @author     Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     *
     * @param      string                $type            Define el tipo de modificación presupuestaria a mostrar
     * @param      BudgetModification    $modification    Objeto con información de la modificación presupuestaria a
     *                                                    actualizar
     *
     * @return     View                Devuelve la vista a mostrar para la respectiva modificación presupuestaria
     */
    public function edit($type, BudgetModification $modification)
    {
        $viewTemplate = ($type==="AC")
                        ? 'aditional_credits'
                        : (($type==='RE')
                          ? 'reductions'
                          : (($type==="TR")
                            ? 'transfers' : ''));
        $model = $modification;

        return view("budget::$viewTemplate.create-edit-form", compact('type', 'model'));
    }

    public function update(Request $request)
    {
        /** @var array Arreglo con las reglas de validación para el registro */
        $rules = [
            'approved_at' => ['required', 'date'],
            'description' => ['required'],
            'document' => ['required'],
            'institution_id' => ['required'],
            'budget_account_id' => ['required', 'array', 'min:1']
        ];

        /** @var array Arreglo con los mensajes para las reglas de validación */
        $messages = [
            'budget_account_id.required' => 'Las cuentas presupestarias son obligatorias.',
        ];

        $this->validate($request, $rules, $messages);

        $documentStatus = DocumentStatus::getStatus('AP');

        DB::transaction(function () use ($request, $documentStatus) {
            $budgetModification = BudgetModification::find($request->id);
            $type = ($request->type==="AC") ? 'C' : (($request->type==="RE") ? 'R' : 'T');

            /** @var object Objeto que contiene los datos de la modificación presupuestaria creada */
            $budgetModification->type = $type;
            $budgetModification->approved_at = $request->approved_at;
            $budgetModification->description = $request->description;
            $budgetModification->document = $request->document;
            $budgetModification->institution_id = $request->institution_id;
            $budgetModification->document_status_id = $documentStatus->id;
            $budgetModification->save();

            $deleted = BudgetModificationAccount::where('budget_modification_id', $budgetModification->id)->delete();

            foreach ($request->budget_account_id as $account) {

                /** @var object Obtiene la formulación correspondiente a la acción específica seleccionada */
                $formulation = BudgetSubSpecificFormulation::where('budget_specific_action_id', $account['from_specific_action_id'])
                     ->where('document_status_id', $documentStatus->id)
                     ->where('assigned', true)
                     ->orderBy('year', 'desc')->first();

                if ($formulation) {
                    $budgetAccountOpen = BudgetAccountOpen::where('budget_sub_specific_formulation_id', $formulation->id)
                                                            ->where('budget_account_id', $account['from_account_id'])
                                                            ->first();
                    if ($budgetAccountOpen) {
                        $modificationType = ($type==="C") ? 'I' : 'D';

                        if ($modificationType == 'D') {
                            if ($request->type==="TR") {
                                if ($account['operation'] == 'I') {
                                    $budgetAccountOpen->update([
                                        'total_year_amount_m' => $budgetAccountOpen->total_year_amount_m,
                                    ]);
                                } elseif ($account['operation'] == 'S') {
                                    $budgetAccountOpen->update([
                                        'total_year_amount_m' => $budgetAccountOpen->total_year_amount_m + ($account['from_amount_edit'] < 0 ? $account['from_amount_edit'] * -1 : $account['from_amount_edit']),
                                    ]);
                                } elseif ($account['operation'] == 'R') {
                                    $budgetAccountOpen->update([
                                        'total_year_amount_m' => $budgetAccountOpen->total_year_amount_m - ($account['from_amount_edit'] < 0 ? $account['from_amount_edit'] * -1 : $account['from_amount_edit']),
                                    ]);
                                } else {
                                    $budgetAccountOpen->total_year_amount_m = $budgetAccountOpen->total_year_amount_m - $account['from_amount'];
                                            $budgetAccountOpen->save();
                                }
                            } else {
                                if ($account['operation'] == 'I') {
                                    $budgetAccountOpen->update([
                                        'total_year_amount_m' => $budgetAccountOpen->total_year_amount_m,
                                    ]);
                                } elseif ($account['operation'] == 'S') {
                                    $budgetAccountOpen->update([
                                        'total_year_amount_m' => $budgetAccountOpen->total_year_amount_m + ($account['from_amount_edit'] < 0 ? $account['from_amount_edit'] * -1 : $account['from_amount_edit']),
                                    ]);
                                } elseif ($account['operation'] == 'R') {
                                    $budgetAccountOpen->update([
                                        'total_year_amount_m' => $budgetAccountOpen->total_year_amount_m - ($account['from_amount_edit'] < 0 ? $account['from_amount_edit'] * -1 : $account['from_amount_edit']),
                                    ]);
                                } else {
                                    $budgetAccountOpen->total_year_amount_m = $budgetAccountOpen->total_year_amount_m - $account['from_amount'];
                                            $budgetAccountOpen->save();
                                }
                            }
                        }

                        if ($modificationType == 'I') {
                            if ($account['operation'] == 'I') {
                                $budgetAccountOpen->update([
                                    'total_year_amount_m' => $budgetAccountOpen->total_year_amount_m,
                                ]);
                            } elseif ($account['operation'] == 'S') {
                                $budgetAccountOpen->update([
                                    'total_year_amount_m' => $budgetAccountOpen->total_year_amount_m + ($account['from_amount_edit'] < 0 ? $account['from_amount_edit'] * -1 : $account['from_amount_edit']),
                                ]);
                            } elseif ($account['operation'] == 'R') {
                                $budgetAccountOpen->update([
                                    'total_year_amount_m' => $budgetAccountOpen->total_year_amount_m - ($account['from_amount_edit'] < 0 ? $account['from_amount_edit'] * -1 : $account['from_amount_edit']),
                                ]);
                            } else {
                                $budgetAccountOpen->total_year_amount_m = $budgetAccountOpen->total_year_amount_m + $account['from_amount'];
                                        $budgetAccountOpen->save();
                            }
                        }
                    }

                    BudgetModificationAccount::create([
                        'amount' => $account['from_amount'],
                        'operation' => ($type==="C") ? 'I' : 'D',
                        'budget_sub_specific_formulation_id' => $formulation->id,
                        'budget_account_id' => $account['from_account_id'],
                        'budget_modification_id' => $budgetModification->id
                    ]);
                }

                if (isset($account['to_account_id'])) {
                    /** @var object Obtiene la formulación correspondiente a la acción específica a donde transferir
                    los recursos */
                    $formulation_transfer = BudgetSubSpecificFormulation::currentFormulation(
                        $account['to_specific_action_id']
                    );
                    $budgetAccountOpen = BudgetAccountOpen::where('budget_sub_specific_formulation_id', $formulation_transfer->id)
                                                            ->where('budget_account_id', $account['to_account_id'])
                                                            ->first();
                    if ($budgetAccountOpen) {
                        $modificationType = ($type==="C") ? 'I' : 'D';
                        if ($modificationType == 'D') {
                            if ($account['operation'] == 'I') {
                                $budgetAccountOpen->update([
                                    'total_year_amount_m' => $budgetAccountOpen->total_year_amount_m,
                                ]);
                            } elseif ($account['operation'] == 'S') {
                                $budgetAccountOpen->update([
                                    'total_year_amount_m' => $budgetAccountOpen->total_year_amount_m - ($account['to_amount_edit'] < 0 ?
                                        $account['to_amount_edit'] * -1 : $account['to_amount_edit']),
                                ]);
                            } elseif ($account['operation'] == 'R') {
                                $budgetAccountOpen->update([
                                    'total_year_amount_m' => $budgetAccountOpen->total_year_amount_m + ($account['to_amount_edit'] < 0 ?
                                        $account['to_amount_edit'] * -1 : $account['to_amount_edit']),
                                ]);
                            } else {
                                $budgetAccountOpen->total_year_amount_m = $budgetAccountOpen->total_year_amount_m + $account['from_amount'];
                                        $budgetAccountOpen->save();
                            }
                        }
                    }

                    if ($formulation_transfer) {
                        BudgetModificationAccount::create([
                            'amount' => $account['to_amount'],
                            'operation' => 'I',
                            'budget_sub_specific_formulation_id' => $formulation_transfer->id,
                            'budget_account_id' => $account['to_account_id'],
                            'budget_modification_id' => $budgetModification->id
                        ]);
                    }
                }
            }
        });

        $request->session()->flash('message', ['type' => 'update']);
        return response()->json([
            'result' => true, 'redirect' => route('budget.modifications.index')
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     * @return Renderable
     */
    public function destroy($id)
    {
        /** @var object Objeto con información de la modificación presupuestaria a eliminar */
        $budgetModification = BudgetModification::find($id);

        if ($budgetModification) {
            $BudgetModificationAccounts = BudgetModificationAccount::where('budget_modification_id', $budgetModification->id)->get();
            $documentStatus = DocumentStatus::getStatus('AP');

            foreach ($BudgetModificationAccounts as $account) {
                /** @var object Obtiene la formulación correspondiente a la acción específica seleccionada */
                $formulation = BudgetSubSpecificFormulation::where('id', $account['budget_sub_specific_formulation_id'])
                     ->where('document_status_id', $documentStatus->id)
                     ->where('assigned', true)
                     ->orderBy('year', 'desc')->first();

                if ($formulation) {
                    $budgetAccountOpen = BudgetAccountOpen::where('budget_sub_specific_formulation_id', $formulation->id)
                                                            ->where('budget_account_id', $account['budget_account_id'])
                                                            ->first();
                    if ($budgetAccountOpen) {
                        $modificationType = ($account['operation']==="I") ? 'I' : 'D';

                        if ($modificationType == 'D') {
                            $budgetAccountOpen->total_year_amount_m = $budgetAccountOpen->total_year_amount_m + $account['amount'];
                            $budgetAccountOpen->save();
                        }

                        if ($modificationType == 'I') {
                            $budgetAccountOpen->total_year_amount_m = $budgetAccountOpen->total_year_amount_m - $account['amount'];
                            $budgetAccountOpen->save();
                        }
                    }
                }
            }

            $budgetModification->delete();
            $BudgetModificationAccountsDelete = BudgetModificationAccount::where('budget_modification_id', $budgetModification->id)->delete();
        }

        return response()->json(['record' => $budgetModification, 'message' => 'Success'], 200);
    }

    /**
     * Obtiene los registros a mostrar en listados de componente Vue
     *
     * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     * @return json Json con datos de la perición realizada
     */
    public function vueList($type)
    {
        switch ($type) {
            case 'AC':
                $tp = 'C';
                break;
            case 'RE':
                $tp = 'R';
                break;
            case 'TR':
                $tp = 'T';
                break;
            default:
                $tp = '';
                break;
        }

        $records = ($tp) ? BudgetModification::where('type', $tp)->get() : [];
        return response()->json([
            'records' => $records
        ], 200);
    }
}
