<?php

namespace Celsius3\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;

/**
 * User controller.
 *
 * @Route("/admin/rest/users")
 */
class AdminBaseUserRestController extends BaseInstanceDependentRestController
{

    /**
     * GET Route annotation.
     * @Get("", name="admin_rest_user", options={"expose"=true})
     */
    public function getUsersAction()
    {
        $users = $this->getDocumentManager()
                ->getRepository('Celsius3CoreBundle:BaseUser')
                ->findBy(array(
            'instance.id' => $this->getInstance(),
        ));

        $view = $this->view(array_values($users), 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/pending", name="admin_rest_user_pending", options={"expose"=true})
     */
    public function getPendingUsersAction()
    {
        $users = $this->getDocumentManager()
                ->getRepository('Celsius3CoreBundle:BaseUser')
                ->findPendingUsers($this->getInstance())
                ->toArray();

        $view = $this->view(array_values($users), 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Post("/enable", name="admin_rest_user_enable", options={"expose"=true})
     */
    public function enableUserAction(Request $request)
    {
        $user_id = $request->request->get('id', null);

        $user = $this->getDocumentManager()
                ->getRepository('Celsius3CoreBundle:BaseUser')
                ->findOneBy(array(
            'instance.id' => $this->getInstance()->getId(),
            'id' => $user_id,
        ));

        if (!$user) {
            return $this->createNotFoundException('User not found.');
        }

        $user->setEnabled(true);
        $dm = $this->getDocumentManager();
        $dm->persist($user);
        $dm->flush();
        
        $view = $this->view($user->isEnabled(), 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/{id}", name="admin_rest_user_get", options={"expose"=true})
     */
    public function getUserAction($id)
    {
        $dm = $this->getDocumentManager();

        $user = $dm->getRepository('Celsius3CoreBundle:BaseUser')
                ->find($id);

        if (!$user) {
            return $this->createNotFoundException('User not found.');
        }

        $view = $this->view($user, 200)
                ->setFormat('json');

        return $this->handleView($view);
    }
}