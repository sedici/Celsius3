<?php

namespace Celsius3\CoreBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Get;

/**
 * BaseUser controller.
 *
 * @Route("/user/rest/user")
 */
class UserBaseUserRestController extends BaseInstanceDependentRestController
{

    /**
     * GET Route annotation.
     * @Get("/", name="user_rest_user", options={"expose"=true})
     */
    public function getUsersAction()
    {
        $view = $this->view(array(), 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/{id}", name="user_rest_user_get", options={"expose"=true})
     */
    public function getUserAction($id)
    {
        $user = $this->getUser()->getId() === $id ? $this->getUser() : null;

        $view = $this->view($user, 200)
                ->setFormat('json');

        return $this->handleView($view);
    }
}