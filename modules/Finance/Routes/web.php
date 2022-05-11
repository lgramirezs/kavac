<?php

use Illuminate\Support\Facades\Route;

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
    Route::get('/', 'FinanceController@index')->name('finance.index');

    Route::group(['middleware' => 'permission:finance.setting.create'], function () {
        /** Ruta de acceso a parámetros de configuración del módulo */
        Route::get('settings', 'FinanceController@setting')->name('finance.setting.index');
        Route::post('settings', 'FinanceController@store')->name('finance.setting.store');
        /** Rutas para la gestión de entidades bancarias */
        Route::resource('banks', 'FinanceBankController', ['as' => 'finance']);
        /** Rutas para la gestión de agencias bancarias */
        Route::resource('banking-agencies', 'FinanceBankingAgencyController', ['as' => 'finance']);
        /** Rutas para la gestión de tipos de cuentas bancarias */
        Route::resource('account-types', 'FinanceAccountTypeController', ['as' => 'finance']);
        /** Rutas para la gestión de cuentas bancarias */
        Route::resource('bank-accounts', 'FinanceBankAccountController', ['as' => 'finance']);
        /** Rutas para la gestión de chequeras */
        Route::resource('check-books', 'FinanceCheckBookController', ['as' => 'finance', 'except' => ['edit']]);
        Route::get('check-books/edit/{id}', 'FinanceCheckBookController@edit')->name('finance.edit');








        Route::resource('payment_methods', 'FinancePaymentMethodsController', ['as' => 'finance']);
    });

    Route::resource('pay-orders', 'FinancePayOrderController', ['as' => 'finance']);

    Route::get('get-banks/', 'FinanceBankController@getBanks');
    Route::get('get-bank-info/{bank_id}', 'FinanceBankController@getBankInfo');
    Route::get('get-agencies/{bank_id?}', 'FinanceBankingAgencyController@getAgencies');
    Route::get('get-account-types', 'FinanceAccountTypeController@getAccountTypes');
    Route::get('get-accounts/{bank_id}', 'FinanceBankAccountController@getBankAccounts');
    Route::get('voucher-design', function () {
        return view('finance::vouchers.design');
    })->name('finance.voucher.design');
});
