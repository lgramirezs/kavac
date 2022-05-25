@extends('payroll::layouts.master')

@section('maproute-icon')
	<i class="ion-settings"></i>
@stop

@section('maproute-icon-mini')
	<i class="ion-settings"></i>
@stop

@section('maproute-actual')
	{{ __('Talento Humano') }}
@stop

@section('maproute-title')
	{{ __('Configuración') }}
@stop

@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card" id="cardPayrollGeneralParametersSettingForm">
				<div class="card-header">
					<h6 class="card-title">
						{{ __('Parámetros Generales de Nómina') }}
						@include('buttons.help', [
						'helpId' => 'GeneralParams',
						'helpSteps' => get_json_resource('ui-guides/settings/general_parameters.json', 'payroll')
						])
					</h6>
					<div class="card-btns">
						@include('buttons.previous', ['route' => url()->previous()])
						@include('buttons.minimize')
					</div>
				</div>
				<div class="card-body">
					<div class="row">
						{{-- Parámetros de nómina --}}
						<payroll-parameters></payroll-parameters>

						{{-- Escalafones salariales --}}
						<payroll-salary-scales></payroll-salary-scales>

						{{-- Tabuladores de nómina --}}
						<payroll-salary-tabulators></payroll-salary-tabulators>

						{{-- Tipos de conceptos --}}
						<payroll-concept-types></payroll-concept-types>

						{{-- Conceptos --}}
						<payroll-concepts></payroll-concepts>

						{{-- Tipos de pago --}}
						<payroll-payment-types
							start_operations_date="{!! (isset($institution)) ? $institution->start_operations_date : '' !!}">
						</payroll-payment-types>

						{{-- Políticas vacacionales --}}
						<payroll-vacation-policies
							start_operations_date="{!! (isset($institution)) ? $institution->start_operations_date : '' !!}">
						</payroll-vacation-policies>

						{{-- Políticas de prestaciones sociales --}}
						<payroll-benefits-policies
							start_operations_date="{!! (isset($institution)) ? $institution->start_operations_date : '' !!}">
						</payroll-benefits-policies>

						{{-- Políticas de Permisos --}}
						<payroll-permission-policies></payroll-permission-policies>

						{{-- Tipos de liquidación --}}
						<payroll-settlement-types></payroll-settlement-types>

					</div>
				</div>
			</div>
		</div>
	</div>
@stop

@section('extra-js')
	@parent
	{!! Html::script('js/ckeditor.js', [], Request::secure()) !!}
@stop
