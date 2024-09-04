<?php

declare(strict_types=1);

namespace Phico;

use JsonException;

/**
 * Provides string helper methods
 */
class Str
{
    /**
     * Returns data as a JSON encoded string
     * @param array|object $data The data to be encoded
     * @param int $flags Flags to apss to the json_encode function
     * @throws JsonException On error
     * @return string
     */
    public function fromJson(array|object $data, int $flags = 0): string
    {
        $flags = JSON_THROW_ON_ERROR + $flags;

        return json_encode($data, $flags, 32);
    }
    /**
     * Sanitises a string by converting special characters to HTML entities.
     *
     * @param string $str The string to sanitise.
     * @return string The sanitised string.
     */
    public function sanitise(string $str): string
    {
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }
    /**
     * Alias for the sanitise method to support different spelling (sanitize).
     *
     * @param string $str The string to sanitise.
     * @return string The sanitised string.
     */
    public function sanitize(string $str): string
    {
        return $this->sanitise($str);
    }
    /**
     * Splits a string at capital letters and adds spaces between them.
     *
     * @param string $str The string to split on capital letters.
     * @return string The modified string with spaces between capital letters.
     */
    public function splitOnCaps(string $str): string
    {
        $str = preg_replace('/\s{2,}/', ' ', trim($str));
        $parts = preg_split('/(?=[A-Z])/', $str, -1, PREG_SPLIT_NO_EMPTY);
        return join(' ', $parts);
    }
    /**
     * Converts a string to camelCase.
     *
     * @param string $str The string to convert to camelCase.
     * @return string The string in camelCase format.
     */
    public function toCamelCase(string $str): string
    {
        return lcfirst($this->toPascalCase($str));
    }
    /**
     * Converts a string to kebab-case.
     *
     * @param string $str The string to convert to kebab-case.
     * @return string The string in kebab-case format.
     */
    public function toKebabCase(string $str): string
    {
        return strtolower($this->toTrainCase(strtolower($str)));
    }
    /**
     * Converts a string to PascalCase.
     *
     * @param string $str The string to convert to PascalCase.
     * @return string The string in PascalCase format.
     */
    public function toPascalCase(string $str): string
    {
        $str = str_replace(['_', '-'], ' ', $this->splitOnCaps($str));
        $str = ucwords($str);
        return str_replace(' ', '', $str);
    }
    /**
     * Converts a string to snake_case.
     *
     * @param string $str The string to convert to snake_case.
     * @return string The string in snake_case format.
     */
    public function toSnakeCase(string $str): string
    {
        $str = str_replace(['_', '-'], ' ', $this->splitOnCaps($str));
        $str = strtolower($str);

        return str_replace(' ', '_', $str);
    }
    /**
     * Converts a string to Train-Case (also known as Title-Case with hyphens).
     *
     * @param string $str The string to convert to Train-Case.
     * @return string The string in Train-Case format.
     */
    public function toTrainCase(string $str): string
    {
        $str = str_replace(['_', '-'], ' ', $this->splitOnCaps($str));
        $str = ucwords($str);

        return str_replace(' ', '-', $str);
    }
    /**
     * Decodes the json encoded string returning as an array or object, depending on the $as_array flag
     * @param string $str The json encoded string to be decoded
     * @param bool $as_array Flag to set the default return type
     * @throws JsonException On error
     * @return array|object
     */
    public function toJson(string $str = '', bool $as_array = false): array|object
    {
        if (empty($str)) {
            return ($as_array) ? (object) [] : [];
        }

        return json_decode($str, $as_array, 512, JSON_THROW_ON_ERROR);
    }
}
