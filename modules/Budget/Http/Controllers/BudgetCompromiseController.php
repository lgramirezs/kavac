<?php

namespace Modules\Budget\Http\Controllers;

use App\Models\CodeSetting;
use App\Models\DocumentStatus;
use App\Models\Tax;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Budget\Models\BudgetCompromise;
use Modules\Budget\Models\BudgetCompromiseDetail;
use Modules\Budget\Models\BudgetSpecificAction;
use Modules\Budget\Models\BudgetStage;
use Modules\Budget\Models\BudgetAccountOpen;
use Modules\Purchase\Models\PurchaseRequirement;

class BudgetCompromiseController extends Controller
{
    use ValidatesRequests;

    /**
     * Arreglo con las reglas de validación sobre los datos de un formulario
     * @var Array $validateRules
     */
    protected $validateRules;

    /**
     * Arreglo con los mensajes para las reglas de validación
     * @var Array $messages
     */
    protected $messages;

    /**
     * Define la configuración de la clase
     *
     * @author    Daniel Contreras <dcontreras@cenditel.gob.ve> | <exodiadaniel@gmail.com>
     */
    public function __construct()
    {
        /** Define las reglas de validación para el formulario */
        $this->validateRules = [
            'institution_id' => ['required'],
            'compromised_at' => ['required', 'date'],
            'source_document' => ['required', 'unique:budget_compromises,document_number'],
            'description' => ['required'],
            'accounts.*.account_id' => ['required'],
            'accounts.*.specific_action_id' => ['required'],
        ];

        /** Define los mensajes de validación para las reglas del formulario */
        $this->messages = [
            'institution_id.required' => 'El campo institución es obligatorio.',
            'compromised_at.required' => 'El campo fecha es obligatorio.',
            'source_document.required' => 'El campo documento origen es obligatorio.',
            'source_document.unique' => 'El campo documento origen ya ha sido registrado.',
            'description.required' => 'El campo descripción es obligatorio.',
            'accounts.*.specific_action_id.required' => 'El campo acción específica es obligatorio',
            'accounts.*.account_id.required' => 'El campo cuenta es obligatorio',
        ];
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('budget::compromises.list');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('budget::compromises.create-edit-form');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->validateRules, $this->messages);

        $codeSetting = CodeSetting::where("model", BudgetCompromise::class)->first();

        if (!$codeSetting) {
            return response()->json(['result' => false, 'message' => [
                'type' => 'custom', 'title' => 'Alerta', 'icon' => 'screen-error', 'class' => 'danger',
                'text' => 'Debe configurar previamente el formato para el código a generar',
            ]], 200);
        }

        $year = $request->fiscal_year ?? date("Y");

        $code = generate_registration_code(
            $codeSetting->format_prefix,
            strlen($codeSetting->format_digits),
            (strlen($codeSetting->format_year) === 2) ? date("y") : $year,
            BudgetCompromise::class,
            'code'
        );

        $compromisedYear = explode("-", $request->compromised_at)[0];

        $codeStage = generate_registration_code('STG', '00000000', 'YYYY', BudgetStage::class, 'code');

        DB::transaction(function () use ($request, $code, $codeStage, $compromisedYear) {
            /** @var Object Estado inicial del compromiso establecido a elaborado */
            $documentStatus = DocumentStatus::where('action', 'AP')->first();

            /** @var Object Datos del compromiso */
            $compromise = BudgetCompromise::create([
                'document_number' => $request->source_document,
                'institution_id' => $request->institution_id,
                'compromised_at' => $request->compromised_at,
                'description' => $request->description,
                'code' => $code,
                'document_status_id' => $documentStatus->id,
            ]);

            $total = 0;
            $totalEdit = 0;
            /** Gestiona los ítems del compromiso */
            foreach ($request->accounts as $account) {
                $spac = BudgetSpecificAction::find($account['specific_action_id']);
                $formulation = $spac->subSpecificFormulations()->where('year', $compromisedYear)->first();
                $tax = (isset($account['account_tax_id']) || isset($account['tax_id']))
                ? Tax::find($account['account_tax_id'] ?? $account['tax_id'])
                : new Tax();
                $taxHistory = ($tax) ? $tax->histories()->orderBy('operation_date', 'desc')->first() : new Tax();
                $taxAmount = ($account['amount'] * (($taxHistory) ? $taxHistory->percentage : 0)) / 100;
                $compromise->budgetCompromiseDetails()->create([
                    'description' => $account['description'],
                    'amount' => $account['amount'],
                    'tax_amount' => $taxAmount,
                    'tax_id' => $account['account_tax_id'] ?? $account['tax_id'],
                    'budget_account_id' => $account['account_id'],
                    'budget_sub_specific_formulation_id' => $formulation->id,
                ]);
                $total += ($account['amount'] + $taxAmount);
                $totalEdit = ($account['amount'] + $taxAmount);

                $budgetAccountOpen = BudgetAccountOpen::where('budget_sub_specific_formulation_id', $formulation->id)
                                                            ->where('budget_account_id', $account['account_id'])
                                                            ->first();
                $budgetAccountOpen->total_year_amount_m = $budgetAccountOpen->total_year_amount_m - $totalEdit;
                        $budgetAccountOpen->save();
            }

            $compromise->budgetStages()->create([
                'code' => $code,
                'registered_at' => $request->compromised_at,
                'type' => 'COM',
                'amount' => $total,
            ]);

            $request->session()->flash('message', ['type' => 'store']);
        });

