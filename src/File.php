<?php

declare(strict_types=1);

namespace PhpFs;

use PhpFs\Exception\FileException;

use function chmod;
use function error_get_last;
use function file_exists;
use function file_get_contents;
use function file_put_contents;
use function fileperms;
use function finfo_close;
use function finfo_file;
use function finfo_open;
use function finfo_set_flags;
use function fopen;
use function is_file;
use function is_readable;
use function is_writeable;
use function pathinfo;
use function unlink;

class File
{
    public static function create(string $file, int $mode = 0766): mixed
    {
        $resource = fopen($file, 'wb');
        if ($resource === false || !chmod($file, $mode)) {
            throw FileException::createError($file, error_get_last());
        }

        return $resource;
    }

    public static function exists(string $file): bool
    {
        return file_exists($file);
    }

    public static function info(string $file): array
    {
        self::validateRead($file);

        $info = [...pathinfo($file)];
        $info['permissions'] = substr(sprintf('%o', fileperms($file)), -4);

        $handle = finfo_open(FILEINFO_MIME_TYPE);
        $info['mime_type'] = finfo_file($handle, $file);
        finfo_set_flags($handle, FILEINFO_MIME_ENCODING);
        $info['mime_encoding'] = finfo_file($handle, $file);
        finfo_close($handle);

        return $info;
    }

    public static function write(string $file, string $content): bool|int
    {
        self::validateWrite($file);

        $bytes = file_put_contents($file, $content);
        if ($bytes === false) {
            throw FileException::writeError($file, error_get_last());
        }

        return $bytes;
    }

    public static function append(string $file, string $content): bool|int
    {
        self::validateWrite($file);

        $bytes = file_put_contents($file, self::read($file).$content);
        if ($bytes === false) {
            throw FileException::writeError($file, error_get_last());
        }

        return $bytes;
    }

    public static function prepend(string $file, string $content): bool|int
    {
        self::validateWrite($file);

        $bytes = file_put_contents($file, $content.self::read($file));
        if ($bytes === false) {
            throw FileException::writeError($file, error_get_last());
        }

        return $bytes;
    }

    public static function read(string $file): string
    {
        self::validateRead($file);

        $content = file_get_contents($file);
        if ($content === false) {
            throw FileException::readError($file, error_get_last());
        }

        return $content;
    }

    public static function copy(string $file, string $targetFile): bool
    {
        self::validateFile($file);

        return copy($file, $targetFile);
    }

    public static function move(string $file, string $targetFile): bool
    {
        self::validateFile($file);

        return rename($file, $targetFile);
    }

    public static function remove(string $file): bool
    {
        self::validateFile($file);

        return unlink($file);
    }

    private static function validateRead(string $file): void
    {
        self::validateFile($file);

        if (!is_readable($file)) {
            throw FileException::notReadable($file);
        }
    }

    private static function validateWrite(string $file): void
    {
        self::validateFile($file);

        if (!is_writeable($file)) {
            throw FileException::notWriteable($file);
        }
    }

    private static function validateFile(string $file): void
    {
        if (!is_file($file)) {
            throw FileException::noFile($file);
        }
    }
}
