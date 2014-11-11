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
<!--<link href="css1.css" rel="stylesheet" type="text/css" />-->
<link href="../css/estilo.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="../css/prettyPhoto.css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" language="javascript" src="af_fscript.js"></script>
<script type="text/javascript" src="../js/funciones.js" charset="utf-8"></script>

</head>

<body>
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<table width="100%" cellspacing="0" cellpadding="0">
 <tr>
  <td class="titulo">Maestro Catastro | Editar Registro</td>
  <td align="right">
   <a class="cerrar" href="framemain.php">[cerrar]</a>
  </td>
 </tr>
</table>
<?
  $scon = "select * from af_catastro where CodCatastro = '".$_POST['registro']."'";
  $qcon = mysql_query($scon) or die ($scon.mysql_error());
  $fcon = mysql_fetch_array($qcon);
  
?>

<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<hr width="100%" color="#333333" />
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<form name="fentrada" id="fentrada" action="af_catastro.php" method="POST" onsubmit="return verificarFormulario(this);">
<?php echo "<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />"; ?>

<div style="width:700px" class="divFormCaption">Datos</div>
<table width="700" class="tblForm">
  <tr>
    <td class="tagForm">C&oacute;digo de Catastro:</td>
    <td><input type="text" name="cod_catastro"  id="cod_catastro" size="10" style="text-align:right" maxlength="4" value="<?=$fcon['CodCatastro']?>" readonly/>*</td>
  </tr>
  <tr>
    <td class="tagForm">Descripci&oacute;n:</td>
    <td><input type="text" name="descp_catastro"  id="descp_catastro" size="60" maxlength="100" value="<?=$fcon['Descripcion'];?>" readonly/>*</td>
  </tr>
   <tr>
	    <td class='tagForm'>Estado:</td>
	    <td>
          <?
		   echo"<input type='text' id='radioEstado' name='radioEstado' value='".$fcon['Estado']."'/> "; 
           if($fcon['Estado']=='A'){
		     echo"<input name='status_catastro1' type='radio' checked='checked' onclick='asignarEstado3(this.form)'/> Activo";
			 echo"<input name='status_catastro2' type='radio' onclick='asignarEstado3(this.form)'/> Inactivo";
		   }else{
			 echo"<input name='status_catastro1' type='radio' onclick='asignarEstado3(this.form)'/> Activo";
			 echo"<input name='status_catastro2' type='radio' checked='checked' onclick='asignarEstado3(this.form)'/> Inactivo";
		   }
		  ?>
		   <!--<input name="status_catastro1" type="radio" value="A" checked="checked" onclick="asignarEstado1(this.form)" /> Activo
		   <input name="status_catastro2" type="radio" value="I" onclick="asignarEstado2(this.form)" /> Inactivo
           <input type="text" id="radioEstado" name="radioEstado" value="A" />-->
	   </td>
  </tr>
  <tr>
   <td height="5"></td>
  </tr>
  <tr><? 
     $ahora=date("Y-m-d H:m:s");
     echo"
	<td class='tagForm'>&Uacute;ltima Modif.:</td>
	<td>
	  <input name='ult_usuario' type='text' id='ult_usuario' size='30' value='".$fcon['UltimoUsuario']."'  readonly />
	  <input name='ult_fecha' type='text' id='ult_fecha' size='20' value='".$fcon['UltimaFechaModif']."' style='text-align:right'  readonly />
	</td>";
	?>
  </tr>
</table>
  <!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
  <center>
    <input name="guardar" type="submit" id="guardar" value="Guardar Registro"/>
    <input name="cancelar" type="button" id="cancelar" value="Cancelar" onClick="cargarPagina(this.form, 'af_catastro.php');" />
  </center><br />
  <!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
</form>

<div style="width:700px" class="divFormCaption" align="left">Precio x metro Cuadrado</div>
<form name="frmdetalles" id="frmdetalles">
<input type="hidden" id="seldetalle" />
<input type="hidden" id="candetalle" />

