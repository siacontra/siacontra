<?
//// PROCESO APROBAR ACTIVO INCORPORACION
$accion = 'corre';
include ("fphp.php");
connect();

if ($accion==""){

$parametro[0]='CONFACTIVOPOR';
$parametro[1]='APROBACTIVOPOR';
$valor = 2;
for($i=0; $i<$valor; $i++){

	$scon = "select 
                  mp.ValorParam,
				  b.CodPersona,
				  d.DescripCargo
			from 
			      mastparametros mp 
			      inner join mastdependencias a on (a.CodDependencia=mp.ValorParam) 
				  inner join mastpersonas b on (b.CodPersona=a.CodPersona) 
				  inner join mastempleado c on (c.CodPersona=a.CodPersona)
				  inner join rh_puestos d on (d.CodCargo=c.CodCargo)
			where 
			      mp.ParametroClave='".$parametro[$i]."'"; //echo $scon;
	$qcon = mysql_query($scon) or die ($scon.mysql_error());
	$rcon = mysql_num_rows($qcon); //echo $rcon.'****';

if($rcon!=0)$fcon = mysql_fetch_array($qcon);

$scon2 = "select 
				max(Secuencia) 
			from 
				rh_empleadonivelacion 
			where 
				CodPersona='".$fcon['CodPersona']."'";
$qcon2 = mysql_query($scon2) or die ($scon2.mysql_error());
$rcon2 = mysql_num_rows($qcon2);//echo $rcon2;
if($rcon2!=0) $fcon2 = mysql_fetch_array($qcon2);

$scon3 = "Select 
				a.CodCargo,
				a.CodPersona,
				b.DescripCargo
		   from 
		   		rh_empleadonivelacion a 
				inner join rh_puestos b on (b.CodCargo=a.CodCargo)
		 where 
		 		a.Secuencia='".$fcon2['0']."' and  
				a.CodPersona='".$fcon['CodPersona']."'"; 
$qcon3 = mysql_query($scon3) or die ($scon3.mysql_error());
$rcon3 = mysql_num_rows($qcon3);
if($rcon3!=0)$fcon3 = mysql_fetch_array($qcon3);

if($cont==1){
	$codConformado = $fcon['CodPersona'];
	$cargoConformado = $fcon3['CodCargo'];
	echo"<tr style='border:2; bordercolor='#000000'>
        <td>".$fcon['ValorParam']."</td><td>".$codConformado."</td><td>".$cargoConformado."</td><td>".$fcon3['DescripCargo']."</td>
      </tr>";
}else{
    $codAprobado = $fcon['CodPersona'];
	$cargoAprobado = $fcon3['CodCargo'];
	echo"<tr style='border:2; bordercolor='#000000'>
        <td>".$fcon['ValorParam'].'****'."</td><td>".$codAprobado."</td><td>".$cargoAprobado."</td><td>".$fcon3['DescripCargo']."</td>
      </tr>";
}
  /*echo"<tr style='border:2; bordercolor='#000000'>
        <td>".$fcon['ValorParam']."</td><td>".$fcon['CodPersona']."</td><td>".$fcon3['CodCargo']."</td><td>".$fcon3['DescripCargo']."</td>
      </tr>";*/
}
//// ------------------------------------------------------------------------------
//// ------------------------------- funcion que devuelve el dia de la semana
function nameDate($fecha='')//formato: 00/00/0000
{ 	$fecha= empty($fecha)?date('d/m/Y'):$fecha;
	$dias = array('domingo','lunes','martes','miércoles','jueves','viernes','sábado');
	$dd   = explode('/',$fecha);
	$ts   = mktime(0,0,0,$dd[1],$dd[0],$dd[2]);
	//return $dias[date('w',$ts)].'/'.date('m',$ts).'/'.date('Y',$ts);
	return $dias[date('w',$ts)];
}
//echo nameDate('16/08/2012');
$mesNombre = nameDate('16/08/2012');
echo $mesNombre;

/*  Función dia_semana by PaToRoCo (www.patoroco.net)
Se permite la distribución total y modificación de la función, siempre que se nombre al autor */

function dia_semana($dia, $mes, $ano){
    $dias = array('Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado');
    return $dias[date('w', mktime(0, 0, 0, $mes, $dia, $ano))];
}

$fecha = "05-08-2004"; //5 agosto de 2004 por ejemplo 

$fechats = strtotime($fecha); //a timestamp

//el parametro w en la funcion date indica que queremos el dia de la semana
//lo devuelve en numero 0 domingo, 1 lunes,....
switch (date('w', $fechats)){
    case 0: echo "Domingo"; break;
    case 1: echo "Lunes"; break;
    case 2: echo "Martes"; break;
    case 3: echo "Miercoles"; break;
    case 4: echo "Jueves"; break;
    case 5: echo "Viernes"; break;
    case 6: echo "Sabado"; break;
}  
}
if($accion=="corre"){
  $sql = "select * from af_activo";
  $qry = mysql_query($sql) or die ($sql.mysql_error());
  $row = mysql_num_rows($qry);
  if($row!=0){
     for($i=0; $i<$row; $i++){
	   $field = mysql_fetch_array($qry);
	   $s_insert = "insert into af_historicotransaccion(CodOrganismo,
	   													Activo,
														Secuencia,
														CodDependencia,
														CentroCosto,
														CodigoInterno,
														SituacionActivo,
														CodTipoMovimiento,
														Ubicacion,
														InternoExternoFlag,
														MotivoTraslado,
														FechaIngreso,
														FechaTransaccion,
														PeriodoIngreso,
														PeriodoTransaccion,
														NumeroOrden,
														OrdenSecuencia,
														MontoActivo,
														UltimoUsuario,
														UltimaFechaModif) 
												  values('".$field['CodOrganismo']."',
												         '".$field['Activo']."',
														 '1',
														 '".$field['CodDependencia']."',
														 '".$field['CentroCosto']."',
														 '".$field['CodigoInterno']."',
														 '".$field['SituacionActivo']."',
														 '01',
														 '".$field['Ubicacion']."',
														 'IN',
														 '01',
														 '".$field['FechaIngreso']."',
														 '".date("Y-m-d")."',
														 '".$field['PeriodoIngreso']."',
														 '".date("Y-m")."',
														 '".$field['NumeroOrden']."',
														 '1',
														 '".$field['MontoLocal']."',
														 'CRODRIGUEZ',
														 '".date("Y-m-d H:i:s")."') ";
	 $q_insert= mysql_query($s_insert) or die ($s_insert.mysql_error());
	 }
  }
  
}
?>