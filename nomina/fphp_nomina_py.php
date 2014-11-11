<?php
session_start();
set_time_limit(-1);
ini_set('memory_limit','128M'); 
extract($_POST);
extract($_GET);
include ("../../funciones.php");

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
function getSecuencia($secuencia, $codigo, $tabla, $valor) {
	connect();
	$sql="select MAX($secuencia) FROM $tabla WHERE $codigo='$valor'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$field=mysql_fetch_array($query);
	$secuencia=(int) ($field[0]+1);
	return ($secuencia);
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
function getCodigo_2($tabla, $campo, $correlativo, $valor, $digitos) {
	connect();
	$sql="select max($campo) FROM $tabla WHERE $correlativo = '$valor'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$field=mysql_fetch_array($query);
	$codigo=(int) ($field[0]+1);
	$codigo=(string) str_repeat("0", $digitos-strlen($codigo)).$codigo;
	return ($codigo);
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
	}
}

//	FUNCION PARA CARGAR LAS APLICACIONES EN UN SELECT
function getAplicaciones($aplicacion, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT * FROM mastaplicaciones ORDER BY CodAplicacion";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field['CodAplicacion']==$aplicacion) echo "<option value='".$field['CodAplicacion']."' selected>".$field['Descripcion']."</option>"; 
				else echo "<option value='".$field['CodAplicacion']."'>".$field['Descripcion']."</option>";
			}
			break;
		case 1:
			$sql="SELECT * FROM mastaplicaciones WHERE CodAplicacion='$aplicacion'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field['CodAplicacion']."'>".$field['Descripcion']."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS PAISES EN UN SELECT
function getPaises($pais, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT * FROM mastpaises ORDER BY CodPais";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				$field['Pais']=($field['Pais']);
				if ($field['CodPais']==$pais) echo "<option value='".$field['CodPais']."' selected>".$field['Pais']; 
				else echo "<option value='".$field['CodPais']."'>".$field['Pais']."</option>";
			}
			break;
		case 1:
			$sql="SELECT * FROM mastpaises WHERE CodPais='$pais'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				$field['Pais']=($field['Pais']);
				echo "<option value='".$field['CodPais']."'>".$field['Pais']."</option>";
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
				$field['Estado']=($field['Estado']);
				if ($field['CodEstado']==$estado) echo "<option value='".$field['CodEstado']."' selected>".$field['Estado']; 
				else echo "<option value='".$field['CodEstado']."'>".$field['Estado']."</option>";
			}
			break;
		case 1:
			$sql="SELECT CodEstado, Estado FROM mastestados WHERE CodEstado='".$estado."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				$field['Estado']=($field['Estado']);
				echo "<option value='".$field['CodEstado']."'>".$field['Estado']."</option>";
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
				$field['Municipio']=($field['Municipio']);
				if ($field['CodMunicipio']==$municipio) echo "<option value='".$field['CodMunicipio']."' selected>".$field['Municipio']."</option>"; 
				else echo "<option value='".$field['CodMunicipio']."'>".$field['Municipio']."</option>";
			}
			break;
		case 1:
			$sql="SELECT CodMunicipio, Municipio FROM mastmunicipios WHERE CodMunicipio='".$municipio."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				$field['Municipio']=($field['Municipio']);
				echo "<option value='".$field['CodMunicipio']."'>".$field['Municipio']."</option>";
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
				$field['Ciudad']=($field['Ciudad']);
				if ($field['CodCiudad']==$ciudad) echo "<option value='".$field['CodCiudad']."' selected>".$field['Ciudad']."</option>"; 
				else echo "<option value='".$field['CodCiudad']."'>".$field['Ciudad']."</option>";
			}
			break;
		case 1:
			$sql="SELECT CodCiudad, Ciudad FROM mastciudades WHERE CodCiudad='".$ciudad."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				$field['Ciudad']=($field['Ciudad']);
				echo "<option value='".$field['CodCiudad']."'>".$field['Ciudad']."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS TIPOS DE PAGO EN UN SELECT
function getTPago($tpago, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT * FROM masttipopago ORDER BY CodTipoPago";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field['CodTipoPago']==$tpago) echo "<option value='".$field['CodTipoPago']."' selected>".$field['TipoPago']."</option>"; 
				else echo "<option value='".$field['CodTipoPago']."'>".$field['TipoPago']."</option>";
			}
			break;
		case 1:
			$sql="SELECT * FROM masttipopago WHERE CodTipoPago='$tpago'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field['CodTipoPago']."'>".$field['TipoPago']."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS ORGANISMOS EN UN SELECT
function getOrganismos($organismo, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT * FROM mastorganismos WHERE CodOrganismo<>'' ORDER BY CodOrganismo";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field['CodOrganismo']==$organismo) echo "<option value='".$field['CodOrganismo']."' selected>".$field['Organismo']."</option>"; 
				else echo "<option value='".$field['CodOrganismo']."'>".$field['Organismo']."</option>";
			}
			break;
		case 1:
			$sql="SELECT * FROM mastorganismos WHERE CodOrganismo='$organismo'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field['CodOrganismo']."'>".$field['Organismo']."</option>";
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
	switch ($opt) {
		case 0:
			$sql="SELECT CodDependencia, Dependencia FROM mastdependencias WHERE CodOrganismo='".$organismo."' AND CodDependencia<>'' ORDER BY CodDependencia";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field['CodDependencia']==$dependencia) echo "<option value='".$field['CodDependencia']."' selected>".$field['Dependencia']."</option>"; 
				else echo "<option value='".$field['CodDependencia']."'>".$field['Dependencia']."</option>";
			}
			break;
		case 1:
			$sql="SELECT CodDependencia, Dependencia FROM mastdependencias WHERE CodDependencia='".$dependencia."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field['CodDependencia']."'>".$field['Dependencia']."</option>";
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
				if ($field['CodDivision']==$division) echo "<option value='".$field['CodDivision']."' selected>".$field['Division']."</option>"; 
				else echo "<option value='".$field['CodDivision']."'>".$field['Division']."</option>";
			}
			break;
		case 1:
			$sql="SELECT CodDivision, Division FROM mastdivisiones WHERE CodDivision='".$division."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field['CodDivision']."'>".$field['Division']."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS GRUPOS EN UN SELECT
function getGrupos($grupo, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT * FROM rh_grupoocupacional ORDER BY CodGrupOcup";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field['CodGrupOcup']==$grupo) echo "<option value='".$field['CodGrupOcup']."' selected>".$field['GrupoOcup']."</option>"; 
				else echo "<option value='".$field['CodGrupOcup']."'>".$field['GrupoOcup']."</option>";
			}
			break;
		case 1:
			$sql="SELECT * FROM rh_grupoocupacional WHERE CodGrupOcup='$grupo'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field['CodGrupOcup']."'>".$field['GrupoOcup']."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS TIPOS DE CARGO EN UN SELECT
function getSeries($serie, $grupo, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT * FROM rh_serieocupacional WHERE CodGrupOcup='$grupo' ORDER BY CodSerieOcup";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field['CodSerieOcup']==$serie) echo "<option value='".$field['CodSerieOcup']."' selected>".$field['SerieOcup']."</option>";
				else echo "<option value='".$field['CodSerieOcup']."'>".$field['SerieOcup']."</option>";
			}
			break;
		case 1:
			$sql="SELECT * FROM rh_serieocupacional WHERE CodSerieOcup='$serie'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field['CodSerieOcup']."'>".$field['SerieOcup']."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS TIPOS DE CARGO EN UN SELECT
