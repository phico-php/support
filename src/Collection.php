<?php

declare(strict_types=1);

namespace Phico;


/**
 * Manages a collection of Capsule instances
 */
class Collection implements \Iterator
{
    private $position = 0;
    private array $items = [];


    public function __construct()
    {
        $this->position = 0;
    }

    public function all(): array
    {
        return $this->items;
    }

    public function add(Capsule $item): self
    {
        $this->items[] = $item;
        return $this;
    }
    public function find(): array
    {

    }
    public function map(): array
    {

    }
    public function match(array $matches): array
    {
        // set output array
        $out = [];
        // get total number of properties to match on
        $count = count($matches);

        foreach ($this->items as $item) {
            $matched = 0;
            foreach ($matches as $k => $v) {
                if ($item->$k == $v) {
                    $matched++;
                }
            }

            // number of matches equals the number of props needing to be matched
            if ($matched == $count) {
                $out[] = $item;
            }
        }

        return $out;
    }
    public function reduce(): array
    {

    }



    public function rewind(): void
    {
        $this->position = 0;
    }

    #[\ReturnTypeWillChange]
    public function current()
    {
        return $this->items[$this->position];
    }

    #[\ReturnTypeWillChange]
    public function key()
    {
        return $this->position;
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function valid(): bool
    {
        return isset($this->items[$this->position]);
    }
}
