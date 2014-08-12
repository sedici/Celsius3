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

class CityHelper
{

    private $dm;
    private $container;
    private $institutionHelper;

    public function __construct(ContainerInterface $container, InstitutionHelper $institutionHelper)
    {
        $this->dm = $container->get('doctrine.odm.mongodb.document_manager');
        $this->container = $container;
        $this->institutionHelper = $institutionHelper;
    }

    public function migrate($conn, $country_id, $country)
    {
        $query_localidades = 'SELECT * FROM localidades WHERE Codigo_Pais = '
                . $country_id;
        $result_localidades = mysqli_query($conn, $query_localidades);

        while ($row_localidad = mysqli_fetch_assoc($result_localidades)) {
            $city = new City();
            $city->setCountry($country);
            $city->setName(mb_convert_encoding($row_localidad['Nombre'], 'UTF-8'));
            $city->setInstance($this->container->get('celsius3_core.instance_manager')->getDirectory());
            $this->dm->persist($city);

            $this->container->get('celsius3_migration.migration_manager')->createAssociation($city->getName(), $row_localidad['Id'], 'localidades', $city);
            $this->institutionHelper->migrate($conn, $country_id, $country, $row_localidad['Id'], $city);
            unset($city, $row_localidad);
        }
        unset($query_localidades, $result_localidades);
    }

}