<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
extract($_POST);
extract($_GET);
//	------------------------------------
include("../lib/fphp.php");
include("../lib/lg_fphp.php");
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('04', $concepto);
//	------------------------------------
if ($filtrar == "default") {
	$forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$fperiodo = date("Y-m");
}
if ($forganismo != "") { $corganismo = "checked"; $filtro.=" AND (lr.CodOrganismo = '".$forganismo."')"; } else $dorganismo = "disabled"; 
if ($fperiodo != "") { $cperiodo = "checked"; $filtro.=" AND (lr.Periodo = '".$fperiodo."')"; } else $dperiodo = "disabled"; 
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/estilo.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="../js/funciones.js"></script>
<script type="text/javascript" language="javascript" src="../js/lg_funciones.js"></script>
<script type="text/javascript" language="javascript" src="../js/lg_fscript.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Cierre Mensual</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="lg_cierre_mensual.php" method="post" onsubmit="return cierre_mensual(this);">
<input type="hidden" name="registro" id="registro" />
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
	<tr>
		<td align="right" width="125">Organismo:</td>
		<td>
			<input type="checkbox" <?=$corganismo?> onclick="this.checked=!this.checked;" />
			<select name="forganismo" id="forganismo" <?=$dorganismo?> style="width:300px;">
				<option value=""></option>
				<?=getOrganismos($forganismo, 3);?>
			</select>
		</td>
		<td align="right">Periodo:</td>
		<td>
			<input type="checkbox" <?=$cperiodo?> onclick="this.checked=!this.checked;" />
			<input type="text" name="fperiodo" id="fperiodo" maxlength="7" style="width:60px;" value="<?=$fperiodo?>" <?=$dperiodo?> />
		</td>
	</tr>
</table>
</div>
<center><input type="submit" value="Generar"></center>
</form><br />

<table width="1000" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <div class="header">
            <ul id="tab">
            <!-- CSS Tabs -->
            <li id="li1" class="current" onclick="currentTab('tab', this);">
            	<a href="#" onclick="mostrarTab('tab', '1', 3)">Datos para Precio Promedio</a>
            </li>
            <li id="li2" onclick="currentTab('tab', this);">
            	<a href="#" onclick="mostrarTab('tab', '2', 3);">Errores Detectados</a>
            </li>
            <li id="li3" onclick="currentTab('tab', this);">
            	<a href="#" onclick="mostrarTab('tab', '3', 3);">Soporte de Cambio de Precios</a>
            </li>
            </ul>
            </div>
        </td>
    </tr>
</table>

<div id="tab1" style="display:block;">
<center>
<div style="overflow:scroll; width:1000px; height:400px;">
<table width="1900" class="tblLista">
	<thead>
	<tr class="trListaHead">
		<th scope="col" width="100">Item</th>
		<th scope="col" width="150">Almac&eacute;n</th>
		<th scope="col" width="75">Fecha</th>
		<th scope="col" width="100">Transacci&oacute;n #</th>
		<th scope="col" width="25">#</th>
		<th scope="col">Transacci&oacute;n</th>
		<th scope="col" width="100">Orden de Compra</th>
		<th scope="col" width="75">Cantidad</th>
		<th scope="col" width="50">Uni.</th>
		<th scope="col" width="100">Precio Unit.</th>
		<th scope="col" width="100">Total</th>
		<th scope="col" width="50">O/C Uni.</th>
		<th scope="col" width="100">O/C Precio Unit.</th>
		<th scope="col" width="100">O/C Precio Unit. Otros</th>
		<th scope="col" width="100">O/C Monto Afecto</th>
		<th scope="col" width="100">O/C Monto IGV</th>
		<th scope="col" width="100">Exento Imp.</th>
		<th scope="col" width="100">Gu&iacute;a Proveedor</th>
	</tr>
    </thead>
    
    <tbody>
    </tbody>
</table>
</div>
</center>
</div>

<div id="tab2" style="display:none;">
<center>
<table>
	<tr>
    	<td>
        	<div style="overflow:scroll; width:350px; height:200px;">
            <table width="100%" class="tblLista">
                <thead>
                <tr class="trListaHead">
                    <th scope="col" width="100">Item</th>
                    <th scope="col">Descripci&oacute;n</th>
                    <th scope="col" width="75">Stock Actual</th>
                </tr>
                </thead>
                
                <tbody>
                
                </tbody>
            </table>
            </div>
        </td>
    	
        <td rowspan="2">
        	<div style="overflow:scroll; width:650px; height:405px;">
            <table width="100%" class="tblLista">
                <thead>
                <tr class="trListaHead">
                    <th scope="col" width="100">Nro. Orden</th>
                    <th scope="col" width="125">Gu&iacute;a Proveedor</th>
                    <th scope="col">Proveedor</th>
                    <th scope="col" width="100">Item</th>
                    <th scope="col" width="75">Cantidad</th>
                </tr>
                </thead>
                
                <tbody>
                
                </tbody>
            </table>
            </div>
        </td>
    </tr>
    
	<tr>
    	<td>
        	<div style="overflow:scroll; width:350px; height:200px;">
            <table width="100%" class="tblLista">
                <thead>
                <tr class="trListaHead">
                    <th scope="col" width="100">Item</th>
                    <th scope="col">Descripci&oacute;n</th>
                    <th scope="col" width="75">Fecha</th>
                </tr>
                </thead>
                
                <tbody>
                
                </tbody>
            </table>
            </div>
        </td>
    </tr>
</table>
</center>
</div>

<div id="tab3" style="display:none;">
<center>
<iframe name="iReporte" id="iReporte" style="border:solid 1px #CDCDCD; width:1000px; height:400px;"></iframe>
</center>
</div>

<script type="text/javascript" language="javascript">
	totalRegistros(parseInt(<?=$rows?>), "<?=$_ADMIN?>", "<?=$_INSERT?>", "<?=$_UPDATE?>", "<?=$_DELETE?>");
</script>
</body>
</html>