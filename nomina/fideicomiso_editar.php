<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_nomina.js"></script>
</head>

<body>
<?php
include("fphp.php");
connect();
//	-------------------
$sql = "SELECT 
			paf.*,
			mp.NomCompleto
		FROM
			pr_acumuladofideicomiso paf
			INNER JOIN mastpersonas mp ON (paf.CodPersona = mp.CodPersona)
		WHERE
			paf.CodPersona = '".$registro."'";
$query = mysql_query($sql) or die($sql.mysql_error());
if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
$acumuladodias = $field['AcumuladoInicialDias'] + $field['AcumuladoProvDias'];
$acumuladoprov2 = $field['AcumuladoInicialProv'] + $field['AcumuladoProv'];
$acumuladofide2 = $field['AcumuladoInicialFide'] + $field['AcumuladoFide'];
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Modificar Acumulado de Fideicomiso</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'fideicomiso.php?filtro=<?=$filtro?>&limit=0');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="fideicomiso.php?filtro=<?=$filtro?>&limit=0" method="POST" onsubmit="return verificarFideicomiso(this, 'ACTUALIZAR');">
<div style="width:800px" class="divFormCaption">Datos del Acumulado de Fideicomiso</div>
<table width="800" class="tblForm">
	<tr>
		<td class="tagForm">Empleado:</td>
		<td>
			<input name="codpersona" type="hidden" id="codpersona" value="<?=$field['CodPersona']?>" />
			<input name="nompersona" type="text" id="nompersona" size="75" value="<?=$field['NomCompleto']?>" readonly />
			<input name="bt_examinar" type="button" id="bt_examinar" value="..." onclick="cargarVentana(this.form, 'lista_empleados.php?limit=0&campo=3', 'height=500, width=900, left=200, top=200, resizable=yes');" />*
		</td>
	</tr>
	<tr>
		<td class="tagForm">Acumulado Inicial Dias:</td>
		<td><input name="acumuladoinicialdias" type="text" id="acumuladoinicialdias" size="15" value="<?=number_format($field['AcumuladoInicialDias'], 2, '.', '')?>" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Acumulado Inicial Antiguedad:</td>
		<td><input name="acumuladoinicialprov" type="text" id="acumuladoinicialprov" size="30" value="<?=number_format($field['AcumuladoInicialProv'], 2, '.', '')?>" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Acumulado Inicial Fideicomiso:</td>
		<td><input name="acumuladoinicialfide" type="text" id="acumuladoinicialfide" size="30" value="<?=number_format($field['AcumuladoInicialFide'], 2, '.', '')?>" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Acumulado Dias Adicionales:</td>
		<td><input name="acumuladoinicialdiasadicional" type="text" id="acumuladoinicialdiasadicional" size="15" value="<?=number_format($field['AcumuladoDiasAdicionalInicial'], 2, '.', '')?>" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Periodo Inicial:</td>
		<td><input name="periodoinicial" type="text" id="periodoinicial" size="15" maxlength="7" value="<?=$field['PeriodoInicial']?>" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Acumulado Dias:</td>
		<td><input name="acumuladodias" type="text" id="acumuladodias" size="15" value="<?=number_format($acumuladodias, 2, '.', '')?>" disabled="disabled" /></td>
	</tr>
	<tr>
		<td class="tagForm">Acumulado Actual Antiguedad:</td>
		<td>
			<input name="acumuladoprov" type="text" id="acumuladoprov" size="30" value="<?=number_format($field['AcumuladoProv'], 2, '.', '')?>" disabled="disabled" /> - 
			<input name="acumuladoprov2" type="text" id="acumuladoprov2" size="30" value="<?=number_format($acumuladoprov2, 2, '.', '')?>" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Acumulado Actual Fideicomiso:</td>
		<td>
			<input name="acumuladofide" type="text" id="acumuladofide" size="30" value="<?=number_format($field['AcumuladoFide'], 2, '.', '')?>" disabled="disabled" /> - 
			<input name="acumuladofide2" type="text" id="acumuladofide2" size="30" value="<?=number_format($acumuladofide2, 2, '.', '')?>" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td>
			<input name="ult_usuario" type="text" id="ult_usuario" size="30" readonly /> &nbsp; 
			<input name="ult_fecha" type="text" id="ult_fecha" size="25" readonly />
		</td>
	</tr>
</table>
<center> 
<input type="submit" value="Guardar Registro" />
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onClick="cargarPagina(this.form, 'fideicomiso.php?filtro=<?=$filtro?>&limit=0');" />
</center><br />


<?php
//	FILTRO..............
echo "
<input type='hidden' name='chkorganismo' value='".$_POST['chkorganismo']."' />
<input type='hidden' name='forganismo' value='".$_POST['forganismo']."' />
<input type='hidden' name='chkedoreg' value='".$_POST['chkedoreg']."' />
<input type='hidden' name='fedoreg' value='".$_POST['fedoreg']."' />
<input type='hidden' name='chkdependencia' value='".$_POST['chkdependencia']."' />
<input type='hidden' name='fdependencia' value='".$_POST['fdependencia']."' />
<input type='hidden' name='chksittra' value='".$_POST['chksittra']."' />
<input type='hidden' name='fsittra' value='".$_POST['fsittra']."' />
<input type='hidden' name='chktiponom' value='".$_POST['chktiponom']."' />
<input type='hidden' name='ftiponom' value='".$_POST['ftiponom']."' />
<input type='hidden' name='chkbuscar' value='".$_POST['chkbuscar']."' />
<input type='hidden' name='sltbuscar' value='".$_POST['sltbuscar']."' />
<input type='hidden' name='fbuscar' value='".$_POST['fbuscar']."' />
<input type='hidden' name='chktipotra' value='".$_POST['chktipotra']."' />
<input type='hidden' name='ftipotra' value='".$_POST['ftipotra']."' />
<input type='hidden' name='chkpersona' value='".$_POST['chkpersona']."' />
<input type='hidden' name='sltpersona' value='".$_POST['sltpersona']."' />
<input type='hidden' name='fpersona' value='".$_POST['fpersona']."' />
<input type='hidden' name='chkedad' value='".$_POST['chkedad']."' />
<input type='hidden' name='sltedad' value='".$_POST['sltedad']."' />
<input type='hidden' name='fedad' value='".$_POST['fedad']."' />
<input type='hidden' name='chkfingreso' value='".$_POST['chkfingreso']."' />
<input type='hidden' name='fingresod' value='".$_POST['fingresod']."' />
<input type='hidden' name='fingresoh' value='".$_POST['fingresoh']."' />
<input type='hidden' name='chkndoc' value='".$_POST['chkndoc']."' />
<input type='hidden' name='fndoc' value='".$_POST['fndoc']."' />
<input type='hidden' name='chkordenar' value='".$_POST['chkordenar']."' />
<input type='hidden' name='fordenar' value='".$_POST['fordenar']."' />
<input type='hidden' name='ordenar' value='".$_POST['ordenar']."' />
<input type='hidden' name='filtro' id='filtro' value='".$filtro."' />";
?>


</form>

<div style="width:800px" class="divMsj">Campos Obligatorios *</div>
</body>
</html>
