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

namespace Celsius3\Controller\Html;

use Celsius3\Entity\BaseUser;
use Celsius3\Entity\Configuration;
use Celsius3\Entity\DataRequest;
use Celsius3\Entity\File;
use Celsius3\Entity\Instance;
use Celsius3\Entity\Institution;
use Celsius3\Entity\EmailTemplate;
use Celsius3\Entity\State;
use Celsius3\Mailer\Mailer;
use Celsius3\Manager\Alert;
use DateTime;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Celsius3\Controller\Core\EntityController;
use Twig\Environment;

use Celsius3\Controller\Core\HtmlRenderer;
use Celsius3\Controller\Core\RestRenderer;
use Celsius3\Helper\ConfigurationHelper;
use Celsius3\Manager\InstanceManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Celsius3\Helper\InstanceHelper;
use Celsius3\Manager\FilterManager;
use Celsius3\Manager\UnionManager;
use Celsius3\Manager\UserManager;
use Symfony\Contracts\Translation\TranslatorInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use function count;
use function in_array;
use function json_encode;


/**
 * Administration controller.
 * @Route("/admin")
 */
class AdministrationController extends EntityController
{

    protected $fileRepository;
    protected array $allowedTargets;


    public function __construct(
        protected Environment $twig,
        protected Mailer $mailer,
        ValidatorInterface $validator,
        InstanceManager $instanceManager,
        EntityManagerInterface $entityManager,
        PaginatorInterface $paginator,
        ConfigurationHelper $configurationHelper,
        TranslatorInterface $translator,
        ManagerRegistry $managerRegistry,
        RequestStack $requestStack,
        UnionManager $unionManager,
        UserManager $userManager,
        FilterManager $filterManager,
        InstanceHelper $instanceHelper,
        FormFactoryInterface $formFactory,
        SessionInterface $session,
        RouterInterface $router,
        TokenStorageInterface $tokenStorage,
        Security $security,
        HtmlRenderer $htmlRenderer,
        RestRenderer $restRenderer
    ) {
        parent::__construct(
            $validator,
            $instanceManager,
            $entityManager,
            $paginator,
            $configurationHelper,
            $translator,
            $managerRegistry,
            $requestStack,
            $unionManager,
            $userManager,
            $filterManager,
            $instanceHelper,
            $formFactory,
            $session,
            $router,
            $tokenStorage,
            $security,
            $htmlRenderer,
            $restRenderer
        );
    }

    public function initialize(): void
    {
        $this->setEntity(Configuration::class);

        parent::initialize();

        $this->htmlRenderer->setTemplatePrefix('Admin/Dashboard/');
        $this->setSortDefaults([
            'defaultSortFieldName' => 'e.updatedAt',
            'defaultSortDirection' => 'asc',
        ]);

        $this->fileRepository = $this->entityManager
            ->getRepository(File::class);

        $this->allowedTargets = [ 'Journal', 'BaseUser' ];
    }


    /**
     * @Route("/", name="administration", options={"expose"=true})
     */
    public function htmlIndex(): Response
    {
        $config_helper = $this->configurationHelper;
        $results_per_page_config = $this->repository
            ->findOneBy(
                [
                    'instance' => $this->instance,
                    'key' => $config_helper::CONF__RESULTS_PER_PAGE,
                ]
            );

        return $this->htmlRenderer->render(
            'index',
            [
                'resultsPerPage' => $results_per_page_config->getValue(),
            ]
        );
    }


    /**
     * @Route("/ajax", name="admin_ajax")
     */
    public function ajax(
        Request $request = null,
        Instance $instance = null
    ): Response {
        return $this->restRenderer->ajax(
            $this->requestStack->getCurrentRequest(),
            $this->allowedTargets,
            $this->instance
        );
    }


    /**
     * @Route("/ajax_username", name="admin_ajax_usernames")
     */
    public function usernamesAjax(Request $request): NotFoundHttpException|Response
    {
        $this->requestStack->getCurrentRequest();
        return $this->restRenderer->ajax(
            $request, $this->allowedTargets, $this->instance
        );
    }


