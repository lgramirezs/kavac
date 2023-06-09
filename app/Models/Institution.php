<?php

/** Modelos generales de base de datos */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

/**
 * @class Istitution
 * @brief Datos de Organizaciones
 *
 * Gestiona el modelo de datos para las Organizaciones
 *
 * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
 *
 * @license
 *     [LICENCIA DE SOFTWARE CENDITEL](http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/)
 */
class Institution extends Model implements Auditable
{
    use SoftDeletes;
    use AuditableTrait;

    /**
     * Lista de atributos para la gestión de fechas
     *
     * @var array $dates
     */
    protected $dates = ['deleted_at'];

    /**
     * Lista de atributos que pueden ser asignados masivamente
     *
     * @var array $fillable
     */
    protected $fillable = [
        'onapre_code', 'rif', 'acronym', 'name', 'business_name', 'start_operations_date', 'legal_base',
        'legal_form', 'main_activity', 'mission', 'vision', 'legal_address', 'web', 'composition_assets',
        'postal_code', 'active', 'default', 'retention_agent', 'institution_sector_id',
        'institution_type_id', 'municipality_id', 'city_id', 'logo_id', 'banner_id'
    ];

    /**
     * Método que obtiene el logotipo de la Organización
     *
     * @method  logo
     *
     * @author  Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     *
     * @return object Objeto con el registro relacionado al modelo Image
     */
    public function logo()
    {
        return $this->belongsTo(Image::class, 'logo_id');
    }

    /**
     * Método que obtiene el banner de la Organización
     *
     * @method  banner
     *
     * @author  Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     *
     * @return object Objeto con el registro relacionado al modelo Image
     */
    public function banner()
    {
        return $this->belongsTo(Image::class, 'banner_id');
    }

    /**
     * Método que obtiene el sector de la Organización
     *
     * @method  sector
     *
     * @author  Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     *
     * @return object Objeto con el registro relacionado al modelo InstitutionSector
     */
    public function sector()
    {
        return $this->belongsTo(InstitutionSector::class);
    }

    /**
     * Método que obtiene el tipo de la Organización
     *
     * @method  type
     *
     * @author  Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     *
     * @return object Objeto con el registro relacionado al modelo InstitutionType
     */
    public function type()
    {
        return $this->belongsTo(InstitutionType::class);
    }

    /**
     * Institution belongs to Municipality.
     *
     * @method municipality
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function municipality()
    {
        return $this->belongsTo(Municipality::class);
    }

    /**
     * Institution belongs to City.
     *
     * @method city
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Método que obtiene los departamentos asociados a la intitución.
     *
     * @method  departments
     *
     * @author  Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     *
     * @return object Objeto con los registros relacionados al modelo Institution
     */
    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    /**
     * Institution has many Profiles.
     *
     * @method  profiles
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function profiles()
    {
        return $this->hasMany(Profile::class);
    }

    /**
     * Institution has many FiscalYears.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fiscalYears()
    {
        return $this->hasMany(FiscalYear::class);
    }

    /**
     * Método que obtiene las asignacion relacionadas con la institución
     *
     * @author Daniel Contreras <dcontreras@cenditel.gob.ve>
     * @return Array|\Illuminate\Database\Eloquent\Relations\HasMany Objeto con el registro relacionado al modelo
     * AssetAsignation
     */
    public function assetAsignation()
    {
        return $this->hasMany(\Modules\Asset\Models\AssetAsignation::class);
    }
    /**
     * Filtra datos de la Organización de acuerdo al usuario autenticado
     *
     * @method     newQuery(boolean $excludeDeleted)
     *
     * @author     Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     *
     * @param      boolean          $excludeDeleted    Determina si se excluyen los registros eliminados de la consulta
     *
     * @return     object           Objeto que contiene la consulta
     */
    public function newQuery($excludeDeleted = true)
    {
        /** @var User Objeto con información del usuario autenticado */
        $user = auth()->user();
        if (
            $user !== null && method_exists($user, 'profile') && !is_null($user->profile) && 
            property_exists($user->profile, 'institution')
        ) {
            return parent::newQuery($excludeDeleted)->where('id', $user->profile->institution->id);
        }

        return parent::newQuery($excludeDeleted);
    }
}
