<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include ("fphp.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('01', $concepto);
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
</head>
<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Ajuste | Listar Presupuesto</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />
<?php
include "gmsector.php";
//////////*************** MUESTRO LOS FILTROS ***********////////////////////////////

if((!$_POST)or($volver=='0')) $forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
if(!$_POST) $fejercicio = date("Y");
$MAXLIMIT=30;
$filtro = "";
if ($forganismo != "") { $filtro .= " AND (Organismo = '".$forganismo."')"; $corganismo = "checked"; } else $dorganismo = "disabled";
//if ($fejercicio != "") { $filtro .= " AND (EjercicioPpto = '".$fejercicio."')"; $cejercicio = "checked"; } else $dejercicio = "disabled";
if ($fnpresupuesto != "") { $filtro .= " AND (CodPresupuesto = '".$fnpresupuesto."')"; $cnpresupuesto = "checked"; } else $dnpresupuesto = "disabled";
if ($fstatus != "") { $filtro .= " AND (Estado = '".$fstatus."')"; $cstatus = "checked"; } else $dstatus = "disabled";
if ($fpreparado != "") { $filtro .= " AND (Estado = '".$fpreparado."')"; $cpreparado = "checked"; } else $dpreparado = "disabled";
//if ($fnajuste != "") { $filtro .= " AND (aj.CodAjuste = '".$fnajuste."')"; $cnajuste = "checked"; } else $dnajuste = "disabled";
/*if ($fdesde != "" || $fhasta != "") {
	if ($fdesde != "") $filtro .= " AND (FechaAjuste >= '".$fdesde."')";
	if ($fhasta != "") $filtro .= " AND (FechaAjuste <= '".$fhasta."')"; 
	$cajuste = "checked"; 
} else $dajuste = "disabled";*/
if ($ftajuste != "") { $filtro .= " AND (TipoAjuste = '".$ftajuste."')"; $ctajuste = "checked"; } else $dtajuste = "disabled";
//	-------------------------------------------------------------------------------
$MAXLIMIT=30;
include "presupuesto_listar4.php";
$ano=date("Y");
 $sql="SELECT * FROM pv_presupuesto 
		       WHERE Organismo='".$_SESSION['ORGANISMO_ACTUAL']."' $filtro
		    ORDER BY CodPresupuesto";
			 //echo $sql;
$qry=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($qry);

include "presupuesto_listarcontenido2.php";
$registros=$rows;
if($registros!=0){ 
  	for($i=0; $i<$registros; $i++){
  	   $field=mysql_fetch_array($qry);
	   if($field['Estado']==AP){$est=Aprobado;}
	   $montoP=$field[MontoAprobado];
	   $montoP=number_format($montoP,2,',','.');
	   $id = $field['CodPresupuesto'].'-'.$field['Organismo'].'-'.$field['EjercicioPpto'];
      echo "
      <tr class='trListaBody' onclick='mClk(this,\"registro\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='$id'>
	  <td align='center'>".$field['CodPresupuesto']."</td>
	  <td align='center'>".$field['PreparadoPor']."</td>";
	  list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaInicio']); $fInicio=$d.'-'.$m.'-'.$a;
      list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaFin']); $fFin=$d.'-'.$m.'-'.$a;
      echo"
	  <td align='center'>".$field['EjercicioPpto']."</td>
	  <td align='center'>$fInicio</td>
	  <td align='center'>$fFin</td>
	  <td align='center'>$montoP</td>
	  <td align='center'>$est</td>
      </tr>";
  }}
$rows=(int)$rows;
echo "
<script type='text/javascript' language='javascript'>
	totalAnteproyectos($registros,\"$_INSERT\", \"$_UPDATE\", \"$_DELETE\");
	totalLotes($registros, $rows, ".$limit.");
</script>";
echo "<input type='hidden' name='regresar' id='regresar' value='".$regresar."' />";	

//	-------------------------------------------------------------------------------
?>

</table>
</body>
</html>
