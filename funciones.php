<?php
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

//	-------------------------------
function getFechaFin($fecha, $dias) {
	$sumar=true;
	$dia_semana=getDiaSemana($fecha);	
	list($dia, $mes, $anio)=SPLIT('[/.-]', $fecha);
	$d=(int) $dia; $m=(int) $mes; $a=(int) $anio;
	for ($i=1; $i<=$dias;) {
		$dia_semana++;
		if ($dia_semana==8) $dia_semana=1;
		if ($dia_semana>=1 && $dia_semana<=5) $i++; 
		$d++;
		$dias_mes=getDiasMes($a, $m);
		if ($d>$dias_mes) { 
			$d=1; $m++; 
			if ($m>12) { $m=1; $a++; }
		}
	}
	if ($d<10) $d="0$d";
	if ($m<10) $m="0$m";
	echo "$d-$m-$a";
}
//	-------------------------------

//	-------------------------------
function fechaFin($fecha, $dias) {
	$sumar=true;
	$dia_semana=getDiaSemana($fecha);
	list($dia, $mes, $anio)=SPLIT('[/.-]', $fecha);
	$d=(int) $dia; $m=(int) $mes; $a=(int) $anio;
	for ($i=1; $i<=$dias;) {
		$dia_semana++;
		if ($dia_semana==8) $dia_semana=1;
		if ($dia_semana>=1 && $dia_semana<=5) $i++;
		$d++;
		$dias_mes=getDiasMes($a, $m);
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
function getFechaFinContinuo($fecha, $dias) {
	$sumar = true;
	list($dia, $mes, $anio)=SPLIT('[/.-]', $fecha);
	$d=(int) $dia; $m=(int) $mes; $a=(int) $anio;
	
	for ($i=1; $i<=$dias; $i++) {
		$d++;
		$dias_mes = getDiasMes($a, $m);
		if ($d > $dias_mes) { 
			$d = 1; $m++; 
			if ($m > 12) { $m = 1; $a++; }
		}
	}
	if ($d<10) $d="0$d";
	if ($m<10) $m="0$m";
	return "$d-$m-$a";
}
//	-------------------------------

//	-------------------------------
function fechaFinHabiles($fecha, $dias) {
	if ($dias==1 || $dias==0) $dias=0; else $dias--;
	$sumar=true;
	$dia_semana=getDiaSemana($fecha);	//echo "<tr><td colspan='9'>$dia_semana=getDiaSemana($fecha);</td></tr>";
	list($dia, $mes, $anio)=SPLIT('[/.-]', $fecha);
	$d=(int) $dia; $m=(int) $mes; $a=(int) $anio;
	
	for ($i=1; $i<=$dias;) {
		$dia_semana++;
		if ($dia_semana==8) $dia_semana=1;
		if ($dia_semana>=1 && $dia_semana<=5) $i++;
		$d++;
		$dias_mes=getDiasMes($a, $m);
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
function getFechaFinHabiles($fecha, $dias) {
	$finicio = $fecha;
	$ffin = fechaFinHabiles($finicio, $dias);	//echo "<tr><td colspan='9'>.$ffin = fechaFinHabiles($finicio, $dias);</td></tr>";
	$dias_feriados = 0;
	do {
		$feriados = getDiasFeriados($finicio, $ffin) - $dias_feriados;	//echo "<tr><td colspan='9'>$feriados = getDiasFeriados($finicio, $ffin);</td></tr>";
		$dias += $feriados;	//echo "<tr><td colspan='9'>$dias += $feriados;</td></tr>";		
		$ffin = fechaFinHabiles($finicio, $dias);	//echo "<tr><td colspan='9'>$ffin = fechaFinHabiles($finicio, $dias);.</td></tr>";		
		$dias_feriados += $feriados;
	} while ($feriados > 0);
	return $ffin;
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
function getDiasPermiso($fdesde, $fhasta) {
	list($dd, $mm, $ad)=SPLIT('[/.-]', $fdesde); $dd = (int) $md; $md = (int) $dd; $ad = (int) $ad;
	$dias_completos = getFechaDias($fdesde, $fhasta);
	$dia_semana = getDiaSemana($fdesde);
	$dias_permiso = 0;
	
	for ($i=0; $i<=$dias_completos; $i++) {
		if ($dia_semana == 7) $dia_semana = 0;
		if ($dia_semana >= 1 && $dia_semana <= 5) $dias_permiso++;
		$dia_semana++;
	}
	return $dias_permiso;
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
	$query = mysql_query($sql) or die ($sql.mysql_error());
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
/*
function weekday($fecha){
	$fecha = str_replace("/","-",$fecha);
	list($dia, $mes, $anio) = explode("-",$fecha);
	return (((mktime ( 0, 0, 0, $mes, $dia, $anio) - mktime ( 0, 0, 0, 7, 17, 2006))/(60*60*24))+700000) % 7;
}
*/
//	-------------------------------

//	-------------------------------
function getDiasMes($anio, $mes) {
	$dias_mes[1]=31;
	if ($anio%4==0) $dias_mes[2]=29; else $dias_mes[2]=28;
	$dias_mes[3]=31;
	$dias_mes[4]=30;
	$dias_mes[5]=31;
	$dias_mes[6]=30;
	$dias_mes[7]=31;
	$dias_mes[8]=31;
	$dias_mes[9]=30;
	$dias_mes[10]=31;
	$dias_mes[11]=30;
	$dias_mes[12]=31;
	return $dias_mes[$mes];
}
//	-------------------------------

//	-------------------------------
function getNombreMes($mes) {
	$nombre[1]="Enero";
	$nombre[2]="Febrero";
	$nombre[3]="Marzo";
	$nombre[4]="Abril";
	$nombre[5]="Mayo";
	$nombre[6]="Junio";
	$nombre[7]="Julio";
	$nombre[8]="Agosto";
	$nombre[9]="Septiembre";
	$nombre[10]="Octubre";
	$nombre[11]="Noviembre";
	$nombre[12]="Diciembre";
	return $nombre[$mes];
}
//	-------------------------------

//	-------------------------------
function getFechaDias($fechad, $fechah) {
	list($dd, $md, $ad) = SPLIT( '[/.-]', $fechad);	$desde = "$ad-$md-$dd";
	list($dh, $mh, $ah) = SPLIT( '[/.-]', $fechah);	$hasta = "$ah-$mh-$dh";
	
	$sql = "SELECT DATEDIFF('$hasta', '$desde');";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$field = mysql_fetch_array($query);
	return $field[0];
}
//	---------------------------------

//	---------------------------------
//	FUNCION PARA OBTENER LA EDAD DE UNA FECHA INGRESADA
function getAnios($fecha) {
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
		$annio = (int) ($a);
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
//	---------------------------------

//	---------------------------------
//	FUNCION PARA OBTENER LA EDAD DE UNA FECHA INGRESADA
function getEdadAMD($fecha, $fechaActual) {
	$error=0;
	$listo=0;	
	if ((strlen($fecha))<10) $error=1;
	else {
		list($d, $m, $a)=SPLIT( '[/.-]', $fechaActual);
		$diaActual = $d;
		$mesActual = $m;
		$annioActual = $a;
		//
		list($d, $m, $a)=SPLIT( '[/.-]', $fecha);
		$dia = (int) ($d);
		$mes = (int) ($m);
		$annio = (int) ($a);
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
//	------------------------------------

//	------------------------------------
function getFormula($_CODCONCEPTO) {
	$sql="SELECT Formula FROM pr_concepto WHERE CodConcepto='".$_CODCONCEPTO."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query)!=0) $field=mysql_fetch_array($query);
	return $field['Formula'];
}

function _CONCEPTO($_CODPERSONA, $_CODCONCEPTO) {
	//	Consulto los parametros
	$sql = "SELECT * FROM mastparametros";
	$query_parametro = mysql_query($sql) or die ($sql.mysql_error());
	while ($field_parametro = mysql_fetch_array($query_parametro)) {
		$id = $field_parametro['ParametroClave'];
		$_PARAMETRO[$id] = $field_parametro['ValorParam'];
	}
	//	------------------------
	//	Variables usadas en la formula....
	$_DIAS_BONO_VAC = $_PARAMETRO['PAGOVACA'];
	$_DIAS_BONO_FIN = $_PARAMETRO['PAGOFIN'];
	$_DIAS_ANTIGUEDAD = $_PARAMETRO['DIASANTIG'];
	$_SUELDO_MIN = $_PARAMETRO['SUELDOMIN'];
	$_PROCESO = $_SESSION['_PROCESO'];
	$_PERIODO = $_SESSION['_PERIODO'];
	$_NOMINA = $_SESSION['_NOMINA'];
	$_ADELANTO = esAdelantoQuincena($_PROCESO);
	if ($_ADELANTO == "N") {
		$_MONTO_ADELANTO = getMontoAdelanto($_CODPERSONA, $_PERIODO);
		$_MONTO_ADELANTO_CONCEPTO = getMontoAdelantoConcepto($_CODPERSONA, $_PERIODO, $_CODCONCEPTO);
	} else {
		$_MONTO_ADELANTO = 0;
		$_MONTO_ADELANTO_CONCEPTO = 0;
	}
	$_FINGRESO = getFechaIngreso($_CODPERSONA);
	$_FINGRESO_ENCARGADURIA = getFechaIngresoEncargaduria($_CODPERSONA);
	
	list($diai, $mesi, $anioi)=SPLIT( '[-.-]', $_FINGRESO); $fingreso = "$anioi-$mesi-$diai";
	list($anio, $mes)=SPLIT( '[-.-]', $_PERIODO);
	$_FADELANTO = "15-$mes-$anio"; $fadelanto = "$anio-$mes-15";
	$_FFIN = "30-$mes-$anio"; $ffin = "$anio-$mes-30";
	
	$_ESTADO = estadoTrabajador($_CODPERSONA);
	$_FCESE = getFechaCese($_CODPERSONA);
	list($diac, $mesc, $anioc)=SPLIT( '[-.-]', $_FCESE); $fcese = "$anioc-$mesc-$diac"; $pcese = "$anioc-$mesc";
	if ($_ESTADO == "A") {
		if ($fadelanto >= $fingreso) {
			$_DIAS_SUELDO_ADELANTO = getFechaDias($_FINGRESO, $_FADELANTO) + 1;
			if ($_DIAS_SUELDO_ADELANTO >= 15) $_DIAS_SUELDO_ADELANTO = 15;
		} else $_DIAS_SUELDO_ADELANTO = 0;
		
		list ($_ANIO_SUELDO_MES, $_MES_SUELDO_MES, $_DIAS_SUELDO_MES) = getEdadAMD($_FINGRESO, $_FFIN);
		$_DIAS_SUELDO_MES++;
		//$_DIAS_SUELDO_MES = getFechaDias($_FINGRESO, $_FFIN) + 1;
		if ($_DIAS_SUELDO_MES >= 30 || $_MES_SUELDO_MES >= 1 || $_ANIO_SUELDO_MES > 1) $_DIAS_SUELDO_MES = 30;
	} else {
		if ($pcese >= $_PERIODO) {
			list($d, $m, $a)=SPLIT( '[-.-]', $_FCESE); $dias = (int) $d; $dias--;
			if ($fcese > $fadelanto) $_DIAS_SUELDO_ADELANTO = $dias;
			else $_DIAS_SUELDO_ADELANTO = $dias;
			$_DIAS_SUELDO_MES = $dias;
		} else {
			$_DIAS_SUELDO_ADELANTO = 0;
			$_DIAS_SUELDO_MES = 0;
		}
	}
	
	$_SUELDO = getSueldoBasico($_CODPERSONA);
	$_HIJOS = getHijosMenor18($_CODPERSONA);
	$_CURSOS = getNroCursos($_CODPERSONA, $_PERIODO);
	$_POS = esGrado($_CODPERSONA, "POS");
	$_UNI = esGrado($_CODPERSONA, "UNI");
	$_TSU = esGrado($_CODPERSONA, "TSU");
	$_ESP = esGradoNivel($_CODPERSONA, "UNI", "01");
	$_MAG = esGradoNivel($_CODPERSONA, "UNI", "02");
	$_DOC = esGradoNivel($_CODPERSONA, "UNI", "03");
	
	$periodo_hasta = "$diai-$mes-$anio";
	list ($_ANIOS_SERVICIO, $m, $d) = getEdadAMD($_FINGRESO, $periodo_hasta);
	
	$_JEFE = esJefe($_CODPERSONA);
	$_LUNES = getLunesMes($_PERIODO);
	
	list ($_ENCARGADURIA, $_SUELDO_ENCARGADURIA, $_SUELDO_DIF) = encargaduriaTemporal($_CODPERSONA);
	list($diae, $mese, $anioe)=SPLIT( '[-.-]', $_FINGRESO_ENCARGADURIA); $fingreso_encargaduria = "$anioe-$mese-$diae";
	
	if ($_ENCARGADURIA == "S") {
		if ($fadelanto >= $fingreso_encargaduria) {
			$_DIAS_SUELDO_ADELANTO_ENCARGADURIA = getFechaDias($_FINGRESO_ENCARGADURIA, $_FADELANTO) + 1;
			if ($_DIAS_SUELDO_ADELANTO_ENCARGADURIA >= 12) $_DIAS_SUELDO_ADELANTO_ENCARGADURIA = 12;
		} else $_DIAS_SUELDO_ADELANTO_ENCARGADURIA = 0;
		$_DIAS_SUELDO_MES_ENCARGADURIA = getFechaDias($_FINGRESO_ENCARGADURIA, $_FFIN) + 1;
		if ($_DIAS_SUELDO_MES_ENCARGADURIA >= 30) $_DIAS_SUELDO_MES_ENCARGADURIA = 30;
	} else {
		$_DIAS_SUELDO_ADELANTO_ENCARGADURIA = "";
		$_DIAS_SUELDO_MES_ENCARGADURIA = "";
	}
	
	if ($_DIAS_SUELDO_MES_ENCARGADURIA > 18) $_MONTO_ADELANTO_CONCEPTO = 0;
	
	//	Ejecuto la formula y obtengo los valores....
	$formula=getFormula($_CODCONCEPTO);
	eval($formula);
	$monto = number_format($_MONTO, 2, '.', '');
	$cantidad = $_CANTIDAD;
	return $monto;
}

function getSueldoBasico($_CODPERSONA) {
	$sql="SELECT me.CodEmpleado, rp.NivelSalarial FROM mastempleado me INNER JOIN rh_puestos rp ON (me.CodCargo=rp.CodCargo) WHERE me.CodPersona='".$_CODPERSONA."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query)!=0) $field=mysql_fetch_array($query);
	return $field['NivelSalarial'];
}

function getSalarioJubilacion($_CODPERSONA) {
	$sql="SELECT MontoJubilacion FROM mastempleado WHERE CodPersona='".$_CODPERSONA."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query)!=0) $field=mysql_fetch_array($query);
	return $field[0];
}

function getFechaIngreso($_CODPERSONA) {
	$sql="SELECT Fingreso FROM mastempleado WHERE CodPersona='".$_CODPERSONA."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query)!=0) $field=mysql_fetch_array($query);
	list($a, $m, $d)=SPLIT( '[-.-]', $field['Fingreso']); $fingreso = "$d-$m-$a";
	return $fingreso;
}

function getFechaCese($_CODPERSONA) {
	$sql="SELECT Fegreso FROM mastempleado WHERE CodPersona='".$_CODPERSONA."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query)!=0) $field=mysql_fetch_array($query);
	list($a, $m, $d)=SPLIT( '[-.-]', $field['Fegreso']); $fcese = "$d-$m-$a";
	return $fcese;
}

function getFechaIngresoEncargaduria($_CODPERSONA) {
	$sql="SELECT 
				Fecha 
			FROM 
				rh_empleadonivelacion 
			WHERE 
				CodPersona = '".$_CODPERSONA."' AND 
				TipoAccion = 'ET' AND 
				Secuencia = (SELECT MAX(Secuencia) FROM rh_empleadonivelacion WHERE CodPersona = '".$_CODPERSONA."')";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query)!=0) $field=mysql_fetch_array($query);
	list($a, $m, $d)=SPLIT( '[-.-]', $field['Fecha']); $Fecha = "$d-$m-$a";
	return $Fecha;
}

function esAdelantoQuincena($_PROCESO) {
	$sql = "SELECT FlagAdelanto FROM pr_tipoproceso WHERE CodTipoproceso = '".$_PROCESO."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query)!=0) $field=mysql_fetch_array($query);
	return $field['FlagAdelanto'];
}

function getHijosMenor18($_CODPERSONA) {
	$num = 0;
	$sql = "SELECT FechaNacimiento FROM rh_cargafamiliar WHERE CodPersona = '".$_CODPERSONA."' AND Parentesco = 'HI'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	while ($field=mysql_fetch_array($query)) {
		list($a, $m, $d)=SPLIT('[/.-]', $field['FechaNacimiento']); $fnac=$d."-".$m."-".$a;
		list ($a, $m, $d) = getAnios($fnac);
		$anios = (int) $a;
		if ($anios < 18) $num++;
	}
	return $num;
}

function getNroCursos($_CODPERSONA, $_PERIODO) {
	$sql = "SELECT * FROM rh_empleado_cursos WHERE CodPersona = '".$_CODPERSONA."' AND FlagPago = 'S'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	return $rows;
}

function esGrado($_CODPERSONA, $_GRADO) {
	$sql = "SELECT * FROM rh_empleado_instruccion WHERE CodPersona = '".$_CODPERSONA."' AND CodGradoInstruccion = '".$_GRADO."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) return "S"; else return "N";
}

function esGradoNivel($_CODPERSONA, $_GRADO, $_NIVEL) {
	$sql = "SELECT * FROM rh_empleado_instruccion WHERE CodPersona = '".$_CODPERSONA."' AND CodGradoInstruccion = '".$_GRADO."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $grado = "S"; else $grado = "N";
	
	$sql = "SELECT * FROM rh_empleado_instruccion WHERE CodPersona = '".$_CODPERSONA."' AND Nivel = '".$_NIVEL."' AND CodGradoInstruccion = 'POS'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $nivel = "S"; else $nivel = "N";
	
	if ($grado == "S" && $nivel == "S") return "S"; else return "N"; 
}

function getAniosServicio($_CODPERSONA) {
	$sql = "SELECT Fingreso FROM mastempleado WHERE CodPersona = '".$_CODPERSONA."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	list($a, $m, $d) = SPLIT('[/.-]', $field['Fingreso']); $fingreso = $d."-".$m."-".$a;
	list($a, $m, $d) = getAnios($fingreso);
	$anios = (int) $a;
	return $anios;
}

function getAniosServicioJubilacion($_CODPERSONA) {
	$sql="SELECT AniosServicio FROM rh_proceso_jubilacion WHERE CodPersona='".$_CODPERSONA."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query)!=0) $field=mysql_fetch_array($query);
	return $field['AniosServicio'];
}

function getCoeficienteJubilacion($_CODPERSONA) {
	$anios = getAniosServicioJubilacion($_CODPERSONA);
	$coeficiente = ($anios * 2.5) / 100;
	$coeficiente = number_format($coeficiente, 2, '.', '');
	return $coeficiente;
}

function getLunesMes($_PERIODO) {
	list($ap, $mp)=SPLIT('[/.-]', $_PERIODO); $periodo = "01-$mp-$ap";
	list($a, $m)=SPLIT('[/.-]', $periodo); $a = (int) $a; $m = (int) $m;
	$primer_dia_semana = getDiaSemana($periodo);
	$dias_mes = getDiasMes($a, $m);
	
	if ($primer_dia_semana == 0 && $dias_mes == 31) $lunes = 5;
	elseif ($primer_dia_semana == 1 && ($dias_mes == 30 || $dias_mes == 31)) $lunes = 5;
	elseif ($primer_dia_semana == 2 && ($dias_mes == 29 || $dias_mes == 30 || $dias_mes == 31)) $lunes = 5;
	else $lunes = 4;
	return $lunes;
}

function esJefe($_CODPERSONA) {
	$sql = "SELECT 
				me.CodEmpleado, 
				rp.Grado 
			FROM 
				mastempleado me 
				INNER JOIN rh_puestos rp ON (me.CodCargo = rp.CodCargo AND (rp.Grado = '99' OR rp.Grado = '98' OR rp.Grado = '97' OR rp.Grado = '96')) 
			WHERE 
				CodPersona = '".$_CODPERSONA."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) return "S"; else return "N";
}

function sumConceptos($_CODCONCEPTO, $_CODPERSONA, $_PDESDE, $_PHASTA) {
	$sql = "SELECT SUM(Monto) FROM pr_tiponominaempleadoconcepto WHERE CodConcepto = '".$_CODCONCEPTO."' AND CodPersona = '".$_CODPERSONA."' AND Periodo >= '".$_PDESDE."' AND Periodo <= '".$_PHASTA."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$field = mysql_fetch_array($query);
	return $field[0];
}

function encargaduriaTemporal($_CODPERSONA) {
	$sql = "SELECT 
				c1.NivelSalarial AS SueldoActual,
				c2.NivelSalarial AS SueldoEncarg
			FROM
				mastempleado e 
				INNER JOIN rh_puestos c1 ON (e.CodCargo = c1.CodCargo)
				INNER JOIN rh_puestos c2 ON (e.CodCargoTemp = c2.CodCargo)
			WHERE 
				e.CodPersona = '".$_CODPERSONA."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		$dif = $field['SueldoEncarg'] - $field['SueldoActual'];
		return array("S", $field['SueldoEncarg'], $dif);
	} else return array("N", "", "");
	
}

function getMontoAdelanto($_CODPERSONA, $_PERIODO) {
	$sql = "SELECT TotalIngresos FROM pr_tiponominaempleado WHERE CodPersona = '".$_CODPERSONA."' AND Periodo = '".$_PERIODO."' AND CodTipoProceso = 'ADE'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		return $field['TotalIngresos'];
	} else return 0;
}

function getMontoAdelantoConcepto($_CODPERSONA, $_PERIODO, $_CODCONCEPTO) {
	$sql = "SELECT Monto FROM pr_tiponominaempleadoconcepto WHERE CodPersona = '".$_CODPERSONA."' AND Periodo = '".$_PERIODO."' AND CodConcepto = '".$_CODCONCEPTO."' AND CodTipoProceso = 'ADE'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		return $field['Monto'];
	} else return 0;
}

function getRetencionJudicial($_CODPERSONA) {
	$sql = "SELECT * FROM rh_retencionjudicial WHERE CodPersona = '".$_CODPERSONA."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		if ($field['TipoDescuento'] == "P") {
			$sueldomin = getParametro("SUELDOMIN");
			$monto = $sueldomin * ( $field['Descuento'] / 100 );
		} else $monto = $field['Descuento'];
				
		return $monto;
	} else return 0;
}

function getParametro($parametro) {
	if ($parametro != "") $filtro = "WHERE ParametroClave = '".$parametro."'";
	$sql = "SELECT ValorParam FROM mastparametros $filtro";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		return $field['ValorParam'];
	} else return "";
}

function getParametros($parametro) {
	if ($parametro != "") $filtro = "WHERE ParametroClave = '".$parametro."'";
	$sql = "SELECT ParametroClave, ValorParam FROM mastparametros $filtro";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	while ($field = mysql_fetch_array($query)) {
		$id = $field['ParametroClave'];
		$valor = $field['ValorParam'];
		$parametros[$id] = $valor;
	}
	return $parametros;
}

function getSueldoNormal($_CODPERSONA, $_NOMINA, $_PERIODO) {
	$monto = 0;
	$sql = "SELECT
				pec.CodConcepto
			FROM
				pr_empleadoconcepto pec
				INNER JOIN pr_concepto pc ON (pec.CodConcepto = pc.CodConcepto AND pc.Tipo = 'I')
				INNER JOIN pr_conceptotiponomina pctn ON (pc.CodConcepto = pctn.CodConcepto AND pctn.CodTipoNom = '".$_NOMINA."')
			WHERE
				pec.CodPersona='".$_CODPERSONA."' AND
				((pec.TipoAplicacion='T' AND pec.PeriodoHasta>='".$_PERIODO."' AND pec.PeriodoDesde<='".$_PERIODO."') OR 
				 (pec.TipoAplicacion='P' AND '".$_PERIODO."'>=pec.PeriodoDesde))";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	while ($field = mysql_fetch_array($query)) {
		$concepto = CONCEPTO($_CODPERSONA, $field['CodConcepto']);
		$monto += $concepto;
	}
	return $monto;
}

function getMesesAntiguedad($_CODPERSONA, $_PERIODO) {
	list($ap, $mp)=SPLIT('[/.-]', $_PERIODO);
	$ap = (int) $ap;
	$mp = (int) $mp;
	list($di, $mi, $ai)=SPLIT('[/.-]', getFechaIngreso($_CODPERSONA));
	$ai = (int) $ai;
	$mi = (int) $mi;
	
	if ($ap == $ai) $meses = $mp - $mi;	
	else {
		$anios = $ap - $ai - 1;
		if ($mp > $mi) $meses = (12 + ($mp - $mi)) + (12 * $anios);
		else {
			$meses = ((12 - $mi) + $mp) + (12 * $anios);
		}
	}
	return $meses;
}

function getPeriodoLetras($periodo) {
	list($a, $m)=SPLIT('[/.-]', $periodo); $m = (int) $m;
	$mes = getNombreMes($m);	
	$letras = "MES DE ".strtoupper($mes)." ".$a;
	
	return $letras;
}

function redondear($valor, $decimales) {
	$ceros = (string) str_repeat("0", $decimales);
	$unidad = "1".$ceros;
	$unidad = (int) $unidad;
	
	$multiplicamos = $valor * $unidad;
	
	list($parte_entera, $numero_redondeo)=SPLIT('[.]', $multiplicamos);
	$numero_redondeo = substr($numero_redondeo, 0, 1);
	if ($numero_redondeo >= 5) $parte_entera++;
	
	$resultado = $parte_entera / $unidad;
	
	return $resultado;
}

function estadoTrabajador($_CODPERSONA) {
	$sql = "SELECT Estado FROM mastempleado WHERE CodPersona = '".$_CODPERSONA."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	
	return $field['Estado'];
}

function porcentajeISLR($_CODPERSONA) {
	$sql = "SELECT Porcentaje FROM pr_impuestorenta WHERE CodPersona = '".$_CODPERSONA."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	
	return $field['Porcentaje'];
}
//	------------------------------------

function getPeriodoProceso($proceso, $periodo, $nomina) {
	$sql = "SELECT FechaDesde, FechaHasta
			FROM pr_procesoperiodo
			WHERE
				CodTipoNom = '".$nomina."' AND
				Periodo = '".$periodo."' AND
				CodTipoProceso = '".$proceso."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	return array($field['FechaDesde'], $field['FechaHasta']);
}

//	obtengo el porcentaje del impuesto por defecto 
function getIGVCODIGO($proveedor) {
	$sql = "SELECT i.FactorPorcentaje
			FROM
				mastproveedores mp
				INNER JOIN masttiposervicioimpuesto tsi ON (mp.CodTipoServicio = tsi.CodTipoServicio)
				INNER JOIN mastimpuestos i ON (tsi.CodImpuesto = i.CodImpuesto AND i.CodRegimenFiscal = 'I')
			WHERE mp.CodProveedor = '".$proveedor."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	return $field['FactorPorcentaje'];
}


//	IMPUESTOS DEL PROVEEDOR
function getObligacionesImpuestos($codproveedor) {
	$_IMPUESTOS = array();
	
	$sql = "SELECT i.*
			FROM
				mastproveedores p
				INNER JOIN masttiposervicioimpuesto tsi ON (p.CodTipoServicio = tsi.CodTipoServicio)
				INNER JOIN mastimpuestos i ON (tsi.CodImpuesto = i.CodImpuesto AND i.CodRegimenFiscal = 'R')
			WHERE p.CodProveedor = '".$codproveedor."'";
	$query_impuestos = mysql_query($sql) or die ($sql.mysql_error());
	while ($field_impuestos = mysql_fetch_array($query_impuestos)) {
		$_IMPUESTOS = $field_impuestos;
	}
	return $_IMPUESTOS;
}

// obtiene la cuenta bancaria por defecto de un tipo de pago para un organismo
function getCuentaBancariaDefault($codorganismo, $tpago) {
	$sql = "SELECT * 
			FROM ap_ctabancariadefault 
			WHERE
				CodOrganismo = '".$codorganismo."' AND
				CodTipoPago = '".$tpago."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	return $field['NroCuenta'];
}

//	obtiene 
function getFirmaNomina($nomina, $periodo, $proceso, $campo) {
	$sql = "SELECT
				mp.Busqueda,
				p.DescripCargo,
				mp.Sexo
			FROM
				pr_procesoperiodo pp
				INNER JOIN mastpersonas mp ON (pp.$campo = mp.CodPersona)
				INNER JOIN mastempleado me ON (mp.CodPersona = me.CodPersona)
				INNER JOIN rh_puestos p ON (me.CodCargo = p.CodCargo)
			WHERE
				pp.CodTipoNom = '".$nomina."' AND 
				pp.Periodo = '".$periodo."' AND 
				pp.CodTipoproceso = '".$proceso."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	
	if ($field['Sexo'] == "M") {
		$denominacion_cargo = $field['DescripCargo'];
		$cargo = str_replace("JEFE (A)", "JEFE", $denominacion_cargo);
		$cargo = str_replace("DIRECTOR (A)", "DIRECTOR", $denominacion_cargo);
	} else {
		$denominacion_cargo = $field['DescripCargo'];
		$cargo = str_replace("JEFE (A)", "JEFA", $denominacion_cargo);
		$cargo = str_replace("DIRECTOR (A)", "DIRECTORA", $denominacion_cargo);
	}
	
	return array($field['Busqueda'], $cargo);
}

//	funcion para verificar disponibiliadad presupuestaria de las cotizaciones
function verificarDisponibilidadPresupuestariaCotizacion($invitacion) {
	$disponible = true;
	$total_impuesto = 0;
	
	//	verifico la disponibilidad de las partidas
	$sql = "(SELECT
				p.cod_partida,
				p.denominacion,
				SUM(c.PrecioCantidad) AS Monto,
				SUM(c.Total - c.PrecioCantidad) AS Impuesto,
				(pvpd.MontoAjustado - pvpd.MontoCompromiso) AS Disponible,
				((pvpd.MontoAjustado - pvpd.MontoCompromiso) - SUM(c.PrecioCantidad)) AS Resta,
				pvp.EjercicioPpto
			 FROM
				lg_cotizacion c
				INNER JOIN lg_requerimientosdet rd ON (c.CodOrganismo = rd.CodOrganismo AND
													   c.CodRequerimiento = rd.CodRequerimiento AND
													   c.Secuencia = rd.Secuencia)
				INNER JOIN lg_itemmast i ON (rd.CodItem = i.CodItem)
				INNER JOIN pv_partida p ON (i.PartidaPresupuestal = p.cod_partida)
				LEFT JOIN pv_presupuestodet pvpd ON (c.CodOrganismo = pvpd.Organismo AND
													 p.cod_partida = pvpd.cod_partida)
				LEFT JOIN pv_presupuesto pvp ON (pvp.Organismo = pvpd.Organismo AND
												 pvp.CodPresupuesto = pvpd.CodPresupuesto AND
												 pvp.EjercicioPpto = SUBSTRING(c.FechaDocumento, 1, 4))
			 WHERE c.CotizacionNumero = '".$invitacion."'
			 GROUP BY cod_partida)
			 
			UNION
			
			(SELECT
				p.cod_partida,
				p.denominacion,
				SUM(c.PrecioCantidad) AS Monto,
				SUM(c.Total - c.PrecioCantidad) AS Impuesto,
				(pvpd.MontoAjustado - pvpd.MontoCompromiso) AS Disponible,
				((pvpd.MontoAjustado - pvpd.MontoCompromiso) - SUM(c.PrecioCantidad)) AS Resta,
				pvp.EjercicioPpto
			 FROM
				lg_cotizacion c
				INNER JOIN lg_requerimientosdet rd ON (c.CodOrganismo = rd.CodOrganismo AND
													   c.CodRequerimiento = rd.CodRequerimiento AND
													   c.Secuencia = rd.Secuencia)
				INNER JOIN lg_commoditysub i ON (rd.CommoditySub = i.Codigo)
				INNER JOIN pv_partida p ON (i.cod_partida = p.cod_partida)
				LEFT JOIN pv_presupuestodet pvpd ON (c.CodOrganismo = pvpd.Organismo AND
													 p.cod_partida = pvpd.cod_partida)
				LEFT JOIN pv_presupuesto pvp ON (pvp.Organismo = pvpd.Organismo AND
												 pvp.CodPresupuesto = pvpd.CodPresupuesto AND
												 pvp.EjercicioPpto = SUBSTRING(c.FechaDocumento, 1, 4))
			 WHERE c.CotizacionNumero = '".$invitacion."'
			 GROUP BY cod_partida)";
	$query_general = mysql_query($sql) or die ($sql.mysql_error());
	while($field_general = mysql_fetch_array($query_general)) {
		if ($field_general['Resta'] <= 0) $disponible = false;
		else {
			$total_impuesto += $field_general['Impuesto'];
			$anio = $field_general['EjercicioPpto'];
		}
	}
	
	//	verifico la disponibilidad del igv
	$sql = "SELECT
				p.cod_partida,
				p.denominacion,
				(pvpd.MontoAjustado - pvpd.MontoCompromiso) AS Disponible
			FROM
				pv_partida p
				LEFT JOIN pv_presupuestodet pvpd ON (p.cod_partida = pvpd.cod_partida)
				LEFT JOIN pv_presupuesto pvp ON (pvp.CodPresupuesto = pvpd.CodPresupuesto AND
												 pvp.EjercicioPpto = '$anio')
			WHERE p.cod_partida = '".getParametro('IVADEFAULT')."'";
	$query_general = mysql_query($sql) or die ($sql.mysql_error());
	while($field_general = mysql_fetch_array($query_general)) {
		$resta = $field_general['Disponible'] - $total_impuesto;
		if ($resta <= 0) $disponible = false;
	}
	return $disponible;
}

