<?php

/**
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * @package     Fainex Framework
 * @copyright   2018 - 2019 Podvirnyy Nikita (KRypt0n_)
 * @license     GNU GPLv3 <https://www.gnu.org/licenses/gpl-3.0.html>
 * @license     Enfesto Studio Group license <https://vk.com/topic-113350174_36400959>
 * @author      Podvirnyy Nikita (KRypt0n_)
 * 
 * Contacts:
 *
 * Email: <suimin.tu.mu.ga.mi@gmail.com>
 * VK:    vk.com/technomindlp
 *        vk.com/hphp_convertation
 * 
 */

namespace Fainex;

define ('Fainex\clLight',  \VoidEngine\getARGBColor ('#EEEEEE'));
define ('Fainex\clLight2', \VoidEngine\getARGBColor ('#E0E0E0'));
define ('Fainex\clDark',   \VoidEngine\getARGBColor ('#212121'));
define ('Fainex\clDark2',  \VoidEngine\getARGBColor ('#313131'));
define ('Fainex\clGrey',   \VoidEngine\getARGBColor ('#616161'));
define ('Fainex\clGreen',  \VoidEngine\getARGBColor ('#8ED926'));
define ('Fainex\clRed',    \VoidEngine\getARGBColor ('#800000'));

require 'foundation/Bundle.php';
require 'foundation/Items.php';
require 'foundation/Component.php';
require 'components/Form.php';
require 'components/ListForm.php';
require 'components/Dialog.php';
require 'components/Button.php';
require 'components/Ellipse.php';
require 'components/CheckBox.php';
require 'components/Grid.php';
require 'components/Notification.php';
require 'components/Hint.php';
require 'components/ListBox.php';
require 'components/ProgressBar.php';
require 'common/MessageBox.php';

class ObjectsManager
{
    static $objects = [];

    static function add (Component $object): void
    {
        self::$objects[$object->selector] = $object;
    }

    static function remove (int $selector): void
    {
        unset (self::$objects[$selector]);
    }
}

function f (string $name): ?Component
{
    if (isset (ObjectsManager::$objects[$name]))
        return ObjectsManager::$objects[$name];

    else
    {
        foreach (ObjectsManager::$objects as $object)
            if ($object->name == $name)
                return $object;

        return null;
    }
}
