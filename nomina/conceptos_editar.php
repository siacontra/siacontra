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
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Maestro de Conceptos | Actualizaci&oacute;n</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="document.getElementById('frmentrada').submit();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form id="frmentrada" name="frmentrada" action="conceptos.php" method="POST" onsubmit="return verificarConcepto(this, 'ACTUALIZAR');">
<?php echo "<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />"; ?>

<?php
include("fphp_nomina.php");
connect();
//	-----------------------
$sql = "SELECT
			c.*,
			mp.NomCompleto As NomPersona
		FROM
			pr_concepto c
			LEFT JOIN mastpersonas mp ON (c.CodPersona = mp.CodPersona)
		WHERE c.CodConcepto = '".$registro."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0) $field=mysql_fetch_array($query);
if ($field['Estado']=="A") $activo="checked"; else $inactivo="checked";
if ($field['FlagLiquidacion']=="S") $flag="checked";

?>

<table width="750" align="center">
  <tr>
		<td>
			<div id="header">
			<ul>
			<!-- CSS Tabs -->
			<li><a onclick="document.getElementById('tab1').style.display='block'; document.getElementById('tab2').style.display='none'; document.getElementById('tab3').style.display='none';" href="#">Informaci&oacute;n General</a></li>
			<li><a onclick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='block'; document.getElementById('tab3').style.display='none';" href="#" disabled>Formula</a></li>
			<li><a onclick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='none'; document.getElementById('tab3').style.display='block';" href="#" disabled>Informaci&oacute;n Contable</a></li>
			</ul>
			</div>
		</td>
	</tr>
</table>

<div name="tab1" id="tab1" style="display:block;">
<div style="width:750px" class="divFormCaption">Datos del Concepto</div>
<table width="750" class="tblForm">
    <tr>
        <td class="tagForm">Concepto:</td>
        <td colspan="3"><input name="codigo" type="text" id="codigo" size="10" readonly="readonly" value="<?=$field['CodConcepto']?>" /></td>
    </tr>
    <tr>
        <td class="tagForm">Tipo:</td>
        <td colspan="3">
            <select name="tipo" id="tipo" onchange="tipoConcepto(this.value);">
                <option value=""></option>
                <?php getTipoConcepto($field['Tipo'], 0); ?>
            </select>*
        </td>
    </tr>
    <tr>
        <td class="tagForm">Descripci&oacute;n:</td>
        <td colspan="3"><input name="descripcion" type="text" id="descripcion" size="60" maxlength="100" value="<?=($field['Descripcion'])?>" />*</td>
    </tr>
    <tr>
        <td class="tagForm">Impresi&oacute;n:</td>
        <td colspan="3"><input name="impresion" type="text" id="impresion" size="60" maxlength="50" value="<?=($field['TextoImpresion'])?>" />*</td>
    </tr>
    <tr>
        <td class="tagForm">Orden en Boleta:</td>
        <td><input name="orden" type="text" id="orden" size="10" maxlength="2" value="<?=$field['PlanillaOrden']?>" /></td>
        <td class="tagForm">Abreviatura:</td>
        <td><input name="abreviatura" type="text" id="abreviatura" size="10" maxlength="10" value="<?=($field['Abreviatura'])?>" /></td>
    </tr>
    <tr>
        <td class="tagForm">&nbsp;</td>
        <td colspan="3">
        	<?
            if ($field['Tipo'] == "T") $disabled = "disabled";
			?>
			<? if ($field['FlagAutomatico'] == "S") $flag = "checked"; else $flag = ""; ?>
			<input type="checkbox" name="flagautomatica" id="flagautomatica" value="S" <?=$flag?> <?=$disabled?> /> Asignaci&oacute;n Autom&aacute;tica
            &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
            
			<? if ($field['FlagBono'] == "S") $flag = "checked"; else $flag = ""; ?>
			<input type="checkbox" name="flagbono" id="flagbono" value="S" <?=$flag?> <?=$disabled?> /> Es una bonificaci&oacute;n
            &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
            
			<? if ($field['FlagRetencion'] == "S") $flag = "checked"; else $flag = ""; ?>
			<input type="checkbox" name="flagretencion" id="flagretencion" value="S" <?=$flag?> <?=$disabled?> /> Retenci&oacute;n
		</td>
    </tr>
    <tr>
        <td class="tagForm">Estado:</td>
        <td colspan="3">
            <input name="status" id="activo" type="radio" value="A" <?=$activo?> /> Activo
            <input name="status" id="inactivo" type="radio" value="I" <?=$inactivo?> /> Inactivo
        </td>
    </tr>
    <tr>
        <td class="tagForm">&Uacute;ltima Modif.:</td>
        <td colspan="3">
            <input name="ult_usuario" type="text" id="ult_usuario" size="30" value="<?=$field['UltimoUsuario']?>" readonly />
            <input name="ult_fecha" type="text" id="ult_fecha" size="25" value="<?=$field['UltimaFecha']?>" readonly />
        </td>
    </tr>