//	funcion para verificar disponibiliadad presupuestaria de las cotizaciones
function verificarDisponibilidadPresupuestariaOC($tabla, $detalles, $organismo, $periodo, $total_impuesto, $nroorden) {
	$disponible = true;
	//	verifico la distribucion presupuestaria
	if ($tabla == "item") {
		$filtro_sel = "i.CodItem = '".$codigo."'";
		if ($detalles != "") {
			$detalle = split(";", $detalles);
			foreach ($detalle as $linea) {
				list($registro, $descripcion, $codunidad, $cantidad, $unitario, $descporc, $descfijo, $exon, $fentregadet, $ccostos, $codpartida, $codcuenta, $obs) = SPLIT( '[|]', $linea);
				$filtro_det .= " OR i.CodItem = '".$registro."'";
				$partida[$codpartida] += ($cantidad * $unitario);
			}
		}
        //	consulto las partidas de los items
		$sql = "(SELECT 
					do.cod_partida,
					pc.denominacion,
					pvp.EjercicioPpto,
					(pvpd.MontoAjustado - pvpd.MontoCompromiso + do.Monto) AS MontoDisponible
				 FROM
					lg_distribucionoc do
					INNER JOIN pv_partida pc ON (do.cod_partida = pc.cod_partida)
					LEFT JOIN pv_presupuestodet pvpd ON (pc.cod_partida = pvpd.cod_partida AND pvpd.Organismo = do.CodOrganismo)
					LEFT JOIN pv_presupuesto pvp ON (pvp.Organismo = pvpd.Organismo AND
													 pvp.CodPresupuesto = pvpd.CodPresupuesto AND
													 pvp.EjercicioPpto = SUBSTRING(do.Periodo, 1, 4))
				 WHERE
					do.CodOrganismo = '".$organismo."' AND
					do.NroOrden = '".$nroorden."' AND
					pc.cod_partida <> '".getParametro('IVADEFAULT')."')
					
				UNION
				
				(SELECT
					p.cod_partida,
					p.denominacion,
					pvp.EjercicioPpto,
					(pvpd.MontoAjustado - pvpd.MontoCompromiso) AS MontoDisponible
				 FROM
					lg_itemmast i
					INNER JOIN pv_partida p ON (i.PartidaPresupuestal = p.cod_partida)
					LEFT JOIN pv_presupuestodet pvpd ON (p.cod_partida = pvpd.cod_partida AND pvpd.Organismo = '".$organismo."')
					LEFT JOIN pv_presupuesto pvp ON (pvp.Organismo = pvpd.Organismo AND
													 pvp.CodPresupuesto = pvpd.CodPresupuesto AND
													 pvp.EjercicioPpto = '".date("Y")."')
				 WHERE 
				 	1 AND ($filtro_sel $filtro_det) AND
					p.cod_partida NOT IN (SELECT 
												do1.cod_partida
										  FROM
												lg_distribucionoc do1
												INNER JOIN pv_partida pc1 ON (do1.cod_partida = pc1.cod_partida)
												LEFT JOIN pv_presupuestodet pvpd1 ON (pc1.cod_partida = pvpd1.cod_partida AND pvpd1.Organismo = do1.CodOrganismo)
												LEFT JOIN pv_presupuesto pvp1 ON (pvp1.Organismo = pvpd1.Organismo AND
																				  pvp1.CodPresupuesto = pvpd1.CodPresupuesto AND
																				  pvp1.EjercicioPpto = SUBSTRING(do1.Periodo, 1, 4))
										  WHERE
												do1.CodOrganismo = '".$organismo."' AND
												do1.NroOrden = '".$nroorden."' AND
												pc1.cod_partida <> '".getParametro('IVADEFAULT')."')					
				 GROUP BY cod_partida)
				
				ORDER BY cod_partida";
    } else {
		$filtro_sel = "cs.Codigo = '".$codigo."'";
		if ($detalles != "") {
			$detalle = split(";", $detalles);
			foreach ($detalle as $linea) {	$i++;
				list($registro, $descripcion, $codunidad, $cantidad, $unitario, $descporc, $descfijo, $exon, $fentregadet, $ccostos, $codpartida, $codcuenta, $obs) = SPLIT( '[|]', $linea);
				list($commodity, $sec) = split("[.]", $registro);
				$filtro_det .= " OR cs.Codigo = '".$commodity."'";
				$partida[$codpartida] += ($cantidad * $unitario);
			}
		}
        //	consulto las partidas de los commodities
		$sql = "(SELECT 
					do.cod_partida,
					pc.denominacion,
					pvp.EjercicioPpto,
					(pvpd.MontoAjustado - pvpd.MontoCompromiso + do.Monto) AS MontoDisponible
				 FROM
					lg_distribucionoc do
					INNER JOIN pv_partida pc ON (do.cod_partida = pc.cod_partida)
					LEFT JOIN pv_presupuestodet pvpd ON (pc.cod_partida = pvpd.cod_partida AND pvpd.Organismo = do.CodOrganismo)
					LEFT JOIN pv_presupuesto pvp ON (pvp.Organismo = pvpd.Organismo AND
													 pvp.CodPresupuesto = pvpd.CodPresupuesto AND
													 pvp.EjercicioPpto = SUBSTRING(do.Periodo, 1, 4))
				 WHERE
					do.CodOrganismo = '".$organismo."' AND
					do.NroOrden = '".$nroorden."' AND
					pc.cod_partida <> '".getParametro('IVADEFAULT')."')
					
				UNION
				
				(SELECT
					p.cod_partida,
					p.denominacion,
					pvp.EjercicioPpto,
					(pvpd.MontoAjustado - pvpd.MontoCompromiso) AS MontoDisponible
				 FROM
					lg_commoditysub cs
					INNER JOIN pv_partida p ON (cs.cod_partida = p.cod_partida)
					LEFT JOIN pv_presupuestodet pvpd ON (p.cod_partida = pvpd.cod_partida AND pvpd.Organismo = '".$organismo."')
					LEFT JOIN pv_presupuesto pvp ON (pvp.Organismo = pvpd.Organismo AND
													 pvp.CodPresupuesto = pvpd.CodPresupuesto AND
													 pvp.EjercicioPpto = '".date("Y")."')
				 WHERE 
				 	1 AND ($filtro_sel $filtro_det) AND
					p.cod_partida NOT IN (SELECT 
												do1.cod_partida
										  FROM
												lg_distribucionoc do1
												INNER JOIN pv_partida pc1 ON (do1.cod_partida = pc1.cod_partida)
												LEFT JOIN pv_presupuestodet pvpd1 ON (pc1.cod_partida = pvpd1.cod_partida AND pvpd1.Organismo = do1.CodOrganismo)
												LEFT JOIN pv_presupuesto pvp1 ON (pvp1.Organismo = pvpd1.Organismo AND
																				  pvp1.CodPresupuesto = pvpd1.CodPresupuesto AND
																				  pvp1.EjercicioPpto = SUBSTRING(do1.Periodo, 1, 4))
										  WHERE
												do1.CodOrganismo = '".$organismo."' AND
												do1.NroOrden = '".$nroorden."' AND
												pc1.cod_partida <> '".getParametro('IVADEFAULT')."')					
				 GROUP BY cod_partida)
				
				ORDER BY cod_partida";
	}
	$query_general = mysql_query($sql) or die ($sql.mysql_error());
	while($field_general = mysql_fetch_array($query_general)) {
		$codpartida = $field_general['cod_partida'];
		$resta = $field_general['MontoDisponible'] - $partida[$codpartida];
		if ($resta < 0) $disponible = false;
	}
	if ($total_impuesto > 0) {
		//	si ya tiene distribuido algun monto en el igv lo obtengo
		$sql = "SELECT do.Monto
				FROM lg_distribucionoc do
				WHERE
					do.CodOrganismo = '".$organismo."' AND
					do.NroOrden = '".$nroorden."' AND
					do.cod_partida = '".getParametro('IVADEFAULT')."'";
		$query_igv = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_igv) != 0) $field_igv = mysql_fetch_array($query_general);
		$montoigv = (float) $field_igv['Monto'];
		
		//	obtengo la disponibilidad de la partida del igv		
		$sql = "SELECT
					p.cod_partida,
					p.denominacion,
					(pvpd.MontoAjustado - pvpd.MontoCompromiso + $montoigv) AS MontoDisponible
				FROM
					pv_partida p
					LEFT JOIN pv_presupuestodet pvpd ON (p.cod_partida = pvpd.cod_partida)
					LEFT JOIN pv_presupuesto pvp ON (pvp.CodPresupuesto = pvpd.CodPresupuesto AND
													 pvp.EjercicioPpto = '$anio')
				WHERE p.cod_partida = '".getParametro('IVADEFAULT')."'";
		$query_general = mysql_query($sql) or die ($sql.mysql_error());
		while($field_general = mysql_fetch_array($query_general)) {
			$resta = $field_general['MontoDisponible'] - $total_impuesto;
			if ($resta < 0) $disponible = false;
		}
	}
	return $disponible;
}

