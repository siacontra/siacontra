--
-- Estructura de tabla para la tabla `rh_encuesta_detalle`
--

CREATE TABLE IF NOT EXISTS `rh_encuesta_detalle` (
  `Secuencia` int(4) NOT NULL,
  `Pregunta` int(4) NOT NULL,
  PRIMARY KEY (`Secuencia`,`Pregunta`),
  KEY `FK_rh_encuesta_detalle_2` (`Pregunta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_encuesta_plantillas`
--

CREATE TABLE IF NOT EXISTS `rh_encuesta_plantillas` (
  `Plantilla` int(4) NOT NULL AUTO_INCREMENT,
  `Descripcion` varchar(250) NOT NULL DEFAULT '',
  `Estado` char(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`Plantilla`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_encuesta_plantillas_det`
--

CREATE TABLE IF NOT EXISTS `rh_encuesta_plantillas_det` (
  `Plantilla` int(4) NOT NULL,
  `Pregunta` int(4) NOT NULL,
  PRIMARY KEY (`Plantilla`,`Pregunta`),
  KEY `FK_rh_encuesta_plantillas_det_2` (`Pregunta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_encuesta_preguntas`
--

CREATE TABLE IF NOT EXISTS `rh_encuesta_preguntas` (
  `Pregunta` int(4) NOT NULL AUTO_INCREMENT,
  `Descripcion` varchar(255) NOT NULL DEFAULT '',
  `Area` varchar(2) NOT NULL,
  `ValorMinimo` int(4) NOT NULL,
  `ValorMaximo` int(4) NOT NULL,
  `Estado` char(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`Pregunta`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_encuesta_sujeto`
--

CREATE TABLE IF NOT EXISTS `rh_encuesta_sujeto` (
  `Secuencia` int(4) NOT NULL,
  `Pregunta` int(4) NOT NULL,
  `Sujeto` int(4) NOT NULL,
  `Valor` int(4) NOT NULL,
  PRIMARY KEY (`Pregunta`,`Sujeto`,`Secuencia`),
  KEY `FK_rh_encuesta_sujeto_1` (`Secuencia`,`Pregunta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_evaluacion`
--

CREATE TABLE IF NOT EXISTS `rh_evaluacion` (
  `Evaluacion` int(4) unsigned NOT NULL DEFAULT '0',
  `Descripcion` varchar(40) NOT NULL,
  `TipoEvaluacion` varchar(1) NOT NULL COMMENT 'rh_tipoevalucion->TipoEvalaucion',
  `Plantilla` int(3) NOT NULL DEFAULT '0',
  `PuntajeMin` int(4) NOT NULL,
  `PuntajeMax` int(4) NOT NULL,
  `Estado` varchar(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`Evaluacion`),
  KEY `FK_rh_evaluacion_1` (`Plantilla`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_evaluacionarea`
--

CREATE TABLE IF NOT EXISTS `rh_evaluacionarea` (
  `Area` varchar(3) NOT NULL,
  `TipoEvaluacion` varchar(1) NOT NULL,
  `Descripcion` varchar(100) NOT NULL DEFAULT '',
  `Estado` varchar(1) NOT NULL DEFAULT 'A' COMMENT 'A:ACTIVO; I:INACTIVO;',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Area`),
  KEY `FK_rh_evaluacionarea_1` (`TipoEvaluacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_evaluacionempleado`
--

CREATE TABLE IF NOT EXISTS `rh_evaluacionempleado` (
  `CodOrganismo` varchar(4) NOT NULL,
  `Periodo` varchar(7) NOT NULL,
  `Secuencia` int(4) NOT NULL,
  `CodPersona` varchar(6) NOT NULL,
  `ComentarioPersona` text NOT NULL,
  `Evaluador` varchar(6) NOT NULL,
  `ComentarioEvaluador` text NOT NULL,
  `Supervisor` varchar(6) NOT NULL,
  `ComentarioSupervisor` text NOT NULL,
  `FechaEvaluacion` date NOT NULL,
  `TotalDesempenio` int(4) NOT NULL,
  `TotalFuncion` int(4) NOT NULL,
  `TotalMetas` int(4) NOT NULL,
  `Estado` varchar(2) NOT NULL DEFAULT 'EE' COMMENT 'EE:EN EVALUACION; EV:EVALUADO;',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodOrganismo`,`Periodo`,`Secuencia`,`CodPersona`),
  KEY `FK_rh_evaluacionempleado_1` (`CodOrganismo`,`Secuencia`,`Periodo`),
  KEY `FK_rh_evaluacionempleado_2` (`CodPersona`),
  KEY `FK_rh_evaluacionempleado_3` (`Evaluador`),
  KEY `FK_rh_evaluacionempleado_4` (`Supervisor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_evaluacionfactores`
--

CREATE TABLE IF NOT EXISTS `rh_evaluacionfactores` (
  `Competencia` int(3) NOT NULL,
  `Descripcion` varchar(100) NOT NULL DEFAULT '',
  `Explicacion` text NOT NULL,
  `TipoCompetencia` varchar(2) NOT NULL COMMENT 'miscelaneo->TIPOCOMPE',
  `Area` varchar(3) NOT NULL,
  `Nivel` varchar(2) NOT NULL COMMENT 'miscelaneo->NIVELCOMPE',
  `Calificacion` varchar(2) NOT NULL COMMENT 'miscelaneo->CALICOMPE',
  `FlagPlantilla` varchar(1) NOT NULL DEFAULT 'N',
  `ValorRequerido` int(4) NOT NULL DEFAULT '0',
  `ValorMinimo` int(4) NOT NULL DEFAULT '0',
  `Estado` varchar(1) NOT NULL DEFAULT 'A' COMMENT 'A:ACTIVO; I:INACTIVO;',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Competencia`),
  KEY `FK_rh_evaluacionfactores_2` (`Area`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_evaluacionfactoresplantilla`
--

CREATE TABLE IF NOT EXISTS `rh_evaluacionfactoresplantilla` (
  `Plantilla` int(3) NOT NULL,
  `Descripcion` varchar(100) NOT NULL DEFAULT '',
  `TipoEvaluacion` varchar(1) NOT NULL,
  `FlagTipoEvaluacion` varchar(1) NOT NULL DEFAULT 'M' COMMENT 'M:MULTIPLE; U:UNICO;',
  `Estado` varchar(1) NOT NULL DEFAULT 'A' COMMENT 'A:ACTIVO; I:INACTIVO;',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Plantilla`),
  KEY `FK_rh_evaluacionfactoresplantilla_1` (`TipoEvaluacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_evaluacionitems`
--

CREATE TABLE IF NOT EXISTS `rh_evaluacionitems` (
  `Evaluacion` int(4) unsigned NOT NULL COMMENT 'rh_evaluacion->Evaluacion',
  `CodItem` varchar(4) NOT NULL,
  `Descripcion` text NOT NULL,
  `PuntajeMin` int(4) NOT NULL DEFAULT '0',
  `PuntajeMax` int(4) NOT NULL DEFAULT '0',
  `Estado` varchar(1) NOT NULL DEFAULT 'A' COMMENT 'A:ACTIVO; I:INACTIVO;',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Evaluacion`,`CodItem`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_evaluacionperiodo`
--

CREATE TABLE IF NOT EXISTS `rh_evaluacionperiodo` (
  `CodOrganismo` varchar(4) NOT NULL,
  `Secuencia` int(4) NOT NULL,
  `Descripcion` varchar(100) NOT NULL,
  `TipoEvaluacion` int(3) NOT NULL,
  `Periodo` varchar(7) NOT NULL,
  `FechaFin` date NOT NULL,
  `FechaInicio` date NOT NULL,
  `FechaFinal` date NOT NULL,
  `FlagDesempenio` varchar(1) NOT NULL,
  `FlagMetas` varchar(1) NOT NULL,
  `FlagNecesidad` varchar(1) NOT NULL,
  `FlagRevision` varchar(1) NOT NULL,
  `FlagIncidentes` varchar(1) NOT NULL,
  `FlagFortaleza` varchar(1) NOT NULL,
  `FlagFunciones` varchar(1) NOT NULL,
  `Estado` varchar(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodOrganismo`,`Secuencia`,`Periodo`),
  UNIQUE KEY `Index_2` (`CodOrganismo`,`Estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_evaluacionperiodovalor`
--

CREATE TABLE IF NOT EXISTS `rh_evaluacionperiodovalor` (
  `CodOrganismo` varchar(4) NOT NULL,
  `Periodo` varchar(7) NOT NULL,
  `Secuencia` int(4) NOT NULL,
  `Rango` int(4) NOT NULL,
  `Descripcion` varchar(50) NOT NULL,
  `Explicacion` tinytext NOT NULL,
  `Valor` int(4) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodOrganismo`,`Secuencia`,`Periodo`,`Rango`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_factorvalor`
--

CREATE TABLE IF NOT EXISTS `rh_factorvalor` (
  `Secuencia` int(3) NOT NULL,
  `Competencia` int(3) NOT NULL,
  `TipoEvaluacion` varchar(1) NOT NULL COMMENT 'rh_tipoevaluacion->TipoEvaluacion',
  `Grado` int(4) NOT NULL COMMENT 'rh_gradoscompetencia->Grado',
  `Explicacion` text NOT NULL,
  `Valor` int(4) NOT NULL DEFAULT '0',
  `Estado` varchar(1) NOT NULL DEFAULT 'A' COMMENT 'A:ACTIVO; I:INACTIVO;',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  `Explicacion2` text NOT NULL,
  PRIMARY KEY (`Secuencia`,`Competencia`),
  KEY `FK_rh_factorvalor_1` (`Competencia`),
  KEY `I_rh_factorvalor_1` (`TipoEvaluacion`,`Grado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_factorvalorplantilla`
--

CREATE TABLE IF NOT EXISTS `rh_factorvalorplantilla` (
  `Plantilla` int(3) NOT NULL,
  `Competencia` int(3) NOT NULL COMMENT 'rh_evaluacionfactores->Competencia',
  `FlagPotencial` varchar(1) NOT NULL DEFAULT 'N',
  `FlagCompetencia` varchar(1) NOT NULL DEFAULT 'N',
  `FlagConceptual` varchar(1) NOT NULL DEFAULT 'N',
  `FactorParticipacion` int(4) NOT NULL,
  `Peso` int(4) NOT NULL DEFAULT '0',
  `OrdenFactor` int(2) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Plantilla`,`Competencia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_familarutilesbeneficio`
--

CREATE TABLE IF NOT EXISTS `rh_familarutilesbeneficio` (
  `codfamiliarutiles` bigint(20) NOT NULL AUTO_INCREMENT,
  `codbeneficiarioutiles` bigint(20) DEFAULT NULL,
  `codsecuenciafamiliar` bigint(20) DEFAULT NULL COMMENT 'Se relaciona con el familiar (hijo) del empleado',
  `montoutilesfamiliar` double DEFAULT NULL,
  `ultimousuario` varchar(30) DEFAULT NULL,
  `ultimafecha` datetime DEFAULT NULL,
  PRIMARY KEY (`codfamiliarutiles`),
  KEY `fk_reference_2` (`codbeneficiarioutiles`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_feriados`
--

CREATE TABLE IF NOT EXISTS `rh_feriados` (
  `CodFeriado` varchar(4) NOT NULL,
  `AnioFeriado` varchar(4) NOT NULL,
  `DiaFeriado` varchar(5) NOT NULL,
  `Descripcion` varchar(45) NOT NULL,
  `FlagVariable` varchar(1) NOT NULL,
  `Estado` varchar(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodFeriado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_formatocontrato`
--

CREATE TABLE IF NOT EXISTS `rh_formatocontrato` (
  `TipoContrato` char(2) NOT NULL,
  `CodFormato` varchar(4) NOT NULL,
  `Documento` varchar(100) NOT NULL,
  `RutaPlant` char(150) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodFormato`),
  KEY `rh_formatocontrato_ibfk_1` (`TipoContrato`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_gradoinstruccion`
--

CREATE TABLE IF NOT EXISTS `rh_gradoinstruccion` (
  `CodGradoInstruccion` varchar(3) NOT NULL,
  `Descripcion` varchar(255) NOT NULL,
  `Estado` char(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodGradoInstruccion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_gradoscalificacion`
--

CREATE TABLE IF NOT EXISTS `rh_gradoscalificacion` (
  `Grado` int(4) NOT NULL,
  `Descripcion` varchar(100) NOT NULL,
  `Definicion` text NOT NULL,
  `PuntajeMin` int(4) NOT NULL,
  `PuntajeMax` int(4) NOT NULL,
  `Estado` varchar(1) NOT NULL COMMENT 'A:ACTIVO; I:INACTIVO',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Grado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_gradoscompetencia`
--

CREATE TABLE IF NOT EXISTS `rh_gradoscompetencia` (
  `TipoEvaluacion` varchar(1) NOT NULL COMMENT 'rh_tipoevaluacion->TipoEvaluacion',
  `Grado` int(4) NOT NULL,
  `Descripcion` varchar(50) NOT NULL,
  `PuntajeMin` int(4) NOT NULL,
  `PuntajeMax` int(4) NOT NULL,
  `Estado` varchar(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Grado`,`TipoEvaluacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_grupoocupacional`
--

CREATE TABLE IF NOT EXISTS `rh_grupoocupacional` (
  `CodGrupOcup` varchar(5) NOT NULL,
  `GrupoOcup` varchar(100) NOT NULL DEFAULT '',
  `Estado` varchar(1) NOT NULL DEFAULT 'A',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodGrupOcup`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_historial`
--

CREATE TABLE IF NOT EXISTS `rh_historial` (
  `CodPersona` int(6) unsigned zerofill NOT NULL,
  `Secuencia` int(6) NOT NULL,
  `Periodo` varchar(7) NOT NULL,
  `Fingreso` date DEFAULT NULL,
  `Organismo` varchar(255) NOT NULL,
  `Dependencia` varchar(255) NOT NULL,
  `Cargo` varchar(255) NOT NULL DEFAULT '',
  `NivelSalarial` double(11,2) NOT NULL DEFAULT '0.00',
  `CategoriaCargo` varchar(100) NOT NULL DEFAULT '',
  `TipoNomina` varchar(100) NOT NULL,
  `TipoPago` varchar(100) NOT NULL DEFAULT '',
  `Estado` varchar(1) NOT NULL,
  `MotivoCese` varchar(100) NOT NULL DEFAULT '',
  `Fegreso` date DEFAULT NULL,
  `ObsCese` tinytext,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  `TipoTrabajador` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`CodPersona`,`Secuencia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_horariolaboral`
--

CREATE TABLE IF NOT EXISTS `rh_horariolaboral` (
  `CodHorario` varchar(3) NOT NULL,
  `Descripcion` varchar(50) NOT NULL,
  `FlagCorrido` varchar(1) NOT NULL DEFAULT 'N',
  `Estado` varchar(1) NOT NULL DEFAULT 'A' COMMENT 'A:ACTIVO; I:INACTIVO;',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodHorario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=46;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_horariolaboraldet`
--

CREATE TABLE IF NOT EXISTS `rh_horariolaboraldet` (
  `CodHorario` varchar(3) NOT NULL,
  `Secuencia` int(1) NOT NULL,
  `Dia` int(1) NOT NULL,
  `Abreviatura` varchar(10) NOT NULL,
  `FlagLaborable` varchar(1) NOT NULL DEFAULT 'N',
  `Entrada1` time NOT NULL,
  `Salida1` time NOT NULL,
  `Entrada2` time NOT NULL,
  `Salida2` time NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodHorario`,`Secuencia`),
  KEY `FK_rh_horariolaboraldet_1` (`CodHorario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=48;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_institucionhcm`
--

CREATE TABLE IF NOT EXISTS `rh_institucionhcm` (
  `idInstHcm` int(11) NOT NULL AUTO_INCREMENT,
  `descripcioninsthcm` varchar(500) COLLATE utf8_spanish_ci NOT NULL,
  `tiporazon` varchar(100) COLLATE utf8_spanish_ci NOT NULL COMMENT 'publica, privada, sin fines de lucro, internacional, voluntariado, etc',
  `tipocentro` varchar(300) COLLATE utf8_spanish_ci NOT NULL COMMENT 'hospital, clinica, ambulatorio, cdi,',
  `direccion` varchar(600) COLLATE utf8_spanish_ci NOT NULL,
  `telefonos` varchar(300) COLLATE utf8_spanish_ci NOT NULL,
  `convenioCES` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'si posee o no convenio con la contraloria ejemplo caso de la policlinica',
  PRIMARY KEY (`idInstHcm`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AVG_ROW_LENGTH=42 AUTO_INCREMENT=106 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_item_beneficio`
--

CREATE TABLE IF NOT EXISTS `rh_item_beneficio` (
  `codItemBenf` int(11) NOT NULL AUTO_INCREMENT,
  `codBeneficio` int(11) NOT NULL,
  `nroFactura` varchar(10) CHARACTER SET utf8 NOT NULL,
  `descripcionItem` varchar(250) CHARACTER SET utf8 NOT NULL,
  `montoItem` double(14,2) NOT NULL,
  PRIMARY KEY (`codItemBenf`),
  KEY `codBeneficio` (`codBeneficio`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AVG_ROW_LENGTH=36 AUTO_INCREMENT=2198 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_medicoshcm`
--

CREATE TABLE IF NOT EXISTS `rh_medicoshcm` (
  `idMedHcm` int(11) NOT NULL AUTO_INCREMENT,
  `nombremedico` varchar(300) COLLATE utf8_spanish_ci NOT NULL,
  `convenioCES` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'si posee o no convenio con la contraloria ejemplo caso de la policlinica',
  `internoCES` tinyint(1) NOT NULL COMMENT 'si efectua labores de medicina interna dentro de la contraloría',
  `centromedico` int(11) NOT NULL COMMENT 'el centro medico hospital clinica consultorio al que esta asociado',
  `telefono` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`idMedHcm`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AVG_ROW_LENGTH=31 AUTO_INCREMENT=356 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_meritosfaltas`
--

CREATE TABLE IF NOT EXISTS `rh_meritosfaltas` (
  `CodPersona` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `Secuencia` int(6) NOT NULL,
  `Documento` varchar(50) NOT NULL,
  `FechaDoc` date NOT NULL,
  `Observacion` text,
  `Clasificacion` varchar(2) NOT NULL COMMENT 'miscelaneo->MERITO/DEMERITO',
  `Responsable` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `Tipo` varchar(1) NOT NULL COMMENT 'M:MERITO; D:DEMERITO;',
  `FechaIni` date NOT NULL,
  `FechaFin` date NOT NULL,
  `Externo` varchar(100) NOT NULL,
  `FlagExterno` varchar(1) NOT NULL DEFAULT 'N',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodPersona`,`Secuencia`),
  KEY `rh_meritosfaltas_ibfk_1` (`CodPersona`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_motivocese`
--

CREATE TABLE IF NOT EXISTS `rh_motivocese` (
  `CodMotivoCes` varchar(2) NOT NULL,
  `MotivoCese` varchar(30) NOT NULL,
  `FlagFaltaGrave` char(1) NOT NULL,
  `Estado` char(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodMotivoCes`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_nivelclasecargo`
--

CREATE TABLE IF NOT EXISTS `rh_nivelclasecargo` (
  `CodNivelClase` varchar(3) NOT NULL,
  `CodTipoCargo` varchar(4) NOT NULL,
  `NivelClase` varchar(30) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodNivelClase`,`CodTipoCargo`),
  KEY `CodTipoCargo` (`CodTipoCargo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_nivelgradoinstruccion`
--

CREATE TABLE IF NOT EXISTS `rh_nivelgradoinstruccion` (
  `CodGradoInstruccion` varchar(3) NOT NULL,
  `Nivel` varchar(3) NOT NULL,
  `Descripcion` varchar(255) NOT NULL,
  `AbreviaturaM` varchar(10) NOT NULL,
  `AbreviaturaF` varchar(10) DEFAULT NULL,
  `Estado` varchar(1) DEFAULT 'A',
  `UltimoUsuario` varchar(30) NOT NULL COMMENT 'A:ACTIVO: I:INACTIVO;',
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodGradoInstruccion`,`Nivel`),
  KEY `CodGradoInstruccion` (`CodGradoInstruccion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_nivelsalarial`
--

CREATE TABLE IF NOT EXISTS `rh_nivelsalarial` (
  `CodNivel` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `CategoriaCargo` varchar(2) NOT NULL,
  `Grado` varchar(3) NOT NULL,
  `Descripcion` varchar(45) NOT NULL,
  `SueldoMinimo` decimal(11,2) NOT NULL DEFAULT '0.00',
  `SueldoMaximo` decimal(11,2) NOT NULL DEFAULT '0.00',
  `SueldoPromedio` decimal(11,2) NOT NULL DEFAULT '0.00',
  `Estado` varchar(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodNivel`),
  UNIQUE KEY `UK_rh_nivelsalarial_1` (`CategoriaCargo`,`Grado`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=406 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_nivelsalarialajustes`
--

CREATE TABLE IF NOT EXISTS `rh_nivelsalarialajustes` (
  `CodNivel` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `Secuencia` int(6) NOT NULL,
  `Descripcion` varchar(45) NOT NULL,
  `SueldoPromedio` decimal(11,2) NOT NULL DEFAULT '0.00',
  `Estado` varchar(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodNivel`,`Secuencia`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_patrimonio_cuenta`
--

CREATE TABLE IF NOT EXISTS `rh_patrimonio_cuenta` (
  `CodPersona` varchar(6) NOT NULL,
  `Secuencia` int(6) NOT NULL,
  `TipoCuenta` varchar(2) NOT NULL COMMENT 'miscelaneo->TIPOCTA',
  `Institucion` varchar(100) NOT NULL,
  `NroCuenta` varchar(30) NOT NULL,
  `Valor` decimal(11,2) NOT NULL DEFAULT '0.00',
  `FlagGarantia` varchar(1) NOT NULL DEFAULT 'N',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodPersona`,`Secuencia`),
  KEY `rh_patrimonio_cuenta_ibfk_1` (`CodPersona`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_patrimonio_inmueble`
--

CREATE TABLE IF NOT EXISTS `rh_patrimonio_inmueble` (
  `CodPersona` varchar(6) NOT NULL,
  `Secuencia` int(6) unsigned NOT NULL DEFAULT '0',
  `Descripcion` varchar(255) NOT NULL,
  `Ubicacion` varchar(255) NOT NULL,
  `Uso` varchar(255) NOT NULL,
  `Valor` decimal(11,2) NOT NULL DEFAULT '0.00',
  `FlagHipotecado` varchar(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodPersona`,`Secuencia`),
  KEY `rh_patrimonio_inmueble_ibfk_1` (`CodPersona`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_patrimonio_inversion`
--

CREATE TABLE IF NOT EXISTS `rh_patrimonio_inversion` (
  `CodPersona` varchar(6) NOT NULL,
  `Secuencia` int(6) NOT NULL,
  `Titular` varchar(255) NOT NULL,
  `EmpresaRemitente` varchar(255) NOT NULL,
  `NroCertificado` varchar(20) NOT NULL,
  `Cantidad` int(3) NOT NULL DEFAULT '0',
  `ValorNominal` decimal(11,2) NOT NULL DEFAULT '0.00',
  `Valor` decimal(11,2) NOT NULL DEFAULT '0.00',
  `FlagGarantia` varchar(1) NOT NULL DEFAULT 'N',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodPersona`,`Secuencia`),
  KEY `rh_patrimonio_inversion_ibfk_1` (`CodPersona`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_patrimonio_otro`
--

CREATE TABLE IF NOT EXISTS `rh_patrimonio_otro` (
  `CodPersona` varchar(6) NOT NULL,
  `Secuencia` varchar(6) NOT NULL,
  `Descripcion` varchar(100) NOT NULL,
  `ValorCompra` double(11,2) NOT NULL DEFAULT '0.00',
  `Valor` double(11,2) NOT NULL DEFAULT '0.00',
  `FlagGarantia` varchar(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodPersona`,`Secuencia`),
  KEY `rh_patrimonio_otro_ibfk_1` (`CodPersona`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_patrimonio_vehiculo`
--

CREATE TABLE IF NOT EXISTS `rh_patrimonio_vehiculo` (
  `CodPersona` varchar(6) NOT NULL,
  `Secuencia` int(6) NOT NULL,
  `Marca` varchar(50) NOT NULL,
  `Modelo` varchar(50) NOT NULL,
  `Color` varchar(15) NOT NULL,
  `Placa` varchar(10) NOT NULL,
  `Anio` year(4) NOT NULL,
  `Valor` decimal(11,2) NOT NULL DEFAULT '0.00',
  `FlagPrendado` varchar(1) NOT NULL DEFAULT 'N',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodPersona`,`Secuencia`),
  KEY `rh_patrimonio_vehiculo_ibfk_1` (`CodPersona`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_permisos`
--

CREATE TABLE IF NOT EXISTS `rh_permisos` (
  `CodPermiso` varchar(10) NOT NULL,
  `CodPersona` varchar(6) NOT NULL,
  `Secuencia` varchar(6) NOT NULL,
  `FechaIngreso` date NOT NULL,
  `TipoFalta` varchar(2) NOT NULL,
  `TipoPermiso` varchar(2) NOT NULL,
  `FechaDesde` date NOT NULL,
  `FechaHasta` date NOT NULL,
  `HoraDesde` time NOT NULL DEFAULT '00:00:00',
  `HoraHasta` time NOT NULL DEFAULT '00:00:00',
  `PeriodoContable` varchar(7) NOT NULL,
  `ObsMotivo` longtext NOT NULL,
  `FlagRemunerado` varchar(1) NOT NULL,
  `FlagJustificativo` varchar(1) NOT NULL,
  `FlagExento` varchar(1) NOT NULL,
  `Aprobador` varchar(6) NOT NULL,
  `FechaAprobado` date NOT NULL,
  `ObsAprobado` longtext NOT NULL,
  `Estado` varchar(1) NOT NULL,
  `TotalDias` int(11) NOT NULL,
  `TotalHoras` int(11) NOT NULL,
  `TotalMinutos` int(11) NOT NULL,
  `TotalFecha` varchar(5) NOT NULL,
  `TotalTiempo` varchar(5) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`CodPermiso`),
  KEY `rh_permisos_ibfk_1` (`CodPersona`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_planseguro`
--

CREATE TABLE IF NOT EXISTS `rh_planseguro` (
  `CodPlanSeguro` varchar(3) NOT NULL,
  `CodTipSeguro` varchar(3) NOT NULL,
  `Descripcion` varchar(30) NOT NULL,
  `Estado` char(1) NOT NULL,
  `MontoSeguro` double NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodPlanSeguro`),
  KEY `CodTipSeguro` (`CodTipSeguro`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_postulantes`
--

CREATE TABLE IF NOT EXISTS `rh_postulantes` (
  `Postulante` varchar(6) NOT NULL,
  `Apellido1` varchar(25) DEFAULT NULL,
  `Apellido2` varchar(25) NOT NULL,
  `Nombres` varchar(50) NOT NULL,
  `ResumenEjec` longtext,
  `CiudadNacimiento` varchar(4) NOT NULL,
  `CiudadDomicilio` varchar(4) NOT NULL,
  `Fnacimiento` date NOT NULL,
  `Sexo` char(1) NOT NULL DEFAULT 'M',
  `Direccion` text NOT NULL,
  `Referencia` text NOT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `Telefono1` varchar(15) NOT NULL DEFAULT '',
  `FechaRegistro` datetime NOT NULL,
  `Expediente` varchar(10) NOT NULL,
  `TipoDocumento` varchar(2) NOT NULL,
  `Ndocumento` varchar(20) NOT NULL,
  `Estado` varchar(1) NOT NULL DEFAULT 'P' COMMENT 'P:POSTULANTE; A:APROBADO; C:CONTRATADO;',
  `EstadoCivil` varchar(2) NOT NULL COMMENT 'miscelaneo',
  `FedoCivil` date DEFAULT NULL,
  `GrupoSanguineo` varchar(2) DEFAULT NULL COMMENT 'miscelaneo',
  `SituacionDomicilio` varchar(2) NOT NULL COMMENT 'miscelaneo',
  `InformacionAdic` text,
  `FlagBeneficas` varchar(1) NOT NULL DEFAULT 'N',
  `Beneficas` text,
  `FlagLaborales` varchar(1) NOT NULL DEFAULT 'N',
  `Laborales` text,
  `FlagCulturales` varchar(1) NOT NULL,
  `Culturales` text,
  `FlagDeportivas` varchar(1) NOT NULL DEFAULT 'N',
  `Deportivas` text,
  `FlagReligiosas` varchar(1) NOT NULL DEFAULT 'N',
  `Religiosas` text,
  `FlagSociales` varchar(1) NOT NULL DEFAULT 'N',
  `Sociales` text,
  `Anio` year(4) DEFAULT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Postulante`),
  UNIQUE KEY `uk_rh_postulantes_1` (`TipoDocumento`,`Ndocumento`),
  KEY `rh_postulantes_ibfk_1` (`CiudadNacimiento`),
  KEY `rh_postulantes_ibfk_2` (`CiudadDomicilio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_postulantes_cargos`
--

CREATE TABLE IF NOT EXISTS `rh_postulantes_cargos` (
  `Postulante` varchar(6) NOT NULL,
  `Secuencia` int(3) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `CodCargo` int(5) NOT NULL COMMENT 'rh_puestos->CodCargo',
  `Comentario` text,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Postulante`,`Secuencia`),
  UNIQUE KEY `rh_postulantes_cargos_ibuk_1` (`Postulante`,`CodOrganismo`,`CodCargo`),
  KEY `Postulante` (`Postulante`),
  KEY `CodOrganismo` (`CodOrganismo`),
  KEY `CodCargo` (`CodCargo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_postulantes_cursos`
--

CREATE TABLE IF NOT EXISTS `rh_postulantes_cursos` (
  `Postulante` varchar(6) NOT NULL,
  `Secuencia` int(3) NOT NULL,
  `CodCurso` varchar(4) NOT NULL,
  `TipoCurso` varchar(2) NOT NULL,
  `CodCentroEstudio` varchar(3) NOT NULL,
  `FechaDesde` date NOT NULL,
  `FechaHasta` date NOT NULL,
  `TotalHoras` int(4) NOT NULL,
  `AniosVigencia` int(2) NOT NULL,
  `Observaciones` varchar(255) NOT NULL,
  `FlagInstitucional` char(1) NOT NULL DEFAULT 'N',
  `PeriodoCulminacion` varchar(7) DEFAULT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Postulante`,`Secuencia`),
  KEY `FK_rh_postulantes_cursos_1` (`CodCurso`),
  KEY `FK_rrh_postulantes_cursos_2` (`CodCentroEstudio`),
  KEY `FK_rrh_postulantes_cursos_3` (`Postulante`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_postulantes_documentos`
--

CREATE TABLE IF NOT EXISTS `rh_postulantes_documentos` (
  `Secuencia` int(3) NOT NULL,
  `Postulante` varchar(6) NOT NULL,
  `Documento` varchar(2) NOT NULL,
  `FlagPresento` varchar(1) NOT NULL DEFAULT 'N',
  `Observaciones` text NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Postulante`,`Secuencia`),
  KEY `rh_postulantes_documentos_ibfk_1` (`Postulante`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_postulantes_experiencia`
--

CREATE TABLE IF NOT EXISTS `rh_postulantes_experiencia` (
  `Postulante` varchar(6) NOT NULL,
  `Secuencia` int(3) NOT NULL,
  `Empresa` varchar(255) NOT NULL,
  `FechaDesde` date NOT NULL DEFAULT '0000-00-00',
  `FechaHasta` date NOT NULL DEFAULT '0000-00-00',
  `MotivoCese` varchar(2) NOT NULL COMMENT 'miscelaneo->MOTCESE',
  `Sueldo` decimal(11,2) NOT NULL DEFAULT '0.00',
  `AreaExperiencia` varchar(2) NOT NULL COMMENT 'miscelaneo->AREAEXP',
  `TipoEnte` varchar(2) NOT NULL COMMENT 'miscelaneo->TIPOENTE',
  `CargoOcupado` varchar(255) NOT NULL,
  `Funciones` longtext NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Postulante`,`Secuencia`),
  KEY `rh_postulante_experiencia_ibfk_1` (`Postulante`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_postulantes_idioma`
--

CREATE TABLE IF NOT EXISTS `rh_postulantes_idioma` (
  `Postulante` varchar(6) NOT NULL DEFAULT '',
  `CodIdioma` varchar(3) NOT NULL,
  `NivelLectura` varchar(2) NOT NULL,
  `NivelOral` varchar(2) NOT NULL,
  `NivelEscritura` varchar(2) NOT NULL,
  `NivelGeneral` varchar(2) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Postulante`,`CodIdioma`),
  KEY `Postulante` (`Postulante`),
  KEY `CodIdioma` (`CodIdioma`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_postulantes_informat`
--

CREATE TABLE IF NOT EXISTS `rh_postulantes_informat` (
  `Postulante` varchar(6) NOT NULL,
  `Informatica` varchar(2) NOT NULL COMMENT 'miscelaneo',
  `Nivel` varchar(2) NOT NULL COMMENT 'miscelaneo',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Postulante`,`Informatica`),
  KEY `Postulante` (`Postulante`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_postulantes_instruccion`
--

CREATE TABLE IF NOT EXISTS `rh_postulantes_instruccion` (
  `Postulante` varchar(6) NOT NULL,
  `Secuencia` int(3) NOT NULL,
  `CodGradoInstruccion` char(3) NOT NULL,
  `Area` varchar(2) NOT NULL,
  `CodProfesion` varchar(6) NOT NULL,
  `Nivel` varchar(3) NOT NULL,
  `CodCentroEstudio` varchar(3) NOT NULL,
  `FechaDesde` date NOT NULL,
  `FechaHasta` date NOT NULL,
  `Colegiatura` varchar(2) NOT NULL,
  `NroColegiatura` varchar(9) NOT NULL,
  `Observaciones` longtext NOT NULL,
  `FechaGraduacion` date NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Postulante`,`Secuencia`),
  KEY `rh_postulantes_instruccion_ibfk_1` (`Postulante`),
  KEY `rh_postulantes_instruccion_ibfk_2` (`CodCentroEstudio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_postulantes_referencias`
--

CREATE TABLE IF NOT EXISTS `rh_postulantes_referencias` (
  `Postulante` varchar(6) NOT NULL,
  `Secuencia` int(3) NOT NULL,
  `Nombre` varchar(100) NOT NULL,
  `Empresa` varchar(255) NOT NULL,
  `Direccion` text NOT NULL,
  `Telefono` varchar(15) NOT NULL,
  `Cargo` varchar(255) NOT NULL,
  `Tipo` varchar(1) NOT NULL DEFAULT 'P' COMMENT 'P:PERSONAL; L:LABORAL;',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Postulante`,`Secuencia`),
  KEY `rh_postulantes_referencias_ibfk_1` (`Postulante`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_proceso_jubilacion`
--

CREATE TABLE IF NOT EXISTS `rh_proceso_jubilacion` (
  `CodProceso` varchar(4) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `CodPersona` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `AniosServicio` int(3) NOT NULL,
  `Edad` int(3) NOT NULL,
  `Estado` varchar(2) NOT NULL DEFAULT 'PR' COMMENT 'PR:EN PREPARACION; CN:CONFORMADO; AP:APROBADO; AN:ANULADO;',
  `FechaProcesado` datetime NOT NULL,
  `FechaAprobado` datetime NOT NULL,
  `ProcesadoPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `AprobadoPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `ObsProcesado` text NOT NULL,
  `ObsAprobado` text NOT NULL,
  `FechaJubilacion` date NOT NULL,
  `MontoJubilacion` decimal(11,2) NOT NULL,
  `Coeficiente` decimal(11,2) NOT NULL,
  `CodDependencia` varchar(4) NOT NULL COMMENT 'mastdependencias->CodDependencia',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  `NomDependencia` varchar(100) NOT NULL,
  `CodCargo` int(5) NOT NULL COMMENT 'rh_puestos->CodCargo',
  `DescripCargo` varchar(100) NOT NULL,
  `ConformadoPor` varchar(4) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `FechaConformado` datetime NOT NULL,
  `ObsConformado` text NOT NULL,
  `TotalSueldo` decimal(11,2) NOT NULL,
  `TotalPrimas` decimal(11,2) NOT NULL,
  `AniosServicioExceso` int(3) NOT NULL DEFAULT '0',
  `Periodo` varchar(7) NOT NULL,
  `SueldoActual` decimal(11,2) NOT NULL,
  `Fingreso` date NOT NULL,
  `CodTipoNom` varchar(2) DEFAULT NULL,
  `CodTipoTrabajador` varchar(2) DEFAULT NULL,
  `SitTra` varchar(1) DEFAULT NULL,
  `CodMotivoCes` varchar(2) DEFAULT NULL,
  `Fegreso` date DEFAULT NULL,
  `ObsCese` text,
  PRIMARY KEY (`CodProceso`),
  KEY `FK_rh_proceso_jubilacion_1_idx` (`CodOrganismo`),
  KEY `FK_rh_proceso_jubilacion_2_idx` (`CodPersona`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_proceso_pension`
--

CREATE TABLE IF NOT EXISTS `rh_proceso_pension` (
  `CodProceso` varchar(4) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `CodPersona` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `AniosServicio` int(3) NOT NULL,
  `Edad` int(3) NOT NULL,
  `ProcesadoPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `FechaProcesado` datetime NOT NULL,
  `ObsProcesado` text NOT NULL,
  `AprobadoPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `FechaAprobado` datetime NOT NULL,
  `ObsAprobado` text NOT NULL,
  `ConformadoPor` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `FechaConformado` datetime NOT NULL,
  `ObsConformado` text NOT NULL,
  `CodDependencia` varchar(4) NOT NULL COMMENT 'mastdependencias->CodDependencia',
  `NomDependencia` varchar(100) NOT NULL,
  `CodCargo` int(5) NOT NULL COMMENT 'rh_puestos->CodCargo',
  `DescripCargo` varchar(100) NOT NULL,
  `UltimoSueldo` decimal(11,2) NOT NULL,
  `Fingreso` date NOT NULL,
  `MontoPension` decimal(11,2) NOT NULL,
  `TipoPension` varchar(1) NOT NULL DEFAULT 'I' COMMENT 'I:INVALIDEZ; S:SOBREVIVIENTE;',
  `MotivoPension` varchar(1) NOT NULL DEFAULT 'I' COMMENT 'I:INVALIDEZ; N:INCAPACIDAD; F:FALLECIMIENTO;',
  `MontoJubilacion` decimal(11,2) NOT NULL,
  `Coeficiente` decimal(11,2) NOT NULL,
  `TotalSueldo` decimal(11,2) NOT NULL,
  `TotalPrimas` decimal(11,2) NOT NULL,
  `AniosServicioExceso` int(3) NOT NULL DEFAULT '0',
  `Periodo` varchar(7) NOT NULL,
  `Estado` varchar(2) NOT NULL DEFAULT 'PR' COMMENT 'PR:EN PREPARACION; CN:CONFORMADO; AP:APROBADO; AN:ANULADO;',
  `CodTipoNom` varchar(2) DEFAULT NULL,
  `CodTipoTrabajador` varchar(2) DEFAULT NULL,
  `SitTra` varchar(2) DEFAULT NULL,
  `CodMotivoCes` varchar(2) DEFAULT NULL,
  `Fegreso` date DEFAULT NULL,
  `ObsCese` text,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodProceso`),
  KEY `FK_rh_proceso_pension_1` (`CodPersona`),
  KEY `FK_rh_proceso_pension_2` (`CodDependencia`,`CodOrganismo`),
  KEY `FK_rh_proceso_pension_3` (`CodCargo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=84;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_profesiones`
--

CREATE TABLE IF NOT EXISTS `rh_profesiones` (
  `CodProfesion` varchar(6) NOT NULL,
  `Area` varchar(2) NOT NULL DEFAULT '',
  `CodGradoInstruccion` varchar(3) NOT NULL,
  `Descripcion` varchar(200) NOT NULL,
  `Estado` char(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodProfesion`),
  KEY `CodGradoInstruccion` (`CodGradoInstruccion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_puestos`
--

CREATE TABLE IF NOT EXISTS `rh_puestos` (
  `CodCargo` int(5) NOT NULL,
  `CodGrupOcup` varchar(5) NOT NULL,
  `CodSerieOcup` varchar(5) NOT NULL,
  `CodTipoCargo` varchar(5) NOT NULL,
  `CodNivelClase` varchar(3) NOT NULL,
  `NivelSalarial` decimal(11,2) NOT NULL DEFAULT '0.00',
  `DescripCargo` varchar(100) NOT NULL DEFAULT '',
  `CategoriaCargo` varchar(2) NOT NULL DEFAULT '',
  `Grado` varchar(3) NOT NULL,
  `Estado` char(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  `Plantilla` int(3) NOT NULL DEFAULT '0',
  `DescGenerica` mediumtext NOT NULL,
  `CodDesc` varchar(6) NOT NULL,
  PRIMARY KEY (`CodCargo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_ramaservicio`
--

CREATE TABLE IF NOT EXISTS `rh_ramaservicio` (
  `codRamaS` int(11) NOT NULL AUTO_INCREMENT,
  `descripcionRamaS` varchar(500) COLLATE utf8_spanish_ci NOT NULL,
  `codAyudaE` int(11) NOT NULL,
  PRIMARY KEY (`codRamaS`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AVG_ROW_LENGTH=28 AUTO_INCREMENT=63 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_relacionsueldojubilacion`
--

CREATE TABLE IF NOT EXISTS `rh_relacionsueldojubilacion` (
  `CodProceso` varchar(4) NOT NULL COMMENT 'rh_proceso_jubilacion->CodProceso',
  `Secuencia` int(3) unsigned NOT NULL,
  `CodPersona` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `TipoProceso` varchar(1) NOT NULL DEFAULT 'J' COMMENT 'J:JUBILACION; P:PENSION;',
  `Periodo` varchar(7) NOT NULL,
  `CodConcepto` varchar(4) NOT NULL COMMENT 'pr_concepto->CodConcepto',
  `Monto` decimal(11,2) NOT NULL DEFAULT '0.00',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodProceso`,`Secuencia`),
  KEY `FK_rh_relacionsueldojubilacion_2` (`CodPersona`),
  KEY `FK_rh_relacionsueldojubilacion_3` (`CodConcepto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_requerimiento`
--

CREATE TABLE IF NOT EXISTS `rh_requerimiento` (
  `Requerimiento` varchar(6) NOT NULL,
  `FechaSolicitud` date NOT NULL,
  `NumeroSolicitado` int(3) NOT NULL,
  `NumeroPendiente` int(3) NOT NULL,
  `CodDivision` varchar(4) NOT NULL,
  `CodDependencia` varchar(4) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL,
  `CodPersona` varchar(6) NOT NULL,
  `Modalidad` char(1) NOT NULL,
  `VigenciaInicio` date NOT NULL,
  `VigenciaFin` date NOT NULL,
  `CodCargo` int(5) NOT NULL,
  `Motivo` varchar(2) NOT NULL,
  `TipoContrato` char(2) NOT NULL,
  `FechaDesde` date NOT NULL,
  `FechaHasta` date NOT NULL,
  `FechaTermino` date NOT NULL DEFAULT '0000-00-00',
  `Estado` varchar(2) NOT NULL DEFAULT 'PE' COMMENT 'PE:PENDIENTE; AP:APROBADO; EE:EN EVALUACION; TE:TERMINADO;',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Requerimiento`,`CodOrganismo`),
  KEY `FK_rh_requerimiento_1` (`CodPersona`),
  KEY `FK_rh_requerimiento_2` (`TipoContrato`),
  KEY `FK_rh_requerimiento_3` (`CodOrganismo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_requerimientocomp`
--

CREATE TABLE IF NOT EXISTS `rh_requerimientocomp` (
  `Requerimiento` varchar(6) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL,
  `TipoPostulante` varchar(1) NOT NULL,
  `Postulante` varchar(7) NOT NULL,
  `Evaluacion` int(4) unsigned NOT NULL DEFAULT '0',
  `Competencia` int(3) NOT NULL,
  `Puntaje` int(1) unsigned NOT NULL DEFAULT '0',
  `UltimoUsuario` varchar(30) NOT NULL DEFAULT '',
  `UltimaFecha` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`Requerimiento`,`CodOrganismo`,`Postulante`,`Evaluacion`,`Competencia`,`TipoPostulante`),
  KEY `FK_rh_requerimientocomp_2` (`Evaluacion`),
  KEY `FK_rh_requerimientocomp_3` (`Competencia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 4096 kB';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_requerimientoeval`
--

CREATE TABLE IF NOT EXISTS `rh_requerimientoeval` (
  `Requerimiento` varchar(6) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL,
  `Secuencia` int(4) NOT NULL,
  `Evaluacion` int(4) unsigned NOT NULL DEFAULT '0',
  `Etapa` varchar(3) NOT NULL DEFAULT '',
  `PlantillaEvaluacion` int(3) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`Requerimiento`,`CodOrganismo`,`Secuencia`),
  KEY `FK_rh_requerimientoeval_2` (`Evaluacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_requerimientoevalpost`
--

CREATE TABLE IF NOT EXISTS `rh_requerimientoevalpost` (
  `Requerimiento` varchar(6) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL,
  `TipoPostulante` varchar(1) NOT NULL,
  `Postulante` varchar(7) NOT NULL,
  `Secuencia` int(6) NOT NULL,
  `Calificativo` decimal(11,2) NOT NULL DEFAULT '0.00',
  `Descripcion` varchar(100) NOT NULL,
  `FlagAprobacion` varchar(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL DEFAULT '',
  `UltimaFecha` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`Requerimiento`,`CodOrganismo`,`Postulante`,`Secuencia`,`TipoPostulante`),
  KEY `FK_rh_requerimientoevalpost_2` (`Requerimiento`,`CodOrganismo`,`Secuencia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 8192 kB; (`Requerimiento` `CodOrganismo`) REFER';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_requerimientopost`
--

CREATE TABLE IF NOT EXISTS `rh_requerimientopost` (
  `Requerimiento` varchar(6) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL,
  `Postulante` varchar(7) NOT NULL,
  `Estado` varchar(1) NOT NULL DEFAULT 'P' COMMENT 'P:POSTULANTE; A:APROBADO; D:DESCALIFICADO;',
  `TipoPostulante` varchar(1) NOT NULL,
  `Puntaje` decimal(11,2) NOT NULL DEFAULT '0.00',
  `Observaciones` tinytext NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL DEFAULT '',
  `UltimaFecha` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`Requerimiento`,`CodOrganismo`,`Postulante`,`TipoPostulante`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_retencionjudicial`
--

CREATE TABLE IF NOT EXISTS `rh_retencionjudicial` (
  `CodRetencion` varchar(6) NOT NULL,
  `CodPersona` varchar(6) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL,
  `Expediente` varchar(30) NOT NULL,
  `FechaResolucion` date NOT NULL,
  `TipoRetencion` varchar(2) NOT NULL,
  `Juzgado` varchar(255) NOT NULL,
  `Demandante` varchar(100) NOT NULL,
  `CodTipoPago` varchar(2) NOT NULL,
  `Observaciones` longtext NOT NULL,
  `Estado` varchar(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodRetencion`,`CodOrganismo`),
  KEY `Index_2` (`CodOrganismo`),
  KEY `FK_rh_retencionjudicial_1` (`CodTipoPago`),
  KEY `FK_rh_retencionjudicial_2` (`CodOrganismo`),
  KEY `FK_rh_retencionjudicial_3` (`CodPersona`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 6144 kB; (`CodTipoPago`) REFER `siaceda/masttip';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_retencionjudicialconceptos`
--

CREATE TABLE IF NOT EXISTS `rh_retencionjudicialconceptos` (
  `CodRetencion` varchar(6) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL,
  `TipoDescuento` varchar(1) NOT NULL COMMENT 'P:PORCENTAJE; M:MONTO;',
  `Descuento` decimal(11,2) NOT NULL,
  `CodConcepto` varchar(4) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodRetencion`,`CodOrganismo`,`CodConcepto`),
  KEY `FK_rh_retencionjudicialconceptos_1` (`CodRetencion`,`CodOrganismo`),
  KEY `FK_rh_retencionjudicialconceptos_2` (`CodConcepto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 6144 kB';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_serieocupacional`
--

CREATE TABLE IF NOT EXISTS `rh_serieocupacional` (
  `CodSerieOcup` varchar(5) NOT NULL,
  `CodGrupOcup` varchar(5) NOT NULL,
  `SerieOcup` varchar(100) NOT NULL DEFAULT '',
  `Estado` varchar(1) NOT NULL DEFAULT 'A',
  `UltimoUsuario` varchar(30) NOT NULL,
  `Ultimafecha` date NOT NULL,
  PRIMARY KEY (`CodSerieOcup`),
  KEY `CodGrupOcup` (`CodGrupOcup`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_sindicatos`
--

CREATE TABLE IF NOT EXISTS `rh_sindicatos` (
  `CodSindicato` varchar(3) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL,
  `Nombre` varchar(30) NOT NULL,
  `Descripcion` varchar(255) NOT NULL,
  `Estado` char(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodSindicato`),
  KEY `rh_sindicatos_ibfk_1` (`CodOrganismo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_solicitudHcm`
--

CREATE TABLE IF NOT EXISTS `rh_solicitudHcm` (
  `CodSolicitud` int(11) NOT NULL AUTO_INCREMENT,
  `CodPersona` varchar(255) DEFAULT NULL,
  `fechaSolicitud` date DEFAULT NULL,
  PRIMARY KEY (`CodSolicitud`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=234 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_sueldos`
--

CREATE TABLE IF NOT EXISTS `rh_sueldos` (
  `Secuencia` int(11) NOT NULL,
  `CodPersona` varchar(6) NOT NULL,
  `Periodo` varchar(7) NOT NULL,
  `Sueldo` decimal(11,2) NOT NULL,
  `SueldoNormal` decimal(11,2) NOT NULL,
  `SueldoIntegral` decimal(11,2) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Secuencia`,`CodPersona`),
  KEY `FK_rh_sueldos_1` (`CodPersona`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_tipocargo`
--

CREATE TABLE IF NOT EXISTS `rh_tipocargo` (
  `CodTipoCargo` varchar(4) NOT NULL,
  `TipCargo` varchar(65) NOT NULL,
  `Definicion` longtext NOT NULL,
  `Funcion` longtext NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodTipoCargo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_tipocargoriesgos`
--

CREATE TABLE IF NOT EXISTS `rh_tipocargoriesgos` (
  `Secuencia` int(2) unsigned NOT NULL DEFAULT '0',
  `CodTipoCargo` varchar(4) NOT NULL DEFAULT '',
  `Riesgo` mediumtext NOT NULL,
  `Causa` mediumtext NOT NULL,
  `Consecuencia` mediumtext NOT NULL,
  `Prevencion` mediumtext NOT NULL,
  PRIMARY KEY (`Secuencia`),
  KEY `CodTipoCargo` (`CodTipoCargo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_tipocontrato`
--

CREATE TABLE IF NOT EXISTS `rh_tipocontrato` (
  `TipoContrato` char(2) NOT NULL,
  `Descripcion` varchar(40) NOT NULL,
  `FlagNomina` char(1) NOT NULL,
  `FlagVencimiento` char(1) NOT NULL,
  `Estado` char(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`TipoContrato`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_tipoevaluacion`
--

CREATE TABLE IF NOT EXISTS `rh_tipoevaluacion` (
  `TipoEvaluacion` varchar(1) NOT NULL,
  `Descripcion` varchar(50) NOT NULL DEFAULT '',
  `FlagSistema` varchar(1) NOT NULL DEFAULT 'N',
  `Estado` varchar(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`TipoEvaluacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_tiposeguro`
--

CREATE TABLE IF NOT EXISTS `rh_tiposeguro` (
  `CodTipSeguro` varchar(3) NOT NULL,
  `Descripcion` varchar(30) NOT NULL,
  `Estado` char(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodTipSeguro`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_tipotrabajador`
--

CREATE TABLE IF NOT EXISTS `rh_tipotrabajador` (
  `CodTipoTrabajador` varchar(2) NOT NULL,
  `TipoTrabajador` varchar(30) NOT NULL,
  `Estado` char(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodTipoTrabajador`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_transfeventasistencia`
--

CREATE TABLE IF NOT EXISTS `rh_transfeventasistencia` (
  `Fecha` varchar(10) NOT NULL,
  `Hora` varchar(13) NOT NULL,
  `Event_Puerta` varchar(11) NOT NULL,
  `Puerta` varchar(25) NOT NULL,
  `Empleado` varchar(100) NOT NULL,
  `Id_Empleado` varchar(6) NOT NULL,
  `Slot_Numero` int(10) unsigned NOT NULL,
  `CodEvento` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`CodEvento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_utilesayuda`
--

CREATE TABLE IF NOT EXISTS `rh_utilesayuda` (
  `codutilesayuda` bigint(20) NOT NULL AUTO_INCREMENT,
  `numutilesayuda` varchar(2) DEFAULT NULL,
  `descripcionutiles` varchar(100) DEFAULT NULL,
  `periodoutiles` varchar(7) DEFAULT NULL,
  `montoasignado` double DEFAULT NULL,
  `ultimousuario` varchar(30) DEFAULT NULL,
  `ultimafecha` datetime DEFAULT NULL,
  PRIMARY KEY (`codutilesayuda`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_vacacionpago`
--

CREATE TABLE IF NOT EXISTS `rh_vacacionpago` (
  `CodPersona` varchar(6) NOT NULL DEFAULT '',
  `NroPeriodo` int(2) NOT NULL,
  `Secuencia` int(2) NOT NULL,
  `CodTipoNom` varchar(2) NOT NULL COMMENT 'tiponomina->CodTipoNom',
  `DiasPago` int(3) NOT NULL,
  `Periodo` varchar(7) NOT NULL,
  `Concepto` varchar(50) NOT NULL,
  `FechaInicio` date NOT NULL,
  `FechaFin` date NOT NULL,
  `DiasUtiles` int(8) NOT NULL DEFAULT '0',
  `Pasados` int(8) NOT NULL DEFAULT '0',
  `UltimoUsuario` varchar(30) NOT NULL DEFAULT '',
  `UltimaFecha` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`CodPersona`,`NroPeriodo`,`Secuencia`,`CodTipoNom`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 8192 kB';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_vacacionperiodo`
--

CREATE TABLE IF NOT EXISTS `rh_vacacionperiodo` (
  `CodPersona` varchar(6) NOT NULL DEFAULT '',
  `NroPeriodo` int(2) NOT NULL,
  `CodTipoNom` varchar(2) NOT NULL COMMENT 'tiponomina->CodTipoNom',
  `Anio` varchar(4) NOT NULL,
  `Mes` varchar(2) NOT NULL,
  `Derecho` decimal(8,2) NOT NULL DEFAULT '0.00',
  `PendientePeriodo` decimal(8,2) NOT NULL DEFAULT '0.00',
  `DiasGozados` decimal(8,2) NOT NULL DEFAULT '0.00',
  `DiasTrabajados` decimal(8,2) NOT NULL DEFAULT '0.00',
  `DiasInterrumpidos` decimal(8,2) NOT NULL DEFAULT '0.00',
  `DiasNoGozados` decimal(8,2) NOT NULL DEFAULT '0.00',
  `TotalUtilizados` decimal(8,2) NOT NULL DEFAULT '0.00',
  `Pendientes` decimal(8,2) NOT NULL DEFAULT '0.00',
  `PagosRealizados` decimal(8,2) NOT NULL DEFAULT '0.00',
  `PendienteReales` decimal(8,2) NOT NULL DEFAULT '0.00',
  `PendientePago` decimal(8,2) NOT NULL DEFAULT '0.00',
  `UltimoUsuario` varchar(30) NOT NULL DEFAULT '',
  `UltimaFecha` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`CodPersona`,`NroPeriodo`,`CodTipoNom`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 8192 kB';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_vacacionsolicitud`
--

CREATE TABLE IF NOT EXISTS `rh_vacacionsolicitud` (
  `Anio` year(4) NOT NULL,
  `CodSolicitud` varchar(6) NOT NULL,
  `CodPersona` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `Tipo` varchar(1) NOT NULL DEFAULT 'G' COMMENT 'G:GOCE; I:INTERRUPCION;',
  `Fecha` date NOT NULL DEFAULT '0000-00-00',
  `Periodo` varchar(7) NOT NULL,
  `FechaSalida` date NOT NULL DEFAULT '0000-00-00',
  `FechaTermino` date NOT NULL DEFAULT '0000-00-00',
  `NroDias` int(4) NOT NULL,
  `FechaIncorporacion` date NOT NULL DEFAULT '0000-00-00',
  `Motivo` text,
  `Documento` varchar(50) NOT NULL,
  `CreadoPor` varchar(6) DEFAULT NULL COMMENT 'mastpersonas->CodPersona',
  `FechaCreacion` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `AprobadoPor` varchar(6) DEFAULT NULL COMMENT 'mastpersonas->CodPersona',
  `FechaAprobacion` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ConformadoPor` varchar(6) DEFAULT NULL COMMENT 'mastpersonas->CodPersona',
  `FechaConformacion` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `RevisadoPor` varchar(6) DEFAULT NULL COMMENT 'mastpersonas-CodPersona',
  `FechaRevision` datetime DEFAULT NULL,
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `CodDependencia` varchar(4) NOT NULL COMMENT 'mastdependencias->CodDependencia',
  `NroOtorgamiento` varchar(10) DEFAULT NULL,
  `Observaciones` text,
  `Estado` varchar(2) NOT NULL DEFAULT 'PE' COMMENT 'PE:PENDIENTE; AP:APROBADO; CO:CONFORMADO;',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Anio`,`CodSolicitud`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_vacacionsolicituddetalle`
--

CREATE TABLE IF NOT EXISTS `rh_vacacionsolicituddetalle` (
  `Anio` year(4) NOT NULL,
  `CodSolicitud` varchar(6) NOT NULL,
  `Secuencia` int(2) NOT NULL,
  `CodPersona` varchar(6) NOT NULL COMMENT 'mastpersonas-CodPersona',
  `NroPeriodo` int(2) NOT NULL DEFAULT '0',
  `Periodo` varchar(7) NOT NULL,
  `FechaInicio` date NOT NULL DEFAULT '0000-00-00',
  `FechaFin` date NOT NULL DEFAULT '0000-00-00',
  `FechaIncorporacion` date DEFAULT NULL,
  `NroDias` int(4) NOT NULL,
  `DiasDerecho` int(4) NOT NULL,
  `DiasPendientes` int(4) NOT NULL,
  `DiasUsados` int(4) NOT NULL,
  `FlagUtilizarPeriodo` varchar(1) NOT NULL DEFAULT 'N',
  `Observaciones` text,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Anio`,`CodSolicitud`,`Secuencia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_vacacionutilizacion`
--

CREATE TABLE IF NOT EXISTS `rh_vacacionutilizacion` (
  `Secuencia` int(2) NOT NULL,
  `CodPersona` varchar(6) NOT NULL DEFAULT '',
  `NroPeriodo` int(2) NOT NULL,
  `CodTipoNom` varchar(2) NOT NULL COMMENT 'tiponomina->CodTipoNom',
  `FechaInicio` date NOT NULL,
  `FechaFin` date NOT NULL,
  `TipoUtilizacion` varchar(2) NOT NULL,
  `DiasUtiles` int(8) NOT NULL DEFAULT '0',
  `Anio` year(4) DEFAULT NULL COMMENT 'rh_vacacionsolicitud->Anio',
  `CodSolicitud` varchar(6) DEFAULT NULL COMMENT 'rh_vacacionsolicitud->CodSolicitud',
  `UltimoUsuario` varchar(30) NOT NULL DEFAULT '',
  `UltimaFecha` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`Secuencia`,`CodPersona`,`NroPeriodo`,`CodTipoNom`),
  KEY `FK_rh_vacacionutilizacion_1` (`CodPersona`,`NroPeriodo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 8192 kB';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seguridad_alterna`
--

CREATE TABLE IF NOT EXISTS `seguridad_alterna` (
  `CodAplicacion` varchar(10) NOT NULL,
  `Usuario` varchar(20) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL,
  `CodDependencia` varchar(4) NOT NULL,
  `FlagMostrar` varchar(1) NOT NULL,
  `Estado` varchar(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodAplicacion`,`Usuario`,`CodOrganismo`,`CodDependencia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seguridad_autorizaciones`
--

CREATE TABLE IF NOT EXISTS `seguridad_autorizaciones` (
  `CodAplicacion` varchar(10) NOT NULL,
  `Grupo` varchar(20) NOT NULL,
  `Concepto` varchar(7) NOT NULL DEFAULT '',
  `Usuario` varchar(20) NOT NULL,
  `FlagAgregar` varchar(1) NOT NULL,
  `FlagMostrar` varchar(1) NOT NULL,
  `FlagModificar` varchar(1) NOT NULL,
  `FlagEliminar` varchar(1) NOT NULL,
  `FlagAdministrador` varchar(1) NOT NULL,
  `Estado` varchar(1) NOT NULL DEFAULT 'A' COMMENT 'A:ACTIVO; I:INACTIVO',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodAplicacion`,`Grupo`,`Concepto`,`Usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seguridad_concepto`
--

CREATE TABLE IF NOT EXISTS `seguridad_concepto` (
  `CodAplicacion` varchar(10) NOT NULL,
  `Grupo` varchar(20) NOT NULL,
  `Concepto` varchar(7) NOT NULL DEFAULT '',
  `Descripcion` varchar(100) NOT NULL,
  `Estado` varchar(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodAplicacion`,`Grupo`,`Concepto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seguridad_grupo`
--

CREATE TABLE IF NOT EXISTS `seguridad_grupo` (
  `CodAplicacion` varchar(10) NOT NULL,
  `Grupo` varchar(20) NOT NULL,
  `Descripcion` varchar(100) NOT NULL,
  `Estado` varchar(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodAplicacion`,`Grupo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tiponomina`
--

CREATE TABLE IF NOT EXISTS `tiponomina` (
  `CodTipoNom` varchar(2) NOT NULL,
  `Nomina` varchar(30) NOT NULL,
  `FlagPagoMensual` varchar(1) NOT NULL,
  `TituloBoleta` varchar(50) NOT NULL,
  `CodPerfilConcepto` varchar(4) DEFAULT NULL COMMENT 'pr_conceptoperfil->CodPerfilConcepto',
  `Estado` char(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodTipoNom`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipoperfilnom`
--

CREATE TABLE IF NOT EXISTS `tipoperfilnom` (
  `CodPerfil` varchar(2) NOT NULL,
  `Perfil` varchar(30) NOT NULL,
  `Estado` char(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodPerfil`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `titulos`
--

CREATE TABLE IF NOT EXISTS `titulos` (
  `CodTitulo` varchar(4) NOT NULL,
  `Titulo` varchar(30) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodTitulo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `IdUsuario` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `Usuario` varchar(20) NOT NULL DEFAULT '',
  `CodPersona` int(6) unsigned zerofill NOT NULL,
  `Clave` varchar(255) NOT NULL,
  `FlagFechaExpirar` varchar(1) NOT NULL,
  `FechaExpirar` date NOT NULL,
  `Estado` varchar(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  `UltimaSesion` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `IP` varchar(255) DEFAULT NULL,
  `HOSTNAME` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`IdUsuario`),
  UNIQUE KEY `Usuario` (`Usuario`),
  UNIQUE KEY `CodPersona` (`CodPersona`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=178 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_conexiones`
--

CREATE TABLE IF NOT EXISTS `usuario_conexiones` (
  `IP` varchar(16) DEFAULT NULL,
  `usuario` varchar(25) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `modulo` varchar(60) DEFAULT NULL,
  `host` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura para la vista `compromisos_documentos`
--
DROP TABLE IF EXISTS `compromisos_documentos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`admin`@`%` SQL SECURITY DEFINER VIEW `compromisos_documentos` AS (select `dc`.`cod_partida` AS `cod_partida`,`p`.`denominacion` AS `nompartida`,`dc`.`CodTipoDocumento` AS `CodTipoDocumento`,`dc`.`NroDocumento` AS `NroDocumento`,`oc`.`FechaAprobacion` AS `Fecha`,`oc`.`NomProveedor` AS `NomProveedor` from ((`lg_distribucioncompromisos` `dc` join `pv_partida` `p` on((`p`.`cod_partida` = `dc`.`cod_partida`))) join `lg_ordencompra` `oc` on(((`oc`.`CodOrganismo` = `dc`.`CodOrganismo`) and (`oc`.`Anio` = `dc`.`Anio`) and (`oc`.`NroOrden` = `dc`.`NroDocumento`)))) where (`dc`.`Origen` = _utf8'OC')) union (select `dc`.`cod_partida` AS `cod_partida`,`p`.`denominacion` AS `nompartida`,`dc`.`CodTipoDocumento` AS `CodTipoDocumento`,`dc`.`NroDocumento` AS `NroDocumento`,`os`.`FechaAprobacion` AS `Fecha`,`os`.`NomProveedor` AS `NomProveedor` from ((`lg_distribucioncompromisos` `dc` join `pv_partida` `p` on((`p`.`cod_partida` = `dc`.`cod_partida`))) join `lg_ordenservicio` `os` on(((`os`.`CodOrganismo` = `dc`.`CodOrganismo`) and (`os`.`Anio` = `dc`.`Anio`) and (`os`.`NroOrden` = `dc`.`NroDocumento`)))) where (`dc`.`Origen` = _utf8'OS')) union (select `dc`.`cod_partida` AS `cod_partida`,`p`.`denominacion` AS `nompartida`,`dc`.`CodTipoDocumento` AS `CodTipoDocumento`,`dc`.`NroDocumento` AS `NroDocumento`,`o`.`FechaAprobado` AS `Fecha`,`ps`.`NomCompleto` AS `NomProveedor` from (((`lg_distribucioncompromisos` `dc` join `pv_partida` `p` on((`p`.`cod_partida` = `dc`.`cod_partida`))) join `ap_obligaciones` `o` on(((`o`.`CodProveedor` = `dc`.`CodProveedor`) and (`o`.`CodTipoDocumento` = `dc`.`CodTipoDocumento`) and (`o`.`NroDocumento` = `dc`.`NroDocumento`)))) join `mastpersonas` `ps` on((convert(`ps`.`CodPersona` using utf8) = `dc`.`CodProveedor`))) where (`dc`.`Origen` = _utf8'OB')) union (select `dc`.`cod_partida` AS `cod_partida`,`p`.`denominacion` AS `nompartida`,`dc`.`CodTipoDocumento` AS `CodTipoDocumento`,`dc`.`NroDocumento` AS `NroDocumento`,`bt`.`FechaTransaccion` AS `Fecha`,`ps`.`NomCompleto` AS `NomProveedor` from (((`lg_distribucioncompromisos` `dc` join `pv_partida` `p` on((`p`.`cod_partida` = `dc`.`cod_partida`))) join `ap_bancotransaccion` `bt` on(((`bt`.`CodOrganismo` = `dc`.`CodOrganismo`) and (`bt`.`CodTipoDocumento` = `dc`.`CodTipoDocumento`) and (`bt`.`CodigoReferenciaBanco` = `dc`.`NroDocumento`) and (`bt`.`CodProveedor` = `dc`.`CodProveedor`)))) join `mastpersonas` `ps` on((convert(`ps`.`CodPersona` using utf8) = `dc`.`CodProveedor`))) where (`dc`.`Origen` = _utf8'TB')) order by `cod_partida`,`Fecha`;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `mastciudades`
--
ALTER TABLE `mastciudades`
  ADD CONSTRAINT `mastciudades_ibfk_1` FOREIGN KEY (`CodMunicipio`) REFERENCES `mastmunicipios` (`CodMunicipio`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `mastdependencias`
--
ALTER TABLE `mastdependencias`
  ADD CONSTRAINT `mastdependencias_ibfk_1` FOREIGN KEY (`CodOrganismo`) REFERENCES `mastorganismos` (`CodOrganismo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `mastempleado`
--
ALTER TABLE `mastempleado`
  ADD CONSTRAINT `mastempleado_ibfk_1` FOREIGN KEY (`CodDependencia`) REFERENCES `mastdependencias` (`CodDependencia`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mastempleado_ibfk_2` FOREIGN KEY (`CodPersona`) REFERENCES `mastpersonas` (`CodPersona`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `mastestados`
--
ALTER TABLE `mastestados`
  ADD CONSTRAINT `mastestados_ibfk_1` FOREIGN KEY (`CodPais`) REFERENCES `mastpaises` (`CodPais`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `mastmunicipios`
--
ALTER TABLE `mastmunicipios`
  ADD CONSTRAINT `mastmunicipios_ibfk_1` FOREIGN KEY (`CodEstado`) REFERENCES `mastestados` (`CodEstado`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `mastorganismos`
--
ALTER TABLE `mastorganismos`
  ADD CONSTRAINT `mastorganismos_ibfk_1` FOREIGN KEY (`CodCiudad`) REFERENCES `mastciudades` (`CodCiudad`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `rh_beneficiarioutiles`
--
ALTER TABLE `rh_beneficiarioutiles`
  ADD CONSTRAINT `fk_reference_1` FOREIGN KEY (`codutilesayuda`) REFERENCES `rh_utilesayuda` (`codutilesayuda`);

--
-- Filtros para la tabla `rh_familarutilesbeneficio`
--
ALTER TABLE `rh_familarutilesbeneficio`
  ADD CONSTRAINT `fk_reference_2` FOREIGN KEY (`codbeneficiarioutiles`) REFERENCES `rh_beneficiarioutiles` (`codbeneficiarioutiles`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`CodPersona`) REFERENCES `mastpersonas` (`CodPersona`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
