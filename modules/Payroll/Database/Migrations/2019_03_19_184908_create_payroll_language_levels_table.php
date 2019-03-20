<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * @class CreatePayrollLanguageLevesTable
 * @brief Crear tabla de niveles de idioma
 *
 * Gestiona la creación o eliminación de la tabla de niveles de idioma
 *
 * @author William Páez <wpaez at cenditel.gob.ve>
 * @copyright <a href='http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/'>LICENCIA DE SOFTWARE CENDITEL</a>
 */
class CreatePayrollLanguageLevelsTable extends Migration
{
    /**
     * Método que ejecuta las migraciones
     *
     * @author William Páez <wpaez at cenditel.gob.ve>
     */
    public function up()
    {
        if (!Schema::hasTable('payroll_language_levels')) {
            Schema::create('payroll_language_levels', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 100)->comment('Nombre del nivel de dominio del idioma');
                $table->timestamps();
                $table->softDeletes()->comment('Fecha y hora en la que el registro fue eliminado');
            });
        }
    }

    /**
     * Método que elimina las migraciones
     *
     * @author William Páez (wpaez at cenditel.gob.ve)
     */
    public function down()
    {
        Schema::dropIfExists('payroll_language_levels');
    }
}
