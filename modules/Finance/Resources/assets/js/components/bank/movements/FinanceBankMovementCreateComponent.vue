<template>
    <section id="PayrollEmploymentForm">
        <div class="card-body">
            <div class="alert alert-danger" v-if="errors.length > 0">
                <div class="container">
                    <div class="alert-icon">
                        <i class="now-ui-icons objects_support-17"></i>
                    </div>
                    <strong>Cuidado!</strong> Debe verificar los siguientes errores antes de continuar:
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"
                            @click.prevent="errors = []">
                        <span aria-hidden="true">
                            <i class="now-ui-icons ui-1_simple-remove"></i>
                        </span>
                    </button>
                    <ul>
                        <li v-for="error in errors" :key="error">{{ error }}</li>
                    </ul>
                </div>
            </div>
            <!-- Sección para registrar movimiento bancario -->
            <div class="row">
                <div class="col-12 mb-4">
                    <h6>Datos del movimiento bancario</h6>
                </div>
                <div class="col-md-4" id="helpFinancePaymentDate">
                    <div class="form-group is-required">
                        <label>Fecha de pago:</label>
                        <input type="date" class="form-control input-sm"
                            v-model="record.payment_date"/>
                        <input type="hidden" v-model="record.id">
                    </div>
                </div>
                <div class="col-md-4" id="helpFinanceTransactionType">
                    <div class="form-group is-required">
                        <label>Tipo de transacción:</label>
                        <select2 :options="transaction_types"
                            v-model="record.transaction_type">
                        </select2>
                    </div>
                </div>
                <div class="col-md-4" id="helpFinanceBankAccount">
                    <div class="form-group is-required">
                        <label>Nro. de cuenta:</label>
                        <select2 :options="bank_accounts"
                            v-model="record.finance_bank_account_id">
                        </select2>
                    </div>
                </div>
                <div class="col-md-4" id="helpFinanceBankName">
                    <div class="form-group is-required">
                        <label>Banco:</label>
                        <input type="text" class="form-control input-sm"
                            disabled/>
                    </div>
                </div>
                <div class="col-md-4" id="helpFinanceTypeAccount">
                    <div class="form-group is-required">
                        <label>Tipo de cuenta:</label>
                        <input type="text" class="form-control input-sm"
                            disabled/>
                    </div>
                </div>
                <div class="col-md-4" id="helpFinanceReference">
                    <div class="form-group is-required">
                        <label>Documento de referencia:</label>
                        <input type="text" class="form-control input-sm"
                            v-model="record.reference"/>
                    </div>
                </div>
                <div class="col-md-4" id="helpFinanceConcept">
                    <div class="form-group is-required">
                        <label>Concepto:</label>
                        <input type="text" class="form-control input-sm"
                            v-model="record.concept"/>
                    </div>
                </div>
                <div class="col-md-4" id="helpFinanceAmount">
                    <div class="form-group is-required">
                        <label>Monto:</label>
                        <input type="text" class="form-control input-sm"
                            v-model="record.amount"/>
                    </div>
                </div>
            </div>
            <br>
            <!-- Sección de asiento contable -->
            <div class="row">
                <div class="col-12 mb-4">
                    <h6>Datos del asiento contable</h6>
                </div>
            </div>
            <table class="table table-formulation">
                <thead>
                    <tr>
                        <th class="text-uppercase" width="50%">CÓDIGO DE CUENTA - DENOMINACIÓN</th>
                        <th class="text-uppercase" width="20%">DEBE</th>
                        <th class="text-uppercase" width="20%">HABER</th>
                        <th class="text-uppercase" width="10%">ACCIÓN</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(record, index) in recordsAccounting" :key="index">
                        <td>
                            <select2 :options="accounting_accounts" v-model="record.id" @input="changeSelectinTable(record)"></select2>
                        </td>
                        <td>
                            <input :disabled="record.assets != 0" type="number" data-toggle="tooltip" class="form-control input-sm" :step="cualculateLimitDecimal()" v-model="record.debit" @change="CalculateTot()">
                        </td>
                        <td>
                            <input :disabled="record.debit != 0 " type="number" data-toggle="tooltip" class="form-control input-sm" :step="cualculateLimitDecimal()" v-model="record.assets" @change="CalculateTot()">
                        </td>
                        <td>
                            <div class="text-center">
                                <button @click="clearValues(recordsAccounting.indexOf(record))" class="btn btn-default btn-xs btn-icon btn-action" title="Vaciar valores" data-toggle="tooltip" v-has-tooltip>
                                    <i class="fa fa-eraser"></i>
                                </button>
                                <button @click="deleteAccount(recordsAccounting.indexOf(record), record.entryAccountId)" class="btn btn-danger btn-xs btn-icon btn-action" title="Eliminar registro" data-toggle="tooltip" v-has-tooltip>
                                    <i class="fa fa-trash-o"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td id="helpEntriesAccountSelect">
                            <select2 :disabled="!enableInput" :options="accounting_accounts" id="select2" @input="addAccountingAccount()"></select2>
                        </td>
                        <td id="helpEntriesTotDebit">
                            <div class="form-group text-center">Total Debe:
                                <h6>
                                    <span>{{ data.currency.symbol }}</span>
                                    <span v-if="data.totDebit.toFixed(data.currency.decimal_places) == data.totAssets.toFixed(data.currency.decimal_places) &&
                                            data.totDebit.toFixed(data.currency.decimal_places) >= 0" style="color:#18ce0f;">
                                        <strong>{{ addDecimals(data.totDebit) }}</strong>
                                    </span>
                                    <span v-else style="color:#FF3636;">
                                        <strong>{{ addDecimals(data.totDebit) }}</strong>
                                    </span>
                                </h6>
                            </div>
                        </td>
                        <td id="helpEntriesTotAssets">
                            <div class="form-group text-center">Total Haber:
                                <h6>
                                    <span>{{ data.currency.symbol }}</span>
                                    <span v-if="data.totDebit.toFixed(data.currency.decimal_places) == data.totAssets.toFixed(data.currency.decimal_places) &&
                                            data.totAssets.toFixed(data.currency.decimal_places) >= 0" style="color:#18ce0f;">
                                        <strong>{{ addDecimals(data.totAssets) }}</strong>
                                    </span>
                                    <span v-else style="color:#FF3636;">
                                        <strong>{{ addDecimals(data.totAssets) }}</strong>
                                    </span>
                                </h6>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <!-- Sección de compromisos -->
            <div class="row">
                <div class="col-12 mb-4">
                    <h6>Datos del compromiso</h6>
                </div>
            </div>
            <h6 class="card-title" id="helpStaffPhone">
                Agregar compromiso <i class="fa fa-plus-circle cursor-pointer" @click=""></i>
            </h6>
            <v-client-table :columns="columns" :data="records" :options="table_options">
                <div slot="description" slot-scope="props" class="text-justify">
                    <div v-html="props.row.description"></div>
                </div>
                <div slot="code" slot-scope="props" class="text-center">
                    {{ props.row.code }}
                </div>
                <div slot="id" slot-scope="props" class="text-center">
                    <button @click="editCompromise(props.index, $event)"
                            class="btn btn-warning btn-xs btn-icon btn-action"
                            title="Modificar registro" data-toggle="tooltip" type="button">
                        <i class="fa fa-edit"></i>
                    </button>
                    <button @click="removeCompromise(props.index, $event)"
                            class="btn btn-danger btn-xs btn-icon btn-action"
                            title="Eliminar registro" data-toggle="tooltip"
                            type="button">
                        <i class="fa fa-trash-o"></i>
                    </button>
                </div>
            </v-client-table>
            <div class="card-footer pull-right" id="helpParamButtons">
                <button class="btn btn-default btn-icon btn-round" data-toggle="tooltip" type="button"
                    title="Borrar datos del formulario" @click="reset"><i class="fa fa-eraser"></i>
                </button>
                <button type="button" class="btn btn-warning btn-icon btn-round" data-toggle="tooltip"
                        title="Cancelar y regresar" @click="redirect_back(route_list)">
                    <i class="fa fa-ban"></i>
                </button>
                <button type="button" @click="generateRecord()" data-toggle="tooltip"
                        title="Guardar registro" class="btn btn-success btn-icon btn-round">
                    <i class="fa fa-save"></i>
                </button>
            </div>
        </div>
    </section>
