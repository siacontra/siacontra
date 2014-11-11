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
		<td class="titulo">Contratos del Empleado</td>
		<td align="right"><a class="cerrar" href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form id="frmentrada" name="frmentrada" action="contratos_vigentes.php?limit=0" method="POST" target="itab">
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
	<tr>
		<td align="right">Organismo:</td>
		<td>
			<input type="checkbox" name="chkorganismo" id="chkorganismo" value="1" onclick="enabledOrganismo(this.form);" checked="checked" />
			<select name="forganismo" id="forganismo" class="selectBig" onchange="getFOptions_2(this.id, 'fdependencia', 'chkdependencia');">
            	<option value=""></option>
				<?php getOrganismos($_SESSION["FILTRO_ORGANISMO_ACTUAL"], 3); ?>
			</select>
		</td>
		<td align="right">Tipo de Contrato:</td>
		<td>
			<input type="checkbox" name="chktcon" value="1" onclick="enabledTipoCon(this.form);" />
			<select name="ftcon" id="ftcon" class="selectMed" disabled>
            	<option value=""></option>
				<?php getTContratos('', 0); ?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">Dependencia:</td>
		<td>
			<input type="checkbox" name="chkdependencia" id="chkdependencia" value="1" onclick="enabledDependencia(this.form);" checked="checked" />
			<select name="fdependencia" id="fdependencia" class="selectBig">
            	<option value=""></option>
				<?php getDependencias($_SESSION["FILTRO_DEPENDENCIA_ACTUAL"], $_SESSION["FILTRO_ORGANISMO_ACTUAL"], 3); ?>
			</select>
		</td>
		<td align="right">Vigencia de Contrato:</td>
		<td>
			<input type="checkbox" name="chkvcontrato" value="1" onclick="enabledVContrato(this.form);" />
			<input type="text" name="fvcontratod" size="15" maxlength="10" onkeyup="forzarFecha(this.form, this);" disabled /> - 
			<input type="text" name="fvcontratoh" size="15" maxlength="10" onkeyup="forzarFecha(this.form, this);" disabled />
		</td>
	</tr>
	<tr>
		<td align="right">Persona:</td>
		<td>
			<input type="checkbox" name="chkpersona" value="1" onclick="enabledPersona(this.form);" />
			<select name="sltpersona" id="sltpersona" class="select3" disabled>
				<?php getRelacionales(''); ?>
			</select>
			<input type="text" name="fpersona" size="8" maxlength="6" disabled />
		</td>
		<td align="right">Fecha de Firma:</td>
		<td>
			<input type="checkbox" name="chkfirma" value="1" onclick="enabledFirma(this.form);" />
			<input type="text" name="ffirmad" size="15" maxlength="10" onkeyup="forzarFecha(this.form, this);" disabled /> - 
			<input type="text" name="ffirmah" size="15" maxlength="10" onkeyup="forzarFecha(this.form, this);" disabled />
		</td>
	</tr>	
</table>
</div>
<center><input type="button" name="btBuscar" value="Buscar" onclick="filtroContratos(this.form);"></center>

<br /><div class="divDivision">Lista de Empleados</div><br />

<table width="1000" align="center">
  <tr>
   	<td>
			<div id="header">
			<ul>
			<!-- CSS Tabs -->
			<li><a onClick="frmentrada.action='contratos_vigentes.php?limit=0'; filtroContratos(document.getElementById('frmentrada'));" href="#">Contratos Vigentes</a></li>			
			<li><a onClick="frmentrada.action='contratos_vencidos.php?limit=0'; filtroContratos(document.getElementById('frmentrada'));" href="#">Contratos Vencidos</a></li>
			<li><a onClick="frmentrada.action='contratos_porvencer.php?limit=0'; filtroContratos(document.getElementById('frmentrada'));" href="#">Contratos por Vencer</a></li>
			<li><a onClick="frmentrada.action='contratos_sin.php?limit=0'; filtroContratos(document.getElementById('frmentrada'));" href="#">Empleados sin Contrato</a></li>
			</ul>
			</div>
		</td>
	</tr>
</table>
</form>

<table height="750" align="center" cellpadding="0" cellspacing="0">
	<tr>
    <td valign="top">
		<iframe name="itab" id="itab" scrollbars="no" frameborder="0" height="500" width="1100" src="contratos_vigentes.php?limit=0"></iframe>
	</td>
  </tr>
</table>

</body>
</html>