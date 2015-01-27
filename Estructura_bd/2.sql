CREATE TABLE IF NOT EXISTS `lg_verificarimpuordenser` (
  `Anio` varchar(4) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL,
  `NroOrden` varchar(10) NOT NULL,
  `CodPersona` varchar(6) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `UltimoUsuario` varchar(25) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `UltimaFechaModif` datetime NOT NULL,
  PRIMARY KEY (`Anio`,`CodOrganismo`,`NroOrden`,`CodPersona`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=48;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_verificarpresuordencom`
--

CREATE TABLE IF NOT EXISTS `lg_verificarpresuordencom` (
  `Anio` varchar(4) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL,
  `NroOrden` varchar(10) NOT NULL,
  `CodPersona` varchar(6) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `UltimoUsuario` varchar(25) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `UltimaFechaModif` datetime NOT NULL,
  PRIMARY KEY (`Anio`,`CodOrganismo`,`NroOrden`,`CodPersona`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=48;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_verificarpresuordenser`
--

CREATE TABLE IF NOT EXISTS `lg_verificarpresuordenser` (
  `Anio` varchar(4) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL,
  `NroOrden` varchar(10) NOT NULL,
  `CodPersona` varchar(6) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `UltimoUsuario` varchar(25) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `UltimaFechaModif` datetime NOT NULL,
  PRIMARY KEY (`Anio`,`CodOrganismo`,`NroOrden`,`CodPersona`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=48;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mastaplicaciones`
--

CREATE TABLE IF NOT EXISTS `mastaplicaciones` (
  `CodAplicacion` varchar(10) NOT NULL,
  `Descripcion` varchar(30) NOT NULL,
  `PeriodoContable` varchar(7) NOT NULL,
  `PrefVoucherPD` varchar(2) NOT NULL COMMENT 'ac_voucher->CodVoucher',
  `PrefVoucherPA` varchar(2) NOT NULL COMMENT 'ac_voucher->CodVoucher',
  `PrefVoucherLP` varchar(2) NOT NULL COMMENT 'ac_voucher->CodVoucher',
  `PrefVoucherTB` varchar(2) NOT NULL COMMENT 'ac_voucher->CodVoucher',
  `CodSistemaFuente` varchar(10) NOT NULL COMMENT 'ac_sistemafuente->CodSistemaFuente',
  `Estado` char(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodAplicacion`),
  KEY `ik_mastaplicaciones_1` (`PrefVoucherPD`),
  KEY `ik_mastaplicaciones_2` (`PrefVoucherPA`),
  KEY `ik_mastaplicaciones_3` (`PrefVoucherLP`),
  KEY `ik_mastaplicaciones_4` (`PrefVoucherTB`),
  KEY `ik_mastaplicaciones_5` (`CodSistemaFuente`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mastbancos`
--

CREATE TABLE IF NOT EXISTS `mastbancos` (
  `CodBanco` varchar(4) NOT NULL,
  `CodPersona` varchar(6) NOT NULL,
  `Banco` varchar(50) NOT NULL DEFAULT '',
  `Estado` char(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodBanco`),
  KEY `CodPersona` (`CodPersona`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mastciudades`
--

CREATE TABLE IF NOT EXISTS `mastciudades` (
  `CodCiudad` varchar(4) NOT NULL,
  `CodPostal` varchar(10) NOT NULL DEFAULT '',
  `CodMunicipio` varchar(4) NOT NULL,
  `Ciudad` varchar(100) NOT NULL,
  `Estado` varchar(1) NOT NULL DEFAULT 'A' COMMENT 'A:ACTIVO; I:INACTIVO',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodCiudad`),
  KEY `CodMunicipio` (`CodMunicipio`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mastdependencias`
--

CREATE TABLE IF NOT EXISTS `mastdependencias` (
  `CodDependencia` varchar(4) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL,
  `Dependencia` varchar(100) NOT NULL,
  `Telefono1` varchar(15) NOT NULL,
  `Telefono2` varchar(15) NOT NULL,
  `Extencion1` varchar(4) NOT NULL,
  `Extencion2` varchar(4) NOT NULL,
  `CodPersona` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `CodInterno` varchar(20) NOT NULL,
  `Estructura` varchar(20) NOT NULL,
  `EstructuraPadre` varchar(20) DEFAULT NULL,
  `CodEstructura` varchar(2) NOT NULL,
  `EntidadPadre` varchar(4) DEFAULT NULL,
  `Nivel` int(2) DEFAULT NULL,
  `FlagControlFiscal` varchar(1) DEFAULT 'N',
  `FlagPrincipal` varchar(1) DEFAULT 'S',
  `Estado` varchar(1) DEFAULT 'A' COMMENT 'A:ACTIVO; I:INACTIVO',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodDependencia`,`CodOrganismo`),
  KEY `CodOrganismo` (`CodOrganismo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mastdivisiones`
--

CREATE TABLE IF NOT EXISTS `mastdivisiones` (
  `CodDivision` varchar(4) NOT NULL,
  `CodDependencia` varchar(4) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL,
  `Division` varchar(100) NOT NULL,
  `Extencion` varchar(4) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodDivision`,`CodDependencia`,`CodOrganismo`),
  KEY `fk_clave` (`CodDependencia`,`CodOrganismo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mastempleado`
--

CREATE TABLE IF NOT EXISTS `mastempleado` (
  `CodEmpleado` varchar(6) NOT NULL,
  `CodPersona` varchar(6) NOT NULL,
  `CodTitulo` varchar(4) NOT NULL,
  `CodTipoTrabajador` varchar(2) NOT NULL,
  `CodMotivoCes` varchar(2) NOT NULL,
  `CodTipoPago` varchar(2) NOT NULL,
  `CodPerfil` varchar(2) NOT NULL,
  `CodCargo` varchar(4) NOT NULL,
  `CodDivision` varchar(4) NOT NULL,
  `CodDependencia` varchar(4) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL,
  `Fingreso` date NOT NULL,
  `SueldoAnterior` decimal(11,2) DEFAULT NULL,
  `SueldoActual` decimal(11,2) NOT NULL DEFAULT '0.00',
  `CodTipoNom` varchar(2) NOT NULL,
  `NivelInstruccion` varchar(30) NOT NULL,
  `Fegreso` date DEFAULT NULL,
  `Fliquidacion` date NOT NULL,
  `Observacion` varchar(50) NOT NULL,
  `Estado` char(1) NOT NULL,
  `ObsCese` tinytext,
  `FechaJubilacion` date NOT NULL,
  `MontoJubilacion` decimal(11,2) NOT NULL,
  `CodCarnetProv` varchar(6) NOT NULL,
  `CodCargoTemp` varchar(4) NOT NULL,
  `CodCentroCosto` varchar(4) NOT NULL COMMENT 'ac_mastcentrocosto->CodCentroCosto',
  `NombreSobreViviente` varchar(100) NOT NULL,
  `CedulaSobreViviente` varchar(20) NOT NULL,
  `NombreSobrevivienteOtro` varchar(200) NOT NULL,
  `CedulaSobrevivienteOtro` varchar(20) NOT NULL,
  `Usuario` varchar(20) DEFAULT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `Ultimafecha` datetime NOT NULL,
  `UltimaPlanilla` datetime NOT NULL,
  `CodDependenciaTemp` varchar(4) DEFAULT NULL,
  `ReferenciaNombre` varchar(100) DEFAULT NULL,
  `CodHorario` varchar(3) DEFAULT NULL COMMENT 'rh_horariolaboral->CodHorario',
  PRIMARY KEY (`CodEmpleado`,`CodPersona`),
  KEY `mastempleado_ibfk_1` (`CodTipoNom`),
  KEY `mastempleado_ibfk_2` (`CodCargo`),
  KEY `mastempleado_ibfk_3` (`CodPerfil`),
  KEY `mastempleado_ibfk_4` (`CodPersona`),
  KEY `mastempleado_ibfk_5` (`CodOrganismo`),
  KEY `mastempleado_ibfk_6` (`CodDependencia`),
  KEY `mastempleado_ibfk_7` (`CodDivision`),
  KEY `mastempleado_ibfk_8` (`CodTipoPago`),
  KEY `mastempleado_ibfk_9` (`CodMotivoCes`),
  KEY `mastempleado_ibfk_11` (`CodTitulo`),
  KEY `FK_mastempleado_7` (`CodTipoTrabajador`),
  KEY `CodCentroCosto` (`CodCentroCosto`),
  KEY `CodHorario` (`CodHorario`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mastestados`
--

CREATE TABLE IF NOT EXISTS `mastestados` (
  `CodEstado` varchar(4) NOT NULL,
  `Estado` varchar(100) NOT NULL,
  `CodPais` varchar(4) NOT NULL,
  `Status` varchar(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodEstado`),
  KEY `CodPais` (`CodPais`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mastformapago`
--

CREATE TABLE IF NOT EXISTS `mastformapago` (
  `CodFormaPago` varchar(3) NOT NULL,
  `Descripcion` varchar(25) NOT NULL,
  `FlagCredito` varchar(1) NOT NULL,
  `DiasVence` int(3) NOT NULL,
  `Estado` varchar(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodFormaPago`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mastidioma`
--

CREATE TABLE IF NOT EXISTS `mastidioma` (
  `CodIdioma` varchar(3) NOT NULL,
  `DescripcionLocal` varchar(25) NOT NULL,
  `DescripcionExtra` varchar(25) NOT NULL,
  `Estado` char(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodIdioma`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mastimpuestos`
--

CREATE TABLE IF NOT EXISTS `mastimpuestos` (
  `CodImpuesto` varchar(3) NOT NULL,
  `Descripcion` varchar(25) NOT NULL,
  `CodRegimenFiscal` varchar(2) NOT NULL COMMENT 'ap_regimenfiscal->CodRegimenFiscal',
  `Signo` varchar(1) NOT NULL COMMENT 'P:POSITIVO; N:NEGATIVO;',
  `CodCuenta` varchar(13) NOT NULL COMMENT 'ac_mastplancuenta->CodCuenta',
  `CodCuentaPub20` varchar(13) NOT NULL COMMENT 'ac_mastplancuenta20->CodCuenta',
  `FactorPorcentaje` decimal(11,2) NOT NULL,
  `FlagProvision` varchar(1) NOT NULL COMMENT 'P:PAGO DEL DOCUMENTO; N:PROVISION DEL DOCUMENTO',
  `FlagImponible` varchar(1) NOT NULL COMMENT 'N:MONTO AFECTO; I:MONTO IGV/IVA',
  `TipoComprobante` varchar(10) NOT NULL COMMENT 'IVA; ISLR; 1X1000;',
  `FlagGeneral` varchar(1) NOT NULL COMMENT 'G:GENERAL; R:REDUCIDO; A:ADICIONAL;',
  `Estado` varchar(1) NOT NULL COMMENT 'A:ACTIVO; I:INACTIVO;',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodImpuesto`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mastmiscelaneoscab`
--

CREATE TABLE IF NOT EXISTS `mastmiscelaneoscab` (
  `CodMaestro` varchar(10) NOT NULL,
  `CodAplicacion` varchar(10) NOT NULL,
  `Descripcion` varchar(100) NOT NULL DEFAULT '',
  `Estado` varchar(1) NOT NULL DEFAULT 'A' COMMENT 'A:ACTIVO; I:INACTIVO',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodMaestro`,`CodAplicacion`),
  KEY `mastmiscelaneoscab_ibfk_1` (`CodAplicacion`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mastmiscelaneosdet`
--

CREATE TABLE IF NOT EXISTS `mastmiscelaneosdet` (
  `CodDetalle` varchar(2) NOT NULL,
  `CodMaestro` varchar(10) NOT NULL,
  `CodAplicacion` varchar(10) NOT NULL,
  `Descripcion` varchar(100) NOT NULL DEFAULT '',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  `Estado` varchar(1) NOT NULL DEFAULT 'A' COMMENT 'A:ACTIVO; I:INACTIVO',
  PRIMARY KEY (`CodDetalle`,`CodMaestro`,`CodAplicacion`),
  KEY `fk_llaves` (`CodMaestro`,`CodAplicacion`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mastmunicipios`
--

CREATE TABLE IF NOT EXISTS `mastmunicipios` (
  `CodMunicipio` varchar(4) NOT NULL,
  `CodEstado` varchar(4) NOT NULL,
  `Municipio` varchar(100) NOT NULL,
  `Estado` varchar(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodMunicipio`),
  KEY `CodEstado` (`CodEstado`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mastorganismos`
--

CREATE TABLE IF NOT EXISTS `mastorganismos` (
  `CodOrganismo` varchar(4) NOT NULL,
  `Organismo` varchar(100) NOT NULL,
  `CodPersona` varchar(6) NOT NULL,
  `DescripComp` varchar(100) NOT NULL DEFAULT '',
  `RepresentLegal` varchar(100) NOT NULL,
  `DocRepreLeg` varchar(20) NOT NULL,
  `PaginaWeb` varchar(100) NOT NULL DEFAULT '',
  `DocFiscal` varchar(20) NOT NULL,
  `FechaFundac` date NOT NULL,
  `Direccion` text NOT NULL,
  `CodCiudad` varchar(4) NOT NULL,
  `Telefono1` varchar(15) NOT NULL,
  `Telefono2` varchar(15) NOT NULL,
  `Telefono3` varchar(15) NOT NULL,
  `Fax1` varchar(15) NOT NULL,
  `Fax2` varchar(15) NOT NULL,
  `Logo` varchar(30) NOT NULL DEFAULT '',
  `Estado` varchar(1) NOT NULL,
  `NumReg` varchar(10) NOT NULL,
  `TomoReg` varchar(5) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodOrganismo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mastpaises`
--

CREATE TABLE IF NOT EXISTS `mastpaises` (
  `CodPais` varchar(4) NOT NULL,
  `Pais` varchar(100) NOT NULL,
  `Observaciones` varchar(100) NOT NULL,
  `Estado` varchar(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodPais`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mastparametros`
--

CREATE TABLE IF NOT EXISTS `mastparametros` (
  `ParametroClave` varchar(20) NOT NULL,
  `TipoValor` varchar(1) NOT NULL,
  `ValorParam` varchar(100) NOT NULL DEFAULT '',
  `Estado` varchar(1) NOT NULL,
  `DescripcionParam` varchar(100) NOT NULL,
  `Explicacion` varchar(255) NOT NULL DEFAULT '',
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `CodAplicacion` varchar(10) NOT NULL COMMENT 'mastaplicaciones->CodAplicacion',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`ParametroClave`),
  KEY `mastparametros_ibfk_1` (`CodOrganismo`),
  KEY `mastparametros_ibfk_2` (`CodAplicacion`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mastpersonas`
--

CREATE TABLE IF NOT EXISTS `mastpersonas` (
  `CodPersona` varchar(6) NOT NULL,
  `Apellido1` varchar(25) DEFAULT NULL,
  `Apellido2` varchar(25) NOT NULL DEFAULT '',
  `Nombres` varchar(50) NOT NULL DEFAULT '',
  `Busqueda` varchar(100) NOT NULL DEFAULT '',
  `Nacionalidad` varchar(2) NOT NULL,
  `NomCompleto` varchar(100) NOT NULL DEFAULT '',
  `EstadoCivil` varchar(2) NOT NULL,
  `Sexo` char(1) NOT NULL,
  `Fnacimiento` date NOT NULL,
  `CiudadNacimiento` varchar(4) NOT NULL,
  `FedoCivil` date DEFAULT NULL,
  `Direccion` tinytext NOT NULL,
  `Telefono1` varchar(15) NOT NULL,
  `Telefono2` varchar(15) DEFAULT NULL,
  `CiudadDomicilio` varchar(4) NOT NULL,
  `Fax` varchar(15) DEFAULT NULL,
  `Lnacimiento` varchar(150) NOT NULL,
  `NomEmerg1` varchar(100) NOT NULL DEFAULT '',
  `DirecEmerg1` varchar(255) NOT NULL DEFAULT '',
  `TelefEmerg1` varchar(15) NOT NULL,
  `DocFiscal` varchar(20) DEFAULT NULL,
  `TipoPersona` varchar(1) NOT NULL DEFAULT '',
  `Estado` char(1) NOT NULL,
  `Ndocumento` varchar(20) NOT NULL,
  `EsCliente` char(1) DEFAULT NULL,
  `CelEmerg1` varchar(15) DEFAULT NULL,
  `EsProveedor` char(1) DEFAULT NULL,
  `ParentEmerg1` varchar(2) NOT NULL,
  `NomEmerg2` varchar(100) DEFAULT NULL,
  `EsEmpleado` char(1) DEFAULT NULL,
  `EsOtros` char(1) DEFAULT NULL,
  `DirecEmerg2` varchar(255) DEFAULT NULL,
  `TelefEmerg2` varchar(15) DEFAULT NULL,
  `CelEmerg2` varchar(15) DEFAULT NULL,
  `SituacionDomicilio` varchar(2) NOT NULL,
  `ParentEmerg2` varchar(2) DEFAULT NULL,
  `TipoDocumento` varchar(2) NOT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `Foto` varchar(255) DEFAULT NULL,
  `GrupoSanguineo` varchar(2) DEFAULT NULL,
  `Observacion` varchar(200) DEFAULT NULL,
  `TipoLicencia` varchar(2) DEFAULT NULL,
  `Nlicencia` varchar(30) DEFAULT NULL,
  `ExpiraLicencia` date DEFAULT NULL,
  `SiAuto` char(1) DEFAULT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodPersona`),
  KEY `mastpersonas_ibfk_1` (`CiudadNacimiento`),
  KEY `mastpersonas_ibfk_2` (`CiudadDomicilio`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mastproveedores`
--

CREATE TABLE IF NOT EXISTS `mastproveedores` (
  `CodProveedor` varchar(6) NOT NULL,
  `CodTipoDocumento` varchar(2) NOT NULL COMMENT 'ap_tipodocumento->CodTipoDocumento',
  `CodTipoPago` varchar(2) NOT NULL COMMENT 'masttipopago->CodTipoPago',
  `CodFormaPago` varchar(3) NOT NULL COMMENT 'mastformapago->CodFormaPago',
  `CodTipoServicio` varchar(5) NOT NULL COMMENT 'masttiposervicio->CodTipoServicio',
  `DiasPago` int(4) DEFAULT NULL,
  `RegistroPublico` varchar(20) DEFAULT NULL,
  `LicenciaMunicipal` varchar(20) DEFAULT NULL,
  `FechaConstitucion` date NOT NULL,
  `RepresentanteLegal` varchar(20) DEFAULT NULL,
  `ContactoVendedor` varchar(20) DEFAULT NULL,
  `FlagSNC` varchar(1) DEFAULT NULL,
  `NroInscripcionSNC` varchar(20) DEFAULT NULL,
  `FechaEmisionSNC` date NOT NULL,
  `FechaValidacionSNC` date NOT NULL,
  `Nacionalidad` varchar(1) NOT NULL COMMENT 'N:NACIONAL; E:EXTRANJERO;',
  `CondicionRNC` int(11) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  `Calificacion` varchar(1) NOT NULL,
  `Nivel` varchar(15) NOT NULL,
  `Capacidad` float(11,2) NOT NULL,
  PRIMARY KEY (`CodProveedor`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mastsueldosmin`
--

CREATE TABLE IF NOT EXISTS `mastsueldosmin` (
  `Secuencia` int(4) NOT NULL,
  `Periodo` varchar(7) NOT NULL,
  `Monto` decimal(11,2) NOT NULL DEFAULT '0.00',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Secuencia`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `masttasainteres`
--

CREATE TABLE IF NOT EXISTS `masttasainteres` (
  `Periodo` varchar(7) NOT NULL,
  `Porcentaje` decimal(11,2) NOT NULL,
  `Fecha` date DEFAULT NULL,
  `Estado` varchar(1) NOT NULL COMMENT 'A:ACTIVO; I:INACTIVO;',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Periodo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `masttipopago`
--

CREATE TABLE IF NOT EXISTS `masttipopago` (
  `CodTipoPago` varchar(2) NOT NULL,
  `TipoPago` varchar(30) NOT NULL,
  `Estado` char(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodTipoPago`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `masttiposervicio`
--

CREATE TABLE IF NOT EXISTS `masttiposervicio` (
  `CodTipoServicio` varchar(5) NOT NULL,
  `Descripcion` varchar(35) NOT NULL,
  `CodRegimenFiscal` varchar(2) NOT NULL COMMENT 'ap_regimenfiscal->CodRegimenFiscal',
  `Estado` varchar(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodTipoServicio`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `masttiposervicioimpuesto`
--

CREATE TABLE IF NOT EXISTS `masttiposervicioimpuesto` (
  `CodTipoServicio` varchar(5) NOT NULL,
  `CodImpuesto` varchar(3) NOT NULL COMMENT 'mastimpuestos->CodImpuesto',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodTipoServicio`,`CodImpuesto`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mastunidades`
--

CREATE TABLE IF NOT EXISTS `mastunidades` (
  `CodUnidad` varchar(3) NOT NULL,
  `Descripcion` varchar(50) NOT NULL,
  `TipoMedida` varchar(2) NOT NULL,
  `Estado` varchar(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodUnidad`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mastunidadesconv`
--

CREATE TABLE IF NOT EXISTS `mastunidadesconv` (
  `CodUnidad` varchar(3) NOT NULL,
  `CodEquivalente` varchar(3) NOT NULL,
  `Descripcion` varchar(50) NOT NULL,
  `Cantidad` decimal(11,2) NOT NULL,
  `Estado` varchar(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodUnidad`,`CodEquivalente`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mastunidadtributaria`
--

CREATE TABLE IF NOT EXISTS `mastunidadtributaria` (
  `Anio` year(4) NOT NULL,
  `Secuencia` int(10) NOT NULL,
  `Valor` decimal(11,2) NOT NULL DEFAULT '0.00',
  `PorcentajeAumento` decimal(11,2) NOT NULL DEFAULT '0.00',
  `Fecha` date NOT NULL DEFAULT '0000-00-00',
  `GacetaOficial` varchar(20) NOT NULL,
  `ProvidenciaNro` varchar(20) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Anio`,`Secuencia`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=32;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pf_actividades`
--

CREATE TABLE IF NOT EXISTS `pf_actividades` (
  `CodFase` varchar(4) NOT NULL,
  `Codigo` varchar(2) NOT NULL,
  `CodActividad` varchar(6) NOT NULL,
  `Descripcion` varchar(255) NOT NULL,
  `Comentarios` text,
  `Duracion` int(4) NOT NULL,
  `FlagAutoArchivo` varchar(1) NOT NULL DEFAULT 'N',
  `FlagNoAfectoPlan` varchar(1) NOT NULL DEFAULT 'N',
  `Estado` varchar(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodActividad`),
  UNIQUE KEY `UK_pf_actividades_1` (`CodFase`,`Codigo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pf_actuacionfiscal`
--

CREATE TABLE IF NOT EXISTS `pf_actuacionfiscal` (
  `CodActuacion` varchar(15) NOT NULL,
  `Anio` year(4) NOT NULL,
  `Secuencia` int(2) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `CodDependencia` varchar(4) NOT NULL COMMENT 'mastdependencias->CodDependencia',
  `CodOrganismoExterno` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `CodDependenciaExterna` varchar(4) NOT NULL COMMENT 'mastdependencias->CodDependencia',
  `CodTipoActuacion` varchar(2) NOT NULL COMMENT 'pf_tipoactuacionfiscal->CodTipoActuacion',
  `CodCentroCosto` varchar(4) NOT NULL COMMENT 'ac_mastcentrocosto->CodCentroCosto',
  `CodProceso` varchar(2) NOT NULL COMMENT 'pf_procesos->CodProceso',
  `ObjetivoGeneral` longtext NOT NULL,
  `Alcance` longtext NOT NULL,
  `Observacion` longtext NOT NULL,
  `FechaRegistro` date NOT NULL,
  `FechaInicio` date NOT NULL,
  `FechaTermino` date NOT NULL,
  `FechaTerminoReal` date NOT NULL,
  `Duracion` int(4) NOT NULL,
  `Prorroga` int(4) NOT NULL,
  `DiasAdelanto` int(4) NOT NULL,
  `PreparadoPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `RevisadoPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `AprobadoPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `FechaPreparacion` datetime NOT NULL,
  `FechaRevision` datetime NOT NULL,
  `FechaAprobado` datetime NOT NULL,
  `Estado` varchar(2) NOT NULL DEFAULT 'PR' COMMENT 'PR:En Preparación, RV:Revisada, AP:Aprobada, TE:Terminada, CO:Completada, AN:Anulada, CE:Cerrada',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  `Origen` varchar(2) NOT NULL COMMENT 'mastmiscelaneosdet->ORIGENACT',
  `FechaCompletada` date DEFAULT NULL,
  `EstadoValJur` varchar(2) NOT NULL DEFAULT 'PE' COMMENT 'PE:PENDIENTE; GE:GENERADO; AA:AUTO DE ARCHIVO; AP:AUTO DE PROCEDER;',
  `CodValJur` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`CodActuacion`),
  KEY `CodOrganismo` (`CodOrganismo`),
  KEY `CodDependencia` (`CodDependencia`),
  KEY `CodOrganismoExterno` (`CodOrganismoExterno`),
  KEY `CodDependenciaExterna` (`CodDependenciaExterna`),
  KEY `FK_pf_actuacionfiscal_1` (`CodTipoActuacion`),
  KEY `FK_pf_actuacionfiscal_2` (`CodCentroCosto`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pf_actuacionfiscalauditores`
--

CREATE TABLE IF NOT EXISTS `pf_actuacionfiscalauditores` (
  `CodActuacion` varchar(15) NOT NULL COMMENT 'pf_actuacionfiscal->CodActuacion',
  `CodPersona` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `FlagCoordinador` varchar(1) NOT NULL DEFAULT 'N',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodActuacion`,`CodPersona`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pf_actuacionfiscaldetalle`
--

CREATE TABLE IF NOT EXISTS `pf_actuacionfiscaldetalle` (
  `CodActuacion` varchar(15) NOT NULL,
  `Secuencia` int(3) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `CodActividad` varchar(6) NOT NULL COMMENT 'pf_actividades->CodActividad',
  `Descripcion` varchar(255) DEFAULT NULL,
  `CodCentroCosto` varchar(4) NOT NULL COMMENT 'ac_mastcentrocosto->CodCentroCosto',
  `FechaInicio` date NOT NULL,
  `FechaInicioReal` date NOT NULL,
  `FechaTermino` date NOT NULL,
  `FechaTerminoReal` date NOT NULL,
  `Duracion` int(4) NOT NULL,
  `Prorroga` int(4) NOT NULL,
  `DiasAdelanto` int(4) NOT NULL,
  `Observaciones` longtext NOT NULL,
  `FechaRegistroCierre` date NOT NULL,
  `FechaTerminoCierre` date NOT NULL,
  `DiasCierre` int(4) NOT NULL,
  `Estado` varchar(2) NOT NULL DEFAULT 'PR' COMMENT 'PR:En Preparación, RV:Revisado, EJ:En Ejecucion, AN:Anulado, CO:Completado',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodActuacion`,`Secuencia`),
  KEY `CodOrganismo` (`CodOrganismo`),
  KEY `FK_pf_actuaciondetalle_1` (`CodActividad`),
  KEY `FK_pf_actuaciondetalle_2` (`CodCentroCosto`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pf_actuacionfiscaldocumentos`
--

CREATE TABLE IF NOT EXISTS `pf_actuacionfiscaldocumentos` (
  `CodActuacion` varchar(15) NOT NULL,
  `Secuencia` varchar(3) NOT NULL,
  `Linea` int(1) NOT NULL,
  `Documento` varchar(15) NOT NULL,
  `NroDocumento` varchar(15) NOT NULL,
  `Fecha` date NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodActuacion`,`Secuencia`,`Linea`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pf_dependenciasexternas`
--

CREATE TABLE IF NOT EXISTS `pf_dependenciasexternas` (
  `CodDependencia` varchar(4) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL,
  `Dependencia` varchar(255) NOT NULL,
  `Direccion` text,
  `Representante` varchar(100) NOT NULL,
  `Cargo` varchar(100) NOT NULL,
  `Telefono1` varchar(15) NOT NULL,
  `Telefono2` varchar(15) NOT NULL,
  `Estado` varchar(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodDependencia`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pf_determinacion`
--

CREATE TABLE IF NOT EXISTS `pf_determinacion` (
  `CodPotestad` varchar(15) NOT NULL COMMENT 'pf_potestad->CodPotestad',
  `CodDeterminacion` varchar(15) NOT NULL,
  `Anio` year(4) NOT NULL,
  `Secuencia` int(2) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `CodDependencia` varchar(4) NOT NULL COMMENT 'mastdependencias->CodDependencia',
  `CodOrganismoExterno` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `CodDependenciaExterna` varchar(4) NOT NULL COMMENT 'mastdependencias->CodDependencia',
  `CodTipoActuacion` varchar(2) NOT NULL COMMENT 'pf_tipoactuacionfiscal->CodTipoActuacion',
  `CodCentroCosto` varchar(4) NOT NULL COMMENT 'ac_mastcentrocosto->CodCentroCosto',
  `CodProceso` varchar(2) NOT NULL COMMENT 'pf_procesos->CodProceso',
  `ObjetivoGeneral` longtext NOT NULL,
  `Alcance` longtext NOT NULL,
  `Observacion` longtext NOT NULL,
  `FechaRegistro` date NOT NULL,
  `FechaInicio` date NOT NULL,
  `FechaTermino` date NOT NULL,
  `FechaTerminoReal` date NOT NULL,
  `Duracion` int(4) NOT NULL,
  `Prorroga` int(4) NOT NULL,
  `PreparadoPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `RevisadoPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `AprobadoPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `FechaPreparacion` datetime NOT NULL,
  `FechaRevision` datetime NOT NULL,
  `FechaAprobado` datetime NOT NULL,
  `DiasAdelanto` int(4) DEFAULT NULL,
  `Origen` varchar(2) NOT NULL,
  `FechaCompletada` date DEFAULT NULL,
  `Estado` varchar(2) NOT NULL DEFAULT 'PR' COMMENT 'PR:En Preparación, RV:Revisado, AP:Aprobado, AN:Anulado, CO:Completado',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodDeterminacion`),
  KEY `CodOrganismo` (`CodOrganismo`),
  KEY `CodDependencia` (`CodDependencia`),
  KEY `CodOrganismoExterno` (`CodOrganismoExterno`),
  KEY `CodDependenciaExterna` (`CodDependenciaExterna`),
  KEY `FK_pf_determinacion_1` (`CodTipoActuacion`),
  KEY `FK_pf_determinacion_2` (`CodCentroCosto`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pf_determinacionauditores`
--

CREATE TABLE IF NOT EXISTS `pf_determinacionauditores` (
  `CodDeterminacion` varchar(15) NOT NULL COMMENT 'pf_detarminacion->CodDeterminacion',
  `CodPersona` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `FlagCoordinador` varchar(1) NOT NULL DEFAULT 'N',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodDeterminacion`,`CodPersona`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pf_determinaciondetalle`
--

CREATE TABLE IF NOT EXISTS `pf_determinaciondetalle` (
  `CodDeterminacion` varchar(15) NOT NULL,
  `Secuencia` int(3) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `CodActividad` varchar(6) NOT NULL COMMENT 'pf_actividades->CodActividad',
  `Descripcion` varchar(255) DEFAULT NULL,
  `CodCentroCosto` varchar(4) NOT NULL COMMENT 'ac_mastcentrocosto->CodCentroCosto',
  `FechaInicio` date NOT NULL,
  `FechaInicioReal` date NOT NULL,
  `FechaTermino` date NOT NULL,
  `FechaTerminoReal` date NOT NULL,
  `Duracion` int(4) NOT NULL,
  `Prorroga` int(4) NOT NULL,
  `Observaciones` longtext NOT NULL,
  `FechaRegistroCierre` date NOT NULL,
  `FechaTerminoCierre` date NOT NULL,
  `DiasCierre` int(4) NOT NULL,
  `DiasAdelanto` int(4) DEFAULT NULL,
  `Estado` varchar(2) NOT NULL DEFAULT 'PR' COMMENT 'PR:En Preparación, RV:Revisado, EJ:En Ejecucion, AN:Anulado, CO:Completado',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodDeterminacion`,`Secuencia`),
  KEY `CodOrganismo` (`CodOrganismo`),
  KEY `FK_pf_determinaciondetalle_1` (`CodActividad`),
  KEY `FK_pf_determinaciondetalle_2` (`CodCentroCosto`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pf_determinaciondocumentos`
--

CREATE TABLE IF NOT EXISTS `pf_determinaciondocumentos` (
  `CodDeterminacion` varchar(15) NOT NULL,
  `Secuencia` varchar(3) NOT NULL,
  `Linea` int(1) NOT NULL,
  `Documento` varchar(15) NOT NULL,
  `NroDocumento` varchar(15) NOT NULL,
  `Fecha` date NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodDeterminacion`,`Secuencia`,`Linea`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pf_determinacionprorroga`
--

CREATE TABLE IF NOT EXISTS `pf_determinacionprorroga` (
  `CodProrroga` varchar(20) NOT NULL,
  `Secuencia` int(4) NOT NULL,
  `CodDeterminacion` varchar(15) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `CodActividad` varchar(6) NOT NULL COMMENT 'pf_actividades->CodActividad',
  `Prorroga` int(4) NOT NULL,
  `Motivo` text NOT NULL,
  `FechaRegistro` date NOT NULL,
  `PreparadoPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `FechaPreparacion` datetime NOT NULL,
  `RevisadoPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `FechaRevision` datetime NOT NULL,
  `AprobadoPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `FechaAprobacion` datetime NOT NULL,
  `Estado` varchar(2) NOT NULL DEFAULT 'PR' COMMENT 'PR:En Preparación, RV:Revisado, AP:Aprobado, AN:Anulado',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodProrroga`),
  KEY `FK_pf_determinacionprorroga_1` (`CodDeterminacion`),
  KEY `FK_pf_determinacionprorroga_2` (`CodActividad`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pf_fases`
--

CREATE TABLE IF NOT EXISTS `pf_fases` (
  `CodProceso` varchar(2) NOT NULL,
  `Codigo` varchar(2) NOT NULL,
  `CodFase` varchar(4) NOT NULL,
  `Descripcion` varchar(50) NOT NULL,
  `Estado` varchar(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodFase`),
  UNIQUE KEY `UK_pf_fases_1` (`CodProceso`,`Codigo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pf_organismosexternos`
--

CREATE TABLE IF NOT EXISTS `pf_organismosexternos` (
  `CodOrganismo` varchar(4) NOT NULL,
  `Organismo` varchar(255) NOT NULL,
  `DescripComp` varchar(255) NOT NULL,
  `RepresentLegal` varchar(100) NOT NULL,
  `DocRepreLeg` varchar(30) NOT NULL,
  `PaginaWeb` varchar(100) NOT NULL,
  `DocFiscal` varchar(20) NOT NULL,
  `FechaFundac` date NOT NULL,
  `Direccion` varchar(255) NOT NULL,
  `CodCiudad` varchar(4) NOT NULL,
  `Telefono1` varchar(15) NOT NULL,
  `Telefono2` varchar(15) NOT NULL,
  `Telefono3` varchar(15) NOT NULL,
  `Fax1` varchar(15) NOT NULL,
  `Fax2` varchar(15) NOT NULL,
  `Logo` varchar(30) NOT NULL,
  `Estado` varchar(1) NOT NULL,
  `NumReg` varchar(10) NOT NULL,
  `TomoReg` varchar(5) NOT NULL,
  `Control` varchar(2) NOT NULL COMMENT 'AC:CENTRALIZADA; AD:DESCENTRALIZADA',
  `Cargo` varchar(100) DEFAULT NULL,
  `FlagSujetoControl` varchar(1) NOT NULL DEFAULT 'N',
  `CodDependencia` varchar(4) DEFAULT NULL COMMENT 'mastdependencias->CodDependencia',
  `Mision` text,
  `Vision` text,
  `Gaceta` varchar(25) DEFAULT NULL,
  `Resolucion` varchar(25) DEFAULT NULL,
  `Otros` text,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  `FlagSocial` varchar(1) NOT NULL DEFAULT 'N',
  PRIMARY KEY (`CodOrganismo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pf_potestad`
--

CREATE TABLE IF NOT EXISTS `pf_potestad` (
  `CodActuacion` varchar(15) NOT NULL COMMENT 'pf_actuacionfiscal->CodActuacion',
  `CodValJur` varchar(15) NOT NULL,
  `CodPotestad` varchar(15) NOT NULL,
  `Anio` year(4) NOT NULL,
  `Secuencia` int(2) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `CodDependencia` varchar(4) NOT NULL COMMENT 'mastdependencias->CodDependencia',
  `CodOrganismoExterno` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `CodDependenciaExterna` varchar(4) NOT NULL COMMENT 'mastdependencias->CodDependencia',
  `CodTipoActuacion` varchar(2) NOT NULL COMMENT 'pf_tipoactuacionfiscal->CodTipoActuacion',
  `CodCentroCosto` varchar(4) NOT NULL COMMENT 'ac_mastcentrocosto->CodCentroCosto',
  `CodProceso` varchar(2) NOT NULL COMMENT 'pf_procesos->CodProceso',
  `ObjetivoGeneral` longtext NOT NULL,
  `Alcance` longtext NOT NULL,
  `Observacion` longtext NOT NULL,
  `FechaRegistro` date NOT NULL,
  `FechaInicio` date NOT NULL,
  `FechaTermino` date NOT NULL,
  `FechaTerminoReal` date NOT NULL,
  `Duracion` int(4) NOT NULL,
  `Prorroga` int(4) NOT NULL,
  `PreparadoPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `RevisadoPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `AprobadoPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `FechaPreparacion` datetime NOT NULL,
  `FechaRevision` datetime NOT NULL,
  `FechaAprobado` datetime NOT NULL,
  `Origen` varchar(2) DEFAULT NULL,
  `DiasAdelanto` int(4) DEFAULT NULL,
  `FechaCompletada` date DEFAULT NULL,
  `CodDeterminacion` varchar(15) DEFAULT NULL,
  `EstadoDeterminacion` varchar(2) DEFAULT 'PE',
  `Estado` varchar(2) NOT NULL DEFAULT 'PR' COMMENT 'PR:En Preparación, RV:Revisado, AP:Aprobado, AN:Anulado, CO:Completado',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodPotestad`),
  KEY `CodOrganismo` (`CodOrganismo`),
  KEY `CodDependencia` (`CodDependencia`),
  KEY `CodOrganismoExterno` (`CodOrganismoExterno`),
  KEY `CodDependenciaExterna` (`CodDependenciaExterna`),
  KEY `FK_pf_potestad_1` (`CodTipoActuacion`),
  KEY `FK_pf_potestad_2` (`CodCentroCosto`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pf_potestadauditores`
--

CREATE TABLE IF NOT EXISTS `pf_potestadauditores` (
  `CodPotestad` varchar(15) NOT NULL COMMENT 'pf_actuacionfiscal->CodActuacion',
  `CodPersona` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `FlagCoordinador` varchar(1) NOT NULL DEFAULT 'N',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodPotestad`,`CodPersona`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pf_potestaddetalle`
--

CREATE TABLE IF NOT EXISTS `pf_potestaddetalle` (
  `CodPotestad` varchar(15) NOT NULL,
  `Secuencia` int(3) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `CodActividad` varchar(6) NOT NULL COMMENT 'pf_actividades->CodActividad',
  `Descripcion` varchar(255) DEFAULT NULL,
  `CodCentroCosto` varchar(4) NOT NULL COMMENT 'ac_mastcentrocosto->CodCentroCosto',
  `FechaInicio` date NOT NULL,
  `FechaInicioReal` date NOT NULL,
  `FechaTermino` date NOT NULL,
  `FechaTerminoReal` date NOT NULL,
  `Duracion` int(4) NOT NULL,
  `Prorroga` int(4) NOT NULL,
  `Observaciones` longtext NOT NULL,
  `FechaRegistroCierre` date NOT NULL,
  `FechaTerminoCierre` date NOT NULL,
  `DiasCierre` int(4) NOT NULL,
  `DiasAdelanto` int(4) DEFAULT NULL,
  `Estado` varchar(2) NOT NULL DEFAULT 'PR' COMMENT 'PR:En Preparación, RV:Revisado, EJ:En Ejecucion, AN:Anulado, CO:Completado',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodPotestad`,`Secuencia`),
  KEY `CodOrganismo` (`CodOrganismo`),
  KEY `FK_pf_potestaddetalle_1` (`CodActividad`),
  KEY `FK_pf_potestaddetalle_2` (`CodCentroCosto`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pf_potestaddocumentos`
--

CREATE TABLE IF NOT EXISTS `pf_potestaddocumentos` (
  `CodPotestad` varchar(15) NOT NULL,
  `Secuencia` varchar(3) NOT NULL,
  `Linea` int(1) NOT NULL,
  `Documento` varchar(15) NOT NULL,
  `NroDocumento` varchar(15) NOT NULL,
  `Fecha` date NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodPotestad`,`Secuencia`,`Linea`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pf_potestadprorroga`
--

CREATE TABLE IF NOT EXISTS `pf_potestadprorroga` (
  `CodProrroga` varchar(20) NOT NULL,
  `Secuencia` int(4) NOT NULL,
  `CodPotestad` varchar(15) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `CodActividad` varchar(6) NOT NULL COMMENT 'pf_actividades->CodActividad',
  `Prorroga` int(4) NOT NULL,
  `Motivo` text NOT NULL,
  `FechaRegistro` date NOT NULL,
  `PreparadoPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `FechaPreparacion` datetime NOT NULL,
  `RevisadoPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `FechaRevision` datetime NOT NULL,
  `AprobadoPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `FechaAprobacion` datetime NOT NULL,
  `Estado` varchar(2) NOT NULL DEFAULT 'PR' COMMENT 'PR:En Preparación, RV:Revisado, AP:Aprobado, AN:Anulado',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodProrroga`),
  KEY `FK_pf_potestadprorroga_1` (`CodPotestad`),
  KEY `FK_pf_potestadprorroga_2` (`CodActividad`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pf_procesos`
--

CREATE TABLE IF NOT EXISTS `pf_procesos` (
  `CodProceso` varchar(2) NOT NULL,
  `Descripcion` varchar(50) NOT NULL,
  `Estado` varchar(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodProceso`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pf_prorroga`
--

CREATE TABLE IF NOT EXISTS `pf_prorroga` (
  `CodProrroga` varchar(20) NOT NULL,
  `Secuencia` int(4) NOT NULL,
  `CodActuacion` varchar(15) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `CodActividad` varchar(6) NOT NULL COMMENT 'pf_actividades->CodActividad',
  `Prorroga` int(4) NOT NULL,
  `Motivo` text NOT NULL,
  `FechaRegistro` date NOT NULL,
  `PreparadoPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `FechaPreparacion` datetime NOT NULL,
  `RevisadoPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `FechaRevision` datetime NOT NULL,
  `AprobadoPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `FechaAprobacion` datetime NOT NULL,
  `Estado` varchar(2) NOT NULL DEFAULT 'PR' COMMENT 'PR:En Preparación, RV:Revisado, AP:Aprobado, AN:Anulado',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodProrroga`),
  KEY `FK_pf_prorroga_1` (`CodActuacion`),
  KEY `FK_pf_prorroga_2` (`CodActividad`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pf_tipoactuacionfiscal`
--

CREATE TABLE IF NOT EXISTS `pf_tipoactuacionfiscal` (
  `CodTipoActuacion` varchar(2) NOT NULL,
  `Descripcion` varchar(50) NOT NULL,
  `Estado` varchar(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodTipoActuacion`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pf_valoracionjuridica`
--

CREATE TABLE IF NOT EXISTS `pf_valoracionjuridica` (
  `CodValJur` varchar(15) NOT NULL,
  `CodActuacion` varchar(15) NOT NULL,
  `Anio` year(4) NOT NULL,
  `Secuencia` int(2) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `CodDependencia` varchar(4) NOT NULL COMMENT 'mastdependencias->CodDependencia',
  `CodOrganismoExterno` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `CodDependenciaExterna` varchar(4) NOT NULL COMMENT 'mastdependencias->CodDependencia',
  `CodTipoActuacion` varchar(2) NOT NULL COMMENT 'pf_tipoactuacionfiscal->CodTipoActuacion',
  `CodCentroCosto` varchar(4) NOT NULL COMMENT 'ac_mastcentrocosto->CodCentroCosto',
  `CodProceso` varchar(2) NOT NULL COMMENT 'pf_procesos->CodProceso',
  `ObjetivoGeneral` longtext NOT NULL,
  `Alcance` longtext NOT NULL,
  `Observacion` longtext NOT NULL,
  `FechaRegistro` date NOT NULL,
  `FechaInicio` date NOT NULL,
  `FechaTermino` date NOT NULL,
  `FechaTerminoReal` date NOT NULL,
  `Duracion` int(4) NOT NULL,
  `Prorroga` int(4) NOT NULL,
  `DiasAdelanto` int(4) NOT NULL,
  `PreparadoPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `RevisadoPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `AprobadoPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `FechaPreparacion` datetime NOT NULL,
  `FechaRevision` datetime NOT NULL,
  `FechaAprobado` datetime NOT NULL,
  `Estado` varchar(2) NOT NULL DEFAULT 'PR' COMMENT 'PR:En Preparación, RV:Revisada, AP:Aprobada, TE:Terminada, CO:Completada, AN:Anulada, CE:Cerrada',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  `Origen` varchar(2) NOT NULL COMMENT 'mastmiscelaneosdet->ORIGENACT',
  `FechaCompletada` date DEFAULT NULL,
  `EstadoPotestad` varchar(2) DEFAULT 'PE',
  `CodPotestad` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`CodValJur`),
  KEY `CodOrganismo` (`CodOrganismo`),
  KEY `CodDependencia` (`CodDependencia`),
  KEY `CodOrganismoExterno` (`CodOrganismoExterno`),
  KEY `CodDependenciaExterna` (`CodDependenciaExterna`),
  KEY `FK_pf_valoracionjuridica_1` (`CodTipoActuacion`),
  KEY `FK_pf_valoracionjuridica_2` (`CodCentroCosto`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pf_valoracionjuridicaauditores`
--

CREATE TABLE IF NOT EXISTS `pf_valoracionjuridicaauditores` (
  `CodValJur` varchar(15) NOT NULL COMMENT 'pf_valoracionjuridica->CodActuacion',
  `CodPersona` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `FlagCoordinador` varchar(1) NOT NULL DEFAULT 'N',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodValJur`,`CodPersona`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pf_valoracionjuridicadetalle`
--

CREATE TABLE IF NOT EXISTS `pf_valoracionjuridicadetalle` (
  `CodValJur` varchar(15) NOT NULL,
  `Secuencia` int(3) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `CodActividad` varchar(6) NOT NULL COMMENT 'pf_actividades->CodActividad',
  `Descripcion` varchar(255) DEFAULT NULL,
  `CodCentroCosto` varchar(4) NOT NULL COMMENT 'ac_mastcentrocosto->CodCentroCosto',
  `FechaInicio` date NOT NULL,
  `FechaInicioReal` date NOT NULL,
  `FechaTermino` date NOT NULL,
  `FechaTerminoReal` date NOT NULL,
  `Duracion` int(4) NOT NULL,
  `Prorroga` int(4) NOT NULL,
  `DiasAdelanto` int(4) NOT NULL,
  `Observaciones` longtext NOT NULL,
  `FechaRegistroCierre` date NOT NULL,
  `FechaTerminoCierre` date NOT NULL,
  `DiasCierre` int(4) NOT NULL,
  `Estado` varchar(2) NOT NULL DEFAULT 'PR' COMMENT 'PR:En Preparación, RV:Revisado, EJ:En Ejecucion, AN:Anulado, CO:Completado',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodValJur`,`Secuencia`),
  KEY `CodOrganismo` (`CodOrganismo`),
  KEY `FK_pf_valoracionjuridicadetalle_1` (`CodActividad`),
  KEY `FK_pf_valoracionjuridicadetalle_2` (`CodCentroCosto`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pf_valoracionjuridicadocumentos`
--

CREATE TABLE IF NOT EXISTS `pf_valoracionjuridicadocumentos` (
  `CodValJur` varchar(15) NOT NULL,
  `Secuencia` varchar(3) NOT NULL,
  `Linea` int(1) NOT NULL,
  `Documento` varchar(15) NOT NULL,
  `NroDocumento` varchar(15) NOT NULL,
  `Fecha` date NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodValJur`,`Secuencia`,`Linea`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pf_valoracionjuridicaprorroga`
--

CREATE TABLE IF NOT EXISTS `pf_valoracionjuridicaprorroga` (
  `CodProrroga` varchar(20) NOT NULL,
  `Secuencia` int(4) NOT NULL,
  `CodValJur` varchar(15) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `CodActividad` varchar(6) NOT NULL COMMENT 'pf_actividades->CodActividad',
  `Prorroga` int(4) NOT NULL,
  `Motivo` text NOT NULL,
  `FechaRegistro` date NOT NULL,
  `PreparadoPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `FechaPreparacion` datetime NOT NULL,
  `RevisadoPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `FechaRevision` datetime NOT NULL,
  `AprobadoPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `FechaAprobacion` datetime NOT NULL,
  `Estado` varchar(2) NOT NULL DEFAULT 'PR' COMMENT 'PR:En Preparación, RV:Revisado, AP:Aprobado, AN:Anulado',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodProrroga`),
  KEY `FK_pf_valoracionjuridicaprorroga_1` (`CodValJur`),
  KEY `FK_pf_valoracionjuridicaprorroga_2` (`CodActividad`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pr_acumuladofideicomiso`
--

CREATE TABLE IF NOT EXISTS `pr_acumuladofideicomiso` (
  `CodPersona` varchar(6) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL,
  `AcumuladoInicialDias` decimal(11,2) NOT NULL,
  `AcumuladoInicialProv` decimal(11,2) NOT NULL,
  `AcumuladoInicialFide` decimal(11,2) NOT NULL,
  `AcumuladoProvDias` decimal(11,2) NOT NULL,
  `AcumuladoProv` decimal(11,2) NOT NULL,
  `AcumuladoFide` decimal(11,2) NOT NULL,
  `AcumuladoDiasAdicionalInicial` decimal(11,2) NOT NULL DEFAULT '0.00',
  `AcumuladoDiasAdicional` decimal(11,2) NOT NULL DEFAULT '0.00',
  `PeriodoInicial` varchar(7) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `Ultimafecha` datetime NOT NULL,
  PRIMARY KEY (`CodPersona`),
  KEY `FK_pr_acumuladofideicomiso_1` (`CodOrganismo`),
  KEY `FK_pr_acumuladofideicomiso_2` (`CodOrganismo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pr_acumuladofideicomisodetalle`
--

CREATE TABLE IF NOT EXISTS `pr_acumuladofideicomisodetalle` (
  `CodPersona` varchar(6) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL,
  `Periodo` varchar(7) NOT NULL,
  `AnteriorProv` decimal(11,2) NOT NULL,
  `AnteriorFide` decimal(11,2) NOT NULL,
  `Transaccion` decimal(11,2) NOT NULL,
  `TransaccionFide` decimal(11,2) NOT NULL,
  `Dias` decimal(11,2) NOT NULL,
  `DiasAdicional` decimal(11,2) NOT NULL,
  `Complemento` decimal(11,2) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `Ultimafecha` datetime NOT NULL,
  PRIMARY KEY (`CodPersona`,`Periodo`),
  KEY `FK_pr_acumuladofideicomisodetalle_1` (`CodPersona`),
  KEY `FK_pr_acumuladofideicomisodetalle_2` (`CodOrganismo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pr_ajustesalarial`
--

CREATE TABLE IF NOT EXISTS `pr_ajustesalarial` (
  `CodOrganismo` varchar(4) NOT NULL,
  `Periodo` varchar(7) NOT NULL,
  `Secuencia` int(2) NOT NULL,
  `Descripcion` text NOT NULL,
  `NroResolucion` varchar(15) NOT NULL,
  `NroGaceta` varchar(15) NOT NULL,
  `PreparadoPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `AprobadoPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `FechaPreparacion` date NOT NULL,
  `FechaAprobado` date NOT NULL,
  `MotivoAnulacion` text NOT NULL,
  `Estado` varchar(2) NOT NULL DEFAULT 'PR' COMMENT 'PR:En Preparación, AP:Aprobado, AN:Anulado',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodOrganismo`,`Periodo`,`Secuencia`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pr_ajustesalarialajustes`
--

CREATE TABLE IF NOT EXISTS `pr_ajustesalarialajustes` (
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `Periodo` varchar(7) NOT NULL,
  `Secuencia` int(2) NOT NULL,
  `CodNivel` int(2) unsigned NOT NULL,
  `Descripcion` varchar(45) NOT NULL,
  `Porcentaje` decimal(11,2) DEFAULT '0.00',
  `Monto` decimal(11,2) DEFAULT '0.00',
  `SueldoPromedio` decimal(11,2) NOT NULL DEFAULT '0.00',
  `Estado` varchar(2) NOT NULL DEFAULT 'PR' COMMENT 'PR:En Preparación, AP:Aprobado, AN:Anulado',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodOrganismo`,`Periodo`,`CodNivel`,`Secuencia`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pr_concepto`
--

CREATE TABLE IF NOT EXISTS `pr_concepto` (
  `CodConcepto` varchar(4) NOT NULL DEFAULT '',
  `Descripcion` varchar(100) NOT NULL,
  `Tipo` varchar(1) NOT NULL COMMENT 'I:INGRESOS; D:DESCUENTOS; A:APORTES; P:PROVISIONES;',
  `TextoImpresion` varchar(50) NOT NULL,
  `PlanillaOrden` int(2) NOT NULL DEFAULT '0',
  `Estado` varchar(1) NOT NULL DEFAULT 'A' COMMENT 'A:ACTIVO; I:INACTIVO;',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  `Formula` longtext NOT NULL,
  `FormulaEditor` longtext NOT NULL,
  `FlagFormula` varchar(1) NOT NULL DEFAULT 'N',
  `FlagAutomatico` varchar(1) NOT NULL DEFAULT 'N',
  `Abreviatura` varchar(10) NOT NULL,
  `FlagBono` varchar(1) NOT NULL DEFAULT 'N',
  `FlagRetencion` varchar(1) NOT NULL DEFAULT 'N',
  `FlagObligacion` varchar(1) NOT NULL DEFAULT 'N',
  `CodPersona` varchar(6) DEFAULT NULL COMMENT 'mastpersonas->CodPersona',
  `FlagBonoRemuneracion` varchar(1) DEFAULT 'N',
  `FlagRelacionIngreso` varchar(1) NOT NULL DEFAULT 'N',
  `FlagJubilacion` varchar(1) DEFAULT 'N',
  PRIMARY KEY (`CodConcepto`),
  KEY `CodPersona` (`CodPersona`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pr_conceptoperfil`
--

CREATE TABLE IF NOT EXISTS `pr_conceptoperfil` (
  `CodPerfilConcepto` varchar(4) NOT NULL DEFAULT '',
  `Descripcion` varchar(50) NOT NULL,
  `Estado` varchar(1) NOT NULL DEFAULT 'A' COMMENT 'A:ACTIVO; I:INACTIVO;',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodPerfilConcepto`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 8192 kB';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pr_conceptoperfildetalle`
--

CREATE TABLE IF NOT EXISTS `pr_conceptoperfildetalle` (
  `CodPerfilConcepto` varchar(4) NOT NULL DEFAULT '',
  `CodTipoProceso` varchar(3) NOT NULL COMMENT 'pr_tipoproceso->CodTipoProceso',
  `CodConcepto` varchar(4) NOT NULL COMMENT 'pr_concepto->CodConcepto',
  `cod_partida` varchar(12) NOT NULL COMMENT 'pv_partida->cod_partida',
  `CuentaDebe` varchar(13) NOT NULL COMMENT 'ac_mastplancuenta->CodCuenta',
  `CuentaDebePub20` varchar(13) NOT NULL COMMENT 'ac_mastplancuenta20->CodCuenta',
  `CuentaHaber` varchar(13) NOT NULL COMMENT 'ac_mastplancuenta->CodCuenta',
  `CuentaHaberPub20` varchar(13) NOT NULL COMMENT 'ac_mastplancuenta20->CodCuenta',
  `FlagDebeCC` varchar(1) NOT NULL DEFAULT 'N',
  `FlagHaberCC` varchar(1) NOT NULL DEFAULT 'N',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodPerfilConcepto`,`CodTipoProceso`,`CodConcepto`),
  KEY `cod_partida` (`cod_partida`),
  KEY `FK_pr_conceptoperfildetalle_1_idx` (`CodPerfilConcepto`) USING BTREE,
  KEY `FK_pr_conceptoperfildetalle_2_idx` (`CodTipoProceso`) USING BTREE,
  KEY `FK_pr_conceptoperfildetalle_3_idx` (`CodConcepto`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pr_conceptoproceso`
--

CREATE TABLE IF NOT EXISTS `pr_conceptoproceso` (
  `CodConcepto` varchar(4) NOT NULL DEFAULT '',
  `CodTipoProceso` varchar(3) NOT NULL DEFAULT '',
  PRIMARY KEY (`CodConcepto`,`CodTipoProceso`),
  KEY `FK_pr_conceptoproceso_2` (`CodTipoProceso`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 11264 kB';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pr_conceptotiponomina`
--

CREATE TABLE IF NOT EXISTS `pr_conceptotiponomina` (
  `CodConcepto` varchar(4) NOT NULL DEFAULT '',
  `CodTipoNom` varchar(2) NOT NULL,
  PRIMARY KEY (`CodConcepto`,`CodTipoNom`),
  KEY `FK_pr_conceptotiponomina_2` (`CodTipoNom`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 11264 kB';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pr_empleadoconcepto`
--

CREATE TABLE IF NOT EXISTS `pr_empleadoconcepto` (
  `CodPersona` varchar(6) NOT NULL,
  `CodConcepto` varchar(4) NOT NULL DEFAULT '',
  `Secuencia` int(6) NOT NULL,
  `TipoAplicacion` varchar(1) NOT NULL,
  `PeriodoDesde` varchar(7) NOT NULL DEFAULT '',
  `PeriodoHasta` varchar(7) NOT NULL DEFAULT '',
  `Monto` decimal(11,2) NOT NULL,
  `Cantidad` decimal(11,2) NOT NULL,
  `FlagTipoProceso` varchar(1) NOT NULL,
  `Estado` varchar(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  `Procesos` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`CodConcepto`,`CodPersona`,`Secuencia`),
  KEY `FK_pr_empleadoconcepto_1` (`CodPersona`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 11264 kB; (`CodPersona`) REFER `siaceda/mastper';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pr_empleadoconceptoprocesos`
--

CREATE TABLE IF NOT EXISTS `pr_empleadoconceptoprocesos` (
  `CodPersona` varchar(6) NOT NULL,
  `CodConcepto` varchar(4) NOT NULL DEFAULT '',
  `Secuencia` int(6) NOT NULL,
  `CodTipoProceso` varchar(3) NOT NULL,
  PRIMARY KEY (`CodPersona`,`CodConcepto`,`Secuencia`,`CodTipoProceso`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pr_fideicomisocalculo`
--

CREATE TABLE IF NOT EXISTS `pr_fideicomisocalculo` (
  `Periodo` varchar(7) NOT NULL,
  `CodPersona` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `SueldoMensual` decimal(11,2) DEFAULT '0.00',
  `Bonificaciones` decimal(11,2) DEFAULT '0.00',
  `AliVac` decimal(11,2) DEFAULT '0.00',
  `AliFin` decimal(11,2) DEFAULT '0.00',
  `SueldoDiario` decimal(11,2) DEFAULT '0.00',
  `SueldoDiarioAli` decimal(11,2) DEFAULT '0.00',
  `Dias` decimal(11,2) DEFAULT '0.00',
  `PrestAntiguedad` decimal(11,2) DEFAULT '0.00',
  `DiasComplemento` decimal(11,2) DEFAULT '0.00',
  `PrestComplemento` decimal(11,2) DEFAULT '0.00',
  `PrestAcumulada` decimal(11,2) DEFAULT '0.00',
  `Tasa` decimal(11,2) DEFAULT '0.00',
  `DiasMes` int(2) DEFAULT '0',
  `InteresMensual` decimal(11,2) DEFAULT '0.00',
  `InteresAcumulado` decimal(11,2) DEFAULT '0.00',
  `Anticipo` decimal(11,2) DEFAULT '0.00',
  `UltimoUsuario` varchar(30) DEFAULT NULL,
  `UltimaFecha` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`Periodo`,`CodPersona`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pr_funciones`
--

CREATE TABLE IF NOT EXISTS `pr_funciones` (
  `CodFuncion` int(3) NOT NULL AUTO_INCREMENT,
  `Funcion` varchar(25) NOT NULL,
  `Descripcion` text NOT NULL,
  `Estado` varchar(1) NOT NULL DEFAULT 'A' COMMENT 'A:ACTIVO; I:INACTIVO;',
  PRIMARY KEY (`CodFuncion`) USING BTREE
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=67 AUTO_INCREMENT=25 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pr_impuestorenta`
--

CREATE TABLE IF NOT EXISTS `pr_impuestorenta` (
  `CodPersona` varchar(6) NOT NULL,
  `Anio` varchar(4) NOT NULL,
  `Desde` varchar(7) NOT NULL,
  `Hasta` varchar(7) NOT NULL,
  `Porcentaje` decimal(11,2) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`CodPersona`,`Anio`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pr_obligaciones`
--

CREATE TABLE IF NOT EXISTS `pr_obligaciones` (
  `TipoObligacion` varchar(2) NOT NULL,
  `CodTipoNom` varchar(2) NOT NULL,
  `Periodo` varchar(7) NOT NULL,
  `CodTipoProceso` varchar(3) NOT NULL,
  `CodProveedor` varchar(6) NOT NULL COMMENT 'mastproveedores->CodProveedor',
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
  `MontoObligacion` decimal(11,2) NOT NULL,
  `MontoImpuestoOtros` decimal(11,2) NOT NULL,
  `MontoNoAfecto` decimal(11,2) NOT NULL,
  `MontoAfecto` decimal(11,2) NOT NULL,
  `MontoAdelanto` decimal(11,2) NOT NULL,
  `MontoImpuesto` decimal(11,2) NOT NULL,
  `MontoPagoParcial` decimal(11,2) NOT NULL,
  `FlagContabilizacionPendiente` varchar(1) NOT NULL DEFAULT 'S',
  `Voucher` varchar(10) NOT NULL,
  `NroPago` varchar(10) NOT NULL,
  `NroProceso` varchar(10) NOT NULL,
  `ProcesoSecuencia` varchar(10) NOT NULL,
  `NroRegistro` varchar(6) NOT NULL,
  `Comentarios` longtext NOT NULL,
  `ComentariosAdicional` longtext NOT NULL,
  `CodCentroCosto` varchar(4) NOT NULL COMMENT 'ac_mastcentrocosto->CodCentroCosto',
  `FechaRecepcion` date NOT NULL,
  `CodProveedorPagar` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `NomProveedorPagar` varchar(100) NOT NULL,
  `FechaDocumento` date NOT NULL,
  `FlagAfectoIGV` varchar(1) NOT NULL DEFAULT 'N',
  `FlagDiferido` varchar(1) NOT NULL DEFAULT 'N',
  `FlagAdelanto` varchar(1) NOT NULL DEFAULT 'N',
  `FlagPagoDiferido` varchar(1) NOT NULL DEFAULT 'N',
  `Estado` varchar(2) NOT NULL DEFAULT 'PE' COMMENT 'PE:PENDIENTE; GE:GENERADO',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  `FlagCompromiso` varchar(1) NOT NULL DEFAULT 'S',
  `FlagPresupuesto` varchar(1) NOT NULL DEFAULT 'S',
  `FlagObligacionAuto` varchar(1) NOT NULL DEFAULT 'S',
  `FlagObligacionDirecta` varchar(1) NOT NULL DEFAULT 'N',
  `FlagCajaChica` varchar(1) NOT NULL DEFAULT 'N',
  `FlagPagoIndividual` varchar(1) NOT NULL DEFAULT 'N',
  `FlagTransferido` varchar(1) NOT NULL DEFAULT 'N',
  `NroControl` varchar(10) NOT NULL,
  `CodCuenta` varchar(13) NOT NULL COMMENT 'ac_mastplancuenta->CodCuenta',
  `CodCuentaPub20` varchar(13) NOT NULL COMMENT 'ac_mastplancuenta20->CodCuenta',
  `Secuencia` int(2) DEFAULT NULL,
  `FechaGeneracion` date DEFAULT NULL,
  `FechaPago` date DEFAULT NULL,
  `FlagVerificado` varchar(1) DEFAULT 'N',
  `PeriodoNomina` varchar(7) NOT NULL,
  PRIMARY KEY (`CodProveedor`,`CodTipoDocumento`,`NroDocumento`),
  KEY `CodTipoNom` (`CodTipoNom`),
  KEY `Periodo` (`Periodo`),
  KEY `CodTipoProceso` (`CodTipoProceso`),
  KEY `CodOrganismo` (`CodOrganismo`),
  KEY `NroCuenta` (`NroCuenta`),
  KEY `CodTipoPago` (`CodTipoPago`),
  KEY `CodTipoServicio` (`CodTipoServicio`),
  KEY `CodCentroCosto` (`CodCentroCosto`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pr_obligacionescuenta`
--

CREATE TABLE IF NOT EXISTS `pr_obligacionescuenta` (
  `CodProveedor` varchar(6) NOT NULL,
  `CodTipoDocumento` varchar(2) NOT NULL COMMENT 'ap_tipodocumento->CodTipoDocumento',
  `NroDocumento` varchar(25) NOT NULL,
  `Linea` varchar(10) NOT NULL,
  `Descripcion` varchar(255) NOT NULL,
  `Monto` decimal(11,2) NOT NULL DEFAULT '0.00',
  `CodCentroCosto` varchar(4) NOT NULL COMMENT 'ac_mastcentrocosto->CodCentroCosto',
  `CodCuenta` varchar(13) NOT NULL COMMENT 'ac_mastplancuenta->CodCuenta',
  `CodCuentaPub20` varchar(13) DEFAULT NULL COMMENT 'ac_mastplancuenta20->CodCuenta',
  `cod_partida` varchar(12) NOT NULL COMMENT 'pv_partida->cod_partida',
  `FlagNoAfectoIGV` varchar(1) NOT NULL DEFAULT 'N',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodProveedor`,`CodTipoDocumento`,`NroDocumento`,`Linea`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pr_obligacionesretenciones`
--

CREATE TABLE IF NOT EXISTS `pr_obligacionesretenciones` (
  `CodProveedor` varchar(6) NOT NULL,
  `CodTipoDocumento` varchar(2) NOT NULL COMMENT 'ap_tipodocumento->CodTipoDocumento',
  `NroDocumento` varchar(25) NOT NULL,
  `Linea` int(2) NOT NULL,
  `CodImpuesto` varchar(3) NOT NULL COMMENT 'mastimpuestos->CodImpuesto',
  `CodConcepto` varchar(4) NOT NULL COMMENT 'pr_concepto->CodConcepto',
  `FactorPorcentaje` decimal(11,2) NOT NULL DEFAULT '0.00',
  `MontoImpuesto` decimal(11,2) NOT NULL DEFAULT '0.00',
  `MontoAfecto` decimal(11,2) NOT NULL DEFAULT '0.00',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  `CodCuenta` varchar(13) NOT NULL COMMENT 'ac_mastplancuenta->CodCuenta',
  `CodCuentaPub20` varchar(13) DEFAULT NULL COMMENT 'ac_mastplancuenta20->CodCuenta',
  `FlagProvision` varchar(1) NOT NULL DEFAULT 'N' COMMENT 'P:PAGO DEL DOCUMENTO; N:PROVISION DEL DOCUMENTO;',
  PRIMARY KEY (`CodProveedor`,`CodTipoDocumento`,`NroDocumento`,`Linea`),
  KEY `Index_2` (`CodCuenta`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pr_procesoperiodo`
--

CREATE TABLE IF NOT EXISTS `pr_procesoperiodo` (
  `CodOrganismo` varchar(4) NOT NULL,
  `CodTipoNom` varchar(2) NOT NULL,
  `Periodo` varchar(7) NOT NULL DEFAULT '',
  `CodTipoProceso` varchar(3) NOT NULL DEFAULT '',
  `FechaDesde` date NOT NULL,
  `FechaHasta` date NOT NULL,
  `CreadoPor` varchar(6) NOT NULL,
  `FechaCreado` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ProcesadoPor` varchar(6) NOT NULL,
  `FechaProceso` date NOT NULL,
  `FechaPago` date NOT NULL,
  `AprobadoPor` varchar(6) NOT NULL,
  `FechaAprobado` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `Estado` varchar(1) NOT NULL,
  `FlagProcesado` varchar(1) NOT NULL,
  `FlagAprobado` varchar(1) NOT NULL,
  `FlagMensual` varchar(1) NOT NULL,
  `FlagPagado` varchar(1) NOT NULL,
  `Voucher` varchar(7) DEFAULT NULL COMMENT 'ac_vouchermast->Voucher',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodOrganismo`,`CodTipoNom`,`Periodo`,`CodTipoProceso`),
  KEY `FK_pr_procesoperiodo_2` (`CodTipoNom`),
  KEY `FK_pr_procesoperiodo_3` (`CodTipoProceso`),
  KEY `FK_pr_procesoperiodo_4` (`CreadoPor`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 11264 kB';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pr_procesoperiodoprenomina`
--

CREATE TABLE IF NOT EXISTS `pr_procesoperiodoprenomina` (
  `CodOrganismo` varchar(4) NOT NULL,
  `CodTipoNom` varchar(2) NOT NULL,
  `Periodo` varchar(7) NOT NULL DEFAULT '',
  `CodTipoProceso` varchar(3) NOT NULL DEFAULT '',
  `FechaDesde` date NOT NULL,
  `FechaHasta` date NOT NULL,
  `CreadoPor` varchar(6) NOT NULL,
  `FechaCreado` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ProcesadoPor` varchar(6) NOT NULL,
  `FechaProceso` date NOT NULL,
  `FechaPago` date NOT NULL,
  `AprobadoPor` varchar(6) NOT NULL,
  `FechaAprobado` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `Estado` varchar(1) NOT NULL,
  `FlagProcesado` varchar(1) NOT NULL,
  `FlagAprobado` varchar(1) NOT NULL,
  `FlagMensual` varchar(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodOrganismo`,`CodTipoNom`,`Periodo`,`CodTipoProceso`),
  KEY `FK_pr_procesoperiodoprenomina_2` (`CodTipoNom`),
  KEY `FK_pr_procesoperiodoprenomina_3` (`CodTipoProceso`),
  KEY `FK_pr_procesoperiodoprenomina_4` (`CreadoPor`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 11264 kB';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pr_tiponominaempleado`
--

CREATE TABLE IF NOT EXISTS `pr_tiponominaempleado` (
  `CodTipoNom` varchar(2) NOT NULL DEFAULT '',
  `Periodo` varchar(7) NOT NULL DEFAULT '',
  `CodPersona` varchar(6) NOT NULL DEFAULT '',
  `CodOrganismo` varchar(4) NOT NULL DEFAULT '',
  `CodTipoProceso` varchar(3) NOT NULL DEFAULT '',
  `SueldoBasico` decimal(11,2) NOT NULL DEFAULT '0.00',
  `TotalIngresos` decimal(11,2) NOT NULL DEFAULT '0.00',
  `TotalEgresos` decimal(11,2) NOT NULL DEFAULT '0.00',
  `TotalPatronales` decimal(11,2) NOT NULL DEFAULT '0.00',
  `TotalNeto` decimal(11,2) NOT NULL DEFAULT '0.00',
  `TotalProvisiones` decimal(11,2) NOT NULL DEFAULT '0.00',
  `CodBanco` varchar(4) NOT NULL DEFAULT '',
  `TipoCuenta` varchar(2) NOT NULL DEFAULT '',
  `Ncuenta` varchar(30) NOT NULL DEFAULT '',
  `CodTipoPago` varchar(2) NOT NULL DEFAULT '',
  `FechaGeneracion` date NOT NULL DEFAULT '0000-00-00',
  `GeneradoPor` varchar(100) NOT NULL DEFAULT '',
  `UltimoUsuario` varchar(30) NOT NULL DEFAULT '',
  `UltimaFecha` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`CodTipoNom`,`Periodo`,`CodPersona`,`CodOrganismo`,`CodTipoProceso`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pr_tiponominaempleadoconcepto`
--

CREATE TABLE IF NOT EXISTS `pr_tiponominaempleadoconcepto` (
  `CodTipoNom` varchar(2) NOT NULL DEFAULT '',
  `Periodo` varchar(7) NOT NULL DEFAULT '',
  `CodPersona` varchar(6) NOT NULL DEFAULT '',
  `CodOrganismo` varchar(4) NOT NULL DEFAULT '',
  `CodTipoProceso` varchar(3) NOT NULL DEFAULT '',
  `CodConcepto` varchar(4) NOT NULL DEFAULT '',
  `Monto` decimal(11,2) NOT NULL DEFAULT '0.00',
  `Cantidad` decimal(11,2) NOT NULL DEFAULT '0.00',
  `Saldo` decimal(11,2) NOT NULL DEFAULT '0.00',
  `UltimoUsuario` varchar(30) NOT NULL DEFAULT '',
  `UltimaFecha` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`CodTipoNom`,`Periodo`,`CodPersona`,`CodOrganismo`,`CodTipoProceso`,`CodConcepto`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pr_tiponominaempleadoconceptoprenomina`
--

CREATE TABLE IF NOT EXISTS `pr_tiponominaempleadoconceptoprenomina` (
  `CodTipoNom` varchar(2) NOT NULL DEFAULT '',
  `Periodo` varchar(7) NOT NULL DEFAULT '',
  `CodPersona` varchar(6) NOT NULL DEFAULT '',
  `CodOrganismo` varchar(4) NOT NULL DEFAULT '',
  `CodTipoProceso` varchar(3) NOT NULL DEFAULT '',
  `CodConcepto` varchar(4) NOT NULL DEFAULT '',
  `Monto` decimal(11,2) NOT NULL DEFAULT '0.00',
  `Cantidad` decimal(11,2) NOT NULL DEFAULT '0.00',
  `Saldo` decimal(11,2) NOT NULL DEFAULT '0.00',
  `UltimoUsuario` varchar(30) NOT NULL DEFAULT '',
  `UltimaFecha` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`CodTipoNom`,`Periodo`,`CodPersona`,`CodOrganismo`,`CodTipoProceso`,`CodConcepto`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pr_tiponominaempleadoprenomina`
--

CREATE TABLE IF NOT EXISTS `pr_tiponominaempleadoprenomina` (
  `CodTipoNom` varchar(2) NOT NULL DEFAULT '',
  `Periodo` varchar(7) NOT NULL DEFAULT '',
  `CodPersona` varchar(6) NOT NULL DEFAULT '',
  `CodOrganismo` varchar(4) NOT NULL DEFAULT '',
  `CodTipoProceso` varchar(3) NOT NULL DEFAULT '',
  `SueldoBasico` decimal(11,2) NOT NULL DEFAULT '0.00',
  `TotalIngresos` decimal(11,2) NOT NULL DEFAULT '0.00',
  `TotalEgresos` decimal(11,2) NOT NULL DEFAULT '0.00',
  `TotalPatronales` decimal(11,2) NOT NULL DEFAULT '0.00',
  `TotalNeto` decimal(11,2) NOT NULL DEFAULT '0.00',
  `CodBanco` varchar(4) NOT NULL DEFAULT '',
  `TipoCuenta` varchar(2) NOT NULL DEFAULT '',
  `Ncuenta` varchar(30) NOT NULL DEFAULT '',
  `CodTipoPago` varchar(2) NOT NULL DEFAULT '',
  `FechaGeneracion` date NOT NULL DEFAULT '0000-00-00',
  `GeneradoPor` varchar(100) NOT NULL DEFAULT '',
  `UltimoUsuario` varchar(30) NOT NULL DEFAULT '',
  `UltimaFecha` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`CodTipoNom`,`Periodo`,`CodPersona`,`CodOrganismo`,`CodTipoProceso`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pr_tiponominaperiodo`
--

CREATE TABLE IF NOT EXISTS `pr_tiponominaperiodo` (
  `CodTipoNom` varchar(2) NOT NULL,
  `Periodo` varchar(4) NOT NULL,
  `Mes` varchar(2) NOT NULL,
  `Secuencia` int(2) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodTipoNom`,`Periodo`,`Mes`,`Secuencia`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pr_tiponominaproceso`
--

CREATE TABLE IF NOT EXISTS `pr_tiponominaproceso` (
  `CodTipoNom` varchar(2) NOT NULL,
  `CodTipoProceso` varchar(3) NOT NULL DEFAULT '',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  `CodTipoDocumento` varchar(2) NOT NULL COMMENT 'ap_tipodocumento->CodtipoDocumento',
  PRIMARY KEY (`CodTipoNom`,`CodTipoProceso`),
  KEY `FK_pr_tiponominaproceso_3` (`CodTipoProceso`),
  KEY `Index_3` (`CodTipoDocumento`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 7168 kB';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pr_tipoproceso`
--

CREATE TABLE IF NOT EXISTS `pr_tipoproceso` (
  `CodTipoProceso` varchar(3) NOT NULL DEFAULT '',
  `Descripcion` varchar(100) NOT NULL DEFAULT '',
  `FlagAdelanto` varchar(1) NOT NULL,
  `Estado` char(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodTipoProceso`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pr_variables`
--

CREATE TABLE IF NOT EXISTS `pr_variables` (
  `CodVariable` int(3) NOT NULL AUTO_INCREMENT,
  `Variable` varchar(25) NOT NULL,
  `Descripcion` text NOT NULL,
  `Estado` varchar(1) NOT NULL DEFAULT 'A' COMMENT 'A:ACTIVO; I:INACTIVO;',
  PRIMARY KEY (`CodVariable`) USING BTREE
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=59 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pv_actividad1`
--

CREATE TABLE IF NOT EXISTS `pv_actividad1` (
  `id_actividad` varchar(4) NOT NULL,
  `id_proyecto` varchar(4) NOT NULL,
  `cod_actividad` varchar(2) NOT NULL,
  `descp_actividad` varchar(250) NOT NULL,
  `Estado` varchar(1) NOT NULL,
  `UltimoUsuario` varchar(100) NOT NULL,
  `UltimaFecha` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id_actividad`),
  KEY `id_proyecto` (`id_proyecto`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

