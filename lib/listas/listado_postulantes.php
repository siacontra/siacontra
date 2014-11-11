<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../../index.php");
//	------------------------------------
extract($_POST);
extract($_GET);
//	------------------------------------
include("../fphp.php");
//	------------------------------------
$Ahora = ahora();
list($Anio, $Mes, $Dia) = split("[/.-]", substr($Ahora, 0, 10));
//	------------------------------------
if ($filtrar == "default") {
	$fCodOrganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$fEstado = "P";
	$maxlimit = $_SESSION["MAXLIMIT"];
}
if ($fEstado != "") { $cEstado = "checked"; $filtro.=" AND (p.Estado = '".$fEstado."')"; } else $dEstado = "disabled";
if ($fBuscar != "") {
	$cBuscar = "checked";
	$filtro .= " AND (p.Postulante LIKE '%".$fBuscar."%' OR
					  p.Expediente LIKE '%".$fBuscar."%' OR
					  p.Nombres LIKE '%".$fBuscar."%' OR
					  p.Apellido1 LIKE '%".$fBuscar."%' OR
					  p.Apellido2 LIKE '%".$fBuscar."%' OR
					  CONCAT(p.Nombres, ' ', p.Apellido1, ' ', p.Apellido2) LIKE '%".$fBuscar."%' OR
					  p.Ndocumento LIKE '%".$fBuscar."%')";
} else $dBuscar = "disabled";
if ($fSexo != "") { $cSexo = "checked"; $filtro.=" AND (p.Sexo = '".$fSexo."')"; } else $dSexo = "disabled";
if ($fEdadD != "" || $fEdadH != "") {
	$cEdad = "checked";
	if ($fEdadD != "") list($Desde1, $Hasta1) = getFechasEdad($fEdadD, "$Dia-$Mes-$Anio");
	if ($fEdadH != "") list($Desde2, $Hasta2) = getFechasEdad($fEdadH, "$Dia-$Mes-$Anio");
	if ($Hasta1 != "") $filtro .= " AND (p.Fnacimiento <= '".formatFechaAMD($Hasta1)."')";
	if ($Hasta1 != "") $filtro .= " AND (p.Fnacimiento >= '".formatFechaAMD($Hasta2)."')";
} else $dEdad = "disabled";
if ($fCodCargo != "") { $cCodCargo = "checked"; $filtro.=" AND (pc.CodCargo = '".$fCodCargo."')"; } else $dCodCargo = "visibility:hidden;";
if ($fCodGradoInstruccion != "") { $cCodGradoInstruccion = "checked"; $filtro.=" AND (pi.CodGradoInstruccion = '".$fCodGradoInstruccion."')"; } else $dCodGradoInstruccion = "disabled";
if ($fArea != "") { $cArea = "checked"; $filtro.=" AND (pi.Area = '".$fArea."')"; } else $dArea = "disabled";
if ($fCodProfesion != "") { $cCodProfesion = "checked"; $filtro.=" AND (pi.CodProfesion = '".$fCodProfesion."')"; } else $dCodProfesion = "disabled";
if ($fCodCentroEstudio != "") { $cCodCentroEstudio = "checked"; $filtro.=" AND (pi.CodCentroEstudio = '".$fCodCentroEstudio."')"; } else $dCodCentroEstudio = "visibility:hidden;";
if ($fCodCurso != "") { $cCodCurso = "checked"; $filtro.=" AND (pcs.CodCurso = '".$fCodCurso."')"; } else $dCodCurso = "visibility:hidden;";
if ($fCodIdioma != "") { $cCodIdioma = "checked"; $filtro.=" AND (pi.CodIdioma = '".$fCodIdioma."')"; } else $dCodIdioma = "disabled";
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
<!-- ui-dialog -->
<div id="cajaModal"></div>

