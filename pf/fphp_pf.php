<?php
session_start();
set_time_limit(-1);
ini_set('memory_limit','128M');  
include ("../funciones.php");

//	FUNCION PARA CONECTARSE CON EL SERVIDO MYSQL
function connect() {
	mysql_connect($_SESSION["MYSQL_HOST"], $_SESSION["MYSQL_USER"], $_SESSION["MYSQL_CLAVE"]) or die ("NO SE PUDO CONECTAR CON EL SERVIDOR MYSQL!");
mysql_select_db($_SESSION["MYSQL_BD"]) or die ("NO SE PUDO CONECTAR CON LA BASE DE DATOS!");
}

//	FUNCION PARA GENERAR UN NUEVO CODIGO
function getCodigo($tabla, $campo, $digitos) {
	connect();
	$sql="select max($campo) FROM $tabla";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$field=mysql_fetch_array($query);
	$codigo=(int) ($field[0]+1);
	$codigo=(string) str_repeat("0", $digitos-strlen($codigo)).$codigo;
	return ($codigo);
}

//	FUNCION PARA GENERAR UN NUEVO CODIGO
function getCodigo_2($tabla, $campo, $correlativo, $valor, $digitos) {
	connect();
	$sql="select max($campo) FROM $tabla WHERE $correlativo = '$valor'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$field=mysql_fetch_array($query);
	$codigo=(int) ($field[0]+1);
	$codigo=(string) str_repeat("0", $digitos-strlen($codigo)).$codigo;
	return ($codigo);
}

//	FUNCION PARA GENERAR UN NUEVO CODIGO
function getCodigo_3($tabla, $campo, $correlativo1, $correlativo2, $valor1, $valor2, $digitos) {
	connect();
	$sql="select max($campo) FROM $tabla WHERE $correlativo1 = '$valor1' AND $correlativo2 = '$valor2'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$field=mysql_fetch_array($query);
	$codigo=(int) ($field[0]+1);
	$codigo=(string) str_repeat("0", $digitos-strlen($codigo)).$codigo;
	return ($codigo);
}

//	FUNCION PARA CONFIRMAR LOS PERMISOS DEL USUARIO
function opcionesPermisos($grupo, $concepto) {
	$sql="SELECT FlagAdministrador FROM seguridad_autorizaciones WHERE CodAplicacion='".$_SESSION['APLICACION_ACTUAL']."' AND Usuario='".$_SESSION['USUARIO_ACTUAL']."' AND Estado='A'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	if ($rows!=0) $field=mysql_fetch_array($query);
	$_ADMIN=$field['FlagAdministrador'];
	//	--------------------------------------------
	$sql="SELECT FlagMostrar, FlagAgregar, FlagModificar, FlagEliminar FROM seguridad_autorizaciones WHERE CodAplicacion='".$_SESSION['APLICACION_ACTUAL']."' AND Usuario='".$_SESSION['USUARIO_ACTUAL']."' AND Grupo='".$grupo."' AND Concepto='".$concepto."' AND Estado='A'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	if ($rows!=0) $field=mysql_fetch_array($query);
	$_SHOW=$field['FlagMostrar'];
	$_INSERT=$field['FlagAgregar'];
	$_UPDATE=$field['FlagModificar'];
	$_DELETE=$field['FlagEliminar'];
	return array($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE);	
}

//	FUNCION PARA CARGAR SELECTS
function loadSelect($tabla, $campo1, $campo2, $codigo, $opt) {
	switch ($opt) {
		case 0:
			$sql = "SELECT $campo1, $campo2 FROM $tabla ORDER BY $campo1";
			$query = mysql_query($sql) or die ($sql.mysql_error());
			while ($field = mysql_fetch_array($query)) {
				if ($field[0] == $codigo) { ?><option value="<?=$field[0]?>" selected="selected"><?=htmlentities($field[1])?></option><? }
				else { ?><option value="<?=$field[0]?>"><?=htmlentities($field[1])?></option><? }
			}
			break;
			
		case 1:
			$sql = "SELECT $campo1, $campo2 FROM $tabla WHERE $campo1 = '$codigo'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
			while ($field = mysql_fetch_array($query)) {
				?><option value="<?=$field[0]?>" selected="selected"><?=htmlentities($field[1])?></option><?
			}
			break;
	}
}

