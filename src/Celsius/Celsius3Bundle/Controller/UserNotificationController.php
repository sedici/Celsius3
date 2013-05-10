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
     
     /*Given a particular code and a particular idiom, this funtion returns a template*/
    public function getTemplate($code, $idiom)
    {
        $templates = $this->get('doctrine.odm.mongodb.document_manager')
                                      ->getRepository('CelsiusCelsius3Bundle:Template')
                                      ->createQueryBuilder()
                                      ->field('code')->equals($code)
                                      ->field('idiom')->equals($idiom)
                                      ->getQuery()
                                      ->execute()
                                      ->getNext();
        return $templates;
    }
    
    /*This function returns all existing templates for a given idiom*/
    public function getAllTemplate($idiom)
    {
        $templates = $this->get('doctrine.odm.mongodb.document_manager')
                          ->createQueryBuilder('CelsiusCelsius3Bundle:Template')
                          ->field('idiom')->equals($idiom)
                          ->getQuery()
                          ->execute();
        return $templates;
    }
    
    /*Given a cause_notification, this function returns all existing notification for a specific user*/
    public function getNotificationToUser($cause, $user)
    {
        $notifications = $this->get('doctrine.odm.mongodb.document_manager')
                        ->getRepository('CelsiusCelsius3Bundle:Notification')
                        ->createQueryBuilder()
                        ->field('user.$id')->equals(new \MongoId($user->getId()))
                        ->field('cause')->equals($cause)
                        ->field('viewed')->equals(false)
                        ->getQuery()
                        ->execute();
        return $notifications;
    }
    
    /*This function make and returns all messages belonging to the session' user*/
    public function getMessageNotifiaction($cause)
    {
        $notifications = $this->getNotificationToUser($cause, $this->getUser());
        if (count($notifications) > 0) 
        {
            $templateNotification = $this->getTemplate($cause, $this->get('request')->get('_locale'));
            $notificationsMessagesArray = array();
            foreach ($notifications as $notification)
            {
                $env = new \Twig_Environment(new \Twig_Loader_String());
                $renderTemplate = $env->render($templateNotification->getText(),
                                               array("notification" => $notification));
                array_push($notificationsMessagesArray, array ('id' => $notification->getId(),
                                                               'text' => $renderTemplate,
                                                               'date' => $notification->getCreated()));
            }
            return $notificationsMessagesArray;
        }
    }


    public function loadNotifiactions()
    {
       $causeNotifications = NotificationManager::getCauseNotification();
       foreach($causeNotifications as $cause)
           $array_response[$cause] = $this->getMessageNotifiaction($cause);
       return array('hiddenTemplates' => $array_response);
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
   //   $this->get('twig')->addExtension(new \Twig_Extension_StringLoader());
        return array_merge($notifications, array('templates'=>$templates));
    }
    
    
    /**
     * Lists all Notification documents.
     *
     * @Route("/getNotifications", name="get_notifications_list")
     * @Template()
     *
     * @return array
     */
    public function getNotificationListAction()
    {
       $causeNotifications = NotificationManager::getCauseNotification();
       foreach($causeNotifications as $cause)
           $array_response[$cause] = $this->getMessageNotifiaction($cause);
     
       return $this->render('CelsiusCelsius3Bundle:UserNotification:list.html.twig',
                             array('hiddenTemplates' => $array_response)
                            );
    }
    

}
