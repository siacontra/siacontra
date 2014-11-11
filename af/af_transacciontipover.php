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
<script type="text/javascript" language="javascript" src="af_fscript.js"></script>
<script type="text/javascript" language="javascript" src="af_fscript2.js"></script>
<script type="text/javascript" language="javascript" src="fscript.js"></script>
</head>

<body>
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<table width="100%" cellspacing="0" cellpadding="0">
 <tr>
  <td class="titulo">Maestro Tipo de Transacci&oacute;n | Ver Registro</td>
  <td align="right">
   <a class="cerrar" href="" onclick="window.close();">[cerrar]</a>
  </td>
 </tr>
</table>
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<hr width="100%" color="#333333" />
<?
 /// Consulto datos de carga
 $sc = "select * from af_tipotransaccion where TipoTransaccion='".$_GET['registro']."'"; //echo $sc;
 $qc =  mysql_query($sc) or die ($sc.mysql_error());
 $rc = mysql_num_rows($qc);
 
 if($rc!=0) $fc = mysql_fetch_array($qc); 
 

?>
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<form name="fentrada" id="fentrada" >
<?php echo "<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />"; ?>

<div style="width:800px" class="divFormCaption">Datos</div>
<table width="800" class="tblForm">
  <tr>
    <td width="144" class="tagForm">Transacci&oacute;n C&oacute;digo:</td>
    <td width="276"><input type="text" name="cod_trans"  id="cod_trans" size="4" maxlength="3" value="<?=$fc['TipoTransaccion'];?>" readonly/>*</td>
    <td width="134" class="tagForm">Tipo de Transacci&oacute;n:</td>
    <td width="226"><? if($fc['FlagAltaBaja']=='A'){?>
                        <input type="radio" id="Alta" name="Alta" checked onclick="this.checked=true"/>Alta <input type="radio" id="Baja" name="Baja" onclick="this.checked=false"/>Baja
                    <? }else{?>
                        <input type="radio" id="Alta" name="Alta" onclick="this.checked=false"/>Alta <input type="radio" id="Baja" name="Baja" checked onclick="this.checked=true"/>Baja
                    <? }?></td>
  </tr>
  <tr>
    <td class="tagForm">Descripci&oacute;n:</td>
    <td><input type="text" name="descp_tipotrans"  id="descp_tipotrans" size="60" maxlength="100" value="<?=$fc['Descripcion'];?>" readonly/>*</td>
    <td colspan="2">
	<?
     if($fc['TransaccionesdelSistemaFlag']=='S'){
	 ?><input type="checkbox" id="flagTranSistema" name="flagTranSistema" checked onclick="this.checked=true"/>
     <? }else{ ?><input type="checkbox" id="flagTranSistema" name="flagTranSistema" disabled/><? }?>Transacci&oacute;n del Sistema</td>
  </tr>
  <tr>
   <td class="tagForm">Tipo de Voucher:</td>
   <td><select id="tipo_voucher" name="tipo_voucher" class="selectMed" disabled>
    <?
       $svoucher = "select * from ac_voucher";
	   $qvoucher = mysql_query($svoucher) or die ($svoucher.mysql_error());
	   $rvoucher = mysql_num_rows($qvoucher);
	   if($rvoucher!=0){
		  for($i=0; $i<$rvoucher; $i++){
		     $fvoucher = mysql_fetch_array($qvoucher);
			 if($fvoucher['CodVoucher']==$fc['TipoVoucher'])echo"<option value='".$fvoucher['CodVoucher']."' selected>".$fvoucher['CodVoucher']."-".$fvoucher['Descripcion']."</option>";
			 echo"<option value='".$fvoucher['CodVoucher']."'>".$fvoucher['CodVoucher']."-".$fvoucher['Descripcion']."</option>";
		  }
		}
	   
	   ?>
       </select></td>
  </tr> 
   <tr>
    <td class="tagForm">Estado:</td>
 <td><? if($fc['Estado']=='A'){ ?>
        <input type="radio" name="radio1" checked onclick="this.checked=true"/>Activo<input type="radio" name="radio2" onclick="this.checked=false"/>Inactivo
     <? }else{?>
        <input type="radio" name="radio1"  onclick="this.checked=false"/>Activo<input type="radio" name="radio2"  checked onclick="this.checked=true"/>Inactivo
     <? }?>
     </td>
  </tr>
   
  <tr>
   <td height="5"></td>
  </tr>
  <tr>
   <td colspan="4" align="center">Ultima Modif.: <input type="text" id="usuario" name="usuario" size="25" value="<?=$fc['UltimoUsuario'];?>" readonly/><input type="text" id="ultimafecha" name="ultimafecha" size="20" readonly value="<?=$fc['UltimaFechaModif'];?>"/></td>
  </tr>
