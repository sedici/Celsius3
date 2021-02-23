<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210222114111 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `instance` CHANGE createdAt created_at DATETIME NOT NULL, CHANGE updatedAt updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `order` CHANGE createdAt created_at DATETIME NOT NULL, CHANGE updatedAt updated_at DATETIME NOT NULL, CHANGE deletedat deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE `news` CHANGE createdAt created_at DATETIME NOT NULL, CHANGE updatedAt updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `configuration` CHANGE createdAt created_at DATETIME NOT NULL, CHANGE updatedAt updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `login` CHANGE createdAt created_at DATETIME NOT NULL, CHANGE updatedAt updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `user` CHANGE createdAt created_at DATETIME NOT NULL, CHANGE updatedAt updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `catalog` CHANGE createdAt created_at DATETIME NOT NULL, CHANGE updatedAt updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `catalog_position` CHANGE createdAt created_at DATETIME NOT NULL, CHANGE updatedAt updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `catalog_result` CHANGE createdAt created_at DATETIME NOT NULL, CHANGE updatedAt updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `city` CHANGE createdAt created_at DATETIME NOT NULL, CHANGE updatedAt updated_at DATETIME NOT NULL, CHANGE postalcode postal_code VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE `contact` CHANGE createdAt created_at DATETIME NOT NULL, CHANGE updatedAt updated_at DATETIME NOT NULL, CHANGE deletedat deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE `contact_type` CHANGE createdAt created_at DATETIME NOT NULL, CHANGE updatedAt updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `counter` CHANGE createdAt created_at DATETIME NOT NULL, CHANGE updatedAt updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `country` CHANGE createdAt created_at DATETIME NOT NULL, CHANGE updatedAt updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `custom_field` CHANGE createdAt created_at DATETIME NOT NULL, CHANGE updatedAt updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `custom_value` CHANGE createdAt created_at DATETIME NOT NULL, CHANGE updatedAt updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `data_request` CHANGE createdAt created_at DATETIME NOT NULL, CHANGE updatedAt updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `email` CHANGE createdAt created_at DATETIME NOT NULL, CHANGE updatedAt updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `event` CHANGE createdAt created_at              DATETIME NOT NULL, CHANGE updatedAt updated_at DATETIME NOT NULL, CHANGE deletedat deleted_at DATETIME DEFAULT NULL, CHANGE cancelledbyuser cancelled_by_user TINYINT(1) DEFAULT NULL, CHANGE deliverytype delivery_type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE `file` CHANGE createdAt created_at DATETIME NOT NULL, CHANGE updatedAt updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `file_download` CHANGE createdAt created_at DATETIME NOT NULL, CHANGE updatedAt updated_at DATETIME NOT NULL, CHANGE useragent user_agent LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE `hive` CHANGE createdAt created_at DATETIME NOT NULL, CHANGE updatedAt updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `journal` CHANGE createdAt created_at DATETIME NOT NULL, CHANGE updatedAt updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `material_type` CHANGE createdAt created_at DATETIME NOT NULL, CHANGE updatedAt updated_at DATETIME NOT NULL, CHANGE deletedAt deleted_at DATETIME DEFAULT NULL, CHANGE startPage start_page INT DEFAULT NULL, CHANGE endPage end_page INT DEFAULT NULL, CHANGE withindex with_index TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE `metadata` CHANGE createdAt created_at DATETIME NOT NULL, CHANGE updatedAt updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `migration` CHANGE createdAt created_at DATETIME NOT NULL, CHANGE updatedAt updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `migration_user` CHANGE createdAt created_at DATETIME NOT NULL, CHANGE updatedAt updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `notification` CHANGE createdAt created_at DATETIME NOT NULL, CHANGE updatedAt updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `provider` CHANGE createdAt created_at DATETIME NOT NULL, CHANGE updatedAt updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `request` CHANGE createdAt created_at DATETIME NOT NULL, CHANGE updatedAt updated_at DATETIME NOT NULL, CHANGE deletedat deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE `state` CHANGE createdAt created_at DATETIME NOT NULL, CHANGE updatedAt updated_at DATETIME NOT NULL, CHANGE searchpending search_pending TINYINT(1) NOT NULL, CHANGE deletedat deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE `template` CHANGE createdAt created_at DATETIME NOT NULL, CHANGE updatedAt updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `ticket` CHANGE createdAt created_at DATETIME NOT NULL, CHANGE updatedAt updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `ticket_category` CHANGE createdAt created_at DATETIME NOT NULL, CHANGE updatedAt updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `ticket_priority` CHANGE createdAt created_at DATETIME NOT NULL, CHANGE updatedAt updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `ticket_state` CHANGE createdAt created_at DATETIME NOT NULL, CHANGE updatedAt updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `ticket_type_state` CHANGE createdAt created_at DATETIME NOT NULL, CHANGE updatedAt updated_at DATETIME NOT NULL, CHANGE typestate type_state VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE `user` CHANGE downloadAuth download_auth TINYINT(1) NOT NULL, CHANGE wrongemail wrong_email TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE notification CHANGE viewedat viewed_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE `thread` CHANGE createdAt created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE thread DROP FOREIGN KEY FK_31204C833174800F; DROP INDEX idx_created_by ON thread');
        $this->addSql('ALTER TABLE thread CHANGE createdby_id created_by_id INT default null, CHANGE isspam is_spam TINYINT(1) NOT null');
        $this->addSql('ALTER TABLE thread ADD CONSTRAINT FK_31204C83B03A8386 FOREIGN KEY(created_by_id) REFERENCES user(id); CREATE INDEX IDX_31204C83B03A8386 ON thread (created_by_id)');
        $this->addSql('RENAME TABLE AccessToken TO access_token, RefreshToken to refresh_token, AuthCode to auth_code');
        $this->addSql('ALTER TABLE access_token RENAME INDEX uniq_b39617f55f37a13b TO UNIQ_B6A2DD685F37A13B');
        $this->addSql('ALTER TABLE access_token RENAME INDEX idx_b39617f519eb6921 TO IDX_B6A2DD6819EB6921');
        $this->addSql('ALTER TABLE access_token RENAME INDEX idx_b39617f5a76ed395 TO IDX_B6A2DD68A76ED395');
        $this->addSql('ALTER TABLE Client RENAME INDEX idx_c0e801633a51721d TO IDX_C74404553A51721D');
        $this->addSql('ALTER TABLE refresh_token RENAME INDEX uniq_7142379e5f37a13b TO UNIQ_C74F21955F37A13B');
        $this->addSql('ALTER TABLE refresh_token RENAME INDEX idx_7142379e19eb6921 TO IDX_C74F219519EB6921');
        $this->addSql('ALTER TABLE refresh_token RENAME INDEX idx_7142379ea76ed395 TO IDX_C74F2195A76ED395');
        $this->addSql('ALTER TABLE auth_code RENAME INDEX uniq_f1d7d1775f37a13b TO UNIQ_5933D02C5F37A13B');
        $this->addSql('ALTER TABLE auth_code RENAME INDEX idx_f1d7d17719eb6921 TO IDX_5933D02C19EB6921');
        $this->addSql('ALTER TABLE auth_code RENAME INDEX idx_f1d7d177a76ed395 TO IDX_5933D02CA76ED395');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `instance` CHANGE created_at createdAt DATETIME NOT NULL, CHANGE updated_at updatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `order` CHANGE created_at createdAt DATETIME NOT NULL, CHANGE updated_at updatedAt DATETIME NOT NULL, CHANGE deleted_at deletedat DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE `news` CHANGE created_at createdAt DATETIME NOT NULL, CHANGE updated_at updatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `configuration` CHANGE created_at createdAt DATETIME NOT NULL, CHANGE updated_at updatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `login` CHANGE created_at createdAt DATETIME NOT NULL, CHANGE updated_at updatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `user` CHANGE created_at createdAt DATETIME NOT NULL, CHANGE updated_at updatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `catalog` CHANGE created_at createdAt DATETIME NOT NULL, CHANGE updated_at updatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `catalog_position` CHANGE created_at createdAt DATETIME NOT NULL, CHANGE updated_at updatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `catalog_result` CHANGE created_at createdAt DATETIME NOT NULL, CHANGE updated_at updatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `city` CHANGE created_at createdAt DATETIME NOT NULL, CHANGE updated_at updatedAt DATETIME NOT NULL, CHANGE postal_code postalcode VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE `contact` CHANGE created_at createdAt DATETIME NOT NULL, CHANGE updated_at updatedAt DATETIME NOT NULL, CHANGE deleted_at deletedat DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE `contact_type` CHANGE created_at createdAt DATETIME NOT NULL, CHANGE updated_at updatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `counter` CHANGE created_at createdAt DATETIME NOT NULL, CHANGE updated_at updatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `country` CHANGE created_at createdAt DATETIME NOT NULL, CHANGE updated_at updatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `custom_field` CHANGE created_at createdAt DATETIME NOT NULL, CHANGE updated_at updatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `custom_value` CHANGE created_at createdAt DATETIME NOT NULL, CHANGE updated_at updatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `data_request` CHANGE created_at createdAt DATETIME NOT NULL, CHANGE updated_at updatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `email` CHANGE created_at createdAt DATETIME NOT NULL, CHANGE updated_at updatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `event` CHANGE created_at createdAt              DATETIME NOT NULL, CHANGE updated_at updatedAt DATETIME NOT NULL, CHANGE deleted_at deletedat DATETIME DEFAULT NULL, CHANGE cancelled_by_user cancelledbyuser TINYINT(1) DEFAULT NULL, CHANGE delivery_type deliverytype VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE `file` CHANGE created_at createdAt DATETIME NOT NULL, CHANGE updated_at updatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `file_download` CHANGE created_at createdAt DATETIME NOT NULL, CHANGE updated_at updatedAt DATETIME NOT NULL, CHANGE user_agent useragent LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE `hive` CHANGE created_at createdAt DATETIME NOT NULL, CHANGE updated_at updatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `journal` CHANGE created_at createdAt DATETIME NOT NULL, CHANGE updated_at updatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `material_type` CHANGE created_at createdAt DATETIME NOT NULL, CHANGE updated_at updatedAt DATETIME NOT NULL, CHANGE deleted_at deletedAt DATETIME DEFAULT NULL, CHANGE start_page startPage INT DEFAULT NULL, CHANGE end_page endPage INT DEFAULT NULL, CHANGE with_index withindex TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE `metadata` CHANGE created_at createdAt DATETIME NOT NULL, CHANGE updated_at updatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `migration` CHANGE created_at createdAt DATETIME NOT NULL, CHANGE updated_at updatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `migration_user` CHANGE created_at createdAt DATETIME NOT NULL, CHANGE updated_at updatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `notification` CHANGE created_at createdAt DATETIME NOT NULL, CHANGE updated_at updatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `provider` CHANGE created_at createdAt DATETIME NOT NULL, CHANGE updated_at updatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `request` CHANGE created_at createdAt DATETIME NOT NULL, CHANGE updated_at updatedAt DATETIME NOT NULL, CHANGE deleted_at deletedat DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE `state` CHANGE created_at createdAt DATETIME NOT NULL, CHANGE updated_at updatedAt DATETIME NOT NULL, CHANGE search_pending searchpending TINYINT(1) NOT NULL, CHANGE deleted_at deletedat DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE `template` CHANGE created_at createdAt DATETIME NOT NULL, CHANGE updated_at updatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `ticket` CHANGE created_at createdAt DATETIME NOT NULL, CHANGE updated_at updatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `ticket_category` CHANGE created_at createdAt DATETIME NOT NULL, CHANGE updated_at updatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `ticket_priority` CHANGE created_at createdAt DATETIME NOT NULL, CHANGE updated_at updatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `ticket_state` CHANGE created_at createdAt DATETIME NOT NULL, CHANGE updated_at updatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `ticket_type_state` CHANGE created_at createdAt DATETIME NOT NULL, CHANGE updated_at updatedAt DATETIME NOT NULL, CHANGE type_state typestate VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE `user` CHANGE download_auth downloadAuth TINYINT(1) NOT NULL, CHANGE wrong_email wrongemail TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE notification CHANGE viewed_at viewedat DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE `thread` CHANGE created_at createdAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE thread DROP FOREIGN KEY FK_31204C83B03A8386; DROP INDEX IDX_31204C83B03A8386 ON thread');
        $this->addSql('ALTER TABLE thread CHANGE created_by_id createdby_id INT default null, CHANGE is_spam isspam TINYINT(1) NOT null');
        $this->addSql('ALTER TABLE thread ADD CONSTRAINT FK_31204C833174800F FOREIGN KEY(createdby_id) REFERENCES user(id); CREATE INDEX idx_created_by ON thread (createdby_id)');
        $this->addSql('RENAME TABLE access_token TO AccessToken, refresh_token TO RefreshToken, auth_code TO AuthCode');
        $this->addSql('ALTER TABLE AccessToken RENAME INDEX UNIQ_B6A2DD685F37A13B TO uniq_b39617f55f37a13b');
        $this->addSql('ALTER TABLE AccessToken RENAME INDEX IDX_B6A2DD6819EB6921 TO idx_b39617f519eb6921');
        $this->addSql('ALTER TABLE AccessToken RENAME INDEX IDX_B6A2DD68A76ED395 TO idx_b39617f5a76ed395');
        $this->addSql('ALTER TABLE Client RENAME INDEX IDX_C74404553A51721D TO idx_c0e801633a51721d');
        $this->addSql('ALTER TABLE RefreshToken RENAME INDEX UNIQ_C74F21955F37A13B TO uniq_7142379e5f37a13b');
        $this->addSql('ALTER TABLE RefreshToken RENAME INDEX IDX_C74F219519EB6921 TO idx_7142379e19eb6921');
        $this->addSql('ALTER TABLE RefreshToken RENAME INDEX IDX_C74F2195A76ED395 TO idx_7142379ea76ed395');
        $this->addSql('ALTER TABLE AuthCode RENAME INDEX UNIQ_5933D02C5F37A13B TO uniq_f1d7d1775f37a13b');
        $this->addSql('ALTER TABLE AuthCode RENAME INDEX IDX_5933D02C19EB6921 TO idx_f1d7d17719eb6921');
        $this->addSql('ALTER TABLE AuthCode RENAME INDEX IDX_5933D02CA76ED395 TO idx_f1d7d177a76ed395');
    }
}