    /**
     * GET Route annotation.
     * @Route("/send_reminder_emails", name="admin_send_reminder_emails", options={"expose"=true})
     */
    public function sendReminderEmails()
    {
        $entity_manager = $this->entityManager;
        $users_with_pending_requets = $entity_manager->getRepository(State::class)
            ->countUsersWithPendingRequests(
                $this->instance,
                $this->instance->get('min_days_for_send_mail')->getValue(),
                $this->instance->get('max_days_for_send_mail')->getValue()
            );

        $templates = $entity_manager->getRepository(EmailTemplate::class)->findAllEnabled();

        $errors = $this->flashBag->get('errors');

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

        return $this->htmlRenderer->render(
            'send_reminder_emails',
            [
                'users' => $users,
                'templates' => $templates,
                'error' => $error,
                'errorMessage' => $error_message,
            ]
        );
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
                $this->instance,
                $this->instance->get('min_days_for_send_mail')->getValue(),
                $this->instance->get('max_days_for_send_mail')->getValue()
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

        foreach ($users as $user) {
            try {
                $template = $this->twig->createTemplate($text);
                $body = $template->render(['user' => $user]);
                $this->mailer->sendEmail(
                    $user['email'], $subject, $body, $this->instance
                );
            } catch (Exception $e) {
                $this->addFlash('error', 'Invalid Template');

                return $this->redirectToRoute('admin_send_reminder_emails');
            }
        }

        return $this->redirectToRoute('administration');
    }


    /**
     * @Route("/{id}/data_request_download", name="admin_instance_data_request_download", options={"expose"=true})
     */
    public function dataRequestDownload(DataRequest $dataRequest)
    {
        $filename = $dataRequest->getFile();
        $directory = $this->getParameter('data_requests_directory');
        $filepath = (string) $directory.$filename;
        $file = $this->fileRepository->find($filepath);

        if (!file_exists($filepath)) {
            Alert::add(Alert::ERROR, 'The requested file does not exists.');
            return $this->redirectToRoute('administration');
        }

        $this->persistEntity(
            $dataRequest->setDownloaded(true)
        );

        $response = new BinaryFileResponse($filename);
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $file->getName()
        );

        return $response;
    }


    /**
     * @Route("/data_request_get", name="admin_instance_data_requests_get", options={"expose"=true})
     */
    public function dataRequestGet(): Response
    {
        $em = $this->entityManager;
        $result = $em->getRepository(DataRequest::class)->findExportedRequests($this->instance);
        return $this->restRenderer->render($result);
        // return new Response(json_encode($result));
    }


    /**
     * @Route("/interaction_get", name="admin_instance_interaction_get", options={"expose"=true}, methods={"POST"})
     */
    public function getInteractionWith(): JsonResponse
    {
        $request = $this->requestStack->getCurrentRequest();
        $id = $request->request->get('id');
        $initial_year = !empty($request->request->get('anio_desde')) ? $request->request->get('anio_desde') : 2001;
        $final_year = !empty($request->request->get('anio_hasta')) ? $request->request->get(
            'anio_hasta'
        ) : (int)(new DateTime())->format('Y');

        $institution_repository = $this->entityManager->getRepository(Institution::class);
        $institution = $institution_repository->find($id);

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
                $this->instance,
                $institutions,
                $initial_year,
                $final_year
            );
            $response['instanceInteraction'] = $request_repository->getInteractionOfInstanceWithInstitution(
                $this->instance,
                $institutions,
                $initial_year,
                $final_year
            );

            $interaction['institution'] = $institution->getName();
            $interaction['instance'] = $this->instance->getName();

            foreach ($response['institutionInteraction'] as $res) {
                $interaction['institutionInteraction']['data'][$res['year']][$res['st']] = $res['c'];
            }

            foreach ($response['instanceInteraction'] as $res) {
                $interaction['instanceInteraction']['data'][$res['year']][$res['st']] = $res['c'];
            }
        }

        return $this->restRenderer->render($interaction);
    }

    // protected function validateAjax($target): bool
    // {
    //     $allowed_targets = [
    //         'Journal',
    //         'BaseUser',
    //     ];

    //     return in_array($target, $allowed_targets, true);
    // }

    protected function parentAjax(Request $request, Instance $instance = null, $librarian = null)
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException();
        }

        $target = $request->query->get('target');
        if (!in_array($target, $this->allowedTargets, true))
            throw $this->createNotFoundException();

        $term = $request->query->get('term');

        if ($this->security->isGranted('ROLE_ADMIN')) {
            $insts = [];
        } else {
            $insts = $this->userManager->getLibrarianInstitutions($librarian);
        }

        $result = $this->entityManager
            ->getRepository('Celsius3\\Entity\\'.$target)
            ->findByTerm($term, $instance, null)
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
