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
    private $tokenList = array(
        'user:' => 'BaseUser',
    );

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Detects if any token from $tokenList is present in $keyword
     *
     * @param  String $keyword The raw string to parse
     * @return array
     */
    private function parseTokens($keyword)
    {
        $where = array();
        foreach ($this->tokenList as $token => $repository) {
            if (preg_match('/\b' . $token . ' \b/i', $keyword)) {
                $where[$repository] = trim(str_replace($token, '', $keyword));
            }
        }

        return $where;
    }

    /**
     * Performs the search on the specified repository
     *
     * @param  string   $repository
     * @param  string   $keyword
     * @param  Instance $instance
     * @return array
     */
    public function search($repository, $keyword, Instance $instance = null)
    {
        return $this->em->getRepository('Celsius3CoreBundle:' . $repository)
                        ->findByTerm($keyword, $instance, $this->parseTokens($keyword));
    }
}