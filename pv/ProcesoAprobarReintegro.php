<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location:index.php");
//	------------------------------------
include("fphp.php"); 
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('01', $concepto);
//	------------------------------------
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
</head>
<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Reintegro | Aprobar Reintegro</td>
 <td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />
<?php
include "gpresupuesto.php";
//////////*************** MUESTRO LOS FILTROS ***********////////////////////////////

if((!$_POST)or($volver=='0')) $forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];

$MAXLIMIT=30;
$filtro = " asd";
if ($forganismo != "") { $filtro .= " AND (aj.Organismo = '".$forganismo."')"; $corganismo = "checked"; } else $dorganismo = "disabled";
if ($fejercicio != "") { $filtro .= " AND (pr.EjercicioPpto = '".$fejercicio."')"; $cejercicio = "checked"; } else $dejercicio = "disabled";
if ($fnpresupuesto != "") { $filtro .= " AND (pr.CodPresupuesto = '".$fnpresupuesto."')"; $cnpresupuesto = "checked"; } else $dnpresupuesto = "disabled";
if ($fstatus != "") { $filtro .= " AND (aj.Estado = '".$fstatus."')"; $cstatus = "checked"; } else $dstatus = "disabled";
if ($fnajuste != "") { $filtro .= " AND (aj.CodReintegro = '".$fnajuste."')"; $cnajuste = "checked"; } else $dnajuste = "disabled";
if ($fdesde != "" || $fhasta != "") {
	if ($fdesde != "") $filtro .= " AND (aj.FechaAjuste >= '".$fdesde."')";
	if ($fhasta != "") $filtro .= " AND (aj.FechaAjuste <= '".$fhasta."')"; 
	$cajuste = "checked"; 
} else $dajuste = "disabled";
if ($ftajuste != "") { $filtro .= " AND (aj.TipoAjuste = '".$ftajuste."')"; $ctajuste = "checked"; } else $dtajuste = "disabled";
//	-------------------------------------------------------------------------------
$MAXLIMIT=30;
include "ProcesoAprobarReintegro2.php";
$ano=date("Y");
$sql="SELECT aj.Estado AS Estado,
		  pr.EjercicioPpto As Ejercicio,
		  aj.FechaAjuste As FAjuste,
		  aj.CodReintegro As CAReintegro,
		  aj.CodPresupuesto As CPresupuesto,
		  aj.TotalAjuste AS Monto,
                  aj.Organismo AS Organismo
	 FROM 
			pv_ReintegroPresupuestario aj, 
			pv_presupuesto pr
	WHERE 
			aj.CodPresupuesto = pr.CodPresupuesto AND 
			aj.Estado='PR' $filtro
 ORDER BY aj.CodPresupuesto, aj.CodReintegro"; //echo $sql;
$qry=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($qry);

include "ProcesoAprobarReintegroContenidoasdasd.php";
$registros=$rows;
if($registros!=0){ 
for($i=0; $i<$registros; $i++){
   $field=mysql_fetch_array($qry); 
	$SQL="SELECT p.cod_partida,p.partida1 as partida, p.cod_tipoCuenta as tcuenta 
		  FROM pv_partida p, pv_ReintegroPresupuestariodet a 
		 WHERE a.cod_partida=p.cod_Partida AND 
			   a.CodPresupuesto='".$field['CPresupuesto']."' AND 
			   a.CodReintegro='".$field['CAReintegro']."'";
   $QRY=mysql_query($SQL) or die ($SQL.mysql_error());
   $FIELD=mysql_fetch_array($QRY);
   $mostrar='00.00.00';
   $mostrar2=$FIELD['tcuenta'].$FIELD['partida'].".".$mostrar;
   
   if($field['Estado']=='PR'){$estado='Preparado';}else{if($field['Estado']=='AP'){$estado=Aprobado;}else{$estado=Anulado;}}
   $monto=$field['Monto']; $monto=number_format($monto,2,',','.');
   
   $id = $field['CAReintegro'].'|'.$field['FAjuste'].'|'.$field['Organismo'].'|'.$field['CPresupuesto'];
  echo "
  <tr class='trListaBody' onclick='mClk(this,\"registro\");' id='$id'>
  <td align='center'>".$field['CPresupuesto']."</td>";
  list($a, $m, $d)=SPLIT( '[/.-]', $field['FAjuste']); $fAjuste=$d.'-'.$m.'-'.$a;
  list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaFin']); $fFin=$d.'-'.$m.'-'.$a;
  echo"
  <td align='center'>".$field['CAReintegro']."</td>
  <td align='center'>$mostrar2</td>
  <td align='centeasdasdr'>$fAjuste</td>
  <td align='center'>$estado</td>
  <td align='center'>$monto</td>
  </tr>";
}}
$rows=(int)$rows;
echo "
<script type='text/javascript' language='javascript'>
	totalAnteproyectos($registros,\"$_INSERT\", \"$_UPDATE\", \"$_DELETE\");
	totalLotes($registros, $rows, ".$limit.");
</script>";	
//	-------------------------------------------------------------------------------
?>
</table>
</div>
</td></tr></table>
</body>
</html>
