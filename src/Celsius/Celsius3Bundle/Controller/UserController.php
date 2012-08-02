<?php

namespace Celsius\Celsius3Bundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * User controller
 * 
 * @Route("/user") 
 */
class UserController extends BaseInstanceDependentController
{
    /**
     * @Route("/", name="user_index")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/ajax", name="user_ajax")
     */
    public function ajaxAction()
    {
        if (!$this->getRequest()->isXmlHttpRequest())
            return $this->createNotFoundException();

        $target = $this->getRequest()->query->get('target');
        $term = $this->getRequest()->query->get('term');

        $result = $this->getDocumentManager()
                ->getRepository('CelsiusCelsius3Bundle:' . $target)
                ->findByTerm($term, $this->getInstance())
                ->execute();

        $json = array();
        foreach ($result as $element)
        {
            $json[] = array(
                'id' => $element->getId(),
                'value' => $element->__toString(),
            );
        }

        $response = new Response(json_encode($json));
        $response->headers->set('Content-Type', 'application/json');
        
        return $response;
    }

}
