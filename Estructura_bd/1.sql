  `NroComprobante` varchar(8) NOT NULL,
  `Anio` year(4) NOT NULL,
  `TipoComprobante` varchar(10) NOT NULL,
  `CodImpuesto` varchar(3) NOT NULL COMMENT 'mastimpuestos->CodImpuesto',
  `PeriodoFiscal` varchar(7) NOT NULL,
  `FechaComprobante` date NOT NULL,
  `CodProveedor` varchar(6) NOT NULL COMMENT 'mastproveedores->CodProveedor',
  `CodTipoDocumento` varchar(2) NOT NULL COMMENT 'ap_tipodocumento->CodTipoDocumento',
  `NroDocumento` varchar(25) NOT NULL COMMENT 'ap_obligaciones',
  `NroControl` varchar(25) NOT NULL,
  `FechaFactura` date NOT NULL,
  `MontoAfecto` decimal(11,2) NOT NULL,
  `MontoNoAfecto` decimal(11,2) NOT NULL,
  `MontoImpuesto` decimal(11,2) NOT NULL,
  `MontoFactura` decimal(11,2) NOT NULL,
  `Porcentaje` decimal(11,2) NOT NULL,
  `MontoRetenido` decimal(11,2) NOT NULL,
  `Estado` varchar(2) NOT NULL DEFAULT 'PA' COMMENT 'PA:PAGADO; AN:ANULADO',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`NroComprobante`,`Anio`,`TipoComprobante`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ap_tipodocumento`
--

CREATE TABLE IF NOT EXISTS `ap_tipodocumento` (
  `CodTipoDocumento` varchar(2) NOT NULL,
  `Descripcion` varchar(100) NOT NULL,
  `Clasificacion` varchar(1) NOT NULL COMMENT 'O:OBLIGACIONES; C:OTROS DE CTAS POR PAGAR; P:PRESTAMOS; E:OTROS EXTERNOS',
  `CodRegimenFiscal` varchar(2) NOT NULL COMMENT 'ap_regimenfiscal->CodRegimenFiscal',
  `CodVoucher` varchar(2) NOT NULL COMMENT 'ac_voucher->CodVoucher',
  `FlagProvision` varchar(1) NOT NULL DEFAULT 'N',
  `CodCuentaProv` varchar(13) NOT NULL COMMENT 'ac_mastplancuenta->CodCuenta',
  `CodCuentaProvPub20` varchar(13) NOT NULL COMMENT 'ac_mastplancuenta20->CodCuenta',
  `FlagAdelanto` varchar(1) NOT NULL DEFAULT 'N',
  `CodCuentaAde` varchar(13) NOT NULL COMMENT 'ac_mastplancuenta->CodCuenta',
  `CodCuentaAdePub20` varchar(13) NOT NULL COMMENT 'ac_mastplancuenta20->CodCuenta',
  `FlagFiscal` varchar(1) NOT NULL DEFAULT 'N',
  `CodFiscal` varchar(2) NOT NULL,
  `FlagExportableRegistroCompra` varchar(1) NOT NULL DEFAULT 'N',
  `FlagMontoNegativo` varchar(1) NOT NULL DEFAULT 'N',
  `FlagAutoNomina` varchar(1) NOT NULL DEFAULT 'N',
  `Estado` varchar(1) NOT NULL DEFAULT 'A' COMMENT 'A:ACTIVO; I:INACTIVO;',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  `ap_tipodocumentocol` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`CodTipoDocumento`),
  KEY `FK_ap_tipodocumento_1_idx` (`CodRegimenFiscal`) USING BTREE,
  KEY `FK_ap_tipodocumento_2_idx` (`CodVoucher`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bancopersona`
--

