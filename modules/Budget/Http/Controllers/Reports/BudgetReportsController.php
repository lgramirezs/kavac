<?php

/** [descripción del namespace] */

namespace Modules\Budget\Http\Controllers\Reports;

use Modules\Budget\Exports\RecordsExport;
use App\Models\FiscalYear;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Budget\Models\Currency;
use App\Repositories\ReportRepository;
use Modules\Budget\Models\Institution;
use Modules\Budget\Models\BudgetAccount;
use Modules\Budget\Models\BudgetProject;
use Illuminate\Contracts\Support\Renderable;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Budget\Models\BudgetAccountOpen;
use Modules\Budget\Models\BudgetCompromiseDetail;
use Modules\Budget\Models\BudgetCentralizedAction;
use Modules\Budget\Models\BudgetModificationAccount;
use Modules\Budget\Models\BudgetSubSpecificFormulation;

/**
 * @class BudgetAccountOpenController
 * @brief Clase para generar reporte de disponibilad presupuestaria
 *
 * Clase para generar reporte de disponibilad presupuestaria
 *
 * @author Jonathan Alvarado <wizardx1407@gmail.com> | <jonathanalvarado1407@gmail.com>
 *
 * @license
 *     [LICENCIA DE SOFTWARE CENDITEL](http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/)
 */
class BudgetReportsController extends Controller
{

    private $monthColumnNames = [
        'jan_amount',
        'feb_amount',
        'mar_amount',
        'apr_amount',
        'may_amount',
        'jun_amount',
        'jul_amount',
        'aug_amount',
        'sep_amount',
        'oct_amount',
        'nov_amount',
        'dec_amount'
    ];

    /**
     * Genera los datos necesarios para el formulario de generacion de reporte de disponibilidad presupuestaria
     *
     * @author    Jonathan Alvarado <wizardx1407@gmail.com> | <jonathanalvarado1407@gmail.com>
     *
     * @return    Renderable 
     */
    public function budgetAvailability($flag = null)
    {

        $budgetItems = $this->getBudgetAccounts();
        $budgetProjects = $this->getBudgetProjects(true);
        $budgetCentralizedActions = $this->getBudgetCentralizedActions(true);

        $data = array();
        $temp = array('text' => '', 'children' => []);
        $isFirst = true;

        foreach ($budgetItems as $budgetItem) {

            $code = str_replace('.', '', $budgetItem->getCodeAttribute());

            if (substr_count($code, '0') == 8) {

                if (!$isFirst) {
                    array_push($data, $temp);
                    $temp = array('text' => '', 'children' => []);
                }

                $temp['text'] = $budgetItem->denomination;
                $isFirst = false;
            }

            array_push($temp['children'], array(
                'text' => $budgetItem->denomination . ' ' . "($code)",
                'id' => (int)$code
            ));
        }

        array_push($data, $temp);

        if ($flag) {
            return [
                'budgetItems' => json_encode($data),
                'budgetProjects' => json_encode($budgetProjects),
                'budgetCentralizedActions' => json_encode($budgetCentralizedActions)
            ];
        }

        return view('budget::reports.budgetAvailability', [
            'budgetItems' => json_encode($data),
            'budgetProjects' => json_encode($budgetProjects),
            'budgetCentralizedActions' => json_encode($budgetCentralizedActions)
        ]);
    }

    /**
     * Metodo para retornar un array con las cuentas presupuestarias
     *
     * @author    Jonathan Alvarado <wizardx1407@gmail.com> | <jonathanalvarado1407@gmail.com>
     *
     * @return    Array Arreglo ordenado de cuentas presupuestarias
     */
    public function getBudgetAccounts()
    {

        $budgetItems = BudgetAccount::all()->all();

        usort($budgetItems, function ($budgetItemOne, $budgetItemTwo) {

            $codeOne = str_replace('.', '', $budgetItemOne->getCodeAttribute());
            $codeTwo = str_replace('.', '', $budgetItemTwo->getCodeAttribute());

            if ($codeOne > $codeTwo) return 1;

            else if ($codeOne == $codeTwo) return 0;

            else return -1;
        });

        return $budgetItems;
    }

