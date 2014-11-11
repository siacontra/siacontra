<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('01', $concepto);
//	------------------------------------
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
		<td class="titulo">Antecedentes de Servicio | Agregar</td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" action="antecedentes_empleado.php" method="POST" onsubmit="return verificarAntecedenteEmpleado(this, document.getElementById('accion').value);">

<?php
//	OBTENGO LOS DATOS DEL EMPLEADO
$sql = "SELECT me.CodEmpleado, mp.NomCompleto FROM mastpersonas mp INNER JOIN mastempleado me ON (mp.CodPersona = me.CodPersona) WHERE mp.CodPersona = '".$registro."'";
$query = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
?>

<input name="secuencia" type="hidden" id="secuencia" />
<input name="sec" type="hidden" id="sec" />
<input name="accion" type="hidden" id="accion" value="GUARDAR" />
<input name="chkorganismo" type="hidden" id="chkorganismo" value="<?=$chkorganismo?>" />
<input name="forganismo" type="hidden" id="forganismo" value="<?=$forganismo?>" />
<input name="chkedoreg" type="hidden" id="chkedoreg" value="<?=$chkedoreg?>" />
<input name="fedoreg" type="hidden" id="fedoreg" value="<?=$fedoreg?>" />
<input name="chkdependencia" type="hidden" id="chkdependencia" value="<?=$chkdependencia?>" />
<input name="fdependencia" type="hidden" id="fdependencia" value="<?=$fdependencia?>" />
<input name="chksittra" type="hidden" id="chksittra" value="<?=$chksittra?>" />
<input name="fsittra" type="hidden" id="fsittra" value="<?=$fsittra?>" />
<input name="chktiponom" type="hidden" id="chktiponom" value="<?=$chktiponom?>" />
<input name="ftiponom" type="hidden" id="ftiponom" value="<?=$ftiponom?>" />
<input name="chkbuscar" type="hidden" id="chkbuscar" value="<?=$chkbuscar?>" />
<input name="sltbuscar" type="hidden" id="sltbuscar" value="<?=$sltbuscar?>" />
<input name="fbuscar" type="hidden" id="fbuscar" value="<?=$fbuscar?>" />
<input name="chktipotra" type="hidden" id="chktipotra" value="<?=$chktipotra?>" />
<input name="ftipotra" type="hidden" id="ftipotra" value="<?=$ftipotra?>" />
<input name="chkndoc" type="hidden" id="chkndoc" value="<?=$chkndoc?>" />
<input name="fndoc" type="hidden" id="fndoc" value="<?=$fndoc?>" />
<input name="chkordenar" type="hidden" id="chkordenar" value="<?=$chkordenar?>" />
<input name="fordenar" type="hidden" id="fordenar" value="<?=$fordenar?>" />
<input name="chkpersona" type="hidden" id="chkpersona" value="<?=$chkpersona?>" />
<input name="sltpersona" type="hidden" id="sltpersona" value="<?=$sltpersona?>" />
<input name="fpersona" type="hidden" id="fpersona" value="<?=$fpersona?>" />
<input name="chkedad" type="hidden" id="chkedad" value="<?=$chkedad?>" />
<input name="sltedad" type="hidden" id="sltedad" value="<?=$sltedad?>" />
<input name="fedad" type="hidden" id="fedad" value="<?=$fedad?>" />
<input name="chkingreso" type="hidden" id="chkingreso" value="<?=$chkingreso?>" />
<input name="fingresod" type="hidden" id="fingresod" value="<?=$fingresod?>" />
<input name="fingresoh" type="hidden" id="fingresoh" value="<?=$fingresoh?>" />
<input name="limit" type="hidden" id="limit" value="<?=$limit?>" />
<input name="filtro" type="hidden" id="filtro" value="<?=$filtro?>" />
<input name="ordenar" type="hidden" id="ordenar" value="<?=$ordenar?>" />
<input name="registro" type="hidden" id="registro" value="<?=$registro?>" />
<div class="divBorder" style="width:750px;">
<table width="750" class="tblFiltro">
	<tr>
		<td align="right">Empleado:</td>
		<td><input type="text" size="10" value="<?=$field["CodEmpleado"]?>" readonly /></td>
	</tr>
	<tr>
		<td align="right">Nombre Completo:</td>
		<td><input type="text" size="75" value="<?=$field["NomCompleto"]?>" readonly /></td>
	</tr>
</table>
</div><br />

