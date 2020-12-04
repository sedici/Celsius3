ALTER TABLE `instance`
    CHANGE createdAt created_at DATETIME NOT NULL,
    CHANGE updatedAt updated_at DATETIME NOT NULL;
ALTER TABLE `order`
    CHANGE createdAt created_at DATETIME NOT NULL,
    CHANGE updatedAt updated_at DATETIME NOT NULL,
    CHANGE deletedat deleted_at DATETIME DEFAULT NULL;
ALTER TABLE `news`
    CHANGE createdAt created_at DATETIME NOT NULL,
    CHANGE updatedAt updated_at DATETIME NOT NULL;
ALTER TABLE `configuration`
    CHANGE createdAt created_at DATETIME NOT NULL,
    CHANGE updatedAt updated_at DATETIME NOT NULL;
ALTER TABLE `login`
    CHANGE createdAt created_at DATETIME NOT NULL,
    CHANGE updatedAt updated_at DATETIME NOT NULL;
ALTER TABLE `user`
    CHANGE createdAt created_at DATETIME NOT NULL,
    CHANGE updatedAt updated_at DATETIME NOT NULL;
ALTER TABLE `catalog`
    CHANGE createdAt created_at DATETIME NOT NULL,
    CHANGE updatedAt updated_at DATETIME NOT NULL;
ALTER TABLE `catalog_position`
    CHANGE createdAt created_at DATETIME NOT NULL,
    CHANGE updatedAt updated_at DATETIME NOT NULL;
ALTER TABLE `catalog_result`
    CHANGE createdAt created_at DATETIME NOT NULL,
    CHANGE updatedAt updated_at DATETIME NOT NULL;
ALTER TABLE `city`
    CHANGE createdAt created_at DATETIME NOT NULL,
    CHANGE updatedAt updated_at DATETIME NOT NULL,
    CHANGE postalcode postal_code VARCHAR(255) DEFAULT NULL;
ALTER TABLE `contact`
    CHANGE createdAt created_at DATETIME NOT NULL,
    CHANGE updatedAt updated_at DATETIME NOT NULL,
    CHANGE deletedat deleted_at DATETIME DEFAULT NULL;
ALTER TABLE `contact_type`
    CHANGE createdAt created_at DATETIME NOT NULL,
    CHANGE updatedAt updated_at DATETIME NOT NULL;
ALTER TABLE `counter`
    CHANGE createdAt created_at DATETIME NOT NULL,
    CHANGE updatedAt updated_at DATETIME NOT NULL;
ALTER TABLE `country`
    CHANGE createdAt created_at DATETIME NOT NULL,
    CHANGE updatedAt updated_at DATETIME NOT NULL;
ALTER TABLE `custom_field`
    CHANGE createdAt created_at DATETIME NOT NULL,
    CHANGE updatedAt updated_at DATETIME NOT NULL;
ALTER TABLE `custom_value`
    CHANGE createdAt created_at DATETIME NOT NULL,
    CHANGE updatedAt updated_at DATETIME NOT NULL;
ALTER TABLE `data_request`
    CHANGE createdAt created_at DATETIME NOT NULL,
    CHANGE updatedAt updated_at DATETIME NOT NULL;
ALTER TABLE `email`
    CHANGE createdAt created_at DATETIME NOT NULL,
    CHANGE updatedAt updated_at DATETIME NOT NULL;
ALTER TABLE `event`
    CHANGE createdAt created_at              DATETIME NOT NULL,
    CHANGE updatedAt updated_at              DATETIME NOT NULL,
    CHANGE deletedat deleted_at              DATETIME     DEFAULT NULL,
    CHANGE cancelledbyuser cancelled_by_user TINYINT(1)   DEFAULT NULL,
    CHANGE deliverytype delivery_type        VARCHAR(255) DEFAULT NULL;
ALTER TABLE `file`
    CHANGE createdAt created_at DATETIME NOT NULL,
    CHANGE updatedAt updated_at DATETIME NOT NULL;
ALTER TABLE `file_download`
    CHANGE createdAt created_at DATETIME NOT NULL,
    CHANGE updatedAt updated_at DATETIME NOT NULL,
    CHANGE useragent user_agent LONGTEXT DEFAULT NULL;
ALTER TABLE `hive`
    CHANGE createdAt created_at DATETIME NOT NULL,
    CHANGE updatedAt updated_at DATETIME NOT NULL;
ALTER TABLE `journal`
    CHANGE createdAt created_at DATETIME NOT NULL,
    CHANGE updatedAt updated_at DATETIME NOT NULL;
ALTER TABLE `material_type`
    CHANGE createdAt created_at DATETIME NOT NULL,
    CHANGE updatedAt updated_at DATETIME NOT NULL,
    CHANGE deletedAt deleted_at DATETIME   DEFAULT NULL,
    CHANGE startPage start_page INT        DEFAULT NULL,
    CHANGE endPage end_page     INT        DEFAULT NULL,
    CHANGE withindex with_index TINYINT(1) DEFAULT NULL;
