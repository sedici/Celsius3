<?php

namespace Celsius3\CoreBundle\Twig;

use Celsius3\CoreBundle\Document\Instance;
use Celsius3\CoreBundle\Manager\CatalogManager;
use Celsius3\CoreBundle\Document\Request;
use Doctrine\Common\Collections\ArrayCollection;
use Celsius3\CoreBundle\Document\Catalog;
use Doctrine\ODM\MongoDB\Cursor;

class CatalogExtension extends \Twig_Extension
{

    private $catalog_manager;

    public function __construct(CatalogManager $catalog_manager)
    {
        $this->catalog_manager = $catalog_manager;
    }

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    public function getFunctions()
    {
        return array(
            'get_catalogs' => new \Twig_Function_Method($this, 'getCatalogs'),
            'get_searches' => new \Twig_Function_Method($this, 'getSearches'),
            'search_exists' => new \Twig_Function_Method($this, 'searchExists'),
        );
    }

    public function getCatalogs(Instance $instance)
    {
        return $this->catalog_manager->getAllCatalogs($instance);
    }

    public function getSearches(Request $request, $result = null)
    {
        return $this->catalog_manager->getSearches($request, $result);
    }

    public function searchExists(Cursor $searches, Catalog $catalog)
    {
        $searches = new ArrayCollection($searches->toArray());
        $result = $searches->filter(function ($entry) use ($catalog) {
                            return $entry->getCatalog()->getId() == $catalog->getId();
                        })->first();
        return false !== $result ? $result : null;
    }

    public function getName()
    {
        return 'celsius3_core.catalog_extension';
    }

}
