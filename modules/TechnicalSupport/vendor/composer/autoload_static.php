<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitfea66508788b9fe95da16a02dcb55b61
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'Modules\\TechnicalSupport\\' => 25,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Modules\\TechnicalSupport\\' => 
        array (
            0 => __DIR__ . '/../..' . '/',
        ),
    );

    public static $classMap = array (
        'Modules\\TechnicalSupport\\Database\\Seeders\\TechnicalSupportDatabaseSeeder' => __DIR__ . '/../..' . '/Database/Seeders/TechnicalSupportDatabaseSeeder.php',
        'Modules\\TechnicalSupport\\Http\\Controllers\\TechnicalSupportController' => __DIR__ . '/../..' . '/Http/Controllers/TechnicalSupportController.php',
        'Modules\\TechnicalSupport\\Providers\\RouteServiceProvider' => __DIR__ . '/../..' . '/Providers/RouteServiceProvider.php',
        'Modules\\TechnicalSupport\\Providers\\TechnicalSupportServiceProvider' => __DIR__ . '/../..' . '/Providers/TechnicalSupportServiceProvider.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitfea66508788b9fe95da16a02dcb55b61::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitfea66508788b9fe95da16a02dcb55b61::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitfea66508788b9fe95da16a02dcb55b61::$classMap;

        }, null, ClassLoader::class);
    }
}