</table>
<center> 
<input type="submit" value="Guardar Registro" />
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onclick="cargarPagina(this.form, 'conceptos.php');" />
</center><br />
<div style="width:700px" class="divMsj">Campos Obligatorios *</div>

<table align="center">
	<tr>
    	<td>
        	<div style="width:252px" class="divFormCaption">Tipos de N&oacute;mina</div>
        	<iframe name="iNomina" id="iNomina" class="frameTab" style="height:300px; width:250px;" src="conceptos_tiponomina.php?accion=EDITAR&concepto=<?=$registro?>"></iframe>
		</td>
    	<td>
			<div style="width:252px" class="divFormCaption">Tipos de Proceso</div>
        	<iframe name="iProceso" id="iProceso" class="frameTab" style="height:300px; width:250px;" src="conceptos_procesos.php?accion=EDITAR&concepto=<?=$registro?>"></iframe>
		</td>
    	<td></td>
    </tr>
</table>
</div>
</form>

<div name="tab2" id="tab2" style="display:none;">
<div style="width:750px" class="divFormCaption">Campo de Formula</div>
<table width="750" class="tblForm">
    <tr>
    	<td align="center">
      	<textarea name="formula" id="formula" style="width:99%; height:150px; font-size:10px; letter-spacing:1px; word-spacing:3px; font-weight:bold;" <?=$disabled?>><?=($field['Formula'])?></textarea>
      </td>
        <td width="100" valign="top">
        	<table cellpadding="0" cellspacing="0">
            	<tr>
                	<td><input type="button" value="7" class="btFormula" onclick="escribirFormula('7');" <?=$disabled?> /></td>
                	<td><input type="button" value="8" class="btFormula" onclick="escribirFormula('8');" <?=$disabled?> /></td>
                	<td><input type="button" value="9" class="btFormula" onclick="escribirFormula('9');" <?=$disabled?> /></td>
                	<td><input type="button" value="+" class="btFormula" onclick="escribirFormula('+');" <?=$disabled?> /></td>
                </tr>
            	<tr>
                	<td><input type="button" value="4" class="btFormula" onclick="escribirFormula('4');" <?=$disabled?> /></td>
                	<td><input type="button" value="5" class="btFormula" onclick="escribirFormula('5');" <?=$disabled?> /></td>
                	<td><input type="button" value="6" class="btFormula" onclick="escribirFormula('6');" <?=$disabled?> /></td>
                	<td><input type="button" value="-" class="btFormula" onclick="escribirFormula('-');" <?=$disabled?> /></td>
                </tr>
            	<tr>
                	<td><input type="button" value="1" class="btFormula" onclick="escribirFormula('1');" <?=$disabled?> /></td>
                	<td><input type="button" value="2" class="btFormula" onclick="escribirFormula('2');" <?=$disabled?> /></td>
                	<td><input type="button" value="3" class="btFormula" onclick="escribirFormula('3');" <?=$disabled?> /></td>
                	<td><input type="button" value="/" class="btFormula" onclick="escribirFormula('/');" <?=$disabled?> /></td>
                </tr>
            	<tr>
                	<td><input type="button" value="=" class="btFormula" onclick="escribirFormula('=');" <?=$disabled?> /></td>
                	<td><input type="button" value="0" class="btFormula" onclick="escribirFormula('0');" <?=$disabled?> /></td>
                	<td><input type="button" value="." class="btFormula" onclick="escribirFormula('.');" <?=$disabled?> /></td>
                	<td><input type="button" value="*" class="btFormula" onclick="escribirFormula('*');" <?=$disabled?> /></td>
                </tr>
            	<tr>
                	<td><input type="button" value="(" class="btFormula" onclick="escribirFormula('(');" <?=$disabled?> /></td>
                	<td><input type="button" value=")" class="btFormula" onclick="escribirFormula(')');" <?=$disabled?> /></td>
                	<td><input type="button" value="$" class="btFormula" onclick="escribirFormula('$_');" <?=$disabled?> /></td>
                	<td><input type="button" value=";" class="btFormula" onclick="escribirFormulaRetorno(';');" <?=$disabled?> /></td>
                </tr>
            	<tr>
                	<td colspan="4"><input type="button" value="MONTO   " style="width:100%" align="left" onclick="escribirFormula('$_MONTO = ');" <?=$disabled?> /></td>
                </tr>
            	<tr>
                	<td colspan="4"><input type="button" value="CANTIDAD" style="width:100%" align="left" onclick="escribirFormula('$_CANTIDAD = ');" <?=$disabled?> /></td>
                </tr>
            </table>
        </td>
	</tr>
</table>

