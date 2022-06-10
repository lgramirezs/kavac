<?php

namespace Modules\Payroll\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Modules\Payroll\Models\PayrollSocioeconomic;
use Modules\Payroll\Models\PayrollChildren;
use Modules\Payroll\Models\MaritalStatus;
use Illuminate\Support\Facades\DB;

/**
 * @class PayrollSocioeconomicController
 * @brief Controlador de información socioeconómica del trabajador
 *
 * Clase que gestiona los datos de información socioeconómica del trabajador
 *
 * @author William Páez <wpaez@cenditel.gob.ve>
 * @license <a href='http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/'>
 *              LICENCIA DE SOFTWARE CENDITEL
 *          </a>
 */
class PayrollSocioeconomicController extends Controller
{
    use ValidatesRequests;

    /**
     * Define la configuración de la clase
     *
     * @author William Páez <wpaez@cenditel.gob.ve>
     */
    public function __construct()
    {
        /** Establece permisos de acceso para cada método del controlador */
        $this->middleware('permission:payroll.socioeconomics.list', ['only' => ['index', 'vueList']]);
        $this->middleware('permission:payroll.socioeconomics.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:payroll.socioeconomics.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:payroll.socioeconomics.delete', ['only' => 'destroy']);

        /** Define las reglas de validación para el formulario */
        $this->rules = [
            'marital_status_id' => ['required'],
        ];

        /** Define los atributos para los campos personalizados */
        $this->attributes = [
            'marital_status_id' => 'estado civil',
            'payroll_staff_id' => 'trabajador',
        ];
    }

    /**
     * Muestra todos los registros de información socioeconómica del trabajador
     *
     * @author William Páez <wpaez@cenditel.gob.ve>
     * @return Renderable    Muestra los datos organizados en una tabla
     */
    public function index()
    {
        return view('payroll::socioeconomics.index');
    }

    /**
     * Muestra el formulario de registro de información socioeconómica del trabajador
     *
     * @author William Páez <wpaez@cenditel.gob.ve>
     * @return Renderable    Vista con el formulario
     */
    public function create()
    {
        return view('payroll::socioeconomics.create-edit');
    }

