<template>
	<div>
		<v-client-table :columns="columns" :data="records" :options="table_options">
			<div slot="status" slot-scope="props" class="text-center">
				<span class="text-danger" v-if="props.row.document_status!==null && props.row.document_status.action==='AN'">Anulada</span>
				<span class="text-danger" v-if="props.row.document_status!==null && props.row.document_status.action==='RE'">Rechazada</span>
				<span v-else>
					<span class="text-success" v-if="props.row.status==='PA'">Pagada</span>
					<span class="text-warning" v-if="props.row.status==='PE'">Pendiente</span>
				</span>
			</div>
			<div slot="id" slot-scope="props" class="text-center">
				<div class="row no-gutters">
					<div class="col-4 mb-1">
						<button class="btn btn-info btn-xs btn-icon btn-action" data-title="Ver detalles" 
								data-toggle="modal" type="button" data-target="#payOrderDetails" 
								@click="setDetails(props.row)">
							<i class="fa fa-info-circle"></i>
						</button>
					</div>
					<div class="col-4 mb-1">
						<button class="btn btn-success btn-xs btn-icon btn-action" title="Aprobar" data-toggle="tooltip" 
								type="button" @click="changeDocumentStatus('AP', props.row)"
								:disabled="props.row.document_status!==null && props.row.document_status.action==='RE'">
							<i class="fa fa-check"></i>
						</button>
					</div>
					<div class="col-4 mb-1">
						<button class="btn btn-xs btn-icon btn-action" title="Rechazar" data-toggle="tooltip" type="button" 
								@click="changeDocumentStatus('RE', props.row)"
								:disabled="props.row.document_status!==null && props.row.document_status.action==='RE'">
							<i class="fa fa-ban"></i>
						</button>
					</div>
					<div class="col-4 mb-1">
						<a :href="setUrl(`finance/pay-orders/pdf/${props.row.id}`)" target="_blank" 
						class="btn btn-primary btn-xs btn-icon btn-action" title="Imprimir comprobante" 
						data-toggle="tooltip">
							<i class="fa fa-print"></i>
						</a>
						
					</div>
					<div class="col-4 mb-1">
						<button @click="editForm(props.row.id)"
								class="btn btn-warning btn-xs btn-icon btn-action"
								title="Modificar registro" data-toggle="tooltip" type="button"
								:disabled="props.row.status==='PP' || props.row.status==='PA'">
							<i class="fa fa-edit"></i>
						</button>
					</div>
					<div class="col-4 mb-1">
						<button @click="deleteRecord(props.row.id, '/finance/pay-orders')"
								class="btn btn-danger btn-xs btn-icon btn-action"
								title="Eliminar registro" data-toggle="tooltip"
								type="button" :disabled="props.row.status==='PP' || props.row.status==='PA'">
							<i class="fa fa-trash-o"></i>
						</button>
					</div>
				</div>
			</div>
		</v-client-table>
		<div id="payOrderDetails" class="modal fade text-left" tabindex="-1" role="dialog">
			<div class="modal-dialog vue-crud" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
						<h6>
							<i class="fa fa-info-circle inline-block"></i>
							Detalles de la orden de pago
						</h6>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-12 mb-4">
								<h6>Datos de la Órden</h6>
							</div>
						</div>
						<div class="row">
							<div class="col-6">
								<b>Organismo:</b> {{ details.institution.name }}
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-3">
								<b>Fecha:</b> {{ details.ordered_at }}
							</div>
							<div class="col-md-3">
								<b>Tipo de Orden:</b> {{ (details.type==='PR')?'':'No' }} Presupuestario
							</div>
							<div class="col-md-3">
								<b>Tipo de Documento:</b> Cotización
							</div>
							<div class="col-md-3">
								<b>Nro. Doc. Origen:</b> 
								{{ details.document_sourceable.reference || details.document_sourceable.code }}
							</div>
						</div>
						<div class="row">
							<div class="col-12 mt-4 mb-4">
								<h6>Datos del Proveedor ó Beneficiario</h6>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<b>Nombre o Razón Social:</b> {{ details.name_sourceable.name }}
							</div>
							<div class="col-md-4">
								<b>Nro. de Documento:</b> {{ details.document_number || 'No indica' }}
							</div>
							<div class="col-md-4">
								<b>Monto:</b> {{ formatToCurrency(details.source_amount, details.currency.symbol) }}
							</div>
							<div class="col-md-4">
								<b>Concepto:</b> {{ details.concept }}
							</div>
							<div class="col-md-4">
								<b>Pago parcial:</b> {{ details.is_partial ? 'Si': 'No' }}
							</div>
							<div class="col-md-4">
								<b>Monto a pagar</b> {{ formatToCurrency(details.amount, details.currency.symbol) }}
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-12 mt-4 mb-4">
								<h6>Datos Bancarios</h6>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<b>Método de pago:</b> {{ details.finance_payment_method.name }}
							</div>
							<div class="col-md-4">
								<b>Banco:</b> {{ details.finance_bank_account.finance_banking_agency.finance_bank.name }}
							</div>
							<div class="col-md-4">
								<b>Nro. de Cuenta:</b> {{ format_bank_account(details.finance_bank_account.ccc_number) }}
							</div>
							<div class="col-12">
								<b>Observación:</b>
								<div v-html="details.observations"></div>
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
									<col class="col-8">
									<col class="col-2">
									<col class="col-2">
									<thead>
										<tr>
											<th class="text-uppercase" width="50%">CÓDIGO DE CUENTA - DENOMINACIÓN</th>
											<th class="text-uppercase" width="20%">DEBE</th>
											<th class="text-uppercase" width="20%">HABER</th>
										</tr>
									</thead>
									<tbody v-if="details.accounting_entryable">
										<tr v-for="(acc, index) in details.accounting_entryable.accounting_entry.accounting_accounts" :key="index">
											<td class="text-justify">
												{{ acc.account.group }}.{{ acc.account.subgroup }}.{{ acc.account.item }}.
												{{ acc.account.generic }}.{{ acc.account.specific }}.
												{{ acc.account.subspecific }} - {{ acc.account.denomination }}
											</td>
											<td class="text-right">
												{{ formatToCurrency(acc.debit, details.currency.symbol) }}
											</td>
											<td class="text-right">
												{{ formatToCurrency(acc.assets, details.currency.symbol) }}
											</td>
										</tr>
									</tbody>
									<tbody v-else>
										<tr>
											<td class="text-uppercase" colspan="4">Sin Asiento Contable registrado</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
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
                records: [],
				details: {
					ordered_at: '',
					type: '',
					documentType: '',
					document_number: '',
					source_amount: 0,
					amount: 0,
					concept: '',
					is_partial: false,
					observations: '',
					document_sourceable: {
						reference: '',
						code: ''
					},
					institution: {
						name: ''
					},
					name_sourceable: {
						name: ''
					},
					currency: {
						symbol: '',
						decimal_places: 2
					},
					finance_payment_method: {
						name: ''
					},
					finance_bank_account: {
						ccc_number: '',
						finance_banking_agency: {
							finance_bank: {
								name: ''
							}
						}
					},
					accounting_entryable: {
						accounting_entry: {
							accounting_accounts: []
						}
					}
				},
				columns: ['code', 'ordered_at', 'name_sourceable.name', 'concept', 'status', 'id']
            }
        },
		methods: {
			reset() {},
			/**
			 * Modifica el estatus del documento de la orden de pago
			 * 
			 * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
			 *
			 * @param   {String}  status  Estatus a modificar
			 * @param   {Object}  record  Datos del registro a modificar
			 */
			async changeDocumentStatus(status, record) {
				const vm = this;
				const url = vm.setUrl('finance/pay-orders/change-document-status');
				vm.loading = true;
				await axios.post(url, {id: record.id, action: status}).then(response => {
					record = response.data.record;
					vm.showMessage('custom', 'Éxito!', 'success', 'screen-ok', 'Estatus del documento actualizado');
				}).catch(error => {
					console.error(error);
				});
				vm.loading = false;
			},
			/**
			 * Establece los datos para mostrar detalles del registro seleccionado
			 * 
			 * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
			 *
			 * @param   {Object}  record  Datos del registro a mostrar
			 */
			setDetails(record) {
				this.details = record;
			}
		},
        created() {
			this.table_options.headings = {
				'code': 'Código',
				'ordered_at': 'Fecha solicitud',
				'name_sourceable.name': 'Proveedor / Beneficiario',
				'concept': 'Concepto',
				'status': 'Estatus',
				'id': 'Acción'
			};
			this.table_options.columnsClasses = {
				'code': 'col-md-2',
				'ordered_at': 'col-md-2',
				'name_sourceable.name': 'col-md-2',
				'concept': 'col-md-2',
				'status': 'col-md-2',
				'id': 'col-md-2'
			};
			this.table_options.sortable = ['code', 'ordered_at', 'name_sourceable.name', 'concept', 'status'];
			this.table_options.filterable = ['code', 'ordered_at', 'name_sourceable.name', 'concept', 'status'];
		},
        mounted() {
			this.initRecords(this.route_list, '');
		},
    }
</script>