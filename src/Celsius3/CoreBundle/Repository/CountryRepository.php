<?php

namespace Celsius3\CoreBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;
use Celsius3\CoreBundle\Document\Instance;

class CountryRepository extends DocumentRepository
{

    public function findForInstanceAndGlobal(Instance $instance, Instance $directory)
    {
        $qb = $this->getDocumentManager()->getRepository('Celsius3CoreBundle:Country')
                ->createQueryBuilder();

        return $qb->addOr($qb->expr()->field('instance.id')->equals($directory->getId()))
                        ->addOr($qb->expr()->field('instance.id')->equals($instance->getId()))
                        ->getQuery()
                        ->execute()
                        ->toArray();
    }

}
