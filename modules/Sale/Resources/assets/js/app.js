/**
*--------------------------------------------------------------------------
* App Scripts
*--------------------------------------------------------------------------
*
* Scripts del Modulo de Commercialización a compilar por la aplicación
*/

/**
 * Componente para listar, crear, actualizar y borrar datos de formas de cobro
 *
 * @author Miguel Narvaez <mnarvaez@cenditel.gob.ve>
 */
Vue.component('sale-payment-method', () => import(
    /* webpackChunkName: "sale-payment-method" */
    './components/settings/SalePaymentMethodComponent.vue')
);

/**
 * Componente para listar, crear, actualizar y borrar datos de almacén
 *
 * @author Miguel Narvaez <mnarvaez@cenditel.gob.ve>
 */
Vue.component('sale-warehouse-method', () => import(
    /* webpackChunkName: "sale-warehouse-method" */
    './components/settings/SaleWarehouseMethodComponent.vue')
);

/**
 * Componente para listar, crear, actualizar y borrar datos de Lista de Subservicios
 *
 * @author Miguel Narvaez <mnarvaez@cenditel.gob.ve>
 */
Vue.component('sale-list-subservices-method', () => import(
    /* webpackChunkName: "sale-warehouse-method" */
    './components/settings/SaleListSubservicesMethod.vue')
);

/**
 * Componente para gestionar los pedidos
 *
 * @author José Puentes <jpuentes@cenditel.gob.ve>
 */
Vue.component('register-order-create', () => import(
    /* webpackChunkName: "register-clients" */
    './components/order/SaleOrderComponentCreate.vue')
);

Vue.component('register-order-pending-list', () => import(
    /* webpackChunkName: "register-clients" */
    './components/order/SaleOrderComponentPendingList.vue')
);

Vue.component('register-order-approved-list', () => import(
    /* webpackChunkName: "register-clients" */
    './components/order/SaleOrderComponentApprovedList.vue')
);

Vue.component('register-order-rejected-list', () => import(
    /* webpackChunkName: "register-clients" */
    './components/order/SaleOrderComponentRejectedList.vue')
);

/**
 * Componente para gestionar los clientes
 *
 * @author José Puentes <jpuentes@cenditel.gob.ve>
 */
Vue.component('register-clients', () => import(
    /* webpackChunkName: "register-clients" */
    './components/settings/SaleClientsComponent.vue')
);

Vue.component('sale-settings-charge-money', () => import(
    /* webpackChunkName: "form-payment" */
    './components/settings/SaleChargeMoneyComponent.vue')
);

Vue.component('sale-settings-form-payment', () => import(
    /* webpackChunkName: "form-payment" */
    './components/settings/SaleFormPaymentComponent.vue')
);
/**
 * Componente para listar, crear, actualizar y borrar datos de los productos
 *
 * @author Daniel Contreras <dcontreras@cenditel.gob.ve>
 */
Vue.component('sale-setting-product', () => import(
    /* webpackChunkName: "sale-setting-product" */
    './components/settings/SaleSettingProductComponent.vue')
);

/**
 * Componente para listar, crear, actualizar y borrar datos de los productos
 *
 * @author Daniel Contreras <dcontreras@cenditel.gob.ve>
 */
Vue.component('sale-setting-product-type', () => import(
    /* webpackChunkName: "sale-setting-product-type" */
    './components/settings/SaleSettingProductTypeComponent.vue')
);

/**
 * Componente para listar, crear, actualizar y borrar datos de Descuento
 *
 * @author Miguel Narvaez <mnarvaez@cenditel.gob.ve>
 */
Vue.component('sale-discount', () => import(
    /* webpackChunkName: "sale-discount" */
    './components/settings/SaleDiscountComponent.vue')
);

/**
 * Componentes para gestionar los ingresos de productos al almacén
 *
 * @author Daniel Contreras <dcontreras@cenditel.gob.ve>
 */
