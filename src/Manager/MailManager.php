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

namespace Celsius3\Manager;

use Celsius3\CoreBundle\Entity\BaseUser;
use Celsius3\CoreBundle\Entity\Instance;
use Celsius3\CoreBundle\Entity\Order;
use Celsius3\Exception\Exception;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use Twig\Environment;
use Twig\Error\Error;

class MailManager
{
    public const MAIL__ORDER_PRINTED = 'order_printed';
    public const MAIL__ORDER_DOWNLOAD = 'order_download';
    public const MAIL__ORDER_CANCEL = 'order_cancel';
    public const MAIL__ORDER_PRINTED_RECONFIRM = 'order_printed_reconfirm';
    public const MAIL__USER_WELCOME = 'user_welcome';
    public const MAIL__USER_WELCOME_PROVISION = 'user_welcome_provision';
    public const MAIL__USER_LOST = 'user_lost';
    public const MAIL__NO_HIVE = 'no_hive';
    public const MAIL__RESETTING = 'resetting';
    public const MAIL__USER_CONFIRMATION = 'user_confirmation';
    public const MAIL__CUSTOM = 'custom';

    private $entityManager;
    private $instanceManager;
    private $twig;
    private $serializer;

    public function __construct(
        EntityManagerInterface $entityManager,
        InstanceManager $instanceManager,
        Environment $twig,
        Serializer $serializer
    ) {
        $this->entityManager = $entityManager;
        $this->instanceManager = $instanceManager;
        $this->twig = $twig;
        $this->serializer = $serializer;
    }

    public function renderTemplate($code, Instance $instance, BaseUser $user, Order $order = null): ?string
    {
        try {
            $template = $this->twig->createTemplate($this->getTemplate($code, $instance)->getText());

            $vars = compact('instance', 'user', 'order');

            return $template->render($this->serializeData($vars));
        } catch (Error $error) {
            throw Exception::create(Exception::RENDER_TEMPLATE, 'exception.template.mail_template');
        }
    }

    public function getTemplate($code, Instance $instance)
    {
        $template = $this->entityManager->getRepository('Celsius3CoreBundle:MailTemplate')
            ->findGlobalAndForInstance($instance, $this->instanceManager->getDirectory(), $code)
            ->getQuery()->getResult()[0];

        if (!$template) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.mail_template');
        }

        return $template;
    }

    private function serializeData($vars): array
    {
        $data = [];
        foreach ($vars as $key => $value) {
            if ($value !== null) {
                $context = SerializationContext::create()->setGroups(['email_template']);
                $data[$key] = $this->serializer->toArray($value, $context);
            }
        }

        return $data;
    }

    public function renderRawTemplate($text, $vars): ?string
    {
        try {
            $template = $this->twig->createTemplate($text);

            return $template->render($this->serializeData($vars));
        } catch (Error $error) {
            throw Exception::create(Exception::RENDER_TEMPLATE, 'exception.template.mail_template');
        }
    }
}
