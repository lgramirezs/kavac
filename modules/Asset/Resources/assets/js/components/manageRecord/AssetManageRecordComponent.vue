<template>
	<div>
		<a 	class="btn btn-info btn-xs btn-icon btn-action"
		   	href="#"
			title="Gestionar acta" data-toggle="tooltip"
			type="button" v-has-tooltip
		   	@click="addRecord('manage_record', route_list ,$event)">
			<i class="fa fa-file-pdf-o"></i>
		</a>
		<div class="modal fade text-left" tabindex="-1" role="dialog" id="manage_record">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="reset()">
							<span aria-hidden="true">×</span>
						</button>
						<h6>
							<i class="icofont icofont-file-text ico-2x"></i>
							Gestiión de Acta de {{action}} de bienes
						</h6>
					</div>

					<div class="modal-body">
						
						<div class="alert alert-danger" v-if="errors.length > 0">
							<ul>
								<li v-for="error in errors" :key="error">{{ error }}</li>
							</ul>
						</div>
					
	                    <div class="tab-content" id="info_general">
	                    	<div class="tab-pane active" id="general" role="tabpanel">
	                    		<div class="row">
							        <div class="col-md-12">
										<div class="form-group">
											<strong>Organización:</strong>
											<span class="col-md-12" id="institution"></span>
											<input type="hidden" id="id">
										</div>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<strong>Ubicación Geográfica/Física: </strong>
							            </div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<strong>Estado:</strong>
											<span id="estate"></span>
							            </div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<strong>Minicipio:</strong>
											<span id="municipality"></span>
							            </div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<strong>Dirección:</strong>
											<span id="address"></span>
							            </div>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<strong>Ejercicio Fiscal:</strong>
											<span class="col-md-12" id="fiscal_year"></span>
							            </div>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<strong>Fecha de Asignación:</strong>
											<span class="col-md-12" id="created_at"></span>
							            </div>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-md-12">
										<div class="form-group" id="staff">
											<strong>Responsable por uso:</strong>
							            </div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<strong>Apellidos:</strong>
											<span id="last_name"></span>
							            </div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<strong>Nombres:</strong>
											<span id="first_name"></span>
							            </div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<strong>Cédula de identidad:</strong>
											<span id="id_number"></span>
							            </div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<strong>Departamento:</strong>
											<span id="department"></span>
							            </div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<strong>Cargo:</strong>
											<span id="payroll_position"></span>
							            </div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<strong>Lugar de Ubicación:</strong>
											<span id="location_place"></span>
							            </div>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<strong v-if="action=='asignación'">Bienes Asignados:</strong>
							            </div>
									</div>
								</div>
								
								<div class="row">
									<div class="col-md-12">
										<v-client-table :columns="columns" :data="equipments" :options="table_options"></v-client-table>
									</div>
								</div>
								
								<hr>
								<div class="row">
									<div class="col-md-4" id="authorized_by">
										<div class="form-group">
											<strong>Autorizado por:</strong>
											<select2 :options="payroll_staffs" 
											v-model="record.authorized_by"></select2>
										</div>
									</div>
									<div class="col-md-4" id="formed_by">
										<div class="form-group">
											<strong>Conformado por:</strong>
											<select2 :options="payroll_staffs" 
											v-model="record.formed_by"></select2>
										</div>
									</div>
									<div class="col-md-4" id="delivered_by">
										<div class="form-group">
											<strong>Entregado por:</strong>
											<select2 :options="payroll_staffs" 
											v-model="record.delivered_by"></select2>
										</div>
									</div>
								</div>

	                    	</div>
	                    </div>
					</div>

	                <div class="modal-footer">
                        <button class="btn btn-default btn-sm btn-round btn-modal-close" type="button"
                                data-dismiss="modal" @click="reset()">
                            Cerrar
                        </button>
                        <button class="btn btn-primary btn-sm btn-round btn-modal-save" type="button"
								title="Generar acta">
                            Guardar
                        </button>
                    </div>
		        </div>
		    </div>
		</div>
	</div>
</template>

