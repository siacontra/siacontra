<?php
session_start();
set_time_limit(-1);
ini_set('memory_limit','128M');
include ("../funciones.php");
extract($_POST);
extract($_GET);
$_PARAMETRO = parametros();

// FUNCION PARA OBTENER LOS PARAMETROS DEL SISTEMA
function parametros() {	
	connect();
	$sql = "SELECT * 
			FROM mastparametros 
			WHERE 
				(CodAplicacion = 'GE' OR CodAplicacion = '".$_SESSION["APLICACION_ACTUAL"]."') AND
				Estado = 'A'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	while ($field = mysql_fetch_array($query)) {
		$id = $field['ParametroClave'];
		$_PARAMETRO[$id] = $field['ValorParam'];
	}
	return $_PARAMETRO;
}

//	FUNCION PARA CONECTARSE CON EL SERVIDO MYSQL
function connect() {
	mysql_connect($_SESSION["MYSQL_HOST"], $_SESSION["MYSQL_USER"], $_SESSION["MYSQL_CLAVE"]) or die ("NO SE PUDO CONECTAR CON EL SERVIDOR MYSQL!");
mysql_select_db($_SESSION["MYSQL_BD"]) or die ("NO SE PUDO CONECTAR CON LA BASE DE DATOS!");
	mysql_query("SET NAMES 'utf8'");
	
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

//	
function getCorrelativoSecuencia_2($tabla, $campo1, $campo2, $valor1, $valor2) {
	connect();
	$sql = "SELECT $campo1, $campo2 FROM $tabla WHERE $campo1 = '$valor1' AND $campo2 = '$valor2'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);	
	return ++$rows;
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
				if ($field[0] == $codigo) { ?><option value="<?=$field[0]?>" selected="selected"><?=($field[1])?></option><? }
				else { ?><option value="<?=$field[0]?>"><?=($field[1])?></option><? }
			}
			break;
			
		case 1:
			$sql = "SELECT $campo1, $campo2 FROM $tabla WHERE $campo1 = '$codigo'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
			while ($field = mysql_fetch_array($query)) {
				?><option value="<?=$field[0]?>" selected="selected"><?=($field[1])?></option><?
			}
			break;
			
		case 10:
			$sql = "SELECT $campo1, $campo2 FROM $tabla ORDER BY $campo1";
			$query = mysql_query($sql) or die ($sql.mysql_error());
			while ($field = mysql_fetch_array($query)) {
				if ($field[0] == $codigo) { ?><option value="<?=$field[0]?>" selected="selected"><?=$field[0]?>&nbsp;&nbsp;&nbsp;<?=($field[1])?></option><? }
				else { ?><option value="<?=$field[0]?>"><?=$field[0]?>&nbsp;&nbsp;&nbsp;<?=($field[1])?></option><? }
			}
			break;
			
		case 11:
			$sql = "SELECT $campo1, $campo2 FROM $tabla WHERE $campo1 = '$codigo'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
			while ($field = mysql_fetch_array($query)) {
				?><option value="<?=$field[0]?>" selected="selected"><?=$field[0]?>&nbsp;&nbsp;&nbsp;<?=($field[1])?></option><?
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
				if ($field[0] == $codigo1) { ?><option value="<?=$field[0]?>" selected="selected"><?=($field[1])?></option><? }
				else { ?><option value="<?=$field[0]?>"><?=($field[1])?></option><? }
			}
			break;
			
		case 1:
			$sql = "SELECT $campo1, $campo2 FROM $tabla WHERE $campo1 = '$codigo1'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
			while ($field = mysql_fetch_array($query)) {
				?><option value="<?=$field[0]?>" selected="selected"><?=($field[1])?></option><?
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
				if ($field[0]==$detalle) echo "<option value='".$field[0]."' selected>".($field[1])."</option>";
				else echo "<option value='".$field[0]."'>".($field[1])."</option>";
			}
			break;
		case 1:
			$sql="SELECT CodDetalle, Descripcion FROM mastmiscelaneosdet WHERE CodMaestro='$maestro' AND CodDetalle='$detalle'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field[0]."'>".($field[1])."</option>";
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
			
		case "BUSCAR-FACTURACION":
			$c[0] = "d.DocumentoReferencia"; $v[0] = "Doc. Interno";
			$c[1] = "d.ReferenciaNroDocumento"; $v[1] = "Nro. OC/OS";
			$c[2] = "d.TransaccionNroDocumento"; $v[2] = "Transaccion";
			$c[3] = "d.Comentarios"; $v[3] = "Comentarios";
			break;
			
		case "TIPO-TRANSACCION":
			$c[0] = "I"; $v[0] = "Ingreso";
			$c[1] = "E"; $v[1] = "Egreso";
			$c[2] = "T"; $v[2] = "Transaccion";
			break;
			
		case "ESTADO-OBLIGACIONES":
			$c[0] = "PR"; $v[0] = "En Preparacion";
			$c[1] = "RV"; $v[1] = "Revisada";
			$c[2] = "AP"; $v[2] = "Aprobada";
			$c[3] = "AN"; $v[3] = "Anulada";
			$c[4] = "PA"; $v[4] = "Pagada";
			break;
		
		case "ESTADO-ORDENES":
			$c[0] = "PR"; $v[0] = "En Preparacion";
			$c[1] = "RV"; $v[1] = "Revisado";
			$c[2] = "AP"; $v[2] = "Aprobado";
			$c[3] = "AN"; $v[3] = "Anulado";
			$c[4] = "RE"; $v[4] = "Rechazado";
			$c[5] = "CE"; $v[5] = "Cerrado";
			$c[6] = "CO"; $v[6] = "Completado";
			break;
		
		case "ESTADO-ORDEN-PAGO":
			$c[0] = "PE"; $v[0] = "Pendiente";
			$c[1] = "GE"; $v[1] = "Generada";
			$c[2] = "PA"; $v[2] = "Pagada";
			$c[3] = "AN"; $v[3] = "Anulada";
			break;
			
		case "ORDENAR-ORDEN-PAGO":
			$c[0] = "NomProveedorPagar, CodTipoDocumento, NroDocumento"; $v[0] = "Proveedor/Documento";
			$c[1] = "MontoTotal"; $v[1] = "Monto a Pagar";
			$c[2] = "FechaProgramada"; $v[2] = "Fecha Prog. Pago";
			break;
		
		case "ESTADO-TRANSACCION-BANCARIA":
			$c[0] = "PR"; $v[0] = "Pendiente";
			$c[1] = "AP"; $v[1] = "Actualizado";
			$c[2] = "CO"; $v[2] = "Contabilizado";
			break;
		
		case "APLICACION-GASTO":
			$c[0] = "CC"; $v[0] = "Caja Chica";
			$c[1] = "RG"; $v[1] = "Reporte Gastos";
			$c[2] = "AP"; $v[2] = "Adelanto a Proveedores";
			break;
		
		case "ESTADO-CAJA-CHICA":
			$c[0] = "PR"; $v[0] = "En Preparacion";
			$c[1] = "AP"; $v[1] = "Aprobado";
			//$c[2] = "PA"; $v[2] = "Pagado";
			$c[2] = "AN"; $v[2] = "Anulado";
			break;
		
		case "ESTADO-CHEQUE":
			$c[0] = "C"; $v[0] = "Custodia";
			$c[1] = "E"; $v[1] = "Entregado";
			break;
		
		case "ESTADO-PAGO":
			$c[0] = "GE"; $v[0] = "Generado";
			$c[1] = "IM"; $v[1] = "Impreso";
			break;
		
		case "FLAG-COBRADO":
			$c[0] = "S"; $v[0] = "Cobrado";
			$c[1] = "N"; $v[1] = "Pendiente por Cobrar";
			break;
		
		case "SISTEMA-AP":
			$c[0] = "CP"; $v[0] = "Cuentas x Pagar";
			$c[1] = "CC"; $v[1] = "Caja Chica";
			break;
	}
	
	$i = 0;
	switch ($opt) {
		case 0:
			foreach ($c as $cod) {
				if ($cod == $codigo) echo "<option value='".$cod."' selected>".($v[$i])."</option>";
				else echo "<option value='".$cod."'>".($v[$i])."</option>";
				$i++;
			}
			break;
			
		case 1:
			foreach ($c as $cod) {
				if ($cod == $codigo) echo "<option value='".$cod."' selected>".($v[$i])."</option>";
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
			
		case "ESTADO-DOCUMENTOS":
			$c[0] = "PR"; $v[0] = "Pendiente";
			$c[1] = "RV"; $v[1] = "Completado";
			break;
			
		case "ESTADO-OBLIGACIONES":
			$c[0] = "PR"; $v[0] = "En Preparacion";
			$c[1] = "RV"; $v[1] = "Revisada";
			$c[2] = "AP"; $v[2] = "Aprobada";
			$c[3] = "AN"; $v[3] = "Anulada";
			$c[4] = "PA"; $v[4] = "Pagada";
			break;
		
		case "ESTADO-ORDENES":
			$c[0] = "PR"; $v[0] = "En Preparacion";
			$c[1] = "RV"; $v[1] = "Revisado";
			$c[2] = "AP"; $v[2] = "Aprobado";
			$c[3] = "AN"; $v[3] = "Anulado";
			$c[4] = "RE"; $v[4] = "Rechazado";
			$c[5] = "CE"; $v[5] = "Cerrado";
			$c[6] = "CO"; $v[6] = "Completado";
			break;
		
		case "ESTADO-ORDEN-PAGO":
			$c[0] = "PE"; $v[0] = "Pendiente";
			$c[1] = "GE"; $v[1] = "Generada";
			$c[2] = "PA"; $v[2] = "Pagada";
			$c[3] = "AN"; $v[3] = "Anulada";
			break;
		
		case "ESTADO-TRANSACCION-BANCARIA":
			$c[0] = "PR"; $v[0] = "Pendiente";
			$c[1] = "AP"; $v[1] = "Actualizado";
			$c[2] = "CO"; $v[2] = "Contabilizado";
			break;
			
		case "TIPO-TRANSACCION":
			$c[0] = "I"; $v[0] = "Ingreso";
			$c[1] = "E"; $v[1] = "Egreso";
			$c[2] = "T"; $v[2] = "Transaccion";
			break;
		
		case "APLICACION-GASTO":
			$c[0] = "CC"; $v[0] = "Caja Chica";
			$c[1] = "RG"; $v[1] = "Reporte Gastos";
			$c[2] = "AP"; $v[2] = "Adelanto a Proveedores";
			break;
		
		case "ESTADO-CAJA-CHICA":
			$c[0] = "PR"; $v[0] = "En Preparacion";
			$c[1] = "AP"; $v[1] = "Aprobado";
			$c[2] = "PA"; $v[2] = "Pagado";
			$c[3] = "AN"; $v[3] = "Anulado";
			break;
		
		case "ESTADO-CHEQUE":
			$c[0] = "C"; $v[0] = "Custodia";
			$c[1] = "E"; $v[1] = "Entregado";
			break;
		
		case "ESTADO-PAGO":
			$c[0] = "GE"; $v[0] = "Generado";
			$c[1] = "IM"; $v[1] = "Impreso";
			break;
		
		case "FLAG":
			$c[0] = "S"; $v[0] = "Si";
			$c[1] = "N"; $v[1] = "No";
			break;
		
		case "FLAG-CONTABILIZADO":
			$c[0] = "N"; $v[0] = "Si";
			$c[1] = "S"; $v[1] = "No";
			break;
		
		case "ESTADO-CHEQUE-COBRO":
			$c[0] = "S"; $v[0] = "Cobrado";
			$c[1] = "N"; $v[1] = "Pendiente";
			break;
		
		case "ORIGEN-PAGO":
			$c[0] = "A"; $v[0] = "Automatico";
			break;
		
		case "FLAG-COBRADO":
			$c[0] = "S"; $v[0] = "Cobrado";
			$c[1] = "N"; $v[1] = "Pendiente por Cobrar";
			break;
		
		case "SISTEMA-AP":
			$c[0] = "CP"; $v[0] = "Cuentas x Pagar";
			$c[1] = "CF"; $v[1] = "Caja Chica";
			break;
	}
	
	$i=0;
	foreach ($c as $cod) {
		if ($cod == $codigo) return ($v[$i]);
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
				if ($field[0]==$organismo) echo "<option value='".$field[0]."' selected>".($field[1])."</option>"; 
				else echo "<option value='".$field[0]."'>".($field[1])."</option>";
			}
			break;
		case 1:
			$sql="SELECT CodOrganismo, Organismo FROM mastorganismos WHERE CodOrganismo='$organismo'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field[0]."'>".($field[1])."</option>";
			}
			break;
		case 3:
			$sql="SELECT s.CodOrganismo, o.Organismo FROM seguridad_alterna s INNER JOIN mastorganismos o ON (s.CodOrganismo=o.CodOrganismo) WHERE s.Usuario='".$_SESSION["USUARIO_ACTUAL"]."' AND s.CodAplicacion='".$_SESSION["APLICACION_ACTUAL"]."' AND s.FlagMostrar='S' GROUP BY s.CodOrganismo ORDER BY s.CodOrganismo";
			$query=mysql_query($sql) or die ($sql.mysql_error());
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
	connect();
	if ($opt==3 && $_SESSION["USUARIO_ACTUAL"]==$_SESSION["SUPER_USUARIO"]) $opt=0;
	switch ($opt) {
		case 0:
			$sql="SELECT CodDependencia, Dependencia FROM mastdependencias WHERE CodOrganismo='".$organismo."' AND CodDependencia<>'' ORDER BY CodDependencia";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$dependencia) echo "<option value='".$field[0]."' selected>".($field[1])."</option>"; 
				else echo "<option value='".$field[0]."'>".($field[1])."</option>";
			}
			break;
		case 1:
			$sql="SELECT CodDependencia, Dependencia FROM mastdependencias WHERE CodDependencia='".$dependencia."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field[0]."'>".($field[1])."</option>";
			}
			break;
		case 3:
			$sql="SELECT s.CodDependencia, o.Dependencia FROM seguridad_alterna s INNER JOIN mastdependencias o ON (s.CodDependencia=o.CodDependencia) WHERE s.Usuario='".$_SESSION["USUARIO_ACTUAL"]."' AND s.CodAplicacion='".$_SESSION["APLICACION_ACTUAL"]."' AND s.FlagMostrar='S' AND s.CodOrganismo='$organismo' GROUP BY s.CodDependencia ORDER BY s.CodDependencia";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$dependencia) echo "<option value='".$field[0]."' selected>".($field[1])."</option>"; 
				else echo "<option value='".$field[0]."'>".($field[1])."</option>";
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
	list($a, $m, $d)=SPLIT( '[/.-]', $fecha);
	if ($d == "" || $d == "0000") return ""; else return "$d-$m-$a";
}

