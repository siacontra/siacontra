-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2+deb7u1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 05-02-2015 a las 15:50:18
-- Versión del servidor: 5.5.40
-- Versión de PHP: 5.4.34-0+deb7u1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `phpmyadmin`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pma_relation`
--

CREATE TABLE IF NOT EXISTS `pma_relation` (
  `master_db` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `master_table` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `master_field` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `foreign_db` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `foreign_table` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `foreign_field` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`master_db`,`master_table`,`master_field`),
  KEY `foreign_field` (`foreign_db`,`foreign_table`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Relation table';

--
-- Volcado de datos para la tabla `pma_relation`
--

INSERT INTO `pma_relation` (`master_db`, `master_table`, `master_field`, `foreign_db`, `foreign_table`, `foreign_field`) VALUES
('siacem01', 'lg_itemmast', 'CodFamilia', 'siacem01', 'lg_clasesubfamilia', 'CodLinea'),
('siacem01', 'lg_ordencompra', 'NroOrden', 'siacem01', 'lg_ordencompradetalle', 'NroOrden'),
('siacem01', 'lg_informeadjudicacion', 'CodAdjudicacion', 'siacem01', 'lg_adjudicaciondetalle', 'CodAdjudicacion'),
('siacem01', 'lg_requerimientos', 'CodRequerimiento', 'siacem01', 'lg_requerimientosdet', 'CodRequerimiento'),
('siacem01', 'lg_requerimientosdet', 'CommoditySub', 'siacem01', 'lg_commoditysub', 'Codigo'),
('siacem01', 'lg_requerimientosdet', 'CodItem', 'siacem01', 'lg_itemmast', 'CodItem'),
('siacem01', 'lg_itemmast', 'CodSubFamilia', 'siacem01', 'lg_clasesubfamilia', 'CodLinea'),
('siacem01', 'lg_itemmast', 'CodUnidadEmb', 'siacem01', 'mastunidades', 'CodUnidad'),
('siacem01', 'lg_actainicio', 'CodActaInicio', 'siacem01', 'lg_requedetalleacta', 'CodActaInicio'),
('siacem01', 'lg_itemmast', 'CodUnidadComp', 'siacem01', 'mastunidades', 'CodUnidad'),
('siacem01', 'lg_itemmast', 'CtaInventario', 'siacem01', 'ac_mastplancuenta', 'CodCuenta'),
('siacem01', 'lg_itemmast', 'CtaVenta', 'siacem01', 'ac_mastplancuenta', 'CodCuenta'),
('siacem01', 'lg_itemmast', 'CtaInventarioPub20', 'siacem01', 'ac_mastplancuenta', 'CodCuenta'),
('siacem01', 'lg_itemmast', 'CtaGastoPub20', 'siacem01', 'ac_mastplancuenta', 'CodCuenta'),
('siacem01', 'lg_itemmast', 'CtaGasto', 'siacem01', 'ac_mastplancuenta', 'CodCuenta'),
('siacem01', 'lg_itemmast', 'PartidaPresupuestal', 'siacem01', 'pv_partida', 'cod_partida'),
('siacem01', 'af_activo', 'Ubicacion', 'siacem01', 'af_ubicaciones', 'CodUbicacion');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
