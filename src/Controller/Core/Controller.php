<?php

/*
 * Celsius3 - Core controller
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

namespace Celsius3\Controller\Core;

use Celsius3\Entity\BaseUser;
use Celsius3\Helper\ConfigurationHelper;
use Celsius3\Manager\InstanceManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Celsius3\Entity\Instance;
use Celsius3\Exception\Exception;
use Celsius3\Helper\InstanceHelper;
use Celsius3\Manager\FilterManager;
use Celsius3\Manager\UnionManager;
use Celsius3\Manager\UserManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Exception\SessionNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

abstract class Controller
{

    protected Instance $directory;
    protected ObjectManager $objectManager;
    protected EntityRepository $repository;
    protected Instance $instance;
    protected FlashBagInterface $flashBag;

    public function __construct(
        protected InstanceManager $instanceManager,
        protected EntityManagerInterface $entityManager,
        protected PaginatorInterface $paginator,
        protected ConfigurationHelper $configurationHelper,
        protected TranslatorInterface $translator,
        protected ManagerRegistry $managerRegistry,
        protected RequestStack $requestStack,
        protected UnionManager $unionManager,
        protected UserManager $userManager,
        protected FilterManager $filterManager,
        protected InstanceHelper $instanceHelper,
        protected FormFactoryInterface $formFactory,
        protected SessionInterface $session,
        protected RouterInterface $router,
        protected TokenStorageInterface $tokenStorage,
        protected Security $security,
        protected HtmlRenderer $htmlRenderer,
        protected RestRenderer $restRenderer
    ) {
        $this->initialize();
    }


    public function initialize(): void
    {
        $this->objectManager = $this->managerRegistry->getManager();
        $this->setInstance($this->instanceHelper->getSessionOrUrlInstance());
        $this->setDirectory($this->instanceManager->getDirectory());
        $this->flashBag = $this->session->getBag('flashes');
    }


    // protected function getInstance(): Instance
    // { return $this->instanceHelper->getSessionOrUrlInstance(); }


    protected function setInstance(Instance $instance): void
    { $this->instance = $instance; }


    // protected function getDirectory(): Instance|null
    // { return $this->instanceManager->getDirectory(); }


    protected function setDirectory(Instance $directory): void
    { $this->directory = $directory; }

    
    public function listQuery(): QueryBuilder
    { return $this->repository->createQueryBuilder('e'); }


    public function findQuery(string $id)
    { return $this->repository->find($id); }


    protected function getUser(): BaseUser|null
    {
        $token = $this->tokenStorage->getToken();
        if (null === $token) return null;
        $user = $token->getUser();
        return $user instanceof BaseUser
            ? $this->entityManager->getReference(
                BaseUser::class, $user->getId()
            )
            : null;
    }


    protected function createFormBuilder(
        $data = null, array $options = []
    ): FormBuilderInterface {
        return $this->formFactory
            ->createBuilder(FormType::class, $data, $options);
    }


    protected function createForm(
        string $type, $data = null, array $options = []
    ): FormInterface {
        return $this->formFactory
            ->create($type, $data, $options);
    }


    protected function addFlash(string $type, $message): void
    {
        try {
            $this->flashBag->add($type, $message);
        } catch (SessionNotFoundException $e) {
            throw new \LogicException(
                'You cannot use the addFlash method if sessions are disabled. Enable them in "config/packages/framework.yaml".', 0, $e
            );
        }
    }


    protected function redirect(string $url, int $status = 302): RedirectResponse
    { return new RedirectResponse($url, $status); }


    protected function redirectToRoute(
        string $route,
        array $parameters = [],
        int $status = 302
    ): RedirectResponse {
        return $this->redirect($this->generateUrl(
            $route, $parameters
        ), $status);
    }


    protected function generateUrl(
        string $route,
        array $parameters = [],
        int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH
    ): string {
        return $this->router->generate(
            $route,
            $parameters,
            $referenceType
        );
    }


    protected function getResultsPerPage(): mixed
    {
        return $this
            ->configurationHelper
            ->getCastedValue(
                $this->directory
                    ->get('results_per_page')
            );
    }


    public function error(
        string $type,
        string $entity = '',
        ?string $msg = null,
        bool $isRest = false
    ): never {
        $msg = (string) 'exception.' . $type . $entity;
        throw Exception::create($type, $msg, $isRest);
    }


    public function checkArg(
        array $args,
        string $fieldName,
        ?string $dataName = null,
        ?string $msg = null,
        ?string $type = 'not_found',
        ?bool $isRest = false
    ): string {
        $dataName ??= $fieldName;
        $msg ??= ucfirst($dataName) . ' must be set (at "' . $fieldName . '" field).';
        if (!isset($args[$fieldName]))
            $this->error($type, msg: $msg, isRest: $isRest);
        return $args[$fieldName];
    }


    protected function paginate(
        ?QueryBuilder $query = null,
        ?Request $request = null,
        ?int $page = null,
        ?int $limit = null,
        ?array $options = null
    ): PaginationInterface {
        if ($request === null)
            $request = $this->requestStack->getCurrentRequest();

        if ($query === null) $query = $this->listQuery();

        if ($page === null)
            $page = intval($request->query->get('page', 1));

        if ($limit === null) $limit = $this->getResultsPerPage();

        return $this->paginator->paginate(
            $query,
            $page,
            $limit,
            $options
        );
    }


    protected function createDeleteForm(string $id): FormInterface
    {
        return $this
            ->createFormBuilder([
                'id' => $id,
            ])
            ->add('id', HiddenType::class)
            ->getForm();
    }


    protected function createNotFoundException(
        string $message = 'Not Found',
        ?\Throwable $previous = null
    ): NotFoundHttpException {
        return new NotFoundHttpException(
            $message, $previous
        );
    }
}