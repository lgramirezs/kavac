{{-- Reporte de historial de inventario de bienes --}}
@foreach($assets as $asset)
    <h3 align="center">Bienes registrados</h3>
    <table cellspacing="0" cellpadding="1" border="0.5">
        <thead>
            <tr>
                <th width="20%" style="font-size:9rem; background-color: #BDBDBD;" align="center">Organización</th>
                <th width="20%" style="font-size:9rem; background-color: #BDBDBD;" align="center">Condición física</th>
                <th width="30%" style="font-size:9rem; background-color: #BDBDBD;" align="center">Estatus de uso</th>
                <th width="10%" style="font-size:9rem; background-color: #BDBDBD;" align="center">Serial</th>
                <th width="10%" style="font-size:9rem; background-color: #BDBDBD;" align="center">Marca</th>
                <th width="10%" style="font-size:9rem; background-color: #BDBDBD;" align="center">Modelo</th>
            </tr>
        </thead>
        <tbody>
            @foreach($asset->assetInventoryAssets as $assetInventoryAsset)
                <tr>
                    <td width="20%" style="font-size: 8rem;" align="center">
                        {{ $assetInventoryAsset['asset']['institution']['name'] }}
                    </td>
                    <td width="20%" style="font-size:9rem;" align="center">
                        {{ $assetInventoryAsset['asset']['assetCondition']['name'] }}
                    </td>
                    <td width="30%" style="font-size:9rem;" align="center">
                        {{ $assetInventoryAsset['asset']['assetStatus']['name'] }}
                    </td>
                    <td width="10%" style="font-size:9rem;" align="center">
                        {{ $assetInventoryAsset['asset']['serial'] }}
                    </td>
                    <td width="10%" style="font-size:9rem;" align="center">
                        {{ $assetInventoryAsset['asset']['marca'] }}
                    </td>
                    <td width="10%" style="font-size:9rem;" align="center">
                        {{ $assetInventoryAsset['asset']['model'] }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endforeach
@php
    $showAsignedTable = false;

    foreach($assets as $asset) {
        foreach($asset->assetInventoryAssets as $assetInventoryAsset) {
            if ($assetInventoryAsset->asset->assetAsignationAsset) {
                $showAsignedTable = true;
            }
        }
    }
@endphp
@if ($showAsignedTable)
    @foreach($assets as $asset)
        <h3 align="center">Bienes asignados</h3>
        <table cellspacing="0" cellpadding="1" border="0.5">
            <thead>
                <tr>
                    <th width="20%" style="font-size:9rem; background-color: #BDBDBD;" align="center">Organización</th>
                    <th width="20%" style="font-size:9rem; background-color: #BDBDBD;" align="center">Trabajador</th>
                    <th width="10%" style="font-size:9rem; background-color: #BDBDBD;" align="center">Condición física</th>
                    <th width="20%" style="font-size:9rem; background-color: #BDBDBD;" align="center">Estatus de uso</th>
                    <th width="10%" style="font-size:9rem; background-color: #BDBDBD;" align="center">Serial</th>
                    <th width="10%" style="font-size:9rem; background-color: #BDBDBD;" align="center">Marca</th>
                    <th width="10%" style="font-size:9rem; background-color: #BDBDBD;" align="center">Modelo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($asset->assetInventoryAssets as $assetInventoryAsset)
                    @if($assetInventoryAsset->asset->assetAsignationAsset)
                        <tr>
                            <td width="20%" style="font-size: 8rem;" align="center">
                                {{ $assetInventoryAsset['asset']['institution']['name'] }}
                            </td>
                            <td width="20%" style="font-size: 8rem;" align="center">
                                {{ $assetInventoryAsset['asset']['assetAsignationAsset']['assetAsignation'] ? $assetInventoryAsset['asset']['assetAsignationAsset']['assetAsignation']['payrollStaff']['first_name'] . ' ' .  $assetInventoryAsset['asset']['assetAsignationAsset']['assetAsignation']['payrollStaff']['last_name'] : 'N/A'}}
                            </td>
                            <td width="10%" style="font-size:9rem;" align="center">
                                {{ $assetInventoryAsset['asset']['assetCondition']['name'] }}
                            </td>
                            <td width="20%" style="font-size:9rem;" align="center">
                                {{ $assetInventoryAsset['asset']['assetStatus']['name'] }}
                            </td>
                            <td width="10%" style="font-size:9rem;" align="center">
                                {{ $assetInventoryAsset['asset']['serial'] }}
                            </td>
                            <td width="10%" style="font-size:9rem;" align="center">
                                {{ $assetInventoryAsset['asset']['marca'] }}
                            </td>
                            <td width="10%" style="font-size:9rem;" align="center">
                                {{ $assetInventoryAsset['asset']['model'] }}
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    @endforeach
@endif
@php
    $showTable = false;
    foreach($assets as $asset) {
        foreach($asset->assetInventoryAssets as $assetInventoryAsset) {
            if ($assetInventoryAsset['asset']['asset_status_id'] == 5 ||
                $assetInventoryAsset['asset']['asset_status_id'] == 7 ||
                $assetInventoryAsset['asset']['asset_status_id'] == 8 ||
                $assetInventoryAsset['asset']['asset_status_id'] == 9) {
                $showTable = true;
            }
        }
    }
@endphp
@if($showTable)
    @foreach($assets as $asset)
        <h3 align="center">Bienes desincorporados</h3>
        <table cellspacing="0" cellpadding="1" border="0.5">
            <thead>
                <tr>
                    <th width="20%" style="font-size:9rem; background-color: #BDBDBD;" align="center">Organización</th>
                    <th width="10%" style="font-size:9rem; background-color: #BDBDBD;" align="center">Condición física</th>
                    <th width="20%" style="font-size:9rem; background-color: #BDBDBD;" align="center">Estatus de uso</th>
                    <th width="10%" style="font-size:9rem; background-color: #BDBDBD;" align="center">Serial</th>
                    <th width="10%" style="font-size:9rem; background-color: #BDBDBD;" align="center">Marca</th>
                    <th width="10%" style="font-size:9rem; background-color: #BDBDBD;" align="center">Modelo</th>
                    <th width="20%" style="font-size:9rem; background-color: #BDBDBD;" align="center">Motivo de desincorporación</th>
                </tr>
            </thead>
            <tbody>
                @foreach($asset->assetInventoryAssets as $assetInventoryAsset)
                    @if($assetInventoryAsset['asset']['asset_status_id'] == 5 ||
                        $assetInventoryAsset['asset']['asset_status_id'] == 7 ||
                        $assetInventoryAsset['asset']['asset_status_id'] == 8 ||
                        $assetInventoryAsset['asset']['asset_status_id'] == 9)
                        <tr>
                            <td width="20%" style="font-size: 8rem;" align="center">
                                {{ $assetInventoryAsset['asset']['institution']['name'] }}
                            </td>
                            <td width="10%" style="font-size:9rem;" align="center">
                                {{ $assetInventoryAsset['asset']['assetCondition']['name'] }}
                            </td>
                            <td width="20%" style="font-size:9rem;" align="center">
                                {{ $assetInventoryAsset['asset']['assetStatus']['name'] }}
                            </td>
                            <td width="10%" style="font-size:9rem;" align="center">
                                {{ $assetInventoryAsset['asset']['serial'] }}
                            </td>
                            <td width="10%" style="font-size:9rem;" align="center">
                                {{ $assetInventoryAsset['asset']['marca'] }}
                            </td>
                            <td width="10%" style="font-size:9rem;" align="center">
                                {{ $assetInventoryAsset['asset']['model'] }}
                            </td>
                            <td width="20%" style="font-size: 8rem;" align="center">
                                {{ $assetInventoryAsset['asset']['assetDisincorporationAsset'] && $assetInventoryAsset['asset']['assetDisincorporationAsset']['assetDisincorporation'] ? $assetInventoryAsset['asset']['assetDisincorporationAsset']['assetDisincorporation']['assetDisincorporationMotive']['name'] : $assetInventoryAsset['asset']['assetStatus']['name'] }}
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    @endforeach
@endif