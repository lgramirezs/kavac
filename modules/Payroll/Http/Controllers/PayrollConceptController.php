<?php

namespace Modules\Payroll\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Modules\Payroll\Models\PayrollConcept;
use Modules\Payroll\Models\PayrollConceptAssignOption;

/**
 * @class      PayrollConceptController
 * @brief      Controlador de conceptos
 *
 * Clase que gestiona los conceptos
 *
 * @author     Henry Paredes <hparedes@cenditel.gob.ve>
 * @license
 *     [LICENCIA DE SOFTWARE CENDITEL](http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/)
 */
class PayrollConceptController extends Controller
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
     * Arreglo con los registros de opciones a asignar el concepto
     * @var Array $assignTo
     */
    protected $assignTo;

    /**
     * Define la configuración de la clase
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     */
    public function __construct()
    {
        /** Establece permisos de acceso para cada método del controlador */
        //$this->middleware('permission:payroll.concept.list',   ['only' => 'index']);
        //$this->middleware('permission:payroll.concept.create', ['only' => 'store']);
        //$this->middleware('permission:payroll.concept.edit',   ['only' => 'update']);
        //$this->middleware('permission:payroll.concept.delete', ['only' => 'destroy']);

        /** Define las reglas de validación para el formulario */
        $this->validateRules = [
            'name'                    => ['required', 'unique:payroll_concepts,name'],
            'payroll_concept_type_id' => ['required'],
            'institution_id'          => ['required'],
            'assign_to'               => ['required'],
            'currency_id'             => ['required']
        ];

        /** Define los mensajes de validación para las reglas del formulario */
        $this->messages = [
            'payroll_concept_type_id.required'     => 'El campo tipo de concepto es obligatorio.',
            'institution_id.required'              => 'El campo institución es obligatorio.',
            'assign_to.required'                   => 'El campo "¿asignar a?" es obligatorio.',
            'payroll_salary_tabulator_id.required' => 'El campo tabulador salarial es obligatorio.',
            'formula.required'                     => 'El campo fórmula es obligatorio.',
            'currency_id.required'                 => 'El campo moneda es obligatorio'
        ];

        /** Define las opciones del campo "asignar a" a emplear en el formulario */
        $this->assignTo = [
            [
                'id'    => 'all',
                'name'  => 'Todos los trabajadores',
                'model' => 'Modules\Payroll\Models\PayrollStaff',
                'type'  => ''
            ],
            [
                'id'    => 'all_active_staff',
                'name'  => 'Todos los trabajadores activos',
                'model' => 'Modules\Payroll\Models\PayrollStaff',
                'type'  => ''
            ],
            [
                'id'    => 'all_except_staffs_in_vacation',
                'name'  => 'Todos excepto trabajadores que se encuentren en período de vacaciones',
                'model' => 'Modules\Payroll\Models\PayrollStaff',
                'type'  => ''
            ],
            [
                'id'    => 'all_except_staffs_at_rest',
                'name'  => 'Todos excepto trabajadores que se encuentren de reposo',
                'model' => 'Modules\Payroll\Models\PayrollStaff',
                'type'  => ''
            ],
            [
                'id'    => 'all_except_staffs_in_vacation_and_rest',
                'name'  => 'Todos excepto trabajadores que se encuentren en período de vacaciones y reposo',
                'model' => 'Modules\Payroll\Models\PayrollStaff',
                'type'  => ''
            ],
            [
                'id'    => 'all_except_disabled_staff',
                'name'  => 'Todos excepto trabajadores discapacitados',
                'model' => 'Modules\Payroll\Models\PayrollStaff',
                'type'  => ''
            ],
            [
                'id'    => 'all_except_staff_on_leave',
                'name'  => 'Todos excepto trabajadores que se encuentren de permiso',
                'model' => 'Modules\Payroll\Models\PayrollStaff',
                'type'  => ''
            ],
            [
                'id'    => 'all_retired_staff',
                'name'  => 'Todos los trabajadores jubilados',
                'model' => 'Modules\Payroll\Models\PayrollStaff',
                'type'  => ''
            ],
            [
                'id'    => 'all_disabled_staff',
                'name'  => 'Todos los trabajadores con discapacidad',
                'model' => 'Modules\Payroll\Models\PayrollStaff',
                'type'  => ''
            ],
            [
                'id'    => 'all_studying_staff',
                'name'  => 'Todos los trabajadores que cursen estudios',
                'model' => 'Modules\Payroll\Models\PayrollStaff',
                'type'  => ''
            ],
            [
                'id'    => 'all_staff_with_sons',
                'name'  => 'Todos los trabajadores con hijos',
                'model' => 'Modules\Payroll\Models\PayrollStaff',
                'type'  => 'range'
            ],
            [
                'id'    => 'all_staff_with_sons_studying',
                'name'  => 'Todos los trabajadores con hijos que cursen estudios',
                'model' => 'Modules\Payroll\Models\PayrollStaff',
                'type'  => ''
            ],
            [
                'id'    => 'staff',
                'name'  => 'Trabajadores',
                'model' => 'Modules\Payroll\Models\PayrollStaff',
                'field' => ['first_name', ' ','last_name'],
                'type'  => 'list'
            ],
            [
                'id'    => 'staff_master_the_languages',
                'name'  => 'Trabajadores que dominen más de un idioma',
                'model' => 'Modules\Payroll\Models\PayrollLanguage',
                'type'  => 'list'
            ],
            [
                'id'    => 'staff_according_instruction_degree',
                'name'  => 'Trabajadores de acuerdo a su nivel de instrucción',
                'model' => 'Modules\Payroll\Models\PayrollInstructionDegree',
                'type'  => 'list'
            ],
            [
                'id'    => 'staff_according_antiquity_years',
                'name'  => 'Trabajadores de acuerdo a sus años de antiguedad en la institución',
                'model' => 'Modules\Payroll\Models\PayrollStaff',
                'type'  => 'range'
            ],
            [
                'id'    => 'staff_according_antiquity_years',
                'name'  => 'Trabajadores de acuerdo a sus años de antiguedad en la administración pública',
                'model' => 'Modules\Payroll\Models\PayrollStaff',
                'type'  => 'range'
            ],
            [
                'id'    => 'staff_according_department',
                'name'  => 'Trabajadores de acuerdo al departamento al que pertenece',
                'model' => 'Modules\Payroll\Models\Department',
                'type'  => 'list'
            ],
            [
                'id'    => 'staff_according_position_type',
                'name'  => 'Trabajadores de acuerdo al tipo de cargo al que pertenece',
                'model' => 'Modules\Payroll\Models\PayrollPositionType',
                'type'  => 'list'
            ],
            [
                'id'    => 'staff_according_contract_type',
                'name'  => 'Trabajadores de acuerdo al tipo de contrato al que pertenece',
                'model' => 'Modules\Payroll\Models\PayrollContractType',
                'type'  => 'list'
            ],
            [
                'id'    => 'staff_according_rol',
                'name'  => 'Trabajadores de acuerdo al rol al que pertenece',
                'model' => 'Modules\Payroll\Models\Role',
                'type'  => 'list'
            ],
            [
                'id'    => 'staff_according_staff_type',
                'name'  => 'Trabajadores de acuerdo al tipo de personal al que pertenece',
                'model' => 'Modules\Payroll\Models\PayrollStaffType',
                'type'  => 'list'
            ],
            [
                'id'    => 'staff_according_gender',
                'name'  => 'Trabajadores de acuerdo al género al que pertenece',
                'model' => 'Modules\Payroll\Models\PayrollGender',
                'type'  => 'list'
            ],
            [
                'id'    => 'staff_according_years_old',
                'name'  => 'Trabajadores de acuerdo a la edad que poseen',
                'model' => 'Modules\Payroll\Models\PayrollStaff',
                'type'  => 'range'
            ]
        ];
    }

    /**
     * Muestra un listado de los conceptos registradas (activos e inactivos)
     *
     * @method    index
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     *
     * @return    JsonResponse    Objeto con los registros a mostrar
     */
    public function index()
    {
        /**
         * Objeto asociado al modelo PayrollConcept
         * @var Object $payrollConcepts
         */
        $payrollConcepts = PayrollConcept::with('payrollSalaryTabulator', 'payrollConceptAssignOptions')->get();
        foreach ($payrollConcepts as $payrollConcept) {
            $assign_to = json_decode($payrollConcept->assign_to);
            $assign_options = [];
            if ($assign_to) {
                foreach ($assign_to as $field) {
                    if ($field->type) {
                        $key = $field->id;
                        $options = [];
                        if ($payrollConcept->payrollConceptAssignOptions) {
                            foreach ($payrollConcept->payrollConceptAssignOptions as $assign_option) {
                                if ($key == $assign_option['key']) {
                                    if ($field->type == 'range') {
                                        $options = json_decode($assign_option['value']);
                                    } elseif ($field->type == 'list') {
                                        $option = $field->model::find($assign_option['assignable_id']);
                                        array_push(
                                            $options,
                                            [
                                                'id'   => $assign_option['assignable_id'],
                                                'text' => $option->name
                                            ]
                                        );
                                    }
                                }
                            }
                            $assign_options[$field->id] = $options;
                        }
                    }
                }
            }
            $payrollConcept->assign_to = $assign_to;
            $payrollConcept->assign_options = json_decode(json_encode($assign_options));
        }
        return response()->json(['records' => $payrollConcepts], 200);
    }

    /**
     * Valida y registra un nuevo concepto
     *
     * @method    store
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     *
     * @param     \Illuminate\Http\Request     $request    Datos de la petición
     *
     * @return    JsonResponse                Objeto con los registros a mostrar
     */
    public function store(Request $request)
    {
        /**
         * FALTA:
         * 1-. validar assign_options dependiendo de assign_to
         * Revisar regla de validación
         */
        $this->validate($request, $this->validateRules, $this->messages);

        /**
         * Objeto asociado al modelo PayrollConcept
         * @var Object $payrollConcept
         */
        $payrollConcept = PayrollConcept::create([
            'name'                        => $request->name,
            'description'                 => $request->description ?? '',
            'active'                      => !empty($request->active)
                                                 ? $request->active
                                                 : false,
            'formula'                     => $request->formula,
            'institution_id'              => $request->institution_id,
            'payroll_concept_type_id'     => $request->payroll_concept_type_id,
            'accounting_account_id'       => $request->accounting_account_id,
            'budget_account_id'           => $request->budget_account_id,
            'assign_to'                   => json_encode($request->assign_to),
            'currency_id'                 => $request->currency_id

        ]);
        foreach ($request->assign_to as $assign_to) {
            if ($assign_to['type'] == 'range') {
                PayrollConceptAssignOption::create([
                    'key'                => $assign_to['id'],
                    'value'              => json_encode($request->assign_options[$assign_to['id']]),
                    'applicable_type' => PayrollConcept::class,
                    'applicable_id' => $payrollConcept->id,
                ]);
            } elseif ($assign_to['type'] == 'list') {
                foreach ($request->assign_options[$assign_to['id']] as $assign_option) {
                    /**
                     * Objeto asociado al modelo PayrollConceptAssignOption
                     * @var Object $payrollConceptAssignOption
                     */
                    $payrollConceptAssignOption = PayrollConceptAssignOption::create([
                        'key'                => $assign_to['id'],
                        'applicable_type' => PayrollConcept::class,
                        'applicable_id' => $payrollConcept->id,
                    ]);
                    /** Se guarda la información en el campo morphs */
                    $option = $assign_to['model']::find($assign_option['id']);
                    $option->payrollConceptAssignOptions()->save($payrollConceptAssignOption);
                }
            }
        };
        return response()->json(['record' => $payrollConcept, 'message' => 'Success'], 200);
    }

    /**
     * Actualiza la información de una asignación salarial
     *
     * @method    update
     *
     * @param     \Illuminate\Http\Request         $request    Datos de la petición
     *
     * @return    \Illuminate\Http\JsonResponse                Objeto con los registros a mostrar
     */
    public function update(Request $request, $id)
    {
        /**
         * Objeto con la información del concepto a editar asociado al modelo PayrollConcept
         * @var Object $payrollConcept
         */
        $payrollConcept = PayrollConcept::find($id);
        $validateRules  = $this->validateRules;
        $validateRules  = array_replace(
            $validateRules,
            ['name' => ['required', 'unique:payroll_concepts,name,' . $payrollConcept->id]]
        );
        $this->validate($request, $validateRules, $this->messages);

        $payrollConcept->name                        = $request->name;
        $payrollConcept->description                 = $request->description ?? '';
        $payrollConcept->active                      = !empty($request->active)
                                                           ? $request->active
                                                           : $payrollConcept->active;
        $payrollConcept->formula                     = $request->formula;
        $payrollConcept->institution_id              = $request->institution_id;
        $payrollConcept->payroll_concept_type_id     = $request->payroll_concept_type_id;
        $payrollConcept->accounting_account_id       = $request->accounting_account_id;
        $payrollConcept->budget_account_id           = $request->budget_account_id;
        $payrollConcept->assign_to                   = json_encode($request->assign_to);
        $payrollConcept->currency_id                 = $request->currency_id;
        $payrollConcept->save();


        /** Se eliminan las opciones de asignación asociadas al concepto */
        $assignOptions = PayrollConceptAssignOption::where('applicable_type', PayrollConcept::class)
                            ->where('applicable_id', $payrollConcept->id)->get();
        foreach ($assignOptions as $assignOption) {
            $assignOption->forceDelete();
        }

        /** Se agregan las nuevas opciones de asignación asociadas al concepto */

        foreach ($request->assign_to as $assign_to) {
            if ($assign_to['type'] == 'range') {
                PayrollConceptAssignOption::create([
                    'key'                => $assign_to['id'],
                    'value'              => json_encode($request->assign_options[$assign_to['id']]),
                    'applicable_type' => PayrollConcept::class,
                    'applicable_id' => $payrollConcept->id,
                ]);
            } elseif ($assign_to['type'] == 'list') {
                foreach ($request->assign_options[$assign_to['id']] as $assign_option) {
                    /**
                     * Objeto asociado al modelo PayrollConceptAssignOption
                     * @var Object $payrollConceptAssignOption
                     */
                    $payrollConceptAssignOption = PayrollConceptAssignOption::create([
                        'key'                => $assign_to['id'],
                        'applicable_type' => PayrollConcept::class,
                        'applicable_id' => $payrollConcept->id,
                    ]);
                    /** Se guarda la información en el campo morphs */
                    $option = $assign_to['model']::find($assign_option['id']);
                    $option->payrollConceptAssignOptions()->save($payrollConceptAssignOption);
                }
            }
        };
        return response()->json(['result' => true], 200);
    }

    /**
     * Elimina un concepto
     *
     * @method    destroy
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     *
     * @param     Integer                          $id    Identificador único del concepto a eliminar
     *
     * @return    \Illuminate\Http\JsonResponse           Objeto con los registros a mostrar
     */
    public function destroy($id)
    {
        /**
         * Objeto con la información del concepto a eliminar asociado al modelo PayrollConcept
         * @var Object $payrollConcept
         */
        $payrollConcept = PayrollConcept::find($id);
        $payrollConcept->delete();
        return response()->json(['record' => $payrollConcept, 'message' => 'Success'], 200);
    }

    /**
     * Obtiene los tipos de conceptos registrados
     *
     * @method    getPayrollConcepts
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     *
     * @return    {array}    Listado de los registros a mostrar
     */
    public function getPayrollConcepts()
    {
        return template_choices('Modules\Payroll\Models\PayrollConcept', ['name'], ['active' => 't'], true);
    }

    /**
     * Obtiene las opciones a asignar registrados asociadas a un concepto
     *
     * @method    getPayrollConceptAssignTo
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     *
     * @return    {array}    Listado de los registros a mostrar
     */
    public function getPayrollConceptAssignTo()
    {
        return $this->assignTo;
    }

    /**
     * Obtiene la lista de opciones de acuerdo al parametro seleccionado
     *
     * @method    getPayrollParametersTypes
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     *
     * @return    {array}    Listado de los registros a mostrar
     */
    public function getPayrollConceptAssignOptions($id)
    {
        foreach ($this->assignTo as $field) {
            if ($field['type'] == 'list') {
                if ($field['id'] == $id) {

                    return template_choices($field['model'], $field['field'] ?? 'name', '', true);
                }
            }
        }
    }
}
