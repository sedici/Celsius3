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

declare(strict_types=1);

namespace Celsius3\CoreBundle\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Event\ConsoleErrorEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;

use function get_class;

class ConsoleLoggingListener
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onConsoleException(ConsoleErrorEvent $event): void
    {
        $command = $event->getCommand();
        $error = $event->getError();

        $message = sprintf(
            '%s: %s (uncaught exception) at %s line %s while running console command `%s`',
            get_class($error),
            $error->getMessage(),
            $error->getFile(),
            $error->getLine(),
            $command->getName()
        );

        $this->logger->error($message, ['exception' => $error]);
    }

    public function onConsoleTerminate(ConsoleTerminateEvent $event): void
    {
        $status_code = $event->getExitCode();
        $command = $event->getCommand();

        if ($status_code === 0) {
            return;
        }

        if ($status_code > 255) {
            $status_code = 255;
            $event->setExitCode($status_code);
        }

        $this->logger->warning(
            sprintf(
                'Command `%s` exited with status code %d',
                $command->getName(),
                $status_code
            )
        );
    }
}
