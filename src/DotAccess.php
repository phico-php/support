<?php

declare(strict_types=1);

namespace Phico\Support;


/**
 * Provides dotted array accessors
 */
trait DotAccess
{
    /**
     * Returns true if key is found in the array
     * @param string $items The name of the array to search
     * @param string $key The name of the key to find
     * @return bool
     */
    protected function dotHas(string $items, string $key): bool
    {
        $parts = explode('.', $key);
        $items = $this->$items;

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
     * @param string $items The name of the array to search
     * @param string $key The name of the key to find
     * @param mixed $default The value to return if not found
     * @return mixed
     */
    protected function dotGet(string $items, string $key, mixed $default = null): mixed
    {
        $parts = explode('.', $key);
        $items = $this->$items;

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
     * @param string $items The name of the array to search
     * @param string $key The name of the key to set
     * @param mixed $value The value to set
     * @return void
     */
    protected function dotSet(string $items, string $key, mixed $value): void
    {
        $parts = explode('.', $key);
        $items = &$this->$items;

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
     * @param string $items The name of the array to search
     * @param string $key The name of the key to unset
     * @return void
     */
    protected function dotUnset(string $items, string $key): void
    {
        $parts = explode('.', $key);
        $items = &$this->$items;

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
