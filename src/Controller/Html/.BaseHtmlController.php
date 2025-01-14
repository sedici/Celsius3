<?php

/*
 * Celsius3 - Base HTML controller
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

namespace Celsius3\Controller\Html;

use Celsius3\Controller\Base\BaseController;
use Celsius3\Controller\Base\BaseInstanceDependentController;
use ReflectionClass;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Base HTML controller.
 *
 * @Route("/admin/city")
 */
class BaseHtmlController extends AbstractController
{
    protected BaseInstanceDependentController $logic;
    protected string|null $templatePrefix;


    public function __construct(
        BaseInstanceDependentController $logic
    ) {
        $this->logic = $logic;
        $this->templatePrefix = $this->getTemplatePrefix();
    }


    protected function getTemplatePrefix(): string
    {
        $str = (new ReflectionClass($this->logic))->getShortName();

	    $str = preg_replace(
            '/Controller$/', '', $str
        );
	
	    $str = preg_replace(
            '/([a-z])([A-Z])/', '$1/$2', $str
        ) . '/';

        return $str;
    }


    public function renderFlash(string $type, string $msg): void {
        $this->addFlash($type, $msg);
    }


    public function renderHtml(
        string $templatePostfix,
        array $parameters
    ) {
        return $this->render(
            (string) $this->templatePrefix . $templatePostfix . 'html.twig',
            $parameters
        );
    }
}