<?php

namespace Modules\Warehouse\Imports;

use Modules\Warehouse\Models\WarehouseProduct;
use App\Models\MeasurementUnit;
use Maatwebsite\Excel\Concerns\ToModel;

class WarehouseProductImport extends \App\Imports\DataImport implements
    ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if (empty($row['identificador_de_la_unidad_de_medida']) || is_null($row['identificador_de_la_unidad_de_medida'])) {
            /** @var array Datos de la unidad de medida a la cual asociar la información del producto */
            $dataMeasurementUnit = [
                'name'        => $row['nombre_de_la_unidad_de_medida'],
                'acronym'     => $row['acronimo_de_la_unidad_de_medida'],
                'description' => $row['descripcion_de_la_unidad_de_medida']
            ];
            /** @var object Crea la nueva unidad de medida a ser asociado el producto */
            $measurementUnit = MeasurementUnit::create($dataMeasurementUnit);
        } else {
            /** @var object Contiene los datos de la unidad de medida asociada al producto */
            $measurementUnit = MeasurementUnit::find($row['identificador_de_la_unidad_de_medida']);
        }

        /** @var array Datos de los productos a importar */
        $data = [
            'name'                => $row['nombre_del_insumo'],
            'description'         => $row['descripcion_del_insumo'],
            'measurement_unit_id' => $measurementUnit->id
        ];

        if (!empty($row['identificador_del_insumo']) || !is_null($row['identificador_del_insumo'])) {
            return WarehouseProduct::updateOrCreate(
                ['id' => $row['identificador_del_insumo']],
                $data
            );
        }
        return new WarehouseProduct($data);
    }
}
