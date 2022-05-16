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
        Route::get('check-books/edit/{id}', 'FinanceCheckBookController@edit')->name('finance.edit');
        /** Rutas para la gestión de los métodos de pago */
        Route::resource('payment_methods', FinancePaymentMethodsController::class, ['as' => 'finance']);
        /** Rutas para la gestión de la configuración de archivos de conciliación bancaria */
        Route::resource('setting-bank-reconciliation-files', FinanceSettingBankReconciliationFilesController::class, ['as' => 'finance']);
    });

    Route::resource('pay-orders', FinancePayOrderController::class, ['as' => 'finance']);
    Route::post(
        'pay-orders/documents/get-sources', 
        [FinancePayOrderController::class, 'getSourceDocuments']
    );
    Route::resource('payment-execute', FinancePaymentExecuteController::class, ['as' => 'finance']);

    Route::get('get-banks/', [FinanceBankController::class, 'getBanks']);
    Route::get('get-bank-info/{bank_id}', [FinanceBankController::class, 'getBankInfo']);
    Route::get('get-agencies/{bank_id?}', [FinanceBankingAgencyController::class, 'getAgencies']);
    Route::get('get-account-types', [FinanceAccountTypeController::class, 'getAccountTypes']);
    Route::get('get-accounts/{bank_id}', [FinanceBankAccountController::class, 'getBankAccounts']);
    Route::get('get-payment-methods', [FinancePaymentMethodsController::class, 'getPaymentMethods']);
    Route::get('voucher-design', function () {
        return view('finance::vouchers.design');
    })->name('finance.voucher.design');
});
