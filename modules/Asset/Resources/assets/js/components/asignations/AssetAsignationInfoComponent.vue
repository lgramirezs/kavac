<template>
	<div>
		<a class="btn btn-info btn-xs btn-icon btn-action"
		   href="#" title="Ver información del registro" data-toggle="tooltip"
		   @click="addRecord('view_asignation',route_list ,$event)">
			<i class="fa fa-info-circle"></i>
		</a>
		<div class="modal fade text-left" tabindex="-1" role="dialog" id="view_asignation">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="reset()">
							<span aria-hidden="true">×</span>
						</button>
						<h6>
							<i class="icofont icofont-read-book ico-2x"></i>
							Información de la Asignación Registrada
						</h6>
					</div>

					<div class="modal-body">

						<div class="alert alert-danger" v-if="errors.length > 0">
							<ul>
								<li v-for="error in errors" :key="error">{{ error }}</li>
							</ul>
						</div>
						<ul class="nav nav-tabs custom-tabs justify-content-center" role="tablist">
	                        <li class="nav-item">
	                            <a class="nav-link active" data-toggle="tab" href="#general" id="info_general" role="tab">
	                                <i class="ion-android-person"></i> Información general
	                            </a>
	                        </li>

	                        <li class="nav-item">
	                            <a class="nav-link" data-toggle="tab" href="#equipment_assigned" role="tab" @click="loadEquipment()">
	                                <i class="ion-arrow-swap"></i> Equipos asignados
	                            </a>
	                        </li>
							<li class="nav-item" v-if="flag">
	                            <a class="nav-link" data-toggle="tab" href="#equipment_delivered" role="tab">
	                                <i class="ion-arrow-swap"></i> Equipos entregados
	                            </a>
	                        </li>
	                    </ul>

	                    <div class="tab-content">
	                    	<div class="tab-pane active" id="general" role="tabpanel">
	                    		<div class="row">
							        <div class="col-md-6">
										<div class="form-group">
											<strong>Fecha de registro</strong>
											<div class="row" style="margin: 1px 0">
												<span class="col-md-12" id="date_init">
												</span>
											</div>
											<input type="hidden" id="id">
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<strong>Trabajador responsable de los bienes asignados</strong>
											<div class="row" style="margin: 1px 0">
												<span class="col-md-12" id="staff">
												</span>
											</div>
							            </div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<strong>Lugar de ubicación de los bienes asiganados</strong>
											<div class="row" style="margin: 1px 0">
												<span class="col-md-12" id="location">
												</span>
											</div>
							            </div>
									</div>

							    </div>
	                    	</div>

	                    	<div class="tab-pane" id="equipment_assigned" role="tabpanel">
	                    		<div class="row">
	                    			<div class="col-md-12">
										<hr>
										<v-client-table :columns="columns" :data="equipments_assigned" :options="table_options"></v-client-table>
									</div>
	                    		</div>
	                    	</div>
							<div class="tab-pane" id="equipment_delivered" role="tabpanel">
	                    		<div class="row">
	                    			<div class="col-md-12">
										<hr>
										<v-client-table :columns="columns" :data="equipments_delivered" :options="table_options"></v-client-table>
									</div>
	                    		</div>
	                    	</div>
	                    </div>
					</div>

	                <div class="modal-footer">

	                	<button type="button" class="btn btn-default btn-sm btn-round btn-modal-close"
	                			data-dismiss="modal" @click="reset()">
	                		Cerrar
	                	</button>
		            </div>
		        </div>
		    </div>
		</div>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				records: [],
				equipments_assigned: [],
				equipments_delivered: [],
				errors: [],
				flag : false,
				columns: ['asset.inventory_serial','asset.serial','asset.marca','asset.asset_institutional_code'],
			}
		},
		created() {
			this.table_options.headings = {
				'asset.inventory_serial': 'Código',
				'asset.serial': 'Serial',
				'asset.marca': 'Marca',
				'asset.asset_institutional_code': 'Código org.',
			};
			this.table_options.sortable = ['asset.inventory_serial','asset.serial','asset.marca','asset.asset_institutional_code'];
			this.table_options.filterable = ['asset.inventory_serial','asset.serial','asset.marca','asset.asset_institutional_code'];
			this.table_options.orderBy = { 'column': 'asset.id'};

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
				vm.equipments_assigned = [];
				vm.equipments_delivered = [];
				vm.flag = false;
            },

			/**
			 * Inicializa los registros base del formulario
			 *
			 * @author Henry Paredes <hparedes@cenditel.gob.ve>
			 */
            initRecords(url,modal_id){
            	this.errors = [];

				const vm = this;
            	var fields = {};

            	document.getElementById("info_general").click();
				url = vm.setUrl(url);

            	axios.get(url).then(response => {
					if (typeof(response.data.records) !== "undefined") {
						fields = response.data.records;

						$(".modal-body #id").val( fields.id );
		            	document.getElementById('date_init').innerText = (fields.created_at)?vm.format_date(fields.created_at):'';
		            	document.getElementById('staff').innerText = (fields.payroll_staff)?fields.payroll_staff.first_name + ' ' + fields.payroll_staff.last_name:'N/A';
						document.getElementById('location').innerText = (fields.location_place)?fields.location_place:'N/A';
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
            loadEquipment(){
				const vm = this;
				vm.equipments_assigned = [];
				vm.equipments_delivered = [];
				let equipments = [];
				vm.flag = true;
            	var index = $(".modal-body #id").val();
				axios.get(`${window.app_url}/asset/asignations/vue-info/${index}`).then(response => {
					vm.records = response.data.records.asset_asignation_assets;
					equipments = JSON.parse(response.data.records.ids_assets);
					if(equipments == null){
						vm.equipments_assigned = vm.records;
					}else{
						vm.equipments_assigned = vm.records.filter(asset => equipments.assigned.includes(asset.asset.id)
																	|| equipments.possible_deliveries.includes(asset.asset.id));
						vm.equipments_delivered = vm.records.filter(asset => equipments.delivered.includes(asset.asset.id));
					}
				});
			}
		},
	};
</script>
