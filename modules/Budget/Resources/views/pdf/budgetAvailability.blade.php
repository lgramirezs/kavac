{{-- <h1 style="font-size: 9rem;" align="center">CERTIFICADO DE DISPONIBILIDAD PRESUPUESTARIA </h1> --}}
<h2 style="font-size: 9rem;" align="center">Información Presupuestaria desde {{ $initialDate }} hasta
    {{ $finalDate }}
</h2>
<h2>
    <br>
</h2>
<h4 style="font-size: 9rem;">Expresado en: {{ $currencySymbol }}</h4>
<h4 style="font-size: 9rem;">Código del ente: {{ $institution['onapre_code'] }}</h4>
<h4 style="font-size: 9rem;">Denominación del ente: {{ $institution['name'] }}</h4>
<h4 style="font-size: 9rem;">Año Fiscal: {{ $fiscal_year }}</h4>
<br>
<table cellspacing="0" cellpadding="1" border="1">
    <tr>
        <th style="font-size: 9rem;" width="20%" align="center">Acción/Proyecto</th>
        <th style="font-size: 9rem;" width="20%" align="center">Acción Especifica</th>
        <th style="font-size: 9rem;" width="20%" align="center">Código</th>
        <th style="font-size: 9rem;" width="20%" align="center">Denominación</th>
        {{-- <th style="font-size: 9rem;" width="14%" align="center">PROGRAMADO </th> --}}
        {{-- <th style="font-size: 9rem;" width="14%" align="center">COMPROMETIDO </th> --}}
        <th style="font-size: 9rem;" width="20%" align="center">Disponibilidad Presupuestaria</th>
    </tr>
</table>

<table cellspacing="0" cellpadding="1" border="1">
    @php
        // dd($records);
        // $total_programmed = 0;
        // $total_compromised = 0;
        $total_amount_available = 0;
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
                    {{ $budgetAccounts['project_code'] }}</td>
                <td style="font-size: 7rem; border-bottom: 1px solid #999; {{ $styles }}" align="center">
                    {{ $budgetAccounts['specific_action_code'] }}</td>

                {{-- Informacion de las cuentas --}}
                <td style="font-size: 8rem; border-bottom: 1px solid #999; {{ $styles }}" align="center">
                    {{ $budgetAccount['budgetAccount']['code'] }}</td>
                <td style="font-size: 8rem; border-bottom: 1px solid #999; {{ $styles }}" align="left">
                    {{ $budgetAccount['budgetAccount']['denomination'] }}</td>
                {{-- <td style="font-size: 8rem; border-bottom: 1px solid #999; {{ $styles }}" align="center">
                    {{ number_format($budgetAccount['programmed'], 2) }}</td>
                <td style="font-size: 8rem; border-bottom: 1px solid #999; {{ $styles }}" align="center">
                    {{ number_format($budgetAccount['compromised'], 2) }}</td> --}}
                <td style="font-size: 8rem; border-bottom: 1px solid #999; {{ $styles }}" align="center">
                    {{ number_format($budgetAccount['total_year_amount_m'], 2) }}</td>
            </tr>
            @php
                if ($budgetAccount->budgetAccount->item === '00') {
                    // $total_programmed += $budgetAccount['programmed'];
                    // $total_compromised += $budgetAccount['compromised'];
                    $total_amount_available += $budgetAccount['total_year_amount_m'];
                }
            @endphp
        @endforeach
    @endforeach

</table>

<table cellspacing="0" cellpadding="1" border="1" style="font-weight: bold">
    <tr>
        <td style="font-size: 8rem; border-bottom: 1px solid #999;" align="right" width="80%">
            Total
        </td>
        {{-- <td style="font-size: 8rem; border-bottom: 1px solid #999; " align="center" width="15%">
            {{ number_format($total_programmed, 2) }}
        </td>
        <td style="font-size: 8rem; border-bottom: 1px solid #999;" align="center" width="14%">
            {{ number_format($total_compromised, 2) }}
        </td> --}}
        <td style="font-size: 8rem; border-bottom: 1px solid #999;" align="center" width="20%">
            {{ number_format($total_amount_available, 2) }}
        </td>
    </tr>
</table>
