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

use Celsius3\Entity\BaseUser;
use Celsius3\Entity\Catalog;
use Celsius3\Entity\CatalogPosition;
use Celsius3\Entity\CatalogResult;
use Celsius3\Entity\City;
use Celsius3\Entity\Contact;
use Celsius3\Entity\Country;
use Celsius3\Entity\CustomUserValue;
use Celsius3\Entity\Email;
use Celsius3\Entity\Event\Event;
use Celsius3\Entity\Event\MultiInstanceRequestEvent;
use Celsius3\Entity\Event\SearchEvent;
use Celsius3\Entity\Event\SingleInstanceRequestEvent;
use Celsius3\Entity\FileDownload;
use Celsius3\Entity\Institution;
use Celsius3\Entity\Journal;
use Celsius3\Entity\Request;
use Celsius3\Entity\State;
use Celsius3\Form\Type\JournalType;
use Celsius3\Entity\Message;
use Celsius3\Entity\ThreadMetadata;
use Celsius3\Entity\BaseUserNotification;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class UnionManager
{
    private $em;
    private $instance_manager;
    private $references = array(
        Country::class => array(
            City::class => array(
                'country',
            ),
            Institution::class => array(
                'country',
            ),
        ),
        City::class => array(
            Institution::class => array(
                'city',
            ),
        ),
        Institution::class => array(
            BaseUser::class => array(
                'institution',
            ),
            Institution::class => array(
                'parent',
            ),
            Catalog::class => array(
                'institution',
            ),
            Contact::class => array(
                'institution',
            ),
            SingleInstanceRequestEvent::class => array(
                'provider',
            ),
            MultiInstanceRequestEvent::class => array(
                'provider',
            ),
        ),
        Catalog::class => array(
            SearchEvent::class => array(
                'catalog',
            ),
            CatalogPosition::class => array(
                'catalog',
            ),
            CatalogResult::class => array(
                'catalog'
            )
        ),
        Journal::class => array(
            JournalType::class => array(
                'journal',
            ),
        ),
        BaseUser::class => array(
            Request::class => array(
                'owner',
                'operator',
                'creator',
                'librarian',
            ),
            FileDownload::class => array(
                'user',
            ),
            Contact::class => array(
                'user',
            ),
            CustomUserValue::class => array(
                'user',
            ),
            Email::class => array(
                'sender',
            ),
            Event::class => array(
                'operator',
            ),
            State::class => array(
                'operator',
            ),
            Message::class => array(
                'sender',
            ),
            ThreadMetadata::class => array(
                'participant',
            ),
            BaseUserNotification::class => array(
                'object',
            ),
        ),
    );

    public function __construct(EntityManagerInterface $em, InstanceManager $instance_manager)
    {
        $this->em = $em;
        $this->instance_manager = $instance_manager;
    }

    public function union($name, $main, array $elements, $updateInstance)
    {
        $this->em->getFilters()->disable('softdeleteable');

        if (array_key_exists($name, $this->references)) {
            foreach ($this->references[$name] as $key => $reference) {
                foreach ($reference as $field) {
                    $this->em->getRepository($key)->union($field, $main->getId(), $elements);
                }
            }
        }

        $this->em->getFilters()->enable('softdeleteable');

        $this->em->getRepository($name)->deleteUnitedEntities($elements);

        if ($updateInstance) {
            $main->setInstance($this->instance_manager->getDirectory());
            $this->em->persist($main);
            $this->em->flush($main);
        }
    }
}
