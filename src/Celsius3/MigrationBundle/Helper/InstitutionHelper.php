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

use Celsius3\CoreBundle\Document\Institution;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\DBAL\Connection;

class InstitutionHelper
{
    private $dm;
    private $container;
    private $hive;
    private $conn;

    public function __construct(ContainerInterface $container, Connection $conn)
    {
        $this->container = $container;
        $this->dm = $this->container->get('doctrine.odm.mongodb.document_manager');
        $this->hive = $this->dm->getRepository('Celsius3CoreBundle:Hive')
                ->findOneBy(array(
            'name' => 'LibLink',
        ));
        $this->conn = $conn;
    }

    public function migrate($country_id, $country, $city_id, $city = null)
    {
        $query = 'SELECT * FROM instituciones WHERE Codigo_Localidad =  ? AND Codigo_Pais = ?';
        $instituciones = $this->conn->fetchAll($query, array($city_id, $country_id));

        foreach ($instituciones as $institucion) {
            $institution = new Institution();
            $institution->setCountry($country);
            $institution->setCity($city);
            $institution->setName(mb_convert_encoding($institucion['Nombre'], 'UTF-8'));
            $institution->setAbbreviation(mb_convert_encoding($institucion['Abreviatura'], 'UTF-8'));
            if ((bool) $institucion['Participa_Proyecto']) {
                $institution->setHive($this->hive);
            }
            if ($institucion['Direccion'] != '') {
                $institution->setAddress(mb_convert_encoding($institucion['Direccion'], 'UTF-8'));
            }
            if ($institucion['Sitio_Web'] != '') {
                $institution->setWebsite($institucion['Sitio_Web']);
            }
            $institution->setInstance($this->container->get('celsius3_core.instance_manager')->getDirectory());
            $this->dm->persist($institution);

            $this->container->get('celsius3_migration.migration_manager')->createAssociation($institution->getName(), $institucion['Codigo'], 'instituciones', $institution);

            $this->migrateDependencies($institucion['Codigo'], $institution, $country, $city);

            unset($institution, $institucion);
        }
        unset($query, $instituciones);
    }

    private function migrateDependencies($institution_id, $institution, $country, $city)
    {
        $query = 'SELECT * FROM dependencias WHERE Codigo_Institucion = ?';
        $dependencias = $this->conn->fetchAll($query, array($institution_id));

        foreach ($dependencias as $dependencia) {
            $dependency = new Institution();
            $dependency->setCountry($country);
            $dependency->setCity($city);
            $dependency->setParent($institution);
            $dependency->setName(mb_convert_encoding($dependencia['Nombre'], 'UTF-8'));
            $dependency->setAbbreviation(mb_convert_encoding($dependencia['Abreviatura'], 'UTF-8'));
            if ((bool) $dependencia['Es_LibLink']) {
                $dependency->setHive($this->hive);
            }
            if ($dependencia['Direccion'] != '') {
                $dependency->setAddress(mb_convert_encoding($dependencia['Direccion'], 'UTF-8'));
            }
            if ($dependencia['Hipervinculo1'] != '') {
                $dependency->setWebsite($dependencia['Hipervinculo1']);
            }
            $dependency->setInstance($this->container->get('celsius3_core.instance_manager')->getDirectory());
            $this->dm->persist($dependency);

            $this->container->get('celsius3_migration.migration_manager')->createAssociation($dependency->getName(), $dependencia['Id'], 'dependencias', $dependency);

            $this->migrateUnits($dependencia['Id'], $dependency, $country, $city);

            unset($dependency, $dependencia);
        }
        unset($query, $dependencias);
    }

    private function migrateUnits($dependency_id, $dependency, $country, $city)
    {
        $query = 'SELECT * FROM unidades WHERE Codigo_Dependencia = ?';
        $unidades = $this->conn->fetchAll($query, array($dependency_id));

        foreach ($unidades as $unidad) {
            $unit = new Institution();
            $unit->setCountry($country);
            $unit->setCity($city);
            $unit->setParent($dependency);
            $unit->setName(mb_convert_encoding($unidad['Nombre'], 'UTF-8'));
            if ($unidad['Direccion'] != '') {
                $unit->setAddress(mb_convert_encoding($unidad['Direccion'], 'UTF-8'));
            }
            if ($unidad['Hipervinculo1'] != '') {
                $unit->setWebsite($unidad['Hipervinculo1']);
            }
            $unit->setInstance($this->container->get('celsius3_core.instance_manager')->getDirectory());
            $this->dm->persist($unit);

            $this->container->get('celsius3_migration.migration_manager')->createAssociation($unit->getName(), $unidad['Id'], 'unidades', $unit);

            unset($unit, $unidad);
        }
        unset($query, $unidades);
    }
}