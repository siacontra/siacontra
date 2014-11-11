<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<style type="text/css">
<!--
UNKNOWN {
        FONT-SIZE: small
}
#header {
        FONT-SIZE: 93%; BACKGROUND: url(bg.gif) #dae0d2 repeat-x 50% bottom; FLOAT: left; WIDTH: 100%; LINE-HEIGHT: normal
}
#header UL {
        PADDING-RIGHT: 10px; PADDING-LEFT: 10px; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 10px; LIST-STYLE-TYPE: none
}
#header LI {
        PADDING-RIGHT: 0px; PADDING-LEFT: 9px; BACKGROUND: url(left.gif) no-repeat left top; FLOAT: left; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 0px
}
#header A {
        PADDING-RIGHT: 15px; DISPLAY: block; PADDING-LEFT: 6px; FONT-WEIGHT: bold; BACKGROUND: url(right.gif) no-repeat right top; FLOAT: left; PADDING-BOTTOM: 4px; COLOR: #765; PADDING-TOP: 5px; TEXT-DECORATION: none
}
#header A {
        FLOAT: none
}
#header A:hover {
        COLOR: #333
}
#header #current {
        BACKGROUND-IMAGE: url(left_on.gif)
}
#header #current A {
        BACKGROUND-IMAGE: url(right_on.gif); PADDING-BOTTOM: 5px; COLOR: #333
}
-->
</style>
</head>
<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Registro de Postulantes | Actualizaci&oacute;n</td>
        <td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'postulantes.php?filtro=<?=$filtro?>&limit=<?=$limit?>');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php
