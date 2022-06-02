<?php
/** [descripción del namespace] */
namespace Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use App\Traits\ModelsTrait;
use Modules\Accounting\Models\AccountingEntryable;
use Modules\Budget\Models\BudgetCompromise;
use App\Models\Currency;
use App\Models\Institution;

/**
 * @class FinanceBankingMovement
 * @brief Movimientos bancarios
 *
 * Gestiona el modelo de datos para los movimientos bancarios
 *
 * @author Daniel Contreras <dcontreras@cenditel.gob.ve>
 *
 * @license <a href='http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/'>
 *              LICENCIA DE SOFTWARE CENDITEL
 *          </a>
 */
class FinanceBankingMovement extends Model implements Auditable
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
        'payment_date', 'transaction_type', 'reference', 'concept', 'amount',
        'finance_bank_account_id', 'currency_id', 'institution_id', 'code'
    ];

    /**
     * Método que obtiene la cuenta bancaria
     *
     * @author Daniel Contreras <dcontreras@cenditel.gob.ve>
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo Objeto con el registro relacionado al modelo
     * FinanceBankAccount
     */
    public function financeBankAccount()
    {
        return $this->belongsTo(FinanceBankAccount::class);
    }

    /**
     * Método que el asiento contable
     *
     * @author Daniel Contreras <dcontreras@cenditel.gob.ve>
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo Objeto con el registro relacionado al modelo
     * AccountingEntry
     */
    public function accountingEntryPivot()
    {
        return $this->morphOne(AccountingEntryable::class, 'accounting_entryable');
    }

    /**
     * Método que obtiene el compromiso
     *
     * @author Daniel Contreras <dcontreras@cenditel.gob.ve>
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo Objeto con el registro relacionado al modelo
     * BudgetCompromise
     */
    public function budgetCompromise()
    {
        return $this->morphOne(BudgetCompromise::class, 'compromiseable');
    }

    /**
     * Método que obtiene el tipo de moneda
     *
     * @author Daniel Contreras <dcontreras@cenditel.gob.ve>
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo Objeto con el registro relacionado al modelo
     * Currency
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * Método que obtiene la institución
     *
     * @author Daniel Contreras <dcontreras@cenditel.gob.ve>
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo Objeto con el registro relacionado al modelo
     * Institution
     */
    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }
}
