<?php

namespace Fainex;

use VoidEngine\{
    Panel,
    WFObject,
    WFClass
};

class ListBox extends Component
{
    public $roundRadius = 15;
    public $items = [];

    public function __construct (object $parent)
    {
        parent::__construct ($parent);

        $panel = new Panel ($parent);
        $panel->bounds = [16, 54, 96, 128];
        $panel->backColor = clLight;
        $panel->autoScroll = true;

        $panel->paintEvent = function ($self, $args)
        {
            $args->graphics->interpolationMode  = 7;
            $args->graphics->compositingQuality = 2;
            $args->graphics->pixelOffsetMode    = 2;
            $args->graphics->smoothingMode      = 4;

            $this->round ();
        };

        /*$this->items->setChangingHandler (function ($items) use ($panel)
        {
            $panel->h = $this->getHeight ();
        });*/

        /*$this->items->setTypeComparator (function (&$item) use ($panel)
        {
            if (is_string ($item))
            {
                $caption = $item;

                $item = new Button ($panel);
                $item->caption = \VoidEngine\text ($caption);
                $item->h = 24;
            }

            if ($item instanceof Button)
            {
                $item->parent    = $panel;
                $item->bounds    = [0, $this->getHeight (), $panel->w, $item->h];
                $item->textAlign = alMiddleCenter;
                $item->cursor    = (new WFClass ('System.Windows.Forms.Cursors'))->hand;

                return true;
            }

            return false;
        });*/

        $this->bundle = new Bundle ($panel);
    }

    public function addItem (string $item): ListBox
    {
        $button = new Button ($this);
        $button->caption   = \VoidEngine\text ('  '. $item);
        $button->bounds    = [0, $this->getHeight (), $this->w, 32];
        $button->textAlign = alMiddleLeft;
        $button->cursor    = (new WFClass ('System.Windows.Forms.Cursors'))->hand;

        $this->items[] = $button;

        return $this;
    }

    public function addItems (array $items): ListBox
    {
        foreach ($items as $item)
            $this->addItem ($item);

        return $this;
    }

    public function removeItem (string $item): ListBox
    {
        foreach ($this->items as $id => $eitem)
            if (substr ($eitem->caption, 0, 2) == $item)
            {
                $h = $eitem->h;

                $eitem->visible = false;
                $eitem->dispose ();

                unset ($this->items[$id]);

                foreach ($this->items as $id2 => $eitem2)
                    if ($id2 >= $id)
                        $eitem2->h -= $h;
            }

        return $this;
    }

    public function removeItems (array $items): ListBox
    {
        foreach ($items as $item)
            $this->removeItem ($item);
        
        return $this;
    }

    public function indexOf (string $item): int
    {
        foreach ($this->items as $id => $eitem)
            if (substr ($eitem->caption, 2) == $item)
                return $id;

        return -1;
    }

    public function getHeight (): int
    {
        $height = 0;

        foreach ($this->items as $item)
            $height += $item->h;

        return $height;
    }

    protected function round (): void
    {
        $bounds = new WFObject ('System.Drawing.Rectangle', 'auto', 0, 0, $this->w, $this->h);
        $path   = new WFObject ('System.Drawing.Drawing2D.GraphicsPath', 'System.Drawing');

        $path->addArc ($bounds->x, $bounds->y, $this->roundRadius, $this->roundRadius, 180, 90);
        $path->addArc ($bounds->x + $bounds->width - $this->roundRadius, $bounds->y, $this->roundRadius, $this->roundRadius, 270, 90);
        $path->addArc ($bounds->x + $bounds->width - $this->roundRadius, $bounds->y + $bounds->height - $this->roundRadius, $this->roundRadius, $this->roundRadius, 0, 90);
        $path->addArc ($bounds->x, $bounds->y + $bounds->height - $this->roundRadius, $this->roundRadius, $this->roundRadius, 90, 90);

        $path->closeAllFigures ();

        $this->region = new WFObject ('System.Drawing.Region', 'auto', $path->selector);
    }
}