Vue.component('sale-warehouse-reception-create', () => import(
    /* webpackChunkName: "sale-warehouse-reception-create" */
    './components/receptions/SaleWarehouseReceptionCreateComponent.vue')
);

/**
 * Componente para mostrar un listado de los ingresos de productos al almacén
 *
 * @author Daniel Contreras <dcontreras@cenditel.gob.ve>
 */
Vue.component('sale-warehouse-reception-list', () => import(
    /* webpackChunkName: "sale-warehouse-reception-list" */
    './components/receptions/SaleWarehouseReceptionListComponent.vue')
);

/**
 * Componente para mostrar un listado de los ingresos de productos al almacén pendientes por ejecutar
 *
 * @author Daniel Contreras <dcontreras@cenditel.gob.ve>
 */
Vue.component('sale-warehouse-reception-pending-list', () => import(
    /* webpackChunkName: "sale-warehouse-reception-pending-list" */
    './components/receptions/SaleWarehouseReceptionPendingListComponent.vue')
);

/**
 * Componente para mostrar la información de los ingresos de almacén
 *
 * @author Daniel Contreras <dcontreras@cenditel.gob.ve>
 */
Vue.component('sale-warehouse-reception-info', () => import(
    /* webpackChunkName: "sale-warehouse-reception-info" */
    './components/receptions/SaleWarehouseReceptionInfoComponent.vue')
);

/**
 * Componente para listar, crear, actualizar y borrar datos de las formas de pago
 *
 * @author Daniel Contreras <dcontreras@cenditel.gob.ve>
 */
Vue.component('sale-setting-deposit', () => import(
    /* webpackChunkName: "sale-setting-deposit" */
    './components/settings/SaleSettingDepositComponent.vue')
);

/**
 * Componente para listar, crear, actualizar y borrar datos de Gestión de Pedidos
 *
 * @author Miguel Narvaez <mnarvaez@cenditel.gob.ve>
 */
Vue.component('sale-order-management-method', () => import(
    /* webpackChunkName: "sale-ordermanagement-method" */
    './components/settings/SaleOrderManagementMethodComponent.vue')
);

/**
 * Componente para gestionar la creación de los reportes de almacén
 *
 * @author Daniel Contreras <dcontreras@cenditel.gob.ve>
 */
Vue.component('sale-report-products', () => import(
    /* webpackChunkName: "sale-report-products" */
    './components/reports/SaleReportProductsComponent.vue')
);

/**
 * Componentes para gestionar la creación de facturas
 *
 * @author Daniel Contreras <dcontreras@cenditel.gob.ve>
 */
Vue.component('sale-bill-create', () => import(
    /* webpackChunkName: "sale-bill-create" */
    './components/bills/SaleBillCreateComponent.vue')
);

/**
 * Componente para mostrar un listado de las facturas
 *
 * @author Daniel Contreras <dcontreras@cenditel.gob.ve>
 */
Vue.component('sale-bill-list', () => import(
    /* webpackChunkName: "sale-bill-list" */
    './components/bills/SaleBillListComponent.vue')
);

/**
 * Componente que permite filtrar las facturas registradas en el sistema.
 *
 * @author Ing. Argenis Osorio <aosorio@cenditel.gob.ve>
 */
Vue.component('sale-report-bill-list', () => import(
    /* webpackChunkName: "sale-report-bill-list" */
    './components/reports/SaleReportBillListComponent.vue')
);

/**
 * Componente para mostrar la información de las solicitudes de almacén
 *
 * @author Daniel Contreras <dcontreras@cenditel.gob.ve>
 */
Vue.component('sale-bill-info', () => import(
    /* webpackChunkName: "sale-bill-info" */
    './components/bills/SaleBillInfoComponent.vue')
);

/**
 * Componente para mostrar un listado de las facturas pendientes
 *
 * @author Daniel Contreras <dcontreras@cenditel.gob.ve>
 */
