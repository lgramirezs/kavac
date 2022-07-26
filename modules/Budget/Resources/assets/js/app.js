const { default: axios } = require('axios');

/**
 * Componente para mostrar listado del clasificador de cuentas presupuestarias
 *
 * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
 */
Vue.component('budget-accounts-list', () =>
	import(
		/* webpackChunkName: "budget-accounts-list" */
		'./components/BudgetAccountsListComponent.vue'
	)
);

/**
 * Componente para la gestión de los tipos de financiamiento.
 *
 * @author  Ing. Argenis Osorio <aosorio@cenditel.gob.ve>
 */
Vue.component('budget-financement-types', () => import(
    /* webpackChunkName: "budget-financement-types" */
    './components/BudgetFinancementTypesComponent.vue'
));

/**
 * Componente para la gestión de las fuentes de financiamiento.
 *
 * @author  Ing. Argenis Osorio <aosorio@cenditel.gob.ve>
 */
Vue.component('budget-financement-sources', () => import(
    /* webpackChunkName: "budget-financement-sources" */
    './components/BudgetFinancementSourcesComponent.vue'
));

/**
 * Componente para mostrar listado de proyectos
 *
 * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
 */
Vue.component('budget-list-projects', () =>
	import(
		/* webpackChunkName: "budget-list-projects" */
		'./components/BudgetProjectsListComponent.vue'
	)
);

/**
 * Componente para mostrar listado de acciones centralizadas
 *
 * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
 */
Vue.component('budget-centralized-actions-list', () =>
	import(
		/* webpackChunkName: "budget-centralized-actions-list" */
		'./components/BudgetCentralizedActionsListComponent.vue'
	)
);
/**
 * Componente para mostrar listado de acciones centralizadas
 *
 * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
 */
 Vue.component('budget-centralized-actions-info', () =>
 import(
	 /* webpackChunkName: "budget-centralized-actions-info" */
	 './components/BudgetCentralizedActionsInfoComponent.vue'
 )
);

/**
 * Componente para mostrar listado de acciones centralizadas
 *
 * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
 */
Vue.component('budget-specific-actions-list', () =>
	import(
		/* webpackChunkName: "budget-specific-actions-list" */
		'./components/BudgetSpecificActionsListComponent.vue'
	)
);

/**
 * Componente para detallar la información de acciones específicas
 *
 * @author Pedro Contreras <pdrocont@gmail.com>
 */
 Vue.component('budget-specific-actions-info', () =>
 import(
	 /* webpackChunkName: "budget-specific-actions-info" */
	 './components/BudgetSpecificActionsInfoComponent.vue'
 )
);

/**
 * Componente para detallar la información de acciones específicas
 *
 * @author Jesús Paredes <danielparedessotillo13@gmail.com>
 */
 Vue.component('budget-modification-list-data', () =>
 import(
	 /* webpackChunkName: "budget-modification-list-data" */
	 './components/BudgetModificationListComponentsData.vue'
 )
);

/**
 * Componente para detallar la información de acciones específicas
 *
 * @author Oscar Gonzales <>
 */
 Vue.component('budget-modification-list-reduction', () =>
 import(
	 /* webpackChunkName: "budget-modification-list-reduction" */
	 './components/BudgetReductionListComponent.vue'
 )
);
Vue.component('budget-reduction-modal', () =>
import(
	/* webpackChunkName: "budget-reduction-modal" */
	'./components/BudgetReductionListModelComponents.vue'
)
);

/**
 * Componente para detallar la información de acciones específicas
 *
 * @author Daniel Ordaz <danielordaz61@gmail.com>
 */
 Vue.component('budget-modification-list-transfer', () =>
 import(
	 /* webpackChunkName: "budget-modification-list-transfer" */
	 './components/BudgetTransferListComponent.vue'
 )
);
Vue.component('budget-transfer-modal', () =>
import(
	/* webpackChunkName: "budget-transfer-modal" */
	'./components/BudgetTransferListModelComponents.vue'
)
);

/**
 * Componente para mostrar listado de formulaciones de presupuesto
 *
 * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
 */
Vue.component('budget-formulation-list', () =>
	import(
		/* webpackChunkName: "budget-subspecific-formulation-list" */
		'./components/BudgetSubSpecificFormulationListComponent.vue'
	)
);

