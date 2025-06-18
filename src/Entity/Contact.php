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

// use Celsius3\Entity\Mixin\SoftDeleteableEntity;
// use Celsius3\Entity\Mixin\TimestampableEntity;
use Celsius3\Repository\ContactRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


#[
    ORM\Table(name: "contact"),
    ORM\Entity(repositoryClass: ContactRepository::class),

    Gedmo\SoftDeleteable(fieldName: "deletedAt"),

    ORM\Index(name: "idx_name", columns: ["name"]),
    ORM\Index(name: "idx_surname", columns: ["surname"]),
    ORM\Index(name: "idx_email", columns: ["email"]),
    ORM\Index(name: "idx_user", columns: ["user_id"]),
    ORM\Index(name: "idx_institution", columns: ["institution_id"]),
    ORM\Index(name: "idx_owning_instance", columns: ["owning_instance_id"])
]
class Contact
{
    use TimestampableEntity;
    use SoftDeleteableEntity;

    #[ORM\OneToMany(targetEntity: CustomContactValue::class, mappedBy: "contact", cascade: ["remove"], fetch: "EXTRA_LAZY")]
    protected Collection $customValues;


    #[ORM\Column(type: "integer")]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    // #[Groups([
    //     "api",
    //     "administration"
    // ])]
    private ?int $id = null;


    #[Assert\NotBlank]
    #[ORM\Column(type: "string", length: 255)]
    // #[Groups([
    //     "api",
    //     "administration"
    // ])]
    private string $name;


    #[Assert\NotBlank]
    #[ORM\Column(type: "string", length: 255)]
    // #[Groups([
    //     "api",
    //     "administration"
    // ])]
    private string $surname;


    #[Assert\NotBlank]
    #[Assert\Email]
    #[ORM\Column(type: "string", length: 255)]
    // #[Groups([
    //     "api",
    //     "administration"
    // ])]
    private string $email;


    #[ORM\Column(type: "string", length: 255, nullable: true)]
    // #[Groups([
    //     "api",
    //     "administration"
    // ])]
    private ?string $address = null;


    #[ORM\OneToOne(targetEntity: BaseUser::class)]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id")]
    private ?BaseUser $user = null;


    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: ContactType::class, inversedBy: "contacts")]
    #[ORM\JoinColumn(name: "type_id", referencedColumnName: "id", nullable: false)]
    // #[Groups(["api"])]
    private ContactType $type;


    #[ORM\ManyToOne(targetEntity: Instance::class, inversedBy: "contacts")]
    #[ORM\JoinColumn(name: "instance_id", referencedColumnName: "id")]
    private ?Instance $instance = null;


    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Institution::class, inversedBy: "contacts")]
    #[ORM\JoinColumn(name: "institution_id", referencedColumnName: "id", nullable: false)]
    private Institution $institution;


    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Instance::class)]
    #[ORM\JoinColumn(name: "owning_instance_id", referencedColumnName: "id", nullable: false)]
    private Instance $owningInstance;


    public function __construct()
    {
        $this->customValues = new ArrayCollection();
    }

    /**
     * Get id.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    public function full_name(): string
    {
        return ($this->user === null)
            ? (string) $this->surname . ', ' . $this->name
            : $this->user->getFullName();
    }

    public function username(): string
    {
        return ($this->user === null)
            ? $this->email
            : $this->user->getUsername();
    }

    /**
     * Get name.
     *
     * @return string $name
     */
    public function getName(): string
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
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get surname.
     *
     * @return string $surname
     */
    public function getSurname(): string
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
    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string $email
     */
    public function getEmail(): string
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
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get address.
     *
     * @return string $address
     */
    public function getAddress(): ?string
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
    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get user.
     *
     * @return BaseUser $user
     */
    public function getUser(): ?BaseUser
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
    public function setUser(?BaseUser $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get type.
     *
     * @return ContactType $type
     */
    public function getType(): ContactType
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
    public function setType(ContactType $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get instance.
     *
     * @return Instance $instance
     */
    public function getInstance(): ?Instance
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
    public function setInstance(?Instance $instance): self
    {
        $this->instance = $instance;

        return $this;
    }

    /**
     * Get institution.
     *
     * @return Institution $institution
     */
    public function getInstitution(): Institution
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
    public function setInstitution(Institution $institution): self
    {
        $this->institution = $institution;

        return $this;
    }

    /**
     * Get owning instance.
     *
     * @return Instance $owningInstance
     */
    public function getOwningInstance(): Instance
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
    public function setOwningInstance(Instance $owningInstance): self
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
