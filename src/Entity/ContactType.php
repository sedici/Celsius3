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

// use Celsius3\Entity\Mixin\TimestampableEntity as MixinTimestampableEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Celsius3\Repository\BaseRepository;
use Doctrine\Common\Collections\Collection;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: BaseRepository::class)]
#[ORM\Table(name: 'contact_type', indexes: [new ORM\Index(name: 'idx_name', columns: ['name'])] )]
class ContactType
{
    use TimestampableEntity;

    #[ORM\Column(type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    // #[Groups([
    //     'api',
    //     'administration',
    // ])]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: 'string', length: 255)]
    // #[Groups([
    //     'api',
    //     'administration',
    // ])]
    private ?string $name = null;

    #[ORM\OneToMany(targetEntity: Contact::class, mappedBy: 'type', fetch: "EXTRA_LAZY")]
    private $contacts;

    public function __construct()
    {
        $this->contacts = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * Get id.
     */
    public function getId(): int|null
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add contacts.
     *
     * @param Contact $contacts
     */
    public function addContact(Contact $contacts)
    {
        $this->contacts[] = $contacts;
    }

    /**
     * Remove contacts.
     *
     * @param Contact $contacts
     */
    public function removeContact(Contact $contacts)
    {
        $this->contacts->removeElement($contacts);
    }

    /**
     * Get contacts.
     */
    public function getContacts(): array|Collection
    {
        return $this->contacts;
    }
}
