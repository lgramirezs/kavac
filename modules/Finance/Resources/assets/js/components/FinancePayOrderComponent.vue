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
                        <select2 :options="documentSources" v-model="record.document_sourceable_id"/>
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
                        <label for="" class="control-label">Nro. de Documento</label>
                        <select2 :options="documentNumbers" v-model="record.document_number"/>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group is-required">
                        <label for="" class="control-label">Monto</label>
                        <input type="text" class="form-control input-sm">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group is-required">
                        <label for="" class="control-label">Concepto</label>
                        <input type="text" class="form-control input-sm">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group is-required">
                        <label for="" class="control-label">Pago Parcial</label>
                        <div class="col-12 bootstrap-switch-mini">
                            <input type="checkbox" class='form-control bootstrap-switch' data-on-label="SI" data-off-label="NO">
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group is-required">
                        <label for="" class="control-label">Monto a Pagar</label>
                        <input type="text" class="form-control input-sm">
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
                        <select2 :options="paymentMethods" v-model="record.payment_method_id"/>
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
                        <select class="form-control select2" data-toggle="tooltip" title="Seleccione el número de cuenta bancaria" v-model="record.finance_bank_account_id">
                            <option :value="account.id" v-for="(account, index) in accounts" :key="index">
                                {{ format_bank_account(account.text) }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="" class="control-label">Observación</label>
                        <ckeditor :editor="ckeditor.editor" data-toggle="tooltip"
                                    title="Indique una observación para la orden de pago" 
                                    :config="ckeditor.editorConfig" class="form-control" 
                                    tag-name="textarea" rows="3"
                                    v-model="record.observation"></ckeditor>
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
                        <col class="col-2">
                        <col class="col-5">
                        <col class="col-2">
                        <col class="col-2">
                        <col class="col-1">
                        <thead>
                            <tr>
                                <th>CUENTA</th>
                                <th>DESCRIPCIÓN</th>
                                <th>DEBE</th>
                                <th>HABER</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(account, index) in accounting" :key="index"></tr>
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
        data() {
            return {
                record: {
                    ordered_at: '',
                    type: '',
                    documentType: '',
                    observation: '',
                    institution_id: '',
                    document_sourceable_id: '',
                    name_sourceable_id: '',
                    payment_method_id: '',
                    finance_bank_id: '',
                    finance_bank_account_id: ''
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
                accounting: [],
                institutions: [],
                paymentMethods: [],
                banks: [],
                accounts: [],
                displayedMessage: false
            }
        },
        methods: {
            /**
             * Reinicia los campos del formularios
             *
             * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
             */
            reset() {},
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
                    vm.showMessage(
                        'custom', 'Alerta!', 'warning', 'screen-error', 
                        'Debe indicar todos los datos de la órden'
                    );
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
             * Establece los datos de la orden de pago a generar
             * 
             * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
             */
            setPayOrderData() {}
        },
        async mounted() {
            const vm = this;
            vm.loading = true;
            await vm.getInstitutions();
            await vm.getPaymentMethods();
            await vm.getBanks();
            vm.loading = false;
        }
    }
</script>