<form name="frmentrada" id="frmentrada" action="listado_postulantes.php?" method="post">
<input type="hidden" name="registro" id="registro" />
<input type="hidden" name="cod" id="cod" value="<?=$cod?>" />
<input type="hidden" name="nom" id="nom" value="<?=$nom?>" />
<input type="hidden" name="ventana" id="ventana" value="<?=$ventana?>" />
<input type="hidden" name="campo3" id="campo3" value="<?=$campo3?>" />
<input type="hidden" name="campo4" id="campo4" value="<?=$campo4?>" />
<input type="hidden" name="detalle" id="detalle" value="<?=$detalle?>" />
<input type="hidden" name="marco" id="marco" value="<?=$marco?>" />
<div class="divBorder" style="width:100%;">
<table width="100%" class="tblFiltro">
    <tr>
		<td align="right">G. Instrucci&oacute;n:</td>
		<td>
			<input type="checkbox" <?=$cCodGradoInstruccion?> onclick="chkCampos(this.checked, 'fCodGradoInstruccion');" />
			<select name="fCodGradoInstruccion" id="fCodGradoInstruccion" style="width:150px;" onChange="getOptionsSelect2('profesiones', 'fCodProfesion', true, this.value, $('#fArea').val());" <?=$dCodGradoInstruccion?>>
            	<option value="">&nbsp;</option>
				<?=loadSelect("rh_gradoinstruccion", "CodGradoInstruccion", "Descripcion", $fCodGradoInstruccion, 0)?>
			</select>
		</td>
		<td align="right">Sexo: </td>
		<td>
            <input type="checkbox" <?=$cSexo?> onclick="chkCampos(this.checked, 'fSexo');" />
            <select name="fSexo" id="fSexo" style="width:150px;" <?=$dSexo?>>
                <option value=""></option>
                <?=loadSelectGeneral("SEXO", $fSexo, 0)?>
            </select>
		</td>
		<td align="right">Buscar:</td>
		<td>
			<input type="checkbox" <?=$cBuscar?> onclick="chkCampos(this.checked, 'fBuscar');" />
			<input type="text" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" style="width:150px;" <?=$dBuscar?> />
		</td>
	</tr>
    <tr>
		<td align="right">Area Profesional:</td>
		<td>
			<input type="checkbox" <?=$cArea?> onclick="chkCampos(this.checked, 'fArea');" />
			<select name="fArea" id="fArea" style="width:150px;" onChange="getOptionsSelect2('profesiones', 'fCodProfesion', true, $('#fCodGradoInstruccion').val(), this.value);" <?=$dArea?>>
            	<option value="">&nbsp;</option>
				<?=getMiscelaneos($fArea, "AREA", 0)?>
			</select>
		</td>
		<td align="right">Idioma:</td>
		<td>
			<input type="checkbox" <?=$cCodIdioma?> onclick="chkCampos(this.checked, 'fCodIdioma');" />
			<select name="fCodIdioma" id="fCodIdioma" style="width:150px;" <?=$dCodIdioma?>>
            	<option value="">&nbsp;</option>
				<?=loadSelect("mastidioma", "CodIdioma", "DescripcionLocal", $fCodIdioma, 0)?>
			</select>
		</td>
		<td align="right">Edad:</td>
		<td>
			<input type="checkbox" <?=$cEdad?> onclick="chkCampos(this.checked, 'fEdadD', 'fEdadH');" />
			<input type="text" name="fEdadD" id="fEdadD" value="<?=$fEdadD?>" style="width:25px;" <?=$dEdad?> /> -
			<input type="text" name="fEdadH" id="fEdadH" value="<?=$fEdadH?>" style="width:25px;" <?=$dEdad?> />
		</td>
	</tr>
    <tr>
		<td align="right">Profesi&oacute;n:</td>
		<td>
			<input type="checkbox" <?=$cCodProfesion?> onclick="chkCampos(this.checked, 'fCodProfesion');" />
			<select name="fCodProfesion" id="fCodProfesion" style="width:150px;" <?=$dCodProfesion?>>
            	<option value="">&nbsp;</option>
				<?=loadSelectDependiente2("rh_profesiones", "CodProfesion", "Descripcion", "CodGradoInstruccion", "Area", $fCodProfesion, $fCodGradoInstruccion, $fArea, 0)?>
			</select>
		</td>
		<td align="right">Estado: </td>
		<td>
            <input type="checkbox" <?=$cEstado?> onclick="chkCampos(this.checked, 'fEstado');" />
            <select name="fEstado" id="fEstado" style="width:150px;" <?=$dEstado?>>
                <option value=""></option>
                <?=loadSelectGeneral("ESTADO-POSTULANTE", $fEstado, 0)?>
            </select>
		</td>
		<td align="right">A&ntilde;os de Experiencia:</td>
		<td>
			<input type="checkbox" <?=$cAnio?> onclick="chkCampos(this.checked, 'fAnioD', 'fAnioH');" />
			<input type="text" name="fAnioD" id="fAnioD" value="<?=$fAnioD?>" style="width:25px;" <?=$dAnio?> /> -
			<input type="text" name="fAnioH" id="fAnioH" value="<?=$fAnioH?>" style="width:25px;" <?=$dAnio?> />
		</td>
	</tr>
