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

use Celsius3\CoreBundle\Entity\Catalog;
use Celsius3\CoreBundle\Entity\Instance;
use Celsius3\Helper\InstanceHelper;
use Celsius3\Manager\CatalogManager;
use Celsius3\Manager\InstanceManager;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CatalogExtension extends AbstractExtension
{
    private $entityManager;
    private $instanceHelper;
    private $instanceManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        InstanceHelper $instanceHelper,
        InstanceManager $instanceManager
    ) {
        $this->entityManager = $entityManager;
        $this->instanceHelper = $instanceHelper;
        $this->instanceManager = $instanceManager;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('is_catalog_enabled', [$this, 'isCatalogEnabled']),
            new TwigFunction('get_disabled_catalogs_count', [$this, 'getDisabledCatalogsCount']),
        ];
    }

    public function isCatalogEnabled(Catalog $catalog): bool
    {
        $position = $catalog->getPosition($this->instanceHelper->getSessionInstance());

        if (!$position && ($catalog->getInstance()->getId() === $this->instanceManager->getDirectory()->getId())) {
            return true;
        }

        if (!$position && ($catalog->getInstance()->getId() === $this->instanceManager->getDirectory()->getId())) {
            return true;
        }

        if ($position) {
            return $position->getEnabled();
        }

        return false;
    }

    public function getDisabledCatalogsCount(Instance $instance, Instance $directory)
    {
        return $this->entityManager->getRepository(Catalog::class)
            ->getDisabledCatalogsCount($instance, $directory);
    }

    public function getName(): string
    {
        return 'celsius3_core.catalog_extension';
    }
}
