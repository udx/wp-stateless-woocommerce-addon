<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit01b1bb0889a0744cf0d09c7117ceb836
{
    public static $files = array (
        '597188d14662b5e311af3929f4072824' => __DIR__ . '/../..' . '/class-woocommerce.php',
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInit01b1bb0889a0744cf0d09c7117ceb836::$classMap;

        }, null, ClassLoader::class);
    }
}
