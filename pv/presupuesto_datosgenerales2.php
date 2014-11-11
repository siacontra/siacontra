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
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" language="javascript" src="fscript03.js"></script>
<body>
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
$sql="SELECT MAX(CodAnteproyecto) FROM pv_antepresupuesto";
$query=mysql_query($sql) or die ($sql.mysql_error());
if(mysql_num_rows($query)!=0){
  $field=mysql_fetch_array($query);
  $sqlAnt="SELECT * FROM pv_antepresupuesto WHERE CodAnteproyecto='".$field['0']."'";
  $qry=mysql_query($sqlAnt) or die ($sqlAnt.mysql_error());
 if(mysql_num_rows($qry)!=0){
   $fieldAnt=mysql_fetch_array($qry);
   list($a, $m, $d)=SPLIT( '[/.-]', $fieldAnt['FechaInicio']); $fInicio=$d.'-'.$m.'-'.$a;
   list($a, $m, $d)=SPLIT( '[/.-]', $fieldAnt['FechaFin']); $fFin=$d.'-'.$m.'-'.$a;
   list($a, $m, $d)=SPLIT( '[/.-]', $fieldAnt['FechaAnteproyecto']); $fAnt=$d.'-'.$m.'-'.$a;
   $limit=(int) $limit; 
}}
?>
<!--//////////////////////////  ********** DATOS GENERALES DEL PRESUPUESTO *************  ///////////////////////-->   
<div id="tab1" style="display:block;">
<div style="width:800px" class="divFormCaption">Informaci&oacute;n de Presupuesto</div>
<table width="800" class="tblForm">
	<tr>
		<td width="87"></td>
		<td width="151" class="tagForm">Organismo:</td>
		<? $sql2=mysql_query("SELECT * FROM mastorganismos WHERE CodOrganismo='".$fieldAnt['Organismo']."'");
		   if(mysql_num_rows($sql2)!=0){$field2=mysql_fetch_array($sql2);}?>
		<td width="342"><input name="organismo" id="organismo" value="<?=$field2['Organismo']?>" maxlength="100" size="60" readonly/></td>
		<td colspan="2"></td>
	</tr>	
	<tr>
	<td></td>		
		<td class="tagForm">A&ntilde;o P.:</td>
		<td><? $ano = date(Y); // devuelve el año
		       $fcreacion= date("d-m-Y");//Fecha de Creación ?>
			<input name="anop" type="text" id="anop" size="3" value="<?=$fieldAnt[EjercicioPpto]?>" readonly /> 
			F.Creaci&oacute;n:<input name="fcreacion" type="text" id="fcreacion" size="8" value="<?=$fAnt?>" readonly/> 
			 Estado:<input name="estado" type="text" id="estado" size="13" value="<?=$fieldAnt[Estado]?>" readonly/></td>
	   <td></td>
	</tr>
	</table>
	<!---////////////////////////////////////////////////////////////////////////////////////////////////-->
	<!---/////////////////// ********   CARGAR SELECT SECTOR PRESUPUESTO  *******   /////////////////////-->
	<table width="800" class="tblForm">
	<tr>
	  <td width="83"></td>
	  <td width="181" class="tagForm">Sector:</td><? $sql="SELECT * FROM pv_sector WHERE cod_sector='".$fieldAnt['Sector']."'";
	                                                 $qry=mysql_query($sql) or die ($sql.mysql_error());
													 if(mysql_num_rows($qry)!=0){$fieldSector=mysql_fetch_array($qry);}
												  ?>
	  <td width="520"><input name="sector" id="sector" value="<?=$fieldSector['descripcion']?>" size="50" readonly/></td>
	</tr>
	<tr>
	  <td width="83"></td>
	  <td class="tagForm">Programa:</td><? $sql="SELECT * FROM pv_programa1 WHERE id_programa='".$fieldAnt['Programa']."'";
	                                                 $qry=mysql_query($sql) or die ($sql.mysql_error());
													 if(mysql_num_rows($qry)!=0){$fieldPrograma=mysql_fetch_array($qry);}
												  ?>
	  <td><input name="programa" id="programa" value="<?=$fieldPrograma[descp_programa]?>" size="50" readonly/></td>
	</tr>
	<tr>
	  <td width="83"></td>
	  <td class="tagForm">Sub-Programa:</td><? $sql="SELECT * FROM pv_subprog1 WHERE id_sub='".$fieldAnt['SubPrograma']."'";
	                                                 $qry=mysql_query($sql) or die ($sql.mysql_error());
													 if(mysql_num_rows($qry)!=0){$fieldSubprog=mysql_fetch_array($qry);}
												  ?>
	  <td><input name="subprograma" id="subprograma" value="<?=$fieldSubprog[descp_subprog]?>" size="50" readonly/></td>
	</tr>
	<tr>
	  <td width="83"></td>
	  <td class="tagForm">Proyecto:</td><? $sql="SELECT * FROM pv_proyecto1 WHERE id_proyecto='".$fieldAnt['Proyecto']."'";
	                                                 $qry=mysql_query($sql) or die ($sql.mysql_error());
													 if(mysql_num_rows($qry)!=0){$fieldProyecto=mysql_fetch_array($qry);}
												  ?>
	  <td><input name="proyecto" id="proyecto" value="<?=$fieldProyecto[descp_proyecto]?>" size="50" readonly/></td>
	</tr>
	<tr>
	  <td width="83"></td>
	  <td class="tagForm">Actividad:</td><? $sql="SELECT * FROM pv_actividad1 WHERE id_actividad='".$fieldAnt['Actividad']."'";
	                                                 $qry=mysql_query($sql) or die ($sql.mysql_error());
													 if(mysql_num_rows($qry)!=0){$fieldActividad=mysql_fetch_array($qry);}
												  ?>
	  <td><input name="actividad" id="actividad" value="<?=$fieldActividad[descp_actividad]?>" size="50" readonly/></td>
	</tr>
	<tr><td></td></tr>
	</table>
	<div style="width:800px" class="divFormCaption">Duraci&oacute;n de Presupuesto</div>
	<table width="800" class="tblForm"> 
	<tr><td></td></tr>
	<tr><td></td>
		<td class="tagForm">F. Inicio:</td>
	    <td colspan="2"><input  name="finicio" type="text" id="finicio" size="10" maxlength="10" value="<?=$fInicio?>" readonly/>*(dd-mm-yyyy)</td>
	</tr>	
	<tr><td></td>
		<td class="tagForm">F. Termino:</td>
	    <td colspan="2"><input name="ftermino" type="text" id="ftermino" size="10" maxlength="10" value="<?=$fFin?>" readonly/>*(dd-mm-yyyy)</td>
	</tr>	
	<tr><td></td>
		<td class="tagForm">Duraci&oacute;n:</td><? $date1=strtotime($fieldAnt['FechaInicio']);
													$date2=strtotime($fieldAnt['FechaFin']);
													$s = ($date1)-($date2);
													$d = intval($s/86400);
													$s -= $d*86400;
													$h = intval($s/3600);
													$s -= $h*3600;
													$m = intval($s/60);
													$s -= $m*60;
													
													$dif= (($d*24)+$h).hrs." ".$m."min";
													$dif2= abs($d.$space);?>
	    <td><input name="dias" type="text" id="dias" size="6" maxlength="3" value="<?=$dif2?>" readonly/> d&iacute;as.</td>
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
	  <td ><input name="montoautori" type="text" id="montoautori" size="20" maxlength="15" value="<?=$fieldAnt[MontoPresupuestado]?>" readonly/>*<em>99999999,99</em></td>
	</tr>
	<tr><td></td>
	  <td class="tagForm">Monto Restante:</td>
	  <td><input name="montorestante" id="montorestante" type="text" size="20" maxlength="15" value="<?=$fieldAnt[MontoPresupuestado] - $total?>" readonly/></td>
	</tr>
	<tr><td></td></tr>
	<tr><td></td>
	   <td class="tagForm">Preparado por:</td>
	   <td><input name="prepor" id="prepor" type="text" size="60" value="<?=$fieldAnt['PreparadoPor']?>" readonly/></td>
	<tr>
	<tr><td></td>
	   <td class="tagForm">Aprobado por:</td>
	   <td><input name="nomempleado" id="nomempleado" type="text" size="60" value="<?=$fieldAnt['AprobadoPor']?>" readonly/></td>
	</tr>
	<tr><td></td>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td colspan="1">
			<input name="ult_usuario" type="text" id="ult_usuario" size="30" value="<?=$fieldAnt['UltimoUsuario']?>" readonly />
			<input name="ult_fecha" type="text" id="ult_fecha" size="22" value="<?=$fieldAnt['UltimaFecha']?>" readonly />		</td>
	</tr>
</table>
</div>
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
		   //VALIDACION DESCRIPCION
		   if (formulario.descripcion.value.length <2) {
	  		 alert("Escriba los datos correctos en el campo \"Descripción\".");
	   		 formulario.descripcion.focus();
	      return (false);
	      }
          var checkOK = "ABCDEFGHIJKLMNÑOPQRSTUVWXYZÁÉÍÓÚ" + "abcdefghijklmnñopqrstuvwxyzáéíóú" + " ._/";
	      var checkStr = formulario.descripcion.value;
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
	         alert("Escriba sólo letras en el campo \"Descripción\"."); 
	         formulario.descripcion.focus(); 
	         return (false); 
	       } 
	return (true); 
	} 
</SCRIPT>

