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

namespace Celsius3\CoreBundle\Entity\Event;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Celsius3\CoreBundle\Helper\LifecycleHelper;
use Celsius3\CoreBundle\Entity\Request;
use Celsius3\NotificationBundle\Entity\Notifiable;
use Celsius3\NotificationBundle\Manager\NotificationManager;

/**
 * @ORM\Entity
 */
class SearchEvent extends SingleInstanceEvent implements Notifiable
{

    /**
     * @Assert\NotBlank
     * @Assert\Choice(callback = {"\Celsius3\CoreBundle\Manager\CatalogManager", "getResults"}, message = "Choose a valid result.")
     * @ORM\Column(type="string", length=255)
     */
    private $result;

    /**
     * @Assert\NotNull
     * @ORM\ManyToOne(targetEntity="Celsius3\CoreBundle\Entity\Catalog")
     * @ORM\JoinColumn(name="catalog_id", referencedColumnName="id")
     */
    private $catalog;

    public function getEventType()
    {
        return 'search';
    }

    public function applyExtraData(Request $request, array $data, LifecycleHelper $lifecycleHelper, $date)
    {
        $this->setResult($data['extraData']['result']);
        $this->setCatalog($data['extraData']['catalog']);
    }

    /**
     * Set result
     *
     * @param  string $result
     * @return self
     */
    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * Get result
     *
     * @return string $result
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Set catalog
     *
     * @param  Celsius3\CoreBundle\Entity\Catalog $catalog
     * @return self
     */
    public function setCatalog(\Celsius3\CoreBundle\Entity\Catalog $catalog)
    {
        $this->catalog = $catalog;

        return $this;
    }

    /**
     * Get catalog
     *
     * @return Celsius3\CoreBundle\Entity\Catalog $catalog
     */
    public function getCatalog()
    {
        return $this->catalog;
    }

    public function notify(NotificationManager $manager)
    {
        $manager->notifyEvent($this,'search');
    }

}
