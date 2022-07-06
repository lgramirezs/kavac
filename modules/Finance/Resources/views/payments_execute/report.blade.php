<table>
    <tbody>
        <tr>
            <td width="70%">
                <strong>Proveedor / Beneficiario:</strong> {{ $payOrder->nameSourceable->name }}
            </td>
            <td style="font-size: 1.3em">
                <strong>*** {{ $payOrder->currency->symbol }} {{ number_format($financePaymentExecute->paid_amount, $payOrder->currency->decimal_places, ",", ".") }} ***</strong>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <strong>R.I.F. / C.I.:</strong> 
                {{ $payOrder->nameSourceable->rif ?? $payOrder->nameSourceable->dni }}
            </td>
        </tr>
        <tr>
            <td colspan="2">&#160;</td>
        </tr>
        <tr>
            <td colspan="2" class="text-justify font-weight-bold" style="font-size: 1.2em;font-weight:bold">
                {{ convertirNumeros(number_format($financePaymentExecute->paid_amount, $payOrder->currency->decimal_places, ".", "")) }}
            </td>
        </tr>
        <tr>
            <td colspan="2">&#160;</td>
        </tr>
    </tbody>
</table>
<br>&#160;<br>
<table style="font-size: 0.85em;">
    <thead>
        <tr>
            <th style="text-align: center; font-weight:bold;">MÉTODO DE PAGO</th>
            <th style="text-align: center; font-weight:bold;">BANCO DE ORIGEN</th>
            <th style="text-align: center; font-weight:bold;">REFERENCIA Nº {{ $financePaymentExecute->code }}</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="text-align: center;">{{ $payOrder->financePaymentMethod->name }}</td>
            <td style="text-align: center;">{{ $payOrder->financeBankAccount->financeBankingAgency->financeBank->name }}</td>
            <td style="text-align: center;">{{ $financePaymentExecute->paid_at->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td colspan="4">&#160;</td>
        </tr>
    </tbody>
</table>
<br>&#160;<br>
<table>
    <tbody>
        <tr>
            <td>
                COD. ORDEN DE PAGO: <b>{{ $payOrder->code }}</b>
            </td>
        </tr>
        <tr>
            <td>
                CONCEPTO: {{ $payOrder->concept }}
            </td>
        </tr>
    </tbody>
</table>
<br>
<hr>
&#160;
<br>
@if ($financePaymentExecute->has('financePaymentDeductions'))
    <h5 style="text-align: center">RETENCIONES</h5>
    <table style="border:solid 1px #000;font-size: 0.85em;background-color:#adbfd3; padding:10px;">
        <thead>
            <tr>
                <th style="text-align: center; font-weight:bold;">TIPO DE RETENCIÓN</th>
                <th style="text-align: center; font-weight:bold;">MONTO</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($financePaymentExecute->financePaymentDeductions as $paymentDeduction)
                <tr>
                    <td>{{ $paymentDeduction->deduction->name }}</td>
                    <td style="text-align: right">{{ $paymentDeduction->amount }}</td>
                </tr>
            @endforeach
            <tr>
                <td style="text-align: right;"><b>TOTAL RETENCIONES:</b></td>
                <td style="text-align: right"><b>{{ $financePaymentExecute->deduction_amount }}</b></td>
            </tr>
        </tbody>
    </table>
    <br>
    &#160;
    <br>
@endif
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
                    <td style="text-align: right;">{{ number_format($accountEntry->debit, $payOrder->currency->decimal_places, ",", ".") }}</td>
                    <td style="text-align: right;">{{ number_format($accountEntry->assets, $payOrder->currency->decimal_places, ",", ".") }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2" style="font-weight:bold;text-align: right">TOTAL</td>
                <td style="font-weight:bold;text-align: right;border-top:solid 1px #000;">
                    {{ number_format($accountingEntry->tot_debit, $payOrder->currency->decimal_places, ",", ".") }}
                </td>
                <td style="font-weight:bold;text-align: right;border-top:solid 1px #000;">
                    {{ number_format($accountingEntry->tot_assets, $payOrder->currency->decimal_places, ",", ".") }}
                </td>
            </tr>
        </tbody>
    </table>
@endif
<br>&#160;<br>
<table>
    <tbody>
        <tr>
            <td colspan="2" style="font-size: 0.85em;">
                <b>Por medio de la presente declaro haber recibido a mi entera conformidad el  pago identificado en el presente documento por el concepto descrito en el mismo.</b>
            </td>
        </tr>
        <tr><td colspan="2">&#160;</td></tr>
        <tr>
            <td style="width:350px;font-size: 0.85em;">
                NOMBRE Y APELLIDO: _____________________________________<br>&#160;<br>
                CÉDULA DE IDENTIDAD Nº: _________________________________<br>&#160;<br>
                EN REPRESENTACIÓN DE: ___________________________________
            </td>
            <td style="width:220px;text-align: center;">
                _____________________________________<br>&#160;<br>
                Firma
            </td>
        </tr>
    </tbody>
</table>