Vue.component('sale-bill-pending-list', () => import(
    /* webpackChunkName: "sale-bill-pending-list" */
    './components/bills/SaleBillPendingListComponent.vue')
);

/**
 * Componente para aprobar las facturas pendientes
 *
 * @author Daniel Contreras <dcontreras@cenditel.gob.ve>
 */
Vue.component('sale-bill-accept-pending', () => import(
    /* webpackChunkName: "sale-bill-accept-pending" */
    './components/bills/SaleBillAcceptPendingComponent.vue')
);

/**
 * Componente para rechazar las facturas pendientes
 *
 * @author Daniel Contreras <dcontreras@cenditel.gob.ve>
 */
Vue.component('sale-bill-rejected-pending', () => import(
    /* webpackChunkName: "sale-bill-rejected-pending" */
    './components/bills/SaleBillRejectedPendingComponent.vue')
);

/**
 * Componente para mostrar un listado de las facturas aprobadas
 *
 * @author Daniel Contreras <dcontreras@cenditel.gob.ve>
 */
Vue.component('sale-bill-approved-list', () => import(
    /* webpackChunkName: "sale-bill-approved-list" */
    './components/bills/SaleBillApprovedListComponent.vue')
);

/**
 * Componente para mostrar un listado de las facturas rechazadas
 *
 * @author Daniel Contreras <dcontreras@cenditel.gob.ve>
 */
Vue.component('sale-bill-rejected-list', () => import(
    /* webpackChunkName: "sale-bill-rejected-list" */
    './components/bills/SaleBillRejectedListComponent.vue')
);

/**
 * Componente para mostrar un listado de las facturas emitidas por 
 pagos de anticipo
 *
 * @author Daniel Contreras <dcontreras@cenditel.gob.ve>
 */
Vue.component('sale-bill-advance-payment-list', () => import(
    /* webpackChunkName: "sale-bill-advance-payment-list" */
    './components/bills/SaleBillAdvancePaymentListComponent.vue')
);

/*
 * Componente para gestionar la creación de los reportes de Pedidos
 *
 * @author Miguel Narvaez <mnarvaez@cenditel.gob.ve>
 */
Vue.component('sale-report-orders', () => import(
    /* webpackChunkName: "sale-report-products" */
    './components/reports/SaleReportOrdersComponent.vue')
);

/**
 * Componente para gestionar la creación de los tipos de bien
 *
 * @author Daniel Contreras <dcontreras@cenditel.gob.ve>
 */
Vue.component('sale-type-good', () => import(
    /* webpackChunkName: "sale-type-good" */
    './components/settings/SaleTypeGoodComponent.vue')
);

/**
 * Componente para mostrar un listado de las solicitudes de servicios
 *
 * @author Daniel Contreras <dcontreras@cenditel.gob.ve>
 */
Vue.component('sale-service-list', () => import(
    /* webpackChunkName: "sale-service-list" */
    './components/services/SaleServiceListComponent.vue')
);

/**
 * Componente para registrar las solicitudes de servicios
 *
 * @author Daniel Contreras <dcontreras@cenditel.gob.ve>
 */
Vue.component('sale-service-create', () => import(
    /* webpackChunkName: "sale-service-list" */
    './components/services/SaleServiceCreateComponent.vue')
);

/**
 * Componente para mostrar la información asociada a una solicitud de servicios
 *
 * @author Daniel Contreras <dcontreras@cenditel.gob.ve>
 */
Vue.component('sale-service-info', () => import(
    /* webpackChunkName: "sale-service-info" */
    './components/services/SaleServiceInfoComponent.vue')
);

/**
 * Componente para mostrar un listado de las solicitudes de servicios pendientes
 *
 * @author Daniel Contreras <dcontreras@cenditel.gob.ve>
 */
