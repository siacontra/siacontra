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
//	------------------------------------------------------
$sql = "SELECT mv.*, d.CodOrganismo
		FROM
			ac_modelovoucher mv
			INNER JOIN mastdependencias d ON (mv.CodDependencia = d.CodDependencia)
		WHERE
			mv.CodModeloVoucher = '".$registro."'";
$query = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Modelo de Voucher | Ver Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<div style="width:800px" class="divFormCaption">Datos del Registro</div>
<table width="800" class="tblForm">
	<tr>
		<td class="tagForm" width="125">Modelo:</td>
		<td><input type="text" id="codigo" maxlength="4" style="width:75px; font-weight:bold;" value="<?=$field['CodModeloVoucher']?>" disabled="disabled" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Descripci&oacute;n:</td>
		<td><input type="text" id="descripcion" maxlength="75" class="selectBig" value="<?=htmlentities($field['Descripcion'])?>" disabled="disabled" />*</td>
	</tr>
    <tr>
        <td class="tagForm">Distribuci&oacute;n:</td>
        <td>
            <select name="distribucion" id="distribucion" class="selectBig" disabled="disabled">
                <?=loadSelectValores("DISTRIBUCION-VOUCHER", $field['Distribucion'], 0)?>
            </select>*
        </td>
    </tr>
    <tr>
        <td class="tagForm">Libro Contable:</td>
        <td>
            <select name="libro" id="libro" class="selectBig" disabled="disabled">
                <?=loadSelect("ac_librocontable", "CodLibroCont", "Descripcion", $field['CodLibroCont'], 0)?>
            </select>*
        </td>
    </tr>
    <tr>
        <td class="tagForm">Organismo:</td>
        <td>
            <select name="organismo" id="organismo" class="selectBig" disabled="disabled">
            <?=getOrganismos($field['CodOrganismo'], 0); ?>
        </select>*
        </td>
    </tr>
    <tr>
        <td class="tagForm">Dependencia:</td>
        <td>
            <select name="dependencia" id="dependencia" class="selectBig" disabled="disabled">
            	<?=getDependencias($field['CodDependencia'], $field['CodOrganismo'], 0)?>
            </select>*
        </td>
    </tr>
	<tr>
		<td class="tagForm">Estado:</td>
		<td>
        	<? if ($field['Estado'] == "A") $flagactivo = "checked"; else $flaginactivo = "checked"; ?>
			<input id="activo" name="estado" type="radio" value="A" <?=$flagactivo?> disabled="disabled" /> Activo &nbsp;&nbsp;
			<input id="inactivo" name="estado" type="radio" value="I" <?=$flaginactivo?> disabled="disabled" /> Inactivo
		</td>
	</tr>
	<tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td>
			<input name="ult_usuario" type="text" id="ult_usuario" size="30" value="<?=$field['UltimoUsuario']?>" disabled="disabled" />
			<input name="ult_fecha" type="text" id="ult_fecha" size="25" value="<?=$field['UltimaFecha']?>" disabled="disabled" />
		</td>
	</tr>
</table>
<center> 
<input type="submit" value="Guardar Registro" />
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onclick="document.getElementById('frmentrada').submit();" />
</center>
</form><br />

<div style="width:800px" class="divFormCaption">Lineas</div>
<form name="frmdetalles" id="frmdetalles">
<input type="hidden" id="seldetalle" />
<table width="800" class="tblBotones">
    <tr>
        <td>
            <input type="button" class="btLista" value="Sel. Cuenta" disabled="disabled" />
            <input type="button" class="btLista" value="Sel. C.Costo" disabled="disabled" />
            <input type="button" class="btLista" value="Sel. Persona" disabled="disabled" />
        </td>
        <td align="right">
            <input type="button" class="btLista" value="Insertar" disabled="disabled" />
            <input type="button" class="btLista" value="Quitar" disabled="disabled" />
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
    <?
	$sql = "SELECT * FROM ac_modelovoucherdetalle WHERE CodModeloVoucher = '".$registro."'";
	$query_det = mysql_query($sql) or die ($sql.mysql_error());
	while ($field_det = mysql_fetch_array($query_det)) {	$nrodetalles++;
		?>
        <tr class="trListaBody" onclick="mClk(this, 'seldetalle');" id="<?=$nrodetalles?>">
            <td align="center">
                <?=$nrodetalles?>
                <input type="hidden" name="nrodetalles" value="<?=$nrodetalles?>">
            </td>
            <td align="center"><input type="text" name="cuenta" id="cuenta<?=$nrodetalles?>" class="cell2" style="text-align:center;" value="<?=$field_det['CodCuenta']?>" disabled="disabled"></td>
            <td align="center"><input type="text" name="ccosto" id="ccosto<?=$nrodetalles?>" class="cell2" style="text-align:center;" value="<?=$field_det['CodCentroCosto']?>"  disabled="disabled"></td>
            <td align="center"><input type="text" name="porcentaje" id="porcentaje<?=$nrodetalles?>" class="cell" style="text-align:right;" disabled="disabled" value="<?=number_format($field_det['Porcentaje'],2 ,',' ,'.')?>"></td>
            <td align="center"><input type="text" name="monto" id="monto<?=$nrodetalles?>" class="cell" style="text-align:right;" disabled="disabled" value="<?=number_format($field_det['Monto'],2 ,',' ,'.')?>"></td>
            <td align="center"><input type="text" name="persona" id="persona<?=$nrodetalles?>" class="cell2" style="text-align:center;" value="<?=$field_det['CodPersona']?>" disabled="disabled"></td>
            <td align="center"><input type="text" name="nrodocumento" id="nrodocumento<?=$nrodetalles?>" maxlength="10" class="cell" disabled="disabled" value="<?=$field_det['NroDocumento']?>"></td>
		</tr>
        <?
	}
	?>
    </tbody>
</table>
</div></td></tr></table>
<input type="hidden" id="nrodetalles" value="<?=$nrodetalles?>" />
<input type="hidden" id="cantdetalles" value="<?=$nrodetalles?>" />
</form>

</body>
</html>