</table>
  <center>
    <input name="guardar" type="submit" id="guardar" value="Guardar Registro" disabled/>
    <input name="cancelar" type="button" id="cancelar" value="Cancelar" onClick="cargarPagina(this.form, 'af_transaccionestipotransaccion.php');" disabled/>
  </center><br />
</form>
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<form name="frmdetalles" id="frmdetalles">
<input type="hidden" id="seldetalle" />
<input type="hidden" id="candetalle" />

<table width="800" class="tblBotones">
 <tr>
	<td align="right">
    	<input type="button" class="btLista" id="btInsertar" value="Insertar" onclick="insertarLineaTipoTransaccion();" disabled/>
        <input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="quitarLinea(document.getElementById('seldetalle').value);" disabled/>
	</td>
 </tr>
</table>

<table align="center" cellpadding="0" cellspacing="0"><tr><td valign="top" style="height:100px; width:800px;">
<table align="center" width="400px"><tr><td align="center"><div style="overflow:scroll; height:200px; width:800px;">
<table width="900px" class="tblLista">
<thead>
    <tr class="trListaHead">
        <th scope="col" width="100">Categor&iacute;a</th>
        <th scope="col" width="80">Contabilidad</th>
        <th width="15"> # </th>
        <th width="200">Descripci&oacute;n</th>
        <th>Cuenta</th>
        <th scope="col" width="10">Signo</th>
    </tr>
 </thead>               
    <tbody id="listaDetalles">
    <?
       $scon = "select * from af_tipotranscuenta where TipoTransaccion='".$_GET['registro']."'"; //echo $scon;
	   $qcon = mysql_query($scon) or die ($scon.mysql_error());
	   $rcon = mysql_num_rows($qcon); //echo $rcon;
	   
	   if($rcon!=0)
	   for($i=0; $i<$rcon; $i++){
		   $fcon = mysql_fetch_array($qcon);
	   echo"<tr class='trListaBody'>
	         <td>";
			   $scat = "select * from af_categoriadeprec where CodCategoria='".$fcon['Categoria']."'"; //echo $scat;
			   $qcat = mysql_query($scat) or die ($scat.mysql_error());
			   $rcat = mysql_num_rows($qcat);
			   if($rcat!=0)$fcat = mysql_fetch_array($qcat);
			      echo"<select class='selectMed'>
			             <option>".$fcat['CodCategoria']."-".$fcat['DescripcionLocal']."</option>
			           </select></td>
			 <td>";
			     $scont = "select * from ac_contabilidades where CodContabilidad='".$fcon['Contabilidad']."'";
				 $qcont = mysql_query($scont) or die ($scont.mysql_error);
				 $rcont = mysql_num_rows($qcont);
				 if($rcont!=0)$fcont=mysql_fetch_array($qcont);
			        echo"<select>
			             <option>".$fcont['Descripcion']."</option>
			           </select></td>";
			 echo"</td> 
			 <td align='center'>".$fcon['Secuencia']."</td> 
			 <td width='300'>".$fcon['Descripcion']."</td> 
			 <td align='center'>".$fcon['CuentaContable']."</td> 
			 <td><select id='signo'>";
			  if($fcon['SignoFlag']=='-') echo"<option>".$fcon['SignoFlag']."</option>";
		      else echo"<option>+</option>
			  </select> </td>      
	       </tr>";
	   }
	?>
    </tbody>
</table>
</div></td></tr></table>
</td></tr></table>
</form>
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<div style="width:700px" class="divMsj">Campos Obligatorios *</div>
</body>
</html>
