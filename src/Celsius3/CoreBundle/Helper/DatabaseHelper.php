<?php

namespace Celsius3\CoreBundle\Helper;

use Doctrine\ODM\MongoDB\DocumentManager;

class DatabaseHelper
{

    private $dm;

    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
    }

    public function findRandomRecord($repository)
    {
        $rand = rand(0, $this->dm->getRepository($repository)
                        ->createQueryBuilder()->getQuery()->count() - 1);

        return $this->dm->getRepository($repository)
                        ->createQueryBuilder()
                        ->limit(-1)
                        ->skip($rand)
                        ->getQuery()
                        ->getSingleResult();
    }

}
