<?php

namespace Celsius3\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Get;

/**
 * User controller.
 *
 * @Route("/user")
 */
class UserController extends FOSRestController
{

    /**
     * GET Route annotation.
     * @Get("/")
     */
    public function getUsersAction()
    {
        return json_encode(array(
            'sarasa' => 'sarasa',
        ));
    }

}
