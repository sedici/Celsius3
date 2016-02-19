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

namespace Celsius3\CoreBundle\Helper;

use Celsius3\CoreBundle\Entity\Instance;

class MailerHelper
{

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function validateSmtpServerData(Instance $instance)
    {
        return (!empty($instance->get('smtp_host')->getValue()) &&
                !empty($instance->get('smtp_port')->getValue()) &&
                !empty($instance->get('smtp_username')->getValue()) &&
                !empty($instance->get('smtp_password')->getValue()));
    }

    public function testConnection($host, $port, $user, $pass, $email)
    {
        try {
            $transport = \Swift_SmtpTransport::newInstance($host, $port)
                    ->setUsername($user)
                    ->setPassword($pass)
            ;
            $mailer = \Swift_Mailer::newInstance($transport);
            $mailer->getTransport()->start();

            $translator = $this->container->get('translator');

            $message = \Swift_Message::newInstance()
                    ->setSubject($translator->trans('Test email'))
                    ->setSender($email)
                    ->setFrom($email)
                    ->setTo($email)
                    ->setBody($translator->trans('It is an test email'))
            ;

            $mailer->send($message);
        } catch (\Swift_TransportException $e) {
            return array(
                'test' => false,
                'message' => $e->getMessage()
            );
        } catch (\Exception $e) {
            return array(
                'test' => false,
                'message' => $e->getMessage()
            );
        }

        return array(
            'test' => true,
            'message' => $translator->trans('Sucefull connection')
        );
    }

}
