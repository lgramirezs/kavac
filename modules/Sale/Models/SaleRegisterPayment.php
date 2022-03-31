<?php
/** [descripción del namespace] */
namespace Modules\Sale\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use App\Traits\ModelsTrait;
use Modules\Sale\Models\SaleGoodsToBeTraded;

/**
 * @class SaleRegisterPayment
 * @brief [descripción detallada]
 *
 * [descripción corta]
 *
 * @author [autor de la clase] [correo del autor]
 *
 * @license
 *     [LICENCIA DE SOFTWARE CENDITEL](http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/)
 */
class SaleRegisterPayment extends Model implements Auditable
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
    protected $fillable = ['id','order_or_service_define_attributes','order_service_id','total_amount','way_to_pay','banking_entity','reference_number','payment_date','advance_define_attributes','payment_approve','payment_refuse'];

    public function saleService()
    {
        return $this->belongsTo(SaleService::class, 'order_service_id', 'id');
    }

    /**
     * Método que obtiene las formas de pago almacenadas en el sistema
     *
     * @author Miguel Narvaez <mnarvaez@cenditel.gob.ve>
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo Objeto con el registro relacionado al modelo
     * Currency
     */
    public function Currency()
    {
        return $this->belongsTo(\App\Models\Currency::class);
    }


    /**
     * Método que obtiene los bancos registrados
     *
     * @author Miguel Narvaez <mnarvaez@cenditel.gob.ve>
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo Objeto con el registro relacionado al modelo
     * Currency
     */
    public function FinanceBank()
    {
        return $this->belongsTo(\Modules\Finance\Models\FinanceBank::class);
    }

    /**
     * Método que obtiene las formas de cobro 
     *
     * @author Miguel Narvaez <mnarvaez@cenditel.gob.ve>
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo Objeto con el registro relacionado al modelo
     * Currency
     */
    public function SaleFormPayment()
    {
        return $this->belongsTo(\Modules\Sale\Models\SaleFormPayment::class);
    }

}









