-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 24-05-2020 a las 23:24:49
-- Versión del servidor: 5.7.26
-- Versión de PHP: 7.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `Monitor_vital`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Medical_eq`
--

CREATE TABLE `Medical_eq` (
  `MACEQ` varchar(17) COLLATE utf8_spanish_ci NOT NULL,
  `TYPE` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `BRAND` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `MODEL` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `LAST_SEEN` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Net_devices`
--

CREATE TABLE `Net_devices` (
  `MACND` varchar(17) COLLATE utf8_spanish_ci NOT NULL,
  `TYPE` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `IP_ADDR` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `SSH` tinyint(1) NOT NULL,
  `TELNET` tinyint(1) NOT NULL,
  `NPORTS` int(3) NOT NULL,
  `BRAND` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `MODEL` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `PASS` varchar(255) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `Net_devices`
--

INSERT INTO `Net_devices` (`MACND`, `TYPE`, `IP_ADDR`, `SSH`, `TELNET`, `NPORTS`, `BRAND`, `MODEL`, `PASS`) VALUES
('00:18:BA:92:35:80', 'switch', '192.168.1.4', 0, 1, 50, 'Cisco', 'Catalyst 2960', 'toor'),
('B8:27:EB:FC:F9:3C', 'ap', '192.168.2.1', 1, 0, 2, 'openwrt', 'Raspberry Pi 3B', 'toor');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Ports`
--

CREATE TABLE `Ports` (
  `ID` int(11) NOT NULL,
  `NAME` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `LOCATION` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `IP_ADDR` varchar(15) COLLATE utf8_spanish_ci DEFAULT NULL,
  `MACND` varchar(17) COLLATE utf8_spanish_ci DEFAULT NULL,
  `MACEQ` varchar(17) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `Ports`
--

INSERT INTO `Ports` (`ID`, `NAME`, `LOCATION`, `IP_ADDR`, `MACND`, `MACEQ`) VALUES
(356, 'FastEthernet0/1', NULL, NULL, '00:18:BA:92:35:80', NULL),
(357, 'FastEthernet0/2', NULL, NULL, '00:18:BA:92:35:80', NULL),
(358, 'FastEthernet0/3', NULL, NULL, '00:18:BA:92:35:80', NULL),
(359, 'FastEthernet0/4', NULL, NULL, '00:18:BA:92:35:80', NULL),
(360, 'FastEthernet0/5', NULL, NULL, '00:18:BA:92:35:80', NULL),
(361, 'FastEthernet0/6', NULL, NULL, '00:18:BA:92:35:80', NULL),
(362, 'FastEthernet0/7', NULL, NULL, '00:18:BA:92:35:80', NULL),
(363, 'FastEthernet0/8', NULL, NULL, '00:18:BA:92:35:80', NULL),
(364, 'FastEthernet0/9', NULL, NULL, '00:18:BA:92:35:80', NULL),
(365, 'FastEthernet0/10', NULL, NULL, '00:18:BA:92:35:80', NULL),
(366, 'FastEthernet0/11', NULL, NULL, '00:18:BA:92:35:80', NULL),
(367, 'FastEthernet0/12', NULL, NULL, '00:18:BA:92:35:80', NULL),
(368, 'FastEthernet0/13', NULL, NULL, '00:18:BA:92:35:80', NULL),
(369, 'FastEthernet0/14', NULL, NULL, '00:18:BA:92:35:80', NULL),
(370, 'FastEthernet0/15', NULL, NULL, '00:18:BA:92:35:80', NULL),
(371, 'FastEthernet0/16', NULL, NULL, '00:18:BA:92:35:80', NULL),
(372, 'FastEthernet0/17', NULL, NULL, '00:18:BA:92:35:80', NULL),
(373, 'FastEthernet0/18', NULL, NULL, '00:18:BA:92:35:80', NULL),
(374, 'FastEthernet0/19', NULL, NULL, '00:18:BA:92:35:80', NULL),
(375, 'FastEthernet0/20', NULL, NULL, '00:18:BA:92:35:80', NULL),
(376, 'FastEthernet0/21', NULL, NULL, '00:18:BA:92:35:80', NULL),
(377, 'FastEthernet0/22', NULL, NULL, '00:18:BA:92:35:80', NULL),
(378, 'FastEthernet0/23', NULL, NULL, '00:18:BA:92:35:80', NULL),
(379, 'FastEthernet0/24', NULL, NULL, '00:18:BA:92:35:80', NULL),
(380, 'FastEthernet0/25', NULL, NULL, '00:18:BA:92:35:80', NULL),
(381, 'FastEthernet0/26', NULL, NULL, '00:18:BA:92:35:80', NULL),
(382, 'FastEthernet0/27', NULL, NULL, '00:18:BA:92:35:80', NULL),
(383, 'FastEthernet0/28', NULL, NULL, '00:18:BA:92:35:80', NULL),
(384, 'FastEthernet0/29', NULL, NULL, '00:18:BA:92:35:80', NULL),
(385, 'FastEthernet0/30', NULL, NULL, '00:18:BA:92:35:80', NULL),
(386, 'FastEthernet0/31', NULL, NULL, '00:18:BA:92:35:80', NULL),
(387, 'FastEthernet0/32', NULL, NULL, '00:18:BA:92:35:80', NULL),
(388, 'FastEthernet0/33', NULL, NULL, '00:18:BA:92:35:80', NULL),
(389, 'FastEthernet0/34', NULL, NULL, '00:18:BA:92:35:80', NULL),
(390, 'FastEthernet0/35', NULL, NULL, '00:18:BA:92:35:80', NULL),
(391, 'FastEthernet0/36', NULL, NULL, '00:18:BA:92:35:80', NULL),
(392, 'FastEthernet0/37', NULL, NULL, '00:18:BA:92:35:80', NULL),
(393, 'FastEthernet0/38', NULL, NULL, '00:18:BA:92:35:80', NULL),
(394, 'FastEthernet0/39', NULL, NULL, '00:18:BA:92:35:80', NULL),
(395, 'FastEthernet0/40', NULL, NULL, '00:18:BA:92:35:80', NULL),
(396, 'FastEthernet0/41', NULL, NULL, '00:18:BA:92:35:80', NULL),
(397, 'FastEthernet0/42', NULL, NULL, '00:18:BA:92:35:80', NULL),
(398, 'FastEthernet0/43', NULL, NULL, '00:18:BA:92:35:80', NULL),
(399, 'FastEthernet0/44', NULL, NULL, '00:18:BA:92:35:80', NULL),
(400, 'FastEthernet0/45', NULL, NULL, '00:18:BA:92:35:80', NULL),
(401, 'FastEthernet0/46', NULL, NULL, '00:18:BA:92:35:80', NULL),
(402, 'FastEthernet0/47', NULL, NULL, '00:18:BA:92:35:80', NULL),
(403, 'FastEthernet0/48', NULL, NULL, '00:18:BA:92:35:80', NULL),
(404, 'GigabitEthernet0/1', NULL, NULL, '00:18:BA:92:35:80', NULL),
(405, 'GigabitEthernet0/2', NULL, NULL, '00:18:BA:92:35:80', NULL),
(410, 'Wlan0', NULL, NULL, 'B8:27:EB:FC:F9:3C', NULL),
(411, 'Wlan1', NULL, NULL, 'B8:27:EB:FC:F9:3C', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Users`
--

CREATE TABLE `Users` (
  `ID` int(11) NOT NULL,
  `NAME` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `USERNAME` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `PASS` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `ROLE` varchar(10) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `Users`
--

INSERT INTO `Users` (`ID`, `NAME`, `USERNAME`, `PASS`, `ROLE`) VALUES
(1, 'Administrator', 'admin', '$2y$04$rQk4qYsaAACi1dykBn8iSu8Tf7kjMNUy0NNiuNs7ny6GNpnCCktye', 'admin'),
(2, 'Juan', 'juan', '$2y$04$PNBzve0OdlGHKTR33O4gbu/39acya7BF6ozT.KNpQazlyayhGHnmi', 'tech'),
(3, 'María', 'maria', '$2y$04$vP/wMwY15gxIlWZKrt6sreAt18Mxh9N.mjEQaazhqQDpc9rIzMN1O', 'medical');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `Medical_eq`
--
ALTER TABLE `Medical_eq`
  ADD PRIMARY KEY (`MACEQ`);

--
-- Indices de la tabla `Net_devices`
--
ALTER TABLE `Net_devices`
  ADD PRIMARY KEY (`MACND`);

--
-- Indices de la tabla `Ports`
--
ALTER TABLE `Ports`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `UNIQUE` (`MACEQ`),
  ADD KEY `MACND` (`MACND`);

--
-- Indices de la tabla `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `Ports`
--
ALTER TABLE `Ports`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=412;

--
-- AUTO_INCREMENT de la tabla `Users`
--
ALTER TABLE `Users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `Ports`
--
ALTER TABLE `Ports`
  ADD CONSTRAINT `ports_ibfk_1` FOREIGN KEY (`MACND`) REFERENCES `Net_devices` (`MACND`),
  ADD CONSTRAINT `ports_ibfk_2` FOREIGN KEY (`MACEQ`) REFERENCES `Medical_eq` (`MACEQ`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
