<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../../index.php");
//	------------------------------------
extract($_POST);
extract($_GET);
//	------------------------------------
include("../fphp.php");
//	------------------------------------
if ($filtrar == "default") {
	$fordenar = "pc.CodCuenta";
	$fedoreg = "A";
	$maxlimit = $_SESSION["MAXLIMIT"];
}
if ($fordenar != "") { $cordenar = "checked"; $orderby = "ORDER BY $fordenar"; } else $dordenar = "disabled";
if ($fedoreg != "") { $cedoreg = "checked"; $filtro.=" AND (pc.Estado = '".$fedoreg."')"; } else $dedoreg = "disabled";
if ($fbuscar != "") {
	$cbuscar = "checked";
	$filtro.=" AND (pc.CodCuenta LIKE '%".$fbuscar."%' OR
					pc.Descripcion LIKE '%".$fbuscar."%')";
} else $dbuscar = "disabled";
if ($ftipocuenta != "") { $ctipocuenta = "checked"; $filtro.=" AND (pc.TipoCuenta = '".$ftipocuenta."')"; } else $dtipocuenta = "disabled";
if ($fnaturaleza != "") { $cnaturaleza = "checked"; $filtro.=" AND (pc.TipoSaldo = '".$fnaturaleza."')"; } else $dnaturaleza = "disabled";
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
<link type="text/css" rel="stylesheet" href="../../css/custom-theme/jquery-ui-1.8.16.custom.css" charset="utf-8" />
<link type="text/css" rel="stylesheet" href="../../css/estilo.css" charset="utf-8" />
<link type="text/css" rel="stylesheet" href="../../css/prettyPhoto.css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<script type="text/javascript" src="../../js/jquery-1.7.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../../js/jquery-ui-1.8.16.custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../../js/jquery.prettyPhoto.js" charset="utf-8"></script>
<script type="text/javascript" src="../../js/funciones.js" charset="utf-8"></script>
<script type="text/javascript" src="../../js/fscript.js" charset="utf-8"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Listado de Plan Cuentas</td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="listado_plan_cuentas.php?" method="post">
<input type="hidden" name="registro" id="registro" />
<input type="hidden" name="cod" id="cod" value="<?=$cod?>" />
<input type="hidden" name="nom" id="nom" value="<?=$nom?>" />
<input type="hidden" name="campo3" id="campo3" value="<?=$campo3?>" />
<input type="hidden" name="campo4" id="campo4" value="<?=$campo4?>" />
<input type="hidden" name="ventana" id="ventana" value="<?=$ventana?>" />
<input type="hidden" name="seldetalle" id="seldetalle" value="<?=$seldetalle?>" />
<div class="divBorder" style="width:900px;">
<table width="900" class="tblFiltro">
	<tr>
		<td align="right">Tipo de Cuenta:</td>
		<td>
            <input type="checkbox" <?=$ctipocuenta?> onclick="chkFiltro(this.checked, 'ftipocuenta');" />
            <select name="ftipocuenta" id="ftipocuenta" style="width:125px;" <?=$dtipocuenta?>>
                <option value="">&nbsp;</option>
                <?=getMiscelaneos($ftipocuenta, "CUENTAS")?>
            </select>
		</td>
		<td align="right">Naturaleza:</td>
		<td>
            <input type="checkbox" <?=$cnaturaleza?> onclick="chkFiltro(this.checked, 'fnaturaleza');" />
            <select name="fnaturaleza" id="fnaturaleza" style="width:100px;" <?=$dnaturaleza?>>
                <option value="">&nbsp;</option>
                <?=loadSelectGeneral("TIPO-SALDO", $fnaturaleza, 0)?>
            </select>
		</td>
	</tr>
	<tr>
		<td align="right" width="125">Buscar:</td>
        <td>
            <input type="checkbox" <?=$cbuscar?> onclick="chkFiltro(this.checked, 'fbuscar');" />
            <input type="text" name="fbuscar" id="fbuscar" style="width:200px;" value="<?=$fbuscar?>" <?=$dbuscar?> />
		</td>
		<td align="right" width="125">Estado Reg.:</td>
		<td>
            <input type="checkbox" <?=$cedoreg?> onclick="chkFiltro(this.checked, 'fedoreg');" />
            <select name="fedoreg" id="fedoreg" style="width:100px;" <?=$dedoreg?>>
                <option value="">&nbsp;</option>
                <?=loadSelectGeneral("ESTADO", $fedoreg, 0)?>
            </select>
		</td>
	</tr>
	<tr>
		<td align="right">Ordenar Por:</td>
		<td>
            <input type="checkbox" <?=$cordenar?> onclick="this.checked=!this.checked;" />
            <select name="fordenar" id="fordenar" style="width:125px;" <?=$dordenar?>>
                <?=loadSelectGeneral("ORDENAR-PLANCUENTAS", $fordenar, 0)?>
            </select>
		</td>
	</tr>
