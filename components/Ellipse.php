<?php

namespace Fainex;

use VoidEngine\{
    Panel,
    WFObject
};

class Ellipse extends Component
{
    public function __construct (object $parent)
    {
        parent::__construct ($parent);

        $panel = new Panel ($parent);
        $panel->bounds = [16, 54, 32, 32];

        $panel->paintEvent = function ($self, $args)
        {
            $args->graphics->interpolationMode  = 7;
            $args->graphics->compositingQuality = 2;
            $args->graphics->pixelOffsetMode    = 2;
            $args->graphics->smoothingMode      = 4;
            
            $this->round ();
        };

        $this->bundle = new Bundle ($panel);
    }

    protected function round (): void
    {
        $bounds = new WFObject ('System.Drawing.Rectangle', 'auto', 0, 0, $this->w, $this->h);
        $path   = new WFObject ('System.Drawing.Drawing2D.GraphicsPath', 'System.Drawing');

        $path->addArc ($bounds->x, $bounds->y, $this->w, $this->h, 180, 90);
        $path->addArc ($bounds->x + $bounds->width - $this->w, $bounds->y, $this->w, $this->h, 270, 90);
        $path->addArc ($bounds->x + $bounds->width - $this->w, $bounds->y + $bounds->height - $this->h, $this->w, $this->h, 0, 90);
        $path->addArc ($bounds->x, $bounds->y + $bounds->height - $this->h, $this->w, $this->h, 90, 90);

        $path->closeAllFigures ();

        $this->region = new WFObject ('System.Drawing.Region', 'auto', $path->selector);
    }
}
