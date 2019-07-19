<?php

namespace Fainex;

class Component
{
    public $helpStorage = null;

    protected $parent   = null;
    protected $selector = null;
    protected $name     = null;
    protected $bundle   = null;
    protected $hint     = null;

    public function __construct (object &$parent = null)
    {
        while ($parent instanceof Component)
            $parent = $parent->bundle->base;

        $this->parent = $parent;

        do
        {
            $this->selector = random_int (10000000, 99999999);
        }

        while (f($this->selector) !== null);

        ObjectsManager::add ($this);
    }

    public function get_name (): ?string
    {
        return $this->name;
    }

    public function set_name (string $name): void
    {
        if (f($name) !== null && $this->name != $name)
            throw new \Exception ('This name already used');

        $this->name = $name;
    }

    /*public function get_hint (): ?string
    {
        return $this->hint !== null ?
            $this->hint->caption : null;
    }

    public function set_hint (string $hint): void
    {
        if ($this->hint === null)
        {
            $this->hint = new Hint ($this->parent);

            $this->mouseHoverEvent = function ($self, $args)
            {
                \VoidEngine\pre ($this->hint);

                // $this->hint->visible  = true;
                // $this->hint->location = [$args->x, $args->y];
            };

            $this->mouseLeaveEvent = function ()
            {
                $this->hint->visible = false;
            };
        }

        $this->hint->caption = $hint;
        $this->hint->toFront ();
    }*/

    public function __get (string $name)
    {
        return method_exists ($this, $method = 'get_'. $name) ?
            $this->$method () : ($this->$name ?? $this->bundle->$name);
    }

    public function __set (string $name, $value)
    {
        method_exists ($this, $method = 'set_'. $name) ?
            $this->$method ($value) : (isset ($this->$name) ?
                $this->$name = $value :
                $this->bundle->$name = $value);
    }

    public function __call (string $name, array $args = [])
    {
        return method_exists ($this, $name) ?
            $this->$name (...$args) :
            $this->bundle->$name (...$args);
    }

    public function dispose ()
    {
        array_map (function ($item)
        {
            $item->dispose ();
        }, $this->bundle->components);

        $this->bundle->base->dispose ();
        unset ($this->bundle);
    }
}
