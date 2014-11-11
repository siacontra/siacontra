<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location:index.php");
//	------------------------------------
include ("fphp.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('01', $concepto);
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" language="javascript" src="fscript03.js"></script>

<style type="text/css">
<!--
UNKNOWN {
        FONT-SIZE: small
}
#header {
        FONT-SIZE: 93%; BACKGROUND: url(imagenes/bg.gif) #dae0d2 repeat-x 50% bottom; FLOAT: left; WIDTH: 100%; LINE-HEIGHT: normal
}
#header UL {
        PADDING-RIGHT: 10px; PADDING-LEFT: 10px; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 10px; LIST-STYLE-TYPE: none
}
#header LI {
        PADDING-RIGHT: 0px; PADDING-LEFT: 9px; BACKGROUND: url(imagenes/left.gif) no-repeat left top; FLOAT: left; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 0px
}
#header A {
        PADDING-RIGHT: 15px; DISPLAY: block; PADDING-LEFT: 6px; FONT-WEIGHT: bold; BACKGROUND: url(imagenes/right.gif) no-repeat right top; FLOAT: left; PADDING-BOTTOM: 4px; COLOR: #765; PADDING-TOP: 5px; TEXT-DECORATION: none
}
#header A {
        FLOAT: none
}
#header A:hover {
        COLOR: #333
}
#header #current {
        BACKGROUND-IMAGE: url(imagenes/left_on.gif)
}
#header #current A {
        BACKGROUND-IMAGE: url(imagenes/right_on.gif); PADDING-BOTTOM: 5px; COLOR: #333
}
-->
</style>

<body>
<script type="text/javascript" language="javascript">
		 function comparaFecha(){
		  
		   var str1 = document.getElementById("fdesde").value;
           var str2 = document.getElementById("fhasta").value;
		   
		   var dt1  = parseInt(str1.substring(0,2),10);
		   var mon1 = parseInt(str1.substring(3,5),10);
		   var yr1  = parseInt(str1.substring(6,10),10);
		   var dt2  = parseInt(str2.substring(0,2),10);
		   var mon2 = parseInt(str2.substring(3,5),10);
		   var yr2  = parseInt(str2.substring(6,10),10);
		   var date1 = new Date(yr1, mon1, dt1);
		   var date2 = new Date(yr2, mon2, dt2); 
		   
		   var diferencia = date1 - date2;
           var Dias = Math.floor(diferencia / (1000 * 60 * 60 * 24));
		       Dias = -1 * Dias;
		       document.getElementById("dias").value= Dias;
		 }
</script>
<table width="100%" cellspacing="0" cellpadding="0">
<tr>
 <td class="titulo">Anteproyecto| Nuevo</td>
 <td align="right"><a class="cerrar"; href="../presupuesto/framemain.php">[Cerrar]</a></td>
</tr>
</table><hr width="100%" color="#333333" />
<?php
$annio_actual=date("Y");
$mes_actual=date("m");
$dia_actual=date("d");
$hora_actual=date("H");
$min_actual=date("i");
$periodo=$annio_actual."-".$mes_actual;
$fecha=$dia_actual."-".$mes_actual."-".$annio_actual;
$fecha2=$dia_actual."-".$mes_actual."-".$annio_actual;
if ($hora_actual<12) $meridiano="AM";
else {
	$meridiano="PM";
	$hora_actual=(int) $hora_actual;
	$hora_actual-=12;
	if ($hora_actual==0) $hora_actual=12;
	if ($hora_actual<10) $hora_actual="0$hora_actual";
}
$hora=$hora_actual.":".$min_actual;
?>
<form id="frmentrada" name="frmentrada" action="presupuesto_detalle.php?accion=GuardarDatosPres" method="POST" onsubmit="return verificarDatosgenerales(this,'Guardar')">
<?php
$limit=(int) $limit;
echo "
<input type='hidden' name='codantepres' id='codantepres' value='".$codantepres."'/>
<input type='hidden' name='filtro' id='filtro' value='".$filtro."' />
<input type='hidden' name='regresar' id='regresar' value='".$regresar."' />
<input type='hidden' name='forganismo' id='forganismo' value='".$forganismo."' />
<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />";
?>

