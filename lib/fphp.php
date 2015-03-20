<?php
session_start();
extract($_POST);
extract($_GET);
include("conexion.php");
connect();
$_PARAMETRO = parametros();
$Ahora = ahora();

// FUNCION PARA OBTENER LOS PARAMETROS DEL SISTEMA
function parametros() {
	global $_APLICACION;
	$sql = "SELECT * 
			FROM mastparametros 
			WHERE 
				--	(CodAplicacion = 'GE' OR CodAplicacion = '".$_APLICACION."') AND
				Estado = 'A'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	while ($field = mysql_fetch_array($query)) {
		$id = $field['ParametroClave'];
		$_PARAMETRO[$id] = $field['ValorParam'];
	}
	
	$_PARAMETRO["PATHSIA"]="../";
	return $_PARAMETRO;
}

//	FUNCION PARA GENERAR UN NUEVO CODIGO
function getCodigo($tabla, $campo, $digitos, $campo2=NULL, $valor2=NULL) {
	$filtro = "";
	if ($campo2) $filtro .= " AND $campo2='".$valor2."'";
	$sql="SELECT MAX($campo) FROM $tabla WHERE 1 $filtro";
	$query=mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	$field=mysql_fetch_array($query);
	$codigo=(int) ($field[0]+1);
	$codigo=(string) str_repeat("0", $digitos-strlen($codigo)).$codigo;
	return ($codigo);
}

//	FUNCION PARA GENERAR UN NUEVO CODIGO
function getCodigo_2($tabla, $campo, $correlativo, $valor, $digitos) {
	if($tabla=='bancopersona'){
		$sql="select max($campo) FROM $tabla";
	}
	else{
		$sql="select max($campo) FROM $tabla WHERE $correlativo = '$valor'";
	}

	$query=mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	$field=mysql_fetch_array($query);
	$codigo=(int) ($field[0]+1);
	$codigo=(string) str_repeat("0", $digitos-strlen($codigo)).$codigo;
	return ($codigo);
}

//	FUNCION PARA GENERAR UN NUEVO CODIGO
function getCodigo_3($tabla, $campo, $correlativo1, $correlativo2, $valor1, $valor2, $digitos) {
	$sql="select max($campo) FROM $tabla WHERE $correlativo1 = '$valor1' AND $correlativo2 = '$valor2'";
	$query=mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	$field=mysql_fetch_array($query);
	$codigo=(int) ($field[0]+1);
	$codigo=(string) str_repeat("0", $digitos-strlen($codigo)).$codigo;
	return ($codigo);
}

//	
function getCorrelativoSecuencia_2($tabla, $campo1, $campo2, $valor1, $valor2) {
	$sql = "SELECT $campo1, $campo2 FROM $tabla WHERE $campo1 = '$valor1' AND $campo2 = '$valor2'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	$rows = mysql_num_rows($query);	
	return ++$rows;
}

//	FUNCION PARA CONFIRMAR LOS PERMISOS DEL USUARIO
function opcionesPermisos($grupo, $concepto) {
	if ($_SESSION['USUARIO_ACTUAL'] == "ADMINISTRADOR") {
		$_ADMIN = "S";
		$_SHOW = "S";
		$_INSERT = "S";
		$_UPDATE = "S";
		$_DELETE = "S";
	} else {
		$sql = "SELECT FlagAdministrador 
				FROM seguridad_autorizaciones 
				WHERE 
					CodAplicacion = '".$_SESSION['APLICACION_ACTUAL']."' AND 
					Usuario = '".$_SESSION['USUARIO_ACTUAL']."' AND 
					FlagAdministrador = 'S' AND
					Estado = 'A'";
		$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		$rows = mysql_num_rows($query);
		if ($rows != 0) {
			$_ADMIN = "S";
			$_SHOW = "S";
			$_INSERT = "S";
			$_UPDATE = "S";
			$_DELETE = "S";
		} else {
			$_ADMIN = "N";
			//	--------------------------------------------
			$sql = "SELECT
						FlagMostrar,
						FlagAgregar,
						FlagModificar,
						FlagEliminar
					FROM seguridad_autorizaciones
					WHERE
						CodAplicacion = '".$_SESSION['APLICACION_ACTUAL']."' AND
						Usuario = '".$_SESSION['USUARIO_ACTUAL']."' AND
						Concepto = '".$concepto."' AND
						Estado = 'A'"; echo "<br>";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			$rows = mysql_num_rows($query);
			if ($rows != 0) {
				$field = mysql_fetch_array($query);
				$_SHOW = $field['FlagMostrar'];
				$_INSERT = $field['FlagAgregar'];
				$_UPDATE = $field['FlagModificar'];
				$_DELETE = $field['FlagEliminar'];
			} else {
				$_SHOW = "N";
				$_INSERT = "N";
				$_UPDATE = "S";
				$_DELETE = "N";
			}
		}
		return array($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE);
	}
}

//	FUNCION PARA CARGAR SELECTS
function loadSelect($tabla, $campo1, $campo2, $codigo, $opt) {
	switch ($opt) {
		case 0:
			$sql = "SELECT $campo1, $campo2 FROM $tabla WHERE Estado = 'A' ORDER BY $campo2";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			while ($field = mysql_fetch_array($query)) {
				if ($field[0] == $codigo) { ?><option value="<?=$field[0]?>" selected="selected"><?=$field[1]?></option><? }
				else { ?><option value="<?=$field[0]?>"><?=$field[1]?></option><? }
			}
			break;
			
		case 1:
			$sql = "SELECT $campo1, $campo2 FROM $tabla WHERE Estado = 'A' AND $campo1 = '$codigo'";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			while ($field = mysql_fetch_array($query)) {
				?><option value="<?=$field[0]?>" selected="selected"><?=$field[1]?></option><?
			}
			break;
			
		case 10:
			$sql = "SELECT $campo1, $campo2 FROM $tabla WHERE Estado = 'A' ORDER BY $campo1";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			while ($field = mysql_fetch_array($query)) {
				if ($field[0] == $codigo) { ?><option value="<?=$field[0]?>" selected="selected"><?=$field[0]?>&nbsp;&nbsp;&nbsp;<?=($field[1])?></option><? }
				else { ?><option value="<?=$field[0]?>"><?=$field[0]?>&nbsp;&nbsp;&nbsp;<?=$field[1]?></option><? }
			}
			break;
			
		case 11:
			$sql = "SELECT $campo1, $campo2 FROM $tabla WHERE Estado = 'A' AND $campo1 = '$codigo'";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			while ($field = mysql_fetch_array($query)) {
				?><option value="<?=$field[0]?>" selected="selected"><?=$field[0]?>&nbsp;&nbsp;&nbsp;<?=$field[1]?></option><?
			}
			break;
	}
}

//	FUNCION PARA CARGAR SELECTS
function loadSelectDependiente($tabla, $campo1, $campo2, $campo3, $codigo1, $codigo2, $opt) {
	switch ($opt) {
		case 0:
			$sql = "SELECT $campo1, $campo2 FROM $tabla WHERE Estado = 'A' AND $campo3 = '$codigo2' ORDER BY $campo1";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			while ($field = mysql_fetch_array($query)) {
				if ($field[0] == $codigo1) { ?><option value="<?=$field[0]?>" selected="selected"><?=($field[1])?></option><? }
				else { ?><option value="<?=$field[0]?>"><?=($field[1])?></option><? }
			}
			break;
			
		case 1:
			$sql = "SELECT $campo1, $campo2 FROM $tabla WHERE Estado = 'A' AND $campo1 = '$codigo1'";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			while ($field = mysql_fetch_array($query)) {
				?><option value="<?=$field[0]?>" selected="selected"><?=($field[1])?></option><?
			}
			break;
	}
}

//	FUNCION PARA CARGAR SELECTS
function loadSelectDependiente2($tabla, $campo1, $campo2, $campo3, $campo4, $codigo1, $codigo2, $codigo3, $opt) {
	switch ($opt) {
		case 0:
			$sql = "SELECT $campo1, $campo2 FROM $tabla WHERE Estado = 'A' AND $campo3 = '$codigo2' AND $campo4 = '$codigo3' ORDER BY $campo1";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			while ($field = mysql_fetch_array($query)) {
				if ($field[0] == $codigo1) { ?><option value="<?=$field[0]?>" selected="selected"><?=($field[1])?></option><? }
				else { ?><option value="<?=$field[0]?>"><?=($field[1])?></option><? }
			}
			break;
			
		case 1:
			$sql = "SELECT $campo1, $campo2 FROM $tabla WHERE Estado = 'A' AND $campo1 = '$codigo1'";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			while ($field = mysql_fetch_array($query)) {
				?><option value="<?=$field[0]?>" selected="selected"><?=($field[1])?></option><?
			}
			break;
	}
}

//	FUNCION PARA CARGAR SELECTS
function loadSelectDependienteEstado($codigo1, $codigo2, $opt) {
	switch ($opt) {
		case 0:
			$sql = "SELECT CodEstado, Estado FROM mastestados WHERE Status = 'A' AND CodPais = '$codigo2' ORDER BY CodEstado";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			while ($field = mysql_fetch_array($query)) {
				if ($field[0] == $codigo1) { ?><option value="<?=$field[0]?>" selected="selected"><?=($field[1])?></option><? }
				else { ?><option value="<?=$field[0]?>"><?=($field[1])?></option><? }
			}
			break;
			
		case 1:
			$sql = "SELECT CodEstado, Estado FROM mastestados WHERE $campo1 = '$codigo1'";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			while ($field = mysql_fetch_array($query)) {
				?><option value="<?=$field[0]?>" selected="selected"><?=($field[1])?></option><?
			}
			break;
	}
}

//	FUNCION PARA CARGAR SELECTS
function loadSelectDependienteSE($tabla, $campo1, $campo2, $campo3, $codigo1, $codigo2, $opt) {
	switch ($opt) {
		case 0:
			$sql = "SELECT $campo1, $campo2 FROM $tabla WHERE $campo3 = '$codigo2' ORDER BY $campo1";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			while ($field = mysql_fetch_array($query)) {
				if ($field[0] == $codigo1) { ?><option value="<?=$field[0]?>" selected="selected"><?=($field[1])?></option><? }
				else { ?><option value="<?=$field[0]?>"><?=($field[1])?></option><? }
			}
			break;
			
		case 1:
			$sql = "SELECT $campo1, $campo2 FROM $tabla WHERE $campo1 = '$codigo1'";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			while ($field = mysql_fetch_array($query)) {
				?><option value="<?=$field[0]?>" selected="selected"><?=($field[1])?></option><?
			}
			break;
	}
}
//	------------------------------

//	FUNCION PARA CARGAR SELECTS
function loadSelectAplicacion($codigo, $opt) {
	switch ($opt) {
		case 0:
			$sql = "SELECT CodAplicacion, Descripcion
					FROM mastaplicaciones
					WHERE
						Estado = 'A' AND
						(CodAplicacion = '".$_SESSION["APLICACION_ACTUAL"]."' OR
						 CodAplicacion = 'GE')
					ORDER BY CodAplicacion";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			while ($field = mysql_fetch_array($query)) {
				if ($field[0] == $codigo) { ?><option value="<?=$field[0]?>" selected="selected"><?=($field[1])?></option><? }
				else { ?><option value="<?=$field[0]?>"><?=($field[1])?></option><? }
			}
			break;
			
		case 1:
			$sql = "SELECT CodAplicacion, Descripcion
					FROM mastaplicaciones
					WHERE CodAplicacion = '".$codigo."'
					ORDER BY CodAplicacion";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			while ($field = mysql_fetch_array($query)) {
				?><option value="<?=$field[0]?>" selected="selected"><?=($field[1])?></option><?
			}
			break;
			
		case 10:
			$sql = "SELECT CodAplicacion, Descripcion
					FROM mastaplicaciones
					WHERE
						Estado = 'A' AND
						(CodAplicacion = '".$_SESSION["APLICACION_ACTUAL"]."' OR
						 CodAplicacion = 'GE')
					ORDER BY CodAplicacion";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			while ($field = mysql_fetch_array($query)) {
				if ($field[0] == $codigo) { ?><option value="<?=$field[0]?>" selected="selected"><?=$field[0]?>&nbsp;&nbsp;&nbsp;<?=($field[1])?></option><? }
				else { ?><option value="<?=$field[0]?>"><?=$field[0]?>&nbsp;&nbsp;&nbsp;<?=($field[1])?></option><? }
			}
			break;
			
		case 11:
			$sql = "SELECT CodAplicacion, Descripcion
					FROM mastaplicaciones
					WHERE CodAplicacion = '".$codigo."'
					ORDER BY CodAplicacion";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			while ($field = mysql_fetch_array($query)) {
				?><option value="<?=$field[0]?>" selected="selected"><?=$field[0]?>&nbsp;&nbsp;&nbsp;<?=($field[1])?></option><?
			}
			break;
	}
}

//	FUNCION PARA CARGAR EL MISCELANEO EN UN SELECT
function getMiscelaneos($detalle, $maestro, $opt) {
	switch ($opt) {
		case 0:
			$sql = "SELECT CodDetalle, Descripcion
					FROM mastmiscelaneosdet
					WHERE
						Estado = 'A' AND
						CodMaestro = '".$maestro."'
					ORDER BY CodDetalle";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			while ($field = mysql_fetch_array($query)) {
				if ($field[0] == $detalle) { ?><option value="<?=$field[0]?>" selected="selected"><?=($field[1])?></option><? }
				else { ?><option value="<?=$field[0]?>"><?=($field[1])?></option><? }
			}
			break;
			
		case 1:
			$sql = "SELECT CodDetalle, Descripcion
					FROM mastmiscelaneosdet
					WHERE
						CodDetalle = '".$detalle."' AND
						CodMaestro = '".$maestro."'
					ORDER BY CodDetalle";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			while ($field = mysql_fetch_array($query)) {
				?><option value="<?=$field[0]?>" selected="selected"><?=($field[1])?></option><?
			}
			break;
			
		case 10:
			$sql = "SELECT CodDetalle, Descripcion
					FROM mastmiscelaneosdet
					WHERE
						Estado = 'A' AND
						CodMaestro = '".$maestro."'
					ORDER BY CodDetalle";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			while ($field = mysql_fetch_array($query)) {
				if ($field[0] == $detalle) { ?><option value="<?=$field[0]?>" selected="selected"><?=$field[0]?>&nbsp;&nbsp;&nbsp;<?=($field[1])?></option><? }
				else { ?><option value="<?=$field[0]?>"><?=$field[0]?>&nbsp;&nbsp;&nbsp;<?=($field[1])?></option><? }
			}
			break;
			
		case 11:
			$sql = "SELECT CodDetalle, Descripcion
					FROM mastmiscelaneosdet
					WHERE
						CodDetalle = '".$detalle."' AND
						CodMaestro = '".$maestro."'
					ORDER BY CodDetalle";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			while ($field = mysql_fetch_array($query)) {
				?><option value="<?=$field[0]?>" selected="selected"><?=$field[0]?>&nbsp;&nbsp;&nbsp;<?=($field[1])?></option><?
			}
			break;
		case 12:
			$sql = "SELECT CodDetalle, Descripcion
					FROM mastmiscelaneosdet
					WHERE
						Estado = 'A' AND
						CodMaestro = '".$maestro."'
					ORDER BY CodDetalle";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			while ($field = mysql_fetch_array($query)) {
				if ($field[0] == $detalle) { ?><option value="<?=$field[1]?>" selected="selected"><?=($field[1])?></option><? }
				else { ?><option value="<?=$field[1]?>"><?=($field[1])?></option><? }
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS ORGANISMOS EN UN SELECT
function getOrganismos($organismo, $opt) {
	if ($opt==3 && $_SESSION["USUARIO_ACTUAL"]==$_SESSION["SUPER_USUARIO"]) $opt=0;
	switch ($opt) {
		case 0:
			$sql="SELECT CodOrganismo, Organismo FROM mastorganismos WHERE CodOrganismo<>'' ORDER BY CodOrganismo";
			$query=mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$organismo) echo "<option value='".$field[0]."' selected>".($field[1])."</option>"; 
				else echo "<option value='".$field[0]."'>".($field[1])."</option>";
			}
			break;
		case 1:
			$sql="SELECT CodOrganismo, Organismo FROM mastorganismos WHERE CodOrganismo='$organismo'";
			$query=mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field[0]."'>".($field[1])."</option>";
			}
			break;
		case 3:
			$sql="SELECT s.CodOrganismo, o.Organismo FROM seguridad_alterna s INNER JOIN mastorganismos o ON (s.CodOrganismo=o.CodOrganismo) WHERE s.Usuario='".$_SESSION["USUARIO_ACTUAL"]."' AND s.CodAplicacion='".$_SESSION["APLICACION_ACTUAL"]."' AND s.FlagMostrar='S' GROUP BY s.CodOrganismo ORDER BY s.CodOrganismo";
			$query=mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$organismo) echo "<option value='".$field[0]."' selected>".($field[1])."</option>"; 
				else echo "<option value='".$field[0]."'>".($field[1])."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LAS DEPENDENCIAS EN UN SELECT
