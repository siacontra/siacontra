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
<script type="text/javascript" src="../js/funciones.js" charset="utf-8"></script>
</head>

<body>
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<table width="100%" cellspacing="0" cellpadding="0">
 <tr>
  <td class="titulo">Maestro Tipo de Veh&iacute;culos | Ver Registro</td>
  <td align="right">
   <a class="cerrar" href="" onclick="window.close()">[cerrar]</a>
  </td>
 </tr>
</table>
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<hr width="100%" color="#333333" />

<?
 $sql="SELECT * FROM af_tipovehiculo WHERE CodTipoVehiculo='".$_GET['registro']."'";
 $qry=mysql_query($sql) or die ($sql.mysql_error());
 $field=mysql_fetch_array($qry);
?>

<form name="fentrada" id="fentrada">
<?php echo "<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />"; ?>

<div style="width:700px" class="divFormCaption">Datos</div>
<table width="700" class="tblForm">
  <tr>
    <td width="194" class="tagForm">Tipo Veh&iacute;culo:</td>
    <td width="494"><input type="text" name="cod_tvehiculo"  id="cod_tvehiculo" size="4" maxlength="4" value="<?=$field['CodTipoVehiculo']?>" readonly/></td>
  </tr>
  <tr>
    <td class="tagForm">Descripci&oacute;n:</td>
    <td><input type="text" name="descp_tvehiculo"  id="descp_tvehiculo" size="60" maxlength="100" value="<?=$field['Descripcion']?>" readonly/></td>
  </tr>
   <tr>
	    <td class='tagForm'>Estado:</td>
	    <td>
          <?
		   if($field['Estado']=='A'){?>
		   <input name='status_tvehiculo' type='radio' value='A' checked="checked" onclick="this.checked"/> Activo
		   <input name='status_tvehiculo' type='radio' value='I' disabled /> Inactivo
          <? }else{?>
            <input name='status_tvehiculo' type='radio' value='A' disabled/> Activo
		   <input name='status_tvehiculo' type='radio' value='I'  checked="checked" onclick="this.checked" /> Inactivo
         <? } ?>
	   </td>
  </tr>
  <tr>
   <td height="5"></td>
  </tr>
  <tr>
  <? 
      if($field['UltimaFechaModif']!='0000-00-00 00:00:00'){
	      $ult_fecha=$field['UltimaFechaModif']; $ult_usuario=$field['UltimoUsuario'];
	  }else{
	      $ult_fecha=''; $ult_usuario='';
	  }
     echo"
	<td class='tagForm'>&Uacute;ltima Modif.:</td>
	<td>
	  <input type='text' name='ult_usuario'  id='ult_usuario' size='30' value='$ult_usuario'  readonly />
	  <input type='text' name='ult_fecha'  id='ult_fecha' size='20' value='$ult_fecha' style='text-align:right' readonly />
	</td>";
	?>
  </tr>
</table>
</form>
</body>
</html>

