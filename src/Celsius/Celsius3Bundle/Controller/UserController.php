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
    public function showMessageNotifiaction()
    {
        
     /* $notification = $this->getDocumentManager()
                             ->getRepository('CelsiusCelsius3Bundle:Notification')
                             ->createQueryBuilder()
                             //->field('users.id')->equals($this->getUser()->getId());
                             ->field('users')
                             ->includesReferenceTo($this->getUser())
                             ->field('id')->equals($this->getUser()->getId());
     */
        
   //     $repository = $this->get('doctrine.odm.mongodb.document_manager')
                         //   ->getRepository('CelsiusCelsius3Bundle:Notification');
        
    //    $notification = $repository->findAll();

        
        //$notification = $this->listQuery('Notification');
        
     
        
   //     var_dump($notification->getId());die;
 

    }
    
    /**
     * @Route("/", name="user_index")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
      //  $this->showMessageNotifiaction();    
        
        return array();
    }

    /**
     * @Route("/ajax", name="user_ajax")
     */
    public function ajaxAction()
    {
        return $this->ajax($this->getInstance(), $this->getUser());
    }

}
