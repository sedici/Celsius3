<?php

namespace Celsius3\CoreBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;
use Celsius3\CoreBundle\Document\Instance;
use Celsius3\CoreBundle\Document\Hive;

class InstitutionRepository extends DocumentRepository
{

    public function findForInstanceAndGlobal(Instance $instance, Instance $directory, Hive $hive, $country_id, $city_id = null, $filter = null)
    {
        $qb = $this->getDocumentManager()->getRepository('Celsius3CoreBundle:Institution')
                ->createQueryBuilder();

        $qb = $qb->addOr($qb->expr()->field('instance.id')->equals($directory->getId()))
                        ->addOr($qb->expr()->field('instance.id')->equals($instance->getId()))
                        ->field('country.id')->equals($country_id)
                        ->field('parent.id')->equals(null);

        if (!is_null($city_id)) {
            $qb = $qb->field('city.id')->equals($city_id);
        }

        if (!is_null($filter)) {
            if ($filter === 'hive') {
                $qb = $qb->field('hive.id')->equals($hive->getId());
            } elseif ($filter === 'celsius3') {
                $qb = $qb->field('celsiusInstance.id')->notEqual(null);
            }
        }

        return $qb->getQuery()
                        ->execute()
                        ->toArray();
    }

}
