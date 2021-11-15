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

namespace Celsius3\Entity\Event;

use Celsius3\Entity\Catalog;
use Celsius3\Entity\Request;
use Celsius3\Helper\LifecycleHelper;
use Celsius3\NotificationBundle\Entity\Notifiable;
use Celsius3\NotificationBundle\Manager\NotificationManager;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Celsius3\Repository\SearchEventRepository")
 */
class SearchEvent extends SingleInstanceEvent implements Notifiable
{
    /**
     * @Assert\NotBlank
     * @Assert\Choice(
     *     callback = {"\Celsius3\Manager\CatalogManager", "getResults"},
     *     message = "Choose a valid result."
     * )
     * @ORM\Column(type="string", length=255)
     */
    private $result;

    /**
     * @Assert\NotNull
     * @ORM\ManyToOne(targetEntity="Celsius3\Entity\Catalog")
     * @ORM\JoinColumn(name="catalog_id", referencedColumnName="id")
     */
    private $catalog;

    public function getEventType(): string
    {
        return 'search';
    }

    public function applyExtraData(Request $request, array $data, LifecycleHelper $lifecycleHelper, $date): void
    {
        $this->setResult($data['extraData']['result']);
        $this->setCatalog($data['extraData']['catalog']);
    }

    public function getResult(): ?string
    {
        return $this->result;
    }

    public function setResult(?string $result): self
    {
        $this->result = $result;

        return $this;
    }

    public function getCatalog(): Catalog
    {
        return $this->catalog;
    }

    public function setCatalog(Catalog $catalog): self
    {
        $this->catalog = $catalog;

        return $this;
    }

    public function notify(NotificationManager $manager): void
    {
        $manager->notifyEvent($this, 'search');
    }
}
