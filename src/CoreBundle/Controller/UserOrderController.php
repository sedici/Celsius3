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

namespace Celsius3\CoreBundle\Controller;

use Celsius3\CoreBundle\Form\Type\JournalTypeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius3\CoreBundle\Entity\Order;
use Celsius3\CoreBundle\Form\Type\OrderType;
use Celsius3\CoreBundle\Manager\UserManager;
use Symfony\Component\HttpFoundation\Request;

/**
 * Order controller.
 *
 * @Route("/user/order")
 */
class UserOrderController extends OrderController
{
    protected function listQuery($name)
    {
        return $this->getDoctrine()->getManager()
                ->getRepository('Celsius3CoreBundle:'.$name)
                ->createQueryBuilder('e')
                ->join('e.originalRequest', 'r')
                ->join('e.materialData', 'm')
                ->where('r.instance = :instance')
                ->setParameter('instance', $this->getInstance()->getId())
                ->andWhere('r.owner = :owner OR r.librarian = :owner')
                ->setParameter('owner', $this->getUser()->getId());
    }

    protected function findQuery($name, $id)
    {
        $qb = $this->getDoctrine()->getManager()
                ->getRepository('Celsius3CoreBundle:'.$name)
                ->createQueryBuilder('e')
                ->join('e.originalRequest', 'r')
                ->where('r.instance = :instance')
                ->setParameter('instance', $this->getInstance()->getId());

        $qb = $qb->orWhere($qb->expr()->where('r.owner = :owner')->setParameter('owner', $this->getUser()->getId()));
        $qb = $qb->orWhere($qb->expr()->where('r.librarian = :librarian')->setParameter('librarian', $this->getUser()->getId()));

        return $qb->andWhere('e.id = :id')->setParameter('id', $id)->getQuery()->getSingleResult();
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
        $options = array(
            'instance' => $this->getInstance(),
            'user' => $this->getUser(),
            'actual_user' => $this->getUser(),
        );
        if ($this->get('security.authorization_checker')->isGranted(UserManager::ROLE_LIBRARIAN)) {
            $options['librarian'] = true;
        } else {
            $options['librarian'] = false;
        }

        return $this->baseNew('Order', new Order(), OrderType::class, $options);
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
    public function createAction(Request $request)
    {

        $options = array(
            'instance' => $this->getInstance(),
            'material' => $this->getMaterialType(),
            'user' => $this->getUser(),
            'actual_user' => $this->getUser(),
            'target' => $this->get('request_stack')->getCurrentRequest()->request->get('order')['originalRequest']['target'] ?? ''

        );
        if ($this->get('security.authorization_checker')->isGranted(UserManager::ROLE_LIBRARIAN)) {
            $options['librarian'] = true;
        } else {
            $options['librarian'] = false;
        }

        if ($this->getMaterialType() === JournalTypeType::class)
            $options['other'] = $request->request->get('order')['materialData']['journal_autocomplete'];

        return $this->baseCreate('Order', new Order(), OrderType::class, $options, 'user_index');
    }

    /**
     * Updates de form materialData field.
     *
     * @Route("/change", name="user_order_change",  options={"expose"=true})
     * @Template()
     *
     * @return array
     */
    public function changeAction()
    {
        return $this->change();
    }
}
