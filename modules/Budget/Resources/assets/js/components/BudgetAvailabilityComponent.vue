<template>
	<div class="form-horizontal">
		<div class="card-body">
			<!-- mensajes de error -->
			<div class="alert alert-danger" v-if="errors.length > 0">
				<div class="container">
					<div class="alert-icon">
						<i class="now-ui-icons objects_support-17"></i>
					</div>
					<strong>Cuidado!</strong> Debe verificar los siguientes errores antes de continuar:
					<button type="button" class="close" data-dismiss="alert" aria-label="Close" @click.prevent="errors = []">
						<span aria-hidden="true">
							<i class="now-ui-icons ui-1_simple-remove"></i>
						</span>
					</button>
					<ul>
						<li v-for="error in errors" :key="error">{{ error }}</li>
					</ul>
				</div>
			</div>
			<!-- mensajes de error -->
			<div class="row">
				<div class="col-4" id="budgetAvailabilityInitDate">
					<label><strong>Desde:</strong></label>
					<div class="form-group is-required mt-2">
						<label class="control-label"
							>Partida Presupuestaria</label
						>
						<select2 
							v-model="initialCode"
							:options="budgetItemsArray"
						></select2>
					</div>
					<div class="form-group is-required mt-3">
						<label class="control-label">Desde</label>
						<input
							class="form-control input-sm"
							type="date"
							v-model="initialDate"
						/>
					</div>
				</div>
				<div class="col-4" id="budgetAvailabilityEndDate">
					<label><strong>Hasta:</strong></label>
					<div class="form-group is-required mt-2">
						<label class="control-label"
							>Partida Presupuestaria</label
						>
						<select2
							v-model="finalCode"
							:options="budgetItemsArray"
						></select2>
					</div>
					<div class="form-group is-required mt-3">
						<label class="control-label">Hasta</label
						><input
							class="form-control input-sm"
							type="date"
							v-model="finalDate"
						/>
					</div>
				</div>
				<div class="col-4" id="budgetAvailabilityWithoutMovements">
					<div class="form-group">
						<label class="text-center">
							<strong>Quitar cuentas sin movimientos</strong>
						</label>
						<div class="col-12 mt-4">
							<div class="form-check">
								<input
									v-model="accountsWithMovements"
									type="checkbox"
									class="form-check-input"
									id="checkbox"
								/>
							</div>
						</div>
					</div>
				</div>
				<div class="col-6 mt-4">
					<label for="">
						<div class="col-12 bootstrap-switch-mini">
							<input
								type="radio"
								name="project_centralized_action"
								value="project"
								id="sel_project"
								class="form-control bootstrap-switch bootstrap-switch-mini sel_pry_acc"
								data-on-label="SI"
								data-off-label="NO"
							/>
							Proyecto
						</div>
					</label>
					<div class="mt-4">
						<select2
							:options="budgetProjectsArray"
							v-model="project_id"
							id="project_id"
							@input="getSpecificActions('Project')"
							disabled
						></select2>
					</div>
				</div>
				<div class="col-6 mt-4">
					<label for="">
						<div class="col-12 bootstrap-switch-mini">
							<input
								type="radio"
								name="project_centralized_action"
								value="project"
								class="form-control bootstrap-switch bootstrap-switch-mini sel_pry_acc"
								id="sel_centralized_action"
								data-on-label="SI"
								data-off-label="NO"
							/>
							Acción Centralizada
						</div>
					</label>
					<div class="mt-4">
						<select2
							:options="budgetCentralizedActionsArray"
							v-model="centralized_action_id"
							@input="getSpecificActions('CentralizedAction')"
							id="centralized_action_id"
							disabled
						></select2>
					</div>
				</div>
				<div class="col-12">
					<div class="mt-4">
						<div class="form-group is-required">
							<label for="specific_action_id" class="control-label">Acción Específica</label>
							<select2 :options="specific_actions"
								v-model="specific_action_id"
								id="specific_action_id"
								disabled
							></select2>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="card-footer text-right">
			<button
				class="btn btn-primary btn-sm"
				data-toggle="tooltip"
				title="Generar Reporte"
				@click="generateReport"
				id="budgetAvailabilityGenerateReport"
			>
				<span>Generar reporte</span>
				<i class="fa fa-print"></i>
			</button>
		</div>
	</div>
