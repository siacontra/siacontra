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
function loadSelectValores($tabla, $codigo, $opt) {
	switch ($tabla) {
		case "ESTADO":
			$c[0] = "A"; $v[0] = "Activo";
			$c[1] = "I"; $v[1] = "Inactivo";
			break;
			
		case "TIPOALMACEN":
			$c[0] = "P"; $v[0] = "Principal";
			$c[1] = "T"; $v[1] = "Tránsito";
			$c[2] = "V"; $v[2] = "Venta";
			break;
			
		case "BUSCAR-ITEMS":
			$c[0] = "i.CodItem"; $v[0] = "Código";
			$c[1] = "i.Descripcion"; $v[1] = "Descripción";
			$c[2] = "i.CodLinea"; $v[2] = "Linea";
			$c[3] = "i.CodFamilia"; $v[3] = "Familia";
			$c[4] = "i.CodSubFamilia"; $v[4] = "Sub-Familia";
			$c[5] = "i.CodInterno"; $v[5] = "Cod. Interno";
			break;
			
		case "BUSCAR-REQUERIMIENTOS":
			$c[0] = "lr.CodRequerimiento"; $v[0] = "# Requerimiento";
			$c[1] = "lr.Descripcion"; $v[1] = "Descripción";
			$c[2] = "lr.CodCentroCosto"; $v[2] = "Centro de Costo";
			$c[3] = "lc.Descripcion"; $v[3] = "Clasificación";
			break;
			
		case "BUSCAR-REQUERIMIENTOS-DET":
			$c[0] = "lrd.CodRequerimiento"; $v[0] = "# Requerimiento";
			$c[1] = "lrd.CodItem|lrd.CommodityMast"; $v[1] = "Código";
			$c[2] = "lrd.Descripcion"; $v[2] = "Descripción";
			$c[3] = "lrd.CodCentroCosto"; $v[3] = "Centro de Costo";
			$c[4] = "d.Dependencia"; $v[4] = "Dependencia";
			$c[5] = "lc.Descripcion"; $v[5] = "Clasificación";
			break;
		
		case "DIRIGIDO":
			$c[0] = "C"; $v[0] = "Compras";
			$c[1] = "A"; $v[1] = "Almacen";
			break;
		
		case "PRIORIDAD":
			$c[0] = "N"; $v[0] = "Normal";
			$c[1] = "U"; $v[1] = "Urgente";
			$c[2] = "M"; $v[2] = "Muy Urgente";
			break;
		
		case "ESTADO-REQUERIMIENTO":
			$c[0] = "P"; $v[0] = "En Preparación";
			$c[1] = "R"; $v[1] = "Revisado";
			$c[2] = "A"; $v[2] = "Aprobado";
			$c[3] = "N"; $v[3] = "Anulado";
			$c[4] = "E"; $v[4] = "Rechazado";
			$c[5] = "C"; $v[5] = "Cerrado";
			break;
		
		case "ESTADO-REQUERIMIENTO-DET":
			$c[0] = "P"; $v[0] = "En Preparación";
			$c[1] = "E"; $v[1] = "Pendiente";
			$c[2] = "R"; $v[2] = "Revisado";
			$c[3] = "N"; $v[3] = "Anulado";
			$c[4] = "C"; $v[4] = "Cerrado";
			$c[5] = "O"; $v[5] = "Completado";
			break;
		
		case "ESTADO-REQUERIMIENTO-DET1":
			$c[0] = "P"; $v[0] = "En Preparación";
			break;
	}
	
	$i = 0;
	switch ($opt) {
		case 0:
			foreach ($c as $cod) {
				if ($cod == $codigo) echo "<option value='".$cod."' selected>".htmlentities($v[$i])."</option>";
				else echo "<option value='".$cod."'>".htmlentities($v[$i])."</option>";
				$i++;
			}
			break;
			
		case 1:
			foreach ($c as $cod) {
				if ($cod == $codigo) echo "<option value='".$cod."' selected>".htmlentities($v[$i])."</option>";
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
			
		case "TIPOALMACEN":
			$c[0] = "P"; $v[0] = "Principal";
			$c[1] = "T"; $v[1] = "Tránsito";
			$c[2] = "V"; $v[2] = "Venta";
			break;
			
		case "ALMACENCOMPRA":
			$c[0] = "A"; $v[0] = "Almacén";
			$c[1] = "C"; $v[1] = "Compra";
			break;
			
		case "TIPOMOVIMIENTO":
			$c[0] = "I"; $v[0] = "Ingreso";
			$c[1] = "E"; $v[1] = "Egreso";
			$c[2] = "T"; $v[2] = "Transferencia";
			break;
		
		case "DIRIGIDO":
			$c[0] = "C"; $v[0] = "Compras";
			$c[1] = "A"; $v[1] = "Almacen";
			break;
		
		case "PRIORIDAD":
			$c[0] = "N"; $v[0] = "Normal";
			$c[1] = "U"; $v[1] = "Urgente";
			$c[2] = "M"; $v[2] = "Muy Urgente";
			break;
		
		case "ESTADO-REQUERIMIENTO":
			$c[0] = "P"; $v[0] = "En Preparación";
			$c[1] = "R"; $v[1] = "Revisado";
			$c[2] = "A"; $v[2] = "Aprobado";
			$c[3] = "N"; $v[3] = "Anulado";
			$c[4] = "E"; $v[4] = "Rechazado";
			$c[5] = "C"; $v[5] = "Cerrado";
			break;
		
		case "ESTADO-REQUERIMIENTO-DET":
			$c[0] = "P"; $v[0] = "En Preparación";
			$c[1] = "E"; $v[1] = "Pendiente";
			$c[2] = "R"; $v[2] = "Revisado";
			$c[3] = "N"; $v[3] = "Anulado";
			$c[4] = "C"; $v[4] = "Cerrado";
			$c[5] = "O"; $v[5] = "Completado";
			break;
	}
	
	$i=0;
	foreach ($c as $cod) {
		if ($cod == $codigo) return htmlentities($v[$i]);
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

//	FUNCION PARA IMPRIMIR UN CHECK
function printFlag($check) {
	if ($check == "S") $flag = "<img src='../imagenes/flag.png' />";
	return $flag;
}

//
function formatFechaDMA($fecha) {
	list($a, $m, $d)=SPLIT( '[/.-]', $fecha);
	return "$d-$m-$a";
}

//
function formatFechaAMD($fecha) {
	list($a, $m, $d)=SPLIT( '[/.-]', $fecha);
	return "$a-$m-$d";
}
?>

