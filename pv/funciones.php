<?php
// FUNCIONES DE CONVERSION DE NUMEROS A LETRAS.
function centimos() {
	global $importe_parcial;

	$importe_parcial = number_format($importe_parcial, 2, ".", "") * 100;

	if ($importe_parcial > 0)
		$num_letra = " con ".decena_centimos($importe_parcial);
	else
		$num_letra = "";

	return $num_letra;
}

function unidad_centimos($numero)
{
	switch ($numero)
	{
		case 9:
		{
			$num_letra = "nueve céntimos";
			break;
		}
		case 8:
		{
			$num_letra = "ocho céntimos";
			break;
		}
		case 7:
		{
			$num_letra = "siete céntimos";
			break;
		}
		case 6:
		{
			$num_letra = "seis céntimos";
			break;
		}
		case 5:
		{
			$num_letra = "cinco céntimos";
			break;
		}
		case 4:
		{
			$num_letra = "cuatro céntimos";
			break;
		}
		case 3:
		{
			$num_letra = "tres céntimos";
			break;
		}
		case 2:
		{
			$num_letra = "dos céntimos";
			break;
		}
		case 1:
		{
			$num_letra = "un céntimo";
			break;
		}
	}
	return $num_letra;
}

function decena_centimos($numero)
{
	if ($numero >= 10)
	{
		if ($numero >= 90 && $numero <= 99)
		{
			  if ($numero == 90)
				  return "noventa céntimos";
			  else if ($numero == 91)
				  return "noventa y un céntimos";
			  else
				  return "noventa y ".unidad_centimos($numero - 90);
		}
		if ($numero >= 80 && $numero <= 89)
		{
			if ($numero == 80)
				return "ochenta céntimos";
			else if ($numero == 81)
				return "ochenta y un céntimos";
			else
				return "ochenta y ".unidad_centimos($numero - 80);
		}
		if ($numero >= 70 && $numero <= 79)
		{
			if ($numero == 70)
				return "setenta céntimos";
			else if ($numero == 71)
				return "setenta y un céntimos";
			else
				return "setenta y ".unidad_centimos($numero - 70);
		}
		if ($numero >= 60 && $numero <= 69)
		{
			if ($numero == 60)
				return "sesenta céntimos";
			else if ($numero == 61)
				return "sesenta y un céntimos";
			else
				return "sesenta y ".unidad_centimos($numero - 60);
		}
		if ($numero >= 50 && $numero <= 59)
		{
			if ($numero == 50)
				return "cincuenta céntimos";
			else if ($numero == 51)
				return "cincuenta y un céntimos";
			else
				return "cincuenta y ".unidad_centimos($numero - 50);
		}
		if ($numero >= 40 && $numero <= 49)
		{
			if ($numero == 40)
				return "cuarenta céntimos";
			else if ($numero == 41)
				return "cuarenta y un céntimos";
			else
				return "cuarenta y ".unidad_centimos($numero - 40);
		}
		if ($numero >= 30 && $numero <= 39)
		{
			if ($numero == 30)
				return "treinta céntimos";
			else if ($numero == 91)
				return "treinta y un céntimos";
			else
				return "treinta y ".unidad_centimos($numero - 30);
		}
		if ($numero >= 20 && $numero <= 29)
		{
			if ($numero == 20)
				return "veinte céntimos";
			else if ($numero == 21)
				return "veintiun céntimos";
			else
				return "veinti".unidad_centimos($numero - 20);
		}
		if ($numero >= 10 && $numero <= 19)
		{
			if ($numero == 10)
				return "diez céntimos";
			else if ($numero == 11)
				return "once céntimos";
			else if ($numero == 11)
				return "doce céntimos";
			else if ($numero == 11)
				return "trece céntimos";
			else if ($numero == 11)
				return "catorce céntimos";
			else if ($numero == 11)
				return "quince céntimos";
			else if ($numero == 11)
				return "dieciseis céntimos";
			else if ($numero == 11)
				return "diecisiete céntimos";
			else if ($numero == 11)
				return "dieciocho céntimos";
			else if ($numero == 11)
				return "diecinueve céntimos";
		}
	}
	else
		return unidad_centimos($numero);
}

