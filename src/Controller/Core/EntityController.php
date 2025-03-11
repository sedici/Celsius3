<?php

/*
 * Celsius3 - Entity controller
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
use Celsius3\Exception\Exception;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\Pagination\PaginationInterface;
use ReflectionClass;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;

class EntityController extends InstanceDependentController
{

    use CRUDTrait;

    protected string $entityClassName;
    protected string $typeClassName;
    protected array $sortDefaults;
    protected ReflectionClass $entityClass;
    protected string $filterClassName;
    protected string $entityClassShortName;
    protected string $redirectRoute;


    public function __construct(
        protected ValidatorInterface $validator,
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
        $this->redirectRoute = $this->getRedirectRoute();
        $this->htmlRenderer->setController($this);
        $this->restRenderer->setController($this);

        parent::initialize();
    }


    protected function setType(string $typeClassName): void
    { $this->typeClassName = $typeClassName; }
    final protected function getEntityClass(): ReflectionClass
    { return new ReflectionClass($this->entityClassName); }
    protected function getType(): string
    { return (string) 'Celsius3\Form\Type\\' . $this->entityClassShortName . 'Type'; }
    protected function getFilterType(): string
    { return (string) 'Celsius3\Form\Type\Filter\\' . $this->entityClassShortName . 'FilterType'; }


    protected function getRepository(): EntityRepository
    {
        return $this->entityManager
            ->getRepository($this->entityClassName);
    }


    protected function persistEntity($entity): void
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }


    final public function getEntityClassName(): string
    { return $this->entityClassName; }


    final public function setEntity(string $entityClassName): void
    {
        $this->entityClassName = $entityClassName;
        $this->entityClass = $this->getEntityClass();
        $this->entityClassShortName = $this->entityClass->getShortName();
        $this->typeClassName = $this->getType();
        $this->repository = $this->getRepository();
        $this->filterClassName = $this->getFilterType();
        $this->objectManager = $this->entityManager;
    }


    public function setSortDefaults(array $sortDefaults): void
    { $this->sortDefaults = $sortDefaults; }


    public function error(
        string $type,
        ?string $entity = null,
        ?string $msg = null,
        ?bool $isRest = false
    ): never {
        if ($msg === null) {
            if ($entity === null)
                $entity = $this->entityClass->getShortName();
            $msg = (string) 'exception.' . $type . '.' . $entity;
        }

        // throw new \Exception($msg);

        throw Exception::create($type, $msg, $isRest);
    }


    public function flashEntityMessage(
        string $id, ?array $entities = null
    ): string {
        return $this->translator->trans(
            $id,
            ($entities === null)
                ? [ '%entity%' => $this->translator->trans(
                    $this->entityClass->getShortName()
                ) ]
                : [ '%entities%' => $this->translator->trans(
                    $this->entityClass->getShortName(),
                    [ '%count%' => count($entities) ],
                    'Flashes'
                ) ],
            'Flashes'
        );
    }


    public function createForm(
        ?string $type = null,
        $data = null,
        array $options = [],
        ?bool $isInstanceDependent = null,
        bool $hasData = true,
    ): FormInterface {
        if ($type === null) $type = $this->typeClassName;

        if ($data === null && $hasData) {
            $entityClassName = $this->entityClassName;
            $data = new $entityClassName();
        }

        return parent::createForm(
            $type,
            $data,
            $options,
            $isInstanceDependent,
        );
    }


    protected function paginate(
        ?QueryBuilder $query = null,
        ?Request $request = null,
        ?int $page = null,
        ?int $limit = null,
        ?array $options = null
    ): PaginationInterface {
        if ($options === null) $options = $this->sortDefaults;

        return parent::paginate(
            $query,
            $request,
            $page,
            $limit,
            $options
        );
    }


    protected function baseBatch(): mixed
    {
        $request = $this->requestStack->getCurrentRequest();
        $action = $request->get('action');
        $function = 'batch' . ucfirst($action);
        $element_ids = $request->get('element', []);

        return $this->$function($element_ids);
    }

    protected function baseUnion(array $ids): array
    {
        $entities = $this->repository
            ->findBy(['id' => $ids]);

        return [ 'entities' => $entities ];
    }


    protected function mergeSecondaryInstances(
        BaseUser $main, array $entities
    ): void { }


    protected function baseDoUnion(
        $ids, $main_id, $updateInstance = true
    ): void {
        $main = $this->findQuery($main_id);

        if (!$main) $this->error(Exception::ENTITY_NOT_FOUND);

        $entities = $this->repository
            ->findBaseDoUnionEntities($main, $ids);

        if (count($entities) !== count($ids) - 1)
            $this->error(Exception::ENTITY_NOT_FOUND);

        if ($this->entityClassName === BaseUser::class) {
            $this->mergeSecondaryInstances(
                $main, $entities
            );
        }

        $this->unionManager
            ->union(
                $this->entityClassName, $main,
                $entities, $updateInstance
            );
    }


    protected function addEntityFlash(
        string $type, string $id, ?array $entities = null
    ): void {
        $this->addFlash(
            $type, $this->flashEntityMessage($id, $entities)
        );
    }


    private function getRedirectRoute(): string 
    {
        // Remove Controller suffix
        $name = preg_replace(
            '/Controller$/',
            '',
            (new ReflectionClass($this))->getShortName()
        );
        
        // Convert camelCase to snake_case
        $name = preg_replace('/([a-z])([A-Z])/', '$1_$2', $name);
        $name = strtolower($name);
        
        return $name;
    }
}