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

namespace Celsius3\CoreBundle\Manager;

use Celsius3\CoreBundle\Entity\BaseUser;
use Celsius3\CoreBundle\Entity\Institution;

class UserManager
{
    const ROLE_USER = 'ROLE_USER';
    const ROLE_LIBRARIAN = 'ROLE_LIBRARIAN';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';
    const ROLE_MIGRATOR = 'ROLE_MIGRATOR';
    private $types = array(
        self::ROLE_USER,
        self::ROLE_LIBRARIAN,
        self::ROLE_ADMIN,
        self::ROLE_SUPER_ADMIN,
        self::ROLE_MIGRATOR,
    );

    private function iterateInstitutions(Institution $institution)
    {
        $results = array($institution->getId());
        foreach ($institution->getInstitutions() as $child) {
            $results = array_merge($results, $this->iterateInstitutions($child));
        }

        return $results;
    }

    public function transform($type, BaseUser $entity)
    {
        if (in_array($type, $this->types)) {
            $entity->setRoles(array());
            $entity->addRole($type);
        };
    }

    public function getCurrentRole(BaseUser $entity)
    {
        $roles = $entity->getRoles();

        $default = self::ROLE_USER;

        foreach ($this->types as $role) {
            if (in_array($role, $roles)) {
                $default = $role;
            }
        }

        return $default;
    }

    public function getLibrarianInstitutions(BaseUser $librarian = null)
    {
        if ($librarian) {
            return $this->iterateInstitutions($librarian->getInstitution(), array());
        } else {
            return array();
        }
    }
}