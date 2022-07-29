<h2 align="center" style="font-size: 8rem;">Acta de {{$request['action']}} de Bienes </h2>

<table style="font-size: 8rem;"  cellpadding="6" >
    
    <tr>
        <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3">
            <strong>Fecha de Asignación: </strong>{{$request['created_at']}}
        </td>
    </tr>
    <tr>
        <td colspan="3">&nbsp;</td>
    </tr>
    
    <tr>
        <td colspan="3">
            <strong>Organización: </strong>{{$request['institution']}}
        </td>
        
    </tr>
    
    <tr>
        <td colspan="3">
            <strong>Ubicación Geográfica/Física </strong>
        </td>
    </tr>
    <tr>
        <td>
            <strong>Estado: </strong>{{$request['estate']}}
        </td>
        <td>
            <strong>Municipio: </strong>{{$request['municipality']}}
        </td>
        <td>
            <strong>Dirección: </strong>{{$request['address']}}
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <strong>Ejercicio Fiscal: </strong>{{$request['fiscal_year']}}
        </td>
    </tr>
    
    <tr>
        <td colspan="3">
            <strong>Responsable por uso</strong>
        </td>
    </tr>
    <tr>
        <td>
            <strong>Apellidos: </strong>{{$request['last_name']}}
        </td>
        <td>
            <strong>Nombres: </strong>{{$request['first_name']}}
        </td>
        <td>
            <strong>Cédula de identidad: </strong>{{$request['id_number']}}
        </td>
    </tr>
    <tr>
        <td>
            <strong>Departamento: </strong>{{$request['department']}}
        </td>
        <td>
            <strong>Cargo: </strong>{{$request['payroll_position']}}
        </td>
        <td>
            <strong>Lugar de Ubicación: </strong>{{$request['location_place']}}
        </td>
    </tr>
    <tr>
        <td colspan="3">&nbsp;</td>
    </tr>
    
    <tr>
        <td colspan="3">
            <strong>Bienes Asignados: </strong>
        </td>
    </tr>

    <table cellspacing="0" cellpadding="1" border="0.5" style="font-size: 8rem;">
        <thead>
            <tr>
                <th style="background-color: #BDBDBD;" align="center">Código</th>
                <th style="background-color: #BDBDBD;" align="center">Organización</th>
                <th style="background-color: #BDBDBD;" align="center">Especificaciones</th>
                <th style="background-color: #BDBDBD;" align="center">Condición física</th>
                <th style="background-color: #BDBDBD;" align="center">Marca</th>
                <th style="background-color: #BDBDBD;" align="center">Modelo</th>
                <th style="background-color: #BDBDBD;" align="center">Serial</th>
                <th style="background-color: #BDBDBD;" align="center">Color</th>
                <th style="background-color: #BDBDBD;" align="center">Código de bien organizacional</th>
            </tr>
        </thead>
        <tbody>
            
            @foreach($request['assets'] as $asset)
                <tr>
                <td align="center">
                        {{ $asset['asset']['inventory_serial'] ?  $asset['asset']['inventory_serial']:'' }}
                    </td>
                    <td align="center">
                        {{ $asset['asset']['institution']['name'] }}
                    </td>
                    <td align="center">
                        {{ str_replace("<p>", "", str_replace("</p>","",$asset['asset']['specifications'])) ?
                            str_replace("<p>", "", str_replace("</p>","",$asset['asset']['specifications'])) :
                            '' }}
                    </td>
                    <td align="center">
                        {{ $asset['asset']['asset_condition']['name']}}
                    </td>
                    <td align="center">
                        {{ $asset['asset']['marca'] ?
                            $asset['asset']['marca'] :
                            '' }}
                    </td>
                    <td align="center">
                        {{ $asset['asset']['model'] ?
                            $asset['asset']['model'] :
                            '' }}
                    </td>
                    <td align="center">
                        {{ $asset['asset']['serial'] ?
                            $asset['asset']['serial'] :
                            '' }}
                    </td>
                    <td align="center">
                        {{ $asset['asset']['color'] ?
                            $asset['asset']['color'] :
                            '' }}
                    </td>
                    <td align="center">
                        {{ $asset['asset']['asset_institutional_code'] ?
                            $asset['asset']['asset_institutional_code'] :
                            '' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <tr>
        <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3">
            <strong>Autorizado por: </strong>{{$request['authorized_by']}}
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <strong>Conformado por: </strong>{{$request['formed_by']}}
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <strong>Entregado por:</strong>{{$request['delivered_by']}}
        </td>
    </tr>
</table>
    

    
    
