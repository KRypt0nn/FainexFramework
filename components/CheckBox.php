<?php

namespace Fainex;

use VoidEngine\{
    Label,
    Panel,
    WFObject,
    WFClass
};

// TODO: можно сделать событие "stateChanged"

class CheckBox extends Component
{
    protected $checked = false;

    public function __construct (object $parent)
    {
        parent::__construct ($parent);

        $panel = new Panel ($parent);
        $panel->bounds = [16, 54, 120, 24];

        $checkbox = new Label ($panel);
        $checkbox->bounds = [0, 0, $panel->h, $panel->h];
        $checkbox->anchor = acLeft | acTop | acBottom;
        $checkbox->backColor = clGreen;
        $checkbox->foreColor = clWhite;
        $checkbox->textAlign = alMiddleCenter;
        $checkbox->font = ['Webdings', 12];
        $checkbox->cursor = (new WFClass ('System.Windows.Forms.Cursors'))->hand;

        $checkbox->paintEvent = function ($self, $args)
        {
            $args->graphics->interpolationMode  = 7;
            $args->graphics->compositingQuality = 2;
            $args->graphics->pixelOffsetMode    = 2;
            $args->graphics->smoothingMode      = 4;

            $this->round ();
        };

        $checkbox->clickEvent = function ()
        {
            $this->set_checked (!$this->get_checked ());
        };

        $caption = new Label ($panel);
        $caption->bounds = [$checkbox->w + 8, 0, $panel->w - $checkbox->w - 8, $panel->h];
        $caption->anchor = acLeft | acRight | acTop | acBottom;
        $caption->foreColor = clBlack;
        $caption->textAlign = alMiddleLeft;
        $caption->font = ['Segoe UI', 10];

        $panel->sizeChangedEvent = function ($self) use ($checkbox, $caption)
        {
            $checkbox->w = $self->h;
            $checkbox->h = $self->h;

            $caption->x = $checkbox->w + 8;
            $caption->w = $self->w - $checkbox->w - 8;
        };

        $this->bundle = new Bundle ($panel, [
            'checkbox' => $checkbox,
            'caption'  => $caption
        ]);
    }

    public function get_checked (): bool
    {
        return $this->checked;
    }

    public function set_checked (bool $state): void
    {
        $this->checked = $state;

        $this->search ('checkbox')->caption = $state ?
            'a' : '';
    }

    protected function round (): void
    {
        $w = $this->search ('checkbox')->w;
        $h = $this->search ('checkbox')->h;

        $bounds = new WFObject ('System.Drawing.Rectangle', 'auto', 0, 0, $w, $h);
        $path   = new WFObject ('System.Drawing.Drawing2D.GraphicsPath', 'System.Drawing');

        $path->addArc ($bounds->x, $bounds->y, $w, $h, 180, 90);
        $path->addArc ($bounds->x + $bounds->width - $w, $bounds->y, $w, $h, 270, 90);
        $path->addArc ($bounds->x + $bounds->width - $w, $bounds->y + $bounds->height - $h, $w, $h, 0, 90);
        $path->addArc ($bounds->x, $bounds->y + $bounds->height - $h, $w, $h, 90, 90);

        $path->closeAllFigures ();

        $this->search ('checkbox')->region = new WFObject ('System.Drawing.Region', 'auto', $path->selector);
    }
}
