<template>
	<div class="form-horizontal">
		<div class="card-body">
			<!-- mensajes de error -->
			<div class="alert alert-danger" v-if="errors.length > 0">
				<div class="container">
					<div class="alert-icon">
						<i class="now-ui-icons objects_support-17"></i>
					</div>
					<strong>Cuidado!</strong> Debe verificar los siguientes
					errores antes de continuar:
					<button
						type="button"
						class="close"
						data-dismiss="alert"
						aria-label="Close"
						@click.prevent="errors = []"
					>
						<span aria-hidden="true">
							<i class="now-ui-icons ui-1_simple-remove"></i>
						</span>
					</button>
					<ul>
						<li v-for="error in errors" :key="error">
							{{ error }}
						</li>
					</ul>
				</div>
			</div>
			<!-- mensajes de error -->
			<div class="row">
				<div class="col-12 mt-4">
					<label for="all_specific_actions"
						>Reporte consolidado de Proyectos y Acciones
						Centralizadas</label
					>
					<div class="col-12 bootstrap-switch-mini">
						<input
							type="checkbox"
							name="consolidated"
							value="true"
							class="form-control bootstrap-switch"
							id="consolidated"
							data-on-label="SI"
							data-off-label="NO"
							v-model="consolidated"
						/>
					</div>
					<br />
					<hr />
				</div>
				<div class="row col-12" v-show="!consolidated">
					<div class="col-6 mt-4">
						<div class="col-12 bootstrap-switch-mini">
							<input
								type="radio"
								name="project_centralized_action"
								value="project"
								id="sel_project"
								class="
									form-control
									bootstrap-switch
									sel_pry_acc
								"
								data-on-label="SI"
								data-off-label="NO"
							/>
							<label>Proyecto</label>
						</div>
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
						<div class="col-12 bootstrap-switch-mini">
							<input
								type="radio"
								name="project_centralized_action"
								value="project"
								class="
									form-control
									bootstrap-switch
									sel_pry_acc
								"
								id="sel_centralized_action"
								data-on-label="SI"
								data-off-label="NO"
							/>
							<label>Acción Centralizada</label>
						</div>
						<div class="mt-4">
							<select2
								name="centralized_action"
								:options="budgetCentralizedActionsArray"
								v-model="centralized_action_id"
								@input="getSpecificActions('CentralizedAction')"
								id="centralized_action_id"
								disabled
							></select2>
						</div>
					</div>
					<div class="col-12 mt-4">
						<label for="all_specific_actions"
							>Seleccionar todas las acciones especificas de este
							Proyecto / Acción Centralizada</label
						>
						<div class="col-12 bootstrap-switch-mini">
							<input
								type="checkbox"
								name="all_specific_actions"
								value="true"
								class="form-control bootstrap-switch"
								id="all_specific_actions"
								data-on-label="SI"
								data-off-label="NO"
								v-model="all_specific_actions"
							/>
						</div>
					</div>
					<div
						class="col-12"
						id="all_specific_actions"
						v-if="!all_specific_actions"
					>
						<div class="mt-4">
							<label
								for="specific_action_id"
								class="control-label"
								>Acción Específica</label
							>
							<div
								class="form-group is-required"
								style="margin-top: -1.5rem"
							>
								<v-multiselect
									:options="specific_actions"
									track_by="text"
									:hide_selected="false"
									:selected="specific_actions_ids"
									v-model="specific_actions_ids"
								>
								</v-multiselect>
							</div>
						</div>
						<br />
						<hr />
					</div>
				</div>
				<div class="col-12" v-if="all_specific_actions"><br /></div>
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
						<label class="control-label">Desde:</label>
						<input
							type="date"
							class="form-control input-sm"
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
						<label class="control-label">Hasta:</label>
						<input
							type="date"
							class="form-control input-sm no-restrict"
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
			default: "[]",
		},
		budgetProjects: {
			type: String,
			default: "[]",
		},
		budgetCentralizedActions: {
			type: String,
			default: "[]",
		},
		url: {
			type: String,
			required: true,
		},
	},
	data() {
		return {
			initialDate: "",
			finalDate: "",

			initialCode: 0,
			finalCode: 0,

			accountsWithMovements: false,

			project_id: "",
			centralized_action_id: "",

			specific_actions_ids: [],
			all_specific_actions: false,

			consolidated: false,

			budgetItemsArray: JSON.parse(this.budgetItems),
			budgetProjectsArray: JSON.parse(this.budgetProjects),
			budgetCentralizedActionsArray: JSON.parse(
				this.budgetCentralizedActions
			),

			errors: [],
			specific_actions: [],
		};
	},
	created() {
		this.all_specific_actions = false;
		const vm = this;

		window.addEventListener("updateProjectId", (event) => {
			vm.project_id = event.value;
			vm.specific_actions_ids = [];
		});

		window.addEventListener("updateCentralizedActionId", (event) => {
			vm.centralized_action_id = event.value;
			vm.specific_actions_ids = [];
		});
	},
	mounted() {
		const vm = this;

		$(".sel_pry_acc").on("switchChange.bootstrapSwitch", function (e) {
			$("#project_id").attr("disabled", e.target.id !== "sel_project");
			$("#centralized_action_id").attr(
				"disabled",
				e.target.id !== "sel_centralized_action"
			);
			if (e.target.id === "sel_project") {
				// window.dispatchEvent(new CustomEvent("updateCentralizedActionId", {value: '' }));
				$("#all_specific_actions").bootstrapSwitch("state", false);
				vm.centralized_action_id = "";
				vm.specific_actions_ids = [];
				$("#centralized_action_id")
					.closest(".form-group")
					.removeClass("is-required");
				$("#project_id").closest(".form-group").addClass("is-required");
			} else if (e.target.id === "sel_centralized_action") {
				// window.dispatchEvent(new CustomEvent("updateProjectId", {value: '' }));
				vm.project_id = "";
				vm.specific_actions_ids = [];
				$("#all_specific_actions").bootstrapSwitch("state", false);
				$("#centralized_action_id")
					.closest(".form-group")
					.addClass("is-required");
				$("#project_id")
					.closest(".form-group")
					.removeClass("is-required");
			}
		});

		$("#all_specific_actions").on(
			"switchChange.bootstrapSwitch",
			function (event, state) {
				vm.all_specific_actions = state;
				if (vm.all_specific_actions) {
					for (
						let index = 1;
						index < vm.specific_actions.length;
						index++
					) {
						vm.specific_actions_ids.push(
							vm.specific_actions[index].id
						);
					}
				} else {
					vm.specific_actions_ids = [];
				}
			}
		);

		$("#consolidated").on(
			"switchChange.bootstrapSwitch",
			function (event, state) {
				vm.consolidated = state;
				$("#sel_project").bootstrapSwitch(
					"disabled",
					vm.consolidated !== false
				);
				$("#sel_centralized_action").bootstrapSwitch(
					"disabled",
					vm.consolidated !== false
				);
				vm.project_id = "";
				vm.centralized_action_id = "";
				$("#project_id").attr("disabled", vm.consolidated !== false);
				$("#centralized_action_id").attr(
					"disabled",
					vm.consolidated !== false
				);

				if ($("#all_specific_actions").bootstrapSwitch("state")) {
					$("#all_specific_actions").bootstrapSwitch("state", false);
					vm.all_specific_actions = false;
				}
				$("#all_specific_actions").bootstrapSwitch(
					"disabled",
					vm.consolidated !== false
				);
			}
		);
	},
	methods: {
		reset() {
			const vm = this;
			vm.all_specific_actions = false;
			vm.specific_actions_ids = "";
			$("#all_specific_actions").bootstrapSwitch("state", false);
		},

		getSpecificActions(type) {
			let id =
				type === "Project"
					? this.project_id
					: this.centralized_action_id;

			this.specific_actions = [];

			if (id) {
				axios
					.get(
						`${window.app_url}/budget/get-specific-actions/${type}/${id}/report`
					)
					.then((response) => {
						this.specific_actions = response.data;
					})
					.catch((error) => {
						vm.logs(
							"BudgetSubSpecificFormulationComponent.vue",
							551,
							error,
							"getSpecificActions"
						);
					});
			}

			var len = this.specific_actions.length;
			$("#specific_action_id").attr("disabled", len == 0);
		},

		generateReport: function () {
			this.errors = [];
			if (!this.consolidated) {
				if (!this.initialDate) {
					this.errors.push("El campo fecha Desde es obligatorio");
				}
				if (!this.finalDate) {
					this.errors.push("El campo fecha Hasta es obligatorio");
				}
				if (!this.initialCode) {
					this.errors.push(
						"El campo Desde: Partida Presupuestario es obligatorio"
					);
				}
				if (!this.finalCode) {
					this.errors.push(
						"El campo Hasta: Partida Presupuestario es obligatorio"
					);
				}
				if (!this.project_id && !this.centralized_action_id) {
					this.errors.push(
						"El campo Proyecto o Acción Centralizada es obligatorio"
					);
				}
				if (!this.specific_actions_ids) {
					this.errors.push(
						"El campo Acción Específica es obligatorio"
					);
				} else {
					if (!this.all_specific_actions) {
						this.specific_actions_ids =
							this.specific_actions_ids.map(function (object) {
								return object.id;
							});
					}
				}
				let initialDate_ = new Date(this.initialDate);
				let finalDate_ = new Date(this.finalDate);

				if (initialDate_.getTime() >= finalDate_.getTime()) {
					this.errors.push("La fecha inicial es incorrecta");
				}

				if (this.errors.length === 0) {
					window.open(
						`${this.url}?initialDate=${this.initialDate}
						&finalDate=${this.finalDate}
						&initialCode=${this.initialCode}
						&finalCode=${this.finalCode}
						&accountsWithMovements=${this.accountsWithMovements}
						&project_id=${this.project_id ? this.project_id : this.centralized_action_id}
						&project_type=${this.project_id ? "project" : "centralized_action"}
						&specific_actions_ids=${this.specific_actions_ids}`
					);
					this.reset();
				}
			} else {
				let initialDate_ = new Date(this.initialDate);
				let finalDate_ = new Date(this.finalDate);

				if (initialDate_.getTime() >= finalDate_.getTime()) {
					this.errors.push("La fecha inicial es incorrecta");
				}

				if (!this.initialDate) {
					this.errors.push("El campo desde es obligatorio");
				}
				if (!this.finalDate) {
					this.errors.push("El campo hasta es obligatorio");
				}
				if (!this.initialCode) {
					this.errors.push(
						"El campo Desde: Partida Presupuestario es obligatorio"
					);
				}
				if (!this.finalCode) {
					this.errors.push(
						"El campo Hasta: Partida Presupuestario es obligatorio"
					);
				}

				let projects_ids = this.budgetProjectsArray
					.slice(1)
					.map((element) => {
						return element.id;
					});
				let centralized_actions_ids = this.budgetCentralizedActionsArray
					.slice(1)
					.map((element) => {
						return element.id;
					});

				if (this.errors.length === 0) {
					window.open(`${window.app_url}/budget/report/consolidated-pdf?
						initialDate=${this.initialDate}
						&finalDate=${this.finalDate}
						&initialCode=${this.initialCode}
						&finalCode=${this.finalCode}
						&accountsWithMovements=${this.accountsWithMovements}
						&projects_ids=${projects_ids}
						&centralized_actions_ids=${centralized_actions_ids}`);
				}
			}
		},
	},
	watch: {
		specific_actions: function () {
			$("#specific_action_id").attr(
				"disabled",
				this.specific_actions.length <= 1
			);
			// $('#all_specific_actions').bootstrapSwitch('state', false);
		},
	},
};
</script>
