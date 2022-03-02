<?php

namespace Celsius3\TicketBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    public function indexAction()
    {
        return $this->render('Celsius3TicketBundle:Default:index.html.twig');
    }
}
