<h1 style="font-size: 9rem;" align="center">MAYOR ANALÍTICO PRESUPUESTO POR PROYECTO / ACCIÓN CENTRALIZADA</h1>
<h2 style="font-size: 9rem;" align="center">Información Presupuestaria {{ $initialDate }} HASTA {{ $finalDate }}
</h2>
<h4 style="font-size: 9rem;">EXPRESADO EN {{ $currencySymbol }}</h4>
<h4 style="font-size: 9rem;">Código del ente: {{ $institution['onapre_code'] }}</h4>
<h4 style="font-size: 9rem;">Denominación del ente: {{ $institution['name'] }}</h4>
<h4 style="font-size: 9rem;">Proyecto / Acción Centralizada: </h4>
<h4 style="font-size: 9rem;">Año Fiscal: {{ $fiscal_year }}</h4>
<br>

<table cellspacing="0" cellpadding="1" border="1">
    <tr>
        <th style="font-size: 7rem;" width="9%" align="center">CÓDIGO</th>
        <th style="font-size: 7rem;" width="10%" align="center">DENOMINACIÓN </th>
        <th style="font-size: 7rem;" width="9%" align="center">REAL</th>
        <th style="font-size: 7rem;" width="9%" align="center">PROGRAMADO</th>
        <th style="font-size: 7rem;" width="9%" align="center">AUMENTO</th>
        <th style="font-size: 7rem;" width="9%" align="center">DISMINUCIÓN</th>
        <th style="font-size: 7rem;" width="9%" align="center">ACTUAL</th>
        <th style="font-size: 7rem;" width="9%" align="center">COMPROMETIDO</th>
        <th style="font-size: 7rem;" width="9%" align="center">CAUSADO</th>
        <th style="font-size: 7rem;" width="9%" align="center">PAGADO</th>
        <th style="font-size: 7rem;" width="9%" align="center">DISPONIBLE</th>
    </tr>
</table>

<table cellspacing="0" cellpadding="1" border="1">
    @php
        $total_programmed = 0;
        $total_compromised = 0;
        $total_amount_available = 0;
        $increment = 0;
        $decrement = 0;
        $current = 0;
        $caused = 0;
        $paid = 0;
        $real = 0;
    @endphp
    @foreach ($records as $budgetAccounts)
        @if (count($budgetAccounts[0]) < 0)
            @php
                break;
            @endphp
        @endif
        @php
            usort($budgetAccounts[0], function ($budgetItemOne, $budgetItemTwo) {
                $codeOne = str_replace('.', '', $budgetItemOne->budgetAccount->getCodeAttribute());
                $codeTwo = str_replace('.', '', $budgetItemTwo->budgetAccount->getCodeAttribute());
                return $codeOne > $codeTwo;
            });
        @endphp
        @foreach ($budgetAccounts[0] as $budgetAccount)
            @php
                $styles = $budgetAccount->budgetAccount->specific === '00' ? 'font-weight: bold;' : '';
            @endphp
            <tr>
                <td style="font-size: 7rem; border-bottom: 1px solid #999; {{ $styles }}" align="center">
                    {{ $budgetAccount['budgetAccount']['code'] }}</td>
                <td style="font-size: 7rem; border-bottom: 1px solid #999; {{ $styles }}" align="left">
                    {{ $budgetAccount['budgetAccount']['denomination'] }}</td>
                <td style="font-size: 7rem; border-bottom: 1px solid #999; {{ $styles }}" align="center">
                    {{ number_format('0', 2) }}</td>
                <td style="font-size: 7rem; border-bottom: 1px solid #999; {{ $styles }}" align="center">
                    {{ number_format($budgetAccount['programmed'], 2) }}</td>
                <td style="font-size: 7rem; border-bottom: 1px solid #999; {{ $styles }}" align="center">
                    {{ number_format($budgetAccount['increment'], 2) }}</td>
                <td style="font-size: 7rem; border-bottom: 1px solid #999; {{ $styles }}" align="center">
                    {{ number_format($budgetAccount['decrement'], 2) }}</td>
                <td style="font-size: 7rem; border-bottom: 1px solid #999; {{ $styles }}" align="center">
                    {{ number_format($budgetAccount['programmed'] + $budgetAccount['increment'] - $budgetAccount['decrement'], 2) }}
                </td>
                <td style="font-size: 7rem; border-bottom: 1px solid #999; {{ $styles }}" align="center">
                    {{ number_format($budgetAccount['compromised'], 2) }}</td>
                <td style="font-size: 7rem; border-bottom: 1px solid #999; {{ $styles }}" align="center">
                    {{ number_format('0', 2) }}</td>
                <td style="font-size: 7rem; border-bottom: 1px solid #999; {{ $styles }}" align="center">
                    {{ number_format('0', 2) }}</td>
                <td style="font-size: 7rem; border-bottom: 1px solid #999; {{ $styles }}" align="center">
                    {{ number_format($budgetAccount['amount_available'], 2) }}</td>
            </tr>
            @php
                if ($budgetAccount->budgetAccount->item === '00') {
                    $real += 0;
                    $total_programmed += $budgetAccount['programmed'];
                
                    $current += $budgetAccount['programmed'] + $budgetAccount['increment'] - $budgetAccount['decrement'];
                    $total_compromised += $budgetAccount['compromised'];
                    $caused += 0;
                    $paid += 0;
                    $total_amount_available += $budgetAccount['amount_available'];
                }
                $increment += $budgetAccount['increment'];
                $decrement += $budgetAccount['decrement'];
            @endphp
        @endforeach
    @endforeach

</table>
<table cellspacing="0" cellpadding="1" border="1" style="font-weight: bold">
    <tr>
        <td style="font-size: 8rem; border-bottom: 1px solid #999;" align="center" width="17.5%">
        </td>
        <td style="font-size: 8rem; border-bottom: 1px solid #999;" align="center" width="10%">
            {{ number_format($real, 2) }}
        </td>
        <td style="font-size: 8rem; border-bottom: 1px solid #999; " align="center" width="9%">
            {{ number_format($total_programmed, 2) }}
        </td>
        <td style="font-size: 8rem; border-bottom: 1px solid #999;" align="center" width="9%">
            {{ number_format($increment, 2) }}
        </td>
        <td style="font-size: 8rem; border-bottom: 1px solid #999;" align="center" width="9%">
            {{ number_format($decrement, 2) }}
        </td>
        <td style="font-size: 8rem; border-bottom: 1px solid #999;" align="center" width="9%">
            {{ number_format($current, 2) }}
        </td>
        <td style="font-size: 8rem; border-bottom: 1px solid #999;" align="center" width="9%">
            {{ number_format($total_compromised, 2) }}
        </td>
        <td style="font-size: 8rem; border-bottom: 1px solid #999;" align="center" width="9.5%">
            {{ number_format($caused, 2) }}
        </td>
        <td style="font-size: 8rem; border-bottom: 1px solid #999;" align="center" width="9%">
            {{ number_format($paid, 2) }}
        </td>
        <td style="font-size: 8rem; border-bottom: 1px solid #999;" align="center" width="9%">
            {{ number_format($total_amount_available, 2) }}
        </td>
    </tr>
</table>
