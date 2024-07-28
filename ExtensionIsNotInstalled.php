<?php

declare(strict_types=1);

namespace Typhoon\ChangeDetector;

/**
 * @api
 */
final class ExtensionIsNotInstalled extends \RuntimeException
{
    /**
     * @param non-empty-string $name
     */
    public function __construct(string $name, ?\Throwable $previous = null)
    {
        parent::__construct(\sprintf('PHP extension "%s" is not installed', $name), previous: $previous);
    }
}
