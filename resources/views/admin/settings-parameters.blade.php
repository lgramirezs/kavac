<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h6 class="card-title">
					{{ __('Parámetros Generales') }}
					@include('buttons.help', [
						'helpId' => 'GeneralParams',
						'helpSteps' => get_json_resource('ui-guides/general_parameters.json')
					])
				</h6>
				<div class="card-btns">
					@include('buttons.previous', ['route' => url()->previous()])
					@include('buttons.minimize')
				</div>
			</div>
			{!! Form::open($header_parameters) !!}
				<div class="card-body" id="card_general_params">
					<div class="row">
						<div class="col-12">
							<h6>{{ __('Activar funciones de la aplicación') }}</h6>
						</div>
					</div>
					@if (
						$errors->has('support') || $errors->has('chat') || $errors->has('notify') || 
						$errors->has('report_banner') || $errors->has('multi_institution') || 
						$errors->has('digital_sign') || $errors->has('online')
					)
						@include('layouts.form-errors')
					@endif
					<div class="row">
						<div id="switchNotify" class="col-12 col-md-3">
							<div class="form-group">
								<label for="" class="control-label">{{ __('Notificaciones') }}</label>
								<div class="col-12 bootstrap-switch-mini">
									{!! Form::checkbox('notify', true,
										(!is_null($paramNotify) && $paramNotify->p_value === 'true'), [
										'id' => 'notify', 'class' => 'form-control bootstrap-switch',
										'data-on-label' => __('SI'), 'data-off-label' => __('NO')
									]) !!}
								</div>
							</div>
						</div>
						<div id="switchBannerInReport" class="col-12 col-md-3">
							<div class="form-group">
								<label for="" class="control-label">{{ __('Banner en reportes') }}</label>
								<div class="col-12 bootstrap-switch-mini">
									{!! Form::checkbox('report_banner', true,
										(!is_null($paramReportBanner) && $paramReportBanner->p_value === 'true'), [
										'id' => 'report_banner', 'class' => 'form-control bootstrap-switch',
										'data-on-label' => __('SI'), 'data-off-label' => __('NO')
									]) !!}
								</div>
							</div>
						</div>
						<div id="switchMultiInstitution" class="col-12 col-md-3">
							<div class="form-group">
								<label for="" class="control-label">
                                    {{ __('Multi gestión') }}
                                </label>
								<div class="col-12 bootstrap-switch-mini">
									{!! Form::checkbox('multi_institution', true,
										(
											!is_null($paramMultiInstitution)
											&& $paramMultiInstitution->p_value === 'true'
										), [
										'id' => 'multi_institution', 'class' => 'form-control bootstrap-switch',
										'data-on-label' => __('SI'), 'data-off-label' => __('NO')
									]) !!}
								</div>
							</div>
						</div>
						<div id="switchSign" class="col-12 col-md-3">
							<div class="form-group">
								<label for="" class="control-label">{{ __('Firma electrónica') }}</label>
								<div class="col-12 bootstrap-switch-mini">
									{!! Form::checkbox('digital_sign', true,
										(!is_null($paramDigitalSign) && $paramDigitalSign->p_value === 'true'), [
										'id' => 'digital_sign', 'class' => 'form-control bootstrap-switch',
										'data-on-label' => __('SI'), 'data-off-label' => __('NO')
									]) !!}
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card-footer text-right">
					<div class="row">
						<div id="settingParamButtons" class="col-md-3 offset-md-9">
							@include('layouts.form-buttons')
						</div>
					</div>
				</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>
