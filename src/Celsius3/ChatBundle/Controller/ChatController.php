<?php

namespace Celsius3\ChatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Chat controller.
 *
 * @Route("/admin/chat")
 */
class ChatController extends Controller
{

    /**
     * @Route("/", name="admin_chat")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

}
