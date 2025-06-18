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

namespace Celsius3\Controller\Base;

use Celsius3\Controller\Core\EntityController;
use Celsius3\Controller\Core\HtmlRenderer;
use Celsius3\Controller\Core\RestRenderer;
use Celsius3\Entity\City;
use Celsius3\Entity\Country;
use Celsius3\Entity\Institution;
use Celsius3\Entity\News;
use Celsius3\Entity\Order;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Celsius3\Exception\Exception;

use Celsius3\Helper\ConfigurationHelper;
use Celsius3\Helper\InstanceHelper;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Celsius3\Manager\FilterManager;
use Celsius3\Manager\UnionManager;
use Celsius3\Manager\UserManager;
use Symfony\Contracts\Translation\TranslatorInterface;
use Doctrine\Persistence\ManagerRegistry;
use FOS\RestBundle\Controller\Annotations\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;


#[
    Route('/public'),
    IsGranted('IS_AUTHENTICATED_FULLY')
]
class PublicController extends EntityController
{

    public function __construct(
        protected string $maxPerPage,
        ValidatorInterface $validator,
        InstanceHelper $InstanceHelper,
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
        parent::initialize();
        $this->setInstanceDependent(true);
        $this->repository = $this->entityManager
            ->getRepository(Order::class);
        $this->setSortDefaults([
            'defaultSortFieldName' => 'e.updatedAt',
            'defaultSortDirection' => 'asc',
        ]);
        $this->htmlRenderer->setTemplatePrefix('Public/');
    }


    #[Route('/', name: 'public_index')]
    public function htmlIndex(): Response
    {
        return $this->htmlRenderer->render(
            'index',
            [
                'instance' => $this->instance,
                'lastNews' => $this->instance
                    ? $this->entityManager
                        ->getRepository(News::class)
                        ->findLastNews($this->getInstance())
                    : [],
            ]
        );
    }


    #[Route('/information', name: 'public_information')]
    public function information(): Response
    {
        if ($this->instance && !boolval(
            $this->instance
                ->get('home_information_visible')->getValue()
            )
        ) {
            return $this->redirectToRoute('public_index');
        }

        return $this->htmlRenderer->render(
            'information',
            [
                'instance' => $this->instance,
            ]
        );
    }


    #[Route('/news', name: 'public_news')]
    public function news(): Response
    {
        if ($this->instance && !boolval(
            $this->instance->get('home_news_visible')->getValue()
            )
        ) {
            return $this->redirectToRoute('public_index');
        }

        $news = $this->entityManager
            ->getRepository(News::class)
            ->findByInstanceQB($this->instance);

        $pagination = $this->paginate($news, limit: $this->maxPerPage);

        return $this->htmlRenderer->render(
            'news',
            [
                'pagination' => $pagination,
            ]
        );
    }


    #[Route(path: '/statistics', name: 'public_statistics', options: ['expose' => true])]
    public function statistics(): Response
    {
        if ($this->instance && !boolval(
            $this->instance->get('home_statistics_visible')->getValue()
            )
        ) {
            return $this->redirectToRoute('public_index');
        }

        return $this->htmlRenderer->render(
            'statistics',
            []
        );
    }


    #[Route(path: '/countries', name: 'public_countries', options: ['expose' => true])]
    public function countries(): Response
    {
        $countries = $this->objectManager
            ->getRepository(Country::class)
            ->getAllOrderedByNameQB()
            ->getQuery()->execute();

        $response = [];
        foreach ($countries as $country) {
            $response[] = [
                'value' => $country->getId(),
                'name' => ucfirst(
                    strtolower($country->getName())
                )
            ];
        }

        return $this->restRenderer->render($response);
    }


    #[Route(path: '/cities', name: 'public_cities', options: ['expose' => true])]
    public function cities(): Response
    {
        $request = $this->requestStack->getCurrentRequest()->toArray();

        if (!isset($request['country_id']))
            $this->error(Exception::ENTITY_NOT_FOUND, City::class);

        $cities = $this->objectManager
            ->getRepository(City::class)
            ->findForCountry($request['country_id']);

        $response = [];
        foreach ($cities as $city) {
            $response[] = [
                'value' => $city->getId(),
                'name' => $city->getName()
            ];
        }

        return $this->restRenderer->render($response);
    }


