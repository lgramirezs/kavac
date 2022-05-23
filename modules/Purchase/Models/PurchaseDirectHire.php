<?php
/** [descripción del namespace] */
namespace Modules\Purchase\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use App\Traits\ModelsTrait;

/**
 * @class PurchaseDirectHire
 * @brief Modelo para la contratación directa
 *
 * Modelo para la contratación directa
 *
 * @author Juan Rosas <jrosas@cenditel.gob.ve | juan.rosasr01@gmail.com>
 *
 * @license
 *     [LICENCIA DE SOFTWARE CENDITEL](http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/)
 */
class PurchaseDirectHire extends Model implements Auditable
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
        'institution_id',
        'contracting_department_id',
        'user_department_id',
        'fiscal_year_id',
        'purchase_supplier_id',
        'purchase_supplier_object_id',
        'currency_id',
        'funding_source',
        'description',
    ];

    /**
     * PurchaseDirectHire belongs to FiscalYear.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fiscalYear()
    {
        // belongsTo(RelatedModel, foreignKey = curr_id, keyOnRelatedModel = id)
        return $this->belongsTo(FiscalYear::class);
    }

    /**
     * PurchaseDirectHire belongs to Currency.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currency()
    {
        // belongsTo(RelatedModel, foreignKey = curr_id, keyOnRelatedModel = id)
        return $this->belongsTo(Currency::class);
    }
    /**
     * PurchaseDirectHire belongs to Institution.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function institution()
    {
        // belongsTo(RelatedModel, foreignKey = institution_id, keyOnRelatedModel = id)
        return $this->belongsTo(Institution::class);
    }

    /**
     * Obtiene todos los documentos asociados al proveedor
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    /**
     * PurchaseDirectHire belongs to PurchaseSupplier.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function purchaseSupplier()
    {
        // belongsTo(RelatedModel, foreignKey = purchaseSupplier_id, keyOnRelatedModel = id)
        return $this->belongsTo(PurchaseSupplier::class);
    }

    /**
     * PurchaseDirectHire belongs to PurchaseSupplierObject.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function purchaseSupplierObject()
    {
        return $this->belongsTo(PurchaseSupplierObject::class);
    }

    /**
     * PurchaseDirectHire belongs to ContratingDepartment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function contratingDepartment()
    {
        return $this->belongsTo(Department::class, 'contracting_department_id');
    }

    /**
     * PurchaseDirectHire belongs to UserDepartment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userDepartment()
    {
        return $this->belongsTo(Department::class, 'user_department_id');
    }
}
