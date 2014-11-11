<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include ("fphp.php");
connect();
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
</head>
<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Maestro Tipo Correspondencia | Ver</td>
		<td align="right"><a class="cerrar" href="" onclick="window.close()">[cerrar]</a></td>
	</tr>
</table>
<hr width="100%" color="#333333" />
<?
  $sql="SELECT * FROM cp_tipocorrespondencia WHERE Cod_TipoDocumento='".$_GET['registro']."'";
  $qry=mysql_query($sql)  or die ($sql.mysql_error());
  $field=mysql_fetch_array($qry);
?>
<form id="frmentrada" name="frmentrada" method="post" action="cp_tipocorrespondencia.php?limit=0&accion=editarTipoCorrespondencia">
<div class="divFormCaption" style="width:700px;">Datos</div>
<table class="tblForm" width="700">
<tr> 
 <td width="194" class="tagForm"> C&oacute;d. Documento:</td>
 <td width="494"><input type="text" id="cod_tipodocumento" name="cod_tipodocumento" size="8" maxlength="4" value="<?=$field['Cod_TipoDocumento'];?>" readonly="readonly"/></td>
</tr>
<tr>
 <td class="tagForm">Descripci&oacute;n:</td>
 <td><input type="text" id="descripCorta" name="descripCorta" size="8" maxlength="2" value="<?=$field['DescripCorta'];?>" readonly />*</td>
</tr>
<tr>
 <td class="tagForm">Descripci&oacute;n: Completa:</td>
 <td><input type="text" id="descripcion" name="descripcion" size="55" value="<?=$field['Descripcion']?>" readonly />*</td>
</tr>
<tr>
 <td class="tagForm">Uso Interno</td>
 <td><?
      if($field['FlagUsoInterno']==1){$i='checked onclick="this.checked=!this.checked"';}else{$i='disabled="disabled"';}
	  if($field['FlagUsoExterno']==1){$e='checked onclick="this.checked=!this.checked"';}else{$e='disabled="disabled"';}
      if($field['FlagProcedenciaExterna']==1){$pe='checked onclick="this.checked=!this.checked"';}else{$pe='disabled="disabled"';}
echo"
     <input type='checkbox' name='uso_interno' id='uso_interno' value='1' $i $i_fun1/>
     Uso Externo
     <input type='checkbox' name='uso_externo' id='uso_externo' value='1' $e $e_fun1/>
     Procedencia Externa
     <input type='checkbox' name='proc_externa' id='proc_externa' value='1' $pe/>";
	?>*</td>
</tr>
<tr><td height="5"></td></tr>
<tr>
 <td class="tagForm">Estado:</td>
 <td>
   <?
  if($field['Estado']=='A'){$activo=checked; $funcion1='onclick="this.checked=!this.checked"';}else{$activo='disabled="disabled"';}
  if($field['Estado']=='I'){$inactivo=checked; $funcion2='onclick="this.checked=!this.checked"';}else{$inactivo='disabled="disabled"';}
  echo"Activo<input type='radio' name='estado' id='estado' $activo/> 
   Inactivo<input type='radio' name='estado' id='estado' $inactivo/>";
  ?>
   </td>
</tr>
<tr>
 <td class="tagForm">&Uacute;ltima Modif.:</td>
 <td><input type="text" id="usuario" size="20" value="<?=$field['UltimoUsuario']?>" readonly/>
     <input type="text" id="fecha" size="20" value="<?=$field['UltimaFechaModif'];?>" readonly/></td>
</tr>
<tr><td height="2"></td></tr>
</table>
</form>
</body></html>
