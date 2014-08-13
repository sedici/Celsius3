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

use Celsius3\CoreBundle\Document\City;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\DBAL\Connection;

class CityHelper
{
    private $dm;
    private $container;
    private $institutionHelper;
    private $conn;

    public function __construct(ContainerInterface $container, Connection $conn, InstitutionHelper $institutionHelper)
    {
        $this->dm = $container->get('doctrine.odm.mongodb.document_manager');
        $this->container = $container;
        $this->institutionHelper = $institutionHelper;
        $this->conn = $conn;
    }

    public function migrate($country_id, $country)
    {
        $query = 'SELECT * FROM localidades WHERE Codigo_Pais = ?';
        $localidades = $this->conn->fetchAll($query, array($country_id));

        foreach ($localidades as $localidad) {
            $city = new City();
            $city->setCountry($country);
            $city->setName(mb_convert_encoding($localidad['Nombre'], 'UTF-8'));
            $city->setInstance($this->container->get('celsius3_core.instance_manager')->getDirectory());
            $this->dm->persist($city);

            $this->container->get('celsius3_migration.migration_manager')->createAssociation($city->getName(), $localidad['Id'], 'localidades', $city);
            $this->institutionHelper->migrate($country_id, $country, $localidad['Id'], $city);
            unset($city, $localidad);
        }
        unset($query, $localidades);
    }
}