<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Modules\Finance\Models\FinanceBank;
use Modules\Finance\Models\FinanceBankAccount;
use Illuminate\Validation\Rule;

/**
 * @class FinanceBankAccountController
 * @brief Controlador para las cuentas bancarias
 *
 * Clase que gestiona las cuentas bancarias
 *
 * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
 * @license <a href='http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/'>
 *              LICENCIA DE SOFTWARE CENDITEL
 *          </a>
 */
class FinanceBankAccountController extends Controller
{
    use ValidatesRequests;

     /**
     * Método constructor de la clase
     *
     * @author  Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     */
    public function __construct()
    {

        $this->customAttributes = [
            'ccc_number' => 'código cuenta cliente ',
            'description' => 'descripción',
            'opened_at' => 'fecha de apertura',
            'finance_banking_agency_id' => 'agencia',
            'finance_account_type_id' => 'tipo de cuenta',
        ];
    }
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json([
            'records' => FinanceBankAccount::with(['financeBankingAgency' => function ($query) {
                return $query->with('financeBank');
            }])->orderBy('ccc_number')->get()
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('finance::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'ccc_number' => ['required', 'numeric', 'digits_between:16, 20','unique:finance_bank_accounts,ccc_number'],
            'description' => ['required'],
            'opened_at' => ['required', 'date'],
            'finance_banking_agency_id' => ['required'],
            'finance_account_type_id' => ['required']
        ],[
            'ccc_number.digits_between' => "El campo código cuenta cliente debe tener 20 dígitos, incluyendo los 4 dígitos del código del banco.",
        ], $this->customAttributes);

        $ccc_number = $request->bank_code . $request->ccc_number;
        $ccc_numbers = FinanceBankAccount::where('ccc_number', $ccc_number)->first();
            if($ccc_numbers){
                 $error[0]= "El campo código cuenta cliente ya ha sido registrado.";
                return response()->json(['result' => true, 'errors' => ["code" => $error]], 422);
            }

        $financeBankAccount = FinanceBankAccount::create([
            'ccc_number' => $request->bank_code . $request->ccc_number,
            'description' => $request->description,
            'opened_at' => $request->opened_at,
            'finance_banking_agency_id' => $request->finance_banking_agency_id,
            'finance_account_type_id' => $request->finance_account_type_id
        ]);

        return response()->json(['record' => $financeBankAccount, 'message' => 'Success'], 200);
    }

    /**
     * Show the specified resource.
     * @return Renderable
     */
    public function show()
    {
        return view('finance::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Renderable
     */
    public function edit()
    {
        return view('finance::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        /** @var object Datos de la cuenta bancaria */
        $bankAccount = FinanceBankAccount::find($id);
        
        $this->validate($request, [
            'ccc_number' => [
                'required', 'numeric', 'digits_between:16, 20','unique:finance_bank_accounts,ccc_number,'. $bankAccount->id
            ],
            'description' => ['required'],
            'opened_at' => ['required', 'date'],
            'finance_banking_agency_id' => ['required'],
            'finance_account_type_id' => ['required']
        ], [
            'ccc_number.digits_between' => "El campo código cuenta cliente debe tener 20 dígitos, incluyendo los 4 dígitos del código del banco.",
        ], $this->customAttributes);

        $ccc_number = $request->bank_code . $request->ccc_number;
        $ccc_numbers = FinanceBankAccount::where(['id' => $id, 'ccc_number' => $ccc_number])->first();
            if(!$ccc_numbers){
                $error[0]= "El campo código cuenta cliente ya existe.";
                return response()->json(['result' => true, 'errors' => ["code" => $error]], 422);
            }
        
        $bankAccount->ccc_number = $request->bank_code . $request->ccc_number;
        $bankAccount->description = $request->description;
        $bankAccount->opened_at = $request->opened_at;
        $bankAccount->finance_banking_agency_id = $request->finance_banking_agency_id;
        $bankAccount->finance_account_type_id = $request->finance_account_type_id;
        $bankAccount->save();

        return response()->json(['message' => 'Registro actualizado correctamente'], 200);
    }

    /**
     * Remove the specified resource from storage.
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        /** @var object Datos de la cuenta bancaria */
        $financeBankAccount = FinanceBankAccount::find($id);
        $financeBankAccount->delete();
        return response()->json(['record' => $financeBankAccount, 'message' => 'Success'], 200);
    }

    /**
     * Obtiene todas las cuentas bancarias asociadas a una entidad bancaria
     *
     * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     * @param  integer $bank_id                 Identificador de la entidad bancaria de la que se
     *                                          desean obtener las cuentas
     * @return \Illuminate\Http\JsonResponse    JSON con los datos de las cuentas bancarias asociadas
     *                                          al banco
     */
    public function getBankAccounts($bank_id)
    {
        /** @var object Datos de la entidad bancaria */
        $bank = FinanceBank::where('id', $bank_id)->with(['financeAgencies' => function ($query) {
            return $query->with('bankAccounts');
        }])->first();

        $accounts = [['id' => '', 'text' => 'Seleccione...']];
        foreach ($bank->financeAgencies as $agency) {
            foreach ($agency->bankAccounts as $bank_account) {
                $accounts[] = [
                    'id' => $bank_account->id,
                    'text' => $bank->code . $bank_account->ccc_number
                ];
            }
        }

        return response()->json(['result' => true, 'accounts' => $accounts], 200);
    }
}
