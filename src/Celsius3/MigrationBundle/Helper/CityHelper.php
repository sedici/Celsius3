<?php

namespace Celsius3\MigrationBundle\Helper;
use Celsius3\CoreBundle\Document\City;

class CityHelper
{

    private $dm;
    private $container;
    private $institutionHelper;

    public function __construct($container,
            InstitutionHelper $institutionHelper)
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
            $city
                    ->setName(
                            mb_convert_encoding($row_localidad['Nombre'],
                                    'UTF-8'));

            $this->dm->persist($city);

            $this->container->get('celsius3_migration.migration_manager')
                    ->createAssociation($city->getName(), $row_localidad['Id'],
                            'localidades', $city);

            $this->institutionHelper
                    ->migrate($conn, $country_id, $country,
                            $row_localidad['Id'], $city);
        }
    }

}
