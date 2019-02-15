-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 13-12-2018 a las 14:06:26
-- Versión del servidor: 5.7.24-0ubuntu0.18.04.1
-- Versión de PHP: 7.0.32-4+ubuntu18.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `celsius3`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `AccessToken`
--

CREATE TABLE `AccessToken` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `expires_at` int(11) DEFAULT NULL,
  `scope` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `approves_files`
--

CREATE TABLE `approves_files` (
  `event_id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `AuthCode`
--

CREATE TABLE `AuthCode` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `redirect_uri` longtext COLLATE utf8_unicode_ci NOT NULL,
  `expires_at` int(11) DEFAULT NULL,
  `scope` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catalog`
--

CREATE TABLE `catalog` (
  `id` int(11) NOT NULL,
  `institution_id` int(11) DEFAULT NULL,
  `instance_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `comments` longtext COLLATE utf8_unicode_ci,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catalog_position`
--

CREATE TABLE `catalog_position` (
  `id` int(11) NOT NULL,
  `catalog_id` int(11) NOT NULL,
  `instance_id` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catalog_result`
--

CREATE TABLE `catalog_result` (
  `id` int(11) NOT NULL,
  `catalog_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `searches` int(11) NOT NULL,
  `matches` int(11) NOT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `city`
--

CREATE TABLE `city` (
  `id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `instance_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `postalCode` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `city`
--

INSERT INTO `city` (`id`, `country_id`, `instance_id`, `name`, `postalCode`, `createdAt`, `updatedAt`) VALUES
(1, 2, 1, 'Quebec', NULL, '2015-09-01 14:24:26', '2018-05-04 11:03:15'),
(2, 2, 1, 'Ottawa', NULL, '2015-09-01 14:24:26', '2018-05-04 11:02:47'),
(9, 5, 1, 'ROMA', NULL, '2015-09-01 14:24:28', '2015-09-01 14:24:28'),
(28, 10, 1, 'Borås', NULL, '2015-09-01 14:24:33', '2015-09-01 14:24:33'),
(29, 10, 1, 'Uppsala', NULL, '2015-09-01 14:24:33', '2015-09-01 14:24:33'),
(40, 15, 1, 'Quilmes', NULL, '2015-09-01 14:24:34', '2015-09-01 14:24:37'),
(44, 27, 1, 'Ciudad de Buenos Aires', NULL, '2015-09-01 14:24:34', '2016-10-04 13:44:45'),
(45, 27, 1, 'Cordoba', NULL, '2015-09-01 14:24:35', '2015-09-01 14:24:44'),
(46, 27, 1, 'Bariloche', NULL, '2015-09-01 14:24:35', '2016-01-12 14:16:01'),
(47, 27, 1, 'LA PAMPA', NULL, '2015-09-01 14:24:35', '2016-01-12 14:16:01'),
(48, 27, 1, 'La Plata', NULL, '2015-09-01 14:24:35', '2016-01-12 14:16:01'),
(49, 27, 1, 'ENSENADA', NULL, '2015-09-01 14:24:36', '2016-01-12 14:16:01'),
(50, 27, 1, 'Tornquist', NULL, '2015-09-01 14:24:36', '2016-01-12 14:16:01'),
(51, 27, 1, 'Azul', '7300', '2015-09-01 14:24:36', '2018-05-04 10:02:51'),
(52, 27, 1, 'web1', NULL, '2015-09-01 14:24:36', '2016-01-12 14:16:01'),
(53, 27, 1, 'Llavallol', NULL, '2015-09-01 14:24:36', '2016-01-12 14:16:01'),
(54, 27, 1, 'Chascomús', NULL, '2015-09-01 14:24:36', '2016-01-12 14:16:01'),
(55, 27, 1, 'Resistencia', NULL, '2015-09-01 14:24:36', '2016-01-12 14:16:01'),
(56, 27, 1, 'Florencio Varela', NULL, '2015-09-01 14:24:36', '2016-01-12 14:16:02'),
(57, 27, 1, 'Rosario', '2000', '2015-09-01 14:24:36', '2018-05-04 10:19:30'),
(58, 27, 1, 'Salta', NULL, '2015-09-01 14:24:36', '2016-01-12 14:16:02'),
(59, 27, 1, 'Berazategui', NULL, '2015-09-01 14:24:36', '2016-10-04 13:44:46'),
(60, 27, 1, 'Paraná', '3100', '2015-09-01 14:24:36', '2018-05-04 12:12:24'),
(61, 27, 1, 'Concepción del Uruguay', '3260', '2015-09-01 14:24:36', '2018-05-04 10:09:57'),
(62, 27, 1, 'TUCUMAN', NULL, '2015-09-01 14:24:36', '2016-10-04 13:44:46'),
(63, 27, 1, 'BAHIA BLANCA', NULL, '2015-09-01 14:24:36', '2016-10-04 13:44:46'),
(64, 27, 1, 'hhh', NULL, '2015-09-01 14:24:36', '2016-10-04 13:44:46'),
(65, 27, 1, 'Comodoro Rivadavia', NULL, '2015-09-01 14:24:36', '2016-10-04 13:44:46'),
(66, 27, 1, 'CIUDAD AUTÓNOMA DE BUENOS AIRES', NULL, '2015-09-01 14:24:36', '2016-10-04 13:44:46'),
(67, 27, 1, 'Santa Fe', NULL, '2015-09-01 14:24:36', '2016-10-04 13:44:46'),
(68, 27, 1, 'Alejandro Korn', NULL, '2015-09-01 14:24:36', '2016-10-04 13:44:46'),
(72, 27, 1, 'Buenos Aires', NULL, '2015-09-01 14:24:37', '2016-01-12 14:16:01'),
(73, 27, 1, 'General Belgrano', NULL, '2015-09-01 14:24:37', '2015-09-01 14:24:37'),
(75, 27, 1, 'Puerto Madryn', NULL, '2015-09-01 14:24:37', '2015-09-01 14:24:37'),
(85, 27, 1, 'Bizkaia', NULL, '2015-09-01 14:24:37', '2015-09-01 14:24:44'),
(89, 27, 1, 'Junín', NULL, '2015-09-01 14:24:37', '2016-01-12 14:16:02'),
(122, 28, 1, 'San Pablo', NULL, '2015-09-01 14:24:39', '2016-01-12 14:16:03'),
(123, 28, 1, 'Sao Jose dos Campos', NULL, '2015-09-01 14:24:40', '2016-01-12 14:16:03'),
(124, 28, 1, 'Uberlandia', NULL, '2015-09-01 14:24:40', '2016-01-12 14:16:03'),
(125, 28, 1, 'Rio de Janeiro', NULL, '2015-09-01 14:24:40', '2016-01-12 14:16:03'),
(126, 28, 1, 'Florianópolis', NULL, '2015-09-01 14:24:40', '2015-09-01 14:24:41'),
(127, 28, 1, 'Porto Alegre', NULL, '2015-09-01 14:24:40', '2016-01-12 14:16:03'),
(128, 28, 1, 'São Carlos/SP', NULL, '2015-09-01 14:24:40', '2016-10-04 13:44:47'),
(129, 28, 1, 'Brasilia', NULL, '2015-09-01 14:24:40', '2016-10-04 13:44:47'),
(130, 28, 1, 'Recife', NULL, '2015-09-01 14:24:40', '2018-05-04 12:04:20'),
(131, 28, 1, 'Bauru - SP', NULL, '2015-09-01 14:24:40', '2016-10-04 13:44:47'),
(132, 28, 1, 'Goiás', NULL, '2015-09-01 14:24:41', '2016-10-04 13:44:47'),
(136, 33, 1, 'Quito', NULL, '2015-09-01 14:24:41', '2016-02-23 07:45:20'),
(137, 29, 1, 'Bogotá', NULL, '2015-09-01 14:24:41', '2018-08-23 09:44:27'),
(138, 29, 1, 'Cali', NULL, '2015-09-01 14:24:41', '2016-01-12 14:16:01'),
(139, 29, 1, 'otra', NULL, '2015-09-01 14:24:41', '2015-09-01 14:24:43'),
(140, 29, 1, 'Barranquilla', NULL, '2015-09-01 14:24:41', '2016-01-12 14:16:01'),
(141, 29, 1, 'Medellín', NULL, '2015-09-01 14:24:41', '2016-10-04 13:44:48'),
(142, 29, 1, 'Popayán', NULL, '2015-09-01 14:24:41', '2016-10-04 13:44:48'),
(143, 29, 1, 'Cartagena', NULL, '2015-09-01 14:24:41', '2016-11-04 13:10:33'),
(144, 30, 1, 'Albuquerque', NULL, '2015-09-01 14:24:41', '2016-01-12 14:16:01'),
(145, 30, 1, 'Washington', NULL, '2015-09-01 14:24:42', '2016-01-12 14:16:01'),
(146, 30, 1, 'Florida', NULL, '2015-09-01 14:24:42', '2016-10-04 13:44:47'),
(153, 31, 1, 'MÉRIDA', NULL, '2015-09-01 14:24:43', '2016-01-12 14:16:01'),
(154, 31, 1, 'Caracas', NULL, '2015-09-01 14:24:43', '2016-01-12 14:16:01'),
(156, 32, 1, 'La Paz', NULL, '2015-09-01 14:24:43', '2016-10-04 13:44:49'),
(160, 33, 1, 'Loja', NULL, '2015-09-01 14:24:43', '2016-01-12 14:16:01'),
(161, 34, 1, 'MONTERREY', NULL, '2015-09-01 14:24:43', '2016-01-12 14:16:01'),
(162, 34, 1, 'Puebla', NULL, '2015-09-01 14:24:43', '2016-10-04 13:44:49'),
(166, 35, 1, 'Valparaíso', NULL, '2015-09-01 14:24:43', '2016-10-04 13:44:49'),
(167, 35, 1, 'Santiago de Chile', NULL, '2015-09-01 14:24:43', '2016-10-04 13:44:49'),
(168, 35, 1, 'Talca', NULL, '2015-09-01 14:24:43', '2016-01-12 14:16:01'),
(172, 38, 1, 'Colima', NULL, '2015-09-01 14:24:43', '2016-01-12 14:16:01'),
(173, 38, 1, 'VIGO', NULL, '2015-09-01 14:24:43', '2016-01-12 14:16:01'),
(174, 38, 1, 'MADRID', NULL, '2015-09-01 14:24:43', '2016-01-12 14:16:01'),
(175, 38, 1, 'CADIZ', NULL, '2015-09-01 14:24:44', '2016-10-04 13:44:48'),
(176, 38, 1, 'Valencia', NULL, '2015-09-01 14:24:44', '2016-01-12 14:16:01'),
(177, 38, 1, 'Huelva', NULL, '2015-09-01 14:24:44', '2016-10-04 13:44:48'),
(178, 38, 1, 'Girona', NULL, '2015-09-01 14:24:44', '2015-09-01 14:24:44'),
(179, 38, 1, 'Salamanca', NULL, '2015-09-01 14:24:44', '2016-10-04 13:44:48'),
(180, 38, 1, 'VEGAZANA', NULL, '2015-09-01 14:24:44', '2016-10-04 13:44:48'),
(181, 38, 1, 'La Coruña', NULL, '2015-09-01 14:24:44', '2016-10-04 13:44:48'),
(182, 38, 1, 'Granada', NULL, '2015-09-01 14:24:44', '2015-09-01 14:24:45'),
(184, 38, 1, 'Almeria', NULL, '2015-09-01 14:24:44', '2015-09-01 14:24:44'),
(185, 38, 1, 'Barcelona', NULL, '2015-09-01 14:24:44', '2018-12-06 16:27:31'),
(188, 38, 1, 'Sevilla', NULL, '2015-09-01 14:24:44', '2015-09-01 14:24:44'),
(191, 38, 1, 'OVIEDO', NULL, '2015-09-01 14:24:44', '2018-12-06 16:27:31'),
(201, 41, 1, 'PANAMA', NULL, '2015-09-01 14:24:44', '2016-10-04 13:44:49'),
(202, 43, 1, 'Lima', NULL, '2015-09-01 14:24:45', '2016-10-04 13:44:48'),
(203, 44, 1, 'Montevideo', NULL, '2015-09-01 14:24:45', '2016-01-12 14:16:03'),
(220, 29, 1, 'BUCARAMANGA', NULL, '2016-01-12 14:16:01', '2016-02-23 07:43:06'),
(237, 29, 1, 'Arauca', NULL, '2016-02-24 19:40:15', '2017-12-20 08:56:38'),
(238, 29, 1, 'Armenia', NULL, '2016-02-24 19:40:59', '2016-11-04 13:10:33'),
(245, 29, 1, 'Manizales', '0', '2016-02-24 19:44:26', '2016-11-04 13:10:33'),
(247, 29, 1, 'Neiva', NULL, '2016-02-25 19:53:27', '2018-08-02 15:03:10'),
(249, 29, 1, 'Pasto', NULL, '2016-02-25 19:54:32', '2017-02-15 08:10:56'),
(251, 29, 1, 'Tunja', NULL, '2016-02-25 19:55:34', '2017-10-25 13:15:52'),
(264, 5, 1, 'Genova', '16121', '2016-06-16 14:59:12', '2018-12-06 16:27:35'),
(270, 28, 1, 'campinas', NULL, '2016-10-04 13:44:47', '2016-10-04 13:44:47'),
(282, 29, 1, 'Cundinamarca', NULL, '2016-11-04 13:10:33', '2017-12-20 08:56:39'),
(283, 29, 1, 'Boyacá', NULL, '2016-11-04 13:10:33', '2017-12-20 08:56:39'),
(284, 29, 1, 'Tolima', NULL, '2016-11-04 13:10:33', '2017-12-20 08:56:40'),
(285, 29, 1, 'Santander', NULL, '2016-11-04 13:10:33', '2017-12-20 08:56:40'),
(290, 5, 1, 'Napoles', '80139', '2016-12-19 08:52:52', '2018-12-06 16:27:35'),
(300, 29, 1, 'SUCRE', NULL, '2017-10-25 13:15:52', '2017-12-20 08:56:40'),
(303, 28, 1, 'Sao Paulo', NULL, '2017-12-20 08:56:38', '2018-12-06 16:27:07'),
(340, 29, 1, 'Palmira', NULL, '2018-07-31 14:37:31', '2018-07-31 14:37:31');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Client`
--

CREATE TABLE `Client` (
  `id` int(11) NOT NULL,
  `instance_id` int(11) DEFAULT NULL,
  `random_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `redirect_uris` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `secret` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `allowed_grant_types` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuration`
--

CREATE TABLE `configuration` (
  `id` int(11) NOT NULL,
  `instance_id` int(11) NOT NULL,
  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` longtext COLLATE utf8_unicode_ci,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `configuration`
--

INSERT INTO `configuration` (`id`, `instance_id`, `key`, `name`, `value`, `type`, `createdAt`, `updatedAt`) VALUES
(1, 1, 'instance_title', 'Title', 'Directorio Celsius', 'string', '2015-09-01 14:23:20', '2016-02-22 12:40:16'),
(2, 1, 'results_per_page', 'Results per page', '10', 'results', '2015-09-01 14:23:20', '2017-05-29 10:05:39'),
(3, 1, 'email_reply_address', 'Reply to', 'info@prebi.unlp.edu.ar', 'email', '2015-09-01 14:23:20', '2015-09-01 17:59:00'),
(4, 1, 'instance_description', 'Instance description', '<div style=\"text-align:justify\"><br></div><div style=\"text-align:justify\"><img src=\"http://www.istec.org/wp-content/uploads/2016/02/ISTEC-Logos-vert.png\" style=\"width: 117.377px; height: 121px; float: left;\" class=\"pull-left\"></div><div style=\"text-align:justify\"><span style=\"line-height: 1.42857;\">Bienvenido al directorio Celsius, un proyecto de la iniciativa&nbsp;</span><a href=\"http://liblink.istec.org/\" style=\"line-height: 1.42857; background-color: rgb(255, 255, 255);\">LibLink</a><span style=\"line-height: 1.42857;\">&nbsp;del&nbsp;</span><a href=\"http://www.istec.org/\" style=\"line-height: 1.42857; background-color: rgb(255, 255, 255);\">Consorcio Iberoamericano para la Educación en Ciencia y Tecnología (ISTEC)&nbsp;</a><span style=\"line-height: 1.42857;\">. Este sitio web brinda información sobre las instituciones que están participando de la iniciativa LibLink, y permite acceder a las distancias instalaciones (instancias) de Celsius para cada una de ellas.</span><br></div><div style=\"text-align:justify\"><span style=\"font-weight: bold;\"><br></span></div><div style=\"text-align:justify\"><span style=\"font-weight: bold;\">El listado actualizado de instituciones, contactos y catálogos puede accederse <a href=\"https://docs.google.com/document/d/1vBZ6b0mZ2qMbJzqkKdZ_DPPIHmzMjNIHMqvtpjRwFZI/edit\" target=\"_blank\">haciendo clic aquí</a>.</span></div><div style=\"text-align:justify\"><h4><br></h4><h4>Acerca del Software Celsius </h4><div style=\"text-align:justify\"><img src=\"data:image/png;base64,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\" data-filename=\"unlp.png\" style=\"text-align: right; line-height: 1.42857; width: 132px; float: right;\" class=\"pull-right\">Celsius es una herramienta, desarrollada por el equipo de <a href=\"http://prebi.unlp.edu.ar\">PREBI</a>-<a href=\"http://sedici.unlpl.edu.ar\">SEDICI</a> en la <a href=\"http://www.unlp.edu.ar\">UNLP</a>, que permite realizar la gestión de los pedidos de los usuarios, comunicarse con ellos, realizar intercambios bibliográficos con otras instituciones de la red, y obtener estadísticas en tiempo real sobre el funcionamiento del servicio.</div><div style=\"text-align:justify\">Desde la versión 3.0 de este software, la gestión de la red se realiza de manera centralizada.<a href=\"http://www.unlp.edu.ar\">Universidad Nacional de La Plata</a> Esto permite simplificar mucho la creación e instalación de nuevas instancias, y asegura que todas las instancias existentes se mantendrán actualizadas siempre. También se brindan servicios básicos sobre el software instalado, como actualizaciones en el servidor, monitoreo, backups y soporte.<h4> Trabajo en progreso </h4><h4><img src=\"data:image/png;base64,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fQ+EEALpdLqfBp4xY8YrDQ0Np4bDYSQSCbz88stTXnnllSmMMcydO/dZ3w73zYychaUzwO7NatRkMhnxH5bTTz/9riVLllxJKYVpmti1axfbvn37ib4p4kcebdvuZ8qoqgrLsrJaP51Oj3rrrbdG+aZJOp1GIBDA2LFjWy+88MIfDtZ7PsjbuhXvicVi8KfaO++885rrr7/+p5s2bSr9a/uZpgnHcfzWbf3m90WLFv02EAggkUggFAplAyC5DX78NFNd12Ga5n6Lu4aGhiLfz+0vDH259dZbrzryyCM3+7ayby7ltnjOaR7K/AfIfzg45yguLu7Xgs5fMxQUFOBHP/rRuVVVVTwP6ENQ5s+fv+qKK664BQDS6TR6e3vxk5/85Jpx48a11dTUyLPPPvu1F154Yf5Ae7SqqgqlpaUoLi4G57yfIX3OOec88fOf//xrtbW1SCaTcF0XmqZh1KhRqSOOOGKN762oqKhAKBRCSUmJOfCh6O7uLg0EAigpKUFBQQEqKipadu/enXXt3XvvvUddfvnlD5aXl0PTNESjUSxatOjtN998c9yECRNai4uLUVtbC13XUxltr5eUlKCsrAylpaW49dZbv3zppZf+bujQoTwcDqO8vBxnnnnm8y+//HLNggUL3hzM93xQp4+ee+65LxYUFPTLm8gduq7LUCgkL7vssn+o7H/dunVV77333ugdO3YEP6lr2L59e2T37t3qP7p/U1OTejjRGLAbb7xxUD6oCxYsWPnqq6/OTSQSUFUVI0eOfLO+vn7ZEUccsbqwsLApmUyKVCpVIoTA2rVrJ9XU1DRMmjRpLQBs3bo11traWlBaWpoNiW/fvj3S2NhYyTlPRaNRAQBlZWUJznmys7OzIpVKyaKiImvnzp3BgoICB/CKC/r6+oi/fWNjY/azgbJ169bYrl27KioqKnr999auXVtt27YxcuTIntzz6Onp0QsLC7Muwx07dkQKCwvtdevWVZWVlWX95jt37gzW1tZa/rls2bJlhG3bdu6+eQ19CIx33nmnHplsOkVR5Oc///nbt23bZuRuc+edd57JGGsihEjGmLzggguelFJi+fLlY2fNmtU0Y8aM5u9///s/llLixhtv/PHMmTObRo8eLadPn978wgsvzPez3I488si7x48f/9p555130eOPP37q/PnzN/jHuOCCC54844wz3vJfz507d9NLL700d+D5/vznP//mtGnT9o4bNy5+3XXX/aylpYVddNFFj06cOLFn7NixyRNOOOGj9evXlz/11FMnjx8/Pj537txNn/vc515buXLlyFWrVo089dRT33vmmWdOnDhxYs/bb789RUqJ999/f9gZZ5zx1pYtW4qeeuqpk2fPnn3PlClTXp49e/Y9Dz744HmDVUMPyou6++67L/ZTMHVdl0888cSRB9ouGo1u9IHvA+/aa6/9v1NOOUW2tLTIbdu2RZ544olTq6urV95zzz2fbWhoCH/rW9+6pr6+/rE333xz+pgxY176wQ9+8JXMg1D+29/+9rgxY8YkpZTYtGlT6dChQ2VNTY3cuHFjqZQSY8eOTfoPgz+effbZ40aNGtX2hz/84Yxt27ZFli9fPub73//+TWPHjpXvvPPOpDVr1lSecsopHaeccop88sknZX19/XOvvvpq7dVXX/2tyZMnP7Ns2TJ5xBFHyKefflpGo1FZV1cnpZT46KOPqidOnCj//Oc/yyFDhqz51a9+dXLmtzn+H2GDyqeP/gclGo12OY6DcDgMx3GwbNmyowZu8/TTT8/0cx6EEPAXdNOnT3/9/fffxxVXXIG2trb4yy+//NwJJ5zwysUXX/zcyJEj+26//fbbmpubR99///0rARg33HDDfQAwa9as1rKyslY/R+Lpp5++uL6+HpMnT8bjjz9+pR/BSyQS/cLlL7zwwpemTp363rnnnvvk8OHDE7Nmzdr04osvfuHLX/7ybXPmzPlo4sSJe2688cajtmzZgsbGRkQikcTxxx+/a8mSJT+zbVvbsGGD14XWslBWVoYFCxY8cu655768Z8+e4bFYDGvWrEEoFEpedtllLwHA5MmTVyxYsOCBvJfjEJLp06e/HgqF0NfnpTTceeedV4wbN+71Sy655AdXXHHF9SeccMKDl1xyyT2EkCpd99oS+wlKixcvfuqPf/zjjGAwiC9+8YsghMA0zX5uN8MwzNxkoBzfsu57RR5//PHLR48ejdraWjz++OOXZaKQ6sAqFCEEGxh4icVi3ZRSN8efXiCEQCaCqWTsay1zvH4h8csvv/y8eDx+4sMPP/yWT23guq6SecjGf/e73/31woULN+TzoQ8hGTFiRO+3v/3tH4VCIQghYJrm8C1bthx777333vCrX/3qpuXLl1/Q3t5e74ewv/SlL/3ulFNOeRMAXnnllaPD4XDPaaedht7eXsyaNeuCDz74YMIzzzwzc9euXdr//M//XF5cXNz4la985SgppX3bbbd9HgCWLVs2pL29vVTTNHPp0qX1TU1N1e++++7WTZs2rdu4cWPpihUrRgeDwb6enp7SlpaWrCtw3rx5T61YsWLOiy++OK+pqclYuXLlqFmzZr368MMP//fq1avrNm7cWHzvvffePXz4cBQWFkJKyZYuXVr6zDPPfC4cDs8fPXp0NktPCIGioiLtxz/+8dBly5bx7u5ujB8/HrZt6/fff/+80047bf1JJ510XigUwuTJkwdl6HtQu3CWLFny9dGjR6d1XZeapmVLlJCpqq6srNyPXfT222+/ZsyYMclp06btveKKKx7wS6OqqqrkqFGj0iNGjHAfe+yxhVJK/OlPf1pYXV29asqUKY8cf/zxNz/22GMLp0yZ0vad73znjq9+9asP+995zjnnvPy9733vpxMnTuypqamRJ5100ge5x7zhhht+UldXJ6dMmdJ22WWXPdTU1KSeddZZb9TV1ckxY8YkZ82a1fTOO+/UP/bYYwurqqrk8OHD3WOOOaZh2bJlE9555536GTNmND/99NMnTp8+vXnnzp1BKSUef/zxUydOnNizatWqkQ899NB5w4YNk7NmzWqqr6/vfOihhwbtovCw6FP42muvzd66dWt9b29vqRCChsPh3tGjR68aO3bs+zU1NfsVou7YsSPS29tbNFCLvf/++yOnTZu2deD277333thZs2ZtzH1vz549rLKysl9AZffu3Wp1dbWzbdu22IgRI3pzP2tqajKam5uHHXnkkdnv2bBhQ3k6nY7kHtP/joH71tTUmAOPuXXr1tjIkSOzx1m5cuXogVwgg03yjTfzkreh85KXPKDzkpc8oPOSlzyg85IHdF7ykgd0XvKSB3Re8pIHdF7ykgd0XvKAzkte8oDOS17ygM5LXj4R+f8DAC1nZg8nFSd9AAAAAElFTkSuQmCC\" data-filename=\"github_logo-180x180.png\" style=\"width: 146px; height: 146px;\"><br></h4>Celsius 3 es un desarrollo en constante evolución. Se incorporan continuamente nuevas funciones, se resuelven problemas reportados por los usuarios, y se mejoran las capacidades existentes para que las instituciones puedan brindar un servicio de calidad a sus usuarios. Es por ello que decimos que Celsius 3 es un \"trabajo en progreso\", pues no es una herramienta en caja cerrada que se instala y funciona, sino que cambia con el tiempo, se adapta, evoluciona.<br>Si bien esta evolución tiene grandes ventajas para todos los usuarios de la red, presenta importantes desafíos a la hora de generar documentación, tutoriales, manuales, etc., pues se requiere tiempo dedicado a generar y actualizar estos documentos. Es por ello que invitamos a toda la comunidad de usuarios de LibLink a sumar su aporte, ya sea generando documentación o reportando errores y/o propuestas de mejoras.</div><div style=\"text-align:justify\">Las instituciones que cuenten con equipos técnicos con capacidad de desarrollo de software, pueden también realizar sus aportes al proyecto. Todo el código de Celsius 3 se encuentra compartido en&nbsp;<a href=\"https://github.com/sedici/Celsius3/\" style=\"background-color: rgb(255, 255, 255);\">nuestro espacio de GitHub</a>, e invitamos a los desarrolladores a clonar el proyecto, mejorarlo y proponer sus mejoras. </div></div>', 'text', '2015-09-01 14:23:20', '2016-05-04 09:57:31'),
(5, 1, 'instance_information', 'Instance information', 'Iniciativa LibLink - ISTEC', 'text', '2015-09-01 14:23:20', '2016-10-06 13:37:07'),
(6, 1, 'default_language', 'Default language', 'es', 'language', '2015-09-01 14:23:20', '2015-09-01 14:23:20'),
(7, 1, 'confirmation_type', 'Confirmation type', 'email', 'confirmation', '2015-09-01 14:23:20', '2015-09-01 14:23:20'),
(8, 1, 'mail_signature', 'Mail signature', '<p>Celsius Software</p><p>Universidad Nacional de La Plata</p>', 'text', '2015-09-01 14:23:20', '2015-09-01 17:59:00'),
(9, 1, 'api_key', 'Api Key', NULL, 'string', '2015-09-01 14:23:20', '2015-09-01 17:59:00'),
(10, 1, 'min_days_for_send_mail', 'Minimun days for send emails', '5', 'integer', '2015-09-01 14:23:20', '2017-05-29 10:05:39'),
(11, 1, 'max_days_for_send_mail', 'Maximun days for send emails', '10', 'integer', '2015-09-01 14:23:20', '2017-05-29 10:05:39'),
(12, 1, 'instance_logo', 'Instance Logo', 'b7336179c7f907f5242fe83aafe73b7a.png', 'image', '2015-09-01 14:23:20', '2017-05-29 10:05:39'),
(13, 1, 'smtp_host', 'SMTP Host', 'ssl://smtp.gmail.com', 'string', '2015-09-01 14:23:20', '2015-09-01 17:59:00'),
(14, 1, 'smtp_port', 'SMTP Port', '465', 'integer', '2015-09-01 14:23:20', '2017-05-29 10:05:39'),
(15, 1, 'smtp_username', 'SMTP Username', 'info@prebi.unlp.edu.ar', 'string', '2015-09-01 14:23:20', '2015-09-01 17:59:00'),
(16, 1, 'smtp_password', 'SMTP Password', '123456', 'password', '2015-09-01 14:23:20', '2015-09-01 17:59:00'),
(17, 2, 'instance_title', 'Title', 'Proyecto de Enlace de Bibliotecas', 'string', '2015-09-01 14:23:21', '2015-09-15 17:13:50'),
(18, 2, 'results_per_page', 'Results per page', '10', 'results', '2015-09-01 14:23:21', '2017-01-10 09:55:38'),
(19, 2, 'email_reply_address', 'Reply to', 'info@prebi.unlp.edu.ar', 'email', '2015-09-01 14:23:21', '2015-09-01 16:12:51');
INSERT INTO `configuration` (`id`, `instance_id`, `key`, `name`, `value`, `type`, `createdAt`, `updatedAt`) VALUES
(20, 2, 'instance_description', 'Instance description', '<p></p><div class=\"titulo\"><div class=\"titulo\" style=\"text-align: justify; \">El Proyecto de Enlace de Bibliotecas (PREBI) es el nodo de la Universidad Nacional de La Plata en la red de bibliotecas LibLink del Consorcio Iberoamericano para la Educación en Ciencia y Tecnología (<a href=\"www.istec.org\" target=\"_blank\">ISTEC</a>). En esta red participan bibliotecas de América y España, de cuyos catálogos en línea puede solicitar material bibliográfico: <span style=\"font-weight: bold;\">Capítulos de libros, Actas de Congresos, Artículos de revistas, Tesis y Patentes</span>. El trabajo del PREBI consiste en acercar estos materiales bibliográficos a Docentes, Investigadores y alumnos de la UNLP por un lado (servicio de búsqueda), y ofrecer los acervos bibliográficos de las bibliotecas de la UNLP a las instituciones que participan de la red LibLink (servicio de provisión).</div><div class=\"titulo\" style=\"text-align: justify;\">PREBI ha desarrollado íntegramente el software Celsius, el cual permite la gestión integral de las solicitudes de bibliografía. La versión actual, Celsius 3®, posee un diseño de software moderno, fue desarrollada bajo un esquema centralizado y multi instancia, e incluye tecnologías y metodologías de desarrollo propios de los tiempos que corren.&nbsp;</div><div class=\"titulo\" style=\"text-align: justify;\">Para más información sobre los diferentes proyectos que lleva adelante el equipo de PREBI-SEDICI, ingrese <a href=\"http://prebi.unlp.edu.ar/acerca-de-prebi/\" target=\"_blank\">aquí</a>.</div></div>', 'text', '2015-09-01 14:23:21', '2016-03-31 13:01:48'),
(21, 2, 'instance_information', 'Instance information', '<p><span style=\"line-height: 1.42857;\">Puede contactarse con PREBI UNLP en la dirección info@prebi.unlp.edu.ar o llamarnos a los teléfonos</span><br></p><ul><li>+54 221 6447282</li><li>+54 221 4236677/6696 INT 141. </li></ul><p>Departamento de Ciencias Básicas, Facultad de Ingeniería, Universidad Nacional de La Plata, Argentina. Calle 49 y 115 sin número, primer piso<br></p><div><br></div>', 'text', '2015-09-01 14:23:21', '2016-05-11 09:11:41'),
(22, 2, 'default_language', 'Default language', 'es', 'language', '2015-09-01 14:23:21', '2015-09-01 14:23:21'),
(23, 2, 'confirmation_type', 'Confirmation type', 'admin', 'confirmation', '2015-09-01 14:23:21', '2015-09-09 15:34:54'),
(24, 2, 'mail_signature', 'Mail signature', '<p>Proyecto de Enlace de Bibliotecas</p><p>Universidad Nacional de La Plata</p>', 'text', '2015-09-01 14:23:21', '2015-09-02 17:41:30'),
(25, 2, 'api_key', 'Api Key', 'a376c1546d2d68177ec2fec2dbcd56cda950a5ce', 'string', '2015-09-01 14:23:21', '2015-09-01 14:23:21'),
(26, 2, 'min_days_for_send_mail', 'Minimun days for send emails', '5', 'integer', '2015-09-01 14:23:21', '2017-01-10 09:55:38'),
(27, 2, 'max_days_for_send_mail', 'Maximun days for send emails', '10', 'integer', '2015-09-01 14:23:21', '2017-01-10 09:55:38'),
(28, 2, 'instance_logo', 'Instance Logo', '066aa70d9e170a9a26349762e14e4638.png', 'image', '2015-09-01 14:23:21', '2016-07-07 14:17:40'),
(29, 2, 'smtp_host', 'SMTP Host', 'smtp.gmail.com', 'string', '2015-09-01 14:23:21', '2017-01-10 09:55:38'),
(30, 2, 'smtp_port', 'SMTP Port', '465', 'integer', '2015-09-01 14:23:21', '2017-01-10 09:55:38'),
(31, 2, 'smtp_username', 'SMTP Username', 'info@prebi.unlp.edu.ar', 'string', '2015-09-01 14:23:21', '2015-09-01 16:12:51'),
(32, 2, 'smtp_password', 'SMTP Password', '123456', 'password', '2015-09-01 14:23:21', '2015-09-01 16:12:51'),
(33, 1, 'instance_tagline', 'Tagline', 'ISTEC LibLink', 'string', '2015-09-24 16:03:48', '2016-02-22 12:40:16'),
(34, 2, 'instance_tagline', 'Tagline', 'PREBI UNLP', 'string', '2015-09-24 16:03:48', '2016-03-21 09:30:48'),
(35, 1, 'instance_css', 'Instance CSS', '.jumbotron.page-header { \r\n   background-color: #FEFEFE;\r\n   background-image: url(http://celsius3.prebi.unlp.edu.ar/uploads/logos/d91d632a6abbc0927dd64319f1610bd7.png);\r\n   background-repeat: no-repeat;\r\n   background-position: right;\r\n   border: 1px solid #CECECE;\r\n}\r\n\r\n.jumbotron.page-header h1 { \r\n   color: #548BB4;\r\n}\r\n\r\n#news h3 {\r\n    color: #337AB7;\r\n}', 'text', '2015-09-24 16:03:48', '2016-10-05 13:45:37'),
(36, 2, 'instance_css', 'Instance CSS', 'body {\r\nbackground-color: #EEEEEE;\r\n}\r\n.container .jumbotron.page-header \r\n{\r\n  background-color:rgb(0, 95, 99);\r\n  color: #FFF;\r\n} \r\n.container .jumbotron.page-header  a { color: #FFF;}\r\n.container .jumbotron.page-header .dropdown-menu A { color : #23527c; }', 'text', '2015-09-24 16:03:48', '2016-03-04 09:55:30'),
(109, 1, 'instance_staff', 'Staff', 'Instance Staff', 'text', '2016-04-13 11:13:04', '2016-04-13 11:13:04'),
(110, 2, 'instance_staff', 'Staff', '<p><br></p><h2>Directora PrEBi-SEDICI, Directora Iniciativa LibLink (ISTEC)</h2><p>\r\n    <br></p><ul><li>\r\n            <a target=\"_blank\" href=\"http://prebi.unlp.edu.ar/wp-content/uploads/2007/12/Marisa-De-Giusti-CV-270611.pdf\" title=\"Marisa R. De Giusti\">Marisa R. De Giusti</a>\r\n        </li></ul><p>\r\n    \r\n    <br></p><h2>Diseño gráfico</h2><p>\r\n    <br></p><ul><li>Lucas Folegotto</li></ul><p>\r\n\r\n    <br></p><h2>Referencia</h2><p>\r\n    <br></p><ul><li>Carlos Nusch</li><li>Esteban Fernandez</li><li>Bruno Percivale</li><li>Urbina, Emmanuel</li></ul><p>\r\n    \r\n    <br></p><h2>Desarrollo y mantenimiento de software</h2><p>\r\n    <br></p><ul><li>Gianotti, Jorge Oscar</li><li>Lic. Sobrado Ariel</li><li>Dr. Villarreal Gonzalo</li></ul><p><br></p>', 'text', '2016-04-13 11:13:04', '2016-04-13 11:14:10'),
(115, 1, 'download_time', 'Download time in hours', '24', 'integer', '2016-05-13 13:12:52', '2017-05-29 10:05:39'),
(116, 2, 'download_time', 'Download time in hours', '24', 'integer', '2016-05-13 13:12:52', '2017-01-10 09:55:38'),
(221, 1, 'show_news', 'Show news', '', 'boolean', '2016-10-03 14:03:26', '2017-05-29 10:05:39'),
(222, 2, 'show_news', 'Show news', '1', 'boolean', '2016-10-03 14:03:26', '2017-01-10 09:55:38'),
(337, 1, 'smtp_protocol', 'SMTP Protocol', 'ssl', 'select', '2017-01-10 08:52:31', '2017-01-10 08:52:31'),
(338, 2, 'smtp_protocol', 'SMTP Protocol', 'ssl', 'select', '2017-01-10 08:52:31', '2017-01-10 08:52:31'),
(353, 1, 'smtp_status', 'SMTP Status', '', 'hidden', '2017-01-10 08:52:31', '2017-05-29 10:05:39'),
(354, 2, 'smtp_status', 'SMTP Status', '1', 'hidden', '2017-01-10 08:52:31', '2017-01-10 09:55:38'),
(369, 1, 'resetting_check_email_title', 'Resetting title', '', 'string', '2017-01-10 08:52:31', '2017-01-10 08:52:31'),
(370, 2, 'resetting_check_email_title', 'Resetting title', '', 'string', '2017-01-10 08:52:31', '2017-01-10 08:52:31'),
(385, 1, 'resetting_check_email_text', 'Resetting text', '', 'text', '2017-01-10 08:52:31', '2017-01-10 08:52:31'),
(386, 2, 'resetting_check_email_text', 'Resetting text', '', 'text', '2017-01-10 08:52:31', '2017-01-10 08:52:31'),
(401, 1, 'resetting_password_already_requested_title', 'Password already requested title', '', 'string', '2017-01-10 08:52:31', '2017-01-10 08:52:31'),
(402, 2, 'resetting_password_already_requested_title', 'Password already requested title', '', 'string', '2017-01-10 08:52:31', '2017-01-10 08:52:31'),
(417, 1, 'resetting_password_already_requested_text', 'Password already requested text', '', 'text', '2017-01-10 08:52:31', '2017-01-10 08:52:31'),
(418, 2, 'resetting_password_already_requested_text', 'Password already requested text', '', 'text', '2017-01-10 08:52:31', '2017-01-10 08:52:31'),
(433, 1, 'registration_wait_confirmation_title', 'Wait confirmation title', '', 'string', '2017-01-10 08:52:31', '2017-01-10 08:52:31'),
(434, 2, 'registration_wait_confirmation_title', 'Wait confirmation title', '', 'string', '2017-01-10 08:52:31', '2017-01-10 08:52:31'),
(449, 1, 'registration_wait_confirmation_text', 'Wait confirmation text', '', 'text', '2017-01-10 08:52:31', '2017-01-10 08:52:31'),
(450, 2, 'registration_wait_confirmation_text', 'Wait confirmation text', '', 'text', '2017-01-10 08:52:31', '2017-01-10 08:52:31'),
(465, 1, 'home_home_btn_text', 'Home button text', '', 'string', '2017-01-10 08:52:31', '2017-01-10 08:52:31'),
(466, 2, 'home_home_btn_text', 'Home button text', '', 'string', '2017-01-10 08:52:31', '2017-01-10 08:52:31'),
(481, 1, 'home_news_btn_text', 'News button text', '', 'string', '2017-01-10 08:52:31', '2017-01-10 08:52:31'),
(482, 2, 'home_news_btn_text', 'News button text', '', 'string', '2017-01-10 08:52:31', '2017-01-10 08:52:31'),
(497, 1, 'home_news_visible', '', '1', 'boolean', '2017-01-10 08:52:31', '2017-05-29 10:05:39'),
(498, 2, 'home_news_visible', '', '1', 'boolean', '2017-01-10 08:52:31', '2017-01-10 09:55:38'),
(513, 1, 'home_information_btn_text', '', '', 'string', '2017-01-10 08:52:31', '2017-01-10 08:52:31'),
(514, 2, 'home_information_btn_text', '', '', 'string', '2017-01-10 08:52:31', '2017-01-10 08:52:31'),
(529, 1, 'home_information_visible', '', '1', 'boolean', '2017-01-10 08:52:31', '2017-05-29 10:05:39'),
(530, 2, 'home_information_visible', '', '1', 'boolean', '2017-01-10 08:52:31', '2017-01-10 09:55:38'),
(545, 1, 'home_statistics_btn_text', '', '', 'string', '2017-01-10 08:52:31', '2017-01-10 08:52:31'),
(546, 2, 'home_statistics_btn_text', '', '', 'string', '2017-01-10 08:52:31', '2017-01-10 08:52:31'),
(561, 1, 'home_statistics_visible', '', '1', 'boolean', '2017-01-10 08:52:31', '2017-05-29 10:05:39'),
(562, 2, 'home_statistics_visible', '', '1', 'boolean', '2017-01-10 08:52:31', '2017-01-10 09:55:38'),
(577, 1, 'home_help_btn_text', '', '', 'string', '2017-01-10 08:52:31', '2017-01-10 08:52:31'),
(578, 2, 'home_help_btn_text', '', '', 'string', '2017-01-10 08:52:31', '2017-01-10 08:52:31'),
(593, 1, 'home_help_visible', '', '1', 'boolean', '2017-01-10 08:52:31', '2017-05-29 10:05:39'),
(594, 2, 'home_help_visible', '', '1', 'boolean', '2017-01-10 08:52:31', '2017-01-10 09:55:38');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contact`
--

CREATE TABLE `contact` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `type_id` int(11) NOT NULL,
  `instance_id` int(11) DEFAULT NULL,
  `institution_id` int(11) NOT NULL,
  `owning_instance_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `surname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL,
  `deletedAt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contact_type`
--

CREATE TABLE `contact_type` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `counter`
--

CREATE TABLE `counter` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` int(11) NOT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `country`
--

CREATE TABLE `country` (
  `id` int(11) NOT NULL,
  `instance_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `abbreviation` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `country`
--

INSERT INTO `country` (`id`, `instance_id`, `name`, `abbreviation`, `createdAt`, `updatedAt`) VALUES
(2, 1, 'CANADA', 'CAN', '2015-09-01 14:24:26', '2016-01-12 14:16:01'),
(4, 1, 'NORUEGA', 'NOR', '2015-09-01 14:24:27', '2016-01-12 14:16:01'),
(5, 1, 'ITALIA', 'ITA', '2015-09-01 14:24:28', '2016-01-12 14:16:03'),
(7, 1, 'ALEMANIA', 'DE', '2015-09-01 14:24:31', '2016-01-12 14:16:01'),
(27, 1, 'Argentina', 'ARG', '2015-09-01 14:24:34', '2016-01-12 14:16:01'),
(28, 1, 'Brasil', 'BRA', '2015-09-01 14:24:39', '2016-01-12 14:16:02'),
(29, 1, 'Colombia', 'COL', '2015-09-01 14:24:41', '2016-01-12 14:16:01'),
(30, 1, 'United States of America', 'USA', '2015-09-01 14:24:41', '2016-01-12 14:16:01'),
(31, 1, 'Venezuela', 'VEN', '2015-09-01 14:24:43', '2016-01-12 14:16:01'),
(32, 1, 'Bolivia', 'BOL', '2015-09-01 14:24:43', '2016-01-12 14:16:03'),
(33, 1, 'Ecuador', 'ECU', '2015-09-01 14:24:43', '2016-01-12 14:16:01'),
(34, 1, 'Mexico', 'MX', '2015-09-01 14:24:43', '2016-01-12 14:16:01'),
(35, 1, 'Chile', 'CHL', '2015-09-01 14:24:43', '2016-01-12 14:16:01'),
(36, 1, 'Costa Rica', 'CRI', '2015-09-01 14:24:43', '2016-01-12 14:16:01'),
(37, 1, 'República Dominicana', 'DOM', '2015-09-01 14:24:43', '2016-01-12 14:16:03'),
(38, 1, 'España', 'ESP', '2015-09-01 14:24:43', '2016-01-12 14:16:01'),
(39, 1, 'Honduras', 'HND', '2015-09-01 14:24:44', '2016-01-12 14:16:03'),
(40, 1, 'Nicaragua', 'NIC', '2015-09-01 14:24:44', '2016-01-12 14:16:03'),
(41, 1, 'Panama', 'PAN', '2015-09-01 14:24:44', '2016-01-12 14:16:03'),
(42, 1, 'Paraguay', 'PRY', '2015-09-01 14:24:44', '2016-01-12 14:16:03'),
(43, 1, 'Peru', 'PER', '2015-09-01 14:24:45', '2016-01-12 14:16:01'),
(44, 1, 'Uruguay', 'URY', '2015-09-01 14:24:45', '2016-01-12 14:16:03'),
(45, 1, 'Rusia', 'RU', '2015-09-01 14:24:45', '2016-01-12 14:16:01'),
(52, 1, 'WEB1', 'WEB', '2016-01-12 14:16:01', '2016-10-04 13:44:49'),
(57, 1, 'El Salvador', 'SV', '2016-11-04 13:10:34', '2017-02-15 08:10:58');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `custom_user_field`
--

CREATE TABLE `custom_user_field` (
  `id` int(11) NOT NULL,
  `instance_id` int(11) NOT NULL,
  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `value` longtext COLLATE utf8_unicode_ci,
  `private` tinyint(1) NOT NULL,
  `required` tinyint(1) NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `orden` int(11) DEFAULT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `custom_user_value`
--

CREATE TABLE `custom_user_value` (
  `id` int(11) NOT NULL,
  `field_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `value` longtext COLLATE utf8_unicode_ci,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `email`
--

CREATE TABLE `email` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `instance_id` int(11) NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `text` longtext COLLATE utf8_unicode_ci NOT NULL,
  `sent` tinyint(1) NOT NULL,
  `attempts` int(11) NOT NULL,
  `error` tinyint(1) NOT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `event`
--

CREATE TABLE `event` (
  `id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `operator_id` int(11) DEFAULT NULL,
  `state_id` int(11) NOT NULL,
  `instance_id` int(11) NOT NULL,
  `catalog_id` int(11) DEFAULT NULL,
  `provider_id` int(11) DEFAULT NULL,
  `request_event_id` int(11) DEFAULT NULL,
  `remote_state_id` int(11) DEFAULT NULL,
  `remote_request_id` int(11) DEFAULT NULL,
  `receive_event_id` int(11) DEFAULT NULL,
  `remote_instance_id` int(11) DEFAULT NULL,
  `observations` longtext COLLATE utf8_unicode_ci,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL,
  `deletedAt` datetime DEFAULT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `result` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reclaimed` tinyint(1) DEFAULT NULL,
  `cancelled` tinyint(1) DEFAULT NULL,
  `cancelledByUser` tinyint(1) DEFAULT NULL,
  `deliveryType` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `approved` tinyint(1) DEFAULT NULL,
  `annulled` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ext_log_entries`
--

CREATE TABLE `ext_log_entries` (
  `id` int(11) NOT NULL,
  `action` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `logged_at` datetime NOT NULL,
  `object_id` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `object_class` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `version` int(11) NOT NULL,
  `data` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:array)',
  `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ext_translations`
--

CREATE TABLE `ext_translations` (
  `id` int(11) NOT NULL,
  `locale` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `object_class` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `field` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `foreign_key` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `file`
--

CREATE TABLE `file` (
  `id` int(11) NOT NULL,
  `request_id` int(11) DEFAULT NULL,
  `instance_id` int(11) DEFAULT NULL,
  `event_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `comments` longtext COLLATE utf8_unicode_ci,
  `enabled` tinyint(1) NOT NULL,
  `downloaded` tinyint(1) NOT NULL,
  `pages` int(11) NOT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `file_download`
--

CREATE TABLE `file_download` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `instance_id` int(11) NOT NULL,
  `ip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `userAgent` longtext COLLATE utf8_unicode_ci,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hive`
--

CREATE TABLE `hive` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `hive`
--

INSERT INTO `hive` (`id`, `name`, `createdAt`, `updatedAt`) VALUES
(1, 'LibLink', '2015-09-01 14:23:20', '2015-09-01 14:23:20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `instance`
--

CREATE TABLE `instance` (
  `id` int(11) NOT NULL,
  `hive_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `abbreviation` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `website` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `host` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `invisible` tinyint(1) DEFAULT NULL,
  `latitud` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `longitud` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `observaciones` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `instance`
--

INSERT INTO `instance` (`id`, `hive_id`, `name`, `abbreviation`, `website`, `email`, `enabled`, `createdAt`, `updatedAt`, `type`, `url`, `host`, `invisible`, `latitud`, `longitud`, `observaciones`) VALUES
(1, NULL, 'Directorio', 'Directorio', 'http://directorio.localhost', 'directorio@localhost', 0, '2015-09-01 14:23:20', '2015-09-01 14:23:20', 'current', 'directory', 'directorio.localhost', NULL, NULL, NULL, NULL),
(2, 1, 'Universidad Nacional de La Plata', 'PREBI', 'http://unlp.localhost', 'unlp@localhost', 1, '2015-09-01 14:23:21', '2017-09-15 08:54:25', 'current', 'unlp', 'unlp.localhost', 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `journal`
--

CREATE TABLE `journal` (
  `id` int(11) NOT NULL,
  `instance_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `abbreviation` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `responsible` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ISSN` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ISSNE` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `frecuency` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `journal`
--

INSERT INTO `journal` (`id`, `instance_id`, `name`, `abbreviation`, `responsible`, `ISSN`, `ISSNE`, `frecuency`, `createdAt`, `updatedAt`) VALUES
(1, 1, 'ADVANCES IN QUANTUM CHEMISTRY', 'Adv. Quantum Chem.', 'New York, N.Y. : Academic Press, 1964- // Orlando, Fla., US : Academic Press', '00653276', NULL, 'Anual // Irregular', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(2, 1, 'JOURNAL OF MOLECULAR STRUCTURE', 'J. Mol. Struct.', 'Amsterdam, NL : Elsevier Scientific', '00222860', NULL, 'Bimonthly, 1967-July 1969 ; Monthly irrregular, Aug. 1969-', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(3, 1, 'SCIENCE', 'Science (Washington)', 'Washington, US : American Association For The Advancement Of Science', '00368075', NULL, 'Weekly (except last week in Dec.)', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(4, 1, 'SOLID STATE COMMUNICATIONS', 'Solid State Commun.', 'New York, N.Y. : Pergamon Press, 1963-', '00381098', NULL, 'Monthly 1963-19 ; semimonthly Jan. 1, 1976-19 ; 48 no. a year <, June 1980->', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(5, 1, 'FUZZY SETS AND APPLICATIONS: QUALITY CONTROL HANDBOOK  [4TH EDITION]', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(6, 1, 'FUZZY SETS AND APPLICATIONS: SELECTED PAPERS', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(7, 1, 'JOURNAL OF CHROMATOGRAPHY', 'J. Chromatogr.', 'Amsterdam, NL : Elsevier Scientific Publishing', '00219673', NULL, 'Two no. per vol / Irregular', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(8, 1, 'AMERICAN MIDLAND NATURALIST', 'Am. midl. nat', 'Notre Dame, Ind. : University of Notre Dame, 1909-', '00030031', NULL, 'Bimonthly , Quarterly', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(9, 1, 'SCIENTIFIC AMERICAN', '', '', '0036-8733', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(10, 1, 'MAMMALIA', '', '', '0025-1461', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(11, 1, 'CHEMICAL PHYSICS LETTERS', 'Chem. Phys. Lett.', 'Amsterdam, NL : North-Holland', '00092614', NULL, 'Semanal', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(12, 1, 'BIOMETALS', 'Biometals', 'Oxford, OX : Rapid Communications of Oxford, c1992-', '09660844', NULL, 'Quarterly', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(13, 1, 'CELESTIAL MECHANICS', 'Celest. Mech.', 'Dordrecht, Holland : D. Reidel Pub. Co., 1969-1989', '00088714', NULL, '12 no. a year 1981-1989', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(14, 1, 'STUDIA MATHEMATICA', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(15, 1, 'ANNALES DE CHIMIE', 'Ann. chim. (Paris)', 'Paris ; New York, N.Y. : Masson et cie, 1914-1977', '00033936', NULL, 'Monthly 1961-1966 ; bimonthly 1967-1977', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(17, 1, 'MATERIALS CHEMISTRY AND PHYSICS', 'Mat. Chem. and Physics', '', '0254-0584', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(18, 1, 'JOURNAL OF ALLOYS AND COMPOUNDS', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(19, 1, 'ZEITSCHRIFT FUR PHYSIKALISCHE CHEMIE (FRANKFURT AM MAIN, GERMANY)', 'Z Phys Chem (N F)', '', '0942-9352', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(20, 1, 'ZEITSCHRIFT FUR SAUGETIERKUNDE', '', '', '0044-3468', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(21, 1, 'COLLOID AND POLYMER SCIENCE', 'Colloid Polym. Sci.', 'Darmstadt, Alemanha, DE : Dietrich Steinkopff Verlag', '0303402X', NULL, 'Mensal', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(22, 1, 'STUDIES IN MATHEMATICS', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(23, 1, 'CHROMATOGRAPHIA', 'Chromatographia', 'Braunschweig, Germany [etc.] : Friedrich Vieweg & Sohn ; New York, N.Y. : Pergamon Press, 1968-', '00095893', NULL, 'Bimonthly 1968; monthly 1969-', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(24, 1, 'SPECTROSCOPY LETTERS', '', '', '0038-7010', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(26, 1, 'BULLETIN [NEW SERIES] OF THE AMERICAN MATHEMATICAL SOCIETY', '', 'American Mathematical Society', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(27, 1, 'TRANSACTIONS OF THE AMERICAN MICROSCOPICAL SOCIETY', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(28, 1, 'JOURNAL OF MOLECULAR SPECTROSCOPY', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(29, 1, 'JOURNAL OF COMPUTATIONAL PHYSICS', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(30, 1, 'JOURNAL OF CHEMICAL PHYSICS', 'J. Chem. Phys.', 'New York, US : American Institute Of Physics', '00219606', NULL, 'Bimensal', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(31, 1, 'BIOCHEMICAL JOURNAL (1984)', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(32, 1, 'BIOTROPICA', 'Biotropica', 'Washington, US : Association For Tropical Biology, 1969-', '00063606', NULL, 'Frequency varies ;  Four issues yearly 1983-', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(33, 1, 'ANNUAL REVIEW OF ECOLOGY AND SYSTEMATICS', 'Ann. rev. ecolog. syst.', 'Palo Alto, Calif. Annual Reviews', '00664162', NULL, 'Anual', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(34, 1, 'AMERICAN NATURALIST', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(35, 1, 'AMERICAN JOURNAL OF BOTANY', 'Am. j. bot', 'Lawrence, Kan., etc. // Davis,(Ca) // Columbus, Ohio, US : Botanical Society of America, 1914-', '00029122', NULL, 'Monthly (except bimonthly May-June, Nov.-Dec.) 1975-', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(36, 1, 'ZEITSCHRIFT FUR PHYSIK. C. PARTICLES AND FIELDS', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(37, 1, 'JOURNAL OF FISH BIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(38, 1, 'CANADIAN JOURNAL OF FISHERIES AND AQUATIC SCIENCES', 'Can. J. Fish. Aquat. Sci.', 'Ottawa, CA : National Research Council Of Canada // Fisheries and Oceans, 1980- // Supply and Servic', '0706652X', NULL, 'Monthly', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(39, 1, 'JOURNAL OF THE HELMINTHOLOGICAL SOCIETY OF WASHINGTON', 'J Helm Soc Wash', '', '1049-233X', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(40, 1, 'SYSTEMATIC PARASITOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(41, 1, 'FOLIA PARASITOLOGICA', 'Folia parasitologica', 'Prague, CS : Publishing House Of The Czechoslovak Academy Of Sciences, 1966-', '00155683', NULL, 'Trimestral', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(42, 1, 'IMA JOURNAL OF APPLIED MATHEMATICS', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(43, 1, 'JOURNAL OF THE INSTITUTE OF MATHEMATICS AND ITS APPLICATIONS', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(44, 1, 'PHYSICA. C. SUPERCONDUCTIVITY', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(45, 1, 'PHYSICA. B. CONDENSED MATTER', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(46, 1, 'MATERIALS SCIENCE AND ENGINEERING', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(47, 1, 'JOURNAL DE PHYSIQUE', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(48, 1, 'EUROPHYSICS LETTERS', 'Europhys. Lett.', 'Paris, FR : Editions de Physique, 1986-', '02955075', NULL, 'Frequency varies, 1986-1992;  Twelve no. a year', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(49, 1, 'STATISTICAL THEORY AND METHODOLOGY IN SCIENCE AND ENGINEERING', '', 'Brownlee, K.A.', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(50, 1, 'JOURNAL OF COMPUTATIONAL CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(51, 1, 'CHEMICAL ENGINEERING SCIENCE = JOURNAL INTERNATIONAL DE GENIE CHIMIQUE', 'Chem. Eng. Sci.', 'New York, US : Pergamon Press', '00092509', NULL, 'Bimonthly Oct. 1951-<Dec. 1955> ; monthly <1976->', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(52, 1, 'NUCLEAR PHYSICS. B', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(53, 1, 'PARASITOLOGY RESEARCH', '', '', '0932-0113', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(54, 1, 'PARASITOLOGY', 'Parasitology', 'Cambridge, Inglaterra , GB : Cambridge University Press', '00311820', NULL, 'Bimonthly', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(55, 1, 'CANADIAN JOURNAL OF ZOOLOGY = JOURNAL CANADIEN DE ZOOLOGIE', 'Can. J. Zool.', 'Ottawa, CA : National Research Council Of Canada, 1951-', '00084301', NULL, 'Bimonthly, 1951-70 ; Monthly, 1971-', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(56, 1, 'JOURNAL OF PARASITOLOGY', 'J. Parasitol.', 'Lancaster, Pa., US // Lawrence, Kan., etc. : American Society of Parasitologists, 1914-', '0022-3395 (e) 1937-2345', NULL, 'Bimonthly', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(57, 1, 'JOURNAL OF PHYSICS. A. MATHEMATICAL AND GENERAL', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(58, 1, 'IEEE TRANSACTIONS ON ELECTROMAGNETIC COMPATIBILITY', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(59, 1, 'INORGANIC CHEMISTRY', 'Inorg. Chem.', 'Washington, US // Easton, Pa.: American Chemical Society', '00201669', NULL, 'Frequency varies ; Biweekly, <1996->', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(60, 1, 'AMERICAN JOURNAL OF PHYSICS', 'Am. J. Phys', 'College Park, MD / Woodbury, N.Y., etc. Published for the American Association of Physics Teachers b', '00029505', NULL, 'Bimonthly, 1940-1947 / Monthly <, Sept. 1975->', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(61, 1, 'PREPARATIVE BIOCHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(62, 1, 'JOURNAL OF MEDICINAL CHEMISTRY', 'J. Med. Chem.', 'Washington, US // Easton, Pa.: American Chemical Society', '00222623', NULL, 'Bimonthly 1963- ;  monthly , Jan. 1976-', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(63, 1, 'ZEITSCHRIFT FUR ANORGANISCHE UND ALLGEMEINE CHEMIE [1950-]', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(64, 1, 'APPLIED OPTICS', 'Appl. opt.', 'New York, etc., Optical Society of America', '00036935', NULL, 'Semimonthly, 1978- ; Monthly, 1963-1977 ; Bimonthly, 1962 ; 3 times a month, <Mar. 1994->', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(65, 1, 'FRESENIUS\' JOURNAL OF ANALYTICAL CHEMISTRY', 'Fresenius J. Anal. Chem.', 'Berlin : Springer International, 1990-', '09370633', NULL, 'Irregular', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(66, 1, 'CHEMOSPHERE', 'Chemosphere', 'Oxford ; New York, N.Y. : Pergamon Press, 1972-', '00456535', NULL, 'Bimonthly, Jan. 1972-; Monthly <, v. 6, no. 4->', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(68, 1, 'CANADIAN JOURNAL OF PHYSIOLOGY AND PHARMACOLOGY', 'Can. J. Physiol. Pharmacol.', 'Ottawa, Ont. : National Research Council Canada, 1964-', '00084212', NULL, 'bimonthly 1964-68; Monthly 1969-73; Bimonthly 1974-', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(69, 1, 'PHARMACOLOGY & TOXICOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(70, 1, 'JOURNAL OF CHEMICAL INFORMATION AND COMPUTER SCIENCES', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(71, 1, 'TRANSACTIONS OF THE AMERICAN MATHEMATICAL SOCIETY', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(72, 1, 'MEASUREMENT SCIENCE AND TECHNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(73, 1, 'CANADIAN JOURNAL OF PHYSICS', 'Can. J. Phys.', 'Ottawa, CA : National Research Council Of Canada, 1951-', '00084204', NULL, 'Bimonthly, 1951-1953 ; Monthly, 1954-1967 ; Semi-monthly, 1968-1977 ; Monthly, 1978-', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(74, 1, 'PLANT AND CELL PHYSIOLOGY', '', '', '0032-0781', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(75, 1, 'JOURNAL OF MATHEMATICAL PHYSICS', 'J. Math. Phys.', 'New York, US : American Institute Of Physics', '00222488', NULL, 'Bimonthly, 1960-62 ; Monthly, 1963-', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(76, 1, 'RECHERCHES SUR QUELQUES NEMATODES PARASITES DE POISSONS DE LA MER DU NORD', '', 'Punt, A.', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(77, 1, 'MATERIALS LETTERS', 'Mater Lett', '', '0167-577X', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(78, 1, 'IEEE TRANSACTIONS ON COMPUTER-AIDED DESIGN OF INTEGRATED CIRCUITS AND SYSTEMS', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(79, 1, 'JOURNAL OF RAMAN SPECTROSCOPY', '', '', '1097-4555', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(80, 1, 'ENVIRONMENTAL ENTOMOLOGY', 'Environ. Entomol.', 'College Park, Md.: Entomological Society of America, 1972-', '0046225X', NULL, 'Bimonthly', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(81, 1, 'JOURNAL OF STRUCTURAL CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(82, 1, 'PHYSICS LETTERS. PART A', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(83, 1, 'ANNALS OF PHYSICS', 'Ann. phys', 'New York, Academic Press', '00034916', NULL, 'Monthly (semimonthly in Sept. and Oct.)', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(84, 1, 'APPLIED PHYSICS LETTERS', '', 'New York, American Institute of Physics', '00036951', NULL, 'Monthly 1962 ; Semimonthly, 19 - // Semanal', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(85, 1, 'LIFE SCIENCES', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(86, 1, 'COMPUTER PHYSICS COMMUNICATIONS', 'Comput. Phys. Commun.', 'Amsterdam : North-Holland Pub. Co., 1969-', '00104655', NULL, 'Eight issues yearly , 1980-; 12 issues yearly , 1982-', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(87, 1, 'ASTROPHYSICAL JOURNAL', 'Astrophys. J.', 'Chicago, Ill., US: University of Chicago Press', '0004637X', NULL, 'Frequency varies, 1895- ; 3 times a month, <Jan. 1992->', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(88, 1, 'STRUCTURAL CHEMISTY', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(89, 1, 'OPTICA ACTA', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(90, 1, 'OPTICS COMMUNICATIONS', 'Optic Comm', '', '0030-4018', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(91, 1, 'JOURNAL OF MOLECULAR CATALYSIS. A. CHEMICAL', 'J. Mol. Catal. A. Chem.', 'Amsterdam, NL : Elsevier Science, 1995-', '13811169', NULL, 'Semimonthly // Semestral', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(92, 1, 'ZEITSCHRIFT FUR PHYSIK. B. CONDENSED MATTER', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(93, 1, 'JOURNAL OF MOLECULAR CATALYSIS', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(94, 1, 'SYNTHESIS AND REACTIVITY IN INORGANIC AND METAL-ORGANIC CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(95, 1, 'ENVIRONMENTAL TOXICOLOGY AND RISK ASSESSMENT', 'Environ. Toxicol. Risk Assess.', 'Philadelphia, PA : ASTM, c1993-', '1071720X', NULL, 'Annual', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(96, 1, 'CATALYSIS TODAY', 'Catal. Today', 'Amsterdam ; New York : Elsevier Science Publishers, 1987-', '09205861', NULL, 'Five no. a year // Bimestral', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(97, 1, 'ANGEWANDTE CHEMIE [INTERNATIONAL EDITION IN ENGLISH]', 'Angew. chem., int. ed. engl.', 'Weinheim/Bergstr. : Verlag Chemie ; New York : Academic Press, c1962-', '05700833', NULL, 'Frequency varies, 1962- ; Semimonthly, <1999->', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(98, 1, 'ANNUAL REVIEW OF PLANT PHYSIOLOGY AND PLANT MOLECULAR BIOLOGY', 'Annu. Rev. Plant Physiol. Plant Mol. Biol.', 'Palo Alto, Calif. : Annual Reviews Inc., c1988-c2001', '10402519', NULL, 'Annual', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(99, 1, 'ASPECTS OF DEGRADATION AND STABILIZATION OF POLYMERS', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(100, 1, 'SIAM JOURNAL ON SCIENTIFIC AND STATISTICAL COMPUTING', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(101, 1, 'FUNCTIONAL ANALYSIS AND ITS APPLICATIONS', 'Funct. Anal. Appl.', 'New York, US : Plenum Publishing // Consultants Bureau, 1967-', '00162663', NULL, 'Quarterly', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(102, 1, 'PHYSICS TEACHER', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(103, 1, 'INORGANICA CHIMICA ACTA', 'Inorg. Chim. Acta', 'Lausanne, Suica, CH : Elsevier Sequoia', '00201693', NULL, 'Frequency varies 1967-88 ; semimonthly 1989-', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(104, 1, 'JOURNAL OF PHOTOCHEMISTRY AND PHOTOBIOLOGY A: CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(105, 1, 'BIOCHEMISTRY AND MOLECULAR BIOLOGY INTERNATIONAL', 'Biochem. Mol. Biol. Int.', 'Marrickville, Australia, AU : Academic Press', '10399712', NULL, 'Bimestral', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(106, 1, 'PHISICAL REVIEW LETTERS', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(107, 1, 'INTERNATIONAL JOURNAL QUANTUM CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(108, 1, 'PAKISTAN JOURNAL OF SCIENTIFIC AND INDUSTRIAL RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(109, 1, 'OECOLOGIA', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(110, 1, 'LANGMUIR', '', '', '0743-7463', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(111, 1, 'JOURNAL OF UROLOGY', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(112, 1, 'BIOCHEMICAL AND BIOPHYSICAL RESEARCH COMMUNICATIONS', 'Biochem. Biophys. Res. Commun.', 'Orlando, Fla., US // San Diego [etc.] : Academic Press, 1959-', '0006291X', NULL, 'Monthly 1959-April 1962 ; semimonthly May 1962-', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(113, 1, 'JOURNAL OF THE AMERICAN CHEMICAL SOCIETY', 'J. Am. Chem. Soc.', 'Washington, etc // Easton, Pa., US : American Chemical Society', '00027863', NULL, 'Monthly, 1879- ; Biweekly, -1994 ; Weekly (except first week of Jan.), 1995- // Quincenal', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(114, 1, 'PROCEEDINGS OF THE CAMBRIDGE PHILOSOPHICAL SOCIETY, MATHEMATICAL AND PHYSICAL SCIENCES', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(115, 1, 'JOURNAL OF PHYSICAL CHEMISTRY', 'J. Phys. Chem. (1952)', 'Ithaca, Ny, US : American Chemical Society', '00223654', NULL, 'Biweekly, 1952- ;  Weekly, except for last week of Dec., -1996', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(116, 1, 'THERMOCHIMICA ACTA', 'Thermochim. Acta', 'Amsterdam, NL : Elsevier Scientific Publishing, 1970-', '00406031', NULL, 'Semimonthly', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(117, 1, 'ORGANOMENTAL', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(118, 1, 'MOLECULAR PHYSICS', '', 'London, GB : Taylor & Francis, 1958-', '00268976', NULL, 'Quarterly ;  Semi-monthly (except one issue in Jan., Mar., May, July, Sept., andNov.) <, Dec. 1980->', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(119, 1, 'ACTA CRYSTALLOGRAPHICA', 'Acta crystallogr.', 'Copenhagen,[etc.] Published for the International Union of Crystallography by Munksgaard [etc.]', '0365110X', NULL, 'Six no. a year 1948-52 ; 12 no. a year 1', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(120, 1, 'CONTROL ENGINEERING PRACTICE', 'Control Eng. Pract.', 'Oxford, Inglaterra, GB : Pergamon Press', '09670661', NULL, 'Bimestral', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(121, 1, 'ZOOLOGICA SCRIPTA', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(122, 1, 'CHEMISCHE BERICHTE', 'Chem. Ber.', 'Weinheim/Bergsr. [etc.] : VCH Verlagsgesellschaft mbH. [etc.], 1947-1996', '00092940', NULL, 'Monthly', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(123, 1, 'RUSSIAN JOURNAL OF INORGANIC CHEMISTRY', 'Russ. J. Inorg. Chem.', 'Letchworth, England : British Library Lending Division with the cooperation of the Royal Society of', '00360236', NULL, 'Monthly', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(124, 1, 'JOURNAL OF STATISTICAL PHYSICS', 'J. Stat. Phys.', 'New York, US : Plenum Press', '00224715', NULL, 'Quarterly 1969- ; monthly <June 1977->', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(125, 1, 'PURE AND APPLIED CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(126, 1, 'CHEMISTRY LETTERS', 'Chem. Lett.', 'Tokyo, JP : Chemical Society Of Japan', '03667022', NULL, 'Monthly', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(127, 1, 'ANNALS OF SCIENCE', 'Ann. sci', 'London : Taylor & Francis, 1936-', '00033790', NULL, 'Frequency varies', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(128, 1, 'ARCHIVE FOR HISTORY OF EXACT SCIENCES', 'Arch. hist. exact sci.', 'Berlin ; New York : Springer-Verlag, 1960-', '00039519', NULL, 'Irregular', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(129, 1, 'HYPERFINE INTERACTIONS', 'Hyperfine Interact.', 'Basel, Switzerland [etc.] : J.C. Baltzer [etc.] , 1975-', '03043843', NULL, 'Bimestral', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(130, 1, 'REACTION KINETICS AND CATALYSIS LETTERS', 'React Kinet Catal Lett', '', 'printed: 0133-1736 electronic: 1588-2837.', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(131, 1, 'REVISTA BRASILEIRA DE BIOLOGIA', 'Rev Bras Biol', '', '0034-7108', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(132, 1, 'JOURNAL OF APPLIED METEOROLOGY', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(133, 1, 'JOURNAL OF THE EXPERIMENTAL ANALYSIS OF BEHAVIOR', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(134, 1, 'JOURNAL OF CHROMATOGRAPHIC SCIENCE', '', '', '0021-9665', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(135, 1, 'CHEMICAL REVIEWS', 'Chem. Rev.', 'Easton, Pa., etc.: American Chemical Society [etc.]', '00092665', NULL, '8 times a year, 1988-1998; Monthly, 1999-', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(136, 1, 'JOURNAL OF CHEMICAL CRYSTALLOGRAPHY', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(137, 1, 'SIGNAL PROCESSING', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(138, 1, 'INTERNATIONAL JOURNAL OF GENERAL SYSTEMS', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(139, 1, 'TIME HARMONIC ELECTROMAGNETIC FIELDS', '', 'Harrington, Roger', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(140, 1, 'STRUCTURE AND BONDING', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(141, 1, 'SYNTHETIC COMMUNICATIONS', 'Synthetic Comm', '', '0039-7911 (e): 1532-2432', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(142, 1, 'BRITISH JOURNAL FOR THE HISTORY OF SCIENCE', 'Br. J. Hist. Sci.', 'London, GB : British Society For The History Of Science', '00070874', NULL, 'Semiannual 1962- ; three times a year Mar. 1975-', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(143, 1, 'INTERNATIONAL JOURNAL OF PHARMACEUTICS', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(144, 1, 'POLARONS IN IONIC CRYSTAL AND POLAR SEMICONDUCTORS', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(145, 1, 'IEEE SOFTWARE', 'IEEE Softw.', 'Los Alamitos, CA : IEEE Computer Society, c1984-', '07407459', NULL, 'Quarterly', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(146, 1, 'JOURNAL OF CHEMICAL EDUCATION', 'J Chem Educ', '', '0021-9584', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(147, 1, 'JOURNAL OF MEMBRANE SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(148, 1, 'BULLETIN OF THE CHEMICAL SOCIETY OF JAPAN', 'Bull. Chem. Soc. Jpn.', 'Tokyo, JP : Chemical Society Of Japan', '00092673', NULL, 'Monthly', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(149, 1, 'COMPARATIVE EDUCATION REVIEW', 'Comp. Educ. Rev.', 'Chicago [etc.] : Comparative and International Education Society [etc.], 1957-', '00104086', NULL, 'Three times a year; 4 times a year', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(150, 1, 'COORDINATION CHEMISTRY REVIEWS', 'Coord. Chem. Rev.', 'Amsterdam : Elsevier Publishing Company, 1966-', '00108545', NULL, 'Quarterly', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(151, 1, 'JOURNAL OF APPLIED POLYMER SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(152, 1, 'POLYMER', 'Polymer (Guilford)', 'London, GB // Guildford, England [etc.]: Butterworth Scientific', '00323861', NULL, 'Quarterly, 1960-19 ; Monthly, <May 1979>-Dec. 1990 ; 18 times a year, 1991 ; 24 times a year, 1992-', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(153, 1, 'COLLEGE AND RESEARCH LIBRARIES', 'Coll. Res. Libr.', 'Chicago, Ill. [etc.] : American Library Association, 1939-', '00100870', NULL, 'Quarterly 1939-55; bimonthly 1956-', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(154, 1, 'PSYCHOLOGICAL MEDICINE', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(155, 1, 'PSYCOLOGICAL MEDICINE', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(156, 1, 'IEEE TRANSACTIONS ON SOFTWARE ENGINEERING', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(157, 1, 'IEEE TRANSACTIONS ON COMPUTERS', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(158, 1, 'COMPUTER', 'Computer', 'Long Beach, Calif., etc.: IEEE Computer Society', '00189162', NULL, 'Bimonthly <, -1971>; Quarterly <, 1973-74>; Monthly <, 1975->', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(159, 1, 'JOURNAL OF INVESTIGATIVE DERMATOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:45', '2016-01-12 14:16:04'),
(160, 1, 'REVIEW OF EDUCATIONAL RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(161, 1, 'JOURNAL OF POLYMER SCIENCE. A 1986-. POLYMER CHEMISTRY', '', '', '0887-624X', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(162, 1, 'JOURNAL OF MACROMOLECULAR SCIENCE. PURE AND APPLIED CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(163, 1, 'GYNECOLOGIC AND OBSTETRIC INVESTIGATION', 'Gynecol. Obstet. Invest.', 'Basel, New York, Karger, 1978-', '03787346', NULL, '12 no. a year <, 1983- >', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(164, 1, 'NUCLEAR INSTRUMENTS AND METHODS IN PHYSICS RESEARCH. B. BEAM INTERACTIONS WITH MATERIALS AND ATOMS', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(165, 1, 'AGRICULTURAL HISTORY', 'Agric. hist.', 'Berkeley, Calif., etc.: Published for the Agricultural History Society by the University of Californ', '00021482', NULL, 'Semiannual 1927-  quarterly 1928-<, July 1977->', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(166, 1, 'ELECTRONIC ENGINEERING', 'Electron. Eng.', 'London : Morgan-Grampian, 1941-2001', '00134902', NULL, 'Monthly, <May 1976->; 14 no. a year, <June 1985->; 12 times a year, <1990>-2001', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(167, 1, 'IEEE TRANSACTIONS ON AUTOMATIC CONTROL', '', 'New York, Institute of Electrical and Electronics Engineers', '00189286', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(168, 1, 'WATER AND WASTES ENGINEERING', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(169, 1, 'CHEMICAL ENGINEERING JOURNAL (1996)', 'Chem. Eng. J.', 'Lausanne, Suica, CH : Elsevier Science', '13858947', NULL, 'Mensal', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(170, 1, 'SYSTEMATIC ENTOMOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(171, 1, 'JOURNAL OF THE AMERICAN COLLEGE OF NUTRITION', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(172, 1, 'BIOTECHNOLOGY AND BIOENGINEERING', 'Biotechnol. Bioeng.', 'New York, US : John Wiley & Sons', '00063592', NULL, 'Quinzenal', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(174, 1, 'METHODS OF EXPERIMENTAL PHYSICS', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(175, 1, 'NUMERISCHE MATHEMATIK', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(176, 1, 'POLYHEDRON', 'Polyhedron', 'New York, US : Pergamon Press', '02775387', NULL, 'Monthly', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(177, 1, 'SPECTROCHIMICA ACTA', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(178, 1, 'PAPER TRADE JOURNAL', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(179, 1, 'JOURNAL OF MICROSCOPY', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(180, 1, 'STRUCTURAL JOURNAL (ACI)', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(181, 1, 'ANNALES GEOPHYSICAE SERIE A. UPPER ATMOSPHERE AND SPACE SCIENCES', 'Ann. geophys., Ser A: Upper atmos. space sci', '[Montrouge Cedex] : Gauthier-Villars, 1986-1987', '09808752', NULL, 'Bimonthly', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(182, 1, 'OPTICS LETTERS', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(183, 1, 'OPTICAL ENGINEERING', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(184, 1, 'KANT-STUDIEN', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(185, 1, 'REVUE INTERNATIONALE DE PHILOSOPHIE', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(186, 1, 'TOPOI', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(187, 1, 'INTERNATIONAL JOURNAL OF SYSTEMATIC BACTERIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(188, 1, 'ANNALS OF APPLIED BIOLOGY', 'Ann. appl. biol.', '[London] : Association of Applied Biologists [etc.], 1915-', '00034746', NULL, 'Quarterly ;  9 issues a year <, Oct. 1977- >', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(189, 1, 'JOURNAL OF PHYSICAL CHEMISTRY. B. MATERIALS, SURFACES, INTERFACES, & BIOPHYSICAL', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(190, 1, 'IEEE JOURNAL OF QUANTUM ELECTRONICS', 'IEEE J Quant Electron', 'New York, Institute of Electrical and Electronics Engineers', '00189197', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(191, 1, 'JOURNAL OF WIND ENGINEERING AND INDUSTRIAL AERODYNAMICS', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(192, 1, 'PROCEEDINGS OF THE ASME FLUIDS ENGINEERING DIVISION SUMMER MEETING', 'Proc. ASME Fluids Eng. Div. Summer Meet', 'New York, N.Y. : The Society, 1996-', '10934928', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(193, 1, 'AIAA PAPER', 'AIAA pap', 'New York : American Institute of Aeronautics and Astronautics', '01463705', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(194, 1, 'ZEITSCHRIFT FUR FLUGWISSENSCHAFTEN UN WELTAUMFORSCHUNG', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(195, 1, 'METHODS IN ENZYMOLOGY', 'Methods Enzymol', 'New York : Academic Press, 1955-', '0076-6879 (e): 1557-7988', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(196, 1, 'JOURNAL OF THE CHEMICAL SOCIETY. DALTON TRANSACTIONS', 'Dalton Trans.', 'London : Chemical Society, [1972]-c1999', '03009246', NULL, 'Twenty-three no. a year, 1972-1977 ; 12 no. a year, 1978-1991 ; Semimonthly, 1992-1999', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(197, 1, 'ANNALS OF MATHEMATICAL STATISTICS', 'Ann. math. stat', 'Ann Arbor, Mich., US  // Baltimore, Md. etc. Institute of Mathematical Statistics', '00034851', NULL, 'Trimestral', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(198, 1, 'PHYSICS OF ATOMIC NUCLEI', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(199, 1, 'JOURNAL OF THE WASHINGTON ACADEMY OF SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(200, 1, 'HUMAN BEHAVIOR AND THE PRINCIPLE OF LEAST EFFORT, AND INTRODUCTION TO HUMAN ECOLOGY', '', 'Zipf, G.K.', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(201, 1, 'DESIGN OF EXPERIMENTS: A REALIST APPROACH', '', 'Anderson, V.L.', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(202, 1, 'REVIEW OF RADICAL POLITICAL ECONOMICS', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(203, 1, 'CLIMATOLOGY DREXEL INSTITUTE OF TECHNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(204, 1, 'JOURNAL DE PHARMACIE DE BELGIQUE', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(205, 1, 'CHEMICAL ENGINEERING AND PROCESSING', 'Chem. Eng. Process.', 'Lausanne, Suica, CH : Elsevier Sequoia', '02552701', NULL, 'Bimestral', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(206, 1, 'CENOTES OF YUCATAN : A ZOOLOGICAL AND HIDROGRAPHIC SURVEY', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(207, 1, 'ZOOLOGISCHER ANZEIGER', '', '', '0044-5231', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(208, 1, 'PROCEEDINGS OF THE NATIONAL ACADEMY OF SCIENCES OF THE UNITED STATES OF AMERICA', '', '', '1091-6490', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(209, 1, 'SOUTH AFRICAN JOURNAL OF ZOOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(210, 1, 'ADVANCES IN PARASITOLOGY', 'Adv. parasitol', 'London, New York : Academic Press, 1963-', '0065308X', NULL, 'Anual', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(211, 1, 'ZEITSCHRIFT FUR PARASITENKUNDE', '', '', '', NULL, '', '2015-09-01 14:24:46', '2015-09-01 14:25:10'),
(213, 1, 'JOURNAL OF THE CHEMICAL SOCIETY. PERKIN TRANSACTIONS 1', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(214, 1, 'ANALYTICA CHIMICA ACTA', 'Anal. Chim. Acta', 'Amsterdam, NL : Elsevier Scientific Publishing', '00032670', NULL, 'Bimonthly, 1947- ; Monthly <, Mar. 1976- >', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(215, 1, 'ACTA CHEMICA SCANDINAVICA', 'http://actachemscand.dk/', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(216, 1, 'ACTA CRYSTALLOGRAPHICA. SECTION C, CRYSTAL STRUCTURE COMMUNICATIONS', 'Acta Crystallogr.. Sect. C, Cryst. Struct. Commun.', 'Copenhagen, Denmark : Published for International Union of Crystallography by Munksgaard, [1983', '01082701', NULL, 'Monthly', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(217, 1, 'ANNALES DE CHIMIE, SCIENCE DES MATERIAUX [PARIS]', '', 'Paris ; New York, N.Y. : Masson, 1978-', '01519107', NULL, 'Monthly (except Feb., Apr., July, Oct.)', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(218, 1, 'SUNLIGHT, ULTRAVIOLET RADIATION, AND TE SKIN', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(219, 1, 'LIMIT FOR EXPOSURE TO \'HOT PARTICLES\' ON THE SKIN', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(220, 1, 'PROCESS TECHNOLOGIES FOR WATER TREATMENT', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(221, 1, 'JOURNAL OF THE CHEMICAL SOCIETY. PERKIN TRANSACTIONS 2', 'J. Chem. Soc., Perkin Trans., 2', 'London, GB : Royal Society Of Chemistry', '03009580', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(222, 1, 'ARCHIVES OF DERMATOLOGY', 'Arch. Dermatol.', 'Chicago, Ill., US : American Medical Association', '0003987X', NULL, 'Mensual', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(223, 1, 'SEMINARS IN DERMATOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(224, 1, 'JAMA: THE JOURNAL OF THE AMERICAN MEDICAL ASSOCIATION', '', '', '0098-7484', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(225, 1, 'ADVANCES IN PESTICIDE FORMULATION TECHNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(226, 1, 'PESTICIDE FORMULATIONS : INNOVATIONS AND DEVELOPMENTS', '', 'Cross, B.', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(227, 1, 'JOURNAL OF SPACECRAFT ROCKETS', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(228, 1, 'LASERS IN SURGERY AND MEDICINE', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(229, 1, 'PHYSICS IN MEDICINE AND BIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(230, 1, 'ANALYTICAL BIOCHEMISTRY', 'Anal. Biochem.', 'New York, N.Y. : Academic Press, 1960-', '00032697', NULL, 'Six no. a year, June 1960-; 18 no. a year ; Semimonthly, except monthly in Apr. and Sept., <Jan. 15,', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(231, 1, 'ADVANCES IN APPLIED MATHEMATICS', 'Adv. appl. math.', 'New York : Academic Press', '01968858', NULL, 'Trimestral', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(232, 1, 'ZEITSCHRIFT FUR METALLKUNDE', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(233, 1, 'GEOPHYSICAL RESEARCH LETTERS', 'Geophys. Res. Lett.', '[Washington] : American Geophysical Union, 1974-', '00948276', NULL, 'Monthly // Bimensual', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(234, 1, 'PROCEEDINGS OF THE 1994 IEEE FRECUENCY CONTROL SYMPOSIUM', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(235, 1, 'GREAT BASIN NATURALIST', 'Great Basin Nat.', '[Provo, Utah] : Brigham Young University, 1939-1999', '00173614', NULL, 'Irregular, 1939-1989 ; Quarterly, 1990-1999', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(236, 1, 'ORAL PATOLOGY', '', 'Wall, Isaac van der', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(237, 1, 'LABORATORY INVESTIGATION : A JOURNAL OF TECHNICAL METHODS AND PATHOLOGY', '', '', '0023-6837', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(238, 1, 'THEORETICAL FOUNDATIONS OF CHEMICAL ENGINEERING', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(239, 1, 'NATURE', 'Nature (Lond.)', 'London, GB : Macmillan Journals', '00280836', NULL, 'Weekly, except last week in Dec., 1981-', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(240, 1, 'LANCET', '', 'London, GB : Lancet Publications', '01406736', NULL, 'Semanal', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(241, 1, 'JOURNAL OF PHYSICS. CONDENSED MATTER', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(242, 1, 'JOURNAL OF AGRICULTURAL AND FOOD CHEMISTRY', 'J. Agric. Food Chem.', 'Easton, Pa. : American Chemical Society, Books and Journals Division', '00218561', NULL, 'Biweekly, Apr. 1, 1953-<Dec. 22, 1954> ; Bimonthly, <Jan./Feb. 1976>-Nov./Dec. 1989 ; Monthly, <Dec.', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(243, 1, 'JOURNAL OF ORGANIC CHEMISTRY', 'J. Org. Chem. ; JOC', 'Washington, US // [Easton, Pa., etc.] : American Chemical Society', '00223263', NULL, 'Biweekly', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(244, 1, 'MAGNETIC RESONANCE IN CHEMISTRY: MRC', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(245, 1, 'TETRAHEDRON LETTERS', 'Tetrahedron Lett.', 'Elmsford, Ny, US // New York ; London [etc.] : Pergamon Press, 1959-', '00404039', NULL, 'Fifty-two times per year // Semanal', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(246, 1, 'JOURNAL OF THE CHEMICAL SOCIETY', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(247, 1, 'JOURNAL OF ACADEMIC LIBRARIANSHIP', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(248, 1, 'LIBRARY ADMINISTRATION AND MANAGEMENT', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(249, 1, 'COMPUTER IN LIBRARIES', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(250, 1, 'COLLECTION MANAGEMENT', 'Collect. Manage.', 'New York : Haworth Press, 1977-', '01462679', NULL, 'Trimestral', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(251, 1, 'SERIALS REVIEW', '', '', '0953-0460', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(252, 1, 'REFERENCE SERVICES REVIEW', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(253, 1, 'JOURNAL OF THE AMERICAN SOCIETY FOR INFORMATION SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(254, 1, 'IEEE TRANSACTIONS ON ANTENNAS AND PROPAGATION', 'IEEE Trans. Antennas Propag.', 'New York, Institute of Electrical and Electronics Engineers', '0018926X', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(255, 1, 'NEURAL NETWORKS : THEORETICAL FUNDATIONS AND ANALYSIS', '', 'Lau, C. (editor)', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(256, 1, 'HANDBOOK OF GENETIC ALGORITHMS', '', 'Davis, L. (editor)', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(257, 1, 'GENETIC ALGORITHMS', '', 'Buckles, B.L. ; Petry, F.E. (editores)', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(258, 1, 'APPLIED CATALYSIS. B, ENVIROMENTAL', 'Appl. Catal., B Environ.', 'Amsterdam : Elsevier, c1992-', '09263373', NULL, 'Quarterly', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(259, 1, 'RENEWABLE ENERGY', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(260, 1, 'JOURNAL OF CELL BIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(261, 1, 'AMERICAN JOURNAL OF PHYSIOLOGY', 'Am. J. Physiol.', 'Bethesda : American Physiological Society , cop1898 // Baltimore, Md. [etc.]', '00029513', NULL, 'Bimonthly (except Aug.-Oct.) 1898-July 1899 / Monthly, Aug. 1899-<Aug. 1977>', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(262, 1, 'JOURNAL MEMBRANE SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(263, 1, 'JOURNAL OF PHYSICS. E. SCIENTIFIC INSTRUMENTS', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(264, 1, 'MATERIALS RESEARCH BULLETIN', 'Mater. Res. Bull.', 'Oxford, Inglaterra, GB // New York, N.Y. : Pergamon Press, 1966-', '00255408', NULL, 'Mensual', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(265, 1, 'ZEITSCHRIFT FUR ANORGANISCHE UND ALLGEMEINE CHEMIE [1915-1949]', '', '', '1521-3749', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(266, 1, 'BIOPHYSICAL CHEMISTRY', 'Biophys. Chem.', 'Amsterdam, North-Holland Pub. Co. // Elsevier Science Publishers', '03014622', NULL, 'Frec. actual: Bimestral. Cuatro números al año desde 1980. Ocho números al año desde 1982', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(267, 1, 'PHILOSOPHICAL TRANSACTIONS OF THE ROYAL SOCIETY OF LONDON. SERIES A, CONTAINING PAPERS OF A MATHEMATICAL OR PHYSICAL CHARACTER', '', '', '', NULL, '\'\'', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(268, 1, 'IEEE TRANSACTIONS ON NUCLEAR SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(269, 1, 'STUDIA LEIBNITIANA', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(270, 1, 'ISIS', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(271, 1, 'STUDIES IN THE HISTORY AND THE PHILOSOPHY OF SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(272, 1, 'STUDIES IN THE HISTORY AND THE PHILOSOPHY SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(273, 1, 'EVOLUTION: INTERNATIONAL JOURNAL OF ORGANIC EVOLUTION', 'Evolution', 'Boulder, Colo., US : Society For The Study Of Evolution', '00143820', NULL, 'Quarterly <, Mar. 1975->;  Bimonthly <, Jan. 1980->; Quarterly <, Mar. 1980- >', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(274, 1, 'IEEE TRANSACTIONS ON ACOUSTICS, SPEECH AND SIGNAL PROCESSING', 'IEEE Trans. Acoust. Speech Signal Process.', 'New York, N.Y. : Institute of Electrical and Electronics Engineers, 1974-1990', '00963518', NULL, 'Bimonthly', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(275, 1, 'IEEE TRANSACTIONS ON IMAGE PROCESSING', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(276, 1, 'ACS SYMPOSIUM SERIES', '', '', '1088-5641', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(277, 1, 'NEW ZEALAND JOURNAL OF SCIENCE', '', '\'\'', '0028-8365', NULL, '\'\'', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(278, 1, 'EUROPEAN JOURNAL OF BIOCHEMISTRY', 'Eur. J. Biochem.', 'Berlin, DE : Springer Verlag', '00142956', NULL, 'Irregular', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(279, 1, 'ELECTROCHIMICA ACTA', 'Electrochim. Aacta', 'Oxford ; New York, N.Y. : Pergamon Press, 1959-', '00134686', NULL, 'Mensual. Mensual con n. extra en feb., mayo, jul. y oct. desde 1992. Semimensual, con n. extra en en', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(280, 1, 'FRESENIUS ZEITSCHRIFT FUR ANALYTISCHE CHEMIE', 'Fresenius Z. Anal. Chem.', 'Munchen : J.F. Bergmann ; Berlin : Springer-Verlag, 1947-1989', '00161152', NULL, 'Irregular', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(281, 1, 'OHIO JOURNAL OF SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(282, 1, 'JOURNAL OF THE WASHINGTON ACADEMY OF SCIENCES', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(283, 1, 'PROCEEDINGS OF THE IRE', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(284, 1, 'MOLECULAR SPECTROSCOPY: MODERN RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(285, 1, 'REVIEW OF ELECTRICAL COMMUNICATION LABORATORY', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(286, 1, 'COMPUTERS AND CHEMICAL ENGINEERING', 'Comput. Chem. Eng.', 'New York, US : Pergamon Press', '00981354', NULL, 'Mensal', '2015-09-01 14:24:46', '2016-01-12 14:16:04'),
(287, 1, 'BRITISH JOURNAL OF PHARMACOLOGY', 'Br. J. Pharmacol.', 'London, GB : British Pharmacological Society', '00071188', NULL, 'Mensal', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(288, 1, 'CONTINUING EVALUATION OF THE USE OF FLUORIDES', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(289, 1, 'CELLULAR SIGNALLING', 'Cell. Signal.', 'New York, US : Elsevier', '08986568', NULL, 'Mensal', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(290, 1, 'JOURNAL OF BACTERIOLOGY', 'J. Bacteriol.', 'Washington, US : American Society For Microbiology // Baltimore, Md. [etc.] : Williams & Wilkins [et', '00219193', NULL, 'Bimonthly, 1916-25 ; Monthly, 1926-', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(291, 1, 'PESTICIDE FORMULATIONS AND APPLICATION SYSTEMS', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(292, 1, 'HERBICIDES, FUNGICIDES, FORMULATION CHEMISTRY; PROCEEDINGS', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(293, 1, 'PHYSIS : RIVISTA INTERNAZIONALE DI STORIA DELLA SCIENZA', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(294, 1, 'EXPERIMENTAL MECHANICS', 'Exp. Mech.', 'St. Bethel, Conn., US : Society For Experimental Mechanics, 1961-', '00144851', NULL, 'Monthly 1961-1982; Quarterly <1983->', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(295, 1, 'HEALTH PHYSICS', 'Health Phys.', 'Elmsford, Ny, US : Pergamon Press, c1958-', '00179078', NULL, 'Monthly', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(296, 1, 'ARCHIVES OF BIOCHEMISTRY AND BIOPHYSICS', 'Arch. biochem. biophys.', 'New York, US : Academic Press', '00039861', NULL, '14 nos. a year, 1977- ; Monthly (except semimonthly in Jan. and Apr.) 197 - 7 ; Monthly (except semi', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(298, 1, 'JOURNAL OF FLUORINE CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(299, 1, 'AMERICAN JOURNAL OF MEDICINE', 'Am. j. med.', 'New York, US : Yorke Medical Group // New York : American Journal of Medicine , cop1946', '00029343', NULL, 'Mensual', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(300, 1, 'ANNALS OF INTERNAL MEDICINE', 'Ann Intern Med', 'Philadelphia : American College of Physicians , cop1922', '00034819', NULL, 'Mensual', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(301, 1, 'BONE', 'Bone', 'New York, US : Elsevier Science', '87563282', NULL, 'Mensal', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(302, 1, 'JOURNAL OF THE AMERICAN LEATHER CHEMISTS ASSOCIATION', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(303, 1, 'PRINCIPLES OF DESIGN AND OPERATION OF BRAIN: PROCEEDINGS OF A STUDY WEEK', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(304, 1, 'JOURNAL OF COLLOID AND INTERFACE SCIENCE', '', 'New York, US : Academic Press, 1966-', '00219797', NULL, 'Mensal', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(305, 1, 'PHYSICAL REVIEW. B. CONDENSED MATTER', 'Phys. Rev., B, Condensed Matter.', 'New York, US : American Physical Society', '01631829', NULL, 'Mensal', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(306, 1, 'JOURNAL OF PHYSICS AND CHEMISTRY OF SOLIDS', '', '', '0022-3697', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(307, 1, 'JOURNAL OF MATERIALS RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(308, 1, 'INTERNATIONAL REVIEW OF CYTOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(309, 1, 'PHYSICAL REVIEW LETTERS', 'Phys. Rev. Lett.', 'New York, US : American Society Of Physics , American Institute Of Physics', '00319007', NULL, 'Semimonthly, 1958- ; Weekly <, Feb. 1976->', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(310, 1, 'WEED SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(311, 1, 'PESTICIDE FORMULATIONS AND APPLICTION SYSTEMS', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(312, 1, 'PLACENTA', 'Placenta', '', '0143-4004', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(313, 1, 'CELL MOTILITY AND THE CYTOSKELETON', 'Cell Motil. Cytoskelet.', 'New York : A.R. Liss, c1986-', '08861544', NULL, 'Mensual', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(315, 1, 'PROCEEDINGS OF THE NIPR SYMPOSIUM ON POLAR BIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(316, 1, 'ACCOUNTS OF CHEMICAL RESEARCH', 'Acc. chem. res', '[Easton, Pa.] American Chemical Society', '00014842', NULL, 'Monthly', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(317, 1, 'INTERNATIONAL JOURNAL OF CHEMICAL KINETICS', 'Int. J. Chem. Kinet.', 'New York, N.Y. : Wiley, 1969-', '05388066', NULL, 'Bimonthly 1969-77; monthly 1978-', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(318, 1, 'APPLIED PHYSICS. B, LASERS AND OPTICS', 'Appl. phys., B Lasers opt.', 'Berlin ; New York : Springer-Verlag, c1994-', '09462171', NULL, 'Monthly', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(319, 1, 'A STANDARD REFERENCE MATERIAL CONTAINING NOMINALLY FIFTEEN PERCENT AUSTENITE (SRM 486)', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(320, 1, 'FORMING OF AUSTENITIC CHROMIUM-NICKEL STAINLESS STEELS', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(321, 1, 'PROCEEDINGS OF THE PHYSICAL SOCIETY', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05');
INSERT INTO `journal` (`id`, `instance_id`, `name`, `abbreviation`, `responsible`, `ISSN`, `ISSNE`, `frecuency`, `createdAt`, `updatedAt`) VALUES
(322, 1, 'JOURNAL OF THE IRON AND STEEL INSTITUTE', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(323, 1, 'ELECTRONICS LETTERS', '', 'London, GB : Institution Of Electrical Engineers', '00135194', NULL, 'Monthly, 1965- ; Biweekly <, May 1976-> ; Biweekly (except 1 issue in Dec.) <, 29 Apr. 1982- >', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(324, 1, 'NEUROPHARMACOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(325, 1, 'NUCLEAR INSTRUMENTS & METHODS IN PHYSICS RESEARCH. A. ACCELERATORS, SPECTROMETERS, DETECTORS AND ASSOCIATED EQUIPMENT', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(326, 1, 'ASSEMBLY LANGUAGE PROGRAMMING FOR THE IBM PERSONAL COMPUTER', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(327, 1, 'CLIMATIC CHANGE', 'Clim. Change', 'Dordrecht, Holanda, NL : D. Reidel Publishing', '01650009', NULL, 'Trimestral', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(328, 1, 'BIOLOGIA PLANTARUM', 'Biol. Plant.', 'Praha, CS; Praha, CS : Publishing House Of The Czechoslovak Academy Of Sciences, Junk', '00063134', NULL, 'Bimestral', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(329, 1, 'JOURNAL OF MOLECULAR BIOLOGY', 'J. Mol. Biol.', 'London, GB : Academic Press', '00222836', NULL, 'Three times a month <Jan. 6, 1976-> ; 2 times a month <20 Jan. 1985-> //  Semanal', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(330, 1, 'JOURNAL OF BIOLOGICAL CHEMISTRY', 'J. Biol. Chem.', 'Baltimore [etc.] Rockefeller University, 1905- // Bethesda, Md., US : American Society Of Biological', '00219258', NULL, 'Quinzenal', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(331, 1, 'JOURNAL OF IMMUNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(332, 1, 'PROTEINS', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(333, 1, 'ARCHIV DER PHARMAZIE', 'Arch. Pharm. (Weinheim)', 'Weinheim, Alemanha, DE : Vch Verlagsgesellschaft', '03656233', NULL, 'Mensal', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(334, 1, 'MODERN CASTING', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(335, 1, 'MOLECULAR AND GENERAL GENETICS: MGG', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(337, 1, 'HOSPITAL PHARMACY', 'Hosp. Pharm.', 'Philadelphia, Pa., US : J. B. Lippincott', '00185787', NULL, 'Mensal', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(338, 1, 'BULLETIN OF ENTOMOLOGICAL RESEARCH', 'Bull. Entomol. Res.', 'London : Entomological Research Committee, 1910-', '00074853', NULL, 'Trimestral', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(339, 1, 'BERICHTE DER BUNSEN-GESELLSCHAFT FUR PHYSICALISCHE CHEMIE', 'Ber. Bunsenges. Phys. Chem.', 'Weinhein : Verlag Chemie, 1963-', '00059021', NULL, 'Monthly', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(340, 1, 'SCIENTIFIC NATURARIST', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(341, 1, 'INTERNATIONAL JOURNAL FOR PARASITOLOGY', '', '', '0020-7519', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(342, 1, 'APPLIED SCIENTIFIC RESEARCH', 'Appl. sci. res.', 'The Hague : Nijhoff, 1966- // Dordrecht, Holanda, NL : Martinus Nijhoff Publishers', '00036994', NULL, 'Trimestral', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(343, 1, 'PUBLICACAO ESPECIAL DA ESCOLA DE GEOLOGIA DA UNIVERSIDADE FEDERAL DO RIO GRANDE DO SUL', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(344, 1, 'JOURNAL OF MOLECULAR LIQUIDS', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(345, 1, 'FASEB JOURNAL', 'FASEB J.', 'Bethesda, Md., US : Federation Of American Societies For Experimental Biology, c1987-', '08926638', NULL, 'Monthly (except 4 issues in Mar.)', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(346, 1, 'BIOPOLYMERS', 'Biopolymers', 'New York, etc. : John Wiley & Sons, etc., 1963-', '00063525', NULL, 'Bimonthly Feb. 1963- ; monthly Dec. 1979-', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(347, 1, 'ANNALES DE PARASITOLOGIE HUMAINE ET COMPAREE', 'Ann. parasitol. hum. comp', 'Paris ; New York, N.Y. : Masson, 1923-1993', '00034150', NULL, 'Bimestral', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(348, 1, 'CRYSTAL STRUCTURE COMMUNICATIONS', 'Cryst. Struct. Commun.', 'Parma, Italy : Universita degli studi di Parma, 1972-1982', '03021742', NULL, 'Quarterly', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(349, 1, 'JOURNAL OF PHARMACEUTICAL SCIENCES', 'J Pharm Sci', '', 'pISSN: 0022-3549 eISSN: 1520-6017', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(350, 1, 'JOURNAL OF PHARMACOLOGY AND EXPERIMENTAL THERAPEUTICS', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(351, 1, 'DIE PHARMAZIE', 'Pharmazie', 'Eschborng, Alemanha, DE : Govi Verlag', '00317144', NULL, 'Mensal', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(352, 1, 'BIOPHYSICAL JOURNAL', 'Biophys. J.', 'New York, N.Y. : Published for the Biophysical Society by the Rockefeller University Press, 1960-', '00063495', NULL, 'Bimonthly 1960-<Nov. 1961> ; monthly <, June 1979->', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(353, 1, 'JOURNAL OF THE ACOUSTICAL SOCIETY OF AMERICA', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(354, 1, 'WATER RESOURCES RESEARCH', 'Water Resour. Res.', 'Washington [etc.]: American Geophysical Union, cop1965', '00431397', NULL, 'Quarterly, 1965-; Bimonthly <, June 1977->', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(355, 1, 'FERROELECTRICS', 'Ferroelectrics', 'London, GB : Gordon And Breach Science Publishers, 1970-', '00150193', NULL, 'Quarterly ;  Bimonthly', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(356, 1, 'JOURNAL OF MATERIALS ELECTRICS', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(357, 1, 'JOURNAL OF GEOPHYSICAL RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(358, 1, 'MACROMOLECULAR', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(359, 1, 'MEMOIRS OF THE AMERICAN MATHEMATICAL SOCIETY', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(360, 1, 'JOURNAL OF POLYMER SCIENCE. B 1986-. POLYMER PHYSICS', '', '', '0887-6266', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(361, 1, 'JOURNAL OF MATERIALS SCIENCE', 'J. Mater. Sci.', 'Norwell, Mass., US : Kluwer Academic Publishers // [London] Chapman and Hall', '00222461', NULL, 'Bimonthly <, July 1968-> ; Monthly <, May 1976->', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(362, 1, 'CORROSION SCIENCE', 'Corros. Sci.', 'Oxford ; New York, N.Y. : Pergamon Press, 1961-', '0010938X', NULL, 'Quarterly 1961-<Jan./Mar. 1963>; monthly <, Dec. 1974->', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(363, 1, 'ANATOMICAL RECORD', 'Anat. rec.', 'New York : Liss , 1906 // American Association of Anatomists', '0003276X', NULL, 'Mensual', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(364, 1, 'MARINE BIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(365, 1, 'ACTA TROPICA', 'Acta trop.', 'Basel : Verlag für Recht und Gesellschaft, 1944- // Basel, Suica, CH : Schwabe', '0001706X', NULL, '4 no. per vol // Trimestral', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(366, 1, 'BIOLOGICAL BULLETIN', 'Biol. Bull.', 'Lancaster, Pa. [etc.] Lancaster Press, inc. [etc.] // Woods Hole, Mass., US : Marine Biological Labo', '00063185', NULL, 'Frequency varies', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(367, 1, 'ACTA AGRONOMICA', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(368, 1, 'COMPTES RENDUS HEBDOMADAIRES DES SEANCES DE L\'ACADEMIE DES SCIENCES', 'C. R. Hebd. Seances Acad. Sci.', 'Paris, Gauthier-Villars [etc.]', '00014036', NULL, 'Semanal', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(369, 1, 'ANNALS OF TROPICAL MEDICINE AND PARASITOLOGY', 'Ann. trop. med. parasitol.', 'London ; Orlando [etc.] : Academic Press [etc.], 1907-', '00034983', NULL, 'Irregular ; Quarterly <Dec. 1977-> ; bimonthly <Dec. 1979- >', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(370, 1, 'JOURNAL OF CLINICAL MICROBIOLOGY', 'J. Clin. Microbiol.', 'Washington, US : American Society For Microbiology', '00951137', NULL, 'Mensal', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(371, 1, 'MUTATION RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(372, 1, 'REVISTA LATINOAMERICANA DE MICROBIOLOGIA (1970)', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(373, 1, 'IL FARMACO', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(374, 1, 'CONTRIBUTIONS IN MARINE SCIENCE', 'Contrib. Mar. Sci.', 'Port Aransas, Tex. [etc.] Port Aransas Marine Laboratory, University of Texas Marine Science Institu', '00823349', NULL, 'Annual, 1945-<67>; Irregular <, Sept. 1974->', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(375, 1, 'COLLOIDS AND SURFACES. A, PHYSICOCHEMICAL AND ENGINEERING ASPECTS', 'Colloids Surf. A, Physicochem. Eng. Asp.', 'Amsterdam, NL : Elsevier Science Publishers', '09277757', NULL, 'Mensal', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(376, 1, 'EXPERIMENTAL PARASITOLOGY', 'Exp. Parasitol.', 'New York, N.Y. : Academic Press, 1951-', '00144894', NULL, 'Quarterly, 1951-52; Bimonthly, 1953-', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(377, 1, 'MICROPOROUS MATERIALS', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(378, 1, 'PARASITOLOGY TODAY', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(379, 1, 'VIBRATIONAL SPECTROSCOPY', 'Vib Spectros', '', '0924-2031', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(380, 1, 'RUSSIAN JOURNAL OF PHYSICAL CHEMISTRY', 'Russ J Phys Chem', '', '0036-0244', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(381, 1, 'ZEITSCHRIFT FUR KRISTALLOGRAPHIE', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(382, 1, 'YADERNAYA FIZIKA', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(383, 1, 'PROCEEDINGS OF THE UNITED STATES NATIONAL MUSEUM', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(384, 1, 'PROCEEDINGS OF THE HELMINTHOLOGICAL SOCIETY OF WASHINGTON', 'Proc. Helminthol. Soc. Wash.', 'Lawrence, Kan. : Helminthological Society of Washington, 1934-1989', '00180130', NULL, 'Semiannual', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(385, 1, 'SYSTEMATIC AND APPLIED MICROBIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(386, 1, 'CANADIAN JOURNAL OF MICROBIOLOGY', 'Can. J. Microbiol.', 'Ottawa, CA : National Research Council Of Canada, 1954-', '00084166', NULL, 'Bimonthly 1954-1966 ;  Monthly 1967-', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(387, 1, 'NEW PHYTOLOGIST', '', '', '1469-8137', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(388, 1, 'INTERNATIONAL JOURNAL OF FRACTURE MECHANICS', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(389, 1, 'SIGPLAN NOTICES', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(390, 1, 'ZEOLITES', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(391, 1, 'PISMA V ZHURNAL EKSPERIMENTALNOI I TEORETICHESKOI FISIKI. ENGLISH', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(392, 1, 'BIOLOGICAL CHEMISTRY HOPPE-SEYLER', 'Biol. Chem. Hoppe-Seyler', 'Berlin ; New York : W. de Gruyter, 1985-', '01773593', NULL, 'Monthly', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(393, 1, 'JOURNAL OF WILDLIFE DISEASES', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(394, 1, 'ANALES DEL INSTITUTO DE BIOLOGIA', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(395, 1, 'JOURNAL OF THE ELISHA MITCHELL SCIENTIFIC SOCIETY', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(396, 1, 'BIOCHEMICAL SOCIETY TRANSACTIONS', 'Biochem. Soc. Trans.', 'London : Biochemical Society, 1973-', '03005127', NULL, 'Bimonthly', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(397, 1, 'JOURNAL OF ORGANOMETALLIC CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(398, 1, 'COMBUSTION AND FLAME', 'Combust. Flame', 'New York, N.Y. [etc.] : Elsevier [etc.], 1957-', '00102180', NULL, 'Quarterly 1957-; bimonthly <1976->; Monthly <Feb. 1981->; monthly (except semimonthly in Jan.) <, Ja', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(399, 1, 'REVIEWS IN MACROMOLECULAR CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(400, 1, 'JOURNAL OF MATERIALS CHEMISTRY', 'J Mater Chem', '', '0959-9428  (e): 1364-5501', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(401, 1, 'JOURNAL OF AMERICAN CHEMISTRY SOCIETY', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(402, 1, 'SYNTHESIS AND PROCEEDING OF CERAMICS SCIENTIFIC ISSUES. SYMPOSIUM', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(403, 1, 'JOURNAL OF PHARMACY AND PHARMACOLOGY', 'J Pharm Pharmacol', '', '0022-3573. ISSN (electronic): 2042-7158', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(404, 1, 'PHARMACOLOGICAL REVIEWS', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(405, 1, 'JAPANESE JOURNAL OF ZOOLOGY; TRANSACTIONS AND ABSTRACS', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(406, 1, 'KIDNEY INTERNATIONAL', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(407, 1, 'INTERNATIONAL JOURNAL OF HEAT AND MASS TRANSFER', 'Int. J. Heat Mass Transfer', 'Oxford, New York, Pergamon Press', '00179310', NULL, 'Irregular, June 1960-Mar./Apr. 1962;  Monthly, May 1962-<1975>', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(408, 1, 'REVIEW OF METAPHYSICS', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(409, 1, 'JOURNAL OF THE HISTORY OF PHILOSOPHY', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(410, 1, 'JOURNAL OF APPLIED PHYSICS', 'J. Appl. Physi.', 'New York, US; New York, US : American Institute Of Physics , American Physical Society', '00218979', NULL, 'Monthly, 1937-1983  ; Semimonthly, Jan. 1984-<Dec. 1992>', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(411, 1, 'PHYSICS OF METALS AND METALLOGRAPHY', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(412, 1, 'IMMUNOLOGICAL COMMUNICATIONS', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(413, 1, 'IMMUNOLOGICAL INVESTIGATIONS', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(414, 1, 'OFFICIAL METHODS OF ANALYSIS OF THE ASSOCIATION OF OFFICIAL ANALYTICAL CHEMISTS', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(415, 1, 'EDUCATIONAL PSYCHOLOGIST', 'Educ. Psychol.', 'Madison, Wis. : Division 15, American Psychological Association, 1963- // Hillsdale, N.J. : Lawrence', '00461520', NULL, 'Trimestral', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(416, 1, 'JOURNAL OF APPLIED MECHANICS', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(417, 1, 'CHEMICAL ENGINEERING JOURNAL AND BIOCHEMICAL ENGINEERING JOURNAL', 'Chem. Eng. J. Biochem. Eng. J.', 'Lausanne : Elsevier Sequoia S.A., 1983-', '09230467', NULL, 'Bimonthly', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(418, 1, 'JOURNAL OF ARQUEOLOGICAL SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(419, 1, 'DOMESTIC DOG : ITS EVOLUTION, BEHAVIOUR AND INTERACTIONS WITH PEOPLE', '', 'Serpell, James', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(420, 1, 'JOURNAL OF THE AMERICAN DENTAL ASSOCIATION', 'J Am Dent Assoc', '', '0002-8177 e: 1943-4723', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(421, 1, 'MACROMOLECULES', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(422, 1, 'JOURNAL OF HEREDITY', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(423, 1, 'JOURNAL DE PHYSIQUE. II', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(424, 1, 'SOVIET ASTRONOMY LETTERS', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(425, 1, 'PROCEEDINGS OF THE INDIAN ACADEMY OF SCIENCES. SECTION B.', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(426, 1, 'BIOCHIMICA ET BIOPHYSICA ACTA = INTERNATIONAL JOURNAL OF BIOCHEMISTRY AND BIOPHYSICS', 'Biochim. Biophys. Acta', 'Amsterdam [etc.] : Elsevier/North Holland [etc.], 1947-', '00063002', NULL, 'Irregular', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(427, 1, 'CELL', 'Cell (Cambridge)', 'Cambridge, Mass., US : Massachusetts Institute Of Technology', '00928674', NULL, 'Monthly (except twice in Dec.) <, Jan. 1982-> ; biweekly', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(428, 1, 'COMPARATIVE BIOCHEMISTRY AND PHYSIOLOGY. B. COMPARATIVE BIOCHEMISTRY', 'Comp. Biochem. Physiol., B. Comp. Biochem.', 'London ; New York : Pergamon Press, 1971-1993 // Oxford : Elsevier Science , 1971', '03050491', NULL, 'Monthly', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(429, 1, 'TOXICOLOGY LETTERS', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(430, 1, 'ALCOHOLISM: CLINICAL AND EXPERIMENTAL RESEARCH', 'Alcohol., clin. exp. res', 'Baltimore, Md., etc. : American Medical Society on Alcoholism, etc.], 1977-', '01456008', NULL, 'Bimonthly , Jan./Feb. 1984-', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(431, 1, 'PROSTAGLANDINS', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(432, 1, 'EUROPEAN JOURNAL OF CLINICAL INVESTIGATION', 'Eur. J. Clin. Investig.', 'Oxford, Inglaterra, GB : Blackwell Scientific Publications', '00142972', NULL, 'Mensal', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(433, 1, 'JOURNAL OF APPLIED PHYSIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(434, 1, 'CARDIOVASCULAR RESEARCH', 'Cardiovasc. Res.', 'London : British Medical Association,', '00086363', NULL, 'Monthly <, 1981- >', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(435, 1, 'JOURNAL OF THE AMERICAN SOCIETY OF NEPHROLOGY', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(436, 1, 'OBSTETRICS AND GYNECOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(437, 1, 'DRUGS', 'Drugs', 'Sydney, Australia, AU // New York, ADIS Press [etc.]', '00126667', NULL, 'Mensual', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(438, 1, 'TECNOLOGIA Y ORGANIZACION DE LA PRODUCCION CERAMICA', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(439, 1, 'CERAMIC ENGINEERING AND SCIENCE PROCEEDINGS', 'Ceram. Eng. Sci. Proc.', 'Columbus, Ohio, American Ceramic Society', '01966219', NULL, 'Bimestral', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(440, 1, 'JOURNAL OF THE EUROPEAN CERAMIC SOCIETY', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(441, 1, 'MATERIALS SCIENCE AND ENGINEERING. B. SOLID-STATE MATERIALS FOR ADVANCED TECHNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(442, 1, 'PROCEEDINS OF THE INTERNATIONAL CLAY CONFERENCE, 1969, TOKYO, JAPAN', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(443, 1, 'TRENDS IN BIOCHEMICAL SCIENCES', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(444, 1, 'JOURNAL OF NATURAL PRODUCTS', 'J Nat Prod', '', '0163-3864 (e): 1520-6025', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(445, 1, 'PARASITOLOGY : SUPPLEMENT 85', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(446, 1, 'MEMOIRES POUR SERVIR A L\'HISTOIRE DES INSECTES', '', 'De Geer, Charles', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(447, 1, 'AMERICAN CERAMIC SOCIETY BULLETIN', 'Am. Ceram. Soc. bull', '[Easton, Pa. : The Society], 1946- // Columbus, Ohio : American Ceramic Society, 1946-', '00027812', NULL, 'Monthly', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(448, 1, 'JOURNAL OF HELMINTHOLOGY', 'J Helminthol', '', '0022-149X (e): 1475-2697', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(449, 1, 'TECHNOMETRICS', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(450, 1, 'JOURNAL OF THE OPTICAL SOCIETY', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(451, 1, 'BELL SYSTEM TECHNICAL JOURNAL', 'Bell Syst. Tech. J.', 'Short Hills, N.J., etc., : American Telephone and Telegraph Co., c1922-1983', '00058580', NULL, 'Quarterly, 1922-41 ; Irregular, 1942-64 ; Monthly (except May/June and July/Aug.) 1965-83', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(452, 1, 'INTERNATIONAL CHEMICAL ENGINEERING', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(453, 1, 'JOURNAL OF PHYSICAL CHEMISTRY. A. MOLECULES, SPECTROSCOPY, KINETICS, ENVIRONMENT, & GENERAL THEORY', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(454, 1, 'BUILDING AND ENVIRONMENT', 'Build. Environ.', 'Oxford, Inglaterra, GB : Pergamon Press, 1976-', '03601323', NULL, 'Trimestral', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(455, 1, 'REVIEWS IN CHEMICAL ENGINEERING', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(456, 1, 'JOURNAL OF THE OPTICAL SOCIETY OF AMERICA. B. OPTICAL PHYSICS', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(457, 1, 'ANNUAL REVIEW OF ENTOMOLOGY', '', 'Palo Alto, Calif. [etc.] Annual Reviews, inc. [etc.]', '00664170', NULL, 'Anual', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(458, 1, 'REPORTS ON PROGRESS IN PHYSICS', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(459, 1, 'RCA REVIEW', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(460, 1, 'ACTA HEREDIANA', 'Acta Hered.', 'Lima, PE : Universidad Peruana Cayetano Heredia', '10177000', NULL, 'Semestral', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(461, 1, 'JOURNAL OF NON-NEWTONIAN FLUID MECHANICS', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(462, 1, 'ZOOLOGISCHER ANZEIGER [MICROFORM]', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(463, 1, 'METODO GRAFICO PARA A INTERPRETACAO DE RESULTADOS EM PROGRAMA INTERLABORATORIAL', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(464, 1, 'JOURNAL OF MATERIALS SCIENCE LETTERS', '', '', '0261-8028', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(465, 1, 'JOURNAL OF APPLIED CRYSTALLOGRAPHY', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(466, 1, 'IEEE TRANSACTIONS ON POWER DELIVERY', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(467, 1, 'RADIO ELECTRONICS', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(468, 1, 'ANAIS DO CBECIMAT', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(469, 1, 'RADIO SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(470, 1, 'ELECTROCHEMISTRY OF MOLTEN AND SOLID ELECTROLYTES', '', 'New York, Consultants Bureau [1961-', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(471, 1, 'SCRIPTA MATERIALIA', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(472, 1, 'ADVANCES IN ATOMIC AND MOLECULAR PHYSICS', 'Adv. at. mol. phys.', 'New York, N.Y. : Academic Press, 1965-1988', '00652199', NULL, 'Annual', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(473, 1, 'JOURNAL OF TOXICOLOGY AND CLINICAL TOXICOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(474, 1, 'INTERNATIONAL JOURNAL OF MASS SPECTROMETRY AND ION PROCESSES', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(475, 1, 'COSMETICS AND TOILETRIES', 'Cosmetics', 'Wheaton, Ill. etc. : Allured Pub. Corp., 1976-', '03614387', NULL, 'Monthly', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(476, 1, 'PROCEEDINGS OF THE IEEE', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(477, 1, 'NEUROSCIENCE LETTERS', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(478, 1, 'BRAIN RESEARCH REVIEWS', 'Brain Res. Rev.', 'Amsterdam, NL : Elsevier North Holland', '01650173', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(479, 1, 'METAPHYSICS AND THE PHILOSOPHY OF SCIENCE. THE CLASSICAL ORIGINS. DESCARTES TO KANT', '', 'Buchdahl, Gerd', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(480, 1, 'POWER DIFFRACTION', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(481, 1, 'CHEMICAL ENGINEERING AND TECHNOLOGY', 'Chem. Eng. Technol.', 'Weinheim, Alemanha, DE : Vch Verlagsgesellschaft', '09307516', NULL, 'Mensal', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(482, 1, 'BRAZILIAN JOURNAL OF MEDICAL AND BIOLOGICAL RESEARCH', 'Braz. J. Med. Biol. Res.', 'Ribeirao Preto, SP : Associacao Brasileira De Divulgacao Cientifica', '0100879X', NULL, 'Mensal', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(483, 1, 'CERAMICS INTERNATIONAL', 'Ceram. Int.', '[Faenza, Italy] : Ceramurgica, [c1981-', '02728842', NULL, 'Quarterly', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(484, 1, 'U.S.S.R. COMPUTATIONAL MATHEMATICS AND MATHEMATICAL PHYSICS', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(485, 1, 'CURRENT OPINION IN STRUCTURAL BIOLOGY', 'Curr. Opin. Struct. Biol.', 'London : Current Biology, 1991-', '0959440x', NULL, 'Bimonthly', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(486, 1, 'THERAPEUTIC DRUG MONITORING', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(487, 1, 'PHARMAZIE', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(488, 1, 'JANAF THERMOCHEMICAL TABLES', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(489, 1, 'CARBOHYDRATE RESEARCH', 'Carbohydr. Res.', 'Amsterdam, NL : Elsevier Science Publishers', '00086215', NULL, 'Bimonthly July/Aug. 1965-Apr./May 1966 ; monthly June 1966-', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(490, 1, 'JOURNAL OF ZOOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(491, 1, 'JOURNAL OF ULTRAESTRUCTURE RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(492, 1, 'NUCLEAR PHYSICS. A', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(493, 1, 'INSTRUMENTS AND EXPERIMENTAL TECHNIQUES', 'Instrum. Exp. Tech.', 'New York ; Pittsburgh, Pa., US : Instrument Society Of America, 1959-', '00204412', NULL, 'Monthly', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(494, 1, 'REVIEW OF SCIENTIFIC INSTRUMENTS', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(495, 1, 'PHYSICAL REVIEW. C. NUCLEAR PHYSICS', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(496, 1, 'JOURNAL OF VIROLOGY', 'J. Virol.', 'Washington, US // Baltimore: American Society For Microbiology', '0022538X', NULL, 'Monthly', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(497, 1, 'INTERNATIONAL JOURNAL OF ELECTRICAL ENGINEERING EDUCATION', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(498, 1, 'PROCEEDINGS OF THE INSTITUTION OF ELECTRICAL ENGINEERS', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(499, 1, 'IEE PROCEEDINGS. C, GENERATION, TRANSMISSION AND DISTRIBUTION', 'IEE proc. C', 'Stevenage, Herts. : Institution of Electrical Engineers 1980-1993', '01437046', NULL, 'Six no. a year', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(500, 1, 'JOURNAL OF PHYSICS. G. NUCLEAR PHYSICS', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(501, 1, 'INTERCIENCIA', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(502, 1, 'SCIENTOMETRICS', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(503, 1, 'SCIENTIOMETRICS', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(504, 1, 'APPLIED SUPERCONDUCTIVITY', 'Appl. Supercond.', 'Tarrytown, Ny, US : Pergamon Press', '09641807', NULL, 'Mensal', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(505, 1, 'JOURNAL OF EXPERIMENTAL BOTANY', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(506, 1, 'CANADIAN JOURNAL OF CHEMISTRY', 'Can. J. Chem.', 'Ottawa, CA : National Research Council Of Canada', '00084042', NULL, 'Monthly', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(507, 1, 'ENERGY & FUELS: AN AMERICAN CHEMICAL SOCIETY JOURNAL', 'Energy Fuels', 'Washington, D.C. : The Society, c1987-', '08870624', NULL, 'Bimonthly', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(508, 1, 'JOURNAL OF FLUIDS ENGINEERING', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(509, 1, 'CANADIAN JOURNAL OF PLANT SCIENCE', 'Can. J. Plant Sci.', 'Ottawa : Agricultural Institute of Canada, 1957-', '00084220', NULL, 'Bimestral', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(510, 1, 'SCIENCE OF CERAMICS', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(511, 1, 'HELVETICA CHIMICA ACTA', 'Helv. Chim. Acta', 'Basel, Suica, CH : Verlag Helvetica Chimica Acta', '0018019X', NULL, 'Irregular', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(512, 1, 'HUMAN BIOLOGY', 'Hum. Biol.', 'Detroit, Mich., etc. Wayne State University Press [etc.] // Human Biology Council', '00187143', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(513, 1, 'INTERNATIONAL JOURNAL OF INMUNOPHARMACOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(514, 1, 'L\' ANTROPOLOGIE [PARIS]', '', '', '', NULL, '', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(515, 1, 'ANNALS OF HUMAN GENETICS', 'Ann. Hum. Genet.', 'London, Cambridge University Press for the Galton Laboratory, University College London', '00034800', NULL, 'Trimestral', '2015-09-01 14:24:46', '2016-01-12 14:16:05'),
(516, 1, 'COLLECTION OF CZECHOSLOVAK CHEMICAL COMMUNICATIONS', 'Collect. Czech. Chem. Commun.', 'Prague : Ceskoslovenska Akademie Ved', '00100765', NULL, 'Monthly 1958-', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(517, 1, 'BIOSTATISTICS : EXPERIMENTAL DESIGN AND STATISTICAL INFERENCE', '', 'Zolman, James', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(518, 1, 'APPLIED MULTIVARIATE ANALYSIS AND EXPERIMENTAL DESIGNS', '', 'Namboodiri, N. Krisshnan', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(519, 1, 'ANALYSIS OF VARIANCE IN COMPLEX EXPERIMENTAL DESIGNS', '', 'Lindman, Harold', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(520, 1, 'ANALYST', '', '[London, England, etc.] : Royal Society of Chemistry [etc.], 1877-', '00032654', NULL, 'Monthly, 1877-', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(521, 1, 'JOURNAL OF THE AMERICAN CERAMIC SOCIETY', 'J. Am. Ceram. Soc.', 'Westerville, OH // Easton, Pa., US : American Ceramic Society', '00027820', NULL, 'Monthly 1918-;  bimonthly 1975-80 ;  monthly 1981-', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(522, 1, 'CANADIAN JOURNAL OF PHARMACEUTICAL SCIENCE', 'Can. J. Public Health', 'Ottawa, etc. Canadian Public Health Assn', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(523, 1, 'JOURNAL OF PHARMACOKINETICS AND BIOPHARMACEUTICS', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(524, 1, 'ESCENARIOS PARA LA UNIVERSIDAD CONTEMPORANEA', '', 'Munoz Garcia, H.', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(525, 1, 'WESTERN JOURNAL OF NURSING RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(526, 1, 'NURSING RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(527, 1, 'INDUSTRIE CERAMIQUE', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(528, 1, 'CIENCIA Y TECNOLOGIA PARA UNA SOCIEDAD ABIERTA', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(529, 1, 'PROGRESS IN ZEOLITE AND MICROPOROUS MATERIALS: PROCEEDINGS OF THE 11TH INTERNATIONAL ZEOLITE', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(530, 1, 'SURFACE SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(531, 1, 'PROGRESS IN OPTICS', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(532, 1, 'INTERDISCIPLINARY SCIENCE REVIEWS: ISR', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(533, 1, 'PROCEEDINGS OF SPIE', 'Proc. SPIE', 'Bellingham, Wash. : The Society, 1981-', '0277-786X e: 1996-756X', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(534, 1, 'COMPUTING', 'Computing', 'Wien ; New York, N.Y. : Springer-Verlag, 1966-', '0010485X', NULL, 'Four no a year; 8 no. a year', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(535, 1, 'BIT', '', 'Copenhagen, DK : Scandinavian Computer Societes // BIT DATA A/S', '00063835', NULL, 'Trimestral', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(536, 1, 'MATHEMATICS OF COMPUTATION', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(537, 1, 'PARALLEL COMPUTING', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(538, 1, 'CLINICAL AND EXPERIMENTAL IMMUNOLOGY', 'Clin. Exp. Immunol.', 'Oxford : Blackwell Scientific Publications', '00099104', NULL, 'Monthly <, Apr. 1976->', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(539, 1, 'LINEAR ALGEBRA AND ITS APPLICATIONS', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(540, 1, 'SIAM JOURNAL ON CONTROL AND OPTIMIZATION', 'SIAM J. Control Optim.', '', '0363-0129', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(541, 1, 'MOLECULAR AND CELLULAR BIOCHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(542, 1, 'FEBS LETTERS', 'FEBS Lett.', 'Amsterdam : North-Holland Pub., 1968-', '00145793', NULL, 'Monthly, July 1968-; Semimonthly <, July 1978->', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(543, 1, 'JOURNAL OF PLANT FOODS', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(544, 1, 'JOURNAL OF THE ROYAL STATISTICAL SOCIETY. SERIES A', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(545, 1, 'NATURAL BIOTECHNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(546, 1, 'JOURNAL OF PROTEIN CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(547, 1, 'JOURNAL OF GEOPHYSICAL RESEARCH. A. SPACE PHYSICS.', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(548, 1, 'AGRICULTURAL AND BIOLOGICAL CHEMISTRY', 'Agric. biol. chem', 'Tokyo, Japan : Agricultural Chemical Society of Japan, 1961-1991', '00021369', NULL, 'Monthly', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(549, 1, 'SAE JOURNAL', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(550, 1, 'IRE TRANSACTIONS ON ANTENNAS & PROPAGATION', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(551, 1, 'JOURNAL OF DAIRY SCIENCE', '', '', '1525-3198', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(552, 1, 'AN INTRODUCTION TO GENERALIZED LINEAR MODELS', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(553, 1, 'ARCHIVES OF ORAL BIOLOGY', 'Arch. oral biol.', 'Elmsford, Ny, US // Oxford, New York, Pergamon Press', '00039969', NULL, 'Mensual', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(554, 1, 'TRANSACTIONS OF THE METALLURGICAL SOCIETY OF AIME', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(555, 1, 'JOURNAL OF THE INSTITUTE OF METALS', 'J Inst Met', '', '0020-2975', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(556, 1, 'CUADERNOS DEL CENDES', 'Cuad. Cendes', 'Caracas, Venezuela : Editorial Ateneo de Caracas, c1983- // Universidad Cantral De Venezuela, Centro', '10122508', NULL, 'Cuatrimestral', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(557, 1, 'SCHOL COUNSELOR', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(558, 1, 'METALLURGICAL AND MATERIALS TRANSACTIONS. A', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(559, 1, 'AICHE JOURNAL', 'AIChE j', 'New York, etc., American Institute of Chemical Engineers', '00011541', NULL, 'Quarterly, Mar. 1955-<June 1957> Bimonthly <, Jan. 1975->', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(560, 1, 'STUDIES IN HISTORY AND PHILOSOPHY OF SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(561, 1, 'HISTORY OF SCIENCE', 'Hist. Sci.', 'Bucks, Eng. [etc.] : Science History Publications [etc.], 1962-', '00732753', NULL, 'Annual, 1962-72 ; Quarterly, 1973-', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(562, 1, 'FEMS MICROBIOLOGY LETTERS', '', '[Amsterdam] : Published by Elsevier/North Holland on behalf of the Federation of European Microbiolo', '03781097', NULL, 'Monthly // Irregular', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(563, 1, 'ANNUAL REVIEW OF MICROBIOLOGY', 'Annu. rev. microbiol.', 'Palo Alto, Calif. [etc.] Annual Reviews, inc', '00664227', NULL, 'Anual', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(564, 1, 'TETRAHEDRON, ASYMMETRY', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(565, 1, 'IEEE TRANSACTIONS ON MICROWAVE', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(566, 1, 'CHEMICAL PHYSICS', 'Chem. Phys.', 'Amsterdam, North-Holland Pub. Co. // : Elsevier Science, 1973-', '03010104', NULL, 'Semimonthly, 1976-', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(567, 1, 'PROCEEDINGS [SOIL SCIENCE SOCIETY OF AMERICA]', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(568, 1, 'FRACTALS', '', 'Singapore, SG : World Scientific, c1993-', '0218348X', NULL, 'Quarterly', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(569, 1, 'ASTROPHYSICAL ASPECTS OF THE MOST ENERGETIC COSMIC RAYS', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(570, 1, 'SCIENTIA HORTICULTURAE', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(571, 1, 'JOURNAL OF CHEMICAL THERMODYNAMICS', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(572, 1, 'AUSTRALIAN JOURNAL OF CHEMISTRY', 'Aust. J. Chem.', 'Melbourne : Commonwealth Scientific and Industrial Research Organization, 1953-', '00049425', NULL, 'Quarterly Feb. 1953-<, Nov. 1955> ;  monthly <June 1975->', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(573, 1, 'PHYSICAL REVIEW. A, GENERAL PHYSICS', 'Phys. Rev., A, Gen. Phys.', 'New York, N.Y. : Published for the American Physical Society by the American Institute of Physics, [', '05562791', NULL, 'Monthly Jan. 1970- ; semimonthly Jan. 1, 1987-Dec. 15, 1989', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(574, 1, 'INTERNATIONAL JOURNAL OF SPORT PSYCHOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(575, 1, 'JOURNAL OF NUTRITION', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(576, 1, 'MIGRACIONES LATINAS Y FORMACION DE LA NACION LATINOAMERICANA', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(577, 1, 'CRITICAL CARE CLINICS', 'Crit. Care Clin.', 'Philadelphia : W.B. Saunders Co., c1985-', '07490704', NULL, 'Trimestral', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(578, 1, 'JOURNAL OF RHEUMATOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(579, 1, 'JOURNAL OF THE AMERICAN COLLEGE OF SURGEONS', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(580, 1, 'SEMINARS IN ROENTGENOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(581, 1, 'CRITIQUE [PARIS, FRANCE]', 'Critique', 'Paris : Editions de Minuit, 1946-', '00111600', NULL, 'Mensual', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(582, 1, 'LEIBNIZ: CRITICAL AND INTERPRETATIVE ESSAYS', '', 'Hooker, Michael [ed.]', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(583, 1, 'STUDIES IN SURFACE SCIENCE AND CATALYSIS', 'Stud Surf Sci Catal', '', '0167-2991', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(584, 1, 'CLINICA CHIMICA ACTA', 'Clin. Chim. Acta', 'Amsterdam, Elsevier Science Publishers', '00098981', NULL, 'Quincenal // Bimensal', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(585, 1, 'CHEMICAL AND PHARMACEUTICAL BULLETIN', 'Chem. Pharm. Bull.', 'Tokyo, Pharmaceutical Society of Japan', '00092363', NULL, 'Mensal', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(586, 1, 'JOURNAL OF ORAL PATHOLOGY & MEDICINE', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(587, 1, 'FOOD TECHNOLOGY', 'Food technol. (Chicago)', 'Chicago, Ill., US : Food technology, etc // Institute Of Food Technologists', '00156639', NULL, 'Mensal', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(588, 1, 'ZEITSCHRIFT FUR SAUGETIERKUNDE = INTERNATIONAL JOURNAL OF MAMMALIAN BIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(589, 1, 'IEEE TRANSACTIONS ON CIRCUITS AND SYSTEMS FOR VIDEO TECHNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(590, 1, 'COMPUTATIONAL MECHANICS', 'Comp. Mech.', 'Berlin ; New York : Springer-Verlag, 1986-', '01787675', NULL, 'Four issues yearly', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(591, 1, 'INTERNATIONAL SYMPOSIUM ON NEW TRENDS IN PHYSICS AND PHYSICAL CHEMISTRY OF POLYMERS [1988:TORONTO, ONT.]', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(592, 1, 'SPORTS MEDICINE', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(593, 1, 'JOURNAL OF SCHOOL HEALTH', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(594, 1, 'MEDICINE AND SCIENCE IN SPORTS AND EXERCISE', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(595, 1, 'INTERNATIONAL JOURNAL OF IMMUNOPHARMACOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(596, 1, 'PERCEPTUAL AND MOTOR SKILLS', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(597, 1, 'MORBIDITY AND MORTALITY WEEKLY REPORT', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:05'),
(599, 1, 'REVIEWS OF MODERN PHYSICS', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(600, 1, 'INTERNATIONAL CHEMICAL ENGINEERING SYMPOSIUM', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(601, 1, 'IEEE TRANSACTIONS ON INFORMATION THEORY', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(602, 1, 'IEEE TRANSACTIONS ON COMMUNICATIONS', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(603, 1, 'IEEE TRANSACTIONS ON SYSTEMS, MAN AND CYBERNETICS', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(604, 1, 'IEEE JOURNAL ON SELECTED AREAS IN COMMUNICATIONS', 'IEEE J. Sel. Areas Commun.', 'New York, N.Y. : Institute of Electrical and Electronics Engineers, c1983-', '07338716', NULL, 'Six no. a year', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(605, 1, 'PROCEEDINGS OF THE AMERICAN SOCIETY FOR HOSTICULTURAL SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(606, 1, 'METAPHYSICS AND THE PHILOSOPHY SCIENCE; THE CLASSICAL ORIGINS, DESCARTES AND KANT', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(607, 1, 'NIHON KAGAKU ZASSHI', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(608, 1, 'JOURNAL OF INVERTEBRATE PATHOLOGY', '', 'San Diego, Calif., US // New York, N.Y. : Academic Press, 1965-', '00222011', NULL, 'Bimonthly', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(609, 1, 'LER HISTORIA', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(610, 1, 'BREAST CANCER RESEARCH AND TREATMENT', 'Breast Cancer Res. Treat.', 'The Hague, Holanda, NL : Martinus Nijhoff Publishers', '01676806', NULL, 'Bimonthly, <Jan. 1990->', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(611, 1, 'NATO ASI SERIES. SERIES E, APPLIED SIENCES', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(612, 1, 'JOURNAL OF THE SCIENCE OF FOOD AND AGRICULTURE', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(613, 1, 'ASTROPHYSICS AND SPACE SCIENCE LIBRARY', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(614, 1, 'METALLURGICAL TRANSACTIONS. B. PROCESS METALLURGY', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(615, 1, 'MATERIALS SCIENCE AND TECHNOLOGY', 'Mater Sci Tech', '', '0267-0836 (e): 1743-2847', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(616, 1, 'PHILIPS JOURNAL OF RESEARCH', 'Philips J Res', '', '0165-5817', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(617, 1, 'SOLID STATE ELECTRONICS', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(618, 1, 'IEEE TRANSACTIONS ON ELECTRON DEVICES', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(619, 1, 'ADVANCES IN PROTEIN CHEMISTRY', '', 'New York, : Academic Press, 1944-', '00653233', NULL, 'Anual', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(620, 1, 'JOURNAL OF APPLIED BACTERIOLOGY', 'J. Appl. Bacteriol.', 'Oxford, Inglaterra, GB : Blackwell Scientific Publications, 1954-1996', '00218847', NULL, 'Bimonthly, 1975-1984 ; Monthly, 1985-', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(621, 1, 'APPLIED MICROBIOLOGY', 'Appl. microbiol.', '[Washington, etc.] American Society for Microbiology [etc.]', '00036919', NULL, 'Bimonthly, 1953-<Nov. 1955> ; Monthly, 19 -', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(622, 1, 'BRITISH FOUNDRYMAN', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(623, 1, 'TRENDS IN BIOTECHNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(624, 1, 'PLANT AND SOIL', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(625, 1, 'PROGRESS IN MAGNETIC RESONANCE SPECTROSCOPY', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(626, 1, 'REVISTA INTERAMERICANA DE BIBLIOGRAFIA = INTERAMERICAN BIBLIOGRAPHIC REVIEW', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(627, 1, 'GEOCHIMICA ET COSMOCHIMICA ACTA', 'Geochim. Cosmochim. Acta', 'Oxford : New York : Pergamon Press, cop1950', '00167037', NULL, 'Quincenal // Mensal', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(628, 1, 'SOVIET JOURNAL OF WATER CHEMISTRY AND TECHNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(629, 1, 'BIODEGRADATION', 'Biodegradation (Dordr.)', 'Dordrecht, Netherlands ; Boston : Kluwer Academic Publishers, 1990-', '09239820', NULL, 'Four no. a year // Trimestral', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(630, 1, 'ENVIRONMENTAL POLLUTION', '', 'London : Elsevier Applied Science Publishers, 1987-', '02697491', NULL, 'Four no. per v., 6 v. a year', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(631, 1, 'PHYSICS OF FLUIDS', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(632, 1, 'PHYSICA. A. THEORETICAL AND STATISTICAL PHYSICS', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(633, 1, 'PHYSICS OF FLUIDS. A. FLUID DYNAMICS', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(634, 1, 'JOURNAL OF INORGANIC BIOCHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(635, 1, 'CROATICA CHEMICA ACTA', 'Croat. Chem. Acta', 'Zagreb, Iugoslavia, YU : Hrvatsko Kemijsko Drustvo', '00111643', NULL, 'Frequency varies', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(636, 1, 'JOURNAL OF APPLIED POLYMER SCIENCE. APPLIED POLYMER SYMPOSIUM', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(637, 1, 'ECONOMIC BOTANY', 'Econ. Bot.', 'New York, New York Botanical Garden // Society for Economic Botany , cop1947', '00130001', NULL, 'Trimestral', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(638, 1, 'METAPHYSICS AND THE PHILOSOPHY OF SCIENCE; THE CLASSICAL ORIGINS, DESCARTES TO KANT', '', 'Buchdahl, Geard', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(639, 1, 'PALAEOGEOGRAPHY, PALAEOCLIMATOLOGY, PALAEOECOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(640, 1, 'SYNLETT', 'Synlett', '', '0936-5214', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(641, 1, 'SYNTHETIC METALS', 'Synthetic Met', '', '0379-6779', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(642, 1, 'MORPHOLOGICAL CHANGE IN QUATERNALY MAMMALS OF NORTH AMERICA', '', 'Martin and Barnosky eds.', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(643, 1, 'CANADIAN JOURNAL OF EARTH SCIENCES', 'Can. J. Earth Sci.', 'Ottawa, CA : National Research Council', '00084077', NULL, 'Bimonthly 1964-1970 ; monthly 1971-', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(644, 1, 'JOURNAL OF CHROMATOGRAPHY. A. INCLUDING ELECTROSPHORESIS AND OTHER SEPARATION METHODS', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(645, 1, 'PHYTOCHEMISTRY', 'Phytochemistry (Oxford)', 'New York, US : Pergamon Press, Headington Hill Hall', '00319422', NULL, 'Four issues yearly, 1961-1963 ; Six issues yearly, 1964-1966 ; Monthly, 1967-', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(646, 1, 'ADVANCES IN CHEMICAL PHYSICS', 'Adv. chem. phys', 'New York : Wiley [etc.], 1958- // New York, US : Interscience Publishers', '00652385', NULL, 'Irregular', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(647, 1, 'JOURNAL OF CRYSTALLOGRAPHIC AND SPECTROSCOPIC RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(648, 1, 'JOURNAL OF THE CHEMICAL SOCIETY. PERKIN TRANSACTIONS', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(649, 1, 'ANTIMICROBIAL AGENTS AND CHEMOTHERAPY', 'Antimicrob. agents chemother', 'Baltimore // Washington // Bethesda, Md. [etc.] American Society for Microbiology', '00664804', NULL, 'Monthly', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(650, 1, 'FUNDAMENTAL AND APPLIED TOXICOLOGY', 'Fundam. Appl. Toxicol.', 'Akron, Ohio, US : Society Of Toxicology', '02720590', NULL, 'Mensal', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(651, 1, 'TOXICOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(652, 1, 'IZVESTIIA AKADEMII NAUK SSSR. SERIIA KHIMICHESKAIA', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(653, 1, 'GEODERMA', 'Geoderma', 'Amsterdam ; New York, N.Y. : Elsevier Scientific Publishing Co., 1967-', '00167061', NULL, 'Ten issues a year', '2015-09-01 14:24:47', '2016-01-12 14:16:06');
INSERT INTO `journal` (`id`, `instance_id`, `name`, `abbreviation`, `responsible`, `ISSN`, `ISSNE`, `frecuency`, `createdAt`, `updatedAt`) VALUES
(654, 1, 'FEMS MICROBIOLOGY ECOLOGY', 'FEMS Microbiol. Ecol.', 'Amsterdam : Published by Elsevier Science Publishers on behalf of the Federation of European Microbi', '01686496', NULL, 'Bimonthly, 1985-; Eight times a year, <July 1992->', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(656, 1, 'BIOLOGY AND FERTILITY OF SOILS', 'Biol. Fertil. Soils', 'Secaucus, N.J. : Springer-Verlag New York Inc. ; Heidelberg, Germany : Springer-Verlag, 1985-', '01782762', NULL, 'Four issues yearly', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(657, 1, 'COLLOIDS AND SURFACES', 'Colloids Surf.', 'Amsterdam, NL : Elsevier Scientific', '01666622', NULL, 'Trimestral', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(658, 1, 'BRITISH CORROSION JOURNAL', 'Br. Corros. J.', 'London, GB : Metals Society', '00070599', NULL, 'Trimestral', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(659, 1, 'GEOLOGICAL MAGAZINE', 'Geol. Mag.', 'London [etc.] : Cambridge University Press, 1864-', '00167568', NULL, 'Monthly 1864-1939 ; bimonthly 1940-', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(660, 1, 'PHYSICA. D. NONLINEAR PHENOMENA', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(661, 1, 'CHEMICAL RESEARCH IN TOXICOLOGY', 'Chem. Res. Toxicol.', 'Washington, D.C. : American Chemical Society, c1988-', '0893228X', NULL, 'Bimonthly, 1988-1994; Eight no. a year, 1995-', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(662, 1, 'JOURNAL OF ETHNOPHARMACOLOGY', 'J Ethnopharmacol', '', '0378-8741', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(663, 1, 'CHEMOMETRICS AND INTELLIGENT LABORATORY SYSTEMS', 'Chemom. Intell. Lab. Syst.', 'Amsterdam ; New York : Elsevier Science Pub. Co., 1986-', '01697439', NULL, 'Bimonthly', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(664, 1, 'JOURNAL OF SOLID STATE CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(665, 1, 'MICROBIAL ECOLOGY', 'Microb. Ecol.', 'New York, N.Y. : Springer-Verlag, 1974-', '00953628', NULL, 'Quarterly / Bimestral', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(666, 1, 'ASTROPHYSICS AND SPACE SCIENCE', 'Astrophys. space sci.', 'Dordrecht, Holland ; Boston, Mass. : D. Reidel, 1968-', '0004640X', NULL, 'Semimonthly (except Sept.) <, 1986->', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(667, 1, 'ISRAEL JOURNAL OF CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(668, 1, 'AIRCRAFT PISTON ENGINE: FROM THE MANLY BALTZER TO THE CONTINENTAL TIARA', '', 'Smith, Herschel', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(669, 1, 'JOURNAL OF ANIMAL ECOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(670, 1, 'JOURNAL OF ATMOSPHERIC CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(671, 1, 'INTERNATIONAL JOINT CONFERENCE ON ARTIFICIAL INTELLIGENCE', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(672, 1, 'COMMUNICATIONS OF THE ACM', 'Commun. ACM', 'New York : Association for Computing Machinery, 1959-', '', NULL, 'Monthly', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(673, 1, 'INNOVATIONS IN EDUCATION AND TRAINING INTERNATIONAL', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(674, 1, 'IEEE TRANSACTIONS ON ENGINEERING MANAGEMENT', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(675, 1, 'WORLD CLEAN AIR CONGRESS [8TH: 1989; HAGUE, NETHERLANDS]', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(676, 1, 'JOURNAL OF AERONAUTICAL SCIENCES', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(677, 1, 'REASON, EXPERIMENT AND MYSTICISM...', '', 'Righini Bonelli ; Shea [eds.]', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(678, 1, 'A IMIGRACAO ESPANHOLA NO BRASIL', '', 'Klein, Herbert', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(679, 1, 'INTERNATIONAL JOURNAL OF HUMAN-COMPUTER STUDIES', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(680, 1, 'ECOLOGICAL MODELLING', 'Ecol. Modell.', 'Amsterdam : Elsevier Scientific Publishers, 1975-', '03043800', NULL, 'Quarterly, May 1975-Oct. 1977; 8 no. a year, Jan. 1978-; 20 issues yearly (5 vols. a year), <Feb. 19', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(681, 1, 'HTTP://CURRY.EDSCHOOL.VIRGINIA.EDU/AACE/CONF/WEBNET/HTML/127.HTM', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(682, 1, 'FLUID PHASE EQUILIBRIA', 'Fluid Phase Equilib.', 'Amsterdam, NL : Elsevier Scientific Publishing', '03783812', NULL, 'Quarterly; Bimonthly <, 1980- >', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(683, 1, 'JOURNAL OF GEOPHYSICAL RESEARCH. D. ATMOSPHERES', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(685, 1, 'HUMAN-COMPUTER INTERACTION: INTERACT\'95', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(686, 1, 'PHYSIOLOGIA PLANTARUM', 'Physiol Plantarum', '', '0031-9317 (e): 1399-3054', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(687, 1, 'JOURNAL OF THE INDIAN CHEMICAL SOCIETY', 'J. Indian Chem. Soc.', 'Calcutta, India, IN : Indian Chemical Society // Calcutta University Press', '00194522', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(688, 1, 'ZEITSCHRIFT FUR NATURFORSCHUNG', '', '', '0372-9516', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(689, 1, 'INDIAN JOURNAL OF ENTOMOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(690, 1, 'JOURNAL OF MAGNETISM AND MAGNETIC MATERIALS', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(691, 1, 'PHYSICA', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(692, 1, 'JOURNAL DE PHYSIQUE. I', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(693, 1, 'INTERNATIONAL JOURNAL OF BIOLOGICAL MACROMOLECULES', 'Int J Biol Macromol', '', '0141-8130 (Print) 1879-0003 (Electronic)', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(695, 1, 'JOURNAL OF THE AMERICAN MOSQUITO CONTROL ASSOCIATION', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(696, 1, 'TETRAHEDRON', 'Tetrahedron', 'Oxford, Inglaterra, GB : Pergamon Press', '00404020', NULL, 'Bimensal', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(697, 1, 'NEUES JAHRBUCH FUR MINERALOGIE', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(698, 1, 'JOURNAL OF APICULTURAL RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(699, 1, 'PROCEEDINGS OF THE AMERICAN MATHEMATICAL SOCIETY', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(700, 1, 'EUROPEAN JOURNAL OF CARDIO-THORACIC SURGERY', 'Eur. J. Cardio-Thorac. Surg.', 'Berlin, DE : Springer International', '10107940', NULL, 'Mensal', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(701, 1, 'CHEMICAL COMMUNICATIONS [ENGLAND]', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(702, 1, 'INTERNATIONAL CONFERENCE ON SIGNAL PROCESSING APPLICATION AND TECH.', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(703, 1, 'JOURNAL OF CARDIOVASCULAR SURGERY', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(704, 1, 'PROCEEDINGS OF IFAC/IMACS. INTERNATIONAL SYMPOSIUM SIMULATION CONTROL SYSTEMS', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(705, 1, 'ACTA HORTICULTURAE', 'Acta Hortic.', 'The Hague, Holanda, NL : International Society For Horticultural Science', '05677572', NULL, 'Irregular', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(706, 1, 'JOURNAL OF THORACIC AND CARDIOVASCULAR SURGERY', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(707, 1, 'EUROPEAN MASS SPECTROMETRY', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(708, 1, 'EXPERIENTIA', 'Experientia', 'Basel, Switzerland : Verlag Birkh„user, 1945-c1996', '00144754', NULL, 'Monthly', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(709, 1, 'POLISH JOURNAL OF CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(710, 1, 'MACROMOLECULAR CHEMISTRY AND PHYSICS', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(711, 1, 'JOURNAL OF THE CHEMICAL SOCIETY. CHEMICAL COMMUNICATIONS', 'J. Chem. Soc., Chem. Commun.', 'London, GB : Chemical Society', '0022-4936', NULL, 'Semimonthly', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(712, 1, 'BIOCHEMICAL JOURNAL. MOLECULAR ASPECTS', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(713, 1, 'ENTOMOLOGIA EXPERIMENTALIS ET APPLICATA', 'Entomol. Exp. Appl.', 'Amsterdam : Nederlandse Entomologische Vereniging [etc.], 1958- // Dordrecht, Holanda, NL : Junk', '00138703', NULL, 'Mensual', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(714, 1, 'JOURNAL OF THE HISTORY OF BIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(715, 1, 'EXPERIMENTAL AND APPLIED ACAROLOGY', 'Exp. Appl. Acarol.', 'Amsterdam, NL : Elsevier Science Publishers', '01688162', NULL, 'Monthly', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(716, 1, 'INTERNATIONAL JOURNAL OF MAN-MACHINE STUDIES', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(717, 1, 'UNIVERSITIES POWER ENGINEERING', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(718, 1, 'WIND ENGINEERING', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(719, 1, 'CANADIAN JOURNAL OF CHEMICAL ENGINEERING', 'Can. J. Chem. Eng.', 'Ottawa, CA : Chemical Institute Of Canada', '00084034', NULL, 'Bimonthly', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(720, 1, 'REVISTA BRASILEIRA DE FISICA', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(721, 1, 'JOURNAL OF POLYMER SCIENCE', '', '', '0022-3832', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(722, 1, 'EEC DIRECTIVE 80/779/EEC: A STUDY OF NETWORK DESIGN FOR MONITORING SUSPENDED...', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(723, 1, 'APPLIED SURFACE SCIENCE', 'Appl. Surf. Sci.', 'Amsterdam ; New York : North-Holland, 1985-', '01694332', NULL, 'Irregular', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(724, 1, 'KEY ENGINEERING MATERIALS', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(725, 1, 'APPLIED CATALYSIS. A, GENERAL', 'Appl. catal., A Gen.', 'Amsterdam : Elsevier, c1991-', '0926860X', NULL, 'Semimonthly', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(726, 1, 'BIORGANICHESKAIA KHIMIIA', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(727, 1, 'CHEMICAL ENGINEERING COMMUNICATIONS', 'Chem. Eng. Commun.', 'New York, US : Gordon And Breach Science', '00986445', NULL, 'Mensal', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(728, 1, 'NUMERICAL HEAT TRANSFER. PART A, APPLICATIONS', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(729, 1, 'SENSORS AND ACTUATORS. A. PHYSICAL', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(730, 1, 'SENSORS AND ACTUATORS. B. CHEMICAL', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(732, 1, 'APPLIED SPECTROSCOPY', 'Appl. Spectrosc.', '[New York, N.Y.] [etc.] : Society for Applied Spectroscopy, 1951-', '00037028', NULL, 'Quarterly, Nov. 1951-<May 1955> ; Bimonthly <, Nov./Dec. 1975->', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(733, 1, 'BIOORGANIC CHEMISTRY', 'Bioorg. Chem.', 'New York, US // San Diego, Calif. [etc.] : Academic Press, 1971-', '00452068', NULL, 'Quarterly', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(734, 1, 'COMPARATIVE BIOCHEMISTRY AND PHYSIOLOGY', 'Comp. Biochem. Physiol.', 'Oxford, New York, Pergamon Press', '0010406X', NULL, 'Monthly; Semimonthly <, Oct. 1970>', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(735, 1, 'JOURNAL OF THE ASSOCIATION OF OFFICIAL ANALYTICAL CHEMISTS', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(736, 1, 'JOURNAL DE CHIMIE PHYSIQUE ET DE PHYSICO-CHIMIE BIOLOGIQUE', 'J. Chim. Phys. Phys.-Chim. Biol.', '', '0021-7689', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(737, 1, 'NUCLEIC ACIDS RESEARCH', 'Nucleic Acids Res.', 'Oxford, Inglaterra, GB; Oxford, Inglaterra, GB : Rl Press , Information Retrieval', '03051048', NULL, 'Semimonthly, May 1979-', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(738, 1, 'JOURNAL OF MATERIALS PROCESSING TECHNOLOGY', 'J Mater Process Tech', '', '0924-0136', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(739, 1, 'INTERNATIONAL JOURNAL OF PRODUCTION RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(740, 1, 'JOURNAL OF MATERIALS PROCESSING & MANUFACTURING SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(741, 1, 'IEEE TRANSACTIONS ON PLASMA SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(742, 1, 'CLASSICAL AND THREE-DIMENSIONAL QSAR IN AGROCHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(743, 1, 'ANALES DE QUIMICA', 'An. Quim.', 'Madrid : Real Sociedad Española de Química, 1990', '11302283', NULL, 'Bimestral', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(744, 1, 'ARCHIVES OF TOXICOLOGY', 'Arch. Toxicol.', 'Berlin, New York, Springer-Verlag', '03405761', NULL, 'Bimestral', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(745, 1, 'JOURNAL OF BIOMOLECULAR STRUCTURE & DYNAMICS', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(746, 1, 'JOURNAL OF OCCUPATIONAL MEDICINE AND TOXICOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(747, 1, 'NAUCHNYE DOKLADY VYSSHEI SHKOLY. BIOLOGICHESKIE NAUKI', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(748, 1, 'BIOORGANIC & MEDICINAL CHEMISTRY LETTERS', 'Bioorg. Med. Chem. Lett.', 'Oxford : Pergamon Press, 1991-', '0960894X', NULL, 'Frec. actual: Mensual. Bimensual desde el 5 de en. de 1995', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(749, 1, 'STAUB', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(750, 1, 'ANNALS OF THE ENTOMOLOGICAL SOCIETY OF AMERICA', 'Ann. Entomol. Soc. Am.', '[College Park, Md., etc.] : Entomological Society of America, 1908-', '00138746', NULL, 'Quarterly Mar. 1908- ; bimonthly <Nov. 1974->', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(751, 1, 'HERBA POLONICA', 'Herba Pol.', 'Poznan, Polonia, PL : Instytut Przemyslu Zielarskiego', '00180599', NULL, 'Trimestral', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(752, 1, 'PLANTA MEDICA', '', '', '0032-0943', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(753, 1, 'LITTLE SCIENCE, BIG SCIENCE', '', 'Price, Derek de Solla', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(754, 1, 'CZECHOSLOVAK JOURNAL OF PHYSICS', 'Czech. J. Phys.', 'Praha : Chekhoslovatskia akademiia nauk, 1952-1965', '00114626', NULL, 'Twelve issues a year', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(755, 1, 'PHYSICA STATUS SOLIDI. B. BASIC RESEARCH', 'Phys Status Solidi B', '', '0370-1972  (e): 1521-3951', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(756, 1, 'ZEITSCHRIFT FUR ANGEWANDTE PHYSIK', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(757, 1, 'ZHURNAL EKSPERIMENTALNOI I TEORETICHESKOI FIZIKI', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(758, 1, 'JOURNAL OF ECONOMIC ENTOMOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(759, 1, 'QUARTERLY JOURNAL OF THE ROYAL METEOROLOGICAL SOCIETY', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(760, 1, 'JOURNAL OF THE AMERICAN VETERINARY MEDICAL ASSOCIATION', 'J. Am. Vet. Med. Assoc.', 'Schaumburg, Ill., US // Chicago, etc. : American Veterinary Medical Association', '00031488', NULL, 'Semestral', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(761, 1, 'PHARMACEUTICAL RESEARCH', 'Pharmaceut Res', '', '0724-8741 (e) 1573-904X', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(762, 1, 'PROCEEDINGS OF THE ROYAL SOCIETY OF LONDON', '', '', '03701662', NULL, '\'', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(763, 1, 'JOURNAL OF EXPERIMENTAL PSYCHOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(764, 1, 'PERCEPTION & PSICHOPHYSICS', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(765, 1, 'PERCEPTION & PSYCHOPHYSICS', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(766, 1, 'FOUNDATIONS OF PHYSICS', 'Found. Phys.', 'New York, US : Plenum Press, 1970-', '0015-9018', NULL, 'Quarterly 1970-1975 ;  Bimonthly 1976-1981 ; Monthly 1982-', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(767, 1, 'CURRENT OPINION IN BIOTECHNOLOGY', 'Curr. Opin. Biotechnol.', 'London, GB : Current Biology', '09581669', NULL, 'Bimestral', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(768, 1, 'QUARTERLY JOURNAL OF EXPERIMENTAL PSYCHOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(769, 1, 'PROTEIN SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(770, 1, 'JOURNAL OF NUCLEAR MATERIALS', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(771, 1, 'EUROPEAN JOURNAL OF SOLID STATE AND INORGANIC CHEMISTRY', 'Eur. J. Solid State Inorg. Chem.', 'Paris : Gauthier-Villars, c1988-', '09924361', NULL, 'Bimonthly', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(772, 1, 'MONATSHEFTE FUR CHEMIE', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(773, 1, 'INDUSTRIAL QUALITY CONTROL', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(774, 1, 'JOURNAL OF THE OPTICAL SOCIETY OF AMERICA', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(775, 1, 'SUBCELLULAR BIOCHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(776, 1, 'ARTERIOSCLEROSIS, THROMBOSIS AND VASCULAR BIOLOGY', 'Arterioscler. thromb. vasc. biol.', 'Dallas, Tex. : American Heart Association, c1995-', '10795642', NULL, 'Monthly', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(777, 1, 'ACTA MICROBIOLOGICA ET IMMUNOLOGICA HUNGARICA', 'Acta Microbiol. Inmunol. Hung.', 'Budapest, HU: Akademiai Kiado', '12178950', NULL, 'Trimestral', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(778, 1, 'JOURNAL OF BASIC MICROBIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(779, 1, 'CHEMICA SCRIPTA', 'Chem. Scr.', 'Cambridge, [England] ; New York, N.Y. : Published for the Royal Swedish Academy of Sciences by Cambr', '00042056', NULL, 'Ten no. a year; quarterly <, 1985->', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(780, 1, 'TRENDS IN ECOLOGY & EVOLUTION', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(781, 1, 'JOURNAL OF THE CHEMICAL SOCIETY. FARADAY TRANSACTIONS', 'J. Chem. Soc. Faraday trans.', '', '0956-5000', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(782, 1, 'SAGAMORE ARMY MATERIALS RESEARCH CONFERENCE (10TH: 1963)', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(783, 1, 'ECOLOGY', 'Ecology', 'Brooklyn, N.Y., etc., Brooklyn Botanic Garden, etc. // Durham, Nc, US : Ecological Society Of Americ', '00129658', NULL, 'Six no. a year', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(784, 1, 'JAPANESE JOURNAL OF PHARMACOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(785, 1, 'BOLLETTINO DELLA SOCIETA ITALIANA DI BIOLOGIA SPERIMENTALE', 'Boll. Soc. Ital. Biol. Sper.', 'Napoli, Italia, IT : Consiglio Nazionale Delle Ricerche // N. Jovene, 1927-', '00378771', NULL, 'Monthly', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(786, 1, 'BULLETIN DES SOCIETES CHIMIQUES BELGES', 'Bull. Soc. Chim. Belg.', 'Bruxelles, BE : Societe Chimique De Belgique', '00379646', NULL, 'Monthly', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(787, 1, 'BULLETIN DE LA SOCIETE CHIMIQUE DE FRANCE (PARIS, FRANCE: 1946)', 'Bull. Soc. Chim. Fr.', 'Paris : Masson et cie, 1946-1972', '00378968', NULL, 'Monthly', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(788, 1, 'AUTOMATICA', 'Automatica (Oxf.)', 'Oxford ; Elmsford, N.Y. : Pergamon Press, 1963-', '00051098', NULL, 'Quarterly (irregular) 1963-1968 ; bimonthly 1969-', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(789, 1, 'EXPERIMENTAL STATISTICS IN ENTOMOLOGY', '', 'Wadley, F.M.', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(790, 1, 'NOTULAE NATURAE ACADEMY NATURAL OF SCIENCE OF PHILADELPHIA', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(791, 1, 'SIAM JOURNAL ON OPTIMIZATION', '', 'Society for industrial and applied mathematics', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(792, 1, 'JAPANESE JOURNAL OF GEOLOGY AND GEOGRAPHY', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(793, 1, 'TRAFFIC ENGINEERING & CONTROL', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(794, 1, 'ECLOGAE GEOLOGICAE HELVETIAE', '', 'Basel : Schweiz. Geologischen Gesellschaft, 1888- // Birkhauser, 1888-', '00129402', NULL, 'Cuatrimestral', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(795, 1, 'IEEE TRANSACTIONS ON MAGNETIC', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(796, 1, 'IEEE TRANSACTIONS ON POWER APPARATUS AND SYSTEMS', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(797, 1, 'JAPANESE JOURNAL OF APPLIED PHYSICS. 1. REGULAR PAPERS AND SHORT NOTE', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(798, 1, 'JOURNAL OF MAGNETISM AND MAGNETIC', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(800, 1, 'SOLID STATE IONICS', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(801, 1, 'TOXICON', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(802, 1, 'TRANSACTIONS OF THE ROYAL SOCIETY OF TROPICAL MEDICINE AND HYGIENE', 'Trans R Soc Trop Med Hyg', '', '0035-9203 (Print) 1878-3503 (Electronic)', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(803, 1, 'WELDING AND METAL FABRICATION', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(804, 1, 'MATERIALS AND STRUCTURES', '', '', '1359-5997', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(805, 1, 'JOURNAL OF THE PHYSICAL SOCIETY OF JAPAN', 'J Phys Soc Jpn', '', '0031-9015  (e): 1347-4073', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(806, 1, 'DENKI KAGAKU', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(807, 1, 'PDA JOURNAL OF PHARMACEUTICAL SCIENCE AND TECHNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(808, 1, 'PHARMACEUTICAL ENGINEERING', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(809, 1, 'JOURNAL OF DRUG TARGETING', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(810, 1, 'INTERNATIONAL JOURNAL OF PHARMACY PRACTICE', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(811, 1, 'OPTICS AND LASERS IN ENGINEERING', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(812, 1, 'WIRELESS WORLD AND RADIO REVIEW', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(813, 1, 'STATISTICAL METHODS', '', 'Snedecor, G.W.', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(814, 1, 'EUROPEAN JOURNAL OF CLINICAL MICROBIOLOGY & INFECTIOUS DISEASES', '', 'Wiesbaden, Alemanha, DE : Vieweg', '09349723', NULL, 'Bimestral', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(815, 1, 'HETEROCYCLES', 'Heterocycles', 'Sendai, Japan: Sendai Institute of Heterocyclic Chemistry, 1973-', '03855414', NULL, 'Bimonthly 1973-74; monthly 1975-', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(816, 1, 'JOURNAL OF ELECTRON SPECTROSCOPY AND RELATED PHENOMENA', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(817, 1, 'ANNUAL REVIEW OF OCLC RESEARCH', 'Annu. rev. OCLC res', 'Dublin, Ohio : Offices of Research and Technical Planning, OCLC Online Computer Library Center,', '0894198X', NULL, 'Annual', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(818, 1, 'ASIS\'90: PROCEEDINGS OF THE 53RD ASIS ANNUAL MEETING', '', 'White Plains, Ny, US : Knowledge Industry Publications', '00447870', NULL, 'Anual', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(819, 1, 'INTERNATIONAL JOURNAL OF PHARMACOGNOSY', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(820, 1, 'OSIRIS', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(821, 1, 'PLATONIC COSMOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(822, 1, 'THEORETICA CHIMICA ACTA', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(823, 1, 'POWDER TECHNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(824, 1, 'SYNTHETIC METALS: MICROFORM', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(825, 1, 'TOXICOLOGY AND APPLIED PHARMACOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(826, 1, 'TRANSFUSION MEDICINE', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(827, 1, 'FOLIA MICROBIOLOGICA', 'Folia Microbiol.', 'Praha, CS; Praha, CS : Publishing House Of The Czechoslovak Academy Of Sciences, Academic Press', '00155632', NULL, 'Bimestral', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(828, 1, 'BIOTECHNOLOGY AND APPLIED BIOCHEMISTRY', 'Appl. Biochem. Biotechnol.', 'Clifton, Nj, US : Humana Press', '02732289', NULL, 'Bimestral', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(829, 1, 'UTILITAS MATHEMATICA', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(830, 1, 'PERCEPTION', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(831, 1, 'JOURNAL OF THERMAL ANALYSIS', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(832, 1, 'NUCLEI ACIDS RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(833, 1, 'JOURNAL OF CRYSTAL GROWTH', 'J Cryst Growth', '', '0022-0248', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(834, 1, 'APPLIED ENTOMOLOGY AND ZOOLOGY', 'Appl. Entomol. Zool.', 'Tokyo, JP : Japanese Society Of Applied Entomology And Zoology', '00036862', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(835, 1, 'JOURNAL OF THE PHYSICAL CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(836, 1, 'IEEE TRANSACTIONS ON CIRCUITS AND SYSTEMS', 'IEEE Trans. Circuits Syst.', 'New York : IEEE Circuits and Systems Society, 1974-1991', '00984094', NULL, 'Monthly', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(837, 1, 'EL CONCEPTO DE REPRESENTACION', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(838, 1, 'INTERNATIONAL JOURNAL OF MOLECULAR MEDICINE', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(839, 1, 'JOURNAL OF APPLIED TECHNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(840, 1, 'IEEE TRANSACTIONS ON MAGNETICS', '', '', '0018-9464', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(841, 1, 'VETERINARY PARASITOLOGY', 'Vet. Parasitol.', 'Amsterdam, NL : Elsevier Science Publishers', '03044017', NULL, 'Quarterly June 1975-Jan. 1980 ; 8 no. a year Mar. 1980-', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(842, 1, 'JOURNAL OF OPTIMIZATION THEORY AND APPLICATIONS', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(843, 1, 'JOURNAL OF PHYSICS AND CHEMISTRY OF SOLID', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(844, 1, 'ELECTROENCEPHALOGRAPHY AND CLINICAL NEUROPHYSIOLOGY', 'Electroencephalogr. Clin. Neurophysiol.', '[Limerick, Ireland, etc.] Elsevier [etc.] // Montreal [etc.] : EEG Journal , cop1949', '00134694', NULL, '24 no. a year, 1991-', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(845, 1, 'JOURNAL OF NON-CRYSTALLINE SOLIDS', '', '', '0022-3093', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(846, 1, 'JOURNAL OF BIOCHEMISTRY', 'J Biochem', '', 'pISSN: 0021-924X eISSN: 1756-2651', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(847, 1, 'LIPIDS', '', '', '0024-4201', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(848, 1, 'COMPARATIVE BIOCHEMISTRY AND PHYSIOLOGY. C. COMPARATIVE PHARMACOLOGY', 'Comp. Biochem. Physiol., C Comp. Pharmacol.', 'Oxford ; New York : Pergamon Press, -c1982', '03064492', NULL, 'Six no. a year', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(849, 1, 'CONTACT DERMATITIS', 'Contact. Derm.', 'Copenhagen, DK : Munksgaard International', '01051873', NULL, 'Mensal', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(850, 1, 'APPLIED BIOCHEMISTRY AND BIOTECHNOLOGY', 'Appl. Biochem. Biotechnol.', 'Clifton, Nj, US : Humana Press // Hatfield : John M. Walker , 1997,', '02732289', NULL, 'Bimestral // Mensual', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(851, 1, 'JOURNAL OF CHROMATOGRAPHY. B. BIOMEDICAL APPLICATIONS', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(852, 1, 'INTERNATIONAL JOURNAL OF CLINICAL & LABORATORY RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(853, 1, 'AMERICAN JOURNAL OF HEALTH-SYSTEM PHARMACY', 'Am J Health Syst Pharm', 'Bethesda, Md. : American Society of Hospital Pharmacists, 1995', '10792082', NULL, 'Semimonthly // Bimensual', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(854, 1, 'TRANSACTIONS OF THE INDIAN INSTITUTE OF METALS', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(855, 1, 'PARAZITOLOGIYA', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(856, 1, 'JOURNAL OF BOTANY, BRITISH AND FOREIGN', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(858, 1, 'PHARMACEUTICA ACTA HELVETIAE', 'Pharm Acta Helv', '', '0031-6865', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(859, 1, 'FUEL PROCESSING TECHNOLOGY', 'Fuel Process. Technol.', 'Amsterdam, NL : Elsevier Scientific Publishers, 1977-', '03783820', NULL, 'Trimestral', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(860, 1, 'CHEMICAL ENGINEERING', 'Chem. Eng.', 'Albany, N.Y. : McGraw-Hill Pub. Co., 1946-', '00092460', NULL, 'Monthly <Jan. 1947->; biweekly <Sept. 8, 1980->', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(861, 1, 'JOURNAL OF PROCESS CONTROL', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(862, 1, 'FEMS MICROBIOLOGY REVIEWS', 'Fems Microbiol. Rev.', 'Amsterdam: Published by Elsevier Science Publishers on behalf of the Federation of European Microbio', '01686445', NULL, 'Quarterly', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(863, 1, 'INTERNATIONAL JOURNAL OF RADIATION BIOLOGY AND RELATED STUDIES IN PHYSICS, CHEMISTRY AND MEDICINE', '', '', '', NULL, '', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(864, 1, 'ARCHIVES OF MICROBIOLOGY', 'Arch. Microbiol.', 'Berlin ; New York : Springer-Verlag, 1974-', '03028933', NULL, 'Mensual', '2015-09-01 14:24:47', '2016-01-12 14:16:06'),
(865, 1, 'ANNALS OF BOTANY', 'Ann. bot.', 'London ; New York [etc.] : Academic Press [etc.], 1887-', '03057364', NULL, 'Quarterly 1887-<Oct. 1947> ; 5 no. a yr ;  monthly Feb. 1979-', '2015-09-01 14:24:48', '2016-01-12 14:16:06'),
(866, 1, 'LETTERS IN APPLIED MICROBIOLOGY', 'Lett. Appl. Microbiol.', 'Oxford, Inglaterra, GB: Blackwell Scientific Publications', '02668254', NULL, 'Mensal', '2015-09-01 14:24:48', '2016-01-12 14:16:06'),
(867, 1, 'ORIENTAL JOURNAL OF CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:06'),
(868, 1, 'ENZYME AND MICROBIAL TECHNOLOGY', 'Enzyme Microb. Technol.', 'New York, US : Elsevier', '01410229', NULL, 'Trimestral // Mensual', '2015-09-01 14:24:48', '2016-01-12 14:16:06'),
(869, 1, 'DEVELOPMENTS IN PLANT AND SOIL SCIENCES', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:06'),
(870, 1, 'CURRENT PLANT SCIENCE AND BIOTECHNOLOGY IN AGRICULTURE', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:06'),
(871, 1, 'SYMBIOSIS [PHILADELPHIA, PA]', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:06'),
(872, 1, 'FERTILIZER RESEARCH', 'Fertil. Res.', 'The Hague, Holanda, NL : Nijhoff', '01671731', NULL, 'Trimestral', '2015-09-01 14:24:48', '2016-01-12 14:16:06'),
(873, 1, 'ZEITSCHRIFT FUR PFLANZENERNAHRUNG UND BODENKUNDE = JOURNAL OF PLANT NUTRITION AND SOIL SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:06'),
(874, 1, 'JOURNAL OF THE LESS-COMMON METALS', 'J Less Common Met', '', '0022-5088', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:06'),
(875, 1, 'JOURNAL OF THE AMERICN CHEMICAL SOCIETY', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:06'),
(876, 1, 'JOURNAL PHYSICAL CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:06'),
(877, 1, 'JOURNAL OF THE CHEMICAL PHYSICS', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:06'),
(878, 1, 'JOURNAL OF HIGH RESOLUTION CHROMATOGRAPHY: HRC', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:06'),
(879, 1, 'COMPTES RENDUS HEBDOMADAIRES DES SEANCES DE L\'ACADEMIE DES SCIENCES. SERIE D: SCIENCES NATURELLES', 'C. R. Hebd. Seances Acad. Sci., Ser. D, Sci. Nat.', 'Paris : Gauthier-Villars, 1966-1980', '0567655X', NULL, 'Semanal', '2015-09-01 14:24:48', '2016-01-12 14:16:06'),
(881, 1, 'INDUSTRIAL AND ENGINEERING CHEMISTRY RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:06'),
(882, 1, 'INDUSTRIAL AND ENGINEERING CHEMISTRY PROCESS DESIGN AND DEVELPMENT', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:06'),
(883, 1, 'NEW ZEALAND JOURNAL OF AGRICULTURAL RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:06'),
(884, 1, 'MOLECULAR MICROBIOLOGY', 'Mol. Microbiol.', 'Salem, Mass., US // Oxford : Blackwell Scientific Publications, 1987-', '0950382X', NULL, 'Monthly, 1989-', '2015-09-01 14:24:48', '2016-01-12 14:16:06'),
(885, 1, 'CRITICAL REVIEWS IN PLANT SCIENCES', 'Crit Rev Plant Sci', '', '0735-2689 (e): 1549-7836', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:06'),
(886, 1, 'MOLECULAR PLANT-MICROBE INTERACTIONS', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:06'),
(887, 1, 'BIOLOGICAL CHEMISTRY', 'Biol. Chem.', 'Berlin ; New York : W. de Gruyter, c1996-', '14316730', NULL, 'Monthly', '2015-09-01 14:24:48', '2016-01-12 14:16:06'),
(888, 1, 'BIOPROCESS ENGINEERING', '', 'New York, N.Y. : American Society of Mechanical Engineers, c1993- // Springer Verlag', '0178515X', NULL, 'Annual // Mensal', '2015-09-01 14:24:48', '2016-01-12 14:16:06'),
(889, 1, 'IEEE PHOTONICS TECHNOLOGY LETTERS', 'IEEE Photonics Technol. Lett.', 'New York, NY : Institute of Electrical and Electronics Engineers, c1989-', '10411135', NULL, 'Monthly', '2015-09-01 14:24:48', '2016-01-12 14:16:06'),
(890, 1, 'SPECTROCHIMICA ACTA. A. MOLECULAR AND BIOMOLECULAR SPECTROSCOPY', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:06'),
(891, 1, 'ARCHIVE FOR RATIONAL MECHANICS AND ANALYSIS', 'Arch. ration mech. anal.', 'Berlin ; New York, N.Y. : Springer-Verlag, 1957-', '00039527', NULL, 'Irregular  / Trimestral', '2015-09-01 14:24:48', '2016-01-12 14:16:06'),
(892, 1, 'APPLICATIONS ON NONLINEAR IN THE PHYSICAL SCIENCES', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:06'),
(893, 1, 'ZEITSCHRIFT FUR ANORGANISCHE CHEMIE [1892-1915]', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:06'),
(894, 1, 'DIE NATURWISSENSCHAFTEN', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:06'),
(895, 1, 'BERG-UND HUTTENMANNISCHE MONATSCHEFTE', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:06'),
(896, 1, 'PHYSICAL REVIEW', '', '', '0031-899X', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:06'),
(897, 1, 'AMERICAN JOURNAL OF PHILOLOGY', 'Am. j. philol.', 'Baltimore : Johns Hopkins University Press [etc.] // Basil L. Gildersleeve, 1980', '00029475', NULL, 'Trimestral', '2015-09-01 14:24:48', '2016-01-12 14:16:06'),
(898, 1, 'CARLETON\'S HISTOLOGICAL TECHNIQUE', '', 'Drury', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:06'),
(899, 1, 'JOURNAL OF HEAT TRATING', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:06'),
(900, 1, 'TRANSACTIONS OF THE FARADAY SOCIETY', 'Trans Faraday Soc', '', '0014-7672', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:06'),
(901, 1, 'HYDROBIOLOGIA', 'Hydrobiologia', 'Den Haag : W. Junk, 1948-', '00188158', NULL, 'Semimonthly, <Oct. 18, 1980-> ;  36 no. a year, <Oct. 31, 1985->', '2015-09-01 14:24:48', '2016-01-12 14:16:06'),
(902, 1, 'ACUATIC BOTANY', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(903, 1, 'JOURNAL OF FRESHWATER ECOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(904, 1, 'MEDICAL & BIOLOGICAL ENGINEERING', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(905, 1, 'SYNTHESIS', '', '', '0039-7881', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(906, 1, 'BIOTECHNOLOGY LETTERS', 'Biotechnol. Lett.', 'Dordrecht, Holanda, NL : Kluwer Academic Publishers', '01415492', NULL, 'Bisemanal', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(907, 1, 'AMERICAN JOURNAL OF VETERINARY RESEARCH', 'Am. J. Vet. Res.', 'Chicago, Ill., US : American Veterinary Medical Association', '00029645', NULL, 'Mensal', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(908, 1, 'BRITISH PHYCOLOGICAL JOURNAL', 'Br. Phycol. J.', 'London, GB : Academic Press', '00071617', NULL, 'Trimestral', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(909, 1, 'SCRIPTA METALLURGICA', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(910, 1, 'PHYSICA STATUS SOLIDI. A. APPLIED RESEARCH', '', '', '1862-6319', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(911, 1, 'INORGANIC SYNTHESES', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(912, 1, 'PROTEIN ENGINEERING', 'Protein Eng.', 'Oxford, Inglaterra, GB : Irl Press', '02692139', NULL, 'Mensal', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(913, 1, 'ANNUAL REVIEW OF PHYTOPATHOLOGY', '', 'Palo Alto, Calif., Annual Reviews, inc', '00664286', NULL, 'Anual', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(914, 1, 'FOOD RESEARCH INTERNATIONAL', 'Food Res. Int.', 'Ottawa : Elsevier Applied Science, 1992-', '09639969', NULL, 'Bimestral', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(915, 1, 'MICROBIAL PATHOGENESIS', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(916, 1, 'INFORMATIONS CHIMIE', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(917, 1, 'ZENTRALBLATT FUR VETERINARMEDIZIN. REIHE B.', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(918, 1, 'IEEE TRANSACTIONS ON GEOSCIENCE AND REMOTE SENSING', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(920, 1, 'REVISTA MATEMATICA IBEROAMERICANA', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(921, 1, 'INFECTION AND IMMUNITY', 'Infect. Immun.', 'Washington, US : American Society For Microbiology', '00199567', NULL, 'Monthly', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(922, 1, 'CANADIAN JOURNAL OF VETERINARY RESEARCH', 'Can. J. Vet. Res.', 'Ottawa, CA : Canadian Veterinary Medical Association', '08309000', NULL, 'Trimestral', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(923, 1, 'FATIGUE & FRACTURE OF ENGINEERING MATERIALS & STRUCTURES', 'Fatigue Fract. Eng. Mater. Struct.', 'Oxford, Inglaterra, GB : Pergamon Press // Blackwell Science, 2001-', '8756758X', NULL, 'Quarterly / Bimestral', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(924, 1, 'JOURNAL OF APPLIED CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(925, 1, 'IEE PROCEEDINGS. F, RADAR AND SIGNAL PROCESSING', 'IEE proc. F', 'Stevenage, Herts. : Institution of Electrical Engineers, c1989-1993', '0956375X', NULL, 'Six no. a year', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(926, 1, 'HEAT AND MASS TRANSFER IN THE PLANT-SOIL-AIR SYSTEM', '', 'Nerpin, Sergei Vladimirovich', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(927, 1, 'REVIEWS IN MATHEMATICAL PHYSICS', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(928, 1, 'METALLOGRAPHY', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(929, 1, 'ENGINEERING FRACTURE MECHANICS', 'Eng. Fract. Mech.', 'New York, N.Y. : Pergamon Press, 1968-', '00137944', NULL, 'Quarterly 1968-1980; bimonthly 1983-', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(930, 1, 'LARYNGORHINOOTOLOGIE', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(931, 1, 'AMERICAN INDUSTRIAL HYGIENE ASSOCIATION JOURNAL', 'Am. Ind. Hyg. Assoc. j', 'Baltimore, Md. [etc.], : Williams & Wilkins Co. [etc.], [1958', '00028894', NULL, 'Bimonthly Feb. 1958- / monthly Apr. 1980-', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(932, 1, 'JOURNAL OF HETEROCYCLIC CHEMISTRY', ' J. Heterocycl. Chem.', '[Provo, Utah, etc., HeteroCorporation] // Imprenta Albuquerque, Nm, US : Midwest Research Institute', '0022152X', NULL, '8 no. a year', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(933, 1, 'KHIMIIA PRIRODNYKH SOEDINENII', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(934, 1, 'HYDROGEN BOND', '', 'Pimentel, George', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(935, 1, 'JOURNAL OF APPLIED PHYSICOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(936, 1, 'CARBOHYDRATE POLYMERS', 'Carbohydr. Polym.', 'Barking, Inglaterra, GB : Elsevier Applied Science', '01448617', NULL, 'Bimestral', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(937, 1, 'INTERNATIONAL JOURNAL OF ENERGY RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(938, 1, 'CHINESE JOURNAL OF PHARMACEUTICAL ANALYSIS', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(939, 1, 'INDIAN JOURNAL OF PHARMACEUTICAL SCIENCES', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(940, 1, 'ARCHIV FOR PHARMACI OG CHEMI. SCIENTIFIC EDITION', 'Arch. Pharm. Chemi. Sci. Ed.', 'Kobenhavn, DK : Danmarks Apotekerforening', '0302248X', NULL, 'Semestral', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(941, 1, 'EASTERN PHARMACIST', 'East. Pharm.', 'New Delhi, IN : Eastern Pharmacist', '00128872', NULL, 'Mensal', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(942, 1, 'AMERICAN MINERALOGIST', 'Am. mineral', 'Washington : Mineralogical Society of America , 1916', '0003004X', NULL, 'Monthly 1916-June 1943 ;  bimonthly July/Aug. 1943-', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(943, 1, 'INTERNATIONAL JOURNAL OF QUANTUM CHEMISTRY', 'Int J Quant Chem', '', '0020-7608 (e): 1097-461X', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(944, 1, 'PHYSICS AND CHEMISTRY OF MINERALS', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(945, 1, 'CORE-LEVEL SPECTROSCOPY IN CONDENSED SYSTEMS: PROCEEDINGS OF THE TENTH TANIGUCHI INTERNATIONAL SYMPOSIUM', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(946, 1, 'JOURNAL DE PHYSIQUE. IV', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(947, 1, 'BULLETIN OF THE AMERICAN MATHEMATICAL SOCIETY', '', 'Lancaster, Pa., US : American Mathematical Society', '02730979', NULL, 'Trimestral', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(948, 1, 'ENERGY CONVERSION AND MANAGEMENT', 'Energy Convers. Manage.', 'Oxford ; New York : Pergamon, 1980-', '01968904', NULL, 'Trimestral', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(949, 1, 'MATHEMATICAL GEOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(950, 1, 'ACTA ENTOMOLOGICA SINICA', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(951, 1, 'PHYSICS OF THE EARTH AND PLANETARY INTERIORS', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(952, 1, 'TOPICS IN CURRENT CHEMISTRY', '', '', '0340-1022', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(953, 1, 'INDUSTRIAL AND ENGINEERING CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(954, 1, 'IEEE TRANSACTIONS ON VEHICULAR TECHNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(955, 1, 'CANADIAN ENTOMOLOGIST', 'Can. Entomol.', 'Ottawa, CA : Entomological Society Of Canada, 1868-', '0008347X', NULL, 'Irregular Aug. 1869-Dec. 1871 ; monthly 1868, 1872-', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(956, 1, 'ACTA CRYSTALLOGRAPHICA. SECTION B, STRUCTURAL SCIENCE', '', 'Copenhagen : Published for the International Union of Crystallography by Munksgaard, c1983-', '01087681', NULL, 'Bimonthly', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(960, 1, 'ACTA CRYSTALLOGRAPHICA. SECTION A, FOUNDATIONS OF CRYSTALLOGRAPHY', 'Acta Crystallogr.. Sect. A, Found. Crystallogr.', 'Copenhagen, Denmark : Published for the International Union of Crystallography by Munksgaard, c1983', '01087673', NULL, 'Bimonthly', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(961, 1, 'COMPUTER AND CHEMICAL ENGINEERING', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(962, 1, 'JOURNAL OF COORDINATION CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(963, 1, 'JOURNAL OF COMPUTER-AIDED MOLECULAR DESIGN', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(964, 1, 'JOURNAL OF APROXIMATION THEORY', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(965, 1, 'RUBBER CHEMISTRY AND TECHNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(966, 1, 'JOURNAL OF PHYSICS AND CHEMISTRY OF SOLIDS.', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(967, 1, 'PROGRESS OF THEORETICAL PHYSICS. SUPPLEMENT', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(968, 1, 'SOVIET PHYSICS, JETP', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(969, 1, 'INTRODUCTION TO INTERVAL COMPUTATIONS', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(970, 1, 'JOURNAL OF HIDRAULIC ENGINEERING', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(971, 1, 'SPACE POWER', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(972, 1, 'IEEE TRANSACTIONS ON ELECTRON DEVICES.', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(973, 1, 'INTERNATIONAL JOURNAL OF CONTROL', 'Internat. J. Control', '', '0020-7179 (Prin', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(974, 1, 'BELGIAN JOURNAL OF FOOD CHEMISTRY AND BIOTECHNOLOGY', 'Belg. J. Food Chem. Biotechnol.', 'Bruxelles, BE : Cooperative D´Edition Pour Les Industries Alimentaires', '07736177', NULL, 'Bimestral', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(975, 1, 'REVUE DES FERMENTATIONS ET DES INDUSTRIES ALIMENTAIRES', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(976, 1, 'MACHINING SCIENCE AND TECHNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(977, 1, 'PHARMACY INTERNATIONAL', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(978, 1, 'JOURNAL OF AOAC INTERNATIONAL', '', '', '1060-3271', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(979, 1, 'JOURNAL OF VETERINARY PHARMACOLOGY AND THERAPEUTICS', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(980, 1, 'CONTEMPORARY PHYSICS', 'Contemp. Phys.', 'London : Taylor & Francis, 1959-', '00107514', NULL, 'Bimonthly', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(981, 1, 'PHYSICAL REVIEW. D. PARTICLES AND FIELDS', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(982, 1, 'JOURNAL OF COLLOID SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(983, 1, 'ACTA BIOLOGICA HUNGARICA', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(984, 1, 'JOURNAL OF ANTIBIOTICS', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(985, 1, 'AUSTRALIAN VETERINARY JOURNAL', '', 'Brunswick, Australia, AU : Australian Veterinary Association', '00050423', NULL, 'Mensal', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(986, 1, 'CANCER LETTERS', 'Cancer Lett.', 'Amsterdam, Elsevier/North-Holland', '03043835', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(987, 1, 'FUKUOKA IGAKU ZASSHI', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(989, 1, 'ANTICANCER RESEARCH', 'Anticancer res', 'Athens, Greece : Potamitis Press, 1981-', '', NULL, 'Bimonthly', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(990, 1, 'SUPRAMOLECULAR SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(991, 1, 'JOURNAL OF POWER SOURCES', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(992, 1, 'ADVANCES IN EXPERIMENTAL MEDICINE AND BIOLOGY', '', 'New York, US : Plenum Publishing', '00652598', NULL, 'Irregular', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(993, 1, 'ANNALS OF THE NEW YORK ACADEMY OF SCIENCES', 'Ann. N.Y. Acad. Sci', 'New York, N.Y. : New York Academy of Sciences, 1877-', '00778923', NULL, 'Approximately 20 vols. per year', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(994, 1, 'NASA CONFERENCE PUBLICATION', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(995, 1, 'MATERIALS ENGINEERING', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(996, 1, 'APPLIED MICROBIOLOGY AND BIOTECHNOLOGY', ' Appl. microbiol. biotechnol.', 'Berlin ; New York : Springer International, [1984-', '01757598', NULL, 'Two vols. of 6 issues // Mensal', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(997, 1, 'WATER RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(998, 1, 'HELVETICA PHYSICA ACTA', 'Helv. Phys. Acta', 'Basel, Suica, CH : Birkhauser Verlag, 1928-1999', '00180238', NULL, 'Frequency varies, 1928- ;  Bimonthly, <1998>-1999', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(999, 1, 'ACTA PHYSICA POLONICA. B, ELEMENTARY PARTICLE PHYSICS, THEORY OF RELATIVITY, FIELD THEORY', '', 'Warszawa : Panstwowe Wydawn. Naukowe [Oddzial w Krakowie], 1970-', '05874254', NULL, 'Quarterly // Mensal', '2015-09-01 14:24:48', '2016-01-12 14:16:07');
INSERT INTO `journal` (`id`, `instance_id`, `name`, `abbreviation`, `responsible`, `ISSN`, `ISSNE`, `frecuency`, `createdAt`, `updatedAt`) VALUES
(1000, 1, 'APPLIED AND ENVIRONMENTAL MICROBIOLOGY', 'Appl. Environ. Microbiol.', '[Washington] American Society for Microbiology', '00992240', NULL, 'Mensual', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1001, 1, 'MICROBIOLOGY (READING)', '', 'Reading, United Kingdom : Society for General Microbiology, c1994-', '13500872', NULL, 'Monthly', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1002, 1, 'INTERNATIONAL SERIES OF NUMERICAL MATHEMATICS', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1003, 1, 'LA SIMULACION EN LA PLANEACION EDUCATIVA', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1004, 1, 'EUROPEAN POLYMER JOURNAL', 'Eur. Polym. J.', 'Oxford ; New York : Pergamon Press, 1965-', '00143057', NULL, 'Quarterly, 1965-1967; Bimonthly, 1968-1969; Monthly 1970-', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1005, 1, 'NEOPLASMA', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1006, 1, 'JOURNAL OF ULTRASTRUCTURE AND MOLECULAR STRUCTURE RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1007, 1, 'EPIDEMIOLOGY AND INFECTION', 'Epidemiol. Infect.', 'Cambridge, Inglaterra, GB: Cambridge University Press, c1987-', '09502688', NULL, 'Bimestral', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1008, 1, 'VETERINARY MICROBIOLOGY', 'Vet. Microbiol.', 'Amsterdam, NL : Elsevier Scientific Publishing', '03781135', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1009, 1, 'JOURNAL OF VETERINARY MEDICAL SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1010, 1, 'VETERINARY RECORD', 'Vet. Rec.', 'London : The British Veterinary Association,', '00424900', NULL, 'Weekly', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1011, 1, 'ENVIRONMENTAL HEALTH PERSPECTIVES: EHP', 'Environ. Health Perspect.', '[Research Triangle Park, N.C.] : U.S. Dept. of Health, Education, and Welfare, Public Health Service', '00916765', NULL, 'Irregular, Apr. 1972-Feb. 1976; Bimonthly, Apr. 1976-', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1012, 1, 'ZEITSCHRIFT FUR ALLGEMEINE MIKROBIOLOGIE', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1013, 1, 'SCIENCE OF TOTAL ENVIRONMENT', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1014, 1, 'MODERN PHILOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1015, 1, 'NEW LITERARY HISTORY', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1016, 1, 'INTERNATIONAL JOURNAL OF ONCOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1017, 1, 'PLANT PHYSIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1018, 1, 'PLANTA', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1019, 1, 'ANNALES UNIVERSITATIS MARIAE CURIE-SKLODOSKA. SECTIO C. BIOLOGIA', 'Ann. univ. Mariae Curie-Sklodowska, Sect. C', 'Lublin : Uniwersytet Marii Curie-Sklodowskiej, 1946-', '00662232', NULL, 'Anual', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1020, 1, 'AMERICAN PSYCHOLOGIST', 'Am. psychol', 'Washington, American Psychological Association', '0003066X', NULL, 'Monthly', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1021, 1, 'APIDOLOGIE', '', 'Versailles, Franca, FR : Institut National De La Recherche Agronomique', '00448435', NULL, 'Trimestral', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1022, 1, 'NONLINEARITY', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1023, 1, 'JAPANESE JOURNAL OF VETERINARY SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1024, 1, 'TROPICAL SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1025, 1, 'RESOURCES, CONSERVATION AND RECYCLING', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1026, 1, 'FOOD CHEMISTRY', 'Food Chem.', 'Oxford, Inglaterra, GB : Elsevier Science //  London : Applied Science, 1976-', '03088146', NULL, 'Trimestral // Cuatrimestral', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1027, 1, 'GENETIC ENGINEER & BIOTECHNOLOGIST', 'Genet. Eng. Biotechnol.', 'Oxfordshire, Inglaterra, GB : Carfax Publishing', '0959020X', NULL, 'Trimestral', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1028, 1, 'NAUTILUS', 'Nautilus', 'Melbourne, Fla., US : American Malacologists', '00281344', NULL, 'Trimestral', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1029, 1, 'ACTA MATERIALIA', 'Acta Mater.', 'Tarrytown, NY ; Oxford, England : Elsevier Science, c1995-', '13596454', NULL, 'Monthly', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1030, 1, 'BULLETIN OF MATERIALS SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1031, 1, 'NANOSTRUCTURED MATERIALS', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1032, 1, 'ARCHIV FÜR PROTISTENKUNDE', 'Arch. Protistenkd.', 'Jena, Alemanha, DE : Gustav Fischer', '00039365', NULL, 'Bimonthly, 1902- ; 4 no. a year, <1977->', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1033, 1, 'MATERIALS FORUM', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1034, 1, 'JOURNAL OF INDUSTRIAL MICROBIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1035, 1, 'JOURNAL OF ANIMAL SCIENCE', '', '', '0021-8812', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1036, 1, 'CLINICAL MICROBIOLOGY REVIEWS', 'Clin. Microbiol. Rev.', 'Washington, US : American Society For Microbiology', '08938512', NULL, 'Trimestral', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1037, 1, 'CALCIFIED TISSUE INTERNATIONAL', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1038, 1, 'JOURNAL OF BIOLOGICAL JOURNAL', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1039, 1, 'HORTSCIENCE', 'HortScience', 'Alexandria, Va., US : American Society For Horticultural Science', '00185345', NULL, 'Bimonthly // Trimestral', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1041, 1, 'IZVESTIIA AKADEMII NAUK ARMIANSKOI SSR. MATEMATIKA', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1042, 1, 'GERONTOLOGY', 'Gerontology', 'Basel, New York, Karger', '0304324X', NULL, 'Bimestral', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1043, 1, 'JOURNAL OF ENDOCRINOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1044, 1, 'PROCEEDINGS KONINKLIFKE NEDERLANDSE AKADEMIE', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1045, 1, 'NATURE: PHYSICAL SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1046, 1, 'METALLURGICAL TRANSACTIONS. A. PHYSICAL METALLURGY AND MATERIALS SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1047, 1, 'WELDING JOURNAL', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1048, 1, 'CHEM TECH', '', 'Washington, D.C., American Chemical Society', '00092703', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1049, 1, 'VIRUS GENES', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1050, 1, 'TECTONICS', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1051, 1, 'INTERNATIONAL JOURNAL OF FOOD MICROBIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1052, 1, 'JOURNAL OF AGRICULTURAL SCIENCE', 'J Agr Sci', '', '0021-8596', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1053, 1, 'RADIATION EFFECTS AND DEFECTS IN SOLIDS', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1054, 1, 'JOURNAL OF COMPARATIVE PATHOLOGY', 'J. Comp. Pathol.', 'Edinburgh, Inglaterra, GB // London ; Orlando: Academic Press', '00219975', NULL, 'Bimonthly, 1986-', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1055, 1, 'VETERINARY PATHOLOGY', 'Vet. Pathol.', 'Washington, US : American College Of Veterinary Pathologists, Karger', '03009858', NULL, 'Bimestral', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1056, 1, 'JOURNAL OF NUTRITION SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1057, 1, 'CHEMICAL ENGINEERING RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1058, 1, 'JOURNAL OF LIQUID CHROMATOGRAPHY', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1059, 1, 'ARZNEIMITTEL FORSCHUNG = DRUG RESEARCH', '', 'Aulendorf [Germany] : Editio Cantor', '00044172', NULL, 'Monthly', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1060, 1, 'INTERNATIONAL JOURNAL OF CLINICAL PHARMACOLOGY, THERAPY AND TOXICOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1061, 1, 'APPLIED PHYSICS', 'Appl. phys.', 'Berlin, New York, Springer-Verlag', '03403793', NULL, '12 no. a year', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1062, 1, 'CERAMURGIA INTERNATIONAL', 'Ceramurg. Int.', 'Faenza, Italia, IT : Ceramurgica', '03905519', NULL, 'Trimestral', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1063, 1, 'JOURNAL OF INFECTIOUS DISEASES', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1064, 1, 'JOURNAL OF ANTIMICROBIAL CHEMOTHERAPY', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1065, 1, 'OPTICA PURA Y APLICADA', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1066, 1, 'CHEMISTRY OF HETEROCYCLIC COMPOUNDS', 'Chem. Heterocycl. Compd.', 'New York : Faraday Press, c1965-', '00093122', NULL, 'Bimonthly 1965-; Monthly 1976-', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1067, 1, 'ENTOMOLOGICA SCANDINAVICA', 'Entomol. Scand.', 'Stockholm [etc.] : Societas Entomologica Scandinavica [etc.], 1970-', '00138711', NULL, 'Trimestral', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1068, 1, 'ZEITSCHRIFT FUR NATURFORSCHUNG. B. JOURNAL OF CHEMICAL SCIENCES', '', '', '0932-0776', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1069, 1, 'PHATOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1070, 1, 'AMERICAN JOURNAL OF PATHOLOGY', 'Am. j. pathol.', 'Hagerstown (MD) : American Association of Pathologists and Bacteriologists , 1925 // Philadelphia ,', '00029440', NULL, 'Mensual', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1072, 1, 'JOURNAL OF PERIODONTOLOGY', '', '', '0022-3492', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1073, 1, 'MATRIX BIOLOGY. JOURNAL OF INTERNATIONAL SOCIETY FOR MATRIX BIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1074, 1, 'JOURNAL OF BONE AND MINERAL RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1075, 1, 'EQUINE VETERINARY JOURNAL', '', 'London, GB : British Equine Veterinary Association', '04251644', NULL, 'Trimestral', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1076, 1, 'CANADIAN JOURNAL OF COMPARATIVE MEDICINE (1968)', 'Can. J. Comp. Med.', 'Ottawa, CA : Canadian Veterinary Medical Association', '00084050', NULL, 'Trimestral', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1078, 1, 'PLANT MOLECULAR BIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1079, 1, 'PROTEIN EXPRESSION AND PURIFICATION', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1080, 1, 'SPE JOURNAL', '', 'Society of Plastic Engineers', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1081, 1, 'POLYMER BULLETIN', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1082, 1, 'INTERNATIONAL CONFERENCE ON ASTEROIDS, COMETS, METEORS', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1083, 1, 'JOURNAL OF GEOPHYSICAL RESEARCH. E. PLANETS', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1084, 1, 'JOURNAL OF THE CHINESE CHEMICAL SOCIETY', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1085, 1, 'BULLETIN OF ENVIRONMENTAL CONTAMINATION AND TOXICOLOGY', 'Bull. Environ. Contam. Toxicol.', 'New York, N.Y. : Springer-Verlag, 1966-', '00074861', NULL, 'Bimonthly, 1966- ; monthly 1972-', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1086, 1, 'MARINE ENVIRONMENTAL RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1087, 1, 'MICROBIAL DEGRADATION OF ORGANIC COMPOUNDS', '', 'Gibson, David', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1088, 1, 'PHYSICS LETTERS. B', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1089, 1, 'AMERICAN JOURNAL OF TROPICAL MEDICINE AND HYGIENE', 'Am. j. trop. med. hyg', '[Lawrence, Kan., etc. : Allen Press, etc.], 1952- // Baltimore, Md., US : American Society Of Tropic', '00029637', NULL, 'Bimonthly', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1090, 1, 'METAL SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1092, 1, 'AMERICAN JOURNAL OF OBSTETRICS AND GYNECOLOGY', 'Am. j. obstet. gynecol.', 'St. Louis : Mosby , cop1920', '00029378', NULL, 'Quincenal', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1093, 1, 'JOURNAL OF PEDIATRICS', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1094, 1, 'MOLECULAR MEMBRANE BIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1095, 1, 'ACTA METALLURGICA', 'Acta metall.', '[Toronto] : University of Toronto Press, 1953-1989 // Elmsford, N.Y., Pergamon Press', '00016160', NULL, 'Monthly <Nov. 1975>-dez. 1989 // Bimonth', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1096, 1, 'BIOORGANIC & MEDICINAL CHEMISTRY', 'Bioorg. Med. Chem.', 'Oxford : Pergamon, 1993-', '09680896', NULL, 'Mensual', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1097, 1, 'BIOMEDICA BIOCHIMICA ACTA', 'Biomed. Biochim. Acta', 'Berlin : Akademie-Verlag, [c. 1983-1991]', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1098, 1, 'INTERNATIONAL JOURNAL OF PEPTIDE AND PROTEIN RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1099, 1, 'BASIC ECONOMETRICS', '', 'Gujarati Damodar', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1100, 1, 'CHEMICO-BIOLOGICAL INTERACTIONS', 'Chem. -biol. Interact.', 'Amsterdam : Elsevier Pub. Co., c1969-', '00092797', NULL, 'Monthly, <July/Aug. 1986->', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1101, 1, 'DRUGS DEVELOPMENT RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1102, 1, 'JOURNAL OF THE SOUTH AFRICAN VETERINARY ASSOCIATION', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1103, 1, 'SOUTHERN MEDICAL JOURNAL', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1104, 1, 'ASTRONOMISCHE NACHRICHTEN', 'Astron. Nachr.', 'Berlin : Akademie-Verlag , 1823-', '00046337', NULL, 'Bimestral', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1105, 1, 'JOURNAL OF GENERAL AND APPLIED MICROBIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1106, 1, 'FOOD HYDROCOLLOIDS', 'Food Hydrocoll.', 'Oxford, Inglaterra, GB: Irl Pres', '0268005X', NULL, 'Bimestral', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1107, 1, 'JOURNAL OF BIOMEDICAL MATERIALS RESEARCH', 'J. Biomed Mater Res.', 'New York, US : Wiley-Interscience', '00219304', NULL, ' Nine times a year <, Jan. 1984-> // Mensal', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1108, 1, 'JOURNAL OF CLINICAL INVESTIGATION', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1109, 1, 'JOURNAL OF BONE AND SURGERY, AMERICAN VOLUME', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1110, 1, 'ASTRONOMICAL SOCIETY OF THE PACIFIC MEETING [106TH: 1994]', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1111, 1, 'JOURNAL OF APPLIED MICROBIOLOGY', 'J. Appl. Microbiol.', 'Oxford, Inglaterra, GB : Blackwell Science', '13645072', NULL, 'Monthly', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1112, 1, 'RESEARCH COMMUNICATIONS IN MOLECULAR PATHOLOGY AND PHARMACOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1113, 1, 'BIOMEDICAL & ENVIRONMENTAL MASS SPECTROSCOPY', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1114, 1, 'GENOME', 'Genome', 'Ottawa, Canada : National Research Council Canada, c1987-', '08312796', NULL, 'Bimonthly', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1115, 1, 'BIOCHEMICAL PHARMACOLOGY', 'Biochem. Pharmacol.', 'Oxford [etc.] : Pergamon Press , cop1958', '00062952', NULL, 'Anteriormente mensual ; Quincenal', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1116, 1, 'JOURNAL OF DOCUMENTATION', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1117, 1, 'JOURNAL OF INFORMATION SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1118, 1, 'NEW CRYSTAL STRUCTURES', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1119, 1, 'JOURNAL OF TOXICOLOGICAL SCIENCES', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1120, 1, 'INTERNATIONAL JOURNAL OF SYSTEMS SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1121, 1, 'PHYSICS AND CHEMISTRY OF LIQUIDS', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1122, 1, 'HISTORY OF BIOLOGY: A SURVEY', '', 'Nordenskiold, E.', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1123, 1, 'IDEAS OF LIFE AND MATTER: STUDIES IN THE HISTORY OF GENERAL PHYSIOLOGY', '', 'Hall, Thomas S.', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1124, 1, 'HISTORY OF THE LIFE SCIENCES', '', 'Magner, Lois N.', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1125, 1, 'LES ETUDES PHILOSOPHIQUES', '', '', '2101-0056', NULL, '', '2015-09-01 14:24:48', '2015-09-01 14:25:07'),
(1126, 1, 'EUROPEAN STUDIES REVIEW', 'Eur. Stud. Rev.', 'London, GB : Macmillan Journals, 1971-1983', '00143111', NULL, 'Bienal', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1127, 1, 'ENVIRONMENT', 'Environment', 'Washington, etc. : Helen Dwight Reid Educational Foundation, 1969-', '00139157', NULL, '10 no. a year', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1128, 1, 'INTERNATIONAL JOURNAL OF DERMATOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1129, 1, 'MOLECULAR PHARMACOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1130, 1, 'DERMATOLOGIC SURGERY', 'Dermatol. Surg.', 'New York, NY : Elsevier Science, Inc., 1995-', '10760512', NULL, 'Monthly', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1131, 1, 'COMMUNICATIONS ON PURE AND APPLIED MATHEMATICS', 'Commun. Pure Appl. Math.', 'New York : John Wiley & Sons, 1949-', '00103640', NULL, 'Quarterly, 1948-; Bimonthly, 19 -', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1132, 1, 'MATERIALS SCIENCE AND ENGINEERING. A. STRUCTURAL MATERIALS', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1133, 1, 'REVUE DE L\'INSTITUT FRANCAIS DU PETROLE', '', '', '0020-2274', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1134, 1, 'JOURNAL OF THE ELECTROCHEMICAL SOCIETY', 'J Electrochem Soc', '', '0013-4651', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1135, 1, 'ANNALES DES SCIENCES NATURELLES. ZOOLOGIE ET BIOLOGIE ANIMALE', 'Ann. Sci. Nat. Zool. Biol. Anim.', 'Paris, FR : Centre National De La Recherche Scientifique', '00034339', NULL, 'Trimestral', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1136, 1, 'BEE WORLD', '', 'Bucks, Inglaterra, GB : International Bee Research Association', '0005772X', NULL, 'Trimestral', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1137, 1, 'ORAL SURGERY, ORAL MEDICINE AND ORAL PATOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1138, 1, 'POLSKIE ARCHIWUM WETERYNARYJNE', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1139, 1, 'ACTA UNIVERSITATIS PALACKIANAE OLOMUCENSIS FACULTATIS MEDICAE', 'Acta Univ Palacki Olomuc Fac Med', 'Praha : Státní Pedagogické Nakladatelství, cop1960', '03012514', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1140, 1, 'COMPARATIVE IMMUNOLOGY, MICROBIOLOGY AND INFECTIOUS DISEASES', 'Comp. Immunol., Microbiol. Infect. Dis.', 'Oxford, Inglaterra, GB : Pergamon Press', '01479571', NULL, 'Bimestral', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1141, 1, 'CURRENT TOPICS IN CELLULAR REGULATION', 'Curr. Top. Cell. Regul.', 'New York, N.Y. : Academic Press, 1969-', '00702137', NULL, 'Irregular', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1142, 1, 'BIOMETRIKA', 'Biometrika', 'London, GB : Biometrika Trust // Cambridge : University Press ; New York : Macmillan Co. [distributo', '00063444', NULL, 'Quarterly, Oct. 1901- ; 3 no. a year, <1977- > ; Quarterly, <1992- >', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1143, 1, 'JOURNAL OF FUNCTIONAL ANALYSIS', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1144, 1, 'HORMONE RESEARCH', 'Horm. Res.', 'Basel, Suica, CH : European Association Of Endocrinology // Karger', '03010163', NULL, 'Bimestral', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1145, 1, 'MECHANISMS OF AGEING AND DEVELOPMENT', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1146, 1, 'JOURNAL OF THE FORMOSAN MEDICAL ASSOCIATION', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1147, 1, 'EUROPEAN JOURNAL OF ENDOCRINOLOGY', 'Eur. J. Endocrinol.', 'Oslo, NO : Scandinavian University Press, c1994-', '08044643', NULL, 'Monthly', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1148, 1, 'JOURNAL OF SUBMICROSCOPIC CYTOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1149, 1, 'OIKOS', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1150, 1, 'EVOLUTIONARY ECOLOGY', 'Evol. Ecol.', 'London, GB : Chapman & Hall, 1987-', '02697653', NULL, 'Four issues yearly', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1151, 1, 'PROCEEDINGS. BIOLOGICAL SCIENCES', '', '', '09628452', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1152, 1, 'FLORIDA ENTOMOLOGIST', 'Fla. Entomol.', 'Gainesville, Fla., US : Florida Entomological Society, 1920-', '00154040', NULL, 'Quarterly', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1153, 1, 'JOURNAL OF THE AMERICAN GERIATRICS SOCIETY', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1154, 1, 'NEUROLOGIC CLINICS', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1155, 1, 'TOXICOLOGIC PATHOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1156, 1, 'FARBE UND LACK [1947]', '', 'Hannover, Alemanha, DE : Curt R. Vincentz Verlag', '00147699', NULL, 'Mensal', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1157, 1, 'CORROSION', '', 'Houston : National Association of Corrosion Engineers, 1945-', '00109312', NULL, 'Quarterly Mar. 1945-June 1946; Monthly Sept. 1946-', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1158, 1, 'COMMUNICATIONS IN STATISTICS: THEORY AND METHODS', 'Commun. Stat., Theory Methods', 'New York, N.Y. : Marcel Dekker, inc., 1976-', '03610926', NULL, '25 no. a year 1983-', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1159, 1, 'ENVIRONMENTAL TECHNOLOGY', 'Environ. Technol.', 'London : Publications Division, Selper Ltd., 1990-', '09593330', NULL, 'Monthly', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1160, 1, 'IEEE TRANSACTIONS ON PATTERN ANALYSIS AND MACHINE INTELLIGENCE', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1161, 1, 'CRYSTAL RESEARCH AND TECHNOLOGY', 'Cryst. Res. Technol.', 'Berlin : Akademie-Verlag, c1981-', '02321300', NULL, 'Monthly; 8 no. a year, <1991->', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1162, 1, 'NETHERLANDS JOURNAL OF ZOOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1163, 1, 'DOXA: CUADERNOS DE FILOSOFIA DEL DERECHO', '', 'Alicante, Espanha, ES : Universidad De Alicante, Departamento De Filosofia Del Derecho,1984', '02148676', NULL, 'Anual', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1164, 1, 'JOURNAL OF ORAL PATHOLOGY AND MEDICINE', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1165, 1, 'CORROSION ASIA 1994: THE SECOND NACE ASIAN CONFERENCE, 26-30 SEPTEMBER 1994', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1166, 1, 'OCHRONA PRZED KOROZJA', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1167, 1, 'CONSTRUCTION AND BUILDING MATERIALS', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1168, 1, 'JOURNAL OF GENERAL VIROLOGY', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1169, 1, 'ADVANCES IN X-RAY ANALYSIS: PROCEEDINGS OF THE ... ANNUAL CONFERENCE ON APPLICATION OF X-RAY ANALYSIS', 'Adv. X-Ray Anal.', 'New York, US : Plenum Press', '03760308', NULL, 'Anual', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1170, 1, 'INFECTION AND IMMUNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1171, 1, 'AMERICAN JOURNAL OF CLINICAL NUTRITION', 'Am. J. Clin. Nutr.', 'New York, NY: Reuben H. Donnelley, 1954- / New York, US : Amer Soc For Clin / Bethesda, Md.', '00029165', NULL, 'Mensal', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1172, 1, 'JOURNAL OF CHEMICAL AND ENGINEERING DATA', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1173, 1, 'REVISTA DO CENTRO DE CIENCIAS RURAIS', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1174, 1, 'IMMUNOLOGICAL REVIEWS', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1175, 1, 'CANCER RESEARCH', 'Cancer Res.', 'Baltimore (MD) : Waberly Press , 1941 // American Association For Cancer Research', '00085472', NULL, 'Dos veces al mes', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1177, 1, 'ADVANCES IN INORGANIC CHEMISTRY', 'Adv. inorg. chem', 'Orlando, Fla. : Academic Press, 1987- // New York, US : Academic Press', '08988838', NULL, 'Irregular', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1178, 1, 'JOURNAL OF APPLIED PHYSIOLOGY: RESPIRATORY, ENVIRONMENTAL AND EXERCISE PHYSIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1179, 1, 'METAL FINISHING', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1180, 1, 'JOURNAL OF PHYSICAL ORGANIC CHEMISTRY', '', 'Chichester, Inglaterra , GB : John Wiley & Sons', '08943230', NULL, 'Bimestral', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1181, 1, 'IEEE TRANSACTIONS ON AUDIO AND ELECTROACOUSTICS', '', 'New York, Institute of Electrical and Electronics Engineers', '00189278', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1182, 1, 'MOLECULAR PHOTOCHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1183, 1, 'MICROBIOLOGY AND IMMUNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1184, 1, 'JOURNAL OF CLINICAL AND EXPERIMENTAL GERONTOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1185, 1, 'BULLETINS OF AMERICAN PALEONTOLOGY', 'Bull. Am. Paleontol.', 'Ithaca, Ny, US : Paleontological Research Institution // Cornell University [etc.], 1895-', '00075779', NULL, 'Trimestral', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1186, 1, 'BRITISH MEDICAL BULLETIN', 'Br. Med. Bull.', 'Edinburgh, Inglaterra, GB : Churchill Livingstone Medical Journals', '00071420', NULL, 'Trimestral', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1187, 1, 'JOURNAL OF LEUKOCYTE BIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1189, 1, 'PROCEEDINGS OF THE INDIAN ACADEMY OF SCIENCES. SECTION A', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1190, 1, 'MYCOLOGIA', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1191, 1, 'CHEMISTRY OF MATERIALS', 'Chem. Mater.', 'Washington, US : American Chemical Society', '08974756', NULL, 'Bimonthly // Mensal', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1192, 1, 'INTERNATIONAL JOURNAL FOR NUMERICAL METHODS IN ENGINEERING', '', '', '1097-0207', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1193, 1, 'RESEARCH IN VETERINARY SCIENCE', 'Res Vet Sci', '', '0034-5288 (Print) 1532-2661 (Electronic)', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1194, 1, 'KLINISCHE PEDIATRIE', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1195, 1, 'SCHWEIZER ARCHIV FUR TIERHEILKUNDE', '', '', '', NULL, '', '2015-09-01 14:24:48', '2015-09-01 14:25:01'),
(1196, 1, 'CATALYSIS LETTERS', 'Catal. Lett.', 'Basel, Suica, CH : J. C. Baltzer', '1011372X', NULL, 'Mensal', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1197, 1, 'JOURNAL OF THE AMERICAN ANIMAL HOSPITAL ASSOCIATION', 'J. / Am. Anim. Hosp. Assoc.', 'Lakewood, Colo., US : American Animal Hospital Association', '05872871', NULL, 'Bimestral', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1198, 1, 'VETERINARY CLINICS OF NORTH AMERICA. SMALL ANIMAL PRACTICE', 'Vet Clin Small Anim Pract', '', '0195-5616', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1199, 1, 'MEDEDELINGEN VAN DE FACULTEIT LANDBOUWWETENSCHAPPEN RIJKSUNIVERSITEIT GENT', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1200, 1, 'PHYSICAL CHEMISTRY CHEMICAL PHYSICS', '', '', '1751-1097', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1201, 1, 'TRUDY [GEL MINTOLOGICHESKAIA LABORATORIIA AKADEMIIA NAUK SSSR]', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1202, 1, 'ZENTRALBLATT FUR VETERINARMEDIZIN. REIHE A', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1203, 1, 'QUARTERLY OF APPLIED MATHEMATICS', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1204, 1, 'AMERICAN JOURNAL OF HUMAN GENETICS', 'Am. j. hum. genet', 'Chicago, Ill. [etc.] : University of Chicago Press [etc.], 1949-', '00029297', NULL, 'Quarterly 1949-<Dec. 1950> / bimonthly', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1205, 1, 'GENETICS', 'Genetics (Austin)', 'Chapel Hill, Nc, US : Genetics Society Of America // Princeton, N.J. [etc.] : Princeton University P', '00166731', NULL, 'Bimonthly 1916-  ; monthly <, 1975- >', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1206, 1, 'TALANTA', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1207, 1, 'VACCINE', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1208, 1, 'JOURNAL OF CHROMATOGRAPHY & RELATED TECHNOLOGIES', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1209, 1, 'JAPANESE JOURNAL OF APPLIED PHYSICS', '', '', '0021-4922', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1210, 1, 'PROCEEDINGS OF THE INTERSOCIETY ENERGY CONVERSION ENGINEERING CONFERENCE. 24TH', '', 'IECEC', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1211, 1, 'PROCEEDINGS OF THE INTERSOCIETY ENERGY CONVERSION ENGINEERING CONFERENCE. 23TH', '', 'IECEC', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1212, 1, 'PROCEEDINGS OF THE INTERSOCIETY ENERGY CONVERSION ENGINEERING CONFERENCE. 25TH', '', 'IECEC', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1213, 1, 'PROCEEDINGS OF THE INTERSOCIETY ENERGY CONVERSION ENGINEERING CONFERENCE. 26TH', '', 'IECEC', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1214, 1, 'IMMUNOGENETICS', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:07'),
(1215, 1, 'TISSUE ANTIGENS', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:08'),
(1216, 1, 'U.S. PATENT 4.177.325', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:08'),
(1217, 1, 'U.S. PATENT 4.224.388', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:08'),
(1218, 1, 'U.S. PATENT 3.867.199', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:08'),
(1219, 1, 'JOURNAL OF THE AMERICAN VETERINARY ASSOCIATION', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:08'),
(1220, 1, 'JOURNAL OF THE VETERINARY DIAGNOSTIC INVESTIGATION', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:08'),
(1221, 1, 'MACROMOLECULAR THEORY AND SIMULATIONS', '', '', '', NULL, '', '2015-09-01 14:24:48', '2016-01-12 14:16:08'),
(1222, 1, 'JOURNAL OF PROTOZOOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1223, 1, 'CAHIERS DU CINEMA', 'Cah. Cine.', 'Paris, FR : Les Éditions de l´Etoile , cop1951', '0008011X', NULL, 'Monthly', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1224, 1, 'JOURNAL OF PHYSICS. F. METAL PHYSICS', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1225, 1, 'ANNALS OF THE MISSOURI BOTANICAL GARDEN', 'Ann. Mo. Bot. Gard. // Ann. Missouri Bot. Gard.', '[St. Louis, Mo. : Missouri Botanical Garden Press, etc.], 1914-', '00266493', NULL, 'Quarterly', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1226, 1, 'ANNALS AND MAGAZINE OF NATURAL HISTORY', 'Ann. Mag. Nat. Hist.', 'London, GB: Taylor & Francis', '03745481', NULL, 'Mensal', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1227, 1, 'JOURNAL OF APPLIED CHEMISTRY & BIOTECHNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1228, 1, 'HYDROMETALLURGY', 'Hydrometallurgy', 'Amsterdam, NL : Elsevier Scientific Publishing', '0304386X', NULL, 'Trimestral', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1229, 1, 'CLINICAL PHARMACOKINETICS', 'Clin. Pharmacokinet.', 'Auckland, Nova Zelandia, NZ  // Sydney // New York, ADIS Press', '03125963', NULL, 'Mensual', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1230, 1, 'REVISTA ESPAÑOLA DE FISIOLOGIA', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1231, 1, 'CELL AND TISSUE RESEARCH', 'Cell Tissue Res.', 'Berlin, New York, Springer-Verlag', '0302766X', NULL, 'Mensual', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1232, 1, 'STATISTICS AND PROBABILITY LETTERS', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1233, 1, 'ACTA MEDICA SCANDINAVICA. SUPPLEMENTUM', 'Acta med. Scand., suppl.', 'Stockholm, Distributed by Amqvist & Wiksell [etc.]', '0365463X', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1234, 1, 'MICROSCOPY RESEARCH AND TECHNIQUE', '', '', '1097-0029', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1235, 1, 'IRE TRANSACTIONS ON MICROWAVE THEORY AND TECHNIQUES', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1236, 1, 'IRE TRANSACTIONS ON CIRCUIT THEORY', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1237, 1, 'CLINICAL AND EXPERIMENTAL OBSTETRICS & GYNECOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1238, 1, 'INTERNATIONAL JOURNAL OF OBESITY', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1239, 1, 'BAILLIERE\'S CLINICAL ENDOCRINOLOGY AND METABOLISM', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1240, 1, 'NIPPON RINSHO = JAPANESE JOURNAL OF CLINICAL MEDICINE', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1241, 1, 'MOLECULAR AND CELLULAR ENDOCRINOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1242, 1, 'HISTORY OF BIOLOGY', '', 'Nordenskiold, Erik', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1243, 1, 'AMERICAN JOURNAL OF PHYSICAL ANTHROPOLOGY', 'Am. J. Phys. Anthropol.', 'New York [etc.] A. R. Liss [etc.] // Philadelphia : Wistar Institute of Anatomy and Biology , cop191', '00029483', NULL, 'Quarterly, 1918- / Monthly (except Apr., June, Oct., and Dec.)', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1244, 1, 'COMPUTATIONAL AND THEORETICAL POLYMER SCIENCE', 'Comput. Theor. Polymer Sci.', 'Cincinnati, Ohio, US : Polymer Research Associates', '10893156', NULL, 'Trimestral', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1245, 1, 'JOURNAL OF VIROLOGICAL METHODS', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1246, 1, 'NEW ENGLAND JOURNAL OF MEDICINE', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1248, 1, 'JOURNAL OF FERMENTATION AND BIOENGINEERING', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1249, 1, 'JOURNAL OF CHEMICAL TECHNOLOGY AND BIOTECHNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1250, 1, 'AUTOTROPHIC BACTERIA', '', 'Schlegel and Bowien eds.', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1251, 1, 'INNOVATIVE APPROACHES TO PLANT DISEASE CONTROL', '', 'Chet ed.', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1252, 1, 'MYCOLOGICAL RESEARCH', '', 'Cambridge, Inglaterra , GB : Cambridge University Press', '09537562', NULL, 'Irregular', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1253, 1, 'TRANSACTIONS OF THE BRITISH MYCOLOGICAL SOCIETY', 'Trans Br Mycol Soc', '', '0007-1536', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1254, 1, 'TRANSACTIONS [AMERICAN GEOPHYSICAL UNION]', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1255, 1, 'TRENDS IN PHARMACOLOGICAL SCIENCES', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1256, 1, 'ZEITSCHRIFT FUR CHEMIE', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1257, 1, 'BIOLOGICAL CONTROL: THEORY AND APPLICATION IN PEST MANAGEMENT', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1258, 1, 'EUROPEAN PHYSICAL JOURNAL. B, CONDENSED MATTER PHYSICS', 'Eur. Phys. J., B Cond. Matter Phys.', 'Les Ulis, France : EDP Sciences ; Secaucus, NJ : Springer-Verlag, 1998-', '14346028', NULL, 'Bimestral // Semimonthly', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1259, 1, 'ANNUAL REVIEW OF FLUID MECHANICS', '', 'Palo Alto, Calif. : Annual Reviews, inc., 1969-', '00664189', NULL, 'Annual', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1260, 1, 'AN INTRODUCTION TO THE BOOTSTRAP', '', 'Bradley and Tibshirani', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1261, 1, 'JOURNAL OF MEDICAL GENETICS', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1263, 1, 'ADVANCES IN COMPUTATIONAL MATHEMATICS', 'Adv. Comput. Math.', 'Amsterdam, NL : Baltzer Science Publishers', '10197168', NULL, 'Trimestral', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1264, 1, 'INSECT-PLANT INTERACTIONS', '', 'Berbays, E.A. ed.', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1265, 1, 'JOURNAL OF MICROBIOLOGICAL METHODS', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1266, 1, 'BIOTECHNOLOGY TECHNIQUES', 'Biotechnol. Tech.', 'Kew, Inglaterra, GB : Science And Technology Letters', '0951208X', NULL, 'Mensal', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1267, 1, 'NEUROCHEMICAL RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1268, 1, 'JOURNAL OF EXPERIMENTAL ZOOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1269, 1, 'GAMETE RESEARCH', 'Gamete Res', 'New York, Liss', '0148-7280', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1270, 1, 'PLANETARY AND SPACE SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1271, 1, 'DISCRETE MATHEMATICS', 'Discrete Math.', 'Amsterdam, North-Holland Publishing Co.', '0012365X', NULL, 'Irregular', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1272, 1, 'GEOPHYSICAL JOURNAL OF THE RAS, DGG AND EGS', '', 'Oxford : Blackwell Scientific Publications Ltd., 1989', '0955419X', NULL, 'Monthly', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1273, 1, 'BIOTECHNIQUES', '', 'Natick, MA : Eaton Pub. Co., c1983-', '07366205', NULL, 'Ten no. a year // Mensal', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1274, 1, 'JOURNAL OF CONTROLLED RELEASE', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1275, 1, 'BIOSPECTROSCOPY', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1276, 1, 'PROGRESS IN CLINICAL AND BIOLOGICAL RESEARCH', 'Prog Clin Biol Res', '', '0361-7742', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1277, 1, 'NEW ZEALAND VETERINARY JOURNAL', 'New Zeal Vet J', '', '0048-0169 (e): 1176-0710', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1278, 1, 'PREVENTIVE VETERINARY MEDICINE', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1279, 1, 'MATHEMATISCHE ZEITSCHRIFT', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1280, 1, 'JOURNAL OF PROSTHETIC DENTISTRY', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1281, 1, 'AMERICAN JOURNAL OF ORTHODONTICS AND DENTOFACIAL ORTHOPEDICS', 'Am. j. orthod. dentofac. orthop.', 'St. Louis : Mosby , 1915', '08895406', NULL, 'Mensual', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1282, 1, 'ADVANCES IN CORROSION SCIENCE AND TECHNOLOGY', 'Adv. corros. sci. technol', 'New York, Plenum Press, 1970-', '00652474', NULL, 'Annual (irregular)', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1283, 1, 'PESTICIDE BIOCHEMISTRY AND PHYSIOLOGY', '', '', '0048-3575', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1284, 1, 'ARCHIV FÜR ANATOMIE, PHYSIOLOGIE UND WISSENSCHAFTLICHE MEDIZIN', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1285, 1, 'ACTA OECOLOGICA', '', 'Paris, FR : Gauthier Villars', '1146609X', NULL, 'Bimestral', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1286, 1, 'CEREAL CHEMISTRY', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1287, 1, 'BIODIVERSITY AND CONSERVATION', '', 'London : Chapman & Hall , 1992-', '09603115', NULL, 'Bimestral', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1288, 1, 'MOLECULAR AND BIOCHEMICAL PARASITOLOGY', 'Mol Biochem Parasitol', '', '0166-6851', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1289, 1, 'CROP PROTECTION', 'Crop Prot.', 'Guildford, Inglaterra, GB : Butterworth Scientific', '02612194', NULL, 'Trimestral', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1290, 1, 'STATISTICAL MECHANICS OF CHARGED PARTICLES', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1291, 1, 'ELECTRONICS OF SOLIDS', '', 'Beam, Walter R.', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1292, 1, 'ACTA METALLURGICA ET MATERIALIA', 'Acta Metal. Mater.', 'Elmsford, N.Y. : Pergamon Press, c1990-c1995 // Published: Tarrytown, N.Y. : Oxford, England : Elsev', '09567151', NULL, 'Monthly', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1293, 1, 'INDIAN JOURNAL OF CHEMISTRY. SECTION B', 'Indian J Chem B Org', '', '0376-4699', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1294, 1, 'CRYSTAL OPTICS WITH SPATIAL DISPERSION AND EXCITONS', '', 'Agranovich, V.M.', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1295, 1, 'PUBLICATIONS MATHEMATIQUES', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1296, 1, 'LIEBIGS ANNALEN DER CHEMIE', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1297, 1, 'CURRENT SCIENCE (BANGALORE)', 'Curr. Sci.', 'Bangalore : Current Science Association, 1932-', '00113891', NULL, 'Monthly, 1932-63; bimonthly; semimonthly Mar. 20, 1979-', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1298, 1, 'ELEKTROKHIMIIA', '', '', '04248570', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1299, 1, 'APPLIED SPECTROSCOPY REVIEWS', 'Appl. spectrosc. rev. (Softcover ed.)', 'New York, etc. : M. Dekker, 1967-', '05704928', NULL, 'Semestral', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1300, 1, 'EXPERIMENTAL THERMAL AND FLUID SCIENCE', 'Exp. Therm. Fluid Sci.', 'New York, US : Elsevier Science Publishers, 1988-', '08941777', NULL, 'Quarterly', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1301, 1, 'INTERNATIONAL JOURNAL OF PARASITOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1302, 1, 'CLADISTICS', 'Cladistics', 'Westport, Conn. : Meckler Pub., c1985-', '07483007', NULL, 'Quarterly // Bimestral', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1303, 1, 'SISTEMATIC ZOOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1304, 1, 'THEORY OF METALS', '', 'Wilson, Alan Herries', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1305, 1, 'PROPERTIES OF LITHIUM NIOBATE', '', 'INSPEC', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1306, 1, 'KOORDINATSIONNAIA KHIMIIA', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1307, 1, 'JOURNAL OF THE AMERICAN STATISTICAL ASSOCIATION', 'J Am Stat Assoc', '', '0162-1459  (e): 1537-274X', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1308, 1, 'VETERINARIAN MEDICINE (1985)', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1309, 1, 'PROGRESS IN NUCLEIC ACID RESEARCH AND MOLECULAR BIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1310, 1, 'JOURNAL OF LABELLED COMPOUNDS & RADIOPHARMACEUTICALS', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1311, 1, 'PHYSICA STATUS SOLIDI', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1312, 1, 'SOVIET PHYSICS, CRYSTALLOGRAPHY', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1313, 1, 'ESTUARIES', '', 'Columbia, Sc, US : Estuarine Research Federation', '01608347', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1314, 1, 'CANADIAN TECHNICAL REPORT OF FISHERIES AND AQUATIC SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1315, 1, 'FISHERIES SCIENCE', 'Fish. Sci.', 'Tokyo, JP : Japanese Society Of Fisheries Sciences // Oxford : Blackwell Science ; [Dublin, Ohio] :', '09199268', NULL, 'Bimestral', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1316, 1, 'BIOLOGY AND ENVIRONMENT : PROCEEDINGS OF THE ROYAL IRISH ACADEMY', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1317, 1, 'OPHELIA. SUPPLEMENTUM', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1318, 1, 'SCIENCE SINCE BABYLON', '', 'Price, Derek J. de Solla', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1319, 1, 'PROCEEDINGS OF THE ROYAL SOCIETY OF LONDON. SERIES A, CONTAINING PAPERS OF A MATHEMATICAL AND PHYSICAL CHARACTER', 'Proc. R. Soc. Lond., Ser. A, Math. Phys. Sci.', 'London : Harrison and Son, 1905-1990', '00804630', NULL, 'Monthly, Dec. 1989-', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1320, 1, 'REVISTA BRASILEIRA DE ENTOMOLOGIA', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1321, 1, 'ONDERSTEPOORT JOURNAL OF VETERINARY', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1322, 1, 'AGRONOMY JOURNAL', 'Agron. j', 'Madison, Wis. [etc.] : American Society of Agronomy, 1949-', '00021962', NULL, 'Monthly 1949-   bimonthly <, v. 53->', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1323, 1, 'JOURNAL OF CATALYSIS', 'J. Catal.', 'San Diego, Calif. // New York, US : Academic Press', '00219517', NULL, 'Bimonthly 1962-<Dec. 1963> ; monthly (semimonthly in Mar., June, Oct.) <Oct. 1975-> ; monthly <Jan.', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1324, 1, 'INTEGRATED FERROELECTRICS', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1325, 1, 'ENVIRONMENTAL TOXICOLOGY', 'Environ. Toxicol.', 'New York, US : John Wiley & Sons', '15204081', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1326, 1, 'PHILOSOPHICAL TRANSACTIONS OF THE ROYAL SOCIETY OF LONDON. B. BIOLOGICAL SCIENCES', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1327, 1, 'BIOCHEMISTRY AND MOLECULAR BIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1328, 1, 'CHEMOTHERAPY', 'Chemotherapy', 'Basel [etc.] New York, S. Karger', '00093157', NULL, 'Bimonthly', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1329, 1, 'SCIENTIA PHARMACEUTICA', 'Sci Pharm', '', '0036-8709', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1330, 1, 'U.S. PATENT 3.436.407', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1331, 1, 'BRITISH JOURNAL OF CANCER', 'Br. J. Cancer', 'Edinburgh, Inglaterra, GB : British Empire Cancer Campaign For Research', '00070920', NULL, 'Quinzenal', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1332, 1, 'ANNALES PHARMACEUTIQUES FRANCAISES', 'Ann. pharm. Fr', 'Paris [etc.] : Masson , cop1943 // Paris, FR : Societe De Pharmacie De Paris Et De Province', '00034509', NULL, 'Mensual', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1333, 1, 'CLINICAL AND DIAGNOSTIC LABORATORY RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1334, 1, 'ALLERGOLOGIA ET IMMUNOPATHOLOGIA', 'Allergol. Immunopathol.', 'Madrid : Garsi , cop1973', '03010546', NULL, 'Bimestral', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1335, 1, 'ALLERGY', '', 'Copenhagen, DK : Munksgaard International Publishers', '01054538', NULL, 'Mensal  // Eight issues a year Apr. 1981-', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1336, 1, 'ZEITSCHRIFT FUR KRISTALLOGRAPHIE. SUPPLEMENT', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1337, 1, 'TECHNICAL BULLETIN [UNITED STATES DEPARTMENT OF AGRICULTURE]', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1338, 1, 'ADVANCES IN WELDING SCIENCE AND TECHNOLOGY: TWR\'86: PROCEEDINGS OF AN INTERNATIONAL CONFERENCE ON TRENDS IN WELDING RESEARCH, GATLINBURG, TENNESSE, USA, 18-22 MAY 1986', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1339, 1, 'AUSTRALIAN JOURNAL OF EXPERIMENTAL AGRICULTURE', '', 'Melbourne, Australia, AU : Commonwealth Scientific And Industrial Research Organization', '08161089', NULL, 'Trimestral', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1340, 1, 'TRENDS IN WELDING RESEARCH IN THE UNITED STATES: PROCEEDINGS OF A CONFERENCE, NEW ORLEANS, LUISIANA, 16-18 NOVEMBER, 1981', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1341, 1, 'JOURNAL OF MOLECULAR CATALYSIS. B. ENZYMATIC', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1342, 1, 'HELMINTHOLOGIA', '', 'Bratislava, Tchecoslovaquia, CS : Academia Scientiarum Slovaca', '04406605', NULL, 'Trimestral', '2015-09-01 14:24:49', '2016-01-12 14:16:08');
INSERT INTO `journal` (`id`, `instance_id`, `name`, `abbreviation`, `responsible`, `ISSN`, `ISSNE`, `frecuency`, `createdAt`, `updatedAt`) VALUES
(1343, 1, 'BULLETIN DU MUSEUM NATIONAL D\'HISTOIRE NATURELLE', 'Bull. Mus. Natl. Hist. Nat.', 'Paris, FR : Museum National D´Histoire Naturelle', '11488425', NULL, 'Bimestral', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1344, 1, 'JOURNAL OF BIOCHEMICAL AND BIOPHYSICAL METHODS', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1345, 1, 'EUPHYTICA', '', 'Wageningen, Holanda, NL : Foundation Euphytica', '00142336', NULL, 'Trimestral', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1346, 1, 'BIOLOGY OF REPRODUCTION', 'Biol. Reprod.', '[Champaign, Ill., etc.] Society for the Study of Reproduction [etc.]', '00063363', NULL, 'Monthly (except Jan. and July) <, June 1976->', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1347, 1, 'MATERIALS SCIENCE AND ENGINEERING. R. REPORTS : A REVIEW JOURNAL', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1348, 1, 'PEDIATRIC ALLERGY AND IMMUNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1349, 1, 'JOURNAL OF PEDIATRIC GASTROENTEROLOGY AND NUTRITION', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1350, 1, 'CANADIAN JOURNAL OF GENETICS AND CYTOLOGY', 'Can. J. Genet. Cytol.', 'Ottawa : Genetics Society of Canada, 1959-1986', '00084093', NULL, 'Quarterly -1981 ; bimonthly 1982-1986', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1351, 1, 'JOURNAL OF GENERAL MICROBIOLOGY', 'J. Gen. Microbiol.', 'London, GB : Cambridge University Press', '00221287', NULL, 'Three no. a year 1947-50 ; quarterly 1951-52 ; bimonthly 1953-1960 ; monthly 1960-', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1352, 1, 'BOVINE PRACTITIONER', 'Bov. Pract.', 'Stillwater, Okla., US : American Association Of Bovine Practitioners', '05241685', NULL, 'Anual', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1353, 1, 'IBM JOURNAL OF RESEARCH AND DEVELOPMENT', 'Ibm J. Res. Develop.', 'New York, US : International Business Machines', '00188646', NULL, 'Bimestral', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1354, 1, 'EUROPEAN JOURNAL OF MECHANICS. A. SOLIDS', 'Eur. J. Mech. A, Solids', 'Paris, FR : Gauthier Villars, 1989-', '09977538', NULL, 'Six issues yearly', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1355, 1, 'JOURNAL OF ARID ENVIRONMENTS', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1356, 1, 'CELL AND TISSUE CULTURE IN FORESTRY', '', 'Jordan, M.', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1357, 1, 'FOREST ECOLOGY AND MANAGEMENT', 'For. Ecol. Manage.', 'Amsterdam, NL : Elsevier Science Publishers', '03781127', NULL, 'Trimestral', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1358, 1, 'MEDICINA DEL LAVORO', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1359, 1, 'PROGRESS OF THEORETICAL PHYSICS', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1360, 1, 'JOURNAL OF PHYSICS. C. SOLID STATE PHYSICS', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1361, 1, 'PHYSICAL REVIEW. E. STATISTICAL PHYSICS, PLASMAS, FLUIDS, AND RELATED INTERDISCIPLINARY TOPICS', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1362, 1, 'INTERNATIONAL JOURNAL OF REDIATION APPLICATIONS AND INSTRUMENTATION. PART C', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1363, 1, 'GUT: THE JOURNAL OF THE BRITISH SOCIETY OF GASTROENTEROLOGY', 'Gut', 'London : British Medical Association , cop1960 // British Society Of Gastroenterology', '00175749', NULL, 'Mensual ; Anteriormente bimestral', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1365, 1, 'ABSTRACTS WITH PROGRAMS (GEOLOGICAL SOCIETY AMERICAN)', 'Abstr. program - Geol. Soc. Am', '[Boulder, Colo. : Geological Society of America], 1969-', '00167592', NULL, 'Frequency varies', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1366, 1, 'LUNAR AND PLANETARY SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1367, 1, 'EUROPEAN JOURNAL OF APPLIED MICROBIOLOGY AND BIOTECHNOLOGY', 'Eur. J. Appl. Microbiol. Biotechnol.', 'Berlin, DE : Springer International, 1978-1983', '01711741', NULL, 'Four issues a year;  Three vols. of 4 issues <, 1981->;  Two vols. of 6 issues <, 1983->', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1368, 1, 'GERMAN CHEMICAL ENGINEERING', 'Ger. Chem. Eng.', 'Weinheim, Alemanha, DE : Verlag Chemie', '03435539', NULL, 'Bimestral', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1369, 1, 'CERAMIC TRANSACTIONS', 'Ceram. Trans.', 'Westerville, Ohio : American Ceramic Society, c1988-', '10421122', NULL, 'Irregular', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1370, 1, 'MEETING ABSTRACTS', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1371, 1, 'ACTA MATHEMATICA HUNGARICA', 'Acta math. Hung.', 'Budapest : Akademiai Kiado, 1983-', '02365294', NULL, 'Four no. a year', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1372, 1, 'JOURNAL OF FOOD ENGINEERING', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1373, 1, 'JOURNAL OF TEXTURE STUDIES', '', 'Westport, Conn., US : Food & Nutrition Press', '00224901', NULL, 'Trimestral', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1374, 1, 'JOURNAL OF BIOENERGETICS AND BIOMEMBRANES', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1375, 1, 'MICROBIOLOGICAL REVIEWS', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1376, 1, 'PHOTOCHEMISTRY AND PHOTOBIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1377, 1, 'CROP SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1378, 1, 'GENES AND GENETICS SYSTEMS', '', 'Mishima, Shizuoka, Japan : Genetics Society of Japan, [1996-', '13417568', NULL, 'Bimonthly', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1379, 1, 'SOVIET JOURNAL OF NUCLEAR PHYSICS', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1380, 1, 'ANNUAL REVIEW OF BIOPHYSICS AND BIOMOLECULAR STRUCTURE', 'Annu. rev. biophys. biomol. struct', 'Palo Alto, Calif. : Annual Reviews Inc., c1992-', '10568700', NULL, 'Annual', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1381, 1, 'MATERIALS RESEARCH SOCIETY SYMPOSIA PROCEEDINGS', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1382, 1, 'JOURNAL OF FOOD SCIENCE', 'J. Food Sci.', 'Chicago, Ill., US : Institute Of Food Technologists', '00221147', NULL, 'Bimestral', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1383, 1, 'ANNALI DI MICROBIOLOGIA ED ENZIMOLOGIA', 'Ann. Microbiol. Enzimol.', 'Milan : Universitá degli Studi di Milano, 1940-1999', '00034649', NULL, 'Semestral // Bimestral', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1384, 1, 'IEEE TRANSACTIONS ON MICROWAVE THEORY AND TECHNIQUES', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1385, 1, 'ADVANCES IN DENTAL RESEARCH', 'Adv. dent. res.', 'Washington D. C. : International Association for Dental Research, 1987-', '08959374', NULL, 'Irregular', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1386, 1, 'AIAA JOURNAL', 'AIAA j', '[New York, etc.] American Institute of Aeronautics and Astronautics', '00011452', NULL, 'Monthly', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1387, 1, 'DIABETES CARE', '', 'Alexandria, Va., US  // New York, American Diabetes Assn', '01495992', NULL, 'Mensal', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1388, 1, 'DRYING TECHNOLOGY', '', 'New York, US : Marcel Dekker', '07373937', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1389, 1, 'REVISTA LATINOAMERICANA DE QUIMICA', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1390, 1, 'REVISTA DE FARMACIA E BIOQUIMICA DA UNIVERSIDADE DE SAO PAULO', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1391, 1, 'PHARMACEUTICAL AND PHARMACOLOGICAL LETTERS', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1392, 1, 'ZEITSCHRIFT FUR LEBENSMITTEL-UNTERSUCHUNG UND-FORSCHUNG. A,', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1393, 1, 'PLANT SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1394, 1, 'HEPATOLOGY', 'Hepatology', 'Orlando, Fla., US : W.B. Saunders', '02709139', NULL, 'Mensal', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1395, 1, 'CANADIAN JOURNAL OF ANIMAL SCIENCE', 'Can. J. Anim. Sci.', 'Ottawa, CA : Agricultural Institute Of Canada', '00083984', NULL, 'Trimestral', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1396, 1, 'FORENSIC SCIENCE INTERNATIONAL', 'Forensic Sci. Int.', 'Lausanne, Suica, CH : Elsevier Sequoia', '03790738', NULL, 'Bimestral', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1397, 1, 'JOURNAL OF EMERGENCY MEDICINE', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1399, 1, 'CELLULAR AND MOLECULAR LIFE SCIENCES', 'Cell. Mol. Life Sci.', 'Basel, Switzerland : Birkhäuser, c1997-', '1420682X', NULL, 'Monthly', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1400, 1, 'INTERNATIONAL JOURNAL OF COSMETICS SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1401, 1, 'REVISTA DE CHIMIE', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1402, 1, 'NEW SYNTHESES WITH CARBON MONOXIDE', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1403, 1, 'CHEMIA ANALITYCZNA', 'Chem. Anal.', 'Warszawa : Polskiej Akademii Nauk, 1956-', '00092223', NULL, 'Quarterly', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1404, 1, 'LECTURE NOTES IN PHYSICS', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1405, 1, 'AMERICAN JOURNAL OF CLINICAL PATHOLOGY', 'Am J Clin Pathol', 'Philadelphia [etc.] Lippincott [etc.] // Baltimore : Williams and Wilkins, cop1931', '00029173', NULL, 'Mensual', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1406, 1, 'ZEITSCHRIFT FUR WAHRSCHEINLICHKEITSTHEORIE UND VREWNDTW GEBIETE', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1407, 1, 'EUROPEAN JOURNAL OF PHARMACOLOGY', 'Eur. J. Pharmacol.', 'Amsterdam : North-Holland, 1967- // Elsevier Science Publishers', '00142999', NULL, 'Thirty-nine issues per year, <1980>-', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1408, 1, 'VETERINARY RESEARCH COMMUNICATIONS', '', '', '0165-7380', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1409, 1, 'VIROLOGY', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1410, 1, 'STHAL UND EISEN', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1411, 1, 'PLANETS, STARS AND NEBULAE.', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1412, 1, 'INDIAN JOURNAL OF DAIRY SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1413, 1, 'CAN. PAT. APPL. CA 2185510', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1414, 1, 'ENVIRONMENTAL SCIENCE & TECHNOLOGY', 'Environ. Sci. Technol.', 'Easton, Pa., US // Washington, etc., American Chemical Society', '0013936X', NULL, 'Monthly, 1967-; 13 no. a year 1977-', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1415, 1, 'TRANSITION METAL CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1416, 1, 'ENVIRONMENTAL TOXICOLOGY AND CHEMISTRY', 'Environ. Toxicol. Chem.', 'New York : Pergamon, c1982-', '07307268', NULL, 'Quarterly, 1982-; Monthly, <1986->', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1417, 1, 'INTERNATIONAL JOURNAL OF SCIENCE EDUCATION', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1418, 1, 'LEARNING AND INSTRUCTION', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1419, 1, 'JOURNAL OF MATHEMATICAL ANALYSIS AND APPLICATIONS', '', '', '0022-247x', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1420, 1, 'SOVIET SCIENTIFIC REVIEWS. B. CHEMISTRY REVIEWS', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1421, 1, 'MOLECULAR BIOLOGY AND EVOLUTION', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1422, 1, 'AMERICAN JOURNAL OF PUBLIC HEALTH (1971)', 'Am. j. publ. health (1971)', 'Washington, D.C. : American Public Health Association, 1971-', '00900036', NULL, 'Monthly', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1423, 1, 'FEMS IMMUNOLOGY AND MEDICAL MICROBIOLOGY', '', 'Amsterdam, NL : Elsevier Science Publishers, 1993-', '09288244', NULL, 'Monthly', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1424, 1, 'PLASMID', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1425, 1, 'JOURNAL OF RADIOANALYTICAL AND NUCLEAR CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1426, 1, 'SIAM JOURNAL ON DISCRETE MATHEMATICS', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1427, 1, 'PLASMA CHEMISTRY AND PLASMA PROCESSING', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1428, 1, 'TAGUNGSBERICHT', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1429, 1, 'ERDOL, ERDGAS, KHOLE', '', 'Hamburg, Alemanha, DE : Urban Verlag', '', NULL, 'Mensal', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1430, 1, 'KHIMICHESKAIA PROMYSHLENNOST', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1432, 1, 'AVIAN DISEASES', 'Avian Dis', 'Kennett Square, Pa., US : American Association Of Avian Pathologists', '0005-2086', NULL, 'Trimestral', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1433, 1, 'JOURNAL OF INORGANIC AND NUCLEAR CHEMISTRY', 'J Inorg Nucl Chem', '', '0022-1902', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1434, 1, 'ENTOMOPHAGA', '', 'Paris, FR : Lavoisier Abonnements', '00138959', NULL, 'Trimestral', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1435, 1, 'CURRENT BIOLOGY', 'Curr. Biol.', 'London, UK : Current Biology Ltd., c1991-', '09609822', NULL, 'Bimonthly, <Feb. 1991->; Monthly, <Dec. 1991->; 24 times a year, <30 July/13 Aug. 1998->', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1436, 1, 'INTERNATIONAL JOURNAL OF ZOONOSES', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1437, 1, 'JOURNAL OF EXPERIMENTAL MARINE BIOLOGY AND ECOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1438, 1, 'FISH PHYSIOLOGY AND BIOCHEMISTRY', 'Fish Physiol. Biochem.', 'Amsterdam : Kluwer Academic, 1986-', '09201742', NULL, 'Eight issues yearly', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1439, 1, 'AQUATIC TOXICOLOGY', 'Aquat. Toxicol.', 'Amsterdam, NL : Elsevier North Holland Biomedical Press', '0166445X', NULL, 'Mensal', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1440, 1, 'MARINE ECOLOGY. PROGRESS SERIES', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1441, 1, 'NEWER METHODS OF PREPARATIVE ORGANIC CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1443, 1, 'ARCHIVES OF ENVIRONMENTAL CONTAMINATION AND TOXICOLOGY', 'Arch. environ. contam. toxicol.', 'New York, Springer-Verlag, 1973-', '0090-4341', NULL, '6 no. a year, 1979-1980 ; Four no. a year, <1975>-1978 ;  Bimonthly, 1981-', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1444, 1, 'THEORETICAL CHEMISTRY ACCOUNTS', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1445, 1, 'PROCEEDINGS OF THE BIOLOGICAL SOCIETY OF WASHINGTON', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1446, 1, 'JOURNAL OF THE KANSAS ENTOMOLOGICAL SOCIETY', 'J Kans Entomol Soc', '', '0022-8567', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1447, 1, 'COMPUTER METHODS IN APPLIED MECHANICS AND ENGINEERING', 'Comput. Methods Appl. Mech. Eng.', 'Amsterdam : North-Holland Pub. Co., 1972-', '00457825', NULL, 'Monthly', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1448, 1, 'REMOTE SENSING REVIEWS', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1449, 1, 'JOURNAL OF THE FACULTY OF SCIENCE OF SCIENCE, UNIVERSITY OF TOKYO.SECTION III.BOTANY', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1450, 1, 'DREVO', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1451, 1, 'INTERNATIONAL JOURNAL FOR RADIATION PHYSICS AND CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1452, 1, 'CHEMISTRY', 'Chemistry', 'Weinheim, Germany : VCH Verlagsgesellschaft, 1995-', '09476539', NULL, 'Bimestral / Quinzenal', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1453, 1, 'EXTENDED ABSTRACTS', 'Ext. Abstr.', 'Princeton, Nj, US : Electrochemical Society', '01604619', NULL, 'Semiannual', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1454, 1, 'PROCEEDINGS OF THE INTERNATIONAL POWER SOURCES SYMPOSIUM. 34TH', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1455, 1, 'POWER SOURCES', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1456, 1, 'PROCEEDINGS OF THE INTERNATIONAL POWER SOURCES SYMPOSIUM. 17TH', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1457, 1, 'SEED SCIENCE AND TECHNOLOGY', '', '', '0251-0952', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1458, 1, 'JOURNAL OF ECOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1459, 1, 'VLSI SIGNAL PROCESSING: A BIT-SERIAL APPROACH', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1460, 1, 'SERIAL DATA COMPUTATION', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1461, 1, 'IRE TRANSACTIONS ELECTRONIC COMPUTING', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1462, 1, 'COMPUTER ARITHMETIC', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1463, 1, 'COMPUTER ARITHMETIC, VOLUME II', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1464, 1, 'VLSI SIGNAL PROCESSING SYSTEMS', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1466, 1, 'SOIL SCIENCE', '', '', '0038-075X', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1467, 1, 'ALUMINUM-LITHIUM ALLOYS: PROCEEDINGS OF THE FIRST INTERNATIONAL ALUMINUM-LITHIUF', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1468, 1, 'TRANSACTIONS OF AMERICAN SOCIETY OF CIVIL ENGINEERS', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1469, 1, 'TRANSACTIONS OF THE ASAE', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1470, 1, 'JOURNAL OF BIOTECHNOLOGY', 'J Biotechnol', '', '0168-1656 (e) 1873-4863', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1471, 1, 'BIOCHEMICAL ENGINEERING JOURNAL', 'Biochem. Eng. J.', 'Amsterdam, NL : Elsevier', '1369703X', NULL, 'Bimestral', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1472, 1, 'JOURNAL OF NEW MATERIALS FOR ELECTROCHEMICAL SYSTEMS', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1473, 1, 'JOURNAL OF VACUUM SCIENCE AND TECHNOLOGY', 'J Vac Sci Tech', '', '0022-5355', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1474, 1, 'KIDNEY INTERNATIONAL. SUPPLEMENT', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1475, 1, 'COMMUNICATIONS IN MATHEMATICAL PHYSICS', 'Commun. Math. Phys.', 'New York, US : Springer Verlag', '00103616', NULL, 'Monthly // Bimensal', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1476, 1, 'LETTERS IN MATHEMATICAL PHYSICS', 'Lett Math Phys', '', '0377-9017 e:1573-0530', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1477, 1, 'SURFACE SCIENCE REPORTS', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1478, 1, 'DOKLADY AKADEMII NAUK SSSR', 'Dokl. Akad. Nauk SSSR', 'Leningrad : Izd-vo Akademii nauk SSSR, 1933-1992', '00023264', NULL, 'Six no. a year, 1933; 3 times a month, 1934-1992', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1479, 1, 'GIESSENREIFORSCHUNG', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1480, 1, 'CHEMISTRY IN BRITAIN', 'Chem. Brit.', 'London, Chemical Education Trust Fund for the Chemical Society and the Royal Institute of Chemistry', '00093106', NULL, 'Mensual', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1481, 1, 'JOURNAL OF APPLIED ECOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1482, 1, 'INTERNATIONAL JOURNAL PHARMACEUTICS', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1483, 1, 'KINETICS AND MECHANISM', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1484, 1, 'APPLIED CLAY SCIENCE', 'Appl. Clay Sci.', 'Amsterdam : Elsevier, 1985-', '01691317', NULL, 'Trimestral', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1485, 1, 'MEMORIAS DO INSTITUTO OSWALDO CRUZ', 'Mem. Inst. Oswaldo Cruz', 'Rio De Janeiro, RJ : Fundacao Oswaldo Cruz', '00740276', NULL, 'Trimestral', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1486, 1, 'AMERICAN JOURNAL OF EMERGENCY MEDICINE', 'Am J Emerg Med', '[Philadelphia, PA. : Centrum Philadelphia, c1983- // Philadelphia, Pa., US : W.B. Saunders', '07356757', NULL, 'Bimonthly', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1487, 1, 'ALDRICHIMICA ACTA', '', 'Milwaukee, Wis., US : Aldrich Chemical', '00025100', NULL, 'Irregular', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1488, 1, 'ADVANCES IN PHYSICAL ORGANIC CHEMISTRY', 'Adv. phys. org. chem', 'London, New York, Academic Press', '00653160', NULL, 'Anual', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1489, 1, 'BIOLOGICAL CYBERNETICS', 'Biol. Cybern.', 'Berlin ; New York : Springer, 1975-', '03401200', NULL, 'Monthly', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1490, 1, 'JOURNAL OF MATHEMATICAL BIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1491, 1, 'COMPENDIUM ON CONTINUING EDUCATION FOR THE PRACTICING VETERINARIAN', 'Compend. Contin. Educ. Pract. Vet.', 'Princeton, Nj, US : Veterinary Learning Systems', '01931903', NULL, 'Mensal', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1492, 1, 'AGRI-PRACTICE', '', 'Santa Barbara, Calif., US : Veterinary Practice Publishing', '0745452X', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1493, 1, 'CLAY MINERALS', 'Clay Miner.', 'London : Mineralogical Society Of Great Britain, 1965-', '00098558', NULL, 'Semiannual 1965-1975; quarterly 1976-', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1494, 1, 'POWDER DIFFRACTION', '', '', '0885-7156', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1495, 1, 'PROCEEDINGS OF THE INTERNATIONAL POWER SOURCES SYMPOSIUM. 28TH', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1496, 1, 'ASTERISQUE 66-67', '', 'Paris, FR : Societe Mathematique De France', '03031179', NULL, 'Mensal', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1497, 1, 'MICROPOROUS AND MESOPOROUS MATERIALS', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1498, 1, 'INTERNATIONAL ARCHIVES OF OCCUPATIONAL AND ENVIRONMENTAL HEALTH', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1499, 1, 'PSYCHOLOGY TODAY', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1500, 1, 'ULTRASTRUCTURE PROCESSING OF CERAMICS, GLASSES, AND COMPOSITES', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1501, 1, 'HETEROPOLY AND ISOPOLY OXOMETALATES', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1502, 1, 'JOURNAL OF COMPUTATIONAL BIOLOGY', 'J Comput Biol', '', '1066-5277', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1503, 1, 'NATURE BIOTECHNOLOGY', '', '', '1087-0156', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1504, 1, 'TAXON', '', '', '0040-0262', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1505, 1, 'SURFACE COATINGS AUSTRALIA', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1506, 1, 'PRODUCT FINISHING', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1507, 1, 'INFO CHIMIE MAGAZINE', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:08'),
(1508, 1, 'INTERFINISH 96', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1509, 1, 'INDUSTRIAL ENVIRONMENTAL MANAGEMENT', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1510, 1, 'JOURNAL OF FERMENTATION TECHNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1511, 1, 'JOURNAL OF SOIL CONTAMINATION', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1512, 1, 'REVISTA DA FACULDADE DE FARMACIA E BIOQUIMICA DA UNIVERSIDADE DE SAO PAULO', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1513, 1, 'EUROPEAN CHEMICAL NEWS', 'Eur. Chem. News', 'Surrey, Inglaterra, GB : Reed Business Publishing', '00142875', NULL, 'Weekly', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1514, 1, 'JOURNAL OF CLINICAL ENDOCRINOLOGY AND METABOLISM', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1515, 1, 'JOURNAL OF VETERINARY DIAGNOSTIC INVESTIGATION', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1516, 1, 'INDUSTRIE LACKIER BETRIEB', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1517, 1, 'CLINICAL PHARMACOLOGY AND THERAPEUTICS', 'Clin. Pharmacol. Ther.', 'St. Louis : Mosby , cop1960 // American Society For Pharmacology And Experimental Therapeutics', '00099236', NULL, 'Mensual', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1518, 1, 'OFFICIAL GAZETTE OF THE UNITED STATES PATENT AND TRADEMARK OFFICE: PATENTS', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1519, 1, 'PAINTINDIA', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1520, 1, 'SCRIPTA METALLURGICA ET MATERIALIA', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1521, 1, 'PROBABILITY THEORY AND RELATED FIELDS', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1522, 1, 'ARCHIVES INTERNATIONALES DE PHARMACODYNAMIE ET DE THERAPIE', 'Arch. int. pharmacodyn. ther.', 'Ghent, Belgica, BE : Heymans Institute Of Pharmacology // Gand : H. Engelcke ; Paris : O. Doin, 1899', '00039780', NULL, 'Monthly', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1523, 1, 'JOURNAL OF EVOLUTIONARY BIOLOGY', '', '', '1420-9101', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1524, 1, 'LA LOGIQUE DU VIVANT; UNE HISTOIRE DE L\'HEREDITE', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1525, 1, 'THE FERMENT OF KNOWLEDGE', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1526, 1, 'LA PHYSIOLOGIE DES LUMIERES', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1527, 1, 'BIOCHEMICAL MEDICINE', 'Biochem. Med.', 'Orlando, Fla., US // San Diego [etc.] Academic Press', '00062944', NULL, 'Bimonthly, Feb. 1970-Dec. 1985', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1528, 1, 'POWER INDUSTRY COMPUTER APPLICATIONS CONFERENCE', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1529, 1, 'INTERNATIONAL STATISTICAL REVIEW', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1530, 1, 'AMERICAN MATHEMATICAL SOCIETY TRANSLATIONS', 'Transl. - Am. Math. Soc', 'Providence [etc.] : American Mathematical Society, 1949-', '00659290', NULL, 'Irregular', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1531, 1, 'JOURNAL OF ETHNOBIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1532, 1, 'MOLECULAR AND BIOLOGICAL EVOLUTION', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1533, 1, 'SYSTEMATIC ZOOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1534, 1, 'JOURNAL OF THE CHEMICAL SOCIETY. A. INORGANIC, PHYSICAL, THEORETICAL', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1535, 1, 'JOURNAL OF IMMUNOLOGICAL METHODS', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1536, 1, 'ANALYTICAL SCIENCES', '', 'Tokyo, JP : Japan Society For Analytical Chemistry', '09106340', NULL, 'Bimestral', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1537, 1, 'HEMATOLOGY, ONCOLOGY CLINICS OF NORTH AMERICA', 'Hematol. Oncol. Clin. Nort Am.', 'Philadelphia, Pa., US : W.B. Saunders, c1987-', '08898588', NULL, 'Trimestral', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1538, 1, 'IEEE POWER ENGINEERING SOCIETY CONFERENCE PAPERS FROM THE SUMMER MEETING', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1539, 1, 'NATURE STRUCTURAL BIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1540, 1, 'BIOCHEMICAL SYSTEMATICS AND ECOLOGY', 'Biochem. Syst. Ecol.', 'Oxford ; New York, N.Y. : Pergamon Press, 1974-', '03051978', NULL, 'Bimonthly', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1541, 1, 'PULSATIONS IN CLASSICAL AND CATACLYSMIC VARIABLE STARS', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1542, 1, 'WHITE DWARFS: ADVANCES IN OBSERVATION AND THEORY', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1543, 1, 'IAU COLLOQUIUM', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1544, 1, 'FORTSCHRITTE DER PHYSIK', 'Fortschr. Phys.', 'Berlin, DE : Kunts & Wissen Erich Bieber // Akademie Verlag, 1953-', '00158208', NULL, 'Monthly', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1545, 1, 'SCANDINAVIAN JOURNAL OF INFECTIOUS DISEASES', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1546, 1, 'INFECTION', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1547, 1, 'PHYSICS OF THE SOLID STATE', '', '', '1063-7834', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1548, 1, 'IMMUNOLOGY TODAY', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1549, 1, 'NATURE NEW BIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1550, 1, 'JOURNAL OF FORENSIC SCIENCES', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1551, 1, 'INORGANIC AND NUCLEAR CHEMISTRY LETTERS', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1552, 1, 'GLUTATHIONE: METABOLISM AND PHYSIOLOGICAL FUCTIONS', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1553, 1, 'CIRCULATORY SHOCK', 'Circ. Shock', 'Baltimore, University Park Press // New York, US : Wiley-Liss', '00926213', NULL, 'Mensal', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1554, 1, 'JOURNAL OF MOLECULAR EVOLUTION', 'J. Mol. Evol.', 'Berlin ; New York: Springer International, 1971-', '00222844', NULL, 'Six issues yearly <1983->', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1555, 1, 'AMERICAN ZOOLOGIST', 'Am. zool.', '[McLean, VA] : Society for Integrative and Comparative Biology [etc.], 1961-2002 // Thousand Oaks, C', '00031569', NULL, 'Quarterly, -1990 ; 6 no. a year, 1991-2001', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1556, 1, 'JOURNAL OF CELLULAR PHYSIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1557, 1, 'LABORATORY ANIMALS', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1558, 1, 'JOURNAL OF VETERINARY INTERNATIONAL MEDICINE', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1559, 1, 'ORGANOMETALLIC SYNTHESES', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1560, 1, 'MICROGRAPHIA', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1561, 1, 'METAPHYSICS AND THE PHILOSOPHY OF SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1562, 1, 'JOURNAL OF IMAGING SCIENCE AND TECHNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1563, 1, 'ZHURNAL STRUKTURNOI KHIMII. ENGLISH', '', '', '', NULL, '', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1564, 1, 'AMERICAN ANTIQUITY', 'Am. antiq', 'Menasha, Wisconsin : The Society for American Archaeology, 1935- //Washington : Society for American', '00027316', NULL, 'Quarterly', '2015-09-01 14:24:49', '2016-01-12 14:16:09'),
(1565, 1, 'ANIMAL BRUCELLOSIS', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1566, 1, 'JOURNAL OF THE SOCIETY FOR INDUSTRIAL AND APPLIED MATHEMATICS', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1567, 1, 'IL NUOVO CIMENTO DELLA SOCIETA ITALIANA DE FISICA. D,', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1568, 1, 'ARCHIVES OF VIROLOGY', 'Arch. Virol.', 'Wien, New York, Springer-Verlag', '03048608', NULL, 'Bimestral', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1569, 1, 'HYPERTENSION', 'Hypertension', 'Dallas, Tex. : American Heart Association, c1979-', '0194911X', NULL, 'Monthly <, Jan. 1986->', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1570, 1, 'EUROPEAN JOURNAL OF DERMATOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1571, 1, 'JOURNAL OF COMPUTATIONAL AND GRAPHICAL STATISTICS', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1572, 1, 'PHYTOPATHOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1573, 1, 'INTERNATIONAL JOURNAL OF LEGAL MEDICINE', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1574, 1, 'NASA AEROSPACE BATTERY WORKSHOP', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1575, 1, 'INTERNATIONAL RECHARGEABLE BATTERY SEMINAR (3RD: 1990)', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1576, 1, 'AIAA/UTAH STATE UNIVERSITY CONFERENCE ON SMALL SATELLITES', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1577, 1, 'ANNALES DE L\'I.H.P. PHYSIQUE THEORIQUE', 'Ann. I.H.P. Phys. théor.', 'Paris : Gauthier-Villars, c1983-c1999', '0246-0211', NULL, 'Eight no. a year', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1578, 1, 'THEORETICAL AND MATHEMATICAL PHYSICS', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1579, 1, 'TROPICAL AND GEOGRAPHICAL MEDICINE', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1580, 1, 'MOLECULAR MEDICINE', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1581, 1, 'MATHEMATICAL RESEARCH LETTERS', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1582, 1, 'GEOLOGY [GEOLOGICAL SOCIETY OF AMERICA]', 'Geology', 'Boulder, Colo.: Geological Society of America, 1973-', '00917613', NULL, 'Mensal', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1583, 1, 'ENDOCRINE REVIEWS', 'Endocr. Rev.', 'Bethesda, MD, USA : Endocrine Society , cop1980', '0163769X', NULL, 'Quarterly', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1584, 1, 'CONTRIBUTIONS TO MINERALOGY AND PETROLOGY', 'Contrib. Mineral. Petrol.', 'Berlin : Springer Verlag, 1966-', '00107999', NULL, 'Irregular', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1585, 1, 'JAPANESE JOURNAL OF VETERINARY RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1586, 1, 'JOURNAL OF CLINICAL ENDOCRINOLOLOGY AND METABOLISM', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1587, 1, 'CRITICAL REVIEWS IN FOOD SCIENCE AND NUTRITION', 'Crit. Rev. Food Sci. Nutr.', 'Boca Raton, Fla., US : Crc Press', '10408398', NULL, 'Bimestral', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1588, 1, 'ARCHIVES OF PATHOLOGY AND LABORATORY MEDICINE', 'Arch. Pathol. Lab. Med.', 'Chicago, Ill., US : American Medical Association', '00039985', NULL, 'Monthly', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1589, 1, 'ACTA NEUROPATHOLOGICA', 'Acta neuropathol.', 'Berlin, Springer-Verlag, 1961-  // New York, US : Springer Verlag', '00016322', NULL, 'Irregular // Quadrimestral', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1590, 1, 'PARASITE IMMUNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1591, 1, 'OXIDATION OF METALS', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1592, 1, 'SUPERCONDUCTOR SCIENCE AND TECHNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1593, 1, 'JOURNAL OF HISTOCHEMISTRY AND CYTOCHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1594, 1, 'ACH MODELS IN CHEMISTRY', 'Ach Models Chem.', 'Budapest, HU : Akademiai Kiado', '12178969', NULL, 'Bimestral', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1595, 1, 'THEORETICAL AND APPLIED GENETICS', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1596, 1, 'BULLETIN OF VOLCANOLOGY', 'Bull. Volcanol.', 'Berlin, DE : Springer Verlag', '02588900', NULL, 'Six issues yearly', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1597, 1, 'SCIENCE EDUCATION', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1598, 1, 'INTERNATIONAL JOURNAL OF ENVIRONMENTAL ANALYTICAL CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1599, 1, 'IMMOBILIZED ENZYMES AND CELLS. B', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1600, 1, 'EUROPEAN JOURNAL OF MEDICAL RESEARCH', 'Eur J Med Res', '', '2047-783X', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1601, 1, 'AMERICAN JOURNAL OF ANATOMY', 'Am J Anat', 'Baltimore, MD : [s.n.], 1902-1991', '00029106', NULL, 'Bi-monthly, Nov. 1910-<Nov. 1947> /  Quarterly (irregular), Nov. 1901-Oct. 1910 / Monthly, <1979>-19', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1602, 1, 'INDIAN JOURNAL OF PHYSICS. B', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1603, 1, 'ACTA PHARMACEUTICA', 'Acta Pharm.', 'Zagreb, Iugoslavia, YU : Federation Of Yugoslav Pharmaceutical Association', '13300075', NULL, 'Trimestral', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1604, 1, 'HEREDITY', 'Heredity', 'Essex, Inglaterra, GB : Genetical Society Of Great Britain // London : Oliver and Boyd, 1947-', '0018067X', NULL, 'Three no. a year, 1947- ; Bimonthly, <1989-> ; Monthly, <Aug. 1992->', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1605, 1, 'VETERINARY IMMUNOLOGY AND IMMUNOPATHOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1606, 1, 'PUBLIC HEALTH', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1607, 1, 'THE CLEFT PALATE-CRANIOFACIAL JOURNAL', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1608, 1, 'IEEE SIGNAL PROCESSING MAGAZINE', 'Vol. 8, no. 1 (Jan. 1991)-', 'New York, N.Y. : Institute of Electrical & Electronic Engineers', '10535888', NULL, 'Quarterly', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1609, 1, 'INTERNATIONAL JOURNAL OF PATTERN RECOGNITION AND ARTIFICIAL INTELIGENCE', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1610, 1, 'REVUE GENERALE DES ROUTES ET DES AERODROMES', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1611, 1, 'MEMOIRES SCIENTIFIQUES DE LA REVUE DE METALLURGIE', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1612, 1, 'COMPREHENSIVE ORGANIC SYNTHESIS', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1613, 1, 'MOLECULAR PHYLOGENETICS AND EVOLUTION', '', '', '1055-7903', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1614, 1, 'JOURNAL OF LIGHTWAVE TECHNOLOGY', '', 'New York, US : Institute Of Electrical And Electronics Engineers', '07338724', NULL, 'Quarterly // Mensal', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1615, 1, 'ANALYTICAL PROCEEDINGS', 'Anal. proc.', '[London] : Chemical Society, c1980-c1995', '0144557X', NULL, 'Monthly', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1616, 1, 'ELECTRONICAL PROPIERTIES OF OXIDE MATERIALS', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1617, 1, 'SEED SCIENCE RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1618, 1, 'BIOCATALYSIS AND BIOTRANSFORMATION', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1619, 1, 'ORGANIC COATINGS FOR CORROSION CONTROL', '', 'BIERWAGEN, G. ED.', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1620, 1, 'INTERNATIONAL REVIEWS OF IMMUNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1621, 1, 'GALVANO-ORGANO', '', 'Paris, FR : [S.N.]', '03026477', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1622, 1, 'BRE INFORMATION PAPER', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1623, 1, 'CESKA SLOVENSKA FARMACIE: CASOPIS CESKE FARMACEUTICJE SPOLECNOSTI A SLONENSKE', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1624, 1, 'FARMACJA POLSKA', 'Farm. Pol.', 'Warszawa, PL : Polskie Towarzystwo Farmaceutyczne', '00148261', NULL, 'Bimensal', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1625, 1, 'PHARMACEUTISCH WEEKBLAD', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1626, 1, 'JOURNAL OF THE JAPAN SOCIETY OF COLOUR MATERIAL', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1627, 1, 'INTERNATIONAL JOURNAL OF CANCER', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1628, 1, 'SURGERY', 'SURGERY', '', '0039-6060 (Prin', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1629, 1, 'CONFERENCE ON APPLICATION X-RAY ANALYSIS', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1630, 1, 'TOPICS IN CATALYSIS', '', '', '1022-5528', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1631, 1, 'IZVESTIIA SIBIRSKOGO OTDELENIIA AKADEMII NAUK SSSR', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1632, 1, 'CORROSION AND MATERIALS', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1633, 1, 'CORROSION MANAGEMENT', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1634, 1, 'INTERNATIONAL CONFERENCE ON COSMIC RAYS', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1635, 1, 'JOURNAL OF PHARMACEUTICAL AND BIOMEDICAL ANALYSIS', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1636, 1, 'ZEITSCHRIFT FUR NATURFORSCHUNG. A. JOURNAL OF PHYSICAL SCIENCES', '', '', '0932-0784', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1637, 1, 'MEDICAL TOXICOLOGY AND ADVERSE DRUG EXPERIENCE', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1638, 1, 'OBERFLACHE JOT', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1639, 1, 'CANCER', 'Cancer', 'Philadelphia [etc.] Lippincott // American Cancer Society , 1948', '0008543X', NULL, 'Dos veces al mes // Semestral', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1640, 1, 'AUSTRALIAN AND NEW ZEALAND JOURNAL OF SURGERY', 'Aust. N. Z. J. Surg.', 'Sydney, Australia, AU : Blackwell Scientific Publications', '0004-8682', NULL, 'Trimestral', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1641, 1, 'JOURNAL OF PATHOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1642, 1, 'DISEASE MARKERS', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1643, 1, 'MOLECULAR ECOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1644, 1, 'ACTA ANATOMICA', 'Acta Anat. (Basel)', 'Basel ; New York : S. Karger', '00015180', NULL, 'Frecuencia Actual : Mensual', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1645, 1, 'JOURNAL OF PLANT BIOCHEMISTRY AND BIOTECHNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1646, 1, 'BOLLETTINO CHIMICO FARMACEUTICO', 'Boll. Chim. Farm.', 'Milan, Italia, IT : Societa Editoriale Farmaceutica', '00066648', NULL, 'Mensal', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1647, 1, 'JOURNAL OF ANNALYTICAL ATOMIC SPECTROMETRY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1648, 1, 'JOURNAL OF MICROCOLUMN SEPARATIONS', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1649, 1, 'PLANT FOODS FOR HUMAN NUTRITION: [ONLINE]', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1650, 1, 'MICROBIOLOGY AND MOLECULAR BIOLOGY REVIEWS: MMBR', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1651, 1, 'TRENDS IN MICROBIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1652, 1, 'VIRUS INFECTIONS OF CARNIVORES', '', 'APPLEL, MAX J. ED.', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1653, 1, 'RUSSIAN JOURNAL OF GENERAL CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1654, 1, 'JOURNAL OF MEDICAL VIROLOGY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1655, 1, 'ARCHIVES DE PEDIATRIE', 'Arch. Pediatr.', 'Paris, FR : Scientifiques Elsevier', '0929-693X', NULL, 'Mensal', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1656, 1, 'MEMORIE DELLA PONTIFICIA ACCADEMIA ROMANA DEI NUOVO LINCEI', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1657, 1, 'JAPANESE JOURNAL OF ZOOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2015-09-07 15:07:23'),
(1658, 1, 'JOURNAL OF VOLCANOLOGY AND GEOTHERMAL RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1659, 1, 'AMERICAN JOURNAL OF SOCIOLOGY', 'Am. J. Sociol.', 'Chicago [etc.] : University of Chicago Press , 1895', '00029602', NULL, 'Bimestral', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1660, 1, 'TRANSACTIONS OF THE ROYAL SOCIETY OF SOUTH AUSTRALIA', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1661, 1, 'JOURNAL OF ENDOUROLOGY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1662, 1, 'PLATING', '', '', '0032-1397', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1663, 1, 'CANADIAN JOURNAL OF ANALYTICAL SCIENCES AND SPECTROSCOPY', 'Can. J. Anal. Sci. Spectrosc.', 'Quebec, Canada, CA : Polyscience Publications', '12056685', NULL, 'Vol. 42, No. 1 (1997)-', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1664, 1, 'PUBLICATIONS OF THE INSTITUTE OF MARINE SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1665, 1, 'MATERIALS SCIENCE FORUM', 'Mater. Sci. Forum', 'Aedermannsdorf, Switzerland : Trans Tech Publications, 1984-', '02555476', NULL, 'Two vols. yearly, 1984 ; 4 vols. yearly, 1985-', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1666, 1, 'INTERNATIONAL JOURNAL OF MODERN PHYSICS. C, PHYSICS AND COMPUTERS', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1667, 1, 'ANALYTICAL LETTERS', 'Anal Lett', '[New York] : M. Dekker, 1967-', '0003-2719 (e): 1532-236X', NULL, 'Frequency varies', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1668, 1, 'JOURNAL OF THE ROYAL STATISTICAL SOCIETY. C. APPLIED STATISTICS', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1670, 1, 'JOURNAL OF THE ATMOSPHERIC SCIENCES', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1671, 1, 'ATMOSPHERIC ENVIRONMENT (1967)', 'Atmos. environ.', 'Elmsford, Ny, US  // Oxford, New York [etc.] : Pergamon Press, 1967-1989', '00046981', NULL, 'Bimonthly, Jan. 1967- ; Monthly <, Dec. 1974, Mar. 1975->', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1672, 1, 'SYMBIOSIS', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1673, 1, 'NEW ZEALAND JOURNAL OF BOTANY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1674, 1, 'MEDICAL HYPOTHESES', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1675, 1, 'TRENDS IN ANALYTICAL CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1676, 1, 'JOURNAL OF PHASE EQUILIBRIA', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1677, 1, 'EMBO JOURNAL', 'EMBO J.', 'Oxford : Published for the European Molecular Biology Organization by IRL Press, [c1982-', '02614189', NULL, 'Monthly // Quincenal', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1678, 1, 'MYCORRHIZA', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1679, 1, 'PLANT, CELL AND ENVIRONMENT', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1680, 1, 'PROGRESS IN BIOORGANIC CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1681, 1, 'INTERNATIONAL JOURNAL OF RADIATION BIOLOGY AND RELATED STUDIES IN PHYSICS', '', '', '3', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1682, 1, 'CAST METALS', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1683, 1, 'SCIENCE PROGRESS', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1684, 1, 'JOURNAL OF THE OIL & COLOUR CHEMISTS ASSOCIATION', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1685, 1, 'ROCKS AND MINERALS', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1686, 1, 'CANADIAN MINERALOGIST', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1687, 1, 'JOURNAL OF METAMORPHIC GEOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1688, 1, 'SOUTHWEST NATURALIS', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1689, 1, 'JOURNAL OF BONE AND JOINT SURGERY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1690, 1, 'JOURNAL OF ORAL AND MAXILLOFACIAL SURGERY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1691, 1, 'JOURNAL OF THE NATIONAL CANCER INSTITUTE', 'JNCI J Natl Cancer Inst', '', 'print ISSN: 0027-8874, online ISSN: 1460-2105', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1692, 1, 'CANCER CAUSES & CONTROL', '', 'Oxford, UK : Rapid Communications of Oxford Ltd., 1990-', '09575243', NULL, 'Bimonthly', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1693, 1, 'JOURNAL OF THE NATIONAL MEDICAL ASSOCIATION', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1694, 1, 'NATIONAL CANCER INSTITUTE MONOGRAPH', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1695, 1, 'AMERICAN JOURNAL OF EPIDEMIOLOGY', 'Am J Epidemiol', 'Baltimore, Md., US : Johns Hopkins University, School Of Hygiene And Public Health', '00029262', NULL, 'Mensal', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1696, 1, 'MEDICAL JOURNAL OF AUSTRALIA', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09');
INSERT INTO `journal` (`id`, `instance_id`, `name`, `abbreviation`, `responsible`, `ISSN`, `ISSNE`, `frecuency`, `createdAt`, `updatedAt`) VALUES
(1697, 1, 'BRITISH JOURNAL OF PREVENTIVE & SOCIAL MEDICINE', 'Br. J. Prev. Soc. Med.', 'London, GB : British Medical Association', '00071242', NULL, 'Quarterly', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1698, 1, 'DEVELOPMENTAL MEDICINE AND CHILD NEUROLOGY', 'Dev. Med. Child. Neurol.', 'London, Spastics International Medical Publications // Mac Keith Press', '00121622', NULL, 'Bimestral', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1699, 1, 'INTERNATIONAL JOURNAL OF ADULT ORTHODONTICS AND ORTHOGNATHIC SURGERY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1700, 1, 'EARTH AND PLANETARY SCIENCE LETTERS', 'Earth Planet. Sci. Lett.', 'Amsterdam, North-Holland Pub. Co., 1966- // Elsevier Scientific Publishing', '0012821X', NULL, 'Bimonthly, 1966-; Monthly <, Sept. 1976->', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1701, 1, 'INFORMATION PROCESSING LETTERS', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1702, 1, 'JOURNAL OF THE ASSOCIATION OF OFFICIAL AGRICULTURAL CHEMISTS', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1703, 1, 'AFS INTERNATIONAL CAST METALS JOURNAL', '', 'Des Plaines, Ill., US : American Foundrymen´S Society', '03621723', NULL, 'Trimestral', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1704, 1, 'CARCINOGENESIS', 'Carcinogenesis', 'New York, IRL Press // London [etc.] : Oxford University Press', '01433334', NULL, 'Mensual', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1705, 1, 'PHYSICS OF PLASMAS', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1706, 1, 'JOURNAL OF GENERAL CHEMISTRY OF THE USSR', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1707, 1, 'ELECTROPHORESIS', 'Electrophoresis', 'Deerfield Beach, Alemanha, DE // Weinheim, Germany: Verlag Chemie, [1980-', '01730835', NULL, 'Irregular // Mensal', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1708, 1, 'PHYTOCHEMICAL ANALYSIS', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1709, 1, 'AMERICAN JOURNAL OF GASTROENTEROLOGY', 'Am J Gastroenterol', 'New York, US : American College Of Gastroenterology', '00029270', NULL, 'Mensal', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1710, 1, 'JOURNAL OF THE MARINE BIOLOGICAL ASSOCIATION OF THE UNITED KINGDOM', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1711, 1, 'JOURNAL OF FOOD PROTECTION', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1712, 1, 'ADVANCES IN SPACE RESEARCH', 'Adv. space res', '[Oxford ; New York : Published for the Committee on Space Research by Pergamon Press], c1981- // Oxf', '02731177', NULL, 'Mensal', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1713, 1, 'MINERALOGICAL MAGAZINE', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1714, 1, 'CANADIAN JOURNAL OF BOTANY', 'Can. J. Bot.', 'Ottawa, CA : National Research Council Of Canada, 1951-', '00084026', NULL, 'Monthly, 1951-1975 ; semimonthly, 1975-1980 ; monthly, 1981-', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1715, 1, 'NOTICES OF THE AMERICAN MATHEMATICAL SOCIETY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1716, 1, 'MINERALOGY AND PETROLOGY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1717, 1, 'JOURNAL OF GEOPHYSICAL RESEARCH. A. SPACE PHYSICS', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1718, 1, 'MODELING, IDENTIFICATION AND CONTROL: MIC', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1719, 1, 'NOTRE DAME JOURNAL OF FORMAL LOGIC', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1720, 1, 'JOURNAL OF BIOMATERIALS SCIENCE. POLYMER EDITION', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1721, 1, 'MYTH AND SOCIETY IN ANCIENT GREECE', '', 'VERNANT, JEAN PIERRE', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1722, 1, 'ADVANCES IN COLLOID AND INTERFACE SCIENCE', 'Adv. colloid interface sci.', 'Amsterdam : Elsevier Scientific Pub. Co. [etc.], 1967-', '00018686', NULL, 'Quarterly -1967 // frequency varies', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1723, 1, 'AMERICAN EDUCATIONAL RESEARCH JOURNAL', 'Am. educ. res. j.', '[Washington] American Educational Research Association, 1964', '00028312', NULL, 'Four no. a year', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1724, 1, 'REVUE D\'EPIDEMIOLOGIE ET DE SANTE PUBLIQUE', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1725, 1, 'JOURNAL OF THE INDIAN MEDICAL ASSOCIATION', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1726, 1, 'JOURNAL OF ALLERGY AND CLINICAL IMMUNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1727, 1, 'RECORDS OF THE INDIAN MUSEUM', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1728, 1, 'JOURNAL OF PHYSICAL OCEANOGRAPHY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1729, 1, 'GENE', 'Gene', 'Amsterdam, Elsevier/North-Holland, 1976-', '03781119', NULL, 'Bimestral. Mensual desde en. de 1979. Quincenal desde el 15 de jul. de 1992 // Bissemanal', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1730, 1, 'CLINICAL GENETICS', 'Clin. Genet.', 'Copenhagen, Munksgaard', '00099163', NULL, 'Monthly <, Mar. 1980->', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1731, 1, 'AMERICAN JOURNAL OF MEDICAL GENETICS', 'Am J Med Genet', 'New York : Wiley-Liss , 1977-', '01487299', NULL, 'Mensal', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1732, 1, 'HUMAN MOLECULAR GENETICS', 'Hum. Mol. Genet.', 'Oxford, England ; New York : IRL Press, c1992-', '09646906', NULL, ' Eight no. a year, plus annual author/subject index', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1733, 1, 'GENOMICS', 'Genomics', 'San Diego : Academic Press , cop1987', '08887543', NULL, 'Monthly (except Mar., June, Sept., and Dec.)', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1734, 1, 'HEREDITAS', 'Hereditas', 'Lund, Suecia, SE : Mendelian Society, Institute Of Genetics, 1920-', '00180661', NULL, 'Three no. a year 1920-37 ; Quarterly 1938- ; Two vol. a year (four issues)', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1735, 1, 'CHROMOSOMA', 'Chromosoma', 'Berlin : Springer International', '00095915', NULL, 'Quincenal. Mensual a partir de 1996', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1736, 1, 'PHILOSOPHICAL TRANSACTIONS OF THE ROYAL SOCIETY OF LONDON. SERIES B. BIOLOGICAL', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1737, 1, 'CLINICAL CHEMISTRY AND LABORATORY MEDICINE', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1738, 1, 'PHILOSOPHICAL REVIEW', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1739, 1, 'PATHOLOGY, RESEARCH AND PRACTICE', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1740, 1, 'GENERAL & DIAGNOSTIC PATHOLOGY', 'Gen. Diagn. Pathol.', 'Jena, Alemanha, DE : Gustav Fischer Verlag', '0947823X', NULL, 'Bimestral', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1741, 1, 'THEORIES OF THRUTH: A CRITICAL INTRODUCTION', '', 'KIRKHAM, R.L.', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1742, 1, 'ADVANCES IN FOOD SCIENCES', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1743, 1, 'ACTA AGRONOMICA HUNGARICA', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1744, 1, 'INTERNATIONAL JOURNAL OF EPIDEMIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1745, 1, 'EPIDEMIOLOGY', 'Epidemiology', 'Baltimore, Md., US : Williams & Wilkins // Cambridge, Mass. : Blackwell Scientific Publications ; Ch', '10443983', NULL, 'Bimonthly', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1746, 1, 'OCCUPATIONAL AND ENVIRONMENTAL MEDICINE', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1747, 1, 'JOURNAL OF THE AMERICAN COLLEGE OF CARDIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1748, 1, 'JOURNAL OF EPIDEMIOLOGY AND COMMUNITY HEALTH', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1749, 1, 'ENVIRONMENTAL RESEARCH', 'Environ. Res.', 'San Diego [etc.] Academic Press', '00139351', NULL, 'Bimonthly // Trimestral', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1750, 1, 'INTERNATIONAL JOURNAL OF MODERN PHYSICS. A, PARTICLES AND FIELDS, GRAVITATION, COSMOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1751, 1, 'PUBLIC HEALTH REVIEWS', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1752, 1, 'IARC SCIENTIFIC PUBLICATIONS', 'Iarc Sci. Publ.', 'Lyon, Franca, FR : International Agency For Research On Cancer', '03005038', NULL, 'Irregular', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1753, 1, 'JOURNAL OF OCCUPATIONAL MEDICINE', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1754, 1, 'JOURNAL OF MAMMALOGY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1755, 1, 'COLLECTED PAPERS', '', 'PEIRCE, C.S.', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1756, 1, 'TERATOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1757, 1, 'BRITISH JOURNAL OF INDUSTRIAL MEDICINE', 'Br. J. Ind. Med.', 'London, GB : British Medical Association', '00071072', NULL, 'Monthly, <Feb. 1988->', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1758, 1, 'JOURNAL OF THE SOCIETY OF INSTRUMENT AND CONTROL ENGINEERS', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1759, 1, 'ANNALS OF PHARMACOTHERAPY', 'Ann. Pharmacother.', 'Cincinnnati, OH : Harvey Whitney Books Co., 1992-', '10600280', NULL, 'Monthly (except July/Aug. combined)', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1760, 1, 'EUROPEAN JOURNAL OF CANCER. PART A', 'Eur. J. Cancer', 'Oxford ; New York : Pergamon Press, c1990-', '09641947', NULL, 'Monthly', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1761, 1, 'BRITISH JOURNAL OF UROLOGY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1762, 1, 'DIGESTIVE DISEASES AND SCIENCES', 'Dig. Dis. Sci.', 'New York : Plenum Press , cop1979', '01632116', NULL, 'Monthly', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1763, 1, 'REVIEWS OF GEOPHYSICS AND SPACE PHYSICS', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1764, 1, 'ULTRAMICROSCOPY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1765, 1, 'JOURNAL OF DAIRY RESEARCH', '', '', '1469-7629', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1766, 1, 'AMERICAN JOURNAL OF VETERINARY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1767, 1, 'NUCLEAR MEDICINE AND BIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1768, 1, 'MATERIALS CHARACTERIZATION', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1769, 1, 'BRITISH VETERINARY JOURNAL', 'Br. Vet. J.', 'London, GB : Bailliere Tindall', '00071935', NULL, 'Bimestral', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1770, 1, 'METAL SCIENCE AND HEAT TREATMENT', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1771, 1, 'MATERIALS TRANSACTIONS: JIM', 'JIM', '', '0916-1821', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1772, 1, 'ACTA MENDELOVY ZEMEDELSKE A LESNICKE UNIVERZITY V BRN', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1773, 1, 'APPLIED RADIATION AND ISOTOPES', 'Appl. Radiat. Isotopes', 'Oxford : Pergamon, 1986-', '08832889', NULL, 'Monthly', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1774, 1, 'GASTROENTEROLOGY', 'Gastroenterology', 'Baltimore : Williams and Wilkins , cop1943 // New York, US : Elsevier Science Publishers', '00165085', NULL, 'Mensual', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1775, 1, 'EXPERIMENTAL MYCOLOGY', 'Exp. Mycol.', 'New York, US : Academic Press', '01475975', NULL, 'Trimestral', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1776, 1, 'AMERICAN JOURNAL OF THE MEDICAL SCIENCES', 'Am. J. Med. Sci.', 'Philadelphia, Pa., US : J. B. Lippincott', '00029629', NULL, 'Bimestral', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1777, 1, 'ACTA VIROLOGICA', 'Acta virol', 'Prague, Czechoslovak Academy of Sciences // Bratislava, Tchecoslovaquia, CS : Publishing House Of Th', '0001723X', NULL, 'Bimestral', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1778, 1, 'MOLECULAR AND CELLULAR PROBES', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1779, 1, 'JOURNAL OF HIGH RESOLUTION CHROMATOGRAPHY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1780, 1, 'TREE PHYSIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1781, 1, 'MYCOPATHOLOGIA', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1782, 1, 'INTERNATIONAL CITRUS CONGRESS [1981:TOKYO,JAPAN]', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1783, 1, 'CATALYSIS. CONTRIBUTING AUTHORS', '', 'RISE, Herman E. ; et.al.', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1784, 1, 'ADVANCES IN CHEMISTRY SERIES: FUEL CELL SYSTEMS II: 5TH BIENNIAL FUEL SYMPOSIUM SPONSORED BY THE DIVISION FUEL CHEMISTRY AT THE 154TH MEETING OF THE AMERICAN CHEMICAL SOCIETY [CHICAGO, ILLINOIS, 1967', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1785, 1, 'JOURNAL OF CLINICAL PATHOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1786, 1, 'ANALYTICAL CHEMISTRY', 'Anal. Chem. (Wash.)', 'Washington : American Chemical Society, c1948-', '00032700', NULL, 'Monthly, 1948- ; 14 no. a year, <Nov. 1979-> ;  Semimonthly', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1787, 1, 'PHYLOSOPHICAL TRANSACTIONS OF THE ROYAL SOCIETY OF LONDON. SERIES B,', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1788, 1, 'VETERINARY RECORD: JOURNAL OF THE BRITISH VETERINARY ASSOCIATION', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1789, 1, 'JOURNAL OF THE EGYPTIAN SOCIETY OF PARASITOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1790, 1, 'IEEE TRANSACTIONS ON SIGNAL PROCESSING', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1791, 1, 'ENSEÑANZA DE LAS CIENCIAS', 'Ensen. Cienc', 'Barcelona : ICE de la Universidad Autónoma de Barcelona , 1983', '02124521', NULL, 'Quadrimestral', '2015-09-01 14:24:50', '2016-01-12 14:16:09'),
(1792, 1, 'TRANSACTIONS OF THE INSTITUTION OF CHEMICAL ENGINEERS', 'Trans Inst Chem Eng', '', '0046-9858', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1793, 1, 'AGRICULTURAL ENGINEERING', 'Agric. eng', 'St. Joseph, Mich., etc., American Society of Agricultural Engineers', '00021458', NULL, 'Thirteen issues a year <, June, 1984> // Bimestral', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1794, 1, 'WORLD REVIEW OF NUTRITION AND DIETETICS', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1795, 1, 'NUTRITION REPORTS INTERNATIONAL', 'Nutr Rep Int', '', '0029-6635', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1796, 1, 'REVUE D\'ECOLOGIE ET DE BIOLOGIE DU SOL', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1797, 1, 'PROCEEDINGS OF THE LONDON MATHEMATICAL SOCIETY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1798, 1, 'CANADIAN GEOTECHNICAL JOURNAL', 'Can. Geotech. J.', 'Ottawa, CA : National Research Council Of Canada, 1963-', '00083674', NULL, 'Trimestral', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1799, 1, 'GROUND WATER MONITORING REVIEW', 'Ground Water Monit. Rev.', '[Worthington, OH : Water Well Journal Pub. Co., 1981-c1992', '02771926', NULL, 'Quarterly', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1800, 1, 'GEOPHYSICS', 'Geophysics', 'Houston, Tex., US // Tulsa, Okla. : Society of Exploration Geophysicists, 1936-', '00168033', NULL, 'Seven nos. a year -1978 ; monthly 1979-', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1801, 1, 'LOG ANALYST', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1802, 1, 'EOS', 'Eos Trans. AGU', 'Washington, etc.: American Geophysical Union', '0096-3941', NULL, 'Monthly, 1969-78; Weekly, 1979-', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1803, 1, 'INDAGATIONES MATHEMATICAE', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1804, 1, 'CANADIAN JOURNAL OF BIOCHEMISTRY AND CELL BIOLOGY = REVUE CANADIENNE DE BIOCHIMIE ET BIOLOGIE CELLULAIRE', 'Can. J. Biochem. Cell Biol.', 'Ottawa, CA : National Research Council Of Canada = Conseil national de recherches Canada, c1983-', '07147511', NULL, 'Monthly', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1805, 1, 'OBSERVATION AND THEORY IN SCIENCE', '', 'NAGEL, Ernest', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1806, 1, 'CAMBRIDGE COMPANION TO DESCARTES', '', 'Cambridge ; New York : Cambridge University Press, 1992', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1807, 1, 'BIOCHEMICAL MEDICINE AND METABOLIC BIOLOGY', 'Biochem. Med. Metabol. Biol.', 'Orlando, Fla., US // San Diego : Academic Press,', '08854505', NULL, 'Bimestral', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1808, 1, 'JOURNAL OF LIPID RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1809, 1, 'JOURNAL OF NUTRITIONAL SCIENCE AND VITAMINOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1810, 1, 'COMPARATIVE ANIMAL NUTRITION', 'Comp. Anim. Nutr.', 'Basel, Suica, CH : S. Karger', '03045374', NULL, 'Irregular', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1811, 1, 'ATHEROSCLEROSIS', 'Atherosclerosis(Amst.)', 'Limerick, Irlanda, IE // Amsterdam: Elsevier Scientific Publishers Ireland', '00219150', NULL, 'Monthly', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1812, 1, 'LLOYDIA', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1813, 1, 'TRANSACTIONS OF THE IRON AND STEEL INSTITUTE OF JAPAN', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1814, 1, 'DIFFERENTIATION', '', 'Berlin, DE : Springer Verlag', '03014681', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1815, 1, 'LOGIQUE DU VIVANT: ENGLISH', '', 'JACOB, FRANCOIS', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1816, 1, 'IEE PROCEEDINGS. CIRCUITS, DEVICES AND SYSTEMS', '', 'London, GB : Institution Of Electrical Engineers // Stevenage, Herts UK : Société Linnéene de Lyon ,', '13502409', NULL, 'Bimestral', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1817, 1, 'JOURNAL OF AIRCRAFT', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1818, 1, 'AMERICAN JOURNAL OF HYGIENE', 'Am. j. hyg', 'Baltimore : Published by School of Hygiene and Public Health, Johns Hopkins University through the J', '00965294', NULL, 'Bimonthly', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1819, 1, 'JOURNAL OF PLANT PHYSIOLOGY', 'J Plant Physiol', '', '0176-1617', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1820, 1, 'PYRETHROID INSECTICIDES', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1821, 1, 'JOURNAL OF FAMILY HISTORY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1822, 1, 'INVESTIGACION EN LA ESCUELA', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1823, 1, 'JOURNAL OF PAINT TECHNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1824, 1, 'INTERNATIONAL JOURNAL OF BIOLOGICAL MAGROMOLECULES', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1825, 1, 'RUSSIAN CHEMICAL REVIEWS', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1826, 1, 'PROCEEDINGS OF THE SME FLUIDS ENGINEERING DIVISION SUMMER MEETING', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1827, 1, 'TROPICAL MEDICINE AND INTERNATIONAL HEALTH', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1828, 1, 'AUSTRALIAN JOURNAL OF PLANT PHYSIOLOGY', 'Aust. J. Plant Physiol.', 'Melbourne : Commonwealth Scientific and Industrial Research Organization, 1974-', '03107841', NULL, 'Quarterly, Mar. 1974- ; 6 no. a year', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1829, 1, 'ACTA CYTOLOGICA', 'Acta Cytol.', 'Philadelphia [etc.] : Lippincott , cop1957 // Chicago, 1979 -', '00015547', NULL, 'Bimestral', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1830, 1, 'AMERICAN JOURNAL OF NEPHROLOGY', 'Am J Nephrol', 'Basel ; New York : Karger, 1981-', '02508095', NULL, 'Bimestral', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1831, 1, 'ATAS DA SOCIEDADE DE BIOLOGIA DO RIO DE JANEIRO', 'Atas Soc. Biol. Rio J.', 'Rio De Janeiro, RJ : Sociedade De Biologia Do Rio De Janeiro', '03650898', NULL, 'Anual', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1832, 1, 'U.S. PAPENT 3.726.794', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1833, 1, 'NATURAL PRODUCT LETTERS', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1834, 1, 'INTERNATIONAL JOURNAL OF PHARMACOGNOSY: A JOURNAL OF CRUDE DRUG RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1835, 1, 'LIBRARY HI TECH', '', '', '0737-8831', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1836, 1, 'ADVANCES IN APPLIED PROBABILITY', 'Adv. appl. probab.', 'Sheffield, England, Applied Probability Trust', '00018678', NULL, 'Semiannual, 1969-71; Quarterly <,Dec 197', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1837, 1, 'MATERIALS EVALUATION', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1838, 1, 'ENVIRONMENTAL MONITORING AND ASSESSMENT', 'Environ. Monit. Assess.', 'Dordrecht, Holland ; Boston : D. Reidel Pub. Co., c1981- // Kluwer Academic Publishers', '01676369', NULL, 'Four issues yearly', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1839, 1, 'JOURNAL OF THE AIR POLLUTION CONTROL ASSOCIATION', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1840, 1, 'JOURNAL OF HYDROLOGY', '', '', '0022-1694', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1841, 1, 'NUKLEONIKA', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1842, 1, 'ANNALS OF DISCRETE MATHEMATICS', 'Ann. Discrete Math.', 'Amsterdam, NL: North Holland Publishing', '01675060', NULL, 'Irregular', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1843, 1, 'HISTOCHEMICAL JOURNAL', 'Histochem. J.', 'London : Chapman and Hall, 1968-', '00182214', NULL, 'Monthly <, Jan. 1983->', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1844, 1, 'HISTOCHEMISTRY (1974)', '', 'Berlin, New York, Springer-Verlag', '03015564', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1845, 1, 'THEORY OF PROBABILITY AND ITS APPLICATIONS', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1846, 1, 'ANTONIE VAN LEEUWENHOEK: JOURNAL OF MICROBIOLOGY AND SEROLOGY', 'Antonie Van Leeuwenhoek', 'Dordrecht [etc.] Kluwer Academic Publishers [etc.] // Amsterdam, NL : Nederlandsche Vereeniging Voor', '00036072', NULL, 'Quarterly ;  Six no. a year', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1847, 1, 'BIOMETEOROLOGY', '', 'Amsterdam', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1848, 1, 'METALLURGICAL TRANSACTIONS', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1849, 1, 'METALLURGICAL AND MATERIALS TRANSACTIONS. A. PHYSICAL METALLURGY AND MATERIALS SCIENCE', 'Metall. mater. trans. A, Phys. metall. mater. sci.', '', '1073-5623', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1850, 1, 'ZEITSCHRIFT FUR ANORGANISCHE UND ALLGEMEINE CHEMIE', '', '', '', NULL, '', '2015-09-01 14:24:50', '2015-09-01 14:25:01'),
(1851, 1, 'THIN SOLID FILMS', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1852, 1, 'ARS', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1853, 1, 'GROUND ENGINEERING', 'Ground Eng.', 'Brentwood, Essex, Foundation Publications // Essex, Inglaterra, GB : Geo Publishing', '00174653', NULL, '8 times a year', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1854, 1, 'SOVIET PHYSICS: SEMICONDUCTORS', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1855, 1, 'JOURNAL OF THE KOREAN PHYSICAL SOCIETY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1856, 1, 'GENERAL PHYSIOLOGY AND BIOPHYSICS', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1857, 1, 'POLYMER JOURNAL', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1858, 1, 'DYNAMICAL SYSTEMS: THEORY AND APPLICATIONS', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1859, 1, 'JOURNAL OF LIBRARY AUTOMATION', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1860, 1, 'PROCEEDINGS OF THE ACADEMY OF NATURAL SCIENCES OF PHILADELPHIA', 'Proc Acad Nat Sci Phila', '', '0097-3157', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1861, 1, 'INVERTEBRATE BIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1862, 1, 'NASA REFERNCE PUBLICATION', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1863, 1, 'ZOOLOGICAL JOURNAL OF THE LINNEAN SOCIETY', 'Zool J Linn Soc', '', '0024-4082  (e): 1096-3642', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1864, 1, 'BIOLOGICAL JOURNAL OF THE LINNEAN SOCIETY', '', '[London] : Academic Press, 1969-', '00244066', NULL, 'Monthly (except Jan., Apr., July, and Oct.)', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1865, 1, 'NEW LEFT REVIEW', '', '', '0028-6060', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1866, 1, 'SCIENCE OF THE TOTAL ENVIRONMENT', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1867, 1, 'SABOURAUDIA', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1868, 1, 'JOURNAL OF FOOD PROCESS ENGINEERING', '', '', '0145-8876', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1869, 1, 'HISTOCHEMISTRY AND CELL BIOLOGY', 'Histochem. Cell Biol.', 'Berling; Heidelberg : Springer , 1995-', '09486143', NULL, 'Irregular', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1870, 1, 'ACTA ZOOLOGICA', '', 'Stockholm, Swedish Natural Science Research Council [etc.] // Stockholm, SE : International Union Bi', '00017272', NULL, 'Three no. a year', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1871, 1, 'ANNOTATIONES ZOOLOGICAE JAPONENSES', 'Annot. Zool. Jpn.', 'Tokyo, JP : Zoological Society Of Japan', '00035092', NULL, 'Trimestral', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1872, 1, 'ACTA HISTOCHEMICA:  A JOURNAL OF STRUCTURAL BIOCHEMISTRY', 'Acta Histochen.', 'Jena, Alemanha, DE : Fischer', '00651281', NULL, 'Trimestral', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1873, 1, 'ACTA EMBRYOLOGIAE ET MORPHOLOGIAE EXPERIMENTALIS', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1874, 1, 'APPLIED MICROBIOLOGY AND BIOTEFEMS', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1875, 1, 'HUMAN NUTRITION. CLINICAL NUTRITION', 'Hum. Nutr., Clin. Nutr.', 'London : J. Libbey, 1982-[c1982]', '02638290', NULL, 'Six issues yearly', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1876, 1, 'FOOD AND COSMETICS TOXICOLOGY', 'Food Cosmet. Toxicol.', 'Oxford, New York // Elmsford, Ny, US : Pergamon Press', '00156264', NULL, 'Mensal', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1877, 1, 'HEALTH CARE FOR WOMEN INTERNATIONAL', '', 'Washington, D.C. : Hemisphere Pub. Corp., c1984-', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1878, 1, 'JOURNAL OF CHEMICAL TECHNOLOGY AND BIOTECHNOLOGY (1986)', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1879, 1, 'POLYMER CHEMISTRY OF SYNTHETIC ELASTOMERS', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1880, 1, 'EMU', 'Emu', 'Melbourne : The Union, (Melbourne : Walker, May & Co., Printers), 1901-', '01584197', NULL, 'Four issues a year', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1881, 1, 'AMERICAN JOURNAL OF SURGICAL PATHOLOGY', 'Am. J. Surg. Pathol.', 'New York : Raven Press , cop1977  //  Masson', '01475185', NULL, 'Monthly (with special supplement in Mar.) <, 1985->', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1882, 1, 'MODERN PATHOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1883, 1, 'ONCOLOGY', 'Oncology', '', '0030-2414 (e) 1423-0232', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1884, 1, 'JOURNAL OF HOSPITAL INFECTION', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1885, 1, 'REVISTA CUBANA DE ENFERMERIA', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1886, 1, 'JOURNAL OF STRUCTURAL GEOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1887, 1, 'CHEMIE DER ERDE', 'Chem. Erde', 'Jena : Gustav Fischer, 1914-', '00092819', NULL, 'Quarterly', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1888, 1, 'MARINE GEOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1889, 1, 'COASTAL ENGINEERING IN JAPAN', 'Coast. Eng. Jpn', 'Tokyo, JP : Japan Society Of Civil Engineers', '05785634', NULL, 'Semestral', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1890, 1, 'COASTAL ENGINEERING', 'Coast. Eng.', 'Amsterdam, NL : Elsevier Scientific Publishing', '03783839', NULL, 'Trimestral', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1891, 1, 'BRITISH JOURNAL OF DERMATOLOGY', 'Br. J. Dermatol.', 'Oxford, Inglaterra, GB : Blackwell Scientific Publications', '00070963', NULL, 'Mensal', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1892, 1, 'POLIMERIC MATERIALS: SCIENCE AND ENGINEERING, PROCEEDINGS OF THE ACS', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1893, 1, 'TRANSACTIONS OF THE AMERICAN INSTITUTE OF CHEMICAL ENGINEERS', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1894, 1, 'MONTHLY WEATHER REVIEW', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1895, 1, 'RADIATION EFFECTS', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1896, 1, 'LIBRARY ACQUISITIONS', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1897, 1, 'REMOTE SENSING ENVIRONMENT', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1898, 1, 'DISABILITY AND REHABILITATIONS: AN INTERNATIONAL, MULTIDISCIPLINARY JOURNAL', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1899, 1, 'JOURNAL OF SMALL ANIMAL SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1900, 1, 'JOURNAL OF VETERINARY INTERNAL MEDICINE', 'J Vet Intern Med', '', '0891-6640 e: 1939-1676', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1901, 1, 'IDENTIFICATIONS METHODS FOR MICROBIOLOGISTS', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1902, 1, 'AMERICAN JOURNAL OF SURGERY', 'Am J Surg', 'New York : Yorke Publishing , cop1891', '00029610', NULL, 'Mensual', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1903, 1, 'EUROPEAN JOURNAL OF SURGERY. SUPPLEMENT', '', 'Stockholm [etc.] Society for the Publication of Acta Chirurigica Scsndinavica [etc.]', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1904, 1, 'DISEASES OF THE COLON AND RECTUM', 'Dis. Colon Rectum', 'Philadelphia [etc.] : Lippincott , cop1958', '00123706', NULL, 'Mensual', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1905, 1, 'WORLD JOURNAL OF SURGERY', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1906, 1, 'AUSTRALIAN JOURNAL OF AGRICULTURAL RESEARCH', '', 'Victoria, Australia, AU : Commonwealth Scientific And Industrial Research Organization', '00049409', NULL, 'Bimestral', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1907, 1, 'CYTOMETRY', '', 'Baltimore, Md. : Society for Analytical Cytology, 1980- // New York, US : Wiley-Liss', '01964763', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1908, 1, 'ACTA CRYSTALLOGRAPHICA. SECTION B, STRUCTURAL CRYSTALLOGRAPHY AND CRYSTAL CHEMISTRY', '', 'Copenhagen : Published for the International Union of Crystallography by Munksgaard, c1967-', '05677408', NULL, 'Monthly', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1909, 1, 'EDUCATIONAL AND PSYCHOLOGICAL MEASUREMENT', '', 'Durham, N.C., etc., : Educational and psychological measurement, etc., 1941-', '00131644', NULL, 'Cuatrimestral, -1994;   Bimensual', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1910, 1, 'AUSTRALIAN JOURNAL OF BIOLOGICAL SCIENCES', 'Aust. J. Biol. Sci.', 'Melbourne : Commonwealth Scientific and Industrial Research Organization, 1953-1988', '00049417', NULL, 'Bimonthly ; Quarterly', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1911, 1, 'JAPANESE JOURNAL OF APPLIED PHYSICS. 1. REGULAR PAPERS & SHORT NOTES', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1913, 1, 'NORSK GEOLOGISK TIDSSKRIFT', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1914, 1, 'JOURNAL OF THE GEOLOGICAL SOCIETY', 'J Geol Soc', '', '0016-7649', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1915, 1, 'CHEMICAL GEOLOGY', 'Chem. Geol.', 'Amsterdam ; New York : Elsevier, 1966-', '00092541', NULL, 'quarterly 1966-69; 8 no. a year 1970-77; 12 no. a year 1978-', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1916, 1, 'RECENT ADVANCES IN BOTANY. CONGRESS', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1917, 1, 'PARASITE: JOURNAL DE LA SOCIETE FRANCAISE DE PARASITOLOGIE', '', '', '', NULL, '', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1918, 1, 'BULLETIN / HOSPITAL FOR JOINT DISEASES', 'Bull. Hosp. Joint Dis.', 'New York, US : Hospital For Joint Diseases, c1992-', '00185647', NULL, 'Trimestral', '2015-09-01 14:24:50', '2016-01-12 14:16:10'),
(1919, 1, 'HISTOPATHOLOGY', 'Histopathology', 'Oxford, Blackwell Scientific Publications', '03090167', NULL, 'Mensal', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1920, 1, 'RADIOGRAPHICS', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1921, 1, 'REPRODUCTION, NUTRITION, DEVELOPMENT', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1922, 1, 'ZENTRALBLATT FUR BAKTERIOLOGIE, PARASITENKUNDE, INFEKTIONSKRANKHEITEN UND HYGIENE. ABT. 1 ORIGINALE. REIHE A MEDIZINISCHE MIKROBIOLOGIE UND PARASITOLOGIE', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1923, 1, 'MAMMALIAN GENOME', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1924, 1, 'JOURNAL OF DIFFERENCIAL EQUATIONS', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1925, 1, 'RUSSIAN ELECTROCHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1926, 1, 'CIRCULATION', 'Circulation', 'Dallas : American Heart Association , 1950 // Baltimore, Md., US : Williams & Wilkins', '00097322', NULL, 'Monthly', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1927, 1, 'CARDIOVASCULAR SURGERY', 'Cardiovasc. Surg.', 'London, GB : Butterworth Scientific', '09672109', NULL, 'Bimestral', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1928, 1, 'JOURNAL OF VASCULAR SURGERY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1929, 1, 'NEPHROLOGY, DIALYSIS, TRANSPLANTATION', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1930, 1, 'BULLETIN DE LA SOCIETE DE PATHOLOGIE EXOTIQUE', 'Bull. Soc. Pathol. Exot.', 'Paris, FR : Masson & Cie.', '00379085', NULL, 'Bimestral', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1931, 1, 'GENE THERAPY', 'Gene Ther.', 'Houndmills, Basingstoke, Hampshire, UK : Macmillan Press Ltd., c1994-', '', NULL, 'Six issues a year', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1932, 1, 'SURGICAL CLINICS OF NORTH AMERICA', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1933, 1, 'COMPTES RENDUS DE L\'ACADEMIE DES SCIENCES - SERIES IIC - CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1934, 1, 'CRITICAL CARE MEDICINE', 'Crit. Care Med.', 'Baltimore : Williams and Wilkins , cop1973 // New York, Kolen', '00903493', NULL, 'Mensual', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1935, 1, 'CURRENT OPINION IN CARDIOLOGY', 'Curr. Opin. Cardiol.', 'London, GB : Gower Academic Journals', '02684705', NULL, 'Bimestral', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1936, 1, 'HUMAN GENETICS', 'Hum. Genet.', 'Berlin, New York, Springer-Verlag', '03406717', NULL, '15 issues per year in 5 vols', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1937, 1, 'JOURNAL OF THE AMERICAN CHEMICAL SOCIETY. CHEMICAL COMMUNICATIONS', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1938, 1, 'SOVIET JOURNAL OF QUANTUM ELECTRONICS', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1939, 1, 'BIOMETRICS', 'Biometrics', 'Washington, US : Biometric Society // Raleigh, North Carolina [etc.] : American Statistical Associat', '0006341X', NULL, 'Quarterly', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1940, 1, 'BOUNDARY LAYER METEOROLOGY', 'Boundary Layer Meteorol.', 'Dordrecht, Holanda, NL : D. Reidel Publishing, c1970-', '00068314', NULL, 'Four no. a year, 1970- ; 8 no. a year, 1976- ; 16 no. a year, 1987-', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1941, 1, 'JOURNAL OF THEORETICAL PROBABILITY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1942, 1, 'STOCHASTIC PROCESSES AND THEIR APPLICATIONS', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1943, 1, 'JOURNAL OF PHYSIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1944, 1, 'PFLUGERS ARCHIV: EUROPEAN JOURNAL OF PHYSIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1945, 1, 'JOURNAL OF HUMAN GENETICS', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1946, 1, 'EUROPEAN JOURNAL OF HUMAN GENETICS', '', 'Nature Journals Online', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1947, 1, 'SCHIZOPHRENIA RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1948, 1, 'ARQUIVOS DE BIOLOGIA E TECNOLOGIA', 'Arq. Biol. Tecnol.', 'Curitiba, PR : Instituto De Tecnologia Do Parana', '03650979', NULL, 'Trimestral', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1949, 1, 'INVERTEBRATE REPRODUCTION & DEVELOPMENT', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1950, 1, 'PACIFIC SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1951, 1, 'BIO/TECHNOLOGY', 'Bio/technology (N.Y. N.Y., 1983)', 'Martinsville // New York, N.Y.: Nature Pub. Co., [c1983]-1996', '0733222X', NULL, 'Monthly', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1952, 1, 'CHEMICAL COMMUNICATIONS', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1953, 1, 'NEW JOURNAL OF CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1954, 1, 'CARBON', 'Carbon', 'New York, N.Y. : Pergamon Press, 1963-', '00086223', NULL, 'Bimonthly', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1955, 1, 'ANNUAL REVIEW OF PHYSICAL CHEMISTRY', 'Annu. rev. phys. chem.', 'Stanford, Conn., US  // Palo Alto, Calif. [etc.]  // Stanford, Calif. [etc.] : Annual Reviews, inc.,', '0066426X', NULL, 'Annual', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1956, 1, 'ANALUSIS', 'Analusis', 'Rueil-Malmaison, France [etc.] : [Societe de productions documentaires], 1972- // Paris, FR : Societ', '03654877', NULL, 'Ten no. a year', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1957, 1, 'ANUARIO DE ESTUDIOS CENTROAMERICANOS', 'Anu. Estud. Centroam.', 'San José : Universidad de Costa Rica, Departamento de Publicaciones, 1974-', '03777316', NULL, 'Semestral', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1958, 1, 'LATIN AMERICAN PERSPECTIVES', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1959, 1, 'COLONIAL LATIN AMERICAN HISTORICAL REVIEW', 'Colon. Latin Am. Hist. Rev.', 'Albuquerque, N.M. : Spanish Colonial Research Center at the University of New Mexico, c1992-', '10635769', NULL, 'Quarterly', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1960, 1, 'INTERNATIONAL ARCHIVES OF ALLERGY AND APPLIED IMMUNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1961, 1, 'JOURNAL OF GEOLOGICAL EDUCATION', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1962, 1, 'JOURNAL OF THE ROYAL SOCIETY OF WESTERN AUSTRALIA', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1963, 1, 'BULLETIN (INTERNATIONAL DAIRY FEDERATION)', 'Bull. Int. Dairy Fed.', 'Brussels, BE : International Dairy Federation', '02598434', NULL, 'Irregular', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1964, 1, 'JAPANESE JOURNAL OF ZOOTECHNICAL SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1965, 1, 'TEORETICHESKAIA I EKSPERIMENTALNAIA KHIMIIA', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1966, 1, 'KIMIIA GETEROTSIKLICHESKIKH SOEDINENII [ENGL. ED.]', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1967, 1, 'PHILOSOPHICAL MAGAZINE A', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1968, 1, 'PROGRESS IN PHYSICAL ORGANIC CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1969, 1, 'STUDIES IN NATURAL PRODUCTS CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1971, 1, 'CHEMICAL AND ENGINEERING NEWS', 'Chem. Eng. News', 'Easton //  Washington : American Chemical Society, cop1942', '00092347', NULL, 'Semanal', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1972, 1, 'ORDER', 'Order', '', '0167-8094 (e): 1572-9273', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1973, 1, 'MOLECULAR PATHOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1974, 1, 'JOURNAL OF HYGIENE', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1975, 1, 'INVESTIGACION Y CIENCIA', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1976, 1, 'JOURNAL OF BASIC AND CLINICAL PHYSIOLOGY AND PHARMACOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1977, 1, 'FOREST SCIENCE', 'For. Sci.', 'Lawrence, Kan., US : Allen Press //  Washington, D.C. : Society of American Foresters, 1955-', '0015749X', NULL, 'Trimestral', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1978, 1, 'PARASITIC ZOONOSES', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1979, 1, 'PHILOSOPHICAL TRANSACTIONS OF THE ROYAL SOCIETY OF LONDON. SERIES A, MATHEMATICAL AND PHYSICAL SCIENCES', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1980, 1, 'CONTINENTAL BASALTS AND MANTLE XENOLITHS', '', 'C.J. Hawkesworth and M.J. Norry, editors', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1981, 1, 'JOURNAL OF APPLIED MATHEMATICS AND MECHANICS', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1982, 1, 'REVUE DE METALLURGIE. CAHIERS D\'INFORMATIONS TECHNIQUES', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1983, 1, 'ACTA CRYSTALLOGRAPHICA. B', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1984, 1, 'ATMOSPHERIC ENVIRONMENT. PART A, GENERAL TOPICS', 'Atmos. Environ.. A Gen. Topics', 'Oxford ; New York : Pergamon Press, c1990-1993', '09601686', NULL, 'Monthly', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1985, 1, 'JOURNAL OF ESSENTIAL OIL RESEARCH', 'J Essent Oil Res', '', '1041-2905', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1986, 1, 'HIGH TEMPERATURE', 'High Temp.', 'New York, N.Y. : Consultants Bureau, 1963-', '0018151X', NULL, 'Bimonthly', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1987, 1, 'ORGANIC REACTIONS', '', '', '0078-6179', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1988, 1, 'INTERNATIONAL JOURNAL OF PHARMACOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1989, 1, 'AMERICAN SCIENTIST', 'Am. sci', 'New Haven, Conn. [etc.] Sigma Xi', '00030996', NULL, 'Bimestral', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1990, 1, 'EARTH SCIENCE REVIEWS', 'Earth-Sci. Rev.', 'Amsterdam, New York, Elsevier Pub. Co., 1966-', '00128252', NULL, 'Trimestral. 6 n. al año. 8 n. al año desde 1992', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1991, 1, 'JOURNAL OF PETROLOGY', '', '', '0022-3530', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1992, 1, 'THE SOCIALIST REGISTER', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1993, 1, 'EUROPEAN JOURNAL OF ORGANIC CHEMISTRY', 'Eur. J. Org. Chem.', 'Weinheim, Germany : Wiley-VCH, c1998-', '1434193X', NULL, 'Monthly', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1994, 1, 'CHIMIA', 'Chimia', 'Zürich : Schweizerischer Chemiker-Verband, 1947-', '00094293', NULL, 'Monthly', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1995, 1, 'CLAY RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1996, 1, 'CHEMICAL INNOVATION', 'Chem. Innov.', 'Washington, DC : American Chemical Society, c2000-c2001', '15274799', NULL, 'Monthly', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1997, 1, 'CURRENT OPINION IN IMMUNOLOGY', 'Curr. Opin. Immunol.', 'Philadelphia, PA, USA : Current Science, c1988- // London, GB : Current Biology', '09527915', NULL, 'Bimonthly', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1998, 1, 'JOURNAL OF LOW TEMPERATURE PHYSICS', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(1999, 1, 'JOURNAL OF SOIL AND WATER CONSERVATION', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2000, 1, 'COMPARATIVE BIOCHEMISTRY AND PHYSIOLOGY. A. PHYSIOLOGY', 'Comp. Biochem. Physiol. Part A, Physiol.', 'New York, US : Elsevier Science', '10964940', NULL, 'Mensal', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2001, 1, 'COMPARATIVE BIOCHEMISTRY AND PHYSIOLOGY. COMPARATIVE PHYSIOLOGY', '', 'Oxford ; New York : Pergamon Press, c1992-c1994', '03009629', NULL, 'Monthly', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2002, 1, 'POLYMER DEGRADATION AND STABILITY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2003, 1, 'ANALYTICAL CELLULAR PATHOLOGY', '', 'Amsterdam, NL : Elsevier Science Publishers', '09218912', NULL, 'Bimestral', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2004, 1, 'CANADIAN JOURNAL OF SOIL SCIENCE', 'Can. J. Soil Sci.', 'Ottawa, Agricultural Institute of Canada', '00084271', NULL, 'Frequency varies; quarterly, 1992-', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2005, 1, 'JOURNAL OF GEOPHYSICAL RESEARCH A. SPACE PHYSICS', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2006, 1, 'ANNALS OF THORACIC SURGERY', 'Ann. Thorac. Surg.', 'Boston, etc., Little, Brown & Co., etc.', '00034975', NULL, 'Monthly <,Jan. 1976->', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2007, 1, 'ANALYSIS AND SIMULATION OF SEMICONDUCTOR DEVICES', '', 'Selberherr, S.', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2008, 1, 'JOURNAL OF PHYSICAL CHEMISTRY. A', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2009, 1, 'STEROIDS', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2010, 1, 'RECUEIL DES TRAVAUX CHIMIQUES DES PAYS-BAS [1920-]', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2011, 1, 'JOURNAL OF THERMAL ANALYSIS AND CALORIMETRY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2012, 1, 'ATOMIC DATA AND NUCLEAR DATA TABLES', 'At. data nucl. data tables', 'San Diego, Calif., US // New York, N.Y. : Academic Press, 1973-', '0092640X', NULL, 'Bimonthly', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2013, 1, 'CHEMISTRY FOR THE FUTURE', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2014, 1, 'PROCEEDINGS OF THE SIXTH BERKELEY SYMPOSIUM ON MATHEMATICAL STATISTICS AND', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2015, 1, 'JOURNAL OF RESEARCH IN SCIENCE TEACHING', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2016, 1, 'IRISH JOURNAL OF FOOD SCIENCE AND TECHNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2017, 1, 'BOTANICAL GAZETTE', 'Bot. Gaz.', 'Chicago, Ill., US : University Of Chicago Press', '00068071', NULL, 'Monthly, Nov. 1876- ; Quarterly', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2018, 1, 'PHYSICA A', 'Physica A', 'Amsterdam : North-Holland Pub. Co., 1975-1998', '03784371', NULL, 'Monthly, 1975-; Semimonthly, Nov. 1988-', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2019, 1, 'PHARMACOGENETICS', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2020, 1, 'JOURNAL OF VACUUM SCIENCE AND TECHNOLOGY B', 'J Vac Sci Tech B', '', '1071-1023 (e): 1520-8567', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2021, 1, 'JOURNAL OF GENETICS', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2022, 1, 'PHARMACOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2023, 1, 'JOURNAL OF BIOLOGICAL EDUCATION', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2024, 1, 'CONTEMPORARY EDUCATIONAL PSYCHOLOGY', 'Contemp. Educ. Psychol.', 'Orlando // New York, N.Y. : Academic Press, 1976-', '0361476X', NULL, 'Cuatrimestral // Trimestral', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2025, 1, 'SCIENCE TEACHER', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2026, 1, 'EDUCATIONAL COMMUNICATION AND TECHNOLOGY', 'Educ. Commun. Technol.', 'Washington, D.C.: Association for Educational Communications and Technology, c1978-c1988', '01485806', NULL, 'Vol. 26, no. 1 (spring 1978)-v. 36, no. 4 (winter 1988)', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2027, 1, 'JOURNAL OF EDUCATIONAL PSYCHOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10');
INSERT INTO `journal` (`id`, `instance_id`, `name`, `abbreviation`, `responsible`, `ISSN`, `ISSNE`, `frecuency`, `createdAt`, `updatedAt`) VALUES
(2028, 1, 'BRITISH JOURNAL OF EDUCATIONAL PSYCHOLOGY', 'Br. J. Educ. Psychol.', 'Leicester, Inglaterra, GB : British Psychological Society // Edinburgh [etc.] Scottish Academic Pres', '00070998', NULL, 'Trimestral', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2029, 1, 'BACTERIOLOGICAL REVIEWS', 'Bacteriol. Rev.', 'Baltimore, Maryland : American Society for Microbiology, etc., 1937-1977', '00053678', NULL, 'Semiannual, 1937-39 ; quarterly, 1940-77', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2030, 1, 'PHYSICAL REVIEW. A', 'Phys. Rev., A', 'New York, N.Y. : Published by the American Physical Society through the American Institute of Physic', '10502947', NULL, 'Semimonthly', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2031, 1, 'BULLETIN DE LA SOCIETE ROYAL SCIENTIFIQUE DE LIEGE', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2032, 1, 'JOURNAL OF INSECT PHYSIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2033, 1, 'APPLIED OPTIMAL ESTIMATION', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2034, 1, 'STOCHASTIC PROCESSES AND FILTERING THEORY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2035, 1, 'ENVIRONMENTAL HEALTH PERSPECTIVES. SUPPLEMENTS', 'Environ. Health Perspect., Suppl.', '[Research Triangle Park, N.C.] : U.S. Dept. of Health and Human Services, Public Health Service, Nat', '10780475', NULL, 'Six to 8 no. a year', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2036, 1, 'HISTORIA MATHEMATICA', '', 'New York [etc.] Academic Press, [etc.]', '03150860', NULL, '4 no. a year', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2037, 1, 'PROGRESS IN MATHEMATICS', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2038, 1, 'AMERICAN MATHEMATICAL MONTHLY', 'Am. math. mon', 'Washington, D.C. : Mathematical Association of America , 1894 // Kidder, Mo. [etc.] : Chubbuck Bros.', '00029890', NULL, 'Monthly, except July and Aug', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2039, 1, 'VIRAL IMMUNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2040, 1, 'ECOTOXICOLOGY AND ENVIRONMENTAL SAFETY', 'Ecotoxicol. Environ. Saf.', 'New York, US  // San Diego [etc.] Academic Press', '01476513', NULL, 'Bimonthly <, 1982- >', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2041, 1, 'CELLULAR IMMUNOLOGY', 'Cell. Immunol.', 'New York  // San Diego [etc.] Academic Press', '00088749', NULL, '14 no a year <, Oct. 1, 1985->', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2042, 1, 'SCANDINAVIAN JOURNAL OF WORK, ENVIRONMENT & HEALTH', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2043, 1, 'SURGICAL NEUROLOGY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2044, 1, 'CORNELL VETERINARIAN', 'Cornell Vet.', 'Ithaca, N.Y. : Published under the auspices of the Alumni Association and Society of Comparative Med', '00108901', NULL, 'Semiannual June 1911-Jan. 1914; quarterly Apr. 1914-', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2045, 1, 'AIAA UNMANNED SPACECRAFT MEETING [1965: LOS ANGELES, CALIF.]', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2046, 1, 'JAPANESE JOURNAL OF APPLIED PHYSICS. PART 1', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2047, 1, 'ETHOLOGY', '', 'Berlin, DE : Paul Parey', '01791613', NULL, 'Trimestral', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2048, 1, 'ADVANCES IN NEUROLOGY', 'Adv. Neurol.', 'New York, US : Raven Press', '00913952', NULL, 'Irregular', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2049, 1, 'EPILEPSIA', 'Epilepsia', 'Amsterdam, NL : International League Against Epilepsy //  New York [etc.] Raven Press [etc.]', '00139580', NULL, 'Bimestral', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2050, 1, 'REVISTA DE NEUROLOGIA', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2051, 1, 'INTERNATIONAL JOURNAL OF BIOMEDICAL COMPUTING', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2052, 1, 'SHELL-MODEL APPLICATIONS IN NUCLEAR SPECTROSCOPY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2053, 1, 'JOURNAL OF POLYMER SCIENCE. POLYMER CHEMISTRY EDITION', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2054, 1, 'JOURNAL OF CHROMATOGRAPHY A', 'J. Chromatogr. A', 'Amsterdam, NL : Elsevier Science', '0021-9673', NULL, 'Semanal', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2055, 1, 'PARASITIC PROTOZOA', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2056, 1, 'INORGANIC CHEMISTRY LETTERS', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2057, 1, 'ORGANIC MASS SPECTROMETRY', '', '', '1096-9888', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2058, 1, 'WATER SCIENCE AND TECHNOLOGY', 'Water Sci. Technol.', ' Oxford, England ; New York, N.Y. : Pergamon Press, 1981-', '02731223', NULL, 'Monthly', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2059, 1, 'JOURNAL OF SYMBOLIC LOGIC', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2060, 1, 'INTERNATIONAL JOURNAL OF SYSTEMATIC BACTARIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2061, 1, 'JOURNAL OF THEORETICAL BIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2062, 1, 'SCIENCE REPORTS OF THE RESEARCH INSTITUTES, TOHOKU UNIVERSITY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2063, 1, 'TRENDS IN NEUROSCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2064, 1, 'JOURNAL OF ENGINEERING FOR GAS TURBINES AND POWER', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2065, 1, 'VETERINARY CLINICS OF NORTH AMERICA FOOD ANIMAL PRACTICE', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2066, 1, 'INTERNATIONAL JOURNAL OF INORGANIC MATERIALS', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2067, 1, 'CHAOS SOLITONS AND FRACTALS', '', 'Oxford, Inglaterra, GB : Pergamon Press', '09600779', NULL, 'Bimestral', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2068, 1, 'JOURNAL OF SOUND AND VIBRATION', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2069, 1, 'JOURNAL OF ELECTROSTATICS', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2070, 1, 'ACTA MECHANICA', 'Acta mech.', 'Wien ; New York : Springer-Verlag, 1965-', '00015970', NULL, '4 vols. of 4 nos. (2 double issues) <, 1', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2071, 1, 'JOURNAL OF ULTRASTRUCTURE RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2073, 1, 'GENERALIZED FUNCTIONS', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2074, 1, 'AMERICAN JOURNAL OF MATHEMATICS', 'Am. j. math.', 'Baltimore [etc.] : Johns Hopkins University Press [etc.], 1878-', '00029327', NULL, 'Four no. a year, 1878- / Bimonthly, <1990->', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2075, 1, 'RESEARCH IN SCIENCE & TECHNOLOGICAL EDUCATION', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2076, 1, 'SCHOOL, SCIENCE AND MATHEMATICS', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2077, 1, 'RESEARCH IN SCIENCE AND TECHNOLOGICAL EDUCATION', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2078, 1, 'ACTA MATHEMATICA', 'Acta math.', 'Uppsala [etc.] : Almqvist & Wiksell, [etc.], 1882-', '00015962', NULL, '4 nos. a year', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2079, 1, 'NASA HANDBOOK FOR NICKEL-HYDROGEN BATTERIES', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2080, 1, 'JOURNAL OF INCLUSION PHENOMENA AND MACROCYCLIC CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2081, 1, 'JOURNAL OF GUIDANCE AND CONTROL', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:10'),
(2082, 1, 'JOURNAL OF GUIDANCE, CONTROL, AND DYNAMICS', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2083, 1, 'ATLA', '', 'Nottingham, Inglaterra, GB : Fund For The Replacement Of Animals In Medical Experiments', '02611929', NULL, 'Trimestral', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2084, 1, 'ANTI-CORROSION METHODS AND MATERIALS', 'Anti-corros. methods mater.', 'London : Sawell Publications, etc., 1966-', '00035599', NULL, 'Monthly', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2085, 1, 'ARCHIVES OF ENVIRONMENTAL HEALTH', 'Arch. environ. health', 'Washington, D.C. Heldref Publications [etc.]', '00039896', NULL, 'Monthly, 1960-<June 1961> ; Bimonthly, <Jan./Feb. 1976->', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2086, 1, 'PAEDIATRIC PERINATAL EPIDEMIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2087, 1, 'NEURON', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2088, 1, 'PESQUISA AGROPECUARIA BRASILEIRA', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2089, 1, 'BRITISH MEDICAL JOURNAL', '', 'Edinburgh, Inglaterra, GB : British Medical Association', '09598146', NULL, 'Semanal', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2090, 1, 'JOURNAL OF CLINICAL EPIDEMIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2091, 1, 'SOLAR ENERGY MATERIALS AND SOLAR CELLS', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2092, 1, 'OCCUPATIONAL MEDICINE', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2093, 1, 'BOLETIN DE LA ACADEMIA CHILENA DE LA HISTORIA', 'Bol. Acad. Chil. Hist.', 'Santiago De Chile, CL : Academia Chilena De La Historia', '07165439', NULL, 'Semiannual, 1933-1970 ; Annual, 1971-', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2094, 1, 'JOURNAL OF COMPUTATIONAL AND APPLIED MATHEMATICS', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2095, 1, 'ARCHIVES OF DISEASE IN CHILDHOOD', 'Arch. Dis. Child.', 'London, GB : British Medical Association', '00039888', NULL, 'Anteriormente bimestral ; Frec. actual:  Mensual', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2096, 1, 'METHODS IN MICROBIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2097, 1, 'JOURNAL OF LIQUID CHROMATOGRAPHY AND RELATED TECHNOLOGIES', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2098, 1, 'JOURNAL OF BIOMECHANICAL ENGINEERING', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2099, 1, 'INTERNATIONAL JOURNAL OF MINERAL PROCESSING', 'Int. J. Miner. Process.', 'Amsterdam, NL : Elsevier Science Publishers', '03017516', NULL, 'Quarterly', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2100, 1, 'JOURNAL OF CLIMATE', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2101, 1, 'FARMACO. EDIZIONE SCIENTIFICA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2102, 1, 'JOURNAL OF PHYSICS. A', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2103, 1, 'PROCEESING OF LATIN AMERICAN WORKSHOP ON GREEN HOUSE GAS EMISSION OF ENERGY SECTOR AND THEIR IMPACTS', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2104, 1, 'ZEITSCHRIFT FUR NATURFORSCHUNG. A', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2105, 1, 'COMBUSTION AND PLASMA SYNTHESIS OF HIGH TEMPERATURE MATERIALS', '', 'MUNIR AND HOLT EDS.', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2106, 1, 'JOURNAL OF MATERIALS SYNTHESIS AND PROCESSING', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2107, 1, 'PROGRESS IN MATERIALS SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2108, 1, 'RIO DE LA PLATA', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2109, 1, 'DESALINATION', 'Desalination', 'Amsterdam : Elsevier Scientific Pub. Co., 1966-', '00119164', NULL, 'Frequency varies', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2110, 1, 'IMMUNOLOGY RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2111, 1, 'ACTA VETERINARIA SCANDINAVICA', '', 'Copenhagen, DK : Societatum Veterinariarum Scandinavicarum', '0044605X', NULL, 'Trimestral', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2112, 1, 'BULLETIN OF MARINE SCIENCE', 'Bull. Mar. Sci.', 'Coral Gables, Fla., US : University Of Miami, Marine Laboratory // Coral Gables, Fla. : Rosenstiel S', '00074977', NULL, 'Quarterly, 1965-1983 ;  bimonthly Jan. 1984-', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2113, 1, 'INVENTIONES MATHEMATICAE', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2114, 1, 'ENDOCRINE JOURNAL', 'Endocr. J.', 'Tokyo : Japan Endocrine Society, 1993-', '09188959', NULL, 'Bimonthly', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2115, 1, 'METABOLISM : CLINICAL AND EXPERIMENTAL', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2116, 1, 'PSYCHONEUROENDOCRINOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2117, 1, 'JOURNAL OF THE ASTRONAUTICAL SCIENCES', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2118, 1, 'CLINICAL ORTHOPAEDICS AND RELATED RESEARCH', 'Clin. Orthop. Relat. Res.', 'Philadelphia : J. B. Lippincott', '0009921X', NULL, 'Ten issues a year <, Nov./Dec. 1981->', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2119, 1, 'BIOLOGICAL BULLETIN OF THE MARINE BIOLOGICAL LABORATORY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2120, 1, 'JOURNAL OF ORTHOPAEDIC RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2121, 1, 'ISRAEL JOURNAL OF MEDICAL SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2122, 1, 'INTERNATIONAL JOURNAL OF INVERTEBRATE REPRODUCTION', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2123, 1, 'JOURNAL OF THE MARINE ASSOCIATION OF THE UNITED KINGDOM', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2124, 1, 'HUMAN GENE THERAPHY', 'Hum. Gene Ther.', 'New York : Mary Ann Liebert, c1990-', '', NULL, 'Quarterly', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2125, 1, 'INTERNAL MEDICINE', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2126, 1, 'EUROPEAN JOURNAL OF MEDICINAL CHEMISTRY', 'Eur. J. Med. Chem.', 'Paris : S.E.C.T. [etc.], 1974-', '02235234', NULL, 'Bimonthly', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2127, 1, 'CRITICAL REVIEWS IN MICROBIOLOGY', 'Crit. Rev. Microbiol.', 'Boca Raton, Fla. : CRC Press, 1980-', '1040841x', NULL, 'Quarterly', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2128, 1, 'JOURNAL OF EXPERIMENTAL BIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2129, 1, 'UROLOGY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2130, 1, 'SOVIET PHYSICS. TECHNICAL PHYSICS', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2131, 1, 'SOVIET TECHNICAL PHYSICS LETTERS', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2132, 1, 'BIOCHIMIE', 'Biochimie', 'Paris : Société de chimie biologique, 1971- // Masson // Elsevier', '03009084', NULL, 'Monthly', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2133, 1, 'ADVANCES IN APPLIED MICROBIOLOGY', '', 'New York, Academic Press // San Diego, Calif., US : Academic Press', '00652164', NULL, 'Irregular', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2134, 1, 'ARQUIVOS BRASILEIROS DE CARDIOLOGIA', 'Arq. Bras. Cardiol.', 'Sao Paulo, SP : Sociedade Brasileira De Cardiologia', '0066782X', NULL, 'Mensal', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2135, 1, 'REVISTA DO INSTITUTO DE MEDICINA TROPICAL DE SAO PAULO', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2136, 1, 'IMMUNOLOGIC RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2137, 1, 'POULTRY SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2138, 1, 'JOURNAL OF COMPARATIVE NEUROLOGY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2139, 1, 'PLANT BREEDING', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2140, 1, 'EUROPEAN JOURNAL OF PLANT PATHOLOGY', 'Eur. J. Plant Pathology', 'Dordrecht [etc.] : Kluwer Academic : European Foundation for Plant Pathology , 1994-', '09291873', NULL, 'Nine issues yearly', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2141, 1, 'JOURNAL OF INTERDISCIPLINARY HISTORY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2142, 1, 'INTERNATIONAL JOURNAL OF SYSTEMATIC AND EVOLUTIONARY MICROBIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2143, 1, 'PLANT DISEASE', 'Plant Dis.', 'St. Paul, Minn. : American Phytopathological Society, 1980-', '01912917', NULL, 'Mensal', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2144, 1, 'JOURNAL OF CRUSTACEAN BIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2145, 1, 'ANNALES DE MICROBIOLOGIE: ORGANE DE LA SOCIETÉ FRANCAISE DE MICROBIOLOGIE', '', 'Paris, FR: Masson', '03005410', NULL, 'Bimestral', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2146, 1, 'PLANT PATHOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2147, 1, 'CRUSTACEANA', '', 'Leiden, Holanda, NL : Universiteit Van Amsterdam, Zoologisch Museum', '0011216X', NULL, 'Mensal', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2148, 1, 'ACTA HORTICULTAE WAGENINGEN: INTERNATIONAL SOCIETY FOR HORTICULTURAL SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2149, 1, 'ARCHIVES OF BIOCHEMISTRY', '', 'New York, US : Academic Press', '00969621', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2150, 1, 'CLINICAL CHEMISTRY', 'Clin. Chem.', 'Baltimore, Md., etc. : Paul B. Hoeber, Inc. for the American Association of Clinical Chemists, etc.,', '00099147', NULL, 'Bimonthly 1955-1963; monthly 1964-', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2151, 1, 'BOLETIN DEL INSTITUTO ESPAÑOL DE OCEANOGRAFIA', 'Bol. Inst. Esp. Oceanogr.', 'Madrid, ES : Instituto Espanol De Oceanografia', '00740195', NULL, 'Trimestral', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2152, 1, 'TRENDS IN CELL BIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2153, 1, 'WASTE MANAGEMENT', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2154, 1, 'ENVIRONMENTAL ENGINEERING SCIENCE', 'Environ. Eng. Sci.', 'Larchmont, NY : Mary Ann Liebert, Inc.', '10928758', NULL, 'Quarterly', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2155, 1, 'AGROCIENCIA (CHAPINGO)', '', 'Chapingo, Mexico, MX : Colegio De Postgraduados, Escuela Nacional De Agricultura', '01850288', NULL, 'Trimestral', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2156, 1, 'SOVIET PHYSICS. SOLID STATE', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2157, 1, 'FUEL', '', 'London, GB : Butterworth Scientific // Guildford, England : IPC Science and Technology Press', '00162361', NULL, 'Monthly', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2158, 1, 'REVISTA CUBANA DE MEDICINA TROPICAL', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2159, 1, 'APPLIED THERMAL ENGINEERING', 'Appl. Therm. Eng.', 'Oxford, Inglaterra, GB : Elsevier Science', '13594311', NULL, 'Mensal', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2160, 1, 'KINETICS AND CATALYSIS', '', 'New York, US : Plenum Publishing', '00231584', NULL, 'Mensal', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2161, 1, 'CHEMISTRY AND TECHNOLOGY OF FUELS AND OILS', 'Chem. Technol. Fuels Oils', 'New York, US: Consultants Bureau Enterprises', '00093092', NULL, 'Bimestral', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2162, 1, 'HAZARDOUS MATERIALS', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2163, 1, 'QUARTERLY REVIEW OF BIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2164, 1, 'ONDERSTEPOORT JOURNAL OF VETERINARY RESEARCH', 'Onderstepoort J Vet Res', '', '0030-2465', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2165, 1, 'ISELYA', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2166, 1, 'WILLDENOWIA', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2167, 1, 'ACI MATERIALS JOURNAL', 'ACI mater. j', 'Detroit, Mich. : American Concrete Institute, [c1987-', '0889325X', NULL, 'Bimonthly', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2168, 1, 'EUROPEAN JOURNAL OF PROSTHODONTICS AND RESTORATIVE DENTISTRY', 'Eur. J. Prosthodont. Restor. Dent.', 'Ramford, Inglaterra, GB : Mosby Year Book', '09657452', NULL, 'Trimestral', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2169, 1, 'JOURNAL OF DENTAL RESEARCH', '', '', '0022-0345', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2170, 1, 'DENTAL MATERIALS', 'Dent. Mater.', 'Copenhagen : Munksgaard, 1985- // Kidlinton, Inglaterra, GB : Elsevier Science', '01095641', NULL, 'Bimestral', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2171, 1, 'EGYPTIAN DENTAL JOURNAL', '', 'Cairo, EG : Egyptian Dental Association', '00709484', NULL, 'Mensal', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2172, 1, 'JOURNAL OF DENTISTRY', 'J Dent', '', '0300-5712', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2173, 1, 'ANNALES DE L\'INSTITUT PASTEUR', 'Ann. Inst. Pasteur', 'Paris : Masson, -1972', '00202444', NULL, 'Monthly', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2174, 1, 'BULLETIN OF MATHEMATICAL BIOPHYSICS', 'Bull. Math. Biophys.', 'New York, US : Pergamon Press // Holland, Mich. [etc.] : Mathematical Biology, inc. [etc.], 1939-197', '00074985', NULL, 'Quarterly', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2175, 1, 'PHYSICA SCRIPTA', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2176, 1, 'CELL BIOLOGY INTERNATIONAL', 'Cell Biol. Int.', 'London, UK : Published for the International Federation for Cell Biology by Academic Press, c1993-', '10656995', NULL, 'Monthly', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2177, 1, 'TRANSACTIONS OF THE NEW YORK ACADEMY OF SCIENCES', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2178, 1, 'BRITISH JOURNAL OF BIOMEDICAL SCIENCE', 'Brit. J. Biomed. Sci.', 'London, GB; London, GB : Published for the Institute of Medical Laboratory Sciences by Royal Society', '09674845', NULL, 'Quarterly', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2179, 1, 'PROBLEMS OF INFORMATION TRANSMISSION', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2180, 1, 'FLUID DYNAMICS RESEARCH', 'Fluid Dyn. Res.', 'Amsterdam, Netherlands : North-Holland, 1986-', '01695983', NULL, 'Quarterly', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2181, 1, 'MEDICINE, SCIENCE, AND LAW', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2182, 1, 'PATHOLOGIA ET MICROBIOLOGIA', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2183, 1, 'PROC. INTER. PLANT. PROP. SOC.', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2184, 1, 'FOOD MANUFACTURE', '', 'London, GB : Morgan Grampian', '00156477', NULL, 'Mensal', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2185, 1, 'REVISTA BRASILEIRA DE ZOOLOGIA', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2186, 1, 'REVIEWS OF PURE APPLIED CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2187, 1, 'OXIDATION AND COMBUSTION REVIEWS', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2188, 1, 'JOURNAL OF THE LESS COMMON METALS', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2189, 1, 'JOURNAL OF MOLECULAR AND CELLULAR CARDIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2190, 1, 'JAPANESE HEART JOURNAL', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2191, 1, 'BULLETIN OF THE IRISH BIOGEOGRAPHICAL SOCIETY', '', 'Irish Biogeographical Society (Dublin)', '', NULL, 'Anual', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2192, 1, 'IRISH NATURALISTS JOURNAL', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2193, 1, 'JOURNAL OF FOOD QUALITY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2194, 1, 'AMERICAN JOURNAL OF RESPIRATORY AND CRITICAL CARE MEDICINE', 'Am. J. Respir. Crit. Care. Med.', 'New York : American Lung Association, c1994- // New York, US : American Thoracic Society', '1073449X', NULL, 'Monthly', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2195, 1, 'SOUTHEAST ASIAN JOURNAL OF TROPICAL MEDICINE AND PUBLIC HEALTH', 'Southeast Asian J Trop Med Public Health', '', '0125-1562', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2196, 1, 'EMERGING INFECTIOUS DISEASES', 'Emerg. Infect. Dis.', 'Atlanta, GA : National Center for Infectious Diseases, Centers for Disease Control and Prevention (C', '10806040', NULL, 'Four times a year', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2197, 1, 'JOURNAL OF RHEOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2198, 1, 'REVISTA ITALIANA DELLE SOSTANZE GRASSE', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2199, 1, 'REVUE FRANCAISE DES CORPS GRAS', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2200, 1, 'ETHIOPIAN MEDICAL JOURNAL', '', 'Addis Ababa, ET : Ethiopian Medical Association', '00141755', NULL, 'Ethiop. Med. J.', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2201, 1, 'BRIMLEYANA', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2202, 1, 'PROCEEDINGS OF THE ZOOLOGICAL SOCIETY OF LONDON', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2203, 1, 'ARQUIVOS DO INSTITUTO BIOLOGICO', 'Arq. Inst. Biol.', 'Sao Paulo : Instituto Biologico , 1934-', '00203653', NULL, 'Semestral', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2204, 1, 'REVISTA DA SOCIEDADE BRASILEIRA DE MEDICINA TROPICAL', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2205, 1, 'MICROBIOLOGY AND INFECTIOUS DISEASE', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2206, 1, 'DIAGNOSTIC MICROBIOLOGY AND INFECTIOUS DISEASE', 'Diagn. Microbiol. Infect. Dis.', 'New York : Elsevier Science Pub. Co., c1983-', '07328893', NULL, 'Monthly, <June 1987->', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2207, 1, 'BOLETIN CHILENO DE PARASITOLOGIA', 'Bol. Chil. Parasitol.', 'Santiago De Chile, CL : Universidad De Chile', '03659402', NULL, 'Trimestral', '2015-09-01 14:24:51', '2015-09-01 14:25:03'),
(2208, 1, 'SYSTEMATIC BOTANY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2209, 1, 'AMERICAN BEE JOURNAL', '', 'Hamilton, Ill., US : Dadant & Sons', '00027626', NULL, 'Mensal', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2210, 1, 'COATING', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2212, 1, 'JOURNAL OF NEUROCHEMISTRY', '', '', '1471-4159', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2213, 1, 'EXPERIMENTAL CELL RESEARCH', 'Expr. Cell Res.', 'New York, US : Academic Press, 1950-', '00144827', NULL, 'Irregular <, Apr. 1950-Dec. 1958>;  monthly (except semimonthly Mar. and Oct) <, Nov. 1975-> ; month', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2215, 1, 'ACTA ANAESTHESIOLOGICA SCANDINAVICA', 'Acta Anaesthesiol. Scand.', '[Copenhagen] : Munksgaard International Publishers, // Aarhus (de acuerdo con HSC)', '00015172', NULL, 'Ten no. a year / Frec. actual : Ocho n.', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2216, 1, 'JOURNAL OF CARDIOVASCULAR PHARMACOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2217, 1, 'JOURNAL OF SURGICAL RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2218, 1, 'EUROPEAN JOURNAL OF HEART FAILURE', 'Eur. J. Heart Fail.', 'Amsterdam [etc.] : Elsevier, 1987-', '13889842', NULL, 'Bimonthly', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2219, 1, 'REVISTA MEDICA DE CHILE', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2220, 1, 'JOURNAL OF CARDIOTHORACIC AND VASCULAR ANESTHESIA', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2221, 1, 'JOURNAL OF NEMATOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2222, 1, 'NEMATOLOGICA', '', '', '0028-2596', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2223, 1, 'INTERNATIONAL JOURNAL OF POLYMERIC MATERIALS', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2224, 1, 'FRIESIA', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2225, 1, 'ADVANCES IN FORENSIC HAEMOGENETICS', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2226, 1, 'ACTA PATHOLOGICA ET MICROBIOLOGICA SCANDINAVICA. SECTION B, MICROBIOLOGY AND IMMUNOLOGY', 'Acta Pathol. Microbiol. Scand. Sect. B, Microbiol. Immunol.', 'Copenhagen, Munksgaard', '03655563', NULL, 'Bimonthly', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2227, 1, 'AGRO ENVIRONMENTAL PROTECTION', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2228, 1, 'TOXICOLOGICAL AND ENVIRONMENTAL CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2229, 1, 'JOURNAL OF MEDICAL ENTOMOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2230, 1, 'INTERNATIONAL JOURNAL OF BIOCHEMISTRY AND CELL BIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2231, 1, 'BIOCYCLE: JOURNAL OF WASTE RECYCLING', 'Biocycle', 'Emmaus, PA : JG Press, 1981-', '', NULL, 'Bimonthly // Mensal', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2232, 1, 'PALEOBIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2233, 1, 'PEDIATRIC RADIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2234, 1, 'HUMAN IMMUNOLOGY', 'Hum. Immunol.', 'New York: Elsevier/North-Holland', '01988859', NULL, 'Mensal', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2235, 1, 'ARCHIVOS ESPAÑOLES DE UROLOGÍA', 'Arch. Esp. Urol.', 'Madrid, ES : Bok // Orbe , cop1944', '00040614', NULL, 'Bimestral // Diez n. al año', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2236, 1, 'ACTAS UROLOGICAS ESPAÑOLAS', 'Actas Urol. Esp.', 'Madrid : Asociación Española de Urología , cop1977', '02104806', NULL, 'Bimestral', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2237, 1, 'JOURNAL OF SMALL ANIMAL PRACTICE', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2238, 1, 'LA PRESSE MEDICALE', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2239, 1, 'ENVIRONMENTAL PROTECTION', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2240, 1, 'SYMPOSIUM SERIES. SOCIETY FOR APPLIED BACTERIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2241, 1, 'ZUCKERINDUSTRIE', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2242, 1, 'HORTTECHNOLOGY', 'Horttechnology', 'Alexandria, Va., US : American Society For Horticultural Science', '10630198', NULL, 'Trimestral', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2243, 1, 'FOOD INDUSTRY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2244, 1, 'JOURNAL OF FOOD SCIENCE AND TECHNOLOGY', '', 'Mysore, India, IN : Association Of Food Technologist', '00221155', NULL, 'Bimestral', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2245, 1, 'HORTICULTURA BRASILEIRA', 'Hortic. Bras.', 'Brasilia, DF : Sociedade De Olericultura Do Brasil', '01020536', NULL, 'Semestral', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2246, 1, 'IEEE TRANSACTIONS ON POWER SYSTEMS', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2247, 1, 'FOOD CONTROL', 'Food Control', 'Surrey, Inglaterra, GB : Butterworth Scientific, 1990-', '09567135', NULL, 'Trimestral', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2248, 1, 'VEGETABLE CROPS RESEARCH BULLETIN', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2249, 1, 'POSTHARVEST HORTICULTURE SERIES DEPARTAMENT OF POMOLOGY UNIVERSITY OF CALIFORNIA', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2250, 1, 'RUSSIAN AGRICULTURAL SCIENCES', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2251, 1, 'MINERALS ENGINEERING', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2252, 1, 'VIRUS RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2253, 1, 'SOVIET MATHEMATICS', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2254, 1, 'MOLECULAR MARINE BIOLOGY AND BIOTECHNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2255, 1, 'NATURWISSENSCHAFTEN', 'Naturwissenschaften', '', '0028-1042 (e): 1432-1904', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2256, 1, 'JOURNAL OF REPRODUCTION AND FERTILITY', 'J. Reprod. Fertil.', 'Cambridge, Inglaterra , GB : Society For The Study Of Fertility', '00224251', NULL, 'Bimestral', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2257, 1, 'AUSTRALIAN SYSTEMATIC BOTANY', 'Aust. Syst. Bot.', 'Melbourne, Australia, AU : Commonwealth Scientific And Industrial Research Organization', '10301887', NULL, 'Trimestral', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2258, 1, 'BIOTECHNOLOGY ADVANCES', 'Biotech. Adv.', 'New York, US : Pergamon Press', '07349750', NULL, 'Dos nros al año (1983-1987). Cuatro nros al año (1988-1997). Bimestral 1998. Ocho nros al año, 1999-', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2259, 1, 'INTERNATIONAL DAIRY JOURNAL', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2260, 1, 'JOURNAL OF COMPARATIVE PHYSIOLOGY  A, SENSORY, NEURAL, AND BEHAVIORAL PHYSIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:51', '2016-01-12 14:16:11'),
(2261, 1, 'NEW ZEALAND JOURNAL OF MARINE FRESHWATER RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2262, 1, 'ACTA HORTICULTURAE SINICA', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2263, 1, 'ANUARIO IEHS', '', 'Tandil, Argentina : Universidad Nacional del Centro de la Provincia de Buenos Aires, [1987-', '03269671', NULL, 'Annual', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2264, 1, 'TISSUE AND CELL', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2265, 1, 'ZOOMORPHOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2266, 1, 'NEOTROPICA', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2267, 1, 'REVISTA DE LA SOCIEDAD ENTOMOLOGICA ARGENTINA', '', '', '', NULL, '', '2015-09-01 14:24:52', '2015-09-01 14:25:07'),
(2268, 1, 'EUROPEAN HEART JOURNAL', 'Eur. Heart J.', 'London, GB : Academic Press', '0195668X', NULL, 'Monthly <, Jan. 1983- >', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2269, 1, 'JOURNAL OF THE JAPANESE SOCIETY FOR HORTICULTURAL SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2270, 1, 'AUSTRALIAN  JOURNAL OF EXPERIMENTAL AGRICULTURE', '', 'Melbourne, Australia, AU : Commonwealth Scientific And Industrial Research Organization', '08161089', NULL, 'Trimestral', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2271, 1, 'CHEMISTRY AND INDUSTRY', 'Chem. Ind.(Lond)', 'London, Eng. : Society of Chemical Industry, 1932-', '00093068', NULL, 'Semimonthly', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2272, 1, 'JOURNAL OF BIOMEDICAL OPTICS', 'J. Biomed. Opt.', '', '1083-3668', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2273, 1, 'MEDICAL AND BIOLOGICAL ENGINEERING AND COMPUTING', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2274, 1, 'PATTERN RECOGNITION', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2275, 1, 'PROBLEMS IN VETERINARY MEDICINE', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2276, 1, 'ANIMAL HOSPITAL', '', 'Chicago, Ill., US: Willing Publishers', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2277, 1, 'STATISTICA', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2278, 1, 'KOREAN JOURNAL OF HORTICULTURAL SCIENCE AND TECHNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2279, 1, 'ADVANCES IN HORTICULTURE', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2280, 1, 'ADVANCES IN HORTICULTUAL SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2281, 1, 'MINING ENGINEERING', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2282, 1, 'PHYTOLOGIA', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2283, 1, 'FOLIA GEOBOTANICA', 'Folia Geobot.', 'Praha, CS : Academy Of Sciences Of The Czech Republic, Institute Of Botany, 1998-', '12119520', NULL, 'Trimestral', '2015-09-01 14:24:52', '2015-09-01 14:25:05'),
(2284, 1, 'JOURNAL OF RESEARCH OF THE NATIONAL BUREAU OF STANDARDS. SECTION D. RADIO SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2285, 1, 'CANADIAN JOURNAL OF CIVIL ENGINEERING', 'Can. J. Civ. Eng.', 'Ottawa, Ont. : National Research Council of Canada, 1974-', '03151468', NULL, 'Bimestral', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2286, 1, 'ANNUAL REVIEW OF CELL BIOLOGY', 'Annu. rev. cell biol.', 'Palo Alto, Calif. : Annual Reviews, c1985-c1994', '07434634', NULL, 'Annual', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2287, 1, 'AMERICAN JOURNAL OF DENTISTRY', 'Am. j. dent.', 'San Antonio, TX, USA : Mosher and Linder , 1988-', '08948275', NULL, 'Bimestral', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2288, 1, 'INIDIAN JOURNAL OF AGRICULTURAL SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2289, 1, 'INDIAN PHYTOPATHOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2290, 1, 'JOURNAL OF FISH DISEASES', '', '', '1365-2761', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2291, 1, 'IDEOLOGIES & LITERATURE', 'Ideol. Lit.', 'Minneapolis, Minn., US : Institute For The Study Of Ideologies And Literature, 1977-1989', '01619225', NULL, 'Quinzenal', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2292, 1, 'JOURNAL OF CRANIOFACIAL GENETICS AND DEVELOPMENT BIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2293, 1, 'PROCESS BIOCHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2294, 1, 'MOLECULAR BREEDING', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2295, 1, 'PROTEIN STRUCTURE AND MOLECULAR ENZYMOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2296, 1, 'IEEE TRANSACTIONS ON FUZZY SYSTEMS', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2297, 1, 'SYSTEMS AND CONTROL LETTERS', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2298, 1, 'ELECTRIC POWER SYSTEMS RESEARCH', 'Electr. Power Syst. Res.', 'Lausanne [Switzerland] : Elsevier Sequoia, 1977-', '03787796', NULL, 'Quarterly', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2299, 1, 'ADVANCES IN PHYSICS', 'Adv. phys', 'London, GB : Taylor & Francis', '00018732', NULL, 'Quarterly, 1952-<Apr. 1953>; Bimonthly <Nov. 1977- >', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2300, 1, 'SYMPOSIUM ON FREQUENCY CONTROL', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2301, 1, 'BIOMATERIALS', '', 'Surrey, Inglaterra, GB : Butterworth Scientific', '01429612', NULL, 'Quinzenal', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2302, 1, 'CORROSION OF METALS IN CONCRETE', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2303, 1, 'SOLVING REBAR CORROSION PROBLEMS IN CONCRETE', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2304, 1, 'WATER & SEWAGE WORKS', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2305, 1, 'ACTA PHYTOTAXONOMICA SINICA', '', 'Peiping, China, CN : Kexue Chubanshe', '05291526', NULL, 'Bimestral', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2307, 1, 'ZEITSCHRIFT FUR NATURFORSCHUNG, C. JOURNAL OF BIOSCIENCES', '', '', '0341-0382', NULL, '', '2015-09-01 14:24:52', '2015-09-01 14:24:56'),
(2308, 1, 'IEE PROCEEDINGS. VISION, IMAGE AND SIGNAL PROCESSING', '', 'London : Institution of Electrical Engineers, 1994-', '1350245X', NULL, 'Bimonthly', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2309, 1, 'AMERICAN JOURNAL OF SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2310, 1, 'ANNALS OF THE CARNEGIE MUSEUM', 'Ann. Carnegie Mus', 'Pittsburgh, Carnegie Museum Of Natural History', '00974463', NULL, 'Trimestral', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2311, 1, 'SUMMA PHYTOPATHOLOGICA', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2312, 1, 'KAGAKU KOGAKU RONBUNSHU', '', '', '0386-216X', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2313, 1, 'INTERNATIONAL JOURNAL OF FRACTURE', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2314, 1, 'JOURNAL OF THE MARINE AND BIOLOGICAL ASSOCIATION OF THE UNITED KINGDOM', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2315, 1, 'FOOD ADDITIVES AND CONTAMINANTS', '', 'London, GB : Taylor & Francis', '0265203X', NULL, 'Mensal', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2316, 1, 'INTERNATIONAL JOURNAL OF PROSTHODONTICS', 'Int J Prosthodont', '', '0893-2174', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2317, 1, 'CHEMISTRY OF FUNCTIONAL GROUPS. SUPPLEMENT B.', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2318, 1, 'PHILOSOPHICAL MAGAZINE B.', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2319, 1, 'JOURNAL OF PERINATAL MEDICINE', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2320, 1, 'JOURNAL OF MOLLUSCAN STUDIES', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2321, 1, 'ECOGRAPHY', '', 'Copenhagen : Munksgaard International Publishers, Ltd., c1992-', '09067590', NULL, 'Quarterly', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2322, 1, 'INTERNATIONAL JOURNAL OF INVERTEBRATE REPRODUCTION AND DEVELOPMENT', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2323, 1, 'NAUNYN-SCHMIEDEBERG\'S ARCHIVES OF PHARMACOLOGY', '', 'Belfast, Irlanda, IE : Springer Verlag', '00281298', NULL, 'Trimestral', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2324, 1, 'WAVELETS: THEORY, ALGORITHMS, AND APPLICATIONS', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2325, 1, 'INTERNATIONAL JOURNAL OF NON-LINEAR MECHANICS', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2326, 1, 'SYMPOSIA OF THE ZOOLOGICAL SOCIETY OF LONDON', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2327, 1, 'MARINE AND FRESHWATER RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2328, 1, 'WORLD JOURNAL OF MICROBIOLOGY & BIOTECHNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2329, 1, 'COMPUTER, VISION GRAPHICS AND IMAGE PROCESSING', 'Comput. Vis. Graph. Image Process.', 'New York, N.Y. : Academic Press, c1983-1990', '0734189X', NULL, 'Monthly // 4 vol. al año   *', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2330, 1, 'PATHOLOGY  ANNUAL', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2331, 1, 'CANADIAN VETERINARY JOURNAL', 'Can. Vet. J.', 'Ottawa, CA : Canadian Veterinary Medical Association', '00085286', NULL, 'Mensal', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2332, 1, 'VESTNIK CESKOSLOVENSKE SPOLECNOSTI ZOOLOGICKE.', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2333, 1, 'ELECTROANALYSIS', 'Electroanalysis', 'New York, N.Y. : VCH, c1989-', '10400397', NULL, 'Bimonthly; Eight issues yearly, 1990-', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2334, 1, 'NEUROCHEMISTRY INTERNATIONAL', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2335, 1, 'CANCER CELLS', '', 'Cold Spring Harbor, N.Y. : Cold Spring Laboratory, 1984-1989', '10422196', NULL, '1984/1-1989/7 ; Ceased in 1989', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2336, 1, 'ENVIRONMENTAL MICROBIOLOGY', 'Environ. Microbiol.', 'Oxford, Inglaterra, GB : Blackwell Science', '14622912', NULL, 'Mensal', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2337, 1, 'CLASSICAL PHILOLOGY', 'Classical Philol.', 'Chicago : University of Chicago Press, 1906-', '0009837X', NULL, 'Quarterly', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2338, 1, 'CRITICAL REVIEWS IN BIOCHEMISTRY AND MOLECULAR BIOLOGY', 'Crit. Rev. Biochem. Mol. Biol.', 'Boca Raton, Fla. : CRC Press, c1989-', '10409238', NULL, 'Bimonthly', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2339, 1, 'ANNUAL REVIEW OF BIOCHEMISTRY', 'Annu. rev. biochem', 'Palo Alto, Calif., Annual Reviews, inc. [etc.] // Stanford : Annual Review of Biochemistry , cop1932', '00664154', NULL, 'Annual', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2340, 1, 'JOURNAL OF ANATOMY', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2342, 1, 'JAPANESE JOURNAL OF MEDICAL SCIENCE AND BIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2343, 1, 'MICROCHEMICAL JOURNAL', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2344, 1, 'JOURNAL OF NEUROPHYSIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2345, 1, 'JOURNAL OF ORAL REHABILITATION', 'J Oral Rehabil', '', '1365-2842', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2346, 1, 'WAVES IN RANDOM MEDIA', 'Waves Random Media', 'Bristol, UK : IOP Pub. Ltd., c1991-', '09597174', NULL, 'Quarterly', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2347, 1, 'COMPTES RENDUS DES SEANCES DE LA SOCIETE DE BIOLOGIE ET DE SES FILIALES', 'C. R. Seances Soc. Biol. Fil.', 'Paris : Masson, cop1849', '00379026', NULL, 'Bimonthly', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2348, 1, 'AMERICAN JOURNAL OF PHYSIOLOGY: HEART AND CIRCULATORY PHYSIOLOGY', '', 'Bethesda, Md., US : American Physiological Society', '03636135', NULL, 'Mensal', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2349, 1, 'CLINICAL MICROBIOLOGY AND INFECTION', 'Clin. Microbiol. Infect.', 'Paris, FR : Decker', '1198743X', NULL, 'Mensal', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2350, 1, 'PROGRESS IN SURFACE AND MEMBRANE SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2351, 1, 'FOOD SCIENCE AND TECHNOLOGY INTERNATIONAL', 'Food Sci. Technol. Int.', 'London, GB : Chapman & Hall, cop1995', '10820132', NULL, 'Bimestral', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2352, 1, 'JOURNAL OF THE AMERICAN SOCIETY FOR HORTICULTURAL SCIENCE', '', '', '0003-1062', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2353, 1, 'ZEITSCHRIFT FUR PHYSIK. D, ATOMS MOLECULES AND CLUSTERS', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2354, 1, 'CARIBBEAN JOURNAL OF SCIENCE', 'Caribb. J. Sci.', 'Mayaguez, Puerto Rico : University of Puerto Rico, College Of Arts And Sciences, 1961-', '00086452', NULL, 'Quarterly', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2355, 1, 'ACTA CIENTIFICA VENEZOLANA', 'Acta cient. venez', '[Caracas] : Asociación Venezolana para el Avance de la Ciencia, [1950-', '00015504', NULL, 'Bimonthly', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2356, 1, 'STUDIES IN CONSERVATION', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2357, 1, 'ADVANCES IN PHARMACOLOGY (1990)', 'Adv. Pharmacol.', 'San Diego, Calif., US : Academic Press', '10543589', NULL, 'Anual', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2358, 1, 'REVUE ZOOLOGIQUE AFRICAINE', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2359, 1, 'REVUE ZOOLOGIQUE ET DE  BOTANIQUE AFRICAINES', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2360, 1, 'NOTES FROM THE LEYDEN MUSEUM', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2361, 1, 'BOLLETTINO DEI MUSEI DI ZOOLOGIA ED ANATOMIA COMPARATA DELLA R. UNIVERSITA DI TORINO', 'Boll. Mus. Zool. Anat. Comp. R. Univ. Torino', 'Torino, Italia, IT : Museo Di Zoologia // Accame, 1886-1942', '03934683', NULL, 'Irregular', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2362, 1, 'ZEITSCHRIFT FUR NATURFORSCHUNG. C: BIOSCIENCE', '', '', '0939-5075', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2363, 1, 'JOURNAL OF PESTICIDE SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2364, 1, 'ACTA ENDOCRINOLOGICA. SUPPLEMENTUM', 'Acta Endocrinol., Suppl.', 'Copenhagen : Ejnar Munksgaard,', '03009750', NULL, 'Monthly', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2365, 1, 'JOURNAL OF THE SOCIETY FOR  GYNECOLOGIC INVESTIGATION', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2366, 1, 'XENOBIOTICA', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2367, 1, 'THE NEW MICROBIOLOGICA', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2368, 1, 'CLINICAL CANCER RESEARCH', 'Clin. Cancer Res.', 'Philadelphia : Association for Cancer Research, c1995-', '10780432', NULL, 'Monthly', '2015-09-01 14:24:52', '2016-01-12 14:16:11'),
(2369, 1, 'REPORTS ON MATHEMATICAL PHYSICS', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2370, 1, 'ANIMAL PRODUCTION', '', 'Betchley, Inglaterra, GB: British Society of Animal Production', '00033561', NULL, 'Bimestral', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2371, 1, 'VETERINARY SURGERY', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2372, 1, 'THE JOURNAL OF GEOMETRIC ANALYSIS', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2373, 1, 'ANNALS OF THE RHEUMATIC DISEASES', 'Ann. Rheum. Dis.', 'London : British Medical Association , cop1939', '00034967', NULL, 'Anteriormente bimestral ; Mensual', '2015-09-01 14:24:52', '2016-01-12 14:16:12');
INSERT INTO `journal` (`id`, `instance_id`, `name`, `abbreviation`, `responsible`, `ISSN`, `ISSNE`, `frecuency`, `createdAt`, `updatedAt`) VALUES
(2374, 1, 'AGRICULTURAL AND FOREST METEOROLOGY', 'Agric. For. Meteorol.', 'Amsterdam, NL : Elsevier', '01681923', NULL, 'Trimestral', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2375, 1, 'JOURNAL OF INDUSTRIAL MICROBIOLOGY AND BIOTECHNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2376, 1, 'APPLIED GEOCHEMISTRY', 'Appl. Geochem.', 'Oxford ; New York : Pergamon Press, c1986-', '08832927', NULL, 'Bimonthly, Jan./Feb. 1986-91 ; 7 no. a year, <Jan. 1992->', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2377, 1, 'ANIMAL FEED SCIENCE AND TECHNOLOGY', 'Anim. Feed Sci. Technol.', 'Amsterdam [etc.] : Elsevier, 1976-', '03778401', NULL, 'Bimensual', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2378, 1, 'PESQUISA ODONTOLOGICA BRASILEIRA', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2379, 1, 'REVISTA DA FACULDADE DE ODONTOLOGIA DE BAURU', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2380, 1, 'QUINTESSENCE INTERNATIONAL', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2381, 1, 'DENTAL UPDATE', 'Dent. Update', 'Guildford, Inglaterra, GB : Update Publications', '03055000', NULL, 'Bimestral', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2382, 1, 'JOURNAL OF  ANTIMICROBIAL CHEMOTHERAPY', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2383, 1, 'TRANSACTIONS OF THE AMERICAN INSTITUTE OF MINING AND METALLURGICAL ENGINEERS', 'Trans. Am. Inst. Min. Metall. Eng.', '', '0096-4778', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2384, 1, 'JOURNAL OF METALS', '', '', '0148-6608', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2385, 1, 'JOURNAL OF CANCER RESEARCH AND CLINICAL ONCOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2387, 1, 'JOURNAL OF LUMINESCENCE', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2388, 1, 'MICROELECTRONIC ENGINEERING : AN INTERNATIONAL JOURNAL OF SEMICONDUCTOR MANUFACTURING TECHNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2389, 1, 'SEMINARS IN THORACIC AND CARDIOVASCULAR SURGERY', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2390, 1, 'CHAOS', 'Chaos', 'Woodbury, Ny, US : American Institute Of Physics', '10541500', NULL, 'Quarterly', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2391, 1, 'GREEN CHEMISTRY', 'Green Chem.', 'Cambridge, Inglaterra , GB : Royal Society Of Chemistry', '14639262', NULL, 'Bimestral', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2392, 1, 'EUROPEAN JOURNAL OF SOIL SCIENCE', 'Eur. J. Soil Sci.', 'Oxford : Published by Blackwell Scientific Publications and the British Society of Soil Science on b', '13510754', NULL, 'Trimestral', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2393, 1, 'REVISTA GAUCHA DE ODONTOLOGIA', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2394, 1, 'BRITISH DENTAL JOURNAL', 'Br. Dent. J.', 'London, GB : British Dental Association', '00070610', NULL, 'Semestral', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2395, 1, 'ENGINEERING ANALYSIS WITH BOUNDARY ELEMENTS', 'Eng. Anal. Bound. Elem.', 'Southampton : Computational Mechanics Publications, 1989-', '09557997', NULL, 'Mensual; Trimestral. 8 n. al año desde 1992', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2396, 1, 'BRAIN AND LANGUAGE', 'Brain Lang.', 'San Diego, Calif., US // New York, N.Y. : Academic Press', '0093934X', NULL, 'Quarterly 1974-1977 ; bimonthly 1978-', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2397, 1, 'BEHAVIORAL AND BRAIN SCIENCES', 'Behav. Brain Sci.', 'Cambridge, Inglaterra , GB // New York, N.Y.: Cambridge University Press, 1978-', '0140525X', NULL, 'Bimestral', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2398, 1, 'JOURNAL OF PHYSICS = FIZICHESKII ZHURNAL', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2399, 1, 'JOURNAL OF THE MARINE BIOLOGICAL ASSOCIATION OF INDIA', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2400, 1, 'CARIES RESEARCH', 'Caries Res.', 'Basel ; New York : S. Karger // European Organization for Caries Research, 1967-', '00086568', NULL, '6 no. a year <, 1983- >', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2401, 1, 'JOURNAL OF CLINICAL PERIODONTOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2402, 1, 'TRANSPORT IN POROUS MEDIA', '', '', '0169-3913', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2403, 1, 'NCI MONOGRAPHS', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2404, 1, 'JOURNAL OF ANIMAL MORPHOLOGY AND PHYSIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2405, 1, 'MARINE BIOLOGY LETTERS', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2406, 1, 'PEDIATRIC INFECTIOUS DISEASE JOURNAL', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2407, 1, 'IMMUNOLOGY AND CELL BIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2408, 1, 'CHEMOTERAPIA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2409, 1, 'AFRICAN PLANT PROTECTION', 'Afr. Plant Prot.', 'Arcadia, Africa Do Sul, ZA : Agricultural Research Council', '10233121', NULL, 'Semestral', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2410, 1, 'AGRICULTURAL NEWS', '', 'Bridgetown, Barbados, BB : Imperial Department Of Agriculture For The West Indies', '', NULL, 'Trimestral', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2411, 1, 'KOEDOE', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2412, 1, 'RIVISTA DI PARASSITOLOGIA', '', '', '0035-6387', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2413, 1, 'REVUE NEUROLOGIQUE', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2414, 1, 'DEUTSCHE MEDIZINISCHE WOCHENSCHRIFT', 'Dtsch. Med. Wochenschr.', 'Stuttgart, Alemanha, DE : Georg Thieme Verlag, cop1875', '00120472', NULL, 'Semanal', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2415, 1, 'DENTAL CLINICS OF NORTH AMERICA', 'Dent. Clin. North Am.', 'Philadelphia : W.B. Saunders, c1957-', '00118532', NULL, 'Quarterly', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2416, 1, 'JAPANESE JOURNAL OF APPLIED PHYSICS. PART 2 LETTERS', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2417, 1, 'EUROPEAN JOURNAL OF ORAL SCIENCES', '', 'Copenhahen : Munksgaard International Publishers, cop1893', '09098836', NULL, 'Bimestral', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2418, 1, 'NUCLEAR INSTRUMENTS & METHODS IN PHYSICS RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2419, 1, 'CIRCULATION RESEARCH', 'Circ. Res.', 'Baltimore, Md. : Grune & Stratton, c1953- // Dallas : American Heart Association , cop1953', '00097330', NULL, 'Monthly, 1962- // Bissemanal', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2420, 1, 'CHRONOBIOLOGIA', 'Chronobiologia', 'Milano, Italia : Il Ponte, 1974- // International Society For Chronobiologia', '03900037', NULL, 'Quarterly', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2421, 1, 'JOURNAL OF ATMOSPHERIC AND SOLAR-TERRESTRIAL PHYSICS', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2422, 1, 'ZOOLOGISKA BIDRAG FRAN UPPSALA', 'Zool. Bidr. Uppsala.', '\'\'', '0373-0964', NULL, '\'\'', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2423, 1, 'ADVANCED MATERIALS', 'Adv. mat. (Weinh.) // Adv. Mater.', 'Deerfield Beach, FL [etc.] : VCH Publishers, 1989- // Weinheim, Alemanha, DE : Vch Verlagsgesellscha', '09359648', NULL, '15 no. a year, <1997-> Monthly, 1989- //', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2424, 1, 'PATHOLOGY INTERNATIONAL', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2425, 1, 'DEVELOPMENTAL BIOLOGY', 'Dev. Biol.', 'San Diego, Calif., US // New York, N.Y. : Academic Press, 1959-', '00121606', NULL, 'Frequency varies 1959-1981; monthly, <Jan. 1982->', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2426, 1, 'GENES AND DEVELOPMENT', 'Genes Dev.', 'New York : Genetical Society of Great Britain, cop1987', '08909369', NULL, 'Monthly', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2427, 1, 'HYPERTENSION IN PREGNANCY', 'Hypertens. Pregnancy', 'New York : Marcel Dekker, Inc.,', '10641955', NULL, 'Three issues a year', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2428, 1, 'MEMOIRS OF NATIONAL INSTITUTE OF POLAR RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2429, 1, 'ARCHIV FÜR NATURGESCHICHTE. A, ORIGINAL ARBEITEN', '', 'Berlin, DE : [S.N.]', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2430, 1, 'REVISTA DE LA ACADEMIA COLOMBIANA DE CIENCIAS EXACTAS, FISICAS Y NATURALES', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2431, 1, 'THERIOGENOLOGY', 'Theriogenology', 'Stoneham, Mass., US : Butterworths', '0093691X', NULL, 'Mensal', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2432, 1, 'YOGYO KYOKAI SHI', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2433, 1, 'JOURNAL OF BIOMECHANICS', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2434, 1, 'POLYMERS PAINT AND COLOUR JOURNAL', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2435, 1, 'POLYHEDRON: THE INTERNATIONAL JOURNAL FOR INORGANIC AND ORGANOMETALLIC CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2436, 1, 'MEDICINE', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2437, 1, 'CLINICAL INFECTIOUS DISEASES', 'Clin. Infect. Dis.', 'Chicago, IL : The University of Chicago Press, c1992-', '10584838', NULL, 'Monthly // Bimensal', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2438, 1, 'REVISTA MEDICA DEL IMSS', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2439, 1, 'JOURNAL OF AGRICULTURAL RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2440, 1, 'STUDIA PATRISTICA', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2441, 1, 'JOURNAL OF THE CHEMICAL SOCIETY B. PHYSICAL ORGANIC', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2442, 1, 'CURRENT OPINION IN COLLOID AND INTERFACE SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2443, 1, 'JOURNAL OF HYDRAULIC ENGINEERING', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2444, 1, 'WATER ENVIRONMENT AND TECHNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2445, 1, 'PHILOSOPHY AND RHETORIC', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2446, 1, 'LABORATORY ANIMAL SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2447, 1, 'INSECT BIOCHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2448, 1, 'ACTA PARASITOLOGICA POLONICA', 'Acta Parasitol. Pol.', 'Warszawa, PL : Polskie Towarzystwo Parazytologiczne', '00651478', NULL, 'Trimestral', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2449, 1, 'APPLIED PHYSICS. A, MATERIALS SCIENCE & PROCESSING', '', 'Berlin : Springer-Verlag, c1995-', '07217250', NULL, 'Monthly', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2450, 1, 'JOURNAL OF HAZARDOUS MATERIALS', 'J. Hazard. Mater.', 'Amsterdam, NL : Elsevier Scientific Pub. Co., 1975-', '03043894', NULL, 'Trimestral', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2451, 1, 'AUSTRALIAN SCIENCE TEACHERS JOURNAL', 'Aust. Sci. Teach. J.', 'Melbourne, Australia, AU : Australian Science Teachers Association', '00450855', NULL, 'Trimestral', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2452, 1, 'JOURNAL OF TROPICAL ECOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2453, 1, 'CLINICAL ANATOMY', 'Clin. Anat.', 'New York, US : Wiley-Liss', '08973806', NULL, 'Bimestra', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2454, 1, 'EPRI REPORT', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2455, 1, 'ZOON', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2456, 1, 'JOURNAL OF FOOD BIOCHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2457, 1, 'AUSTRALIAN JOURNAL OF ZOOLOGY', 'Aust. J. Zool.', 'Victoria, Australia, AU // Melbourne, Australia : Commonwealth Scientific and Industrial Research Or', '0004959X', NULL, 'Irregular, 1953-<June 1955> ; quarterly <Feb. 1976-> ; Six no. a year <1979- >', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2458, 1, 'NEW FORESTS', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2459, 1, 'ACTA PARASITOLOGICA', 'Acta Parasitol.', ' Warsaw, PL : Witold Stefanski Institute Of Parasitology', '12302821', NULL, 'Trimestral', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2460, 1, 'ANGEWANDTE PARASITOLOGIE', 'Angew Parasitol', 'Jena, Alemanha, DE: Gustav Fischer Verlag', '0003-3162', NULL, 'Trimestral', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2461, 1, 'AIP CONFERENCE PROCEEDINGS', 'Aip Conf. Proc.', 'New York, US : American Institute Of Physics', '0094243X', NULL, 'Irregular', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2462, 1, 'PARASITOLOGICHESKII SBORNIC', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2463, 1, 'CYTOGENETICS AND CELL GENETICS', 'Cytogenet. Cell Genet.', 'Basel ; New York, N.Y. : S. Karger, 1973-', '03010171', NULL, '6 no. a year; monthly <1980->', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2464, 1, 'JOURNAL OF CONTAMINANT HYDROLOGY', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2465, 1, 'IEEE TRANSACTIONS ON EDUCATION', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2466, 1, 'AUSTRALIAN JOURNAL OF SCIENCE', 'Aust. J. Sci.', 'Sydney : Australian National Research Council, 1938-1970', '03653668', NULL, 'Bimonthly 1938-<June 1944> ; Monthly', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2467, 1, 'BIOTECHNOLOGY PROGRESS', 'Biotechnol. Prog.', 'New York, US : American Institute Of Chemical Engineers, c1985-', '87567938', NULL, 'Quarterly, 1985-1989 ; Bimonthly, 1990-', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2468, 1, 'JOURNAL OF APPLIED TOXICOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2469, 1, 'COLLOQUIUM MATHEMATICUM', 'Colloq. Math.', 'Warszawa : Polska Akademia Nauk, Instytut Matematyczny // Editions Scientifiques de Pologne, 1947-', '00101354', NULL, 'Irregular', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2470, 1, 'SURFACES', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2471, 1, 'PAINT & INK INTERNATIONAL', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2472, 1, 'CANADIAN JOURNAL OF BIOCHEMISTRY AND PHYSIOLOGY', 'Can. J. Biochem. Physiol.', 'Ottawa, CA : National Research Council Of Canada', '05765544', NULL, 'Bimonthly, 1954-56 ; Mensal', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2473, 1, 'NETHERLANDS MILK AND DAIRY JOURNAL', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2474, 1, 'TRENDS IN FOOD SCIENCE AND TECHNOLOGY', '', '', '0924-2244', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2475, 1, 'PARASSITOLOGIA', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2476, 1, 'GALVANOTECHNIK', '', 'Saulgau, Alemanha, DE : Eugen G. Leuze Verlag', '00164232', NULL, 'Mensal', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2477, 1, 'GLYCOCONJUGATES', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2478, 1, 'REPORT OF THE NEW YORK STATE VETERINARY COLLEGE AT CORNELL UNIVERSITY', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2479, 1, 'JOURNAL OF PEDIATRIC NURSING', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2480, 1, 'APPLIED ORGANOMETALLIC CHEMISTRY', 'Appl. Organomet. Chem.', 'Sussex, Inglaterra, GB : John Wiley & Sons', '02682605', NULL, 'Bimestral', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2481, 1, 'DIVISION OF PETROLEUM CHEMISTRY', 'Prepr. / Div. Pet. Chem., Am. Chem. Soc.', 'Washington, US : American Chemical Society, Division Of Petroleum Chemistry', '05693799', NULL, 'Trimestral', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2482, 1, 'ACTA ODONTOLOGICA SCANDINAVICA', 'Acta Odontol. Scand.', 'Oslo : Scandinavian University Press, 1939-', '00016357', NULL, 'Actual: Bimestral', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2483, 1, 'ARQUIVOS DO INSTITUTO DE PESQUISAS AGRONOMICAS DE PERNAMBUCO', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2484, 1, 'CURRENT MICROBIOLOGY', 'Cur. Microbiol.', 'New York, Springer-Verlag 1978-', '03438651', NULL, 'Twelve no. a year, 1978-; 6 issues per year <, 1982->', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2485, 1, 'REVISTA DE BIOLOGIA TROPICAL', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2486, 1, 'JOURNAL OF THE COLLEGE OF DAIRIYING', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2487, 1, 'SOUTHWESTERN NATURALIST', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2488, 1, 'ADVANCES IN ECOLOGICAL RESEARCH', 'Adv. ecol. res', 'London, New York, Academic Press', '00652504', NULL, 'Irregular', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2489, 1, 'MEDICINAL RESEARCH REVIEWS', 'Med Res Rev', '', '0198-6325  (e): 1098-1128', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2490, 1, 'EUROPEAN JOURNAL OF CLINICAL NUTRITION', 'Eur. J. Clin. Nutr.', 'London, GB : John Libbey Eurotext //  Houndmills : Nature Publishing Group , 1988', '09543007', NULL, 'Monthly', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2491, 1, 'MICROWAVE AND OPTICAL TECHNOLOGY LETTERS', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2492, 1, 'JOURNAL OF MORPHOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2493, 1, 'EUROPEAN UROLOGY', 'Eur. Urol.', 'Basel, Suica, CH : S. Karger', '03022838', NULL, 'Bimestral', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2494, 1, 'FOLIA MORPHOLOGICA', '', 'Warszawa, PL : Panstwowy Zaklad Wydawnictw Lekarskich', '00155659', NULL, 'Trimestral', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2495, 1, 'QUINTESSENCE : PUBLICACION INTERNACIONAL DE ODONTOLOGIA', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2496, 1, 'EDUCACION MEDICA Y SALUD', 'Educ. Med. Salud', 'Washington : Oficina Sanitaria Panamericana, Oficina Regional de la Organización Mundial de la Salud', '00131091', NULL, 'Trimestral', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2497, 1, 'JOURNAL OF ELECTROANALYTICAL CHEMISTRY (LAUSANNE, SWITZERLAND)', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2498, 1, 'BIOSENSORS AND BIOELECTRONICS', 'Biosens. Bioelestron.', 'Barking, Essex, England : Elsevier Applied Science, 1989-', '09565663', NULL, 'Six issues a year, 1989- ; Six issues a year, 1989- ; 12 issues a year, <1997->', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2499, 1, 'REVISTA CERES', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2500, 1, 'JOURNAL OF THE OPTICAL SOCIETY OF AMERICA. A, OPTICS AND IMAGE SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2501, 1, 'BIOSENSORS', '', 'Essex, Inglaterra, GB : Elsevier Applied Science', '0265928X', NULL, 'Bimestral', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2502, 1, 'NEDERLANDS TIJDSCHRIFT VOOR GENEESKUNDE', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2503, 1, 'ARCHIVES OF INTERNAL MEDICINE', 'Arch. intern. med.', 'Chicago : American Medical Association , cop1960', '00039926', NULL, 'Mensual', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2504, 1, 'AGRONOMIA TROPICAL', 'Agron. Trop.', 'Maracay, Venezuela, VE; Maracay, Venezuela, VE : Instituto Nacional De Agricultura , Centro De Inves', '0002192X', NULL, 'Bimestral', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2505, 1, 'PLANT DISEASE REPORTER', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2506, 1, 'AGRONOMIA COSTARRICENSE', 'Agron. costarric', 'San Jose, Costa Rica : Universidad de Costa Rica, 1977-', '03779424', NULL, 'Semiannual', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2507, 1, 'COMPTES RENDUS', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2508, 1, 'IEEE TRANSACTIONS ON MEDICAL IMAGING', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2509, 1, 'INDIAN JOURNAL OF HORTICULTURE', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2510, 1, 'JOURNAL OF AQUATIC ANIMAL HEALTH', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2511, 1, 'LIMNOLOGY AND OCEANOGRAPHY', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2512, 1, 'STUDIES ON NEOTROPICAL FAUNA AND ENVIROMENT', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2513, 1, 'ADVANCES IN CANCER RESEARCH', 'Adv. cancer res', 'Editors: 1953- J. P. Greenstein, A. Haddow // New York, US : Academic Press', '0065230X', NULL, 'Mensal', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2514, 1, 'CURRENT OPINION IN CELL BIOLOGY', 'Curr. Opin. Cell Biol.', 'Philadelphia, PA, USA: Current Science, c1988- // London, GB : Current Biology', '09550674', NULL, 'Bimonthly', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2515, 1, 'PEDIATRIC NEUROSURGERY', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2516, 1, 'FACIAL PLASTIC SURGERY', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2517, 1, 'JOURNAL OF BIOGEOGRAPHY', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2518, 1, 'OXFORD SURVEYS IN EVOLUTIONARY BIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2519, 1, 'PEDIATRIC SURGERY INTERNATIONAL', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2520, 1, 'NATURE MEDICINE', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2521, 1, 'VETERINARY QUARTERLY', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2522, 1, 'MEDICAL MICROBIOLOGY AND IMMUNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2523, 1, 'ADVANCES IN HETEROCYCLIC CHEMISTRY', '', 'New York, Academic Press, 1963-', '00652725', NULL, 'Frequency varies', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2524, 1, 'PRODUCTS FINISHING', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2525, 1, 'MODERN PAINT AND COATINGS', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2526, 1, 'AMERICAN INKMAKER', 'Am. inkmak', 'New York : MacNair-Dorland Co.', '00028916', NULL, 'Monthly', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2527, 1, 'PHILIPPINE JOURNAL OF SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2528, 1, 'NORTH AMERICAN VETERINARIAN', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2529, 1, 'ANALES DE LA FACULTAD DE VETERINARIA DE LEON', 'An. Fac. Vet. Leon', 'Oviedo : Universidad de Oviedo , cop1955 // Leon', '03731170', NULL, 'Anual', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2530, 1, 'WORLD ARCHAEOLOGY', '', '', '0043-8243', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2531, 1, 'OIL AND PETROCHEMICAL POLLUTION', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2532, 1, 'JOURNAL OF COMPARATIVE PHYSIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2533, 1, 'ANIMAL GENETICS', 'Anim. Genet.', '[Oxford, England] : Blackwell Scientific Publications', '02689146', NULL, 'Bimestral', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2534, 1, 'INTERNATIONAL JOURNAL OF HYDROGEN ENERGY', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2535, 1, 'PLANT PHYSIOLOGY AND BIOCHEMISTRY', 'Plant Physiol Biochem', '', '0981-9428', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2536, 1, 'PHARMACOLOGICAL RESEARCH COMMUNICATIONS', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2537, 1, 'ENDOCRINOLOGY', 'Endocrinology', 'Baltimore [etc.] : Williams and Wilkins // Philadelphia, Pa. : Published for the Endocrine Society b', '00137227', NULL, 'Monthly', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2538, 1, 'JOURNAL OF CELLULAR BIOCHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2539, 1, 'BIOCATALYSIS', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2540, 1, 'CURRENT OPINION IN GENETICS AND DEVELOPMENT', 'Curr. Opin. Genet. Dev.', 'London, UK : Current Biology Ltd., c1991-', '0959437x', NULL, 'Bimonthly', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2541, 1, 'MICROBES AND INFECTION', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2542, 1, 'ACTA CHEMICA SCANDINAVICA. SERIES B: ORGANIC CHEMISTRY AND BIOCHEMISTRY', 'Acta chem. Scand., Ser. B. Org. chem. & biochem', 'Copenhagen : Munksgaard, 1974-1988', '03024369', NULL, 'Ten no. a year', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2543, 1, 'JOURNAL OF SOUTH AMERICAN EARTH SCIENCES', '', '', '0895-9811', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2544, 1, 'CELLULAR MICROBIOLOGY', 'Cell. Microbiol.', 'Oxford, Inglaterra, GB : Blackwell Science', '14625814', NULL, 'Mensal', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2545, 1, 'GLYCOBIOLOGY', 'Glycobiology', 'Cary, Nc, US : Oxford University Press', '0959-6658 (e): 1460-2423', NULL, 'Mensal', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2546, 1, 'VISION RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2547, 1, 'JOURNAL OF HORTICULTURAL SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2548, 1, 'DEUTSCHE TIERARZTLICHE WOCHENSCHRIFT', '', 'Hannover, Alemanha, DE : Verlag M. Und H. Schaper', '03416593', NULL, 'Irregular', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2549, 1, 'INTERNATIONAL JOURNAL OF TECHNOLOGY MANAGEMENT', '', '', '0267-5730', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2550, 1, 'INTERNATIONAL ANGIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2551, 1, 'JOURNAL OF MAMMALIAN EVOLUTION', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2552, 1, 'VETERINARIA (MEXICO)', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2553, 1, 'CURRENT EYE RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2554, 1, 'ENVIRONMENT INTERNATIONAL', 'Environ. Int.', 'Elmsford, Ny, US : Pergamon Press', '01604120', NULL, 'Bimestral', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2555, 1, 'TENSIDE DETERGENTS', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2556, 1, 'BIOELECTROCHEMISTRY AND BIOENERGETICS', 'Bioelectrochem. Bioenerg.', 'Basel : Birkhäuser // Lausanne, Suica, CH : Elsevier Sequoia', '03024598', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2557, 1, 'DEUTSCHE MILCHWIRTSCHAFT', 'Quinzenal', 'Hildesheim, Alemanha, DE : Zentralverband Deutscher Molkereifachleute U. Milchwirtschaftler', '00120480', NULL, 'Dtsch. Milchwirtsch.', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2558, 1, 'SOUTH AFRICAN JOURNAL OF PLANT AND SOIL', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2559, 1, 'INTERNATIONAL JOURNAL OF ADHESION AND ADHESIVES', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2560, 1, 'HYOMEN GIJUTSU', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2561, 1, 'AUSTRALIAN JOURNAL OF EARTH SCIENCES', 'Aust. J. Earth Sci.', 'Victoria, Australia, AU // Sydney, Aust. : Geological Society of Australia, 1984-', '08120099', NULL, 'Quarterly', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2562, 1, 'ANALES DE MEDICINA INTERNA', 'An. Med. Interna', 'Madrid : C y S , cop1983', '02127199', NULL, 'Mensual', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2563, 1, 'INSECT BIOCHEMISTRY AND MOLECULAR BIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2564, 1, 'PHYSIOLOGICAL ENTOMOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2565, 1, 'ACTA BIOTECHNOLOGICA', 'Acta biotechnol.', 'Alemania : Akal-Verl., 1980-', '01384988', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2566, 1, 'PLANT CELL', '', '', '1040-4651', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2567, 1, 'NOVARTIS FOUNDATION SYMPOSIUM', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2568, 1, 'JOURNAL OF PHYSICS D, APPLIED PHYSICS', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2569, 1, 'REGULATORY PEPTIDES', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2570, 1, 'JOURNAL OF CELL SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2571, 1, 'CELLULAR PHYSIOLOGY AND BIOCHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2572, 1, 'PROGRESS IN INORGANIC CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2573, 1, 'JOURNAL OF PLANT NUTRITION', '', '', '', NULL, '', '2015-09-01 14:24:52', '2016-01-12 14:16:12'),
(2574, 1, 'KITASATO ARCHIVES OF EXPERIMENTAL MEDICINE', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2575, 1, 'CATALYSIS REVIEWS. SCIENCE AND ENGINEERING', 'Catal. Rev., Sci. Eng.', 'New York, N.Y. : M. Dekker, 1974-', '01614940', NULL, 'Five no. a year (2 vols.) <, 1980->; 4 no. a year <, Mar. 1984->', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2576, 1, 'APPLIED CATALYSIS', 'Appl. catal.', 'Amsterdam : Elsevier Scientific Pub. Co., 1981-1991', '01669834', NULL, 'Bimonthly (some issues combined)', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2577, 1, 'EUROPEAN JOURNAL OF EPIDEMIOLOGY', 'Eur. J. Epidemiol.', 'Dordrecht: Kluwer Academic Publishers, 1985-', '03932990', NULL, 'Ten issues yearly', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2578, 1, 'REVISTA DE EDUCACION (MADRID)', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2579, 1, 'FOOD BIOTECHNOLOGY', 'Food Biotechnol.', 'New York, US : Marcel Dekker', '08905436', NULL, 'Quadrimestral', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2580, 1, 'JOURNAL OF SOLID-PHASE BIOCHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2581, 1, 'AMERICAN JOURNAL OF ENOLOGY', '', 'Reedley, Calif., US : American Society Of Enology', '', NULL, 'Trimestral', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2582, 1, 'AMERICAN JOURNAL OF ENOLOGY AND VITICULTURE', 'Am. J. Enol. Vitic.', 'Davis, Calif., US : American Society Of Enologists', '00029254', NULL, 'Trimestral', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2583, 1, 'JOURNAL OF SENSORY STUDIES', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2584, 1, 'BUNSEKI KAGAKU = JAPAN ANALYST', 'Bunseki Kagaku', '[Tokyo] : Nihon Bunseki Kagakkai, 1952-', '05251931', NULL, 'Quarterly, 1952-  ; Monthly, 19 -', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2585, 1, 'ACTA PHYSICA POLONICA. A, GENERAL PHYSICS, SOLID STATE PHYSICS, APPLIED PHYSICS', '', 'Warszawa : Panstwowe Wydawnictwo Naukowe, 1970-', '05874246', NULL, 'Mensal', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2586, 1, 'ZHURNAL OBSHCHEI KHIMII', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2587, 1, 'VESTNIK MOSKOVSKOGO UNIVERSITETA. SERRIA II, KHIMIIA', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2588, 1, 'PREPRINTS AMERICAN CHEMICAL SOCIETY DIVISION PETROLEUM CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2589, 1, 'SOLID STATE CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2590, 1, 'SPECIAL PUBLICATION/ THE CHEMICAL SOCIETY', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2591, 1, 'JOURNAL OF THE AIR AND WASTE MANAGEMENT ASSOCIATION', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2592, 1, 'AVIAN PATHOLOGY', 'Avian Pathol.', 'Huntingdon, Inglaterra, GB: World Veterinary Poultry Association', '03079457', NULL, 'Bimestral', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2593, 1, 'MARINE POLLUTION BULLETIN', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2594, 1, 'JOURNAL OF THE AMERICAN  ANIMAL HOSPITAL ASSOCIATION', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2595, 1, 'urol int', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2596, 1, 'UROLOGIA INTERNATIONALIS', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2597, 1, 'BJU INTERNATIONAL', 'Bju Int.', 'Edinburgh, Inglaterra, GB // Oxford, UK : Blackwell Science', '14644096', NULL, 'Eighteen no. a year', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2598, 1, 'ARQUIVO BRASILEIRO DE MEDICINA VETERINARIA E ZOOTECNIA', 'Arq. Bras. Med. Vet. Zootec.', 'Belo Horizonte, MG : Ufmg, Escola De Veterinaria', '01020935', NULL, 'Bimestral', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2599, 1, 'JOURNAL OF REPRODUCTION AND DEVELOPMENT', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2600, 1, 'HYBRIDOMA', '', 'New York : M.A. Liebert, Inc., c1981-', '0272457X', NULL, 'Bimestral', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2601, 1, 'BULLETIN DE L\'ACADEMIE NATIONALE DE MEDECINE', 'Bull. Acad. Natl. Med.', 'Paris, FR : Masson', '00014079', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2602, 1, 'BIOCHEMICAL GENETICS', 'Biochem. Genet.', 'New York, N.Y. : Plenum Press, 1967-', '00062928', NULL, 'Bimonthly 1975-', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2603, 1, 'JOURNAL OF THE COLLEGE SCIENCE TEACHING', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2604, 1, 'MINERALIUM DEPOSITA', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2605, 1, 'JOURNAL OF AGRONOMY AND CROP SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2606, 1, 'GREAT BASIN NATURALIST MEMOIRS', 'Gt. Basin Nat. Mem.', 'Provo, Utah, US : Brigham Young University', '0160239X', NULL, 'Irregular', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2607, 1, 'JOURNAL OF SOLAR ENERGY ENGINEERING', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2608, 1, 'JOURNAL OF THE ACADEMY OF NATURAL SCIENCES OF PHILADELPHIA', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2609, 1, 'CHEMISTRY AND BIOLOGY', 'Chem. Biol.', 'Cambridge, Mass., US: Cell Press', '10745521', NULL, 'Mensal', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2610, 1, 'JOURNAL OF NATURAL TOXINS', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2611, 1, 'THE LONDON, EDINBURGH AND DUBLIN PHILOSOPHICAL MAGAZINE AND JOURNAL OF SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2612, 1, 'JOURNAL OF THE SOUTH AFRICAN INSTITUTE OF MINING & METALLURGY.', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2613, 1, 'NATURE GENETICS', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2614, 1, 'SEPARATION AND PURIFICATION TECHNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2615, 1, 'SURFACE AND INTERFACE ANALYSIS', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2616, 1, 'HUMAN REPRODUCTION', 'Hum. Reprod.', 'Oxford, Inglaterra, GB : Oxford University Press // IRL Press', '02681161', NULL, 'Mensal', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2617, 1, 'MOLECULAR GENETICS AND METABOLISM', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2618, 1, 'HADRONIC JOURNAL', 'Hadron. J.', 'Nonatum, Mass., US : Hadronic Press', '01625519', NULL, 'Bimestral', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2619, 1, 'FITOPATOLOGIA BRASILEIRA', 'Fitopatol. Bras.', 'Brasilia, DF : Sociedade Brasileira De Fitopatologia', '01004158', NULL, 'Quadrimestral', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2620, 1, 'JOURNAL/ AMERICAN WATER WORKS ASSOCIATION', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2621, 1, 'ACTA GENETICAE MEDICAE ET GEMELLOLOGIAE', '', '', '11209623', NULL, 'Trimestral', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2622, 1, 'LEBENSMITTEL, WISSENSCHAFT UND TECHNOLOGIE = FOOD SCIENCE AND TECHNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2623, 1, 'ACTA ALIMENTARIA POLONICA', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2624, 1, 'CHEMICAL ENGINEERING RESEARCH  AND DESIGN', 'Chem. Eng. Res. Des.', 'Rugby [Warwickshire] : The Institution ; Elmsford, N.Y. : distributed by Pergamon, c1983-', '02638762', NULL, 'Six times a year', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2625, 1, 'MATERIALS SCIENCE & ENGINEERING. C', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2627, 1, 'WORLD CONGRESS III OF CHEMICAL ENGINEERING', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2628, 1, 'BIODRUGS', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2629, 1, 'AGRICULTURA TROPICAL (BOGOTA)', 'Agric. Trop.', 'Bogota, CO : Asociación Colombiana De Ingenieros Agrónomos', '03652793', NULL, 'Trimestral', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2630, 1, 'SYLLOGE FUNGORUM', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2631, 1, 'DIE NAHRUNG', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2632, 1, 'ANNALES MYCOLOGICI', 'Ann. Mycol.', 'Berlin, DE : Natura, Buchhandlung Fur Naturkunde U. Exakte Wiss. Budy', '01763970', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2633, 1, 'BOLETIN DE PATOLOGIA VEGETAL Y ENTOMOLOGIA AGRICOLA (MADRID)', '', 'Madrid, ES : Instituto Nacional De Investigaciones Agronomicas', '03662381', NULL, 'Anual', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2634, 1, 'BULLETIN OF THE TORREY BOTANICAL CLUB', 'Bull. Torrey Bot. Club', 'Lawrence, KS [etc] // New York, US : Torrey Botanical Club', '00409618', NULL, 'Bimonthly ; Quarterly, <1992>-1996', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2635, 1, 'BIOLOGICO', 'Biologico', 'Sao Paulo, SP : Instituto Biologico', '03660567', NULL, 'Semestral', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2636, 1, 'CONCHOLOGIA ICONICA', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2637, 1, 'ORGANIC LETTERS', '', '', '1523-7060', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2638, 1, 'EUROPEAN JOURNAL OF FOOD RESEARCH AND TECHNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2639, 1, 'ITALIAN JOURNAL OF FOOD SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2640, 1, 'ZOOLOGICAL JOURNAL', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2641, 1, 'BULLETIN DE LA SOCIETE PHILOMATHIQUE DE PARIS', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2642, 1, 'ENVIRONMENTAL BIOLOGY OF FISHES', 'Environ. Biol. Fishes', 'Dordrecht : Norwell, MA [etc.], Kluwer Academic Publishers [etc.], 1976-', '03781909', NULL, 'Quarterly, <Sept. 29, 1980->; 8 issues yearly, <1983->; Monthly, <1994->', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2643, 1, 'INTERNATIONAL BIODETERIORATION & BIODEGRADATION', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2644, 1, 'REVIEW OF APPLIED MYCOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2645, 1, 'CLAYS AND CLAY MINERALS', 'Clays and Clay Miner.', 'Long Island City, N.Y.: Pergamon Press, 1968- // New York, US : Clay Minerals Society', '00098604', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2646, 1, 'PHASE TRANSITIONS', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2647, 1, 'TRENDS IN PLANT SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2648, 1, 'MEDICAL PHYSICS', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2649, 1, 'REVISTA  DE AGROQUIMICA Y TECNOLOGIA DE ALIMENTOS', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2650, 1, 'REVISTA DE AGROQUIMICA Y TECNOLOGIA DE ALIMENTOS', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2651, 1, 'AMERICAN JOURNAL OF PHARMACEUTICAL EDUCATION', 'Am. J. Pharm. Educ.', 'Bethesda, Md. [etc.] American Association of Colleges of Pharmacy // Alexandria, Va., US', '00029459', NULL, '5 no. a year', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2652, 1, 'INDIAN DRUGS', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2653, 1, 'SEPARATION SCIENCE AND TECHNOLOGY', 'Separ Sci Tech', '', '0149-6395 (e): 1520-5754', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2654, 1, 'NANOTECHNOLOGY', NULL, NULL, '0957-4484', '1361-6528', NULL, '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2655, 1, 'ACTA PHARMACEUTICA SUECICA', 'Acta pharm. Suec.', 'Stockholm : Apotekarsocieteten // Stockholm : Swedish Pharmaceutical Press , 1964-1988', '00016675', NULL, 'Six no. a year // Bimestral', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2656, 1, 'FISHERIES', 'Fisheries (Bethesda)', 'Bethesda, Md., US : American Fisheries Society, 1976-', '03632415', NULL, 'Bimestral', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2657, 1, 'JOURNAL OF GREAT LAKES RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2658, 1, 'ORGANOMETALLICS', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2659, 1, 'ZEITSCHRIFT FUR NATURFORSCHUNG. TEIL A , PHYSIK, PHYSICALISCHE CHEMIE, KOSMOPHYSIK', '', '', '0340-4811', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2660, 1, 'JOURNAL OF VEGETATION SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2661, 1, 'SURVEYS ON MATHEMATICS FOR INDUSTRY', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2662, 1, 'MATHEMATICAL MODELS AND METHODS IN APPLIED SCIENCES', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:12'),
(2663, 1, 'CURRENT TOPICS IN MICROBIOLOGY AND IMMUNOLOGY', 'Cur. Top. Microbiol. Immunol.', 'Berlin, DE : Springer Verlag', '0070217X', NULL, 'Irregular', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2664, 1, 'PROCEEDINGS OF THE NUTRITION SOCIETY', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2665, 1, 'CHEMICAL JOURNAL OF CHINESE UNIVERSITIES', 'G¯aodeng xuéxiào huàxué xuébaò', 'Ch`ang-ch`un [China] : Kao teng hsüeh hsiao hua hsüeh hsüeh pao, 1980-', '02510790', NULL, 'Quarterly', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2666, 1, 'ARCHIVES OF NEUROLOGY', 'Arch. neurol.', 'Chicago, Ill. : American Medical Association, 1960-', '00039942', NULL, 'Monthly', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2667, 1, 'JETP LETTERS', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2668, 1, 'RADIOPHYSICS AND QUANTUM ELECTRONICS', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2669, 1, 'EUROPEAN JOURNAL OF LIPID SCIENCE & TECHNOLOGY', '', 'Weinheim, Alemanha, DE : Wiley-Vch', '14387697', NULL, 'Mensal', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2670, 1, 'ALZHEIMER DISEASE AND ASSOCIATED DISORDERS', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2671, 1, 'CURRENTS DRUG TARGETS', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2672, 1, 'CURRENT DRUG TARGETS', 'Curr. Drug Targets', 'Hilversum, Netherlands ; Boca Raton, FL : Bentham Science Publishers, c2000-', '13894501', NULL, 'Bimensual', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2673, 1, 'ANNALS OF HUMAN BIOLOGY', '', 'London, GB : Society For The Study Of Human Biology', '03014460', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2674, 1, 'THE MONIST', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2675, 1, 'Preparation of catalysts III', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2676, 1, 'RADIATION PHYSICS AND CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2677, 1, 'WASTE MANAGEMENT AND RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2678, 1, 'HORA VETERINARIA', 'Hora Vet.', 'Porto Alegre, RS : A Hora Veterinaria', '01019163', NULL, 'Bimestral', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2679, 1, 'PROGRESS IN  MOLECULAR AND SUBCELLULAR BIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2680, 1, 'SCANDINAVIAN JOURNAL OF MEDICINE AND SCIENCE IN SPORTS', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2681, 1, 'AMERICAN MUSEUM NOVITATES', 'Am. Mus. Novit.', 'New York, American Museum of Natural History', '00030082', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2682, 1, 'MAYO CLINIC PROCEEDINGS', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2683, 1, 'WATER, AIR, AND SOIL POLLUTION', 'Water air soil pollut', 'Dordrecht : D. Reidel Pub. Co, 1971-', '00496979', NULL, 'Quarterly 1971-1982 ; 8 no. a year , 1982- ;  20 no. a year 1986-', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2684, 1, 'JOURNAL OF BIOSOCIAL SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2685, 1, 'JOURNAL OF DYNAMICAL AND CONTROL SYSTEMS', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2686, 1, 'PHYSICS REPORTS', '', '', '0370-1573', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2687, 1, 'SUPPLEMENTO AL NUOVO CIMENTO', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2688, 1, 'AUSTRALIAN JOURNAL OF STATISTICS', 'Aust. J. Stat.', 'Sydney: Statistical Society of Australia, 1959-1997', '00049581', NULL, 'Three no. a year', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2689, 1, 'ANNALS OF STATISTICS', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2690, 1, 'JOURNAL OF CHEMICAL RESEARCH. SYNOPSES', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2691, 1, 'TROPICAL AGRICULTURE', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2692, 1, 'JOURNAL OF THE CHEMICAL SOCIETY OF JAPAN = NIPPON KAGAKU KAISHI', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2693, 1, 'APPLIED INDUSTRIAL CATALYSIS', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2694, 1, 'ACTA PHYSICA SINICA', '', 'Beijing, China, CN : Kexue Chubanshe', '10003290', NULL, 'Mensal', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2695, 1, 'BULLETIN OF THE INTERNATIONAL STATISTICAL INSTITUTE', 'Bull. Inst. Int. Stat.', 'Rome, IT : Institut International De Statistique', '03730441', NULL, 'Trimestral', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2696, 1, 'CNS DRUGS', '', '', '11727047', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2697, 1, 'INDIAN JOURNAL OF AGRICULTURE SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2698, 1, 'JOURNAL OF PHYTOPATHOLOGY = PHYTOPATHOLOGISCHE ZEITSCHRIFT', '', 'Berlin und Hamburg: Verlag Paul Parey', '00319481', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2699, 1, 'CADERNO OMEGA - SERIE AGRONOMIA', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2700, 1, 'JOURNAL OF HUMAN EVOLUTION', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2701, 1, 'BIOLOGY OF HETEROMYIDAE: SPECIAL PUBLICATION OF THE AMERICAN SOCIETY OF MAMMALOGISTS', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2702, 1, 'MICROWAVE JOURNAL', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2703, 1, 'DIABETOLOGIA', '', 'Berlin, New York, Springer-Verlag', '0012186X', NULL, '2 vols. (six issues each) a year <, Mar. 1980- >', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2704, 1, 'CANCER EPIDEMIOLOGY, BIOMARKERS & PREVENTION', 'Cancer Epidemiol. Biomark. Prev.', 'Philadelphia : American Association for Cancer Research, 1991-', '10559965', NULL, '8 no. a year, 1994- // Bimestral', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2705, 1, 'CLINICAL ENDOCRINOLOGY', 'Clin. Endocrinol.', 'Oxford : Blackwell Scientific Publications, 1972-', '03000664', NULL, 'Frequency varies', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2706, 1, 'REVUE D\'ETUDES VALLESIENNES', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2707, 1, 'INSTITUTO NACIONAL AGRONOMIA MEXICANA FOLLETO TECNICO', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2708, 1, 'ENDEAVOUR', 'Endeavour', 'London ; New York [etc.] : Kynoch Press for Imperial Chemical Industries [etc.] 1942- // Oxford, Ing', '00137162', NULL, 'Frequency varies', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2709, 1, 'SKIN PHARMACOLOGY AND APPLIED SKIN PHYSIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2710, 1, 'VETERINARY AND HUMAN TOXICOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2711, 1, 'SOUTH AFRICAN MEDICAL JOURNAL', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2712, 1, 'BRITISH JOURNAL OF OPHTHALMOLOGY', 'Br. J. Ophthalmal.', 'London : British Medical Association , cop1917', '00071161', NULL, 'Mensal', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2713, 1, 'TROPICAL PEST MANAGEMENT', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2714, 1, 'JOURNAL OF THE AUSTRALIAN INSTITUTE OF AGRICULTURAL SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2715, 1, 'NEUROTOXICOLOGY AND TERATOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2716, 1, 'SAUDI MEDICAL JOURNAL', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2717, 1, 'HUMAN & EXPERIMENTAL TOXICOLOGY', 'Human. Exp. Toxicol.', 'Hampshire, Inglaterra, GB : Macmillan Press', '09603271', NULL, 'Bimestral', '2015-09-01 14:24:53', '2016-01-12 14:16:13');
INSERT INTO `journal` (`id`, `instance_id`, `name`, `abbreviation`, `responsible`, `ISSN`, `ISSNE`, `frecuency`, `createdAt`, `updatedAt`) VALUES
(2718, 1, 'JOURNAL OF THE ASSOCIATION OF PHYSICIANS OF INDIA', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2719, 1, 'OPHTHALMOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2720, 1, 'SCANDINAVIAN JOURNAL OF IMMUNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2721, 1, 'INFECTIOUS AGENTS AND DISEASE', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2722, 1, 'JOURNAL OF MACROMOLECULAR SCIENCE. PART A, CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2723, 1, 'JOURNAL OF ORGANIC CHEMISTRY OF THE USSR = ZHURNAL ORGANICHESKOI KHIMII. ENGLISH', '', '', '', NULL, '\'\'', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2724, 1, 'PHYSIOLOGICAL CHEMISTRY AND PHYSICS AND MEDICAL NMR', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2725, 1, 'FITOPATOLOGIA COLOMBIANA', '', '', '01200143', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2726, 1, 'AGRICULTURA TROPICAL (MEXICO)', '', 'Mexico, MX : Colegio Superior De Agricultura Tropical', '01873598', NULL, 'Quadrimestral', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2727, 1, 'BIOCHEMICAL AND MOLECULAR MEDICINE', 'Biochem. Mol. Med.', 'San Diego, Calif., US : Academic Press', '10773150', NULL, 'Bimestral', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2728, 1, 'HEALTH ECONOMICS', 'Health Econ.', 'Chichester, Inglaterra , GB : John Wiley & Sons', '1057-9230', NULL, 'Bimonthly, <1995->', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2729, 1, 'WEAR', 'Wear', 'Lausanne, Switzerland [etc.] : Elsevier Sequoia [etc.], 1957-', '00431648', NULL, 'Monthly -Jan. 1, 1981 ; biweekly Jan. 15, 1981-', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2730, 1, 'JOURNAL OF ENVIRONMENTAL MONITORING', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2731, 1, 'TRANSACTIONS OF THE CHARLES SANDERS PIERCE SOCIETY', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2732, 1, 'INDIAN JOURNAL OF MYCOLOGY AND PLANT PATHOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2733, 1, 'HISTORY OF PHILOSOPHY QUARTERLY', 'Hist. Philos. Q.', 'Bowling Green, Ohio : Published in cooperation with the Philosophy Documentation Center, [1984-', '07400675', NULL, 'Quarterly', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2734, 1, 'SPECTROSCOPY', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2735, 1, 'ANNALES DE MATHEMATIQUES PURES ET APPLIQUÉES', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2736, 1, 'ZHURNAL NEORGANICHESKOI KHIMII', '', '', '0036-0236', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2737, 1, 'JOURNAL OF INFECTION', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2738, 1, 'NEW MICROBIOLOGICA', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2739, 1, 'Indian Horticulture', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2740, 1, 'MICRON (1993)', 'Micron', '', '0968-4328 (e) 1878-4291', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2741, 1, 'CELL ADHESION AND COMMUNICATION', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2742, 1, 'TRANSACTIONS OF THE ROYAL SOCIETY OF EDIMBURG: EARTH SCIENCES', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2743, 1, 'JOURNAL OF PHYSICAL  AND CHEMICAL REFERENCE DATA', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2744, 1, 'CONTEMPORARY TOPICS IN LABORATORY ANIMAL SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2745, 1, 'KHELMINTOLOGIYA', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2746, 1, 'BIOMACROMOLECULES', 'Biomacromolecules', 'Washington, US : American Chemical Society', '15257797', NULL, 'Trimestral', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2747, 1, 'AUK: A JOURNAL OF ORNITHOLOGY', 'Auk', 'Lawrence, Kan., US : American Ornithologists Union', '00048038', NULL, 'Trimestral', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2748, 1, 'RIVISTA DI AGRICOLTURA SUBTROPICALE E TROPICALE', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2749, 1, 'IEE PROCEEDINGS. H, MICROWAVES, OPTICS AND ANTENNAS', '', 'London, GB : Institution Of Electrical Engineers, c1980-1985', '01437097', NULL, 'Bimestral', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2750, 1, 'APPITA JOURNAL', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2751, 1, 'CURRENT OPINION IN PLANT BIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2752, 1, 'JOURNAL OF THE AMERICAN OIL CHEMISTS SOCIETY', 'J. Am. Oil Chem. Soc.', 'Chicago, Ill., US : American Oil Chemists Society', '0003021X', NULL, 'Monthly', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2753, 1, 'PATHOLOGIE BIOLOGIE', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2754, 1, 'RUSSIAN JOURNAL OF APPLIED CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2755, 1, 'CELL BIOPHYSICS', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2756, 1, 'JAPANESE JOURNAL OF INFECTIOUS DISEASES', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2757, 1, 'JOURNAL OF THE JAPANESE ASSOCIATION FOR INFECTIOUS DISEASES', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2758, 1, 'TROPICAL ANIMAL HEALTH AND PRODUCTION', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2759, 1, 'SEPARATION SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2760, 1, 'BOLETIN DE LA DIRECCION DE MALARIOLOGIA Y SANEAMIENTO AMBIENTAL', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2761, 1, 'ASAE STANDARDS', 'Asae Stand.', 'St. Joseph, Mich., US : American Society Of Agricultural Engineers', '87551187', NULL, 'Anual', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2762, 1, 'IEEE TRANSACTIONS ON PARALLEL AND DISTRIBUTED SYSTEMS', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2763, 1, 'REVISTA ESPAÑOLA DE DOCUMENTACION CIENTIFICA', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2764, 1, 'PARASITOLOGIA AL DIA', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2765, 1, 'ACTA MATHEMATICA ACADEMIAE SCIENTIARUM HUNGARICAE', 'Acta math. Acad. Sci. Hung.', 'Budapest : Akadémiai Kiadó, 1950-1982', '00015954', NULL, 'Trimestral', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2766, 1, 'MICHIGAN DRY BEAN DIGEST', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2767, 1, 'INTERNATIONAL JOURNAL OF FATIGUE', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2768, 1, 'SCANDINAVIAN JOURNAL OF METALLURGY', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2769, 1, 'CANADIAN METALLURGICAL QUARTERLY', 'Can. Metall. Q.', 'Toronto // Ottawa, CA : Pergamon Press, 1962-', '00084433', NULL, 'Quarterly', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2770, 1, 'CLINICAL AND EXPERIMENTAL DERMATOLOGY', 'Clin. Exp. Dermatol.', 'Oxford, Blackwell Scientific Publications', '03076938', NULL, 'Bimonthly', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2771, 1, 'FITOTECNIA  LATINOAMERICANA', 'Fitotec. Latinoam.', 'San Jose, CR : Asociacion Latinoamericana De Fitotecnia', '00153168', NULL, 'Semestral', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2772, 1, 'AFRICAN CROP SCIENCE JOURNAL', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2773, 1, 'PETROLEUM SCIENCE AND TECHNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2774, 1, 'JOURNAL OF AGRICULTURAL ENGINEERING RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2775, 1, 'BIOKHIMIIA', '', 'Moskva [etc.] Izdatelstvo Nauka', '03209725', NULL, 'Bimonthly', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2776, 1, 'ANNALS OF EUGENICS', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2777, 1, 'EUROPEAN JOURNAL OF FOREST PATHOLOGY', 'Eur. J. Forest Pathol.', 'Hamburg, Alemanha, DE : Verlag Paul Parey, 1971-', '03001237', NULL, 'Irregular', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2778, 1, 'EAST AFRICAN AGRICULTURAL AND FORESTRY JOURNAL', '', 'Nairobi, KE : Kenya Agricultural Research Institute', '00128325', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2779, 1, 'PROGRESS IN LIPID RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2780, 1, 'JOURNAL OF WILDLIFE MANAGEMENT', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2781, 1, 'CANADIAN JOURNAL OF PLANT PATHOLOGY', 'Can. J. Plant Pathol.', 'Ontario, Canada, CA : Canadian Phytopatological Society', '07060661', NULL, 'Trimestral', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2782, 1, 'RICE GENETICS NEWSLETTERS', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2783, 1, 'JOURNAL OF ELECTROANALYTICAL CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2784, 1, 'JOURNAL OF VASCULAR RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2785, 1, 'THEORETICAL AND ANALYTICAL CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2786, 1, 'DIAGNOSTIC MOLECULAR PATHOLOGY: THE AMERICAN JOURNAL OF SURGICAL PATHOLOGY, PART B', 'Diagn. Mol. Pathol.', 'New York, N.Y. : Raven Press, c1992-', '10529551', NULL, 'Quarterly', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2787, 1, 'CURRENT OPINION IN ONCOLOGY', 'Curr. Opin. Oncol.', 'Philadelphia, PA : Current Science, c1989- // Rapid Science Publishers', '10408746', NULL, 'Bimonthly', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2788, 1, 'JOURNAL OF ELECTROANALYTICAL CHEMISTRY AND INTERFACIAL ELECTROCHEMISTRY (LAUSANNE, SWITZERLAND)', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2789, 1, 'HARVARD STUDIES IN CLASSICAL PHILOLOGY', 'Harv. Stud. Class. Philol.', 'London, GB : Harvard University Press // Boston [etc.] : Ginn & Co. [etc.]', '00730688', NULL, 'Anual', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2790, 1, 'ORIGINS OF LIFE AND EVOLUTION OF THE BIOSPHERE.', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2791, 1, 'REDOX REPORT', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2792, 1, 'MAGNESIUM', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2793, 1, 'COMPTES RENDUS DE L\'ACADEMIE DES SCIENCES. SERIE I, MATHEMATIQUE', 'C. R. Acad. Sci., Ser. I. Math.', 'Paris : Gauthier-Villars, [1984- // Montrouge : Centrale des Revues, 1984-', '07644442', NULL, 'Forty no. a year', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2794, 1, 'PHILIPPINE JOURNAL OF VETERINARY MEDICINE', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2795, 1, 'MYSORE JOURNAL OF AGRICULTURAL SCIENCES', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2796, 1, 'INDIAN JOURNAL OF ANIMAL HEALTH', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2797, 1, 'ENVIRONMENTAL SCIENCE AND POLLUTION RESEARCH INTERNATIONAL', '', '', '1614-7499', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2798, 1, 'AMERICAN FISHERIES SOCIETY SYMPOSIUM (PROCEEDINGS)', 'Am. Fish. Soc. Symp', 'Bethesda, Md. : The Society, 1987-', '08922284', NULL, 'Irregular', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2799, 1, 'BRITISH JOURNAL OF HAEMATOLOGY', 'Br. J. Haematol.', 'Oxford, Inglaterra, GB : Blackwell Scientific Publications', '00071048', NULL, 'Mensal', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2800, 1, 'THROMBOSIS AND HAEMATOSIS', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2801, 1, 'JOURNAL OF ANALYTICAL CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2802, 1, 'CHERN SYMPOSIUM', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2803, 1, 'JOURNAL OF  REPRODUCTIVE IMMUNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2804, 1, 'IMMUNOCHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2805, 1, 'CANADIAN JOURNAL OF SPECTROSCOPY', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2806, 1, 'CANADIAN JOURNAL OF PHILOSOPHY', 'Can. J. Philos.', 'Edmonton, Canada, CA : Canadian Association For Publishing In Philosophy, 1971-', '00455091', NULL, 'Quarterly // Trimestral', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2807, 1, 'INTERNATIONAL JOURNAL OF ORAL AND MAXILLOFACIAL IMPLANTS', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2808, 1, 'PHILOSOPHICAL STUDIES: AN INTERNATIONAL JOURNAL FOR PHILOSOPHY IN THE ANALYTIC TRADITION', '', '', '0031-8116 (print ) 1573-0883 (electronic)', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2809, 1, 'JOURNAL OF COMPOSITE MATERIALS', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2810, 1, 'COMPUTERS AND STRUCTURES', 'Comput. Struct.', 'New York, N.Y. : Pergamon Press, 1971-', '00457949', NULL, 'Frequency varies // Quincenal  // Bimensal', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2811, 1, 'CLINICAL BIOCHEMISTRY', 'Clin. Biochem.', 'New York, N.Y. : Pergamon Press // Toronto, Canada, CA : Canadian Society Of Clinical Chemists', '00099120', NULL, 'Bimonthly, 1975-', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2812, 1, 'DNA SEQUENCE', '', '', '1042-5179', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2814, 1, 'THYROID', '', '', '1050-7256', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2815, 1, 'EUROPEAN JOURNAL OF ORTHODONTICS', '', 'Oxford : European Association of Orthodontics, 1979-', '01415387', NULL, 'Trimestral', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2816, 1, 'JOURNAL OF TOXICOLOGY AND ENVIRONMENTAL HEALTH', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2817, 1, 'MIKOLOGIIA  I  FITOPATOLOGIIA', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2818, 1, 'CADERNOS DE SAUDE PUBLICA', 'Cad. Saude Publica', 'Rio De Janeiro, RJ : Escola Nacional De Saude Publica', '0102311X', NULL, 'Quarterly', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2819, 1, 'AMERICAN JOURNAL OF HUMAN BIOLOGY', 'Am. J. Human Biol.', 'New York, US : Alan R. Liss', '10420533', NULL, 'Bimestral', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2820, 1, 'PRAGMATICS & COGNITION', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2821, 1, 'CURRENT PHARMACEUTICAL DESIGN', 'Curr. Pharm. Des.', 'Hilversum : Bentham Science Publishers , 2000-', '13816128', NULL, 'Mensual', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2822, 1, 'JOURNAL OF PROSTHODONTICS', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2823, 1, 'BIOCELL', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2824, 1, 'BRITISH JOURNAL OF OBSTETRICS AND GYNAECOLOGY', 'Br. J. Obstet. Gynaecol.', 'London, GB : Royal College Of Obstetricians And Gynaecologists', '03065456', NULL, 'Mensal', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2825, 1, 'BULLETIN OF THE PAN AMERICAN HEALTH ORGANIZATION', 'Bull. Pan Am. Health Organ.', 'Washington, US : Pan American Health Organization', '00854638', NULL, 'Trimestral', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2826, 1, 'SOUTH AFRICAN MEDICINAL JOURNAL', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2827, 1, 'BOLETIN DE LA OFICINA SANITARIA PANAMERICANA. ENGLISH EDITION', 'Bol. Of. Sanit. Panam.', 'Washington, US : Oficina Sanitaria Panamericana, 1923-1996', '00300632', NULL, 'Monthly', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2828, 1, 'PHYSIOLOGICAL AND MOLECULAR PLANT PATHOLOGY', '', '', '0885-5765', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2829, 1, 'ALTEX', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2830, 1, 'JOURNAL OF POROUS MATERIALS', '', 'Dordrecht: Kluwer Academic Publishers', '13802224', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2831, 1, 'ARCHIVOS DE BIOLOGIA Y MEDICINA', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2832, 1, 'REVISTA ARGENTINA DE PSIQUIATRIA Y PSICOLOGIA DE LA INFANCIA Y LA ADOLECENCIA', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2833, 1, 'JOURNAL OF THE ROYAL NETHERLANDS CHEMICAL SOCIETY = RECUEIL DES TRAVAUX CHIMIQUES DES PAYS-BAS (LEIDEN, NETHERLANDS : 1920)', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2834, 1, 'THE AMERICAN HEARTH JOURNAL', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2835, 1, 'The American journal of cardiology', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2836, 1, 'ANNALS OF SURGERY', 'Ann. Surg.', 'St. Louis : J. H. Chambers , cop1885 // Philadelphia, Pa., US : J. B. Lippincott', '00034932', NULL, 'Mensal', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2837, 1, 'SPIXIANA', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2838, 1, 'BEITRAGE ZUR ENTOMOLOGIE', 'Beitr. Entomol.', 'Berlin, DE : Akademie Verlag', '0005805X', NULL, 'Bimestral', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2839, 1, 'Quintessence of dental technology', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2840, 1, 'ASCLEPIO: ARCHIVO IBEROAMERICANO DE HISTORIA DE LA MEDICINA Y ANTROPOLOGÍA MÉDICA', 'Asclepio', 'Madrid, ES : Consejo Superior De Investigaciones Científicas, Centro De Estudios Históricos', '0210-4466', NULL, 'Semestral', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2841, 1, 'BIOCHIMICA ET BIOPHYSICA ACTA. REVIEWS ON CANCER', 'Biochim. Biophys. Acta. Rev. Cancer', 'Amsterdam : Elsevier Science , 1974', '0304419X', NULL, 'Tres n. al año', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2842, 1, 'JOURNAL OF MICROWAVE POWER', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2843, 1, 'TECHNICAL QUARTERLY (MASTER BREWERS ASSOCIATION OF AMERICA)', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2844, 1, 'MICROWAVE ENERGY APPLICATIONS NEWSLETTER', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2845, 1, 'COMPUTER APPLICATIONS IN THE BIOSCIENCES', '', 'Oxford : Oxford University Press , cop1985', '02667061', NULL, 'Bimestral', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2846, 1, 'THEOCHEM', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2847, 1, 'THE PHYLOSOPHICAL REVIEW', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2848, 1, 'ARCHIVES OF GENERAL PSYCHIATRY', 'Arch. Gen. Psychiatry', 'Chicago, American Medical Association', '0003990X', NULL, 'Mensal', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2849, 1, 'ADVANCES IN MICROBIAL PHYSIOLOGY', 'Adv. microb. physiol', 'London ; New York : Academic Press, 1967-', '00652911', NULL, 'Anual', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2850, 1, 'BRAZIL-MEDICO', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2851, 1, 'DEVELOPMENTS IN THEORETICAL AND APPLIED MECHANICS. PROCEEDINGS', '', 'Washington, etc.: Pergamon Press', '00704598', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2852, 1, 'ZEITSCHRIFT FUR LEBENSMITTEL-UNTERSUCHUNG UND -FORSCHUNG', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2853, 1, 'JCT JOURNAL OF COATINGS TECHNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2854, 1, 'JORNADAS CHILENAS DE SISMOLOGIA E INGENIERIA ANTISISMICA', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2855, 1, 'DIE MAKROMOLEKULARE CHEMIE. RAPID COMMUNICATIONS', 'Makromol. Chem., Rapid Commun.', 'Basel, Suica, CH : Huethig Und Wepf Verlag', '01732803', NULL, 'Mensal', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2856, 1, 'CANADIAN INSTITUTE OF FOOD SCIENCE AND TECHNOLOGY JOURNAL', '', 'Ottawa, CA : Canadian Institute Of Food Science And Technology', '03155463', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2857, 1, 'PATOLOGIA DE SEMENTES FUNDACION CAMPINAS', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2858, 1, 'NUTRITIONAL SCIENCE OF SOY PROTEIN', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2859, 1, 'BRITISH JOURNAL OF RHEUMATOLOGY', 'Br. J. Rheumatol.', 'London : Published for the British Association for Rheumatology and Rehabilitation by Baillière Tind', '02637103', NULL, 'Bimestral', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2860, 1, 'Lecta : Revista De Farmacia E Biologia Da Universidade Sao Francisco', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2861, 1, 'ANESTHESIOLOGY', '', 'Philadelphia : Lippincott , cop1940 //', '00033022', NULL, 'Mensual ; Anteriormente bimestral', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2862, 1, 'FOOD PRODUCT DEVELOPMENT', '', 'Chicago, Ill., US : Arlington Publishing Company', '0015654X', NULL, 'Mensal', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2863, 1, 'INVERSE PROBLEMS', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2864, 1, 'ZEITSCHRIFT FUR ELEKTROCHEMIE', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2865, 1, 'SYNTHESE', 'Synthese', '', '0039-7857 (e): 1573-0964', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2866, 1, 'CONVERGENCIA: REVISTA DE CIENCIAS SOCIALES', '', 'Toluca, Mexico, MX : Universidad Autonoma Del Estado De Mexico, Facultad De Ciencias Politicas Y Adm', '14051435', NULL, 'Irregular', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2867, 1, 'JOURNAL OF ARCHAEOLOGY  SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2868, 1, 'JOURNAL OF ARCHAEOLOGICAL SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2869, 1, 'MEMORIA DE LA SOCIEDAD DE CIENCIAS NATURALES LA SALLE', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2870, 1, 'UNIVERSITY KANSAS SCIENCE BULLETIN', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2871, 1, 'OCCASIONAL PAPERS OF THE MUSEUM OF ZOOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2872, 1, 'BULLETIN OF THE ANTIVENIN INSTITUTE OF AMERICA', '', 'Philadelphia, Pa., US : Antevenin Institute Of America', '00968560', NULL, 'Trimestral', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2873, 1, 'PROCEEDINGS OF THE ENTOMOLOGICAL SOCIETY OF WASHINGTON', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2874, 1, 'GAYANA. ZOOLOGIA', 'Gayana, Zool.', 'Concepcion, Chile, CL : Universidad De Concepcion, Facultad De Ciencias Biologicas Y Recursos Natura', '0016531X', NULL, 'Irregular', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2875, 1, 'Zeitschrift fur Wahrscheinlichkeitstheorie und verwandte Gebiete', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2876, 1, 'CALCIFIED TISSUE RESEARCH', 'Calcif. Tissue Res.', 'Berlin, DE : Springer International', '00080594', NULL, 'Three issues yearly', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2877, 1, 'NUTRITION RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2878, 1, 'ENVIRONMENTAL AND EXPERIMENTAL BOTANY', 'Environ. Exp. Bot.', 'Oxford ; New York : Pergamon Press, 1976-', '00988472', NULL, 'Quarterly', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2879, 1, 'POLYMERS AND POLYMER COMPOSITES', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2880, 1, 'ANNALS  OF BIOMEDICAL ENGINEERING', 'Ann. biomed. eng.', 'Publisher New York : Pergamon Press [etc.], 1972- // Academic Press // Biomedical Engineering Societ', '00906964', NULL, 'Quarterly ; Bimonthly, 197<9>-', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2881, 1, 'HUNGARIAN  JOURNAL  OF  INDUSTRIAL  CHEMISTRY', 'Hung. J. Ind. Chem.', 'Veszprem, Hungria, HU : Hungarian Academy Of Sciences, Research Institute Of Chemical Engineering', '01330276', NULL, 'Trimestral', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2882, 1, 'SPATIAL VISION', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2883, 1, 'OPHTHALMIC AND PHYSIOLOGICAL OPTICS', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2884, 1, 'ZEITSCHRIFT FUR WHARSCHEINLICHKEITSTHEORIE UND VERWANDTE GEBIETE', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2885, 1, 'DHAKA UNIVERSITY STUDIES PART B SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2886, 1, 'HETEROATOM CHEMISTRY', 'Heteroat. Chem.', 'Deerfield Beach : VCH Publishers , 1990-', '10427163', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2887, 1, 'EXPERIMENTAL PHYSIOLOGY', 'Exp. Physiol.', 'New York, US : Cambridge University Press, Physiological Society', '09580670', NULL, 'Bimonthly', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2888, 1, 'AMERICAN JOURNAL OF PHYSIOLOGY ENDOCRINOLOGY AND METABOLISM', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2889, 1, 'THE ANNALS OF THORACIC SURGERY', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2890, 1, 'REVUE SUISSE DE ZOOLOGIE', '', '', '0035-418X', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2891, 1, 'COMPUTERS IN BIOLOGY AND MEDICINE', 'Comput. Biol. Med.', 'New York, N.Y. : Pergamon Press, 1970-', '00104825', NULL, 'Trimestral // Bimestral', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2892, 1, 'IEE PROCEEDINGS. MICROWAVES, ANTENNAS AND PROPAGATION', 'Iee Proc.. Microw. Antennas Propag.', 'London, GB : Institution Of Electrical Engineers', '13502417', NULL, 'Bimonthly', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2893, 1, 'JOURNAL OF ENVIRONMENTAL SCIENCE AND HEALTH: PART B. PESTICIDES, FOOD CONTAMINANTS, AND AGRICULTURAL WASTES', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2894, 1, 'INTERNATIONAL JOURNAL OF MEDICAL MICROBIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2895, 1, 'BIORESOURCE TECHNOLOGY', 'Bioresour. Technol.', 'Essex, Inglaterra, GB : Elsevier Science Publishers', '09608524', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2896, 1, 'REVISTA DE AGRICULTURA (PIRACICABA)', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2897, 1, 'JOURNAL OF THE ASSOCIATION FOR COMPUTING MACHINERY', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2898, 1, 'COMPUTATIONAL LINGUISTICS', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2899, 1, 'THE AMERICAN JOURNAL OF CARDIOVASCULAR PATHOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2900, 1, 'MATERIALS RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2901, 1, 'APMIS: ACTA PATHOLOGICA, MICROBIOLOGICA ET IMMUNOLOGICA SCANDINAVICA', 'Apmis : Acta Pathol. Microbiol. Immunol. Scand.', 'Copenhagen : Munksgaard, c1988-', '09034641', NULL, 'Mensal', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2902, 1, 'POLYMER COMMUNICATIONS', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2903, 1, 'DRUG METABOLISM AND DISPOSITION', 'Drug Metab. Dispos.', 'Bethesda, Md., etc., American Society for Pharmacology and Experimental Therapeutics, etc. // Baltim', '00909556', NULL, 'Bimonthly', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2904, 1, 'RUSSIAN CHEMICAL BULLETIN', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2905, 1, 'ANNALEN DER CHEMIE UND PHARMACIE', '', 'Leipzig, C. F. Winter´sche // Heidelberg : Friedrich Wöhler , 18??, 19?? // Weinheim, Alemanha, DE:', '', NULL, 'Mensal', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2906, 1, 'CLASSICAL QUARTERLY', 'Classical Q', '', '0009-8388', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2907, 1, 'INDUSTRIAL CROPS AND PRODUCTS', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2908, 1, 'AUSTRALIAN JOURNAL OF APPLIED SCIENCE', 'Aust. J. Appl. Sci.', 'Melbourne, Australia, AU : Commonwealth Scientific And Industrial Research', '05721156', NULL, 'Four issues yearly', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2909, 1, 'HISTORIA NATURAL', '', 'Mendoza, Argentina [etc.] : Asociacion de Ciencias Naturales de Cuyo, 1979-', '', NULL, 'Irregular', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2910, 1, 'DEVELOPMENT DYNAMICS', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2911, 1, 'MOLECULAR ENDOCRINOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2912, 1, 'JOURNAL OF MEMBRANE BIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2913, 1, 'RECORDS OF THE ZOOLOGICAL SURVEY OF INDIA', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2914, 1, 'PHYTOPATHOLOGIA POLONICA', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2915, 1, 'PHYTOPATOLOGIA POLONICA', '', '', '', NULL, '', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2916, 1, 'JOURNAL OF APPLIED ELECTROCHEMISTRY', 'J. Appl. Electrochem.', 'London : Chapman and Hall, 1971-', '0021891X', NULL, 'Bimonthly', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2917, 1, 'ANNALS OF CLINICAL BIOCHEMISTRY', 'Ann. Clin. Biochem.', 'London, GB: British Medical Association', '00045632', NULL, 'Bimestral', '2015-09-01 14:24:53', '2016-01-12 14:16:13'),
(2918, 1, 'JOURNAL FUR PRAKTISCHE CHEMIE', '', '', '', NULL, '', '2015-09-01 14:24:53', '2015-09-01 14:25:08'),
(2919, 1, 'AMERICAN HEART JOURNAL', 'Am. heart j', 'St. Louis, C. V. Mosby Co // American Heart Association', '00028703', NULL, 'Bimonthly, 1926- / Monthly <, Oct. 1977->', '2015-09-01 14:24:54', '2016-01-12 14:16:13'),
(2920, 1, 'FEMS MICROBIOLOGY IMMUNOLOGY', '', 'Amsterdam, NL : Elsevier Science Publishers, 1988-', '09208534', NULL, 'Mensal', '2015-09-01 14:24:54', '2016-01-12 14:16:13'),
(2921, 1, 'JOURNAL OF COMPUTER ASSISTED TOMOGRAPHY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:13'),
(2922, 1, 'PROCEEDINGS OF THE ACADEMY OF SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:13'),
(2923, 1, 'EUROPEAN JOURNAL OF APPLIED MICROBIOLOGY', '', 'New York, US : Springer Verlag', '03402118', NULL, 'Mensal', '2015-09-01 14:24:54', '2016-01-12 14:16:13'),
(2924, 1, 'MICROBIOLOGIA', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:13'),
(2925, 1, 'THE JOURNAL OF THE BRITISH CERAMIC SOCIETY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:13'),
(2926, 1, 'GUMS AND STABILISERS FOR THE FOOD INDUSTRY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:13'),
(2927, 1, 'MACROMOLECULAR RAPID COMMUNICATIONS', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:13'),
(2928, 1, 'PROCEEDINGS OF THE ACADEMY OF NATURAL SCIENCE OF PHILADELPHIA', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:13'),
(2929, 1, 'NETHERLANDS JOURNAL OF PLANT PATHOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:13'),
(2930, 1, 'BIOCHEMICAL JOURNAL. CELLULAR ASPECTS', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:13'),
(2931, 1, 'SEMINARS IN ONCOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:13'),
(2932, 1, 'COMPUTERS AND BIOMEDICAL RESEARCH', 'Comput. Biomed. Res.', 'San Diego // New York, N.Y. : Academic Press, 1967-', '00104809', NULL, 'Bimonthly', '2015-09-01 14:24:54', '2016-01-12 14:16:13'),
(2933, 1, 'JOURNAL OF ANDROLOGY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:13'),
(2934, 1, 'PHYSICS AND CHEMISTRY OF THE EARTH', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:13'),
(2935, 1, 'POLYMER INTERNATIONAL', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:13'),
(2936, 1, 'BIKEN JOURNAL', 'Biken J.', 'Osaka, Japan : The Research Institute for Microbial Diseases,', '00062324', NULL, 'Quarterly', '2015-09-01 14:24:54', '2016-01-12 14:16:13'),
(2937, 1, 'ANNALS OF PROBABILITY', '', 'Hayward, Calif., US // [San Francisco] Institute of Mathematical Statistics', '00911798', NULL, 'Trimestral', '2015-09-01 14:24:54', '2016-01-12 14:16:13'),
(2938, 1, 'BRAIN BEHAVIOR AND EVOLUTION', 'Brain Behav. Evol.', 'Basel, Suica, CH : S. Karger', '00068977', NULL, 'Frequency varies', '2015-09-01 14:24:54', '2016-01-12 14:16:13'),
(2939, 1, 'BRAZILIAN ARCHIVES OF BIOLOGY AND TECHNOLOGY = ARQUIVOS DE BIOLOGIA E TECNOLOGIA', 'Arq. Biol. Tecnol.', 'Curitiba, PR : Instituto De Tecnologia Do Parana', '03650979', NULL, 'Trimestral', '2015-09-01 14:24:54', '2016-01-12 14:16:13'),
(2940, 1, 'FOOD AUSTRALIA', '', 'North Sydney, Australia, AU : Australian Institute Of Food Science And Technology Incorporated', '10325298', NULL, 'Mensal', '2015-09-01 14:24:54', '2016-01-12 14:16:13'),
(2941, 1, 'JOURNAL OF ENVIRONMENTAL QUALITY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:13'),
(2942, 1, 'POLYMER TESTING', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:13'),
(2943, 1, 'CLINICAL AUTONOMIC RESEARCH', 'Clinic. Auton. Res.', 'Oxford, Inglaterra, GB : Rapid Communications', '09599851', NULL, 'Bimestral', '2015-09-01 14:24:54', '2016-01-12 14:16:13'),
(2944, 1, 'HENRY FORD HOSPITAL MEDICAL BULLETIN', 'Henry Ford Hosp. Med. Bull.', 'Detroit, Mich., US : Professional Staff Of The Henry Ford Hospital', '00961868', NULL, 'Trimestral', '2015-09-01 14:24:54', '2016-01-12 14:16:13'),
(2945, 1, 'ACTA ORTHOPAEDICA BELGICA', 'Acta Orthop. Belg.', 'Bruxelles, BE : Societe Belge D Orthopedie Et De Chirurgie De L Appareil Moteur', '00016462', NULL, 'Bimestral', '2015-09-01 14:24:54', '2016-01-12 14:16:13'),
(2946, 1, 'BULLETIN DE L\'ASSOCIATION DES ANATOMISTES', 'Bull. Assoc. Anat.', 'Paris, FR : Association Des Anatomistes, Faculte Medecine', '03766160', NULL, 'Trimestral', '2015-09-01 14:24:54', '2016-01-12 14:16:13'),
(2947, 1, 'JOURNAL OF CHEMOMETRICS', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:13'),
(2948, 1, 'BULLETIN TECHNIQUE APICOLE', 'Bull. Tech. Apic.', 'Echauffour, Franca, FR : Office Pour L´Information Et La Documentation En Apiculture', '03353710', NULL, 'Trimestral', '2015-09-01 14:24:54', '2016-01-12 14:16:13'),
(2949, 1, 'RANDOM STRUCTURES AND ALGORITHMS', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:13'),
(2950, 1, 'NATURE REVIEWS GENETICS', '', '', '1471-0056', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:13'),
(2951, 1, 'NATURE  REVIEWS GENETICS', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:13'),
(2952, 1, 'JOURNAL OF THE NEUROLOGICAL SCIENCES', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:13'),
(2953, 1, 'EUROPEAN JOURNAL OF BIOCHEMISTRY REVIEWS', '', '[Berlin] : Springer-Verlag, c1989-', '', NULL, 'Annual', '2015-09-01 14:24:54', '2016-01-12 14:16:13'),
(2954, 1, 'CAHIERS DE BIOLOGIE MARINE', 'Cah. Biol. Mar.', 'Paris, FR : Station Biologique De Roscoff', '00079723', NULL, 'Trimestral', '2015-09-01 14:24:54', '2016-01-12 14:16:13'),
(2955, 1, 'THE MATHEMATICAL SCIENTIST', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:13'),
(2956, 1, 'BULLETIN DE L\'ASSOCIATION DES ANATOMISTES (NANCY)', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:13'),
(2957, 1, 'ANATOMISCHER ANZEIGER', 'Anat. Anz.', 'Jena, G. Fischer', '', NULL, '5 no. a year', '2015-09-01 14:24:54', '2016-01-12 14:16:13'),
(2958, 1, 'ABHANDLUNGEN DER SENCKENBERGISCHEN NATURFORSCHENDEN GESELLSCHAFT', 'Abh. Senckenb. Naturforsch. Ges', 'Frankfurt am Main, Waldemar Kramer [etc.]', '03657000', NULL, 'irregular', '2015-09-01 14:24:54', '2016-01-12 14:16:13'),
(2959, 1, 'BRAIN RESEARCH', 'Brain Res.', 'Amsterdam, NL : Elsevier Science Publishers', '00068993', NULL, 'Monthly, Jan. 1966-<Jan. 1967> ; Weekly', '2015-09-01 14:24:54', '2016-01-12 14:16:13'),
(2960, 1, 'CURRENT OPINION IN SOLID STATE AND MATERIALS SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:13'),
(2961, 1, 'PHARMACOLOGY LETTERS', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:13'),
(2962, 1, 'STUDIA ZOOLOGICA', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:13'),
(2963, 1, 'ARCHIVES DE ANATOMIE, DE HISTOLOGIE ET DE EMBRIOLOGIE', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:13'),
(2964, 1, 'FREE RADICAL RESEARCH', 'Free Radic Res', 'London, GB : Harwood Academic Publishers', '1071-5762 (e): 1029-2470', NULL, 'Bimestral', '2015-09-01 14:24:54', '2016-01-12 14:16:13'),
(2965, 1, 'SULFUR IN ORGANIC AND INORGANIC CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:13'),
(2966, 1, 'YEARBOOK  OF PHYSICAL ANTHROPOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:13'),
(2967, 1, 'BIOLOGIE CELLULAIRE', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(2968, 1, 'HUMAN NATURE. AN INTERDISCIPLINARY BIOSOCIAL PERSPECTIVE', 'Hum. Nat.', 'Hawthorne, N.Y. : Aldine de Gruyter, c1990-', '10456767', NULL, 'Quarterly', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(2969, 1, 'ORGANIC COMPOUNDS OF SULPHUR, SELENIUM, AND TELLURIUM', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(2970, 1, 'EUROPEAN FOOD & DRINK REVIEW', 'Eur. Food Drink Rev.', 'London, GB : Ccl Industrial', '09554416', NULL, 'Trimestral', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(2971, 1, 'JOURNAL OF THE AMERICAN ACADEMY OF ORTHOPAEDIC SURGEONS', 'J Am Acad Orthop Surg', '', '1067-151X (Prin', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(2972, 1, 'GENERAL PHARMACOLOGY', 'Gen. Pharmacol.', 'Oxford, Elmsford, N. Y., Pergamon Press', '03063623', NULL, 'Mensal', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(2973, 1, 'INTERNATIONAL JOURNAL OF DISABILITY, DEVELOPMENT, AND EDUCATION', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(2974, 1, 'CLINICAL AND DIAGNOSTIC LABORATORY IMMUNOLOGY', 'Clin. Diagn. Lab. Immunol.', 'Washington, US : American Society For Microbiology', '1071412X', NULL, 'Bimestral', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(2975, 1, 'REVUE DES SCIENCES PHILOSOPHIQUES ET THEOLOGIQUES', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(2976, 1, 'BOLETIN DE LA SOCIEDAD BIOLOGICA DE CONCEPCION', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(2977, 1, 'RECENT RESEARCH DEVELOPMENTS IN MICROBIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(2978, 1, 'ANNALS OF MEDICINE (HELSINKI)', 'Ann. Med.', 'Helsinki : Finnish Medical Society Duodecim, 1989-', '07853890', NULL, 'Bimonthly', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(2979, 1, 'FERTILITY AND STERILITY', 'Fertil. Steril.', 'Birmingham, Ala., US : American Fertility Society //  New York, Hoeber', '00150282', NULL, 'Mensal', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(2980, 1, 'JOURNAL OF ENVIRONMENTAL AND ENGINEERING GEOPHYSICS', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(2981, 1, 'NATIONAL SCIENCE MUSEUM MONOGRAPHS', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(2982, 1, 'BIOLOGY OF THE CELL', 'Biol. Cell (1981)', 'Ivry sur Seine, France : Publié par la Société française de microscopie électronique avec le concour', '02484900', NULL, 'Nine no. a year', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(2983, 1, 'REVUE ROUMAINE DE MORPHOLOGIE, DE EMBRYOLOGIE ET DE PHYSIOLOGIE =  ROMANIAN JOURNAL OF MORPHOLOGY AND EMBRYOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(2984, 1, 'ANAIS DA FACULDADE DE FARMACIA E ODONTOLOGIA DA UNIVERSIDADE DE SAO PAULO', 'An. Fac. Farm. Odontol. Univ. Sao Paulo', 'Sao Paulo, SP : Usp, Faculdade De Farmacia E Odontologia', '03652181', NULL, 'Anual', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(2985, 1, 'LASER AND PARTICLE BEAMS', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(2986, 1, 'JOURNAL OF ENGINEERING MECHANICS', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(2987, 1, 'BIOCONTROL SCIENCE AND TECHNOLOGY', 'Biocontrol Sci. Technol.', 'Oxford, Inglaterra, GB : Journals Oxford', '09583157', NULL, 'Trimestral', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(2988, 1, 'JOURNAL OF STORED PRODUCTS RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(2989, 1, 'BIOLOGICAL CONTROL', 'Biol. Control.', 'Orlando, Fla., US : Academic Press', '10499644', NULL, 'Mensal', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(2990, 1, 'PESTICIDE SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(2991, 1, 'BULLETIN OF THE BRITISH MUSEUM (NATURAL HISTORY). ZOOLOGY SERIES', 'Bull. Br. Mus. Nat. Hist., Zool.', 'London, GB : British Museum Of Natural History', '00071498', NULL, 'Irregular', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(2992, 1, 'JOURNAL OF NATURAL HISTORY', 'J Nat Hist', '', '0022-2933 (e): 1464-5262', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(2993, 1, 'JOURNAL OF FELINE MEDICINE & SURGERY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(2994, 1, 'JOURNAL OF CEREAL SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(2995, 1, 'IOBC/WPRS bulletin = Bulletin OILB/SROP', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(2996, 1, 'CRC CRITICAL REVIEWS IN BIOCOMPATIBILITY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(2997, 1, 'DENTAL MATERIALS JOURNAL', 'Dent. Mater. J.', 'Tokyo, JP : Japanese Society For Dental Materials And Devices', '02874547', NULL, 'Trimestral', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(2998, 1, 'MATHEMATICA SCANDINAVICA', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(2999, 1, 'DUKE MATHEMATICAL JOURNAL', '', 'Durham, N.C.: Duke University Press, 1935-', '00127094', NULL, 'Mensual  // Trimestral', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3000, 1, 'PSYCHOLOGICAL BULLETIN', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3001, 1, 'EPIDEMIOLOGIA E PSICHIATRIA SOCIALE', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3002, 1, 'INDIAN JOURNAL OF CHEMISTRY', 'Indian J Chem', '', '0019-5103', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3003, 1, 'BIOLOGICAL & PHARMACEUTICAL BULLETIN', 'Biol. Pharm. Bull.', 'Tokyo : Pharmaceutical Society of Japan, c1993-', '09186158', NULL, 'Monthly', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3004, 1, 'AMERICAN LABORATORY', 'Am. lab. (Fairfield)', 'Shelton, CT, etc. : International Scientific Communications, Inc., etc., 1969- // Greens Farms, Conn', '00447749', NULL, 'Monthly 1969-1976 ; 13 no. a year 1977-1986 ; 19 no. a year 1987-', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3005, 1, 'COMPTES RENDUS HEBDOMADAIRES DES SEANCES DE L\'ACADEMIE DES SCIENCES. SERIE C: SCIENCES CHIMIQUES', 'C. R. Hebd. Séances Acad. Sci., Ser. C, Sci. Chim.', 'Paris : Gauthier-Villars, 1966-1980', '05676541', NULL, 'Semanal', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3006, 1, 'CELLS TISSUES ORGANS', '', 'Basel, Suica, CH : Karger', '1422-6405 (e): 1422-6421', NULL, 'Mensal', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3007, 1, 'MITTEILUNGEN AUS DEM GEBIETE DER LEBENSMITTELUNTERSUCHUNG UND HYGIENE / GESUNDHEITSAMT = TRAVAUX  DE CHIMIE ALIMENTAIRE ET DE HYGIENE / SERVICE SANITAIRE FEDERAL.', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3008, 1, 'RAPID COMMUNICATIONS IN MASS SPECTROMETRY : RCM.', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3009, 1, 'MARKOV PROCESSES AND RELATED FIELDS', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3010, 1, 'THE PHILOSOPHICAL REVIEW', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3011, 1, 'AMERICAN PHILOSOPHICAL QUARTERLY', 'Am. philos. q.', 'Pittsburgh : University of Pittsburgh, Department of Philosophy , cop1964 // Oxford, Inglaterra, GB', '00030481', NULL, 'Trimestral', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3012, 1, 'APEIRON', '', 'Alberta, Canada, CA // Edmonton : Academic Print. & Pub., [1966]-', '00036390', NULL, 'Semiannual, 1966-1987 ; 3 no. a year, 1988-', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3013, 1, 'PROGRESS IN ORGANIC COATINGS', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3014, 1, 'SURFACE AND COATINGS TECHNOLOGY', 'Surf Coating Tech', '', '0257-8972', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3015, 1, 'VIRCHOWS ARCHIV. A, PATHOLOGICAL ANATOMY AND HISTOPATHOLOGY (BERLIN)', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3016, 1, 'CHEMICAL SOCIETY REVIEWS', 'Chem. Soc. Rev.', 'London : Royal Society of Chemistry, etc., 1972-', '03060012', NULL, 'Quarterly', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3017, 1, 'PIGMENT & RESIN TECHNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3018, 1, 'GENETIC RESOURCES AND CROP EVOLUTION', 'Genet. Resour. Crop Evol.', 'Dordrecht, Holanda, NL : Kluwer Academic Publishers', '09259864', NULL, 'Bimestral', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3019, 1, 'TRANSACTIONS OF AMERICAN SOCIETY FOR METALS', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3020, 1, 'FUNGAL GENETICS NEWSLETTER', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3021, 1, 'CYTOBIOS', 'Cytobios', 'Cambridge, England : Faculty Press, 1969-', '00114529', NULL, 'Frequency varies', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3022, 1, 'VETERINARY CLINICAL PATHOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3023, 1, 'AUSTRALIAN JOURNAL OF PHYSICS', 'Aust. J. Phys.', 'Melbourne : Commonwealth Scientific and Industrial Research Organization, 1953-', '00049506', NULL, 'Quarterly, Mar. 1953-<Dec. 1955> ; bimonthly <, Oct. 1976->', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3024, 1, 'ADVANCES IN CHEMISTRY SERIES', 'Adv. Chem. Ser.', 'Washington, US : American Chemical Society', '00652393', NULL, 'Irregular', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3025, 1, 'JOURNAL OF SHANGHAI JIAOTONG UNIVERSITY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3026, 1, 'LATIN AMERICAN APPLIED RESEARCH: AN INTERNATIONAL JOURNAL', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3027, 1, 'ADVANCES IN MATHEMATICS', 'Adv. math. (New York. 1965)', 'New York, Academic Press, 1965- // Brugge (Belgium) : Academic Press, 1965- // San Diego, Calif., US', '00018708', NULL, 'Annual, v. 2-3; 1968-69; Bimonthly, v. 4-; Monthly, 19; Frec. actual: Mensual', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3028, 1, 'REVISTA IBERICA DE PARASITOLOGIA', '', '', '0034-9623', NULL, '', '2015-09-01 14:24:54', '2015-09-01 14:25:12'),
(3029, 1, 'BULLETIN DE LA SOCIETE NEUCHATELOISE DES SCIENCES NATURELLES', 'Bull. Soc. Neuchatel. Sci. Nat.', 'Neuchatel, Suica, CH : Societe Neuchateloise Des Sciences Naturelles', '03663469', NULL, 'Anual', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3030, 1, 'MENOPAUSE', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3031, 1, 'BULLETIN DE LA SOCIETE ROYALE DE BOTANIQUE DE BELGIQUE', 'Bull. Soc. R. Bot. Belg.', 'Brussels, BE : Societe Royale De Botanique De Belgique', '00379557', NULL, 'Semestral', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3032, 1, 'BULLETIN OF ALLOY PHASE DIAGRAMS', 'Bull. Alloy Phase Diagr.', 'Metals Park, Ohio, US : American Society For Metals, c1980-', '01970216', NULL, 'Bimestral', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3033, 1, 'VETERINARY RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3034, 1, 'MATURITAS', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3035, 1, 'CANADIAN JOURNAL OF PSYCHIATRY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3037, 1, 'ISVESTIIA AKADEMII NAUK SSSR. METALLY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3038, 1, 'GENETICA', '', 'Dordrecht, Holanda, NL : Junk', '00166707', NULL, 'Irregular', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3039, 1, 'ANNUAL REVIEW OF SEX RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3040, 1, 'INTERNATIONAL JOURNAL OF PHYTOTHERAPY AND PHYTOPHARMACOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3041, 1, 'PHOTOGRAMMETRIC  ENGINEERING AND REMOTE SENSIN', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3042, 1, 'GENOME BIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3043, 1, 'FUNDAMENTAL AND CLINICAL PHARMACOLOGY', 'Fundam. Clin. Pharmacol.', 'Paris, FR : Elsevier', '07673981', NULL, 'Bimestral', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3044, 1, 'FUEL SCIENCE AND TECHNOLOGY INTERNATIONAL', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3045, 1, 'CRYOBIOLOGY', '', 'San Diego [etc.] Academic Press', '00112240', NULL, 'Bimonthly', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3046, 1, 'REVISTA ARGENTINA DE MICROBIOLOGIA', '', '', '0325-7541', NULL, '', '2015-09-01 14:24:54', '2015-09-01 14:25:03'),
(3047, 1, 'COMPTES RENDUS DE L\'ACADEMIE DES SCIENCES DE L\'URSS', 'Dokl. Akad. nauk SSSR', 'Leningrad : Izd-vo Akademii nauk SSSR, 1933-1992', '00023264', NULL, 'Six no. a year, 1933 // Três vezes no mês', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3048, 1, 'PHOSPHORUS, SULFUR AND SILICON', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3049, 1, 'BIOSEPARATION', '', 'Dordrecht, Holanda, NL : Kluwer Academic Publishers', '0923179X', NULL, 'Bimestral', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3050, 1, 'JOURNAL OF VACUUM SCIENCE AND TECHNOLOGY A', 'J Vac Sci Tech', '', '0734-2101 (e): 1520-8559', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3051, 1, 'JOURNAL OF BIOLOGICAL INORGANIC CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14');
INSERT INTO `journal` (`id`, `instance_id`, `name`, `abbreviation`, `responsible`, `ISSN`, `ISSNE`, `frecuency`, `createdAt`, `updatedAt`) VALUES
(3052, 1, 'BOGAWUS. ZEITSCHRIFT FUR LITERATUR, KUNST UND PHILOSOPHIE', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3053, 1, 'TRANSPORTATION RESEARCH RECORD', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3054, 1, 'CURRENT ADVANCES IN MATERIAL AND PROCESSES', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3055, 1, 'INTERNATIONAL JOURNAL OF POLYMER ANALYSIS AND CHARACTERIZATION', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3056, 1, 'IEEE TRANSACTIONS ON INSTRUMENTATION AND MEASUREMENTS', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3057, 1, 'ACS DIVISION OF FUEL CHEMISTRY PREPRINTS', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3058, 1, 'ARCHIVOS DE MEDICINA VETERINARIA', '', 'Valdivia, Chile, CL : Asociacion Nacional De Escuelas De Medicina Veterinaria', '0301732X', NULL, 'Semestral', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3059, 1, 'HRANA I ISHRANA. FOOD AND NUTRITION', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3060, 1, 'CANADIAN JOURNAL OF FOREST RESEARCH', 'Can. J. For. Res.', 'Ottawa, CA : National Research Council', '00455067', NULL, 'Quarterly  // Mensal', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3061, 1, 'INORGANIC CHEMISTRY COMMUNICATIONS', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3062, 1, 'FLAVOUR AND FRAGRANCE JOURNAL', 'Flavour Fragr. J.', 'Chichester, Inglaterra , GB : Wiley', '08825734', NULL, 'Trimestral', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3063, 1, 'REVISTA DEL MUSEO ARGENTINO DE CIENCIAS NATURALES', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3064, 1, 'POLISH JOURNAL OF VETERINARY SCIENCES', 'Pol J Vet Sci', '', '1505-1773', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3065, 1, 'JSMFD: JOURNAL OF THE SOIL MECHANICS AND FOUNDATIONS DIVISION', '', '', '0044-7994', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3066, 1, 'TURRIALBA', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3067, 1, 'AMERICAN ANTHROPOLOGIST', 'Am. anthropol', 'Washington, D.C., etc. : American Anthropological Association, etc.], 1888- // Lancaster, Pa., US :', '00027294', NULL, 'Frequency varies / Quarterly, <1983->', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3068, 1, 'AUSTRALIAN JOURNAL OF BOTANY', 'Aust. J. Bot.', 'Melbourne, Aust. : Commonwealth Scientific and Industrial Research Organization, 1953-', '00671924', NULL, 'Irregular, Mar. 1953- ; 6 no. a year <, Aug. 1977->', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3069, 1, 'FOOD QUALITY AND PREFERENCE', 'Food Qual. Prefer.', 'Barking, Inglaterra, GB : Elsevier Applied Science', '09503293', NULL, 'Trimestral', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3070, 1, 'JOURNAL OF AEROSOL SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3071, 1, 'CHINESE JOURNAL OF STRUCTURAL CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3072, 1, 'CO-TEXTES', 'Etud. Sociocrit. Co Textes', 'Montpellier : Université Paul-Valéry , cop1980 // Centre D´Etudes Et Recherches Sociocritiques', '02496356', NULL, 'Irregular', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3073, 1, 'SCIENTIFIC AND TECHNICAL REVIEW', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3075, 1, 'JOURNAL OF PHYSICAL AND CHEMICAL REFERENCE DATA', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3076, 1, 'STRUCTURE', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3077, 1, 'NANO LETTERS', '', '', '1530-6984', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3078, 1, 'PHARMACEUTICAL BIOLOGY', 'Pharmaceut Biol', '', '1388-0209', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3079, 1, 'UNIVERSIDAD: CIENCIA Y TECNOLOGÍA', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3080, 1, 'ZEITSCHRIFT FUR NATURFORSCHUNG. TEIL A, ASTROPHYSIK, PHYSIK UND PHYSIKALISCHE CHEMIE', '', '', '0044-3166', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3081, 1, 'ANAIS DA ACADEMIA BRASILEIRA DE CIENCIAS', 'An. Acad. Bras. Cienc.', 'Rio de Janeiro : A Academia, 1941-', '00013765', NULL, 'Quarterly', '2015-09-01 14:24:54', '2015-09-01 14:25:08'),
(3082, 1, 'STARCH', '', '', '0038-9056', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3083, 1, 'REVUE SCIENTIFIQUE ET TECHNIQUE', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3084, 1, 'GEOCHIMICA BRASILIENSIS', 'Geochim. Bras.', 'Rio De Janeiro, RJ : Sociedade Brasileira De Geoquimica', '01029800', NULL, 'Semestral', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3085, 1, 'INTERNATIONAL JOURNAL OF COMPUTER VISION', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3086, 1, 'ANTIVIRAL RESEARCH', '', 'Amsterdam, NL : Elsevier Science Publishers', '01663542', NULL, 'Bimestral', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3087, 1, 'BRITISH JOURNAL OF DEVELOPMENTAL PSYCHOLOGY', 'Br. J. Dev. Psychol.', 'Leicester, Inglaterra, GB : British Psychological Society', '0261510X', NULL, 'Trimestral', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3088, 1, 'PROCEEDINGS OF THE ARISTOTELIAN SOCIETY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3089, 1, 'MIND', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3090, 1, 'THE JOURNAL OF MATERNAL-FETAL MEDICINE', 'J Matern Fetal Med', '', '1057-0802', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3091, 1, 'JAPANESE JOURNAL OF LEGAL MEDICINE', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3092, 1, 'INDIAN JOURNAL OF HETEROCYCLIC CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3093, 1, 'ANNALES DE L\'INSTITUT NATIONAL DE LA RECHERCHE AGRONOMIQUE', '', 'Paris, FR: Institut National de la Recherche Agronomique', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3094, 1, 'NEUROPSYCHOPHARMACOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3095, 1, 'PROGRESS IN SOLID STATE CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3096, 1, 'PROGRSS IN BATTERIES AND BATTERY MATERIALS', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3097, 1, 'PROGRESS IN BATTERIES AND BATTERY MATERIALS', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3098, 1, 'SPECTROCHIMICA ACTA PART B: ATOMIC SPECTROSCOPY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3099, 1, 'THERMAL ANALYSIS', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3100, 1, 'JOURNAL OF SUPERCRITICAL FLUIDS', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3101, 1, 'TERATOGENESIS, CARCINOGENESIS, AND MUTAGENESIS', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3102, 1, 'BROMATOLOGIA I CHEMIA TOKSYKOLOGICZNA', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3103, 1, 'JOURNAL OF INVESTIGATIVE MEDICINE', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3104, 1, 'CURRENT OPINION IN LIPIDOLOGY', 'Curr. Opin. Lipidol.', 'London : Current Science , c1990-', '09579672', NULL, 'Bimestral', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3105, 1, 'RECUEIL DE MEDECINE VETERINAIRE', '', '', '', NULL, '', '2015-09-01 14:24:54', '2015-09-01 14:25:10'),
(3106, 1, 'PHYTOTHERAPY RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3107, 1, 'NIGERIAN JOURNAL OF NATURAL PRODUCTS AND MEDICINE', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3108, 1, 'FITOTERAPIA', 'Fitoterapia (Milano)', 'Milan, Italia, IT : Inverni Della Beffa, 1925-', '0367326X', NULL, 'Bimestral', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3109, 1, 'JOURNAL OF SEPARATION SCIENCE', 'J Separ Sci', '', '1615-9306 (e): 1615-9314', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3110, 1, 'TURTOX NEWS', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3111, 1, 'WIENER STUDIEN ZEITSCHRIFT FUR KLASSISCHE PHILOLOGIE UND PATRISTIK', '', '', '0084-005X', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3112, 1, 'PHARMACY AND PHARMACOLOGY COMMUNICATIONS', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3113, 1, 'DRUG DEVELOPMENT AND INDUSTRIAL PHARMACY', 'Drug Dev. Ind. Pharm.', 'New York : Dekker, 1977-', '03639045', NULL, '14 no. a year <, Feb. 1986->', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3114, 1, 'JOURNAL OF PHARMACOLOGICAL AND TOXICOLOGICAL METHODS', 'J Pharmacol Toxicol Meth', '', '1056-8719', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3115, 1, 'BIOESSAYS', 'BioEssays', 'Cambridge, UK ; New York, N.Y., U.S.A. : Published for ICSU Press by Cambridge University Press, c19', '02659247', NULL, 'Monthly', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3116, 1, 'JOURNAL OF PHILOSOPHY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3117, 1, 'RATIO', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3118, 1, 'REVISTA IBEROAMERICANA', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3119, 1, 'ION GPS', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3120, 1, 'MACROMOLECULAR MATERIALS AND ENGINEERING', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3121, 1, 'ANAIS DA ESCOLA SUPERIOR DA AGRICULTURA LUIZ DE QUEIROZ', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3122, 1, 'ARS COMBINATORIA', 'Ars Comb.', 'Waterloo, Canada, CA : University Of Waterloo, Department Of Combinatorics And Optimization', '03817032', NULL, 'Semestral', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3123, 1, 'ZIMBABWE JOURNAL OF AGRICULTURAL RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3124, 1, 'JOURNAL OF SEED TECHNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3125, 1, 'BOLETIN DEL MUSEO NACIONAL DE HISTORIA NATURAL (SANTIAGO, CHILE)', 'Bol. Mus. Nac. Hist. Nat.', 'Santiago de Chile : El Museo, 1937-1983', '00273910', NULL, 'Irregular', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3126, 1, 'BULLETIN OF THE POLYTECHNIC INSTITUTE OF IASI', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3127, 1, 'ATOMIC ABSORPTION NEWSLETTER', 'At. Absorpt. Newsl.', 'Norwalk, Conn. : Perkin-Elmer Corp., 1962-1979', '00449954', NULL, 'Monthly (irregular) 1962-<65> ; Bimonthly, 1966-', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3128, 1, 'CMI DESCRIPTIONS OF PATHOGENIC FUNGI AND BACTERIA', 'Cmi Descr. Pathog. Fungi Bact.', 'Kew, Inglaterra, GB : Commonwealth Mycological Institute', '00099716', NULL, 'Trimestral', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3129, 1, 'IN PRACTICE', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3130, 1, 'CADERNOS DO SEMINARIO DE SARGADELOS', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3131, 1, 'PHYTOPATHOLOGISCHE ZEITSCHRIFT = JOURNAL OF PHYTOPATHOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3132, 1, 'ACTA MICROBIOLOGICA HUNGARICA', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3133, 1, 'INDIAN JOURNAL OF MICROBIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3134, 1, 'ACTA CHIMICA SINICA', 'Acta Chimi. Sin.', 'Peking, CN : Science Press', '02567660', NULL, 'Trimestral', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3135, 1, 'JOURNAL OF PALEONTOLOGY', '', '', '0022-3360', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3136, 1, 'PROGRESS IN BRAIN RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3137, 1, 'NATURE REVIEWS NEUROSCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3138, 1, 'BRAIN RESEARCH BULLETIN', 'Brain Res. Bull.', 'New York, US : Elsevier Science', '03619230', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3139, 1, 'GLIA', 'Glia', 'New York [etc.] : John Wiley and Sons, 1988 // Alan R. Liss', '08941491', NULL, 'Monthly /7 Bimestral', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3140, 1, 'TRENDS IN NEUROSCIENCES', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3141, 1, 'EUROPEAN ARCHIVES OF PSYCHIATRY AND CLINICAL NEUROSCIENCE', 'Eur. Arch. Psychiatr. Clin. Neurosci.', 'Berlin, DE : Springer', '09401334', NULL, 'Bimestral', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3142, 1, 'PHARMACOLOGICAL RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3143, 1, 'PAIN', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3145, 1, 'JOURNAL OF NEUROSCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3146, 1, 'INTERNATIONAL JOURNAL OF ANDROLOGY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3147, 1, 'JOURNAL OF GEOMAGNETISM AND GEOELECTRICITY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3148, 1, 'TOPICS IN EARLY CHILDHOOD SPECIAL EDUCATION', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3149, 1, 'SCANDINAVIAN JOURNAL OF CLINICAL & LABORATORY INVESTIGATION', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3150, 1, 'MENTAL RETARDATION', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3151, 1, 'PLANT PROTECTION QUARTERLY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3152, 1, 'CURRENT OPINION IN NEUROBIOLOGY', 'Curr. Opin. Neurobiol.', 'Philadelphia, EE.UU // London, UK : Current Biology Ltd., c1991-', '09594388', NULL, 'Bimonthly', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3153, 1, 'SOIL AND TILLAGE RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3154, 1, 'AGRICULTURAL SYSTEMS', '', 'Essex, Inglaterra, GB : Elsevier Applied Science', '0308521X', NULL, 'Mensal', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3155, 1, 'CIENCIA DEL SUELO', 'Cienc. Suelo', 'La Plata : Asociación Argentina de la Ciencia del Suelo , 1983', '03263169', NULL, 'Semestral', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3156, 1, 'THE AUK', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3157, 1, 'ECOLOGICAL ENTOMOLOGY', '', 'Oxford ; Boston [etc.] : Blackwell Scientific Publications, 1976-', '03076946', NULL, 'Quarterly', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3158, 1, 'LIVESTOCK PRODUCTION SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3159, 1, 'INTERNATIONAL JOURNAL OF ROBOTICS RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3160, 1, 'EUROPEAN JOURNAL OF OBSTETRICS, GYNECOLOGY AND REPRODUCTIVE BIOLOGY', 'Eur. J. Obstet. Gynecol. Reprod. Biol.', 'Amsterdam, NL : Elsevier Science Publishers', '03012115', NULL, 'Monthly', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3161, 1, 'MOLECULAR HUMAN REPRODUCTION', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3162, 1, 'TRANSACTIONS OF THE CHARLES S. PEIRCE SOCIETY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3163, 1, 'JOURNAL OF CARBOHYDRATE CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3164, 1, 'INTERNATIONAL JOURNAL OF MASS SPECTROMETRY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3165, 1, 'JOURNAL OF HIGH RESOLUTION CHROMATOGRAPHY & CHROMATOGRAPHIC COMMUNICATIONS', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3166, 1, 'APPLICATIONS OF SURFACE SCIENCE', '', 'Amsterdam, NL : North Holland Publishing', '03785963', NULL, 'Trimestral', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3167, 1, 'ANALYTICAL AND QUANTITATIVE CYTOLOGY AND HISTOLOGY', 'Anal. quant. cytol. histol', 'St. Louis, MO : Science Printers and Publishers, 1985-', '', NULL, 'Quarterly', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3168, 1, 'ZEITSCHRIFT FÜR NATURFORSCHUNG. TEIL B, ANORGANISCHE CHEMIE, ORGANISCHE CEIME', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3169, 1, 'IL NUOVO CIMENTO', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3170, 1, 'CHINESE JOURNAL OF CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3171, 1, 'METHODS', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3172, 1, 'METHODS: A COMPANION TO METHODS IN ENZYMOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3173, 1, 'RESEARCH COMMUNICATIONS IN CHEMICAL PATHOLOGY AND PHARMACOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3174, 1, 'OPTICS AND LASER TECHNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3175, 1, 'REVISTA DE FILOLOGIA HISPANICA', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3176, 1, 'NUEVA REVISTA DE FILOLOGIA HISPANICA', '', '', '01850121', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3177, 1, 'STAIN TECHNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3178, 1, 'MOLECULAR CRYSTALS AND LIQUID CRYSTALS', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3179, 1, 'ACTA PATHOLOGICA ET MICROBIOLOGICA SCANDINAVICA', 'Acta Pathol. Microbiol. Scand.', 'Copenhagen, Munksgaard', '03655555', NULL, 'Bimestral', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3180, 1, 'NEURORADIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3181, 1, 'ICARUS', 'Icarus (New York)', ' New York  // San Diego, Calif., US : Academic Press', '00191035', NULL, 'Mensal', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3182, 1, 'CURRENT GENETICS', 'Curr. Genet.', 'Berlin ; New York, N.Y. : Springer-Verlag', '01728083', NULL, 'Three no. a year, 1979-; Six no. a year, <1981->', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3183, 1, 'METHODS IN BIOTECHNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3184, 1, 'SCANDINAVIAN JOURNAL OF GASTROENTEROLOGY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3185, 1, 'inorg. mater (engl. transl)', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3186, 1, 'INORGANIC MATERIALS', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3187, 1, 'PROPUESTA EDUCATIVA', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3188, 1, 'PHARMACOGENOMICS', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3189, 1, 'TECHNOLOGY REPORTS OF THE OSAKA UNIVERSITY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3190, 1, 'ADVANCES IN MASS SPECTROMETRY', 'Adv. Mass Spectrom.', 'Oxford, Inglaterra, GB : Applied Science Publishers. // Chichester ; New York, N.Y. : Wiley, c1986-', '08872430', NULL, 'Triennial', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3191, 1, 'NBS monograph: NATIONAL BUREAU OF STANDARS', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3192, 1, 'SMITHSONIAN CONTRIBUTIONS TO ZOOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3193, 1, 'GENETICS AND MOLECULAR BIOLOGY', 'Genet. Mol. Biol.', 'Ribeirao Preto, SP : Sociedade Brasileira De Genetica', '14154757', NULL, 'Trimestral', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3194, 1, 'REVISTA DE INVESTIGACIONES AGROPECUARIAS', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3195, 1, 'PATHOLOGY', '', '', '0031-3025', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3196, 1, 'BIOCHIMICA ET BIOPHYSICA ACTA. GENERAL SUBJECTS', 'Biochim. biophys. acta (G) // Biochim. Biophys. Acta. Gen. S', 'Amsterdam : Elsevier', '03044165', NULL, 'Irregular', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3197, 1, 'INORGANIC AND ORGANOMETALLIC POLYMERS', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3198, 1, 'PROGRESS IN CARDIOVASCULAR DISEASES', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3199, 1, 'REVUE INTERNATIONALE DES HAUTES TEMPERATURES ET DES REFRACTAIRES', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3200, 1, 'EUROSURVEILLANCE', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3201, 1, 'ENDOCRINE', '', 'Houndmills, Basingstoke, Hants, UK : Macmillan Press, c1994-', '', NULL, 'Six no. a year', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3202, 1, 'PROCEEDINGS OF THE AMERICAN ACADEMY OF ARTS AND SCIENCES', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3203, 1, 'ESTACION EXPERIMENTAL AGROPECUARIA DELTA DEL PARANA SERIE MISCELANEAS', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3204, 1, 'FRONTIERS OF HORMONE RESEARCH', 'Front. Horm. Res.', 'Basel, New York, Karger, 1972-', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3205, 1, 'DRUG METABOLISM REVIEWS', 'Drug. Metab. Rev. (Softcover ed.)', 'New York, Marcel Dekker, 1972-', '03602532', NULL, '8 no. a year <, June 1984->', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3206, 1, 'LC GC NORTH AMERICA', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3207, 1, 'ADVANCED DRUG DELIVERY REVIEWS', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3208, 1, 'IN VIVO', 'In Vivo', '', '0258-851X (Prin', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3209, 1, 'RESIDUOS', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3210, 1, 'MEMOIRES DE LE HERBIER BOISSIER', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3211, 1, 'OPTICAL FIBER TECHNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3212, 1, 'PUBLIC LIBRARIES', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3213, 1, 'INTERNATIONAL JOURNAL FOR NUMERICAL AND ANALYTICAL METHODS IN GEOMECHANICS', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3214, 1, 'JOURNAL OF IRRIGATION AND DRAINAGE ENGINEERING', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3215, 1, 'TRANSACTIONS OF THE AMERICAN PHILOSOPHICAL SOCIETY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3216, 1, 'HOMO', 'Homo', 'Gottingen, [etc.] : Musterschmidt, 1949- // Stuttgart, Alemanha, DE : Fischer', '0018442x', NULL, 'Irregular, 1949-50 ; 4 no. a year, 1951-', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3217, 1, 'TRENDS IN GENETICS', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3218, 1, 'BULLETIN DE LA SOCIETE VAUDOISE DES SCIENCES NATURELLES', 'Bull. Soc. Vaudoise Sci. Nat.', 'Lausanne, Suica, CH : Societe Vaudoise Des Sciences Naturelles', '00379603', NULL, 'Irregular', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3219, 1, 'ZEITSCHRIFT FUR ZOOLOGISCHE SYSTEMATIK UND EVOLUTIONSFORSCHUNG', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3220, 1, 'IZVESTIIA AKADEMII NAUK TURKMENSOI SSR SERIIA BIOLOGICHESKIKH NAUK', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3221, 1, 'ANALES DEL INSTITUTO DE BIOLOGIA / UNIVERSIDAD NACIONAL AUTONOMA DE MEXICO', 'An. Inst. Biol. / Univ. Nac. Auton. Mex.', 'México : El Instituto, 1930-1966', '00767174', NULL, 'Quarterly, 1930-39 ; Two no. a year, 1940-66', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3222, 1, 'ENTOMOLOGIST', '', 'London, GB : Royal Entomological Society Of London', '00138878', NULL, 'Trimestral', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3223, 1, 'MAGAZINE OF NATURAL HISTORY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3224, 1, 'CENTRAL AFRICAN JOURNAL OF MEDICINE', 'Cent. Afr. J. Med.', 'Salisbury, Zimbabwe, ZW : Central African Journal Of Medecine', '00089176', NULL, 'Mensal', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3225, 1, 'EL HORNERO', '', 'Buenos Aires, AR : Asociacion Ornitologica Del Plata', '00733407', NULL, 'Irregular', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3226, 1, 'TURKIYE PARAZITOLOJI DERGISI', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3227, 1, 'JOURNAL DE PHYSIQUE COLLOQUE', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3228, 1, 'VETERINARY BULLETIN', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3229, 1, 'MOLECULAR PSYCHIATRY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3230, 1, 'KOREAN JOURNAL OF SYSTEMATIC ZOOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3231, 1, 'CHINA ELASTOMERICS', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3232, 1, 'GAZZETTA CHIMICA ITALIANA', 'Gazz. Chim. Ital.', 'Roma : Societ# Chimica Italiana', '00165603', NULL, 'Monthly', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3233, 1, 'CHINESE JOURNAL OF PARASITIC DISEASE CONTROL', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3234, 1, 'JOURNAL OF BENGAL NATURAL HISTORY SOCIETY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3235, 1, 'BULLETIN DE LA SOCIETE ENTOMOLOGIQUE DE FRANCE', 'Bull. Soc. Entomol. France', 'Paris, FR : Societe Entomologique De France', '0037928X', NULL, 'Bimestral', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3236, 1, 'TRENDS IN ORGANIC CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3237, 1, 'ZENTRALBLATT FUR MIKROBIOLOGIE', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3238, 1, 'APPLIED MATHEMATICS AND COMPUTATION', 'Appl. math. comput.', 'New York, N.Y. : Elsevier [etc.], 1975-', '00963003', NULL, 'Irregular // Trimestral', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3239, 1, 'BIOLOGISCHES CENTRALBLATT', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3240, 1, 'INTERNATIONAL JOURNAL OF FOOD SCIENCES AND NUTRITION', '', '', '0963-7486', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3241, 1, 'JOURNAL OF STRUCTURAL BIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3242, 1, 'JOURNAL (AMERICAN WATER WORKS ASSOCIATION)', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3243, 1, 'CURRENT OPINION IN INFECTIOUS DISEASES', 'Curr. Opin. Infect. Dis.', 'Philadelphia, Pa., US : Current Science', '09517375', NULL, 'Bimestral', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3244, 1, 'ADVANCES IN CELL CULTURE', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3245, 1, 'TURKISH JOURNAL OF PEDIATRICS', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3246, 1, 'PEDIATRIC CARDIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3247, 1, 'IEEE TRANSACTIONS ON INDUSTRY APPLICATIONS', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3248, 1, 'INTERNATIONAL SYMPOSIUM ON CIRCUITS AND SYSTEMS', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3249, 1, 'LATIN AMERICAN LITERARY REVIEW', 'Lat Am Lit Rev', '', '0047-4134', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3250, 1, 'INDUSTRIAL AND ENGINEERING CHEMISTRY FUNDAMENTALS', '', '', '0888-5885', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3251, 1, 'JOURNAL OF ADHESION SCIENCE AND TECHNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3252, 1, 'GIORNALE DELLA ACCADEMIA DI MEDICINA DI TORINO', 'G. Accad. Med. Torino', 'Torino, Italia, IT : Accademia Di Medicina', '03674770', NULL, 'Mensal', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3253, 1, 'REVUE DE MEDECINE VETERINAIRE', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3254, 1, 'SOUTH AFRICAN INDUSTRIAL CHEMIST', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3255, 1, 'PROCEEDINGS OF THE ESTONIAN ACADEMY OF SCIENCES. CHEMISTRY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3256, 1, 'CHIRALITY', 'Chirality', 'New York, US : Alan R. Liss', '0899-0042 (e): 1520-636X', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3257, 1, 'REDES Revista de Estudios Sociales de la Ciencia', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3258, 1, 'INTERNATIONALE ZEITSCHRIFT FÜR VITAMINFORSCHUNG = INTERNATIONAL JOURNAL FOR VITAMIN RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3259, 1, 'JOURNAL OF THE AGRICULTURAL CHEMICAL SOCIETY OF JAPAN', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3260, 1, 'MEDICAL REPOSITORY', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3261, 1, 'CATALYST', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3262, 1, 'ANNUAL REVIEW OF PSYCHOLOGY', 'Annu. rev. psychol', 'Stanford, Calif. ; Annual Reviews, 1950-', '00664308', NULL, 'Anual', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3263, 1, 'SIAM JOURNAL ON NUMERICAL ANALYSIS', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3264, 1, 'ZEITSCHRIFT FUR NATURFORSCHUNG TEIL B, CHEMIE, BIOCHEMIE, BIOPHYSIK, BIOLOGIE AND VERWANDTE GEBIETE', '', '', '0044-3174', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3265, 1, 'FLORE GENERALE DE BELGIQUE. SPERMATOPHYTES', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3267, 1, 'JOURNAL OF ENDOCRINOLOGICAL INVESTIGATION', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3268, 1, 'CATALYST = SHOKUBAI', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3269, 1, 'ANDROLOGIA', '', 'Berlin : Grosse , cop1969 // Berlin, DE : Blackwell Wissenschafts Verlag', '03034569', NULL, 'Bimestral', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3270, 1, 'THE NEW PHYTOLOGIST', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3271, 1, 'ANIMAL REPRODUCTION SCIENCE', 'Anim. Reprod. Sci.', 'Amsterdam : Elsevier, 1977-', '03784320', NULL, 'Trimestral', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3272, 1, 'RENDICONTI DI MATEMATICA E DELLE SUE APPLICAZIONI. SERIE VII.', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3273, 1, 'FOOD MICROBIOLOGY', 'Food Microbiol.', 'London, GB : Academic Press', '07400020', NULL, 'Trimestral', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3274, 1, 'RHEOLOGICA ACTA', 'Rheol Acta', '', '0035-4511 e: 1435-1528', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3275, 1, 'JUSTUS LIEBIGS ANNALEN DER CHEMIE', '', '', '0075-4617', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3276, 1, 'NEMATROPICA', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3277, 1, 'MICROBIOLOGICAL SCIENCES', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3278, 1, 'BIOSCIENCE', 'Bioscience', '[Washington, D.C.] : American Institute of Biological Sciences, [c1964-', '00063568', NULL, 'Monthly', '2015-09-01 14:24:54', '2016-01-12 14:16:14'),
(3279, 1, 'PROCEEDINGS OF THE SPIE. LASER BEAM PROPAGATION AND CONTROL', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:15'),
(3280, 1, 'NEUROIMMUNOMODULATION', 'Neuroimmunomodulation', '', '1021-7401', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:15'),
(3281, 1, 'OXFORD STUDIES IN ANCIENT PHILOSOPHY', 'Oxf. Stud. Anc. Philos.', 'Oxford : Clarendon Press ; New York : Oxford University Press, 1983-', '02657651', NULL, 'Annual', '2015-09-01 14:24:54', '2016-01-12 14:16:15'),
(3282, 1, 'BOLETIN DE LA SOCIEDAD ZOOLOGICA DEL URUGUAY SEGUNDA EPOCA', '', '', '', NULL, '', '2015-09-01 14:24:54', '2016-01-12 14:16:15'),
(3283, 1, 'FOCUS', 'Focus', 'Rockville, Md., US : Life Technologies', '', NULL, 'Quadrimestral', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3284, 1, 'HILGARDIA', 'Hilgardia', 'Berkeley, Calif. : California Agricultural Experiment Station, 1925-', '00732230', NULL, 'Irregular', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3285, 1, 'BIOREMEDIATION JOURNAL', '', 'Boca Raton, FL : CRC Press, Inc.', '10889868', NULL, 'Quarterly', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3286, 1, 'LIGHT METAL AGE', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3287, 1, 'LIGHT METALS', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3288, 1, 'JOURNAL OF DENTAL TECHNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3289, 1, 'JOURNAL OF ORAL IMPLANTOLOGY', '', '', '0160-6972', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3290, 1, 'MEDEDELINGEN (RIJKSUNIVERSITEIT TE GENT. FACULTEIT LANDBOUWKUNDIGE EN TOEGEPASTE BIOLOGISCHE WETENSCHAPPEN)', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3291, 1, 'PUBLIC UTILITIES FORTNIGHTLY', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3292, 1, 'SAN FRANCISCO CHRONICLE', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3293, 1, 'NHA NEWS', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3294, 1, 'JOURNAL OF THE ROYAL STATISTICAL SOCIETY. SERIES B (METHODOLOGICAL)', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3295, 1, 'BIOTECHNOLOGY & GENETIC ENGINEERING REVIEWS', 'Biotechnol. Genet. Eng. Rev.', 'Newcastle upon Tyne, England : Intercept, c1984-', '02648725', NULL, 'Anual', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3296, 1, 'SYSTEMATIC  BIOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3297, 1, 'PHILOSOPHICAL MAGAZINE', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3298, 1, 'PHYSICS', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3299, 1, 'JOURNAL OF THE INSTITUTE OF BREWING', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3300, 1, 'ALCOHOL', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3301, 1, 'MILITARY MEDICINE', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3302, 1, 'CHEMIJA', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3303, 1, 'BOLETIM DO MUSEU NACIONAL NOVA SERIE ZOOLOGIA (RIO DE JANEIRO)', 'Bol. Mus. Nac., Nova sér., Zool.', 'Rio de Janeiro : O Museu', '0080312X', NULL, 'Irregular', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3304, 1, 'EUROPEAN JOURNAL OF PHARMACEUTICAL SCIENCES', 'Eur. J. Pharm. Sci.', 'Amsterdam ; New York : Elsevier, c1993-', '09280987', NULL, 'Six issues yearly', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3305, 1, 'CHEMICAL AND BIOCHEMICAL ENGINEERING QUARTERLY', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3306, 1, 'SOIL SCIENCE AND PLANT NUTRITION', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3307, 1, 'THE AMERICAN JOURNAL OF FORENSIC MEDICINE AND PATHOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3308, 1, 'BIOCHEMISTRY AND CELL BIOLOGY = BIOCHIMIE ET BIOLOGIE CELLULAIRE', 'Biochem. Cell Biol.', 'Ottawa: National Research Council Canada = Conseil National de Recherches Canada, c1986-', '08298211', NULL, 'Monthly // Bimestral', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3309, 1, 'JOURNAL OF CHEMICAL ENGINEERING OF CHINESE UNIVERSITIES', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3310, 1, 'ORGANIC SULFUR COMPOUNDS', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3311, 1, 'ESTUARINE AND COASTAL MARINE SCIENCE', 'Estuar. Coast. Mar. Sci.', 'London, GB : Academic Press', '03023524', NULL, 'Mensal', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3312, 1, 'MYKOSEN', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3313, 1, 'JOURNAL OF LONG-TERM EFFECTS OF MEDICAL IMPLANTS', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3314, 1, 'ACTA OBSTETRICIA ET GYNECOLOGICA SCANDINAVICA', 'Acta Obstet Gynecol Scand', '[Copenhagen, DK] : Munksgaard, // Lund: Hakan Ohlssons Boktryckeri , cop1921 // Stockholm, SE : Scan', '00016349', NULL, 'Actual Ocho n. al año // Anteriormente b', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3315, 1, 'PRENATAL DIAGNOSIS', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3316, 1, 'PLASTIC AND RECONSTRUCTIVE SURGERY', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3317, 1, 'ARCHIVES OF PEDIATRICS & ADOLESCENT MEDICINE', 'Arch. Pediatr. Adolesc. Med.', 'Chicago, Ill., US : American Medical Association', '10724710', NULL, 'Monthly', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3318, 1, 'GENETIC EPIDEMIOLOGY', 'Genet. epidemiol.', 'New York, US : Alan R. Liss', '07410395', NULL, 'Bimestral', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3319, 1, 'JOURNAL OF REPRODUCTIVE IMMUNOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3320, 1, 'ANNALS OF EPIDEMIOLOGY', 'Ann. Epidemiol.', 'New York, NY : Elsevier, c1990-', '', NULL, 'Quarterly', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3321, 1, 'THE CLEFT PALATE JOURNAL', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3322, 1, 'EARLY HUMAN DEVELOPMENT', 'Early Hum. Dev.', 'Amsterdam, Elsevier/North-Holland, 1977-', '03783782', NULL, 'Trimestral', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3323, 1, 'MINERVA GINECOLOGICA', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3324, 1, 'ZENTRALBLATT FUR GYNAKOLOGIE', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3325, 1, 'MUSEUM HELVETICUM : SCHWEIZERISCHE ZEITSCHRIFT FÜR KLASSISCHE ALTERTUMSWISSENSCHAFT = REVUE SUISSE POUR LÉTUDE DE LANTIQUITÉ CLASSIQUE = RIVISTA SUIZZERA DI FILOLOGIA CLASSICA', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3326, 1, 'CHEST', 'Chest', 'Chicago : American College of Chest Physicians , cop1970', '00123692', NULL, 'Mensal', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3327, 1, 'PROGRESS IN FOOD & NUTRITION SCIENCE', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3328, 1, 'BULLETIN OF THE KOREAN CHEMICAL SOCIETY', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3329, 1, 'PROCEEDINGS OF THE IABs SYMPOSIA SERIES', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3330, 1, 'OPERATIVE DENTISTRY', '', '', '03617734', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3331, 1, 'ECOLOGY LETTERS', 'Ecol. Lett.', 'Oxford: Blackwell Science', '1461023X', NULL, 'Bimonthly', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3332, 1, 'VETERINARY THERAPEUTICS', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3333, 1, 'JOURNAL OF HYPERTENSION', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3334, 1, 'CULTURED DAIRY PRODUCTS JOURNAL', 'Cult. Dairy Prod. J.', 'Washington, US : American Cultured Dairy Products Institute', '00459259', NULL, 'Trimestral', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3335, 1, 'WERKSTOFFE UND KORROSION', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3337, 1, 'GEOEXPLORATION', 'Geoexploration', 'Amsterdam : Elsevier Scientific Pub., 1963-', '00167142', NULL, 'Quarterly', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3338, 1, 'AUSTRALASIAN PLANT PATHOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3339, 1, 'JOURNAL OF INTERFERON AND CYTOKINE RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3340, 1, 'MILCHWISSENSCHAFT', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3341, 1, 'THROMBOSIS RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3342, 1, 'INTERNATIONAL JOURNAL OF OSTEOARCHAEOLOGY', '', '', '1047-482X (prin', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3343, 1, 'PROGRESS IN THEORETICAL PHYSICS', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3344, 1, 'ANTHROPOLOGIE', '', 'Basel, Suica, CH : [S.N.]', '0003553X', NULL, 'Quadrimestral', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3345, 1, 'INTERNATIONAL JOURNAL OF MECHANICAL SCIENCES', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3346, 1, 'MAGAZINE OF CONCRETE RESEARCH', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3347, 1, 'LA CHIMICA E LINDUSTRIA', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3348, 1, 'BULLETIN DE L\'ACADEMIE POLONAISE DES SCIENCES. SERIE DES SCIENCES CHIMIQUES', 'Bull. Acad. Pol. Sci. Ser. Sci. Chim.', 'Varsovie, PL : Academie Polonaise Des Sciences', '00014095', NULL, 'Mensal', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3349, 1, 'INTERNATIONAL JOURNAL OF MODERN PHYSICS. B, CONDENSED MATTER PHYSICS, STATISTICAL PHYSICS, APPLIED PHYSICS', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3350, 1, 'ACTA POLONIAE PHARMACEUTICA', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3351, 1, 'ADVANCES IN PEDIATRICS', 'Adv pediatr', 'New York, Interscience [etc.] // Chicago, Ill., US : Year Book Medical Publishers', '0065-3101', NULL, 'Anual', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3352, 1, 'YONSEI MEDICAL JOURNAL', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3353, 1, 'JOURNAL OF CUTANEOUS PATHOLOGY', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3354, 1, 'JOURNAL OF ENVIRONMENTAL ENGINEERING', 'J Environ Eng', '', '0733-9372  (e): 1943-7870', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3355, 1, 'VDI-BERICHTE', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3356, 1, 'PRACTICE PERIODICAL OF HAZARDOUS, TOXIC, AND RADIOACTIVE WASTE MANAGEMENT', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3357, 1, 'REVUE DES ETUDES GRECQUES', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3358, 1, 'CHEMICKÉ ZVESTI = CHEMICAL PAPERS', 'Chem. Zvesti', 'Bratislava : Slovenská akadémia vied', '03666352', NULL, '10 no. a year <1951>-1954; Twelve no. a year 1955-1969; 6 no. a year 1972-', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3359, 1, 'CANCER AND METASTASIS REVIEWS', 'Cancer Metastasis Rev.', 'Boston : Martinus Nijhoff, 1985-', '01677659', NULL, 'Quarterly', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3360, 1, 'GENOME RESEARCH', '', 'New York, US : Cold Spring Harbor', '10889051', NULL, 'Mensal', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3361, 1, 'INTERNATIONAL JOURNAL OF COMPUTER MATHEMATICS', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3362, 1, 'AMERICAN JOURNAL OF PHYSIOLOGY: ADVANCES IN PHYSIOLOGY EDUCATION', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3363, 1, 'SOCIAL STUDIES OF SCIENCE', '\'\'', '\'\'', '0', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3364, 1, 'CURRENT DRUG TARGETS - CNS AND NEUROLOGICAL DISORDERS', '\'\'', '\'\'', '0', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3365, 1, 'EPILEPSY RESEARCH', 'Epilepsy Res.', 'Amsterdam, NL : Elsevier Science Publishers', '09201211', NULL, 'Bimestral', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3366, 1, 'EUROPEAN JOURNAL OF ANAESTHESIOLOGY', 'Eur. J. Anaesthesiol.', 'Oxford, Inglaterra, GB : Blackwell Scientific Publications', '02650215', NULL, 'Bimestral', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3367, 1, 'ABSTRACTS (GEOLOGICAL SOCIETY OF AMERICA)', '', 'New York : [The Society, c1962-1968]', '', NULL, 'Anual', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3368, 1, 'JOURNAL OF THE AMERICAN CONCRETE INSTITUTE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3369, 1, 'ACTA CHEMICA SCANDINAVICA. SERIES A: PHYSICAL AND INORGANIC CHEMISTRY', 'Acta chem. Scand. Ser. A., phys. & inorg. chem', 'Copenhagen : Munksgaard, 1974-1988', '03024377', NULL, '10 no. a year', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3370, 1, 'ACTA CHEMICA SCANDINAVICA (1947)', 'Acta Chem. Scand. (1947)', 'Copenhagen : Munksgaard, 1947-1973', '00015393', NULL, 'Ten no. a year', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3371, 1, 'ACTA CHEMICA SCANDINAVICA (1989)', 'Acta Chem. Scand. (1989)', 'Copenhagen : Munksgaard, c1989-', '0904213X', NULL, 'Monthly (except June and Dec.)', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3372, 1, 'ACTA SCIENTARUM MATHEMATICARUM', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3373, 1, 'EPILEPTIC DISORDERS', '\'\'', '\'\'', '0', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3375, 1, 'PEST MANAGEMENT SCIENCE', '\'\'', '\'\'', '0', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3376, 1, 'ADVANCES IN PHYSIOLOGY EDUCATION', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3377, 1, 'READING RESEARCH QUARTERLY', '\'\'', '\'\'', '0', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3378, 1, 'JOURNAL OF AIR POLLUTION CONTROL ASSOCIATION', '\'\'', '\'\'', '0', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3379, 1, 'ACTA CRYSTALLOGRAPHICA. SECTION A, CRYSTAL PHYSICS, DIFFRACTION, THEORETICAL AND GENERAL CRYSTALLOGRAPHY', 'Acta crystallogr., Sect. A. Cryst. phys. diffr. theor. gen.', 'Copenhagen : Published for the International Union of Crystallography by Munksgaard, c1967-c1982', '05677394', NULL, 'Bimonthly', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3380, 1, 'MINI-REVIEWS IN MEDICINAL CHEMISTRY', '\'\'', '\'\'', '0', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3381, 1, 'ACTA CRYSTALLOGRAPHICA. SECTION D, BIOLOGICAL CRYSTALLOGRAPHY', '', 'Copenhagen : Published for the International Union of Crystallography by Munksgaard, International B', '09074449', NULL, 'Bimonthly', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3382, 1, 'OSTERREICHISCHES INGENIEUR-ARCHIV', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3383, 1, 'JOURNAL OF INTERNAL MEDICINE. SUPPLEMENT', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3384, 1, 'ACTA GYNECOLOGICA SCANDINAVICA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3385, 1, 'ACTA OECOLOGICA. SER. 1, OECOLOGIA GENERALIS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3386, 1, 'ACTA OECOLOGICA . SER. 2, OECOLOGIA APPLICATA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3387, 1, 'ACTA OECOLOGICA . SER. 3, OECOLOGIA PLANTARUM', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3388, 1, 'ACTA PATHOLOGICA ET MICROBIOLOGICA SCANDINAVICA. SECTION A, PATHOLOGY', 'Acta Pathol. Microbiol. Scand. Sect. A, Pathol.', 'Copenhagen, Munksgaard', '03654184', NULL, 'Bimestral', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3389, 1, 'ACTA PATHOLOGICA ET MICROBIOLOGICA SCANDINAVICA. SECTION B, MICROBIOLOGY', 'Acta Pathol. Microbiol. Scand. Sect. B, Microbiol.', 'Copenhagen, DK : Munksgaard', '0304131X', NULL, 'Bimestral', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3390, 1, 'ACTA PATHOLOGICA ET MICROBIOLOGICA SCANDINAVICA. SECTION C, IMMUNOLOGY', 'Acta Pathol. Microbiol. Scand. Sect. C, Immunol.', 'Copenhagen, Munksgaard', '03041328', NULL, 'Bimestral', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3391, 1, 'ACTA PATHOLOGICA, MICROBIOLOGICA, ET IMMUNOLOGICA SCANDINAVICA. SECTION A, PATHOLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3392, 1, 'ACTA PATHOLOGICA, MICROBIOLOGICA, ET IMMUNOLOGICA SCANDINAVICA. SECTION B, MICROBIOLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3393, 1, 'ACTA PATHOLOGICA, MICROBIOLOGICA, ET IMMUNOLOGICA SCANDINAVICA. SECTION C, IMMUNOLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3394, 1, 'ACTA PHARMACEUTICA NORDICA', '', '', '', NULL, '\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3395, 1, 'ACTA PHYSICA POLONICA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3396, 1, 'ADVANCES IN ATOMIC, MOLECULAR, AND OPTICAL PHYSICS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3397, 1, 'ADVANCES IN SPACE EXPLORATION', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3398, 1, 'LIFE SCIENCES AND SPACE RESEARCH', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3399, 1, 'SPACE RESEARCH', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3400, 1, 'AFS CAST METALS RESEARCH JOURNAL', '', '', '', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3401, 1, 'LARGE ANIMAL PRACTICE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3402, 1, 'BULLETIN OF THE AGRICULTURAL CHEMICAL SOCIETY OF JAPAN', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3403, 1, 'BIOSCIENCE, BIOTECHNOLOGY AND BIOCHEMISTRY', 'Biosci., Biotechnol. Biochem.', 'Tokyo, JP : Japan Society For Bioscience, Biotechnology And Agrochemistry', '09168451', NULL, 'Mensal', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3404, 1, 'AGRICULTURAL METEOROLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3405, 1, 'PAPERS (AGRICULTURAL HISTORY SOCIETY)', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3406, 1, 'JOURNAL OF THE AMERICAN SOCIETY OF AGRONOMY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3407, 1, 'ARS JOURNAL', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3408, 1, 'JOURNAL OF THE AEROSPACE SCIENCES', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3409, 1, 'ACTA ALLERGOLOGICA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3410, 1, 'TRANSACTIONS OF THE ANTHROPOLOGICAL SOCIETY OF WASHINGTON', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3411, 1, 'BULLETIN OF THE AMERICAN CERAMIC SOCIETY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15');
INSERT INTO `journal` (`id`, `instance_id`, `name`, `abbreviation`, `responsible`, `ISSN`, `ISSNE`, `frecuency`, `createdAt`, `updatedAt`) VALUES
(3412, 1, 'JOURNAL OF THE INSTITUTE OF ELECTRICAL ENGINEERS OF JAPAN', '\'\'', '\'\'', '0', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3413, 1, 'AMERICAN INDUSTRIAL HYGIENE ASSOCIATION QUARTERLY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3414, 1, 'AIHAJ : A JOURNAL FOR THE SCIENCE OF OCCUPATIONAL AND ENVIRONMENTAL HEALTH AND SAFETY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3415, 1, 'DEVELOPMENTAL DYNAMICS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3416, 1, 'JOURNAL OF CLINICAL NUTRITION', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3417, 1, 'COMPTES RENDUS DE L\'ACADEMIE DES SCIENCES. SERIE III, SCIENCES DE LA VIE', '\'\'', '\'\'', '0', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3418, 1, 'ASIAN JOURNAL OF PHYSICS', '\'\'', '\'\'', '0', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3419, 1, 'JOURNAL OF BIOLOGICAL PHYSICS', '\'\'', '\'\'', '0', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3420, 1, 'REVIEW OF GASTROENTEROLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3421, 1, 'JOURNAL OF HEALTH EDUCATION', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3422, 1, 'SCOTTISH JOURNAL OF GEOLOGY', '\'\'', '\'\'', '0', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3423, 1, 'EVOLUTION AND DEVELOPMENT', 'Evol. Dev.', 'Malden, Mass., US : Blackwell Science', '1520541X', NULL, 'Bimestral', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3424, 1, 'CURRENT OPINION IN MICROBIOLOGY', 'Curr. Opin. Microbiol.', 'London ; New York : Elsevier Science, c1998-', '13695274', NULL, 'Bimonthly', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3425, 1, 'CLINICS IN DERMATOLOGY', '\'\'', '\'\'', '0', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3426, 1, 'GEGENBAURS MORPHOLOGISCHES JAHRBUCH (LEIPZIG)', '', 'Leipzig, Alemanha, DE : Akademische Verlagsgesellschaft Geest Und Portig K.G.', '00165840', NULL, 'Bimestral', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3427, 1, 'AMERICAN JOURNAL OF OBSTETRICS AND DISEASES OF WOMEN AND CHILDREN', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3428, 1, 'AMERICAN JOURNAL OF ORTHODONTICS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3429, 1, 'JOURNAL OF MEDICAL RESEARCH', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3430, 1, 'ANATOMIA, HISTOLOGIA, EMBRYOLOGIA: JOURNAL OF VETERINARY MEDICINE SERIES C', '', '[Berlin] : Blackwell Science,', '', NULL, 'Six issues a year', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3431, 1, 'JOURNAL WATER POLLUTION CONTROL FEDERATION', '\'\'', '\'\'', '0', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3432, 1, 'ADVANCES IN ENVIRONMENTAL RESEARCH', '', 'Publisher Berkeley, CA : Nelson & Commons Communications, c1997-', '1093-0191', NULL, 'Quarterly', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3433, 1, 'JOURNAL OF CHEMOTHERAPY', '\'\'', '\'\'', '0', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3434, 1, 'AMERICAN PHYSICS TEACHER', '', 'New York, Published for the American Association of Physics Teachers by the American Institute of Ph', '00960322', NULL, 'Quarterly, 1933-36 ; Bimonthly, 1937-39', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3435, 1, 'AMERICAN JOURNAL OF PUBLIC HEALTH AND THE NATION\'S HEALTH', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3436, 1, 'AMERICAN REVIEW OF RESPIRATORY DISEASE', 'Am. rev. respir. dis.', 'New York : American Lung Association', '00030805', NULL, 'Monthly', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3437, 1, 'PLANT CELL, TISSUE AND ORGAN CULTURE', '\'\'', '\'\'', '0', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3438, 1, 'JOURNAL OF PETROLEUM SCIENCE AND ENGINEERING', '\'\'', '\'\'', '0920-4105', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3439, 1, 'BULLETIN / WELDING RESEARCH COUNCIL', 'Bull. / Weld. Res. Counc.', 'New York, US : Welding Research Council, Engineering Foundation', '00432326', NULL, '\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3440, 1, 'AMERICAN JOURNAL OF SURGERY AND GYNECOLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3441, 1, 'JOURNAL OF PETROLEUM SCIENCE & ENGINEERING', '\'\'', '\'\'', '0', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3442, 1, 'PHILADELPHIA JOURNAL OF THE MEDICAL AND PHYSICAL SCIENCES', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3443, 1, 'AMERICAN JOURNAL OF TROPICAL MEDICINE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3444, 1, 'JOURNAL OF THE NATIONAL MALARIA SOCIETY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3445, 1, 'MIDLAND NATURALIST', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3446, 1, 'PROCEEDINGS OF THE ANNUAL CONVENTION OF THE AMERICAN PSYCHOLOGICAL ASSOCIATION', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3447, 1, 'AMERICAN REVIEW OF TUBERCULOSIS AND PULMONARY DISEASES', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3448, 1, 'SIGMA XI QUARTERLY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3449, 1, 'INTEGRATIVE AND COMPARATIVE BIOLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3450, 1, 'ZENTRALBLATT FUR MINERALOGIE, GEOLOGIE UND PALAONTOLOGIE', '\'\'', '\'\'', '0', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3451, 1, 'BEHAVIOUR : AN INTERNATIONAL JOURNAL OF COMPARATIVE ETHOLOGY', 'Behaviour', 'Leiden, Netherlands : E. J. Brill, 1947-', '00057959', NULL, 'Mensal', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3452, 1, 'REVISTA DA ACADEMIA BRASILEIRA DE CIENCIAS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3453, 1, 'REVISTA DA FACULDADE DE ODONTOLOGIA DA UNIVERSIDADE DE SAO PAULO', '', '', '0581-6866', NULL, '\'\'', '2015-09-01 14:24:55', '2015-09-01 14:25:11'),
(3454, 1, 'ANALES DE QUÍMICA. SERIE A, QUÍMICA FÍSICA E INGENIERÍA QUÍMICA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3455, 1, 'ANALES DE QUÍMICA. SERIE B, QUÍMICA INORGÁNICA Y QUÍMICA ANALÍTICA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3456, 1, 'ANALES DE QUÍMICA. SERIE C, QUÍMICA ORGÁNICA Y BIOQUÍMICA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3457, 1, 'ANALES DEL INSTITUTO BIOLOGÍA, UNIVERSIDAD NACIONAL AUTÓNOMA DE MÉXICO. SERIE INFORMATIVA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3458, 1, 'ANALES DEL INSTITUTO BIOLOGÍA, UNIVERSIDAD NACIONAL AUTÓNOMA DE MÉXICO. SERIE BIOLOGÍA EXPERIMENTAL', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3459, 1, 'ANALES DEL INSTITUTO BIOLOGÍA, UNIVERSIDAD NACIONAL AUTÓNOMA DE MÉXICO. SERIE BOTÁNICA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3460, 1, 'ANALES DEL INSTITUTO BIOLOGÍA, UNIVERSIDAD NACIONAL AUTÓNOMA DE MÉXICO. SERIE CIENCIAS DEL MAR Y LIMNOLOGÍA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3461, 1, 'ANALES DEL INSTITUTO BIOLOGÍA, UNIVERSIDAD NACIONAL AUTÓNOMA DE MÉXICO. SERIE ZOOLOGÍA', 'An. Inst. Biol., Univ. Nac. Autón. Méx., Ser. zool.', '[México, D.F.] : Instituto de Biología, Universidad Nacional Autónoma de México, 1967-', '03688720', NULL, 'Semestral', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3462, 1, 'CHIMIE ANALYTIQUE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3463, 1, 'MÉTHODES PHYSIQUES D\'ANALYSE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3464, 1, 'ANALUSIS MAGAZINE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3465, 1, 'ANALYTICAL AND QUANTITATIVE CYTOLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3466, 1, 'INDUSTRIAL & ENGINEERING CHEMISTRY. ANALYTICAL EDITION', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3467, 1, 'ANALYTICAL COMMUNICATIONS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3468, 1, 'PROCEEDINGS OF THE ANALYTICAL DIVISION OF THE CHEMICAL SOCIETY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3469, 1, 'TOXICOLOGY AND INDUSTRIAL HEALTH', '\'\'', '\'\'', '0', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3470, 1, 'JOURNAL OF EXPOSURE ANALYSIS AND ENVIRONMENTAL EPIDEMIOLOGY', '\'\'', '\'\'', '0', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3471, 1, 'ANNALS OF ANATOMY = ANATOMISCHER ANZEIGER', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3472, 1, 'APPLIED PARASITOLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3473, 1, 'ANIMAL BLOOD GROUPS AND BIOCHEMICAL GENETICS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3474, 1, 'ANIMAL SCIENCE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3475, 1, 'ANNALEN DER PHARMACIE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3476, 1, 'JUSTUS LIEBIGS ANNALEN DER CHEMIE UND PHARMACIE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3477, 1, 'PAKISTAN VETERINARY JOURNAL', '\'\'', '\'\'', '0', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3478, 1, 'FOLIA VETERINARIA', '\'\'', '\'\'', '0', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3479, 1, 'BERLINER UND MUNCHENER TIERARZTLICHE WOCHENSCHRIFT', '\'', 'Berlin, DE : Verlagsbuchhandlung Von Richard Schoetz', '00059366', NULL, 'Semanal', '2015-09-01 14:24:55', '2015-09-01 14:25:05'),
(3480, 1, 'PARASITOLOGIA HUNGARICA', '\'\'', '\'\'', '0', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3481, 1, 'COMPOSITE STRUCTURES', 'Compos. Struct.', 'Barking, Essex, England : Applied Science Publishers, 1983- // Oxford, Inglaterra, GB : Elsevier Sci', '02638223', NULL, 'Semiannual // Trimestral', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3482, 1, 'ANNALES DE L\'INSTITUT HENRI POINCARÉ. SECTION A: PHYSIQUE THÉORIQUE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3483, 1, 'VETERINARIA URUGUAY', '\'\'', '\'\'', '0', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3484, 1, 'PROCEEDINGS OF THE LINNEAN SOCIETY OF NEW  SOUTH  WALES', '\'\'', '\'\'', '0', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3485, 1, 'REVISTA IBEROAMERICANA DE MICOLOGÍA', '\'\'', '\'\'', '0', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3486, 1, 'BULLETIN OF THE NATIONAL RESEARCH COUNCIL', '\'\'', '\'\'', '0', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3487, 1, 'CLINICAL ORAL IMPLANTS RESEARCH', 'Clin. Oral Implants Res.', 'Copenhahen : Munksgaard International Publishers, 1990-', '09057161', NULL, 'Trimestral // Bimestral', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3488, 1, 'IMPLANT DENTISTRY', '\'\'', '\'\'', '0', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3489, 1, 'ISHS acta horticulturae', '\'\'', '\'\'', '0', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3490, 1, 'SCIENTIFIC REPORTS OF THE AGRICULTURAL UNIVERSITY OF NORWAY', '\'\'', '\'\'', '0', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3492, 1, 'CABALLO', '', 'Buenos Aires, AR : Comando De Remonta Y Veterinaria', '03280551', NULL, 'Bimestral', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3493, 1, 'ANNALES DE L\'INSTITUT HENRI POINCARÉ', 'Ann. Inst. Henri Poincaré', '[Paris] : Institut Henri Poincare, 1930-1964 // Université de Paris', '0365320X', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3495, 1, 'REVUE D`ELEVAGE ET DE MEDECINE VETERINAIRE DES PAYS TROPICAUX', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3496, 1, 'BULLETIN OF THE FACULTY OF AGRICULTURE / TAMAGAWA UNIVERSITY', '', 'Tokyo, JP : Tamagawa University', '0082156X', NULL, 'Anual', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3497, 1, 'ORGANIC PREPARATIONS AND PROCEDURES INTERNATIONAL', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3498, 1, 'ACTA MICROBIOLOGICA ACADEMIAE SCIENTIARUM HUNGARICAE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3499, 1, 'GAYANA', 'Gayana', 'Concepcion, Chile, CL : Universidad De Concepcion, Facultad De Ciencias Naturales Y Oceanograficas', '0717-6538 (e): 0717-652X', NULL, 'Semestral', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3500, 1, 'PHILOSOPHICAL TRANSACTIONS OF THE ROYAL SOCIETY OF LONDON: PHYSICAL SCIENCES AND ENGINEERING', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3501, 1, 'ARCHIV DER PHARMAZIE UND BERICHTE DER DEUTSCHEN PHARMAZEUTISCHEN GESELLSCHAFT', 'Arch. Pharm. Ber. Dtsch. Pharm. Ges.', 'Weinheim, Alemanha, DE : Verlag Chemie', '03760367', NULL, 'Mensal', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3502, 1, 'PLANT CELL REPORTS', '', '\'\'', '0721-7714', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3503, 1, 'CURRENT TOPICS IN PLANT PHYSIOLOGY', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3504, 1, 'ADVANCES IN INORGANIC CHEMISTRY AND RADIOCHEMISTRY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3505, 1, 'MASS SPECTROMETRY ADVANCES', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3506, 1, 'ANNALES DE L\'INSTITUT HENRI POINCARÉ. ANALYSE NON LINÉAIRE', 'Ann. Inst. Henri Poincare, Anal. non lineaire', 'Paris : Gauthier-Villars, 1984- // Montrouge : Centrale des Revues , cop1984', '02941449', NULL, 'Six no. a year', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3507, 1, 'SUOMEN KEMISTILEHTI = FINNISH CHEMICAL JOURNAL', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3508, 1, 'MAGNETIC RESONANCE IN CHEMISTRY : MRC', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3509, 1, 'TRANSACTIONS. SECTION C, MINERAL PROCESSING & EXTRACTIVE METALLURGY / INSTITUTION OF MINING & METALLURGY', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3510, 1, 'ANNALES DE CHIMIE ET DE PHYSIQUE', 'Ann. Chim. Phys.', 'Paris, FR : Masson', '03651444', NULL, '', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3511, 1, 'ANNALES DE L\'INSTITUT HENRI POINCARÉ. SECTION B: CALCUL DES PROBABILITÉS ET STATISTIQUE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3512, 1, 'READING TEACHER : A JOURNAL OF THE INTERNATIONAL READING ASSOCIATION', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3513, 1, 'ANNALES HENRI POINCARÉ', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3514, 1, 'PARASITE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3515, 1, 'REVUE D\'IMMUNOLOGIE (1970)', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3516, 1, 'ANNALES D\'IMMUNOLOGIE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3517, 1, 'ANNALS OF MICROBIOLOGY', 'Ann Microbiol', '', '1590-4261', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3518, 1, 'JOURNAL OF BIOENGINEERING', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3519, 1, 'ANNALES DE L\'INSTITUT PASTEUR. IMMUNOLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3520, 1, 'ANNALES DE L\'INSTITUT PASTEUR. MICROBIOLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3521, 1, 'ANNALES DE VIROLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3522, 1, 'ARCHIVES DE PARASITOLOGIE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3523, 1, 'PRIKLADNAIA BIOKHIMIIA I MIKROBIOLOGIIA', 'Prikl Biokhim Mikrobiol', '\'\'', '0555-1099', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3524, 1, 'ANNALES GEOPHYSICAE (1983)', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3525, 1, 'ANNALES GEOPHYSICAE (1988)', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3526, 1, 'ANNALS OF CLINICAL MEDICINE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3527, 1, 'PROCEEDINGS OF THE ASSOCIATION OF CLINICAL BIOCHEMISTS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3528, 1, 'NORWEGIAN JOURNAL OF ZOOLOGY', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3529, 1, 'INTERNATIONAL JOURNAL OF COMPUTER INTEGRATED MANUFACTURING', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3530, 1, 'MACHINE VISION AND APPLICATIONS', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3531, 1, 'BREEDING SCIENCE', 'Breed. Sci.', 'Tokyo, JP : Japanese Society Of Breeding', '13447610', NULL, 'Trimestral', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3532, 1, 'VIE ET MILIEU', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3533, 1, 'ANNALES DES SCIENCES NATURELLES', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3534, 1, 'SYDOWIA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3535, 1, 'JOURNAL DE PHARMACY ET DE CHIMIE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3536, 1, 'BULLETIN DES SCIENCES PHARMACOLOGIQUES', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3537, 1, 'ANNALI DI MICROBIOLOGIA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3538, 1, 'ANNALS OF CLINICAL RESEARCH', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3539, 1, 'MEDICAL BIOLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3541, 1, 'HISTOCHEMIE = HISTOCHEMISTRY (1964) = HISTOCHIMIE', '', 'Berlin, DE : Springer', '00182222', NULL, 'Irregular', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3542, 1, 'COMPARATIVE PARASITOLOGY', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3543, 1, 'JOURNAL OF THE WATER POLLUTION CONTROL FEDERATION', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3544, 1, 'ANNUAL REPORT / MISSOURI BOTANICAL GARDEN', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3545, 1, 'ANNALS OF THE LYCEUM OF NATURAL HISTORY OF NEW YORK', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3546, 1, 'REPORTS ON CHRONIC RHEUMATIC DISEASES', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3547, 1, 'ANNUAL REVIEW OF BIOPHYSICS AND BIOPHYSICAL CHEMISTRY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3548, 1, 'ANNUAL REVIEW OF CELL AND DEVELOPMENTAL BIOLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3549, 1, 'ANNUAL REVIEW OF OCLC RESEARCH (ON LINE)', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3550, 1, 'ANNUAL REVIEW OF PLANT BIOLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3551, 1, 'ANNUAL REVIEW OF PLANT PHYSIOLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3552, 1, 'GAYANA. BOTANICA', 'Gayana, Bot.', 'Concepcion, Chile, CL : Universidad De Concepcion, Facultad De Ciencias Biologicas Y Recursos Natura', '00165301', NULL, 'Irregular', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3553, 1, 'CORROSION TECHNOLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3554, 1, 'ANTIMICROBIAL AGENTS ANNUAL', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3555, 1, 'NEDERLANDSCH TIJDSCHRIFT VOOR HYGIËNE, MICROBIOLOGIE EN SEROLOGIE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3556, 1, 'ANNALES DE L\' ABEILLE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3557, 1, 'ZEITSCHRIFT FUR BIENENFORSCHUNG', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3558, 1, 'BULLETIN DE LA SOCIETE PHYCOLOGIQUE DE FRANCE', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3559, 1, 'GEOPHYSICAL JOURNAL INTERNATIONAL', 'Geophys. J. Int.', 'Oxford: Blackwell Scientific Publications Ltd., [c1989-', '0956540X', NULL, 'Monthly', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3560, 1, 'APPLIED RHEOLOGY', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3561, 1, 'APPLIED PHYSICS. A, SOLIDS AND SURFACES', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3562, 1, 'APPLIED PHYSICS. B, PHOTOPHYSICS AND LASER CHEMISTRY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3563, 1, 'INTERNATIONAL JOURNAL OF APPLIED RADIATION AND ISOTOPES', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3564, 1, 'APPLIED SCIENTIFIC RESEARCH. A, MECHANICS, HEAT, CHEMICAL ENGINEERING, MATHEMATICAL METHODS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3565, 1, 'APPLIED SCIENTIFIC RESEARCH. B, ELECTROPHYSICS, ACOUSTICS, OPTICS, MATHEMATICAL METHODS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3566, 1, 'BULLETIN / SOCIETY FOR APPLIED SPECTROSCOPY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3567, 1, 'ARCHIV DER PHARMAZIE (1835)', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3568, 1, 'BERICHTE DER DEUTSCHEN PHARMAZEUTISCHEN GESELLSCHAFT', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3569, 1, 'DANSK TIDSSKRIFT FOR FARMACI', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3570, 1, 'JOURNAL OF OPTICAL COMMUNICATIONS', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3571, 1, 'ARCHIV FÜR NATURGESCHICHTE (1835)', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3572, 1, 'ARCHIV FÜR NATURGESCHICHTE (1932)', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3573, 1, 'PROTIST', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3574, 1, 'ARCHIVES FRANCAISES DE PEDIATRIE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3575, 1, 'PEDIATRIE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:15'),
(3576, 1, 'ARCHIVES DE PHARMACODYNAMIE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:16'),
(3577, 1, 'BRITISH JOURNAL OF CHILDREN\'S DISEASES', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:16'),
(3578, 1, 'A.M.A. ARCHIVES OF INDUSTRIAL HEALTH', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:16'),
(3579, 1, 'ARCHIV FÜR MIKROBIOLOGIE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:16'),
(3580, 1, 'ARCHIVES OF PATHOLOGY (1960)', 'Arch Pathol', '', '0363-0153 (e): 0096-6711', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:16'),
(3581, 1, 'NAUNYN-SCHMIEDEBERGS ARCHIV FÜR PHARMAKOLOGIE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:16'),
(3582, 1, 'AMERICAN JOURNAL OF DISEASES OF CHILDREN (1960)', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:16'),
(3583, 1, 'ARCHIV FÜR TOXIKOLOGIE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:16'),
(3584, 1, 'ARCHIV FÜR DIE GESAMTE VIRUSFORSCHUNG', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:16'),
(3585, 1, 'YEAR BOOK/CARNEGIE INSTITUTION OF WASHINGTON', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:16'),
(3586, 1, 'MORFOLOGIA NORMAL Y PATOLOGICA. SECCION A, HISTOLOGIA', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:16'),
(3587, 1, 'ARQUIVOS DA ESCOLA DE VETERINARIA DA UNIVERSIDADE FEDERAL DE MINAS GERAIS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:16'),
(3588, 1, 'ARQUIVOS DO INSTITUTO BIOLOGICO DE DEFESA AGRICOLA E ANIMAL', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:16'),
(3589, 1, 'ARTERIOSCLEROSIS AND THROMBOSIS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:16'),
(3590, 1, 'SULFUR LETTERS', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:16'),
(3591, 1, 'MOLECULAR REPRODUCTION AND DEVELOPMENT', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:16'),
(3592, 1, 'AGRICULTURAL ENGINEERS YEARBOOK', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:16'),
(3593, 1, 'ARCHIVO IBEROAMERICANO DE HISTORIA DE LA MEDICINA Y ANTROPOLOGÍA MÉDICA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:16'),
(3594, 1, 'JOURNAL OF ATHEROSCLEROSIS RESEARCH', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:16'),
(3595, 1, 'ATMOSPHERIC ENVIRONMENT (1994)', 'Atmos. Environ. (1994)', 'Oxford ; New York : Pergamon, c1993-', '13522310', NULL, 'Semimonthly except in March and Oct', '2015-09-01 14:24:55', '2016-01-12 14:16:16'),
(3596, 1, 'ATMOSPHERIC ENVIRONMENT. PART B, URBAN ATMOSPHERE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:16'),
(3597, 1, 'AIR AND WATER POLLUTION', 'Air water pollut.', '', '0568-3408', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:16'),
(3598, 1, 'ATOMIC SPECTROSCOPY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:16'),
(3599, 1, 'ATOMIC DATA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:16'),
(3600, 1, 'NUCLEAR DATA TABLES (NEW YORK)', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:16'),
(3601, 1, 'ANZ JOURNAL OF SURGERY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:16'),
(3602, 1, 'JOURNAL OF THE COLLEGE OF SURGEONS OF AUSTRALASIA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:16'),
(3603, 1, 'THERIOS', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:16'),
(3604, 1, 'RILEM BULLETIN', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:16'),
(3605, 1, 'AUSTRALIAN JOURNAL OF SCIENTIFIC RESEARCH. SERIES B, BIOLOGICAL SCIENCES', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:16'),
(3606, 1, 'REPRODUCTION, FERTILITY, AND DEVELOPMENT', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:16'),
(3607, 1, 'AUSTRALIAN JOURNAL OF SCIENTIFIC RESEARCH. SERIES A, PHYSICAL SCIENCES', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:16'),
(3608, 1, 'JOURNAL OF THE GEOLOGICAL SOCIETY OF AUSTRALIA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:16'),
(3609, 1, 'FUNCTIONAL PLANT BIOLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:16'),
(3610, 1, 'SEARCH: SCIENCE, TECHNOLOGY AND SOCIETY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:16'),
(3611, 1, 'AUSTRALIAN AND NEW ZEALAND JOURNAL OF STATISTICS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:16'),
(3612, 1, 'NEW ZEALAND STATISTICAN', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:16'),
(3613, 1, 'AUSTRALIAN JOURNAL OF BOTANY. SUPPLEMENTARY SERIES', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:16'),
(3614, 1, 'BRUNONIA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:16'),
(3616, 1, 'JOURNAL OF THE AUSTRALIAN VETERINARY ASSOCIATION', '', '', '', NULL, '\'\'', '2015-09-01 14:24:55', '2016-01-12 14:16:16'),
(3617, 1, 'CEREVISIA AND BIOTECHNOLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3618, 1, 'AT&T BELL LABORATORIES TECHNICAL JOURNAL', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3619, 1, 'MEDICAL EDUCATION', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3620, 1, 'THE AMERICAN BIOLOGY TEACHER', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3621, 1, 'BULLETIN OF THE ACADEMY OF SCIENCES OF THE USSR. DIVISION OF CHEMICAL SCIENCES', '', 'New York, N.Y. : Consultants Bureau, 1952-1991', '05685230', NULL, '', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3622, 1, 'ZEITSCHRIFT FÜR ELEKTROCHEMIE (WEINHEIM AN DER BERGSTRASSE, GERMANY)', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3623, 1, 'BERLINER TIERARZTLICHE WOCHENSCHRIFT', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3624, 1, 'FACHBLATT DER SUDETENDEUTSCHEN TIERARZTE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3625, 1, 'BIOCHEMICAL JOURNAL (1906)', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3626, 1, 'BIOCHEMICAL SYSTEMATICS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3627, 1, 'BULLETIN DE LA SOCIETE DE CHIMIE BIOLOGIQUE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3628, 1, 'PROCEEDINGS OF THE NATIONAL ACADEMY OF SCIENCES, INDIA', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3629, 1, 'COMPOST SCIENCE / LAND UTILIZATION', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3630, 1, 'CESKOSLOVENSKA BIOLOGIE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3631, 1, 'JOURNAL OF PHARMACOBIODYNAMICS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3632, 1, 'KYBERNETIK', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3633, 1, 'PROCEEDINGS OF THE LINNEAN SOCIETY OF LONDON', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3634, 1, 'MATERIAUX ET CONSTRUCTIONS=MATERIALS AND STRUCTURES', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3635, 1, 'PHILLIPINE JOURNAL OF SCIENCE', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3636, 1, 'REVUE SUISSE DE ZOOLOGIE : ANNALES DE LA SOCIÉTÉ ZOOLOGIQUE SUISSE ET DU MUSÉUM D\'HISTOIRE NATURELLE DE GENÈVE', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3637, 1, 'PROCEEDINGS OF THE NATIONAL ACADEMY OF SCIENCES (INDIA). SECTION B: BIOLOGICAL SCIENCES', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3638, 1, 'BIOCHEMISTRY INTERNATIONAL', '', '', '', NULL, '', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3639, 1, 'IUBMB LIFE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3640, 1, 'ACTA BIOLOGICA ET MEDICA GERMANICA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3641, 1, 'BIOLOGY OF METALS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3642, 1, 'BIOMETRICS BULLETIN', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3643, 1, 'INTERNATIONAL JOURNAL OF CLINICAL PHARMACOLOGY AND THERAPEUTICS', 'Int. J. Clin. Pharmacol. Ther.', 'Munchen, Alemanha, DE : Dustri Verlag', '09461965', NULL, 'Mensal', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3644, 1, 'AULA DE INNOVACIÓN EDUCATIVA', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3645, 1, 'COMUNIDAD EDUCATIVA', '', 'Madrid : Escuelas Pías , 1970', '02122650', NULL, 'Mensual', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3646, 1, 'BIOLOGICAL WASTES', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3647, 1, 'BIOMASS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3648, 1, 'AIBS BULLETIN', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3649, 1, 'JOURNAL OF ANALYTICAL AND APPLIED CHEMISTRY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3650, 1, 'AMERICAN CHEMICAL JOURNAL', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3651, 1, 'SCIENTIFIC MONTHLY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3652, 1, 'OIL AND GAS JOURNAL', 'Oil Gas J', '\'\'', '0030-1388', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3653, 1, 'JOURNAL OF PHYSICAL AND COLLOID CHEMISTRY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3654, 1, 'CANADIAN JOURNAL OF RESEARCH. SECTION D, ZOOLOGICAL SCIENCES', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3655, 1, 'PROCEEDINGS OF THE SOCIETY FOR APPLIED BACTERIOLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3656, 1, 'CAN', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3657, 1, 'HOLZFORSCHUNG', 'Holzforschung', 'Berlin, DE : Technischer Verlag Herbert', '0018-3830', '1437-434X', 'Bimestral', '2015-09-01 14:24:56', '2015-11-30 08:41:26'),
(3658, 1, 'WOOD SCIENCE AND TECHNOLOGY / UNDER THE AUSPICES OF THE INTERNATIONAL ACADEMY OF WOOD SCIENCE (IAWS)', 'Wood Sci. Technol.', 'New York : Springer-Verlag, 1967-', '00437719', NULL, '4 no. a year', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3659, 1, 'PETROLEUM ENGINEER INTERNATIONAL', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3660, 1, 'BIOPHARMACEUTICS AND DRUG DISPOSITION', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3661, 1, 'CANADIAN JOURNAL OF RESEARCH. SECTION B, CHEMICAL SCIENCES', '', '', '0366-7391', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3662, 1, 'JOURNAL OF INSECT PATHOLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3663, 1, 'JOURNAL OF MEDICINAL AND PHARMACEUTICAL CHEMISTRY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3664, 1, 'CONCHOLOGISTS\' EXCHANGE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3665, 1, 'PATHOLOGIA VETERINARIA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3666, 1, 'AMERICAN VETERINARY REVIEW', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3667, 1, 'DALTON', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3668, 1, 'PROCEEDINGS OF THE SOCIETY OF PHOTO-OPTICAL INSTRUMENTATION ENGINEERS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3669, 1, 'BULLETIN DE LA SOCIETE CHIMIQUE DE BELGIQUE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3670, 1, 'BULLETIN DE LA SOCIETE CHIMIQUE DE FRANCE (PARIS, FRANCE : 1985)', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3671, 1, 'LIEBIGS ANNALEN / RECUEIL', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3672, 1, 'BIOCHEMISCHE ZEITSCHRIFT', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3673, 1, 'JOURNAL OF REPRODUCTION AND FERTILITY. SUPPLEMENT', 'J. Reprod. Fert. Suppl.', 'Oxford, Inglaterra, GB : Society For The Study Of Fertility', '04493087', NULL, 'Irregular / Bimestral', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3674, 1, 'REPRODUCTION IN DOMESTIC ANIMALS', 'Reprod Domest Anim', '\'\'', '0936-6768. ISSN (electronic): 1439-0531', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3675, 1, 'VETERINARY RECORD, AND TRANSACTIONS OF THE VETERINARY MEDICAL ASSOCIATION', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3676, 1, 'FREE RADICAL RESEARCH COMMUNICATIONS', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3677, 1, 'JOURNAL OF COMPARATIVE PATHOLOGY AND THERAPEUTICS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3678, 1, 'FOOD RESEARCH', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3679, 1, 'CHEMICAL COMMUNICATIONS (LONDON, 1970)', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3680, 1, 'SILVAE GENETICA', 'Silvae Genet.', 'Franfurt, Alemanha, DE: J: D: Sauerlaender´s Verlag', '00375349', NULL, 'Irregular', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3681, 1, 'PHYSICAL REVIEW. B. SOLID STATE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3682, 1, 'SPE DRILLING ENGINEERING', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3683, 1, 'BRITISH JOURNAL OF SPORTS MEDICINE', 'Br. J. Sports. Med.', 'Loughborough, Inglaterra, GB : British Association Of Sports And Medicine', '03063674', NULL, 'Bimestral', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3684, 1, 'JOURNAL OF CHEMICAL RESEARCH', '', '\'\'', '1747-5198 0308-2350', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3685, 1, 'EUROPEAN JOURNAL OF INORGANIC CHEMISTRY', 'Eur. J. Inorg. Chem.', 'Weinheim, Alemanha, DE : Wiley-VCH, c1998-', '14341948', NULL, 'Monthly', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3686, 1, 'BULLETIN OF THE RUSSIAN ACADEMY OF SCIENCES', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3687, 1, 'BULLETIN / ASSOCIATION FOR TROPICAL BIOLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3688, 1, 'BOLETIN DE INFORMACIONES PARASITARIAS CHILENAS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3689, 1, 'PARASITOLOGIA LATINOAMERICANA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3690, 1, 'REVISTA PANAMERICANA DE SALUD PÚBLICA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3691, 1, 'ANALES DEL INSTITUTO NACIONAL DE INVESTIGACIONES AGRARIAS . SERIE PROTECCION VEGETAL', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3692, 1, 'BOLETIN DEL MUSEO NACIONAL (SANTIAGO, CHILE)', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3693, 1, 'BOLLETTINO DELL ISTITUTO E MUSEO DI ZOOLOGIA DELLA UNIVERSITA DI TORINO', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3694, 1, 'BOLLETTINO DELLA SOCIETA DI BIOLOGIA SPERIMENTALE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3695, 1, 'BOTANICAL BULLETIN (HANOVER, IND.)', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3696, 1, 'INTERNATIONAL JOURNAL OF PLANT SCIENCES', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3697, 1, 'REVISTA BRASILEIRA DE PESQUISAS MEDICAS E BIOLOGICAS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3698, 1, 'DRILLING CONTRACTOR', 'Drill. Contract.', 'Houston, etc. : Drilling Contractor Publications, Inc., etc., 1944-', '00460702', NULL, 'Bimonthly, 1944-; Monthly <, 1979->; Bimonthly <, June/July 1986- >', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3699, 1, 'JOURNAL OF PULP AND PAPER SCIENCE', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3700, 1, 'JAPANESE JOURNAL OF BREEDING', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3701, 1, 'CORROSION ENGINEERING, SCIENCE AND TECHNOLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3702, 1, 'BULLETIN OF THE BRITISH SOCIETY FOR THE HISTORY OF SCIENCE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3703, 1, 'MEDICAL LABORATORY SCIENCES', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3704, 1, 'PAPIR-JOURNALEN', '', '', ' 0369-8351', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3705, 1, 'POLYMER ENGINEERING AND SCIENCE', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3706, 1, 'METALLURGICAL PLANT AND TECHNOLOGY', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3707, 1, 'FORUM OF EDUCATION', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3708, 1, 'SOILS AND FOUNDATIONS', 'Soils Found.', 'Tokyo : Japanese Society of Soil Mechanics and Foundation Engineering, 1968-', '00380806', NULL, 'Trimestral', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3709, 1, 'SOIL AND FOUNDATION', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3710, 1, 'LOS ALAMOS [REPORTS]', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3711, 1, 'BJOG', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3712, 1, 'BRITISH JOURNAL OF PHARMACOLOGY AND CHEMOTHERAPY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3713, 1, 'BRITISH JOURNAL OF SOCIAL MEDICINE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3714, 1, 'RHEUMATOLOGY AND REHABILITATION', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3715, 1, 'JOURNAL OF OBSTETRICS AND GYNAECOLOGY OF THE BRITISH COMMONWEALTH', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3716, 1, 'VETERINARY JOURNAL (1875)', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3717, 1, 'BUILDING SCIENCE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3718, 1, 'BULLETIN OF THE HOSPITAL FOR JOINT DISEASES ORTHOPAEDIC INSTITUTE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3719, 1, 'BULLETIN DE L\'ACADEMIE DE MEDECINE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3720, 1, 'BULLETIN DE L\'ACADEMIE POLONAISE DES SCIENCES. SERIE DES SCIENCES CHIMIQUES, GEOLOGIQUES ET GEOGRAPHIQUES', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3721, 1, 'COMPTES RENDUS DE L\'ASSOCIATION DES ANATOMISTES', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3722, 1, 'BULLETIN DE LA SOCIETE DES SCIENCES NATURELLES DE NEUCHATEL', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3723, 1, 'BELGIAN JOURNAL OF BOTANY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3724, 1, 'BULLETIN DU MUSEUM NATIONAL D\'HISTOIRE NATURELLE. BOTANIQUE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3725, 1, 'BULLETIN DU MUSEUM NATIONAL D\'HISTOIRE NATURELLE. ECOLOGIE GENERALE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3726, 1, 'BULLETIN DU MUSEUM NATIONAL D\'HISTOIRE NATURELLE. SCIENCES PHYSICO-CHIMIQUES', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3727, 1, 'BULLETIN DU MUSEUM NATIONAL D\'HISTOIRE NATURELLE. SCIENCES DE LA TERRE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3728, 1, 'BULLETIN DU MUSEUM NATIONAL D\'HISTOIRE NATURELLE. ZOOLOGIE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3729, 1, 'JOURNAL OF ALLOY PHASE DIAGRAMS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3730, 1, 'BULLETIN OF THE MARINE SCIENCE OF THE GULF AND CARIBBEAN', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3731, 1, 'ISRAEL JOURNAL OF MATHEMATICS', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3732, 1, 'BULLETIN OF MATHEMATICAL BIOLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3733, 1, 'JOURNAL OF THE TORREY BOTANICAL SOCIETY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3734, 1, 'BULLETIN VOLCANOLOGIQUE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3735, 1, 'CADERNOS DE SAUDE PUBLICA. SERIE ENSAIO', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3736, 1, 'CADERNOS DE SAUDE PUBLICA. SERIE PESQUISA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3737, 1, 'CADERNOS DE SAUDE PUBLICA. SERIE DOCUMENTO', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3738, 1, 'ORGANIC AND BIOMOLECULAR CHEMISTRY', 'Org Biomol Chem', '\'\'', '1477-0520 (e): 1477-0539', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3739, 1, 'REVISTA DEL MUSEO DE LA CIUDAD EVA PERON', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3740, 1, 'MAIA : RIVISTA DI LETTERATURE CLASSICHE', '', '\'\'', '0025-0538', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3741, 1, 'CLASSICAL JOURNAL', 'Class. J. (Class. Assoc. Middle West South)', 'Gainesville // Chicago, Ill. [etc.] : University of Chicago Press, for the Classical Association of', '00098353', NULL, 'Four bimonthly issues (Oct.-May) <, Oct./Nov. 1976->', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3742, 1, 'CANADIAN JOURNAL OF APPLIED SPECTROSCOPY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3743, 1, 'CANADIAN JOURNAL OF AGRICULTURAL SCIENCE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3744, 1, 'CANADIAN JOURNAL OF MEDICAL SCIENCES', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3745, 1, 'CANADIAN JOURNAL OF RESEARCH. SECTION C, BOTANICAL SCIENCES', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3746, 1, 'CANADIAN JOURNAL OF TECHNOLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3747, 1, 'CANADIAN JOURNAL OF COMPARATIVE MEDICINE AND VETERINARY SCIENCE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3748, 1, 'JOURNAL OF THE FISHERIES RESEARCH BOARD OF CANADA', 'J Fish Res Board Can', '', '0015-296X', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3749, 1, 'CANADIAN JOURNAL OF RESEARCH. SECTION A, PHYSICAL SCIENCES', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3750, 1, 'JOURNAL OF BIOCHEMICAL AND MICROBIOLOGICAL TECHNOLOGY AND ENGINEERING', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3751, 1, 'JOURNAL OF THE AMERICAN DIETETIC ASSOCIATION', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3752, 1, 'MEDICINA (BUENOS AIRES)', '', '\'\'', '0025-7680', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3753, 1, 'HORMONES', '', '\'\'', '0018-5051', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3754, 1, 'MENDELEEV COMMUNICATIONS', '', '\'\'', '(printed): 0959-9436 (electronic): 1364-551X', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3755, 1, 'CANCER. CYTOPATHOLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3756, 1, 'COLD SPRING HARBOR CONFERENCES ON CELL PROLIFERATION', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3757, 1, 'AMERICAN JOURNAL OF CANCER', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3758, 1, 'CELESTIAL MECHANICS AND DYNAMICAL ASTRONOMY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3759, 1, 'ZEITSCHRIFT FUR ZELLFORSCHUNG UND MIKROSKOPISCHE ANATOMIE', 'Z Zellforsch Mikrosk Anat', '', '0340-0336', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3760, 1, 'CELL BIOLOGY INTERNATIONAL REPORTS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3761, 1, 'CELL MOTILITY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3762, 1, 'JOURNAL OF ROMAN STUDIES', '', '', '0075-4358', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3763, 1, 'ANNUAIRE DE L\'INSTITUT DE PHILOLOGIE ET D\'HISTOIRE ORIENTALES', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3764, 1, 'MNEMOSYNE: BIBLIOTHECA CLASSICA BATAVA', '', '', '0026-7074 Online: 1568-525X', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3765, 1, 'ANNALS OF MATHEMATICS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3766, 1, 'ARKIV FÖR KEMI', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3767, 1, 'PHARMACEUTICAL BULLETIN', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3768, 1, 'NEWS EDITION', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3769, 1, 'CHEMICAL AND METALLURGICAL ENGINEERING', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3770, 1, 'CHEMICAL ENGINEERING JOURNAL (1970)', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3771, 1, 'QUARTERLY REVIEWS / CHEMICAL SOCIETY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3772, 1, 'RIC REVIEWS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3773, 1, 'BERICHTE DER DEUTSCHEN CHEMISCHEN GESELLSCHAFT', '', '', '0365-9496', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3774, 1, 'CHEMISTRY AND INDUSTRY REVIEW', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3775, 1, 'PROCEEDINGS OF THE CHEMICAL SOCIETY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3776, 1, 'JOURNAL OF THE ROYAL INSTITUTE OF CHEMISTRY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3777, 1, 'DISEASES OF THE CHEST', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3778, 1, 'BIOMASS AND BIOENERGY', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3779, 1, 'SCHWEIZER CHEMIKER-ZEITUNG', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3780, 1, 'CLAY MINERALS BULLETIN', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3781, 1, 'PROCEEDINGS. COMMITTEE ON CLAY MINERALS OF THE NATIONAL ACADEMY OF SCIENCES--NATIONAL RESEARCH COUNCIL AND THE RICE INSTITUTE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3782, 1, 'TRANSACTIONS OF THE ST. JOHN\'S HOSPITAL DERMATOLOGICAL SOCIETY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3783, 1, 'CLINICAL CHEMIST', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16');
INSERT INTO `journal` (`id`, `instance_id`, `name`, `abbreviation`, `responsible`, `ISSN`, `ISSNE`, `frecuency`, `createdAt`, `updatedAt`) VALUES
(3784, 1, 'REVIEWS OF INFECTIOUS DISEASES', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3785, 1, 'CLINICAL ORTHOPAEDICS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3786, 1, 'DE-ACQUISITIONS LIBRARIAN', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3787, 1, 'KOLLOID-ZEITSCHRIFT UND ZEITSCHRIFT FUR POLYMERE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3788, 1, 'ZONA ABIERTA', '', '\'\'', '0210-2692', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3789, 1, 'TRANSACTIONS OF THE JAPAN INSTITUTE OF METALS', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3790, 1, 'TRANSGENIC RESEARCH', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3791, 1, 'BULLETIN DU MUSEUM NATIONAL D\'HISTOIRE NATURELLE. SECTION A, ZOOLOGIE, BIOLOGIE ET ECOLOGIE ANIMALES', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3792, 1, 'FOCUS (GAITHERSBURG, MD.)', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3793, 1, 'JOURNAL OF BIOLOGICAL RESPONSE MODIFIERS', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3794, 1, 'JOURNAL OF ELECTRON MICROSCOPY', 'J. Electron. Microsc.', 'Tokyo, Japan : The Society, 1953-', '00220744', NULL, 'Annual 1953-1956; Quarterly', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3795, 1, 'HOPPE-SEYLER\'S ZEITSCHRIFT FUR PHYSIOLOGISCHE CHEMIE', 'Hoppe-Seyler#s Z. Physiol. Chem.', 'Strassburg : K. J. Trbner, -1984', '01773593', NULL, 'Monthly', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3796, 1, 'CHIMICA THERAPEUTICA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3797, 1, 'PHYSIS : REVISTA DE LA SOCIEDAD ARGENTINA DE CIENCIAS NATURALES', 'Physis (Buenos Aires)', '\'\'', '0373-6709', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3798, 1, 'TAPPI JOURNAL', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3799, 1, 'MATERIALS PERFORMANCE', '', '\'\'', '0094-1492', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3800, 1, 'JOURNAL OF DERMATOLOGIC SURGERY AND ONCOLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3801, 1, 'IMI DESCRIPTIONS OF FUNGI AND BACTERIA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3802, 1, 'PROCEEDINGS OF THE CAMBRIDGE PHILOSOPHICAL SOCIETY', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3803, 1, 'JOURNAL OF THE FRANKLIN INSTITUTE', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3804, 1, 'VACUUM', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3805, 1, 'AUFSTIEG UND NIEDERGANG DER ROMISCHEN WELT', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3806, 1, 'HIGH ENERGY CHEMISTRY', 'High Energy Chem.', 'New York, N.Y. : Consultants Bureau, 1967-', '00181439', NULL, 'Bimestral', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3808, 1, 'REVISTA DE MEDICINA VETERINARIA (BUENOS AIRES)', '', '\'\'', '0325-6391', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3809, 1, 'REVISTA DE MEDICINA VETERINARIA (MONTEVIDEO. 1916)', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3810, 1, 'REVISTA DE MEDICINA VETERINARIA (QUITO)', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3811, 1, 'REVISTA DE MEDICINA VETERINARIA (SANTIAGO DE CHILE)', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3812, 1, 'REVISTA DE MEDICINA VETERINARIA Y PARASITOLOGIA (VENEZUELA)', '', 'Maracay, Venezuela, VE : Universidad Central De Venezuela, Facultad De Medicina Veterinaria', '', NULL, '', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3813, 1, 'REVISTA DE MEDICINA VETERINARIA (LISBOA)', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3814, 1, 'REVISTA DE MEDICINA VETERINARIA (SAO PAULO. 1965)', '', '', '0556-6193', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3815, 1, 'IEE PROCEEDINGS. F, COMMUNICATIONS, RADAR, AND SIGNAL PROCESSING', 'IEE proc. F', 'London, GB // Stevenage, Herts. : Institution of Electrical Engineers, c1980-1988', '0143-7070', NULL, 'Six no. a year', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3816, 1, 'IPEF INTERNATIONAL', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3817, 1, 'CEREBRAL PALSY BULLETIN', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3818, 1, 'AMERICAN JOURNAL OF DIGESTIVE DISEASES', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3819, 1, 'DOKLADY AKADEMII NAUK SOIUZA SOVETSKIKH SOTSIALISTICHESKIKH RESPUBLIK. A', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3820, 1, 'DRUG DEVELOPMENT COMMUNICATIONS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3821, 1, 'ACTA ENDOCRINOLOGICA', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3822, 1, 'JOURNAL OF ENVIRONMENTAL SCIENCE AND HEALTH. PART A, TOXIC/HAZARDOUS SUBSTANCES & ENVIRONMENTAL ENGINEERING', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3823, 1, 'EAST AFRICAN AGRICULTURAL JOURNAL', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3824, 1, 'FOOD TECHNOLOGY INTERNATIONAL EUROPE', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3825, 1, 'COLLOIDS AND SURFACES. B, BIOINTERFACES', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3826, 1, 'COMMUNICATIONS IN STATISTICS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3827, 1, 'JOURNAL OF FOOD TECHNOLOGY', '', '', '1684-8462', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3828, 1, 'GACETA VETERINARIA', 'Gac. Vet.', 'Buenos Aires, AR : Librart', '03673812', NULL, 'Mensal', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3829, 1, 'ANALES DEL MUSEO DE LA PLATA', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3830, 1, 'CIENCIA E CULTURA', 'Cienc. cult.', '\'\'', '0009-6725', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3831, 1, 'REVISTA LATINOAMERICANA DE FILOSOFIA', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3832, 1, 'RANGIFER', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3833, 1, 'PLANT WORLD', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3834, 1, 'AV COMMUNICATION REVIEW', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3835, 1, 'ELECTROENCEPHALOGRAPHY AND CLINICAL NEUROPHYSIOLOGY. INDEX TO CURRENT LITERATURE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3836, 1, 'ELECTRONICS AND TELEVISION AND SHORT WAVE WORLD', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3837, 1, 'ELECTRONIC ENGINEERING DESIGN', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3838, 1, 'BULLETIN OF THE NATIONAL SCIENCE MUSEUM', '', 'Tokyo (Japan). National Science Museum.', '0028-0119', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3839, 1, 'COMPTES RENDUS DES SEANCES DE L\'ACADÉMIE DES SCIENCES. SERIE I, MATHEMATIQUE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3840, 1, 'COMPTES RENDUS HEBDOMADAIRES DES SEANCES DE L\'ACADEMIE DES SCIENCES. SERIE A: SCIENCES MATHEMATIQUES', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3841, 1, 'COMPTES RENDUS HEBDOMADAIRES DES SEANCES DE L\'ACADEMIE DES SCIENCES. SERIE B: SCIENCES PHYSIQUES', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3842, 1, 'COMPUTER GROUP NEWS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3843, 1, 'BIOINFORMATICS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3844, 1, 'COMPUTER GRAPHICS AND IMAGE PROCESSING', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3845, 1, 'CVGIP. IMAGE UNDERSTANDING', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3846, 1, 'CVGIP. GRAPHICAL MODELS AND IMAGE PROCESSING', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3847, 1, 'AUSTRALIAN FOREST RESEARCH', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3848, 1, 'FORRAJERAS Y PRODUCCION BOVINA', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3849, 1, 'MATHEMATIK, TECHNIK, WIRTSCHAFT', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3850, 1, 'BEITRAGE ZUR MINERALOGIE UND PETROGRAPHIE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3851, 1, 'COSMETICS AND PERFUMERY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:16'),
(3852, 1, 'CRITICAL REVIEWS IN BIOCHEMISTRY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3853, 1, 'CRC CRITICAL REVIEWS IN FOOD SCIENCE AND NUTRITION', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3854, 1, 'CRC CRITICAL REVIEWS IN MICROBIOLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3855, 1, 'ARCHIV ZA KEMIJU', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3856, 1, 'KRISTALL UND TECHNIK', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3857, 1, 'ERGEBNISSE DER MIKROBIOLOGIE, IMMUNITATSFORSCHUNG UND EXPERIMENTELLEN THERAPHIE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3858, 1, 'CYTOGENETICS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3859, 1, 'ENDOCRINOLOGIA JAPONICA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3860, 1, 'GETREIDE, MEHL UND BROT', '', 'Bochum, Alemanha, DE : Backer-Verlag', '03674177', NULL, 'Semestral', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3861, 1, 'TRANSACTIONS OF THE AMERICAN GOITER ASSOCIATION', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3862, 1, 'ENGINEERING ANALYSIS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3863, 1, 'ENERGY CONVERSION', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3864, 1, 'ANNALES DE PARASITOLOGIE (PARIS)', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3865, 1, 'NATURE IMMUNOLOGY', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3866, 1, 'JOURNAL OF EXPERIMENTAL MEDICINE', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3867, 1, 'INFLAMMATORY BOWEL DISEASES', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3868, 1, 'SAE TECHNICAL PAPERS', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3869, 1, 'ANALES DE LITERATURA HISPANOAMERICANA', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3870, 1, 'ANNALS OF SYSTEMS RESEARCH', 'Ann. Syst. Res.', 'Leiden : H. E. S. Kroese', '03049795', NULL, 'Annual', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3871, 1, 'CUADERNOS HISPANOAMERICANOS', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3872, 1, 'IEEE SPECTRUM', 'IEEE Spectrum', 'New York, Institute of Electrical and Electronics Engineers', '00189235', NULL, '', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3873, 1, 'TECTONOPHYSICS', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3874, 1, 'SAE TECHNICAL PAPER SERIES', '', '', '0148-7191', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3875, 1, 'JOURNAL OF INORGANIC CHEMISTRY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3876, 1, 'JOURNAL OF PLANT RESEARCH', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3877, 1, 'PROCEEDINGS OF THE ROYAL SOCIETY OF LONDON. SERIES B, BIOLOGICAL SCIENCES', '', '', '00804649', NULL, '\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3878, 1, 'PROCEEDINGS. MATHEMATICAL AND PHYSICAL SCIENCES', '', '', '09628444', NULL, '\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3879, 1, 'ANNALES ACADEMIAE SCIENTIARUM FENNICAE', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3880, 1, 'JOURNAL OF SCIENTIFIC INSTRUMENTS', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3881, 1, 'MICROBIOS', '', '\'\'', '0026-2633 e: 0026-2633', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3882, 1, 'ENTOMOLOGICAL MAGAZINE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3883, 1, 'BIOCONTROL', 'BioControl', '', '1386-6141 (e): 1573-8248', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3884, 1, 'SCIENTIST AND CITIZEN', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3885, 1, 'RADIATION BOTANY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3886, 1, 'HAZARDOUS WASTE AND HAZARDOUS MATERIALS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3887, 1, 'ENVIRONMENTAL POLLUTION. SERIES A, ECOLOGICAL AND BIOLOGICAL', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3888, 1, 'ENVIRONMENTAL POLLUTION. SERIES B, CHEMICAL AND PHYSICAL', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3889, 1, 'ENVIRONMENTAL TECHNOLOGY LETTERS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3890, 1, 'ENVIRONMENTAL TOXICOLOGY AND WATER QUALITY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3891, 1, 'TRANSACTIONS / AMERICAN GEOPHYSICAL UNION', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3892, 1, 'CHESAPEAKE SCIENCE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3893, 1, 'EUROPEAN ARCHIVES OF PSYCHIATRY AND NEUROLOGICAL SCIENCES', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3894, 1, 'ATMOSPHERIC AND OCEANIC OPTICS', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3895, 1, 'JOURNAL OF CLINICAL PEDIATRIC DENTISTRY', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3896, 1, 'JOURNAL OF ESTHETIC DENTISTRY', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3897, 1, 'ZEITSCHRIFT FUR PHYSIKALISCHE CHEMIE, STOCHIOMETRIE UND VERWANDTSCHAFTSLEHRE', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3898, 1, 'REACTIVE AND FUNCTIONAL POLYMERS', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3899, 1, 'ADVANCES IN FLUORINE CHEMISTRY', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3900, 1, 'LIFE SCIENCE CONTRIBUTIONS', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3901, 1, 'PROTEIN AND PEPTIDE LETTERS', '', '\'\'', '0929-8665', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3902, 1, 'AAPG BULLETIN', 'AAPG bull.', 'Tulsa, Okla., American Association of Petroleum Geologists', '01491423', NULL, 'Mensual', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3904, 1, 'ZEITSCHRIFT FUR TIERPSYCHOLOGIE = JOURNAL OF COMPARATIVE ETHOLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3905, 1, 'ARCHIV FUR KLINISCHE MEDIZIN', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3906, 1, 'CHEMISTRY AND PHYSICS OF LIPIDS', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3907, 1, 'DISCUSSIONS OF THE FARADAY SOCIETY', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3908, 1, 'HUMAN NUTRITION. APPLIED NUTRITION', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3909, 1, 'JOURNAL DE MECANIQUE THEORIQUE ET APPLIQUEE = JOURNAL OF THEORETICAL AND APPLIED MECHANICS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3910, 1, 'EUROPEAN JOURNAL OF OBSTETRICS AND GYNECOLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3911, 1, 'SCANDINAVIAN JOURNAL OF DENTAL RESEARCH', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3912, 1, 'ACTA PHARMACEUTICA FENNICA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3913, 1, 'ACTA PHYSIOLOGICA ET PHARMACOLOGICA NEERLANDICA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3914, 1, 'JOURNAL OF SOIL SCIENCE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3915, 1, 'PEDOLOGIE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3916, 1, 'SCIENCE DU SOL (PLAISIR, FRANCE : 1984)', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3917, 1, 'REVUE DE CHIMIE MINERALE = INORGANIC CHEMISTRY REVIEW', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3918, 1, 'LITHOS', 'Lithos', 'Oslo : Universitetsforlaget, 1968-', '00244937', NULL, 'Quarterly', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3919, 1, 'PROCEEDINGS: BIOLOGICAL SCIENCES', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3920, 1, 'DALTON (CAMBRIDGE, ENGLAND)', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3921, 1, 'FLEISCHWIRTSCHAFT', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3922, 1, 'PROGRESS IN WATER TECHNOLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3923, 1, 'INTERNATIONAL JOURNAL OF DAIRY TECHNOLOGY', 'Int. J. Dairy Technol.', 'Huntingdon, Inglaterra, GB : Society Of Dairy Technology', '1364727X', NULL, 'Trimestral', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3924, 1, 'JOURNAL OF THE SOCIETY OF DAIRY TECHNOLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3925, 1, 'CONTORNO', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3926, 1, 'CANADIAN JOURNAL OF PUBLIC HEALTH', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3927, 1, 'JOURNAL OF PATHOLOGY AND BACTERIOLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3928, 1, 'JOURNAL OF POLYMER RESEARCH', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3929, 1, 'BIRTH DEFECTS: ORIGINAL ARTICLE SERIES', 'Birth Defects Orig. Artic. Ser.', 'New York : A.R. Liss [etc.], 1965-1996 // National Foundation', '05476844', NULL, 'Irregular', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3930, 1, 'NATIONAL INSTITUTE OF ANIMAL HEALTH QUARTERLY', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3931, 1, 'ANNALES DE PHYSIQUE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3932, 1, 'ANNALES DE CHIMIE (1789)', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3933, 1, 'CESKOSLOVENSKA PARASITOLOGIE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3934, 1, 'REVISTA DE LA FACULTAD DE CIENCIAS VETERINARIAS / UNIVERSIDAD CENTRAL DE VENEZUELA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3935, 1, 'ZENTRALBLATT FUR HYGIENE UND UMWELTMEDIZIN', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:56', '2016-01-12 14:16:17'),
(3936, 1, 'FRUIT PROCESSING', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3937, 1, 'NOTES OF THE ROYAL BOTANIC GARDEN EDINBURGH', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3938, 1, 'EUROPEAN HISTORY QUARTERLY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3939, 1, 'LETTERE AL NUOVO CIMENTO DELLA SOCIETA ITALIANA DI FISICA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3940, 1, 'JOURNAL DE PHYSIQUE . LETTRES', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3941, 1, 'FUNGAL GENETICS AND BIOLOGY', '', '', '1087-1845', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3942, 1, 'FEDERATION PROCEEDINGS', 'Fed Proc', '', '0014-9446', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3943, 1, 'FATIGUE OF ENGINEERING MATERIALS AND STRUCTURES', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3944, 1, 'ADVANCES IN POLYMER SCIENCE', 'Adv. Polym. Sci.', 'Berlin, New York, Springer-Verlag', '00653195', NULL, 'Irregular', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3945, 1, 'TRANSACTIONS OF THE AMERICAN CERAMIC SOCIETY CONTAINING THE PAPERS AND DISCUSSIONS OF THE ... ANNUAL MEETING', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3946, 1, 'CERAMIC ABSTRACTS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3947, 1, 'BULLETIN OF THE JAPANESE SOCIETY OF SCIENTIFIC FISHERIES = NIPPON SUISAN GAKKAISHI', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3948, 1, 'REVISTA LATINOAMERICANA DE CIENCIAS AGRICOLAS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3949, 1, 'FLORIDA BUGGIST', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3950, 1, 'FOLIA GEOBOTANICA ET PHYTOTAXONOMICA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3951, 1, 'CESKOSLOVENSKA MIKROBIOLOGIE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3952, 1, 'UNITED STATES DEPARTMENT OF AGRICULTURE. FOREST SERVICE, GENERAL TECHNICAL REPORT NC', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3953, 1, 'FOOD AND CHEMICAL TOXICOLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3954, 1, 'FOOD TECHNOLOGY IN AUSTRALIA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3955, 1, 'FOOD DEVELOPMENT', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3956, 1, 'BIOORGANICHESKAIA KHIMIIA', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3957, 1, 'TECNICA MOLITORIA', 'Tec. Molit.', 'Pinerolo, Italia, IT : Chiriotti Editori', '00401862', NULL, 'Mensal', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3958, 1, 'REVISTA ESPAÑOLA DE CIENCIA Y TECNOLOGIA DE ALIMENTOS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3959, 1, 'PROCEEDINGS OF THE INSTITUTE OF FOOD TECHNOLOGISTS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3960, 1, 'NEWSLETTER OF THE INSTITUTE OF FOOD TECHNOLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3961, 1, 'TRANSACTIONS OF THE INSTITUTE OF FOOD TECHNOLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3962, 1, 'FORENSIC SCIENCE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3963, 1, 'ADVANCES IN FREE RADICAL BIOLOGY AND MEDICINE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3964, 1, 'JOURNAL OF FREE RADICALS BIOLOGY & MEDICINE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3965, 1, 'ZEITSCHRIFT FUR ANALYTISCHE CHEMIE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3966, 1, 'FUEL IN SCIENCE AND PRACTICE', '', '', '0367-3367', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3967, 1, 'TOXICOLOGICAL SCIENCES', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3968, 1, 'JOURNAL DE PHARMACOLOGIE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3969, 1, 'ANNALS OF THE INTERNATIONAL GEOPHYSICAL YEAR', 'Ann. Int. Geophys. Year', 'London, GB : Pergamon Press', '00034959', NULL, 'Anual', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3970, 1, 'MORPHOLOGISCHES JAHRBUCH', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3971, 1, 'ZENTRALBLATT FUR PATHOLOGIE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3972, 1, 'COMPARATIVE AND GENERAL PHARMACOLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3973, 1, 'MOLECULAR AND CELLULAR BIOLOGY', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3974, 1, 'PHYSIOLOGICAL PLANT PATHOLOGY', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3975, 1, 'INTERNATIONAL INDUSTRIAL BIOTECHNOLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3976, 1, 'NEW GENETICS AND SOCIETY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3977, 1, 'IDENGAKU ZASSHI', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3978, 1, 'KULTURPFLANZE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3979, 1, 'REVISTA BRASILEIRA DE GENETICA = BRAZILIAN JOURNAL OF GENETICS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3980, 1, 'PCR METHODS AND APPLICATIONS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3981, 1, 'GEOPHYSICAL JOURNAL', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3982, 1, 'JOURNAL OF THE SOCIETY OF PETROLEUM GEOPHYSICISTS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3983, 1, 'GERONTOLOGIA CLINICA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3984, 1, 'GERONTOLOGIA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3985, 1, 'BROT UND GEBACK', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3986, 1, 'WESTERN NORTH AMERICAN NATURALIST', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3987, 1, 'GROUND WATER MONITORING AND REMENDATION', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3988, 1, 'GYNECOLOGIC INVESTIGATION', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3989, 1, 'ISSUES IN HEALTH CARE OF WOMEN', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3990, 1, 'CLINICS IN HAEMATOLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3991, 1, 'CLINICS IN ONCOLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3992, 1, 'HENRY FORD HOSPITAL MEDICAL JOURNAL', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3993, 1, 'CALIFORNIA AGRICULTURAL EXPERIMENT STATION. TECHNICAL PAPER - AGRICULTURAL EXPERIMENT STATION, BERKELEY, CALIFORNIA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3994, 1, 'ZEITSCHRIFT FUR ZELLFORSCHUNG UND MIKROSKOPISCHE ANATOMIE. HISTOCHEMIE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3995, 1, 'ZEITSCHRIFT FUR RASSENKUNDE UND DIE GESAMTE FORSCHUNG AM MENSCHEN', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3996, 1, 'ZEITSCHRIFT FUR PHYSIOLOGISCHE CHEMIE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3997, 1, 'REVISTA DE OLERICULTURA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3998, 1, 'POLLUTION ENGINEERING', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(3999, 1, 'INFANCIA Y APRENDIZAJE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4000, 1, 'JOURNAL OF URBAN ECONOMICS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4001, 1, 'A HORA VETERINARIA', 'Hora Vet.', 'Porto Alegre, RS : A Hora Veterinaria', '01019163-0203 (', NULL, 'Bimestral', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4002, 1, 'CERAMICA', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4003, 1, 'ACTA BIOCHIMICA POLONICA', 'Acta Biochim. Pol.', 'Warszawa, PL : Panstwowe Wydawnictwo Naukowe', '0001527X', NULL, 'Trimestral', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4004, 1, 'METAL IONS IN BIOLOGICAL SYSTEMS', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4005, 1, 'SMALL RUMINANT RESEARCH', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4006, 1, 'THE CLASSICAL REVIEW', 'Class. Rev.', 'London : D. Nutt ; New York : G.P. Putnam#s Sons, 1887- // Oxford : Clarendon Press , cop1886', '0009840X', NULL, 'Monthly (except Jan., Aug., and Sept)., 1887-1906 ; Monthly (except Jan., Apr., July, and Oct.), Mar', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4007, 1, 'JOURNAL OF GASTROENTEROLOGY', 'J. Gastroenterol.', 'Tokyo : Springer International , 1994-', '09441174', NULL, 'Mensal', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4008, 1, 'GASTROENTEROLOGIA JAPONICA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4009, 1, 'ZEITSCHRIFT FUR GASTROENTEROLOGIE', 'Z. Gastroenterol.', 'Grafelfing, Alemanha, DE : Demeter', '00442771', NULL, 'Mensal', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4010, 1, 'JOURNAL OF PHARMACY AND PHARMACEUTICAL SCIENCES', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4011, 1, 'REPRODUCTIVE BIOMEDICINE ONLINE', '', '', '14726483', NULL, '', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4012, 1, 'JOURNAL OF MAGNETIC RESONANCE', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4013, 1, 'MUTATION RESEARCH / REVIEWS IN MUTATION RESEARCH', '', '', '13835742', NULL, '\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4014, 1, 'MUTATION RESEARCH / FUNDAMENTAL AND MOLECULAR MECHANISMS OF MUTAGENESIS', '', '', '00275107', NULL, '\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4015, 1, 'FOOD SERVICE TECHNOLOGY', '', '', '14715732', NULL, '\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4016, 1, 'THE MICROSCOPE', 'Microscope', 'Carshalton Beeches, England : Microscope Publications', '0026282X', NULL, 'Quarterly', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4017, 1, 'BULLETIN OF THE INSTITUTE OF CHEMISTRY ACADEMIA SINICA', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4018, 1, 'FOOD AND BIOPRODUCTS PROCESSING: TRANSACTIONS OF THE INSTITUTION OF CHEMICAL ENGINEERS, PART C.', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4019, 1, 'JOURNAL OF ZOO AND WILDLIFE MEDICINE', '', 'Lawrence, Kan., US : American Association Of Zoo Veterinarians', '10427260', NULL, 'Trimestral', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4020, 1, 'JOURNAL OF ZOO ANIMAL MEDICINE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4021, 1, 'ZEITSCHRIFT FUR PHYSIKALISCHE CHEMIE. NEUE FOLGE', 'Z. Phys. Chem. (Neue Folge)', 'Frankfurt am Main : Akademische Verlagsgesellschaft, 1954- // Munchen, Alemanha, DE : R. Oldenbourg', '00443336', NULL, 'Twelve no. a year, 1954- ; 5 no. a year, <1978-> ; Irregular, <1984->', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4022, 1, 'PROCEEDINGS / SOIL AND CROP SCIENCE SOCIETY OF FLORIDA', '', 'Belle Glade, Fla., US : Soil And Crop Science Society Of Florida', '00964522', NULL, 'Anual', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4023, 1, 'SOIL SCIENCE SOCIETY OF FLORIDA - PROCEEDINGS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4024, 1, 'MICROSCOPE AND CRYSTAL FRONT', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4025, 1, 'ANTIBIOTICS AND CHEMOTHERAPY', 'Antibiot. Chemother.', 'Washington, US : Washington Institute Of Medicine // Northfield, Ill., etc., Antibiotics & Chemother', '05703123', NULL, 'Mensal', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4026, 1, 'U.S. FOREST SERVICE RESEARCH NOTE RM.', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4027, 1, 'CLINICAL MEDICINE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4028, 1, 'ANTIBIOTIC MEDICINE AND CLINICAL THERAPY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4029, 1, 'INTERNATIONAL RECORD OF MEDICINE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4030, 1, 'INTERNATIONAL JOURNAL OF THERMOPHYSICS', '', '\'\'', '0195-928X', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4031, 1, 'BIOLOGICAL REVIEWS OF THE CAMBRIDGE PHILOSOPHICAL SOCIETY', 'Biol. Rev. Camb. Philos. Soc', 'London : Cambridge University Press, [1935]-', '00063231', NULL, 'Quarterly', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4032, 1, 'METAL PROGRESS', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4033, 1, 'BIOLOGICAL REVIEWS AND BIOLOGICAL PROCEEDINGS OF THE CAMBRIDGE PHILOSOPHICAL SOCIETY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4034, 1, 'ASTM SPECIAL TECHNICAL PUBLICATION', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4035, 1, 'HUMAN TOXICOLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4036, 1, 'HUMANGENETIK = GENETIQUE HUMAINE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4037, 1, 'JOURNAL OF HUMAN NUTRITION', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4038, 1, 'CLINICAL AND EXPERIMENTAL HYPERTENSION. PART B, HYPERTENSION IN PREGNANCY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4039, 1, 'INTERNATIONAL GEOPHYSICAL YEAR', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4040, 1, 'PLANT JOURNAL', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4041, 1, 'PROGRESS IN BIOPHYSICS AND MOLECULAR BIOLOGY', 'Progr Biophys Mol Biol', '\'\'', '0079-6107', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4042, 1, 'PAPERI JA PUU = PAPER AND TIMBER', 'Pap. Puu', 'Helsinki, FI : Suomen Puunjalostusteollisuuden Keskusliitto', '00311243', NULL, 'Mensal', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4043, 1, 'ESPRI RESEARCH REPORTS', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4044, 1, 'JOURNAL OF ACHAEOLOGICAL  SCIENCE', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4045, 1, 'EUROPEAN POWER ELECTRONICS AND DRIVES JOURNAL', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4046, 1, 'ALISO', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4047, 1, 'MEAT SCIENCE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4048, 1, 'LAIT', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4049, 1, 'ELECTROCHEMICAL AND SOLID-STATE LETTERS', 'Electrochem. Solid-State Lett.', 'Pennington, NJ : The Electrochemical Society, c1998-', '1099-0062', NULL, 'Monthly', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4050, 1, 'JOURNAL OF ALGORITHMS', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4051, 1, 'SOCIETY OF PETROLEUM ENGINEERS JOURNAL', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4052, 1, 'CANCER CHEMOTHERAPY AND PHARMACOLOGY', 'Cancer Chemother. Pharmacol.', 'Berlin, New York, Springer International', '03445704', NULL, 'Mensal', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4053, 1, 'CHUNG KUO I HSUEH KO HSUEH YUAN HSUEH PAO', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4054, 1, 'JAPANESE JOURNAL OF CANCER RESEARCH', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4055, 1, 'PHOTODERMATOLOGY, PHOTOIMMUNOLOGY AND PHOTOMEDICINE', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4056, 1, 'AGRICULTURA TECNICA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4057, 1, 'PERKIN 2: AN INTERNATIONAL JOURNAL OF PHYSICAL ORGANIC CHEMISTRY', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4058, 1, 'QUARTERLY JOURNAL OF MICROSCOPICAL SCIENCE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4059, 1, 'CHEMISTRY - A EUROPEAN JOURNAL', 'Chem Eur J', '', '0947-6539 (e): 1521-3765', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4060, 1, 'INTERNATIONAL JOURNAL OF REMOTE SENSING', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4061, 1, 'ANNALS AND MAGAZINE OF NATURAL HISTORY. SERIE 7', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4062, 1, 'ANIMAL BEHAVIOUR', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4063, 1, 'PROCEEDINGS ( ELECTROCHEMICAL SOCIETY )', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4064, 1, 'JOURNAL OF MEDICAL AND VETERINARY MYCOLOGY', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4065, 1, 'NATURAL TOXINS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4066, 1, 'JOURNAL OF MICROWAVE POWER AND ELECTROMAGNETIC ENERGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4067, 1, 'JOURNAL OF THE AMERICAN WATER WORKS ASSOCIATION', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4068, 1, 'ANNALS OF SCHOLARSHIP', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4069, 1, 'STUDIUM GENERALE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4070, 1, 'IRISH JOURNAL OF AGRICULTURAL AND FOOD RESEARCH', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4071, 1, 'JOURNAL OF POLYMER SCIENCE. POLYMER LETTERS EDITION', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4072, 1, 'ENVIRONMENTAL AND MOLECULAR MUTAGENESIS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4073, 1, 'REGULATORY TOXICOLOGY AND PHARMACOLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4074, 1, 'ENVIRONMENTAL MUTAGENESIS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4075, 1, 'FOLHA MEDICA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4076, 1, 'INTERNATIONAL JOURNAL OF OPTOELECTRONICS', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4077, 1, 'ANNALS OF ALLERGY', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4078, 1, 'JOURNAL OF ANALYTICAL AND APPLIED PYROLYSIS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4079, 1, 'RUSSIAN JOURNAL OF ELECTROCHEMISTRY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4080, 1, 'GEOLOGICAL SOCIETY OF  AMERICA BULLETIN', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4081, 1, 'JOURNAL OF HYGIENE, EPIDEMIOLOGY, MICROBIOLOGY, AND IMMUNOLOGY', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4082, 1, 'INDOOR AIR', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4083, 1, 'MEDICAL AND VETERINARY ENTOMOLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4084, 1, 'ZHURNAL PRIKLADNOI SPEKTROSKOPII', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4085, 1, 'JOURNAL OF SPACECRAFT TECHNOLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4086, 1, 'TOHOKU MATHEMATICAL JOURNAL', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4087, 1, 'PLURAL', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4088, 1, 'WIRE JOURNAL INTERNATIONAL', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4089, 1, 'ACTA VETERINARIA ACADEMIAE SCIENTIARUM HUNGARICAE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4090, 1, 'FUNCTIONAL POLYMERS', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4091, 1, 'IDEA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4092, 1, 'AVANCES EN ODONTOESTOMATOLOGIA', 'Av Odontoestomatol', '', '0213-1285', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4093, 1, 'ZEITSCHRIFT DER DEUTSCHEN GEOLOGISCHEN GESELLSCHAFT', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4094, 1, 'ENVIRON MOL MUTAGEN', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4095, 1, 'COPEIA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4096, 1, 'SOCIOLOGICAL METHODS AND RESEARCH', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4097, 1, 'INTERNATIONAL JOURNAL OF REFRIGERATION = REVUE INTERNATIONALE DU FROID', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4098, 1, 'HINYOKIKA KIYO = ACTA UROLOGICA JAPONICA', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4099, 1, 'JOURNAL OF HEAT TRANSFER', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4100, 1, 'POSTGRADUATE MEDICAL JOURNAL', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4101, 1, 'CANADIAN PLANT DISEASE SURVEY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4102, 1, 'ANNUAL REVIEW OF PHARMACOLOGY AND TOXICOLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4103, 1, 'EXPERIMENTAL BIOLOGY AND MEDICINE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4104, 1, 'ZOOLOGISCHE JAHRBUCHER ABTEILUNG FUR SYSTEMATIK OKOLOGIE UND GEOGRAPHIE DER TIERE', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4105, 1, 'JOURNAL OF INVESTIGATIVE DERMATOLOGY. SYMPOSIUM PROCEEDINGS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4106, 1, 'JOURNAL OF PHOTOCHEMISTRY AND PHOTOBIOLOGY. B, BIOLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4107, 1, 'MATHEMATICAL PROGRAMMING', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4108, 1, 'DERMATOLOGY', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4109, 1, 'INDIAN JOURNAL OF ENVIRONMENTAL PROTECTION', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4110, 1, 'JOURNAL OF ENVIRONMENTAL SCIENCE AND HEALTH. PART A, ENVIRONMENTAL SCIENCE AND ENGINEERING', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4111, 1, 'JOURNAL OF ELECTROCERAMICS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4112, 1, 'IONICS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4113, 1, 'REVISTA JURIDICA ARGENTINA LA LEY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2015-09-01 14:25:05'),
(4114, 1, 'BIOTECHNOLOGY ANNUAL REVIEW', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4116, 1, 'JOURNAL OF FAILURE ANALYSIS AND PREVENTION', '', '', '1547-7029', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4117, 1, 'BUNDESGESUNDHBLATT', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4118, 1, 'DEUTSCHE LEBENSMITTEL-RUNDSCHAU', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4119, 1, 'TELECOMMUNICATION JOURNAL', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4120, 1, 'JOURNAL OF AUTOIMMUNITY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4121, 1, 'SOIL BIOLOGY AND BIOCHEMISTRY', 'Soil Biol Biochem', '\'\'', '0038-0717', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4122, 1, 'HEALTH LABORATORY SCIENCE', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4123, 1, 'PHARMACY WORLD AND SCIENCE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4124, 1, 'THEORETICAL AND EXPERIMENTAL CHEMISTRY', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4125, 1, 'REVIEWS IN INORGANIC CHEMISTRY', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4126, 1, 'JOURNAL OF THE KOREAN FISHERIES SOCIETY', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4127, 1, 'IDF STANDARDS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4128, 1, 'FORESTRY EXPERIMENTAL STATION, MEGURO, TOKYO BULLETIN', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4129, 1, 'REVISTA DE METALURGIA', '', '\'\'', '0034-8570', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:17'),
(4130, 1, 'JOURNAL OF SHELLFISH RESEARCH', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4131, 1, 'KEW BULLETIN', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4132, 1, 'JOURNAL OF STEROID BIOCHEMISTRY', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4134, 1, 'JOURNAL DE RECHERCHES ATMOSPHERIQUES', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4135, 1, 'HERPETOLOGICA', '', '', '1938-5099', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4136, 1, 'GEOLOGICAL SOCIETY OF AMERICA SPECIAL PAPER', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4137, 1, 'BLOOD', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4139, 1, 'JOM: THE JOURNAL OF THE MINERALS, METALS AND MATERIALS SOCIETY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4140, 1, 'PROGRESS IN SURFACE SCIENCE', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4141, 1, 'INTERNATIONAL JOURNAL OF INFRARED AND MILLIMETER WAVES', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4142, 1, 'JOURNAL OF THERMAL BIOLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4143, 1, 'ANZEIGER FUR SCHADLINGSKUNDE, PFLANZENSCHUTZ, UMWELTSCHUTZ', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4144, 1, 'PARASITICA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4145, 1, 'FUNDAMENTAL AND APPLIED NEMATOLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4146, 1, 'JUSTUS LIEBIG\'S ANNALEN DER CHEMIE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4147, 1, 'ARCHIV FUR HYDROBIOLOGIE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2015-09-01 14:25:06'),
(4148, 1, 'ELASTOMERICS', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4149, 1, 'TRANSACTIONS OF THE AMERICAN FISHERIES SOCIETY', 'Trans. Amer. Fish. Soc.', '\'\'', 'Online: 1548-8659  Print: 0002-8487', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4152, 1, 'JOURNAL OF POLYMER MATERIALS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4153, 1, 'SOLAR ENERGY', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4154, 1, 'KIVA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4155, 1, 'PHILOSOPHICAL MAGAZINE A. PHYSICS OF CONDENSED MATTER. STRUCTURE DEFECTS AND MECHANICAL PROPERTIES', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4156, 1, 'ANALES DE LA ASOCIACION QUIMICA ARGENTINA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2015-09-01 14:25:11'),
(4157, 1, 'WEST INDIAN MEDICAL JOURNAL', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4158, 1, 'DNA AND CELL BIOLOGY', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4159, 1, 'VOCES', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4160, 1, 'ACTA ZOOLOGICA ACADEMIAE SCIENTIARUM HUNGARICAE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4161, 1, 'AMERICAN FAMILY PHYSICIAN', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4162, 1, 'CURRENT GASTROENTEROLOGY REPORTS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4163, 1, 'HEPATOBILIARY AND PANCREATIC DISEASES INTERNATIONAL', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4164, 1, 'BOLETIN MICOLOGICO. SANTIAGO DE CHILE', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4166, 1, 'JOURNAL OF MATERIALS IN CIVIL ENGINEERING', '', '', '0899-1561', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4167, 1, 'HIKAKU KAGAKU', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4168, 1, 'KEIO JOURNAL OF MEDICINE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4170, 1, 'NUCLEAR PHYSICS A', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4171, 1, 'INDIAN JOURNAL OF ANIMAL SCIENCES', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4172, 1, 'BIOLOGICAL CONSERVATION', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4173, 1, 'NEW ZEALAND JOURNAL OF FORESTRY SCIENCE', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4174, 1, 'BEHAVIOR RESEARCH METHODS AND INSTRUMENTATION', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4175, 1, 'AMERICAN JOURNAL OF OPTOMETRY AND ARCHIVES OF AMERICAN ACADEMY OF OPTOMETRY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4176, 1, 'OPTICIAN', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4177, 1, 'JOURNAL OF THE AMERICAN OPTOMETRIC ASSOCIATION', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4178, 1, 'AMERICAN JOURNAL OF OPTOMETRY AND PHYSIOLOGICAL OPTICS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4179, 1, 'CHEMICKE LISTY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18');
INSERT INTO `journal` (`id`, `instance_id`, `name`, `abbreviation`, `responsible`, `ISSN`, `ISSNE`, `frecuency`, `createdAt`, `updatedAt`) VALUES
(4181, 1, 'INFRARED PHYSICS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4182, 1, 'INDIAN JOURNAL OF HELMINTHOLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4183, 1, 'IMMUNOLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4184, 1, 'RICE JOURNAL', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4185, 1, 'PROCEEDINGS OF THE INDIAN ACADEMY OF SCIENCES. ANIMAL SCIENCES', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4186, 1, 'IEE PROCEEDINGS. GENERATION, TRANSMISSION AND DISTRIBUTION', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4187, 1, 'IEE PROCEEDINGS. G, CIRCUITS, DEVICES AND SYSTEMS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4188, 1, 'IEE PROCEEDINGS. RADAR, SONAR AND NAVIGATION', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4189, 1, 'IEE PROCEEDINGS. I, COMMUNICATIONS, SPEECH AND VISION', '', 'Stevenage, Herts., U.K: IEE, [c1989-1993', '09563776', NULL, 'Six no. a year', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4190, 1, 'TATA SEARCH', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4191, 1, 'PHYSIOLOGICAL REVIEWS', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4192, 1, 'BOLETIN DE LIMA', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4193, 1, 'IRISH VETERINARY JOURNAL', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4194, 1, 'EUROPEAN JOURNAL OF PHARMACEUTICS AND BIOPHARMACEUTICS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4195, 1, 'INTERNATIONAL JOURNAL OF ENVIRONMENTAL HEALTH RESEARCH', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4196, 1, 'PROTEIN JOURNAL', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4197, 1, 'AMBIO', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4198, 1, 'WATER  POLLUTION CONTROL', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4199, 1, 'BULLETIN DE L\'INSTITUT FRANCAIS D\'AFRIQUE NOIRE, SERIE A. SCIENCES NATURELLES', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4200, 1, 'QATAR UNIVERSITY, SCIENCE JOURNAL', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4201, 1, 'JOURNAL OF INDUSTRIAL AERODYNAMICS', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4202, 1, 'JOURNAL OF REFRACTIVE SURGERY', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4203, 1, 'STUDI ITALIANI DI FILOLOGIA CLASSICA', '', '\'\'', '0039-2987', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4204, 1, 'HARVARD THEOLOGICAL REVIEW', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4205, 1, 'JOURNAL OF INCLUSION PHENOMENA', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4206, 1, 'FRESHWATER BIOLOGY', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4207, 1, 'PROCEEDINGS OF THE HAWAIIAN ENTOMOLOGICAL SOCIETY', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4208, 1, 'INGENIERIA AERONAUTICA Y ASTRONAUTICA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4209, 1, 'AQUATIC CONSERVATION: MARINE AND FRESHWATER ECOSYSTEMS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4210, 1, 'BOLETIN DE INFORMACION DENTAL', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4211, 1, 'STEEL RESEARCH INTERNATIONAL', 'Steel Res Int', '\'\'', '1611-3683 e: 1869-344X', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4212, 1, 'CRUSTACEAN ISSUES', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4213, 1, 'CIBC TECHNICAL COMMUNICATIONS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4214, 1, 'BULLETIN DE LA SOCIETE CHIMIQUE DE PARIS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4215, 1, 'MOLECULAR BIOLOGY REPORTS', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4216, 1, 'JOURNAL OF ATMOSPHERIC AND TERRESTRIAL PHYSICS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4217, 1, 'DEMONSTRATIO MATHEMATICA', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4218, 1, 'HYDROCARBON PROCESSING', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4219, 1, 'PHARMACEUTICAL DEVELOPMENT AND TECHNOLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4220, 1, 'ESTUDIOS OCEANOLOGICOS', 'Estud. oceanol.', '', '0071-173X', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4221, 1, 'LEDER', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4222, 1, 'METHODS AND FINDINGS IN EXPERIMENTAL AND CLINICAL PHARMACOLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4223, 1, 'AMERICAN JOURNAL OF KIDNEY DISEASES', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4224, 1, 'EUROPEAN JOURNAL OF CLINICAL PHARMACOLOGY', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4225, 1, 'STEEL GRIPS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4226, 1, 'PLANT SYSTEMATICS AND EVOLUTION', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4227, 1, 'LEONARDO', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4228, 1, 'MISCELLANEOUS PUBLICATION (US DEPARTMENT OF AGRICULTURE )', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4229, 1, 'ANALYTICAL AND BIOANALYTICAL CHEMISTRY', '', '', '1618-2642. ISSN (electronic): 1618-2650.', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4230, 1, 'BIOFERTILISER NEWSLETTER', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4231, 1, 'adv drug deliv rev', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4233, 1, 'TUBERCULOSIS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4234, 1, 'CLINICAL NEPHROLOGY', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4235, 1, 'BOLETIN MEDICO DE POSTGRADO', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4236, 1, 'REVISTA DE OBSTETRICIA Y GINECOLOGIA DE VENEZUELA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4237, 1, 'REVISTA CHILENA DE ULTRASONOGRAFIA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4238, 1, 'GACETA MEDICA DE CARACAS', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4239, 1, 'REVISTA CHILENA DE OBSTETRICIA Y GINECOLOGIA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4240, 1, 'REVISTA DEL COLEGIO DE MEDICOS DEL ESTADO DE TACHIRA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4241, 1, 'INVESTIGACION CLINICA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4242, 1, 'REVISTA DE LA SOCIEDAD MEDICO-QUIRURGICA DEL HOSPITAL DE EMERGENCIA PEREZ DE LEON', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4243, 1, 'TRUDY GOSUDARSTVENNOGO INSTITUTA EKSPERIMENTALNOI VETERINARII', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4244, 1, 'BULK SOLIDS HANDLING', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4245, 1, 'HERMES', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4246, 1, 'BOLETIN DEL HOSPITAL SAN JUAN DE DIOS (CHILE)', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4247, 1, 'POWER (NEW YORK)', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4248, 1, 'SOLVENT EXTRACTION AND ION EXCHANGE', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4249, 1, 'Proc. R. Soc. Med', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4250, 1, 'PROCEEDINGS OF THE ROYAL SOCIETY OF MEDICINE', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4251, 1, 'BRITISH JOURNAL OF EXPERIMENTAL PATHOLOGY', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4252, 1, 'JOURNAL OF THE BRITISH INSTITUTION OF RADIO ENGINEERS', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4253, 1, 'REVISTA DE LA SOCIEDAD DE OBSTETRICIA Y GINECOLOGIA DE LA PROVINCIA DE BUENOS AIRES', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4254, 1, 'STUDIES IN APPLIED MATHEMATICS', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4255, 1, 'COMPUTERS AND GEOSCIENCES', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4256, 1, 'NUOVA VETERINARIA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4257, 1, 'AGRICULTURAL CHEMISTRY AND BIOTECHNOLOGY', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4258, 1, 'QUIMICA NOVA', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4259, 1, 'ARCHIV FUR MOLLUSKENKUNDE', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4260, 1, 'PROCEEDINGS OF THE NATIONAL SHELLFISHERIES ASSOCIATION', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4261, 1, 'QUARTERLY JOURNAL OF SPEECH', '', '', '', NULL, '\'\'', '2015-09-01 14:24:57', '2016-01-12 14:16:18'),
(4799, 1, 'REVISTA DE LA ASOCIACION ODONTOLOGICA ARGENTINA', '', '', '', NULL, '\'\'', '2015-09-01 14:24:59', '2015-09-01 14:25:07'),
(4864, 1, 'RHEINISCHES MUSEUM FUR PHILOLOGIE', '', '', '0035-449X', NULL, '\'\'', '2015-09-01 14:24:59', '2015-09-01 14:25:08'),
(4874, 1, 'INTERNATIONAL JOURNAL OF DEVELOPMENTAL BIOLOGY', '', '', '', NULL, '\'\'', '2015-09-01 14:24:59', '2016-01-12 14:16:18'),
(4962, 1, 'REVISTA ARGENTINA DE PRODUCCION ANIMAL', '', '', '', NULL, '\'\'', '2015-09-01 14:24:59', '2015-09-01 14:25:05'),
(4996, 1, 'REVUE DES ETUDES LATINES', 'Rev. etud. lat.', '', '0373-5737', NULL, '\'\'', '2015-09-01 14:24:59', '2015-09-01 14:25:09'),
(5179, 1, 'MADRONO', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:25:00', '2015-09-01 14:25:05'),
(5239, 1, 'LES ÉTUDES CLASSIQUES', '', '\'\'', '0014-200X', NULL, '\'\'', '2015-09-01 14:25:00', '2015-09-01 14:25:05'),
(5245, 1, 'ARCHIVOS LATINOAMERICANOS DE NUTRICION', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:25:00', '2015-09-01 14:25:08'),
(5263, 1, 'BULLETIN DE LA SOCIETE CHIMIQUE DE FRANCE', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:25:00', '2015-09-01 14:25:12'),
(5345, 1, 'TODO ES HISTORIA', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:25:01', '2016-01-12 14:16:18'),
(5364, 1, 'REVISTA DE INVESTIGACIONES AGRÍCOLAS', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:25:01', '2015-09-01 14:25:05'),
(5389, 1, 'REVISTA DE LA ASOCIACIÓN CIVIL ARGENTINA DE AUDITORIA ODONTOLÓGICA', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:25:01', '2015-09-01 14:25:04'),
(5503, 1, 'REVISTA DE FILOLOGIA ESPAÑOLA', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:25:01', '2015-09-01 14:25:11'),
(5673, 1, 'REVISTA PERUANA DE BIOLOGÍA', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:25:01', '2015-09-01 14:25:03'),
(5815, 1, 'ANALES DE LA SOCIEDAD CIENTÍFICA ARGENTINA', '', '\'\'', '', NULL, '\'\'', '2015-09-01 14:25:02', '2015-09-01 14:25:05'),
(6393, 1, 'BULLETIN DE L\'ASSOCIATION GUILLAUME BUDE', '', '', '0004-5527', NULL, '', '2015-09-01 14:25:04', '2015-09-01 14:25:05'),
(6532, 1, 'ERGODIC THEORY AND DYNAMICAL SYSTEMS', '0143-3857', '', '', NULL, '', '2015-09-01 14:25:04', '2016-01-12 14:16:07'),
(6590, 1, 'REVISTA ESPAÑOLA DE ECONOMIA', '', '', '', NULL, '', '2015-09-01 14:25:04', '2015-09-01 14:25:06'),
(6713, 1, 'Revista de la Universidad Nacional de Córdoba', '', '', '', NULL, '', '2015-09-01 14:25:05', '2015-09-01 14:25:06'),
(7105, 1, 'HISPAMERICA', '', '', '', NULL, '', '2015-09-01 14:25:06', '2015-09-01 14:25:13'),
(7256, 1, 'Revista de estudios histórico-jurídicos', '', '', '', NULL, '', '2015-09-01 14:25:06', '2015-09-01 14:25:07'),
(7315, 1, 'MEDECINE ET NUTRITION', 'Med. Nutrition', '', '0398-7604', NULL, '', '2015-09-01 14:25:06', '2015-09-01 14:25:08'),
(7574, 1, 'REVISTA LATINOAMERICANA DE SOCIOLOGIA', '', '', '0034-9801', NULL, '', '2015-09-01 14:25:07', '2015-09-01 14:25:09'),
(7596, 1, 'BOLETIN DEL INSTITUTO DE HISTORIA ARGENTINA Y AMERICANA DR. EMILIO RAVIGNANI', '', '', '1850-2563', NULL, '', '2015-09-01 14:25:08', '2015-09-01 14:25:09'),
(8464, 1, 'PERSPECTIVES MEDIEVALES', '', '', '0338-2338', NULL, '', '2015-09-01 14:25:11', '2015-09-01 14:25:11'),
(8696, 1, 'MATERIALS SCIENCE AND ENGINEERING A', '', '', '0921-5093', NULL, '', '2015-09-01 14:25:11', '2016-01-12 14:16:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `librarian_institution`
--

CREATE TABLE `librarian_institution` (
  `user_id` int(11) NOT NULL,
  `institution_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `category` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `message` longtext COLLATE utf8_unicode_ci NOT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `material_type`
--

CREATE TABLE `material_type` (
  `id` int(11) NOT NULL,
  `journal_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `authors` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `year` int(11) NOT NULL,
  `startPage` int(11) DEFAULT NULL,
  `endPage` int(11) DEFAULT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL,
  `deletedAt` datetime DEFAULT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `volume` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `other` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `editor` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `chapter` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ISBN` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `withIndex` tinyint(1) DEFAULT NULL,
  `place` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `communication` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `director` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `degree` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `thread_id` int(11) DEFAULT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `body` longtext COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `message_metadata`
--

CREATE TABLE `message_metadata` (
  `id` int(11) NOT NULL,
  `message_id` int(11) DEFAULT NULL,
  `participant_id` int(11) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mirequests_files`
--

CREATE TABLE `mirequests_files` (
  `event_id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `instance_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `text` longtext COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `active` tinyint(1) NOT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notification`
--

CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `template_id` int(11) NOT NULL,
  `message_notification_id` int(11) DEFAULT NULL,
  `base_user_notification_id` int(11) DEFAULT NULL,
  `event_notification_id` int(11) DEFAULT NULL,
  `cause` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `viewed` tinyint(1) NOT NULL,
  `viewedAt` datetime DEFAULT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notification_receiver`
--

CREATE TABLE `notification_receiver` (
  `notification_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notification_settings`
--

CREATE TABLE `notification_settings` (
  `id` int(11) NOT NULL,
  `user` int(11) DEFAULT NULL,
  `instance` int(11) DEFAULT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `subscribedToInterfaceNotifications` tinyint(1) NOT NULL,
  `subscribedToEmailNotifications` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notification_viewer`
--

CREATE TABLE `notification_viewer` (
  `notification_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `order`
--

CREATE TABLE `order` (
  `id` int(11) NOT NULL,
  `material_data_id` int(11) DEFAULT NULL,
  `original_request_id` int(11) DEFAULT NULL,
  `code` int(11) NOT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL,
  `deletedAt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `provider`
--

CREATE TABLE `provider` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `instance_id` int(11) DEFAULT NULL,
  `celsius_instance_id` int(11) DEFAULT NULL,
  `hive_id` int(11) DEFAULT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `abbreviation` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `provider`
--

INSERT INTO `provider` (`id`, `parent_id`, `city_id`, `country_id`, `instance_id`, `celsius_instance_id`, `hive_id`, `createdAt`, `updatedAt`, `type`, `name`, `abbreviation`, `website`, `address`) VALUES
(1, NULL, NULL, NULL, NULL, NULL, NULL, '2015-09-01 14:23:20', '2015-09-01 14:23:20', 'web', NULL, NULL, NULL, NULL),
(2, NULL, NULL, NULL, NULL, NULL, NULL, '2015-09-01 14:23:20', '2015-09-01 14:23:20', 'author', NULL, NULL, NULL, NULL),
(245, 241, NULL, 3, 1, NULL, NULL, '2015-09-01 14:24:27', '2015-09-01 14:24:27', 'institution', 'Service commun de la documentation. Section Sciences (Villeurbanne, Rhône)', '', NULL, NULL),
(394, NULL, NULL, 4, 1, NULL, NULL, '2015-09-01 14:24:28', '2016-01-12 14:16:01', 'institution', 'Universitetsbiblioteket i Agder', 'Univ', NULL, NULL),
(535, 533, 11, 5, 1, NULL, NULL, '2015-09-01 14:24:28', '2015-09-01 14:24:28', 'institution', 'DIPARTIMENTO DI DIRITTO ROMANO, STORIA E TEORIA DEL DIRITTO', '', NULL, NULL),
(581, 571, 11, 5, 1, NULL, NULL, '2015-09-01 14:24:29', '2015-09-01 14:24:29', 'institution', 'Biblioteca del Dipartimento di studi linguistici e orientali', '', NULL, NULL),
(598, 571, 11, 5, 1, NULL, NULL, '2015-09-01 14:24:29', '2015-09-01 14:24:29', 'institution', 'Biblioteca centralizzata Roberto Ruffilli', '', NULL, NULL),
(601, 571, 11, 5, 1, NULL, NULL, '2015-09-01 14:24:29', '2015-09-01 14:24:29', 'institution', 'Biblioteca del Mulino', '', NULL, NULL),
(630, 571, 11, 5, 1, NULL, NULL, '2015-09-01 14:24:29', '2015-09-01 14:24:29', 'institution', 'Biblioteca Centrale del Campus di Ravenna Sede di Palazzo Corradini', 'BCCR', NULL, NULL),
(632, 571, 11, 5, 1, NULL, NULL, '2015-09-01 14:24:29', '2015-09-01 14:24:29', 'institution', 'Biblioteca del Dipartimento di Storia Culture Civiltà', 'DISCI', NULL, NULL),
(738, 733, 13, 5, 1, NULL, NULL, '2015-09-01 14:24:29', '2015-09-01 14:24:29', 'institution', 'Biblioteca del Dipartimento di scienze dell\'antichità', '', NULL, NULL),
(756, 733, 13, 5, 1, NULL, NULL, '2015-09-01 14:24:29', '2015-09-01 14:24:29', 'institution', 'Biblioteca di agraria della Facoltà di agraria', 'IT-MI0275', NULL, NULL),
(839, 823, 14, 5, 1, NULL, NULL, '2015-09-01 14:24:30', '2015-09-01 14:24:30', 'institution', 'Biblioteca CNR Area della Ricerca di Bologna', '', NULL, NULL),
(844, 843, 14, 5, 1, NULL, NULL, '2015-09-01 14:24:30', '2015-09-01 14:24:30', 'institution', 'Biblioteca centrale dell\'Area umanistica', '', NULL, NULL),
(901, 892, 14, 5, 1, NULL, NULL, '2015-09-01 14:24:30', '2015-09-01 14:24:30', 'institution', 'Servizio biblioteca di economia', '', NULL, NULL),
(1005, NULL, NULL, 5, 1, NULL, NULL, '2015-09-01 14:24:30', '2015-09-01 14:24:31', 'institution', 'Biblioteca Nazionale Centrale di Firenze', 'BNCF', NULL, NULL),
(1099, 1098, NULL, 5, 1, NULL, NULL, '2015-09-01 14:24:31', '2015-09-01 14:24:31', 'institution', 'Biblioteca della Fondazione Antonio Guarasci', 'BFAG', NULL, NULL),
(1124, NULL, NULL, 5, 1, NULL, NULL, '2015-09-01 14:24:31', '2015-09-01 14:24:31', 'institution', 'Biblioteca nazionale Marciana', 'IT-VE0049', NULL, NULL),
(1138, NULL, NULL, 5, 1, NULL, NULL, '2015-09-01 14:24:31', '2015-09-01 14:24:31', 'institution', 'Biblioteca dell\'Accademia nazionale virgiliana', 'IT-MN0040', NULL, NULL),
(1179, 1178, NULL, 5, 1, NULL, NULL, '2015-09-01 14:24:31', '2015-09-01 14:24:31', 'institution', 'Casanatense Biblioteca - Roma', 'CBR', NULL, NULL),
(1184, 1183, NULL, 5, 1, NULL, NULL, '2015-09-01 14:24:31', '2015-09-01 14:24:31', 'institution', 'Biblioteca Comunale Luciano Benincasa', '', NULL, NULL),
(1262, NULL, 17, 7, 1, NULL, NULL, '2015-09-01 14:24:31', '2018-05-04 10:58:01', 'institution', 'Bibliothek der Tierärztlichen Hochschule', 'TIHO', NULL, NULL),
(1415, NULL, NULL, 7, 1, NULL, NULL, '2015-09-01 14:24:32', '2015-09-01 14:24:32', 'institution', 'Universität Stuttgart', 'USt', NULL, NULL),
(1546, 1541, 25, 10, 1, NULL, NULL, '2015-09-01 14:24:32', '2015-09-01 14:24:33', 'institution', 'Linköpings universitetsbibliotek', 'LUB', NULL, NULL),
(1569, 1567, 25, 10, 1, NULL, NULL, '2015-09-01 14:24:33', '2015-09-01 14:24:33', 'institution', 'Skeriabiblioteket i Skellefteå', '', NULL, NULL),
(1589, 1588, 26, 10, 1, NULL, NULL, '2015-09-01 14:24:33', '2016-02-25 17:59:38', 'institution', 'Almedalsbiblioteket', '', NULL, NULL),
(1669, NULL, NULL, 10, 1, NULL, NULL, '2015-09-01 14:24:33', '2015-09-01 14:24:33', 'institution', 'Svenska Filminstitutet', 'SVI', NULL, NULL),
(2013, 1997, 44, 27, 1, NULL, 1, '2015-09-01 14:24:34', '2015-09-01 14:24:35', 'institution', 'Facultad de Filosofía y Letras', '', NULL, NULL),
(2018, 2013, 44, 27, 1, NULL, NULL, '2015-09-01 14:24:34', '2015-09-01 14:24:35', 'institution', 'Instituto de Filología Clásica', NULL, NULL, NULL),
(2114, 2113, 44, 27, 1, NULL, NULL, '2015-09-01 14:24:35', '2016-02-25 18:01:44', 'institution', 'Archivo Histórico del Museo de La Plata', NULL, NULL, NULL),
(2205, NULL, 47, 27, 1, NULL, NULL, '2015-09-01 14:24:35', '2016-01-12 14:16:01', 'institution', 'UNIVERSIDAD NACIONAL DE LA PAMPA', 'UPAM', NULL, NULL),
(2217, 2216, 48, 27, 1, NULL, NULL, '2015-09-01 14:24:35', '2016-01-12 14:16:01', 'institution', 'IMBICE', '', NULL, NULL),
(2221, NULL, 48, 27, 1, NULL, NULL, '2015-09-01 14:24:35', '2016-01-12 14:16:01', 'institution', 'ASOCIACION ARGENTINA DE ASTRONOMIA', 'AAA', NULL, NULL),
(2223, NULL, 48, 27, 1, NULL, NULL, '2015-09-01 14:24:35', '2016-01-12 14:16:01', 'institution', 'COMISIÓN DE INVESTIGACIONES CIENTÍFICAS', 'CIC', NULL, NULL),
(2233, NULL, 48, 27, 1, NULL, NULL, '2015-09-01 14:24:35', '2016-01-12 14:16:01', 'institution', 'Hospital Italiano de La Plata', 'HILP', NULL, NULL),
(2251, NULL, 48, 27, 1, NULL, NULL, '2015-09-01 14:24:35', '2016-10-11 07:42:13', 'institution', 'Universidad Tecnologica Nacional', 'UTN', NULL, NULL),
(2311, NULL, 48, 27, 1, NULL, NULL, '2015-09-01 14:24:36', '2018-05-04 10:45:04', 'institution', 'Instituto Superior de Formación Docente N° 17', 'ISFD', NULL, NULL),
(2335, NULL, 51, 27, 1, NULL, NULL, '2015-09-01 14:24:36', '2016-01-12 14:16:01', 'institution', 'Instituto Nacional de Enfermedades Infecciosas Carlos G. Malbrán', 'IMAL', NULL, NULL),
(2342, NULL, 55, 27, 1, NULL, NULL, '2015-09-01 14:24:36', '2016-01-12 14:16:01', 'institution', 'Universidad Nacional del Nordeste', 'UNNE', NULL, NULL),
(2343, 2342, 55, 27, 1, NULL, NULL, '2015-09-01 14:24:36', '2016-01-12 14:16:02', 'institution', 'Dirección de Bibliotecas', '', NULL, NULL),
(2362, NULL, 57, 27, 1, NULL, NULL, '2015-09-01 14:24:36', '2016-01-12 14:16:02', 'institution', 'Universidad Nacional de Rosario', 'UNR', NULL, NULL),
(2363, 2362, 57, 27, 1, NULL, NULL, '2015-09-01 14:24:36', '2016-01-12 14:16:02', 'institution', 'Facultad de Humanidades y Artes', '', NULL, NULL),
(2364, 2363, 57, 27, 1, NULL, NULL, '2015-09-01 14:24:36', '2016-01-12 14:16:02', 'institution', 'Centro de Estudios Arqueológicos Regionales', NULL, NULL, NULL),
(2390, NULL, 57, 27, 1, NULL, NULL, '2015-09-01 14:24:36', '2016-01-12 14:16:02', 'institution', 'otro', 'otro', NULL, NULL),
(2418, 2405, 62, 27, 1, NULL, NULL, '2015-09-01 14:24:36', '2015-09-01 14:24:36', 'institution', 'Facultad de Ciencias Exactas y Tecnología', '', NULL, NULL),
(2461, 2457, 65, 27, 1, NULL, NULL, '2015-09-01 14:24:36', '2015-09-01 14:24:36', 'institution', 'Laboratorio de Bioestratigrafía', NULL, NULL, NULL),
(2551, 2546, 72, 27, 1, NULL, NULL, '2015-09-01 14:24:37', '2015-09-01 14:24:37', 'institution', 'Instituto de Industria', '', NULL, NULL),
(2607, 2606, 76, 27, 1, NULL, NULL, '2015-09-01 14:24:37', '2015-09-01 14:24:37', 'institution', 'Laboratorio de Forrajes. Laboratorio de Semillas', NULL, NULL, NULL),
(2697, 2673, NULL, 27, 1, NULL, NULL, '2015-09-01 14:24:37', '2015-09-01 14:24:37', 'institution', 'Facultad de Ciencias Agrarias', '', NULL, NULL),
(2705, 2673, NULL, 27, 1, NULL, NULL, '2015-09-01 14:24:37', '2015-09-01 14:24:37', 'institution', 'Facultad de Ciencias del Ambiente y la Salud', '', NULL, NULL),
(2710, NULL, NULL, 27, 1, NULL, NULL, '2015-09-01 14:24:37', '2015-09-01 14:24:39', 'institution', 'Universidad Nacional de Quilmes', 'UNQ', 'www.unq.edu.ar', 'Roque Sáenz Peña 352, Bernal'),
(2730, NULL, 48, 27, 1, 2, 1, '2015-09-01 14:24:38', '2015-09-08 14:37:23', 'institution', 'Universidad Nacional de La Plata', 'UNLP', 'http://www.unlp.edu.ar', NULL),
(2731, 2730, 48, 27, 1, NULL, NULL, '2015-09-01 14:24:38', '2016-10-07 08:19:58', 'institution', 'RECTORADO UNLP', 'RECTORADO', 'http://www.unlp.edu.ar/institucional', 'Avenida 7 N° 776'),
(2811, 2803, NULL, 27, 1, NULL, NULL, '2015-09-01 14:24:38', '2016-02-25 17:59:00', 'institution', 'AERONAUTICA', NULL, NULL, NULL),
(2899, 2888, NULL, 27, 1, NULL, NULL, '2015-09-01 14:24:38', '2016-02-25 18:02:44', 'institution', 'Area de Botánica', NULL, NULL, NULL),
(2974, 2931, NULL, 27, 1, NULL, NULL, '2015-09-01 14:24:38', '2015-09-01 14:24:38', 'institution', 'Laboratorio de Investigaciones del Sistema Inmune', NULL, NULL, 'LISIN'),
(2978, 2976, NULL, 27, 1, NULL, NULL, '2015-09-01 14:24:38', '2015-09-01 14:24:39', 'institution', 'Instituto de Integración Latinoamericana', NULL, NULL, 'IIL'),
(2991, 2986, NULL, 27, 1, NULL, NULL, '2015-09-01 14:24:38', '2016-02-25 18:03:48', 'institution', 'ARQUEOLOGIA', NULL, NULL, NULL),
(3012, 2986, NULL, 27, 1, NULL, NULL, '2015-09-01 14:24:38', '2015-09-01 14:24:39', 'institution', 'Instituto de Botánica Carlos Spegazzini', NULL, NULL, NULL),
(3103, NULL, 89, 27, 1, 3, 1, '2015-09-01 14:24:39', '2016-01-12 14:17:53', 'institution', 'Universidad Nacional del Noroeste de la Provincia de Buenos Aires', 'UNNOBA', 'http://www.unnoba.edu.ar/', NULL),
(3106, 3103, NULL, 27, 1, NULL, 1, '2015-09-01 14:24:39', '2016-01-12 14:16:02', 'institution', 'Sede Pergamino', '', NULL, NULL),
(3108, 3103, NULL, 27, 1, NULL, 1, '2015-09-01 14:24:39', '2016-01-12 14:16:02', 'institution', 'Biblioteca', '', NULL, NULL),
(3109, NULL, NULL, 27, 1, NULL, 1, '2015-09-01 14:24:39', '2016-01-12 14:16:02', 'institution', 'Universidad Nacional del Sur', 'UNS', 'http://www.uns.edu.ar/', 'Avenida Colón 80 - Bahía Blanca (8000FTN)'),
(3111, NULL, NULL, 27, 1, NULL, 1, '2015-09-01 14:24:39', '2016-01-12 14:16:02', 'institution', 'Universidad Abierta Interamericana', 'UAI', 'http://www.vaneduc.edu.ar/uai/', 'Chacabuco 90 - 1° Piso, Capital Federal'),
(3158, 3157, NULL, 27, 1, NULL, NULL, '2015-09-01 14:24:39', '2015-09-01 14:24:39', 'institution', 'Facultad de Ciencias Forestales', '', NULL, NULL),
(3297, NULL, 122, 28, 1, NULL, NULL, '2015-09-01 14:24:39', '2016-01-12 14:16:03', 'institution', 'otra', 'otra', NULL, NULL),
(3333, 3332, 122, 28, 1, NULL, 1, '2015-09-01 14:24:40', '2015-09-01 14:24:40', 'institution', 'Biblioteca Central Irmão José Otão', '', NULL, NULL),
(3355, 5047, 122, 28, 1, NULL, NULL, '2015-09-01 14:24:40', '2015-09-01 14:24:40', 'institution', 'Faculdade de Educação', '', NULL, NULL),
(3614, NULL, NULL, 28, 1, NULL, 1, '2015-09-01 14:24:41', '2016-01-12 14:16:03', 'institution', 'Universidade Estadual Paulista', 'UNES', 'www.unesp.br', NULL),
(3654, NULL, 137, 29, 1, 27, 1, '2015-09-01 14:24:41', '2018-08-24 08:34:21', 'institution', 'Fundación Universitaria De Ciencias de la Salud', 'FUCS', 'http://www.fucsalud.edu.co/', 'Carrera 19 Número 8A-32 Bogotá'),
(3655, 3654, 137, 29, 1, NULL, NULL, '2015-09-01 14:24:41', '2015-09-01 14:24:41', 'institution', 'BIBLIOTECA', '', NULL, NULL),
(3677, 3676, 137, 29, 1, NULL, NULL, '2015-09-01 14:24:41', '2015-09-01 14:24:41', 'institution', 'Biblioteca', '', NULL, NULL),
(3685, 3684, 137, 29, 1, NULL, 1, '2015-09-01 14:24:41', '2015-09-01 14:24:41', 'institution', 'Biblioteca', '', NULL, NULL),
(3688, NULL, 137, 29, 1, 33, 1, '2015-09-01 14:24:41', '2018-05-08 17:23:04', 'institution', 'Universidad Nacional de Colombia', 'UNAL', ' http://www.unal.edu.co', ' Carrera 45 No 26-85 - Edificio Uriel Gutiérrez'),
(3689, 3688, 137, 29, 1, NULL, NULL, '2015-09-01 14:24:41', '2016-01-12 14:16:01', 'institution', 'Biblioteca Central', '', NULL, NULL),
(3690, 3689, 137, 29, 1, NULL, NULL, '2015-09-01 14:24:41', '2016-01-12 14:16:01', 'institution', 'Conmutación Bibliográfica', NULL, NULL, NULL),
(3703, 3702, 138, 29, 1, NULL, NULL, '2015-09-01 14:24:41', '2015-09-01 14:24:41', 'institution', 'Biblioteca', '', NULL, NULL),
(3705, NULL, 138, 29, 1, NULL, 1, '2015-09-01 14:24:41', '2016-01-12 14:16:01', 'institution', 'Universidad del Valle', 'UNIV', ' http://www.univalle.edu.co', ' Ciudad Universitaria Melendez Calle 13 No 100-00'),
(3706, 3705, 138, 29, 1, NULL, NULL, '2015-09-01 14:24:41', '2015-09-01 14:24:41', 'institution', 'Biblioteca', '', NULL, NULL),
(3710, 3709, 138, 29, 1, NULL, NULL, '2015-09-01 14:24:41', '2015-09-01 14:24:41', 'institution', 'Biblioteca Ciencias de la Salud', '', NULL, NULL),
(3713, NULL, 139, 29, 1, NULL, 1, '2015-09-01 14:24:41', '2016-01-12 14:16:01', 'institution', 'Universidad Autónoma de Bucaramanga', 'UNAB', NULL, NULL),
(3714, NULL, 139, 29, 1, NULL, 1, '2015-09-01 14:24:41', '2016-01-12 14:16:01', 'institution', 'Escuela Colombiana de Ingeniería', 'ECI', NULL, NULL),
(3715, NULL, 139, 29, 1, NULL, 1, '2015-09-01 14:24:41', '2016-01-12 14:16:01', 'institution', 'Universidad Autónoma de Occidente', 'UAO', ' http://www.cuao.edu.co', ' Cali'),
(3716, 3715, 139, 29, 1, NULL, NULL, '2015-09-01 14:24:41', '2015-09-01 14:24:41', 'institution', 'Biblioteca', '', NULL, NULL),
(3718, NULL, 139, 29, 1, NULL, 1, '2015-09-01 14:24:41', '2016-01-12 14:16:01', 'institution', 'Universidad del Norte', 'UNIN', ' http://www.uninorte.edu.co', ' Km.5 Vía Puerto Colombia - Barranquilla'),
(3719, 3718, 139, 29, 1, NULL, NULL, '2015-09-01 14:24:41', '2016-01-12 14:16:01', 'institution', 'Biblioteca General', '', NULL, NULL),
(3720, 3719, 139, 29, 1, NULL, NULL, '2015-09-01 14:24:41', '2016-01-12 14:16:01', 'institution', 'Consulta Especializada', NULL, NULL, NULL),
(3731, NULL, NULL, 29, 1, NULL, 1, '2015-09-01 14:24:41', '2016-01-12 14:16:03', 'institution', 'Politécnico GranColombiano', 'PGC', 'http://www.poligran.edu.co/', 'PBX 3468800 - Calle 57 # 3-00 Este'),
(3732, 3731, NULL, 29, 1, NULL, 1, '2015-09-01 14:24:41', '2016-01-12 14:16:03', 'institution', 'Biblioteca', '', NULL, NULL),
(3733, NULL, NULL, 29, 1, NULL, 1, '2015-09-01 14:24:41', '2016-01-12 14:16:03', 'institution', 'Universidad Autónoma de Bucaramanga', 'UNAB', 'http://www.unab.edu.co', 'Calle 48 No 39 - 234 Bucaramanga'),
(3734, 3733, NULL, 29, 1, NULL, 1, '2015-09-01 14:24:41', '2016-01-12 14:16:03', 'institution', 'Biblioteca', '', NULL, NULL),
(3735, NULL, NULL, 29, 1, NULL, 1, '2015-09-01 14:24:41', '2016-01-12 14:16:03', 'institution', 'Universidad de Caldas', 'UCAL', 'http://www.ucaldas.edu.co/', 'Calle 65 No 26 - 10 - Manizales'),
(3736, 3735, NULL, 29, 1, NULL, 1, '2015-09-01 14:24:41', '2016-01-12 14:16:03', 'institution', 'Biblioteca', '', NULL, NULL),
(3737, NULL, NULL, 29, 1, 23, 1, '2015-09-01 14:24:41', '2017-09-13 09:39:39', 'institution', 'Universidad de La Salle', 'UNIS', ' http://www.lasalle.edu.co', ' Cra. 5 No. 59A-44 - Bogotá'),
(3738, 3737, NULL, 29, 1, NULL, 1, '2015-09-01 14:24:41', '2016-01-12 14:16:03', 'institution', 'Biblioteca', '', NULL, NULL),
(3740, NULL, NULL, 29, 1, NULL, 1, '2015-09-01 14:24:41', '2016-01-12 14:16:03', 'institution', 'Universidad de medellín', 'UDEM', 'http://www.udem.edu.co', 'Carrera 87 N° 30 - 65 - Medellín'),
(3741, 3740, NULL, 29, 1, NULL, 1, '2015-09-01 14:24:41', '2016-01-12 14:16:03', 'institution', 'Biblioteca', '', NULL, NULL),
(3742, NULL, NULL, 29, 1, NULL, 1, '2015-09-01 14:24:41', '2016-01-12 14:16:03', 'institution', 'Universidad Sergio Arboleda', 'USA', 'http://www.usergioarboleda.edu.co/', 'Calle 74 no. 14 - 14'),
(3743, 3742, NULL, 29, 1, NULL, 1, '2015-09-01 14:24:41', '2016-01-12 14:16:03', 'institution', 'Biblioteca', '', NULL, NULL),
(3744, NULL, NULL, 29, 1, NULL, 1, '2015-09-01 14:24:41', '2016-01-12 14:16:03', 'institution', 'Universidad del Magdalena', 'UNIM', 'http://www.unimagdalena.edu.co', 'Carrera 32 No 22 - 08'),
(3745, 3744, NULL, 29, 1, NULL, 1, '2015-09-01 14:24:41', '2016-01-12 14:16:03', 'institution', 'Biblioteca', '', NULL, NULL),
(3746, NULL, NULL, 29, 1, NULL, 1, '2015-09-01 14:24:41', '2016-01-12 14:16:03', 'institution', 'Universidad del Tolima', 'UT', 'http://www.ut.edu.co/', 'B. Santa Helena A.A. 546 - Ibagué'),
(3747, 3746, NULL, 29, 1, NULL, 1, '2015-09-01 14:24:41', '2016-01-12 14:16:03', 'institution', 'Biblioteca', '', NULL, NULL),
(3748, NULL, NULL, 29, 1, NULL, 1, '2015-09-01 14:24:41', '2016-01-12 14:16:03', 'institution', 'Universidad Distrital Francisco Jose de Caldas', 'UDIS', 'http://www.udistrital.edu.co', 'Cr 7 No 40 - 53'),
(3749, 3748, NULL, 29, 1, NULL, 1, '2015-09-01 14:24:41', '2016-01-12 14:16:03', 'institution', 'Biblioteca', '', NULL, NULL),
(3750, NULL, NULL, 29, 1, NULL, 1, '2015-09-01 14:24:41', '2016-01-12 14:16:03', 'institution', 'Universidad Pedagógica y Tecnológica de Colombia', 'UPTC', 'http://www.uptc.edu.co/', 'Avenida Central del Norte. - Tunja'),
(3751, 3750, NULL, 29, 1, NULL, 1, '2015-09-01 14:24:41', '2016-01-12 14:16:03', 'institution', 'Biblioteca', '', NULL, NULL),
(3752, NULL, NULL, 29, 1, NULL, 1, '2015-09-01 14:24:41', '2016-01-12 14:16:03', 'institution', 'Universidad Santiago de Cali', 'USC', 'http://www.usc.edu.co/', 'Calle 5 No. 62-00 Cali Valle del Cauca '),
(3753, 3752, NULL, 29, 1, NULL, 1, '2015-09-01 14:24:41', '2016-01-12 14:16:03', 'institution', 'Biblioteca', '', NULL, NULL),
(3754, NULL, 137, 29, 1, 5, 1, '2015-09-01 14:24:41', '2016-02-01 12:59:07', 'institution', 'Universidad Santo Tomás (Bogota)', 'USTA', 'http://www.usta.edu.co', 'carrera 9 No. 51 - 11'),
(3755, 3754, NULL, 29, 1, NULL, 1, '2015-09-01 14:24:41', '2016-01-12 14:16:03', 'institution', 'Biblioteca', '', NULL, NULL),
(3756, NULL, 220, 29, 1, NULL, 1, '2015-09-01 14:24:41', '2016-04-05 13:40:33', 'institution', 'Universidad Santo Tomás (Bucaramanga)', 'USTA', 'http://www.ustabuca.edu.co', 'carrera 18 No 9 - 27'),
(3757, 3756, NULL, 29, 1, NULL, 1, '2015-09-01 14:24:41', '2016-01-12 14:16:03', 'institution', 'Biblioteca', '', NULL, NULL),
(3758, NULL, NULL, 29, 1, NULL, 1, '2015-09-01 14:24:41', '2016-01-12 14:16:03', 'institution', 'Corporación Universitaria UNITEC', 'UNIT', 'http://www.unitec.edu.co', 'Calle 76No 12 - 58 - PBX 5939393 - Bogotá'),
(3759, 3758, NULL, 29, 1, NULL, 1, '2015-09-01 14:24:41', '2016-01-12 14:16:03', 'institution', 'Biblioteca', '', NULL, NULL),
(3760, NULL, 245, 29, 1, 8, 1, '2015-09-01 14:24:41', '2016-08-17 12:42:52', 'institution', 'Universidad de Manizales', 'UMAN', 'http://www.umanizales.edu.co/', 'Cra. 9a No 19-03 Conmutador 8 84 14 50 Manizales'),
(3761, 3760, NULL, 29, 1, NULL, 1, '2015-09-01 14:24:41', '2016-01-12 14:16:03', 'institution', 'Biblioteca', '', NULL, NULL),
(3762, NULL, NULL, 29, 1, NULL, 1, '2015-09-01 14:24:41', '2016-01-12 14:16:03', 'institution', 'Escuela de Ingeniería de Antioquia', 'EIA', 'http://www.eia.edu.co', 'Sede Palmas (Pregrado): dirección: km 2 variante al aeropuerto José María Córdova, Envigado'),
(3763, 3762, NULL, 29, 1, NULL, 1, '2015-09-01 14:24:41', '2016-01-12 14:16:03', 'institution', 'Biblioteca', '', NULL, NULL),
(3769, NULL, NULL, 29, 1, NULL, 1, '2015-09-01 14:24:41', '2016-10-07 08:09:53', 'institution', ' Iberoamericana Institución Universitaria', 'IIU', 'www.iberoamericana.edu.co', 'BOGOTÁ: SEDE ADMINISTRATIVA CALLE 67 Nº 5-27'),
(3784, NULL, NULL, 29, 1, 34, 1, '2015-09-01 14:24:41', '2018-08-21 13:25:44', 'institution', 'Universidad de América', 'UAMERICA', 'http://www.uamerica.edu.co/', 'Avda Circunvalar No. 20-53. '),
(3818, NULL, 145, 30, 1, NULL, NULL, '2015-09-01 14:24:42', '2016-01-12 14:16:01', 'institution', 'OTRA', '', NULL, NULL),
(4035, NULL, NULL, 30, 1, NULL, 1, '2015-09-01 14:24:43', '2016-01-12 14:16:03', 'institution', 'Instituto de Estudios Avanzados de las Americas', 'OAS', 'http://www.educoas.org', '17th Street & Constitution Ave., N.W., Washington, D.C. 20006, USA'),
(4036, NULL, NULL, 30, 1, NULL, 1, '2015-09-01 14:24:43', '2016-01-12 14:16:03', 'institution', 'University of South Florida', 'USF', 'http://www.usf.edu', 'USF Tampa: 4202 E. Fowler Ave Tampa, Fl 33620'),
(4074, 4073, 154, 31, 1, NULL, NULL, '2015-09-01 14:24:43', '2015-09-01 14:24:43', 'institution', 'Biblioteca Pedro Grases', '', NULL, NULL),
(4080, NULL, NULL, 31, 1, NULL, 1, '2015-09-01 14:24:43', '2016-01-12 14:16:03', 'institution', 'Universidad de los Andes', 'ULA', 'http://www.serbi.ula.ve/celsius/', 'Av. Tulio Febres Cordero, Edif. Administrativo, Piso 5. Mérida - Venezuela.'),
(4082, NULL, NULL, 31, 1, NULL, 1, '2015-09-01 14:24:43', '2016-01-12 14:16:03', 'institution', 'Universidad Central', 'UCV', 'http://www.ucv.ve/', 'Ciudad Universitaria, Los Chaguaramos Caracas, Venezuela Apartado postal 1050'),
(4083, NULL, NULL, 31, 1, NULL, 1, '2015-09-01 14:24:43', '2016-01-12 14:16:03', 'institution', 'Universidad de Carabobo', 'UC', 'http://www.uc.edu.ve', 'Sede del Rectorado - Av. Bolivar Norte, Valencia, Edo. Carabobo. '),
(4085, NULL, NULL, 31, 1, NULL, 1, '2015-09-01 14:24:43', '2016-01-12 14:16:03', 'institution', 'Universidad Panamericana del Puerto', 'UNIP', 'http://www.unipap.edu.ve', 'Calle de Anzoátegui, Casco Histórico de Puerto Cabello, Estado Carabobo'),
(4105, NULL, NULL, 32, 1, NULL, 1, '2015-09-01 14:24:43', '2015-09-01 14:24:43', 'institution', 'Universidad Privada Boliviana', 'UPD', 'www.upb.edu', '-'),
(4107, NULL, NULL, 32, 1, NULL, 1, '2015-09-01 14:24:43', '2016-01-12 14:16:03', 'institution', 'Universidad Católica Boliviana San Pablo - Unidad Académica Santa Cruz', 'UCBS', 'http://www.ucbscz.edu.bo', 'Calle España #283, Santa Cruz, Bolivia'),
(4108, NULL, NULL, 32, 1, NULL, 1, '2015-09-01 14:24:43', '2016-01-12 14:16:03', 'institution', 'Universidad Autónoma Gabriel René Moreno', 'UAGR', 'http://www.uagrm.edu.bo/', ' Plaza 24 de Septiembre, Santa Cruz de la Sierra, Bolivia'),
(4109, 4108, NULL, 32, 1, NULL, 1, '2015-09-01 14:24:43', '2016-01-12 14:16:03', 'institution', 'Facultad de Ciencias Exactas y Tecnología', '', 'http://www.uagrm.edu.bo/index_princ.php?opcion=4002&fac=05&op=1', NULL),
(4110, NULL, NULL, 32, 1, NULL, 1, '2015-09-01 14:24:43', '2016-01-12 14:16:03', 'institution', 'Universidad Mayor de San Andrés', 'UMSA', 'http://www.umsa.bo', 'Monoblock Central Villazón Av. Nº 1995, La Paz, Bolivia'),
(4111, 4110, NULL, 32, 1, NULL, 1, '2015-09-01 14:24:43', '2016-01-12 14:16:03', 'institution', 'Facultad de Ciencias Puras y Naturales', '', 'http://fcpn.umsa.bo/', 'v. Villazón 1995 Monoblock Central (Primer patio)'),
(4112, NULL, NULL, 32, 1, NULL, 1, '2015-09-01 14:24:43', '2016-01-12 14:16:03', 'institution', 'Universidad de Aquino Bolivia', 'UDAB', 'http://www.udabol.edu.bo', 'Calle Capitan Ravelo Pasaje Isaac Eduardo #2643 Ed. UDABOL, La Paz, Bolivia'),
(4113, NULL, NULL, 32, 1, NULL, 1, '2015-09-01 14:24:43', '2016-01-12 14:16:03', 'institution', 'Fundación para el Desarrollo de la Ecología', 'FUND', 'www.fundeco.org.bo', 'Campus Universitario - Calle 27, Cota-Cota Casilla de Correo:  3-12376 SM , La Paz - Bolivia'),
(4114, NULL, NULL, 32, 1, NULL, 1, '2015-09-01 14:24:43', '2016-01-12 14:16:03', 'institution', 'Universidad San Francisco Xavier de Chuquisaca ', 'USFX', 'http://www.usfx.info', 'Casilla Postal: 232'),
(4115, NULL, NULL, 32, 1, NULL, 1, '2015-09-01 14:24:43', '2016-01-12 14:16:03', 'institution', 'Universidad Privada Santa Cruz de la Sierra', 'UPSA', 'http://www.upsa.edu.bo/', 'Campus Universitario: Av. Paraguay y 4to Anillo, Santa Cruz de la Sierra, Bolivia'),
(4123, NULL, 160, 33, 1, NULL, 1, '2015-09-01 14:24:43', '2016-01-12 14:16:01', 'institution', 'Universidad Técnica Particular de Loja', 'UTPL', 'http://www.utpl.edu.ec/ http://www.utpl.edu.ec', ' San Cayetano Alto - Loja Ecuador . Apartado postal: 11-01-608'),
(4129, NULL, NULL, 33, 1, NULL, 1, '2015-09-01 14:24:43', '2016-01-12 14:16:03', 'institution', 'Universidad Internacional del Ecuador', 'UIDE', 'http://www.internacional.edu.ec/', NULL),
(4130, 4129, NULL, 33, 1, NULL, 1, '2015-09-01 14:24:43', '2016-01-12 14:16:03', 'institution', 'Biblioteca', '', NULL, NULL),
(4131, NULL, NULL, 33, 1, NULL, 1, '2015-09-01 14:24:43', '2016-01-12 14:16:03', 'institution', 'Escuela Politécnica del Ejército', 'ESPE', 'http://www.espe.edu.ec', 'Av. El Progreso s/n - Ecuador'),
(4132, NULL, NULL, 33, 1, NULL, 1, '2015-09-01 14:24:43', '2016-01-12 14:16:03', 'institution', 'Universidad Tecnológica América', 'UNIT', 'http://www.unita.edu.ec', NULL),
(4133, NULL, NULL, 33, 1, NULL, 1, '2015-09-01 14:24:43', '2016-01-12 14:16:03', 'institution', 'Universidad de Guayaquil', 'UG', 'http://www.ug.edu.ec', 'Ciudadela Universitaria, Intersección de la Av. Kennedy y la Av. Delta'),
(4134, NULL, NULL, 33, 1, NULL, 1, '2015-09-01 14:24:43', '2016-01-12 14:16:03', 'institution', 'Instituto Superior Tecnológico de Educación Virtual', 'ISEV', 'http://www.geocities.com/isev2005', 'Mercadillo No. 300 entre Versalles y 10 de Agosto Edificio Solandes, Departamento 24'),
(4135, NULL, NULL, 33, 1, NULL, 1, '2015-09-01 14:24:43', '2016-01-12 14:16:03', 'institution', 'Charles Darwin Research Station', 'CDRS', 'http://www.darwinfoundation.org', 'Puerto Ayora, Isla Santa Cruz en las Islas Galapagos'),
(4136, NULL, NULL, 33, 1, NULL, 1, '2015-09-01 14:24:43', '2016-01-12 14:16:03', 'institution', 'Universidad Cetral del Ecuador', 'UCE', 'http://www.uce.edu.ec', 'Av. América y Pérez Guerrero – Ciudadela Universitaria Apartado 1456. Quito, Ecuador'),
(4138, NULL, 161, 34, 1, NULL, 1, '2015-09-01 14:24:43', '2016-01-12 14:16:01', 'institution', 'Campus de Morelos', 'More', NULL, NULL),
(4139, NULL, 161, 34, 1, NULL, 1, '2015-09-01 14:24:43', '2016-01-12 14:16:01', 'institution', 'Instituto Tecnologico y de Estudios Superiores de Monterrey', 'ITES', NULL, NULL),
(4143, NULL, 161, 34, 1, NULL, 1, '2015-09-01 14:24:43', '2016-01-12 14:16:01', 'institution', 'CENTRO DE INVESTIGACIONES BIOLÓGICAS DEL NOROESTE', 'CIBN', 'http://www.cibnor.mx', 'Mar Bermejo No. 195, Col. Playa Palo de Santa Rita, AP 128 La Paz, Baja California Sur, 23090'),
(4144, 4143, 161, 34, 1, NULL, NULL, '2015-09-01 14:24:43', '2016-01-12 14:16:01', 'institution', 'Biblioteca', '', NULL, NULL),
(4150, NULL, 162, 34, 1, 19, 1, '2015-09-01 14:24:43', '2017-02-16 08:18:29', 'institution', 'Instituto Nacional de Astrofísica, Óptica y Electrónica', 'INAO', ' http://www.inaoep.mx/', 'Luis Enrique Erro N0. 1. Tonantzintla, Puebla Mex. Luis Enrique Erro # 1, Tonantzintla, Puebla'),
(4154, 5993, 162, 34, 1, NULL, NULL, '2015-09-01 14:24:43', '2016-02-25 16:12:23', 'institution', 'CENTRO DE INFORMACION INAOE', NULL, NULL, NULL),
(4167, NULL, NULL, 34, 1, NULL, 1, '2015-09-01 14:24:43', '2016-01-12 14:16:03', 'institution', 'Centro de Investigaciones Biológicas del Noroeste', 'CIBN', 'http://www.cibnor.mx/', NULL),
(4168, NULL, NULL, 34, 1, NULL, 1, '2015-09-01 14:24:43', '2016-01-12 14:16:03', 'institution', 'Instituto Tecnológico de Aguascalientes', 'ITCH', 'http://www.itch.edu.mx/', 'Ave. Tecnológico #2909'),
(4169, NULL, NULL, 34, 1, NULL, 1, '2015-09-01 14:24:43', '2016-01-12 14:16:03', 'institution', 'Instituto Tecnológico de Chihuahua', 'ITCH', 'http://www.itch.edu.mx/', 'Ave. Tecnológico #2909 '),
(4170, NULL, NULL, 34, 1, NULL, 1, '2015-09-01 14:24:43', '2016-01-12 14:16:03', 'institution', 'Universidad de Guadalajara', 'UDG', 'http://www.udg.mx/', NULL),
(4171, NULL, NULL, 34, 1, NULL, 1, '2015-09-01 14:24:43', '2016-01-12 14:16:03', 'institution', 'Universidad de las Américas Puebla', 'UDLA', 'http://info.pue.udlap.mx/', 'Sta. Catarina Martir. Cholula, Puebla. C.P. 72820.'),
(4221, NULL, NULL, 36, 1, NULL, 1, '2015-09-01 14:24:43', '2016-01-12 14:16:03', 'institution', ' Universidad Estatal a Distancia', 'UNED', 'http://www.uned.ac.cr/', NULL),
(4223, NULL, NULL, 37, 1, NULL, 1, '2015-09-01 14:24:43', '2016-01-12 14:16:03', 'institution', 'Universidad Abierta para Adultos', 'UAPA', 'http://www.uapa.edu.do/uapasitev3/', NULL),
(4224, NULL, NULL, 37, 1, NULL, 1, '2015-09-01 14:24:43', '2016-01-12 14:16:03', 'institution', 'Fundacion Global Democracia y Desarrollo', 'FUNG', 'http://www.funglode.org/FunglodeApp/Index.aspx', NULL),
(4225, NULL, 172, 38, 1, NULL, NULL, '2015-09-01 14:24:43', '2016-01-12 14:16:01', 'institution', 'OTRA', '', NULL, NULL),
(4241, NULL, 174, 38, 1, NULL, NULL, '2015-09-01 14:24:43', '2016-01-12 14:16:01', 'institution', 'UNIVERSIDAD REY JUAN CARLOS', 'URJC', 'www.urjc.es', 'C/ Tulipán, s/n  28933 Móstoles (madrid)'),
(4242, 4241, 174, 38, 1, NULL, NULL, '2015-09-01 14:24:43', '2016-01-12 14:16:01', 'institution', 'Biblioteca', '', NULL, NULL),
(4263, NULL, 174, 38, 1, NULL, 1, '2015-09-01 14:24:44', '2016-04-21 10:56:01', 'institution', 'Universidad Carlos III de Madrid', 'UC3M', 'http://www.uc3m.es/', 'Avda. Universidad 30. Leganés; 28911 Madrid'),
(4277, NULL, 176, 38, 1, NULL, 1, '2015-09-01 14:24:44', '2016-01-12 14:16:01', 'institution', 'Universidad Politécnica de Valencia', 'UPV', NULL, NULL),
(4278, 4277, 176, 38, 1, NULL, NULL, '2015-09-01 14:24:44', '2016-01-12 14:16:01', 'institution', 'BIBLIOTECA CENTRAL', '', NULL, NULL),
(4338, NULL, 188, 38, 1, NULL, NULL, '2015-09-01 14:24:44', '2015-09-01 14:24:44', 'institution', 'UNIVERSIDAD PABLO DE OLAVIDE', 'UPO', NULL, 'http://www.upo.es/'),
(4386, NULL, NULL, 38, 1, NULL, 1, '2015-09-01 14:24:44', '2016-01-12 14:16:03', 'institution', 'Universidad de Granada', 'UGR', 'http://www.ugr.es/', 'C/ Cuesta del Hospicio s/n C.P. 18071'),
(4391, NULL, NULL, 38, 1, NULL, NULL, '2015-09-01 14:24:44', '2015-09-01 14:24:44', 'institution', 'Universidad de La Laguna', 'ULL', NULL, NULL),
(4413, 4394, NULL, 38, 1, NULL, NULL, '2015-09-01 14:24:44', '2015-09-01 14:24:44', 'institution', 'Instituto de Ciencias de la Construcción E. Torroja', 'M-IETCC', NULL, NULL),
(4414, 4413, NULL, 38, 1, NULL, NULL, '2015-09-01 14:24:44', '2015-09-01 14:24:44', 'institution', 'Biblioteca', NULL, NULL, NULL),
(4446, NULL, NULL, 39, 1, NULL, 1, '2015-09-01 14:24:44', '2016-01-12 14:16:03', 'institution', 'Universidad Pedagógica Nacional Francisco Morazán', 'UPNF', 'http://www.upnfm.edu.hn/', NULL),
(4447, NULL, NULL, 40, 1, NULL, 1, '2015-09-01 14:24:44', '2016-01-12 14:16:03', 'institution', 'Universidad Centroamericana', 'UCA', 'http://www.uca.edu.ni/', 'Avenida Universitaria. Managua. Apartado Postal 69. '),
(4451, NULL, NULL, 41, 1, NULL, 1, '2015-09-01 14:24:44', '2016-01-12 14:16:03', 'institution', 'Universidad Católica Santa Marí­a la Antigua', 'USMA', 'http://www.usma.ac.pa/', NULL),
(4454, NULL, NULL, 41, 1, NULL, 1, '2015-09-01 14:24:44', '2016-01-12 14:16:03', 'institution', 'Universidad Tecnológica de Panamá', 'UTP', 'http://www.utp.ac.pa/', 'Apartado Postal: 0819-07289 Panamá, Rep. de Panamá'),
(4457, NULL, NULL, 42, 1, NULL, 1, '2015-09-01 14:24:44', '2016-01-12 14:16:03', 'institution', 'Universidad Católica Nuestra Señora de la Asunción - Sede Regional Asunción', 'UC', 'http://www.uc.edu.py/', 'Independencia Nacional 176 y Comuneros C.C 1718. AsuciÃ³n'),
(4465, NULL, NULL, 43, 1, NULL, 1, '2015-09-01 14:24:45', '2016-01-12 14:16:03', 'institution', 'Pontificia Universidad Católica del Perú', 'PUCP', 'http://www.pucp.edu.pe/', 'Av. Universitaria 1801, San Miguel, Lima 32, Perú'),
(4483, 5208, 203, 44, 1, NULL, NULL, '2015-09-01 14:24:45', '2015-09-01 14:24:45', 'institution', 'Biblioteca', '', NULL, NULL),
(4488, 5208, 203, 44, 1, NULL, 1, '2015-09-01 14:24:45', '2015-09-01 14:24:45', 'institution', 'Facultad de Medicina', 'CENDIM', NULL, NULL),
(4492, 4488, 203, 44, 1, NULL, NULL, '2015-09-01 14:24:45', '2016-02-26 12:33:35', 'institution', 'BINAME - CENDIM (Hospital de clínicas)', NULL, NULL, NULL),
(4496, 5208, 203, 44, 1, NULL, NULL, '2015-09-01 14:24:45', '2015-09-01 14:24:45', 'institution', 'Facultad de Ingeniería', 'FI', NULL, NULL),
(4532, NULL, 5, 5, 1, NULL, NULL, '2015-09-10 10:37:41', '2015-09-10 10:38:40', 'institution', 'Biblioteca della Soprintendenza per i beni archeologici del Piemonte', 'BSBAP', 'http://archeo.piemonte.beniculturali.it/index.php/en/biblioteca', NULL),
(4535, 1323, NULL, 7, 1, NULL, NULL, '2015-09-16 07:55:10', '2015-09-16 07:55:10', 'institution', 'Tieraerztliche Hochschule Hannover', 'THHO', NULL, NULL),
(4537, NULL, 13, 5, 1, NULL, NULL, '2015-09-17 09:58:20', '2015-09-17 10:00:05', 'institution', 'Biblioteca del Museo Archeologico Nazionale di Napoli', 'BMANN', NULL, NULL),
(4662, 4661, NULL, 29, 1, NULL, NULL, '2016-01-12 14:16:01', '2016-01-12 14:16:01', 'institution', 'Sala Virtual', NULL, NULL, NULL),
(4663, NULL, NULL, 29, 1, 17, 1, '2016-01-12 14:16:01', '2017-02-15 08:07:25', 'institution', 'Universidad CES', 'UCES', NULL, NULL),
(4714, NULL, NULL, 36, 1, NULL, 1, '2016-01-12 14:16:01', '2016-01-12 14:16:03', 'institution', 'Universidad de Costa Rica', 'COSR', NULL, NULL),
(4724, NULL, NULL, 35, 1, NULL, NULL, '2016-01-12 14:16:01', '2016-01-12 14:16:03', 'institution', 'Universidad de Talca', 'UTALCA', NULL, NULL),
(4731, NULL, NULL, 33, 1, NULL, NULL, '2016-01-12 14:16:01', '2016-01-12 14:16:03', 'institution', 'Escuela Politécnica Nacional', 'EPN', NULL, NULL),
(4732, 4731, NULL, 33, 1, NULL, NULL, '2016-01-12 14:16:01', '2016-01-12 14:16:03', 'institution', 'Biblioteca Central', NULL, NULL, NULL),
(4733, 4732, NULL, 33, 1, NULL, NULL, '2016-01-12 14:16:01', '2016-01-12 14:16:03', 'institution', 'Departamento Tecnológico', NULL, NULL, NULL),
(4964, 2730, NULL, 27, 1, NULL, NULL, '2016-01-12 14:16:02', '2016-10-07 08:10:47', 'institution', 'Biblioteca Central de la UNLP', NULL, 'http://www.biblio.unlp.edu.ar', NULL),
(4980, 2731, 48, 27, 1, 2, 1, '2016-01-12 14:16:02', '2016-10-07 08:20:43', 'institution', 'PREBI-SEDICI', 'PREBI-SEDICI', 'http://prebi.unlp.edu.ar/', '49 y 115 s/n'),
(5047, NULL, 133, 28, 1, NULL, NULL, '2016-01-12 14:16:03', '2016-10-05 10:00:25', 'institution', 'Universidade Estadual de Campinas', 'UNICAMP', 'http://www.unicamp.br/unicamp/', NULL),
(5135, 5134, NULL, 28, 1, NULL, 1, '2016-01-12 14:16:03', '2016-01-12 14:16:03', 'institution', 'CPTEC', NULL, NULL, 'Rodovia Presidente Dutra, km 40'),
(5208, NULL, NULL, 44, 1, NULL, 1, '2016-01-12 14:16:03', '2016-02-26 12:31:19', 'institution', 'Universidad de la República del Uruguay', 'UDELAR', 'http://www.universidad.edu.uy/', '18 de Julio 1824. Motevideo'),
(5215, NULL, 140, 29, 1, 6, NULL, '2016-02-10 08:32:23', '2016-02-10 08:32:23', 'institution', 'Universidad Simón Bolivar', 'USB', 'http://www.unisimon.edu.co/', 'Sede Principal Carrera 59 No. 59-92 PBX +57 (5) 344 4333. Fax : +57 (5) 3682892'),
(5300, NULL, 137, 29, 1, 10, NULL, '2016-09-09 13:30:25', '2016-09-09 13:36:52', 'institution', 'Universitaria Agustiniana', 'Uniagustiniana', 'http://www.uniagustiniana.edu.co/index.php', 'Calle 147 No. 89-39'),
(5993, 4150, 162, 34, 1, NULL, 1, '2016-10-04 13:44:49', '2016-10-11 07:45:51', 'institution', 'Biblioteca', NULL, NULL, NULL),
(6259, 5047, 133, 28, 1, 11, NULL, '2016-10-05 09:54:16', '2016-10-05 09:54:16', 'institution', 'Biblioteca da Área de Engenharia e Arquitetura', 'BAE', 'http://www.bae.unicamp.br/portal2/', NULL),
(7172, NULL, 137, 29, 1, 18, NULL, '2017-02-16 09:14:15', '2017-02-16 09:14:15', 'institution', 'Centro de Apoyo a la Investigación Económica', 'caie', 'http://www.banrep.gov.co', NULL),
(7205, NULL, 137, 29, 1, 21, NULL, '2017-04-24 08:31:17', '2017-04-24 08:32:54', 'institution', 'Escuela de Ingeniería Julio Garavito', 'EIJG', 'http://www.escuelaing.edu.co/', 'AK.45 No.205-59 (Autopista Norte)'),
(8748, NULL, 305, 29, 1, 31, NULL, '2018-07-31 14:26:04', '2018-07-31 14:26:04', 'institution', 'Universidad Nacional de Colombia. Sede Amazonia.', 'unalama', 'http://amazonia.unal.edu.co', NULL),
(8749, NULL, 324, 29, 1, 32, NULL, '2018-07-31 14:28:45', '2018-07-31 14:28:45', 'institution', 'Universidad Nacional de Colombia. Sede Caribe.', 'unalcar', 'http://caribe.unal.edu.co/', NULL),
(8750, NULL, 245, 29, 1, 29, NULL, '2018-07-31 14:30:01', '2018-07-31 14:30:01', 'institution', 'Universidad Nacional de Colombia. Sede Manizales.', 'unalman', 'http://manizales.unal.edu.co', NULL),
(8751, NULL, 141, 29, 1, 28, NULL, '2018-07-31 14:31:35', '2018-07-31 14:31:35', 'institution', 'Universidad Nacional de Colombia. Sede Medellin.', 'unalmed', 'http://medellin.unal.edu.co', NULL),
(8752, NULL, 237, 29, 1, 30, NULL, '2018-07-31 14:33:34', '2018-07-31 14:33:34', 'institution', 'Universidad Nacional de Colombia. Sede Orinoquia.', 'unalori', 'http://orinoquia.unal.edu.co/', NULL),
(8753, NULL, 340, 29, 1, 33, NULL, '2018-07-31 14:38:21', '2018-07-31 14:38:21', 'institution', 'Universidad Nacional de Colombia. Sede Palmira.', 'unalpal', 'http://www.palmira.unal.edu.co/', NULL),
(9020, NULL, 137, 29, 1, 36, NULL, '2018-08-30 12:42:08', '2018-08-30 12:47:50', 'institution', 'Universidad Central', 'ucentral', 'http://ucentral.edu.co', NULL),
(9022, NULL, NULL, 29, 1, NULL, NULL, '2018-09-06 10:45:00', '2018-09-06 10:45:00', 'institution', 'International Relations and Cooperation Office', 'ITM', 'http://www.itm.edu.co/en/dependencias/direccion-de-cooperacion-y-relaciones-internacionales-5/', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `RefreshToken`
--

CREATE TABLE `RefreshToken` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `expires_at` int(11) DEFAULT NULL,
  `scope` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `request`
--

CREATE TABLE `request` (
  `id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `creator_id` int(11) NOT NULL,
  `librarian_id` int(11) DEFAULT NULL,
  `instance_id` int(11) NOT NULL,
  `operator_id` int(11) DEFAULT NULL,
  `order_id` int(11) NOT NULL,
  `previous_request_id` int(11) DEFAULT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `comments` longtext COLLATE utf8_unicode_ci,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL,
  `deletedAt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sirequests_files`
--

CREATE TABLE `sirequests_files` (
  `event_id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `state`
--

CREATE TABLE `state` (
  `id` int(11) NOT NULL,
  `remote_event_id` int(11) DEFAULT NULL,
  `instance_id` int(11) NOT NULL,
  `previous_id` int(11) DEFAULT NULL,
  `request_id` int(11) NOT NULL,
  `operator_id` int(11) DEFAULT NULL,
  `current` tinyint(1) NOT NULL,
  `searchPending` tinyint(1) NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL,
  `deletedAt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `template`
--

CREATE TABLE `template` (
  `id` int(11) NOT NULL,
  `instance_id` int(11) DEFAULT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `text` longtext COLLATE utf8_unicode_ci NOT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `template`
--

INSERT INTO `template` (`id`, `instance_id`, `code`, `text`, `createdAt`, `updatedAt`, `type`, `enabled`, `title`) VALUES
(1, NULL, 'new_message', 'You have a new message from {{ user }}.', '2015-09-01 14:23:20', '2015-09-01 14:23:20', 'notification', NULL, NULL),
(2, NULL, 'new_user', 'There\'s a new registered user called {{ user }}.', '2015-09-01 14:23:20', '2015-09-01 14:23:20', 'notification', NULL, NULL),
(3, NULL, 'order_event', 'El pedido {{ request.order.id }} cambio al estado \"{{ event }}\".', '2015-09-01 14:23:20', '2015-09-01 14:23:20', 'notification', NULL, NULL),
(39, 1, 'no_hive', '<p>Estimado/a {{ user.full_name }}, el material <span style=\"font-style: italic;\">{{ order.material_data.title }}</span>\r\n fue localizado y solicitado por fuera de la red de instituciones \r\nmiembro del Consorcio Iberoamericano para la Educación en Ciencia y \r\nTecnología, por lo que es posible que se demore más de lo que podemos \r\nesperar.</p><p><br></p><p>Un cordial saludo.</p><p><br></p>', '2017-01-10 11:29:26', '2017-01-10 11:29:26', 'mail', 1, 'Solicitud por fuera de la red'),
(40, 1, 'order_cancel', '<p><span dir=\"ltr\" style=\"text-align: left;\" id=\":a9.co\" class=\"tL8wMe EMoHub\">Estimado Usuario {{ user.full_name }}:\r\n                Me dirijo a ud. para informarle que lamentablemente no \r\nhemos ubicado su solicitud de: {{ order.material_data.title }}.</span></p><p><span dir=\"ltr\" style=\"text-align: left;\" id=\":a9.co\" class=\"tL8wMe EMoHub\"><br></span></p><p><span dir=\"ltr\" style=\"text-align: left;\" id=\":a9.co\" class=\"tL8wMe EMoHub\">Esperamos poder ayudarlo en otra oportunidad. Hasta pronto.</span></p>', '2017-01-10 11:31:00', '2017-01-10 11:31:00', 'mail', 1, 'Cancelación de solicitud bibliográfica'),
(41, 1, 'order_download', '<p>Estimado {{ user.full_name }}:\r\nLe informo a Ud. que su solicitud: \" {{ order.material_data.title }} \" ha sido recibido.</p><p><br></p><p>Usted puede encontrarlo en su SITIO DE USUARIOS en la página web de&nbsp;<span style=\"color: rgb(51, 51, 51);\">{{ instance.name }}:&nbsp;</span>{{ instance.website }} Recuerde que puede bajar el artículo sólo UNA vez.</p><p><br></p><p>Si tiene inconvenientes por favor contáctenos<br></p><p>\r\nAtte. Personal<br></p>', '2017-01-10 11:32:24', '2017-01-10 11:32:24', 'mail', 1, 'Documento listo para descargar'),
(42, 1, 'order_printed', '<p>Estimado/a {{ user.full_name }}: Le informo a Ud. que su solicitud: \" {{ order.material_data.title }} \" ha sido recibido.<br></p><p><br>Usted puede retirarlo de las oficinas durante el horario de atención.<br>Si tiene inconvenientes por favor contáctenos.</p><p><br></p><p>Atte. Personal <br></p>', '2017-01-10 11:34:11', '2017-01-10 11:34:11', 'mail', 1, 'Documento listo para retirar'),
(43, 1, 'user_welcome_provision', '<p>Estimado {{ user.full_name }}:\r\nPor la presente cumplo en informarle que ha sido dado de alta como Usuario de&nbsp;<span style=\"color: rgb(51, 51, 51);\">{{ instance.name }}</span>.\r\nA partir de este momento podrá realizar sus solicitudes de \"Provisión\" \r\ndel material bibliográfico que Usted necesite, directamente a través de \r\nnuestra página web: {{ instance.website }} Ingrese al Sitio de Usuario \r\nclickeando este ítem en la parte superior de la pantalla de su \r\nexplorador, después de hacerlo deberá completar la siguiente \r\ninformación: Su login es: {{ user.username }} Su clave es la que ingresó\r\n al registrarse.\r\nA partir de este momento se encuentra en su \"sitio personal\" de&nbsp;<span style=\"color: rgb(51, 51, 51);\">{{ instance.name }}</span>.\r\n Para cambiar su clave&nbsp;seleccione Cambiar Clave de acceso y modifique \r\nlos datos, según su comodidad.\r\nEntre las ventajas de este sistema podemos mencionar que el usuario \r\ncuenta con la información en tiempo real de todo lo que ocurre con sus \r\nsolicitudes en curso y un registro histórico relacionado con todas sus \r\nsolicitudes a lo largo del tiempo de utilización del servicio. Por medio\r\n de la Opción Pedido Nuevo, usted puede llenar el formulario para \r\nsolicitar un Pedido.</p><p><br></p><p>Atentamente,</p><p><br></p><p>Personal</p><p><br></p>', '2017-01-10 11:35:35', '2017-01-10 11:35:35', 'mail', 1, 'Bienvenida Nuevo Usuario - Provisión (Exterior)'),
(44, 1, 'user_welcome', '<p><br></p><p><span style=\"color: rgb(51, 51, 51);\">Estimado {{ user.full_name }}: Por la presente cumplo en informarle que ha sido dado de alta como Usuario de&nbsp;</span><span style=\"color: rgb(51, 51, 51);\">{{ instance.name }}</span><span style=\"color: rgb(51, 51, 51);\">.\r\n Desde ahora podrá realizar sus solicitudes de \"Búsqueda\" del material \r\nbibliográfico que Usted necesite, directamente a través de nuestra \r\npágina web: {{ instance.website }} Ingrese al Sitio de Usuario \r\ncliqueando este ítem en la parte superior de la pantalla de su \r\nexplorador, después de hacerlo deberá completar la siguiente \r\ninformación: Su login es: {{ user.username }} Su clave es la que ingresó\r\n al registrarse. A partir de este momento se encuentra en su \"sitio \r\npersonal\" de&nbsp;</span><span style=\"color: rgb(51, 51, 51); line-height: 1.42857;\">{{ instance.name }}</span><span style=\"color: rgb(51, 51, 51); line-height: 1.42857;\">.\r\n Para cambiar su clave seleccione Cambiar Clave de acceso y modifique \r\nlos datos, según su comodidad. Entre las ventajas de este sistema \r\npodemos mencionar que el usuario cuenta con la información en tiempo \r\nreal de todo lo que ocurre con sus solicitudes en curso y un registro \r\nhistórico relacionado con todas sus solicitudes a lo largo del tiempo de\r\n utilización del servicio. Por medio de la Opción Pedido Nuevo, usted \r\npuede llenar el formulario para solicitar un Pedido. A la hora de \r\nsolicitar un nuevo pedido, debe revisar que la publicación que desea no \r\nse encuentre disponible en la institución.</span></p><p><span style=\"color: rgb(51, 51, 51); line-height: 1.42857;\"><br></span></p><p><span style=\"color: rgb(51, 51, 51);\">Atentamente,</span></p><p><span style=\"color: rgb(51, 51, 51); line-height: 1.42857;\"><br></span></p><p><span style=\"color: rgb(51, 51, 51);\">Personal</span></p><p><span style=\"color: rgb(51, 51, 51);\"><br></span></p><p><span style=\"color: rgb(51, 51, 51);\"><br></span><span style=\"color: rgb(51, 51, 51); line-height: 1.42857;\"><br></span></p><p><br></p>', '2017-01-10 11:36:44', '2017-01-10 11:36:44', 'mail', 1, 'Bienvenida nuevo usuario'),
(45, 1, 'resetting', '<p>Estimado/a {{ user.username }} :</p><p><br></p><p>Para restablecer tu contraseña - por favor visita {{ url }}</p><p><br></p><p>Atte.</p><p>Personal<br></p><p><br></p>', '2017-01-10 11:54:00', '2017-01-10 11:54:00', 'mail', 1, 'Reestablecer contraseña'),
(49, 1, 'user_lost', '<p>Estimado {{ user.full_name }}:<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Hemos detectado que ud. no pertenece a la institución {{ instance.name }}. Por favor, comuníquese con nosotros al siguiente correo {{ instance.email }}</p><p><br>Disculpe las molestias.<br>Muchas Gracias por su interés en nuestros servicios.<br></p>', '2017-02-08 14:20:04', '2017-02-08 14:37:45', 'mail', 1, 'Usuario perdido'),
(50, 1, 'user_confirmation', '<p>Estimado/a {{ user.username }}<br></p><p>Para completar la validación de tu cuenta - por favor visita {{ url }}</p><p><br></p><p>Atte.</p><p>Personal<br></p>', '2017-02-08 14:21:55', '2017-02-08 14:21:55', 'mail', 1, 'Confirmación de usuario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `thread`
--

CREATE TABLE `thread` (
  `id` int(11) NOT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `createdAt` datetime NOT NULL,
  `isSpam` tinyint(1) NOT NULL,
  `createdBy_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `thread_metadata`
--

CREATE TABLE `thread_metadata` (
  `id` int(11) NOT NULL,
  `thread_id` int(11) DEFAULT NULL,
  `participant_id` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  `last_participant_message_date` datetime DEFAULT NULL,
  `last_message_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ticket`
--

CREATE TABLE `ticket` (
  `id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `user_assigned_id` int(11) DEFAULT NULL,
  `status_current_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `priority_id` int(11) DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `text` longtext COLLATE utf8_unicode_ci NOT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ticket_category`
--

CREATE TABLE `ticket_category` (
  `id` int(11) NOT NULL,
  `category` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ticket_priority`
--

CREATE TABLE `ticket_priority` (
  `id` int(11) NOT NULL,
  `priority` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ticket_state`
--

CREATE TABLE `ticket_state` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `type_state_id` int(11) DEFAULT NULL,
  `ticket_id` int(11) NOT NULL,
  `descripcion` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ticket_type_state`
--

CREATE TABLE `ticket_type_state` (
  `id` int(11) NOT NULL,
  `typeState` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `uploads_files`
--

CREATE TABLE `uploads_files` (
  `event_id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `instance_id` int(11) NOT NULL,
  `institution_id` int(11) NOT NULL,
  `username` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `username_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `email_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `confirmation_token` varchar(180) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `surname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `birthdate` date DEFAULT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `downloadAuth` tinyint(1) NOT NULL,
  `wrongEmail` tinyint(1) NOT NULL,
  `pdf` tinyint(1) NOT NULL,
  `secondary_instances` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `observaciones` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `locked` tinyint(1) NOT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_client`
--

CREATE TABLE `user_client` (
  `user_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `AccessToken`
--
ALTER TABLE `AccessToken`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_B39617F55F37A13B` (`token`),
  ADD KEY `IDX_B39617F519EB6921` (`client_id`),
  ADD KEY `IDX_B39617F5A76ED395` (`user_id`);

--
-- Indices de la tabla `approves_files`
--
ALTER TABLE `approves_files`
  ADD PRIMARY KEY (`event_id`,`file_id`),
  ADD UNIQUE KEY `UNIQ_23888D7793CB796C` (`file_id`),
  ADD KEY `IDX_23888D7771F7E88B` (`event_id`);

--
-- Indices de la tabla `AuthCode`
--
ALTER TABLE `AuthCode`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_F1D7D1775F37A13B` (`token`),
  ADD KEY `IDX_F1D7D17719EB6921` (`client_id`),
  ADD KEY `IDX_F1D7D177A76ED395` (`user_id`);

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
-- Indices de la tabla `Client`
--
ALTER TABLE `Client`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_C0E801633A51721D` (`instance_id`);

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
-- Indices de la tabla `custom_user_field`
--
ALTER TABLE `custom_user_field`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_idx` (`key`,`instance_id`),
  ADD KEY `idx_key` (`key`),
  ADD KEY `idx_name` (`name`),
  ADD KEY `idx_instance` (`instance_id`);

--
-- Indices de la tabla `custom_user_value`
--
ALTER TABLE `custom_user_value`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_field` (`field_id`),
  ADD KEY `idx_user` (`user_id`);

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
  ADD KEY `translations_lookup_idx` (`locale`,`object_class`,`foreign_key`);

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
  ADD KEY `idx_created_at` (`createdAt`),
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
-- Indices de la tabla `RefreshToken`
--
ALTER TABLE `RefreshToken`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_7142379E5F37A13B` (`token`),
  ADD KEY `IDX_7142379E19EB6921` (`client_id`),
  ADD KEY `IDX_7142379EA76ED395` (`user_id`);

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
  ADD KEY `idx_created_by` (`createdBy_id`),
  ADD KEY `idx_created_at` (`createdAt`);

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
  ADD UNIQUE KEY `UNIQ_8D93D64992FC23A8` (`username_canonical`),
  ADD UNIQUE KEY `UNIQ_8D93D649A0D96FBF` (`email_canonical`),
  ADD UNIQUE KEY `UNIQ_8D93D649C05FB297` (`confirmation_token`),
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
-- AUTO_INCREMENT de la tabla `AccessToken`
--
ALTER TABLE `AccessToken`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `AuthCode`
--
ALTER TABLE `AuthCode`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `catalog`
--
ALTER TABLE `catalog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `catalog_position`
--
ALTER TABLE `catalog_position`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `catalog_result`
--
ALTER TABLE `catalog_result`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `city`
--
ALTER TABLE `city`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=341;
--
-- AUTO_INCREMENT de la tabla `Client`
--
ALTER TABLE `Client`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `configuration`
--
ALTER TABLE `configuration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=595;
--
-- AUTO_INCREMENT de la tabla `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `contact_type`
--
ALTER TABLE `contact_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `counter`
--
ALTER TABLE `counter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `country`
--
ALTER TABLE `country`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;
--
-- AUTO_INCREMENT de la tabla `custom_user_field`
--
ALTER TABLE `custom_user_field`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `custom_user_value`
--
ALTER TABLE `custom_user_value`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `email`
--
ALTER TABLE `email`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `event`
--
ALTER TABLE `event`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `ext_log_entries`
--
ALTER TABLE `ext_log_entries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `ext_translations`
--
ALTER TABLE `ext_translations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `file`
--
ALTER TABLE `file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `file_download`
--
ALTER TABLE `file_download`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `hive`
--
ALTER TABLE `hive`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `instance`
--
ALTER TABLE `instance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `journal`
--
ALTER TABLE `journal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8697;
--
-- AUTO_INCREMENT de la tabla `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `material_type`
--
ALTER TABLE `material_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `message_metadata`
--
ALTER TABLE `message_metadata`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `notification_settings`
--
ALTER TABLE `notification_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `order`
--
ALTER TABLE `order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `provider`
--
ALTER TABLE `provider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9023;
--
-- AUTO_INCREMENT de la tabla `RefreshToken`
--
ALTER TABLE `RefreshToken`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `request`
--
ALTER TABLE `request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `state`
--
ALTER TABLE `state`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `template`
--
ALTER TABLE `template`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;
--
-- AUTO_INCREMENT de la tabla `thread`
--
ALTER TABLE `thread`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `thread_metadata`
--
ALTER TABLE `thread_metadata`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `ticket`
--
ALTER TABLE `ticket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `ticket_category`
--
ALTER TABLE `ticket_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `ticket_priority`
--
ALTER TABLE `ticket_priority`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `ticket_state`
--
ALTER TABLE `ticket_state`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `ticket_type_state`
--
ALTER TABLE `ticket_type_state`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `AccessToken`
--
ALTER TABLE `AccessToken`
  ADD CONSTRAINT `FK_B39617F519EB6921` FOREIGN KEY (`client_id`) REFERENCES `Client` (`id`),
  ADD CONSTRAINT `FK_B39617F5A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Filtros para la tabla `approves_files`
--
ALTER TABLE `approves_files`
  ADD CONSTRAINT `FK_23888D7771F7E88B` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
  ADD CONSTRAINT `FK_23888D7793CB796C` FOREIGN KEY (`file_id`) REFERENCES `file` (`id`);

--
-- Filtros para la tabla `AuthCode`
--
ALTER TABLE `AuthCode`
  ADD CONSTRAINT `FK_F1D7D17719EB6921` FOREIGN KEY (`client_id`) REFERENCES `Client` (`id`),
  ADD CONSTRAINT `FK_F1D7D177A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

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
--  ADD CONSTRAINT `FK_2D5B0234F92F3E70` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`);

--
-- Filtros para la tabla `Client`
--
ALTER TABLE `Client`
  ADD CONSTRAINT `FK_C0E801633A51721D` FOREIGN KEY (`instance_id`) REFERENCES `instance` (`id`);

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
-- Filtros para la tabla `custom_user_field`
--
ALTER TABLE `custom_user_field`
  ADD CONSTRAINT `FK_86C882AA3A51721D` FOREIGN KEY (`instance_id`) REFERENCES `instance` (`id`);

--
-- Filtros para la tabla `custom_user_value`
--
ALTER TABLE `custom_user_value`
  ADD CONSTRAINT `FK_C04A9FC6443707B0` FOREIGN KEY (`field_id`) REFERENCES `custom_user_field` (`id`),
  ADD CONSTRAINT `FK_C04A9FC6A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

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
-- Filtros para la tabla `RefreshToken`
--
ALTER TABLE `RefreshToken`
  ADD CONSTRAINT `FK_7142379E19EB6921` FOREIGN KEY (`client_id`) REFERENCES `Client` (`id`),
  ADD CONSTRAINT `FK_7142379EA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

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
-- Filtros para la tabla `thread`
--
ALTER TABLE `thread`
  ADD CONSTRAINT `FK_31204C833174800F` FOREIGN KEY (`createdBy_id`) REFERENCES `user` (`id`);

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
  ADD CONSTRAINT `FK_A2161F6819EB6921` FOREIGN KEY (`client_id`) REFERENCES `Client` (`id`),
  ADD CONSTRAINT `FK_A2161F68A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
