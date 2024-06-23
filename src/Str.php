<?php

declare(strict_types=1);

namespace Phico\Support;

use Phico\PhicoException;

use Throwable;

class Str
{
    public function fromJson(array|object $data, int $flags = 0): string
    {
        try {
            $flags = JSON_THROW_ON_ERROR + $flags;
            return json_encode($data, $flags, 32);
        } catch (Throwable $th) {
            $e = new PhicoException("Failed to encode object to json", 1010, $th);
            logger()->error($e->toString());
            throw $e;
        }
    }
    public function sanitise(string $str): string
    {
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }
    public function sanitize(string $str): string
    {
        return $this->sanitise($str);
    }
    public function splitOnCaps(string $str): string
    {
        $str = preg_replace('/\s{2,}/', ' ', trim($str));
        $parts = preg_split('/(?=[A-Z])/', $str, -1, PREG_SPLIT_NO_EMPTY);
        return join(' ', $parts);
    }
    public function toCamelCase(string $str): string
    {
        return lcfirst($this->toPascalCase($str));
    }
    public function toKebabCase(string $str): string
    {
        return strtolower($this->toTrainCase(strtolower($str)));
    }
    public function toPascalCase(string $str): string
    {
        $str = $this->splitOnCaps($str);
        $str = ucwords($str);
        return str_replace(' ', '', $str);
    }
    public function toTrainCase(string $str): string
    {
        $str = str_replace(['_', '-'], ' ', $this->splitOnCaps($str));
        $str = ucwords($str);

        return str_replace(' ', '-', $str);
    }
    public function toJson(string $str, bool $as_array = false): array|object
    {
        if (empty($str)) {
            return ($as_array) ? (object) [] : [];
        }

        try {
            return json_decode($str, $as_array, 512, JSON_THROW_ON_ERROR);
        } catch (Throwable $th) {
            throw new PhicoException("Failed to decode invalid json", 1010, $th);
        }
    }
}
