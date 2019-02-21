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

namespace Celsius3\CoreBundle\Manager;

use Doctrine\ORM\EntityManager;

class UnionManager
{
    private $em;
    private $instance_manager;
    private $references = array(
        'Celsius3CoreBundle:Country' => array(
            'Celsius3CoreBundle:City' => array(
                'country',
            ),
            'Celsius3CoreBundle:Institution' => array(
                'country',
            ),
        ),
        'Celsius3CoreBundle:City' => array(
            'Celsius3CoreBundle:Institution' => array(
                'city',
            ),
        ),
        'Celsius3CoreBundle:Institution' => array(
            'Celsius3CoreBundle:BaseUser' => array(
                'institution',
            ),
            'Celsius3CoreBundle:Institution' => array(
                'parent',
            ),
            'Celsius3CoreBundle:Catalog' => array(
                'institution',
            ),
            'Celsius3CoreBundle:Contact' => array(
                'institution',
            ),
            'Celsius3CoreBundle:Event\\SingleInstanceRequestEvent' => array(
                'provider',
            ),
            'Celsius3CoreBundle:Event\\MultiInstanceRequestEvent' => array(
                'provider',
            ),
        ),
        'Celsius3CoreBundle:Catalog' => array(
            'Celsius3CoreBundle:Event\\SearchEvent' => array(
                'catalog',
            ),
            'Celsius3CoreBundle:CatalogPosition' => array(
                'catalog',
            ),
            'Celsius3CoreBundle:CatalogResult' => array(
                'catalog'
            )
        ),
        'Celsius3CoreBundle:Journal' => array(
            'Celsius3CoreBundle:JournalType' => array(
                'journal',
            ),
        ),
        'Celsius3CoreBundle:BaseUser' => array(
            'Celsius3CoreBundle:Request' => array(
                'owner',
                'operator',
                'creator',
                'librarian',
            ),
            'Celsius3CoreBundle:FileDownload' => array(
                'user',
            ),
            'Celsius3CoreBundle:Contact' => array(
                'user',
            ),
            'Celsius3CoreBundle:CustomUserValue' => array(
                'user',
            ),
            'Celsius3CoreBundle:Email' => array(
                'sender',
            ),
            'Celsius3CoreBundle:Event\\Event' => array(
                'operator',
            ),
            'Celsius3CoreBundle:State' => array(
                'operator',
            ),
            'Celsius3MessageBundle:Message' => array(
                'sender',
            ),
            'Celsius3MessageBundle:ThreadMetadata' => array(
                'participant',
            ),
            'Celsius3NotificationBundle:BaseUserNotification' => array(
                'object',
            ),
        ),
    );

    public function __construct(EntityManager $em, InstanceManager $instance_manager)
    {
        $this->em = $em;
        $this->instance_manager = $instance_manager;
    }

    public function union($name, $main, array $elements, $updateInstance)
    {
        $this->em->getFilters()->disable('softdeleteable');

        if (array_key_exists($name, $this->references)) {
            foreach ($this->references[$name] as $key => $reference) {
                foreach ($reference as $field) {
                    $this->em->getRepository($key)->union($field, $main->getId(), $elements);
                }
            }
        }

        $this->em->getFilters()->enable('softdeleteable');

        $this->em->getRepository($name)->deleteUnitedEntities($elements);

        if ($updateInstance) {
            $main->setInstance($this->instance_manager->getDirectory());
            $this->em->persist($main);
            $this->em->flush($main);
        }
    }
}
