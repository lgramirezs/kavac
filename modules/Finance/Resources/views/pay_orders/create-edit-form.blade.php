@extends('finance::layouts.master')

@section('maproute-icon')
	<i class="mdi mdi-ballot-outline"></i>
@stop

@section('maproute-icon-mini')
	<i class="mdi mdi-ballot-outline"></i>
@stop

@section('maproute-actual')
	{{ __('Finanzas') }}
@stop

@section('maproute-title')
	{{ __('Órdenes de Pago') }}
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <finance-pay-order route-list="{{ route('finance.pay-order.vuelist') }}" 
                               :accounting_accounts="{{ $accountingAccounts }}"/>
        </div>
    </div>
@endsection