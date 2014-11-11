<?php 
include ("fphp.php");
include ("../funciones.php");
//	--------------------------
if ($accion=="FECHA_FIN") getFechaFin($fecha, $dias); 

elseif ($accion=="MOSTRAR-CATEGORIA-SUELDO") {
	connect();
	$sql="SELECT rp.NivelSalarial, mmd.Descripcion AS TipoTrabajador FROM rh_puestos rp INNER JOIN mastmiscelaneosdet mmd ON (rp.CategoriaCargo=mmd.CodDetalle AND mmd.CodMaestro='CATCARGO') WHERE rp.CodCargo='".$codcargo."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query)!=0) {
		$field=mysql_fetch_array($query);
		echo "0|:|".$field["TipoTrabajador"]."|:|".number_format($field['NivelSalarial'], 2, ',', '.');
	}
}

elseif ($accion=="SET-TIPO-CONTRATOS") {
	connect();
	$sql="SELECT rtc.FlagVencimiento, rfc.CodFormato, rfc.Documento FROM rh_tipocontrato rtc INNER JOIN rh_formatocontrato rfc ON (rtc.TipoContrato=rfc.TipoContrato) WHERE rtc.TipoContrato='".$tipo."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query)!=0) {
		$field=mysql_fetch_array($query);
		echo "0|:|".$field["FlagVencimiento"]."|:|".$field["CodFormato"]."|:|".htmlentities($field["Documento"]);
	}
}
elseif ($accion=="GET-TOTAL-DIAS-PERMISOS") {
	connect();
	// Obtengo los dias de permiso...
	$dias_permiso = getDiasPermiso($fdesde, $fhasta);
	$dias_feriado = getDiasFeriados($fdesde, $fhasta);
	$dias_permiso = $dias_permiso - $dias_feriado;
	
	if ($dias_permiso == 0) {
		echo "|:||:||:||:||:|";
	} else {
		//	Obtengo las horas diarias...
		list($hd, $md) = SPLIT( '[:.:]', $hdesde);
		list($hh, $mh) = SPLIT( '[:.:]', $hhasta);
		$hd = (int) $hd;
		$hh = (int) $hh;
		$md = (int) $md;
		$mh = (int) $mh;
		if ($turnodesde == "PM" && $hd != 12) $hd += 12;
		if ($turnohasta == "PM" && $hh != 12) $hh += 12;
		if ($turnodesde == "AM" && $hd == 12) $hd = 0;
		if ($turnohasta == "AM" && $hh == 12) $hh = 0;
		
		$horas = $hh - $hd;
		
		// Obtengo los minutos diarios...
		if ($mh >= $md) $minutos = ($mh - $md); else $minutos = ($mh - $md) + 60;
		if ($md > $mh) $horas--;
			
		//	Totales...
		$total_minutos = $minutos * $dias_permiso; 
		if ($total_minutos >= 60) $minutos_a_horas = (int) ($total_minutos / 60);
		$total_minutos = $total_minutos - ($minutos_a_horas * 60);
		
		$total_horas = ($horas * $dias_permiso) + $minutos_a_horas;
		
		$thora = "$horas:$minutos";
		$tfecha = $dias_permiso;
		
		if ($fdesde == $fhasta) { $dias_permiso = ""; $tfecha = ""; } 
		if ($horas == 0 && $minutos == 0) { $total_horas = ""; $total_minutos = ""; $thora = ""; }	
		
		//	Cambiar esta linea en funcion de las horas diarias de trabajo... 5 o 7 horas dependiendo
		if ($dias_permiso == "" && $horas >= 7) { $dias_permiso = 1; $tfecha = 1; }
		  
		echo "$dias_permiso|:|$total_horas|:|$total_minutos|:|$tfecha|:|$thora";
	}
}
?>