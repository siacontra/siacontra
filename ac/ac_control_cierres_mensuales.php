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
	$ftipo_registro = "AB"; 
	$flibro_contable = "CO";
}
//	------------------------------------
$filtro = "";
if ($forganismo != "") { $corganismo = "checked"; $filtro .= " AND ccm.CodOrganismo = '".$forganismo."'"; } else $dorganismo = "disabled";
if ($flibro_contable != "") { $clibro_contable = "checked"; $filtro .= " AND ccm.CodLibroCont = '".$flibro_contable."'"; } else $dlibro_contable = "disabled";
if ($ftipo_registro != "") { $ctipo_registro = "checked"; $filtro .= " AND ccm.TipoRegistro = '".$ftipo_registro."'"; } else $dtipo_registro = "disabled";
if ($festado != "") { $cestado = "checked"; $filtro .= " AND ccm.Estado = '".$festado."'"; } else $destado = "disabled";
if ($fperiodo_filtro != "") { $cperiodo_filtro = "checked"; $filtro .= " AND ccm.Periodo = '".$fperiodo_filtro."'"; } else $dperiodo_filtro = "disabled";
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
		<td class="titulo">Control de Cierres Mensuales</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="ac_control_cierres_mensuales.php" method="POST">
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
	<tr>
		<td width="125" align="right">Organismo:</td>
		<td>
			<input type="checkbox" name="chkorganismo" id="chkorganismo" value="1" <?=$corganismo?> onclick="this.checked=!this.checked" />
			<select name="forganismo" id="forganismo" style="width:300px;" <?=$dorganismo?>>
				<?=getOrganismos($forganismo, 3);?>
			</select>
		</td>
		<td width="125" align="right">Libro Contable:</td>
		<td>
			<input type="checkbox" name="chklibro_contable" id="chklibro_contable" value="1" <?=$clibro_contable?> onclick="this.checked=!this.checked" />
			<select name="flibro_contable" id="flibro_contable" style="width:150px;" <?=$dlibro_contable?>>
                <?=loadSelect("ac_librocontable", "CodLibroCont", "Descripcion", $flibro_contable, 0)?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">Tipo Registro:</td>
		<td>
            <input type="checkbox" name="chktipo_registro" id="chktipo_registro" value="1" <?=$ctipo_registro?> onclick="this.checked=!this.checked" />
            <select name="ftipo_registro" id="ftipo_registro" style="width:150px;" <?=$dtipo_registro?>>
                <?=loadSelectValores("TIPO-REGISTRO", $ftipo_registro, 0)?>
            </select>
		</td>
		<td align="right">Estado:</td>
		<td>
            <input type="checkbox" name="chkestado" id="chkestado" value="1" <?=$cestado?> onclick="chkFiltro(this.checked, 'festado');" />
            <select name="festado" id="festado" style="width:150px;" <?=$destado?>>
                <option value=""></option>
                <?=loadSelectValores("ESTADO-CONTROL-CIERRE", $festado, 0)?>
            </select>
		</td>
	</tr>
	<tr>
		<td align="right">Periodo Filtro:</td>
		<td>
			<input type="checkbox" name="chkperiodo_filtro" id="chkperiodo_filtro" value="1" <?=$cperiodo_filtro?> onclick="chkFiltro(this.checked, 'fperiodo_filtro');" />
			<input type="text" name="fperiodo_filtro" id="fperiodo_filtro" value="<?=$fperiodo_filtro?>" maxlength="10" style="width:65px;" <?=$dperiodo_filtro?> />
		</td>
		<td align="right">&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
</table>
</div>
<center><input type="submit" name="btBuscar" value="Buscar"></center>

<input type="hidden" name="registro" id="registro" />
<table width="1000" class="tblBotones">
	<tr>
    	<td>
        	Periodo a Actualizar: 
            <input type="text" name="periodo" id="periodo" value="<?=$periodo?>" maxlength="10" style="width:65px;" <?=$dperiodo?> />
            <input type="button" class="btLista" id="btAgregar" value="Agregar" onclick="control_cierres_mensuales(this.form, 'agregar');" />
        </td>
		<td align="right">
			<input type="button" class="btLista" id="btModificar" value="Modificar" disabled="disabled" />
			<input type="button" class="btLista" id="btAbrir" value="Abrir" onclick="control_cierres_mensuales(this.form, 'abrir');" />
			<input type="button" class="btLista" id="btCerrar" value="Cerrar" onclick="control_cierres_mensuales(this.form, 'cerrar');" />
		</td>
	</tr>
</table>

<table align="center"><tr><td align="center"><div style="overflow:scroll; width:1000px; height:350px;">
<table width="100%" class="tblLista">
<thead>
	<tr class="trListaHead">
		<th width="100" scope="col">Tipo Registro</th>
		<th scope="col">Organismo</th>
		<th width="100" scope="col">Periodo</th>
		<th width="150" scope="col">Libro Contable</th>
		<th width="75" scope="col">Estado</th>
		<th width="100" scope="col">Ult. Usuario</th>
		<th width="100" scope="col">Ult. Modif.</th>
	</tr>
 </thead>
	<?php
	//	CONSULTO LA TABLA
	$sql = "SELECT
				ccm.*,
				lc.Descripcion AS NomLibroContable,
				o.Organismo
			FROM
				ac_controlcierremensual ccm
				INNER JOIN ac_librocontable lc ON (ccm.CodLibroCont = lc.CodLibroCont)
				INNER JOIN mastorganismos o ON (ccm.CodOrganismo = o.CodOrganismo)
			WHERE 1 $filtro
			ORDER BY ccm.TipoRegistro, ccm.CodOrganismo, ccm.Periodo";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	while ($field = mysql_fetch_array($query)) {
		?>
		<tr class="trListaBody" onclick="mClkMulti(this);" id="periodo_<?=$field['Periodo']?>">
			<td align="center">
            	<input type="checkbox" name="periodos" id="<?=$field['Periodo']?>" value="<?=$field['Periodo']?>" style="display:none;" />
				<?=printValores("TIPO-REGISTRO", $field['TipoRegistro'])?>
			</td>
			<td><?=$field['CodOrganismo']?> - <?=htmlentities($field['Organismo'])?></td>
			<td align="center"><?=$field['Periodo']?></td>
			<td align="center"><?=htmlentities($field['NomLibroContable'])?></td>
			<td align="center"><?=printValores("ESTADO-CONTROL-CIERRE", $field['Estado'])?></td>
			<td align="center"><?=$field['UltimoUsuario']?></td>
			<td align="center"><?=$field['UltimaFecha']?></td>
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