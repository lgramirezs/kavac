<template>
    <section>
        <v-client-table :columns="columns" :data="records" :options="table_options" ref="tableResults">
            <div slot="description" slot-scope="props" class="text-justify">
                <div v-html="props.row.description"></div>
            </div>
            <div slot="compromised_at" slot-scope="props" class="text-center">
                {{  new Date(props.row.compromised_at).toLocaleDateString('en-GB', {timeZone: 'UTC'}) }}
            </div>
            <div slot="code" slot-scope="props" class="text-center">
                {{ props.row.code }}
            </div>
            <div slot="id" slot-scope="props" class="text-center">
                <button @click.prevent="setDetails('BudgetCompromise', props.row.id, 'BudgetCompromiseInfo')"
                    class="btn btn-info btn-xs btn-icon btn-action btn-tooltip"
                    title="Ver registro" data-toggle="tooltip" data-placement="bottom" type="button">
                    <i class="fa fa-eye"></i>
                </button>
                <button @click="editForm(props.row.id)"
                        class="btn btn-warning btn-xs btn-icon btn-action"
                        title="Modificar registro" data-toggle="tooltip" type="button">
                    <i class="fa fa-edit"></i>
                </button>
                <button @click="deleteRecord(props.row.id, '')"
                        class="btn btn-danger btn-xs btn-icon btn-action"
                        title="Eliminar registro" data-toggle="tooltip"
                        type="button">
                    <i class="fa fa-trash-o"></i>
                </button>
            </div>
            <div slot="document_status" slot-scope="props" class="text-center">
                <span>{{ props.row.document_status.name }}</span>
            </div>
        </v-client-table>
        <budget-compromise-info
            ref="BudgetCompromise">
        </budget-compromise-info>
    </section>
</template>

<script>
    export default {
        data() {
            return {
                records: [],
                columns: ['compromised_at', 'code', 'description', 'document_status', 'id']
            }
        },
        created() {
            this.table_options.headings = {
                'compromised_at': 'Fecha',
                'code': 'Código',
                'description': 'Descripción',
                'document_status': 'Estatus',
                'id': 'Acción'
            };
            this.table_options.sortable = ['code', 'compromised_at', 'description'];
            this.table_options.filterable = ['code', 'compromised_at', 'description'];
            this.table_options.columnsClasses = {
                'compromised_at': 'col-md-2',
                'code': 'col-md-2',
                'description': 'col-md-4',
                'document_status': 'col-md-2',
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
