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
        ];

        /** Define los mensajes de validación para las reglas del formulario */
        $this->messages = [
            'institution_id.required' => 'El campo institución es obligatorio.',
            'compromised_at.required' => 'El campo fecha es obligatorio.',
            'source_document.required' => 'El campo documento origen es obligatorio.',
            'source_document.unique' => 'El campo documento origen ya ha sido registrado.',
            'description.required' => 'El campo descripción es obligatorio.',
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

        DB::transaction(function () use ($request, $code, $compromisedYear) {
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
        $documentStatus = DocumentStatus::where('action', 'AP')->first();

        $budgetCentralizedAction = BudgetCompromise::find($request->id);
        $budgetCentralizedAction->document_number = $request->source_document;
        $budgetCentralizedAction->institution_id = $request->institution_id;
        $budgetCentralizedAction->compromised_at = $request->compromised_at;
        $budgetCentralizedAction->description = $request->description;
        $budgetCentralizedAction->code = $code;
        $budgetCentralizedAction->document_status_id = $documentStatus->id;

        $budgetCentralizedAction->save();

        $total = 0;

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

            $budgetCentralizedAction->budgetCompromiseDetails()->Create(
                [
                    'description' => $account['description'],
                    'amount' => $account['amount'],
                    'tax_amount' => $taxAmount,
                    'tax_id' => $account['account_tax_id'] ?? $account['tax_id'],
                    'budget_account_id' => $account['account_id'],
                    'budget_sub_specific_formulation_id' => $formulation->id,
                ]);
            $total += ($account['amount'] + $taxAmount);
        }

        $budgetCentralizedAction->budgetStages()->update([
            'code' => $code,
            'registered_at' => $request->compromised_at,
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
    public function destroy()
    {
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
                'budgetCompromiseDetails',
                'budgetStages',
                'documentStatus'
            )->orderBy('compromised_at')->get(),
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
        $compromises = BudgetCompromise::where(['compromised_at' => null, 'institution_id' => $institution_id])->with([
            'budgetCompromiseDetails',
            'sourceable',
            'budgetStages' => function ($budgetStages) {
                return $budgetStages->where('type', 'PRE');
            },
        ])->get();
        return response(['records' => $compromises], 200);
    }
}
