<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitc5e8818c62b8120edd850e34c2a12415
{
    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'Twilio\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Twilio\\' => 
        array (
            0 => __DIR__ . '/..' . '/twilio/sdk/src/Twilio',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitc5e8818c62b8120edd850e34c2a12415::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitc5e8818c62b8120edd850e34c2a12415::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitc5e8818c62b8120edd850e34c2a12415::$classMap;

        }, null, ClassLoader::class);
    }
}
