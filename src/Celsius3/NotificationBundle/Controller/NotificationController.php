<?php
/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PrEBi <info@prebi.unlp.edu.ar>
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

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Celsius3\CoreBundle\Controller\BaseController;

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
        return parent::listQuery($name)->field('receivers.id')
                        ->equals($this->getUser()->getId());
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
     * @Route("/{id}/view", name="user_notification_view", options={"expose"=true})
     * @Template()
     *
     * @return array
     */
    public function viewAction($id)
    {
        $dm = $this->getDocumentManager();
        $notification = $dm
                ->getRepository('Celsius3NotificationBundle:Notification')
                ->find($id);

        if (!$notification) {
            return $this->createNotFoundException('Notification not found');
        }

        $notification->setIsViewed(true);
        $dm->persist($notification);
        $dm->flush();

        return $this->redirect($this->get('celsius3_notification.notification_manager')->generateUrl($notification));
    }

}