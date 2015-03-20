<?php

namespace Celsius3\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Debug\Exception\FlattenException;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Exception
 *
 * @author oscar
 */
class ExceptionController extends Controller
{
    public function showExceptionAction(){
        $this->render('TwigBundle:Exception:error.html.twig');
    }
}
