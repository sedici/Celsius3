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

declare(strict_types=1);

namespace Celsius3\Twig;

use Celsius3\CoreBundle\Entity\BaseUser;
use Celsius3\CoreBundle\Entity\Instance;
use Celsius3\CoreBundle\Entity\Journal;
use Celsius3\CoreBundle\Manager\UserManager;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class JournalExtension extends AbstractExtension
{
    private $entityManager;
    private $userManager;

    public function __construct(EntityManagerInterface $entityManager, UserManager $userManager)
    {
        $this->entityManager = $entityManager;
        $this->userManager = $userManager;
    }

    public function getName(): string
    {
        return 'celsius3_core.journal_extension';
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('is_editable_journal', [$this, 'isEditableJournal']),
        ];
    }

    public function isEditableJournal(Journal $journal, Instance $instance, BaseUser $user): bool
    {
        $entity_manager = $this->entityManager;
        $user_manager = $this->userManager;

        if ($user_manager->getCurrentRole($user) === 'ROLE_SUPER_ADMIN') {
            return true;
        }

        $entity = $entity_manager->getRepository('Celsius3CoreBundle:Journal')
            ->findOneBy(['id' => $journal->getId(), 'instance' => $instance->getId()]);

        return $entity !== null;
    }
}
