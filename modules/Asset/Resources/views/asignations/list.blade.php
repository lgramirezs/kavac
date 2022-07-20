@extends('asset::layouts.master')

@section('maproute-icon')
	<i class="ion-ios-pricetags-outline"></i>
@stop

@section('maproute-icon-mini')
	<i class="ion-ios-pricetags-outline"></i>
@stop

@section('maproute-actual')
	Bienes
@stop

@section('maproute-title')
	Gestión de Bienes
@stop

@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h6 class="card-title">Asignación de Bienes</h6>
					<div class="card-btns">
						@include('buttons.previous', ['route' => url()->previous()])
						@include('buttons.new', ['route' => route('asset.asignation.create')])
						@include('buttons.minimize')
					</div>
				</div>
				<div class="card-body">
					<asset-asignation-list
						route_list="{{ url('asset/asignations/vue-list') }}"
						route_edit="{{ url('asset/asignations/edit/{id}') }}"
						route_delete="{{ url('asset/asignations/delete') }}">
					</asset-asignation-list>
				</div>
			</div>
		</div>
	</div>

	@role(['admin','asset'])
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h6 class="card-title">Entrega de Bienes Pendientes</h6>
					<div class="card-btns">
						@include('buttons.previous', ['route' => url()->previous()])
						@include('buttons.minimize')
					</div>
				</div>
				<div class="card-body">
					<asset-asignation-delivery-list
							route_list="{{ url('asset/asignations/deliveries') }}">
					</asset-asignation-delivery-list>
				</div>
			</div>
		</div>
	</div>
	@endrole
@stop
