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

class Mailer
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
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

        $em->persist($email);
        $em->flush($email);
    }

    public function sendEmail($address, $subject, $text, Instance $instance)
    {
        $signature = $instance->get(ConfigurationHelper::CONF__MAIL_SIGNATURE)->getValue();
        
        $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($instance->get('email_reply_address')->getValue())
                ->setTo($address)
                ->setBody($text . "\n" . $signature);
        $this->container->get('mailer')->send($message);

        $this->saveEmail($address, $subject, $text, $instance);

        return true;
    }
}
