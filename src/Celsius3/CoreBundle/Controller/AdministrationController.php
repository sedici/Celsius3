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

use Celsius3\CoreBundle\Entity\DataRequest;
use Celsius3\CoreBundle\Form\Type\DataRequestType;
use Celsius3\CoreBundle\Manager\Alert;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Administration controller.
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
                    'key' => $configHelper::CONF__RESULTS_PER_PAGE,)
            );

        return array(
            'resultsPerPage' => $resultsPerPageConfig->getValue(),
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
     *
     * @Route("/send_reminder_emails", name="admin_send_reminder_emails", options={"expose"=true})
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
                'requestsCount' => $x['requestsCount'],);
        }

        return array(
            'users' => $users,
            'templates' => $templates,
            'error' => $error,
            'errorMessage' => $errorMessage,
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
                ++$i;
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

    /**
     * @Route("/data_request", name="admin_instance_data_request", options={"expose"=true})
     */
    public function dataRequestAction(Request $request)
    {
        $dataRequest = new DataRequest($this->getInstance());

        $data = null;
        if ($dr = $request->request->get('data_request')) {
            foreach ($request->request->get('data_request') as $k => $v) {
                if ($v === "1") {
                    $data[] = $k;
                } elseif (is_array($v) && !empty($v)) {
                    $data[] = [$k => $v];
                }
            }
        }

        if ($data) {
            $dataRequest->setData(serialize($data));
        }

        $form = $this->createForm(DataRequestType::class, $dataRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($dataRequest);
            $em->flush();

            Alert::add(Alert::SUCCESS, $this->get('translator')->trans('The data request was successfully registered', [], 'Flashes'));
            return $this->redirectToRoute('administration');
        }

        return $this->render('Celsius3CoreBundle:Administration:request_data.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/{id}/data_request_download", name="admin_instance_data_request_download", options={"expose"=true})
     */
    public function dataRequestDownloadAction(Request $request, DataRequest $dataRequest)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $filename = $dataRequest->getFile();
        $directory = $directory = $this->getParameter('data_requests_directory');
        $filepath = $directory . $filename;

        if (!file_exists($filepath)) {
            Alert::add(Alert::ERROR, 'The requested file does not exists.');
            return $this->redirectToRoute('administration');
        }

        $em->persist($dataRequest->setDownloaded(true));
        $em->flush($dataRequest);

        $response = new Response();
        $response->headers->set('Content-type', mime_content_type($filepath));
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $filename . '"');
        $response->headers->set('Content-length', filesize($filepath));
        $response->sendHeaders();
        $response->setContent(readfile($filepath));

        return $response;
    }


    /**
     * @Route("/data_request_get", name="admin_instance_data_requests_get", options={"expose"=true})
     */
    public function dataRequestGetAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $result = $em->getRepository(DataRequest::class)->findExportedRequests($this->getInstance());

        return new Response(json_encode($result));
    }

    /**
     * @Route("/interaction_get/{id}/{initialYear}/{finalYear}", name="admin_instance_interaction_get", options={"expose"=true})
     */
    public function getInteractionWithAction($id,$initialYear, $finalYear) {
        $institutionRepository = $this->getDoctrine()->getManager()->getRepository('Celsius3CoreBundle:Institution');
        $institution = $institutionRepository->find($id);
        $instance = $this->getInstance();

        $interaction['result'] = false;
        if (true) {
            if ($institution->getParent() !== null)
                $institution = $institutionRepository->getBaseInstitution($institution);

            $interaction['result'] = true;
            $institutions = $this->getDoctrine()->getManager()->getRepository('Celsius3CoreBundle:Institution')->getInstitutionsTree($institution);

            $requestRepository = $this->getDoctrine()->getManager()->getRepository('Celsius3CoreBundle:Request');
            $response['institutionInteraction'] = $requestRepository->getInteractionOfInstitutionWithInstance($instance, $institutions, $initialYear, $finalYear);
            $response['instanceInteraction'] = $requestRepository->getInteractionOfInstanceWithInstitution($instance, $institutions, $initialYear, $finalYear);

            $interaction['institution'] = $institution->getName();
            $interaction['instance'] = $instance->getName();

            foreach ($response['institutionInteraction'] as $res) {
                $interaction['institutionInteraction']['data'][$res['year']][$res['st']] = $res['c'];
            }

            foreach ($response['instanceInteraction'] as $res) {
                $interaction['instanceInteraction']['data'][$res['year']][$res['st']] = $res['c'];
            }
        }

        $response = new JsonResponse($interaction, 200);
        return $response;
    }
}
