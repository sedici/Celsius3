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

class CountryHelper
{

    private $container;
    private $dm;
    private $cityHelper;
    private $institutionHelper;

    public function __construct(ContainerInterface $container, CityHelper $cityHelper, InstitutionHelper $institutionHelper)
    {
        $this->container = $container;
        $this->dm = $container->get('doctrine.odm.mongodb.document_manager');
        $this->cityHelper = $cityHelper;
        $this->institutionHelper = $institutionHelper;
    }

    public function migrate($conn)
    {
        $query_paises = 'SELECT * FROM paises WHERE Abreviatura <> ""';
        $result_paises = mysqli_query($conn, $query_paises);

        while ($row_pais = mysqli_fetch_assoc($result_paises)) {
            $country = new Country();
            $country->setName(mb_convert_encoding($row_pais['Nombre'], 'UTF-8'));
            $country->setAbbreviation($row_pais['Abreviatura']);
            $country->setInstance($this->container->get('celsius3_core.instance_manager')->getDirectory());
            $this->dm->persist($country);

            $this->container->get('celsius3_migration.migration_manager')->createAssociation($country->getName(), $row_pais['Id'], 'paises', $country);
            $this->cityHelper->migrate($conn, $row_pais['Id'], $country);
            $this->institutionHelper->migrate($conn, $row_pais['Id'], $country, 0);
            unset($country, $row_pais);
        }
        $this->dm->flush();
        $this->dm->clear();
        unset($query_paises, $result_paises);
    }

}