Vue.component('sale-service-pending-list', () => import(
    /* webpackChunkName: "sale-service-pending-list" */
    './components/services/SaleServicePendingListComponent.vue')
);

/**
 * Componente para mostrar aprobar o rechazar las solicitudes de servicios pendientes
 *
 * @author Daniel Contreras <dcontreras@cenditel.gob.ve>
 */
Vue.component('sale-service-pending', () => import(
    /* webpackChunkName: "sale-service-pending" */
    './components/services/SaleServicePendingComponent.vue')
);

/**
 * Componente para mostrar un listado de las solicitudes de servicios rechazadas
 *
 * @author Daniel Contreras <dcontreras@cenditel.gob.ve>
 */
Vue.component('sale-service-rejected-list', () => import(
    /* webpackChunkName: "sale-service-rejected-list" */
    './components/services/SaleServiceRejectedListComponent.vue')
);

/**
 * Componente para mostrar un listado de las propuestas técnicas de solicitudes de servicios
 *
 * @author Daniel Contreras <dcontreras@cenditel.gob.ve>
 */
Vue.component('sale-technical-proposal-list', () => import(
    /* webpackChunkName: "sale-technical-proposal-list" */
    './components/services/SaleTechnicalProposalListComponent.vue')
);

/**
 * Componente para completar las propuestas técnicas de solicitudes de servicios
 *
 * @author Daniel Contreras <dcontreras@cenditel.gob.ve>
 */
Vue.component('sale-technical-proposal-create', () => import(
    /* webpackChunkName: "sale-technical-proposal-create" */
    './components/services/SaleTechnicalProposalCreateComponent.vue')
);

/**
 * Componente para gestionar las frecuencias de tiempo
 *
 * Juan Vizcarrondo <jvizcarrondo@cenditel.gob.ve> | <juanvizcarrondo@gmail.com>
 */

Vue.component('sale-frecuencies', () => import(
    /* webpackChunkName: "sale-frecuencies-list" */
    './components/settings/SaleSettingFrecuenciesComponent.vue')
);

/**
 * Componente para gestionar los pagos comunes
 *
 * Juan Vizcarrondo <jvizcarrondo@cenditel.gob.ve> | <juanvizcarrondo@gmail.com>
 */
Vue.component('sale-periodic-cost', () => import(
    /* webpackChunkName: "sale-periodic-cost-list" */
    './components/settings/SalePeriodicCostComponent.vue')
);

/**
 * Componentes para gestionar Cotizaciones
 *
 * @author Juan Vizcarrondo <jvizcarrondo@cenditel.gob.ve> | <juanvizcarrondo@gmail.com>
 */
Vue.component('sale-quote-form', () => import(
    /* webpackChunkName: "sale-quote-create" */
    './components/quotes/SaleQuoteFormComponent.vue')
);

/**
 * Componente para mostrar un listado de Cotizaciones
 *
 * @author Juan Vizcarrondo <jvizcarrondo@cenditel.gob.ve> | <juanvizcarrondo@gmail.com>
 */
Vue.component('sale-quote-list', () => import(
    /* webpackChunkName: "sale-quote-list" */
    './components/quotes/SaleQuoteListComponent.vue')
);

/**
 * Componente para mostrar un listado de Cotizaciones
 *
 * @author Juan Vizcarrondo <jvizcarrondo@cenditel.gob.ve> | <juanvizcarrondo@gmail.com>
 */
Vue.component('sale-report-quote', () => import(
    /* webpackChunkName: "sale-report-quote" */
    './components/reports/SaleReportQuotesComponent.vue')
);

/**
 * Componente para gestionar bienes a Comercializar
 *
 * @author Miguel Narvaez <mnarvaez@cenditel.gob.ve>
 */

Vue.component('sale-goods-to-be-traded', () => import(
    /* webpackChunkName: "sale-goods-to-be-traded-list" */
    './components/settings/SaleGoodsToBeTradedComponent.vue')
);

