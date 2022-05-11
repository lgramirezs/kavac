@extends('purchase::layouts.master')

@section('maproute-icon')
	<i class="ion-social-dropbox-outline"></i>
@stop

@section('maproute-icon-mini')
	<i class="ion-social-dropbox-outline"></i>
@stop

@section('maproute-actual')
	Compra
@stop

@section('maproute-title')
	Proveedores
@stop

@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h6 class="card-title">Proveedor</h6>
					<div class="card-btns">
						@include('buttons.previous', ['route' => route('purchase.suppliers.index')])
						@include('buttons.minimize')
					</div>
				</div>
				{!! (!isset($model)) ? Form::open($header) : Form::model($model, $header) !!}
					{!! Form::token() !!}
					<div class="card-body">
						@include('layouts.form-errors')
				        <ul class="nav nav-tabs custom-tabs" role="tablist">
							<li class="nav-item">
								<a href="#default_data" class="nav-link active" data-toggle="tab"
								   title="Datos básicos del proveedor">
									Datos Básicos
								</a>
							</li>
							<li class="nav-item">
								<a href="#rnc" class="nav-link" data-toggle="tab"
								   title="Datos de Información del Registro Nacional de Contratistas (RNC)">
									Datos del RNC
								</a>
							</li>
							<li class="nav-item">
								<a href="#requirement_docs" class="nav-link" data-toggle="tab"
								   title="Consignación de requisitos en físico y digital">
									Documentos
								</a>
							</li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="default_data" role="tabpanel">
								<h6 class="card-title">Datos básicos del Proveedor</h6>
								<div class="row">
									<div class="col-4">
										<div class="form-group is-required{{ $errors->has('person_type') ? ' has-error' : '' }}">
											{!! Form::label('person_type', 'Tipo de Persona') !!}
											<div class="col-12">
												<label class="radio-inline">
													<span class="left">Natural</span>
                                                    <div class="col-12 bootstrap-switch-mini">
    													{!! Form::radio('person_type', 'N', null, [
    														'class' => 'form-control bootstrap-switch',
                                                            'data-on-label' => 'SI', 'data-off-label' => 'NO'
    													]) !!}
                                                    </div>
												</label>
												<label class="radio-inline">
													<span class="left">Jurídica</span>
                                                    <div class="col-12 bootstrap-switch-mini">
    													{!! Form::radio('person_type', 'J', null, [
    														'class' => 'form-control bootstrap-switch',
                                                            'data-on-label' => 'SI', 'data-off-label' => 'NO'
    													]) !!}
                                                    </div>
												</label>
											</div>
										</div>
									</div>
									<div class="col-4">
										<div class="form-group is-required{{ $errors->has('company_type') ? ' has-error' : '' }}">
											{!! Form::label('company_type', 'Tipo de Empresa') !!}
											<div class="col-12">
												<label class="radio-inline">
													<span class="left">Pública</span>
                                                    <div class="col-12 bootstrap-switch-mini">
    													{!! Form::radio('company_type', 'PU', null, [
    														'class' => 'form-control bootstrap-switch',
                                                            'data-on-label' => 'SI', 'data-off-label' => 'NO'
    													]) !!}
                                                    </div>
												</label>
												<label class="radio-inline">
													<span class="left">Privada</span>
                                                    <div class="col-12 bootstrap-switch-mini">
    													{!! Form::radio('company_type', 'PR', null, [
    														'class' => 'form-control bootstrap-switch',
                                                            'data-on-label' => 'SI', 'data-off-label' => 'NO'
    													]) !!}
                                                    </div>
												</label>
											</div>
										</div>
									</div>
									<div class="col-4">
										<div class="form-group{{ $errors->has('active') ? ' has-error' : '' }}">
											{!! Form::label('active', 'Activo') !!}
											<div class="col-12">
                                                <div class="col-12 bootstrap-switch-mini">
    												{!! Form::checkbox('active', true, null, [
    													'class' => 'form-control bootstrap-switch',
                                                        'data-on-label' => 'SI', 'data-off-label' => 'NO'
    												]) !!}
                                                </div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-3">
										<div class="form-group is-required{{ $errors->has('rif') ? ' has-error' : '' }}">
											{!! Form::label('rif', 'R.I.F.') !!}
											{!! Form::text('rif', null, [
												'class' => 'form-control input-sm'
											]) !!}
										</div>
									</div>
									<div class="col-2">
										{!! Form::button('<i class="icofont icofont-check-alt"></i> Validar', [
											'class' => 'btn btn-sm btn-info btn-custom', 'style' => 'margin-top:25px',
											'onclick' => ''
										]) !!}
									</div>
									<div class="col-3 offset-1">
										<div class="form-group is-required{{ $errors->has('name') ? ' has-error' : '' }}">
											{!! Form::label('name', 'Nombre o Razón Social') !!}
											{!! Form::text('name', null, [
												'class' => 'form-control input-sm'
											]) !!}
										</div>
									</div>
									<div class="col-3">
										<div class="form-group {{ $errors->has('social_purpose') ? ' has-error' : '' }}">
											{!! Form::label('social_purpose', 'Objeto Social de la organización') !!}
											{!! Form::text('social_purpose', null, [
												'class' => 'form-control input-sm'
											]) !!}
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-3">
										<div class="form-group is-required{{ $errors->has('purchase_supplier_type_id') ? ' has-error' : '' }}">
											{!! Form::label('purchase_supplier_type_id', 'Denominación Comercial') !!}
											{!! Form::select('purchase_supplier_type_id', $supplier_types, null, [
												'class' => 'form-control select2'
											]) !!}
										</div>
									</div>
									<div class="col-3">
										<div class="form-group is-required{{ $errors->has('purchase_supplier_object_id') ? ' has-error' : '' }}">
											{!! Form::label('purchase_supplier_object_id', 'Objeto Principal') !!}
											{!! Form::select('purchase_supplier_object_id', $supplier_objects, 
													(isset($model_supplier_objects)) ? $model_supplier_objects : null, [
												'class' => 'form-control',
												'multiple' => 'multiple',
												'name' => 'purchase_supplier_object_id[]'
											]) !!}
										</div>
									</div>
									<div class="col-3">
										<div class="form-group is-required{{ $errors->has('purchase_supplier_branch_id') ? ' has-error' : '' }}">
											{!! Form::label('purchase_supplier_branch_id', 'Rama') !!}
											{!! Form::select('purchase_supplier_branch_id', $supplier_branches, null, [
												'class' => 'form-control select2'
											]) !!}
										</div>
									</div>
									<div class="col-3">
										<div class="form-group is-required{{ $errors->has('purchase_supplier_specialty_id') ? ' has-error' : '' }}">
											{!! Form::label('purchase_supplier_specialty_id', 'Especialidad') !!}
											{!! Form::select('purchase_supplier_specialty_id', $supplier_specialties, null, [
												'class' => 'form-control select2'
											]) !!}
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-6">
										<div class="form-group{{ $errors->has('website') ? ' has-error' : '' }}">
											{!! Form::label('website', 'Sitio Web') !!}
											{!! Form::url('website', null, [
												'class' => 'form-control input-sm'
											]) !!}
										</div>
									</div>
									<div class="col-6">
										<div class="form-group is-required{{ $errors->has('country_id') ? ' has-error' : '' }}">
											{!! Form::label('country_id', 'Pais') !!}
											{!! Form::select('country_id', $countries, null, [
												'class' => 'form-control select2', 'id' => 'country_id',
												'onchange' => 'updateSelect($(this), $("#estate_id"), "Estate")'
											]) !!}
										</div>
									</div>
									<div class="col-6">
										<div class="form-group is-required{{ $errors->has('estate_id') ? ' has-error' : '' }}">
											{!! Form::label('estate_id', 'Estado') !!}
											{!! Form::select('estate_id', $estates, null, [
												'class' => 'form-control select2', 'id' => 'estate_id',
												'onchange' => 'updateSelect($(this), $("#city_id"), "City")',
												'disabled' => (!isset($model))
											]) !!}
										</div>
									</div>
									<div class="col-6">
										<div class="form-group is-required{{ $errors->has('city_id') ? ' has-error' : '' }}">
											{!! Form::label('city_id', 'Ciudad') !!}
											{!! Form::select('city_id', $cities, null, [
												'class' => 'form-control select2', 'id' => 'city_id',
												'disabled' => (!isset($model))
											]) !!}
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-12">
										<div class="form-group is-required{{ $errors->has('direction') ? ' has-error' : '' }}">
											{!! Form::label('direction', 'Dirección Fiscal') !!}
                                            <ckeditor :editor="ckeditor.editor" data-toggle="tooltip"
                                                      title="Indique la dirección del proveedor"
                                                      :config="ckeditor.editorConfig" name="direction"
                                                      class="form-control" tag-name="textarea" rows="3"
                                                      placeholder="dirección del proveedor" value="{{isset($model) ? $model->direction : ''}}">
                                                      </ckeditor>
										</div>
									</div>
								</div>
								<hr>
								@php
									$contacts = [];
									if (isset($model) && $model->contacts) {
										foreach ($model->contacts as $contact) {
											array_push($contacts, [
												'name' => $contact->name,
												'email' => $contact->email
											]);
										}
									}
								@endphp
								<contacts initial_data="{{ ($contacts) ? json_encode($contacts) : '' }}"></contacts>
								<hr>
								@php
									$phones = [];
									if (isset($model) && $model->phones) {
										foreach ($model->phones as $phone) {
											array_push($phones, [
												'type' => $phone->type,
												'area_code' => $phone->area_code,
												'number' => $phone->number,
												'extension' => $phone->extension ?? ''
											]);
										}
									}
								@endphp
								<phones initial_data="{{ ($phones) ? json_encode($phones) : '' }}"></phones>
							</div>
							<div class="tab-pane" id="rnc" role="tabpanel">
								<h6 class="card-title">Datos del Registro Nacional de Contratistas</h6>
								<div class="row">
									<div class="col-8">
										<div class="form-group is-required{{ $errors->has('rnc_status') ? ' has-error' : '' }}">
											{!! Form::label('rnc_status', 'Situación Actual') !!}
											<div class="col-12">
												<label class="radio-inline mt-4 mr-4">
													<span class="left">Inscrito y no habilitado</span>
                                                    <div class="col-12 bootstrap-switch-mini mt-3 text-center">
    													{!! Form::radio('rnc_status', 'INH', null, [
    														'class' => 'form-control bootstrap-switch',
                                                            'data-on-label' => 'SI', 'data-off-label' => 'NO'
    													]) !!}
                                                    </div>
												</label>
												<label class="radio-inline mt-4 mr-4">
													<span class="left">Inscrito y habilitado para contratar</span>
                                                    <div class="col-12 bootstrap-switch-mini mt-3 text-center">
    													{!! Form::radio('rnc_status', 'ISH', null, [
    														'class' => 'form-control bootstrap-switch',
                                                            'data-on-label' => 'SI', 'data-off-label' => 'NO'
    													]) !!}
                                                    </div>
												</label>
												{{-- <label class="radio-inline mt-4 mr-4">
													<span class="left">Inscrito, habilitado y calificado</span>
                                                    <div class="col-12 bootstrap-switch-mini mt-3 text-center">
    													{!! Form::radio('rnc_status', 'IHC', null, [
    														'class' => 'form-control bootstrap-switch',
                                                            'data-on-label' => 'SI', 'data-off-label' => 'NO'
    													]) !!}
                                                    </div>
												</label> --}}
											</div>
										</div>
									</div>
									<div class="col-4">
										<div class="form-group is-required{{ $errors->has('rnc_certificate_number') ? ' has-error' : '' }}">
											{!! Form::label('rnc_certificate_number', 'Número de Certificado') !!}
											{!! Form::text('rnc_certificate_number', null, [
												'class' => 'form-control input-sm'
											]) !!}
										</div>
									</div>
								</div>
							</div>
							<div class="tab-pane" id="requirement_docs" role="tabpanel">
								<h6 class="card-title">Documentos a consignar</h6>
								<div class="row">
									<div class="col-6">
										{{-- Campo para registrar un arreglo de documentos --}}
										<input type="hidden" id="documents" name="documents" readonly>
										<ul class="feature-list list-group list-group-flush">
											@foreach ($requiredDocuments as $reqDoc)
									            <li class="list-group-item">
									                <div class="feature-list-indicator bg-info"></div>
									                <div class="feature-list-content p-0">
									                    <div class="feature-list-content-wrapper">
															<div class="feature-list-content-right feature-list-content-actions">
									                        	<button class="btn btn-simple btn-success btn-events"
									                        			title="Presione para cargar el documento"
									                        			data-toggle="tooltip" type="button"
									                        			onclick="clickUploadDoc({{$reqDoc->id}})">
									                        		<i class="fa fa-cloud-upload fa-2x"></i>
									                        	</button>
																{{-- {{ dd($model->documents->purchaseDocumentRequiredDocument) }} --}}

																@if(isset($model)
																	&& isset($model->documents)
																	&& isset($docs_to_download['req_doc_'.$reqDoc->id]))
																	<a class="btn btn-simple btn-primary btn-events"
																		title="Presione para descargar el documento"
																		data-toggle="tooltip"
																		target="_blank"
																		href="{{'/purchase/document/download/'.$docs_to_download['req_doc_'.$reqDoc->id]->file}}"
																		download="{{$model->rif . ' - ' . $reqDoc->name.'.pdf'}}"
																		>
																		<i class="fa fa-cloud-download fa-2x"></i>
																	</a>
																@endif
									                        	<input type="file" id="{{'doc'.$reqDoc->id}}" name="docs[]" style="display:none"
									                        		   onchange="uploadFile(event)" accept=".doc, .pdf, .odt, .docx">
																<input type="number" id="{{'reqDoc'.$reqDoc->id}}" name="reqDocs[]" style="display:none">
									                        </div>
									                        <div class="feature-list-content-left">
																@if(isset($docs_to_download['req_doc_'.$reqDoc->id]))
																	<div class="feature-list-subheading">
																		<span class="badge badge-success"
																			title="El documento se ha cargado"
																			data-toggle="tooltip">
																			<strong>Documento cargado</strong>
																		</span>
																	</div>
																@else
																	<div class="feature-list-heading" id="{{'toload_doc'.$reqDoc->id}}">
																		<div class="badge badge-danger ml-2"
																			title="El documento aún no ha sido cargado"
																			data-toggle="tooltip">
																			por cargar
																		</div>
																	</div>
																	<div class="feature-list-subheading" id="{{'loaded_doc'.$reqDoc->id}}" style="display:none;">
																		<span class="badge badge-success"
																			title="El documento se ha cargado"
																			data-toggle="tooltip">
																			<strong>Documento cargado</strong>
																		</span>
																	</div>
																@endif
									                            <div class="feature-list-subheading">
									                            	<i class="font-weight-bold">{!! $reqDoc->name ?? '' !!}</i>
																	{!! $reqDoc->description ?? '' !!}
									                            </div>
									                        </div>
									                    </div>
									                </div>
									            </li>
											@endforeach
								        </ul>
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

@section('extra-js')
	@parent
	{!! Html::script('js/ckeditor.js', [], Request::secure()) !!}
	<script>
		let idclicker = 0;
		$(document).ready(function() {
			$(".nav-link").tooltip();
		});
		function clickUploadDoc(id, ){
			idclicker = id;
			$('#doc'+id).click();
		};

		function uploadFile(e) {
            const files = e.target.files;

            Array.from(files).forEach(file => addFile(file, idclicker));
        };
        function addFile(file, inputID) {
			
			$('#reqDoc'+inputID).val(inputID);
			$('#loaded_doc' + inputID).show("slow");
			$('#toload_doc' + inputID).hide("slow");
        };

		function conditi(){
			if (!file.type.match('application/pdf') || 
				!file.type.match('application/msword') || 
				!file.type.match('application/vnd.oasis.opendocument.text') || 
				!file.type.match('application/vnd.openxmlformats-officedocument.wordprocessingml.document')) {
                this.showMessage(
                    'custom', 'Error', 'danger', 'screen-error', 'Solo se permiten archivos pdf.'
                );
                return;
            } else {
                //this.files[inputID] = file;
                $('#status_' + inputID).show("slow");
            }
		}
	</script>
@stop
