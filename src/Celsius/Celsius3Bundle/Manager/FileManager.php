<?php

namespace Celsius\Celsius3Bundle\Manager;
use Doctrine\ODM\MongoDB\DocumentManager;
use Celsius\Celsius3Bundle\Document\Event;
use Celsius\Celsius3Bundle\Document\File;
use Celsius\Celsius3Bundle\Document\Order;
use Celsius\Celsius3Bundle\Document\FileDownload;
use Symfony\Component\HttpFoundation\Request;
use Celsius\Celsius3Bundle\Document\BaseUser;

class FileManager
{
    private $dm;

    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
    }

    public function uploadFiles(Order $order, Event $event,
            array $files = array())
    {
        foreach ($files as $uploadedFile) {
            $file = new File();
            $file->setFile($uploadedFile);
            $file->setEvent($event);
            $file->setOrder($order);
            $event->addFile($file);
        }
    }

    public function registerDownload(Order $order, File $file, Request $request,
            BaseUser $user)
    {
        $file->setIsDownloaded(true);
        $download = new FileDownload();
        $download->setDate(time());
        $download->setIp($request->getClientIp());
        $download->setUser($user);
        $download->setUserAgent($request->headers->get('user-agent'));
        $download->setFile($file);
        $download->setOrder($order);
        $this->dm->persist($file);
        $this->dm->persist($download);
        $this->dm->flush();
    }
}
