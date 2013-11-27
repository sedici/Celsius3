<?php

namespace Celsius3\CoreBundle\Manager;

use Celsius3\CoreBundle\Document\BaseUser;
use Celsius3\CoreBundle\Document\Institution;

class UserManager
{

    const ROLE_USER = 'ROLE_USER';
    const ROLE_LIBRARIAN = 'ROLE_LIBRARIAN';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    private $types = array(
        self::ROLE_USER,
        self::ROLE_LIBRARIAN,
        self::ROLE_ADMIN,
        self::ROLE_SUPER_ADMIN,
    );

    private function iterateInstitutions(Institution $institution)
    {
        $results = array($institution->getId());
        foreach ($institution->getInstitutions() as $child) {
            $results = array_merge($results, $this->iterateInstitutions($child));
        }
        return $results;
    }

    public function transform($type, BaseUser $document)
    {
        if (in_array($type, $this->types)) {
            $document->setRoles(array());
            $document->addRole($type);
        };
    }

    public function getCurrentRole(BaseUser $document)
    {
        $roles = $document->getRoles();

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
