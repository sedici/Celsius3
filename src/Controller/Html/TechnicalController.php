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

namespace Celsius3\Controller\Html;

use Celsius3\Entity\Instance;
use Celsius3\Helper\MailerHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Celsius3\Controller\Core\EntityController;

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
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;
use Celsius3\Controller\Core\HtmlRenderer;
use Celsius3\Controller\Core\RestRenderer;


/**
 * BaseUser controller.
 * @Route("/tichnical")
 */
class TechnicalController extends EntityController
{

    public function __construct(
        protected MailerHelper $mailerHelper,
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
        FlashBagInterface $session,
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


    /**
     * Lists all BaseUser entities.
     * @Route("/", name="tichnical_index")
     */
    public function htmlIndex(): Response
    {
        $instances = $this->objectManager
            ->getRepository(Instance::class)
            ->findAllEnabledAndVisible();

        $cInstances = [];
        foreach ($instances as $instance) {
            if (!array_key_exists($instance->getOwnerInstitutions()->first()->getCountry()->getName(), $cInstances)) {
                $cInstances[$instance->getOwnerInstitutions()->first()->getCountry()->getName()] = array();
            }
            $cInstances[$instance->getOwnerInstitutions()->first()->getCountry()->getName()][] = $instance;
        }

        return $this->htmlRenderer->render(
            'index',
            [ 'instances' => $cInstances ]
        );
    }


    /**
     *  @Route("/test_smtp", name="technical_instance_rest_test_smtp", options={"expose"=true})
     */
    public function testConnection(): Response
    {
        $request = $this->requestStack->getCurrentRequest();

        $instance_id = $request->get('instance');

        $instance = $this->objectManager
            ->getRepository(Instance::class)
            ->createQueryBuilder('i')
            ->andWhere('i.id = :instance_id')
            ->setParameter('instance_id', $instance_id)
            ->getQuery()
            ->getOneOrNullResult();

        $mailerHelper = $this->mailerHelper;
        $info_connection = $mailerHelper->testConnection(
            $instance->get('smtp_host')->getValue(),
            $instance->get('smtp_port')->getValue(),
            $instance->get('smtp_protocol')->getValue(),
            $instance->get('smtp_username')->getValue(),
            $instance->get('smtp_password')->getValue()
        );

        return $this->htmlRenderer->render(
            '_testConnection',
            [ 'info_connection' => $info_connection ]
        );
    }
}
