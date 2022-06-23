@extends('warehouse::layouts.master')

@section('maproute-icon')
    <i class="ion-ios-list-outline"></i>
@stop

@section('maproute-icon-mini')
    <i class="ion-ios-list-outline"></i>
@stop

@section('maproute-actual')
    Almacén
@stop

@section('maproute-title')
    Gestión de Almacenes
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title text-uppercase">Reporte de mínimo inventario
                </h6>
                <div class="card-btns">
                    @include('buttons.previous', ['route' => url()->previous()])
                    @include('buttons.minimize')
                </div>
            </div>
            <warehouse-report-stocks
                route_list="{{ url()->previous() }}">
            </warehouse-report-stocks>
        </div>
    </div>
</div>
@stop
