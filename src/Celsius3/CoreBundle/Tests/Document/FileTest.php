<?php

namespace Celsius3\CoreBundle\Tests\Document;

use Celsius3\CoreBundle\Tests\TestCase;
use Celsius3\CoreBundle\Document\File;
use Celsius3\CoreBundle\Document\Order;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileTest extends TestCase
{

    protected $file;
    protected $path;
    protected $name;
    protected $order;

    public function setUp()
    {
        parent::setUp();

        $this->file = new File();

        $this->name = md5(rand(0, 99999999)) . '.jpg';
        $this->path = $this->file->getUploadDir() . '/' . $this->name;
        $image = imagecreate(100, 100);
        imagejpeg($image, $this->path);
        imagedestroy($image);

        $this->order = new Order();
    }

    public function testGetName()
    {
        $name = 'Sample name';

        $this->file->setName($name);

        $this->assertEquals($name, $this->file->getName());
    }

    public function testGetPath()
    {
        $path = '/sample/path';

        $this->file->setPath($path);

        $this->assertEquals($path, $this->file->getPath());
    }

    public function testGetComments()
    {
        $comments = 'Sample comments';

        $this->file->setComments($comments);

        $this->assertEquals($comments, $this->file->getComments());
    }

    public function testGetMime()
    {
        $mime = 'image/jpeg';

        $this->file->setMime($mime);

        $this->assertEquals($mime, $this->file->getMime());
    }

    public function testGetFile()
    {
        $file = 'file';

        $this->file->setFile($file);

        $this->assertEquals($file, $this->file->getFile());
    }

    public function testGetUploaded()
    {
        $date = date('Y-m-d H:i:s');

        $this->file->setUploaded($date);

        $this->assertEquals($date, $this->file->getUploaded());
    }

    public function testGetEnabled()
    {
        $enabled = true;

        $this->file->setEnabled($enabled);

        $this->assertEquals($enabled, $this->file->getEnabled());
    }

    public function testGetOrders()
    {
        $this->file->addOrders($this->order);

        $this->assertContains($this->order, $this->file->getOrders());
    }

    public function testGenerateFile()
    {
        $file = new UploadedFile(
                        $this->path,
                        $this->name,
                        'image/jpeg',
                        123,
                        null,
                        true
        );

        $this->file->setFile($file);

        $this->assertNull($this->file->getId());

        $this->documentManager->persist($this->file);
        $this->documentManager->flush();

        $this->assertNotNull($this->file->getId());
    }

    public function tearDown()
    {
        parent::tearDown();

        if (file_exists($this->path))
            unlink($this->path);
        unset($this->file, $this->name, $this->path, $this->order);
    }

}
