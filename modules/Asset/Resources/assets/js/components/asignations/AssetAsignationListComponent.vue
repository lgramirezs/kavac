<template>
	<div class="card-body">
		<v-client-table :columns="columns" :data="records" :options="table_options" ref="tableMax">
			<div slot="code" slot-scope="props" class="text-center">
				<span>
					{{ props.row.code }}
				</span>
			</div>
			<div slot="payroll_staff" slot-scope="props" class="text-center">
				<span>
					{{ (props.row.payroll_staff)?(props.row.payroll_staff.first_name + ' ' + props.row.payroll_staff.last_name):'N/A' }}
				</span>
			</div>
			<div slot="location_place" slot-scope="props" class="text-center">
				<span>
					{{ props.row.location_place }}
				</span>
			</div>
			<div slot="created" slot-scope="props" class="text-center">
				<span>
					{{ (props.row.created_at)? format_date(props.row.created_at):'N/A' }}
				</span>
			</div>
			<div slot="id" slot-scope="props" class="text-center">
				<div class="d-inline-flex">
					<asset-asignation-info
						:route_list="app_url + '/asset/asignations/vue-info/' + props.row.id">
					</asset-asignation-info>

					<button @click="editForm(props.row.id)"
							class="btn btn-warning btn-xs btn-icon btn-action"
							title="Modificar registro" data-toggle="tooltip" v-has-tooltip type="button">
						<i class="fa fa-edit"></i>
					</button>
					<button @click="deleteRecord(props.row.id, '')"
							class="btn btn-danger btn-xs btn-icon btn-action"
							title="Eliminar registro" data-toggle="tooltip" v-has-tooltip 
							type="button">
						<i class="fa fa-trash-o"></i>
					</button>
				</div>
			</div>
		</v-client-table>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				records: [],
				columns: ['code', 'payroll_staff', 'location_place','created', 'id']
			}
		},

		created() {
			this.table_options.headings = {
				'code': 'Código',
				'payroll_staff': 'Trabajador',
				'location_place': 'Lugar de ubicación',
				'created': 'Fecha de asignación',
				'id': 'Acción'
			};
			this.table_options.sortable = ['code', 'payroll_staff', 'location_place', 'created'];
			this.table_options.filterable = ['code', 'payroll_staff', 'location_place','created'];
			this.table_options.orderBy = { 'column': 'code'};
		},
		mounted () {
			this.readRecords(this.route_list);
		},
		methods: {
			/**
			 * Inicializa los datos del formulario
			 *
			 * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve | roldandvg@gmail.com>
			 */
			reset() {

			},
		}
	};
</script>
