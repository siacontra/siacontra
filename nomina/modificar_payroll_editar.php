<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");

include("fphp_nomina.php");
connect();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_nomina.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Modificar Conceptos del Empleado</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?
$sql = "SELECT 
			mp.Ndocumento,
			mp.NomCompleto,
			me.CodEmpleado,
			me.Fingreso,
			me.CodOrganismo,
			me.CodTipoNom,
			rp.DescripCargo
		FROM
			mastpersonas mp
			INNER JOIN mastempleado me ON (mp.CodPersona = me.CodPersona)
			INNER JOIN rh_puestos rp ON (me.CodCargo = rp.CodCargo)
		WHERE mp.CodPersona = '".$persona."'";
$query_datos = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query_datos) != 0) $field_datos = mysql_fetch_array($query_datos);
?>

<table align="center" width="90%" class="tblLista">
	<tr class="trListaHead">
		<td>C&oacute;digo:</td>
		<td>Empleado:</td>
		<td>C&eacute;dula:</td>
		<td>F. Ingreso:</td>
	</tr>
	<tr>
		<td><?=$field_datos['CodEmpleado']?></td>
		<td><?=($field_datos['NomCompleto'])?></td>
		<td><?=$field_datos['Ndocumento']?></td>
		<td><?=$field_datos['Fingreso']?></td>
	</tr>
	<tr>
		<td class="trListaHead">Cargo:</td><td colspan="3"><?=($field_datos['DescripCargo'])?></td>
	</tr>
</table><br />

<form name="frmentrada" id="frmentrada" method="post" onsubmit="return insertarConceptoPayRoll(this.form);">
<input type="hidden" name="periodo" id="periodo" value="<?=$periodo?>" />
<input type="hidden" name="persona" id="persona" value="<?=$persona?>" />
<input type="hidden" name="organismo" id="organismo" value="<?=$organismo?>" />
<input type="hidden" name="proceso" id="proceso" value="<?=$proceso?>" />
<input type="hidden" name="nomina" id="nomina" value="<?=$nomina?>" />
<div style="width:90%" class="divFormCaption">Datos del Concepto</div>
<table width="90%" class="tblForm">
    <tr>
        <td class="tagForm">Concepto:</td>
        <td>
            <input name="codconcepto" type="hidden" id="codconcepto" />
            <input name="nomconcepto" type="text" id="nomconcepto" size="56" readonly />
            <input name="btConcepto" type="button" id="btConcepto" value="..." onclick="window.open('lista_conceptos.php?accion=MODIFICACION&limit=0&codpersona=<?=$persona?>&codtiponom=<?=$field_datos['CodTipoNom']?>', 'wLista', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=500, width=800, left=200, top=200, resizable=yes');" />*
        </td>
    	<td class="tagForm">Desde:</td>
        <td>
        	<select name="pdesde" id="pdesde">
                <?=getPeriodos($periodo, $field_datos['CodTipoNom'], $field_datos['CodOrganismo'], 4)?>
            </select>*
        </td>
    	<td class="tagForm">Hasta:</td>
        <td>
        	<select name="phasta" id="phasta" disabled="disabled">
            	<option value=""></option>
            </select>
        </td>
    </tr>
    <tr>
    	<td class="tagForm">Monto:</td>
        <td><input type="text" name="monto" id="monto" size="20" maxlength="14" /></td>
    	<td class="tagForm">Cantidad:</td>
        <td><input type="text" name="cantidad" id="cantidad" size="20" maxlength="14" /></td>
        <td class="tagForm">Estado:</td>
        <td>
        	<select name="status" id="status">
            	<option value=""></option>
                <?=getStatus("", 0)?>
            </select>*
        </td>
    </tr>
</table>
<center><input type="submit" value="Insertar Concepto" /></center>
</form><br />

