<template>
    <div class="col-xs-2 text-center">
        <a class="btn-simplex btn-simplex-md btn-simplex-primary"
            href="#" title="Registros de archivos de conciliación bancaria"
            data-toggle="tooltip"
            @click="addRecord('add_bank_reconciliation_file', '/finance/banks', $event)"
        >
            <i class="icofont icofont-files ico-3x"></i>
            <span>Archivos de conciliación</span>
        </a>
        <div class="modal fade text-left" tabindex="-1" role="dialog"
            id="add_bank_reconciliation_file">
            <div class="modal-dialog vue-crud" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h6>
                            <i class="icofont icofont-bank-alt inline-block"></i>
                            Configuración de archivos de conciliación bancaria
                        </h6>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger" v-if="errors.length > 0">
                            <div class="alert-icon">
                                <i class="now-ui-icons objects_support-17"></i>
                            </div>
                            <strong>Cuidado!</strong> Debe verificar los siguientes errores antes de continuar:
                            <button type="button" class="close" data-dismiss="alert"
                                aria-label="Close">
                                <span aria-hidden="true">
                                    <i class="now-ui-icons ui-1_simple-remove"></i>
                                </span>
                            </button>
                            <ul>
                                <li v-for="(error, index) in errors"
                                    :key="index">{{ error }}
                                </li>
                            </ul>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group is-required">
                                    <label>Banco:</label>
                                    <select2 :options="banks" v-model="record.bank_id"></select2>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h6 class="card-title">
                            Lectura de contenido:
                        </h6>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Leer línea de inicio:</label>
                                <br>
                                <div class="pretty p-switch p-fill p-bigger p-toggle">
                                    <input type="checkbox" data-toggle="tooltip"
                                        title="Indique si el campo está activo"
                                        v-model="record.read_start_line">
                                    <div class="state p-off">
                                        <label></label>
                                    </div>
                                    <div class="state p-on p-success">
                                        <label></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label>Leer línea final:</label>
                                <br>
                                <div class="pretty p-switch p-fill p-bigger p-toggle">
                                    <input type="checkbox" data-toggle="tooltip"
                                        title="Indique si el campo está activo"
                                        v-model="record.read_end_line">
                                    <div class="state p-off">
                                        <label></label>
                                    </div>
                                    <div class="state p-on p-success">
                                        <label></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h6 class="card-title">
                            Posición de las columnas:
                        </h6>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group is-required">
                                    <label>Referencia:</label>
                                    <select2
                                        :options="lines"
                                        v-model="record.position_reference_column"
                                    >
                                    </select2>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group is-required">
                                    <label>Fecha:</label>
                                    <select2
                                        :options="lines"
                                        v-model="record.position_date_column"
                                    >
                                    </select2>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group is-required">
                                    <label>Monto débito:</label>
                                    <select2
                                        :options="lines"
                                        v-model="record.position_debit_amount_column"
                                    >
                                    </select2>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group is-required">
                                    <label>Monto cŕedito:</label>
                                    <select2
                                        :options="lines"
                                        v-model="record.position_credit_amount_column"
                                    >
                                    </select2>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group is-required">
                                    <label>Descripción:</label>
                                    <select2
                                        :options="lines"
                                        v-model="record.position_description_column"
                                    >
                                    </select2>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h6 class="card-title">
                            Formatos de lectura:
                        </h6>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group is-required">
                                    <label>Columnas separadas por:</label>
                                    <select2 :options="separated_list" v-model="record.separated_by"></select2>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group is-required">
                                    <label>Formato de fecha:</label>
                                    <select2 :options="date_formats" v-model="record.date_format"></select2>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h6 class="card-title">
                            Formatos de monto:
                        </h6>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group is-required">
                                    <label>Separador de miles:</label>
                                    <select2 :options="banks" v-model="record.finance_bank_id"></select2>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group is-required">
                                    <label>Separador de decimales:</label>
                                    <select2 :options="banks" v-model="record.finance_bank_id"></select2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="form-group">
                            <button type="button"
                                class="btn btn-default btn-sm btn-round btn-modal-close"
                                @click="clearFilters" data-dismiss="modal">
                                Cerrar
                            </button>
                            <button type="button"
                                class="btn btn-warning btn-sm btn-round btn-modal btn-modal-clear"
                                @click="reset()">
                                Cancelar
                            </button>
                            <button type="button" @click="createRecord()"
                                class="btn btn-primary btn-sm btn-round btn-modal-save">
                                Guardar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                record: {
                    bank_id: '',
                    read_start_line: false,
                    read_end_line: false,
                    position_reference_column: '',
                    position_date_column: '',
                    position_debit_amount_column: '',
                    position_credit_amount_column: '',
                    position_description_column: '',
                    separated_by: '',
                    date_format: '',
                },
                errors: [],
                records: [],
                banks: [],
                lines: [
                    { "id": "", "text": "Seleccione..." },
                    { "id": 1, "text": "1" },
                    { "id": 2, "text": "2" },
                    { "id": 3, "text": "3" },
                    { "id": 4, "text": "4" },
                    { "id": 5, "text": "5" },
                    { "id": 6, "text": "6" },
                    { "id": 7, "text": "7" },
                    { "id": 8, "text": "8" },
                    { "id": 9, "text": "9" },
                    { "id": 10, "text": "10" },
                ],
                separated_list : [
                    { "id": "", "text": "Seleccione..." },
                    { "id": 1, "text": "Tabulador" },
                    { "id": 2, "text": "Punto y Coma" },
                    { "id": 3, "text": "Coma" },
                ],
                date_formats : [
                    { "id": "", "text": "Seleccione..." },
                    { "id": 1, "text": "DD-MM-YYYY" },
                    { "id": 2, "text": "YYYY-MM-DD" },
                ],
            }
        },
        methods: {
            /**
             * Método que limpia todos los datos del formulario.
             *
             * @author  Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
             */
            reset() {
                this.record = {
                    bank_id: '',
                    read_start_line: false,
                    read_end_line: false,
                    position_reference_column: '',
                    position_date_column: '',
                    position_debit_amount_column: '',
                    position_credit_amount_column: '',
                    position_description_column: '',
                    separated_by: '',
                    date_format: '',
                };
            },

            createRecord () {
                // console.log("Entró aquí!");
                console.log(this.record.bank_id);
                console.log(this.record.read_start_line);
                console.log(this.record.read_end_line);
                console.log(this.record.position_reference_column);
                console.log(this.record.position_date_column);
                console.log(this.record.position_debit_amount_column);
                console.log(this.record.position_credit_amount_column);
                console.log(this.record.position_description_column);
            }
        },
        created() {
            this.getBanks();
        },
        mounted() {
            const vm = this;
        },
    };
</script>
