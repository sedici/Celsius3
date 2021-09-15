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

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections\ArrayCollection;
use Celsius3\CoreBundle\Entity\BaseUser;

class UsersToUsernamesTransformer implements DataTransformerInterface
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Transforms a collection of objects (BaseUser) to a string (ids).
     *
     * @param  ArrayCollection $users
     * @return string
     */
    public function transform($users)
    {
        if ($users->count() === 0) {
            return "";
        }

        return implode(', ', $users->map(function(BaseUser $user) {
                    return $user->getUsername();
                })->toArray());
    }

    /**
     * Transforms a string (ids) to an ArrayCollection (BaseUser).
     *
     * @param  string                        $usernames
     * @return ArrayCollection
     * @throws TransformationFailedException if object (BaseUser) is not found.
     */
    public function reverseTransform($usernames)
    {
        $col = new ArrayCollection();

        if (!$usernames) {
            return $col;
        }

        foreach (explode(',', $usernames) as $username) {
            if (trim($username) !== '') {
                $user = $this->em->getRepository('Celsius3CoreBundle:BaseUser')
                        ->findOneBy(array('username' => trim($username)));

                if (null === $user) {
                    throw new TransformationFailedException(sprintf('A user with username "%s" does not exist!', $username));
                } else {
                    $col->add($user);
                }
            }
        }

        return $col;
    }
}