function getDependencias($dependencia, $organismo, $opt) {
	if ($opt==3 && $_SESSION["USUARIO_ACTUAL"]==$_SESSION["SUPER_USUARIO"]) $opt=0;
	switch ($opt) {
		case 0:
			$sql="SELECT CodDependencia, Dependencia FROM mastdependencias WHERE CodOrganismo='".$organismo."' AND CodDependencia<>'' ORDER BY CodDependencia";
			$query=mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$dependencia) echo "<option value='".$field[0]."' selected>".($field[1])."</option>"; 
				else echo "<option value='".$field[0]."'>".($field[1])."</option>";
			}
			break;
		case 1:
			$sql="SELECT CodDependencia, Dependencia FROM mastdependencias WHERE CodDependencia='".$dependencia."'";
			$query=mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field[0]."'>".($field[1])."</option>";
			}
			break;
		case 3:
			$sql="SELECT s.CodDependencia, o.Dependencia FROM seguridad_alterna s INNER JOIN mastdependencias o ON (s.CodDependencia=o.CodDependencia) WHERE s.Usuario='".$_SESSION["USUARIO_ACTUAL"]."' AND s.CodAplicacion='".$_SESSION["APLICACION_ACTUAL"]."' AND s.FlagMostrar='S' AND s.CodOrganismo='$organismo' GROUP BY s.CodDependencia ORDER BY s.CodDependencia";
			$query=mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$dependencia) echo "<option value='".$field[0]."' selected>".($field[1])."</option>"; 
				else echo "<option value='".$field[0]."'>".($field[1])."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LAS DEPENDENCIAS EN UN SELECT
function loadDependenciaFiscal($dependencia, $organismo, $opt) {
	switch ($opt) {
		case 0:
			$sql = "SELECT
						s.CodDependencia,
						o.Dependencia
					FROM
						seguridad_alterna s
						INNER JOIN mastdependencias o ON (s.CodDependencia = o.CodDependencia)
					WHERE
						s.Usuario = '".$_SESSION["USUARIO_ACTUAL"]."' AND
						s.CodAplicacion = '".$_SESSION["APLICACION_ACTUAL"]."' AND
						s.FlagMostrar = 'S' AND
						s.CodOrganismo = '$organismo' AND
						o.FlagControlFiscal = 'S'
					GROUP BY s.CodDependencia
					ORDER BY s.CodDependencia";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			$rows = mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field = mysql_fetch_array($query);
				if ($field[0] == $dependencia) echo "<option value='".$field[0]."' selected>".($field[1])."</option>"; 
				else echo "<option value='".$field[0]."'>".($field[1])."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR SELECT PERIODOS DE NOMINA
function loadSelectNominaPeriodos($organismo, $nomina, $periodo) {
	global $_PARAMETRO;
	$sql = "SELECT Periodo 
			FROM pr_procesoperiodo 
			WHERE 
				CodOrganismo = '".$organismo."' AND 
				CodTipoNom = '".$nomina."' AND
				(CodTipoProceso = 'FIN' OR CodTipoProceso = 'PPA')
			GROUP BY Periodo
			ORDER BY Periodo DESC";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	while ($field = mysql_fetch_array($query)) {
		list($anio, $mes) = split("[-]", $field['Periodo']);
		if ($grupo != $anio) {
			$grupo = $anio;
			?><optgroup label="<?=$anio?>"><?=$anio?></optgroup><?
		}
		
		if ($field[0] == $periodo) { ?><option value="<?=$field[0]?>" selected="selected"><?=$field[0]?></option><? }
		else { ?><option value="<?=$field[0]?>"><?=$field[0]?></option><? }
	}
}

//	FUNCION PARA CARGAR SELECTS
function loadSelectPeriodosNomina($Periodo, $CodOrganismo, $CodTipoNom, $opt) {
	switch ($opt) {
		case 1:
			$sql = "SELECT Periodo
					FROM pr_procesoperiodo
					WHERE
						CodTipoNom = '".$CodTipoNom."' AND
						CodOrganismo = '".$CodOrganismo."' AND
						Estado = 'A' AND
						FlagProcesado = 'N' AND
						FlagAprobado = 'S'
					GROUP BY Periodo
					ORDER BY Periodo DESC";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			while ($field = mysql_fetch_array($query)) {
				if ($field[0] == $Periodo) { ?><option value="<?=$field[0]?>" selected="selected"><?=$field[0]?></option><? }
				else { ?><option value="<?=$field[0]?>"><?=$field[0]?></option><? }
			}
			break;
	}
}

//	FUNCION PARA CARGAR SELECTS
function loadSelectPeriodosNominaProcesos($CodTipoProceso, $Periodo, $CodOrganismo, $CodTipoNom, $opt) {
	switch ($opt) {
		case 1:
			$sql = "SELECT
						pp.CodTipoProceso,
						tp.Descripcion
					FROM
						pr_procesoperiodo pp
						INNER JOIN pr_tipoproceso tp ON (tp.CodTipoProceso = pp.CodTipoProceso)
					WHERE
						pp.Periodo = '".$Periodo."' AND
						pp.CodTipoNom = '".$CodTipoNom."' AND
						pp.CodOrganismo = '".$CodOrganismo."' AND
						pp.Estado = 'A' AND
						pp.FlagProcesado = 'N' AND
						pp.FlagAprobado = 'S'
					ORDER BY Descripcion";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			while ($field = mysql_fetch_array($query)) {
				if ($field[0] == $CodTipoProceso) { ?><option value="<?=$field[0]?>" selected="selected"><?=($field[1])?></option><? }
				else { ?><option value="<?=$field[0]?>"><?=htmlentities($field[1])?></option><? }
			}
			break;
	}
}

//	FUNCION PARA CARGAR LAS DEPENDENCIAS EN UN SELECT
function loadSelectTipoServicioDocumento($CodTipoDocumento, $opt) {
	switch ($opt) {
		case 0:
			$sql = "SELECT ts.CodTipoServicio, ts.Descripcion
					FROM
						masttiposervicio ts
						INNER JOIN ap_tipodocumento td ON (td.CodRegimenFiscal = ts.CodRegimenFiscal)
					WHERE td.CodTipoDocumento = '".$CodTipoDocumento."'";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			$rows = mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field = mysql_fetch_array($query);
				echo "<option value='".$field[0]."'>".($field[1])."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LAS DEPENDENCIAS EN UN SELECT
function loadSelectAlmacen($CodAlmacen, $FlagCommodity, $opt) {
	if ($FlagCommodity != "") $filtro = "AND FlagCommodity = '".$FlagCommodity."'";
	switch ($opt) {
		case 0:
			$sql = "SELECT CodAlmacen, Descripcion
					FROM lg_almacenmast
					WHERE Estado = 'A' $filtro
					ORDER BY CodAlmacen";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			while ($field = mysql_fetch_array($query)) {
				if ($field[0] == $CodAlmacen) { ?><option value="<?=$field[0]?>" selected="selected"><?=($field[1])?></option><? }
				else { ?><option value="<?=$field[0]?>"><?=($field[1])?></option><? }
			}
			break;
			
		case 1:
			$sql = "SELECT CodAlmacen, Descripcion
					FROM lg_almacenmast
					WHERE CodAlmacen = '".$CodAlmacen."' AND Estado = 'A' $filtro
					ORDER BY CodAlmacen";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			while ($field = mysql_fetch_array($query)) {
				?><option value="<?=$field[0]?>" selected="selected"><?=($field[1])?></option><?
			}
			break;
	}
}