<table align="center" width="90%" class="tblLista">
	<tr>
		<td align="center" width="33%" class="trListaHead">A S I G N A C I O N E S</td>
		<td align="center" width="33%" class="trListaHead">D E D U C C I O N E S</td>
		<td align="center" class="trListaHead">P A T R O N A L E S</td>
	</tr>
	<tr>
		<td valign="top">
			<form name="frmasignaciones" id="frmasignaciones">
			<table align="center" width="100%">
			<?
			$sql = "SELECT 
						tnec.*,
						c.TextoImpresion AS NomConcepto
					FROM 
						pr_tiponominaempleadoconcepto tnec
						INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto AND c.Tipo = 'I')
					WHERE
						tnec.CodTipoNom = '".$nomina."' AND
						tnec.Periodo = '".$periodo."' AND
						tnec.CodPersona = '".$persona."' AND
						tnec.CodOrganismo = '".$organismo."' AND
						tnec.CodTipoProceso = '".$proceso."'
					ORDER BY c.Tipo, c.PlanillaOrden";
			$query_asignaciones = mysql_query($sql) or die ($sql.mysql_error());
			while($field_asignaciones = mysql_fetch_array($query_asignaciones)) {
				$total_asignaciones += $field_asignaciones['Monto'];
				?>
				<tr class="trListaBody">
					<td width="75%"><?=($field_asignaciones['NomConcepto'])?></td>
					<td><input type="text" id="<?=$field_asignaciones['CodConcepto']?>" dir="rtl" value="<?=number_format($field_asignaciones['Monto'], 2, ',', '.')?>" style="width:100%;" onchange="calcularTotalesConceptos();" /></td>
				</tr>
				<?
			}
			?>
			</table>
			</form>
		</td>
		
		<td valign="top">
			<form name="frmdeducciones" id="frmdeducciones">
			<table align="center" width="100%">
			<?
			$sql = "SELECT 
						tnec.*,
						c.TextoImpresion AS NomConcepto
					FROM 
						pr_tiponominaempleadoconcepto tnec
						INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto AND c.Tipo = 'D')
					WHERE
						tnec.CodTipoNom = '".$nomina."' AND
						tnec.Periodo = '".$periodo."' AND
						tnec.CodPersona = '".$persona."' AND
						tnec.CodOrganismo = '".$organismo."' AND
						tnec.CodTipoProceso = '".$proceso."'
					ORDER BY c.Tipo, c.PlanillaOrden";
			$query_deducciones = mysql_query($sql) or die ($sql.mysql_error());
			while($field_deducciones = mysql_fetch_array($query_deducciones)) {
				$total_deducciones += $field_deducciones['Monto'];
				?>
				<tr class="trListaBody">
					<td width="75%"><?=($field_deducciones['NomConcepto'])?></td>
					<td><input type="text" id="<?=$field_deducciones['CodConcepto']?>" dir="rtl" value="<?=number_format($field_deducciones['Monto'], 2, ',', '.')?>" style="width:100%;" onchange="calcularTotalesConceptos();" /></td>
				</tr>
				<?
			}
			?>
			</table>
			</form>
		</td>
		
		<td valign="top">
			<form name="frmpatronales" id="frmpatronales">
			<table align="center" width="100%">
			<?
			$sql = "SELECT 
						tnec.*,
						c.TextoImpresion AS NomConcepto
					FROM 
						pr_tiponominaempleadoconcepto tnec
						INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto AND c.Tipo = 'A')
					WHERE
						tnec.CodTipoNom = '".$nomina."' AND
						tnec.Periodo = '".$periodo."' AND
						tnec.CodPersona = '".$persona."' AND
						tnec.CodOrganismo = '".$organismo."' AND
						tnec.CodTipoProceso = '".$proceso."'
					ORDER BY c.Tipo, c.PlanillaOrden";
			$query_patronales = mysql_query($sql) or die ($sql.mysql_error());
			while($field_patronales = mysql_fetch_array($query_patronales)) {
				$total_patronales += $field_patronales['Monto'];
				?>
				<tr class="trListaBody">
					<td width="75%"><?=($field_patronales['NomConcepto'])?></td>
					<td><input type="text" id="<?=$field_patronales['CodConcepto']?>" dir="rtl" value="<?=number_format($field_patronales['Monto'], 2, ',', '.')?>" style="width:100%;" onchange="calcularTotalesConceptos();" /></td>
				</tr>
				<?
			}
			?>
			</table>
			</form>
		</td>
	</tr>
</table>

<table align="center" width="90%" class="tblLista">
	<tr>
		<td valign="top" width="33%">
			<table align="center" width="100%">
				<tr class="trListaBody">
					<td width="75%"><b>Total Asignaciones:</b></td>
					<td><input type="text" id="asignaciones" dir="rtl" value="<?=number_format($total_asignaciones, 2, ',', '.')?>" style="width:100%;" disabled="disabled" /></td>
				</tr>
			</table>
		</td>
		
		<td valign="top" width="33%">
			<table align="center" width="100%">
				<tr class="trListaBody">
					<td width="75%"><b>Total Deducciones:</b></td>
					<td><input type="text" id="deducciones" dir="rtl" value="<?=number_format($total_deducciones, 2, ',', '.')?>" style="width:100%;" disabled="disabled" /></td>
				</tr>
				<tr class="trListaBody">
					<td width="75%"><b>Total Neto:</b></td>
					<td><input type="text" id="neto" dir="rtl" value="<?=number_format(($total_asignaciones-$total_deducciones), 2, ',', '.')?>" style="width:100%;" disabled="disabled" /></td>
				</tr>
			</table>
		</td>
		
		<td valign="top">
			<table align="center" width="100%">
				<tr class="trListaBody">
					<td width="75%"><b>Total Patronales:</b></td>
					<td><input type="text" id="patronales" dir="rtl" value="<?=number_format($total_patronales, 2, ',', '.')?>" style="width:100%;" disabled="disabled" /></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
	
<center><input type="button" value="Guardar Valores" onclick="modificarPayRoll();" /></center>
</body>
</html>