<?php
/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PrEBi <info@prebi.unlp.edu.ar>
 *
 * This file is part of Celsius3.
 *
 * Celsius3 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Celsius3 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Celsius3.  If not, see <http://www.gnu.org/licenses/>.
 */

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