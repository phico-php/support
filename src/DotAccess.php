<?php

declare(strict_types=1);

namespace Phico;


/**
 * Provides dotted array property access
 */
trait DotAccess
{
    /**
     * Returns true if key is found in the array
     * @param array $items The array to search
     * @param string $key The name of the key to find
     * @return bool
     */
    protected function dotHas(array $items, string $key): bool
    {
        $parts = explode('.', $key);

        foreach ($parts as $part) {
            if (isset($items[$part])) {
                $items = $items[$part];
            } else {
                return false;
            }
        }

        return true;
    }
    /**
     * Returns a value by key
     * @param array $items The array to search
     * @param string $key The name of the key to find
     * @param mixed $default The value to return if not found
     * @return mixed
     */
    protected function dotGet(array $items, string $key, mixed $default = null): mixed
    {
        $parts = explode('.', $key);

        foreach ($parts as $part) {
            if (isset($items[$part])) {
                $items = $items[$part];
            } else {
                return $default;
            }
        }

        return $items;
    }
    /**
     * Sets a value by key
     * @param array $items The reference to the array to search
     * @param string $key The name of the key to set
     * @param mixed $value The value to set
     * @return void
     */
    protected function dotSet(array &$items, string $key, mixed $value): void
    {
        $parts = explode('.', $key);

        foreach ($parts as $part) {
            if (!isset($items[$part])) {
                $items[$part] = [];
            }
            $items = &$items[$part];
        }

        $items = $value;
    }
    /**
     * Removes the value named key
     * @param array $items The reference to the array to search
     * @param string $key The name of the key to unset
     * @return void
     */
    protected function dotUnset(array &$items, string $key): void
    {
        $parts = explode('.', $key);

        foreach ($parts as $i => $part) {
            if (isset($items[$part])) {
                if ($i === count($parts) - 1) {
                    unset($items[$part]);
                } else {
                    $items = &$items[$part];
                }
            } else {
                return;
            }
        }
    }
}