<table width="700" class="tblBotones">
 <tr>
	<td align="right">
    	<input type="button" class="btLista" id="btInsertar" value="Insertar" onclick="insertarLinea();" />
        <input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="quitarLineaCatastroEditar(document.getElementById('seldetalle').value);" />
	</td>
 </tr>
</table>

<table align="center" cellpadding="0" cellspacing="0"><tr><td valign="top" style="height:250px; width:700px;">
<table align="center" width="100%"><tr><td align="center"><div style="overflow:scroll; height:200px; width:696px;">
<table width="100%" class="tblLista">
    <tr class="trListaHead">
        <th scope="col" width="200">A&ntilde;o</th>
        <th scope="col" width="200">Precio Oficial</th>
        <th scope="col" width="200">Precio Mercado</th>
        <th scope="col" width="200">Fecha Referencial</th>
    </tr>
                
    <tbody id="listaDetalles">
    <?
     $s_catanual = "select * from af_catastroanual where CodCatastro='".$fcon['CodCatastro']."'";
	 $q_catanual = mysql_query($s_catanual) or die ($s_catanual.mysql_error());
	 $r_catanual = mysql_num_rows($q_catanual);
	 
	 if($r_catanual!=0){
		for($i=0;$i<$r_catanual; $i++){
		  $f_catanual = mysql_fetch_array($q_catanual) ;
	 //while ($f_catanual = mysql_fetch_array($q_catanual)){ 
	 $cont++;
	      list($a, $m, $d) = SPLIT('[-]',$f_catanual['FechaReferencia']); $f_referencial = $d.'-'.$m.'-'.$a;
	?>
    <!--<tr onclick="mClk(this, 'seldetalle');" id="det_<?=$cont?>">-->
    <tr onclick="mClk(this, 'seldetalle')" id="<?=$f_catanual['IdCatastroanual'];?>">
    <td><input type="hidden" name="id" id="id" value="<?=$f_catanual['IdCatastroanual'];?>">
        <input type="text" name="ano" id="ano" style="width:100%; text-align:center" value="<?=$f_catanual['Ano'];?>"></td>
    <td><input type="text" name="precio_Oficial" id="precio_Oficial" style="width:100%; text-align:right" value="<?=$f_catanual['PrecioOficial'];?>"></td>
    <td><input type="text" name="precio_Mercado" id="precio_Mercado" style="width:100%; text-align:right" value="<?=$f_catanual['PrecioMercado'];?>"></td>
    <td><input type="text" name="fecha_Referencial" id="fecha_Referencial" style="width:100%; text-align:center" value="<?=$f_referencial?>"></td>
    </tr>
    <? }}?>
    </tbody>
</table>
</div></td></tr></table>
</td></tr></table>
</form>
<div style="width:700px" class="divMsj">Campos Obligatorios *</div>
</body>
</html>
<script  language="javascript">

 function verificarFormulario(formulario){
	 
   if(formulario.descp_catastro.value.length<1){
     alert("Introduzca la descripcion del Catastro.");
	 formulario.descp_catastro.focus();
	 return (false);
   }
    var checkOK = "ABCDEFGHIJKLMNÑOPQRSTUVWXYZÁÉÍÓÚ" + "abcdefghijklmnñopqrstuvwxyzáéíóú" + ".,_/- " + "0123456789";
    var checkStr = formulario.descp_catastro.value;
    var allValid = true;
   for (i=0;i<checkStr.length;i++){
		ch = checkStr.charAt(i);
		for (j=0;j<checkOK.length;j++)
		 if (ch = checkOK.charAt(j))
		 break;
		 if (j == checkOK.length){
			allValid = false;
			break;
		 }
   }
	if (!allValid){
	  alert("Introduzca la descripcion del Catastro.");
	  formulario.descp_catastro.focus(); 
	  return (false);
	}
 
   return guardarCatastroEditar(formulario);
 }
</script>
