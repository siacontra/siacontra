<?php
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
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Registro de Postulantes</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=rh_postulantes_lista" method="post">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="registro" id="registro" />
<div class="divBorder" style="width:1050px;">
<table width="1050" class="tblFiltro">
	<tr>
		<td align="right">Cargo Aplicable: </td>
		<td class="gallery clearfix">
            <input type="checkbox" <?=$cCodCargo?> onclick="chkListado(this.checked, 'btCargo', 'fCodCargo', 'fDescripCargo');" />
            <input type="hidden" name="fCodCargo" id="fCodCargo" value="<?=$fCodCargo?>" />
			<input type="text" name="fDescripCargo" id="fDescripCargo" style="width:150px;" class="disabled" value="<?=$fDescripCargo?>" disabled />
            <a href="../lib/listas/listado_cargos.php?filtrar=default&cod=fCodCargo&nom=fDescripCargo&iframe=true&width=100%&height=100%" rel="prettyPhoto[iframe1]" id="btCargo" style=" <?=$dCodCargo?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
        </td>
		<td align="right">Centro de Estudio: </td>
		<td class="gallery clearfix">
            <input type="checkbox" <?=$cCodCentroEstudio?> onclick="chkListado(this.checked, 'btCentro', 'fCodCentroEstudio', 'fNomCentroEstudio');" />
            <input type="hidden" name="fCodCentroEstudio" id="fCodCentroEstudio" value="<?=$fCodCentroEstudio?>" />
			<input type="text" name="fNomCentroEstudio" id="fNomCentroEstudio" style="width:150px;" class="disabled" value="<?=$fNomCentroEstudio?>" disabled />
            <a href="../lib/listas/listado_centro_estudio.php?filtrar=default&cod=fCodCentroEstudio&nom=fNomCentroEstudio&iframe=true&width=100%&height=100%" rel="prettyPhoto[iframe2]" id="btCentro" style=" <?=$dCodCentroEstudio?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
        </td>
		<td align="right">Buscar:</td>
		<td>
			<input type="checkbox" <?=$cBuscar?> onclick="chkCampos(this.checked, 'fBuscar');" />
			<input type="text" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" style="width:150px;" <?=$dBuscar?> />
		</td>
	</tr>
    <tr>
		<td align="right">G. Instrucci&oacute;n:</td>
		<td>
			<input type="checkbox" <?=$cCodGradoInstruccion?> onclick="chkCampos(this.checked, 'fCodGradoInstruccion');" />
			<select name="fCodGradoInstruccion" id="fCodGradoInstruccion" style="width:150px;" onChange="getOptionsSelect2('profesiones', 'fCodProfesion', true, this.value, $('#fArea').val());" <?=$dCodGradoInstruccion?>>
            	<option value="">&nbsp;</option>
				<?=loadSelect("rh_gradoinstruccion", "CodGradoInstruccion", "Descripcion", $fCodGradoInstruccion, 0)?>
			</select>
		</td>
		<td align="right">Curso: </td>
		<td class="gallery clearfix">
            <input type="checkbox" <?=$cCodCurso?> onclick="chkListado(this.checked, 'btCurso', 'fCodCurso', 'fNomCurso');" />
            <input type="hidden" name="fCodCurso" id="fCodCurso" value="<?=$fCodCurso?>" />
			<input type="text" name="fNomCurso" id="fNomCurso" style="width:150px;" class="disabled" value="<?=$fNomCurso?>" disabled />
            <a href="../lib/listas/listado_centro_estudio.php?filtrar=default&cod=fCodCurso&nom=fNomCurso&iframe=true&width=100%&height=100%" rel="prettyPhoto[iframe3]" id="btCurso" style=" <?=$dCodCurso?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
        </td>
		<td align="right">Sexo: </td>
		<td>
            <input type="checkbox" <?=$cSexo?> onclick="chkCampos(this.checked, 'fSexo');" />
            <select name="fSexo" id="fSexo" style="width:150px;" <?=$dSexo?>>
                <option value=""></option>
                <?=loadSelectGeneral("SEXO", $fSexo, 0)?>
            </select>
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
                <?=loadSelectValores("ESTADO-POSTULANTE", $fEstado, 0)?>
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
<table width="1050" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">
			<input type="button" id="btNuevo" value="Nuevo" style="width:75px;" onclick="cargarPagina(this.form, 'gehen.php?anz=rh_postulantes_form&opcion=nuevo');" />
            
			<input type="button" id="btModificar" value="Modificar" style="width:75px;" onclick="cargarOpcionValidar2(this.form, $('#registro').val(), 'accion=postulantes_modificar', 'gehen.php?anz=rh_postulantes_form&opcion=modificar', 'SELF', '');" />
            
			<input type="button" id="btVer" value="Ver" style="width:75px;" onclick="cargarOpcion2(this.form, 'gehen.php?anz=rh_postulantes_form&opcion=ver', 'SELF', '', 'registro');" />
		</td>
	</tr>
</table>

<div style="overflow:scroll; width:1050px; height:300px;">
<table width="100%" class="tblLista">
	<thead>
    <tr>
		<th scope="col" width="60">C&oacute;digo</th>
		<th scope="col" width="75">Nro. Expediente</th>
		<th scope="col">Nombre Completo</th>
		<th scope="col" width="75">Nro. Documento</th>
		<th scope="col" width="30">Sexo</th>
		<th scope="col" width="60">Fecha de Registro</th>
		<th scope="col" width="75">Estado</th>
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
		$id = "$field[Postulante]";
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
			<td align="center"><?=$field['Postulante']?></td>
			<td align="center"><?=$field['Expediente']?></td>
			<td><?=$field['NomCompleto']?></td>
			<td><?=$field['Ndocumento']?></td>
			<td align="center"><?=$field['Sexo']?></td>
			<td align="center"><?=formatFechaDMA(substr($field['FechaRegistro'], 0, 10))?></td>
			<td align="center"><?=printValores("ESTADO-POSTULANTE", $field['Estado'])?></td>
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