//	FUNCION PARA CARGAR SELECTS 
function loadSelectGeneral($tabla, $codigo, $opt) {
	switch ($tabla) {
		case "ESTADO":
			$c[0] = "A"; $v[0] = "Activo";
			$c[1] = "I"; $v[1] = "Inactivo";
			break;
			
		case "ORDENAR-PERSONA":
			$c[0] = "p.CodPersona"; $v[0] = "C&oacute;digo";
			$c[1] = "p.NomCompleto"; $v[1] = "Nombre Completo";
			$c[2] = "p.Ndocumento"; $v[2] = "Nro. Documento";
			$c[3] = "p.Estado"; $v[3] = "Estado";
			break;
			
		case "ORDENAR-CCOSTO":
			$c[0] = "cc.CodCentroCosto"; $v[0] = "C&oacute;digo";
			$c[1] = "cc.Descripcion"; $v[1] = "Descripci&oacute;n";
			$c[2] = "gcc.Descripcion"; $v[2] = "Grupo";
			$c[3] = "sgcc.Descripcion"; $v[3] = "Sub-Grupo";
			$c[4] = "cc.Estado"; $v[4] = "Estado";
			break;
			
		case "ORDENAR-PARAMETRO":
			$c[0] = "p.ParametroClave"; $v[0] = "C&oacute;digo";
			$c[1] = "p.DescripcionParam"; $v[1] = "Descripci&oacute;n";
			$c[2] = "p.TipoValor"; $v[2] = "Tipo";
			$c[3] = "p.Estado"; $v[3] = "Estado";
			break;
			
		case "ORDENAR-APLICACION":
			$c[0] = "a.CodAplicacion"; $v[0] = "C&oacute;digo";
			$c[1] = "a.Descripcion"; $v[1] = "Descripci&oacute;n";
			$c[2] = "a.PeriodoContable"; $v[2] = "Periodo Contable";
			$c[3] = "a.Estado"; $v[3] = "Estado";
			break;
			
		case "ORDENAR-ORGANISMO":
			$c[0] = "o.CodOrganismo"; $v[0] = "C&oacute;digo";
			$c[1] = "o.Descripcion"; $v[1] = "Descripci&oacute;n";
			$c[2] = "o.CodPersona"; $v[2] = "Tipo";
			$c[3] = "o.RepresentLegal"; $v[3] = "Representante Legal";
			$c[4] = "o.Estado"; $v[4] = "Estado";
			break;
			
		case "ORDENAR-DEPENDENCIA":
			$c[0] = "d.Estructura"; $v[0] = "C&oacute;digo";
			$c[1] = "d.Dependencia"; $v[1] = "Descripci&oacute;n";
			$c[2] = "d.Estado"; $v[2] = "Estado";
			break;
			
		case "ORDENAR-PLANCUENTAS":
			$c[0] = "pc.CodCuenta"; $v[0] = "C&oacute;digo";
			$c[1] = "pc.Descripcion"; $v[1] = "Descripci&oacute;n";
			$c[2] = "pc.TipoCuenta"; $v[2] = "Tipo de Cuenta";
			$c[3] = "pc.TipoSaldo"; $v[3] = "Naturaleza";
			$c[4] = "pc.Estado"; $v[4] = "Estado";
			break;
			
		case "ORDENAR-GRUPOCC":
			$c[0] = "gcc.CodGrupoCentroCosto"; $v[0] = "C&oacute;digo";
			$c[1] = "gcc.Descripcion"; $v[1] = "Descripci&oacute;n";
			$c[2] = "gcc.Estado"; $v[2] = "Estado";
			break;
			
		case "ORDENAR-CENTROCOSTO":
			$c[0] = "cc.CodCentroCosto"; $v[0] = "C&oacute;digo";
			$c[1] = "cc.Descripcion"; $v[1] = "Descripci&oacute;n";
			$c[2] = "cc.Estado"; $v[2] = "Estado";
			break;
			
		case "ORDENAR-TIPOCUENTA":
			$c[0] = "tc.cod_tipocuenta"; $v[0] = "C&oacute;digo";
			$c[1] = "tc.descp_tipocuenta"; $v[1] = "Descripci&oacute;n";
			break;
			
		case "ORDENAR-CLASIFICADOR":
			$c[0] = "p.cod_partida"; $v[0] = "C&oacute;digo";
			$c[1] = "p.denominacion"; $v[1] = "Descripci&oacute;n";
			break;
			
		case "ORDENAR-ORGANISMO-EXTERNO":
			$c[0] = "CodOrganismo"; $v[0] = "C&oacute;digo";
			$c[1] = "Organismo"; $v[1] = "Descripci&oacute;n";
			$c[2] = "Estado"; $v[2] = "Estado";
			break;
			
		case "ORDENAR-DEPENDENCIA-EXTERNA":
			$c[0] = "CodDependencia"; $v[0] = "C&oacute;digo";
			$c[1] = "Dependencia"; $v[1] = "Descripci&oacute;n";
			$c[2] = "Estado"; $v[2] = "Estado";
			break;
			
		case "ORDENAR-ENTE":
			$c[0] = "CodDependencia"; $v[0] = "C&oacute;digo";
			$c[1] = "Dependencia"; $v[1] = "Descripci&oacute;n";
			$c[2] = "Estado"; $v[2] = "Estado";
			break;
			
		case "BUSCAR-ITEMS":
			$c[0] = "i.CodItem"; $v[0] = "Código";
			$c[1] = "i.Descripcion"; $v[1] = "Descripción";
			$c[2] = "i.CodLinea"; $v[2] = "Linea";
			$c[3] = "i.CodFamilia"; $v[3] = "Familia";
			$c[4] = "i.CodSubFamilia"; $v[4] = "Sub-Familia";
			$c[5] = "i.CodInterno"; $v[5] = "Cod. Interno";
			break;
			
		case "BUSCAR-EMPLEADOS":
			$c[0] = "mp.Apellido1"; $v[0] = "Apellido Paterno";
			$c[1] = "mp.Apellido2"; $v[1] = "Apellido Materno";
			$c[2] = "mp.Nombres"; $v[2] = "Nombre";
			$c[3] = "mp.Busqueda"; $v[3] = "Nombre Búsqueda";
			$c[4] = "mp.NomCompleto"; $v[4] = "Nombre Completo";
			break;
			
		case "ORDENAR-EMPLEADOS":
			$c[0] = "e.CodEmpleado"; $v[0] = "Código";
			$c[1] = "p.NomCompleto"; $v[1] = "Nombre Completo";
			$c[2] = "p.Ndocumento"; $v[2] = "Nro. Documento";
			$c[3] = "e.Fingreso"; $v[3] = "Fecha de Ingreso";
			$c[4] = "d.Dependencia"; $v[4] = "Dependencia";
			break;
			
		case "COMPARATIVOS":
			$c[0] = "="; $v[0] = "=";
			$c[1] = "&lt;"; $v[1] = "&lt;";
			$c[2] = "&gt;"; $v[2] = "&gt;";
			$c[3] = "&lt;="; $v[3] = "&lt;=";
			$c[4] = "&gt;="; $v[4] = "&gt;=";
			$c[5] = "&lt;&gt;"; $v[5] = "&lt;&gt;";
			break;
			
		case "CLASE-PERSONA":
			$c[0] = "N"; $v[0] = "Natural";
			$c[1] = "J"; $v[1] = "Jurídica";
			break;
			
		case "SEXO":
			$c[0] = "M"; $v[0] = "MASCULINO";
			$c[1] = "F"; $v[1] = "FEMENINO";
			break;
			
		case "MAXLIMIT":
			$c[0] = "100"; $v[0] = "100";
			$c[1] = "250"; $v[1] = "250";
			$c[2] = "500"; $v[2] = "500";
			$c[3] = "1000"; $v[3] = "1000";
			break;
			
		case "TIPO-SALDO":
			$c[0] = "D"; $v[0] = "Deudora";
			$c[1] = "A"; $v[1] = "Acreedora";
			break;
			
		case "NIVEL-CUENTA":
			$c[0] = "1"; $v[0] = "Grupo";
			$c[1] = "2"; $v[1] = "Sub-Grupo";
			$c[2] = "3"; $v[2] = "Rubro";
			$c[3] = "4"; $v[3] = "Cuenta";
			$c[4] = "5"; $v[4] = "Sub-Cuenta de Primer Orden";
			$c[5] = "6"; $v[5] = "Sub-Cuenta de Segundo Orden";
			$c[6] = "7"; $v[6] = "Sub-Cuenta Anexa";
			break;
			
		case "ESTADO-ACTUACION":
			$c[0] = "PR"; $v[0] = "En Preparación";
			$c[1] = "RV"; $v[1] = "Revisada";
			$c[2] = "AP"; $v[2] = "Aprobada";
			$c[3] = "TE"; $v[3] = "Terminada";
			$c[4] = "CO"; $v[4] = "Completada";
			$c[5] = "AN"; $v[5] = "Anulada";
			$c[6] = "CE"; $v[6] = "Cerrada";
			break;
			
		case "ESTADO-ACTUACION-PRORROGAS":
			$c[0] = "AP"; $v[0] = "Aprobada";
			$c[1] = "TE"; $v[1] = "Terminada";
			break;
			
		case "ESTADO-VALORACION":
			$c[0] = "PR"; $v[0] = "En Preparación";
			$c[1] = "RV"; $v[1] = "Revisada";
			$c[2] = "AP"; $v[2] = "Aprobada";
			$c[3] = "AC"; $v[3] = "Auto de Proceder";
			$c[4] = "AA"; $v[4] = "Auto de Archivo";
			$c[5] = "CE"; $v[5] = "Cerrada";
			break;
			
		case "ESTADO-ACTUACION-GENERAR":
			$c[0] = "TE"; $v[0] = "Terminada";
			$c[1] = "CO"; $v[1] = "Completada";
			break;
			
		case "ESTADO-VALORACION-GENERAR":
			$c[0] = "AA"; $v[0] = "Auto de Archivo";
			break;
			
		case "ORDENAR-ACTUACION":
			$c[0] = "af.Anio, af.Secuencia, af.CodActuacion"; $v[0] = "C&oacute;digo";
			$c[1] = "oe.Organismo, de.Dependencia"; $v[1] = "Ente Externo";
			$c[2] = "af.ObjetivoGeneral"; $v[2] = "Objetivo General";
			$c[3] = "af.FechaRegistro"; $v[3] = "Fecha de Registro";
			$c[4] = "af.Estado"; $v[4] = "Estado";
			break;
			
		case "ORDENAR-VALORACION":
			$c[0] = "vj.Anio, vj.Secuencia, vj.CodValJur"; $v[0] = "C&oacute;digo";
			$c[1] = "oe.Organismo, de.Dependencia"; $v[1] = "Ente Externo";
			$c[2] = "vj.ObjetivoGeneral"; $v[2] = "Objetivo General";
			$c[3] = "vj.FechaRegistro"; $v[3] = "Fecha de Registro";
			$c[4] = "vj.Estado"; $v[4] = "Estado";
			break;
			
		case "ORDENAR-POTESTAD":
			$c[0] = "vj.Anio, vj.Secuencia, vj.CodPotestad"; $v[0] = "C&oacute;digo";
			$c[1] = "oe.Organismo, de.Dependencia"; $v[1] = "Ente Externo";
			$c[2] = "vj.ObjetivoGeneral"; $v[2] = "Objetivo General";
			$c[3] = "vj.FechaRegistro"; $v[3] = "Fecha de Registro";
			$c[4] = "vj.Estado"; $v[4] = "Estado";
			break;
			
		case "ORDENAR-DETERMINACION":
			$c[0] = "vj.Anio, vj.Secuencia, vj.CodDeterminacion"; $v[0] = "C&oacute;digo";
			$c[1] = "oe.Organismo, de.Dependencia"; $v[1] = "Ente Externo";
			$c[2] = "vj.ObjetivoGeneral"; $v[2] = "Objetivo General";
			$c[3] = "vj.FechaRegistro"; $v[3] = "Fecha de Registro";
			$c[4] = "vj.Estado"; $v[4] = "Estado";
			break;
			
		case "ORDENAR-ACTIVIDADES":
			$c[0] = "a.CodActividad"; $v[0] = "C&oacute;digo";
			$c[1] = "a.Descripcion"; $v[1] = "Descripci&oacute;n";
			$c[2] = "a.Estado"; $v[2] = "Estado";
			break;
			
		case "ORDENAR-PAISES":
			$c[0] = "p.CodPais"; $v[0] = "C&oacute;digo";
			$c[1] = "p.Pais"; $v[1] = "Descripci&oacute;n";
			$c[2] = "p.Estado"; $v[2] = "Estado";
			break;
			
		case "ORDENAR-ESTADOS":
			$c[0] = "e.CodEstado"; $v[0] = "C&oacute;digo";
			$c[1] = "e.Estado"; $v[1] = "Descripci&oacute;n";
			$c[2] = "e.Status"; $v[2] = "Estado";
			break;
			
		case "ORDENAR-MUNICIPIOS":
			$c[0] = "m.CodMunicipio"; $v[0] = "C&oacute;digo";
			$c[1] = "m.Municipio"; $v[1] = "Descripci&oacute;n";
			$c[2] = "m.Estado"; $v[2] = "Estado";
			break;
			
		case "ORDENAR-CIUDADES":
			$c[0] = "c.CodCiudad"; $v[0] = "C&oacute;digo";
			$c[1] = "c.Ciudad"; $v[1] = "Descripci&oacute;n";
			$c[2] = "c.Estado"; $v[2] = "Estado";
			break;
			
		case "ORDENAR-TIPOPAGO":
			$c[0] = "tp.CodTipoPago"; $v[0] = "C&oacute;digo";
			$c[1] = "tp.TipoPago"; $v[1] = "Descripci&oacute;n";
			$c[2] = "tp.Estado"; $v[2] = "Estado";
			break;
			
		case "ORDENAR-BANCO":
			$c[0] = "b.CodBanco"; $v[0] = "C&oacute;digo";
			$c[1] = "b.Banco"; $v[1] = "Descripci&oacute;n";
			$c[2] = "b.Estado"; $v[2] = "Estado";
			break;
			
		case "ORDENAR-USUARIO":
			$c[0] = "u.Usuario"; $v[0] = "Usuario";
			$c[1] = "p.NomCompleto"; $v[1] = "Persona";
			$c[2] = "u.FechaExpirar"; $v[2] = "Fecha Expira";
			$c[3] = "u.Estado"; $v[3] = "Estado";
			break;
			
		case "ORDENAR-MISCELANEO":
			$c[0] = "mm.CodMaestro"; $v[0] = "Maestro";
			$c[1] = "mm.Descripcion"; $v[1] = "Descripci&oacute;n";
			$c[2] = "u.Estado"; $v[2] = "Estado";
			break;
			
		case "ORDENAR-IMPUESTO":
			$c[0] = "i.CodImpuesto"; $v[0] = "Código";
			$c[1] = "i.Descripcion"; $v[1] = "Descripción";
			$c[2] = "i.Estado"; $v[2] = "Estado";
			break;
			
		case "ESTADO-DOCUMENTOS":
			$c[0] = "PR"; $v[0] = "Pendiente";
			$c[1] = "RV"; $v[1] = "Facturado";
			break;
			
		case "PRIORIDAD":
			$c[0] = "N"; $v[0] = "Normal";
			$c[1] = "U"; $v[1] = "Urgente";
			$c[2] = "M"; $v[2] = "Muy Urgente";
			break;
			
		case "ORDENAR-CLASIFICACION-ACTIVO":
			$c[0] = "CodClasificacion"; $v[0] = "Código";
			$c[1] = "Descripcion"; $v[1] = "Descripción";
			break;
			
		case "ORDENAR-LINEAS":
			$c[0] = "CodLinea"; $v[0] = "Código";
			$c[1] = "Descripcion"; $v[1] = "Descripción";
			break;
		
		case "ORDENAR-ITEMS":
			$c[0] = "CodItem"; $v[0] = "Item";
			$c[1] = "CodInterno"; $v[1] = "Código";
			$c[2] = "Descripcion"; $v[2] = "Descripción";
			$c[3] = "CodUnidad"; $v[3] = "Unidad";
			$c[4] = "CodLinea, CodFamilia, CodSubFamilia"; $v[4] = "CodLinea/CodFamilia/CodSubFamilia";
			$c[5] = "Estado"; $v[5] = "Estado";
			break;
		
		case "DIRIGIDO":
			$c[0] = "C"; $v[0] = "Compras";
			$c[1] = "A"; $v[1] = "Almacen";
			break;
			
		case "ESTADO-COMPRA":
			$c[0] = "PR"; $v[0] = "En Preparacion";
			$c[1] = "RV"; $v[1] = "Revisada";
			$c[2] = "AP"; $v[2] = "Aprobada";
			$c[3] = "AN"; $v[3] = "Anulada";
			$c[4] = "RE"; $v[4] = "Rechazada";
			$c[5] = "CE"; $v[5] = "Cerrada";
			$c[6] = "CO"; $v[6] = "Completada";
			break;
			
		case "ESTADO-COMPRA-DETALLE":
			$c[0] = "PR"; $v[0] = "En Preparacion";
			$c[1] = "PE"; $v[1] = "Pendiente";
			$c[2] = "RE"; $v[2] = "Rechazada";
			$c[3] = "CE"; $v[3] = "Cerrada";
			$c[4] = "CO"; $v[4] = "Completada";
			break;
		
		case "ESTADO-SERVICIO":
			$c[0] = "PR"; $v[0] = "En Preparación";
			$c[1] = "RV"; $v[1] = "Revisado";
			$c[2] = "AP"; $v[2] = "Aprobado";
			$c[3] = "AN"; $v[3] = "Anulado";
			$c[4] = "RE"; $v[4] = "Rechazado";
			$c[5] = "CE"; $v[5] = "Cerrado";
			$c[6] = "CO"; $v[6] = "Completado";
			break;
		
		case "ESTADO-SERVICIO-DETALLE":
			$c[0] = "N"; $v[0] = "Pendiente";
			$c[1] = "S"; $v[1] = "Completado";
			break;
		
		case "TIPO-MOVIMIENTO-TRANSACCION":
			$c[0] = "I"; $v[0] = "Ingreso";
			$c[1] = "E"; $v[1] = "Egreso";
			$c[2] = "T"; $v[2] = "Transferencia";
			break;
		
		case "FLAG":
			$c[0] = "S"; $v[0] = "Si";
			$c[1] = "N"; $v[1] = "No";
			break;

		case "ESTADO-POSTULANTE":
			$c[0] = "P"; $v[0] = "Postulante";
			$c[1] = "A"; $v[1] = "Aceptado";
			$c[2] = "C"; $v[2] = "Contratado";
			$c[3] = "D"; $v[3] = "Descalificado";
			break;

		case "DIA-SEMANA":
			$c[0] = "01"; $v[0] = "Lunes";
			$c[1] = "02"; $v[1] = "Martes";
			$c[2] = "03"; $v[2] = "Miercoles";
			$c[3] = "04"; $v[3] = "Jueves";
			$c[4] = "05"; $v[4] = "Viernes";
			$c[5] = "06"; $v[5] = "Sabado";
			$c[6] = "07"; $v[6] = "Domingo";
			break;

		case "HORA-12":
			$j=0;
			for($i=0;$i<12;$i++) {
				++$j;
				if ($j<10) $valor = "0$j"; else $valor = "$j";
				$c[$i] = "$valor"; $v[$i] = "$valor";
			}
			break;

		case "MINUTO":
			for($i=0;$i<60;$i++) {
				if ($i<10) $valor = "0$i"; else $valor = "$i";
				$c[$i] = "$valor"; $v[$i] = "$valor";
			}
			break;

		case "MES":
			$j=0;
			for($i=0;$i<12;$i++) {
				++$j;
				if ($j<10) $valor = "0$j"; else $valor = "$j";
				$c[$i] = "$valor"; $v[$i] = "$valor";
			}
			break;
			
		case "ESTADO-PERMISOS":
			$c[0] = "P"; $v[0] = "Pendiente";
			$c[1] = "A"; $v[1] = "Aprobado";
			$c[2] = "N"; $v[2] = "Anulado";
			break;
	}
	
	$i = 0;
	switch ($opt) {
		case 0:
			foreach ($c as $cod) {
				if ($cod == $codigo) echo "<option value='".$cod."' selected>".$v[$i]."</option>";
				else echo "<option value='".$cod."'>".$v[$i]."</option>";
				$i++;
			}
			break;
			
		case 1:
			foreach ($c as $cod) {
				if ($cod == $codigo) echo "<option value='".$cod."' selected>".$v[$i]."</option>";
				$i++;
			}
			break;
	}
}

