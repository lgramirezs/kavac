<template>
	<v-client-table :columns="columns" :data="records" :options="table_options">
		<div slot="city_id" slot-scope="props">
			{{ props.row.city.name }}
		</div>
		<div slot="id" slot-scope="props" class="text-center">
			<button @click="editForm(props.row.id)"
    				class="btn btn-warning btn-xs btn-icon btn-action" data-placement="bottom"
    				title="Modificar registro" data-toggle="tooltip" type="button">
    			<i class="fa fa-edit"></i>
    		</button>
    		<button @click="deleteRecord(props.row.id, '')"
					class="btn btn-danger btn-xs btn-icon btn-action"
					title="Eliminar registro" data-toggle="tooltip" data-placement="bottom"
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
				columns: ['rif', 'name', 'city_id', 'id']
			}
		},
		created() {
			this.table_options.headings = {
				'rif': 'R.I.F.',
				'name': 'Nombre',
				'city_id': 'Ciudad',
				'id': 'Acción'
			};
			this.table_options.sortable = ['rif', 'name', 'city_id'];
			this.table_options.filterable = ['rif', 'name', 'city_id'];
		},
		mounted() {
			this.initRecords(this.route_list, '');
		},
		methods: {
			/**
			 * Inicializa los datos del formulario
			 *
			 * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
			 */
			reset() {
				// 
			},

			/**
			 * Redirecciona al formulario de actualización de datos
			 *
			 * @author Juan Rosas <jrosas@cenditel.gob.ve> | <juan.rosasr01@gmail.com>
			 *
			 * @param  {integer} id Identificador del registro a actualizar
			 */
			editForm(id){
				const vm = this;
				let route = vm.route_edit.indexOf("{id}") >= 0
							? vm.route_edit.replace("{id}", id)
							: vm.route_edit + '/' + id;

				location.href = `${window.app_url}${route}`;
			}
		}
	};
</script>