function unidad($numero)
{
	switch ($numero)
	{
		case 9:
		{
			$num = "nueve";
			break;
		}
		case 8:
		{
			$num = "ocho";
			break;
		}
		case 7:
		{
			$num = "siete";
			break;
		}
		case 6:
		{
			$num = "seis";
			break;
		}
		case 5:
		{
			$num = "cinco";
			break;
		}
		case 4:
		{
			$num = "cuatro";
			break;
		}
		case 3:
		{
			$num = "tres";
			break;
		}
		case 2:
		{
			$num = "dos";

			break;
		}
		case 1:
		{
			$num = "uno";
			break;
		}
	}
	return $num;
}

function decena($numero)
{
	if ($numero >= 90 && $numero <= 99)
	{
		$num_letra = "noventa ";
		
		if ($numero > 90)
			$num_letra = $num_letra."y ".unidad($numero - 90);
	}
	else if ($numero >= 80 && $numero <= 89)
	{
		$num_letra = "ochenta ";
		
		if ($numero > 80)
			$num_letra = $num_letra."y ".unidad($numero - 80);
	}
	else if ($numero >= 70 && $numero <= 79)
	{
			$num_letra = "setenta ";
		
		if ($numero > 70)
			$num_letra = $num_letra."y ".unidad($numero - 70);
	}
	else if ($numero >= 60 && $numero <= 69)
	{
		$num_letra = "sesenta ";
		
		if ($numero > 60)
			$num_letra = $num_letra."y ".unidad($numero - 60);
	}
	else if ($numero >= 50 && $numero <= 59)
	{
		$num_letra = "cincuenta ";
		
		if ($numero > 50)
			$num_letra = $num_letra."y ".unidad($numero - 50);
	}
	else if ($numero >= 40 && $numero <= 49)
	{
		$num_letra = "cuarenta ";
		
		if ($numero > 40)
			$num_letra = $num_letra."y ".unidad($numero - 40);
	}
	else if ($numero >= 30 && $numero <= 39)
	{
		$num_letra = "treinta ";
		
		if ($numero > 30)
			$num_letra = $num_letra."y ".unidad($numero - 30);
	}
	else if ($numero >= 20 && $numero <= 29)
	{
		if ($numero == 20)
			$num_letra = "veinte ";
		else
			$num_letra = "veinti".unidad($numero - 20);
	}
	else if ($numero >= 10 && $numero <= 19)
	{
		switch ($numero)
		{
			case 10:
			{
				$num_letra = "diez ";
				break;
			}
			case 11:
			{
				$num_letra = "once ";
				break;
			}
			case 12:
			{
				$num_letra = "doce ";
				break;
			}
			case 13:
			{
				$num_letra = "trece ";
				break;
			}
			case 14:
			{
				$num_letra = "catorce ";
				break;
			}
			case 15:
			{
				$num_letra = "quince ";
				break;
			}
			case 16:
			{
				$num_letra = "dieciseis ";
				break;
			}
			case 17:
			{
				$num_letra = "diecisiete ";
				break;
			}
			case 18:
			{
				$num_letra = "dieciocho ";
				break;
			}
			case 19:
			{
				$num_letra = "diecinueve ";
				break;
			}
		}
	}
	else
		$num_letra = unidad($numero);

	return $num_letra;
}

function centena($numero)
{
	if ($numero >= 100)
	{
		if ($numero >= 900 & $numero <= 999)
		{
			$num_letra = "novecientos ";
			
			if ($numero > 900)
				$num_letra = $num_letra.decena($numero - 900);
		}
		else if ($numero >= 800 && $numero <= 899)
		{
			$num_letra = "ochocientos ";
			
			if ($numero > 800)
				$num_letra = $num_letra.decena($numero - 800);
		}
		else if ($numero >= 700 && $numero <= 799)
		{
			$num_letra = "setecientos ";
			
			if ($numero > 700)
				$num_letra = $num_letra.decena($numero - 700);
		}
		else if ($numero >= 600 && $numero <= 699)
		{
			$num_letra = "seiscientos ";
			
			if ($numero > 600)
				$num_letra = $num_letra.decena($numero - 600);
		}
		else if ($numero >= 500 && $numero <= 599)
		{
			$num_letra = "quinientos ";
			
			if ($numero > 500)
				$num_letra = $num_letra.decena($numero - 500);
		}
		else if ($numero >= 400 && $numero <= 499)
		{
			$num_letra = "cuatrocientos ";
			
			if ($numero > 400)
				$num_letra = $num_letra.decena($numero - 400);
		}
		else if ($numero >= 300 && $numero <= 399)
		{
			$num_letra = "trescientos ";
			
			if ($numero > 300)
				$num_letra = $num_letra.decena($numero - 300);
		}
		else if ($numero >= 200 && $numero <= 299)
		{
			$num_letra = "doscientos ";
			
			if ($numero > 200)
				$num_letra = $num_letra.decena($numero - 200);
		}
		else if ($numero >= 100 && $numero <= 199)
		{
			if ($numero == 100)
				$num_letra = "cien ";
			else
				$num_letra = "ciento ".decena($numero - 100);
		}
	}
	else
		$num_letra = decena($numero);
	
	return $num_letra;
}

