<table cellspacing="0" cellpadding="1" border="1">
    <tr align="C">
        <th width="12%">Código</th>
        <th width="12%">Producto</th>
        <th width="25%">Descripción</th>
        <th width="12%">Almacén</th>
        <th width="12%">Existencia</th>
        <th width="12%">Reservados</th>
        <th width="12%">Valor Unitario</th>
    </tr>

    @foreach($fields as $field)
        <tr>
            <td width="12%"> {{ $field->warehouseInventoryProduct
                ? $field->warehouseInventoryProduct->code
                : $field->code }} </td>
            <td width="12%"> {{ $field->warehouseInventoryProduct
                ? $field->warehouseInventoryProduct->warehouseProduct->name
                : $field->warehouseProduct->name  }} </td>
            <td width="25%"> {{ $field->warehouseInventoryProduct
                ? strip_tags($field->warehouseInventoryProduct->warehouseProduct->description) 
                : strip_tags($field->warehouseProduct->description)   }} </td>
            <td width="12%"> {{ $field->warehouseInventoryProduct
                ? $field->warehouseInventoryProduct->warehouseInstitutionWarehouse->warehouse->name
                : $field->warehouseInstitutionWarehouse->warehouse->name }} </td>
            <td width="12%"> {{ $field->warehouseInventoryProduct
                ? $field->warehouseInventoryProduct->exist
                : $field->exist }} </td>
            <td width="12%"> {{ $field->warehouseInventoryProduct
                ? $field->warehouseInventoryProduct->reserved
                : $field->reserved }} </td>
            <td width="12%"> {{ $field->warehouseInventoryProduct
                ? ($field->warehouseInventoryProduct->unit_value .' '. $field->warehouseInventoryProduct->currency->symbol)
                : ($field->unit_value .' '. $field->currency->symbol) }} </td>
        </tr>
    @endforeach
</table>
