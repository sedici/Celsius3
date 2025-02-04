<?php

/*
 * Celsius3 - Variadic argument trait
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

namespace Celsius3\Controller\Core;

Trait VariadicArgsTrait
{
    public function getArg(
        array $args,
        string $argName,
        ?string $className = null
    ): mixed {
        if (isset($args[$argName])) {
            if ($className === null) return $args[$argName];
            else {
                if ($args[$argName] instanceof $className) {
                    return $args[$argName];
                } else {
                    throw new \InvalidArgumentException(
                        "Argument $argName not instance of $className"
                    );
                }
            }
        }

        throw new \InvalidArgumentException(
            "Argument $argName not found"
        );
    }


    public function getArgOrNull(
        array $args,
        string $argName,
        ?string $className = null
    ): mixed {
        if (isset($args[$argName])) {
            if ($className === null) return $args[$argName];
            else {
                if ($args[$argName] instanceof $className) {
                    return $args[$argName];
                } else {
                    throw new \InvalidArgumentException(
                        "Argument $argName not instance of $className"
                    );
                }
            }
        }

        return null;
    }


    public function getArgOrDefault(
        array $args,
        mixed $default,
        string $argName,
        ?string $className = null,
    ): mixed {
        if (isset($args[$argName])) {
            if ($className === null) return $args[$argName];
            else {
                if ($args[$argName] instanceof $className) {
                    return $args[$argName];
                } else {
                    throw new \InvalidArgumentException(
                        "Argument $argName not instance of $className"
                    );
                }
            }
        }

        return $default;
    }
}