function getTCargo($tcargo, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT * FROM rh_tipocargo ORDER BY CodTipoCargo";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field['CodTipoCargo']==$tcargo) echo "<option value='".$field['CodTipoCargo']."' selected>".$field['TipCargo']."</option>";
				else echo "<option value='".$field['CodTipoCargo']."'>".$field['TipCargo']."</option>";
			}
			break;
		case 1:
			$sql="SELECT * FROM rh_tipocargo WHERE CodTipoCargo='$tcargo'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field['CodTipoCargo']."'>".$field['TipCargo']."</option>";
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
				if ($field['CodNivelClase']==$nivelcargo) echo "<option value='".$field['CodNivelClase']."' selected>".$field['NivelClase']."</option>"; 
				else echo "<option value='".$field['CodNivelClase']."'>".$field['NivelClase']."</option>";
			}
			break;
		case 1:
			$sql="SELECT CodNivelClase, NivelClase FROM rh_nivelclasecargo WHERE CodNivelClase='$nivelcargo'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field['CodNivelClase']."'>".$field['NivelClase']."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS CARGOS EN UN SELECT
function getCargos($cargo, $serie, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT * FROM rh_puestos WHERE CodSerieOcup='$serie' ORDER BY CodCargo";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field['CodCargo']==$cargo) echo "<option value='".$field['CodCargo']."' selected>".$field['DescripCargo']."</option>"; 
				else echo "<option value='".$field['CodCargo']."'>".$field['DescripCargo']."</option>";
			}
			break;
		case 1:
			$sql="SELECT * FROM rh_puestos WHERE CodCargo='$cargo'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field['CodCargo']."'>".$field['DescripCargo']."</option>";
			}
			break;
		case 2:
			$sql="SELECT * FROM rh_puestos ORDER BY DescripCargo";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field['CodCargo']==$cargo) echo "<option value='".$field['CodCargo']."' selected>".$field['DescripCargo']."</option>"; 
				else echo "<option value='".$field['CodCargo']."'>".$field['DescripCargo']."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS TIPOS DE TRABAJADOR EN UN SELECT
function getTTrabajador($ttrabajador, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT * FROM rh_tipotrabajador ORDER BY CodTipoTrabajador";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field['CodTipoTrabajador']==$ttrabajador) echo "<option value='".$field['CodTipoTrabajador']."' selected>".$field['TipoTrabajador']."</option>";
				else echo "<option value='".$field['CodTipoTrabajador']."'>".$field['TipoTrabajador']."</option>";
			}
			break;
		case 1:
			$sql="SELECT * FROM rh_tipotrabajador WHERE CodTipoTrabajador='$ttrabajador'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field['CodTipoTrabajador']."'>".$field['TipoTrabajador']."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS TIPOS DE NOMINA EN UN SELECT
function getTNomina($tnomina, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT * FROM tiponomina ORDER BY CodTipoNom";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field['CodTipoNom']==$tnomina) echo "<option value='".$field['CodTipoNom']."' selected>".$field['Nomina']."</option>"; 
				else echo "<option value='".$field['CodTipoNom']."'>".$field['Nomina']."</option>";
			}
			break;
		case 1:
			$sql="SELECT * FROM tiponomina WHERE CodTipoNom='$tnomina'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field['CodTipoNom']."'>".$field['Nomina']."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS PERFILES DE NOMINA EN UN SELECT
function getPNomina($pnomina, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT * FROM tipoperfilnom ORDER BY CodPerfil";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field['CodPerfil']==$pnomina) echo "<option value='".$field['CodPerfil']."' selected>".$field['Perfil']."</option>"; 
				else echo "<option value='".$field['CodPerfil']."'>".$field['Perfil']."</option>";
			}
			break;
		case 1:
			$sql="SELECT * FROM tipoperfilnom WHERE CodPerfil='$pnomina'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field['CodPerfil']."'>".$field['Perfil']."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS TIPOS DE CESE EN UN SELECT
function getTCese($tcese, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT * FROM rh_motivocese WHERE CodMotivoCes<>'' ORDER BY CodMotivoCes";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field['CodMotivoCes']==$tcese) echo "<option value='".$field['CodMotivoCes']."' selected>".$field['MotivoCese']."</option>"; 
				else echo "<option value='".$field['CodMotivoCes']."'>".$field['MotivoCese']."</option>";
			}
			break;
		case 1:
			$sql="SELECT * FROM rh_motivocese WHERE CodMotivoCes<>'' AND CodMotivoCes='$tcese'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field['CodMotivoCes']."'>".$field['MotivoCese']."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS TIPOS DE SEGURO EN UN SELECT
function getTSeguros($tseguro, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT * FROM rh_tiposeguro ORDER BY CodTipSeguro";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field['CodTipSeguro']==$tseguro) echo "<option value='".$field['CodTipSeguro']."' selected>".$field['Descripcion']."</option>"; 
				else echo "<option value='".$field['CodTipSeguro']."'>".$field['Descripcion']."</option>";
			}
			break;
		case 1:
			$sql="SELECT * FROM rh_tiposeguro WHERE CodTipSeguro='$tseguro'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field['CodTipSeguro']."'>".$field['Descripcion']."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS TIPOS DE CONTRATOS EN UN SELECT
function getTContratos($tcontrato, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT * FROM rh_tipocontrato WHERE TipoContrato<>'' ORDER BY TipoContrato";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field['TipoContrato']==$tcontrato) echo "<option value='".$field['TipoContrato']."' selected>".$field['Descripcion']."</option>"; 
				else echo "<option value='".$field['TipoContrato']."'>".$field['Descripcion']."</option>";
			}
			break;
		case 1:
			$sql="SELECT * FROM rh_tipocontrato WHERE TipoContrato='$tcontrato'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field['TipoContrato']."'>".$field['Descripcion']."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS TIPOS DE FORMATOS DE CONTRATOS EN UN SELECT
function getFContratos($fcontrato, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT * FROM rh_formatocontrato ORDER BY CodFormato";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field['CodFormato']==$fcontrato) echo "<option value='".$field['CodFormato']."' selected>".$field['Documento']."</option>"; 
				else echo "<option value='".$field['CodFormato']."'>".$field['Documento']."</option>";
			}
			break;
		case 1:
			$sql="SELECT * FROM rh_formatocontrato WHERE CodFormato='$fcontrato' ORDER BY CodFormato";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field['CodFormato']."'>".$field['Documento']."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR EL MISCELANEO EN UN SELECT
function getMiscelaneos($detalle, $maestro, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT * FROM mastmiscelaneosdet WHERE CodMaestro='$maestro' ORDER BY CodDetalle";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field['CodDetalle']==$detalle) echo "<option value='".$field['CodDetalle']."' selected>".$field['Descripcion']."</option>";
				else echo "<option value='".$field['CodDetalle']."'>".$field['Descripcion']."</option>";
			}
			break;
		case 1:
			$sql="SELECT * FROM mastmiscelaneosdet WHERE CodMaestro='$maestro' AND CodDetalle='$detalle'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field['CodDetalle']."'>".$field['Descripcion']."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS BANCOS EN UN SELECT
function getBancos($banco, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT * FROM mastbancos ORDER BY CodBanco";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field['CodBanco']==$banco) echo "<option value='".$field['CodBanco']."' selected>".$field['Banco']."</option>"; 
				else echo "<option value='".$field['CodBanco']."'>".$field['Banco']."</option>";
			}
			break;
		case 1:
			$sql="SELECT * FROM mastbancos WHERE CodBanco='$banco'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field['CodBanco']."'>".$field['Banco']."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS TIPOS DE GRADO DE INSTRUCCION EN UN SELECT
