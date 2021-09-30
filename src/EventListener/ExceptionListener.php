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

namespace Celsius3\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Celsius3\Exception\Exception;
use Celsius3\Exception\Celsius3ExceptionInterface;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\Routing\Router;

class ExceptionListener
{
    private $exceptionLogger;
    private $restExceptionLogger;
    private $router;

    public function __construct(Logger $exceptionLogger, Logger $restExceptionLogger, Router $router)
    {
        $this->exceptionLogger = $exceptionLogger;
        $this->restExceptionLogger = $restExceptionLogger;
        $this->router = $router;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if ($exception instanceof Celsius3ExceptionInterface) {
            if (Exception::isRest()) {
                $exception->handleEvent($event, $this->restExceptionLogger);
            } else {
                if(method_exists($exception, 'setRouter')) {
                    $exception->setRouter($this->router);
                }
                $exception->handleEvent($event, $this->exceptionLogger);
            }
        }
    }
}
