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

namespace Celsius3\CoreBundle\Manager;

use Doctrine\ORM\EntityManager;
use Celsius3\CoreBundle\Entity\BaseUser;
use Celsius3\CoreBundle\Entity\Instance;
use Celsius3\CoreBundle\Entity\Order;
use Celsius3\CoreBundle\Manager\InstanceManager;
use Symfony\Component\Config\Definition\Exception\Exception;

class MailManager
{
    const MAIL__ORDER_PRINTED = 'order_printed';
    const MAIL__ORDER_DOWNLOAD = 'order_download';
    const MAIL__ORDER_CANCEL = 'order_cancel';
    const MAIL__ORDER_PRINTED_RECONFIRM = 'order_printed_reconfirm';
    const MAIL__USER_WELCOME = 'user_welcome';
    const MAIL__USER_WELCOME_PROVISION = 'user_welcome_provision';
    const MAIL__USER_LOST = 'user_lost';
    private $em;
    private $im;
    private $twig;

    public function __construct(EntityManager $em, InstanceManager $im)
    {
        $this->em = $em;
        $this->im = $im;
        $loader = new \Twig_Loader_String();
        $this->twig = new \Twig_Environment($loader);
    }

    public function renderTemplate($code, Instance $instance, BaseUser $user, Order $order = null)
    {
        $template = $this->em->getRepository('Celsius3CoreBundle:MailTemplate')
                ->findGlobalAndForInstance($instance, $this->im->getDirectory(), $code)
                ->getQuery()
                ->getSingleResult();

        return $this->twig->render($template->getText(), array(
                    'user' => $user,
                    'order' => $order
        ));
    }

    public function renderRawTemplate($text,$vars_array) {
        return $this->twig->render($text,$vars_array);
    }
}
