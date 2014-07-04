<?php

namespace Celsius3\CoreBundle\Tests\Document;

use Celsius3\CoreBundle\Tests\TestCase;
use Celsius3\CoreBundle\Document\Journal;
use Celsius3\CoreBundle\Document\JournalType;

class JournalTest extends TestCase
{

    protected $journal;
    protected $material;

    public function setUp()
    {
        parent::setUp();

        $this->journal = new Journal();
        $this->material = new JournalType();
    }

    public function testGetName()
    {
        $name = 'Sample name';

        $this->journal->setName($name);

        $this->assertEquals($name, $this->journal->getName());
    }

    public function testGetAbbreviation()
    {
        $abbreviation = 'SN';

        $this->journal->setAbbreviation($abbreviation);

        $this->assertEquals($abbreviation, $this->journal->getAbbreviation());
    }

    public function testGetResponsible()
    {
        $responsible = 'John Doe';

        $this->journal->setResponsible($responsible);

        $this->assertEquals($responsible, $this->journal->getResponsible());
    }

    public function testGetISSN()
    {
        $ISSN = '0123-4567';

        $this->journal->setISSN($ISSN);

        $this->assertEquals($ISSN, $this->journal->getISSN());
    }

    public function testGetISSNE()
    {
        $ISSNE = '0123-4567';

        $this->journal->setISSNE($ISSNE);

        $this->assertEquals($ISSNE, $this->journal->getISSNE());
    }

    public function testGetFrecuency()
    {
        $frecuency = 'weekly';

        $this->journal->setFrecuency($frecuency);

        $this->assertEquals($frecuency, $this->journal->getFrecuency());
    }

    public function testGetMaterials()
    {
        $this->journal->addMaterials($this->material);

        $this->assertContains($this->material, $this->journal->getMaterials());
    }

    public function testGenerateState()
    {
        $name = 'Sample name';
        $abbreviation = 'SN';
        $responsible = 'John Doe';
        $ISSN = '0123-4567';
        $ISSNE = '0123-4567';
        $frecuency = 'weekly';

        $this->journal->setName($name);
        $this->journal->setAbbreviation($abbreviation);
        $this->journal->setResponsible($responsible);
        $this->journal->setISSN($ISSN);
        $this->journal->setISSNE($ISSNE);
        $this->journal->setFrecuency($frecuency);

        $this->assertNull($this->journal->getId());

        $this->documentManager->persist($this->journal);
        $this->documentManager->flush();

        $this->assertNotNull($this->journal->getId());
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->journal, $this->material);
    }

}
