@extends('payroll::layouts.master')

@section('maproute-icon')
    <i class="ion-ios-folder-outline"></i>
@stop

@section('maproute-icon-mini')
    <i class="ion-ios-folder-outline"></i>
@stop

@section('maproute-actual')
    Talento Humano
@stop

@section('maproute-title')
    Gestión de tablas salariales
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <payroll-salary-adjustments-form
                route_list="{{ url()->previous() }}"
                route_show="{{ url('payroll/salary-tabulators/show/{id}') }}"
                route_create="{{ url('payroll/salary-adjustments') }}"
                :payroll_salary_adjustment_id="{!! (isset($payrollSalaryAdjustment)) ? $payrollSalaryAdjustment->id : "null" !!}">
            </payroll-salary-adjustments-form>
        </div>
    </div>
@stop