//	FUNCION PARA CARGAR SELECTS 
function getFechaEventos($FechaInicio, $FechaFin, $Fecha, $opt) {
	$nro_dias = getFechaDias($FechaInicio, $FechaFin) + 1;
	$fi = $FechaInicio;
	for($i=0;$i<$nro_dias;$i++) {
		$c[$i] = $fi; $v[$i] = $fi;
		$fi = obtenerFechaFin($fi, 2);
	}
	
	$i = 0;
	switch ($opt) {
		case 0:
			foreach ($c as $cod) {
				if ($cod == $Fecha) echo "<option value='".$cod."' selected>".$v[$i]."</option>";
				else echo "<option value='".$cod."'>".$v[$i]."</option>";
				$i++;
			}
			break;
			
		case 1:
			foreach ($c as $cod) {
				if ($cod == $Fecha) echo "<option value='".$cod."' selected>".$v[$i]."</option>";
				$i++;
			}
			break;
	}
}

//	FUNCION PARA CARGAR SELECTS
function loadSelectTipoDocumentoCxP($codigo, $FlagAutoNomina, $opt) {
	if ($FlagAutoNomina != "") $filtro = " AND td.FlagAutoNomina = '".$FlagAutoNomina."'";
	switch ($opt) {
		case 0:
			$sql = "SELECT
						td.CodTipoDocumento,
						td.Descripcion,
						td.CodRegimenFiscal,
						rf.Descripcion AS NomRegimenFiscal
					FROM
						ap_tipodocumento td
						INNER JOIN ap_regimenfiscal rf ON (rf.CodRegimenFiscal = td.CodRegimenFiscal)
					WHERE
						td.Estado = 'A'
						$filtro
					ORDER BY NomRegimenFiscal, CodRegimenFiscal, Descripcion";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));	$i=0;
			while ($field = mysql_fetch_array($query)) {
				if ($Grupo != $field['CodRegimenFiscal']) {
					$Grupo = $field['CodRegimenFiscal'];
					if ($i==0) { ?></optgroup><? }
					?><optgroup label="<?=($field['NomRegimenFiscal'])?>"><?
				}
				if ($field[0] == $codigo) { ?><option value="<?=$field[0]?>" selected="selected"><?=($field[1])?></option><? }
				else { ?><option value="<?=$field[0]?>"><?=($field[1])?></option><? }
				++$i;
			}
			?></optgroup><?
			break;
			
		case 1:
			$sql = "SELECT
						td.CodTipoDocumento,
						td.Descripcion,
						td.CodRegimenFiscal,
						rf.Descripcion AS NomRegimenFiscal
					FROM
						ap_tipodocumento td
						INNER JOIN ap_regimenfiscal rf ON (rf.CodRegimenFiscal = td.CodRegimenFiscal)
					WHERE
						td.Estado = 'A' AND
						td.CodTipoDocumento = '$codigo'
						$filtro
					ORDER BY NomRegimenFiscal, CodRegimenFiscal, Descripcion";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			while ($field = mysql_fetch_array($query)) {
				?>
                <optgroup label="<?=($field['NomRegimenFiscal'])?>">
                <option value="<?=$field[0]?>" selected="selected"><?=($field[1])?></option>
                </optgroup>
				<?
			}
			break;
			
		case 10:
			$sql = "SELECT
						td.CodTipoDocumento,
						td.Descripcion,
						td.CodRegimenFiscal,
						rf.Descripcion AS NomRegimenFiscal
					FROM
						ap_tipodocumento td
						INNER JOIN ap_regimenfiscal rf ON (rf.CodRegimenFiscal = td.CodRegimenFiscal)
					WHERE
						td.Estado = 'A'
						$filtro
					ORDER BY NomRegimenFiscal, CodRegimenFiscal, Descripcion";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));	$i=0;
			while ($field = mysql_fetch_array($query)) {
				if ($Grupo != $field['CodRegimenFiscal']) {
					$Grupo = $field['CodRegimenFiscal'];
					if ($i==0) { ?></optgroup><? }
					?><optgroup label="<?=($field['NomRegimenFiscal'])?>"><?
				}
				if ($field[0] == $codigo) { ?><option value="<?=$field[0]?>" selected="selected"><?=$field[0]?>&nbsp;&nbsp;&nbsp;<?=htmlentities($field[1])?></option><? }
				else { ?><option value="<?=$field[0]?>"><?=$field[0]?>&nbsp;&nbsp;&nbsp;<?=($field[1])?></option><? }
				++$i;
				
			}
			break;
			
		case 11:
			$sql = "SELECT
						td.CodTipoDocumento,
						td.Descripcion,
						td.CodRegimenFiscal,
						rf.Descripcion AS NomRegimenFiscal
					FROM
						ap_tipodocumento td
						INNER JOIN ap_regimenfiscal rf ON (rf.CodRegimenFiscal = td.CodRegimenFiscal)
					WHERE
						td.Estado = 'A' AND
						td.CodTipoDocumento = '$codigo'
						$filtro
					ORDER BY NomRegimenFiscal, CodRegimenFiscal, Descripcion";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			while ($field = mysql_fetch_array($query)) {
				?>
                <optgroup label="<?=($field['NomRegimenFiscal'])?>">
                <option value="<?=$field[0]?>" selected="selected"><?=$field[0]?>&nbsp;&nbsp;&nbsp;<?=($field[1])?></option>
                </optgroup>
				<?
			}
			break;
	}
}

//	FUNCION PARA CARGAR SELECTS
function loadSelectProfesiones($CodProfesion, $CodGradoInstruccion, $Area, $opt) {
	switch ($opt) {
		case 0:
			$sql = "SELECT CodProfesion, Descripcion 
					FROM rh_profesiones 
					WHERE Estado = 'A' AND CodGradoInstruccion = '".$CodGradoInstruccion."' AND Area = '".$Area."' 
					ORDER BY Descripcion";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			while ($field = mysql_fetch_array($query)) {
				if ($field[0] == $CodProfesion) { ?><option value="<?=$field[0]?>" selected="selected"><?=htmlentities($field[1])?></option><? }
				else { ?><option value="<?=$field[0]?>"><?=htmlentities($field[1])?></option><? }
			}
			break;
			
		case 1:
			$sql = "SELECT CodProfesion, Descripcion 
					FROM rh_profesiones 
					WHERE Estado = 'A' AND CodGradoInstruccion = '".$CodGradoInstruccion."' AND Area = '".$Area."' AND CodProfesion = '".$CodProfesion."'";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			while ($field = mysql_fetch_array($query)) {
				?><option value="<?=$field[0]?>" selected="selected"><?=htmlentities($field[1])?></option><?
			}
			break;
	}
}
//	------------------------------

//	FUNCION PARA CARGAR SELECTS
function loadSelectPeriodosBono($Periodo, $CodOrganismo, $CodTipoNom, $opt) {
	$sql = "SELECT Periodo, Anio, CodBonoAlim
			FROM rh_bonoalimentacion
			WHERE
				CodOrganismo = '".$CodOrganismo."' AND
				CodTipoNom = '".$CodTipoNom."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	while ($field = mysql_fetch_array($query)) {
		if ($field[0] == $Periodo) { ?><option value="<?=$field[2]?>" selected="selected"><?=$field[0]?></option><? }
		else { ?><option value="<?=$field[2]?>"><?=$field[0]?></option><? }
	}
}
//	------------------------------

//	FUNCION PARA CARGAR SELECTS
function loadSelectSemanasBono($Semana, $Periodo, $CodOrganismo, $CodTipoNom, $opt) {
	//	consulto los dias
	$sql = "SELECT
				ba.FechaInicio,
				ba.FechaFin,
				ba.TotalDiasPeriodo AS DiasPeriodo
			FROM rh_bonoalimentacion ba
			WHERE
				ba.CodBonoAlim = '".$Periodo."' AND
				ba.CodOrganismo = '".$CodOrganismo."' AND
				ba.CodTipoNom = '".$CodTipoNom."'";
	$query_dias = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query_dias) != 0) $field_dias = mysql_fetch_array($query_dias);
	
	//	obtengo el nro de semanas
	$swSemana = true;
	$ns = 0;
	$nro_semanas = 0;
	$fi = formatFechaDMA($field_dias['FechaInicio']);
	while($swSemana) {
		++$ns;
		++$nro_semanas;
		$dsemana = getWeekDay($fi);
		$dias_semana = 7 - $dsemana + 1;
		$ff = obtenerFechaFin($fi, $dias_semana);
		if (formatFechaAMD($ff) >= $field_dias['FechaFin']) { $ff = formatFechaDMA($field_dias['FechaFin']); $swSemana = false; }
		$ttl[$ns] = "$fi|$ff";
		$fechai[$ns] = $fi;
		$fechaf[$ns] = $ff;
		$fi = obtenerFechaFin($ff, 2);
	}
	
	//	semanas
	for($ns=1;$ns<=$nro_semanas;$ns++) {
		?><option value="<?=$ttl[$ns]?>"><?=$ttl[$ns]?></option><?
	}
}
//	------------------------------

//	FUNCION PARA IMPRIMIR EN UNA TABLA VALORES
function printValoresGeneral($tabla, $codigo) {
	switch ($tabla) {
		case "ESTADO":
			$c[0] = "A"; $v[0] = "Activo";
			$c[1] = "I"; $v[1] = "Inactivo";
			break;
			
		case "ESTADO-PERMISOS":
			$c[0] = "P"; $v[0] = "Pendiente";
			$c[1] = "A"; $v[1] = "Aprobado";
			$c[2] = "N"; $v[2] = "Anulado";
			break;
			
		case "SEXO":
			$c[0] = "M"; $v[0] = "Masculino";
			$c[1] = "F"; $v[1] = "Femenino";
			break;
			
		case "NACIONALIDAD":
			$c[0] = "N"; $v[0] = "Nacional";
			$c[1] = "E"; $v[1] = "Extranjero";
			break;
			
		case "ESTADO-OBLIGACIONES":
			$c[0] = "PR"; $v[0] = "En Preparacion";
			$c[1] = "RV"; $v[1] = "Revisada";
			$c[2] = "AP"; $v[2] = "Aprobada";
			$c[3] = "AN"; $v[3] = "Anulada";
			$c[4] = "PA"; $v[4] = "Pagada";
			break;
			
		case "CLASE-PERSONA":
			$c[0] = "N"; $v[0] = "Natural";
			$c[1] = "J"; $v[1] = "Jurídica";
			break;
			
		case "TIPO-CAMPO":
			$c[0] = "T"; $v[0] = "Texto";
			$c[1] = "N"; $v[1] = "Número";
			$c[2] = "F"; $v[2] = "Fecha";
			break;
			
		case "TIPO-SALDO":
			$c[0] = "D"; $v[0] = "Deudora";
			$c[1] = "A"; $v[1] = "Acreedora";
			break;
			
		case "PRIORIDAD":
			$c[0] = "N"; $v[0] = "Normal";
			$c[1] = "U"; $v[1] = "Urgente";
			$c[2] = "M"; $v[2] = "Muy Urgente";
			break;
			
		case "ESTADO-ACTUACION":
			$c[0] = "PR"; $v[0] = "En Preparación";
			$c[1] = "RV"; $v[1] = "Revisada";
			$c[2] = "AP"; $v[2] = "Aprobada";
			$c[3] = "TE"; $v[3] = "Terminada";
			$c[4] = "CO"; $v[4] = "Completada";
			$c[5] = "AN"; $v[5] = "Anulada";
			$c[6] = "CE"; $v[6] = "Cerrada";
			break;
			
		case "ESTADO-VALORACION":
			$c[0] = "PR"; $v[0] = "En Preparación";
			$c[1] = "RV"; $v[1] = "Revisada";
			$c[2] = "AP"; $v[2] = "Aprobada";
			$c[3] = "AC"; $v[3] = "Auto de Proceder";
			$c[4] = "AA"; $v[4] = "Auto de Archivo";
			$c[5] = "CE"; $v[5] = "Cerrada";
			break;
			
		case "PROVISIONAR":
			$c[0] = "P"; $v[0] = "Pago del Documento";
			$c[1] = "N"; $v[1] = "Provisión del Documento";
			break;
			
		case "IMPONIBLE":
			$c[0] = "N"; $v[0] = "Monto Afecto";
			$c[1] = "I"; $v[1] = "Monto IGV/IVA";
			break;
			
		case "SIGNO":
			$c[0] = "P"; $v[0] = "+";
			$c[1] = "N"; $v[1] = "-";
			break;
			
		case "ESTADO-DOCUMENTOS":
			$c[0] = "PR"; $v[0] = "Pendiente";
			$c[1] = "RV"; $v[1] = "Facturado";
			break;
		
		case "FLAG-CONTABILIZADO":
			$c[0] = "N"; $v[0] = "Si";
			$c[1] = "S"; $v[1] = "No";
			break;
		
		case "DIRIGIDO":
			$c[0] = "C"; $v[0] = "Compras";
			$c[1] = "A"; $v[1] = "Almacen";
			break;
			
		case "ESTADO-COMPRA":
			$c[0] = "PR"; $v[0] = "En Preparacion";
			$c[1] = "RV"; $v[1] = "Revisada";
			$c[2] = "AP"; $v[2] = "Aprobada";
			$c[3] = "AN"; $v[3] = "Anulada";
			$c[4] = "RE"; $v[4] = "Rechazada";
			$c[5] = "CE"; $v[5] = "Cerrada";
			$c[6] = "CO"; $v[6] = "Completada";
			break;
			
		case "ESTADO-COMPRA-DETALLE":
			$c[0] = "PR"; $v[0] = "En Preparacion";
			$c[1] = "PE"; $v[1] = "Pendiente";
			$c[2] = "RE"; $v[2] = "Rechazada";
			$c[3] = "CE"; $v[3] = "Cerrada";
			$c[4] = "AN"; $v[4] = "Anulada";
			$c[5] = "CO"; $v[5] = "Completada";
			break;
		
		case "ESTADO-SERVICIO":
			$c[0] = "PR"; $v[0] = "En Preparacion";
			$c[1] = "RV"; $v[1] = "Revisada";
			$c[2] = "AP"; $v[2] = "Aprobada";
			$c[3] = "AN"; $v[3] = "Anulada";
			$c[4] = "RE"; $v[4] = "Rechazada";
			$c[5] = "CE"; $v[5] = "Cerrada";
			$c[6] = "CO"; $v[6] = "Completada";
			break;
		
		case "ESTADO-SERVICIO-DETALLE":
			$c[0] = "N"; $v[0] = "Pendiente";
			$c[1] = "S"; $v[1] = "Completado";
			break;
		
		case "TIPO-MOVIMIENTO-TRANSACCION":
			$c[0] = "I"; $v[0] = "Ingreso";
			$c[1] = "E"; $v[1] = "Egreso";
			$c[2] = "T"; $v[2] = "Transferencia";
			break;
		
		case "ESTADO-REQUERIMIENTO-DETALLE":
			$c[0] = "PR"; $v[0] = "En Preparación";
			$c[1] = "PE"; $v[1] = "Pendiente";
			$c[2] = "AN"; $v[2] = "Anulado";
			$c[3] = "RE"; $v[3] = "Rechazado";
			$c[4] = "CE"; $v[4] = "Cerrado";
			$c[5] = "CO"; $v[5] = "Completado";
			break;
		
		case "FLAG":
			$c[0] = "S"; $v[0] = "Si";
			$c[1] = "N"; $v[1] = "No";
			break;

		case "DIA-SEMANA":
			$c[0] = "1"; $v[0] = "Lunes";
			$c[1] = "2"; $v[1] = "Martes";
			$c[2] = "3"; $v[2] = "Miercoles";
			$c[3] = "4"; $v[3] = "Jueves";
			$c[4] = "5"; $v[4] = "Viernes";
			$c[5] = "6"; $v[5] = "Sabado";
			$c[6] = "7"; $v[6] = "Domingo";
			break;
	}
	
	$i=0;
	foreach ($c as $cod) {
		if ($cod == $codigo) return $v[$i];
		$i++;
	}
}