    #[Route(path: '/institutions', name: 'public_institutions', options: ['expose' => true])]
    public function institutions(): Response
    {
        $request = $this->requestStack->getCurrentRequest()->toArray();

        if (!isset($request['country_id'])) 
            $this->error(Exception::ENTITY_NOT_FOUND, Country::class);

        $institutions = $this->objectManager
            ->getRepository(Institution::class)
            ->findByCountry(
                $request['country_id'],
                $this->getInstance(), $this->directory
            );

        $response = [];
        foreach ($institutions as $institution) {
            $response[] = [
                'value' => $institution->getId(),
                'name' => $institution->getName(),
            ];
        }

        return $this->restRenderer->render($response);
    }


    #[Route(path: '/institutionsFull', name: 'public_institutions_full', options: ['expose' => true])]
    public function institutionsFull(): Response
    {
        $request = $this->requestStack->getCurrentRequest()->toArray();

        if (
            !isset($request['country_id'])
            && !isset($request['city_id'])
            && !isset($request['institution_id'])
        ) {
            $this->error(Exception::ENTITY_NOT_FOUND, Country::class);
        }

        $institutions = $this->entityManager
            ->getRepository(Institution::class)
            ->findForCountryOrCity(
                $request['country_id'],
                $request['city_id'],
                $this->directory,
                $this->instanceHelper->getSessionOrUrlInstance()
            );

        $actual = array_filter(
            $institutions,
            function ($i): bool {
                return $i['parent_id'] === null;
            }
        );

        $institutions = array_diff_key(
            $institutions, $actual
        );

        $response = [];
        foreach ($actual as $institution) {
            $level = 0;
            if (
                (
                    $request['filter'] === 'liblink'
                    && $institution['hive_id'] === $this->instance->getHive()->getId()
                ) || (
                    $request['filter'] === 'celsius3'
                    && $institution['celsiusInstance']
                ) || ($request['filter'] === '')
            ) {
                $children = array_filter(
                    $institutions,
                    function ($i) use ($institution): bool {
                        return $i['parent_id'] === $institution['id'];
                    }
                );

                $instAbbr = ' | '.(($institution['abbreviation']) ?: $institution['name']);

                $response[] = [
                    'value' => $institution['id'],
                    'hasChildren' => count($children) > 0,
                    'name' => $institution['name'].(
                        ($institution['abbreviation'])
                            ? ' ('.$institution['abbreviation'].')'
                            : ''
                    ),
                    'level' => $level,
                    'children' => $this->getChildrenInstitution(
                        $institutions,
                        $children,
                        $level + 1,
                        $instAbbr
                    )
                ];
            }
        }

        return $this->restRenderer->render($response);
    }


    protected function getChildrenInstitution(
        array &$all, array $institutions, $level, $parent
    ): array {
        $response = [];
        $all = array_diff_key($all, $institutions);
        if (count($institutions) > 0) {
            foreach ($institutions as $institution) {
                $children = array_filter(
                    $all,
                    function ($i) use ($institution): bool {
                        return $i['parent_id'] === $institution['id'];
                    }
                );

                $response[] = [
                    'value' => $institution['id'],
                    'hasChildren' => count($children) > 0,
                    'name' => $institution['name'].(
                        ($institution['abbreviation'])
                            ? ' ('.$institution['abbreviation'].')'
                            : ''
                    ).$parent,
                    'level' => $level,
                    'children' => $this->getChildrenInstitution(
                        $all,
                        $children,
                        $level + 1,
                        $parent
                    )
                ];
            }
        }

        return $response;
    }


    #[Route(path: '/help', name: 'public_help')]
    public function help(): RedirectResponse|Response
    {
        if ($this->instance && !boolval(
            $this->instance->get('home_help_visible')->getValue()
            )
        ) {
            return $this->redirectToRoute('public_index');
        }

        return $this->htmlRenderer->render(
            'help',
            [
                'staff' => $this->instance->get('instance_staff')->getValue()
            ]
        );
    }
}
