<?php

namespace Celsius3\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius3\CoreBundle\Document\File;
use Celsius3\CoreBundle\Form\Type\FileType;
use Celsius3\CoreBundle\Controller\Mixin\FileControllerTrait;

/**
 * File controller.
 *
 * @Route("/admin/file")
 */
class AdminFileController extends BaseController
{

    use FileControllerTrait;

    protected function validate($request, $file)
    {
        if (!$request) {
            return $this->createNotFoundException('Request not found.');
        }

        if (!$file) {
            return $this->createNotFoundException('File not found.');
        }

        $user = $this->get('security.context')->getToken()->getUser();
        
        $httpRequest = $this->get('request_stack')->getCurrentRequest();

        $this->get('celsius3_core.file_manager')->registerDownload($request, $file, $httpRequest, $user);
    }

    /**
     * Downloads the file associated to a File document.
     *
     * @Route("/{request}/{file}/download", name="admin_file_download", options={"expose"=true})
     *
     * @param string $id The document ID
     */
    public function downloadAction($request, $file)
    {
        return $this->download($request, $file);
    }

}
