<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * @class UpdateFinanceCheckBooks
 * @brief [descripción detallada]
 *
 * [descripción corta]
 *
 * @author [autor de la clase] [correo del autor]
 *
 * @license
 *     [LICENCIA DE SOFTWARE CENDITEL](http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/)
 */
class UpdateFinanceCheckBooks extends Migration
{
    /**
     * Ejecuta las migraciones.
     *
     * @return void
     */
    public function up()
    {
      /*
        Schema::dropIfExists('finance_check_books');
        Schema::create('finance_check_books', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code')->unique()->comment('Código que identifica a la chequera');
            $table->string('number', 12)->unique()->comment('Numeración del cheque');
            $table->boolean('used')->default(false)->comment('Determina si el cheque ya fue emitido');
            $table->boolean('annulled')->default(false)->comment('Determina si el cheque se encuentra anulado');
            $table->foreignId('finance_bank_account_id')->constrained()->onDelete('restrict')->onUpdate('cascade');
            $table->timestamps();
            $table->softDeletes()->comment('Fecha y hora en la que el registro fue eliminado');
            $table->unique(['code', 'number', 'finance_bank_account_id'])->comment('Clave única para el registro');
        });
        */
    }

    /**
     * Revierte las migraciones.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('finance_check_books', function (Blueprint $table) {
            if (Schema::hasColumn('finance_check_books', 'code')) {
                $table->dropColumn('code');
            }
            if (Schema::hasColumn('finance_check_books', 'number')) {
                $table->dropColumn('number');
            }
        });
    }
}
