<?php

namespace Celsius\Celsius3Bundle\Tests\Document;

use Celsius\Celsius3Bundle\Document\ThesisType;

class ThesisTypeTest extends MaterialTypeTest
{

    public function setUp()
    {
        parent::setUp();

        $this->material = new ThesisType();
    }

    public function testGetDirector()
    {
        $director = 'Sample director';

        $this->material->setDirector($director);

        $this->assertEquals($director, $this->material->getDirector());
    }

    public function testGetDegree()
    {
        $degree = 'Grade';

        $this->material->setDegree($degree);

        $this->assertEquals($degree, $this->material->getDegree());
    }

}