<?php

namespace Celsius\Celsius3Bundle\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius\Celsius3Bundle\Document\File;
use Celsius\Celsius3Bundle\Form\Type\FileType;
use Celsius\Celsius3Bundle\Manager\FileManager;
use Celsius\Celsius3Bundle\Helper\LifecycleHelper;
use Celsius\Celsius3Bundle\Manager\EventManager;
use Celsius\Celsius3Bundle\Document\Order;
use Celsius\Celsius3Bundle\Controller\Mixin\FileControllerTrait;

/**
 * File controller.
 *
 * @Route("/user/file")
 */
class UserFileController extends BaseController
{
    use FileControllerTrait;

    protected function validate(Order $order, File $file)
    {
        if (!$order) {
            return $this->createNotFoundException('Order not found.');
        }

        $user = $this->get('security.context')->getToken()->getUser();

        if (!$file || $file->getIsDownloaded()
                || $order->getOwner()->getId() != $user->getId()) {
            return $this->createNotFoundException('File not found.');
        }

        $this->get('file_manager')
                ->registerDownload($order, $file, $this->getRequest(), $user);

        if ($order->getNotDownloadedFiles()->count() == 0) {
            $this->get('lifecycle_helper')
                    ->createEvent(EventManager::EVENT__DELIVER, $order);
        }
    }

    /**
     * Downloads the file associated to a File document.
     *
     * @Route("/{order}/{file}/download", name="user_file_download")
     * @Method("post")
     *
     * @param string $id The document ID
     */
    public function downloadAction($order, $file)
    {
        return $this->download($order, $file);
    }
}