//	funcion para verificar disponibiliadad presupuestaria de las cotizaciones
function verificarDisponibilidadPresupuestariaOS($tabla, $detalles, $organismo, $periodo, $total_impuesto, $nroorden) {
	$disponible = true;	
	//	verifico la distribucion presupuestaria
	$filtro_sel = "cs.Codigo = '".$codigo."'";
	if ($detalles != "") {
		$detalle = split(";", $detalles);
		foreach ($detalle as $linea) {	$i++;
			list($codigo, $descripcion_det, $cantidad, $unitario, $esperada, $ccostos, $activo, $terminado, $codpartida, $codcuenta, $obs)=SPLIT( '[|]', $linea);
			list($commodity, $sec) = split("[.]", $codigo);
			$filtro_det .= " OR cs.Codigo = '".$commodity."'";
			$partida[$codpartida] += ($cantidad * $unitario);
		}
	}
	//	consulto las partidas de los commodities
	$sql = "(SELECT 
				do.cod_partida,
				pc.denominacion,
				pvp.EjercicioPpto,
				(pvpd.MontoAjustado - pvpd.MontoCompromiso + do.Monto) AS MontoDisponible
			 FROM
				lg_distribucionos do
				INNER JOIN pv_partida pc ON (do.cod_partida = pc.cod_partida)
				LEFT JOIN pv_presupuestodet pvpd ON (pc.cod_partida = pvpd.cod_partida AND pvpd.Organismo = do.CodOrganismo)
				LEFT JOIN pv_presupuesto pvp ON (pvp.Organismo = pvpd.Organismo AND
												 pvp.CodPresupuesto = pvpd.CodPresupuesto AND
												 pvp.EjercicioPpto = '$periodo')
			 WHERE
				do.CodOrganismo = '".$organismo."' AND
				do.NroOrden = '".$nroorden."' AND
				pc.cod_partida <> '".getParametro('IVADEFAULT')."')
				
			UNION
			
			(SELECT
				p.cod_partida,
				p.denominacion,
				pvp.EjercicioPpto,
				(pvpd.MontoAjustado - pvpd.MontoCompromiso) AS MontoDisponible
			 FROM
				lg_commoditysub cs
				INNER JOIN pv_partida p ON (cs.cod_partida = p.cod_partida)
				LEFT JOIN pv_presupuestodet pvpd ON (p.cod_partida = pvpd.cod_partida AND pvpd.Organismo = '".$organismo."')
				LEFT JOIN pv_presupuesto pvp ON (pvp.Organismo = pvpd.Organismo AND
												 pvp.CodPresupuesto = pvpd.CodPresupuesto AND
												 pvp.EjercicioPpto = '$periodo')
			 WHERE 
				1 AND ($filtro_sel $filtro_det) AND
				p.cod_partida NOT IN (SELECT 
											do1.cod_partida
									  FROM
											lg_distribucionos do1
											INNER JOIN pv_partida pc1 ON (do1.cod_partida = pc1.cod_partida)
											LEFT JOIN pv_presupuestodet pvpd1 ON (pc1.cod_partida = pvpd1.cod_partida AND pvpd1.Organismo = do1.CodOrganismo)
											LEFT JOIN pv_presupuesto pvp1 ON (pvp1.Organismo = pvpd1.Organismo AND
																			  pvp1.CodPresupuesto = pvpd1.CodPresupuesto AND
																			  pvp1.EjercicioPpto = '$periodo')
									  WHERE
											do1.CodOrganismo = '".$organismo."' AND
											do1.NroOrden = '".$nroorden."' AND
											pc1.cod_partida <> '".getParametro('IVADEFAULT')."')					
			 GROUP BY cod_partida)
			
			ORDER BY cod_partida";
	$query_general = mysql_query($sql) or die ($sql.mysql_error());
	while($field_general = mysql_fetch_array($query_general)) {
		$codpartida = $field_general['cod_partida'];
		$resta = $field_general['MontoDisponible'] - $partida[$codpartida];
		if ($resta < 0) $disponible = false;
	}
	if ($total_impuesto > 0) {
		//	si ya tiene distribuido algun monto en el igv lo obtengo
		$sql = "SELECT do.Monto
				FROM lg_distribucionos do
				WHERE
					do.CodOrganismo = '".$organismo."' AND
					do.NroOrden = '".$nroorden."' AND
					do.cod_partida = '".getParametro('IVADEFAULT')."'";
		$query_igv = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_igv) != 0) $field_igv = mysql_fetch_array($query_general);
		$montoigv = (float) $field_igv['Monto'];
		
		//	obtengo la disponibilidad de la partida del igv		
		$sql = "SELECT
					p.cod_partida,
					p.denominacion,
					(pvpd.MontoAjustado - pvpd.MontoCompromiso + $montoigv) AS MontoDisponible
				FROM
					pv_partida p
					LEFT JOIN pv_presupuestodet pvpd ON (p.cod_partida = pvpd.cod_partida)
					LEFT JOIN pv_presupuesto pvp ON (pvp.CodPresupuesto = pvpd.CodPresupuesto AND
													 pvp.EjercicioPpto = '$anio')
				WHERE p.cod_partida = '".getParametro('IVADEFAULT')."'";
		$query_general = mysql_query($sql) or die ($sql.mysql_error());
		while($field_general = mysql_fetch_array($query_general)) {
			$resta = $field_general['MontoDisponible'] - $total_impuesto;			
			if ($resta < 0) $disponible = false;
		}
	}
	return $disponible;
}

