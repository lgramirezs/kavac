<template>
	<div class="col-xs-2 text-center">
		<a class="btn-simplex btn-simplex-md btn-simplex-primary"
		   href="#" title="Registros de chequeras"
		   data-toggle="tooltip"
		   @click="addRecord('add_check_book', '/finance/check-books', $event)">
			<i class="icofont icofont-card ico-3x"></i>
			<span>Chequeras</span>
		</a>
		<div class="modal fade text-left" tabindex="-1" role="dialog" id="add_check_book">
			<div class="modal-dialog vue-crud" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
						<h6>
							<i class="icofont icofont-card inline-block"></i>
							Chequera
						</h6>
					</div>
					<div class="modal-body">
						<input type="hidden" v-model="record.id">
						<div class="alert alert-danger" v-if="errors.length > 0">
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
						<div class="row">
							<div class="col-md-6">
								<div class="form-group is-required">
									<label>Banco</label>
									<select2 :options="banks" v-model="record.finance_bank_id"
											 @input="getBankAccounts"></select2>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group is-required">
									<label>Cuenta</label>
									<select2 :options="accounts" v-model="record.bank_account_id"></select2>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								<div class="form-group is-required">
									<label>Serial / Código</label>
									<input type="text" class="form-control input-sm" v-model="record.code" data-toggle="tooltip"
										   title="Número de serial o código único que identifica la chequera">
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group is-required">
									<label>Nro. de Cheques</label>
									<input type="text" class="form-control input-sm" v-model="record.checks" data-toggle="tooltip"
										   title="Cantidad de cheques a registrar">
								</div>
							</div>
							<div class="col-md-1">
								<button class="btn btn-sm btn-info btn-action btn-tooltip" style="margin:30px auto"
										title="Asignar números de cheques" data-toggle="tooltip" @click="addChecks">
									<i class="fa fa-plus-circle"></i>
								</button>
							</div>
						</div>
						<div class="row" v-if="record.checks && record.checks > 0">
							<div class="col-md-3" v-for="(number, index) in record.numbers" :key="index">
								<div class="form-group is-required">
									<label>Cheque #{{ index + 1 }}</label>
									<input type="text" class="form-control input-sm" data-toggle="tooltip"
										   title="Indique el número de cheque" v-model="record.numbers[index]">
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
							<button type="button" @click="createVRecord('finance/check-books')" 
									class="btn btn-primary btn-sm btn-round btn-modal-save">
								Guardar
							</button>
	                	</div>
	                </div>
	                <div class="modal-body modal-table">
	                	<v-client-table :columns="columns" :data="records" :options="table_options">
	                		<div slot="code" slot-scope="props" class="text-center">
	                			{{ props.row.code }}
	                		</div>
	                		<div slot="checks" slot-scope="props" class="text-center">
	                			{{ props.row.checks }}
	                		</div>
	                		<div slot="id" slot-scope="props" class="text-center">
	                			<button @click="initUpdate_finance(props.row.id, $event)"
		                				class="btn btn-warning btn-xs btn-icon btn-action btn-tooltip"
		                				title="Modificar registro" data-toggle="tooltip" type="button"
		                				>
		                			<i class="fa fa-edit"></i>
		                		</button>
		                		<button @click="deleteRecord(props.row.id, '/finance/check-books')"
										class="btn btn-danger btn-xs btn-icon btn-action btn-tooltip"
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
					code: '',
					checks: '',
					finance_bank_id: '',
					bank_account_id: '',
					numbers: [],
				},
				banks: [],
				accounts: [],
				errors: [],
				records: [],
				columns: ['finance_bank', 'code', 'cant_checks', 'id'],
			}
		},
		watch: {
			record: {
                deep: true,
                handler: function(newValue, oldValue) {
                }
            },
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
					code: '',
					checks: '',
					finance_bank_id: '',
					bank_account_id: '',
					numbers: [],
				};
			},

			createVRecord(){
			if (this.record.numbers.length === 0) {
				    this.addChecks();
				 
				 }
               this.createRecord('finance/check-books');

			},
			/**
			 * Método que permite agregar una cantidad específica de campos para el registro de números de cheques
			 *
			 * @author  Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
			 */
			addChecks() {
				let check_numbers = [];
            	if (this.record.checks && this.record.checks > 0) {
            		for (var i = 0; i < this.record.checks; i++) {
            			check_numbers.push('');
            		}
            	}
            	this.record.numbers = check_numbers;
			},

			/**
              * Método que carga el formulario con los datos a modificar
              *
              * @author  Tsu. Miguel Narvaez <mnarvaez@cenditel.gob.ve> | <miguelnarvaez31@gmail.com>
              *
              * @param  {integer} index Identificador del registro a ser modificado
              * @param {object} event   Objeto que gestiona los eventos
              */
            initUpdate_finance(id, event) {
                let vm = this;
                vm.errors = [];
  
                let recordEdit = JSON.parse(JSON.stringify(vm.records.filter((rec) => {
                    return rec.id === id;
                })[0])) || vm.reset();
 
                vm.record.id = recordEdit.id;
                vm.record.code = recordEdit.code;
                vm.record.checks = recordEdit.checks;
                vm.record.numbers = recordEdit.numbers;
                vm.record.finance_bank_id = recordEdit.finance_bank_id;

                const timeOpen = setTimeout(addBankAccountId, 4000);
                
                function addBankAccountId () {
                    vm.record.bank_account_id = recordEdit.finance_bank_account_id;
                }
                event.preventDefault();
            },

		},
		created() {
			this.table_options.headings = {
				'finance_bank': 'Banco',
				'code': 'Código Chequera',
				'cant_checks': 'Cheques',
				'id': 'Acción'
			};
			this.table_options.sortable = ['finance_bank', 'code', 'cant_checks'];
			this.table_options.filterable = ['finance_bank', 'code', 'cant_checks'];
			this.table_options.columnsClasses = {
				'finance_bank': 'col-md-6',
				'code': 'col-md-2',
				'cant_checks': 'col-md-2',
				'id': 'col-md-2'
			};
			this.getBanks();
		},
	};
</script>
