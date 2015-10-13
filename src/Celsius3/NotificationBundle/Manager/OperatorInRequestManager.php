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

namespace Celsius3\NotificationBundle\Manager;

use Celsius3\CoreBundle\Entity\Request;
use Celsius3\CoreBundle\Entity\BaseUser;
use Celsius3\CoreBundle\Entity\Instance;
use Doctrine\ORM\EntityManager;
use Celsius3\NotificationBundle\Entity\OperatorInRequest;

class OperatorInRequestManager
{

    private $em;
    private $zmq_port;
    private $zmq_host;

    public function __construct(EntityManager $em, $zmq_host, $zmq_port)
    {
        $this->em = $em;
        $this->zmq_host = $zmq_host;
        $this->zmq_port = $zmq_port;
    }

    public function addOperatorInRequest(BaseUser $operator, Request $request, Instance $instance)
    {
        $or = $this->em->getRepository('Celsius3NotificationBundle:OperatorInRequest')
                ->findOneBy(array('operator' => $operator, 'request' => $request, 'instance' => $instance));

        if (!$or) {
            $or = new OperatorInRequest();
            $or->setOperator($operator);
            $or->setRequest($request);
            $or->setInstance($instance);
        }

        $or->setWorking(true);

        $this->em->persist($or);
        $this->em->flush($or);

        $context = new \ZMQContext();
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'operator pusher');
        $socket->connect('tcp://' . $this->zmq_host . ':' . $this->zmq_port);

        $data = array(
            'operator_id' => $or->getOperator()->getId(),
            'operator_fullname' => $or->getOperator()->__toString()
        );

        $socket->send(json_encode(array(
            'type' => 'operator_in_request',
            'data' => $data
        )));
    }

    public function removeOperatorInRequest(BaseUser $operator, Request $request, Instance $instance)
    {
        $or = $this->em->getRepository('Celsius3NotificationBundle:OperatorInRequest')
                ->findOneBy(array('operator' => $operator, 'request' => $request, 'instance' => $instance));

        if ($or) {
            $or->setWorking(true);
        }

        $this->em->persist($or);
        $this->em->flush($or);
    }

}
