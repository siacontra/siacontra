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
		<td class="titulo">Maestro de Cargos | Actualizaci&oacute;n</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'cargos.php');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php
include("fphp.php");
connect();
if ($_POST['registro']=="") $_POST['registro']=$_GET['registro'];
if ($_POST['filtro']=="")  $_POST['filtro']=$_GET['filtro'];
$sql="SELECT * FROM rh_puestos WHERE CodCargo='".$_POST['registro']."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0) $field=mysql_fetch_array($query);
?>

<table width="750" align="center">
  <tr>
   	<td>
			<div id="header">
			<ul>
			<!-- CSS Tabs -->
			<li><a onClick="document.getElementById('tab1').style.display='block'; document.getElementById('tab2').style.display='none'; document.getElementById('tab3').style.display='none'; document.getElementById('tab4').style.display='none'; document.getElementById('tab5').style.display='none'; document.getElementById('tab6').style.display='none'; document.getElementById('tab7').style.display='none'; document.getElementById('tab8').style.display='none'; document.getElementById('tab9').style.display='none'; document.getElementById('tab10').style.display='none'; document.getElementById('tab11').style.display='none'; document.getElementById('tab12').style.display='none'; document.getElementById('tab13').style.display='none';" href="#">Cargo</a></li>
			<li><a onClick="document.getElementById('tab2').style.display='block'; document.getElementById('tab1').style.display='none'; document.getElementById('tab3').style.display='none'; document.getElementById('tab4').style.display='none'; document.getElementById('tab5').style.display='none'; document.getElementById('tab6').style.display='none'; document.getElementById('tab7').style.display='none'; document.getElementById('tab8').style.display='none'; document.getElementById('tab9').style.display='none'; document.getElementById('tab10').style.display='none'; document.getElementById('tab11').style.display='none'; document.getElementById('tab12').style.display='none'; document.getElementById('tab13').style.display='none';" href="#">Relaciones</a></li>
			<li><a onClick="document.getElementById('tab10').style.display='block'; document.getElementById('tab2').style.display='none'; document.getElementById('tab1').style.display='none'; document.getElementById('tab3').style.display='none'; document.getElementById('tab4').style.display='none'; document.getElementById('tab5').style.display='none'; document.getElementById('tab6').style.display='none'; document.getElementById('tab7').style.display='none'; document.getElementById('tab8').style.display='none'; document.getElementById('tab9').style.display='none'; document.getElementById('tab11').style.display='none'; document.getElementById('tab12').style.display='none'; document.getElementById('tab13').style.display='none';" href="#">Competencias</a></li>
			<li><a onClick="document.getElementById('tab3').style.display='block'; document.getElementById('tab2').style.display='none'; document.getElementById('tab1').style.display='none'; document.getElementById('tab4').style.display='none'; document.getElementById('tab5').style.display='none'; document.getElementById('tab6').style.display='none'; document.getElementById('tab7').style.display='none'; document.getElementById('tab8').style.display='none'; document.getElementById('tab9').style.display='none'; document.getElementById('tab10').style.display='none'; document.getElementById('tab11').style.display='none'; document.getElementById('tab12').style.display='none'; document.getElementById('tab13').style.display='none';" href="#">Funciones</a></li>
			<li><a onClick="document.getElementById('tab4').style.display='block'; document.getElementById('tab2').style.display='none'; document.getElementById('tab3').style.display='none'; document.getElementById('tab1').style.display='none'; document.getElementById('tab5').style.display='none'; document.getElementById('tab6').style.display='none'; document.getElementById('tab7').style.display='none'; document.getElementById('tab8').style.display='none'; document.getElementById('tab9').style.display='none'; document.getElementById('tab10').style.display='none'; document.getElementById('tab11').style.display='none'; document.getElementById('tab12').style.display='none'; document.getElementById('tab13').style.display='none';" href="#">Formaci&oacute;n</a></li>
			<li><a onClick="document.getElementById('tab5').style.display='block'; document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='none'; document.getElementById('tab3').style.display='none'; document.getElementById('tab4').style.display='none'; document.getElementById('tab6').style.display='none'; document.getElementById('tab7').style.display='none'; document.getElementById('tab8').style.display='none'; document.getElementById('tab9').style.display='none'; document.getElementById('tab10').style.display='none'; document.getElementById('tab11').style.display='none'; document.getElementById('tab12').style.display='none'; document.getElementById('tab13').style.display='none';" href="#">Experiencia Previa</a></li>			
			<li><a onClick="document.getElementById('tab6').style.display='block'; document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='none'; document.getElementById('tab3').style.display='none'; document.getElementById('tab4').style.display='none'; document.getElementById('tab5').style.display='none'; document.getElementById('tab7').style.display='none'; document.getElementById('tab8').style.display='none'; document.getElementById('tab9').style.display='none'; document.getElementById('tab10').style.display='none'; document.getElementById('tab11').style.display='none'; document.getElementById('tab12').style.display='none'; document.getElementById('tab13').style.display='none';" href="#">Riesgos de Trabajo</a></li>			
			<li><a onClick="document.getElementById('tab11').style.display='block'; document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='none'; document.getElementById('tab3').style.display='none'; document.getElementById('tab4').style.display='none'; document.getElementById('tab5').style.display='none'; document.getElementById('tab6').style.display='none'; document.getElementById('tab8').style.display='none'; document.getElementById('tab9').style.display='none'; document.getElementById('tab10').style.display='none'; document.getElementById('tab7').style.display='none'; document.getElementById('tab12').style.display='none'; document.getElementById('tab13').style.display='none';" href="#">Evaluaci&oacute;n - Reclutamiento</a></li>			
			<li><a onClick="document.getElementById('tab7').style.display='block'; document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='none'; document.getElementById('tab3').style.display='none'; document.getElementById('tab4').style.display='none'; document.getElementById('tab5').style.display='none'; document.getElementById('tab6').style.display='none'; document.getElementById('tab8').style.display='none'; document.getElementById('tab9').style.display='none'; document.getElementById('tab10').style.display='none'; document.getElementById('tab11').style.display='none'; document.getElementById('tab12').style.display='none'; document.getElementById('tab13').style.display='none';" href="#">Puestos Subordinados</a></li>			
			<li><a onClick="document.getElementById('tab8').style.display='block'; document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='none'; document.getElementById('tab3').style.display='none'; document.getElementById('tab4').style.display='none'; document.getElementById('tab5').style.display='none'; document.getElementById('tab7').style.display='none'; document.getElementById('tab6').style.display='none'; document.getElementById('tab9').style.display='none'; document.getElementById('tab10').style.display='none'; document.getElementById('tab11').style.display='none'; document.getElementById('tab12').style.display='none'; document.getElementById('tab13').style.display='none';" href="#">Otros Estudios</a></li>
			<li><a onClick="document.getElementById('tab9').style.display='block'; document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='none'; document.getElementById('tab3').style.display='none'; document.getElementById('tab4').style.display='none'; document.getElementById('tab5').style.display='none'; document.getElementById('tab7').style.display='none'; document.getElementById('tab8').style.display='none'; document.getElementById('tab6').style.display='none'; document.getElementById('tab10').style.display='none'; document.getElementById('tab11').style.display='none'; document.getElementById('tab12').style.display='none'; document.getElementById('tab13').style.display='none';" href="#">Objetivos y/o Metas</a></li>
			<li><a onClick="document.getElementById('tab9').style.display='none'; document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='none'; document.getElementById('tab3').style.display='none'; document.getElementById('tab4').style.display='none'; document.getElementById('tab5').style.display='none'; document.getElementById('tab7').style.display='none'; document.getElementById('tab8').style.display='none'; document.getElementById('tab6').style.display='none'; document.getElementById('tab10').style.display='none'; document.getElementById('tab11').style.display='none'; document.getElementById('tab12').style.display='block'; document.getElementById('tab13').style.display='none';" href="#">Ambiente de Trabajo</a></li>
			<li><a onClick="document.getElementById('tab9').style.display='none'; document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='none'; document.getElementById('tab3').style.display='none'; document.getElementById('tab4').style.display='none'; document.getElementById('tab5').style.display='none'; document.getElementById('tab7').style.display='none'; document.getElementById('tab8').style.display='none'; document.getElementById('tab6').style.display='none'; document.getElementById('tab10').style.display='none'; document.getElementById('tab11').style.display='none'; document.getElementById('tab12').style.display='none'; document.getElementById('tab13').style.display='block';" href="#">Habilidades/Destrezas</a></li>
			</ul>
			</div>
		</td>
	</tr>