function getGInstruccion($grado, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT * FROM rh_gradoinstruccion ORDER BY CodGradoInstruccion";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field['CodGradoInstruccion']==$grado) echo "<option value='".$field['CodGradoInstruccion']."' selected>".$field['Descripcion']."</option>"; 
				else echo "<option value='".$field['CodGradoInstruccion']."'>".$field['Descripcion']."</option>";
			}
			break;
		case 1:
			$sql="SELECT * FROM rh_gradoinstruccion WHERE CodGradoInstruccion='$grado' ORDER BY CodGradoInstruccion";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field['CodGradoInstruccion']."'>".$field['Descripcion']."</option>";
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
				if ($field['Nivel']==$nivel) echo "<option value='".$field['Nivel']."' selected>".$field['Descripcion']; 
				else echo "<option value='".$field['Nivel']."'>".$field['Descripcion']."</option>";
			}
			break;
		case 1:
			$sql="SELECT Nivel, Descripcion FROM rh_nivelgradoinstruccion WHERE Nivel='".$nivel."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field['Nivel']."'>".$field['Descripcion']."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS CENTROS DE ESTUDIOS EN UN SELECT EN UN SELECT
function getCEstudios($centro, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT * FROM rh_centrosestudios WHERE (CodCentroEstudio<>'') ORDER BY CodCentroEstudio";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field['CodCentroEstudio']==$centro) echo "<option value='".$field['CodCentroEstudio']."' selected>".$field['Descripcion']."</option>"; 
				else echo "<option value='".$field['CodCentroEstudio']."'>".$field['Descripcion']."</option>";
			}
			break;
		case 1:
			$sql="SELECT * FROM rh_centrosestudios WHERE (CodCentroEstudio<>'') AND CodCentroEstudio='$centro' ORDER BY CodCentroEstudio";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field['CodCentroEstudio']."'>".$field['Descripcion']."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS IDIOMAS EN UN SELECT
function getIdiomas($idioma, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT * FROM mastidioma ORDER BY CodIdioma";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field['CodIdioma']==$idioma) echo "<option value='".$field['CodIdioma']."' selected>".$field['DescripcionLocal']."</option>";
				else echo "<option value='".$field['CodIdioma']."'>".$field['DescripcionLocal']."</option>";
			}
			break;
		case 1:
			$sql="SELECT * FROM mastidioma WHERE CodIdioma='$idioma'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field['CodIdioma']."'>".$field['DescripcionLocal']."</option>";
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
	
	//convierto segundos en días
	$dias_diferencia = $segundos_diferencia / (60 * 60 * 24);
	
	//obtengo el valor absoulto de los días (quito el posible signo negativo)
	$dias_diferencia = abs($dias_diferencia);
	
	//quito los decimales a los días de diferencia
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
	$tordenar[0]="Persona"; $vordenar[0]="mastpersonas.CodPersona";
	$tordenar[1]="Nombre Completo"; $vordenar[1]="mastpersonas.NomCompleto";
	$tordenar[2]="Nro. Documento"; $vordenar[2]="mastpersonas.Ndocumento";
	$tordenar[3]="Fecha de Ingreso"; $vordenar[3]="mastempleado.Fingreso";
	$tordenar[4]="Dependencia"; $vordenar[4]="mastdependencias.Dependencia";
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
				if ($field['CodProfesion']==$profesion) echo "<option value='".$field['CodProfesion']."' selected>".$field['Descripcion']."</option>"; 
				else echo "<option value='".$field['CodProfesion']."'>".$field['Descripcion']."</option>";
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
				if ($field['Grado']==$grado) echo "<option value='".$field['Grado']."' selected>".($field['Grado'])."</option>"; 
				else echo "<option value='".$field['Grado']."'>".($field['Grado'])."</option>";
			}
			break;
			
		case 1:
			$sql="SELECT Grado, Descripcion FROM rh_nivelsalarial WHERE CategoriaCargo='$categoria' AND Grado = '$grado'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows != 0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field['Grado']."' selected>".($field['Grado'])."</option>"; 
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
			$sql="SELECT * FROM rh_cursos ORDER BY CodCurso";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field['CodCurso']==$curso) echo "<option value='".$field['CodCurso']."' selected>".$field['Descripcion']."</option>";
				else echo "<option value='".$field['CodCurso']."'>".$field['Descripcion']."</option>";
			}
			break;
		case 1:
			$sql="SELECT * FROM rh_cursos WHERE CodCurso='$curso'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field['CodCurso']."'>".$field['Descripcion']."</option>";
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
				if ($field['CodCentroEstudio']==$centro) echo "<option value='".$field['CodCentroEstudio']."' selected>".$field['Descripcion']."</option>";
				else echo "<option value='".$field['CodCentroEstudio']."'>".$field['Descripcion']."</option>";
			}
			break;
		case 1:
			$sql="SELECT CodCentroEstudio, Descripcion FROM rh_centrosestudios WHERE CodCentroEstudio='$centro'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field['CodCentroEstudio']."'>".$field['Descripcion']."</option>";
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
	$tstatus[0]="En Preparaci&oacute;n"; $vstatus[0]="P";
	$tstatus[1]="Aprobado"; $vstatus[1]="A";
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
				if ($status==$vstatus[$i]) echo "<option value='".$vstatus[$i]."' selected>".($tvalor[$i])."</option>";
				else echo "<option value='".$vstatus[$i]."'>".($tvalor[$i])."</option>";
			}
			break;
		case 1:
			for ($i=0; $i<2; $i++) {
				if ($status==$vstatus[$i]) echo "<option value='".$vstatus[$i]."' selected>".($tvalor[$i])."</option>";
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
				if ($field[0]==$valor) echo "<option value='".$field[0]."' selected>".($field[1])."</option>"; 
				else echo "<option value='".$field[0]."'>".($field[1])."</option>";
			}
			break;
		case 1:
			$sql="SELECT TipoEvaluacion, Descripcion FROM rh_tipoevaluacion WHERE TipoEvaluacion='$valor'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field[0]."'>".($field[1])."</option>";
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
				if ($field[0]==$valor) echo "<option value='".$field[0]."' selected>".($field[1])."</option>"; 
				else echo "<option value='".$field[0]."'>".($field[1])."</option>";
			}
			break;
		case 1:
			$sql="SELECT Area, Descripcion FROM rh_evaluacionarea WHERE Area='$valor'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field[0]."'>".($field[1])."</option>";
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
				if ($valor==$vvalor[$i]) echo "<option value='".$vvalor[$i]."' selected>".($tvalor[$i])."</option>";
				else echo "<option value='".$vvalor[$i]."'>".($tvalor[$i])."</option>";
			}
			break;
		case 1:
			for ($i=0; $i<$total; $i++) {
				if ($valor==$vvalor[$i]) echo "<option value='".$vvalor[$i]."' selected>".($tvalor[$i])."</option>";
			}
			break;
	}
}

// CARGAR EN UN SELECT TIPO DE VALORES	
function getTipoValor($valor, $opt) {
	connect();
	$tvalor[0]="Numérico"; $vvalor[0]="N";
	$tvalor[1]="Alfanumérico"; $vvalor[1]="A";
	$total=2;
	switch ($opt) {
		case 0:
			for ($i=0; $i<$total; $i++) {
				if ($valor==$vvalor[$i]) echo "<option value='".$vvalor[$i]."' selected>".($tvalor[$i])."</option>";
				else echo "<option value='".$vvalor[$i]."'>".($tvalor[$i])."</option>";
			}
			break;
		case 1:
			for ($i=0; $i<$total; $i++) {
				if ($valor==$vvalor[$i]) echo "<option value='".$vvalor[$i]."' selected>".($tvalor[$i])."</option>";
			}
			break;
	}
}

