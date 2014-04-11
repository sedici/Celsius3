<?php

namespace Celsius3\CoreBundle\Manager;

use Celsius3\CoreBundle\Document\BaseUser;
use Celsius3\CoreBundle\Document\Instance;
use Celsius3\CoreBundle\Document\Request;
use Celsius3\CoreBundle\Document\Catalog;
use Celsius3\CoreBundle\Document\CatalogSearch;
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

    public function getSearches(Request $request, $result = null)
    {
        $qb = $this->dm->getRepository('Celsius3CoreBundle:CatalogSearch')
                        ->createQueryBuilder()
                        ->field('request.id')->equals($request->getId());

        if ($result) {
            $qb = $qb->field('result')->equals($result);
        }

        return $qb->getQuery()->execute();
    }

    public function createSearch(Catalog $catalog, Request $request, BaseUser $user, $result)
    {
        $document = $this->dm->getRepository('Celsius3CoreBundle:CatalogSearch')
                ->findOneBy(array(
            'catalog.id' => $catalog->getId(),
            'request.id' => $request->getId(),
        ));
        
        if (!$document) {
            $document = new CatalogSearch();
            $document->setCatalog($catalog);
            $document->setRequest($request);
        }

        $document->setOperator($user);
        $document->setDate(date('Y-m-d H:i:s'));
        $document->setResult($result);

        $this->dm->persist($document);
        $this->dm->flush();

        $this->dm->refresh($document);
        
        return $document;
    }

}
