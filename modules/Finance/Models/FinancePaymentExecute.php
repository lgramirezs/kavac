<?php
/** [descripción del namespace] */
namespace Modules\Finance\Models;

use App\Models\Receiver;
use App\Traits\ModelsTrait;
use App\Models\DocumentStatus;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable as AuditableTrait;

/**
 * @class FinancePaymentExecute
 * @brief [descripción detallada]
 *
 * [descripción corta]
 *
 * @author [autor de la clase] [correo del autor]
 *
 * @license
 *     [LICENCIA DE SOFTWARE CENDITEL](http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/)
 */
class FinancePaymentExecute extends Model implements Auditable
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
        'paid_at',
        'has_budget',
        'is_partial',
        'source_amount',
        'deduction_amount',
        'paid_amount',
        'pending_amount',
        'completed',
        'observations',
        'status',
        'document_status_id',
        'currency_id'
    ];

    protected $appends = ['receiver_name'];

    public function getReceiverNameAttribute()
    {
        $payOrder = FinancePayOrderFinancePaymentExecute::where('finance_payment_execute_id', $this->id)->first();
        if (!$payOrder) {
            return '';
        }
        
        $receiver = Receiver::where([
            'receiverable_type' => $payOrder->financePayOrder->name_sourceable_type,
            'receiverable_id' => $payOrder->financePayOrder->name_sourceable_id
        ])->first();
        return $receiver->description;
    }

    /**
     * The financePayOrders that belong to the FinancePaymentExecute
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function financePayOrders()
    {
        return $this->belongsToMany(FinancePayOrder::class)->withTimestamps();
    }

    /**
     * Get all of the financePaymentDeductions for the FinancePaymentExecute
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function financePaymentDeductions()
    {
        return $this->hasMany(FinancePaymentDeduction::class);
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
}
