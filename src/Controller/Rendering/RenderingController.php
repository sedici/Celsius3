<?php

/*
 * Celsius3 - Rendering controller
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

namespace Celsius3\Controller\Rendering;

use Celsius3\Controller\Base\BaseController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

abstract class RenderingController extends AbstractController
{

    protected BaseController $controllerInstance;


    public function __construct()
    { $this->initialize(); }
    
    
    protected function initialize(): void {}


    protected function setController(BaseController $baseController): void
    { $this->controllerInstance = $baseController; }


    protected function printVar($entity): void {
        $str = '{ ';
        foreach ($entity as $property => $value) {
            $str .= "$property => $value, ";
        }
        $str .= ' }';
        throw new \Exception($str);
    }


    public function setControllerInstance(BaseController $controllerInstance): void
    { $this->controllerInstance = $controllerInstance; }


    abstract public function renderResponse(array $parameters, ... $args): Response;

    abstract public function renderFlash(string $type, string $msg): void;

}