//
function formatFechaAMD($fecha) {
	list($d, $m, $a)=SPLIT( '[/.-]', $fecha);
	if ($a == "" || $a == "0000") return ""; else return "$a-$m-$d";
}

function getPartidaCuentaItem($item) {
	$sql = "SELECT CtaGasto, PartidaPresupuestal
			FROM lg_itemmast
			WHERE CodItem = '".$item."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	return array($field[0], $field[1]);
}

function getPartidaCuentaCommodity($item) {
	$sql = "SELECT CodCuenta, cod_partida 
			FROM lg_commoditysub
			WHERE Codigo = '".$item."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	return array($field[0], $field[1]);
}

function loadSelectImpuesto($impuesto, $rfiscal) {
	if ($rfiscal != "") $filtro = "AND CodRegimenFiscal = '$rfiscal'";
	$sql = "SELECT CodImpuesto, Descripcion
					FROM mastimpuestos
					WHERE 1 $filtro
					ORDER BY CodImpuesto";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	for ($i=0; $i<$rows; $i++) {
		$field=mysql_fetch_array($query);
		if ($field[0] == $impuesto) echo "<option value='".$field[0]."' selected>".($field[1])."</option>"; 
		else echo "<option value='".$field[0]."'>".($field[1])."</option>";
	}
}

