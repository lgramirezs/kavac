<template>
	<section>
		<v-client-table :columns="columns" :data="records" :options="table_options" ref="tableResultsTransfer">
			<div slot="institution" slot-scope="props">
				{{ props.row.institution.acronym }}
			</div>
			<div slot="approved_at" slot-scope="props">
				{{ format_date(props.row.approved_at, 'DD/MM/YYYY') }}
			</div>
			<div slot="id" slot-scope="props" class="text-center">
				<button
					@click.prevent="setDetails('BudgetTransferListModel', props.row.id, 'BudgetTransferListModel')"
					class="btn btn-info btn-xs btn-icon btn-action btn-tooltip"
					title="Ver registro" data-toggle="tooltip" data-placement="bottom" type="button">
					<i class="fa fa-eye"></i>
				</button>
				<button @click="editForm(props.row.id)" data-placement="bottom"
						class="btn btn-warning btn-xs btn-icon"
						title="Modificar registro" data-toggle="tooltip" type="button">
					<i class="fa fa-edit"></i>
				</button>
				<button @click="deleteRecord(props.index, '')" data-placement="bottom"
						class="btn btn-danger btn-xs btn-icon"
						title="Eliminar registro" data-toggle="tooltip"
						type="button">
					<i class="fa fa-trash-o"></i>
				</button>
			</div>
		</v-client-table>
			<budget-transfer-modal
				ref="BudgetTransferListModel">
			</budget-transfer-modal>
	</section>
</template>

<script>
	export default {
		data() {
			return {
				records: [],
				columns: ['code', 'approved_at', 'description', 'document', 'institution', 'id']
			}
		},
		created() {
			this.table_options.headings = {
				'approved_at': 'Fecha',
				'code': 'Código',
				'description': 'Descripción',
				'document': 'Documento',
				'institution': 'Institución',
				'id': 'Acción'
			};
			this.table_options.sortable = ['code', 'approved_at', 'description', 'document', 'institution'];
			this.table_options.filterable = ['code', 'approved_at', 'description', 'document', 'institution'];
			this.table_options.columnsClasses = {
				'approved_at': 'col-md-1',
				'code': 'col-md-1',
				'description': 'col-md-4',
				'document': 'col-md-2',
				'institution': 'col-md-2',
				'id': 'col-md-2'
			};
		},
		mounted() {
			this.initRecords(this.route_list, '');
		},
		methods: {
			/**
			 * Inicializa los daudgetSpecificDattos del formulario
			 *tableResults
			 * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
			 */
			reset() {

			},
			/**
	         * Método que permite dar formato a una fecha
	         *
	         * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
	         * @author Daniel Contreras <dcontreras@cenditel.gob.ve> | <exodiadaniel@gmail.com>
	         *
	         * @param  {string} value  Fecha ser formateada
	         * @param  {string} format Formato de la fecha
	         *
	         * @return {string}       Fecha con el formato establecido
	         */
			format_date: function(value, format = 'DD/MM/YYYY') {
	            return moment.utc(value).format(format);
	        },
		
			/**
             * Método que establece los datos del registro seleccionado para el cual se desea mostrar detalles
             *
             * @method    setDetails
             *
             * @author     Daniel Contreras <dcontreras@cenditel.gob.ve>
             *
             * @param     string   ref       Identificador del componente
             * @param     integer  id        Identificador del registro seleccionado
             * @param     object  var_list  Objeto con las variables y valores a asignar en las variables del componente
             */
              setDetails(ref, id, modal ,var_list = null) {
                const vm = this;
                if (var_list) {
                    for(var i in var_list){
                        vm.$refs[ref][i] = var_list[i];
                    }
                }else{
                    vm.$refs[ref].record = vm.$refs.tableResultsTransfer.data.filter(r => {
                        return r.id === id;
                    })[0];
                }    
                vm.$refs[ref].id = id;
           
               $(`#${modal}`).modal('show');
			},
		}
	};
</script>