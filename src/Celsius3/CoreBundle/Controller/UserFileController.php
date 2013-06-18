<?php

namespace Celsius3\CoreBundle\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius3\CoreBundle\Document\File;
use Celsius3\CoreBundle\Form\Type\FileType;
use Celsius3\CoreBundle\Manager\FileManager;
use Celsius3\CoreBundle\Helper\LifecycleHelper;
use Celsius3\CoreBundle\Manager\EventManager;
use Celsius3\CoreBundle\Document\Order;
use Celsius3\CoreBundle\Controller\Mixin\FileControllerTrait;

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

        $this->get('celsius3_core.file_manager')
                ->registerDownload($order, $file, $this->getRequest(), $user);

        if ($order->getNotDownloadedFiles()->count() == 0) {
            $this->get('celsius3_core.lifecycle_helper')
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