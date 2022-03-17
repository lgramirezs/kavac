<?php
/** [descripción del namespace] */
namespace Modules\CitizenService\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Roles\Models\Role;
use App\Roles\Models\Permission;

/**
 * @class $CLASS$
 * @brief Inicializa los roles y permisos del módulo de atención al ciudadano
 *
 *
 *
 * @author [Ing. Yennifer Ramirez] [yramirez@cenditel.gob.ve]
 *
 * @license
 *     [LICENCIA DE SOFTWARE CENDITEL](http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/)
 */
class CitizenServiceRoleAndPermissionsTableSeeder extends Seeder
{
    /**
     * Ejecuta los seeds de la base de datos
     *
     * @method run
     *
     * @return void     [descripción de los datos devueltos]
     */
    public function run()
    {

            Model::unguard();

            $adminRole = Role::where('slug', 'admin')->first();

            $citizenServiceRole = Role::updateOrCreate(
                ['slug' => 'CitizenService'],
                ['name' => 'OAC', 'description' => 'Coordinador de atención al ciudadano']
            );

            $permissions = [
                [
                    'name' => 'Configuración del módulo de atención al ciudadano',
                    'slug' => 'citizenservice.setting.index',
                    'description' => 'Acceso a la configuración del módulo de atención al ciudadano',
                    'model' => '', 'model_prefix' => 'OAC',
                    'slug_alt' => 'configuracion.ver'
                ],
                /**
                 * Request (Solicitudes)
                 */
                [
                    'name' => 'Ver gestion de atencion al ciudadano', 'slug' => 'citizenservice.requests.list',
                    'description' => 'Acceso para ver solicitud',
                    'model' => 'Modules\CitizenService\Models\CitizenServiceRequest', 'model_prefix' => 'OAC',
                    'slug_alt' => 'solicitud.ver'
                ],
                [
                    'name' => 'Crear solicitud', 'slug' => 'citizenservice.requests.create',
                    'description' => 'Acceso para crear solicitud',
                    'model' => 'Modules\CitizenService\Models\CitizenServiceRequest', 'model_prefix' => 'OAC',
                    'slug_alt' => 'solicitud.crear'
                ],
                [
                    'name' => 'Editar solicitud', 'slug' => 'citizenservice.requests.edit',
                    'description' => 'Acceso para editar solicitud',
                    'model' => 'Modules\CitizenService\Models\CitizenServiceRequest', 'model_prefix' => 'OAC',
                    'slug_alt' => 'solicitud.editar'
                ],
                [
                    'name' => 'Eliminar solicitud', 'slug' => 'citizenservice.requests.delete',
                    'description' => 'Acceso para eliminar solicitud',
                    'model' => 'Modules\CitizenService\Models\CitizenServiceRequest', 'model_prefix' => 'OAC',
                    'slug_alt' => 'solicitud.eliminar'
                ],
                /* Report (Reportes)*/
                [
                    'name' => 'Crear reporte de atencion al ciudadano', 'slug' => 'citizenservice.report.create',
                    'description' => 'Acceso para crear reportes de atencion al ciudadano',
                    'model' => '', 'model_prefix' => 'OAC',
                    'slug_alt' => 'reporte.crear', 'short_description' => 'generar reporte de atencion al ciudadano'
                ],
                [
                    'name' => 'Ver reporte de atencion al ciudadano', 'slug' => 'citizenservice.report.list',
                    'description' => 'Acceso para ver reportes de atencion al ciudadano',
                    'model' => '', 'model_prefix' => 'OAC',
                    'slug_alt' => 'reporte.ver', 'short_description' => 'generar reporte de atencion al ciudadano'
                ],
                /**
                 * Register (Cronograma)
                 */
                [
                    'name' => 'Ver gestion de cronograma de actividades', 'slug' => 'citizenservice.registers.list',
                    'description' => 'Acceso para ver cronograma de actividades',
                    'model' => 'Modules\CitizenService\Models\CitizenServiceRegister', 'model_prefix' => 'OAC',
                    'slug_alt' => 'cronograma.ver'
                ],
                [
                    'name' => 'Crear cronograma de actividades', 'slug' => 'citizenservice.registers.create',
                    'description' => 'Acceso para crear cronograma de actividades',
                    'model' => 'Modules\CitizenService\Models\CitizenServiceRegister', 'model_prefix' => 'OAC',
                    'slug_alt' => 'cronograma.crear'
                ],
                [
                    'name' => 'Editar cronograma de actividades', 'slug' => 'citizenservice.registers.edit',
                    'description' => 'Acceso para editar cronograma de actividades',
                    'model' => 'Modules\CitizenService\Models\CitizenServiceRegister', 'model_prefix' => 'OAC',
                    'slug_alt' => 'cronograma.editar'
                ],
                [
                    'name' => 'Eliminar cronograma de actividades', 'slug' => 'citizenservice.registers.delete',
                    'description' => 'Acceso para eliminar cronograma de actividades',
                    'model' => 'Modules\CitizenService\Models\CitizenServiceRegister', 'model_prefix' => 'OAC',
                    'slug_alt' => 'cronograma.eliminar'
                ],

            ];

            foreach ($permissions as $permission) {
                $per = Permission::updateOrCreate(
                    ['slug' => $permission['slug']],
                    [
                        'name' => $permission['name'], 'description' => $permission['description'],
                        'model' => $permission['model'], 'model_prefix' => $permission['model_prefix'],
                        'slug_alt' => $permission['slug_alt']
                    ]
                );

                $citizenServiceRole->attachPermission($per);

                if ($adminRole) {
                    $adminRole->attachPermission($per);
                }
            }
    }
}
