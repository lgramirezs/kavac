<table cellspacing="1" cellpadding="0" border="0">
  <thead>
  </thead>
  <tbody>
    <tr>
      <td width="60%">
        Descripción de contratación: <strong>{{ $record->description }}</strong>
      </td>
      <td width="40%">
        Nro de contratación {{ $record->code }}
      </td>
    </tr>
    <tr>
      <td width="50%">
        Unidad contratante: <strong>{{ $record->contratingDepartment->name }}</strong>
      </td>
      <td width="50%">
        Unidad usuaria: <strong>{{ $record->userDepartment->name }}</strong>
      </td>
    </tr>
    <br>
    <tr>
      <th width="100%" style="text-align: center">
        <h3><strong>DATOS DEL PROVEEDOR</strong></h3>
      </th>
    </tr>
    <br>
    <tr>
      <td width="50%">
        Nombre o Razón social: <strong>{{ $record->purchaseSupplier->name }}</strong>
      </td>
      <td width="50%">
        R.I.F: <strong>{{ $record->purchaseSupplier->rif }}</strong>
      </td>
    </tr>
    <tr>
      <td width="100%" style="display: inline">
        Dirección Físcal: <strong>{!! $record->purchaseSupplier->direction !!}</strong>
      </td>
    </tr>
    
  </tbody>
</table>

{{$record}}