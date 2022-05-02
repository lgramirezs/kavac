<?php
namespace Database\Seeders;

use App\Models\Parameter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

/**
 * @class ParametersTableSeeder
 * @brief Información por defecto para parámetros del sistema
 *
 * Gestiona los parámetros por defecto a implementar en la aplicación
 *
 * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
 * @license 
 *      [LICENCIA DE SOFTWARE CENDITEL](http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/)
 */
class ParametersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $parameters = [
            ['p_key' => 'support', 'p_value' => 'false'],
            ['p_key' => 'chat', 'p_value' => 'false'],
            ['p_key' => 'notify', 'p_value' => 'false'],
            ['p_key' => 'report_banner', 'p_value' => 'false'],
            ['p_key' => 'multi_institution', 'p_value' => 'false'],
            ['p_key' => 'digital_sign', 'p_value' => 'false'],
            ['p_key' => 'online', 'p_value' => 'true'],
        ];

        DB::transaction(function () use ($parameters) {
            foreach ($parameters as $parameter) {
                Parameter::withTrashed()->updateOrCreate(
                    ['p_key' => $parameter['p_key']], 
                    ['p_value' => $parameter['p_value'], 'deleted_at' => null]
                );
            }
        });
    }
}
