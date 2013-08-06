<?php

namespace Celsius3\CoreBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;
use Celsius3\CoreBundle\Document\Instance;
use Celsius3\CoreBundle\Manager\StateManager;

class StateRepository extends DocumentRepository
{

    public function countOrders(Instance $instance = null)
    {
        $types = $this->dm->getRepository('Celsius3CoreBundle:StateType')
                        ->createQueryBuilder()->hydrate(false)->select('id', 'name')
                        ->getQuery()->execute()->toArray();

        $qb = $this->createQueryBuilder()
                ->field('isCurrent')->equals(true)
                ->map('function() { emit(this.type.$id, 1); }')
                ->reduce(
                'function(k, vals) {
                    var sum = 0;
                    for (var i in vals) {
                        sum += vals[i];
                    }
                    return sum;
                }');

        if (!is_null($instance))
            $qb = $qb->field('instance.id')->equals($instance->getId());

        $qb = $qb->getQuery()->execute()->toArray();

        $result = array();
        foreach ($types as $type) {
            $result[$type['name']] = 0;
            foreach ($qb as $count) {
                if ($count['_id'] == $type['_id']) {
                    $result[$type['name']] = $count['value'];
                }
            }
        }

        return $result;
    }

    public function findOrdersPerStatePerInstance($state = null)
    {
        if (!is_null($state)) {
            $state = $this->getDocumentManager()
                    ->getRepository('Celsius3CoreBundle:StateType')
                    ->findOneBy(array('name' => $state));
        }

        return $this->createQueryBuilder()
                        ->field('type.$id')->equals($state ? $state->getId() : null)
                        ->field('isCurrent')->equals(true)
                        ->map('function() { emit(this.instance.$id, 1); }')
                        ->reduce('function(k, vals) {
                            var sum = 0;
                            for (var i in vals) {
                                sum += vals[i];
                            }
                            return sum;
                        }')
                        ->getQuery()
                        ->execute();
    }

    public function findTotalOrdersPerInstance()
    {
        $state = $this->getDocumentManager()
                ->getRepository('Celsius3CoreBundle:StateType')
                ->findOneBy(array('name' => StateManager::STATE__CREATED));

        return $this->createQueryBuilder()
                        ->field('type.$id')->equals($state ? $state->getId() : null)
                        ->field('isCurrent')->equals(true)
                        ->map('function() { emit(this.instance.$id, 1); }')
                        ->reduce('function(k, vals) {
                            var sum = 0;
                            for (var i in vals) {
                                sum += vals[i];
                            }
                            return sum;
                        }')
                        ->getQuery()
                        ->execute();
    }

}
