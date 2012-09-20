<?php

namespace Celsius\Celsius3Bundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * NewsRss controller.
 *
 * @Route("/newsFeeds")
 */
class NewsFeedsController extends BaseInstanceDependentController
{
    
    protected function getInstance()
    {
        $instance = $this->getDocumentManager()
                ->getRepository('CelsiusCelsius3Bundle:Instance')
                ->findOneBy(array('url' => $this->getRequest()->attributes->get('urlInstance')));

        if (!$instance)
        {
            throw $this->createNotFoundException('Unable to find Instance.');
        }

        return $instance;
    }

    
    /**
     * Generate Rss News.
     *
     * @Route("/rss/directory",name="directory_rss_news")
     * @Template()
     *
     */
    
    public function directory_rssAction()
    {
        $array = array(
            'instance' => 'Directory',
            'lastNews' => $this->getDocumentManager()->getRepository('CelsiusCelsius3Bundle:News')->findLastNewsDirectory(),
            'url' => 'http://www.celsius3.com.localhost/app_dev.php/es/newsFeeds/rss/directory',
        );
       return $this->render('CelsiusCelsius3Bundle:NewsFeeds:index_rss.html.twig', $array);
    }
    
    /**
     * Generate Atom News.
     *
     * @Route("/atom/directory",name="directory_atom_news")
     * @Template()
     *
     */
    
    public function directory_atomAction()
    {
        $array = array(
            'instance' => 'Directory',
            'lastNews' => $this->getDocumentManager()->getRepository('CelsiusCelsius3Bundle:News')->findLastNewsDirectory(),
            'url' => 'http://www.celsius3.com.localhost/app_dev.php/es/newsFeeds/atom/directory/',
        );
        return $this->render('CelsiusCelsius3Bundle:NewsFeeds:index_atom.html.twig', $array);
    }
    
     /**
     * Generate Rss News.
     *
     * @Route("/rss/{urlInstance}",name="instance_rss_news")
     * @Template()
     *
     */
    
    public function instance_rssAction($urlInstance)
    {
        
        $array = array(
            'instance' => $this->getInstance(),
            'lastNews' => $this->getDocumentManager()->getRepository('CelsiusCelsius3Bundle:News')->findLastNews($this->getInstance()),
            'url' => 'http://www.celsius3.com.localhost/app_dev.php/es/newsFeeds/rss/'.$urlInstance,
        );
        return $this->render('CelsiusCelsius3Bundle:NewsFeeds:index_rss.html.twig', $array);
        
    }
        
    /**
     * Generate atom News.
     *
     * @Route("/atom/{urlInstance}", name="instance_atom_news")
     * @Template()
     *
     */
    public function instance_atomAction($urlInstance)
    {
        $array = array(
            'instance' => $this->getInstance(),
            'lastNews' => $this->getDocumentManager()->getRepository('CelsiusCelsius3Bundle:News')->findLastNews($this->getInstance()),
            'url' => 'http://www.celsius3.com.localhost/app_dev.php/es/newsFeeds/atom/'.$urlInstance,
        );
        return $this->render('CelsiusCelsius3Bundle:NewsFeeds:index_atom.html.twig', $array);
        
    }
    
    
}
