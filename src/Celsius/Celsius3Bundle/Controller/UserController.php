<?php
namespace Celsius\Celsius3Bundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * User controller
 * 
 * @Route("/user") 
 */
class UserController extends BaseInstanceDependentController
{
    /**
     * @Route("/", name="user_index")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
      $lastOrders = $this->getDocumentManager()
              ->getRepository('CelsiusCelsius3Bundle:Order')
              ->createQueryBuilder()
              ->field('owner.id')->equals($this->getUser()->getId())
              ->sort('created', 'desc')
              ->limit(10)
              ->getQuery()
              ->execute();
      
      $lastMessages = $this->getDocumentManager()
              ->getRepository('CelsiusCelsius3MessageBundle:Thread')
              ->createQueryBuilder()
              ->field('participants.id')->equals($this->getUser()->getId())
              ->sort('lastMessageDate', 'desc')
              ->limit(10)
              ->getQuery()
              ->execute();
      
      return array(
          'lastOrders' => $lastOrders,
          'lastMessages' => $lastMessages,
      );
    }

    /**
     * @Route("/ajax", name="user_ajax")
     */
    public function ajaxAction()
    {
        return $this->ajax($this->getInstance(), $this->getUser());
    }

}
