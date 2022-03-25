-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-09-2021 a las 02:47:15
-- Versión del servidor: 10.4.18-MariaDB
-- Versión de PHP: 7.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `cliner_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `id_cita` varchar(3) NOT NULL,
  `id_paciente` varchar(10) NOT NULL,
  `fecha_cita` date NOT NULL,
  `hora_cita` time NOT NULL,
  `id_medico` varchar(10) NOT NULL,
  `estado_cita` varchar(10) NOT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `citas`
--

INSERT INTO `citas` (`id_cita`, `id_paciente`, `fecha_cita`, `hora_cita`, `id_medico`, `estado_cita`, `create_at`, `updated_at`) VALUES
('121', '121212', '2021-09-22', '22:20:00', '12121', 'Atendida', '2021-09-03 03:48:30', '2021-09-03 04:28:19'),
('122', '21212', '2021-09-08', '13:02:00', '11212', 'Asignada', '2021-09-03 03:23:51', '2021-09-03 04:28:11'),
('232', '32323', '2021-09-29', '23:44:00', '1111', 'Asignada', '2021-09-03 04:28:52', '2021-09-03 04:28:52'),
('765', '5656', '2021-09-28', '00:00:00', '6565', 'Cancelada', '2021-09-03 03:52:02', '2021-09-03 04:28:27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medicos`
--

CREATE TABLE `medicos` (
  `tipo_documento_med` varchar(2) NOT NULL,
  `no_documento_med` varchar(10) NOT NULL,
  `nombres_med` varchar(30) NOT NULL,
  `apellidos_med` varchar(30) NOT NULL,
  `direccion_med` varchar(50) CHARACTER SET utf8 NOT NULL,
  `barrio_med` varchar(50) NOT NULL,
  `ciudad_med` varchar(20) NOT NULL,
  `telefono_med` varchar(10) NOT NULL,
  `email_med` varchar(50) NOT NULL,
  `estado_med` varchar(8) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `medicos`
--

INSERT INTO `medicos` (`tipo_documento_med`, `no_documento_med`, `nombres_med`, `apellidos_med`, `direccion_med`, `barrio_med`, `ciudad_med`, `telefono_med`, `email_med`, `estado_med`, `created_at`, `updated_at`) VALUES
('CE', '1', '1', '1', '1', '1', '1', '1', '123@loquesea.com', 'Activo', '2021-09-03 03:20:51', '2021-09-03 03:22:38'),
('CC', '222', '2222', '222', '22', '22', '2', '2', '2@2', 'Inactivo', '2021-09-03 03:22:53', '2021-09-03 03:23:17'),
('PA', '3', '3', '3', '3', '3', '3', '3', '3@3', 'Activo', '2021-09-03 03:23:09', '2021-09-03 03:23:09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pacientes`
--

CREATE TABLE `pacientes` (
  `tipo_documento_pac` varchar(2) NOT NULL,
  `no_documento_pac` varchar(10) NOT NULL,
  `nombres_pac` varchar(30) NOT NULL,
  `apellidos_pac` varchar(30) NOT NULL,
  `direccion_pac` varchar(50) CHARACTER SET utf8 NOT NULL,
  `barrio_pac` varchar(50) NOT NULL,
  `ciudad_pac` varchar(20) NOT NULL,
  `telefono_pac` varchar(10) NOT NULL,
  `email_pac` varchar(50) NOT NULL,
  `estado_pac` varchar(8) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `pacientes`
--

INSERT INTO `pacientes` (`tipo_documento_pac`, `no_documento_pac`, `nombres_pac`, `apellidos_pac`, `direccion_pac`, `barrio_pac`, `ciudad_pac`, `telefono_pac`, `email_pac`, `estado_pac`, `created_at`, `updated_at`) VALUES
('TI', '1111', '2222', '3333', '444', '555', '666', '777', '888@999', 'Activo', '2021-09-03 02:02:11', '2021-09-03 02:02:11'),
('TI', '111111', 'aaaaa', 'aaaaa', 'aaaa', 'aaa', 'aaaa', 'aaaaaa', 'aaa@aaa.com', 'Inactivo', '2021-09-03 02:01:01', '2021-09-03 02:23:39'),
('TI', '1234', '4', '5', '6', '45', '8', '5', '9@87', 'Inactivo', '2021-09-03 02:02:46', '2021-09-03 02:13:12'),
('TI', '22222', '2222', '22', '2', '22', '2', '2', '222@22', 'Inactivo', '2021-09-03 02:01:18', '2021-09-03 02:14:37'),
('CE', '33333', '33', '33', '33', '33', '3', '333', '333@333', 'Activo', '2021-09-03 02:01:32', '2021-09-03 02:01:32'),
('TI', '4444', '4444', '4444', '4444', '4444', '4444', '4444', '444@444', 'Inactivo', '2021-09-03 02:01:51', '2021-09-03 02:14:27');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`id_cita`);

--
-- Indices de la tabla `medicos`
--
ALTER TABLE `medicos`
  ADD PRIMARY KEY (`no_documento_med`);

--
-- Indices de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  ADD PRIMARY KEY (`no_documento_pac`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
