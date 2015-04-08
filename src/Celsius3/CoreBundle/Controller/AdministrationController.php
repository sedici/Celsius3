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

namespace Celsius3\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

/**
 * Administration controller
 *
 * @Route("/admin")
 */
class AdministrationController extends BaseInstanceDependentController
{

    /**
     * @Route("/", name="administration", options={"expose"=true})
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/search", name="administration_search")
     * @Template()
     *
     * @return array
     */
    public function searchAction(Request $request)
    {
        $keyword = $request->query->get('keyword');

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($this->get('celsius3_core.search_manager')->search('Order', $keyword, $this->getInstance()), $this->get('request')->query->get('page', 1)/* page number */, $this->container->getParameter('max_per_page')/* limit per page */);

        return array(
            'keyword' => $keyword,
            'pagination' => $pagination,
        );
    }

    /**
     * @Route("/ajax", name="admin_ajax")
     */
    public function ajaxAction(Request $request)
    {
        return $this->ajax($request, $this->getInstance());
    }

    /**
     * @Route("/{id}/change", name="administration_change_context")
     */
    public function changeContextAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $instance = $em->getRepository('Celsius3CoreBundle:Instance')->find($id);

        $user = $this->getUser();
        if (!$user->getAdministeredInstances()->contains($user->getInstance())) {
            $user->getAdministeredInstances()->add($user->getInstance());
        }

        if (!$instance || !$user->getAdministeredInstances()->contains($instance)) {
            return $this->createNotFoundException('Instance not found');
        }

        $this->get('session')->set('instance_id', $instance->getId());
        $this->get('session')->set('instance_url', $instance->getUrl());

        return $this->redirect($this->generateUrl('administration'));
    }

    protected function validateAjax($target)
    {
        $allowedTargets = array(
            'Journal',
            'BaseUser',
        );

        return in_array($target, $allowedTargets);
    }

    /**
     * GET Route annotation.
     * @Route("/send_reminder_emails", name="admin_send_reminder_emails")
     * @Template("Celsius3CoreBundle:Administration:send_reminder_emails.html.twig")
     */
    public function sendReminderEmailsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $usersWithPendingRequets = $em->getRepository('Celsius3CoreBundle:State')
                ->countUsersWithPendingRequests($this->getInstance(), $this->getInstance()->get('min_days_for_send_mail')->getValue(), $this->getInstance()->get('max_days_for_send_mail')->getValue());

        $templates = $em->getRepository('Celsius3CoreBundle:MailTemplate')->findAllEnabled();

        $errors = $this->get('session')->getFlashBag()->get('errors', array());

        $error = false;
        $errorMessage = '';
        if (isset($errors[0])) {
            $error = true;
            $errorMessage = $errors[0];
        }

        $users = array();
        foreach ($usersWithPendingRequets as $x) {
            $users[$x['id']] = array(
                'username' => $x['username'],
                'surname' => $x['surname'],
                'name' => $x['name'],
                'requestsCount' => $x['requestsCount']);
        }

        return array(
            'users' => $users,
            'templates' => $templates,
            'error' => $error,
            'errorMessage' => $errorMessage
        );
    }

    /**
     * @Route("/send_reminder_emails_batch", name="admin_send_reminder_emails_batch")
     * @Method("post")
     */
    public function sendReminderEmailsBatchAction(Request $request)
    {
        $subject = $request->request->get('subject');
        $text = $request->request->get('text');
        $fromEmail = $this->getInstance()->get('email_reply_address')->getValue();

        $usersRequests = $this->getDoctrine()->getManager()
                ->getRepository('Celsius3CoreBundle:State')
                ->getUsersWithPendingRequests($this->getInstance(), $this->getInstance()->get('min_days_for_send_mail')->getValue(), $this->getInstance()->get('max_days_for_send_mail')->getValue());

        $i = 0;
        while ($i < count($usersRequests)) {
            $users[$usersRequests[$i]['id']] = array(
                'username' => $usersRequests[$i]['username'],
                'surname' => $usersRequests[$i]['surname'],
                'name' => $usersRequests[$i]['name'],
                'email' => $usersRequests[$i]['email']);

            $actual = $usersRequests[$i]['id'];
            while ($i < count($usersRequests) && $actual === $usersRequests[$i]['id']) {
                $users[$usersRequests[$i]['id']]['requests'][] = $usersRequests[$i]['request'];
                $i++;
            }
        }

        $mailer = $this->get('mailer');
        $message = $mailer->createMessage();

        $message = $message->setSubject($subject)
                ->setFrom($fromEmail);

        $twig = clone $this->get('twig');
        $twig->setLoader(new \Twig_Loader_String());

        foreach ($users as $user) {
            try {
                $body = $twig->render($text, array('user' => $user));
            } catch (\Twig_Error_Runtime $e) {
                $this->get('session')->getFlashBag()->set('errors', 'Invalid Template');
                return $this->redirectToRoute('admin_send_reminder_emails');
            }
            $message = $message->setTo($user['email'])
                    ->setBody($body, 'text/html');
            $mailer->send($message);
        }

        return $this->redirectToRoute('administration');
    }

}
