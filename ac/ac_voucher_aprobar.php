<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
extract($_POST);
extract($_GET);
//	------------------------------------
include("../lib/fphp.php");
include("../lib/ac_fphp.php");
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('01', $concepto);
//	------------------------------------
if ($filtrar == "default") {
	$forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$fperiodo = date("Y-m");
}
//	------------------------------------
$filtro = "";
if ($forganismo != "") { $corganismo = "checked"; $filtro .= " AND vm.CodOrganismo = '".$forganismo."'"; } else $dorganismo = "disabled";
if ($fvoucherd != "" || $fvoucherh != "") {
	if ($fvoucherd != "") $filtro .= " AND vm.Voucher >= '".$fvoucherd."'";
	if ($fvoucherh != "") $filtro .= " AND vm.Voucher <= '".$fvoucherh."'";
	$cvoucher = "checked";
} else $dvoucher = "disabled";
if ($fdependencia != "") { $cdependencia = "checked"; $filtro .= " AND vm.CodDependencia = '".$fdependencia."'"; } else $ddependencia = "disabled";
if ($festado != "") { $cestado = "checked"; $filtro .= " AND vm.Estado = '".$festado."'"; } else $destado = "disabled";
if ($fcodvoucher != "") { $ccodvoucher = "checked"; $filtro .= " AND vm.CodVoucher = '".$fcodvoucher."'"; } else $dcodvoucher = "disabled";
if ($fperiodo != "") { $cperiodo = "checked"; $filtro .= " AND vm.Periodo = '".$fperiodo."'"; } else $dperiodo = "disabled";
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/estilo.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="../js/funciones.js"></script>
<script type="text/javascript" language="javascript" src="../js/ac_funciones.js"></script>
<script type="text/javascript" language="javascript" src="../js/ac_fscript.js"></script>
</head>

<body onload="document.getElementById('fperiodo').focus();">
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Vouchers Pendientes de Aprobaci&oacute;n</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="ac_voucher_aprobar.php" method="POST">
<div class="divBorder" style="width:1100px;">
<table width="1100" class="tblFiltro">
	<tr>
		<td width="125" align="right">Organismo:</td>
		<td>
			<input type="checkbox" name="chkorganismo" id="chkorganismo" value="1" <?=$corganismo?> onclick="this.checked=!this.checked;" />
			<select name="forganismo" id="forganismo" style="width:300px;" <?=$dorganismo?> onchange="getFOptions_2(this.id, 'fdependencia', 'chkdependencia');">
				<?=getOrganismos($forganismo, 3);?>
			</select>
		</td>
		<td width="125" align="right">Voucher:</td>
		<td>
			<input type="checkbox" name="chkvoucher" id="chkvoucher" value="1" <?=$cvoucher?> onclick="chkFiltro_2(this.checked, 'fvoucherd', 'fvoucherh');" />
			<input type="text" name="fvoucherd" id="fvoucherd" value="<?=$fvoucherd?>" maxlength="7" style="width:65px;" <?=$dvoucher?> /> - 
			<input type="text" name="fvoucherh" id="fvoucherh" value="<?=$fvoucherh?>" maxlength="7" style="width:65px;" <?=$dvoucher?> />
		</td>
	</tr>
    <tr>
		<td align="right">Dependencia:</td>
		<td>
			<input type="checkbox" name="chkdependencia" id="chkdependencia" value="1" <?=$cdependencia?> onclick="chkFiltro(this.checked, 'fdependencia');" />
			<select name="fdependencia" id="fdependencia" style="width:300px;" <?=$ddependencia?>>
            	<option value=""></option>
				<?=getDependencias($fdependencia, $forganismo, 3);?>
			</select>
		</td>
		<td align="right">Estado:</td>
		<td>
			<input type="checkbox" name="chkestado" id="chkestado" value="1" <?=$cestado?> onclick="chkFiltro(this.checked, 'festado');" />
			<select name="festado" id="festado" style="width:72px;" <?=$destado?>>
            	<option value=""></option>
				<?=loadSelectValores("ESTADO-VOUCHER", $festado, 0)?>
			</select>
		</td>
	</tr>
    <tr>
		<td align="right">Tipo de Voucher:</td>
		<td>
			<input type="checkbox" name="chkcodvoucher" id="chkcodvoucher" value="1" <?=$ccodvoucher?> onclick="chkFiltro(this.checked, 'fcodvoucher');" />
			<select name="fcodvoucher" id="fcodvoucher" style="width:200px;" <?=$dcodvoucher?>>
            	<option value=""></option>
				<?=loadSelect("ac_voucher", "CodVoucher", "Descripcion", $fcodvoucher, 0);?>
			</select>
		</td>
		<td align="right">Per&iacute;odo:</td>
		<td>
			<input type="checkbox" name="chkperiodo" id="chkperiodo" value="1" <?=$cperiodo?> onclick="this.checked=!this.checked;" />
			<input type="text" name="fperiodo" id="fperiodo" value="<?=$fperiodo?>" maxlength="7" style="width:65px;" <?=$dperiodo?> />
		</td>
	</tr>
