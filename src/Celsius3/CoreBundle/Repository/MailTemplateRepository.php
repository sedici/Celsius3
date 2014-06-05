<?php

namespace Celsius3\CoreBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;
use Celsius3\CoreBundle\Document\Instance;

class MailTemplateRepository extends DocumentRepository
{

    public function findGlobalAndForInstance(Instance $instance, Instance $directory)
    {
        $custom = array_map(function ($elem) {
            return $elem['code'];
        }, $this->createQueryBuilder()
                        ->hydrate(false)
                        ->select('code')
                        ->field('instance.id')
                        ->equals($instance->getId())->getQuery()
                        ->execute()
                        ->toArray());

        $qb = $this->createQueryBuilder();

        return $qb->addOr($qb->expr()->field('instance.id')->equals($directory->getId())
                                ->field('code')->notIn($custom)
                                ->field('enabled')->equals(true))
                        ->addOr($qb->expr()->field('instance.id')->equals($instance->getId()));
    }

}
