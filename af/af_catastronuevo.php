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
  <td class="titulo">Maestro Catastro | Nuevo Registro</td>
  <td align="right">
   <a class="cerrar" href="framemain.php">[cerrar]</a>
  </td>
 </tr>
</table>
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<hr width="100%" color="#333333" />
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<form name="fentrada" id="fentrada" action="af_catastro.php" method="POST" onsubmit="return verificarFormulario(this);">
<?php echo "<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />"; ?>

<div style="width:700px" class="divFormCaption">Datos</div>
<table width="700" class="tblForm">
  <tr>
    <td class="tagForm">C&oacute;digo de Catastro:</td>
    <td><input type="text" name="cod_catastro"  id="cod_catastro" size="4" maxlength="4"/>*</td>
  </tr>
  <tr>
    <td class="tagForm">Descripci&oacute;n:</td>
    <td><input type="text" name="descp_catastro"  id="descp_catastro" size="60" maxlength="100" />*</td>
  </tr>
   <tr>
	    <td class='tagForm'>Estado:</td>
	    <td>
		   <input name="status_catastro1" type="radio" value="A" checked="checked" onclick="asignarEstado1(this.form)" /> Activo
		   <input name="status_catastro2" type="radio" value="I" onclick="asignarEstado2(this.form)" /> Inactivo
           <input type="hidden" id="radioEstado" name="radioEstado" value="A" />
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
	  <input name='ult_usuario' type='text' id='ult_usuario' size='30'  readonly />
	  <input name='ult_fecha' type='text' id='ult_fecha' size='25'  readonly />
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
        <input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="quitarLinea(document.getElementById('seldetalle').value);" />
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
 
   return guardarCatastro(formulario);
 }
</script>
