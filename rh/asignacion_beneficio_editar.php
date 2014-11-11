<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Maestro Asignación de beneficio | Editar Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'asignacion_beneficio.php');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form id="frmentrada" name="frmentrada" action="asignacion_beneficio.php" method="POST" onsubmit="return verificarAsignacion(this, 'ACTUALIZAR');">
<?php
include("fphp.php");
connect();
echo "<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />";

if ($_POST['registro']=="") $_POST['registro']=$_GET['registro'];
if ($_POST['filtro']=="")  $_POST['filtro']=$_GET['filtro'];
$sql="SELECT * FROM rh_ayudamedicaespecifica WHERE codAyudaE='".$_POST['registro']."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
//$rows=mysql_num_rows($query);
$fieldE=mysql_fetch_array($query);


$sqlPer="SELECT CodPersona, Busqueda FROM mastpersonas WHERE CodPersona=".$fieldE['CodPerAprobar'];
$queryPer=mysql_query($sqlPer) or die ($sqlPer.mysql_error());
//$rows=mysql_num_rows($query);
$fieldPer=mysql_fetch_array($queryPer);

?>

<div style="width:900px" class="divFormCaption">Datos del Tipo de Cargo</div>
<table width="900" class="tblForm">
  <tr>
    <td class="tagForm">Codigo:</td>
    <td><input name="txt_codigo" type="text" id="txt_codigo" size="3" maxlength="3" readonly value="<?=$fieldE['numAyudaE'];?>" />
        <input name="hid_codigo" id="codAyudaE" type="hidden" value="<?=$fieldE['codAyudaE'];?>" /></td>
  </tr>
  <tr>
    <td class="tagForm">Descripci&oacute;n:</td>
    <td><input name="txt_descripcion" type="text" id="txt_descripcion" size="60" maxlength="30"  value="<?=$fieldE['decripcionAyudaE'];?>"/>*</td>
  </tr>	
  <tr>
    <td class="tagForm">Asignación general : </td>
    <td align="left">
    	<?php 
		$sql2 ='SELECT * FROM rh_ayudamedicaglobal';
		$query2=mysql_query($sql2) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query2);
		//$field=mysql_fetch_array($query);
		/*
		$sig = $field['CANT']+1;
		if(strlen($sig)==1){$codigo='0'.$sig;}
		else{$codigo = $sig;*/
	?>
    	<select id="sel_asignacionGeneral" name="sel_asignacionGeneral" style="width:25 px"> 
        <option value="0">..</option>        
        <?php
			while ($row = mysql_fetch_array($query2, MYSQL_ASSOC)) {
				echo "<option value='".$row['codAyudaG']."'>".$row["decripcionAyudaG"]."</option>";
    			//printf("ID: %s  Name: %s", $row["id"], $row["name"]);
			}

			echo "<script>document.getElementById('sel_asignacionGeneral').value=".$fieldE['codAyudaG']."</script>";
		?>
        </select>
    </td>
  </tr>
  <tr>
    <td class="tagForm">Limite :</td>
    <td><label for="txt_limite"></label>
      <input name="txt_limite" type="text" id="txt_limite" value="<?=number_format($fieldE['limiteAyudaE'],2,',','.');?>"  style="text-align:right" onkeypress="return(formatoMoneda(this,'.',',',event));" /> 
      Bs.F</td>
  </tr>
  <tr>
    <td class="tagForm">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="tagForm">Aprobar por :</td>
    <td><input name="codempleado" type="hidden" id="codempleado" value="<?=$fieldPer['CodPersona']?>" />
      <input name="nomempleado" id="nomempleado" type="text" size="60" readonly="readonly" value="<?=$fieldPer['Busqueda'];?>"/>
      <input name="bt_examinar" id="bt_examinar" type="button" value="..." onclick="cargarVentana(this.form, 'lista_empleados.php?limit=0&amp;campo=1', 'height=500, width=800, left=200, top=200, resizable=yes');" />
      * </td>
  </tr>
  <tr>
    <td class="tagForm">&Uacute;ltima Modif.:</td>
    <td>
      <input name="ult_usuario" type="text" id="ult_usuario" size="30" readonly value="<?=$fieldE['UltimoUsuario'];?>" />
      <input name="ult_fecha" type="text" id="ult_fecha" size="25" readonly value="<?=$fieldE['UltimaFecha'];?>" />
      </td>
  </tr>
  </table>
<center> 
<input type="submit" value="Guardar Registro" />
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onclick="cargarPagina(this.form, 'asignacion_beneficio.php');" />
</center>
</form>

<script>



function formatoMoneda(fld, milSep,decSep, e)
{
	/*
	ENTRADA: fld (NOMBRE DEL CAMPO DE TEXTO), milSep (separadores miles), decSep(separador de decimales) e (evento)
	SALIDA: 
	DESCRIPCIÓN: FUNCION QUE SE ENCARGA DE TRANFORMAR LOS NUMEROS INTRODUCISDOS EN FORMATO DE MILES Y DECIMALES
	PROGRAMADOR: ERNESTO RIVAS
	fecha: 01-10-2012
	*/
	
	/* */
	
    var sep = 0; 
    var key = ''; 
    var i = j = 0; 
    var len = len2 = 0; 
    var strCheck = '0123456789'; 
    var aux = aux2 = ''; 
    var whichCode = (window.Event) ? e.which : e.keyCode; 
   // alert(whichCode);
    //if (whichCode == 13) return true; // Enter 
    
    //key = String.fromCharCode(whichCode); // Get key value from key code
    //alert(whichCode);
    
    if(whichCode!=8) //PARA QUE PERMITA ACEPTAR LA TECHA <- (BORRAR)
    {
    	key = String.fromCharCode(whichCode); // Get key value from key code
    	//alert(strCheck.indexOf(key));
    	if (strCheck.indexOf(key) == -1) return false; // Not a valid key
    	len = fld.value.length;    	
   		// alert(len);
    }
    
    else len = fld.value.length-1; //PARA QUE PERMITA BORRAR
   // alert(len);
    for(i = 0; i < len; i++) 
     if ((fld.value.charAt(i) != '0') && (fld.value.charAt(i) != 44)) break; 
    aux = ''; 
    for(; i < len; i++) 
     if (strCheck.indexOf(fld.value.charAt(i))!=-1) aux += fld.value.charAt(i); 
    aux += key;
    len = aux.length;
    if (len == 0) fld.value = '0,00'; 
    if (len == 1) fld.value = '0'+ decSep + '0' + aux; 
    if (len == 2) fld.value = '0'+ decSep + aux; 
    if (len > 2) 
    { 
	     aux2 = ''; 
	     for (j = 0, i = len - 3; i >= 0; i--) 
	     { 
		      if (j == 3) 
		      { 
			       aux2 += milSep; 
			       j = 0; 
		      } 
		      aux2 += aux.charAt(i); 
		      j++; 
	     } 
	     fld.value = ''; 
	     len2 = aux2.length; 
	     for (i = len2 - 1; i >= 0; i--) 
	      	fld.value += aux2.charAt(i); 
	     fld.value += decSep + aux.substr(len - 2, len);
    } //decSep +
    return false;
}//----------------------------------------------------------------------------------------------------------------------------------

</script>


</body>
</html>
