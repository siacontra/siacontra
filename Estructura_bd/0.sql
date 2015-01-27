-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2+deb7u1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 27-01-2015 a las 09:00:26
-- Versión del servidor: 5.5.40
-- Versión de PHP: 5.4.34-0+deb7u1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `siacem01`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ac_contabilidades`
--

CREATE TABLE IF NOT EXISTS `ac_contabilidades` (
  `CodContabilidad` char(1) NOT NULL DEFAULT '',
  `Descripcion` varchar(30) NOT NULL DEFAULT '',
  `Estado` char(1) NOT NULL DEFAULT '',
  `UltimoUsuario` char(10) NOT NULL DEFAULT '',
  `UltimaFechaModif` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`CodContabilidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ac_controlcierremensual`
--

CREATE TABLE IF NOT EXISTS `ac_controlcierremensual` (
  `TipoRegistro` varchar(2) NOT NULL COMMENT 'AB:PERIODO ABIERTO; AC:PERIODO ACTUAL;',
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `Periodo` varchar(7) NOT NULL,
  `CodLibroCont` char(2) NOT NULL COMMENT 'ac_librocontable->CodLibroCont',
  `Estado` varchar(1) NOT NULL DEFAULT 'A' COMMENT 'A:ABIERTO; C:CERRADO;',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`TipoRegistro`,`CodOrganismo`,`Periodo`),
  KEY `FK_ac_controlcierremensual_1` (`CodLibroCont`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ac_grupocentrocosto`
--

