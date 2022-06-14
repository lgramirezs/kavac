<?php

namespace Modules\DigitalSignature\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Roles\Models\Role;
use App\Roles\Models\Permission;

/**
 * @class   DigitalSignatureRoleAndPermissionsTableSeeder
 * @brief   Gestiona la inserción de permisos en la base de datos
 *
 *          Clase que gestiona la inserción de permisos en la base de datos.
 *
 * @author  Ing. Argenis Osorio <aosorio@cenditel.gob.ve>
 * 
 * @license <a href='http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/'>
 *              LICENCIA DE SOFTWARE CENDITEL
 *          </a>
 */
class DigitalSignatureRoleAndPermissionsTableSeeder extends Seeder
{
    /**
     * Ejecute el seeder de la base de datos.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $adminRole = Role::where('slug', 'admin')->first();

        $digitalSignatureRole = Role::updateOrCreate(
            [
                'slug' => 'digitalsignature'
            ],
            [
                'name' => 'Firma Electrónica',
                'description' => 'Coordinador de firma electrónica'
            ]
        );

        $permissions = [
            [
                'name' => 'Acceder al módulo de Firma Electrónica',
                'slug' => 'digitalsignature.index',
                'description' => 'Acceso al módulo de Firma Electrónica',
                'model' => '',
                'model_prefix' => 'firma_electronica',
                'slug_alt' => 'firma_electronica.acceso',
                'short_description' => 'Acceder al módulo de Firma Electrónica'
            ]
        ];

        $digitalSignatureRole->detachAllPermissions();

        foreach ($permissions as $permission) {
            $per = Permission::updateOrCreate(
                ['slug' => $permission['slug']],
                [
                    'name' => $permission['name'],
                    'description' => $permission['description'],
                    'model' => $permission['model'],
                    'model_prefix' => $permission['model_prefix'],
                    'slug_alt' => $permission['slug_alt'],
                    'short_description' => $permission['short_description']
                ]
            );

            $digitalSignatureRole->attachPermission($per);

            if ($adminRole) {
                $adminRole->attachPermission($per);
            }
        }
    }
}
