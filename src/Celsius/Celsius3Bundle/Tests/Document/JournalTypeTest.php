<?php

namespace Celsius\Celsius3Bundle\Tests\Document;

use Celsius\Celsius3Bundle\Document\JournalType;
use Celsius\Celsius3Bundle\Document\Journal;

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