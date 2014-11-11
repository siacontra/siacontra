<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("../lib/fphp.php");
include("../lib/ac_fphp.php");
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('06', $concepto);
//	------------------------------------
if ($filtrar == "DEFAULT") {
	$forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$fperiodo = date("Y");
}
//	------------------------------------
$filtro = "";
if ($forganismo != "") { $corganismo = "checked"; $filtro .= " AND vg.CodOrganismo = '".$forganismo."'"; } else $dorganismo = "disabled";
if ($fperiodo != "") { $cperiodo = "checked"; $filtro .= " AND vg.Periodo = '".$fperiodo."'"; } else $dperiodo = "disabled";
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/estilo.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="../js/funciones.js"></script>
<script type="text/javascript" language="javascript" src="../js/ac_fscript.js"></script>
</head>

<body onload="document.getElementById('periodo').focus();">
<div id="bloqueo" class="divBloqueo"></div>
<div id="cargando" class="divCargando">
<table>
	<tr>
    	<td valign="middle" style="height:50px;">
			<img src="../imagenes/iconos/cargando.gif" /><br />Procesando...
        </td>
    </tr>
</table>
</div>

<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Maestro de Vouchers Generados</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="ac_ultimos_vouchers_generados.php" method="POST">
<div class="divBorder" style="width:900px;">
<table width="900" class="tblFiltro">
	<tr>
		<td width="125" align="right">Organismo:</td>
		<td>
			<input type="checkbox" name="chkorganismo" id="chkorganismo" value="1" <?=$corganismo?> onclick="this.checked=!this.checked" />
			<select name="forganismo" id="forganismo" style="width:300px;" <?=$dorganismo?>>
				<?=getOrganismos($forganismo, 3);?>
			</select>
		</td>
		<td width="125" align="right">Per&iacute;odo:</td>
		<td>
			<input type="checkbox" name="chkperiodo" id="chkperiodo" value="1" <?=$cperiodo?> onclick="chkFiltro(this.checked, 'fperiodo');" />
			<input type="text" name="fperiodo" id="fperiodo" value="<?=$fperiodo?>" maxlength="4" style="width:65px;" <?=$dperiodo?> />
		</td>
	</tr>
</table>
</div>
<center><input type="submit" name="btBuscar" value="Buscar"></center>

<input type="hidden" name="registro" id="registro" />
<table width="900" class="tblBotones">
	<tr>
		<td align="right">
			<input type="button" class="btLista" id="btAceptar" value="Aceptar" onclick="ultimos_vouchers_generados(this.form);" />
		</td>
	</tr>
</table>

<table align="center"><tr><td align="center"><div style="overflow:scroll; width:900px; height:400px;">
<table width="100%" class="tblLista">
 <thead>
	<tr class="trListaHead">
		<th width="50" scope="col">A&ntilde;o</th>
		<th scope="col">Organismo</th>
		<th width="100" scope="col">Aplicaci&oacute;n</th>
		<th width="100" scope="col">Voucher</th>
		<th width="50" scope="col">01</th>
		<th width="50" scope="col">02</th>
		<th width="50" scope="col">03</th>
		<th width="50" scope="col">04</th>
		<th width="50" scope="col">05</th>
		<th width="50" scope="col">06</th>
		<th width="50" scope="col">07</th>
		<th width="50" scope="col">08</th>
		<th width="50" scope="col">09</th>
		<th width="50" scope="col">10</th>
		<th width="50" scope="col">11</th>
		<th width="50" scope="col">12</th>
	</tr>
   </thead>
	<?php
	//	CONSULTO LA TABLA
	$sql = "SELECT
				vg.*,
				o.Organismo,
				v.Descripcion AS NomVoucher
			FROM
				ac_vouchersgenerados vg
				INNER JOIN mastorganismos o ON (vg.CodOrganismo = o.CodOrganismo)
				INNER JOIN mastaplicaciones a ON (vg.CodAplicacion = a.CodAplicacion)
				INNER JOIN ac_voucher v ON (vg.CodVoucher = v.CodVoucher)
			WHERE 1 $filtro
			ORDER BY vg.Periodo, vg.CodOrganismo, vg.CodAplicacion, vg.CodVoucher";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	while ($field = mysql_fetch_array($query)) {
		?>
		<tr class="trListaBody">
			<td align="center"><?=$field['Periodo']?></td>
			<td align="center"><?=$field['CodOrganismo']?></td>
			<td align="center"><?=htmlentities($field['NomAplicacion'])?></td>
			<td align="center"><?=htmlentities($field['NomVoucher'])?></td>
			<td align="center"><input type="text" name="mes01" style="width:90%;" value="<?=$field['Mes01']?>" /></td>
			<td align="center"><input type="text" name="mes02" style="width:90%;" value="<?=$field['Mes02']?>" /></td>
			<td align="center"><input type="text" name="mes03" style="width:90%;" value="<?=$field['Mes03']?>" /></td>
			<td align="center"><input type="text" name="mes04" style="width:90%;" value="<?=$field['Mes04']?>" /></td>
			<td align="center"><input type="text" name="mes05" style="width:90%;" value="<?=$field['Mes05']?>" /></td>
			<td align="center"><input type="text" name="mes06" style="width:90%;" value="<?=$field['Mes06']?>" /></td>
			<td align="center"><input type="text" name="mes07" style="width:90%;" value="<?=$field['Mes07']?>" /></td>
			<td align="center"><input type="text" name="mes08" style="width:90%;" value="<?=$field['Mes08']?>" /></td>
			<td align="center"><input type="text" name="mes09" style="width:90%;" value="<?=$field['Mes09']?>" /></td>
			<td align="center"><input type="text" name="mes10" style="width:90%;" value="<?=$field['Mes10']?>" /></td>
			<td align="center"><input type="text" name="mes11" style="width:90%;" value="<?=$field['Mes11']?>" /></td>
			<td align="center"><input type="text" name="mes12" style="width:90%;" value="<?=$field['Mes12']?>" /></td>
		</tr>
		<?
	}
	?>
</table>
</div></td></tr></table>
</form>

<script type="text/javascript" language="javascript">
	totalRegistros(parseInt(<?=$rows?>), "<?=$_ADMIN?>", "<?=$_INSERT?>", "<?=$_UPDATE?>", "<?=$_DELETE?>");
</script>
</body>
</html>