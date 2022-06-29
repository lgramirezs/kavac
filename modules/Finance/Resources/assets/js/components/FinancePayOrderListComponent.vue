<template>
    <v-client-table :columns="columns" :data="records" :options="table_options">
		<div slot="status" slot-scope="props" class="text-center">
			<span class="text-danger" v-if="props.row.document_status!==null && props.row.document_status.action==='AN'">Anulada</span>
			<span class="text-danger" v-if="props.row.document_status!==null && props.row.document_status.action==='RE'">Rechazada</span>
			<span v-else>
				<span class="text-success" v-if="props.row.status==='PA'">Pagada</span>
				<span class="text-warning" v-if="props.row.status==='PE'">Pendiente</span>
			</span>
		</div>
		<div slot="id" slot-scope="props" class="text-center">
			<div class="row no-gutters">
				<div class="col-4 mb-1">
					<button class="btn btn-info btn-xs btn-icon btn-action" title="Ver detalles" data-toggle="tooltip" 
							type="button">
						<i class="fa fa-info-circle"></i>
					</button>
				</div>
				<div class="col-4 mb-1">
					<button class="btn btn-success btn-xs btn-icon btn-action" title="Aprobar" data-toggle="tooltip" 
							type="button" @click="changeDocumentStatus('AP', props.row)"
							:disabled="props.row.document_status!==null && props.row.document_status.action==='RE'">
						<i class="fa fa-check"></i>
					</button>
				</div>
				<div class="col-4 mb-1">
					<button class="btn btn-xs btn-icon btn-action" title="Rechazar" data-toggle="tooltip" type="button" 
							@click="changeDocumentStatus('RE', props.row)"
							:disabled="props.row.document_status!==null && props.row.document_status.action==='RE'">
						<i class="fa fa-ban"></i>
					</button>
				</div>
				<div class="col-4 mb-1">
					<a :href="setUrl(`finance/pay-orders/pdf/${props.row.id}`)" target="_blank" 
					   class="btn btn-primary btn-xs btn-icon btn-action" title="Imprimir comprobante" 
					   data-toggle="tooltip">
						<i class="fa fa-print"></i>
					</a>
					
				</div>
				<div class="col-4 mb-1">
					<button @click="editForm(props.row.id)"
							class="btn btn-warning btn-xs btn-icon btn-action"
							title="Modificar registro" data-toggle="tooltip" type="button"
							:disabled="props.row.status==='PP' || props.row.status==='PA'">
						<i class="fa fa-edit"></i>
					</button>
				</div>
				<div class="col-4 mb-1">
					<button @click="deleteRecord(props.row.id, '/finance/pay-orders')"
							class="btn btn-danger btn-xs btn-icon btn-action"
							title="Eliminar registro" data-toggle="tooltip"
							type="button" :disabled="props.row.status==='PP' || props.row.status==='PA'">
						<i class="fa fa-trash-o"></i>
					</button>
				</div>
			</div>
		</div>
	</v-client-table>
</template>

<script>
    export default {
        data() {
            return {
                records: [],
				columns: ['code', 'ordered_at', 'name_sourceable.name', 'concept', 'status', 'id']
            }
        },
		methods: {
			reset() {},
			async changeDocumentStatus(status, record) {
				const vm = this;
				const url = vm.setUrl('finance/pay-orders/change-document-status');
				vm.loading = true;
				await axios.post(url, {id: record.id, action: status}).then(response => {
					record = response.data.record;
					vm.showMessage('custom', 'Éxito!', 'success', 'screen-ok', 'Estatus del documento actualizado');
				}).catch(error => {
					console.error(error);
				});
				vm.loading = false;
			}
		},
        created() {
			this.table_options.headings = {
				'code': 'Código',
				'ordered_at': 'Fecha solicitud',
				'name_sourceable.name': 'Proveedor / Beneficiario',
				'concept': 'Concepto',
				'status': 'Estatus',
				'id': 'Acción'
			};
			this.table_options.columnsClasses = {
				'code': 'col-md-2',
				'ordered_at': 'col-md-2',
				'name_sourceable.name': 'col-md-2',
				'concept': 'col-md-2',
				'status': 'col-md-2',
				'id': 'col-md-2'
			};
			this.table_options.sortable = ['code', 'ordered_at', 'name_sourceable.name', 'concept', 'status'];
			this.table_options.filterable = ['code', 'ordered_at', 'name_sourceable.name', 'concept', 'status'];
		},
        mounted() {
			this.initRecords(this.route_list, '');
		},
    }
</script>