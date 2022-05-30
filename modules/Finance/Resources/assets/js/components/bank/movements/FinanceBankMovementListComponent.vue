<template>
    <v-client-table :columns="columns" :data="records" :options="table_options" ref="tableResults">
        <div slot="id" slot-scope="props" class="text-center">
            <div class="d-inline-flex">
                <button @click="editForm(props.row.id)"
                        class="btn btn-warning btn-xs btn-icon btn-action"
                        title="Modificar registro" data-toggle="tooltip" type="button">
                    <i class="fa fa-edit"></i>
                </button>
                <button @click="deleteRecord(props.row.id, '')"
                        class="btn btn-danger btn-xs btn-icon btn-action"
                        title="Eliminar registro" data-toggle="tooltip" type="button">
                    <i class="fa fa-trash-o"></i>
                </button>
            </div>
        </div>
    </v-client-table>
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
        }
    };
</script>