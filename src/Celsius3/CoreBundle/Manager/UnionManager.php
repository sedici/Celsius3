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

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\Common\Collections\ArrayCollection;

class UnionManager
{
    private $dm;
    private $instance_manager;
    private $references = array(
        'Celsius3CoreBundle:Country' => array(
            'Celsius3CoreBundle:City' => array(
                'country'
            )
        ),
        'Celsius3CoreBundle:City' => array(
            'Celsius3CoreBundle:Institution' => array(
                'city'
            )
        ),
        'Celsius3CoreBundle:Institution' => array(
            'Celsius3CoreBundle:BaseUser' => array(
                'institution'
            ),
            'Celsius3CoreBundle:Institution' => array(
                'parent'
            ),
            'Celsius3CoreBundle:Catalog' => array(
                'institution'
            )
        ),
        'Celsius3CoreBundle:Journal' => array(
            'Celsius3CoreBundle:Order' => array(
                'materialData.journal'
            )
        ),
        'Celsius3CoreBundle:BaseUser' => array(
            'Celsius3CoreBundle:Order' => array(
                'owner',
                'operator',
                'creator'
            ),
            'Celsius3CoreBundle:BaseUser' => array(
                'librarian'
            ),
            'Celsius3MessageBundle:Message' => array(
                'sender',
                'receiver'
            )
        )
    );

    public function __construct(DocumentManager $dm, InstanceManager $instance_manager)
    {
        $this->dm = $dm;
        $this->instance_manager = $instance_manager;
    }

    public function union($name, $main, ArrayCollection $elements, $updateInstance)
    {
        if (array_key_exists($name, $this->references)) {
            foreach ($this->references[$name] as $key => $reference) {
                foreach ($reference as $field) {
                    $this->dm->getRepository($key)
                            ->createQueryBuilder()
                            ->update()
                            ->field($field . '.id')
                            ->in(array_keys($elements->toArray()))
                            ->field($field . '.id')
                            ->set($main->getId())
                            ->getQuery(array(
                                'multiple' => true
                            ))
                            ->execute();
                }
            }
        }

        $this->dm->getRepository('Celsius3CoreBundle:' . $name)
                ->createQueryBuilder()
                ->remove()
                ->field('id')
                ->in(array_keys($elements->toArray()))
                ->getQuery()
                ->execute();

        if ($updateInstance) {
            $main->setInstance($this->instance_manager->getDirectory());
            $this->dm->persist($main);
            $this->dm->flush();
        }
    }
}