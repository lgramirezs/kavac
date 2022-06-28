<template>
    <v-client-table :columns="columns" :data="records" :options="table_options">
		<div slot="id" slot-scope="props" class="text-center">
			<button @click="editForm(props.row.id)"
    				class="btn btn-warning btn-xs btn-icon btn-action"
    				title="Modificar registro" data-toggle="tooltip" type="button">
    			<i class="fa fa-edit"></i>
    		</button>
    		<button @click="deleteRecord(props.index, '')"
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
				columns: ['code', 'ordered_at', 'name_sourceable.full_name', 'concept', 'status', 'id']
            }
        },
		methods: {
			reset() {}
		},
        created() {
			this.table_options.headings = {
				'code': 'Código',
				'ordered_at': 'Fecha solicitud',
				'name_sourceable.full_name': 'Proveedor / Beneficiario',
				'concept': 'Concepto',
				'status': 'Estatus',
				'id': 'Acción'
			};
			this.table_options.columnsClasses = {
				'code': 'col-md-2',
				'ordered_at': 'col-md-2',
				'name_sourceable.full_name': 'col-md-2',
				'concept': 'col-md-2',
				'status': 'col-md-2',
				'id': 'col-md-2'
			};
			this.table_options.sortable = ['code', 'ordered_at', 'name_sourceable.full_name', 'concept', 'status'];
			this.table_options.filterable = ['code', 'ordered_at', 'name_sourceable.full_name', 'concept', 'status'];
		},
        mounted() {
			this.initRecords(this.route_list, '');
		},
    }
</script>