<div style="width:750px" class="divFormCaption">Datos del Antecedente</div>
<table width="750" class="tblForm">
   <tr>
   	<td class="tagForm">Organismo:</td>
      <td><input name="organismo" type="text" id="organismo" size="60" maxlength="100" />*</td>
   </tr>
   <tr>
      <td class="tagForm">Fecha de Ingreso:</td>
      <td><input name="fingreso" type="text" id="fingreso" size="15" maxlength="10" onKeyUp="getEdadAMD(this.form, this.value, document.getElementById('fegreso').value);" />*</td>
	</tr>
	<tr>
      <td class="tagForm">Fecha de Egreso:</td>
      <td><input name="fegreso" type="text" id="fegreso" size="15" maxlength="10"onKeyUp="getEdadAMD(this.form, document.getElementById('fingreso').value, this.value);" />*</td>
   </tr>
	<tr>
      <td class="tagForm">Tiempo de Servicio:</td>
      <td>
			<input name="anios" type="text" id="anios" size="4" readonly="true" />
			<input name="meses" type="text" id="meses" size="2" readonly="true" />
			<input name="dias" type="text" id="dias" size="2" readonly="true" />
		</td>
   </tr>
   <tr>
   	<td class="tagForm">&Uacute;ltima Modif.:</td>
      <td>
      	<input name="ult_usuario" type="text" id="ult_usuario" size="30" readonly />
         <input name="ult_fecha" type="text" id="ult_fecha" size="25" readonly />
		</td>
	</tr>
</table>
<center> 
<input type="submit" value="Insertar Antecedente" />
<input type="button" value="Limpiar Formulario" onclick="limpiarFormEmpleadoAntecedente(this.form);" />
<input name="btCancelar" type="button" id="btCancelar" value="Cancelar" onclick="cargarPagina(this.form, 'antecedentes_listado.php');" />
</center>
<div style="width:750px" class="divMsj">Campos Obligatorios *</div><br /><br />

<table width="750" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">
			<input name="btEditar" class="btLista" type="button" id="btEditar" value="Editar" onclick="editarEmpleadoAntecedente(this.form);" />
			<input name="btEliminar" class="btLista" type="button" id="btEliminar" value="Eliminar" onclick="eliminarEmpleadoAntecedente(this.form);" />
			<input name="btPDF" class="btLista" type="button" id="btPDF" value="PDF" onclick="pdfAntecedentes();" />
		</td>
	</tr>
</table>

<table width="750" class="tblLista">
	<tr class="trListaHead">
		<th scope="col">Organismo</th>
		<th scope="col" width="75" >Fecha de Ingreso</th>
		<th scope="col" width="75">Fecha de Egreso</th>
		<th scope="col" width="50">A&ntilde;os</th>
		<th scope="col" width="50">Meses</th>
		<th scope="col" width="50">Dias</th>
	</tr>

	<?php
	$total_anios = 0;
	$total_meses = 0;
	$total_dias = 0;
	//	Consulto los antecedentes del empleado...
	$sql = "SELECT * FROM rh_empleado_antecedentes WHERE CodPersona = '".$registro."' ORDER BY FIngreso DESC";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	while ($field = mysql_fetch_array($query)) {
		list($a, $m, $d)=SPLIT( '[/.-]', $field['FIngreso']); $fingreso=$d."-".$m."-".$a;
		list($a, $m, $d)=SPLIT( '[/.-]', $field['FEgreso']); $fegreso=$d."-".$m."-".$a;
		
		list ($a, $m, $d) = getEdadAMD($fingreso, $fegreso);
		$anios = $a; 
		$meses = $m; 
		$dias = $d;
		
		if ($dias == 30) { $meses++; $dias = 0; }
		if ($meses == 13) { $anios++; $meses = 0; }
		
		$total_anios += (int) $anios;
		$total_meses += (int) $meses;
		$total_dias += (int) $dias;
		?>
		<tr class="trListaBody" onclick="mClk(this, 'sec');" id="<?=$field['Secuencia']?>">
			<td><?=($field['Organismo'])?></td>
			<td align="center"><?=$fingreso?></td>
			<td align="center"><?=$fegreso?></td>
			<td align="center"><?=$anios?></td>
			<td align="center"><?=$meses?></td>
			<td align="center"><?=$dias?></td>
		</tr>
		<?
	}
	
	if ($total_dias >= 30) {
		$div = (int) ($total_dias / 30);
		$total_meses = $total_meses + $div;
		$total_dias = $total_dias - ($div * 30);
	}
	if ($total_meses >= 12) {
		$div = (int) ($total_meses / 12);
		$total_anios = $total_anios + $div;
		$total_meses = $total_meses - ($div * 12);
	}
	?>
	<tr>
		<td colspan="3">&nbsp;</td>
		<td align="center" class="trListaBody2"><?=$total_anios?></td>
		<td align="center" class="trListaBody2"><?=$total_meses?></td>
		<td align="center" class="trListaBody2"><?=$total_dias?></td>
	</tr>
	<? if ($total_meses >= 8) { 
		$total_total_anios = $total_anios + 1; 
		?>
		<tr>
			<td colspan="3">&nbsp;</td>
			<td align="center" class="trListaBody2"><?=$total_total_anios?></td>
			<td align="center" class="trListaBody2">0</td>
			<td align="center" class="trListaBody2"><?=$total_dias?></td>
		</tr>
	<? } else $total_total_anios = $total_anios; ?>
</table>
</form>
</body>
</html>
