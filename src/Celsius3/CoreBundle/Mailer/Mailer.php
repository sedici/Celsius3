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

namespace Celsius3\CoreBundle\Mailer;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Celsius3\CoreBundle\Entity\Instance;
use Celsius3\CoreBundle\Entity\Email;
use Celsius3\CoreBundle\Helper\ConfigurationHelper;
use Celsius3\CoreBundle\Helper\MailerHelper;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\Console\Output\OutputInterface;

class Mailer
{

    private $container;
    private $mailerHelper;

    public function __construct(ContainerInterface $container, MailerHelper $mailerHelper)
    {
        $this->container = $container;
        $this->mailerHelper = $mailerHelper;
    }

    public function saveEmail($address, $subject, $text, Instance $instance)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');

        $email = new Email();
        $email->setAddress($address);
        $email->setSubject($subject);
        $email->setText($text);
        $email->setSender($this->container->get('security.token_storage')->getToken()->getUser());
        $email->setInstance($instance);
        $email->setSent(false);

        $em->persist($email);
        $em->flush($email);
    }

    public function sendEmail($address, $subject, $text, Instance $instance)
    {
        if ($this->container->get('security.token_storage')->getToken()->getUser() instanceof \Celsius3\CoreBundle\Entity\BaseUser) {
            $this->saveEmail($address, $subject, $text, $instance);
            return true;
        }
        return false;
    }

    public function sendInstanceEmails(Instance $instance, $limit, OutputInterface $output, Logger $logger, $logLevel)
    {
        if (!$this->mailerHelper->validateSmtpServerData($instance)) {
            if ($logLevel <= 2) {
                $output->writeln('Instance ' . $instance->getUrl() . ': The SMTP server data are not valid.');
                $logger->error('Instance ' . $instance->getUrl() . ': The SMTP server data are not valid.');
            }
            return;
        }

        $em = $this->container->get('doctrine.orm.entity_manager');

        $emails = $em->getRepository('Celsius3CoreBundle:Email')
                ->findNotSentEmailsWithLimit($instance, $limit);

        if (count($emails) === 0) {
            return;
        }

        $signature = $instance->get(ConfigurationHelper::CONF__MAIL_SIGNATURE)->getValue();

        try {
            $transport = \Swift_SmtpTransport::newInstance($instance->get(ConfigurationHelper::CONF__SMTP_HOST)->getValue(), $instance->get(ConfigurationHelper::CONF__SMTP_PORT)->getValue())
                    ->setUsername($instance->get(ConfigurationHelper::CONF__SMTP_USERNAME)->getValue())
                    ->setPassword($instance->get(ConfigurationHelper::CONF__SMTP_PASSWORD)->getValue())
            ;
            $mailer = \Swift_Mailer::newInstance($transport);
        } catch (\Exception $e) {
            $output->writeln('Connection error from instance ' . $instance->getUrl() . '. ' . $e->getMessage());
            $logger->error('Connection error from instance ' . $instance->getUrl() . '. ' . $e->getMessage());
        }

        if ($logLevel <= 3) {
            $output->writeln('Sending mails from instance ' . $instance->getUrl());
            $logger->info('Sending mails from instance ' . $instance->getUrl());
        }
        if ($logLevel === 1) {
            $output->writeln('SMTP Host: ' . $instance->get(ConfigurationHelper::CONF__SMTP_HOST)->getValue());
            $output->writeln('SMTP Port: ' . $instance->get(ConfigurationHelper::CONF__SMTP_PORT)->getValue());
            $logger->info('SMTP Host: ' . $instance->get(ConfigurationHelper::CONF__SMTP_HOST)->getValue());
            $logger->info('SMTP Port: ' . $instance->get(ConfigurationHelper::CONF__SMTP_PORT)->getValue());
        }

        foreach ($emails as $email) {
            $from = $instance->get(ConfigurationHelper::CONF__EMAIL_REPLY_ADDRESS)->getValue();
            if ($logLevel <= 2) {
                $output->writeln('Sending mail from ' . $from . ' to ' . $email->getAddress());
                $logger->info('Sending mail from ' . $from . ' to ' . $email->getAddress());
            }
            if ($logLevel === 1) {
                $output->writeln('Subject: ' . $email->getSubject());
                $logger->info('Instance ' . $instance->getUrl() . ': The SMTP server data are not valid.');
            }

            $message = \Swift_Message::newInstance()
                    ->setSubject($email->getSubject())
                    ->setFrom($from)
                    ->setTo($email->getAddress())
                    ->setBody($email->getText() . "\n" . $signature)
                    ->addPart($email->getText() . "\n" . $signature, 'text/html')
            ;

            if ($mailer->send($message)) {
                $em->persist($email->setSent(true));
                $em->flush();
            }
        }
    }

}
