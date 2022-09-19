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

namespace Celsius3\Entity;

use Celsius3\Entity\Notifiable;
use Celsius3\Manager\NotificationManager;
use Doctrine\ORM\Mapping as ORM;
//use FOS\MessageBundle\Entity\Message as BaseMessage;

/**
 * @ORM\Entity(repositoryClass="Celsius3\Repository\ThreadRepository")
 * @ORM\Table(name="message", indexes={
 *   @ORM\Index(name="idx_thread", columns={"thread_id"}),
 *   @ORM\Index(name="idx_sender", columns={"sender_id"})
 * })
 */
class Message /*extends BaseMessage*/ implements Notifiable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\ManyToOne(
     *   targetEntity="Celsius3\Entity\Thread",
     *   inversedBy="messages"
     * )
     *
     * @var \FOS\MessageBundle\Model\ThreadInterface
     */
    protected $thread;
    /**
     * @ORM\ManyToOne(targetEntity="Celsius3\Entity\BaseUser")
     *
     * @var \FOS\MessageBundle\Model\ParticipantInterface
     */
    protected $sender;
    /**
     * @ORM\OneToMany(
     *   targetEntity="Celsius3\Entity\MessageMetadata",
     *   mappedBy="message",
     *   cascade={"all"}
     * )
     *
     * @var MessageMetadata[]|\Doctrine\Common\Collections\Collection
     */
    protected $metadata;

    public function __toString()
    {
        return $this->getSender().' - '.$this->getThread()->getSubject();
    }

    public function notify(NotificationManager $manager): void
    {
        $manager->notifyNewMessage($this);
    }

    /**
     * Add metadatum.
     *
     * @param MessageMetadata $metadatum
     *
     * @return Message
     */
    public function addMetadatum(MessageMetadata $metadatum)
    {
        $this->metadata[] = $metadatum;

        return $this;
    }

    /**
     * Remove metadatum.
     *
     * @param MessageMetadata $metadatum
     */
    public function removeMetadatum(MessageMetadata $metadatum)
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
