<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp_nomina.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('05', $concepto);
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_nomina.js"></script>
</head>

<body>
<?php
$ahora=date("Y-m-d H:i:s");
//	----------------------------
if ($filtrar == "DEFAULT") {
	$forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"]; 
}

if ($forganismo != "") { $filtro .= " AND ppp.CodOrganismo = '".$forganismo."'"; $corganismo = "checked"; } else $dorganismo = "disabled";
if ($ftiponom != "") { $filtro .= " AND ppp.CodTipoNom = '".$ftiponom."'"; $ctiponom = "checked"; } else $dtiponom = "disabled";
if ($chkinactivos == "S") $cinactivos = "checked"; else $filtro .= " AND ppp.Estado = 'A'";
//	----------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Control de Procesos</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="voucher_nomina.php" method="POST">
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
    <tr>
        <td align="right">Organismo:</td>
        <td>
        	<input type="checkbox" name="chkorganismo" id="chkorganismo" value="1" <?=$corganismo?> onclick="forzarCheck('chkorganismo');" />
			<select name="forganismo" id="forganismo" class="selectBig" <?=$dorganismo?>>
				<?=getOrganismos($forganismo, 3)?>
			</select>
        </td>
        <td align="right">N&oacute;mina:</td>
        <td>
        	<input type="checkbox" name="chktiponom" id="chktiponom" value="1" <?=$ctiponom?> onclick="enabledTipoNom(this.form);" />
			<select name="ftiponom" id="ftiponom" class="selectBig" <?=$dtiponom?>>
				<option value="">&nbsp;</option>
				<?=getTNomina($ftiponom, 0)?>
			</select>
        </td>
    </tr>
    <tr><td>&nbsp;</td><td><input type="checkbox" name="chkinactivos" id="chkinactivos" value="S" <?=$cinactivos?> /> Mostrar Procesos Inactivos</td></tr>
</table>
</div>
<center><input type="submit" value="Filtrar"></center>
<br />

<table width="1000" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">
			<input type="button" id="btEditar" value="Editar" style="width:100px;" onclick="cargarOpcion(this.form, 'voucher_nomina_generar.php', 'SELF');" />
		</td>
	</tr>
</table>

<input type="hidden" name="registro" id="registro" />
<table align="center"><tr><td align="center"><div style="overflow:scroll; width:1000px; height:400px;">
<table width="100%" class="tblLista">
	<tr class="trListaHead">
		<th width="100" scope="col">Organismo</th>
		<th width="300" scope="col">Tipo de N&oacute;mina</th>
		<th width="75" scope="col">A&ntilde;o</th>
		<th width="50" scope="col">Sec.</th>
		<th width="50" scope="col">Mes</th>
		<th scope="col">Tipo de Proceso</th>
		<th width="25" scope="col">Est.</th>
		<th width="25" scope="col">Apr.</th>
	</tr>
	<?php 
	//	CONSULTO LA TABLA
	$sql = "SELECT
				ppp.*,
				tn.Nomina,
				ptnp.Secuencia,
				ptp.Descripcion AS Proceso
			FROM
				pr_procesoperiodo ppp
				INNER JOIN tiponomina tn ON (ppp.CodTipoNom = tn.CodTipoNom)
				INNER JOIN pr_tiponominaperiodo ptnp ON (ppp.CodTipoNom = ptnp.CodTipoNom AND 
														 SUBSTRING(ppp.Periodo, 1, 4) = ptnp.Periodo AND 
														 SUBSTRING(ppp.Periodo, 6, 2) = ptnp.Mes) 
				INNER JOIN pr_tipoproceso ptp ON (ppp.CodTipoProceso = ptp.CodTipoProceso)
			WHERE ppp.FlagProcesado <> 'S' $filtro
			ORDER BY ppp.CodOrganismo, ppp.Periodo DESC, ppp.CodTipoNom, ppp.CodTipoProceso, ppp.FlagProcesado";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	while ($field = mysql_fetch_array($query)) {
		$id = "$field[CodOrganismo].$field[CodTipoNom].$field[Periodo].$field[CodTipoProceso]";
		list($anio, $mes) = SPLIT('[/.-]', $field['Periodo']);
		if ($field['Estado'] == "A") {
			if ($field['FlagProcesado'] == "N") $src_status = "imagenes/reloj.png";
			else $src_status = "imagenes/check.png";
		} else $src_status = "imagenes/inactivo.png";
		if ($field['FlagAprobado'] == "S") $src_aprobado = "imagenes/bandera.png";
		else $src_aprobado = "imagenes/menos.png";
		//	---------------------------------------------------
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
        	<td align="center"><?=$field['CodOrganismo']?></td>
        	<td><?=($field['Nomina'])?></td>
        	<td align="center"><?=$anio?></td>
        	<td align="center"><?=$field['Secuencia']?></td>
        	<td align="center"><?=$mes?></td>
        	<td><?=($field['Proceso'])?></td>
        	<td align="center"><img src="<?=$src_status?>" width="16" height="16" /></td>
        	<td align="center"><img src="<?=$src_aprobado?>" width="16" height="16" /></td>
		</tr>
        <?
	}
	?>
</table> 
</div></td></tr></table>   
<script type="text/javascript" language="javascript">
	totalRegistros(<?=intval($rows)?>, "<?=$_ADMIN?>", "<?=$_INSERT?>", "<?=$_UPDATE?>", "<?=$_DELETE?>");
</script>
</table>
</form>
</body>
</html>