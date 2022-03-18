@section('modules-js')
    @parent
    {!! Html::script(mix('modules/asset/js/app.js'), [], Request::secure()) !!}
@endsection

{{-- Gráficos Estadísticos --}}
<asset-dashboard-graphs></asset-dashboard-graphs>

{{-- Histórico de Operaciones --}}
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h6 class="card-title">
					Histórico de Operaciones del Módulo de Bienes
					@include('buttons.help', [
                        'helpId' => 'developmentTools',
                        'helpSteps' => get_json_resource('ui-guides/development_tools.json')
                    ])
				</h6>
				<div class="card-btns">
					@include('buttons.previous', ['route' => url()->previous()]) 
					@include('buttons.minimize')
				</div>
			</div>
			<div class="card-body">
				<asset-operations-history-list
					route_list="{{ url('asset/dashboard/operations/vue-list') }}">
				</asset-operations-history-list>
			</div>
		</div>
	</div>
</div>
