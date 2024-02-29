<?php

declare(strict_types=1);

namespace Typhoon\Reflection\ClassLocator;

use Composer\Autoload\ClassLoader as Loader;
use Typhoon\Reflection\ClassLocator;
use Typhoon\Reflection\FileResource;

/**
 * @api
 */
final class ComposerClassLocator implements ClassLocator
{
    public static function isSupported(): bool
    {
        return class_exists(Loader::class);
    }

    public function locateClass(string $name): null|FileResource
    {
        foreach (Loader::getRegisteredLoaders() as $loader) {
            $file = $loader->findFile($name);

            if ($file !== false) {
                return new FileResource($file);
            }
        }

        return null;
    }
}
