<?php

namespace Celsius3\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Get;

/**
 * User controller.
 *
 * @Route("/users")
 */
class UserController extends BaseController
{

    /**
     * GET Route annotation.
     * @Get("/")
     */
    public function usersAction()
    {
        $dm = $this->getDocumentManager();

        $users = $dm->getRepository('Celsius3CoreBundle:BaseUser')
                ->findBy(array('instance.id' => $this->getInstance()->getId()))
                ->toArray();

        $view = $this->view($users, 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

}
