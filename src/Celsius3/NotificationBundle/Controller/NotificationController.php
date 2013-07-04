<?php

namespace Celsius3\NotificationBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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

        return $this
                ->redirect(
                        $this
                                ->get(
                                        'celsius3_notification.notification_manager')
                                ->generateUrl($notification));
    }
}
