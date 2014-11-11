<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_ac.js"></script>
</head>

<body onload="document.getElementById('codigo').focus();">
<?php
include("fphp_ac.php");
connect();
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Modelo de Voucher | Nuevo Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="document.getElementById('frmentrada').submit();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="ac_modelo_voucher.php" method="POST" onsubmit="return validarModeloVoucher(this, 'INSERTAR');">
<input type="hidden" name="filtro" id="filtro" value="<?=$filtro?>" />
<div style="width:800px" class="divFormCaption">Datos del Registro</div>
<table width="800" class="tblForm">
	<tr>
		<td class="tagForm" width="125">Modelo:</td>
		<td><input type="text" id="codigo" maxlength="4" style="width:75px;" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Descripci&oacute;n:</td>
		<td><input type="text" id="descripcion" maxlength="75" class="selectBig" />*</td>
	</tr>
    <tr>
        <td class="tagForm">Distribuci&oacute;n:</td>
        <td>
            <select name="distribucion" id="distribucion" class="selectBig">
                <?=loadSelectValores("DISTRIBUCION-VOUCHER", "CP", 0)?>
            </select>*
        </td>
    </tr>
    <tr>
        <td class="tagForm">Libro Contable:</td>
        <td>
            <select name="libro" id="libro" class="selectBig">
                <?=loadSelect("ac_librocontable", "CodLibroCont", "Descripcion", "", 0)?>
            </select>*
        </td>
    </tr>
    <tr>
        <td class="tagForm">Organismo:</td>
        <td>
            <select name="organismo" id="organismo" class="selectBig" onchange="getOptions_2(this.id, 'dependencia')">
            <?=getOrganismos($_SESSION["ORGANISMO_ACTUAL"], 0); ?>
        </select>*
        </td>
    </tr>
    <tr>
        <td class="tagForm">Dependencia:</td>
        <td>
            <select name="dependencia" id="dependencia" class="selectBig">
            	<?=getDependencias($_SESSION["DEPENDENCIA_ACTUAL"], $_SESSION["ORGANISMO_ACTUAL"], 0)?>
            </select>*
        </td>
    </tr>
	<tr>
		<td class="tagForm">Estado:</td>
		<td>
			<input id="activo" name="estado" type="radio" value="A" checked /> Activo &nbsp;&nbsp;
			<input id="inactivo" name="estado" type="radio" value="I" /> Inactivo
		</td>
	</tr>
	<tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td>
			<input name="ult_usuario" type="text" id="ult_usuario" size="30" disabled="disabled" />
			<input name="ult_fecha" type="text" id="ult_fecha" size="25" disabled="disabled" />
		</td>
	</tr>
</table>
<center> 
<input type="submit" value="Guardar Registro" />
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onclick="document.getElementById('frmentrada').submit();" />
</center>
<div style="width:800px" class="divMsj">Campos Obligatorios *</div>
</form><br />

<div style="width:800px" class="divFormCaption">Lineas</div>
<form name="frmdetalles" id="frmdetalles">
<input type="hidden" id="nrodetalles" value="0" />
<input type="hidden" id="cantdetalles" value="0" />
<input type="hidden" id="seldetalle" />
<table width="800" class="tblBotones">
    <tr>
        <td>
            <input type="button" class="btLista" value="Sel. Cuenta" onclick="abrirListado(document.getElementById('seldetalle').value, 'listado_cuentas_contables.php?destino=listadoCuentaContable');" />
            <input type="button" class="btLista" value="Sel. C.Costo" onclick="abrirListado(document.getElementById('seldetalle').value, 'listado_centro_costos.php?ventana=listadoCentroCosto');" />
            <input type="button" class="btLista" value="Sel. Persona" onclick="abrirListado(document.getElementById('seldetalle').value, 'listado_personas.php?ventana=listadoPersonas&limit=0');" />
        </td>
        <td align="right">
            <input type="button" class="btLista" value="Insertar" onclick="insertarLineaVoucher(this);" />
            <input type="button" class="btLista" value="Quitar" onclick="quitarLineaVoucher(document.getElementById('seldetalle').value);" />
        </td>
    </tr>
</table>
<table align="center"><tr><td align="center"><div style="overflow:scroll; width:800px; height:200px;">
<table width="100%" class="tblLista">
    <tr class="trListaHead">
        <th scope="col" width="35">#</th>
        <th scope="col" width="100">Cuenta</th>
        <th scope="col" width="100">C. Costos</th>
        <th scope="col" width="125">% Dist.</th>
        <th scope="col" width="125">Monto</th>
        <th scope="col">Persona</th>
        <th scope="col" width="150">Nro. Documento</th>
    </tr>
    
    <tbody id="listaDetalles">
    
    </tbody>
</table>
</div></td></tr></table>
</form>

</body>
</html>
