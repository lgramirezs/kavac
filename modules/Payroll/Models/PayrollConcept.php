<?php

namespace Modules\Payroll\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use App\Traits\ModelsTrait;
use Module;

use Modules\Payroll\Repositories\PayrollAssociatedParametersRepository;

/**
 * @class      PayrollConcept
 * @brief      Datos de conceptos
 *
 * Gestiona el modelo de conceptos
 *
 * @author     Henry Paredes <hparedes@cenditel.gob.ve>
 * @license
 *     [LICENCIA DE SOFTWARE CENDITEL](http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/)
 */
class PayrollConcept extends Model implements Auditable
{
    use SoftDeletes;
    use AuditableTrait;
    use ModelsTrait;

    /**
     * Lista de atributos para la gestión de fechas
     * @var array $dates
     */
    protected $dates = ['deleted_at'];

    /**
     * Lista de atributos que pueden ser asignados masivamente
     * @var array $fillable
     */
    protected $fillable = [
        'code', 'name', 'description', 'active', 'incidence_type', 'affect',
        'formula', 'institution_id', 'payroll_concept_type_id',
        'payroll_salary_tabulator_id', 'accounting_account_id', 'budget_account_id',
        'assign_to'
    ];

    protected $appends = ['translate_formula'];

    /**
     * Método que obtiene la información de la institución asociada al concepto
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     *
     * @return    \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    /**
     * Método que obtiene la información del tipo de concepto asociado al concepto
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     *
     * @return    \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function payrollConceptType()
    {
        return $this->belongsTo(PayrollConceptType::class);
    }

    /**
     * Método que obtiene la información del tabulador salarial asociado al concepto
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     *
     * @return    \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function payrollSalaryTabulator()
    {
        return $this->belongsTo(PayrollSalaryTabulator::class);
    }

    /**
     * Obtiene información de las opciones asignadas asociadas a un género
     *
     * @author    Juan Rosas <jrosas@cenditel.gob.ve> | <juan.rosasr01@gmail.com>
     *
     * @return    \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function payrollConceptAssignOptions()
    {
        return $this->morphMany(PayrollConceptAssignOption::class, 'applicable');
    }

    /**
     * Método que obtiene la información de la cuenta contable asociada al concepto
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     *
     * @return    \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function accountingAccount()
    {
        return (Module::has('Accounting'))
               ? $this->belongsTo(\Modules\Accounting\Models\AccountingAccount::class) : null;
    }

    /**
     * Método que obtiene la información de la cuenta presupuestaria asociada al concepto
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     *
     * @return    \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function budgetAccount()
    {
        return (Module::has('Budget'))
               ? $this->belongsTo(\Modules\Budget\Models\BudgetAccount::class) : null;
    }

    /**
     * Método que obtiene los tipos de pago de nómina asociados a muchos conceptos
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     *
     * @return    \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function payrollPaymentTypes()
    {
        return $this->belongsToMany(
            PayrollPaymentType::class,
            'payroll_concept_payment_type',
            'payroll_concept_id',
            'payroll_payment_type_id'
        );
    }

    /**
     * Método que obtiene el concepto asociado a muchos tipos de liquidación
     *
     * @author    William Páez <wpaez@cenditel.gob.ve> | <paez.william8@gmail.com>
     *
     * @return    \Illuminate\Database\Eloquent\Relations\HasMany
     */
    // public function payrollSettlementTypes()
    // {
    //     return $this->hasMany(PayrollSettlementType::class);
    // }

    public function getTranslateFormulaAttribute()
    {
        $parameters = new PayrollAssociatedParametersRepository;
        $formula = str_replace('if', 'Si', $this->formula);
        $typesParameters = ['associatedVacation', 'associatedWorkerFile', 'associatedBenefit', 'parameter', 'concept', 'tabulator'];

        foreach ($typesParameters as $typeParameter) {
            if (in_array($typeParameter, ['parameter', 'concept', 'tabulator'])) {
                if ($typeParameter == 'parameter') {
                    $types = Parameter::where(
                        [
                            'required_by' => 'payroll',
                            'active'      => true,
                        ]
                    )->where('p_key', 'like', 'global_parameter_%')->get();
                    foreach ($types as $type) {
                        $jsonValue = json_decode($type->p_value);
                        $formula = str_replace('parameter(' . $jsonValue->id . ')', $jsonValue->name, $formula);
                    }
                } elseif ($typeParameter == 'concept') {
                    $types = PayrollConcept::all();
                    foreach ($types as $type) {
                        $formula = str_replace('concept(' . $type['id'] . ')', $type['name'], $formula);
                    }
                } elseif ($typeParameter == 'tabulator') {
                    $types = PayrollSalaryTabulator::all();
                    foreach ($types as $type) {
                        $formula = str_replace('tabulator(' . $type['id'] . ')', $type['name'], $formula);
                    }
                }
            } else {
                $types = $parameters->loadData($typeParameter);
                foreach ($types as $type) {
                    if (empty($type['children'])) {
                        $formula = str_replace($type['id'], $type['name'], $formula);
                    } else {
                        foreach ($type['children'] as $children) {
                            $formula = str_replace($children['id'], $children['name'], $formula);
                        }
                    }
                }
            }
        }
        return $formula;
    }
}
