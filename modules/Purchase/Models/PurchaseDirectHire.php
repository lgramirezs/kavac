<?php
/** [descripción del namespace] */
namespace Modules\Purchase\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use App\Traits\ModelsTrait;

use Nwidart\Modules\Facades\Module;

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
        'code',
        'institution_id',
        'contracting_department_id',
        'user_department_id',
        'fiscal_year_id',
        'purchase_supplier_id',
        'purchase_supplier_object_id',
        'currency_id',
        'funding_source',
        'description',
        'payment_methods',

        // variables para firmas
        'prepared_by_id',
        'reviewed_by_id',
        'verified_by_id',
        'first_signature_id',
        'second_signature_id',
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

    /**
     * PurchaseDirectHire morphs many PurchaseBaseBudget.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function purchaseBaseBudgets()
    {
        // morphMany(MorphedModel, morphableName, type = orderable_type, relatedKeyName = orderable_id, localKey = id)
        return $this->morphMany(PurchaseBaseBudget::class, 'orderable');
    }

    /**
     * PurchaseDirectHire belongs to payroll_employment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function preparedBy()
    {
        // belongsTo(RelatedModel, foreignKey = payroll_employment_id, keyOnRelatedModel = id)
        return (Module::has('Payroll') && Module::isEnabled('Payroll'))? $this->belongsTo(\Modules\Payroll\Models\PayrollEmployment::class, 'prepared_by_id') : null;
    }

    /**
     * PurchaseDirectHire belongs to payroll_employment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reviewedBy()
    {
        // belongsTo(RelatedModel, foreignKey = payroll_employment_id, keyOnRelatedModel = id)
        return (Module::has('Payroll') && Module::isEnabled('Payroll'))? $this->belongsTo(\Modules\Payroll\Models\PayrollEmployment::class, 'reviewed_by_id') : null;
    }
    /**
     * PurchaseDirectHire belongs to payroll_employment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function verifiedBy()
    {
        // belongsTo(RelatedModel, foreignKey = payroll_employment_id, keyOnRelatedModel = id)
        return (Module::has('Payroll') && Module::isEnabled('Payroll'))? $this->belongsTo(\Modules\Payroll\Models\PayrollEmployment::class, 'verified_by_id') : null;
    }
    /**
     * PurchaseDirectHire belongs to payroll_employment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function firstSignature()
    {
        // belongsTo(RelatedModel, foreignKey = payroll_employment_id, keyOnRelatedModel = id)
        return (Module::has('Payroll') && Module::isEnabled('Payroll'))? $this->belongsTo(\Modules\Payroll\Models\PayrollEmployment::class, 'first_signature_id') : null;
    }
    /**
     * PurchaseDirectHire belongs to payroll_employment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function secondSignature()
    {
        // belongsTo(RelatedModel, foreignKey = payroll_employment_id, keyOnRelatedModel = id)
        return (Module::has('Payroll') && Module::isEnabled('Payroll'))? $this->belongsTo(\Modules\Payroll\Models\PayrollEmployment::class, 'second_signature_id') : null;
    }
}
