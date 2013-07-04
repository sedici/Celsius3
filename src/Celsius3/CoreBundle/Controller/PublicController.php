<?php

namespace Celsius3\CoreBundle\Controller;
use Symfony\Component\HttpFoundation\Response;
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
        return $this->get('celsius3_core.instance_helper')->getUrlInstance();
    }

    /**
     * @Route("/", name="public_index")
     * @Template()
     */
    public function indexAction()
    {
        return array('instance' => $this->getInstance(),
                'lastNews' => $this->getDocumentManager()
                        ->getRepository('Celsius3CoreBundle:News')
                        ->findLastNews($this->getInstance()),);

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
        $pagination = $paginator
                ->paginate($this->getInstance()->getNews(),
                        $this->get('request')->query->get('page', 1)/* page number */,
                        $this->container->getParameter('max_per_page')/* limit per page */
                );

        return array('pagination' => $pagination,);
    }

    /**
     * @Route("/statistics", name="public_statistics")
     * @Template()
     */
    public function statisticsAction()
    {
        return array();
    }

    /**
     * @Route("/cities", name="public_cities", options={"expose"=true})
     * @Template()
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function citiesAction()
    {
        $request = $this->container->get('request');

        if (!$request->query->has('country_id')) {
            throw $this->createNotFoundException();
        }

        $cities = $this->getDocumentManager()
                ->getRepository('Celsius3CoreBundle:City')
                ->createQueryBuilder()->field('country.id')
                ->equals($request->query->get('country_id'))->getQuery()
                ->execute();

        $response = array();

        foreach ($cities as $city) {
            $response[] = array('value' => $city->getId(),
                    'name' => $city->getName());
        }

        return new Response(json_encode($response));
    }

    /**
     * @Route("/institutions", name="public_institutions", options={"expose"=true})
     * @Template()
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function institutionsAction()
    {
        $request = $this->container->get('request');

        if (!$request->query->has('country_id')
                && !$request->query->has('city_id')
                && !$request->query->has('institution_id')) {
            throw $this->createNotFoundException();
        }

        $qb = $this->getDocumentManager()
                ->getRepository('Celsius3CoreBundle:Institution')
                ->createQueryBuilder();

        if ($request->query->has('city_id')) {
            $qb = $qb->field('city.id')
                    ->equals($request->query->get('city_id'))
                    ->field('parent.id')->equals(null);
        } else if ($request->query->has('country_id')) {
            $qb = $qb->field('country.id')
                    ->equals($request->query->get('country_id'))
                    ->field('city.id')->equals(null)->field('parent.id')
                    ->equals(null);
        } else {
            $qb = $qb->field('parent.id')
                    ->equals($request->query->get('institution_id'));
        }

        $institutions = $qb->getQuery()->execute();

        $response = array();

        foreach ($institutions as $institution) {
            $response[] = array('value' => $institution->getId(),
                    'name' => $institution->getName(),
                    'hasChildren' => $institution->getInstitutions()->count()
                            > 0,);
        }

        return new Response(json_encode($response));
    }
    
    
    /**
     * @Route("/institutionsFull", name="public_institutions_full", options={"expose"=true})
     * @Template()
     * 
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function institutionsFullAction()
    {
        
        $request = $this->container->get('request');

        if (!$request->query->has('country_id') && !$request->query->has('city_id') && !$request->query->has('institution_id'))
        {
            throw $this->createNotFoundException();
        }

        $qb = $this->getDocumentManager()->getRepository('Celsius3CoreBundle:Institution')
                ->createQueryBuilder();

        if ($request->query->has('city_id'))
        {
            $qb = $qb->field('city.id')->equals($request->query->get('city_id'))
                            ->field('parent.id')->equals(null);
        } else if ($request->query->has('country_id'))
        {
            $qb = $qb->field('country.id')->equals($request->query->get('country_id'))
                            ->field('city.id')->equals(null)
                            ->field('parent.id')->equals(null);
        } else
        {
            $qb = $qb->field('parent.id')->equals($request->query->get('institution_id'));
            
        }

        $institutions = $qb
                ->getQuery()
                ->execute();

        $response = array();
        foreach ($institutions as $institution)
        {
            $level = 0;
            $response [] = array(
                                'value' => $institution->getId(),
                                'hasChildren' => $institution->getInstitutions()->count() > 0,    
                                'name' => $institution->getName(),
                                'level' => $level,
                                'children' => $this->getChildrenInstitution($institution, $level+1),
                                );
        }
        return new Response(json_encode($response));
    }
    
    function getChildrenInstitution($institution, $level)
    {
        if ($institution->getInstitutions()->count() > 0)
        {
            $response= array();
            foreach ($institution->getInstitutions() as $inst)
            {
                array_push($response, array(
                                            'value' => $inst->getId(),
                                            'hasChildren' => $inst->getInstitutions()->count() > 0,                    
                                            'name' => $inst->getName(),
                                            'level' => $level,
                                            'children' =>  $this->getChildrenInstitution($inst, $level+1),       
                                            )
                          );
            }
            return $response;
        }
        else
        {
            return [];
        }
    }
}
