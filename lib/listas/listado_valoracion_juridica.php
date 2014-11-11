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
	$fordenar = "vj.Anio, vj.Secuencia, vj.CodValJur";
	$maxlimit = $_SESSION["MAXLIMIT"];
	$forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$fnomorganismo = $_SESSION["FILTRO_NOMBRE_ORGANISMO_ACTUAL"];
	$fdependencia = $_SESSION["FILTRO_DEPENDENCIA_ACTUAL"];
	$fnomdependencia = $_SESSION["FILTRO_NOMBRE_DEPENDENCIA_ACTUAL"];
	$fanio = date("Y");
	$fedoreg = "AP";
}
if ($fordenar != "") { $cordenar = "checked"; $orderby = "ORDER BY $fordenar"; } else $dordenar = "disabled";
if ($fedoreg != "") { $cedoreg = "checked"; $filtro.=" AND (vj.Estado = '".$fedoreg."')"; } else $dedoreg = "disabled";
if ($fbuscar != "") {
	$cbuscar = "checked";
	$filtro.=" AND (vj.CodValJur LIKE '%".$fbuscar."%' OR
					oe.Organismo LIKE '%".$fbuscar."%' OR
					de.Dependencia LIKE '%".$fbuscar."%' OR
					vj.ObjetivoGeneral LIKE '%".$fbuscar."%')";
} else $dbuscar = "disabled";
if ($forganismo != "") { $corganismo = "checked"; $filtro.=" AND (vj.CodOrganismo = '".$forganismo."')"; } else $dorganismo = "disabled";
if ($fdependencia != "") { $cdependencia = "checked"; $filtro.=" AND (vj.CodDependencia = '".$fdependencia."')"; } else $ddependencia = "disabled";
if ($forganismoext != "") { $corganismoext = "checked"; $filtro.=" AND (vj.CodOrganismoExterno = '".$forganismoext."')"; } else $dorganismoext = "disabled";
if ($fdependenciaext != "") { $cdependenciaext = "checked"; $filtro.=" AND (vj.CodDependenciaExterna = '".$fdependenciaext."')"; } else $ddependenciaext = "visibility:hidden;";
if ($fregistrod != "" || $fregistroh != "") {
	$cregistro = "checked";
	if ($fregistrod != "") $filtro.=" AND (vj.FechaRegistro >= '".$fregistrod."')";
	if ($fregistroh != "") $filtro.=" AND (vj.FechaRegistro <= '".$fregistroh."')";
} else $dregistro = "disabled";
if ($fanio != "") { $canio = "checked"; $filtro.=" AND (vj.Anio = '".$fanio."')"; } else $danio = "disabled";
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
<form name="frmentrada" id="frmentrada" action="listado_valoracion_juridica.php?" method="post">
<input type="hidden" name="cod" id="cod" value="<?=$cod?>" />
<input type="hidden" name="nom" id="nom" value="<?=$nom?>" />
<input type="hidden" name="ventana" id="ventana" value="<?=$ventana?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" name="registro" id="registro" />
<div class="divBorder" style="width:1050px;">
<table width="1050" class="tblFiltro">
	<tr>
		<td align="right" width="125">Organismo:</td>
		<td>
            <input type="checkbox" <?=$corganismo?> onclick="this.checked=!this.checked;">
            <select name="forganismo" id="forganismo" style="width:300px;" <?=$dorganismo?> onchange="getOptionsSelect(this.value, 'dependencia_fiscal', 'fdependencia', true, 'fccosto');">
                <?=getOrganismos($forganismo, 0);?>
            </select>            
		</td>
		<td align="right" width="125">Fecha de Registro:</td>
        <td>
            <input type="checkbox" <?=$cregistro?> onclick="chkFiltro_2(this.checked, 'fregistrod', 'fregistroh');" />
            <input type="text" name="fregistrod" id="fregistrod" value="<?=$fregistrod?>" style="width:75px;" maxlength="10" class="datepicker" onkeyup="setFechaDMA(this);" <?=$dregistro?> /> - 
            <input type="text" name="fregistroh" id="fregistroh" value="<?=$fregistroh?>" style="width:75px;" maxlength="10" class="datepicker" onkeyup="setFechaDMA(this);" <?=$dregistro?> />
		</td>
	</tr>
	<tr>
		<td align="right">Dependencia:</td>
		<td>
            <input type="checkbox" <?=$cdependencia?> onclick="chkFiltro(this.checked, 'fdependencia');" />
            <select name="fdependencia" id="fdependencia" style="width:300px;" <?=$ddependencia?> onchange="getOptionsSelect(this.value, 'centro_costo', 'fccosto', true);">
            	<option value="">&nbsp;</option>
                <?=loadDependenciaFiscal($fdependencia, $forganismo, 0)?>
            </select>            
		</td>
		<td align="right">Estado Reg.:</td>
		<td>
        	<?php
            if ($ventana == "prorrogas") {
				?>
                <input type="checkbox" <?=$cedoreg?> onclick="this.checked=!this.checked;" />
                <select name="fedoreg" id="fedoreg" style="width:100px;" <?=$dedoreg?>>
                    <?=loadSelectGeneral("ESTADO-VALORACION", $fedoreg, 1)?>
                </select>
                <?
			} else {
				?>
                <input type="checkbox" <?=$cedoreg?> onclick="chkFiltro(this.checked, 'fedoreg');" />
                <select name="fedoreg" id="fedoreg" style="width:100px;" <?=$dedoreg?>>
                    <option value="">&nbsp;</option>
                    <?=loadSelectGeneral("ESTADO-VALORACION", $fedoreg, 0)?>
                </select>
				<? 
			}
			?>
		</td>
	</tr>
	<tr>
		<td class="tagForm">Centro de Costo:</td>
		<td>
            <input type="checkbox" <?=$cccosto?> onclick="chkFiltro(this.checked, 'fccosto');" />
            <select name="fccosto" id="fccosto" style="width:300px;" <?=$dccosto?>>
            	<option value="">&nbsp;</option>
                <?=loadSelectDependiente("ac_mastcentrocosto", "CodCentroCosto", "Descripcion", "CodDependencia", $fccosto, $fdependencia, 0);?>
            </select>
		</td>
		<td align="right">Buscar:</td>
        <td>
            <input type="checkbox" <?=$cbuscar?> onclick="chkFiltro(this.checked, 'fbuscar');" />
            <input type="text" name="fbuscar" id="fbuscar" style="width:200px;" value="<?=$fbuscar?>" <?=$dbuscar?> />
		</td>
	</tr>
	<tr>
		<td align="right">Ente Externo:</td>
		<td>
            <input type="checkbox" <?=$corganismoext?> onclick="chkFiltroLista_2(this.checked, 'forganismoext', 'fnomorganismoext', 'fdependenciaext', 'fnomdependenciaext', 'btEnteExt');" />
            <input type="hidden" name="forganismoext" id="forganismoext" value="<?=$forganismoext?>" />
			<input type="text" id="fnomorganismoext" style="width:295px;" value="<?=$fnomorganismoext?>" disabled="disabled" />
            <a href="../lib/listas/listado_organismos_externos.php?filtrar=default&&cod=forganismoext&nom=fnomorganismoext&iframe=true&width=950&height=525" rel="prettyPhoto[iframe2]" style=" <?=$ddependenciaext?>" id="btEnteExt">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
		</td>
		<td align="right">A&ntilde;o Fiscal:</td>
        <td>
            <input type="checkbox" <?=$canio?> onclick="chkFiltro(this.checked, 'fanio');" />
            <input type="text" name="fanio" id="fanio" value="<?=$fanio?>" style="width:50px;" maxlength="4" <?=$danio?> />
		</td>
	</tr>
	<tr>
		<td align="right">&nbsp;</td>
		<td>
            <input type="checkbox" style="visibility:hidden;" />
            <input type="hidden" name="fdependenciaext" id="fdependenciaext" value="<?=$fdependenciaext?>" />
			<input type="text" id="fnomdependenciaext" style="width:295px;" value="<?=$fnomdependenciaext?>" disabled="disabled" />
		</td>
		<td align="right">Ordenar Por:</td>
		<td>
            <input type="checkbox" <?=$cordenar?> onclick="this.checked=!this.checked;" />
            <select name="fordenar" id="fordenar" style="width:100px;" <?=$dordenar?>>
                <?=loadSelectGeneral("ORDENAR-VALORACION", $fordenar, 0)?>
            </select>
		</td>
	</tr>