</table>
</div>
<center><input type="submit" value="Buscar"></center><br />

<center>
<table width="900" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
	</tr>
</table>

<div style="overflow:scroll; width:900px; height:280px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
		<th scope="col" width="85">Cuenta</th>
		<th scope="col">Descripci&oacute;n</th>
		<th scope="col" width="115">Tipo de Cuenta</th>
		<th scope="col" width="75">Naturaleza</th>
	</tr>
    </thead>
	<?php
	//	consulto todos	
	$sql = "SELECT
				pc.CodCuenta,
				pc.Descripcion,
				pc.TipoCuenta,
				pc.TipoSaldo,
				pc.Estado,
				md.Descripcion AS NomTipoCuenta
			FROM
				ac_mastplancuenta pc
				LEFT JOIN mastmiscelaneosdet md ON (pc.TipoCuenta = md.CodDetalle AND md.CodMaestro = 'CUENTAS')
			WHERE 1 $filtro";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	
	//	consulto lista
	$sql = "SELECT
				pc.CodCuenta,
				pc.Descripcion,
				pc.TipoCuenta,
				pc.TipoSaldo,
				pc.Estado,
				md.Descripcion AS NomTipoCuenta
			FROM
				ac_mastplancuenta pc
				LEFT JOIN mastmiscelaneosdet md ON (pc.TipoCuenta = md.CodDetalle AND md.CodMaestro = 'CUENTAS')
			WHERE 1 $filtro
			$orderby
			LIMIT ".intval($limit).", $maxlimit";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_lista = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	while ($field = mysql_fetch_array($query)) {
		if ($ventana == "selListadoLista") {
			?><tr class="trListaBody" onclick="selListadoLista('<?=$seldetalle?>', '<?=$field["CodCuenta"]?>', '<?=$field["Descripcion"]?>', '<?=$cod?>', '<?=$nom?>');" id="<?=$field['CodCuenta']?>"><?
		}
		else {
			?><tr class="trListaBody" onclick="selListado2('<?=$field['CodCuenta']?>', '<?=($field["Descripcion"])?>', '<?=$cod?>', '<?=$nom?>');" id="<?=$field['CodCuenta']?>"><?
		}
		?>
            <td><?=$field['CodCuenta']?></td>
			<td><?=($field['Descripcion'])?></td>
			<td align="center"><?=$field['NomTipoCuenta']?></td>
			<td align="center"><?=printValoresGeneral("TIPO-SALDO", $field['TipoSaldo'])?></td>
        </tr>
		<?
	}
	?>
</table>
</div>
<table width="900">
	<tr>
    	<td>
        	Mostrar: 
            <select name="maxlimit" style="width:50px;" onchange="this.form.submit();">
                <?=loadSelectGeneral("MAXLIMIT", $maxlimit, 0)?>
            </select>
        </td>
        <td align="right">
        	<?=paginacion(intval($rows_total), intval($rows_lista), intval($maxlimit), intval($limit));?>
        </td>
    </tr>
</table>
</center>
</form>
<script type="text/javascript" language="javascript">
	totalRegistros(parseInt(<?=$rows_total?>));
</script>
</body>
</html>