<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp.php");
connect();
//include "ControlCorrespondencia.php";
//include("gmcorrespondencia.php");

list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('03', $concepto);
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
UNKNOWN {FONT-SIZE: small}
#header {FONT-SIZE: 93%; BACKGROUND: url(imagenes/bg.gif) #dae0d2 repeat-x 50% bottom; FLOAT: left; WIDTH: 100%; LINE-HEIGHT: normal}
#header UL {PADDING-RIGHT: 10px; PADDING-LEFT: 10px; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 10px; LIST-STYLE-TYPE: none}
#header LI {
        PADDING-RIGHT: 0px; PADDING-LEFT: 9px; BACKGROUND: url(imagenes/left.gif) no-repeat left top; FLOAT: left; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 0px}
#header A {
        PADDING-RIGHT: 15px; DISPLAY: block; PADDING-LEFT: 6px; FONT-WEIGHT: bold; BACKGROUND: url(imagenes/right.gif) no-repeat right top; FLOAT: left; PADDING-BOTTOM: 4px; COLOR: #765; PADDING-TOP: 5px; TEXT-DECORATION: none}
#header A { FLOAT: none}
#header A:hover {  COLOR: #333 }
#header #current { BACKGROUND-IMAGE: url(imagenes/left_on.gif)}
#header #current A { BACKGROUND-IMAGE: url(imagenes/right_on.gif); PADDING-BOTTOM: 5px; COLOR: #333 }
-->
</style>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Documentos Externos | Ver Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />
<table width="900" align="center">
<tr>
  <td>
	<div id="header">
	<ul>
	<!-- CSS Tabs PESTAÑAS OPCIONES DE PRESUPUESTO -->
	<li><a onClick="document.getElementById('tab1').style.display='block';" href="#">Datos Generales</a></li>
	<li><a  href="#">Detalle Documento</a></li> 
	</ul>
	</div>
  </td>
</tr>
</table>

<?
 $sql="select * from cp_documentoextentrada where Cod_Documento='".$_GET['registro']."' "; echo $sql;
 $qry=mysql_query($sql) or die ($sql.mysql_error());
 $field=mysql_fetch_array($qry);
?>


<div id="tab1" style="display:block;">
<form name="frmentrada" id="frmentrada" action="cpe_entrada.php?limit=0&accion=EditarEntradaExterna" method="post">
<div style="width:895px; height:15px" class="divFormCaption">Datos Generales</div>
<table class="tblForm" width="895px" border="0">
<tr><td height="5"></td></tr>
<tr>
  <td width="118" class="tagForm">Tipo Documento:</td>
  <td width="320">
     <? 
	  echo"<input type='hidden' id='regresar' name='regresar' value='".$regresar."'/>";
	  echo"<input type='hidden' name='cod_documento' id='cod_documento' value='".$_POST['registro']."'/>
	       <input type='hidden' name='cod_tdocumento' id='cod_tdocumento' value='".$field['Cod_TipoDocumento']."'/>";
		    
	  $sql="SELECT * FROM cp_tipocorrespondencia WHERE FlagProcedenciaExterna='1'";
	  $qry=mysql_query($sql) or die ($sql.mysql_error());
	  $row=mysql_num_rows($qry);?>
      <select name="t_documento" id="t_documento" class="selectMed" disabled>
        <?
      for($i; $i<$row; $i++){
	    $fcorresp=mysql_fetch_array($qry);
		if($field['Cod_TipoDocumento']=$fcorresp['Cod_TipoDocumento']){
		?>
        <option value="<?=$fcorresp['Cod_TipoDocumento'];?>"><?=$fcorresp['Descripcion'];?></option>
        <?
		}else{
        ?>
        <option value="<?=$fcorresp['Cod_TipoDocumento'];?>"><?=$fcorresp['Descripcion'];?></option>
        <? }}?>
      </select>*</td>
   <td width="110" class="tagForm">Fecha Documento:</td>
   <? 
     list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaDocumentoExt']); $f_documentoext=$d.'-'.$m.'-'.$a;
	 list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaRegistro']); $f_registro=$d.'-'.$m.'-'.$a;
   ?>
 <td width="327"><input type="text" id="fecha_documento" name="fecha_documento" size="10" value="<?=$f_documentoext;?>" readonly/>*(dd-mm-aaaa)</td>
</tr>
<tr style="border-style:double">
 <td class="tagForm">Nro. Documento:</td>
 <td><input type="text" id="n_documento" name="n_documento" size="20" style="text-align:right" value="<?=$field['NumeroDocumentoExt'];?>" readonly/>*</td>
 <td class="tagForm">Fecha Recibido:</td>
 <td><input type="text" id="fecha_recibido" name="fecha_recibido" size="10" maxlength="10" value="<?=$f_registro;?>" readonly/>*(dd-mm-aaaa)</td>
