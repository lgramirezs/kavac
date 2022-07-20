/**
*--------------------------------------------------------------------------
* App Scripts
*--------------------------------------------------------------------------
*
* Scripts del Modulo de Bienes a compilar por la aplicación
*/


/**
 * Componente para mostrar listado del clasificador de Bienes
 *
 * @author Henry Paredes <hparedes@cenditel.gob.ve>
 */
Vue.component('asset-clasifications', () => import(
    /* webpackChunkName: "asset-clasifications" */
    './components/settings/AssetClasificationComponent.vue')
);

/**
 * Componente para la gestión de Tipos de Bienes
 *
 * @author Henry Paredes <hparedes@cenditel.gob.ve>
 */
Vue.component('asset-types', () => import(
    /* webpackChunkName: "asset-types" */
    './components/settings/AssetTypesComponent.vue')
);

/**
 * Componente para la gestión de las Categorías de Bienes
 *
 * @author Henry Paredes <hparedes@cenditel.gob.ve>
 */
Vue.component('asset-categories', () => import(
    /* webpackChunkName: "asset-categories" */
    './components/settings/AssetCategoriesComponent.vue')
);

/**
 * Componente para la gestión de las Subcategorías de Bienes
 *
 * @author Henry Paredes <hparedes@cenditel.gob.ve>
 */
Vue.component('asset-subcategories', () => import(
    /* webpackChunkName: "asset-subcategories" */
    './components/settings/AssetSubcategoriesComponent.vue')
);

/**
 * Componente para la gestión de las Categorías Específicas de Bienes
 *
 * @author Henry Paredes <hparedes@cenditel.gob.ve>
 */
Vue.component('asset-specific-categories', () => import(
    /* webpackChunkName: "asset-specific-categories" */
    './components/settings/AssetSpecificCategoriesComponent.vue')
);

/**
 * Componente para la gestión de las Condiciones Físicas de un Bien
 *
 * @author Henry Paredes <hparedes@cenditel.gob.ve>
 */
Vue.component('asset-conditions', () => import(
    /* webpackChunkName: "asset-conditions" */
    './components/settings/AssetConditionsComponent.vue')
);

/**
 * Componente para la gestión de los Status de Uso de un Bien
 *
 * @author Henry Paredes <hparedes@cenditel.gob.ve>
 */
Vue.component('asset-status', () => import(
    /* webpackChunkName: "asset-status" */
    './components/settings/AssetStatusComponent.vue')
);

/**
 * Componente para la gestión de la Función de Uso de un Bien
 *
 * @author Henry Paredes <hparedes@cenditel.gob.ve>
 */
Vue.component('asset-use-functions', () => import(
    /* webpackChunkName: "asset-use-functions" */
    './components/settings/AssetUseFunctionsComponent.vue')
);

/**
 * Componente para la gestión del Tipo de Adquisición de un Bien
 *
 * @author Henry Paredes <hparedes@cenditel.gob.ve>
 */
Vue.component('asset-acquisition-types', () => import(
    /* webpackChunkName: "asset-acquisition-types" */
    './components/settings/AssetAcquisitionTypesComponent.vue')
);

/**
 * Componente para gestionar las solicitudes de bienes institucionales
 *
 * @author Henry Paredes <hparedes@cenditel.gob.ve>
 */
Vue.component('asset-request-create', () => import(
    /* webpackChunkName: "asset-request-create" */
    './components/requests/AssetRequestCreateComponent.vue')
);
/**
 * Componente para mostrar la información de una solicitud registrada
 *
 * @author Henry Paredes <hparedes@cenditel.gob.ve>
 */
Vue.component('asset-show', () => import(
    /* webpackChunkName: "asset-show" */
    './components/requests/AssetRequestInfoComponent.vue')
);

/**
 * Componente para gestionar las asignaciones de bienes institucionales
 *
 * @author Henry Paredes <hparedes@cenditel.gob.ve>
 */
