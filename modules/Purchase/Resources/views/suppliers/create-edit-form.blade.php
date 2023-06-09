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
                                                    <div class="col-12">
														<div class="custom-control custom-switch">
															{!! Form::radio('person_type', 'N', null, [
																'class' => 'custom-control-input reseteable',
																'id' => 'personTypeN'
															]) !!}
															<label class="custom-control-label" for="personTypeN"></label>
														</div>
                                                    </div>
												</label>
												<label class="radio-inline">
													<span class="left">Jurídica</span>
                                                    <div class="col-12">
														<div class="custom-control custom-switch">
															{!! Form::radio('person_type', 'J', null, [
																'class' => 'custom-control-input reseteable',
																'id' => 'personTypeJ'
															]) !!}
															<label class="custom-control-label" for="personTypeJ"></label>
														</div>
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
                                                    <div class="col-12">
														<div class="custom-control custom-switch">
															{!! Form::radio('company_type', 'PU', null, [
																'class' => 'custom-control-input',
																'id' => 'companyTypePU'
															]) !!}
															<label class="custom-control-label" for="companyTypePU"></label>
														</div>
                                                    </div>
												</label>
												<label class="radio-inline">
													<span class="left">Privada</span>
                                                    <div class="col-12">
														<div class="custom-control custom-switch">
															{!! Form::radio('company_type', 'PR', null, [
																'class' => 'custom-control-input',
																'id' => 'companyTypePR'
															]) !!}
															<label class="custom-control-label" for="companyTypePR"></label>
														</div>
                                                    </div>
												</label>
											</div>
										</div>
									</div>
									<div class="col-4">
										<div class="form-group{{ $errors->has('active') ? ' has-error' : '' }}">
											{!! Form::label('active', 'Activo') !!}
											<div class="col-12">
                                                <div class="custom-control custom-switch">
    												{!! Form::checkbox('active', true, null, [
    													'class' => 'custom-control-input', 'id' => 'activo'
    												]) !!}
													<label class="custom-control-label" for="activo"></label>
                                                </div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-4">
										<div class="form-group is-required{{ $errors->has('rif') ? ' has-error' : '' }}">
											{!! Form::label('rif', 'R.I.F.') !!}
											{!! Form::text('rif', null, [
												'class' => 'form-control input-sm'
											]) !!}
										</div>
									</div>
									<div class="col-3">
										{!! Form::button('<i class="icofont icofont-check-alt"></i> Validar', [
											'class' => 'btn btn-sm btn-info btn-custom', 'style' => 'margin-top:25px',
											'onclick' => ''
										]) !!}
									</div>
									<div class="col-4 offset-1">
										<div class="form-group is-required{{ $errors->has('name') ? ' has-error' : '' }}">
											{!! Form::label('name', 'Nombre o Razón Social') !!}
											{!! Form::text('name', null, [
												'class' => 'form-control input-sm'
											]) !!}
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-4">
										<div class="form-group {{ $errors->has('social_purpose') ? ' has-error' : '' }}">
											{!! Form::label('social_purpose', 'Objeto Social de la organización') !!}
											{!! Form::text('social_purpose', null, [
												'class' => 'form-control input-sm'
											]) !!}
										</div>
									</div>
									<div class="col-4">
										<div class="form-group is-required{{ $errors->has('purchase_supplier_type_id') ? ' has-error' : '' }}">
											{!! Form::label('purchase_supplier_type_id', 'Denominación Comercial') !!}
											{!! Form::select('purchase_supplier_type_id', $supplier_types, null, [
												'class' => 'form-control select2'
											]) !!}
										</div>
									</div>
									<div class="col-4">
										<div class="form-group is-required{{ $errors->has('purchase_supplier_object_id') ? ' has-error' : '' }}">
											{!! Form::label('purchase_supplier_object_id', 'Objeto Principal') !!}
											{!! Form::select('purchase_supplier_object_id', $supplier_objects, 
													(isset($model_supplier_objects)) ? $model_supplier_objects : null, [
												'class' => 'form-control multiple',
												'multiple' => 'multiple',
												'name' => 'purchase_supplier_object_id[]'
											]) !!}
										</div>
									</div>
									<div class="col-4">
										<div class="form-group is-required{{ $errors->has('purchase_supplier_branch_id') ? ' has-error' : '' }}">
											{!! Form::label('purchase_supplier_branch_id', 'Rama') !!}
											{!! Form::select('purchase_supplier_branch_id', $supplier_branches, null, [
												'class' => 'form-control select2'
											]) !!}
										</div>
									</div>
									<div class="col-4">
										<div class="form-group is-required{{ $errors->has('purchase_supplier_specialty_id') ? ' has-error' : '' }}">
											{!! Form::label('purchase_supplier_specialty_id', 'Especialidad') !!}
											{!! Form::select('purchase_supplier_specialty_id', $supplier_specialties, null, [
												'class' => 'form-control select2'
											]) !!}
										</div>
									</div>
									<div class="col-4">
										<div class="form-group is-required{{ $errors->has('accounting_account_id') ? ' has-error' : '' }}">
											{!! Form::label('accounting_account_id', 'Cuentas contables') !!}
											{!! Form::select('accounting_account_id', $accounting_accounts, null, [
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
											<!--{!! Form::label('country_id', 'Pais') !!}
											{!! Form::select('country_id', $countries, null, [
												'class' => 'form-control select2', 'id' => 'country_id',
												'onchange' => 'updateSelect($(this), $("#estate_id"), "Estate")'
											]) !!}-->
											{!! Form::label('country_id', __('País'), []) !!}
											{!! Form::select('country_id', (isset($countries))?$countries:[], (isset($model_institution)) ? $model_institution->city->estate->country->id : null, [
												'class' => 'form-control select2 input-sm', 'id' => 'country_id',
												'onchange' => 'updateSelect($(this), $("#estate_id"), "Estate")'
											]) !!}
										</div>
									</div>
									<div class="col-6">
										<div class="form-group is-required{{ $errors->has('estate_id') ? ' has-error' : '' }}">
											<!--{!! Form::label('estate_id', 'Estado') !!}
											{!! Form::select('estate_id', $estates, null, [
												'class' => 'form-control select2', 'id' => 'estate_id',
												'onchange' => 'updateSelect($(this), $("#city_id"), "City")',
												'disabled' => (!isset($model))
											]) !!} -->
											{!! Form::label('estate_id', __('Estado'), []) !!}
											{!! Form::select('estate_id', (isset($estates))?$estates:[], (isset($model_institution)) ? $model_institution->city->estate->id : old('estate_id'), [
												'class' => 'form-control select2', 'id' => 'estate_id',
												'onchange' => 'updateSelect($(this), $("#municipality_id"), "Municipality"),updateSelect($(this), $("#city_id"), "City")',
												//'disabled' => (!isset($model_institution))
											]) !!}
										</div>
									</div>
									<div class="col-6">
										<div class="form-group is-required{{ $errors->has('city_id') ? ' has-error' : '' }}">
											<!--{!! Form::label('city_id', 'Ciudad') !!}
											{!! Form::select('city_id', $cities, null, [
												'class' => 'form-control select2', 'id' => 'city_id',
												'disabled' => (!isset($model))
											]) !!} -->
											{!! Form::label('city_id', __('Ciudad'), []) !!}
											{!! Form::select('city_id', (isset($cities))?$cities:[], (isset($model_institution))? $model_institution->city_id : old('city_id'), [
												'class' => 'form-control select2', 'id' => 'city_id',
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
                                                      placeholder="dirección del proveedor" value="{{ isset($model) ? $model->direction : old('direction') }}">
                                                      </ckeditor>
										</div>
									</div>
								</div>

								<hr>
								@php
									$contacts = [];
									$contact_names = session()->getOldInput('contact_names'); 
									$contact_emails = session()->getOldInput('contact_emails');
									if($contact_names) {
										for($i = 0; $i < count($contact_names); $i++ ) {
											array_push($contacts, [
												'name' => $contact_names[$i],
												'email' => $contact_emails[$i]
											]);
										}
									}
									elseif(isset($model) && $model->contacts) {
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
									$phone_type = session()->getOldInput('phone_type'); 
									$phone_area_code = session()->getOldInput('phone_area_code');
									$phone_number = session()->getOldInput('phone_number'); 
									$phone_extension = session()->getOldInput('phone_extension');

									if($phone_type) {
										for ($i = 0; $i < count($phone_type); $i++ ) {
											array_push($phones, [
												'type' => $phone_type[$i],
												'area_code' => $phone_area_code[$i],
												'number' => $phone_number[$i],
												'extension' => $phone_extension[$i] ?? ''
											]);
										}
									}
									elseif (isset($model) && $model->phones) {
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
										<div class="form-group {{ $errors->has('rnc_status') ? ' has-error' : '' }}">
											{!! Form::label('rnc_status', 'Situación Actual') !!}
											<div class="col-12">
												<label class="radio-inline mt-4 mr-4">
													<span class="left">Inscrito y no habilitado</span>
                                                    <div class="col-12 mt-3 text-center">
														<div class="custom-control custom-switch">
															{!! Form::radio('rnc_status', 'INH', null, [
																'class' => 'custom-control-input',
																'id' => 'rnc_status_inh'
															]) !!}
															<label class="custom-control-label" for="tnc_status_inh"></label>
														</div>
                                                    </div>
												</label>
												<label class="radio-inline mt-4 mr-4">
													<span class="left">Inscrito y habilitado para contratar</span>
                                                    <div class="col-12 mt-3 text-center">
														<div class="custom-control custom-switch">
															{!! Form::radio('rnc_status', 'ISH', null, [
																'class' => 'custom-control-input', 'id' => 'rnc_status_ish'
															]) !!}
															<label class="custom-control-label" for="tnc_status_ish"></label>
														</div>
                                                    </div>
												</label>
												{{-- <label class="radio-inline mt-4 mr-4">
													<span class="left">Inscrito, habilitado y calificado</span>
                                                    <div class="col-12 mt-3 text-center">
														<div class="custom-control custom-switch">
															{!! Form::radio('rnc_status', 'IHC', null, [
																'class' => 'custom-control-input', 'id' => 'rnc_status_ihc'
															]) !!}
															<label class="custom-control-label" for="tnc_status_ihc"></label>
														</div>
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
												  @if($reqDoc->type == 'proveedor')  
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
									       	@endif
											@endforeach
								        </ul>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="card-footer text-right">
						<!-- @include('layouts.form-buttons') -->
						@if (!isset($hide_clear) || !$hide_clear)
							{!! Form::button('<i class="fa fa-eraser"></i>', [
								'class' => 'btn btn-default btn-icon btn-round', 'data-toggle' => 'tooltip', 'type' => 'reset',
								'id' => 'reset-select',
								'title' => __('Borrar datos del formulario'),
							]) !!}
						@endif
						@if (!isset($hide_previous) || !$hide_previous)
						{!! Form::button('<i class="fa fa-ban"></i>', [
							'class' => 'btn btn-warning btn-icon btn-round', 'data-toggle' => 'tooltip', 'type' => 'button',
							'title' => __('Cancelar y regresar'), 'onclick' => 'window.location.href="' . url()->previous() . '"',
						]) !!}
						@endif
						@if (!isset($hide_save) || !$hide_save)
							{!! Form::button('<i class="fa fa-save"></i>', [
								'class' => 'btn btn-success btn-icon btn-round', 'data-toggle' => 'tooltip',
								'title' => __('Guardar registro'), 'type' => 'submit'
							]) !!}
						@endif
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
			$("#reset-select").on('click', function() {
	    		$('#purchase_supplier_type_id').val('').change();
	    		$('#purchase_supplier_object_id').val('').change();
	    		$('#purchase_supplier_branch_id').val('').change();
	    		$('#purchase_supplier_specialty_id').val('').change();
	    		$('#country_id').val('').change();
	    		$('#state_id').val('').change();
	    		$('#city_id').val('').change();
	    		$('#activo').prop('checked', false);
	    		$(":radio").prop('checked', false).change();
	    		$(":checkbox").prop('checked', false).change();
			});
			$(".multiple").select2({
				placeholder: "Seleccione..."
			});
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
		};

		//@if (old('name')!=='')
		//	console.log("Entro");
		//@else console.log("No entro");
		//@endif
		//console.log(old('estate_id'));

		//$('#country_id').attr("onChange","return('cambio');");
		//$('#country_id').attr('onChange','updateSelect($(this),$("#estate_id"),"Estate")');
		//	$('#estate_id').attr('onChange','updateSelect($(this), $("#city_id"), "City")');
		/*@if (old('country_id')!=='')
			$("#country_id").click();
		@endif
		@if (old('estate_id')!=='')
			$("#estate_id").click();
		@endif */
	</script>
@stop
