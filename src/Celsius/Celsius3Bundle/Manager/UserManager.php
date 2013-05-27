<?php

namespace Celsius\Celsius3Bundle\Manager;
use Celsius\Celsius3Bundle\Document\BaseUser;
class UserManager
{
    const ROLE_USER = 'ROLE_USER';
    const ROLE_LIBRARIAN = 'ROLE_LIBRARIAN';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    private $types = array(self::ROLE_USER, self::ROLE_LIBRARIAN,
            self::ROLE_ADMIN, self::ROLE_SUPER_ADMIN,);

    public function transform($type, $document)
    {
        if (in_array($type, $this->types)) {
            $document->setRoles(array());
            $document->addRole($type);
        }

        return $document;
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
}
