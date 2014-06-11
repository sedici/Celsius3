<?php

namespace Celsius3\CoreBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Post;

/**
 * User controller.
 *
 * @Route("/admin/rest/file")
 */
class AdminFileRestController extends BaseInstanceDependentRestController
{

    /**
     * @Post("/{file_id}/state", name="admin_rest_file_state", options={"expose"=true})
     */
    public function changeStateAction($file_id)
    {
        $dm = $this->getDocumentManager();

        $file = $dm->getRepository('Celsius3CoreBundle:File')
                ->find($file_id);

        if (!$file) {
            throw $this->createNotFoundException('Unable to find File.');
        }

        $file->setEnabled(!$file->getEnabled());
        
        $dm->persist($file);
        $dm->flush();

        $view = $this->view($file, 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

}
