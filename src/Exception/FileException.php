<?php

declare(strict_types=1);

namespace PhpFs\Exception;

use RuntimeException;
use Throwable;

class FileException extends RuntimeException
{
    public static function fileNotCreated(string $file, Throwable $previous = null): self
    {
        return new self(
            sprintf('The file "%s" could not be created. Maybe you have to create the directory first', $file),
            $previous?->getCode() ?? 0,
            $previous
        );
    }

    public static function noFile(string $noFile): self
    {
        return new self(
            sprintf('Given value "%s" doesn\'t appear to be a file.', $noFile)
        );
    }
}
