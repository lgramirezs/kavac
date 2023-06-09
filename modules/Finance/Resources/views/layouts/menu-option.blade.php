{{-- Gestión de finanzas --}}
<li>
    <a href="#" title="Gestión de bancos y finanzas" data-toggle="tooltip" data-placement="right">
        <i class="ion-ios-calculator-outline"></i><span>Finanzas</span>
    </a>
    <ul class="submenu" style="{!! display_submenu('finance') !!}">
        <li class="{!! set_active_menu('finance.setting.index') !!}">
            <a href="{{ route('finance.setting.index') }}">Configuración</a>
        </li>
        <li>
            <a href="javascript:void('0')">Gestión de Pagos</a>
            <ul class="submenu">
                <li>
                    <a href="{{ route('finance.pay-orders.index') }}">Órdenes</a>
                </li>
                <li>
                    <a href="{{ route('finance.payment-execute.index') }}">Emisiones</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="#">Banco</a>
            <ul class="submenu">
                <li>
                    <a href="{{ route('finance.movements.index') }}">Movimientos</a>
                </li>
                <li>
                    <a href="{{ route('finance.conciliation.index') }}">Conciliación</a>
                </li>
            </ul>
        </li>
        <!--<li>
            <a href="#">Reportes</a
            <ul class="submenu">
                <li><a href="#">Vouchers</a></li>
                <li><a href="#"></a></li>
            </ul>
        </li>-->
    </ul>
</li>
