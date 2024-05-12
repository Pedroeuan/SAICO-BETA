-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-05-2024 a las 01:19:15
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `saico_beta`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `accesorios`
--

CREATE TABLE `accesorios` (
  `idAccesorios` int(11) NOT NULL,
  `idGeneral_EyC` int(11) NOT NULL,
  `Proveedor` varchar(200) DEFAULT NULL,
  `Unidades_existentes_oficina` varchar(200) DEFAULT NULL,
  `Fecha_suministro` date DEFAULT NULL,
  `Fecha_salida` varchar(200) DEFAULT NULL,
  `Cantidad_pzs_entregadas` varchar(200) DEFAULT NULL,
  `Fecha_llegada` date DEFAULT NULL,
  `Condicion_pzs_recibidas` varchar(200) DEFAULT NULL,
  `Total` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `almacen`
--

CREATE TABLE `almacen` (
  `idAlmacen` int(11) NOT NULL,
  `idGeneral_EyC` int(11) NOT NULL,
  `Lote` varchar(200) DEFAULT NULL,
  `Stock` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `block_probeta`
--

CREATE TABLE `block_probeta` (
  `idBlock_probeta` int(11) NOT NULL,
  `idGeneral_EyC` int(11) NOT NULL,
  `Fecha_calibracion` date DEFAULT NULL,
  `Num_certificado_calibracion` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `certificados`
--

CREATE TABLE `certificados` (
  `idCertificados` int(11) NOT NULL,
  `idGeneral_EyC` int(11) NOT NULL,
  `No_certificado` varchar(200) DEFAULT NULL,
  `Certificado_Actual` varchar(200) DEFAULT NULL,
  `Fecha_calibracion` date DEFAULT NULL,
  `Prox_fecha_calibracion` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `certificados`
--

INSERT INTO `certificados` (`idCertificados`, `idGeneral_EyC`, `No_certificado`, `Certificado_Actual`, `Fecha_calibracion`, `Prox_fecha_calibracion`) VALUES
(1, 1, '1', '1', '2024-05-03', '2024-05-04'),
(2, 2, '2', '2', '2024-05-07', '2024-05-07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consumibles`
--

CREATE TABLE `consumibles` (
  `idConsumibles` int(11) NOT NULL,
  `idGeneral_EyC` int(11) NOT NULL,
  `Proveedor` varchar(200) DEFAULT NULL,
  `Lugar_anaquel` varchar(200) DEFAULT NULL,
  `Tipo` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_oc`
--

CREATE TABLE `detalles_oc` (
  `idDetalles_OC` int(11) NOT NULL,
  `OC_idOC` int(11) NOT NULL,
  `Uni_medida` varchar(200) DEFAULT NULL,
  `Cantidad` varchar(200) DEFAULT NULL,
  `Descripcion` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `devoluciones`
--

CREATE TABLE `devoluciones` (
  `idDevoluciones` int(11) NOT NULL,
  `idGeneral_EyC` int(11) NOT NULL,
  `idAlmacen` int(11) NOT NULL,
  `idHistorial_almacen` int(11) NOT NULL,
  `Estado_Cantidad` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipos`
--

CREATE TABLE `equipos` (
  `idEquipos` int(11) NOT NULL,
  `idGeneral_EyC` int(11) NOT NULL,
  `Proceso` varchar(200) DEFAULT NULL,
  `Metodo` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipos_tools_complementos`
--

CREATE TABLE `equipos_tools_complementos` (
  `idEquipos_Tools_Complementos` int(11) NOT NULL,
  `idGeneral_EyC` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `firmantes_os`
--

CREATE TABLE `firmantes_os` (
  `idFirmantes_OS` int(11) NOT NULL,
  `Nombre_Comp` varchar(100) DEFAULT NULL,
  `Cargo` varchar(200) DEFAULT NULL,
  `Compania` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `general_eyc`
--

CREATE TABLE `general_eyc` (
  `idGeneral_EyC` int(11) NOT NULL,
  `Nombre_E_P_BP` varchar(200) DEFAULT NULL,
  `No_economico` varchar(200) DEFAULT NULL,
  `Serie` varchar(200) DEFAULT NULL,
  `Marca` varchar(200) DEFAULT NULL,
  `Modelo` varchar(200) DEFAULT NULL,
  `Ubicacion` varchar(200) DEFAULT NULL,
  `Almacenamiento` varchar(200) DEFAULT NULL,
  `Comentario` varchar(200) DEFAULT NULL,
  `SAT` varchar(200) DEFAULT NULL,
  `BMPRO` varchar(200) DEFAULT NULL,
  `Factura` varchar(200) DEFAULT NULL,
  `Destino` varchar(200) DEFAULT NULL,
  `Tipo` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `general_eyc`
--

INSERT INTO `general_eyc` (`idGeneral_EyC`, `Nombre_E_P_BP`, `No_economico`, `Serie`, `Marca`, `Modelo`, `Ubicacion`, `Almacenamiento`, `Comentario`, `SAT`, `BMPRO`, `Factura`, `Destino`, `Tipo`) VALUES
(1, '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1'),
(2, '2', '2', '2', '2', '2', '2', '2', '2', '2', '2', '2', '2', '2');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupo_juntas_detalles_os`
--

CREATE TABLE `grupo_juntas_detalles_os` (
  `idGrupo_Juntas_Detalles_OS` int(11) NOT NULL,
  `idOrden_Servicio` int(11) NOT NULL,
  `idOC` int(11) NOT NULL,
  `idFirmantes_OS` int(11) NOT NULL,
  `Nombre_grupo` varchar(200) DEFAULT NULL,
  `Juntas_grupo` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_almacen`
--

CREATE TABLE `historial_almacen` (
  `idHistorial_almacen` int(11) NOT NULL,
  `idAlmacen` int(11) NOT NULL,
  `idGeneral_EyC` int(11) NOT NULL,
  `Tipo` varchar(200) DEFAULT NULL,
  `Cantidad` int(11) DEFAULT NULL,
  `Fecha` date DEFAULT NULL,
  `Tierra_Costafuera` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_certificados`
--

CREATE TABLE `historial_certificados` (
  `idHistorial_certificados` int(11) NOT NULL,
  `idCertificados` int(11) NOT NULL,
  `idGeneral_EyC` int(11) NOT NULL,
  `Certificado_Caducado` varchar(200) DEFAULT NULL,
  `Tipo` varchar(200) DEFAULT NULL,
  `Ultima_Fecha_calibracion` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `manifiestos`
--

CREATE TABLE `manifiestos` (
  `idManifiestos` int(11) NOT NULL,
  `idSolicitud` int(11) NOT NULL,
  `idGeneral_EyC` int(11) NOT NULL,
  `Persona` varchar(45) DEFAULT NULL,
  `Fecha` varchar(45) DEFAULT NULL,
  `Trabajo` varchar(45) DEFAULT NULL,
  `Comentario` varchar(45) DEFAULT NULL,
  `Cantidad` varchar(45) DEFAULT NULL,
  `Unidad` varchar(45) DEFAULT NULL,
  `Descripcion` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `oc`
--

CREATE TABLE `oc` (
  `idOC` int(11) NOT NULL,
  `Num_OC` int(11) DEFAULT NULL,
  `Proyecto` varchar(200) DEFAULT NULL,
  `Lugar_trabajo` varchar(200) DEFAULT NULL,
  `Fecha_solicitud` varchar(200) DEFAULT NULL,
  `Tipo_servicio` varchar(200) DEFAULT NULL,
  `Estatus` varchar(200) DEFAULT NULL,
  `OC_archivo` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden_servicio`
--

CREATE TABLE `orden_servicio` (
  `idOrden_Servicio` int(11) NOT NULL,
  `OC_idOC` int(11) NOT NULL,
  `idFirmantes_OS` int(11) NOT NULL,
  `Obra` varchar(200) DEFAULT NULL,
  `Servicio` varchar(200) DEFAULT NULL,
  `Norma_codigo` varchar(200) DEFAULT NULL,
  `Contrato` varchar(200) DEFAULT NULL,
  `Folio` varchar(200) DEFAULT NULL,
  `Plano` varchar(200) DEFAULT NULL,
  `Fecha` date DEFAULT NULL,
  `Material` varchar(200) DEFAULT NULL,
  `Plataforma` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('ZTjvXKcHv4xqmRikLBNCASFa88ol52GyntqnQQsm', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:125.0) Gecko/20100101 Firefox/125.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoialp4ZVM4OXRWTUZidVhJanlZOVhkR21HdVlpYUp6UndYQkxtTmV6SyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9FcXVpcG9zIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjQ6ImF1dGgiO2E6MTp7czoyMToicGFzc3dvcmRfY29uZmlybWVkX2F0IjtpOjE3MTUxMjM5NDY7fX0=', 1715123949);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitud`
--

CREATE TABLE `solicitud` (
  `idSolicitud` int(11) NOT NULL,
  `idGeneral_EyC` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Pedro Eduardo Euan Graniel', 'admin@admin.com', NULL, '$2y$12$Kz9GeIcJ7yIaZER6H/EFXO6jfnwQAArLLat3noStsTbRYNPh4brSi', NULL, '2024-05-04 04:03:12', '2024-05-04 04:03:12');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `accesorios`
--
ALTER TABLE `accesorios`
  ADD PRIMARY KEY (`idAccesorios`,`idGeneral_EyC`),
  ADD KEY `fk_Accesorios_General_EyC1_idx` (`idGeneral_EyC`);

--
-- Indices de la tabla `almacen`
--
ALTER TABLE `almacen`
  ADD PRIMARY KEY (`idAlmacen`,`idGeneral_EyC`),
  ADD KEY `fk_Almacen_General_EyC1_idx` (`idGeneral_EyC`);

--
-- Indices de la tabla `block_probeta`
--
ALTER TABLE `block_probeta`
  ADD PRIMARY KEY (`idBlock_probeta`,`idGeneral_EyC`),
  ADD KEY `fk_Block_probeta_General_EyC1_idx` (`idGeneral_EyC`);

--
-- Indices de la tabla `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `certificados`
--
ALTER TABLE `certificados`
  ADD PRIMARY KEY (`idCertificados`,`idGeneral_EyC`),
  ADD KEY `fk_Certificados_General_EyC1_idx` (`idGeneral_EyC`);

--
-- Indices de la tabla `consumibles`
--
ALTER TABLE `consumibles`
  ADD PRIMARY KEY (`idConsumibles`,`idGeneral_EyC`),
  ADD KEY `fk_Consumibles_General_EyC1_idx` (`idGeneral_EyC`);

--
-- Indices de la tabla `detalles_oc`
--
ALTER TABLE `detalles_oc`
  ADD PRIMARY KEY (`idDetalles_OC`,`OC_idOC`),
  ADD KEY `fk_Detalles_OC_OC1_idx` (`OC_idOC`);

--
-- Indices de la tabla `devoluciones`
--
ALTER TABLE `devoluciones`
  ADD PRIMARY KEY (`idDevoluciones`,`idGeneral_EyC`,`idAlmacen`,`idHistorial_almacen`),
  ADD KEY `fk_Devoluciones_Historial_almacen1_idx` (`idHistorial_almacen`,`idAlmacen`,`idGeneral_EyC`);

--
-- Indices de la tabla `equipos`
--
ALTER TABLE `equipos`
  ADD PRIMARY KEY (`idEquipos`,`idGeneral_EyC`),
  ADD KEY `fk_Equipos_General_EyC1_idx` (`idGeneral_EyC`);

--
-- Indices de la tabla `equipos_tools_complementos`
--
ALTER TABLE `equipos_tools_complementos`
  ADD PRIMARY KEY (`idEquipos_Tools_Complementos`,`idGeneral_EyC`),
  ADD KEY `fk_Equipos_Tools_Complementos_General_EyC1_idx` (`idGeneral_EyC`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `firmantes_os`
--
ALTER TABLE `firmantes_os`
  ADD PRIMARY KEY (`idFirmantes_OS`);

--
-- Indices de la tabla `general_eyc`
--
ALTER TABLE `general_eyc`
  ADD PRIMARY KEY (`idGeneral_EyC`);

--
-- Indices de la tabla `grupo_juntas_detalles_os`
--
ALTER TABLE `grupo_juntas_detalles_os`
  ADD PRIMARY KEY (`idGrupo_Juntas_Detalles_OS`,`idOrden_Servicio`,`idOC`,`idFirmantes_OS`),
  ADD KEY `fk_Grupo_Juntas_Detalles_OS_Orden_Servicio1_idx` (`idOrden_Servicio`,`idOC`,`idFirmantes_OS`);

--
-- Indices de la tabla `historial_almacen`
--
ALTER TABLE `historial_almacen`
  ADD PRIMARY KEY (`idHistorial_almacen`,`idAlmacen`,`idGeneral_EyC`),
  ADD KEY `fk_Historial_almacen_Almacen1_idx` (`idAlmacen`,`idGeneral_EyC`);

--
-- Indices de la tabla `historial_certificados`
--
ALTER TABLE `historial_certificados`
  ADD PRIMARY KEY (`idHistorial_certificados`,`idCertificados`,`idGeneral_EyC`),
  ADD KEY `fk_Historial_certificados_Certificados1_idx` (`idCertificados`,`idGeneral_EyC`);

--
-- Indices de la tabla `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indices de la tabla `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `manifiestos`
--
ALTER TABLE `manifiestos`
  ADD PRIMARY KEY (`idManifiestos`,`idSolicitud`,`idGeneral_EyC`),
  ADD KEY `fk_Manifiestos_Solicitud1_idx` (`idSolicitud`,`idGeneral_EyC`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `oc`
--
ALTER TABLE `oc`
  ADD PRIMARY KEY (`idOC`);

--
-- Indices de la tabla `orden_servicio`
--
ALTER TABLE `orden_servicio`
  ADD PRIMARY KEY (`idOrden_Servicio`,`OC_idOC`,`idFirmantes_OS`),
  ADD KEY `fk_Orden_Servicio_OC_idx` (`OC_idOC`),
  ADD KEY `fk_Orden_Servicio_Firmantes_OS1_idx` (`idFirmantes_OS`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indices de la tabla `solicitud`
--
ALTER TABLE `solicitud`
  ADD PRIMARY KEY (`idSolicitud`,`idGeneral_EyC`),
  ADD KEY `fk_Solicitud_General_EyC1_idx` (`idGeneral_EyC`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `accesorios`
--
ALTER TABLE `accesorios`
  MODIFY `idAccesorios` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `almacen`
--
ALTER TABLE `almacen`
  MODIFY `idAlmacen` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `block_probeta`
--
ALTER TABLE `block_probeta`
  MODIFY `idBlock_probeta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `certificados`
--
ALTER TABLE `certificados`
  MODIFY `idCertificados` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `consumibles`
--
ALTER TABLE `consumibles`
  MODIFY `idConsumibles` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalles_oc`
--
ALTER TABLE `detalles_oc`
  MODIFY `idDetalles_OC` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `devoluciones`
--
ALTER TABLE `devoluciones`
  MODIFY `idDevoluciones` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `equipos`
--
ALTER TABLE `equipos`
  MODIFY `idEquipos` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `equipos_tools_complementos`
--
ALTER TABLE `equipos_tools_complementos`
  MODIFY `idEquipos_Tools_Complementos` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `firmantes_os`
--
ALTER TABLE `firmantes_os`
  MODIFY `idFirmantes_OS` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `general_eyc`
--
ALTER TABLE `general_eyc`
  MODIFY `idGeneral_EyC` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `grupo_juntas_detalles_os`
--
ALTER TABLE `grupo_juntas_detalles_os`
  MODIFY `idGrupo_Juntas_Detalles_OS` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historial_almacen`
--
ALTER TABLE `historial_almacen`
  MODIFY `idHistorial_almacen` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historial_certificados`
--
ALTER TABLE `historial_certificados`
  MODIFY `idHistorial_certificados` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `manifiestos`
--
ALTER TABLE `manifiestos`
  MODIFY `idManifiestos` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `oc`
--
ALTER TABLE `oc`
  MODIFY `idOC` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `orden_servicio`
--
ALTER TABLE `orden_servicio`
  MODIFY `idOrden_Servicio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `solicitud`
--
ALTER TABLE `solicitud`
  MODIFY `idSolicitud` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `accesorios`
--
ALTER TABLE `accesorios`
  ADD CONSTRAINT `fk_Accesorios_General_EyC1` FOREIGN KEY (`idGeneral_EyC`) REFERENCES `general_eyc` (`idGeneral_EyC`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `almacen`
--
ALTER TABLE `almacen`
  ADD CONSTRAINT `fk_Almacen_General_EyC1` FOREIGN KEY (`idGeneral_EyC`) REFERENCES `general_eyc` (`idGeneral_EyC`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `block_probeta`
--
ALTER TABLE `block_probeta`
  ADD CONSTRAINT `fk_Block_probeta_General_EyC1` FOREIGN KEY (`idGeneral_EyC`) REFERENCES `general_eyc` (`idGeneral_EyC`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `certificados`
--
ALTER TABLE `certificados`
  ADD CONSTRAINT `fk_Certificados_General_EyC1` FOREIGN KEY (`idGeneral_EyC`) REFERENCES `general_eyc` (`idGeneral_EyC`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `consumibles`
--
ALTER TABLE `consumibles`
  ADD CONSTRAINT `fk_Consumibles_General_EyC1` FOREIGN KEY (`idGeneral_EyC`) REFERENCES `general_eyc` (`idGeneral_EyC`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `detalles_oc`
--
ALTER TABLE `detalles_oc`
  ADD CONSTRAINT `fk_Detalles_OC_OC1` FOREIGN KEY (`OC_idOC`) REFERENCES `oc` (`idOC`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `devoluciones`
--
ALTER TABLE `devoluciones`
  ADD CONSTRAINT `fk_Devoluciones_Historial_almacen1` FOREIGN KEY (`idHistorial_almacen`,`idAlmacen`,`idGeneral_EyC`) REFERENCES `historial_almacen` (`idHistorial_almacen`, `idAlmacen`, `idGeneral_EyC`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `equipos`
--
ALTER TABLE `equipos`
  ADD CONSTRAINT `fk_Equipos_General_EyC1` FOREIGN KEY (`idGeneral_EyC`) REFERENCES `general_eyc` (`idGeneral_EyC`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `equipos_tools_complementos`
--
ALTER TABLE `equipos_tools_complementos`
  ADD CONSTRAINT `fk_Equipos_Tools_Complementos_General_EyC1` FOREIGN KEY (`idGeneral_EyC`) REFERENCES `general_eyc` (`idGeneral_EyC`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `grupo_juntas_detalles_os`
--
ALTER TABLE `grupo_juntas_detalles_os`
  ADD CONSTRAINT `fk_Grupo_Juntas_Detalles_OS_Orden_Servicio1` FOREIGN KEY (`idOrden_Servicio`,`idOC`,`idFirmantes_OS`) REFERENCES `orden_servicio` (`idOrden_Servicio`, `OC_idOC`, `idFirmantes_OS`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `historial_almacen`
--
ALTER TABLE `historial_almacen`
  ADD CONSTRAINT `fk_Historial_almacen_Almacen1` FOREIGN KEY (`idAlmacen`,`idGeneral_EyC`) REFERENCES `almacen` (`idAlmacen`, `idGeneral_EyC`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `historial_certificados`
--
ALTER TABLE `historial_certificados`
  ADD CONSTRAINT `fk_Historial_certificados_Certificados1` FOREIGN KEY (`idCertificados`,`idGeneral_EyC`) REFERENCES `certificados` (`idCertificados`, `idGeneral_EyC`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `manifiestos`
--
ALTER TABLE `manifiestos`
  ADD CONSTRAINT `fk_Manifiestos_Solicitud1` FOREIGN KEY (`idSolicitud`,`idGeneral_EyC`) REFERENCES `solicitud` (`idSolicitud`, `idGeneral_EyC`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `orden_servicio`
--
ALTER TABLE `orden_servicio`
  ADD CONSTRAINT `fk_Orden_Servicio_Firmantes_OS1` FOREIGN KEY (`idFirmantes_OS`) REFERENCES `firmantes_os` (`idFirmantes_OS`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Orden_Servicio_OC` FOREIGN KEY (`OC_idOC`) REFERENCES `oc` (`idOC`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `solicitud`
--
ALTER TABLE `solicitud`
  ADD CONSTRAINT `fk_Solicitud_General_EyC1` FOREIGN KEY (`idGeneral_EyC`) REFERENCES `general_eyc` (`idGeneral_EyC`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
