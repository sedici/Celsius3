<?php

namespace Celsius\Celsius3Bundle\Manager;

class UserManager
{

    private $types = array(
        'baseuser' => 'ROLE_USER',
        'librarian' => 'ROLE_LIBRARIAN',
        'admin' => 'ROLE_ADMIN',
        'superadmin' => 'ROLE_SUPER_ADMIN',
    );

    public function transform($type, $document)
    {
        if (array_key_exists($type, $this->types))
        {
            $document->setRoles(array());
            $document->addRole($this->types[$type]);
        }

        return $document;
    }

    public function getCurrentRole($document)
    {
        $roles = $document->getRoles();

        $default = 'baseuser';
        
        foreach ($this->types as $key => $role)
        {
            if (in_array($role, $roles))
            {
                $default = $key;
            }
        }

        return $default;
    }

}