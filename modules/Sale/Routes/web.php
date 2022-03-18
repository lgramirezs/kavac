<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * Grupo de rutas para el módulo de Comercialización
 */
Route::group(
    ['middleware' => ['web', 'auth', 'verified'], 'prefix' => 'sale'],
    function () {
        /**
         * -----------------------------------------------------------------------
         * Ruta para el panel de control del módulo de Comercialización
         * -----------------------------------------------------------------------
         *
         * Muestra información del módulo de Comercialización
         */
        Route::get('settings', 'SaleSettingController@index')->name('sale.settings.index');
        Route::post('settings', 'SaleSettingController@store')->name('sale.settings.store');

        /**
         * Panel de control referente a los pedidos
         */
        Route::resource('order', 'SaleOrderSettingController', ['only' => 'store']);
        Route::get('order/create', 'SaleOrderSettingController@create')->name('sale.order.create');
        Route::get('order', 'SaleOrderSettingController@index')->name('sale.order.index');
        Route::patch('order/{order}', 'SaleOrderSettingController@update');
        Route::delete('order/delete/{order}', 'SaleOrderSettingController@destroy')->name('sale.order.destroy');
        Route::get('order/edit/{order}', 'SaleOrderSettingController@edit')->name('sale.order.edit');
        Route::get('order/vue-list', 'SaleOrderSettingController@getListPending');
        Route::get('order/list-rejected', 'SaleOrderSettingController@getListRejected');
        Route::get('order/list-approved', 'SaleOrderSettingController@getListApproved');
        Route::get('order/info/{order}', 'SaleOrderSettingController@getOrderInfo');

        Route::resource(
          'approve-order',
          'SaleOrderSettingController',
          ['as' => 'order', 'except' => ['create','edit','show']]
        );

        Route::put(
          'order/rejected/{order}',
          'SaleOrderSettingController@rejectedOrder'
        );
        Route::put(
          'order/approved/{order}',
          'SaleOrderSettingController@approvedOrder'
        );
        Route::put(
          'order/delete/{order}',
          'SaleOrderSettingController@destroy'
        );

        /** Ruta que obtiene un array con los precios registrados, de acuerdo al producto seleccionado */
        Route::get('get-price-product/{id?}', 'SaleOrderSettingController@getPriceProduct');

        /**
         * -----------------------------------------------------------------------
         * Rutas para la configuración general del módulo de Comercialización
         * -----------------------------------------------------------------------
         *
         * Gestiona los datos de configuración del módulo de Comercialización
         */
        Route::resource(
            'register-clients',
            'SaleClientsController',
            ['as' => 'sale', 'except' => ['create','edit','show']]
        );

        Route::get(
            'get-sale-clients-rif',
            'SaleClientsController@getSaleClientsRif'
        )->name('sale.get-sale-clients-rif');

        Route::get(
            'get-sale-client/{id}',
            'SaleClientsController@getSaleClient'
        )->name('sale.get-sale-client');

        /**Route::resource(
            'payment-method',
            'SalePaymentMethodController',
            ['as' => 'sale', 'except' => ['create','edit','show']]
        );

        Route::get(
            'get-paymentmethod',
            'SalePaymentMethodController@getSalePaymentMethod'
        )->name('sale.get-sale-paymentmethod');*/

        Route::resource(
            'register-quote',
            'SaleQuoteController',
            ['as' => 'sale', 'except' => ['create','edit','show']]
        );

        /**
         * Gestión de los metodos de cobro
         */
        Route::resource(
            'register-charge-money',
            'SaleChargeMoneyController',
            ['as' => 'sale']
        );

        /**
         * Gestión de las formas de cobro
         */
        Route::resource(
            'register-form-payment',
            'SaleFormPaymentController',
            ['as' => 'sale']
        );

        Route::get(
            'get-form-payments',
            'SaleFormPaymentController@getSaleFormPayment'
        )->name('sale.get-sale-form-payment');

        /**
         * -----------------------------------------------------------------------
         * Rutas para la configuración general del módulo de Comercialización
         * -----------------------------------------------------------------------
         *
         * Gestiona los datos de configuración del módulo de Comercialización
         */
        Route::resource(
            'payment-method',
            'SalePaymentMethodController',
            ['as' => 'sale', 'except' => ['create','edit','show']]
        );
        Route::get(
            'get-paymentmethod',
            'SalePaymentMethodController@getSalePaymentMethod'
        )->name('sale.get-sale-paymentmethod');

        Route::resource(
            'setting-product-type',
            'SaleSettingProductTypeController',
            ['as' => 'sale', 'except' => ['create','edit','show']]
        );
        Route::get(
            'get-setting-product-type',
            'SaleSettingProductTypeController@getSaleSettingProductType'
        )->name('sale.get-sale-setting-product-type');
        Route::resource(
            'setting-deposit',
            'SaleSettingDepositController',
            ['as' => 'sale', 'except' => ['create','edit','show']]
        );
        Route::get(
            'get-setting-deposit',
            'SaleSettingDepositController@getSaleSettingDeposit'
        )->name('sale.get-sale-setting-deposit');

        Route::resource(
            'type-good',
            'SaleTypeGoodController',
            ['as' => 'sale', 'except' => ['create','edit','show']]
        );

        Route::get(
            'get-type-goods',
            'SaleTypeGoodController@getSaleTypeGoods'
        );

        Route::get(
            'get-type-good-attributes',
            'SaleTypeGoodController@getSaleTypeGoodsAttributes'
        )->name('sale.get-sale-type-good-attributes');

        /**
         * -----------------------------------------------------------------------
         * Rutas para la configuración de Almacen de Comercialización
         * -----------------------------------------------------------------------
         *
         * Gestiona los datos de configuración de Almacen de Comercialización
         */
        Route::resource(
            'warehouse-method',
            'SaleWarehouseController',
            ['as' => 'sale', 'except' => ['create','edit','show']]
        );
        Route::get(
            'get-salewarehousemethod',
            'SaleWarehouseController@getSaleWarehouseMethod'
        )->name('sale.get-sale-warehousemethod');

        /**
         * -----------------------------------------------------------------------
         * Rutas para la configuración de Descuento
         * -----------------------------------------------------------------------
         *
         * Gestiona los datos de configuración de Descuento de Comercialización
         */
        Route::resource(
            'discount-method',
            'SaleDiscountController',
            ['as' => 'sale', 'except' => ['create','edit','show']]
        );
        Route::get(
            'get-discountmethod',
            'SaleDiscountController@getSaleDiscount'
        )->name('sale.get-discountmethod');

        /**
         * ------------------------------------------------------------
         * Rutas para gestionar los Ingresos de Almacén
         * ------------------------------------------------------------
         */

        Route::resource('receptions', 'SaleWarehouseReceptionController', ['only' => 'store']);
        Route::patch('receptions/{reception}', 'SaleWarehouseReceptionController@update');
        Route::get('receptions', 'SaleWarehouseReceptionController@index')->name('sale.reception.index');
        Route::get('receptions/create', 'SaleWarehouseReceptionController@create')->name('sale.reception.create');
        Route::get('receptions/info/{reception}', 'SaleWarehouseReceptionController@vueInfo');
        Route::get('receptions/vue-list', 'SaleWarehouseReceptionController@vueList');

        Route::get(
            'receptions/edit/{reception}',
            'SaleWarehouseReceptionController@edit'
        )->name('sale.reception.edit');
        Route::delete(
            'receptions/delete/{reception}',
            'SaleWarehouseReceptionController@destroy'
        )->name('sale.reception.destroy');

        Route::put(
            'receptions/reception-rejected/{reception}',
            'SaleWarehouseReceptionController@rejectedReception'
        );
        Route::put(
            'receptions/reception-approved/{reception}',
            'SaleWarehouseReceptionController@approvedReception'
        );

        /**
         * ------------------------------------------------------------
         * Rutas para gestionar los Elementos select de reportes
         * ------------------------------------------------------------
         */

        Route::get('get-salewarehousemethod/{institution?}', 'SaleWarehouseController@getSaleWarehouseMethod');
        Route::get('get-sale-setting-product/{get-salewarehousemethod}', 'SaleSettingProductController@getSaleSettingProduct');
        Route::get('get-measurement-units', 'SaleWarehouseReceptionController@getMeasurementUnits');

        /**
         * -----------------------------------------------------------------------
         * Rutas para la configuración de Gestión de Pedidos.
         * -----------------------------------------------------------------------
         *
         * Gestiona los datos de configuración de Gestión de Pedidos.
         */
        Route::resource(
            'saleordermanagement-method',
            'SaleOrderManagementController',
            ['as' => 'sale', 'except' => ['create','edit','show']]
        );
        Route::get(
            'get-saleordermanagementmethod',
            'SaleOrderManagementController@getSaleOrderManagementMethod'
        )->name('sale.get-sale-saleordermanagementmethod');

        /**
         * ------------------------------------------------------------
         * Rutas para gestionar la generación de reportes en el Modulo de Almacén
         * ------------------------------------------------------------
         */

        Route::get('reports/inventory-products', 'SaleReportController@inventoryProducts')
            ->name('sale.report.inventory-products');
        Route::post('reports/inventory-products/vue-list', 'SaleReportController@vueList');
        Route::post('reports/inventory-products/create', 'SaleReportController@create');

        Route::get('reports/show/{code}', 'SaleReportController@show');

        /**

         * ---------------------------------------------------------------------------------
         * Rutas para gestionar la generación de facturas en el Modulo de Comercialización
         * ---------------------------------------------------------------------------------
         */

        Route::resource('bills', 'SaleBillController', ['only' => 'store']);
        Route::get('bills/create', 'SaleBillController@create')->name('sale.bills.create');
        Route::get('bills', 'SaleBillController@index')->name('sale.bills.index');
        Route::get('bills/vue-list', 'SaleBillController@vueList');
        Route::patch('bills/{bill}', 'SaleBillController@update');
        Route::get('bills/info/{bill}', 'SaleBillController@vueInfo');
        Route::get('bills/edit/{bill}', 'SaleBillController@edit')->name('sale.bills.edit');
        Route::delete('bills/delete/{bill}', 'SaleBillController@destroy')->name('sale.bills.destroy');
        Route::put('bills/bill-approved/{bill}', 'SaleBillController@approvedBill');
        Route::put('bills/bill-rejected/{bill}', 'SaleBillController@rejectedBill');
        Route::get('bills/vue-approved-list', 'SaleBillController@vueApprovedList');
        Route::get('bills/vue-rejected-list', 'SaleBillController@vueRejectedList');
        Route::get('get-bill-product/{product}/{id}', 'SaleBillController@getBillProduct');
        Route::get('bills/pdf/{id}', 'Reports\SaleBillController@pdf');

        /**
         * ---------------------------------------------------------------------------------
         * Rutas para gestionar los select de facturas en el Modulo de Comercialización
         * ---------------------------------------------------------------------------------
         */
        Route::get('get-bill-inventory-product', 'SaleBillController@getBillInventoryProducts');

        /*
         * ------------------------------------------------------------
         * Rutas para gestionar la generación de reportes en el Modulo de Pedidos
         * ------------------------------------------------------------
         */
        Route::get('reports/orders', 'SaleOrderReportController@listOrders')
            ->name('sale.report.orders');
        //Route::post('reports/orders/vue-list', 'SaleOrderReportController@vueList');

        /**
         * -----------------------------------------------------------------------
         * Rutas para la configuración de Lista de subservicios
         * -----------------------------------------------------------------------
         *
         * Gestiona los datos de configuración de Lista de subservicios
         */
        Route::resource(
            'list-subservices-method',
            'SaleListSubservicesController',
            ['as' => 'sale', 'except' => ['create','edit','show']]
        );
        Route::get(
            'get-salelistsubservicesmethod',
            'SaleListSubservicesController@getSaleListSubservicesMethod'
        )->name('sale.get-sale-listsubservicesmethod');

        /**
         * ---------------------------------------------------------------------------------
         * Rutas para gestionar la generación de solicitudes de servicios en el Modulo de Comercialización
         * ---------------------------------------------------------------------------------
         */

        Route::resource('services', 'SaleServiceController', ['as' => 'sale', 'except' => ['create','edit','show']]);
        Route::get('services/create', 'SaleServiceController@create')->name('sale.services.create');
        Route::get('services/vue-list', 'SaleServiceController@vueList');
        Route::get('services/edit/{id}', 'SaleServiceController@edit')->name('sale.services.edit');
        Route::get('services/info/{id}', 'SaleServiceController@vueInfo');
        Route::patch('services/{id}', 'SaleServiceController@update');
        Route::delete('services/delete/{id}', 'SaleServiceController@destroy');
        Route::put('services/service-approved/{id}', 'SaleServiceController@approved');
        Route::put('services/service-rejected/{id}', 'SaleServiceController@rejected');
        Route::get('services/vue-pending-list/{status}', 'SaleServiceController@vuePendingList');

        /**
         * ---------------------------------------------------------------------------------
         * Rutas para gestionar la generación de propuestas técnicas en el Modulo de Comercialización
         * ---------------------------------------------------------------------------------
         */

        Route::resource('technical-proposals', 'SaleTechnicalProposalController', ['as' => 'sale', 'except' => ['create','edit','show']]);
        Route::get('technical-proposals/complete/{id}', 'SaleTechnicalProposalController@saleCompleteTechnicalProposal')
             ->name('sale.technical-proposals.create');
        Route::get('technical-proposals/info/{id}', 'SaleTechnicalProposalController@vueInfo');
        Route::delete('technical-proposals/delete/{id}', 'SaleTechnicalProposalController@destroy');

        //Frecuency (periodicidad de tiempo)
        Route::resource(
            'frecuencies',
            'FrecuencyController',
            ['as' => 'sale', 'except' => ['create','edit','show']]
        );

        Route::get(
            'get-frecuencies',
            'FrecuencyController@getFrecuencies'
        )->name('sale.get-sale-frecuencies');

        //PeriodicCost (Costos fijos)
        Route::resource(
            'periodic-cost',
            'PeriodicCostController',
            ['as' => 'sale', 'except' => ['create','edit','show']]
        );

        Route::get(
            'get-periodic-cost',
            'PeriodicCostController@getPeriodicCostAttributes'
        )->name('sale.get-sale-periodic-cost');
        //Products (productos)
        Route::resource(
            'product',
            'SaleSettingProductController',
            ['as' => 'sale', 'except' => ['create','edit','show']]
        );

        Route::get(
            'get-setting-product',
            'SaleSettingProductController@getSaleSettingProduct'
        )->name('sale.get-sale-setting-product');

        /**
         * ---------------------------------------------------------------------------------
         * Rutas para gestionar la generación de cotizaciones en el Modulo de Comercialización
         * ---------------------------------------------------------------------------------
        */

        Route::resource('quotes', 'SaleQuoteController', ['only' => 'store']);
        Route::get('quotes/create', 'SaleQuoteController@create')->name('sale.quotes.create');
        Route::get('quotes', 'SaleQuoteController@index')->name('sale.quotes.index');
        Route::get('quotes/vue-list', 'SaleQuoteController@vueList');
        Route::patch('quotes/{SaleQuote}', 'SaleQuoteController@update');
        Route::get('quotes/info/{SaleQuote}', 'SaleQuoteController@vueInfo');
        Route::get('quotes/edit/{SaleQuote}', 'SaleQuoteController@edit')->name('sale.quotes.edit');
        Route::delete('quotes/delete/{SaleQuote}', 'SaleQuoteController@destroy')->name('sale.quotes.destroy');
        Route::put('quotes/quote-approved/{SaleQuote}', 'SaleQuoteController@approvedQuote');
        Route::put('quotes/quote-rejected/{SaleQuote}', 'SaleQuoteController@rejectedQuote');
        Route::get('quotes/vue-state-list/{state}', 'SaleQuoteController@vueStateList');
        Route::get('get-quote-taxes', 'SaleQuoteController@getTaxes');
        Route::get('get-quote-status', 'SaleQuoteController@getSaleQuoteStatus');
        Route::get('get-quote-subservices', 'SaleQuoteController@getSaleListSubservices');
        Route::get('get-quote-sale-goods', 'SaleQuoteController@getQuoteGoodsToBeTradeds');
        Route::get('get-quote-inventory', 'SaleQuoteController@getInventoryProducts');
        Route::get('get-quote-payment', 'SaleQuoteController@getSaleFormPayments');
        Route::get('get-quote-price-product/{id?}', 'SaleQuoteController@getPriceProduct');
        Route::get('get-quote-price-service/{id?}', 'SaleQuoteController@getPriceService');
        Route::get('get-quote-measurement-units', 'SaleQuoteController@getMeasurementUnits');
        Route::get('get-quote-clients', 'SaleQuoteController@getSaleClients');
        Route::get('get-quote-with-clients', 'SaleQuoteController@getSaleQuoteClients');
        Route::get('get-quote-years', 'SaleQuoteController@getSaleQuoteYear');
        Route::get('get-quote-range-dates', 'SaleQuoteController@getSaleQuoteRangeDates');
        Route::get('reports/quotes', 'Reports\SaleQuoteReportController@index')
            ->name('sale.report.quote');
        Route::post('reports/quotes/data', 'Reports\SaleQuoteReportController@quotesSearch');
        Route::get('reports/quotes/pdf/{ListIds}', 'Reports\SaleQuoteReportController@pdf');


        /*
         * ------------------------------------------------------------
         * Ruta para el panel de Pagos del módulo de Comercialización
         * ------------------------------------------------------------
         */
        Route::get('payment', 'SalePaymentController@index')->name('sale.payment.index');
        Route::get('payment/create', 'SalePaymentController@create')->name('payment.register.create');
        Route::post('payment/store', 'SalePaymentController@store')->name('payment.register.store');
        Route::get('payment/vue-list', 'SalePaymentController@vueList');
        Route::get('payment/payment_pending', 'SalePaymentController@pending');
        Route::get('payment/payment_approve', 'SalePaymentController@payment_approve');
        Route::get('payment/payment_rejected', 'SalePaymentController@payment_rejected');
        Route::get('payment/advance_define_attributes_approve', 'SalePaymentController@advance_define_attributes_approve');
        Route::get('payment/edit/{id}', 'SalePaymentController@edit')->name('sale.payment.edit');
        Route::delete('payment/delete/{id}', 'SalePaymentController@destroy')->name('sale.payment.delete');
        Route::get('payment/info/{id}', 'SalePaymentController@vueInfo');
        Route::put('payment/approvedPayment/{id}', 'SalePaymentController@approvedPayment');
        Route::put('payment/refusePayment/{id}', 'SalePaymentController@refusePayment');
        Route::get('payment/update/{id}', 'SalePaymentController@update');
        //imprime en pdf 
        Route::get('payment/pdf/{id}','SalePaymentController@PdfGenerator');
                
        Route::get(
            'get-sales-client/{id}',
            'SalePaymentController@getSaleClient'
        )->name('sale.get-sales-client');

        Route::get(
            'get-sales-to-be-trade/{id}',
            'SalePaymentController@getSaleGoodsToBeTraded'
        )->name('sale.get-sales-to-be-trade');  

        Route::get(
            'get-sales-service/{id}',
            'SalePaymentController@getSaleService'
        )->name('sale.get-sales-service');       

        Route::get('get-sale-order-list', 'SalePaymentController@getSaleOrderList');

        Route::get('get-sale-service-list', 'SalePaymentController@getSaleServiceList');

        Route::get('get-bank', 'SalePaymentController@getSaleBank');

        Route::get('get-currencie', 'SalePaymentController@getCurrencie');

        /*
         * ------------------------------------------------------------
         * Ruta para gestionar Bienes a Comercializar
         * ------------------------------------------------------------
         */
        Route::resource(
            'good_to_be_traded',
            'SaleGoodsToBeTradedController',
            ['as' => 'sale', 'except' => ['create','edit','show']]
        );

        Route::get(
            'get-goods-attributes',
            'SaleGoodsToBeTradedController@getSaleGoodsAttributes'
        )->name('sale.get-sale-goods-attribute');

        Route::get(
            'get-sale-goods',
            'SaleGoodsToBeTradedController@getSaleGoods'
        );

        /**
         * -------------------------------------------------------------------
         * Rutas para gestionar los Elementos select de bienes a comercializar
         * -------------------------------------------------------------------
         */
        Route::get('get-currencies', 'SaleGoodsToBeTradedController@getCurrencies');
        Route::get('get-departments', 'SaleGoodsToBeTradedController@getDepartments');
        Route::get('get-taxes', 'SaleGoodsToBeTradedController@getTaxes');
        Route::get('get-payroll-staffs', 'SaleGoodsToBeTradedController@getPayrollStaffs');
        
        /**
         * -------------------------------------------------------------------
         * Rutas para gestionar los Elementos select de la propuesta técnica
         * -------------------------------------------------------------------
         */
        Route::get('get-asignation-staffs', 'SaleTechnicalProposalController@getAsignationStaffs');

        /*
         * ------------------------------------------------------------
         * Rutas para gestionar la generación de reportes en el Modulo de Pagos
         * ------------------------------------------------------------
         */
        Route::get('reports/payment', 'SalePaymentReportController@listPayment')
            ->name('sale.report.payment');

        /*
         * ------------------------------------------------------------
         * Rutas para gestionar la generación de reportes en el Modulo de Solicitud de servicios
         * ------------------------------------------------------------
         */
        Route::get('reports/service-requests', 'Reports\SaleServiceRequestController@index',
            ['except' => ['create', 'store','edit','update','show', 'destroy']])
            ->name('sale.report.service-requests');

        Route::post('reports/service-requests/filter-records', 'Reports\SaleServiceRequestController@filterRecords');

        Route::get(
            'reports/service-requests/pdf/{value?}',
            'Reports\SaleServiceRequestController@pdf'
        );

        /*
         * ----------------------------------------------------------
         * Rutas para gestionar la generación de reportes de facturas
         * ----------------------------------------------------------
         */
        Route::get('reports/bills', 'Reports\SaleBillReportController@index')
        ->name('sale.report.bill');

        Route::post('reports/bills/filter-records', 'Reports\SaleBillReportController@filterRecords');

        Route::get(
            'reports/bills/pdf/{value?}',
            'Reports\SaleBillReportController@pdf'
        );
    }
);
