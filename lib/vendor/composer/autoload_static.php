<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit4670a7a43d2bb819aa09845a33a564be
{
    public static $prefixLengthsPsr4 = array (
        'J' => 
        array (
            'Jfcherng\\Roundcube\\Plugin\\NthuEe\\' => 33,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Jfcherng\\Roundcube\\Plugin\\NthuEe\\' => 
        array (
            0 => __DIR__ . '/../..' . '/../src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit4670a7a43d2bb819aa09845a33a564be::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit4670a7a43d2bb819aa09845a33a564be::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
