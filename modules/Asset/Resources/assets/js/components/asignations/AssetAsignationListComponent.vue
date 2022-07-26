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
			<div slot="state" slot-scope="props" class="text-center">
				<span>
					{{ (props.row.state)}}
				</span>
			</div>
			<div slot="id" slot-scope="props" class="text-center">
				<div class="d-inline-flex">
					<asset-asignation-info
						:route_list="app_url + '/asset/asignations/vue-info/' + props.row.id">
					</asset-asignation-info>
					<asset-asignation-deliver-equipment
						:index="props.row.id"
						:route_list="app_url + '/asset/asignations/vue-info/' + props.row.id"
						:state="props.row.state">
					</asset-asignation-deliver-equipment>
					<button @click="editForm(props.row.id)"
							class="btn btn-warning btn-xs btn-icon btn-action"
							:disabled="(props.row.state == 'Asignado')?false:true"
							title="Modificar registro" data-toggle="tooltip" v-has-tooltip type="button">
						<i class="fa fa-edit"></i>
					</button>
					<button 
							class="btn btn-primary btn-xs btn-icon btn-action"
							title="Generar acta" data-toggle="tooltip"
							type="button" v-has-tooltip>
							<i class="fa fa-file-pdf-o"></i>
					</button>
					<!-- :href="url+'pdf/'+props.row.id" -->
					<button
							class="btn btn-primary btn-xs btn-icon" 
							title="Imprimir acta" 
							data-toggle="tooltip" v-has-tooltip target="_blank">
							<i class="fa fa-print" style="text-align: center;"></i>
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
				errors: [],
				columns: ['code', 'payroll_staff', 'location_place','created', 'state', 'id']
			}
		},

		created() {
			this.table_options.headings = {
				'code': 'Código',
				'payroll_staff': 'Trabajador',
				'location_place': 'Lugar de ubicación',
				'created': 'Fecha de asignación',
				'state': 'Estado de la asignación',
				'id': 'Acción'
			};
			this.table_options.sortable = ['code', 'payroll_staff', 'location_place', 'created', 'state'];
			this.table_options.filterable = ['code', 'payroll_staff', 'location_place','created', 'state'];
			//this.table_options.orderBy = { 'column': 'code'};
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
                            vm.showMessage('destroy');
							location.href = response.data.redirect;
                        }).catch(error => {
                            vm.logs('mixins.js', 498, error, 'deleteRecord');
                        });
                    }
                }
            });
        },
		},
	};
</script>