//	FUNCION PARA IMPRIMIR UN CHECK
function printFlag($check) {
	if (file_exists("../imagenes/checked.png")) $path = "../imagenes/checked.png";
	elseif (file_exists("../../imagenes/checked.png")) $path = "../../imagenes/checked.png";
	if ($check == "S" || $check == "X") $flag = "<img src='$path' width='12' height='12' />";
	return $flag;
}

//	FUNCION PARA IMPRIMIR UN CHECK
function printFlag2($check) {
	if ($check == "S" || $check == "X") $flag = "<img src='../imagenes/checked.png' width='16' height='16' />";
	return $flag;
}

//	FUNCION PARA IMPRIMIR UN CHECK
function printFlagEstado($check) {
	if ($check == "A") $flag = "<img src='../imagenes/arriba.png' width='20' height='20' />";
	elseif ($check == "I") $flag = "<img src='../imagenes/abajo.png' width='20' height='20' />";
	return $flag;
}

//	FUNCION PARA IMPRIMIR UN CHECK
function printSigno($signo) {
	if ($signo == "P") $flag = "<img src='../imagenes/positivo.png' width='16' height='16' />";
	elseif ($signo == "N") $flag = "<img src='../imagenes/negativo.png' width='16' height='16' />";
	return $flag;
}

//	imprime la fecha en formato dd-mm-aaaa
function formatFechaDMA($fecha) {
	list($a, $m, $d)=SPLIT( '[/.-]', $fecha);
	if ($d == "" || $d == "0000") return ""; else return "$d-$m-$a";
}

//	imprime la fecha en formato aaaa-mm-dd
function formatFechaAMD($fecha) {
	list($d, $m, $a)=SPLIT( '[/.-]', $fecha);
	if ($a == "" || $a == "0000") return ""; else return "$a-$m-$d";
}

//	imprime la hora en formato 12 horas
function formatHora12($hora, $seg=false) {
	list($h, $m, $s) = split("[:]", $hora);
	$time = "";
	if ($seg) {
		if ($h >= "01" && $h < 12) $time = "$h:$m:$s am";
		if ($h == 12) $time = "$h:$m:$s pm";
		elseif ($h == "00") $time = "12:$m:$s am";
		elseif ($h > 12) {
			$hh = $h - 12;
			if ($hh < 10) $hh = "0$hh";
			$time = "$hh:$m:$s pm";
		}
	} else {
		if ($h >= "01" && $h < 12) $time = "$h:$m am";
		if ($h == 12) $time = "$h:$m pm";
		elseif ($h == "00") $time = "12:$m am";
		elseif ($h > 12) {
			$hh = $h - 12;
			if ($hh < 10) $hh = "0$hh";
			$time = "$hh:$m pm";
		}
	}
	return $time;
}

//	imprime la fecha completa e ormato dd-mm-aaaa hh:mm:ss md
function formatDateFull($tiempo, $seg=false) {
	list($fecha, $hora) = split("[ ]", $tiempo);
	list($fd, $fm, $fa) = split("[/.-]", $fecha);
	list($hh, $hm, $hs) = split("[:]", $hora);
	$FechaDMA = formatFechaDMA($fecha);
	if ($FechaDMA != "") $Hora12 = formatHora12($hora, $seg);
	return "$FechaDMA $Hora12";
}

//	funcion para convertir un numero formateado en su valor real
function setNumero($num) {
	$num = str_replace(".", "", $num);
	$num = str_replace(",", ".", $num);
	$numero = floatval($num);
	return $numero;
}

//	devuelve el numero de dias de un mes
function getDiasMes($periodo) {
	list($anio, $mes) = split("[/.-]", $periodo);
	$anio = intval($anio);
	$mes = intval($mes);
	$dias_mes[1] = 31;
	if(!checkdate(02, 29, $anio)) $dias_mes[2] = 28; else $dias_mes[2] = 29;
	$dias_mes[3] = 31;
	$dias_mes[4] = 30;
	$dias_mes[5] = 31;
	$dias_mes[6] = 30;
	$dias_mes[7] = 31;
	$dias_mes[8] = 31;
	$dias_mes[9] = 30;
	$dias_mes[10] = 31;
	$dias_mes[11] = 30;
	$dias_mes[12] = 31;
	return $dias_mes[$mes];
}

//	devuelve el nombre del mes
function getNombreMes($periodo) {
	list($anio, $mes) = split("[/.-]", $periodo);
	$anio = intval($anio);
	$mes = intval($mes);
	$nombre_mes[1] = "Enero";
	$nombre_mes[2] = "Febrero";
	$nombre_mes[3] = "Marzo";
	$nombre_mes[4] = "Abril";
	$nombre_mes[5] = "Mayo";
	$nombre_mes[6] = "Junio";
	$nombre_mes[7] = "Julio";
	$nombre_mes[8] = "Agosto";
	$nombre_mes[9] = "Septiembre";
	$nombre_mes[10] = "Octubre";
	$nombre_mes[11] = "Noviembre";
	$nombre_mes[12] = "Diciembre";
	return $nombre_mes[$mes];
}

//	devuelve una fecha que es la suma de una fecha inicial + dias
function getFechaFin($fecha, $dias) {
	$sumar = true;
	$dia_semana = getDiaSemana($fecha);	
	list($dia, $mes, $anio) = split("[/.-]", $fecha);
	$d = intval($dia); $m = intval($mes); $a = intval($anio);
	for ($i=1; $i<=$dias;) {
		$dia_semana++;
		if ($dia_semana == 8) $dia_semana = 1;
		if ($dia_semana >= 1 && $dia_semana <= 5) $i++; 
		$d++;
		$dias_mes = getDiasMes("$a-$m");
		if ($d > $dias_mes) { 
			$d = 1;
			$m++; 
			if ($m > 12) { $m = 1; $a++; }
		}
	}
	if ($d < 10) $d = "0$d";
	if ($m < 10) $m = "0$m";
	return "$d-$m-$a";
}

//	devuel el dia de la semana de una fecha
function getDiaSemana($fecha) {
	// primero creo un array para saber los días de la semana
	$dias = array(0, 1, 2, 3, 4, 5, 6);
	$dia = substr($fecha, 0, 2);
	$mes = substr($fecha, 3, 2);
	$anio = substr($fecha, 6, 4);
	// en la siguiente instrucción $pru toma el día de la semana, lunes, martes,
	$pru = strtoupper($dias[intval((date("w",mktime(0,0,0,$mes,$dia,$anio))))]);
	return $pru;
}

//	devuel el dia de la semana de una fecha
function getWeekDay($fecha) {
	$sql = "SELECT WEEKDAY('".formatFechaAMD($fecha)."') AS DiaSemana;";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	return ++$field['DiaSemana'];
}

//	devuelve fecha de inicio y fin de un numero cualquiera (edad, tiempo, etc)
function getFechasTiempo($tiempo) {
	global $Ahora;
	list($anio, $mes, $dia) = split("[.-/]", substr($Ahora, 0, 10));
	$a = intval($anio);
	$m = intval($mes);
	$d = intval($dia);
	$anio_inicio = intval($a - $tiempo - 1);
	$mes_inicio = $m;
	$dia_inicio = $d + 1;	
	$anio_fin = intval($a - $tiempo);
	$mes_fin = $m;
	$dia_fin = $d;	
	if ($dia_inicio > getDiasMes("$anio-$mes")) {
		$dia_inicio = 1;
		if ($mes_inicio == 12) {
			$mes_inicio = 1;
			$anio_inicio++;
		} else {
			$mes_inicio++;
		}
	}	
	if ($dia_inicio < 10) $di = "0$dia_inicio";
	if ($mes_inicio < 10) $mi = "0$mes_inicio";	
	if ($dia_fin < 10) $df = "0$dia_fin";
	if ($mes_fin < 10) $mf = "0$mes_fin";	
	$fecha_inicio = "$di-$mi-$anio_inicio";
	$fecha_fin = "$df-$mf-$anio_fin";	
	return array($fecha_inicio, $fecha_fin);
}

//	devuelve la fecha de inicio y fin para una edad
function getFechasEdad($edad, $fecha) {
	list($aa, $ma, $da) = split("[.-/]", $fecha);
	$ad = $aa - $edad;
	$md = $ma;
	$dd = intval($da) + 1;
	if ($dd > getDiasMes("$ad-$md")) {
		$md = intval($ma) + 1;
		$dd = 1;
	}
	$anio_desde = $ad - 1;
	if ($md < 10) $mes_desde = "0$md"; else $mes_desde = $md;
	if ($dd < 10) $dia_desde = "0$dd"; else $dia_desde = $dd;
	$anio_hasta = $ad; 
	$mes_hasta = $ma;
	$dia_hasta = $da;
	$fecha_desde = "$dia_desde-$mes_desde-$anio_desde";
	$fecha_hasta = "$dia_hasta-$mes_hasta-$anio_hasta";
	return array($fecha_desde, $fecha_hasta);
}
//	---------------------------------

//	funcion para obtener los años meses y dias entre dos fechas
function getTiempo($_DESDE, $_HASTA) {
	$error=0;
	$listo=0;	
	if ((strlen($_DESDE))<10) $error=1;
	else {
		list($d, $m, $a)=SPLIT( '[/.-]', $_HASTA);
		$diaActual = $d;
		$mesActual = $m;
		$annioActual = $a;
		//
		list($d, $m, $a)=SPLIT( '[/.-]', $_DESDE);
		$dia = (int) ($d);
		$mes = (int) ($m);
		$annio = (int) ($a);
		$dias = 0;
		$meses = 0;
		$annios = 0;
		//
		if ($annio>$annioActual || ($annio==$annioActual && $mes>$mesActual) || ($annio==$annioActual && $mes==$mesActual && $dia>$diaActual)) $error=2;
		else {
			$annios = $annioActual - $annio;
			$meses = $mesActual - $mes;
			$dias = $diaActual - $dia;
			
			if ($dias < 0) { $meses--; $dias = 30 + $dias; }
			if ($meses < 0) { $annios--; $meses = 12 + $meses; }
			
			if ($dias >= 30) { $meses++; $dias = 0; }
			if ($meses >= 12) { $annios++; $meses = 0; }
			
			return array($annios, $meses, $dias);
		}
	}
	if ($error!=0) return array("", "", "");
}

//	FUNCION PARA OBTENER LOS ANIOS, MESES Y DIAS ENTRE DOS FECHAS
function getEdad($_DESDE, $_HASTA) {
	$error = 0;
	$listo = 0;
	if ((strlen($_DESDE)) < 10) $error = 1;
	else {
		list($d, $m, $a) = SPLIT("[/.-]", $_HASTA);
		$diaActual = $d;
		$mesActual = $m;
		$annioActual = $a;
		##
		list($d, $m, $a) = split("[/.-]", $_DESDE);
		$dia = intval($d);
		$mes = intval($m);
		$annio = intval($a);
		$dias = 0;
		$meses = 0;
		$annios = 0;
		##
		if ($annio > $annioActual || ($annio == $annioActual && $mes > $mesActual) || ($annio == $annioActual && $mes == $mesActual && $dia > $diaActual)) $error = 2;
		else {
			$annios = $annioActual - $annio;
			$meses = $mesActual - $mes;
			$dias = $diaActual - $dia;
			##
			if ($dias < 0) { $meses--; $dias = 30 + $dias; }
			if ($meses < 0) { $annios--; $meses = 12 + $meses; }
			##
			if ($dias >= 30) { $meses++; $dias = 0; }
			if ($meses >= 12) { $annios++; $meses = 0; }
			##
			return array($annios, $meses, $dias);
		}
	}
	if ($error!=0) return array("", "", "");
}
//	---------------------------------

//
function getDiffHora($Desde, $Hasta) {
	$sql = "SELECT TIMEDIFF('$Hasta', '$Desde') AS TotalHoras;";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	return substr($field['TotalHoras'], 0, 5);
}
//	---------------------------------

//
function sumarHoras($Hora1, $Hora2) {
	list($h1, $m1, $s1) = split("[:]", $Hora1);
	list($h2, $m2, $s2) = split("[:]", $Hora2);
	$totalh = intval($h1) + intval($h2);
	$totalm = intval($m1) + intval($m2);
	if ($totalm >= 60) {
		$hsumar = intval($totalm / 60);
		$totalh += $hsumar;
		$totalm = $totalm - (60 * $hsumar);
	}
	return "$totalh:$totalm";
}
//	---------------------------------

//	funcion para redondear un numero con decimales
function redondeo($VALOR, $DECIMALES) {
	$ceros = (string) str_repeat("0", $DECIMALES);
	$unidad = "1".$ceros;
	$unidad = intval($unidad);
	$multiplicamos = $VALOR * $unidad;	
	list($parte_entera, $numero_redondeo) = split('[.]', $multiplicamos);
	$numero_redondeo = substr($numero_redondeo, 0, 1);
	if ($numero_redondeo >= 5) $parte_entera++;	
	$resultado = $parte_entera / $unidad;	
	return $resultado;
}

