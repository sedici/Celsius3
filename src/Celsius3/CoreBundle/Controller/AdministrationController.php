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

namespace Celsius3\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Celsius3\CoreBundle\Manager\StateManager;

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
        $configHelper = $this->get('celsius3_core.configuration_helper');
        $resultsPerPageConfig = $this->getDoctrine()
                ->getManager()
                ->getRepository('Celsius3CoreBundle:Configuration')
                ->findOneBy(
                array(
                    'instance' => $this->getInstance(),
                    'key' => $configHelper::CONF__RESULTS_PER_PAGE));

        return array(
            'resultsPerPage' => $resultsPerPageConfig->getValue()
        );
    }

    /**
     * @Route("/search", name="administration_search")
     * @Template()
     *
     * @return array
     */
    public function searchAction(Request $request)
    {
        $type = $request->query->get('type');
        $keyword = trim($request->query->get('keyword'));
        $state = $request->query->get('state');

        $paginator = $this->get('knp_paginator');
        $query = $this->get('celsius3_core.search_manager')->search('Order', $type, $keyword, $this->getInstance());

        $states = array();
        foreach (StateManager::$stateTypes as $s) {
            $states[$s] = 0;
        }

        $total = 0;
        $results = $query->getArrayResult();
        foreach ($results as $result) {
            foreach ($result['requests'] as $request) {
                foreach ($request['states'] as $s) {
                    if ($s['isCurrent']) {
                        $states[$s['type']] += 1;
                        $total += 1;
                    }
                }
            }
        }

        $pagination = $paginator->paginate(
    $this->get('celsius3_core.search_manager')->search('Order', $type, $keyword, $this->getInstance(), $state), $this->get('request')->query->get('page', 1)/* page number */, $this->container->getParameter('max_per_page')/* limit per page */);

        return array(
            'keyword' => $keyword,
            'type' => $type,
            'state' => $state,
            'states' => $states,
            'pagination' => $pagination,
            'total' => $total,
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
     * @Route("/ajax_username", name="admin_ajax_usernames")
     */
    public function usernamesAjaxAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            return $this->createNotFoundException();
        }

        $instance = $this->getInstance();
        $term = $request->query->get('term');

        $result = $this->getDoctrine()->getManager()
                ->getRepository('Celsius3CoreBundle:BaseUser')
                ->findByTerm($term, $instance, null, $this->get('celsius3_core.user_manager')->getLibrarianInstitutions(null))
                ->getResult();

        $json = array();
        foreach ($result as $element) {
            $json[] = array(
                'id' => $element->getUsername(),
                'value' => $element->__toString(),
            );
        }

        $response = new Response(json_encode($json));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
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

        $usersRequests = $this->getDoctrine()->getManager()
                ->getRepository('Celsius3CoreBundle:State')
                ->getUsersWithPendingRequests($this->getInstance(), $this->getInstance()->get('min_days_for_send_mail')->getValue(), $this->getInstance()->get('max_days_for_send_mail')->getValue());

        $i = 0;
        $users = array();
        while ($i < count($usersRequests)) {
            $users[$usersRequests[$i]['id']] = array(
                'username' => $usersRequests[$i]['username'],
                'surname' => $usersRequests[$i]['surname'],
                'name' => $usersRequests[$i]['name'],
                'email' => $usersRequests[$i]['email'],
            );

            $actual = $usersRequests[$i]['id'];
            while ($i < count($usersRequests) && $actual === $usersRequests[$i]['id']) {
                $users[$usersRequests[$i]['id']]['requests'][] = $usersRequests[$i]['request'];
                $i++;
            }
        }

        $mailer = $this->get('celsius3_core.mailer');
        $twig = $this->get('twig');

        foreach ($users as $user) {
            try {
                $template = $twig->createTemplate($text);
                $body = $template->render(array('user' => $user));
                $mailer->sendEmail($user['email'], $subject, $body, $this->getInstance());
            } catch (\Twig_Error_Runtime $e) {
                $this->get('session')->getFlashBag()->set('errors', 'Invalid Template');
                return $this->redirectToRoute('admin_send_reminder_emails');
            }
        }

        return $this->redirectToRoute('administration');
    }

}
