<table>
    <tbody>
        <tr>
            <td width="70%">
                <strong>Proveedor / Beneficiario:</strong> {{ $financePayOrder->nameSourceable->name }}
            </td>
            <td style="font-size: 1.3em">
                <strong>*** {{ $financePayOrder->currency->symbol }} {{ number_format($financePayOrder->amount, $financePayOrder->currency->decimal_places, ",", ".") }} ***</strong>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <strong>R.I.F. / C.I.:</strong> 
                {{ $financePayOrder->nameSourceable->rif ?? $financePayOrder->nameSourceable->dni }}
            </td>
        </tr>
        <tr>
            <td colspan="2">&#160;</td>
        </tr>
        <tr>
            <td colspan="2">
                CONCEPTO: {{ $financePayOrder->concept }}
            </td>
        </tr>
    </tbody>
</table>
<br>
<hr>
&#160;
<br>
<table style="font-size: 0.85em;">
    <thead>
        <tr>
            <th style="text-align: center; font-weight:bold;">MÉTODO DE PAGO</th>
            <th style="text-align: center; font-weight:bold;">BANCO DE ORIGEN</th>
            <th style="text-align: center; font-weight:bold;">Nº DE CUENTA</th>
            <th style="text-align: center; font-weight:bold;">MONTO DE ESTA OPERACIÓN</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="text-align: center;">{{ $financePayOrder->financePaymentMethod->name }}</td>
            <td style="text-align: center;">{{ $financePayOrder->financeBankAccount->financeBankingAgency->financeBank->name }}</td>
            <td style="text-align: center;">{{ $financePayOrder->financeBankAccount->formatedCccNumber }}</td>
            <td style="text-align: center;">{{ $financePayOrder->currency->symbol }} {{ number_format($financePayOrder->amount, $financePayOrder->currency->decimal_places, ",", ".") }}</td>
        </tr>
        <tr>
            <td colspan="4">&#160;</td>
        </tr>
        @if ($specificAction!==null)
            <tr>
                <td colspan="4" style="text-align: center">PROYECTO O ACCIÓN CENTRALIZADA</td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: center">{{ $specificAction['type'] }}: {{ $specificAction['code'] }}</td>
            </tr>
        @endif
    </tbody>
</table>
@if ($accountingEntry)
<br>
&#160;
<br>
    <h5 style="text-align: center">ASIENTO CONTABLE CONTABILIDAD</h5>
    <table style="border:solid 1px #000;font-size: 0.85em;background-color:#adbfd3; padding:10px;">
        <thead>
            <tr>
                <th style="text-align: center; font-weight:bold;">CÓDIGO CUENTA</th>
                <th style="text-align: center; font-weight:bold;">NOMBRE CUENTA</th>
                <th style="text-align: center; font-weight:bold;">DEBE</th>
                <th style="text-align: center; font-weight:bold;">HABER</th>
            </tr>
        </thead>
        <tbody>
            
            @foreach ($accountingEntry->accountingAccounts as $accountEntry)
                <tr>
                    <td style="text-align: center;">{{ $accountEntry->account->code }}</td>
                    <td style="text-align: center;">{{ $accountEntry->account->denomination }}</td>
                    <td style="text-align: right;">{{ number_format($accountEntry->debit, $financePayOrder->currency->decimal_places, ",", ".") }}</td>
                    <td style="text-align: right;">{{ number_format($accountEntry->assets, $financePayOrder->currency->decimal_places, ",", ".") }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2" style="font-weight:bold;text-align: right">TOTAL</td>
                <td style="font-weight:bold;text-align: right;border-top:solid 1px #000;">
                    {{ number_format($accountingEntry->tot_debit, $financePayOrder->currency->decimal_places, ",", ".") }}
                </td>
                <td style="font-weight:bold;text-align: right;border-top:solid 1px #000;">
                    {{ number_format($accountingEntry->tot_assets, $financePayOrder->currency->decimal_places, ",", ".") }}
                </td>
            </tr>
        </tbody>
    </table>
@endif
<br>
&#160;
<br>
<table>
    <tbody>
        <tr>
            <td colspan="2">
                OBSERVACIONES: {!! $financePayOrder->observations !!}
            </td>
        </tr>
    </tbody>
</table>