// CARGAR EN UN SELECT TIPO DE CONCEPTOS	
function getTipoConcepto($valor, $opt) {
	connect();
	$tvalor[0]="Ingresos"; $vvalor[0]="I";
	$tvalor[1]="Descuentos"; $vvalor[1]="D";
	$tvalor[2]="Aportes"; $vvalor[2]="A";
	$tvalor[3]="Provisiones"; $vvalor[3]="P";
	$tvalor[4]="Totales"; $vvalor[4]="T";
	$total=5;
	switch ($opt) {
		case 0:
			for ($i=0; $i<$total; $i++) {
				if ($valor==$vvalor[$i]) echo "<option value='".$vvalor[$i]."' selected>".($tvalor[$i])."</option>";
				else echo "<option value='".$vvalor[$i]."'>".($tvalor[$i])."</option>";
			}
			break;
		case 1:
			for ($i=0; $i<$total; $i++) {
				if ($valor==$vvalor[$i]) echo "<option value='".$vvalor[$i]."' selected>".($tvalor[$i])."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LAS FORMULAS EN UN SELECT
function getFormulas($codigo, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT CodFormula AS Codigo, Descripcion FROM pr_formula ORDER BY CodFormula";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field['Codigo']==$codigo) echo "<option value='".$field['Codigo']."' selected>".($field['Descripcion'])."</option>"; 
				else echo "<option value='".$field['Codigo']."'>".($field['Descripcion'])."</option>";
			}
			break;
		case 1:
			$sql="SELECT CodFormula AS Codigo, Descripcion FROM pr_formula WHERE CodFormula='$codigo'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field['Codigo']."'>".($field['Descripcion'])."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS TIPOS DE PROCESO EN UN SELECT
function getTipoProceso($codigo, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT CodTipoProceso AS Codigo, Descripcion FROM pr_tipoproceso ORDER BY CodTipoProceso";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field['Codigo']==$codigo) echo "<option value='".$field['Codigo']."' selected>".($field['Descripcion'])."</option>"; 
				else echo "<option value='".$field['Codigo']."'>".($field['Descripcion'])."</option>";
			}
			break;
		case 1:
			$sql="SELECT CodTipoProceso AS Codigo, Descripcion FROM pr_tipoproceso WHERE CodTipoProceso='$codigo'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field['Codigo']."'>".($field['Descripcion'])."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS TIPOS DE PROCESO EN UN SELECT
function getConceptos($codigo, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT CodConcepto, Descripcion, Tipo FROM pr_concepto WHERE Estado = 'A' ORDER BY Tipo, CodConcepto";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$codigo) echo "<option value='".$field[0]."' selected>($field[2]) ".($field[1])."</option>"; 
				else echo "<option value='".$field[0]."'>$field[2] ".($field[1])."</option>";
			}
			break;
			
		case 1:
			$sql="SELECT CodConcepto, Descripcion, Tipo FROM pr_concepto WHERE CodConcepto='$codigo'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field[0]."'>($field[2]) ".($field[1])."</option>";
			}
			break;
			
		case 2:
			$sql="SELECT CodConcepto, Descripcion, Tipo FROM pr_concepto WHERE Tipo = 'D' ORDER BY CodConcepto";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$codigo) echo "<option value='".$field[0]."' selected>($field[2]) ".($field[1])."</option>"; 
				else echo "<option value='".$field[0]."'>$field[2] ".($field[1])."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS TIPOS DE PROCESO EN UN SELECT
function getTipoProcesoNomina($codigo, $periodo, $nomina, $codorganismo, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT ptnp.CodTipoProceso AS Codigo, ptp.Descripcion FROM pr_tiponominaproceso ptnp INNER JOIN pr_tipoproceso ptp ON (ptnp.CodTipoProceso=ptp.CodTipoProceso) WHERE (ptnp.CodTipoNom='".$nomina."') ORDER BY ptnp.CodTipoProceso";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field['Codigo']==$codigo) echo "<option value='".$field['Codigo']."' selected>".($field['Descripcion'])."</option>"; 
				else echo "<option value='".$field['Codigo']."'>".($field['Descripcion'])."</option>";
			}
			break;
			
		case 1:			
			$sql="SELECT tp.CodTipoProceso AS Codigo, tp.Descripcion FROM pr_tipoproceso tp INNER JOIN pr_procesoperiodo pp ON (tp.CodTipoProceso=pp.CodTipoProceso AND pp.CodTipoNom='".$nomina."' AND pp.Periodo='".$periodo."' AND pp.CodOrganismo='".$codorganismo."' AND pp.Estado='A' AND pp.FlagAprobado='S' AND pp.FlagProcesado='N') ORDER BY tp.CodTipoProceso DESC";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field['Codigo']==$codigo) echo "<option value='".$field['Codigo']."' selected>".($field['Descripcion'])."</option>"; 
				else echo "<option value='".$field['Codigo']."'>".($field['Descripcion'])."</option>";
			}
			break;
			
		case 5:		
			$sql="SELECT tp.CodTipoProceso AS Codigo, tp.Descripcion FROM pr_tipoproceso tp INNER JOIN pr_procesoperiodoprenomina pp ON (tp.CodTipoProceso=pp.CodTipoProceso AND pp.CodTipoNom='".$nomina."' AND pp.Periodo='".$periodo."' AND pp.CodOrganismo='".$codorganismo."' AND pp.Estado='A' AND pp.FlagAprobado='S' AND pp.FlagProcesado='N') ORDER BY tp.CodTipoProceso DESC";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field['Codigo']==$codigo) echo "<option value='".$field['Codigo']."' selected>".($field['Descripcion'])."</option>"; 
				else echo "<option value='".$field['Codigo']."'>".($field['Descripcion'])."</option>";
			}
			break;
			
		case 6:			
			$sql="SELECT tp.CodTipoProceso AS Codigo, tp.Descripcion FROM pr_tipoproceso tp INNER JOIN pr_procesoperiodo pp ON (tp.CodTipoProceso=pp.CodTipoProceso AND pp.CodTipoNom='".$nomina."' AND pp.Periodo='".$periodo."' AND pp.CodOrganismo='".$codorganismo."' AND pp.FlagAprobado='S') ORDER BY tp.CodTipoProceso DESC";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field['Codigo']==$codigo) echo "<option value='".$field['Codigo']."' selected>".($field['Descripcion'])."</option>"; 
				else echo "<option value='".$field['Codigo']."'>".($field['Descripcion'])."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS PERIODOS
