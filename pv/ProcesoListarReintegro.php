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
		<td class="titulo">Reintegro | Listar Reintegro</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />
<?php
echo"<input type='hidden' id='regresar' name='regresar' value='ProcesoListarReintegro'/>";

include "gpresupuesto.php";
if(!$_POST){ $forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"]; $corganismo = "checked";}else {$forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"]; $corganismo = "checked";}

$MAXLIMIT=30;

$filtro = "";
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
include "ProcesoListarReintegro2.php";
$fecha=date("Y-m");
$ano=date("Y");
$sql="SELECT aj.Estado AS Estado,
		  pr.EjercicioPpto As Ejercicio,
		  aj.FechaAjuste As FAjuste,
		  aj.CodReintegro As CAjuste,
		  aj.CodPresupuesto As CPresupuesto,
		  aj.TotalAjuste AS Monto,
		  aj.Descripcion AS Descripcion,
		  aj.Organismo as Organismo
	 FROM 
			pv_ReintegroPresupuestario aj, 
			pv_presupuesto pr
	WHERE 
			aj.CodPresupuesto = pr.CodPresupuesto  $filtro
 ORDER BY aj.CodPresupuesto, aj.CodReintegro"; //echo $sql;
$qry=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($qry);

include "ProsesoListarReintegroContenido.php";

$registros=$rows;
if($registros!=0){ 
for($i=0; $i<$registros; $i++){
   $field=mysql_fetch_array($qry); 
   if($field['Estado']=='PR'){$estado='Preparado';}else{if($field['Estado']=='AP'){$estado='Aprobado';}else{$estado='Anulado';}}
   $monto=$field['Monto']; $monto=number_format($monto,2,',','.');
   $id = $field['CAjuste'].'|'.$field['FAjuste'].'|'.$field['Organismo'].'|'.$field['CPresupuesto'];
  echo "
  <tr class='trListaBody' onclick='mClk(this,\"registro\");' id='$id'>
  <td align='center'>".$field['CPresupuesto']."</td>";
  list($a, $m, $d)=SPLIT( '[/.-]', $field['FAjuste']); $fAjuste=$d.'-'.$m.'-'.$a;
  list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaFin']); $fFin=$d.'-'.$m.'-'.$a;
  echo"
  <td align='center'>".$field['CAjuste']."</td>
  <td align='center'>$fAjuste</td>
  <td>".$field['Descripcion']."</td>
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