function cien()
{
	global $importe_parcial;
	
	$parcial = 0; $car = 0;
	
	while (substr($importe_parcial, 0, 1) == 0)
		$importe_parcial = substr($importe_parcial, 1, strlen($importe_parcial) - 1);
	
	if ($importe_parcial >= 1 && $importe_parcial <= 9.99)
		$car = 1;
	else if ($importe_parcial >= 10 && $importe_parcial <= 99.99)
		$car = 2;
	else if ($importe_parcial >= 100 && $importe_parcial <= 999.99)
		$car = 3;
	
	$parcial = substr($importe_parcial, 0, $car);
	$importe_parcial = substr($importe_parcial, $car);
	
	$num_letra = centena($parcial).centimos();
	
	return $num_letra;
}

function cien_mil()
{
	global $importe_parcial;
	
	$parcial = 0; $car = 0;
	
	while (substr($importe_parcial, 0, 1) == 0)
		$importe_parcial = substr($importe_parcial, 1, strlen($importe_parcial) - 1);
	
	if ($importe_parcial >= 1000 && $importe_parcial <= 9999.99)
		$car = 1;
	else if ($importe_parcial >= 10000 && $importe_parcial <= 99999.99)
		$car = 2;
	else if ($importe_parcial >= 100000 && $importe_parcial <= 999999.99)
		$car = 3;
	
	$parcial = substr($importe_parcial, 0, $car);
	$importe_parcial = substr($importe_parcial, $car);
	
	if ($parcial > 0)
	{
		if ($parcial == 1)
			$num_letra = "mil ";
		else
			$num_letra = centena($parcial)." mil ";
	}
	
	return $num_letra;
}


function millon()
{
	global $importe_parcial;
	
	$parcial = 0; $car = 0;
	
	while (substr($importe_parcial, 0, 1) == 0)
		$importe_parcial = substr($importe_parcial, 1, strlen($importe_parcial) - 1);
	
	if ($importe_parcial >= 1000000 && $importe_parcial <= 9999999.99)
		$car = 1;
	else if ($importe_parcial >= 10000000 && $importe_parcial <= 99999999.99)
		$car = 2;
	else if ($importe_parcial >= 100000000 && $importe_parcial <= 999999999.99)
		$car = 3;
	
	$parcial = substr($importe_parcial, 0, $car);
	$importe_parcial = substr($importe_parcial, $car);
	
	if ($parcial == 1)
		$num_letras = "un millón ";
	else
		$num_letras = centena($parcial)." millones ";
	
	return $num_letras;
}

function convertir_a_letras($numero)
{
	global $importe_parcial;
	
	$importe_parcial = $numero;
	
	if ($numero < 1000000000)
	{
		if ($numero >= 1000000 && $numero <= 999999999.99)
			$num_letras = millon().cien_mil().cien();
		else if ($numero >= 1000 && $numero <= 999999.99)
			$num_letras = cien_mil().cien();
		else if ($numero >= 1 && $numero <= 999.99)
			$num_letras = cien();
		else if ($numero >= 0.01 && $numero <= 0.99)
		{
			if ($numero == 0.01)
				$num_letras = "un céntimo";
			else
				$num_letras = convertir_a_letras(($numero * 100)."/100")." céntimos";
		}
	}
	return $num_letras;
}
//	-------------------------------

//	-------------------------------
function getFechaFin($fecha, $dias) {
	$sumar=true;
	$dia_semana=getDiaSemana($fecha); $dia_semana++;
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
function getDiaSemana($fecha) {
	$fecha=str_replace("/","-",$fecha);
	list($dia,$mes,$anio)=explode("-",$fecha);
	return (((mktime ( 0, 0, 0, $mes, $dia, $anio) - mktime ( 0, 0, 0, 7, 17, 2006))/(60*60*24))+700000) % 7;
}
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
	$nombre[1]="enero";
	$nombre[2]="febrero";
	$nombre[3]="marzo";
	$nombre[4]="abril";
	$nombre[5]="mayo";
	$nombre[6]="junio";
	$nombre[7]="julio";
	$nombre[8]="agosto";
	$nombre[9]="septiembre";
	$nombre[10]="octubre";
	$nombre[11]="noviembre";
	$nombre[12]="diciembre";
	return $nombre[$mes];
}
//	-------------------------------

