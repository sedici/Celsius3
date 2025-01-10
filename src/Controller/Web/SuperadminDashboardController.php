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

use Celsius3\Entity\Order;
use Celsius3\Helper\ConfigurationHelper;
use Celsius3\Manager\StatisticManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Celsius3\Controller\Base\DashboardController;

use Celsius3\Manager\InstanceManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Celsius3\Helper\InstanceHelper;
use Celsius3\Manager\FilterManager;
use Celsius3\Manager\UnionManager;
use Celsius3\Manager\UserManager;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Contracts\Translation\TranslatorInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * SuperAdminDashboard controller.
 *
 * @Route("/superadmin")
 */
class SuperadminDashboardController extends DashboardController
{

    private StatisticManager $statsManager;


    public function __construct(
        StatisticManager $statsManager,
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
        InstanceHelper $instanceHelper
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
            $instanceHelper
        );

        $this->statsManager = $statsManager;
    }


    /**
     * Lists all items to manage.
     *
     * @Route("/", name="superadministration")
     */
    public function index(): Response
    {
        return $this->render(
            (string) $this->templatePrefix . 'index.html.twig'
        );
    }


    /**
     * Lists all Order entities.
     *
     * @Route("/orderusertable", name="superadmin_orderusertable")
     */
    public function orderUserTable(): NotFoundHttpException|Response
    {
        $request = $this->requestStack->getMainRequest();

        if (!$request->isXmlHttpRequest()) {
            return $this->createNotFoundException();
        }

        return new Response(
            json_encode(
                $this->statsManager->getOrderUserTableData()
            )
        );
    }


    /**
     * Lists all Order entities.
     *
     * @Route("/admins_message", name="superadmin_admins_message", methods={"POST", "GET"})
     */
    public function adminsMessage(): RedirectResponse
    {
        $request = $this->requestStack->getCurrentRequest();

        $subject = $request->get('subject');
        $content = $request->get('message');

        if (!$content || empty($content)) {
            throw new NotFoundHttpException();
        }

        $composer = $this->get('fos_message.composer');

        $user = $this->getUser();
        $admins = new ArrayCollection(
            $this->repository
                ->getRepository(Order::class)
                ->findAllAdmins()
        );

        $message = $composer->newThread()
            ->setSender($user)
            ->addRecipients($admins)
            ->setSubject($subject)
            ->setBody($content)
            ->getMessage();

        $sender = $this->get('fos_message.sender');

        $sender->send($message);

        $this->addFlash(
            'success',
            $this->translator->trans(
                'The message was sent',
                [],
                'Flashes'
            )
        );

        return $this->redirectToRoute('superadministration');
    }


    /**
     * Lists all Order entities.
     *
     * @Route("/ajax", name="superadmin_ajax")
     */
    public function customAjax(): Response
    {
        $request = $this->requestStack->getCurrentRequest();
        return $this->ajax($request);
    }


    protected function validateAjax($target): bool
    {
        $allowed_targets = [
            'Journal',
            'BaseUser',
        ];

        return in_array(
            $target,
            $allowed_targets,
            true
        );
    }
}
