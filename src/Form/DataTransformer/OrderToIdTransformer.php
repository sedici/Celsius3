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

namespace Celsius3\Form\DataTransformer;

use Celsius3\Entity\Order;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\ORM\EntityManager;

class OrderToIdTransformer implements DataTransformerInterface
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Transforms an object (Order) to a string (id).
     *
     * @param  Order|null $order
     * @return string
     */
    public function transform($order)
    {
        if (null === $order) {
            return "";
        }

        return $order->getId();
    }

    /**
     * Transforms a string (id) to an object (Order).
     *
     * @param  string                        $id
     * @return Order|null
     * @throws TransformationFailedException if object (Order) is not found.
     */
    public function reverseTransform($id)
    {
        if (!$id) {
            return null;
        }

        $order = $this->em->getRepository(Order::class)
                ->find($id);

        if (null === $order) {
            throw new TransformationFailedException(
            sprintf('An order with id "%s" does not exist!', $id));
        }

        return $order;
    }
}
