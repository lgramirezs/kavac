<?php

use Illuminate\Support\Facades\Route;
use Modules\Finance\Http\Controllers\FinanceController;
use Modules\Finance\Http\Controllers\FinanceBankController;
use Modules\Finance\Http\Controllers\FinancePayOrderController;
use Modules\Finance\Http\Controllers\FinanceCheckBookController;
use Modules\Finance\Http\Controllers\FinanceAccountTypeController;
use Modules\Finance\Http\Controllers\FinanceBankAccountController;
use Modules\Finance\Http\Controllers\FinanceBankingAgencyController;
use Modules\Finance\Http\Controllers\FinancePaymentExecuteController;
use Modules\Finance\Http\Controllers\FinancePaymentMethodsController;
use Modules\Finance\Http\Controllers\FinanceSettingBankReconciliationFilesController;
use Modules\Finance\Http\Controllers\FinanceConciliationController;
use Modules\Finance\Http\Controllers\FinanceMovementsController;
use Modules\Finance\Models\FinancePaymentExecute;
use Modules\Finance\Models\FinancePayOrder;

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

Route::group([
    'middleware' => ['web', 'auth', 'verified'],
    'prefix' => 'finance'
], function () {
    Route::get('/', [FinanceController::class, 'index'])->name('finance.index');

    Route::group(['middleware' => 'permission:finance.setting.create'], function () {
        /** Ruta de acceso a parámetros de configuración del módulo */
        Route::get('settings', [FinanceController::class, 'setting'])->name('finance.setting.index');
        Route::post('settings', [FinanceController::class, 'store'])->name('finance.setting.store');
        /** Rutas para la gestión de entidades bancarias */
        Route::resource('banks', FinanceBankController::class, ['as' => 'finance']);
        /** Rutas para la gestión de agencias bancarias */
        Route::resource('banking-agencies', FinanceBankingAgencyController::class, ['as' => 'finance']);
        /** Rutas para la gestión de tipos de cuentas bancarias */
        Route::resource('account-types', FinanceAccountTypeController::class, ['as' => 'finance']);
        /** Rutas para la gestión de cuentas bancarias */
        Route::resource('bank-accounts', FinanceBankAccountController::class, ['as' => 'finance']);
        /** Rutas para la gestión de chequeras */
        Route::resource('check-books', FinanceCheckBookController::class, ['as' => 'finance', 'except' => ['edit']]);

        Route::get('get-bank-account/{bank_id}', [FinanceCheckBookController::class, 'getBanksAccounts']);

        Route::get('check-books/edit/{id}', 'FinanceCheckBookController@edit')->name('finance.edit');
        /** Rutas para la gestión de los métodos de pago */
        Route::resource('payment_methods', FinancePaymentMethodsController::class, ['as' => 'finance']);
        /** Rutas para la gestión de la configuración de archivos de conciliación bancaria */
        Route::resource('setting-bank-reconciliation-files', FinanceSettingBankReconciliationFilesController::class, ['as' => 'finance']);
    });

    /** Ruta para la gestión de Finanzas > Banco > Ordenes de pago */
    Route::get('pay-orders/pending/{receiver_id?}', [FinancePayOrderController::class, 'getPendingPayOrders'])
        ->name('finance.pay-order.pending');
    Route::get('pay-orders/vue-list', [FinancePayOrderController::class, 'vueList'])->name('finance.pay-order.vuelist');
    Route::post('pay-orders/change-document-status', [FinancePayOrderController::class, 'changeDocumentStatus'])
        ->name('finance.pay-order.change-document-status');
    Route::get('pay-orders/pdf/{financePayOrder}', [FinancePayOrderController::class, 'pdf']);
    Route::resource('pay-orders', FinancePayOrderController::class, ['as' => 'finance']);
    Route::post(
        'pay-orders/documents/get-sources', 
        [FinancePayOrderController::class, 'getSourceDocuments']
    );

    /** Ruta para la gestión de Finanzas > Banco > Emisiones de pago */
    Route::get(
        'payment-execute/list/get-receivers', [FinancePaymentExecuteController::class, 'getPayOrderReceivers']
    );
    Route::get('payment-execute/vue-list', [FinancePaymentExecuteController::class, 'vueList'])
        ->name('finance.payment-execute.vuelist');
    Route::get('payment-execute/pdf/{financePaymentExecute}', [FinancePaymentExecuteController::class, 'pdf']);
    Route::resource('payment-execute', FinancePaymentExecuteController::class, ['as' => 'finance']);

    /** Ruta para la gestión de Finanzas > Banco > Movimientos */
    Route::get('movements/vue-list', [FinanceMovementsController::class, 'vueList']);
    Route::get('movements/vue-info/{id}',[FinanceMovementsController::class, 'vueInfo']);
    Route::get('movements/edit/{id}',[FinanceMovementsController::class, 'edit']);
    Route::resource('movements', FinanceMovementsController::class, ['as' => 'finance']);

    /** Ruta para la gestión de Finanzas > Banco > Conciliación */
    Route::resource('conciliation', FinanceConciliationController::class, ['as' => 'finance']);
    Route::get('get-institution', [FinanceConciliationController::class, 'getInstitution']);

    Route::get('get-banks/', [FinanceBankController::class, 'getBanks']);
    Route::get('get-bank-info/{bank_id}', [FinanceBankController::class, 'getBankInfo']);
    Route::get('get-agencies/{bank_id?}', [FinanceBankingAgencyController::class, 'getAgencies']);
    Route::get('get-account-types', [FinanceAccountTypeController::class, 'getAccountTypes']);
    Route::get('get-accounts/{bank_id}', [FinanceBankAccountController::class, 'getBankAccounts']);
    Route::get('get-bank-accounts', [FinanceBankAccountController::class, 'getFinanceBankAccount']);
    Route::get('get-payment-methods', [FinancePaymentMethodsController::class, 'getPaymentMethods']);
    Route::get('voucher-design', function () {
        return view('finance::vouchers.design');
    })->name('finance.voucher.design');
});
