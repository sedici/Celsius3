<?php

namespace Celsius\Celsius3Bundle\Tests\Document;

use Celsius\Celsius3Bundle\Document\BookType;

class BookTypeTest extends MaterialTypeTest
{

    public function setUp()
    {
        parent::setUp();

        $this->material = new BookType();
    }
    
    public function testGetEditor()
    {
        $editor = 'Sample editor';

        $this->material->setEditor($editor);

        $this->assertEquals($editor, $this->material->getEditor());
    }
    
    public function testGetChapter()
    {
        $chapter = 'VII';

        $this->material->setChapter($chapter);

        $this->assertEquals($chapter, $this->material->getChapter());
    }
    
    public function testGetISBN()
    {
        $ISBN = '978-3-16-148410-0';

        $this->material->setISBN($ISBN);

        $this->assertEquals($ISBN, $this->material->getISBN());
    }
    
    public function testGetWithIndex()
    {
        $withIndex = true;

        $this->material->setWithIndex($withIndex);

        $this->assertEquals($withIndex, $this->material->getWithIndex());
    }

}