</table>
</div>
<center><input type="submit" value="Buscar"></center><br />

<center>
<table width="1050" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
	</tr>
</table>

<div style="overflow:scroll; width:1050px; height:275px;">
<table width="1800" class="tblLista">
	<thead>
	<tr>
		<th scope="col" width="125">Actuaci&oacute;n</th>
		<th scope="col">Ente</th>
		<th scope="col" width="75">F.Registro</th>
		<th scope="col" width="75">F.Inicio</th>
		<th scope="col" width="75">F.Termino</th>
		<th scope="col" width="75">F.Termino Real</th>
		<th scope="col" width="100">Estado</th>
		<th scope="col" width="800">Objetivo General</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos
	$sql = "SELECT
				vj.CodValJur,
				vj.ObjetivoGeneral,
				vj.FechaRegistro,
				vj.FechaInicio,
				vj.FechaTermino,
				vj.FechaTerminoReal,
				vj.Estado,
				oe.Organismo AS NomOrganismoExterno,
				de.Dependencia As NomDependenciaExterna
			FROM
				pf_valoracionjuridica vj
				INNER JOIN seguridad_alterna sa ON (vj.CodDependencia = sa.CodDependencia AND
													sa.CodAplicacion = '".$_SESSION["APLICACION_ACTUAL"]."' AND
													sa.Usuario = '".$_SESSION["USUARIO_ACTUAL"]."' AND
													sa.FlagMostrar = 'S')
				INNER JOIN pf_organismosexternos oe ON (vj.CodOrganismoExterno = oe.CodOrganismo)
				LEFT JOIN pf_dependenciasexternas de ON (vj.CodDependenciaExterna = de.CodDependencia)
			WHERE 1 $filtro";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	
	//	consulto lista
	$sql = "SELECT
				vj.CodValJur,
				vj.ObjetivoGeneral,
				vj.FechaRegistro,
				vj.FechaInicio,
				vj.FechaTermino,
				vj.FechaTerminoReal,
				vj.Estado,
				oe.Organismo AS NomOrganismoExterno,
				de.Dependencia As NomDependenciaExterna
			FROM
				pf_valoracionjuridica vj
				INNER JOIN seguridad_alterna sa ON (vj.CodDependencia = sa.CodDependencia AND
													sa.CodAplicacion = '".$_SESSION["APLICACION_ACTUAL"]."' AND
													sa.Usuario = '".$_SESSION["USUARIO_ACTUAL"]."' AND
													sa.FlagMostrar = 'S')
				INNER JOIN pf_organismosexternos oe ON (vj.CodOrganismoExterno = oe.CodOrganismo)
				LEFT JOIN pf_dependenciasexternas de ON (vj.CodDependenciaExterna = de.CodDependencia)
			WHERE 1 $filtro
			$orderby
			LIMIT ".intval($limit).", $maxlimit";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_lista = mysql_num_rows($query);
	while ($field = mysql_fetch_array($query)) {
		if ($field['NomDependenciaExterna'] != "") {
			$dep = "<br>($field[NomDependenciaExterna])";
		}
		
		if ($ventana == "prorrogas") {
			?>
            <tr class="trListaBody" onclick="selListadoProrrogasValoracionJuridica('<?=$field['CodValJur']?>');">
            <?
		}
		
		else {
			?>
            <tr class="trListaBody" onclick="selListado2('<?=$field['CodValJur']?>', '<?=($field["CodValJur"])?>', '<?=$cod?>', '<?=$nom?>');" id="<?=$field['CodValJur']?>">
            <?
		}
		?>
			<td align="center"><?=$field['CodValJur']?></td>
			<td>
				<strong><?=$field['NomOrganismoExterno']?></strong>
				<?=$dep?>
            </td>
			<td align="center"><?=formatFechaDMA($field['FechaRegistro'])?></td>
			<td align="center"><?=formatFechaDMA($field['FechaInicio'])?></td>
			<td align="center"><?=formatFechaDMA($field['FechaTermino'])?></td>
			<td align="center"><?=formatFechaDMA($field['FechaTerminoReal'])?></td>
			<td align="center"><?=printValoresGeneral("ESTADO-VALORACION", $field['Estado'])?></td>
			<td><?=$field['ObjetivoGeneral']?></td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div>
<table width="1050">
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
	totalRegistros(parseInt(<?=$rows_total?>), "<?=$_ADMIN?>", "<?=$_INSERT?>", "<?=$_UPDATE?>", "<?=$_DELETE?>");
</script>
</body>
</html>