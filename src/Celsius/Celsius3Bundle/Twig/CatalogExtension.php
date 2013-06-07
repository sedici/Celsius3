<?php

namespace Celsius\Celsius3Bundle\Twig;
use Celsius\Celsius3Bundle\Document\Instance;
use Celsius\Celsius3Bundle\Manager\CatalogManager;
use Celsius\Celsius3Bundle\Document\Order;
use Doctrine\Common\Collections\ArrayCollection;
use Celsius\Celsius3Bundle\Document\Catalog;
use Doctrine\ODM\MongoDB\LoggableCursor;

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
                'get_catalogs' => new \Twig_Function_Method($this,
                        'getCatalogs'),
                'get_searches' => new \Twig_Function_Method($this,
                        'getSearches'),
                'search_exists' => new \Twig_Function_Method($this,
                        'searchExists'),);
    }

    public function getCatalogs(Instance $instance)
    {
        return $this->catalog_manager->getAllCatalogs($instance);
    }

    public function getSearches(Order $order, Instance $instance)
    {
        return $this->catalog_manager->getSearches($order, $instance);
    }

    public function searchExists(LoggableCursor $searches, Catalog $catalog)
    {
        $searches = new ArrayCollection($searches->toArray());
        $result = $searches
                ->filter(
                        function ($entry) use ($catalog)
                        {
                            return $entry->getCatalog()->getId()
                                    == $catalog->getId();
                        })->first();
        return false !== $result ? $result : null;
    }

    public function getName()
    {
        return 'catalog_extension';
    }
}