</template>
<script>
    export default {
        data() {
            return {
                record: {},
                records: [],
                data: {
                    date: '',
                    reference: '',
                    concept: '',
                    observations: '',
                    category: '',
                    totDebit: 0,
                    totAssets: 0,
                    institution_id: null,
                    currency_id: null,
                    currency: {
                        id: '',
                        symbol: '',
                        name: '',
                        decimal_places: 0,
                    },
                },
                bank_accounts: [],
                transaction_types:  [
                    {'id' : '', 'text' : "Seleccione..."},
                    {'id' : 'Balance inicial', 'text' : 'Balance inicial'},
                    {'id' : 'Nota de crédito', 'text' : 'Nota de crédito'},
                    {'id' : ' Transferencia o deposito', 'text' : ' Transferencia o deposito'},
                    {'id' : 'Nota de débito', 'text' : 'Nota de débito'}
                ],
                columns: ['code', 'account', 'esp_code', 'description', 'amount', 'id'],
                errors: [],
            }
        },

        created() {
            const vm = this;
            this.table_options.headings = {
                'code': 'Código',
                'account': 'Cuenta',
                'esp_code': 'Código Acción Específica',
                'description': 'Descripción',
                'amount': 'Monto',
                'id': 'Acción'
            };
            this.table_options.sortable = ['code', 'account', 'esp_code', 'description', 'amount'];
            this.table_options.filterable = ['code', 'esp_code', 'description'];
            this.table_options.columnsClasses = {
                'code': 'col-md-2',
                'account': 'col-md-2',
                'esp_code': 'col-md-2',
                'description': 'col-md-2',
                'amount': 'col-md-2',
                'id': 'col-md-2'
            };

            vm.getFinanceBankAccounts();
        },

        mounted() {
            if (this.entries) {
                for (var i = 0; i < this.entries.accounting_accounts.length; i++) {
                    this.recordsAccounting.push({
                        id: this.entries.accounting_accounts[i].accounting_account_id,
                        entryAccountId: this.entries.accounting_accounts[i].id,
                        debit: this.entries.accounting_accounts[i].debit,
                        assets: this.entries.accounting_accounts[i].assets,
                    });
                }
                this.data.totDebit = parseFloat(this.entries.tot_debit);
                this.data.totAssets = parseFloat(this.entries.tot_assets);
            }
        },

        methods: {
            /**
             * Método que borra todos los datos del formulario
             * 
             * @author  Daniel Contreras <dcontreras@cenditel.gob.ve>
             */
            reset() {
            },

            addDecimals(value) {
                return parseFloat(value).toFixed(this.data.currency.decimal_places);
            },
            /**
             * Obtiene los datos de las entidades bancarias registradas
             *
             * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
             */
            getFinanceBankAccounts() {
                this.bank_accounts = [];
                axios.get(`${window.app_url}/finance/get-bank-accounts`).then(response => {
                    this.bank_accounts = response.data;
                });
            },
        }
    };
</script>
