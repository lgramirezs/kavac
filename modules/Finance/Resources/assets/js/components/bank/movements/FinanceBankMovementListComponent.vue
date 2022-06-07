<template>
    <section>
        <v-client-table :columns="columns" :data="records" :options="table_options" ref="tableResults">
            <div slot="id" slot-scope="props" class="text-center">
                <div class="d-inline-flex">
                    <button @click.prevent="setDetails('BankMovementInfo', props.row.id, 'FinanceBankMovementInfo')"
                            class="btn btn-info btn-xs btn-icon btn-action btn-tooltip"
                            title="Ver registro" data-toggle="tooltip" data-placement="bottom" type="button">
                        <i class="fa fa-eye"></i>
                    </button>
                    <button @click="editForm(props.row.id)"
                            class="btn btn-warning btn-xs btn-icon btn-action"
                            title="Modificar registro" data-toggle="tooltip" type="button">
                        <i class="fa fa-edit"></i>
                    </button>
                    <button @click="deleteRecord(props.row.id)"
                            class="btn btn-danger btn-xs btn-icon btn-action"
                            title="Eliminar registro" data-toggle="tooltip" type="button">
                        <i class="fa fa-trash-o"></i>
                    </button>
                </div>
            </div>
        </v-client-table>
        <finance-bank-movements-info
            ref="BankMovementInfo">
        </finance-bank-movements-info>
    </section>
</template>

<script>
    export default {
        data() {
            return {
                records: [],
                columns: ['code', 'payment_date', 'transaction_type', 'concept', 'amount', 'id']
            }
        },
        created() {
            this.table_options.headings = {
                'code': 'Código',
                'payment_date': 'Fecha de pago',
                'transaction_type': 'Tipo de transacción',
                'concept': 'Concepto',
                'amount': 'Monto',
                'id': 'Acción'
            };
            this.table_options.sortable = ['code', 'payment_date', 'transaction_type', 'concept', 'amount'];
            this.table_options.filterable = ['code', 'payment_date', 'transaction_type', 'concept', 'amount'];
            this.table_options.columnsClasses = {
                'code': 'col-md-2',
                'payment_date': 'col-md-2',
                'transaction_type': 'col-md-2',
                'concept': 'col-md-3',
                'amount': 'col-md-2',
                'id': 'col-md-1'
            };
        },
        mounted () {
            this.initRecords(this.route_list, '');
        },
        methods: {
            /**
             * Inicializa los datos del formulario
             *
             * @author Daniel Contreras <dcontreras@cenditel.gob.ve>
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