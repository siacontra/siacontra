<?php
session_start();
set_time_limit(-1);
ini_set('memory_limit','128M');  
include ("../../funciones.php");
connect();
$_PARAMETRO = parametros();
//	FUNCION PARA CONECTARSE CON EL SERVIDO MYSQL
function connect() {
	mysql_connect($_SESSION["MYSQL_HOST"], $_SESSION["MYSQL_USER"], $_SESSION["MYSQL_CLAVE"]) or die ("NO SE PUDO CONECTAR CON EL SERVIDOR MYSQL!");
mysql_select_db($_SESSION["MYSQL_BD"]) or die ("NO SE PUDO CONECTAR CON LA BASE DE DATOS!");
	mysql_query("SET NAMES 'utf8'");
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
// FUNCION PARA OBTENER LOS PARAMETROS DEL SISTEMA
function parametros() {	
	$sql = "SELECT * 
			FROM mastparametros 
			WHERE 
				(CodAplicacion = 'GE' OR CodAplicacion = '".$_SESSION["APLICACION_ACTUAL"]."') AND
				Estado = 'A'"; //echo $sql;
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	while ($field = mysql_fetch_array($query)) {
		$id = $field['ParametroClave'];
		$_PARAMETRO[$id] = $field['ValorParam'];
		//if($field['ParametroClave']=='VOUINGP20') //echo  $_PARAMETRO[$id];
	}
	return $_PARAMETRO;
}
function cambioFormato($num){
	$num = str_replace(".","",$num);
	$num = str_replace(",",".",$num);
	return ($num);
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

//	FUNCION PARA CARGAR SELECTS 
function loadSelectValores($tabla, $codigo, $opt) {
	switch ($tabla) {
		case "ESTADO":
			$c[0] = "A"; $v[0] = "Activo";
			$c[1] = "I"; $v[1] = "Inactivo";
			break;
		
		case "PRIORIDAD":
			$c[0] = "N"; $v[0] = "Normal";
			$c[1] = "U"; $v[1] = "Urgente";
			$c[2] = "M"; $v[2] = "Muy Urgente";
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

//	FUNCION PARA CARGAR LAS APLICACIONES EN UN SELECT
function getAplicaciones($aplicacion, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT CodAplicacion, Descripcion FROM mastaplicaciones ORDER BY CodAplicacion";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$aplicacion) echo "<option value='".$field[0]."' selected>".htmlentities($field[1])."</option>"; 
				else echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
		case 1:
			$sql="SELECT CodAplicacion, Descripcion FROM mastaplicaciones WHERE CodAplicacion='$aplicacion'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS PAISES EN UN SELECT
function getPaises($pais, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT CodPais, Pais FROM mastpaises ORDER BY CodPais";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$pais) echo "<option value='".$field[0]."' selected>".htmlentities($field[1]); 
				else echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
		case 1:
			$sql="SELECT CodPais, Pais FROM mastpaises WHERE CodPais='$pais'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS ESTADOS EN UN SELECT
function getEstados($estado, $pais, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT CodEstado, Estado FROM mastestados WHERE CodPais='".$pais."' ORDER BY CodEstado";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$estado) echo "<option value='".$field[0]."' selected>".htmlentities($field[1]); 
				else echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
		case 1:
			$sql="SELECT CodEstado, Estado FROM mastestados WHERE CodEstado='".$estado."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS MUNICIPIOS EN UN SELECT
function getMunicipios($municipio, $estado, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT CodMunicipio, Municipio FROM mastmunicipios WHERE CodEstado='".$estado."' ORDER BY CodMunicipio";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$municipio) echo "<option value='".$field[0]."' selected>".htmlentities($field[1])."</option>"; 
				else echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
		case 1:
			$sql="SELECT CodMunicipio, Municipio FROM mastmunicipios WHERE CodMunicipio='".$municipio."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LAS CIUDADES EN UN SELECT
function getCiudades($ciudad, $municipio, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT CodCiudad, Ciudad FROM mastciudades WHERE CodMunicipio='".$municipio."' ORDER BY CodCiudad";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$ciudad) echo "<option value='".$field[0]."' selected>".htmlentities($field[1])."</option>"; 
				else echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
		case 1:
			$sql="SELECT CodCiudad, Ciudad FROM mastciudades WHERE CodCiudad='".$ciudad."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS TIPOS DE PAGO EN UN SELECT
function getTPago($tpago, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT CodTipoPago, TipoPago FROM masttipopago ORDER BY CodTipoPago";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$tpago) echo "<option value='".$field[0]."' selected>".htmlentities($field[1])."</option>"; 
				else echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
		case 1:
			$sql="SELECT CodTipoPago, TipoPago FROM masttipopago WHERE CodTipoPago='$tpago'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
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
		case 2:
			$sql="SELECT CodDependencia, Dependencia FROM mastdependencias";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for($i; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$dependencia) echo "<option value='".$field[0]."' selected>".$field[1]."</option>"; 
				else echo "<option value='".$field[0]."'>".$field[1]."</option>";
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

//	FUNCION PARA CARGAR LAS DIVISIONES EN UN SELECT
function getDivisiones($division, $dependencia, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT CodDivision, Division FROM mastdivisiones WHERE CodDependencia='".$dependencia."' AND CodDivision<>'' ORDER BY CodDivision";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$division) echo "<option value='".$field[0]."' selected>".htmlentities($field[1])."</option>"; 
				else echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
		case 1:
			$sql="SELECT CodDivision, Division FROM mastdivisiones WHERE CodDivision='".$division."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS GRUPOS EN UN SELECT
function getGrupos($grupo, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT CodGrupOcup, GrupoOcup FROM rh_grupoocupacional ORDER BY CodGrupOcup";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$grupo) echo "<option value='".$field[0]."' selected>".htmlentities($field[1])."</option>"; 
				else echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
		case 1:
			$sql="SELECT CodGrupOcup, GrupoOcup FROM rh_grupoocupacional WHERE CodGrupOcup='$grupo'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS TIPOS DE CARGO EN UN SELECT
function getSeries($serie, $grupo, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT CodSerieOcup, SerieOcup FROM rh_serieocupacional WHERE CodGrupOcup='$grupo' ORDER BY CodSerieOcup";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$serie) echo "<option value='".$field[0]."' selected>".htmlentities($field[1])."</option>";
				else echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
		case 1:
			$sql="SELECT CodSerieOcup, SerieOcup FROM rh_serieocupacional WHERE CodSerieOcup='$serie'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS TIPOS DE CARGO EN UN SELECT
function getTCargo($tcargo, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT CodTipoCargo, TipCargo FROM rh_tipocargo ORDER BY CodTipoCargo";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$tcargo) echo "<option value='".$field[0]."' selected>".htmlentities($field[1])."</option>";
				else echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
		case 1:
			$sql="SELECT CodTipoCargo, TipCargo FROM rh_tipocargo WHERE CodTipoCargo='$tcargo'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS NIVELES EN UN SELECT
function getNiveles($nivelcargo, $tipocargo, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT CodNivelClase, NivelClase FROM rh_nivelclasecargo WHERE CodTipoCargo='".$tipocargo."' ORDER BY CodNivelClase";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$nivelcargo) echo "<option value='".$field[0]."' selected>".htmlentities($field[1])."</option>"; 
				else echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
		case 1:
			$sql="SELECT CodNivelClase, NivelClase FROM rh_nivelclasecargo WHERE CodNivelClase='$nivelcargo'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS CARGOS EN UN SELECT
function getCargos($cargo, $serie, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT CodCargo, DescripCargo FROM rh_puestos WHERE CodSerieOcup='$serie' ORDER BY CodCargo";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$cargo) echo "<option value='".$field[0]."' selected>".htmlentities($field[1])."</option>"; 
				else echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
		case 1:
			$sql="SELECT CodCargo, DescripCargo FROM rh_puestos WHERE CodCargo='$cargo'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
		case 2:
			$sql="SELECT CodCargo, DescripCargo FROM rh_puestos ORDER BY DescripCargo";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$cargo) echo "<option value='".$field[0]."' selected>".htmlentities($field[1])."</option>"; 
				else echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS CARGOS EN UN SELECT
function getCargosReporta($cargo, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT CodCargo, DescripCargo FROM rh_puestos ORDER BY DescripCargo";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$cargo) echo "<option value='".$field[0]."' selected>".htmlentities($field[1])."</option>"; 
				else echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
			
		case 1:
			$sql="SELECT CodCargo, DescripCargo FROM rh_puestos WHERE CodCargo='$cargo'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS TIPOS DE TRABAJADOR EN UN SELECT
function getTTrabajador($ttrabajador, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT CodTipoTrabajador, TipoTrabajador FROM rh_tipotrabajador ORDER BY CodTipoTrabajador";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$ttrabajador) echo "<option value='".$field[0]."' selected>".htmlentities($field[1])."</option>";
				else echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
		case 1:
			$sql="SELECT CodTipoTrabajador, TipoTrabajador FROM rh_tipotrabajador WHERE CodTipoTrabajador='$ttrabajador'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS TIPOS DE NOMINA EN UN SELECT
function getTNomina($tnomina, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT CodTipoNom, Nomina FROM tiponomina ORDER BY CodTipoNom";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$tnomina) echo "<option value='".$field[0]."' selected>".htmlentities($field[1])."</option>"; 
				else echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
		case 1:
			$sql="SELECT CodTipoNom, Nomina FROM tiponomina WHERE CodTipoNom='$tnomina'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS PERFILES DE NOMINA EN UN SELECT
function getPNomina($pnomina, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT CodPerfil, Perfil FROM tipoperfilnom ORDER BY CodPerfil";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$pnomina) echo "<option value='".$field[0]."' selected>".htmlentities($field[1])."</option>"; 
				else echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
		case 1:
			$sql="SELECT CodPerfil, Perfil FROM tipoperfilnom WHERE CodPerfil='$pnomina'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS TIPOS DE CESE EN UN SELECT
function getTCese($tcese, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT CodMotivoCes, MotivoCese FROM rh_motivocese WHERE CodMotivoCes<>'' ORDER BY CodMotivoCes";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$tcese) echo "<option value='".$field[0]."' selected>".htmlentities($field[1])."</option>"; 
				else echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
		case 1:
			$sql="SELECT CodMotivoCes, MotivoCese FROM rh_motivocese WHERE CodMotivoCes<>'' AND CodMotivoCes='$tcese'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS TIPOS DE SEGURO EN UN SELECT
function getTSeguros($tseguro, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT CodTipSeguro, Descripcion FROM rh_tiposeguro ORDER BY CodTipSeguro";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$tseguro) echo "<option value='".$field[0]."' selected>".htmlentities($field[1])."</option>"; 
				else echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
		case 1:
			$sql="SELECT CodTipSeguro, Descripcion FROM rh_tiposeguro WHERE CodTipSeguro='$tseguro'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS TIPOS DE CONTRATOS EN UN SELECT
function getTContratos($tcontrato, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT TipoContrato, Descripcion FROM rh_tipocontrato WHERE TipoContrato<>'' ORDER BY TipoContrato";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$tcontrato) echo "<option value='".$field[0]."' selected>".htmlentities($field[1])."</option>"; 
				else echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
		case 1:
			$sql="SELECT TipoContrato, Descripcion FROM rh_tipocontrato WHERE TipoContrato='$tcontrato'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS TIPOS DE FORMATOS DE CONTRATOS EN UN SELECT
function getFContratos($fcontrato, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT CodFormato, Documento FROM rh_formatocontrato ORDER BY CodFormato";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$fcontrato) echo "<option value='".$field[0]."' selected>".htmlentities($field[1])."</option>"; 
				else echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
		case 1:
			$sql="SELECT CodFormato, Documento FROM rh_formatocontrato WHERE CodFormato='$fcontrato' ORDER BY CodFormato";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
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

//	FUNCION PARA CARGAR LOS BANCOS EN UN SELECT
function getBancos($banco, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT CodBanco, Banco FROM mastbancos ORDER BY CodBanco";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$banco) echo "<option value='".$field[0]."' selected>".htmlentities($field[1])."</option>"; 
				else echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
		case 1:
			$sql="SELECT CodBanco, Banco FROM mastbancos WHERE CodBanco='$banco'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS TIPOS DE GRADO DE INSTRUCCION EN UN SELECT
function getGInstruccion($grado, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT CodGradoInstruccion, Descripcion FROM rh_gradoinstruccion ORDER BY CodGradoInstruccion";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$grado) echo "<option value='".$field[0]."' selected>".htmlentities($field[1])."</option>"; 
				else echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
		case 1:
			$sql="SELECT CodGradoInstruccion, Descripcion FROM rh_gradoinstruccion WHERE CodGradoInstruccion='$grado' ORDER BY CodGradoInstruccion";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS NIVELES DE INTRUCCION EN UN SELECT
function getNInstruccion($nivel, $grado, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT Nivel, Descripcion FROM rh_nivelgradoinstruccion WHERE CodGradoInstruccion='".$grado."' ORDER BY Nivel";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$nivel) echo "<option value='".$field[0]."' selected>".htmlentities($field[1]); 
				else echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
		case 1:
			$sql="SELECT Nivel, Descripcion FROM rh_nivelgradoinstruccion WHERE Nivel='".$nivel."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS CENTROS DE ESTUDIOS EN UN SELECT EN UN SELECT
function getCEstudios($centro, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT CodCentroEstudio, Descripcion FROM rh_centrosestudios WHERE (CodCentroEstudio<>'') ORDER BY CodCentroEstudio";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$centro) echo "<option value='".$field[0]."' selected>".htmlentities($field[1])."</option>"; 
				else echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
		case 1:
			$sql="SELECT CodCentroEstudio, Descripcion FROM rh_centrosestudios WHERE (CodCentroEstudio<>'') AND CodCentroEstudio='$centro' ORDER BY CodCentroEstudio";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS IDIOMAS EN UN SELECT
function getIdiomas($idioma, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT CodIdioma, DescripcionLocal FROM mastidioma ORDER BY CodIdioma";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$idioma) echo "<option value='".$field[0]."' selected>".htmlentities($field[1])."</option>";
				else echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
		case 1:
			$sql="SELECT CodIdioma, DescripcionLocal FROM mastidioma WHERE CodIdioma='$idioma'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS TIPOS DE STATUS EN UN SELECT
function getStatus($status, $opt) {
	connect();
	$tstatus[0]="Activo"; $vstatus[0]="A";
	$tstatus[1]="Inactivo"; $vstatus[1]="I";
	switch ($opt) {
		case 0:
			for ($i=0; $i<2; $i++) {
				if ($status==$vstatus[$i]) echo "<option value='".$vstatus[$i]."' selected>".$tstatus[$i]."</option>";
				else echo "<option value='".$vstatus[$i]."'>".$tstatus[$i]."</option>";
			}
			break;
		case 1:
			for ($i=0; $i<2; $i++) {
				if ($status==$vstatus[$i]) echo "<option value='".$vstatus[$i]."' selected>".$tstatus[$i]."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS TIPOS DE STATUS EN UN SELECT
function getStatusJudiciales($status, $opt) {
	connect();
	$tstatus[0]="Vigente"; $vstatus[0]="V";
	$tstatus[1]="Vencido"; $vstatus[1]="E";
	switch ($opt) {
		case 0:
			for ($i=0; $i<2; $i++) {
				if ($status==$vstatus[$i]) echo "<option value='".$vstatus[$i]."' selected>".$tstatus[$i]."</option>";
				else echo "<option value='".$vstatus[$i]."'>".$tstatus[$i]."</option>";
			}
			break;
		case 1:
			for ($i=0; $i<2; $i++) {
				if ($status==$vstatus[$i]) echo "<option value='".$vstatus[$i]."' selected>".$tstatus[$i]."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS TIPOS DE STATUS EN UN SELECT
function getStatusPostulante($status, $opt) {
	connect();
	$tstatus[0]="Postulante"; $vstatus[0]="P";
	$tstatus[1]="Aceptado"; $vstatus[1]="A";
	$tstatus[2]="Contratado"; $vstatus[2]="C";
	switch ($opt) {
		case 0:
			for ($i=0; $i<3; $i++) {
				if ($status==$vstatus[$i]) echo "<option value='".$vstatus[$i]."' selected>".$tstatus[$i]."</option>";
				else echo "<option value='".$vstatus[$i]."'>".$tstatus[$i]."</option>";
			}
			break;
		case 1:
			for ($i=0; $i<2; $i++) {
				if ($status==$vstatus[$i]) echo "<option value='".$vstatus[$i]."' selected>".$tstatus[$i]."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LAS OPCIONES DE BUSQUEDA EN UN SELECT
function getBuscar($buscar) {
	connect();
	if ($buscar=="Apellido1") echo "<option value='Apellido1' selected>Apellido Paterno"."</option>"; else echo "<option value='Apellido1'>Apellido Paterno"."</option>";
	if ($buscar=="Apellido2") echo "<option value='Apellido2' selected>Apellido Materno"."</option>"; else echo "<option value='Apellido2'>Apellido Materno"."</option>";
	if ($buscar=="Nombres") echo "<option value='Nombres' selected>Nombre"."</option>"; else echo "<option value='Nombres'>Nombre"."</option>";
	if ($buscar=="Busqueda") echo "<option value='Busqueda' selected>Nombre B&uacute;squeda"."</option>"; else echo "<option value='Busqueda'>Nombre B&uacute;squeda"."</option>";
	if ($buscar=="NomCompleto") echo "<option value='NomCompleto' selected>Nombre Completo"."</option>"; else echo "<option value='NomCompleto'>Nombre Completo"."</option>";
}

//	FUNCION PARA CARGAR LAS OPCIONES DE BUSQUEDA EN UN SELECT
function getRelacionales($relacion) {
	connect();
	if ($relacion=="1") echo "<option value='1' selected>="."</option>"; else echo "<option value='1'>="."</option>";
	if ($relacion=="2") echo "<option value='2' selected>&lt;"."</option>"; else echo "<option value='2'>&lt;"."</option>";
	if ($relacion=="3") echo "<option value='3' selected>&gt;"."</option>"; else echo "<option value='3'>&gt;"."</option>";
	if ($relacion=="4") echo "<option value='4' selected>&lt;="."</option>"; else echo "<option value='4'>&lt;="."</option>";
	if ($relacion=="5") echo "<option value='5' selected>&gt;="."</option>"; else echo "<option value='5'>&gt;="."</option>";
	if ($relacion=="6") echo "<option value='6' selected>&lt;&gt;"."</option>"; else echo "<option value='6'>&lt;&gt;"."</option>";
}

//	FUNCION PARA CARGAR EL SEXO EN UN SELECT
function getSexo($sexo, $opt) {
	connect();
	$tsexo[0]="Masculino"; $vsexo[0]="M";
	$tsexo[1]="Femenino"; $vsexo[1]="F";
	switch ($opt) {
		case 0:
			for ($i=0; $i<2; $i++) {
				if ($sexo==$vsexo[$i]) echo "<option value='".$vsexo[$i]."' selected>".$tsexo[$i]."</option>";
				else echo "<option value='".$vsexo[$i]."'>".$tsexo[$i]."</option>";
			}
			break;
		case 1:
			for ($i=0; $i<2; $i++) {
				if ($sexo==$vsexo[$i]) echo "<option value='".$vsexo[$i]."' selected>".$tsexo[$i]."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR EL SEXO EN UN SELECT
function getFiltroSexo($sexo, $opt) {
	connect();
	$tsexo[0]="[Todos]"; $vsexo[0]="T";
	$tsexo[1]="Masculino"; $vsexo[1]="M";
	$tsexo[2]="Femenino"; $vsexo[2]="F";
	switch ($opt) {
		case 0:
			for ($i=0; $i<3; $i++) {
				if ($sexo==$vsexo[$i]) echo "<option value='".$vsexo[$i]."' selected>".$tsexo[$i]."</option>";
				else echo "<option value='".$vsexo[$i]."'>".$tsexo[$i]."</option>";
			}
			break;
		case 1:
			for ($i=0; $i<3; $i++) {
				if ($sexo==$vsexo[$i]) echo "<option value='".$vsexo[$i]."' selected>".$tsexo[$i]."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR EL SEXO EN UN SELECT
function getTRelacion($rel, $opt) {
	connect();
	$trel[0]="Externa"; $vrel[0]="E";
	$trel[1]="Interna"; $vrel[1]="I";
	switch ($opt) {
		case 0:
			for ($i=0; $i<2; $i++) {
				if ($rel==$vrel[$i]) echo "<option value='".$vrel[$i]."' selected>".$trel[$i]."</option>";
				else echo "<option value='".$vrel[$i]."'>".$trel[$i]."</option>";
			}
			break;
		case 1:
			for ($i=0; $i<2; $i++) {
				if ($rel==$vrel[$i]) echo "<option value='".$vrel[$i]."' selected>".$trel[$i]."</option>";
			}
			break;
	}
}

//	FUNCION PARA OBTENER LA EDAD DE UNA FECHA INGRESADA
function getEdad($fecha) {
	$error=0;
	$listo=0;	
	if ((strlen($fecha))<10) $error=1;
	else {
		$fechaActual= getdate();
		$diaActual = $fechaActual['mday'];
		$mesActual = $fechaActual['mon'];
		$annioActual = $fechaActual['year'];;
		//
		list($d, $m, $a)=SPLIT( '[/.-]', $fecha);
		$dia = (int) ($d);
		$mes = (int) ($m);
		$annio = (int) ($a);		//
		$dias = 0;
		$meses = 0;
		$annios = 0;
		//
		if ($annio>$annioActual || ($annio==$annioActual && $mes>$mesActual) || ($annio==$annioActual && $mes==$mesActual && $dia>$diaActual)) $error=2;
		else {
			$diasMes[1]=31; $diasMes[3]=31; $diasMes[4]=30; $diasMes[5]=31; $diasMes[6]=30; $diasMes[7]=31;
			$diasMes[8]=31; $diasMes[9]=30; $diasMes[10]=31; $diasMes[11]=30; $diasMes[12]=31;
			if ($annioActual%4==0) $diasMes[2]=29; else $diasMes[2]=28;			
			while ($listo==0) {
				if ($annio==$annioActual && $mes==$mesActual) {
					if ($diaActual>=$dia) $dias=$diaActual-$dia;
					else {
						if (($mesActual-1)==0) $dias=(31-$dia)+$diaActual;
						else $dias=($diasMes[$mesActual-1]-$dia)+$diaActual;
						$meses--;
					}
					if ($meses==12) {$annios++; $meses=0;}
					$listo=1;
				} else {							
					if ($mes==12) {
						$mes=0; $annio++;
					}
					if ($meses==12) {
						$meses=0; $annios++;
					}
					$mes++; $meses++;
				}
			}
			return array($annios, $meses, $dias);
		}
	}
	if ($error!=0) return array("", "", "");
}

//	FUNCION PARA OBTENER LA FECHA A PARTIR DE LA EDAD
function setEdadFecha($edad) {
	$a=date("Y");
	$m=date("m");
	$d=date("d");
	$diasMes[1]=31; $diasMes[3]=31; $diasMes[4]=30; $diasMes[5]=31; $diasMes[6]=30; $diasMes[7]=31;
	$diasMes[8]=31; $diasMes[9]=30; $diasMes[10]=31; $diasMes[11]=30; $diasMes[12]=31;
	$aniod=$a-$edad-1; $mesd=(int) $m; $diad=(int) $d+1;
	$anioh=$a-$edad; $mesh=(int) $m; $diah=(int) $d;
	if ($diad>$diasMes[$mesd]) {
		$diad=1;
		if ($mesd==12) { $mesd=1; $anniod=$a-$edad; }
		else $mesd=$mesd+1;
	}
	$fechad=$aniod."-".$mesd."-".$diad;
	$fechah=$anioh."-".$mesh."-".$diah;
	return $fechad.":".$fechah;
}

/*
//	FUNCION PARA OBTENER LOS DIAS ENTRE DOS FECHAS	(FORMATO DD/MM/YYYY)
function getFechaDias($fechad, $fechah) {
	list($dd, $md, $ad)=SPLIT( '[/.-]', $fechad);
	list($dh, $mh, $ah)=SPLIT( '[/.-]', $fechah);
	
	//	Calculo timestam de las dos fechas
	$timestampd = mktime(0, 0, 0, $md, $dd, $ad);
	$timestamph = mktime(0, 0, 0, $mh, $dh, $ah);
	
	//	Resto a una fecha la otra
	$segundos_diferencia = $timestampd - $timestamph;
	
	//convierto segundos en d�as
	$dias_diferencia = $segundos_diferencia / (60 * 60 * 24);
	
	//obtengo el valor absoulto de los d�as (quito el posible signo negativo)
	$dias_diferencia = abs($dias_diferencia);
	
	//quito los decimales a los d�as de diferencia
	$dias_diferencia = floor($dias_diferencia); 
	
	return $dias_diferencia;
}
*/

//	FUNCION PARA CARGAR LOS ITEMS DE ORDENAR POR DE PERSONAS EN UN SELECT
function getOrdenarPersona($ordenar, $opt) {
	connect();
	$nitem=3;
	$tordenar[0]="Persona"; $vordenar[0]="CodPersona";
	$tordenar[1]="Nombre Busqueda"; $vordenar[1]="Busqueda";
	$tordenar[2]="Nro. Documento"; $vordenar[2]="Ndocumento";
	switch ($opt) {
		case 0:
			for ($i=0; $i<$nitem; $i++) {
				if ($ordenar==$vordenar[$i]) echo "<option value='".$vordenar[$i]."' selected>".$tordenar[$i]."</option>";
				else echo "<option value='".$vordenar[$i]."'>".$tordenar[$i]."</option>";
			}
			break;
		case 1:
			for ($i=0; $i<2; $i++) {
				if ($ordenar==$vordenar[$i]) echo "<option value='".$vordenar[$i]."' selected>".$tordenar[$i]."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS ITEMS DE ORDENAR POR DE EMPLEADOS EN UN SELECT
function getOrdenarEmpleado($ordenar, $opt) {
	connect();
	$nitem=5;
	$tordenar[0]="Persona"; $vordenar[0]="me.CodEmpleado";
	$tordenar[1]="Nombre Completo"; $vordenar[1]="mp.NomCompleto";
	$tordenar[2]="Nro. Documento"; $vordenar[2]="mp.Ndocumento";
	$tordenar[3]="Fecha de Ingreso"; $vordenar[3]="me.Fingreso";
	$tordenar[4]="Dependencia"; $vordenar[4]="md.Dependencia";
	switch ($opt) {
		case 0:
			for ($i=0; $i<$nitem; $i++) {
				if ($ordenar==$vordenar[$i]) echo "<option value='".$vordenar[$i]."' selected>".$tordenar[$i]."</option>";
				else echo "<option value='".$vordenar[$i]."'>".$tordenar[$i]."</option>";
			}
			break;
		case 1:
			for ($i=0; $i<2; $i++) {
				if ($ordenar==$vordenar[$i]) echo "<option value='".$vordenar[$i]."' selected>".$tordenar[$i]."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS ITEMS DE MOSTRAR ACTUALIZAR  PERSONAS EN UN SELECT
function getActualizarPersona($actualizar, $opt) {
	connect();
	$nitem=4;
	$tactualizar[0]="Personas"; $vactualizar[0]="Persona";
	$tactualizar[1]="Empleados"; $vactualizar[1]="Empleado";
	$tactualizar[2]="Proveedores"; $vactualizar[1]="Proveedor";
	$tactualizar[3]="Clientes"; $vactualizar[1]="Cliente";
	switch ($opt) {
		case 0:
			for ($i=0; $i<$nitem; $i++) {
				if ($actualizar==$vactualizar[$i]) echo "<option value='".$vactualizar[$i]."' selected>".$tactualizar[$i]."</option>";
				else echo "<option value='".$vactualizar[$i]."'>".$tactualizar[$i]."</option>";
			}
			break;
		case 1:
			for ($i=0; $i<2; $i++) {
				if ($actualizar==$vactualizar[$i]) echo "<option value='".$vactualizar[$i]."' selected>".$tactualizar[$i]."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS ITEMS DE MOSTRAR CLASE DE PERSONAS EN UN SELECT
function getCPersona($cpersona, $opt) {
	connect();
	$nitem=2;
	$tcpersona[0]="Natural"; $vcpersona[0]="N";
	$tcpersona[1]="Juridica"; $vcpersona[1]="J";
	switch ($opt) {
		case 0:
			for ($i=0; $i<$nitem; $i++) {
				if ($cpersona==$vcpersona[$i]) echo "<option value='".$vcpersona[$i]."' selected>".$tcpersona[$i]."</option>";
				else echo "<option value='".$vcpersona[$i]."'>".$tcpersona[$i]."</option>";
			}
			break;
		case 1:
			for ($i=0; $i<2; $i++) {
				if ($cpersona==$vcpersona[$i]) echo "<option value='".$vcpersona[$i]."' selected>".$tcpersona[$i]."</option>";
			}
			break;
	}	
}

//	FUNCION PARA CARGAR LOS ITEMS DE MOSTRAR TIPO DE PERSONAS EN UN SELECT
function getTPersona($tpersona, $opt) {
	connect();
	$nitem=4;
	$ttpersona[0]="Cliente"; $vtpersona[0]="Cliente";
	$ttpersona[1]="Proveedor"; $vtpersona[1]="Proveedor";
	$ttpersona[2]="Empleado"; $vtpersona[2]="Empleado";
	$ttpersona[3]="Otro"; $vtpersona[3]="Otro";
	switch ($opt) {
		case 0:
			for ($i=0; $i<$nitem; $i++) {
				if ($tpersona==$vtpersona[$i]) echo "<option value='".$vtpersona[$i]."' selected>".$ttpersona[$i]."</option>";
				else echo "<option value='".$vtpersona[$i]."'>".$ttpersona[$i]."</option>";
			}
			break;
		case 1:
			for ($i=0; $i<2; $i++) {
				if ($tpersona==$vtpersona[$i]) echo "<option value='".$vtpersona[$i]."' selected>".$ttpersona[$i]."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LAS PROFESIONES EN UN SELECT
function getProfesiones($profesion, $grado, $area, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT CodProfesion, Descripcion FROM rh_profesiones WHERE CodGradoInstruccion='$grado' AND Area='$area' ORDER BY CodProfesion";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field['CodProfesion']==$profesion) echo "<option value='".$field['CodProfesion']."' selected>".htmlentities($field[1])."</option>"; 
				else echo "<option value='".$field['CodProfesion']."'>".htmlentities($field[1])."</option>";
			}
			break;
		case 1:
			
			break;
	}
}

//	FUNCION PARA CARGAR LOS GRADOS DEL CARGO EN UN SELECT
function getGCargo($categoria, $grado, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT Grado, Descripcion FROM rh_nivelsalarial WHERE CategoriaCargo='$categoria' ORDER BY Grado";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field['Grado']==$grado) echo "<option value='".$field['Grado']."' selected>".htmlentities($field['Grado'])."</option>"; 
				else echo "<option value='".$field['Grado']."'>".htmlentities($field['Grado'])."</option>";
			}
			break;
			
		case 1:
			$sql="SELECT Grado, Descripcion FROM rh_nivelsalarial WHERE CategoriaCargo='$categoria' AND Grado = '$grado'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows != 0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field['Grado']."' selected>".htmlentities($field['Grado'])."</option>"; 
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS TIPOS DE STATUS EN UN SELECT
function getStatusDoc($status, $opt) {
	connect();
	$tstatus[0]="Entregado"; $vstatus[0]="E";
	$tstatus[1]="Devuelto"; $vstatus[1]="D";
	$tstatus[2]="Perdido"; $vstatus[2]="P";	
	switch ($opt) {
		case 0:
			for ($i=0; $i<3; $i++) {
				if ($status==$vstatus[$i]) echo "<option value='".$vstatus[$i]."' selected>".$tstatus[$i]."</option>";
				else echo "<option value='".$vstatus[$i]."'>".$tstatus[$i]."</option>";
			}
			break;
		case 1:
			for ($i=0; $i<3; $i++) {
				if ($status==$vstatus[$i]) echo "<option value='".$vstatus[$i]."' selected>".$tstatus[$i]."</option>";
			}
			break;
		case 2:
			for ($i=1; $i<3; $i++) {
				if ($status==$vstatus[$i]) echo "<option value='".$vstatus[$i]."' selected>".$tstatus[$i]."</option>";
				else echo "<option value='".$vstatus[$i]."'>".$tstatus[$i]."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS TIPOS DE STATUS EN UN SELECT
function getStatusPermisos($status, $opt) {
	connect();
	$tstatus[0]="Pendiente"; $vstatus[0]="P";
	$tstatus[1]="Aprobado"; $vstatus[1]="A";
	$tstatus[2]="Rechazado"; $vstatus[2]="R";
	$tstatus[3]="Anulado"; $vstatus[3]="N";
	switch ($opt) {
		case 0:
			for ($i=0; $i<4; $i++) {
				if ($status==$vstatus[$i]) echo "<option value='".$vstatus[$i]."' selected>".$tstatus[$i]."</option>";
				else echo "<option value='".$vstatus[$i]."'>".$tstatus[$i]."</option>";
			}
			break;
		case 1:
			for ($i=0; $i<4; $i++) {
				if ($status==$vstatus[$i]) echo "<option value='".$vstatus[$i]."' selected>".$tstatus[$i]."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS CURSOS EN UN SELECT
function getCursos($curso, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT CodCurso, Descripcion FROM rh_cursos ORDER BY CodCurso";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$curso) echo "<option value='".$field[0]."' selected>".htmlentities($field[1])."</option>";
				else echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
		case 1:
			$sql="SELECT CodCurso, Descripcion FROM rh_cursos WHERE CodCurso='$curso'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
	}
}

function getMeridian($turno, $opt) {
	connect();
	$tturno[0]="AM"; $vturno[0]="AM";
	$tturno[1]="PM"; $vturno[1]="PM";
	switch ($opt) {
		case 0:
			for ($i=0; $i<2; $i++) {
				if ($turno==$vturno[$i]) echo "<option value='".$vturno[$i]."' selected>".$tturno[$i]."</option>";
				else echo "<option value='".$vturno[$i]."'>".$tturno[$i]."</option>";
			}
			break;
		case 1:
			for ($i=0; $i<2; $i++) {
				if ($turno==$vturno[$i]) echo "<option value='".$vturno[$i]."' selected>".$tturno[$i]."</option>";
			}
			break;
	}
}

function getCentrosEstudios($centro, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT CodCentroEstudio, Descripcion FROM rh_centrosestudios WHERE CodCentroEstudio<>'' ORDER BY CodCentroEstudio";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$centro) echo "<option value='".$field[0]."' selected>".htmlentities($field[1])."</option>";
				else echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
		case 1:
			$sql="SELECT CodCentroEstudio, Descripcion FROM rh_centrosestudios WHERE CodCentroEstudio='$centro'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS TIPOS DE MODALIDAD EN UN SELECT
function getModalidad($mod, $opt) {
	connect();
	$tmod[0]="Externo"; $vmod[0]="E";
	$tmod[1]="Interno"; $vmod[1]="I";
	$tmod[2]="Ambos"; $vmod[2]="A";
	switch ($opt) {
		case 0:
			for ($i=0; $i<3; $i++) {
				if ($mod==$vmod[$i]) echo "<option value='".$vmod[$i]."' selected>".$tmod[$i]."</option>";
				else echo "<option value='".$vmod[$i]."'>".$tmod[$i]."</option>";
			}
			break;
		case 1:
			for ($i=0; $i<3; $i++) {
				if ($mod==$vmod[$i]) echo "<option value='".$vmod[$i]."' selected>".$tmod[$i]."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS TIPOS DE STATUS EN UN SELECT
function getStatusRequerimiento($status, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$tstatus[0]="En Preparaci&oacute;n"; $vstatus[0]="P";
			$tstatus[1]="Aprobado"; $vstatus[1]="A";
			$tstatus[2]="En Evaluaci&oacute;n"; $vstatus[2]="E";
			$tstatus[3]="Terminado"; $vstatus[3]="T";
			for ($i=0; $i<3; $i++) {
				if ($status==$vstatus[$i]) echo "<option value='".$vstatus[$i]."' selected>".$tstatus[$i]."</option>";
				else echo "<option value='".$vstatus[$i]."'>".$tstatus[$i]."</option>";
			}
			break;
		case 1:
			$tstatus[0]="En Preparaci&oacute;n"; $vstatus[0]="P";
			$tstatus[1]="Aprobado"; $vstatus[1]="A";
			$tstatus[2]="En Evaluaci&oacute;n"; $vstatus[2]="E";
			$tstatus[3]="Terminado"; $vstatus[3]="T";
			for ($i=0; $i<4; $i++) {
				if ($status==$vstatus[$i]) echo "<option value='".$vstatus[$i]."' selected>".$tstatus[$i]."</option>";
			}
			break;
		case 2:
			$tstatus[0]="Aprobado"; $vstatus[0]="A";
			$tstatus[1]="En Evaluaci&oacute;n"; $vstatus[1]="E";
			$tstatus[2]="Aprobado + En Evaluaci&oacute;n"; $vstatus[2]="A+E";
			for ($i=0; $i<3; $i++) {
				if ($status==$vstatus[$i]) echo "<option value='".$vstatus[$i]."' selected>".$tstatus[$i]."</option>";
				else echo "<option value='".$vstatus[$i]."'>".$tstatus[$i]."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS TIPOS DE STATUS EN UN SELECT
function getStatusCapacitacion($status, $opt) {	
	connect();	
	switch ($opt) {
		case 0:
			$tstatus[0]="En Preparaci&oacute;n"; $vstatus[0]="P";
			$tstatus[1]="Aprobado"; $vstatus[1]="A";
			$tstatus[2]="Iniciado"; $vstatus[2]="I";
			$tstatus[3]="Terminado"; $vstatus[3]="T";
			for ($i=0; $i<4; $i++) {
				if ($status==$vstatus[$i]) echo "<option value='".$vstatus[$i]."' selected>".$tstatus[$i]."</option>";
				else echo "<option value='".$vstatus[$i]."'>".$tstatus[$i]."</option>";
			}
			break;
		case 1:
			$tstatus[0]="En Preparaci&oacute;n"; $vstatus[0]="P";
			$tstatus[1]="Aprobado"; $vstatus[1]="A";
			$tstatus[2]="Iniciado"; $vstatus[2]="I";
			$tstatus[3]="Terminado"; $vstatus[3]="T";
			for ($i=0; $i<4; $i++) {
				if ($status==$vstatus[$i]) echo "<option value='".$vstatus[$i]."' selected>".$tstatus[$i]."</option>";
			}
			break;
		case 2:
			$tstatus[0]="Aprobado"; $vstatus[0]="A";
			$tstatus[1]="Iniciado"; $vstatus[1]="I";
			for ($i=0; $i<2; $i++) {
				if ($status==$vstatus[$i]) echo "<option value='".$vstatus[$i]."' selected>".$tstatus[$i]."</option>";
				else echo "<option value='".$vstatus[$i]."'>".$tstatus[$i]."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS TIPOS DE STATUS EN UN SELECT
function getTipoCapacitacion($status, $opt) {
	connect();
	$tstatus[0]="Interno"; $vstatus[0]="I";
	$tstatus[1]="Externo"; $vstatus[1]="E";
	switch ($opt) {
		case 0:
			for ($i=0; $i<2; $i++) {
				if ($status==$vstatus[$i]) echo "<option value='".$vstatus[$i]."' selected>".$tstatus[$i]."</option>";
				else echo "<option value='".$vstatus[$i]."'>".$tstatus[$i]."</option>";
			}
			break;
		case 1:
			for ($i=0; $i<2; $i++) {
				if ($status==$vstatus[$i]) echo "<option value='".$vstatus[$i]."' selected>".$tstatus[$i]."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS TIPOS DE STATUS EN UN SELECT
function getMeses($mes, $opt) {
	connect();
	$tmes['01']="Enero"; $vmes['01']="01";
	$tmes['02']="Febrero"; $vmes['02']="02";
	$tmes['03']="Marzo"; $vmes['03']="03";
	$tmes['04']="Abril"; $vmes['04']="04";
	$tmes['05']="Mayo"; $vmes['05']="05";
	$tmes['06']="Junio"; $vmes['06']="06";
	$tmes['07']="Julio"; $vmes['07']="07";
	$tmes['08']="Agosto"; $vmes['08']="08";
	$tmes['09']="Septiembre"; $vmes['09']="09";
	$tmes['10']="Octubre"; $vmes['10']="10";
	$tmes['11']="Noviembre"; $vmes['11']="11";
	$tmes['12']="Diciembre"; $vmes['12']="12";
	switch ($opt) {
		case 0:
			for ($i=1; $i<=12; $i++) {
				if ($i<10) $m="0".$i; else $m=$i;
				if ($mes==$vmes[$m]) echo "<option value='".$vmes[$m]."' selected>".$tmes[$m]."</option>";
				else echo "<option value='".$vmes[$m]."'>".$tmes[$m]."</option>";
			}
			break;
		case 1:
			for ($i=1; $i<=12; $i++) {
				if ($i<10) $m="0".$i; else $m=$i;
				if ($mes==$vmes[$m]) echo "<option value='".$vmes[$m]."' selected>".$tmes[$m]."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS TIPOS DE EVALUACIONES EN UN SELECT
function getTEvaluaciones($valor, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT TipoEvaluacion, Descripcion FROM rh_tipoevaluacion ORDER BY TipoEvaluacion";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$valor) echo "<option value='".$field[0]."' selected>".htmlentities($field[1])."</option>"; 
				else echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
		case 1:
			$sql="SELECT TipoEvaluacion, Descripcion FROM rh_tipoevaluacion WHERE TipoEvaluacion='$valor'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS TIPOS DE EVALUACIONES EN UN SELECT
function getTEvaluaciones2($valor, $opt) {
	connect();
	$tvalor[0]="90�"; $vvalor[0]="90";
	$tvalor[1]="180�"; $vvalor[1]="180";
	$tvalor[2]="360�"; $vvalor[2]="360";
	$total=3;
	switch ($opt) {
		case 0:
			for ($i=0; $i<$total; $i++) {
				if ($valor==$vvalor[$i]) echo "<option value='".$vvalor[$i]."' selected>".htmlentities($tvalor[$i])."</option>";
				else echo "<option value='".$vvalor[$i]."'>".htmlentities($tvalor[$i])."</option>";
			}
			break;
		case 1:
			for ($i=0; $i<$total; $i++) {
				if ($valor==$vvalor[$i]) echo "<option value='".$vvalor[$i]."' selected>".htmlentities($tvalor[$i])."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS TIPOS DE EVALUACIONES EN UN SELECT
function getStatusEvaluacion($valor, $opt) {
	connect();
	$tvalor[0]="Abierto"; $vvalor[0]="A";
	$tvalor[1]="Cerrado"; $vvalor[1]="C";
	$total=2;
	switch ($opt) {
		case 0:
			for ($i=0; $i<$total; $i++) {
				if ($valor==$vvalor[$i]) echo "<option value='".$vvalor[$i]."' selected>".htmlentities($tvalor[$i])."</option>";
				else echo "<option value='".$vvalor[$i]."'>".htmlentities($tvalor[$i])."</option>";
			}
			break;
		case 1:
			for ($i=0; $i<$total; $i++) {
				if ($valor==$vvalor[$i]) echo "<option value='".$vvalor[$i]."' selected>".htmlentities($tvalor[$i])."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS TIPOS DE EVALUACIONES EN UN SELECT
function getGCompetencias($valor, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT Area, Descripcion FROM rh_evaluacionarea ORDER BY Area";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$valor) echo "<option value='".$field[0]."' selected>".htmlentities($field[1])."</option>"; 
				else echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
		case 1:
			$sql="SELECT Area, Descripcion FROM rh_evaluacionarea WHERE Area='$valor'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
	}
}

//	FUNCION PARA VERIFICAR QUE EL REGISTRO A ELIMINAR NO TENGA REGISTROS HIJOS
function esFKey($tabla, $campo, $valor) {
	$sql="SELECT * FROM $tabla WHERE ($campo='$valor')";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	if ($rows!=0) return true; else return false;
}

//	FUNCION PARA CARGAR LOS TIPOS DE UTILIZACION DE LAS VACACIONES EN UN SELECT
function getUtilizacion($valor, $opt) {
	connect();
	$tvalor[0]="Goce"; $vvalor[0]="G";
	$tvalor[1]="Interrupcion"; $vvalor[1]="G";
	$total=2;
	switch ($opt) {
		case 0:
			for ($i=0; $i<$total; $i++) {
				if ($valor==$vvalor[$i]) echo "<option value='".$vvalor[$i]."' selected>".htmlentities($tvalor[$i])."</option>";
				else echo "<option value='".$vvalor[$i]."'>".htmlentities($tvalor[$i])."</option>";
			}
			break;
		case 1:
			for ($i=0; $i<$total; $i++) {
				if ($valor==$vvalor[$i]) echo "<option value='".$vvalor[$i]."' selected>".htmlentities($tvalor[$i])."</option>";
			}
			break;
	}
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

//	FUNCION PARA CARGAR LOS TIPOS DE EVALUACIONES EN UN SELECT
function getEvaluaciones($valor, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT Evaluacion, Descripcion FROM rh_evaluacion ORDER BY Evaluacion";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$valor) echo "<option value='".$field[0]."' selected>".htmlentities($field[1])."</option>"; 
				else echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
		case 1:
			$sql="SELECT Evaluacion, Descripcion FROM rh_evaluacion WHERE Evaluacion='$valor'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS TIPOS DE EVALUACIONES EN UN SELECT
function getPeriodosEvaluacion($valor, $padre, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT Secuencia, Descripcion FROM rh_evaluacionperiodo WHERE (CodOrganismo='".$padre."' AND Estado='A')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			while($field=mysql_fetch_array($query)) {
				echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR EN UN SELECT
function getTipoFD($valor, $opt) {
	connect();
	$tvalor[0]="Fortaleza"; $vvalor[0]="F";
	$tvalor[1]="Debilidad"; $vvalor[1]="D";
	$total=2;
	switch ($opt) {
		case 0:
			for ($i=0; $i<$total; $i++) {
				if ($valor==$vvalor[$i]) echo "<option value='".$vvalor[$i]."' selected>".htmlentities($tvalor[$i])."</option>";
				else echo "<option value='".$vvalor[$i]."'>".htmlentities($tvalor[$i])."</option>";
			}
			break;
		case 1:
			for ($i=0; $i<$total; $i++) {
				if ($valor==$vvalor[$i]) echo "<option value='".$vvalor[$i]."' selected>".htmlentities($tvalor[$i])."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR EN UN SELECT
function getTipoHabilidad($valor, $opt) {
	connect();
	$tvalor[0]="Habilidad"; $vvalor[0]="H";
	$tvalor[1]="Destreza"; $vvalor[1]="D";
	$total=2;
	switch ($opt) {
		case 0:
			for ($i=0; $i<$total; $i++) {
				if ($valor==$vvalor[$i]) echo "<option value='".$vvalor[$i]."' selected>".htmlentities($tvalor[$i])."</option>";
				else echo "<option value='".$vvalor[$i]."'>".htmlentities($tvalor[$i])."</option>";
			}
			break;
		case 1:
			for ($i=0; $i<$total; $i++) {
				if ($valor==$vvalor[$i]) echo "<option value='".$vvalor[$i]."' selected>".htmlentities($tvalor[$i])."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LAS PLANTILLAS DE COMPETENCIAS EN UN SELECT
function getPlantillas($valor, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT Plantilla, Descripcion FROM rh_evaluacionfactoresplantilla ORDER BY Plantilla";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$valor) echo "<option value='".$field[0]."' selected>".htmlentities($field[1])."</option>"; 
				else echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
		case 1:
			$sql="SELECT Plantilla, Descripcion FROM rh_evaluacionfactoresplantilla WHERE Plantilla='$valor'";
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
function loadSelect($tabla, $campo1, $campo2, $codigo, $opt) {
	connect();
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
	connect();
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
//
function formatFechaDMA($fecha) {
	list($a, $m, $d)=SPLIT( '[/.-]', $fecha);
	if ($fecha == "0000-00-00") return ""; else return "$d-$m-$a";
}
//
function formatFechaAMD($fecha) {
	list($d, $m, $a)=SPLIT( '[/.-]', $fecha);
	if ($fecha == "00-00-0000") return ""; else return "$a-$m-$d";
}
////  MODULO ACTIVO FIJO
//// ---------------------------------------------------------------
//// FUNCION QUE PERMITE OBTENER LA DEPENDENCIA SEGUN EL ORGANISMO
//// SELECCIONADO
function getDependenciaTransferir($DEPENDENCIA, $ORGANISMO){
  connect();
  $s_dep = "select * from mastdependencias where CodOrganismo = '".$ORGANISMO."'";
  $q_dep = mysql_query($s_dep) or die ($s_dep.mysql_error()); //echo $s_dep;
  $r_dep = mysql_num_rows($q_dep);
  
  if($r_dep!='0'){
	for($i=0;$i<$r_dep;$i++){
	   $f_dep = mysql_fetch_array($q_dep);
	   echo"<option value='".$f_dep['CodDependencia']."'>".$f_dep['Dependencia']."</option>"; 
	}
  }
}
//// ---------------------------------------------------------------------
//// CARGAR ESTADO
function getEstado($fEstado, $opt){
    connect();
	switch ($opt) {
	      case 0:
		     $tstatus[0]="Preparaci&oacute;n"; $vstatus[0]="PR";
			 $tstatus[1]="Aprobado"; $vstatus[1]="AP";
			 $tstatus[2]="Anulado"; $vstatus[2]="AN";
			 $tcant=3;
		     for ($i=0; $i<$tcant; $i++) {
				if ($fEstado==$vstatus[$i]) echo "<option value='".$vstatus[$i]."' selected>".$tstatus[$i]."</option>";
				else echo "<option value='".$vstatus[$i]."'>".$tstatus[$i]."</option>";
			}
			break;
		  case 1:
		     $tstatus[0]="Preparaci&oacute;n"; $vstatus[0]="PR";
			 $tstatus[1]="Aprobado"; $vstatus[1]="AP";
			 $tcant = 2;
		     for ($i=0; $i<$tcant; $i++) {
				if ($fEstado==$vstatus[$i]) echo "<option value='".$vstatus[$i]."' selected>".$tstatus[$i]."</option>";
				else echo "<option value='".$vstatus[$i]."'>".$tstatus[$i]."</option>";
			}
			break;
		  case 2:
		     /*$tstatus[0]="Activo"; $vstatus[0]="A";
			 $tstatus[1]="Inactivo"; $vstatus[1]="I";*/
			 $tstatus[0]="Pendiente"; $vstatus[0]="PE";
			 $tstatus[1]="Aprobado"; $vstatus[1]="AP";
			 $tcant = 2;
		     for ($i=0; $i<$tcant; $i++) {
				if ($fEstado==$vstatus[$i]) echo "<option value='".$vstatus[$i]."' selected>".$tstatus[$i]."</option>";
				else echo "<option value='".$vstatus[$i]."'>".$tstatus[$i]."</option>";
			}
			break;
			case 3:
		     $tstatus[0]="Preparaci&oacute;n"; $vstatus[0]="PR";
			 $tstatus[1]="Aprobado"; $vstatus[1]="AP";
			 $tstatus[2]="Anulado"; $vstatus[2]="AN";
			 $tcant = 3;
		     for ($i=0; $i<$tcant; $i++) {
				if ($fEstado==$vstatus[$i]) echo "<option value='".$vstatus[$i]."' selected>".$tstatus[$i]."</option>";
				else echo "<option value='".$vstatus[$i]."'>".$tstatus[$i]."</option>";
			}
			break;
		    case 4:
		     $tstatus[0]="Activado"; $vstatus[0]="A";
			 $tstatus[1]="Inactivo"; $vstatus[1]="I";
			 $tcant = 2;
		     for ($i=0; $i<$tcant; $i++) {
				if ($fEstado==$vstatus[$i]) echo "<option value='".$vstatus[$i]."' selected>".$tstatus[$i]."</option>";
				else echo "<option value='".$vstatus[$i]."'>".$tstatus[$i]."</option>";
			}
			break; 
	}	
}
/// -------------------------------------------------------------------------
/// CARGAR SITUACION ACTIVO
function getSituacionActivo($fSituacionActivo, $opt){
connect();
	switch ($opt) {
		case 0:
			$sql = "SELECT CodSituActivo, Descripcion FROM af_situacionactivo";
			$query = mysql_query($sql) or die ($sql.mysql_error());
			$rows = mysql_num_rows($query);
			for($i=0; $i<$rows; $i++) {
				$field = mysql_fetch_array($query);
				if($field['0']==$fSituacionActivo)echo"<option value='".$field['0']."' selected>".$field['1']."</option>";
				else echo"<option value='".$field['0']."'>".$field['1']."</option>";
			}
			break;
	}
}
/// ---------------------------------------------------------------------------
///  CARGAR TIPO SEGURO
function getT_Seguro($ftseguro, $opt){
connect();
	switch ($opt) {
		case 0:
			$sql = "SELECT CodTipoSeguro, Descripcion FROM af_tiposeguro";
			$query = mysql_query($sql) or die ($sql.mysql_error());
			$rows = mysql_num_rows($query);
			for($i=0; $i<$rows; $i++) {
				$field = mysql_fetch_array($query);
				if($field['0']==$ftseguro)echo"<option value='".$field['0']."' selected>".$field['1']."</option>";
 				else echo"<option value='".$field['0']."'>".$field['1']."</option>";
			}
			break;
	}
}
/// ---------------------------------------------------------------------------
/// CARGAR COLOR
function getColor($fcolor, $opt){
connect();
   switch($opt){
	   case 0:
	      $sql = "SELECT CodDetalle, Descripcion FROM mastmiscelaneosdet WHERE CodMaestro = 'COLOR'";
		  $qry = mysql_query($sql) or die ($sql.mysql_error());
		  $rows = mysql_num_rows($qry);
		  for($i=0; $i<$rows; $i++){
		    $field= mysql_fetch_array($qry);
			if($field['0']==$fcolor)echo"<option value='".$field['0']."' selected>".$field['1']."</option>";
			else echo"<option value='".$field['0']."'>".$field['1']."</option>";
		  }
		  break;
   }
}
/// ---------------------------------------------------------------------------
/// CARGAR CATEGORIA
function getCategoria($fCategoria, $opt){
connect();
  switch ($opt){
     case 0: 
	     $sql = "select CodCategoria, DescripcionLocal from af_categoriadeprec";
		 $qry = mysql_query($sql) or die ($sql.mysql_error());
		 $rows = mysql_num_rows($qry);
		 for($i=0; $i<$rows; $i++){
		  	 $field = mysql_fetch_array($qry);
			 if($field['0']==$fCategoria)echo"<option value='".$field['0']."' selected>".$field['0']." - ".$field['1']."</option>";
			 else echo"<option value='".$field['0']."'>".$field['0']." - ".$field['1']."</option>";
		 }
		 break;
	case 1: 
	     $sql = "select CodCategoria, DescripcionLocal from af_categoriadeprec";
		 $qry = mysql_query($sql) or die ($sql.mysql_error());
		 $rows = mysql_num_rows($qry);
		 for($i=0; $i<$rows; $i++){
		  	 $field = mysql_fetch_array($qry);
			 if($field['0']==$fCategoria)echo"<option value='".$field['0']."' selected>".$field['1']."</option>";
			 else echo"<option value='".$field['0']."'>".$field['1']."</option>";
		 }
		 break;
  }
}
/// ----------------------------------------------------------------------------
/// CARGAR CLASIFICACION ACTIVO
function getClasifActivo($fCatClasf, $opt){
connect();
  switch ($opt){
     case 0: 
	     $sql = "select CodClasificacion, Descripcion from af_clasificacionactivo";
		 $qry = mysql_query($sql) or die ($sql.mysql_error());
		 $rows = mysql_num_rows($qry);
		 for($i=0; $i<$rows; $i++){
		  	 $field = mysql_fetch_array($qry);
			 echo"<option value='".$field['0']."'>".$field['1']."</option>";
		 }
		 break;
  }
}
/// ----------------------------------------------------------------------------
/// CARGAR TIPOACTIVO
function getTipoActivo($fTipoActivo, $opt){
connect();
  switch ($opt){
     case 0: 
	     $sql = "select CodDetalle,Descripcion from mastmiscelaneosdet where CodMaestro = 'TIPOACTIVO'";
		 $qry = mysql_query($sql) or die ($sql.mysql_error());
		 $rows = mysql_num_rows($qry);
	     for($i=0; $i<$rows; $i++){
		  	 $field = mysql_fetch_array($qry);
			 if($field['0']==$fTipoActivo)echo"<option value='".$field['0']."' selected>".$field['1']."</option>";
			 else echo"<option value='".$field['0']."'>".$field['1']."</option>";
		 }
		 break;
  }
}
/// ----------------------------------------------------------------------------
function getEstadoListActivo($fEstado, $opt){
	$tstatus[0]="Pendiente de Activar"; $vstatus[0]="PE";
	$tstatus[1]="Activado"; $vstatus[1]="AC";
	$cantidad = 2;
	switch ($opt) {
	      case 0:
		     for ($i=0; $i<$cantidad; $i++) {
				if ($fEstado==$vstatus[$i]) echo "<option value='".$vstatus[$i]."' selected>".$tstatus[$i]."</option>";
				else echo "<option value='".$vstatus[$i]."'>".$tstatus[$i]."</option>";
			}
			break;
	}	
}
/// *****************************************************************************
///                     Referencia: af_listactivos.php
/// *****************************************************************************
function getEstadoListActivo2($fEstado, $opt){
	$tstatus[0]="Pendiente de Activar"; $vstatus[0]="PE";
	$tstatus[1]="Activado"; $vstatus[1]="AP";
	$cantidad = 2;
	switch ($opt) {
	      case 0:
		     for ($i=0; $i<$cantidad; $i++) {
				if ($fEstado==$vstatus[$i]) echo "<option value='".$vstatus[$i]."' selected>".$tstatus[$i]."</option>";
				else echo "<option value='".$vstatus[$i]."'>".$tstatus[$i]."</option>";
			}
			break;
	}	
}
/// ----------------------------------------------------------------------------
/// SEGURIDAD ALTERNA - CARGA DEPENDENCIA 
function getDependenciaSeguridad($dependencia, $organismo, $opt) {
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
//// ---------------------------------------------------------------------
//// PARA CARGAR SELECT DE NATURALEZA DE ACTIVO
function getNaturaleza($fNaturaleza, $opt){
  connect();
  switch ($opt){
      case 0:
	       $tvalor[0]="Activo Menor"; $vvalor[0]="AM";
	       $tvalor[1]="Activo Normal"; $vvalor[1]="AN";
		   $valor= 2;
	       for($i=0;$i<$valor;$i++){
			  if($fNaturaleza==$vvalor[$i]) echo"<option value='$vvalor[$i]' selected>$tvalor[$i]</option>";
			  else echo"<option value='$vvalor[$i]'>$tvalor[$i]</option>";
		   }
  }
} 
//// ---------------------------------------------------------------------
//// PARA CARGAR SELECT DE ORDENAR POR
function getOrdenarPor($fOrdenarPor,$opt){
  connect();
  switch($opt){
      case 0:
	       $tvalor[0]="Activo"; $vvalor[0]="Activo";
	       $tvalor[1]="Codigo Interno"; $vvalor[1]="CodigoInterno";
		   $tvalor[2]="Descripcion"; $vvalor[2]="Descripcion";
		   $valor= 3;
	       for($i=0;$i<$valor;$i++){
			  if($fOrdenarPor==$vvalor[$i]) echo"<option value='$vvalor[$i]' selected>$tvalor[$i]</option>";
			  else echo"<option value='$vvalor[$i]'>$tvalor[$i]</option>";
		   }   
  
  }
}
//// ---------------------------------------------------------------------
//// PARA CARGAR SELECT DE ORDENAR POR
function getContabilidad($fContabilidad, $opt){
 connect();
 switch($opt){
  case 0:
       $sql = "select * from ac_contabilidades";
	   $qry = mysql_query($sql) or die ($sql.mysql_error());
	   $row = mysql_num_rows($qry);
	   for($i=0;$i<$row;$i++){
	      $field = mysql_fetch_array($qry);
	      if($field['CodContabilidad']==$fContabilidad) 
	        echo"<option value='".$field['CodContabilidad']."' selected>".$field['Descripcion']."</option> ";
	      else 
	        echo"<option value='".$field['CodContabilidad']."'>".$field['Descripcion']."</option> ";
	   }
 }
}
//// ---------------------------------------------------------------------
//// PARA CARGAR SELECT MOSTRAR AF_AGRUPARCONSOLIDARACT.PHP
function getMostrar($fMostrar,$opt){
  connect();
  switch($opt){
      case 0:
	       $tvalor[0]="Activos sin relacionar"; $vvalor[0]="SR";
	       $tvalor[1]="Activos relacionados"; $vvalor[1]="AR";
		   $tvalor[2]="Todos los Activos"; $vvalor[2]="TA";
		   $valor= 3;
	       for($i=0;$i<$valor;$i++){
			  if($fMostrar==$vvalor[$i]) echo"<option value='$vvalor[$i]' selected>$tvalor[$i]</option>";
			  else echo"<option value='$vvalor[$i]'>$tvalor[$i]</option>";
		   }   
           break;  
  }
}
//// ---------------------------------------------------------------------
//// 
function getBienes($fBienes,$opt){
    connect();
	switch($opt){
	  case 0:
	       $tvalor[0]="Inmuebles"; $vvalor[0]="01";
	       $tvalor[1]="Muebles";   $vvalor[1]="02";	
		   $valor= 2;
		   for($i=0; $i<$valor; $i++){
		     if($fBienes==$vvalor[$i]) echo"<option value='$vvalor[$i]' selected>$tvalor[$i]</option>";
			 else echo"<option value='$vvalor[$i]'>$tvalor[$i]</option>";
		   }
		   break;
	}
}
//// ---------------------------------------------------------------------
//// 
function myTruncate($string, $limit, $break, $pad) {
if(strlen($string) <= $limit)
return $string;
if(false!== ($breakpoint = strpos($string,$break,$limit))) {
 if($breakpoint < strlen($string)){
    $string = substr($string, 0, $breakpoint) . $pad;
  }
}
return $string;
}
//// ---------------------------------------------------------------------
//// 
function getTipoCuenta($fBienes,$opt){
    connect();
	switch($opt){
	  case 0:
	       $tvalor[0]="Cuentas del Tesoro"; $vvalor[0]="CT";
	       $tvalor[1]="Cuentas de la Hacienda";   $vvalor[1]="CH";	
		   $tvalor[2]="Cuentas del Presupuesto";   $vvalor[2]="CP";
		   $tvalor[3]="Cuentas de Resultado del Presupuesto";  $vvalor[3]="CP";	
		   $tvalor[4]="Cuentas de Patrimonio";  $vvalor[4]="CD";	
		   $valor= 5;
		   for($i=0; $i<$valor; $i++){
		     if($fBienes==$vvalor[$i]) echo"<option value='$vvalor[$i]' selected>$tvalor[$i]</option>";
			 else echo"<option value='$vvalor[$i]'>$tvalor[$i]</option>";
		   }
		   break;
	}
}
?>
