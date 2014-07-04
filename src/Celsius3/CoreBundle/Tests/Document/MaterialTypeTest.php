<?php

namespace Celsius3\CoreBundle\Tests\Document;

use Celsius3\CoreBundle\Tests\TestCase;
use Celsius3\CoreBundle\Document\MaterialType;

class MaterialTypeTest extends TestCase
{

    protected $material;

    public function setUp()
    {
        parent::setUp();

        $this->material = $this->getMockForAbstractClass('Celsius\\Celsius3Bundle\\Document\\MaterialType');
    }

    public function testToString()
    {
        $title = 'Sample title';

        $this->material->setTitle($title);

        $this->assertEquals($title, $this->material->__toString());
    }

    public function testGetId()
    {
        $this->assertNull($this->material->getId());
    }

    public function testGetTitle()
    {
        $title = 'Sample title';

        $this->material->setTitle($title);

        $this->assertEquals($title, $this->material->getTitle());
    }

    public function testGetAuthors()
    {
        $authors = 'Sample authors';

        $this->material->setAuthors($authors);

        $this->assertEquals($authors, $this->material->getAuthors());
    }

    public function testGetYear()
    {
        $year = 1917;

        $this->material->setYear($year);

        $this->assertEquals($year, $this->material->getYear());
    }

    public function testGetStartPage()
    {
        $startPage = 121;

        $this->material->setStartPage($startPage);

        $this->assertEquals($startPage, $this->material->getStartPage());
    }

    public function testGetEndPage()
    {
        $endPage = 214;

        $this->material->setEndPage($endPage);

        $this->assertEquals($endPage, $this->material->getEndPage());
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->material);
    }

}
