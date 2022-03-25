<div class="row">
	<div class="col-12">
		<div id="card_config_institution" class="card">
			<div class="card-header">
				<h6 class="card-title">
					{{ __('Configurar Organización') }}
					@include('buttons.help', [
						'helpId' => 'institution',
						'helpSteps' => get_json_resource('ui-guides/institution.json')
					])
				</h6>
				<div class="card-btns">
					@include('buttons.previous', ['route' => url()->previous()])
					@include('buttons.minimize')
				</div>
			</div>
			<div class="card-body">
				@if (
					$errors->has('onapre_code') || $errors->has('rif') || $errors->has('name') || 
					$errors->has('acronym') || $errors->has('business_name') || $errors->has('country_id') || 
					$errors->has('estate_id') || $errors->has('municipality_id') || $errors->has('city_id') || 
					$errors->has('parish_id') || $errors->has('postal_code') || 
					$errors->has('start_operations_date') || $errors->has('organism_adscript_id') || 
					$errors->has('institution_sector_id') || $errors->has('institution_type_id') || 
					$errors->has('legal_address') || $errors->has('active') || $errors->has('default') || 
					$errors->has('retention_agent') || $errors->has('web') || $errors->has('social_networks') || 
					$errors->has('legal_base') || $errors->has('legal_form') || $errors->has('main_activity') || 
					$errors->has('mission') || $errors->has('vision') || $errors->has('composition_assets')
				)
					@include('layouts.form-errors')
				@endif
				<div id="helpInstitutionImgs" class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label for="">{{ __('Logotipo') }}</label>
							{!! Form::open([
                                'id' => 'formImgLogo', 'method' => 'POST', 'route' => 'upload-image.store',
                                'role' => 'form', 'class' => 'form', 'enctype' => 'multipart/form-data'
                            ]) !!}
                                @php
                                	$img_logo = (
                                		isset($model_institution) && !is_null($model_institution->logo) &&
                                        file_exists(base_path($model_institution->logo->url))
                                	) ? $model_institution->logo->url : null;
                                @endphp
                                <img src="{{ asset($img_logo ?? 'images/no-image2.png') }}"
                                     alt="{{ __('Logotipo') }}"
                                     class="img-fluid institution-logo" style="cursor:pointer"
                                     title="{{ __('Click para cargar o modificar la imagen') }}" data-toggle="tooltip"
                                     onclick="$('input[name=logo_image]').click()">
                                <input id="logo_image" type="file" name="logo_image" style="display:none"
                                       onchange="uploadSingleImage('formImgLogo', 'logo_image', 'logo_id', 'institution-logo')">
                                <div class="row row-delete-img">
                                	<div class="col-12">
                                		<div class="institution-logo text-center">
                                			<a class="img-delete" href="javascript:void(0)"
                                			   onclick="deleteImage($(this), $('#logo_id').val(), '2')">
                                                {{ __('Eliminar') }}
                                            </a>
                                		</div>
                                	</div>
                                </div>
                            {!! Form::close() !!}
						</div>
					</div>
					<div class="col-md-8">
						<div class="form-group">
							<label for="">{{ __('Banner o Cintillo') }}</label>
							{!! Form::open([
                                'id' => 'formImgBanner', 'method' => 'POST', 'route' => 'upload-image.store',
                                'role' => 'form', 'class' => 'form', 'enctype' => 'multipart/form-data'
                            ]) !!}
                                @php
                                	$img_banner = (
                                		isset($model_institution) && !is_null($model_institution->banner) &&
                                        file_exists(base_path($model_institution->banner->url))
                                	) ? $model_institution->banner->url : null;
                                @endphp
                                <img src="{{ asset($img_banner ?? 'images/no-image3.png') }}"
                                     alt="{{ __('Banner / Cintillo') }}"
                                     class="img-fluid institution-banner" style="cursor:pointer"
                                     title="{{ __('Click para cargar o modificar la imagen') }}" data-toggle="tooltip"
                                     onclick="$('input[name=banner_image]').click()">
                                <input type="file" id="banner_image" name="banner_image" style="display:none"
                                       onchange="uploadSingleImage('formImgBanner', 'banner_image', 'banner_id', 'institution-banner')">
                                <div class="row row-delete-img">
                                	<div class="col-12">
                                		<div class="text-center">
                                			<a class="img-delete" href="javascript:void(0)"
                                			   onclick="deleteImage($(this), $('#banner_id').val(), '3')">
                                				{{ __('Eliminar') }}
                                			</a>
                                		</div>
                                	</div>
                                </div>
                            {!! Form::close() !!}
						</div>
					</div>
				</div>
			</div>
			{!! Form::model($model_institution, $header_institution) !!}
				<div class="card-body">
					{!! Form::hidden('logo_id', null, ['readonly' => 'readonly', 'id' => 'logo_id']) !!}
					{!! Form::hidden('banner_id', null, ['readonly' => 'readonly', 'id' => 'banner_id']) !!}
					{!! Form::hidden('institution_id', (isset($model_institution))?$model_institution->id:'', [
						'readonly' => 'readonly', 'id' => 'institution_id'
					]) !!}

					<hr>
					<h6 class="md-title">{{ __('Datos Básicos') }}:</h6>
					<div id="helpInstitutionBasicData">
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									{!! Form::label('onapre_code', __('Código ONAPRE'), []) !!}
									{!! Form::text('onapre_code',
										(isset($model_institution))?$model_institution->onapre_code:old('onapre_code'), [
											'class' => 'form-control input-sm', 'id' => 'onapre_code',
											'data-toggle' => 'tooltip',
											'title' => __('Indique el código ONAPRE asignado a la organización (requerido)')
										]
									) !!}
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group{{ $errors->has('rif') ? ' has-error' : '' }} is-required">
									{!! Form::label('rif', __('R.I.F.'), []) !!}
									{!! Form::text('rif',
										(isset($model_institution))?$model_institution->rif:old('rif'), [
											'class' => 'form-control input-sm', 'id' => 'rif',
											'data-toggle' => 'tooltip',
											'title' => __('Indique el número de registro de identificación fiscal (requerido)')
										]
									) !!}
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group is-required{{ $errors->has('name') ? ' has-error' : '' }}">
									{!! Form::label('name', __('Nombre'), []) !!}
									{!! Form::text('name',
										(isset($model_institution))?$model_institution->name:old('name'), [
											'class' => 'form-control input-sm',
											'data-toggle' => 'tooltip',
											'title' => __('Introduzca el nombre de la organización (requerido)')
										]
									) !!}
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group is-required{{ $errors->has('acronym') ? ' has-error' : '' }}">
									{!! Form::label('acronym', __('Acrónimo (Nombre corto)'), []) !!}
									{!! Form::text('acronym',
										(isset($model_institution))?$model_institution->acronym:old('acronym'), [
											'class' => 'form-control input-sm', 'id' => 'acronym',
											'data-toggle' => 'tooltip',
											'title' => __('Introduzca el nombre corto de la organización')
										]
									) !!}
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group is-required">
									{!! Form::label('business_name', __('Razón Social'), []) !!}
									{!! Form::text('business_name',
										(isset($model_institution))?$model_institution->business_name:old('business_name'), [
											'class' => 'form-control input-sm', 'id' => 'business_name',
											'data-toggle' => 'tooltip',
											'title' => __('Introduzca la razón social')
										]
									) !!}
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group is-required">
									{!! Form::label('country_id', __('País'), []) !!}
									{!! Form::select('country_id', (isset($countries))?$countries:[], (isset($model_institution)) ? $model_institution->city->estate->country->id : null, [
										'class' => 'form-control select2 input-sm', 'id' => 'country_id',
										'onchange' => 'updateSelect($(this), $("#estate_id"), "Estate")'
									]) !!}
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group is-required">
									{!! Form::label('estate_id', __('Estado'), []) !!}
									{!! Form::select('estate_id', (isset($estates))?$estates:[], (isset($model_institution)) ? $model_institution->city->estate->id : old('estate_id'), [
										'class' => 'form-control select2', 'id' => 'estate_id',
										'onchange' => 'updateSelect($(this), $("#municipality_id"), "Municipality"), updateSelect($(this), $("#city_id"), "City")',
										//'disabled' => (!isset($model_institution))
									]) !!}
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group is-required">
									{!! Form::label('municipality_id', __('Municipio'), []) !!}
									{!! Form::select(
										'municipality_id', (isset($municipalities))?$municipalities:[], (isset($model_institution)) ? $model_institution->municipality_id : old('municipality_id'), [
											'class' => 'form-control select2', 'id' => 'municipality_id',
											'onchange' => 'updateSelect($(this), $("#parish_id"), "Parish")',
											//'disabled' => (!isset($model_institution))
										]
									) !!}
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group is-required">
									{!! Form::label('city_id', __('Ciudad'), []) !!}
									{!! Form::select('city_id', (isset($cities))?$cities:[], (isset($model_institution))? $model_institution->city_id : old('city_id'), [
										'class' => 'form-control select2', 'id' => 'city_id',
										//'disabled' => (!isset($model_institution))
									]) !!}
								</div>
							</div>
						</div>
						<div class="row">
							{{-- <div class="col-md-4">
								<div class="form-group">
									{!! Form::label('parish_id', 'Parroquia', []) !!}
									{!! Form::select('parish_id', (isset($parishes))?$parishes:[], null, [
										'class' => 'form-control select2', 'id' => 'parish_id',
										'disabled' => (!isset($model_institution))
									]) !!}
								</div>
							</div> --}}
							<div class="col-md-4">
								<div class="form-group is-required">
									{!! Form::label('postal_code', __('Código Postal'), []) !!}
									{!! Form::text('postal_code',
										(isset($model_institution))?$model_institution->postal_code:old('postal_code'), [
											'class' => 'form-control input-sm', 'id' => 'postal_code',
											'data-toggle' => 'tooltip',
											'title' => __('Indique el código postal (requerido)')
										]
									) !!}
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group is-required">
									{!! Form::label('start_operations_date', __('Fecha de inicio de operaciones'), []) !!}
									{!! Form::date('start_operations_date',
										(isset($model_institution))?$model_institution->start_operations_date:old('start_operations_date'), [
											'class' => 'form-control input-sm', 'id' => 'start_operations_date',
											'data-toggle' => 'tooltip',
											'title' => __('Indique la fecha de inicio de operaciones (requerido)')
										]
									) !!}
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									{!! Form::label('organism_adscript_id', __('Adscrito a'), []) !!}
									{!! Form::select(
										'organism_adscript_id',
										(isset($organism_adscripts))?$organism_adscripts:[], null, [
											'class' => 'form-control select2', 'id' => 'organism_adscript_id'
										]
									) !!}
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group is-required">
									{!! Form::label('institution_sector_id', __('Sector'), []) !!}
									{!! Form::select('institution_sector_id', (isset($sectors))?$sectors:[], null, [
										'class' => 'form-control select2', 'id' => 'institution_sector_id'
									]) !!}
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group is-required">
									{!! Form::label('institution_type_id', __('Tipo'), []) !!}
									{!! Form::select('institution_type_id', (isset($types))?$types:[], null, [
										'class' => 'form-control select2', 'id' => 'institution_type_id'
									]) !!}
								</div>
							</div>
						</div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group is-required">
                                    {!! Form::label('legal_address', __('Dirección Fiscal'), []) !!}
                                    <ckeditor :editor="ckeditor.editor" id="legal_address" data-toggle="tooltip"
                                              title="{!! __('Indique la dirección fiscal de la organización (requerido)') !!}"
                                              :config="ckeditor.editorConfig" class="form-control" name="legal_address"
                                              tag-name="textarea" rows="4"
                                              value="{!!
                                                (isset($model_institution))
                                                ? $model_institution->legal_address
                                                : old('legal_address')
                                              !!}"></ckeditor>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    {!! Form::label('active', __('Activa'), []) !!}
                                    <div class="col-12">
                                        <div class="col-12 bootstrap-switch-mini">
                                            {!! Form::checkbox('active', true, null, [
                                                'id' => 'active', 'class' => 'form-control bootstrap-switch',
                                                'data-on-label' => __('SI'), 'data-off-label' => __('NO')
                                            ]) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('default', __('Organización por defecto'), []) !!}
                                    <div class="col-12">
                                        <div class="col-12 bootstrap-switch-mini">
                                            {!! Form::checkbox('default', true, (isset($model_institution) && $model_institution->default)?null:true, [
                                                'id' => 'default', 'class' => 'form-control bootstrap-switch',
                                                'data-on-label' => __('SI'), 'data-off-label' => __('NO')
                                            ]) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    {!! Form::label('retention_agent', __('Agente de Retención'), []) !!}
                                    <div class="col-12">
                                        <div class="col-12 bootstrap-switch-mini">
                                            {!! Form::checkbox('retention_agent', true, null, [
                                                'id' => 'retention_agent', 'class' => 'form-control bootstrap-switch',
                                                'data-on-label' => __('SI'), 'data-off-label' => __('NO')
                                            ]) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
						<div class="row">
							<div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::label('web', __('Sitio Web'), []) !!}
                                    {!! Form::text('web',
                                        (isset($model_institution))?$model_institution->web:old('web'), [
                                            'class' => 'form-control input-sm', 'id' => 'web',
                                            'data-toggle' => 'tooltip',
                                            'title' => __('Indique la URL del sitio web')
                                        ]
                                    ) !!}
                                </div>
							</div>
							{{-- <div class="col-md-4">
								<div class="form-group">
									{!! Form::label('social_networks', __('Redes Sociales'), []) !!}
									{!! Form::select(
										'social_networks', (isset($social_networks))?$social_networks:[], null, [
											'class' => 'form-control select2', 'multiple' => 'multiple',
											'id' => 'social_networks'
										]
									) !!}
								</div>
							</div> --}}
						</div>
					</div>
					<hr>
					<h6 class="md-title">{{ __('Datos Complementarios') }}:</h6>
					<div id="helpInstitutionComplementaryData">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									{!! Form::label('legal_base', __('Base Legal'), []) !!}
                                    <ckeditor :editor="ckeditor.editor" id="legal_base" data-toggle="tooltip"
                                              title="{!! __('Indique la base legal constitutiva de la organización') !!}"
                                              :config="ckeditor.editorConfig" class="form-control" name="legal_base"
                                              tag-name="textarea" rows="4"
                                              value="{!!
                                                (isset($model_institution))
                                                ? $model_institution->legal_base
                                                : old('legal_base')
                                              !!}"></ckeditor>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									{!! Form::label('legal_form', __('Forma Jurídica'), []) !!}
                                    <ckeditor :editor="ckeditor.editor" id="legal_form" data-toggle="tooltip"
                                              title="{!! __('Indique la forma jurídica de la organización') !!}"
                                              :config="ckeditor.editorConfig" class="form-control" name="legal_form"
                                              tag-name="textarea" rows="4"
                                              value="{!!
                                                (isset($model_institution))
                                                ? $model_institution->legal_form
                                                : old('legal_form')
                                              !!}"></ckeditor>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									{!! Form::label('main_activity', __('Actividad Principal'), []) !!}
                                    <ckeditor :editor="ckeditor.editor" id="main_activity" data-toggle="tooltip"
                                              title="{!! __('Indique la actividad principal a la cual se dedica la organización') !!}"
                                              :config="ckeditor.editorConfig" class="form-control" name="main_activity"
                                              tag-name="textarea" rows="4"
                                              value="{!!
                                                (isset($model_institution))
                                                ? $model_institution->main_activity
                                                : old('main_activity')
                                              !!}"></ckeditor>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									{!! Form::label('mission', __('Misión'), []) !!}
                                    <ckeditor :editor="ckeditor.editor" id="mission" data-toggle="tooltip"
                                              title="{!! __('Indique la misión de la organización') !!}"
                                              :config="ckeditor.editorConfig" class="form-control" name="mission"
                                              tag-name="textarea" rows="4"
                                              value="{!!
                                                (isset($model_institution))
                                                ? $model_institution->mission
                                                : old('mission')
                                              !!}"></ckeditor>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									{!! Form::label('vision', __('Visión'), []) !!}
                                    <ckeditor :editor="ckeditor.editor" id="vision" data-toggle="tooltip"
                                              title="{!! __('Indique la visión de la organización') !!}"
                                              :config="ckeditor.editorConfig" class="form-control" name="vision"
                                              tag-name="textarea" rows="4"
                                              value="{!!
                                                (isset($model_institution))
                                                ? $model_institution->vision
                                                : old('vision')
                                              !!}"></ckeditor>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									{!! Form::label('composition_assets', __('Composición de Patrimonio'), []) !!}
                                    <ckeditor :editor="ckeditor.editor" id="composition_assets" data-toggle="tooltip"
                                              title="{!! __('Indique la composición patrimonial de la organización') !!}"
                                              :config="ckeditor.editorConfig" class="form-control"
                                              name="composition_assets" tag-name="textarea" rows="4"
                                              value="{!!
                                                (isset($model_institution))
                                                ? $model_institution->composition_assets
                                                : old('composition_assets')
                                              !!}"></ckeditor>
								</div>
							</div>
						</div>
					</div>

					@if (!is_null($paramMultiInstitution))
						<hr>
						<h6 class="md-title card-title">{{ __('Organizaciones Registradas') }}</h6>
						<div class="row">
							<div class="col-12 text-right">
								@include('buttons.new', ['route' => 'javascript:void(0)', 'btnClass' => 'btn btn-sm btn-primary btn-custom btn-mini btn-new btn-new-institution'])
							</div>
						</div>

						<table class="table table-hover table-striped dt-responsive nowrap datatable"
							   id="helpInstitutionList">
							<thead>
								<tr>
									<th class="col-md-1">{{ __('Logo') }}</th>
									<th class="col-md-1">{{ __('R.I.F') }}</th>
									<th class="col-md-1">{{ __('Código ONAPRE') }}</th>
									<th class="col-md-6">{{ __('Nombre') }}</th>
									<th class="col-md-1">{{ __('Activa') }}</th>
									<th class="col-md-2">{{ __('Acción') }}</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($institutions as $institution)
									<tr>
										<td class="text-center">
											@if (!is_null($institution->logo))
												<img src="{{ url($institution->logo->url) }}"
													 alt="{{ __('logo') }}" class="img-fluid"
													 style="max-height:50px;">
											@endif
										</td>
										<td>
											{{ $institution->rif }}
										</td>
										<td>{{ $institution->onapre_code }}</td>
										<td>
											@if ($institution->acronym)
												{{ $institution->acronym }} -
											@endif
											{{ $institution->name }}
										</td>
										<td class="text-center">
											<span class="text-bold text-{{ ($institution->active)?'success':'danger' }}">
												{{ ($institution->active)?__('SI'):__('NO') }}
											</span>
										</td>
										<td class="text-center">
											<a class="btn btn-info btn-xs btn-icon btn-action" data-toggle="tooltip"
												href="javascript:void(0)" title="Ver registro" v-has-tooltip
												onclick="showInstitution('{{ $institution->id }}')">
												<i class="fa fa-eye"></i>
											</a>
											<a class="btn btn-warning btn-xs btn-icon btn-action" data-toggle="tooltip"
												href="javascript:void(0)" title="Modificar registro" v-has-tooltip
												onclick="loadInstitution('{{ $institution->id }}')">
												<i class="fa fa-edit"></i>
											</a>
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					@endif
				</div>
				<div class="card-footer text-right">
					<div class="row">
						<div class="col-md-3 offset-md-9" id="helpInstitutionButtons">
							@include('layouts.form-buttons')
						</div>
					</div>
				</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>

