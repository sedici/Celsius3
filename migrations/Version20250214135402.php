<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250214135402 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE admin_instance DROP FOREIGN KEY FK_D690B6D83A51721D');
        $this->addSql('ALTER TABLE admin_instance DROP FOREIGN KEY FK_D690B6D8A76ED395');
        $this->addSql('ALTER TABLE metadata DROP FOREIGN KEY FK_4F14341479D9816F');
        $this->addSql('ALTER TABLE migration DROP FOREIGN KEY FK_C31CB5C23A51721D');
        $this->addSql('DROP TABLE admin_instance');
        $this->addSql('DROP TABLE metadata');
        $this->addSql('DROP TABLE migration');
        $this->addSql('DROP TABLE migration_user');
        $this->addSql('DROP TABLE migration_versions');
        $this->addSql('DROP TABLE orders_data_request');
        $this->addSql('DROP TABLE users_data_request');
        $this->addSql('ALTER TABLE ext_log_entries CHANGE object_class object_class VARCHAR(191) NOT NULL, CHANGE username username VARCHAR(191) DEFAULT NULL');
        $this->addSql('ALTER TABLE ext_translations CHANGE object_class object_class VARCHAR(191) NOT NULL');
        $this->addSql('CREATE INDEX general_translations_lookup_idx ON ext_translations (object_class, foreign_key)');
        $this->addSql('ALTER TABLE message DROP body, DROP created_at');
        $this->addSql('ALTER TABLE request DROP created_at');
        $this->addSql('ALTER TABLE state DROP created_at');
        $this->addSql('ALTER TABLE thread DROP FOREIGN KEY FK_31204C83B03A8386');
        $this->addSql('DROP INDEX IDX_31204C83B03A8386 ON thread');
        $this->addSql('ALTER TABLE thread DROP subject, DROP is_spam, CHANGE created_by_id created_by_id INT NOT NULL');
        $this->addSql('ALTER TABLE thread_metadata DROP is_deleted, DROP last_participant_message_date, CHANGE last_message_date last_message_date DATE NOT NULL');
        $this->addSql('DROP INDEX UNIQ_8D93D64992FC23A8 ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D649A0D96FBF ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D649C05FB297 ON user');
        $this->addSql('ALTER TABLE user DROP last_login, DROP confirmation_token, DROP password_requested_at, CHANGE username username VARCHAR(255) NOT NULL, CHANGE username_canonical username_canonical VARCHAR(255) NOT NULL, CHANGE email_canonical email_canonical VARCHAR(255) NOT NULL, CHANGE salt salt VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE admin_instance (instance_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_D690B6D83A51721D (instance_id), INDEX IDX_D690B6D8A76ED395 (user_id), PRIMARY KEY(instance_id, user_id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE metadata (id INT AUTO_INCREMENT NOT NULL, migration_id INT NOT NULL, original_id VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, tuple LONGTEXT CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, `table` VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, entity VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, entityId INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX idx_original_id (original_id), INDEX idx_table (`table`), INDEX idx_entity (entity), INDEX idx_entity_id (entityId), INDEX idx_migration (migration_id), INDEX idx_search (`table`, original_id, migration_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE migration (id INT AUTO_INCREMENT NOT NULL, instance_id INT NOT NULL, `database` VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, status VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX idx_instance (instance_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE migration_user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, username_canonical VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, email VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, email_canonical VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, password VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, last_login DATETIME DEFAULT NULL, locked TINYINT(1) NOT NULL, expired TINYINT(1) NOT NULL, expires_at DATETIME DEFAULT NULL, confirmation_token VARCHAR(255) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_unicode_ci`, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:array)\', credentials_expired TINYINT(1) NOT NULL, credentials_expire_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_9C247DD892FC23A8 (username_canonical), UNIQUE INDEX UNIQ_9C247DD8A0D96FBF (email_canonical), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE migration_versions (version VARCHAR(14) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, executed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(version)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE orders_data_request (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, start_date DATE NOT NULL, end_date DATE NOT NULL, data LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, exported TINYINT(1) NOT NULL, downloaded TINYINT(1) NOT NULL, visible TINYINT(1) NOT NULL, file VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE users_data_request (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, start_date DATE NOT NULL, end_date DATE NOT NULL, data LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, exported TINYINT(1) NOT NULL, downloaded TINYINT(1) NOT NULL, visible TINYINT(1) NOT NULL, file VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE admin_instance ADD CONSTRAINT FK_D690B6D83A51721D FOREIGN KEY (instance_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE admin_instance ADD CONSTRAINT FK_D690B6D8A76ED395 FOREIGN KEY (user_id) REFERENCES instance (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE metadata ADD CONSTRAINT FK_4F14341479D9816F FOREIGN KEY (migration_id) REFERENCES migration (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE migration ADD CONSTRAINT FK_C31CB5C23A51721D FOREIGN KEY (instance_id) REFERENCES instance (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE ext_log_entries CHANGE object_class object_class VARCHAR(255) NOT NULL, CHANGE username username VARCHAR(255) DEFAULT NULL');
        $this->addSql('DROP INDEX general_translations_lookup_idx ON ext_translations');
        $this->addSql('ALTER TABLE ext_translations CHANGE object_class object_class VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE message ADD body LONGTEXT NOT NULL, ADD created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE request ADD created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE state ADD created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE thread ADD subject VARCHAR(255) NOT NULL, ADD is_spam TINYINT(1) NOT NULL, CHANGE created_by_id created_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE thread ADD CONSTRAINT FK_31204C83B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_31204C83B03A8386 ON thread (created_by_id)');
        $this->addSql('ALTER TABLE thread_metadata ADD is_deleted TINYINT(1) NOT NULL, ADD last_participant_message_date DATETIME DEFAULT NULL, CHANGE last_message_date last_message_date DATETIME DEFAULT NULL');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74 ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D649F85E0677 ON user');
        $this->addSql('ALTER TABLE user ADD last_login DATETIME DEFAULT NULL, ADD confirmation_token VARCHAR(180) DEFAULT NULL, ADD password_requested_at DATETIME DEFAULT NULL, CHANGE username username VARCHAR(180) NOT NULL, CHANGE username_canonical username_canonical VARCHAR(180) NOT NULL, CHANGE email_canonical email_canonical VARCHAR(180) NOT NULL, CHANGE salt salt VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64992FC23A8 ON user (username_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649A0D96FBF ON user (email_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649C05FB297 ON user (confirmation_token)');
    }
}
