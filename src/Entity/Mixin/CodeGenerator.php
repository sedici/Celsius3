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

namespace Celsius3\Entity\Mixin;

use Doctrine\ORM\EntityManagerInterface;


class CodeGenerator
{
    public function __construct(private EntityManagerInterface $em) {}

    public function generateCode(
        string $entityClass,
        string $field
    ): int {
        $conn = $this->em->getConnection();
        $conn->beginTransaction();
        
        try {
            $tableName = $this->em->getClassMetadata($entityClass)->getTableName();
            
            // Bloqueo para MySQL
            $conn->executeQuery("LOCK TABLES $tableName WRITE");
            
            $sql = "SELECT MAX($field) FROM $tableName";
            $max = $conn->executeQuery($sql)->fetchOne();
            
            $newCode = ($max ?? 0) + 1;
            $conn->commit();
            
            // Desbloquear tablas (MySQL)
            $conn->executeQuery("UNLOCK TABLES");
            
            return $newCode;
        } catch (\Exception $e) {
            $conn->rollBack();
            throw $e;
        }
    }
}