<?php

namespace Modules\Asset\Exports;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Modules\Asset\Models\Asset;
use App\Models\User;

use Illuminate\Support\Facades\Log;
class AssetExport extends \App\Exports\DataExport implements
WithHeadings,
ShouldAutoSize,
WithMapping
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    { 
        $auth = auth()->user();

        $user = User::where('id', auth()->user()->id)->with('profile')->first();
    


       

        if (
            $user !== null && !is_null($user->profile) &&
             !is_null($user->profile->institution_id) && $user->profile->institution_id != ""
        ) {
     

            return Asset::where('institution_id', $user->profile->institution_id)->with(
                [
                    'assetType',
                    'assetCategory',
                    'assetSubcategory',
                    'assetSpecificCategory',
                    'assetAcquisitionType',
                    'assetCondition',
                    'assetStatus',
                    'assetUseFunction',
                    'institution',
                    'parish' => function ($query) {
                        $query->with(['municipality' => function ($query) {
                            $query->with(['estate' => function ($query) {
                                $query->with('country')->get();
                            }])->get();
                        }])->get();
                    },
                ]
            )->get();

        } else {
         

            return Asset::with(
                [
                    'assetType',
                    'assetCategory',
                    'assetSubcategory',
                    'assetSpecificCategory',
                    'assetAcquisitionType',
                    'assetCondition',
                    'assetStatus',
                    'assetUseFunction',
                    'institution',
                    'parish' => function ($query) {
                        $query->with(['municipality' => function ($query) {
                            $query->with(['estate' => function ($query) {
                                $query->with('country')->get();
                            }])->get();
                        }])->get();
                    },
                ]
            )->get();

        }

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
            'ID',
            'ID Tipo de bien',
            'Tipo de bien',
            'ID Categoría',
            'Categoría',
            'ID Subcategoria',
            'Subcategoria',
            'ID Categoria específica',
            'Categoria específica',
            'ID Condición del bien',
            'Condición del bien',
            'ID Tipo de adquisición',
            'Tipo de adquisición',
            'Fecha de adquisición',
            'ID Estatus de uso',
            'Estatus de uso',
            'Serial',
            'Marca',
            'Modelo',
            'Serial de inventario',
            'ID Moneda',
            'Moneda',
            'Valor',
            'ID Función de uso',
            'Función de uso',
            'Especificaciones',
            'Dirección',
            'ID Parroquia',
            'Parroquia',
            'ID Institución',
            'Institución',
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
            $asset->id,
            $asset->assetType->id ?? '',
            $asset->assetType->name ?? '',
            $asset->assetCategory->id ?? '',
            $asset->assetCategory->name ?? '',
            $asset->assetSubcategory->id ?? '',
            $asset->assetSubcategory->name ?? '',
            $asset->assetSpecificCategory->id ?? '',
            $asset->assetSpecificCategory->name ?? '',
            $asset->assetCondition->id ?? '',
            $asset->assetCondition->name ?? '',
            $asset->assetAcquisitionType->id ?? '',
            $asset->assetAcquisitionType->name ?? '',
            $asset->acquisition_date,
            $asset->assetStatus->id ?? '',
            $asset->assetStatus->name ?? '',
            $asset->serial,
            $asset->marca,
            $asset->model,
            $asset->inventory_serial,
            $asset->currency->id ?? '',
            $asset->currency->name ?? '',
            $asset->value,
            $asset->assetUseFunction->id ?? '',
            $asset->assetUseFunction->name ?? '',
            strip_tags($asset->specifications) ?? '',
            strip_tags($asset->address) ?? '',
            $asset->parish->id ?? '',
            $asset->parish->name ?? '',
            $asset->institution->id ?? '',
            $asset->institution->name ?? '',
        ];
    }
}
