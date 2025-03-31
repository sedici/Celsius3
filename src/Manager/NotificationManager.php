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

namespace Celsius3\Manager;

use Celsius3\Controller\Base\EmailController;
use Celsius3\Mailer\Mailer;
use Celsius3\Entity\NotificationTemplate;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Celsius3\Entity\BaseUserNotification;
use Celsius3\Entity\MessageNotification;
use Celsius3\Entity\EventNotification;
use Celsius3\Entity\Notification;
use Celsius3\Entity\BaseUser;
use Celsius3\Entity\Event\Event;
use Celsius3\Entity\Instance;
use Celsius3\Entity\Message;
use JMS\TranslationBundle\Annotation\Ignore;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

class NotificationManager
{
    public const CAUSE__NEW_MESSAGE = 'new_message';
    public const CAUSE__NEW_USER = 'new_user';
    public const CAUSE__CREATE = 'creation';
    public const CAUSE__SEARCH = 'search';
    public const CAUSE__ANNUL = 'annul';
    public const CAUSE__REQUEST = 'request';
    public const CAUSE__RECEIVE = 'receive';
    public const CAUSE__CANCEL = 'cancel';
    public const CAUSE__DELIVER = 'deliver';
    public const CAUSE__UPLOAD = 'upload';
    public const CAUSE__RECLAIM = 'reclaim';

    public function __construct(
        protected EntityManagerInterface $entityManager,
        // protected Mailer $mailer,
        protected TranslatorInterface $translator,
        protected Environment $twig,
        protected RouterInterface $router,
        protected $notificationLimit,
        protected $zmqHost,
        protected $zmqPort,
        protected EmailController $emailController
    ) { }


    private function getMap(): array
    {
        $eventArray = $this->getEventArray();
        return [
            self::CAUSE__NEW_MESSAGE => [
                'template_data' =>
                    fn (Notification $notification): array => [
                        'user' => $notification->getObject()->getSender(),
                    ],
                'route' => 'fos_message_thread_view',
                'route_params' =>
                    fn (Notification $notification): array => [
                        'threadId' => $notification->getObject()
                            ->getThread()
                            ->getId(),
                    ],
            ],
            self::CAUSE__NEW_USER => [
                'template_data' =>
                    fn (Notification $notification): array => [
                        'user' => $notification->getObject(),
                    ],
                'route' => 'admin_user',
                'route_params' =>
                    fn (Notification $notification): array => [
                        'id' => $notification->getObject()->getId(),
                    ],
            ],
            self::CAUSE__CREATE => $eventArray,
            self::CAUSE__SEARCH => $eventArray,
            self::CAUSE__ANNUL => $eventArray,
            self::CAUSE__REQUEST => $eventArray,
            self::CAUSE__RECEIVE => $eventArray,
            self::CAUSE__CANCEL => $eventArray,
            self::CAUSE__DELIVER => $eventArray,
            self::CAUSE__UPLOAD => $eventArray,
            self::CAUSE__RECLAIM => $eventArray,
        ];
    }

    private function getEventArray(): array
    {
        return [
            'template_data' => function (Notification $notification): array {
                return [
                    'request' => $notification->getObject()->getRequest(),
                    'event' => $this->translator->trans($notification->getCause()),
                ];
            },
            'route' => 'admin_order_show',
            'route_params' => function (Notification $notification) {
                return [
                    'id' => $notification->getObject()->getRequest()->getOrder()->getId(),
                ];
            },
        ];
    }

    public function getRenderedTemplate(Notification $notification)
    {
        $data = $this->getMap()[$notification->getCause()]['template_data'];

        $template = $this->twig->createTemplate($notification->getTemplate()->getText());

        return $template->render($data($notification));
    }

    public function generateUrl(Notification $notification)
    {
        $route = $this->getMap()[$notification->getCause()]['route'];
        $params = $this->getMap()[$notification->getCause()]['route_params'];

        return $this->router->generate($route, $params($notification));
    }


    private function notifyInterface(
        Notification $notification, array $receivers
    ): void {
        $em = $this->entityManager;

        foreach ($receivers as $receiver) {
            $notification->addReceiver($receiver);
        }

        $em->persist($notification);
        $em->flush();
    }


    private function notifyEmail(
        Notification $notification,
        array $receivers,
        Instance $instance,
        string $otherText = ''
    ): void {
        $function = $this->getMap()[$notification->getCause()]['template_data'];
        $data = $function($notification);

        $template = $this->twig->createTemplate($notification->getTemplate()->getText());

        foreach ($receivers as $user) {
            $text = 'Celsius3 - '
                . $user->getInstance()
                . "\n\n"
                . $template->render($data).' '
                . $otherText;

            if (!$user->getWrongEmail()) {
                $this->emailController->sendEmail(
                    $user->getEmail(),
                    'Celsius 3 ' . $this->translator->trans(
                        $notification->getCause()
                    ), $text, instance: $instance
                );
            }
        }
    }


