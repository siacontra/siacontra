-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pv_ajustepresupuestario`
--

CREATE TABLE IF NOT EXISTS `pv_ajustepresupuestario` (
  `Organismo` varchar(4) NOT NULL DEFAULT '',
  `CodPresupuesto` varchar(4) NOT NULL DEFAULT '',
  `CodAjuste` varchar(4) NOT NULL DEFAULT '',
  `FechaAjuste` date NOT NULL DEFAULT '0000-00-00',
  `Periodo` varchar(7) NOT NULL DEFAULT '',
  `TipoAjuste` varchar(2) NOT NULL DEFAULT '' COMMENT 'PR: Preparado; AP: Aprobado',
  `NumGaceta` varchar(20) NOT NULL DEFAULT '',
  `FechaGaceta` date NOT NULL DEFAULT '0000-00-00',
  `NumResolucion` varchar(20) NOT NULL DEFAULT '',
  `FechaResolucion` date NOT NULL DEFAULT '0000-00-00',
  `Descripcion` longtext NOT NULL,
  `TotalAjuste` double(11,2) NOT NULL,
  `PreparadoPor` varchar(150) NOT NULL,
  `FechaPreparacion` date NOT NULL,
  `AprobadoPor` varchar(150) NOT NULL,
  `FechaAprobacion` date NOT NULL,
  `Estado` varchar(2) NOT NULL COMMENT 'PE:Preparado; AP: Aprobado',
  `UltimaFechaModif` datetime NOT NULL,
  `UltimoUsuario` varchar(150) NOT NULL,
  `MotivoAjuste` varchar(2) NOT NULL DEFAULT '' COMMENT 'Miscelaneo',
  PRIMARY KEY (`Organismo`,`CodPresupuesto`,`CodAjuste`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pv_ajustepresupuestariodet`
--

