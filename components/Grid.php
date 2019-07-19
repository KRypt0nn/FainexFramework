<?php

namespace Fainex;

use VoidEngine\Panel;

class Grid extends Component
{
    protected $act_x = 8;
    protected $act_y = 8;
    protected $max_h = 0;

    public function __construct (object $parent)
    {
        parent::__construct ($parent);

        $panel = new Panel ($parent);
        $panel->bounds = [16, 54, 160, 64];
        $panel->autoScroll = true;

        $this->bundle = new Bundle ($panel);
    }

    public function append (object $item): Grid
    {
        $item->parent = $this->base;
        $this->max_h  = max ($this->max_h, $item->h);

        if ($this->act_x + $item->w > $this->w - 16)
        {
            $this->act_x  = 8;
            $this->act_y += $this->max_h + 8;

            $this->max_h = 0;
        }

        $item->location = [$this->act_x, $this->act_y];
        $this->act_x += $item->w + 8;

        return $this;
    }
}
