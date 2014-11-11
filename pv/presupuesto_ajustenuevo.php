<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include ("fphp.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('01', $concepto);
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
</head>
<body>
<style type="text/css">
<!--
UNKNOWN {
        FONT-SIZE: small
}
#header {
        FONT-SIZE: 93%; BACKGROUND: url(imagenes/bg.gif) #dae0d2 repeat-x 50% bottom; FLOAT: left; WIDTH: 100%; LINE-HEIGHT: normal
}
#header UL {
        PADDING-RIGHT: 10px; PADDING-LEFT: 10px; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 10px; LIST-STYLE-TYPE: none
}
#header LI {
        PADDING-RIGHT: 0px; PADDING-LEFT: 9px; BACKGROUND: url(imagenes/left.gif) no-repeat left top; FLOAT: left; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 0px
}
#header A {
        PADDING-RIGHT: 15px; DISPLAY: block; PADDING-LEFT: 6px; FONT-WEIGHT: bold; BACKGROUND: url(imagenes/right.gif) no-repeat right top; FLOAT: left; PADDING-BOTTOM: 4px; COLOR: #765; PADDING-TOP: 5px; TEXT-DECORATION: none
}
#header A {
        FLOAT: none
}
#header A:hover {
        COLOR: #333
}
#header #current {
        BACKGROUND-IMAGE: url(imagenes/left_on.gif)
}
#header #current A {
        BACKGROUND-IMAGE: url(imagenes/right_on.gif); PADDING-BOTTOM: 5px; COLOR: #333
}
-->
</style>
<table width="100%" cellspacing="0" cellpadding="0">
<tr>
 <td class="titulo">Nuevo Ajuste</td>
 <td align="right"><a class="cerrar"; href="../presupuesto/framemain.php">[Cerrar]</a></td>
</tr>
</table><hr width="100%" color="#333333" />

<form id="frmentrada" name="frmentrada" >
<table width="850" align="center">
<tr><td>
<div id="header">
<ul>
<!-- CSS Tabs PESTA�AS OPCIONES DE PRESUPUESTO -->
<li>
<a onClick="document.getElementById('tab1').style.display='block'; 
            document.getElementById('tab2').style.display='none';" href="#">Datos Generales</a></li>
<li>
<a onClick="document.getElementById('tab1').style.display='none'; 
            document.getElementById('tab2').style.display='block';" href="#">Detalle de Presupuesto</a></li>
</ul>
</div>
  </td>
</tr>
</table>
<div id="tab1" style="display:block;">
<div style="width:850px" class="divFormCaption">Informaci&oacute;n de Presupuesto</div>
<table width="850" class="tblForm">
<tr>
  <td width="75"></td>
  <td class="tagForm">Organismo:</td>
        <? $sql="SELECT * FROM mastorganismos";
		   $rs=mysql_query($sql) or die ($sql.mysql_error());
		   $field=mysql_fetch_array($rs);
		?>
  <td><input type="text" id="organismo" name="organismo" size="60" value="<?=$field['Organismo'] ?>" /></td>
  <td width="64" align="right">N� Gaceta:</td>
  <td width="54"><input name="gaceta" id="gaceta" type="text" size="8"/></td>
  <td width="49" align="right">Fecha:</td>
  <td width="140"><input name="gaceta" id="gaceta" type="text" size="8"/>(dd/mm/aa)</td>
</tr>
<tr>
 <td width="75"></td>
 <td colspan="2"></td>
 <td width="64" align="right">N� Decreto:</td>
 <td width="54"><input name="decreto" id="decreto" type="text" size="8"/></td>
 <td width="49" align="right">Fecha:</td>
 <td width="140"><input name="fdecreto" id="fdecreto" type="text" size="8"/>(dd/mm/aa)</td>
</tr>
</table>
<table width="850" class="tblForm">
<tr>
 <td class="tagForm">Fecha Ajuste:</td>
 <td><input type="text" id="fAjuste" name="fAjuste" size="12" maxlength="8"/></td>
 <td class="tagForm">Nro. Presupuesto:</td>
 <td><input id="npresupuesto" name="npresupuesto" type="text" size="8" maxlength="4" /></td>
 <td class="tagForm">Tipo Ajuste:</td>
 <td><select id="tAjuste" name="tAjuste">
      <option value=""></option>
	  <option value="PO">Positivo</option>
	  <option value="NE">Negativo</option>
     </select></td>
</tr>
</table>
<div style="width:850px" class="divFormCaption">Duraci&oacute;n del Ajuste</div>
<table class="tblForm" width="850">
<tr>
  <td class="tagForm">Fecha Inicio:</td>
  <td><input id="fInicio" name="fInicio" type="text" size="8" maxlength="10"/> *(dd-mm-aaaa)</td>
  <td class="tagForm">Duraci&oacute;n:</td>
  <td><input id="duracion" name="duracion" type="text" size="8" maxlength="10" readonly/> d&iacute;as</td>
</tr>
<tr>
  <td class="tagForm">Fecha Termino</td>
  <td><input id="fTermino" name="fTermino" type="text" size="8" maxlength="10" /> *(dd-mm-aaaa)</td>
</tr>
</table>
</div>

<div id="tab2" style="display:none;">
<div style="width:850px" class="divFormCaption">Informaci&oacute;n de Presupuesto</div>


</div>




</form>
</body>
</html>
