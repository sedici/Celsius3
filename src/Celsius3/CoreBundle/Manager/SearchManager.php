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

use Doctrine\ORM\EntityManager;
use Celsius3\CoreBundle\Entity\Instance;

class SearchManager
{

    private $em;
    private $typeList = array(
        'user' => 'BaseUser',
        'journal' => 'JournalType',
    );

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    private function getTypeRepository($type)
    {
        if (array_key_exists($type, $this->typeList)) {
            return $this->typeList[$type];
        } else {
            return null;
        }
    }

    /**
     * Performs the search on the specified repository
     *
     * @param  string   $repository
     * @param  string   $type
     * @param  string   $keyword
     * @param  Instance $instance
     * @return array
     */
    public function search($repository, $type, $keyword, Instance $instance = null, $state = null)
    {
        return $this->em->getRepository('Celsius3CoreBundle:' . $repository)
                        ->findByTerm($keyword, $instance, $this->getTypeRepository($type), null, $state);
    }

}
