<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp_lg.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('06', $concepto);
//	------------------------------------
list($AnioActual, $MesActual, $DiaActual) = split("[/.-]", substr($Ahora, 0, 10));
$FechaActual = "$DiaActual-$MesActual-$AnioActual";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_lg.js"></script>

<!--Hoja de estilos del calendario -->

<script src="../comunes/js/calendario/js/jscal2.js"></script>
<script src="../comunes/js/calendario/js/lang/es.js"></script>
<link rel="stylesheet" type="text/css" href="../comunes/js/calendario/css/jscal2.css" />
<link rel="stylesheet" type="text/css" href="../comunes/js/calendario/css/border-radius.css" />
<link rel="stylesheet" type="text/css" href="../comunes/js/calendario/css/steel/steel.css" />

</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Cat&aacute;logo de Proveedores</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" method="post" action="reporte_catalogo_proveedores_pdf.php" target="iReporte">
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
    <tr>
        <td align="right">Nacionalidad:</td>
        <td>
        	<input type="checkbox" name="chknacionalidad" id="chknacionalidad" value="1" onclick="chkFiltro(this.checked, 'fnacionalidad');" />
			<select name="fnacionalidad" id="fnacionalidad" style="width:200px;" disabled="disabled">
            	<option value="">:::. Seleccione .:::</option>
				 <?=loadSelectValores("NACIONALIDAD","", 0)?>

			</select>
        </td>
        <td align="right">Tipo de Servicio:</td>
        <td>
        	<input type="checkbox" name="chktiposervicio" id="chktiposervicio" value="1" onclick="chkFiltro(this.checked, 'ftiposervicio');" />
			<select name="ftiposervicio" id="ftiposervicio" style="width:200px;" disabled="disabled">
            	<option value="">:::. Seleccione .:::</option>
				<?=loadSelect("masttiposervicio", "CodTipoServicio", "Descripcion", "", 0)?>
			</select>
        </td>
    </tr>
    <tr>
        <td align="right">Ordenado por:</td>
        <td>
        	<input type="checkbox" name="chkordenadopor" id="chkordenadopor" value="1" onclick="this.checked=!this.checked" checked="checked" />
			<select name="fordenadopor" id="fordenadopor" style="width:200px;">
				<?=loadSelectValores("ORDENAR-PROVEEDORES", "p.CodProveedor", 0)?>
			</select>
		</td>
        <td align="right">Ingresado el:</td>
        <td>
        	<input type="checkbox" name="chkfingresado" id="chkfingresado" value="1" onclick="chkFiltro(this.checked, 'fingresado');" />
            <input name="fingresado" type="text" id="fingresado" disabled="disabled" size="8" maxlength="10" align="middle"/> *<i>(dd-mm-aaaa)</i>
		</td>
    </tr>
    <tr>
        <td align="right">Estado:</td>
        <td>
        	<input type="checkbox" name="chkestado" id="chkestado" value="1" onclick="chkFiltro(this.checked, 'festado');" />
			<select name="festado" id="festado" style="width:200px;" disabled="disabled">
            	<option value="">:::. Seleccione .:::</option>
				<?=loadSelectValores("ESTADO", "", 0)?>
			</select>
        </td>
        <td align="right">Buscar:</td>
        <td>
        	<input type="checkbox" name="chkfbuscar" id="chkfbuscar" value="1" onclick="chkFiltro(this.checked, 'fbuscar');" />
			<input type="text" name="fbuscar" id="fbuscar" style="width:200px;" disabled="disabled" />
		</td>
    </tr>
</table>
</div>
<center><input type="submit" name="btBuscar" value="Buscar"></center>
</form> 

<br /><div class="divDivision">Cat&aacute;logo de Proveedores</div><br />

<center>
<iframe name="iReporte" id="iReporte" style="border:solid 1px #CDCDCD; width:1000px; height:750px;"></iframe>
</center>
   <script type="text/javascript">//<![CDATA[
          Calendar.setup({
             inputField : "fingresado",
             trigger    : "fingresado",
             onSelect   : function() { this.hide() },
             showTime   : 12,
             dateFormat : "%d-%m-%Y"
         });
    //]]></script>
</body>
</html>