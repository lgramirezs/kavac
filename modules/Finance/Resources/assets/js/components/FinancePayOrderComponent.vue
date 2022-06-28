<template>
    <div class="card">
        <div class="card-header">
			<h6 class="card-title text-uppercase">
				Órden de Pago
			</h6>
			<div class="card-btns">
				<a href="javascript:void(0)" class="btn btn-sm btn-primary btn-custom" 
                   @click="redirect_back(route_list)" title="Ir atrás" data-toggle="tooltip">
					<i class="fa fa-reply"></i>
				</a>
				<a href="javascript:void(0)" class="card-minimize btn btn-card-action btn-round" 
                   title="Minimizar" data-toggle="tooltip">
					<i class="now-ui-icons arrows-1_minimal-up"></i>
				</a>
			</div>
		</div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 mb-4">
                    <h6>Datos de la Órden</h6>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group is-required">
                        <label for="" class="control-label">Institución</label>
                        <select2 :options="institutions" v-model="record.institution_id"/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group is-required">
                        <label for="" class="control-label">Fecha</label>
                        <input type="date" class="form-control input-sm" data-toggle="tooltip" 
                               title="Fecha de la órden de pago" v-model="record.ordered_at">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group is-required">
                        <label for="" class="control-label">Tipo de Orden</label>
                        <select2 :options="types" v-model="record.type" @input="getSourceDocuments"/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group is-required">
                        <label for="" class="control-label">Tipo de Documento</label>
                        <select2 :options="documentTypes" v-model="record.documentType" 
                                 @input="getSourceDocuments"/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group is-required">
                        <label for="" class="control-label">Nro. Documento de Origen</label>
                        <select2 :options="documentSources" @input="setCompromise" v-model="record.document_sourceable_id"/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 mt-4 mb-4">
                    <h6>Datos del Proveedor ó Beneficiario</h6>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group is-required">
                        <label for="" class="control-label">Nombre o Razón Social</label>
                        <select2 :options="receivers" v-model="record.name_sourceable_id"/>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group is-required">
                        <label for="" class="control-label">Nro. de Documento (solo para órdenes manuales)</label>
                        <!--<select2 :options="documentNumbers" v-model="record.document_number"/>-->
                        <input type="text" class="form-control input-sm" v-model="record.document_number" 
                               :disabled="record.document_sourceable_id!==''">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group is-required">
                        <label for="" class="control-label">Monto</label>
                        <input type="number" step="0.01" class="form-control input-sm" v-model="record.source_amount" 
                               :disabled="record.document_sourceable_id!==''">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group is-required">
                        <label for="" class="control-label">Concepto</label>
                        <input type="text" class="form-control input-sm" v-model="record.concept">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group is-required">
                        <label for="" class="control-label">Pago Parcial</label>
                        <div class="col-12 bootstrap-switch-mini">
                            <input type="checkbox" class='form-control bootstrap-switch' data-on-label="SI" data-off-label="NO" 
                                   v-model="record.is_partial">
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group is-required">
                        <label for="" class="control-label">Monto a Pagar</label>
                        <input type="text" class="form-control input-sm" v-model="record.amount" :readonly="!record.is_partial">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 mt-4 mb-4">
                    <h6>Datos Bancarios</h6>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group is-required">
                        <label for="" class="control-label">Método de Pago</label>
                        <select2 :options="paymentMethods" v-model="record.finance_payment_method_id"/>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group is-required">
                        <label for="" class="control-label">Banco</label>
                        <select2 :options="banks" @input="getBankAccounts" v-model="record.finance_bank_id"/>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group is-required">
                        <label for="" class="control-label">Nro. de Cuenta</label>
                        <select2 :options="accounts" v-model="record.finance_bank_account_id"></select2>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="" class="control-label">Observación</label>
                        <ckeditor :editor="ckeditor.editor" data-toggle="tooltip"
                                    title="Indique una observación para la orden de pago" 
                                    :config="ckeditor.editorConfig" class="form-control" 
                                    tag-name="textarea" rows="3"
                                    v-model="record.observations"></ckeditor>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 mt-4 mb-4">
                    <h6>Datos Contables</h6>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <table class="table table-sm table-striped table-hover">
                        <col class="col-6">
                        <col class="col-2">
                        <col class="col-2">
                        <col class="col-2">
                        <thead>
                            <tr>
                                <th class="text-uppercase" width="50%">CÓDIGO DE CUENTA - DENOMINACIÓN</th>
                                <th class="text-uppercase" width="20%">DEBE</th>
                                <th class="text-uppercase" width="20%">HABER</th>
                                <th class="text-uppercase" width="10%">ACCIÓN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(account, index) in autoAccounting" :key="index">

                            </tr>
                            <tr v-for="(record, index) in recordsAccounting" :key="index">
                                <td>
                                    <select2 :options="accounting_accounts" v-model="record.id" @input="changeSelectinTable(record)"></select2>
                                </td>
                                <td>
                                    <input type="number" data-toggle="tooltip" class="form-control input-sm" :step="cualculateLimitDecimal()" v-model="record.debit" @change="CalculateTot()">
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
                                <td colspan="4"></td>
                            </tr>
                            <tr>
                                <td id="helpEntriesAccountSelect">
                                    <select2 :disabled="!enableInput" :options="accounting_accounts" id="select2" @input="addAccountingAccount()"></select2>
                                </td>
                                <td id="helpEntriesTotDebit">
                                    <div class="form-group text-center">Total Debe:
                                        <h6>
                                            <span>{{ accounting.currency.symbol }}</span>
                                            <span v-if="accounting.totDebit.toFixed(accounting.currency.decimal_places) == accounting.totAssets.toFixed(accounting.currency.decimal_places) &&
                                                    accounting.totDebit.toFixed(accounting.currency.decimal_places) >= 0" style="color:#18ce0f;">
                                                <strong>{{ addDecimals(accounting.totDebit) }}</strong>
                                            </span>
                                            <span v-else style="color:#FF3636;">
                                                <strong>{{ addDecimals(accounting.totDebit) }}</strong>
                                            </span>
                                        </h6>
                                    </div>
                                </td>
                                <td id="helpEntriesTotAssets">
                                    <div class="form-group text-center">Total Haber:
                                        <h6>
                                            <span>{{ accounting.currency.symbol }}</span>
                                            <span v-if="accounting.totDebit.toFixed(accounting.currency.decimal_places) == accounting.totAssets.toFixed(accounting.currency.decimal_places) &&
                                                    accounting.totAssets.toFixed(accounting.currency.decimal_places) >= 0" style="color:#18ce0f;">
                                                <strong>{{ addDecimals(accounting.totAssets) }}</strong>
                                            </span>
                                            <span v-else style="color:#FF3636;">
                                                <strong>{{ addDecimals(accounting.totAssets) }}</strong>
                                            </span>
                                        </h6>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
			<buttonsDisplay :route_list="app_url+'/finance/pay-orders'" display="false"></buttonsDisplay>
		</div>
    </div>
