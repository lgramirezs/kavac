<?php

namespace Modules\Payroll\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Modules\Payroll\Models\PayrollConcept;
use App\Models\Parameter;

/**
 * FALTA:
 *
 * 1-. Mover las variables parameterTypes y associatedRecords a base de datos (Crear Seeders)
 * 2-. Ajustar el comportamiento de los siguientes métodos para busquedas desde el modelo parameter
 */

/**
 * @class      PayrollParameterController
 * @brief      Controlador de parámetros de nómina
 *
 * Clase que gestiona los parámetros de nómina
 *
 * @author     Henry Paredes <hparedes@cenditel.gob.ve>
 * @license
 *     [LICENCIA DE SOFTWARE CENDITEL](http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/)
 */
class PayrollParameterController extends Controller
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
     * Arreglo con los tipos de parámetros de nómina
     * @var Array $parameterTypes
     */
    protected $parameterTypes;

    /**
     * Arreglo con los registros asociados al expediente del trabajador
     * @var Array $associatedRecords
     */
    protected $associatedRecords;

    /**
     * Arreglo con los registros asociados a la configuración de vacaciones
     * @var Array $associatedVacation
     */
    protected $associatedVacation;

    /**
     * Define la configuración de la clase
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     */
    public function __construct()
    {
        /** Establece permisos de acceso para cada método del controlador */
        //$this->middleware('permission:payroll.parameters.list',   ['only' => 'index']);
        //$this->middleware('permission:payroll.parameters.create', ['only' => 'store']);
        //$this->middleware('permission:payroll.parameters.edit',   ['only' => 'update']);
        //$this->middleware('permission:payroll.parameters.delete', ['only' => 'destroy']);

        /** Define las reglas de validación para el formulario */
        $this->validateRules = [
            'parameter_type' => ['required'],
            'name'           => ['required'],
        ];

        /** Define los mensajes de validación para las reglas del formulario */
        $this->messages = [
            'parameter_type.required' => 'El campo tipo de parámetro es obligatorio.',
            'value.required'          => 'El campo valor es obligatorio.',
            'formula.required'        => 'El campo fórmula es obligatorio.'
        ];

        /** Define los tipos de parámetros de nómina a emplear en el formulario */
        $this->parameterTypes = [
            ['id' => 'global_value',        'name' => 'Valor global'],
            ['id' => 'resettable_variable', 'name' => 'Variable reiniciable a cero por período de nómina'],
            ['id' => 'processed_variable',  'name' => 'Variable procesada']
        ];

        /** Define los campos del expediente del trabajador a emplear en el formulario */
        $this->associatedRecords = [
            [
                'id'       => 'STAFF',
                'name'     => 'Datos Personales',
                'model'    => 'Modules\Payroll\Models\PayrollStaff',
                'required' => [],
                'children' =>  [
                    [
                        'id'        => 'NATIONALITY',
                        'name'      => 'Nacionalidad',
                        'type'      => 'list',
                        'model'     => 'Modules\Payroll\Models\PayrollNationality',
                        'required'  => ['payroll_nationality_id']
                    ],
                    [
                        'id'        => 'GENDER',
                        'name'      => 'Género',
                        'type'      => 'list',
                        'model'     => 'Modules\Payroll\Models\PayrollGender',
                        'required'  => ['payroll_gender_id']

                    ],
                    [
                        'id'        => 'DISABLE',
                        'name'      => 'Estatus Discapacitado',
                        'type'      => 'boolean',
                        'model'     => '',
                        'required'  => ['has_disability']
                    ],
                    [
                        'id'        => 'BLOOD_TYPE',
                        'name'      => 'Tipo de sangre',
                        'type'      => 'list',
                        'model'     => 'Modules\Payroll\Models\PayrollBloodType',
                        'required'  => ['payroll_blood_type_id']
                    ],
                    [
                        'id'        => 'LICENSE_DEGREE',
                        'name'      => 'Grado de licencia de conducir',
                        'type'      => 'list',
                        'model'     => 'Modules\Payroll\Models\PayrollLicenseDegree',
                        'required'  => ['payroll_license_degree_id']
                    ]
                ]
            ],
            [
                'id'       => 'PROFESIONAL',
                'name'     => 'Datos Profesionales',
                'model'    => 'Modules\Payroll\Models\PayrollProfesional',
                'required' => ['payrollProfesional'],
                'children' =>
                [
                    [
                        'id'        => 'INSTRUCTION_DEGREE',
                        'name'      => 'Grado de instrucción',
                        'type'      => 'list',
                        'model'     => 'Modules\Payroll\Models\PayrollInstructionDegree',
                        'required'  => ['payroll_instruction_degree_id']
                    ],
                    [
                        'id'        => 'PROFESSION',
                        'name'      => 'Profesión',
                        'type'      => 'list',
                        'model'     => 'Modules\Payroll\Models\Profession',
                        'required'  => ['Profession_id']
                    ],
                    [
                        'id'        => 'STUDENT',
                        'name'      => 'Estatus Estudiante',
                        'type'      => 'boolean',
                        'model'     => '',
                        'required'  => ['is_student']
                    ],
                    [
                        'id'        => 'NUMBER_LANG',
                        'name'      => 'Número de idiomas',
                        'type'      => 'number',
                        'model'     => '',
                        'required'  => ['payrollLanguages']
                    ]
                ]
            ],
            [
                'id'       => 'SOCIOECONOMIC_INFORMATION',
                'name'     => 'Datos Socioeconómicos',
                'model'    => 'Modules\Payroll\Models\PayrollSocioeconomic',
                'required' => ['payrollSocioecomicInformation'],
                'children' =>
                [
                    [
                        'id'       => 'MARITAL_STATUS',
                        'name'     => 'Estado Civil',
                        'type'     => 'list',
                        'model'    => 'Modules\Payroll\Models\MaritalStatus',
                        'required' => ['marital_status_id']
                    ],
                    [
                        'id'       => 'NUMBER_CHILDREN',
                        'name'     => 'Número de hijos',
                        'type'     => 'number',
                        'model'    => '',
                        'required' => ['payrollChildrens']
                    ]
                ]
            ],
            [
                'id'       => 'EMPLOYMENT_INFORMATION',
                'name'     => 'Datos Laborales',
                'required' => ['payrollEmployment'],
                'children' =>
                [
                    [
                        'id'       => 'START_APN',
                        'name'     => 'Años en la administración pública',
                        'type'     => 'number',
                        'model'    => '',
                        'required' => ['start_date_apn']
                    ],
                    [
                        'id'       => 'START_DATE',
                        'name'     => 'Años en la institución',
                        'type'     => 'number',
                        'model'    => '',
                        'required' => ['start_date']
                    ],
                    [
                        'id'       => 'POSITION_TYPE',
                        'name'     => 'Tipo de cargo',
                        'type'     => 'list',
                        'model'    => 'Modules\Payroll\Models\PayrollPositionType',
                        'required' => ['payroll_position_type_id']
                    ],
                    [
                        'id'       => 'POSITION',
                        'name'     => 'Cargo',
                        'type'     => 'list',
                        'model'    => 'Modules\Payroll\Models\PayrollPosition',
                        'required' => ['payroll_position_id']
                    ],
                    [
                        'id'       => 'DEPARTMENT',
                        'name'     => 'Departamento',
                        'type'     => 'list',
                        'model'    => 'Modules\Payroll\Models\Department',
                        'required' => ['department_id']
                    ],
                    [
                        'id'       => 'STAFF_TYPE',
                        'name'     => 'Tipo de personal',
                        'type'     => 'list',
                        'model'    => 'Modules\Payroll\Models\PayrollStaffType',
                        'required' => ['payroll_staff_type_id']
                    ],
                    [
                        'id'       => 'CONTRACT_TYPE',
                        'name'     => 'Tipo de contrato',
                        'type'     => 'list',
                        'model'    => 'Modules\Payroll\Models\PayrollContractType',
                        'required' => ['payroll_contract_type_id']
                    ]
                ]
            ]
        ];

        /** Define los campos de la configuración de vacaciones a emplear en el formulario */
        $this->associatedVacation = [
            [
                'id'       => 'VACATION_DAYS',
                'name'     => 'Días a otorgar para el disfrute de vacaciones',
                'model'    => 'Modules\Payroll\Models\PayrollVacationPolicy',
                'required' => ['vacation_days'],
            ],
            [
                'id'       => 'ADDITIONAL_DAYS_PER_YEAR',
                'name'     => 'Días de disfrute adicionales por año de servicio',
                'model'    => 'Modules\Payroll\Models\PayrollVacationPolicy',
                'required' => ['additional_days_per_year'],
            ],
            [
                'id'       => 'DAYS_REQUESTED',
                'name'     => 'Días a otogar para el pago de vacaciones',
                'model'    => 'Modules\Payroll\Models\PayrollVacationRequests',
                'required' => ['days_requested'],
            ]
        ];
    }

    /**
     * Muestra un listado de los parámetros globales de nómina registrados
     *
     * @method    index
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     *
     * @return    \Illuminate\Http\JsonResponse    Objeto con los registros a mostrar
     */
    public function index()
    {
        $listGlobalParameters = [];
        /**
         * Objeto asociado al modelo Parameter
         * @var Object $parameters
         */
        $parameters = Parameter::where(
            [
                'required_by' => 'payroll',
                'active'      => true,
            ]
        )->where('p_key', 'like', 'global_parameter_%')->get();

        if (!is_null($parameters)) {
            foreach ($parameters as $parameter) {
                $param = json_decode($parameter->p_value);
                array_push($listGlobalParameters, [
                    'id'             => $param->id,
                    'name'           => $param->name,
                    'description'    => $param->description ?? '',
                    'parameter_type' => $param->parameter_type,
                    'percentage'     => $param->percentage ?? '',
                    'value'          => $param->value ?? '',
                    'formula'        => $param->formula ?? ''
                ]);
            }
        }

        return response()->json(['records' => $listGlobalParameters], 200);
    }

    /**
     * Valida y registra un nuevo parámetro global de nómina
     *
     * @method    store
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     *
     * @param     \Illuminate\Http\Request     $request    Datos de la petición
     *
     * @return    \Illuminate\Http\JsonResponse                Objeto con los registros a mostrar
     */
    public function store(Request $request)
    {
        $validateRules = $this->validateRules;
        if ($request->parameter_type == 'global_value') {
            $validateRules = array_merge($validateRules, ['value' => ['required']]);
        } elseif ($request->parameter_type == 'processed_variable') {
            $validateRules = array_merge($validateRules, ['formula' => ['required']]);
        }
        $this->validate($request, $validateRules, $this->messages);
        $index = 0;
        $errors = [];
        $listGlobalParameters = [];

        /**
         * Objeto asociado al modelo Parameter
         * @var Object $parameters
         */
        $parameters = Parameter::where(
            [
                'required_by' => 'payroll',
                'active'      => true,
            ]
        )->where('p_key', 'like', 'global_parameter_%')->withTrashed()->orderBy('created_at')->get();

        if (!is_null($parameters)) {
            foreach ($parameters as $parameter) {
                $param = json_decode($parameter->p_value);
                /** Cambiar por regla de validación, ej: Rule::notIn */
                if ($request->name == $param->name) {
                    $errors = array_merge($errors, ["name" => ["El campo nombre contiene un valor duplicado."]]);
                }
                if (!empty($errors)) {
                    return response()->json(['errors' => $errors], 422);
                }
                $index = $param->id;
                array_push($listGlobalParameters, [
                    'id'             => $param->id,
                    'name'           => $param->name,
                    'description'    => $param->description,
                    'parameter_type' => $param->parameter_type,
                    'percentage'     => $param->percentage,
                    'value'          => $param->value,
                    'formula'        => $param->formula
                ]);
            }
        }
        $payrollParameter = [
            'id'             => $index + 1,
            'name'           => $request->name,
            'description'    => $request->description ?? '',
            'parameter_type' => $request->parameter_type,
            'percentage'     => !empty($request->input('percentage'))
                                    ? $request->input('percentage')
                                    : false,
            'value'          => $request->value ?? '',
            'formula'        => $request->formula ?? ''
        ];
        array_push($listGlobalParameters, $payrollParameter);

        /**
         * Objeto asociado al modelo Parameter
         * @var Object $parameter
         */
        $parameter = Parameter::create([
            'p_key'       => 'global_parameter_' . $payrollParameter['id'],
            'p_value'     => json_encode($payrollParameter),
            'required_by' => 'payroll',
            'active'      => true
        ]);
        return response()->json(['record' => $parameter, 'message' => 'Success'], 200);
    }

    /**
     * Actualiza la información de un parámetro global de nómina
     *
     * @method    update
     *
     * @param     \Illuminate\Http\Request         $request    Datos de la petición
     *
     * @param     Integer                          $id         Identificador único del parámetro a editar
     *
     * @return    \Illuminate\Http\JsonResponse                Objeto con los registros a mostrar
     */
    public function update(Request $request, $id)
    {
        $validateRules = $this->validateRules;
        if ($request->parameter_type == 'global_value') {
            $validateRules = array_merge($validateRules, ['value' => ['required']]);
        } elseif ($request->parameter_type == 'processed_variable') {
            $validateRules = array_merge($validateRules, ['formula' => ['required']]);
        }
        $this->validate($request, $validateRules, $this->messages);

        $errors = [];
        $listGlobalParameters = [];

        /**
         * Objeto asociado al modelo Parameter
         * @var Object $parameter
         */
        $parameters = Parameter::where(
            [
                'required_by' => 'payroll',
                'active'      => true,
            ]
        )->where('p_key', 'like', 'global_parameter_%')->withTrashed()->get();

        if (!is_null($parameters)) {
            foreach ($parameters as $parameter) {
                $param = json_decode($parameter->p_value);
                /** Cambiar por regla de validación, ej: Rule::notIn */
                if ($request->id != $param->id) {
                    if ($request->name == $param->name) {
                        $errors = array_merge($errors, ["name" => ["El campo nombre contiene un valor duplicado."]]);
                    }
                    array_push($listGlobalParameters, [
                        'id'             => $param->id,
                        'name'           => $param->name,
                        'description'    => $param->description ?? '',
                        'parameter_type' => $param->parameter_type,
                        'percentage'     => $param->percentage ?? '',
                        'value'          => $param->value ?? '',
                        'formula'        => $param->formula ?? ''
                    ]);
                } else {
                    $payrollParameter = [
                        'id'             => $request->id,
                        'name'           => $request->name,
                        'description'    => $request->description ?? '',
                        'parameter_type' => $request->parameter_type,
                        'percentage'     => !empty($request->input('percentage'))
                                                ? $request->input('percentage')
                                                : $param->percentage,
                        'value'          => $request->value ?? '',
                        'formula'        => $request->formula ?? ''
                    ];
                    array_push($listGlobalParameters, $payrollParameter);
                }
            }
        }

        /**
         * Objeto asociado al modelo Parameter
         * @var Object $parameter
         */
        $parameter = Parameter::updateOrCreate(
            [
                'p_key'       => 'global_parameter_' . $payrollParameter['id'],
                'required_by' => 'payroll',
                'active'      => true
            ],
            [
                'p_value'     => json_encode($payrollParameter)
            ]
        );
        return response()->json(['record' => $parameter, 'message' => 'Success'], 200);
    }

    /**
     * Elimina un parámetro global de nómina
     *
     * @method    destroy
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     *
     * @param     Integer                          $id    Identificador único del parámetro a eliminar
     *
     * @return    \Illuminate\Http\JsonResponse           Objeto con los registros a mostrar
     */
    public function destroy($id)
    {
        /**
         * Objeto asociado al modelo Parameter
         * @var Object $parameter
         */
        $parameter = Parameter::where(
            [
                'p_key'       => 'global_parameter_' . $id,
                'required_by' => 'payroll',
                'active'      => true,
            ]
        )->first();

        if (!is_null($parameter)) {
            $parameter->forceDelete();
            return response()->json(['message' => 'destroy'], 200);
        } else {
            $parameter = Parameter::where([
                'p_key'       => 'global_parameter_group_by_tabs_' . $id,
                'required_by' => 'payroll',
                'active'      => true,
            ])->first();

            if (!is_null($parameter)) {
                $parameter->forceDelete();
                return response()->json(['message' => 'destroy'], 200);
            }
        }
    }

    /**
     * Obtiene los grupos de tabuladores salariales registrados
     *
     * @method    getSalaryTabulatorsGroups
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     *
     * @return    Array    Listado de los registros a mostrar
     */
    public function getSalaryTabulatorsGroups()
    {
        $list = [['id' => '', 'text' => 'Seleccione...']];
        $childrens = [];

        foreach ($this->associatedRecords as $record) {
            if (empty($record['children'])) {
                array_push($list, [
                    'id'   => $record['id'],
                    'text' => $record['name']
                ]);
            } else {
                $childrens = [];
                foreach ($record['children'] as $children) {
                    array_push($childrens, [
                        'id'   => $children['id'],
                        'text' => $children['name'],
                        'type' => $children['type']
                    ]);
                }
                array_push($list, [
                    'id'       => $record['id'],
                    'text'     => $record['name'],
                    'children' => $childrens
                ]);
            }
        }

        return $list;
    }

    /**
     * Obtiene los parámetros globales de nómina registrados
     *
     * @method    getPayrollParameters
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     *
     * @param     \Illuminate\Http\Request     $request    Datos de la petición
     *
     * @return    Array                                    Listado de los registros a mostrar
     */
    public function getPayrollParameters(Request $request)
    {
        if (is_null($request->payroll_concepts)) {
            $listGlobalParameters = [['id' => '', 'text' => 'Seleccione...']];
            /**
             * Objeto asociado al modelo Parameter
             * @var Object $parameter
             */
            $parameters = Parameter::where(
                [
                    'required_by' => 'payroll',
                    'active'      => true,
                ]
            )->where('p_key', 'like', 'global_parameter_%')->get();

            if (!is_null($parameters)) {
                foreach ($parameters as $parameter) {
                    $param = json_decode($parameter->p_value);
                    array_push($listGlobalParameters, [
                        'id'             => $param->id,
                        'text'           => $param->name,
                    ]);
                }
            }
            return $listGlobalParameters;
        } else {
            $payrollParameters = [];
            foreach ($request->payroll_concepts as $payroll_concept) {
                $payrollConcept = PayrollConcept::find($payroll_concept['id']);
                if ($payrollConcept && $payrollConcept->calculation_way == 'formula') {
                    $exploded = multiexplode(
                        [
                            'if', '(', ')', '{', '}', ' ',
                            '==', '<=', '>=', '<', '>', '!=',
                            '+', '-','*','/'
                        ],
                        $payrollConcept->formula
                    );
                    foreach ($exploded as $explod) {
                        /**
                         * Objeto asociado al modelo Parameter
                         * @var Object $parameters
                         */
                        $parameters = Parameter::where(
                            [
                                'required_by' => 'payroll',
                                'active'      => true,
                            ]
                        )->where('p_value', 'like', '%' . $explod . '%')->get();
                        if ($parameters) {
                            foreach ($parameters as $parameter) {
                                $jsonValue = json_decode($parameter->p_value);
                                if (isset($jsonValue->id)) {
                                    if ($jsonValue->id == $explod) {
                                        if ($jsonValue->parameter_type == 'global_value') {
                                            /** Si el parámetro es de valor global */
                                            array_push($payrollParameters, [
                                                'id'  => $jsonValue->id,
                                                'value' => $jsonValue->value
                                            ]);
                                        } elseif ($jsonValue->parameter_type == 'resettable_variable') {
                                            /** Si el parámetro es reiniciable a cero por período de nómina */
                                            array_push($payrollParameters, [
                                                'id'  => $jsonValue->id,
                                                'value' => ''
                                            ]);
                                        } elseif ($jsonValue->parameter_type == 'processed_variable') {
                                            /** Si el parámetro es una variable procesada */
                                            array_push($payrollParameters, [
                                                'id'  => $jsonValue->id,
                                                'value' => $jsonValue->formula
                                            ]);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            return $payrollParameters;
        }
    }

    /**
     * Obtiene los tipos de parámetros globales de nómina
     *
     * @method    getPayrollParametersTypes
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     *
     * @return    Array    Listado de los registros a mostrar
     */
    public function getPayrollParameterTypes()
    {
        $list = [['id' => '', 'text' => 'Seleccione...']];

        foreach ($this->parameterTypes as $parameterType) {
            array_push($list, [
                'id'   => $parameterType['id'],
                'text' => $parameterType['name']
            ]);
        }

        return $list;
    }

    /**
     * Obtiene la lista de opciones de acuerdo al parametro seleccionado
     *
     * @method    getPayrollParametersTypes
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     *
     * @param     Integer    $code    Código del parámetro seleccionado
     *
     * @return    Array               Listado de los registros a mostrar
     */
    public function getPayrollParameterOptions($code)
    {
        foreach ($this->associatedRecords as $record) {
            if (!empty($record['children'])) {
                foreach ($record['children'] as $children) {
                    if ($children['id'] == $code) {
                        if ($children['type'] == 'list') {
                            return template_choices($children['model'], ['name'], '', true);
                        }
                    }
                }
            }
        }
    }

    /**
     * Obtiene los registros asociados a los campos del expediente del trabajador
     *
     * @method    getAssociatedRecords
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     *
     * @return    Array    Listado de los registros a mostrar
     */
    public function getAssociatedRecords()
    {
        $list = [['id' => '', 'text' => 'Seleccione...']];
        $childrens = [];

        foreach ($this->associatedRecords as $record) {
            if (empty($record['children'])) {
                array_push($list, [
                    'id'   => $record['id'],
                    'text' => $record['name']
                ]);
            } else {
                $childrens = [];
                foreach ($record['children'] as $children) {
                    array_push($childrens, [
                        'id'   => $children['id'],
                        'text' => $children['name'],
                        'type' => $children['type']
                    ]);
                }
                array_push($list, [
                    'id'       => $record['id'],
                    'text'     => $record['name'],
                    'children' => $childrens
                ]);
            }
        }
        return $list;
    }

    /**
     * Obtiene los registros asociados a los campos de la configuración de vacaciones
     *
     * @method    getVacationAssociatedRecords
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     *
     * @return    Array    Listado de los registros a mostrar
     */
    public function getVacationAssociatedRecords()
    {
        $list = [['id' => '', 'text' => 'Seleccione...']];

        foreach ($this->associatedVacation as $record) {
            array_push($list, [
                'id'   => $record['id'],
                'text' => $record['name'],
                'type' => 'number'
            ]);
        }
        return $list;
    }
}
