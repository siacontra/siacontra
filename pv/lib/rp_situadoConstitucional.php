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
<script type="text/javascript" language="javascript" src="r_fscript.js"></script>
</head>
<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Reporte | Situado Constitucional</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />
<?php
//// ----------------------------------------------------------------------------------------
if(!$_POST) $forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
if(!$_POST) $fejercicio = date("Y");

$MAXLIMIT=30;
$filtro = "";
if ($forganismo != "") { $filtro .= " AND (Organismo = '".$forganismo."')"; $corganismo = "checked"; } else $dorganismo = "disabled";
if ($fejercicio != "") { $filtro .= " AND (EjercicioPpto = '".$fejercicio."')"; $cejercicio = "checked"; } else $dejercicio = "disabled";
if ($fnpresupuesto != "") { $filtro .= " AND (CodPresupuesto = '".$fnpresupuesto."')"; $cnpresupuesto = "checked"; } else $dnpresupuesto = "disabled";
if ($fP_desde != "" || $fP_hasta != "")	$cPeriodo = "checked"; else {$fP_desde=$fejercicio.'-'.date('m');$fP_hasta=$fejercicio.'-'.date('m');$cPeriodo = "checked";}
//	-------------------------------------------------------------------------------
$MAXLIMIT=30;
//// ------------------------------------------------------------------------------
echo "
<form name='frmentrada' action='rp_situadoConstitucional.php?limit=0' method='POST'>";
echo" <input type='hidden' name='limit' id='limit' value='".$limit."'/>
      <input type='hidden' name='registros' id='registros' value='".$registros."'/>

<div class='divBorder' style='width:900px;'>
<table width='900' class='tblFiltro'>
<tr>
 <td width='90' align='right'>Organismo:</td>
 <td width='150'>
  <input type='checkbox' name='chkorganismo' id='chkorganismo' value='1' $corganismo onclick='this.checked=true' />
  <select name='forganismo' id='forganismo' class='selectBig' $dorganismo onchange='getFOptions_2(this.id, \"fnanteproyecto\", \"chknanteproyecto\");'>";
		getOrganismos($forganismo, 3, $_SESSION[ORGANISMO_ACTUAL]);
		echo "
   </select>
 </td>
 <td width='80' align='right'>P. Ejecuci&oacute;n:</td>
 <td>
  <input type='checkbox' name='chkejercicio' id='chkejercicio' value='1' $cejercicio onclick='enabledPEjercicio(this.form);' />
  <input type='text' name='fejercicio' id='fejercicio' size='6' maxlength='4' $dejercicio value='$fejercicio' />
  </td>
</tr>
<tr>
  <td width='90' align='right'>Nro. Presupuesto:</td>
  <td width='100'>
	<input type='checkbox' name='chknpresupuesto' id='chknpresupuesto' value='1' $cnpresupuesto onclick='enabledPNpresupuesto(this.form);' />
	<input type='text' name='fnpresupuesto' id='fnpresupuesto' size='6' maxlength='4' $dnpresupuesto value='$fnpresupuesto' />
  </td>
  <td width='20' align='right'>Per&iacute;odo:</td>
  <td width='150' align='left'>
    	<input type='checkbox' name='chkPeriodo' id='chkPeriodo' value='1' $cPeriodo onclick='enabledRpPeriodoEjecucionPresupuestaria(this.form);' />
    	<input type='text' name='fP_desde' id='fP_desde' size='6' maxlength='7' $dPeriodo value='$fP_desde'/> -   
		<input type='text' name='fP_hasta' id='fP_hasta' size='6' maxlength='7' $dPeriodo value='$fP_hasta'/>
  </td>
</tr>

<tr><td height='2'></td></tr>
</table>
</div>
<center><input type='submit' name='btBuscar' value='Buscar' ></center>
<br />
	<div class='divDivision'></div>
<br />"; 
//	-------------------------------------------------------------------------------
//// ------------------------------------------------------------------------------
//// ------------------------------------------------------------------------------
?>
<div style="width:1000px" class="divFormCaption"> </div>
<center>


<img src="rp_situadoConstitucionalGrafica.php?organismo=forganismo&ejercicio=<?=$fejercicio;?>&presupuesto=fnpresupuesto&periodo=<?=$fP_desde;?>&periodohasta=<?=$fP_hasta;?>"/>

</center>
<form/>

<script> 
</script>
</body>
</html>
