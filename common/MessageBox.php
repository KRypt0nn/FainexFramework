<?php

namespace Fainex;

use VoidEngine\{
    Label,
    WFClass
};

function messageBox (string $message, string $caption = ''): Form
{
    $dialog = new Form;
    $dialog->size = [400, 300];
    $dialog->search ('head')->caption = $caption;
    $dialog->search ('minimize')->visible = false;
    $dialog->remove ('minimize');

    $label = new Label ($dialog->base);
    $label->bounds = [16, 54, $dialog->clientSize[0] - 32, $dialog->clientSize[1] - 118 + 64]; // + 64
    $label->anchor = acLeft | acTop | acRight | acBottom;
    $label->font = ['Segoe UI', 10];
    $label->caption = \VoidEngine\text ($message);

    /*$button = new Button ($dialog);
    $button->bounds = [0, $dialog->clientSize[1] - 48, $dialog->clientSize[0], 48];
    $button->anchor = acLeft | acRight | acBottom;
    $button->caption = \VoidEngine\text ('Окей');
    $button->firstColor = clGreen;
    $button->secondColor = clGreen;
    $button->backColor = clGreen;
    $button->foreColor = clWhite;
    $button->textAlign = alMiddleCenter;
    $button->cursor = (new WFClass ('System.Windows.Forms.Cursors'))->hand;

    $button->clickEvent = function () use ($dialog)
    {
        $dialog->close ();
    };*/

    $size = (new WFClass ('System.Windows.Forms.TextRenderer'))
        ->measureText ($message, \VoidEngine\VoidEngine::getProperty ($label->selector, 'Font'));

    $width = max (min ((int) $SCREEN->width / 2, $size->width) + 64, 256);
    
    $dialog->w = $width;
    $dialog->h = max (max (round ($size->width / $width) - 1, 0) + 94, 128); // 94 118 150

    $dialog->bundle->set ('message', $label);
    // $dialog->bundle->set ('button', $button);

    return $dialog;
}

function pre (string $message, string $caption = ''): void
{
    $dialog = messageBox ($message, $caption);
    
    $dialog->showDialog ();
    $dialog->dispose ();
}
