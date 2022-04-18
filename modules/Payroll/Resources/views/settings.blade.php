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
			<div class="card" id="codeSettingForm">
				<div class="card-header">
					<h6 class="card-title">
						{{ __('Formatos de Códigos') }}
						{{--
							// Issue #96: Solicitaron que no se muestre el botón de ayuda en esta sección
							@include('buttons.help', [
								'helpId' => 'PayrollCodeSetting',
								'helpSteps' => get_json_resource('ui-guides/settings/code_setting.json', 'payroll')
							])
						--}}
					</h6>
					<div class="card-btns">
						@include('buttons.previous', ['route' => url()->previous()])
						@include('buttons.minimize')
					</div>
				</div>
				{!! Form::open(['id' => 'form-codes', 'route' => 'payroll.settings.store', 'method' => 'post']) !!}
				{!! Form::token() !!}
				<div class="card-body">
					@include('layouts.help-text', ['codeSetting' => true])
					@include('layouts.form-errors')
					<div class="row">
						<div class="col-12">
							<h6>{{ __('Personal') }}</h6>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4" id="staffsCode">
							<div class="form-group">
								{!! Form::label('staffs_code', 'Código del personal', []) !!}
								{!! Form::text('staffs_code', ($sCode) ? $sCode->format_code : old('staffs_code'), [
								'class' => 'form-control input-sm', 'data-toggle' => 'tooltip',
								'title' => 'Formato para el código del personal',
								'placeholder' => 'Ej. XXX-00000000-YYYY',
								'readonly' => ($sCode) ? true : false
								]) !!}
							</div>
						</div>
						<div class="col-md-4" id="vacationRequestsCode">
							<div class="form-group">
								{!! Form::label('vacation_requests_code', 'Código de las solicitudes de vacaciones', []) !!}
								{!! Form::text('vacation_requests_code', ($vRCode) ? $vRCode->format_code : old('vacation_requests_code'), [
								'class' => 'form-control input-sm', 'data-toggle' => 'tooltip',
								'title' => 'Formato para el código de las solicitudes de vacaciones',
								'placeholder' => 'Ej. XXX-00000000-YYYY',
								'readonly' => ($vRCode) ? true : false
								]) !!}
							</div>
						</div>
						<div class="col-md-4" id="benefitsRequestsCode">
							<div class="form-group">
								{!! Form::label('benefits_requests_code', 'Código de las solicitudes de adelanto de prestaciones', []) !!}
								{!! Form::text('benefits_requests_code', ($bRCode) ? $bRCode->format_code : old('benefits_requests_code'), [
								'class' => 'form-control input-sm', 'data-toggle' => 'tooltip',
								'title' => 'Formato para el código de las solicitudes de adelanto de prestaciones',
								'placeholder' => 'Ej. XXX-00000000-YYYY',
								'readonly' => ($bRCode) ? true : false
								]) !!}
							</div>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-12">
							<h6>{{ __('Nómina') }}</h6>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4" id="salaryScalesCode">
							<div class="form-group">
								{!! Form::label('salary_scales_code','Código de los escalafones salariales',[]) !!}
								{!! Form::text('salary_scales_code', ($ssCode)
								? $ssCode->format_code
								: old('salary_scales_code'), [
								'class' => 'form-control input-sm', 'data-toggle' => 'tooltip',
								'title' => 'Formato para el código de los escalafones salariales',
								'placeholder' => 'Ej. XXX-00000000-YYYY',
								'readonly' => ($ssCode) ? true : false
								]) !!}
							</div>
						</div>
						<div class="col-md-4" id="salaryTabulatorsCode">
							<div class="form-group">
								{!! Form::label('salary_tabulators_code','Código de los tabuladores salariales',[]) !!}
								{!! Form::text('salary_tabulators_code', ($stCode)
								? $stCode->format_code
								: old('salary_tabulators_code'), [
								'class' => 'form-control input-sm', 'data-toggle' => 'tooltip',
								'title' => 'Formato para el código de los tabuladores salariales',
								'placeholder' => 'Ej. XXX-00000000-YYYY',
								'readonly' => ($stCode) ? true : false
								]) !!}
							</div>
						</div>
					</div>
				</div>
				<div class="card-footer text-right">
					@include('layouts.form-buttons')
				</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<div class="card" id="payroll_work_age">
				<div class="card-header">
					<h6 class="card-title">
						{{ __('Configuración de la Edad Laboral Permitida') }}
						@include('buttons.help', [
							'helpId' => 'PayrollWorkAge',
							'helpSteps' => get_json_resource('ui-guides/settings/work_age.json', 'payroll')
						])
					</h6>
					<div class="card-btns">
						@include('buttons.previous', ['route' => url()->previous()])
						@include('buttons.minimize')
					</div>
				</div>
				{!! Form::open(['route' => 'parameters.store', 'method' => 'post']) !!}
				{!! Form::token() !!}
				<div class="card-body">
					@include('layouts.form-errors')
					<div class="row">
						<div class="col-md-4" id="helpWorkAge">
							<div class="form-group">
								@if (Modules\Payroll\Models\Parameter::where([
									'active' => true, 'required_by' => 'payroll',
									'p_key' => 'work_age'
								])->first())
									{!! Form::label('p_value', 'Edad', []) !!}
									{!! Form::number('p_value', ($parameter) ? $parameter->p_value : old('p_value'), [
										'class' => 'form-control', 'data-toggle' => 'tooltip',
										'title' => 'Indique la edad laboral permitida', 'min' => '1',
										'placeholder' => 'Edad'
									]) !!}
									{!! Form::hidden('p_key', 'work_age'); !!}
								@endif
							</div>
						</div>
					</div>
				</div>
				<div class="card-footer text-right" id="helpParamButtons">
					@include('layouts.form-buttons')
				</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<div class="card" id="payroll_common">
				<div class="card-header">
					<h6 class="card-title">
						{{ __('Registros Comunes') }}
						@include('buttons.help', [
							'helpId' => 'PayrollCommon',
							'helpSteps' => get_json_resource('ui-guides/settings/common.json', 'payroll')
						])
					</h6>
					<div class="card-btns">
						@include('buttons.previous', ['route' => url()->previous()])
						@include('buttons.minimize')
					</div>
				</div>
				<div class="card-body">
					<div class="row">
						{{-- Tipos de personal --}}
						<payroll-staff-types></payroll-staff-types>

						{{-- Tipos de cargos --}}
						<payroll-position-types></payroll-position-types>

						{{-- Cargos --}}
						<payroll-positions></payroll-positions>

						{{-- Clasificaciones del personal --}}
						<payroll-staff-classifications></payroll-staff-classifications>

						{{-- Grados de instrucción --}}
						<payroll-instruction-degrees></payroll-instruction-degrees>

						{{-- Tipos de estudio --}}
						<payroll-study-types></payroll-study-types>

						{{-- Nacionalidades --}}
						<payroll-nationalities></payroll-nationalities>

						{{-- Niveles de idioma --}}
						<payroll-language-levels></payroll-language-levels>

						{{-- Idiomas --}}
						<payroll-languages></payroll-languages>

						{{-- Géneros --}}
						<payroll-genders></payroll-genders>

						{{-- Tipos de inactividad --}}
						<payroll-inactivity-types></payroll-inactivity-types>

						{{-- Tipos de contrato --}}
						<payroll-contract-types></payroll-contract-types>

						{{-- Tipos de sector --}}
						<payroll-sector-types></payroll-sector-types>

						{{-- Grados de licencia de conducir --}}
						<payroll-license-degrees></payroll-license-degrees>

						{{-- Tipos de sangre --}}
						<payroll-blood-types></payroll-blood-types>

						{{-- Parentescos --}}
						<payroll-relationships></payroll-relationships>

						{{-- Discapacidades --}}
						<payroll-disabilities></payroll-disabilities>

						{{-- Niveles de escolaridad --}}
						<payroll-schooling-levels></payroll-schooling-levels>
					</div>
				</div>
			</div>
		</div>
	</div>
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
							start_operations_date="{!! (isset($institution)) ? $institution->start_operations_date : '' !!}">></payroll-benefits-policies>

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
