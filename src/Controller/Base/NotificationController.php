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

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Celsius3\Entity\Notification;
use Celsius3\Form\Type\SubscriptionType;
use Celsius3\Entity\NotificationSettings;
use Celsius3\Manager\NotificationManager;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template; // NO BORRAR
use Celsius3\Exception\Exception;

use Celsius3\Helper\ConfigurationHelper;
use Celsius3\Manager\InstanceManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Celsius3\Helper\InstanceHelper;
use Celsius3\Manager\FilterManager;
use Celsius3\Manager\UnionManager;
use Celsius3\Manager\UserManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Contracts\Translation\TranslatorInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Notification controller.
 * @Route("/user/notification")
 */
class NotificationController extends BaseEntityController
{

    protected NotificationManager $notificationManager;
    protected EntityRepository $nsrepository;

    public function __construct(
        NotificationManager $notificationManager,
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

        $this->notificationManager = $notificationManager;
        $this->nsrepository = $this->entityManager
            ->getRepository(NotificationSettings::class);
    }

    final protected function getEntity(): string
    { return Notification::class; }

    final protected function getType(): string
    { return Notification::class; }

    final protected function getTemplatePrefix(): string
    { return 'Notification/'; }


    protected function getSortDefaults(): array
    {
        return [
            'defaultSortFieldName' => 'e.updatedAt',
            'defaultSortDirection' => 'asc',
        ];
    }


    protected function listQuery(): QueryBuilder
    {
        return $this->repository
            ->createQueryBuilder('e')
            ->join('e.receivers', 'r')
            ->where('r.id = :user_id')
            ->setParameter('user_id', $this->getUser()->getId());
    }


    /**
     * Lists all Notification documents.
     *
     * @Route("/", name="user_notification")
     * @Template()
     *
     * @return array
     */
    public function indexAction(): Response
    {
        return $this->baseIndex(
            SubscriptionType::class,
            hasFilterForm: false,
        );
    }


    /**
     * Lists all Notification documents.
     *
     * @Route("/subscriptions", name="user_notification_subscriptions")
     * @Template()
     *
     * @return array
     */
    public function subscriptionsAction(): array
    {
        $request = $this->requestStack->getCurrentRequest();

        $settings = $this->nsrepository->findBy(
            [
                'user' => $this->getUser(),
                'instance' => $this->instance,
            ]
        );

        $form = $this->createForm(
            SubscriptionType::class, null, [
                'user' => $this->getUser(),
            ], false
        );

        foreach ($settings as $value) {
            $data = [];
            if ($value->getSubscribedToInterfaceNotifications()) {
                $data[] = 'notification';
            }
            if ($value->getSubscribedToEmailNotifications()) {
                $data[] = 'email';
            }

            if (
                !(strpos($value->getType(), 'user') === false)
                || !(strpos($value->getType(), 'message') === false)
            ) {
                $form->get($value->getType())->setData($data);
            } else {
                if ($form
                    ->get('event_notification')
                    ->has($value->getType())
                ) {
                    $form
                        ->get('event_notification')
                        ->get($value->getType())
                        ->setData($data);
                }
            }
        }

        if ($request->getMethod() === 'POST') {
            $form->handleRequest($request);
            $data = $form->getData();
            if (
                $this->getUser()->hasRole('ROLE_ADMIN')
                || $this->getUser()->hasRole('ROLE_SUPERADMIN')
            ) {
                $this->setNotificationTypes(
                    'user_notification',
                    $data['user_notification']
                );
            }
            $this->setNotificationTypes(
                'message_notification',
                $data['message_notification']
            );

            foreach ($data['event_notification'] as $notification => $types) {
                $this->setNotificationTypes(
                    $notification, $types
                );
            }

            $this->objectManager->flush();
        }

        return [
            'form' => $form->createView(),
        ];
    }


    private function setNotificationTypes($notification, $types)
    {

        $notificationSettings = $this->nsrepository->findOneBy([
            'user' => $this->getUser(),
            'instance' => $this->instance,
            'type' => $notification,
        ]);

        if (!$notificationSettings) {
            $notificationSettings = new NotificationSettings();
            $notificationSettings
                ->setUser($this->getUser())
                ->setInstance($this->instance)
                ->setType($notification);
        }

        $notificationSettings->setSubscribedToEmailNotifications(
            in_array('email', $types)
        );
        $notificationSettings->setSubscribedToInterfaceNotifications(
            in_array('notification', $types)
        );

        $this->persistEntity($notificationSettings);
    }


    /**
     * Lists all Notification documents.
     *
     * @Route("/{id}/view", name="user_notification_view", options={"expose"=true})
     * @Template()
     *
     * @return array
     */
    public function viewAction($id): RedirectResponse
    {
        $notification = $this->findQuery($id);

        if (!$notification) $this->error(Exception::ENTITY_NOT_FOUND);

        $notification->setViewed(true);
        $this->persistEntity($notification);

        return $this->redirect(
            $this->notificationManager->generateUrl(
                $notification
            )
        );
    }
}