/**
 * Componente para gestionar Pagos Registrados
 *
 * @author Miguel Narvaez <mnarvaez@cenditel.gob.ve>
 */

Vue.component('payment-registered-list', () => import(
    /* webpackChunkName: "payment-registered-list" */
    './components/payment/SalePaymentRegisteredListComponent.vue')
);

/**
 * Componente para registrar pagos
 *
 * @author Miguel Narvaez <mnarvaez@cenditel.gob.ve>
 */
Vue.component('payment-registered-create', () => import(
    /* webpackChunkName: "payment-registered-list" */
    './components/payment/SalePaymentCreateComponent.vue')
);

/**
 * Componente para gestionar Pagos Pendientes
 *
 * @author Miguel Narvaez <mnarvaez@cenditel.gob.ve>
 */

Vue.component('pending-payments-list', () => import(
    /* webpackChunkName: "payment-registered-list" */
    './components/payment/SalePendingPaymentsListComponent.vue')
);

/**
 * Componente para gestionar Pagos Aprobados
 *
 * @author Miguel Narvaez <mnarvaez@cenditel.gob.ve>
 */

Vue.component('approved-payments-list', () => import(
    /* webpackChunkName: "payment-registered-list" */
    './components/payment/SaleApprovedPaymentsListComponent.vue')
);

/**
 * Componente para gestionar Pagos Rehcazados
 *
 * @author Miguel Narvaez <mnarvaez@cenditel.gob.ve>
 */

Vue.component('rejected-payments-list', () => import(
    /* webpackChunkName: "payment-registered-list" */
    './components/payment/SaleRejectedPaymentsListComponent.vue')
);

/**
 * Componente para gestionar Pagos de anticvipos Aprobados
 *
 * @author Miguel Narvaez <mnarvaez@cenditel.gob.ve>
 */

Vue.component('approved-advance-payments-list', () => import(
    /* webpackChunkName: "payment-registered-list" */
    './components/payment/SaleApprovedAdvancePaymentsListComponent.vue')
);


/**
 * Componente para mostrar la información de los Pagos
 *
 * @author Miguel Narvaez <mnarvaez@cenditel.gob.ve>
 */
Vue.component('sale-payment-info', () => import(
    /* webpackChunkName: "sale-payment-info" */
    './components/payment/SalePaymentInfoComponent.vue')
);


/**
 * Componente para gestionar la creación de los reportes de Pagos
 *
 * @author Miguel Narvaez <mnarvaez@cenditel.gob.ve>
 */
Vue.component('sale-report-payment', () => import(
    /* webpackChunkName: "sale-report-payment" */
    './components/reports/SaleReportPaymentComponent.vue')
);


/*
 * Componente para gestionar la creación de los reportes de solicitud de servicios
 *
 * @author  Juan Rosas <jrosas@cenditel.gob.ve | juan.rosasr01@gmail.com>>
 */
Vue.component('sale-report-service-requests', () => import(
    /* webpackChunkName: "sale-report-service-requests" */
    './components/reports/SaleReportServiceRequestComponent.vue')
);

/**
 * Opciones de configuración global del módulo de Commercialización
 */
