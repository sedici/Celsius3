<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PrEBi <info@prebi.unlp.edu.ar>
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
        $em = $this->getDoctrine()->getManager();

        $startDate = $this->getRequest()->query->get('startDate');

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

        $view = $this->view($users, 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * @Get("/{user_id}")
     */
    public function userAction($user_id)
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

        $view = $this->view($user, 200)
                ->setFormat('json');

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