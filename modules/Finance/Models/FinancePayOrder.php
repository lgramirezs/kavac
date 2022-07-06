<?php
/** [descripción del namespace] */
namespace Modules\Finance\Models;

use App\Models\Currency;
use App\Models\Institution;
use App\Traits\ModelsTrait;
use App\Models\DocumentStatus;
use Nwidart\Modules\Facades\Module;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable as AuditableTrait;
use Modules\Accounting\Models\AccountingEntryable;

/**
 * @class FinancePayOrder
 * @brief [descripción detallada]
 *
 * [descripción corta]
 *
 * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
 *
 * @license
 *     [LICENCIA DE SOFTWARE CENDITEL](http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/)
 */
class FinancePayOrder extends Model implements Auditable
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
        'ordered_at',
        'type',
        'is_partial',
        'pending_amount',
        'completed',
        'document_type',
        'document_number',
        'source_amount',
        'amount',
        'concept',
        'observations',
        'status',
        'budget_specific_action_id',
        'finance_payment_method_id',
        'finance_bank_account_id',
        'institution_id',
        'document_status_id',
        'currency_id',
        'name_sourceable_type',
        'name_sourceable_id',
        'document_sourceable_type',
        'document_sourceable_id'
    ];

    protected $appends = ['accounting_entryable'];

    /**
     * Obtiene los datos del asiento contable asociado
     *
     * @author Ing. Roldan Vargas <roldandvg at gmail.com> | <rvargas at cenditel.gob.ve>
     *
     * @return void 
     */
    public function getAccountingEntryableAttribute()
    {
        $accountingEntryable = null;
        
        if (Module::has('Accounting')) {
            $accountingEntryable = AccountingEntryable::with(['accountingEntry' => function($q) {
                $q->with(['accountingAccounts' => function ($qq) {
                    $qq->with('account');
                }]);
            }])->where([
                'accounting_entryable_type' => FinancePayOrder::class,
                'accounting_entryable_id' => $this->id
            ])->first();
        }
        return $accountingEntryable;
    }

    /**
     * Get the budgetSpecificAction that owns the FinancePayOrder
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function budgetSpecificAction()
    {
        return (Module::has('Budget'))
               ? $this->belongsTo(\Modules\Budget\Models\BudgetSpecificAction::class) : [];
    }

    /**
     * Get the financePaymentMethod that owns the FinancePayOrder
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function financePaymentMethod()
    {
        return $this->belongsTo(FinancePaymentMethods::class, 'finance_payment_method_id');
    }

    /**
     * Get the financeBankAccount that owns the FinancePayOrder
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function financeBankAccount()
    {
        return $this->belongsTo(FinanceBankAccount::class);
    }

    /**
     * Get the institution that owns the FinancePayOrder
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    /**
     * Get the documentStatus that owns the FinancePayOrder
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function documentStatus()
    {
        return $this->belongsTo(DocumentStatus::class, 'document_status_id');
    }

    /**
     * Get the currency that owns the FinancePayOrder
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * FinancePayOrder morphs to models in document_sourceable_type.
     *
     * @author  Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function documentSourceable()
    {
        return $this->morphTo();
    }

    /**
     * FinancePayOrder morphs to models in name_sourceable_type.
     *
     * @author  Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function nameSourceable()
    {
        return $this->morphTo();
    }

    /**
     * The financePaymentExecute that belong to the FinancePayOrder
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function financePaymentExecute()
    {
        return $this->belongsToMany(FinancePaymentExecute::class)->withTimestamps();
    }
}
