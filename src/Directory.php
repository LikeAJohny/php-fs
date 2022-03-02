<?php

declare(strict_types=1);

namespace PhpFs;

use PhpFs\Exception\DirectoryException;

class Directory
{
    public static function create(string $dir, int $permissions = 0777, bool $recursive = true): void
    {
        // https://github.com/kalessil/phpinspectionsea/blob/master/docs/probable-bugs.md#mkdir-race-condition
        if (!is_dir($dir) && !mkdir($dir, $permissions, $recursive) && !is_dir($dir)) {
            throw DirectoryException::directoryNotCreated($dir);
        }
    }

    public static function remove(string $dir): bool
    {
        self::validate($dir);

        $files = array_diff(scandir($dir), ['.', '..']);

        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? self::remove("$dir/$file") : unlink("$dir/$file");
        }

        return rmdir($dir);
    }

    public static function empty(string $dir): void
    {
        self::validate($dir);

        $files = array_diff(scandir($dir), ['.', '..']);

        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? self::remove("$dir/$file") : unlink("$dir/$file");
        }
    }

    public static function move(string $dir, string $targetDir): bool
    {
        self::create($targetDir);
        self::validate($dir);
        self::validate($targetDir);

        return rename($dir, $targetDir);
    }

    public static function copy(string $dir, string $targetDir): void
    {
        self::create($targetDir);
        self::validate($dir);
        self::validate($targetDir);

        $files = array_diff(scandir($dir), ['.', '..']);

        foreach ($files as $file) {
            (is_dir("$dir/$file"))
                ? self::copy("$dir/$file", "$targetDir/$file")
                : copy("$dir/$file", "$targetDir/$file");
        }
    }

    private static function validate(string $dir): void
    {
        if (!is_dir($dir)) {
            throw DirectoryException::noDirectory($dir);
        }
    }
}
