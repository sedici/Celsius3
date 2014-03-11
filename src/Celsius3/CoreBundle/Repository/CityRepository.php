<?php

namespace Celsius3\CoreBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;
use Celsius3\CoreBundle\Document\Instance;

class CityRepository extends DocumentRepository
{

    public function findForInstanceAndGlobal(Instance $instance, Instance $directory, $country_id = null)
    {
        $qb = $this->getDocumentManager()->getRepository('Celsius3CoreBundle:City')
                ->createQueryBuilder();

        $qb = $qb->addOr($qb->expr()->field('instance.id')->equals($directory->getId()))
                ->addOr($qb->expr()->field('instance.id')->equals($instance->getId()));

        if (!is_null($country_id)) {
            $qb = $qb->field('country.id')->equals($country_id);
        }

        return $qb->getQuery()
                        ->execute()
                        ->toArray();
    }

}
