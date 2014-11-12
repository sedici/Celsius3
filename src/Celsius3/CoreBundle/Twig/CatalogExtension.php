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

namespace Celsius3\CoreBundle\Twig;

use Celsius3\CoreBundle\Entity\Instance;
use Celsius3\CoreBundle\Manager\CatalogManager;
use Celsius3\CoreBundle\Entity\Request;
use Doctrine\Common\Collections\ArrayCollection;
use Celsius3\CoreBundle\Entity\Catalog;

class CatalogExtension extends \Twig_Extension
{
    private $catalog_manager;

    public function __construct(CatalogManager $catalog_manager)
    {
        $this->catalog_manager = $catalog_manager;
    }

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    public function getFunctions()
    {
        return array(
            'get_catalogs' => new \Twig_Function_Method($this, 'getCatalogs'),
            'get_searches' => new \Twig_Function_Method($this, 'getSearches'),
            'search_exists' => new \Twig_Function_Method($this, 'searchExists'),
        );
    }

    public function getCatalogs(Instance $instance)
    {
        return $this->catalog_manager->getAllCatalogs($instance);
    }

    public function getSearches(Request $request, $result = null)
    {
        return $this->catalog_manager->getSearches($request, $result);
    }

    public function searchExists($searches, Catalog $catalog)
    {
        $searches = new ArrayCollection($searches);
        $result = $searches->filter(function ($entry) use ($catalog) {
                    return $entry->getCatalog()->getId() == $catalog->getId();
                })->first();

        return false !== $result ? $result : null;
    }

    public function getName()
    {
        return 'celsius3_core.catalog_extension';
    }
}