//	funcion para convertir un numero formateado en su valor real
function setNumero($num) {
	$num = str_replace(".", "", $num);
	$num = str_replace(",", ".", $num);
	$numero = (float) $num;
	return $numero;
}

//	funcion para obtener el monto autorizado para caja chica de un empleado
function getMontoAutorizadoCajaChica($codorganismo, $codbeneficiario) {
	$sql = "SELECT Monto
			FROM
				mastpersonas p
				INNER JOIN mastempleado e ON (p.CodPersona = e.CodPersona)
				INNER JOIN ap_cajachicaautorizacion cca ON (e.CodEmpleado = cca.CodEmpleado)
			WHERE
				p.CodPersona = '".$codbeneficiario."' AND
				cca.CodOrganismo = '".$codorganismo."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	return $field['Monto'];

}

//	obtengo la partida y la cuenta de un concepto de gasto
function getPartidaCuentaFromConceptoGasto($concepto) {
	$sql = "SELECT CodPartida, CodCuenta
			FROM ap_conceptogastos
			WHERE CodConceptoGasto = '".$concepto."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	return array($field['CodPartida'], $field['CodCuenta']);
}

//	obtengo el codigo del empleado de la persona
function getCodEmpleadoFromCodPersona($persona) {
	$sql = "SELECT CodEmpleado
			FROM mastempleado
			WHERE CodPersona = '".$persona."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	return $field['CodEmpleado'];
}

