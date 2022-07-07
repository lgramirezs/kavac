<template>
	<section id="AssetAsignationForm">
		<div class="card-body">
			<div class="alert alert-danger" v-if="errors.length > 0">
				<div class="container">
					<div class="alert-icon">
						<i class="now-ui-icons objects_support-17"></i>
					</div>
					<strong>Cuidado!</strong> Debe verificar los siguientes errores antes de continuar:
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"
							@click.prevent="errors = []">
						<span aria-hidden="true">
							<i class="now-ui-icons ui-1_simple-remove"></i>
						</span>
					</button>
					<ul>
						<li v-for="error in errors" :key="error">{{ error }}</li>
					</ul>
				</div>
			</div>

			<div class="row">
				<div class="col-md-12">
					<b>Información del trabajador responsable del bien</b>
				</div>
				<div class="col-md-4" id="helpInstitution">
					<div class="form-group is-required">
						<label>Organización:</label>
						<select2 :options="institutions"
								 v-model="record.institution_id"></select2>
                    </div>

				</div>
				<div class="col-md-4" id="helpStaff">
					<div class="form-group">
						<label>Trabajador:</label>
						<select2 :options="payroll_staffs" v-model="record.payroll_staff_id"></select2>
                    </div>
				</div>
				<div class="col-md-4" id="helpDepartment">
					<div class="form-group">
						<label>Departamento:</label>
						<select2 :options="departments" v-model="record.department_id"></select2>
                    </div>

				</div>
				<div class="col-md-4" id="helpLocationPlace">
					<div class="form-group is-required">
						<label>Lugar de ubicación.</label>
						<input type="text" placeholder="Lugar de ubicación" data-toggle="tooltip"
							   title="Indique el lugar de ubicación del bien a ser asignado"
							   class="form-control input-sm" v-model="record.location_place">
					</div>
				</div>
				<div class="col-md-4" id="helpPositionType">
					<div class="form-group">
						<label>Puesto de trabajo:</label>
						<select2 :options="payroll_position_types" v-model="record.payroll_position_type_id"></select2>
						<input type="hidden" v-model="record.id">
                    </div>
				</div>
				<div class="col-md-4" id="helpPosition">
					<div class="form-group">
						<label>Cargo:</label>
						<select2 :options="payroll_positions" v-model="record.payroll_position_id"></select2>
                    </div>
				</div>

			</div>
			<div class="row">
				<div class="col-md-12">
					<b>Información de los bienes a ser asignados</b>
				</div>
			</div>
			<div class="row" style="margin: 10px 0">
				<div class="col-md-12">
					<b>Filtros</b>
				</div>
			</div>

			<div class="row">
				<div class="col-md-3" id="helpSearchAssetType">
					<div class="form-group">
						<label>Tipo de bien</label>
						<select2 :options="asset_types" @input="getAssetCategories()"
								 v-model="record.asset_type_id"></select2>
					</div>
				</div>

				<div class="col-md-3" id="helpSearchAssetCategory">
					<div class="form-group">
						<label>Categoria general</label>
						<select2 :options="asset_categories" @input="getAssetSubcategories()"
								 v-model="record.asset_category_id"
								 title="Indique la categoria general del bien"></select2>
					</div>
				</div>
				<div class="col-md-3" id="helpSearchAssetSubCategory">
					<div class="form-group">
						<label>Subcategoria</label>
						<select2 :options="asset_subcategories"
								 @input="getAssetSpecificCategories()"
								 v-model="record.asset_subcategory_id"
								 title="Indique la subcategoria del bien"></select2>
					</div>
				</div>

				<div class="col-md-3" id="helpSearchAssetSpecificCategory">
					<div class="form-group">
						<label>Categoria específica</label>
						<select2 :options="asset_specific_categories"
								 v-model="record.asset_specific_category_id"
								 title="Indique la categoria específica del bien"></select2>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<button type="button" id="helpSearchButton" @click="filterRecords()"
                            class="btn btn-sm btn-primary btn-info float-right" title="Buscar registros"
                            data-toggle="tooltip">
						<i class="fa fa-search"></i>
					</button>
				</div>
			</div>

			<hr>
			<v-client-table v-if="record.institution_id"  id="helpTable"
				@row-click="toggleActive" :columns="columns" :data="records" :options="table_options" ref="tableMax">
				<div slot="h__check" class="text-center">
					<label class="form-checkbox">
						<input type="checkbox" v-model="selectAll" @click="select()" class="cursor-pointer">
					</label>
				</div>

				<div slot="check" slot-scope="props" class="text-center">
					<label class="form-checkbox">
						<input type="checkbox" class="cursor-pointer" :value="props.row.id"
							:id="'checkbox_'+props.row.id" v-model="selected">
					</label>
				</div>
				<div slot="institution" slot-scope="props" class="text-center">
					<span>
						{{ 
							(props.row.institution)
							?props.row.institution.name
							:((props.row.institution_id)?props.row.institution_id:'N/A')
						}}
					</span>

				</div>
				<div slot="asset_condition" slot-scope="props" class="text-center">
					<span>
						{{ 
							(props.row.asset_condition)
							?props.row.asset_condition.name
							:props.row.asset_condition_id
						}}
					</span>
				</div>
				<div slot="asset_status" slot-scope="props" class="text-center">
					<span>{{ (props.row.asset_status)? props.row.asset_status.name:props.row.asset_status_id }}</span>
				</div>
			</v-client-table>
		</div>
        <div class="card-footer text-right">
			<div class="row">
				<div class="col-md-3 offset-md-9" id="helpParamButtons">
		        	<button type="button" @click="reset()"
							class="btn btn-default btn-icon btn-round"
							data-toggle="tooltip"
							title="Borrar datos del formulario">
							<i class="fa fa-eraser"></i>
					</button>

		        	<button type="button" @click="redirect_back(route_list)"
		        			class="btn btn-warning btn-icon btn-round btn-modal-close"
		        			data-dismiss="modal"
		        			title="Cancelar y regresar">
		        			<i class="fa fa-ban"></i>
		        	</button>

		        	<button type="button"  @click="createForm('asset/asignations')"
		        			class="btn btn-success btn-icon btn-round btn-modal-save"
		        			title="Guardar registro">
		        		<i class="fa fa-save"></i>
		            </button>
		        </div>
		    </div>
        </div>
	</section>
