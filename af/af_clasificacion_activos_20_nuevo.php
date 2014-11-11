<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--<link href="css1.css" rel="stylesheet" type="text/css" />-->
<link href="../css/estilo.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="../css/prettyPhoto.css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" /><script type="text/javascript" language="javascript" src="af_fscript.js"></script>
<script type="text/javascript" language="javascript" src="af_fscript01.js"></script>
</head>
<body>
<!--<body onload="document.getElementById('codigo').focus();">-->
<?php
include("af_fphp.php");
connect();
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Clasificaci&oacute;n de Activos - Publicaci&oacute;n 20 | Nuevo Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'af_clasificacion_activos_20.php');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="af_clasificacion_activos_20.php" method="POST" onsubmit="return verificarClasificacionActivo20(this);">
<input type="hidden" name="filtro" id="filtro" value="<?=$filtro?>" />
<div style="width:700px" class="divFormCaption">Datos del Registro</div>
<table width="700" class="tblForm">
	<tr>
       <td class="tagForm">Nivel:</td>
       <td><select id="nivel" name="nivel" onchange="activarVisible(this.form, this.id,'codigo2');">
             <?
             $snivel = "select distinct(Nivel) from af_clasificacionactivo20 order by Nivel";
			 $qnivel = mysql_query($snivel) or die ($snivel.mysql_error());
			 $rnivel = mysql_num_rows($qnivel); 
			 for($i=0;$i<$rnivel;$i++){
			   $fnivel = mysql_fetch_array($qnivel);
			   if($fnivel['Nivel']!='$nivel'){
				  $nivel = $fnivel['Nivel']; 
			      echo"<option value='".$fnivel['Nivel']."'>".$fnivel['Nivel']."</option>";
			   }
			 }
			 ?>
           </select></td>
    </tr>
    <tr>
		<td class="tagForm">C&oacute;digo:</td><? echo"<input type='hidden' id='valorNivel' name='valorNivel' value='$valorNivel'/>"; ?>
		<td><div style="display:block;width:100px" id="cod1"><input name="codigo1" id="codigo1" type="text"  size="15" maxlength="8"/>*</div><div style="display:none;width:200px" id="cod2"><select id="codigo2" name="codigo2"><option value=""></option></select>*</div></td>
	</tr>
	<tr>
		<td class="tagForm">Descripci&oacute;n:</td>
		<td><input name="descripcion" type="text" id="descripcion" maxlength="255" style="width:95%;" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Estado:</td>
		<td>
			<input id="activo" name="status" type="radio" value="A" checked="checked" /> Activo
			<input id="inactivo" name="status" type="radio" value="I" /> Inactivo
		</td>
	</tr>
	<tr>
	<td class="tagForm">&Uacute;ltima Modif.:</td>
	<td>
		<input name="ult_usuario" type="text" id="ult_usuario" size="30"  disabled="disabled" />
		<input name="ult_fecha" type="text" id="ult_fecha" size="25"  disabled="disabled" />
	</td>
	</tr>
</table>
<center> 
<input type="submit" value="Guardar Registro" />
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onClick="cargarPagina(this.form, 'af_clasificacion_activos_20.php');" />
</center><br />
</form>

<div style="width:700px" class="divMsj">Campos Obligatorios *</div>
</body>
</html>
