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

namespace Celsius3\CoreBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Bundle\TwigBundle\TwigEngine;

class MaintenanceModeListener
{
    private $file;
    private $directory;
    private $template;
    private $templating;

    public function __construct($file, $directory, $template, TwigEngine $templating)
    {
        $this->file = $file;
        $this->directory = $directory;
        $this->template = $template;
        $this->templating = $templating;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $filename = $this->file;
        $modedir = $this->directory;
        $class = new \ReflectionClass($this);
        $basedir = dirname($class->getFileName()).'/../../../..';
        $file = $basedir.$modedir.$filename;

        if (file_exists($file)) {
            $template = $this->template;
            $response = $this->templating->renderResponse($template);
            $event->setResponse($response);
        }

        return;
    }
}
