<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PREBI-SEDICI <info@prebi.unlp.edu.ar> http://prebi.unlp.edu.ar http://sedici.unlp.edu.ar
 *
 * This file is part of Celsius3.
 *
 * Celsius3 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Celsius3 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Celsius3.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Celsius3\CoreBundle\Manager;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;

class Alert
{
    const INFO = 'info';
    const SUCCESS = 'success';
    const WARNING = 'warning';
    const ERROR = 'danger';

    private static $alerts = [];
    private static $rest = false;

    public static function isRest()
    {
        return self::$rest;
    }

    public static function setRest()
    {
        self::$rest = true;
    }

    public static function add($type, $message)
    {
        self::$alerts[$type][] = $message;
    }

    public static function getAlerts(FlashBag $flashBag)
    {
        foreach (self::$alerts as $type => $messages) {
            foreach ($messages as $message) {
                $flashBag->add($type, $message);
            }
        }
    }
}