Vue.mixin({
	methods: {
		/**
		 * Obtiene los datos de las formas de cobro en la institucion
		 *
		 * @author Miguel Narvaez <mnarvaez@cenditel.gob.ve>
		 */
		getSalePaymentMethod() {
			const vm = this;
			vm.sale_payment_method = [];
			axios.get('/sale/get-paymentmethod').then(response => {
				vm.sale_payment_method = response.data;
			});
		},
		/**
		 * Obtiene los datos de las Listas de Subservicios
		 *
		 * @author Miguel Narvaez <mnarvaez@cenditel.gob.ve>
		 */
		getSaleListSubServicesMethod() {
			const vm = this;
			vm.sale_list_subservices_method = [];
			axios.get('/sale/get-listsubservicesmethod').then(response => {
				vm.sale_list_subservices_method = response.data;
			});
		},
		/**
		 * Obtiene los datos de los productos
		 *
		 * @author Daniel Contreras <dcontreras@cenditel.gob.ve>
		 */
		getSaleSettingProduct() {
			const vm = this;
			vm.sale_setting_product = [];
			axios.get('/sale/get-setting-product').then(response => {
				vm.sale_setting_product = response.data;
			});
		},
		/**
		 * Obtiene los datos de los tipos de productos
		 *
		 * @author Daniel Contreras <dcontreras@cenditel.gob.ve>
		 */
		getSaleSettingProductType() {
			const vm = this;
			vm.sale_setting_product_type = [];
			axios.get('/sale/get-setting-product-type').then(response => {
				vm.sale_setting_product_type = response.data;
			});
		},

		/**
		 * Obtiene los datos de los almacenes de comercialización
		 *
		 * @author Miguel Narvaez <mnarvaez@cenditel.gob.ve>
		 */
		getSaleWarehouseMethod() {
			const vm = this;
			vm.sale_warehouse_method = [];
			axios.get('/sale/get-salewarehousemethod').then(response => {
				vm.sale_warehouse_method = response.data;
			});
		},

		/**
		 * Obtiene los datos de las formas de Descuento
		 *
		 * @author Miguel Narvaez <mnarvaez@cenditel.gob.ve>
		 */
		getSaleDiscount() {
			const vm = this;
			vm.sale_descount_method = [];
			axios.get('/sale/get-saledescount').then(response => {
				vm.sale_descount_method = response.data;
			});
		},
		
		/**
		 * Obtiene los datos de las formas de pago
		 *
		 * @author Daniel Contreras <dcontreras@cenditel.gob.ve>
		 */
		getSaleSettingDeposit() {
			const vm = this;
			vm.sale_setting_deposit = [];
			axios.get('/sale/get-setting-deposit').then(response => {
				vm.sale_setting_deposit = response.data;
			});
		},

		/**
		 * Obtiene los datos de Gestión de Pedidos de comercialización
		 *
		 * @author Miguel Narvaez <mnarvaez@cenditel.gob.ve>
		 */
		getSaleOrderManagementMethod() {
			const vm = this;
			vm.sale_warehouse_method = [];
			axios.get('/sale/get-saleordermanagementmethod').then(response => {
				vm.sale_order_management_method = response.data;
			});
		},

		/**
		 * Obtiene un arreglo con las frecuencias
		 *
		 * @author Juan Vizcarrondo <jvizcarrondo@cenditel.gob.ve> | <juanvizcarrondo@gmail.com>
		 */
		getFrecuencies() {
			const vm = this;
			vm.frecuencies = [];
			axios.get('/sale/get-frecuencies').then(response => {
				vm.frecuencies = response.data;
			});
		},

		/**
		 * Obtiene un arreglo con las frecuencias
		 *
		 * @author Juan Vizcarrondo <jvizcarrondo@cenditel.gob.ve> | <juanvizcarrondo@gmail.com>
		 */
		getQuoteInventoryProducts() {
			const vm = this;
			vm.quote_inventory_products_list = [];
			axios.get('/sale/get-quote-inventory').then(response => {
				vm.quote_inventory_products_list = response.data;
			});
		},

		/**
		 * Obtiene un arreglo con los estados de las cotizaciones
		 *
		 * @author Juan Vizcarrondo <jvizcarrondo@cenditel.gob.ve> | <juanvizcarrondo@gmail.com>
		 */
		getQuoteStatus() {
			const vm = this;
			vm.quote_status_list = [];
			axios.get('/sale/get-quote-status').then(response => {
				vm.quote_status_list = response.data;
			});
		},

		/**
		 * Obtiene un arreglo con la lista de los subservicios en cotizaciones
		 *
		 * @author Juan Vizcarrondo <jvizcarrondo@cenditel.gob.ve> | <juanvizcarrondo@gmail.com>
		 */
		getQuoteListSubservices() {
			const vm = this;
			vm.quote_subservices_list = [];
			axios.get('/sale/get-quote-subservices').then(response => {
				vm.quote_subservices_list = response.data;
			});
		},

		/**
		 * Obtiene un arreglo con los impuestos en cotizaciones
		 *
		 * @author Juan Vizcarrondo <jvizcarrondo@cenditel.gob.ve> | <juanvizcarrondo@gmail.com>
		 */
		getQuoteTaxes() {
			const vm = this;
			vm.quote_taxes = [];
			axios.get('/sale/get-quote-taxes').then(response => {
				vm.quote_taxes = response.data;
			});
		},

		/**
		 * Obtiene un arreglo con los metodos de pago en cotizaciones
		 *
		 * @author Juan Vizcarrondo <jvizcarrondo@cenditel.gob.ve> | <juanvizcarrondo@gmail.com>
		 */
		getQuotePayments() {
			const vm = this;
			vm.quote_payments = [];
			axios.get('/sale/get-quote-payment').then(response => {
                               let quote_payment_id = vm.record.sale_payment_method_id;
				vm.quote_payments = response.data;
			});
		},

		/**
		 * Obtiene un arreglo con las unidades de medida en cotizaciones
		 *
		 * @author Juan Vizcarrondo <jvizcarrondo@cenditel.gob.ve> | <juanvizcarrondo@gmail.com>
		 */
		getQuoteMeasurementUnits() {
			const vm = this;
			vm.quote_measurement_units = [];
			axios.get('/sale/get-quote-measurement-units').then(response => {
				vm.quote_measurement_units = response.data;
			});
		},

		/**
		 * Obtiene un arreglo con la lista los productos para ser comercializados en cotizaciones
		 *
		 * @author Juan Vizcarrondo <jvizcarrondo@cenditel.gob.ve> | <juanvizcarrondo@gmail.com>
		 */
		getQuoteGoodsToBeTraded() {
			const vm = this;
			vm.quote_good_to_be_traded = [];
			axios.get('/sale/get-quote-sale-goods').then(response => {
				vm.quote_good_to_be_traded = response.data;
			});
		},

		/**
		 * Obtiene un arreglo con la lista con los clientes en cotizaciones
		 *
		 * @author Juan Vizcarrondo <jvizcarrondo@cenditel.gob.ve> | <juanvizcarrondo@gmail.com>
		 */
		getQuoteClients() {
			const vm = this;
			vm.quote_clients = [];
			axios.get('/sale/get-quote-clients').then(response => {
				vm.quote_clients = response.data;
			});
		},

		/**
		 * Obtiene un arreglo con la lista con los clientes con cotizaciones
		 *
		 * @author Juan Vizcarrondo <jvizcarrondo@cenditel.gob.ve> | <juanvizcarrondo@gmail.com>
		 */
		getQuoteWithClients() {
			const vm = this;
			vm.quote_with_clients = [];
			axios.get('/sale/get-quote-with-clients').then(response => {
				vm.quote_with_clients = response.data;
			});
		},
		/**
		 * Obtiene un arreglo con la lista con los clientes con cotizaciones
		 *
		 * @author Juan Vizcarrondo <jvizcarrondo@cenditel.gob.ve> | <juanvizcarrondo@gmail.com>
		 */
		getSaleQuoteYear() {
			const vm = this;
			vm.quote_years = [];
			axios.get('/sale/get-quote-years').then(response => {
				vm.quote_years = response.data;
			});
		},
		/**
		 * Obtiene un arreglo con la lista con los clientes con cotizaciones
		 *
		 * @author Juan Vizcarrondo <jvizcarrondo@cenditel.gob.ve> | <juanvizcarrondo@gmail.com>
		 */
		getSaleQuoteRangeDates() {
			const vm = this;
			vm.quote_range_dates = [];
			axios.get('/sale/get-quote-range-dates').then(response => {
				vm.quote_range_dates = response.data;
			});
		},


        /**
         * Obtiene los datos de Bienes a Comercializar
         *
         * @author Miguel Narvaez <mnarvaez@cenditel.gob.ve>
         */
        getSaleGoodsToBeTraded() {
            const vm = this;
            vm.good_to_be_traded = [];
            axios.get('/sale/get-salegoodstobetraded').then(response => {
                vm.sale_good_to_be_traded = response.data;
            });
        },

        /**
        * Abre una nueva ventana en el navegador
        *
        * @author Juan Rosas <jrosas@cenditel.gob.ve | juan.rosasr01@gmail.com>
        * @param  {string} url para la nueva ventana
        * @param  {string} type tipo de ventana que se desea abrir
        * @return {boolean} Devuelve falso si no se ha indicado alguna información requerida
        */
        OpenPdf(url, type){
            const vm = this;
            if (!url) {
                return;
            }
            vm.loading = true;
            axios.get(url).then(response=>{
                if (!response.data.result) {
                    vm.showMessage(
                            'custom', 'Error en conversión', 'danger', 'screen-error', response.data.message
                        );
                }else{
                    url = url.split('/pdf')[0];
                    url += '/'+response.data.id;
                    window.open(url, type);
                }
                vm.loading = false;
            })
        },

        /**
         * Obtiene los Estados del Pais seleccionado
         *
         * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
         */
        async getEstates() {
            const vm = this;
            vm.estates = [];
            if (vm.record.country_id) {
                await axios.get(`/get-estates/${vm.record.country_id}`).then(response => {
                    vm.estates = response.data;
                });
                if (vm.record.id) {
                    vm.record.estate_id = vm.record.parish.municipality.estate_id;
                }
            }
        },
        /**
         * Obtiene los Municipios del Estado seleccionado
         *
         * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
         */
        async getMunicipalities() {
            const vm = this;
            vm.municipalities = [];
            if (vm.record.estate_id) {
                await axios.get(`/get-municipalities/${vm.record.estate_id}`).then(response => {
                    vm.municipalities = response.data;
                });
                if (vm.record.id) {
                    vm.record.municipality_id = vm.record.parish.municipality_id;
                }
            }
        },
        /**
         * Obtiene las parroquias del municipio seleccionado
         *
         * @author William Páez <wpaez@cenditel.gob.ve>
         */
        async getParishes() {
            const vm = this;
            vm.parishes = [];
            if (vm.record.municipality_id) {
                await axios.get(`/get-parishes/${vm.record.municipality_id}`).then(response => {
                    vm.parishes = response.data;
                });
                if (vm.record.id) {
                    vm.record.parish_id = vm.record.parish.id;
                }
            }
        },

        /**
         * Método que establece los datos del registro seleccionado para el cual se desea mostrar detalles
         *
         * @method    setDetails
         *
         * @author     Ing. Roldan Vargas <rvargas@cenditel.gob.ve>
         * @author     Juan Rosas <jrosasr@cenditel.gob.ve>
         * @author     Daniel Contreras <dcontreras@cenditel.gob.ve>
         *
         * @param     string   ref       Identificador del componente
         * @param     integer  id        Identificador del registro seleccionado
         * @param     object  var_list  Objeto con las variables y valores a asignar en las variables del componente
         */
        setDetails(ref, id, modal ,var_list = null) {
            const vm = this;
            if (var_list) {
                for(var i in var_list){
                    vm.$refs[ref][i] = var_list[i];
                }
            }else{
                vm.$refs[ref].record = vm.$refs.tableResults.data.filter(r => {
                    return r.id === id;
                })[0];
            }
            vm.$refs[ref].id = id;

            $(`#${modal}`).modal('show');
        },
	},
});
