<?php

namespace Modules\Payroll\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Modules\Payroll\Models\Payroll;
use Modules\Payroll\Models\Parameter;
use Modules\Payroll\Models\Institution;
use Modules\Payroll\Models\PayrollStaff;
use Modules\Payroll\Models\PayrollConcept;
use Modules\Payroll\Models\PayrollProfessional;
use Modules\Payroll\Models\PayrollStaffPayroll;
use Modules\Payroll\Models\PayrollSalaryTabulator;
use Modules\Payroll\Models\PayrollSalaryTabulatorScale;
use Modules\Payroll\Jobs\PayrollCreatePaymentRelationship;
use Modules\Payroll\Repositories\PayrollAssociatedParametersRepository;

/**
 * @class      PayrollController
 * @brief      Controlador de registros de nómina
 *
 * Clase que gestiona los registros de nómina
 *
 * @author     Henry Paredes <hparedes@cenditel.gob.ve>
 * @license
 *     [LICENCIA DE SOFTWARE CENDITEL](http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/)
 */
class PayrollController extends Controller
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
        //$this->middleware('permission:payroll.registers.list',   ['only' => ['index', 'vueList']]);
        //$this->middleware('permission:payroll.registers.create', ['only' => ['create', 'store']]);
        //$this->middleware('permission:payroll.registers.edit',   ['only' => ['edit', 'update']]);
        //$this->middleware('permission:payroll.registers.delete', ['only' => 'destroy']);

        /** Define las reglas de validación para el formulario */
        $this->validateRules = [
            'created_at'                => ['required'],
            'name'                      => ['required'],
            'payroll_payment_type_id'   => ['required'],
            'payroll_payment_period_id' => ['required'],
            'payroll_concepts'          => ['required']
        ];

        /** Define los mensajes de validación para las reglas del formulario */
        $this->messages = [
            'created_at.required'                => 'El campo fecha de generación es obligatorio.',
            'payroll_payment_type_id.required'   => 'El campo tipo de pago de nómina es obligatorio.',
            'payroll_payment_period_id.required' => 'El campo período de pago es obligatorio.',
            'payroll_concepts.required'          => 'El campo concepto es obligatorio.'
        ];
    }

    /**
     * Muestra un listado de las nóminas de sueldos registradas
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     *
     * @return    Renderable
     */
    public function index()
    {
        return view('payroll::registers.index');
    }

    /**
     * Muestra el formulario para registrar una nueva nómina de sueldos
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     *
     * @return    Renderable
     */
    public function create()
    {
        return view('payroll::registers.create-edit');
    }

    /**
     * Valida y registra una nueva nómina de sueldos
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     *
     * @param     \Illuminate\Http\Request         $request    Datos de la petición
     *
     * @return    \Illuminate\Http\JsonResponse                Objeto con los registros a mostrar
     */
    public function store(Request $request)
    {
        ini_set('max_execution_time', 600);
        $this->validate($request, $this->validateRules, $this->messages);
        $created_at = now();
        $payrollParameters = new PayrollAssociatedParametersRepository;

        /**
         * Objeto asociado al modelo Payroll
         * @var    Object    $payroll
         */
        $payroll = Payroll::updateOrCreate(
            [
                'id'                        => $request['id']
            ],
            [
                'name'                      => $request['name'],
                'payroll_payment_period_id' => $request['payroll_payment_period_id'],
                'payroll_parameters'        => json_encode($request['payroll_parameters']),
                'created_at'                => $request['created_at'] ?? $created_at
            ]
        );

        /** Se recorren los conceptos establecidos para la generación de la nómina */
        $concepts = [];
        foreach ($request['payroll_concepts'] as $concept) {
            $formula = null;
            $payrollConcept = PayrollConcept::find($concept['id']);
            $formula = $this->translateFormConcept($payrollConcept->formula);
            $exploded = multiexplode(
                [
                    'if', '(', ')', '{', '}', ' ',
                    '==', '<=', '>=', '<', '>', '!=',
                    '+', '-','*','/'
                ],
                $formula
            );
            while (count($exploded) > 0) {
                $complete = false;
                $current = max_length($exploded);
                $key = array_search($current, $exploded);
                /** Se descartan los elementos vacios y las constantes númericas */
                if ($current == '' || is_numeric($current)) {
                    unset($exploded[$key]);
                    $complete = true;
                } else {
                    /** Se recorre el listado de parámetros para sustituirlos por su valor real
                      * en la formula del concepto */
                    foreach ($request['payroll_parameters'] as $parameter) {
                        if ($parameter['name'] == $current) {
                            unset($exploded[$key]);
                            $complete = true;
                            $formula = str_replace($parameter['name'], $parameter['value'], $formula);
                        }
                    }
                    if ($complete == false) {
                        /** Se descartan los parametro de vacaciones y los del expediente del trabajador
                         * para ser analizados mas adelante */
                        unset($exploded[$key]);
                        $complete = true;
                    }
                }
            }
            array_push($concepts, ['field' => $payrollConcept, 'formula' => $formula]);
        }
        /** Se evaluan los parámetros del expediente del trabajador y de la configuración de vacaciones */
        /** Se identifica la institución en la que se está operando */
        $institution = Institution::where('active', true)->where('default', true)->first();
        /* Revisar (No funciona en segundo plano --- Alternativa solicitar institucion desde formulario)
        $profileUser = Auth()->user()->profile;
        if ($profileUser) {
            $institution = Institution::find($profileUser->institution_id);
        } else {
            $institution = Institution::where('active', true)->where('default', true)->first();
        }
        */
        /** Se obtienen todos los trabajadores asociados a la institución y se evalua si aplica cada uno de los conceptos */
        $payrollStaffs = PayrollStaff::whereHas('payrollEmployment', function ($q) use ($institution) {
            $q->whereHas('department', function ($qq) use ($institution) {
                $qq->where('institution_id', $institution->id);
            });
        })->get();

        foreach ($payrollStaffs as $payrollStaff) {
            /** Se definen los arreglos de asignaciones y deducciones para clasificar los conceptos */
            $assignments = [];
            $deductions  = [];
            foreach ($concepts as $concept) {
                $formula = $concept['formula'];
                /** Se hace la busqueda de los tabuladores */
                $matchs = [];
                preg_match_all("/tabulator\([0-9]+\)/", $formula, $matchs);
                foreach ($matchs[0] as $match) {
                    $id = substr($match, (strpos($match, "(") +1), strpos($match, ")")-(strpos($match, "(")+1));
                    $payrollSalaryTabulator = PayrollSalaryTabulator::find($id);

                    if ($payrollSalaryTabulator->payroll_salary_tabulator_type == 'horizontal') {
                        /** Se carga el escalafón horizontal asociado al tabulador */
                        $payrollSalaryTabulator->load(['payrollHorizontalSalaryScale' => function($q) {
                            $q->with('payrollScales');
                        }]);
                        foreach ($payrollParameters->loadData('associatedWorkerFile') as $parameter) {
                            if (!empty($parameter['children'])) {
                                foreach ($parameter['children'] as $children) {
                                    if ($children['id'] == $payrollSalaryTabulator->payrollHorizontalSalaryScale['group_by']) {
                                        $record = ($parameter['model'] != PayrollStaff::class)
                                            ? $parameter['model']::where('payroll_staff_id', $payrollStaff->id)->first()
                                            : $payrollStaff;
                                        if (isset($record)) {
                                            foreach ($payrollSalaryTabulator->payrollHorizontalSalaryScale->payrollScales as $scale) {
                                                if ($children['type'] == 'number') {
                                                    /** Se calcula el número de registros existentes según sea el caso
                                                     * y se sustituye por su valor en el tabulador */
                                                    $scl = json_decode($scale['value']);
                                                    $record->loadCount($children['required'][0]);

                                                    if (isset($scl->from) && isset($scl->to)) {
                                                        if (($record[Str::snake($children['required'][0]) . '_count'] >= $scl->from) &&
                                                            ($record[Str::snake($children['required'][0]) . '_count'] <= $scl->to)) {
                                                            $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                ->where('payroll_vertical_scale_id', null)->first();
                                                            if ($payrollSalaryTabulator->percentage) {
                                                                $formula = str_replace(
                                                                    $match,
                                                                    $tabScale['value']/100,
                                                                    $formula ?? $concept['formula']
                                                                );
                                                            } else {
                                                                $formula = str_replace(
                                                                    $match,
                                                                    $tabScale['value'],
                                                                    $formula ?? $concept['formula']
                                                                );
                                                            }
                                                        }
                                                    } else {
                                                        if ($scl == $record[Str::snake($children['required'][0]) . '_count']) {
                                                            $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                ->where('payroll_vertical_scale_id', null)->first();
                                                            if ($payrollSalaryTabulator->percentage) {
                                                                $formula = str_replace(
                                                                    $match,
                                                                    $tabScale['value']/100,
                                                                    $formula ?? $concept['formula']
                                                                );
                                                            } else {
                                                                $formula = str_replace(
                                                                    $match,
                                                                    $tabScale['value'],
                                                                    $formula ?? $concept['formula']
                                                                );
                                                            }
                                                        }
                                                    }
                                                } elseif ($children['type'] == 'date') {
                                                    /** Se calcula el número de años según la fecha de ingreso
                                                      * y se sustituye por su valor en el tabulador */
                                                    $scl = json_decode($scale['value']);
                                                    if (isset($scl->from) && isset($scl->to)) {
                                                        if ((age($record[$children['required'][0]]) >= $scl->from) &&
                                                            (age($record[$children['required'][0]]) <= $scl->to)) {
                                                            $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                ->where('payroll_vertical_scale_id', null)->first();
                                                            if ($payrollSalaryTabulator->percentage) {
                                                                $formula = str_replace(
                                                                    $match,
                                                                    $tabScale['value']/100,
                                                                    $formula ?? $concept['formula']
                                                                );
                                                            } else {
                                                                $formula = str_replace(
                                                                    $match,
                                                                    $tabScale['value'],
                                                                    $formula ?? $concept['formula']
                                                                );
                                                            }
                                                        }
                                                    } else {
                                                        if ($scl == age($record[$children['required'][0]])) {
                                                            $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                ->where('payroll_vertical_scale_id', null)->first();
                                                            if ($payrollSalaryTabulator->percentage) {
                                                                $formula = str_replace(
                                                                    $match,
                                                                    $tabScale['value']/100,
                                                                    $formula ?? $concept['formula']
                                                                );
                                                            } else {
                                                                $formula = str_replace(
                                                                    $match,
                                                                    $tabScale['value'],
                                                                    $formula ?? $concept['formula']
                                                                );
                                                            }
                                                        }
                                                    }
                                                } else {
                                                    /** Se identifica el valor según el expediente del trabajador
                                                     * y se sustituye por su valor en el tabulador */
                                                    if (json_decode($scale['value']) == $record[$children['required'][0]]) {
                                                        $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                            ->where('payroll_horizontal_scale_id', $scale['id'])
                                                            ->where('payroll_vertical_scale_id', null)->first();

                                                        if ($payrollSalaryTabulator->percentage) {
                                                            $formula = str_replace(
                                                                $match,
                                                                $tabScale['value']/100,
                                                                $formula ?? $concept['formula']
                                                            );
                                                        } else {
                                                            $formula = str_replace(
                                                                $match,
                                                                $tabScale['value'],
                                                                $formula ?? $concept['formula']
                                                            );
                                                        }
                                                    }
                                                }
                                            }
                                        } else {
                                            $formula = str_replace(
                                                $match,
                                                0,
                                                $formula ?? $concept['formula']
                                            );
                                        }
                                    }
                                }
                            }
                        }
                    } elseif ($payrollSalaryTabulator->payroll_salary_tabulator_type == 'vertical') {
                        /** Se carga el escalafón vertical asociado al tabulador */
                        $payrollSalaryTabulator->load(['payrollVerticalSalaryScale' => function($q) {
                            $q->with('payrollScales');
                        }]);
                        foreach ($payrollParameters->loadData('associatedWorkerFile') as $parameter) {
                            if (!empty($parameter['children'])) {
                                foreach ($parameter['children'] as $children) {
                                    if ($children['id'] == $payrollSalaryTabulator->payrollVerticalSalaryScale['group_by']) {
                                        $record = ($parameter['model'] != PayrollStaff::class)
                                            ? $parameter['model']::where('payroll_staff_id', $payrollStaff->id)->first()
                                            : $payrollStaff;
                                        if (isset($record)) {
                                            foreach ($payrollSalaryTabulator->payrollVerticalSalaryScale->payrollScales as $scale) {
                                                if ($children['type'] == 'number') {
                                                    /** Se calcula el número de registros existentes según sea el caso
                                                     * y se sustituye por su valor en el tabulador */
                                                    $scl = json_decode($scale['value']);
                                                    $record->loadCount($children['required'][0]);

                                                    if (isset($scl->from) && isset($scl->to)) {
                                                        if (($record[Str::snake($children['required'][0]) . '_count'] >= $scl->from) &&
                                                            ($record[Str::snake($children['required'][0]) . '_count'] <= $scl->to)) {
                                                            $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                ->where('payroll_horizontal_scale_id', null)
                                                                ->where('payroll_vertical_scale_id', $scale['id'])->first();
                                                            if ($payrollSalaryTabulator->percentage) {
                                                                $formula = str_replace(
                                                                    $match,
                                                                    $tabScale['value']/100,
                                                                    $formula ?? $concept['formula']
                                                                );
                                                            } else {
                                                                $formula = str_replace(
                                                                    $match,
                                                                    $tabScale['value'],
                                                                    $formula ?? $concept['formula']
                                                                );
                                                            }
                                                        }
                                                    } else {
                                                        if ($scl == $record[Str::snake($children['required'][0]) . '_count']) {
                                                            $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                ->where('payroll_horizontal_scale_id', null)
                                                                ->where('payroll_vertical_scale_id', $scale['id'])->first();
                                                            if ($payrollSalaryTabulator->percentage) {
                                                                $formula = str_replace(
                                                                    $match,
                                                                    $tabScale['value']/100,
                                                                    $formula ?? $concept['formula']
                                                                );
                                                            } else {
                                                                $formula = str_replace(
                                                                    $match,
                                                                    $tabScale['value'],
                                                                    $formula ?? $concept['formula']
                                                                );
                                                            }
                                                        }
                                                    }
                                                } elseif ($children['type'] == 'date') {
                                                    /** Se calcula el número de años según la fecha de ingreso
                                                      * y se sustituye por su valor en el tabulador */
                                                    $scl = json_decode($scale['value']);
                                                    if (isset($scl->from) && isset($scl->to)) {
                                                        if ((age($record[$children['required'][0]]) >= $scl->from) &&
                                                            (age($record[$children['required'][0]]) <= $scl->to)) {
                                                            $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                ->where('payroll_horizontal_scale_id', null)
                                                                ->where('payroll_vertical_scale_id', $scale['id'])->first();
                                                            if ($payrollSalaryTabulator->percentage) {
                                                                $formula = str_replace(
                                                                    $match,
                                                                    $tabScale['value']/100,
                                                                    $formula ?? $concept['formula']
                                                                );
                                                            } else {
                                                                $formula = str_replace(
                                                                    $match,
                                                                    $tabScale['value'],
                                                                    $formula ?? $concept['formula']
                                                                );
                                                            }
                                                        }
                                                    } else {
                                                        if ($scl == age($record[$children['required'][0]])) {
                                                            $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                ->where('payroll_horizontal_scale_id', null)
                                                                ->where('payroll_vertical_scale_id', $scale['id'])->first();
                                                            if ($payrollSalaryTabulator->percentage) {
                                                                $formula = str_replace(
                                                                    $match,
                                                                    $tabScale['value']/100,
                                                                    $formula ?? $concept['formula']
                                                                );
                                                            } else {
                                                                $formula = str_replace(
                                                                    $match,
                                                                    $tabScale['value'],
                                                                    $formula ?? $concept['formula']
                                                                );
                                                            }
                                                        }
                                                    }
                                                } else {
                                                    /** Se identifica el valor según el expediente del trabajador
                                                     * y se sustituye por su valor en el tabulador */
                                                    if (json_decode($scale['value']) == $record[$children['required'][0]]) {
                                                        $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                            ->where('payroll_horizontal_scale_id', null)
                                                            ->where('payroll_vertical_scale_id', $scale['id'])->first();

                                                        if ($payrollSalaryTabulator->percentage) {
                                                            $formula = str_replace(
                                                                $match,
                                                                $tabScale['value']/100,
                                                                $formula ?? $concept['formula']
                                                            );
                                                        } else {
                                                            $formula = str_replace(
                                                                $match,
                                                                $tabScale['value'],
                                                                $formula ?? $concept['formula']
                                                            );
                                                        }
                                                    }
                                                }
                                            }
                                        } else {
                                            $formula = str_replace(
                                                $match,
                                                0,
                                                $formula ?? $concept['formula']
                                            );
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        /** Se cargan los escalafones horizontal y vertical asociados al tabulador */
                        $payrollSalaryTabulator->load([
                            'payrollHorizontalSalaryScale' => function ($q) {
                                $q->with('payrollScales');
                            }, 'payrollVerticalSalaryScale' => function($q) {
                                $q->with('payrollScales');
                            }
                        ]);
                        foreach ($payrollParameters->loadData('associatedWorkerFile') as $parameter) {
                            if (!empty($parameter['children'])) {
                                foreach ($parameter['children'] as $children) {
                                    if ($children['id'] == $payrollSalaryTabulator->payrollHorizontalSalaryScale['group_by']) {
                                        $record = ($parameter['model'] != PayrollStaff::class)
                                            ? $parameter['model']::where('payroll_staff_id', $payrollStaff->id)->first()
                                            : $payrollStaff;
                                        if (isset($record)) {
                                            foreach ($payrollSalaryTabulator->payrollHorizontalSalaryScale->payrollScales as $scale) {
                                                if ($children['type'] == 'number') {
                                                    /** Se calcula el número de registros existentes según sea el caso
                                                     * y se sustituye por su valor en el tabulador */
                                                    $scl = json_decode($scale['value']);
                                                    $record->loadCount($children['required'][0]);

                                                    if (isset($scl->from) && isset($scl->to)) {
                                                        if (($record[Str::snake($children['required'][0]) . '_count'] >= $scl->from) &&
                                                            ($record[Str::snake($children['required'][0]) . '_count'] <= $scl->to)) {

                                                            foreach ($payrollParameters->loadData('associatedWorkerFile') as $parameterV) {
                                                                if (!empty($parameterV['children'])) {
                                                                    foreach ($parameterV['children'] as $childrenV) {
                                                                        if ($childrenV['id'] == $payrollSalaryTabulator->payrollVerticalSalaryScale['group_by']) {
                                                                            $recordV = ($parameterV['model'] != PayrollStaff::class)
                                                                                ? $parameterV['model']::where('payroll_staff_id', $payrollStaff->id)->first()
                                                                                : $payrollStaff;
                                                                            if (isset($recordV)) {
                                                                                foreach ($payrollSalaryTabulator->payrollVerticalSalaryScale->payrollScales as $scaleV) {
                                                                                    if ($childrenV['type'] == 'number') {
                                                                                        /** Se calcula el número de registros existentes según sea el caso
                                                                                         * y se sustituye por su valor en el tabulador */
                                                                                        $sclV = json_decode($scaleV['value']);
                                                                                        $recordV->loadCount($childrenV['required'][0]);

                                                                                        if (isset($sclV->from) && isset($sclV->to)) {
                                                                                            if (($recordV[Str::snake($childrenV['required'][0]) . '_count'] >= $sclV->from) &&
                                                                                                ($recordV[Str::snake($childrenV['required'][0]) . '_count'] <= $sclV->to)) {
                                                                                                
                                                                                                $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                                                    ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                                                    ->where('payroll_vertical_scale_id', $scaleV['id'])->first();
                                                                                                if ($payrollSalaryTabulator->percentage) {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value']/100,
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                } else {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value'],
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                }
                                                                                            }
                                                                                        } else {
                                                                                            if ($sclV == $recordV[Str::snake($childrenV['required'][0]) . '_count']) {
                                                                                                $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                                                    ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                                                    ->where('payroll_vertical_scale_id', $scaleV['id'])->first();
                                                                                                if ($payrollSalaryTabulator->percentage) {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value']/100,
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                } else {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value'],
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    } elseif ($childrenV['type'] == 'date') {
                                                                                        /** Se calcula el número de años según la fecha de ingreso
                                                                                          * y se sustituye por su valor en el tabulador */
                                                                                        $sclV = json_decode($scaleV['value']);
                                                                                        if (isset($sclV->from) && isset($sclV->to)) {
                                                                                            if ((age($recordV[$childrenV['required'][0]]) >= $sclV->from) &&
                                                                                                (age($recordV[$childrenV['required'][0]]) <= $sclV->to)) {
                                                                                                
                                                                                                $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                                                    ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                                                    ->where('payroll_vertical_scale_id', $scaleV['id'])->first();
                                                                                                if ($payrollSalaryTabulator->percentage) {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value']/100,
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                } else {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value'],
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                }
                                                                                            }
                                                                                        } else {
                                                                                            if ($sclV == age($recordV[$childrenV['required'][0]])) {
                                                                                                
                                                                                                $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                                                    ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                                                    ->where('payroll_vertical_scale_id', $scaleV['id'])->first();
                                                                                                if ($payrollSalaryTabulator->percentage) {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value']/100,
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                } else {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value'],
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    } else {
                                                                                        /** Se identifica el valor según el expediente del trabajador
                                                                                         * y se sustituye por su valor en el tabulador */
                                                                                        if (json_decode($scaleV['value']) == $recordV[$childrenV['required'][0]]) {
                                                                                            
                                                                                            $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                                                ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                                                ->where('payroll_vertical_scale_id', $scaleV['id'])->first();

                                                                                            if ($payrollSalaryTabulator->percentage) {
                                                                                                $formula = str_replace(
                                                                                                    $match,
                                                                                                    $tabScale['value']/100,
                                                                                                    $formula ?? $concept['formula']
                                                                                                );
                                                                                            } else {
                                                                                                $formula = str_replace(
                                                                                                    $match,
                                                                                                    $tabScale['value'],
                                                                                                    $formula ?? $concept['formula']
                                                                                                );
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }
                                                                            } else {
                                                                                $formula = str_replace(
                                                                                    $match,
                                                                                    0,
                                                                                    $formula ?? $concept['formula']
                                                                                );
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    } else {
                                                        if ($scl == $record[Str::snake($children['required'][0]) . '_count']) {
                                                            foreach ($payrollParameters->loadData('associatedWorkerFile') as $parameterV) {
                                                                if (!empty($parameterV['children'])) {
                                                                    foreach ($parameterV['children'] as $childrenV) {
                                                                        if ($childrenV['id'] == $payrollSalaryTabulator->payrollVerticalSalaryScale['group_by']) {
                                                                            $recordV = ($parameterV['model'] != PayrollStaff::class)
                                                                                ? $parameterV['model']::where('payroll_staff_id', $payrollStaff->id)->first()
                                                                                : $payrollStaff;
                                                                            if (isset($recordV)) {
                                                                                foreach ($payrollSalaryTabulator->payrollVerticalSalaryScale->payrollScales as $scaleV) {
                                                                                    if ($childrenV['type'] == 'number') {
                                                                                        /** Se calcula el número de registros existentes según sea el caso
                                                                                         * y se sustituye por su valor en el tabulador */
                                                                                        $sclV = json_decode($scaleV['value']);
                                                                                        $recordV->loadCount($childrenV['required'][0]);

                                                                                        if (isset($sclV->from) && isset($sclV->to)) {
                                                                                            if (($recordV[Str::snake($childrenV['required'][0]) . '_count'] >= $sclV->from) &&
                                                                                                ($recordV[Str::snake($childrenV['required'][0]) . '_count'] <= $sclV->to)) {
                                                                                                
                                                                                                $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                                                    ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                                                    ->where('payroll_vertical_scale_id', $scaleV['id'])->first();
                                                                                                if ($payrollSalaryTabulator->percentage) {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value']/100,
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                } else {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value'],
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                }
                                                                                            }
                                                                                        } else {
                                                                                            if ($sclV == $recordV[Str::snake($childrenV['required'][0]) . '_count']) {
                                                                                                $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                                                    ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                                                    ->where('payroll_vertical_scale_id', $scaleV['id'])->first();
                                                                                                if ($payrollSalaryTabulator->percentage) {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value']/100,
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                } else {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value'],
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    } elseif ($childrenV['type'] == 'date') {
                                                                                        /** Se calcula el número de años según la fecha de ingreso
                                                                                          * y se sustituye por su valor en el tabulador */
                                                                                        $sclV = json_decode($scaleV['value']);
                                                                                        if (isset($sclV->from) && isset($sclV->to)) {
                                                                                            if ((age($recordV[$childrenV['required'][0]]) >= $sclV->from) &&
                                                                                                (age($recordV[$childrenV['required'][0]]) <= $sclV->to)) {
                                                                                                
                                                                                                $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                                                    ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                                                    ->where('payroll_vertical_scale_id', $scaleV['id'])->first();
                                                                                                if ($payrollSalaryTabulator->percentage) {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value']/100,
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                } else {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value'],
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                }
                                                                                            }
                                                                                        } else {
                                                                                            if ($sclV == age($recordV[$childrenV['required'][0]])) {
                                                                                                
                                                                                                $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                                                    ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                                                    ->where('payroll_vertical_scale_id', $scaleV['id'])->first();
                                                                                                if ($payrollSalaryTabulator->percentage) {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value']/100,
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                } else {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value'],
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    } else {
                                                                                        /** Se identifica el valor según el expediente del trabajador
                                                                                         * y se sustituye por su valor en el tabulador */
                                                                                        if (json_decode($scaleV['value']) == $recordV[$childrenV['required'][0]]) {
                                                                                            
                                                                                            $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                                                ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                                                ->where('payroll_vertical_scale_id', $scaleV['id'])->first();

                                                                                            if ($payrollSalaryTabulator->percentage) {
                                                                                                $formula = str_replace(
                                                                                                    $match,
                                                                                                    $tabScale['value']/100,
                                                                                                    $formula ?? $concept['formula']
                                                                                                );
                                                                                            } else {
                                                                                                $formula = str_replace(
                                                                                                    $match,
                                                                                                    $tabScale['value'],
                                                                                                    $formula ?? $concept['formula']
                                                                                                );
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }
                                                                            } else {
                                                                                $formula = str_replace(
                                                                                    $match,
                                                                                    0,
                                                                                    $formula ?? $concept['formula']
                                                                                );
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                } elseif ($children['type'] == 'date') {
                                                    /** Se calcula el número de años según la fecha de ingreso
                                                      * y se sustituye por su valor en el tabulador */
                                                    $scl = json_decode($scale['value']);
                                                    if (isset($scl->from) && isset($scl->to)) {
                                                        if ((age($record[$children['required'][0]]) >= $scl->from) &&
                                                            (age($record[$children['required'][0]]) <= $scl->to)) {
                                                            foreach ($payrollParameters->loadData('associatedWorkerFile') as $parameterV) {
                                                                if (!empty($parameterV['children'])) {
                                                                    foreach ($parameterV['children'] as $childrenV) {
                                                                        if ($childrenV['id'] == $payrollSalaryTabulator->payrollVerticalSalaryScale['group_by']) {
                                                                            $recordV = ($parameterV['model'] != PayrollStaff::class)
                                                                                ? $parameterV['model']::where('payroll_staff_id', $payrollStaff->id)->first()
                                                                                : $payrollStaff;
                                                                            if (isset($recordV)) {
                                                                                foreach ($payrollSalaryTabulator->payrollVerticalSalaryScale->payrollScales as $scaleV) {
                                                                                    if ($childrenV['type'] == 'number') {
                                                                                        /** Se calcula el número de registros existentes según sea el caso
                                                                                         * y se sustituye por su valor en el tabulador */
                                                                                        $sclV = json_decode($scaleV['value']);
                                                                                        $recordV->loadCount($childrenV['required'][0]);

                                                                                        if (isset($sclV->from) && isset($sclV->to)) {
                                                                                            if (($recordV[Str::snake($childrenV['required'][0]) . '_count'] >= $sclV->from) &&
                                                                                                ($recordV[Str::snake($childrenV['required'][0]) . '_count'] <= $sclV->to)) {
                                                                                                
                                                                                                $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                                                    ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                                                    ->where('payroll_vertical_scale_id', $scaleV['id'])->first();
                                                                                                if ($payrollSalaryTabulator->percentage) {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value']/100,
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                } else {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value'],
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                }
                                                                                            }
                                                                                        } else {
                                                                                            if ($sclV == $recordV[Str::snake($childrenV['required'][0]) . '_count']) {
                                                                                                $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                                                    ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                                                    ->where('payroll_vertical_scale_id', $scaleV['id'])->first();
                                                                                                if ($payrollSalaryTabulator->percentage) {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value']/100,
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                } else {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value'],
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    } elseif ($childrenV['type'] == 'date') {
                                                                                        /** Se calcula el número de años según la fecha de ingreso
                                                                                          * y se sustituye por su valor en el tabulador */
                                                                                        $sclV = json_decode($scaleV['value']);
                                                                                        if (isset($sclV->from) && isset($sclV->to)) {
                                                                                            if ((age($recordV[$childrenV['required'][0]]) >= $sclV->from) &&
                                                                                                (age($recordV[$childrenV['required'][0]]) <= $sclV->to)) {
                                                                                                
                                                                                                $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                                                    ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                                                    ->where('payroll_vertical_scale_id', $scaleV['id'])->first();
                                                                                                if ($payrollSalaryTabulator->percentage) {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value']/100,
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                } else {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value'],
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                }
                                                                                            }
                                                                                        } else {
                                                                                            if ($sclV == age($recordV[$childrenV['required'][0]])) {
                                                                                                
                                                                                                $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                                                    ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                                                    ->where('payroll_vertical_scale_id', $scaleV['id'])->first();
                                                                                                if ($payrollSalaryTabulator->percentage) {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value']/100,
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                } else {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value'],
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    } else {
                                                                                        /** Se identifica el valor según el expediente del trabajador
                                                                                         * y se sustituye por su valor en el tabulador */
                                                                                        if (json_decode($scaleV['value']) == $recordV[$childrenV['required'][0]]) {
                                                                                            
                                                                                            $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                                                ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                                                ->where('payroll_vertical_scale_id', $scaleV['id'])->first();

                                                                                            if ($payrollSalaryTabulator->percentage) {
                                                                                                $formula = str_replace(
                                                                                                    $match,
                                                                                                    $tabScale['value']/100,
                                                                                                    $formula ?? $concept['formula']
                                                                                                );
                                                                                            } else {
                                                                                                $formula = str_replace(
                                                                                                    $match,
                                                                                                    $tabScale['value'],
                                                                                                    $formula ?? $concept['formula']
                                                                                                );
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }
                                                                            } else {
                                                                                $formula = str_replace(
                                                                                    $match,
                                                                                    0,
                                                                                    $formula ?? $concept['formula']
                                                                                );
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    } else {
                                                        if ($scl == age($record[$children['required'][0]])) {
                                                            foreach ($payrollParameters->loadData('associatedWorkerFile') as $parameterV) {
                                                                if (!empty($parameterV['children'])) {
                                                                    foreach ($parameterV['children'] as $childrenV) {
                                                                        if ($childrenV['id'] == $payrollSalaryTabulator->payrollVerticalSalaryScale['group_by']) {
                                                                            $recordV = ($parameterV['model'] != PayrollStaff::class)
                                                                                ? $parameterV['model']::where('payroll_staff_id', $payrollStaff->id)->first()
                                                                                : $payrollStaff;
                                                                            if (isset($recordV)) {
                                                                                foreach ($payrollSalaryTabulator->payrollVerticalSalaryScale->payrollScales as $scaleV) {
                                                                                    if ($childrenV['type'] == 'number') {
                                                                                        /** Se calcula el número de registros existentes según sea el caso
                                                                                         * y se sustituye por su valor en el tabulador */
                                                                                        $sclV = json_decode($scaleV['value']);
                                                                                        $recordV->loadCount($childrenV['required'][0]);

                                                                                        if (isset($sclV->from) && isset($sclV->to)) {
                                                                                            if (($recordV[Str::snake($childrenV['required'][0]) . '_count'] >= $sclV->from) &&
                                                                                                ($recordV[Str::snake($childrenV['required'][0]) . '_count'] <= $sclV->to)) {
                                                                                                
                                                                                                $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                                                    ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                                                    ->where('payroll_vertical_scale_id', $scaleV['id'])->first();
                                                                                                if ($payrollSalaryTabulator->percentage) {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value']/100,
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                } else {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value'],
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                }
                                                                                            }
                                                                                        } else {
                                                                                            if ($sclV == $recordV[Str::snake($childrenV['required'][0]) . '_count']) {
                                                                                                $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                                                    ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                                                    ->where('payroll_vertical_scale_id', $scaleV['id'])->first();
                                                                                                if ($payrollSalaryTabulator->percentage) {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value']/100,
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                } else {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value'],
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    } elseif ($childrenV['type'] == 'date') {
                                                                                        /** Se calcula el número de años según la fecha de ingreso
                                                                                          * y se sustituye por su valor en el tabulador */
                                                                                        $sclV = json_decode($scaleV['value']);
                                                                                        if (isset($sclV->from) && isset($sclV->to)) {
                                                                                            if ((age($recordV[$childrenV['required'][0]]) >= $sclV->from) &&
                                                                                                (age($recordV[$childrenV['required'][0]]) <= $sclV->to)) {
                                                                                                
                                                                                                $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                                                    ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                                                    ->where('payroll_vertical_scale_id', $scaleV['id'])->first();
                                                                                                if ($payrollSalaryTabulator->percentage) {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value']/100,
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                } else {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value'],
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                }
                                                                                            }
                                                                                        } else {
                                                                                            if ($sclV == age($recordV[$childrenV['required'][0]])) {
                                                                                                
                                                                                                $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                                                    ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                                                    ->where('payroll_vertical_scale_id', $scaleV['id'])->first();
                                                                                                if ($payrollSalaryTabulator->percentage) {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value']/100,
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                } else {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value'],
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    } else {
                                                                                        /** Se identifica el valor según el expediente del trabajador
                                                                                         * y se sustituye por su valor en el tabulador */
                                                                                        if (json_decode($scaleV['value']) == $recordV[$childrenV['required'][0]]) {
                                                                                            
                                                                                            $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                                                ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                                                ->where('payroll_vertical_scale_id', $scaleV['id'])->first();

                                                                                            if ($payrollSalaryTabulator->percentage) {
                                                                                                $formula = str_replace(
                                                                                                    $match,
                                                                                                    $tabScale['value']/100,
                                                                                                    $formula ?? $concept['formula']
                                                                                                );
                                                                                            } else {
                                                                                                $formula = str_replace(
                                                                                                    $match,
                                                                                                    $tabScale['value'],
                                                                                                    $formula ?? $concept['formula']
                                                                                                );
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }
                                                                            } else {
                                                                                $formula = str_replace(
                                                                                    $match,
                                                                                    0,
                                                                                    $formula ?? $concept['formula']
                                                                                );
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                } else {
                                                    /** Se identifica el valor según el expediente del trabajador
                                                     * y se sustituye por su valor en el tabulador */
                                                    if (json_decode($scale['value']) == $record[$children['required'][0]]) {
                                                        foreach ($payrollParameters->loadData('associatedWorkerFile') as $parameterV) {
                                                            if (!empty($parameterV['children'])) {
                                                                foreach ($parameterV['children'] as $childrenV) {
                                                                    if ($childrenV['id'] == $payrollSalaryTabulator->payrollVerticalSalaryScale['group_by']) {
                                                                        $recordV = ($parameterV['model'] != PayrollStaff::class)
                                                                            ? $parameterV['model']::where('payroll_staff_id', $payrollStaff->id)->first()
                                                                            : $payrollStaff;
                                                                        if (isset($recordV)) {
                                                                            foreach ($payrollSalaryTabulator->payrollVerticalSalaryScale->payrollScales as $scaleV) {
                                                                                if ($childrenV['type'] == 'number') {
                                                                                    /** Se calcula el número de registros existentes según sea el caso
                                                                                     * y se sustituye por su valor en el tabulador */
                                                                                    $sclV = json_decode($scaleV['value']);
                                                                                    $recordV->loadCount($childrenV['required'][0]);

                                                                                    if (isset($sclV->from) && isset($sclV->to)) {
                                                                                        if (($recordV[Str::snake($childrenV['required'][0]) . '_count'] >= $sclV->from) &&
                                                                                            ($recordV[Str::snake($childrenV['required'][0]) . '_count'] <= $sclV->to)) {
                                                                                            
                                                                                            $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                                                ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                                                ->where('payroll_vertical_scale_id', $scaleV['id'])->first();
                                                                                            if ($payrollSalaryTabulator->percentage) {
                                                                                                $formula = str_replace(
                                                                                                    $match,
                                                                                                    $tabScale['value']/100,
                                                                                                    $formula ?? $concept['formula']
                                                                                                );
                                                                                            } else {
                                                                                                $formula = str_replace(
                                                                                                    $match,
                                                                                                    $tabScale['value'],
                                                                                                    $formula ?? $concept['formula']
                                                                                                );
                                                                                            }
                                                                                        }
                                                                                    } else {
                                                                                        if ($sclV == $recordV[Str::snake($childrenV['required'][0]) . '_count']) {
                                                                                            $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                                                ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                                                ->where('payroll_vertical_scale_id', $scaleV['id'])->first();
                                                                                            if ($payrollSalaryTabulator->percentage) {
                                                                                                $formula = str_replace(
                                                                                                    $match,
                                                                                                    $tabScale['value']/100,
                                                                                                    $formula ?? $concept['formula']
                                                                                                );
                                                                                            } else {
                                                                                                $formula = str_replace(
                                                                                                    $match,
                                                                                                    $tabScale['value'],
                                                                                                    $formula ?? $concept['formula']
                                                                                                );
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                } elseif ($childrenV['type'] == 'date') {
                                                                                    /** Se calcula el número de años según la fecha de ingreso
                                                                                      * y se sustituye por su valor en el tabulador */
                                                                                    $sclV = json_decode($scaleV['value']);
                                                                                    if (isset($sclV->from) && isset($sclV->to)) {
                                                                                        if ((age($recordV[$childrenV['required'][0]]) >= $sclV->from) &&
                                                                                            (age($recordV[$childrenV['required'][0]]) <= $sclV->to)) {
                                                                                            
                                                                                            $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                                                ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                                                ->where('payroll_vertical_scale_id', $scaleV['id'])->first();
                                                                                            if ($payrollSalaryTabulator->percentage) {
                                                                                                $formula = str_replace(
                                                                                                    $match,
                                                                                                    $tabScale['value']/100,
                                                                                                    $formula ?? $concept['formula']
                                                                                                );
                                                                                            } else {
                                                                                                $formula = str_replace(
                                                                                                    $match,
                                                                                                    $tabScale['value'],
                                                                                                    $formula ?? $concept['formula']
                                                                                                );
                                                                                            }
                                                                                        }
                                                                                    } else {
                                                                                        if ($sclV == age($recordV[$childrenV['required'][0]])) {
                                                                                            
                                                                                            $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                                                ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                                                ->where('payroll_vertical_scale_id', $scaleV['id'])->first();
                                                                                            if ($payrollSalaryTabulator->percentage) {
                                                                                                $formula = str_replace(
                                                                                                    $match,
                                                                                                    $tabScale['value']/100,
                                                                                                    $formula ?? $concept['formula']
                                                                                                );
                                                                                            } else {
                                                                                                $formula = str_replace(
                                                                                                    $match,
                                                                                                    $tabScale['value'],
                                                                                                    $formula ?? $concept['formula']
                                                                                                );
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                } else {
                                                                                    /** Se identifica el valor según el expediente del trabajador
                                                                                     * y se sustituye por su valor en el tabulador */
                                                                                    if (json_decode($scaleV['value']) == $recordV[$childrenV['required'][0]]) {
                                                                                        
                                                                                        $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                                            ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                                            ->where('payroll_vertical_scale_id', $scaleV['id'])->first();

                                                                                        if ($payrollSalaryTabulator->percentage) {
                                                                                            $formula = str_replace(
                                                                                                $match,
                                                                                                $tabScale['value']/100,
                                                                                                $formula ?? $concept['formula']
                                                                                            );
                                                                                        } else {
                                                                                            $formula = str_replace(
                                                                                                $match,
                                                                                                $tabScale['value'],
                                                                                                $formula ?? $concept['formula']
                                                                                            );
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                        } else {
                                                                            $formula = str_replace(
                                                                                $match,
                                                                                0,
                                                                                $formula ?? $concept['formula']
                                                                            );
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        } else {
                                            $formula = str_replace(
                                                $match,
                                                0,
                                                $formula ?? $concept['formula']
                                            );
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                /** Si no se encuentra resultado se envian a cero los tabuladores */
                $matchs = [];
                preg_match_all("/tabulator\([0-9]+\)/", $formula, $matchs);
                foreach ($matchs[0] as $match) {
                    $formula = str_replace(
                        $match,
                        0,
                        $formula ?? $concept['formula']
                    );
                }
                /** Fin de la busqueda */
                $exploded = multiexplode(
                    [
                        'if', '(', ')', '{', '}', ' ',
                        '==', '<=', '>=', '<', '>', '!=',
                        '+', '-','*','/'
                    ],
                    $formula
                );
                while (count($exploded) > 0) {
                    $complete = false;
                    $current = max_length($exploded);
                    $key = array_search($current, $exploded);
                    /** Se descartan los elementos vacios y las constantes númericas */
                    if ($current == '' || is_numeric($current)) {
                        unset($exploded[$key]);
                        $complete = true;
                    } else {
                        /** Se recorre el listado de parámetros asociados a la configuración de vacaciones
                          * para sustituirlos por su valor real en la formula del concepto */
                        foreach ($payrollParameters->loadData('associatedVacation') as $parameter) {
                            if ($parameter['id'] == $current) {
                                $record = (is_object($parameter['model']))
                                    ? $parameter['model']
                                    : $parameter['model']::where('institution_id', $institution->id)->first();
                                unset($exploded[$key]);
                                $complete = true;
                                if (isset($record)) {
                                    $formula = str_replace(
                                        $parameter['id'],
                                        $record[$parameter['required'][0]],
                                        $formula ?? $concept['formula']
                                    );
                                } else {
                                    $formula = str_replace(
                                        $parameter['id'],
                                        0,
                                        $formula ?? $concept['formula']
                                    );
                                }
                            }
                        }
                        /** Se recorre el listado de parámetros asociados a la configuración de prestaciones sociales
                          * para sustituirlos por su valor real en la formula del concepto */
                        foreach ($payrollParameters->loadData('associatedBenefit') as $parameter) {
                            if ($parameter['id'] == $current) {
                                $record = (is_object($parameter['model']))
                                    ? $parameter['model']
                                    : $parameter['model']::where('institution_id', $institution->id)->first();
                                unset($exploded[$key]);
                                $complete = true;
                                if (isset($record)) {
                                    $formula = str_replace(
                                        $parameter['id'],
                                        $record[$parameter['required'][0]],
                                        $formula ?? $concept['formula']
                                    );
                                } else {
                                    $formula = str_replace(
                                        $parameter['id'],
                                        0,
                                        $formula ?? $concept['formula']
                                    );
                                }
                            }
                        }
                        /** Se recorre el listado de parámetros asociados al expediente del trabajador
                          * para sustituirlos por su valor real en la formula del concepto */
                        if ($complete == false) {
                            foreach ($payrollParameters->loadData('associatedWorkerFile') as $parameter) {
                                if (!empty($parameter['children'])) {
                                    foreach ($parameter['children'] as $children) {
                                        if ($children['id'] == $current) {
                                            $record = ($parameter['model'] != PayrollStaff::class)
                                                ? $parameter['model']::where('payroll_staff_id', $payrollStaff->id)->first()
                                                : $payrollStaff;
                                            unset($exploded[$key]);
                                            $complete = true;
                                            if ($children['type'] == 'number') {
                                                /** Se calcula el número de registros existentes según sea el caso
                                                 * y se sustituye por su valor real en la fórmula del concepto */
                                                if (isset($record)) {
                                                    $record->loadCount($children['required'][0]);
                                                    $formula = str_replace(
                                                        $children['id'],
                                                        $record[Str::snake($children['required'][0]) . '_count'],
                                                        $formula ?? $concept['formula']
                                                    );
                                                } else {
                                                    $formula = str_replace(
                                                        $children['id'],
                                                        0,
                                                        $formula ?? $concept['formula']
                                                    );
                                                }
                                            } elseif ($children['type'] == 'date') {
                                                /** Se calcula el número de años según la fecha de ingreso
                                                 * y se sustituye por su valor real en la fórmula del concepto */

                                                if (isset($record)) {
                                                    $formula = str_replace(
                                                        $children['id'],
                                                        age($record[$children['required'][0]]),
                                                        $formula ?? $concept['formula']
                                                    );
                                                } else {
                                                    $formula = str_replace(
                                                        $children['id'],
                                                        0,
                                                        $formula ?? $concept['formula']
                                                    );
                                                }
                                            } else {
                                                /** Se identifica el valor según el expediente del trabajador
                                                 * y se sustituye por su valor real en la fórmula del concepto */
                                                if (isset($record)) {
                                                    $formula = str_replace(
                                                        $children['id'],
                                                        $record[$children['required'][0]],
                                                        $formula ?? $concept['formula']
                                                    );
                                                } else {
                                                    $formula = str_replace(
                                                        $children['id'],
                                                        0,
                                                        $formula ?? $concept['formula']
                                                    );
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                /** Se carga la propiedad payrollConceptType
                 *  para determinar si clasificar el concepto como asignación o deducción */
                $concept['field']->load('payrollConceptType');
                if ($concept['field']->payrollConceptType['sign'] == '+') {
                    array_push(
                        $assignments,
                        [
                            'name'  => $concept['field']->name,
                            'value' => $formula ? str_eval($formula): str_eval($concept['formula'])
                        ]
                    );
                } elseif ($concept['field']->payrollConceptType['sign'] == '-') {
                    array_push(
                        $deductions,
                        [
                            'name'  => $concept['field']->name,
                            'value' => $formula ? str_eval($formula): str_eval($concept['formula'])
                        ]
                    );
                }
            }
            PayrollStaffPayroll::updateOrCreate(
                [
                    'payroll_id'       => $payroll->id,
                    'payroll_staff_id' => $payrollStaff->id
                ],
                [
                    'assignments' => json_encode($assignments),
                    'deductions'  => json_encode($deductions)
                ]
            );
        }
        //PayrollCreatePaymentRelationship::dispatch($request->all());
        $request->session()->flash('message', ['type' => 'store']);
        return response()->json(['result' => true, 'redirect' => route('payroll.registers.index')], 200);
    }

    /**
     * Muestra el formulario con la información de una nómina de sueldos registrada
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     *
     * @param     Integer                          $id    Identificador único del registro de nómina
     *
     * @return    \Illuminate\Http\JsonResponse           Objeto con los registros a mostrar
     */
    public function show($id)
    {
        /**
         * Objeto asociado al modelo Payroll
         * @var Object $payroll
         */
        $payroll = Payroll::find($id);
        return view('payroll::registers.show', compact('payroll'));
    }

    /**
     * Muestra el formulario para actualizar la información de una nómina de sueldos
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     *
     * @param     Integer                  $id    Identificador único del registro de nómina
     *
     * @return    Renderable
     */
    public function edit($id)
    {
        /**
         * Objeto asociado al modelo Payroll
         * @var Object $payroll
         */
        $payroll = Payroll::find($id);
        return view('payroll::registers.create-edit', compact('payroll'));
    }

    /**
     * Actualiza la información de una nómina de sueldos
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     *
     * @param     \Illuminate\Http\Request         $request    Datos de la petición
     * @param     Integer                          $id         Identificador único del registro de nómina
     *
     * @return    \Illuminate\Http\JsonResponse                Objeto con los registros a mostrar
     */
    public function update(Request $request, $id)
    {
        ini_set('max_execution_time', 600); 
        $this->validate($request, $this->validateRules, $this->messages);
        $created_at = now();
        $payrollParameters = new PayrollAssociatedParametersRepository;

        /**
         * Objeto asociado al modelo Payroll
         * @var    Object    $payroll
         */
        $payroll = Payroll::updateOrCreate(
            [
                'id'                        => $id
            ],
            [
                'name'                      => $request['name'],
                'payroll_payment_period_id' => $request['payroll_payment_period_id'],
                'payroll_parameters'        => json_encode($request['payroll_parameters']),
                'created_at'                => $request['created_at'] ?? $created_at
            ]
        );

        /** Se recorren los conceptos establecidos para la generación de la nómina */
        $concepts = [];
        foreach ($request['payroll_concepts'] as $concept) {
            $formula = null;
            $payrollConcept = PayrollConcept::find($concept['id']);
            $formula = $this->translateFormConcept($payrollConcept->formula);
            $exploded = multiexplode(
                [
                    'if', '(', ')', '{', '}', ' ',
                    '==', '<=', '>=', '<', '>', '!=',
                    '+', '-','*','/'
                ],
                $formula
            );
            while (count($exploded) > 0) {
                $complete = false;
                $current = max_length($exploded);
                $key = array_search($current, $exploded);
                /** Se descartan los elementos vacios y las constantes númericas */
                if ($current == '' || is_numeric($current)) {
                    unset($exploded[$key]);
                    $complete = true;
                } else {
                    /** Se recorre el listado de parámetros para sustituirlos por su valor real
                      * en la formula del concepto */
                    foreach ($request['payroll_parameters'] as $parameter) {
                        if ($parameter['name'] == $current) {
                            unset($exploded[$key]);
                            $complete = true;
                            $formula = str_replace($parameter['name'], $parameter['value'], $formula);
                        }
                    }
                    if ($complete == false) {
                        /** Se descartan los parametro de vacaciones y los del expediente del trabajador
                         * para ser analizados mas adelante */
                        unset($exploded[$key]);
                        $complete = true;
                    }
                }
            }
            array_push($concepts, ['field' => $payrollConcept, 'formula' => $formula]);
        }
        /** Se evaluan los parámetros del expediente del trabajador y de la configuración de vacaciones */
        /** Se identifica la institución en la que se está operando */
        $institution = Institution::where('active', true)->where('default', true)->first();
        /* Revisar (No funciona en segundo plano --- Alternativa solicitar institucion desde formulario)
        $profileUser = Auth()->user()->profile;
        if ($profileUser) {
            $institution = Institution::find($profileUser->institution_id);
        } else {
            $institution = Institution::where('active', true)->where('default', true)->first();
        }
        */
        /** Se obtienen todos los trabajadores asociados a la institución y se evalua si aplica cada uno de los conceptos */
        $payrollStaffs = PayrollStaff::whereHas('payrollEmployment', function ($q) use ($institution) {
            $q->whereHas('department', function ($qq) use ($institution) {
                $qq->where('institution_id', $institution->id);
            });
        })->get();

        foreach ($payrollStaffs as $payrollStaff) {
            /** Se definen los arreglos de asignaciones y deducciones para clasificar los conceptos */
            $assignments = [];
            $deductions  = [];
            foreach ($concepts as $concept) {
                $formula = $concept['formula'];
                /** Se hace la busqueda de los tabuladores */
                $matchs = [];
                preg_match_all("/tabulator\([0-9]+\)/", $formula, $matchs);
                foreach ($matchs[0] as $match) {
                    $id = substr($match, (strpos($match, "(") +1), strpos($match, ")")-(strpos($match, "(")+1));
                    $payrollSalaryTabulator = PayrollSalaryTabulator::find($id);

                    if ($payrollSalaryTabulator->payroll_salary_tabulator_type == 'horizontal') {
                        /** Se carga el escalafón horizontal asociado al tabulador */
                        $payrollSalaryTabulator->load(['payrollHorizontalSalaryScale' => function($q) {
                            $q->with('payrollScales');
                        }]);
                        foreach ($payrollParameters->loadData('associatedWorkerFile') as $parameter) {
                            if (!empty($parameter['children'])) {
                                foreach ($parameter['children'] as $children) {
                                    if ($children['id'] == $payrollSalaryTabulator->payrollHorizontalSalaryScale['group_by']) {
                                        $record = ($parameter['model'] != PayrollStaff::class)
                                            ? $parameter['model']::where('payroll_staff_id', $payrollStaff->id)->first()
                                            : $payrollStaff;
                                        if (isset($record)) {
                                            foreach ($payrollSalaryTabulator->payrollHorizontalSalaryScale->payrollScales as $scale) {
                                                if ($children['type'] == 'number') {
                                                    /** Se calcula el número de registros existentes según sea el caso
                                                     * y se sustituye por su valor en el tabulador */
                                                    $scl = json_decode($scale['value']);
                                                    $record->loadCount($children['required'][0]);

                                                    if (isset($scl->from) && isset($scl->to)) {
                                                        if (($record[Str::snake($children['required'][0]) . '_count'] >= $scl->from) &&
                                                            ($record[Str::snake($children['required'][0]) . '_count'] <= $scl->to)) {
                                                            $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                ->where('payroll_vertical_scale_id', null)->first();
                                                            if ($payrollSalaryTabulator->percentage) {
                                                                $formula = str_replace(
                                                                    $match,
                                                                    $tabScale['value']/100,
                                                                    $formula ?? $concept['formula']
                                                                );
                                                            } else {
                                                                $formula = str_replace(
                                                                    $match,
                                                                    $tabScale['value'],
                                                                    $formula ?? $concept['formula']
                                                                );
                                                            }
                                                        }
                                                    } else {
                                                        if ($scl == $record[Str::snake($children['required'][0]) . '_count']) {
                                                            $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                ->where('payroll_vertical_scale_id', null)->first();
                                                            if ($payrollSalaryTabulator->percentage) {
                                                                $formula = str_replace(
                                                                    $match,
                                                                    $tabScale['value']/100,
                                                                    $formula ?? $concept['formula']
                                                                );
                                                            } else {
                                                                $formula = str_replace(
                                                                    $match,
                                                                    $tabScale['value'],
                                                                    $formula ?? $concept['formula']
                                                                );
                                                            }
                                                        }
                                                    }
                                                } elseif ($children['type'] == 'date') {
                                                    /** Se calcula el número de años según la fecha de ingreso
                                                      * y se sustituye por su valor en el tabulador */
                                                    $scl = json_decode($scale['value']);
                                                    if (isset($scl->from) && isset($scl->to)) {
                                                        if ((age($record[$children['required'][0]]) >= $scl->from) &&
                                                            (age($record[$children['required'][0]]) <= $scl->to)) {
                                                            $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                ->where('payroll_vertical_scale_id', null)->first();
                                                            if ($payrollSalaryTabulator->percentage) {
                                                                $formula = str_replace(
                                                                    $match,
                                                                    $tabScale['value']/100,
                                                                    $formula ?? $concept['formula']
                                                                );
                                                            } else {
                                                                $formula = str_replace(
                                                                    $match,
                                                                    $tabScale['value'],
                                                                    $formula ?? $concept['formula']
                                                                );
                                                            }
                                                        }
                                                    } else {
                                                        if ($scl == age($record[$children['required'][0]])) {
                                                            $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                ->where('payroll_vertical_scale_id', null)->first();
                                                            if ($payrollSalaryTabulator->percentage) {
                                                                $formula = str_replace(
                                                                    $match,
                                                                    $tabScale['value']/100,
                                                                    $formula ?? $concept['formula']
                                                                );
                                                            } else {
                                                                $formula = str_replace(
                                                                    $match,
                                                                    $tabScale['value'],
                                                                    $formula ?? $concept['formula']
                                                                );
                                                            }
                                                        }
                                                    }
                                                } else {
                                                    /** Se identifica el valor según el expediente del trabajador
                                                     * y se sustituye por su valor en el tabulador */
                                                    if (json_decode($scale['value']) == $record[$children['required'][0]]) {
                                                        $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                            ->where('payroll_horizontal_scale_id', $scale['id'])
                                                            ->where('payroll_vertical_scale_id', null)->first();

                                                        if ($payrollSalaryTabulator->percentage) {
                                                            $formula = str_replace(
                                                                $match,
                                                                $tabScale['value']/100,
                                                                $formula ?? $concept['formula']
                                                            );
                                                        } else {
                                                            $formula = str_replace(
                                                                $match,
                                                                $tabScale['value'],
                                                                $formula ?? $concept['formula']
                                                            );
                                                        }
                                                    }
                                                }
                                            }
                                        } else {
                                            $formula = str_replace(
                                                $match,
                                                0,
                                                $formula ?? $concept['formula']
                                            );
                                        }
                                    }
                                }
                            }
                        }
                    } elseif ($payrollSalaryTabulator->payroll_salary_tabulator_type == 'vertical') {
                        /** Se carga el escalafón vertical asociado al tabulador */
                        $payrollSalaryTabulator->load(['payrollVerticalSalaryScale' => function($q) {
                            $q->with('payrollScales');
                        }]);
                        foreach ($payrollParameters->loadData('associatedWorkerFile') as $parameter) {
                            if (!empty($parameter['children'])) {
                                foreach ($parameter['children'] as $children) {
                                    if ($children['id'] == $payrollSalaryTabulator->payrollVerticalSalaryScale['group_by']) {
                                        $record = ($parameter['model'] != PayrollStaff::class)
                                            ? $parameter['model']::where('payroll_staff_id', $payrollStaff->id)->first()
                                            : $payrollStaff;
                                        if (isset($record)) {
                                            foreach ($payrollSalaryTabulator->payrollVerticalSalaryScale->payrollScales as $scale) {
                                                if ($children['type'] == 'number') {
                                                    /** Se calcula el número de registros existentes según sea el caso
                                                     * y se sustituye por su valor en el tabulador */
                                                    $scl = json_decode($scale['value']);
                                                    $record->loadCount($children['required'][0]);

                                                    if (isset($scl->from) && isset($scl->to)) {
                                                        if (($record[Str::snake($children['required'][0]) . '_count'] >= $scl->from) &&
                                                            ($record[Str::snake($children['required'][0]) . '_count'] <= $scl->to)) {
                                                            $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                ->where('payroll_horizontal_scale_id', null)
                                                                ->where('payroll_vertical_scale_id', $scale['id'])->first();
                                                            if ($payrollSalaryTabulator->percentage) {
                                                                $formula = str_replace(
                                                                    $match,
                                                                    $tabScale['value']/100,
                                                                    $formula ?? $concept['formula']
                                                                );
                                                            } else {
                                                                $formula = str_replace(
                                                                    $match,
                                                                    $tabScale['value'],
                                                                    $formula ?? $concept['formula']
                                                                );
                                                            }
                                                        }
                                                    } else {
                                                        if ($scl == $record[Str::snake($children['required'][0]) . '_count']) {
                                                            $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                ->where('payroll_horizontal_scale_id', null)
                                                                ->where('payroll_vertical_scale_id', $scale['id'])->first();
                                                            if ($payrollSalaryTabulator->percentage) {
                                                                $formula = str_replace(
                                                                    $match,
                                                                    $tabScale['value']/100,
                                                                    $formula ?? $concept['formula']
                                                                );
                                                            } else {
                                                                $formula = str_replace(
                                                                    $match,
                                                                    $tabScale['value'],
                                                                    $formula ?? $concept['formula']
                                                                );
                                                            }
                                                        }
                                                    }
                                                } elseif ($children['type'] == 'date') {
                                                    /** Se calcula el número de años según la fecha de ingreso
                                                      * y se sustituye por su valor en el tabulador */
                                                    $scl = json_decode($scale['value']);
                                                    if (isset($scl->from) && isset($scl->to)) {
                                                        if ((age($record[$children['required'][0]]) >= $scl->from) &&
                                                            (age($record[$children['required'][0]]) <= $scl->to)) {
                                                            $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                ->where('payroll_horizontal_scale_id', null)
                                                                ->where('payroll_vertical_scale_id', $scale['id'])->first();
                                                            if ($payrollSalaryTabulator->percentage) {
                                                                $formula = str_replace(
                                                                    $match,
                                                                    $tabScale['value']/100,
                                                                    $formula ?? $concept['formula']
                                                                );
                                                            } else {
                                                                $formula = str_replace(
                                                                    $match,
                                                                    $tabScale['value'],
                                                                    $formula ?? $concept['formula']
                                                                );
                                                            }
                                                        }
                                                    } else {
                                                        if ($scl == age($record[$children['required'][0]])) {
                                                            $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                ->where('payroll_horizontal_scale_id', null)
                                                                ->where('payroll_vertical_scale_id', $scale['id'])->first();
                                                            if ($payrollSalaryTabulator->percentage) {
                                                                $formula = str_replace(
                                                                    $match,
                                                                    $tabScale['value']/100,
                                                                    $formula ?? $concept['formula']
                                                                );
                                                            } else {
                                                                $formula = str_replace(
                                                                    $match,
                                                                    $tabScale['value'],
                                                                    $formula ?? $concept['formula']
                                                                );
                                                            }
                                                        }
                                                    }
                                                } else {
                                                    /** Se identifica el valor según el expediente del trabajador
                                                     * y se sustituye por su valor en el tabulador */
                                                    if (json_decode($scale['value']) == $record[$children['required'][0]]) {
                                                        $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                            ->where('payroll_horizontal_scale_id', null)
                                                            ->where('payroll_vertical_scale_id', $scale['id'])->first();

                                                        if ($payrollSalaryTabulator->percentage) {
                                                            $formula = str_replace(
                                                                $match,
                                                                $tabScale['value']/100,
                                                                $formula ?? $concept['formula']
                                                            );
                                                        } else {
                                                            $formula = str_replace(
                                                                $match,
                                                                $tabScale['value'],
                                                                $formula ?? $concept['formula']
                                                            );
                                                        }
                                                    }
                                                }
                                            }
                                        } else {
                                            $formula = str_replace(
                                                $match,
                                                0,
                                                $formula ?? $concept['formula']
                                            );
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        /** Se cargan los escalafones horizontal y vertical asociados al tabulador */
                        $payrollSalaryTabulator->load([
                            'payrollHorizontalSalaryScale' => function ($q) {
                                $q->with('payrollScales');
                            }, 'payrollVerticalSalaryScale' => function($q) {
                                $q->with('payrollScales');
                            }
                        ]);
                        foreach ($payrollParameters->loadData('associatedWorkerFile') as $parameter) {
                            if (!empty($parameter['children'])) {
                                foreach ($parameter['children'] as $children) {
                                    if ($children['id'] == $payrollSalaryTabulator->payrollHorizontalSalaryScale['group_by']) {
                                        $record = ($parameter['model'] != PayrollStaff::class)
                                            ? $parameter['model']::where('payroll_staff_id', $payrollStaff->id)->first()
                                            : $payrollStaff;
                                        if (isset($record)) {
                                            foreach ($payrollSalaryTabulator->payrollHorizontalSalaryScale->payrollScales as $scale) {
                                                if ($children['type'] == 'number') {
                                                    /** Se calcula el número de registros existentes según sea el caso
                                                     * y se sustituye por su valor en el tabulador */
                                                    $scl = json_decode($scale['value']);
                                                    $record->loadCount($children['required'][0]);

                                                    if (isset($scl->from) && isset($scl->to)) {
                                                        if (($record[Str::snake($children['required'][0]) . '_count'] >= $scl->from) &&
                                                            ($record[Str::snake($children['required'][0]) . '_count'] <= $scl->to)) {

                                                            foreach ($payrollParameters->loadData('associatedWorkerFile') as $parameterV) {
                                                                if (!empty($parameterV['children'])) {
                                                                    foreach ($parameterV['children'] as $childrenV) {
                                                                        if ($childrenV['id'] == $payrollSalaryTabulator->payrollVerticalSalaryScale['group_by']) {
                                                                            $recordV = ($parameterV['model'] != PayrollStaff::class)
                                                                                ? $parameterV['model']::where('payroll_staff_id', $payrollStaff->id)->first()
                                                                                : $payrollStaff;
                                                                            if (isset($recordV)) {
                                                                                foreach ($payrollSalaryTabulator->payrollVerticalSalaryScale->payrollScales as $scaleV) {
                                                                                    if ($childrenV['type'] == 'number') {
                                                                                        /** Se calcula el número de registros existentes según sea el caso
                                                                                         * y se sustituye por su valor en el tabulador */
                                                                                        $sclV = json_decode($scaleV['value']);
                                                                                        $recordV->loadCount($childrenV['required'][0]);

                                                                                        if (isset($sclV->from) && isset($sclV->to)) {
                                                                                            if (($recordV[Str::snake($childrenV['required'][0]) . '_count'] >= $sclV->from) &&
                                                                                                ($recordV[Str::snake($childrenV['required'][0]) . '_count'] <= $sclV->to)) {
                                                                                                
                                                                                                $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                                                    ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                                                    ->where('payroll_vertical_scale_id', $scaleV['id'])->first();
                                                                                                if ($payrollSalaryTabulator->percentage) {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value']/100,
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                } else {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value'],
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                }
                                                                                            }
                                                                                        } else {
                                                                                            if ($sclV == $recordV[Str::snake($childrenV['required'][0]) . '_count']) {
                                                                                                $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                                                    ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                                                    ->where('payroll_vertical_scale_id', $scaleV['id'])->first();
                                                                                                if ($payrollSalaryTabulator->percentage) {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value']/100,
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                } else {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value'],
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    } elseif ($childrenV['type'] == 'date') {
                                                                                        /** Se calcula el número de años según la fecha de ingreso
                                                                                          * y se sustituye por su valor en el tabulador */
                                                                                        $sclV = json_decode($scaleV['value']);
                                                                                        if (isset($sclV->from) && isset($sclV->to)) {
                                                                                            if ((age($recordV[$childrenV['required'][0]]) >= $sclV->from) &&
                                                                                                (age($recordV[$childrenV['required'][0]]) <= $sclV->to)) {
                                                                                                
                                                                                                $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                                                    ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                                                    ->where('payroll_vertical_scale_id', $scaleV['id'])->first();
                                                                                                if ($payrollSalaryTabulator->percentage) {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value']/100,
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                } else {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value'],
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                }
                                                                                            }
                                                                                        } else {
                                                                                            if ($sclV == age($recordV[$childrenV['required'][0]])) {
                                                                                                
                                                                                                $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                                                    ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                                                    ->where('payroll_vertical_scale_id', $scaleV['id'])->first();
                                                                                                if ($payrollSalaryTabulator->percentage) {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value']/100,
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                } else {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value'],
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    } else {
                                                                                        /** Se identifica el valor según el expediente del trabajador
                                                                                         * y se sustituye por su valor en el tabulador */
                                                                                        if (json_decode($scaleV['value']) == $recordV[$childrenV['required'][0]]) {
                                                                                            
                                                                                            $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                                                ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                                                ->where('payroll_vertical_scale_id', $scaleV['id'])->first();

                                                                                            if ($payrollSalaryTabulator->percentage) {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value']/100,
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                } else {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value'],
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                }
                                                                                        }
                                                                                    }
                                                                                }
                                                                            } else {
                                                                                $formula = str_replace(
                                                                                    $match,
                                                                                    0,
                                                                                    $formula ?? $concept['formula']
                                                                                );
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    } else {
                                                        if ($scl == $record[Str::snake($children['required'][0]) . '_count']) {
                                                            foreach ($payrollParameters->loadData('associatedWorkerFile') as $parameterV) {
                                                                if (!empty($parameterV['children'])) {
                                                                    foreach ($parameterV['children'] as $childrenV) {
                                                                        if ($childrenV['id'] == $payrollSalaryTabulator->payrollVerticalSalaryScale['group_by']) {
                                                                            $recordV = ($parameterV['model'] != PayrollStaff::class)
                                                                                ? $parameterV['model']::where('payroll_staff_id', $payrollStaff->id)->first()
                                                                                : $payrollStaff;
                                                                            if (isset($recordV)) {
                                                                                foreach ($payrollSalaryTabulator->payrollVerticalSalaryScale->payrollScales as $scaleV) {
                                                                                    if ($childrenV['type'] == 'number') {
                                                                                        /** Se calcula el número de registros existentes según sea el caso
                                                                                         * y se sustituye por su valor en el tabulador */
                                                                                        $sclV = json_decode($scaleV['value']);
                                                                                        $recordV->loadCount($childrenV['required'][0]);

                                                                                        if (isset($sclV->from) && isset($sclV->to)) {
                                                                                            if (($recordV[Str::snake($childrenV['required'][0]) . '_count'] >= $sclV->from) &&
                                                                                                ($recordV[Str::snake($childrenV['required'][0]) . '_count'] <= $sclV->to)) {
                                                                                                
                                                                                                $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                                                    ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                                                    ->where('payroll_vertical_scale_id', $scaleV['id'])->first();
                                                                                                if ($payrollSalaryTabulator->percentage) {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value']/100,
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                } else {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value'],
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                }
                                                                                            }
                                                                                        } else {
                                                                                            if ($sclV == $recordV[Str::snake($childrenV['required'][0]) . '_count']) {
                                                                                                $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                                                    ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                                                    ->where('payroll_vertical_scale_id', $scaleV['id'])->first();
                                                                                                if ($payrollSalaryTabulator->percentage) {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value']/100,
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                } else {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value'],
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    } elseif ($childrenV['type'] == 'date') {
                                                                                        /** Se calcula el número de años según la fecha de ingreso
                                                                                          * y se sustituye por su valor en el tabulador */
                                                                                        $sclV = json_decode($scaleV['value']);
                                                                                        if (isset($sclV->from) && isset($sclV->to)) {
                                                                                            if ((age($recordV[$childrenV['required'][0]]) >= $sclV->from) &&
                                                                                                (age($recordV[$childrenV['required'][0]]) <= $sclV->to)) {
                                                                                                
                                                                                                $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                                                    ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                                                    ->where('payroll_vertical_scale_id', $scaleV['id'])->first();
                                                                                                if ($payrollSalaryTabulator->percentage) {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value']/100,
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                } else {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value'],
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                }
                                                                                            }
                                                                                        } else {
                                                                                            if ($sclV == age($recordV[$childrenV['required'][0]])) {
                                                                                                
                                                                                                $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                                                    ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                                                    ->where('payroll_vertical_scale_id', $scaleV['id'])->first();
                                                                                                if ($payrollSalaryTabulator->percentage) {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value']/100,
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                } else {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value'],
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    } else {
                                                                                        /** Se identifica el valor según el expediente del trabajador
                                                                                         * y se sustituye por su valor en el tabulador */
                                                                                        if (json_decode($scaleV['value']) == $recordV[$childrenV['required'][0]]) {
                                                                                            
                                                                                            $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                                                ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                                                ->where('payroll_vertical_scale_id', $scaleV['id'])->first();

                                                                                            if ($payrollSalaryTabulator->percentage) {
                                                                                                $formula = str_replace(
                                                                                                    $match,
                                                                                                    $tabScale['value']/100,
                                                                                                    $formula ?? $concept['formula']
                                                                                                );
                                                                                            } else {
                                                                                                $formula = str_replace(
                                                                                                    $match,
                                                                                                    $tabScale['value'],
                                                                                                    $formula ?? $concept['formula']
                                                                                                );
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }
                                                                            } else {
                                                                                $formula = str_replace(
                                                                                    $match,
                                                                                    0,
                                                                                    $formula ?? $concept['formula']
                                                                                );
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                } elseif ($children['type'] == 'date') {
                                                    /** Se calcula el número de años según la fecha de ingreso
                                                      * y se sustituye por su valor en el tabulador */
                                                    $scl = json_decode($scale['value']);
                                                    if (isset($scl->from) && isset($scl->to)) {
                                                        if ((age($record[$children['required'][0]]) >= $scl->from) &&
                                                            (age($record[$children['required'][0]]) <= $scl->to)) {
                                                            foreach ($payrollParameters->loadData('associatedWorkerFile') as $parameterV) {
                                                                if (!empty($parameterV['children'])) {
                                                                    foreach ($parameterV['children'] as $childrenV) {
                                                                        if ($childrenV['id'] == $payrollSalaryTabulator->payrollVerticalSalaryScale['group_by']) {
                                                                            $recordV = ($parameterV['model'] != PayrollStaff::class)
                                                                                ? $parameterV['model']::where('payroll_staff_id', $payrollStaff->id)->first()
                                                                                : $payrollStaff;
                                                                            if (isset($recordV)) {
                                                                                foreach ($payrollSalaryTabulator->payrollVerticalSalaryScale->payrollScales as $scaleV) {
                                                                                    if ($childrenV['type'] == 'number') {
                                                                                        /** Se calcula el número de registros existentes según sea el caso
                                                                                         * y se sustituye por su valor en el tabulador */
                                                                                        $sclV = json_decode($scaleV['value']);
                                                                                        $recordV->loadCount($childrenV['required'][0]);

                                                                                        if (isset($sclV->from) && isset($sclV->to)) {
                                                                                            if (($recordV[Str::snake($childrenV['required'][0]) . '_count'] >= $sclV->from) &&
                                                                                                ($recordV[Str::snake($childrenV['required'][0]) . '_count'] <= $sclV->to)) {
                                                                                                
                                                                                                $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                                                    ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                                                    ->where('payroll_vertical_scale_id', $scaleV['id'])->first();
                                                                                                if ($payrollSalaryTabulator->percentage) {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value']/100,
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                } else {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value'],
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                }
                                                                                            }
                                                                                        } else {
                                                                                            if ($sclV == $recordV[Str::snake($childrenV['required'][0]) . '_count']) {
                                                                                                $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                                                    ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                                                    ->where('payroll_vertical_scale_id', $scaleV['id'])->first();
                                                                                                if ($payrollSalaryTabulator->percentage) {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value']/100,
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                } else {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value'],
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    } elseif ($childrenV['type'] == 'date') {
                                                                                        /** Se calcula el número de años según la fecha de ingreso
                                                                                          * y se sustituye por su valor en el tabulador */
                                                                                        $sclV = json_decode($scaleV['value']);
                                                                                        if (isset($sclV->from) && isset($sclV->to)) {
                                                                                            if ((age($recordV[$childrenV['required'][0]]) >= $sclV->from) &&
                                                                                                (age($recordV[$childrenV['required'][0]]) <= $sclV->to)) {
                                                                                                
                                                                                                $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                                                    ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                                                    ->where('payroll_vertical_scale_id', $scaleV['id'])->first();
                                                                                                if ($payrollSalaryTabulator->percentage) {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value']/100,
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                } else {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value'],
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                }
                                                                                            }
                                                                                        } else {
                                                                                            if ($sclV == age($recordV[$childrenV['required'][0]])) {
                                                                                                
                                                                                                $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                                                    ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                                                    ->where('payroll_vertical_scale_id', $scaleV['id'])->first();
                                                                                                if ($payrollSalaryTabulator->percentage) {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value']/100,
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                } else {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value'],
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    } else {
                                                                                        /** Se identifica el valor según el expediente del trabajador
                                                                                         * y se sustituye por su valor en el tabulador */
                                                                                        if (json_decode($scaleV['value']) == $recordV[$childrenV['required'][0]]) {
                                                                                            
                                                                                            $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                                                ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                                                ->where('payroll_vertical_scale_id', $scaleV['id'])->first();

                                                                                            if ($payrollSalaryTabulator->percentage) {
                                                                                                $formula = str_replace(
                                                                                                    $match,
                                                                                                    $tabScale['value']/100,
                                                                                                    $formula ?? $concept['formula']
                                                                                                );
                                                                                            } else {
                                                                                                $formula = str_replace(
                                                                                                    $match,
                                                                                                    $tabScale['value'],
                                                                                                    $formula ?? $concept['formula']
                                                                                                );
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }
                                                                            } else {
                                                                                $formula = str_replace(
                                                                                    $match,
                                                                                    0,
                                                                                    $formula ?? $concept['formula']
                                                                                );
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    } else {
                                                        if ($scl == age($record[$children['required'][0]])) {
                                                            foreach ($payrollParameters->loadData('associatedWorkerFile') as $parameterV) {
                                                                if (!empty($parameterV['children'])) {
                                                                    foreach ($parameterV['children'] as $childrenV) {
                                                                        if ($childrenV['id'] == $payrollSalaryTabulator->payrollVerticalSalaryScale['group_by']) {
                                                                            $recordV = ($parameterV['model'] != PayrollStaff::class)
                                                                                ? $parameterV['model']::where('payroll_staff_id', $payrollStaff->id)->first()
                                                                                : $payrollStaff;
                                                                            if (isset($recordV)) {
                                                                                foreach ($payrollSalaryTabulator->payrollVerticalSalaryScale->payrollScales as $scaleV) {
                                                                                    if ($childrenV['type'] == 'number') {
                                                                                        /** Se calcula el número de registros existentes según sea el caso
                                                                                         * y se sustituye por su valor en el tabulador */
                                                                                        $sclV = json_decode($scaleV['value']);
                                                                                        $recordV->loadCount($childrenV['required'][0]);

                                                                                        if (isset($sclV->from) && isset($sclV->to)) {
                                                                                            if (($recordV[Str::snake($childrenV['required'][0]) . '_count'] >= $sclV->from) &&
                                                                                                ($recordV[Str::snake($childrenV['required'][0]) . '_count'] <= $sclV->to)) {
                                                                                                
                                                                                                $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                                                    ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                                                    ->where('payroll_vertical_scale_id', $scaleV['id'])->first();
                                                                                                if ($payrollSalaryTabulator->percentage) {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value']/100,
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                } else {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value'],
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                }
                                                                                            }
                                                                                        } else {
                                                                                            if ($sclV == $recordV[Str::snake($childrenV['required'][0]) . '_count']) {
                                                                                                $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                                                    ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                                                    ->where('payroll_vertical_scale_id', $scaleV['id'])->first();
                                                                                                if ($payrollSalaryTabulator->percentage) {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value']/100,
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                } else {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value'],
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    } elseif ($childrenV['type'] == 'date') {
                                                                                        /** Se calcula el número de años según la fecha de ingreso
                                                                                          * y se sustituye por su valor en el tabulador */
                                                                                        $sclV = json_decode($scaleV['value']);
                                                                                        if (isset($sclV->from) && isset($sclV->to)) {
                                                                                            if ((age($recordV[$childrenV['required'][0]]) >= $sclV->from) &&
                                                                                                (age($recordV[$childrenV['required'][0]]) <= $sclV->to)) {
                                                                                                
                                                                                                $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                                                    ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                                                    ->where('payroll_vertical_scale_id', $scaleV['id'])->first();
                                                                                                if ($payrollSalaryTabulator->percentage) {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value']/100,
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                } else {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value'],
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                }
                                                                                            }
                                                                                        } else {
                                                                                            if ($sclV == age($recordV[$childrenV['required'][0]])) {
                                                                                                
                                                                                                $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                                                    ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                                                    ->where('payroll_vertical_scale_id', $scaleV['id'])->first();
                                                                                                if ($payrollSalaryTabulator->percentage) {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value']/100,
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                } else {
                                                                                                    $formula = str_replace(
                                                                                                        $match,
                                                                                                        $tabScale['value'],
                                                                                                        $formula ?? $concept['formula']
                                                                                                    );
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    } else {
                                                                                        /** Se identifica el valor según el expediente del trabajador
                                                                                         * y se sustituye por su valor en el tabulador */
                                                                                        if (json_decode($scaleV['value']) == $recordV[$childrenV['required'][0]]) {
                                                                                            
                                                                                            $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                                                ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                                                ->where('payroll_vertical_scale_id', $scaleV['id'])->first();

                                                                                            if ($payrollSalaryTabulator->percentage) {
                                                                                                $formula = str_replace(
                                                                                                    $match,
                                                                                                    $tabScale['value']/100,
                                                                                                    $formula ?? $concept['formula']
                                                                                                );
                                                                                            } else {
                                                                                                $formula = str_replace(
                                                                                                    $match,
                                                                                                    $tabScale['value'],
                                                                                                    $formula ?? $concept['formula']
                                                                                                );
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }
                                                                            } else {
                                                                                $formula = str_replace(
                                                                                    $match,
                                                                                    0,
                                                                                    $formula ?? $concept['formula']
                                                                                );
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                } else {
                                                    /** Se identifica el valor según el expediente del trabajador
                                                     * y se sustituye por su valor en el tabulador */
                                                    if (json_decode($scale['value']) == $record[$children['required'][0]]) {
                                                        foreach ($payrollParameters->loadData('associatedWorkerFile') as $parameterV) {
                                                            if (!empty($parameterV['children'])) {
                                                                foreach ($parameterV['children'] as $childrenV) {
                                                                    if ($childrenV['id'] == $payrollSalaryTabulator->payrollVerticalSalaryScale['group_by']) {
                                                                        $recordV = ($parameterV['model'] != PayrollStaff::class)
                                                                            ? $parameterV['model']::where('payroll_staff_id', $payrollStaff->id)->first()
                                                                            : $payrollStaff;
                                                                        if (isset($recordV)) {
                                                                            foreach ($payrollSalaryTabulator->payrollVerticalSalaryScale->payrollScales as $scaleV) {
                                                                                if ($childrenV['type'] == 'number') {
                                                                                    /** Se calcula el número de registros existentes según sea el caso
                                                                                     * y se sustituye por su valor en el tabulador */
                                                                                    $sclV = json_decode($scaleV['value']);
                                                                                    $recordV->loadCount($childrenV['required'][0]);

                                                                                    if (isset($sclV->from) && isset($sclV->to)) {
                                                                                        if (($recordV[Str::snake($childrenV['required'][0]) . '_count'] >= $sclV->from) &&
                                                                                            ($recordV[Str::snake($childrenV['required'][0]) . '_count'] <= $sclV->to)) {
                                                                                            
                                                                                            $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                                                ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                                                ->where('payroll_vertical_scale_id', $scaleV['id'])->first();
                                                                                            if ($payrollSalaryTabulator->percentage) {
                                                                                                $formula = str_replace(
                                                                                                    $match,
                                                                                                    $tabScale['value']/100,
                                                                                                    $formula ?? $concept['formula']
                                                                                                );
                                                                                            } else {
                                                                                                $formula = str_replace(
                                                                                                    $match,
                                                                                                    $tabScale['value'],
                                                                                                    $formula ?? $concept['formula']
                                                                                                );
                                                                                            }
                                                                                        }
                                                                                    } else {
                                                                                        if ($sclV == $recordV[Str::snake($childrenV['required'][0]) . '_count']) {
                                                                                            $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                                                ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                                                ->where('payroll_vertical_scale_id', $scaleV['id'])->first();
                                                                                            if ($payrollSalaryTabulator->percentage) {
                                                                                                $formula = str_replace(
                                                                                                    $match,
                                                                                                    $tabScale['value']/100,
                                                                                                    $formula ?? $concept['formula']
                                                                                                );
                                                                                            } else {
                                                                                                $formula = str_replace(
                                                                                                    $match,
                                                                                                    $tabScale['value'],
                                                                                                    $formula ?? $concept['formula']
                                                                                                );
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                } elseif ($childrenV['type'] == 'date') {
                                                                                    /** Se calcula el número de años según la fecha de ingreso
                                                                                      * y se sustituye por su valor en el tabulador */
                                                                                    $sclV = json_decode($scaleV['value']);
                                                                                    if (isset($sclV->from) && isset($sclV->to)) {
                                                                                        if ((age($recordV[$childrenV['required'][0]]) >= $sclV->from) &&
                                                                                            (age($recordV[$childrenV['required'][0]]) <= $sclV->to)) {
                                                                                            
                                                                                            $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                                                ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                                                ->where('payroll_vertical_scale_id', $scaleV['id'])->first();
                                                                                            if ($payrollSalaryTabulator->percentage) {
                                                                                                $formula = str_replace(
                                                                                                    $match,
                                                                                                    $tabScale['value']/100,
                                                                                                    $formula ?? $concept['formula']
                                                                                                );
                                                                                            } else {
                                                                                                $formula = str_replace(
                                                                                                    $match,
                                                                                                    $tabScale['value'],
                                                                                                    $formula ?? $concept['formula']
                                                                                                );
                                                                                            }
                                                                                        }
                                                                                    } else {
                                                                                        if ($sclV == age($recordV[$childrenV['required'][0]])) {
                                                                                            
                                                                                            $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                                                ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                                                ->where('payroll_vertical_scale_id', $scaleV['id'])->first();
                                                                                            if ($payrollSalaryTabulator->percentage) {
                                                                                                $formula = str_replace(
                                                                                                    $match,
                                                                                                    $tabScale['value']/100,
                                                                                                    $formula ?? $concept['formula']
                                                                                                );
                                                                                            } else {
                                                                                                $formula = str_replace(
                                                                                                    $match,
                                                                                                    $tabScale['value'],
                                                                                                    $formula ?? $concept['formula']
                                                                                                );
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                } else {
                                                                                    /** Se identifica el valor según el expediente del trabajador
                                                                                     * y se sustituye por su valor en el tabulador */
                                                                                    if (json_decode($scaleV['value']) == $recordV[$childrenV['required'][0]]) {
                                                                                        
                                                                                        $tabScale = PayrollSalaryTabulatorScale::where('payroll_salary_tabulator_id', $payrollSalaryTabulator->id)
                                                                                            ->where('payroll_horizontal_scale_id', $scale['id'])
                                                                                            ->where('payroll_vertical_scale_id', $scaleV['id'])->first();

                                                                                        if ($payrollSalaryTabulator->percentage) {
                                                                                            $formula = str_replace(
                                                                                                $match,
                                                                                                $tabScale['value']/100,
                                                                                                $formula ?? $concept['formula']
                                                                                            );
                                                                                        } else {
                                                                                            $formula = str_replace(
                                                                                                $match,
                                                                                                $tabScale['value'],
                                                                                                $formula ?? $concept['formula']
                                                                                            );
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                        } else {
                                                                            $formula = str_replace(
                                                                                $match,
                                                                                0,
                                                                                $formula ?? $concept['formula']
                                                                            );
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        } else {
                                            $formula = str_replace(
                                                $match,
                                                0,
                                                $formula ?? $concept['formula']
                                            );
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                /** Si no se encuentra resultado se envian a cero los tabuladores */
                $matchs = [];
                preg_match_all("/tabulator\([0-9]+\)/", $formula, $matchs);
                foreach ($matchs[0] as $match) {
                    $formula = str_replace(
                        $match,
                        0,
                        $formula ?? $concept['formula']
                    );
                }
                /** Fin de la busqueda */
                $exploded = multiexplode(
                    [
                        'if', '(', ')', '{', '}', ' ',
                        '==', '<=', '>=', '<', '>', '!=',
                        '+', '-','*','/'
                    ],
                    $formula
                );
                while (count($exploded) > 0) {
                    $complete = false;
                    $current = max_length($exploded);
                    $key = array_search($current, $exploded);
                    /** Se descartan los elementos vacios y las constantes númericas */
                    if ($current == '' || is_numeric($current)) {
                        unset($exploded[$key]);
                        $complete = true;
                    } else {
                        /** Se recorre el listado de parámetros asociados a la configuración de vacaciones
                          * para sustituirlos por su valor real en la formula del concepto */
                        foreach ($payrollParameters->loadData('associatedVacation') as $parameter) {
                            if ($parameter['id'] == $current) {
                                $record = (is_object($parameter['model']))
                                    ? $parameter['model']
                                    : $parameter['model']::where('institution_id', $institution->id)->first();
                                unset($exploded[$key]);
                                $complete = true;
                                if (isset($record)) {
                                    $formula = str_replace(
                                        $parameter['id'],
                                        $record[$parameter['required'][0]],
                                        $formula ?? $concept['formula']
                                    );
                                } else {
                                    $formula = str_replace(
                                        $parameter['id'],
                                        0,
                                        $formula ?? $concept['formula']
                                    );
                                }
                            }
                        }
                        /** Se recorre el listado de parámetros asociados a la configuración de prestaciones sociales
                          * para sustituirlos por su valor real en la formula del concepto */
                        foreach ($payrollParameters->loadData('associatedBenefit') as $parameter) {
                            if ($parameter['id'] == $current) {
                                $record = (is_object($parameter['model']))
                                    ? $parameter['model']
                                    : $parameter['model']::where('institution_id', $institution->id)->first();
                                unset($exploded[$key]);
                                $complete = true;
                                if (isset($record)) {
                                    $formula = str_replace(
                                        $parameter['id'],
                                        $record[$parameter['required'][0]],
                                        $formula ?? $concept['formula']
                                    );
                                } else {
                                    $formula = str_replace(
                                        $parameter['id'],
                                        0,
                                        $formula ?? $concept['formula']
                                    );
                                }
                            }
                        }
                        /** Se recorre el listado de parámetros asociados al expediente del trabajador
                          * para sustituirlos por su valor real en la formula del concepto */
                        if ($complete == false) {
                            foreach ($payrollParameters->loadData('associatedWorkerFile') as $parameter) {
                                if (!empty($parameter['children'])) {
                                    foreach ($parameter['children'] as $children) {
                                        if ($children['id'] == $current) {
                                            $record = ($parameter['model'] != PayrollStaff::class)
                                                ? $parameter['model']::where('payroll_staff_id', $payrollStaff->id)->first()
                                                : $payrollStaff;
                                            unset($exploded[$key]);
                                            $complete = true;
                                            if ($children['type'] == 'number') {
                                                /** Se calcula el número de registros existentes según sea el caso
                                                 * y se sustituye por su valor real en la fórmula del concepto */
                                                if (isset($record)) {
                                                    $record->loadCount($children['required'][0]);
                                                    $formula = str_replace(
                                                        $children['id'],
                                                        $record[Str::snake($children['required'][0]) . '_count'],
                                                        $formula ?? $concept['formula']
                                                    );
                                                } else {
                                                    $formula = str_replace(
                                                        $children['id'],
                                                        0,
                                                        $formula ?? $concept['formula']
                                                    );
                                                }
                                            } elseif ($children['type'] == 'date') {
                                                /** Se calcula el número de años según la fecha de ingreso
                                                 * y se sustituye por su valor real en la fórmula del concepto */

                                                if (isset($record)) {
                                                    $formula = str_replace(
                                                        $children['id'],
                                                        age($record[$children['required'][0]]),
                                                        $formula ?? $concept['formula']
                                                    );
                                                } else {
                                                    $formula = str_replace(
                                                        $children['id'],
                                                        0,
                                                        $formula ?? $concept['formula']
                                                    );
                                                }
                                            } else {
                                                /** Se identifica el valor según el expediente del trabajador
                                                 * y se sustituye por su valor real en la fórmula del concepto */
                                                if (isset($record)) {
                                                    $formula = str_replace(
                                                        $children['id'],
                                                        $record[$children['required'][0]],
                                                        $formula ?? $concept['formula']
                                                    );
                                                } else {
                                                    $formula = str_replace(
                                                        $children['id'],
                                                        0,
                                                        $formula ?? $concept['formula']
                                                    );
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                /** Se carga la propiedad payrollConceptType
                 *  para determinar si clasificar el concepto como asignación o deducción */
                $concept['field']->load('payrollConceptType');
                if ($concept['field']->payrollConceptType['sign'] == '+') {
                    array_push(
                        $assignments,
                        [
                            'name'  => $concept['field']->name,
                            'value' => $formula ? str_eval($formula): str_eval($concept['formula'])
                        ]
                    );
                } elseif ($concept['field']->payrollConceptType['sign'] == '-') {
                    array_push(
                        $deductions,
                        [
                            'name'  => $concept['field']->name,
                            'value' => $formula ? str_eval($formula): str_eval($concept['formula'])
                        ]
                    );
                }
            }
            PayrollStaffPayroll::updateOrCreate(
                [
                    'payroll_id'       => $payroll->id,
                    'payroll_staff_id' => $payrollStaff->id
                ],
                [
                    'assignments' => json_encode($assignments),
                    'deductions'  => json_encode($deductions)
                ]
            );
        }

        $request->session()->flash('message', ['type' => 'update']);
        return response()->json(['redirect' => route('payroll.registers.index')], 200);
    }

    /**
     * Elimina una nómina de sueldos
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     *
     * @param     Integer                          $id    Identificador único del registro de nómina
     *
     * @return    \Illuminate\Http\JsonResponse           Objeto con los registros a mostrar
     */
    public function destroy($id)
    {
        /**
         * Objeto asociado al modelo Payroll
         * @var Object $payroll
         */
        $payroll = Payroll::find($id);
        $payroll->delete();
        return response()->json(['message' => 'destroy'], 200);
    }

    /**
     * Obtiene la información de una nómina de sueldos registrada
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     *
     * @param     Integer                          $id    Identificador único del registro de nómina
     *
     * @return    \Illuminate\Http\JsonResponse           Objeto con los registros a mostrar
     */
    public function vueInfo($id)
    {
        /**
         * Objeto asociado al modelo Payroll
         * @var Object $payroll
         */
        $payroll = Payroll::with('payrollStaffPayrolls')->find($id);
        return response()->json(['record' => $payroll], 200);
    }

    /**
     * Obtiene un listado de los registros de nómina
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     *
     * @return    \Illuminate\Http\JsonResponse    Objeto con los registros a mostrar
     */
    public function vueList()
    {
        return response()->json(['records' => Payroll::with(['payrollPaymentPeriod'])->get()], 200);
    }

    /**
     * Actualiza el estado de una nómina de sueldos
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     *
     * @param     \Illuminate\Http\Request         $request    Datos de la petición
     * @param     Integer                          $id         Identificador único del registro de nómina
     *
     * @return    \Illuminate\Http\JsonResponse                Objeto con los registros a mostrar
     */
    public function close(Request $request, $id)
    {
        $payroll = Payroll::find($id);
        $payrollPaymentPeriod = $payroll->payrollPaymentPeriod;
        $payrollPaymentPeriod->payment_status = 'generated';
        $payrollPaymentPeriod->save();

        $request->session()->flash('message', ['type' => 'update']);
        return response()->json(['redirect' => route('payroll.registers.index')], 200);
    }

    public function translateFormConcept($form)
    {
        $formula = $form;
        /** Se hace la busqueda de los parámetros globales */
        $parameters = Parameter::where(
            [
                'required_by' => 'payroll',
                'active'      => true,
            ]
        )->where('p_key', 'like', 'global_parameter_%')->get();
        foreach ($parameters as $parameter) {
            $jsonValue = json_decode($parameter->p_value);
            $formula = str_replace('parameter(' . $jsonValue->id . ')', $jsonValue->name, $formula);
        }

        /** Se hace la busqueda de los conceptos */
        $matchs = [];
        preg_match_all("/concept\([0-9]+\)/", $formula, $matchs);
        foreach ($matchs[0] as $match) {
            $id = substr($match, (strpos($match, "(") +1), strpos($match, ")")-(strpos($match, "(")+1));
            $concept = PayrollConcept::find($id);
            $formula = str_replace('concept(' . $id . ')', $this->translateFormConcept($concept['formula']), $formula);
        }
        return $formula;
    }
}
