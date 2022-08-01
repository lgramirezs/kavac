{{-- <h1 style="font-size: 9rem;" align="center">MAYOR ANALÍTICO PRESUPUESTO POR PROYECTO / ACCIÓN CENTRALIZADA</h1> --}}
<h3 style="font-size: 9rem;" align="center">
    Información Presupuestaria desde {{ $initialDate }} hasta {{ $finalDate }}
</h3>
<table cellspacing="2" cellpadding="2" border="0" style="font-size: 10rem; font-weight: bold">
    <tr>
        <td>Expresado en: {{ $currencySymbol }}</td>
    </tr>
    <tr>
        <td>Código del ente: {{ $institution['onapre_code'] }}</td>
    </tr>
    <tr>
        <td>Denominación del ente:
            {{ $institution['name'] }}</td>
    </tr>
    <tr>
        <td>Denominación del ente:
            {{ $fiscal_year }}</td>
    </tr>
    <tr>
        <td>Proyecto / Acción Centralizada:
            {{ $records[0][1]['specificAction']['specificable']['name'] }}</td>
    </tr>
    <tr>
        <td>Código de Proyecto / Acción Centralizada:
            {{ $records[0][1]['specificAction']['specificable']['code'] }}
        </td>
    </tr>
    <tr>
        <td></td>
    </tr>
</table>

{{-- <h2 style="font-size: 9rem;" align="center">Información Presupuestaria desde {{ $initialDate }} hasta {{ $finalDate }}
</h2>
<h2></h2>
<h2></h2>
<h4 style="font-size: 9rem;">Expresado en: {{ $currencySymbol }}</h4>
<h4 style="font-size: 9rem;">Código del ente: {{ $institution['onapre_code'] }}</h4>
<h4 style="font-size: 9rem;">Denominación del ente: {{ $institution['name'] }}</h4>
<h4 style="font-size: 9rem;">Proyecto / Acción Centralizada:
    {{ $records[0][1]['specificAction']['specificable']['name'] }} </h4>
<h4 style="font-size: 9rem;">Código de Proyecto / Acción Centralizada:
    {{ $records[0][1]['specificAction']['specificable']['code'] }}</h4>
<h4 style="font-size: 9rem;">Año Fiscal: {{ $fiscal_year }}</h4>
<br> --}}

