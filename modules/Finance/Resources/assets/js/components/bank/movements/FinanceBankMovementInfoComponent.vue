<template>
    <div id="FinanceBankMovementInfo" class="modal fade" tabindex="-1" role="dialog"
         aria-labelledby="FinanceBankMovementInfoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document" style="max-width:60rem">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h6>
                        <i class="icofont icofont-read-book ico-2x"></i>
                         Información Detallada del Movimiento Bancario
                    </h6>
                </div>

                <div class="modal-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="general" role="tabpanel">
                            <h6 class="text-center">Datos del movimiento bancario</h6><br>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <strong>Institución:</strong>
                                        <div class="row" style="margin: 1px 0">
                                            <span class="col-md-12">
                                                {{ record.institution_id ? record.institution.name : '' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <strong>Fecha de pago:</strong>
                                        <div class="row" style="margin: 1px 0">
                                            <span class="col-md-12">
                                                {{ record.payment_date }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                        <div class="form-group">
                                            <strong>Tipo de transacción:</strong>
                                            <div class="row" style="margin: 1px 0">
                                                <span class="col-md-12">
                                                    {{ record.transaction_type }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <strong>Nro. de cuenta:</strong>
                                        <div class="row" style="margin: 1px 0">
                                            <span class="col-md-12">
                                                {{ record.finance_bank_account_id ? record.finance_bank_account.ccc_number : '' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <strong>Banco:</strong>
                                        <div class="row" style="margin: 1px 0">
                                            <span class="col-md-12">
                                                {{ record.finance_bank_account_id ? record.finance_bank_account.finance_banking_agency.finance_bank.name : '' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <strong>Tipo de cuenta:</strong>
                                        <div class="row" style="margin: 1px 0">
                                            <span class="col-md-12">
                                                {{ record.finance_bank_account_id ? record.finance_bank_account.finance_account_type.name : '' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <strong>Documento de referencia:</strong>
                                        <div class="row" style="margin: 1px 0">
                                            <span class="col-md-12">
                                                {{ record.reference }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <strong>Concepto:</strong>
                                        <div class="row" style="margin: 1px 0">
                                            <span class="col-md-12">
                                                {{ record.concept }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <strong>Monto:</strong>
                                        <div class="row" style="margin: 1px 0">
                                            <span class="col-md-12">
                                                {{ record.amount }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <strong>Tipo de moneda:</strong>
                                        <div class="row" style="margin: 1px 0">
                                            <span class="col-md-12">
                                                {{ record.currency_id ? record.currency.symbol + ' - ' + record.currency.name : '' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-if="record.accounting_entry_pivot && record.accounting_entry_pivot.accounting_entry">
                                <h6 class="text-center">Datos del asiento contable</h6><br>
                                <div class="row">
                                    <div class="col-12">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <tr class="row">
                                                    <th tabindex="0" class="col-8" style="border: 1px solid #dee2e6; position: relative;">
                                                        Denominación
                                                    </th>
                                                    <th tabindex="0" class="col-2" style="border: 1px solid #dee2e6; position: relative;">
                                                        Debe
                                                    </th>
                                                    <th tabindex="0" class="col-2" style="border: 1px solid #dee2e6; position: relative;">
                                                        Haber
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(row, index) in record.accounting_entry_pivot.accounting_entry.accounting_accounts" :key="index" class="row">
                                                    <td style="border: 1px solid #dee2e6;" tabindex="0" class="col-8 text-left">
                                                        {{ row.account.denomination }}
                                                    </td>
                                                    <td style="border: 1px solid #dee2e6;" tabindex="0" class="col-2 text-right">
                                                        {{ addDecimals(row.debit) }}
                                                    </td>
                                                    <td style="border: 1px solid #dee2e6;" tabindex="0" class="col-2 text-right">
                                                        {{ addDecimals(row.assets) }}
                                                    </td>
                                                </tr>
                                                <tr class="row">
                                                    <td style="border: 1px solid #dee2e6;" tabindex="0" class="col-8 text-left">
                                                        Totales Debe / Haber
                                                    </td>
                                                    <td style="border: 1px solid #dee2e6;" tabindex="0" class="col-2 text-right">
                                                        {{ record.currency.symbol }} {{ addDecimals(record.accounting_entry_pivot.accounting_entry.tot_debit) }}
                                                    </td>
                                                    <td style="border: 1px solid #dee2e6;" tabindex="0" class="col-2 text-right">
                                                        {{ record.currency.symbol }} {{ addDecimals(record.accounting_entry_pivot.accounting_entry.tot_assets) }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div v-if="record.budget_compromise && record.budget_compromise.budget_compromise_details">
                                <h6 class="text-center">Datos del compromiso</h6><br>
                                <div class="row">
                                    <div class="col-12">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <tr class="row">
                                                    <th tabindex="0" class="col-2" style="border: 1px solid #dee2e6; position: relative;">
                                                        Código
                                                    </th>
                                                    <th tabindex="0" class="col-3" style="border: 1px solid #dee2e6; position: relative;">
                                                        Cuenta
                                                    </th>
                                                    <th tabindex="0" class="col-2" style="border: 1px solid #dee2e6; position: relative;">
                                                        Código acción específica
                                                    </th>
                                                    <th tabindex="0" class="col-3" style="border: 1px solid #dee2e6; position: relative;">
                                                        Descripción
                                                    </th>
                                                    <th tabindex="0" class="col-2" style="border: 1px solid #dee2e6; position: relative;">
                                                        Monto
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(row, index) in record.budget_compromise.budget_compromise_details" :key="index" class="row">
                                                    <td style="border: 1px solid #dee2e6;" tabindex="0" class="col-2 text-left">
                                                        {{ row.budget_sub_specific_formulation.specific_action.specificable.code }}
                                                    </td>
                                                    <td style="border: 1px solid #dee2e6;" tabindex="0" class="col-3 text-right">
                                                        {{ row.budget_account.code }}
                                                    </td>
                                                    <td style="border: 1px solid #dee2e6;" tabindex="0" class="col-2 text-right">
                                                        {{ row.budget_sub_specific_formulation.specific_action.code }}
                                                    </td>
                                                    <td style="border: 1px solid #dee2e6;" tabindex="0" class="col-3 text-right">
                                                        {{ row.description }}
                                                    </td>
                                                    <td style="border: 1px solid #dee2e6;" tabindex="0" class="col-2 text-right">
                                                        {{ row.amount }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-default btn-sm btn-round btn-modal-close"
                            data-dismiss="modal">
                        Cerrar
                    </button>
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
                    payment_date: '',
                    transaction_type: '',
                    finance_bank_account_id: '',
                    reference: '',
                    concept: '',
                    amount: '',
                    currency_id:'',
                    recordsAccounting: [],
                    entry_concept: '',
                    entry_category: '',
                    institution_id: '',
                    accounts: [],
                    totDebit: 0,
                    totAssets: 0,
                },
                errors: [],
            }
        },
        created() {
            //
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
                return parseFloat(value).toFixed(this.currency_decimal_places);
            },
        },
    }
</script>
