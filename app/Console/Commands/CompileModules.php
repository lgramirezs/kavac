<?php
/** Gestiona los comandos personalizados de la aplicación */
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Nwidart\Modules\Facades\Module;

/**
 * @class CompileModules
 * @brief Gestiona las instrucciones necesarias para ejecutar los comandos para la compilación de archivos
 *
 * Gestiona las instrucciones para la compilación de archivos css y js
 *
 * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
 *
 * @license
 *     [LICENCIA DE SOFTWARE CENDITEL](http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/)
 */
class CompileModules extends Command
{
    /**
     * El nombre y firma del comando, así como las opciones y argumentos que recibe
     *
     * @var string
     */
    protected $signature = 'module:compile
                            {module? : The module name to compile. Example: module:compile moduleName}
                            {--p|prod : Option to compile in production mode}
                            {--i|install : With previous install node packages}
                            {--u|update : With previous update node packages}
                            {--s|system : With core compile}
                            {--x|no-compile : Without compile action}
                            {--d|details : With detail output info}';

    /**
     * Descripción del comando.
     *
     * @var string
     */
    protected $description = 'compile assets modules';

    /**
     * Crea una nueva instancia al comando.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Ejecuta el comando de la consola.
     *
     * @return int
     */
    public function handle()
    {
        $result = shell_exec("npm -v");
        
        if (strpos($result, 'no encontrada')!==false || strpos($result, 'not found')!==false || $result===null) {
            $this->line('');
            $this->line('<fg=red>El paquete node no esta instalado.</>');
            $this->line('<fg=red>Antes de continuar instale el paquete y repita el proceso.</>');
            $this->line('');
            return 0;
        }
        $withCore = ($this->option('system'))?true:false;
        $withNoCompile = ($this->option('no-compile'))?true:false;

        $this->line('');
        $this->line('<fg=yellow>---------------------------------------------------------</>');
        $this->line('<fg=yellow>|                Compilación de archivos                |</>');
        $this->line('<fg=yellow>---------------------------------------------------------</>');
        $this->line('');

        /** @var array Guarda el listado de los módulos compilados */
        $m = [];
        /** @var string Modo de compilación. dev = desarrollo, prod = producción */
        $compileMode = ($this->option('prod'))?'prod':'dev';
        /** @var string Establece si se debe ejecutar el comando de instalación de paquetes */
        $withInstall = ($this->option('install'))?'&& npm install':'';
        /** @var string Establece si se debe ejecutar el comando de actualización de paquetes */
        $withUpdate = ($this->option('update'))?'&& npm update':'';
        /** @var string Texto a mostrar como resultado de la ejecución */
        $successText = (!empty($withInstall))?'instalados':((!empty($withUpdate))?'actualizados':'');
        /** @var boolean Determina si se encuentra un error en la compilación */
        $hasError = false;
        /** @var string Mensaje del error */
        $errorMsg = '';
        /** @var string Nombre del módulo que generó error al compilar */
        $errorModule = '';

        /** @var object Objeto con información de los módulos habilitados en la aplicación */
        $modules = Module::collections(1);
        /** @var integer Total de módulos habilitados */
        $count = (!$this->argument('module'))?count($modules):1;
        /** @var integer contador que registra el número de modulo que se compila */
        $index = 1;
        /** @var string Título de la acción a ejecutar */
        $moduleCompileTitle = (!empty($withUpdate))
                              ?"Actualizando paquetes y "
                              :((!empty($withInstall))?"Instalando paquetes y ":"");

        if ($withCore) {
            $this->line('');
            if (!empty($withInstall) || !empty($withUpdate)) {
                $this->line('');
                $this->line(
                    "<fg=green>" .
                    ((!empty($withInstall)) ? "Instalando":"Actualizando") .
                    " paquetes generales del sistema</>"
                );
                $this->line('');
                $result = shell_exec(!empty($withUpdate)?"npm update":"npm install");
                if (strpos($result, 'audited') === false) {
                    $hasError = true;
                    $errorMsg = $result;
                    $this->info("Ocurrió un error en la compilación");
                    $this->info("Detalles del error:");
                    $this->info($errorMsg);
                    return 0;
                }
                $this->line("<fg=green>\xE2\x9C\x94</> <fg=yellow>Paquetes instalados</>");
            }

            if (!$withNoCompile) {
                $this->line('');
                $this->line("<fg=green>Compilando archivos generales del sistema</>");
                $this->line('');
                $result = shell_exec("npm run $compileMode");
                if (strpos($result, 'successfully') === false) {
                    $hasError = true;
                    $errorMsg = $result;
                    $this->info("Ocurrió un error en la compilación");
                    $this->info("Detalles del error:");
                    $this->info($errorMsg);
                    return 0;
                }
                $this->line("<fg=green>\xE2\x9C\x94</> <fg=yellow>Archivos compilados</>");
            }
        }

        foreach ($modules as $key => $module) {
            if ($this->argument('module') && strtolower($this->argument('module')) !== strtolower($module)) {
                continue;
            }
            $this->line('');
            $this->line(
                "<fg=green>{$moduleCompileTitle}Compilando módulo $index/$count:</> ".
                "<fg=yellow>" . $module->getName()  . "</>"
            );
            $this->line('');
            $packages = (!empty($withUpdate))?$withUpdate:$withInstall;
            if (!$withNoCompile) {
                $result = shell_exec("cd modules/$module $packages && npm run $compileMode");
                if (strpos($result, 'successfully') === false) {
                    $hasError = true;
                    $errorMsg = $result;
                    $errorModule = $module;
                    break;
                }
            }
            array_push($m, $module);
            if (!empty($successText)) {
                $this->line("<fg=green>\xE2\x9C\x94</> <fg=yellow>Paquetes ".$successText."</>");
            }
            if (!$withNoCompile) {
                $this->line("<fg=green>\xE2\x9C\x94</> <fg=yellow>Archivos compilados</>");
                if ($this->option('details')) {
                    echo $result;
                }
            }

            $index++;
        }

        if (!empty($m) && !$hasError) {
            if ($withCore) {
                $this->line("");
                $this->info("Se ha compilado los archivos de la aplicación");
            }
            $this->line("");
            $this->info("Se han compilado los siguientes módulos:");
            $this->line("");
            foreach ($m as $compiledModule) {
                $this->line("<fg=yellow>- $compiledModule</>");
            }
            $this->line("");
        }
        if ($hasError) {
            $this->info("<fg=red>Ocurrió un error en la compilación del módulo:</> $errorModule");
            $this->info("<fg=red>Detalles del error:</>");
            $this->info($errorMsg);
        }
        return 0;
    }
}