</template>

<script>
	export default {
		data() {
			return {
				record: {
					id: '',
					payroll_position_type_id: '',
					payroll_position_id: '',
					payroll_staff_id: '',
					institution_id: '',
					department_id: '',
					asset_type_id: '',
					asset_category_id: '',
					asset_subcategory_id: '',
					asset_specific_category_id: '',
				},
				getStaffIdInfo:'',
				errors: [],
				records: [],
				
				columns: [
                    'check', 'inventory_serial', 'institution', 'asset_condition', 'asset_status', 'serial',
                    'marca', 'model'
                ],

				payroll_position_types:[],
				payroll_positions:[],
				payroll_staffs:[],
				institutions: [],
				departments:[],

				asset_types: [],
				asset_categories: [],
				asset_subcategories: [],
				asset_specific_categories: [],

				selected: [],
				selectAll: false,

				table_options: {
					rowClassCallback(row) {
						var checkbox = document.getElementById('checkbox_' + row.id);
						return ((checkbox)&&(checkbox.checked))? 'selected-row cursor-pointer' : 'cursor-pointer';
					},
					headings: {
						'inventory_serial': 'Código',
						'asset_condition': 'Condición Física',
						'asset_status': 'Estatus de Uso',
						'serial': 'Serial',
						'marca': 'Marca',
						'model': 'Modelo',
					},
					sortable: ['inventory_serial', 'asset_condition', 'asset_status', 'serial', 'marca', 'model'],
					filterable: ['inventory_serial', 'asset_condition', 'asset_status', 'serial', 'marca', 'model']
				}
			}
		},

		created() {
			const vm = this;
			vm.getInstitutions();
			vm.getPayrollStaffs();
			vm.getAssetTypes();
			// vm.getPayrollPositionTypes();
			// vm.getPayrollPositions();
		},
		mounted() {
			const vm = this;
			let url = `${window.app_url}/asset/registers/vue-list` 
			url += (vm.asignationid != null)?'/asignations/' + vm.asignationid:'/asignations'
			this.readRecords(url);
			
			if((this.asignationid)&&(!this.assetid)) {
				this.loadForm(this.asignationid);
            }
			else if((!this.asignationid)&&(this.assetid)) {
				this.selected.push(this.assetid);
            }
		},
		props: {
			asignationid: Number,
			assetid: Number,
		},
		methods: {
			toggleActive({ row })
			{
				const vm = this;
				var checkbox = document.getElementById('checkbox_' + row.id);

				if((checkbox)&&(checkbox.checked == false)){
					var index = vm.selected.indexOf(row.id);
					if (index >= 0){
						vm.selected.splice(index,1);
					}
					else {
						checkbox.click();
                    }
				}
				else if ((checkbox)&&(checkbox.checked == true)) {
					var index = vm.selected.indexOf(row.id);
					if (index >= 0) {
						checkbox.click();
                    }
					else {
						vm.selected.push(row.id);
                    }
				}
		    },

			reset()
			{
				this.record = {
					id: '',
					payroll_position_type_id: '',
					payroll_position_id: '',
					payroll_staff_id: '',
					institution_id: '',
					department_id: '',
					asset_type_id: '',
					asset_category_id: '',
					asset_subcategory_id: '',
					asset_specific_category_id: '',
				};
				this.selected = [];
				this.selectAll = false;

			},
			select()
			{
				const vm = this;
				vm.selected = [];
				$.each(vm.records, function(index,campo){
					var checkbox = document.getElementById('checkbox_' + campo.id);

					if(!vm.selectAll) {
						vm.selected.push(campo.id);
                    }
					else if(checkbox && checkbox.checked) {
						checkbox.click();
					}
				});
			},
			
			createForm(url)
			{
				const vm = this;
				vm.errors = [];
				if(!vm.selected.length > 0){
                    bootbox.alert("Debe agregar al menos un elemento a la solicitud");
					return false;
				};
				vm.record.assets = vm.selected;
				vm.createRecord(url);
			},
			loadForm(id)
			{
				const vm = this;
	            var fields = {};

	            axios.get(`${window.app_url}/asset/asignations/vue-info/${id}`).then(response => {
	                if(typeof(response.data.records != "undefined")){

						vm.record = response.data.records;
	                    fields = response.data.records.asset_asignation_assets;
	                    $.each(fields, function(index,campo){
	                        vm.selected.push(campo.asset.id);
	                    });
	                }
	            });
			},
			
			filterRecords()
			{
				const vm = this;
				var url =  `${window.app_url}/asset/registers/search/clasification`;

				var filters = {
					asset_type: vm.record.asset_type_id,
					asset_category: vm.record.asset_category_id,
					asset_subcategory: vm.record.asset_subcategory_id,
					asset_specific_category: vm.record.asset_specific_category_id,
					institution_id: vm.record.institution_id,
				};

				axios.post(url, filters).then(response => {
					vm.records = response.data.records;
				});

			},
		}
	};
</script>
