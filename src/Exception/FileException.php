<?php

declare(strict_types=1);

namespace PhpFs\Exception;

use RuntimeException;

use function sprintf;

class FileException extends RuntimeException
{
    public static function createError(string $file, ?array $error): self
    {
        return new self(
            sprintf('Could not create file "%s". %s', $file, $error['message'] ?? '')
        );
    }

    public static function noFile(string $file): self
    {
        return new self(
            sprintf('Given value "%s" doesn\'t appear to be a file.', $file)
        );
    }

    public static function notReadable(string $file): self
    {
        return new self(
            sprintf('Given file "%s" is not readable.', $file)
        );
    }

    public static function readError(string $file, ?array $error): self
    {
        return new self(
            sprintf('Could not read from file "%s". %s', $file, $error['message'] ?? ''),
        );
    }

    public static function notWriteable(string $file): self
    {
        return new self(
            sprintf('Given file "%s" is not writeable.', $file)
        );
    }

    public static function writeError(string $file, ?array $error): self
    {
        return new self(
            sprintf('Could not write to file "%s". %s', $file, $error['message'] ?? '')
        );
    }
}
