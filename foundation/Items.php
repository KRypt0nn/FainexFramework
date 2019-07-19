<?php

namespace Fainex;

class Items implements \ArrayAccess
{
    protected $callables = [
        'changingHandler' => null,
        'typeComparator'  => null
    ];

    protected $items = [];

    public function __construct (array $items = [])
    {
        $this->items = $items;
    }

    public function setChangingHandler (\Closure $handler): Items
    {
        $this->callables['changingHandler'] = $handler;

        return $this;
    }

    public function setTypeComparator (\Closure $comparator): Items
    {
        $this->callables['typeComparator'] = $comparator;

        return $this;
    }

    public function add ($item): Items
    {
        if ($this->callables['typeComparator'] !== null && !$this->callables['typeComparator'] ($item))
            throw new \Exception ('$item have wrong type');
        
        $this->items[] = $item;

        if ($this->callables['changingHandler'] !== null)
            $this->callables['changingHandler'] ($this);

        return $this;
    }

    public function addRange (array $items): Items
    {
        foreach ($items as $item)
        {
            if ($this->callables['typeComparator'] !== null && !$this->callables['typeComparator'] ($item))
                throw new \Exception ('$item have wrong type');

            $this->items[] = $item;
        }

        if ($this->callables['changingHandler'] !== null)
            $this->callables['changingHandler'] ($this);

        return $this;
    }

    public function set (string $id, $item): Items
    {
        if ($this->callables['typeComparator'] !== null && !$this->callables['typeComparator'] ($item))
            throw new \Exception ('$item have wrong type');
        
        $this->items[$id] = $item;

        if ($this->callables['changingHandler'] !== null)
            $this->callables['changingHandler'] ($this);

        return $this;
    }

    public function remove ($item): Items
    {
        foreach ($this->items as $id => $eitem)
            if ($eitem == $item)
                unset ($this->items[$id]);

        if ($this->callables['changingHandler'] !== null)
            $this->callables['changingHandler'] ($this);

        return $this;
    }

    public function removeAt (string $id): Items
    {
        unset ($this->items[$id]);

        if ($this->callables['changingHandler'] !== null)
            $this->callables['changingHandler'] ($this);

        return $this;
    }

    public function foreach (\Closure $callable): Items
    {
        foreach ($this->items as $id => $item)
            $callable ($item, $id);

        return $this;
    }

    public function where (\Closure $comparator): Items
    {
        $items = [];

        foreach ($this->items as $id => $item)
            if ($comparator ($item, $id))
                $items[$id] = $item;

        return new Item ($items);
    }

    public function offsetExists ($offset) : bool
    {
        return isset ($this->items[$offset]);
    }

    public function offsetGet ($offset) : mixed
    {
        return $this->items[$offset];
    }

    public function offsetSet ($offset, $value) : void
    {
        if ($this->callables['typeComparator'] !== null && !$this->callables['typeComparator'] ($item))
            throw new \Exception ('$item have wrong type');
        
        $this->items[$offset] = $value;

        if ($this->callables['changingHandler'] !== null)
            $this->callables['changingHandler'] ($this);
    }

    public function offsetUnset ($offset) : void
    {
        unset ($this->items[$offset]);
        
        if ($this->callables['changingHandler'] !== null)
            $this->callables['changingHandler'] ($this);
    }
}
