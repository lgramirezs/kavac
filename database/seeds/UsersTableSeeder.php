<?php

use Illuminate\Database\Seeder;
use App\Roles\Models\Role;

use App\User;
use Carbon\Carbon;

/**
 * @class UsersTableSeeder
 * @brief Información por defecto para Usuarios
 * 
 * Gestiona la información por defecto a registrar inicialmente para los Usuarios
 * 
 * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
 * @copyright <a href='http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/'>LICENCIA DE SOFTWARE CENDITEL</a>
 */
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function() {
        	/** @var object Crea el usuario por defecto de la aplicación */
    		$user_admin = User::updateOrCreate(
                ['username' => 'admin'],
                [
                    'name' => 'Admin',
                    'email' => 'admin@admin.com',
                    'password' => bcrypt('123456'),
                    'level' => 1,
                    'created_at' => Carbon::now()
                ]
            );
	        if (!$user_admin) {
                throw new Exception('Error creando el usuario administrador por defecto');
            }

            /** @var object Crea el rol de administrador del sistema */
            $adminRole = Role::where('slug', 'admin')->first();

            if ($adminRole) {
                /** Asigna el rol de administrador */
                $user_admin->attachRole($adminRole);
            }
    	});
    }
}
