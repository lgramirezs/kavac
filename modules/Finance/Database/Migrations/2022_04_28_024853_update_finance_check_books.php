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
        Schema::table('finance_check_books', function (Blueprint $table) {
          if (!Schema::hasColumn('finance_check_books', 'code')) {
            $table->dropColumn(['code']);
          }
          //se crea codigo como campo único
          $table->string('code')->unique()->comment('Código que identifica a la chequera');
          if (!Schema::hasColumn('finance_check_books', 'number')) {
            $table->dropColumn(['number']);
          }
          //se crea codigo number como campo único
          $table->string('number', 12)->unique()->comment('Numeración del cheque');

        });
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
