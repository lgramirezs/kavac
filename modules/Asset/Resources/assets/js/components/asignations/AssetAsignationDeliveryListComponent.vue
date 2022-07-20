<template>
	<div class="card-body col-md-12">
		<v-client-table :columns="columns" :data="records" :options="table_options" ref="tableMax">
			<div slot="asset_asignation.code" slot-scope="props" class="text-center">
				<span>
					{{ props.row.asset_asignation.code }}
				</span>
			</div>
			<div slot="observation" slot-scope="props" class="text-center">
				<span>
					{{ (props.row.observation)? props.row.observation:'N/A'}}
				</span>
			</div>
			<div slot="asset_asignation.payroll_staff" slot-scope="props" class="text-center">
				<span>
					{{ (props.row.asset_asignation.payroll_staff)?(props.row.asset_asignation.payroll_staff.first_name + ' ' + props.row.asset_asignation.payroll_staff.last_name):'N/A' }}
				</span>
			</div>
			<div slot="asset_asignation.location_place" slot-scope="props" class="text-center">
				<span>
					{{ (props.row.asset_asignation.location_place)? props.row.asset_asignation.location_place:'N/A' }}
				</span>
			</div>
			<div slot="state" slot-scope="props" class="text-center">
				<span>
					{{ props.row.state }}
				</span>
			</div>
			<div slot="id" slot-scope="props" class="text-center">
				<button @click="acceptRequest(props.index)"
						:disabled="(props.row.state == 'Aprobado')? true:false"
						class="btn btn-success btn-xs btn-icon btn-action"
						title="Aceptar Entrega" data-toggle="tooltip" type="button">
					<i class="fa fa-check"></i>
				</button>

				<button @click="rejectedRequest(props.index)"
						:disabled="(props.row.state == 'Aprobado')? true:false"
						class="btn btn-danger btn-xs btn-icon btn-action" title="Rechazar Entrega" data-toggle="tooltip" type="button">
					<i class="fa fa-ban"></i>
				</button>

				<button @click="deleteRecord(props.row.id, 'asset/asignations/deliver')"
						:disabled="(props.row.state == 'Aprobado')? true:false"
						class="btn btn-danger btn-xs btn-icon btn-action" title="Eliminar registro" data-toggle="tooltip" type="button">
					<i class="fa fa-trash-o"></i>
				</button>
			</div>

		</v-client-table>
	</div>

</template>

<script>
	export default {
		data() {
			return {
				record: {
					id: '',
					observation: '',
					state: '',
					asset_asignation_id: '',
				},

				records: [],
				errors: [],
				columns: ['asset_asignation.code', 'asset_asignation.payroll_staff', 
							'asset_asignation.location_place', 'state', 'observation', 'id'],
			}
		},
		
		created() {
			this.readRecords(this.route_list);
			this.table_options.headings = {
				'asset_asignation.code': 'Código',
				'asset_asignation.payroll_staff': 'Trabajador',
				'asset_asignation.location_place': 'Lugar de ubicación',
				'state': 'Estado de entrega',
				'observation': 'Observaciones',
				'id': 'Acción'
			};
			this.table_options.sortable = ['asset_asignation.code', 'asset_asignation.payroll_staff', 
											'asset_asignation.location_place', 'state'];
			this.table_options.filterable = ['asset_asignation.code', 'asset_asignation.payroll_staff', 
											'asset_asignation.location_place', 'state'];
			
		},
		
		methods: {
			reset() {
				this.record = {
					id: '',
					observation: '',
					state: '',
					asset_asignation_id: '',
				}
			},

			acceptRequest(index)
			{
				const vm = this;
				let fields = vm.records[index-1];
				vm.record.id = fields.id;
				vm.record.state = 'Aprobado';
				vm.record.asset_asignation_id = fields.asset_asignation.id;
			    let dialog = bootbox.confirm({
				    title: '¿Aprobar entrega de equipos?',
				    message: "<div class='row'><div class='col-md-12'><div class='form-group'><label>Observaciones generales</label> <textarea data-toggle='tooltip' class='form-control input-sm' title='Indique las observaciones presentadas en la solicitud' id='asignation_observation'></textarea></div></div></div>",
				    size: 'medium',
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
							vm.record.observation = document.getElementById('asignation_observation').value;
	    					vm.createRecord('asset/asignations/deliver');
	    				}
	    			}
	    		});

			},
			rejectedRequest(index)
			{
				const vm = this;
				let fields = vm.records[index-1];
				vm.record.id = fields.id;
				vm.record.state = 'Rechazado';
				vm.record.asset_asignation_id = fields.asset_asignation.id;
				let dialog = bootbox.confirm({
				    title: '¿Rechazar entrega de equipos?',
				    message: "<div class='row'><div class='col-md-12'><div class='form-group'><label>Observaciones generales</label> <textarea data-toggle='tooltip' class='form-control input-sm' title='Indique las observaciones presentadas en la solicitud' id='asignation_observation'></textarea></div></div></div>",
				    size: 'medium',
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
							vm.record.observation = document.getElementById('asignation_observation').value;
	    					vm.createRecord('asset/asignations/deliver');
	    				}
	    			}
	    		});

			},

			 /**
			 * Método para la eliminación de registros
			 *
			 * @author  Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
			 *
			 * @param  {integer} id    ID del Elemento seleccionado para su eliminación
			 * @param  {string}  url   Ruta que ejecuta la acción para eliminar un registro
			 */
			deleteRecord(id, url) {
				const vm = this;
				/** @type {string} URL que atiende la petición de eliminación del registro */
				var url = vm.setUrl((url)?url:vm.route_delete);

				bootbox.confirm({
					title: "¿Eliminar registro?",
					message: "¿Está seguro de eliminar este registro?",
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
							/** @type {object} Objeto con los datos del registro a eliminar */
							let recordDelete = JSON.parse(JSON.stringify(vm.records.filter((rec) => {
								return rec.id === id;
							})[0]));

							axios.delete(`${url}${url.endsWith('/')?'':'/'}${recordDelete.id}`).then(response => {
								if (typeof(response.data.error) !== "undefined") {
									/** Muestra un mensaje de error si sucede algún evento en la eliminación */
									vm.showMessage('custom', 'Alerta!', 'warning', 'screen-error', response.data.message);
									return false;
								}
								/** @type {array} Arreglo de registros filtrado sin el elemento eliminado */
								vm.records = JSON.parse(JSON.stringify(vm.records.filter((rec) => {
									return rec.id !== id;
								})));
								if (typeof(vm.$refs.tableResults) !== "undefined") {
									vm.$refs.tableResults.refresh();
								}
								location.href = response.data.redirect;
								vm.showMessage('destroy');
							}).catch(error => {
								vm.logs('mixins.js', 498, error, 'deleteRecord');
							});
						}
					}
				}); 
				
			},

		}
	};
</script>