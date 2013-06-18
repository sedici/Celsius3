<?php

namespace Celsius3\MigrationBundle\Manager;
use Doctrine\ODM\MongoDB\DocumentManager;
use Celsius3\MigrationBundle\Helper\CountryHelper;
use Celsius3\MigrationBundle\Document\Association;

class MigrationManager
{

    private $countryHelper;
    private $dm;

    public function __construct(CountryHelper $countryHelper,
            DocumentManager $dm)
    {
        $this->countryHelper = $countryHelper;
        $this->dm = $dm;
    }

    public function migrate($host, $username, $password, $database, $port)
    {
        set_time_limit(0);

        $conn = mysqli_connect($host, $username, $password, $database, $port);

        $this->countryHelper->migrate($conn);
    }

    public function createAssociation($name, $original_id, $table, $document)
    {
        $association = new Association();
        $association->setName($name);
        $association->setOriginalId($original_id);
        $association->setTable($table);
        $association->setDocument($document);

        $this->dm->persist($association);
    }

}
