<?php

namespace Celsius3\CoreBundle\Controller\Mixin;

use Celsius3\CoreBundle\Document\Order;
use Celsius3\CoreBundle\Document\File;
use Symfony\Component\HttpFoundation\Response;
trait FileControllerTrait
{
    protected function download($order, $file)
    {
        $order = $this->getDocumentManager()
            ->getRepository('Celsius3CoreBundle:Order')->find($order);

        $file = $this->getDocumentManager()
            ->getRepository('Celsius3CoreBundle:File')->find($file);

        $this->validate($order, $file);

        $response = new Response();
        $response->headers->set('Content-type', $file->getMime() . ';');
        $response->headers
            ->set('Content-Disposition',
                    'attachment;filename="' . $file->getName() . '"');
        $response->setContent($file->getFile()->getBytes());

        return $response;
    }

    abstract protected function validate(Order $order, File $file);
}