Vue.component('asset-asignation-create', () => import(
    /* webpackChunkName: "asset-asignation-create" */
    './components/asignations/AssetAsignationCreateComponent.vue')
);

/**
 * Componente para mostrar un listado de las asignaciones registradas
 *
 * @author Henry Paredes <hparedes@cenditel.gob.ve>
 */
Vue.component('asset-asignation-list', () => import(
    /* webpackChunkName: "asset-asignation-list" */
    './components/asignations/AssetAsignationListComponent.vue')
);

/**
 * Componente para mostrar la información de una asignacion registrada
 *
 * @author Henry Paredes <hparedes@cenditel.gob.ve>
 */
Vue.component('asset-asignation-info', () => import(
    /* webpackChunkName: "asset-assignation-info" */
    './components/asignations/AssetAsignationInfoComponent.vue')
);

/**
 * Componente para mostrar un listado de entregas registradas
 *
 * @author Henry Paredes <hparedes@cenditel.gob.ve>
 */
 Vue.component('asset-asignation-delivery-list', () => import(
    /* webpackChunkName: "asset-asignation-delivery-list" */
    './components/asignations/AssetAsignationDeliveryListComponent.vue')
);

/**
 * Componente para gestionar las desincorporaciones de bienes institucionales
 *
 * @author Henry Paredes <hparedes@cenditel.gob.ve>
 */
Vue.component('asset-disincorporation-create', () => import(
    /* webpackChunkName: "asset-desincorporation-create" */
    './components/disincorporations/AssetDisincorporationCreateComponent.vue')
);

/**
 * Componente para mostrar un listado de las desincorporaciones registradas
 *
 * @author Henry Paredes <hparedes@cenditel.gob.ve>
 */
Vue.component('asset-disincorporation-list', () => import(
    /* webpackChunkName: "asset-desincorporation-list" */
    './components/disincorporations/AssetDisincorporationListComponent.vue')
);

/**
 * Componente para mostrar la información de una desincorporación registrada
 *
 * @author Henry Paredes <hparedes@cenditel.gob.ve>
 */
Vue.component('asset-disincorporation-info', () => import(
    /* webpackChunkName: "asset-desincorporation-info" */
    './components/disincorporations/AssetDisincorporationInfoComponent.vue')
);

/**
 * Componente para gestionar el ingreso manual de bienes institucionales
 *
 * @author Henry Paredes <hparedes@cenditel.gob.ve>
 */
Vue.component('asset-create', () => import(
    /* webpackChunkName: "asset-create" */
    './components/registers/AssetCreateComponent.vue')
);

/**
 * Componente para mostrar un listado de bienes institucionales registrados
 *
 * @author Henry Paredes <hparedes@cenditel.gob.ve>
 */
Vue.component('asset-list', () => import(
    /* webpackChunkName: "asset-list" */
    './components/registers/AssetListComponent.vue')
);

/**
 * Componente para mostrar la información de un bien registrado
 *
 * @author Henry Paredes <hparedes@cenditel.gob.ve>
 */
Vue.component('asset-info', () => import(
    /* webpackChunkName: "asset-info" */
    './components/registers/AssetInfoComponent.vue')
);

/**
 * Componente para solicitar prorroga en la entrega de solicitudes registradas
 *
 * @author Henry Paredes <hparedes@cenditel.gob.ve>
 */
Vue.component('asset-extension', () => import(
    /* webpackChunkName: "asset-extension" */
    './components/requests/AssetRequestExtensionComponent.vue')
);

/**
 * Componente para la gestion de eventos ocurridos en equipos solicitados
 *
 * @author Henry Paredes <hparedes@cenditel.gob.ve>
 */
Vue.component('asset-events', () => import(
    /* webpackChunkName: "asset-events" */
    './components/requests/AssetRequestEventComponent.vue')
);