//	obtengo el nombre del concepto
function getNomConceptoGasto($concepto) {
	$sql = "SELECT Descripcion FROM ap_conceptogastos WHERE CodConceptoGasto = '".$concepto."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	return $field['Descripcion'];
}

//	obtengo el nombre de la partida
function getNomPartida($partida) {
	$sql = "SELECT denominacion FROM pv_partida WHERE cod_partida = '".$partida."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	return $field['denominacion'];
}

//	obtengo el nombre de la cuenta
function getNomCuenta($cuenta) {
	$sql = "SELECT Descripcion FROM ac_mastplancuenta WHERE CodCuenta = '".$cuenta."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	return $field['Descripcion'];
}

//	obtengo el concepto del gasto a partir de la partida
function getConceptoGastoFromPartida($partida) {
	$sql = "SELECT CodConceptoGasto FROM ap_conceptogastos WHERE CodPartida = '".$partida."' LIMIT 0, 1";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	return $field['CodConceptoGasto'];
}

//	FUNCION PARA REDONDEAR LOS VALORES DECIMALES
function REDONDEO($VALOR, $DECIMALES) {
	$ceros = (string) str_repeat("0", $DECIMALES);
	$unidad = "1".$ceros;
	$unidad = (int) $unidad;
	
	$multiplicamos = $VALOR * $unidad;
	
	list($parte_entera, $numero_redondeo)=SPLIT('[.]', $multiplicamos);
	$numero_redondeo = substr($numero_redondeo, 0, 1);
	if ($numero_redondeo >= 5) $parte_entera++;
	
	$resultado = $parte_entera / $unidad;
	
	return $resultado;
}

