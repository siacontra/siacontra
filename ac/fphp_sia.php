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
function getSecuencia($secuencia, $codigo, $tabla, $valor) {
	connect();
	$sql="select MAX($secuencia) FROM $tabla WHERE $codigo='$valor'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$field=mysql_fetch_array($query);
	$secuencia=(int) ($field[0]+1);
	return ($secuencia);
}

//	FUNCION PARA GENERAR UN NUEVO CODIGO
function getSecuencia_0($secuencia, $codigo, $tabla, $valor) {
	connect();
	$sql = "SELECT * FROM $tabla WHERE $codigo = '$valor'";
	$query = mysql_query ($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) {
		$sql="select MAX($secuencia) FROM $tabla WHERE $codigo='$valor'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$field=mysql_fetch_array($query);
		$secuencia=(int) ($field[0]+1);
		return ($secuencia);
	} else return 0;
	
}

//	FUNCION PARA GENERAR UN NUEVO CODIGO
function getSecuencia2($secuencia, $codigo1, $codigo2, $tabla, $valor1, $valor2) {
	connect();
	$sql="select max($secuencia) FROM $tabla WHERE $codigo1='$valor1' AND $codigo2='$valor2'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$field=mysql_fetch_array($query);
	$secuencia=(int) ($field[0]+1);
	return ($secuencia);
}

//	FUNCION PARA GENERAR UN NUEVO CODIGO
function getSecuencia3($secuencia, $codigo1, $codigo2, $codigo3, $tabla, $valor1, $valor2, $valor3) {
	connect();
	$sql="select max($secuencia) FROM $tabla WHERE $codigo1='$valor1' AND $codigo2='$valor2' AND $codigo3='$valor3'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$field=mysql_fetch_array($query);
	$secuencia=(int) ($field[0]+1);
	return ($secuencia);
}

//	FUNCION PARA GENERAR UN NUEVO CODIGO
function getSecuencia5($secuencia, $codigo1, $codigo2, $codigo3, $codigo4, $codigo5, $tabla, $valor1, $valor2, $valor3, $valor4, $valor5) {
	connect();
	$sql="select max($secuencia) FROM $tabla WHERE $codigo1='$valor1' AND $codigo2='$valor2' AND $codigo3='$valor3' AND $codigo4='$valor4' AND $codigo5='$valor5'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$field=mysql_fetch_array($query);
	$secuencia=(int) ($field[0]+1);
	return ($secuencia);
}

function getCorrelativo($tabla, $campo, $annio, $digito) {
	$sql="SELECT MAX($campo) FROM $tabla WHERE ($campo LIKE '$annio%')";
	$query=mysql_query($sql) or die ($sql.mysql_error($sql));
	$rows=mysql_num_rows($query);
	if ($rows!=0) $field=mysql_fetch_array($query);
	if ($field[0]==0) $correlativo=$annio."000001"; else $correlativo=$field[0]+1;
	return $correlativo;
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

//	FUNCION PARA CARGAR SELECT
function getGrupoCentroCosto($codigo, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT CodGrupoCentroCosto, Descripcion FROM ac_mastcentrocosto ORDER BY CodGrupoCentroCosto";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$codigo) echo "<option value='".$field[0]."' selected>".htmlentities($field[1])."</option>"; 
				else echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
			
		case 1:
			$sql="SELECT CodGrupoCentroCosto, Descripcion FROM ac_mastcentrocosto WHERE CodGrupoCentroCosto = '".$codigo."' ORDER BY CodGrupoCentroCosto";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
	}
}

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

//	FUNCION PARA IMPRIMIR EN UNA TABLA VALORES
function printValores($tabla, $codigo) {
	switch ($tabla) {
		case "ESTADO":
			$c[0] = "A"; $v[0] = "Activo";
			$c[1] = "I"; $v[1] = "Inactivo";
			break;
			
		case "PROVISION":
			$c[0] = "N"; $v[0] = "Provisión del Documento";
			$c[1] = "P"; $v[1] = "Pago del Documento";
			break;
			
		case "MONTO-IMPONIBLE":
			$c[0] = "N"; $v[0] = "Monto Afecto";
			$c[1] = "I"; $v[1] = "IGV/IVA";
			break;
			
		case "SIGNO":
			$c[0] = "P"; $v[0] = "+";
			$c[1] = "N"; $v[1] = "-";
			break;
			
		case "CLASIFICACION-CXP":
			$c[0] = "O"; $v[0] = "Obligaciones";
			$c[1] = "C"; $v[1] = "Otros de Ctas. Por Pagar";
			$c[2] = "P"; $v[2] = "Prestamos";
			$c[3] = "E"; $v[3] = "Otros Externos";
			break;
	}
	
	$i=0;
	foreach ($c as $cod) {
		if ($cod == $codigo) return htmlentities($v[$i]);
		$i++;
	}
}

//	FUNCION PARA IMPRIMIR UN CHECK
function printFlag($check) {
	if ($check == "S") $flag = "<img src='../imagenes/flag.png' />";
	return $flag;
}

//	FUNCION PARA CONFIRMAR LOS PERMISOS DEL USUARIO
function opcionesPermisos($grupo, $concepto) {
	$sql="SELECT FlagAdministrador FROM seguridad_autorizaciones WHERE CodAplicacion='".$_SESSION['APLICACION_ACTUAL']."' AND Usuario='".$_SESSION['USUARIO_ACTUAL']."' AND Estado='A'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	if ($rows!=0) $field=mysql_fetch_array($query);
	$_ADMIN=$field['FlagAdministrador'];
	//	--------------------------------------------
	$sql="SELECT FlagMostrar, FlagAgregar, FlagModificar, FlagEliminar FROM seguridad_autorizaciones WHERE CodAplicacion='".$_SESSION['APLICACION_ACTUAL']."' AND Usuario='".$_SESSION['USUARIO_ACTUAL']."' AND Concepto='".$concepto."' AND Estado='A'";
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


//	FUNCTION PARA IMPRIMIR EL ESTILO DE LA PARTIDA
function stylePartida($nivel) {
	if ($nivel == "1") return "style='font-weight:bold; font-size:12px;'";
	elseif ($nivel == "2") return "style='font-weight:bold; font-size:10px;'";
	elseif ($nivel == "3") return "style='text-decoration:underline; font-size:10px;'";
	else return "";
}
?>