function getPeriodos($valor, $tiponom, $codorganismo, $opt) {
	connect();
	list($anio, $mes)=SPLIT( '[-.-]', $valor);
	switch ($opt) {
		case 0:
			$sql="SELECT Periodo FROM pr_procesoperiodo WHERE CodTipoNom='".$tiponom."' AND CodOrganismo='".$codorganismo."' AND Estado='A' AND FlagProcesado='N' AND FlagAprobado='S' GROUP BY Periodo ORDER BY Periodo DESC";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				$codigo=$field['Periodo'];
				if ($codigo==$valor) echo "<option value='".$codigo."' selected>".$codigo."</option>"; 
				else  echo "<option value='".$codigo."'>".$codigo."</option>"; 
			}
			break;
			
		case 1:
			$sql="SELECT Periodo, Mes FROM pr_tiponominaperiodo WHERE CodTipoNom='".$tiponom."'  ORDER BY Periodo";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				$codigo=$field['Periodo']."-".$field['Mes'];
				if ($codigo>=$valor) {
					if ($codigo==$valor) echo "<option value='".$codigo."' selected>".$codigo."</option>"; 
					else  echo "<option value='".$codigo."'>".$codigo."</option>"; 
				}
			}
			break;
			
		case 2:
			$sql="SELECT Periodo, Mes FROM pr_tiponominaperiodo WHERE CodTipoNom='".$tiponom."' AND Periodo='".$anio."' AND Mes='".$mes."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			if(mysql_num_rows($query)!=0) {
				$field=mysql_fetch_array($query);
				$codigo=$field['Periodo']."-".$field['Mes'];
				if ($codigo==$valor) echo "<option value='".$codigo."' selected>".$codigo."</option>"; 
			}
			break;
			
		case 3:
			$sql="SELECT Periodo FROM pr_procesoperiodo WHERE Periodo>='".$valor."' AND CodTipoNom='".$tiponom."' AND CodOrganismo='".$codorganismo."' AND Estado='A' AND FlagProcesado='N' AND FlagAprobado='S' GROUP BY Periodo ORDER BY Periodo DESC";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				$codigo=$field['Periodo'];
				if ($codigo==$valor) echo "<option value='".$codigo."' selected>".$codigo."</option>"; 
				else  echo "<option value='".$codigo."'>".$codigo."</option>"; 
			}
			break;
			
		case 4:
			$sql="SELECT Periodo FROM pr_procesoperiodo WHERE CodTipoNom='".$tiponom."' AND CodOrganismo='".$codorganismo."' AND Estado='A' AND FlagProcesado='N' AND FlagAprobado='S' GROUP BY Periodo ORDER BY Periodo DESC";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				$codigo=$field['Periodo'];
				if ($codigo==$valor) echo "<option value='".$codigo."' selected>".$codigo."</option>"; 
			}
			break;
			
		case 5:
			$sql="SELECT Periodo FROM pr_procesoperiodoprenomina WHERE CodTipoNom='".$tiponom."' AND CodOrganismo='".$codorganismo."' AND Estado='A' AND FlagProcesado='N' AND FlagAprobado='S' GROUP BY Periodo ORDER BY Periodo DESC";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				$codigo=$field['Periodo'];
				if ($codigo==$valor) echo "<option value='".$codigo."' selected>".$codigo."</option>"; 
				else  echo "<option value='".$codigo."'>".$codigo."</option>"; 
			}
			break;
			
		case 6:
			$sql="SELECT Periodo FROM pr_procesoperiodo WHERE CodTipoNom='".$tiponom."' AND CodOrganismo='".$codorganismo."' AND FlagAprobado='S' GROUP BY Periodo ORDER BY Periodo DESC";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				$codigo=$field['Periodo'];
				if ($codigo==$valor) echo "<option value='".$codigo."' selected>".$codigo."</option>"; 
				else  echo "<option value='".$codigo."'>".$codigo."</option>"; 
			}
			break;
	}
}

//	FUNCION PARA MOSTRAR EN UNA TABLA LAS VARIABLES DISPONIBLES
function mostrarVariablesConceptos() {
	$tvalor[0]="\$_SUELDO_BASICO"; $vvalor[0]="\$_SUELDO_BASICO"; $title[0]=("Sueldo básico de un trabajador");
	$tvalor[1]="\$_SUELDO_NORMAL"; $vvalor[1]="\$_SUELDO_NORMAL"; $title[1]=("Total de asignaciones de un trabajador");
	$tvalor[2]="\$_SUELDO_BASICO_DIARIO"; $vvalor[2]="\$_SUELDO_BASICO_DIARIO"; $title[2]=("Sueldo básico diario de un trabajador");
	$tvalor[3]="\$_SUELDO_NORMAL_DIARIO"; $vvalor[3]="\$_SUELDO_NORMAL_DIARIO"; $title[3]=("Total de asignaciones diarias de un trabajador");
	$tvalor[4]="\$_SUELDO_NORMAL_COMPLETO"; $vvalor[4]="\$_SUELDO_NORMAL_COMPLETO"; $title[4]=("Sueldo normal completo del trabajador para el proceso actual");
	$tvalor[5]="\$_DIAS_SUELDO_BASICO"; $vvalor[5]="\$_DIAS_SUELDO_BASICO"; $title[5]=("Dias trabajados para calcular el sueldo basico del trabajador");
	$tvalor[6]="\$_FECHA_INGRESO"; $vvalor[6]="\$_FECHA_INGRESO"; $title[6]=("Fecha de ingreso del trabajador");
	$tvalor[7]="\$_ANO_INGRESO"; $vvalor[7]="\$_ANO_INGRESO"; $title[7]=("Año de ingreso del trabajador");
	$tvalor[8]="\$_MES_INGRESO"; $vvalor[8]="\$_MES_INGRESO"; $title[8]=("Mes de ingreso del trabajador");
	$tvalor[9]="\$_DIA_INGRESO"; $vvalor[9]="\$_DIA_INGRESO"; $title[9]=("Dia de ingreso del trabajador");
	$tvalor[10]="\$_ANO_PROCESO"; $vvalor[10]="\$_ANO_PROCESO"; $title[10]=("Año del proceso");
	$tvalor[11]="\$_MES_PROCESO"; $vvalor[11]="\$_MES_PROCESO"; $title[11]=("Mes del proceso");
	$tvalor[12]="\$_DIA_PROCESO"; $vvalor[12]="\$_DIA_PROCESO"; $title[12]=("Dia del proceso");
	$tvalor[13]="\$_NOMINA"; $vvalor[13]="\$_NOMINA"; $title[13]=("Nomina del trabajador");
	
	$i=0;
	echo "<table width='100%' class='tblLista' onclick='escribirFormula(this.id);'>";
	foreach ($tvalor as $valor) {
		echo "
		<tr class='trListaBody' id='".$vvalor[$i]."' title='".$title[$i]."' onclick='escribirFormula(this.id);'>
			<td>".$valor."</td>
		</tr>";
		$i++;
	}
	echo "</table>";
}

//	FUNCION PARA MOSTRAR EN UNA TABLA LAS VARIABLES DISPONIBLES
function mostrarParametrosConceptos() {
	$sql = "SELECT * FROM mastparametros";
	$query_parametro = mysql_query($sql) or die ($sql.mysql_error()); 
	$i=0;
	echo "<table width='100%' class='tblLista' onclick='escribirFormula(this.id);'>";
	while ($field_parametro = mysql_fetch_array($query_parametro)) {
		$id = $field_parametro['ParametroClave'];
		$_PARAMETRO[$id] = $field_parametro['ValorParam'];
		
		$tvalor[$i]="\$_".$field_parametro['ParametroClave']; $vvalor[$i]="\$_PARAMETROS[\"".$id."\"]"; $title[$i]=($field_parametro['DescripcionParam']);
		
		echo "
		<tr class='trListaBody' id='".$vvalor[$i]."' title='".$title[$i]."' onclick='escribirFormula(this.id);'>
			<td>".$tvalor[$i]."</td>
		</tr>";
		
		$i++;
	}
	echo "</table>";
}

