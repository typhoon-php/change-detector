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
     */
    public function __construct(
        private readonly string $file,
        private readonly false|string $xxh3,
        private false|int $mtime = false,
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

        return new self($file, $xxh3, $mtime);
    }

    /**
     * @param non-empty-string $file
     */
    public static function fromFileAndContents(string $file, string $contents): self
    {
        return new self($file, hash(self::HASHING_ALGORITHM, $contents), @filemtime($file));
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
