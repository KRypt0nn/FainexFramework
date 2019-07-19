<?php

namespace Fainex;

use VoidEngine\Component;

class Bundle
{
    protected $base = null;
    protected $components = [];

    public function __construct (Component $base, array $components = [])
    {
        $this->base = $base;
        $this->components = $components;
    }

    public function set (string $name, object $object): Bundle
    {
        $this->components[$name] = $object;

        return $this;
    }

    public function remove (string $name): Bundle
    {
        if (isset ($this->components[$name]))
        {
            $component = $this->components[$name];
            unset ($this->components[$name]);

            $component->dispose ();
            unset ($component);
        }

        return $this;
    }

    public function search (string $name): ?object
    {
        if (isset ($this->components[$name]))
            return $this->components[$name];
        
        foreach ($this->components as $component)
            if ($component->name == $name || $component->selector == $name)
                return $component;

        return null;
    }

    public function __get (string $name)
    {
        return $this->$name ?? $this->base->$name;
    }

    public function __set (string $name, $value)
    {
        isset ($this->$name) ?
            $this->$name = $value :
            $this->base->$name = $value;
    }

    public function __call (string $name, array $args = [])
    {
        return method_exists ($this, $name) ?
            $this->$name (...$args) :
            $this->base->$name (...$args);
    }
}