//	FUNCION PARA MOSTRAR EN UNA TABLA LAS VARIABLES DISPONIBLES
function mostrarFuncionesConceptos() {
	$tvalor[0]="SUELDO_MINIMO()"; $vvalor[0]="SUELDO_MINIMO(\$_ARGS)"; $title[0]=("Sueldo mínimo actual");
	$tvalor[1]="DIFERENCIA_SUELDO_BASICO()"; $vvalor[1]="DIFERENCIA_SUELDO_BASICO(\$_ARGS)"; $title[1]=("Diferencia de sueldo básico del trabajador");
	$tvalor[2]="DIAS_SUELDO_BASICO()"; $vvalor[2]="DIAS_SUELDO_BASICO(\$_ARGS)"; $title[2]=("Dias de sueldo básico del trabajador");
	$tvalor[3]="UNIVERSITARIO()"; $vvalor[3]="UNIVERSITARIO(\$_ARGS)"; $title[3]=("Verifica si el trabajador tiene como grado de instrucción universitario");
	$tvalor[4]="TSU()"; $vvalor[4]="TSU(\$_ARGS)"; $title[4]=("Verifica si el trabajador tiene como grado de instrucción T.S.U");
	$tvalor[5]="ANIOS_DE_SERVICIO()"; $vvalor[5]="ANIOS_DE_SERVICIO(\$_ARGS)"; $title[5]=("Años de servicio del trabajador");
	$tvalor[6]="ESPECIALIZACION()"; $vvalor[6]="ESPECIALIZACION(\$_ARGS)"; $title[6]=("Verifica si el trabajador tiene una especialización");
	$tvalor[7]="MAGISTER()"; $vvalor[7]="MAGISTER(\$_ARGS)"; $title[7]=("Verifica si el trabajador tiene un magister");
	$tvalor[8]="DOCTORADO()"; $vvalor[8]="DOCTORADO(\$_ARGS)"; $title[8]=("Verifica si el trabajador tiene un doctorado");
	$tvalor[9]="NUMERO_DE_CURSOS()"; $vvalor[9]="NUMERO_DE_CURSOS(\$_ARGS)"; $title[9]=("Número de cursos del trabajador");
	$tvalor[10]="NUMERO_DE_HIJOS_MENORES()"; $vvalor[10]="NUMERO_DE_HIJOS_MENORES(\$_ARGS)"; $title[10]=("Número de hijos menores de edad");
	$tvalor[11]="DIAS_JERARQUIA()"; $vvalor[11]="DIAS_JERARQUIA(\$_ARGS)"; $title[11]=("Número de dias que ocupo el trabajador un cargo de Jerarquia");
	$tvalor[12]="NUMERO_LUNES()"; $vvalor[12]="NUMERO_LUNES(\$_ARGS)"; $title[12]=("Número de lunes del mes completo");
	$tvalor[13]="ADELANTO_QUINCENA()"; $vvalor[13]="ADELANTO_QUINCENA(\$_ARGS)"; $title[13]=("Monto del adelanto de quincena del trabajador");
	$tvalor[14]="PORCENTAJE_ISLR()"; $vvalor[14]="PORCENTAJE_ISLR(\$_ARGS)"; $title[14]=("Porcentaje del impuesto sobre la renta");
	$tvalor[15]="SALARIO_JUBILACION()"; $vvalor[15]="SALARIO_JUBILACION(\$_ARGS)"; $title[15]=("Salario de jubilación de un trabajador");
	$tvalor[16]="MESES_ANTIGUEDAD()"; $vvalor[16]="MESES_ANTIGUEDAD(\$_ARGS)"; $title[16]=("Meses de antiguedad de un trabajador");
	$tvalor[17]="JEFE_TITULAR()"; $vvalor[17]="JEFE_TITULAR(\$_ARGS)"; $title[17]=("Verifica si el trabajador es jefe titular");
	$tvalor[18]="MESES_POR_DERECHO()"; $vvalor[18]="MESES_POR_DERECHO(\$_ARGS)"; $title[18]=("Devuelve los meses que por derecho le tocan para bonificacion de fin de año del trabajador");
	$tvalor[19]="TIPO_RETENCION()"; $vvalor[19]="TIPO_RETENCION(\$_ARGS)"; $title[19]=("Devuelve el tipo de retencion PORCENTAJE - MONTO");
	$tvalor[20]="RETENCION_JUDICIAL()"; $vvalor[20]="RETENCION_JUDICIAL(\$_ARGS)"; $title[20]=("Devuelve el monto a descontar por retención judicial");
	$tvalor[21]="ULTIMO_SUELDO_NORMAL()"; $vvalor[21]="ULTIMO_SUELDO_NORMAL(\$_ARGS)"; $title[21]=("Devuelve el monto del ultimo sueldo normal del trabajador");
	$tvalor[22]="ULTIMO_SUELDO_NORMAL_DIARIO()"; $vvalor[22]="ULTIMO_SUELDO_NORMAL_DIARIO(\$_ARGS)"; $title[22]=("Devuelve el monto del ultimo sueldo normal diario del trabajador");
	$tvalor[23]="ULTIMA_ALICUOTA_VACACIONAL()"; $vvalor[23]="ULTIMA_ALICUOTA_VACACIONAL(\$_ARGS)"; $title[23]=("Devuelve el monto de la ultima alicuota vacacional");
	$tvalor[24]="SUELDO_NORMAL()"; $vvalor[24]="SUELDO_NORMAL(\$_ARGS)"; $title[24]=("Devuelve el sueldo normal del trabajador para el periodo");
	$tvalor[25]="SUELDO_NORMAL_DIARIO()"; $vvalor[25]="SUELDO_NORMAL_DIARIO(\$_ARGS)"; $title[25]=("Devuelve el sueldo normal diario del trabajador para el periodo");
	$tvalor[26]="NUMERO_LUNES_FECHA()"; $vvalor[26]="NUMERO_LUNES_FECHA(\$_ARGS)"; $title[26]=("Devuelve el numero de lunes trabajados en el mes por trabajador");
	$tvalor[27]="SUELDO_BASICO_NETO()"; $vvalor[27]="SUELDO_BASICO_NETO(\$_ARGS)"; $title[27]=("Devuelve el sueldo basico neto del trabajador");
	$tvalor[28]="DIAS_PROCESO()"; $vvalor[28]="DIAS_PROCESO(\$_ARGS)"; $title[28]=("Devuelve los dias del proceso que se esta ejecutando");
	$tvalor[29]="REMUNERACION_DIARIA()"; $vvalor[29]="REMUNERACION_DIARIA(\$_ARGS)"; $title[29]=("Devuelve la remuneracion diaria del trabajador en el proceso (Sueldo normal + todas las bonificaciones que el trabajador haya percibido en el periodo / 30)");
	$tvalor[30]="NUMERO_DE_HIJOS_MENORES14()"; $vvalor[30]="NUMERO_DE_HIJOS_MENORES14(\$_ARGS)"; $title[30]=("Número de hijos menores de 14 años");
	$tvalor[31]="PENSION_JUBILACION()"; $vvalor[30]="PENSION_JUBILACION(\$_ARGS)"; $title[30]=("Pension Jubilados CEM");
	echo "<table width='98%' class='tblLista'>";
	$i=0;
	foreach ($tvalor as $valor) {
		echo "
		<tr class='trListaBody' id='".$vvalor[$i]."' title='".$title[$i]."' onclick='escribirFormula(this.id);'>
			<td>".$valor."</td>
		</tr>";
		$i++;
	}
	echo "</table>";
}

