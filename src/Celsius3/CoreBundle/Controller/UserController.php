<?php

namespace Celsius3\CoreBundle\Controller;

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
        $lastMessages = $this->getDocumentManager()
                ->getRepository('Celsius3MessageBundle:Thread')
                ->createQueryBuilder()
                ->field('participants.id')->equals($this->getUser()->getId())
                ->sort('lastMessageDate', 'desc')
                ->limit(3)
                ->getQuery()
                ->execute();

        return array(
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
