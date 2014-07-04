<?php

namespace Celsius3\CoreBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;
use Celsius3\CoreBundle\Document\Instance;

class CatalogRepository extends DocumentRepository
{

    public function findForInstanceAndGlobal(Instance $instance, Instance $directory)
    {
        $qb = $this->getDocumentManager()->getRepository('Celsius3CoreBundle:CatalogPosition')
                ->createQueryBuilder()
                ->hydrate(false)
                ->select('catalog');

        $positions = array_map(function ($item) {
            return $item['catalog']['$id'];
        }, $qb->addOr($qb->expr()->field('instance.id')->equals($directory->getId()))
                        ->addOr($qb->expr()->field('instance.id')->equals($instance->getId()))
                        ->sort('position', 'asc')
                        ->getQuery()
                        ->execute()
                        ->toArray());

        $qb2 = $this->createQueryBuilder();

        foreach ($positions as $position) {
            $qb2 = $qb2->addOr($qb2->expr()->field('id')->equals($position));
        }

        return $qb2;
    }

}