/**
 * Componente para mostrar un listado de las solicitudes registradas
 *
 * @author Henry Paredes <hparedes@cenditel.gob.ve>
 */
Vue.component('asset-request-list', () => import(
    /* webpackChunkName: "asset-request-list" */
    './components/requests/AssetRequestListComponent.vue')
);

/**
 * Componente para mostrar un listado de solicitudes pendientes registradas
 *
 * @author Henry Paredes <hparedes@cenditel.gob.ve>
 */
Vue.component('asset-request-list-pending', () => import(
    /* webpackChunkName: "asset-request-list-pending" */
    './components/requests/AssetRequestListPendingComponent.vue')
);

/**
 * Componente para mostrar un listado de solicitudes de entregas registradas
 *
 * @author Henry Paredes <hparedes@cenditel.gob.ve>
 */
Vue.component('asset-request-delivery-list', () => import(
    /* webpackChunkName: "asset-request-delivery-list" */
    './components/requests/AssetRequestDeliveryListComponent.vue')
);

/**
 * Componente para gestionar la creación de reportes de inventario
 *
 * @author Henry Paredes <hparedes@cenditel.gob.ve>
 */
Vue.component('asset-report-create', () => import(
    /* webpackChunkName: "asset-report-create" */
    './components/reports/AssetReportCreateComponent.vue')
);

/**
 * Componente para mostrar un listado del historico del inventario
 *
 * @author Henry Paredes <hparedes@cenditel.gob.ve>
 */
Vue.component('asset-inventory-history-list', () => import(
    /* webpackChunkName: "asset-inventory-history-list" */
    './components/inventories/AssetInventoryHistoryListComponent.vue')
);

/**
 * Componente para mostrar los gráficos del panel de control asociados al módulo de bienes
 *
 * @author Henry Paredes <hparedes@cenditel.gob.ve>
 */
Vue.component('asset-dashboard-graphs', () => import(
    /* webpackChunkName: "asset-dashboard-graphs" */
    './components/dashboard/AssetDashboardGraphsComponent.vue')
);

/**
 * Componente para mostrar la información de una operación asociada al módulo de bienes
 *
 * @author Henry Paredes <hparedes@cenditel.gob.ve>
 */
Vue.component('asset-operations-history-info', () => import(
    /* webpackChunkName: "asset-operations-history-info" */
    './components/dashboard/AssetOperationsHistoryInfoComponent.vue')
);

/**
 * Componente para mostrar un listado de las operaciones registradas
 *
 * @author Henry Paredes <hparedes@cenditel.gob.ve>
 */
Vue.component('asset-operations-history-list', () => import(
    /* webpackChunkName: "asset-operations-history-list" */
    './components/dashboard/AssetOperationsHistoryListComponent.vue')
);

/**
 * Componente para la gestión de gráficos estadísticos del módulo de bienes
 *
 * @author Henry Paredes <hparedes@cenditel.gob.ve>
 */
//Vue.component('asset-graph-charts', () => import(
    /* webpackChunkName: "asset-graph-charts" */
    //'./components/dashboard/AssetGraphChartsComponent.vue')
//);
Vue.component('asset-graph-charts', require('./components/dashboard/AssetGraphChartsComponent.vue').default);

/**
 * Opciones de configuración global del módulo de bienes
 *
 * @author Henry Paredes <hparedes@cenditel.gob.ve>
 */