//	funcion para verificar disponibiliadad presupuestaria de las cotizaciones
function verificarDisponibilidadPresupuestariaObligacion($anio, $organismo, $tdoc, $nrofactura, $detalles_dis, $monto_impuesto) {
	$_PARAMETRO = getParametro("IVADEFAULT");
	$disponible = true;
	
	//	obtengo la distribucion insertada por el usuario para la obligacion
	$linea_dis = split(";", $detalles_dis);
	$linea=0;	$secuencia = 0;
	foreach ($linea_dis as $registro) {	$linea++;	$secuencia++;
		list($partida, $cuenta, $ccosto, $_flagnoafecto, $monto, $orden, $_documentoreferencia, $descripcion, $_codpersona, $_nroactivo, $_flagdiferido) = SPLIT( '[|]', $registro);
		list($tipoorden, $nroorden) = split('[-]', $orden);
		$partida_obligacion[$partida] += $monto;
		$codpartida_obligacion[$partida] = $partida;
	}
	$partida = $_PARAMETRO['IVADEFAULT'];	die($partida."...");
	$partida_obligacion[$partida] += $monto_impuesto;
	$codpartida_obligacion[$partida] = $partida;
	
	//	obtengo la ditribucion anterior insertada por el usuario para la obligacion si se esta modificando la obligacion
	foreach ($codpartida_obligacion as $partida) {
		$sql = "SELECT Monto
				FROM ap_distribucionobligacion
				WHERE
					CodTipoDocumento = '".$tdoc."' AND
					NroDocumento = '".$nrofactura."' AND
					cod_partida = '".$partida."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		while($field = mysql_fetch_array($query)) {
			$partida_anterior[$partida] += $field['Monto'];
		}
	}
	
	//	obtengo la disponibilidad actual de cada una de las partidas de la obligacion
	foreach ($codpartida_obligacion as $partida) {
		if ($partida != "") {
			$sql = "SELECT (pvpd.MontoAjustado - pvpd.MontoCompromiso + ".floatval($partida_anterior[$partida]).") AS MontoDisponible
					FROM
						pv_presupuesto pvp
						INNER JOIN pv_presupuestodet pvpd ON (pvp.Organismo = pvpd.Organismo AND pvp.CodPresupuesto = pvpd.CodPresupuesto)
					WHERE 
						pvp.EjercicioPpto = '".$anio."' AND
						pvpd.Organismo = '".$organismo."' AND
						pvpd.cod_partida = '".$partida."'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query) == 0) { $disponible = false; $partida_return = $partida; break; }
			else {
				$field = mysql_fetch_array($query);
				if ($partida_obligacion[$partida] > $field['MontoDisponible']) { $disponible = false; $partida_return = $partida; break; }
			}
		}
	}
	return array($partida_return, $disponible);
}

function getNroOrdenPago($codtipopago, $nrocuenta) {
	$sql = "SELECT UltimoNumero
			FROM ap_ctabancariatipopago
			WHERE
				CodTipoPago = '".$codtipopago."' AND
				NroCuenta = '".$nrocuenta."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	$codigo = (int) ($field['UltimoNumero'] + 1);
	$codigo = (string) str_repeat("0", 10-strlen($codigo)).$codigo;
	return $codigo;
}
?>
