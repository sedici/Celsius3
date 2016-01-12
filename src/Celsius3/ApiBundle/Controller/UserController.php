<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PREBI-SEDICI <info@prebi.unlp.edu.ar> http://prebi.unlp.edu.ar http://sedici.unlp.edu.ar
 *
 * This file is part of Celsius3.
 *
 * Celsius3 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Celsius3 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Celsius3.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Celsius3\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use Symfony\Component\HttpFoundation\Request;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\JsonResponse;

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
    public function usersAction(Request $request)
    {
        $context = SerializationContext::create()->setGroups(array('api'));

        $em = $this->getDoctrine()->getManager();

        $startDate = $request->query->get('startDate');

        $qb = $em->getRepository('Celsius3CoreBundle:BaseUser')
                ->createQueryBuilder('u')
                ->where('u.instance = :instance_id')
                ->setParameter('instance_id', $this->getInstance()->getId());

        if (!is_null($startDate)) {
            $qb = $qb->andWhere('u.createdAt >= :date')
                    ->setParameter('date', $startDate);
        }

        $users = $qb->getQuery()
                ->getResult();

        $view = $this->view($users, 200)->setFormat('json');
        $view->setSerializationContext($context);

        return $this->handleView($view);
    }

    /**
     * @Get("/current_user")
     */
    public function currentUserAction(Request $request)
    {
        $context = SerializationContext::create()->setGroups(array('api'));

        $accessToken = $this->getAccessTokenByToken($request->get('access_token'));

        $isValidToken = false;
        if (!is_null($accessToken)) {
            $isValidToken = $this->validateAccessToken($accessToken);
        }

        if (!$isValidToken) {
            $view = $this->view(array(), 200)->setFormat('json');
        } else {
            $user = $accessToken->getUser();
            $view = $this->view($user, 200)->setFormat('json');
        }

        $view->setSerializationContext($context);
        return $this->handleView($view);
    }

    /**
     * @Get("/check_token")
     */
    public function checkAccessTokenAction(Request $request)
    {
        $response = new JsonResponse();

        $response->setData(array('validAccessToken' => false));
        $accessToken = $this->getAccessTokenByToken($request->query->get('access_token'));
        if ($this->validateAccessToken($accessToken)) {
            $response->setData(array('validAccessToken' => true));
        }

        return $response;
    }

    /**
     * @Get("/{user_id}")
     */
    public function userAction($user_id)
    {
        $context = SerializationContext::create()->setGroups(array('api'));
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('Celsius3CoreBundle:BaseUser')
                ->findOneBy(array(
            'id' => $user_id,
            'instance' => $this->getInstance()->getId(),
        ));

        if (!$user) {
            return $this->createNotFoundException('User not found');
        }

        $view = $this->view($user, 200)->setFormat('json');
        $view->setSerializationContext($context);

        return $this->handleView($view);
    }

    /**
     * @Post("/{user_id}/disable_download")
     */
    public function disableDownloadAction($user_id)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('Celsius3CoreBundle:BaseUser')
                ->findOneBy(array(
            'id' => $user_id,
            'instance' => $this->getInstance()->getId(),
        ));

        if (!$user) {
            return $this->createNotFoundException('User not found');
        }

        $user->setDownloadAuth(false);
        $em->persist($user);
        $em->flush($user);

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
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('Celsius3CoreBundle:BaseUser')
                ->findOneBy(array(
            'id' => $user_id,
            'instance' => $this->getInstance()->getId(),
        ));

        if (!$user) {
            return $this->createNotFoundException('User not found');
        }

        $user->setDownloadAuth(true);
        $em->persist($user);
        $em->flush($user);

        $view = $this->view(array(
                    'result' => true
                        ), 200)
                ->setFormat('json');

        return $this->handleView($view);
    }
}
