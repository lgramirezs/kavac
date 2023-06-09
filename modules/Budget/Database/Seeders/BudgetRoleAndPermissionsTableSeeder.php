<?php

namespace Modules\Budget\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Roles\Models\Role;
use App\Roles\Models\Permission;
use Modules\Budget\Models\BudgetAccount;
use Modules\Budget\Models\BudgetCentralizedAction;
use Modules\Budget\Models\BudgetCompromise;
use Modules\Budget\Models\BudgetStage;
use Modules\Budget\Models\BudgetModification;
use Modules\Budget\Models\BudgetProject;
use Modules\Budget\Models\BudgetSpecificAction;
use Modules\Budget\Models\BudgetSubSpecificFormulation;

/**
 * @class BudgetRoleAndPermissionsTableSeeder
 * @brief Información por defecto para Roles y Permisos del módulo de presupuesto
 *
 * Gestiona la información por defecto a registrar inicialmente para los Roles y Permisos del módulo de presupuesto
 *
 * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
 * @license <a href='http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/'>
 *              LICENCIA DE SOFTWARE CENDITEL
 *          </a>
 */
class BudgetRoleAndPermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $adminRole = Role::where('slug', 'admin')->first();

        $budgetRole = Role::updateOrCreate(
            ['slug' => 'budget'],
            ['name' => 'Presupuesto', 'description' => 'Coordinador de presupuesto']
        );

        /** @var array Listado de permisos a registrar */
        $permissions = [
            [
                'name' => 'Inicio del módulo de presupuesto', 'slug' => 'budget.home',
                'description' => 'Acceso a descripción del módulo de presupuesto',
                'model' => 'Modules\Budget\Models\BudgetAccount', 'model_prefix' => 'presupuesto',
                'slug_alt' => 'presupuesto.inicio', 'short_description' => 'página de inicio'
            ],
            [
                'name' => 'Configuración del módulo de presupuesto', 'slug' => 'budget.setting.create',
                'description' => 'Acceso a la configuración del módulo de presupuesto',
                'model' => '', 'model_prefix' => 'presupuesto',
                'slug_alt' => 'configuracion.crear', 'short_description' => 'agregar configuración'
            ],
            [
                'name' => 'Editar configuración del módulo de presupuesto',
                'slug' => 'budget.setting.edit',
                'description' => 'Acceso para editar la configuración del módulo de presupuesto',
                'model' => '', 'model_prefix' => 'presupuesto',
                'slug_alt' => 'configuracion.editar', 'short_description' => 'editar configuración'
            ],
            [
                'name' => 'Ver configuración del módulo de presupuesto',
                'slug' => 'budget.setting.list',
                'description' => 'Acceso para editar la configuración del módulo de presupuesto',
                'model' => '', 'model_prefix' => 'presupuesto',
                'slug_alt' => 'configuracion.ver', 'short_description' => 'ver configuración'
            ],
            [
                'name' => 'Eliminar configuración del módulo de presupuesto',
                'slug' => 'budget.setting.delete',
                'description' => 'Acceso para eliminar la configuración del módulo de presupuesto',
                'model' => '', 'model_prefix' => 'presupuesto',
                'slug_alt' => 'configuracion.eliminar', 'short_description' => 'eliminar configuración'
            ],
            [
                'name' => 'Crear cuenta presupuestaria', 'slug' => 'budget.account.create',
                'description' => 'Acceso para crear cuenta presupuestaria',
                'model' => 'Modules\Budget\Models\BudgetAccount', 'model_prefix' => 'presupuesto',
                'slug_alt' => 'clasificador.crear', 'short_description' => 'agregar clasificador'
            ],
            [
                'name' => 'Editar cuenta presupuestaria', 'slug' => 'budget.account.edit',
                'description' => 'Acceso para editar cuenta presupuestaria',
                'model' => 'Modules\Budget\Models\BudgetAccount', 'model_prefix' => 'presupuesto',
                'slug_alt' => 'clasificador.editar', 'short_description' => 'editar clasificador'
            ],
            [
                'name' => 'Eliminar cuenta presupuestaria', 'slug' => 'budget.account.delete',
                'description' => 'Acceso para eliminar cuenta presupuestaria',
                'model' => 'Modules\Budget\Models\BudgetAccount', 'model_prefix' => 'presupuesto',
                'slug_alt' => 'clasificador.eliminar', 'short_description' => 'eliminar clasificador'
            ],
            [
                'name' => 'Ver cuentas presupuestarias', 'slug' => 'budget.account.list',
                'description' => 'Acceso para ver cuentas presupuestarias',
                'model' => 'Modules\Budget\Models\BudgetAccount', 'model_prefix' => 'presupuesto',
                'slug_alt' => 'clasificador.ver', 'short_description' => 'ver clasificador'
            ],
            [
                'name' => 'Crear proyecto', 'slug' => 'budget.project.create',
                'description' => 'Acceso para crear proyecto',
                'model' => 'Modules\Budget\Models\BudgetProject', 'model_prefix' => 'presupuesto',
                'slug_alt' => 'proyecto.crear', 'short_description' => 'agregar proyecto'
            ],
            [
                'name' => 'Editar proyecto', 'slug' => 'budget.project.edit',
                'description' => 'Acceso para editar proyectos',
                'model' => 'Modules\Budget\Models\BudgetProject', 'model_prefix' => 'presupuesto',
                'slug_alt' => 'proyecto.editar', 'short_description' => 'editar proyecto'
            ],
            [
                'name' => 'Eliminar proyecto', 'slug' => 'budget.project.delete',
                'description' => 'Acceso para eliminar proyectos',
                'model' => 'Modules\Budget\Models\BudgetProject', 'model_prefix' => 'presupuesto',
                'slug_alt' => 'proyecto.eliminar', 'short_description' => 'eliminar proyecto'
            ],
            [
                'name' => 'Ver proyectos', 'slug' => 'budget.project.list',
                'description' => 'Acceso para ver proyectos',
                'model' => 'Modules\Budget\Models\BudgetProject', 'model_prefix' => 'presupuesto',
                'slug_alt' => 'proyecto.ver', 'short_description' => 'ver proyecto'
            ],

            [
                'name' => 'Crear acción centralizada', 'slug' => 'budget.centralizedaction.create',
                'description' => 'Acceso para crear acción centralizada',
                'model' => 'Modules\Budget\Models\BudgetCentralizedAction', 'model_prefix' => 'presupuesto',
                'slug_alt' => 'accion_centralizada.crear', 'short_description' => 'agregar acción centralizada'
            ],
            [
                'name' => 'Editar acción centralizada', 'slug' => 'budget.centralizedaction.edit',
                'description' => 'Acceso para editar acción centralizada',
                'model' => 'Modules\Budget\Models\BudgetCentralizedAction', 'model_prefix' => 'presupuesto',
                'slug_alt' => 'accion_centralizada.editar', 'short_description' => 'editar acción centralizada'
            ],
            [
                'name' => 'Eliminar acción centralizada', 'slug' => 'budget.centralizedaction.delete',
                'description' => 'Acceso para eliminar acción centralizada',
                'model' => 'Modules\Budget\Models\BudgetCentralizedAction', 'model_prefix' => 'presupuesto',
                'slug_alt' => 'accion_centralizada.eliminar', 'short_description' => 'eliminar acción centralizada'
            ],
            [
                'name' => 'Ver acciones centralizadas', 'slug' => 'budget.centralizedaction.list',
                'description' => 'Acceso para ver acciones centralizadas',
                'model' => 'Modules\Budget\Models\BudgetCentralizedAction', 'model_prefix' => 'presupuesto',
                'slug_alt' => 'accion_centralizada.ver', 'short_description' => 'ver acción centralizada'
            ],
            [
                'name' => 'Crear acción específica', 'slug' => 'budget.specificaction.create',
                'description' => 'Acceso para crear acción específica',
                'model' => 'Modules\Budget\Models\BudgetSpecificAction', 'model_prefix' => 'presupuesto',
                'slug_alt' => 'accion_especifica.crear', 'short_description' => 'agregar acción específica'
            ],
            [
                'name' => 'Editar acción específica', 'slug' => 'budget.specificaction.edit',
                'description' => 'Acceso para editar acciones específicas',
                'model' => 'Modules\Budget\Models\BudgetSpecificAction', 'model_prefix' => 'presupuesto',
                'slug_alt' => 'accion_especifica.editar', 'short_description' => 'editar acción específica'
            ],
            [
                'name' => 'Eliminar acción específica', 'slug' => 'budget.specificaction.delete',
                'description' => 'Acceso para eliminar acciones específicas',
                'model' => 'Modules\Budget\Models\BudgetSpecificAction', 'model_prefix' => 'presupuesto',
                'slug_alt' => 'accion_especifica.eliminar', 'short_description' => 'eliminar acción específica'
            ],
            [
                'name' => 'Ver acciones específicas', 'slug' => 'budget.specificaction.list',
                'description' => 'Acceso para ver acciones específicas',
                'model' => 'Modules\Budget\Models\BudgetSpecificAction', 'model_prefix' => 'presupuesto',
                'slug_alt' => 'accion_especifica.ver', 'short_description' => 'ver acción específica'
            ],
            [
                'name' => 'Crear formulación de presupuesto', 'slug' => 'budget.formulation.create',
                'description' => 'Acceso para crear formulación de presupuesto',
                'model' => 'Modules\Budget\Models\BudgetFormulation', 'model_prefix' => 'presupuesto',
                'slug_alt' => 'formulacion.crear', 'short_description' => 'agregar formulación'
            ],
            [
                'name' => 'Editar formulación de presupuesto', 'slug' => 'budget.formulation.edit',
                'description' => 'Acceso para editar formulaciones de presupuesto',
                'model' => 'Modules\Budget\Models\BudgetFormulation', 'model_prefix' => 'presupuesto',
                'slug_alt' => 'formulacion.editar', 'short_description' => 'editar formulación'
            ],
            [
                'name' => 'Eliminar formulación de presupuesto', 'slug' => 'budget.formulation.delete',
                'description' => 'Acceso para eliminar formulaciones de presupuesto',
                'model' => 'Modules\Budget\Models\BudgetFormulation', 'model_prefix' => 'presupuesto',
                'slug_alt' => 'formulacion.eliminar', 'short_description' => 'eliminar formulación'
            ],
            [
                'name' => 'Ver formulaciones de presupuesto', 'slug' => 'budget.formulation.list',
                'description' => 'Acceso para ver formulaciones de presupuesto',
                'model' => 'Modules\Budget\Models\BudgetFormulation', 'model_prefix' => 'presupuesto',
                'slug_alt' => 'formulacion.ver', 'short_description' => 'ver formulación'
            ],
            [
                'name' => 'Crear crédito adicional', 'slug' => 'budget.aditionalcredit.create',
                'description' => 'Acceso para crear crédito adicional',
                'model' => 'Modules\Budget\Models\BudgetModification', 'model_prefix' => 'presupuesto',
                'slug_alt' => 'credito_adicional.crear', 'short_description' => 'agregar crédito adicional'
            ],
            [
                'name' => 'Editar crédito adicional', 'slug' => 'budget.aditionalcredit.edit',
                'description' => 'Acceso para editar créditos adicionales',
                'model' => 'Modules\Budget\Models\BudgetModification', 'model_prefix' => 'presupuesto',
                'slug_alt' => 'credito_adicional.editar', 'short_description' => 'editar crédito adicional'
            ],
            [
                'name' => 'Eliminar crédito adicional', 'slug' => 'budget.aditionalcredit.delete',
                'description' => 'Acceso para eliminar créditos adicionales',
                'model' => 'Modules\Budget\Models\BudgetModification', 'model_prefix' => 'presupuesto',
                'slug_alt' => 'credito_adicional.eliminar', 'short_description' => 'eliminar crédito adicional'
            ],
            [
                'name' => 'Ver créditos adicionales', 'slug' => 'budget.aditionalcredit.list',
                'description' => 'Acceso para ver créditos adicionales',
                'model' => 'Modules\Budget\Models\BudgetModification', 'model_prefix' => 'presupuesto',
                'slug_alt' => 'credito_adicional.ver', 'short_description' => 'ver crédito adicional'
            ],
            [
                'name' => 'Crear reducción de presupuesto', 'slug' => 'budget.reduction.create',
                'description' => 'Acceso para crear reducción de presupuesto',
                'model' => 'Modules\Budget\Models\BudgetModification', 'model_prefix' => 'presupuesto',
                'slug_alt' => 'reduccion.crear', 'short_description' => 'agregar reducción de presupuesto'
            ],
            [
                'name' => 'Editar reducción de presupuesto', 'slug' => 'budget.reduction.edit',
                'description' => 'Acceso para editar reducciones de presupuesto',
                'model' => 'Modules\Budget\Models\BudgetModification', 'model_prefix' => 'presupuesto',
                'slug_alt' => 'reduccion.editar', 'short_description' => 'editar reducción de presupuesto'
            ],
            [
                'name' => 'Eliminar reducción de presupuesto', 'slug' => 'budget.reduction.delete',
                'description' => 'Acceso para eliminar reducciones de presupuesto',
                'model' => 'Modules\Budget\Models\BudgetModification', 'model_prefix' => 'presupuesto',
                'slug_alt' => 'reduccion.eliminar', 'short_description' => 'eliminar reducción de presupuesto'
            ],
            [
                'name' => 'Ver reducciones de presupuesto', 'slug' => 'budget.reduction.list',
                'description' => 'Acceso para ver reducciones de presupuesto',
                'model' => 'Modules\Budget\Models\BudgetModification', 'model_prefix' => 'presupuesto',
                'slug_alt' => 'reduccion.ver', 'short_description' => 'ver reducción de presupuesto'
            ],
            [
                'name' => 'Crear traspaso de presupuesto', 'slug' => 'budget.transfer.create',
                'description' => 'Acceso para crear traspaso de presupuesto',
                'model' => 'Modules\Budget\Models\BudgetModification', 'model_prefix' => 'presupuesto',
                'slug_alt' => 'traspaso.crear', 'short_description' => 'agregar traspaso de presupuesto'
            ],
            [
                'name' => 'Editar traspaso de presupuesto', 'slug' => 'budget.transfer.edit',
                'description' => 'Acceso para editar traspasos de presupuesto',
                'model' => 'Modules\Budget\Models\BudgetModification', 'model_prefix' => 'presupuesto',
                'slug_alt' => 'traspaso.editar', 'short_description' => 'editar traspaso de presupuesto'
            ],
            [
                'name' => 'Eliminar traspaso de presupuesto', 'slug' => 'budget.transfer.delete',
                'description' => 'Acceso para eliminar traspasos de presupuesto',
                'model' => 'Modules\Budget\Models\BudgetModification', 'model_prefix' => 'presupuesto',
                'slug_alt' => 'traspaso.eliminar', 'short_description' => 'eliminar traspaso de presupuesto'
            ],
            [
                'name' => 'Ver traspasos de presupuesto', 'slug' => 'budget.transfer.list',
                'description' => 'Acceso para ver traspasos de presupuesto',
                'model' => 'Modules\Budget\Models\BudgetModification', 'model_prefix' => 'presupuesto',
                'slug_alt' => 'traspaso.ver', 'short_description' => 'ver traspaso de presupuesto'
            ],
            [
                'name' => 'Crear modificación presupuestaria', 'slug' => 'budget.modifications.create',
                'description' => 'Acceso para crear modificación presupuestaria',
                'model' => 'Modules\Budget\Models\BudgetModification', 'model_prefix' => 'presupuesto',
                'slug_alt' => 'modificacion.crear', 'short_description' => 'agregar modificación presupuestaria'
            ],
            [
                'name' => 'Editar modificación presupuestaria', 'slug' => 'budget.modifications.edit',
                'description' => 'Acceso para editar modificaciones presupuestarias',
                'model' => 'Modules\Budget\Models\BudgetModification', 'model_prefix' => 'presupuesto',
                'slug_alt' => 'modificacion.editar', 'short_description' => 'editar modificación presupuestaria'
            ],
            [
                'name' => 'Eliminar modificación presupuestaria', 'slug' => 'budget.modifications.delete',
                'description' => 'Acceso para eliminar modificaciones presupuestarias',
                'model' => 'Modules\Budget\Models\BudgetModification', 'model_prefix' => 'presupuesto',
                'slug_alt' => 'modificacion.eliminar', 'short_description' => 'eliminar modificación presupuestaria'
            ],
            [
                'name' => 'Ver modificaciones presupuestarias', 'slug' => 'budget.modifications.list',
                'description' => 'Acceso para ver modificaciones presupuestarias',
                'model' => 'Modules\Budget\Models\BudgetModification', 'model_prefix' => 'presupuesto',
                'slug_alt' => 'modificacion.ver', 'short_description' => 'ver modificación presupuestaria'
            ],

            /**
            * Permisos de los Registros comúnes > Tipos de financiamiento.
            */
            [
                'name'              => 'Obtener listado de los tipos de financiamiento',
                'slug'              => 'budget.financementtypes.index',
                'description'       => 'Acceso para obtener listado de los tipos de financiamiento',
                'model'             => 'Modules\Budget\Models\BudgetFinancementTypes',
                'model_prefix'      => 'presupuesto',
                'slug_alt'          => 'tipos_financiamiento.listado',
                'short_description' => 'Acceder al listado de tipos de financiamiento'
            ],
            [
                'name'              => 'Registrar una configuración de archivo de conciliación bancaria',
                'slug'              => 'budget.financementtypes.store',
                'description'       => 'Acceso para registrar un configuració de archivo de conciliación bancaria',
                'model'             => 'Modules\Budget\Models\BudgetFinancementTypes',
                'model_prefix'      => 'presupuesto',
                'slug_alt'          => 'tipos_financiamiento.crear',
                'short_description' => 'Registrar un tipo de financiamiento'
            ],
            [
                'name'              => 'Actualizar una configuración de archivo de conciliación bancaria',
                'slug'              => 'budget.financementtypes.update',
                'description'       => 'Acceso para actualizar un configuración de archivo de conciliación bancaria',
                'model'             => 'Modules\Budget\Models\BudgetFinancementTypes',
                'model_prefix'      => 'presupuesto',
                'slug_alt'          => 'tipos_financiamiento.actualizar',
                'short_description' => 'Actualizar un tipo de financiamiento'
            ],
            [
                'name'              => 'Eliminar una configuración de archivo de conciliación bancaria',
                'slug'              => 'budget.financementtypes.destroy',
                'description'       => 'Acceso para eliminar un configuración de archivo de conciliación bancaria',
                'model'             => 'Modules\Budget\Models\BudgetFinancementTypes',
                'model_prefix'      => 'presupuesto',
                'slug_alt'          => 'tipos_financiamiento.eliminar',
                'short_description' => 'Eliminar un tipo de financiamiento'
            ],
        ];

        $permissions = array_merge($permissions, [
            [
                'name' => 'Notificar gestion de cuentas presupuestarias',
                'slug' => 'notify.budget.account',
                'description' => 'Notificar sobre gestión de datos de cuentas presupuestarias',
                'model' => BudgetAccount::class, 'model_prefix' => 'presupuesto',
                'slug_alt' => 'notificar.presupuesto.cuentas',
                'short_description' => 'notificar la gestion de cuentas presupuestarias'
            ],
            [
                'name' => 'Notificar gestion de acciones centralizadas',
                'slug' => 'notify.budget.centralized_actions',
                'description' => 'Notificar sobre gestión de datos de acciones centralizadas',
                'model' => BudgetCentralizedAction::class, 'model_prefix' => 'presupuesto',
                'slug_alt' => 'notificar.presupuesto.acciones.centralizadas',
                'short_description' => 'notificar la gestion de acciones centralizadas'
            ],
            [
                'name' => 'Notificar gestion de compromisos',
                'slug' => 'notify.budget.compromise',
                'description' => 'Notificar sobre gestión de datos de compromisos',
                'model' => BudgetCompromise::class, 'model_prefix' => 'presupuesto',
                'slug_alt' => 'notificar.presupuesto.compromiso',
                'short_description' => 'notificar la gestion de compromisos'
            ],
            [
                'name' => 'Notificar etapa de compromiso',
                'slug' => 'notify.budget.stage',
                'description' => <<<EOT
                    Notificar sobre etapas de compromisos. Precomprometido, Programado, Comprometido, Causado o Pagado
                EOT,
                'model' => BudgetStage::class, 'model_prefix' => 'presupuesto',
                'slug_alt' => 'notificar.presupuesto.etapa',
                'short_description' => 'notificar la etapa de compromisos'
            ],
            [
                'name' => 'Notificar gestion de modificaciones presupuestarias',
                'slug' => 'notify.budget.modification',
                'description' => 'Notificar sobre gestión de datos de modificaciones presupuestarias',
                'model' => BudgetModification::class, 'model_prefix' => 'presupuesto',
                'slug_alt' => 'notificar.presupuesto.modificacion',
                'short_description' => 'notificar la gestion de modificaciones presupuestarias'
            ],
            [
                'name' => 'Notificar gestion de proyectos',
                'slug' => 'notify.budget.project',
                'description' => 'Notificar sobre gestión de datos de proyectos',
                'model' => BudgetProject::class, 'model_prefix' => 'presupuesto',
                'slug_alt' => 'notificar.presupuesto.proyecto',
                'short_description' => 'notificar la gestion de proyectos'
            ],
            [
                'name' => 'Notificar gestion de acciones específicas',
                'slug' => 'notify.budget.specific_action',
                'description' => 'Notificar sobre gestión de datos de acciones específicas',
                'model' => BudgetSpecificAction::class, 'model_prefix' => 'presupuesto',
                'slug_alt' => 'notificar.presupuesto.accion.especifica',
                'short_description' => 'notificar la gestion de acciones específicas'
            ],
            [
                'name' => 'Notificar gestion de formulaciones de presupuesto',
                'slug' => 'notify.budget.sub_specific_formulation',
                'description' => 'Notificar sobre gestión de datos de formulaciones de presupuesto',
                'model' => BudgetSubSpecificFormulation::class, 'model_prefix' => 'presupuesto',
                'slug_alt' => 'notificar.presupuesto.formulacion',
                'short_description' => 'notificar la gestion de formulaciones de presupuesto'
            ],
        ]);

        $budgetRole->detachAllPermissions();

        foreach ($permissions as $permission) {
            $per = Permission::updateOrCreate(
                ['slug' => $permission['slug']],
                [
                    'name' => $permission['name'], 'description' => $permission['description'],
                    'model' => $permission['model'], 'model_prefix' => $permission['model_prefix'],
                    'slug_alt' => $permission['slug_alt'], 'short_description' => $permission['short_description']
                ]
            );

            $budgetRole->attachPermission($per);

            if ($adminRole) {
                $adminRole->attachPermission($per);
            }
        }
    }
}
