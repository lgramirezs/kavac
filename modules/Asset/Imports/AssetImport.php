<?php

namespace Modules\Asset\Imports;

use Modules\Asset\Models\Asset;

use Modules\Asset\Models\AssetType;
use Modules\Asset\Models\AssetCategory;
use Modules\Asset\Models\AssetSubcategory;
use Modules\Asset\Models\AssetSpecificCategory;
use Modules\Asset\Models\AssetCondition;
use Modules\Asset\Models\AssetAcquisitionType;
use Modules\Asset\Models\AssetStatus;
use Modules\Asset\Models\AssetUseFunction;
use App\Models\Currency;
use App\Models\Institution;
use App\Models\Parish;
use Maatwebsite\Excel\Concerns\ToModel;

class AssetImport extends \App\Imports\DataImport implements
    ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        /** @var array Datos del tipo de bien al cual asociar la información del bien */
        $dataAssetType = [
            'name' => $row['tipo_de_bien']
        ];

        if (empty($row['id_tipo_de_bien']) || is_null($row['id_tipo_de_bien'])) {
            /** @var object Crea el nuevo tipo de bien a ser asociado al bien */
            $assetType = AssetType::updateOrCreate($dataAssetType, $dataAssetType);
        } else {
            /** @var object Contiene los datos del tipo de bien asociado al bien */
            $assetType = AssetType::find($row['id_tipo_de_bien']);
        }

        /** @var array Datos de la categoría al cual asociar la información del bien */
        $dataAssetCategory = [
            'name'          => $row['categoria'],
            'asset_type_id' => $assetType->id
        ];

        if (empty($row['id_categoria']) || is_null($row['id_categoria'])) {
            /** @var object Crea la nueva categoría a ser asociada al bien */
            $assetCategory = AssetCategory::updateOrCreate($dataAssetCategory, $dataAssetCategory);
        } else {
            /** @var object Contiene los datos de la categoría asociada al bien */
            $assetCategory = AssetCategory::find($row['id_categoria']);
        }

        /** @var array Datos de la sub-categoría al cual asociar la información del bien */
        $dataAssetSubcategory = [
            'name'              => $row['subcategoria'],
            'asset_category_id' => $assetCategory->id
        ];

        if (empty($row['id_subcategoria']) || is_null($row['id_subcategoria'])) {
            /** @var object Crea la nueva sub-categoría a ser asociada al bien */
            $assetSubcategory = AssetSubcategory::updateOrCreate($dataAssetSubcategory, $dataAssetSubcategory);
        } else {
            /** @var object Contiene los datos de la subcategoría asociada al bien */
            $assetSubcategory = AssetSubcategory::find($row['id_subcategoria']);
        }

        /** @var array Datos de la categoría específica al cual asociar la información del bien */
        $dataAssetSpecificCategory = [
            'name'                 => $row['categoria_especifica'],
            'asset_subcategory_id' => $assetSubcategory->id
        ];

        if (empty($row['id_categoria_especifica']) || is_null($row['id_categoria_especifica'])) {
            /** @var object Crea la nueva categoría específica a ser asociada al bien */
            $assetSpecificCategory = AssetSpecificCategory::updateOrCreate($dataAssetSpecificCategory, $dataAssetSpecificCategory);
        } else {
            /** @var object Contiene los datos de la categoría específica asociada al bien */
            $assetSpecificCategory = AssetSpecificCategory::find($row['id_categoria_especifica']);
        }

        /** @var array Datos de la condición física al cual asociar la información del bien */
        $dataAssetCondition = [
            'name' => $row['condicion_del_bien']
        ];

        if (empty($row['id_condicion_del_bien']) || is_null($row['id_condicion_del_bien'])) {
            /** @var object Crea la nueva condición física a ser asociada al bien */
            $assetCondition = AssetCondition::updateOrCreate($dataAssetCondition, $dataAssetCondition);
        } else {
            /** @var object Contiene los datos de la condición física asociada al bien */
            $assetCondition = AssetCondition::find($row['id_condicion_del_bien']);
        }

        if (!(empty($row['tipo_de_adquisicion']) || is_null($row['tipo_de_adquisicion']))){
            /** @var array Datos del tipo de adquisición al cual asociar la información del bien */
            $dataAssetAcquisitionType = [
                'name' => $row['tipo_de_adquisicion']
            ];

            if (empty($row['id_tipo_de_adquisicion']) || is_null($row['id_tipo_de_adquisicion'])) {
                /** @var object Crea el nuevo tipo de adquisición a ser asociada al bien */
                $assetAcquisitionType = AssetAcquisitionType::updateOrCreate($dataAssetAcquisitionType, $dataAssetAcquisitionType);
            } else {
                /** @var object Contiene los datos del tipo de adquisición asociado al bien */
                $assetAcquisitionType = AssetAcquisitionType::find($row['id_tipo_de_adquisicion']);
            }
        }
        

        /** @var array Datos del estatus de uso al cual asociar la información del bien */
        $dataAssetStatus = [
            'name' => $row['estatus_de_uso']
        ];

        if (empty($row['id_estatus_de_uso']) || is_null($row['id_estatus_de_uso'])) {
            /** @var object Crea el nuevo estatus de uso a ser asociada al bien */
            $assetStatus = AssetStatus::updateOrCreate($dataAssetStatus, $dataAssetStatus);
        } else {
            /** @var object Contiene los datos del estatus de uso asociado al bien */
            $assetStatus = AssetStatus::find($row['id_estatus_de_uso']);
        }

        /** @var array Datos de la moneda al cual asociar la información del bien */
        $dataCurrency = [
            'name' => $row['moneda']
        ];

        if (empty($row['id_moneda']) || is_null($row['id_moneda'])) {
            /** @var object Crea la nueva moneda a ser asociada al bien */
            $currency = Currency::updateOrCreate($dataCurrency, $dataCurrency);
        } else {
            /** @var object Contiene los datos de la moneda asociada al bien */
            $currency = Currency::find($row['id_moneda']);
        }

        /** @var array Datos de la institución al cual asociar la información del bien */
        $dataInstitution = [
            'name' => $row['institucion']
        ];

        if (empty($row['id_institucion']) || is_null($row['id_institucion'])) {
            /** @var object Contiene los datos de la moneda asociada al bien */
            $institution = Institution::updateOrCreate($dataInstitution, $dataInstitution);
        } else {
            /** @var object Contiene los datos de la institución asociada al bien */
            $institution = Institution::find($row['id_institucion']);
        }

        if (!(empty($row['funcion_de_uso']) || is_null($row['funcion_de_uso']))) {
            /** @var array Datos de la función de uso al cual asociar la información del bien */
            $dataUseFunction = [
                'name' => $row['funcion_de_uso']
            ];

            if (empty($row['id_funcion_de_uso']) || is_null($row['id_funcion_de_uso'])) {
                /** @var object Crea la nueva función de uso a ser asociada al bien */
                $assetUseFunction = AssetUseFunction::updateOrCreate($dataUseFunction, $dataUseFunction);
            } else {
                /** @var object Contiene los datos de la función de uso asociada al bien */
                $assetUseFunction = AssetUseFunction::find($row['id_funcion_de_uso']);
            }
        }

        if (!(empty($row['parroquia']) || is_null($row['parroquia']))) {

            /** @var array Datos de la parroquia al cual asociar la información del bien */
            $dataParish = [
                'name' => $row['parroquia']
            ];

            if (empty($row['id_parroquia']) || is_null($row['id_parroquia'])) {
                /** @var object Crea la nueva parroquia a ser asociada al bien */
                $parish = Parish::updateOrCreate($dataParish, $dataParish);
            } else {
                /** @var object Contiene los datos de la parroquia asociada al bien */
                $parish = Parish::find($row['id_parroquia']);
            }
        }
        
        /** @var array Datos de los bienes a importar */
        $data = [
            'asset_type_id'              => $assetType->id,
            'asset_category_id'          => $assetCategory->id,
            'asset_subcategory_id'       => $assetSubcategory->id,
            'asset_specific_category_id' => $assetSpecificCategory->id,
            'specifications'             => $row['especificaciones'],
            'asset_condition_id'         => $assetCondition->id,
            'asset_acquisition_type_id'  => $assetAcquisitionType->id ?? NULL,
            'acquisition_date'           => $row['fecha_de_adquisicion'],
            'asset_status_id'            => $assetStatus->id,
            'serial'                     => $row['serial'],
            'marca'                      => $row['marca'],
            'model'                      => $row['modelo'],
            'value'                      => $row['valor'],
            'currency_id'                => $currency->id,
            'institution_id'             => $institution->id,
            'asset_use_function_id'      => $assetUseFunction->id ?? null,
            'parish_id'                  => $parish->id ?? null,
            'address'                    => $row['direccion']
        ];

        $asset = Asset::withTrashed()->updateOrCreate(
            ['id' => $row['id']],
            $data
        );
        $asset->inventory_serial = $asset->getCode();
        $asset->deleted_at = null;
        $asset->save();
        return $asset;
    }
}