</table>

<div id="tab1" style="display:block;">
<form id="frmentrada" name="frmentrada" action="cargos.php" method="POST" onsubmit="return verificarCargo(this, 'ACTUALIZAR');">
<?php
$sueldo=STRTR($field['NivelSalarial'], ".", ",");
echo "
<input type='hidden' name='codigo' id='codigo' value='".$field['CodCargo']."' />
<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />";
echo "
<div style='width:750px' class='divFormCaption'>Datos del Cargo</div>
<table width='750' class='tblForm'>
	<tr>
		<td class='tagForm'>Tipo:</td>
		<td>
			<select name='tipocargo' id='tipocargo' class='selectMed' onchange='getOptions_2(this.id, \"nivelcargo\"); setCargo(this.form);'>
				<option value=''></option>";
					getTCargo($field['CodTipoCargo'], 0);
			echo "
			</select>*
		</td>
	</tr>
	<tr>
		<td class='tagForm'>Nivel:</td>
		<td>
			<select name='nivelcargo' id='nivelcargo' class='selectMed' onchange='setCargo(this.form);'>
				<option value=''></option>";
					getNiveles($field['CodNivelClase'], $field['CodTipoCargo'], 0);
			echo "
			</select>*
		</td>
	</tr>
	<tr>
		<td class='tagForm'>Grupo Ocupacional:</td>
		<td>
			<select name='grupo' id='grupo' class='selectMed' onchange='getOptions_2(this.id, \"serie\")'>
				<option value=''></option>";
					getGrupos($field['CodGrupOcup'], 0);
			echo"
			</select>*
		</td>
	</tr>
	<tr>
		<td class='tagForm'>Serie Ocupacional:</td>
		<td>
			<select name='serie' id='serie' class='selectMed' onchange='setCargo(this.form);'>
				<option value=''></option>";
					getSeries($field['CodSerieOcup'], $field['CodGrupOcup'], 0);
			echo "
			</select>*
		</td>
	</tr>
    <tr>
        <td class='tagForm'>Clasificaci&oacute;n:</td>
        <td><input type='text' name='codcargo' id='codcargo' size='8' maxlength='6' value = '".$field['CodDesc']."' /></td>
    </tr>
	<tr>
		<td class='tagForm'>Descripci&oacute;n:</td>
		<td><input name='descripcion' type='text' id='descripcion' size='60' maxlength='100' value='".$field['DescripCargo']."' />*</td>
	</tr>
    <tr>
        <td class='tagForm'>Descripci&oacute;n Gen&eacute;rica:</td>
        <td><textarea name='descripcion_generica' id='descripcion_generica' cols='100' rows='3'>".($field['DescGenerica'])."</textarea>*</td>
    </tr>	
	<tr>
		<td class='tagForm'>Categor&iacute;a del Cargo:</td>
		<td>
			<select name='ttra' id='ttra' class='selectMed' onchange='getGradosCargo(this.form, this.value);'>
				<option value=''></option>";
					getMiscelaneos($field['CategoriaCargo'], "CATCARGO", 0);
			echo "
			</select>*
		</td>
	</tr>
	<tr>
		<td class='tagForm'>Grado del Cargo:</td>
		<td>
			<select name='gcargo' id='gcargo' onchange='getSueldoCargo(this.form, this.value)'>
				<option value=''></option>";
					getGCargo($field['CategoriaCargo'], $field['Grado'], 0);
			echo "
			</select>*
		</td>
	</tr>
	<tr>
		<td class='tagForm'>Sueldo B&aacute;sico:</td>
		<td><input name='sueldo' type='text' id='sueldo' size='20' maxlength='15' value='".$sueldo."' readonly />*</td>
	</tr>
	<tr>
		<td class='tagForm'>Plantilla de Competencias:</td>
		<td>
			<select name='plantilla_competencias' id='plantilla_competencias'>
				<option value=''></option>";
					getPlantillas($field['Plantilla'], 0);
			echo "
			</select>
		</td>
	</tr>
	<tr>
		<td class='tagForm'>Estado:</td>
		<td>";
			if ($field['Estado']=="A") echo "<input name='status' type='radio' value='A' checked /> Activo"; 
			else echo "<input name='status' type='radio' value='A' /> Activo";
			if ($field['Estado']=="I") echo "<input name='status' type='radio' value='I' checked /> Inactivo"; 
			else echo "<input name='status' type='radio' value='I' /> Inactivo";
		echo "
		</td>
	</tr>
	<tr>
		<td class='tagForm'>&Uacute;ltima Modif.:</td>
		<td>
			<input name='ult_usuario' type='text' id='ult_usuario' size='30' value='".$field['UltimoUsuario']."' readonly />
			<input name='ult_fecha' type='text' id='ult_fecha' size='25' value='".$field['UltimaFecha']."' readonly />
		</td>
	</tr>
