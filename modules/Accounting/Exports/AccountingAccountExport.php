<?php

namespace Modules\Accounting\Exports;

use Modules\Accounting\Models\AccountingAccount;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;

class AccountingAccountExport extends \App\Exports\DataExport implements
    WithHeadings,
    ShouldAutoSize,
    WithMapping
{

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return AccountingAccount::get();
    }

    /**
     * Establece las cabeceras de los datos en el archivo a exportar
     *
     * @method    headings
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     *
     * @return    array    Arreglo con las cabeceras de los datos a exportar
     */
    public function headings(): array
    {
        return [
            'CÓDIGO',
            'DENOMINACION',
            'ACTIVA'
        ];
    }

    /**
     * Establece las columnas que van a ser exportadas
     *
     * @method    map
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     *
     * @param     object    $asset    Objeto con las propiedades del modelo a exportar
     *
     * @return    array     Arreglo con los campos estrictamente a ser exportados
     */
    public function map($asset): array
    {
        return [
            $asset->getCodeAttribute(),
            $asset->denomination,
            $asset->active ? 'SI' : 'NO',
        ];
    }
}