//	-------------------------------
function getDias($mes) {
	$nombre[1]="enero";
	$nombre[2]="febrero";
	$nombre[3]="marzo";
	$nombre[4]="abril";
	$nombre[5]="mayo";
	$nombre[6]="junio";
	$nombre[7]="julio";
	$nombre[8]="agosto";
	$nombre[9]="septiembre";
	$nombre[10]="octubre";
	$nombre[11]="noviembre";
	$nombre[12]="diciembre";
	return $nombre[$mes];
}
//	-------------------------------

//	-------------------------------
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
//	-------------------------------

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
//	------------------------------------

//	------------------------------------
function getFormula($_CODCONCEPTO) {
	$sql="SELECT Formula FROM pr_concepto WHERE CodConcepto='".$_CODCONCEPTO."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query)!=0) $field=mysql_fetch_array($query);
	return $field['Formula'];
}

function CONCEPTO($_CODPERSONA, $_CODCONCEPTO) {
	$_DIAS_SUELDO_ADELANTO = 12;
	$_DIAS_SUELDO_MES = 30;
	$_ADELANTO = "N";
	$_SUELDO = getSueldoBasico($_CODPERSONA);
	$_HIJOS = getHijosMenor18($_CODPERSONA);
	$_CURSOS = getNroCursos($_CODPERSONA);
	$_LIC = esGrado($_CODPERSONA, "LIC");
	$_ING = esGrado($_CODPERSONA, "ING");
	$_TSU = esGrado($_CODPERSONA, "TSU");
	$_ANIOS_SERVICIO = getAniosServicio($_CODPERSONA);
	$_JEFE = esJefe($_CODPERSONA);
	
	$formula=getFormula($_CODCONCEPTO);
	eval($formula);
	if ($_MONTO=="" && $_CANTIDAD=="") return "";
	elseif($_CANTIDAD!="") return $_CANTIDAD;
	elseif($_MONTO!="") return $_MONTO;
}

function getSueldoBasico($_CODPERSONA) {
	$sql="SELECT me.CodEmpleado, rp.NivelSalarial FROM mastempleado me INNER JOIN rh_puestos rp ON (me.CodCargo=rp.CodCargo) WHERE me.CodPersona='".$_CODPERSONA."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query)!=0) $field=mysql_fetch_array($query);
	return $field['NivelSalarial'];
}

function getFechaIngreso($_CODPERSONA) {
	$sql="SELECT Fingreso FROM mastempleado WHERE CodPersona='".$_CODPERSONA."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query)!=0) $field=mysql_fetch_array($query);
	list($a, $m, $d)=SPLIT( '[-.-]', $field['Fingreso']); $fingreso = "$d-$m-$a";
	return $fingreso;
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

function getNroCursos($_CODPERSONA) {
	$sql = "SELECT * FROM rh_empleado_cursos WHERE CodPersona = '".$_CODPERSONA."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	return $rows;
}

function esGrado($_CODPERSONA, $_GRADO) {
	$sql = "SELECT CodGradoInstruccion FROM rh_empleado_instruccion WHERE CodPersona = '".$_CODPERSONA."' AND CodGradoInstruccion = '".$_GRADO."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) return "S"; else return "N";
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

function getLunesMes() {
	//list($a, $m)=SPLIT('[/.-]', $_PERIODO); $periodo="01-".$m."-".$a;
	$periodo = date("d-m-Y");
	$primer_dia_semana = getDiaSemana($periodo);
	$dias_mes = getDiasMes($a, $m);
	if ($primer_dia_semana == 0 && ($dias_mes == 29 || $dias_mes == 30 || $dias_mes == 31)) $lunes = 5;
	elseif ($primer_dia_semana == 5 && $dias_mes == 31) $lunes = 5;
	elseif ($primer_dia_semana == 6 && ($dias_mes == 30 || $dias_mes == 31)) $lunes = 5;
	else $lunes = 4;
	return $lunes;
}
function esJefe($_CODPERSONA) {
	$sql = "SELECT me.CodEmpleado, rp.Grado FROM mastempleado me INNER JOIN rh_puestos rp ON (me.CodCargo = rp.CodCargo AND rp.Grado = '99') WHERE CodPersona = '".$_CODPERSONA."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) return "S"; else return "N";
}
//	------------------------------------
?>