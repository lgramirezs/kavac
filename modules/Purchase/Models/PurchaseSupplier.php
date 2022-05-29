<?php

namespace Modules\Purchase\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use App\Traits\ModelsTrait;

use Modules\Purchase\Models\PurchaseSupplierObject;

/**
 * @class PurchaseSupplier
 * @brief Datos de los proveedores
 *
 * Gestiona el modelo de datos para los proveedores
 *
 * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
 * @license <a href='http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/'>
 *              LICENCIA DE SOFTWARE CENDITEL
 *          </a>
 */
class PurchaseSupplier extends Model implements Auditable
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

    protected $with = [
        'purchaseSupplierSpecialty', 'purchaseSupplierType', 'purchaseSupplierBranch', 'purchaseSupplierObjects',
        'phones', 'city', 'contacts'
    ];

    protected $fillable = [
        'rif', 'code', 'name', 'direction', 'person_type', 'company_type', 'website',
        'active', 'purchase_supplier_specialty_id', 'purchase_supplier_type_id', 'purchase_supplier_object_id',
        'purchase_supplier_branch_id', 'country_id', 'estate_id', 'city_id', 'rnc_status', 'rnc_certificate_number', 
        'social_purpose'
    ];

    /**
     * Get all of the budget project's specific actions.
     *
     * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function phones()
    {
        return $this->morphMany(\App\Models\Phone::class, 'phoneable');
    }

    /**
     * PurchaseSupplier morphs many contact.
     *
     * @author  Juan Rosas <jrosas@cenditel.gob.ve> | <juan.rosasr01@gmail.com>
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function contacts()
    {
        return $this->morphMany(\App\Models\Contact::class, 'contactable');
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
     * PurchaseSupplier belongs to PurchaseSupplierSpecialty.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function purchaseSupplierSpecialty()
    {
        return $this->belongsTo(PurchaseSupplierSpecialty::class);
    }

    /**
     * PurchaseSupplier belongs to PurchaseSupplierType.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function purchaseSupplierType()
    {
        return $this->belongsTo(PurchaseSupplierType::class);
    }

    /**
     * PurchaseSupplier belongs to Country.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * PurchaseSupplier belongs to Estate.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function estate()
    {
        return $this->belongsTo(Estate::class);
    }

    /**
     * PurchaseSupplier belongs to City.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * PurchaseSupplier belongs to PurchaseSupplierBranch.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function purchaseSupplierBranch()
    {
        return $this->belongsTo(PurchaseSupplierBranch::class);
    }

    /**
     * The purchaseSupplierObjects that belong to the PurchaseSupplier
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function purchaseSupplierObjects()
    {
        return $this->belongsToMany(PurchaseSupplierObject::class, 'purchase_object_supplier');
    }

    /**
     * PurchaseSupplier has many PurchaseOrder.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchaseOrder()
    {
        // hasMany(RelatedModel, foreignKeyOnRelatedModel = purchaseSupplier_id, localKey = id)
        return $this->hasMany(PurchaseOrder::class);
    }

    /**
     * Método que obtiene los bienes asociados a un proveedor
     *
     * @author Henry Paredes <hparedes@cenditel.gob.ve>
     * @return \Illuminate\Database\Eloquent\Relations\HasMany Objeto con el registro relacionado al modelo Asset
     */
    public function assets()
    {
        return $this->hasMany(\Modules\Asset\Models\Asset::class);
    }
}
