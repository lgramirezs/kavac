<template>
		<v-client-table :columns="columns" :data="records" :options="table_options" ref="tableMax">
			<div slot="id" slot-scope="props" class="text-center">
				<button @click="acceptRequest(props.index)"
						class="btn btn-success btn-xs btn-icon btn-action"
						title="Aceptar Solicitud" data-toggle="tooltip" type="button">
					<i class="fa fa-check"></i>
				</button>

				<button @click="rejectedRequest(props.index)"
						class="btn btn-danger btn-xs btn-icon btn-action" title="Rechazar Solicitud" data-toggle="tooltip" type="button">
					<i class="fa fa-ban"></i>
				</button>
			</div>
			<div slot="code" slot-scope="props" class="text-center">
                <span>
                    {{ props.row.code }}
                </span>
            </div>
			<div slot="created_at" slot-scope="props" class="text-center">
                <span>
                    {{ format_date(props.row.created_at) }}
                </span>
            </div>
			<div slot="delivery_date" slot-scope="props" class="text-center">
                <span>
                    {{ format_date(props.row.delivery_date) }}
                </span>
            </div>

		</v-client-table>
</template>

<script>
	export default {
		data() {
			return {
				records: [],
				errors: [],
				
				columns: ['code', 'state', 'user.name', 'created_at', 'delivery_date', 'id'],
			}
		},
		
		created() {
			this.readRecords(`${this.route_list}`);
			this.table_options.headings = {
				'code': 'Código',
				'state': 'Estado',
				'user.name': 'Solicitante',
				'created_at': 'Fecha de emisión',
				'delivery_date': 'Fecha de entrega',
				'id': 'Acción'
			};
			this.table_options.sortable = ['code', 'state','created_at','delivery_date'];
			this.table_options.filterable = ['code', 'state','created_at','delivery_date'];

		},
		methods: {

			acceptRequest(index)
			{
				const vm = this;
				var fields = this.records[index-1];
				var id = this.records[index-1].id;

				var dialog = bootbox.confirm({
				    title: '¿Aprobar solicitud equipos?',
					message: "¿Está seguro de Aprobar esta solicitud?",
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
							axios.put(vm.route_update+'/request-approved/'+id, fields).then(response => {
								if (typeof(response.data.redirect) !== "undefined") {
									location.href = response.data.redirect;
								}
								else {
									vm.readRecords(url);
									vm.reset();
									vm.showMessage('update');
								}
							}).catch(error => {
								vm.errors = [];

								if (typeof(error.response) !="undefined") {
									for (var index in error.response.data.errors) {
										if (error.response.data.errors[index]) {
											vm.errors.push(error.response.data.errors[index][0]);
										}
									}
								}
							});
	    				}
	    			}
	    		});
			},

			rejectedRequest(index)
			{
				const vm = this;
				var fields = this.records[index-1];
				var id = this.records[index-1].id;

				var dialog = bootbox.confirm({
				    title: '¿Rechazar solicitud de equipos?',
					message: "¿Está seguro de rechazar esta solicitud?",
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
							axios.put(vm.route_update+'/request-rejected/'+id, fields).then(response => {
								if (typeof(response.data.redirect) !== "undefined") {
									location.href = response.data.redirect;
								}
								else {
									vm.readRecords(url);
									vm.reset();
									vm.showMessage('update');
								}
							}).catch(error => {
								vm.errors = [];

								if (typeof(error.response) !="undefined") {
									for (var index in error.response.data.errors) {
										if (error.response.data.errors[index]) {
											vm.errors.push(error.response.data.errors[index][0]);
										}
									}
								}
							});
	    				}
	    			}
	    		});
			},

		}
	};
</script>
