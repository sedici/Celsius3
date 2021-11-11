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

use Celsius3\CoreBundle\Entity\Instance;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\ORM\EntityManager;

class InstanceToIdTransformer implements DataTransformerInterface
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Transforms an object (Instance) to a string (id).
     *
     * @param  Instance|null $instance
     * @return string
     */
    public function transform($instance)
    {
        if (null === $instance) {
            return "";
        }

        return $instance->getId();
    }

    /**
     * Transforms a string (id) to an object (Instance).
     *
     * @param  string                        $id
     * @return Instance|null
     * @throws TransformationFailedException if object (Instance) is not found.
     */
    public function reverseTransform($id)
    {
        if (!$id) {
            return null;
        }

        $instance = $this->em->getRepository(Instance::class)
                ->find($id);

        if (null === $instance) {
            throw new TransformationFailedException(sprintf('An instance with id "%s" does not exist!', $id));
        }

        return $instance;
    }
}
