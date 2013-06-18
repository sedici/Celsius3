<?php

namespace Celsius3\MigrationBundle\Helper;
use Celsius3\CoreBundle\Document\Institution;

class InstitutionHelper
{

    private $dm;
    private $container;

    public function __construct($container)
    {
        $this->dm = $container->get('doctrine.odm.mongodb.document_manager');
        $this->container = $container;
    }

    public function migrate($conn, $country_id, $country, $city_id,
            $city = null)
    {
        $query_instituciones = 'SELECT * FROM instituciones WHERE Codigo_Localidad = '
                . $city_id . ' AND Codigo_Pais = ' . $country_id;
        $result_instituciones = mysqli_query($conn, $query_instituciones);

        while ($row_institucion = mysqli_fetch_assoc($result_instituciones)) {
            $institution = new Institution();
            $institution->setCountry($country);
            $institution->setCity($city);
            $institution
                    ->setName(
                            mb_convert_encoding($row_institucion['Nombre'],
                                    'UTF-8'));
            $institution
                    ->setAbbreviation(
                            mb_convert_encoding(
                                    $row_institucion['Abreviatura'], 'UTF-8'));
            if ($row_institucion['Direccion'] != '') {
                $institution
                        ->setAddress(
                                mb_convert_encoding(
                                        $row_institucion['Direccion'], 'UTF-8'));
            }
            if ($row_institucion['Sitio_Web'] != '') {
                $institution->setWebsite($row_institucion['Sitio_Web']);
            }

            $this->dm->persist($institution);

            $this->container->get('celsius3_migration.migration_manager')
                    ->createAssociation($institution->getName(),
                            $row_institucion['Codigo'], 'instituciones',
                            $institution);

            $this
                    ->migrateDependencies($conn, $row_institucion['Codigo'],
                            $institution, $country, $city);
        }
    }

    private function migrateDependencies($conn, $institution_id, $institution,
            $country, $city)
    {
        $query_dependencias = 'SELECT * FROM dependencias WHERE Codigo_Institucion = '
                . $institution_id;
        $result_dependencias = mysqli_query($conn, $query_dependencias);

        while ($row_dependencia = mysqli_fetch_assoc($result_dependencias)) {
            $dependency = new Institution();
            $dependency->setCountry($country);
            $dependency->setCity($city);
            $dependency->setParent($institution);
            $dependency
                    ->setName(
                            mb_convert_encoding($row_dependencia['Nombre'],
                                    'UTF-8'));
            $dependency
                    ->setAbbreviation(
                            mb_convert_encoding(
                                    $row_dependencia['Abreviatura'], 'UTF-8'));
            if ($row_dependencia['Direccion'] != '') {
                $dependency
                        ->setAddress(
                                mb_convert_encoding(
                                        $row_dependencia['Direccion'], 'UTF-8'));
            }
            if ($row_dependencia['Hipervinculo1'] != '') {
                $dependency->setWebsite($row_dependencia['Hipervinculo1']);
            }

            $this->dm->persist($dependency);

            $this->container->get('celsius3_migration.migration_manager')
                    ->createAssociation($dependency->getName(),
                            $row_dependencia['Id'], 'dependencias',
                            $dependency);

            $this
                    ->migrateUnits($conn, $row_dependencia['Id'], $dependency,
                            $country, $city);
        }
    }

    private function migrateUnits($conn, $dependency_id, $dependency, $country,
            $city)
    {
        $query_unidades = 'SELECT * FROM unidades WHERE Codigo_Dependencia = '
                . $dependency_id;
        $result_unidades = mysqli_query($conn, $query_unidades);

        while ($row_unidad = mysqli_fetch_assoc($result_unidades)) {
            $unit = new Institution();
            $unit->setCountry($country);
            $unit->setCity($city);
            $unit->setParent($dependency);
            $unit->setName(mb_convert_encoding($row_unidad['Nombre'], 'UTF-8'));
            if ($row_unidad['Direccion'] != '') {
                $unit
                        ->setAddress(
                                mb_convert_encoding($row_unidad['Direccion'],
                                        'UTF-8'));
            }
            if ($row_unidad['Hipervinculo1'] != '') {
                $unit->setWebsite($row_unidad['Hipervinculo1']);
            }

            $this->dm->persist($unit);

            $this->container->get('celsius3_migration.migration_manager')
                    ->createAssociation($unit->getName(), $row_unidad['Id'],
                            'unidades', $unit);
        }
    }

}
