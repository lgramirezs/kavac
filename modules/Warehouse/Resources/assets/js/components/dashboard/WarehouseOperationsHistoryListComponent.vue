<template>
	<v-client-table :columns="columns" :data="records" :options="table_options">
		<div slot="id" slot-scope="props" class="text-center">
			<div slot="created_at" slot-scope="props">
            <span>
                {{ (props.row.created_at)? format_date(props.row.created_at):'N/A' }}
            </span>
        </div>
			<div class="d-inline-flex">
				<warehouse-operations-history-info
					route_list="/warehouse/dashboard/operations/info"
					:operation="props.row">>
				</warehouse-operations-history-info>
				<button @click="showReport(props.index, $event)" 
						class="btn btn-primary btn-xs btn-icon btn-action" 
						title="Abrir reporte de operación" data-toggle="tooltip" 
						type="button">
					<i class="fa fa-file-pdf-o"></i>
				</button>
			</div>
		</div>
	</v-client-table>
</template>

<script>
	export default {
		data() {
			return {
				record: {
					id: '',
					code: '',
					created_at: '',
					type_operation: '',
					description: ''
				},
				records: [],
				columns: ['created_at', 'description', 'id']
			}
		},
		created() {
			this.table_options.headings = {
				'created_at': 'Fecha de operación',
				'description': 'Descripción',
				'id': 'Acción'
			};
			this.table_options.sortable = ['created_at', 'description'];
			this.table_options.filterable = ['created_at', 'description'];
		},
		mounted () {
			this.initRecords(this.route_list, '');
		},
		methods: {
			/**
			 * Inicializa los datos del formulario
			 *
			 * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve | roldandvg@gmail.com>
			 */
			reset() {
				this.record = {
					id: '',
					code: '',
					created_at: '',
					type_operation: '',
					description: ''
				};
			},
			showReport(index, event) {
				const vm = this;
				vm.record = vm.records[index-1];

				var url = '/warehouse/dashboard/get-operation/' + vm.record.type_operation + '/' + vm.record.code;
				event.preventDefault();

				axios.get(url).then(response => {
					if (response.data.result == false)
						location.href = response.data.redirect;
					else if (typeof(response.data.redirect) !== "undefined") {
						//window.open(response.data.redirect, '_blank');
					}
				});
			}
		}
	};
</script>