ALTER TABLE `metadata`
    CHANGE createdAt created_at DATETIME NOT NULL,
    CHANGE updatedAt updated_at DATETIME NOT NULL;
ALTER TABLE `migration`
    CHANGE createdAt created_at DATETIME NOT NULL,
    CHANGE updatedAt updated_at DATETIME NOT NULL;
ALTER TABLE `migration_user`
    CHANGE createdAt created_at DATETIME NOT NULL,
    CHANGE updatedAt updated_at DATETIME NOT NULL;
ALTER TABLE `notification`
    CHANGE createdAt created_at DATETIME NOT NULL,
    CHANGE updatedAt updated_at DATETIME NOT NULL;
ALTER TABLE `provider`
    CHANGE createdAt created_at DATETIME NOT NULL,
    CHANGE updatedAt updated_at DATETIME NOT NULL;
ALTER TABLE `request`
    CHANGE createdAt created_at DATETIME NOT NULL,
    CHANGE updatedAt updated_at DATETIME NOT NULL,
    CHANGE deletedat deleted_at DATETIME DEFAULT NULL;
ALTER TABLE `state`
    CHANGE createdAt created_at DATETIME NOT NULL,
    CHANGE updatedAt updated_at DATETIME NOT NULL,
    CHANGE searchpending search_pending TINYINT(1) NOT NULL,
    CHANGE deletedat deleted_at DATETIME DEFAULT NULL;
ALTER TABLE `template`
    CHANGE createdAt created_at DATETIME NOT NULL,
    CHANGE updatedAt updated_at DATETIME NOT NULL;
ALTER TABLE `ticket`
    CHANGE createdAt created_at DATETIME NOT NULL,
    CHANGE updatedAt updated_at DATETIME NOT NULL;
ALTER TABLE `ticket_category`
    CHANGE createdAt created_at DATETIME NOT NULL,
    CHANGE updatedAt updated_at DATETIME NOT NULL;
ALTER TABLE `ticket_priority`
    CHANGE createdAt created_at DATETIME NOT NULL,
    CHANGE updatedAt updated_at DATETIME NOT NULL;
ALTER TABLE `ticket_state`
    CHANGE createdAt created_at DATETIME NOT NULL,
    CHANGE updatedAt updated_at DATETIME NOT NULL;
ALTER TABLE `ticket_type_state`
    CHANGE createdAt created_at DATETIME NOT NULL,
    CHANGE updatedAt updated_at DATETIME NOT NULL,
    CHANGE typestate type_state VARCHAR(255) NOT NULL;
ALTER TABLE `user`
    CHANGE downloadAuth download_auth TINYINT(1) NOT NULL,
    CHANGE wrongemail wrong_email     TINYINT(1) NOT NULL;
ALTER TABLE notification
    CHANGE viewedat viewed_at DATETIME DEFAULT NULL;

ALTER TABLE `thread`
    CHANGE createdAt created_at DATETIME NOT NULL;
ALTER TABLE thread
    DROP FOREIGN KEY FK_31204C833174800F;
DROP INDEX idx_created_by ON thread;
ALTER TABLE thread
    CHANGE createdby_id created_by_id INT DEFAULT NULL,
    CHANGE isspam is_spam             TINYINT(1) NOT NULL;
ALTER TABLE thread
    ADD CONSTRAINT FK_31204C83B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id);
CREATE INDEX IDX_31204C83B03A8386 ON thread (created_by_id);


RENAME TABLE AccessToken TO access_token,
    RefreshToken to refresh_token,
    AuthCode to auth_code;

ALTER TABLE access_token RENAME INDEX uniq_b39617f55f37a13b TO UNIQ_B6A2DD685F37A13B;
ALTER TABLE access_token RENAME INDEX idx_b39617f519eb6921 TO IDX_B6A2DD6819EB6921;
ALTER TABLE access_token RENAME INDEX idx_b39617f5a76ed395 TO IDX_B6A2DD68A76ED395;
ALTER TABLE Client RENAME INDEX idx_c0e801633a51721d TO IDX_C74404553A51721D;
ALTER TABLE refresh_token RENAME INDEX uniq_7142379e5f37a13b TO UNIQ_C74F21955F37A13B;
ALTER TABLE refresh_token RENAME INDEX idx_7142379e19eb6921 TO IDX_C74F219519EB6921;
ALTER TABLE refresh_token RENAME INDEX idx_7142379ea76ed395 TO IDX_C74F2195A76ED395;
ALTER TABLE auth_code RENAME INDEX uniq_f1d7d1775f37a13b TO UNIQ_5933D02C5F37A13B;
ALTER TABLE auth_code RENAME INDEX idx_f1d7d17719eb6921 TO IDX_5933D02C19EB6921;
ALTER TABLE auth_code RENAME INDEX idx_f1d7d177a76ed395 TO IDX_5933D02CA76ED395;





