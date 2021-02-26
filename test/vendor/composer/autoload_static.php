<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitec09c5a7f50548e5792fc81b7dd54252
{
    public static $prefixLengthsPsr4 = array (
        'p' => 
        array (
            'phpapi\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'phpapi\\' => 
        array (
            0 => __DIR__ . '/..' . '/hrishikesh214/php-api/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitec09c5a7f50548e5792fc81b7dd54252::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitec09c5a7f50548e5792fc81b7dd54252::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitec09c5a7f50548e5792fc81b7dd54252::$classMap;

        }, null, ClassLoader::class);
    }
}