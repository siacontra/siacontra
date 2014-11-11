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
<link type="text/css" rel="stylesheet" href="../css/prettyPhoto.css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<script type="text/javascript" language="javascript" src="af_fscript.js"></script>
<script type="text/javascript" src="../js/funciones.js" charset="utf-8"></script>
</head>

<body onload="document.getElementById('codigo').focus();">
<?php
include("af_fphp.php");
connect();
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Tipo de Movimiento Activos | Editar Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'af_tipomovimientoactivo.php');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="af_clasificacion_activos.php" method="POST" onsubmit="return verificarClasificacionActivo(this, 'GUARDAR');">
<input type="hidden" name="filtro" id="filtro" value="<?=$filtro?>" />
<div style="width:700px" class="divFormCaption">Datos del Registro</div>
<?
 $sql = "select * from af_tipomovimientos where CodTipoMovimiento = '".$_GET['registro']."'";
 $qry = mysql_query($sql) or die ($sql.mysql_error());
 $row = mysql_num_rows($qry);
 if($row!=0) $field = mysql_fetch_array($qry);

?>
<table width="700" class="tblForm">
	<tr>
		<td class="tagForm">C&oacute;digo:</td>
		<td><input name="codigo" type="text" id="codigo" size="10" maxlength="2" value="<?=$field['CodTipoMovimiento'];?>" readonly/>*</td>
	</tr>
	<tr>
		<td class="tagForm">Descripci&oacute;n:</td>
		<td><input name="descripcion" type="text" id="descripcion" maxlength="255" value="<?=htmlentities($field['DescpMovimiento']);?>" style="width:90%;"/>*</td>
	</tr>
	<tr>
		<td class="tagForm">Estado:</td>
		<td>
			<? if($field['Estado']=='A'){ ?>
				<input id="activo" name="status" type="radio" value="A" checked="checked" /> Activo
			    <input id="inactivo" name="status" type="radio" value="I" /> Inactivo
            <? }else{ ?>
                <input id="activo" name="status" type="radio" value="A"  /> Activo
			    <input id="inactivo" name="status" type="radio" value="I" checked="checked" /> Inactivo
            <? }?>
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
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onClick="cargarPagina(this.form, 'af_tipomovimientoactivo.php');" />
</center><br />
</form>

<div style="width:700px" class="divMsj">Campos Obligatorios *</div>
</body>
</html>
