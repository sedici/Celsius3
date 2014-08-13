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

namespace Celsius3\MigrationBundle\Manager;

use Doctrine\ODM\MongoDB\DocumentManager;
use Celsius3\MigrationBundle\Helper\CountryHelper;
use Celsius3\MigrationBundle\Document\Association;

class MigrationManager
{
    private $countryHelper;
    private $dm;

    public function __construct(CountryHelper $countryHelper, DocumentManager $dm)
    {
        $this->countryHelper = $countryHelper;
        $this->dm = $dm;
    }

    public function migrate()
    {
        set_time_limit(0);

        $this->countryHelper->migrate();
    }

    public function createAssociation($name, $original_id, $table, $document)
    {
        $association = new Association();
        $association->setName($name);
        $association->setOriginalId($original_id);
        $association->setTable($table);
        $association->setDocument($document);

        $this->dm->persist($association);
        unset($association);
    }
}