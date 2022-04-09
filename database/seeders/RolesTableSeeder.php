<?php
namespace Database\Seeders;

use Exception;
use App\Roles\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * @class RolesTableSeeder
 * @brief Información por defecto para Roles
 *
 * Gestiona la información por defecto a registrar inicialmente para los Roles
 *
 * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
 * @license 
 *      [LICENCIA DE SOFTWARE CENDITEL](http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/)
 */
class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            /** @var object Crea el rol para desarrolladores de la aplicación */
            $roleDev = Role::updateOrCreate(
                ['slug' => 'dev'],
                [
                    'name' => 'Desarrollador',
                    'description' => 'Desarrollador de la aplicación',
                    'level' => 2,
                ]
            );

            if (!$roleDev) {
                throw new Exception('Error creando el rol por defecto para desarrolladores');
            }

            /** @var object Crea el rol de administrador del sistema */
            $roleAdmin = Role::updateOrCreate(
                ['slug' => 'admin'],
                [
                    'name' => 'Administrador',
                    'description' => 'Administrador de la aplicación',
                    'level' => 1,
                ]
            );

            if (!$roleAdmin) {
                throw new Exception('Error creando el rol por defecto para administradores');
            }

            /** @var object Crea el rol de usuario del sistema */
            $roleUser = Role::updateOrCreate(
                ['slug' => 'user'],
                [
                    'name' => 'Usuario',
                    'description' => 'Usuario de la aplicación',
                    'level' => 2,
                ]
            );

            if (!$roleUser) {
                throw new Exception('Error creando el rol por defecto para usuarios');
            }

            /** @var object Crea el rol para soporte del sistema */
            $roleSupport = Role::updateOrCreate(
                ['slug' => 'support'],
                [
                    'name' => 'Soporte',
                    'description' => 'Soporte técnico de la aplicación',
                    'level' => 2,
                ]
            );

            if (!$roleSupport) {
                throw new Exception('Error creando el rol por defecto para soporte técnico');
            }
        });
    }
}
