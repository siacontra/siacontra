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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link href="css1.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" language="javascript" src="fscript03.js"></script>
<body>
<? 
echo "<input type='text' name='hola' id='hola' value='".$_POST['registro']."' />";

if($accion=="EDITAR"){
  $sql="SELECT CodAnteproyecto,Estado FROM pv_antepresupuesto WHERE CodAnteproyecto='".$_POST['registro']."'";
  $query=mysql_query($sql) or die ($sql.mysql_error());
  if(mysql_num_rows($query)!=0){
	$field=mysql_fetch_array($query);
	if(($field[1]=="Aprobado") or ($field[1]=="Anulado")){ ?>
	   <script type="text/javascript">
        alert("Según el estado del Anteproyecto no es posible ser editado");
        window.location.href="listar_anteproyecto.php";
       </script>
	    <? }
	 }
   }
?>
<!--<table width="100%" cellspacing="0" cellpadding="0">
<tr>
 <td class="titulo">Anteproyecto | Actualizaci&oacute;n</td>
 <td align="right"><a class="cerrar"; href="../presupuesto/framemain.php">[Cerrar]</a></td>
</tr>
</table><hr width="100%" color="#333333" />-->
<?php
$annio_actual=date("Y");
$mes_actual=date("m");
$dia_actual=date("d");
$hora_actual=date("H");
$min_actual=date("i");
$periodo=$annio_actual."-".$mes_actual;
$fecha=$dia_actual."-".$mes_actual."-".$annio_actual;
if ($hora_actual<12) $meridiano="AM";
else{
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
<!--////////////////////////// **********DATOS GENERALES DEL PRESUPUESTO*************  ///////////////////////-->   
<div id="tab1" style="display:block;">
<div style="width:790px" class="divFormCaption">Informaci&oacute;n de Antepresupuesto</div>
<table width="790" class="tblForm">
	<tr>
		<td width="87"></td>
		<td width="151" class="tagForm">Organismo:</td>
		<td width="342">
			<select name="organismo" id="organismo" class="selectBig">
			<?php 
			// segundo bloque php //* Conectamos a los datos *//
			include "conexion_.php";
			$sql="SELECT CodOrganismo,Organismo FROM mastorganismos WHERE 1";
			$rs=mysql_query($sql);
			while($reg=mysql_fetch_assoc($rs)){
			$codOrganismo=$reg['CodOrganismo'];// Codigo de Sector
			$organismo=$reg['Organismo'];// Codigo Programa
			$p=0;
			if (($cod_sector==$cs)){
			   echo "<option value=$cs>$organismo</option>";
			}
			}
			?></select></td>
		<td colspan="2"></td>
	</tr>
	<tr>
	<td></td>		
		<td class="tagForm">A&ntilde;o P.:</td>
		<td><? $sql=mysql_query("SELECT * FROM pv_antepresupuesto WHERE CodAnteproyecto='001'");
		       if(mysql_num_rows($sql)!=0){
			      $field=mysql_fetch_array($sql);
			   } 
			   list($a, $m, $d)=SPLIT('[/.-]', $field['FechaAnteproyecto']); $fecha="$d-$m-$a";?>
			<!--<input title="A&ntilde;o de Presupuesto" name="anop" type="text" id="anop" size="3" value="<?=$field['EjercicioPpto']?>" readonly/>*-->
			<input title="A&ntilde;o de Presupuesto" name="anop" type="text" id="anop" size="3" maxlength="4" value="<?=$field['EjercicioPpto']?>"/>
			F.Creaci&oacute;n:<input name="fcreacion" type="text" id="fcreacion" size="8" value="<?=$fecha?>" readonly /> 
			 Estado:<input name="estado" type="text" id="estado" size="11" value="<?=$field['Estado']?>" readonly/>		</td>
	</tr>
	</table>
	<!---////////////////////////////////////////////////////////////////////////////////////////////////-->
	<!---/////////////////// ********   CARGAR SELECT SECTOR PRESUPUESTO  *******   /////////////////////-->
	<table width="790" class="tblForm">
	<tr>
	  <td width="83"></td>
	  <td width="181" class="tagForm">Sector:</td>
	  <td width="520"><select name="sector" id="sector" class="selectMed" onchange="getOptions_5(this.id, 'programa', 'subprograma', 'proyecto', 'actividad');">
        <option value=""></option>
        <?php getSector('', 0); ?>
      </select>*</td>
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
	<div style="width:790px" class="divFormCaption">Duraci&oacute;n de Presupuesto</div>
	<table width="790" class="tblForm"> 
	<tr><td></td></tr>
	<tr><td></td><?php
	
				    /*$date1=strtotime($field['FechaInicio']);
					$date2=strtotime($field['FechaFin']);
					//$date1="2010-04-27";
					//$date2="2010-05-27";
					
					$s = ($date1)-($date2);
					$d = intval($s/86400);
					$s -= $d*86400;
					$h = intval($s/3600);
					$s -= $h*3600;
					$m = intval($s/60);
					$s -= $m*60;
					
					$dif= (($d*24)+$h).hrs." ".$m."min";
					$dif2= abs($d.$space);*/
					
					
				
					
					list($a, $m, $d)=SPLIT('[/.-]', $field['FechaInicio']); $fechad="$d-$m-$a";
					list($a, $m, $d)=SPLIT('[/.-]', $field['FechaFin']); $fechah="$d-$m-$a";
					
					$dif2 = getFechaDias($fechad, $fechah)
			      ?>
		<td class="tagForm">F. Inicio:</td>
	    <td colspan="2"><input name="fdesde" type="text" id="finicio" size="10" value="<?=$fechad?>" maxlength="10" />*<i>(dd-mm-aaaa)</i></td>
	</tr>	
	<tr><td></td>
		<td class="tagForm">F. Termino:</td>
	    <td colspan="2"><input name="fhasta" type="text" id="ftermino" size="10" value="<?=$fechah?>" maxlength="10" />*<i>(dd-mm-aaaa)</i></td>
	</tr>	
	<tr><td></td>
		<td class="tagForm">Duraci&oacute;n:</td>
	    <td><input name="dias" type="text" id="dias" size="6" maxlength="3" value="<?=$dif2?>" readonly/> d&iacute;as.</td>
		<td colspan="1"></td>
	</tr>
	<tr><td></td></tr>
	</table>
	<!---  TABLA 2 ------>
	<div style="width:790px" class="divFormCaption">Duraci&oacute;n de Presupuesto</div>
    <table width="790" class="tblForm">
	<tr><td></td></tr>
	<tr><td></td>
	  <td class="tagForm">Monto Autorizado:</td>
	  <td ><input name="montoautori" type="text" id="montoautori" size="20" maxlength="15" value="<?=$field['MontoPresupuestado']?>"/>*<em>(99999999,99)</em></td>
	</tr>
	<tr><td></td>
	  <td class="tagForm">Monto Restante:</td>
	  <td><input name="montorestante" id="montorestante" type="text" size="20" maxlength="15" readonly/></td>
	</tr>
	<tr><td></td></tr>
	<tr><td></td>
	   <td class="tagForm">Preparado por:</td>
	   <td><input name="prepor" id="prepor" type="text" size="60" value="<?=$field['PreparadoPor']?>" readonly/></td>
	<tr>
	<tr><td></td>
	   <td class="tagForm">Aprobado por:</td>
	   <td><input name="codempleado" type="hidden" id="codempleado" value="" />
	       <input name="nomempleado" id="nomempleado" type="text" size="60" value="<?=$field['AprobadoPor']?>" readonly/>
		   <input name="bt_examinar" id="bt_examinar" type="button" value="..." onclick="cargarVentana(this.form, 'lista_aprobador.php?limit=0&campo=1', 'height=500, width=800, left=200, top=200, resizable=yes');" /> </td>
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
<!--<input name="btguardar" type="submit" id="btguardar" value="Guardar Registro"/>
<input name="btcancelar" type="button" id="btcancelar" value="Cancelar" onclick="cargarPagina(this.form, 'listar_anteproyecto.php');"/>-->
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
		   if (formulario.anop.value.length <1) {
	  		 alert("Escriba los datos correctos en el campo \"Año P.\".");
	   		 formulario.anop.focus();
	      return (false);
	      }
          var checkOK ="0123456789";
	      var checkStr = formulario.anop.value;
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
	         alert("Escriba sólo números en el campo \"Año P.\"."); 
	         formulario.anop.focus(); 
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
		   if (formulario.programa.value.length <2) {
	  		 alert("Seleccione el Programa a utilizar.");
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
	         alert("Seleccione el Programa a utilizar."); 
	         formulario.programa.focus(); 
	         return (false); 
	       } 
		   //VALIDACION SUB-PROGRAMA
		   if (formulario.subprograma.value.length <2) {
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
		   if (formulario.proyecto.value.length <2) {
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
		   if (formulario.actividad.value.length <2) {
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
          var checkOK ="0123456789" + ".,";
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
</SCRIPT>>