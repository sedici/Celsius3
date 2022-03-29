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

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Celsius3\CoreBundle\Manager\Alert;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bridge\Monolog\Logger;

/**
 * Throw when the functionnality is not implemented.
 *
 * @author Cedric LOMBARDOT
 */
class NotImplementedException extends \LogicException implements Celsius3ExceptionInterface
{
    public function handleEvent(GetResponseForExceptionEvent $event, Logger $logger)
    {
        $exception = $event->getException();

        Alert::add(Alert::WARNING, $exception->getMessage());

        $response = new RedirectResponse($event->getRequest()->headers->get('referer'));
        $event->setResponse($response);

        $logger->error($exception);
    }
}