<table align="center">
	<tr>
    	<td valign="top">
        	<div style="width:242px" class="divFormCaption">Variables Disponibles</div>
            <div class="divTab" style="width:240px;"><?=mostrarVariablesConceptos();?></div><br />
            
        	<div style="width:242px" class="divFormCaption">Par&aacute;metros Disponibles</div>
            <div class="divTab" style="height:300px; width:240px;"><?=mostrarParametrosConceptos();?></div>
		</td>
    	<td valign="top">
        	<div style="width:252px" class="divFormCaption">Funciones</div>
            <div class="divTab" style="width:250px;"><?=mostrarFuncionesConceptos();?></div>
		</td>
    	<td valign="top">
        	<div style="width:252px" class="divFormCaption">Conceptos Disponibles</div>
            <div class="divTab" style="width:250px;"><?=mostrarConceptosDisponibles($registro);?></div>
		</td>
    </tr>
</table>
</div>

<div name="tab3" id="tab3" style="display:none;">
<div style="width:750px" class="divFormCaption">Informaci&oacute;n Contable</div>
<form name="frmdetalles" id="frmdetalles">
<input type="hidden" id="selconcepto" />

<table width="750" class="tblBotones">
 <tr>
 	<td>
    	<input type="button" value="Sel. Partida" onclick="listaPartidaConceptoPerfil(this.form);" disabled="disabled" />
	</td>
 </tr>
</table>

<table align="center" cellpadding="0" cellspacing="0"><tr><td valign="top" style="height:305px; width:750px;">
<table align="center" width="100%"><tr><td align="center"><div style="overflow:scroll; height:300px; width:745px;">
<table width="100%" class="tblLista">
    <tr class="trListaHead">
        <th scope="col">Perfil</th>
        <th scope="col" width="200">Tipo Proceso</th>
        <th scope="col" width="100">Partida Presupuestal</th>
        <th scope="col" width="75">Debe</th>
        <th scope="col" width="25">C.C</th>
        <th scope="col" width="75">Haber</th>
        <th scope="col" width="25">C.C</th>
    </tr>
                
    <tbody id="listaDetalles">
    <?
	$sql = "SELECT
				cpd.*,
				cp.Descripcion AS NomPerfilConcepto,
				tp.Descripcion AS NomTipoProceso
			FROM
				pr_conceptoperfildetalle cpd
				INNER JOIN pr_conceptoperfil cp ON (cpd.CodPerfilConcepto = cp.CodPerfilConcepto)
				INNER JOIN pr_tipoproceso tp ON (cpd.CodTipoProceso = tp.CodTipoProceso)
			WHERE
				cpd.CodConcepto = '".$registro."'";
	$query_concepto = mysql_query($sql) or die ($sql.mysql_error());
	while ($field_concepto = mysql_fetch_array($query_concepto)) { $i++;
		$id_concepto = $field_procesos['CodTipoProceso']."_".$field_concepto['CodConcepto'];
		
		?>
        <tr class="trListaBody" id="<?=$id_concepto?>">
            <td>
				<?=($field_concepto['NomPerfilConcepto'])?>
                <input type="hidden" name="txtconcepto" value="<?=$id_concepto?>" style="display:none;" />
            </td>
            <td><?=($field_concepto['NomTipoProceso'])?></td>
            <td><input type="text" name="partida" id="partida_<?=$id_concepto?>" value="<?=$field_concepto['cod_partida']?>" style="width:99%; text-align:center;" disabled="disabled" /></td>
            <td><input type="text" name="debe" id="debe_<?=$id_concepto?>" value="<?=$field_concepto['CuentaDebe']?>" style="width:99%; text-align:center;" disabled="disabled" /></td>
            <td><input type="checkbox" name="debecc" id="debecc_<?=$id_concepto?>" /></td>
            <td><input type="text" name="haber" id="haber_<?=$id_concepto?>" value="<?=$field_concepto['CuentaHaber']?>" style="width:99%; text-align:center;" disabled="disabled" /></td>
            <td><input type="checkbox" name="habercc" id="habercc_<?=$id_concepto?>" /></td>
        </tr>
        <?
	}
	?>
    </tbody>
</table>
</div></td></tr></table>
</td></tr></table>

<table class="tblForm" width="750">
	<tr>
    	<td>
        	<?php if ($field['FlagObligacion'] == "S") $flagobligacion = "checked"; else $btpersona = "disabled"; ?>
        	<input type="checkbox" id="flagobligacion" <?=$flagobligacion?> onclick="document.getElementById('codpersona').value='';
                                                                                      document.getElementById('nompersona').value='';
                                                                                      document.getElementById('btpersona').disabled=!this.checked;" <?=$disabled?> />
            Se genera obligaci&oacute;n para este proveedor 
        </td>
        <td>
        	<input type="text" size="8" id="codpersona" value="<?=$field['CodPersona']?>" disabled="disabled"  />
        	<input type="text" size="65" id="nompersona" value="<?=($field['NomPersona'])?>"  disabled="disabled"  />
            <input type="button" id="btpersona" value="..." onclick="window.open('listado_personas.php?cod=codpersona&nom=nompersona&limit=0&flagproveedor=S', 'listado_personas', 'height=500, width=800, left=200, top=200, resizable=yes');" <?=$btpersona?> <?=$disabled?> />
        </td>
    </tr>
</table>

</form>
</div>

</body>
</html>
