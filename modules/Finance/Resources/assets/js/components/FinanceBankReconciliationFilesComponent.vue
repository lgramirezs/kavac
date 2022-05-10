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
                                    <select2 :options="banks" v-model="record.finance_bank_id"></select2>
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
                                <input type="checkbox" name="checkbox"
                                    class="bootstrap-switch" data-on-label="SI"
                                    data-off-label="NO" checked>
                            </div>
                            <div class="col-md-4">
                                <label>Leer línea final:</label>
                                <br>
                                <input type="checkbox" name="checkbox"
                                    class="bootstrap-switch" data-on-label="SI"
                                    data-off-label="NO" checked>
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
                                    <select2 :options="banks" v-model="record.finance_bank_id"></select2>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group is-required">
                                    <label>Fecha:</label>
                                    <select2 :options="banks" v-model="record.finance_bank_id"></select2>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group is-required">
                                    <label>Monto débito:</label>
                                    <select2 :options="banks" v-model="record.finance_bank_id"></select2>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group is-required">
                                    <label>Monto cŕedito:</label>
                                    <select2 :options="banks" v-model="record.finance_bank_id"></select2>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group is-required">
                                    <label>Descripción:</label>
                                    <select2 :options="banks" v-model="record.finance_bank_id"></select2>
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
                                    <select2 :options="banks" v-model="record.finance_bank_id"></select2>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group is-required">
                                    <label>Descripción:</label>
                                    <select2 :options="banks" v-model="record.finance_bank_id"></select2>
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
                    finance_bank_id: '',
                },
                errors: [],
                records: [],
                banks: [],
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
                };
            },
        },
        created() {
            this.getBanks();
        },
    };
</script>
