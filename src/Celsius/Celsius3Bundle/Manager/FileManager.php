<?php

namespace Celsius\Celsius3Bundle\Manager;

use Doctrine\ODM\MongoDB\DocumentManager;
use Celsius\Celsius3Bundle\Document\Event;
use Celsius\Celsius3Bundle\Document\File;
use Celsius\Celsius3Bundle\Document\Order;

class FileManager
{

    private $dm;

    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
    }

    public function uploadFiles(Order $order, Event $event, array $files = array())
    {
        foreach ($files as $uploadedFile)
        {
            $file = new File();
            $file->setFile($uploadedFile);
            $file->setEvent($event);
            $file->setOrder($order);
            $this->dm->persist($file);
            $this->dm->flush();
        }
    }

}