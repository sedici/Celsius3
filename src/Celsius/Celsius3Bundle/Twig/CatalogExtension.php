<?php

namespace Celsius\Celsius3Bundle\Twig;
use Celsius\Celsius3Bundle\Document\Instance;
use Celsius\Celsius3Bundle\Manager\CatalogManager;
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
                        'getCatalogs'),);
    }

    public function getCatalogs(Instance $instance)
    {
        return $this->catalog_manager->getAllCatalogs($instance);
    }

    public function getName()
    {
        return 'catalog_extension';
    }
}
