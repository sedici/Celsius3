<?php

/*
 * This file is part of the Doctrine Behavioral Extensions package.
 * (c) Gediminas Morkevicius <gediminas.morkevicius@gmail.com> http://www.gediminasm.org
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Celsius3\Entity\Mixin;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * A soft deletable trait you can apply to your Doctrine ORM entities.
 * Includes default annotation mapping.
 *
 * @author Wesley van Opdorp <wesley.van.opdorp@freshheads.com>
 */
trait SoftDeleteableEntity
{

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    protected $deletedAt;


    public function setDeletedAt(?DateTime $deletedAt = null): static
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }


    public function getDeletedAt(): DateTime|null
    { return $this->deletedAt; }


    public function isDeleted(): bool
    { return null !== $this->deletedAt; }
}
