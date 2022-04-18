<?php

namespace Modules\Purchase\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use App\Traits\ModelsTrait;

/**
 * @class PurchaseRequirement
 * @brief Datos de los requerimientos de compras
 *
 * Gestiona el modelo de datos para los requerimientos de compra
 *
 * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
 * @license <a href='http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/'>
 *              LICENCIA DE SOFTWARE CENDITEL
 *          </a>
 */
class PurchaseRequirement extends Model implements Auditable
{
    use SoftDeletes;
    use AuditableTrait;
    use ModelsTrait;
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $with = ['purchaseSupplierObject','fiscalYear'];

    protected $fillable = [
        'code', 'description', 'fiscal_year_id', 'contracting_department_id', 'user_department_id',
        'purchase_supplier_object_id', 'requirement_status', 'purchase_base_budget_id', 'institution_id'
    ];
    
    /**
     * PurchaseRequirement belongs to FiscalYear.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fiscalYear()
    {
        return $this->belongsTo(FiscalYear::class);
    }

    /**
     * PurchaseRequirement belongs to PurchaseSupplierObject.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function purchaseSupplierObject()
    {
        return $this->belongsTo(PurchaseSupplierObject::class);
    }

    /**
     * PurchaseRequirement belongs to ContratingDepartment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function institution()
    {
        return $this->belongsTo('App\Models\Institution', 'institution_id')??null;
    }
    
    /**
     * PurchaseRequirement belongs to ContratingDepartment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function contratingDepartment()
    {
        return $this->belongsTo(Department::class, 'contracting_department_id')??null;
    }

    /**
     * PurchaseRequirement belongs to UserDepartment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userDepartment()
    {
        return $this->belongsTo(Department::class, 'user_department_id')??null;
    }


    /**
     * PurchaseRequirement has many PurchaseRequirementItems.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchaseRequirementItems()
    {
        return $this->hasMany(PurchaseRequirementItem::class);
    }

    /**
     * PurchaseRequirement belongs to PurchaseBaseBudget.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function purchaseBaseBudget()
    {
        // belongsTo(RelatedModel, foreignKey = purchaseBaseBudget_id, keyOnRelatedModel = id)
        return $this->belongsTo(PurchaseBaseBudget::class);
    }

    /**
     * PurchaseRequirement belongs to PurchaseOrder.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function purchaseOrder()
    {
        // belongsTo(RelatedModel, foreignKey = purchaseOrder_id, keyOnRelatedModel = id)
        return $this->belongsTo(PurchaseOrder::class);
    }
}
