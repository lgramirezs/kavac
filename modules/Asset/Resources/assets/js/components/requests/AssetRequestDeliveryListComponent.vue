<template>
	<div class="card-body col-md-12">
		<v-client-table :columns="columns" :data="records" :options="table_options" ref="tableMax">
			<div slot="observation" slot-scope="props" class="text-center">
				<span>
					{{ (props.row.observation)? props.row.observation:'No definido'}}
				</span>
			</div>
			<div slot="created_at" slot-scope="props" class="text-center">
				<span>
					{{ (props.row.created_at)? format_date(props.row.created_at):'N/A'}}
				</span>
			</div>
			<div slot="id" slot-scope="props" class="text-center">
				<button @click="acceptRequest(props.index)"
						:disabled="(props.row.state == 'Aprobado')? true:false"
						class="btn btn-success btn-xs btn-icon btn-action"
						title="Aceptar Solicitud" data-toggle="tooltip" type="button">
					<i class="fa fa-check"></i>
				</button>

				<button @click="rejectedRequest(props.index)"
						:disabled="(props.row.state == 'Aprobado')? true:false"
						class="btn btn-danger btn-xs btn-icon btn-action" title="Rechazar Solicitud" data-toggle="tooltip" type="button">
					<i class="fa fa-ban"></i>
				</button>

				<button @click="deleteRecord(props.row.id, 'asset/requests/deliveries')"
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
					asset_request_id: '',
				},

				records: [],
				errors: [],
				columns: ['asset_request.code', 'state', 'user.name', 'created_at', 'observation','id'],
			}
		},
		
		created() {
			this.readRecords(this.route_list);
			this.table_options.headings = {
				'asset_request.code': 'Código de solicitud',
				'state': 'Estado de entrega',
				'user.name': 'Solicitante',
				'created_at': 'Fecha de emisión',
				'observation': 'Observaciones',
				'id': 'Acción'
			};
			this.table_options.sortable = ['asset_request.code', 'state', 'user.name', 'created_at'];
			this.table_options.filterable = ['asset_request.code', 'state', 'user.name', 'created_at'];
			
		},
		
		methods: {
			reset() {
				this.record = {
					id: '',
					observation: '',
					state: '',
					asset_request_id: '',
				}
			},

			acceptRequest(index)
			{
				const vm = this;
				var fields = vm.records[index-1];
				vm.record.id = fields.id;
				vm.record.state = 'Aprobado';
				vm.record.asset_request_id = fields.asset_request.id;
				var dialog = bootbox.confirm({
				    title: '¿Aprobar entrega de equipos?',
				    message: "<div class='row'><div class='col-md-12'><div class='form-group'><label>Observaciones generales</label> <textarea data-toggle='tooltip' class='form-control input-sm' title='Indique las observaciones presentadas en la solicitud' id='request_observation'></textarea></div></div></div>",
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
							vm.record.observation = document.getElementById('request_observation').value;
	    					vm.createRecord('asset/requests/deliveries');
	    				}
	    			}
	    		});

			},
			rejectedRequest(index)
			{
				const vm = this;
				var fields = vm.records[index-1];
				vm.record.id = fields.id;
				vm.record.state = 'Rechazado';
				vm.record.asset_request_id = fields.asset_request.id;
				var dialog = bootbox.confirm({
				    title: '¿Rechazar entrega de equipos?',
				    message: "<div class='row'><div class='col-md-12'><div class='form-group'><label>Observaciones generales</label> <textarea data-toggle='tooltip' class='form-control input-sm' title='Indique las observaciones presentadas en la solicitud' id='request_observation'></textarea></div></div></div>",
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
							vm.record.observation = document.getElementById('request_observation').value;
	    					vm.createRecord('asset/requests/deliveries');
	    				}
	    			}
	    		});

			},

		}
	};
</script>
