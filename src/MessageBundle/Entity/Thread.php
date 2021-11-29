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

namespace Celsius3\MessageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\MessageBundle\Entity\Thread as BaseThread;

/**
 * @ORM\Entity(repositoryClass="Celsius3\MessageBundle\Repository\ThreadRepository")
 * @ORM\Table(name="thread", indexes={
 *   @ORM\Index(name="idx_created_at", columns={"created_at"})
 * })
 */
class Thread extends BaseThread
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\ManyToOne(targetEntity="Celsius3\Entity\BaseUser")
     *
     * @var \FOS\MessageBundle\Model\ParticipantInterface
     */
    protected $createdBy;
    /**
     * @ORM\OneToMany(
     *   targetEntity="Celsius3\MessageBundle\Entity\Message",
     *   mappedBy="thread"
     * )
     *
     * @var Message[]|\Doctrine\Common\Collections\Collection
     */
    protected $messages;
    /**
     * @ORM\OneToMany(
     *   targetEntity="Celsius3\MessageBundle\Entity\ThreadMetadata",
     *   mappedBy="thread",
     *   cascade={"all"}
     * )
     *
     * @var ThreadMetadata[]|\Doctrine\Common\Collections\Collection
     */
    protected $metadata;

    /**
     * Remove message.
     *
     * @param \Celsius3\MessageBundle\Entity\Message $message
     */
    public function removeMessage(\Celsius3\MessageBundle\Entity\Message $message)
    {
        $this->messages->removeElement($message);
    }

    /**
     * Add metadatum.
     *
     * @param ThreadMetadata $metadatum
     *
     * @return Thread
     */
    public function addMetadatum(ThreadMetadata $metadatum)
    {
        $this->metadata[] = $metadatum;

        return $this;
    }

    /**
     * Remove metadatum.
     *
     * @param ThreadMetadata $metadatum
     */
    public function removeMetadatum(ThreadMetadata $metadatum)
    {
        $this->metadata->removeElement($metadatum);
    }

    /**
     * Get metadata.
     *
     * @return Collection
     */
    public function getMetadata()
    {
        return $this->metadata;
    }
}
