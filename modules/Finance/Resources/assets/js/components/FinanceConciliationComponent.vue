<template>
    <div>
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
            <div class="col-md-3">
                <div class="form-group is-required">
                    <label>Cuenta bancaria:</label>
                    <select2 :options="accounts" v-model="record.account"></select2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group is-required">
                    <label>Mes:</label>
                    <select2
                        :options="months"
                        v-model="record.month"
                    >
                    </select2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group is-required">
                    <label>Año:</label>
                    <select2
                        :options="years"
                        v-model="record.year"
                    >
                    </select2>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Archivo:</label>
                    <input type="file" accept=".txt">
                </div>
            </div>
            <div class="col-md-3">
                <label>Mostrar coincidencias:</label>
                <div class="custom-control custom-switch" data-toggle="tooltip" title="Indique si se mostrarán coincidencias">
                    <input type="checkbox" class="custom-control-input" id="coincidences" v-model="record.coincidences" 
                           :value="true">
                    <label class="custom-control-label" for="coincidences"></label>
                </div>
            </div>
        </div>
        <hr>
        <div class="form-group text-right">
            <button type="button"
                class="btn btn-warning btn-sm btn-round"
                @click="reset()">
                Cancelar
            </button>
            <button type="button" @click="showTableResults()"
                class="btn btn-primary btn-sm btn-round">
                Consultar
            </button>
        </div>
        <br>
        <table v-if="tableResults" table border="1px" cellpadding="0px" cellspacing="0px" style="width:100%">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Descripción</th>
                    <th>N° Referencia</th>
                    <th>Monto</th>
                    <th>Coincide</th>
                    <th>Sub-total</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>xxx</td>
                    <td>xxx</td>
                    <td>xxx</td>
                    <td>xxx</td>
                    <td>xxx</td>
                    <td>xxx</td>
                    <td>xxx</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                record: {
                    account: '',
                    month: '',
                    year: '',
                    file: '',
                    coincidences: false,
                },
                accounts: [],
                errors: [],
                columns: [
                ],
                months: [
                    { "id": "", "text": "Seleccione..." },
                    { "id": 1, "text": "Enero"},
                    { "id": 2, "text": "Febrero"},
                    { "id": 3, "text": "Marzo"},
                    { "id": 4, "text": "Abril"},
                    { "id": 5, "text": "Mayo"},
                    { "id": 6, "text": "Junio"},
                    { "id": 7, "text": "Julio"},
                    { "id": 8, "text": "Agosto"},
                    { "id": 9, "text": "Septiembre"},
                    { "id": 10, "text": "Octubre"},
                    { "id": 11, "text": "Noviembre"},
                    { "id": 12, "text": "Diciembre"},
                ],
                years: [
                    { "id": "", "text": "Seleccione..." },
                ],
                tableResults: false
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
                    account: '',
                    month: '',
                    year: '',
                    file: '',
                    coincidences: false,
                };
                this.tableResults = false
            },

            /**
             * Método que muestra la tabla de resultados.
             */
            showTableResults() {
                this.tableResults = true
            },

            /**
             * Obtiene los datos de las cuentas bancarias.
             *
             * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
             */
            async getBankAccounts() {
                let vm = this;
                await axios.get(`${vm.app_url}/finance/get-bank-accounts/`).then(response => {
                    vm.accounts = response.data;
                }).catch(error => {
                    vm.logs('Budget/Resources/assets/js/_all.js', 127, error, 'getBankAccounts');
                });
            },

            /**
             * Carga el select de años desde el año de inicio de operaciones de
             * la organización hasta el año fiscal.
             *
             * @author Ing. Argenis Osorio <aosorio@cenditel.gob.ve>
             */
            async getInstitutionStartOperationYear() {
                let vm = this;
                await axios.get(`${vm.app_url}/finance/get-institution`).then(response => {
                    var currentTime = new Date();
                    var year = currentTime.getFullYear()
                    let start_operations_date = response.data.institution.start_operations_date;
                    const d = new Date(start_operations_date);
                    let start_operations_year = d.getFullYear();
                    for (var i=start_operations_year; i < year+1; i++) {
                        vm.years.push({ "id": i, "text": i});
                    }
                }).catch(error => {
                    console.log("Error");
                });
            },

        },
        created() {
            this.getBankAccounts();
            this.getInstitutionStartOperationYear();
        },
        mounted() {
        },
    };
</script>
