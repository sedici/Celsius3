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

use Celsius3\CoreBundle\Document\Catalog;
use Celsius3\CoreBundle\Document\Instance;
use Doctrine\ODM\MongoDB\DocumentManager;

class CatalogManager
{

    const CATALOG__NON_SEARCHED = 'non_searched';
    const CATALOG__FOUND = 'found';
    const CATALOG__PARTIALLY_FOUND = 'partially_found';
    const CATALOG__NOT_FOUND = 'not_found';

    private $dm;
    private $instance_manager;

    public function __construct(DocumentManager $dm, InstanceManager $instance_manager)
    {
        $this->dm = $dm;
        $this->instance_manager = $instance_manager;
    }

    public static function getResults()
    {
        return array(
            self::CATALOG__FOUND,
            self::CATALOG__PARTIALLY_FOUND,
            self::CATALOG__NOT_FOUND,
            self::CATALOG__NOT_SEARCHED,
        );
    }

    public function getCatalogs(Instance $instance = null)
    {
        return $this->dm->getRepository('Celsius3CoreBundle:Catalog')
                        ->findBy(array(
                            'instance.id' => $instance->getId(),
        ));
    }

    public function getAllCatalogs(Instance $instance)
    {
        $qb = $this->dm->getRepository('Celsius3CoreBundle:Catalog')
                ->createQueryBuilder();

        return $qb->addOr($qb->expr()->field('instance.id')->equals($instance->getId()))
                        ->addOr($qb->expr()->field('instance.id')->equals($this->instance_manager->getDirectory()->getId()))
                        ->getQuery()
                        ->execute();
    }

    public function getCatalogResults($catalogs, $title)
    {
        return $this->dm->getRepository('Celsius3CoreBundle:CatalogResult')
                        ->createQueryBuilder()
                        ->field('title')->equals($title)
                        ->field('catalog.id')->in(array_map(function (Catalog $catalog) {
                                    return $catalog->getId();
                                }, $catalogs->toArray()))
                        ->getQuery()
                        ->execute();
    }

}