<?php

namespace Celsius\Celsius3Bundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;
use Celsius\Celsius3Bundle\Document\Instance;

/**
 * OrderRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OrderRepository extends DocumentRepository
{

    protected function getIds($value)
    {
        return $value['order']['$id'];
    }

    public function findByTerm($term, Instance $instance = null, $in = array(), $limit = null)
    {
        $qb = $this->createQueryBuilder();

        if (!empty($in))
        {
            $secondary = array();
            foreach ($in as $repository => $term)
            {
                $secondary = array_keys($this->getDocumentManager()
                                ->getRepository('CelsiusCelsius3Bundle:' . $repository)
                                ->findByTerm($term, $instance)
                                ->execute()
                                ->toArray());
            }

            $qb = $qb->field('owner.id')->in($secondary);
        } else
        {

            $expr = new \MongoRegex('/.*' . $term . '.*/i');

            $qb = $qb->addOr($qb->expr()->field('comments')->equals($expr))
                    ->addOr($qb->expr()->field('code')->equals(intval($term)))
                    ->addOr($qb->expr()->field('materialData.title')->equals($expr))
                    ->addOr($qb->expr()->field('materialData.authors')->equals($expr))
                    ->addOr($qb->expr()->field('materialData.year')->equals($expr));
        }

        if (!is_null($instance))
            $qb = $qb->field('instance.id')->equals($instance->getId());

        if (!is_null($limit))
            $qb = $qb->limit(10);

        return $qb->getQuery();
    }

    public function findForInstance(Instance $instance)
    {
        $stateType = $this->getDocumentManager()
                ->getRepository('CelsiusCelsius3Bundle:StateType')
                ->createQueryBuilder()
                ->field('name')->equals('created')
                ->getQuery()
                ->getSingleResult();

        $order_ids = array_map(array($this, 'getIds'), $this->getDocumentManager()
                        ->getRepository('CelsiusCelsius3Bundle:State')
                        ->createQueryBuilder()
                        ->hydrate(false)
                        ->select('order')
                        ->field('type.id')->equals($stateType->getId())
                        ->field('instance.id')->equals($instance->getId())
                        ->getQuery()
                        ->execute()
                        ->toArray());

        return $this->createQueryBuilder()
                        ->field('id')->in($order_ids);
    }
    
    public function findOneForInstance($id, Instance $instance)
    {
        $stateType = $this->getDocumentManager()
                ->getRepository('CelsiusCelsius3Bundle:StateType')
                ->createQueryBuilder()
                ->field('name')->equals('created')
                ->getQuery()
                ->getSingleResult();
        
        $order_id = $this->getDocumentManager()
                        ->getRepository('CelsiusCelsius3Bundle:State')
                        ->createQueryBuilder()
                        ->hydrate(false)
                        ->select('order')
                        ->field('order.id')->equals($id)
                        ->field('type.id')->equals($stateType->getId())
                        ->field('instance.id')->equals($instance->getId())
                        ->getQuery()
                        ->getSingleResult();
        
        return $this->createQueryBuilder()
                        ->field('id')->equals($order_id['order']['$id']);
    }

}