    /**
     * Valida y registra nueva información socioeconómica del trabajador
     *
     * @author  William Páez <wpaez@cenditel.gob.ve>
     * @param  \Illuminate\Http\Request $request    Solicitud con los datos a guardar
     * @return \Illuminate\Http\JsonResponse        Json: result en verdadero y redirect con la url a donde ir
     */
    public function store(Request $request)
    {
        $this->rules['payroll_staff_id'] = ['required', 'unique:payroll_socioeconomics,payroll_staff_id'];
        $this->validate($request, $this->rules, [], $this->attributes);
        $maritalStatus = MaritalStatus::where('name', 'Casado(a)')->first();
        if ($request->marital_status_id == $maritalStatus->id) {
            $this->validate(
                $request,
                [
                    'full_name_twosome' => ['required', 'max:200'],
                    'birthdate_twosome' => ['nullable', 'date'],
                    'id_number_twosome' => [
                        'nullable', 'regex:/^([\d]{7}|[\d]{8})$/u', 'unique:payroll_socioeconomics,id_number_twosome'
                    ],
                ],
                [],
                [
                    'full_name_twosome' => 'nombres y apellidos de la pareja del trabajador',
                    'birthdate_twosome' => 'fecha de nacimiento de la pareja del trabajador',
                    'id_number_twosome' => 'cédula de identidad de la pareja del trabajdor',
                ],
            );
        }
        DB::transaction(function () use ($request) {
            $payrollSocioeconomic = PayrollSocioeconomic::create([
                'full_name_twosome' => $request->full_name_twosome,
                'id_number_twosome' => $request->id_number_twosome,
                'birthdate_twosome' => $request->birthdate_twosome,
                'payroll_staff_id' => $request->payroll_staff_id,
                'marital_status_id' => $request->marital_status_id
            ]);
            $i = 0;
            foreach ($request->payroll_childrens as $payrollChildren) {
                $this->validate(
                    $request,
                    [
                        'payroll_childrens.' . $i . '.first_name' => ['required'],
                        'payroll_childrens.' . $i . '.last_name' => ['required'],
                        'payroll_childrens.' . $i . '.id_number' => [
                            'nullable',
                            'regex:/^([\d]{7}|[\d]{8})$/u'
                        ],
                        'payroll_childrens.' . $i . '.birthdate' => ['required', 'date'],
                    ],
                    [],
                    [
                        'payroll_childrens.' . $i . '.first_name' => 'nombres del hijo del trabajador #' . ($i + 1),
                        'payroll_childrens.' . $i . '.last_name' => 'apellidos del hijo del trabajador #' . ($i + 1),
                        'payroll_childrens.' . $i . '.id_number' => 'cédula de identidad del hijo del trabajador #' . ($i + 1),
                        'payroll_childrens.' . $i . '.birthdate' => 'fecha de nacimiento del hijo del trabajador #' . ($i + 1),
                    ],
                );

                if (in_array("is_student", $payrollChildren, true) && $payrollChildren["is_student"] === true) {
                    $this->validate(
                        $request,
                        [
                            'payroll_childrens.' . $i . '.payroll_schooling_level_id' => ['required'],
                            'payroll_childrens.' . $i . '.study_center' => ['required'],
                        ],
                        [],
                        [
                            'payroll_childrens.' . $i . '.payroll_schooling_level_id' => 'nivel de escolaridad #' . ($i + 1),
                            'payroll_childrens.' . $i . '.study_center' => 'centro de estudio #' . ($i + 1),

                        ],
                    );
                }

                if (in_array("has_disability", $payrollChildren, true) && $payrollChildren["has_disability"] === true) {
                    $this->validate(
                        $request,
                        [
                            'payroll_childrens.' . $i . '.payroll_disability_id' => ['required'],
                        ],
                        [],
                        [
                            'payroll_childrens.' . $i . '.payroll_disability_id' => 'discapacidad #' . ($i + 1),

                        ],
                    );
                }
                $i++;
            }
            if ($request->payroll_childrens && !empty($request->payroll_childrens)) {
                foreach ($request->payroll_childrens as $payrollChildren) {
                    PayrollChildren::create([
                        'first_name' => $payrollChildren['first_name'],
                        'last_name' => $payrollChildren['last_name'],
                        'id_number' => $payrollChildren['id_number'],
                        'birthdate' => $payrollChildren['birthdate'],
                        'is_student' => $payrollChildren['is_student'],
                        'has_disability' => $payrollChildren['has_disability'],
                        'study_center' => $payrollChildren['study_center'],
                        'payroll_schooling_level_id' => $payrollChildren['payroll_schooling_level_id'],
                        'payroll_disability_id' => $payrollChildren['payroll_disability_id'],
                        'payroll_socioeconomic_id' => $payrollSocioeconomic->id,
                    ]);
                }
            }
        });
        $request->session()->flash('message', ['type' => 'store']);
        return response()->json([
            'result' => true, 'redirect' => route('payroll.socioeconomics.index')
        ], 200);
    }

    /**
     * Muestra los datos de información socioeconómica del trabajador en específico
     *
     * @author  William Páez <wpaez@cenditel.gob.ve>
     * @param  integer $id                          Identificador del dato a mostrar
     * @return \Illuminate\Http\JsonResponse        Json con el dato de información socioeconómica del trabajador
     */
    public function show($id)
    {
        $payrollSocioeconomic = PayrollSocioeconomic::where('id', $id)->with([
            'payrollStaff', 'maritalStatus', 'payrollChildrens' => function ($query) {
                $query->with(['payrollSchoolingLevel', 'payrollDisability']);
            },
        ])->first();

        return response()->json(['record' => $payrollSocioeconomic], 200);
    }

