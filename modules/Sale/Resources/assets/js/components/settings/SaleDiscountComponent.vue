<template>
	<div class="text-center">
		<a class="btn-simplex btn-simplex-md btn-simplex-primary" href=""
		   title="Registros de Descuentos" data-toggle="tooltip"
		   @click="addRecord('add_sale_discount_method', 'sale/discount-method', $event)">
           <i class="icofont icofont-sale-discount ico-3x"></i>
		   <span>Descuentos</span>
		</a>
		<div class="modal fade text-left" tabindex="-1" role="dialog" id="add_sale_discount_method">
			<div class="modal-dialog vue-crud" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
						<h6>
							<i class="icofont icofont-sale-discount ico-3x"></i>
							Descuentos
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
                        <div class="row">
                            <div class="col-md-4">
        						<div class="form-group is-required">
        							<label for="name">Nombre:</label>
        							<input type="text" id="name" placeholder="Nombre"
        								   class="form-control input-sm" v-model="record.name" data-toggle="tooltip"
        								   title="Indique el nombre (requerido)">
        							<input type="hidden" name="id" id="id" v-model="record.id">
        	                    </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group is-required">
        							<label for="description">Descripción:</label>
        							<input type="text" id="description" placeholder="Descripción"
        								   class="form-control input-sm" v-model="record.description" data-toggle="tooltip"
        								   title="Indique la descripción (requerido)">
        	                    </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group is-required">
        							<label for="percent">Porcentaje:</label>
        							<input type="number" min="1" id="percent" placeholder="Descripción"
        								   class="form-control input-sm" v-model="record.percent" data-toggle="tooltip"
        								   title="Indique la descripción (requerido)">
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
							<button type="button" @click="createRecord('sale/discount-method')" 
									class="btn btn-primary btn-sm btn-round btn-modal-save">
								Guardar
							</button>
	                	</div>
	                </div>
	                <div class="modal-body modal-table">
	                	<v-client-table :columns="columns" :data="records" :options="table_options">
	                		<div slot="id" slot-scope="props" class="text-center">
	                			<button @click="initUpdate(props.index, $event)"
		                				class="btn btn-warning btn-xs btn-icon btn-action"
		                				title="Modificar registro" data-toggle="tooltip" type="button">
		                			<i class="fa fa-edit"></i>
		                		</button>
		                		<button @click="deleteRecord(props.index, 'sale/discount-method')"
										class="btn btn-danger btn-xs btn-icon btn-action"
										title="Eliminar registro" data-toggle="tooltip"
										type="button">
									<i class="fa fa-trash-o"></i>
								</button>
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
                    description: '',
                    percent: ''
				},
				errors: [],
				records: [],
				columns: ['name', 'description', 'percent', 'id'],
			}
		},
		methods: {
			/**
			 * Método que borra todos los datos del formulario
			 *
			 * @author  Miguel Narvaez <mnarvaez@cenditel.gob.ve>
			 */
			reset() {
				this.record = {
					id: '',
					name: '',
                    description: '',
                    percent: ''
				};
			},
		},
		created() {
			this.table_options.headings = {
				'name': 'Nombre',
                'description': 'Descripción',
                'percent': 'Porcentaje',
				'id': 'Acción'
			};
			this.table_options.sortable = ['name'];
			this.table_options.filterable = ['name'];
			this.table_options.columnsClasses = {
				'name': 'col-md-3',
                'description': 'col-md-3',
                'percent': 'col-md-3',
				'id': 'col-md-3'
			};
		},
	};
</script>
