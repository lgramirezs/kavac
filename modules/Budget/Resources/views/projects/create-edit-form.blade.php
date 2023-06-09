@extends('budget::layouts.master')

@section('maproute-icon')
	<i class="ion-arrow-graph-up-right"></i>
@stop

@section('maproute-icon-mini')
	<i class="ion-arrow-graph-up-right"></i>
@stop

@section('maproute-actual')
	{{ __('Presupuesto') }}
@stop

@section('maproute-title')
	{{ __('Proyectos') }}
@stop

@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h6 class="card-title">
						{{ __('Proyecto') }}
						@include('buttons.help', [
							'helpId' => 'BudgetProjectHelp',
							'helpSteps' => get_json_resource('ui-guides/budget_project.json', 'budget')
						])
					</h6>
					<div class="card-btns">
						@include('buttons.previous', ['route' => url()->previous()])
						@include('buttons.minimize')
					</div>
				</div>
				{!! (!isset($model))?Form::open($header):Form::model($model, $header) !!}
					<div class="card-body"> 
						@include('layouts.form-errors')
						<div class="row">
							<div class="col-3" id="helpInstitution">
								<div class="form-group is-required">
									{!! Form::label('institution_id', __('Institución'), ['class' => 'control-label']) !!}
									{!! Form::select(
										'institution_id',
										$institutions, (isset($model)) ? $model->department->institution->id : null,
										[
											'class' => 'select2', 'data-toggle' => 'tooltip',
											'id' => 'institution_id',
											'onchange' => 'updateSelectActive($(this), $("#department_id"), "Department",undefined, undefined,[$("#payroll_position_id"), $("#payroll_staff_id")] )',
											'title' => __('Seleccione una institución')
										]
									) !!}
								</div>
							</div>
							@php
								$institution = count($institutions); 
							@endphp

							@if($institution > 1)
							<div class="col-3" id="helpDepartment">
								<div class="form-group is-required">
									{!! Form::label('department_id', __('Dependencia'), ['class' => 'control-label']) !!}
									{!! Form::select('department_id', $departments, null, [
										'class' => 'select2', 'data-toggle' => 'tooltip',
											'id' => 'department_id',
											'disabled' => ( true),
										'onchange' => 'updateSelectActive($(this), $("#payroll_staff_id"), "PayrollEmployment", "Payroll", "payrollStaff",[$("#payroll_position_id")])',
										'title' => __('Seleccione un departamento o dependencia'),
									]) !!}
								</div>
							</div>
							@if (Module::has('Payroll') && Module::isEnabled('Payroll'))
								<div class="col-3" id="helpResponsible">
									<div class="form-group is-required">
										{!! Form::label('payroll_staff_id', __('Responsable'), ['class' => 'control-label']) !!}
										{!! Form::select('payroll_staff_id', $staffs, null, [
											'class' => 'select2', 'data-toggle' => 'tooltip',
											'id' => 'payroll_staff_id',
											'disabled' => ( true),
											'onchange' => 'updateSelectCustomPosition($(this), $("#payroll_position_id"), "PayrollEmployment", "Payroll", "")',
											'title' => __('Seleccione una persona responsable del proyecto')
										]) !!}
									</div>
								</div>
								<div class="col-3" id="helpPosition">
									<div class="form-group is-required">
										{!! Form::label('payroll_position_id', __('Cargo de Responsable'), [
											'class' => 'control-label'
										]) !!}
										{!! Form::select('payroll_position_id', $positions, null, [
											'class' => 'select2', 'data-toggle' => 'tooltip',
											'id' => 'payroll_position_id',
											'disabled' => ( true),
											'title' => __('Seleccione el cargo de la persona responsable del proyecto')
										]) !!}
									</div>
								</div>
							@endif
						@else
						<div class="col-3" id="helpDepartment">
								<div class="form-group is-required">
									{!! Form::label('department_id', __('Dependencia'), ['class' => 'control-label']) !!}
									{!! Form::select('department_id', $departments, null, [
										'class' => 'select2', 'data-toggle' => 'tooltip',
											'id' => 'department_id',
											'disabled' => ( false),
										'onchange' => 'updateSelectActive($(this), $("#payroll_staff_id"), "PayrollEmployment", "Payroll", "payrollStaff",[$("#payroll_position_id")])',
										'title' => __('Seleccione un departamento o dependencia'),
									]) !!}
								</div>
							</div>
							@if (Module::has('Payroll') && Module::isEnabled('Payroll'))
								<div class="col-3" id="helpResponsible">
									<div class="form-group is-required">
										{!! Form::label('payroll_staff_id', __('Responsable'), ['class' => 'control-label']) !!}
										{!! Form::select('payroll_staff_id', $staffs, null, [
											'class' => 'select2', 'data-toggle' => 'tooltip',
											'id' => 'payroll_staff_id',
											'disabled' => ( false),
											'onchange' => 'updateSelectCustomPosition($(this), $("#payroll_position_id"), "PayrollEmployment", "Payroll", "")',
											'title' => __('Seleccione una persona responsable del proyecto')
										]) !!}
									</div>
								</div>
								<div class="col-3" id="helpPosition">
									<div class="form-group is-required">
										{!! Form::label('payroll_position_id', __('Cargo de Responsable'), [
											'class' => 'control-label'
										]) !!}
										{!! Form::select('payroll_position_id', $positions, null, [
											'class' => 'select2', 'data-toggle' => 'tooltip',
											'id' => 'payroll_position_id',
											'disabled' => ( true),
											'title' => __('Seleccione el cargo de la persona responsable del proyecto')
										]) !!}
									</div>
								</div>
							@endif
						@endif
						</div>
						<div class="row">
							<div class="col-3" id="helpCode">
								<div class="form-group is-required">
									{!! Form::label('code', __('Código'), ['class' => 'control-label']) !!}
									{!! Form::text('code', (isset($model)) ? $model->code : old('code'), [
										'class' => 'form-control input-sm', 'placeholder' => __('Código de proyecto'),
										'data-toggle' => 'tooltip', 'title' => __('Código que identifica el proyecto')
									]) !!}
								</div>
							</div>
							<div class="col-3" id="helpOnapreCode">
								<div class="form-group">
									{!! Form::label('onapre_code', __('Código ONAPRE'), ['class' => 'control-label']) !!}
									{!! Form::text('onapre_code', (isset($model)) ? $model->onapre_code : old('onapre_code'), [
										'class' => 'form-control input-sm', 'placeholder' => __('Código de la ONAPRE'),
										'data-toggle' => 'tooltip',
										'title' => __('Código asignado por la Oficina Nacional de Presupuesto (ONAPRE)')
									]) !!}
								</div>
							</div>
							<div class="col-6" id="helpName">
								<div class="form-group is-required">
									{!! Form::label('name', __('Nombre'), ['class' => 'control-label']) !!}
									{!! Form::text('name', (isset($model)) ? $model->name : old('name'), [
										'class' => 'form-control input-sm', 'placeholder' => __('Nombre del proyecto'),
										'data-toggle' => 'tooltip',
										'title' => __('Nombre que identifica el proyecto')
									]) !!}
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-3" id="helpStatus">
								<div class="form-group">
									<label for="" class="control-label">{{ __('Activo') }}</label>
									<div class="custom-control custom-switch">
										{!! Form::checkbox('active', true, (isset($model))?$model->active:null, [
											'id' => 'active', 'class' => 'custom-control-input'
										]) !!}
										<label class="custom-control-label" for="active"></label>
									</div>
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
@stop