        return response()->json(['result' => true, 'redirect' => route('budget.compromises.index')], 200);
    }

    /**
     * Show the specified resource.
     * @return Renderable
     */
    public function show()
    {
        //return view('budget::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Renderable
     *  @author    Francisco Escala 
     */
    public function edit($id)
    {
     

        $budgetCompromise = BudgetCompromise::with(['budgetCompromiseDetails' => function ($query) {
            $query->with(['budgetSubSpecificFormulation' => function ($query) {
                $query->with(['specificAction' => function ($query) {

                }]);
            }]);
        }])->find($id);

        return view('budget::compromises.create-edit-form', compact('budgetCompromise'));
    }

    /**
     * Update the specified resource in storage.ç
     *  @author    Francisco Escala 
     * @param  Request $request
     * @return Renderable
     */
    public function update(Request $request)
    {
        $year = $request->fiscal_year ?? date("Y");

        $compromisedYear = explode("-", $request->compromised_at)[0];
        $documentStatus = DocumentStatus::where('action', 'AP')->first();

        $budget = BudgetCompromise::find($request->id);
        $this->validateRules['source_document'] = ['required', 'unique:budget_compromises,document_number,' . $budget->id];
        $this->validate($request, $this->validateRules, $this->messages);

        $budget->document_number = $request->source_document;
        $budget->institution_id = $request->institution_id;
        $budget->compromised_at = $request->compromised_at;
        $budget->description = $request->description;
        $budget->document_status_id = $documentStatus->id;
        $budget->save();

        $total = 0;
        $totalEdit = 0;

        /** Gestiona los ítems del compromiso */
        $deleted = BudgetCompromiseDetail::where('budget_compromise_id', $request->id)->delete();

        foreach ($request->accounts as $account) {
            $spac = BudgetSpecificAction::find($account['specific_action_id']);
            $formulation = $spac->subSpecificFormulations()->where('year', $compromisedYear)->first();
            $tax = (isset($account['account_tax_id']) || isset($account['tax_id']))
            ? Tax::find($account['account_tax_id'] ?? $account['tax_id'])
            : new Tax();
            $taxHistory = ($tax) ? $tax->histories()->orderBy('operation_date', 'desc')->first() : new Tax();
            $taxAmount = ($account['amount'] * (($taxHistory) ? $taxHistory->percentage : 0)) / 100;

            $budget->budgetCompromiseDetails()->Create(
                [
                    'description' => $account['description'],
                    'amount' => $account['amount'],
                    'tax_amount' => $taxAmount,
                    'tax_id' => $account['account_tax_id'] ?? $account['tax_id'],
                    'budget_account_id' => $account['account_id'],
                    'budget_sub_specific_formulation_id' => $formulation->id,
                ]);
            $total += ($account['amount'] + $taxAmount);
            $totalEdit = ($account['amountEdit'] + $taxAmount);

            $budgetAccountOpen = BudgetAccountOpen::where('budget_sub_specific_formulation_id', $formulation->id)
                                                            ->where('budget_account_id', $account['account_id'])
                                                            ->first();
            if ($account['operation'] == 'I') {
                $budgetAccountOpen->update([
                    'total_year_amount_m' => $budgetAccountOpen->total_year_amount_m,
                ]);
            } elseif ($account['operation'] == 'S') {
                $budgetAccountOpen->update([
                    'total_year_amount_m' => $budgetAccountOpen->total_year_amount_m + $totalEdit,
                ]);
            } elseif ($account['operation'] == 'R') {
                $budgetAccountOpen->update([
                    'total_year_amount_m' => $budgetAccountOpen->total_year_amount_m - ($totalEdit < 0 ? $totalEdit * -1 : $totalEdit),
                ]);
            } else {
                $budgetAccountOpen->total_year_amount_m = $budgetAccountOpen->total_year_amount_m - ($account['amount'] + $taxAmount);
                        $budgetAccountOpen->save();
            }
        }

        $budget->budgetStages()->update([
            'type' => 'COM',
            'amount' => $total,
        ]);

        $request->session()->flash('message', ['type' => 'store']);

        return response()->json(['result' => true, 'redirect' => route('budget.compromises.index')], 200);

    }

    /**
     * Remove the specified resource from storage.
     * @return Renderable
     */
    public function destroy($id)
    {
        /** @var object Objeto con información del compromiso a eliminar */
        $budgetCompromise = BudgetCompromise::find($id);
        $compromisedYear = explode("-", $budgetCompromise->compromised_at)[0];

        if ($budgetCompromise) {
            $budgetCompromiseDetails = BudgetCompromiseDetail::where('budget_compromise_id', $id)->get();

            foreach ($budgetCompromiseDetails as $budgetCompromiseDetail) {
                $formulation = $budgetCompromiseDetail->budgetSubSpecificFormulation()->where('year', $compromisedYear)->first();
                $tax = isset($budgetCompromiseDetail['tax_id'])
                ? Tax::find($budgetCompromiseDetail['tax_id'])
                : new Tax();
                $taxHistory = ($tax) ? $tax->histories()->orderBy('operation_date', 'desc')->first() : new Tax();
                $taxAmount = ($budgetCompromiseDetail['amount'] * (($taxHistory) ? $taxHistory->percentage : 0)) / 100;
                $total = ($budgetCompromiseDetail['amount'] + $taxAmount);

                $budgetAccountOpen = BudgetAccountOpen::where('budget_sub_specific_formulation_id', $formulation->id)
                                                            ->where('budget_account_id', $budgetCompromiseDetail['budget_account_id'])
                                                            ->first();
                $budgetAccountOpen->update([
                    'total_year_amount_m' => $budgetAccountOpen->total_year_amount_m + $total,
                ]);
            }

            $budgetCompromise->delete();
            $budgetCompromiseDetailsDelete = BudgetCompromiseDetail::where('budget_compromise_id', $id)->delete();
        }

        return response()->json(['record' => $budgetCompromise, 'message' => 'Success'], 200);
    }

    /**
     * Obtiene los registros a mostrar en listados de componente Vue
     *
     * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     * @return \Illuminate\Http\JsonResponse Devuelve un JSON con la información de las formulaciones
     */
    public function vueList()
    {
        return response()->json([
            'records' => BudgetCompromise::with(
                'budgetCompromiseDetails.budgetAccount',
                'budgetCompromiseDetails.budgetSubSpecificFormulation',
                'budgetCompromiseDetails.tax',
                'budgetStages',
                'documentStatus',
                'institution'
            )->whereHas('budgetStages', function ($query) {
                $query->where('type', 'COM');
            })->orderBy('compromised_at')->get(),
        ], 200);
    }

    /**
     * Obtiene las fuentes de documentos que aún no han sido comprometidos, solo (PRE)comprometidos y/o (PRO)gramados
     *
     * @method     getDocumentSources
     *
     * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     *
     * @param      integer               $institution_id    Identificador de la institución
     * @param      string                $year              Año de ejercicio económico
     *
     * @return     \Illuminate\Http\JsonResponse    Devuelve un JSON con la información de registros por comprometer
     */
    public function getDocumentSources($institution_id, $year)
    {
        /** @var object Obtiene todos los registros de fuentes de documentos que aún no han sido comprometidos */
        $compromises = BudgetCompromise::where('institution_id', $institution_id)->with([
            'budgetCompromiseDetails',
            'sourceable',
            'budgetStages'
        ])->whereHas('budgetStages', function ($query) {
            $query->where('type', 'PRE');
        })->get();

        return response(['records' => $compromises], 200);
    }
}