<!-- Modal -->
<div id="detailsInstitutionModal" class="modal fade" tabindex="-1" aria-labelledby="detailsInstitutionModalLabel"
	aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">DATOS DE LA ORGANIZACIÓN</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				{{-- Imágenes --}}
				<div class="row justify-content-center">
					<div class="col-8 col-lg-3">
						<p class="text-center mb-1 font-weight-bold">Logotipo</p>
						<img id="modal-logo" class="w-100" src="{{ asset('/images/no-image2.png', Request::secure()) }}">
					</div>
					<div class="col-8 col-lg-7">
						<p class="text-center mb-1 font-weight-bold">Banner o Cintillo</p>
						<img id="modal-banner" class="w-100" src="{{ asset('/images/no-image3.png') }}">
					</div>
				</div>
				{{-- Detalles --}}
                <h6 class="md-title mt-3 mb-3">DATOS BÁSICOS:</h6>
                <div class="row justify-content-center">
					<div class="col-4">
                        <span class="font-weight-bold">Código ONAPRE</span>
                        <br>
                        <span id="modal-onapre_code"></span>
					</div>
					<div class="col-4">
                        <span class="font-weight-bold">R.I.F.</span>
                        <br>
                        <span id="modal-rif"></span>
					</div>
					<div class="col-4">
                        <span class="font-weight-bold">Nombre</span>
                        <br>
                        <span id="modal-name"></span>
					</div>
				</div>
                <hr>
                <div class="row justify-content-center">
					<div class="col-4">
                        <span class="font-weight-bold">Acrónimo (Nombre corto)</span>
                        <br>
                        <span id="modal-acronym"></span>
					</div>
					<div class="col-4">
                        <span class="font-weight-bold">Razón Social</span>
                        <br>
                        <span id="modal-business_name"></span>
					</div>
					<div class="col-4">
                        <span class="font-weight-bold">País</span>
                        <br>
                        <span id="modal-country_id"></span>
					</div>
				</div>
                <hr>
                <div class="row justify-content-center">
					<div class="col-4">
                        <span class="font-weight-bold">Estado</span>
                        <br>
                        <span id="modal-estate_id"></span>
					</div>
					<div class="col-4">
                        <span class="font-weight-bold">Municipio</span>
                        <br>
                        <span id="modal-municipality_id"></span>
					</div>
					<div class="col-4">
                        <span class="font-weight-bold">Ciudad</span>
                        <br>
                        <span id="modal-city_id"></span>
					</div>
				</div>
                <hr>
                <div class="row justify-content-center">
					<div class="col-4">
                        <span class="font-weight-bold">Código Postal</span>
                        <br>
                        <span id="modal-postal_code"></span>
					</div>
					<div class="col-4">
                        <span class="font-weight-bold">Fecha de inicio de operaciones</span>
                        <br>
                        <span id="modal-start_operations_date"></span>
					</div>
					<div class="col-4">
					</div>
				</div>
                <hr>
                <div class="row justify-content-center">
					<div class="col-4">
                        <span class="font-weight-bold">Adscrito a</span>
                        <br>
                        <span id="modal-organism_adscript_id"></span>
					</div>
					<div class="col-4">
                        <span class="font-weight-bold">Sector</span>
                        <br>
                        <span id="modal-institution_sector_id"></span>
					</div>
					<div class="col-4">
                        <span class="font-weight-bold">Tipo</span>
                        <br>
                        <span id="modal-institution_type_id"></span>
					</div>
				</div>
                <hr>
                <div class="row justify-content-center">
					<div class="col-12">
                        <span class="font-weight-bold">Dirección Fiscal</span>
                        <br>
                        <span id="modal-legal_address"></span>
					</div>
				</div>
                <hr>
                <div class="row justify-content-center">
					<div class="col-4">
                        <span class="font-weight-bold">Activa</span>
                        <br>
                        <span class="text-bold text-{{ ($institution->active)?'success':'danger' }}" id="modal-active"></span>
					</div>
					<div class="col-4">
                        <span class="font-weight-bold">Organización por defecto</span>
                        <br>
                        <span id="modal-default"></span>
					</div>
					<div class="col-4">
                        <span class="font-weight-bold">Agente de Retención</span>
                        <br>
                        <span id="modal-retention_agent"></span>
					</div>
				</div>
                <hr>
                <div class="row justify-content-center">
					<div class="col-4">
                        <span class="font-weight-bold">Sitio web</span>
                        <br>
                        <span id="modal-web"></span>
					</div>
					<div class="col-4">
					</div>
					<div class="col-4">
					</div>
				</div>
                <hr>
                <h6 class="md-title mt-3 mb-3">DATOS COMPLEMENTARIOS:</h6>
                <div class="row justify-content-center">
					<div class="col-6">
                        <span class="font-weight-bold">Base Legal</span>
                        <br>
                        <span id="modal-legal_base"></span>
					</div>
					<div class="col-6">
                        <span class="font-weight-bold">Forma Jurídica</span>
                        <br>
                        <span id="modal-legal_form"></span>
					</div>
				</div>
                <hr>
                <div class="row justify-content-center">
					<div class="col-6">
                        <span class="font-weight-bold">Actividad Principal</span>
                        <br>
                        <span id="modal-main_activity"></span>
					</div>
					<div class="col-6">
                        <span class="font-weight-bold">Misión</span>
                        <br>
                        <span id="modal-mission"></span>
					</div>
				</div>
                <hr>
                <div class="row justify-content-center">
					<div class="col-6">
                        <span class="font-weight-bold">Visión</span>
                        <br>
                        <span id="modal-vision"></span>
					</div>
					<div class="col-6">
                        <span class="font-weight-bold">Composición de Patrimonio</span>
                        <br>
                        <span id="modal-composition_assets"></span>
					</div>
				</div>
            </div>
            <hr>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>

