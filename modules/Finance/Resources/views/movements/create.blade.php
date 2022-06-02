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
    {{ __('Movimientos bancarios') }}
@stop


@section('content')
@role(['admin','finance'])
    <div class="row">
        <div class="col-12">
            <div class="card" id="cardFinanceMovementsForm">
                <div class="card-header">
                    <h6 class="card-title text-uppercase">Nuevo Movimiento Bancario
                    </h6>
                    <div class="card-btns">
                        @include('buttons.previous', ['route' => url()->previous()])
                        @include('buttons.minimize')
                    </div>
                </div>
                <finance-bank-movements-create
                    :accounting="{{ $accounting ? $accounting : 0 }}"
                    :budget="{{ $budget ? $budget : 0 }}"
                    :entry_categories="{{ $categories }}"
                    :accounting_list="{{ $accountingList }}"
                    :movementid ="{!! (isset($movement)) ? $movement->id : 'null' !!}">
                </finance-bank-movements-create>
            </div>
        </div>
    </div>
@endrole
@stop