</table>
<center> 
<input type='submit' value='Guardar Registro' />
<input name='bt_cancelar' type='button' id='bt_cancelar' value='Cancelar' onClick='cargarPagina(this.form, \"cargos.php\");' />
</center><br />";
?>
</form>
<div style="width:700px" class="divMsj">Campos Obligatorios *</div>
</div>

<div id="tab2" style="display:none;">
<?php
echo "
<div style='width:745px' class='divFormCaption'>Cargos Superiores</div>
<table align='center' cellpadding='0' cellspacing='0'>
	<tr>
    <td valign='top'>
			<iframe class='frameTab' style='height:250px; width:744px;' src='cargos_reporta.php?registro=".$_POST['registro']."'></iframe>
		</td>
  </tr>
</table><br />
<div style='width:745px' class='divFormCaption'>Relaciones Externas e Internas</div>
<table align='center' cellpadding='0' cellspacing='0'>
	<tr>
    <td valign='top'>
			<iframe class='frameTab' style='height:250px; width:744px;' src='cargos_relaciones.php?registro=".$_POST['registro']."'></iframe>
		</td>
  </tr>
</table>";
?>

</div>

<div id="tab3" style="display:none;">
<?php
echo "
<div style='width:745px' class='divFormCaption'>Funciones del Cargo</div>
<table align='center' cellpadding='0' cellspacing='0'>
	<tr>
    <td valign='top'>
			<iframe class='frameTab' style='height:500px; width:744px;' src='cargos_funciones.php?accion=ACTUALIZAR&codcargo=".$_POST['registro']."'></iframe>
		</td>
  </tr>