</template>

<script>
    export default {
        props: {
            accounting_accounts: {
                type: Array,
                default: []
            },
        },
        data() {
            return {
                record: {
                    ordered_at: '',
                    type: '',
                    documentType: '',
                    observations: '',
                    source_amount: 0,
                    amount: 0,
                    institution_id: '',
                    document_sourceable_id: '',
                    name_sourceable_id: '',
                    finance_payment_method_id: '',
                    finance_bank_id: '',
                    finance_bank_account_id: '',
                    budget_compromise_id: '', //Solo es para identificar el compromiso de existir
                    is_partial: false,
                    document_number: '',
                    concept: '',
                },
                types: [
                    {'id': '', 'text': 'Seleccione...'},
                    {'id': 'PR', 'text': 'Presupuestario'},
                    {'id': 'NP', 'text': 'No presupuestario'}
                ],
                documentTypes: [
                    {'id': '', 'text': 'Seleccione...'},
                    {'id': 'C', 'text': 'Cotización'},
                    {'id': 'M', 'text': 'Manual'},
                    {'id': 'R', 'text': 'Reintegro'},
                    {'id': 'O', 'text': 'Otro'}
                ],
                documentSources: [
                    {'id': '', 'text': 'Seleccione...'}
                ],
                receivers: [
                    {'id': '', 'text': 'Seleccione...'}
                ],
                documentNumbers: [
                    {'id': '', 'text': 'Seleccione...'}
                ],
                autoAccounting: [],
                institutions: [],
                paymentMethods: [],
                banks: [],
                accounts: [],
                displayedMessage: false,
                enableInput: false,
                recordsAccounting: [],
                rowsToDelete: [],
                accounting: {
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
            }
        },
        watch: {
            record: {
                deep: true,
                handler: function(newValue, oldValue) {
                    const vm = this;
                    if (vm.record.source_amount) {
                        vm.record.amount = vm.record.source_amount;
                    }
                    vm.enableInput = (vm.accounting.currency.symbol);
                }
            },
        },
        methods: {
            /**
             * Reinicia los campos del formularios
             *
             * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
             */
            reset() {
                const vm = this;
                vm.record.ordered_at = '';
                vm.record.type = '';
                vm.record.documentType = '';
                vm.record.observations = '';
                vm.record.source_amount = 0;
                vm.record.amount = 0;
                vm.record.institution_id = '';
                vm.record.document_sourceable_id = '';
                vm.record.name_sourceable_id = '';
                vm.record.finance_payment_method_id = '';
                vm.record.finance_bank_id = '';
                vm.record.finance_bank_account_id = '';
                vm.record.budget_compromise_id = '';
                vm.record.is_partial = false;
                vm.record.document_number = '';
                vm.record.concept = '';
            },
            /**
             * Obtiene un listado de documentos a los cuales ordenar un pago
             * 
             * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
             */
            async getSourceDocuments() {
                const vm = this;

                if (!vm.displayedMessage && (!vm.record.ordered_at || !vm.record.type || !vm.record.documentType)) 
                {
                    vm.displayedMessage = true;
                    /*vm.showMessage(
                        'custom', 'Alerta!', 'warning', 'screen-error', 
                        'Debe indicar todos los datos de la órden'
                    );*/
                    return false;
                }

                await axios.post(
                    `${vm.app_url}/finance/pay-orders/documents/get-sources`,
                    {
                        ordered_at: vm.record.ordered_at,
                        type: vm.record.type,
                        documentType: vm.record.documentType
                    }
                ).then(response => {
                    vm.documentSources = response.data.records;
                }).catch(error => {
                    console.error(error);
                });
                vm.displayedMessage = false;
            },
            /**
             * Establece los datos del compromiso si existen
             * 
             * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
             */
            setCompromise() {
                const vm = this;
                if (vm.record.document_sourceable_id) {
                    const documentSource = vm.documentSources.filter(
                        (ds) => parseInt(ds.id) === parseInt(vm.record.document_sourceable_id)
                    )[0] || '';
                    vm.record.budget_compromise_id = (documentSource)?documentSource.budget_compromise_id:'';
                    vm.record.source_amount = (documentSource)?documentSource.budget_total_amount:0;
                    vm.accounting.currency = (documentSource)?documentSource.currency:0;
                }
            },
            /**
             * Establece la información base para cada fila de cuentas
             *
             * @author Juan Rosas <jrosas@cenditel.gob.ve | juan.rosasr01@gmail.com>
             */
            addAccountingAccount() {
                if ($('#select2').val() != '') {
                    for (var i = this.accounting_accounts.length - 1; i >= 0; i--) {
                        if (this.accounting_accounts[i].id == $('#select2').val()) {
                            this.recordsAccounting.push({
                                id: $('#select2').val(),
                                entryAccountId: null,
                                debit: 0,
                                assets: 0,
                            });
                            $('#select2').val('');
                            break;
                        }
                    }
                }
            },
            /**
             * Elimina la fila de la cuenta y vuelve a calcular el total del asiento
             *
             * @author Juan Rosas <jrosas@cenditel.gob.ve | juan.rosasr01@gmail.com>
             */
            deleteAccount: function(index, id) {
                this.rowsToDelete.push(id);
                this.recordsAccounting.splice(index, 1);
                this.CalculateTot();
            },
            /**
             * vacia los valores del debe y del haber de la fila de la cuenta y vuelve a calcular el total del asiento
             *
             * @author Juan Rosas <jrosas@cenditel.gob.ve | juan.rosasr01@gmail.com>
             */
            clearValues: function(index) {
                this.recordsAccounting[index].assets = 0.00;
                this.recordsAccounting[index].debit = 0.00;
                this.CalculateTot();
            },
            /**
             * Agrega decimales al monto
             *
             * @author Juan Rosas <jrosas@cenditel.gob.ve | juan.rosasr01@gmail.com>
             */
            addDecimals(value) {
                return parseFloat(value).toFixed(this.accounting.currency.decimal_places);
            },
            /**
             * Vacia la información del debe y haber de la columna sin cuenta seleccionada
             *
             * @author Juan Rosas <jrosas@cenditel.gob.ve | juan.rosasr01@gmail.com>
             */
            changeSelectinTable: function(record) {
                // si asigna un select en vacio, vacia los valores del debe y haber de esa fila
                if (record.id == '') {
                    record.debit = 0;
                    record.assets = 0;
                    this.CalculateTot();
                }
            },
            /**
             * Establece la cantidad de decimales correspondientes a la moneda que se maneja
             *
             * @author Juan Rosas <jrosas@cenditel.gob.ve | juan.rosasr01@gmail.com>
             */
            cualculateLimitDecimal() {
                var res = "0.";
                for (var i = 0; i < this.accounting.currency.decimal_places - 1; i++) {
                    res += "0";
                }
                res += "1";
                return res;
            },
            /**
             * Calcula el total del debe y haber del asiento contable
             *
             * @author Juan Rosas <jrosas@cenditel.gob.ve | juan.rosasr01@gmail.com>
             */
            CalculateTot() {

                this.accounting.totDebit = 0;
                this.accounting.totAssets = 0;

                /** Se recorren todo el arreglo que tiene todas las filas de las cuentas y saldos para el asiento y se calcula el total */
                for (var i = this.recordsAccounting.length - 1; i >= 0; i--) {
                    if (this.recordsAccounting[i].id != '') {
                        var debit = (this.recordsAccounting[i].debit != '') ? this.recordsAccounting[i].debit : 0;
                        var assets = (this.recordsAccounting[i].assets != '') ? this.recordsAccounting[i].assets : 0;

                        this.recordsAccounting[i].debit = parseFloat(debit).toFixed(this.accounting.currency.decimal_places);
                        this.recordsAccounting[i].assets = parseFloat(assets).toFixed(this.accounting.currency.decimal_places)

                        this.accounting.totDebit += (this.recordsAccounting[i].debit != '')
                                                    ? parseFloat(this.recordsAccounting[i].debit) : 0;
                        this.accounting.totAssets += (this.recordsAccounting[i].assets != '') 
                                                     ? parseFloat(this.recordsAccounting[i].assets) : 0;
                    }
                }
            },
            /**
             * Establece los datos de la orden de pago a generar
             * 
             * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
             */
            createRecord() {
                const vm = this;
                const url = vm.setUrl('finance/pay-orders');
                if (vm.record.id) {
                    vm.updateRecord(url);
                }
                else {
                    vm.loading = true;
                    var fields = {};
                    for (var index in vm.record) {
                        fields[index] = vm.record[index];
                    }
                    /** Datos de los ítems contables */
                    fields['accounting'] = vm.accounting;
                    fields['accountingItems'] = vm.recordsAccounting;
                    axios.post(url, fields).then(response => {
                        location.href = vm.setUrl('finance/pay-orders');
                    }).catch(error => {
                        console.error(error);
                    });
                    vm.loading = false;
                }
            }
        },
        async mounted() {
            const vm = this;
            vm.loading = true;
            await vm.getInstitutions();
            await vm.getPaymentMethods();
            await vm.getBanks();
            await vm.getReceivers();
            vm.loading = false;
        }
    }
</script>