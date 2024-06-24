<?php

declare(strict_types=1);

namespace Phico;

use Exception;


class PhicoException extends Exception
{
    public function toArray(): array
    {
        return [
            'code' => $this->getCode(),
            'message' => $this->getMessage(),
        ];
    }

    public function toString(): string
    {
        return sprintf(
            '%s %s in file %s on line %d',
            get_class($this),
            $this->getMessage(),
            $this->getFile(),
            $this->getLine()
        );
    }
}
