<?php

namespace Celsius\Celsius3Bundle\Repository;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Doctrine\ODM\MongoDB\Query\Builder;
use Celsius\Celsius3Bundle\Document\Instance;
use Celsius\Celsius3Bundle\Manager\StateManager;

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

    public function findByTerm($term, Instance $instance = null, $in = array(),
            $limit = null)
    {
        $qb = $this->createQueryBuilder();

        if (!empty($in)) {
            $secondary = array();
            foreach ($in as $repository => $term) {
                $secondary = array_keys(
                        $this->getDocumentManager()
                                ->getRepository(
                                        'CelsiusCelsius3Bundle:' . $repository)
                                ->findByTerm($term, $instance)->execute()
                                ->toArray());
            }

            $qb = $qb->field('owner.id')->in($secondary);
        } else {
            $expr = new \MongoRegex('/.*' . $term . '.*/i');
            $qb = $qb->addOr($qb->expr()->field('code')->equals(intval($term)))
                    ->addOr(
                            $qb->expr()->field('materialData.title')
                                    ->equals($expr))
                    ->addOr(
                            $qb->expr()->field('materialData.authors')
                                    ->equals($expr))
                    ->addOr(
                            $qb->expr()->field('materialData.year')
                                    ->equals($expr));
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
                ->findOneBy(array('name' => StateManager::STATE__CREATED));

        $order_ids = array_map(array($this, 'getIds'),
                $this->getDocumentManager()
                        ->getRepository('CelsiusCelsius3Bundle:State')
                        ->createQueryBuilder()->hydrate(false)->select('order')
                        ->field('type.id')->equals($stateType->getId())
                        ->field('instance.id')->equals($instance->getId())
                        ->getQuery()->execute()->toArray());

        return $this->createQueryBuilder()->field('id')->in($order_ids);
    }

    public function findOneForInstance($id, Instance $instance)
    {
        $stateType = $this->getDocumentManager()
                ->getRepository('CelsiusCelsius3Bundle:StateType')
                ->findOneBy(array('name' => StateManager::STATE__CREATED));

        $order_id = $this->getDocumentManager()
                ->getRepository('CelsiusCelsius3Bundle:State')
                ->createQueryBuilder()->hydrate(false)->select('order')
                ->field('order.id')->equals($id)->field('type.id')
                ->equals($stateType->getId())->field('instance.id')
                ->equals($instance->getId())->getQuery()->getSingleResult();

        return $this->createQueryBuilder()->field('id')
                ->equals($order_id['order']['$id']);
    }

    public function addFindByStateType(array $types, Builder $query,
            Instance $instance = null)
    {
        $stateTypes = array_keys(
                $this->getDocumentManager()
                        ->getRepository('CelsiusCelsius3Bundle:StateType')
                        ->createQueryBuilder()->hydrate(false)->select('id')
                        ->field('name')->in($types)->getQuery()->execute()
                        ->toArray());

        $states = $this->getDocumentManager()
                ->getRepository('CelsiusCelsius3Bundle:State')
                ->createQueryBuilder()->hydrate(false)->select('order')
                ->field('isCurrent')->equals(true)->field('type.id')
                ->in($stateTypes);

        if (!is_null($instance)) {
            $states = $states->field('instance.id')->equals($instance->getId());
        }

        return $query->field('id')
                ->in(
                        array_map(array($this, 'getIds'),
                                $states->getQuery()->execute()->toArray()));
    }

}
