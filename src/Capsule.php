<?php

declare(strict_types=1);

namespace Phico;

use BadMethodCallException;
use InvalidArgumentException;
use JsonSerializable;


/**
 * Encapsulates properties and data and provides useful accessor methods
 * @TODO use DotAccess? Use dotNormalise to explode by . then normalise
 */
class Capsule implements JsonSerializable
{
    /**
     * When true any set() methods will throw exceptions
     * @var bool
     */
    protected bool $readonly = false;
    /**
     * Keyed array of property name => values
     * @var array<string,mixed>
     */
    protected array $data = [];
    /**
     * Keyed array of property name => types
     * @var array<string,mixed>
     */
    protected array $props = [];


    public function __construct(array $data = [], bool $readonly = false)
    {
        foreach ($data as $k => $v) {
            $k = $this->normalise($k);
            $this->data[$k] = $v;
        }
        // once this is true, set methods will throw exceptions
        $this->readonly = $this->readonly ?? $readonly;
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
    /**
     * Returns a keyed array of all set property name => values
     * @return array<string,mixed>
     */
    public function all(): array
    {
        return $this->data;
    }
    /**
     * Returns a filtered keyed array of all set property name => values excluding the names in $props
     * @param array|string $props An array of property names to exclude
     * @return array<string,mixed>
     */
    public function except(array|string $props): array
    {
        // always work with arrays
        $props = is_string($props) ? [$props] : $props;
        // normalise props
        $props = array_map([$this, 'normalise'], $props);

        // return only the non-excluded props
        return array_filter($this->data, function ($k) use ($props) {
            return !in_array($k, $props);
        }, ARRAY_FILTER_USE_KEY);
    }
    /**
     * Returns a property value, or the default if it is not set
     *
     * Normalises the property name
     * Attempts to set the property using a defined set{PropertyName} method guessed from the name
     * Or sets the name and value directly.
     *
     * Throws an exception if called with an array and the default return value is not an array,
     * calling this method with $props as an array must always return an array
     *
     * @param array|string $prop The property names or name to get
     * @param mixed $default The value to return if the property is not found
     * @throws InvalidArgumentException When called with an array and a default return value
     * @return mixed
     */
    public function get(array|string $prop, mixed $default = null): mixed
    {
        // convenience option, returns an array if $prop is an array
        if (is_array($prop)) {
            // the type of default must be null or array
            if (!is_null($default) or !is_array($default)) {
                throw new InvalidArgumentException('Capsule::get() requires that $default be an array or null when called with an array of properties');
            }
            // pass to only()
            return $this->only($prop);
        }
        // normalise prop
        $prop = $this->normalise($prop);
        // check for getter method
        $method = sprintf('get%s', str()->toPascalCase($prop));

        // if getter method is available return the call
        if (method_exists($this, $method)) {
            return $this->$method($prop, $default);
        }

        // get directly, returning the value of $default if the property is not set
        return ($this->__isset($prop)) ? $this->data[$prop] : $default;
    }
    /**
     * Returns true if the names propery has been set
     * @param string $prop
     * @return bool
     */
    public function has(string $prop): bool
    {
        return $this->__isset($this->normalise($prop));
    }
    /**
     * Supports json serialisation
     * @return array<string,mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->all();
    }
    /**
     * Returns a filtered keyed array of all set property name => values returning only the names in $props
     * @param array|string $props An array of property names to return
     * @return array<string,mixed>
     */
    public function only(array|string $props): array
    {
        // always work with arrays
        $props = is_string($props) ? [$props] : $props;
        // normalise props
        $props = array_map([$this, 'normalise'], $props);

        // return only the specified props
        return array_filter($this->data, function ($k) use ($props) {
            return in_array($k, $props);
        }, ARRAY_FILTER_USE_KEY);
    }
    /**
     * Sets a property value.
     *
     * Normalises the property name
     * Attempts to set the property using a defined set{PropertyName} method guessed from the name
     * Or sets the name and value directly.
     *
     * Throws an exception if the readonly flag is set.
     * Throws an exception if the props array is defined and the property is unknown
     *
     * @param string $prop  The property name to set
     * @param mixed $value  The value to set
     * @throws BadMethodCallException If the readonly flag is set
     * @throws InvalidArgumentException If the readonly flag is set
     * @return Capsule
     */
    public function set(string $prop, mixed $value): self
    {
        // readonly check
        if ($this->readonly) {
            throw new BadMethodCallException(sprintf("'Cannot set property '%s' on class '%s' as it is readonly", $prop, __CLASS__));
        }
        // normalise prop
        $prop = $this->normalise($prop);
        // if using the props array ensure this prop is known
        if (!empty($this->props) and !array_key_exists($prop, $this->props)) {
            throw new InvalidArgumentException(sprintf("'Cannot set unknown property '%s' on class '%s', the property was not set in the props array", $prop, __CLASS__));
        }
        // check for setter method
        $method = sprintf('set%s', str()->toPascalCase($prop));

        // set via method if available or set directly
        if (method_exists($this, $method)) {
            $this->$method($value);
        } else {
            $this->data[$prop] = $value;
        }

        return $this;
    }
    /**
     * Removes the named property and value.
     *
     * Will not throw an Exception if the property is unknown
     *
     * @param string $prop
     * @return Capsule
     */
    public function unset(string $prop): self
    {
        $this->__unset($prop);
        return $this;
    }

    /**
     * Override this method to normalise property names
     * @param string $name
     * @return string
     */
    protected function normalise(string $name): string
    {
        return $name;
    }
}
