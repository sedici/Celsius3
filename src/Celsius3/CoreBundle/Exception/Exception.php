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

namespace Celsius3\CoreBundle\Exception;

class Exception {

    const NOT_FOUND = 'not_found';
    const PREVIOUS_STATE_NOT_FOUND = 'previous_state_not_found';
    const EXCEPTION_NOT_FOUND = 'exception_not_found';
    const ENTITY_NOT_FOUND = 'entity_not_found';
    const INSTANCE_NOT_FOUND = 'instance_not_found';
    const NOT_IMPLEMENTED = 'not_implemented';
    const RENDER_TEMPLATE = 'render_template';
    const INVALID_SEARCH = 'invalid_search';
    const ACCESS_DENIED = 'access_denied';

    private static $rest = false;
    private static $class_prefix = 'Celsius3\\CoreBundle\\Exception\\';
    private static $classes = [
        self::NOT_FOUND => 'NotFound'
        , self::PREVIOUS_STATE_NOT_FOUND => 'PreviousStateNotFound'
        , self::EXCEPTION_NOT_FOUND => 'ExceptionNotFound'
        , self::ENTITY_NOT_FOUND => 'EntityNotFound'
        , self::NOT_IMPLEMENTED => 'NotImplemented'
        , self::RENDER_TEMPLATE => 'RenderTemplate'
        , self::INVALID_SEARCH => 'InvalidSearch'
        , self::ACCESS_DENIED => 'AccessDenied'
    ];

    public static function isRest() {
        return self::$rest;
    }

    public static function setRest() {
        self::$rest = true;
    }

    private static function getClass($type) {
        if (!array_key_exists($type, self::$classes)) {
            throw self::create(self::EXCEPTION_NOT_FOUND, 'exception.not_found.exception');
        }

        $class = self::$class_prefix . self::$classes[$type];

        if (self::isRest()) {
            $class .= 'Rest';
        }

        $class .= 'Exception';

        return $class;
    }

    public static function create($type, $message = '') {
        $class = self::getClass($type);

        return new $class($message);
    }

}
