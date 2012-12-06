<?php

namespace Celsius\Celsius3Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Public controller
 * 
 * @Route("/{url}/public") 
 */
class PublicController extends BaseInstanceDependentController
{

    protected function getInstance()
    {
        $instance = $this->getDocumentManager()
                ->getRepository('CelsiusCelsius3Bundle:Instance')
                ->findOneBy(array('url' => $this->getRequest()->attributes->get('url')));

        if (!$instance)
        {
            throw $this->createNotFoundException('Unable to find Instance.');
        }

        return $instance;
    }

    /**
     * @Route("/", name="public_index")
     * @Template()
     */
    public function indexAction()
    {
        return array(
            'instance' => $this->getInstance(),
            'lastNews' => $this->getDocumentManager()->getRepository('CelsiusCelsius3Bundle:News')->findLastNews($this->getInstance()),
        );
    }

    /**
     * @Route("/information", name="public_information")
     * @Template()
     */
    public function informationAction()
    {
        return array();
    }

    /**
     * @Route("/news", name="public_news")
     * @Template()
     */
    public function newsAction()
    {
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $this->getInstance()->getNews(), $this->get('request')->query->get('page', 1)/* page number */, $this->container->getParameter('max_per_page')/* limit per page */
        );

        return array(
            'pagination' => $pagination,
        );
    }

    /**
     * @Route("/statistics", name="public_statistics")
     * @Template()
     */
    public function statisticsAction()
    {
        return array();
    }
    
}
