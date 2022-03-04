<?php

declare(strict_types=1);

namespace PhpFs;

use PhpFs\Exception\FileException;
use Throwable;

class File
{
    public static function create(string $file): bool
    {
        try {
            $resource = fopen($file, 'wb');
        } catch (Throwable $e) {
            throw FileException::fileNotCreated($file, $e);
        }

        return (bool) $resource;
    }

    public static function remove(string $file): bool
    {
        self::validate($file);

        return unlink($file);
    }

    public static function copy(string $file, string $targetFile): bool
    {
        self::validate($file);

        return copy($file, $targetFile);
    }

    public static function move(string $file, string $targetFile): bool
    {
        self::validate($file);

        return rename($file, $targetFile);
    }

    private static function validate(string $file): void
    {
        if (!is_file($file)) {
            throw FileException::noFile($file);
        }
    }
}