// FUNCIONES DE CONVERSION DE NUMEROS A LETRAS.
function num2letras($num, $fem = true, $dec = true) {
//if (strlen($num) > 14) die("El número introducido es demasiado grande");
   $matuni[2]  = "dos";
   $matuni[3]  = "tres";
   $matuni[4]  = "cuatro";
   $matuni[5]  = "cinco";
   $matuni[6]  = "seis";
   $matuni[7]  = "siete";
   $matuni[8]  = "ocho";
   $matuni[9]  = "nueve";
   $matuni[10] = "diez";
   $matuni[11] = "once";
   $matuni[12] = "doce";
   $matuni[13] = "trece";
   $matuni[14] = "catorce";
   $matuni[15] = "quince";
   $matuni[16] = "dieciseis";
   $matuni[17] = "diecisiete";
   $matuni[18] = "dieciocho";
   $matuni[19] = "diecinueve";
   $matuni[20] = "veinte";
   $matunisub[2] = "dos";
   $matunisub[3] = "tres";
   $matunisub[4] = "cuatro";
   $matunisub[5] = "quin";
   $matunisub[6] = "seis";
   $matunisub[7] = "sete";
   $matunisub[8] = "ocho";
   $matunisub[9] = "nove";
   $matdec[2] = "veint";
   $matdec[3] = "treinta";
   $matdec[4] = "cuarenta";
   $matdec[5] = "cincuenta";
   $matdec[6] = "sesenta";
   $matdec[7] = "setenta";
   $matdec[8] = "ochenta";
   $matdec[9] = "noventa";
   $matsub[3]  = "mill";
   $matsub[5]  = "bill";
   $matsub[7]  = "mill";
   $matsub[9]  = "trill";
   $matsub[11] = "mill";
   $matsub[13] = "bill";
   $matsub[15] = "mill";
   $matmil[4]  = "millones";
   $matmil[6]  = "billones";
   $matmil[7]  = "de billones";
   $matmil[8]  = "millones de billones";
   $matmil[10] = "trillones";
   $matmil[11] = "de trillones";
   $matmil[12] = "millones de trillones";
   $matmil[13] = "de trillones";
   $matmil[14] = "billones de trillones";
   $matmil[15] = "de billones de trillones";
   $matmil[16] = "millones de billones de trillones";
   $num = trim((string)@$num);
   if ($num[0] == "-") {
      $neg = "menos ";
      $num = substr($num, 1);
   }else
      $neg = "";
   while ($num[0] == "0") $num = substr($num, 1);
   if ($num[0] < "1" or $num[0] > 9) $num = "0" . $num;
   $zeros = true;
   $punt = false;
   $ent = "";
   $fra = "";
   for ($c = 0; $c < strlen($num); $c++) {
      $n = $num[$c];
      if (! (strpos(".,´´`", $n) === false)) {
         if ($punt) break;
         else{
            $punt = true;
            continue;
         }
      }elseif (! (strpos("0123456789", $n) === false)) {
         if ($punt) {
            if ($n != "0") $zeros = false;
            $fra .= $n;
         }else
            $ent .= $n;
      }else
         break;
   }
  
   $ent = "     " . $ent;
  
   if ($dec and $fra and ! $zeros) {
      $fin = " coma";
      for ($n = 0; $n < strlen($fra); $n++) {
         if (($s = $fra[$n]) == "0")
            $fin .= " cero";
         elseif ($s == "1")
            $fin .= $fem ? " una" : " un";
         else
            $fin .= " " . $matuni[$s];
      }
   }else
      $fin = "";
   if ((int)$ent === 0) return "Cero " . $fin;
   $tex = "";
   $sub = 0;
   $mils = 0;
   $neutro = false;
  
   while ( ($num = substr($ent, -3)) != "   ") {
     
      $ent = substr($ent, 0, -3);
      if (++$sub < 3 and $fem) {
         $matuni[1] = "una";
         $subcent = "as";
      }else{
         //$matuni[1] = $neutro ? "un" : "uno";
         $matuni[1] = $neutro ? "un" : "un";
         $subcent = "os";
      }
      $t = "";
      $n2 = substr($num, 1);
      if ($n2 == "00") {
      }elseif ($n2 < 21)
         $t = " " . $matuni[(int)$n2];
      elseif ($n2 < 30) {
         $n3 = $num[2];
         if ($n3 != 0) $t = "i" . $matuni[$n3];
         $n2 = $num[1];
         $t = " " . $matdec[$n2] . $t;
      }else{
         $n3 = $num[2];
         if ($n3 != 0) $t = " y " . $matuni[$n3];
         $n2 = $num[1];
         $t = " " . $matdec[$n2] . $t;
      }
     
      $n = $num[0];
      if ($n == 1) {
         if ($num == 100) $t = " cien" . $t; else $t = " ciento" . $t;
      }elseif ($n == 5){
         $t = " " . $matunisub[$n] . "ient" . $subcent . $t;
      }elseif ($n != 0){
         $t = " " . $matunisub[$n] . "cient" . $subcent . $t;
      }
     
      if ($sub == 1) {
      }elseif (! isset($matsub[$sub])) {
         if ($num == 1) {
            $t = " mil";
         }elseif ($num > 1){
            $t .= " mil";
         }
      }elseif ($num == 1) {
         $t .= " " . $matsub[$sub] . "ón";
      }elseif ($num > 1){
         $t .= " " . $matsub[$sub] . "ones";
      }  
      if ($num == "000") $mils ++;
      elseif ($mils != 0) {
         if (isset($matmil[$sub])) $t .= " " . $matmil[$sub];
         $mils = 0;
      }
      $neutro = true;
      $tex = $t . $tex;
   }
   $tex = $neg . substr($tex, 1) . $fin;
   return $tex;
}
function convertir_a_letras($numero, $tipo) {
	list($e, $d) = SPLIT('[.]', $numero);
	if ($tipo == "moneda")
		return num2letras($e, false, false)." bolivares con ".num2letras($d, false, false)." centimos";
	else if ($tipo == "decimal")
		return num2letras($e, false, false)." con ".num2letras($d, false, false);
	else if ($tipo == "entero")
		return num2letras($e, false, false);
}
//	-------------------------------

//	obtengo la tasa de interes para un periodo
function tasaInteres($periodo) {
	$sql = "SELECT Porcentaje FROM masttasainteres WHERE Periodo = '".$periodo."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	return $field['Porcentaje'];
}

//	obtengo el codigo interno de una dependencia
function getCodInternoDependencia($dependencia) {
	$sql = "SELECT CodInterno FROM mastdependencias WHERE CodDependencia = '".$dependencia."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	return $field['CodInterno'];
}

//	obtengo partida y cuenta de un item
function getPartidaCuentaItem($codigo) {
	$sql = "SELECT CtaGasto, PartidaPresupuestal FROM lg_itemmast WHERE CodItem = '".$codigo."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	return array($field['CtaGasto'], $field['PartidaPresupuestal']);
}

//	obtengo partida y cuenta de un item
function getPartidaCuentaCommodity($codigo) {
	$sql = "SELECT CodCuenta, cod_partida FROM lg_commoditysub WHERE Codigo = '".$codigo."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	return array($field['CodCuenta'], $field['cod_partida']);
}

//	devuelve el valor de un campo
function getValorCampo($tabla, $codcampo, $nomcampo, $codigo) {
	$sql = "SELECT $nomcampo FROM $tabla WHERE $codcampo = '".$codigo."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	return $field[0];
}

//	devuelve el valor de un campo
function valorExiste($tabla, $campo, $codigo) {
	$sql = "SELECT * FROM $tabla WHERE $campo = '".$codigo."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) return true;
	else return false;
}

//	devuelve el valor de un campo
function valorExisteUp($tabla, $campo, $codigo, $codcampo, $codvalor) {
	$sql = "SELECT * FROM $tabla WHERE $campo = '".$codigo."' AND $codcampo <> '".$codvalor."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) return true;
	else return false;
}

//	funcion que devuel el primer registro de una tabla
function getPrimeroDefault($tabla, $codigo, $nombre, $order=NULL) {
	if ($order) $fOrderBy = $order; else $fOrderBy = $codigo;
	$sql = "SELECT $codigo, $nombre FROM $tabla ORDER BY $fOrderBy LIMIT 0, 1";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	return array($field[0], $field[1]);
}

//	paginacion
function paginacion($rows_total, $rows, $maxlimit, $limit) {
	if ($rows_total <= $maxlimit) {
        $p = "style = 'visibility:hidden;'";
        $a = "style = 'visibility:hidden;'";
        $s = "style = 'visibility:hidden;'";
        $u = "style = 'visibility:hidden;'";
        $dh = "style = 'visibility:hidden;'";
    }
    elseif ($limit == 0) {
        $p = "style = 'visibility:hidden;'";
        $a = "style = 'visibility:hidden;'";
    }
    elseif (($limit + $maxlimit) >= $rows_total) {
        $s = "style = 'visibility:hidden;'";
        $u = "style = 'visibility:hidden;'";
    }    
    $primero = 0;
    $anterior = $limit - $maxlimit;
    $siguiente = $limit + $maxlimit;
    $num = (int) ($rows_total / $maxlimit);
    $ultimo = $num * $maxlimit;
    if ($ultimo == $rows_total) $ultimo = $ultimo - $maxlimit;    
    $desde = $limit + 1;
    if ($maxlimit > $rows) $hasta = ($limit + $rows); else $hasta = ($limit + $maxlimit);
	
	if ($rows_total % $maxlimit == 0) $paginas = ($rows_total / $maxlimit); else $paginas = (int) ($rows_total / $maxlimit) + 1;
	if ($hasta % $maxlimit == 0) $pagina_actual = $hasta / $maxlimit;	else $pagina_actual = (int) (($hasta / $maxlimit) + 1);
	if ($paginas <= 1) $pag = "style = 'visibility:hidden;'";
	
	if (file_exists("../imagenes/f_primero.png")) $path = "../imagenes/";
	elseif (file_exists("../../imagenes/f_primero.png")) $path = "../../imagenes/";
    ?>
    <table>
        <tr>
            <td scope="row" width="16">
            	<button onclick="cargarPagina(this.form, this.form.action+'&limit=<?=$primero?>');" <?=$p?>>
                <img src="<?=$path?>f_primero.png" height="12" title="Primera página" />
                </button>
            </td>
            <td scope="row" width="16">
            	<button onclick="cargarPagina(this.form, this.form.action+'&limit=<?=$anterior?>');" <?=$a?>>
                <img src="<?=$path?>f_anterior.png" height="12" title="Primera anterior" />
                </button>
            </td>
            <td scope="row">
                <span <?=$pag?>>P&aacute;gina <?=$pagina_actual?> de <?=$paginas?></span>
            </td>
            <td scope="row" width="16">
            	<button onclick="cargarPagina(this.form, this.form.action+'&limit=<?=$siguiente?>');" <?=$s?>>
                <img src="<?=$path?>f_siguiente.png" height="12" title="Página siguiente" />
                </button>
            </td>
            <td scope="row" width="16">
            	<button onclick="cargarPagina(this.form, this.form.action+'&limit=<?=$ultimo?>');" <?=$u?>>
                <img src="<?=$path?>f_ultimo.png" height="12" title="Última página" />
                </button>
            </td>
        </tr>
    </table>
	<?
}

//	IMPRIMIR ERROR DE CONSULTA SQL
function getErrorSql($nroerror, $deterror, $sql) {
	switch ($nroerror) {
		case "1451":
			$error = "¡ERROR: Registro enlazado a otra tabla!: <br />".$deterror;
			break;
			
		case "1062":
			$error = "¡ERROR: Registro Existe!: <br />".$sql;
			break;
			
		case "1064":
			$error = "Error de sintaxis: <br />".$sql;
			break;
			
		default:
			$error = "Error: ".$nroerror." <br />".$sql." <br />".$deterror;
			break;
	}
	return $error;
}
//	------------------------------

//	------------------------------
function setUsuario($CodPersona) {
	$sql = "SELECT Apellido1, Apellido2, Nombres FROM mastpersonas WHERE CodPersona = '".$CodPersona."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	$nombres = split(" ", $field['Nombres']);
	$n1 = "$nombres[0]";
	$n2 = "$nombres[1]";
	$n3 = "$nombres[2]";
	$i1 = substr($n1, 0, 1);
	$i2 = substr($n2, 0, 1);
	$i3 = substr($n3, 0, 1);
	if (strtoupper($n2) == "DE" || strtoupper($n2) == "DEL") $i2 = substr($n3, 0, 1);
	else $i2 = substr($n2, 0, 1);
	if ($field['Apellido1'] == "") $apellido = $field['Apellido2'];
	else $apellido = $field['Apellido1'];
	$usuario = strtoupper("$i1$i2$apellido");
	return $usuario;
}
//	------------------------------

//	-------------------------------
function getFechaFinHabiles($fecha, $dias) {
	$finicio = $fecha;
	$ffin = fechaFinHabiles($finicio, $dias);
	$dias_feriados = 0;
	do {
		$feriados = getDiasFeriados($finicio, $ffin) - $dias_feriados;
		$dias += $feriados;
		$ffin = fechaFinHabiles($finicio, $dias);
		$dias_feriados += $feriados;
	} while ($feriados > 0);
	return $ffin;
}
//	-------------------------------

//	-------------------------------
function fechaFinHabiles($fecha, $dias) {
	if ($dias==1 || $dias==0) $dias=0; else $dias--;
	$sumar=true;
	$dia_semana=getDiaSemana($fecha);
	list($dia, $mes, $anio)=SPLIT('[/.-]', $fecha);
	$d=(int) $dia; $m=(int) $mes; $a=(int) $anio;
	
	for ($i=1; $i<=$dias;) {
		$dia_semana++;
		if ($dia_semana==8) $dia_semana=1;
		if ($dia_semana>=1 && $dia_semana<=5) $i++;
		$d++;
		$dias_mes=getDiasMes("$a-$m");
		if ($d>$dias_mes) { 
			$d=1; $m++; 
			if ($m>12) { $m=1; $a++; }
		}
	}
	if ($d<10) $d="0$d";
	if ($m<10) $m="0$m";
	return "$d-$m-$a";
}
//	-------------------------------

//	-------------------------------
function getDiasFeriados($fdesde, $fhasta) {
	list($dia_desde, $mes_desde, $anio_desde)=SPLIT('[/.-]', $fdesde); $DiaDesde = "$mes_desde-$dia_desde";
	list($dia_hasta, $mes_hasta, $anio_hasta)=SPLIT('[/.-]', $fhasta); $DiaHasta = "$mes_hasta-$dia_hasta";
	
	$sql = "SELECT * 
			FROM rh_feriados 
			WHERE 
				(FlagVariable = 'S' AND
				 (AnioFeriado = '".$anio_desde."' OR AnioFeriado = '".$anio_hasta."') AND 
				 (DiaFeriado >= '".$DiaDesde."' AND DiaFeriado <= '".$DiaHasta."')) OR
				(FlagVariable = 'N' AND DiaFeriado >= '".$DiaDesde."' AND DiaFeriado <= '".$DiaHasta."')";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	$rows = mysql_num_rows($query);	$dias_feriados = 0;
	while ($field = mysql_fetch_array($query)) {
		list($mes, $dia) = SPLIT('[/.-]', $field['DiaFeriado']);
		if ($field['AnioFeriado'] == "") $anio = $anio_desde; else $anio = $field['AnioFeriado'];
		$fecha = "$dia-$mes-$anio";
		$dia_semana = getDiaSemana($fecha);
		if ($dia_semana >= 1 && $dia_semana <= 5) $dias_feriados++;
		if ($anio_desde != $anio_hasta) {
			if ($field['AnioFeriado'] == "") $anio = $anio_hasta; else $anio = $field['AnioFeriado'];
			$fecha = "$dia-$mes-$anio";
			$dia_semana = getDiaSemana($fecha);
			if ($dia_semana >= 1 && $dia_semana <= 5) $dias_feriados++;
		}
	}
	return $dias_feriados;
}
//	-------------------------------

//	-------------------------------
function getDiasHabiles($desde, $hasta) {
	$dias_completos = getFechaDias($desde, $hasta);
	$dias_feriados = getDiasFeriados($desde, $hasta);
	$dia_semana = getDiaSemana($desde);
	$dias_habiles = 0;
	for ($i=0; $i<=$dias_completos; $i++) {
		if ($dia_semana >= 1 && $dia_semana <= 5) $dias_habiles++;
		$dia_semana++;
		if ($dia_semana == 7) $dia_semana = 0;
	}
	$dias_habiles -= $dias_feriados;
	return $dias_habiles;
}
//	-------------------------------

