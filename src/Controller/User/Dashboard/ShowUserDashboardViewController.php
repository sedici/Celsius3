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

namespace Celsius3\Controller\User\Dashboard;

use Celsius3\CoreBundle\Controller\BaseInstanceDependentController;
use Celsius3\CoreBundle\Entity\Configuration;
use Celsius3\CoreBundle\Helper\ConfigurationHelper;
use Celsius3\CoreBundle\Repository\ConfigurationRepository;
use Celsius3\MessageBundle\Entity\Thread;
use Celsius3\MessageBundle\Repository\ThreadRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpFoundation\Response;

final class ShowUserDashboardViewController extends BaseInstanceDependentController
{
    /**
     * @var ThreadRepository | ObjectRepository
     */
    private $threadRepository;

    /**
     * @var ConfigurationRepository | ObjectRepository
     */
    private $configurationRepository;

    /**
     * @DI\InjectParams({
     *     "entityManager" = @DI\Inject("doctrine.orm.entity_manager"),
     * })
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->threadRepository = $entityManager->getRepository(Thread::class);
        $this->configurationRepository = $entityManager->getRepository(Configuration::class);
    }

    public function __invoke(): Response
    {
        $last_messages = $this->threadRepository->findUserLastMessages($this->getUser(), 3);
        $results_per_page_config = $this->configurationRepository->findOneBy(
            [
                'instance' => $this->getInstance(),
                'key' => ConfigurationHelper::CONF__RESULTS_PER_PAGE,
            ]
        );

        return $this->render('User/Dashboard/index.html.twig', [
            'lastMessages' => $last_messages,
            'resultsPerPage' => $results_per_page_config->getValue(),
        ]);
    }
}
