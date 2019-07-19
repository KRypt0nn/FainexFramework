<?php

namespace Fainex;

use VoidEngine\Label;

class Button extends Component
{
    public $firstColor  = clLight;
    public $secondColor = clLight2;

    public function __construct (object $parent)
    {
        parent::__construct ($parent);

        $label = new Label ($parent);
        $label->bounds = [16, 54, 96, 24];
        $label->backColor = $this->firstColor;
        $label->foreColor = clBlack;
        $label->textAlign = alMiddleLeft;
        $label->font = ['Segoe UI', 10];

        $label->mouseEnterEvent = function ($self)
        {
            $self->backColor = $this->secondColor;
        };

        $label->mouseLeaveEvent = function ($self)
        {
            $self->backColor = $this->firstColor;
        };

        /*$label->textChangedEvent = function ($self)
        {
            $self->text = '  '. $self->text;
        };*/

        $this->bundle = new Bundle ($label);
    }
}
