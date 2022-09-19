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

namespace Celsius3\Command;

use Celsius3\Entity\Instance;
use Celsius3\Mailer\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendEmailsCommand extends Command
{
    private $mailer;
    private $entityManager;
    private $logger;

    public function __construct(EntityManagerInterface $entityManager, Mailer $mailer, LoggerInterface $logger)
    {
        parent::__construct();
        $this->mailer = $mailer;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    protected function configure()
    {
        $this->setName('celsius3_core:mailer:send_emails')
            ->setDescription('Send emails')
            ->addArgument('limit', InputArgument::REQUIRED, 'Limit for emails to be sent on each connection.')
            ->addArgument(
                'log-level',
                InputArgument::OPTIONAL,
                'Log level. 1) Max level, 2) Medium level, 3) Minimum level.',
                3
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $limit = (int)$input->getArgument('limit');
        $logLevel = (int)$input->getArgument('log-level');

        $limit = ($limit >= 1 && $limit <= 10) ? $limit : 5;
        $logLevel = ($logLevel === 1 || $logLevel === 2 || $logLevel === 3) ? $logLevel : null;

        $mailer = $this->mailer;
        $em = $this->entityManager;

        $logger = $this->logger;

        $instances = $em->getRepository(Instance::class)
            ->findAllAndInvisibleExceptDirectory()
            ->getQuery()
            ->execute();

        foreach ($instances as $instance) {
            try {
                $mailer->sendInstanceEmails($instance, $limit, $output, $logger, $logLevel);
            } catch (Exception $e) {
                $message = "Failed to send emails for instance $instance. " . $e->getMessage();

                $logger->error($message);
                echo $message;
            }
        }
    }
}
