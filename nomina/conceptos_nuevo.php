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
		<td class="titulo">Maestro de Conceptos | Nuevo Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="document.getElementById('frmentrada').submit();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form id="frmentrada" name="frmentrada" action="conceptos.php" method="POST" onsubmit="return verificarConcepto(this, 'GUARDAR');">
<?php echo "<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />"; ?>

<?php
include("fphp_nomina.php");
connect();
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
        <td colspan="3"><input name="codigo" type="text" id="codigo" size="10" disabled="disabled" /></td>
    </tr>
    <tr>
        <td class="tagForm">Tipo:</td>
        <td>
            <select name="tipo" id="tipo" onchange="tipoConcepto(this.value);">
                <option value=""></option>
                <?php getTipoConcepto('', 0); ?>
            </select>*
        </td>
    </tr>
    <tr>
        <td class="tagForm">Descripci&oacute;n:</td>
        <td colspan="3"><input name="descripcion" type="text" id="descripcion" size="60" maxlength="100" />*</td>
    </tr>
    <tr>
        <td class="tagForm">Impresi&oacute;n:</td>
        <td colspan="3"><input name="impresion" type="text" id="impresion" size="60" maxlength="50" />*</td>
    </tr>
    <tr>
        <td class="tagForm">Orden en Boleta:</td>
        <td><input name="orden" type="text" id="orden" size="10" maxlength="2" /></td>
        <td class="tagForm">Abreviatura:</td>
        <td><input name="abreviatura" type="text" id="abreviatura" size="10" maxlength="10" /></td>
    </tr>
    <tr>
        <td class="tagForm">&nbsp;</td>
        <td colspan="3">
        	<input type="checkbox" name="flagautomatica" id="flagautomatica" value="S" /> Asignaci&oacute;n Autom&aacute;tica
            &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
			<input type="checkbox" name="flagbono" id="flagbono" value="S" /> Es una bonificaci&oacute;n
            &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
			<input type="checkbox" name="flagretencion" id="flagretencion" value="S" /> Retenci&oacute;n
		</td>
    </tr>
    <tr>
        <td class="tagForm">Estado:</td>
        <td colspan="3">
            <input name="status" id="activo" type="radio" value="A" checked /> Activo
            <input name="status" id="inactivo" type="radio" value="I" /> Inactivo
        </td>
    </tr>
    <tr>
        <td class="tagForm">&Uacute;ltima Modif.:</td>
        <td colspan="3">
            <input name="ult_usuario" type="text" id="ult_usuario" size="30" readonly />
            <input name="ult_fecha" type="text" id="ult_fecha" size="25" readonly />
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
        	<iframe name="iNomina" id="iNomina" class="frameTab" style="height:300px; width:250px;" src="conceptos_tiponomina.php?accion=NUEVO"></iframe>
		</td>
    	<td>
			<div style="width:252px" class="divFormCaption">Tipos de Proceso</div>
        	<iframe name="iProceso" id="iProceso" class="frameTab" style="height:300px; width:250px;" src="conceptos_procesos.php?accion=NUEVO"></iframe>
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
      	<textarea name="formula" id="formula" style="width:99%; height:150px; font-size:14px; word-spacing:5px; font-weight:bold;"></textarea>
      </td>
        <td width="100" valign="top">
        	<table cellpadding="0" cellspacing="0">
            	<tr>
                	<td><input type="button" value="7" class="btFormula" onclick="escribirFormula('7');" /></td>
                	<td><input type="button" value="8" class="btFormula" onclick="escribirFormula('8');" /></td>
                	<td><input type="button" value="9" class="btFormula" onclick="escribirFormula('9');" /></td>
                	<td><input type="button" value="+" class="btFormula" onclick="escribirFormula('+');" /></td>
                </tr>
            	<tr>
                	<td><input type="button" value="4" class="btFormula" onclick="escribirFormula('4');" /></td>
                	<td><input type="button" value="5" class="btFormula" onclick="escribirFormula('5');" /></td>
                	<td><input type="button" value="6" class="btFormula" onclick="escribirFormula('6');" /></td>
                	<td><input type="button" value="-" class="btFormula" onclick="escribirFormula('-');" /></td>
                </tr>
            	<tr>
                	<td><input type="button" value="1" class="btFormula" onclick="escribirFormula('1');" /></td>
                	<td><input type="button" value="2" class="btFormula" onclick="escribirFormula('2');" /></td>
                	<td><input type="button" value="3" class="btFormula" onclick="escribirFormula('3');" /></td>
                	<td><input type="button" value="/" class="btFormula" onclick="escribirFormula('/');" /></td>
                </tr>
            	<tr>
                	<td><input type="button" value="=" class="btFormula" onclick="escribirFormula('=');" /></td>
                	<td><input type="button" value="0" class="btFormula" onclick="escribirFormula('0');" /></td>
                	<td><input type="button" value="." class="btFormula" onclick="escribirFormula('.');" /></td>
                	<td><input type="button" value="*" class="btFormula" onclick="escribirFormula('*');" /></td>
                </tr>
            	<tr>
                	<td><input type="button" value="(" class="btFormula" onclick="escribirFormula('(');" /></td>
                	<td><input type="button" value=")" class="btFormula" onclick="escribirFormula(')');" /></td>
                	<td><input type="button" value="$" class="btFormula" onclick="escribirFormula('$_');" /></td>
                	<td><input type="button" value=";" class="btFormula" onclick="escribirFormulaRetorno(';');" /></td>
                </tr>
            	<tr>
                	<td colspan="4"><input type="button" value="MONTO   " style="width:100%" align="left" onclick="escribirFormula('$_MONTO = ');" /></td>
                </tr>
            	<tr>
                	<td colspan="4"><input type="button" value="CANTIDAD" style="width:100%" align="left" onclick="escribirFormula('$_CANTIDAD = ');" /></td>
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
        <th scope="col" width="150">Tipo Proceso</th>
        <th scope="col" width="115">Partida Presupuestal</th>
        <th scope="col" width="115">Debe</th>
        <th scope="col" width="25">C.C</th>
        <th scope="col" width="115">Haber</th>
        <th scope="col" width="25">C.C</th>
    </tr>
                
    <tbody id="listaDetalles">
    
    </tbody>
</table>
</div></td></tr></table>
</td></tr></table>

<table class="tblForm" width="750">
	<tr>
    	<td>
        	<input type="checkbox" name="flagobligacion" id="flagobligacion" onclick="document.getElementById('codpersona').value='';
            																		  document.getElementById('nompersona').value='';
            																		  document.getElementById('btpersona').disabled=!this.checked;"  />
            Se genera obligaci&oacute;n para este proveedor 
        </td>
        <td>
        	<input type="text" size="8" id="codpersona" disabled="disabled"  />
        	<input type="text" size="65" id="nompersona" disabled="disabled"  />
            <input type="button" id="btpersona" value="..." onclick="window.open('listado_personas.php?cod=codpersona&nom=nompersona&limit=0&flagproveedor=S', 'listado_personas', 'height=500, width=800, left=200, top=200, resizable=yes');" disabled="disabled" />
        </td>
    </tr>
</table>

</form>
</div>

</body>
</html>