</tr>
<tr><td height="5"></td></tr>
<tr>
 <td colspan="4"><div class="divBorder" style="border-color:#999999;"></div></td>
</tr>
<tr><td height="5"></td></tr>
<tr>
  <td class="tagForm">Organismo:</td>
  <td colspan="3"><select id="organismo" name="organismo" class="selectBig" onchange="getOptionsOrganismosExt2(this.id,'dependencia');" disabled>
        <option value=""></option>
        <? getOrganismosExt(1, $field['Cod_Organismos']); ?>
      </select>*</td>
</tr>
<tr>
  <td class="tagForm" colspan="">Dependencia:</td>
  <td colspan="3"><select id="dependencia" name="dependencia" class="selectBig" disabled>
  <option value=""></option>
  <? getDependenciaExt( $field['Cod_Dependencia'], $field['Cod_Organismos'], 1)?>
      </select>*</td>
</tr>
<tr>
 <td class="tagForm">Remitente:</td>
 <td><select id="remitente" name="remitente" class="selectBig" disabled>
 <? getRepreExt( $field['Remitente'], $field['Cod_Dependencia'], 1, $field['Cod_Organismos'])?>
      </select>*</td>
 <td class="tagForm">Cargo:</td>
 <td><select id="cargoremitente" name="cargoremitente" class="selectBig" disabled>
    <? getRepreExt( $field['Cargo'], $field['Cod_Dependencia'], 1, $field['Cod_Organismos'])?>
      </select>*</td>
</tr>
<tr><td height="5"></td></tr>
<tr>
 <td class="tagForm">Recibido por:</td>
 <?
  $sempleado="select 
                     rh_puestos.DescripCargo,
					 mastpersonas.Busqueda
                from 
				     mastpersonas, 
					 mastempleado, 
					 rh_puestos 
			   where 
			         mastpersonas.CodPersona='".$field['RecibidoPor']."' and
					 mastempleado.CodPersona='".$field['RecibidoPor']."' and
					 rh_puestos.CodCargo=mastempleado.CodCargo";
  $qempleado=mysql_query($sempleado) or die ($sempleado.mysql_error());
  $fempleado=mysql_fetch_array($qempleado);
 ?>
 <td><input name="codempleado" type="hidden" id="codempleado" value="<?=$field['RecibidoPor']?>" />
	 <input name="nomempleado" id="nomempleado" type="text" size="52" value="<?=$fempleado['Busqueda']?>" readonly/>
	 <input name="bt_examinar" id="bt_examinar" type="button" value="..." />*
 </td>
 <td class="tagForm">Cargo:</td>
 <td><input type="text" id="cargoremit" name="cargoremit" value="<?=$fempleado['DescripCargo']?>" size="68" readonly/></td>
</tr>

<tr>
  <td class="tagForm">Asunto Tratado:</td>
  <td colspan="1"><input type="text" id="asunto" name="asunto" size="60" value="<?=$field['Asunto']?>" readonly/>*</td>
  <td class="tagForm"></td>
  <td></td>
</tr>
<tr>
 <td class="tagForm">Descripci&oacute;n:</td>
 <td colspan="1"><input type="text" id="descrip" name="descrip" size="60" value="<?=$field['Descripcion']?>" readonly/>*</td>
 <td class="tagForm">Folio:</td>
 <td><input type="text" id="folio" name="folio" size="2" maxlength="3" style="text-align:right" value="<?=$field['Folio']?>" readonly />* 
     Anexo: <input type="text" id="anexofolio" name="anexofolio" size="2" maxlength="3" style="text-align:right" value="<?=$field['AnexoFolio']?>" readonly/>*</td>
</tr>
<tr>
  <td class="tagForm">Comentario:</td>
  <td colspan="1"></td>
  <td class="tagForm">Descripci&oacute;n:</td>
  <td></td>
</tr>
<tr>
  <td class="tagForm"></td>
  <td colspan="1"><textarea name="comentario" id="comentario" rows="2" cols="65" readonly><?=$field['Comentario']?></textarea></td>
  <td></td>
  <td><textarea name="descpfolio" id="descpfolio" rows="2" cols="65" readonly><?=$field['DescpFolio']?></textarea></td>
</tr>
<tr><td height="5"></td></tr>
<tr>
 <td colspan="4">
  <table align="center" width="400">
  <tr>
     <?
      if($field['UltimaFechaModif']!='0000-00-00 00:00:00'){ $u_usuario=$field['UltimoUsuario']; $u_fechamodif=$field['UltimaFechaModif'];}
	 ?>
     <td width="92" class="tagForm">Ultima Modif.:</td>
     <td width="296" colspan="2">
     <input type="text" id="ultimousuario" size="20" value="<?=$u_usuario?>" readonly="readonly"/><input type="text" id="ultimafecha" size="20" value="<?=$u_fechamodif?>" readonly="readonly"/></td>
  </tr>
  </table>
 </td>
</tr>
</table>
</form> 
</div>
</body>
</html>
