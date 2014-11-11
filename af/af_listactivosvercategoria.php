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
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<table width="100%" cellspacing="0" cellpadding="0">
 <tr>
  <td class="titulo">Ver Registro</td>
  <td align="right"><a class="cerrar" href=" " onclick="window.close();">[Cerrar]</a></td>
 </tr>
</table>
<hr width="100%" color="#333333" />
<? 
 echo"<input type='hidden' name='regresar' id='regresar' value='".$regresar."' />";
 
 $sql="SELECT * FROM af_categoriadeprec WHERE CodCategoria='".$_GET['registro']."'";
 $qry=mysql_query($sql) or die ($sql.mysql_error());
 $field=mysql_fetch_array($qry);
?>

<form name="frmentrada" id="frmentrada" action="af_categoriadepreciacion.php" method="POST" >
<?php echo "<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />"; ?>

<div style="width:820px" class="divFormCaption">Datos</div>
<table width="820px" class="tblForm">
<tr>
<td align="center">

<table id="t_1" width="810" class="tblForm">
<tr>
 <td width="143" class="tagForm"><b>C&oacute;digo Categor&iacute;a:</b></td>
 <td width="161"><input type="text" name="codcategoria"  id="codcategoria" size="7" maxlength="10" value="<?=$field['CodCategoria']?>" readonly/>*</td>
 <td width="187" class="tagForm"><b><u>Cuentas Contables Activo</u></b></td>
 <td width="73"></td>
 <td width="150" class="tagForm"><b><u>Cuentas de Gastos</u></b></td>
</tr>
<tr>
 <td class="tagForm">Descripci&oacute;n Local:</td>
 <td><input type="text" name="descp_local"  id="descp_local" size="29" maxlength="250" value="<?=$field['DescripcionLocal']?>" readonly/>*</td>
 <td class="tagForm">Valor Hist&oacute;rico:</td>
 <td><input type="text" name="v_historico"  id="v_historico" size="8" maxlength="20" value="<?=$field['CuentaHistorica']?>" disabled/>*</td>
 <td class="tagForm">Depreciaci&oacute;n:</td>
 <td width="78"><input type="text" name="cg_depreciacion"  id="cg_depreciacion" size="8" maxlength="20" value="<?=$field['CuentaGastos']?>" disabled/>*</td>
</tr>
<tr>
 <td class="tagForm">Tipo de Deprec.:</td>
 <td><select name="t_depreciacion" id="t_depreciacion" disabled>
     <?
      //// CONSULTA PARA CARGAR EL SELECT  DE TIPO DEPRECIACION
	 $s_tdepre = "select * from mastmiscelaneosdet where CodMaestro = 'TIPODEPREC'";
	 $q_tdepre = mysql_query($s_tdepre) or die ($s_tdepre.mysql_error());
	 $r_tdepre = mysql_num_rows($q_tdepre);
	 for($i=0;$i<$r_tdepre;$i++){
		  $f_tdepre = mysql_fetch_array($q_tdepre);
		  if($f_tdepre['CodDetalle']==$field['TipoDepreciacion'])
		     echo"<option value='".$f_tdepre['CodDetalle']."' selected>".$f_tdepre['Descripcion']."</option>";
		  else echo"<option value='".$f_tdepre['CodDetalle']."'>".$f_tdepre['Descripcion']."</option>";
	 }
     ?>
     </select>*</td>
  <td class="tagForm">Adiciones:</td>
  <td><input type="text" name="cc_adiciones"  id="cc_adiciones" size="8" maxlength="20" value="<?=$field['CuentaHistoricaVariacion']?>" disabled/>*</td>
 <td class="tagForm">Ajuste x Inflaci&oacute;n:</td>
 <td><input type="text" name="cg_ajinflacion"  id="cg_ajinflacion" size="8" maxlength="20" value="<?=$field['CuentaGastosRevaluacion']?>" disabled/>*</td>
<tr>
 <td class="tagForm">Grupo Categor&iacute;a:</td>
 <td><select name="g_categoria" id="g_categoria" disabled>
       <? 
	    $s_categoria = "select * from mastmiscelaneosdet where CodMaestro = 'GRUPOCATEG'";
		$q_categoria = mysql_query($s_categoria) or die ($s_categoria.mysql_error());
		$r_categoria = mysql_num_rows($q_categoria);
		for($a=0; $a<$r_categoria; $a++){
		    $f_categoria=mysql_fetch_array($q_categoria);
			if($field['GrupoCateg']==$f_categoria['CodDetalle'])  
			  echo"<option value='".$f_categoria['CodDetalle']."' selected>".$f_categoria['Descripcion']."</option>";
			else
			  echo"<option value='".$f_categoria['CodDetalle']."'>".$f_categoria['Descripcion']."</option>";
		}
	   
	   ?>
     </select></td>
  <td class="tagForm">Ajustes x Inflaci&oacute;n:</td>
  <td><input type="text" name="cc_ajinflacion"  id="cc_ajinflacion" size="8" maxlength="20" value="<?=$field['CuentaHistoricaRevaluacion']?>" disabled/>*</td>
 <td class="tagForm"><b><u>Otras Cuentas Contables</u></b></td>
</tr>
 <td class="tagForm">Categor&iacute;a Inventariable:</td>
 <td><? 
      if($field['InventariableFlag']=='S'){?>
	     <input type="checkbox" id="cat_invent" name="cat_invent" checked disabled/>
      <? }else{ ?>
       <input type="checkbox" id="cat_invent" name="cat_invent" disabled/> 
       <? }?> 
 </td>
 <td class="tagForm"><b><u>Cuentas Contables Depreciaci&oacute;n</u></b></td>
 <td></td>
 <td class="tagForm">R.E.I.:</td>
 <td><input type="text" name="occ_rei"  id="occ_rei" size="8" maxlength="20" value="<?=$field['CuentaREI']?>" disabled/>*</td>
