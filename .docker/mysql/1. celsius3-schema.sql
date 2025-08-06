SET FOREIGN_KEY_CHECKS=0;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `access_token` (
  `id` int NOT NULL AUTO_INCREMENT,
  `client_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B6A2DD6819EB6921` (`client_id`),
  KEY `IDX_B6A2DD68A76ED395` (`user_id`),
  CONSTRAINT `FK_B39617F519EB6921` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`),
  CONSTRAINT `FK_B39617F5A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1312 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `approves_files` (
  `event_id` int NOT NULL,
  `file_id` int NOT NULL,
  PRIMARY KEY (`event_id`,`file_id`),
  UNIQUE KEY `UNIQ_23888D7793CB796C` (`file_id`),
  KEY `IDX_23888D7771F7E88B` (`event_id`),
  CONSTRAINT `FK_23888D7771F7E88B` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
  CONSTRAINT `FK_23888D7793CB796C` FOREIGN KEY (`file_id`) REFERENCES `file` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auth_code` (
  `id` int NOT NULL AUTO_INCREMENT,
  `client_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5933D02C19EB6921` (`client_id`),
  KEY `IDX_5933D02CA76ED395` (`user_id`),
  CONSTRAINT `FK_F1D7D17719EB6921` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`),
  CONSTRAINT `FK_F1D7D177A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=429 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `catalog` (
  `id` int NOT NULL AUTO_INCREMENT,
  `institution_id` int DEFAULT NULL,
  `instance_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `url` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `comments` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_name` (`name`),
  KEY `idx_url` (`url`),
  KEY `idx_institution` (`institution_id`),
  KEY `idx_instance` (`instance_id`),
  CONSTRAINT `FK_1B2C324710405986` FOREIGN KEY (`institution_id`) REFERENCES `provider` (`id`),
  CONSTRAINT `FK_1B2C32473A51721D` FOREIGN KEY (`instance_id`) REFERENCES `instance` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1019 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `catalog_position` (
  `id` int NOT NULL AUTO_INCREMENT,
  `catalog_id` int NOT NULL,
  `instance_id` int NOT NULL,
  `position` int NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_position` (`position`),
  KEY `idx_catalog` (`catalog_id`),
  KEY `idx_instance` (`instance_id`),
  CONSTRAINT `FK_74FB3AEB3A51721D` FOREIGN KEY (`instance_id`) REFERENCES `instance` (`id`),
  CONSTRAINT `FK_74FB3AEBCC3C66FC` FOREIGN KEY (`catalog_id`) REFERENCES `catalog` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2231 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `catalog_result` (
  `id` int NOT NULL AUTO_INCREMENT,
  `catalog_id` int NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `searches` int NOT NULL,
  `matches` int NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_title` (`title`),
  KEY `idx_catalog` (`catalog_id`),
  KEY `idx_title_catalog` (`catalog_id`,`title`),
  CONSTRAINT `FK_A16ED95ACC3C66FC` FOREIGN KEY (`catalog_id`) REFERENCES `catalog` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=215886 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `city` (
  `id` int NOT NULL AUTO_INCREMENT,
  `country_id` int NOT NULL,
  `instance_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `postal_code` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_name` (`name`),
  KEY `idx_country` (`country_id`),
  KEY `idx_instance` (`instance_id`),
  CONSTRAINT `FK_2D5B02343A51721D` FOREIGN KEY (`instance_id`) REFERENCES `instance` (`id`),
  CONSTRAINT `FK_2D5B0234F92F3E70` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=369 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `client` (
  `id` int NOT NULL AUTO_INCREMENT,
  `instance_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C74404553A51721D` (`instance_id`),
  CONSTRAINT `FK_C0E801633A51721D` FOREIGN KEY (`instance_id`) REFERENCES `instance` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `configuration` (
  `id` int NOT NULL AUTO_INCREMENT,
  `instance_id` int NOT NULL,
  `key` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `value` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `type` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_idx` (`key`,`instance_id`),
  KEY `idx_key` (`key`),
  KEY `idx_instance` (`instance_id`),
  CONSTRAINT `FK_A5E2A5D73A51721D` FOREIGN KEY (`instance_id`) REFERENCES `instance` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2032 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `type_id` int NOT NULL,
  `instance_id` int DEFAULT NULL,
  `institution_id` int NOT NULL,
  `owning_instance_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `surname` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `address` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_4C62E638A76ED395` (`user_id`),
  KEY `IDX_4C62E638C54C8C93` (`type_id`),
  KEY `IDX_4C62E6383A51721D` (`instance_id`),
  KEY `idx_name` (`name`),
  KEY `idx_surname` (`surname`),
  KEY `idx_email` (`email`),
  KEY `idx_user` (`user_id`),
  KEY `idx_institution` (`institution_id`),
  KEY `idx_owning_instance` (`owning_instance_id`),
  CONSTRAINT `FK_4C62E63810405986` FOREIGN KEY (`institution_id`) REFERENCES `provider` (`id`),
  CONSTRAINT `FK_4C62E63834F938CE` FOREIGN KEY (`owning_instance_id`) REFERENCES `instance` (`id`),
  CONSTRAINT `FK_4C62E6383A51721D` FOREIGN KEY (`instance_id`) REFERENCES `instance` (`id`),
  CONSTRAINT `FK_4C62E638A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_4C62E638C54C8C93` FOREIGN KEY (`type_id`) REFERENCES `contact_type` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_type` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `counter` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `value` int NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `country` (
  `id` int NOT NULL AUTO_INCREMENT,
  `instance_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `abbreviation` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_5373C9665E237E06` (`name`),
  UNIQUE KEY `UNIQ_5373C966BCF3411D` (`abbreviation`),
  KEY `idx_name` (`name`),
  KEY `idx_instance` (`instance_id`),
  CONSTRAINT `FK_5373C9663A51721D` FOREIGN KEY (`instance_id`) REFERENCES `instance` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `custom_field` (
  `id` int NOT NULL AUTO_INCREMENT,
  `instance_id` int NOT NULL,
  `key` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `private` tinyint(1) NOT NULL,
  `required` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `value` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `enabled` tinyint(1) NOT NULL,
  `position` int DEFAULT NULL,
  `entity` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_idx` (`key`,`instance_id`),
  KEY `idx_key` (`key`),
  KEY `idx_name` (`name`),
  KEY `idx_instance` (`instance_id`),
  CONSTRAINT `FK_86C882AA3A51721D` FOREIGN KEY (`instance_id`) REFERENCES `instance` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `custom_value` (
  `id` int NOT NULL AUTO_INCREMENT,
  `field_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `value` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `contact_id` int DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_field` (`field_id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_contact` (`contact_id`),
  CONSTRAINT `FK_C04A9FC6443707B0` FOREIGN KEY (`field_id`) REFERENCES `custom_field` (`id`),
  CONSTRAINT `FK_C04A9FC6A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_DE7AA05DE7A1254A` FOREIGN KEY (`contact_id`) REFERENCES `contact` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25669 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `data_request` (
  `id` int NOT NULL AUTO_INCREMENT,
  `instance_id` int DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `data` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `exported` tinyint(1) NOT NULL,
  `downloaded` tinyint(1) NOT NULL,
  `file` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `visible` tinyint(1) NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5F9CD96E3A51721D` (`instance_id`),
  CONSTRAINT `FK_5F9CD96E3A51721D` FOREIGN KEY (`instance_id`) REFERENCES `instance` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `email` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sender_id` int NOT NULL,
  `instance_id` int NOT NULL,
  `address` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `text` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `sent` tinyint(1) NOT NULL,
  `attempts` int NOT NULL,
  `error` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_E7927C74F624B39D` (`sender_id`),
  KEY `IDX_E7927C743A51721D` (`instance_id`),
  CONSTRAINT `FK_E7927C743A51721D` FOREIGN KEY (`instance_id`) REFERENCES `instance` (`id`),
  CONSTRAINT `FK_E7927C74F624B39D` FOREIGN KEY (`sender_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=55671 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `event` (
  `id` int NOT NULL AUTO_INCREMENT,
  `request_id` int NOT NULL,
  `operator_id` int DEFAULT NULL,
  `state_id` int NOT NULL,
  `instance_id` int NOT NULL,
  `catalog_id` int DEFAULT NULL,
  `provider_id` int DEFAULT NULL,
  `request_event_id` int DEFAULT NULL,
  `receive_event_id` int DEFAULT NULL,
  `observations` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `result` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `delivery_type` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `remote_state_id` int DEFAULT NULL,
  `remote_request_id` int DEFAULT NULL,
  `remote_instance_id` int DEFAULT NULL,
  `cancelled_by_user` tinyint(1) DEFAULT NULL,
  `reclaimed` tinyint(1) DEFAULT NULL,
  `cancelled` tinyint(1) DEFAULT NULL,
  `approved` tinyint(1) DEFAULT NULL,
  `annulled` tinyint(1) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_3BAE0AA7CC3C66FC` (`catalog_id`),
  KEY `IDX_3BAE0AA7A53A8AA` (`provider_id`),
  KEY `IDX_3BAE0AA784D208AE` (`request_event_id`),
  KEY `IDX_3BAE0AA7B385DA0` (`receive_event_id`),
  KEY `IDX_3BAE0AA7D64A438D` (`remote_state_id`),
  KEY `IDX_3BAE0AA7DE080793` (`remote_request_id`),
  KEY `IDX_3BAE0AA7F577913B` (`remote_instance_id`),
  KEY `idx_type` (`type`),
  KEY `idx_request` (`request_id`),
  KEY `idx_operator` (`operator_id`),
  KEY `idx_state` (`state_id`),
  KEY `idx_instance` (`instance_id`),
  CONSTRAINT `FK_3BAE0AA73A51721D` FOREIGN KEY (`instance_id`) REFERENCES `instance` (`id`),
  CONSTRAINT `FK_3BAE0AA7427EB8A5` FOREIGN KEY (`request_id`) REFERENCES `request` (`id`),
  CONSTRAINT `FK_3BAE0AA7584598A3` FOREIGN KEY (`operator_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_3BAE0AA75D83CC1` FOREIGN KEY (`state_id`) REFERENCES `state` (`id`),
  CONSTRAINT `FK_3BAE0AA784D208AE` FOREIGN KEY (`request_event_id`) REFERENCES `event` (`id`),
  CONSTRAINT `FK_3BAE0AA7A53A8AA` FOREIGN KEY (`provider_id`) REFERENCES `provider` (`id`),
  CONSTRAINT `FK_3BAE0AA7B385DA0` FOREIGN KEY (`receive_event_id`) REFERENCES `event` (`id`),
  CONSTRAINT `FK_3BAE0AA7CC3C66FC` FOREIGN KEY (`catalog_id`) REFERENCES `catalog` (`id`),
  CONSTRAINT `FK_3BAE0AA7D64A438D` FOREIGN KEY (`remote_state_id`) REFERENCES `state` (`id`),
  CONSTRAINT `FK_3BAE0AA7DE080793` FOREIGN KEY (`remote_request_id`) REFERENCES `request` (`id`),
  CONSTRAINT `FK_3BAE0AA7F577913B` FOREIGN KEY (`remote_instance_id`) REFERENCES `instance` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=719073 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ext_log_entries` (
  `id` int NOT NULL AUTO_INCREMENT,
  `action` varchar(8) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `logged_at` datetime NOT NULL,
  `object_id` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `object_class` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `version` int NOT NULL,
  `data` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci COMMENT '(DC2Type:array)',
  `username` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `log_class_lookup_idx` (`object_class`),
  KEY `log_date_lookup_idx` (`logged_at`),
  KEY `log_user_lookup_idx` (`username`),
  KEY `log_version_lookup_idx` (`object_id`,`object_class`,`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ext_translations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `locale` varchar(8) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `object_class` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `field` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `foreign_key` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `content` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lookup_unique_idx` (`locale`,`object_class`,`field`,`foreign_key`),
  KEY `translations_lookup_idx` (`locale`,`object_class`,`foreign_key`),
  KEY `general_translations_lookup_idx` (`object_class`,`foreign_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `file` (
  `id` int NOT NULL AUTO_INCREMENT,
  `request_id` int DEFAULT NULL,
  `instance_id` int DEFAULT NULL,
  `event_id` int DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `path` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `comments` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `enabled` tinyint(1) NOT NULL,
  `downloaded` tinyint(1) NOT NULL,
  `pages` int NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_event` (`event_id`),
  KEY `idx_request` (`request_id`),
  KEY `idx_instance` (`instance_id`),
  CONSTRAINT `FK_8C9F36103A51721D` FOREIGN KEY (`instance_id`) REFERENCES `instance` (`id`),
  CONSTRAINT `FK_8C9F3610427EB8A5` FOREIGN KEY (`request_id`) REFERENCES `request` (`id`),
  CONSTRAINT `FK_8C9F361071F7E88B` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=69887 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `file_download` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `file_id` int NOT NULL,
  `request_id` int NOT NULL,
  `instance_id` int NOT NULL,
  `ip` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `user_agent` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C94A0DED93CB796C` (`file_id`),
  KEY `idx_ip` (`ip`),
  KEY `idx_request` (`request_id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_instance` (`instance_id`),
  CONSTRAINT `FK_C94A0DED3A51721D` FOREIGN KEY (`instance_id`) REFERENCES `instance` (`id`),
  CONSTRAINT `FK_C94A0DED427EB8A5` FOREIGN KEY (`request_id`) REFERENCES `request` (`id`),
  CONSTRAINT `FK_C94A0DED93CB796C` FOREIGN KEY (`file_id`) REFERENCES `file` (`id`),
  CONSTRAINT `FK_C94A0DEDA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=50352 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hive` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `instance` (
  `id` int NOT NULL AUTO_INCREMENT,
  `hive_id` int DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `abbreviation` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `website` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `url` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `host` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `invisible` tinyint(1) DEFAULT NULL,
  `latitud` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `longitud` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `observaciones` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_4230B1DEF47645AE` (`url`),
  UNIQUE KEY `UNIQ_4230B1DECF2713FD` (`host`),
  KEY `idx_name` (`name`),
  KEY `idx_website` (`website`),
  KEY `idx_url` (`url`),
  KEY `idx_type` (`type`),
  KEY `idx_hive` (`hive_id`),
  CONSTRAINT `FK_4230B1DEE9A48D12` FOREIGN KEY (`hive_id`) REFERENCES `hive` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `journal` (
  `id` int NOT NULL AUTO_INCREMENT,
  `instance_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `abbreviation` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `responsible` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `ISSN` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `ISSNE` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `frecuency` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_name` (`name`),
  KEY `idx_abbreviation` (`abbreviation`),
  KEY `idx_instance` (`instance_id`),
  CONSTRAINT `FK_C1A7E74D3A51721D` FOREIGN KEY (`instance_id`) REFERENCES `instance` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28532 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `librarian_institution` (
  `user_id` int NOT NULL,
  `institution_id` int NOT NULL,
  PRIMARY KEY (`user_id`,`institution_id`),
  KEY `IDX_73E3D2FEA76ED395` (`user_id`),
  KEY `IDX_73E3D2FE10405986` (`institution_id`),
  CONSTRAINT `FK_73E3D2FE10405986` FOREIGN KEY (`institution_id`) REFERENCES `provider` (`id`),
  CONSTRAINT `FK_73E3D2FEA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `login` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `message` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_category` (`category`)
) ENGINE=InnoDB AUTO_INCREMENT=230504 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `material_type` (
  `id` int NOT NULL AUTO_INCREMENT,
  `journal_id` int DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `authors` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `year` int NOT NULL,
  `start_page` int DEFAULT NULL,
  `end_page` int DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `volume` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `number` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `other` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `editor` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `chapter` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `ISBN` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `with_index` tinyint(1) DEFAULT NULL,
  `place` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `communication` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `director` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `degree` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `article` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `month` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `day` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D8B63A1C478E8802` (`journal_id`),
  KEY `idx_type` (`type`),
  KEY `idx_title` (`title`),
  CONSTRAINT `FK_D8B63A1C478E8802` FOREIGN KEY (`journal_id`) REFERENCES `journal` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=101573 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `message` (
  `id` int NOT NULL AUTO_INCREMENT,
  `thread_id` int NOT NULL,
  `sender_id` int NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_thread` (`thread_id`),
  KEY `idx_sender` (`sender_id`),
  CONSTRAINT `FK_B6BD307FE2904019` FOREIGN KEY (`thread_id`) REFERENCES `thread` (`id`),
  CONSTRAINT `FK_B6BD307FF624B39D` FOREIGN KEY (`sender_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1056 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `message_metadata` (
  `id` int NOT NULL AUTO_INCREMENT,
  `message_id` int DEFAULT NULL,
  `participant_id` int DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_message` (`message_id`),
  KEY `idx_participant` (`participant_id`),
  KEY `idx_read` (`is_read`),
  CONSTRAINT `FK_4632F005537A1329` FOREIGN KEY (`message_id`) REFERENCES `message` (`id`),
  CONSTRAINT `FK_4632F0059D1C3019` FOREIGN KEY (`participant_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13430 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mirequests_files` (
  `event_id` int NOT NULL,
  `file_id` int NOT NULL,
  PRIMARY KEY (`event_id`,`file_id`),
  UNIQUE KEY `UNIQ_F60E931693CB796C` (`file_id`),
  KEY `IDX_F60E931671F7E88B` (`event_id`),
  CONSTRAINT `FK_F60E931671F7E88B` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
  CONSTRAINT `FK_F60E931693CB796C` FOREIGN KEY (`file_id`) REFERENCES `file` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `news` (
  `id` int NOT NULL AUTO_INCREMENT,
  `instance_id` int NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `text` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_title` (`title`),
  KEY `idx_date` (`date`),
  KEY `idx_instance` (`instance_id`),
  CONSTRAINT `FK_1DD399503A51721D` FOREIGN KEY (`instance_id`) REFERENCES `instance` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notification` (
  `id` int NOT NULL AUTO_INCREMENT,
  `template_id` int NOT NULL,
  `message_notification_id` int DEFAULT NULL,
  `base_user_notification_id` int DEFAULT NULL,
  `event_notification_id` int DEFAULT NULL,
  `cause` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `viewed` tinyint(1) NOT NULL,
  `viewed_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_template` (`template_id`),
  KEY `idx_object_user` (`base_user_notification_id`),
  KEY `idx_object_message` (`message_notification_id`),
  KEY `idx_object_event` (`event_notification_id`),
  KEY `idx_viewed` (`viewed`),
  CONSTRAINT `FK_BF5476CA26C9F9B4` FOREIGN KEY (`event_notification_id`) REFERENCES `event` (`id`),
  CONSTRAINT `FK_BF5476CA5366CAB7` FOREIGN KEY (`base_user_notification_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_BF5476CA5DA0FB8` FOREIGN KEY (`template_id`) REFERENCES `template` (`id`),
  CONSTRAINT `FK_BF5476CA9743C372` FOREIGN KEY (`message_notification_id`) REFERENCES `message` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=162950 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notification_receiver` (
  `notification_id` int NOT NULL,
  `base_user_id` int NOT NULL,
  PRIMARY KEY (`notification_id`,`base_user_id`),
  KEY `IDX_68A8B433EF1A9D84` (`notification_id`),
  KEY `IDX_68A8B43393686AF1` (`base_user_id`),
  CONSTRAINT `FK_68A8B43393686AF1` FOREIGN KEY (`base_user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_68A8B433EF1A9D84` FOREIGN KEY (`notification_id`) REFERENCES `notification` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notification_settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user` int DEFAULT NULL,
  `instance` int DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `subscribedToInterfaceNotifications` tinyint(1) NOT NULL,
  `subscribedToEmailNotifications` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B05598608D93D649` (`user`),
  KEY `IDX_B05598604230B1DE` (`instance`),
  CONSTRAINT `FK_B05598604230B1DE` FOREIGN KEY (`instance`) REFERENCES `instance` (`id`),
  CONSTRAINT `FK_B05598608D93D649` FOREIGN KEY (`user`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=261 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notification_viewer` (
  `notification_id` int NOT NULL,
  `base_user_id` int NOT NULL,
  PRIMARY KEY (`notification_id`,`base_user_id`),
  KEY `IDX_C7FB5208EF1A9D84` (`notification_id`),
  KEY `IDX_C7FB520893686AF1` (`base_user_id`),
  CONSTRAINT `FK_C7FB520893686AF1` FOREIGN KEY (`base_user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_C7FB5208EF1A9D84` FOREIGN KEY (`notification_id`) REFERENCES `notification` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order` (
  `id` int NOT NULL AUTO_INCREMENT,
  `material_data_id` int DEFAULT NULL,
  `original_request_id` int DEFAULT NULL,
  `code` int NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_F5299398866F7A93` (`material_data_id`),
  UNIQUE KEY `UNIQ_F52993982FC9CF31` (`original_request_id`),
  KEY `idx_code` (`code`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_material_data` (`material_data_id`),
  KEY `idx_original_request` (`original_request_id`),
  CONSTRAINT `FK_F52993982FC9CF31` FOREIGN KEY (`original_request_id`) REFERENCES `request` (`id`),
  CONSTRAINT `FK_F5299398866F7A93` FOREIGN KEY (`material_data_id`) REFERENCES `material_type` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=94361 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `provider` (
  `id` int NOT NULL AUTO_INCREMENT,
  `parent_id` int DEFAULT NULL,
  `city_id` int DEFAULT NULL,
  `country_id` int DEFAULT NULL,
  `instance_id` int DEFAULT NULL,
  `celsius_instance_id` int DEFAULT NULL,
  `hive_id` int DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `abbreviation` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `website` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_name` (`name`),
  KEY `idx_type` (`type`),
  KEY `idx_city` (`city_id`),
  KEY `idx_country` (`country_id`),
  KEY `idx_instance` (`instance_id`),
  KEY `idx_celsius_instance` (`celsius_instance_id`),
  KEY `idx_parent` (`parent_id`),
  KEY `idx_hive` (`hive_id`),
  CONSTRAINT `FK_92C4739C3A51721D` FOREIGN KEY (`instance_id`) REFERENCES `instance` (`id`),
  CONSTRAINT `FK_92C4739C4337DD9B` FOREIGN KEY (`celsius_instance_id`) REFERENCES `instance` (`id`),
  CONSTRAINT `FK_92C4739C727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `provider` (`id`),
  CONSTRAINT `FK_92C4739C8BAC62AF` FOREIGN KEY (`city_id`) REFERENCES `city` (`id`),
  CONSTRAINT `FK_92C4739CE9A48D12` FOREIGN KEY (`hive_id`) REFERENCES `hive` (`id`),
  CONSTRAINT `FK_92C4739CF92F3E70` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11216 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `refresh_token` (
  `id` int NOT NULL AUTO_INCREMENT,
  `client_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C74F219519EB6921` (`client_id`),
  KEY `IDX_C74F2195A76ED395` (`user_id`),
  CONSTRAINT `FK_7142379E19EB6921` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`),
  CONSTRAINT `FK_7142379EA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1312 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `request` (
  `id` int NOT NULL AUTO_INCREMENT,
  `owner_id` int NOT NULL,
  `creator_id` int NOT NULL,
  `librarian_id` int DEFAULT NULL,
  `instance_id` int NOT NULL,
  `operator_id` int DEFAULT NULL,
  `order_id` int NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `comments` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `previous_request_id` int DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_order_instance` (`instance_id`,`order_id`),
  KEY `idx_type` (`type`),
  KEY `idx_previous_request` (`previous_request_id`),
  KEY `idx_owner` (`owner_id`),
  KEY `idx_creator` (`creator_id`),
  KEY `idx_librarian` (`librarian_id`),
  KEY `idx_instance` (`instance_id`),
  KEY `idx_operator` (`operator_id`),
  KEY `idx_order` (`order_id`),
  CONSTRAINT `FK_3B978F9F357EFA1C` FOREIGN KEY (`previous_request_id`) REFERENCES `request` (`id`),
  CONSTRAINT `FK_3B978F9F3A51721D` FOREIGN KEY (`instance_id`) REFERENCES `instance` (`id`),
  CONSTRAINT `FK_3B978F9F584598A3` FOREIGN KEY (`operator_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_3B978F9F61220EA6` FOREIGN KEY (`creator_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_3B978F9F7E3C61F9` FOREIGN KEY (`owner_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_3B978F9F8D9F6D38` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`),
  CONSTRAINT `FK_3B978F9FD8B58D1F` FOREIGN KEY (`librarian_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=94253 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reset_password_request` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `selector` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `hashed_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `requested_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `expires_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_7CE748AA76ED395` (`user_id`),
  CONSTRAINT `FK_7CE748AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sirequests_files` (
  `event_id` int NOT NULL,
  `file_id` int NOT NULL,
  PRIMARY KEY (`event_id`,`file_id`),
  UNIQUE KEY `UNIQ_30AB5B2393CB796C` (`file_id`),
  KEY `IDX_30AB5B2371F7E88B` (`event_id`),
  CONSTRAINT `FK_30AB5B2371F7E88B` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
  CONSTRAINT `FK_30AB5B2393CB796C` FOREIGN KEY (`file_id`) REFERENCES `file` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `state` (
  `id` int NOT NULL AUTO_INCREMENT,
  `remote_event_id` int DEFAULT NULL,
  `instance_id` int NOT NULL,
  `previous_id` int DEFAULT NULL,
  `request_id` int NOT NULL,
  `operator_id` int DEFAULT NULL,
  `current` tinyint(1) NOT NULL,
  `search_pending` tinyint(1) NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_A393D2FBA26597C7` (`remote_event_id`),
  KEY `idx_type` (`type`),
  KEY `idx_previous` (`previous_id`),
  KEY `idx_request` (`request_id`),
  KEY `idx_instance` (`instance_id`),
  KEY `idx_operator` (`operator_id`),
  KEY `idx_current` (`current`),
  CONSTRAINT `FK_A393D2FB2DE62210` FOREIGN KEY (`previous_id`) REFERENCES `state` (`id`),
  CONSTRAINT `FK_A393D2FB3A51721D` FOREIGN KEY (`instance_id`) REFERENCES `instance` (`id`),
  CONSTRAINT `FK_A393D2FB427EB8A5` FOREIGN KEY (`request_id`) REFERENCES `request` (`id`),
  CONSTRAINT `FK_A393D2FB584598A3` FOREIGN KEY (`operator_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_A393D2FBA26597C7` FOREIGN KEY (`remote_event_id`) REFERENCES `event` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=427858 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `template` (
  `id` int NOT NULL AUTO_INCREMENT,
  `instance_id` int DEFAULT NULL,
  `code` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `text` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `enabled` tinyint(1) DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_code` (`code`),
  KEY `idx_title` (`title`),
  KEY `idx_instance` (`instance_id`),
  KEY `idx_type` (`type`),
  CONSTRAINT `FK_97601F833A51721D` FOREIGN KEY (`instance_id`) REFERENCES `instance` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=158 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `thread` (
  `id` int NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_created_at` (`created_at`),
  KEY `IDX_31204C83B03A8386` (`created_by_id`),
  CONSTRAINT `FK_31204C83B03A8386` FOREIGN KEY (`created_by_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=562 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `thread_metadata` (
  `id` int NOT NULL AUTO_INCREMENT,
  `thread_id` int DEFAULT NULL,
  `participant_id` int DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  `last_participant_message_date` datetime DEFAULT NULL,
  `last_message_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_thread` (`thread_id`),
  KEY `idx_participant` (`participant_id`),
  KEY `idx_last_message_date` (`last_message_date`),
  CONSTRAINT `FK_40A577C89D1C3019` FOREIGN KEY (`participant_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_40A577C8E2904019` FOREIGN KEY (`thread_id`) REFERENCES `thread` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6665 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `uploads_files` (
  `event_id` int NOT NULL,
  `file_id` int NOT NULL,
  PRIMARY KEY (`event_id`,`file_id`),
  UNIQUE KEY `UNIQ_931AF9593CB796C` (`file_id`),
  KEY `IDX_931AF9571F7E88B` (`event_id`),
  CONSTRAINT `FK_931AF9571F7E88B` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
  CONSTRAINT `FK_931AF9593CB796C` FOREIGN KEY (`file_id`) REFERENCES `file` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `instance_id` int NOT NULL,
  `institution_id` int NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `username_canonical` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `email` varchar(180) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `email_canonical` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `locked` tinyint(1) NOT NULL,
  `confirmation_token` varchar(180) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `surname` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `birthdate` date DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `download_auth` tinyint(1) NOT NULL,
  `wrong_email` tinyint(1) NOT NULL,
  `secondary_instances` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `pdf` tinyint(1) NOT NULL,
  `observaciones` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`),
  KEY `idx_username` (`username`),
  KEY `idx_email` (`email`),
  KEY `idx_name` (`name`),
  KEY `idx_surname` (`surname`),
  KEY `idx_enabled` (`enabled`),
  KEY `idx_locked` (`locked`),
  KEY `idx_instance` (`instance_id`),
  KEY `idx_institution` (`institution_id`),
  CONSTRAINT `FK_8D93D64910405986` FOREIGN KEY (`institution_id`) REFERENCES `provider` (`id`),
  CONSTRAINT `FK_8D93D6493A51721D` FOREIGN KEY (`instance_id`) REFERENCES `instance` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31402 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_client` (
  `user_id` int NOT NULL,
  `client_id` int NOT NULL,
  PRIMARY KEY (`user_id`,`client_id`),
  KEY `IDX_A2161F68A76ED395` (`user_id`),
  KEY `IDX_A2161F6819EB6921` (`client_id`),
  CONSTRAINT `FK_A2161F6819EB6921` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`),
  CONSTRAINT `FK_A2161F68A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
