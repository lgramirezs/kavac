<?php

namespace Modules\Asset\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

/**
 * @class Asset
 * @brief Datos de los Bienes Institucionales
 * 
 * Gestiona el modelo de datos para los Bienes Institucionales
 * 
 * @author Henry Paredes (henryp2804@gmail.com)
 * @copyright <a href='http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/'>LICENCIA DE SOFTWARE CENDITEL</a>
 */

class Asset extends Model implements Auditable
{
    use SoftDeletes;
    use RevisionableTrait;
    use AuditableTrait;

    /**
     * Establece el uso o no de bitácora de registros para este modelo
     * @var boolean $revisionCreationsEnabled
     */
    protected $revisionCreationsEnabled = true;

    /**
     * Lista de atributos para la gestión de fechas
     *
     * @var array $dates
     */
    protected $dates = ['deleted_at'];

    /**
     * Lista de atributos que pueden ser asignados masivamente
     * @var array $fillable
     */
    protected $fillable = [
        'orden_compra', 'type_id', 'category_id', 'subcategory_id', 'specific_category_id', 'institution_id', 'proveedor_id','condition_id','purchase_id','purchase_year','status_id', 'serial', 'marca',
        'model', 'serial_inventario', 'value','use_id'
    ];

    /**
     * Método que permite obtener el Serial de Inventario de un Bien
     *
     * @author Henry Paredes (henryp2804@gmail.com)
     * @return [string] Retorna el Serial de Inventario de un Bien
     */
    public function getCode()
    {
        $code = $this->type_id .'-'. $this->category_id .'-'. $this->subcategory_id .'-'. $this->specific_category_id .'-'. $this->purchase_year .'-'. $this->id;

        return $code;
    }

    public function getDescription()
    {
        $description = 'Código: '.$this->getCode() .'. Marca: '. $this->marca .'. Modelo: '. $this->model;
        if ($this->type_id == 2){
            $description = $description . ". Serial: ". $this->serial;
        }
        return $description;
    }
    
    /**
     * Método que obtiene el tipo al que pertenece el bien
     *
     * @author Henry Paredes (henryp2804@gmail.com)
     * @return Objeto con el registro relacionado al modelo AssetCategory
     */
    public function type()
    {
        return $this->belongsTo('Modules\Asset\Models\AssetType', 'type_id');
    }
    
    /**
     * Método que obtiene la categoria a la que pertenece el bien
     *
     * @author Henry Paredes (henryp2804@gmail.com)
     * @return Objeto con el registro relacionado al modelo AssetCategory
     */
    public function category()
    {
        return $this->belongsTo('Modules\Asset\Models\AssetCategory', 'category_id');
    }

    /**
     * Método que obtiene la subcategoria a la que pertenece el bien
     *
     * @author Henry Paredes (henryp2804@gmail.com)
     * @return Objeto con el registro relacionado al modelo AssetCategory
     */
    public function subcategory()
    {
        return $this->belongsTo('Modules\Asset\Models\AssetSubcategory', 'subcategory_id');
    }

    /**
     * Método que obtiene la categoria específica a la que pertenece el bien
     *
     * @author Henry Paredes (henryp2804@gmail.com)
     * @return Objeto con el registro relacionado al modelo AssetCategory
     */
    public function specific()
    {
        return $this->belongsTo('Modules\Asset\Models\AssetSpecificCategory', 'specific_category_id');
    }

    /**
     * Método que obtiene la forma de adquisicion del bien
     *
     * @author Henry Paredes (henryp2804@gmail.com)
     * @return Objeto con el registro relacionado al modelo AssetCategory
     */
    public function purchase()
    {
        return $this->belongsTo('Modules\Asset\Models\AssetPurchase', 'purchase_id');
    }

    /**
     * Método que obtiene la condición física del bien
     *
     * @author Henry Paredes (henryp2804@gmail.com)
     * @return Objeto con el registro relacionado al modelo AssetCategory
     */
    public function condition()
    {
        return $this->belongsTo('Modules\Asset\Models\AssetCondition', 'condition_id');
    }

    /**
     * Método que obtiene el Status de uso del bien
     *
     * @author Henry Paredes (henryp2804@gmail.com)
     * @return Objeto con el registro relacionado al modelo AssetCategory
     */
    public function status()
    {
        return $this->belongsTo('Modules\Asset\Models\AssetStatus', 'status_id');
    }

    /**
     * Método que obtiene la Función de uso del bien
     *
     * @author Henry Paredes (henryp2804@gmail.com)
     * @return Objeto con el registro relacionado al modelo AssetCategory
     */
    public function use()
    {
        return $this->belongsTo('Modules\Asset\Models\AssetUse', 'use_id');
    }

    
    /**
     * Método que obtiene el registro en inventario del bien
     *
     * @author Henry Paredes (henryp2804@gmail.com)
     * @return Objeto con el registro relacionado al modelo AssetInventary
     */
    public function inventary()
    {
        return $this->belongsTo('Modules\Asset\Models\AssetInventary', 'inventary_id');
    }

     /**
     * Método que obtiene los bienes de un registro de inventario
     *
     * @author Henry Paredes (henryp2804@gmail.com)
     * @return Objeto con el registro relacionado al modelo Asset
     */
    public function assetRequested()
    {
        return $this->hasOne('Modules\Asset\Models\AssetRequested');
    }

    /**
     * Método que obtiene los bienes asignados
     *
     * @author Henry Paredes (henryp2804@gmail.com)
     * @return Objeto con el registro relacionado al modelo AssetAsignationAsset
     */
    public function assetAsignation()
    {
        return $this->hasOne('Modules\Asset\Models\assetAsignationAsset');
    }

