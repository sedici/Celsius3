<?php

namespace Celsius3\TicketBundle\Twig;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Router;

class AppExtension extends \Twig_Extension
{
    protected $_em;
    protected $router;
    protected $kernel;

    public function __construct(EntityManager $entityManager, Router $router, Kernel $kernel)
    {
        $this->_em = $entityManager;
        $this->router = $router;
        $this->kernel = $kernel;
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

            case '2':
                $name = 'label-info';
                break;

            case '3':
                $name = 'label-success';
                break;

            case '4':
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
