<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250808173403 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', expires_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA312469DE2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA3497B19F9
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA316FE72E1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA3DE12AB56
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA3484BD390
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA3F4D99EDA
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE migration DROP FOREIGN KEY FK_C31CB5C23A51721D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE admin_instance DROP FOREIGN KEY FK_D690B6D8A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE admin_instance DROP FOREIGN KEY FK_D690B6D83A51721D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE metadata DROP FOREIGN KEY FK_4F14341479D9816F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ticket_state DROP FOREIGN KEY FK_8BA3B170C8271B3D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ticket_state DROP FOREIGN KEY FK_8BA3B170700047D2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ticket_state DROP FOREIGN KEY FK_8BA3B170A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE orders_data_request
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE ticket_category
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE ticket_type_state
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE ticket
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE migration
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE ticket_priority
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE migration_user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE admin_instance
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE metadata
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE ticket_state
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE migration_versions
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE users_data_request
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_B6A2DD685F37A13B ON access_token
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE access_token DROP token, DROP expires_at, DROP scope
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_5933D02C5F37A13B ON auth_code
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE auth_code DROP token, DROP redirect_uri, DROP expires_at, DROP scope
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE city CHANGE postal_code postal_code VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE client DROP random_id, DROP redirect_uris, DROP secret, DROP allowed_grant_types
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE contact CHANGE address address VARCHAR(255) DEFAULT NULL, CHANGE deleted_at deleted_at DATETIME DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE custom_field CHANGE type type VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE data_request CHANGE file file VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event CHANGE result result VARCHAR(255) DEFAULT NULL, CHANGE delivery_type delivery_type VARCHAR(255) DEFAULT NULL, CHANGE deleted_at deleted_at DATETIME DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ext_log_entries CHANGE object_id object_id VARCHAR(64) DEFAULT NULL, CHANGE object_class object_class VARCHAR(191) NOT NULL, CHANGE data data LONGTEXT DEFAULT NULL COMMENT '(DC2Type:array)', CHANGE username username VARCHAR(191) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ext_translations CHANGE object_class object_class VARCHAR(191) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX general_translations_lookup_idx ON ext_translations (object_class, foreign_key)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE instance CHANGE url url VARCHAR(255) DEFAULT NULL, CHANGE host host VARCHAR(255) DEFAULT NULL, CHANGE latitud latitud VARCHAR(255) DEFAULT NULL, CHANGE longitud longitud VARCHAR(255) DEFAULT NULL, CHANGE observaciones observaciones VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE journal CHANGE abbreviation abbreviation VARCHAR(255) DEFAULT NULL, CHANGE responsible responsible VARCHAR(255) DEFAULT NULL, CHANGE ISSN issn VARCHAR(255) DEFAULT NULL, CHANGE ISSNE issne VARCHAR(255) DEFAULT NULL, CHANGE frecuency frecuency VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE material_type CHANGE authors authors VARCHAR(255) DEFAULT NULL, CHANGE volume volume VARCHAR(255) DEFAULT NULL, CHANGE number number VARCHAR(255) DEFAULT NULL, CHANGE other other VARCHAR(255) DEFAULT NULL, CHANGE editor editor VARCHAR(255) DEFAULT NULL, CHANGE chapter chapter VARCHAR(255) DEFAULT NULL, CHANGE ISBN isbn VARCHAR(255) DEFAULT NULL, CHANGE place place VARCHAR(255) DEFAULT NULL, CHANGE communication communication VARCHAR(255) DEFAULT NULL, CHANGE director director VARCHAR(255) DEFAULT NULL, CHANGE degree degree VARCHAR(255) DEFAULT NULL, CHANGE deleted_at deleted_at DATETIME DEFAULT NULL, CHANGE article article VARCHAR(255) DEFAULT NULL, CHANGE month month VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message DROP body, CHANGE thread_id thread_id INT NOT NULL, CHANGE sender_id sender_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notification CHANGE viewed_at viewed_at DATETIME DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notification_receiver DROP FOREIGN KEY FK_68A8B433CD53EDB6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notification_receiver DROP FOREIGN KEY FK_68A8B433EF1A9D84
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_68A8B433CD53EDB6 ON notification_receiver
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `primary` ON notification_receiver
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notification_receiver CHANGE receiver_id base_user_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notification_receiver ADD CONSTRAINT FK_68A8B43393686AF1 FOREIGN KEY (base_user_id) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notification_receiver ADD CONSTRAINT FK_68A8B433EF1A9D84 FOREIGN KEY (notification_id) REFERENCES notification (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_68A8B43393686AF1 ON notification_receiver (base_user_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notification_receiver ADD PRIMARY KEY (notification_id, base_user_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notification_viewer DROP FOREIGN KEY FK_C7FB5208CD53EDB6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notification_viewer DROP FOREIGN KEY FK_C7FB5208EF1A9D84
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_C7FB5208CD53EDB6 ON notification_viewer
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `primary` ON notification_viewer
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notification_viewer CHANGE receiver_id base_user_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notification_viewer ADD CONSTRAINT FK_C7FB520893686AF1 FOREIGN KEY (base_user_id) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notification_viewer ADD CONSTRAINT FK_C7FB5208EF1A9D84 FOREIGN KEY (notification_id) REFERENCES notification (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_C7FB520893686AF1 ON notification_viewer (base_user_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notification_viewer ADD PRIMARY KEY (notification_id, base_user_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `order` CHANGE deleted_at deleted_at DATETIME DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE provider CHANGE name name VARCHAR(255) DEFAULT NULL, CHANGE abbreviation abbreviation VARCHAR(255) DEFAULT NULL, CHANGE website website VARCHAR(255) DEFAULT NULL, CHANGE address address VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_C74F21955F37A13B ON refresh_token
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE refresh_token DROP token, DROP expires_at, DROP scope
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE request CHANGE deleted_at deleted_at DATETIME DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE state CHANGE deleted_at deleted_at DATETIME DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE template CHANGE title title VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE thread DROP is_spam, CHANGE created_by_id created_by_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE thread_metadata CHANGE last_participant_message_date last_participant_message_date DATETIME DEFAULT NULL, CHANGE last_message_date last_message_date DATETIME DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_8D93D649C05FB297 ON user
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_8D93D64992FC23A8 ON user
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_8D93D649A0D96FBF ON user
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user CHANGE username username VARCHAR(255) NOT NULL, CHANGE username_canonical username_canonical VARCHAR(255) NOT NULL, CHANGE email_canonical email_canonical VARCHAR(255) NOT NULL, CHANGE salt salt VARCHAR(255) DEFAULT NULL, CHANGE last_login last_login DATETIME DEFAULT NULL, CHANGE confirmation_token confirmation_token VARCHAR(180) DEFAULT NULL, CHANGE password_requested_at password_requested_at DATETIME DEFAULT NULL, CHANGE birthdate birthdate DATE DEFAULT NULL, CHANGE address address VARCHAR(255) DEFAULT NULL, CHANGE observaciones observaciones VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE orders_data_request (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, start_date DATE NOT NULL, end_date DATE NOT NULL, data LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, exported TINYINT(1) NOT NULL, downloaded TINYINT(1) NOT NULL, visible TINYINT(1) NOT NULL, file VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT 'NULL' COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE ticket_category (id INT AUTO_INCREMENT NOT NULL, category VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE ticket_type_state (id INT AUTO_INCREMENT NOT NULL, type_state VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE ticket (id INT AUTO_INCREMENT NOT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, user_assigned_id INT DEFAULT NULL, status_current_id INT DEFAULT NULL, category_id INT DEFAULT NULL, priority_id INT DEFAULT NULL, subject VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, text LONGTEXT CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_97A0ADA312469DE2 (category_id), INDEX IDX_97A0ADA316FE72E1 (updated_by), INDEX IDX_97A0ADA3497B19F9 (priority_id), INDEX IDX_97A0ADA3484BD390 (user_assigned_id), INDEX IDX_97A0ADA3F4D99EDA (status_current_id), INDEX IDX_97A0ADA3DE12AB56 (created_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE migration (id INT AUTO_INCREMENT NOT NULL, instance_id INT NOT NULL, `database` VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, status VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX idx_instance (instance_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE ticket_priority (id INT AUTO_INCREMENT NOT NULL, priority VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE migration_user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, username_canonical VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, email VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, email_canonical VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, password VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, last_login DATETIME DEFAULT 'NULL', locked TINYINT(1) NOT NULL, expired TINYINT(1) NOT NULL, expires_at DATETIME DEFAULT 'NULL', confirmation_token VARCHAR(255) CHARACTER SET utf8mb3 DEFAULT 'NULL' COLLATE `utf8mb3_unicode_ci`, password_requested_at DATETIME DEFAULT 'NULL', roles LONGTEXT CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT '(DC2Type:array)', credentials_expired TINYINT(1) NOT NULL, credentials_expire_at DATETIME DEFAULT 'NULL', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_9C247DD892FC23A8 (username_canonical), UNIQUE INDEX UNIQ_9C247DD8A0D96FBF (email_canonical), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE admin_instance (instance_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_D690B6D83A51721D (instance_id), INDEX IDX_D690B6D8A76ED395 (user_id), PRIMARY KEY(instance_id, user_id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE metadata (id INT AUTO_INCREMENT NOT NULL, migration_id INT NOT NULL, original_id VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, tuple LONGTEXT CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, `table` VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, entity VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, entityId INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX idx_search (`table`, original_id, migration_id), INDEX idx_entity (entity), INDEX idx_entity_id (entityId), INDEX idx_original_id (original_id), INDEX idx_migration (migration_id), INDEX idx_table (`table`), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE ticket_state (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, type_state_id INT DEFAULT NULL, ticket_id INT NOT NULL, descripcion VARCHAR(255) CHARACTER SET utf8mb3 DEFAULT 'NULL' COLLATE `utf8mb3_unicode_ci`, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_8BA3B170A76ED395 (user_id), INDEX IDX_8BA3B170C8271B3D (type_state_id), INDEX IDX_8BA3B170700047D2 (ticket_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE migration_versions (version VARCHAR(14) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, executed_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', PRIMARY KEY(version)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE users_data_request (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, start_date DATE NOT NULL, end_date DATE NOT NULL, data LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, exported TINYINT(1) NOT NULL, downloaded TINYINT(1) NOT NULL, visible TINYINT(1) NOT NULL, file VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT 'NULL' COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA312469DE2 FOREIGN KEY (category_id) REFERENCES ticket_category (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3497B19F9 FOREIGN KEY (priority_id) REFERENCES ticket_priority (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA316FE72E1 FOREIGN KEY (updated_by) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3DE12AB56 FOREIGN KEY (created_by) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3484BD390 FOREIGN KEY (user_assigned_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3F4D99EDA FOREIGN KEY (status_current_id) REFERENCES ticket_state (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE migration ADD CONSTRAINT FK_C31CB5C23A51721D FOREIGN KEY (instance_id) REFERENCES instance (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE admin_instance ADD CONSTRAINT FK_D690B6D8A76ED395 FOREIGN KEY (user_id) REFERENCES instance (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE admin_instance ADD CONSTRAINT FK_D690B6D83A51721D FOREIGN KEY (instance_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE metadata ADD CONSTRAINT FK_4F14341479D9816F FOREIGN KEY (migration_id) REFERENCES migration (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ticket_state ADD CONSTRAINT FK_8BA3B170C8271B3D FOREIGN KEY (type_state_id) REFERENCES ticket_type_state (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ticket_state ADD CONSTRAINT FK_8BA3B170700047D2 FOREIGN KEY (ticket_id) REFERENCES ticket (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ticket_state ADD CONSTRAINT FK_8BA3B170A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE reset_password_request
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `order` CHANGE deleted_at deleted_at DATETIME DEFAULT 'NULL'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event CHANGE deleted_at deleted_at DATETIME DEFAULT 'NULL', CHANGE result result VARCHAR(255) DEFAULT 'NULL', CHANGE delivery_type delivery_type VARCHAR(255) DEFAULT 'NULL'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE state CHANGE deleted_at deleted_at DATETIME DEFAULT 'NULL'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notification CHANGE viewed_at viewed_at DATETIME DEFAULT 'NULL'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE material_type CHANGE authors authors VARCHAR(255) DEFAULT 'NULL', CHANGE deleted_at deleted_at DATETIME DEFAULT 'NULL', CHANGE volume volume VARCHAR(255) DEFAULT 'NULL', CHANGE number number VARCHAR(255) DEFAULT 'NULL', CHANGE other other VARCHAR(255) DEFAULT 'NULL', CHANGE editor editor VARCHAR(255) DEFAULT 'NULL', CHANGE chapter chapter VARCHAR(255) DEFAULT 'NULL', CHANGE isbn ISBN VARCHAR(255) DEFAULT 'NULL', CHANGE place place VARCHAR(255) DEFAULT 'NULL', CHANGE communication communication VARCHAR(255) DEFAULT 'NULL', CHANGE director director VARCHAR(255) DEFAULT 'NULL', CHANGE degree degree VARCHAR(255) DEFAULT 'NULL', CHANGE article article VARCHAR(255) DEFAULT 'NULL', CHANGE month month VARCHAR(255) DEFAULT 'NULL'
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_8D93D649E7927C74 ON user
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user CHANGE confirmation_token confirmation_token VARCHAR(180) DEFAULT 'NULL', CHANGE username username VARCHAR(180) NOT NULL, CHANGE username_canonical username_canonical VARCHAR(180) NOT NULL, CHANGE email_canonical email_canonical VARCHAR(180) NOT NULL, CHANGE salt salt VARCHAR(255) DEFAULT 'NULL', CHANGE password_requested_at password_requested_at DATETIME DEFAULT 'NULL', CHANGE last_login last_login DATETIME DEFAULT 'NULL', CHANGE birthdate birthdate DATE DEFAULT 'NULL', CHANGE address address VARCHAR(255) DEFAULT 'NULL', CHANGE observaciones observaciones VARCHAR(255) DEFAULT 'NULL'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_8D93D649C05FB297 ON user (confirmation_token)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_8D93D64992FC23A8 ON user (username_canonical)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_8D93D649A0D96FBF ON user (email_canonical)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE auth_code ADD token VARCHAR(255) NOT NULL, ADD redirect_uri LONGTEXT NOT NULL, ADD expires_at INT DEFAULT NULL, ADD scope VARCHAR(255) DEFAULT 'NULL'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_5933D02C5F37A13B ON auth_code (token)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE refresh_token ADD token VARCHAR(255) NOT NULL, ADD expires_at INT DEFAULT NULL, ADD scope VARCHAR(255) DEFAULT 'NULL'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_C74F21955F37A13B ON refresh_token (token)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE provider CHANGE name name VARCHAR(255) DEFAULT 'NULL', CHANGE abbreviation abbreviation VARCHAR(255) DEFAULT 'NULL', CHANGE website website VARCHAR(255) DEFAULT 'NULL', CHANGE address address VARCHAR(255) DEFAULT 'NULL'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE data_request CHANGE file file VARCHAR(255) DEFAULT 'NULL'
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX general_translations_lookup_idx ON ext_translations
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ext_translations CHANGE object_class object_class VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE custom_field CHANGE type type VARCHAR(255) DEFAULT 'NULL'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notification_receiver DROP FOREIGN KEY FK_68A8B43393686AF1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notification_receiver DROP FOREIGN KEY FK_68A8B433EF1A9D84
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_68A8B43393686AF1 ON notification_receiver
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `PRIMARY` ON notification_receiver
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notification_receiver CHANGE base_user_id receiver_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notification_receiver ADD CONSTRAINT FK_68A8B433CD53EDB6 FOREIGN KEY (receiver_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notification_receiver ADD CONSTRAINT FK_68A8B433EF1A9D84 FOREIGN KEY (notification_id) REFERENCES notification (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_68A8B433CD53EDB6 ON notification_receiver (receiver_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notification_receiver ADD PRIMARY KEY (notification_id, receiver_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE thread ADD is_spam TINYINT(1) NOT NULL, CHANGE created_by_id created_by_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE request CHANGE deleted_at deleted_at DATETIME DEFAULT 'NULL'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE client ADD random_id VARCHAR(255) NOT NULL, ADD redirect_uris LONGTEXT NOT NULL COMMENT '(DC2Type:array)', ADD secret VARCHAR(255) NOT NULL, ADD allowed_grant_types LONGTEXT NOT NULL COMMENT '(DC2Type:array)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notification_viewer DROP FOREIGN KEY FK_C7FB520893686AF1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notification_viewer DROP FOREIGN KEY FK_C7FB5208EF1A9D84
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_C7FB520893686AF1 ON notification_viewer
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `PRIMARY` ON notification_viewer
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notification_viewer CHANGE base_user_id receiver_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notification_viewer ADD CONSTRAINT FK_C7FB5208CD53EDB6 FOREIGN KEY (receiver_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notification_viewer ADD CONSTRAINT FK_C7FB5208EF1A9D84 FOREIGN KEY (notification_id) REFERENCES notification (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_C7FB5208CD53EDB6 ON notification_viewer (receiver_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notification_viewer ADD PRIMARY KEY (notification_id, receiver_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ext_log_entries CHANGE object_id object_id VARCHAR(64) DEFAULT 'NULL', CHANGE object_class object_class VARCHAR(255) NOT NULL, CHANGE data data LONGTEXT DEFAULT 'NULL' COMMENT '(DC2Type:array)', CHANGE username username VARCHAR(255) DEFAULT 'NULL'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE access_token ADD token VARCHAR(255) NOT NULL, ADD expires_at INT DEFAULT NULL, ADD scope VARCHAR(255) DEFAULT 'NULL'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_B6A2DD685F37A13B ON access_token (token)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message ADD body LONGTEXT NOT NULL, CHANGE thread_id thread_id INT DEFAULT NULL, CHANGE sender_id sender_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE instance CHANGE url url VARCHAR(255) DEFAULT 'NULL', CHANGE host host VARCHAR(255) DEFAULT 'NULL', CHANGE latitud latitud VARCHAR(255) DEFAULT 'NULL', CHANGE longitud longitud VARCHAR(255) DEFAULT 'NULL', CHANGE observaciones observaciones VARCHAR(255) DEFAULT 'NULL'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE thread_metadata CHANGE last_message_date last_message_date DATETIME DEFAULT 'NULL', CHANGE last_participant_message_date last_participant_message_date DATETIME DEFAULT 'NULL'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE city CHANGE postal_code postal_code VARCHAR(255) DEFAULT 'NULL'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE template CHANGE title title VARCHAR(255) DEFAULT 'NULL'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE journal CHANGE abbreviation abbreviation VARCHAR(255) DEFAULT 'NULL', CHANGE responsible responsible VARCHAR(255) DEFAULT 'NULL', CHANGE issn ISSN VARCHAR(255) DEFAULT 'NULL', CHANGE issne ISSNE VARCHAR(255) DEFAULT 'NULL', CHANGE frecuency frecuency VARCHAR(255) DEFAULT 'NULL'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE contact CHANGE address address VARCHAR(255) DEFAULT 'NULL', CHANGE deleted_at deleted_at DATETIME DEFAULT 'NULL'
        SQL);
    }
}
