<?php

declare(strict_types=1);

namespace Typhoon\ChangeDetector;

/**
 * @api
 */
final class FileChangeDetector implements ChangeDetector
{
    public const HASHING_ALGORITHM = 'xxh3';

    /**
     * @param non-empty-string $file
     * @param false|non-empty-string $xxh3
     * @todo make $mtime optional with default false
     */
    public function __construct(
        private readonly string $file,
        private false|int $mtime,
        private readonly false|string $xxh3,
    ) {}

    /**
     * @param non-empty-string $file
     */
    public static function fromFile(string $file): self
    {
        $mtime = @filemtime($file);
        $xxh3 = @hash_file(self::HASHING_ALGORITHM, $file);

        if ($xxh3 === false) {
            throw new FileIsNotReadable($file);
        }

        return new self($file, $mtime, $xxh3);
    }

    /**
     * @param non-empty-string $file
     */
    public static function fromFileAndContents(string $file, string $contents): self
    {
        return new self($file, @filemtime($file), hash(self::HASHING_ALGORITHM, $contents));
    }

    public function changed(): bool
    {
        $mtime = @filemtime($this->file);

        if ($this->mtime !== false && $mtime === $this->mtime) {
            return false;
        }

        if (@hash_file(self::HASHING_ALGORITHM, $this->file) === $this->xxh3) {
            $this->mtime = $mtime;

            return false;
        }

        return true;
    }

    public function deduplicate(): array
    {
        return [\sprintf('%s.%s.%s', self::class, $this->file, (string) $this->xxh3) => $this];
    }
}