    public function notifyNewMessage(Message $message): void
    {
        $receivers = new ArrayCollection(
            $message->getThread()->getParticipants()->toArray()
        );
        $senderId = $message->getSender()->getId();
        $instance = $message->getSender()->getInstance();

        $this->notify(
            $receivers,
            self::CAUSE__NEW_MESSAGE,
            $message,
            MessageNotification::class,
            $instance,
            function (BaseUser $receiver) use ($senderId): bool {
                return $receiver->getId() !== $senderId;
            }
        );
    }


    private function notify(
        ArrayCollection $receivers,
        string $cause,
        object $object,
        string $notificationClass,
        Instance $instance,
        ?callable $filterCondition = null
    ): void {
        $em = $this->entityManager;

        $usersInterfaceNotification = $em->getRepository(BaseUser::class)->getUsersWithNotification('interface', $receivers, $cause);
        $usersEmailNotification = $em->getRepository(BaseUser::class)->getUsersWithNotification('email', $receivers, $cause);

        $receiversInterfaceNotification = $receivers->filter(function (BaseUser $receiver) use ($usersInterfaceNotification, $filterCondition): bool {
            $isInArray = in_array($receiver, $usersInterfaceNotification, true);
            return ($filterCondition === null || $filterCondition($receiver)) && $isInArray;
        });

        $receiversEmailNotification = $receivers->filter(function (BaseUser $receiver) use ($usersEmailNotification, $filterCondition): bool {
            $isInArray = in_array($receiver, $usersEmailNotification, true);
            return ($filterCondition === null || $filterCondition($receiver)) && $isInArray;
        });

        $template = $em->getRepository(NotificationTemplate::class)->findOneBy(['code' => $cause]);

        $notification = new $notificationClass($cause, $object, $template);

        $this->notifyInterface($notification, $receiversInterfaceNotification->toArray());
        $this->notifyEmail($notification, $receiversEmailNotification->toArray(), $instance);
    }

    public function notifyNewUser(BaseUser $user)
    {
        $em = $this->entityManager;

        $adminsInterfaceNotification = $em->getRepository(BaseUser::class)->getAdminsWithUserNotification('interface', $user->getInstance());
        $adminsEmailNotification = $em->getRepository(BaseUser::class)->getAdminsWithUserNotification('email', $user->getInstance());

        $template = $em->getRepository(NotificationTemplate::class)
                                        ->findOneBy(array('code' => self::CAUSE__NEW_USER));

        $notification = new BaseUserNotification(self::CAUSE__NEW_USER, $user, $template);

        $this->notifyInterface($notification, $adminsInterfaceNotification);
        $this->notifyEmail($notification, $adminsEmailNotification, $user->getInstance());
    }

    public function notifyEvent(Event $event, $type)
    {
        $em = $this->entityManager;
        $router = $this->router;

        $usersInsterfaceNotification = $em->getRepository(BaseUser::class)
                                        ->getUsersWithEventNotification('interface', $event, $type);
        $usersEmailNotification = $em->getRepository(BaseUser::class)
                                        ->getUsersWithEventNotification('email', $event, $type);

        $template = $em->getRepository(NotificationTemplate::class)
                                        ->findOneBy(array('code' => 'order_event'));

        $notification = new EventNotification($type, $event, $template);

        foreach ($usersInsterfaceNotification as $user) {
            $this->notifyInterface($notification, array($user));
        }

        $otherText = "\n\n" . $this->translator->trans("Para acceder al pedido ingrese al siguiente enlace") . ".\n";
        $otherText .= $router->generate('admin_order_show', array('id' => $notification->getObject()->getRequest()->getOrder()->getId()), UrlGeneratorInterface::ABSOLUTE_URL);

        $this->notifyEmail($notification, $usersEmailNotification, $event->getInstance(), $otherText);
    }

    public function notifyRemoteEvent(Event $event, $type)
    {
        $em = $this->entityManager;
        $template = $em->getRepository(NotificationTemplate::class)
            ->findOneBy([ 'code' => 'order_event' ]);

        $notification = new EventNotification($type, $event, $template);

        $user = $event->getRemoteNotificationTarget();

        if (!is_null($user)) {
            $this->notifyInterface($notification, [ $user ]);
        }
    }

    public function getUnreadNotificationsCount($user_id)
    {
        $em = $this->entityManager;

        return $em->getRepository(Notification::class)->getUnreadNotificationsCount($user_id);
    }

    public function getUnreadNotifications($user_id)
    {
        $em = $this->entityManager;

        return $em->getRepository(Notification::class)
            ->getUnreadNotifications($user_id, $this->notificationLimit);
    }
}
