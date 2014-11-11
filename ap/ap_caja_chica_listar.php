<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
extract($_GET);
extract($_POST);
//	------------------------------------
include("fphp_ap.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('06', $concepto);
//	------------------------------------
if ($accion == "LISTAR") {
	$btNuevo = "";
	$btEditar = "";
	$btVer = "";
	$btAprobar = "disabled";
	$btAnular = "";
	$btImprimir = "";
	$title = "Listado de Caja Chica";
}
elseif ($accion == "APROBAR") {
	$btNuevo = "disabled";
	$btEditar = "disabled";
	$btVer = "";
	$btAprobar = "";
	$btAnular = "";
	$btImprimir = "";
	$title = "Aprobar Caja Chica";
	$festado = "PR";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_ap.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$title?></td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?
$MAXLIMIT=30;
//	-------------------------------
if ($filtrar == "DEFAULT") {
	$forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	if ($accion == "LISTAR") $festado = "PR";
}
//	-------------------------------
if ($forganismo != "") { $corganismo = "checked"; $filtro.=" AND (cc.CodOrganismo = '".$forganismo."')"; } else $dorganismo = "disabled";
if ($fcodpersona != "") { $cpersona = "checked"; $filtro.=" AND (cc.CodPersona = '".$fcodpersona."')"; } else $dpersona = "disabled";
if ($ffdependencia != "") { $cfdependencia = "checked"; $filtro.=" AND (cc.CodDependencia = '".$ffdependencia."')"; } else $dfdependencia = "disabled";
if ($festado != "") { $cestado = "checked"; $filtro.=" AND (cc.Estado = '".$festado."')"; } else $destado = "disabled";
if ($fcchica != "") { $ccchica = "checked"; $filtro.=" AND (cc.NroCajaChica LIKE '%".$fcchica."%')"; } else $dcchica = "disabled";
if ($fcodccosto != "") { $cccosto = "checked"; $filtro.=" AND (cc.CodCentroCosto = '".$fcodccostcc."')"; } else $dccosto = "disabled";
if ($ffpreparaciond != "" || $ffpreparacionh != "") { 
	$cffpreparacion = "checked";
	if ($ffpreparaciond != "") $filtro.=" AND (cc.FechaPreparacion >= '".$ffpreparaciond."')";
	if ($ffpreparacionh != "") $filtro.=" AND (cc.FechaPreparacion <= '".$ffpreparacionh."')"; 
} else $dffpreparacion = "disabled";
//	-------------------------------
?>

<form name="frmfiltro" id="frmfiltro" action="ap_caja_chica_listar.php" method="get">
<input type="hidden" name="accion" id="accion" value="<?=$accion?>" />
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
	<tr>
		<td width="125" align="right">Organismo:</td>
		<td>
			<input type="checkbox" name="chkorganismo" id="chkorganismo" value="1" <?=$corganismo?> onclick="forzarCheck(this.id)" />
			<select name="forganismo" id="forganismo" class="selectBig" <?=$dorganismo?>>
				<?=getOrganismos($forganismo, 3);?>
			</select>
		</td>
		<td width="125" align="right">Persona: </td>
		<td>
			<input type="checkbox" name="chkpersona" value="1" <?=$cpersona?> onclick="chkBtFiltro(this.checked, 'fcodpersona', 'fnompersona', 'btPersona');" />
        	<input type="hidden" name="fcodpersona" id="fcodpersona" value="<?=$fcodpersona?>" />
			<input type="text" name="fnompersona" id="fnompersona" value="<?=$fnompersona?>" readonly="readonly" style="width:200px;" />
			<input type="button" value="..." id="btPersona" <?=$dpersona?> onclick="cargarVentana(this.form, 'listado_personas.php?ventana=&cod=fcodpersona&nom=fnompersona&limit=0&flagpersona=S', 'height=600, width=775, left=50, top=50, resizable=yes');" />
        </td>
	</tr>
    <tr>
		<td align="right">Dependencia</td>
		<td>
			<input type="checkbox" name="chkdependencia" id="chkdependencia" value="1" <?=$cdependencia?> onclick="chkFiltro(this.checked, 'fedoreg');" />
			<select name="fdependencia" id="fdependencia" class="selectBig" <?=$ddependencia?>>
            	<option value="">&nbsp;</option>
				<?=getDependencias($fdependencia, $forganismo, 3);?>
			</select>
        </td>
		<td align="right" valign="top">Estado:</td>
		<td>
        	<? 
			if ($accion == "APROBAR") {
				?>
                <input type="checkbox" name="chkestado" id="chkestado" value="1" <?=$cestado?> onclick="this.checked=!this.checked;" />
                <select name="festado" id="festado" style="width:105px;" <?=$destado?>>
                    <?=loadSelectValores("ESTADO-CAJA-CHICA", $festado, 1)?>
                </select>
                <?
			} 
			else {
				?>
                <input type="checkbox" name="chkestado" id="chkestado" value="1" <?=$cestado?> onclick="chkFiltro(this.checked, 'festado');" />
                <select name="festado" id="festado" style="width:105px;" <?=$destado?>>
                    <option value=""></option>
                    <?=loadSelectValores("ESTADO-CAJA-CHICA", $festado, 0)?>
                </select>
                <?
			} 
			?>
		</td>
	</tr>
    <tr>
		<td align="right">C.Chica:</td>
		<td>
			<input type="checkbox" name="chkcchica" id="chkcchica" value="1" <?=$ccchica?> onclick="chkFiltro(this.checked, 'fcchica');" />
			<input type="text" name="fcchica" id="fcchica" value="<?=$fcchica?>" maxlength="6" style="width:100px;" <?=$dcchica?> />
		</td>
		<td align="right">C. Costo: </td>
		<td>
			<input type="checkbox" name="chkccosto" value="1" <?=$cccosto?> onclick="chkFiltroCCosto(this.checked);" />
        	<input type="text" name="fcodccosto" id="fcodccosto" value="<?=$fcodccosto?>" readonly="readonly" style="width:50px;" />
			<input type="hidden" name="fnomccosto" id="fnomccosto" value="<?=$fnomccosto?>" />
			<input type="button" value="..." id="btCCosto" <?=$dccosto?> onclick="cargarVentana(this.form, 'listado_centro_costos.php?ventana=&cod=fcodccosto&nom=fnomccosto&limit=0', 'height=600, width=825, left=50, top=50, resizable=yes');" />
        </td>
	</tr>
    <tr>
		<td align="right">F.Preparaci&oacute;n: </td>
		<td>
			<input type="checkbox" name="chkffpreparacion" value="1" <?=$cffpreparacion?> onclick="chkFiltro_2(this.checked, 'ffpreparaciond', 'ffpreparacionh');" />
			<input type="text" name="ffpreparaciond" id="ffpreparaciond" value="<?=$ffpreparaciond?>" <?=$dffpreparacion?> maxlength="10" style="width:95px;" />-
            <input type="text" name="ffpreparacionh" id="ffpreparacionh" value="<?=$ffpreparacionh?>" <?=$dffpreparacion?> maxlength="10" style="width:95px;" />
        </td>
	</tr>
</table>
</div>
<center><input type="submit" name="btBuscar" value="Buscar"></center>
<br />
<table width="1000" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">
			<input type="button" class="btLista" id="btNuevo" value="Agregar" onclick="cargarPagina(document.getElementById('frmfiltro'), 'ap_caja_chica_form.php?acc=AGREGAR&origen=ap_caja_chica_listar.php');" <?=$btNuevo?> />
			<input type="button" class="btLista" id="btEditar" value="Editar" onclick="cargarOpcion(document.getElementById('frmfiltro'), 'ap_caja_chica_form.php?acc=ACTUALIZAR', 'SELF');" <?=$btEditar?> />
			<input type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(document.getElementById('frmfiltro'), 'ap_caja_chica_form.php?acc=VER&', 'BLANK', 'height=500, width=1100, left=50, top=50, resizable=no');" <?=$btVer?> /> | 
			<input type="button" class="btLista" id="btAprobar" value="Aprobar" onclick="cargarOpcion(document.getElementById('frmfiltro'), 'ap_caja_chica_form.php?acc=APROBAR', 'SELF');" <?=$btAprobar?> />
			<input type="button" class="btLista" id="btAnular" value="Anular" onclick="cargarOpcion(document.getElementById('frmfiltro'), 'ap_caja_chica_form.php?acc=ANULAR', 'SELF');" <?=$btAnular?> /> | 
			<input type="button" class="btLista" id="btImprimir" value="Imprimir" onclick="cargarOpcion(document.getElementById('frmfiltro'), 'ap_caja_chica_pdf.php?', 'BLANK', 'height=800, width=900, left=100, top=0, resizable=no');" <?=$btImprimir?> />
		</td>
	</tr>
</table>

<input type="hidden" name="registro" id="registro" />
<table align="center"><tr><td align="center"><div style="overflow:scroll; width:1000px; height:350px;">
<table width="1900" class="tblLista">
	<tr class="trListaHead">
		<th width="75" scope="col"># Caja</th>
		<th width="75" scope="col">Organismo</th>
		<th scope="col">Descripci&oacute;n</th>
		<th width="125" scope="col">Monto Total</th>
		<th width="100" scope="col">F. Preparaci&oacute;n</th>
		<th width="100" scope="col">F. Aprobaci&oacute;n</th>        
		<th width="125" scope="col">Estado</th>
		<th width="100" scope="col">Dependencia</th>
		<th width="75" scope="col">C.Costo</th>
		<th width="125" scope="col">Obligaci&oacute;n</th>
		<th width="300" scope="col">Beneficiario</th>
	</tr>
	<?php
	//	CONSULTO LA TABLA
	$sql = "SELECT
				cc.*,
				p1.NomCompleto AS NomBeneficiario
			FROM
				ap_cajachica cc
				INNER JOIN mastpersonas p1 ON (cc.CodBeneficiario = p1.CodPersona)
			WHERE 1 $filtro
			ORDER BY NroCajaChica";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	while($field = mysql_fetch_array($query)) {
		$id = "C.".$field['Periodo'].".".$field['NroCajaChica'];
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
			<td align="center"><?=$field['NroCajaChica']?></td>
			<td align="center"><?=$field['CodOrganismo']?></td>
			<td>
				<?php
				if (strlen($field['Descripcion']) > 200) echo substr($field['Descripcion'], 0, 200)."...";
				else echo $field['Descripcion'];
				?>
            </td>
			<td align="right"><?=number_format($field['MontoTotal'], 2, ',', '.')?></td>
			<td align="center"><?=formatFechaDMA($field['FechaPreparacion'])?></td>
			<td align="center"><?=formatFechaDMA($field['FechaAprobacion'])?></td>
			<td align="center"><?=printValores("ESTADO-CAJA-CHICA", $field['Estado'])?></td>
			<td align="center"><?=$field['CodDependencia']?></td>
			<td align="center"><?=$field['CodCentroCosto']?></td>
			<td align="center"><?=$field['CodTipoDocumento']?>-<?=$field['NroDocumento']?></td>
			<td><?=$field['NomBeneficiario']?></td>
		</tr>
		<?
	}
	?>
</table>
</div></td></tr></table>
</form>

<script language="javascript">
totalRegistros(<?=intval($rows)?>, '<?=$_ADMIN?>', '<?=$_INSERT?>', '<?=$_UPDATE?>', '<?=$_DELETE?>');
</script>
</body>
</html>