    /**
     * Metodo para retornar un array con las cuentas presupuestarias que han sido formuladas
     *
     * @author  Jonathan Alvarado <wizardx1407@gmail.com> | <jonathanalvarado1407@gmail.com>
     *
     * @param   bool accountsWithMovements
     * 
     * @return  Array Arreglo ordenado de cuentas presupuestarias formuladas
     */
    public function getBudgetAccountsOpen(bool $accountsWithMovements, object $project)
    {
        $project_accounts_open = array();
        $compromised = 0;

        foreach ($project->specificActions as $specificAction) {

            $accounts_open = $specificAction->subSpecificFormulations[0]->accountOpens->all();
            $flat_accounts_open = array_column($accounts_open, 'budget_account_id');

            foreach ($accounts_open as $account) {
                $increment = 0;
                $decrement = 0;
                if (!isset($account['compromised']) && !isset($account['current']) && !isset($account['programmed']) && !isset($account['increment']) && !isset($account['decrement'])) {
                    $compromised = $this->getAccountCompromisedAmout($account);
                    $modifications = $this->getAccountModifications($account->budget_sub_specific_formulation_id);


                    $account['current'] = ($account->total_year_amount_m + $compromised);
                    // dd($account);
                    $account['compromised'] = $compromised;
                    $account['programmed'] = $account->total_year_amount;


                    if ($modifications) {
                        // dd($modifications);
                        $increment_descriptions = array();
                        $decrement_descriptions = array();
                        foreach ($modifications as $modif) {
                            if ($account->budget_account_id == $modif->budget_account_id) {
                                if ($modif->operation == 'I') {
                                    $increment += $modif->amount;
                                    array_push($increment_descriptions, $modif->budgetModification->description);
                                } else {
                                    $decrement += $modif->amount;
                                    array_push($decrement_descriptions, $modif->budgetModification->description);
                                }
                            }
                            $account['increment_descriptions'] = $increment_descriptions;
                            $account['decrement_descriptions'] = $decrement_descriptions;
                        }
                    }
                    $account['increment'] = $increment;
                    $account['decrement'] = $decrement;

                    $parent = array_search($account->budgetAccount->parent_id, $flat_accounts_open);
                    if ($parent) {
                        $accounts_open[$parent]['compromised'] += $compromised;
                        // $accounts_open[$parent]['amount_available'] = $accounts_open[$parent]->total_year_amount - $accounts_open[$parent]['compromised'];
                        $accounts_open[$parent]['current'] += $account['current'];
                        $accounts_open[$parent]['programmed'] = $accounts_open[$parent]->total_year_amount;
                        $accounts_open[$parent]['increment'] += $increment;
                        $accounts_open[$parent]['decrement'] += $decrement;
                        $accounts_open[$parent]['total_year_amount_m'] += $increment;
                        $accounts_open[$parent]['total_year_amount_m'] -= $decrement;
                        $accounts_open[$parent]['total_year_amount_m'] -= $compromised;
                    }
                } else {

                    $parent = array_search($account->budgetAccount->parent_id, $flat_accounts_open);
                    if ($parent) {
                        $accounts_open[$parent]['compromised'] += $account['compromised'];
                        // $accounts_open[$parent]['amount_available'] = $accounts_open[$parent]->total_year_amount - $accounts_open[$parent]['compromised'];
                        $accounts_open[$parent]['current'] += $account['current'];
                        $accounts_open[$parent]['programmed'] = $accounts_open[$parent]->total_year_amount;
                        $accounts_open[$parent]['increment'] += $account['increment'];
                        $accounts_open[$parent]['decrement'] += $account['decrement'];
                        $accounts_open[$parent]['total_year_amount_m'] += $account['increment'];
                        $accounts_open[$parent]['total_year_amount_m'] -= $account['decrement'];
                        $accounts_open[$parent]['total_year_amount_m'] -= $accounts_open[$parent]['compromised'];
                    }
                }
            }
            array_push(
                $project_accounts_open,
                [
                    $accounts_open,
                    $specificAction->subSpecificFormulations[0],
                    "project_code" => $project->code,
                    "specific_action_code" => $specificAction->code
                ]
            );
        }

        foreach ($project_accounts_open as $accounts) {
            array_filter($accounts[0], function ($account) use ($accountsWithMovements) {
                if ($accountsWithMovements && ($account['amount_available'] === $account['total_year_amount'])) return false;
                return true;
            });
        }

        return $project_accounts_open;
    }

