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
        $testResult = $this->testConnection(
                    $instance->get('smtp_host')->getValue(),
                    $instance->get('smtp_port')->getValue(),
                    $instance->get('smtp_protocol')->getValue(),
                    $instance->get('smtp_username')->getValue(),
                    $instance->get('smtp_password')->getValue()
        );

        return $testResult['test'];
    }

    public function testConnection($host, $port, $protocol, $user, $pass)
    {
        try {
            $transport = \Swift_SmtpTransport::newInstance($host, $port, $protocol)
                ->setUsername($user)
                ->setPassword($pass);
            $mailer = new \Swift_Mailer($transport);
            $mailer->getTransport()->start();
        } catch (\Swift_TransportException $e) {
            return array(
                'test' => false,
                'message' => $e->getMessage(),
            );
        } catch (\Exception $e) {
            return array(
                'test' => false,
                'message' => $e->getMessage(),
            );
        }
        $translator = $this->container->get('translator');

        return array(
            'test' => true,
            'message' => $translator->trans('Sucefull connection'),
        );
    }
}