<?php
include "gmsector.php";
$sql="SELECT * FROM pv_sector,pv_programa1,pv_subprog1,pv_actividad1,pv_proyecto1 WHERE 1";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0){
 $field=mysql_fetch_array($query);
 list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaDesde']); $fdesde=$d.'/'.$m.'/'.$a;
 list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaHasta']); $fhasta=$d.'/'.$m.'/'.$a;
 $limit=(int) $limit;
}
?>
<table width="808" align="center">
<tr>
  <td>
	<div id="header">
	<ul>
	<!-- CSS Tabs -->
    <li><a onclick="document.getElementById('tab1').style.display='block';" href="#">Datos Generales</a></li>
	<li><a onclick="javascript:alert('&iexcl;DEBE GUARDAR PRIMERO!');" href="#">Detalle de Presupuesto</a></li>
	</ul>
	</div>
  </td>
</tr>
</table>
<!--////////////////////////// **********DATOS GENERALES DEL PRESUPUESTO*************  ///////////////////////-->   
<div id="tab1" style="display:block;">
<div style="width:800px" class="divFormCaption">Informaci&oacute;n de Presupuesto</div>
<table width="800" class="tblForm">
	<tr>
		<td width="87"></td>
		<td width="151" class="tagForm">Organismo:</td>
		<td width="342">
			<select name="organismo" id="organismo" class="selectBig">
			<?php 
			// segundo bloque php //* Conectamos a los datos *//
			include "conexion.php";
			$sql="SELECT CodOrganismo,Organismo FROM mastorganismos WHERE 1";
			$rs=mysql_query($sql);
			while($reg=mysql_fetch_assoc($rs)){
			$codOrganismo=$reg['CodOrganismo'];// Codigo del orgnismo
			$organismo=$reg['Organismo'];// Descripcion del Organismo
			   echo "<option value=$codOrganismo>$organismo</option>";
			}
			?></select>		</td>
		<td colspan="2"></td>
	</tr>
	<tr>
	<td></td>		
		<td class="tagForm">Ejercicio P.:</td>
		<td><? $ano = date(Y); // devuelve el año
		       $fcreacion= date("d-m-Y");//Fecha de Creación ?>
			<input title="A&ntilde;o de Presupuesto" name="ejercicioPpto" type="text" id="ejercicioPpto" size="3" />* 
			F.Creaci&oacute;n:<input name="fcreacion" type="text" id="fcreacion" size="8" value="<?=$fcreacion?>" readonly /> 
			 Estado:<input name="estado" type="text" id="estado" size="11" value="Preparado" readonly/>		</td>
	</tr>
	</table>
	<!---////////////////////////////////////////////////////////////////////////////////////////////////-->
	<!---/////////////////// ********   CARGAR SELECT SECTOR PRESUPUESTO  *******   /////////////////////-->
	<table width="800" class="tblForm">
	<tr>
	  <td width="83"></td>
	  <td width="181" class="tagForm">Sector:</td>
	  <td width="520"><select name="sector" id="sector" class="selectMed" onchange="getOptions_5(this.id, 'programa', 'subprograma', 'proyecto', 'actividad');">
        <option value=""></option>
        <?php getSector('', 0); ?>
      </select>*		</td>
	</tr>
	<tr>
	  <td width="83"></td>
	  <td class="tagForm">Programa:</td>
	  <td><select name="programa" id="programa" class="selectMed" disabled>
        <option value=""></option>
      </select>*</td>
	</tr>
	<tr>
	  <td width="83"></td>
	  <td class="tagForm">Sub-Programa:</td>
	  <td>
			<select name="subprograma" id="subprograma" class="selectMed" disabled>
				<option value=""></option>
			</select>*	  </td>
	</tr>
	<tr>
	  <td width="83"></td>
	  <td class="tagForm">Proyecto:</td>
	  <td>
			<select name="proyecto" id="proyecto" class="selectMed" disabled>
				<option value=""></option>
			</select>*	  </td>
	</tr>
	<tr>
	  <td width="83"></td>
	  <td class="tagForm">Actividad:</td>
	  <td>
			<select name="actividad" id="actividad" class="selectMed" disabled>
				<option value=""></option>
			</select>*	  </td>
	</tr>
	<tr><td></td></tr>
	</table>
	<div style="width:800px" class="divFormCaption">Duraci&oacute;n de Presupuesto</div>
	<table width="800" class="tblForm"> 
	<tr><td></td></tr>
	<tr><td></td>
		<td class="tagForm">F. Inicio:</td>
	    <td colspan="2"><input name="fdesde" type="text" id="fdesde" size="10" value="<?=$fecha?>" maxlength="10"/>*<i>(dd-mm-aaaa)</i></td>
	</tr>	
	<tr><td></td>
		<td class="tagForm">F. Termino:</td>
	    <td colspan="2"><input name="fhasta" type="text" id="fhasta" size="10" value="<?=$fecha2?>" maxlength="10"/>*<i>(dd-mm-aaaa)</i></td>
	</tr>	
	<tr><td></td>
		<td class="tagForm">Duraci&oacute;n:</td>
	    <td><input type="text" name="dias"  id="dias" size="6" maxlength="3" onclick="comparaFecha()" readonly/> d&iacute;as.</td>
		<td colspan="1"></td>
	</tr>
	<tr><td></td></tr>
	</table>
	<!---  TABLA 2 ------>
	<div style="width:800px" class="divFormCaption">Duraci&oacute;n de Presupuesto</div>
    <table width="800" class="tblForm">
	<tr><td></td></tr>
	<tr><td></td>
	  <td class="tagForm">Monto Autorizado:</td>
	  <td ><input name="montoautori" type="text" id="montoautori" size="20" maxlength="15"/>*<em>(99999999,99)</em></td>
	</tr>
	<tr><td></td>
	  <td class="tagForm">Monto Restante:</td>
	  <td><input name="montorestante" id="montorestante" type="text" size="20" maxlength="15" readonly/><em>(99999999,99)</em></td>
	</tr>
	<tr><td></td></tr>
	<tr><td></td>
	   <td class="tagForm">Preparado por:</td><? $sql3=mysql_query("SELECT * FROM usuarios WHERE Usuario='".$_SESSION['USUARIO_ACTUAL']."'");
	                                             if(mysql_num_rows($sql3)!=0){
												   $field3=mysql_fetch_array($sql3);
												   $sql4=mysql_query("SELECT * FROM mastpersonas WHERE CodPersona='".$field3['CodPersona']."'");
												   if(mysql_num_rows($sql4)!=0){
												     $field4=mysql_fetch_array($sql4);
												   }
												 }
										      ?>
	   <td><input name="prepor" id="prepor" type="text" size="60" value="<?=$field4['NomCompleto']?>" readonly/></td>
	<tr>
	<tr><td></td>
	   <td class="tagForm">Aprobado por:</td>
	   <td><input name="codempleado" type="hidden" id="codempleado" value="" />
	       <input name="nomempleado" id="nomempleado" type="text" size="60" readonly/>
		   <input name="bt_examinar" id="bt_examinar" type="button" value="..." onclick="cargarVentana(this.form, 'lista_empleados.php?limit=0&campo=1', 'height=500, width=800, left=200, top=200, resizable=yes');" /> </td>
	</tr>
	<tr><td></td>
		<? $ahora=date("Y-m-d H:m:s");
           echo"<td class='tagForm'>&Uacute;ltima Modif.:</td>
	           <td>
	             <input name='ult_usuario' type='text' id='ult_usuario' size='30' value='".$_SESSION['USUARIO_ACTUAL']."' readonly />
	             <input name='ult_fecha' type='text' id='ult_fecha' size='25' value='$ahora' readonly />
	           </td>";
	   ?>
  </tr>
