<?php

declare(strict_types=1);

namespace PhpFs\Exception;

use RuntimeException;

class FileException extends RuntimeException
{
    public static function noFile(string $noFile): self
    {
        return new self(
            sprintf('Given value "%s" doesn\'t appear to be a file.', $noFile)
        );
    }
}
