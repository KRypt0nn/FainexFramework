<?php

namespace Fainex;

use VoidEngine\{
    Label,
    WFClass
};

// TODO: панелька с уведомлениями

\VoidEngine\setTimer (1000, function ()
{
    foreach (Notification::$queue as $id => $item)
        if (time () - $item->displayed >= 5)
        {
            while ($item->opacity > 0)
            {
                $item->opacity -= 0.1;

                usleep (50);
            }
            
            $item->close ();

            unset (Notification::$queue[$id]);
        }
});

class Notification extends Dialog
{
    static $queue = [];
    public $displayed = null;

    public function __construct (object $parent = null)
    {
        parent::__construct ($parent);

        $this->size = [400, 96];
        $this->startPosition = fspManual;
        $this->search ('head')->font = ['Segoe UI Semibold', 10];
        $this->search ('minimize')->visible = false;
        $this->remove ('minimize');

        $head = $this->search ('head')->selector;

        \VoidEngine\Events::removeObjectEvent ($head, 'mouseDown');
        \VoidEngine\Events::removeObjectEvent ($head, 'mouseMove');
        \VoidEngine\Events::removeObjectEvent ($head, 'mouseUp');

        $label = new Label ($this->base);
        $label->bounds = [16, 54, $this->clientSize[0] - 32, $this->clientSize[1] - 70];
        $label->anchor = acLeft | acTop | acRight | acBottom;
        $label->font = ['Segoe UI', 10];

        $this->bundle->set ('message', $label);
    }

    public function setCaption (string $caption): Notification
    {
        $this->search ('head')->caption = $caption;

        return $this;
    }

    public function setMessage (string $message): Notification
    {
        $size = (new WFClass ('System.Windows.Forms.TextRenderer'))
            ->measureText ($message, \VoidEngine\VoidEngine::getProperty ($this->search ('message')->selector, 'Font'));

        $this->h += $size->height * max (round ($size->width / $this->search ('message')->w) - 1, 0);
        
        $this->search ('message')->caption = $message;

        return $this;
    }

    public function execute (string $message = null, string $caption = null): Notification
    {
        if ($message !== null)
            $this
                ->setCaption ($message)
                ->setMessage ($caption);

        self::$queue[] = $this;

        $this->displayed = time ();
        $this->location = [$SCREEN->w / 2 - $this->clientSize[0] / 2, 16];
        $this->opacity = 0;
        $this->base->show ();

        while ($this->opacity < 1)
        {
            $this->opacity += 0.1;
            
            usleep (50);
        }

        return $this;
    }
}
