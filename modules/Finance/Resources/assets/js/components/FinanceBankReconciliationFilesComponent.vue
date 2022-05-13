<template>
    <div class="col-xs-2 text-center">
        <a class="btn-simplex btn-simplex-md btn-simplex-primary"
            href="#" title="Registros de archivos de conciliación bancaria"
            data-toggle="tooltip"
            @click="addRecord('add_bank_reconciliation_file', '/finance/setting-bank-reconciliation-files', $event)"
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
                                    <select2 :options="thousands_decimal_separated_list" v-model="record.thousands_separator"></select2>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group is-required">
                                    <label>Separador de decimales:</label>
                                    <select2 :options="thousands_decimal_separated_list" v-model="record.decimal_separator"></select2>
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
                            <button type="button" @click="createRecord('finance/setting-bank-reconciliation-files')"
                                class="btn btn-primary btn-sm btn-round btn-modal-save">
                                Guardar
                            </button>
                        </div>
                    </div>
                    <div class="modal-body modal-table">
                        <v-client-table :columns="columns" :data="records" :options="table_options">
                            <a slot="bank_id" slot-scope="props" target="_blank"
                                v-if="props.row.bank_id">
                                {{ props.row.bank_id }}
                            </a>
                            <a slot="read_start_line" slot-scope="props" target="_blank"
                                v-if="props.row.read_start_line">
                                {{ props.row.read_start_line }}
                            </a>
                            <a slot="read_end_line" slot-scope="props" target="_blank"
                                v-if="props.row.read_end_line">
                                {{ props.row.read_end_line }}
                            </a>
                            <a slot="position_reference_column" slot-scope="props" target="_blank"
                                v-if="props.row.position_reference_column">
                                {{ props.row.position_reference_column }}
                            </a>
                            <a slot="position_date_column" slot-scope="props" target="_blank"
                                v-if="props.row.position_date_column">
                                {{ props.row.position_date_column }}
                            </a>
                            <a slot="position_debit_amount_column" slot-scope="props" target="_blank"
                                v-if="props.row.position_debit_amount_column">
                                {{ props.row.position_debit_amount_column }}
                            </a>
                            <a slot="position_credit_amount_column" slot-scope="props" target="_blank"
                                v-if="props.row.position_credit_amount_column">
                                {{ props.row.position_credit_amount_column }}
                            </a>
                            <a slot="position_description_column" slot-scope="props" target="_blank"
                                v-if="props.row.position_description_column">
                                {{ props.row.position_description_column }}
                            </a>
                            <a slot="separated_by" slot-scope="props" target="_blank"
                                v-if="props.row.separated_by">
                                {{ props.row.separated_by }}
                            </a>
                            <a slot="date_format" slot-scope="props" target="_blank"
                                v-if="props.row.date_format">
                                {{ props.row.date_format }}
                            </a>
                            <a slot="thousands_separator" slot-scope="props" target="_blank"
                                v-if="props.row.thousands_separator">
                                {{ props.row.thousands_separator }}
                            </a>
                            <a slot="decimal_separator" slot-scope="props" target="_blank"
                                v-if="props.row.decimal_separator">
                                {{ props.row.decimal_separator }}
                            </a>
                            <div slot="id" slot-scope="props" class="text-center">
                                <button @click="initUpdate(props.row.id, $event)"
                                    class="btn btn-warning btn-xs btn-icon btn-action btn-tooltip"
                                    title="Modificar registro" data-toggle="tooltip" type="button">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button @click="deleteRecord(props.row.id, '/finance/banks')"
                                    class="btn btn-danger btn-xs btn-icon btn-action btn-tooltip"
                                    title="Eliminar registro" data-toggle="tooltip"
                                    type="button">
                                    <i class="fa fa-trash-o"></i>
                                </button>
                            </div>
                        </v-client-table>
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
                    thousands_separator: '',
                    decimal_separator: '',
                },
                errors: [],
                records: [],
                columns: [
                    'bank_id',
                    'read_start_line',
                    'read_end_line',
                    'position_reference_column',
                    'position_date_column',
                    'position_debit_amount_column',
                    'position_credit_amount_column',
                    'position_description_column',
                    'separated_by',
                    'date_format',
                    'thousands_separator',
                    'decimal_separator',
                    'id'
                ],
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
                    { "id": "Tabulador", "text": "Tabulador" },
                    { "id": "Punto y Coma", "text": "Punto y Coma" },
                    { "id": "Coma", "text": "Coma" },
                ],
                thousands_decimal_separated_list: [
                    { "id": "", "text": "Seleccione..." },
                    { "id": "Punto", "text": "Punto" },
                    { "id": "Coma", "text": "Coma" },
                ],
                date_formats : [
                    { "id": "", "text": "Seleccione..." },
                    { "id": "DD-MM-YYYY", "text": "DD-MM-YYYY" },
                    { "id": "YYYY-MM-DD", "text": "YYYY-MM-DD" },
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
                    thousands_separator: '',
                    decimal_separator: '',
                };
            },
        },
        created() {
            this.getBanks();
            this.table_options.headings = {
                'bank_id': 'Banco',
                'read_start_line': 'Leer línea de inicio',
                'read_end_line': 'Leer línea final',
                'position_reference_column': 'Referencia',
                'position_date_column': 'Fecha',
                'position_debit_amount_column': 'Monto débito',
                'position_credit_amount_column': 'Monto crédito',
                'position_description_column': 'Descripción',
                'separated_by': 'Columnas separadas por',
                'date_format': 'Formato de fecha',
                'thousands_separator': 'Separador de miles',
                'decimal_separator': 'Separador de decimales',
                'id': 'Acción'
            };
            this.table_options.sortable = [
                'bank_id',
                'read_start_line',
                'read_end_line',
                'position_reference_column',
                'position_date_column',
                'position_debit_amount_column',
                'position_credit_amount_column',
                'position_description_column',
                'separated_by',
                'date_format',
                'thousands_separator',
                'decimal_separator'
            ];
            this.table_options.filterable = [
                'bank_id',
                'read_end_line',
                'read_end_line',
                'position_reference_column',
                'position_date_column',
                'position_debit_amount_column',
                'position_credit_amount_column',
                'position_description_column',
                'separated_by',
                'date_format',
                'thousands_separator',
                'decimal_separator'
            ];
        },
        mounted() {
            const vm = this;
        },
    };
</script>