@section('extra-js')
	@parent
	{!! Html::script('js/ckeditor.js', [], Request::secure()) !!}
	<script>
		$(document).ready(function() {
			if (typeof CkEditor !== 'undefined') {
				$.each([
					'legal_address', 'legal_base', 'legal_form', 'main_activity',
                    'mission', 'vision', 'composition_assets'
				], function(index, element_id) {
					CkEditor.create(document.querySelector(`#${element_id}`), {
			            toolbar: [
			                'heading', '|',
			                'bold', 'italic', 'blockQuote', 'link', 'numberedList', 'bulletedList', '|',
			                'insertTable'
			            ],
			            language: '{{ app()->getLocale() }}',
			        }).then(editor => {
			            window.editor = editor;
			        }).catch(error => {
			            logs('setting-institution', 489, error);
			        });
				});
			}
			@if (!is_null($paramMultiInstitution))
				$(".btn-new-institution").on('click', function() {
					var form = $("#card_config_institution form");
					var clearEl = {
						val: [
							'input[type=text]', 'input[type=date]',
							'.select2:not(select[name^=DataTable])', 'textarea',
							"#logo_id", "#banner_id"
						],
						attr: [

						]
					};
					$.each(clearEl.val, function(index, el) {
						form.find(el).val('');
					});
					form.find('.select2').trigger('change');
					form.find('input[type=checkbox]').attr('checked', false);
					form.find('input[type=radio]').attr('checked', false);
					form.find('.bootstrap-switch').removeClass('bootstrap-switch-on');
					form.find('.bootstrap-switch').addClass('bootstrap-switch-off');
					form.find(".institution-logo").attr('src', "{{ asset('/images/no-image2.png', Request::secure()) }}");
					form.find(".institution-banner").attr('src', "{{ asset('/images/no-image3.png', Request::secure()) }}");
					form.find("#onapre_code").focus();
				});
			@endif
		});

		/**
		 * Carga datos de la organización seleccionada
		 *
		 * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
         *
		 * @param  {integer} id Identificador de la Organización a cargar
		 */
		var loadInstitution = function(id) {
			axios.get(`get-institution/details/${id}`).then(response => {
				if (response.data.result) {
					var institution = response.data.institution;
                    var activeSwitchRemoveClass = (institution.active) ? 'off' : 'on';
                    var activeSwitchAddClass = (institution.active) ? 'on' : 'off';
                    var defaultSwitchRemoveClass = (institution.default) ? 'off' : 'on';
                    var defaultSwitchAddClass = (institution.default) ? 'on' : 'off';
                    var retAgentSwitchRemoveClass = (institution.retention_agent) ? 'off' : 'on';
                    var retAgentSwitchAddClass = (institution.retention_agent) ? 'on' : 'off';

					$(".institution-logo").attr('src', "{{ asset('/images/no-image2.png', Request::secure()) }}");
					$("#logo_id").val('');
					$(".institution-banner").attr('src', "{{ asset('/images/no-image3.png') }}");
					$("#banner_id").val('');

                    if (institution.logo) {
						$(".institution-logo").attr('src', `${window.app_url}/${institution.logo.url}`);
						$(".institution-logo").closest('.form-group').find('.row-delete-img').show();
						$("#logo_id").val(institution.logo.id);
					}

                    if (institution.banner) {
						$(".institution-banner").attr('src', `${window.app_url}/${institution.banner.url}`);
						$(".institution-banner").closest('.form-group').find('.row-delete-img').show();
						$("#banner_id").val(institution.banner.id);
					}

                    $("#institution_id").val(institution.id);
					$("#onapre_code").val(institution.onapre_code);
					$("#rif").val(institution.rif);
					$("input[name=name]").val(institution.name);
					$("#acronym").val(institution.acronym);
					$("#business_name").val(institution.business_name);
					$("#country_id").val(institution.municipality.estate.country.id);
					$("#country_id").trigger('change');
					$("#estate_id").val(institution.municipality.estate.id);
					$("#estate_id").trigger('change');
					$("#municipality_id").val(institution.municipality.id);
					$("#municipality_id").trigger('change');
					$("#city_id").val(institution.city_id);
					$("#city_id").trigger('change');
					$("#postal_code").val(institution.postal_code);
					$("#start_operations_date").val(institution.start_operations_date);
					$("#institution_sector_id").val(institution.institution_sector_id);
					$("#institution_sector_id").trigger('change');
					$("#institution_type_id").val(institution.institution_type_id);
					$("#institution_type_id").trigger('change');
					$("#legal_address").val(institution.legal_address);
					$("#web").val(institution.web);
					$("#social_networks").val(institution.social_networks);
					$("#social_networks").trigger('change');
					$('#active').attr('checked', institution.active);
                    $('#active.bootstrap-switch').removeClass(`bootstrap-switch-${activeSwitchRemoveClass}`);
					$('#active.bootstrap-switch').addClass(`bootstrap-switch-${activeSwitchAddClass}`);
					$('#default').attr('checked', institution.default);
					$('#default.bootstrap-switch').removeClass(`bootstrap-switch-${defaultSwitchRemoveClass}`);
					$('#default.bootstrap-switch').addClass(`bootstrap-switch-${defaultSwitchAddClass}`);
					$('#retention_agent').attr('checked', institution.retention_agent);
					$('#retention_agent.bootstrap-switch').removeClass(`bootstrap-switch-${retAgentSwitchRemoveClass}`);
					$('#retention_agent.bootstrap-switch').addClass(`bootstrap-switch-${retAgentSwitchAddClass}`);
					$("#legal_base").val(institution.legal_base);
					$("#legal_form").val(institution.legal_form);
					$("#main_activity").val(institution.main_activity);
					$("#mission").val(institution.mission);
					$("#vision").val(institution.vision);
					$("#composition_assets").val(institution.composition_assets);


				    // Lo envía a la cabecera del formulario
				    var targetOffset = $('#card_config_institution').offset().top;
				    $('html, body').animate({scrollTop: targetOffset}, 0);

				    // Enfoca el input de onapre
				    $("#onapre_code").focus();
				}
			}).catch(error => {
				logs('setting-institution', 594, error, 'loadInstitution');
			});
		}
		@if (old('country_id')!=='')
			$("#country_id").click();
		@endif
		@if (old('estate_id')!=='')
			$("#estate_id").click();
		@endif
		@if (old('municipality_id')!=='')
			$("#municipality_id").click();
		@endif
	</script>
	<script>
		/**
		 * Abre una modal con los datos de la institución seleccionada
		 *
		 * @author Angelo Osorio <adosorio@cenditel.gob.ve> | <danielking.321@gmail.com>
         *
		 * @param  {integer} id Identificador de la Organización a cargar
		 */
		var showInstitution = function(id) {
			axios.get(`get-institution/details/${id}`).then(response => {
				if (response.data.result){
					var institution = response.data.institution;
					var activeInst = (institution.active) ? 'SI' : 'NO';
					var defaultInst = (institution.default) ? 'SI' : 'NO';
					var retAgentInst = (institution.retention_agent) ? 'SI' : 'NO';

					if (institution.logo) {
						$("#modal-logo").attr('src', `${window.app_url}/${institution.logo.url}`);
					}
					if (institution.banner){
						$("#modal-banner").attr('src', `${window.app_url}/${institution.banner.url}`);
					}
					$("#detailsInstitutionModalLabel").html(institution.name);
					if (institution.onapre_code){
						$("#modal-onapre_code").html(institution.onapre_code);
					}
					$("#modal-name").html(institution.name);
					$("#modal-rif").html(institution.rif);
					$("#modal-acronym").html(institution.acronym);
					$("#modal-business_name").html(institution.business_name);
					$("#modal-country_id").html(institution.municipality.estate.country.name);
					$("#modal-estate_id").html(institution.municipality.estate.name);
					$("#modal-municipality_id").html(institution.municipality.name);
					$("#modal-city_id").html(institution.city_id);
					$("#modal-postal_code").html(institution.postal_code);
					$("#modal-start_operations_date").html(institution.start_operations_date);
					if (institution.organism_adscript_id){
						$("#modal-organism_adscript_id").html(institution.organism_adscript_id);
					}
					$("#modal-institution_sector_id").html(institution.institution_sector_id);
					$("#modal-institution_type_id").html(institution.institution_type_id);
					$("#modal-legal_address").html(institution.legal_address);

					if (institution.web){
						$("#modal-web").html(institution.web);
					}

					$('#modal-active').html(activeInst);
					$('#modal-default').html(defaultInst);
					$('#modal-retention_agent').html(retAgentInst);

					if (institution.legal_base){
						$("#modal-legal_base").html(institution.legal_base);
					}
					if (institution.legal_form){
						$("#modal-legal_form").html(institution.legal_form);
					}
					if (institution.main_activity){
						$("#modal-main_activity").html(institution.main_activity);
					}
					if (institution.mission){
						$("#modal-mission").html(institution.mission);
					}
					if (institution.vision){
						$("#modal-vision").html(institution.vision);
					}
					if (institution.composition_assets){
						$("#modal-composition_assets").html(institution.composition_assets);
					}

                    // Abre la modal
					$('#detailsInstitutionModal').modal('show');
				}
			}).catch(error => {
				logs('setting-institution', 594, error, 'loadInstitution');
			});
		}
	</script>

@endsection
