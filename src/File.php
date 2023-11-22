<?php

declare(strict_types=1);

namespace PhpFs;

use PhpFs\Exception\FileException;
use Throwable;

use function file_exists;
use function file_put_contents;
use function fopen;

class File
{
    public static function create(string $file): bool
    {
        $resource = fopen($file, 'wb');

        if (!$resource) {
            throw FileException::fileNotCreated($file);
        }

        return true;
    }

    public static function exists(string $file): bool
    {
        return file_exists($file);
    }

    public static function write(string $file, string $content): bool
    {
        self::validate($file);

        return (bool)file_put_contents($file, $content);
    }

    public static function append(string $file, string $content): bool
    {
        self::validate($file);

        return (bool)file_put_contents($file, self::read($file) . $content);
    }

    public static function prepend(string $file, string $content): bool
    {
        self::validate($file);

        return (bool)file_put_contents($file, $content . self::read($file));
    }

    public static function read(string $file): string
    {
        return file_get_contents($file);
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
