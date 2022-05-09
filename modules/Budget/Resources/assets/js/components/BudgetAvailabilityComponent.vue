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
				
				<div class="col-6">
					<br>
					<div class="form-group">
						<label>Proyecto</label>
						<div class="col-12">
							<div class="col-4">
								<input id="sel_project"
									type="checkbox" class="form-check-input" 
									v-model="project" value="true"
								/>
								<br>
							</div>
						</div>
					</div>
					<div class="form-group">
                        <select2 :options="budgetProjectsArray" id="project_id" 
								  v-model="project_id">
                        </select2>
					</div>
				</div>

				<div class="col-6">
					<br>
					<div class="form-group">
						<label>Acción Centralizada</label>
						<div class="col-12">
							<div class="col-4">
								<input id="sel_centralized_action"
									type="checkbox" class="form-check-input" 
									v-model="centralized_action" value="true"
								/>
								<br>
							</div>
						</div>
					</div>
					<div class="form-group">
                        <select2 :options="budgetCentralizedActionsArray" id="centralized_action_id" 
								  v-model="centralized_action_id">
                        </select2>
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

				budgetItemsArray: JSON.parse(this.budgetItems),
				budgetProjectsArray: JSON.parse(this.budgetProjects),
				budgetCentralizedActionsArray: JSON.parse(this.budgetCentralizedActions),

				errors: []
			};
		},
		created() {
			this.project = false;
			this.centralized_action = false;
			console.log(this.project);
		},
		mounted() {
			const vm = this;
		},
		methods: {
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
				

				if (this.errors.length === 0)
				{
					window.open(
					`${this.url}?initialDate=${this.initialDate}
					&finalDate=${this.finalDate}
					&initialCode=${this.initialCode}
					&finalCode=${this.finalCode}
					&accountsWithMovements=${this.accountsWithMovements}
					&project_id=${this.project_id ? this.project_id : this.centralized_action_id}
					&project_type=${this.project_id ? 'project' : 'centralized_action'}`);
				}
					
			},
		},
		watch: {
			'project': function(newVal, oldVal) {
				if (newVal === true) {
					$("#sel_centralized_action").prop("disabled", true);
					$("#centralized_action_id").prop("disabled", true);
					this.centralized_action_id = '';
				} else {
					$("#sel_centralized_action").prop("disabled", false);
					$("#centralized_action_id").prop("disabled", false);
				}
			},
			'centralized_action' : function(newVal, oldVal) {
				if (newVal === true) {
					$("#sel_project").prop("disabled", true);
					$("#project_id").prop("disabled", true);
					this.project_id = '';
				} else {
					$("#sel_project").prop("disabled", false);
					$("#project_id").prop("disabled", false);
				}
			}
		}

	};
</script>