    /**
     * Metodo que retorna el monto comprometido para la cuenta $account_id
     * 
     * @author José Briceño <josejorgebriceno9@gmail.com>
     * 
     * @method getAccountCompromisedAmout
     * 
     * @param int $account_id
     * 
     * @return Float Monto del compromiso para la cuenta presupuestaria con 'id' $account_id
     * 
     */

    public function getAccountCompromisedAmout(object $accout_id)
    {
        $compromised = BudgetCompromiseDetail::where('budget_sub_specific_formulation_id', $accout_id->budget_sub_specific_formulation_id)
            ->where('budget_account_id', $accout_id->budget_account_id)->get();
        $amout = 0;
        $descriptions = array();
        if (!$compromised->isEmpty()) {
            foreach ($compromised as $com) {
                $amout = $com->getTotalAttribute();
                array_push($descriptions, $com->description);
            }
            $accout_id['compromised_descriptions'] = $descriptions;
            return $amout;
        }
        return $amout;
    }

    /**
     * Metodo que retorna las modificaciones de una cuenta
     * 
     * @author José Briceño <josejorgebriceno9@gmail.com>
     * 
     * @method getModifications
     * 
     * @param int $account_id
     * 
     * @return Object Objecto que contiene aumentos y disminuciones
     * 
     */

    public function getAccountModifications(int $account_budget_sub_specific_formulation_id)
    {
        $modifications = BudgetModificationAccount::with('budgetModification')->where('budget_sub_specific_formulation_id', $account_budget_sub_specific_formulation_id)->get();
        return !$modifications->isEmpty() ? $modifications : null;
    }


    /**
     * Metodo para filtrar y retornar un array con las cuentas presupuestarias formuladas
     *
     * @author    Jonathan Alvarado <wizardx1407@gmail.com> | <jonathanalvarado1407@gmail.com>
     * 
     * @author José Briceño <josejorgebriceno9@gmail.com>
     *
     * @return    Array Arreglo ordenado de cuentas presupuestarias formuladas
     */
    public function filterBudgetAccounts(array $budgetAccountsOpen, int $initialCode, int $finalCode, string $initialDate, string $finalDate)
    {
        $filteredArray = array();

        foreach ($budgetAccountsOpen as $budgetItem) {

            if ($budgetItem->code > $finalCode) break;

            // $specificAction = BudgetSubSpecificFormulation::where('id', $budgetItem->budget_sub_specific_formulation_id)->first()->specificAction;

            $code = str_replace('.', '', $budgetItem->budgetAccount->getCodeAttribute());

            if ($code >= $initialCode && $code <= $finalCode) {
                // if (($initialDate <= $specificAction->from_date->toDateString()) && ($specificAction->to_date->toDateString() <= $finalDate)) {
                array_push($filteredArray, $budgetItem);
                // }
            }
        }
        return $filteredArray;
    }

