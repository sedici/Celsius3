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

final class Version20220301165750 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add reset_password_request table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE reset_password_request (
                    id INT AUTO_INCREMENT NOT NULL, 
                    user_id INT NOT NULL, 
                    selector VARCHAR(20) NOT NULL, 
                    hashed_token VARCHAR(100) NOT NULL, 
                    requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', 
                    expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                    INDEX IDX_7CE748AA76ED395 (user_id),
                    PRIMARY KEY(id))
                DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
        $this->addSql(
            'ALTER TABLE reset_password_request 
                    ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)'
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE reset_password_request');
    }
}
