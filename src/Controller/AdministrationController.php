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

namespace Celsius3\Controller;

use Celsius3\Entity\BaseUser;
use Celsius3\Entity\Configuration;
use Celsius3\Entity\DataRequest;
use Celsius3\Entity\Instance;
use Celsius3\Entity\Institution;
use Celsius3\Entity\MailTemplate;
use Celsius3\Entity\State;
use Celsius3\Form\Type\DataRequestType;
use Celsius3\Helper\ConfigurationHelper;
use Celsius3\Helper\InstanceHelper;
use Celsius3\Manager\Alert;
use Celsius3\Manager\UserManager;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use function count;
use function filesize;
use function in_array;
use function is_array;
use function json_encode;
use function mime_content_type;
use function readfile;

/**
 * Administration controller.
 *
 * @Route("/admin")
 */
class AdministrationController extends AbstractController //BaseInstanceDependentController
{
    private $configurationHelper;
    private $entityManager;
    private $instanceHelper;
    /**
     * @var UserManager
     */
    private $userManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        ConfigurationHelper $configurationHelper,
        InstanceHelper $instanceHelper,
        UserManager $userManager
    )
    {
        $this->configurationHelper = $configurationHelper;
        $this->entityManager = $entityManager;
        $this->instanceHelper = $instanceHelper;
        $this->userManager = $userManager;
    }
    
    protected function getInstance(): Instance
    {
        return $this->instanceHelper->getSessionInstance();
    }

    /**
     * @Route("/", name="administration", options={"expose"=true})
     */
    public function index()
    {
        $config_helper = $this->configurationHelper;
        $results_per_page_config = $this->entityManager
            ->getRepository(Configuration::class)
            ->findOneBy(
                [
                    'instance' => $this->getInstance(),
                    'key' => $config_helper::CONF__RESULTS_PER_PAGE,
                ]
            );

        return $this->render('Admin/Dashboard/index.html.twig', [
            'resultsPerPage' => $results_per_page_config->getValue(),
        ]);
    }

    /**
     * @Route("/ajax", name="admin_ajax")
     */
    public function ajax(Request $request)
    {
        return $this->parentAjax($request, $this->getInstance());
    }

    /**
     * @Route("/ajax_username", name="admin_ajax_usernames")
     */
    public function usernamesAjax(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            return $this->createNotFoundException();
        }

        $instance = $this->getInstance();
        $term = $request->query->get('term');

        $result = $this->entityManager
            ->getRepository(BaseUser::class)
            ->findByTerm(
                $term,
                $instance,
                null,
                $this->get('celsius3_core.user_manager')->getLibrarianInstitutions()
            )
            ->getResult();

        $json = [];
        foreach ($result as $element) {
            $json[] = [
                'id' => $element->getUsername(),
                'value' => $element->__toString(),
            ];
        }

        $response = new Response(json_encode($json));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * GET Route annotation.
     *
     * @Route("/send_reminder_emails", name="admin_send_reminder_emails", options={"expose"=true})
     */
    public function sendReminderEmails()
    {
        $entity_manager = $this->entityManager;
        $users_with_pending_requets = $entity_manager->getRepository(State::class)
            ->countUsersWithPendingRequests(
                $this->getInstance(),
                $this->getInstance()->get('min_days_for_send_mail')->getValue(),
                $this->getInstance()->get('max_days_for_send_mail')->getValue()
            );

        $templates = $entity_manager->getRepository(MailTemplate::class)->findAllEnabled();

        $errors = $this->get('session')->getFlashBag()->get('errors');

        $error = false;
        $error_message = '';
        if (isset($errors[0])) {
            $error = true;
            $error_message = $errors[0];
        }

        $users = [];
        foreach ($users_with_pending_requets as $x) {
            $users[$x['id']] = [
                'username' => $x['username'],
                'surname' => $x['surname'],
                'name' => $x['name'],
                'requestsCount' => $x['requestsCount'],
            ];
        }

        return $this->render('Admin/Dashboard/send_reminder_emails.html.twig', [
            'users' => $users,
            'templates' => $templates,
            'error' => $error,
            'errorMessage' => $error_message,
        ]);
    }

    /**
     * @Route("/send_reminder_emails_batch", name="admin_send_reminder_emails_batch", methods={"POST"})
     */
    public function sendReminderEmailsBatch(Request $request): RedirectResponse
    {
        $subject = $request->request->get('subject');
        $text = $request->request->get('text');

        $users_requests = $this->entityManager
            ->getRepository(State::class)
            ->getUsersWithPendingRequests(
                $this->getInstance(),
                $this->getInstance()->get('min_days_for_send_mail')->getValue(),
                $this->getInstance()->get('max_days_for_send_mail')->getValue()
            );

        $i = 0;
        $users = [];
        while ($i < count($users_requests)) {
            $users[$users_requests[$i]['id']] = [
                'username' => $users_requests[$i]['username'],
                'surname' => $users_requests[$i]['surname'],
                'name' => $users_requests[$i]['name'],
                'email' => $users_requests[$i]['email'],
            ];

            $actual = $users_requests[$i]['id'];
            while ($i < count($users_requests) && $actual === $users_requests[$i]['id']) {
                $users[$users_requests[$i]['id']]['requests'][] = $users_requests[$i]['request'];
                ++$i;
            }
        }

        $mailer = $this->get('celsius3_core.mailer');
        $twig = $this->get('twig');

        foreach ($users as $user) {
            try {
                $template = $twig->createTemplate($text);
                $body = $template->render(['user' => $user]);
                $mailer->sendEmail($user['email'], $subject, $body, $this->getInstance());
            } catch (Exception $e) {
                $this->get('session')->getFlashBag()->set('errors', 'Invalid Template');

                return $this->redirectToRoute('admin_send_reminder_emails');
            }
        }

        return $this->redirectToRoute('administration');
    }

    /**
     * @Route("/{id}/data_request_download", name="admin_instance_data_request_download", options={"expose"=true})
     * @param  DataRequest  $dataRequest
     * @return RedirectResponse|Response
     */
    public function dataRequestDownload(DataRequest $dataRequest)
    {
        $entity_manager = $this->get('doctrine.orm.entity_manager');

        $filename = $dataRequest->getFile();
        $directory = $this->getParameter('data_requests_directory');
        $filepath = $directory.$filename;

        if (!file_exists($filepath)) {
            Alert::add(Alert::ERROR, 'The requested file does not exists.');
            return $this->redirectToRoute('administration');
        }

        $entity_manager->persist($dataRequest->setDownloaded(true));
        $entity_manager->flush($dataRequest);

        $response = new Response();
        $response->headers->set('Content-type', mime_content_type($filepath));
        $response->headers->set('Content-Disposition', 'attachment;filename="'.$filename.'"');
        $response->headers->set('Content-length', filesize($filepath));
        $response->sendHeaders();
        $response->setContent(readfile($filepath));

        return $response;
    }

    /**
     * @Route("/data_request_get", name="admin_instance_data_requests_get", options={"expose"=true})
     */
    public function dataRequestGet(): Response
    {
        $em = $this->entityManager;
        $result = $em->getRepository(DataRequest::class)->findExportedRequests($this->getInstance());

        return new Response(json_encode($result));
    }

    /**
     * @Route("/interaction_get", name="admin_instance_interaction_get", options={"expose"=true}, methods={"POST"})
     */
    public function getInteractionWith(Request $request): JsonResponse
    {
        $id = $request->request->get('id');
        $initial_year = !empty($request->request->get('anio_desde')) ? $request->request->get('anio_desde') : 2001;
        $final_year = !empty($request->request->get('anio_hasta')) ? $request->request->get(
            'anio_hasta'
        ) : (int)(new DateTime())->format('Y');

        $institution_repository = $this->entityManager->getRepository(Institution::class);
        $institution = $institution_repository->find($id);
        $instance = $this->getInstance();

        $interaction['result'] = false;
        if (true) {
            if ($institution->getParent() !== null) {
                $institution = $institution_repository->getBaseInstitution($institution);
            }

            $interaction['result'] = true;
            $institutions = $this->entityManager->getRepository(
                Institution::class
            )->getInstitutionsTree($institution);

            $request_repository = $this->entityManager->getRepository(\Celsius3\Entity\Request::class);
            $response['institutionInteraction'] = $request_repository->getInteractionOfInstitutionWithInstance(
                $instance,
                $institutions,
                $initial_year,
                $final_year
            );
            $response['instanceInteraction'] = $request_repository->getInteractionOfInstanceWithInstitution(
                $instance,
                $institutions,
                $initial_year,
                $final_year
            );

            $interaction['institution'] = $institution->getName();
            $interaction['instance'] = $instance->getName();

            foreach ($response['institutionInteraction'] as $res) {
                $interaction['institutionInteraction']['data'][$res['year']][$res['st']] = $res['c'];
            }

            foreach ($response['instanceInteraction'] as $res) {
                $interaction['instanceInteraction']['data'][$res['year']][$res['st']] = $res['c'];
            }
        }

        return new JsonResponse($interaction, 200);
    }

    protected function validateAjax($target): bool
    {
        $allowed_targets = [
            'Journal',
            'BaseUser',
        ];

        return in_array($target, $allowed_targets, true);
    }

    protected function parentAjax(Request $request, Instance $instance = null, $librarian = null)
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException();
        }

        $target = $request->query->get('target');
        if (!$this->validateAjax($target)) {
            throw $this->createNotFoundException();
        }

        $term = $request->query->get('term');

        if ($this->isGranted('ROLE_ADMIN')) {
            $insts = [];
        } else {
            $insts = $this->userManager->getLibrarianInstitutions($librarian);
        }

        $result = $this->entityManager
            ->getRepository('Celsius3\\Entity\\'.$target)
            ->findByTerm($term, $instance, null, $insts)
            ->getResult();

        $json = [];


        foreach ($result as $element) {
            if (method_exists( $element,  'asJson' )){
                $json[] = $element -> asJSon();
            }
            else{
                $json[] = [
                    'id' => $element->getId(),
                    'value' => ($target === 'BaseUser') ? $element->__toString().' ('.$element->getUsername().')' : $element->__toString(),

                ];
            }
        }


        $response = new Response(json_encode($json));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
