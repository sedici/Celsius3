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

namespace Celsius3\CoreBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\ORM\EntityManager;

class JournalToIdTransformer implements DataTransformerInterface
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Transforms an object (Journal) to a string (id).
     *
     * @param  Journal|null $user
     * @return string
     */
    public function transform($journal)
    {
        if (null === $journal) {
            return "";
        }

        return $journal->getId();
    }

    /**
     * Transforms a string (id) to an object (Journal).
     *
     * @param  string                        $id
     * @return Journal|null
     * @throws TransformationFailedException if object (Journal) is not found.
     */
    public function reverseTransform($id)
    {
        if (!$id) {
            return null;
        }

        $user = $this->em->getRepository('Celsius3CoreBundle:Journal')
                ->findOneBy(array('id' => $id));

        if (null === $user) {
            throw new TransformationFailedException(
            sprintf('A journal with id "%s" does not exist!', $id));
        }

        return $user;
    }
}