    /**
     * Metodo para generar el reporte en PDF de disponibilad presupuestaria
     *
     * @author    Jonathan Alvarado <wizardx1407@gmail.com> | <jonathanalvarado1407@gmail.com>
     * 
     * @author  José Briceño <josejorgebriceno9@gmail.com>
     *
     */
    public function getPdf(Request $request)
    {

        $data = $request->validate([
            'initialDate' => ['required', 'before_or_equal:finalDate'],
            'finalDate' => ['required', 'after_or_equal:initialDate'],
            'initialCode' => 'required',
            'finalCode' => 'required',
            'accountsWithMovements' => 'required',
            'project_id' => 'required',
            'project_type' => 'required',
            'specific_actions_ids' => 'required',
        ]);

        // Convierte un string que contiene enteros en un array de enteros
        $data["specific_actions_ids"] = json_decode('[' . $request->all()["specific_actions_ids"] . ']', true);
        $ids = $data["specific_actions_ids"];

        $pdf = new ReportRepository();

        if ($request->project_type === 'project') {
            $project = BudgetProject::with(['specificActions' => function ($query) use ($ids) {
                $query->with(['subSpecificFormulations' => function ($query) {
                    $query->with(['accountOpens' => function ($query) {
                        $query->with('budgetAccount');
                        $query->orderBy('id', 'desc');
                    }])->whereHas('accountOpens');
                }])->whereIn('id', $ids)->get();
            }])->find($data["project_id"]);
        } else {
            $project = BudgetCentralizedAction::with(['specificActions' => function ($query) use ($ids) {
                $query->with(['subSpecificFormulations' => function ($query) {
                    $query->with(['accountOpens' => function ($query) {
                        $query->with('budgetAccount');
                        $query->orderBy('id', 'desc');
                    }])->whereHas('accountOpens');
                }])->whereIn('id', $ids)->get();
            }])->find($data["project_id"]);
        }

        $records = $this->getBudgetAccountsOpen($data['accountsWithMovements'], $project);

        for ($i = 0; $i < count($records); $i++) {
            $records[$i][0] = $this->filterBudgetAccounts($records[$i][0], $data['initialCode'], $data['finalCode'], $data['initialDate'], $data['finalDate']);
        }

        $institution = Institution::find(1);

        $fiscal_year = FiscalYear::where('active', true)->first();

        $currency = Currency::where('default', true)->first();

        $pdf->setConfig(['institution' => $institution, 'orientation' => 'P', 'reportDate' => '']);
        $pdf->setHeader('', 'Certificado de disponibilidad presupuestaria', true);
        $pdf->setFooter();
        $pdf->setBody('budget::pdf.budgetAvailability', true, [
            'pdf' => $pdf,
            'records' => $records,
            'institution' => $institution,
            'currencySymbol' => $currency['symbol'],
            'fiscal_year' => $fiscal_year['year'],
            "report_date" => \Carbon\Carbon::today()->format('d-m-Y'),
            'initialDate' => \Carbon\Carbon::rawCreateFromFormat('Y-m-d', $data['initialDate'])->format('d-m-Y'),
            'finalDate' => \Carbon\Carbon::rawCreateFromFormat('Y-m-d', $data['finalDate'])->format('d-m-Y'),
        ]);
    }


    /**
     * Metodo que retorna el monto comprometido para la cuenta $account_id
     * 
     * @author José Briceño <josejorgebriceno9@gmail.com>
     * 
     * @method consolidatedReportPdf
     * 
     * @param Request $request
     * 
     */

    public function consolidatedReportPdf(Request $request)
    {

        $request->validate([
            'initialDate' => ['required', 'before_or_equal:finalDate'],
            'finalDate' => ['required', 'after_or_equal:initialDate'],
            'initialCode' => 'required',
            'finalCode' => 'required',
            'accountsWithMovements' => 'required',
        ]);

        $data = $request->toArray();
        $data["projects_ids"] = json_decode('[' . $data["projects_ids"] . ']', true);
        $data["centralized_actions_ids"] = json_decode('[' . $data["centralized_actions_ids"] . ']', true);

        $projects = BudgetProject::with(['specificActions' => function ($query) {
            $query->with(['subSpecificFormulations' => function ($query) {
                $query->with(['accountOpens' => function ($query) {
                    $query->with('budgetAccount');
                    $query->orderBy('id', 'desc');
                }])->whereHas('accountOpens');
            }])->whereHas('subSpecificFormulations');
        }])->whereIn('id', $data["projects_ids"])->get();


        $centrilized_actions = BudgetCentralizedAction::with(['specificActions' => function ($query) {
            $query->with(['subSpecificFormulations' => function ($query) {
                $query->with(['accountOpens' => function ($query) {
                    $query->with('budgetAccount');
                    $query->orderBy('id', 'desc');
                }])->whereHas('accountOpens');
            }])->whereHas('subSpecificFormulations');
        }])->whereIn('id', $data["centralized_actions_ids"])->get();

        $projects_accounts = array();
        foreach ($projects as $project) {
            array_push($projects_accounts, $this->getBudgetAccountsOpen($data['accountsWithMovements'], $project));
        }

        $centrilized_actions_accounts = array();
        foreach ($centrilized_actions as $centrilized_action) {
            array_push($centrilized_actions_accounts, $this->getBudgetAccountsOpen($data['accountsWithMovements'], $centrilized_action));
        }

        $records = array();

        foreach ($projects_accounts as $projects_account) {
            $projects_account[0][0] = $this->filterBudgetAccounts($projects_account[0][0], $data['initialCode'], $data['finalCode'], $data['initialDate'], $data['finalDate']);
            array_push($records, ...$projects_account);
        }

        foreach ($centrilized_actions_accounts as $centrilized_actions_account) {
            $centrilized_actions_account[0][0] = $this->filterBudgetAccounts($centrilized_actions_account[0][0], $data['initialCode'], $data['finalCode'], $data['initialDate'], $data['finalDate']);
            array_push($records, ...$centrilized_actions_account);
        }

        $pdf = new ReportRepository();

        $institution = Institution::find(1);
        $fiscal_year = FiscalYear::where('active', true)->first();
        $currency = Currency::where('default', true)->first();

        $pdf->setConfig(['institution' => $institution, 'orientation' => 'P', 'reportDate' => '']);
        $pdf->setHeader('', 'Presupuesto Formulado del ejercicio económico financiero vigente');
        $pdf->setFooter();
        $pdf->setBody('budget::pdf.budgetAvailability', true, [
            'pdf' => $pdf,
            'records' => $records,
            'institution' => $institution,
            'currencySymbol' => $currency['symbol'],
            'fiscal_year' => $fiscal_year['year'],
            "report_date" => \Carbon\Carbon::today()->format('d-m-Y'),
            'initialDate' => \Carbon\Carbon::rawCreateFromFormat('Y-m-d', $data['initialDate'])->format('d-m-Y'),
            'finalDate' => \Carbon\Carbon::rawCreateFromFormat('Y-m-d', $data['finalDate'])->format('d-m-Y'),
        ]);
    }

