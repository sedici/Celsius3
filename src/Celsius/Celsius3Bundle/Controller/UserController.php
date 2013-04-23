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
      return $this->loadNotifiactions();
    }

    /**
     * @Route("/ajax", name="user_ajax")
     */
    public function ajaxAction()
    {
        return $this->ajax($this->getInstance(), $this->getUser());
    }

}
