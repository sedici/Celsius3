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

declare(strict_types=1);

namespace Celsius3\CoreBundle\Twig;

use Celsius3\CoreBundle\Entity\BaseUser;
use Celsius3\CoreBundle\Entity\Instance;
use Celsius3\CoreBundle\Manager\UserManager;
use Doctrine\ORM\EntityManager;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

use function array_key_exists;
use function count;
use function is_string;

class AdministrationExtension extends AbstractExtension
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('count_users', [$this, 'countUsers']),
            new TwigFunction('has_higher_roles', [$this, 'hasHigherRoles']),
            new TwigFunction('role_name', [$this, 'roleName']),
            new TwigFunction('full_name', [$this, 'fullName']),
            new TwigFunction('get_buckets', [$this, 'getBuckets']),
            new TwigFunction('pending_data_requests', [$this, 'getPendingDataRequests']),
        ];
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('first_upper', [$this, 'firstUpper']),
        ];
    }

    public function countUsers(Instance $instance)
    {
        return $this->entityManager
            ->getRepository('Celsius3CoreBundle:BaseUser')
            ->countUsers($instance);
    }

    public function hasHigherRoles(BaseUser $user1, BaseUser $user2): bool
    {
        return $user1->hasHigherRolesThan($user2);
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

        return ucfirst(strtolower($text));
    }

    public function getName(): string
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
        }

        if (array_key_exists($name, $agg) && array_key_exists('buckets', $agg[$name])) {
            return $agg[$name]['buckets'];
        }

        return [];
    }

    public function getPendingDataRequests(BaseUser $user): int
    {
        if ($user->hasRole(UserManager::ROLE_SUPER_ADMIN)) {
            return count(
                $this->entityManager
                    ->getRepository('Celsius3CoreBundle:DataRequest')->findBy(['visible' => true, 'exported' => false])
            );
        }

        return 0;
    }
}
