<?php

namespace Modules\Finance\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Roles\Models\Role;
use App\Roles\Models\Permission;

class FinanceRoleAndPermissionsTableSeeder extends Seeder
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

        $financeRole = Role::updateOrCreate(
            ['slug' => 'finance'],
            ['name' => 'Finanza', 'description' => 'Coordinador de finanza']
        );

        $permissions = [
            [
                'name' => 'Configuración en módulo de finanza', 'slug' => 'finance.setting.create',
                'description' => 'Acceso a la configuración del módulo de finanza',
                'model' => '',
                'model_prefix' => 'finanzas',
                'slug_alt' => 'finanza.configuracion.crear',
                'short_description' => 'configuración de finanzas'
            ],
            [
                'name' => 'Crear banco', 'slug' => 'finance.bank.create',
                'description' => 'Acceso para crear banco',
                'model' => 'Modules\Finance\Models\FinanceBank',
                'model_prefix' => 'finanzas',
                'slug_alt' => 'banco.crear',
                'short_description' => 'agregar banco'
            ],
            [
                'name' => 'Editar banco', 'slug' => 'finance.bank.edit',
                'description' => 'Acceso para editar banco',
                'model' => 'Modules\Finance\Models\FinanceBank',
                'model_prefix' => 'finanzas',
                'slug_alt' => 'banco.editar',
                'short_description' => 'editar banco'
            ],
            [
                'name' => 'Eliminar banco', 'slug' => 'finance.bank.delete',
                'description' => 'Acceso para eliminar banco',
                'model' => 'Modules\Finance\Models\FinanceBank',
                'model_prefix' => 'finanzas',
                'slug_alt' => 'banco.eliminar',
                'short_description' => 'eliminar banco'
            ],
            [
                'name' => 'Ver bancos', 'slug' => 'finance.bank.list',
                'description' => 'Acceso para ver bancos',
                'model' => 'Modules\Finance\Models\FinanceBank',
                'model_prefix' => 'finanzas',
                'slug_alt' => 'banco.ver',
                'short_description' => 'ver banco'
            ],
            [
                'name' => 'Crear agencia bancaria', 'slug' => 'finance.bankagency.create',
                'description' => 'Acceso para crear agencia bancaria',
                'model' => 'Modules\Finance\Models\FinanceBankAgency',
                'model_prefix' => 'finanzas',
                'slug_alt' => 'agencia_bancaria.crear',
                'short_description' => 'agregar agencia bancaria'
            ],
            [
                'name' => 'Editar agencia bancaria', 'slug' => 'finance.bankagency.edit',
                'description' => 'Acceso para editar agencia bancaria',
                'model' => 'Modules\Finance\Models\FinanceBankAgency',
                'model_prefix' => 'finanzas',
                'slug_alt' => 'agencia_bancaria.editar',
                'short_description' => 'editar agencia bancaria'
            ],
            [
                'name' => 'Eliminar agencia bancaria', 'slug' => 'finance.bankagency.delete',
                'description' => 'Acceso para eliminar agencia bancaria',
                'model' => 'Modules\Finance\Models\FinanceBankAgency',
                'model_prefix' => 'finanzas',
                'slug_alt' => 'agencia_bancaria.eliminar',
                'short_description' => 'eliminar agencia bancaria'
            ],
            [
                'name' => 'Ver agencia bancarias', 'slug' => 'finance.bankagency.list',
                'description' => 'Acceso para ver agencia bancarias',
                'model' => 'Modules\Finance\Models\FinanceBankAgency',
                'model_prefix' => 'finanzas',
                'slug_alt' => 'agencia_bancaria.ver',
                'short_description' => 'ver agencia bancaria'
            ],
            [
                'name' => 'Crear tipo de cuenta', 'slug' => 'finance.accounttype.create',
                'description' => 'Acceso para crear tipo de cuenta',
                'model' => 'Modules\Finance\Models\FinanceAccountType',
                'model_prefix' => 'finanzas',
                'slug_alt' => 'tipo_cuenta.crear',
                'short_description' => 'agregar tipo de cuenta'
            ],
            [
                'name' => 'Editar tipo de cuenta', 'slug' => 'finance.accounttype.edit',
                'description' => 'Acceso para editar tipo de cuenta',
                'model' => 'Modules\Finance\Models\FinanceAccountType',
                'model_prefix' => 'finanzas',
                'slug_alt' => 'tipo_cuenta.editar',
                'short_description' => 'editar tipo de cuenta'
            ],
            [
                'name' => 'Eliminar tipo de cuenta', 'slug' => 'finance.accounttype.delete',
                'description' => 'Acceso para eliminar tipo de cuenta',
                'model' => 'Modules\Finance\Models\FinanceAccountType',
                'model_prefix' => 'finanzas',
                'slug_alt' => 'tipo_cuenta.eliminar',
                'short_description' => 'eliminar tipo de cuenta'
            ],
            [
                'name' => 'Ver tipo de cuentas', 'slug' => 'finance.accounttype.list',
                'description' => 'Acceso para ver tipo de cuentas',
                'model' => 'Modules\Finance\Models\FinanceAccountType',
                'model_prefix' => 'finanzas',
                'slug_alt' => 'tipo_cuenta.ver',
                'short_description' => 'ver tipo de cuenta'
            ],
            [
                'name' => 'Crear cuenta bancaria', 'slug' => 'finance.bankaccount.create',
                'description' => 'Acceso para crear cuenta bancaria',
                'model' => 'Modules\Finance\Models\FinanceBankAccount',
                'model_prefix' => 'finanzas',
                'slug_alt' => 'cuenta_bancaria.crear',
                'short_description' => 'agregar cuenta bancaria'
            ],
            [
                'name' => 'Editar cuenta bancaria', 'slug' => 'finance.bankaccount.edit',
                'description' => 'Acceso para editar cuenta bancaria',
                'model' => 'Modules\Finance\Models\FinanceBankAccount',
                'model_prefix' => 'finanzas',
                'slug_alt' => 'cuenta_bancaria.editar',
                'short_description' => 'editar cuenta bancaria'
            ],
            [
                'name' => 'Eliminar cuenta bancaria', 'slug' => 'finance.bankaccount.delete',
                'description' => 'Acceso para eliminar cuenta bancaria',
                'model' => 'Modules\Finance\Models\FinanceBankAccount',
                'model_prefix' => 'finanzas',
                'slug_alt' => 'cuenta_bancaria.eliminar',
                'short_description' => 'eliminar cuenta bancaria'
            ],
            [
                'name' => 'Ver cuenta bancarias', 'slug' => 'finance.bankaccount.list',
                'description' => 'Acceso para ver cuenta bancarias',
                'model' => 'Modules\Finance\Models\FinanceBankAccount',
                'model_prefix' => 'finanzas',
                'slug_alt' => 'cuenta_bancaria.ver',
                'short_description' => 'ver cuenta bancaria'
            ],
            [
                'name' => 'Crear chequera', 'slug' => 'finance.checkbook.create',
                'description' => 'Acceso para crear chequera',
                'model' => 'Modules\Finance\Models\FinanceCheckBook',
                'model_prefix' => 'finanzas',
                'slug_alt' => 'chequera.crear',
                'short_description' => 'agregar chequera'
            ],
            [
                'name' => 'Editar chequera', 'slug' => 'finance.checkbook.edit',
                'description' => 'Acceso para editar chequera',
                'model' => 'Modules\Finance\Models\FinanceCheckBook',
                'model_prefix' => 'finanzas',
                'slug_alt' => 'chequera.editar',
                'short_description' => 'editar chequera'
            ],
            [
                'name' => 'Eliminar chequera', 'slug' => 'finance.checkbook.delete',
                'description' => 'Acceso para eliminar chequera',
                'model' => 'Modules\Finance\Models\FinanceCheckBook',
                'model_prefix' => 'finanzas',
                'slug_alt' => 'chequera.eliminar',
                'short_description' => 'eliminar chequera'
            ],
            [
                'name' => 'Ver chequeras', 'slug' => 'finance.checkbook.list',
                'description' => 'Acceso para ver chequeras',
                'model' => 'Modules\Finance\Models\FinanceCheckBook',
                'model_prefix' => 'finanzas',
                'slug_alt' => 'chequera.ver',
                'short_description' => 'ver chequera'
            ],
            [
                'name' => 'Crear órden de pago', 'slug' => 'finance.payorder.create',
                'description' => 'Acceso para crear órden de pago',
                'model' => '',
                'model_prefix' => 'finanzas',
                'slug_alt' => 'orden_pago.crear',
                'short_description' => 'agregar órden de pago'
            ],
            [
                'name' => 'Editar órden de pago',
                'slug' => 'finance.payorder.edit',
                'description' => 'Acceso para editar órden de pago',
                'model' => '',
                'model_prefix' => 'finanzas',
                'slug_alt' => 'orden_pago.editar',
                'short_description' => 'editar órden de pago'
            ],
            [
                'name' => 'Ver órden de pago',
                'slug' => 'finance.payorder.list',
                'description' => 'Acceso para ver órdenes de pago',
                'model' => '',
                'model_prefix' => 'finanzas',
                'slug_alt' => 'orden_pago.ver',
                'short_description' => 'ver órden de pago'
            ],
            [
                'name' => 'Eliminar órden de pago',
                'slug' => 'finance.payorder.delete',
                'description' => 'Acceso para eliminar la órden de pago',
                'model' => '',
                'model_prefix' => 'finanzas',
                'slug_alt' => 'orden_pago.eliminar',
                'short_description' => 'eliminar órden de pago'
            ],
            [
                'name' => 'Crear un movimiento bancario', 'slug' => 'finance.movements.create',
                'description' => 'Acceso para crear un movimiento bancario',
                'model' => '',
                'model_prefix' => 'finanzas',
                'slug_alt' => 'movimiento_bancario.crear',
                'short_description' => 'agregar un movimiento bancario'
            ],
            [
                'name' => 'Editar un movimiento bancario',
                'slug' => 'finance.movements.edit',
                'description' => 'Acceso para editar un movimiento bancario',
                'model' => '',
                'model_prefix' => 'finanzas',
                'slug_alt' => 'movimiento_bancario.editar',
                'short_description' => 'editar un movimiento bancario'
            ],
            [
                'name' => 'Ver movimientos bancario',
                'slug' => 'finance.movements.list',
                'description' => 'Acceso para ver los movimientos bancario',
                'model' => '',
                'model_prefix' => 'finanzas',
                'slug_alt' => 'movimiento_bancario.ver',
                'short_description' => 'ver los movimientos bancario'
            ],
            [
                'name' => 'Eliminar un movimiento bancario',
                'slug' => 'finance.movements.delete',
                'description' => 'Acceso para eliminar un movimiento bancario',
                'model' => '',
                'model_prefix' => 'finanzas',
                'slug_alt' => 'movimiento_bancario.eliminar',
                'short_description' => 'eliminar un movimiento bancario'
            ],

            /**
            * Configuraciones de los archivos de conciliación bancaria.
            */
            [
                'name'              => 'Obtener listado de configuraciones de archivos de conciliación bancaria',
                'slug'              => 'finance.settingbankreconciliationfiles.index',
                'description'       => 'Acceso para obtener listado de configuraciones de archivos de conciliación bancaria',
                'model'             => 'Modules\Finance\Models\FinanceSettingBankReconciliationFiles',
                'model_prefix'      => 'finanzas',
                'slug_alt'          => 'config_archivos_conciliacion_bancaria.listado',
                'short_description' => 'Acceder al listado de configuraciones de archivos de conciliación bancaria'
            ],
            [
                'name'              => 'Registrar una configuración de archivo de conciliación bancaria',
                'slug'              => 'finance.settingbankreconciliationfiles.store',
                'description'       => 'Acceso para registrar un configuració de archivo de conciliación bancaria',
                'model'             => 'Modules\Finance\Models\FinanceSettingBankReconciliationFiles',
                'model_prefix'      => 'finanzas',
                'slug_alt'          => 'config_archivos_conciliacion_bancaria.crear',
                'short_description' => 'Registrar una configuración de archivo de conciliación bancaria'
            ],
            [
                'name'              => 'Actualizar una configuración de archivo de conciliación bancaria',
                'slug'              => 'finance.settingbankreconciliationfiles.update',
                'description'       => 'Acceso para actualizar un configuración de archivo de conciliación bancaria',
                'model'             => 'Modules\Finance\Models\FinanceSettingBankReconciliationFiles',
                'model_prefix'      => 'finanzas',
                'slug_alt'          => 'config_archivos_conciliacion_bancaria.actualizar',
                'short_description' => 'Actualizar una configuración de archivo de conciliación bancaria'
            ],
            [
                'name'              => 'Eliminar una configuración de archivo de conciliación bancaria',
                'slug'              => 'finance.settingbankreconciliationfiles.destroy',
                'description'       => 'Acceso para eliminar un configuración de archivo de conciliación bancaria',
                'model'             => 'Modules\Finance\Models\FinanceSettingBankReconciliationFiles',
                'model_prefix'      => 'finanzas',
                'slug_alt'          => 'config_archivos_conciliacion_bancaria.eliminar',
                'short_description' => 'Eliminar una configuración de archivo de conciliación bancaria'
            ],
        ];

        $financeRole->detachAllPermissions();

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

            $financeRole->attachPermission($per);

            if ($adminRole) {
                $adminRole->attachPermission($per);
            }
        }
    }
}