    /**
     * Muestra el formulario de actualización de información socioeconómica del trabajador
     *
     * @author William Páez <wpaez@cenditel.gob.ve>
     * @param  integer $id              Identificador con el dato a actualizar
     * @return Renderable    Vista con el formulario y el objeto con el dato a actualizar
     */
    public function edit($id)
    {
        $payrollSocioeconomic = PayrollSocioeconomic::find($id);
        return view('payroll::socioeconomics.create-edit', compact('payrollSocioeconomic'));
    }

    /**
     * Actualiza la información socioeconómica del trabajador
     *
     * @author  William Páez <wpaez@cenditel.gob.ve>
     * @param  \Illuminate\Http\Request  $request   Solicitud con los datos a actualizar
     * @param  integer $id                          Identificador del dato a actualizar
     * @return \Illuminate\Http\JsonResponse        Json con la redirección y mensaje de confirmación de la operación
     */
    public function update(Request $request, $id)
    {
        $payrollSocioeconomic = PayrollSocioeconomic::find($id);
        $this->rules['payroll_staff_id'] = [
            'required', 'unique:payroll_socioeconomics,payroll_staff_id,' . $payrollSocioeconomic->id
        ];
        $this->validate($request, $this->rules, [], $this->attributes);
        $maritalStatus = MaritalStatus::where('name', 'Casado(a)')->first();
        if ($request->marital_status_id == $maritalStatus->id) {
            $this->validate(
                $request,
                [
                    'full_name_twosome' => ['required', 'max:200'],
                    'birthdate_twosome' => ['nullable', 'date'],
                    'id_number_twosome' => [
                        'nullable', 'regex:/^([\d]{7}|[\d]{8})$/u',
                        'unique:payroll_socioeconomics,id_number_twosome,' . $payrollSocioeconomic->id
                    ],
                ],
                [],
                [
                    'full_name_twosome' => 'nombres y apellidos de la pareja del trabajador',
                    'birthdate_twosome' => 'fecha de nacimiento de la pareja del trabajador',
                    'id_number_twosome' => 'cédula de identidad de la pareja del trabajdor',
                ],
            );
        }

        $i = 0;
        foreach ($request->payroll_childrens as $payrollChildren) {
            $this->validate(
                $request,
                [
                    'payroll_childrens.' . $i . '.first_name' => ['required'],
                    'payroll_childrens.' . $i . '.last_name' => ['required'],
                    'payroll_childrens.' . $i . '.id_number' => [
                        'nullable',
                        'regex:/^([\d]{7}|[\d]{8})$/u'
                    ],
                    'payroll_childrens.' . $i . '.birthdate' => ['required', 'date'],
                ],
                [],
                [
                    'payroll_childrens.' . $i . '.first_name' => 'nombres del hijo del trabajador #' . ($i + 1),
                    'payroll_childrens.' . $i . '.last_name' => 'apellidos del hijo del trabajador #' . ($i + 1),
                    'payroll_childrens.' . $i . '.id_number' => 'cédula de identidad del hijo del trabajador #' . ($i + 1),
                    'payroll_childrens.' . $i . '.birthdate' => 'fecha de nacimiento del hijo del trabajador #' . ($i + 1),
                ],
            );

            if (in_array("is_student", $payrollChildren, true) && $payrollChildren["is_student"] === true) {
                $this->validate(
                    $request,
                    [
                        'payroll_childrens.' . $i . '.payroll_schooling_level_id' => ['required'],
                        'payroll_childrens.' . $i . '.study_center' => ['required'],
                    ],
                    [],
                    [
                        'payroll_childrens.' . $i . '.payroll_schooling_level_id' => 'nivel de escolaridad #' . ($i + 1),
                        'payroll_childrens.' . $i . '.study_center' => 'centro de estudio #' . ($i + 1),

                    ],
                );
            }

            if (in_array("has_disability", $payrollChildren, true) && $payrollChildren["has_disability"] === true) {
                $this->validate(
                    $request,
                    [
                        'payroll_childrens.' . $i . '.payroll_disability_id' => ['required'],
                    ],
                    [],
                    [
                        'payroll_childrens.' . $i . '.payroll_disability_id' => 'discapacidad #' . ($i + 1),

                    ],
                );
            }
            $i++;
        }
        // DB::transaction(function () use ($payrollSocioeconomic, $request) {
        $payrollSocioeconomic->full_name_twosome  = $request->full_name_twosome;
        $payrollSocioeconomic->id_number_twosome  = $request->id_number_twosome;
        $payrollSocioeconomic->birthdate_twosome  = $request->birthdate_twosome;
        $payrollSocioeconomic->payroll_staff_id  = $request->payroll_staff_id;
        $payrollSocioeconomic->marital_status_id  = $request->marital_status_id;
        $payrollSocioeconomic->save();

        if ($request->payroll_childrens && !empty($request->payroll_childrens)) {
            foreach ($request->payroll_childrens as $payrollChildren) {
                $indentifier = in_array("id", $payrollChildren, true) ? ['id' => $payrollChildren['id']]
                    :
                    [
                        'first_name' => $payrollChildren['first_name'],
                        'last_name' => $payrollChildren['last_name'],
                        'birthdate' => $payrollChildren['birthdate'],
                    ];
                $payrollSocioeconomic->payrollChildrens()->updateOrCreate(
                    $indentifier,
                    [
                        'first_name' => $payrollChildren['first_name'],
                        'last_name' => $payrollChildren['last_name'],
                        'id_number' => $payrollChildren['id_number'],
                        'birthdate' => $payrollChildren['birthdate'],
                        'is_student' => $payrollChildren['is_student'],
                        'has_disability' => $payrollChildren['has_disability'],
                        'study_center' => $payrollChildren['study_center'],
                        'payroll_schooling_level_id' => $payrollChildren['payroll_schooling_level_id'],
                        'payroll_disability_id' => $payrollChildren['payroll_disability_id'],
                        'payroll_socioeconomic_id' => $payrollSocioeconomic->id,
                    ]
                );
            }
        } else {
            foreach ($payrollSocioeconomic->payrollChildrens as $payrollChildren) {
                $payrollChildren->delete();
            }
        }
        // });
        $request->session()->flash('message', ['type' => 'update']);
        return response()->json([
            'result' => true, 'redirect' => route('payroll.socioeconomics.index')
        ], 200);
    }

