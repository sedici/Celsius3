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

namespace Celsius3\DataTransformer;

use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Form\DataTransformer\UserToUsernameTransformer;
use FOS\MessageBundle\DataTransformer\RecipientsDataTransformer;

class CustomRecipientsDataTransformer extends RecipientsDataTransformer
{
    /**
     * @var UserToUsernameTransformer
     */
    private $userToUsernameTransformer;

    /**
     * @param UserToUsernameTransformer $userToUsernameTransformer
     */
    public function __construct(UserToUsernameTransformer $userToUsernameTransformer)
    {
        $this->userToUsernameTransformer = $userToUsernameTransformer;
    }

    /**
     * Transforms a collection of recipients into a string
     *
     * @param Collection $recipients
     *
     * @return string
     */
    public function transform($recipients)
    {
        if (null === $recipients) {
            return new ArrayCollection();
        }

        $usernames = new ArrayCollection();

        foreach ($recipients as $recipient) {
            $usernames->add($this->userToUsernameTransformer->transform($recipient));
        }

        return $usernames;
    }

    /**
     * Transforms a string (usernames) to a Collection of UserInterface
     *
     * @param string $usernames
     *
     * @throws UnexpectedTypeException
     * @throws TransformationFailedException
     * @return Collection                    $recipients
     */
    public function reverseTransform($usernames)
    {
        return $usernames;
    }
}
