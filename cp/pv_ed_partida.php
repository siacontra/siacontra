<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript01.js"></script>
<script type="text/javascript" language="javascript" src="fscript.js"></script>
</head>
<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Maestro de Partida | Actualizaci&oacute;n</td>
		<td align="right"><a class="cerrar" href="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />
<?php
include("fphp.php");
connect();
echo "<input type='hidden' name='fi' id='fi' value='".$_POST['registro']."'/>";
$sql="SELECT * FROM pv_partida WHERE cod_partida='".$_POST['registro']."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
if($rows=mysql_num_rows($query)!=0) {
  $field=mysql_fetch_array($query);
  echo "
   <form name='frmentrada' action='mpartida.php?accion=editarPartida' method='POST' onsubmit='return verificarPartida(this, \"EDITAR\");'>
   <input type='hidden' name='filtro' id='filtro' value='".$_GET['filtro']."'>
   <input type='hidden' name='idprograma' id='idprograma' value='".$field['cod_partida']."'>
   <div style='width:700px' class='divFormCaption'>Datos de la Partida</div>
   <table width='700' class='tblForm'>
   <table width='700' class='tblForm'>
   <tr>
     <td class='tagForm'>Partida Presupuestaria N&deg;:</td>
	 <td><input name='codpartida' type='text' id='codpartida' size='20' maxlength='15' value='".$field['cod_partida']."' readonly/></td>
   </tr>
   </table>
   <table width='700' class='tblForm'>
   <tr>";
      //  //*  *//
	  //// ******************* ////
	  include "conexion.php";
	  $sqlTCuenta="SELECT * FROM pv_tipocuenta WHERE cod_tipocuenta='".$field['cod_tipocuenta']."'";
	  $qryTCuenta=mysql_query($sql) or die ($sqlTCuenta.mysql_error());
	  $rowsTc=mysql_num_rows($qryTCuenta);
	  if($rowsTc!=0){
        $fieldTCuenta=mysql_fetch_array($qryTCuenta);
      }
       echo"<td class='tagForm'>Tipo cuenta:</td>
	        <td colspan='4'>
	          <select name='tcpartida'>
			   <option value='$fieldTCuenta[cod_tipocuenta]'>$fieldTCuenta[cod_tipocuenta]</option>";
       $sql="SELECT * FROM pv_tipocuenta";
	   $qryTC= mysql_query($sql) or die ($sql.mysql_error());
	   $fieldTC=mysql_fetch_array($qryTCuenta);
	   while($reg=mysql_fetch_assoc($qryTC)){
	     $ct=$reg['cod_tipocuenta'];// Codigo Tipo Cuenta
		 if($fieldTCuenta['cod_tipocuenta']!=$ct){
		   if($cod_tipocuenta == $ct){
		      echo "<option value='$ct'>$ct</option>";
		   }else{
		      echo "<option value='$ct'>$ct</option>";
		   }
		 }
	   }
	echo"
    </select> </td>
   </tr>
   <tr>
     <td class='tagForm'>Partida:</td>
	 <td colspan='4'><input name='partida1' type='text' id='partida1' size='10' maxlength='2' value='".$field['partida1']."'</td>
   </tr>
   <tr>
     <td class='tagForm'>Generica:</td>
	 <td colspan='4'><input name='generica' type='text' id='generica' size='10' maxlength='2' value='".$field['generica']."'</td>
   </tr>
   <tr>
     <td class='tagForm'>Especifica:</td>
	 <td colspan='4'><input name='especifica' type='text' id='especifica' size='10' maxlength='2' value='".$field['especifica']."'</td>
   </tr>
   <tr>
     <td class='tagForm'>Sub-Especifica:</td>
	 <td colspan='4'><input name='subespecifica' type='text' id='subespecifica' size='10' maxlength='2' value='".$field['subespecifica']."'   </td>
   </tr>
   <tr>
	 <td align='right'>Denominaci&oacute;n:</td>
     <td width='350'><input name='denominacion' type='text' id='denominacion' size='75' maxlength='200' value='".htmlentities($field['denominacion'])."' /></td>
	 <td width='65'>"; 
	         if ($field['tipo']=="D") echo "<input name='opcion' type='radio' value='D' checked /> Detalle"; 
				else echo "<input name='opcion' type='radio' value='D' /> Detalle";
				if ($field['tipo']=="T") echo "<input name='opcion' type='radio' value='T' checked /> T&iacute;tulo"; 
				else echo "<input name='opcion' type='radio' value='T' /> T&iacute;tulo";
			echo "
	 </td>
	 <td width='80'></td>
  </tr>
  </table>
  <table class='tblForm' width='700'>
  <tr>
     <td class='tagForm'>Nivel:</td>
	 <td><select name='nivel'>
         <OPTION VALUE='1'>1</OPTION>
         <OPTION VALUE='2'>2</OPTION>
         <OPTION VALUE='3'>3</OPTION>
		 <OPTION VALUE='4'>4</OPTION>
		 <OPTION VALUE='5'>5</OPTION>
         </select> 
    </td>
  </tr>
  <tr>
	    <td class='tagForm'>Estado:</td>
	    <td>";
		   if ($field['Estado']=="A") echo "<input name='status' type='radio' value='A' checked /> Activo"; 
				else echo "<input name='status' type='radio' value='A' /> Activo";
				if ($field['Estado']=="I") echo "<input name='status' type='radio' value='I' checked /> Inactivo"; 
				else echo "<input name='status' type='radio' value='I' /> Inactivo";
			echo "
			</td>
  </tr>
  <tr>
	 <td class='tagForm' align='left'>&Uacute;ltima Modif.:</td>
	 <td>
	   <input name='ult_usuario' type='text' id='ult_usuario' size='30' value='".$field['UltimoUsuario']."' readonly />
	   <input name='ult_fecha' type='text' id='ult_fecha' size='25' value='".$field['UltimaFecha']."' readonly />
	 </td>
  </tr>
  </table>";
	//////         //////       //////      //////    //////    
	echo "
	<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />
	<center> 
	<input type='submit' value='Guardar Registro' name='editar' id='editar'/>
	<input name='bt_cancelar' type='button' id='bt_cancelar' value='Cancelar' onclick='cargarPagina(this.form,\"mpartida.php?limit=0\");' />
	</center><br />
	</form>
	<div style='width:700px' class='divMsj'>Campos Obligatorios *</div>";
	
}
?>
<SCRIPT LANGUAGE="JavaScript">
function verificarPartida(formulario) {
	
	       //VALIDACION TIPO CUENTA
		   if (formulario.tipocuenta.value.length <1) {
	  		 alert("Escriba los datos correctos en el campo \"Tipo Cuenta\".");
	   		 formulario.tipocuenta.focus();
	      return (false);
	      }
          var checkOK ="123456789";
	      var checkStr = formulario.tipocuenta.value;
	      var allValid = true; 
	      for (i = 0; i < checkStr.length; i++) {
	          ch = checkStr.charAt(i); 
	          for (j = 0; j < checkOK.length; j++)
	              if (ch == checkOK.charAt(j))
	              break;
	              if (j == checkOK.length) { 
	                 allValid = false; 
	              break; 
	              }
	      }
	      if (!allValid) { 
	         alert("Escriba slo nmeros en el campo \"Tipo Cuenta\"."); 
	         formulario.tipocuenta.focus(); 
	         return (false); 
	       } 
		   //////////////////////////
		   //VALIDACION PARTIDA
		   if (formulario.partida1.value.length <2) {
	  		 alert("Escriba los datos correctos en el campo \"Partida\".");
	   		 formulario.partida1.focus();
	      return (false);
	      }
          var checkOK ="0123456789";
	      var checkStr = formulario.partida1.value;
	      var allValid = true; 
	      for (i = 0; i < checkStr.length; i++) {
	          ch = checkStr.charAt(i); 
	          for (j = 0; j < checkOK.length; j++)
	              if (ch == checkOK.charAt(j))
	              break;
	              if (j == checkOK.length) { 
	                 allValid = false; 
	              break; 
	              }
	      }
	      if (!allValid) { 
	         alert("Escriba slo nmeros en el campo \"Partida\"."); 
	         formulario.partida1.focus(); 
	         return (false); 
	       } 
		   ///////////////////////////////
		   //VALIDACION GENERICA
		   if (formulario.generica.value.length <2) {
	  		 alert("Escriba los datos correctos en el campo \"Genrica\".");
	   		 formulario.generica.focus();
	      return (false);
	      }
          var checkOK ="0123456789";
	      var checkStr = formulario.generica.value;
	      var allValid = true; 
	      for (i = 0; i < checkStr.length; i++) {
	          ch = checkStr.charAt(i); 
	          for (j = 0; j < checkOK.length; j++)
	              if (ch == checkOK.charAt(j))
	              break;
	              if (j == checkOK.length) { 
	                 allValid = false; 
	              break; 
	              }
	      }
	      if (!allValid) { 
	         alert("Escriba slo nmeros en el campo \"Genrica\"."); 
	         formulario.generica.focus(); 
	         return (false); 
	       } 
		   ///////////////////////////////
		   //VALIDACION ESPECIFICA
		   if (formulario.especifica.value.length <2) {
	  		 alert("Escriba los datos correctos en el campo \"Especfica\".");
	   		 formulario.especifica.focus();
	      return (false);
	      }
          var checkOK ="0123456789";
	      var checkStr = formulario.especifica.value;
	      var allValid = true; 
	      for (i = 0; i < checkStr.length; i++) {
	          ch = checkStr.charAt(i); 
	          for (j = 0; j < checkOK.length; j++)
	              if (ch == checkOK.charAt(j))
	              break;
	              if (j == checkOK.length) { 
	                 allValid = false; 
	              break; 
	              }
	      }
	      if (!allValid) { 
	         alert("Escriba slo nmeros en el campo \"Especfica\"."); 
	         formulario.especifica.focus(); 
	         return (false); 
	       } 
		   ///////////////////////////////
		   //VALIDACION SUB-ESPECIFICA
		   if (formulario.subespecifica.value.length <2) {
	  		 alert("Escriba los datos correctos en el campo \"Sub-Especfica\".");
	   		 formulario.subespecifica.focus();
	      return (false);
	      }
          var checkOK ="0123456789";
	      var checkStr = formulario.subespecifica.value;
	      var allValid = true; 
	      for (i = 0; i < checkStr.length; i++) {
	          ch = checkStr.charAt(i); 
	          for (j = 0; j < checkOK.length; j++)
	              if (ch == checkOK.charAt(j))
	              break;
	              if (j == checkOK.length) { 
	                 allValid = false; 
	              break; 
	              }
	      }
	      if (!allValid) { 
	         alert("Escriba slo nmeros en el campo \"Sub-Especfica\"."); 
	         formulario.subespecifica.focus(); 
	         return (false); 
	       } 
		   //////////////////////////
		   //VALIDACION DENOMINACION
		   if (formulario.denominacion.value.length <5) {
	  		 alert("Escriba los datos correctos en el campo \"Denominacin\".");
	   		 formulario.denominacion.focus();
	      return (false);
	      }
          var checkOK = "ABCDEFGHIJKLMNOPQRSTUVWXYZ" + "abcdefghijklmnopqrstuvwxyz" + " ._/";
	      var checkStr = formulario.denominacion.value;
	      var allValid = true; 
	      for (i = 0; i < checkStr.length; i++) {
	          ch = checkStr.charAt(i); 
	          for (j = 0; j < checkOK.length; j++)
	              if (ch == checkOK.charAt(j))
	              break;
	              if (j == checkOK.length) { 
	                 allValid = false; 
	              break; 
	              }
	      }
	      if (!allValid) { 
	         alert("Escriba slo letras en el campo \"Denominacin\"."); 
	         formulario.denominacion.focus(); 
	         return (false); 
	       } 
	return (true); 
	} 
</SCRIPT>
<? include "gmsector.php";?>
</body>
</html>
