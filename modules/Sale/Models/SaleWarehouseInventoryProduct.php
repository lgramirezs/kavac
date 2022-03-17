<?php

namespace Modules\Sale\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use App\Traits\ModelsTrait;
/**
 * @class SaleWarehouseInventoryProduct
 * @brief Datos del inventario de los productos
 *
 * Gestiona el modelo de datos del inventario de los productos almacenables
 *
 * @author Daniel Contreras <dcontreras@cenditel.gob.ve>
 * @license <a href='http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/'>
 *              LICENCIA DE SOFTWARE CENDITEL
 *          </a>
 */
class SaleWarehouseInventoryProduct extends Model implements Auditable
{
    use SoftDeletes;
    use AuditableTrait;
    use ModelsTrait;
    /**
     * Lista de atributos para la gestión de fechas
     *
     * @var array $dates
     */
    protected $dates = ['deleted_at'];

    /**
     * Lista de atributos que pueden ser asignados masivamente
     *
     * @var array $fillable
     */
    protected $fillable = [
        'code', 'exist', 'reserved', 'unit_value', 'currency_id', 'measurement_unit_id', 'sale_setting_product_id', 'sale_warehouse_institution_warehouse_id', 'history_tax_id'
    ];

    /**
     * Método que obtiene el producto registrado
     *
     * @author Daniel Contreras <dcontreras@cenditel.gob.ve>
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo Objeto con el registro relacionado al modelo
     * WarehouseProduct
     */
    public function saleSettingProduct()
    {
        return $this->belongsTo(SaleSettingProduct::class);
    }

    /**
     * Método que obtiene los valores de los atributos del producto registrado
     *
     * @author Daniel Contreras <dcontreras@cenditel.gob.ve>
     * @return \Illuminate\Database\Eloquent\Relations\HasMany Objeto con el registro relacionado al modelo
     * WarehouseProductValue
     */
    public function saleWarehouseProductValues()
    {
        return $this->hasMany(SaleWarehouseProductValue::class);
    }

    /**
     * Método que obtiene la moneda en que se expresa el valor del producto
     *
     * @author Daniel Contreras <dcontreras@cenditel.gob.ve>
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo Objeto con el registro relacionado al modelo Currency
     */
    public function currency()
    {
        return $this->belongsTo(\App\Models\Currency::class);
    }

    /**
     * Método que obtiene el almacen donde esta inventariado el producto
     *
     * @author Daniel Contreras <dcontreras@cenditel.gob.ve>
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo Objeto con el registro relacionado al modelo
     * SaleWarehouseInstitutionWarehouse
     */
    public function saleWarehouseInstitutionWarehouse()
    {
        return $this->belongsTo(SaleWarehouseInstitutionWarehouse::class);
    }

    /**
     * Método que obtiene las reglas de almacenamiento del producto en el inventario
     *
     * @author Daniel Contreras <dcontreras@cenditel.gob.ve>
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo Objeto con el registro relacionado al modelo
     * WarehouseInventoryRule
     */
    public function saleWarehouseInventoryRule()
    {
        return $this->hasOne(SaleWarehouseInventoryRule::class);
    }

    /**
     * Método que obtiene la unidad de medida
     *
     * @author Daniel Contreras <dcontreras@cenditel.gob.ve>
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo Objeto con el registro relacionado al modelo
     * MeasurementUnit
     */
    public function measurementUnit()
    {
        return $this->belongsTo(\App\Models\MeasurementUnit::class);
    }

    /**
     * Método que obtiene los porcentajes de impuestos almacenados en el sistema
     *
     * @author Daniel Contreras <dcontreras@cenditel.gob.ve>
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo Objeto con el registro relacionado al modelo
     * HistoryTax
     */
    public function historyTax()
    {
        return $this->belongsTo(\App\Models\HistoryTax::class);
    }

    /**
     * Método que obtiene el registro en el inventario del producto movilizado
     *
     * @author Daniel Contreras <dcontreras@cenditel.gob.ve>
     * @return \Illuminate\Database\Eloquent\Relations\HasMany Objeto con el registro relacionado al modelo
     * SaleWarehouseInventoryProductMovement
     */
    public function saleWarehouseInventoryProductMovement()
    {
        return $this->hasMany(SaleWarehouseInventoryProductMovement::class);
    }
}