//	-------------------------------
function getFechaDias($fechad, $fechah) {
	list($dd, $md, $ad) = SPLIT( '[/.-]', $fechad);	$desde = "$ad-$md-$dd";
	list($dh, $mh, $ah) = SPLIT( '[/.-]', $fechah);	$hasta = "$ah-$mh-$dh";
	$sql = "SELECT DATEDIFF('$hasta', '$desde');";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	$field = mysql_fetch_array($query);
	return $field[0];
}
//	---------------------------------

//	FUNCION PARA IMPRIMIR UN CHECK
function printEstadoActuacion($estado) {
	if (file_exists("../imagenes/circle_red.png")) $path = "../imagenes";
	elseif (file_exists("../../imagenes/circle_red.png")) $path = "../../imagenes";
	if ($estado == "PR" || $estado == "PE") $flag = "<img src='$path/circle_red.png' width='16' height='16' />";
	elseif ($estado == "EJ") $flag = "<img src='$path/circle_green.png' width='16' height='16' />";
	elseif ($estado == "TE") $flag = "<img src='$path/checked.png' width='16' height='16' />";
	elseif ($estado == "AU") $flag = "<img src='$path/database_48.png' width='16' height='16' />";
	return $flag;
}
//	---------------------------------

//	FUNCION PARA IMPRIMIR UN CHECK
function printEstadoActuacion2($estado) {
	$path = "../imagenes";	
	if ($estado == "PR" || $estado == "PE") $flag = "<img src='$path/circle_red.png' width='16' height='16' />";
	elseif ($estado == "EJ") $flag = "<img src='$path/circle_green.png' width='16' height='16' />";
	elseif ($estado == "TE") $flag = "<img src='$path/checked.png' width='16' height='16' />";
	elseif ($estado == "AU") $flag = "<img src='$path/database_48.png' width='16' height='16' />";
	return $flag;
}
//	---------------------------------

//	FUNCION PARA IMPRIMIR UN CHECK
function printEstado($Estado) {
	$path = "../imagenes";	
	if ($Estado == "A") $flag = "<img src='$path/arriba.png' width='16' height='16' />";
	else $flag = "<img src='$path/abajo.png' width='16' height='16' />";
	return $flag;
}
//	---------------------------------

//	FUNCION PARA IMPRIMIR UN CHECK
function printWarning($Estado) {
	$path = "../imagenes";	
	if ($Estado == "S") $flag = "<img src='$path/warning.png' width='16' height='16' />";
	else $flag = "";
	return $flag;
}
//	---------------------------------

//	FUNCION QUE CHEQUEA UN VALOR PARA UN CHECBOX
function chkFlag($chk) {
	if ($chk == "S") echo "checked='checked'";
	else echo "";
}
//	---------------------------------

//	FUNCION QUE CHEQUEA UN VALOR PARA UN RADIO
function chkOpt($chk, $value) {
	if ($chk == $value) echo "checked='checked'";
	else echo "";
}
//	---------------------------------

//	FUNCION QUE VERIFICA SI EL TIPO DE SERVICIO ES AFECTO A IMPUESTO
function afectaTipoServicio($CodTipoServicio) {
	global $_PARAMETRO;
	$sql = "SELECT tsi.CodImpuesto	
			FROM
				masttiposervicioimpuesto tsi
				INNER JOIN mastimpuestos i ON (tsi.CodImpuesto = i.CodImpuesto AND 
											   i.CodRegimenFiscal = 'I')
			WHERE tsi.CodTipoServicio = '".$CodTipoServicio."'";
	$query_impuesto = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query_impuesto) != 0) return true; else return false;
}

//	Obtengo el porcentaje del igv/iva
function getFactorImpuesto() {
	global $_PARAMETRO;
	$sql = "SELECT FactorPorcentaje
			FROM mastimpuestos
			WHERE CodImpuesto = '".$_PARAMETRO['IGVCODIGO']."'";
	$query_impuesto = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query_impuesto) != 0) $field_impuesto = mysql_fetch_array($query_impuesto);
	return $field_impuesto['FactorPorcentaje'];
}
//	---------------------------------

//	Obtengo el porcentaje del igv/iva
function getPorcentajeIVA($CodTipoServicio) {
	$sql = "SELECT FactorPorcentaje
			FROM
				masttiposervicio ts
				INNER JOIN masttiposervicioimpuesto tsi ON (ts.CodTipoServicio = tsi.CodTipoServicio)
				INNER JOIN mastimpuestos i ON (tsi.CodImpuesto = i.CodImpuesto AND 
											   i.CodRegimenFiscal = 'I' AND 
											   i.Signo = 'P')
			WHERE ts.CodTipoServicio = '".$CodTipoServicio."'";
	$query_impuesto = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query_impuesto) != 0) $field_impuesto = mysql_fetch_array($query_impuesto);
	return $field_impuesto['FactorPorcentaje'];
}
//	---------------------------------

//	
function valObligacion($CodProveedor, $CodTipoDocumento, $NroDocumento) {
	$sql = "SELECT CodProveedor, CodTipoDocumento, NroDocumento
			FROM ap_obligaciones
			WHERE
				CodProveedor = '".$CodProveedor."' AND
				CodTipoDocumento = '".$CodTipoDocumento."' AND
				NroDocumento = '".$NroDocumento."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) return true; else return false;
}
//	---------------------------------

//	
function ahora() {
	$sql = "SELECT NOW() As Ahora";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		return $field[0];
	}
}
//	---------------------------------

//	obtiene 
function getFirma($CodPersona) {
	global $_PARAMETRO;
	$sql = "SELECT
				mp.Apellido1,
				mp.Apellido2,
				mp.Nombres,
				mp.Sexo,
				p1.DescripCargo AS Cargo,
				p2.DescripCargo AS CargoEncargado,
				p2.Grado AS GradoEncargado
			FROM
				mastpersonas mp
				INNER JOIN mastempleado me ON (mp.CodPersona = me.CodPersona)
				INNER JOIN rh_puestos p1 ON (me.CodCargo = p1.CodCargo)
				LEFT JOIN rh_puestos p2 ON (me.CodCargoTemp = p2.CodCargo)
			WHERE mp.CodPersona = '".$CodPersona."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	##
	list($Nombre) = split("[ ]", $field['Nombres']);
	if ($field['Apellido1'] != "") $Apellido = $field['Apellido1']; else $Apellido = $field['Apellido2'];
	$NomCompleto = "$Nombre $Apellido";
	##
	if ($field['CargoEncargado'] != "") {
		if ($field['GradoEncargado'] == "99" && $_PARAMETRO['PROV99'] == $CodPersona) $tmp = "(P)"; else $tmp = "(E)";
		$Cargo = $field['CargoEncargado'];
	}
	else { $Cargo = $field['Cargo']; $tmp = ""; }
	##
	$Cargo = str_replace("(A)", "", $Cargo);
	if ($field['Sexo'] == "M") {
	} else {
		$Cargo = str_replace("JEFE", "JEFA", $Cargo);
		$Cargo = str_replace("DIRECTOR", "DIRECTORA", $Cargo);
		$Cargo = str_replace("CONTRALOR", "CONTRALORA", $Cargo);
	}
	##	consulto el nivel de instruccion
	$sql = "SELECT
				ei.Nivel,
				ngi.AbreviaturaM,
				ngi.AbreviaturaF
			FROM
				rh_empleado_instruccion ei
				INNER JOIN rh_nivelgradoinstruccion ngi ON (ngi.CodGradoInstruccion = ei.CodGradoInstruccion AND
														    ngi.Nivel = ei.Nivel)
			WHERE
				ei.CodPersona = '".$CodPersona."' AND
				ei.FechaGraduacion = (SELECT MAX(ei2.FechaGraduacion) FROM rh_empleado_instruccion ei2 WHERE ei2.CodPersona = ei.CodPersona)";
	$query_nivel = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query_nivel) != 0) $field_nivel = mysql_fetch_array($query_nivel);
	if ($field['Sexo'] == "M") $nivel = $field_nivel['AbreviaturaM']; else $nivel = $field_nivel['AbreviaturaF'];
	##
	return array($NomCompleto, $Cargo.$tmp, $nivel);
}
//	---------------------------------

//	obtiene 
function getFirmaxDependencia($CodDependencia) {
	global $_PARAMETRO;
	//	obtengo responsable de la dependencia
	$sql = "SELECT CodPersona FROM mastdependencias WHERE CodDependencia = '".$CodDependencia."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	//	obtengo la firma
	list($Nombre, $Cargo, $Nivel) = getFirma($field['CodPersona']);
	##
	return array($Nombre, $Cargo, $Nivel);
}
//	---------------------------------

//	funcion para sustituir caracteres especiales
function changeUrl($texto) {
	//eregi_replace("foros","tutoriales","$cadena");
	$texto = str_replace("|char:ampersand|", "&", $texto);
	$texto = str_replace("|char:mas|", "+", $texto);
	$texto = str_replace("|char:comillasimple|", "\'", $texto);
	$texto = str_replace("|char:comilladoble|", "\"", $texto);
	return $texto;
}
//	---------------------------------

//	funcion para verificar
function periodoAbierto($CodOrganismo, $Periodo) {
	$sql = "SELECT *
			FROM lg_periodocontrol
			WHERE
				CodOrganismo = '".$CodOrganismo."' AND
				Periodo = '".$Periodo."' AND
				FlagTransaccion = 'S' AND
				Estado = 'A'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) == 0) return false;
	else return true;
}
//	---------------------------------

//	funcion para verificar
function disponibilidadPartida($Anio, $CodOrganismo, $cod_partida) {
	//	comprometido
	$sql = "SELECT pd.MontoAjustado, pd.MontoCompromiso
			FROM
				pv_presupuesto p
				LEFT JOIN pv_presupuestodet pd ON (p.CodPresupuesto = pd.CodPresupuesto AND
												   p.Organismo = pd.Organismo)
			WHERE
				p.EjercicioPpto = '".$Anio."' AND
				p.Organismo = '".$CodOrganismo."' AND
				pd.cod_partida = '".$cod_partida."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) $field_comprometido = mysql_fetch_array($query);
	
	//	pre-comprometido
	$sql = "SELECT SUM(Monto) AS Monto
			FROM lg_distribucioncompromisos
			WHERE
				Anio = '".$Anio."' AND
				CodOrganismo = '".$CodOrganismo."' AND
				cod_partida = '".$cod_partida."' AND
				Estado = 'PE'
			GROUP BY cod_partida";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) $field_precomprometido = mysql_fetch_array($query);
	
	return array($field_comprometido['MontoAjustado'], $field_comprometido['MontoCompromiso'], $field_precomprometido['Monto']);
}
//	---------------------------------

function prestacionAcumulada($CodPersona, $Periodo) {
	//	consulto lo acumulado hasta diciembre del periodo anterior
	$sql = "SELECT
				PrestAcumulada,
				InteresAcumulado
			FROM pr_fideicomisocalculo
			WHERE
				CodPersona = '".$CodPersona."' AND
				Periodo = '".($Periodo-1)."-12'";
	$query_prest = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query_prest) != 0) $field_prest = mysql_fetch_array($query_prest);
	else {
		//	consulto lo acumulado hasta diciembre del periodo anterior
		$sql = "SELECT
					AcumuladoInicialProv AS PrestAcumulada,
					AcumuladoInicialFide AS InteresAcumulado
				FROM pr_acumuladofideicomiso
				WHERE CodPersona = '".$CodPersona."'";
		$query_prest = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query_prest) != 0) $field_prest = mysql_fetch_array($query_prest);
	}
	
	//	consulto dias inicial
	$sql = "SELECT AcumuladoInicialDias
			FROM pr_acumuladofideicomiso
			WHERE CodPersona = '".$CodPersona."'";
	$query_dias_inicial = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query_dias_inicial) != 0) $field_dias_inicial = mysql_fetch_array($query_dias_inicial);
	
	//	consulto acumulado de dias
	$sql = "SELECT SUM(Dias) AS Dias
			FROM pr_acumuladofideicomisodetalle
			WHERE
				CodPersona = '".$CodPersona."' AND
				Periodo < '".$Periodo."'";
	$query_dias = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query_dias) != 0) $field_dias = mysql_fetch_array($query_dias);
	
	$Dias = $field_dias_inicial['AcumuladoInicialDias'] + $field_dias['Dias'];
	
	return array($field_prest['PrestAcumulada'], $field_prest['InteresAcumulado'], $Dias);
}

//	funcion para copiar una imagen
function copiarImagenTMP($imagen) {
	global $_FILES;
	global $_SESSION;
	$path = "../imagenes/tmp/";
	$im_name = str_replace(' ', '_', $_FILES[$imagen]['name']);
	$im_type = $_FILES[$imagen]['type'];
	$im_size = $_FILES[$imagen]['size'];
	$im_tmp_name = $_FILES[$imagen]['tmp_name'];
	##
	if ($im_size > 10000000) {
		$_error = "Se produjo un error al cargar la imagen (Tamaño excedido)";
	} else {
		$partes =  explode("." , $im_name);
		$ruta = $path.$_SESSION["_USUARIO"]."_tmp_".$partes[0].".".$partes[1];
		$im = $_SESSION["_USUARIO"]."_tmp_".$partes[0].".".$partes[1];
		$existe = true;
		while($existe) {
			if(file_exists($ruta)) {
				unlink($ruta);
				$ran = rand(0, 1000000);
				$ruta = $path.$_SESSION["_USUARIO"]."_tmp_$ran".".".$partes[1];
				$im = $_SESSION["_USUARIO"]."_tmp_$ran".".".$partes[1];
			} else {
				$existe = false;
			}
		}
		if(!copy($im_tmp_name, $ruta)) {
			$_error = "Se produjo un error al cargar la imagen ($im_tmp_name, $ruta)";
			$im = "";
		}
	}
	return array($im, $_error);
}
//	---------------------------------

//	funcion para copiar una imagen
function copiarFoto($imagen, $nombre, $path) {
	global $_FILES;
	global $_SESSION;
	global $_PARAMETRO;
	$im_name = str_replace(' ', '_', $_FILES[$imagen]['name']);
	$im_type = $_FILES[$imagen]['type'];
	$im_size = $_FILES[$imagen]['size'];
	$im_tmp_name = $_FILES[$imagen]['tmp_name'];
	##
	if ($im_size > 10000000) {
		$_error = "Se produjo un error al cargar la imagen (Tamaño excedido)";
	} else {
		$partes =  explode("." , $im_name);
		$ruta = $path.$nombre.".".$partes[1];
		$im = $nombre.".".$partes[1];
		$existe = true;
		while($existe) {
			if(file_exists($ruta)) {
				unlink($ruta);
				$ran = rand(0, 1000000);
				$ruta = $path.$nombre."_$ran".".".$partes[1];
				$im = $nombre."_$ran".".".$partes[1];
			} else {
				$existe = false;
			}
		}
		if(!copy($im_tmp_name, $ruta)) {
			$_error = "Se produjo un error al cargar la imagen ($im_tmp_name, $ruta)";
			$im = "";
		}
	}
	return array($im, $_error);
}
//	---------------------------------

