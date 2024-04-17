<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita34e192349c95243d461a337e378e52a
{
    public static $prefixLengthsPsr4 = array (
        'E' => 
        array (
            'Estudophp\\PhpBasic\\' => 19,
        ),
        'B' => 
        array (
            'Box\\Spout\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Estudophp\\PhpBasic\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'Box\\Spout\\' => 
        array (
            0 => __DIR__ . '/..' . '/box/spout/src/Spout',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita34e192349c95243d461a337e378e52a::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita34e192349c95243d461a337e378e52a::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInita34e192349c95243d461a337e378e52a::$classMap;

        }, null, ClassLoader::class);
    }
}
