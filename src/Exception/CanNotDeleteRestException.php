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

namespace Celsius3\Exception;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\PreconditionFailedHttpException;

class CanNotDeleteRestException extends PreconditionFailedHttpException implements Celsius3ExceptionInterface
{
    public function handleEvent(ExceptionEvent $event, LoggerInterface $logger)
    {
        $exception = $event->getThrowable();

        $response = new JsonResponse([
            'error' => true,
            'hasMessage' => true,
            'message' => $exception->getMessage(),
        ]);

        $response->setStatusCode(405); // Method Not Allowed

        $event->setResponse($response);

        $logger->error($exception);
    }
}
