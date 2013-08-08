<?php

namespace Celsius3\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Directory controller
 *
 * @Route("")
 */
class DirectoryController extends BaseController
{

    private function getDirectory()
    {
        return $this->get('celsius3_core.instance_manager')->getDirectory();
    }

    /**
     * @Route("/directory/", name="directory")
     * @Route("/", name="index")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return array(
            'directory' => $this->getDirectory(),
            'lastNews' => $this->getDocumentManager()
                    ->getRepository('Celsius3CoreBundle:News')
                    ->findLastNews($this->getDirectory()),
        );
    }

    /**
     * @Route("/directory/instances", name="directory_instances")
     * @Template()
     *
     * @return array
     */
    public function instancesAction()
    {
        $instances = $this->getDocumentManager()
                        ->getRepository('Celsius3CoreBundle:Instance')
                        ->createQueryBuilder()
                        ->field('enabled')->equals(true)
                        ->getQuery()->execute();

        return array(
            'directory' => $this->getDirectory(),
            'instances' => $instances,
        );
    }

}
