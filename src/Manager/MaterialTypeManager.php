<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PREBI-SEDICI <info@prebi.unlp.edu.ar> http://prebi.unlp.edu.ar http://sedici.unlp.edu.ar
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

namespace Celsius3\Manager;

use Celsius3\Entity\BookType;
use Celsius3\Entity\CongressType;
use Celsius3\Entity\JournalType;
use Celsius3\Entity\NewspaperType;
use Celsius3\Entity\PatentType;
use Celsius3\Entity\ThesisType;
use Celsius3\Form\Type\BookTypeType;
use Celsius3\Form\Type\CongressTypeType;
use Celsius3\Form\Type\JournalTypeType;
use Celsius3\Form\Type\NewspaperTypeType;
use Celsius3\Form\Type\PatentTypeType;
use Celsius3\Form\Type\ThesisTypeType;

class MaterialTypeManager
{
    public const TYPE__JOURNAL = 'journal';
    public const TYPE__JOURNAL__HNAME = 'Journal';
    public const TYPE__BOOK = 'book';
    public const TYPE__BOOK__HNAME = 'Book';
    public const TYPE__CONGRESS = 'congress';
    public const TYPE__CONGRESS__HNAME = 'Congress';
    public const TYPE__THESIS = 'thesis';
    public const TYPE__THESIS__HNAME = 'Thesis';
    public const TYPE__PATENT = 'patent';
    public const TYPE__PATENT__HNAME = 'Patent';
    public const TYPE__NEWSPAPER = 'newspaper';
    public const TYPE__NEWSPAPER__HNAME = 'Newspaper';

    public const CHOICES__MAP = [
        self::TYPE__JOURNAL__HNAME => self::TYPE__JOURNAL,
        self::TYPE__BOOK__HNAME => self::TYPE__BOOK,
        self::TYPE__CONGRESS__HNAME => self::TYPE__CONGRESS,
        self::TYPE__THESIS__HNAME => self::TYPE__THESIS,
        self::TYPE__PATENT__HNAME => self::TYPE__PATENT,
        self::TYPE__NEWSPAPER__HNAME => self::TYPE__NEWSPAPER,
    ];

    public const CLSTYPES_MAP = [
        self::TYPE__JOURNAL => JournalType::class,
        self::TYPE__BOOK => BookType::class,
        self::TYPE__CONGRESS => CongressType::class,
        self::TYPE__THESIS => ThesisType::class,
        self::TYPE__PATENT => PatentType::class,
        self::TYPE__NEWSPAPER => NewspaperType::class,
    ];

    public const CLSTYPES_FORM_MAP = [
        self::TYPE__JOURNAL => JournalTypeType::class,
        self::TYPE__BOOK => BookTypeType::class,
        self::TYPE__CONGRESS => CongressTypeType::class,
        self::TYPE__THESIS => ThesisTypeType::class,
        self::TYPE__PATENT => PatentTypeType::class,
        self::TYPE__NEWSPAPER => NewspaperTypeType::class,
    ];

}
