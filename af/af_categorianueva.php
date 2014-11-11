<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//--------
include ("fphp.php");
connect();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" language="javascript" src="af_fscript.js"></script>
</head>
<body>
<table width="100%" cellspacing="0" cellpadding="0">
 <tr>
  <td class="titulo">Maestro de Categor&iacute;a | Nuevo Registro</td>
 <td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'),'<?=$regresar?>.php?limit=0')">[Cerrar]</a></td>
 </tr>
</table>
<hr width="100%" color="#333333" />
<? 
 echo"<input type='hidden' name='regresar' id='regresar' value='".$regresar."' />";
 
 $sql="SELECT * FROM af_categoriadeprec WHERE CodCategoria='".$_POST['registro']."'";
 $qry=mysql_query($sql) or die ($sql.mysql_error());
 $field=mysql_fetch_array($qry);
?>

<form name="frmentrada" id="frmentrada" action="af_categoriadepreciacion.php" method="POST" onsubmit="return  guardarNuevaCategoria(this);">
<? 
   echo "<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."'/>"; 
?>

<div style="width:820px" class="divFormCaption">Datos</div>
<table width="820px" class="tblForm">
<input type="hidden" id="selector" name="selector" value=""/>
<tr>
<td align="center">

<table id="t_1" width="810" class="tblForm">
<tr>
 <td width="141" class="tagForm"><b>C&oacute;digo Categor&iacute;a:</b></td>
 <td width="169"><input type="text" name="codcategoria"  id="codcategoria" size="8" maxlength="10" style="text-align:right"/>*</td>
 <td width="183" class="tagForm"><b><u>Cuentas Contables Activo</u></b></td>
 <td width="73"></td>
 <td width="148" class="tagForm"><b><u>Cuentas de Gastos</u></b></td>
</tr>
<tr>
 <td class="tagForm">Descripci&oacute;n Local:</td>
 <td><input type="text" name="descp_local"  id="descp_local" size="33" maxlength="250"/>*</td>
 <td class="tagForm">Valor Hist&oacute;rico:</td>
 <td><input type="text" name="v_historico"  id="v_historico" size="8" maxlength="20" onclick="valorDestino(this.form,this.id)"/>*</td>
 <td class="tagForm">Depreciaci&oacute;n:</td>
 <td width="68"><input type="text" name="cg_depreciacion"  id="cg_depreciacion" size="8" maxlength="20" onclick="valorDestino(this.form,this.id)"/>*</td>
</tr>
<tr>
 <td class="tagForm">Tipo de Deprec.:</td>
 <td><select name="t_depreciacion" id="t_depreciacion">
     <?
      //// CONSULTA PARA CARGAR EL SELECT  DE TIPO DEPRECIACION
         $s_tdepre = "select * from mastmiscelaneosdet where CodMaestro = 'TIPODEPREC'";
         $q_tdepre = mysql_query($s_tdepre) or die ($s_tdepre.mysql_error());
         $r_tdepre = mysql_num_rows($q_tdepre);
         if($r_tdepre!=0){
            for($i=0;$i<$r_tdepre;$i++){
              $f_tdepre = mysql_fetch_array($q_tdepre);
              echo"<option value='".$f_tdepre['CodDetalle']."'>".$f_tdepre['Descripcion']."</option>";
            }
          }
     ?>
     </select>*</td>
  <td class="tagForm">Adiciones:</td>
  <td><input type="text" name="cc_adiciones"  id="cc_adiciones" size="8" maxlength="20" onclick="valorDestino(this.form,this.id)"/>*</td>
 <td class="tagForm">Ajuste x Inflaci&oacute;n:</td>
 <td><input type="text" name="cg_ajinflacion"  id="cg_ajinflacion" size="8" maxlength="20" onclick="valorDestino(this.form,this.id)"/>*</td>
<tr>
 <td class="tagForm">Grupo Categor&iacute;a:</td>
 <td><select name="g_categoria" id="g_categoria">
       <? 
	    $s_categoria = "select * from mastmiscelaneosdet where CodMaestro = 'GRUPOCATEG'";
		$q_categoria = mysql_query($s_categoria) or die ($s_categoria.mysql_error());
		$r_categoria = mysql_num_rows($q_categoria);
		   while($f_categoria=mysql_fetch_array($q_categoria)){
			   echo"<option value='".$f_categoria['CodDetalle']."'>".$f_categoria['Descripcion']."</option>";
		   }
	   
	   ?>
     </select></td>
  <td class="tagForm">Ajustes x Inflaci&oacute;n:</td>
  <td><input type="text" name="cc_ajinflacion"  id="cc_ajinflacion" size="8" maxlength="20" onclick="valorDestino(this.form,this.id)"/>*</td>
 <td class="tagForm"><b><u>Otras Cuentas Contables</u></b></td>