Vue.mixin({
	methods: {
		/**
		 * Obtiene los datos de los tipos de bienes institucionales registrados
		 *
		 * @author Henry Paredes <hparedes@cenditel.gob.ve>
		 */
		async getAssetTypes() {
			const vm = this;
			await axios.get(`${window.app_url}/asset/get-types`).then(response => {
				vm.asset_types = response.data;
			});
            if ((vm.record.asset_type) && (vm.record.id)) {
                if (vm.record.asset_type_id == '') {
                    vm.record.asset_type_id = vm.record.asset_type.id;
                }
            }
		},
		/**
		 * Obtiene los datos de las categorias generales de los bienes institucionales registrados
		 *
		 * @author Henry Paredes <hparedes@cenditel.gob.ve>
		 */
		async getAssetCategories() {
			var vm = this;
			vm.asset_categories = [];

			if (vm.record.asset_type_id) {
				await axios.get(
                    `${window.app_url}/asset/get-categories/${vm.record.asset_type_id}`
                ).then(function (response) {
					vm.asset_categories = response.data;
				});
                if ((vm.record.asset_category) && (vm.record.id)) {
                    if (vm.record.asset_type_id == '') {
                        vm.record.asset_type_id = vm.record.asset_category.asset_type.id;
                    }
                    vm.record.asset_category_id = vm.record.asset_category.id;
                }
                if ((vm.record.asset_subcategory) && (vm.record.id)) {
                    if (vm.record.asset_type_id == '') {
                        vm.record.asset_type_id = vm.record.asset_subcategory.asset_category.asset_type.id;
                    }
                    vm.record.asset_category_id = vm.record.asset_subcategory.asset_category.id;
                }
			}
		},
		/**
		 * Obtiene los datos de las subcategorias de los bienes institucionales registrados
		 *
		 * @author Henry Paredes <hparedes@cenditel.gob.ve>
		 */
		async getAssetSubcategories() {
			var vm = this;
			vm.asset_subcategories = [];

			if (vm.record.asset_category_id) {
				await axios.get(
                    `${window.app_url}/asset/get-subcategories/${vm.record.asset_category_id}`
                ).then(function (response) {
					vm.asset_subcategories = response.data;
				});
                if ((vm.record.asset_subcategory) && (vm.record.id)) {
                    vm.record.asset_subcategory_id = vm.record.asset_subcategory.id;
                }
			}
		},
		/**
		 * Obtiene los datos de las categorias específicas de los bienes institucionales registrados
		 *
		 * @author Henry Paredes <hparedes@cenditel.gob.ve>
		 */
		async getAssetSpecificCategories() {
			var vm = this;
			vm.asset_specific_categories = [];

			if (vm.record.asset_subcategory_id) {
				await axios.get(
                    `${window.app_url}/asset/get-specific-categories/${vm.record.asset_subcategory_id}`
                ).then(function (response) {
					vm.asset_specific_categories = response.data;
				});
                if ((vm.record.asset_specific_category) && (vm.record.id)) {
                    vm.record.asset_specific_category_id = vm.record.asset_specific_category.id;
                }
			}
		},
		/**
		 *--------------------------------------------------------------------------
		 * Módulo Payroll
		 *--------------------------------------------------------------------------
		 *
		 * Operaciones del modulo de talento humano requeridas por el módulo de bienes
		 */

		// getPayrollPositionTypes() {
		// 	const vm = this;
		// 	vm.payroll_position_types = [];
		// 	axios.get(`${window.app_url}/asset/get-payroll-position-types`).then(response => {
		// 		vm.payroll_position_types = response.data;
		// 	});
		// },

		// getPayrollPositions() {
		// 	const vm = this;
		// 	vm.payroll_positions = [];
		// 	axios.get(`${window.app_url}/asset/get-payroll-positions`).then(response => {
		// 		vm.payroll_positions = response.data;
		// 	});
		// },

		getPayrollStaffs() {
			this.payroll_staffs = [];
			axios.get(`${window.app_url}/asset/get-payroll-staffs`).then(response => {
				this.payroll_staffs = response.data;
			});
		},

        getPayrollStaffInfo(id) {
			this.payroll_staff_info = [];
			axios.get(`${window.app_url}/asset/get-payroll-staffs-info/${id}`).then(response => {
                this.payroll_positions = [response.data[0]];
                this.payroll_position_types = [response.data[1]];
                this.departments = [response.data[2]];
			});
		},
	},
});
