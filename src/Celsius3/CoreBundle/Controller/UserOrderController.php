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

namespace Celsius3\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius3\CoreBundle\Entity\Order;
use Celsius3\CoreBundle\Form\Type\OrderType;
use Celsius3\CoreBundle\Filter\Type\OrderFilterType;
use Celsius3\CoreBundle\Manager\UserManager;

/**
 * Order controller.
 *
 * @Route("/user/order")
 */
class UserOrderController extends OrderController
{

    protected function listQuery($name)
    {
        $qb = $this->getDoctrine()->getManager()
                        ->getRepository('Celsius3CoreBundle:' . $name)
                        ->createQueryBuilder('e')
                        ->join('e.originalRequest','r')
                        ->where('r.instance = :instance')->setParameter('instance', $this->getInstance()->getId())
                        ->orWhere('r.owner = :owner')->setParameter('owner', $this->getUser()->getId())
                        ->orWhere('r.librarian = :librarian')->setParameter('librarian', $this->getUser()->getId());

        return $qb;
    }

    protected function findQuery($name, $id)
    {
        $qb = $this->getDoctrine()->getManager()
                        ->getRepository('Celsius3CoreBundle:' . $name)
                        ->createQueryBuilder('e')
                        ->where('e.instance = :instance')->setParameter('instance', $this->getInstance()->getId());

        $qb = $qb->orWhere($qb->expr()->where('e.owner = :owner')->setParameter('owner', $this->getUser()->getId()));
        $qb = $qb->orWhere($qb->expr()->where('e.librarian = :librarian')->setParameter('librarian', $this->getUser()->getId()));

        return $qb->andWhere('e.id = :id')->setParameter('id', $id)->getQuery()->getSingleResult();
    }

    /**
     * Lists all Order entities.
     *
     * @Route("/", name="user_order", options={"expose"=true})
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return $this->baseIndex('Order', $this->createForm(new OrderFilterType($this->getInstance(), $this->getUser())));
    }

    /**
     * Finds and displays a Order entity.
     *
     * @Route("/{id}/show", name="user_order_show")
     * @Template()
     *
     * @param string $id The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function showAction($id)
    {
        return $this->baseShow('Order', $id);
    }

    /**
     * Displays a form to create a new Order entity.
     *
     * @Route("/new", name="user_order_new", options={"expose"=true})
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        if ($this->get('security.context')->isGranted(UserManager::ROLE_LIBRARIAN)) {
            $type = new OrderType($this->getInstance(), null, $this->getUser(), null, true);
        } else {
            $type = new OrderType($this->getInstance(), null, $this->getUser());
        }

        return $this->baseNew('Order', new Order(), $type);
    }

    /**
     * Creates a new Order entity.
     *
     * @Route("/create", name="user_order_create")
     * @Method("post")
     * @Template("Celsius3CoreBundle:UserOrder:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        if ($this->get('security.context')->isGranted(UserManager::ROLE_LIBRARIAN)) {
            $type = new OrderType($this->getInstance(), $this->getMaterialType(), $this->getUser(), null, true);
        } else {
            $type = new OrderType($this->getInstance(), $this->getMaterialType(), $this->getUser());
        }

        return $this->baseCreate('Order', new Order(), $type, 'user_order');
    }

    /**
     * Updates de form materialData field.
     *
     * @Route("/change", name="user_order_change")
     * @Template()
     *
     * @return array
     */
    public function changeAction()
    {
        return $this->change();
    }

}
