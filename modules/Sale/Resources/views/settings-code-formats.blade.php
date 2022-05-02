<div class="row">
	<div class="col-12">
		<div class="card" id="helpCodeSaleSettingForm">
			<div class="card-header">
				<h6 class="card-title">
					{{ __('Formatos de Códigos') }}
					{{--
						// Issue #96: Solicitaron que no se muestre el botón de ayuda en esta sección
						@include('buttons.help', [
							'helpId' => 'SaleCodeSettingForm',
							'helpSteps' => get_json_resource('ui-guides/settings/code_setting.json', 'sale')
						])
					--}}
				</h6>
				<div class="card-btns">
					@include('buttons.previous', ['route' => url()->previous()])
					@include('buttons.minimize')
				</div>
			</div>
			{!! Form::open(['id' => 'form-codes', 'route' => 'sale.settings.store', 'method' => 'post']) !!}
				{!! Form::token() !!}
				<div class="card-body">
					@include('layouts.help-text', ['codeSetting' => true])
					@include('layouts.form-errors')
					<div class="row">
						<div class="col-md-3" id="helpCodeSaleInventoryProduct">
							<div class="form-group">
								{!! Form::label('sale_warehouse_inventory_products_code', 'Código para los productos', []) !!}
								{!! Form::text('products_code', ($pdCode) ? $pdCode->format_code : old('warehouse_inventory_products_code'), [
									'class' => 'form-control input-sm', 'data-toggle' => 'tooltip',
									'title' => 'Formato para el código de los productos',
									'placeholder' => 'Ej. XXX-00000000-YYYY',
									'readonly' => ($pdCode) ? true : false
								]) !!}
							</div>
						</div>
						<div class="col-md-3" id="helpCodeSaleMovement">
							<div class="form-group">
								{!! Form::label('sale_warehouse_movements_code', 'Código para los movimientos', []) !!}
								{!! Form::text('movements_code', ($mvCode) ? $mvCode->format_code : old('movements_code'), [
									'class' => 'form-control input-sm', 'data-toggle' => 'tooltip',
									'title' => 'Formato para el código de los movimientos',
									'placeholder' => 'Ej. XXX-00000000-YYYY',
									'readonly' => ($mvCode) ? true : false
								]) !!}
							</div>
						</div>
						<div class="col-md-3" id="helpCodeSaleBill">
							<div class="form-group">
								{!! Form::label('sale_bills_code', 'Código para las facturas', []) !!}
								{!! Form::text('bills_code', ($billCode) ? $billCode->format_code : old('bills_code'), [
									'class' => 'form-control input-sm', 'data-toggle' => 'tooltip',
									'title' => 'Formato para el código de las facturas',
									'placeholder' => 'Ej. XXX-00000000-YYYY',
									'readonly' => ($billCode) ? true : false
								]) !!}
							</div>
						</div>
						<div class="col-md-3" id="helpCodeSaleService">
							<div class="form-group">
								{!! Form::label('sale_service_code', 'Código para las solicitudes de servicios', []) !!}
								{!! Form::text('services_code', ($serviceCode) ? $serviceCode->format_code : old('services_code'), [
									'class' => 'form-control input-sm', 'data-toggle' => 'tooltip',
									'title' => 'Formato para el código de las solicitudes de servicios',
									'placeholder' => 'Ej. XXX-00000000-YYYY',
									'readonly' => ($serviceCode) ? true : false
								]) !!}
							</div>
						</div>
						<div class="col-md-3" id="helpCodeSaleWarehouseReport">
							<div class="form-group">
								{!! Form::label('sale_warehouse_report_code', 'Código para los reportes de inventario de productos', []) !!}
								{!! Form::text('reports_code', ($saleWarehouseReportCode) ? $saleWarehouseReportCode->format_code : old('reports_code'), [
									'class' => 'form-control input-sm', 'data-toggle' => 'tooltip',
									'title' => 'Formato para el código de los reportes de inventario de productos',
									'placeholder' => 'Ej. XXX-00000000-YYYY',
									'readonly' => ($saleWarehouseReportCode) ? true : false
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