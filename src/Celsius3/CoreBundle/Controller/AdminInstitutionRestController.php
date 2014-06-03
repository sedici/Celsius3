<?php

namespace Celsius3\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;

/**
 * User controller.
 *
 * @Route("/admin/rest/institution")
 */
class AdminInstitutionRestController extends BaseInstanceDependentRestController
{

    /**
     * GET Route annotation.
     * @Get("/parent/{parent_id}", name="admin_rest_institution_parent_get", options={"expose"=true})
     */
    public function getInstitutionByParentAction($parent_id)
    {
        $dm = $this->getDocumentManager();

        $institutions = $dm->getRepository('Celsius3CoreBundle:Institution')
                ->findBy(array(
            'parent.id' => $parent_id,
        ));

        $view = $this->view(array_values($institutions), 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/{country_id}/{city_id}", defaults={"city_id" = null}, name="admin_rest_institution", options={"expose"=true})
     */
    public function getInstitutionsAction($country_id, $city_id, Request $request)
    {
        $dm = $this->getDocumentManager();
        
        $filter = null;
        if ($request->query->has('filter') && $request->query->get('filter') !== '') {
            $filter = $request->query->get('filter');
        }
        
        $hive = $this->getInstance()->getHive();

        $institutions = $dm->getRepository('Celsius3CoreBundle:Institution')
                ->findForInstanceAndGlobal($this->getInstance(), $this->getDirectory(), $hive, $country_id, $city_id, $filter);

        $view = $this->view(array_values($institutions), 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/{id}", name="admin_rest_institution_get", options={"expose"=true})
     */
    public function getInstitutionAction($id)
    {
        $dm = $this->getDocumentManager();

        $institution = $dm->getRepository('Celsius3CoreBundle:Institution')
                ->find($id);

        if (!$institution) {
            return $this->createNotFoundException('Institution not found.');
        }

        $view = $this->view($institution, 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

}
