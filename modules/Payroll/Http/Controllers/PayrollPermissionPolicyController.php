<?php

namespace Modules\Payroll\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Modules\Payroll\Models\PayrollPermissionPolicy;
use Modules\Payroll\Rules\PayrollPermissionPolicyDaysRange;

class PayrollPermissionPolicyController extends Controller
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

    public function __construct()
    {

       /** Define las reglas de validación para el formulario */
        $this->validateRules = [
           'name'             => ['required', 'max:100', 'unique:payroll_permission_policies,name'],
           'anticipation_day' => ['required'],
           'day_min'          => ['required'],
           'day_max'          => ['required'],
           'institution_id'   => ['required']
        ];

       /** Define los mensajes de validación para las reglas del formulario */
        $this->messages = [
           'name.required'    => 'El campo nombre es obligatorio.',
           'name.max'         => 'El campo nombre no debe contener más de 100 caracteres.',
           'name.unique'      => 'El campo nombre ya ha sido registrado.',
           'anticipation_day.required' => 'El campo días de anticipación es obligatorio.',
           'day_min.required' => 'El campo rango mínimo es obligatorio.',
           'day_max.required' => 'El campo rango máximo es obligatorio.',
           'institution_id.required' => 'El campo organización es obligatorio.',
        ];
    }
    /**
     * @author Yennifer Ramirez <yramirez@cenditel.gob.ve>
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return response()->json(['records' => PayrollPermissionPolicy::all()], 200);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('payroll::create');
    }

    /**
     *
     * Valida y registra un nuevo tipo de permiso
     * @author Yennifer Ramirez <yramirez@cenditel.gob.ve>
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $validateRules  = $this->validateRules;
        $validateRules  = array_merge(
            ['id' => [new PayrollPermissionPolicyDaysRange($request->input('day_min'), $request->input('day_max'))]],
            $validateRules
        );
        $this->validate($request, $validateRules, $this->messages);

        $payrollPermissionPolicy = PayrollPermissionPolicy::create([
            'name'             => $request->name,
            'anticipation_day' => $request->anticipation_day,
            'day_min'          => $request->input('day_min'),
            'day_max'          => $request->input('day_max'),
            'active'           => $request->active,
            'institution_id'   => $request->institution_id
        ]);
        return response()->json(['record' => $payrollPermissionPolicy, 'message' => 'Success'], 200);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('payroll::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('payroll::edit');
    }

    /**
     * Actualiza la información del tipo de permiso
     * @author Yennifer Ramirez <yramirez@cenditel.gob.ve>
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $validateRules  = $this->validateRules;
        $validateRules  = array_merge(
            ['id' => [new PayrollPermissionPolicyDaysRange($request->input('day_min'), $request->input('day_max'))]],
            $validateRules
        );

        $this->validate($request, $validateRules, $this->messages);

        $payrollPermissionPolicy = PayrollPermissionPolicy::find($id);
        $payrollPermissionPolicy->name             = $request->name;
        $payrollPermissionPolicy->anticipation_day = $request->anticipation_day;
        $payrollPermissionPolicy->day_min          = $request->input('day_min');
        $payrollPermissionPolicy->day_max          = $request->input('day_max');
        $payrollPermissionPolicy->active           = $request->active;
        $payrollPermissionPolicy->institution_id   = $request->institution_id;
        $payrollPermissionPolicy->save();

        return response()->json(['message' => 'Success'], 200);
    }

    /**
     * Elimina el tipo de permiso
     * @author Yennifer Ramirez <yramirez@cenditel.gob.ve>
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */

    public function destroy($id)
    {
        $payrollPermissionPolicy = PayrollPermissionPolicy::find($id);
        $payrollPermissionPolicy->delete();
        return response()->json(['record' => $payrollPermissionPolicy, 'message' => 'Success'], 200);
    }

    public function getPermissionPolicies()
    {
        return template_choices('Modules\Payroll\Models\PayrollPermissionPolicy', 'name', [], true, null);
    }
}
