<?php

namespace Celsius\Celsius3Bundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Celsius\Celsius3Bundle\Manager\SearchManager;

/**
 * Administration controller
 * 
 * @Route("/admin") 
 */
class AdministrationController extends BaseInstanceDependentController
{

    /**
     * @Route("/", name="administration")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        $instance = $this->getInstance();

        return array('instance' => $instance);
    }

    /**
     * @Route("/search", name="administration_search")
     * @Template()
     *
     * @return array
     */
    public function searchAction()
    {
        $keyword = $this->getRequest()->query->get('keyword');
        $searchManager = new SearchManager();
        
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $searchManager->doSearch('Order', $keyword, $this->getDocumentManager(), $this->getInstance()),
                $this->get('request')->query->get('page', 1)/* page number */,
                $this->container->getParameter('max_per_page')/* limit per page */
        );

        return array(
            'keyword' => $keyword,
            'pagination' => $pagination,
        );
    }

}