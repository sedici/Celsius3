<?php

namespace Celsius3\TicketBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('Celsius3TicketBundle:Default:index.html.twig');
    }
}
