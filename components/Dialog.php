<?php

namespace Fainex;

class Dialog extends Form
{
    public function __construct (object $parent = null)
    {
        parent::__construct ($parent);

        $head = $this->search ('head');
        $head->backColor = clLight;
        $head->foreColor = clGray;
        $head->textAlign = alMiddleCenter;

        $this->search ('close')->mouseEnterEvent = function ($self)
        {
            $self->foreColor = clBlack;
        };

        $this->search ('minimize')->mouseEnterEvent = function ($self)
        {
            $self->foreColor = clBlack;
        };
    }
}
