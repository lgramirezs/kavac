<?php
/** [descripción del namespace] */
namespace Modules\Payroll\Models;

use App\Models\Profile;
use App\Traits\ModelsTrait;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable as AuditableTrait;

/**
 * @class PayrollEmployment
 * @brief Datos laborales del trabajador
 *
 * Gestiona el modelo de datos laborales
 *
 * @author William Páez <wpaez@cenditel.gob.ve>
 *
 * @license
 *     [LICENCIA DE SOFTWARE CENDITEL](http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/)
 */
class PayrollEmployment extends Model implements Auditable
{
    use SoftDeletes;
    use AuditableTrait;
    use ModelsTrait;

    /**
     * Lista de atributos de relacion consultados automáticamente
     * @var array $with
     */
    protected $with = ['payrollPosition', 'department'];

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
        'active', 'years_apn', 'start_date', 'end_date', 'institution_email', 'function_description',
        'payroll_inactivity_type_id', 'payroll_position_type_id', 'payroll_position_id', 'department_id',
        'payroll_staff_type_id', 'payroll_contract_type_id', 'payroll_staff_id'
    ];

    /**
     * Método que obtiene el dato laboral del trabajador que está asociada a muchas organizaciones
     *
     * @author  William Páez <wpaez@cenditel.gob.ve>
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payrollOrganizations()
    {
        return $this->hasMany(PayrollOrganization::class);
    }

    /**
     * Método que obtiene el dato laboral del trabajador asociado a un dato personal del mismo
     *
     * @author  William Páez <wpaez@cenditel.gob.ve>
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function payrollStaff()
    {
        return $this->belongsTo(PayrollStaff::class);
    }

    /**
     * Método que obtiene el dato laboral del trabajador asociado a un tipo de inactividad
     *
     * @author  William Páez <wpaez@cenditel.gob.ve>
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function payrollInactivityType()
    {
        return $this->belongsTo(PayrollInactivityType::class);
    }

    /**
     * Método que obtiene el dato laboral del trabajador asociado a un tipo de cargo
     *
     * @author  William Páez <wpaez@cenditel.gob.ve>
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function payrollPositionType()
    {
        return $this->belongsTo(PayrollPositionType::class);
    }

    /**
     * Método que obtiene el dato laboral del trabajador asociado a un cargo
     *
     * @author  William Páez <wpaez@cenditel.gob.ve>
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function payrollPosition()
    {
        return $this->belongsTo(PayrollPosition::class);
    }

    /**
     * Método que obtiene el dato laboral del trabajador asociado a un departamento
     *
     * @author  William Páez <wpaez@cenditel.gob.ve>
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Método que obtiene el dato laboral del trabajador asociado a un tipo de personal
     *
     * @author  William Páez <wpaez@cenditel.gob.ve>
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function payrollStaffType()
    {
        return $this->belongsTo(PayrollStaffType::class);
    }

    /**
     * Método que obtiene el dato laboral del trabajador asociado a un tipo de contrato
     *
     * @author  William Páez <wpaez@cenditel.gob.ve>
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function payrollContractType()
    {
        return $this->belongsTo(PayrollContractType::class);
    }

    /**
     * Método que obtiene los trabajos anteriores asociados al trabajador
     *
     * @author  Daniel Contreras <dcontreras@cenditel.gob.ve>
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function payrollPreviousJob()
    {
        return $this->hasMany(PayrollPreviousJob::class);
    }

    /**
     * Método que obtiene los datos del perfil asociado al trabajador
     *
     * @author Ing. Roldan Vargas <roldandvg at gmail.com> | <rvargas at cenditel.gob.ve>
     *
     * @return void 
     */
    public function profile()
    {
        return $this->belongsTo(Profile::class, 'employee_id');
    }
}
