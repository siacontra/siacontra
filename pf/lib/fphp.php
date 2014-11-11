<?php
session_start();
set_time_limit(-1);
ini_set('memory_limit','128M');

//	FUNCION PARA CARGAR SELECTS 
function loadSelectValores($tabla, $codigo, $opt) {
	switch ($tabla) {
		case "ORDENAR-PROCESOS":
			$c[0] = "p.CodProceso"; $v[0] = "C&oacute;digo";
			$c[1] = "p.Descripcion"; $v[1] = "Descripci&oacute;n";
			$c[2] = "p.Estado"; $v[2] = "Estado";
			break;
			
		case "ORDENAR-FASES":
			$c[0] = "f.CodFase"; $v[0] = "C&oacute;digo";
			$c[1] = "f.Descripcion"; $v[1] = "Descripci&oacute;n";
			$c[2] = "f.Estado"; $v[2] = "Estado";
			break;
			
		case "ORDENAR-TIPOACTUACION":
			$c[0] = "taf.CodTipoActuacion"; $v[0] = "C&oacute;digo";
			$c[1] = "taf.Descripcion"; $v[1] = "Descripci&oacute;n";
			$c[2] = "taf.Estado"; $v[2] = "Estado";
			break;
			
		case "ESTADO-ACTUACION-DETALLE":
			$c[0] = "PE"; $v[0] = "Pendiente";
			$c[1] = "EJ"; $v[1] = "En Ejecución";
			$c[2] = "AN"; $v[2] = "Anulada";
			$c[3] = "TE"; $v[3] = "Terminada";
			$c[4] = "CE"; $v[4] = "Cerrada";
			break;
			
		case "ORDENAR-ACTUACION-DETALLE":
			$c[0] = "a.CodFase, af.Anio, af.Secuencia, af.CodActuacion"; $v[0] = "C&oacute;digo";
			$c[1] = "oe.Organismo, de.Dependencia"; $v[1] = "Ente Externo";
			$c[2] = "a.Descripcion"; $v[2] = "Actividad";
			break;
			
		case "ESTADO-ACTUACION-PRORROGA":
			$c[0] = "PR"; $v[0] = "En Preparación";
			$c[1] = "RV"; $v[1] = "Revisada";
			$c[2] = "AP"; $v[2] = "Aprobada";
			$c[3] = "AN"; $v[3] = "Anulada";
			break;
			
		case "ORDENAR-ACTUACION-PRORROGA":
			$c[0] = "p.CodProrroga"; $v[0] = "Prorroga";
			$c[1] = "p.Motivo"; $v[1] = "Motivo";
			$c[2] = "a.Descripcion"; $v[2] = "Actividad";
			break;
			
		case "ORDENAR-VALORACION-DETALLE":
			$c[0] = "a.CodFase, vj.Anio, vj.Secuencia, vj.CodValJur"; $v[0] = "C&oacute;digo";
			$c[1] = "oe.Organismo, de.Dependencia"; $v[1] = "Ente Externo";
			$c[2] = "a.Descripcion"; $v[2] = "Actividad";
			break;
			
		case "ORDENAR-POTESTAD-DETALLE":
			$c[0] = "a.CodFase, vj.Anio, vj.Secuencia, vj.CodPotestad"; $v[0] = "C&oacute;digo";
			$c[1] = "oe.Organismo, de.Dependencia"; $v[1] = "Ente Externo";
			$c[2] = "a.Descripcion"; $v[2] = "Actividad";
			break;
			
		case "ORDENAR-DETERMINACION-DETALLE":
			$c[0] = "a.CodFase, vj.Anio, vj.Secuencia, vj.CodDeterminacion"; $v[0] = "C&oacute;digo";
			$c[1] = "oe.Organismo, de.Dependencia"; $v[1] = "Ente Externo";
			$c[2] = "a.Descripcion"; $v[2] = "Actividad";
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
		case "ESTADO-ACTUACION-DETALLE":
			$c[0] = "PE"; $v[0] = "Pendiente";
			$c[1] = "EJ"; $v[1] = "En Ejecución";
			$c[2] = "AN"; $v[2] = "Anulada";
			$c[3] = "TE"; $v[3] = "Terminada";
			$c[4] = "CE"; $v[4] = "Cerrada";
			break;
			
		case "ESTADO-ACTUACION-PRORROGA":
			$c[0] = "PR"; $v[0] = "En Preparación";
			$c[1] = "RV"; $v[1] = "Revisada";
			$c[2] = "AP"; $v[2] = "Aprobada";
			$c[3] = "AN"; $v[3] = "Anulada";
			break;
	}
	
	$i=0;
	foreach ($c as $cod) {
		if ($cod == $codigo) return $v[$i];
		$i++;
	}
}

//	
function getDescripcionActividad($CodActividad) {
	$sql = "SELECT Descripcion FROM pf_actividades WHERE CodActividad = '".$CodActividad."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	return $field['Descripcion'];
}
?>