<?php

namespace Celsius3\CoreBundle\Manager;

use Celsius3\CoreBundle\Document\Instance;
use Celsius3\CoreBundle\Manager\InstanceManager;
use Doctrine\ODM\MongoDB\DocumentManager;

class CatalogManager
{

    const CATALOG__NOT_SEARCHED = 'not_searched';
    const CATALOG__FOUND = 'found';
    const CATALOG__PARTIALLY_FOUND = 'partially_found';
    const CATALOG__NOT_FOUND = 'not_found';

    private $dm;
    private $instance_manager;

    public function __construct(DocumentManager $dm, InstanceManager $instance_manager)
    {
        $this->dm = $dm;
        $this->instance_manager = $instance_manager;
    }

    public static function getResults()
    {
        return array(
            self::CATALOG__FOUND,
            self::CATALOG__PARTIALLY_FOUND,
            self::CATALOG__NOT_FOUND,
            self::CATALOG__NOT_SEARCHED,
        );
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

        return $qb->addOr($qb->expr()->field('instance.id')->equals($instance->getId()))
                        ->addOr($qb->expr()->field('instance.id')->equals($this->instance_manager->getDirectory()->getId()))
                        ->getQuery()->execute();
    }

}
