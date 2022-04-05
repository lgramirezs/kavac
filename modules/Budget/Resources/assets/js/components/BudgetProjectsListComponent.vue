<template>
    <div>
        <v-client-table :columns="columns" :data="records" :options="table_options">
            <div slot="id" slot-scope="props" class="text-center">
                <button
                    @click="show_info(props.row.id)"
                    class="btn btn-info btn-xs btn-icon btn-action btn-tooltip"
                    title="Ver registro"
                    data-toggle="tooltip"
                    type="button">
                    <i class="fa fa-eye"></i>
                </button>
                <button
                    @click="editForm(props.row.id)"
                    class="btn btn-warning btn-xs btn-icon btn-action"
                    title="Modificar registro"
                    data-toggle="tooltip"
                    type="button">
                    <i class="fa fa-edit"></i>
                </button>
                <button
                    @click="deleteRecord(props.index, '')"
                    class="btn btn-danger btn-xs btn-icon btn-action"
                    title="Eliminar registro"
                    data-toggle="tooltip"
                    type="button">
                    <i class="fa fa-trash-o"></i>
                </button>
            </div>
            <div slot="active" slot-scope="props" class="text-center">
                <span v-if="props.row.active">SI</span>
                <span v-else>NO</span>
            </div>
        </v-client-table>
        <!-- Modal -->
        <div class="modal fade" tabindex="-1" role="dialog" id="show_employment">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h6>
                            Información detallada del Proyecto
                        </h6>
                    </div>
                    <div class="modal-body">
                        XXXXXXXXX
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
    </div>
</template>

<script>
    export default {
        data() {
            return {
                records: [],
                columns: ['code', 'name', 'active', 'id']
            }
        },
        created() {
            this.table_options.headings = {
                'code': 'Código',
                'name': 'Proyecto',
                'active': 'Activo',
                'id': 'Acción'
            };
            this.table_options.sortable = ['code', 'name'];
            this.table_options.filterable = ['code', 'name'];
            this.table_options.columnsClasses = {
                'code': 'col-md-2',
                'name': 'col-md-6',
                'active': 'col-md-2',
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
             * Método que abre el modal, realiza la consulta y pasa los datos.
             */
            show_info(id) {
                console.log("Entró aquí");
                /*
                axios.get(`${window.app_url}/payroll/employments/${id}`).then(response => {
                    this.record = response.data.record;
                    $('#payroll_staff').val(this.record.payroll_staff.first_name + ' ' + this.record.payroll_staff.last_name);
                    $('#start_date_apn').val(this.record.start_date_apn);
                    $('#start_date').val(this.record.start_date);
                    $('#end_date').val(this.record.end_date);
                    (this.record.active) ? $('#active').bootstrapSwitch('state', true) : $('#active').bootstrapSwitch('state', false);
                    $('#payroll_inactivity_type').val( (this.record.payroll_inactivity_type) ? this.record.payroll_inactivity_type.name : ' ' );
                    $('#institution_email').val(this.record.institution_email);
                    $('#function_description').val(this.record.function_description);
                    $('#payroll_position_type').val(this.record.payroll_position_type.name);
                    $('#payroll_position').val(this.record.payroll_position.name);
                    $('#payroll_staff_type').val(this.record.payroll_staff_type.name);
                    $('#institution').val(this.record.department.institution.name);
                    $('#department').val(this.record.department.name);
                    $('#payroll_contract_type').val(this.record.payroll_contract_type.name);
                });
                */
                $('#show_employment').modal('show');
            }
        }
    };
</script>
