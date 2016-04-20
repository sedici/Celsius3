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

namespace Celsius3\NotificationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Celsius3\CoreBundle\Controller\BaseController;
use Celsius3\NotificationBundle\Form\Type\SubscriptionType;
use Celsius3\NotificationBundle\Entity\NotificationSettings;
use Celsius3\CoreBundle\Exception\Exception;

/**
 * Notification controller.
 *
 * @Route("/user/notification")
 */
class NotificationController extends BaseController
{

    protected function getBundle()
    {
        return 'Celsius3NotificationBundle';
    }

    protected function listQuery($name)
    {
        return parent::listQuery($name)
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
    public function indexAction()
    {
        return $this->baseIndex('Notification');
    }

    /**
     * Lists all Notification documents.
     *
     * @Route("/subscriptions", name="user_notification_subscriptions")
     * @Template()
     *
     * @return array
     */
    public function subscriptionsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Celsius3NotificationBundle:NotificationSettings');

        $settings = $repository->findBy(
                array(
                    'user' => $this->getUser(),
                    'instance' => $this->get('celsius3_core.instance_helper')->getSessionInstance()
                )
        );

        $form = $this->createForm(SubscriptionType::class, null, array(
            'user' => $this->getUser(),
        ));

        foreach ($settings as $value) {
            $data = array();
            if ($value->getSubscribedToInterfaceNotifications()) {
                $data[] = 'notification';
            }
            if ($value->getSubscribedToEmailNotifications()) {
                $data[] = 'email';
            }

            if (!(strpos($value->getType(), 'user') === FALSE) || !(strpos($value->getType(), 'message') === FALSE)) {
                $form->get($value->getType())->setData($data);
            } else {
                if ($form->get('event_notification')->has($value->getType())) {
                    $form->get('event_notification')->get($value->getType())->setData($data);
                }
            }
        }

        if ($request->getMethod() === 'POST') {
            $form->submit($request);
            $data = $form->getData();
            if ($this->getUser()->hasRole('ROLE_ADMIN') || $this->getUser()->hasRole('ROLE_SUPERADMIN')) {
                $this->setNotificationTypes('user_notification', $data['user_notification']);
            }
            $this->setNotificationTypes('message_notification', $data['message_notification']);

            foreach ($data['event_notification'] as $notification => $types) {
                $this->setNotificationTypes($notification, $types);
            }

            $em->flush();
        }

        return array(
            'form' => $form->createView()
        );
    }

    private function setNotificationTypes($notification, $types)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Celsius3NotificationBundle:NotificationSettings');
        $instance = $this->get('celsius3_core.instance_helper')->getSessionInstance();

        $notificationSettings = $repository->findOneBy(
                array(
                    'user' => $this->getUser(),
                    'instance' => $instance,
                    'type' => $notification
                )
        );

        if (!$notificationSettings) {
            $notificationSettings = new NotificationSettings();
            $notificationSettings
                    ->setUser($this->getUser())
                    ->setInstance($instance)
                    ->setType($notification);
        }

        $notificationSettings->setSubscribedToEmailNotifications(in_array('email', $types));
        $notificationSettings->setSubscribedToInterfaceNotifications(in_array('notification', $types));

        $em->persist($notificationSettings);
    }

    /**
     * Lists all Notification documents.
     *
     * @Route("/{id}/view", name="user_notification_view", options={"expose"=true})
     * @Template()
     *
     * @return array
     */
    public function viewAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $notification = $em->getRepository('Celsius3NotificationBundle:Notification')
                ->find($id);

        if (!$notification) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.notification');
        }

        $notification->setIsViewed(true);
        $em->persist($notification);
        $em->flush($notification);

        return $this->redirect($this->get('celsius3_notification.notification_manager')->generateUrl($notification));
    }

}
