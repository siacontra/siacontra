<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include ("fphp.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('02', $concepto);
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" language="javascript" src="fscript01.js"></script>
<script type="text/javascript" language="javascript" src="r_fscript.js"></script>
</head>
<body>
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<table width="100%" cellspacing="0" cellpadding="0">
 <tr>
  <td class="titulo">Procesos | Cierre Mes</td>
  <td align="right">
   <a class="cerrar" href="framemain.php">[cerrar]</a>
  </td>
 </tr>
</table>
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<hr width="100%" color="#333333" />
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<?php 
if(!$_POST) $forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
if(!$_POST){ $fejercicioppto = date("Y"); $cejercicioppto = "checked";}
if(!$_POST){ $fperiodo = date("Y-m"); $cperiodo = "checked";}

$MAXLIMIT=30;
$filtro = "";
if ($forganismo!=""){$filtro.= " AND (Organismo = '".$forganismo."')"; $corganismo = "checked"; } else $dorganismo = "disabled";
if ($fejercicioppto!=""){$filtro.= " AND (EjercicioPpto = '".$fejercicioppto."')"; $cejercicioppto = "checked"; } else $dejercicioppto = "disabled";
if ($fnropresupuesto!=""){$filtro.= " AND (CodPresupuesto = '".$fnropresupuesto."')"; $cnropresupuesto = "checked"; } else $dnropresupuesto = "disabled";
if ($fperiodo!=""){ $filtro.="AND (Periodo = '".$fperiodo."')"; $cperiodo = "checked";}else $dperiodo = "disabled";


?>
<?php echo"
<form name='frmentrada' action='proceso_cierremes.php?limit=0' method='POST'>
<input type='hidden' name='limit' id='limit' value='".$limit."'>
<input type='hidden' name='registros' id='registros' value='".$registros."'/>
<input type='hidden' name='usuarioActual' id='usuarioActual' value='".$_SESSION['USUARIO_ACTUAL']."'/>

<div class='divBorder' style='width:900px;'>
<table width='900' class='tblFiltro'>
<tr>
<td width='125' align='right'>Organismo:</td>
<td>
<input type='checkbox' name='chkorganismo' id='chkorganismo' value='1' $corganismo onclick='this.checked=true' />
<select name='forganismo' id='forganismo' class='selectBig' $dorganismo onchange='getFOptions_2(this.id, \"fanteproyecto\", \"chknanteproyecto\");'>";
	getOrganismos($obj[2], 3, $_SESSION[ORGANISMO_ACTUAL]);
	echo "
</select>
</td>
<td width='125' align='right'>Ejercicio Ppto.:</td>
<td>
<input type='checkbox' name='chkejercicioPpto' id='chkejercicioPpto' value='1' $cejercicioppto onclick='this.checked=true' />
<input type='text' name='fejercicioppto' id='fejercicioppto' size='6' maxlength='4' $dejercicioppto value='$fejercicioppto'/>
</td>
</tr>
<tr>
<td width='125' align='right'>Nro. Presupuesto:</td>
<td>
<input type='checkbox' name='chknropresupuesto' id='chknropresupuesto' value='1' $cnropresupuesto onclick='enabledCierreNroPresupuesto(this.form);'/>
<input name='fnropresupuesto'  id='fnropresupuesto' type='text' size='5' maxlength='4' style='text-align:right' value='' $dnropresupuesto/>";?><input type="button" name="btnropresup" id="btnropresup" value="..." onclick="cargarVentana(this.form, 'listado_presupuestos.php?limit=0&campo=1', 'height=500, width=850, left=200, top=200, resizable=yes');" disabled="false"/>*
<?php echo" </td>
<td width='125' align='right'>Periodo:</td>
<td>
<input type='checkbox' name='chkperiodo' id='chkperiodo' value='1' $cperiodo onclick='enabledCierrePeriodo(this.form);' />
<input type='text' name='fperiodo' id='fperiodo'size='6' maxlength='7' $dperiodo value='$fperiodo'/>
</td>
</tr>

</table>
</div>
<center><input type='submit' name='btBuscar' value='Buscar' onclick='cargarCierreMesPresupuestario(this.form);'></center>
<br /><div style='width:900;' class='divDivision'>Cierre de Mes</div>
<form/><br />";
?>
<table width="900" class="tblBotones">
<tr>
<td><div id="rows"></div></td>
<td width="250">
<?php 
echo"<input type='hidden' name='regresar' id='regresar' value='cpi_docinternoslista'/>";
?>
		</td>
		<td align="right">
<input type="button" id="btEjecutar" name="btejecutar"  value="Ejecutar Cierre" onclick="ProcesoEjecutarCierre(this.form);"/>
		</td>
	</tr>
</table>
<input type="hidden" name="registro" id="registro" />
<div style="width:900px" class="divFormCaption"></div>
<center>
<iframe name="proceso_cierremespdf" id="proceso_cierremespdf" style="border:solid 1px #CDCDCD; width:900px; height:400px; visibility:false; display:false;" ></iframe>
</center>
<? echo "
<script type='text/javascript' language='javascript'>
	Ejecutarcierre(\"$_UPDATE\");
</script>";?>
<form/>
</body>
</html>
