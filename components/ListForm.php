<?php

namespace Fainex;

use VoidEngine\{
    Form as VoidForm,
    Label,
    Panel,
    WFObject,
    WFClass
};

class ListForm extends Form
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

        $panel = new Panel ($form);
        $panel->bounds = [$form->clientSize[0] - 240, 0, 240, $form->h];
        $panel->anchor = acTop | acRight | acBottom;
        $panel->backColor = clDark;

        $head = new Label ($form);
        $head->bounds = [0, 0, $form->clientSize[0] - 240, 38];
        $head->anchor = acLeft | acTop | acRight;
        $head->foreColor = clBlack;
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

        $head2 = new Label ($panel);
        $head2->bounds = [0, 0, $panel->w, 38];
        $head2->anchor = acLeft | acTop | acRight;
        $head2->foreColor = clWhite;
        $head2->font = ['Segoe UI', 10];
        $head2->textAlign = alMiddleLeft;

        $head2->mouseDownEvent = function ($self, $args) use ($head, $form)
        {
            if ($args->button == 1048576)
            {
                $head->helpStorage = [$args->x, $args->y];
                $form->opacity = 0.97;
            }
        };

        $head2->mouseMoveEvent = function ($self, $args) use ($head, $form)
        {
            if (is_array ($head->helpStorage))
            {
                $form->x += $args->x - $head->helpStorage[0];
                $form->y += $args->y - $head->helpStorage[1];
            }
        };

        $head2->mouseUpEvent = function ($self, $args) use ($head, $form)
        {
            if ($args->button == 1048576)
            {
                $head->helpStorage = null;
                $form->opacity = 0.99;
            }
        };

        $head2->textChangedEvent = function ($self)
        {
            $self->text = '  '. $self->text;
        };

        $close = new Label ($head2);
        $close->bounds = [$head2->w - 32, 0, 32, 38];
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

        $minimize = new Label ($head2);
        $minimize->bounds = [$head2->w - 64, 0, 32, 38];
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
            'head2'    => $head2,
            'panel'    => $panel,
            'close'    => $close,
            'minimize' => $minimize
        ]);
    }
}