<table cellspacing="0" cellpadding="1" border="1">
    <tr>
        <th style="font-size: 7rem;" align="center">Fecha</th>
        <th style="font-size: 7rem;" align="center">Código</th>
        <th style="font-size: 7rem;" align="center">Denominación</th>
        <th style="font-size: 7rem;" align="center">Detalle</th>
        <th style="font-size: 7rem;" align="center">Asignado</th>
        <th style="font-size: 7rem;" align="center">Aumento</th>
        <th style="font-size: 7rem;" align="center">Disminución</th>
        <th style="font-size: 7rem;" align="center">Actual</th>
        <th style="font-size: 7rem;" align="center">Comprometido</th>
        <th style="font-size: 7rem;" align="center">Causado</th>
        <th style="font-size: 7rem;" align="center">Pagado</th>
        <th style="font-size: 7rem;" align="center">Disponible</th>
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
                $compromised_des = join(',', $budgetAccount['compromised_descriptions'] ?? []);
                $increment_des = join(',', $budgetAccount['increment_descriptions'] ?? []);
                $decrement_des = join(',', $budgetAccount['decrement_descriptions'] ?? []);
                $styles = $budgetAccount->budgetAccount->specific === '00' ? 'font-weight: bold;' : '';
            @endphp
            <tr>
                <td style="font-size: 7rem; border-bottom: 1px solid #999; {{ $styles }}" align="center">
                    {{ date_format($budgetAccount['created_at'], 'd-m-Y') }}</td>

                <td style="font-size: 7rem; border-bottom: 1px solid #999; {{ $styles }}" align="center">
                    {{ $budgetAccount['budgetAccount']['code'] }}</td>

                <td style="font-size: 7rem; border-bottom: 1px solid #999; {{ $styles }}" align="left">
                    {{ $budgetAccount['budgetAccount']['denomination'] }}</td>

                <td style="font-size: 7rem; border-bottom: 1px solid #999; {{ $styles }}" align="left">
                    {{ 'Aumento: ' . $increment_des . "\r" . 'Disminución: ' . $decrement_des . "\r" . 'Compromiso: ' . $compromised_des }}
                </td>

                <td style="font-size: 7rem; border-bottom: 1px solid #999; {{ $styles }}" align="center">
                    {{ number_format($budgetAccount['total_year_amount'], 2) }}</td>

                <td style="font-size: 7rem; border-bottom: 1px solid #999; {{ $styles }}" align="center">
                    {{ number_format($budgetAccount['increment'], 2) }}</td>

                <td style="font-size: 7rem; border-bottom: 1px solid #999; {{ $styles }}" align="center">
                    {{ number_format($budgetAccount['decrement'], 2) }}</td>

                <td style="font-size: 7rem; border-bottom: 1px solid #999; {{ $styles }}" align="center">
                    {{ number_format($budgetAccount['current'], 2) }}
                </td>

                <td style="font-size: 7rem; border-bottom: 1px solid #999; {{ $styles }}" align="center">
                    {{ number_format($budgetAccount['compromised'], 2) }}</td>

                <td style="font-size: 7rem; border-bottom: 1px solid #999; {{ $styles }}" align="center">
                    {{ number_format('0', 2) }}</td>

                <td style="font-size: 7rem; border-bottom: 1px solid #999; {{ $styles }}" align="center">
                    {{ number_format('0', 2) }}</td>

                <td style="font-size: 7rem; border-bottom: 1px solid #999; {{ $styles }}" align="center">
                    {{ number_format($budgetAccount['total_year_amount_m'], 2) }}</td>
            </tr>
            @php
                if ($budgetAccount->budgetAccount->item === '00') {
                    $real += $budgetAccount['total_real_amount'];
                    $total_programmed += $budgetAccount['total_year_amount'];
                    $current += $budgetAccount['current'];
                    $total_compromised += $budgetAccount['compromised'];
                    $caused += 0;
                    $paid += 0;
                    $total_amount_available += $budgetAccount['total_year_amount_m'];
                    $increment += $budgetAccount['increment'];
                    $decrement += $budgetAccount['decrement'];
                }
            @endphp
        @endforeach
    @endforeach
</table>

<table cellspacing="0" cellpadding="1" border="1" style="font-weight: bold">
    <tr>
        <td style="font-size: 8rem; border-bottom: 1px solid #999;" align="Right" width="8.3%">
        </td>
        <td style="font-size: 8rem; border-bottom: 1px solid #999;" width="8.3%">
        </td>
        <td style="font-size: 8rem; border-bottom: 1px solid #999;" width="8.3%">
            Total
        </td>
        <td style="font-size: 8rem; border-bottom: 1px solid #999;" align="center" width="8.3%">
        </td>
        <td style="font-size: 8rem; border-bottom: 1px solid #999; " align="center" width="8.3%">
            {{ number_format($total_programmed, 2) }}
        </td>
        <td style="font-size: 8rem; border-bottom: 1px solid #999;" align="center" width="8.3%">
            {{ number_format($increment, 2) }}
        </td>
        <td style="font-size: 8rem; border-bottom: 1px solid #999;" align="center" width="8.3%">
            {{ number_format($decrement, 2) }}
        </td>
        <td style="font-size: 8rem; border-bottom: 1px solid #999;" align="center" width="8.3%">
            {{ number_format($current, 2) }}
        </td>
        <td style="font-size: 8rem; border-bottom: 1px solid #999;" align="center" width="8.3%">
            {{ number_format($total_compromised, 2) }}
        </td>
        <td style="font-size: 8rem; border-bottom: 1px solid #999;" align="center" width="8.3%">
            {{ number_format($caused, 2) }}
        </td>
        <td style="font-size: 8rem; border-bottom: 1px solid #999;" align="center" width="8.3%">
            {{ number_format($paid, 2) }}
        </td>
        <td style="font-size: 8rem; border-bottom: 1px solid #999;" align="center" width="8.3%">
            {{ number_format($total_amount_available, 2) }}
        </td>
    </tr>
</table>
