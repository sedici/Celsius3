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
    protected function getProvider()
    {
        return $this->container->get('fos_message.provider');
    }
    /**
     * @Route("/", name="administration")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        $instance = $this->getInstance();

        $orderCount = $this->getDocumentManager()
                ->getRepository('CelsiusCelsius3Bundle:State')
                ->countOrders($this->getInstance());
        $userCount = $this->getDocumentManager()
                ->getRepository('CelsiusCelsius3Bundle:BaseUser')
                ->countUsers($this->getInstance());

        $arrayResponse = array('instance' => $instance,
                'orderCount' => $orderCount, 'userCount' => $userCount,);

        return $arrayResponse;
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
        $pagination = $paginator
                ->paginate(
                        $this->get('search_manager')
                                ->search('Order', $keyword,
                                        $this->getInstance()),
                        $this->get('request')->query->get('page', 1)/* page number */,
                        $this->container->getParameter('max_per_page')/* limit per page */
                );

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
