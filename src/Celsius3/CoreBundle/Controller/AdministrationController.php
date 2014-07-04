<?php

namespace Celsius3\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

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

        $query = $this->getDocumentManager()
                ->getRepository('Celsius3CoreBundle:Order')
                ->findForInstance($this->getInstance());

        $pendingUsers = $this->getDocumentManager()
                ->getRepository('Celsius3CoreBundle:BaseUser')
                ->findPendingUsers($this->getInstance());

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $this->get('request')->query->get('page', 1)/* page number */, $this->getResultsPerPage()/* limit per page */);

        return array(
            'pagination' => $pagination,
            'users' => $pendingUsers,
            'orderCount' => $orderCount,
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

    /**
     * @Route("/{id}/change", name="administration_change_context")
     */
    public function changeContextAction($id)
    {
        $dm = $this->getDocumentManager();
        $instance = $dm->getRepository('Celsius3CoreBundle:Instance')
                ->find($id);

        $user = $this->getUser();
        if (!$user->getAdministeredInstances()->contains($user->getInstance())) {
            $user->getAdministeredInstances()->add($user->getInstance());
        }

        if (!$instance || !$user->getAdministeredInstances()->contains($instance)) {
            return $this->createNotFoundException('Instance not found');
        }

        $this->get('session')->set('instance_id', $instance->getId());
        $this->get('session')->set('instance_url', $instance->getUrl());

        return $this->redirect($this->generateUrl('administration'));
    }

}
