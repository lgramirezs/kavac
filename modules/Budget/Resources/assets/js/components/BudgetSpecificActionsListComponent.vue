<template>
	<section>
		<v-client-table :columns="columns" :data="records" :options="table_options" ref="tableResults">
			<div slot="id" slot-scope="props" class="text-center">
				<button @click.prevent="setDetails('SpecificActionInfo', props.row.id, 'BudgetSpecificActionsInfo')"
							class="btn btn-info btn-xs btn-icon btn-action btn-tooltip"
							title="Ver registro" data-toggle="tooltip" data-placement="bottom" type="button">
						<i class="fa fa-eye"></i>
				</button>
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
			<div slot="specificable_type" slot-scope="props">
				<span v-if="props.row.specificable_type=='Modules\\Budget\\Models\\BudgetProject'">Proyecto</span>
				<span v-else>Acción Centralizada</span>
			</div>
			<div slot="active" slot-scope="props" class="text-center">
				<span v-if="props.row.active" class="text-success font-weight-bold">SI</span>
				<span v-else class="text-danger font-weight-bold">NO</span>
			</div>
		</v-client-table>
		<budget-specific-actions-info
            ref="SpecificActionInfo">
        </budget-specific-actions-info>
	</section>
</template>

<script>
	export default {
		data() {
			return {
				records: [],
				columns: ['code', 'name', 'specificable_type', 'active', 'id']
			}
		},
		created() {
			this.table_options.headings = {
				'code': 'Código',
				'name': 'Acción Específica',
				'specificable_type': 'Proyecto / Acc. Centralizada',
				'active': 'Activa',
				'id': 'Acción'
			};
			this.table_options.sortable = ['code', 'name', 'specificable_type'];
			this.table_options.filterable = ['code', 'name', 'specificable_type'];
			this.table_options.columnsClasses = {
				'code': 'col-md-2',
				'name': 'col-md-4',
				'specificable_type': 'col-md-3',
				'active': 'col-md-1',
				'id': 'col-md-2'
			};
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
                    vm.$refs[ref].record = vm.$refs.tableResults.data.filter(r => {
                        return r.id === id;
                    })[0];
                }
                vm.$refs[ref].id = id;

                $(`#${modal}`).modal('show');
			},
		}
	};
</script>