    /**
     * Elimina la información socioeconómica del trabajador
     *
     * @author  William Páez <wpaez@cenditel.gob.ve>
     * @param  integer $id                      Identificador del dato a eliminar
     * @return \Illuminate\Http\JsonResponse    Json con mensaje de confirmación de la operación
     */
    public function destroy($id)
    {
        $payrollSocioeconomic = PayrollSocioeconomic::find($id);
        $payrollSocioeconomic->delete();

        $payrollChildrens = PayrollChildren::where('payroll_socioeconomic_id', $payrollSocioeconomic->id)->get();
        foreach ($payrollChildrens as $payrollChildren) {
            $payrollChildren->delete();
        }

        return response()->json(['record' => $payrollSocioeconomic, 'message' => 'Success'], 200);
    }

    /**
     * Muestra la información socioeconómica del trabajador registrada
     *
     * @author  William Páez <wpaez@cenditel.gob.ve>
     * @return \Illuminate\Http\JsonResponse    Json con los datos de la información socioeconómica del trabajador
     */
    public function vueList()
    {
        return response()->json(['records' => PayrollSocioeconomic::with([
            'payrollStaff', 'maritalStatus', 'payrollChildrens.payrollSchoolingLevel',
            'payrollChildrens.payrollDisability'
        ])->get()], 200);
    }
}
