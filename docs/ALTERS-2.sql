--	2012-10-01
--------------
CREATE TABLE `mastunidadtributaria` (
  `Anio` year NOT NULL,
  `Secuencia` int(10) NOT NULL,
  `Valor` decimal(11,2) NOT NULL DEFAULT '0.00',
  `PorcentajeAumento` decimal(11,2) NOT NULL DEFAULT '0.00',
  `Fecha` date NOT NULL default '0000-00-00',
  `GacetaOficial` varchar(20) NOT NULL,
  `ProvidenciaNro` varchar(20) NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Anio`,`Secuencia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
--
INSERT INTO `siaceda`.`seguridad_concepto` (`CodAplicacion`, `Grupo`, `Concepto`, `Descripcion`, `Estado`, `UltimoUsuario`, `UltimaFecha`) VALUES ('RH', '03', '03-0056', 'Unidad Tributaria', 'A', 'EJBOLIVAR', '2012-10-01');
INSERT INTO `siaceda`.`seguridad_concepto` (`CodAplicacion`, `Grupo`, `Concepto`, `Descripcion`, `Estado`, `UltimoUsuario`, `UltimaFecha`) VALUES ('NOMINA', '03', '03-0058', 'Unidad Tributaria', 'A', 'EJBOLIVAR', '2012-10-01');
INSERT INTO `siaceda`.`seguridad_concepto` (`CodAplicacion`, `Grupo`, `Concepto`, `Descripcion`, `Estado`, `UltimoUsuario`, `UltimaFecha`) VALUES ('LG', '03', '03-0039', 'Unidad Tributaria', 'A', 'EJBOLIVAR', '2012-10-01');
INSERT INTO `siaceda`.`seguridad_concepto` (`CodAplicacion`, `Grupo`, `Concepto`, `Descripcion`, `Estado`, `UltimoUsuario`, `UltimaFecha`) VALUES ('AP', '07', '07-0029', 'Unidad Tributaria', 'A', 'EJBOLIVAR', '2012-10-01');
INSERT INTO `siaceda`.`seguridad_concepto` (`CodAplicacion`, `Grupo`, `Concepto`, `Descripcion`, `Estado`, `UltimoUsuario`, `UltimaFecha`) VALUES ('PF', '05', '05-0024', 'Unidad Tributaria', 'A', 'EJBOLIVAR', '2012-10-01');
UPDATE `siaceda`.`seguridad_concepto` SET `Concepto`='03-0057' WHERE `CodAplicacion`='NOMINA' and`Grupo`='03' and`Concepto`='00-0057';
--
INSERT INTO `siaceda`.`seguridad_concepto` (`CodAplicacion`, `Grupo`, `Concepto`, `Descripcion`, `Estado`, `UltimoUsuario`, `UltimaFecha`) VALUES ('RH', '03', '03-0057', 'Horario Laboral', 'A', 'EJBOLIVAR', '2012-10-01');
INSERT INTO `siaceda`.`seguridad_concepto` (`CodAplicacion`, `Grupo`, `Concepto`, `Descripcion`, `Estado`, `UltimoUsuario`, `UltimaFecha`) VALUES ('NOMINA', '03', '03-0059', 'Horario Laboral', 'A', 'EJBOLIVAR', '2012-10-01');
--------------
--	2012-10-02
--------------
CREATE TABLE `rh_horariolaboral` (
  `CodHorario` varchar(3) NOT NULL,
  `Descripcion` varchar(50) NOT NULL,
  `FlagCorrido` varchar(1) NOT NULL default 'N',
  `Estado` varchar(1) NOT NULL default 'A' comment 'A:ACTIVO; I:INACTIVO;',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`CodHorario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
--
CREATE TABLE `rh_horariolaboraldet` (
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
  KEY `FK_rh_horariolaboraldet_1` (`CodHorario`),
  CONSTRAINT `FK_rh_horariolaboraldet_1` FOREIGN KEY (`CodHorario`) REFERENCES `rh_horariolaboral` (`CodHorario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
--------------
--	2012-10-03
--------------
INSERT INTO `siaceda`.`seguridad_concepto` (`CodAplicacion`, `Grupo`, `Concepto`, `Descripcion`, `Estado`, `UltimoUsuario`, `UltimaFecha`) VALUES ('RH', '05', '05-0003', 'Apertura de Periodo', 'A', 'EJBOLIVAR', '2012-10-03');
INSERT INTO `siaceda`.`seguridad_concepto` (`CodAplicacion`, `Grupo`, `Concepto`, `Descripcion`, `Estado`, `UltimoUsuario`, `UltimaFecha`) VALUES ('RH', '05', '05-0004', 'Registro de Eventos', 'A', 'EJBOLIVAR', '2012-10-03');
--
CREATE TABLE `rh_bonoalimentacion` (
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
  UNIQUE KEY `UK_rh_bonoalimentacion_1` (`Periodo`,`CodTipoNom`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
--
CREATE TABLE `rh_bonoalimentaciondet` (
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
  KEY `FK_rh_bonoalimentaciondet_1` (`Anio`,`CodOrganismo`,`CodBonoAlim`),
  CONSTRAINT `FK_rh_bonoalimentaciondet_1` FOREIGN KEY (`Anio`, `CodOrganismo`, `CodBonoAlim`) REFERENCES `rh_bonoalimentacion` (`Anio`, `CodOrganismo`, `CodBonoAlim`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
--------------
--	2012-10-04
--------------
INSERT INTO `siaceda`.`mastparametros` (`ParametroClave`, `TipoValor`, `ValorParam`, `Estado`, `DescripcionParam`, `CodOrganismo`, `CodAplicacion`, `UltimoUsuario`, `UltimaFecha`) VALUES ('UTPORC', 'N', '50', 'A', 'PORCENTAJE DE LA UNIDAD TRIBUTARIA A PAGAR POR BONO DE ALIMENTACION', '0001', 'RH', 'EJBOLIVAR', '2012-10-04');
--
INSERT INTO `siaceda`.`mastparametros` (`ParametroClave`, `TipoValor`, `ValorParam`, `Estado`, `DescripcionParam`, `CodOrganismo`, `CodAplicacion`, `UltimoUsuario`, `UltimaFecha`) VALUES ('UTANIO', 'N', '2011', 'A', 'INDICA EL AÑO A TOMAR EN CUENTA PARA OBTENER EL VALOR DE LA UNIDAD TRIBUTARIA', '0001', 'GE', 'EJBOLIVAR', '2012-10-04');
--
INSERT INTO `siaceda`.`mastunidadtributaria` (`Anio`, `Secuencia`, `Valor`, `Fecha`, `GacetaOficial`) VALUES (2008, 1, 46, '2008-01-22', '38.855');
INSERT INTO `siaceda`.`mastunidadtributaria` (`Anio`, `Secuencia`, `Valor`, `Fecha`, `GacetaOficial`) VALUES (2009, 1, 55, '2009-02-26', '39.127');
INSERT INTO `siaceda`.`mastunidadtributaria` (`Anio`, `Secuencia`, `Valor`, `Fecha`, `GacetaOficial`) VALUES (2010, 1, 65, '2010-04-02', '39.361');
INSERT INTO `siaceda`.`mastunidadtributaria` (`Anio`, `Secuencia`, `Valor`, `Fecha`, `GacetaOficial`) VALUES (2011, 1, 76, '2011-02-24', '39.623');
INSERT INTO `siaceda`.`mastunidadtributaria` (`Anio`, `Secuencia`, `Valor`, `Fecha`, `GacetaOficial`) VALUES (2012, 1, 90, '2012-02-16', '39.866');
--------------
--	2012-10-05
--------------
CREATE TABLE `rh_bonoalimentacioneventos` (
  `Anio` year(4) NOT NULL,
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `CodBonoAlim` varchar(3) NOT NULL,
  `CodPersona` varchar(6) NOT NULL COMMENT 'mastpersonas->CodPersona',
  `Secuencia` int(3) NOT NULL DEFAULT '0',
  `Fecha` date NOT NULL DEFAULT '0000-00-00',
  `HoraSalida` time NOT NULL,
  `HoraEntrada` time NOT NULL,
  `TotalHoras` varchar(10) NOT NULL,
  `TipoEvento` varchar(2) NOT NULL COMMENT 'miscelaneo->TIPOFALTAS',
  `Motivo` varchar(2) NOT NULL COMMENT 'miscelaneo->PERMISOS',
  `Observaciones` text NOT NULL,
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY (`Anio`,`CodOrganismo`,`CodBonoAlim`,`CodPersona`,`Secuencia`),
  KEY `FK_rh_bonoalimentacioneventos_1` (`Anio`,`CodOrganismo`,`CodBonoAlim`,`CodPersona`),
  CONSTRAINT `FK_rh_bonoalimentacioneventos_1` FOREIGN KEY (`Anio`, `CodOrganismo`, `CodBonoAlim`, `CodPersona`) REFERENCES `rh_bonoalimentaciondet` (`Anio`, `CodOrganismo`, `CodBonoAlim`, `CodPersona`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
--------------
--	2012-10-08
--------------
ALTER TABLE `siaceda`.`mastempleado` ADD COLUMN `CodHorario` VARCHAR(3) NULL COMMENT 'rh_horario->CodHorario'  AFTER `ReferenciaNombre` ;
--------------
--	2012-10-09
--------------
INSERT INTO `siaceda`.`mastparametros` (`ParametroClave`, `TipoValor`, `ValorParam`, `Estado`, `DescripcionParam`, `CodOrganismo`, `CodAplicacion`, `UltimoUsuario`, `UltimaFecha`) VALUES ('UTDESC', 'T', '3:30', 'A', 'INDICA EL TOTAL DE TIEMPO MINIMO NECESARIO PARA PODER DESCONTAR EL DIA POR BONO DE ALIMENTACION AL EMPLEADO', '0001', 'RH', 'EJBOLIVAR', '2012-10-09');
--------------
--	2012-10-10
--------------
ALTER TABLE `siaceda`.`mastempleado` ADD INDEX `CodHorario` (`CodHorario` ASC) ;
--------------
--	2012-10-11
--------------
INSERT INTO `siaceda`.`mastparametros` (`ParametroClave`, `TipoValor`, `ValorParam`, `Estado`, `DescripcionParam`, `CodOrganismo`, `CodAplicacion`, `UltimoUsuario`, `UltimaFecha`) VALUES ('VACVENDIAS', 'N', '30', 'A', 'DIAS PARA HABILITAR EL INGRESO DE SOLICITUDES DE VACACIONES PARA LOS EMPLEADOS', '0001', 'RH', 'EJBOLIVAR', '2012-10-11');
--
ALTER TABLE `siaceda`.`rh_vacacionperiodo` ADD COLUMN `CodTipoNom` VARCHAR(2) NOT NULL COMMENT 'tiponomina->CodTipoNom'  AFTER `NroPeriodo` , DROP PRIMARY KEY , ADD PRIMARY KEY (`CodPersona`, `NroPeriodo`, `CodTipoNom`) ;
--
UPDATE rh_vacacionperiodo vp SET CodTipoNom = (SELECT CodTipoNom FROM mastempleado WHERE CodPersona = vp.CodPersona);
--
ALTER TABLE `siaceda`.`rh_vacacionutilizacion` ADD COLUMN `CodTipoNom` VARCHAR(2) NOT NULL COMMENT 'tiponomina->CodTipoNom'  AFTER `NroPeriodo` , DROP PRIMARY KEY , ADD PRIMARY KEY (`Secuencia`, `CodPersona`, `NroPeriodo`, `CodTipoNom`) ;
--
UPDATE rh_vacacionutilizacion vu SET CodTipoNom = (SELECT CodTipoNom FROM rh_vacacionperiodo WHERE CodPersona = vu.CodPersona LIMIT 0, 1);
--
ALTER TABLE `siaceda`.`rh_vacacionpago` ADD COLUMN `CodTipoNom` VARCHAR(2) NOT NULL COMMENT 'tiponomina->CodTipoNom'  AFTER `Secuencia` , DROP PRIMARY KEY , ADD PRIMARY KEY (`CodPersona`, `NroPeriodo`, `Secuencia`, `CodTipoNom`) ;
--
UPDATE rh_vacacionpago vp SET CodTipoNom = (SELECT CodTipoNom FROM rh_vacacionperiodo WHERE CodPersona = vp.CodPersona LIMIT 0, 1);
--
INSERT INTO `siaceda`.`seguridad_concepto` (`CodAplicacion`, `Grupo`, `Concepto`, `Descripcion`, `Estado`, `UltimoUsuario`, `UltimaFecha`) VALUES ('RH', '02', '02-0008', 'Lista de Solicitudes', 'A', 'EJBOLIVAR', '2012-10-11');
INSERT INTO `siaceda`.`seguridad_concepto` (`CodAplicacion`, `Grupo`, `Concepto`, `Descripcion`, `Estado`, `UltimoUsuario`, `UltimaFecha`) VALUES ('RH', '02', '02-0009', 'Disfrute de Vacaciones', 'A', 'EJBOLIVAR', '2012-10-11');
INSERT INTO `siaceda`.`seguridad_concepto` (`CodAplicacion`, `Grupo`, `Concepto`, `Descripcion`, `Estado`, `UltimoUsuario`, `UltimaFecha`) VALUES ('RH', '02', '02-0010', 'Retenciones Judiciales', 'A', 'EJBOLIVAR', '2012-10-11');
--------------
--	2012-10-16
--------------
INSERT INTO `siaceda`.`mastparametros` (`ParametroClave`, `TipoValor`, `ValorParam`, `Estado`, `DescripcionParam`, `CodOrganismo`, `CodAplicacion`, `UltimoUsuario`, `UltimaFecha`) VALUES ('HORADIR', 'N', '7', 'A', 'HORAS DIARIAS DE TRABAJO POR DEFECTO', '0001', 'RH', 'EJBOLIVAR', '2012-10-16');
--
INSERT INTO `siaceda`.`mastparametros` (`ParametroClave`, `TipoValor`, `ValorParam`, `Estado`, `DescripcionParam`, `CodOrganismo`, `CodAplicacion`, `UltimoUsuario`, `UltimaFecha`) VALUES ('HORDIAS', 'N', '5', 'A', 'DIAS SEMANALES DE TRABAJO POR DEFECTO', '0001', 'RH', 'EJBOLIVAR', '2012-10-16');
--
ALTER TABLE `siaceda`.`rh_bonoalimentacioneventos` CHANGE COLUMN `HoraSalida` `HoraSalida` TIME NULL  , CHANGE COLUMN `HoraEntrada` `HoraEntrada` TIME NULL  ;
--
ALTER TABLE `siaceda`.`rh_cargafamiliar` CHANGE COLUMN `DireccionFam` `DireccionFam` TEXT NOT NULL DEFAULT ''  , CHANGE COLUMN `Empresa` `Empresa` VARCHAR(100) NOT NULL  , CHANGE COLUMN `DireccionEmpresa` `DireccionEmpresa` TEXT NOT NULL  ;
--
ALTER TABLE `siaceda`.`rh_cargafamiliar` CHANGE COLUMN `TiempoServicio` `TiempoServicio` INT(2) NOT NULL  , CHANGE COLUMN `SueldoMensual` `SueldoMensual` DECIMAL(11,2) NOT NULL  ;
--
ALTER TABLE `siaceda`.`rh_cargafamiliar` ADD COLUMN `FlagEstudia` VARCHAR(1) NOT NULL DEFAULT 'N'  AFTER `Comentarios`;
--
ALTER TABLE `siaceda`.`rh_cargafamiliar` ADD COLUMN `EstadoCivil` VARCHAR(2) NOT NULL COMMENT 'miscelaneo->EDOCIVIL'  AFTER `FlagEstudia` ;
--------------
--	2012-10-17
--------------
ALTER TABLE `siaceda`.`ap_tipodocumento` ADD COLUMN `FlagAutoNomina` VARCHAR(1) NULL DEFAULT 'N'  AFTER `FlagMontoNegativo` , CHANGE COLUMN `FlagProvision` `FlagProvision` VARCHAR(1) NOT NULL DEFAULT 'N'  , CHANGE COLUMN `FlagAdelanto` `FlagAdelanto` VARCHAR(1) NOT NULL DEFAULT 'N'  , CHANGE COLUMN `FlagFiscal` `FlagFiscal` VARCHAR(1) NOT NULL DEFAULT 'N'  , CHANGE COLUMN `FlagExportableRegistroCompra` `FlagExportableRegistroCompra` VARCHAR(1) NOT NULL DEFAULT 'N'  , CHANGE COLUMN `FlagMontoNegativo` `FlagMontoNegativo` VARCHAR(1) NOT NULL DEFAULT 'N'  , CHANGE COLUMN `Estado` `Estado` VARCHAR(1) NOT NULL DEFAULT 'A' COMMENT 'A:ACTIVO; I:INACTIVO;'  ;
--
ALTER TABLE `siaceda`.`ap_tipodocumento` CHANGE COLUMN `Descripcion` `Descripcion` VARCHAR(100) NOT NULL  ;
--
ALTER TABLE `siaceda`.`pr_tiponominaperiodo` CHANGE COLUMN `Secuencia` `Secuencia` INT(2) NOT NULL  ;
--
ALTER TABLE `siaceda`.`pr_tiponominaperiodo` DROP PRIMARY KEY , ADD PRIMARY KEY (`CodTipoNom`, `Periodo`, `Mes`, `Secuencia`) ;
--------------
--	2012-10-19
--------------
UPDATE `siaceda`.`seguridad_concepto` SET `Descripcion`='Control de Asitencias' WHERE `CodAplicacion`='RH' and`Grupo`='02' and`Concepto`='02-0006';
INSERT INTO `siaceda`.`seguridad_concepto` (`CodAplicacion`, `Grupo`, `Concepto`, `Descripcion`, `Estado`, `UltimoUsuario`, `UltimaFecha`) VALUES ('RH', '02', '02-0011', 'Resumen de Eventos', 'A', 'EJBOLIVAR', '2012-10-19');
--
ALTER TABLE `siaceda`.`rh_bonoalimentacion` DROP INDEX `UK_rh_bonoalimentacion_1` , ADD UNIQUE INDEX `UK_rh_bonoalimentacion_1` (`Periodo` ASC, `CodTipoNom` ASC, `CodOrganismo` ASC) ;
--------------
--	2012-10-23
--------------
ALTER TABLE `siaceda`.`bancopersona` CHANGE COLUMN `Monto` `Monto` DECIMAL(11,2) NOT NULL DEFAULT '0.00'  , CHANGE COLUMN `UltimaFecha` `UltimaFecha` DATETIME NOT NULL  , CHANGE COLUMN `FlagPrincipal` `FlagPrincipal` VARCHAR(1) NOT NULL DEFAULT 'N'  ;
ALTER TABLE `siaceda`.`rh_capacitacion_hora` CHANGE COLUMN `HoraInicioLunes` `HoraInicioLunes` VARCHAR(8) NOT NULL  , CHANGE COLUMN `HoraFinLunes` `HoraFinLunes` VARCHAR(8) NOT NULL  , CHANGE COLUMN `HoraInicioMartes` `HoraInicioMartes` VARCHAR(8) NOT NULL  , CHANGE COLUMN `HoraFinMartes` `HoraFinMartes` VARCHAR(8) NOT NULL  , CHANGE COLUMN `HoraInicioMiercoles` `HoraInicioMiercoles` VARCHAR(8) NOT NULL  , CHANGE COLUMN `HoraFinMiercoles` `HoraFinMiercoles` VARCHAR(8) NOT NULL  , CHANGE COLUMN `HoraInicioJueves` `HoraInicioJueves` VARCHAR(8) NOT NULL  , CHANGE COLUMN `HoraFinJueves` `HoraFinJueves` VARCHAR(8) NOT NULL  , CHANGE COLUMN `HoraInicioViernes` `HoraInicioViernes` VARCHAR(8) NOT NULL  , CHANGE COLUMN `HoraFinViernes` `HoraFinViernes` VARCHAR(8) NOT NULL  , CHANGE COLUMN `HoraInicioSabado` `HoraInicioSabado` VARCHAR(8) NOT NULL  , CHANGE COLUMN `HoraFinSabado` `HoraFinSabado` VARCHAR(8) NOT NULL  , CHANGE COLUMN `HoraInicioDomingo` `HoraInicioDomingo` VARCHAR(8) NOT NULL  , CHANGE COLUMN `HoraFinDomingo` `HoraFinDomingo` VARCHAR(8) NOT NULL  ;
--------------
--	2012-10-30
--------------
ALTER TABLE `siaceda`.`tiponomina` ADD INDEX `Index_2`(`CodPerfilConcepto`);
--
ALTER TABLE `siaceda`.`mastorganismos` ADD INDEX `Index_2`(`CodPersona`);
--
ALTER TABLE `siaceda`.`mastproveedores` ADD INDEX `Index_2`(`CodTipoDocumento`), ADD INDEX `Index_3`(`CodTipoPago`), ADD INDEX `Index_4`(`CodFormaPago`), ADD INDEX `Index_5`(`CodTipoServicio`);
--
ALTER TABLE `siaceda`.`pr_conceptoperfildetalle` ADD INDEX `Index_3`(`CuentaDebe`), ADD INDEX `Index_4`(`CuentaHaber`);
--------------
--	2012-11-01
--------------
ALTER TABLE `siaceda`.`ap_obligaciones` ADD COLUMN `FlagVerificado` VARCHAR(1) NOT NULL DEFAULT N AFTER `VoucherAnulacion`;
--------------
--	2012-11-04
--------------
ALTER TABLE `siaceda`.`rh_empleado_experiencia` MODIFY COLUMN `CodPersona` VARCHAR(6) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'mastpersonas->CodPersona',
 MODIFY COLUMN `FechaDesde` DATE NOT NULL DEFAULT '0000-00-00',
 MODIFY COLUMN `FechaHasta` DATE NOT NULL DEFAULT '0000-00-00',
 MODIFY COLUMN `MotivoCese` VARCHAR(2) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'miscelaneo->AREAEXP',
 MODIFY COLUMN `Sueldo` DECIMAL(11,2) NOT NULL DEFAULT '0.00',
 MODIFY COLUMN `UltimaFecha` DATETIME NOT NULL;
--
ALTER TABLE `siaceda`.`rh_empleado_experiencia` MODIFY COLUMN `MotivoCese` VARCHAR(2) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'miscelaneo->MOTCESE',
 MODIFY COLUMN `AreaExperiencia` VARCHAR(2) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'miscelaneo->AREAEXP';
--
ALTER TABLE `siaceda`.`rh_empleado_experiencia` MODIFY COLUMN `TipoEnte` VARCHAR(2) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'miscelaneo->TIPOENTE';
--
ALTER TABLE `siaceda`.`rh_empleado_experiencia` MODIFY COLUMN `CargoOcupado` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
--------------
--	2012-11-05
--------------
ALTER TABLE `siaceda`.`rh_empleado_referencias` MODIFY COLUMN `Secuencia` INT(6) UNSIGNED NOT NULL;
--
ALTER TABLE `siaceda`.`rh_empleado_referencias` MODIFY COLUMN `Tipo` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'P:PERSONAL; L:LABORAL;',
 MODIFY COLUMN `UltimaFecha` DATETIME NOT NULL;
--
ALTER TABLE `siaceda`.`rh_empleado_referencias` MODIFY COLUMN `Direccion` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
--------------
--	2012-11-05
--------------
ALTER TABLE `siaceda`.`rh_meritosfaltas` DROP FOREIGN KEY `rh_meritosfaltas_ibfk_1` ;
ALTER TABLE `siaceda`.`rh_meritosfaltas` CHANGE COLUMN `Externo` `Externo` VARCHAR(100) NOT NULL  AFTER `FechaFin` , CHANGE COLUMN `FlagExterno` `FlagExterno` VARCHAR(1) NOT NULL DEFAULT 'N'  AFTER `Externo` , CHANGE COLUMN `CodPersona` `CodPersona` VARCHAR(6) NOT NULL COMMENT 'mastpersonas->CodPersona'  , CHANGE COLUMN `Observacion` `Observacion` TEXT NULL DEFAULT NULL  , CHANGE COLUMN `Clasificacion` `Clasificacion` VARCHAR(2) NOT NULL COMMENT 'miscelaneo->MERITO/DEMERITO'  , CHANGE COLUMN `Responsable` `Responsable` VARCHAR(6) NOT NULL COMMENT 'mastpersonas->CodPersona'  , CHANGE COLUMN `UltimaFecha` `UltimaFecha` DATETIME NOT NULL  , ADD CONSTRAINT `rh_meritosfaltas_ibfk_1`  FOREIGN KEY (`CodPersona` )  REFERENCES `siaceda`.`mastpersonas` (`CodPersona` );
--
ALTER TABLE `siaceda`.`rh_meritosfaltas` CHANGE COLUMN `Secuencia` `Secuencia` INT(6) NOT NULL  , CHANGE COLUMN `Tipo` `Tipo` VARCHAR(1) NOT NULL COMMENT 'M:MERITO; D:DEMERITO;'  ;
--
ALTER TABLE `siaceda`.`rh_patrimonio_inmueble` CHANGE COLUMN `Valor` `Valor` DECIMAL(11,2) NOT NULL DEFAULT '0.00'  , CHANGE COLUMN `UltimaFecha` `UltimaFecha` DATETIME NOT NULL  ;
--
ALTER TABLE `siaceda`.`rh_patrimonio_inversion` CHANGE COLUMN `Secuencia` `Secuencia` INT(6) NOT NULL  , CHANGE COLUMN `ValorNominal` `ValorNominal` DECIMAL(11,2) NOT NULL DEFAULT '0.00'  , CHANGE COLUMN `Valor` `Valor` DECIMAL(11,2) NOT NULL DEFAULT '0.00'  , CHANGE COLUMN `FlagGarantia` `FlagGarantia` VARCHAR(1) NOT NULL DEFAULT 'N'  , CHANGE COLUMN `UltimaFecha` `UltimaFecha` DATETIME NOT NULL  ;
--
ALTER TABLE `siaceda`.`rh_patrimonio_cuenta` CHANGE COLUMN `Secuencia` `Secuencia` INT(6) NOT NULL  , CHANGE COLUMN `TipoCuenta` `TipoCuenta` VARCHAR(2) NOT NULL COMMENT 'miscelaneo->TIPOCTA'  , CHANGE COLUMN `Valor` `Valor` DECIMAL(11,2) NOT NULL DEFAULT '0.00'  , CHANGE COLUMN `FlagGarantia` `FlagGarantia` VARCHAR(1) NOT NULL DEFAULT 'N'  , CHANGE COLUMN `UltimaFecha` `UltimaFecha` DATETIME NOT NULL  ;
--
ALTER TABLE `siaceda`.`rh_patrimonio_otro` CHANGE COLUMN `Secuencia` `Secuencia` INT(6) NOT NULL  , CHANGE COLUMN `ValorCompra` `ValorCompra` DECIMAL(11,2) NOT NULL DEFAULT '0.00'  , CHANGE COLUMN `Valor` `Valor` DECIMAL(11,2) NOT NULL DEFAULT '0.00'  , CHANGE COLUMN `FlagGarantia` `FlagGarantia` VARCHAR(1) NOT NULL DEFAULT 'N'  , CHANGE COLUMN `UltimaFecha` `UltimaFecha` DATETIME NOT NULL  ;
--------------
--	2012-11-05
--------------
ALTER TABLE `siaceda`.`rh_documentos_historia` MODIFY COLUMN `CodPersona` VARCHAR(6) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'mastpersonas->CodPersona',
 MODIFY COLUMN `Secuencia` INT(3) UNSIGNED NOT NULL,
 MODIFY COLUMN `Responsable` VARCHAR(6) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'mastpersonas->CodPersona',
 MODIFY COLUMN `ObsEntrega` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
 MODIFY COLUMN `ObsDevuelto` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
 MODIFY COLUMN `UltimaFecha` DATETIME NOT NULL;
--
ALTER TABLE `siaceda`.`rh_documentos_historia` DROP INDEX `rh_documentos_historia_ibfk_2`,
 DROP FOREIGN KEY `rh_documentos_historia_ibfk_1`,
 DROP FOREIGN KEY `rh_documentos_historia_ibfk_2`;
--
ALTER TABLE `siaceda`.`rh_empleado_documentos` DROP INDEX `rh_empleado_documentos_ibuk_1`,
 DROP FOREIGN KEY `rh_empleado_documentos_ibfk_1`;
--
ALTER TABLE `siaceda`.`rh_empleado_documentos` MODIFY COLUMN `CodPersona` VARCHAR(6) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'mastpersonas->CodPersona',
 MODIFY COLUMN `Documento` VARCHAR(2) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'miscelaneo->DOCUMENTOS',
 MODIFY COLUMN `FlagPresento` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'N',
 MODIFY COLUMN `FlagCarga` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'N',
 MODIFY COLUMN `CargaFamiliar` INTEGER(4) UNSIGNED NOT NULL,
 MODIFY COLUMN `Observaciones` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
 MODIFY COLUMN `Ruta` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
 MODIFY COLUMN `UltimaFecha` DATETIME NOT NULL;
--
ALTER TABLE `siaceda`.`rh_empleado_documentos` ADD CONSTRAINT `FK_rh_empleado_documentos_1` FOREIGN KEY `FK_rh_empleado_documentos_1` (`CodPersona`) REFERENCES `mastpersonas` (`CodPersona`) ON DELETE CASCADE ON UPDATE CASCADE;
--
ALTER TABLE `siaceda`.`rh_empleado_documentos` ADD INDEX `Index_3`(`CargaFamiliar`);
--
INSERT INTO `siaceda`.`mastparametros` (
`ParametroClave` ,
`TipoValor` ,
`ValorParam` ,
`Estado` ,
`DescripcionParam` ,
`Explicacion` ,
`CodOrganismo` ,
`CodAplicacion` ,
`UltimoUsuario` ,
`UltimaFecha`
)
VALUES (
'PATHIMGDOC', 'T', '../imagenes/documentos/', 'A', 'PATH DE LAS DOCUMENTOS ENTREGADOS POR LOS EMPLEADOS', '', '0001', 'RH', '', ''
);
--------------
--	2012-11-06
--------------
ALTER TABLE `siaceda`.`rh_documentos_historia` CHANGE COLUMN `Estado` `Estado` VARCHAR(1) NOT NULL DEFAULT 'E' COMMENT 'E:ENTREGADO; D:DEVUELTO; P:PERDIDO;'  ;