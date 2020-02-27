<?php

namespace Modules\Purchase\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use App\Models\MeasurementUnit;

/**
 * @class PurchaseRequirementItem
 * @brief Datos de los productos o servicios en los requerimientos de compras
 *
 * Gestiona el modelo de datos para los productos o servicios en los requerimientos de compra
 *
 * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
 * @license <a href='http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/'>
 *              LICENCIA DE SOFTWARE CENDITEL
 *          </a>
 */
class PurchaseRequirementItem extends Model implements Auditable
{
    use SoftDeletes;
    use AuditableTrait;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name', 'description', 'technical_specifications', 'quantity', 'measurement_unit_id',
        'warehouse_product_id', 'purchase_requirement_id', 'unit_price'
    ];

    protected $with = ['measurementUnit'];

    /**
     * PurchaseRequirementItem belongs to PurchaseRequirement.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function purchaseRequirement()
    {
        return $this->belongsTo(PurchaseRequirement::class);
    }
    /**
     * PurchaseRequirementItem belongs to MeasurementUnit.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function measurementUnit()
    {
        // belongsTo(RelatedModel, foreignKey = measurementUnit_id, keyOnRelatedModel = id)
        return $this->belongsTo(MeasurementUnit::class);
    }

    /**
     * PurchaseRequirementItem has many PurchaseBaseBudget.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchaseBaseBudget()
    {
        // hasMany(RelatedModel, foreignKeyOnRelatedModel = purchaseRequirementItem_id, localKey = id)
        return $this->hasMany(PurchaseBaeBudget::class);
    }

    /**
     * PurchaseRequirementItem has many PurchaseOrder.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchaseOrder()
    {
        // hasMany(RelatedModel, foreignKeyOnRelatedModel = purchaseRequirementItem_id, localKey = id)
        return $this->hasMany(PurchaseOrder::class);
    }

    /**
     * PurchaseRequirementItem has one PurchasePivotModelsToRequirementItem.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function pivotPurchase()
    {
        // hasOne(RelatedModel, foreignKeyOnRelatedModel = purchaseRequirementItem_id, localKey = id)
        return $this->hasOne(PurchasePivotModelsToRequirementItem::class);
    }
}
