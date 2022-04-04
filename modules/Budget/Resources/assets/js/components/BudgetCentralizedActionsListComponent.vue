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
		
                      
           	<budget-centralized-actions-info
    					:route_list="app_url + '/budget/detail-vue-centralized-actions/' + props.row.id">
    	    </budget-centralized-actions-info>
		</div>
		<div slot="active" slot-scope="props" class="text-center">
			<span v-if="props.row.active">SI</span>
			<span v-else>NO</span>
		</div>
	</v-client-table>
</template>

<script>
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
				'name': 'col-md-5',
				'active': 'col-md-1',
				'id': 'col-md-2'
			};
		},
		mounted() {
			this.initRecords(this.route_list, '');
		},
		methods: {
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
			console.log(id);
			
            await axios.get('/budget/detail-vue-centralized-actions/'+ id).then(response => {
					console.log(response);
				
                if (response.data.result) {
                    let buget = response.data.buget;
					let cargo = response.data.cargo;
					let name = "";
							console.log(this.buget);
	
               //    <div class="row">
            //                             <div class="col-md-6 text-left">
            //                                 <h5>Código</h5>
            //                                 <div>${buget.code}</div>
            //                             </div>
            //                             <div class="col-md-6 text-left">
            //                                 <h5>Acción Centralizada</h5>
            //                                 <div>${buget.name}</div>
            //                             </div>
            //                         </div>
			// 						   <div class="row">
            //                             <div class="col-md-6 text-left">
            //                                 <h5>Responsable</h5>
            //                                 <div>${cargo.first_name}, ${cargo.last_name}</div>
            //                             </div>
            //                             <div class="col-md-6 text-left">
            //                                 <h5>Cargo</h5>
            //                                 <div>${cargo.payroll_employment.payroll_position.name}</div>
            //                             </div>
            //                         </div>
            
             
                
                }
            }).catch(error => {
               
            });
            vm.loading = false;
        }
		}
	};
</script>
