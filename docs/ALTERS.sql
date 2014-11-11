--	2012-08-13
ALTER TABLE `siaceda`.`mastpersonas` CHANGE COLUMN `Email` `Email` VARCHAR(255) NULL DEFAULT NULL  ;
--	2012-08-15
ALTER TABLE `siaceda`.`lg_activofijo` DROP INDEX `UK_lg_activofijo_1` ;
ALTER TABLE `siaceda`.`rh_controlasistencia` ADD INDEX `IK_rh_controlasistencia_1` (`FechaFormat` ASC, `HoraFormat` ASC) ;
ALTER TABLE `siaceda`.`rh_controlasistencia` DROP INDEX `IK_rh_controlasistencia_1` , ADD INDEX `IK_rh_controlasistencia_1` (`FechaFormat` ASC, `HoraFormat` ASC, `Event_Puerta` ASC) ;
--	2012-08-17
ALTER TABLE `siaceda`.`rh_capacitacion_empleados` CHANGE COLUMN `Secuencia` `Secuencia` INT(6) NOT NULL  , CHANGE COLUMN `CostoIndividual` `CostoIndividual` DECIMAL(11,2) NOT NULL  , CHANGE COLUMN `ImporteGastos` `ImporteGastos` DECIMAL(11,2) NOT NULL  ;
ALTER TABLE `siaceda`.`rh_capacitacion` ADD COLUMN `FlagCostos` VARCHAR(1) NOT NULL DEFAULT 'N'  AFTER `Fundamentacion7` , ADD COLUMN `Observaciones` TEXT NOT NULL  AFTER `FlagCostos` ;
ALTER TABLE `siaceda`.`rh_capacitacion` CHANGE COLUMN `Estado` `Estado` VARCHAR(2) NOT NULL DEFAULT 'PE' COMMENT 'PE:PENDIENTE; AP:APROBADO; IN:INICIADO; TE:TERMINADO;'  , CHANGE COLUMN `FlagHorarioIndividual` `FlagHorarioIndividual` VARCHAR(1) NOT NULL DEFAULT 'N'  , CHANGE COLUMN `FlagLogistica` `FlagLogistica` VARCHAR(1) NOT NULL DEFAULT 'N'  ;
ALTER TABLE `siaceda`.`rh_capacitacion_empleados` CHANGE COLUMN `NroAsistencias` `NroAsistencias` INT(4) NOT NULL DEFAULT '0'  , CHANGE COLUMN `CostoIndividual` `CostoIndividual` DECIMAL(11,2) NOT NULL DEFAULT '0.00'  , CHANGE COLUMN `DiasAsistidos` `DiasAsistidos` INT(4) NOT NULL DEFAULT '0'  , CHANGE COLUMN `NroPeriodo` `NroPeriodo` INT(4) NOT NULL DEFAULT '0'  , CHANGE COLUMN `Nota` `Nota` INT(4) NOT NULL DEFAULT '0'  , CHANGE COLUMN `ImporteGastos` `ImporteGastos` DECIMAL(11,2) NOT NULL DEFAULT '0.00'  ;
ALTER TABLE `siaceda`.`rh_capacitacion` DROP COLUMN `MontoMaxAsumido` ;
ALTER TABLE `siaceda`.`rh_capacitacion` ADD COLUMN `Periodo` VARCHAR(7) NOT NULL  AFTER `Observaciones` ;
ALTER TABLE `siaceda`.`rh_capacitacion` ADD COLUMN `Anio` YEAR NOT NULL  FIRST , CHANGE COLUMN `CodOrganismo` `CodOrganismo` VARCHAR(4) NOT NULL  AFTER `Anio` , DROP PRIMARY KEY , ADD PRIMARY KEY (`CodOrganismo`, `Capacitacion`, `Anio`) ;
--	2012-08-21
ALTER TABLE `siaceda`.`rh_capacitacion_empleados` ADD COLUMN `Anio` YEAR NOT NULL  FIRST , DROP PRIMARY KEY , ADD PRIMARY KEY (`Capacitacion`, `CodOrganismo`, `Secuencia`, `Anio`) ;
ALTER TABLE `siaceda`.`rh_capacitacion` ADD COLUMN `CreadoPor` VARCHAR(6) NULL  AFTER `Periodo` , ADD COLUMN `FechaCreado` DATE NULL  AFTER `CreadoPor` , ADD COLUMN `AprobadoPor` VARCHAR(6) NULL  AFTER `FechaCreado` , ADD COLUMN `FechaAprobado` DATE NULL  AFTER `AprobadoPor` , DROP PRIMARY KEY , ADD PRIMARY KEY (`Anio`, `CodOrganismo`, `Capacitacion`) ;
ALTER TABLE `siaceda`.`rh_capacitacion_hora` ADD COLUMN `Anio` YEAR NOT NULL  FIRST , DROP PRIMARY KEY , ADD PRIMARY KEY (`Capacitacion`, `CodOrganismo`, `Secuencia`, `Anio`) ;
ALTER TABLE `siaceda`.`rh_capacitacion_hora` DROP PRIMARY KEY , ADD PRIMARY KEY (`Capacitacion`, `CodOrganismo`, `Secuencia`, `Anio`, `CodPersona`) ;
ALTER TABLE `siaceda`.`rh_capacitacion_gastos` ADD COLUMN `Anio` YEAR NOT NULL  FIRST , ADD COLUMN `CodOrganismo` VARCHAR(4) NOT NULL  AFTER `Anio` , CHANGE COLUMN `Secuencia` `Secuencia` INT(2) NOT NULL  , CHANGE COLUMN `SubTotal` `SubTotal` DECIMAL(11,2) NOT NULL  , CHANGE COLUMN `Impuestos` `Impuestos` DECIMAL(11,2) NOT NULL  , CHANGE COLUMN `Total` `Total` DECIMAL(11,2) NOT NULL  , DROP PRIMARY KEY , ADD PRIMARY KEY (`Capacitacion`, `CodOrganismo`, `Anio`, `Secuencia`) ;
--	2012-08-22
ALTER TABLE `siaceda`.`rh_gradoscompetencia` ADD COLUMN `TipoEvaluacion` VARCHAR(1) NOT NULL COMMENT 'rh_tipoevaluacion->TipoEvaluacion'  FIRST , DROP PRIMARY KEY , ADD PRIMARY KEY (`Grado`, `TipoEvaluacion`) ;
ALTER TABLE `siaceda`.`rh_gradoscompetencia` CHANGE COLUMN `UltimaFecha` `UltimaFecha` DATETIME NOT NULL  ;
ALTER TABLE `siaceda`.`rh_tipoevaluacion` ADD COLUMN `FlagSistema` VARCHAR(1) NOT NULL DEFAULT 'N'  AFTER `Descripcion` , CHANGE COLUMN `UltimaFecha` `UltimaFecha` DATETIME NOT NULL  ;
ALTER TABLE `siaceda`.`rh_factorvalor` DROP FOREIGN KEY `FK_rh_factorvalor_2` ;
ALTER TABLE `siaceda`.`rh_factorvalor` DROP INDEX `FK_rh_factorvalor_2` ;
ALTER TABLE `siaceda`.`rh_factorvalor` DROP FOREIGN KEY `FK_rh_factorvalor_1` ;
ALTER TABLE `siaceda`.`rh_factorvalor` DROP INDEX `FK_rh_factorvalor_1` ;
ALTER TABLE `siaceda`.`rh_factorvalor` ADD COLUMN `TipoEvaluacion` VARCHAR(1) NOT NULL  AFTER `Competencia` ,   ADD CONSTRAINT `FK_rh_factorvalor_1`  FOREIGN KEY (`Competencia` )  REFERENCES `siaceda`.`rh_evaluacionfactores` (`Competencia` )  ON DELETE NO ACTION  ON UPDATE NO ACTION, ADD INDEX `FK_rh_factorvalor_1` (`Competencia` ASC) ;
UPDATE rh_factorvalor SET TipoEvaluacion = 'E';
ALTER TABLE `siaceda`.`ap_ordenpago` ADD COLUMN `FechaRevisado` DATE NOT NULL  AFTER `RevisadoPor` ;
ALTER TABLE `siaceda`.`rh_evaluacion` ADD COLUMN `TipoEvaluacion` VARCHAR(1) NOT NULL COMMENT 'rh_tipoevalucion->TipoEvalaucion'  AFTER `Descripcion` ;
ALTER TABLE `siaceda`.`rh_evaluacion` DROP COLUMN `FlagEmpleado` ;
--	2012-08-23
INSERT INTO `siaceda`.`seguridad_concepto` (`CodAplicacion`, `Grupo`, `Concepto`, `Descripcion`, `Estado`) VALUES ('RH', '03', '03-0055', 'Items o Preguntas', 'A');
CREATE TABLE `rh_evaluacionitems` (
  `Evaluacion` int(4) unsigned NOT NULL comment 'rh_evaluacion->Evaluacion',
  `CodItem` varchar(4) NOT NULL,
  `Descripcion` text NOT NULL,
  `PuntajeMin` int(4) NOT NULL DEFAULT '0',
  `PuntajeMax` int(4) NOT NULL DEFAULT '0',
  `Estado` varchar(1) NOT NULL DEFAULT 'A' COMMENT 'A:ACTIVO; I:INACTIVO;',
  `UltimoUsuario` varchar(30) NOT NULL,
  `UltimaFecha` datetime NOT NULL,
  PRIMARY KEY  (`Evaluacion`,`CodItem`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `siaceda`.`rh_evaluacionarea` CHANGE COLUMN `Estado` `Estado` VARCHAR(1) NOT NULL DEFAULT 'A' COMMENT 'A:ACTIVO; I:INACTIVO;'  , CHANGE COLUMN `UltimaFecha` `UltimaFecha` DATETIME NOT NULL  ;
ALTER TABLE `siaceda`.`rh_evaluacionfactores` CHANGE COLUMN `Explicacion` `Explicacion` TEXT NOT NULL  , CHANGE COLUMN `FlagPlantilla` `FlagPlantilla` VARCHAR(1) NOT NULL DEFAULT 'N'  , CHANGE COLUMN `ValorRequerido` `ValorRequerido` INT(4) NOT NULL DEFAULT '0'  , CHANGE COLUMN `ValorMinimo` `ValorMinimo` INT(4) NOT NULL DEFAULT '0'  , CHANGE COLUMN `Estado` `Estado` VARCHAR(1) NOT NULL DEFAULT 'A' COMMENT 'A:ACTIVO; I:INACTIVO;'  , CHANGE COLUMN `UltimaFecha` `UltimaFecha` DATETIME NOT NULL  ;
ALTER TABLE `siaceda`.`rh_evaluacionfactores` CHANGE COLUMN `TipoCompetencia` `TipoCompetencia` VARCHAR(2) NOT NULL COMMENT 'miscelaneo->TIPOCOMPE'  , CHANGE COLUMN `Nivel` `Nivel` VARCHAR(2) NOT NULL COMMENT 'miscelaneo->NIVELCOMPE'  ;
ALTER TABLE `siaceda`.`rh_evaluacionfactores` CHANGE COLUMN `Calificacion` `Calificacion` VARCHAR(2) NOT NULL COMMENT 'miscelaneo->CALICOMPE'  ;
ALTER TABLE `siaceda`.`rh_factorvalor` CHANGE COLUMN `TipoEvaluacion` `TipoEvaluacion` VARCHAR(1) NOT NULL COMMENT 'rh_tipoevaluacion->TipoEvaluacion'  , CHANGE COLUMN `Explicacion` `Explicacion` TEXT NOT NULL  , CHANGE COLUMN `Valor` `Valor` INT(4) NOT NULL DEFAULT '0'  , CHANGE COLUMN `Estado` `Estado` VARCHAR(1) CHARACTER SET 'latin1' NOT NULL DEFAULT 'A' COMMENT 'A:ACTIVO; I:INACTIVO;'  , CHANGE COLUMN `UltimaFecha` `UltimaFecha` DATETIME NOT NULL  , CHANGE COLUMN `Explicacion2` `Explicacion2` TEXT CHARACTER SET 'latin1' NOT NULL  ;
ALTER TABLE `siaceda`.`rh_factorvalor` DROP PRIMARY KEY , ADD PRIMARY KEY (`Secuencia`, `Competencia`) ;
ALTER TABLE `siaceda`.`rh_factorvalor` CHANGE COLUMN `Grado` `Grado` INT(4) NOT NULL COMMENT 'rh_gradoscompetencia->Grado'  ;
ALTER TABLE `siaceda`.`rh_factorvalor` ADD INDEX `I_rh_factorvalor_1` (`TipoEvaluacion` ASC, `Grado` ASC) ;
ALTER TABLE `siaceda`.`rh_evaluacionfactoresplantilla` CHANGE COLUMN `FlagTipoEvaluacion` `FlagTipoEvaluacion` VARCHAR(1) NOT NULL DEFAULT 'M' COMMENT 'M:MULTIPLE; U:UNICO;'  , CHANGE COLUMN `Estado` `Estado` VARCHAR(1) NOT NULL DEFAULT 'A' COMMENT 'A:ACTIVO; I:INACTIVO;'  , CHANGE COLUMN `UltimaFecha` `UltimaFecha` DATETIME NOT NULL  ;
--	2012-08-28
CREATE  TABLE `siaceda`.`rh_asociacioncarreras` (
  `CodOrganismo` VARCHAR(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo' ,
  `Secuencia` INT(4) NOT NULL COMMENT 'rh_evaluacionperiodo->Secuencia' ,
  `Codigo` VARCHAR(4) NOT NULL ,
  `CodPersona` VARCHAR(6) NOT NULL COMMENT 'mastpersonas->CodPersona' ,
  `CodCargo` VARCHAR(4) NOT NULL COMMENT 'rh_puestos->CodCargo' ,
  `DescripCargo` VARCHAR(255) NOT NULL ,
  `CodDependencia` VARCHAR(4) NOT NULL COMMENT 'mastdependencias->CodDependencia' ,
  `Periodo` VARCHAR(7) NOT NULL ,
  `Estado` VARCHAR(2) NOT NULL DEFAULT 'A' COMMENT 'A:ABIERTO; C:CERRADO;' ,
  `UltimoUsuario` VARCHAR(30) NULL ,
  `UltimaFecha` DATETIME NULL ,
  PRIMARY KEY (`CodOrganismo`, `Secuencia`, `Codigo`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;
ALTER TABLE `siaceda`.`rh_asociacioncarreras` CHANGE COLUMN `Estado` `Estado` VARCHAR(2) NOT NULL DEFAULT 'A' COMMENT 'AB:ABIERTO; TE:TERMINADO; AN:ANULADO'  ;
INSERT INTO `siaceda`.`seguridad_concepto` (`CodAplicacion`, `Grupo`, `Concepto`, `Descripcion`, `Estado`, `UltimoUsuario`, `UltimaFecha`) VALUES ('RH', '01', '01-0030', 'Actualizar Carreras', 'A', 'EJBOLIVAR', '2012-08-28');
UPDATE `siaceda`.`seguridad_concepto` SET `Descripcion`='Listar Carreras' WHERE `CodAplicacion`='RH' and`Grupo`='01' and`Concepto`='01-0024';
INSERT INTO `siaceda`.`seguridad_concepto` (`CodAplicacion`, `Grupo`, `Concepto`, `Descripcion`, `Estado`, `UltimoUsuario`, `UltimaFecha`) VALUES ('RH', '01', '01-0031', 'Terminar Carrearas', 'A', 'EJBOLIVAR', '2012-08-28');
--	2012-08-29
ALTER TABLE `siaceda`.`rh_asociacioncarreras` ADD COLUMN `IniciadoPor` VARCHAR(6) NULL  AFTER `UltimaFecha` , ADD COLUMN `FechaIniciado` DATE NULL  AFTER `IniciadoPor` , ADD COLUMN `TerminadoPor` VARCHAR(45) NULL  AFTER `FechaIniciado` , ADD COLUMN `FechaTerminado` DATE NULL  AFTER `TerminadoPor` ;
CREATE TABLE `rh_asociacioncarrerascaptecnica` (
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `Secuencia` int(4) NOT NULL COMMENT 'rh_evaluacionperiodo->Secuencia',
  `Codigo` varchar(4) NOT NULL,
  `Linea` int(2) NOT NULL,
  `Descripcion` text NOT NULL,
  `UltimoUsuario` varchar(30) DEFAULT NULL,
  `UltimaFecha` datetime DEFAULT NULL,
  PRIMARY KEY (`CodOrganismo`,`Secuencia`,`Codigo`,`Linea`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
--	2012-08-30
CREATE TABLE `rh_asociacioncarrerashabilidad` (
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `Secuencia` int(4) NOT NULL COMMENT 'rh_evaluacionperiodo->Secuencia',
  `Codigo` varchar(4) NOT NULL,
  `Linea` int(2) NOT NULL,
  `Tipo` varchar(2) NOT NULL DEFAULT 'H' COMMENT 'H:HABILIDAD; D:DESTREZA; C:CAPACIDAD',
  `Descripcion` text NOT NULL,
  `UltimoUsuario` varchar(30) DEFAULT NULL,
  `UltimaFecha` datetime DEFAULT NULL,
  PRIMARY KEY (`CodOrganismo`,`Secuencia`,`Codigo`,`Linea`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `rh_asociacioncarrerasevaluacion` (
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `Secuencia` int(4) NOT NULL COMMENT 'rh_evaluacionperiodo->Secuencia',
  `Codigo` varchar(4) NOT NULL,
  `Linea` int(2) NOT NULL,
  `Descripcion` text NOT NULL,
  `UltimoUsuario` varchar(30) DEFAULT NULL,
  `UltimaFecha` datetime DEFAULT NULL,
  PRIMARY KEY (`CodOrganismo`,`Secuencia`,`Codigo`,`Linea`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `rh_asociacioncarrerasmetas` (
  `CodOrganismo` varchar(4) NOT NULL COMMENT 'mastorganismos->CodOrganismo',
  `Secuencia` int(4) NOT NULL COMMENT 'rh_evaluacionperiodo->Secuencia',
  `Codigo` varchar(4) NOT NULL,
  `Linea` int(2) NOT NULL,
  `Descripcion` text NOT NULL,
  `UltimoUsuario` varchar(30) DEFAULT NULL,
  `UltimaFecha` datetime DEFAULT NULL,
  PRIMARY KEY (`CodOrganismo`,`Secuencia`,`Codigo`,`Linea`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

