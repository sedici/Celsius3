<?php

namespace Celsius3\TicketBundle\Twig;

use Symfony\Component\DependencyInjection\Container;

class AppExtension extends \Twig_Extension
{
    protected $_em;
    protected $router;
    protected $kernel;
    protected $container;

    public function __construct(Container $container)
    {
        $this->_em = $container->get('doctrine.orm.default_entity_manager');
        $this->router = $container->get('router');
        $this->kernel = $container->get('kernel');
        $this->container = $container;
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
            case '1':
                $name = 'label-danger';
                break;

            case "2":
                $name = 'label-info';
                break;

            case '3':
                $name = 'label-success';
                break;

            case "4":
                $name = 'label-warning';
                break;

            default:
                $name = '';
        }

        return $name;
    }

    public function getName()
    {
        return 'app_extension';
    }
}
