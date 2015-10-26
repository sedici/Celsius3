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

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('is_catalog_enabled', array($this, 'isCatalogEnabled')),
            new \Twig_SimpleFunction('get_disabled_catalogs_count', array($this, 'getDisabledCatalogsCount')),
        );
    }

    public function isCatalogEnabled(Catalog $catalog)
    {
        return $this->catalog_manager->isCatalogEnabled($catalog);
    }

    public function getDisabledCatalogsCount(Instance $instance, Instance $directory, $start = 0, $count = 0)
    {
        return $this->catalog_manager->getDisabledCatalogsCount($instance, $directory, $start, $count);
    }

    public function getName()
    {
        return 'celsius3_core.catalog_extension';
    }

}
