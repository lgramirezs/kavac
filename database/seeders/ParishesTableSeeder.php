<?php
namespace Database\Seeders;

use App\Models\Parish;

use App\Roles\Models\Role;
use App\Models\Municipality;
use Illuminate\Database\Seeder;
use App\Roles\Models\Permission;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * @class ParishTableSeeder
 * @brief Información por defecto para Parroquias
 *
 * Gestiona la información por defecto a registrar inicialmente para las Parroquias
 *
 * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
 * @license 
 *      [LICENCIA DE SOFTWARE CENDITEL](http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/)
 */
class ParishesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $filename = base_path("database/seeders/Data/parishes.csv");

        if (!file_exists($filename) || !is_readable($filename)) {
            return false;
        }

        $adminRole = Role::where('slug', 'admin')->first();

        /**
         * Permisos disponibles para la gestión de parroquías
         */

        $permissions = [
            [
                'name' => 'Crear Parroquías', 'slug' => 'parish.create',
                'description' => 'Acceso al registro de parroquías',
                'model' => Parish::class, 'model_prefix' => '0general',
                'slug_alt' => 'parroquia.crear', 'short_description' => 'agregar parroquia'
            ],
            [
                'name' => 'Editar Parroquías', 'slug' => 'parish.edit',
                'description' => 'Acceso para editar parroquías',
                'model' => Parish::class, 'model_prefix' => '0general',
                'slug_alt' => 'parroquia.editar', 'short_description' => 'editar parroquia'
            ],
            [
                'name' => 'Eliminar Parroquías', 'slug' => 'parish.delete',
                'description' => 'Acceso para eliminar parroquías',
                'model' => Parish::class, 'model_prefix' => '0general',
                'slug_alt' => 'parroquia.eliminar', 'short_description' => 'eliminar parroquia'
            ],
            [
                'name' => 'Ver Parroquías', 'slug' => 'parish.list',
                'description' => 'Acceso para ver parroquías',
                'model' => Parish::class, 'model_prefix' => '0general',
                'slug_alt' => 'parroquia.ver', 'short_description' => 'ver parroquías'
            ],
        ];

        $this->command->line("");
        $this->command->info("<fg=yellow>Cargando las Parroquias</>");
        $this->command->info("<fg=yellow>este proceso puede demorar algunos minutos, por favor espere...</>");
        $this->command->line("");

        $csvFile = fopen($filename, "r");
        foreach ($permissions as $permission) {
            $per = Permission::updateOrCreate(
                ['slug' => $permission['slug']],
                [
                    'name' => $permission['name'], 'description' => $permission['description'],
                    'model' => $permission['model'], 'model_prefix' => $permission['model_prefix'],
                    'slug_alt' => $permission['slug_alt'], 'short_description' => $permission['short_description']
                ]
            );
            if ($adminRole) {
                $adminRole->attachPermission($per);
            }
        }

        $count = 0;
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                $munCode = substr($data[1], 0, 4);
                $parish = Parish::withTrashed()->whereHas('municipality', function($q) use ($munCode) {
                    $q->withTrashed()->where('code', $munCode);
                })->where('code', $data[1])->first();
                if (!$parish) {
                    $mun = Municipality::select('id')->withTrashed()->where('code', $munCode)->first();
                    Parish::withTrashed()->insert(
                        [
                            'code' => $data[1], 'name' => $data[0], 'municipality_id' => $mun->id, 
                            'created_at' => Carbon::now(), 'deleted_at' => null
                        ]
                    );
                } elseif ($parish->name !== $data[0]) {
                    $parish->update(
                        ['name' => $data[0], 'deleted_at' => null]
                    );
                }
                $count++;
            }
            $firstline = false;
        }
        $this->command->line("");
        $this->command->info("<fg=green>Se cargó y/o actualizó un total de</><fg=yellow> $count </><fg=green>Parroquias</>");
        $this->command->line("");
    }
}
