<?php

declare(strict_types=1);

namespace PhpFilesystem\Exception;

use RuntimeException;

class DirectoryException extends RuntimeException
{
    public static function directoryNotCreated(string $dir): self
    {
        return new self(
            sprintf('The directory "%s" could not be created', $dir)
        );
    }

    public static function noDirectory(string $noDir): self
    {
        return new self(
            sprintf('Given value "%s" doesn\'t appear to be a directory', $noDir)
        );
    }
}