    public function getProjectsView()
    {
        return view('budget::reports.projects');
    }

    public function getProjectsReportData(Request $request)
    {
        try {
            $project_code = $request->input('project_code');
            $search = $request->input('search');

            $query = BudgetProject::query();

            if ($project_code) {
                $query->where("code", "LIKE", "%" . $project_code . "%");
            }

            if ($search) {
                $query->where("name", "LIKE", "%" . $search . "%");
            }

            $query = $query->get();

            $response = [
                'data' => $query,
                "message" => "Data para reporte de proyectos"
            ];
        } catch (\Exception $e) {
            $code = $e->getCode() ? (is_numeric($e->getCode()) ? $e->getCode() : 500) : 500;
            $msg = $e->getMessage() ?? "Error al obtener la data para el reporte de proyectos";
            $response = [
                "message" => $msg,
                "errors" => []
            ];
        }

        return response()->json($response, $code ?? 200);
    }

    public function getProjectsReportPdf(Request $request)
    {
        try {
            $project_code = $request->input('project_code');
            $search = $request->input('search');

            $query = BudgetProject::query();

            if ($project_code) {
                $query->where("code", "LIKE", "%" . $project_code . "%");
            }

            if ($search) {
                $query->where("name", "LIKE", "%" . $search . "%");
            }

            $query = $query->get();

            $pdf = new ReportRepository();
            $institution = Institution::find(1);

            $pdf->setConfig(['institution' => $institution]);
            $pdf->setHeader('Reporte de proyectos', 'Reporte de proyectos de la institucion');
            $pdf->setFooter();
            $pdf->setBody('budget::pdf.projects', true, [
                'pdf' => $pdf,
                'records' => $query,
            ]);
        } catch (\Exception $e) {
            $code = $e->getCode() ? (is_numeric($e->getCode()) ? $e->getCode() : 500) : 500;
            $msg = $e->getMessage() ?? "Error al obtener la data para el reporte de proyectos";
            $response = [
                "message" => $msg,
                "errors" => []
            ];


            return response()->json($response, $code ?? 200);
        }
    }

    public function getFormulatedView()
    {
        $formulations = BudgetSubSpecificFormulation::all(['year']);

        $years = [];

        foreach ($formulations as $formulation) {
            $years[] = $formulation->year;
        }

        $years = array_unique($years);

        return view('budget::reports.budgetFormulated', ['years' => json_encode($years)]);
    }

