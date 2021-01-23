-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 13-08-2020 a las 23:34:56
-- Versión del servidor: 10.3.22-MariaDB-1:10.3.22+maria~bionic-log
-- Versión de PHP: 7.2.29-1+ubuntu18.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `titulos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `antecedente`
--

CREATE TABLE `antecedente` (
  `idAntecedente` int(11) UNSIGNED NOT NULL,
  `institucionProcedencia` varchar(100) NOT NULL,
  `idTipoEstudioAntecedente` int(11) NOT NULL,
  `tipoEstudioAntecedente` varchar(100) NOT NULL,
  `idEntidadFederativa` varchar(100) NOT NULL,
  `entidadFederativa` varchar(100) NOT NULL,
  `fechaInicio` date DEFAULT NULL,
  `fechaTerminacion` date NOT NULL,
  `noCedula` varchar(100) DEFAULT NULL,
  `curpProfesionista` varchar(18) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auth_assignment`
--

CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `auth_assignment`
--

INSERT INTO `auth_assignment` (`item_name`, `user_id`, `created_at`) VALUES
('admin', '1', 1475086052),
('universidad', '2', 1597378482);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auth_item`
--

CREATE TABLE `auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` smallint(6) NOT NULL,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` blob DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `auth_item`
--

INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES
('/#', 2, NULL, NULL, NULL, 1459878473, 1459878473),
('/inicio/*', 2, NULL, NULL, NULL, 1459878474, 1459878474),
('/titulos/*', 2, NULL, NULL, NULL, NULL, NULL),
('/titulos/firmarxml', 2, NULL, NULL, NULL, NULL, NULL),
('/usuario/*', 2, 'Modulo de usuarios', NULL, NULL, NULL, NULL),
('admin', 1, 'Rol  de acceso para el RBAC y el administración de usuarios dentro de la aplicación', NULL, NULL, 1453605679, 1459878389),
('universidad', 1, 'Rol de responsables', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auth_item_child`
--

CREATE TABLE `auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `auth_item_child`
--

INSERT INTO `auth_item_child` (`parent`, `child`) VALUES
('admin', '/inicio/*'),
('admin', '/titulos/*'),
('admin', '/usuario/*'),
('universidad', '/titulos/firmarxml');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auth_rule`
--

CREATE TABLE `auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` blob DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrera`
--

CREATE TABLE `carrera` (
  `cveCarrera` varchar(7) NOT NULL,
  `nombreCarrera` varchar(100) NOT NULL,
  `fechaInicio` date DEFAULT NULL,
  `fechaTerminacion` date DEFAULT NULL,
  `idAutorizacionReconocimiento` int(11) NOT NULL,
  `autorizacionReconocimiento` varchar(100) NOT NULL,
  `numeroRvoe` varchar(100) DEFAULT NULL,
  `cveInstitucion` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `expedicion`
--

CREATE TABLE `expedicion` (
  `idExpedicion` int(11) UNSIGNED NOT NULL,
  `fechaExpedicion` date NOT NULL,
  `idModalidadTitulacion` int(11) NOT NULL,
  `modalidadTitulacion` varchar(100) NOT NULL,
  `fechaExamenProfesional` date DEFAULT NULL,
  `fechaExencionExamenProfesional` date DEFAULT NULL,
  `cumplioServicioSocial` int(11) NOT NULL,
  `idFundamentoLegalServicioSocial` int(11) NOT NULL,
  `fundamentoLegalServicioSocial` varchar(100) NOT NULL,
  `idEntidadFederativa` varchar(100) NOT NULL,
  `entidadFederativa` varchar(100) NOT NULL,
  `curpProfesionista` varchar(18) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `importaciones`
--

CREATE TABLE `importaciones` (
  `id` int(11) UNSIGNED NOT NULL,
  `ruta_archivo` varchar(255) NOT NULL,
  `importado` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `importaciones`
--

INSERT INTO `importaciones` (`id`, `ruta_archivo`, `importado`) VALUES
(6, '/var/www/html/titulosgit/backend/web/titulos/CIWgq2clI85u1JPuJt2GYvB_mALuZw5i.xlsx', 0),
(8, '/var/www/html/titulosgit/backend/web/titulos/GYSSydi2T17ScKnD1afU622TPkCEZmM1.xlsx', 0),
(9, '/var/www/html/titulosgit/backend/web/titulos/-VBoFvtv7-1j0CPhjBP5MuD_X9-ujwm6.xlsx', 0),
(10, '/var/www/html/titulosgit/backend/web/titulos/ZxCRmitoEOL7EB-Epgj-VAtPAI8zfgFB.xlsx', 0),
(11, '/var/www/html/titulosgit/backend/web/titulos/P8EC-gzohK44DtjDRwz8f_fqPLA1ui7P.xlsx', 0),
(17, '/var/www/html/titulosgit/backend/web/titulos/0TrFmgtJsuko8_q__ve8EZpp3_OX8SQx.xlsx', 0),
(22, '/var/www/html/titulosgit/backend/web/titulos/iVlhb8LexduKGYam396T4J2SLDl0iTZX.xlsx', 0),
(23, '/var/www/html/titulosgit/backend/web/titulos/iatMWoakPuQOKuhNF4hdk-675GcnwzlV.xlsx', 0),
(24, '/var/www/html/titulosgit/backend/web/titulos/YJJTTJ3nfwnDhdUQb2hscaDpwbb8h0-Q.xlsx', 0),
(25, '/var/www/html/titulosgit/backend/web/titulos/xsFEHJP7LLmbFMDxOSnTTT3Q09Fom9mw.xlsx', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `institucion`
--

CREATE TABLE `institucion` (
  `cveInstitucion` varchar(7) NOT NULL,
  `nombreInstitucion` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migration`
--

CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1595563576),
('m130524_201442_init', 1595563576),
('m140501_075311_oauth_clients', 1595563631),
('m140501_075312_oauth_access_tokens', 1595563632),
('m140501_075313_oauth_refresh_tokens', 1595563632),
('m140501_075314_oauth_authorization_codes', 1595563632),
('m140501_075315_oauth_scopes', 1595563632),
('m140501_075316_oauth_public_keys', 1595563632),
('m140506_102106_rbac_init', 1595566899),
('m170907_052038_rbac_add_index_on_auth_assignment_user_id', 1595566899),
('m180523_151638_rbac_updates_indexes_without_prefix', 1595566899),
('m190124_110200_add_verification_token_column_to_user_table', 1595563576),
('m200409_110543_rbac_update_mssql_trigger', 1595566899),
('m200731_235318_create_table_institucion', 1596253354),
('m200731_235329_create_table_carrera', 1596253354),
('m200731_235410_create_table_responsables', 1597096415),
('m200731_235441_create_table_profesionista', 1596253414),
('m200731_235449_create_table_expedicion', 1596253414),
('m200731_235500_create_table_antecedente', 1596253414),
('m200801_000700_create_table_titulo_electronico', 1597285247),
('m200801_035050_create_table_importaciones', 1596254141);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `access_token` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `client_id` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `expires` datetime NOT NULL,
  `scope` varchar(2000) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `oauth_authorization_codes`
--

CREATE TABLE `oauth_authorization_codes` (
  `authorization_code` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `client_id` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `redirect_uri` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `expires` datetime NOT NULL,
  `scope` varchar(2000) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `client_id` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `client_secret` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `redirect_uri` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `grant_types` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `scope` varchar(2000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `oauth_clients`
--

INSERT INTO `oauth_clients` (`client_id`, `client_secret`, `redirect_uri`, `grant_types`, `scope`, `user_id`) VALUES
('testclient', 'testpass', 'http://fake/', 'client_credentials authorization_code password implicit', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `oauth_public_keys`
--

CREATE TABLE `oauth_public_keys` (
  `client_id` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `public_key` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `private_key` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `encription_algorithm` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'RS256'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `refresh_token` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `client_id` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `expires` datetime NOT NULL,
  `scope` varchar(2000) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `oauth_scopes`
--

CREATE TABLE `oauth_scopes` (
  `scope` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `is_default` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesionista`
--

CREATE TABLE `profesionista` (
  `curp` varchar(18) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `primerApellido` varchar(100) NOT NULL,
  `segundoApellido` varchar(100) DEFAULT NULL,
  `correoElectronico` varchar(100) NOT NULL,
  `folioControl` varchar(100) NOT NULL,
  `idExpedicion` int(11) DEFAULT NULL,
  `cveCarrera` varchar(7) NOT NULL,
  `fechaInicio` date DEFAULT NULL,
  `fechaTerminacion` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `responsables`
--

CREATE TABLE `responsables` (
  `id` int(11) UNSIGNED NOT NULL,
  `nombre` varchar(80) NOT NULL,
  `primerApellido` varchar(80) NOT NULL,
  `segundoApellido` varchar(80) DEFAULT NULL,
  `curp` varchar(80) NOT NULL,
  `idCargo` int(11) NOT NULL,
  `cargo` varchar(80) NOT NULL,
  `abrTitulo` varchar(80) DEFAULT NULL,
  `sello` text DEFAULT NULL,
  `certificadoResponsable` text DEFAULT NULL,
  `noCertificadoResponsable` varchar(80) NOT NULL,
  `cveInstitucion` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `titulo_electronico`
--

CREATE TABLE `titulo_electronico` (
  `idTituloElectronico` int(11) UNSIGNED NOT NULL,
  `xmlns` varchar(100) NOT NULL DEFAULT 'https://www.siged.sep.gob.mx/titulos/',
  `version` varchar(100) NOT NULL DEFAULT '1.0',
  `xmlnsXsi` varchar(250) NOT NULL DEFAULT 'http://www.w3.org/2001/XMLSchema-instance',
  `xsiShecmaLocation` varchar(250) NOT NULL DEFAULT 'https://www.siged.sep.gob.mx/titulos/schema.xsd'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `titulo_electronico`
--

INSERT INTO `titulo_electronico` (`idTituloElectronico`, `xmlns`, `version`, `xmlnsXsi`, `xsiShecmaLocation`) VALUES
(1, 'https://www.siged.sep.gob.mx/titulos/', '1.0', 'http://www.w3.org/2001/XMLSchema-instance', 'https://www.siged.sep.gob.mx/titulos/schema.xsd');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT 10,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `verification_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `instituciones` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `user`
--
-- pass 7h8j9k0l
INSERT INTO `user` (`id`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `status`, `created_at`, `updated_at`, `verification_token`, `instituciones`) VALUES
(1, 'admin', 'I27-Mt9hMQYdWadZ_1WpEpb6oC9TU4QU', '$2y$13$CYJLuZ8ebuNCHNXatWHuGuZhSdXLhbRlG3s.XjpFPLjNzNG2Su7yu', NULL, 'admin@sistema.com', 10, 1595987412, 1595987412, 'QBjaT7ivLmKMQMgi7OXb6uyHoHHFcR3f_1595987412', NULL),
(2, 'universidad', 'tIUs3MGL0uL6r7vNj5Dxuyo-S-O-6Uc3', '$2y$13$9.ss7jfb.3tb5R.7PXm1feOKL5xqb3Q157b02BpxYmZU/PLVHpADi', NULL, 'uni@gmail.com', 10, 1597101074, 1597378482, 'fmaBNTbACCCP5Y8F7TItf5DSvboZ0z69_1597101074', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `antecedente`
--
ALTER TABLE `antecedente`
  ADD PRIMARY KEY (`idAntecedente`),
  ADD KEY `fk-antecedente_profesionista` (`curpProfesionista`);

--
-- Indices de la tabla `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD PRIMARY KEY (`item_name`,`user_id`),
  ADD KEY `idx-auth_assignment-user_id` (`user_id`);

--
-- Indices de la tabla `auth_item`
--
ALTER TABLE `auth_item`
  ADD PRIMARY KEY (`name`),
  ADD KEY `rule_name` (`rule_name`),
  ADD KEY `idx-auth_item-type` (`type`);

--
-- Indices de la tabla `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD PRIMARY KEY (`parent`,`child`),
  ADD KEY `child` (`child`);

--
-- Indices de la tabla `auth_rule`
--
ALTER TABLE `auth_rule`
  ADD PRIMARY KEY (`name`);

--
-- Indices de la tabla `carrera`
--
ALTER TABLE `carrera`
  ADD PRIMARY KEY (`cveCarrera`),
  ADD UNIQUE KEY `nombreCarrera` (`nombreCarrera`),
  ADD KEY `fk-carrera_institucion` (`cveInstitucion`);

--
-- Indices de la tabla `expedicion`
--
ALTER TABLE `expedicion`
  ADD PRIMARY KEY (`idExpedicion`),
  ADD KEY `fk-expedicion_profesionista` (`curpProfesionista`);

--
-- Indices de la tabla `importaciones`
--
ALTER TABLE `importaciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `institucion`
--
ALTER TABLE `institucion`
  ADD PRIMARY KEY (`cveInstitucion`),
  ADD UNIQUE KEY `nombreInstitucion` (`nombreInstitucion`);

--
-- Indices de la tabla `migration`
--
ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);

--
-- Indices de la tabla `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`access_token`),
  ADD KEY `idx-oauth_access_tokens-client_id` (`client_id`);

--
-- Indices de la tabla `oauth_authorization_codes`
--
ALTER TABLE `oauth_authorization_codes`
  ADD PRIMARY KEY (`authorization_code`),
  ADD KEY `idx-oauth_authorization_codes-client_id` (`client_id`);

--
-- Indices de la tabla `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`client_id`);

--
-- Indices de la tabla `oauth_public_keys`
--
ALTER TABLE `oauth_public_keys`
  ADD PRIMARY KEY (`client_id`,`public_key`),
  ADD KEY `idx-oauth_public_keys-client_id` (`client_id`);

--
-- Indices de la tabla `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`refresh_token`),
  ADD KEY `idx-oauth_refresh_tokens-client_id` (`client_id`);

--
-- Indices de la tabla `oauth_scopes`
--
ALTER TABLE `oauth_scopes`
  ADD PRIMARY KEY (`scope`);

--
-- Indices de la tabla `profesionista`
--
ALTER TABLE `profesionista`
  ADD PRIMARY KEY (`curp`),
  ADD KEY `fk-profesionista_carrera` (`cveCarrera`);

--
-- Indices de la tabla `responsables`
--
ALTER TABLE `responsables`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk-responsable_institucion` (`cveInstitucion`);

--
-- Indices de la tabla `titulo_electronico`
--
ALTER TABLE `titulo_electronico`
  ADD PRIMARY KEY (`idTituloElectronico`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `password_reset_token` (`password_reset_token`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `antecedente`
--
ALTER TABLE `antecedente`
  MODIFY `idAntecedente` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `importaciones`
--
ALTER TABLE `importaciones`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT de la tabla `responsables`
--
ALTER TABLE `responsables`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `titulo_electronico`
--
ALTER TABLE `titulo_electronico`
  MODIFY `idTituloElectronico` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `antecedente`
--
ALTER TABLE `antecedente`
  ADD CONSTRAINT `fk-antecedente_profesionista` FOREIGN KEY (`curpProfesionista`) REFERENCES `profesionista` (`curp`) ON DELETE CASCADE;

--
-- Filtros para la tabla `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `auth_item`
--
ALTER TABLE `auth_item`
  ADD CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `carrera`
--
ALTER TABLE `carrera`
  ADD CONSTRAINT `fk-carrera_institucion` FOREIGN KEY (`cveInstitucion`) REFERENCES `institucion` (`cveInstitucion`) ON DELETE CASCADE;

--
-- Filtros para la tabla `expedicion`
--
ALTER TABLE `expedicion`
  ADD CONSTRAINT `fk-expedicion_profesionista` FOREIGN KEY (`curpProfesionista`) REFERENCES `profesionista` (`curp`) ON DELETE CASCADE;

--
-- Filtros para la tabla `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD CONSTRAINT `fk-oauth_access_tokens-client_id` FOREIGN KEY (`client_id`) REFERENCES `oauth_clients` (`client_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `oauth_authorization_codes`
--
ALTER TABLE `oauth_authorization_codes`
  ADD CONSTRAINT `fk-oauth_authorization_codes-client_id` FOREIGN KEY (`client_id`) REFERENCES `oauth_clients` (`client_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `oauth_public_keys`
--
ALTER TABLE `oauth_public_keys`
  ADD CONSTRAINT `fk-oauth_public_keys-client_id` FOREIGN KEY (`client_id`) REFERENCES `oauth_clients` (`client_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD CONSTRAINT `fk-oauth_refresh_tokens-client_id` FOREIGN KEY (`client_id`) REFERENCES `oauth_clients` (`client_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `profesionista`
--
ALTER TABLE `profesionista`
  ADD CONSTRAINT `fk-profesionista_carrera` FOREIGN KEY (`cveCarrera`) REFERENCES `carrera` (`cveCarrera`) ON DELETE CASCADE;

--
-- Filtros para la tabla `responsables`
--
ALTER TABLE `responsables`
  ADD CONSTRAINT `fk-responsable_institucion` FOREIGN KEY (`cveInstitucion`) REFERENCES `institucion` (`cveInstitucion`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
