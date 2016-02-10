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

namespace Celsius3\CoreBundle\Twig;

use Celsius3\CoreBundle\Entity\Journal;
use Celsius3\CoreBundle\Entity\Instance;
use Celsius3\CoreBundle\Entity\BaseUser;

class JournalExtension extends \Twig_Extension
{

    private $container;

    function __construct($container)
    {
        $this->container = $container;
    }

    public function getName()
    {
        return 'celsius3_core.journal_extension';
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('is_editable_journal', array($this, 'isEditableJournal')),
        );
    }

    public function isEditableJournal(Journal $journal, Instance $instance, BaseUser $user)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $um = $this->container->get('celsius3_core.user_manager');

        if ($um->getCurrentRole($user) === 'ROLE_SUPER_ADMIN') {
            return true;
        }

        $entity = $em->getRepository('Celsius3CoreBundle:Journal')
                ->findOneBy(array('id' => $journal->getId(), 'instance' => $instance->getId()));

        return (is_null($entity)) ? false : true;
    }

}
