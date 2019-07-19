<?php

namespace Fainex;

use VoidEngine\{
    Form as VoidForm,
    Label,
    WFObject,
    WFClass
};

class Form extends Component
{
    public $roundRadius = 25;

    public function __construct (object $parent = null)
    {
        parent::__construct ($parent);

        $form = new VoidForm ($parent);
        $form->doubleBuffered = true;
        $form->backColor = clLight;
        $form->startPosition = fspCenterScreen;
        $form->formBorderStyle = fbsNone;
        $form->opacity = 0.99;

        $form->paintEvent = function ($self, $args)
        {
            $args->graphics->interpolationMode  = 7;
            $args->graphics->compositingQuality = 2;
            $args->graphics->pixelOffsetMode    = 2;
            $args->graphics->smoothingMode      = 4;
            
            $this->round ();
        };

        $head = new Label ($form);
        $head->bounds = [0, 0, $form->clientSize[0], 38];
        $head->anchor = acLeft | acTop | acRight;
        $head->backColor = clDark;
        $head->foreColor = clWhite;
        $head->font = ['Segoe UI', 10];
        $head->textAlign = alMiddleLeft;

        $head->mouseDownEvent = function ($self, $args) use ($form)
        {
            if ($args->button == 1048576)
            {
                $self->helpStorage = [$args->x, $args->y];
                $form->opacity = 0.97;
            }
        };

        $head->mouseMoveEvent = function ($self, $args) use ($form)
        {
            if (is_array ($self->helpStorage))
            {
                $form->x += $args->x - $self->helpStorage[0];
                $form->y += $args->y - $self->helpStorage[1];
            }
        };

        $head->mouseUpEvent = function ($self, $args) use ($form)
        {
            if ($args->button == 1048576)
            {
                $self->helpStorage = null;
                $form->opacity = 0.99;
            }
        };

        $head->textChangedEvent = function ($self)
        {
            $self->text = '  '. $self->text;
        };

        $close = new Label ($head);
        $close->bounds = [$head->w - 32, 0, 32, 38];
        $close->anchor = acTop | acRight;
        $close->foreColor = clGrey;
        $close->font = ['Webdings', 9];
        $close->textAlign = alMiddleCenter;
        $close->caption = 'r';
        $close->cursor = (new WFClass ('System.Windows.Forms.Cursors'))->hand;

        $close->mouseEnterEvent = function ($self)
        {
            $self->foreColor = clLight;
        };

        $close->mouseLeaveEvent = function ($self)
        {
            $self->foreColor = clGrey;
        };

        $close->clickEvent = function () use ($form)
        {
            $form->close ();
        };

        $minimize = new Label ($head);
        $minimize->bounds = [$head->w - 64, 0, 32, 38];
        $minimize->anchor = acTop | acRight;
        $minimize->foreColor = clGrey;
        $minimize->font = ['Segoe UI', 16];
        $minimize->textAlign = alMiddleCenter;
        $minimize->caption = '-';
        $minimize->cursor = (new WFClass ('System.Windows.Forms.Cursors'))->hand;

        $minimize->mouseEnterEvent = function ($self)
        {
            $self->foreColor = clLight;
        };

        $minimize->mouseLeaveEvent = function ($self)
        {
            $self->foreColor = clGrey;
        };

        $minimize->clickEvent = function () use ($form)
        {
            $form->windowState = 1;
        };

        $this->bundle = new Bundle ($form, [
            'head'     => $head,
            'close'    => $close,
            'minimize' => $minimize
        ]);
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
