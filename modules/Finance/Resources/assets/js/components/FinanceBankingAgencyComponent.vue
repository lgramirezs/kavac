<template>
	<div class="col-xs-2 text-center">
		<a class="btn-simplex btn-simplex-md btn-simplex-primary"
		   href="#" title="Registros de agencias bancarias"
		   data-toggle="tooltip" @click="addRecord('add_banking_agency', '/finance/banking-agencies', $event)">
			<i class="icofont icofont-business-man ico-3x"></i>
			<span>Agencias<br>Bancarias</span>
		</a>
		<div class="modal fade text-left" tabindex="-1" role="dialog" id="add_banking_agency">
			<div class="modal-dialog vue-crud" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
						<h6>
							<i class="icofont icofont-business-man inline-block"></i>
							Agencias Bancarias
						</h6>
					</div>
					<div class="modal-body">
						<div class="alert alert-danger" v-if="errors.length > 0">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"
							@click.prevent="errors = []">
						<span aria-hidden="true">
							<i class="now-ui-icons ui-1_simple-remove"></i>
						</span>
					</button>
							<ul>
								<li v-for="(error, index) in errors" :key="index">{{ error }}</li>
							</ul>
						</div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Sede principal</label>
                                    <div class="col-md-12">
                                    
								   <div class="pretty p-switch p-fill p-bigger p-toggle">
                                                                    <input type="checkbox" data-toggle="tooltip" title="Indique si el campo está activo" v-model="record.headquarters">
                                                                    <div class="state p-off">
                                                                        <label></label>
                                                                    </div>
                                                                    <div class="state p-on p-success">
                                                                        <label></label>
                                                                    </div>
                                                                </div>
                                    </div>
                                </div>
                            </div>
                        </div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group is-required">
									<label>País</label>
									<select2 :options="countries" @input="getEstates"
                                             v-model="record.country_id"></select2>
									<input type="hidden" v-model="record.id">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group is-required">
									<label>Estado</label>
									<select2 :options="estates" @input="getCities" v-model="record.estate_id"></select2>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group is-required">
									<label>Ciudad</label>
									<select2 :options="cities" v-model="record.city_id"></select2>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group is-required">
									<label>Banco</label>
									<select2 :options="banks" v-model="record.finance_bank_id"></select2>
								</div>
                                <div class="form-group">
                                    <label>Persona de contacto</label>
                                    <input type="text" placeholder="Nombre contacto" data-toggle="tooltip"
                                           title="Indique el nombre de la persona de contacto"
                                           class="form-control input-sm" v-model="record.contact_person">
                                </div>
							</div>
							<div class="col-md-4">
								<div class="form-group is-required">
									<label>Nombre de Agencia</label>
									<input type="text" placeholder="Nombre agencia" data-toggle="tooltip"
										   title="Indique el nombre de la agencia bancaria (requerido)"
										   class="form-control input-sm" v-model="record.name">
								</div>
                                <div class="form-group">
                                    <label>Correo de contacto</label>
                                    <input type="text" placeholder="Nombre contacto" data-toggle="tooltip"
                                           title="Indique el correo de la persona de contacto"
                                           class="form-control input-sm" v-model="record.contact_email">
                                </div>
							</div>
							<div class="col-md-4">
								<div class="form-group is-required">
									<label>Dirección</label>
                                    <ckeditor :editor="ckeditor.editor" id="direction" data-toggle="tooltip"
                                              title="Indique la dirección de la agencia bancaria"
                                              :config="ckeditor.editorConfig" class="form-control" name="direction"
                                              tag-name="textarea" rows="3" v-model="record.direction"></ckeditor>
								</div>
							</div>
						</div>
						<hr>
						<h6 class="card-title">
							Números Telefónicos <i class="fa fa-plus-circle cursor-pointer" @click="addPhone"></i>
						</h6>
						<div class="row" v-for="(phone, index) in record.phones" :key="index">
							<div class="col-3">
								<div class="form-group is-required">
									<select data-toggle="tooltip" v-model="phone.type"
											class="select2"
											title="Seleccione el tipo de número telefónico">
										<option value="">Seleccione...</option>
										<option value="M">Móvil</option>
										<option value="T">Teléfono</option>
										<option value="F">Fax</option>
									</select>
								</div>
							</div>
							<div class="col-2">
								<div class="form-group is-required">
									<input type="text" placeholder="Cod. Area" data-toggle="tooltip"
										   title="Indique el código de área" v-model="phone.area_code"
										   class="form-control input-sm">
								</div>
							</div>
							<div class="col-4">
								<div class="form-group is-required">
									<input type="text" placeholder="Número" data-toggle="tooltip"
										   title="Indique el número telefónico"
										   v-model="phone.number" class="form-control input-sm">
								</div>
							</div>
							<div class="col-2">
								<div class="form-group is-required">
									<input type="text" placeholder="Extensión" data-toggle="tooltip"
										   title="Indique la extención telefónica (opcional)"
										   v-model="phone.extension" class="form-control input-sm">
								</div>
							</div>
							<div class="col-1">
								<div class="form-group">
									<button class="btn btn-sm btn-danger btn-action" type="button"
											@click="removeRow(index, record.phones)"
											title="Eliminar este dato" data-toggle="tooltip">
										<i class="fa fa-minus-circle"></i>
									</button>
								</div>
							</div>
						</div>
	                </div>
	                <div class="modal-footer">
	                	<div class="form-group">
	                		<button type="button" class="btn btn-default btn-sm btn-round btn-modal-close" 
									@click="clearFilters" data-dismiss="modal">
								Cerrar
							</button>
							<button type="button" class="btn btn-warning btn-sm btn-round btn-modal btn-modal-clear" 
									@click="reset()">
								Cancelar
							</button>
							<button type="button" @click="createRecord('finance/banking-agencies')" 
									class="btn btn-primary btn-sm btn-round btn-modal-save">
								Guardar
							</button>
	                	</div>
	                </div>
	                <div class="modal-body modal-table">
	                	<v-client-table :columns="columns" :data="records" :options="table_options">
							<div slot="direction" slot-scope="props" class="text-center">
	                			<span class="text-center">
	                				{{ props.row.direction.replace(/<\/?[^>]+(>|$)/g, "") }} 
	                			</span>
	                		</div>
	                		<div slot="id" slot-scope="props" class="text-center">
	                			<button @click="customupdate(props.row.id, $event)"
		                				class="btn btn-warning btn-xs btn-icon btn-round"
		                				title="Modificar registro" data-toggle="tooltip" type="button">
		                			<i class="fa fa-edit"></i>
		                		</button>
		                		<button @click="deleteRecord(props.row.id, '/finance/banking-agencies')"
										class="btn btn-danger btn-xs btn-icon btn-round"
										title="Eliminar registro" data-toggle="tooltip"
										type="button">
									<i class="fa fa-trash-o"></i>
								</button>
	                		</div>
	                		<div slot="headquarters" slot-scope="props" class="text-center">
	                			<span v-if="props.row.headquarters">SI</span>
	                			<span v-else>NO</span>
	                		</div>
	                		<div slot="phones" slot-scope="props" class="text-center">
	                			<span v-for="(phone, index) in props.row.phones" :key="index">
	                				<div>
	                					{{ phone.area_code }} {{ phone.number }}
		                				{{ (phone.extension) ? ' - ' + phone.extension : '' }}
	                				</div>
	                			</span>
	                		</div>
	                	</v-client-table>
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
				record: {
					id: '',
					name: '',
					direction: '',
					headquarters: false,
					contact_person: '',
					contact_email: '',
					finance_bank_id: '',
					country_id: '',
					estate_id: '',
					city_id: '',
					phones: [],
				},
				errors: [],
				records: [],
				banks: [],
				countries: [],
				estates: ['0'],
				cities: ['0'],
				columns: ['finance_bank.name', 'city.name', 'name', 'direction', 'headquarters', 'phones', 'id'],
			}
		},
		methods: {
			/**
			 * Método que borra todos los datos del formulario
			 *
			 * @author  Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
			 */
		
				reset() {
				this.record = {
					id: '',
					name: '',
					direction: '',
					headquarters: false,
					contact_person: '',
					contact_email: '',
					finance_bank_id: '',
					country_id: '',
					estate_id: '',
					city_id: '',
					phones: [],
				};
			},
			customupdate(id,event ){
              	let vm = this;
			vm.errors = [];

			let recordEdit = JSON.parse(JSON.stringify(vm.records.filter((rec) => {
				return rec.id === id;
			})[0])) || vm.reset();
     
			vm.record = recordEdit;
            	vm.record.country_id = vm.record.city.estate.country_id;		

			/**
			 * Recorre todos los campos para determinar si existe un elemento booleano para, posteriormente,
			 * seleccionarlo en el formulario en el caso de que se encuentre activado en BD
			 */
			$.each(vm.record, function(el, value) {
				if ($("input[name=" + el + "]").hasClass('bootstrap-switch')) {
					/** verifica los elementos bootstrap-switch para seleccionar el que corresponda según los registros del sistema */
					$("input[name=" + el + "]").each(function() {
						if ($(this).val() === value) {
							$(this).bootstrapSwitch('state', value, true)
						}

					});
				}
				if (value === true || value === false) {
					$("input[name=" + el + "].bootstrap-switch").bootstrapSwitch('state', value, true);
				}
			});

			event.preventDefault();
          
			},

           	/**
			 * Reescribe el método getEstates para cambiar su comportamiento por defecto
			 * Obtiene los Estados del Pais seleccionado
			 *
			 */
			async getEstates() {
				const vm = this;
				vm.estates = [];
      
		
				if (vm.record.country_id) {
					await axios.get(`${window.app_url}/get-estates/${this.record.country_id}`).then(response => {
						vm.estates = response.data;
					});
	
						
					if ((vm.record.city.estate.id ) && (vm.record.id)) {	          		
						vm.record.estate_id = vm.record.city.estate.id;  
						vm.record.city_id = vm.record.city_id;
	          			vm.getCities();
	          		}
				}
			},
			/**
			 * * Reescribe el método getCities para cambiar su comportamiento por defecto
			 * Obtiene los ciudades del Estado seleccionado
			 *
			 */
			async getCities() {
				const vm = this;
				vm.cities = [];

				if (vm.record.estate_id) {
					await axios.get(`${window.app_url}/get-cities/${this.record.estate_id}`).then(response => {
							vm.cities = response.data;
					});
				}
				   if(vm.record.id){
					    	if (vm.record.city.id )  {	          		
					  
						vm.record.city_id = vm.record.city.id;
	          			
	          		}

				   }
				 
			
			},			

		},
		created() {
			this.table_options.headings = {
				'finance_bank.name': 'Banco',
				'city.name': 'Ciudad',
				'name': 'Agencia Bancaria',
				'direction': 'Dirección',
				'headquarters': 'Sede Principal',
				'phones': 'Números Telefónicos',
				'id': 'Acción'
			};
			this.table_options.sortable = ['finance_bank.name', 'city.name', 'name'];
			this.table_options.filterable = ['finance_bank.name', 'city.name', 'name'];
			this.getCountries();
			this.getBanks();
		},
	};
</script>
