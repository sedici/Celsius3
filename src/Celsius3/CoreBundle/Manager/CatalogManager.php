<?php

namespace Celsius3\CoreBundle\Manager;
use Doctrine\ODM\MongoDB\DocumentManager;
use Celsius3\CoreBundle\Document\Instance;
use Celsius3\CoreBundle\Document\Order;

class CatalogManager
{
    const CATALOG__NOT_SEARCHED = 'not_searched';
    const CATALOG__FOUND = 'found';
    const CATALOG__PARTIALLY_FOUND = 'partially_found';
    const CATALOG__NOT_FOUND = 'not_found';

    private $dm;

    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
    }

    public function getCatalogs(Instance $instance = null)
    {
        return $this->dm->getRepository('Celsius3CoreBundle:Catalog')
                ->findBy(array('instance.id', $instance));
    }

    public function getAllCatalogs(Instance $instance)
    {
        $qb = $this->dm->getRepository('Celsius3CoreBundle:Catalog')
                ->createQueryBuilder();

        return $qb->addOr($qb->expr()->field('instance.id')->equals($instance))
                ->addOr($qb->expr()->field('instance.id')->equals(null))
                ->getQuery()->execute();
    }

    public function getSearches(Order $order, Instance $instance)
    {
        return $this->dm->getRepository('Celsius3CoreBundle:CatalogSearch')
                ->findBy(
                        array('order.id' => $order->getId(),
                                'instance.id' => $instance->getId()));
    }
}
