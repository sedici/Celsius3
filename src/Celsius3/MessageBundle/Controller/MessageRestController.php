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

namespace Celsius3\MessageBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Get;

/**
 * User controller.
 *
 * @Route("/user/rest/message")
 */
class MessageRestController extends FOSRestController
{

    /**
     * GET Route annotation.
     * @Get("", name="rest_message", options={"expose"=true})
     */
    public function getMessagesAction(Request $request)
    {
        $messages = $this->getDoctrine()->getManager()
                ->getRepository('Celsius3MessageBundle:Thread')
                ->createQueryBuilder('t')
                ->join('t.metadata', 'm')
                ->join('m.participant', 'p')
                ->where('p.id = :id')
                ->setParameter('id', $this->getUser()->getId());

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($messages, $this->get('request')->query->get('page', 1)/* page number */, $this->get('request')->query->get('count', 10)/* limit per page */)->getItems();


        $view = $this->view(array_values($pagination), 200)
                ->setFormat('json');

        return $this->handleView($view);
    }
}
