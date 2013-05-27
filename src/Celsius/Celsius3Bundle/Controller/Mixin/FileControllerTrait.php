<?php

namespace Celsius\Celsius3Bundle\Controller\Mixin;

use Celsius\Celsius3Bundle\Document\Order;
use Celsius\Celsius3Bundle\Document\File;
trait FileControllerTrait
{
    protected function download($order, $file)
    {
        $order = $this->getDocumentManager()
            ->getRepository('CelsiusCelsius3Bundle:Order')->find($order);

        $file = $this->getDocumentManager()
            ->getRepository('CelsiusCelsius3Bundle:File')->find($file);

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