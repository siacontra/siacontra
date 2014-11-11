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

<table width="100%" cellspacing="0" cellpadding="0">
 <tr>
  <td class="titulo">Maestro de Contabilidades | Nuevo Registro</td>
  <td align="right">
   <a class="cerrar" href="framemain.php">[cerrar]</a>
  </td>
 </tr>
</table>
<hr width="100%" color="#333333" />

<form name="fentrada" id="fentrada" action="af_contabilidades.php" method="POST" onsubmit="return  guardarContabilidades(this);">
<?php echo "<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />"; ?>

<div style="width:500px" class="divFormCaption">Datos</div>
<table width="492px" border="0" class="tblForm">
<tr>
 <td>
  <table width="492px">
  <tr>
    <td class="tagForm">Contabilidad:</td>
    <td><input type="text" name="cod_contabilidad"  id="cod_contabilidad" size="4" maxlength="1"/>*</td>
  </tr>
  <tr>
    <td class="tagForm">Descripci&oacute;n:</td>
    <td><input type="text" name="descp_contabilidad"  id="descp_contabilidad" size="60" maxlength="100" />*</td>
  </tr>
   <tr>
	    <td class='tagForm'>Estado:</td>
	    <td>
           <input type="hidden" name="radioEstado" id="radioEstado" value="A"/>
		   <input name='radio1' type='radio'  checked="checked" onclick="estadosPosee(this.form);"/> Activo
		   <input name='radio2' type='radio' onclick="estadosPosee(this.form);"/> Inactivo
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
	  <input name='ult_usuario' type='text' id='ult_usuario' size='30'  readonly />
	  <input name='ult_fecha' type='text' id='ult_fecha' size='20'  readonly />
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
        <input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="quitarLinea(document.getElementById('seldetalle').value);" />
	</td>
 </tr>
</table>

<table align="center" cellpadding="0" cellspacing="0"><tr><td valign="top" style="height:250px; width:400px;">
<table align="center" width="100%"><tr><td align="center"><div style="overflow:scroll; height:200px; width:400px;">
<table width="100%" class="tblLista">
<thead>
    <tr class="trListaHead">
        <th scope="col" width="100">C&oacute;digo</th>
        <th scope="col">Libro Contable</th>
    </tr>
 </thead>
                
    <tbody id="listaDetalles">
    
    </tbody>
</table>
</div></td></tr></table>
</td></tr></table>
</form>
<div style="width:500px" class="divMsj">Campos Obligatorios *</div>
</body>
</html>

