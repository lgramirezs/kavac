<table cellspacing="0" cellpadding="1" border="1">
    <tr align="C">
        <th width="40%">Producto</th>
        <th width="15%">Mínimo</th>
        <th width="15%">Existencia actual</th>
        <th width="30%">Detalle</th>
    </tr>

    @foreach($fields as $field)
        <tr>
            <td width="40%"> {{ $field->warehouseInventoryProduct
                ? $field->warehouseInventoryProduct->warehouseProduct
                    ? $field->warehouseInventoryProduct->warehouseProduct->name
                    : ''
                : ''  }} </td>

            <td width="15%"> {{ $field->minimum }} </td>

            <td width="15%"> {{ $field->warehouseInventoryProduct
                ? $field->warehouseInventoryProduct->exist
                : '' }} </td>

            <td width="30%">
                <span>
                    @if ($field->minimum == $field->warehouseInventoryProduct->exist)
                            El artículo llegó al mínimo de existencia
                    
                    
                    @elseif ($field->warehouseInventoryProduct->exist == 0)
                            
                            No hay existencia en inventario
                        
                       
                    @elseif ($field->minimum > $field->warehouseInventoryProduct->exist)                
                            El artículo sobrepasa el mínimo de existencia
                            

                    @elseif ($field->minimum < $field->warehouseInventoryProduct->exist)
                            Hay existencia del artículo en inventario
                    @endif     

                </span>

            </td>    
                        
        </tr>
    @endforeach
</table>
