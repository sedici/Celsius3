<?php

namespace Celsius3\CoreBundle\Tests\Document;

use Celsius3\CoreBundle\Document\JournalType;
use Celsius3\CoreBundle\Document\Journal;

class JournalTypeTest extends MaterialTypeTest
{

    protected $journal;

    public function setUp()
    {
        parent::setUp();

        $this->material = new JournalType();
        $this->journal = new Journal();
    }

    public function testGetVolume()
    {
        $volume = 'VIII';

        $this->material->setVolume($volume);

        $this->assertEquals($volume, $this->material->getVolume());
    }

    public function testGetNumber()
    {
        $number = '14';

        $this->material->setNumber($number);

        $this->assertEquals($number, $this->material->getNumber());
    }

    public function testGetJournal()
    {
        $this->material->setJournal($this->journal);

        $this->assertEquals($this->journal, $this->material->getJournal());
    }

}
