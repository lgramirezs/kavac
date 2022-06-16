<h1 style="font-size: 9rem;" align="center">CERTIFICADO DE DISPONIBILIDAD PRESUPUESTARIA </h1>
<h2 style="font-size: 9rem;" align="center">Información Presupuestaria {{ $initialDate }} HASTA {{ $finalDate }}
</h2>
<h4 style="font-size: 9rem;">EXPRESADO EN {{ $currencySymbol }}</h4>
<h4 style="font-size: 9rem;">Código del ente: {{ $institution['onapre_code'] }}</h4>
<h4 style="font-size: 9rem;">Denominación del ente: {{ $institution['name'] }}</h4>
<h4 style="font-size: 9rem;">Año Fiscal: {{ $fiscal_year }}</h4>
<br>
<table cellspacing="0" cellpadding="1" border="1">
    <tr>
        <th style="font-size: 9rem;" width="14.1%" align="center">ACCION/PROYECTO</th>
        <th style="font-size: 9rem;" width="14.1%" align="center">ACCION ESPECIFICA</th>
        <th style="font-size: 9rem;" width="14.1%" align="center">CODIGO</th>
        <th style="font-size: 9rem;" width="14.1%" align="center">DENOMINACION</th>
        <th style="font-size: 9rem;" width="14.5%" align="center">PROGRAMADO</th>
        <th style="font-size: 9rem;" width="14.5%" align="center">COMPROMETIDO</th>
        <th style="font-size: 9rem;" width="15%" align="center">DISPONIBILIDAD PRESUPUESTARIA AL DIA:
            {{ $report_date }}</th>
    </tr>
</table>
<table cellspacing="0" cellpadding="1" border="0">
    @foreach ($records as $budgetAccounts)
        @if (count($budgetAccounts[0]) < 0)
            @php
                break;
            @endphp
        @endif
        @foreach ($budgetAccounts[0] as $budgetAccount)
            @php
                $isRoot = substr_count($budgetAccount['budgetAccount']['code'], '0111111') == 8;
                $styles = $isRoot ? 'font-weight: bold;' : '';
            @endphp
            <tr>
                <td style="font-size: 9rem; border-bottom: 1px solid #999; {{ $styles }}" align="center">
                    {{ $budgetAccounts['project_code'] }}</td>
                <td style="font-size: 9rem; border-bottom: 1px solid #999; {{ $styles }}" align="center">
                    {{ $budgetAccounts['specific_action_code'] }}</td>
                <td style="font-size: 9rem; border-bottom: 1px solid #999; {{ $styles }}" align="center">
                    {{ $budgetAccount['budgetAccount']['code'] }}</td>
                <td style="font-size: 9rem; border-bottom: 1px solid #999; {{ $styles }}">
                    {{ $budgetAccount['budgetAccount']['denomination'] }}</td>
                <td style="font-size: 9rem; border-bottom: 1px solid #999; {{ $styles }}" align="center">
                    {{ 'Programado' }}</td>
                <td style="font-size: 9rem; border-bottom: 1px solid #999; {{ $styles }}" align="center">
                    {{ 'Comprometido' }}</td>
                <td style="font-size: 9rem; border-bottom: 1px solid #999;" align="center">
                    {{ $budgetAccount['amount_available'] . ' ' . $currencySymbol }}</td>
            </tr>
        @endforeach
    @endforeach

</table>