</table>";
?>

</div>

<div id="tab4" style="display:none;">
<?php
echo "
<div style='width:745px' class='divFormCaption'>Formaci&oacute;n Acad&eacute;mica</div>
<table align='center' cellpadding='0' cellspacing='0'>
	<tr>
    <td valign='top'>
			<iframe class='frameTab' style='height:175px; width:744px;' src='cargos_formacion.php?registro=".$_POST['registro']."'></iframe>
		</td>
  </tr>
</table><br />
<div style='width:745px' class='divFormCaption'>Dominio de Idiomas</div>
<table align='center' cellpadding='0' cellspacing='0'>
	<tr>
    <td valign='top'>
			<iframe class='frameTab' style='height:175px; width:744px;' src='cargos_idioma.php?registro=".$_POST['registro']."'></iframe>
		</td>
  </tr>
</table><br />
<div style='width:745px' class='divFormCaption'>Cursos de Inform&aacute;tica</div>
<table align='center' cellpadding='0' cellspacing='0'>
	<tr>
    <td valign='top'>
			<iframe class='frameTab' style='height:175px; width:744px;' src='cargos_informatica.php?registro=".$_POST['registro']."'></iframe>
		</td>
  </tr>
</table>";
?>

</div>

<div id="tab5" style="display:none;">
<?php
echo "
<div style='width:745px' class='divFormCaption'>Experiencias Previas</div>
<table align='center' cellpadding='0' cellspacing='0'>
	<tr>
    <td valign='top'>
			<iframe class='frameTab' style='height:500px; width:744px;' src='cargos_experiencia.php?registro=".$_POST['registro']."'></iframe>
		</td>
  </tr>
