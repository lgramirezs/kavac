<?php
/** [descripción del namespace] */
namespace Modules\Finance\Models;

use App\Models\Deduction;
use App\Traits\ModelsTrait;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable as AuditableTrait;

/**
 * @class FinancePaymentDeduction
 * @brief [descripción detallada]
 *
 * [descripción corta]
 *
 * @author [autor de la clase] [correo del autor]
 *
 * @license
 *     [LICENCIA DE SOFTWARE CENDITEL](http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/)
 */
class FinancePaymentDeduction extends Model implements Auditable
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
        'amount',
        'deduction_id',
        'finance_payment_execute_id'
    ];

    /**
     * Get the financePaymentExecute that owns the FinancePaymentDeduction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function financePaymentExecute()
    {
        return $this->belongsTo(FinancePaymentExecute::class);
    }

    /**
     * Get the deduction that owns the FinancePaymentDeduction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function deduction()
    {
        return $this->belongsTo(Deduction::class);
    }
}