//	FUNCION PARA CARGAR SELECTS
function loadSelectDependiente($tabla, $campo1, $campo2, $campo3, $codigo1, $codigo2, $opt) {
	switch ($opt) {
		case 0:
			$sql = "SELECT $campo1, $campo2 FROM $tabla WHERE $campo3 = '$codigo2' ORDER BY $campo1";
			$query = mysql_query($sql) or die ($sql.mysql_error());
			while ($field = mysql_fetch_array($query)) {
				if ($field[0] == $codigo1) { ?><option value="<?=$field[0]?>" selected="selected"><?=htmlentities($field[1])?></option><? }
				else { ?><option value="<?=$field[0]?>"><?=htmlentities($field[1])?></option><? }
			}
			break;
			
		case 1:
			$sql = "SELECT $campo1, $campo2 FROM $tabla WHERE $campo1 = '$codigo1'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
			while ($field = mysql_fetch_array($query)) {
				?><option value="<?=$field[0]?>" selected="selected"><?=htmlentities($field[1])?></option><?
			}
			break;
	}
}

//	FUNCION PARA CARGAR EL MISCELANEO EN UN SELECT
function getMiscelaneos($detalle, $maestro, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT CodDetalle, Descripcion FROM mastmiscelaneosdet WHERE CodMaestro='$maestro' ORDER BY CodDetalle";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$detalle) echo "<option value='".$field[0]."' selected>".htmlentities($field[1])."</option>";
				else echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
		case 1:
			$sql="SELECT CodDetalle, Descripcion FROM mastmiscelaneosdet WHERE CodMaestro='$maestro' AND CodDetalle='$detalle'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR SELECTS
function loadSelectOrganismosExternos($control, $codigo, $opt) {
	switch ($opt) {
		case 0:
			$sql = "SELECT CodOrganismo, Organismo FROM pf_organismosexternos WHERE Control = '".$control."' ORDER BY CodOrganismo";
			$query = mysql_query($sql) or die ($sql.mysql_error());
			while ($field = mysql_fetch_array($query)) {
				if ($field[0] == $codigo) { ?><option value="<?=$field[0]?>" selected="selected"><?=htmlentities($field[1])?></option><? }
				else { ?><option value="<?=$field[0]?>"><?=htmlentities($field[1])?></option><? }
			}
			break;
			
		case 1:
			$sql = "SELECT CodOrganismo, Organismo FROM pf_organismosexternos WHERE CodOrganismo = '".$codigo."' ORDER BY CodOrganismo";
			$query = mysql_query($sql) or die ($sql.mysql_error());
			while ($field = mysql_fetch_array($query)) {
				?><option value="<?=$field[0]?>" selected="selected"><?=htmlentities($field[1])?></option><?
			}
			break;
			
		case 2:
			$i = 0;
			$sql = "SELECT CodOrganismo, Organismo, Control
					FROM pf_organismosexternos
					ORDER BY Control, CodOrganismo";
			$query = mysql_query($sql) or die ($sql.mysql_error());
			while ($field = mysql_fetch_array($query)) {
				$i++;
				if ($grupo != $field['Control']) {
					$grupo = $field['Control'];
					$ambito = strtoupper(printValores("AMBITO_ACTUACION", $field['Control']));
					if ($i > 1) echo "</optgroup>";
					echo "<optgroup label='$ambito'>";
				}
				if ($field[0] == $codigo) {
					?><option value="<?=$field[0]?>" selected="selected"><?=htmlentities($field[1])?></option><?
				} else {
					?><option value="<?=$field[0]?>"><?=htmlentities($field[1])?></option><?
				}
			}
			echo "</optgroup>";
			break;
	}
}

//	FUNCION PARA CARGAR SELECTS 
function loadSelectValores($tabla, $codigo, $opt) {
	switch ($tabla) {
		case "ESTADO":
			$c[0] = "A"; $v[0] = "Activo";
			$c[1] = "I"; $v[1] = "Inactivo";
			break;
		
		case "ESTADO-ACTUACION":
			$c[0] = "PR"; $v[0] = "En Preparación";
			$c[1] = "RV"; $v[1] = "Revisado";
			$c[2] = "AP"; $v[2] = "Aprobado";
			$c[3] = "AN"; $v[3] = "Anulado";
			$c[4] = "CO"; $v[4] = "Completado";
			$c[5] = "EJ"; $v[5] = "En Ejecución";
			$c[6] = "TE"; $v[6] = "Terminado";
			$c[7] = "AU"; $v[7] = "Auto de Archivo";
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

//	FUNCION PARA IMPRIMIR EN UNA TABLA VALORES
function printValores($tabla, $codigo) {
	switch ($tabla) {
		case "ESTADO":
			$c[0] = "A"; $v[0] = "Activo";
			$c[1] = "I"; $v[1] = "Inactivo";
			break;
		
		case "ESTADO-ACTUACION":
			$c[0] = "PR"; $v[0] = "En Preparación";
			$c[1] = "RV"; $v[1] = "Revisado";
			$c[2] = "AP"; $v[2] = "Aprobado";
			$c[3] = "AN"; $v[3] = "Anulado";
			$c[4] = "CO"; $v[4] = "Completado";
			$c[5] = "EJ"; $v[5] = "En Ejecución";
			$c[6] = "TE"; $v[6] = "Terminado";
			$c[7] = "AU"; $v[7] = "Auto de Archivo";
			break;
			
		case "AMBITO_ACTUACION":
			$c[0] = "AC"; $v[0] = "Administración Central";
			$c[1] = "AD"; $v[1] = "Administración Desentralizada";
			break;
	}
	
	$i=0;
	foreach ($c as $cod) {
		if ($cod == $codigo) return $v[$i];
		$i++;
	}
}

//	FUNCION PARA CARGAR LOS ORGANISMOS EN UN SELECT
function getOrganismos($organismo, $opt) {
	connect();
	if ($opt==3 && $_SESSION["USUARIO_ACTUAL"]==$_SESSION["SUPER_USUARIO"]) $opt=0;
	switch ($opt) {
		case 0:
			$sql="SELECT CodOrganismo, Organismo FROM mastorganismos WHERE CodOrganismo<>'' ORDER BY CodOrganismo";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$organismo) echo "<option value='".$field[0]."' selected>".htmlentities($field[1])."</option>"; 
				else echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
		case 1:
			$sql="SELECT CodOrganismo, Organismo FROM mastorganismos WHERE CodOrganismo='$organismo'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
		case 3:
			$sql="SELECT s.CodOrganismo, o.Organismo FROM seguridad_alterna s INNER JOIN mastorganismos o ON (s.CodOrganismo=o.CodOrganismo) WHERE s.Usuario='".$_SESSION["USUARIO_ACTUAL"]."' AND s.CodAplicacion='".$_SESSION["APLICACION_ACTUAL"]."' AND s.FlagMostrar='S' GROUP BY s.CodOrganismo ORDER BY s.CodOrganismo";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$organismo) echo "<option value='".$field[0]."' selected>".htmlentities($field[1])."</option>"; 
				else echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LAS DEPENDENCIAS EN UN SELECT
function getDependencias($dependencia, $organismo, $opt) {
	connect();
	if ($opt==3 && $_SESSION["USUARIO_ACTUAL"]==$_SESSION["SUPER_USUARIO"]) $opt=0;
	switch ($opt) {
		case 0:
			$sql="SELECT CodDependencia, Dependencia FROM mastdependencias WHERE CodOrganismo='".$organismo."' AND CodDependencia<>'' ORDER BY CodDependencia";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$dependencia) echo "<option value='".$field[0]."' selected>".htmlentities($field[1])."</option>"; 
				else echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
		case 1:
			$sql="SELECT CodDependencia, Dependencia FROM mastdependencias WHERE CodDependencia='".$dependencia."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
		case 3:
			$sql="SELECT s.CodDependencia, o.Dependencia FROM seguridad_alterna s INNER JOIN mastdependencias o ON (s.CodDependencia=o.CodDependencia) WHERE s.Usuario='".$_SESSION["USUARIO_ACTUAL"]."' AND s.CodAplicacion='".$_SESSION["APLICACION_ACTUAL"]."' AND s.FlagMostrar='S' AND s.CodOrganismo='$organismo' GROUP BY s.CodDependencia ORDER BY s.CodDependencia";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$dependencia) echo "<option value='".$field[0]."' selected>".htmlentities($field[1])."</option>"; 
				else echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR SELECTS
function loadSelectActuaciones($codigo, $opt) {
	switch ($opt) {
		case 0:
			$sql = "SELECT af.CodActuacion, af.ObjetivoGeneral
					FROM pf_actuacionfiscal af
					WHERE
						af.Estado = 'AP' AND
						af.CodActuacion IN (SELECT CodActuacion
											FROM pf_actuacionfiscaldetalle
											WHERE 
												CodActuacion = af.CodActuacion AND
												Estado = 'EJ')
					ORDER BY CodActuacion";
			$query = mysql_query($sql) or die ($sql.mysql_error());
			while ($field = mysql_fetch_array($query)) {
				if ($field[0] == $codigo) { ?><option value="<?=$field[0]?>" selected="selected"><?=htmlentities($field[1])?></option><? }
				else { ?><option value="<?=$field[0]?>"><?=$field[0]?> <?=htmlentities($field[1])?></option><? }
			}
			break;
			
		case 1:
			$sql = "SELECT af.CodActuacion, af.ObjetivoGeneral
					FROM pf_actuacionfiscal af
					WHERE CodActuacion = '".$codactuacion."'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
			while ($field = mysql_fetch_array($query)) {
				?><option value="<?=$field[0]?>" selected="selected"><?=$field[0]?> <?=htmlentities($field[1])?></option><?
			}
			break;
	}
}

//	FUNCION PARA IMPRIMIR UN CHECK
function printFlag($check) {
	if ($check == "S") $flag = "<img src='../imagenes/flag.png' />";
	return $flag;
}

//	FUNCION PARA IMPRIMIR UN CHECK
function printFlagForm($check, $id, $disabled) {
	if ($check == "S") $c = "checked";
	$flag = "<input type='checkbox' id='$id' $c $disabled />";
	return $flag;
}

//
function formatFechaDMA($fecha) {
	list($a, $m, $d) = SPLIT( '[/.-]', $fecha);
	if ($a == "0000" || $a == "") return ""; else return "$d-$m-$a";
}

//
function formatFechaAMD($fecha) {
	list($d, $m, $a) = SPLIT( '[/.-]', $fecha);
	if ($a == "0000" || $a == "") return ""; else return "$a-$m-$d";
}

//	FUNCION PARA IMPRIMIR UN CHECK
function printEstadoActuacion($estado) {
	if ($estado == "PR" || $estado == "PE") $flag = "<img src='../imagenes/circle_red.png' width='16' height='16' />";
	elseif ($estado == "EJ") $flag = "<img src='../imagenes/circle_green.png' width='16' height='16' />";
	elseif ($estado == "TE") $flag = "<img src='../imagenes/checked.png' width='16' height='16' />";
	elseif ($estado == "AU") $flag = "<img src='../imagenes/database_48.png' width='16' height='16' />";
	return $flag;
}

//	
function getCodInternoDependencia($dependencia) {
	$sql = "SELECT CodInterno FROM mastdependencias WHERE CodDependencia = '".$dependencia."'";
	$query = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	return $field[0];
}
?>