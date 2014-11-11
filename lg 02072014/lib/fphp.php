<?php
session_start();
set_time_limit(-1);
ini_set('memory_limit','128M');

//	FUNCION PARA CARGAR SELECTS 
function loadSelectValores($tabla, $codigo, $opt) {
	switch ($tabla) {
		case "ESTADO-REQUERIMIENTO":
			$c[0] = "PR"; $v[0] = "En Preparación";
			$c[1] = "RV"; $v[1] = "Revisado";
			$c[2] = "CN"; $v[2] = "Conformado";
			$c[3] = "AP"; $v[3] = "Aprobado";
			$c[4] = "AN"; $v[4] = "Anulado";
			$c[5] = "RE"; $v[5] = "Rechazado";
			$c[6] = "CE"; $v[6] = "Cerrado";
			$c[7] = "CO"; $v[7] = "Completado";
			break;
		
		case "ESTADO-REQUERIMIENTO-DETALLE":
			$c[0] = "PR"; $v[0] = "En Preparación";
			$c[1] = "PE"; $v[1] = "Pendiente";
			$c[2] = "AN"; $v[2] = "Anulado";
			$c[3] = "RE"; $v[3] = "Rechazado";
			$c[4] = "CE"; $v[4] = "Cerrado";
			$c[5] = "CO"; $v[5] = "Completado";
			break;
			
		case "ESTADO-TRANSACCION":
			$c[0] = "PR"; $v[0] = "Pendiente";
			$c[1] = "CO"; $v[1] = "Ejecutado";
			break;
			
		case "COMPRA-CLASIFICACION":
			$c[0] = "L"; $v[0] = "O/C Local";
			$c[1] = "F"; $v[1] = "O/C Foráneo";
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
		case "ESTADO-REQUERIMIENTO":
			$c[0] = "PR"; $v[0] = "En Preparación";
			$c[1] = "RV"; $v[1] = "Revisado";
			$c[2] = "CN"; $v[2] = "Conformado";
			$c[3] = "AP"; $v[3] = "Aprobado";
			$c[4] = "AN"; $v[4] = "Anulado";
			$c[5] = "RE"; $v[5] = "Rechazado";
			$c[6] = "CE"; $v[6] = "Cerrado";
			$c[7] = "CO"; $v[7] = "Completado";
			break;
		
		case "ESTADO-REQUERIMIENTO-DETALLE":
			$c[0] = "PR"; $v[0] = "En Preparación";
			$c[1] = "PE"; $v[1] = "Pendiente";
			$c[2] = "AN"; $v[2] = "Anulado";
			$c[3] = "RE"; $v[3] = "Rechazado";
			$c[4] = "CE"; $v[4] = "Cerrado";
			$c[5] = "CO"; $v[5] = "Completado";
			break;
			
		case "ESTADO-TRANSACCION":
			$c[0] = "PR"; $v[0] = "Pendiente";
			$c[1] = "CO"; $v[1] = "Ejecutado";
			break;
		
		case "DIRIGIDO":
			$c[0] = "C"; $v[0] = "Compras";
			$c[1] = "A"; $v[1] = "Almacen";
			break;
		
		case "COMPRA-CLASIFICACION":
			$c[0] = "L"; $v[0] = "O/C Local";
			$c[1] = "F"; $v[1] = "O/C Foráneo";
			break;
	}
	
	$i=0;
	foreach ($c as $cod) {
		if ($cod == $codigo) return $v[$i];
		$i++;
	}
}

//	FUNCION PARA CARGAR SELECTS
function loadSelectClasificacion($codigo, $opt) {
	switch ($opt) {
		case 0:
			$sql = "SELECT Clasificacion, Descripcion FROM lg_clasificacion WHERE Clasificacion <> 'RAU' ORDER BY Clasificacion";
			$query = mysql_query($sql) or die ($sql.mysql_error());
			while ($field = mysql_fetch_array($query)) {
				if ($field[0] == $codigo) { ?><option value="<?=$field[0]?>" selected="selected"><?=($field[1])?></option><? }
				else { ?><option value="<?=$field[0]?>"><?=($field[1])?></option><? }
			}
			break;
			
		case 1:
			$sql = "SELECT Clasificacion, Descripcion FROM lg_clasificacion WHERE Clasificacion = '".$codigo."' ORDER BY Clasificacion";
			$query = mysql_query($sql) or die ($sql.mysql_error());
			while ($field = mysql_fetch_array($query)) {
				?><option value="<?=$field[0]?>" selected="selected"><?=($field[1])?></option><?
			}
			break;
	}
}

//	obtengo el almacen de la clasificacion seleccionada
function setAlmacenFromClasificacion($Clasificacion) {
	$sql = "SELECT CodAlmacen FROM lg_clasificacion WHERE Clasificacion = '".$Clasificacion."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	return $field['CodAlmacen'];
}
?>