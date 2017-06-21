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
use FOS\MessageBundle\Entity\ThreadMetadata as BaseThreadMetadata;

/**
 * @ORM\Entity(repositoryClass="Celsius3\MessageBundle\Repository\BaseRepository")
 * @ORM\Table(name="thread_metadata", indexes={
 *   @ORM\Index(name="idx_thread", columns={"thread_id"}),
 *   @ORM\Index(name="idx_participant", columns={"participant_id"}),
 *   @ORM\Index(name="idx_last_message_date", columns={"last_message_date"})
 * })
 */
class ThreadMetadata extends BaseThreadMetadata
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\ManyToOne(
     *   targetEntity="Celsius3\MessageBundle\Entity\Thread",
     *   inversedBy="metadata"
     * )
     *
     * @var \FOS\MessageBundle\Model\ThreadInterface
     */
    protected $thread;
    /**
     * @ORM\ManyToOne(targetEntity="Celsius3\CoreBundle\Entity\BaseUser")
     *
     * @var \FOS\MessageBundle\Model\ParticipantInterface
     */
    protected $participant;
}