</table>
</div>
<center><input type="submit" name="btBuscar" value="Buscar"></center><br />

<input type="hidden" name="registro" id="registro" />
<table width="1100" class="tblBotones">
	<tr>
    	<td>
        	<a href="#" onclick="listaMultiSelTodo(document.getElementById('frmentrada'), 'voucher', 'row', true);">Seleccionar todo</a>
        </td>
		<td align="right">
        	<input type="button" id="btVer" value="Ver Voucher" style="width:125px;" onclick="cargarOpcionMultiple(this.form, 'ac_voucher_form.php?opcion=ver&return=ac_voucher_lista', 'BLANK', 'height=700, width=1000, left=100, top=0, resizable=no', 'voucher', 'registro', false);" />
			<input type="button" id="btAprobar" value="Aprobar/Rechazar" style="width:125px;" onclick="cargarOpcionMultiple(this.form, 'ac_voucher_form.php?opcion=aprobar&return=ac_voucher_aprobar', 'SELF', '', 'voucher', 'registro', false);" />
			<input type="button" id="btMasiva" value="Aprobación Masiva" style="width:125px;" onclick="cargarOpcionMultipleValidar(this.form, 'ac_voucher_aprobar.php', 'SELF', '', 'accion=voucher_aprobacion_masiva', 'voucher', 'registro', 'ac', true);" />
		</td>
	</tr>
</table>

<table align="center"><tr><td align="center"><div style="overflow:scroll; width:1100px; height:350px;">
<table width="1200" class="tblLista">
  <thead>
	<tr class="trListaHead">
		<th width="75" scope="col">Periodo</th>
		<th width="75" scope="col">Organismo</th>
		<th width="75" scope="col">Voucher</th>
		<th scope="col">Descripci&oacute;n</th>
		<th width="75" scope="col">Fecha</th>
		<th width="125" scope="col">Libro</th>
		<th width="50" scope="col">Lineas</th>
		<th width="300" scope="col">Dependencia</th>
	</tr>
  </thead>
	<?php
	//	CONSULTO LA TABLA
	$sql = "SELECT
				vm.*,
				o.Organismo,
				d.Dependencia,
				lc.Descripcion AS NomLibroContable
			FROM
				ac_vouchermast vm
				INNER JOIN mastorganismos o ON (vm.CodOrganismo = o.CodOrganismo)
				LEFT JOIN mastdependencias d ON (vm.CodDependencia = d.CodDependencia)
				LEFT JOIN ac_librocontable lc ON (vm.CodLibroCont = lc.CodLibroCont)
			WHERE vm.Estado = 'AB' $filtro
			ORDER BY vm.Periodo, vm.CodOrganismo, vm.Voucher";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	while ($field = mysql_fetch_array($query)) {
		$id = "$field[CodOrganismo] $field[Periodo] $field[Voucher]";
		?>
		<tr class="trListaBody" onclick="mClkMulti(this);" id="row_<?=$id?>">
			<td align="center">
            	<input type="checkbox" name="voucher" id="<?=$id?>" value="<?=$id?>" style="display:none;" />
				<?=$field['Periodo']?>
            </td>
			<td align="center"><?=$field['CodOrganismo']?></td>
			<td align="center"><?=$field['Voucher']?></td>
			<td><?=htmlentities($field['TituloVoucher'])?></td>
			<td align="center"><?=formatFechaDMA($field['FechaVoucher'])?></td>
			<td align="center"><?=htmlentities($field['NomLibroContable'])?></td>
			<td align="center"><?=$field['Lineas']?></td>
			<td><?=htmlentities($field['Dependencia'])?></td>
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