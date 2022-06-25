<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * @class AddFieldsToAssetRequestsTable
 * @brief [descripción detallada]
 *
 * [descripción corta]
 *
 * @author [autor de la clase] [correo del autor]
 *
 * @license
 *     [LICENCIA DE SOFTWARE CENDITEL](http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/)
 */
class AddFieldsToAssetRequestsTable extends Migration
{
    /**
     * Ejecuta las migraciones.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('asset_requests')){
            Schema::table('asset_requests', function (Blueprint $table) {
                if(!Schema::hasColumn('asset_requests', 'address')){
                    $table->text('address')->nullable()->comment('Dirección fisíca a donde se prestará el bien');
                    $table->foreignId('country_id')->nullable()->constrained()->onDelete('restrict')->onUpdate('cascade');
                    $table->foreignId('estate_id')->nullable()->constrained()->onDelete('restrict')->onUpdate('cascade');
                    $table->foreignId('municipality_id')->nullable()->constrained()->onDelete('restrict')->onUpdate('cascade');
                    $table->foreignId('parish_id')->nullable()->constrained()->onDelete('restrict')->onUpdate('cascade');
                }
            
            });
        }
        
    }

    /**
     * Revierte las migraciones.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('asset_requests', function (Blueprint $table) {
            
        });
    }
}
