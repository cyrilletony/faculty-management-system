<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit24e12f0f5993a4721e185ac8b78ac34b
{
    public static $files = array (
        '077c46ea4b0fe94d4bac6ac6d1c848fe' => __DIR__ . '/..' . '/copyleaks/php-plagiarism-checker/autoload.php',
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInit24e12f0f5993a4721e185ac8b78ac34b::$classMap;

        }, null, ClassLoader::class);
    }
}