    public function getFormulations(Request $request)
    {
        $entity = $request->input('is_project')
            ? BudgetProject::class
            : BudgetCentralizedAction::class;

        $id = $request->input('id');

        $query = BudgetSubSpecificFormulation::query();

        $query = $query->whereHas('specificAction', function ($query) use ($entity, $id) {
            $query->whereHasMorph('specificable', [BudgetProject::class, BudgetCentralizedAction::class], function ($query) use ($entity, $id) {
                return $query->where('specificable_id', $id)
                    ->where('specificable_type', $entity);
            });
        });

        $query = $query->get();

        $formulations = $query->count() ? [['id' => '', 'text' => 'Seleccione']] : [];

        foreach ($query as $formulation) {
            $formulations[] = [
                'id' => $formulation->id,
                'text' => $formulation->code
            ];
        }

        return response()->json($formulations);
    }


    public function getFormulatedReportData(Request $request)
    {

        $formulation_id = $request->input('formulation_id');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $query = BudgetAccountOpen::where('budget_sub_specific_formulation_id', $formulation_id);

        if ($start_date) {
            $query->where('created_at', '>=', $start_date);
        }

        if ($end_date) {
            $query->where('created_at', '<=', $end_date);
        }

        $query = $query->get();

        $total = $query->sum('total_real_amount');

        foreach ($query as $account) {
            $account->code = $account->budgetAccount->getCodeAttribute();
            $account->percentage = round(($account->total_real_amount * 100) / $total);
            $account->total = $total;
        }

        return response()->json(['data' => $query]);
    }

    public function getFormulatedReportPdf(Request $request)
    {
        try {

            $formulation_id = $request->input('formulation_id');
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');

            $query = BudgetAccountOpen::where('budget_sub_specific_formulation_id', $formulation_id);

            if ($start_date) {
                $query->where('created_at', '>=', $start_date);
            }

            if ($end_date) {
                $query->where('created_at', '<=', $end_date);
            }

            $query = $query->get();
            $total = $query->sum('total_real_amount');

            foreach ($query as $account) {
                $account->code = $account->budgetAccount->getCodeAttribute();
                $account->percentage = round(($account->total_real_amount * 100) / $total);
                $account->total = $total;
            }

            $pdf = new ReportRepository();

            $institution = Institution::find(1);

            $fiscal_year = FiscalYear::where('active', true)->first();

            $currency = Currency::where('default', true)->first();

            $pdf->setConfig(['institution' => $institution]);
            $pdf->setHeader('Reporte de Presupuesto', 'Presupuesto Formulado del ejercicio económico financiero vigente');
            $pdf->setFooter();
            $pdf->setBody('budget::pdf.formulations', true, [
                'pdf' => $pdf,
                'records' => $query,
                'institution' => $institution,
                'currencySymbol' => $currency['symbol'],
                'fiscal_year' => $fiscal_year['year'],
            ]);
        } catch (\Exception $e) {
            $code = $e->getCode() ? (is_numeric($e->getCode()) ? $e->getCode() : 500) : 500;
            $msg = $e->getMessage() ?? "Error al obtener la data para el reporte de formulationes";
            $response = [
                "message" => $msg,
                "errors" => []
            ];


            return response()->json($response, $code ?? 200);
        }
    }

    public function getBudgetProjects(bool $list = null)
    {
        $budgetProjects = BudgetProject::with(['specificActions'])->whereHas('specificActions', function ($query) {
            $query->whereHas('subSpecificFormulations', function ($query) {
                $query->where('assigned', true);
            });
        })->get()->all();

        if ($list) {
            $budgetProjects = array_map(function ($budgeProject) {
                return array(
                    'id' => $budgeProject->id,
                    'text' => $budgeProject->code . ' - ' . $budgeProject->name,
                );
            }, $budgetProjects);

            array_unshift($budgetProjects, ['id' => "", 'text' => "Seleccione..."]);
            return $budgetProjects;
        }

        return $budgetProjects;
    }

    public function getBudgetCentralizedActions(bool $list = null)
    {
        $budgetCentralizedActions = BudgetCentralizedAction::with(['specificActions'])->whereHas('specificActions', function ($query) {
            $query->whereHas('subSpecificFormulations', function ($query) {
                $query->where('assigned', true);
            });
        })->get()->all();

        if ($list) {

            $budgetCentralizedActions = array_map(function ($budgetCentralizedAction) {
                return array(
                    'id' => $budgetCentralizedAction->id,
                    'text' => $budgetCentralizedAction->code . ' - ' . $budgetCentralizedAction->name,
                );
            }, $budgetCentralizedActions);

            array_unshift($budgetCentralizedActions, ['id' => "", 'text' => "Seleccione..."]);

            return $budgetCentralizedActions;
        }

        return $budgetCentralizedActions;
    }


