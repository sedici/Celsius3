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
        return array(
            'instance' => $this->getInstance(),
            'lastNews' => $this->getDocumentManager()
                    ->getRepository('Celsius3CoreBundle:News')
                    ->findLastNews($this->getInstance()),
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
        $pagination = $paginator
                ->paginate($this->getInstance()->getNews(), $this->get('request')->query->get('page', 1)/* page number */, $this->container->getParameter('max_per_page')/* limit per page */
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
     * @Route("/institutionsFull", name="public_institutions_full", options={"expose"=true})
     * @Template()
     * 
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function institutionsFullAction()
    {
        $request = $this->container->get('request');

        if (!$request->query->has('country_id') && !$request->query->has('city_id') && !$request->query->has('institution_id')) {
            throw $this->createNotFoundException();
        }

        $dm = $this->getDocumentManager();
        $qb = $dm->getRepository('Celsius3CoreBundle:Institution')
                ->createQueryBuilder()
                ->hydrate(false);
        
        if ($request->query->has('city_id')) {
            $qb = $qb->field('city.id')->equals($request->query->get('city_id'));
        } else if ($request->query->has('country_id')) {
            $qb = $qb->field('country.id')->equals($request->query->get('country_id'))
                            ->field('city.id')->equals(null);
        }

        if ($request->query->get('filter') == '') {
            $qb = $qb->field('parent.id')->equals(null);
        }
        
        $qb->getQuery()->execute();

        $institutions = $qb->getQuery()->execute()->toArray();

        $response = array();
        foreach ($institutions as $institution) {
            $level = 0;
            if (($request->query->get('filter') == 'liblink' && $institution['isLibLink']) ||
                    ($request->query->get('filter') == 'celsius3' && $institution['celsiusInstance']) ||
                    ($request->query->get('filter') == '')) {

                $children = $dm->getRepository('Celsius3CoreBundle:Institution')
                        ->createQueryBuilder()
                        ->hydrate(false)
                        ->field('parent.id')->equals($institution['_id'])
                        ->getQuery()
                        ->execute()
                        ->toArray();

                $response[] = array(
                    'value' => $institution['_id'],
                    'hasChildren' => count($children) > 0,
                    'name' => $institution['name'],
                    'level' => $level,
                    'children' => $this->getChildrenInstitution($children, $level + 1),
                );
            }
        }
        return new Response(json_encode($response));
    }

    function getChildrenInstitution($institutions, $level)
    {
        $dm = $this->getDocumentManager();
        $response = array();
        if (count($institutions) > 0) {
            foreach ($institutions as $institution) {
                $children = $dm->getRepository('Celsius3CoreBundle:Institution')
                        ->createQueryBuilder()
                        ->hydrate(false)
                        ->field('parent.id')->equals($institution['_id'])
                        ->getQuery()
                        ->execute()
                        ->toArray();

                $response[] = array(
                    'value' => $institution['_id'],
                    'hasChildren' => count($children) > 0,
                    'name' => $institution['name'],
                    'level' => $level,
                    'children' => $this->getChildrenInstitution($children, $level + 1),
                );
            }
        }
        return $response;
    }

}
