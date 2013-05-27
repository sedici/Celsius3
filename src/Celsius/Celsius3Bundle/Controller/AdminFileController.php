<?php

namespace Celsius\Celsius3Bundle\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius\Celsius3Bundle\Document\File;
use Celsius\Celsius3Bundle\Form\Type\FileType;
use Celsius\Celsius3Bundle\Controller\Mixin\FileControllerTrait;

/**
 * File controller.
 *
 * @Route("/admin/file")
 */
class AdminFileController extends BaseController
{
    use FileControllerTrait;

    protected function validate($order, $file)
    {
        if (!$order) {
            return $this->createNotFoundException('Order not found.');
        }

        if (!$file) {
            return $this->createNotFoundException('File not found.');
        }

        $user = $this->get('security.context')->getToken()->getUser();

        $this->get('file_manager')
                ->registerDownload($order, $file, $this->getRequest(), $user);
    }

    /**
     * Downloads the file associated to a File document.
     *
     * @Route("/{order}/{file}/download", name="admin_file_download")
     *
     * @param string $id The document ID
     */
    public function downloadAction($order, $file)
    {
        return $this->download($order, $file);
    }
}