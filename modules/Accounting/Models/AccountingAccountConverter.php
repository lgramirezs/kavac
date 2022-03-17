<?php

namespace Modules\Accounting\Models;

use Nwidart\Modules\Facades\Module;
use Illuminate\Database\Eloquent\Model;
//use Venturecraft\Revisionable\RevisionableTrait;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable as AuditableTrait;
use App\Traits\ModelsTrait;

/**
 * @class AccountingAccount
 * @brief Datos de cuentas del Clasificador Patrimoniales
 *
 * Modelo de la tabla pivot entre budget_account y accounting_account
 *
 * @author Juan Rosas <jrosas@cenditel.gob.ve | juan.rosasr01@gmail.com>
 * @license
 *     [LICENCIA DE SOFTWARE CENDITEL](http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/)
 */
class AccountingAccountConverter extends Model implements Auditable
{
    use SoftDeletes;
    use AuditableTrait;
    use ModelsTrait;
    
    protected $fillable = [
        'accounting_account_id',
        'budget_account_id',
        'active'
    ];

    /**
     * AccountingAccountConverter belongs to BudgetAccount.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function budgetAccount()
    {
        return (Module::has('Budget'))? $this->belongsTo(\Modules\Budget\Models\BudgetAccount::class) : null;
    }

    /**
     * AccountingAccountConverter belongs to AccountingAccount.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function accountingAccount()
    {
        return $this->belongsTo(AccountingAccount::class, 'accounting_account_id');
    }
}