//	FUNCION PARA MOSTRAR EN UNA TABLA LAS VARIABLES DISPONIBLES
function mostrarConceptosDisponibles($actual) {
	$sql="SELECT CodConcepto, Descripcion, Tipo FROM pr_concepto WHERE CodConcepto<>'".$actual."' ORDER BY Tipo DESC, CodConcepto";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	echo "<table width='98%' class='tblLista'>";
	
	while ($field=mysql_fetch_array($query)) {
		echo "
		<tr class='trListaBody' id='CONCEPTO(\$_ARGS, \"".$field['CodConcepto']."\")' onclick='escribirFormula(this.id);'>
			<td>".$field['CodConcepto']."</td>
			<td>".($field['Descripcion'])."</td>
			<td align='center'>".$field['Tipo']."</td>
		</tr>";
	}
	echo "</table>";
}

//	FUNCION PARA CONFIRMAR LOS PERMISOS DEL USUARIO
function opcionesPermisos($grupo, $concepto) {
	connect();
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


//	FUNCION PARA CARGAR LOS PERIODOS
function getPeriodosTXT($codorganismo) {
	connect();
	$sql="SELECT Periodo FROM pr_procesoperiodo WHERE CodOrganismo='".$codorganismo."' AND Estado='A' AND FlagProcesado='N' AND FlagAprobado='S' GROUP BY Periodo ORDER BY Periodo DESC";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	for ($i=0; $i<$rows; $i++) {
		$field=mysql_fetch_array($query);
		$codigo=$field['Periodo'];
		if ($codigo==$valor) echo "<option value='".$codigo."' selected>".$codigo."</option>"; 
		else  echo "<option value='".$codigo."'>".$codigo."</option>"; 
	}
}

//	FUNCION PARA CARGAR LOS TIPOS DE PROCESO EN UN SELECT
function getProcesosTXT($codorganismo) {
	connect();
	$sql="SELECT ptnp.CodTipoProceso AS Codigo, ptp.Descripcion FROM pr_tiponominaproceso ptnp INNER JOIN pr_tipoproceso ptp ON (ptnp.CodTipoProceso=ptp.CodTipoProceso) GROUP BY Codigo ORDER BY ptnp.CodTipoProceso";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	for ($i=0; $i<$rows; $i++) {
		$field=mysql_fetch_array($query);
		if ($field['Codigo']==$codigo) echo "<option value='".$field['Codigo']."' selected>".($field['Descripcion'])."</option>"; 
		else echo "<option value='".$field['Codigo']."'>".($field['Descripcion'])."</option>";
	}
}

//
function formatFechaDMA($fecha) {
	list($a, $m, $d)=SPLIT( '[/.-]', $fecha);
	if ($a == "0000" || $a == "") return "";
	else return "$d-$m-$a";
}

//
function formatFechaAMD($fecha) {
	list($d, $m, $a)=SPLIT( '[/.-]', $fecha);
	if ($a == "0000" || $a == "") return "";
	else return "$a-$m-$d";
}

//	obtengo el sueldo normal por periodo de un empleado
function consultarSueldoNormal($anio, $empleado) {
	$sql = "SELECT 
				tne.Periodo, 
				tne.TotalIngresos 
			FROM pr_tiponominaempleado tne
			WHERE
				tne.CodTipoProceso = 'FIN' AND
				tne.CodPersona = '".$empleado."' AND
				SUBSTRING(tne.Periodo, 1, 4) = '".$anio."'
			ORDER BY Periodo";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$sueldos = array();
	while ($field = mysql_fetch_array($query)) {
	  $sueldos[] = $field;
	}
	return $sueldos;
}

//	obtengo las bonificaciones del empleado
function consultarBonosRemuneracionesAnual($anio, $empleado) {
	$sql = "(SELECT
				c.Codconcepto,
				c.Descripcion,
				SUM(tnec.Monto) AS Monto,
				'1' As Orden
			FROM
				pr_tiponominaempleadoconcepto tnec
				INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto)
				INNER JOIN pr_procesoperiodo pp ON (tnec.CodTipoProceso = pp.CodtipoProceso AND
													tnec.CodTipoNom = pp.CodTipoNom AND
													tnec.Periodo = pp.Periodo)
			WHERE
				tnec.CodPersona = '".$empleado."' AND
				SUBSTRING(tnec.Periodo, 1, 4) = '".$anio."' AND
				c.Tipo = 'I' AND
				c.FlagBono <> 'S' AND
				pp.FlagPagado = 'S' AND
				tnec.CodTipoProceso = 'FIN' AND
				tnec.CodConcepto <> '0001'
			GROUP BY c.CodConcepto)
			
			UNION
			
			(SELECT
				c.Codconcepto,
				c.Descripcion,
				SUM(tnec.Monto) AS Monto,
				'2' AS Orden
			FROM
				pr_tiponominaempleadoconcepto tnec
				INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto)
				INNER JOIN pr_procesoperiodo pp ON (tnec.CodTipoProceso = pp.CodtipoProceso AND
													tnec.CodTipoNom = pp.CodTipoNom AND
													tnec.Periodo = pp.Periodo)
			WHERE
				tnec.CodPersona = '".$empleado."' AND
				SUBSTRING(tnec.Periodo, 1, 4) = '".$anio."' AND
				c.Tipo = 'I' AND
				c.FlagBono = 'S' AND
				pp.FlagPagado = 'S'
			GROUP BY c.CodConcepto)
			
			ORDER BY Orden, CodConcepto";
			
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$sueldos = array();
	while ($field = mysql_fetch_array($query)) {
	  $sueldos[] = $field;
	}
	return $sueldos;
}

//	obtengo las bonificaciones del empleado
function consultarConceptoIngresosAnual($anio, $empleado) {
	$sql = "SELECT
				c.Descripcion,
				SUM(tnec.Monto) AS Monto
			FROM
				pr_tiponominaempleadoconcepto tnec
				INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto)
				INNER JOIN pr_procesoperiodo pp ON (tnec.CodTipoProceso = pp.CodtipoProceso AND
													tnec.CodTipoNom = pp.CodTipoNom AND
													tnec.Periodo = pp.Periodo)
			WHERE
				tnec.CodPersona = '".$empleado."' AND
				SUBSTRING(tnec.Periodo, 1, 4) = '".$anio."' AND
				c.Tipo = 'I' AND
				c.FlagBono <> 'S' AND
				pp.FlagPagado = 'S' AND
				tnec.CodTipoProceso = 'FIN'
			GROUP BY c.CodConcepto";
			
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$sueldos = array();
	while ($field = mysql_fetch_array($query)) {
	  $sueldos[] = $field;
	}
	return $sueldos;
}

//	obtengo las bonificaciones del empleado
function consultarConceptoBonificacionAnual($anio, $empleado) {
	$sql = "SELECT
				c.Descripcion,
				SUM(tnec.Monto) AS Monto,
				tnec.Periodo
			FROM
				pr_tiponominaempleadoconcepto tnec
				INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto)
				INNER JOIN pr_procesoperiodo pp ON (tnec.CodTipoProceso = pp.CodtipoProceso AND
													tnec.CodTipoNom = pp.CodTipoNom AND
													tnec.Periodo = pp.Periodo)
			WHERE
				tnec.CodPersona = '".$empleado."' AND
				SUBSTRING(pp.FechaPago, 1, 4) = '".$anio."' AND
				c.Tipo = 'I' AND
				c.FlagBono = 'S' AND
				pp.FlagPagado = 'S'
			GROUP BY c.CodConcepto, tnec.Periodo";
			
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$sueldos = array();
	while ($field = mysql_fetch_array($query)) {
	  $sueldos[] = $field;
	}
	return $sueldos;
}

