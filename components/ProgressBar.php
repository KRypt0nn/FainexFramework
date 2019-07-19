<?php

namespace Fainex;

use VoidEngine\{
    Panel,
    Label
};

class ProgressBar extends Component
{
    protected $position = 0;

    public function __construct (object $parent)
    {
        parent::__construct ($parent);

        $panel = new Panel ($parent);
        $panel->bounds = [16, 54, 196, 24];
        $panel->backColor = clLight2;

        $position = new Panel ($panel);
        $position->bounds = [0, 0, 0, $panel->h];
        $position->anchor = acLeft | acTop | acBottom;
        $position->backColor = clGreen;

        /*$label = new Label ($panel);
        $label->bounds = [0, 0, $panel->w, $panel->h];
        $label->anchor = acLeft | acRight | acTop | acBottom;
        $label->backColor = \VoidEngine\getARGBColor ('#FFFFFFFF');
        $label->foreColor = clBlack;
        $label->textAlign = alMiddleCenter;
        $label->font = ['Segoe UI', 10];
        $label->toFront ();*/

        $this->bundle = new Bundle ($panel, [
            'position' => $position,
            // 'label'    => $label
        ]);
    }

    public function get_position (): int
    {
        return $this->position;
    }

    public function set_position (int $position): void
    {
        $this->position = min (max ($position, 0), 100);
        $this->search ('position')->w = (int) $this->w / 100 * $this->position;
        // $this->search ('label')->caption = $position .'%';
    }
}