</table>";
?>
</div>


<div id="tab6" style="display:none;">
<?php
echo "
<div style='width:745px' class='divFormCaption'>Riesgos de Trabajo</div>
<table align='center' cellpadding='0' cellspacing='0'>
	<tr>
    <td valign='top'>
			<iframe class='frameTab' style='height:500px; width:744px;' src='cargos_riesgos.php?registro=".$_POST['registro']."'></iframe>
		</td>
  </tr>
</table>";
?>
</div>

<div id="tab7" style="display:none;">
<?php
echo "
<div style='width:745px' class='divFormCaption'>Puestos Subordinados</div>
<table align='center' cellpadding='0' cellspacing='0'>
	<tr>
    <td valign='top'>
			<iframe class='frameTab' style='height:500px; width:744px;' src='cargos_sub.php?registro=".$_POST['registro']."'></iframe>
		</td>
  </tr>
</table>";
?>
</div>


<div id="tab8" style="display:none;">
<?php
echo "
<div style='width:745px' class='divFormCaption'>Otros Estudios</div>
<table align='center' cellpadding='0' cellspacing='0'>
	<tr>
    <td valign='top'>
			<iframe class='frameTab' style='height:500px; width:744px;' src='cargos_cursos.php?registro=".$_POST['registro']."'></iframe>
		</td>
  </tr>
</table>";
?>

</div>

<div id="tab9" style="display:none;">
<?php
echo "
<div style='width:745px' class='divFormCaption'>Objetivos y/o Metas</div>
<table align='center' cellpadding='0' cellspacing='0'>
	<tr>
    <td valign='top'>
			<iframe class='frameTab' style='height:500px; width:744px;' src='cargos_metas.php?registro=".$_POST['registro']."'></iframe>
		</td>
  </tr>
</table>";
?>
</div>

<div id="tab10" style="display:none;">
<?php
echo "
<div style='width:745px' class='divFormCaption'>Competencias</div>
<table align='center' cellpadding='0' cellspacing='0'>
	<tr>
    <td valign='top'>
			<iframe class='frameTab' style='height:500px; width:744px;' src='cargos_competencias.php?codcargo=".$_POST['registro']."&accion=EDITAR'></iframe>
		</td>
  </tr>
</table>";
?>
</div>

<div id="tab11" style="display:none;">
<?php
echo "
<div style='width:745px' class='divFormCaption'>Evaluaci&oacute;n - Reclutamiento</div>
<table align='center' cellpadding='0' cellspacing='0'>
	<tr>
    <td valign='top'>
			<iframe class='frameTab' style='height:500px; width:744px;' src='cargos_evaluacion.php?codcargo=".$_POST['registro']."&accion=EDITAR'></iframe>
		</td>
  </tr>
</table>";
?>
</div>

<div id="tab12" style="display:none;">
<?php
echo "
<div style='width:745px' class='divFormCaption'>Ambiente de Trabajo</div>
<table align='center' cellpadding='0' cellspacing='0'>
	<tr>
    <td valign='top'>
			<iframe class='frameTab' style='height:250px; width:744px;' src='cargos_ambiente.php?codcargo=".$_POST['registro']."&accion=EDITAR'></iframe>
		</td>
  </tr>
</table><br />
<div style='width:745px' class='divFormCaption'>Esfuerzo de Trabajo</div>
<table align='center' cellpadding='0' cellspacing='0'>
	<tr>
    <td valign='top'>
			<iframe class='frameTab' style='height:250px; width:744px;' src='cargos_esfuerzo.php?codcargo=".$_POST['registro']."&accion=EDITAR'></iframe>
		</td>
  </tr>
</table>";
?>
</div>

<div id="tab13" style="display:none;">
<?php
echo "
<div style='width:745px' class='divFormCaption'>Habilidades y Destrezas</div>
<table align='center' cellpadding='0' cellspacing='0'>
	<tr>
    <td valign='top'>
			<iframe class='frameTab' style='height:500px; width:744px;' src='cargos_habilidades.php?codcargo=".$_POST['registro']."&accion=EDITAR'></iframe>
		</td>
  </tr>
</table>";
?>
</div>

</body>
</html>
