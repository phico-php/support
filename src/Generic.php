<?php

declare(strict_types=1);

namespace Phico;


class Generic
{
    protected array $data = [];
    protected bool $readonly = false;
    protected bool $normalise = false;


    public function __construct(array $data = [], bool $readonly = false, bool $normalise = false)
    {
        foreach ($data as $k => $v) {
            if ($this->normalise) {
                $k = $this->normalise($k);
            }
            $this->data[$k] = $v;
        }
        $this->readonly = $this->readonly ?? $readonly;
        $this->normalise = $this->normalise ?? $normalise;
    }
    public function __get(string $name): mixed
    {
        return $this->get($name);
    }
    public function __set(string $name, mixed $value): void
    {
        $this->set($name, $value);
    }
    public function __isset(string $name): bool
    {
        return array_key_exists($name, $this->data);
    }
    public function __unset(string $name): void
    {
        unset($this->data[$name]);
    }

    public function all(): array
    {
        return $this->data;
    }
    public function has(string $prop): bool
    {
        if ($this->normalise) {
            $prop = $this->normalise($prop);
        }
        return $this->__isset($prop);
    }
    public function get(array|string $prop, mixed $default = null): mixed
    {
        if (is_array($prop)) {
            return $this->getMany($prop);
        }

        $method = sprintf('get%s', str()->toCamelCase($prop));
        if (method_exists($this, $method)) {
            return $this->$method($prop, $default);
        }

        if ($this->normalise) {
            $prop = $this->normalise($prop);
        }
        return ($this->__isset($prop)) ? $this->data[$prop] : $default;
    }
    public function getMany(array $props): array
    {
        $out = [];
        foreach ($props as $prop) {
            $out[$prop] = $this->get($prop);
        }

        return array_filter($out);
    }
    public function set(string $prop, mixed $value): self
    {
        if ($this->readonly) {
            throw new \BadMethodCallException(sprintf("'Cannot set property '%s' on class '%s' as it is readonly", $prop, __CLASS__));
        }

        $method = sprintf('set%s', str()->toCamelCase($prop));
        if (method_exists($this, $method)) {
            $this->$method($value);
            return $this;
        }

        if ($this->normalise) {
            $prop = $this->normalise($prop);
        }

        $this->data[$prop] = $value;

        return $this;
    }
    public function toArray(): array
    {
        return $this->data;
    }

    protected function normalise(string $name): string
    {
        return str_replace('-', '_', strtolower($name));
    }

}