include("fphp.php");
connect();
$sql="SELECT Postulante, Apellido1, Apellido2, Nombres, Sexo, ResumenEjec, CiudadNacimiento, CiudadDomicilio, Fnacimiento, Direccion, Referencia, Telefono1, Email, TipoDocumento, Ndocumento, FechaRegistro, GrupoSanguineo, SituacionDomicilio, EstadoCivil, FedoCivil, InformacionAdic, FlagBeneficas, Beneficas, FlagCulturales, Culturales, FlagReligiosas, Religiosas, FlagLaborales, Laborales, FlagDeportivas, Deportivas, FlagSociales, Sociales, UltimoUsuario, UltimaFecha, Estado FROM rh_postulantes WHERE Postulante='".$registro."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0) {
	$field=mysql_fetch_array($query);
	//	-----------------------------
	$sql="SELECT mastciudades.CodMunicipio, mastmunicipios.CodEstado, mastestados.CodPais FROM mastciudades, mastmunicipios, mastestados WHERE mastciudades.CodCiudad='".$field['CiudadNacimiento']."' AND mastciudades.CodMunicipio=mastmunicipios.CodMunicipio AND mastmunicipios.CodEstado=mastestados.CodEstado";
	$query1=mysql_query($sql) or die ($sql.mysql_error());
	$rows1=mysql_num_rows($query1);
	if ($rows1!=0) $field1=mysql_fetch_array($query1);
	$municipio1=$field1['CodMunicipio']; $estado1=$field1['CodEstado']; $pais1=$field1['CodPais'];
	//	-----------------------------
	$sql="SELECT mastciudades.CodMunicipio, mastmunicipios.CodEstado, mastestados.CodPais FROM mastciudades, mastmunicipios, mastestados WHERE mastciudades.CodCiudad='".$field['CiudadDomicilio']."' AND mastciudades.CodMunicipio=mastmunicipios.CodMunicipio AND mastmunicipios.CodEstado=mastestados.CodEstado";
	$query1=mysql_query($sql) or die ($sql.mysql_error());
	$rows1=mysql_num_rows($query1);
	if ($rows1!=0) $field1=mysql_fetch_array($query1);
	$municipio2=$field1['CodMunicipio']; $estado2=$field1['CodEstado']; $pais2=$field1['CodPais'];
	//	-----------------------------
	list($a, $m, $d)=SPLIT('[/.-]', $field['Fnacimiento']); $fnac=$d."-".$m."-".$a;
	if ($fnac=="00-00-0000" || $fnac=="00/00/0000") $fnac="";
	list ($a, $m, $d) = getEdad($fnac);
	$anac=$a; $mnac=$m; $dnac=$d;
	//	-----------------------------
	list($a, $m, $d)=SPLIT('[/.-]', $field['FechaRegistro']); $freg=$d."-".$m."-".$a;
	list($a, $m, $d)=SPLIT('[/.-]', $field['FedoCivil']); $fedocivil=$d."-".$m."-".$a;
	//	-----------------------------
	if ($field['FlagBeneficas']=="S") $fbeneficas="checked"; else $dbeneficas="disabled";
	if ($field['FlagCulturales']=="S") $fculturales="checked"; else $dculturales="disabled";
	if ($field['FlagReligiosas']=="S") $freligiosas="checked"; else $dreligiosas="disabled";
	if ($field['FlagLaborales']=="S") $flaborales="checked"; else $dlaborales="disabled";
	if ($field['FlagDeportivas']=="S") $fdeportivas="checked"; else $ddeportivas="disabled";
	if ($field['FlagSociales']=="S") $fsociales="checked"; else $dsociales="disabled";
	//	-----------------------------
	if ($field['Estado']=="P") $field['Estado']="Postulante";
	elseif ($field['Estado']=="A") $field['Estado']="Aceptado";
	elseif ($field['Estado']=="C") $field['Estado']="Contratado";
	?>
	<form name="frmentrada" action="postulantes.php" method="POST" onsubmit="return verificarPostulante(this, 'ACTUALIZAR');">
	<table width="905" align="center">
	  <tr>
		<td>
			<div id="header">
			<ul>
			<!-- CSS Tabs -->
			<li><a onclick="document.getElementById('tab1').style.display='block'; document.getElementById('tab2').style.display='none'; document.getElementById('tab3').style.display='none'; document.getElementById('tab4').style.display='none'; document.getElementById('tab5').style.display='none'; document.getElementById('tab6').style.display='none'; document.getElementById('tab7').style.display='none'; document.getElementById('tab8').style.display='none';" href="#">Datos Personales</a></li>
			
			<li><a onclick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='block'; document.getElementById('tab3').style.display='none'; document.getElementById('tab4').style.display='none'; document.getElementById('tab5').style.display='none'; document.getElementById('tab6').style.display='none'; document.getElementById('tab7').style.display='none'; document.getElementById('tab8').style.display='none';" href="#">Otros Datos</a></li>
			
			<li><a onclick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='none'; document.getElementById('tab3').style.display='block'; document.getElementById('tab4').style.display='none'; document.getElementById('tab5').style.display='none'; document.getElementById('tab6').style.display='none'; document.getElementById('tab7').style.display='none'; document.getElementById('tab8').style.display='none';" href="#">Instrucci&oacute;n</a></li>
			
			<li><a onclick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='none'; document.getElementById('tab3').style.display='none'; document.getElementById('tab4').style.display='block'; document.getElementById('tab5').style.display='none'; document.getElementById('tab6').style.display='none'; document.getElementById('tab7').style.display='none'; document.getElementById('tab8').style.display='none';" href="#">Cursos</a></li>
			
			<li><a onclick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='none'; document.getElementById('tab3').style.display='none'; document.getElementById('tab4').style.display='none'; document.getElementById('tab5').style.display='block'; document.getElementById('tab6').style.display='none'; document.getElementById('tab7').style.display='none'; document.getElementById('tab8').style.display='none';" href="#">Experiencias Laborales</a></li>

			<li><a onclick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='none'; document.getElementById('tab3').style.display='none'; document.getElementById('tab4').style.display='none'; document.getElementById('tab5').style.display='none'; document.getElementById('tab6').style.display='none'; document.getElementById('tab7').style.display='none'; document.getElementById('tab8').style.display='block';" href="#">Referencias Personales</a></li>
			
			<li><a onclick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='none'; document.getElementById('tab3').style.display='none'; document.getElementById('tab4').style.display='none'; document.getElementById('tab5').style.display='none'; document.getElementById('tab6').style.display='block'; document.getElementById('tab7').style.display='none'; document.getElementById('tab8').style.display='none';" href="#">Documentos</a></li>
			
			<li><a onclick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='none'; document.getElementById('tab3').style.display='none'; document.getElementById('tab4').style.display='none'; document.getElementById('tab5').style.display='none'; document.getElementById('tab6').style.display='none'; document.getElementById('tab7').style.display='block'; document.getElementById('tab8').style.display='none';" href="#">Cargos Aplicables</a></li>
			</ul>
			</div>
		</td>
	  </tr>
	</table>
	
	<div id="tab1" style="display:block;">
	<div style="width:905px" class="divFormCaption">Datos Generales</div>
	<table width="905" class="tblForm">
	  <tr>
		<td class="tagForm">Postulante:</td>
		<td><input name="postulante" type="text" id="postulante" size="10" value="<?=$field['Postulante']?>" readonly /></td>
		<td class="tagForm">Estado:</td>
		<td><input name="status" type="text" id="status" size="10" value="<?=$field['Estado']?>" readonly /></td>
	  </tr>
	  <tr>
		<td class="tagForm">Apellido Paterno:</td>
		<td><input name="apellido1" type="text" id="apellido1" size="25" maxlength="20" value="<?=($field['Apellido1'])?>" /></td>
		<td class="tagForm">Materno:</td>
		<td><input name="apellido2" type="text" id="apellido2" size="25" maxlength="20" value="<?=($field['Apellido2'])?>" />*</td>
	  </tr>
	  <tr>
		<td class="tagForm">Nombres:</td>
		<td><input name="nombres" type="text" id="nombres" size="40" maxlength="30" value="<?=($field['Nombres'])?>" />*</td>
		<td class="tagForm">Sexo:</td>
		<td>
			<select name="sexo">
				<?php getSexo($field['Sexo'], 0); ?>
			</select>
		</td>
	  </tr>
	</table>
	<div style="width:905px" class="divFormCaption">Resumen Ejecutivo</div>
	<table width="905" class="tblForm">
		<tr><td align="center"><textarea name="resumen" id="resumen" cols="200" rows="2"><?=($field['ResumenEjec'])?></textarea></td></tr>
	</table>
	<div style="width:905px" class="divFormCaption">Lugar y Fecha de Nacimiento</div>
	<table width="905" class="tblForm">
		<tr>
			<td class="tagForm">Pais:</td>
			<td>
				<select name="pais" id="pais" class="selectMed" onchange="getOptions_4(this.id, 'estado', 'municipio', 'ciudad');">
					<option value=""></option>
					<?php getPaises($pais1, 0); ?>
				</select>*
			</td>
			<td class="tagForm">Estado:</td>
			<td>
				<select name="estado" id="estado" class="selectMed" onchange="getOptions_3(this.id, 'municipio', 'ciudad');">
					<option value=""></option>
					<?php getEstados($estado1, $pais1, 0); ?>
				</select>*
			</td>
		</tr>
		<tr>
			<td class="tagForm">Municipio:</td>
			<td>
				<select name="municipio" id="municipio" class="selectMed" onchange="getOptions_2(this.id, 'ciudad');">
					<option value=""></option>
					<?php getMunicipios($municipio1, $estado1, 0); ?>
				</select>*
			</td>
			<td class="tagForm">Ciudad:</td>
			<td>
				<select name="ciudad" id="ciudad" class="selectMed">
					<option value=""></option>
					<?php getCiudades($field['CiudadNacimiento'], $municipio1, 0); ?>
				</select>*
		</td>
	  </tr>
	  <tr>
		<td class="tagForm">Fecha:</td>
		<td><input name="fnac" type="text" id="fnac" size="15" maxlength="10" value="<?=$fnac?>" onKeyUp="getEdad(this.form, this.value);" />*<em>(dd-mm-yyyy)</em></td>
		<td class="tagForm">Edad:</td>
		<td>
			<input name="anac" type="text" id="anac" size="5" value="<?=$anac?>" readonly />a
			<input name="mnac" type="text" id="mnac" size="5" value="<?=$mnac?>" readonly />m
			<input name="dnac" type="text" id="dnac" size="5" value="<?=$dnac?>" readonly />d
		</td>
	  </tr>
	</table>
	<div style="width:905px" class="divFormCaption">Domicilio Actual</div>
	<table width="905" class="tblForm">
	  <tr>
		<td class="tagForm">Direcci&oacute;n:</td>
		<td colspan="3"><input name="dir" type="text" id="dir" size="100" maxlength="255" value="<?=($field['Direccion'])?>" />*</td>
	  </tr>
	  <tr>
		<td class="tagForm">Referencia:</td>
		<td colspan="3"><input name="referencia" type="text" id="referencia" size="100" maxlength="100" value="<?=($field['Referencia'])?>" /></td>
	  </tr>
		<tr>
		<td class="tagForm">Pais:</td>
		<td>
				<select name="pais2" id="pais2" class="selectMed" onchange="getOptions_4(this.id, 'estado2', 'municipio2', 'ciudad2')">
					<option value=""></option>
					<?php getPaises($pais2, 0); ?>
				</select>*
			</td>
		<td class="tagForm">Estado:</td>
		<td>
				<select name="estado2" id="estado2" class="selectMed" onchange="getOptions_3(this.id, 'municipio2', 'ciudad2')">
					<option value=""></option>
					<?php getEstados($estado2, $pais2, 0); ?>
				</select>*
			</td>
	  </tr>
		<tr>
		<td class="tagForm">Municipio:</td>
		<td>
				<select name="municipio2" id="municipio2" class="selectMed" onchange="getOptions_2(this.id, 'ciudad2')">
					<option value=""></option>
					<?php getMunicipios($municipio2, $estado2, 0); ?>
				</select>*
			</td>
		<td class="tagForm">Ciudad:</td>
		<td>
				<select name="ciudad2" id="ciudad2" class="selectMed">
					<option value=""></option>
					<?php getCiudades($field['CiudadDomicilio'], $municipio2, 0); ?>
				</select>*
			</td>
	  </tr>
	  <tr>
		<td class="tagForm">Tel&eacute;fono:</td>
		<td><input name="tel" type="text" id="tel" size="25" maxlength="20" value="<?=$field['Telefono']?>" />*</td>
		<td class="tagForm">e-mail:</td>
		<td colspan="3"><input name="email" type="text" id="email" size="45" maxlength="30" value="<?=($field['Email'])?>" /></td>
	  </tr>
	</table>
	<div style="width:905px" class="divFormCaption">Documentos de Identificaci&oacute;n</div>
	<table width="905" class="tblForm">
		<tr>
		<td class="tagForm">Tipo Doc.:</td>
		<td>
				<select name="tdoc" id="tdoc" class="selectMed">
					<option value=""></option>
					<?php getMiscelaneos($field['TipoDocumento'], "TIPODOC", 0); ?>
				</select>*
			</td>
		<td class="tagForm">Nro. Documento:</td>
		<td colspan="3"><input name="ndoc" type="text" id="ndoc" size="25" maxlength="20" value="<?=$field['Ndocumento']?>" />*</td>
	  </tr>
	  <tr>
		  <td class="tagForm">Fecha de Registro:</td>
		  <td colspan="3">
				<input name="freg" type="text" id="freg" size="15" maxlength="10" value="<?=$freg?>" readonly />
			</td>
		</tr>
	  <tr>
		  <td class="tagForm">&Uacute;ltima Modif.:</td>
		  <td colspan="3">
				<input name="ult_usuario" type="text" id="ult_usuario" size="30" value="<?=$field['UltimoUsuario']?>" readonly />
				<input name="ult_fecha" type="text" id="ult_fecha" size="25" value="<?=$field['UltimaFecha']?>" readonly />
			</td>
		</tr>
	</table>
	<center>
	<input type="submit" value="Guardar Registro" />
	<input type="button" value="Cancelar" onclick="cargarPagina(this.form, 'postulantes.php?filtro=<?=$filtro?>&limit=<?=$limit?>');" />
	</center><br />
	<div style="width:905px" class="divMsj">Campos Obligatorios *</div>
	</div>
	
	<div id="tab2" style="display:none;">
	<div style="width:905px" class="divFormCaption">Otros Datos Personales</div>
	<table width="905" class="tblForm">
		<tr>
			<td class="tagForm">Grupo Sangu&iacute;neo:</td>
			<td>
				<select name="gsan" id="gsan" class="selectMed">
					<option value=""></option>
					<?php getMiscelaneos($field['GrupoSanguineo'], "SANGRE", 0); ?>
				</select>
			</td>
			<td class="tagForm">Situaci&oacute;n Domicilio:</td>
			<td>
				<select name="sitdom" id="sitdom" class="selectMed">
					<option value=""></option>
					<?php getMiscelaneos($field['SituacionDomicilio'], "SITDOM", 0); ?>
				</select>
			</td>
		</tr>
		<tr>
			<td class="tagForm">Estado Civil:</td>
			<td>
				<select name="edocivil" id="edocivil" class="selectMed">
					<option value=""></option>
					<?php getMiscelaneos($field['EstadoCivil'], "EDOCIVIL", 0); ?>
				</select>*
			</td>
			<td class="tagForm">Fecha Edo. Civil:</td>
			<td><input name="fedocivil" type="text" id="fedocivil" size="15" maxlength="10" value="<?=$fedocivil?>" />*<i>(dd-mm-yyyy)</i>
			</td>
		</tr>
	</table>
	<div style="width:905px" class="divFormCaption">Informaci&oacute;n Adicional</div>
	<table width="905" class="tblForm">
		<tr><td align="center"><textarea name="obs" id="obs" cols="200" rows="2"><?=$field['InformacionAdic']?></textarea></td></tr>
	</table>
	<div style="width:905px" class="divFormCaption">Actividades Extralaborales</div>
	<table width="905" class="tblForm">
		<tr>
			<td class="tagForm">Ben&eacute;ficas:</td>
			<td>
				<input type="checkbox" value="S" name="flagactividades" id="flagbeneficas" <?=$fbenefica?> onclick="enabledBeneficas(this.form);" />
				<textarea name="beneficas" id="beneficas" cols="50" rows="2" <?=$dbeneficas?>><?=($field['Beneficas'])?></textarea>
			</td>
			<td class="tagForm">Laborales:</td>
			<td>
				<input type="checkbox" value="S" name="flagactividades" id="flaglaborales" <?=$flaborales?> onclick="enabledLaborales(this.form);" />
				<textarea name="laborales" id="laborales" cols="50" rows="2" <?=$dlaborales?>><?=($field['Laborales'])?></textarea>
			</td>
		</tr>
		<tr>
			<td class="tagForm">Culturales:</td>
			<td>
				<input type="checkbox" value="S" name="flagactividades" id="flagculturales" <?=$fculturales?> onclick="enabledCulturales(this.form);" />
				<textarea name="culturales" id="culturales" cols="50" rows="2" <?=$dculturales?>><?=($field['Culturales'])?></textarea>
			</td>
			<td class="tagForm">Deportivas:</td>
			<td>
				<input type="checkbox" value="S" name="flagactividades" id="flagdeportivas" <?=$fdeportivas?> onclick="enabledDeportivas(this.form);" />
				<textarea name="deportivas" id="deportivas" cols="50" rows="2" <?=$ddeportivas?>><?=($field['Deportivas'])?></textarea>
			</td>
		</tr>
		<tr>
			<td class="tagForm">Religiosas:</td>
			<td>
				<input type="checkbox" value="S" name="flagactividades" id="flagreligiosas" <?=$freligiosas?> onclick="enabledReligiosas(this.form);" />
				<textarea name="religiosas" id="religiosas" cols="50" rows="2" <?=$dreligiosas?>><?=($field['Religiosas'])?></textarea>
			</td>
			<td class="tagForm">Sociales:</td>
			<td>
				<input type="checkbox" value="S" name="flagactividades" id="flagsociales" <?=$fsociales?> onclick="enabledSociales(this.form);" />
				<textarea name="sociales" id="sociales" cols="50" rows="2" <?=$dsociales?>><?=($field['Sociales'])?></textarea>
			</td>
		</tr>
	</table>
	</div>
	
	<div id="tab3" style="display:none;">
	<div style='width:905px' class='divFormCaption'>Instrucci&oacute;n</div>
	<table align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td colspan="2"><iframe class="frameTab" style="height:375px; width:905px;" src="postulantes_instruccion.php?registro=<?=$registro?>"></iframe></td>
		</tr>
		<tr>
			<td align="center">
				<div style='width:527px' class='divFormCaption'>Idiomas</div>
				<iframe class="frameTab" style="height:200px; width:525px;" src="postulantes_idioma.php?registro=<?=$registro?>"></iframe>
				</td>	
			<td align="center">
				<div style='width:377px' class='divFormCaption'>Idiomas</div>
				<iframe class="frameTab" style="height:200px; width:375px;" src="postulantes_informatica.php?registro=<?=$registro?>"></iframe>
			</td>			
		</tr>
	</table>
	</div>
	
	<div id="tab4" style="display:none;">
	<div style='width:905px' class='divFormCaption'>Cursos</div>
	<table align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td><iframe class="frameTab" style="height:575px; width:905px;" src="postulantes_cursos.php?registro=<?=$registro?>"></iframe></td>
		</tr>
	</table>
	</div>
	
	<div id="tab5" style="display:none;">
	<div style='width:905px' class='divFormCaption'>Experiencia Laboral</div>
	<table align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td><iframe class="frameTab" style="height:325px; width:905px;" src="postulantes_experiencia.php?registro=<?=$registro?>"></iframe></td>
		</tr>
		<tr>
			<td align="center">
				<div style='width:905px' class='divFormCaption'>Referencias</div>
				<iframe class="frameTab" style="height:250px; width:905px;" src="postulantes_referencias.php?registro=<?=$registro?>"></iframe>
			</td>			
		</tr>
	</table>
	</div>
	
	<div id="tab6" style="display:none;">
	<div style='width:905px' class='divFormCaption'>Documentos</div>
	<table align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td><iframe class="frameTab" style="height:575px; width:905px;" src="postulantes_documentos.php?registro=<?=$registro?>"></iframe></td>
		</tr>
	</table>
	</div>
	
	<div id="tab7" style="display:none;">
	<div style='width:905px' class='divFormCaption'>Cargos Aplicables</div>
	<table align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td><iframe class="frameTab" style="height:575px; width:905px;" src="postulantes_cargos.php?registro=<?=$registro?>"></iframe></td>
		</tr>
	</table>
	</div>
	
	<div id="tab8" style="display:none;">
	<div style='width:905px' class='divFormCaption'>Referencias Personales</div>
	<table align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td><iframe class="frameTab" style="height:575px; width:905px;" src="postulantes_personales.php?registro=<?=$registro?>"></iframe></td>
		</tr>
	</table>
	</div>
	
	<?php
	//	FILTRO..............
	echo "
	<input type='hidden' name='chksexo' value='".$_POST['chksexo']."' />
	<input type='hidden' name='fsexo' value='".$_POST['fsexo']."' />
	<input type='hidden' name='chkginstruccion' value='".$_POST['chkginstruccion']."' />
	<input type='hidden' name='fginstruccion' value='".$_POST['fginstruccion']."' />
	<input type='hidden' name='chkcargo' value='".$_POST['chkcargo']."' />
	<input type='hidden' name='fcargo' value='".$_POST['fcargo']."' />
	<input type='hidden' name='filtro' value='".$_POST['filtro']."' />
	<input type='hidden' name='limit' value='".$_POST['limit']."' />";
	?>
	
	</form>
<? } ?>
</body>
</html>