CREATE TABLE IF NOT EXISTS `bancopersona` (
  `CodSecuencia` varchar(6) NOT NULL,
  `CodBanco` varchar(4) NOT NULL,
  `CodPersona` varchar(6) NOT NULL,
  `TipoCuenta` varchar(2) NOT NULL,
  `Ncuenta` varchar(30) NOT NULL,
  `Aportes` varchar(2) NOT NULL,
  `Monto` decimal(11,2) NOT NULL DEFAULT '0.00',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  `FlagPrincipal` varchar(1) NOT NULL DEFAULT 'N',
  PRIMARY KEY (`CodPersona`,`Ncuenta`),
  UNIQUE KEY `CodSecuencia` (`CodSecuencia`),
  KEY `bancopersona_ibfk_2` (`CodPersona`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `compromisos_documentos`
--
CREATE TABLE IF NOT EXISTS `compromisos_documentos` (
`cod_partida` varchar(12)
,`nompartida` varchar(300)
,`CodTipoDocumento` varchar(3)
,`NroDocumento` varchar(25)
,`Fecha` date
,`NomProveedor` varchar(100)
);
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cp_documentoacuserecibo`
--

CREATE TABLE IF NOT EXISTS `cp_documentoacuserecibo` (
  `CodOrganismo` varchar(4) NOT NULL DEFAULT '',
  `Cod_Documento` varchar(20) NOT NULL DEFAULT '',
  `Cod_TipoDocumento` varchar(4) NOT NULL DEFAULT '',
  `CodAcuse` varchar(4) NOT NULL DEFAULT '',
  `Periodo` varchar(4) NOT NULL DEFAULT '',
  `FechaAcuse` date NOT NULL DEFAULT '0000-00-00',
  `CodPersona` varchar(50) NOT NULL DEFAULT '',
  `CodDependencia` varchar(50) NOT NULL DEFAULT '',
  `CodCargo` varchar(100) NOT NULL DEFAULT '',
  `PersonaRecibido` varchar(30) NOT NULL DEFAULT '',
  `CargoPersonaRecibido` varchar(30) NOT NULL DEFAULT '',
  `FechaRecibido` date NOT NULL DEFAULT '0000-00-00',
  `HoraRecibido` time NOT NULL DEFAULT '00:00:00',
  `CedulaRecibido` varchar(12) NOT NULL DEFAULT '',
  `LugarRecibido` varchar(60) NOT NULL DEFAULT '',
  `Observaciones` longtext NOT NULL,
  `UltimoUsuario` char(15) NOT NULL DEFAULT '',
  `UltimaFechaModif` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`CodOrganismo`,`Cod_Documento`,`Cod_TipoDocumento`,`CodAcuse`,`Periodo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cp_documentodistribucion`
--

CREATE TABLE IF NOT EXISTS `cp_documentodistribucion` (
  `Cod_Organismo` varchar(4) NOT NULL DEFAULT '',
  `Cod_Documento` varchar(20) NOT NULL DEFAULT '',
  `Cod_TipoDocumento` varchar(4) NOT NULL DEFAULT '',
  `Periodo` varchar(4) NOT NULL DEFAULT '',
  `CodPersona` varchar(6) NOT NULL DEFAULT '',
  `CodCargo` int(5) NOT NULL,
  `CodDependencia` varchar(4) NOT NULL DEFAULT '' COMMENT 'Dependencia Interna Remitente cuando es EXT, Destinataria INT',
  `CC` varchar(1) NOT NULL DEFAULT 'N',
  `FechaDistribucion` date NOT NULL DEFAULT '0000-00-00',
  `PlazoAtencion` varchar(2) NOT NULL DEFAULT '',
  `Procedencia` varchar(3) NOT NULL DEFAULT '' COMMENT 'EXT: Externa; INT: Interna',
  `Cod_PersonaResp` varchar(6) NOT NULL DEFAULT '',
  `Cod_CargoResp` varchar(4) NOT NULL DEFAULT '',
  `FechaEnvio` date NOT NULL DEFAULT '0000-00-00',
  `Estado` char(2) NOT NULL DEFAULT '',
  `UltimoUsuario` varchar(15) NOT NULL DEFAULT '',
  `UltimaFechaModif` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`Cod_Organismo`,`Cod_Documento`,`Cod_TipoDocumento`,`Periodo`,`CodPersona`),
  KEY `Index_Cod_Documento` (`Cod_Documento`),
  KEY `Index_Cod_TipoDocumento` (`Cod_TipoDocumento`),
  KEY `Index_Periodo` (`Periodo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cp_documentodistribucionext`
--

CREATE TABLE IF NOT EXISTS `cp_documentodistribucionext` (
  `CodOrganismo` varchar(4) NOT NULL DEFAULT '',
  `Cod_Documento` varchar(4) NOT NULL DEFAULT '',
  `Cod_TipoDocumento` varchar(4) NOT NULL DEFAULT '',
  `Periodo` varchar(4) NOT NULL DEFAULT '',
  `Secuencia` varchar(4) NOT NULL DEFAULT '',
  `Cod_Organismos` varchar(4) NOT NULL DEFAULT '',
  `Cod_Dependencia` varchar(4) NOT NULL DEFAULT '',
  `Representante` varchar(100) NOT NULL DEFAULT '',
  `Cargo` varchar(100) NOT NULL DEFAULT '',
  `Atencion` varchar(1) NOT NULL,
  `FechaDistribucion` date NOT NULL DEFAULT '0000-00-00',
  `FlagEsParticular` char(1) NOT NULL DEFAULT '',
  `PlazoAtencion` varchar(2) NOT NULL DEFAULT '',
  `Cod_PersonaResp` varchar(6) NOT NULL DEFAULT '',
  `Cod_CargoResp` varchar(4) NOT NULL DEFAULT '',
  `FechaEnvio` date NOT NULL DEFAULT '0000-00-00',
  `MotivoDevolucion` longtext NOT NULL,
  `FechaDevolucion` date NOT NULL DEFAULT '0000-00-00',
  `Estado` char(2) NOT NULL DEFAULT '',
  `UltimoUsuario` varchar(15) NOT NULL DEFAULT '',
  `UltimaFechaModif` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`CodOrganismo`,`Cod_Documento`,`Cod_TipoDocumento`,`Periodo`,`Secuencia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cp_documentoextentrada`
--

CREATE TABLE IF NOT EXISTS `cp_documentoextentrada` (
  `CodOrganismo` varchar(4) NOT NULL DEFAULT '',
  `Cod_Documento` varchar(4) NOT NULL DEFAULT '',
  `Cod_TipoDocumento` varchar(4) NOT NULL DEFAULT '',
  `Periodo` varchar(4) NOT NULL DEFAULT '',
  `NumeroRegistroInt` varchar(9) NOT NULL DEFAULT '',
  `FechaRegistro` varchar(10) NOT NULL DEFAULT '',
  `Cod_Organismos` varchar(4) NOT NULL DEFAULT '',
  `Cod_Dependencia` varchar(4) NOT NULL DEFAULT '',
  `Remitente` varchar(100) NOT NULL DEFAULT '',
  `Cargo` varchar(60) NOT NULL DEFAULT '',
  `NumeroDocumentoExt` varchar(15) NOT NULL DEFAULT '',
  `FechaDocumentoExt` date NOT NULL DEFAULT '0000-00-00',
  `Asunto` varchar(100) NOT NULL DEFAULT '',
  `Descripcion` longtext NOT NULL,
  `RecibidoPor` varchar(6) NOT NULL DEFAULT '',
  `CargoRecibidoPor` varchar(4) NOT NULL DEFAULT '',
  `FlagEsParticular` char(1) NOT NULL DEFAULT '' COMMENT 'S:Si ; N:No',
  `Folio` varchar(4) NOT NULL,
  `AnexoFolio` varchar(4) NOT NULL,
  `Carpetas` varchar(4) NOT NULL,
  `DescpFolio` longtext NOT NULL,
  `Mensajero` varchar(100) NOT NULL,
  `CedulaMensajero` varchar(12) NOT NULL,
  `FlagInformeEscrito` char(1) NOT NULL DEFAULT '',
  `FlagHablarConmigo` char(1) NOT NULL DEFAULT '',
  `FlagCoordinarcon` char(1) NOT NULL DEFAULT '',
  `FlagPrepararMemo` char(1) NOT NULL DEFAULT '',
  `FlagInvestigarInformar` char(1) NOT NULL DEFAULT '',
  `FlagTramitarConclusion` char(1) NOT NULL DEFAULT '',
  `FlagDistribuir` char(1) NOT NULL DEFAULT '',
  `FlagConocimiento` char(1) NOT NULL DEFAULT '',
  `FlagPrepararConstentacion` char(1) NOT NULL DEFAULT '',
  `FlagArchivar` char(1) NOT NULL DEFAULT '',
  `FlagRegistrode` char(1) NOT NULL DEFAULT '',
  `FlagPrepararOficio` char(1) NOT NULL DEFAULT '',
  `FlagConocerOpinion` char(1) NOT NULL DEFAULT '',
  `FlagTramitarloCaso` char(1) NOT NULL DEFAULT '',
  `FlagAcusarRecibo` char(1) NOT NULL DEFAULT '',
  `FlagTramitarEn` char(1) NOT NULL DEFAULT '',
  `CoordinarCon` varchar(45) NOT NULL DEFAULT '',
  `PrepararMemo` varchar(45) NOT NULL DEFAULT '',
  `RegistroDe` varchar(45) NOT NULL DEFAULT '',
  `PrepararOficio` varchar(45) NOT NULL DEFAULT '',
  `TramitarEn` varchar(45) NOT NULL DEFAULT '',
  `Observacion` varchar(45) NOT NULL DEFAULT '',
  `Estado` char(2) NOT NULL DEFAULT '',
  `UltimoUsuario` varchar(50) NOT NULL DEFAULT '',
  `UltimaFechaModif` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`CodOrganismo`,`Cod_Documento`,`Cod_TipoDocumento`,`Periodo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cp_documentoextsalida`
--

CREATE TABLE IF NOT EXISTS `cp_documentoextsalida` (
  `CodOrganismo` varchar(4) NOT NULL DEFAULT '',
  `Cod_Documento` varchar(4) NOT NULL DEFAULT '',
  `Cod_TipoDocumento` varchar(4) NOT NULL DEFAULT '',
  `Periodo` varchar(4) NOT NULL DEFAULT '',
  `Cod_DocumentoCompleto` varchar(35) NOT NULL DEFAULT '',
  `Cod_Dependencia` varchar(4) NOT NULL DEFAULT '',
  `CodInterno` varchar(5) NOT NULL DEFAULT '',
  `FechaRegistro` date NOT NULL DEFAULT '0000-00-00',
  `Remitente` varchar(100) NOT NULL DEFAULT '',
  `Cargo` varchar(50) NOT NULL DEFAULT '',
  `Asunto` varchar(100) NOT NULL DEFAULT '',
  `Descripcion` longtext NOT NULL,
  `PlazoAtencion` varchar(2) NOT NULL DEFAULT '',
  `FechaDocumento` date NOT NULL DEFAULT '0000-00-00',
  `Contenido` longtext NOT NULL,
  `MediaFirma` varchar(5) NOT NULL DEFAULT '',
  `FechaEnvio` date NOT NULL DEFAULT '0000-00-00',
  `FechaAnulado` date NOT NULL DEFAULT '0000-00-00',
  `MotivoAnulado` varchar(15) NOT NULL DEFAULT '',
  `Estado` char(2) NOT NULL DEFAULT '',
  `UltimoUsuario` varchar(50) NOT NULL DEFAULT '',
  `UltimaFechaModif` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`CodOrganismo`,`Cod_Documento`,`Cod_TipoDocumento`,`Periodo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cp_documentointerno`
--

CREATE TABLE IF NOT EXISTS `cp_documentointerno` (
  `CodOrganismo` varchar(4) NOT NULL DEFAULT '',
  `Cod_Documento` varchar(4) NOT NULL DEFAULT '',
  `Cod_TipoDocumento` varchar(4) NOT NULL DEFAULT '',
  `Periodo` varchar(4) NOT NULL DEFAULT '',
  `Cod_Dependencia` varchar(4) NOT NULL DEFAULT '',
  `Cod_DocumentoCompleto` varchar(25) NOT NULL DEFAULT '',
  `CodInterno` varchar(10) NOT NULL DEFAULT '',
  `FechaRegistro` date NOT NULL DEFAULT '0000-00-00',
  `Cod_Remitente` varchar(6) NOT NULL DEFAULT '',
  `Cod_CargoRemitente` varchar(4) NOT NULL DEFAULT '',
  `Asunto` varchar(350) NOT NULL DEFAULT '',
  `Descripcion` varchar(200) NOT NULL DEFAULT '',
  `PlazoAtencion` varchar(2) NOT NULL DEFAULT '',
  `FechaDocumento` date NOT NULL DEFAULT '0000-00-00',
  `Contenido` longtext NOT NULL,
  `MediaFirma` varchar(5) NOT NULL DEFAULT '',
  `MotivoAnulado` varchar(15) NOT NULL DEFAULT '',
  `FechaAnulado` date NOT NULL DEFAULT '0000-00-00',
  `Estado` char(2) NOT NULL DEFAULT '' COMMENT 'PR: En Preparacion, PE: Pendiente, EV:Enviado, CO: Completado',
  `UltimoUsuario` varchar(15) NOT NULL DEFAULT '',
  `UltimaFechaModif` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `FlagsAnexo` char(1) NOT NULL DEFAULT '' COMMENT 'S= Si, N= No',
  `DescripcionAnexo` longtext NOT NULL,
  PRIMARY KEY (`CodOrganismo`,`Cod_Documento`,`Cod_TipoDocumento`,`Periodo`,`Cod_Dependencia`),
  KEY `Index_Cod_Documento` (`Cod_Documento`),
  KEY `Index_Cod_DocumentoCompleto` (`Cod_DocumentoCompleto`),
  KEY `Index_Periodo` (`Periodo`),
  KEY `Index_Cod_TipoDocumento` (`Cod_TipoDocumento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 9216 kB';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cp_historicodocumentoextsalida`
--

CREATE TABLE IF NOT EXISTS `cp_historicodocumentoextsalida` (
  `CodOrganismo` varchar(4) NOT NULL DEFAULT '',
  `Cod_Documento` varchar(4) NOT NULL DEFAULT '',
  `Cod_TipoDocumento` varchar(4) NOT NULL DEFAULT '',
  `Cod_Historico` varchar(4) NOT NULL DEFAULT '',
  `Periodo` varchar(4) NOT NULL DEFAULT '',
  `Secuencia` varchar(4) NOT NULL DEFAULT '',
  `CodDependencia` varchar(4) NOT NULL DEFAULT '',
  `FechaRegistro` date NOT NULL DEFAULT '0000-00-00',
  `Cod_Organismos` varchar(4) NOT NULL DEFAULT '',
  `Cod_Dependencia` varchar(4) NOT NULL DEFAULT '',
  `Destinatario` varchar(100) NOT NULL DEFAULT '',
  `CargoDestinatario` varchar(100) NOT NULL DEFAULT '',
  `Remitente` varchar(100) NOT NULL DEFAULT '',
  `Cargo` varchar(50) NOT NULL DEFAULT '',
  `Asunto` varchar(100) NOT NULL DEFAULT '',
  `Descripcion` longtext NOT NULL,
  `FechaDocumento` date NOT NULL DEFAULT '0000-00-00',
  `Contenido` longtext NOT NULL,
  `Cod_PersonaResp` varchar(6) NOT NULL DEFAULT '',
  `Cod_CargoResp` varchar(4) NOT NULL DEFAULT '',
  `FechaEnvio` date NOT NULL DEFAULT '0000-00-00',
  `FechaDevolucion` date NOT NULL DEFAULT '0000-00-00',
  `MotivoDevolucion` varchar(200) NOT NULL DEFAULT '',
  `Estado` char(2) NOT NULL DEFAULT '',
  `UltimoUsuario` varchar(50) NOT NULL DEFAULT '',
  `UltimaFechaModif` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `FechaRecibido` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`CodOrganismo`,`Cod_Documento`,`Cod_TipoDocumento`,`Cod_Historico`,`Periodo`,`Secuencia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cp_particular`
--

CREATE TABLE IF NOT EXISTS `cp_particular` (
  `CodParticular` varchar(4) NOT NULL DEFAULT '',
  `Cedula` varchar(12) NOT NULL DEFAULT '',
  `Nombre` varchar(100) NOT NULL,
  `Cargo` varchar(50) NOT NULL DEFAULT '',
  `Direccion` varchar(150) NOT NULL,
  `Telefono` varchar(25) NOT NULL DEFAULT '',
  `UltimoUsuario` varchar(25) NOT NULL DEFAULT '',
  `UltimaFechaModif` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`CodParticular`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cp_tipocorrespondencia`
--

CREATE TABLE IF NOT EXISTS `cp_tipocorrespondencia` (
  `Cod_TipoDocumento` varchar(4) NOT NULL DEFAULT '',
  `Descripcion` varchar(15) NOT NULL DEFAULT '',
  `FlagUsoInterno` char(1) NOT NULL DEFAULT '',
  `FlagUsoExterno` char(1) NOT NULL DEFAULT '',
  `FlagProcedenciaExterna` char(1) NOT NULL DEFAULT '' COMMENT 'Activo= A - Inactivo= I',
  `Estado` char(1) NOT NULL DEFAULT '',
  `UltimoUsuario` char(15) NOT NULL DEFAULT '',
  `UltimaFechaModif` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `DescripCorta` char(2) NOT NULL DEFAULT '',
  PRIMARY KEY (`Cod_TipoDocumento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dt_asesor`
--

CREATE TABLE IF NOT EXISTS `dt_asesor` (
  `co_asistencia` int(11) NOT NULL,
  `co_asesor` varchar(255) NOT NULL,
  PRIMARY KEY (`co_asesor`,`co_asistencia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dt_asistencia`
--

CREATE TABLE IF NOT EXISTS `dt_asistencia` (
  `co_asistencia` int(11) NOT NULL AUTO_INCREMENT,
  `co_persona` varchar(255) DEFAULT NULL,
  `co_unidad` varchar(255) DEFAULT NULL,
  `co_modalidad` varchar(255) DEFAULT NULL,
  `co_evaluacion` varchar(255) DEFAULT NULL,
  `fe_solicitud` datetime DEFAULT NULL,
  `fe_aprobacion` datetime DEFAULT NULL,
  `fe_ejecucion` datetime DEFAULT NULL,
  `fe_finalizacion` datetime DEFAULT NULL,
  `tx_status` varchar(255) DEFAULT NULL,
  `tx_observacion` varchar(400) DEFAULT NULL,
  `tx_asunto` varchar(255) DEFAULT NULL,
  `tx_tipo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`co_asistencia`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dt_receptores`
--

CREATE TABLE IF NOT EXISTS `dt_receptores` (
  `co_asistencia` varchar(255) NOT NULL,
  `co_receptores` varchar(255) NOT NULL,
  PRIMARY KEY (`co_receptores`,`co_asistencia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dt_servicio`
--

CREATE TABLE IF NOT EXISTS `dt_servicio` (
  `codServicio` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_solicitud` datetime DEFAULT NULL,
  `codSolicitante` varchar(255) DEFAULT NULL,
  `codFuncionario` varchar(255) DEFAULT NULL,
  `fechaInicio` datetime DEFAULT NULL,
  `fechaFin` datetime DEFAULT NULL,
  `CodTipoServicio` int(11) DEFAULT NULL,
  `codStatus` varchar(4) DEFAULT NULL,
  `descripcion` text,
  PRIMARY KEY (`codServicio`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ev_evento_capacitacion`
--

CREATE TABLE IF NOT EXISTS `ev_evento_capacitacion` (
  `co_id_evento` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hh_fecha1` date DEFAULT NULL,
  `hh_fecha2` date DEFAULT NULL,
  `co_lugar` int(11) DEFAULT NULL,
  `co_id` int(11) DEFAULT NULL,
  `tx_nombre_evento` varchar(500) DEFAULT NULL,
  `tx_descripcion_evento` text,
  `hh_hora1` varchar(8) DEFAULT NULL,
  `hh_hora2` varchar(8) DEFAULT NULL,
  `bo_certificado` tinyint(1) DEFAULT NULL,
  `eliminado` tinyint(1) DEFAULT '0',
  `bo_certificado_ponente` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`co_id_evento`),
  UNIQUE KEY `co_id_evento` (`co_id_evento`),
  KEY `eventocapacitacion_lugarevento` (`co_lugar`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ev_evento_temas`
--

CREATE TABLE IF NOT EXISTS `ev_evento_temas` (
  `co_evento_tema` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `co_id_evento` int(10) DEFAULT NULL,
  `co_tema` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`co_evento_tema`),
  UNIQUE KEY `co_evento_tema` (`co_evento_tema`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ev_lugares_evento`
--

CREATE TABLE IF NOT EXISTS `ev_lugares_evento` (
  `co_lugares` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre_lugar` varchar(1000) DEFAULT NULL,
  `eliminado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`co_lugares`),
  UNIQUE KEY `co_lugares` (`co_lugares`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ev_persona_evento`
--

CREATE TABLE IF NOT EXISTS `ev_persona_evento` (
  `co_id_persona_evento` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `CodPersona` varchar(6) DEFAULT NULL,
  `bo_culmino_evento` tinyint(1) DEFAULT NULL,
  `bo_recibio_certificado` tinyint(1) DEFAULT NULL,
  `bo_ponente` tinyint(1) DEFAULT NULL,
  `tx_nu_certificado` int(10) DEFAULT NULL,
  `co_id_evento` int(11) DEFAULT NULL,
  `eliminado` tinyint(1) DEFAULT '0',
  `bo_ponente_1` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`co_id_persona_evento`),
  UNIQUE KEY `co_id_persona_evento` (`co_id_persona_evento`),
  KEY `personaevento_mastpersonas` (`CodPersona`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=318 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ev_temas`
--

CREATE TABLE IF NOT EXISTS `ev_temas` (
  `co_tema` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tx_tema` varchar(1000) DEFAULT NULL,
  `eliminado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`co_tema`),
  UNIQUE KEY `co_tema` (`co_tema`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ev_tipo_capacitacion`
--

CREATE TABLE IF NOT EXISTS `ev_tipo_capacitacion` (
  `co_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tx_nombre_cap` varchar(50) DEFAULT NULL,
  `tx_descripcion_cap` varchar(250) DEFAULT NULL,
  `eliminado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`co_id`),
  UNIQUE KEY `co_id` (`co_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_actainicio`
--

CREATE TABLE IF NOT EXISTS `lg_actainicio` (
  `CodActaInicio` bigint(10) NOT NULL,
  `CodPersonaAsistente` varchar(6) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `CodPersonaAsistente2` varchar(6) DEFAULT NULL,
  `CodPersonaDirector` varchar(6) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `UltimoUsuario` varchar(25) NOT NULL,
  `UltimaFechaModif` datetime NOT NULL,
  `AnioActa` varchar(4) NOT NULL,
  `FechaCreacion` date NOT NULL,
  `NroVisualActaInicio` int(20) NOT NULL,
  `Estado` varchar(3) NOT NULL DEFAULT 'PR' COMMENT 'PR: PREPARACION, AD: ADJUDICADO, DS: DESIERTO, RV: REVISADO, AP: APROBADO',
  `MotivoAnulacion` varchar(150) DEFAULT NULL,
  `FechaReunion` date NOT NULL,
  `HoraReunion` time NOT NULL,
  `PresupuestoBase` float(11,2) NOT NULL,
  `FechaInicio` date NOT NULL,
  `FechaFin` date NOT NULL,
  PRIMARY KEY (`CodActaInicio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=44 COMMENT='almacena los datos del acata de inicio que se relaciona a lg';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_activofijo`
--

CREATE TABLE IF NOT EXISTS `lg_activofijo` (
  `CodOrganismo` varchar(4) NOT NULL,
  `Anio` year(4) NOT NULL,
  `NroOrden` varchar(10) NOT NULL,
  `Secuencia` int(4) NOT NULL,
  `NroSecuencia` int(4) NOT NULL,
  `CommoditySub` varchar(6) NOT NULL COMMENT 'lg_commoditysub->CommoditySub',
  `Descripcion` varchar(255) NOT NULL,
  `CodCentroCosto` varchar(4) NOT NULL COMMENT 'ac_mastcentrocosto->CodCentroCosto',
  `CodClasificacion` varchar(6) NOT NULL COMMENT 'af_clasificacionactivo->CodClasificacion',
  `CodBarra` varchar(25) NOT NULL,
  `NroSerie` varchar(10) NOT NULL,
  `Modelo` varchar(50) NOT NULL,
  `CodProveedor` varchar(6) NOT NULL COMMENT 'mastproveedores->CodProveedor',
  `CodDocumento` varchar(2) NOT NULL COMMENT 'lg_transaccion->CodDocumento',
  `NroDocumento` varchar(25) NOT NULL COMMENT 'lg_transaccion->NroDocumento',
  `Monto` decimal(11,2) NOT NULL,
  `CodUbicacion` varchar(4) NOT NULL COMMENT 'af_ubicaciones->CodUbicacion',
  `FechaIngreso` date NOT NULL,
  `FlagFacturado` varchar(1) NOT NULL DEFAULT 'N',
  `CodMarca` varchar(4) NOT NULL COMMENT 'lg_marcas->CodMarca',
  `Color` varchar(2) NOT NULL COMMENT 'mastmiscelaneosdet->COLOR',
  `NroPlaca` varchar(15) NOT NULL,
  `NumeroGuia` varchar(10) NOT NULL,
  `NumeroGuiaFecha` date NOT NULL,
  `NumeroOrdenFecha` date NOT NULL,
  `Estado` varchar(2) NOT NULL COMMENT 'PE:PENDIENTE; TR:TRANSFERIDO;',
  `ObligacionTipoDocumento` varchar(2) NOT NULL COMMENT 'ap_obligaciones->TipoDocumento',
  `ObligacionNroDocumento` varchar(25) NOT NULL COMMENT 'ap_obligaciones->NroDocumento',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  `ObligacionFechaDocumento` date NOT NULL DEFAULT '0000-00-00',
  `Clasificacion` varchar(3) NOT NULL COMMENT 'lg_clasificacion->Clasificacion',
  `Activo` varchar(10) NOT NULL,
  PRIMARY KEY (`CodOrganismo`,`NroOrden`,`Secuencia`,`NroSecuencia`,`Anio`),
  KEY `FK_lg_activofijo_1` (`CommoditySub`),
  KEY `FK_lg_activofijo_2` (`CodCentroCosto`),
  KEY `FK_lg_activofijo_3` (`CodClasificacion`),
  KEY `FK_lg_activofijo_4` (`CodUbicacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_adjudicaciondetalle`
--

CREATE TABLE IF NOT EXISTS `lg_adjudicaciondetalle` (
  `CodAdjudicaionDetalle` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `CodAdjudicacion` bigint(20) unsigned NOT NULL,
  `CodRequerimiento` varchar(10) NOT NULL,
  `Secuencia` bigint(20) unsigned NOT NULL,
  `UltimoUsuario` varchar(25) NOT NULL,
  `UltimaFechaModif` datetime NOT NULL,
  PRIMARY KEY (`CodAdjudicaionDetalle`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=56 AUTO_INCREMENT=835 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_almacenmast`
--

CREATE TABLE IF NOT EXISTS `lg_almacenmast` (
  `CodAlmacen` varchar(6) NOT NULL,
  `Descripcion` varchar(50) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `CodDependencia` varchar(4) NOT NULL COMMENT 'mastdependencias->CodDependencia',
  `TipoAlmacen` varchar(1) NOT NULL COMMENT 'P:PRINCIPAL; T:TRANSITO; V:VENTA',
  `AlmacenTransito` varchar(1) NOT NULL,
  `Direccion` mediumtext NOT NULL,
  `FlagVenta` varchar(1) NOT NULL,
  `FlagProduccion` varchar(1) NOT NULL,
  `FlagCommodity` varchar(1) NOT NULL,
  `CodPersona` varchar(6) NOT NULL,
  `CuentaInventario` varchar(10) NOT NULL,
  `Estado` varchar(1) NOT NULL COMMENT 'A:ACTIVO; I:INACTIVO',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodAlmacen`),
  UNIQUE KEY `FK_lg_almacenmast_1` (`Descripcion`,`CodOrganismo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_cierremensual`
--

CREATE TABLE IF NOT EXISTS `lg_cierremensual` (
  `Periodo` varchar(7) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `CodAlmacen` varchar(6) NOT NULL COMMENT 'lg_almacenmast->CodAlmacen',
  `CodItem` varchar(10) NOT NULL COMMENT 'lg_itemmast->CodItem',
  `IngresoROC` decimal(11,6) NOT NULL,
  `IngresoOtros` decimal(11,6) NOT NULL,
  `IngresoTraslado` decimal(11,6) NOT NULL,
  `SalidaREQ` decimal(11,6) NOT NULL,
  `SalidaOtros` decimal(11,6) NOT NULL,
  `SalidaTraslado` decimal(11,6) NOT NULL,
  `StockAnterior` decimal(11,6) NOT NULL,
  `StockNuevo` decimal(11,6) NOT NULL,
  `Precio` decimal(11,6) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodOrganismo`,`CodAlmacen`,`CodItem`,`Periodo`),
  KEY `FK_lg_cierremensual_1` (`CodAlmacen`),
  KEY `FK_lg_cierremensual_2` (`CodItem`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_cierremensualsustento`
--

CREATE TABLE IF NOT EXISTS `lg_cierremensualsustento` (
  `Periodo` varchar(7) NOT NULL COMMENT 'lg_cierremensual->Periodo',
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'lg_cierremensual->CodOrganismo',
  `CodAlmacen` varchar(6) NOT NULL COMMENT 'lg_cierremensual->CodAlmacen',
  `CodItem` varchar(10) NOT NULL COMMENT 'lg_cierremensual->CodItem',
  `Secuencia` int(10) NOT NULL,
  `Cantidad` decimal(11,6) NOT NULL,
  `CantidadAcumulada` decimal(11,6) NOT NULL,
  `Precio` decimal(11,6) NOT NULL,
  `FechaRecepcion` date NOT NULL,
  `TransaccionCodDocumento` varchar(2) NOT NULL COMMENT 'lg_transacciondetalle->CodDocumento',
  `TransaccionNroDocumento` varchar(10) NOT NULL COMMENT 'lg_transacciondetalle->NroDocumento',
  `TransaccionSecuencia` int(10) NOT NULL COMMENT 'lg_transacciondetalle->Secuencia',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodOrganismo`,`CodAlmacen`,`CodItem`,`Periodo`,`Secuencia`),
  KEY `FK_lg_cierremensualsustento_1` (`CodAlmacen`),
  KEY `FK_lg_cierremensualsustento_2` (`CodItem`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_clasefamilia`
--

CREATE TABLE IF NOT EXISTS `lg_clasefamilia` (
  `CodLinea` varchar(6) NOT NULL,
  `CodFamilia` varchar(6) NOT NULL,
  `Descripcion` varchar(50) NOT NULL,
  `CuentaInventario` varchar(8) NOT NULL,
  `CuentaGasto` varchar(8) NOT NULL,
  `CuentaVentas` varchar(8) NOT NULL,
  `PartidaPresupuestal` varchar(9) NOT NULL,
  `Estado` varchar(1) NOT NULL COMMENT 'A:ACTIVO; I:INACTIVO',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodLinea`,`CodFamilia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_claselinea`
--

CREATE TABLE IF NOT EXISTS `lg_claselinea` (
  `CodLinea` varchar(6) NOT NULL,
  `Descripcion` varchar(50) NOT NULL,
  `Estado` varchar(1) NOT NULL COMMENT 'A:ACTIVO; I:INACTIVO',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodLinea`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_clasesubfamilia`
--

CREATE TABLE IF NOT EXISTS `lg_clasesubfamilia` (
  `CodLinea` varchar(6) NOT NULL,
  `CodFamilia` varchar(6) NOT NULL,
  `CodSubFamilia` varchar(6) NOT NULL,
  `Descripcion` varchar(50) NOT NULL,
  `Estado` varchar(1) NOT NULL COMMENT 'A:ACTIVO; I:INACTIVO',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodLinea`,`CodFamilia`,`CodSubFamilia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_clasificacion`
--

CREATE TABLE IF NOT EXISTS `lg_clasificacion` (
  `Clasificacion` varchar(3) NOT NULL,
  `Descripcion` varchar(50) NOT NULL,
  `ReqOrdenCompra` varchar(1) NOT NULL COMMENT 'R:REQUISICIONES; O:ORDEN DE COMPRA',
  `CodAlmacen` varchar(6) NOT NULL COMMENT 'lg_almacenmast->CodAlmacen',
  `TipoRequerimiento` varchar(2) NOT NULL COMMENT 'miscelaneos->TIPOREQ',
  `FlagRecepcionAlmacen` varchar(1) NOT NULL DEFAULT 'N',
  `FlagRevision` varchar(1) NOT NULL DEFAULT 'N',
  `ReqAlmacenCompra` varchar(1) NOT NULL DEFAULT 'C' COMMENT 'A:ALMACEN; C:COMPRA',
  `FlagTransaccion` varchar(1) NOT NULL DEFAULT 'N',
  `FlagCajaChica` varchar(1) NOT NULL DEFAULT 'N',
  `Estado` varchar(1) NOT NULL DEFAULT 'A' COMMENT 'A:ACTIVO; I:INACTIVO',
  `FlagActivoFijo` varchar(1) NOT NULL DEFAULT 'N',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Clasificacion`),
  KEY `FK_lg_clasificacion_1` (`CodAlmacen`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_commodityclasificacion`
--

CREATE TABLE IF NOT EXISTS `lg_commodityclasificacion` (
  `Clasificacion` varchar(3) NOT NULL,
  `Descripcion` varchar(50) NOT NULL,
  `FlagTransaccion` varchar(1) NOT NULL,
  `Estado` varchar(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Clasificacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_commoditymast`
--

CREATE TABLE IF NOT EXISTS `lg_commoditymast` (
  `Clasificacion` varchar(3) NOT NULL COMMENT 'lg_commodityclasificacion->Clasificacion',
  `CommodityMast` varchar(3) NOT NULL,
  `Descripcion` varchar(255) NOT NULL,
  `Estado` varchar(1) NOT NULL COMMENT 'A:ACTIVO; I:INACTIVO',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CommodityMast`),
  KEY `FK_lg_commoditymast_1` (`Clasificacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_commoditystock`
--

CREATE TABLE IF NOT EXISTS `lg_commoditystock` (
  `CodAlmacen` varchar(6) NOT NULL COMMENT 'lg_almacenmast',
  `CommoditySub` varchar(6) NOT NULL COMMENT 'lg_commoditysub->CommoditySub',
  `Cantidad` decimal(11,4) NOT NULL,
  `PrecioUnitario` decimal(11,4) NOT NULL,
  `IngresadoPor` varchar(6) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodAlmacen`,`CommoditySub`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_commoditysub`
--

CREATE TABLE IF NOT EXISTS `lg_commoditysub` (
  `CommodityMast` varchar(3) NOT NULL COMMENT 'lg_commoditymast->CommodityMast',
  `CommoditySub` varchar(3) NOT NULL COMMENT 'lg_commoditysub->CommoditySub',
  `Codigo` varchar(6) NOT NULL,
  `Descripcion` varchar(255) NOT NULL,
  `CodUnidad` varchar(3) NOT NULL COMMENT 'mastunidades->CodUnidad',
  `cod_partida` varchar(12) NOT NULL COMMENT 'pv_partida->cod_partida',
  `CodCuenta` varchar(13) NOT NULL COMMENT 'ac_mastplancuenta->CodCuenta',
  `CodCuentaPub20` varchar(13) NOT NULL COMMENT 'ac_mastplancuenta20->CodCuenta',
  `CodClasificacion` varchar(6) NOT NULL COMMENT 'af_clasificacionactivo',
  `Estado` varchar(1) NOT NULL DEFAULT 'A' COMMENT 'A:ACTIVO; I:INACTIVO',
  `FlagPresupuesto` varchar(1) NOT NULL DEFAULT 'N',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Codigo`),
  UNIQUE KEY `UK_lg_commoditysub_1` (`CommodityMast`,`CommoditySub`),
  KEY `FK_lg_commoditysub_1` (`CommodityMast`),
  KEY `FK_lg_commoditysub_2` (`CodUnidad`),
  KEY `FK_lg_commoditysub_3` (`cod_partida`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_commoditytransaccion`
--

CREATE TABLE IF NOT EXISTS `lg_commoditytransaccion` (
  `CodOrganismo` varchar(4) NOT NULL,
  `CodDocumento` varchar(2) NOT NULL COMMENT 'lg_tipodocumento->CodDocumento',
  `NroDocumento` varchar(6) NOT NULL,
  `NroInterno` varchar(6) DEFAULT NULL,
  `CodTransaccion` varchar(3) NOT NULL COMMENT 'lg_operacioncommodity->CodOperacion',
  `FechaDocumento` date NOT NULL,
  `Periodo` varchar(7) NOT NULL,
  `CodAlmacen` varchar(6) NOT NULL COMMENT 'lg_almacenmast->CodAlmacen',
  `CodCentroCosto` varchar(4) NOT NULL COMMENT 'ac_mastcentrocosto->CodCentroCosto',
  `CodDocumentoReferencia` varchar(2) NOT NULL COMMENT 'lg_tipodocumento->CodDocumento',
  `NroDocumentoReferencia` varchar(20) NOT NULL,
  `IngresadoPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `RecibidoPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `Comentarios` longtext NOT NULL,
  `ReferenciaOrganismo` varchar(4) NOT NULL,
  `ReferenciaNroDocumento` varchar(10) NOT NULL,
  `DocumentoReferencia` varchar(20) NOT NULL,
  `DocumentoReferenciaInterno` varchar(10) NOT NULL,
  `CodUbicacion` varchar(4) NOT NULL COMMENT 'af_ubicaciones->CodUbicacion',
  `FlagActivoFijo` varchar(1) NOT NULL DEFAULT 'N',
  `CodDependencia` varchar(4) NOT NULL COMMENT 'mastdependencias-CodDependencia',
  `Estado` varchar(2) NOT NULL DEFAULT 'EJ' COMMENT 'PE:PENDIENTE; EJ:EJECUTADO;',
  `Anio` year(4) DEFAULT NULL,
  `EjecutadoPor` varchar(6) DEFAULT NULL,
  `FechaEjecucion` date DEFAULT NULL,
  `FlagManual` varchar(1) DEFAULT 'N',
  `FlagPendiente` varchar(1) DEFAULT 'N',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodOrganismo`,`CodDocumento`,`NroDocumento`),
  KEY `FK_lg_commoditytransaccion_1` (`CodTransaccion`),
  KEY `FK_lg_commoditytransaccion_2` (`CodAlmacen`),
  KEY `FK_lg_commoditytransaccion_3` (`CodCentroCosto`),
  KEY `FK_lg_commoditytransaccion_4` (`CodDocumento`),
  KEY `CodUbicacion` (`CodUbicacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_commoditytransacciondetalle`
--

CREATE TABLE IF NOT EXISTS `lg_commoditytransacciondetalle` (
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `CodDocumento` varchar(2) NOT NULL COMMENT 'lg_tipodocumento->CodDocumento',
  `NroDocumento` varchar(10) NOT NULL,
  `Secuencia` int(10) NOT NULL,
  `CommoditySub` varchar(6) NOT NULL COMMENT 'lg_commoditysub->Codigo',
  `Descripcion` varchar(255) DEFAULT NULL,
  `CodUnidad` varchar(3) NOT NULL COMMENT 'mastunidades->CodUnidad',
  `CantidadKardex` decimal(11,4) NOT NULL,
  `Cantidad` decimal(11,4) NOT NULL,
  `PrecioUnit` decimal(11,4) NOT NULL,
  `Total` decimal(11,4) NOT NULL,
  `ReferenciaOrganismo` varchar(4) NOT NULL,
  `ReferenciaCodDocumento` varchar(2) NOT NULL,
  `ReferenciaNroDocumento` varchar(10) NOT NULL,
  `ReferenciaSecuencia` int(10) NOT NULL,
  `CodBarraActivo` varchar(20) NOT NULL,
  `CodAlmacen` varchar(6) NOT NULL COMMENT 'lg_almacenmast->CodAlmacen',
  `CodCentroCosto` varchar(4) NOT NULL COMMENT 'ac_mastcentrocosto->CodCentroCosto',
  `Anio` year(4) DEFAULT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodOrganismo`,`CodDocumento`,`NroDocumento`,`Secuencia`),
  KEY `CodAlmacen` (`CodAlmacen`),
  KEY `CodCentroCosto` (`CodCentroCosto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_confirmacionservicio`
--

CREATE TABLE IF NOT EXISTS `lg_confirmacionservicio` (
  `Anio` varchar(4) NOT NULL COMMENT 'lg_ordenserviciodetalle->Anio',
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'lg_ordenserviciodetalle->CodOrganismo',
  `NroOrden` varchar(10) NOT NULL COMMENT 'lg_ordenserviciodetalle->NroOrden',
  `Secuencia` int(4) NOT NULL COMMENT 'lg_ordenserviciodetalle->Secuencia',
  `NroConfirmacion` varchar(4) NOT NULL,
  `DocumentoReferencia` varchar(15) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`NroConfirmacion`),
  KEY `Index_2` (`Anio`,`CodOrganismo`,`NroOrden`,`Secuencia`),
  KEY `Index_3` (`DocumentoReferencia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_controlperceptivo`
--

CREATE TABLE IF NOT EXISTS `lg_controlperceptivo` (
  `CodControlPerceptivo` bigint(20) NOT NULL,
  `NroOrden` varchar(10) CHARACTER SET latin1 NOT NULL,
  `CodPersonaConforme1` varchar(6) CHARACTER SET latin1 NOT NULL,
  `CodPersonaConforme2` varchar(6) CHARACTER SET latin1 NOT NULL,
  `CodPersonaConforme3` varchar(6) CHARACTER SET latin1 NOT NULL,
  `CodPersonaConforme4` varchar(6) CHARACTER SET latin1 NOT NULL,
  `CodPersonaConforme5` varchar(6) CHARACTER SET latin1 NOT NULL,
  `FechaRegistro` date NOT NULL,
  `Estado` tinyint(1) NOT NULL DEFAULT '1',
  `UltimoUsuario` varchar(6) CHARACTER SET latin1 NOT NULL,
  `UltimaFechaModif` datetime NOT NULL,
  PRIMARY KEY (`CodControlPerceptivo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=60;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_controlperceptivodetalle`
--

CREATE TABLE IF NOT EXISTS `lg_controlperceptivodetalle` (
  `CodControlPerceptivoDetalle` int(20) NOT NULL,
  `CodControlPerceptivo` int(20) NOT NULL,
  `CodItem` varchar(10) CHARACTER SET latin1 NOT NULL,
  `Secuencia` int(11) NOT NULL,
  `Recibido` tinyint(1) DEFAULT NULL,
  `ObservacionItem` text NOT NULL,
  `CantidadRecibida` double NOT NULL,
  `UltimoUsuario` varchar(6) CHARACTER SET latin1 NOT NULL,
  `UltimaFechaModif` datetime NOT NULL,
  PRIMARY KEY (`CodControlPerceptivoDetalle`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=47;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_cotizacion`
--

CREATE TABLE IF NOT EXISTS `lg_cotizacion` (
  `CotizacionSecuencia` int(10) NOT NULL AUTO_INCREMENT,
  `CodRequerimiento` varchar(10) NOT NULL,
  `Secuencia` int(4) unsigned NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `CotizacionNumero` varchar(8) NOT NULL,
  `Numero` int(10) NOT NULL,
  `FechaInvitacion` date NOT NULL,
  `FechaDocumento` date NOT NULL,
  `FechaApertura` date NOT NULL,
  `FechaRecepcion` date NOT NULL,
  `CodProveedor` varchar(6) NOT NULL COMMENT 'mastproveedores->CodProveedor',
  `NomProveedor` varchar(100) NOT NULL,
  `FechaEntrega` date NOT NULL,
  `CodFormaPago` varchar(3) NOT NULL COMMENT 'mastformapago->CodFormaPago',
  `PrecioUnit` decimal(11,4) NOT NULL DEFAULT '0.0000',
  `PrecioUnitInicio` decimal(11,4) NOT NULL DEFAULT '0.0000',
  `PrecioUnitInicioIva` decimal(11,4) NOT NULL DEFAULT '0.0000',
  `PrecioUnitIva` decimal(11,4) NOT NULL DEFAULT '0.0000',
  `PrecioCantidad` decimal(11,4) NOT NULL DEFAULT '0.0000',
  `Total` decimal(11,4) NOT NULL DEFAULT '0.0000',
  `ValidezOferta` int(10) NOT NULL DEFAULT '0',
  `DiasEntrega` int(10) NOT NULL,
  `NroCotizacionProv` varchar(10) NOT NULL,
  `FlagAsignado` varchar(1) NOT NULL DEFAULT 'N',
  `FlagExonerado` varchar(1) NOT NULL DEFAULT 'N',
  `Cantidad` decimal(11,4) NOT NULL DEFAULT '0.0000',
  `DescuentoFijo` decimal(11,4) NOT NULL DEFAULT '0.0000',
  `DescuentoPorcentaje` decimal(11,4) NOT NULL DEFAULT '0.0000',
  `FechaLimite` date NOT NULL,
  `Condiciones` longtext NOT NULL,
  `Observaciones` longtext NOT NULL,
  `Estado` varchar(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  `NumeroCotizacion` varchar(10) NOT NULL,
  `NumeroInvitacion` varchar(10) NOT NULL,
  `NroSolicitudCotizacion` bigint(10) NOT NULL,
  PRIMARY KEY (`CotizacionSecuencia`),
  KEY `FK_lg_cotizacion_1` (`CodRequerimiento`,`Secuencia`),
  KEY `Index_3` (`CodOrganismo`),
  KEY `Index_4` (`Numero`),
  KEY `Index_5` (`CotizacionNumero`),
  KEY `FK_lg_cotizacion_2` (`CodProveedor`),
  KEY `FK_lg_cotizacion_3` (`CodFormaPago`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6148 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_cualitativacuantitativa`
--

CREATE TABLE IF NOT EXISTS `lg_cualitativacuantitativa` (
  `CodCualiCuanti` bigint(20) NOT NULL,
  `CodEvaluacion` bigint(20) NOT NULL,
  `CodProveedor` varchar(6) NOT NULL,
  `Secuencia` int(4) NOT NULL,
  `ProvRecRenglon` varchar(1) NOT NULL,
  `PuntajeRenglonOf` float NOT NULL,
  `PuntajeRequeTec` float NOT NULL,
  `PuntajeTiempoEntrega` float NOT NULL,
  `PuntajeCondicionPago` float NOT NULL,
  `TotalPuntajeCuali` float NOT NULL,
  `PMO_POE` float NOT NULL,
  `PP` float NOT NULL,
  `UltimoUsuario` varchar(6) NOT NULL,
  `UltimaFechaModif` datetime NOT NULL,
  PRIMARY KEY (`CodCualiCuanti`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=65;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_declarar_desierto`
--

CREATE TABLE IF NOT EXISTS `lg_declarar_desierto` (
  `CodDesierto` bigint(20) NOT NULL AUTO_INCREMENT,
  `CodInformeRecomendacion` bigint(20) DEFAULT NULL,
  `UltimoUsuario` varchar(6) DEFAULT NULL,
  `UltimaFechaModif` datetime DEFAULT NULL,
  `NroVisualDesierto` bigint(20) NOT NULL,
  `AnioDesierto` varchar(4) NOT NULL,
  PRIMARY KEY (`CodDesierto`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=36 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_distribucioncompromisos`
--

CREATE TABLE IF NOT EXISTS `lg_distribucioncompromisos` (
  `Anio` varchar(4) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `CodPresupuesto` varchar(4) NOT NULL,
  `CodProveedor` varchar(6) NOT NULL COMMENT 'mastproveedores->CodProveedor',
  `CodTipoDocumento` varchar(3) NOT NULL COMMENT 'ap_tipodocumento->CodTipoDocumento; OS; OC;',
  `NroDocumento` varchar(25) NOT NULL COMMENT 'ap_obligaciones->NroDocumento; lg_ordencompra->NroOrden;',
  `Secuencia` int(4) NOT NULL,
  `Linea` int(4) NOT NULL,
  `Mes` varchar(2) NOT NULL,
  `CodCentroCosto` varchar(4) NOT NULL COMMENT 'ac_mastcentrocosto->CodCentroCosto',
  `cod_partida` varchar(12) NOT NULL COMMENT 'pv_partida->cod_partida',
  `Monto` decimal(11,4) NOT NULL,
  `Periodo` varchar(7) DEFAULT NULL,
  `Origen` varchar(2) DEFAULT NULL COMMENT 'OC:ORDEN DE COMPRA; OS:ORDEN DE SERVICIO; OB:OBLIGACIONES; TB:TRANSACCIONES BANCARIAS;',
  `Estado` varchar(2) DEFAULT 'CO' COMMENT 'OC:ORDEN DE COMPRA; OS:ORDEN DE SERVICIO; OB:OBLIGACIONES; TB:TRANSACCIONES BANCARIAS;',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Anio`,`CodOrganismo`,`CodProveedor`,`CodTipoDocumento`,`NroDocumento`,`Secuencia`,`Linea`),
  KEY `FK_lg_distribucioncompromisos_1` (`CodCentroCosto`),
  KEY `FK_lg_distribucioncompromisos_2` (`cod_partida`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Disparadores `lg_distribucioncompromisos`
--
DROP TRIGGER IF EXISTS `lg_distribucioncompromisos_triger1`;
DELIMITER //
CREATE TRIGGER `lg_distribucioncompromisos_triger1` AFTER INSERT ON `lg_distribucioncompromisos`
 FOR EACH ROW BEGIN
	SET @Monto = (SELECT SUM(Monto)
				  FROM lg_distribucioncompromisos
				  WHERE
						CodOrganismo = NEW.CodOrganismo AND
						CodPresupuesto = NEW.CodPresupuesto AND
						cod_partida = NEW.cod_partida AND
						Estado = 'CO');

	UPDATE pv_presupuestodet
	SET MontoCompromiso = @Monto
	WHERE
		Organismo = NEW.CodOrganismo AND
		CodPresupuesto = NEW.CodPresupuesto AND
		cod_partida = NEW.cod_partida;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `lg_distribucioncompromisos_triger2`;
DELIMITER //
CREATE TRIGGER `lg_distribucioncompromisos_triger2` AFTER UPDATE ON `lg_distribucioncompromisos`
 FOR EACH ROW BEGIN
	SET @Monto = (SELECT SUM(Monto)
				  FROM lg_distribucioncompromisos
				  WHERE
						CodOrganismo = NEW.CodOrganismo AND
						CodPresupuesto = NEW.CodPresupuesto AND
						cod_partida = NEW.cod_partida AND
						Estado = 'CO');

	UPDATE pv_presupuestodet
	SET MontoCompromiso = @Monto
	WHERE
		Organismo = NEW.CodOrganismo AND
		CodPresupuesto = NEW.CodPresupuesto AND
		cod_partida = NEW.cod_partida;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `lg_distribucioncompromisos_triger3`;
DELIMITER //
CREATE TRIGGER `lg_distribucioncompromisos_triger3` AFTER DELETE ON `lg_distribucioncompromisos`
 FOR EACH ROW BEGIN
	SET @Monto = (SELECT SUM(Monto)
				  FROM lg_distribucioncompromisos
				  WHERE
						CodOrganismo = OLD.CodOrganismo AND
						CodPresupuesto = OLD.CodPresupuesto AND
						cod_partida = OLD.cod_partida AND
						Estado = 'CO');

	UPDATE pv_presupuestodet
	SET MontoCompromiso = @Monto
	WHERE
		Organismo = OLD.CodOrganismo AND
		CodPresupuesto = OLD.CodPresupuesto AND
		cod_partida = OLD.cod_partida;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_distribucionoc`
--

CREATE TABLE IF NOT EXISTS `lg_distribucionoc` (
  `Anio` varchar(4) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `NroOrden` varchar(10) NOT NULL COMMENT 'lg_ordencompra->NroOrden',
  `Secuencia` int(4) NOT NULL,
  `Mes` varchar(2) NOT NULL,
  `cod_partida` varchar(12) NOT NULL COMMENT 'pv_partida->cod_partida',
  `CodCuenta` varchar(13) NOT NULL COMMENT 'ac_mastplancuenta->CodCuenta',
  `CodCuentaPub20` varchar(13) NOT NULL COMMENT 'ac_mastplancuenta20->CodCuenta',
  `Monto` decimal(11,4) NOT NULL,
  `CodCentroCosto` varchar(4) NOT NULL COMMENT 'ac_mastcentrocosto->CodCentroCosto',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Anio`,`CodOrganismo`,`NroOrden`,`Secuencia`),
  KEY `FK_lg_distribucionoc_2` (`cod_partida`),
  KEY `FK_lg_distribucionoc_4` (`CodCentroCosto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_distribucionos`
--

CREATE TABLE IF NOT EXISTS `lg_distribucionos` (
  `Anio` varchar(4) NOT NULL COMMENT 'lg_ordenservicio->Anio',
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'lg_ordenservicio->CodOrganismo',
  `NroOrden` varchar(10) NOT NULL COMMENT 'lg_ordenservicio->NroOrden',
  `Secuencia` int(4) NOT NULL,
  `Mes` varchar(2) NOT NULL,
  `cod_partida` varchar(12) NOT NULL COMMENT 'pv_partida->cod_partida',
  `CodCuenta` varchar(13) NOT NULL COMMENT 'ac_mastplancuenta->CodCuenta',
  `CodCuentaPub20` varchar(13) NOT NULL COMMENT 'ac_mastplancuenta20->CodCuenta',
  `Monto` decimal(11,4) NOT NULL,
  `CodCentroCosto` varchar(4) NOT NULL COMMENT 'ac_mastcentrocosto->CodCentroCosto',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Anio`,`CodOrganismo`,`NroOrden`,`Secuencia`),
  KEY `FK_lg_distribucionos_2` (`cod_partida`),
  KEY `FK_lg_distribucionos_3` (`CodCuenta`),
  KEY `FK_lg_distribucionos_4` (`CodCentroCosto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_evaluacion`
--

CREATE TABLE IF NOT EXISTS `lg_evaluacion` (
  `CodEvaluacion` bigint(20) NOT NULL,
  `CodActaInicio` bigint(20) NOT NULL,
  `ObjetoEvaluacion` text CHARACTER SET latin1 NOT NULL,
  `CriterioCualitativo` text CHARACTER SET latin1 NOT NULL,
  `CriterioCuantitativo` text CHARACTER SET latin1 NOT NULL,
  `Conclusion` text CHARACTER SET latin1 NOT NULL,
  `Recomendacion` text CHARACTER SET latin1 NOT NULL,
  `CodPersonaAsistente` varchar(6) CHARACTER SET latin1 NOT NULL,
  `CodPersonaAsistente2` varchar(6) DEFAULT NULL,
  `CodPersonaDirector` varchar(6) CHARACTER SET latin1 NOT NULL,
  `UltimoUsuario` varchar(6) CHARACTER SET latin1 NOT NULL,
  `UltimaFechaModif` datetime NOT NULL,
  `AnioEvaluacion` varchar(4) NOT NULL,
  `NroVisualEvaluacion` int(11) NOT NULL,
  `FechaCreacion` date NOT NULL,
  `Estado` varchar(3) NOT NULL DEFAULT 'PR' COMMENT 'PR: PREPARACION, AD: ADJUDICADO, AN: ANULADO, RV: REVISADO, AP: APROBADO',
  PRIMARY KEY (`CodEvaluacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=156;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_informeadjudicacion`
--

CREATE TABLE IF NOT EXISTS `lg_informeadjudicacion` (
  `CodAdjudicacion` bigint(20) NOT NULL,
  `CodInformeRecomendacion` bigint(20) NOT NULL,
  `TipoAdjudicacion` varchar(10) NOT NULL DEFAULT 'DT' COMMENT 'TT: TOATAL, PC: PARCIAL, DT:DIRECTA',
  `FechaCreacion` date NOT NULL,
  `UltimoUsuario` varchar(6) NOT NULL,
  `UltimaFechaModif` datetime NOT NULL,
  `CodProveedor` varchar(6) NOT NULL,
  `Estado` varchar(3) NOT NULL DEFAULT 'PR' COMMENT 'PR: PREPARACION, AD: ADJUDICADO, AN: ANULADO, RV: REVISADO, AP: APROBADO',
  `NroVisualAdjudicacion` int(11) NOT NULL,
  `AnioAdjudicacion` varchar(4) NOT NULL,
  PRIMARY KEY (`CodAdjudicacion`),
  UNIQUE KEY `CodInformeRecomendacion` (`CodInformeRecomendacion`,`CodProveedor`,`AnioAdjudicacion`,`Estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=48;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_informerecomendacion`
--

CREATE TABLE IF NOT EXISTS `lg_informerecomendacion` (
  `CodInformeRecomendacion` bigint(20) unsigned NOT NULL,
  `Conclusiones` text NOT NULL,
  `Recomendacion` text NOT NULL,
  `Asistente` varchar(6) NOT NULL,
  `Asistente2` varchar(6) DEFAULT NULL,
  `Director` varchar(6) NOT NULL,
  `UltimoUsuario` varchar(6) NOT NULL,
  `UltimaFechaModif` datetime NOT NULL,
  `ObjetoConsulta` text NOT NULL,
  `CodEvaluacion` bigint(20) unsigned NOT NULL,
  `Asunto` text NOT NULL,
  `AnioRecomendacion` varchar(4) NOT NULL,
  `NroVisualRecomendacion` bigint(20) NOT NULL,
  `FechaCreacion` date NOT NULL,
  `Estado` varchar(3) NOT NULL DEFAULT 'PR' COMMENT 'PR: PREPARACION, AD: ADJUDICADO, AN: ANULADO, RV: REVISADO, AP: APROBADO',
  `RevisadoPor` varchar(6) NOT NULL,
  `FechaRevisado` datetime NOT NULL,
  `AprobadoPor` varchar(6) NOT NULL,
  `FechaAprobado` datetime NOT NULL,
  `TipoAdjudicacion` varchar(4) NOT NULL DEFAULT 'TT' COMMENT 'ADJUDICACION RECOMENDADA; TT: Total, PR: Parcial',
  `Numeral` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`CodInformeRecomendacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=184;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_itemalmacen`
--

CREATE TABLE IF NOT EXISTS `lg_itemalmacen` (
  `CodItem` varchar(10) NOT NULL COMMENT 'lg_itemmast->CodItem',
  `CodAlmacen` varchar(6) NOT NULL COMMENT 'lg_almacenmast->CodAlmacen',
  `StockPedido` decimal(11,4) NOT NULL DEFAULT '0.0000',
  `StockMax` decimal(11,4) NOT NULL DEFAULT '0.0000',
  `StockMin` decimal(11,4) NOT NULL DEFAULT '0.0000',
  `StockReorden` decimal(11,4) NOT NULL DEFAULT '0.0000',
  `TiempoEspera` int(3) unsigned NOT NULL DEFAULT '0',
  `Ubicacion1` varchar(255) NOT NULL,
  `Ubicacion2` varchar(255) NOT NULL,
  `Ubicacion3` varchar(255) NOT NULL,
  `Estado` varchar(1) NOT NULL COMMENT 'A:ACTIVO; I:INACTIVO',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodItem`,`CodAlmacen`),
  KEY `FK_lg_itemalmacen_1` (`CodItem`),
  KEY `FK_lg_itemalmacen_2` (`CodAlmacen`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 6144 kB';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_itemalmaceninv`
--

CREATE TABLE IF NOT EXISTS `lg_itemalmaceninv` (
  `CodAlmacen` varchar(6) NOT NULL,
  `CodItem` varchar(10) NOT NULL,
  `Proveedor` varchar(6) NOT NULL,
  `FechaIngreso` date NOT NULL,
  `FechaVencimiento` date NOT NULL,
  `StockIngreso` decimal(11,4) NOT NULL DEFAULT '0.0000',
  `StockActual` decimal(11,4) NOT NULL DEFAULT '0.0000',
  `StockComprometido` decimal(11,4) NOT NULL DEFAULT '0.0000',
  `PrecioUnitario` decimal(11,4) NOT NULL DEFAULT '0.0000',
  `DocReferencia` varchar(15) NOT NULL,
  `IngresadoPor` varchar(6) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodAlmacen`,`CodItem`),
  KEY `FK_lg_itemalmacenlote_1` (`CodAlmacen`),
  KEY `FK_lg_itemalmacenlote_2` (`CodItem`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_itemmast`
--

CREATE TABLE IF NOT EXISTS `lg_itemmast` (
  `CodItem` varchar(10) NOT NULL,
  `CodInterno` varchar(10) NOT NULL,
  `Descripcion` varchar(255) NOT NULL,
  `CodTipoItem` varchar(2) NOT NULL COMMENT 'lg_tipoitem->CodTipoItem',
  `CodUnidad` varchar(3) NOT NULL COMMENT 'mastunidades->CodUnidad',
  `CodUnidadComp` varchar(3) NOT NULL COMMENT 'mastunidades->CodUnidad',
  `CodUnidadEmb` varchar(3) NOT NULL COMMENT 'mastunidades->CodUnidad',
  `CodLinea` varchar(6) NOT NULL COMMENT 'lg_clasesubfamilia->CodLinea',
  `CodFamilia` varchar(6) NOT NULL COMMENT 'lg_clasesubfamilia->CodFamilia',
  `CodSubFamilia` varchar(6) NOT NULL COMMENT 'lg_clasesubfamilia->CodSubFamilia',
  `FlagLotes` varchar(1) NOT NULL DEFAULT 'N',
  `FlagItem` varchar(1) NOT NULL DEFAULT 'N',
  `FlagKit` varchar(1) NOT NULL DEFAULT 'N',
  `FlagImpuestoVentas` varchar(1) NOT NULL DEFAULT 'N',
  `FlagAuto` varchar(1) NOT NULL DEFAULT 'N',
  `FlagDisponible` varchar(1) NOT NULL DEFAULT 'N',
  `FlagPresupuesto` varchar(1) NOT NULL DEFAULT 'N',
  `Imagen` varchar(255) NOT NULL,
  `CodMarca` varchar(4) NOT NULL COMMENT 'lg_marcas->CodMarca',
  `Color` varchar(2) NOT NULL COMMENT 'mastmiscelaneosdet->COLOR',
  `CodProcedencia` varchar(6) NOT NULL COMMENT 'lg_procedencias->CodProcedencia',
  `CodBarra` varchar(6) NOT NULL,
  `StockMin` decimal(11,4) NOT NULL DEFAULT '0.0000',
  `StockMax` decimal(11,4) NOT NULL DEFAULT '0.0000',
  `CtaInventario` varchar(15) NOT NULL COMMENT 'ac_mastplancuenta->CodCuenta',
  `CtaGasto` varchar(15) NOT NULL COMMENT 'ac_mastplancuenta->CodCuenta',
  `CtaVenta` varchar(15) NOT NULL COMMENT 'ac_mastplancuenta->CodCuenta',
  `CtaInventarioPub20` varchar(13) DEFAULT NULL COMMENT 'ac_mastplancuenta20->CodCuenta',
  `PartidaPresupuestal` varchar(15) NOT NULL COMMENT 'pv_partida->cod_partida',
  `Estado` varchar(1) NOT NULL DEFAULT 'A' COMMENT 'A:ACTIVO; I:INACTIVO',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  `CtaGastoPub20` varchar(13) DEFAULT NULL COMMENT 'ac_mastplancuenta20->CodCuenta',
  PRIMARY KEY (`CodItem`),
  UNIQUE KEY `UK_lg_itemmast_1` (`CodInterno`),
  KEY `FK_lg_itemmast_1` (`CodTipoItem`),
  KEY `FK_lg_itemmast_2` (`CodUnidad`),
  KEY `FK_lg_itemmast_3` (`CodLinea`,`CodFamilia`,`CodSubFamilia`),
  KEY `FK_lg_itemmast_4` (`CodProcedencia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_kardex`
--

CREATE TABLE IF NOT EXISTS `lg_kardex` (
  `CodItem` varchar(10) NOT NULL COMMENT 'lg_itemmast->CodItem',
  `CodAlmacen` varchar(6) NOT NULL COMMENT 'lg_almacenmast->CodAlmacen',
  `CodDocumento` varchar(2) NOT NULL COMMENT 'lg_tipodocumento->CodDocumento',
  `NroDocumento` varchar(10) NOT NULL,
  `Secuencia` int(4) NOT NULL,
  `Fecha` date NOT NULL,
  `CodTransaccion` varchar(3) NOT NULL,
  `ReferenciaCodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `ReferenciaCodDocumento` varchar(2) NOT NULL COMMENT 'lg_tipodocumento->CodDocumento',
  `ReferenciaNroDocumento` varchar(10) NOT NULL,
  `ReferenciaSecuencia` int(10) NOT NULL,
  `Cantidad` decimal(11,4) NOT NULL,
  `PrecioUnitario` decimal(11,4) NOT NULL,
  `MontoTotal` decimal(11,4) NOT NULL,
  `PeriodoContable` varchar(7) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodItem`,`CodAlmacen`,`CodDocumento`,`NroDocumento`,`Secuencia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 6144 kB';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_marcas`
--

CREATE TABLE IF NOT EXISTS `lg_marcas` (
  `CodMarca` varchar(4) NOT NULL,
  `Descripcion` varchar(50) NOT NULL,
  `Estado` varchar(1) NOT NULL COMMENT 'A:ACTIVO; I:INACTIVO',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodMarca`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_operacioncommodity`
--

CREATE TABLE IF NOT EXISTS `lg_operacioncommodity` (
  `CodOperacion` varchar(3) NOT NULL,
  `Descripcion` varchar(50) NOT NULL,
  `TipoMovimiento` varchar(1) NOT NULL COMMENT 'I:INGRESO; E:EGRESO; T:TRANSFERENCIA',
  `TipoDocGenerado` varchar(2) NOT NULL COMMENT 'lg_tipodocumento->CodDocumento(NI:Nota de Ingreso; NE:Nota de Salida; NT:Nota de Transferencia)',
  `TipoDocTransaccion` varchar(2) NOT NULL COMMENT 'lg_tipodocumento->CodDocumento',
  `Estado` varchar(1) NOT NULL COMMENT 'A:ACTIVO; I:INACTIVO',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodOperacion`),
  KEY `TipoDocGenerado` (`TipoDocGenerado`),
  KEY `TipoDocTransaccion` (`TipoDocTransaccion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_ordencompra`
--

CREATE TABLE IF NOT EXISTS `lg_ordencompra` (
  `Anio` varchar(4) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `NroOrden` varchar(10) NOT NULL,
  `NroInterno` varchar(20) NOT NULL,
  `Mes` varchar(2) NOT NULL,
  `Clasificacion` varchar(1) NOT NULL DEFAULT 'L' COMMENT 'L:LOCAL; F:FORANEO',
  `CodDependencia` varchar(4) NOT NULL COMMENT 'mastdependencias->CodDependencia',
  `CodProveedor` varchar(6) NOT NULL COMMENT 'mastproveedores->CodProveedor',
  `NomProveedor` varchar(100) NOT NULL,
  `FaxProveedor` varchar(15) NOT NULL,
  `CodAlmacen` varchar(6) NOT NULL COMMENT 'lg_almacenmast->CodAlmacen',
  `FechaPrometida` date NOT NULL,
  `PreparadaPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `FechaPreparacion` date NOT NULL,
  `RevisadaPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `FechaRevision` date NOT NULL,
  `AprobadaPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `FechaAprobacion` date NOT NULL,
  `CodTipoServicio` varchar(5) NOT NULL COMMENT 'masttiposervicio->CodTipoServicio',
  `MontoBruto` decimal(11,4) NOT NULL DEFAULT '0.0000',
  `MontoIGV` decimal(11,4) NOT NULL DEFAULT '0.0000',
  `MontoOtros` decimal(11,4) NOT NULL DEFAULT '0.0000',
  `MontoTotal` decimal(11,4) NOT NULL DEFAULT '0.0000',
  `MontoPendiente` decimal(11,4) NOT NULL DEFAULT '0.0000',
  `MontoAfecto` decimal(11,4) NOT NULL DEFAULT '0.0000',
  `MontoNoAfecto` decimal(11,4) NOT NULL DEFAULT '0.0000',
  `MontoPagado` decimal(11,4) NOT NULL DEFAULT '0.0000',
  `CodFormaPago` varchar(3) NOT NULL COMMENT 'mastformapago->CodFormaPago',
  `CodResponsable` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `CodAlmacenIngreso` varchar(6) NOT NULL COMMENT 'lg_almacenmast->CodAlmacen',
  `NomContacto` varchar(50) NOT NULL,
  `FaxContacto` varchar(15) NOT NULL,
  `PlazoEntrega` int(10) NOT NULL,
  `DirEntrega` varchar(75) NOT NULL,
  `InsEntrega` varchar(75) NOT NULL,
  `Entregaren` varchar(75) NOT NULL,
  `Observaciones` text NOT NULL,
  `ObsDetallada` text NOT NULL,
  `MotRechazo` text NOT NULL,
  `FlagTransferencia` varchar(1) NOT NULL DEFAULT 'N',
  `TipoClasificacion` varchar(3) DEFAULT NULL COMMENT 'lg_clasificacion->Clasificacion',
  `Estado` varchar(2) NOT NULL DEFAULT 'PR' COMMENT 'PR:En Preparacion; RV:Revisado; AP:Aprobado; AN:Anulado; RE:Rechazado; CE:Cerrado; CO:Completado;',
  `CodCuenta` varchar(13) NOT NULL COMMENT 'ac_mastplancuenta->CodCuenta',
  `CodCuentaPub20` varchar(13) DEFAULT NULL COMMENT 'ac_mastplancuenta20->CodCuenta',
  `cod_partida` varchar(12) NOT NULL COMMENT 'pv_partida->cod_partida',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Anio`,`CodOrganismo`,`NroOrden`),
  KEY `Index_2` (`CodDependencia`),
  KEY `Index_3` (`PreparadaPor`),
  KEY `Index_4` (`RevisadaPor`),
  KEY `Index_5` (`AprobadaPor`),
  KEY `Index_6` (`CodResponsable`),
  KEY `Index_7` (`CodAlmacenIngreso`),
  KEY `Index_8` (`CodCuenta`),
  KEY `Index_9` (`cod_partida`),
  KEY `FK_lg_ordencompra_1` (`CodProveedor`),
  KEY `FK_lg_ordencompra_2` (`CodAlmacen`),
  KEY `FK_lg_ordencompra_3` (`CodTipoServicio`),
  KEY `FK_lg_ordencompra_4` (`CodFormaPago`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_ordencompradetalle`
--

CREATE TABLE IF NOT EXISTS `lg_ordencompradetalle` (
  `Anio` varchar(4) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `NroOrden` varchar(10) NOT NULL,
  `Secuencia` int(4) NOT NULL,
  `Mes` varchar(2) NOT NULL,
  `CodItem` varchar(10) NOT NULL COMMENT 'lg_itemmast->CodItem',
  `CommoditySub` varchar(6) NOT NULL COMMENT 'lg_commoditysub->CommoditySub',
  `Descripcion` varchar(255) NOT NULL,
  `Condicion` varchar(1) NOT NULL,
  `CodUnidad` varchar(3) NOT NULL COMMENT 'mastunidades->CodUnidad',
  `CantidadPedida` decimal(11,4) NOT NULL DEFAULT '0.0000',
  `CantidadRecibida` decimal(11,4) NOT NULL DEFAULT '0.0000',
  `PrecioUnit` decimal(11,4) NOT NULL DEFAULT '0.0000',
  `PrecioUnitOtros` decimal(11,4) NOT NULL DEFAULT '0.0000',
  `PrecioCantidad` decimal(11,4) NOT NULL DEFAULT '0.0000',
  `Total` decimal(11,4) NOT NULL DEFAULT '0.0000',
  `DescuentoPorcentaje` decimal(11,4) NOT NULL DEFAULT '0.0000',
  `DescuentoFijo` decimal(11,4) NOT NULL DEFAULT '0.0000',
  `FlagExonerado` varchar(1) NOT NULL DEFAULT 'N',
  `CodCentroCosto` varchar(4) NOT NULL COMMENT 'ac_mastcentrocosto->CodCentroCosto',
  `Comentarios` longtext NOT NULL,
  `FechaPrometida` date NOT NULL,
  `Cliente` varchar(25) NOT NULL,
  `ClienteNroPedido` varchar(15) NOT NULL,
  `Estado` varchar(2) NOT NULL DEFAULT 'PR' COMMENT 'PR:En Preparacion; PE:Pendiente; AN:Anulado; RE:Rechazado; CE:Cerrado; CO:Completado',
  `CodCuenta` varchar(13) NOT NULL COMMENT 'ac_mastplancuenta->CodCuenta',
  `CodCuentaPub20` varchar(13) NOT NULL COMMENT 'ac_mastplancuenta20->CodCuenta',
  `cod_partida` varchar(12) NOT NULL COMMENT 'pv_partida->cod_partida',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Anio`,`CodOrganismo`,`NroOrden`,`Secuencia`),
  KEY `Index_2` (`CodItem`),
  KEY `Index_3` (`CommoditySub`),
  KEY `FK_lg_ordencompradetalle_2` (`CodUnidad`),
  KEY `FK_lg_ordencompradetalle_3` (`CodCentroCosto`),
  KEY `FK_lg_ordencompradetalle_5` (`cod_partida`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_ordenservicio`
--

CREATE TABLE IF NOT EXISTS `lg_ordenservicio` (
  `Anio` varchar(4) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `NroOrden` varchar(10) NOT NULL,
  `Mes` varchar(2) NOT NULL,
  `CodDependencia` varchar(4) NOT NULL COMMENT 'mastdependencias->CodDependencia',
  `CodProveedor` varchar(6) NOT NULL COMMENT 'mastproveedores->CodProveedor',
  `NomProveedor` varchar(100) NOT NULL,
  `CodFormaPago` varchar(3) NOT NULL COMMENT 'mastformapago->CodFormaPago',
  `NroInterno` varchar(10) NOT NULL,
  `FechaDocumento` date NOT NULL,
  `DiasPago` int(4) NOT NULL,
  `CodTipoPago` varchar(2) NOT NULL COMMENT 'masttipopago->CodTipoPago',
  `CodTipoServicio` varchar(5) NOT NULL COMMENT 'masttiposervicio->CodTipoServicio',
  `PlazoEntrega` int(4) NOT NULL,
  `FechaEntrega` date NOT NULL,
  `MontoOriginal` decimal(11,4) NOT NULL DEFAULT '0.0000',
  `MontoNoAfecto` decimal(11,4) DEFAULT '0.0000',
  `MontoIva` decimal(11,4) NOT NULL DEFAULT '0.0000',
  `TotalMontoIva` decimal(11,4) NOT NULL DEFAULT '0.0000',
  `MontoGastado` decimal(11,4) NOT NULL DEFAULT '0.0000',
  `MontoPendiente` decimal(11,4) DEFAULT '0.0000',
  `Descripcion` text NOT NULL,
  `DescAdicional` text NOT NULL,
  `MotRechazo` text NOT NULL,
  `Observaciones` text NOT NULL,
  `FechaValidoDesde` date NOT NULL,
  `FechaValidoHasta` date NOT NULL,
  `CodCentroCosto` varchar(4) NOT NULL COMMENT 'ac_mastcentrocosto->CodCentroCosto',
  `PreparadaPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `FechaPreparacion` date NOT NULL,
  `RevisadaPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `FechaRevision` date NOT NULL,
  `AprobadaPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `FechaAprobacion` date NOT NULL,
  `FlagConfirmacion` varchar(1) NOT NULL DEFAULT 'N',
  `Estado` varchar(2) NOT NULL DEFAULT 'PR' COMMENT 'PR:En Preparacion; RV:Revisado; AP:Aprobado; AN:Anulado; RE:Rechazado; CE:Cerrado; CO:Completado;',
  `CodCuenta` varchar(13) NOT NULL COMMENT 'ac_mastplancuenta->CodCuenta',
  `CodCuentaPub20` varchar(13) NOT NULL COMMENT 'ac_mastplancuenta20->CodCuenta',
  `cod_partida` varchar(12) NOT NULL COMMENT 'pv_partida->cod_partida',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Anio`,`CodOrganismo`,`NroOrden`),
  KEY `Index_2` (`CodDependencia`),
  KEY `Index_9` (`CodTipoPago`),
  KEY `FK_lg_ordenservicio_1` (`CodProveedor`),
  KEY `FK_lg_ordenservicio_2` (`CodFormaPago`),
  KEY `FK_lg_ordenservicio_3` (`CodTipoServicio`),
  KEY `FK_lg_ordenservicio_4` (`CodCentroCosto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_ordenserviciodetalle`
--

CREATE TABLE IF NOT EXISTS `lg_ordenserviciodetalle` (
  `Anio` varchar(4) NOT NULL COMMENT 'lg_ordenservicio->Anio',
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'lg_ordenservicio->CodOrganismo',
  `NroOrden` varchar(10) NOT NULL COMMENT 'lg_ordenservicio->NroOrden',
  `Secuencia` int(4) NOT NULL,
  `Mes` varchar(2) NOT NULL,
  `CommoditySub` varchar(6) NOT NULL COMMENT 'lg_commoditysub->CommoditySub',
  `Descripcion` varchar(255) NOT NULL,
  `CantidadPedida` decimal(11,4) NOT NULL DEFAULT '0.0000',
  `CantidadRecibida` decimal(11,4) NOT NULL DEFAULT '0.0000',
  `PrecioUnit` decimal(11,4) NOT NULL DEFAULT '0.0000',
  `Total` decimal(11,4) NOT NULL DEFAULT '0.0000',
  `FechaEsperadaTermino` date NOT NULL,
  `FechaTermino` date NOT NULL,
  `CodCentroCosto` varchar(4) NOT NULL COMMENT 'ac_mastcentrocosto->CodCentroCosto',
  `NroActivo` varchar(15) NOT NULL,
  `FlagExonerado` varchar(1) NOT NULL DEFAULT 'N',
  `FlagTerminado` varchar(1) NOT NULL DEFAULT 'N',
  `Comentarios` longtext NOT NULL,
  `cod_partida` varchar(12) NOT NULL COMMENT 'pv_partida->cod_partida',
  `CodCuenta` varchar(13) NOT NULL COMMENT 'ac_mastplancuenta->CodCuenta',
  `CodCuentaPub20` varchar(13) NOT NULL COMMENT 'ac_mastplancuenta20->CodCuenta',
  `ConfirmadoPor` varchar(6) DEFAULT NULL,
  `FechaConfirmacion` date DEFAULT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Anio`,`CodOrganismo`,`NroOrden`,`Secuencia`),
  KEY `FK_lg_ordenserviciodetalle_1` (`CommoditySub`),
  KEY `FK_lg_ordenserviciodetalle_2` (`CodCentroCosto`),
  KEY `FK_lg_ordenserviciodetalle_3` (`cod_partida`),
  KEY `FK_lg_ordenserviciodetalle_4` (`CodCuenta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_periodocontrol`
--

CREATE TABLE IF NOT EXISTS `lg_periodocontrol` (
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `Periodo` varchar(7) NOT NULL,
  `FlagTransaccion` varchar(1) NOT NULL,
  `Estado` varchar(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodOrganismo`,`Periodo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 6144 kB';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_procedencias`
--

CREATE TABLE IF NOT EXISTS `lg_procedencias` (
  `CodProcedencia` varchar(6) NOT NULL,
  `Descripcion` varchar(50) NOT NULL,
  `Estado` varchar(1) NOT NULL COMMENT 'A:ACTIVO; I:INACTIVO',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodProcedencia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_proveedorrecomendado`
--

CREATE TABLE IF NOT EXISTS `lg_proveedorrecomendado` (
  `CodInformeProveedor` bigint(20) NOT NULL,
  `CodInformeRecomendacion` bigint(20) NOT NULL,
  `CodProveedorRecomendado` varchar(6) NOT NULL,
  `SecuenciaRecomendacion` int(11) NOT NULL,
  `UltimoUsuario` varchar(6) NOT NULL,
  `UltimaFechaModif` datetime NOT NULL,
  PRIMARY KEY (`CodInformeProveedor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=48;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_requedetalleacta`
--

CREATE TABLE IF NOT EXISTS `lg_requedetalleacta` (
  `CodRequerimiento` varchar(10) CHARACTER SET latin1 NOT NULL,
  `Secuencia` int(11) NOT NULL,
  `CodActaInicio` bigint(10) NOT NULL,
  `UltimoUsuario` varchar(25) CHARACTER SET latin1 NOT NULL,
  `UltimaFechaModif` datetime NOT NULL,
  PRIMARY KEY (`Secuencia`,`CodRequerimiento`,`CodActaInicio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=44;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_requerimientos`
--

CREATE TABLE IF NOT EXISTS `lg_requerimientos` (
  `CodRequerimiento` varchar(10) NOT NULL,
  `CodInterno` varchar(15) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `CodDependencia` varchar(4) NOT NULL COMMENT 'mastdependencias->CodDependencia',
  `CodCentroCosto` varchar(4) NOT NULL COMMENT 'ac_mastcentrocosto->CodCentroCosto',
  `CodAlmacen` varchar(6) NOT NULL COMMENT 'lg_almacenmast->CodAlmacen',
  `Clasificacion` varchar(3) NOT NULL COMMENT 'lg_clasificacion->Clasificacion',
  `Prioridad` varchar(1) NOT NULL COMMENT 'N:NORMAL; U:URGENTE; M:MUY URGENTE',
  `TipoClasificacion` varchar(1) NOT NULL COMMENT 'A:ALMACEN; C:COMPRA',
  `PreparadaPor` varchar(6) NOT NULL COMMENT 'mastpersonas-CodPersona',
  `RevisadaPor` varchar(6) NOT NULL COMMENT 'mastpersonas-CodPersona',
  `AprobadaPor` varchar(6) NOT NULL COMMENT 'mastpersonas-CodPersona',
  `FechaRequerida` date NOT NULL,
  `FechaPreparacion` date NOT NULL,
  `FechaRevision` date NOT NULL,
  `FechaAprobacion` date NOT NULL,
  `ConformadaPor` varchar(6) NOT NULL,
  `FechaConformacion` date NOT NULL,
  `Comentarios` text NOT NULL,
  `RazonRechazo` text NOT NULL,
  `Anio` year(4) NOT NULL,
  `Secuencia` int(3) DEFAULT NULL,
  `FlagCajaChica` varchar(1) NOT NULL DEFAULT 'N',
  `ProveedorSugerido` varchar(6) DEFAULT NULL,
  `ClasificacionOC` varchar(1) DEFAULT NULL COMMENT 'L:LOCAL; F:FORANEO',
  `ProveedorDocRef` varchar(15) DEFAULT NULL,
  `Estado` varchar(2) NOT NULL DEFAULT 'PR' COMMENT 'PR:En Preparacion; RV:Revisado; CN:Conformado; AP:Aprobado; AN:Anulado; RE:Rechazado; CE:Cerrado; CO:Completado;',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  `FlagCC` varchar(1) NOT NULL DEFAULT 'N',
  `FlagP` varchar(1) NOT NULL DEFAULT 'N',
  `FechaCheckCajaChica` date DEFAULT NULL,
  `CheckCajaChicaPor` varchar(10) DEFAULT NULL,
  `FechaCheckPresupuesto` date DEFAULT NULL,
  `CheckPresupuestoPor` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`CodRequerimiento`),
  UNIQUE KEY `UK_CodInterno` (`CodInterno`),
  KEY `FK_lg_requerimientos_1` (`CodCentroCosto`),
  KEY `FK_lg_requerimientos_2` (`CodAlmacen`),
  KEY `FK_lg_requerimientos_3` (`Clasificacion`),
  KEY `FK_lg_requerimientos_4_idx` (`CodOrganismo`) USING BTREE,
  KEY `FK_lg_requerimientos_5_idx` (`CodDependencia`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_requerimientosdet`
--

CREATE TABLE IF NOT EXISTS `lg_requerimientosdet` (
  `CodRequerimiento` varchar(10) NOT NULL,
  `Secuencia` int(4) unsigned NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `CodItem` varchar(10) NOT NULL COMMENT 'lg_itemmast->CodItem',
  `CommoditySub` varchar(6) NOT NULL COMMENT 'lg_commoditysub->CommoditySub',
  `Descripcion` text NOT NULL,
  `CodUnidad` varchar(3) NOT NULL COMMENT 'mastunidades->CodUnidad',
  `CantidadPedida` decimal(11,4) NOT NULL,
  `FlagExonerado` varchar(1) NOT NULL,
  `FlagCompraAlmacen` varchar(1) NOT NULL COMMENT 'A:ALMACEN; C:COMPRA',
  `CodCentroCosto` varchar(4) NOT NULL COMMENT 'ac_mastcentrocosto->CodCentroCosto',
  `CantidadOrdenCompra` decimal(11,4) NOT NULL,
  `CantidadRecibida` decimal(11,4) NOT NULL,
  `Comentarios` text NOT NULL,
  `CotizacionSecuencia` int(10) NOT NULL,
  `CotizacionCantidad` int(4) NOT NULL,
  `CotizacionPrecioUnitInicio` decimal(11,4) NOT NULL,
  `CotizacionPrecioUnit` decimal(11,4) NOT NULL,
  `CotizacionPrecioUnitIva` decimal(11,4) NOT NULL,
  `CotizacionProveedor` varchar(6) NOT NULL,
  `CotizacionFechaAsignacion` date NOT NULL,
  `CotizacionFormaPago` varchar(3) NOT NULL COMMENT 'mastformapago->CodFormaPago',
  `CotizacionRegistros` int(4) NOT NULL,
  `Anio` year(4) DEFAULT NULL,
  `NroOrden` varchar(10) NOT NULL,
  `OrdenSecuencia` int(4) DEFAULT NULL,
  `DocReferencia` varchar(15) NOT NULL,
  `Estado` varchar(2) NOT NULL DEFAULT 'PR' COMMENT 'PR:En Preparacion; PE:Pendiente; AN:Anulado; RE:Rechazado; CE:Cerrado; CO:Completado',
  `CodCuenta` varchar(13) NOT NULL COMMENT 'ac_mastplancuenta->CodCuenta',
  `CodCuentaPub20` varchar(13) NOT NULL COMMENT 'ac_mastplancuenta20->CodCuenta',
  `cod_partida` varchar(12) NOT NULL COMMENT 'pv_partida->cod_partida',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodRequerimiento`,`Secuencia`),
  KEY `FK_lg_requerimientosdet_1` (`CodRequerimiento`),
  KEY `FK_lg_requerimientosdet_2` (`CodCentroCosto`),
  KEY `FK_lg_requerimientosdet_3_idx` (`CodOrganismo`) USING BTREE,
  KEY `FK_lg_requerimientosdet_4_idx` (`CodUnidad`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_tipodocumento`
--

CREATE TABLE IF NOT EXISTS `lg_tipodocumento` (
  `CodDocumento` varchar(2) NOT NULL,
  `Descripcion` varchar(50) NOT NULL,
  `FlagDocFiscal` varchar(1) NOT NULL,
  `FlagTransaccion` varchar(1) NOT NULL,
  `Estado` varchar(1) NOT NULL COMMENT 'A:ACTIVO; I:INACTIVO',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodDocumento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_tipoitem`
--

CREATE TABLE IF NOT EXISTS `lg_tipoitem` (
  `CodTipoItem` varchar(2) NOT NULL,
  `Descripcion` varchar(50) NOT NULL,
  `FlagTransaccion` varchar(1) NOT NULL,
  `Estado` varchar(1) NOT NULL COMMENT 'A:ACTIVO; I:INACTIVO',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodTipoItem`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_tipotransaccion`
--

CREATE TABLE IF NOT EXISTS `lg_tipotransaccion` (
  `CodTransaccion` varchar(3) NOT NULL,
  `Descripcion` varchar(50) NOT NULL,
  `TipoMovimiento` varchar(1) NOT NULL COMMENT 'I:INGRESO; E:EGRESO; T:TRANSFERENCIA',
  `TipoDocGenerado` varchar(2) NOT NULL COMMENT 'lg_tipodocumento->CodDocumento(NI:Nota de Ingreso; NE:Nota de Salida; NT:Nota de Transferencia)',
  `TipoDocTransaccion` varchar(2) NOT NULL COMMENT 'lg_tipodocumento->CodDocumento',
  `FlagVoucherConsumo` varchar(1) NOT NULL,
  `FlagVoucherAjuste` varchar(1) NOT NULL,
  `FlagTransaccion` varchar(1) NOT NULL,
  `FlagTransaccionVenta` varchar(1) NOT NULL,
  `Estado` varchar(1) NOT NULL COMMENT 'A:ACTIVO; I:INACTIVO',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodTransaccion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_transaccion`
--

CREATE TABLE IF NOT EXISTS `lg_transaccion` (
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `CodDocumento` varchar(2) NOT NULL COMMENT 'lg_tipodocumento->CodDocumento',
  `NroDocumento` varchar(6) NOT NULL,
  `NroInterno` varchar(6) DEFAULT NULL,
  `CodTransaccion` varchar(3) NOT NULL COMMENT 'lg_tipotransaccion->CodTransaccion',
  `FechaDocumento` date NOT NULL,
  `Periodo` varchar(7) NOT NULL,
  `CodAlmacen` varchar(6) NOT NULL COMMENT 'lg_almacenmast->CodAlmacen',
  `CodCentroCosto` varchar(4) NOT NULL COMMENT 'ac_mastcentrocosto->CodCentroCosto',
  `CodDocumentoReferencia` varchar(2) NOT NULL COMMENT 'lg_tipodocumento->CodDocumento',
  `NroDocumentoReferencia` varchar(20) NOT NULL,
  `IngresadoPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `RecibidoPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `Comentarios` longtext NOT NULL,
  `MotRechazo` longtext NOT NULL,
  `FlagManual` varchar(1) NOT NULL,
  `FlagPendiente` varchar(1) NOT NULL,
  `ReferenciaOrganismo` varchar(4) NOT NULL,
  `ReferenciaNroDocumento` varchar(10) NOT NULL,
  `DocumentoReferencia` varchar(20) NOT NULL,
  `DocumentoReferenciaInterno` varchar(20) NOT NULL,
  `NotaEntrega` varchar(10) NOT NULL,
  `CodDependencia` varchar(4) NOT NULL COMMENT 'mastdependencias->CodDependencia',
  `EjecutadoPor` varchar(6) DEFAULT NULL,
  `FechaEjecucion` date DEFAULT NULL,
  `Estado` varchar(2) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodOrganismo`,`CodDocumento`,`NroDocumento`),
  KEY `FK_lg_transaccion_1` (`CodTransaccion`),
  KEY `FK_lg_transaccion_2` (`CodAlmacen`),
  KEY `FK_lg_transaccion_3` (`CodCentroCosto`),
  KEY `FK_lg_transaccion_4` (`CodDocumento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_transacciondetalle`
--

CREATE TABLE IF NOT EXISTS `lg_transacciondetalle` (
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `CodDocumento` varchar(2) NOT NULL COMMENT 'lg_tipodocumento->CodDocumento',
  `NroDocumento` varchar(10) NOT NULL,
  `Secuencia` int(10) NOT NULL,
  `CodItem` varchar(10) NOT NULL COMMENT 'lg_itemmast->CodItem',
  `Descripcion` varchar(255) DEFAULT NULL,
  `CodUnidad` varchar(3) NOT NULL COMMENT 'mastunidades->CodUnidad',
  `CantidadPedida` decimal(11,4) NOT NULL,
  `CantidadRecibida` decimal(11,4) NOT NULL,
  `PrecioUnit` decimal(11,4) NOT NULL,
  `Total` decimal(11,4) NOT NULL,
  `ReferenciaOrganismo` varchar(4) NOT NULL,
  `ReferenciaCodDocumento` varchar(2) NOT NULL,
  `ReferenciaNroDocumento` varchar(10) NOT NULL,
  `ReferenciaSecuencia` int(10) NOT NULL,
  `CodCentroCosto` varchar(4) DEFAULT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodOrganismo`,`CodDocumento`,`NroDocumento`,`Secuencia`),
  KEY `INDEX_1` (`ReferenciaOrganismo`,`ReferenciaNroDocumento`,`ReferenciaSecuencia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_verificarimpuordencom`
--

CREATE TABLE IF NOT EXISTS `lg_verificarimpuordencom` (
  `Anio` varchar(4) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL,
  `NroOrden` varchar(10) NOT NULL,
  `CodPersona` varchar(6) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `UltimoUsuario` varchar(25) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `UltimaFechaModif` datetime NOT NULL,
  PRIMARY KEY (`Anio`,`CodOrganismo`,`NroOrden`,`CodPersona`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=48;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lg_verificarimpuordenser`
--