/**
 * Componente para mostrar formulario de formulación de presupuesto por sub específica
 *
 * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
 */
Vue.component('budget-formulation-subspecific', () =>
	import(
		/* webpackChunkName: "budget-formulation-subspecific" */
		'./components/BudgetSubSpecificFormulationComponent.vue'
	)
);

/**
 * Componente para getionar las modificaciones presupuestarias
 *
 * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
 *
 * @todo Problema al cargar con lazy load
 */
Vue.component('budget-mod', () =>
	import(
		/* webpackChunkName: "budget-modification" */
		'./components/BudgetModificationComponent.vue'
	)
);

/**
 * Componente para mostrar listado de créditos adicionales
 *
 * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
 *
 * @todo Problema al cargar con lazy load
 */
Vue.component('budget-mod-list', () =>
	import(
		/* webpackChunkName: "budget-modification-list" */
		'./components/BudgetModificationListComponent.vue'
	)
);

/**
 * Componente para agregar cuentas al registro o actualización de créditos adicionales
 *
 * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
 */
//Vue.component('budget-aditional-credit-add', require('./components/BudgetAditionalCreditAddComponent.vue').default);

/**
 * Componente para mostrar listado de compromisos
 *
 * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
 */
Vue.component('budget-compromise-list', () =>
	import(
		/* webpackChunkName: "budget-compromises-list" */
		'./components/BudgetCompromisesListComponent.vue'
	)
);

/**
 * Componente para getionar los compromisos presupuestarios
 *
 * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
 */
Vue.component('budget-compromise', () =>
	import(
		/* webpackChunkName: "budget-compromise" */
		'./components/BudgetCompromiseComponent.vue'
	)
);

/**
 * Componente para mostrar listado de compromisos
 *
 * @author Daniel Contreras <dcontreras@cenditel.gob.ve> | <exodiadaniel@gmail.com>
 */
Vue.component('budget-compromise-info', () =>
    import(
        /* webpackChunkName: "budget-compromise-info" */
        './components/BudgetCompromiseInfoComponent.vue'
    )
);

/**
 * Componente para mostrar el formulario de disponibilidad presupuestaria
 *
 * @author Jonathan Alvarado <wizardx1407@gmail.com> | <jonathanalvarado1407@gmail.com>
 */
Vue.component('budget-availability', () =>
	import(
		/* webpackChunkName: "budget-availability" */
		'./components/BudgetAvailabilityComponent.vue'
	)
);

/**
 * Componente para mostrar lista de proyectos
 *
 * @author Jonathan Alvarado <wizardx1407@gmail.com> | <jonathanalvarado1407@gmail.com>
 */
Vue.component('budget-projects-report', () =>
	import(
		/* webpackChunkName: "budget-projects-list-report" */
		'./components/reports/BudgetProjectsReportComponent.vue'
	)
);

/**
 * Componente para mostrar lista de proyectos
 *
 * @author Jonathan Alvarado <wizardx1407@gmail.com> | <jonathanalvarado1407@gmail.com>
 */
Vue.component('budget-formulated-report', () =>
	import(
		/* webpackChunkName: "budget-formulated-list-report" */
		'./components/reports/BudgetFormulatedReportComponent.vue'
	)
);

/**
 * Componente para mostrar lista de proyectos
 *
 * @author José Briceño <josejorgebriceno9@gmail.com> | <jonathanalvarado1407@gmail.com>
 */
 Vue.component('budget-analytical-major', () =>
 import(
	 /* webpackChunkName: "budget-formulated-list-report" */
	 './components/BudgetAnalyticalMajorComponent.vue'
 )
);

/**
 * Opciones de configuración global del módulo de presupuesto
 *
 * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
 */
Vue.mixin({
	data() {
		return {
			/** @type {String} Especifica el año de ejercicio presupuestario en curso */
			execution_year: ''
		};
	},
	methods: {
		async getSpecificActionDetail(id) {
			const response = await axios.get(
				`${window.app_url}/budget/detail-specific-actions/${id}`
			);
			return response.data;
		},
		async getAccountDetail(id) {
			const response = await axios.get(
				`${window.app_url}/budget/detail-accounts/${id}`
			);
			return response.data;
		},
	},
	mounted() {
		// Agregar instrucciones para determinar el año de ejecución
	}
});
