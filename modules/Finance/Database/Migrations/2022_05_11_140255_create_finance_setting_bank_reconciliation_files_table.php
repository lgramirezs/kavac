<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * @class CreateFinanceSettingBankReconciliationFiles
 * 
 * @brief Configuraciones de los archivos de conciliación bancaria
 *
 * Clase que gestiona los métodos para la gestión de la configuración de
 * archivos de conciliación bancaria.
 *
 * @author Ing. Argenis Osorio <aosorio@cenditel.gob.ve>
 *
 * @license <a href='http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/'>
 *              LICENCIA DE SOFTWARE CENDITEL
 *          </a>
 */
class CreateFinanceSettingBankReconciliationFilesTable extends Migration
{
    /**
     * Ejecuta las migraciones.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finance_setting_bank_reconciliation_files', function (Blueprint $table) {
            $table->id();
            $table->string('bank_id')->comment('Id del banco');
            $table->boolean('read_start_line')->default(false)->comment('Indica si leerá la línea de inicio');
            $table->boolean('read_end_line')->default(false)->comment('Indica si leerá la línea final');
            $table->integer('position_reference_column')->nullable()->comment('Referencia');
            $table->integer('position_date_column')->nullable()->comment('Fecha');
            $table->integer('position_debit_amount_column')->nullable()->comment('Monto débito');
            $table->integer('position_credit_amount_column')->nullable()->comment('Monto crédito');
            $table->integer('position_description_column')->nullable()->comment('Descripción');
            $table->string('separated_by')->nullable()->comment('Columnas separadas por');
            $table->string('date_format')->nullable()->comment('Formato de fecha');
            $table->string('thousands_separator')->nullable()->comment('Separador de miles');
            $table->string('decimal_separator')->nullable()->comment('Separador de decimales');
            $table->timestamps();
            $table->softDeletes()->comment('Fecha y hora en la que el registro fue eliminado');
        });
    }

    /**
     * Revierte las migraciones.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('');
    }
}
