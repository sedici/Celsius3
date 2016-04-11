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

namespace Celsius3\CoreBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Celsius3\CoreBundle\Entity\Instance;
use Celsius3\CoreBundle\Entity\BaseUser;
use Celsius3\CoreBundle\Manager\UserManager;

class AdministrationExtension extends \Twig_Extension
{

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('count_users', array($this, 'countUsers')),
            new \Twig_SimpleFunction('has_higher_roles', array($this, 'hasHigherRoles')),
            new \Twig_SimpleFunction('role_name', array($this, 'roleName')),
            new \Twig_SimpleFunction('full_name', array($this, 'fullName')),
            new \Twig_SimpleFunction('get_buckets', array($this, 'getBuckets')),
        );
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('first_upper', array($this, 'firstUpper')),
        );
    }

    public function countUsers(Instance $instance)
    {
        return $this->container
                        ->get('doctrine.orm.entity_manager')
                        ->getRepository('Celsius3CoreBundle:BaseUser')
                        ->countUsers($instance);
    }

    public function hasHigherRoles(BaseUser $user1, BaseUser $user2)
    {
        return $this->container->get('celsius3_core.user_manager')
                        ->hasHigherRoles($user1, $user2);
    }

    public function roleName($role)
    {
        return UserManager::$roles_names[$role];
    }

    public function firstUpper($text)
    {
        if (!is_string($text)) {
            return $text;
        }

        $arr = explode(" ", $text);

        $t = '';
        foreach ($arr as $a) {
            $t .= ucfirst(strtolower($a)) . ' ';
        }

        return $t;
    }

    public function getName()
    {
        return 'celsius3_core.administration_extension';
    }

    public function fullName($entity = null)
    {
        return $entity ? $entity->getFullName() : '';
    }

    public function getBuckets($name, $agg)
    {
        if (array_key_exists('buckets', $agg)) {
            return $agg['buckets'];
        } elseif (array_key_exists($name, $agg) && array_key_exists('buckets', $agg[$name])) {
            return $agg[$name]['buckets'];
        } else {
            return [];
        }
    }

}
