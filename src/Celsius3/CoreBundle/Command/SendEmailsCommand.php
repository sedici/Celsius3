<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PrEBi <info@prebi.unlp.edu.ar>
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

namespace Celsius3\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendEmailsCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this->setName('celsius3_core:mailer:send_emails')
                ->setDescription('Send emails');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mailer = $this->getContainer()->get('celsius3_core.mailer');
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $instances = $em->getRepository('Celsius3CoreBundle:Instance')
                ->findAllExceptDirectory()
                ->getQuery()
                ->execute();

        foreach ($instances as $instance) {
            $output->writeln('Sending mails from instance ' . $instance->getUrl());
            $mailer->sendInstanceEmails($instance);
        }
    }

}
