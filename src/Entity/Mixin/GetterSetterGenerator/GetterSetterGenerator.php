<?php

/*
 * Celsius3 - Getter and setter generator
 * Copyright (C) 2025 PREBI-SEDICI <info@prebi.unlp.edu.ar> http://prebi.unlp.edu.ar http://sedici.unlp.edu.ar
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


class CodeGenerator
{
    public static function generateGettersSetters(string $className): string
    {
        $reflection = new ReflectionClass($className);
        $properties = $reflection->getProperties();

        $code = "";
        foreach ($properties as $property) {
            // Obtener el atributo GenerateMethods
            $attributes = $property->getAttributes(GenerateMethods::class);
            if (empty($attributes)) {
                continue;
            }

            /** @var GenerateMethods $generateMethods */
            $generateMethods = $attributes[0]->newInstance();
            $methods = $generateMethods->getMethods();

            $name = ucfirst($property->getName());
            $type = self::getPropertyType($property);

            // Generar métodos
            if (in_array('getter', $methods)) {
                $code .= self::generateGetter($name, $type);
            }
            if (in_array('setter', $methods)) {
                $code .= self::generateSetter($name, $type);
            }
        }
        return $code;
    }

    private static function getPropertyType(ReflectionProperty $property): ?string
    {
        $type = $property->getType();
        return ($type instanceof ReflectionNamedType) ? $type->getName() : null;
    }

    private static function generateGetter(string $name, ?string $type): string
    {
        $returnType = $type ? ": $type" : "";
        return "
            public function get$name()$returnType
            {
                return \$this->$name;
            }
        ";
    }

    private static function generateSetter(string $name, ?string $type): string
    {
        $paramType = $type ? "$type " : "";
        return "
            public function set$name($paramType\$$name)
            {
                \$this->$name = \$$name;
                return \$this;
            }
        ";
    }
}