<?php

declare(strict_types=1);

namespace PhpFs;

use PhpFs\Exception\DirectoryException;

use function is_dir;

class Directory
{
    public static function create(string $dir, int $permissions = 0766, bool $recursive = true): void
    {
        // https://github.com/kalessil/phpinspectionsea/blob/master/docs/probable-bugs.md#mkdir-race-condition
        if (!is_dir($dir) && !mkdir($dir, $permissions, $recursive) && !is_dir($dir)) {
            throw DirectoryException::directoryNotCreated($dir);
        }
    }

    public static function exists(string $dir): bool
    {
        return is_dir($dir);
    }

    public static function remove(string $dir): bool
    {
        self::validate($dir);

        $files = array_diff(scandir($dir), ['.', '..']);

        foreach ($files as $file) {
            $source = "$dir/$file";

            is_dir("$source")
                ? self::remove("$source")
                : File::remove("$source");
        }

        return rmdir($dir);
    }

    public static function empty(string $dir): void
    {
        self::validate($dir);

        $files = array_diff(scandir($dir), ['.', '..']);

        foreach ($files as $file) {
            $source = "$dir/$file";

            is_dir("$source")
                ? self::remove("$source")
                : File::remove("$source");
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
            $source = "$dir/$file";
            $destination = "$targetDir/$file";

            is_dir("$source")
                ? self::copy("$source", "$destination")
                : File::copy("$source", "$destination");
        }
    }

    private static function validate(string $dir): void
    {
        if (!is_dir($dir)) {
            throw DirectoryException::noDirectory($dir);
        }
    }
}
