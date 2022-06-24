<?php

namespace Modules\Payroll\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Modules\Payroll\Models\PayrollSalaryAdjustment;
use Modules\Payroll\Models\PayrollSalaryTabulatorScale;
use Illuminate\Support\Facades\DB;

/**
 * @class      PayrollSalaryAdjustmentController
 * @brief      Controlador de ajustes en tablas salariales
 *
 * Clase que gestiona los ajustes en tablas salariales
 *
 * @author     Henry Paredes <hparedes@cenditel.gob.ve>
 * @license
 *     [LICENCIA DE SOFTWARE CENDITEL](http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/)
 */
class PayrollSalaryAdjustmentController extends Controller
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
     * @author     Henry Paredes <hparedes@cenditel.gob.ve>
     */
    public function __construct()
    {
        /** Establece permisos de acceso para cada método del controlador */
        //$this->middleware('permission:payroll.salary-adjustments.create', ['only' => ['create', 'store']]);

        /** Define las reglas de validación para el formulario */
        $this->validateRules = [
            'created_at'                  => ['required'],
            'increase_of_date'            => ['required'],
            'payroll_salary_tabulator_id' => ['required'],
            'increase_of_type'            => ['required']
        ];

        /** Define los mensajes de validación para las reglas del formulario */
        $this->messages = [
            'created_at.required'                  => 'El campo fecha de generación es obligatorio.',
            'increase_of_date.required'            => 'El campo fecha del aumento es obligatorio.',
            'payroll_salary_tabulator_id.required' => 'El campo tabulador salarial es obligatorio.',
            'increase_of_type.required'            => 'El campo tipo de aumento es obligatorio.'
        ];
    }

    /**
     * Muestra el formulario para registrar un nuevo ajuste en las tablas salariales
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     *
     * @return    Renderable
     */
    public function create()
    {
        return view('payroll::salary_adjustments.create');
    }

    /**
     * Valida y registra una nueva nómina de sueldos
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @author    Daniel Contreras <dcontreras@cenditel.gob.ve>
     *
     * @param     \Illuminate\Http\Request         $request    Datos de la petición
     *
     * @return    \Illuminate\Http\JsonResponse                Objeto con los registros a mostrar
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->validateRules, $this->messages);

        DB::transaction(function () use ($request) {
            /**
             * Objeto asociado al modelo PayrollSalaryTabulator
             * @var Object $salaryTabulator
             */
            $salaryTabulator = PayrollSalaryAdjustment::create([
                'increase_of_date'                   => $request->input('increase_of_date'),
                'increase_of_type'                   => $request->input('increase_of_type'),
                'value'                              => $request->input('value'),
                'payroll_salary_tabulator_id'        => $request->input('payroll_salary_tabulator_id')
            ]);

            if ($salaryTabulator) {
                /** Se agregan las escalas del tabulador salarial */
                foreach ($request->payroll_salary_tabulator['payroll_salary_tabulator_scales'] as $payrollScale) {
                    /**
                     * Objeto asociado al modelo PayrollSalaryTabulatorScale
                     * @var Object $salaryTabulatorScale
                     */
                    $salaryTabulatorScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $request->payroll_salary_tabulator_id)
                                            ->first();
                    foreach ($request->scale_values as $scale_value) {
                        $valueScale = $scale_value;
                        $value = $request->increase_of_type == 'percentage' ?
                                 $payrollScale['value'] + $valueScale :
                                 $valueScale;
                        $salaryTabulatorScale->value                       = $value;
                        $salaryTabulatorScale->payroll_vertical_scale_id   = $payrollScale['payroll_vertical_scale_id'] ?? null;
                        $salaryTabulatorScale->payroll_horizontal_scale_id = $payrollScale['payroll_horizontal_scale_id'] ?? null;
                        $salaryTabulatorScale->payroll_salary_tabulator_id = $request->payroll_salary_tabulator_id;
                        $salaryTabulatorScale->save();
                    }
                }
            }
        });

        $request->session()->flash('message', ['type' => 'store']);
        return response()->json(['redirect' => route('payroll.salary-adjustments.create')], 200);
    }
}