<script>
	export default{
		data() {
			return {
				records: [],
				dataRecaord: {},
				record:{},
				errors: [],
				payroll_staffs: [],
				equipments: [],
				// fiscal_years: [],
				// fiscal_year: '',

				columns: ['asset.inventory_serial', 'asset.institution', 'asset.specifications', 
						'asset.asset_condition', 'asset.marca', 'asset.model', 'asset.serial',
						'asset.color', 'asset.asset_institutional_code'],
				table_options: {
					headings: {
						'asset.inventory_serial': 			'Código',
						'asset.institution': 				'Organización',
						'asset.specifications': 			'Especificaciones',
						'asset.asset_condition': 			'Condición Física',
						'asset.marca': 						'Marca',
						'asset.model': 						'Modelo',
						'asset.serial': 					'Serial',
						'asset.color': 						'Color',
						'asset.asset_institutional_code': 	'Código de bien organizacional',
					},
					orderBy: { 'column': 'asset.id'},
				}
			}
		},
		props: {
			index: Number,
            action: String,
            route_list: String,
        },
		created() {
			const vm = this;
			vm.getPayrollStaffs();
			//vm.getOpenedFiscalYears();
			vm.loadEquipment(vm.route_list);
		},

		methods: {
			/**
             * Método que borra todos los datos del formulario
             *
             * @author  Ing. Roldan Vargas <rvargas@cenditel.gob.ve | roldandvg@gmail.com>
             */
            reset() {
				const vm = this;
				vm.records = [];
				vm.dataRecaord = {};
				vm.payroll_staffs = [];
				vm.equipments = [];
            },
			/**
			 * Inicializa los registros base del formulario
			 *
			 * @author 
			 */
            initRecords(url,modal_id){
            	this.errors = [];

				const vm = this;
            	let fields = {};
            	document.getElementById("info_general").click();
				url = vm.setUrl(url);


            	axios.get(url).then(response => {
					if (typeof(response.data.records) !== "undefined") {
						fields = response.data.records;

						$(".modal-body #id").val( fields.id );
		            	document.getElementById('institution').innerText = (fields.institution_id)?fields.institution.name:'';
		            	document.getElementById('estate').innerText = (fields.institution_id)?fields.institution.municipality.estate.name:'';
						document.getElementById('municipality').innerText = (fields.institution_id)?fields.institution.municipality.name:'';
						document.getElementById('address').innerText = (fields.institution_id)?fields.institution.legal_address.replace(/(<([^>]+)>)/ig, ''):'';
		            	document.getElementById('fiscal_year').innerText = (fields.institution_id)?fields.institution.fiscal_years[0].year:'N/A';
						document.getElementById('created_at').innerText = (fields.created_at)?vm.format_date(fields.created_at):'';
						document.getElementById('last_name').innerText = (fields.payroll_staff)?fields.payroll_staff.last_name:'N/A';
		            	document.getElementById('first_name').innerText = (fields.payroll_staff)?fields.payroll_staff.first_name:'N/A';
						document.getElementById('id_number').innerText = (fields.payroll_staff)?fields.payroll_staff.id_number:'N/A';
						document.getElementById('department').innerText = (fields.payroll_staff_id)?fields.payroll_staff.payroll_employment.department.acronym:'N/A';
		            	document.getElementById('payroll_position').innerText = (fields.payroll_staff_id)?fields.payroll_staff.payroll_employment.payroll_position.name:'N/A';
						document.getElementById('location_place').innerText = (fields.location_place)?fields.location_place:'N/A';
					}
					if ($("#" + modal_id).length) {
						$("#" + modal_id).modal('show');
					}
				}).catch(error => {
					if (typeof(error.response) !== "undefined") {
						if (error.response.status == 403) {
							vm.showMessage(
								'custom', 'Acceso Denegado', 'danger', 'screen-error', error.response.data.message
							);
						}
						else {
							vm.logs('resources/js/all.js', 343, error, 'initRecords');
						}
					}
				});
            },
			
			loadEquipment(url){
				const vm = this;
                vm.equipments = [];
                url = vm.setUrl(url);
                let equipments = [];

				axios.get(url).then(response => {
                    equipments = JSON.parse(response.data.records.ids_assets);
					if(equipments == null){
                        vm.equipments = response.data.records.asset_asignation_assets;
					}else{
						vm.equipments = response.data.records.asset_asignation_assets.filter(asset => equipments.assigned.includes(asset.asset.id));
					}
				}).catch(error => {
                    if (typeof(error.response) !== "undefined") {
                        if (error.response.status == 403) {
                            vm.showMessage(
                                'custom', 'Acceso Denegado', 'danger', 'screen-error', error.response.data.message
                            );
                        }
                        else {
                            vm.logs('resources/js/all.js', 343, error, 'initRecords');
                        }
                    }
                });
			},
		}
	};
</script>
