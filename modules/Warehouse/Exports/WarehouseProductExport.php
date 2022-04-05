<?php

namespace Modules\Warehouse\Exports;

use Modules\Warehouse\Models\WarehouseProduct;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;

class WarehouseProductExport extends \App\Exports\DataExport implements
    WithHeadings,
    ShouldAutoSize,
    WithMapping
{

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return WarehouseProduct::all();
    }

    /**
     * Establece las cabeceras de los datos en el archivo a exportar
     *
     * @method    headings
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @author    Yennifer Ramirez <yramirez@cenditel.gob.ve>
     * @return    array    Arreglo con las cabeceras de los datos a exportar
     */
    public function headings(): array
    {
        return [
            'id',
            'Nombre del insumo',
            'Descripción',
            'Identificador de la unidad de medida',
            'Nombre de la unidad de medida',
            'Acrónimo de la unidad de medida',
            'Descripción de la unidad de medida'
        ];
    }

    /**
     * Establece las columnas que van a ser exportadas
     *
     * @method    map
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @author    Yennifer Ramirez <yramirez@cenditel.gob.ve>
     *
     * @param     object    $warehouseProduct    Objeto con las propiedades del modelo a exportar
     *
     * @return    array     Arreglo con los campos estrictamente a ser exportados
     */
    public function map($warehouseProduct): array
    {
        return [
            $warehouseProduct->id,
            $warehouseProduct->name,
            strip_tags($warehouseProduct->description),
            $warehouseProduct->measurementUnit->id,
            $warehouseProduct->measurementUnit->name,
            $warehouseProduct->measurementUnit->acronym,
            $warehouseProduct->measurementUnit->description
        ];
    }
}