    /**
     * Método que obtiene los bienes desincorporados
     *
     * @author Henry Paredes (henryp2804@gmail.com)
     * @return Objeto con el registro relacionado al modelo AssetDisincorporationAsset
     */
    public function assetDisincorporation()
    {
        return $this->hasOne('Modules\Asset\Models\assetDisincorporationAsset');
    }

    /**
     *
     * @brief Método que genera un listado de elementos registrados para ser implementados en plantillas blade
     * 
     * @author Henry Paredes (henryp2804@gmail.com)
     * @return [array] Listado de bienes
     */
     public static function template_choices($filters = [])
    {
        $records = self::all();
        if ($filters) {
            foreach ($filters as $key => $value) {
                $records = $records->where($key, $value);
            }
        }
        $options = [];
        foreach ($records as $rec) {
            $options[$rec->id] = $rec->getDescription();
        }
        return $options;
    }


    public function scopeCodeClasification($query, $type, $category, $subcategory, $specific){
        if($type != ""){
            if ($category != "") {
                if ($subcategory != "") {
                    if ($specific != "") {
                        return $query->where('type_id',$type)
                                     ->where('category_id',$category)
                                     ->where('subcategory_id',$subcategory)
                                     ->where('specific_category_id',$specific);
                    }
                    return $query->where('type_id',$type)
                             ->where('category_id',$category)
                              ->where('subcategory_id',$subcategory);
                }
                return $query->where('type_id',$type)
                             ->where('category_id',$category);
            }
            return $query->where('type_id',$type);
        }
    }

    public function scopeDateClasification($query, $start, $end, $mes, $year){
        if (!is_null($start)){
            if (!is_null($end)){
                return $query->whereBetween("created_at",[$start,$end]); 
            }
            else{
                return $query->whereBetween("created_at",[$start,now()]);
            }
        }

        if (!is_null($mes)){
            if (!is_null($year)){
                return $query->whereMonth('created_at', $mes)
                            ->whereYear('created_at', $year);
            }else
                return $query->whereMonth('created_at', $mes);
        }
    }

    public function scopeRequest($query, $model, $marca, $serial, $id=10){
        if(trim($serial) != ""){
            if(trim($marca) != ""){
                if(trim($model) != ""){
                    $query->where('serial','like',"%$serial%")
                          ->where('marca','like',"%$marca%")
                          ->where('model','like',"%$model%")
                          ->where('status_id','=',$id);
                }
                $query->where('serial','like',"%$serial%")
                      ->where('marca','like',"%$marca%")
                      ->where('status_id','=',$id);
            }
            if(trim($model) != ""){
                    $query->where('serial','like',"%$serial%")
                          ->where('marca','like',"%$marca%")
                          ->where('model','like',"%$model%")
                          ->where('status_id','=',$id);
            }
            $query->where('serial','like',"%$serial%")
                  ->where('status_id','=',$id);
        }
        else if(trim($marca) != ""){
            if(trim($model) != ""){
                if(trim($serial) != ""){
                    $query->where('marca','like',"%$marca%")
                          ->where('model','like',"%$model%")
                          ->where('serial','like',"%serial%")
                          ->where('status_id','=',$id);

                }
                $query->where('marca','like',"%$marca%")
                      ->where('model','like',"%$model%")
                      ->where('status_id','=',$id);
            }
            if(trim($serial) != ""){
                    $query->where('marca','like',"%$marca%")
                          ->where('model','like',"%$model%")
                          ->where('serial','like',"%serial%")
                          ->where('status_id','=',$id);

            }
            $query->where('marca','like',"%$marca%")
            ->where('status_id','=',$id);
        }
        else if(trim($model) != ""){
            if(trim($serial) != ""){
                if(trim($marca) != ""){
                    $query->where('model','like',"%$model%")
                          ->where('serial','like',"%serial%")
                          ->where('marca','like',"%$marca%")
                          ->where('status_id','=',$id);

                }
                $query->where('model','like',"%$model%")
                      ->where('serial','like',"%$serial%")
                      ->where('status_id','=',$id);
            }
            if(trim($marca) != ""){
                    $query->where('model','like',"%$model%")
                          ->where('serial','like',"%serial%")
                          ->where('marca','like',"%$marca%")
                          ->where('status_id','=',$id);

            }
            $query->where('model','like',"%$model%")
                  ->where('status_id','=',$id);
        }
        $query->where('status_id','=',$id);
    }

    public function scopeAssetClasification($query, $case, $type=null, $category=null,$subcategory=null,$specific=null){
        if($case == 1)  //New Asignation
            $query->where('status_id','=',10);
        elseif ($case == 2) //Edit Asignation
            $query->whereIn('status_id',array(1,10));
        elseif ($case == 3) //New Request
            $query->where('status_id',10);
        elseif ($case == 4) //Edit Request
            $query->whereIn('status_id',array(6,10));
        elseif ($case == 5) //New Disincorporation
            $query->whereNotIn('status_id',array(6,7,8,9));
        elseif ($case == 6) //Edit Disincorporation
            $query->whereNotIn('status_id',array(6));

        if(!is_null($type)){
            if(!is_null($category)){
                if(!is_null($subcategory)){
                    if(!is_null($specific)){
                        $query->where('type_id','=',$type)
                              ->where('category_id','=',$category)
                              ->where('subcategory_id','=',$subcategory)
                              ->where('specific_category_id','=',$specific);
                    }
                    else
                        $query->where('type_id','=',$type)
                              ->where('category_id','=',$category)
                              ->where('subcategory_id','=',$subcategory);
                }
                else
                    $query->where('type_id','=',$type)
                              ->where('category_id','=',$category);
            }
            else
                $query->where('type_id','=',$type);
        }
    }


}
