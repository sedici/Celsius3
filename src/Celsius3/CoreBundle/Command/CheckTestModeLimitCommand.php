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

namespace Celsius3\CoreBundle\Command;

use Celsius3\CoreBundle\Entity\Instance;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class CheckTestModeLimitCommand extends ContainerAwareCommand
{
    protected function configure(): void
    {
        $this->setName('celsius3:test-mode:check-limit')
            ->setDescription('Check the time limit for each instance in test mode.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entity_manager = $this->getContainer()->get('doctrine.orm.entity_manager');
        $instances = $entity_manager->getRepository(Instance::class)->findBy(['inTestMode' => true]);

        foreach ($instances as $instance) {
            if ($instance->testTimeIsOver()) {
                $instance->endTestMode();

                $entity_manager->persist($instance);
            }
        }
        $entity_manager->flush();
    }
}
