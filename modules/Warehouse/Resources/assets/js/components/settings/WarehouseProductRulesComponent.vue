<template>
	<section id="warehouseProductRuleFormComponent">
		<a class="btn-simplex btn-simplex-md btn-simplex-primary"
		   href="#" title="Reglas de abastecimiento del almacén" data-toggle="tooltip" v-has-tooltip
		   @click="addRecord('add_rule', 'warehouse/rules', $event)">
			<i class="icofont icofont-law-document ico-3x"></i>
			<span>Reglas de abastecimiento</span>
		</a>
		<div class="modal fade text-left" tabindex="-1" role="dialog" id="add_rule">
			<div class="modal-dialog vue-crud" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
						<h6>
							<i class="icofont icofont-law-document ico-2x"></i>
							Reglas de abastecimiento del almacén
						</h6>
					</div>
					<div class="modal-body">
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
									<li v-for="(error, index) in errors" :key="index">{{ error }}</li>
								</ul>
							</div>
						</div>
						<div class="row" v-if="this.record.warehouse_inventory_product_id == ''">
							<div class="col-md-6">
								<div class="form-group">
									<label>Organización que gestiona el almacén:</label>
									<select2 :options="institutions"
											 v-model="institution_id"
											 @input="getWarehouses()">
									</select2>
			                    </div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Almacén:</label>
									<select2 :options="warehouses"
											 v-model="warehouse_id"
											 @input="getWarehouseProducts()">
									</select2>
			                    </div>
							</div>
						</div>
						<div class="row" v-if="this.record.warehouse_inventory_product_id != ''">
							<div class="col-md-6">
								<div class="form-group">
									<label>Organización que gestiona el almacén:</label>
									<select2 :options="institutions"
											 v-model="institution_id"
											 @input="getWarehouses()">
									</select2>
			                    </div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Almacén:</label>
									<select2 :options="warehouses"
											 v-model="warehouse_id">
									</select2>
			                    </div>
							</div>
						</div>
						<hr>
						<div class="row" v-if="this.record.warehouse_inventory_product_id != ''">
							<div class="col-md-3">
								<div class="form-group">
									<label>Minimo:</label>
									<input  type="number" class="form-control input-sm"
											data-toggle="tooltip" min=0
											v-model="record.minimum">
			                    </div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Maximo:</label>
									<input  type="number" class="form-control input-sm"
											data-toggle="tooltip" min=0
											v-model="record.maximum">
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
							<button type="button" @click="createRecord('warehouse/rules')" 
									class="btn btn-primary btn-sm btn-round btn-modal-save">
								Guardar
							</button>
                        </div>
                    </div>
	                <div class="modal-body modal-table">
	                	<hr>
	                	<v-client-table :columns="columns" :data="records"
	                	:options="table_options">
							<div slot="description" slot-scope="props">
								<span>
									{{ (props.row.warehouse_product)?
											props.row.warehouse_product.name+': '+ editField(props.row.warehouse_product.description):'N/A'
									}}
								</span>
							</div>
							<div slot="inventory" slot-scope="props">
								<span>
									<b>Almacén:</b> {{
										props.row.warehouse_institution_warehouse.warehouse.name
										}} <br>
									<b>Existencia:</b> {{ props.row.exist }}<br>
									<b>Reservados:</b> {{ (props.row.reserved === null)? '0':props.row.reserved }}
								</span>
							</div>
							<div slot="rules" slot-scope="props">
								<span>
									<b>Minimo:</b> {{
										(props.row.warehouse_inventory_rule)?props.row.warehouse_inventory_rule.minimum:'N/A'
										}} <br>
									<b>Maximo:</b> {{
										(props.row.warehouse_inventory_rule)?props.row.warehouse_inventory_rule.maximum:'N/A'
										}}
								</span>
							</div>
							<div slot="details" slot-scope="props">
								<span>
									<div v-for="(att, index) in props.row.warehouse_product_values" :key="index">
										<b>{{att.warehouse_product_attribute.name +":"}}</b> {{ att.value}}
									</div>
										<b>Valor:</b> {{props.row.unit_value}} {{(props.row.currency)?props.row.currency.name:''}}
								</span>
							</div>
							<div slot="id" slot-scope="props" class="text-center">
								<div class="d-inline-flex">
									<button @click="editRule(props.index, $event)"
			                				class="btn btn-warning btn-xs btn-icon btn-action"
			                				title="Modificar registro" data-toggle="tooltip" type="button">
			                			<i class="fa fa-edit"></i>
			                		</button>

			                		<button @click="deleteRecord(props.index, 'warehouse/rules')"
											class="btn btn-danger btn-xs btn-icon btn-action"
											title="Eliminar registro" data-toggle="tooltip"
											type="button">
										<i class="fa fa-trash-o"></i>
									</button>
								</div>
							</div>
						</v-client-table>
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
				record: {
					id: 							'',
					minimum: 						'',
					maximum: 						'',
					warehouse_inventory_product_id: '',
				},

				editIndex:      null,
				institution_id: '',
				warehouse_id:   '',
				warehouses:     [],
				institutions:   [],
				errors:         [],
				records:        [],
				columns:        ['code', 'description', 'details', 'inventory', 'rules', 'id'],
			}
		},
		methods: {
			/**
			 * Método que borra todos los datos del formulario
			 *
			 * @author  Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
			 */
			reset()
			{
				const vm = this;
				vm.record = {
					id:      						'',
					minimum: 						'',
					maximum: 						'',
					warehouse_inventory_product_id: '',
				};
				vm.editIndex      = null;
				vm.institution_id = '';
				vm.warehouse_id   = '';
			},
			/**
	         * Método que obtiene los datos de los productos inventariados
	         *
	         * @author Henry Paredes <hparedes@cenditel.gob.ve>
	         */
			getWarehouseProducts()
			{
				const vm = this;
				if ((vm.warehouse_id != '') && (vm.institution_id != '')) {
					var url = "/warehouse/rules/vue-list-products/";
					axios.get(url + vm.warehouse_id + '/' + vm.institution_id).then(response => {
						if(typeof(response.data.records) != "undefined"){
							vm.records = response.data.records;
						}
						else
							vm.records = [];
					});
				} else {
					axios.get('/warehouse/rules/vue-list-products').then(function (response) {
						if (typeof(response.data.records) !== "undefined")
							vm.records = response.data.records;
						else
							vm.records = [];
					});
				}
			},
			/**
	         * Método que carga el formulario con los datos a modificar
	         *
	         * @author Henry Paredes <hparedes@cenditel.gob.ve>
	         */
			editRule(index, event)
			{
				const vm = this;
				this.editIndex = index-1;

				if (vm.records[index-1].warehouse_inventory_rule == null)
					this.record = {
						id:      						'',
						minimum: 						'',
						maximum: 						'',
						warehouse_inventory_product_id: vm.records[index-1].id,
					};
				else
					vm.record = vm.records[index-1].warehouse_inventory_rule;
				if(vm.records[index-1].warehouse_institution_warehouse) {
					vm.institution_id = vm.records[index-1].warehouse_institution_warehouse.institution_id;
					vm.warehouse_id = vm.records[index-1].warehouse_institution_warehouse.warehouse_id;
				}
				event.preventDefault();
			},
			/**
			 * Reescribe el método deleteRecord para cambiar su comportamiento por defecto
	         * Método que elimina los registros
	         *
	         * @author Henry Paredes <hparedes@cenditel.gob.ve>
	         */
			deleteRecord(index, url)
			{
	    		var url = (url)?url:this.route_delete;
	    		var records = this.records;
	    		var confirmated = false;
	    		var index = index - 1;
	    		const vm = this;

	    		bootbox.confirm({
	    			title: "¿Eliminar registro?",
	    			message: "¿Esta seguro de eliminar esta regla?",
	    			buttons: {
	    				cancel: {
	    					label: '<i class="fa fa-times"></i> Cancelar'
	    				},
	    				confirm: {
	    					label: '<i class="fa fa-check"></i> Confirmar'
	    				}
	    			},
	    			callback: function (result) {
						if (result) {
	    					confirmated = true;
							axios.delete(url + '/' + records[index].id).then(response => {
								if (typeof(response.data.error) !== "undefined") {
									/** Muestra un mensaje de error si sucede algún evento en la eliminación */
	    							vm.showMessage('custom', 'Alerta!', 'warning', 'screen-error', response.data.message);
	    							return false;
								}
								vm.readRecords('warehouse/rules');
								vm.showMessage('destroy');
							}).catch(error => {});
	    				}
	    			}
	    		});
			},
			/**
	         * Método edita el campo descripción
	         *
	         * @author Pedro Buitrago <pbuitrago@cenditel.gob.ve>
	         */
			editField(field) {
				if(field) {
					var editfield = field.replace('<p>','');
					return editfield.replace('</p>','');
				}
			},
		},
		created() {
			this.getInstitutions();
			this.table_options.headings = {
				'code':        'Código',
				'description': 'Descripción',
				'details':     'Detalles',
				'inventory':   'Inventario',
				'rules':       'Reglas',
				'id':          'Acción'
			};
			this.table_options.sortable       = ['code', 'description', 'details', 'inventory', 'rules'];
			this.table_options.filterable     = ['code', 'description', 'details', 'inventory', 'rules'];
			this.table_options.columnsClasses = {
                'code':        'col-xs-2',
                'description': 'col-xs-2',
                'details':     'col-xs-2',
                'inventory':   'col-xs-2',
                'rules':       'col-xs-2',
                'id':          'col-xs-2'
            };
		},
		mounted() {
			this.getWarehouseProducts();
		},
	}
</script>
