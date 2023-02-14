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

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Celsius3\Entity\SoftDeleteableEntity;
use Celsius3\Entity\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 * @ORM\Entity(repositoryClass="Celsius3\Repository\ContactRepository")
 * @ORM\Table(name="contact", indexes={
 *   @ORM\Index(name="idx_name", columns={"name"}),
 *   @ORM\Index(name="idx_surname", columns={"surname"}),
 *   @ORM\Index(name="idx_email", columns={"email"}),
 *   @ORM\Index(name="idx_user", columns={"user_id"}),
 *   @ORM\Index(name="idx_institution", columns={"institution_id"}),
 *   @ORM\Index(name="idx_owning_instance", columns={"owning_instance_id"})
 * })
 */
class Contact
{
    use TimestampableEntity;
    use SoftDeleteableEntity;

    /**
     * @ORM\OneToMany(targetEntity="CustomContactValue", mappedBy="contact", cascade={"remove"})
     */
    protected $customValues;
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     */
    private $name;
    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     */
    private $surname;
    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     * @ORM\Column(type="string", length=255)
     */
    private $email;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;
    /**
     * @ORM\OneToOne(targetEntity="BaseUser")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    /**
     * @Assert\NotNull
     * @ORM\ManyToOne(targetEntity="ContactType", inversedBy="contacts")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id", nullable=false)
     */
    private $type;
    /**
     * @ORM\ManyToOne(targetEntity="Instance", inversedBy="contacts")
     * @ORM\JoinColumn(name="instance_id", referencedColumnName="id")
     */
    private $instance;
    /**
     * @Assert\NotNull
     * @ORM\ManyToOne(targetEntity="Institution", inversedBy="contacts")
     * @ORM\JoinColumn(name="institution_id", referencedColumnName="id", nullable=false)
     */
    private $institution;
    /**
     * @Assert\NotNull
     * @ORM\ManyToOne(targetEntity="Instance")
     * @ORM\JoinColumn(name="owning_instance_id", referencedColumnName="id", nullable=false)
     */
    private $owningInstance;

    /**
     * Get id.
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
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
     * Get surname.
     *
     * @return string $surname
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set surname.
     *
     * @param string $surname
     *
     * @return self
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get address.
     *
     * @return string $address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set address.
     *
     * @param string $address
     *
     * @return self
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get user.
     *
     * @return BaseUser $user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set user.
     *
     * @param BaseUser $user
     *
     * @return self
     */
    public function setUser(BaseUser $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get type.
     *
     * @return ContactType $type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set type.
     *
     * @param ContactType $type
     *
     * @return self
     */
    public function setType(ContactType $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get instance.
     *
     * @return Instance $instance
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * Set instance.
     *
     * @param Instance $instance
     *
     * @return self
     */
    public function setInstance(Instance $instance)
    {
        $this->instance = $instance;

        return $this;
    }

    /**
     * Get institution.
     *
     * @return Institution $institution
     */
    public function getInstitution()
    {
        return $this->institution;
    }

    /**
     * Set institution.
     *
     * @param Institution $institution
     *
     * @return self
     */
    public function setInstitution(Institution $institution)
    {
        $this->institution = $institution;

        return $this;
    }

    /**
     * Get owning instance.
     *
     * @return Instance $owningInstance
     */
    public function getOwningInstance()
    {
        return $this->owningInstance;
    }

    /**
     * Set owning instance.
     *
     * @param Instance $owningInstance
     *
     * @return self
     */
    public function setOwningInstance(Instance $owningInstance)
    {
        $this->owningInstance = $owningInstance;

        return $this;
    }

    public function addCustomValue(CustomValue $customValues): self
    {
        $this->customValues[] = $customValues;

        return $this;
    }

    public function removeCustomValue(CustomValue $customValues): void
    {
        $this->customValues->removeElement($customValues);
    }

    public function getCustomValues(): Collection
    {
        return $this->customValues;
    }
}