CREATE TABLE IF NOT EXISTS `ac_grupocentrocosto` (
  `CodGrupoCentroCosto` varchar(4) NOT NULL,
  `Descripcion` varchar(50) NOT NULL,
  `Estado` varchar(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodGrupoCentroCosto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ac_librocontabilidades`
--

CREATE TABLE IF NOT EXISTS `ac_librocontabilidades` (
  `CodContabilidad` char(1) NOT NULL DEFAULT '',
  `CodLibroCont` char(2) NOT NULL DEFAULT '',
  PRIMARY KEY (`CodContabilidad`,`CodLibroCont`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ac_librocontable`
--

CREATE TABLE IF NOT EXISTS `ac_librocontable` (
  `CodLibroCont` char(2) NOT NULL DEFAULT '',
  `Descripcion` char(30) NOT NULL DEFAULT '',
  `Estado` char(1) NOT NULL DEFAULT 'A' COMMENT 'A: Activo - I: Inactivo',
  `UltimoUsuario` char(10) NOT NULL DEFAULT '',
  `UltimaFechaModif` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`CodLibroCont`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ac_mastcentrocosto`
--

CREATE TABLE IF NOT EXISTS `ac_mastcentrocosto` (
  `CodCentroCosto` varchar(4) NOT NULL,
  `Descripcion` varchar(255) NOT NULL DEFAULT '',
  `Abreviatura` varchar(10) NOT NULL,
  `CodPersona` varchar(6) NOT NULL,
  `TipoCentroCosto` varchar(1) NOT NULL,
  `CodDependencia` varchar(4) NOT NULL,
  `CodGrupoCentroCosto` varchar(4) NOT NULL,
  `CodSubGrupoCentroCosto` varchar(4) NOT NULL,
  `FlagAdministrativo` varchar(1) NOT NULL,
  `FlagVentas` varchar(1) NOT NULL,
  `FlagFinanciero` varchar(1) NOT NULL,
  `FlagProduccion` varchar(1) NOT NULL,
  `FlagCentroIngreso` varchar(1) NOT NULL,
  `Estado` varchar(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodCentroCosto`),
  KEY `FK_ac_mastcentrocosto_1` (`CodGrupoCentroCosto`,`CodSubGrupoCentroCosto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ac_mastplancuenta`
--

CREATE TABLE IF NOT EXISTS `ac_mastplancuenta` (
  `CodCuenta` varchar(13) NOT NULL,
  `Grupo` varchar(1) NOT NULL,
  `SubGrupo` varchar(1) NOT NULL,
  `Rubro` varchar(1) NOT NULL,
  `Cuenta` varchar(2) NOT NULL,
  `SubCuenta1` varchar(2) NOT NULL,
  `SubCuenta2` varchar(2) NOT NULL,
  `SubCuenta3` varchar(3) NOT NULL,
  `Descripcion` varchar(255) NOT NULL,
  `Nivel` int(1) unsigned NOT NULL,
  `FlagTipo` varchar(1) NOT NULL COMMENT 'P:PRINCIPAL; A:AUXILIAR;',
  `TipoCuenta` varchar(2) NOT NULL COMMENT 'MISCELANEOS->CUENTAS',
  `TipoSaldo` varchar(1) NOT NULL COMMENT 'D:DEUDORA; A:ACREEDORA',
  `Naturaleza` varchar(2) NOT NULL,
  `Estado` varchar(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodCuenta`),
  UNIQUE KEY `UK_ac_mastplancuenta_1` (`Grupo`,`SubGrupo`,`Rubro`,`Cuenta`,`SubCuenta1`,`SubCuenta2`,`SubCuenta3`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ac_mastplancuenta20`
--

CREATE TABLE IF NOT EXISTS `ac_mastplancuenta20` (
  `CodCuenta` varchar(13) NOT NULL,
  `Grupo` varchar(1) NOT NULL,
  `SubGrupo` varchar(1) NOT NULL,
  `Rubro` varchar(3) NOT NULL,
  `Cuenta` varchar(2) NOT NULL,
  `SubCuenta1` varchar(2) NOT NULL,
  `SubCuenta2` varchar(2) NOT NULL,
  `SubCuenta3` varchar(2) NOT NULL,
  `Descripcion` varchar(255) NOT NULL,
  `Nivel` int(1) unsigned NOT NULL,
  `FlagTipo` varchar(1) NOT NULL COMMENT 'P:PRINCIPAL; A:AUXILIAR;',
  `TipoCuenta` varchar(2) NOT NULL COMMENT 'MISCELANEOS->CUENTAS',
  `TipoSaldo` varchar(1) NOT NULL COMMENT 'D:DEUDORA; A:ACREEDORA',
  `Naturaleza` varchar(2) NOT NULL,
  `Estado` varchar(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodCuenta`),
  UNIQUE KEY `UK_ac_mastplancuenta_1` (`Grupo`,`SubGrupo`,`Rubro`,`Cuenta`,`SubCuenta1`,`SubCuenta2`,`SubCuenta3`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ac_modelovoucher`
--

CREATE TABLE IF NOT EXISTS `ac_modelovoucher` (
  `CodModeloVoucher` varchar(4) NOT NULL,
  `Descripcion` varchar(75) NOT NULL,
  `Distribucion` varchar(2) NOT NULL COMMENT 'CP:CUENTAS POR PAGAR; MF:MONTOS FIJOS; PO:PORCENTUAL;',
  `CodDependencia` varchar(4) NOT NULL COMMENT 'mastdependencias->CodDependencia',
  `CodLibroCont` varchar(2) NOT NULL COMMENT 'ac_librocontable->CodLibroCont',
  `Estado` varchar(1) NOT NULL COMMENT 'A:ACTIVO; I:INACTIVO',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodModeloVoucher`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ac_modelovoucherdetalle`
--

CREATE TABLE IF NOT EXISTS `ac_modelovoucherdetalle` (
  `CodModeloVoucher` varchar(4) NOT NULL,
  `Secuencia` varchar(4) NOT NULL,
  `CodCuenta` varchar(13) NOT NULL COMMENT 'ac_mastplancuenta->CodCuenta',
  `CodCentroCosto` varchar(4) NOT NULL COMMENT 'ac_mastcentrocosto->CodCentroCosto',
  `Porcentaje` decimal(11,2) NOT NULL,
  `Monto` decimal(11,2) NOT NULL,
  `CodPersona` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `NroDocumento` varchar(10) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodModeloVoucher`,`Secuencia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ac_sistemafuente`
--

CREATE TABLE IF NOT EXISTS `ac_sistemafuente` (
  `CodSistemaFuente` varchar(10) NOT NULL,
  `Descripcion` varchar(50) NOT NULL,
  `Estado` varchar(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodSistemaFuente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ac_subgrupocentrocosto`
--

CREATE TABLE IF NOT EXISTS `ac_subgrupocentrocosto` (
  `CodGrupoCentroCosto` varchar(4) NOT NULL,
  `CodSubGrupoCentroCosto` varchar(4) NOT NULL,
  `Descripcion` varchar(50) NOT NULL,
  `Estado` varchar(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodGrupoCentroCosto`,`CodSubGrupoCentroCosto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ac_voucher`
--

CREATE TABLE IF NOT EXISTS `ac_voucher` (
  `CodVoucher` varchar(2) NOT NULL,
  `Descripcion` varchar(50) NOT NULL,
  `FlagManual` varchar(1) NOT NULL,
  `Estado` varchar(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodVoucher`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ac_voucherbalance`
--

CREATE TABLE IF NOT EXISTS `ac_voucherbalance` (
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `Periodo` varchar(7) NOT NULL,
  `CodCuenta` varchar(13) NOT NULL COMMENT 'ac_mastplancuenta->CodCuenta',
  `CodContabilidad` varchar(1) NOT NULL COMMENT 'ac_contabilidades->CodContabilidad',
  `SaldoInicial` decimal(11,2) NOT NULL DEFAULT '0.00',
  `SaldoBalance` decimal(11,2) NOT NULL DEFAULT '0.00',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodOrganismo`,`Periodo`,`CodCuenta`,`CodContabilidad`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ac_voucherdet`
--

CREATE TABLE IF NOT EXISTS `ac_voucherdet` (
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `Periodo` varchar(7) NOT NULL,
  `Voucher` varchar(7) NOT NULL,
  `Linea` int(3) NOT NULL,
  `CodContabilidad` varchar(1) NOT NULL,
  `CodCuenta` varchar(13) NOT NULL COMMENT 'ac_mastplancuenta->CodCuenta',
  `MontoVoucher` decimal(11,2) NOT NULL,
  `MontoPost` decimal(11,2) NOT NULL,
  `CodPersona` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `NroCheque` varchar(10) NOT NULL,
  `FechaVoucher` date NOT NULL,
  `CodCentroCosto` varchar(4) NOT NULL COMMENT 'ac_mastcentrocosto->CodCentroCosto',
  `ReferenciaTipoDocumento` varchar(3) NOT NULL COMMENT 'OC:ORDEN DE COMPRA; OS:ORDEN DE SERVICIO',
  `ReferenciaNroDocumento` varchar(100) NOT NULL COMMENT 'NRO. DE LA ORDEN',
  `Descripcion` varchar(100) NOT NULL,
  `Estado` varchar(2) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodOrganismo`,`Periodo`,`Voucher`,`Linea`,`CodContabilidad`) USING BTREE,
  KEY `FK_ac_voucherdet_1` (`CodOrganismo`,`Periodo`,`Voucher`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ac_vouchermast`
--

CREATE TABLE IF NOT EXISTS `ac_vouchermast` (
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `Periodo` varchar(7) NOT NULL,
  `Voucher` varchar(7) NOT NULL,
  `Prefijo` varchar(2) NOT NULL,
  `NroVoucher` varchar(4) NOT NULL,
  `CodVoucher` varchar(2) NOT NULL COMMENT 'ac_voucher->CodVoucher',
  `CodDependencia` varchar(4) NOT NULL COMMENT 'mastdependencias->CodDependencia',
  `CodModeloVoucher` varchar(4) NOT NULL COMMENT 'ac_modelovoucher->CodModeloVoucher',
  `CodSistemaFuente` varchar(10) NOT NULL COMMENT 'ac_sistemafuente->CodSistemaFuente',
  `Creditos` decimal(11,2) NOT NULL DEFAULT '0.00',
  `Debitos` decimal(11,2) NOT NULL DEFAULT '0.00',
  `Lineas` int(3) NOT NULL,
  `PreparadoPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `FechaPreparacion` date NOT NULL,
  `AprobadoPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `FechaAprobacion` date NOT NULL,
  `TituloVoucher` text NOT NULL,
  `ComentariosVoucher` text NOT NULL,
  `FechaVoucher` date NOT NULL,
  `NroInterno` varchar(10) NOT NULL,
  `FlagTransferencia` varchar(1) NOT NULL DEFAULT 'N',
  `Estado` varchar(2) NOT NULL DEFAULT 'AB' COMMENT 'AB:ABIERTO; AP:APROBADO; MA:MAYORIZADO; AN:ANULADO;\nRE:RECHAZADO;',
  `CodLibroCont` char(2) DEFAULT NULL COMMENT 'ac_librocontable->CodLibroCont',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  `CodContabilidad` varchar(1) NOT NULL,
  `FechaAnulacion` datetime NOT NULL,
  `AnuladoPor` varchar(6) NOT NULL,
  `PeriodoAnulacion` varchar(7) NOT NULL,
  `MotivoAnulacion` varchar(250) NOT NULL,
  PRIMARY KEY (`CodOrganismo`,`Periodo`,`Voucher`,`CodContabilidad`) USING BTREE,
  KEY `FK_ac_vouchermast_1` (`CodLibroCont`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ac_vouchersgenerados`
--

CREATE TABLE IF NOT EXISTS `ac_vouchersgenerados` (
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `CodAplicacion` varchar(10) NOT NULL COMMENT 'mastaplicaciones->CodAplicacion',
  `CodVoucher` varchar(2) NOT NULL COMMENT 'ac_voucher->CodVoucher',
  `Periodo` year(4) NOT NULL,
  `Mes01` int(3) NOT NULL,
  `Mes02` int(3) NOT NULL,
  `Mes03` int(3) NOT NULL,
  `Mes04` int(3) NOT NULL,
  `Mes05` int(3) NOT NULL,
  `Mes06` int(3) NOT NULL,
  `Mes07` int(3) NOT NULL,
  `Mes08` int(3) NOT NULL,
  `Mes09` int(3) NOT NULL,
  `Mes10` int(3) NOT NULL,
  `Mes11` int(3) NOT NULL,
  `Mes12` int(3) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodOrganismo`,`CodAplicacion`,`CodVoucher`,`Periodo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `af_activo`
--

CREATE TABLE IF NOT EXISTS `af_activo` (
  `Activo` char(10) NOT NULL DEFAULT '',
  `CodOrganismo` varchar(4) NOT NULL DEFAULT '',
  `CodDependencia` varchar(4) NOT NULL DEFAULT '',
  `Descripcion` longtext NOT NULL COMMENT 'Maestro Situación del Activo',
  `TipoActivo` char(1) NOT NULL DEFAULT '' COMMENT 'I: Individual - P: Principal',
  `EstadoConserv` varchar(1) NOT NULL DEFAULT '' COMMENT 'Bueno - Malo - Regular - Obsoleto',
  `CodigoBarras` varchar(15) NOT NULL DEFAULT '',
  `CodigoInterno` varchar(25) NOT NULL DEFAULT '',
  `TipoSeguro` char(4) NOT NULL DEFAULT '' COMMENT 'Maestro Tipo Seguro',
  `TipoVehiculo` char(4) NOT NULL DEFAULT '' COMMENT 'Maestro',
  `Categoria` char(10) NOT NULL DEFAULT '' COMMENT 'Maestro Categoria Depreciación',
  `Clasificacion` char(15) NOT NULL DEFAULT '' COMMENT 'Maestro de Clasificacion del Activo',
  `ClasificacionPublic20` char(15) NOT NULL DEFAULT '' COMMENT 'Maestro de Clasificacion Publicacion 20',
  `Ubicacion` char(15) NOT NULL DEFAULT '' COMMENT 'Maestro Ubicacion del Activo',
  `TipoMejora` char(15) NOT NULL DEFAULT '' COMMENT 'No Aplicable - Adición - Revaluación Voluntaria ',
  `ActivoConsolidado` char(10) NOT NULL DEFAULT '' COMMENT 'Selector de Activos',
  `EmpleadoUsuario` char(10) NOT NULL DEFAULT '' COMMENT 'Selector de Empleados',
  `EmpleadoResponsable` char(10) NOT NULL DEFAULT '' COMMENT 'Selector de Empleados',
  `CentroCosto` char(10) NOT NULL DEFAULT '' COMMENT 'Selector de Centro de costo',
  `Marca` char(30) NOT NULL DEFAULT '' COMMENT 'Selector de Marcas',
  `Modelo` char(20) NOT NULL DEFAULT '',
  `NumeroSerie` char(20) NOT NULL DEFAULT '',
  `NumeroSerieMotor` char(20) NOT NULL DEFAULT '',
  `NumeroPlaca` char(20) NOT NULL DEFAULT '',
  `MarcaMotor` char(30) NOT NULL DEFAULT '',
  `NumeroAsiento` int(3) NOT NULL DEFAULT '0',
  `Material` char(30) NOT NULL DEFAULT '',
  `Dimensiones` char(30) NOT NULL DEFAULT '',
  `NumerodeParte` char(30) NOT NULL DEFAULT '',
  `Color` char(3) NOT NULL DEFAULT '' COMMENT 'maestro colores',
  `FabricacionPais` char(4) NOT NULL DEFAULT '' COMMENT 'Maestro de Paises',
  `FabricacionAno` char(4) NOT NULL DEFAULT '',
  `PolizaSeguro` char(8) NOT NULL DEFAULT '' COMMENT 'Maestro de Pólizas de Seguro',
  `NumeroUnidades` int(3) NOT NULL DEFAULT '0',
  `CodigoCatastro` char(8) NOT NULL DEFAULT '' COMMENT 'Maestro de Catastro',
  `AreaFisicaCatastro` char(10) NOT NULL DEFAULT '',
  `MontoCatastro` double(11,2) NOT NULL DEFAULT '0.00',
  `GenerarVoucherIngresoFlag` char(1) NOT NULL DEFAULT '',
  `CodProveedor` varchar(6) NOT NULL DEFAULT '',
  `FacturaTipoDocumento` char(2) NOT NULL DEFAULT '' COMMENT 'Maestro Tipo Documento CxP',
  `FacturaNumeroDocumento` char(14) NOT NULL DEFAULT '',
  `FacturaFecha` date NOT NULL DEFAULT '0000-00-00',
  `NumeroOrden` char(15) NOT NULL DEFAULT '',
  `NumeroOrdenFecha` date NOT NULL DEFAULT '0000-00-00',
  `NumeroGuia` char(15) NOT NULL DEFAULT '',
  `NumeroGuiaFecha` date NOT NULL DEFAULT '0000-00-00',
  `NumeroDocAlmacen` char(15) NOT NULL DEFAULT '',
  `DocAlmacenFecha` date NOT NULL DEFAULT '0000-00-00',
  `InventarioFisicoFecha` date NOT NULL DEFAULT '0000-00-00',
  `InventarioFisicoComentario` varchar(300) NOT NULL DEFAULT '' COMMENT 'Para Activos Mayores',
  `FechaIngreso` date NOT NULL DEFAULT '0000-00-00',
  `PeriodoIngreso` char(7) NOT NULL DEFAULT '',
  `PeriodoInicioDepreciacion` char(7) NOT NULL DEFAULT '',
  `PeriodoInicioRevaluacion` char(7) NOT NULL DEFAULT '',
  `PeriodoBaja` char(7) NOT NULL DEFAULT '',
  `VoucherBaja` char(12) NOT NULL DEFAULT '',
  `MontoLocal` double(11,2) NOT NULL DEFAULT '0.00',
  `MontoReferencia` double(11,2) NOT NULL DEFAULT '0.00',
  `MontoMercado` double(11,2) NOT NULL DEFAULT '0.00',
  `VoucherIngreso` char(12) NOT NULL DEFAULT '',
  `Estado` char(2) NOT NULL DEFAULT '' COMMENT 'PE: Pendiente; AP: Aprobado',
  `UltimoUsuario` char(50) NOT NULL DEFAULT '',
  `UltimaFechaModif` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `SituacionActivo` char(2) NOT NULL DEFAULT '',
  `FlagParaMantenimiento` char(1) NOT NULL DEFAULT 'N' COMMENT 'S= Si',
  `FlagParaOperaciones` char(1) NOT NULL DEFAULT 'N' COMMENT 'S= Si',
  `DepreEspecificaFlag` char(1) NOT NULL DEFAULT '' COMMENT 'S=Si',
  `UnidadMedida` char(3) NOT NULL DEFAULT '',
  `OrigenActivo` char(2) NOT NULL DEFAULT '' COMMENT 'MA: Manual; AT: Automatico',
  `PreparadoPor` varchar(6) NOT NULL DEFAULT '',
  `Naturaleza` char(2) NOT NULL DEFAULT '' COMMENT 'AN: Activo Normal; ME: Activo Menor ',
  `EstadoRegistro` char(2) NOT NULL DEFAULT '' COMMENT 'A: Activo ;I:Inactivo',
  `DescpCorta` varchar(300) NOT NULL DEFAULT '' COMMENT 'para Activos Menores',
  `CodTipoMovimiento` varchar(2) NOT NULL DEFAULT '' COMMENT 'af_tipomovimientos',
  `FechaPreparacion` date NOT NULL DEFAULT '0000-00-00',
  `AprobadoPor` varchar(6) NOT NULL,
  `FechaRevisadoPor` date NOT NULL DEFAULT '0000-00-00',
  `NroIncorporacion` varchar(4) DEFAULT NULL,
  `NroActaEntrega` varchar(4) DEFAULT NULL,
  `RevisadoPor` varchar(6) DEFAULT NULL,
  `CargoRevisadoPor` varchar(4) DEFAULT NULL,
  `ConformadoPor` varchar(6) DEFAULT NULL,
  `CargoConformadoPor` varchar(4) DEFAULT NULL,
  `CargoAprobadoPor` varchar(4) DEFAULT NULL,
  `VoucherIngPub20` char(1) DEFAULT NULL,
  PRIMARY KEY (`Activo`,`CodOrganismo`,`CodigoInterno`),
  UNIQUE KEY `CodigoInterno` (`CodigoInterno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `af_activocaracteristica`
--

CREATE TABLE IF NOT EXISTS `af_activocaracteristica` (
  `Activo` varchar(10) NOT NULL DEFAULT '',
  `Secuencia` varchar(3) NOT NULL DEFAULT '',
  `CodCaractTecnica` varchar(4) NOT NULL DEFAULT '' COMMENT 'Maestro Caracteristica Tecnica',
  `Descripcion` varchar(30) NOT NULL DEFAULT '',
  `Observaciones` varchar(100) NOT NULL DEFAULT '',
  `Cantidad` double(11,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`Activo`,`Secuencia`,`CodCaractTecnica`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `af_activodetalle`
--

CREATE TABLE IF NOT EXISTS `af_activodetalle` (
  `Activo` varchar(10) NOT NULL DEFAULT '',
  `Secuencia` varchar(3) NOT NULL DEFAULT '',
  `CodTipoComp` varchar(4) NOT NULL DEFAULT '' COMMENT 'Maestro Componentes de un Equipo',
  `Descripcion` varchar(30) NOT NULL DEFAULT '',
  `NumeroSerie` varchar(50) NOT NULL DEFAULT '',
  `Marca` varchar(50) NOT NULL DEFAULT '',
  `FechaIngreso` date NOT NULL DEFAULT '0000-00-00',
  `CodigoBarras` varchar(15) NOT NULL DEFAULT '',
  PRIMARY KEY (`Activo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `af_activodistribcontable`
--

CREATE TABLE IF NOT EXISTS `af_activodistribcontable` (
  `Activo` varchar(10) NOT NULL,
  `TipoTransaccion` varchar(3) NOT NULL,
  `Contabilidad` varchar(3) NOT NULL,
  `Secuencia` varchar(3) NOT NULL,
  `CuentaContable` varchar(45) DEFAULT NULL,
  `Monto` double(11,2) DEFAULT NULL,
  `UltimoUsuario` varchar(15) DEFAULT NULL,
  `UltimaFechaModif` datetime DEFAULT NULL,
  PRIMARY KEY (`Activo`,`TipoTransaccion`,`Contabilidad`,`Secuencia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `af_activohistoricontable`
--

CREATE TABLE IF NOT EXISTS `af_activohistoricontable` (
  `CodActivo` varchar(10) NOT NULL DEFAULT '',
  `CodContabilidad` char(1) NOT NULL DEFAULT '',
  `Periodo` varchar(7) NOT NULL DEFAULT '',
  `DepreciacionPorcentaje` double(11,2) NOT NULL DEFAULT '0.00' COMMENT 'Monto',
  `LocalInicio` double(11,2) NOT NULL DEFAULT '0.00' COMMENT 'Monto',
  `LocalMesVariacion` double(11,2) NOT NULL DEFAULT '0.00' COMMENT 'Monto',
  `LocalAnoVariacion` double(11,2) NOT NULL DEFAULT '0.00' COMMENT 'Monto',
  `LocalMesFinal` double(11,2) NOT NULL DEFAULT '0.00' COMMENT 'Monto',
  `LocalDeprAnoInicio` double(11,2) NOT NULL DEFAULT '0.00' COMMENT 'Monto',
  `LocalDeprMesInicio` double(11,2) NOT NULL DEFAULT '0.00' COMMENT 'Monto',
  `LocalDeprMesVariacion` double(11,2) NOT NULL DEFAULT '0.00' COMMENT 'Monto',
  `LocalDeprMes` double(11,2) NOT NULL DEFAULT '0.00' COMMENT 'Monto',
  `LocalDeprAnoFinal` double(11,2) NOT NULL DEFAULT '0.00' COMMENT 'Monto',
  `LocalDeprAcum` double(11,2) NOT NULL DEFAULT '0.00' COMMENT 'Monto',
  `LocalNeto` double(11,2) NOT NULL DEFAULT '0.00' COMMENT 'Monto',
  `ALocalInicio` double(11,2) NOT NULL DEFAULT '0.00' COMMENT 'Monto',
  `ALocalMesReval` double(11,2) NOT NULL DEFAULT '0.00' COMMENT 'Monto',
  `ALocalAnoReval` double(11,2) NOT NULL DEFAULT '0.00' COMMENT 'Monto',
  `ALocalMesVariacion` double(11,2) NOT NULL DEFAULT '0.00' COMMENT 'Monto',
  `ALocalAnoVariacion` double(11,2) NOT NULL DEFAULT '0.00' COMMENT 'Monto',
  `ALocalMesFinal` double(11,2) NOT NULL DEFAULT '0.00' COMMENT 'Monto',
  `ALocalDeprAnoInicio` double(11,2) NOT NULL DEFAULT '0.00' COMMENT 'Monto',
  `ALocalDeprMesInicio` double(11,2) NOT NULL DEFAULT '0.00' COMMENT 'Monto',
  `ALocalDeprMesReval` double(11,2) NOT NULL DEFAULT '0.00' COMMENT 'Monto',
  `ALocalDeprAnoReval` double(11,2) NOT NULL DEFAULT '0.00' COMMENT 'Monto',
  `ALocalDeprMesVariacion` double(11,2) NOT NULL DEFAULT '0.00' COMMENT 'Monto',
  `ALocalDeprMes` double(11,2) NOT NULL DEFAULT '0.00' COMMENT 'Monto',
  `ALocalDeprAnoFinal` double(11,2) NOT NULL DEFAULT '0.00' COMMENT 'Monto',
  `ALocalDeprAcum` double(11,2) NOT NULL DEFAULT '0.00' COMMENT 'Monto',
  `ALocalAnoAcum` double(11,2) NOT NULL DEFAULT '0.00' COMMENT 'Monto',
  `ALocalAnoREI` double(11,2) NOT NULL DEFAULT '0.00' COMMENT 'Monto',
  `AlocalNeto` double(11,2) NOT NULL DEFAULT '0.00' COMMENT 'Monto',
  `ALocalDeprMesH` double(11,2) NOT NULL DEFAULT '0.00' COMMENT 'Monto',
  `ALocalDeprAcumH` double(11,2) NOT NULL DEFAULT '0.00' COMMENT 'Monto',
  `ALocalDeprAcumHTotal` double(11,2) NOT NULL DEFAULT '0.00' COMMENT 'Monto',
  `Origen` char(2) NOT NULL COMMENT 'M:Manual - A:Automatico',
  `UltimoUsuario` varchar(20) NOT NULL DEFAULT '',
  `UltimaFechaModif` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `VidaUtilTotal` varchar(3) NOT NULL DEFAULT '',
  `VidaUtilTrasncurrida` varchar(3) NOT NULL DEFAULT '',
  PRIMARY KEY (`CodActivo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `af_caracteristicatecnica`
--

CREATE TABLE IF NOT EXISTS `af_caracteristicatecnica` (
  `CodCaractTecnica` varchar(4) NOT NULL DEFAULT '',
  `DescripcionLocal` varchar(30) NOT NULL DEFAULT '',
  `Estado` char(1) NOT NULL DEFAULT '' COMMENT 'A: Activo; I:Inactivo',
  `UltimoUsuario` varchar(10) NOT NULL DEFAULT '',
  `UltimaFechaModif` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`CodCaractTecnica`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `af_catastro`
--

CREATE TABLE IF NOT EXISTS `af_catastro` (
  `CodCatastro` char(8) NOT NULL DEFAULT '',
  `Descripcion` char(30) NOT NULL DEFAULT '',
  `Estado` char(1) NOT NULL DEFAULT '',
  `UltimoUsuario` char(15) NOT NULL DEFAULT '',
  `UltimaFechaModif` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`CodCatastro`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `af_catastroanual`
--

CREATE TABLE IF NOT EXISTS `af_catastroanual` (
  `IdCatastroanual` char(5) NOT NULL DEFAULT '',
  `CodCatastro` char(8) NOT NULL DEFAULT '',
  `Ano` char(4) NOT NULL DEFAULT '',
  `PrecioOficial` double(11,2) NOT NULL DEFAULT '0.00',
  `PrecioMercado` double(11,2) NOT NULL DEFAULT '0.00',
  `FechaReferencia` date NOT NULL DEFAULT '0000-00-00',
  `UltimoUsuario` char(15) NOT NULL DEFAULT '',
  `UltimaFechaModif` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`IdCatastroanual`,`CodCatastro`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `af_categoriacontabilidad`
--

CREATE TABLE IF NOT EXISTS `af_categoriacontabilidad` (
  `CodCategoria` char(10) NOT NULL DEFAULT '',
  `CodContabilidad` char(1) NOT NULL DEFAULT '',
  `DepreciacionPorcentaje` double(11,2) NOT NULL DEFAULT '0.00',
  `VidaUtil` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`CodCategoria`,`CodContabilidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `af_categoriadeprec`
--

CREATE TABLE IF NOT EXISTS `af_categoriadeprec` (
  `CodCategoria` char(10) NOT NULL DEFAULT '',
  `DescripcionLocal` char(50) NOT NULL DEFAULT '',
  `CuentaHistorica` char(20) NOT NULL DEFAULT '',
  `CuentaHistoricaVariacion` char(20) NOT NULL DEFAULT '',
  `CuentaHistoricaRevaluacion` char(20) NOT NULL DEFAULT '',
  `CuentaDepreciacion` char(20) NOT NULL DEFAULT '',
  `CuentaDepreciacionVariacion` char(20) NOT NULL DEFAULT '',
  `CuentaDepreciacionRevaluacion` char(20) NOT NULL DEFAULT '',
  `CuentaGastos` char(20) NOT NULL DEFAULT '',
  `CuentaGastosRevaluacion` char(20) NOT NULL DEFAULT '',
  `CuentaNeto` char(20) NOT NULL DEFAULT '',
  `CuentaREI` char(20) NOT NULL DEFAULT '',
  `CuentaResultado` char(20) NOT NULL DEFAULT '',
  `InventariableFlag` char(1) NOT NULL DEFAULT 'N',
  `GrupoCateg` char(2) NOT NULL DEFAULT '' COMMENT 'AN: Activo Normal - AM: Activo Menor',
  `TipoDepreciacion` char(1) NOT NULL DEFAULT '' COMMENT 'L: Lineal - U: Por Unidades Producidas',
  `Estado` char(1) NOT NULL DEFAULT '',
  `UltimoUsuario` char(10) NOT NULL DEFAULT '',
  `UltimaFechaModif` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`CodCategoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `af_clasificacionactivo`
--

CREATE TABLE IF NOT EXISTS `af_clasificacionactivo` (
  `CodClasificacion` varchar(6) NOT NULL,
  `Descripcion` varchar(255) NOT NULL,
  `Nivel` int(1) NOT NULL,
  `Estado` varchar(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodClasificacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `af_clasificacionactivo20`
--

CREATE TABLE IF NOT EXISTS `af_clasificacionactivo20` (
  `CodClasificacion` varchar(10) NOT NULL,
  `Descripcion` varchar(255) NOT NULL,
  `Nivel` int(1) NOT NULL,
  `Estado` varchar(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodClasificacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `af_historicotransaccion`
--

CREATE TABLE IF NOT EXISTS `af_historicotransaccion` (
  `CodOrganismo` varchar(4) NOT NULL DEFAULT '',
  `Activo` varchar(10) NOT NULL DEFAULT '',
  `Secuencia` int(1) unsigned NOT NULL DEFAULT '0',
  `CodDependencia` varchar(4) NOT NULL DEFAULT '',
  `CentroCosto` varchar(4) NOT NULL DEFAULT '',
  `CodigoInterno` varchar(6) NOT NULL DEFAULT '',
  `SituacionActivo` varchar(2) NOT NULL DEFAULT '',
  `CodTipoMovimiento` varchar(2) NOT NULL DEFAULT '',
  `Ubicacion` varchar(4) NOT NULL DEFAULT '',
  `InternoExternoFlag` varchar(1) NOT NULL DEFAULT '' COMMENT 'I=Interno; E=Externo',
  `MotivoTraslado` varchar(2) NOT NULL DEFAULT '',
  `FechaIngreso` date NOT NULL DEFAULT '0000-00-00',
  `FechaBaja` date NOT NULL DEFAULT '0000-00-00',
  `FechaTransaccion` date NOT NULL DEFAULT '0000-00-00',
  `PeriodoIngreso` varchar(7) NOT NULL DEFAULT '',
  `PeriodoTransaccion` varchar(7) NOT NULL DEFAULT '',
  `PeriodoBaja` varchar(7) NOT NULL DEFAULT '',
  `NumeroOrden` varchar(10) NOT NULL DEFAULT '',
  `OrdenSecuencia` int(1) unsigned NOT NULL DEFAULT '0',
  `MontoActivo` double(11,2) NOT NULL DEFAULT '0.00',
  `UltimoUsuario` varchar(45) NOT NULL DEFAULT '',
  `UltimaFechaModif` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`CodOrganismo`,`Secuencia`,`Activo`,`CodDependencia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `af_movimientos`
--

CREATE TABLE IF NOT EXISTS `af_movimientos` (
  `Organismo` char(4) NOT NULL DEFAULT '',
  `MovimientoNumero` char(10) NOT NULL DEFAULT '',
  `PreparadoPor` char(6) NOT NULL DEFAULT '',
  `FechaPreparacion` date NOT NULL DEFAULT '0000-00-00',
  `Estado` char(2) NOT NULL DEFAULT '' COMMENT 'PR=En Preparacion; AP= Aprobado;AN=Anulado',
  `UltimoUsuario` char(15) NOT NULL DEFAULT '',
  `UltimaFechaModif` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `InternoExternoFlag` char(1) NOT NULL DEFAULT '',
  `MotivoTraslado` char(2) NOT NULL DEFAULT '',
  `Comentario` varchar(150) NOT NULL DEFAULT '',
  `AprobadoPor` char(6) NOT NULL DEFAULT '',
  `FechaAprobacion` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`Organismo`,`MovimientoNumero`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `af_movimientosdetalle`
--

CREATE TABLE IF NOT EXISTS `af_movimientosdetalle` (
  `Organismo` char(4) NOT NULL DEFAULT '',
  `Activo` char(10) NOT NULL DEFAULT '',
  `MovimientoNumero` char(10) NOT NULL DEFAULT '',
  `CentroCosto` char(4) NOT NULL DEFAULT '',
  `CentroCostoAnterior` char(4) NOT NULL DEFAULT '',
  `Ubicacion` char(4) NOT NULL DEFAULT '',
  `UbicacionAnterior` char(4) NOT NULL DEFAULT '',
  `Dependencia` char(4) NOT NULL DEFAULT '',
  `DependenciaAnterior` char(4) NOT NULL DEFAULT '',
  `EmpleadoUsuario` char(6) NOT NULL DEFAULT '',
  `EmpleadoUsuarioAnterior` char(6) NOT NULL DEFAULT '',
  `EmpleadoResponsable` char(6) NOT NULL DEFAULT '',
  `EmpleadoResponsableAnterior` char(6) NOT NULL DEFAULT '',
  `OrganismoActual` char(4) NOT NULL DEFAULT '',
  `Organismoanterior` char(4) NOT NULL DEFAULT '',
  PRIMARY KEY (`Organismo`,`Activo`,`MovimientoNumero`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `af_polizaseguro`
--

CREATE TABLE IF NOT EXISTS `af_polizaseguro` (
  `CodPolizaSeguro` char(8) NOT NULL DEFAULT '',
  `DescripcionLocal` char(30) NOT NULL DEFAULT '',
  `EmpresaAseguradora` char(50) NOT NULL DEFAULT '',
  `MontoCobertura` double(11,2) NOT NULL DEFAULT '0.00',
  `AgenteSeguros` varchar(60) NOT NULL DEFAULT '',
  `FechaVencimiento` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `CostoPoliza` double(11,2) NOT NULL DEFAULT '0.00',
  `Estado` char(1) NOT NULL DEFAULT '',
  `UltimoUsuario` char(10) NOT NULL DEFAULT '',
  `UltimaFechaModif` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`CodPolizaSeguro`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `af_situacionactivo`
--

CREATE TABLE IF NOT EXISTS `af_situacionactivo` (
  `CodSituActivo` char(2) NOT NULL DEFAULT '',
  `Descripcion` varchar(30) NOT NULL DEFAULT '',
  `DepreciacionFlag` char(1) NOT NULL DEFAULT 'N',
  `RevaluacionFlag` char(1) NOT NULL DEFAULT 'N',
  `TransaccionesdelSistemaFlag` char(1) NOT NULL DEFAULT 'N',
  `Estado` char(1) NOT NULL DEFAULT '',
  `UltimoUsuario` char(10) NOT NULL DEFAULT '',
  `UltimaFechaModif` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`CodSituActivo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `af_tipocomponente`
--

CREATE TABLE IF NOT EXISTS `af_tipocomponente` (
  `CodTipoComp` char(4) NOT NULL DEFAULT '',
  `DescripcionLocal` varchar(30) NOT NULL DEFAULT '',
  `Estado` char(1) NOT NULL DEFAULT '' COMMENT 'A:Activo; I:Inactivo',
  `UltimoUsuario` varchar(10) NOT NULL DEFAULT '',
  `UltimaFechaModif` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`CodTipoComp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `af_tipomovimientos`
--

CREATE TABLE IF NOT EXISTS `af_tipomovimientos` (
  `CodTipoMovimiento` varchar(2) NOT NULL DEFAULT '',
  `TipoMovimiento` varchar(2) NOT NULL DEFAULT '' COMMENT 'IN: Incorporacion, DE: Desincorporacion',
  `DescpMovimiento` varchar(150) NOT NULL DEFAULT '',
  `Estado` varchar(1) NOT NULL DEFAULT '' COMMENT 'A=Activo, I= inactivo',
  `UltimoUsuario` varchar(45) NOT NULL DEFAULT '',
  `UltimaFechaModif` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`CodTipoMovimiento`,`TipoMovimiento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `af_tiposeguro`
--

CREATE TABLE IF NOT EXISTS `af_tiposeguro` (
  `CodTipoSeguro` char(4) NOT NULL DEFAULT '',
  `Descripcion` char(30) NOT NULL DEFAULT '',
  `Estado` char(1) NOT NULL DEFAULT '',
  `UltimoUsuario` char(10) NOT NULL DEFAULT '',
  `UltimaFechaModif` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`CodTipoSeguro`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `af_tipotransaccion`
--

CREATE TABLE IF NOT EXISTS `af_tipotransaccion` (
  `TipoTransaccion` char(3) NOT NULL,
  `Descripcion` varchar(45) DEFAULT NULL,
  `FlagAltaBaja` char(2) DEFAULT NULL,
  `TipoVoucher` varchar(4) DEFAULT NULL COMMENT 'Maestro tipo voucher',
  `TransaccionesdelSistemaFlag` char(1) DEFAULT NULL,
  `Estado` char(2) DEFAULT NULL,
  `UltimoUsuario` varchar(15) DEFAULT NULL,
  `UltimaFechaModif` datetime DEFAULT NULL,
  PRIMARY KEY (`TipoTransaccion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `af_tipotranscuenta`
--

CREATE TABLE IF NOT EXISTS `af_tipotranscuenta` (
  `TipoTransaccion` char(3) NOT NULL,
  `Categoria` varchar(10) DEFAULT NULL,
  `Contabilidad` char(1) NOT NULL,
  `Secuencia` varchar(3) NOT NULL,
  `Descripcion` varchar(100) DEFAULT NULL,
  `CuentaContable` varchar(45) DEFAULT NULL,
  `MontoLocal` double(11,2) DEFAULT NULL,
  `SignoFlag` char(1) DEFAULT NULL,
  `UltimoUsuario` varchar(15) DEFAULT NULL,
  `UltimaFechaModif` datetime DEFAULT NULL,
  PRIMARY KEY (`TipoTransaccion`,`Secuencia`,`Contabilidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `af_tipovehiculo`
--

CREATE TABLE IF NOT EXISTS `af_tipovehiculo` (
  `CodTipoVehiculo` char(4) NOT NULL DEFAULT '',
  `Descripcion` char(30) NOT NULL DEFAULT '',
  `Estado` char(1) NOT NULL DEFAULT '',
  `UltimoUsuario` char(10) NOT NULL DEFAULT '',
  `UltimaFechaModif` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`CodTipoVehiculo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `af_transaccionbaja`
--

CREATE TABLE IF NOT EXISTS `af_transaccionbaja` (
  `Activo` varchar(10) NOT NULL,
  `Organismo` varchar(4) NOT NULL DEFAULT '',
  `Dependencia` varchar(4) DEFAULT NULL,
  `Contabilidad` varchar(1) DEFAULT NULL,
  `CentroCosto` varchar(4) DEFAULT NULL,
  `Ubicacion` varchar(4) DEFAULT NULL,
  `Responsable` varchar(4) DEFAULT NULL,
  `Fecha` date DEFAULT NULL,
  `TipoTransaccion` varchar(4) DEFAULT NULL COMMENT 'Maestro Tipo Transaccion',
  `ConceptoMovimiento` varchar(2) DEFAULT NULL,
  `LocalIngreso` double(11,2) DEFAULT NULL,
  `LocalAjustes` double(11,2) DEFAULT NULL,
  `LocalDepreciacion` double(11,2) DEFAULT NULL,
  `ALocalIngreso` double(11,2) DEFAULT NULL,
  `ALocalAjustes` double(11,2) DEFAULT NULL,
  `ALocalDepreciacion` double(11,2) DEFAULT NULL,
  `Comentario` varchar(350) DEFAULT NULL,
  `Periodo` varchar(7) DEFAULT NULL,
  `VoucherNo` varchar(6) DEFAULT NULL,
  `FacturaNumero` varchar(6) DEFAULT NULL,
  `ContabilizadoFlag` char(1) DEFAULT NULL,
  `AprobadoPor` varchar(4) DEFAULT NULL,
  `FechaAprobacion` datetime DEFAULT NULL,
  `Resolucion` varchar(100) DEFAULT NULL,
  `SituacionActivoOriginal` varchar(2) DEFAULT NULL,
  `Estado` varchar(2) DEFAULT NULL COMMENT 'PR: Preparado; AP:Aprobado; AN:Anulado',
  `UltimoUsuario` varchar(4) DEFAULT NULL,
  `UltimaFechaModif` datetime DEFAULT NULL,
  `Categoria` varchar(10) DEFAULT NULL,
  `CodigoInterno` varchar(6) DEFAULT NULL,
  `PreparadoPor` varchar(6) DEFAULT NULL,
  PRIMARY KEY (`Activo`,`Organismo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `af_transaccionbajacuenta`
--

CREATE TABLE IF NOT EXISTS `af_transaccionbajacuenta` (
  `Activo` varchar(10) NOT NULL,
  `Contabilidad` varchar(45) DEFAULT NULL COMMENT 'Maestro de Contabilidades',
  `Secuencia` int(11) DEFAULT NULL,
  `CuentaContable` varchar(20) DEFAULT NULL,
  `Descripcion` varchar(45) DEFAULT NULL,
  `MontoLocal` double(11,2) DEFAULT NULL,
  `MontoALocal` double(11,2) DEFAULT NULL,
  `Fecha` date DEFAULT NULL,
  PRIMARY KEY (`Activo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `af_ubicaciones`
--

CREATE TABLE IF NOT EXISTS `af_ubicaciones` (
  `CodUbicacion` varchar(4) NOT NULL,
  `Descripcion` varchar(100) NOT NULL,
  `Nivel` int(1) NOT NULL,
  `Estado` varchar(1) NOT NULL COMMENT 'A:ACTIVO; I:INACTIVO',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodUbicacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ap_bancotipotransaccion`
--

CREATE TABLE IF NOT EXISTS `ap_bancotipotransaccion` (
  `CodTipoTransaccion` varchar(4) NOT NULL,
  `Descripcion` varchar(50) NOT NULL,
  `TipoTransaccion` varchar(1) NOT NULL COMMENT 'I:INGRESO; E:EGRESO; T:TRANSFERENCIA',
  `FlagVoucher` varchar(1) NOT NULL DEFAULT 'N',
  `CodVoucher` varchar(2) NOT NULL COMMENT 'ac_voucher->CodVoucher',
  `CodCuenta` varchar(13) NOT NULL COMMENT 'ac_mastplancuenta->CodCuenta',
  `CodCuentaPub20` varchar(13) NOT NULL COMMENT 'ac_mastplancuenta20->CodCuenta',
  `FlagTransaccion` varchar(1) NOT NULL DEFAULT 'N',
  `FlagTransaccionPlanilla` varchar(1) NOT NULL DEFAULT 'N',
  `Estado` varchar(1) NOT NULL DEFAULT 'A' COMMENT 'A:ACTIVO; I:INACTIVO;',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  `ap_bancotipotransaccioncol` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`CodTipoTransaccion`),
  KEY `CodVoucher` (`CodVoucher`) USING BTREE,
  KEY `CodCuenta` (`CodCuenta`) USING BTREE,
  KEY `CodCuentaPub20` (`CodCuentaPub20`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ap_bancotransaccion`
--

CREATE TABLE IF NOT EXISTS `ap_bancotransaccion` (
  `NroTransaccion` varchar(5) NOT NULL,
  `Secuencia` int(10) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `CodTipoTransaccion` varchar(4) NOT NULL COMMENT 'ap_bancotipotransaccion->CodTipoTransaccion',
  `TipoTransaccion` varchar(1) NOT NULL COMMENT 'I:INGRESO; E:EGRESO; T:TRANSFERENCIA',
  `NroCuenta` varchar(20) NOT NULL COMMENT 'ap_ctabancaria->NroCuenta',
  `VoucherPeriodo` varchar(7) DEFAULT NULL,
  `Voucher` varchar(7) NOT NULL COMMENT 'ac_vouchermast->Voucher',
  `CodTipoDocumento` varchar(2) NOT NULL COMMENT 'ap_tipodocumento->CodTipoDocumento',
  `CodProveedor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `Responsable` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `CodCentroCosto` varchar(4) NOT NULL COMMENT 'ac_mastcentrocosto->CodCentroCosto',
  `PreparadoPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `FechaPreparacion` date NOT NULL,
  `FechaTransaccion` date NOT NULL,
  `PeriodoContable` varchar(7) NOT NULL,
  `Monto` decimal(11,2) DEFAULT NULL,
  `FlagGeneraVoucher` varchar(1) NOT NULL DEFAULT 'N',
  `FlagConciliacion` varchar(1) NOT NULL DEFAULT 'N',
  `FechaConciliacion` date NOT NULL,
  `CodigoReferenciaBanco` varchar(20) DEFAULT NULL,
  `CodigoReferenciaInterno` varchar(20) NOT NULL,
  `Comentarios` text NOT NULL,
  `CampoReferencia` varchar(12) NOT NULL,
  `PagoNroProceso` varchar(6) DEFAULT NULL COMMENT 'ap_pagos->NroProceso',
  `PagoSecuencia` int(2) DEFAULT NULL COMMENT 'ap_pagos->Secuencia',
  `NroPago` varchar(10) DEFAULT NULL COMMENT 'ap_pagos->NroPago',
  `Estado` varchar(2) NOT NULL COMMENT 'PR:PENDIENTE; AP:ACTUALIZADO; CO:CONTABILIZADO',
  `FlagPresupuesto` varchar(1) NOT NULL DEFAULT 'N',
  `CodPartida` varchar(12) DEFAULT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`NroTransaccion`,`Secuencia`),
  KEY `FK_ap_bancotransaccion_1` (`CodTipoTransaccion`),
  KEY `FK_ap_bancotransaccion_2` (`NroCuenta`),
  KEY `FK_ap_bancotransaccion_3` (`CodTipoDocumento`),
  KEY `CodOrganismo` (`CodOrganismo`),
  KEY `TipoTransaccion` (`TipoTransaccion`),
  KEY `Voucher` (`Voucher`),
  KEY `CodCentroCosto` (`CodCentroCosto`),
  KEY `CodProveedor` (`CodProveedor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ap_cajachica`
--

CREATE TABLE IF NOT EXISTS `ap_cajachica` (
  `FlagCajaChica` varchar(1) NOT NULL COMMENT 'C:Caja Chica; R:Reporte de Gasto',
  `Periodo` varchar(4) NOT NULL,
  `NroCajaChica` varchar(4) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `CodDependencia` varchar(4) NOT NULL COMMENT 'mastdependencias->CodDependencia',
  `CodResponsable` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `CodClasificacion` varchar(2) NOT NULL COMMENT 'ap_clasificaciongastos->CodClasificacion',
  `CodCentroCosto` varchar(4) NOT NULL COMMENT 'ac_mastcentrocosto->CodCentroCosto',
  `CodBeneficiario` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `CodPersonaPagar` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `NomPersonaPagar` varchar(100) NOT NULL,
  `CodTipoDocumento` varchar(2) NOT NULL COMMENT 'ap_obligaciones->CodTipoDocumento',
  `NroDocumento` varchar(20) NOT NULL COMMENT 'ap_obligaciones->NroDocumento',
  `NroCuenta` varchar(20) NOT NULL COMMENT 'ap_ctabancariadefault->NroCuenta',
  `CodTipoPago` varchar(2) NOT NULL COMMENT 'masttipopago->CodTipoPago',
  `PreparadoPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `AprobadoPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `FechaPreparacion` date NOT NULL,
  `FechaAprobacion` date NOT NULL,
  `Descripcion` text NOT NULL,
  `RazonRechazo` text NOT NULL,
  `MontoTotal` decimal(11,2) NOT NULL DEFAULT '0.00',
  `MontoAdelantos` decimal(11,2) NOT NULL DEFAULT '0.00',
  `MontoNeto` decimal(11,2) NOT NULL DEFAULT '0.00',
  `NroDocumentoInterno` varchar(20) NOT NULL,
  `FechaPago` date NOT NULL,
  `Voucher` varchar(7) NOT NULL,
  `Estado` varchar(2) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`FlagCajaChica`,`Periodo`,`NroCajaChica`),
  KEY `FK_ap_cajachica_1` (`CodClasificacion`),
  KEY `FK_ap_cajachica_2` (`CodCentroCosto`),
  KEY `FK_ap_cajachica_3` (`NroCuenta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ap_cajachicaautorizacion`
--

CREATE TABLE IF NOT EXISTS `ap_cajachicaautorizacion` (
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `CodEmpleado` varchar(6) NOT NULL COMMENT 'mastempleado->CodEmpleado',
  `Monto` decimal(11,2) NOT NULL,
  `Estado` varchar(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodOrganismo`,`CodEmpleado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ap_cajachicadetalle`
--

CREATE TABLE IF NOT EXISTS `ap_cajachicadetalle` (
  `FlagCajaChica` varchar(1) NOT NULL COMMENT 'C:Caja Chica; R:Reporte de Gasto',
  `Periodo` varchar(4) NOT NULL,
  `NroCajaChica` varchar(4) NOT NULL,
  `Secuencia` int(4) NOT NULL,
  `CodConceptoGasto` varchar(4) NOT NULL COMMENT 'ap_conceptogastos->CodConceptoGasto',
  `Fecha` date NOT NULL,
  `Descripcion` text NOT NULL,
  `CodRegimenFiscal` varchar(2) NOT NULL COMMENT 'ap_regimenfiscal->CodRegimenFiscal',
  `DocFiscal` varchar(20) NOT NULL,
  `CodProveedor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `NomProveedor` varchar(100) NOT NULL,
  `CodTipoDocumento` varchar(2) NOT NULL COMMENT 'ap_obligaciones->CodTipoDocumento',
  `NroDocumento` varchar(20) NOT NULL COMMENT 'ap_obligaciones->NroDocumento',
  `FechaDocumento` date NOT NULL,
  `MontoAfecto` decimal(11,2) NOT NULL,
  `MontoNoAfecto` decimal(11,2) NOT NULL,
  `MontoImpuesto` decimal(11,2) NOT NULL,
  `MontoRetencion` decimal(11,2) NOT NULL,
  `MontoPagado` decimal(11,2) NOT NULL,
  `PeriodoRegistrocompra` varchar(4) NOT NULL,
  `CodTipoServicio` varchar(5) NOT NULL COMMENT 'masttiposervicio->CodTipoServicio',
  `Comentarios` text NOT NULL,
  `NroRecibo` varchar(20) NOT NULL,
  `FlagNoAfectoIGV` varchar(1) NOT NULL,
  `CodEmpleado` varchar(6) NOT NULL COMMENT 'mastempleado->CodEmpleado',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`FlagCajaChica`,`Periodo`,`NroCajaChica`,`Secuencia`),
  KEY `FK_ap_cajachicadetalle_1` (`CodConceptoGasto`),
  KEY `FK_ap_cajachicadetalle_2` (`CodRegimenFiscal`),
  KEY `FK_ap_cajachicadetalle_3` (`CodTipoServicio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ap_cajachicadistribucion`
--

CREATE TABLE IF NOT EXISTS `ap_cajachicadistribucion` (
  `FlagCajaChica` varchar(1) NOT NULL COMMENT 'C:Caja Chica; R:Reporte de Gasto',
  `Periodo` varchar(4) NOT NULL,
  `NroCajaChica` varchar(4) NOT NULL,
  `Secuencia` int(4) NOT NULL,
  `Linea` int(4) NOT NULL,
  `CodConceptoGasto` varchar(4) NOT NULL COMMENT 'ap_conceptogastos->CodConceptoGasto',
  `Monto` decimal(11,2) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `CodCentroCosto` varchar(4) NOT NULL COMMENT 'ac_mastcentrocosto->CodCentroCosto',
  `CodPartida` varchar(12) NOT NULL COMMENT 'pv_partida->CodPartida',
  `CodCuenta` varchar(13) NOT NULL COMMENT 'ac_mastplancuenta->CodCuenta',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`FlagCajaChica`,`Periodo`,`NroCajaChica`,`Secuencia`,`Linea`),
  KEY `FK_ap_cajachicadistribucion_1` (`CodPartida`),
  KEY `FK_ap_cajachicadistribucion_2` (`CodCuenta`),
  KEY `FK_ap_cajachicadistribucion_3` (`CodConceptoGasto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ap_clasificaciongastos`
--

CREATE TABLE IF NOT EXISTS `ap_clasificaciongastos` (
  `CodClasificacion` varchar(2) NOT NULL,
  `Descripcion` varchar(50) NOT NULL,
  `Aplicacion` varchar(2) NOT NULL COMMENT 'CC:CAJA CHICA; RG:REPORTE GASTOS; AP:ADELANTO A PROVEEDORES',
  `Estado` varchar(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodClasificacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ap_conceptoclasificaciongastos`
--

CREATE TABLE IF NOT EXISTS `ap_conceptoclasificaciongastos` (
  `CodConceptoGasto` varchar(4) NOT NULL COMMENT 'ap_conceptogastos->CodConceptoGasto',
  `CodClasificacion` varchar(2) NOT NULL COMMENT 'ap_conceptogastogrupo->CodGastoGrupo',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodConceptoGasto`,`CodClasificacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ap_conceptogastogrupo`
--

CREATE TABLE IF NOT EXISTS `ap_conceptogastogrupo` (
  `CodGastoGrupo` varchar(3) NOT NULL,
  `Descripcion` varchar(100) NOT NULL,
  `Estado` varchar(1) NOT NULL DEFAULT 'A' COMMENT 'A:ACTIVO; I:INACTIVO;',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodGastoGrupo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ap_conceptogastos`
--

CREATE TABLE IF NOT EXISTS `ap_conceptogastos` (
  `CodConceptoGasto` varchar(4) NOT NULL,
  `CodGastoGrupo` varchar(3) NOT NULL COMMENT 'ap_conceptogastogrupo->CodGastoGrupo',
  `Descripcion` varchar(150) NOT NULL,
  `CodPartida` varchar(12) NOT NULL COMMENT 'pv_partida->cod_partida',
  `CodCuenta` varchar(13) NOT NULL COMMENT 'ac_mastplancuenta->CodCuenta',
  `CodCuentaPub20` varchar(13) NOT NULL COMMENT 'ac_mastplancuenta20->CodCuenta',
  `Estado` varchar(1) NOT NULL DEFAULT 'A' COMMENT 'A:ACTIVO; I:INACTIVO;',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodConceptoGasto`),
  KEY `FK_ap_conceptogastos_1` (`CodPartida`),
  KEY `FK_ap_conceptogastos_3` (`CodGastoGrupo`),
  KEY `CodCuenta` (`CodCuenta`) USING BTREE,
  KEY `CodCuentaPub20` (`CodCuentaPub20`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ap_ctabancaria`
--

CREATE TABLE IF NOT EXISTS `ap_ctabancaria` (
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `CodBanco` varchar(4) NOT NULL COMMENT 'mastbancos->CodBanco',
  `NroCuenta` varchar(20) NOT NULL DEFAULT '',
  `Descripcion` varchar(100) NOT NULL,
  `CtaBanco` varchar(20) NOT NULL DEFAULT '',
  `TipoCuenta` varchar(2) NOT NULL COMMENT 'miscelaneos->TIPOCTA',
  `FechaApertura` date NOT NULL,
  `CodCuenta` varchar(13) NOT NULL COMMENT 'ac_mastplancuenta->CodCuenta',
  `CodCuentaPub20` varchar(13) NOT NULL COMMENT 'ac_mastplancuenta20->CodCuenta',
  `Agencia` varchar(100) NOT NULL,
  `Distrito` varchar(100) NOT NULL,
  `Atencion` varchar(100) NOT NULL,
  `Cargo` varchar(100) NOT NULL,
  `FlagConciliacionBancaria` varchar(1) NOT NULL DEFAULT 'N',
  `FlagConciliacionCP` varchar(1) NOT NULL DEFAULT 'N',
  `FlagDebitoBancario` varchar(1) NOT NULL DEFAULT 'N',
  `PeriodoConciliacion` varchar(7) NOT NULL,
  `Estado` varchar(1) NOT NULL DEFAULT 'A' COMMENT 'A:ACTIVO; I:INACTIVO',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  `ap_ctabancariacol` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`NroCuenta`),
  KEY `CodOrganismo` (`CodOrganismo`),
  KEY `CodBanco` (`CodBanco`),
  KEY `FK_ap_ctabancaria_1_idx` (`CodOrganismo`) USING BTREE,
  KEY `FK_ap_ctabancaria_2_idx` (`CodBanco`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ap_ctabancariabalance`
--

CREATE TABLE IF NOT EXISTS `ap_ctabancariabalance` (
  `NroCuenta` varchar(20) NOT NULL DEFAULT '',
  `FechaTransaccion` date NOT NULL,
  `MontoTransaccion` decimal(11,2) NOT NULL,
  `SaldoAnterior` decimal(11,2) NOT NULL,
  `SaldoActual` decimal(11,2) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`NroCuenta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ap_ctabancariadefault`
--

CREATE TABLE IF NOT EXISTS `ap_ctabancariadefault` (
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `NroCuenta` varchar(20) NOT NULL COMMENT 'ap_ctabancaria->NroCuenta',
  `CodTipoPago` varchar(2) NOT NULL COMMENT 'masttipopago->CodTipoPago',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodOrganismo`,`CodTipoPago`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ap_ctabancariatipopago`
--

CREATE TABLE IF NOT EXISTS `ap_ctabancariatipopago` (
  `NroCuenta` varchar(20) NOT NULL COMMENT 'ap_ctabancaria->NroCuenta',
  `CodTipoPago` varchar(2) NOT NULL COMMENT 'masttipopago->CodTipoPago',
  `UltimoNumero` int(10) NOT NULL DEFAULT '0',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`NroCuenta`,`CodTipoPago`),
  KEY `FK_ap_ctabancariatipopago_1_idx` (`NroCuenta`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ap_distribucionobligacion`
--

CREATE TABLE IF NOT EXISTS `ap_distribucionobligacion` (
  `CodProveedor` varchar(6) NOT NULL COMMENT 'mastproveedores->CodProveedor',
  `CodTipoDocumento` varchar(2) NOT NULL COMMENT 'ap_tipodocumento->CodTipoDocumento',
  `CodPresupuesto` varchar(4) NOT NULL,
  `NroDocumento` varchar(20) NOT NULL,
  `CodCuenta` varchar(13) NOT NULL COMMENT 'ac_mastplancuenta->CodCuenta',
  `cod_partida` varchar(12) NOT NULL COMMENT 'pv_partida->cod_partida',
  `CodCentroCosto` varchar(4) NOT NULL COMMENT 'ac_mastcentrocosto->CodCentroCosto',
  `CodCuentaPub20` varchar(13) DEFAULT NULL COMMENT 'ac_mastplancuenta20->CodCuenta',
  `Monto` decimal(11,2) NOT NULL,
  `Periodo` varchar(7) DEFAULT NULL,
  `FlagCompromiso` varchar(1) NOT NULL DEFAULT 'N',
  `Anio` year(4) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL DEFAULT '0001',
  `Origen` varchar(2) DEFAULT NULL COMMENT 'OB:OBLIGACIONES; TB:TRANSACCIONES BANCARIAS;',
  `Estado` varchar(2) DEFAULT 'CA' COMMENT 'OB:OBLIGACIONES; TB:TRANSACCIONES BANCARIAS;',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodProveedor`,`CodTipoDocumento`,`NroDocumento`,`CodCuenta`,`cod_partida`,`CodCentroCosto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Disparadores `ap_distribucionobligacion`
--
DROP TRIGGER IF EXISTS `ap_distribucionobligacion_triger1`;
DELIMITER //
CREATE TRIGGER `ap_distribucionobligacion_triger1` AFTER INSERT ON `ap_distribucionobligacion`
 FOR EACH ROW BEGIN
	SET @Monto = (SELECT SUM(Monto)
				  FROM ap_distribucionobligacion
				  WHERE
						CodOrganismo = NEW.CodOrganismo AND
						CodPresupuesto = NEW.CodPresupuesto AND
						cod_partida = NEW.cod_partida AND
						Estado = 'CA');

	UPDATE pv_presupuestodet
	SET MontoCausado = @Monto
	WHERE
		Organismo = NEW.CodOrganismo AND
		CodPresupuesto = NEW.CodPresupuesto AND
		cod_partida = NEW.cod_partida;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `ap_distribucionobligacion_triger2`;
DELIMITER //
CREATE TRIGGER `ap_distribucionobligacion_triger2` AFTER UPDATE ON `ap_distribucionobligacion`
 FOR EACH ROW BEGIN
	SET @Monto = (SELECT SUM(Monto)
				  FROM ap_distribucionobligacion
				  WHERE
						CodOrganismo = NEW.CodOrganismo AND
						CodPresupuesto = NEW.CodPresupuesto AND
						cod_partida = NEW.cod_partida AND
						Estado = 'CA');

	UPDATE pv_presupuestodet
	SET MontoCausado = @Monto
	WHERE
		Organismo = NEW.CodOrganismo AND
		CodPresupuesto = NEW.CodPresupuesto AND
		cod_partida = NEW.cod_partida;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `ap_distribucionobligacion_triger3`;
DELIMITER //
CREATE TRIGGER `ap_distribucionobligacion_triger3` AFTER DELETE ON `ap_distribucionobligacion`
 FOR EACH ROW BEGIN
	SET @Monto = (SELECT SUM(Monto)
				  FROM ap_distribucionobligacion
				  WHERE
						CodOrganismo = OLD.CodOrganismo AND
						CodPresupuesto = OLD.CodPresupuesto AND
						cod_partida = OLD.cod_partida AND
						Estado = 'CA');

	UPDATE pv_presupuestodet
	SET MontoCausado = @Monto
	WHERE
		Organismo = OLD.CodOrganismo AND
		CodPresupuesto = OLD.CodPresupuesto AND
		cod_partida = OLD.cod_partida;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ap_documentos`
--

CREATE TABLE IF NOT EXISTS `ap_documentos` (
  `Anio` varchar(4) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `CodProveedor` varchar(6) NOT NULL COMMENT 'mastproveedores->CodProveedor',
  `DocumentoClasificacion` varchar(3) NOT NULL COMMENT 'ap_documentosclasificacion->DocumentoClasificacion',
  `DocumentoReferencia` varchar(20) NOT NULL,
  `Fecha` date NOT NULL,
  `ReferenciaTipoDocumento` varchar(2) NOT NULL COMMENT 'OC:ORDEN DE COMPRA; OS:ORDEN DE SERVICIO',
  `ReferenciaNroDocumento` varchar(10) NOT NULL,
  `MontoAfecto` decimal(11,2) NOT NULL DEFAULT '0.00',
  `MontoNoAfecto` decimal(11,2) NOT NULL DEFAULT '0.00',
  `MontoImpuestos` decimal(11,2) NOT NULL DEFAULT '0.00',
  `MontoTotal` decimal(11,2) NOT NULL DEFAULT '0.00',
  `MontoPendiente` decimal(11,2) DEFAULT '0.00',
  `MontoPagado` decimal(11,2) DEFAULT '0.00',
  `Estado` varchar(2) NOT NULL DEFAULT 'PR' COMMENT 'PR:DISPONIBLE PARA PAGAR; RV:PAGADO;',
  `TransaccionTipoDocumento` varchar(2) NOT NULL DEFAULT '' COMMENT 'Obligaciones',
  `TransaccionNroDocumento` varchar(10) NOT NULL DEFAULT '' COMMENT 'Obligaciones',
  `Comentarios` text NOT NULL,
  `ObligacionTipoDocumento` varchar(2) NOT NULL COMMENT 'ap_obligaciones->TipoDocumento',
  `ObligacionNroDocumento` varchar(20) NOT NULL DEFAULT '' COMMENT 'ap_obligaciones->NroDocumento',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Anio`,`CodProveedor`,`DocumentoClasificacion`,`DocumentoReferencia`),
  KEY `CodOrganismo` (`CodOrganismo`),
  KEY `ReferenciaTipoDocumento` (`ReferenciaTipoDocumento`),
  KEY `ReferenciaNroDocumento` (`ReferenciaNroDocumento`),
  KEY `ObligacionTipoDocumento` (`TransaccionTipoDocumento`),
  KEY `ObligacionNroDocumento` (`TransaccionNroDocumento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ap_documentosclasificacion`
--

CREATE TABLE IF NOT EXISTS `ap_documentosclasificacion` (
  `DocumentoClasificacion` varchar(3) NOT NULL,
  `Descripcion` varchar(50) NOT NULL,
  `FlagTransaccion` varchar(1) NOT NULL,
  `Estado` varchar(1) NOT NULL COMMENT 'A:ACTIVO; I:INACTIVO',
  `FlagItem` varchar(1) NOT NULL,
  `FlagCliente` varchar(1) NOT NULL,
  `FlagCompromiso` varchar(1) NOT NULL,
  `CodCuenta` varchar(13) NOT NULL COMMENT 'ac_mastplancuenta->CodCuenta',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`DocumentoClasificacion`),
  KEY `CodCuenta` (`CodCuenta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ap_documentosdetalle`
--

CREATE TABLE IF NOT EXISTS `ap_documentosdetalle` (
  `Anio` varchar(4) NOT NULL,
  `CodProveedor` varchar(6) NOT NULL COMMENT 'mastproveedores->CodProveedor',
  `DocumentoClasificacion` varchar(3) NOT NULL COMMENT 'ap_documentosclasificacion->DocumentoClasificacion',
  `DocumentoReferencia` varchar(20) NOT NULL,
  `Secuencia` int(4) NOT NULL,
  `ReferenciaSecuencia` int(4) NOT NULL,
  `CodItem` varchar(10) NOT NULL COMMENT 'lg_itemmast->CodItem',
  `CommoditySub` varchar(6) NOT NULL COMMENT 'lg_commoditysub->CommoditySub',
  `Descripcion` varchar(255) NOT NULL,
  `Cantidad` decimal(11,2) NOT NULL DEFAULT '0.00',
  `PrecioUnit` decimal(11,2) NOT NULL,
  `PrecioCantidad` decimal(11,2) NOT NULL,
  `Total` decimal(11,2) NOT NULL,
  `CodCentroCosto` varchar(4) NOT NULL COMMENT 'ac_mastcentrocosto->CodCentroCosto',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Anio`,`CodProveedor`,`DocumentoClasificacion`,`DocumentoReferencia`,`Secuencia`),
  KEY `CodItem` (`CodItem`),
  KEY `CommoditySub` (`CommoditySub`),
  KEY `CodCentroCosto` (`CodCentroCosto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ap_obligaciones`
--

CREATE TABLE IF NOT EXISTS `ap_obligaciones` (
  `CodProveedor` varchar(6) NOT NULL,
  `CodTipoDocumento` varchar(2) NOT NULL COMMENT 'ap_tipodocumento->CodTipoDocumento',
  `NroDocumento` varchar(25) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `NroCuenta` varchar(20) NOT NULL COMMENT 'ap_ctabancariadefault->NroCuenta',
  `CodTipoPago` varchar(2) NOT NULL COMMENT 'masttipopago->CodTipoPago',
  `CodResponsable` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `FechaRegistro` date NOT NULL,
  `FechaVencimiento` date NOT NULL,
  `FlagGenerarPago` varchar(1) NOT NULL DEFAULT 'S',
  `CodTipoServicio` varchar(5) NOT NULL COMMENT 'masttiposervicio->CodTipoServicio',
  `ReferenciaTipoDocumento` varchar(2) NOT NULL COMMENT 'OC:ORDEN DE COMPRA; OS:ORDEN DE SERVICIO',
  `ReferenciaNroDocumento` varchar(100) NOT NULL COMMENT 'NRO. DE LA ORDEN',
  `MontoObligacion` decimal(11,2) NOT NULL,
  `MontoImpuestoOtros` decimal(11,2) NOT NULL,
  `MontoNoAfecto` decimal(11,2) NOT NULL,
  `MontoAfecto` decimal(11,2) NOT NULL,
  `MontoAdelanto` decimal(11,2) NOT NULL,
  `MontoImpuesto` decimal(11,2) NOT NULL,
  `MontoPagoParcial` decimal(11,2) NOT NULL,
  `IngresadoPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `RevisadoPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `AprobadoPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `FlagContabilizacionPendiente` varchar(1) NOT NULL DEFAULT 'S',
  `FlagContPendientePub20` varchar(1) NOT NULL DEFAULT 'S',
  `Voucher` varchar(7) NOT NULL,
  `VoucherPub20` varchar(7) NOT NULL,
  `NroPago` varchar(10) NOT NULL,
  `NroProceso` varchar(6) NOT NULL COMMENT 'ap_pagos->NroProceso',
  `ProcesoSecuencia` int(2) NOT NULL COMMENT 'ap_pagos->Secuencia',
  `NroRegistro` varchar(6) NOT NULL,
  `Comentarios` longtext NOT NULL,
  `ComentariosAdicional` longtext NOT NULL,
  `CodCentroCosto` varchar(4) NOT NULL COMMENT 'ac_mastcentrocosto->CodCentroCosto',
  `FechaRecepcion` date NOT NULL,
  `CodProveedorPagar` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `FechaDocumento` date NOT NULL,
  `FlagAfectoIGV` varchar(1) NOT NULL DEFAULT 'N',
  `FlagDiferido` varchar(1) NOT NULL DEFAULT 'N',
  `FlagAdelanto` varchar(1) NOT NULL DEFAULT 'N',
  `FlagPagoDiferido` varchar(1) NOT NULL DEFAULT 'N',
  `Estado` varchar(2) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  `FlagCompromiso` varchar(1) NOT NULL DEFAULT 'S',
  `FlagPresupuesto` varchar(1) NOT NULL DEFAULT 'S',
  `FlagObligacionAuto` varchar(1) NOT NULL DEFAULT 'S',
  `FlagObligacionDirecta` varchar(1) NOT NULL DEFAULT 'N',
  `FlagCajaChica` varchar(1) NOT NULL DEFAULT 'N',
  `FlagPagoIndividual` varchar(1) NOT NULL DEFAULT 'N',
  `NroControl` varchar(20) NOT NULL,
  `FechaProgramada` date NOT NULL,
  `FechaPreparacion` date NOT NULL,
  `FechaAprobado` date NOT NULL,
  `FechaRevision` date NOT NULL,
  `Periodo` varchar(7) NOT NULL,
  `FechaPago` date DEFAULT NULL,
  `CodCuenta` varchar(13) NOT NULL COMMENT 'ac_mastplancuenta->CodCuenta',
  `CodCuentaPub20` varchar(13) DEFAULT NULL COMMENT 'ac_mastplancuenta20->CodCuenta',
  `FlagDistribucionManual` varchar(1) DEFAULT 'N',
  `FechaFactura` date NOT NULL,
  `AnuladoPor` varchar(6) DEFAULT NULL,
  `FechaAnulacion` date DEFAULT NULL,
  `MotivoAnulacion` text,
  `PeriodoAnulacion` varchar(7) DEFAULT NULL,
  `VoucherAnulacion` varchar(7) DEFAULT NULL,
  `FlagVerificado` varchar(1) NOT NULL DEFAULT 'N',
  `FechaConformado` date DEFAULT NULL,
  `ConformadoPor` varchar(8) DEFAULT NULL,
  PRIMARY KEY (`CodProveedor`,`CodTipoDocumento`,`NroDocumento`),
  KEY `CodOrganismo` (`CodOrganismo`),
  KEY `NroCuenta` (`NroCuenta`),
  KEY `CodTipoPago` (`CodTipoPago`),
  KEY `CodResponsable` (`CodResponsable`),
  KEY `CodTipoServicio` (`CodTipoServicio`),
  KEY `CodCentroCosto` (`CodCentroCosto`),
  KEY `NroPago` (`NroPago`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ap_obligacionescuenta`
--

CREATE TABLE IF NOT EXISTS `ap_obligacionescuenta` (
  `CodProveedor` varchar(6) NOT NULL,
  `CodTipoDocumento` varchar(2) NOT NULL COMMENT 'ap_tipodocumento->CodTipoDocumento',
  `NroDocumento` varchar(25) NOT NULL,
  `Secuencia` int(4) NOT NULL,
  `Linea` int(4) NOT NULL,
  `Descripcion` varchar(255) NOT NULL,
  `Monto` decimal(11,2) NOT NULL,
  `CodCentroCosto` varchar(4) NOT NULL COMMENT 'ac_mastcentrocosto->CodCentroCosto',
  `CodCuenta` varchar(13) NOT NULL COMMENT 'ac_mastplancuenta->CodCuenta',
  `CodCuentaPub20` varchar(45) DEFAULT NULL COMMENT 'ac_mastplancuenta20->CodCuenta',
  `cod_partida` varchar(12) NOT NULL COMMENT 'pv_partida->cod_partida',
  `TipoOrden` varchar(2) NOT NULL COMMENT 'OC:ORDEN DE COMPRA; OS:ORDEN DE SERVICIO;',
  `NroOrden` varchar(100) NOT NULL COMMENT 'NRO. DE LA ORDEN',
  `FlagNoAfectoIGV` varchar(1) NOT NULL DEFAULT 'N',
  `Referencia` varchar(25) DEFAULT NULL,
  `CodPersona` varchar(6) DEFAULT NULL COMMENT 'mastpersonas->CodPersona',
  `NroActivo` varchar(15) DEFAULT NULL,
  `FlagDiferido` varchar(1) DEFAULT 'N',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodProveedor`,`CodTipoDocumento`,`NroDocumento`,`Secuencia`,`Linea`) USING BTREE,
  KEY `FK_CodCentroCosto` (`CodCentroCosto`),
  KEY `FK_CodCuenta` (`CodCuenta`),
  KEY `FK_cod_partida` (`cod_partida`),
  KEY `NroOrden` (`NroOrden`,`TipoOrden`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ap_obligacionesimpuesto`
--

CREATE TABLE IF NOT EXISTS `ap_obligacionesimpuesto` (
  `CodProveedor` varchar(6) NOT NULL COMMENT 'mastproveedores->CodProveedor',
  `CodTipoDocumento` varchar(2) NOT NULL COMMENT 'ap_tipodocumento->CodTipoDocumento',
  `NroDocumento` varchar(25) NOT NULL,
  `Linea` int(2) NOT NULL,
  `CodImpuesto` varchar(3) NOT NULL COMMENT 'mastimpuestos->CodImpuesto',
  `CodConcepto` varchar(4) NOT NULL COMMENT 'pr_concepto->CodConcepto',
  `FactorPorcentaje` decimal(11,2) NOT NULL,
  `MontoImpuesto` decimal(11,2) NOT NULL,
  `MontoAfecto` decimal(11,2) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  `CodCuenta` varchar(13) NOT NULL,
  `CodCuentaPub20` varchar(13) NOT NULL,
  `FlagProvision` varchar(1) NOT NULL DEFAULT '' COMMENT 'P:PAGO DEL DOCUMENTO; N:PROVISION DEL DOCUMENTO;',
  PRIMARY KEY (`CodProveedor`,`CodTipoDocumento`,`NroDocumento`,`Linea`),
  KEY `Index_2` (`CodCuenta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ap_ordenpago`
--

CREATE TABLE IF NOT EXISTS `ap_ordenpago` (
  `Anio` varchar(4) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL,
  `NroOrden` varchar(10) NOT NULL,
  `CodAplicacion` varchar(10) NOT NULL COMMENT 'mastaplicaciones->CodAplicacion',
  `CodProveedor` varchar(6) NOT NULL COMMENT 'mastproveedores->CodProveedor',
  `CodTipoDocumento` varchar(2) NOT NULL COMMENT 'ap_tipodocumento->CodTipoDocumento',
  `NroDocumento` varchar(25) NOT NULL COMMENT 'ap_obligaciones',
  `NroCuenta` varchar(20) NOT NULL COMMENT 'ap_ctabancariadefault->NroCuenta',
  `CodTipoPago` varchar(2) NOT NULL COMMENT 'masttipopago->CodTipoPago',
  `FechaVencimiento` date NOT NULL,
  `FechaDocumento` date NOT NULL,
  `CodProveedorPagar` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `NomProveedorPagar` varchar(100) DEFAULT NULL,
  `Concepto` longtext NOT NULL,
  `Voucher` varchar(7) NOT NULL,
  `VoucherDocumento` varchar(7) NOT NULL,
  `FlagChequeIndividual` varchar(1) NOT NULL DEFAULT 'N',
  `FechaOrdenPago` date NOT NULL,
  `MontoTotal` decimal(11,2) NOT NULL,
  `LetraProveedor` int(5) NOT NULL,
  `LetraSecuencia` int(5) NOT NULL,
  `FechaTransferencia` date NOT NULL,
  `CodCentroCosto` varchar(4) NOT NULL COMMENT 'ac_mastcentrocosto->CodCentroCosto',
  `NroRegistro` varchar(6) NOT NULL,
  `FlagSuspension` varchar(1) NOT NULL DEFAULT 'N',
  `FlagPagoDiferido` varchar(1) NOT NULL DEFAULT 'N',
  `NroPago` varchar(10) NOT NULL,
  `Periodo` varchar(7) NOT NULL,
  `FechaProgramada` date DEFAULT NULL,
  `CodSistemaFuente` varchar(10) DEFAULT NULL COMMENT 'ac_sistemafuente->CodSistemaFuente',
  `FechaVencimientoReal` date DEFAULT NULL,
  `Estado` varchar(2) DEFAULT 'PE' COMMENT 'PE:PENDIENTE; GE:GENERADO; PA:PAGADO; AN:ANULADO;',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  `VoucherDocPeriodo` varchar(7) NOT NULL,
  `RevisadoPor` varchar(6) NOT NULL,
  `FechaRevisado` date NOT NULL,
  `AprobadoPor` varchar(6) NOT NULL,
  `VoucherPagoAnulacion` varchar(7) DEFAULT NULL,
  `PeriodoPagoAnulacion` varchar(7) DEFAULT NULL,
  `VoucherDocAnulacion` varchar(7) DEFAULT NULL,
  `PeriodoDocAnulacion` varchar(7) DEFAULT NULL,
  `FechaAnulacion` date DEFAULT NULL,
  `AnuladoPor` varchar(6) DEFAULT NULL,
  `MotivoAnulacion` text,
  PRIMARY KEY (`Anio`,`CodOrganismo`,`NroOrden`),
  KEY `NroPago` (`NroPago`),
  KEY `CodProveedor` (`CodProveedor`),
  KEY `CodProveedorPagar` (`CodProveedorPagar`),
  KEY `CodTipoPago` (`CodTipoPago`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ap_ordenpagocontabilidad`
--

CREATE TABLE IF NOT EXISTS `ap_ordenpagocontabilidad` (
  `Anio` varchar(4) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL,
  `NroOrden` varchar(10) NOT NULL,
  `Secuencia` int(10) unsigned NOT NULL,
  `CodContabilidad` varchar(1) NOT NULL,
  `CodCuenta` varchar(13) NOT NULL,
  `CodCuentaPub20` varchar(13) NOT NULL,
  `Monto` decimal(11,2) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Anio`,`CodOrganismo`,`NroOrden`,`Secuencia`,`CodContabilidad`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ap_ordenpagodistribucion`
--

CREATE TABLE IF NOT EXISTS `ap_ordenpagodistribucion` (
  `Anio` varchar(4) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL,
  `CodPresupuesto` varchar(4) NOT NULL,
  `NroOrden` varchar(20) NOT NULL,
  `Linea` int(5) NOT NULL,
  `CodProveedor` varchar(6) NOT NULL COMMENT 'mastproveedores->CodProveedor',
  `CodTipoDocumento` varchar(2) NOT NULL COMMENT 'ap_tipodocumento->CodTipoDocumento',
  `NroDocumento` varchar(25) NOT NULL COMMENT 'ap_obligaciones',
  `Monto` decimal(11,2) NOT NULL,
  `CodCentroCosto` varchar(4) NOT NULL COMMENT 'ac_mastcentrocosto->CodCentroCosto',
  `CodCuenta` varchar(13) NOT NULL COMMENT 'ac_mastplancuenta->CodCuenta',
  `CodCuentaPub20` varchar(13) NOT NULL,
  `cod_partida` varchar(12) NOT NULL COMMENT 'pv_partida->cod_partida',
  `FlagNoAfectoIGV` varchar(1) NOT NULL DEFAULT 'N',
  `Periodo` varchar(7) DEFAULT NULL,
  `Origen` varchar(2) DEFAULT NULL COMMENT 'OP: ORDEN DE PAGO; TB:TRANSACCIONES BANCARIAS;',
  `Estado` varchar(2) DEFAULT 'PA' COMMENT 'PE->PENDIENTE; PA->PAGADO; AN->ANULADO;',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Anio`,`CodOrganismo`,`NroOrden`,`Linea`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Disparadores `ap_ordenpagodistribucion`
--
DROP TRIGGER IF EXISTS `ap_ordenpagodistribucion_triger1`;
DELIMITER //
CREATE TRIGGER `ap_ordenpagodistribucion_triger1` AFTER INSERT ON `ap_ordenpagodistribucion`
 FOR EACH ROW BEGIN
	SET @Monto = (SELECT SUM(Monto)
				  FROM ap_ordenpagodistribucion
				  WHERE
						CodOrganismo = NEW.CodOrganismo AND
						CodPresupuesto = NEW.CodPresupuesto AND
						cod_partida = NEW.cod_partida AND
						Estado = 'PA');

	UPDATE pv_presupuestodet
	SET MontoPagado = @Monto
	WHERE
		Organismo = NEW.CodOrganismo AND
		CodPresupuesto = NEW.CodPresupuesto AND
		cod_partida = NEW.cod_partida;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `ap_ordenpagodistribucion_triger2`;
DELIMITER //
CREATE TRIGGER `ap_ordenpagodistribucion_triger2` AFTER UPDATE ON `ap_ordenpagodistribucion`
 FOR EACH ROW BEGIN
	SET @Monto = (SELECT SUM(Monto)
				  FROM ap_ordenpagodistribucion
				  WHERE
						CodOrganismo = NEW.CodOrganismo AND
						CodPresupuesto = NEW.CodPresupuesto AND
						cod_partida = NEW.cod_partida AND
						Estado = 'PA');

	UPDATE pv_presupuestodet
	SET MontoPagado = @Monto
	WHERE
		Organismo = NEW.CodOrganismo AND
		CodPresupuesto = NEW.CodPresupuesto AND
		cod_partida = NEW.cod_partida;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `ap_ordenpagodistribucion_triger3`;
DELIMITER //
CREATE TRIGGER `ap_ordenpagodistribucion_triger3` AFTER DELETE ON `ap_ordenpagodistribucion`
 FOR EACH ROW BEGIN
	SET @Monto = (SELECT SUM(Monto)
				  FROM ap_ordenpagodistribucion
				  WHERE
						CodOrganismo = OLD.CodOrganismo AND
						CodPresupuesto = OLD.CodPresupuesto AND
						cod_partida = OLD.cod_partida AND
						Estado = 'PA');

	UPDATE pv_presupuestodet
	SET MontoPagado = @Monto
	WHERE
		Organismo = OLD.CodOrganismo AND
		CodPresupuesto = OLD.CodPresupuesto AND
		cod_partida = OLD.cod_partida;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ap_pagos`
--

CREATE TABLE IF NOT EXISTS `ap_pagos` (
  `NroProceso` varchar(6) NOT NULL,
  `Secuencia` int(2) NOT NULL,
  `CodTipoPago` varchar(2) NOT NULL COMMENT 'masttipopago->CodTipoPago',
  `NroCuenta` varchar(20) NOT NULL COMMENT 'ap_ctabancariadefault->NroCuenta',
  `CodProveedor` varchar(6) NOT NULL COMMENT 'mastproveedores->CodProveedor',
  `Anio` varchar(4) NOT NULL COMMENT 'ap_ordenpago',
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'ap_ordenpago',
  `NroOrden` varchar(10) NOT NULL COMMENT 'ap_ordenpago',
  `DepositoBanco` varchar(4) NOT NULL COMMENT 'mastbancos->CodBanco',
  `DepositoCuenta` varchar(20) NOT NULL COMMENT 'ap_ctabancariadefault->NroCuenta',
  `NroPago` varchar(10) NOT NULL,
  `NomProveedorPagar` varchar(100) DEFAULT NULL,
  `FechaPago` date NOT NULL,
  `FechaEntregado` date NOT NULL,
  `FechaCobranza` date NOT NULL,
  `FechaAnulacion` date NOT NULL,
  `FechaEmisionDiferido` date NOT NULL,
  `Periodo` varchar(7) NOT NULL,
  `VoucherPago` varchar(7) NOT NULL,
  `VoucherAnulacion` varchar(7) NOT NULL,
  `PeriodoAnulacion` varchar(7) DEFAULT NULL,
  `MotivoAnulacion` varchar(100) DEFAULT NULL,
  `AnuladoPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `ReemplazadoPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `ChequeCargo` varchar(3) NOT NULL,
  `CodSistemaFuente` varchar(10) DEFAULT NULL COMMENT 'ac_sistemafuente->CodSistemaFuente',
  `NroPagoVoucher` varchar(20) NOT NULL,
  `NroCertificado` varchar(14) NOT NULL,
  `DescripcionChequeManual` varchar(14) NOT NULL,
  `TipoUso` varchar(1) NOT NULL,
  `NroFormulario` varchar(15) NOT NULL,
  `NroValija` varchar(6) NOT NULL,
  `PlazaEntrega` varchar(15) NOT NULL,
  `OrigenGeneracion` varchar(1) NOT NULL,
  `FlagContabilizacionPendiente` varchar(1) NOT NULL DEFAULT 'S',
  `FlagNegociacion` varchar(1) NOT NULL DEFAULT 'N',
  `FlagNoNegociable` varchar(1) NOT NULL DEFAULT 'N',
  `FlagCobrado` varchar(1) NOT NULL DEFAULT 'N',
  `FlagCertificadoImpresion` varchar(1) NOT NULL DEFAULT 'N',
  `FlagPagoDiferido` varchar(1) NOT NULL DEFAULT 'N',
  `MontoPago` decimal(11,2) DEFAULT NULL,
  `MontoRetenido` decimal(11,2) DEFAULT NULL,
  `Estado` varchar(2) NOT NULL COMMENT 'GE:GENERADO; IM:IMPRESO; AN:ANULADO;',
  `EstadoEntrega` varchar(1) NOT NULL COMMENT 'C:CUSTODIA; E:ENTREGADO;',
  `EstadoChequeManual` varchar(2) NOT NULL,
  `GeneradoPor` varchar(6) DEFAULT NULL,
  `ConformadoPor` varchar(6) DEFAULT NULL,
  `AprobadoPor` varchar(6) DEFAULT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`NroProceso`,`Secuencia`),
  KEY `CodTipoPago` (`CodTipoPago`),
  KEY `CodOrganismo` (`CodOrganismo`),
  KEY `NroCuenta` (`NroCuenta`),
  KEY `CodProveedor` (`CodProveedor`),
  KEY `NroOrden` (`Anio`,`CodOrganismo`,`NroOrden`),
  KEY `DepositoBanco` (`DepositoBanco`),
  KEY `DepositoCuenta` (`DepositoCuenta`),
  KEY `CodSistemaFuente` (`CodSistemaFuente`),
  KEY `NroPago` (`NroPago`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ap_regimenfiscal`
--

CREATE TABLE IF NOT EXISTS `ap_regimenfiscal` (
  `CodRegimenFiscal` varchar(2) NOT NULL,
  `Descripcion` varchar(25) NOT NULL,
  `Estado` varchar(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodRegimenFiscal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ap_registrocompras`
--

CREATE TABLE IF NOT EXISTS `ap_registrocompras` (
  `Periodo` varchar(7) NOT NULL,
  `SistemaFuente` varchar(4) NOT NULL COMMENT 'CF:CAJA CHICA; CP:CTAS. POR PAGAR; MA:MANUAL;',
  `Secuencia` int(2) NOT NULL,
  `CodProveedor` varchar(6) NOT NULL,
  `CodTipoDocumento` varchar(2) NOT NULL COMMENT 'ap_tipodocumento->CodTipoDocumento',
  `NroDocumento` varchar(25) NOT NULL,
  `NomProveedor` varchar(100) NOT NULL,
  `RifProveedor` varchar(20) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `FechaDocumento` date NOT NULL,
  `Voucher` varchar(7) NOT NULL,
  `VoucherPeriodo` varchar(7) DEFAULT NULL,
  `TipoDocNotaCredito` varchar(2) NOT NULL,
  `DocNotaCredito` varchar(12) NOT NULL,
  `FechaNotaCredito` date NOT NULL,
  `TipoDocNotaDebito` varchar(2) NOT NULL,
  `DocNotaDebito` varchar(12) NOT NULL,
  `FechaNotaDebito` date NOT NULL,
  `DocServicios` varchar(12) NOT NULL,
  `DocCodigoFiscal` varchar(2) NOT NULL,
  `NroRegistro` varchar(6) NOT NULL,
  `FlagCajaChica` varchar(1) NOT NULL COMMENT 'C:Caja Chica; R:Reporte de Gasto',
  `NroCajaChica` varchar(4) NOT NULL,
  `EstadoDocumento` varchar(2) NOT NULL,
  `Comentarios` longtext NOT NULL,
  `CampoAdicional1` varchar(45) NOT NULL,
  `CampoAdicional2` varchar(45) NOT NULL,
  `NroDocumentoInterno` varchar(15) NOT NULL,
  `MontoImponible` decimal(11,2) NOT NULL,
  `MontoNoAfecto` decimal(11,2) NOT NULL,
  `MontoImpuestoVentas` decimal(11,2) NOT NULL,
  `MontoCreditoFiscal` decimal(11,2) NOT NULL,
  `MontoObligacion` decimal(11,2) NOT NULL,
  `FiscalImponible` decimal(11,2) NOT NULL,
  `FiscalNoAfecto` decimal(11,2) NOT NULL,
  `FiscalImpuestoVentas` decimal(11,2) NOT NULL,
  `FiscalObligacion` decimal(11,2) NOT NULL,
  `ImponibleGravado` decimal(11,2) NOT NULL,
  `ImponibleGravadoMixto` decimal(11,2) NOT NULL,
  `ImponibleErrado` decimal(11,2) NOT NULL,
  `NoAfectoAdquisiciones` decimal(11,2) NOT NULL,
  `NoAfectoOtros` decimal(11,2) NOT NULL,
  `IGVGravado` decimal(11,2) NOT NULL,
  `IGVGravadoMixto` decimal(11,2) NOT NULL,
  `IGVErrado` decimal(11,2) NOT NULL,
  `ISC` decimal(11,2) NOT NULL,
  `ImponibleImportacion` decimal(11,2) NOT NULL,
  `IGVImportacion` decimal(11,2) NOT NULL,
  `FiscalImpuestoRetenido` decimal(11,2) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Periodo`,`SistemaFuente`,`Secuencia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ap_retenciones`
--

CREATE TABLE IF NOT EXISTS `ap_retenciones` (
  `CodOrganismo` varchar(4) NOT NULL,
  `NroOrden` varchar(10) NOT NULL COMMENT 'ap_ordenpago->NroOrden',
