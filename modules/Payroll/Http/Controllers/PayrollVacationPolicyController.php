<?php

namespace Modules\Payroll\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Modules\Payroll\Models\PayrollConceptAssignOption;
use Modules\Payroll\Models\PayrollVacationPolicy;
use Modules\Payroll\Models\PayrollScale;
use Modules\Payroll\Models\Institution;

/**
 * @class      PayrollVacationPolicyController
 * @brief      Controlador de políticas vacacionales
 *
 * Clase que gestiona las políticas vacacionales
 *
 * @author     Henry Paredes <hparedes@cenditel.gob.ve>
 * @license
 *     [LICENCIA DE SOFTWARE CENDITEL](http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/)
 */
class PayrollVacationPolicyController extends Controller
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
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     */
    public function __construct()
    {
        /** Establece permisos de acceso para cada método del controlador */
        //$this->middleware('permission:payroll.vacation-policies.list',   ['only' => 'index']);
        //$this->middleware('permission:payroll.vacation-policies.create', ['only' => 'store']);
        //$this->middleware('permission:payroll.vacation-policies.edit',   ['only' => 'update']);
        //$this->middleware('permission:payroll.vacation-policies.delete', ['only' => 'destroy']);

        /** Define las reglas de validación para el formulario */
        $this->validateRules = [
            'name'                                  => ['required'],
            'start_date'                            => ['required'],
            'vacation_type'                         => ['required'],
            'staff_antiquity'                       => ['required'],
            'institution_id'                        => ['required'],
            'assign_to'                             => ['required'],
        ];

        /** Define los mensajes de validación para las reglas del formulario */
        $this->messages = [
            'name.required'                                  => 'El campo nombre es obligatorio.',
            'start_date.required'                            => 'El campo desde (fecha de aplicación) es obligatorio.',
            'institution_id.required'                        => 'El campo organización es obligatorio.',
            'vacation_periods.required'                      => 'Los campos de salidas individuales son obligatorio.',
            'vacation_type.required'                         => 'El campo tipo de vacaciones es obligatorio.',
            'vacation_periods_accumulated_per_year.required' => 'El campo períodos vacacionales acumulados por año ' .
                'es obligatorio.',
            'vacation_days.required'                         => 'El campo días a otorgar para el disfrute de ' .
                'vacaciones es obligatorio.',
            'vacation_period_per_year.required'              => 'El campo períodos vacacionales permitidos por año ' .
                'es obligatorio.',
            'additional_days_per_year.required'              => 'El campo días adicionales a otorgar para el ' .
                'disfrute de vacaciones es obligatorio.',
            'minimum_additional_days_per_year.required'      => 'El campo días de disfrute de vacaciones mínimo ' .
                'por año de servicio es obligatorio.',
            'maximum_additional_days_per_year.required'      => 'El campo días de disfrute de vacaciones máximo ' .
                'por año de servicio es obligatorio.',
            // 'salary_type.required'                           => 'El campo salario a emplear para el cálculo del bono ' .
            //     'vacacional es obligatorio.',
            'payroll_payment_type_id.required'               => 'El campo tipo de pago de nómina es obligatorio.',
            'assign_to.required'                             => 'El campo asignar a es obligatorio.',
            'min_days_advance.required'                      => 'El campo días de anticipación (mínimo) es obligatorio.',
            'from_year.required'                             => 'El campo a partir de es obligatorio.',
            'from_year.between'                              => 'El campo a partir de debe estar entre 1960 y ' . date('Y'),
        ];
    }

    /**
     * Muestra un listado de las políticas vacacionales registradas
     *
     * @method    index
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     *
     * @return    \Illuminate\Http\JsonResponse    Objeto con los registros a mostrar
     */
    public function index()
    {
        //dd('initRecords index');
        $profileUser = auth()->user()->profile;
        
        if ($profileUser && $profileUser->institution_id !== null) {
            $institution = Institution::find($profileUser->institution_id);
        } else {
            $institution = Institution::where('active', true)->where('default', true)->first();
        }

        /**
         * Objeto asociado al modelo PayrollConcept
         * @var Object $PayrollVacationPolicy
         */
        $PayrollVacationPolicies = PayrollVacationPolicy::with('payrollConceptAssignOptions')
            ->with('payrollScales', function($query){
                $query->where('relationship_type', 'payrollScales');
            })
            ->with('payrollDaysScales', function($query){
                $query->where('relationship_type', 'payrollDaysScales');
            })->where('institution_id', $institution->id)->get();
        foreach ($PayrollVacationPolicies as $payrollVacationPolicy) {
            $assign_to = json_decode($payrollVacationPolicy->assign_to);
            $assign_options = [];
            if ($assign_to) {
                foreach ($assign_to as $field) {
                    if ($field->type) {
                        $key = $field->id;
                        $options = [];
                        if ($payrollVacationPolicy->payrollConceptAssignOptions) {
                            foreach ($payrollVacationPolicy->payrollConceptAssignOptions as $assign_option) {
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
            $payrollVacationPolicy->assign_to = $assign_to;
            $payrollVacationPolicy->assign_options = json_decode(json_encode($assign_options));
        }

        return response()->json(
            ['records' => $PayrollVacationPolicies],
            200
        );
    }

    /**
     * Muestra los datos de la información de la política vacacional seleccionada
     *
     * @method    show
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     *
     * @param     Integer    $id                Identificador único de la política vacacional
     *
     * @return \Illuminate\Http\JsonResponse    Objeto con los registros a mostrar
     */
    public function show($id)
    {
        $payrollVacationPolicy = PayrollVacationPolicy::with('payrollConceptAssignOptions', 'payrollScales', 'payrollDaysScales')->find($id);
        return response()->json(['record' => $payrollVacationPolicy], 200);
    }

    /**
     * Valida y registra una nueva política vacacional
     *
     * @method    store
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     *
     * @param     \Illuminate\Http\Request         $request    Datos de la petición
     *
     * @return    \Illuminate\Http\JsonResponse                Objeto con los registros a mostrar
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validateRules  = $this->validateRules;
        if ($request->input('vacation_type') == 'collective_vacations') {
            $validateRules  = array_merge(
                $validateRules,
                [
                    'vacation_periods'                      => ['required'],
                    // 'salary_type'                           => ['required'],
                    'payroll_payment_type_id'               => ['required']
                ]
            );
        } elseif ($request->input('vacation_type') == 'vacation_period') {
            $validateRules  = array_merge(
                $validateRules,
                [
                    'vacation_periods_accumulated_per_year' => ['required'],
                    'vacation_period_per_year'              => ['required'],
                    'vacation_days'                         => ['required'],
                    'additional_days_per_year'              => ['required'],
                    //'minimum_additional_days_per_year'      => ['required'],
                    'maximum_additional_days_per_year'      => ['required'],
                    'payroll_payment_type_id'               => ['required'],
                    'min_days_advance'                      => ['required'],
                    'from_year'                             => ['required', 'integer','between:1960,' . date('Y')]
                ]
            );
        }
        // dd($request->all());
        $this->validate($request, $validateRules, $this->messages);

        $profileUser = Auth()->user()->profile;
        if ($profileUser) {
            $institution = Institution::find($profileUser->institution_id);
        } else {
            $institution = Institution::where('active', true)->where('default', true)->first();
        }

        /**
         * Objeto asociado al modelo PayrollVacationPolicy
         *
         * @var Object $payrollVacationPolicy
         */
        $payrollVacationPolicy = PayrollVacationPolicy::create([
            'name'                                  => $request->input('name'),
            'active'                                => !empty($request->active)
                ? $request->active
                : false,
            'start_date'                            => $request->input('start_date'),
            'end_date'                              => $request->input('end_date'),
            'vacation_periods'                      => json_encode($request->input('vacation_periods')),
            'vacation_type'                         => $request->input('vacation_type'),
            'vacation_periods_accumulated_per_year' => $request->input('vacation_periods_accumulated_per_year'),
            'vacation_days'                         => $request->input('vacation_days'),
            'vacation_period_per_year'              => $request->input('vacation_period_per_year'),
            'additional_days_per_year'              => $request->input('additional_days_per_year'),
            //'minimum_additional_days_per_year'      => $request->input('minimum_additional_days_per_year'),
            'from_year'                             => $request->input('from_year'),
            'maximum_additional_days_per_year'      => $request->input('maximum_additional_days_per_year'),
            // 'salary_type'                           => $request->input('salary_type'),
            'vacation_advance'                      => $request->input('vacation_advance'),
            'vacation_postpone'                     => $request->input('vacation_postpone'),
            'staff_antiquity'                       => $request->input('staff_antiquity'),
            'institution_id'                        => $request->input('institution_id'),
            'payroll_payment_type_id'               => $request->input('payroll_payment_type_id'),
            'assign_to'                             => json_encode($request->assign_to),

            'on_scale'                              => $request->input('on_scale'),
            'worker_arises'                         => $request->input('worker_arises'),
            'generate_worker_arises'                => $request->input('generate_worker_arises'),
            'min_days_advance'                      => $request->input('min_days_advance'),

            'business_days'                         => $request->input('business_days'),
            'old_jobs'                              => $request->input('old_jobs'),

            // Agrupar por
            'group_by'                              => $request->input('group_by'),
            'type'                                  => $request->input('type'),

            'days_on_scale'                         => $request->input('days_on_scale'),
            'days_group_by'                         => $request->input('days_group_by'),
            'days_type'                             => $request->input('days_type'),
        ]);

        /**
         * Relaciona con payrollScales
         */
        if ($request->payroll_scales) {
            foreach ($request->payroll_scales as $payrollScale) {
                $payrollVacationPolicy->payrollScales()->create([
                    'name'                    => $payrollScale['name'],
                    'value'                   => json_encode($payrollScale['value']),
                    'relationship_type'       => 'payrollScales',
                ]);
            }
        }

        if ($request->payroll_days_scales) {
            foreach ($request->payroll_days_scales as $payrollScale) {
                $payrollVacationPolicy->payrollDaysScales()->create([
                    'name'                    => $payrollScale['name'],
                    'value'                   => json_encode($payrollScale['value']),
                    'relationship_type'       => 'payrollDaysScales',
                ]);
            }
        }

        /**
         * Se relaciona con PayrollConceptAssignOption
         */
        if ($request->assign_to) {
            foreach ($request->assign_to as $assign_to) {
                if ($assign_to['type'] == 'range') {
                    PayrollConceptAssignOption::create([
                        'key'                => $assign_to['id'],
                        'value'              => json_encode($request->assign_options[$assign_to['id']]),
                        'applicable_type' => PayrollVacationPolicy::class,
                        'applicable_id' => $payrollVacationPolicy->id,
                    ]);
                } elseif ($assign_to['type'] == 'list') {
                    foreach ($request->assign_options[$assign_to['id']] as $assign_option) {
                        /**
                         * Objeto asociado al modelo PayrollConceptAssignOption
                         * @var Object $payrollConceptAssignOption
                         */
                        $payrollConceptAssignOption = PayrollConceptAssignOption::create([
                            'key'                => $assign_to['id'],
                            'applicable_type' => PayrollVacationPolicy::class,
                            'applicable_id' => $payrollVacationPolicy->id,
                        ]);
                        /** Se guarda la información en el campo morphs */
                        $option = $assign_to['model']::find($assign_option['id']);
                        $option->payrollConceptAssignOptions()->save($payrollConceptAssignOption);
                    }
                }
            };
        }

        return response()->json(['record' => $payrollVacationPolicy, 'message' => 'Success'], 200);
    }

    /**
     * Actualiza la información de una política vacacional
     *
     * @method    update
     *
     * @param     \Illuminate\Http\Request         $request    Datos de la petición
     * @param     Integer                          $id         Identificador único de la política vacacional
     *
     * @return    \Illuminate\Http\JsonResponse                Objeto con los registros a mostrar
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        /**
         * Objeto asociado al modelo PayrollVacationPolicy
         *
         * @var Object $payrollVacationPolicy
         */
        $payrollVacationPolicy = PayrollVacationPolicy::with('payrollConceptAssignOptions','payrollScales')->find($id);

        $validateRules  = $this->validateRules;
        if ($request->input('vacation_type') == 'collective_vacations') {
            $validateRules  = array_merge(
                $validateRules,
                [
                    'vacation_periods'                      => ['required'],
                    // 'salary_type'                           => ['required'],
                    'payroll_payment_type_id'               => ['required']
                ]
            );
        } elseif ($request->input('vacation_type') == 'vacation_period') {
            $validateRules  = array_merge(
                $validateRules,
                [
                    'vacation_periods_accumulated_per_year' => ['required'],
                    'vacation_period_per_year'              => ['required'],
                    'additional_days_per_year'              => ['required'],
                    //'minimum_additional_days_per_year'      => ['required'],
                    'maximum_additional_days_per_year'      => ['required'],
                    'payroll_payment_type_id'               => ['required'],
                    'from_year'                             => ['required', 'integer','between:1960,' . date('Y')]
                ]
            );
        }
        $this->validate($request, $validateRules, $this->messages);

        $profileUser = Auth()->user()->profile;
        if ($profileUser) {
            $institution = Institution::find($profileUser->institution_id);
        } else {
            $institution = Institution::where('active', true)->where('default', true)->first();
        }

        $payrollVacationPolicy->update([
            'name'                                  => $request->input('name'),
            'active'                                => !empty($request->active)
                ? $request->active
                : false,
            'start_date'                            => $request->input('start_date'),
            'end_date'                              => $request->input('end_date'),
            'vacation_periods'                      => json_encode($request->input('vacation_periods')),
            'vacation_type'                         => $request->input('vacation_type'),
            'institution_id'                        => 1 || $institution->id,
            'vacation_periods_accumulated_per_year' => $request->input('vacation_periods_accumulated_per_year'),
            'vacation_days'                         => $request->input('vacation_days'),
            'vacation_period_per_year'              => $request->input('vacation_period_per_year'),
            'additional_days_per_year'              => $request->input('additional_days_per_year'),
            //'minimum_additional_days_per_year'      => $request->input('minimum_additional_days_per_year'),
            'from_year'                             => $request->input('from_year'),
            'maximum_additional_days_per_year'      => $request->input('maximum_additional_days_per_year'),
            // 'salary_type'                           => $request->input('salary_type'),
            'vacation_advance'                      => $request->input('vacation_advance'),
            'vacation_postpone'                     => $request->input('vacation_postpone'),
            'staff_antiquity'                       => $request->input('staff_antiquity'),
            'institution_id'                        => $request->input('institution_id'),
            'payroll_payment_type_id'               => $request->input('payroll_payment_type_id'),
            'assign_to'                             => json_encode($request->assign_to),

            'on_scale'                              => $request->input('on_scale'),
            'worker_arises'                         => $request->input('worker_arises'),
            'generate_worker_arises'                => $request->input('generate_worker_arises'),
            'min_days_advance'                      => $request->input('min_days_advance'),

            'business_days'                         => $request->input('business_days'),
            'old_jobs'                              => $request->input('old_jobs'),

            // Agrupar por
             'group_by'                             => $request->input('group_by'),
             'type'                                 => $request->input('type'),

            'days_on_scale'                         => $request->input('days_on_scale'),
            'days_group_by'                         => $request->input('days_group_by'),
            'days_type'                             => $request->input('days_type'),
        ]);

        /** Se eliminan lo registros anteriores de payrollScales */
        foreach ($payrollVacationPolicy->payrollScales as $payrollScale) {
            $payrollScale->delete();
        }
        /**
         * Relaciona con escalas
         */
        if ($request->payroll_scales) {
            foreach ($request->payroll_scales as $payrollScale) {
                $payrollVacationPolicy->payrollScales()->create([
                    'name'                    => $payrollScale['name'],
                    'value'                   => json_encode($payrollScale['value']),
                    'relationship_type'       => 'payrollScales',
                ]);
            }
        }
        if ($request->payroll_days_scales) {
            foreach ($request->payroll_days_scales as $payrollScale) {
                $payrollVacationPolicy->payrollDaysScales()->create([
                    'name'                    => $payrollScale['name'],
                    'value'                   => json_encode($payrollScale['value']),
                    'relationship_type'       => 'payrollDaysScales',
                ]);
            }
        }

        /**
         * Se elimina los registros previos de payrollConceptAssignOptions
         */
        foreach ($payrollVacationPolicy->payrollConceptAssignOptions as $payrollConceptAssignOption) {
            $payrollConceptAssignOption->delete();
        }
        /**
         * Se relaciona con las opciones
         */
        if ($request->assign_to) {
            foreach ($request->assign_to as $assign_to) {
                if ($assign_to['type'] == 'range') {
                    PayrollConceptAssignOption::create([
                        'key'                => $assign_to['id'],
                        'value'              => json_encode($request->assign_options[$assign_to['id']]),
                        'applicable_type' => PayrollVacationPolicy::class,
                        'applicable_id' => $payrollVacationPolicy->id,
                    ]);
                } elseif ($assign_to['type'] == 'list') {
                    foreach ($request->assign_options[$assign_to['id']] as $assign_option) {
                        /**
                         * Objeto asociado al modelo PayrollConceptAssignOption
                         * @var Object $payrollConceptAssignOption
                         */
                        $payrollConceptAssignOption = PayrollConceptAssignOption::create([
                            'key'                => $assign_to['id'],
                            'applicable_type'    => PayrollVacationPolicy::class,
                            'applicable_id'      => $payrollVacationPolicy->id,
                        ]);
                        /** Se guarda la información en el campo morphs */
                        $option = $assign_to['model']::find($assign_option['id']);
                        $option->payrollConceptAssignOptions()->save($payrollConceptAssignOption);
                    }
                }
            };
        }

        return response()->json(['message' => 'Success'], 200);
    }

    /**
     * Elimina una política vacacional
     *
     * @method    destroy
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     *
     * @param     Integer                          $id    Identificador único de la política vacacional a eliminar
     *
     * @return    Renderable
     */
    public function destroy($id)
    {
        /**
         * Objeto asociado al modelo PayrollVacationPolicy
         *
         * @var Object $payrollVacationPolicy
         */
        $payrollVacationPolicy = PayrollVacationPolicy::with('payrollConceptAssignOptions','payrollScales')->find($id);
        /** Se eliminan lo registros anteriores */
        foreach ($payrollVacationPolicy->payrollScales as $payroll_scale) {
            $payroll_scale->delete();
        }
        /**
         * Se elimina los registros previos de payrollConceptAssignOptions
         */
        foreach ($payrollVacationPolicy->payrollConceptAssignOptions as $payrollConceptAssignOption) {
            $payrollConceptAssignOption->delete();
        }
        $payrollVacationPolicy->delete();
        return response()->json(['record' => $payrollVacationPolicy, 'message' => 'Success'], 200);
    }

    /**
     * Muestra los datos de la información de la política vacacional según la institución asociada
     * al trabajador autenticado
     *
     * @method    getVacationPolicy
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     *
     * @return    \Illuminate\Http\JsonResponse    Objeto con los registros a mostrar
     */
    public function getVacationPolicy()
    {
        $profileUser = Auth()->user()->profile;
        if ($profileUser) {
            $institution = Institution::find($profileUser->institution_id);
        } else {
            $institution = Institution::where('active', true)->where('default', true)->first();
        }

        $payrollVacationPolicy = PayrollVacationPolicy::with('payrollConceptAssignOptions')->where('institution_id', $institution->id)->first();
        return response()->json(['record' => $payrollVacationPolicy], 200);
    }
}
