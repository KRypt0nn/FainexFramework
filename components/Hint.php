<?php

namespace Fainex;

use VoidEngine\{
    Label,
    WFClass
};

class Hint extends Component
{
    public function __construct (object $parent = null)
    {
        parent::__construct ($parent);

        $label = new Label ($parent);
        $label->backColor = clLight;
        $label->foreColor = clBlack;
        $label->textAlign = alMiddleLeft;
        $label->font = ['Segoe UI', 10];
        $label->visible = false;

        $label->textChangedEvent = function ($self)
        {
            $self->text = '  '. $self->text .'  ';

            $size = (new WFClass ('System.Windows.Forms.TextRenderer'))
                ->measureText ($self->text, \VoidEngine\VoidEngine::getProperty ($self->selector, 'Font'));

            $this->w = $size->width + 16;
        };

        $this->bundle = new Bundle ($label);
    }
}