<tr>
 <td></td>
</tr>
<tr>
 <td class="tagForm"></td>
 <td></td>
 <td class="tagForm">Para Depreciaci&oacute;n:</td>
 <td><input type="text" name="cd_pdepreciacion"  id="cd_pdepreciacion" size="8" maxlength="20" value="<?=$field['CuentaDepreciacion']?>" disabled/>*</td>
 <td class="tagForm">Valor Neto:</td>
 <td><input type="text" name="occ_valorneto"  id="occ_valorneto" size="8" maxlength="20" value="<?=$field['CuentaNeto']?>" disabled/>*</td>
</tr>
<tr>
 <td></td>
 <td></td>
 <td class="tagForm">Adiciones:</td>
 <td><input type="text" name="cd_adiciones"  id="cd_adiciones" size="8" maxlength="20" value="<?=$field['CuentaDepreciacionVariacion']?>" disabled/>*</td>
 <td class="tagForm">Cta. Resultado:</td>
 <td><input type="text" name="occ_ctaresultado"  id="occ_ctaresultado" size="8" maxlength="20" value="<?=$field['CuentaResultado']?>" disabled/>*</td>
</tr>
<tr>
 <td class="tagForm">Estado:</td>
 <td><input type="hidden" name="radioEstado" id="radioEstado" value="<?=$field['Estado'];?>"/>
     <? if($field['Estado']=='A'){?> 
     <input type="radio" name="radio1"  id="radio1" checked disabled/>Activo
     <input type="radio" name="radio2" id="radio2" disabled/>Inactivo
     <? }else{?>
     <input type="radio" name="radio1" id="radio1" disabled/>Activo
     <input type="radio" name="radio2" id="radio2" checked disabled/>Inactivo
    <? }?>
 </td>
 <td class="tagForm">Ajustes x Inflaci&oacute;n:</td>
 <td><input type="text" name="cd_ajinflacion"  id="cd_ajinflacion" size="8" maxlength="20" value="<?=$field['CuentaDepreciacionRevaluacion']?>" disabled/>*</td>
</tr>
</table></td>
</tr>
<tr><td height="5"></td></tr>
<!-- ////////////////////////////////////////////////////////////////// -->
<tr><td>
 <table>
<tr>
 <td width="240" height="15"></td>
 <td class="tagForm"></td>
 <td></td>
   <td width="10"></td>
  </tr>
  <tr>
	 <td width="100"></td>
	<td class='tagForm'>&Uacute;ltima Modif.:</td>
	<td>
	  <input type="text" name="ult_usuario"  id="ult_usuario" size="30" value="<?=$field['UltimoUsuario'];?>"  readonly />
	  <input type="text" name="ult_fecha"  id="ult_fecha" size="20" value="<?=$field['UltimaFechaModif'];?>" readonly />
	</td>
	<td width="100"></td>
  </tr>
</table> 
</td></tr>

</table>

  <!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
  <center>
    <input name="guardar" type="submit" id="guardar" value="Guardar Registro" disabled/>
    <input name="cancelar" type="button" id="cancelar" value="Cancelar" onClick="cargarPagina(this.form, 'af_categoriadepreciacion.php');" disabled/>
  </center><br />
  <!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
</form>
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<div style="width:400px" class="divFormCaption">Contabilidades V&aacute;lidas</div>
<form name="frmdetalles" id="frmdetalles">
<input type="hidden" id="seldetalle" />
<input type="hidden" id="candetalle" />

<table width="400" class="tblBotones">
 <tr>
	<td align="right">
    	<input type="button" class="btLista" id="btInsertar" value="Insertar" onclick="insertarLineaCatNueva();" disabled/>
        <input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="quitarLinea(document.getElementById('seldetalle').value);" disabled/>
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
    <? 
	   $s_con="select * from af_categoriacontabilidad where CodCategoria='".$field['CodCategoria']."'";
	   $q_con=mysql_query($s_con) or die ($s_con.mysql_error()); //echo $sdisext;
	   $r_con= mysql_num_rows($q_con); //echo $risext;
		
		if($r_con!=0){
		  for($i=0; $i<$r_con; $i++){
		    $f_con = mysql_fetch_array($q_con);  
			$cont++; //echo"cont=".$cont;	  
    ?>
    <tr class="trListaBody" onclick="mClk(this, 'seldetalle');" id="<?=$f_con['CodContabilidad'].'_'.$f_con['CodContabilidad'].'_'.$cont;?>">
     <td>
         <select name="select1" id="select1_<?=$_POST['candetalle']?>" style="width:100%;" disabled>
		<? 
		  $sa = "select * from ac_contabilidades where CodContabilidad = '".$f_con['CodContabilidad']."'";
		  $qa = mysql_query($sa) or die ($sa.mysql_error());
		  $fa = mysql_fetch_array($qa);
		echo"<option value='".$fa['CodContabilidad']."'>".$fa['Descripcion']."</option>";   ?>
         </select>
       </td>
     <td>
      <input type="text" name="descripcion" id="descripcion_a<?=$cont?>" value="<?=$f_con['DepreciacionPorcentaje'];?>"  style="width:95%;text-align:right;" readonly="readonly" disabled/>
     </td>
    </tr>
    <? }} ?>
    </tbody>
</table>
</div></td></tr></table>
</td></tr></table>
</form>
<div style="width:700px" class="divMsj">Campos Obligatorios *</div>
</body>
</html>

