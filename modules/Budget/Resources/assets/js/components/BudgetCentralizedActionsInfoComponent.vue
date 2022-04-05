<template>
	<section>
		<a class="btn btn-info btn-xs btn-icon btn-action"
		   href="#" title="Ver información" data-toggle="tooltip"
		   v-has-tooltip
		   @click="addRecord('view_request', route_list, $event)">
			<i class="fa fa-eye"></i>
		</a>
		<div class="modal fade text-left" tabindex="-1" role="dialog" id="view_request">
			<div class="modal-dialog modal-lg" style="max-width: 60rem">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
						<h6>
							<i class="icofont icofont-read-book ico-2x"></i>
							Información de la Solicitud Registrada
						</h6>
					</div>

					<div class="modal-body">
				
						<div class="row">
							<ul class="nav nav-tabs custom-tabs justify-content-center" role="tablist">
		                        <li class="nav-item">
		                            <a class="nav-link active" data-toggle="tab" href="#general" id="info_general" role="tab">
		                                <i class="ion-android-person"></i> Información general
									</a>
		                        </li>
		                        <li class="nav-item">
		                            <a class="nav-link" data-toggle="tab" href="#contact" role="tab">
		                                <i class="ion-android-person"></i> Información de contacto
		                            </a>
		                        </li>
		                        <li class="nav-item">
		                            <a class="nav-link" data-toggle="tab" href="#institution" role="tab">
		                                <i class="ion-arrow-person"></i> Información de la institución
		                            </a>
		                        </li>
								<li class="nav-item">
								  <a class="nav-link" data-toggle="tab" href="#info" role="tab">
									  <i class="ion-arrow-person"></i> Información de archivos
								  </a>
							  </li>
		                    </ul>
						</div>

	                    <div class="tab-content">
	                    	<div class="tab-pane active" id="general" role="tabpanel">
		                    	<div class="row">
								        <div class="col-md-4">
								        	<div class="form-group">
												<strong>Fecha de la solicitud</strong>
												<div class="row" style="margin: 1px 0">
													<span class="col-md-12" id="date">
													</span>
												</div>
												<input type="hidden" id="id">
											</div>
										</div>

										<div class="col-md-4">
											<div class="form-group">
												<strong>Motivo de la solicitud</strong>
												<div class="row" style="margin: 1px 0">
													<span class="col-md-12" id="motive_request">
													</span>
												</div>
								            </div>
										</div>

										<div class="col-md-4">
											<div class="form-group">
												<strong>Atributos</strong>
												<div class="row" style="margin: 1px 0">
													<span class="col-md-12" id="attribute">
													</span>
												</div>
								            </div>
										</div>

										<div class="col-md-4">
											<div class="form-group">
												<strong>Tipo de solicitud</strong>
												<div class="row" style="margin: 1px 0">
													<span class="col-md-12" id="citizen_service_request_type_id">
												   </span>
												</div>
								            </div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<strong>Dirección de departamento</strong>
												<div class="row" style="margin: 1px 0">
													<span class="col-md-12" id="citizen_service_department_id">
												   </span>
												</div>
								            </div>
										</div>
								</div>
							</div>
								<div class="tab-pane" id="contact" role="tabpanel">
		                    		<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<strong>Nombre</strong>
												<div class="row" style="margin: 1px 0">
													<span class="col-md-12" id="applicant_name">
													</span>
												</div>
								            </div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<strong>Cédula de identidad</strong>
												<div class="row" style="margin: 1px 0">
													<span class="col-md-12" id="id_number">
													</span>
												</div>
								            </div>
										</div>

										<div class="col-md-4">
											<div class="form-group">
												<strong>Números teléfonicos</strong>
												<div class="row" style="margin: 1px 0">
													<span class="col-md-12" id="applicant_phone">
													</span>
												</div>
								            </div>
										</div>

										<div class="col-md-4">
											<div class="form-group">
												<strong>Correo</strong>
												<div class="row" style="margin: 1px 0">
													<span class="col-md-12" id="email">
													</span>
												</div>
								            </div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<strong>País</strong>
												<div class="row" style="margin: 1px 0">
													<span class="col-md-12" id="country_id">
												   </span>
												</div>
								            </div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<strong>Estado</strong>
												<div class="row" style="margin: 1px 0">
													<span class="col-md-12" id="estate_id">
												   </span>
												</div>
								            </div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<strong>Ciudad</strong>
												<div class="row" style="margin: 1px 0">
													<span class="col-md-12" id="city_id">
												   </span>
												</div>
								            </div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<strong>Municipio</strong>
												<div class="row" style="margin: 1px 0">
													<span class="col-md-12" id="municipality_id">
												   </span>
												</div>
								            </div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<strong>Parroquia</strong>
												<div class="row" style="margin: 1px 0">
													<span class="col-md-12" id="parish_id">
												   </span>
												</div>
								            </div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<strong>Dirección</strong>
												<div class="row" style="margin: 1px 0">
													<span class="col-md-12" id="address">
												   </span>
												</div>
								            </div>
										</div>

		                    		</div>
		                    	</div>
		                    	<div class="tab-pane" id="institution" role="tabpanel">
		                    		<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<strong>Nombre de la institución</strong>
												<div class="row" style="margin: 1px 0">
													<span class="col-md-12" id="institution_name">
													</span>
												</div>
								            </div>
										</div>

										<div class="col-md-6">
											<div class="form-group">
												<strong>Rif</strong>
												<div class="row" style="margin: 1px 0">
													<span class="col-md-12" id="rif">
													</span>
												</div>
								            </div>
										</div>

										<div class="col-md-6">
											<div class="form-group">
												<strong>Dirección de la institución</strong>
												<div class="row" style="margin: 1px 0">
													<span class="col-md-12" id="institution_address">
													</span>
												</div>
								            </div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<strong>Dirección web</strong>
												<div class="row" style="margin: 1px 0">
													<span class="col-md-12" id="web">
													</span>
												</div>
								            </div>
										</div>
		                    		</div>
		                    	</div>
								<div class="tab-pane" id="info"  role="tabpanel">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">	
												<strong>Nombre del archivo</strong>
												<div class="row" style="margin: 1px 0">
														<span class="col-md-12" id="archive">
														</span>
												</div>
											</div>	
										</div>

										<div class="col-md-6">
											<div class="form-group">
												<strong>Fecha de verificación</strong>
												<div class="row" style="margin: 1px 0">
													<span class="col-md-12" id="date_verification">
													</span>
												</div>
											</div>
										</div>
									</div>
								</div>
	                        </div>
	                    </div>
					 <div class="modal-footer">

	                	<button type="button" class="btn btn-default btn-sm btn-round btn-modal-close"
	                			data-dismiss="modal">
	                		Cerrar
	                	</button>
		            </div>
		        </div>
		    </div>
		</div>

	</section>
</template>

<script>
	export default {
		data() {
			return {
				records: [],
				errors: [],

			}
		},
		created() {


		},
		methods: {
			/**
             * Método que borra todos los datos del formulario
             *
             * @author  Yennifer Ramirez <yramirez@cenditel.gob.ve>
             */
            reset() {
            },

             initRecords(){
            	this.errors = [];
				this.reset();


            
            },
		},
	};
</script>
