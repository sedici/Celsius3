<?php

declare(strict_types=1);

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

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220301175801 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add table messenger_messages.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE messenger_messages (
                                id BIGINT AUTO_INCREMENT NOT NULL, 
                                body LONGTEXT NOT NULL, 
                                headers LONGTEXT NOT NULL, 
                                queue_name VARCHAR(255) NOT NULL, 
                                created_at DATETIME NOT NULL, 
                                available_at DATETIME NOT NULL, 
                                delivered_at DATETIME DEFAULT NULL, 
                                INDEX IDX_75EA56E016BA31DB (delivered_at), 
                                PRIMARY KEY(id)) 
                            DEFAULT CHARACTER SET utf8mb4 
                            COLLATE `utf8mb4_unicode_ci` 
                            ENGINE = InnoDB'
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE messenger_messages');
    }
}
