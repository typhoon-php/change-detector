<?php

declare(strict_types=1);

namespace Typhoon\ChangeDetector;

/**
 * @api
 */
final class FileChangeDetector implements ChangeDetector
{
    /**
     * @param non-empty-string $file
     * @param non-empty-string $hash
     */
    private function __construct(
        private readonly string $file,
        private readonly int $mtime,
        private readonly string $hash,
    ) {}

    /**
     * @param non-empty-string $file
     * @throws FileIsNotReadable
     */
    public static function fromFile(string $file): self
    {
        $contents = @file_get_contents($file);

        if ($contents === false) {
            throw new FileIsNotReadable($file);
        }

        return self::fromFileAndContents($file, $contents);
    }

    /**
     * @param non-empty-string $file
     * @throws FileIsNotReadable
     */
    public static function fromFileAndContents(string $file, string $contents): self
    {
        $mtime = @filemtime($file);

        if ($mtime === false) {
            throw new FileIsNotReadable($file);
        }

        return new self(file: $file, mtime: $mtime, hash: md5($contents));
    }

    public function changed(): bool
    {
        return filemtime($this->file) !== $this->mtime || md5_file($this->file) !== $this->hash;
    }

    public function deduplicate(): array
    {
        return [$this->file . '#file' => $this];
    }
}
