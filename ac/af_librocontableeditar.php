<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
include ("fphp.php");
connect();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" language="javascript" src="af_fscript.js"></script>
</head>
<body>

<table width="100%" cellspacing="0" cellpadding="0">
 <tr>
  <td class="titulo">Maestro Libro Contable | Editar Registro</td>
  <td align="right">
   <a class="cerrar" href="framemain.php">[cerrar]</a>
  </td>
 </tr>
</table>

<hr width="100%" color="#333333" />
<?
 $sql="SELECT * FROM ac_librocontable WHERE CodLibroCont='".$_POST['registro']."'";
 $qry=mysql_query($sql) or die ($sql.mysql_error());
 $field=mysql_fetch_array($qry); 
?>
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<form name="fentrada" id="fentrada" action="af_librocontable.php" method="POST" onsubmit="return  editarLibroContable(this);">
<?php 
  echo "<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />
        <input type='hidden' name='registro' id='registro' value='".$_POST['registro']."' "; 
?>

<div style="width:700px" class="divFormCaption"></div>
<table width="700" class="tblForm">
  <tr>
    <td class="tagForm">Libro Contable:</td>
    <td><input type="text" name="cod_librocontable"  id="cod_librocontable" size="4" maxlength="2" value="<?=$field['CodLibroCont']?>" style="text-align:left" readonly/></td>
  </tr>
  <tr>
    <td class="tagForm">Descripci&oacute;n:</td>
    <td><input type="text" name="descp_libro"  id="descp_libro" size="60" maxlength="100" value="<?=htmlentities($field['Descripcion'])?>" />*</td>
  </tr>
   <tr>
	    <td class='tagForm'>Estado:</td>
	    <td> <input type="hidden" name="radioEstado" id="radioEstado" value="<?=$field['Estado'];?>"/>
        <?
         if($field['Estado']=='A'){ 
		    echo "<input name='radio1' type='radio' checked='checked' onclick='estadosPosee(this.form);'/> Activo
		         <input name='radio2' type='radio' onclick='estadosPosee(this.form);'/> Inactivo";
		 }else{
		     echo "<input name='radio1' type='radio' onclick='estadosPosee(this.form);'/> Activo
		           <input name='radio2' type='radio' checked='checked' onclick='estadosPosee(this.form);'/> Inactivo";
		 }
		?>
	   </td>
  </tr>
  <tr>
   <td height="5"></td>
  </tr>
  <tr>
   <?
     if($field['UltimaFechaModif']=='0000-00-00 00:00:00'){
	   $u_usuario=''; $u_fecha;
	 }else{
	   $u_usuario=$field['UltimoUsuario']; $u_fecha=$field['UltimaFechaModif'];
	 }
     echo"
	<td class='tagForm'>&Uacute;ltima Modif.:</td>
	<td>
	  <input name='ult_usuario' type='text' id='ult_usuario' size='30' value='$u_usuario'  readonly />
	  <input name='ult_fecha' type='text' id='ult_fecha' size='20' value='$u_fecha' style='text-align:right' readonly />
	</td>";
	?>
  </tr>
</table>
  <!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
  <center>
    <input name="guardar" type="submit" id="guardar" value="Guardar Registro"/>
    <input name="cancelar" type="button" id="cancelar" value="Cancelar" onClick="cargarPagina(this.form, 'af_librocontable.php');" />
  </center><br />
  <!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
</form>
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<div style="width:700px" class="divMsj">Campos Obligatorios *</div>
</body>
</html>

