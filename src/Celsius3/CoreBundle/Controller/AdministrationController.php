<?php

namespace Celsius3\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Celsius3\CoreBundle\Manager\SearchManager;

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
        $orderCount = $this->getDocumentManager()
                ->getRepository('Celsius3CoreBundle:State')
                ->countOrders($this->getInstance());
        $userCount = $this->getDocumentManager()
                ->getRepository('Celsius3CoreBundle:BaseUser')
                ->countUsers($this->getInstance());

        $query = $this->getDocumentManager()
                ->getRepository('Celsius3CoreBundle:Order')
                ->findForInstance($this->getInstance());

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $this->get('request')->query->get('page', 1)/* page number */, $this->getResultsPerPage()/* limit per page */);

        return array(
            'pagination' => $pagination,
            'orderCount' => $orderCount,
            'userCount' => $userCount,
        );
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

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($this->get('celsius3_core.search_manager')->search('Order', $keyword, $this->getInstance()), $this->get('request')->query->get('page', 1)/* page number */, $this->container->getParameter('max_per_page')/* limit per page */);

        return array('keyword' => $keyword, 'pagination' => $pagination,);
    }

    /**
     * @Route("/ajax", name="admin_ajax")
     */
    public function ajaxAction()
    {
        return $this->ajax($this->getInstance());
    }

}
