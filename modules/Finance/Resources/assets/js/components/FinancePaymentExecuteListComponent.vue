<template>
    <v-client-table :columns="columns" :data="records" :options="table_options">
		<div slot="observations" slot-scope="props" class="text-center">
			<div v-html="props.row.observations" v-if="props.row.observations"></div>
			<div v-else></div>
		</div>
		<div slot="status" slot-scope="props" class="text-center">
			<span class="text-danger" v-if="props.row.document_status!==null && props.row.document_status.action==='AN'">Anulado</span>
			<span class="text-danger" v-if="props.row.document_status!==null && props.row.document_status.action==='RE'">Rechazado</span>
			<div v-else>
				<div v-if="props.row.status==='PP'">Parcialmente pagado</div>
				<div v-if="props.row.status==='PA'">Pagado</div>
				<div v-if="props.row.status==='PE'">Pendiente</div>
			</div>
		</div>
		<div slot="id" slot-scope="props" class="text-center">
			<a :href="setUrl(`finance/payment-execute/pdf/${props.row.id}`)" target="_blank" 
			   class="btn btn-primary btn-xs btn-icon btn-action" title="Imprimir comprobante" data-toggle="tooltip">
				<i class="fa fa-print"></i>
			</a>
			<button @click="editForm(props.row.id)"
    				class="btn btn-warning btn-xs btn-icon btn-action"
    				title="Modificar registro" data-toggle="tooltip" type="button" disabled>
    			<i class="fa fa-edit"></i>
    		</button>
    		<button @click="deleteRecord(props.row.id, '/finance/payment-execute')"
					class="btn btn-danger btn-xs btn-icon btn-action"
					title="Eliminar registro" data-toggle="tooltip"
					type="button">
				<i class="fa fa-trash-o"></i>
			</button>
		</div>
	</v-client-table>
</template>

<script>
    export default {
        data() {
            return {
                records: [],
				columns: ['code', 'paid_at', 'receiver_name', 'observations', 'status', 'id']
            }
        },
		methods: {
			reset() {}
		},
        created() {
			this.table_options.headings = {
				'code': 'Código',
				'paid_at': 'Fecha del pago',
				'receiver_name': 'Proveedor / Beneficiario',
				'observations': 'Concepto',
				'status': 'Estatus',
				'id': 'Acción'
			};
			this.table_options.columnsClasses = {
				'code': 'col-md-2',
				'paid_at': 'col-md-2',
				'receiver_name': 'col-md-2',
				'observations': 'col-md-2',
				'status': 'col-md-2',
				'id': 'col-md-2'
			};
			this.table_options.sortable = ['code', 'paid_at', 'receiver_name', 'observations', 'status'];
			this.table_options.filterable = ['code', 'paid_at', 'receiver_name', 'observations', 'status'];
		},
        mounted() {
			this.initRecords(this.route_list, '');
		},
    }
</script>