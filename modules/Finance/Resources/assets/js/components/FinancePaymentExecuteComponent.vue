<template>
    <div>
        <div class="card">
            <div class="card-header">
                <h6 class="card-title text-uppercase">
                    Emisión de Pago
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
                        <h6>Datos del Pago</h6>
                    </div>
                </div>
                <div class="row">
                    
                    <!--<div class="col-md-3">
                        <div class="form-group is-required">
                            <label for="" class="control-label">Incluir cuentas presupuestarias</label>
                            <div class="col-12 bootstrap-switch-mini">
                                <input type="checkbox" class='form-control bootstrap-switch' data-on-label="SI" data-off-label="NO">
                            </div>
                        </div>
                    </div>-->                    
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group is-required">
                            <label for="" class="control-label">Fecha</label>
                            <input type="date" class="form-control input-sm" data-toggle="tooltip" 
                                title="Fecha del pago" v-model="record.paid_at">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group is-required">
                            <label for="" class="control-label">¿Pago Parcial?</label>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="isPartialSwitch" 
                                       :value="true" v-model="record.is_partial" data-on-text="SI" data-off-text="NO">
                                <label class="custom-control-label" for="isPartialSwitch">Si</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group is-required">
                            <label for="" class="control-label">Proveedor o Beneficiario</label>
                            <select2 :options="receivers" v-model="record.receiver_id" @input="getPayOrders"/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group is-required">
                            <label for="" class="control-label">Nro. Referencia</label>
                            <select2 :options="references" v-model="record.reference_selected" @input="setPayOrderData"/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="" class="control-label">Monto de la Orden</label>
                            <input type="text" class="form-control text-right" v-model="record.source_amount" readonly>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group is-required">
                            <label for="" class="control-label">Monto del pago (Subtotal)</label>
                            <input type="text" class="form-control text-right" v-model="record.sub_amount" v-is-numeric :readonly="!record.is_partial">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group is-required">
                            <label for="">Retenciones</label>
                            <input type="text" class="form-control text-right" v-model="record.deduction_amount" readonly>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group is-required">
                            <label for="">Total a pagar</label>
                            <input type="text" class="form-control text-right" v-model="record.paid_amount" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="" class="control-label">Tipo de retención</label>
                            <div class="row">
                                <div class="col-10">
                                    <select2 :options="deductions" v-model="selDeduction"/>
                                </div>
                                <div class="col-2">
                                    <a href="javascript:void(0)" data-original-title="Agregar retención" 
                                    class="btn btn-sm btn-info btn-action btn-tooltip" data-toggle="modal" data-target="#addDeduction">
                                        <i class="fa fa-plus-circle"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 mb-4">
                        Retenciones
                    </div>
                    <div class="col-md-6 mb-4">
                        <table class="table table-sm table-striped table-hover">
                            <thead>
                                <tr>
                                    <th class="font-weight-bold">Tipo de retención</th>
                                    <th class="font-weight-bold">Monto</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(ret, index) in record.deductions" :key="index">
                                    <td>
                                        {{ ret.name }}
                                    </td>
                                    <td class="text-right">
                                        {{ formatToCurrency(ret.amount) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group is-required">
                            <label for="" class="control-label">Observaciones</label>
                            <ckeditor :editor="ckeditor.editor" data-toggle="tooltip"
                                        title="Indique una observación para la emisión de pago" 
                                        :config="ckeditor.editorConfig" class="form-control" 
                                        tag-name="textarea" rows="3" v-model="record.observations"></ckeditor>
                        </div>
                    </div>
                </div>
                <hr>
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
                                <tr v-for="(account, index) in record.autoAccounting" :key="index">

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
                <buttonsDisplay :route_list="app_url+'/finance/payment-execute'" display="false"></buttonsDisplay>
            </div>
        </div>
        <div class="modal fade text-left" id="addDeduction" tabindex="-1" aria-labelledby="addDeductionLabel" 
             aria-hidden="true" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
						<h6>
							<i class="icofont icofont-mathematical-alt-2 inline-block"></i>
							Retención
						</h6>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="">
                                        M.O.R / Base Imponible
                                    </label>
                                    <input type="number" class="form-control" data-toggle="tooltip" 
                                           title="Monto objeto de retención o base imponible" v-model="mor" @keyup="setDeductionAmount">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="">Monto de la retención</label>
                                    <input type="number" class="form-control" data-toggle="tooltip" 
                                           v-model="deduction_amount" title="Monto calculado a partir de la base imponible" 
                                           readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="form-group">
                            <button type="button" class="btn btn-default btn-sm btn-round btn-modal-close"
									@click="selDeduction=''" data-dismiss="modal">
								Cerrar
							</button>
                            <button type="button" @click="addDeduction"
									class="btn btn-primary btn-sm btn-round btn-modal-save">
								Agregar
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
        props: {
            accounting_accounts: {
                type: Array,
                default: []
            },
        },
        data() {
            return {
                record: {
                    paid_at: '',
                    is_partial: false,
                    receiver_id: '',
                    reference_selected: {},
                    source_amount: 0,
                    sub_amount: 0,
                    deduction_amount: 0,
                    paid_amount: 0,
                    deductions: [],
                    observations: '',
                    autoAccounting: [],
                    accounting: []
                },
                selDeduction: '',
                accounting: [],
                deductions: [],
                tmpDeductions: [],
                references: [],
                receivers: [],
                mor: 0,
                deduction_amount: 0,
                enableInput: true,
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
        methods: {
            /**
             * Reinicia los campos del formularios
             *
             * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
             */
            async reset() {
                const vm = this;
                vm.record.paid_at = '';
                vm.record.is_partial = false;
                vm.record.receiver_id = '';
                vm.record.reference_selected = {};
                vm.record.source_amount = 0;
                vm.record.sub_amount = 0;
                vm.record.deduction_amount = 0;
                vm.record.paid_amount = 0;
                vm.record.deductions = [];
                vm.record.observations = '';
                vm.record.AutoAccounting = [];
                vm.record.accounting = [];
            },
            /**
             * Listado de órdenes de pago pendientes por ejecutar
             * 
             * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
             */
            async getPayOrders() {
                const vm = this;
                if (!vm.record.receiver_id) {
                    return false;
                }
                await axios.get(`/finance/pay-orders/pending/${vm.record.receiver_id}`).then(response => {
                    vm.references = response.data.records;
                }).catch(error => {
                    console.error(error);
                });
            },
            /**
             * Establece el monto de la retención
             *
             * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
             */
            setDeductionAmount() {
                const vm = this;
                if (!vm.mor) {
                    return;
                }
                let deduction = vm.deductions.filter(d => d.id === parseInt(vm.selDeduction))[0] || '';
                let formula = deduction.formula.replace('monto', vm.mor);
                vm.deduction_amount = eval(formula);
            },
            /**
             * Agrega la retención seleccionada
             *
             * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
             */
            addDeduction() {
                const vm = this;
                let deduction = vm.deductions.filter(d => d.id === parseInt(vm.selDeduction))[0] || '';
                
                vm.record.deductions.push({
                    id: deduction.id,
                    name: deduction.text,
                    mor: vm.mor,
                    amount: vm.deduction_amount,
                    formula: deduction.formula,
                    accounting_account: deduction.accounting_account
                });
                vm.setAmounts();
                vm.deductions = vm.tmpDeductions.filter(d => d.id !== parseInt(vm.selDeduction))[0] || '';
                vm.selDeduction = '';
                $('#addDeduction').find('.close').click();
            },
            /**
             * Calcula los montos del pago a ejecutar
             *
             * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
             */
            setAmounts() {
                const vm = this;
                let deduction_amount = 0;
                let reference = vm.references.filter(r => {
                    return r.id === parseInt(vm.record.reference_selected);
                })[0] || '';
                vm.record.deductions.forEach(d => {
                    deduction_amount += parseFloat(d.amount);
                });
                vm.record.deduction_amount = parseFloat(deduction_amount).toFixed(reference.currency.decimal_places);
                vm.record.paid_amount = parseFloat(
                    parseFloat(vm.record.sub_amount) - parseFloat(deduction_amount)
                ).toFixed(reference.currency.decimal_places);
            },
            /**
             * Obtiene un listado de los receptores de órdenes de pago por cancelar
             *
             * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
             */
            async getReceivers() {
                const vm = this;
                await axios.get('/finance/payment-execute/list/get-receivers').then(response => {
                    vm.receivers = response.data.records;
                }).catch(error => {
                    console.error(error);
                });
            },
            /**
             * Establece los datos de la órden de pago seleccionada
             *
             * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
             */
            setPayOrderData() {
                const vm = this;
                if (!vm.record.reference_selected) {
                    return false;
                }
                let reference = vm.references.filter(r => {
                    return r.id === parseInt(vm.record.reference_selected);
                })[0] || '';
                vm.record.source_amount = parseFloat(reference.amount).toFixed(reference.currency.decimal_places);
                vm.record.sub_amount = parseFloat(reference.amount).toFixed(reference.currency.decimal_places);
                vm.record.paid_amount = parseFloat(reference.amount).toFixed(reference.currency.decimal_places);
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
             * Agrega decimales al monto
             *
             * @author Juan Rosas <jrosas@cenditel.gob.ve | juan.rosasr01@gmail.com>
             */
            addDecimals(value) {
                return parseFloat(value).toFixed(this.accounting.currency.decimal_places);
            },
            /**
             * Establece los datos de la orden de pago a generar
             * 
             * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
             */
            async createRecord() {
                const vm = this;
                
                const url = vm.setUrl('finance/payment-execute');
                if (vm.record.id) {
                    await vm.updateRecord(url);
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
                    
                    await axios.post(url, fields).then(response => {
                        bootbox.confirm('Desea generar el comprobante?', function (result) {
                            if (result) {
                                let link = document.createElement('a');
                                link.target = '_blank';
                                link.href = vm.setUrl(`finance/payment-execute/pdf/${response.data.record.id}`);
                                link.click();
                                setTimeout(() => {
                                    location.href = vm.setUrl('finance/payment-execute');
                                }, 3000);
                            }
                            else {
                                location.href = vm.setUrl('finance/payment-execute');
                            }
                        });
                        resultStorage = true;
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
            await vm.reset();
            await vm.getReceivers();
            //await vm.getPayOrders();
            await vm.getDeductions();
            vm.loading = false;
        }
    }
</script>