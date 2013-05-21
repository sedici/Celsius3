<?php

namespace Celsius\Celsius3Bundle\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius\Celsius3Bundle\Document\File;
use Celsius\Celsius3Bundle\Form\Type\FileType;

/**
 * File controller.
 *
 * @Route("/admin/file")
 */
class AdminFileController extends BaseController
{
    /**
     * Downloads the file associated to a File document.
     *
     * @Route("/{order}/{file}/download", name="admin_file_download")
     *
     * @param string $id The document ID
     */
    public function downloadAction($order, $file)
    {
        /* Esto es muy similar al controlador de User, evaluar la posibilidad
         * de usar traits para resolver ese problema
         */
        $order = $this->getDocumentManager()
                ->getRepository('CelsiusCelsius3Bundle:Order')->find($order);

        if (!$order) {
            return $this->createNotFoundException('Order not found.');
        }

        $file = $this->getDocumentManager()
                ->getRepository('CelsiusCelsius3Bundle:File')->find($file);

        $user = $this->get('security.context')->getToken()->getUser();

        if (!$file) {
            return $this->createNotFoundException('File not found.');
        }

        $this->get('file_manager')
                ->registerDownload($order, $file, $this->getRequest(), $user);

        $response = new Response();
        $response->headers->set('Content-type', $file->getMime() . ';');
        $response->headers
                ->set('Content-Disposition',
                        'attachment;filename="' . $file->getName() . '"');
        $response->setContent($file->getFile()->getBytes());

        return $response;
    }
}
