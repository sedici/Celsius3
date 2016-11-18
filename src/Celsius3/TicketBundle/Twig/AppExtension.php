<?php

namespace Celsius3\TicketBundle\Twig;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Router;
use Symfony\Component\Templating\Helper\AssetsHelper;

class AppExtension extends \Twig_Extension
{

    protected $_em;
    protected $router;
    protected $kernel;
//    protected $assetsHelper;
    protected $container;

    public function __construct(Container $container)//EntityManager $entityManager, Router $router, Kernel $kernel, AssetsHelper $assets)
    {
        $this->_em = $container->get('doctrine.orm.default_entity_manager');//$entityManager;
        $this->router = $container->get('router');//$router;
        $this->kernel = $container->get('kernel');//$kernel;
        $this->container = $container;
//        $this->assetsHelper = $container->get('templating.helper.assets');//$assets;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('estilos_estado', [$this, 'getEstiloEstado'], ['is_safe' => ['html']]),
        ];
    }

    public function getEstiloEstado($filter)
    {
        switch ($filter) {
            case "1":
                $name = 'label-danger';
                break;
            case "2":
                $name = 'label-success';
                break;
        }

        return $name;
    }


    public function getName()
    {
        return 'app_extension';
    }
}