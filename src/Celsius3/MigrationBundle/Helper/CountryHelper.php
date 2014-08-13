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

namespace Celsius3\MigrationBundle\Helper;

use Celsius3\CoreBundle\Document\Country;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\DBAL\Connection;

class CountryHelper
{
    private $container;
    private $dm;
    private $cityHelper;
    private $institutionHelper;
    private $conn;

    public function __construct(ContainerInterface $container, Connection $conn, CityHelper $cityHelper, InstitutionHelper $institutionHelper)
    {
        $this->container = $container;
        $this->dm = $container->get('doctrine.odm.mongodb.document_manager');
        $this->cityHelper = $cityHelper;
        $this->institutionHelper = $institutionHelper;
        $this->conn = $conn;
    }

    public function migrate()
    {
        $query = 'SELECT * FROM paises WHERE Abreviatura <> ""';
        $paises = $this->conn->fetchAll($query);

        foreach ($paises as $pais) {
            $country = new Country();
            $country->setName(mb_convert_encoding($pais['Nombre'], 'UTF-8'));
            $country->setAbbreviation($pais['Abreviatura']);
            $country->setInstance($this->container->get('celsius3_core.instance_manager')->getDirectory());
            $this->dm->persist($country);

            $this->container->get('celsius3_migration.migration_manager')->createAssociation($country->getName(), $pais['Id'], 'paises', $country);
            $this->cityHelper->migrate($pais['Id'], $country);
            $this->institutionHelper->migrate($pais['Id'], $country, 0);
            unset($country, $pais);
        }
        $this->dm->flush();
        $this->dm->clear();
        unset($query, $paises);
    }
}