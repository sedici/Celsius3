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

        $startDate = $this->getRequest()->query->get('startDate');

        $qb = $dm->getRepository('Celsius3CoreBundle:BaseUser')
                        ->createQueryBuilder()
                        ->field('instance.id')->equals($this->getInstance()->getId());

        if (!is_null($startDate)) {
            $qb = $qb->field('createdAt')->gte(new \DateTime($startDate));
        }

        $users = $qb->getQuery()
                ->execute()
                ->toArray();

        $view = $this->view($users, 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

}
