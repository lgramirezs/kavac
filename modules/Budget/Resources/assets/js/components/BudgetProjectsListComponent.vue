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
            <div class="modal-dialog modal-xl" role="document">
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
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Institución</label>
                                    <input type="text" data-toggle="tooltip"
                                        class="form-control input-sm"
                                        disabled="true" id="institution">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Dependencia</label>
                                    <input type="text" data-toggle="tooltip"
                                        class="form-control input-sm"
                                        disabled="true" id="department">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Responsable</label>
                                    <input type="text" data-toggle="tooltip"
                                        class="form-control input-sm"
                                        disabled="true" id="responsable">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Cargo del responsable</label>
                                    <input type="text" data-toggle="tooltip"
                                        class="form-control input-sm"
                                        disabled="true" id="payroll_position">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Código</label>
                                    <input type="text" data-toggle="tooltip"
                                        class="form-control input-sm"
                                        disabled="true" id="code">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Código ONAPRE</label>
                                    <input type="text" data-toggle="tooltip"
                                        class="form-control input-sm"
                                        disabled="true" id="onapre_code">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Nombre</label>
                                    <input type="text" data-toggle="tooltip"
                                        class="form-control input-sm"
                                        disabled="true" id="name">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Activo</label>
                                    <input type="text" data-toggle="tooltip"
                                        class="form-control input-sm"
                                        disabled="true" id="active">
                                </div>
                            </div>
                        </div>
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
                axios.get(`${window.app_url}/budget/projects/get-detail-project/${id}`)
                .then(response => {
                    this.record = response.data;
                    $('#name').val(this.record.project.name);
                    $('#institution').val(this.record.cargo.payroll_employment.department.institution.name);
                    $('#department').val(this.record.cargo.payroll_employment.department.name);
                    $('#responsable').val(this.record.cargo.first_name + ' ' + this.record.cargo.last_name);
                    $('#payroll_position').val(this.record.cargo.payroll_employment.payroll_position.name);
                    $('#code').val(this.record.project.code);
                    $('#onapre_code').val(this.record.project.onapre_code);
                    $('#active').val((this.record.project.active === true) ? 'Sí' : 'No');
                });
                $('#show_employment').modal('show');
            }
        }
    };
</script>