//	funcion para armar el lugar
function setLugar($CodCiudad) {
	$sql = "SELECT
				p.Pais,
				e.Estado,
				m.Municipio,
				c.Ciudad
			FROM
				mastciudades c
				INNER JOIN mastmunicipios m ON (m.CodMunicipio = c.CodMunicipio)
				INNER JOIN mastestados e ON (e.CodEstado = m.CodEstado)
				INNER JOIN mastpaises p ON (p.CodPais = e.CodPais)
			WHERE c.CodCiudad = '".$CodCiudad."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	while ($field = mysql_fetch_array($query)) {
		$r = "$field[Ciudad] ESTADO $field[Estado]";
	}
	return $r;
}
//	---------------------------------

// funcion para validar los dos dias adicionales por antiguedad
function getDiasAdicionalesTrimestral($Fingreso, $FechaDesde, $FechaHasta) {
	list($iAnio, $iMes, $iDia) = split("[-]", $Fingreso);
	list($dAnio, $dMes, $dDia) = split("[-]", $FechaDesde);
	list($hAnio, $hMes, $hDia) = split("[-]", $FechaHasta);
	list($sAnios, $sMeses, $sDias) = getTiempo(formatFechaDMA($Fingreso), formatFechaDMA($FechaHasta));
	##
	if ($iAnio <= '1997' && '06' >= $dMes && '06' < $hMes) $Cantidad = 2;
	elseif ($iAnio > '1997' && "$iMes-$iDia" >= "$dMes-$dDia" && "$iMes-$iDia" <= "$hMes-$hDia" && $sAnios >= 1) $Cantidad = 2;
	else $Cantidad = 0;
	return $Cantidad;
}
//	---------------------------------

//	funcion para obtener el complemento por los dos dias adicionales de antiguedad
function calculo_antiguedad_complemento_trimestral($CodPersona, $Fingreso, $FechaDesde, $FechaHasta) {
	if ($Fingreso <= "1997-06-30") $Fingreso = "1997-06-01";
	list($iAnio, $iMes, $iDia) = split("[-]", $Fingreso);
	list($dAnio, $dMes, $dDia) = split("[-]", $FechaDesde);
	list($hAnio, $hMes, $hDia) = split("[-]", $FechaHasta);
	##
	$mDesde = intval($iMes) + 1;
	if ($mDesde > 12) {
		$mDesde = 1;
		$aDesde = $hAnio;
	} else $aDesde = $hAnio - 1;
	if ($mDesde < 10) $mDesde = "0$mDesde";
	$dPeriodo = "$aDesde-$mDesde";
	$hPeriodo = "$hAnio-$iMes";
	
	//	obtengo los sueldos mensuales
	$sql = "SELECT Periodo, SueldoNormal
			FROM rh_sueldos
			WHERE
				CodPersona = '".$CodPersona."' AND
				Periodo >= '".$dPeriodo."' AND
				Periodo <= '".$hPeriodo."'";
	$query_sueldo = mysql_query($sql) or die ($sql.mysql_error());
	while($field_sueldo = mysql_fetch_array($query_sueldo)) {
		$periodo = $field_sueldo['Periodo'];
		$_PERIODOS[$periodo] = $periodo;
		$_SUELDO[$periodo] = $field_sueldo['SueldoNormal'];
	}
	
	//	obtengo bonos adicionales
	$sql = "SELECT Periodo, SUM(tnec.Monto) AS Monto
			FROM
				pr_tiponominaempleadoconcepto tnec
				INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto AND
											 c.Tipo = 'I' AND
											 c.FlagBonoRemuneracion = 'S')
			WHERE
				tnec.CodPersona = '".$CodPersona."' AND
				tnec.Periodo >= '".$dPeriodo."' AND
				tnec.Periodo <= '".$hPeriodo."'
			GROUP BY Periodo";
	$query_bonos = mysql_query($sql) or die ($sql.mysql_error());
	while($field_bonos = mysql_fetch_array($query_bonos)) {
		$periodo = $field_bonos['Periodo'];
		$_BONOS[$periodo] = $field_bonos['Monto'];
	}
	
	//	obtengo la alicuota vacacional
	$sql = "SELECT Periodo, Monto
			FROM pr_tiponominaempleadoconcepto
			WHERE
				CodPersona = '".$CodPersona."' AND
				Periodo >= '".$dPeriodo."' AND
				Periodo <= '".$hPeriodo."' AND
				CodConcepto = '0046'";
	$query_alivac = mysql_query($sql) or die ($sql.mysql_error());
	while($field_alivac = mysql_fetch_array($query_alivac)) {
		$periodo = $field_alivac['Periodo'];
		$_ALIVAC[$periodo] = $field_alivac['Monto'];
	}
	
	//	obtengo la alicuota fin de año
	$sql = "SELECT Periodo, Monto
			FROM pr_tiponominaempleadoconcepto
			WHERE
				CodPersona = '".$CodPersona."' AND
				Periodo >= '".$dPeriodo."' AND
				Periodo <= '".$hPeriodo."' AND
				CodConcepto = '0047'";
	$query_fin = mysql_query($sql) or die ($sql.mysql_error());
	while($field_fin = mysql_fetch_array($query_fin)) {
		$periodo = $field_fin['Periodo'];
		$_ALIFIN[$periodo] = $field_fin['Monto'];
	}
	
	//	obtengo los dias acumulados
	if ($Fingreso <= "1997-06-30") $FechaInicio = "1997-06-01"; else $FechaInicio = $Fingreso;
	list($sAnios, $sMeses, $sDias) = getTiempo(formatFechaDMA($FechaInicio), formatFechaDMA($FechaHasta));
	if ($Fingreso < "2011-05-01") $DiasAcumulados = ($sAnios - 1) * 2;
	else $DiasAcumulados = ($sAnios) * 2;
	
	//	operaciones
	$SueldoAlicuotas = 0;
	foreach ($_PERIODOS as $periodo) {
		$RemuneracionDiaria = round(($_SUELDO[$periodo] + $_BONOS[$periodo]) / 30, 2);
		$SueldoAlicuotas += $_ALIVAC[$periodo] + $_ALIFIN[$periodo] + $RemuneracionDiaria;
	}
	$Monto = $SueldoAlicuotas / 12 * $DiasAcumulados;
	return $Monto;
}
//	---------------------------------

//	obtener fecha fin a partir de una fecha inicial + dias
function obtenerFechaFin($FechaInicial, $Dias) {
	$sql = "SELECT ADDDATE('".formatFechaAMD($FechaInicial)."', ".intval($Dias-1).") AS FechaResultado";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	$field = mysql_fetch_array($query);
	return formatFechaDMA($field['FechaResultado']);
}
//	---------------------------------

//	head de las competencias
function printHeadCompetencias($TipoEvaluacion, $w, $h, $dif=0) {
	?>
	<tr style="background-color:#333;">
        <td style="padding-left:4px;">
		<?php
        //	consulto los grados
        $sql = "SELECT
        			PuntajeMin,
        			PuntajeMax,
        			(PuntajeMax - PuntajeMin + 1) AS Cols,
        			Descripcion
                FROM rh_gradoscompetencia
                WHERE TipoEvaluacion = '".$TipoEvaluacion."'
                ORDER BY PuntajeMin";
        $query_grados = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
        while($field_grados = mysql_fetch_array($query_grados)) {
            $grados[] = $field_grados;
        }
        
        //	imprimir los titulos
        foreach ($grados as $grado) {
	        $width = ($w * $grado['Cols']) + (($grado['Cols'] - $dif) * 3);
            ?><div class="divHeadCompetencias" style="width:<?=$width?>px; height:<?=$h?>px;"><?=$grado['Descripcion']?></div><?
        }
		?><br style="clear:both;" /><?
        foreach ($grados as $grado) {
        	for ($i=$grado['PuntajeMin']; $i<=$grado['PuntajeMax']; $i++) {
            	?><div class="divHeadCompetencias" style="width:<?=$w?>px; height:15px;"><?=$i?></div><?
        	}
        }
        ?>
        </td>
    </tr>
	<?
}
//	---------------------------------

//	head de las competencias
function printBodyCompetenciasCargo($CodCargo, $TipoEvaluacion, $w, $h) {
	//	consulto los grados
	$sql = "SELECT
       			PuntajeMin,
       			PuntajeMax,
       			(PuntajeMax - PuntajeMin + 1) AS Cols,
       			Descripcion
    		FROM rh_gradoscompetencia
    		WHERE TipoEvaluacion = '".$TipoEvaluacion."'
    		ORDER BY PuntajeMin";
    $query_grados = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
    while($field_grados = mysql_fetch_array($query_grados)) {
    	$grados[] = $field_grados;
    }
    
    $nro_competencias = 0;
	//	consulto datos generales
	$sql = "SELECT
				ef.Competencia,
				ef.Descripcion,
				ef.TipoCompetencia,
				ef.ValorRequerido,
				ef.ValorMinimo,
				md.Descripcion AS NomTipoCompetencia
			FROM
				rh_cargocompetencia cc
				INNER JOIN rh_evaluacionfactores ef ON (ef.Competencia = cc.Competencia)
				LEFT JOIN mastmiscelaneosdet md ON (md.CodDetalle = ef.TipoCompetencia AND
													md.CodAplicacion = 'RH' AND
													md.CodMaestro = 'TIPOCOMPE')
			WHERE cc.CodCargo = '".$CodCargo."'
			ORDER BY NomTipoCompetencia, TipoCompetencia, Competencia";
	$query_competencias = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	while ($field_competencias = mysql_fetch_array($query_competencias)) {	$i++;
		$nro_competencias++;
		if ($grupo != $field_competencias['TipoCompetencia']) {
			$grupo = $field_competencias['TipoCompetencia'];
			?>
            <tr class="trListaBody2">
                <td><?=htmlentities($field_competencias['NomTipoCompetencia'])?></td>
            </tr>
            <?
		}
		?>
		<tr class="trListaBody">
			<td>
				<strong><?=htmlentities($field_competencias['Descripcion'])?></strong><br />
                <?
				//	imprimo valor requerido
				foreach ($grados as $grado) {
					for ($i=$grado['PuntajeMin']; $i<=$grado['PuntajeMax']; $i++) {
						if ($field_competencias['ValorRequerido'] >= $i) $style = "background-color:#000;"; else $style = "";
            			?><div class="divBodyCompetencias" style=" <?=$style?> width:<?=$w?>px; height:<?=$h?>px;"></div><?
        			}
				}
				?><br style="clear:both;" /><?
				//	imprimo valor minimo
				foreach ($grados as $grado) {
					for ($i=$grado['PuntajeMin']; $i<=$grado['PuntajeMax']; $i++) {
						if ($field_competencias['ValorMinimo'] >= $i) $style = "background-color:#5F160E;"; else $style = "";
            			?><div class="divBodyCompetencias" style=" <?=$style?> width:<?=$w?>px; height:<?=$h?>px;"></div><?
        			}
				}
				?>
            </td>
		</tr>
		<?
	}
}
//	---------------------------------

//	devuelve el valor de un campo
function getUT($Anio, $Secuencia=NULL) {
	if ($Secuencia != "") {
		$filtro_secuencia .= " AND ut.Secuencia = '".$Secuencia."'";
	} else {
		$filtro_maximo = " AND ut.Secuencia = (SELECT MAX(Secuencia) FROM mastunidadtributaria WHERE Anio = ut.Anio)";
	}
	$sql = "SELECT ut.Valor
			FROM mastunidadtributaria ut
			WHERE ut.Anio = '".$Anio."' $filtro_secuencia $filtro_maximo
			LIMIT 0, 1";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	return $field[0];
}
//	---------------------------------

//	valido si hay presupuesto para una partida en especifico
function valPresupuesto($CodOrganismo, $EjercicioPpto, $cod_partida, $Monto) {
	$sql = "SELECT (pvd.MontoAjustado - pvd.MontoCompromiso) AS MontoDisponible
			FROM
				pv_presupuesto pv
				LEFT JOIN pv_presupuestodet pvd ON (pvd.Organismo = pv.Organismo AND
													pvd.CodPresupuesto = pv.CodPresupuesto)
			WHERE
				pv.Organismo = '".$CodOrganismo."' AND
				pv.EjercicioPpto = '".$EjercicioPpto."' AND
				pvd.cod_partida = '".$cod_partida."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	if ($Monto <= $field['MontoDisponible']) return true; else return false;
}
//	---------------------------------

//	
function totalTiempo($TotalTiempoA, $TotalTiempoM, $TotalTiempoD) {
	$_Dias_A_Meses = intval($TotalTiempoD / 30);
	$TotalTiempoM += $_Dias_A_Meses;
	$_Meses_A_Anios = intval($TotalTiempoM / 12);
	$TotalTiempoA += $_Meses_A_Anios;
	$_Dias = $TotalTiempoD - (intval($TotalTiempoD / 30) * 30);
	$_Meses = $TotalTiempoM - (intval($TotalTiempoM / 12) * 12);
	$_Anios = $TotalTiempoA;
	return array(intval($_Anios), intval($_Meses), intval($_Dias));
}
//	---------------------------------

//	CARGAR SELECT ORDENAR
function cargarSelect($select, $campo, $opt) {
	switch ($select) {
		case "NIVELES-CUENTAS":
			$value[0] = "1"; $text[0] = "Grupo";
			$value[1] = "2"; $text[1] = "Sub-Grupo";
			$value[2] = "3"; $text[2] = "Rubro";
			$value[3] = "4"; $text[3] = "Cuenta";
			$value[4] = "5"; $text[4] = "Sub-Cuenta de Primer Orden";
			$value[5] = "6"; $text[5] = "Sub-Cuenta de Segundo Orden";
			$value[6] = "7"; $text[6] = "Sub-Cuenta Anexa";
			break;
			
		case "ESTADO":
			$value[0] = "A"; $text[0] = "Activo";
			$value[1] = "I"; $text[1] = "Inactivo";
			break;
			
		case "TIPO-CENTRO-COSTO":
			$value[0] = "A"; $text[0] = "Administrativo";
			$value[1] = "O"; $text[1] = "Otro";
			
			break;
			
		case "PROVISION":
			$value[0] = "N"; $text[0] = "Provisión del Documento";
			$value[1] = "P"; $text[1] = "Pago del Documento";
			break;
			
		case "MONTO-IMPONIBLE":
			$value[0] = "N"; $text[0] = "Monto Afecto";
			$value[1] = "I"; $text[1] = "IGV/IVA";
			break;
			
		case "CLASIFICACION-CXP":
			$value[0] = "O"; $text[0] = "Obligaciones";
			$value[1] = "C"; $text[1] = "Otros de Ctas. Por Pagar";
			$value[2] = "P"; $text[2] = "Prestamos";
			$value[3] = "E"; $text[3] = "Otros Externos";
			break;
	}
	
	$i = 0;
	switch ($opt) {
		case 0:
			foreach ($value AS $valor) {
				if ($campo == $valor) { ?> <option value="<?=$valor?>" selected="selected"><?=htmlentities($text[$i])?></option> <? }
				else { ?> <option value="<?=$valor?>"><?=htmlentities($text[$i])?></option> <? }
				$i++;
			}
			break;
			
		case 1:
			foreach ($value AS $valor) {
				if ($campo == $valor) { ?> <option value="<?=$valor?>" selected="selected"><?=htmlentities($text[$i])?></option> <? }
				$i++;
			}
			break;
	}
	
}
//	---------------------------------
?>