    public function budgetAnalyticalMajor()
    {
        $budgetAvailability = $this->budgetAvailability(true);
        return view('budget::reports.budgetAnalyticalMajor', $budgetAvailability);
    }

    public function getbudgetAnalyticalMajorPdf(Request $request)
    {
        $data = $request->validate([
            'initialDate' => ['required', 'before_or_equal:finalDate'],
            'finalDate' => ['required', 'after_or_equal:initialDate'],
            'initialCode' => 'required',
            'finalCode' => 'required',
            'accountsWithMovements' => 'required',
        ]);

        $data = $request->toArray();
        $data["specific_actions_ids"] = json_decode('[' . $data["specific_actions_ids"] . ']', true);
        $ids = $data["specific_actions_ids"];

        if ($request->project_type === 'project') {
            $project = BudgetProject::with(['specificActions' => function ($query) use ($ids) {
                $query->with(['subSpecificFormulations' => function ($query) {
                    $query->with(['accountOpens' => function ($query) {
                        $query->with('budgetAccount');
                        $query->orderBy('id', 'desc');
                    }])->whereHas('accountOpens');
                }])->whereIn('id', $ids)->get();
            }])->find($data["project_id"]);
        } else {
            $project = BudgetCentralizedAction::with(['specificActions' => function ($query) use ($ids) {
                $query->with(['subSpecificFormulations' => function ($query) {
                    $query->with(['accountOpens' => function ($query) {
                        $query->with('budgetAccount');
                        $query->orderBy('id', 'desc');
                    }])->whereHas('accountOpens');
                }])->whereIn('id', $ids)->get();
            }])->find($data["project_id"]);
        }

        $records = $this->getBudgetAccountsOpen($data['accountsWithMovements'], $project);

        for ($i = 0; $i < count($records); $i++) {
            $records[$i][0] = $this->filterBudgetAccounts($records[$i][0], $data['initialCode'], $data['finalCode'], $data['initialDate'], $data['finalDate']);
        }

        $institution = Institution::find(1);
        $fiscal_year = FiscalYear::where('active', true)->first();
        $currency = Currency::where('default', true)->first();

        if ($request->exportReport === 'true') {
            return Excel::download(new RecordsExport([
                'records' => $records, 'institution' => $institution,
                'currencySymbol' => $currency['symbol'],
                'fiscal_year' => $fiscal_year['year'],
                'report_date' => \Carbon\Carbon::today()->format('d-m-Y'),
                'initialDate' => \Carbon\Carbon::rawCreateFromFormat('Y-m-d', $request['initialDate'])->format('d-m-Y'),
                'finalDate' => \Carbon\Carbon::rawCreateFromFormat('Y-m-d', $request['finalDate'])->format('d-m-Y'),
            ]), now()->format('d-m-Y') . '_Reporte_Mayor_Analitico.xlsx');
        } else {
            $pdf = new ReportRepository();
            $pdf->setConfig(['institution' => $institution, 'orientation' => 'L', 'format' => 'A2 LANDSCAPE', 'reportDate' => '']);
            $pdf->setHeader('', 'Reporte Mayor Analítico por Proyecto o Acción Centralizada');
            $pdf->setFooter();
            $pdf->setBody('budget::pdf.budgetAnalyticMajor', true, [
                'pdf' => $pdf,
                'records' => $records,
                'institution' => $institution,
                'currencySymbol' => $currency['symbol'],
                'fiscal_year' => $fiscal_year['year'],
                'report_date' => \Carbon\Carbon::today()->format('d-m-Y'),
                'initialDate' => \Carbon\Carbon::rawCreateFromFormat('Y-m-d', $request['initialDate'])->format('d-m-Y'),
                'finalDate' => \Carbon\Carbon::rawCreateFromFormat('Y-m-d', $request['finalDate'])->format('d-m-Y'),
            ]);
        }
    }
}
