<table cellspacing="0" cellpadding="1" border="1">
    <tr align="C">
        <th width="15%">Solicitante</th>
        <th width="15%">Fecha de solicitud</th>
        <th width="25%">Productos solicitados</th>
        <th width="15%">Cantidad</th>
        <th width="15%">Valor Unitario</th>
        <th width="15%">Inventario despues de entrega</th>
    </tr>
    @foreach($fields as $field)
        <tr>
            <td width="15%"> {{ $field->warehouseRequest && $field->warehouseRequest->payrollStaff
                ? $field->warehouseRequest->payrollStaff->full_name
                : $field->warehouseRequest->department->name }} </td>
            <td width="15%"> {{ date_format($field->created_at, "d/m/Y") }} </td>
            <td width="25%"> {{ $field->warehouseInventoryProduct
                ? $field->warehouseInventoryProduct->warehouseProduct->name
                : $field->warehouseProduct->name  }} </td>
            <td width="15%"> {{ $field->quantity }} </td>
            <td width="15%"> {{ $field->warehouseInventoryProduct
                ? ($field->warehouseInventoryProduct->unit_value .' '. $field->warehouseInventoryProduct->currency->symbol)
                : ($field->unit_value .' '. $field->currency->symbol) }} </td>
            <td width="15%"> {{ $field->warehouseInventoryProduct
                ? $field->warehouseInventoryProduct->exist - $field->quantity
                : $field->exist - $field->quantity }} </td>
        </tr>
    @endforeach
</table>