//	obtengo las bonificaciones del empleado
function consultarBonificacionAnual($anio, $empleado) {
	$sql = "SELECT
				c.Descripcion,
				(SELECT TotalNeto
				 FROM pr_tiponominaempleado
				 WHERE
				 	CodTipoNom = tnec.CodTipoNom AND
					Periodo = tnec.Periodo AND
					CodPersona = tnec.CodPersona AND
					CodOrganismo = tnec.CodOrganismo AND
					CodTipoProceso = tnec.CodTipoProceso) AS Monto
			FROM
				pr_tiponominaempleadoconcepto tnec
				INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto)
				INNER JOIN pr_conceptoproceso cp ON (c.CodConcepto = cp.CodConcepto)
				INNER JOIN pr_procesoperiodo pp ON (cp.CodTipoProceso = pp.CodTipoProceso)
			WHERE
				tnec.CodPersona = '".$empleado."' AND
				SUBSTRING(tnec.Periodo, 1, 4) = '".$anio."' AND
				c.FlagBono = 'S' AND
				pp.FlagPagado = 'S'
			GROUP BY c.CodConcepto
			ORDER BY pp.Periodo";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$sueldos = array();
	while ($field = mysql_fetch_array($query)) {
	  $sueldos[] = $field;
	}
	return $sueldos;
}

//	obtengo las retenciones del empleado
function consultarConceptoRetencionAnual($anio, $empleado) {
	$sql = "SELECT
				c.Abreviatura,
				c.Descripcion,
				SUM(tnec.Monto) AS Monto
			FROM
				pr_tiponominaempleadoconcepto tnec
				INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto)
			WHERE
				tnec.CodPersona = '".$empleado."' AND
				SUBSTRING(tnec.Periodo, 1, 4) = '".$anio."' AND
				c.Tipo = 'D' AND
				c.FlagRelacionIngreso = 'S' AND
				c.CodConcepto <> '0017'
			GROUP BY c.CodConcepto";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$sueldos = array();
	while ($field = mysql_fetch_array($query)) {
	  $sueldos[] = $field;
	}
	return $sueldos;
}

//	obtengo las retenciones del empleado
function consultarRetencionAnual($anio, $empleado) {
	$sql = "SELECT
				c.Abreviatura,
				c.Descripcion,
				SUM(tnec.Monto) AS Monto
			FROM
				pr_tiponominaempleadoconcepto tnec
				INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto)
			WHERE
				tnec.CodPersona = '".$empleado."' AND
				SUBSTRING(tnec.Periodo, 1, 4) = '".$anio."' AND
				c.FlagRetencion = 'S'
			GROUP BY c.CodConcepto";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$sueldos = array();
	while ($field = mysql_fetch_array($query)) {
	  $sueldos[] = $field;
	}
	return $sueldos;
}

//	obtengo los periodos cancelados a un empleado
function getPeriodosCancelados($anio, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql = "SELECT SUBSTRING(Periodo, 1, 4) AS Anio 
					FROM pr_tiponominaempleado 
					GROUP BY Anio 
					ORDER BY Anio";
			$query = mysql_query($sql) or die ($sql.mysql_error());
			$rows = mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field = mysql_fetch_array($query);
				if ($field['Anio'] == $anio) echo "<option value='".$field['Anio']."' selected>".$field['Anio']."</option>"; 
				else echo "<option value='".$field['Anio']."'>".$field['Anio']."</option>";
			}
			break;
		case 1:
			$sql = "SELECT SUBSTRING(Periodo, 1, 4) AS Anio 
					FROM pr_tiponominaempleado 
					WHERE SUBSTRING(Periodo, 1, 4) = '".$anio."'
					GROUP BY Anio 
					ORDER BY Anio";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field['Anio']."'>".$field['Anio']."</option>";
			}
			break;
	}
}

//	FUNCION PARA IMPRIMIR UN CHECK
function printFlag($check) {
	if ($check == "S") $flag = "<img src='../imagenes/flag.png' />";
	return $flag;
}


//	FUNCION PARA IMPRIMIR EN UNA TABLA VALORES
function printValores($tabla, $codigo) {
	switch ($tabla) {
		case "ESTADO":
			$c[0] = "A"; $v[0] = "Activo";
			$c[1] = "I"; $v[1] = "Inactivo";
			break;
		
		case "ESTADO-AJUSTE":
			$c[0] = "PR"; $v[0] = "En Preparación";
			$c[1] = "AP"; $v[1] = "Aprobado";
			$c[2] = "AN"; $v[2] = "Anulado";
			break;
	}
	
	$i=0;
	foreach ($c as $cod) {
		if ($cod == $codigo) return ($v[$i]);
		$i++;
	}
}


//	FUNCION PARA CARGAR SELECTS 
function loadSelectValores($tabla, $codigo) {
	switch ($tabla) {
		case "ORDENAR-PROCESOS-LISTADO":
			$c[0] = "length(Ndocumento), Ndocumento"; $v[0] = "Nro. Documento";
			$c[1] = "NomCompleto"; $v[1] = "Nombre Completo";
			$c[2] = "Grado, length(Ndocumento), Ndocumento"; $v[2] = "Grado Salarial";
			$c[3] = "DescripCargo, length(Ndocumento), Ndocumento"; $v[3] = "Cargo";
			$c[4] = "TotalIngresos"; $v[4] = "Monto";
			break;
	}
	
	$i = 0;
	foreach ($c as $cod) {
		if ($cod == $codigo) echo "<option value='".$cod."' selected>".$v[$i]."</option>";
		else echo "<option value='".$cod."'>".$v[$i]."</option>";
		$i++;
	}
}

//	funcion para verificar disponibiliadad presupuestaria de las cotizaciones
function verificarDisponibilidadPresupuestariaObligacion($anio, $organismo, $codproveedor, $codtipodocumento, $nrodocumento) {
	$_PARAMETRO = getParametro("IVADEFAULT");
	$disponible = true;
	
	//	obtengo impuesto
	$sql = "SELECT MontoImpuestoOtros
			FROM pr_obligaciones
			WHERE
				CodProveedor = '".$codproveedor."' AND
				CodTipoDocumento = '".$codtipodocumento."' AND
				NroDocumento = '".$nrodocumento."'";
	$query_impuesto = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query_impuesto) != 0) $field_impuesto = mysql_fetch_array($query_impuesto);
	
	//	obtengo la distribucion insertada por el usuario para la obligacion
	$sql = "SELECT
				Monto,
				cod_partida
			FROM pr_obligacionescuenta
			WHERE
				CodProveedor = '".$codproveedor."' AND
				CodTipoDocumento = '".$codtipodocumento."' AND
				NroDocumento = '".$nrodocumento."'";
	$query = mysql_query($sql) or die($sql.mysql_error());
	while ($field = mysql_fetch_array($query)) {
		$partida = $field['cod_partida'];
		$partida_obligacion[$partida] += $field['Monto'];
		$codpartida_obligacion[$partida] = $field['cod_partida'];
	}
	$partida = $_PARAMETRO['IVADEFAULT'];
	$partida_obligacion[$partida] += $field_impuesto['MontoImpuestoOtros'];
	$codpartida_obligacion[$partida] = $partida;
	
	//	obtengo la disponibilidad actual de cada una de las partidas de la obligacion
	foreach ($codpartida_obligacion as $partida) {
		if ($partida != "") {
			$sql = "SELECT (pvpd.MontoAjustado - pvpd.MontoCompromiso) AS MontoDisponible
					FROM
						pv_presupuesto pvp
						INNER JOIN pv_presupuestodet pvpd ON (pvp.Organismo = pvpd.Organismo AND 
															  pvp.CodPresupuesto = pvpd.CodPresupuesto)
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
?>
