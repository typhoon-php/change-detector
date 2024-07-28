<?php

declare(strict_types=1);

namespace Typhoon\ChangeDetector;

/**
 * @api
 */
final class InMemoryChangeDetector implements ChangeDetector
{
    private bool $changed = false;

    public function changed(): bool
    {
        return $this->changed;
    }

    public function deduplicate(): array
    {
        return [self::class . json_encode(get_object_vars($this)) => $this];
    }

    public function __serialize(): array
    {
        return [];
    }

    public function __unserialize(array $_data): void
    {
        $this->changed = true;
    }
}
