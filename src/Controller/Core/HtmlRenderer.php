<?php

/*
 * Celsius3 - Html Crud controller contract
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

use ReflectionClass;
use Symfony\Component\HttpFoundation\Response;

class HtmlRenderer extends BaseRenderer
{
    protected string $templatePrefix;


    protected function templatePrefixFromObj($controllerObject): string
    {
        $str = (new ReflectionClass($controllerObject))->getShortName();

        $str = str_replace(
            ['Controller', 'Html', 'Rest'], '', $str
        );

        return preg_replace(
            '/([a-z])([A-Z])/', '$1/$2', $str
        ) . '/';
    }


    public function setTemplatePrefixFromObj($controllerObject): void
    { $this->templatePrefix = $this->templatePrefixFromObj($controllerObject); }


    public function setTemplatePrefix(string $templatePrefix): void
    { $this->templatePrefix = $templatePrefix; }


    public function render(... $args): Response
    {
        return new Response(
            $this->twig->render(

                $this->templatePrefix . $this->getArg(
                    $args, 'templateName' //, string
                ) .  '.html.twig',

                (array) $this->getArgOrNull(
                    $args, 'params' //, array
                )
            )
        );
    }
}