</tr>
 <td class="tagForm">Categor&iacute;a Inventariable:</td>
 <td><input type="checkbox" id="cat_invent" name="cat_invent" value="N" onclick="asignarValorCatInvent(this.form)"/></td>
 <td class="tagForm"><b><u>Cuentas Contables Depreciaci&oacute;n</u></b></td>
 <td></td>
 <td class="tagForm">R.E.I.:</td>
 <td><input type="text" name="occ_rei"  id="occ_rei" size="8" maxlength="20" onclick="valorDestino(this.form,this.id)"/>*</td>
<tr>
 <td></td>
</tr>
<tr>
 <td class="tagForm"></td>
 <td></td>
 <td class="tagForm">Para Depreciaci&oacute;n:</td>
 <td><input type="text" name="cd_pdepreciacion"  id="cd_pdepreciacion" size="8" maxlength="20" onclick="valorDestino(this.form,this.id)"/>*</td>
 <td class="tagForm">Valor Neto:</td>
 <td><input type="text" name="occ_valorneto"  id="occ_valorneto" size="8" maxlength="20" onclick="valorDestino(this.form,this.id)"/>*</td>
</tr>
<tr>
 <td></td>
 <td></td>
 <td class="tagForm">Adiciones:</td>
 <td><input type="text" name="cd_adiciones"  id="cd_adiciones" size="8" maxlength="20" onclick="valorDestino(this.form,this.id)"/>*</td>
 <td class="tagForm">Cta. Resultado:</td>
 <td><input type="text" name="occ_ctaresultado"  id="occ_ctaresultado" size="8" maxlength="20" onclick="valorDestino(this.form,this.id)"/>*</td>
</tr>
<tr>
 <td class="tagForm">Estado:</td>
 <td><input type="hidden" id="radioEstado" name="radioEstado" value="A"/> 
     <input type="radio" name="radio1" checked="checked" onclick="estadosPosee(this.form);"/>Activo<input type="radio" name="radio2" onclick="estadosPosee(this.form);"/>Inactivo</td>
 <td class="tagForm">Ajustes x Inflaci&oacute;n:</td>
 <td><input type="text" name="cd_ajinflacion"  id="cd_ajinflacion" size="8" maxlength="20" onclick="valorDestino(this.form,this.id)"/>*</td>
</tr>
</table></td>
</tr>
<tr><td height="5"></td></tr>
<tr><td>
 <table>
 <tr>
   <td width="240" height="15"></td>
   <td class="tagForm"></td>
   <td></td>
   <td width="10"><input type="button" id="btCuenta" name="btCuenta" onclick="cargarVentanaSelectorCuentas(this.form,'listado_cuentas_contables.php?limit=0&campo=3','height=500, width=800, left=200, top=100, resizable=yes');" value="Seleccionar Cuenta"/></td>
   <td></td>
 </tr>
 <tr>
   <td width="100"></td>
   <td class='tagForm'>&Uacute;ltima Modif.:</td>
   <td>
	  <input type="text" name="ult_usuario"  id="ult_usuario" size="30"  readonly />
	  <input type="text" name="ult_fecha"  id="ult_fecha" size="20"  readonly />
   </td>
   <td width="100"></td>
  </tr>
</table> 
</td></tr>

</table>
  <center>
    <input name="guardar" type="submit" id="guardar" value="Guardar Registro"/>
    <input name="cancelar" type="button" id="cancelar" value="Cancelar" onClick="cargarPagina(this.form, 'af_categoriadepreciacion.php');" />
  </center><br />
</form>

<div style="width:400px" class="divFormCaption">Contabilidades V&aacute;lidas</div>
<form name="frmdetalles" id="frmdetalles">
<input type="hidden" id="seldetalle" />
<input type="hidden" id="candetalle" />

<table width="400" class="tblBotones">
 <tr>
	<td align="right">
    	<input type="button" class="btLista" id="btInsertar" value="Insertar" onclick="insertarLineaCatNueva();" />
        <input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="quitarLinea(document.getElementById('seldetalle').value);" />
	</td>
 </tr>
</table>

<table align="center" cellpadding="0" cellspacing="0"><tr><td valign="top" style="height:100px; width:150px;">
<table align="center" width="400px"><tr><td align="center"><div style="overflow:scroll; height:200px; width:400px;">
<table width="400px" class="tblLista">
    <tr class="trListaHead">
        <th scope="col" width="200">Contabilidad</th>
        <th scope="col">Depreciaci&oacute;n(%)</th>
    </tr>
                
    <tbody id="listaDetalles">
    
    </tbody>
</table>
</div></td></tr></table>
</td></tr></table>
</form>
<div style="width:700px" class="divMsj">Campos Obligatorios *</div>
</body>
</html>

