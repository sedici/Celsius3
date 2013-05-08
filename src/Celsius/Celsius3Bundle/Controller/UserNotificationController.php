<?php

namespace Celsius\Celsius3Bundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius\Celsius3Bundle\Manager\NotificationManager;
/**
 * Notifications controller.
 *
 * @Route("/user/notification")
 */
class UserNotificationController extends BaseController
{
    
     protected function listQuery($name)
     {
         return parent::listQuery($name)
                        ->field('user.$id')->equals(new \MongoId($this->getUser()->getId()));
     }
     
     /**
     *  Change state to notification.
     *
     * @Route("/{id}/change_state", name="notification_change_state")
     * @Template()
     *
     * @return array
     */
    public function changeStateAction($id)
    {

        $notification = $this->findQuery('Notification', $id);

        if (!$notification)
        {
            throw $this->createNotFoundException('Unable to find Notification.');
        }

        $notification->setViewed(!$notification->getViewed());

        $dm = $this->getDocumentManager();
        $dm->persist($notification);
        $dm->flush();

        $this->get('session')->getFlashBag()->add('success', 'The Notification was successfully ' .
                (($notification->getViewed()) ? 'enabled' : 'disabled'));

        return $this->redirect($this->generateUrl('user_notification'));
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
        $notifications = $this->baseIndex('Notification');
        $allTemplateNotification = $this->getAllTemplate($this->get('request')->get('_locale'));

        foreach($allTemplateNotification as $template)
        {
            $templates[$template->getCode()]= array(
                                'text' => $template->getText(), 
                                'title' => $template->getTitle());
        }
   //     $this->get('twig')->addExtension(new \Twig_Extension_StringLoader());

        return array_merge($notifications, array('templates'=>$templates));
    }
}