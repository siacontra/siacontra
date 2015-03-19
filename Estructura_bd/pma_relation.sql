-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2+deb7u1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 16-03-2015 a las 10:21:19
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
('saicom', 'pv_antepresupuestodet', 'cod_partida', 'saicom', 'pv_partida', 'cod_partida'),
('saicom', 'lg_itemmast', 'PartidaPresupuestal', 'saicom', 'pv_partida', 'cod_partida'),
('saicom', 'af_activo', 'Ubicacion', 'saicom', 'af_ubicaciones', 'CodUbicacion'),
('saicom', 'lg_itemmast', 'CtaGasto', 'saicom', 'ac_mastplancuenta', 'CodCuenta'),
('saicom', 'lg_itemmast', 'CtaGastoPub20', 'saicom', 'ac_mastplancuenta', 'CodCuenta'),
('saicom', 'lg_itemmast', 'CtaInventarioPub20', 'saicom', 'ac_mastplancuenta', 'CodCuenta'),
('saicom', 'lg_itemmast', 'CtaVenta', 'saicom', 'ac_mastplancuenta', 'CodCuenta'),
('saicom', 'lg_actainicio', 'CodActaInicio', 'saicom', 'lg_requedetalleacta', 'CodActaInicio'),
('saicom', 'lg_itemmast', 'CodUnidadComp', 'saicom', 'mastunidades', 'CodUnidad'),
('saicom', 'lg_itemmast', 'CtaInventario', 'saicom', 'ac_mastplancuenta', 'CodCuenta'),
('saicom', 'lg_requerimientosdet', 'CommoditySub', 'saicom', 'lg_commoditysub', 'Codigo'),
('saicom', 'lg_requerimientosdet', 'CodItem', 'saicom', 'lg_itemmast', 'CodItem'),
('saicom', 'lg_itemmast', 'CodSubFamilia', 'saicom', 'lg_clasesubfamilia', 'CodLinea'),
('saicom', 'lg_itemmast', 'CodUnidadEmb', 'saicom', 'mastunidades', 'CodUnidad'),
('saicom', 'lg_informeadjudicacion', 'CodAdjudicacion', 'saicom', 'lg_adjudicaciondetalle', 'CodAdjudicacion'),
('saicom', 'lg_requerimientos', 'CodRequerimiento', 'saicom', 'lg_requerimientosdet', 'CodRequerimiento'),
('saicom', 'lg_ordencompra', 'NroOrden', 'saicom', 'lg_ordencompradetalle', 'NroOrden'),
('saicom', 'lg_itemmast', 'CodFamilia', 'saicom', 'lg_clasesubfamilia', 'CodLinea'),
('saicom', 'pv_presupuestodet', 'cod_partida', 'saicom', 'pv_partida', 'cod_partida'),
('saicom', 'pv_subprog1', 'id_programa', 'saicom', 'pv_programa1', 'id_programa'),
('saicom', 'pv_proyecto1', 'id_sub', 'saicom', 'pv_subprog1', 'id_sub');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;