</template>
<script>
	export default {
		props: {
			budgetItems: {
				type: String,
				default: '[]'
			},
			budgetProjects: {
				type: String,
				default: '[]'
			},
			budgetCentralizedActions: {
				type: String,
				default: '[]'
			},
			url: {
				type: String,
				required: true
			}
		},
		data() {
			return {

				initialDate: '',
				finalDate: '',

				initialCode: 0,
				finalCode: 0,

				accountsWithMovements: false,

				project: '',
				centralized_action: '',

				project_id: '',
				centralized_action_id: '',

				specific_action_id: '',

				budgetItemsArray: JSON.parse(this.budgetItems),
				budgetProjectsArray: JSON.parse(this.budgetProjects),
				budgetCentralizedActionsArray: JSON.parse(this.budgetCentralizedActions),

				errors: [],
				specific_actions: []
			};
		},
		created() {
			this.project = false;
			this.centralized_action = false;
		},
		mounted() {
			const vm = this;

			$('.sel_pry_acc').on('switchChange.bootstrapSwitch', function(e) {
				$('#project_id').attr('disabled', e.target.id !== 'sel_project');
				$('#centralized_action_id').attr(
					'disabled',
					e.target.id !== 'sel_centralized_action'
				);

				if (e.target.id === 'sel_project') {
					$('#centralized_action_id')
						.closest('.form-group')
						.removeClass('is-required');
					$('#project_id')
						.closest('.form-group')
						.addClass('is-required');
				} else if (e.target.id === 'sel_centralized_action') {
					$('#centralized_action_id')
						.closest('.form-group')
						.addClass('is-required');
					$('#project_id')
						.closest('.form-group')
						.removeClass('is-required');
				}
			});
		},
		methods: {
			getSpecificActions(type) {
				let id = type === 'Project'
						? this.project_id
						: this.centralized_action_id;

				this.specific_actions = [];

				if (id) {
					axios.get(
						`${window.app_url}/budget/get-specific-actions/${type}/${id}/report`
					).then(response => {
						this.specific_actions = response.data;
					})
					.catch(error => {
						vm.logs(
							'BudgetSubSpecificFormulationComponent.vue',
							551,
							error,
							'getSpecificActions'
						);
					});
				}

				var len = this.specific_actions.length;
				$('#specific_action_id').attr('disabled', len == 0);
			},

			generateReport: function() {
				this.errors = [];
				if(!this.initialDate) {
					this.errors.push('El campo desde es obligatorio');
				}
				if(!this.finalDate) {
					this.errors.push('El campo hasta es obligatorio');
				}
				if(!this.initialCode) {
					this.errors.push('El campo Desde: Partida Presupuestario es obligatorio');
				}
				if(!this.finalCode) {
					this.errors.push('El campo Hasta: Partida Presupuestario es obligatorio');
				}
				if(!this.project_id && !this.centralized_action_id) {
					this.errors.push('El campo Proyecto o Acción Centralizada es obligatorio');
				}
				if(!this.specific_action_id) {
					this.errors.push('El campo Acción Específica es obligatorio');
				}
				

				if (this.errors.length === 0)
				{
					window.open(
					`${this.url}?initialDate=${this.initialDate}
					&finalDate=${this.finalDate}
					&initialCode=${this.initialCode}
					&finalCode=${this.finalCode}
					&accountsWithMovements=${this.accountsWithMovements}
					&project_id=${this.project_id ? this.project_id : this.centralized_action_id}
					&project_type=${this.project_id ? 'project' : 'centralized_action'}
					&specific_action_id=${this.specific_action_id}`);
				}
					
			},
		},
		watch: {
			specific_actions: function() {
				$('#specific_action_id').attr(
					'disabled',
					this.specific_actions.length <= 1
				);
			},
		}
	};
</script>
