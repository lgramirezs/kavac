<?php

namespace Modules\CitizenService\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use App\Traits\ModelsTrait;

/**
 * @class CitizenService
 * @brief Datos de información de ingresar solicitud
 *
 * Gestiona el modelo de ingresar solicitud
 *
 * @author Ing. Yennifer Ramirez <yramirez@cenditel.gob.ve>
 * @license <a href='http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/'>
 *              LICENCIA DE SOFTWARE CENDITEL
 *          </a>
 */
class CitizenServiceRequest extends Model implements Auditable
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
        'first_name','last_name','id_number','email', 'date',
        'city_id', 'municipality_id', 'address', 'motive_request', 'state',
        'institution_name','institution_address', 'rif', 'web',
        'citizen_service_request_type_id', 'type_institution', 'citizen_service_department_id',

        'type_team', 'brand', 'model', 'serial', 'color', 'transfer',
        'code', 'entryhour', 'exithour', 'informationteam'

    ];

    /**
     * Obtiene todos los número telefónicos asociados a la solicitud
     *

     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function phones()
    {
        return $this->morphMany(\App\Models\Phone::class, 'phoneable');
    }

    /**
     * Obtiene todos los número telefónicos asociados a la solicitud
     *

     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function documents()
    {
        return $this->morphMany(\App\Models\Document::class, 'documentable');
    }

    /**
     * Método que obtiene la solicitud asociado a un departamento
     *
     * @author
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function citizenServiceDepartment()
    {
        return $this->belongsTo(CitizenServiceDepartment::class);
    }

    public function scopeSearchPeriod(
        $query,
        $start_date,
        $end_date,
        $citizen_service_request_type_id,
        $citizen_service_id
    ) {
        return $query->whereBetween("date", [$start_date,$end_date])
                     ->where("citizen_service_request_type_id", $citizen_service_request_type_id)
                     ->where("state", $citizen_service_id);
    }
    public function scopeSearchDate(
        $query,
        $date,
        $citizen_service_request_type_id,
        $citizen_service_id
    ) {
        return $query->where("date", $date)
                     ->where("citizen_service_request_type_id", $citizen_service_request_type_id)
                     ->where("state", $citizen_service_id);
    }
}