//	FUNCION QUE OBTIENE LA REMUNERAION DIARIA DE UN TRABAJADOR PARA UN PERIODO
function calculo_antiguedad_complemento($desde, $hasta, $trabajador, $fingreso) {
	//	obtengo el sueldo normal
	$sql = "SELECT SUM(TotalIngresos) AS Monto
			FROM pr_tiponominaempleado
			WHERE
				CodPersona = '".$trabajador."' AND
				Periodo >= '".$desde."' AND
				Periodo <= '".$hasta."' AND
				CodTipoProceso = 'FIN'";
	$query_sueldo = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query_sueldo) != 0) $field_sueldo = mysql_fetch_array($query_sueldo);
	
	//	obtengo bonos adicionales
	$sql = "SELECT SUM(tnec.Monto) AS Monto
			FROM
				pr_tiponominaempleadoconcepto tnec
				INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto AND
											 c.Tipo = 'I' AND
											 c.FlagBonoRemuneracion = 'S')
			WHERE
				tnec.CodPersona = '".$trabajador."' AND
				tnec.Periodo >= '".$desde."' AND
				tnec.Periodo <= '".$hasta."'";
	$query_bonos = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query_bonos) != 0) $field_bonos = mysql_fetch_array($query_bonos);
	
	//	obtengo la alicuota vacacional
	$sql = "SELECT SUM(Monto) AS Monto
			FROM pr_tiponominaempleadoconcepto
			WHERE
				CodPersona = '".$trabajador."' AND
				Periodo >= '".$desde."' AND
				Periodo <= '".$hasta."' AND
				CodConcepto = '0046'";
	$query_alivac = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query_alivac) != 0) $field_alivac = mysql_fetch_array($query_alivac);
	
	//	obtengo la alicuota fin de año
	$sql = "SELECT SUM(Monto) AS Monto
			FROM pr_tiponominaempleadoconcepto
			WHERE
				CodPersona = '".$trabajador."' AND
				Periodo >= '".$desde."' AND
				Periodo <= '".$hasta."' AND
				CodConcepto = '0047'";
	$query_fin = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query_fin) != 0) $field_fin = mysql_fetch_array($query_fin);
	
	//	obtengo los dias acumulados
	if ($fingreso <= "1997-06-30") $fidesde = "1997-06-01"; else $fidesde = $fingreso;
	list($da, $dm) = split("[-]", $desde);
	list($ha, $hm) = split("[-]", $hasta);
	list($aservicio, $mservicio, $dservicio) = TIEMPO_DE_SERVICIO(formatFechaDMA($fidesde), "01-$hm-$ha");
	$dias_acumulados = ($aservicio - 1) * 2;
	
	//	operaciones
	$sueldo_normal_diario = REDONDEO(($field_sueldo['Monto']/30), 2);
	$bonos_diario = REDONDEO(($field_bonos['Monto']/30), 2);
	$remuneracion_diaria = $sueldo_normal_diario + $bonos_diario;
	$sueldo_alicuotas = $remuneracion_diaria + $field_alivac['Monto'] + $field_fin['Monto'];
	$monto = $sueldo_alicuotas / 12 * $dias_acumulados;
	return $monto;
}

