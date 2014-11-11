<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
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
  <td class="titulo">Maestro de Contabilidades | Editar Registro</td>
  <td align="right">
   <a class="cerrar" href="framemain.php">[cerrar]</a>
  </td>
 </tr>
</table>
<hr width="100%" color="#333333" />
<?
 $sql="SELECT * FROM ac_contabilidades WHERE CodContabilidad='".$_POST['registro']."'";
 $qry=mysql_query($sql) or die ($sql.mysql_error());
 $field=mysql_fetch_array($qry); 
?>
<form name="fentrada" id="fentrada" action="af_contabilidades.php" method="POST" onsubmit="return  editarContabilidades(this);">
<?php echo "<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />"; ?>

<div style="width:500px" class="divFormCaption">Datos</div>
<table width="492px" border="0" class="tblForm">
<tr>
 <td>
  <table width="492px">
  <tr>
    <td class="tagForm">Contabilidad:</td>
    <td><input type="text" name="cod_contabilidad"  id="cod_contabilidad" size="4" maxlength="1" value="<?=$field['CodContabilidad'];?>" readonly/>*</td>
  </tr>
  <tr>
    <td class="tagForm">Descripci&oacute;n:</td>
    <td><input type="text" name="descp_contabilidad"  id="descp_contabilidad" size="60" maxlength="100" value="<?=$field['Descripcion'];?>"/>*</td>
  </tr>
   <tr>
	    <td class='tagForm'>Estado:</td>
	    <td><input type="hidden" id="radioEstado" name="radioEstado" value="<?=$field['Estado'];?>"/>
          <? if($field['Estado']=='A'){?>
		   <input name='radio1' type='radio'  checked="checked" onclick="estadosPosee(this.form);"/> Activo
		   <input name='radio2' type='radio' onclick="estadosPosee(this.form);"/> Inactivo
         <? }else{?>
           <input name='radio1' type='radio'  onclick="estadosPosee(this.form);"/> Activo
		   <input name='radio2' type='radio'  checked="checked" onclick="estadosPosee(this.form);"/> Inactivo
         <? }?>
	   </td>
  </tr>
  <tr>
   <td height="5"></td>
  </tr>
</table>
</td>
</tr>
<tr>
 <td>
 <table align="center">
 <tr>
   <td height="10"></td>
 </tr>  
   <tr><? 
     $ahora=date("Y-m-d H:m:s");
     echo"
	<td class='tagForm'>&Uacute;ltima Modif.:</td>
	<td>
	  <input name='ult_usuario' type='text' id='ult_usuario' size='30' value='".$field['UltimoUsuario']."'  readonly />
	  <input name='ult_fecha' type='text' id='ult_fecha' size='20' value='".$field['UltimaFechaModif']."' readonly />
	</td>";
	?>
   </tr>
  </table>
 </td>
 </tr>
</table>
  <!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
  <center>
    <input name="guardar" type="submit" id="guardar" value="Guardar Registro"/>
    <input name="cancelar" type="button" id="cancelar" value="Cancelar" onClick="cargarPagina(this.form, 'af_contabilidades.php');" />
  </center><br />
  <!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
</form>

<div style="width:400px" class="divFormCaption">Libros V&aacute;lidos</div>
<form name="frmdetalles" id="frmdetalles">
<input type="hidden" id="seldetalle" />
<input type="hidden" id="candetalle" />

<table width="400" class="tblBotones">
 <tr>
	<td align="right">
    	<input type="button" class="btLista" id="btInsertar" value="Insertar" onclick="insertarLineaContabilidad();" />
        <!--<input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="quitarLineaEditarContabilidades(document.getElementById('seldetalle').value, this.form);" />-->
        <input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="quitarLinea(document.getElementById('seldetalle').value, this.form);" />
	</td>
 </tr>
</table>

<table align="center" cellpadding="0" cellspacing="0"><tr><td valign="top" style="height:250px; width:400px;">
<table align="center" width="100%"><tr><td align="center"><div style="overflow:scroll; height:200px; width:400px;">
<table width="100%" class="tblLista">
    <tr class="trListaHead">
        <th scope="col" width="100">C&oacute;digo</th>
        <th scope="col">Libro Contable</th>
    </tr>
    <tbody id="listaDetalles">
    <? 
	   
	   $s_con="select * from ac_librocontabilidades where CodContabilidad='".$field['CodContabilidad']."'";
	   $q_con=mysql_query($s_con) or die ($s_con.mysql_error()); //echo $sdisext;
	   $r_con= mysql_num_rows($q_con); //echo $risext;
		
		if($r_con!=0){
		  for($i=0; $i<$r_con; $i++){
		    $f_con = mysql_fetch_array($q_con);  
			$cont++; //echo"cont=".$cont;	  
		?>
         <tr class="trListaBody" onclick="mClk(this, 'seldetalle');" id="<?=$f_con['CodContabilidad'].'_'.$f_con['CodLibroCont'].'_'.$cont;?>">
           <td>
           <input type="text" name="codigo" id="codigo_a<?=$cont?>" value="<?=$f_con['CodLibroCont'];?>"  style="width:100%;" readonly="readonly"/>
           </td>
           <td>
            <? 
			$sa = "select * from ac_librocontable";
		    $qa = mysql_query($sa) or die ($sa.mysql_error());
		    $ra = mysql_num_rows($qa); //echo "ra=".$ra;
		    ?>
		   <select name="l_contable" id="l_contable_<?=$_POST['candetalle']?>" style="width:100%;" onchange="cargarCodigo(this.id,<?=$cont?>);">
            <?
			   while($fa = mysql_fetch_array($qa)){
			      if($fa['CodLibroCont']==$f_con['CodLibroCont']){
				     echo"<option value='".$fa['CodLibroCont']."' selected>".$fa['Descripcion']."</option>";
				  }else{ 
				     echo"<option value='".$fa['CodLibroCont']."'>".$fa['Descripcion']."</option>";
				  }
			   }
			?>
			 </select>
           </td>
         </tr>
        <? }} ?>
    </tbody>
</table>
</div></td></tr></table>
</td></tr></table>
</form>
<div style="width:500px" class="divMsj">Campos Obligatorios *</div>
</body>
</html>

