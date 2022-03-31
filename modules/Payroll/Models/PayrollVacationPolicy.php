<?php

namespace Modules\Payroll\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use App\Traits\ModelsTrait;

/**
 * @class      PayrollVacationPolicy
 * @brief      Datos de las políticas vacacionales
 *
 * Gestiona el modelo de datos de las políticas vacacionales
 *
 * @author     Henry Paredes <hparedes@cenditel.gob.ve>
 * @license
 *     [LICENCIA DE SOFTWARE CENDITEL](http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/)
 */
class PayrollVacationPolicy extends Model implements Auditable
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
        'name', 'start_date', 'end_date', 'vacation_periods', 'vacation_type', 'active',
        'vacation_periods_accumulated_per_year', 'vacation_days', 'vacation_period_per_year',
        'additional_days_per_year', 'minimum_additional_days_per_year', 'maximum_additional_days_per_year',
        'payment_calculation', 'salary_type', 'vacation_advance', 'vacation_postpone', 'staff_antiquity',
        'institution_id', 'payroll_payment_type_id', 'assign_to'
    ];

    /**
     * Lista de atributos de relacion consultados automaticamente
     * @var    array    $with
     */
    protected $with = ['institution'];

    /**
     * Método que obtiene la información de la institución asociada a la política vacacional
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
     * Método que obtiene la información del tipo de pago asociado a la política vacacional
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     *
     * @return    \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function payrollPaymentType()
    {
        return $this->belongsTo(PayrollPaymentType::class);
    }

    /**
     * Método que obtiene la información de las opciones a asignar asociadas al concepto
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     *
     * @return    \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payrollConceptAssignOptions()
    {
        return $this->hasMany(PayrollConceptAssignOption::class);
    }
}