//	FUNCION PARA OBTENER LOS ANIOS, MESES Y DIAS ENTRE DOS FECHAS
function TIEMPO_DE_SERVICIO($_DESDE, $_HASTA) {
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

//	
function tasaInteres($periodo) {
	$sql = "SELECT Porcentaje FROM masttasainteres WHERE Periodo = '".$periodo."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	return $field['Porcentaje'];
}

//
function getFirmaPermisos() {
	$sql = "SELECT
				p.NomCompleto,
				p.Sexo,
				pu1.DescripCargo AS Cargo,
				pu2.DescripCargo AS CargoTemp
			FROM
				mastdependencias d
				INNER JOIN mastpersonas p ON (d.CodPersona = p.CodPersona)
				INNER JOIN mastempleado e ON (p.CodPersona = e.CodPersona)
				LEFT JOIN rh_puestos pu1 ON (e.CodCargo = pu1.CodCargo)
				LEFT JOIN rh_puestos pu2 ON (e.CodCargoTemp = pu2.CodCargo)
			WHERE d.CodDependencia = '0010'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	
	if ($field['CargoTemp'] != "") { $cargo = $field['CargoTemp']; $cargo = str_replace("(A)", "(E)", $cargo); }
	else { $cargo = $field['Cargo']; $cargo = str_replace("(A)", "", $cargo); }
	
	if ($field['Sexo'] == "F") {
		$cargo = str_replace("JEFE", "JEFA", $cargo);
		$cargo = str_replace("DIRECTOR", "DIRECTORA", $cargo);
	}
	return array(utf8_decode($field['NomCompleto']), $cargo);
}
?>
