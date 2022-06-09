	<template>
	<v-client-table :columns="columns" :data="records" :options="table_options">
		<div slot="id" slot-scope="props" class="text-center">
				<div class="d-inline-flex">
			<budget-centralized-actions-info
    				:id="props.row.id"	:route_list="app_url + '/budget/detail-vue-centralized-actions/' + props.row.id">
    	    </budget-centralized-actions-info>		
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
		</div>
		<div slot="active" slot-scope="props" class="text-center">
			<span v-if="props.row.active">SI</span>
			<span v-else>NO</span>
		</div>
	</v-client-table>
</template>

<script>
import { format } from 'path';

	export default {
		data() {
			return {
				records: [],
				columns: ['custom_date', 'code', 'name', 'active', 'id']
			}
		},
		created() {
			this.table_options.headings = {
				'custom_date': 'Fecha',
				'code': 'Código',
				'name': 'Acción Centralizada',
				'active': 'Activa',
				'id': 'Acción'
			};
			this.table_options.sortable = ['custom_date', 'code', 'name'];
			this.table_options.filterable = ['custom_date', 'code', 'name'];
			this.table_options.columnsClasses = {
				'custom_date': 'col-md-2',
				'code': 'col-md-2',
				'name': 'col-md-4',
				'active': 'col-md-1',
				'id': 'col-md-3'
			};
		},
		mounted() {
			this.initRecords();	
		},
		methods: {
			initRecords() 
			{ 
            const vm = this;
            let url = this.setUrl(this.route_list);

            axios.get(url).then(response => {
                if (typeof(response.data.records) !== "undefined") {
                    vm.records = response.data.records;
					vm.records.forEach(element => {
						element.custom_date = this.format_date(element.custom_date);
					});
                }
                if ($("#" + modal_id).length) {
                    $("#" + modal_id).modal('show');
                }
            }).catch(error => {
                if (typeof(error.response) !== "undefined") {
                    if (error.response.status == 403) {
                        vm.showMessage(
                            'custom', 'Acceso Denegado', 'danger', 'screen-error', error.response.data.message
                        );
                    }
                    else {
                        vm.logs('resources/js/all.js', 343, error, 'initRecords');
                    }
                }
            });
			},
			
			/**
			 * Inicializa datos del formulario
			 *
			 * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
			 */
			reset() {

			},
			 /**
         * Muestra los detalles de un registro seleccionado
         *
         * @author    
         *
         * @param     {string}    id    Identificador del registro para el cual se desea mostrar los detalles
         */
        async details(id) {
            const vm = this;
            vm.loading = true;

			
            await axios.get('/budget/detail-vue-centralized-actions/'+ id).then(response => {
				
				
                if (response.data.result) {
                    let buget = response.data.buget;
					let cargo = response.data.cargo;
					let name = "";
				
	
             
                
                }
            }).catch(error => {
               
            });
            vm.loading = false;
        }
		}
	};
</script>