</table>
<center>
<input name="btguardar" type="submit" id="btguardar" value="Guardar Registro"/>
<input name="btcancelar" type="button" id="btcancelar" value="Cancelar" onclick="cargarPagina(this.form, 'listar_anteproyecto.php?limit=0');"/>
</center></div>
<div id="tab2" style="display:none;">
</form>
<div style="width:700px" class="divMsj">Campos Obligatorios *</div>
</body>
</html>
<!-- VALIDACIONES CAMPOS OBLIGATORIOS  -->
<SCRIPT LANGUAGE="JavaScript">
function verificarDatosgenerales(formulario) {
	
	       //VALIDACION AÑO DEL PRESUPUESTO
		   if (formulario.ejercicioPpto.value.length <1) {
	  		 alert("Escriba los datos correctos en el campo \"Ejercicio P.\".");
	   		 formulario.ejercicioPpto.focus();
	      return (false);
	      }
          var checkOK ="0123456789";
	      var checkStr = formulario.ejercicioPpto.value;
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
	         alert("Escriba sólo números en el campo \"Ejercicio P.\"."); 
	         formulario.ejercicioPpto.focus(); 
	         return (false); 
	       } 
		   //VALIDACION SECTOR
		   if (formulario.sector.value.length <2) {
	  		 alert("Seleccione el Sector a utilizar.");
	   		 formulario.sector.focus();
	      return (false);
	      }
          var checkOK ="0123456789";
	      var checkStr = formulario.sector.value;
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
	         alert("Seleccione el Sector a utilizar."); 
	         formulario.sector.focus(); 
	         return (false); 
	       } 
		   //VALIDACION PROGRAMA
		   if (formulario.programa.value.length <1) {
	  		 alert("Seleccione el Programa a utilizar 1.");
	   		 formulario.programa.focus();
	      return (false);
	      }
          var checkOK ="0123456789";
	      var checkStr = formulario.programa.value;
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
	         alert("Seleccione el Programa a utilizar 2."); 
	         formulario.programa.focus(); 
	         return (false); 
	       } 
		   //VALIDACION SUB-PROGRAMA
		   if (formulario.subprograma.value.length <1) {
	  		 alert("Seleccione el Sub-Programa a utilizar.");
	   		 formulario.subprograma.focus();
	      return (false);
	      }
          var checkOK ="0123456789";
	      var checkStr = formulario.subprograma.value;
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
	         alert("Seleccione el Sub-Programa a utilizar."); 
	         formulario.subprograma.focus(); 
	         return (false); 
	       } 
		   //VALIDACION PROYECTO
		   if (formulario.proyecto.value.length <1) {
	  		 alert("Seleccione el Proyecto a utilizar.");
	   		 formulario.proyecto.focus();
	      return (false);
	      }
          var checkOK ="0123456789";
	      var checkStr = formulario.proyecto.value;
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
	         alert("Seleccione el Proyecto a utilizar."); 
	         formulario.proyecto.focus(); 
	         return (false); 
	       }
		   //VALIDACION ACTIVIDAD
		   if (formulario.actividad.value.length <1) {
	  		 alert("Seleccione el Actividad a utilizar.");
	   		 formulario.actividad.focus();
	      return (false);
	      }
          var checkOK ="0123456789";
	      var checkStr = formulario.actividad.value;
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
	         alert("Seleccione el Actividad a utilizar."); 
	         formulario.actividad.focus(); 
	         return (false); 
	       } 
		   //VALIDACION FECHA INICIO
		   if (formulario.finicio.value.length <10) {
	  		 alert("Escriba los datos correctos en el campo \"F. Inicio\".");
	   		 formulario.finicio.focus();
	      return (false);
	      }
          var checkOK ="0123456789" + "-";
	      var checkStr = formulario.finicio.value;
	      var allValid = true; 
	      for (i = 0; i < checkStr.length; i++){
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
	         alert("Escriba sólo números en el campo \"F. Inicio\"."); 
	         formulario.finicio.focus(); 
	         return (false); 
	       } 
		   //VALIDACION FECHA INICIO
		   if (formulario.ftermino.value.length <10) {
	  		 alert("Escriba los datos correctos en el campo \"F. Termino\".");
	   		 formulario.ftermino.focus();
	      return (false);
	      }
          var checkOK ="0123456789" + "-";
	      var checkStr = formulario.ftermino.value;
	      var allValid = true; 
	      for (i = 0; i < checkStr.length; i++){
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
	         alert("Escriba sólo números en el campo \"F. Termino\"."); 
	         formulario.ftermino.focus(); 
	         return (false); 
	       } 
		   //VALIDACION MONTO AUTORIZADO
		   if (formulario.montoautori.value.length <1) {
	  		 alert("Escriba los datos correctos en el campo \"Monto Autorizado\".");
	   		 formulario.montoautori.focus();
	      return (false);
	      }
          var checkOK ="0123456789" + ",";
	      var checkStr = formulario.montoautori.value;
	      var allValid = true; 
	      for (i = 0; i < checkStr.length; i++){
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
	         alert("Escriba sólo números en el campo \"Monto Autorizado\"."); 
	         formulario.montoautori.focus(); 
	         return (false); 
	       } 
		   //VALIDACION APROBADO POR
		   if (formulario.nomempleado.value.length <2) {
	  		 alert("Elija por quien sera aprobado haciendo click en el botón");
	   		 formulario.nomempleado.focus();
	      return (false);
	      }
          var checkOK = "ABCDEFGHIJKLMNÑOPQRSTUVWXYZÁÉÍÓÚ" + "abcdefghijklmnñopqrstuvwxyzáéíóú" + " .,_/";
	      var checkStr = formulario.nomempleado.value;
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
	         alert("Elija por quien sera aprobado haciendo click en el botón"); 
	         formulario.nomempleado.focus(); 
	         return (false); 
	       } 
	return (true); 
	} 
</SCRIPT>