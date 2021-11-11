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

namespace Celsius3\CoreBundle\Mailer;

use Celsius3\CoreBundle\Entity\Email;
use Celsius3\CoreBundle\Entity\Instance;
use Celsius3\Helper\ConfigurationHelper;
use Celsius3\Helper\MailerHelper;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Mailer
{
    private $em;
    private $tokenStorage;
    private $validator;
    private $mailerHelper;

    public function __construct(EntityManager $em, TokenStorage $tokenStorage, ValidatorInterface $validator, MailerHelper $mailerHelper)
    {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
        $this->validator = $validator;
        $this->mailerHelper = $mailerHelper;
    }

    public function saveEmail($address, $subject, $text, Instance $instance)
    {
        $em = $this->em;

        $email = new Email();
        $email->setAddress($address);
        $email->setSubject($subject);
        $email->setText($text);
        $email->setSender($this->tokenStorage->getToken()->getUser());
        $email->setInstance($instance);
        $email->setSent(false);

        $em->persist($email);
        $em->flush($email);
    }

    public function sendEmail($address, $subject, $text, Instance $instance)
    {
        $errors = $this->validator->validate($address, [new EmailConstraint(), new NotBlank()]);

        if (count($errors) > 0) {
            return false;
        }

        if (!is_null($this->tokenStorage->getToken()) && $this->tokenStorage->getToken()->getUser() instanceof \Celsius3\CoreBundle\Entity\BaseUser) {
            $this->saveEmail($address, $subject, $text, $instance);

            return true;
        }

        return false;
    }

    public function sendInstanceEmails(Instance $instance, $limit, OutputInterface $output, Logger $logger, $logLevel)
    {
        if (!$this->mailerHelper->validateSmtpServerData($instance)) {
            if ($logLevel <= 2) {
                $output->writeln('Instance '.$instance->getUrl().': The SMTP server data are not valid.');
                $logger->error('Instance '.$instance->getUrl().': The SMTP server data are not valid.');
            }

            return;
        }

        $em = $this->em;

        $emails = $em->getRepository(Email::class)
                ->findNotSentEmailsWithLimit($instance, $limit);

        if (count($emails) === 0) {
            return;
        }

        $signature = $instance->get(ConfigurationHelper::CONF__MAIL_SIGNATURE)->getValue();

        try {
            $transport = \Swift_SmtpTransport::newInstance($instance->get(ConfigurationHelper::CONF__SMTP_HOST)->getValue(), $instance->get(ConfigurationHelper::CONF__SMTP_PORT)->getValue(), $instance->get(ConfigurationHelper::CONF__SMTP_PROTOCOL)->getValue());
            $transport->setUsername($instance->get(ConfigurationHelper::CONF__SMTP_USERNAME)->getValue())
                    ->setPassword($instance->get(ConfigurationHelper::CONF__SMTP_PASSWORD)->getValue());
            $mailer = \Swift_Mailer::newInstance($transport);
            $mailer->getTransport()->start();
        } catch (\Exception $e) {
            $output->writeln('Connection error from instance '.$instance->getUrl().'. '.$e->getMessage());
            $logger->error('Connection error from instance '.$instance->getUrl().'. '.$e->getMessage());

            return;
        }

        if ($logLevel <= 3) {
            $output->writeln('Sending mails from instance '.$instance->getUrl());
            $logger->info('Sending mails from instance '.$instance->getUrl());
        }
        if ($logLevel === 1) {
            $output->writeln('SMTP Host: '.$instance->get(ConfigurationHelper::CONF__SMTP_HOST)->getValue());
            $output->writeln('SMTP Port: '.$instance->get(ConfigurationHelper::CONF__SMTP_PORT)->getValue());
            $logger->info('SMTP Host: '.$instance->get(ConfigurationHelper::CONF__SMTP_HOST)->getValue());
            $logger->info('SMTP Port: '.$instance->get(ConfigurationHelper::CONF__SMTP_PORT)->getValue());
        }

        foreach ($emails as $email) {
            try {
                if ($email->getAttempts() < 10 || $email->getUpdatedAt()->diff(new \DateTime())->h > 2) {
                    $from = $instance->get(ConfigurationHelper::CONF__SMTP_USERNAME)->getValue();
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
                        ->setBody($email->getText() . "\n" . $signature, 'text/html')
                        ->addPart($email->getText() . "\n" . $signature, 'text/html');

                    if ($mailer->send($message)) {
                        $em->persist($email->setSent(true));
                        $em->flush($email);
                    }
                }
            } catch (\Exception $e) {
                $email->addAttempt();

                $diff = $email->getCreatedAt()->diff(new \DateTime());
                $hours = $diff->h + ($diff->days * 24);
                if($hours > 48) {
                    $email->setError(true);
                }

                $this->em->persist($email);
                $this->em->flush();

                $message = "Error al enviar el correo con ID: " . $email->getId();

                $logger->error($message);
                echo $message;
            }
        }
    }
}