CREATE TABLE IF NOT EXISTS `pv_ajustepresupuestariodet` (
  `Organismo` varchar(4) NOT NULL DEFAULT '',
  `CodPresupuesto` varchar(4) NOT NULL,
  `CodAjuste` varchar(4) NOT NULL,
  `cod_partida` varchar(12) NOT NULL DEFAULT '',
  `Estado` varchar(2) NOT NULL COMMENT 'PE: Preparacion; AP: Aprobado ',
  `MontoDisponible` double(11,2) NOT NULL DEFAULT '0.00',
  `MontoAjuste` double(11,2) NOT NULL DEFAULT '0.00',
  `UltimoUsuario` varchar(150) NOT NULL DEFAULT '',
  `UltimaFechaModif` datetime NOT NULL,
  PRIMARY KEY (`Organismo`,`CodPresupuesto`,`CodAjuste`,`cod_partida`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pv_antepresupuesto`
--

CREATE TABLE IF NOT EXISTS `pv_antepresupuesto` (
  `Organismo` varchar(4) NOT NULL DEFAULT '',
  `CodAnteproyecto` varchar(4) NOT NULL,
  `EjercicioPpto` varchar(4) NOT NULL,
  `FechaAnteproyecto` date NOT NULL,
  `FechaInicio` date NOT NULL,
  `FechaFin` date NOT NULL,
  `MontoPresupuestado` double(11,2) NOT NULL DEFAULT '0.00',
  `Sector` varchar(4) NOT NULL DEFAULT '',
  `Programa` varchar(4) NOT NULL DEFAULT '',
  `SubPrograma` varchar(4) NOT NULL DEFAULT '',
  `Proyecto` varchar(4) NOT NULL DEFAULT '',
  `Actividad` varchar(4) NOT NULL DEFAULT '',
  `UnidadEjecutora` varchar(250) NOT NULL DEFAULT '',
  `NumeroGaceta` varchar(10) NOT NULL,
  `FechaGaceta` date NOT NULL,
  `NumeroDecreto` varchar(10) NOT NULL,
  `FechaDecreto` date NOT NULL,
  `AprobadoPor` varchar(250) NOT NULL,
  `FechaAprobacion` date NOT NULL,
  `PreparadoPor` varchar(250) NOT NULL,
  `FechaPreparacion` date NOT NULL,
  `Estado` varchar(11) NOT NULL,
  `UltimoUsuario` varchar(100) NOT NULL,
  `UltimaFechaModif` datetime NOT NULL,
  `MontoGenerado` double(11,2) NOT NULL DEFAULT '0.00',
  `FechaGenerado` date NOT NULL,
  PRIMARY KEY (`CodAnteproyecto`,`Organismo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pv_antepresupuestodet`
--

CREATE TABLE IF NOT EXISTS `pv_antepresupuestodet` (
  `Organismo` varchar(4) NOT NULL DEFAULT '',
  `CodAnteproyecto` varchar(4) NOT NULL,
  `Secuencia` varchar(4) NOT NULL,
  `tipocuenta` varchar(1) NOT NULL,
  `partida` varchar(2) NOT NULL,
  `generica` varchar(2) NOT NULL DEFAULT '',
  `especifica` varchar(2) NOT NULL,
  `subespecifica` varchar(2) NOT NULL,
  `cod_partida` varchar(12) NOT NULL,
  `tipo` varchar(1) NOT NULL,
  `MontoPresupuestado` double(11,2) NOT NULL DEFAULT '0.00',
  `Estado` varchar(9) NOT NULL,
  `UltimoUsuario` varchar(100) NOT NULL,
  `UltimaFechaModif` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `MontoAprobado` double(11,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`Organismo`,`CodAnteproyecto`,`Secuencia`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pv_controlcierremensual`
--

CREATE TABLE IF NOT EXISTS `pv_controlcierremensual` (
  `Organismo` char(8) NOT NULL DEFAULT '',
  `Periodo` char(6) NOT NULL DEFAULT '',
  `Estado` char(1) NOT NULL DEFAULT '' COMMENT 'A: Abierto; C:Cerrado',
  `UltimoUsuario` char(20) NOT NULL DEFAULT '',
  `UltimaFechaModif` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`Organismo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pv_credito_adicional`
--

CREATE TABLE IF NOT EXISTS `pv_credito_adicional` (
  `co_credito_adicional` int(10) NOT NULL AUTO_INCREMENT COMMENT 'CODIGO UNICO DEL CREDITO ADICIONAL',
  `CodOrganismo` varchar(4) CHARACTER SET utf8 NOT NULL,
  `CodAnteproyecto` varchar(4) COLLATE utf8_spanish_ci NOT NULL,
  `nu_oficio` varchar(15) CHARACTER SET utf8 DEFAULT NULL,
  `ff_oficio` date DEFAULT NULL,
  `nu_decreto` varchar(15) CHARACTER SET utf8 DEFAULT NULL,
  `ff_decreto` date DEFAULT NULL,
  `tx_motivo` text CHARACTER SET utf8 NOT NULL,
  `ff_ejecucion` varchar(4) CHARACTER SET utf8 NOT NULL,
  `ff_creacion` date NOT NULL,
  `tx_estatus` varchar(20) CHARACTER SET utf8 NOT NULL,
  `tx_preparado` varchar(25) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `tx_aprobado` varchar(25) CHARACTER SET utf8 NOT NULL,
  `tx_revisado_por` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `tx_conformado_por` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `ff_revisada` datetime NOT NULL,
  `ff_conformada` datetime NOT NULL,
  `ff_aprobada` datetime NOT NULL,
  `tx_ultima_modificacion` varchar(25) CHARACTER SET utf8 NOT NULL,
  `ff_ultima_modoficacion` datetime NOT NULL,
  `mm_monto_total` double(11,2) NOT NULL,
  PRIMARY KEY (`co_credito_adicional`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pv_credito_adicional_old`
--

CREATE TABLE IF NOT EXISTS `pv_credito_adicional_old` (
  `co_credito_adicional` int(10) NOT NULL AUTO_INCREMENT COMMENT 'CODIGO UNICO DEL CREDITO ADICIONAL',
  `CodOrganismo` varchar(4) CHARACTER SET utf8 NOT NULL,
  `CodAnteproyecto` varchar(4) COLLATE utf8_spanish_ci NOT NULL,
  `nu_oficio` varchar(15) CHARACTER SET utf8 DEFAULT NULL,
  `ff_oficio` date DEFAULT NULL,
  `nu_decreto` varchar(15) CHARACTER SET utf8 DEFAULT NULL,
  `ff_decreto` date DEFAULT NULL,
  `tx_motivo` text CHARACTER SET utf8 NOT NULL,
  `ff_ejecucion` varchar(4) CHARACTER SET utf8 NOT NULL,
  `ff_creacion` date NOT NULL,
  `tx_estatus` varchar(20) CHARACTER SET utf8 NOT NULL,
  `tx_preparado` varchar(25) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `tx_aprobado` varchar(25) CHARACTER SET utf8 NOT NULL,
  `tx_revisado_por` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `tx_conformado_por` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `ff_revisada` datetime NOT NULL,
  `ff_conformada` datetime NOT NULL,
  `ff_aprobada` datetime NOT NULL,
  `tx_ultima_modificacion` varchar(25) CHARACTER SET utf8 NOT NULL,
  `ff_ultima_modoficacion` datetime NOT NULL,
  `mm_monto_total` double(11,2) NOT NULL,
  PRIMARY KEY (`co_credito_adicional`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=76 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pv_ejecucionpresupuestaria`
--

CREATE TABLE IF NOT EXISTS `pv_ejecucionpresupuestaria` (
  `CodOrganismo` varchar(4) NOT NULL DEFAULT '',
  `Periodo` varchar(7) NOT NULL DEFAULT '',
  `CodPresupuesto` varchar(4) NOT NULL DEFAULT '',
  `CodPartida` varchar(12) NOT NULL DEFAULT '',
  `Secuencia` varchar(4) NOT NULL DEFAULT '',
  `DispPresuInicial` double(11,2) NOT NULL DEFAULT '0.00',
  `DispFinanInicial` double(11,2) NOT NULL DEFAULT '0.00',
  `Reintegro` double(11,2) NOT NULL DEFAULT '0.00',
  `Incremento` double(11,2) NOT NULL DEFAULT '0.00',
  `Disminucion` double(11,2) NOT NULL DEFAULT '0.00',
  `PptoAjustado` double(11,2) NOT NULL DEFAULT '0.00',
  `Compromisos` double(11,2) NOT NULL DEFAULT '0.00',
  `Causado` double(11,2) NOT NULL DEFAULT '0.00',
  `Pagado` double(11,2) NOT NULL DEFAULT '0.00',
  `DispPresupuestaria` double(11,2) NOT NULL DEFAULT '0.00',
  `DispFinanciera` double(11,2) NOT NULL DEFAULT '0.00',
  `Variacion` double(11,2) NOT NULL DEFAULT '0.00',
  `UltimoUsuario` varchar(15) NOT NULL DEFAULT '',
  `UltimaFechaModif` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`CodOrganismo`,`Periodo`,`CodPartida`,`Secuencia`,`CodPresupuesto`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pv_item_credito_adicional`
--

CREATE TABLE IF NOT EXISTS `pv_item_credito_adicional` (
  `co_item_credito_adicional` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `co_credito_adicional` int(10) NOT NULL,
  `cod_partida` varchar(16) CHARACTER SET utf8 NOT NULL,
  `mm_monto` double(11,2) NOT NULL,
  PRIMARY KEY (`co_item_credito_adicional`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=197 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pv_partida`
--

CREATE TABLE IF NOT EXISTS `pv_partida` (
  `cod_partida` varchar(12) NOT NULL,
  `partida1` varchar(2) NOT NULL,
  `generica` varchar(2) NOT NULL,
  `especifica` varchar(2) NOT NULL,
  `subespecifica` varchar(2) NOT NULL,
  `denominacion` varchar(300) NOT NULL,
  `cod_tipocuenta` varchar(1) NOT NULL,
  `Estado` char(1) NOT NULL,
  `UltimoUsuario` varchar(100) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  `tipo` char(1) NOT NULL,
  `nivel` char(1) NOT NULL,
  `CodCuenta` varchar(13) NOT NULL,
  `CodCuentaPub20` varchar(13) DEFAULT NULL,
  `CtaOrdPagoPub20` varchar(13) DEFAULT NULL,
  PRIMARY KEY (`cod_partida`),
  KEY `cod_tipocuenta` (`cod_tipocuenta`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pv_presupuesto`
--

CREATE TABLE IF NOT EXISTS `pv_presupuesto` (
  `Organismo` varchar(4) NOT NULL DEFAULT '',
  `CodPresupuesto` varchar(4) NOT NULL,
  `EjercicioPpto` varchar(4) NOT NULL,
  `FechaPresupuesto` date NOT NULL,
  `FechaInicio` date NOT NULL,
  `FechaFin` date NOT NULL,
  `Sector` varchar(4) NOT NULL DEFAULT '',
  `Programa` varchar(4) NOT NULL DEFAULT '',
  `SubPrograma` varchar(4) NOT NULL DEFAULT '',
  `Proyecto` varchar(4) NOT NULL DEFAULT '',
  `Actividad` varchar(4) NOT NULL DEFAULT '',
  `UnidadEjecutora` varchar(250) NOT NULL,
  `NumeroGaceta` varchar(5) NOT NULL,
  `NumeroDecreto` varchar(5) NOT NULL,
  `FechaGaceta` date NOT NULL,
  `FechaDecreto` date NOT NULL,
  `AprobadoPor` varchar(250) NOT NULL,
  `PreparadoPor` varchar(250) NOT NULL,
  `MontoAprobado` double(11,2) NOT NULL DEFAULT '0.00',
  `MontoAjustado` double(11,2) NOT NULL DEFAULT '0.00',
  `Estado` varchar(11) NOT NULL DEFAULT '',
  `UltimoUsuario` varchar(100) NOT NULL,
  `UltimaFechaModif` datetime NOT NULL,
  PRIMARY KEY (`Organismo`,`CodPresupuesto`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pv_presupuestodet`
--

CREATE TABLE IF NOT EXISTS `pv_presupuestodet` (
  `Organismo` varchar(4) NOT NULL DEFAULT '',
  `CodPresupuesto` varchar(4) NOT NULL DEFAULT '',
  `Secuencia` varchar(4) NOT NULL DEFAULT '',
  `cod_partida` varchar(12) NOT NULL DEFAULT '',
  `FlagsAnexa` varchar(1) NOT NULL,
  `FlagsReformulacion` varchar(1) NOT NULL,
  `Ajuste` varchar(1) NOT NULL,
  `partida` varchar(2) NOT NULL DEFAULT '',
  `generica` varchar(2) NOT NULL DEFAULT '',
  `especifica` varchar(2) NOT NULL,
  `subespecifica` varchar(2) NOT NULL,
  `tipocuenta` varchar(1) NOT NULL,
  `tipo` varchar(1) NOT NULL,
  `MontoAprobado` decimal(11,2) NOT NULL DEFAULT '0.00',
  `MontoAjustado` decimal(11,2) NOT NULL DEFAULT '0.00',
  `Estado` varchar(9) NOT NULL DEFAULT '',
  `UltimoUsuario` varchar(100) NOT NULL,
  `UltimaFechaModif` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `MontoCompromiso` decimal(11,2) DEFAULT '0.00',
  `MontoCausado` decimal(11,2) DEFAULT '0.00',
  `MontoPagado` decimal(11,2) DEFAULT '0.00',
  `MontoReintegrado` decimal(11,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`Organismo`,`CodPresupuesto`,`Secuencia`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pv_programa1`
--

CREATE TABLE IF NOT EXISTS `pv_programa1` (
  `id_programa` varchar(4) NOT NULL,
  `cod_programa` varchar(2) NOT NULL,
  `descp_programa` varchar(250) NOT NULL,
  `cod_sector` varchar(2) NOT NULL,
  `Estado` char(1) NOT NULL,
  `UltimoUsuario` varchar(100) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`id_programa`),
  KEY `cod_sector` (`cod_sector`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pv_proyecto1`
--

CREATE TABLE IF NOT EXISTS `pv_proyecto1` (
  `id_proyecto` varchar(4) NOT NULL,
  `id_sub` varchar(4) NOT NULL,
  `cod_proyecto` varchar(2) NOT NULL,
  `descp_proyecto` varchar(250) NOT NULL,
  `Estado` char(1) NOT NULL,
  `UltimoUsuario` varchar(100) NOT NULL,
  `UltimaFecha` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id_proyecto`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pv_reformulacionppto`
--

CREATE TABLE IF NOT EXISTS `pv_reformulacionppto` (
  `Organismo` varchar(4) NOT NULL DEFAULT '',
  `CodPresupuesto` varchar(4) NOT NULL,
  `CodRef` varchar(4) NOT NULL,
  `NumGaceta` varchar(20) NOT NULL DEFAULT '',
  `FechaGaceta` date NOT NULL DEFAULT '0000-00-00',
  `NumResolucion` varchar(20) NOT NULL DEFAULT '',
  `FechaResolucion` date NOT NULL DEFAULT '0000-00-00',
  `MontoRef` double(11,2) NOT NULL DEFAULT '0.00',
  `Descripcion` longtext NOT NULL,
  `PreparadoPor` varchar(6) NOT NULL,
  `FechaPreparacion` date NOT NULL,
  `AprobadoPor` varchar(6) NOT NULL,
  `FechaAprobacion` date NOT NULL,
  `FechaRef` date NOT NULL,
  `PeriodoRef` varchar(7) NOT NULL,
  `Estado` varchar(2) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFechaModif` datetime NOT NULL,
  PRIMARY KEY (`Organismo`,`CodPresupuesto`,`CodRef`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pv_reformulacionpptodet`
--

CREATE TABLE IF NOT EXISTS `pv_reformulacionpptodet` (
  `Organismo` varchar(4) NOT NULL DEFAULT '',
  `CodPresupuesto` varchar(4) NOT NULL,
  `CodRef` varchar(4) NOT NULL,
  `cod_partida` varchar(12) NOT NULL DEFAULT '',
  `MontoRef` double(11,2) NOT NULL,
  `Estado` varchar(2) NOT NULL DEFAULT '',
  `UltimoUsuario` varchar(150) NOT NULL,
  `UltimaFechaModif` datetime NOT NULL,
  PRIMARY KEY (`Organismo`,`CodPresupuesto`,`CodRef`,`cod_partida`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pv_ReintegroPresupuestario`
--

CREATE TABLE IF NOT EXISTS `pv_ReintegroPresupuestario` (
  `Organismo` varchar(4) NOT NULL DEFAULT '',
  `CodPresupuesto` varchar(4) NOT NULL DEFAULT '',
  `CodReintegro` varchar(4) NOT NULL DEFAULT '',
  `FechaAjuste` date NOT NULL DEFAULT '0000-00-00',
  `Periodo` varchar(7) NOT NULL DEFAULT '',
  `NumGaceta` varchar(20) NOT NULL DEFAULT '',
  `FechaGaceta` date NOT NULL DEFAULT '0000-00-00',
  `NumResolucion` varchar(20) NOT NULL DEFAULT '',
  `FechaResolucion` date NOT NULL DEFAULT '0000-00-00',
  `Descripcion` longtext NOT NULL,
  `TotalAjuste` double(11,2) NOT NULL,
  `PreparadoPor` varchar(150) NOT NULL,
  `FechaPreparacion` date NOT NULL,
  `AprobadoPor` varchar(150) NOT NULL,
  `FechaAprobacion` date NOT NULL,
  `Estado` varchar(2) NOT NULL COMMENT 'PE:Preparado; AP: Aprobado',
  `UltimaFechaModif` datetime NOT NULL,
  `UltimoUsuario` varchar(150) NOT NULL,
  PRIMARY KEY (`Organismo`,`CodPresupuesto`,`CodReintegro`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pv_ReintegroPresupuestariodet`
--

CREATE TABLE IF NOT EXISTS `pv_ReintegroPresupuestariodet` (
  `Organismo` varchar(4) NOT NULL DEFAULT '',
  `CodPresupuesto` varchar(4) NOT NULL,
  `CodReintegro` varchar(4) NOT NULL,
  `cod_partida` varchar(12) NOT NULL DEFAULT '',
  `Estado` varchar(2) NOT NULL COMMENT 'PE: Preparacion; AP: Aprobado ',
  `MontoDisponible` double(11,2) NOT NULL DEFAULT '0.00',
  `MontoReintegro` double(11,2) NOT NULL DEFAULT '0.00',
  `UltimoUsuario` varchar(150) NOT NULL DEFAULT '',
  `UltimaFechaModif` datetime NOT NULL,
  PRIMARY KEY (`Organismo`,`CodPresupuesto`,`CodReintegro`,`cod_partida`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pv_sector`
--

CREATE TABLE IF NOT EXISTS `pv_sector` (
  `cod_sector` varchar(2) NOT NULL,
  `descripcion` varchar(250) NOT NULL,
  `Estado` char(1) NOT NULL,
  `UltimoUsuario` varchar(100) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`cod_sector`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pv_subprog1`
--

CREATE TABLE IF NOT EXISTS `pv_subprog1` (
  `id_sub` varchar(4) NOT NULL,
  `id_programa` varchar(4) NOT NULL,
  `cod_subprog` varchar(2) NOT NULL,
  `descp_subprog` varchar(250) NOT NULL,
  `Estado` char(1) NOT NULL,
  `UltimoUsuario` varchar(100) NOT NULL,
  `UltimaFecha` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id_sub`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pv_tipocuenta`
--

CREATE TABLE IF NOT EXISTS `pv_tipocuenta` (
  `cod_tipocuenta` varchar(1) NOT NULL,
  `descp_tipocuenta` varchar(8) NOT NULL,
  `Estado` char(1) NOT NULL,
  `UltimoUsuario` varchar(100) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`cod_tipocuenta`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pv_unidadejecutora`
--

CREATE TABLE IF NOT EXISTS `pv_unidadejecutora` (
  `id_unidadejecutora` varchar(4) NOT NULL,
  `Unidadejecutora` varchar(250) NOT NULL,
  `UltimoUsuario` varchar(100) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  `Estado` varchar(1) NOT NULL,
  PRIMARY KEY (`id_unidadejecutora`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `py_conceptoporcentaje`
--

CREATE TABLE IF NOT EXISTS `py_conceptoporcentaje` (
  `CodConcepto` varchar(255) NOT NULL,
  `Porcentaje` varchar(255) NOT NULL,
  `CodProyeccion` varchar(255) NOT NULL,
  `Desde` varchar(255) DEFAULT NULL,
  `Hasta` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`CodConcepto`,`CodProyeccion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `py_empleadoproceso`
--

CREATE TABLE IF NOT EXISTS `py_empleadoproceso` (
  `CodProceso` varchar(11) NOT NULL,
  `CodPersona` varchar(6) NOT NULL DEFAULT '',
  `SueldoBasico` decimal(15,2) DEFAULT '0.00',
  `TotalIngresos` decimal(15,2) DEFAULT '0.00',
  `TotalEgreso` decimal(15,2) DEFAULT '0.00',
  `TotalPatronales` decimal(15,2) DEFAULT '0.00',
  `TotalNeto` decimal(15,2) DEFAULT '0.00',
  `Periodo` varchar(10) NOT NULL,
  `CodProyeccion` varchar(10) NOT NULL,
  `CodTipoProceso` varchar(10) NOT NULL,
  `CodTipoNom` varchar(10) NOT NULL,
  PRIMARY KEY (`CodProceso`,`CodPersona`,`CodProyeccion`,`CodTipoProceso`,`CodTipoNom`,`Periodo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `py_empleadoprocesoconcepto`
--

CREATE TABLE IF NOT EXISTS `py_empleadoprocesoconcepto` (
  `CodTipoProceso` varchar(10) NOT NULL,
  `CodPersona` varchar(20) NOT NULL,
  `CodConcepto` varchar(20) NOT NULL,
  `CodTipoNom` varchar(20) NOT NULL,
  `CodProyeccion` varchar(20) NOT NULL,
  `Periodo` varchar(20) NOT NULL,
  `Monto` decimal(15,2) NOT NULL DEFAULT '0.00',
  `MontoP` decimal(15,2) DEFAULT NULL,
  `Cantidad` int(15) DEFAULT '0',
  `Saldo` decimal(15,2) DEFAULT '0.00',
  PRIMARY KEY (`CodTipoProceso`,`CodPersona`,`CodConcepto`,`CodTipoNom`,`CodProyeccion`,`Periodo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `py_proceso`
--

CREATE TABLE IF NOT EXISTS `py_proceso` (
  `CodProyeccion` varchar(255) NOT NULL,
  `CodTipoNom` varchar(255) NOT NULL,
  `Periodo` varchar(255) NOT NULL,
  `CodTipoProceso` varchar(255) NOT NULL,
  `Mes` varchar(255) DEFAULT NULL,
  `Anio` varchar(255) DEFAULT NULL,
  `Descripcion` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`CodProyeccion`,`CodTipoNom`,`Periodo`,`CodTipoProceso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `py_proyeccion`
--

CREATE TABLE IF NOT EXISTS `py_proyeccion` (
  `CodProyeccion` varchar(255) NOT NULL,
  `Descripcion` varchar(255) DEFAULT NULL,
  `Desde` varchar(255) DEFAULT NULL,
  `Hasta` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`CodProyeccion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_asociacioncarreras`
--

CREATE TABLE IF NOT EXISTS `rh_asociacioncarreras` (
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `Secuencia` int(4) NOT NULL COMMENT 'rh_evaluacionperiodo->Secuencia',
  `Codigo` varchar(4) NOT NULL,
  `CodPersona` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `CodCargo` varchar(4) NOT NULL COMMENT 'rh_puestos->CodCargo',
  `DescripCargo` varchar(255) NOT NULL,
  `CodDependencia` varchar(4) NOT NULL COMMENT 'mastdependencias->CodDependencia',
  `Periodo` varchar(7) NOT NULL,
  `Estado` varchar(2) NOT NULL DEFAULT 'A' COMMENT 'AB:ABIERTO; TE:TERMINADO; AN:ANULADO',
  `UltimoUsuario` varchar(30) DEFAULT NULL,
  `UltimaFecha` datetime DEFAULT NULL,
  `IniciadoPor` varchar(6) DEFAULT NULL,
  `FechaIniciado` date DEFAULT NULL,
  `TerminadoPor` varchar(45) DEFAULT NULL,
  `FechaTerminado` date DEFAULT NULL,
  PRIMARY KEY (`CodOrganismo`,`Secuencia`,`Codigo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=76;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_asociacioncarrerascaptecnica`
--

CREATE TABLE IF NOT EXISTS `rh_asociacioncarrerascaptecnica` (
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `Secuencia` int(4) NOT NULL COMMENT 'rh_evaluacionperiodo->Secuencia',
  `Codigo` varchar(4) NOT NULL,
  `Linea` int(2) NOT NULL,
  `Descripcion` text NOT NULL,
  `UltimoUsuario` varchar(30) DEFAULT NULL,
  `UltimaFecha` datetime DEFAULT NULL,
  PRIMARY KEY (`CodOrganismo`,`Secuencia`,`Codigo`,`Linea`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_asociacioncarrerasevaluacion`
--

CREATE TABLE IF NOT EXISTS `rh_asociacioncarrerasevaluacion` (
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `Secuencia` int(4) NOT NULL COMMENT 'rh_evaluacionperiodo->Secuencia',
  `Codigo` varchar(4) NOT NULL,
  `Linea` int(2) NOT NULL,
  `Descripcion` text NOT NULL,
  `UltimoUsuario` varchar(30) DEFAULT NULL,
  `UltimaFecha` datetime DEFAULT NULL,
  PRIMARY KEY (`CodOrganismo`,`Secuencia`,`Codigo`,`Linea`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_asociacioncarrerashabilidad`
--

CREATE TABLE IF NOT EXISTS `rh_asociacioncarrerashabilidad` (
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `Secuencia` int(4) NOT NULL COMMENT 'rh_evaluacionperiodo->Secuencia',
  `Codigo` varchar(4) NOT NULL,
  `Linea` int(2) NOT NULL,
  `Tipo` varchar(2) NOT NULL DEFAULT 'H' COMMENT 'H:HABILIDAD; D:DESTREZA; C:CAPACIDAD',
  `Descripcion` text NOT NULL,
  `UltimoUsuario` varchar(30) DEFAULT NULL,
  `UltimaFecha` datetime DEFAULT NULL,
  PRIMARY KEY (`CodOrganismo`,`Secuencia`,`Codigo`,`Linea`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_asociacioncarrerasmetas`
--

CREATE TABLE IF NOT EXISTS `rh_asociacioncarrerasmetas` (
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `Secuencia` int(4) NOT NULL COMMENT 'rh_evaluacionperiodo->Secuencia',
  `Codigo` varchar(4) NOT NULL,
  `Linea` int(2) NOT NULL,
  `Descripcion` text NOT NULL,
  `UltimoUsuario` varchar(30) DEFAULT NULL,
  `UltimaFecha` datetime DEFAULT NULL,
  PRIMARY KEY (`CodOrganismo`,`Secuencia`,`Codigo`,`Linea`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_ayudamedicaespecifica`
--

CREATE TABLE IF NOT EXISTS `rh_ayudamedicaespecifica` (
  `codAyudaE` int(11) NOT NULL AUTO_INCREMENT,
  `numAyudaE` varchar(5) CHARACTER SET utf8 NOT NULL,
  `codAyudaG` int(11) NOT NULL,
  `decripcionAyudaE` varchar(3000) CHARACTER SET utf8 NOT NULL,
  `limiteAyudaE` double(14,2) NOT NULL,
  `CodPerAprobar` varchar(6) CHARACTER SET utf8 NOT NULL,
  `Estado` varchar(1) CHARACTER SET utf8 NOT NULL DEFAULT 'A',
  `UltimoUsuario` varchar(30) CHARACTER SET utf8 NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`codAyudaE`),
  KEY `fk_rh_ayudamedicaespecifica` (`codAyudaG`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AVG_ROW_LENGTH=64 AUTO_INCREMENT=33 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_ayudamedicaglobal`
--

CREATE TABLE IF NOT EXISTS `rh_ayudamedicaglobal` (
  `codAyudaG` int(11) NOT NULL AUTO_INCREMENT,
  `numAyudaG` varchar(5) CHARACTER SET utf8 NOT NULL,
  `decripcionAyudaG` varchar(3000) CHARACTER SET utf8 NOT NULL,
  `limiteAyudaG` double(14,2) NOT NULL,
  `UltimoUsuario` varchar(30) CHARACTER SET utf8 NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`codAyudaG`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AVG_ROW_LENGTH=60 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_beneficiariopension`
--

CREATE TABLE IF NOT EXISTS `rh_beneficiariopension` (
  `CodProceso` varchar(4) NOT NULL,
  `Secuencia` int(2) NOT NULL,
  `CodPersona` varchar(6) NOT NULL,
  `CodBeneficiario` varchar(6) NOT NULL,
  `NroDocumento` varchar(20) NOT NULL,
  `NombreCompleto` varchar(100) NOT NULL,
  `Parentesco` varchar(2) NOT NULL COMMENT 'miscelaneo->PARENT',
  `FechaNacimiento` date NOT NULL,
  `Sexo` varchar(1) NOT NULL,
  `FlagIncapacitados` varchar(1) NOT NULL,
  `FlagPrincipal` varchar(1) NOT NULL DEFAULT 'N',
  `FlagEstudia` varchar(1) NOT NULL DEFAULT 'N',
  `Estado` varchar(1) NOT NULL DEFAULT 'A' COMMENT 'A:ACTIVO; I:INACTIVO;',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodProceso`,`Secuencia`),
  KEY `FK_rh_beneficiariopension_1` (`CodPersona`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_beneficiarioutiles`
--

CREATE TABLE IF NOT EXISTS `rh_beneficiarioutiles` (
  `codbeneficiarioutiles` bigint(20) NOT NULL AUTO_INCREMENT,
  `nroBeneficioUtiles` varchar(5) NOT NULL,
  `codutilesayuda` bigint(20) DEFAULT NULL,
  `CodProveedor` varchar(6) NOT NULL,
  `codpersonabeneficiario` varchar(6) DEFAULT NULL COMMENT 'Esta relacionado con la persona que es empleado, de alli se sabra cual es su tipo de nomina para los reportes',
  `observacionutiles` varchar(100) DEFAULT NULL,
  `montoutiles` double DEFAULT NULL,
  `ultimousuario` varchar(30) DEFAULT NULL,
  `ultimafecha` datetime DEFAULT NULL,
  PRIMARY KEY (`codbeneficiarioutiles`),
  KEY `fk_reference_1` (`codutilesayuda`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_beneficio`
--

CREATE TABLE IF NOT EXISTS `rh_beneficio` (
  `codBeneficio` int(10) NOT NULL AUTO_INCREMENT,
  `CodOrganismo` varchar(5) CHARACTER SET utf8 NOT NULL,
  `nroBeneficio` varchar(5) CHARACTER SET utf8 NOT NULL,
  `tipoSolicitud` varchar(2) CHARACTER SET utf8 NOT NULL,
  `codPersona` varchar(6) CHARACTER SET utf8 NOT NULL,
  `codFamiliar` varchar(6) CHARACTER SET utf8 DEFAULT NULL,
  `codAyudaE` int(5) NOT NULL,
  `codRamaS` int(11) NOT NULL,
  `idInstHcm` int(11) NOT NULL,
  `idMedHcm` int(11) NOT NULL,
  `anhoEjecucio` year(4) NOT NULL,
  `fechaEjecucion` date NOT NULL,
  `estadoBeneficio` varchar(2) CHARACTER SET utf8 NOT NULL,
  `montoTotal` double(14,2) NOT NULL,
  `UltimoUsuario` varchar(30) CHARACTER SET utf8 NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  `preparadoPor` varchar(30) CHARACTER SET utf8 NOT NULL,
  `aprobadoPor` varchar(6) CHARACTER SET utf8 NOT NULL,
  `fechaAprobacion` date NOT NULL,
  `planillaSolicitud` tinyint(1) NOT NULL,
  `informeMedico` tinyint(1) NOT NULL,
  `facturaMedicina` tinyint(1) DEFAULT NULL,
  `recipeMedico` tinyint(1) DEFAULT NULL,
  `otros` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`codBeneficio`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AVG_ROW_LENGTH=100 AUTO_INCREMENT=1286 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_bonoalimentacion`
--

CREATE TABLE IF NOT EXISTS `rh_bonoalimentacion` (
  `Anio` year(4) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `CodBonoAlim` varchar(3) NOT NULL,
  `Periodo` varchar(7) NOT NULL,
  `Descripcion` varchar(100) NOT NULL,
  `FechaInicio` date NOT NULL DEFAULT '0000-00-00',
  `FechaFin` date NOT NULL DEFAULT '0000-00-00',
  `CodTipoNom` varchar(2) NOT NULL COMMENT 'tiponomina->CodTipoNom',
  `TotalDiasPeriodo` int(3) NOT NULL DEFAULT '0',
  `TotalDiasPago` int(3) NOT NULL DEFAULT '0',
  `TotalFeriados` int(3) NOT NULL DEFAULT '0',
  `ValorDia` decimal(11,2) NOT NULL DEFAULT '0.00',
  `HorasDiaria` decimal(11,2) NOT NULL DEFAULT '0.00',
  `HorasSemanal` decimal(11,2) NOT NULL DEFAULT '0.00',
  `ValorSemanal` decimal(11,2) NOT NULL DEFAULT '0.00',
  `ValorMes` decimal(11,2) NOT NULL DEFAULT '0.00',
  `Estado` varchar(1) NOT NULL DEFAULT 'A' COMMENT 'A:ABIERTO; C:CERRADO;',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Anio`,`CodOrganismo`,`CodBonoAlim`),
  UNIQUE KEY `UK_rh_bonoalimentacion_1` (`Periodo`,`CodTipoNom`,`CodOrganismo`,`CodBonoAlim`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=128;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_bonoalimentaciondet`
--

CREATE TABLE IF NOT EXISTS `rh_bonoalimentaciondet` (
  `Anio` year(4) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `CodBonoAlim` varchar(3) NOT NULL,
  `CodPersona` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `Dia1` varchar(1) NOT NULL DEFAULT 'X',
  `Dia2` varchar(1) NOT NULL DEFAULT 'X',
  `Dia3` varchar(1) NOT NULL DEFAULT 'X',
  `Dia4` varchar(1) NOT NULL DEFAULT 'X',
  `Dia5` varchar(1) NOT NULL DEFAULT 'X',
  `Dia6` varchar(1) NOT NULL DEFAULT 'X',
  `Dia7` varchar(1) NOT NULL DEFAULT 'X',
  `Dia8` varchar(1) NOT NULL DEFAULT 'X',
  `Dia9` varchar(1) NOT NULL DEFAULT 'X',
  `Dia10` varchar(1) NOT NULL DEFAULT 'X',
  `Dia11` varchar(1) NOT NULL DEFAULT 'X',
  `Dia12` varchar(1) NOT NULL DEFAULT 'X',
  `Dia13` varchar(1) NOT NULL DEFAULT 'X',
  `Dia14` varchar(1) NOT NULL DEFAULT 'X',
  `Dia15` varchar(1) NOT NULL DEFAULT 'X',
  `Dia16` varchar(1) NOT NULL DEFAULT 'X',
  `Dia17` varchar(1) NOT NULL DEFAULT 'X',
  `Dia18` varchar(1) NOT NULL DEFAULT 'X',
  `Dia19` varchar(1) NOT NULL DEFAULT 'X',
  `Dia20` varchar(1) NOT NULL DEFAULT 'X',
  `Dia21` varchar(1) NOT NULL DEFAULT 'X',
  `Dia22` varchar(1) NOT NULL DEFAULT 'X',
  `Dia23` varchar(1) NOT NULL DEFAULT 'X',
  `Dia24` varchar(1) NOT NULL DEFAULT 'X',
  `Dia25` varchar(1) NOT NULL DEFAULT 'X',
  `Dia26` varchar(1) NOT NULL DEFAULT 'X',
  `Dia27` varchar(1) NOT NULL DEFAULT 'X',
  `Dia28` varchar(1) NOT NULL DEFAULT 'X',
  `Dia29` varchar(1) NOT NULL DEFAULT 'X',
  `Dia30` varchar(1) NOT NULL DEFAULT 'X',
  `Dia31` varchar(1) NOT NULL DEFAULT 'X',
  `DiasPeriodo` int(3) NOT NULL DEFAULT '0',
  `DiasPago` int(3) NOT NULL DEFAULT '0',
  `DiasFeriados` int(3) NOT NULL DEFAULT '0',
  `DiasInactivos` int(3) NOT NULL DEFAULT '0',
  `DiasDescuento` int(3) NOT NULL DEFAULT '0',
  `ValorPagar` decimal(11,2) NOT NULL DEFAULT '0.00',
  `ValorDescuento` decimal(11,2) NOT NULL DEFAULT '0.00',
  `TotalPagar` decimal(11,2) NOT NULL DEFAULT '0.00',
  `Estado` varchar(1) NOT NULL DEFAULT 'A' COMMENT 'A:ACTIVO; I:INACTIVO;',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Anio`,`CodOrganismo`,`CodBonoAlim`,`CodPersona`),
  KEY `FK_rh_bonoalimentaciondet_1` (`Anio`,`CodOrganismo`,`CodBonoAlim`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=134;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_bonoalimentacioneventos`
--

CREATE TABLE IF NOT EXISTS `rh_bonoalimentacioneventos` (
  `Anio` year(4) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `CodBonoAlim` varchar(3) NOT NULL,
  `CodPersona` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `Secuencia` int(3) NOT NULL DEFAULT '0',
  `Fecha` date NOT NULL,
  `HoraSalida` varchar(8) DEFAULT NULL,
  `HoraEntrada` varchar(8) DEFAULT NULL,
  `TotalHoras` varchar(10) NOT NULL,
  `TipoEvento` varchar(2) NOT NULL COMMENT 'miscelaneo->TIPOFALTAS',
  `Motivo` varchar(2) NOT NULL COMMENT 'miscelaneo->PERMISOS',
  `Observaciones` text NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Anio`,`CodOrganismo`,`CodBonoAlim`,`CodPersona`,`Secuencia`),
  KEY `FK_rh_bonoalimentacioneventos_1` (`Anio`,`CodOrganismo`,`CodBonoAlim`,`CodPersona`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=101;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_bonoalimentacionlaborados`
--

CREATE TABLE IF NOT EXISTS `rh_bonoalimentacionlaborados` (
  `Anio` year(4) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL,
  `CodBonoAlim` varchar(3) NOT NULL,
  `CodPersona` varchar(6) NOT NULL,
  `Secuencia` varchar(3) NOT NULL,
  `Fecha` date NOT NULL,
  `Observaciones` text,
  `UltimaFecha` datetime NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  PRIMARY KEY (`Anio`,`CodOrganismo`,`CodBonoAlim`,`CodPersona`,`Secuencia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_capacitacion`
--

CREATE TABLE IF NOT EXISTS `rh_capacitacion` (
  `Anio` year(4) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL,
  `Capacitacion` varchar(6) NOT NULL,
  `CodCurso` varchar(4) NOT NULL,
  `CodCentroEstudio` varchar(4) NOT NULL,
  `Vacantes` int(3) unsigned NOT NULL DEFAULT '0',
  `Estado` varchar(2) NOT NULL DEFAULT 'PE' COMMENT 'PE:PENDIENTE; AP:APROBADO; IN:INICIADO; TE:TERMINADO;',
  `FechaDesde` date NOT NULL,
  `FechaHasta` date NOT NULL,
  `Participantes` int(10) unsigned NOT NULL DEFAULT '0',
  `FlagHorarioIndividual` varchar(1) NOT NULL DEFAULT 'N',
  `Aula` varchar(5) NOT NULL,
  `TipoCapacitacion` varchar(1) NOT NULL,
  `Expositor` varchar(50) NOT NULL,
  `FlagLogistica` varchar(1) NOT NULL DEFAULT 'N',
  `CodCiudad` varchar(4) NOT NULL,
  `CostoEstimado` double(11,2) NOT NULL DEFAULT '0.00',
  `Solicitante` varchar(6) NOT NULL,
  `TelefonoContacto` varchar(15) NOT NULL,
  `MontoAsumido` double(11,2) NOT NULL DEFAULT '0.00',
  `Modalidad` varchar(2) NOT NULL,
  `TipoCurso` varchar(2) NOT NULL,
  `Fundamentacion1` mediumtext NOT NULL,
  `Fundamentacion2` mediumtext NOT NULL,
  `Fundamentacion3` mediumtext NOT NULL,
  `Fundamentacion4` mediumtext NOT NULL,
  `Fundamentacion5` mediumtext NOT NULL,
  `Fundamentacion6` mediumtext NOT NULL,
  `Fundamentacion7` mediumtext NOT NULL,
  `FlagCostos` varchar(1) NOT NULL DEFAULT 'N',
  `Observaciones` text NOT NULL,
  `Periodo` varchar(7) NOT NULL,
  `CreadoPor` varchar(6) DEFAULT NULL,
  `FechaCreado` date DEFAULT NULL,
  `AprobadoPor` varchar(6) DEFAULT NULL,
  `FechaAprobado` date DEFAULT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Anio`,`CodOrganismo`,`Capacitacion`),
  KEY `FK_rh_capacitacion_1` (`CodOrganismo`),
  KEY `FK_rh_capacitacion_2` (`CodCentroEstudio`),
  KEY `FK_rh_capacitacion_3` (`CodCurso`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 8192 kB';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_capacitacion_empleados`
--

CREATE TABLE IF NOT EXISTS `rh_capacitacion_empleados` (
  `Anio` year(4) NOT NULL,
  `Capacitacion` varchar(6) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL,
  `Secuencia` int(6) NOT NULL,
  `CodPersona` varchar(6) NOT NULL,
  `Solicitud` varchar(10) NOT NULL,
  `CodDependencia` varchar(4) NOT NULL,
  `CodDivision` varchar(4) NOT NULL,
  `NroConstancia` varchar(20) NOT NULL,
  `NroAsistencias` int(4) NOT NULL,
  `HoraAsistencias` int(4) NOT NULL,
  `Calificacion` varchar(20) NOT NULL,
  `CostoIndividual` decimal(11,2) NOT NULL,
  `DiasAsistidos` int(4) NOT NULL,
  `NroPeriodo` int(4) NOT NULL,
  `Nota` int(4) NOT NULL,
  `ImporteGastos` decimal(11,2) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Capacitacion`,`CodOrganismo`,`Secuencia`,`Anio`),
  UNIQUE KEY `Index_7` (`Capacitacion`,`CodOrganismo`,`CodPersona`),
  KEY `Index_2` (`CodPersona`),
  KEY `Index_3` (`CodOrganismo`),
  KEY `Index_4` (`Capacitacion`),
  KEY `Index_5` (`CodDependencia`),
  KEY `Index_6` (`CodDivision`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 8192 kB; InnoDB free: 8192 kB; InnoDB free: 819';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_capacitacion_gastos`
--

CREATE TABLE IF NOT EXISTS `rh_capacitacion_gastos` (
  `Anio` year(4) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL,
  `Capacitacion` varchar(6) NOT NULL,
  `Secuencia` int(2) NOT NULL,
  `Numero` varchar(15) NOT NULL DEFAULT '',
  `Fecha` date NOT NULL,
  `SubTotal` decimal(11,2) NOT NULL,
  `Impuestos` decimal(11,2) NOT NULL,
  `Total` decimal(11,2) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Capacitacion`,`CodOrganismo`,`Anio`,`Secuencia`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_capacitacion_hora`
--

CREATE TABLE IF NOT EXISTS `rh_capacitacion_hora` (
  `Anio` year(4) NOT NULL,
  `Capacitacion` varchar(6) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL,
  `Secuencia` varchar(6) NOT NULL,
  `CodPersona` varchar(6) NOT NULL,
  `Estado` varchar(1) NOT NULL,
  `PeriodoInicio` varchar(6) NOT NULL,
  `PeriodoFin` varchar(6) NOT NULL,
  `FechaDesde` date NOT NULL,
  `FechaHasta` date NOT NULL,
  `Lunes` varchar(1) NOT NULL,
  `HoraInicioLunes` varchar(8) NOT NULL,
  `HoraFinLunes` varchar(8) NOT NULL,
  `Martes` varchar(1) NOT NULL,
  `HoraInicioMartes` varchar(8) NOT NULL,
  `HoraFinMartes` varchar(8) NOT NULL,
  `Miercoles` varchar(1) NOT NULL,
  `HoraInicioMiercoles` varchar(8) NOT NULL,
  `HoraFinMiercoles` varchar(8) NOT NULL,
  `Jueves` varchar(1) NOT NULL,
  `HoraInicioJueves` varchar(8) NOT NULL,
  `HoraFinJueves` varchar(8) NOT NULL,
  `Viernes` varchar(1) NOT NULL,
  `HoraInicioViernes` varchar(8) NOT NULL,
  `HoraFinViernes` varchar(8) NOT NULL,
  `Sabado` varchar(1) NOT NULL,
  `HoraInicioSabado` varchar(8) NOT NULL,
  `HoraFinSabado` varchar(8) NOT NULL,
  `Domingo` varchar(1) NOT NULL,
  `HoraInicioDomingo` varchar(8) NOT NULL,
  `HoraFinDomingo` varchar(8) NOT NULL,
  `TotalDias` int(4) NOT NULL,
  `TotalHoras` varchar(10) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Capacitacion`,`CodOrganismo`,`Secuencia`,`Anio`,`CodPersona`),
  KEY `FK_rh_capacitacion_hora_1` (`Secuencia`),
  KEY `FK_rh_capacitacion_hora_2` (`Capacitacion`,`CodOrganismo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_cargafamiliar`
--

CREATE TABLE IF NOT EXISTS `rh_cargafamiliar` (
  `CodSecuencia` int(4) NOT NULL,
  `CodPersona` varchar(6) NOT NULL,
  `Parentesco` varchar(2) NOT NULL,
  `ApellidosCarga` varchar(50) NOT NULL DEFAULT '',
  `NombresCarga` varchar(50) NOT NULL DEFAULT '',
  `DireccionFam` text NOT NULL,
  `FechaNacimiento` date NOT NULL,
  `TipoDocumento` varchar(2) NOT NULL,
  `Ndocumento` varchar(20) NOT NULL,
  `Telefono` varchar(15) DEFAULT NULL,
  `Celular` varchar(15) DEFAULT NULL,
  `Sexo` char(1) NOT NULL,
  `GrupoSanguineo` varchar(2) DEFAULT NULL,
  `Afiliado` char(1) NOT NULL,
  `CodGradoInstruccion` char(3) NOT NULL,
  `Estado` char(1) NOT NULL,
  `MotivoBaja` char(2) NOT NULL,
  `FechaBaja` date NOT NULL,
  `TipoEducacion` char(2) NOT NULL,
  `CodCentroEstudio` varchar(3) NOT NULL,
  `FlagTrabaja` char(1) NOT NULL,
  `Empresa` varchar(100) NOT NULL,
  `DireccionEmpresa` text NOT NULL,
  `TiempoServicio` int(2) NOT NULL,
  `SueldoMensual` decimal(11,2) NOT NULL,
  `Comentarios` varchar(255) NOT NULL,
  `FlagEstudia` varchar(1) NOT NULL DEFAULT 'N',
  `EstadoCivil` varchar(2) NOT NULL COMMENT 'miscelaneo->EDOCIVIL',
  `FlagDiscapacidad` varchar(1) NOT NULL DEFAULT 'N',
  `Foto` varchar(50) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodPersona`,`CodSecuencia`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 8192 kB; (`CodPersona`) REFER `siaceda/mastpers';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_cargoambiente`
--

CREATE TABLE IF NOT EXISTS `rh_cargoambiente` (
  `CodCargo` varchar(4) NOT NULL,
  `Secuencia` int(3) NOT NULL,
  `Ambiente` mediumtext NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodCargo`,`Secuencia`),
  KEY `FK_rh_cargoambiente_1` (`CodCargo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_cargocompetencia`
--

CREATE TABLE IF NOT EXISTS `rh_cargocompetencia` (
  `Competencia` int(3) NOT NULL,
  `CodCargo` varchar(4) NOT NULL,
  `Calificacion` varchar(6) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`Competencia`,`CodCargo`),
  KEY `FK_rh_cargocompetencia_1` (`CodCargo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_cargocursos`
--

CREATE TABLE IF NOT EXISTS `rh_cargocursos` (
  `CodCargo` varchar(4) NOT NULL,
  `Secuencia` varchar(6) NOT NULL,
  `Curso` varchar(4) NOT NULL DEFAULT '',
  `TotalHoras` int(4) NOT NULL,
  `AniosVigencia` int(2) NOT NULL,
  `Observaciones` varchar(255) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`Secuencia`,`CodCargo`),
  KEY `CodCargo` (`CodCargo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_cargoesfuerzo`
--

CREATE TABLE IF NOT EXISTS `rh_cargoesfuerzo` (
  `CodCargo` varchar(4) NOT NULL,
  `Secuencia` int(3) NOT NULL,
  `Esfuerzo` mediumtext NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodCargo`,`Secuencia`),
  KEY `FK_rh_cargoesfuerzo_1` (`CodCargo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_cargoevaluacion`
--

CREATE TABLE IF NOT EXISTS `rh_cargoevaluacion` (
  `CodCargo` varchar(4) NOT NULL,
  `Secuencia` int(3) NOT NULL,
  `Etapa` varchar(3) NOT NULL,
  `Evaluacion` int(4) unsigned NOT NULL,
  `Factor` int(3) NOT NULL,
  `Estado` varchar(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodCargo`,`Secuencia`),
  UNIQUE KEY `Index_3` (`CodCargo`,`Etapa`),
  UNIQUE KEY `Index_4` (`CodCargo`,`Evaluacion`),
  KEY `FK_rh_cargoevaluacion_1` (`Evaluacion`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_cargoexperiencia`
--

CREATE TABLE IF NOT EXISTS `rh_cargoexperiencia` (
  `CodCargo` varchar(4) NOT NULL,
  `Secuencia` varchar(6) NOT NULL,
  `AreaExperiencia` varchar(2) NOT NULL,
  `CargoExperiencia` varchar(4) DEFAULT NULL COMMENT 'rh_puestos->CodCargo',
  `FlagNecesario` varchar(1) NOT NULL,
  `Meses` int(11) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`Secuencia`),
  KEY `CodCargo` (`CodCargo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_cargoformacion`
--

CREATE TABLE IF NOT EXISTS `rh_cargoformacion` (
  `CodCargo` varchar(4) NOT NULL,
  `Secuencia` varchar(6) NOT NULL,
  `CodGradoInstruccion` char(3) NOT NULL,
  `Area` varchar(2) NOT NULL,
  `CodProfesion` varchar(6) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`Secuencia`),
  KEY `CodCargo` (`CodCargo`),
  KEY `CodGradoInstruccion` (`CodGradoInstruccion`),
  KEY `CodProfesion` (`CodProfesion`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_cargofunciones`
--

CREATE TABLE IF NOT EXISTS `rh_cargofunciones` (
  `CodCargo` varchar(4) NOT NULL,
  `CodFuncion` int(6) unsigned NOT NULL DEFAULT '0',
  `Funcion` varchar(2) NOT NULL,
  `Descripcion` longtext NOT NULL,
  `Estado` char(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodCargo`,`CodFuncion`),
  KEY `CodCargo` (`CodCargo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_cargohabilidades`
--

CREATE TABLE IF NOT EXISTS `rh_cargohabilidades` (
  `CodCargo` varchar(4) NOT NULL,
  `Secuencia` int(3) NOT NULL,
  `Tipo` varchar(1) NOT NULL,
  `Descripcion` mediumtext NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodCargo`,`Secuencia`),
  KEY `FK_rh_cargohabilidades_1` (`CodCargo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_cargoidioma`
--

CREATE TABLE IF NOT EXISTS `rh_cargoidioma` (
  `CodCargo` varchar(4) NOT NULL,
  `CodIdioma` varchar(3) NOT NULL,
  `NivelLectura` varchar(2) NOT NULL,
  `NivelOral` varchar(2) NOT NULL,
  `NivelEscritura` varchar(2) NOT NULL,
  `NivelGeneral` varchar(2) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodCargo`,`CodIdioma`),
  KEY `CodCargo` (`CodCargo`),
  KEY `CodIdioma` (`CodIdioma`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_cargoinformat`
--

CREATE TABLE IF NOT EXISTS `rh_cargoinformat` (
  `CodCargo` varchar(4) NOT NULL,
  `Informatica` varchar(2) NOT NULL,
  `Nivel` varchar(2) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodCargo`,`Informatica`),
  KEY `CodCargo` (`CodCargo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_cargometas`
--

CREATE TABLE IF NOT EXISTS `rh_cargometas` (
  `CodCargo` varchar(4) NOT NULL,
  `Secuencia` varchar(6) NOT NULL,
  `Descripcion` varchar(50) NOT NULL DEFAULT '',
  `FactorParticipacion` int(8) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`Secuencia`,`CodCargo`),
  KEY `CodCargo` (`CodCargo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_cargorelaciones`
--

CREATE TABLE IF NOT EXISTS `rh_cargorelaciones` (
  `CodCargo` varchar(4) NOT NULL,
  `Secuencia` int(6) unsigned NOT NULL DEFAULT '0',
  `TipoRelacion` char(1) NOT NULL,
  `EnteRelacionado` varchar(100) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`Secuencia`),
  KEY `CodCargo` (`CodCargo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_cargoreporta`
--

CREATE TABLE IF NOT EXISTS `rh_cargoreporta` (
  `CodCargo` varchar(4) NOT NULL,
  `Secuencia` int(6) unsigned NOT NULL DEFAULT '0',
  `CargoReporta` varchar(14) NOT NULL DEFAULT '',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`Secuencia`),
  KEY `CodCargo` (`CodCargo`),
  KEY `CargoReporta` (`CargoReporta`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_cargoriesgo`
--

CREATE TABLE IF NOT EXISTS `rh_cargoriesgo` (
  `CodCargo` varchar(4) NOT NULL,
  `Secuencia` varchar(6) NOT NULL,
  `TipoRiesgo` varchar(2) NOT NULL,
  `Riesgo` varchar(255) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`Secuencia`),
  KEY `CodCargo` (`CodCargo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_cargosub`
--

CREATE TABLE IF NOT EXISTS `rh_cargosub` (
  `CodCargo` varchar(4) NOT NULL,
  `CargoSubordinado` varchar(4) NOT NULL,
  `Cantidad` int(4) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodCargo`,`CargoSubordinado`),
  KEY `CodCargo` (`CodCargo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_carreras`
--

CREATE TABLE IF NOT EXISTS `rh_carreras` (
  `Anio` year(4) NOT NULL,
  `CodPersona` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `FechaIngreso` date NOT NULL COMMENT 'mastempleado->Fingreso',
  `CodCargo` varchar(4) NOT NULL COMMENT 'rh_puestos->CodCargo',
  `Grado` varchar(2) NOT NULL COMMENT 'rh_puestos->Grado',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Anio`,`CodPersona`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_carreras_academico`
--

CREATE TABLE IF NOT EXISTS `rh_carreras_academico` (
  `Anio` year(4) NOT NULL,
  `CodPersona` varchar(6) NOT NULL,
  `Secuencia` int(11) NOT NULL,
  `CodGradoInstruccion` varchar(3) DEFAULT NULL COMMENT 'rh_gradoinstruccion->CodGradoInstruccion',
  `Area` varchar(2) DEFAULT NULL COMMENT 'rh_gradoinstruccion->Area',
  `CodProfesion` varchar(6) DEFAULT NULL COMMENT 'rh_profesiones->CodProfesion',
  `Nivel` varchar(3) DEFAULT NULL,
  `CodCentroEstudio` varchar(3) DEFAULT NULL COMMENT 'rh_centrosestudios->CodCentroEstudio',
  `FechaGraduacion` date NOT NULL,
  `UltimoUsuario` varchar(30) DEFAULT NULL,
  `UltimaFecha` datetime DEFAULT NULL,
  PRIMARY KEY (`Anio`,`CodPersona`,`Secuencia`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_carreras_competencias`
--

CREATE TABLE IF NOT EXISTS `rh_carreras_competencias` (
  `Anio` year(4) NOT NULL,
  `CodPersona` varchar(6) NOT NULL,
  `Secuencia` int(11) NOT NULL,
  `Competencia` int(3) NOT NULL,
  `FlagAdquiridas` varchar(1) NOT NULL DEFAULT 'N',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Anio`,`CodPersona`,`Secuencia`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_carreras_cursos`
--

CREATE TABLE IF NOT EXISTS `rh_carreras_cursos` (
  `Anio` year(4) NOT NULL,
  `CodPersona` varchar(6) NOT NULL,
  `Secuencia` int(11) NOT NULL,
  `CodCurso` varchar(3) NOT NULL COMMENT 'rh_cursos->CodCurso',
  `TipoCurso` varchar(2) NOT NULL,
  `CodCentroEstudio` varchar(3) NOT NULL COMMENT 'rh_centrosestudios->CodCentroEstudio',
  `FechaCulminacion` date NOT NULL,
  `TotalHoras` int(4) DEFAULT NULL,
  `AniosVigencia` int(2) DEFAULT NULL,
  `FlagInstitucional` varchar(1) NOT NULL DEFAULT 'N',
  `FlagPago` varchar(1) NOT NULL DEFAULT 'N',
  `FlagArea` varchar(1) NOT NULL DEFAULT 'N',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Anio`,`CodPersona`,`Secuencia`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_centrosestudios`
--

CREATE TABLE IF NOT EXISTS `rh_centrosestudios` (
  `CodCentroEstudio` varchar(3) NOT NULL,
  `Descripcion` varchar(255) NOT NULL,
  `Ubicacion` varchar(70) NOT NULL,
  `FlagEstudio` varchar(1) NOT NULL,
  `FlagCurso` varchar(1) NOT NULL,
  `Estado` varchar(1) NOT NULL DEFAULT 'A',
  `UltimoUsuario` varchar(30) NOT NULL COMMENT 'A:ACTIVO; I:INACTIVO;',
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodCentroEstudio`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_contratos`
--

CREATE TABLE IF NOT EXISTS `rh_contratos` (
  `CodPersona` varchar(6) NOT NULL,
  `Secuencia` varchar(6) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL,
  `TipoContrato` char(2) NOT NULL,
  `CodFormato` varchar(4) NOT NULL,
  `FechaDesde` date NOT NULL,
  `FechaHasta` date NOT NULL,
  `Estado` varchar(2) NOT NULL,
  `Comentarios` varchar(300) NOT NULL,
  `FlagFirma` char(1) NOT NULL,
  `FechaFirma` date NOT NULL,
  `Contrato` varchar(10) NOT NULL,
  `FechaContrato` date NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodPersona`,`CodOrganismo`,`FechaDesde`,`FechaHasta`),
  KEY `rh_contratos_ibfk_1` (`TipoContrato`),
  KEY `rh_contratos_ibfk_2` (`CodOrganismo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_controlasistencia`
--

CREATE TABLE IF NOT EXISTS `rh_controlasistencia` (
  `CodEvento` int(11) NOT NULL AUTO_INCREMENT,
  `CodPersona` varchar(6) NOT NULL,
  `Fecha` varchar(10) NOT NULL,
  `Hora` varchar(13) NOT NULL,
  `FechaFormat` date NOT NULL,
  `HoraFormat` time NOT NULL,
  `Event_Puerta` varchar(11) NOT NULL,
  `Estado` varchar(1) NOT NULL COMMENT 'S:SIN PROCESAR; P:PROCESADO',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodEvento`,`CodPersona`),
  KEY `IK_rh_controlasistencia_1` (`FechaFormat`,`HoraFormat`,`Event_Puerta`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26428 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_cooperativa`
--

CREATE TABLE IF NOT EXISTS `rh_cooperativa` (
  `CodCooperativa` varchar(3) NOT NULL,
  `NombreCoop` varchar(100) NOT NULL,
  `ObjetoCoop` varchar(255) NOT NULL,
  `Estado` char(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodCooperativa`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_cursos`
--

CREATE TABLE IF NOT EXISTS `rh_cursos` (
  `CodCurso` varchar(4) NOT NULL,
  `AreaCurso` varchar(2) NOT NULL,
  `Descripcion` varchar(255) NOT NULL,
  `Estado` varchar(1) NOT NULL DEFAULT 'A' COMMENT 'A:ACTIVO; I:INACTIVO;',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodCurso`),
  UNIQUE KEY `uk_1` (`AreaCurso`,`Descripcion`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_documentos_historia`
--

CREATE TABLE IF NOT EXISTS `rh_documentos_historia` (
  `CodDocumento` varchar(2) NOT NULL,
  `CodPersona` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `Secuencia` int(3) unsigned NOT NULL,
  `Responsable` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `Estado` varchar(1) NOT NULL DEFAULT 'E' COMMENT 'E:ENTREGADO; D:DEVUELTO; P:PERDIDO;',
  `FechaEntrega` date NOT NULL,
  `FechaDevuelto` date NOT NULL,
  `ObsEntrega` text NOT NULL,
  `ObsDevuelto` text NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodDocumento`,`CodPersona`,`Secuencia`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_empleadonivelacion`
--

CREATE TABLE IF NOT EXISTS `rh_empleadonivelacion` (
  `CodPersona` varchar(6) NOT NULL,
  `Secuencia` int(6) NOT NULL,
  `Fecha` date NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL,
  `CodDependencia` varchar(4) NOT NULL,
  `CodCargo` varchar(4) NOT NULL,
  `CodTipoNom` varchar(2) NOT NULL,
  `TipoAccion` varchar(2) NOT NULL,
  `Motivo` varchar(255) NOT NULL,
  `Responsable` varchar(6) NOT NULL,
  `Documento` varchar(255) NOT NULL,
  `Estado` varchar(1) NOT NULL,
  `FechaHasta` date NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodPersona`,`Secuencia`),
  KEY `FK_rh_empleadonivelacion_1` (`CodOrganismo`),
  KEY `FK_rh_empleadonivelacion_10` (`CodDependencia`,`CodOrganismo`),
  KEY `FK_rh_empleadonivelacion_2` (`CodCargo`),
  KEY `FK_rh_empleadonivelacion_3` (`CodTipoNom`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 4096 kB; (`CodOrganismo`) REFER `siaceda/mastor';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_empleadonivelacionhistorial`
--

CREATE TABLE IF NOT EXISTS `rh_empleadonivelacionhistorial` (
  `CodPersona` varchar(6) NOT NULL,
  `Secuencia` int(6) NOT NULL,
  `Fecha` date NOT NULL,
  `Organismo` varchar(255) NOT NULL,
  `Dependencia` varchar(255) NOT NULL,
  `Cargo` varchar(255) NOT NULL DEFAULT '',
  `NivelSalarial` double(11,2) NOT NULL DEFAULT '0.00',
  `CategoriaCargo` varchar(100) NOT NULL DEFAULT '',
  `TipoNomina` varchar(100) NOT NULL,
  `TipoAccion` varchar(100) NOT NULL,
  `Motivo` varchar(255) NOT NULL,
  `Responsable` varchar(100) NOT NULL,
  `Documento` varchar(255) NOT NULL,
  `Estado` varchar(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodPersona`,`Secuencia`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_empleado_antecedentes`
--

CREATE TABLE IF NOT EXISTS `rh_empleado_antecedentes` (
  `CodProceso` varchar(4) NOT NULL COMMENT 'rh_proceso_jubilacion->CodProceso',
  `Secuencia` int(3) unsigned NOT NULL,
  `CodPersona` varchar(6) NOT NULL,
  `Organismo` varchar(100) DEFAULT NULL,
  `FIngreso` date DEFAULT NULL,
  `FEgreso` date DEFAULT NULL,
  `TipoProceso` varchar(1) NOT NULL DEFAULT 'J' COMMENT 'J:JUBILACION; P:PENSION;',
  `Anios` int(3) unsigned DEFAULT NULL,
  `Meses` int(3) unsigned DEFAULT NULL,
  `Dias` int(3) unsigned DEFAULT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  `rh_empleado_antecedentescol` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`CodProceso`,`Secuencia`,`TipoProceso`),
  KEY `rh_emp_ant_fk_constraint` (`CodPersona`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_empleado_cursos`
--

CREATE TABLE IF NOT EXISTS `rh_empleado_cursos` (
  `CodPersona` varchar(6) NOT NULL,
  `Secuencia` int(6) NOT NULL,
  `CodCurso` varchar(4) NOT NULL,
  `TipoCurso` varchar(2) NOT NULL,
  `CodCentroEstudio` varchar(3) NOT NULL,
  `FechaDesde` date NOT NULL,
  `FechaHasta` date NOT NULL,
  `FechaCulminacion` varchar(7) NOT NULL,
  `TotalHoras` int(4) NOT NULL,
  `AniosVigencia` int(2) NOT NULL,
  `Observaciones` varchar(255) NOT NULL,
  `FlagInstitucional` char(1) NOT NULL,
  `FlagPago` varchar(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  `FlagArea` varchar(1) NOT NULL DEFAULT 'N',
  PRIMARY KEY (`CodPersona`,`Secuencia`),
  KEY `FK_rh_empleado_cursos_1` (`CodCurso`),
  KEY `FK_rh_empleado_cursos_2` (`CodCentroEstudio`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_empleado_desempenio`
--

CREATE TABLE IF NOT EXISTS `rh_empleado_desempenio` (
  `CodOrganismo` varchar(4) NOT NULL,
  `Periodo` varchar(7) NOT NULL,
  `Secuencia` int(4) NOT NULL,
  `CodPersona` varchar(6) NOT NULL,
  `Evaluador` varchar(6) NOT NULL,
  `SecuenciaDesempenio` int(4) NOT NULL,
  `Descripcion` varchar(255) NOT NULL,
  `Tipo` varchar(1) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodOrganismo`,`Periodo`,`Secuencia`,`CodPersona`,`Evaluador`,`SecuenciaDesempenio`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_empleado_documentos`
--

CREATE TABLE IF NOT EXISTS `rh_empleado_documentos` (
  `CodDocumento` varchar(2) NOT NULL,
  `CodPersona` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `Documento` varchar(2) NOT NULL COMMENT 'miscelaneo->DOCUMENTOS',
  `FlagPresento` varchar(1) NOT NULL DEFAULT 'N',
  `FechaPresento` date NOT NULL,
  `FechaVence` date NOT NULL,
  `FlagCarga` varchar(1) NOT NULL DEFAULT 'N',
  `CargaFamiliar` int(4) unsigned NOT NULL,
  `Observaciones` text NOT NULL,
  `Ruta` text NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodDocumento`,`CodPersona`),
  KEY `FK_rh_empleado_documentos_1` (`CodPersona`),
  KEY `Index_3` (`CargaFamiliar`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_empleado_evaluacion`
--

CREATE TABLE IF NOT EXISTS `rh_empleado_evaluacion` (
  `CodOrganismo` varchar(4) NOT NULL,
  `Periodo` varchar(7) NOT NULL,
  `Secuencia` int(4) NOT NULL,
  `CodPersona` varchar(6) NOT NULL,
  `Evaluador` varchar(6) NOT NULL,
  `SecuenciaDesempenio` int(4) NOT NULL,
  `Plantilla` int(3) NOT NULL,
  `Competencia` int(3) NOT NULL,
  `Calificacion` decimal(11,2) NOT NULL,
  `Peso` decimal(11,2) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodOrganismo`,`Periodo`,`Secuencia`,`CodPersona`,`Evaluador`,`SecuenciaDesempenio`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_empleado_experiencia`
--

CREATE TABLE IF NOT EXISTS `rh_empleado_experiencia` (
  `CodPersona` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `Secuencia` int(6) NOT NULL,
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
  PRIMARY KEY (`CodPersona`,`Secuencia`),
  KEY `rh_empleado_experiencia_ibfk_1` (`CodPersona`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_empleado_funciones`
--

CREATE TABLE IF NOT EXISTS `rh_empleado_funciones` (
  `CodOrganismo` varchar(4) NOT NULL,
  `Periodo` varchar(7) NOT NULL,
  `Secuencia` int(4) NOT NULL,
  `CodPersona` varchar(6) NOT NULL,
  `Evaluador` varchar(6) NOT NULL,
  `SecuenciaDesempenio` int(4) NOT NULL,
  `Funcion` varchar(255) NOT NULL DEFAULT '',
  `Calificacion` int(2) NOT NULL,
  `Peso` int(2) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodOrganismo`,`Periodo`,`Secuencia`,`CodPersona`,`Evaluador`,`SecuenciaDesempenio`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_empleado_idioma`
--

CREATE TABLE IF NOT EXISTS `rh_empleado_idioma` (
  `CodPersona` varchar(6) NOT NULL,
  `CodIdioma` varchar(3) NOT NULL,
  `NivelLectura` varchar(2) NOT NULL,
  `NivelOral` varchar(2) NOT NULL,
  `NivelEscritura` varchar(2) NOT NULL,
  `NivelGeneral` varchar(2) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodPersona`,`CodIdioma`),
  KEY `CodPersona` (`CodPersona`),
  KEY `CodIdioma` (`CodIdioma`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_empleado_instruccion`
--

CREATE TABLE IF NOT EXISTS `rh_empleado_instruccion` (
  `CodPersona` varchar(6) NOT NULL,
  `Secuencia` int(6) NOT NULL,
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
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodPersona`,`Secuencia`),
  KEY `rh_empleado_instruccion_ibfk_1` (`CodPersona`),
  KEY `rh_empleado_instruccion_ibfk_2` (`CodCentroEstudio`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_empleado_metas`
--

CREATE TABLE IF NOT EXISTS `rh_empleado_metas` (
  `CodOrganismo` varchar(4) NOT NULL,
  `Periodo` varchar(7) NOT NULL,
  `Secuencia` int(4) NOT NULL,
  `CodPersona` varchar(6) NOT NULL,
  `Evaluador` varchar(6) NOT NULL,
  `SecuenciaDesempenio` int(4) NOT NULL,
  `Descripcion` text NOT NULL,
  `Comentarios` text NOT NULL,
  `PeriodoMetas` varchar(7) NOT NULL,
  `Desde` date NOT NULL,
  `Hasta` date NOT NULL,
  `Calificacion` decimal(11,2) NOT NULL,
  `Peso` decimal(11,2) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodOrganismo`,`Periodo`,`Secuencia`,`CodPersona`,`Evaluador`,`SecuenciaDesempenio`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_empleado_necesidad`
--

CREATE TABLE IF NOT EXISTS `rh_empleado_necesidad` (
  `CodOrganismo` varchar(4) NOT NULL,
  `Periodo` varchar(7) NOT NULL,
  `Secuencia` int(4) NOT NULL,
  `CodPersona` varchar(6) NOT NULL,
  `Evaluador` varchar(6) NOT NULL,
  `SecuenciaDesempenio` int(4) NOT NULL,
  `Necesidad` varchar(100) NOT NULL,
  `Prioridad` varchar(1) NOT NULL DEFAULT '' COMMENT 'N:NORMAL; U:URGENTE; M:MUY URGENTE',
  `CodCurso` varchar(4) NOT NULL,
  `Objetivo` varchar(100) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodOrganismo`,`Periodo`,`Secuencia`,`CodPersona`,`Evaluador`,`SecuenciaDesempenio`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_empleado_referencias`
--

CREATE TABLE IF NOT EXISTS `rh_empleado_referencias` (
  `CodPersona` varchar(6) NOT NULL,
  `Secuencia` int(6) unsigned NOT NULL,
  `Nombre` varchar(200) NOT NULL,
  `Empresa` varchar(200) NOT NULL,
  `Direccion` text NOT NULL,
  `Telefono` varchar(15) NOT NULL,
  `Cargo` varchar(100) NOT NULL,
  `Tipo` varchar(1) NOT NULL COMMENT 'P:PERSONAL; L:LABORAL;',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodPersona`,`Secuencia`),
  KEY `rh_referencias_ibfk_1` (`CodPersona`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_empleado_revision`
--

CREATE TABLE IF NOT EXISTS `rh_empleado_revision` (
  `CodOrganismo` varchar(4) NOT NULL,
  `Periodo` varchar(7) NOT NULL,
  `Secuencia` int(4) NOT NULL,
  `CodPersona` varchar(6) NOT NULL,
  `Evaluador` varchar(6) NOT NULL,
  `SecuenciaDesempenio` int(4) NOT NULL,
  `Fecha1` date NOT NULL,
  `Observacion1` tinytext NOT NULL,
  `Porcentaje1` decimal(11,2) NOT NULL,
  `Fecha2` date NOT NULL,
  `Observacion2` tinytext NOT NULL,
  `Porcentaje2` decimal(11,2) NOT NULL,
  `Fecha3` date NOT NULL,
  `Observacion3` tinytext NOT NULL,
  `Porcentaje3` decimal(11,2) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` date NOT NULL,
  PRIMARY KEY (`CodOrganismo`,`Periodo`,`Secuencia`,`CodPersona`,`Evaluador`,`SecuenciaDesempenio`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_encuestas`
--

CREATE TABLE IF NOT EXISTS `rh_encuestas` (
  `CodOrganismo` varchar(4) NOT NULL,
  `Secuencia` int(4) NOT NULL,
  `PeriodoContable` varchar(7) NOT NULL,
  `Titulo` varchar(255) NOT NULL,
  `Fecha` date NOT NULL DEFAULT '0000-00-00',
  `Observaciones` varchar(255) NOT NULL,
  `Muestra` int(4) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`Secuencia`),
  KEY `rh_secuencias_ibfk_1` (`CodOrganismo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_encuesta_detalle`
--