</table>
</div>
<center><input type="submit" value="Buscar"></center><br />

<center>
<table width="100%" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
	</tr>
</table>

<div style="overflow:scroll; width:100%; height:325px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
		<th scope="col" width="60">C&oacute;digo</th>
		<th scope="col" width="75">Nro. Expediente</th>
		<th scope="col">Nombre Completo</th>
		<th scope="col" width="75">Nro. Documento</th>
		<th scope="col" width="30">Sexo</th>
		<th scope="col" width="60">Fecha de Registro</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos
	$sql = "SELECT
				p.Postulante,
				p.Apellido1,
				p.Apellido2,
				p.Nombres,
				p.Sexo,
				p.FechaRegistro,
				p.Expediente,
				p.Ndocumento,
				p.Estado,
				p.Anio
			FROM
				rh_postulantes p
				LEFT JOIN rh_postulantes_instruccion pi ON (pi.Postulante = p.Postulante)
				LEFT JOIN rh_postulantes_cargos pc ON (pc.Postulante = p.Postulante)
				LEFT JOIN rh_postulantes_cursos pcs ON (pcs.Postulante = p.Postulante)
				LEFT JOIN rh_postulantes_idioma pid ON (pid.Postulante = p.Postulante)
			WHERE 1 $filtro
			GROUP BY Postulante";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	
	//	consulto lista
	$sql = "SELECT
				p.Postulante,
				p.Apellido1,
				p.Apellido2,
				p.Nombres,
				CONCAT(p.Nombres, ' ', p.Apellido1, ' ', p.Apellido2) AS NomCompleto,
				p.Sexo,
				p.FechaRegistro,
				p.Expediente,
				p.Ndocumento,
				p.Estado,
				p.Anio
			FROM
				rh_postulantes p
				LEFT JOIN rh_postulantes_instruccion pi ON (pi.Postulante = p.Postulante)
				LEFT JOIN rh_postulantes_cargos pc ON (pc.Postulante = p.Postulante)
				LEFT JOIN rh_postulantes_cursos pcs ON (pcs.Postulante = p.Postulante)
				LEFT JOIN rh_postulantes_idioma pid ON (pid.Postulante = p.Postulante)
			WHERE 1 $filtro
			GROUP BY Postulante
			ORDER BY Expediente
			LIMIT ".intval($limit).", ".intval($maxlimit);
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_lista = mysql_num_rows($query);
	while ($field = mysql_fetch_array($query)) {
		if ($ventana == "selListadoIFrameRequerimientoPostulante") {
			?><tr class="trListaBody" onclick="selListadoIFrameRequerimientoPostulante('<?=$field['Postulante']?>', 'E');" id="<?=$field['Postulante']?>"><?
		}
		else {
			?>
        	<tr class="trListaBody" onclick="selListado2('<?=$field['Postulante']?>', '<?=($field["NomCompleto"])?>', '<?=$cod?>', '<?=$nom?>');" id="<?=$field['Postulante']?>">
        	<?
		}
		?>
			<td align="center"><?=$field['Postulante']?></td>
			<td align="center"><?=$field['Expediente']?></td>
			<td><?=$field['NomCompleto']?></td>
			<td><?=$field['Ndocumento']?></td>
			<td align="center"><?=$field['Sexo']?></td>
			<td align="center"><?=formatFechaDMA(substr($field['FechaRegistro'], 0, 10))?></td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div>
<table width="100%">
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