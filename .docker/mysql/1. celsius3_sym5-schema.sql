-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: bd:3306
-- Tiempo de generación: 17-10-2024 a las 13:40:02
-- Versión del servidor: 8.0.39
-- Versión de PHP: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `celsius3`
--

---
--- Verificar si la base de datos existe y vaciarla o crearla
---

-- DROP DATABASE IF EXISTS celsius3;
-- CREATE DATABASE celsius3;

-- USE celsius3;


---
--- Crear usuario y dar permisos
---

-- CREATE USER 'celsius3_usr'@'%' IDENTIFIED BY 'celsius3_pass';
-- GRANT ALL PRIVILEGES ON celsius3.* TO 'celsius3_usr'@'%';
-- FLUSH PRIVILEGES;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `access_token`
--

CREATE TABLE `access_token` (
  `id` int NOT NULL,
  `client_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` int DEFAULT NULL,
  `scope` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `approves_files`
--

CREATE TABLE `approves_files` (
  `event_id` int NOT NULL,
  `file_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auth_code`
--

CREATE TABLE `auth_code` (
  `id` int NOT NULL,
  `client_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `redirect_uri` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` int DEFAULT NULL,
  `scope` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catalog`
--

CREATE TABLE `catalog` (
  `id` int NOT NULL,
  `institution_id` int DEFAULT NULL,
  `instance_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `comments` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catalog_position`
--

CREATE TABLE `catalog_position` (
  `id` int NOT NULL,
  `catalog_id` int NOT NULL,
  `instance_id` int NOT NULL,
  `position` int NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catalog_result`
--

CREATE TABLE `catalog_result` (
  `id` int NOT NULL,
  `catalog_id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `searches` int NOT NULL,
  `matches` int NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `city`
--

CREATE TABLE `city` (
  `id` int NOT NULL,
  `country_id` int NOT NULL,
  `instance_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postal_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `client`
--

CREATE TABLE `client` (
  `id` int NOT NULL,
  `instance_id` int DEFAULT NULL,
  `random_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `redirect_uris` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `secret` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `allowed_grant_types` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:array)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuration`
--

CREATE TABLE `configuration` (
  `id` int NOT NULL,
  `instance_id` int NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` longtext COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contact`
--

CREATE TABLE `contact` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `type_id` int NOT NULL,
  `instance_id` int DEFAULT NULL,
  `institution_id` int NOT NULL,
  `owning_instance_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `surname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contact_type`
--

CREATE TABLE `contact_type` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `counter`
--

CREATE TABLE `counter` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` int NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `country`
--

CREATE TABLE `country` (
  `id` int NOT NULL,
  `instance_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abbreviation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `custom_field`
--

CREATE TABLE `custom_field` (
  `id` int NOT NULL,
  `instance_id` int NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value` longtext COLLATE utf8mb4_unicode_ci,
  `private` tinyint(1) NOT NULL,
  `required` tinyint(1) NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `position` int DEFAULT NULL,
  `entity` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `custom_value`
--

CREATE TABLE `custom_value` (
  `id` int NOT NULL,
  `field_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `contact_id` int DEFAULT NULL,
  `value` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `data_request`
--

CREATE TABLE `data_request` (
  `id` int NOT NULL,
  `instance_id` int DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `data` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exported` tinyint(1) NOT NULL,
  `downloaded` tinyint(1) NOT NULL,
  `visible` tinyint(1) NOT NULL,
  `file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `email`
--

CREATE TABLE `email` (
  `id` int NOT NULL,
  `sender_id` int NOT NULL,
  `instance_id` int NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `text` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `sent` tinyint(1) NOT NULL,
  `attempts` int NOT NULL,
  `error` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `event`
--

CREATE TABLE `event` (
  `id` int NOT NULL,
  `request_id` int NOT NULL,
  `operator_id` int DEFAULT NULL,
  `state_id` int NOT NULL,
  `instance_id` int NOT NULL,
  `catalog_id` int DEFAULT NULL,
  `provider_id` int DEFAULT NULL,
  `request_event_id` int DEFAULT NULL,
  `remote_state_id` int DEFAULT NULL,
  `remote_request_id` int DEFAULT NULL,
  `receive_event_id` int DEFAULT NULL,
  `remote_instance_id` int DEFAULT NULL,
  `observations` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `result` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reclaimed` tinyint(1) DEFAULT NULL,
  `cancelled` tinyint(1) DEFAULT NULL,
  `cancelled_by_user` tinyint(1) DEFAULT NULL,
  `delivery_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approved` tinyint(1) DEFAULT NULL,
  `annulled` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ext_log_entries`
--

CREATE TABLE `ext_log_entries` (
  `id` int NOT NULL,
  `action` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logged_at` datetime NOT NULL,
  `object_id` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `object_class` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `version` int NOT NULL,
  `data` longtext COLLATE utf8mb4_unicode_ci COMMENT '(DC2Type:array)',
  `username` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ext_translations`
--

CREATE TABLE `ext_translations` (
  `id` int NOT NULL,
  `locale` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `object_class` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `field` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `foreign_key` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `file`
--

CREATE TABLE `file` (
  `id` int NOT NULL,
  `request_id` int DEFAULT NULL,
  `instance_id` int DEFAULT NULL,
  `event_id` int DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `comments` longtext COLLATE utf8mb4_unicode_ci,
  `enabled` tinyint(1) NOT NULL,
  `downloaded` tinyint(1) NOT NULL,
  `pages` int NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `file_download`
--

CREATE TABLE `file_download` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `file_id` int NOT NULL,
  `request_id` int NOT NULL,
  `instance_id` int NOT NULL,
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_agent` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hive`
--

CREATE TABLE `hive` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `instance`
--

CREATE TABLE `instance` (
  `id` int NOT NULL,
  `hive_id` int DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abbreviation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `host` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invisible` tinyint(1) DEFAULT NULL,
  `latitud` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitud` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observaciones` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `journal`
--

CREATE TABLE `journal` (
  `id` int NOT NULL,
  `instance_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abbreviation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `responsible` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `issn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `issne` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `frecuency` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `librarian_institution`
--

CREATE TABLE `librarian_institution` (
  `user_id` int NOT NULL,
  `institution_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `login`
--

CREATE TABLE `login` (
  `id` int NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `message` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `material_type`
--

CREATE TABLE `material_type` (
  `id` int NOT NULL,
  `journal_id` int DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `authors` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` int NOT NULL,
  `start_page` int DEFAULT NULL,
  `end_page` int DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `volume` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `other` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `editor` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `chapter` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `isbn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `with_index` tinyint(1) DEFAULT NULL,
  `place` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `communication` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `director` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `degree` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `article` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `day` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `message`
--

CREATE TABLE `message` (
  `id` int NOT NULL,
  `thread_id` int DEFAULT NULL,
  `sender_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `message_metadata`
--

CREATE TABLE `message_metadata` (
  `id` int NOT NULL,
  `message_id` int DEFAULT NULL,
  `participant_id` int DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint NOT NULL,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mirequests_files`
--

CREATE TABLE `mirequests_files` (
  `event_id` int NOT NULL,
  `file_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `news`
--

CREATE TABLE `news` (
  `id` int NOT NULL,
  `instance_id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `text` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `active` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notification`
--

CREATE TABLE `notification` (
  `id` int NOT NULL,
  `template_id` int NOT NULL,
  `message_notification_id` int DEFAULT NULL,
  `base_user_notification_id` int DEFAULT NULL,
  `event_notification_id` int DEFAULT NULL,
  `cause` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `viewed` tinyint(1) NOT NULL,
  `viewed_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notification_receiver`
--

CREATE TABLE `notification_receiver` (
  `notification_id` int NOT NULL,
  `receiver_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notification_settings`
--

CREATE TABLE `notification_settings` (
  `id` int NOT NULL,
  `user` int DEFAULT NULL,
  `instance` int DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subscribedToInterfaceNotifications` tinyint(1) NOT NULL,
  `subscribedToEmailNotifications` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notification_viewer`
--

CREATE TABLE `notification_viewer` (
  `notification_id` int NOT NULL,
  `receiver_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `order`
--

CREATE TABLE `order` (
  `id` int NOT NULL,
  `material_data_id` int DEFAULT NULL,
  `original_request_id` int DEFAULT NULL,
  `code` int NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `provider`
--

CREATE TABLE `provider` (
  `id` int NOT NULL,
  `parent_id` int DEFAULT NULL,
  `city_id` int DEFAULT NULL,
  `country_id` int DEFAULT NULL,
  `instance_id` int DEFAULT NULL,
  `celsius_instance_id` int DEFAULT NULL,
  `hive_id` int DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `abbreviation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `refresh_token`
--

CREATE TABLE `refresh_token` (
  `id` int NOT NULL,
  `client_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` int DEFAULT NULL,
  `scope` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `request`
--

CREATE TABLE `request` (
  `id` int NOT NULL,
  `owner_id` int NOT NULL,
  `creator_id` int NOT NULL,
  `librarian_id` int DEFAULT NULL,
  `instance_id` int NOT NULL,
  `operator_id` int DEFAULT NULL,
  `order_id` int NOT NULL,
  `previous_request_id` int DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `comments` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reset_password_request`
--

CREATE TABLE `reset_password_request` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `selector` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hashed_token` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `requested_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `expires_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sirequests_files`
--

CREATE TABLE `sirequests_files` (
  `event_id` int NOT NULL,
  `file_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `state`
--

CREATE TABLE `state` (
  `id` int NOT NULL,
  `remote_event_id` int DEFAULT NULL,
  `instance_id` int NOT NULL,
  `previous_id` int DEFAULT NULL,
  `request_id` int NOT NULL,
  `operator_id` int DEFAULT NULL,
  `current` tinyint(1) NOT NULL,
  `search_pending` tinyint(1) NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `template`
--

CREATE TABLE `template` (
  `id` int NOT NULL,
  `instance_id` int DEFAULT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `text` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `thread`
--

CREATE TABLE `thread` (
  `id` int NOT NULL,
  `created_by_id` int NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `thread_metadata`
--

CREATE TABLE `thread_metadata` (
  `id` int NOT NULL,
  `thread_id` int DEFAULT NULL,
  `participant_id` int DEFAULT NULL,
  `last_message_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ticket`
--

CREATE TABLE `ticket` (
  `id` int NOT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `user_assigned_id` int DEFAULT NULL,
  `status_current_id` int DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  `priority_id` int DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `text` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ticket_category`
--

CREATE TABLE `ticket_category` (
  `id` int NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ticket_priority`
--

CREATE TABLE `ticket_priority` (
  `id` int NOT NULL,
  `priority` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ticket_state`
--

CREATE TABLE `ticket_state` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `type_state_id` int DEFAULT NULL,
  `ticket_id` int NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ticket_type_state`
--

CREATE TABLE `ticket_type_state` (
  `id` int NOT NULL,
  `type_state` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `uploads_files`
--

CREATE TABLE `uploads_files` (
  `event_id` int NOT NULL,
  `file_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `instance_id` int NOT NULL,
  `institution_id` int NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username_canonical` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_canonical` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `salt` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `surname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `birthdate` date DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `download_auth` tinyint(1) NOT NULL,
  `wrong_email` tinyint(1) NOT NULL,
  `pdf` tinyint(1) NOT NULL,
  `secondary_instances` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `observaciones` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `locked` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_client`
--

CREATE TABLE `user_client` (
  `user_id` int NOT NULL,
  `client_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `access_token`
--
ALTER TABLE `access_token`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_B6A2DD685F37A13B` (`token`),
  ADD KEY `IDX_B6A2DD6819EB6921` (`client_id`),
  ADD KEY `IDX_B6A2DD68A76ED395` (`user_id`);

--
-- Indices de la tabla `approves_files`
--
ALTER TABLE `approves_files`
  ADD PRIMARY KEY (`event_id`,`file_id`),
  ADD UNIQUE KEY `UNIQ_23888D7793CB796C` (`file_id`),
  ADD KEY `IDX_23888D7771F7E88B` (`event_id`);

--
-- Indices de la tabla `auth_code`
--
ALTER TABLE `auth_code`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_5933D02C5F37A13B` (`token`),
  ADD KEY `IDX_5933D02C19EB6921` (`client_id`),
  ADD KEY `IDX_5933D02CA76ED395` (`user_id`);

--
-- Indices de la tabla `catalog`
--
ALTER TABLE `catalog`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_name` (`name`),
  ADD KEY `idx_url` (`url`),
  ADD KEY `idx_institution` (`institution_id`),
  ADD KEY `idx_instance` (`instance_id`);

--
-- Indices de la tabla `catalog_position`
--
ALTER TABLE `catalog_position`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_position` (`position`),
  ADD KEY `idx_catalog` (`catalog_id`),
  ADD KEY `idx_instance` (`instance_id`);

--
-- Indices de la tabla `catalog_result`
--
ALTER TABLE `catalog_result`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_title` (`title`),
  ADD KEY `idx_catalog` (`catalog_id`),
  ADD KEY `idx_title_catalog` (`catalog_id`,`title`);

--
-- Indices de la tabla `city`
--
ALTER TABLE `city`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_name` (`name`),
  ADD KEY `idx_country` (`country_id`),
  ADD KEY `idx_instance` (`instance_id`);

--
-- Indices de la tabla `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_C74404553A51721D` (`instance_id`);

--
-- Indices de la tabla `configuration`
--
ALTER TABLE `configuration`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_idx` (`key`,`instance_id`),
  ADD KEY `idx_key` (`key`),
  ADD KEY `idx_instance` (`instance_id`);

--
-- Indices de la tabla `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_4C62E638A76ED395` (`user_id`),
  ADD KEY `IDX_4C62E638C54C8C93` (`type_id`),
  ADD KEY `IDX_4C62E6383A51721D` (`instance_id`),
  ADD KEY `idx_name` (`name`),
  ADD KEY `idx_surname` (`surname`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_institution` (`institution_id`),
  ADD KEY `idx_owning_instance` (`owning_instance_id`);

--
-- Indices de la tabla `contact_type`
--
ALTER TABLE `contact_type`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_name` (`name`);

--
-- Indices de la tabla `counter`
--
ALTER TABLE `counter`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_name` (`name`);

--
-- Indices de la tabla `country`
--
ALTER TABLE `country`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_5373C9665E237E06` (`name`),
  ADD UNIQUE KEY `UNIQ_5373C966BCF3411D` (`abbreviation`),
  ADD KEY `idx_name` (`name`),
  ADD KEY `idx_instance` (`instance_id`);

--
-- Indices de la tabla `custom_field`
--
ALTER TABLE `custom_field`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_idx` (`key`,`instance_id`),
  ADD KEY `idx_key` (`key`),
  ADD KEY `idx_name` (`name`),
  ADD KEY `idx_instance` (`instance_id`);

--
-- Indices de la tabla `custom_value`
--
ALTER TABLE `custom_value`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_field` (`field_id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_contact` (`contact_id`);

--
-- Indices de la tabla `data_request`
--
ALTER TABLE `data_request`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_5F9CD96E3A51721D` (`instance_id`);

--
-- Indices de la tabla `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indices de la tabla `email`
--
ALTER TABLE `email`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_E7927C74F624B39D` (`sender_id`),
  ADD KEY `IDX_E7927C743A51721D` (`instance_id`);

--
-- Indices de la tabla `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_3BAE0AA7CC3C66FC` (`catalog_id`),
  ADD KEY `IDX_3BAE0AA7A53A8AA` (`provider_id`),
  ADD KEY `IDX_3BAE0AA784D208AE` (`request_event_id`),
  ADD KEY `IDX_3BAE0AA7D64A438D` (`remote_state_id`),
  ADD KEY `IDX_3BAE0AA7DE080793` (`remote_request_id`),
  ADD KEY `IDX_3BAE0AA7B385DA0` (`receive_event_id`),
  ADD KEY `IDX_3BAE0AA7F577913B` (`remote_instance_id`),
  ADD KEY `idx_request` (`request_id`),
  ADD KEY `idx_operator` (`operator_id`),
  ADD KEY `idx_state` (`state_id`),
  ADD KEY `idx_instance` (`instance_id`),
  ADD KEY `idx_type` (`type`);

--
-- Indices de la tabla `ext_log_entries`
--
ALTER TABLE `ext_log_entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `log_class_lookup_idx` (`object_class`),
  ADD KEY `log_date_lookup_idx` (`logged_at`),
  ADD KEY `log_user_lookup_idx` (`username`),
  ADD KEY `log_version_lookup_idx` (`object_id`,`object_class`,`version`);

--
-- Indices de la tabla `ext_translations`
--
ALTER TABLE `ext_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lookup_unique_idx` (`locale`,`object_class`,`field`,`foreign_key`),
  ADD KEY `translations_lookup_idx` (`locale`,`object_class`,`foreign_key`),
  ADD KEY `general_translations_lookup_idx` (`object_class`,`foreign_key`);

--
-- Indices de la tabla `file`
--
ALTER TABLE `file`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_event` (`event_id`),
  ADD KEY `idx_request` (`request_id`),
  ADD KEY `idx_instance` (`instance_id`);

--
-- Indices de la tabla `file_download`
--
ALTER TABLE `file_download`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_C94A0DED93CB796C` (`file_id`),
  ADD KEY `idx_request` (`request_id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_ip` (`ip`),
  ADD KEY `idx_instance` (`instance_id`);

--
-- Indices de la tabla `hive`
--
ALTER TABLE `hive`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_name` (`name`);

--
-- Indices de la tabla `instance`
--
ALTER TABLE `instance`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_4230B1DEF47645AE` (`url`),
  ADD UNIQUE KEY `UNIQ_4230B1DECF2713FD` (`host`),
  ADD KEY `idx_name` (`name`),
  ADD KEY `idx_website` (`website`),
  ADD KEY `idx_hive` (`hive_id`),
  ADD KEY `idx_url` (`url`),
  ADD KEY `idx_type` (`type`);

--
-- Indices de la tabla `journal`
--
ALTER TABLE `journal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_name` (`name`),
  ADD KEY `idx_abbreviation` (`abbreviation`),
  ADD KEY `idx_instance` (`instance_id`);

--
-- Indices de la tabla `librarian_institution`
--
ALTER TABLE `librarian_institution`
  ADD PRIMARY KEY (`user_id`,`institution_id`),
  ADD KEY `IDX_73E3D2FEA76ED395` (`user_id`),
  ADD KEY `IDX_73E3D2FE10405986` (`institution_id`);

--
-- Indices de la tabla `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_category` (`category`);

--
-- Indices de la tabla `material_type`
--
ALTER TABLE `material_type`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D8B63A1C478E8802` (`journal_id`),
  ADD KEY `idx_type` (`type`),
  ADD KEY `idx_title` (`title`);

--
-- Indices de la tabla `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_thread` (`thread_id`),
  ADD KEY `idx_sender` (`sender_id`);

--
-- Indices de la tabla `message_metadata`
--
ALTER TABLE `message_metadata`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_message` (`message_id`),
  ADD KEY `idx_participant` (`participant_id`),
  ADD KEY `idx_read` (`is_read`);

--
-- Indices de la tabla `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Indices de la tabla `mirequests_files`
--
ALTER TABLE `mirequests_files`
  ADD PRIMARY KEY (`event_id`,`file_id`),
  ADD UNIQUE KEY `UNIQ_F60E931693CB796C` (`file_id`),
  ADD KEY `IDX_F60E931671F7E88B` (`event_id`);

--
-- Indices de la tabla `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_title` (`title`),
  ADD KEY `idx_date` (`date`),
  ADD KEY `idx_instance` (`instance_id`);

--
-- Indices de la tabla `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_viewed` (`viewed`),
  ADD KEY `idx_template` (`template_id`),
  ADD KEY `idx_object_user` (`base_user_notification_id`),
  ADD KEY `idx_object_message` (`message_notification_id`),
  ADD KEY `idx_object_event` (`event_notification_id`);

--
-- Indices de la tabla `notification_receiver`
--
ALTER TABLE `notification_receiver`
  ADD PRIMARY KEY (`notification_id`,`receiver_id`),
  ADD KEY `IDX_68A8B433EF1A9D84` (`notification_id`),
  ADD KEY `IDX_68A8B433CD53EDB6` (`receiver_id`);

--
-- Indices de la tabla `notification_settings`
--
ALTER TABLE `notification_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B05598608D93D649` (`user`),
  ADD KEY `IDX_B05598604230B1DE` (`instance`);

--
-- Indices de la tabla `notification_viewer`
--
ALTER TABLE `notification_viewer`
  ADD PRIMARY KEY (`notification_id`,`receiver_id`),
  ADD UNIQUE KEY `UNIQ_C7FB5208CD53EDB6` (`receiver_id`),
  ADD KEY `IDX_C7FB5208EF1A9D84` (`notification_id`);

--
-- Indices de la tabla `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_F5299398866F7A93` (`material_data_id`),
  ADD UNIQUE KEY `UNIQ_F52993982FC9CF31` (`original_request_id`),
  ADD KEY `idx_code` (`code`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `idx_material_data` (`material_data_id`),
  ADD KEY `idx_original_request` (`original_request_id`);

--
-- Indices de la tabla `provider`
--
ALTER TABLE `provider`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_name` (`name`),
  ADD KEY `idx_city` (`city_id`),
  ADD KEY `idx_country` (`country_id`),
  ADD KEY `idx_instance` (`instance_id`),
  ADD KEY `idx_celsius_instance` (`celsius_instance_id`),
  ADD KEY `idx_parent` (`parent_id`),
  ADD KEY `idx_hive` (`hive_id`),
  ADD KEY `idx_type` (`type`);

--
-- Indices de la tabla `refresh_token`
--
ALTER TABLE `refresh_token`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_C74F21955F37A13B` (`token`),
  ADD KEY `IDX_C74F219519EB6921` (`client_id`),
  ADD KEY `IDX_C74F2195A76ED395` (`user_id`);

--
-- Indices de la tabla `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_order_instance` (`instance_id`,`order_id`),
  ADD KEY `idx_type` (`type`),
  ADD KEY `idx_owner` (`owner_id`),
  ADD KEY `idx_creator` (`creator_id`),
  ADD KEY `idx_librarian` (`librarian_id`),
  ADD KEY `idx_instance` (`instance_id`),
  ADD KEY `idx_operator` (`operator_id`),
  ADD KEY `idx_order` (`order_id`),
  ADD KEY `idx_previous_request` (`previous_request_id`);

--
-- Indices de la tabla `reset_password_request`
--
ALTER TABLE `reset_password_request`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_7CE748AA76ED395` (`user_id`);

--
-- Indices de la tabla `sirequests_files`
--
ALTER TABLE `sirequests_files`
  ADD PRIMARY KEY (`event_id`,`file_id`),
  ADD UNIQUE KEY `UNIQ_30AB5B2393CB796C` (`file_id`),
  ADD KEY `IDX_30AB5B2371F7E88B` (`event_id`);

--
-- Indices de la tabla `state`
--
ALTER TABLE `state`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_A393D2FBA26597C7` (`remote_event_id`),
  ADD KEY `idx_current` (`current`),
  ADD KEY `idx_type` (`type`),
  ADD KEY `idx_previous` (`previous_id`),
  ADD KEY `idx_request` (`request_id`),
  ADD KEY `idx_instance` (`instance_id`),
  ADD KEY `idx_operator` (`operator_id`);

--
-- Indices de la tabla `template`
--
ALTER TABLE `template`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_code` (`code`),
  ADD KEY `idx_title` (`title`),
  ADD KEY `idx_instance` (`instance_id`),
  ADD KEY `idx_type` (`type`);

--
-- Indices de la tabla `thread`
--
ALTER TABLE `thread`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indices de la tabla `thread_metadata`
--
ALTER TABLE `thread_metadata`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_thread` (`thread_id`),
  ADD KEY `idx_participant` (`participant_id`),
  ADD KEY `idx_last_message_date` (`last_message_date`);

--
-- Indices de la tabla `ticket`
--
ALTER TABLE `ticket`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_97A0ADA3DE12AB56` (`created_by`),
  ADD KEY `IDX_97A0ADA316FE72E1` (`updated_by`),
  ADD KEY `IDX_97A0ADA3484BD390` (`user_assigned_id`),
  ADD KEY `IDX_97A0ADA3F4D99EDA` (`status_current_id`),
  ADD KEY `IDX_97A0ADA312469DE2` (`category_id`),
  ADD KEY `IDX_97A0ADA3497B19F9` (`priority_id`);

--
-- Indices de la tabla `ticket_category`
--
ALTER TABLE `ticket_category`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ticket_priority`
--
ALTER TABLE `ticket_priority`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ticket_state`
--
ALTER TABLE `ticket_state`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_8BA3B170A76ED395` (`user_id`),
  ADD KEY `IDX_8BA3B170C8271B3D` (`type_state_id`),
  ADD KEY `IDX_8BA3B170700047D2` (`ticket_id`);

--
-- Indices de la tabla `ticket_type_state`
--
ALTER TABLE `ticket_type_state`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `uploads_files`
--
ALTER TABLE `uploads_files`
  ADD PRIMARY KEY (`event_id`,`file_id`),
  ADD UNIQUE KEY `UNIQ_931AF9593CB796C` (`file_id`),
  ADD KEY `IDX_931AF9571F7E88B` (`event_id`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`),
  ADD UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`),
  ADD KEY `idx_username` (`username`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_name` (`name`),
  ADD KEY `idx_surname` (`surname`),
  ADD KEY `idx_enabled` (`enabled`),
  ADD KEY `idx_locked` (`locked`),
  ADD KEY `idx_instance` (`instance_id`),
  ADD KEY `idx_institution` (`institution_id`);

--
-- Indices de la tabla `user_client`
--
ALTER TABLE `user_client`
  ADD PRIMARY KEY (`user_id`,`client_id`),
  ADD KEY `IDX_A2161F68A76ED395` (`user_id`),
  ADD KEY `IDX_A2161F6819EB6921` (`client_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `access_token`
--
ALTER TABLE `access_token`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `auth_code`
--
ALTER TABLE `auth_code`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `catalog`
--
ALTER TABLE `catalog`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `catalog_position`
--
ALTER TABLE `catalog_position`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `catalog_result`
--
ALTER TABLE `catalog_result`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `city`
--
ALTER TABLE `city`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `client`
--
ALTER TABLE `client`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `configuration`
--
ALTER TABLE `configuration`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `contact_type`
--
ALTER TABLE `contact_type`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `counter`
--
ALTER TABLE `counter`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `country`
--
ALTER TABLE `country`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `custom_field`
--
ALTER TABLE `custom_field`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `custom_value`
--
ALTER TABLE `custom_value`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `data_request`
--
ALTER TABLE `data_request`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `email`
--
ALTER TABLE `email`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `event`
--
ALTER TABLE `event`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ext_log_entries`
--
ALTER TABLE `ext_log_entries`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ext_translations`
--
ALTER TABLE `ext_translations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `file`
--
ALTER TABLE `file`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `file_download`
--
ALTER TABLE `file_download`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `hive`
--
ALTER TABLE `hive`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `instance`
--
ALTER TABLE `instance`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `journal`
--
ALTER TABLE `journal`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `login`
--
ALTER TABLE `login`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `material_type`
--
ALTER TABLE `material_type`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `message`
--
ALTER TABLE `message`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `message_metadata`
--
ALTER TABLE `message_metadata`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `news`
--
ALTER TABLE `news`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `notification_settings`
--
ALTER TABLE `notification_settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `order`
--
ALTER TABLE `order`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `provider`
--
ALTER TABLE `provider`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `refresh_token`
--
ALTER TABLE `refresh_token`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `request`
--
ALTER TABLE `request`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reset_password_request`
--
ALTER TABLE `reset_password_request`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `state`
--
ALTER TABLE `state`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `template`
--
ALTER TABLE `template`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `thread`
--
ALTER TABLE `thread`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `thread_metadata`
--
ALTER TABLE `thread_metadata`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ticket`
--
ALTER TABLE `ticket`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ticket_category`
--
ALTER TABLE `ticket_category`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ticket_priority`
--
ALTER TABLE `ticket_priority`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ticket_state`
--
ALTER TABLE `ticket_state`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ticket_type_state`
--
ALTER TABLE `ticket_type_state`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `access_token`
--
ALTER TABLE `access_token`
  ADD CONSTRAINT `FK_B6A2DD6819EB6921` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`),
  ADD CONSTRAINT `FK_B6A2DD68A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Filtros para la tabla `approves_files`
--
ALTER TABLE `approves_files`
  ADD CONSTRAINT `FK_23888D7771F7E88B` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
  ADD CONSTRAINT `FK_23888D7793CB796C` FOREIGN KEY (`file_id`) REFERENCES `file` (`id`);

--
-- Filtros para la tabla `auth_code`
--
ALTER TABLE `auth_code`
  ADD CONSTRAINT `FK_5933D02C19EB6921` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`),
  ADD CONSTRAINT `FK_5933D02CA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Filtros para la tabla `catalog`
--
ALTER TABLE `catalog`
  ADD CONSTRAINT `FK_1B2C324710405986` FOREIGN KEY (`institution_id`) REFERENCES `provider` (`id`),
  ADD CONSTRAINT `FK_1B2C32473A51721D` FOREIGN KEY (`instance_id`) REFERENCES `instance` (`id`);

--
-- Filtros para la tabla `catalog_position`
--
ALTER TABLE `catalog_position`
  ADD CONSTRAINT `FK_74FB3AEB3A51721D` FOREIGN KEY (`instance_id`) REFERENCES `instance` (`id`),
  ADD CONSTRAINT `FK_74FB3AEBCC3C66FC` FOREIGN KEY (`catalog_id`) REFERENCES `catalog` (`id`);

--
-- Filtros para la tabla `catalog_result`
--
ALTER TABLE `catalog_result`
  ADD CONSTRAINT `FK_A16ED95ACC3C66FC` FOREIGN KEY (`catalog_id`) REFERENCES `catalog` (`id`);

--
-- Filtros para la tabla `city`
--
ALTER TABLE `city`
  ADD CONSTRAINT `FK_2D5B02343A51721D` FOREIGN KEY (`instance_id`) REFERENCES `instance` (`id`),
  ADD CONSTRAINT `FK_2D5B0234F92F3E70` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`);

--
-- Filtros para la tabla `client`
--
ALTER TABLE `client`
  ADD CONSTRAINT `FK_C74404553A51721D` FOREIGN KEY (`instance_id`) REFERENCES `instance` (`id`);

--
-- Filtros para la tabla `configuration`
--
ALTER TABLE `configuration`
  ADD CONSTRAINT `FK_A5E2A5D73A51721D` FOREIGN KEY (`instance_id`) REFERENCES `instance` (`id`);

--
-- Filtros para la tabla `contact`
--
ALTER TABLE `contact`
  ADD CONSTRAINT `FK_4C62E63810405986` FOREIGN KEY (`institution_id`) REFERENCES `provider` (`id`),
  ADD CONSTRAINT `FK_4C62E63834F938CE` FOREIGN KEY (`owning_instance_id`) REFERENCES `instance` (`id`),
  ADD CONSTRAINT `FK_4C62E6383A51721D` FOREIGN KEY (`instance_id`) REFERENCES `instance` (`id`),
  ADD CONSTRAINT `FK_4C62E638A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_4C62E638C54C8C93` FOREIGN KEY (`type_id`) REFERENCES `contact_type` (`id`);

--
-- Filtros para la tabla `country`
--
ALTER TABLE `country`
  ADD CONSTRAINT `FK_5373C9663A51721D` FOREIGN KEY (`instance_id`) REFERENCES `instance` (`id`);

--
-- Filtros para la tabla `custom_field`
--
ALTER TABLE `custom_field`
  ADD CONSTRAINT `FK_98F8BD313A51721D` FOREIGN KEY (`instance_id`) REFERENCES `instance` (`id`);

--
-- Filtros para la tabla `custom_value`
--
ALTER TABLE `custom_value`
  ADD CONSTRAINT `FK_DE7AA05D443707B0` FOREIGN KEY (`field_id`) REFERENCES `custom_field` (`id`),
  ADD CONSTRAINT `FK_DE7AA05DA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_DE7AA05DE7A1254A` FOREIGN KEY (`contact_id`) REFERENCES `contact` (`id`);

--
-- Filtros para la tabla `data_request`
--
ALTER TABLE `data_request`
  ADD CONSTRAINT `FK_5F9CD96E3A51721D` FOREIGN KEY (`instance_id`) REFERENCES `instance` (`id`);

--
-- Filtros para la tabla `email`
--
ALTER TABLE `email`
  ADD CONSTRAINT `FK_E7927C743A51721D` FOREIGN KEY (`instance_id`) REFERENCES `instance` (`id`),
  ADD CONSTRAINT `FK_E7927C74F624B39D` FOREIGN KEY (`sender_id`) REFERENCES `user` (`id`);

--
-- Filtros para la tabla `event`
--
ALTER TABLE `event`
  ADD CONSTRAINT `FK_3BAE0AA73A51721D` FOREIGN KEY (`instance_id`) REFERENCES `instance` (`id`),
  ADD CONSTRAINT `FK_3BAE0AA7427EB8A5` FOREIGN KEY (`request_id`) REFERENCES `request` (`id`),
  ADD CONSTRAINT `FK_3BAE0AA7584598A3` FOREIGN KEY (`operator_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_3BAE0AA75D83CC1` FOREIGN KEY (`state_id`) REFERENCES `state` (`id`),
  ADD CONSTRAINT `FK_3BAE0AA784D208AE` FOREIGN KEY (`request_event_id`) REFERENCES `event` (`id`),
  ADD CONSTRAINT `FK_3BAE0AA7A53A8AA` FOREIGN KEY (`provider_id`) REFERENCES `provider` (`id`),
  ADD CONSTRAINT `FK_3BAE0AA7B385DA0` FOREIGN KEY (`receive_event_id`) REFERENCES `event` (`id`),
  ADD CONSTRAINT `FK_3BAE0AA7CC3C66FC` FOREIGN KEY (`catalog_id`) REFERENCES `catalog` (`id`),
  ADD CONSTRAINT `FK_3BAE0AA7D64A438D` FOREIGN KEY (`remote_state_id`) REFERENCES `state` (`id`),
  ADD CONSTRAINT `FK_3BAE0AA7DE080793` FOREIGN KEY (`remote_request_id`) REFERENCES `request` (`id`),
  ADD CONSTRAINT `FK_3BAE0AA7F577913B` FOREIGN KEY (`remote_instance_id`) REFERENCES `instance` (`id`);

--
-- Filtros para la tabla `file`
--
ALTER TABLE `file`
  ADD CONSTRAINT `FK_8C9F36103A51721D` FOREIGN KEY (`instance_id`) REFERENCES `instance` (`id`),
  ADD CONSTRAINT `FK_8C9F3610427EB8A5` FOREIGN KEY (`request_id`) REFERENCES `request` (`id`),
  ADD CONSTRAINT `FK_8C9F361071F7E88B` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`);

--
-- Filtros para la tabla `file_download`
--
ALTER TABLE `file_download`
  ADD CONSTRAINT `FK_C94A0DED3A51721D` FOREIGN KEY (`instance_id`) REFERENCES `instance` (`id`),
  ADD CONSTRAINT `FK_C94A0DED427EB8A5` FOREIGN KEY (`request_id`) REFERENCES `request` (`id`),
  ADD CONSTRAINT `FK_C94A0DED93CB796C` FOREIGN KEY (`file_id`) REFERENCES `file` (`id`),
  ADD CONSTRAINT `FK_C94A0DEDA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Filtros para la tabla `instance`
--
ALTER TABLE `instance`
  ADD CONSTRAINT `FK_4230B1DEE9A48D12` FOREIGN KEY (`hive_id`) REFERENCES `hive` (`id`);

--
-- Filtros para la tabla `journal`
--
ALTER TABLE `journal`
  ADD CONSTRAINT `FK_C1A7E74D3A51721D` FOREIGN KEY (`instance_id`) REFERENCES `instance` (`id`);

--
-- Filtros para la tabla `librarian_institution`
--
ALTER TABLE `librarian_institution`
  ADD CONSTRAINT `FK_73E3D2FE10405986` FOREIGN KEY (`institution_id`) REFERENCES `provider` (`id`),
  ADD CONSTRAINT `FK_73E3D2FEA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Filtros para la tabla `material_type`
--
ALTER TABLE `material_type`
  ADD CONSTRAINT `FK_D8B63A1C478E8802` FOREIGN KEY (`journal_id`) REFERENCES `journal` (`id`);

--
-- Filtros para la tabla `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `FK_B6BD307FE2904019` FOREIGN KEY (`thread_id`) REFERENCES `thread` (`id`),
  ADD CONSTRAINT `FK_B6BD307FF624B39D` FOREIGN KEY (`sender_id`) REFERENCES `user` (`id`);

--
-- Filtros para la tabla `message_metadata`
--
ALTER TABLE `message_metadata`
  ADD CONSTRAINT `FK_4632F005537A1329` FOREIGN KEY (`message_id`) REFERENCES `message` (`id`),
  ADD CONSTRAINT `FK_4632F0059D1C3019` FOREIGN KEY (`participant_id`) REFERENCES `user` (`id`);

--
-- Filtros para la tabla `mirequests_files`
--
ALTER TABLE `mirequests_files`
  ADD CONSTRAINT `FK_F60E931671F7E88B` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
  ADD CONSTRAINT `FK_F60E931693CB796C` FOREIGN KEY (`file_id`) REFERENCES `file` (`id`);

--
-- Filtros para la tabla `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `FK_1DD399503A51721D` FOREIGN KEY (`instance_id`) REFERENCES `instance` (`id`);

--
-- Filtros para la tabla `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `FK_BF5476CA26C9F9B4` FOREIGN KEY (`event_notification_id`) REFERENCES `event` (`id`),
  ADD CONSTRAINT `FK_BF5476CA5366CAB7` FOREIGN KEY (`base_user_notification_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_BF5476CA5DA0FB8` FOREIGN KEY (`template_id`) REFERENCES `template` (`id`),
  ADD CONSTRAINT `FK_BF5476CA9743C372` FOREIGN KEY (`message_notification_id`) REFERENCES `message` (`id`);

--
-- Filtros para la tabla `notification_receiver`
--
ALTER TABLE `notification_receiver`
  ADD CONSTRAINT `FK_68A8B433CD53EDB6` FOREIGN KEY (`receiver_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_68A8B433EF1A9D84` FOREIGN KEY (`notification_id`) REFERENCES `notification` (`id`);

--
-- Filtros para la tabla `notification_settings`
--
ALTER TABLE `notification_settings`
  ADD CONSTRAINT `FK_B05598604230B1DE` FOREIGN KEY (`instance`) REFERENCES `instance` (`id`),
  ADD CONSTRAINT `FK_B05598608D93D649` FOREIGN KEY (`user`) REFERENCES `user` (`id`);

--
-- Filtros para la tabla `notification_viewer`
--
ALTER TABLE `notification_viewer`
  ADD CONSTRAINT `FK_C7FB5208CD53EDB6` FOREIGN KEY (`receiver_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_C7FB5208EF1A9D84` FOREIGN KEY (`notification_id`) REFERENCES `notification` (`id`);

--
-- Filtros para la tabla `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `FK_F52993982FC9CF31` FOREIGN KEY (`original_request_id`) REFERENCES `request` (`id`),
  ADD CONSTRAINT `FK_F5299398866F7A93` FOREIGN KEY (`material_data_id`) REFERENCES `material_type` (`id`);

--
-- Filtros para la tabla `provider`
--
ALTER TABLE `provider`
  ADD CONSTRAINT `FK_92C4739C3A51721D` FOREIGN KEY (`instance_id`) REFERENCES `instance` (`id`),
  ADD CONSTRAINT `FK_92C4739C4337DD9B` FOREIGN KEY (`celsius_instance_id`) REFERENCES `instance` (`id`),
  ADD CONSTRAINT `FK_92C4739C727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `provider` (`id`),
  ADD CONSTRAINT `FK_92C4739C8BAC62AF` FOREIGN KEY (`city_id`) REFERENCES `city` (`id`),
  ADD CONSTRAINT `FK_92C4739CE9A48D12` FOREIGN KEY (`hive_id`) REFERENCES `hive` (`id`),
  ADD CONSTRAINT `FK_92C4739CF92F3E70` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`);

--
-- Filtros para la tabla `refresh_token`
--
ALTER TABLE `refresh_token`
  ADD CONSTRAINT `FK_C74F219519EB6921` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`),
  ADD CONSTRAINT `FK_C74F2195A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Filtros para la tabla `request`
--
ALTER TABLE `request`
  ADD CONSTRAINT `FK_3B978F9F357EFA1C` FOREIGN KEY (`previous_request_id`) REFERENCES `request` (`id`),
  ADD CONSTRAINT `FK_3B978F9F3A51721D` FOREIGN KEY (`instance_id`) REFERENCES `instance` (`id`),
  ADD CONSTRAINT `FK_3B978F9F584598A3` FOREIGN KEY (`operator_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_3B978F9F61220EA6` FOREIGN KEY (`creator_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_3B978F9F7E3C61F9` FOREIGN KEY (`owner_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_3B978F9F8D9F6D38` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`),
  ADD CONSTRAINT `FK_3B978F9FD8B58D1F` FOREIGN KEY (`librarian_id`) REFERENCES `user` (`id`);

--
-- Filtros para la tabla `reset_password_request`
--
ALTER TABLE `reset_password_request`
  ADD CONSTRAINT `FK_7CE748AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Filtros para la tabla `sirequests_files`
--
ALTER TABLE `sirequests_files`
  ADD CONSTRAINT `FK_30AB5B2371F7E88B` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
  ADD CONSTRAINT `FK_30AB5B2393CB796C` FOREIGN KEY (`file_id`) REFERENCES `file` (`id`);

--
-- Filtros para la tabla `state`
--
ALTER TABLE `state`
  ADD CONSTRAINT `FK_A393D2FB2DE62210` FOREIGN KEY (`previous_id`) REFERENCES `state` (`id`),
  ADD CONSTRAINT `FK_A393D2FB3A51721D` FOREIGN KEY (`instance_id`) REFERENCES `instance` (`id`),
  ADD CONSTRAINT `FK_A393D2FB427EB8A5` FOREIGN KEY (`request_id`) REFERENCES `request` (`id`),
  ADD CONSTRAINT `FK_A393D2FB584598A3` FOREIGN KEY (`operator_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_A393D2FBA26597C7` FOREIGN KEY (`remote_event_id`) REFERENCES `event` (`id`);

--
-- Filtros para la tabla `template`
--
ALTER TABLE `template`
  ADD CONSTRAINT `FK_97601F833A51721D` FOREIGN KEY (`instance_id`) REFERENCES `instance` (`id`);

--
-- Filtros para la tabla `thread_metadata`
--
ALTER TABLE `thread_metadata`
  ADD CONSTRAINT `FK_40A577C89D1C3019` FOREIGN KEY (`participant_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_40A577C8E2904019` FOREIGN KEY (`thread_id`) REFERENCES `thread` (`id`);

--
-- Filtros para la tabla `ticket`
--
ALTER TABLE `ticket`
  ADD CONSTRAINT `FK_97A0ADA312469DE2` FOREIGN KEY (`category_id`) REFERENCES `ticket_category` (`id`),
  ADD CONSTRAINT `FK_97A0ADA316FE72E1` FOREIGN KEY (`updated_by`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_97A0ADA3484BD390` FOREIGN KEY (`user_assigned_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_97A0ADA3497B19F9` FOREIGN KEY (`priority_id`) REFERENCES `ticket_priority` (`id`),
  ADD CONSTRAINT `FK_97A0ADA3DE12AB56` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_97A0ADA3F4D99EDA` FOREIGN KEY (`status_current_id`) REFERENCES `ticket_state` (`id`);

--
-- Filtros para la tabla `ticket_state`
--
ALTER TABLE `ticket_state`
  ADD CONSTRAINT `FK_8BA3B170700047D2` FOREIGN KEY (`ticket_id`) REFERENCES `ticket` (`id`),
  ADD CONSTRAINT `FK_8BA3B170A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_8BA3B170C8271B3D` FOREIGN KEY (`type_state_id`) REFERENCES `ticket_type_state` (`id`);

--
-- Filtros para la tabla `uploads_files`
--
ALTER TABLE `uploads_files`
  ADD CONSTRAINT `FK_931AF9571F7E88B` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
  ADD CONSTRAINT `FK_931AF9593CB796C` FOREIGN KEY (`file_id`) REFERENCES `file` (`id`);

--
-- Filtros para la tabla `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_8D93D64910405986` FOREIGN KEY (`institution_id`) REFERENCES `provider` (`id`),
  ADD CONSTRAINT `FK_8D93D6493A51721D` FOREIGN KEY (`instance_id`) REFERENCES `instance` (`id`);

--
-- Filtros para la tabla `user_client`
--
ALTER TABLE `user_client`
  ADD CONSTRAINT `FK_A2161F6819EB6921` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`),
  ADD CONSTRAINT `FK_A2161F68A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
