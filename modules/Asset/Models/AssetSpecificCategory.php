<?php

namespace Modules\Asset\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use App\Traits\ModelsTrait;

/**
 * @class AssetSpecificCategory
 * @brief Datos de las categorias especificas de un bien
 *
 * Gestiona el modelo de datos para las categorias especificas de un bien
 *
 * @author Henry Paredes <hparedes@cenditel.gob.ve>
 * @license <a href='http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/'>
 *              LICENCIA DE SOFTWARE CENDITEL
 *          </a>
 */
class AssetSpecificCategory extends Model implements Auditable
{
    use SoftDeletes;
    use AuditableTrait;
    use ModelsTrait;

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
    protected $fillable = ['code', 'name', 'asset_subcategory_id'];

    /**
     * Lista de atributos personalizados obtenidos por defecto
     *
     * @var array $appends
     */
    protected $appends = [
        'asset_type_id'
    ];

    /**
     * Método que obtiene el valor asociado al campo asset_type_id
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve> | <henryp2804@gmail.com>
     *
     * @return    Object    Objeto con las propiedades registrados
     */
    public function getAssetTypeIdAttribute()
    {
        $data = '';
        if (isset($this->assetSubcategory)) {
            $data = $this->assetSubcategory->assetCategory->assetType->id;
        }
        return $data;
    }

    /**
     * Método que obtiene la subcategoria asociada a la categoria especifica
     *
     * @author Henry Paredes <hparedes@cenditel.gob.ve>
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo Objeto con el registro relacionado al modelo
     * AssetSubcategory
     */
    public function assetSubcategory()
    {
        return $this->belongsTo(AssetSubcategory::class);
    }

    /**
     * Método que obtiene los bienes asociados a la categoria especifica
     *
     * @author Henry Paredes <hparedes@cenditel.gob.ve>
     * @return \Illuminate\Database\Eloquent\Relations\HasMany Objeto con el registro relacionado al modelo Asset
     */
    public function assets()
    {
        return $this->hasMany(Asset::class);
    }
}
