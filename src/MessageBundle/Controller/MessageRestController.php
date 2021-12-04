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

namespace Celsius3\MessageBundle\Controller;

use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * User controller.
 *
 * @Route("/user/rest/message")
 */
class MessageRestController extends FOSRestController
{
    /**
     * GET Route annotation.
     *
     * @Get("", name="rest_message", options={"expose"=true})
     */
    public function getMessagesAction(Request $request)
    {
        $messages = $this->getDoctrine()->getManager()
                ->getRepository('Celsius3MessageBundle:Thread')
                ->findUserMessages($this->getUser());

        $view = $this->view(array_values($messages), 200)->setFormat('json');

        $context = new Context();
        $context->addGroup('user_list');
        $view->setContext($context);

        return $this->handleView($view);
    }

    /**
     * POST Route annotation.
     *
     * @Post("/send-message", name="admin_rest_message", options={"expose"=true})
     */
    public function sendMessageAction(Request $request)
    {
        $form = $this->container->get('fos_message.new_thread_form.factory')->create();
        $formHandler = $this->container->get('fos_message.new_thread_form.handler');

        $form->handleRequest($request);

        $message = $formHandler->process($form);

        $result['result'] = true;
        if ($message) {
            return new RedirectResponse($this->container->get('router')->generate('fos_message_thread_view', array(
                'threadId' => $message->getThread()->getId(),
            )));
        } else {
            $result['result'] = false;
        }

        $result['message'] = $message;
        $view = $this->view($result, 200)->setFormat('json');

        return $this->handleView($view);
    }
}
