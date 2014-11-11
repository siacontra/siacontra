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
		<td class="titulo">Maestro de Partida| Ver Registro</td>
		<td align="right"><a class="cerrar" href="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />
<?php
include("fphp.php");
connect();
$sql="SELECT * FROM pv_partida WHERE cod_partida='".$_GET['registro']."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if($rows!=0) {
  $field=mysql_fetch_array($query);
  echo"
  <div style='width:700px' class='divFormCaption'>Datos de la Partida</div>
  <table width='700' class='tblForm'>
  
  <table width='700' class='tblForm'>
   <tr>
     <td class='tagForm'>Partida Presupuestaria N&deg;:</td>
	 <td><input name='codpartida' type='text' id='codpartida' size='20' maxlength='15' value='".$field['cod_partida']."' readonly/></td>
   </tr>
  </table>
  
  <table width='700' class='tblForm'>
   <tr>
     <td class='tagForm'>Tipo cuenta:</td>
	 <td colspan='4'><input name='tipocuenta' type='text' id='tipocuenta' size='10' maxlength='2' value='".$field['cod_tipocuenta']."' readonly /></td>
   </tr>
   <tr>
     <td class='tagForm'>Partida:</td>
	 <td colspan='4'><input name='partida1' type='text' id='partida1' size='10' maxlength='2' value='".$field['partida1']."' readonly </td>
   </tr>
   <tr>
     <td class='tagForm'>Generica:</td>
	 <td colspan='4'><input name='generica' type='text' id='generica' size='10' maxlength='2' value='".$field['generica']."' readonly </td>
   </tr>
   <tr>
     <td class='tagForm'>Especifica:</td>
	 <td colspan='4'><input name='especifica' type='text' id='especifica' size='10' maxlength='2' value='".$field['especifica']."' readonly </td>
   </tr>
   <tr>
     <td class='tagForm'>Sub-Especifica:</td>
	 <td colspan='4'><input name='subespecifica' type='text' id='subespecifica' size='10' maxlength='2' value='".$field['subespecifica']."' readonly> </td>
   </tr>  
	<tr>
	  <td align='right'>Denominaci&oacute;n:</td>
     <td width='350'><input name='denominacion' type='text' id='denominacion' size='75' maxlength='200' value='".htmlentities($field['denominacion'])."' readonly/></td>
	 <td width='65'>"; 
	         if($field['tipo']=="D"){
			 echo "<input name='opcion' type='radio' value='D' value='".$field['tipo']."' checked /> Detalle";
			 echo "<input name='opcion' type='radio' value='T' value='".$field['tipo']."'/> T&iacute;tulo";
		   }else{
		     echo "<input name='opcion' type='radio' value='D' value='".$field['tipo']."'/> Detalle";
			 echo "<input name='opcion' type='radio' value='T' value='".$field['tipo']."' checked /> T&iacute;tulo";
			 
		   }
			echo "
	 </td>
	 <td width='80'></td>
	</tr>
	</table>
	
	<table width='700' class='tblForm'>
	<tr>
	  <td class='tagForm'>Nivel:</td>
	  <td><select id='nivel'>
	       <option>$field[nivel]</option>
		  </select>
	  </td>
	</tr>
	<tr> 
	    <td class='tagForm'>Estado:</td>
	    <td>";
		   if($field['Estado']=="A"){
			 echo "<input name='status' type='radio' value='A' checked /> Activo";
			 echo "<input name='status' type='radio' value='I'/> Inactivo";
		   }else{
		     echo "<input name='status' type='radio' value='A'/> Activo";
		     echo "<input name='status' type='radio' value='I' checked /> Inactivo";
		   }
			echo "
		</td>
    </tr>
	<tr>
	  <td class='tagForm'>&Uacute;ltima Modif.:</td>
	  <td>
		  <input name='ult_usuario' type='text' id='ult_usuario' size='30' value='".$field['UltimoUsuario']."' readonly />
		  <input name='ult_fecha' type='text' id='ult_fecha' size='25' value='".$field['UltimaFecha']."' readonly />
	  </td>
	</tr>
	</table>
 
  ";
}
?>
</body>
</html>