<?php

namespace Celsius3\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;

/**
 * User controller.
 *
 * @Route("/users")
 */
class UserController extends BaseController
{
    /**
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

    /**
     * @Get("/{user_id}")
     */
    public function userAction($user_id)
    {
        $dm = $this->getDocumentManager();

        $user = $dm->getRepository('Celsius3CoreBundle:BaseUser')
                ->findOneBy(array(
            'id' => $user_id,
            'instance.id' => $this->getInstance()->getId(),
        ));

        if (!$user) {
            return $this->createNotFoundException('User not found');
        }

        $view = $this->view($user, 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * @Post("/{user_id}/disable_download")
     */
    public function disableDownloadAction($user_id)
    {
        $dm = $this->getDocumentManager();

        $user = $dm->getRepository('Celsius3CoreBundle:BaseUser')
                ->findOneBy(array(
            'id' => $user_id,
            'instance.id' => $this->getInstance()->getId(),
        ));

        if (!$user) {
            return $this->createNotFoundException('User not found');
        }

        $user->setDownloadAuth(false);
        $dm->persist($user);
        $dm->flush();

        $view = $this->view(array(
                    'result' => true
                        ), 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * @Post("/{user_id}/enable_download")
     */
    public function enableDownloadAction($user_id)
    {
        $dm = $this->getDocumentManager();

        $user = $dm->getRepository('Celsius3CoreBundle:BaseUser')
                ->findOneBy(array(
            'id' => $user_id,
            'instance.id' => $this->getInstance()->getId(),
        ));

        if (!$user) {
            return $this->createNotFoundException('User not found');
        }

        $user->setDownloadAuth(true);
        $dm->persist($user);
        $dm->flush();

        $view = $this->view(array(
                    'result' => true
                        ), 200)
                ->setFormat('json');

        return $this->handleView($view);
    }
}
