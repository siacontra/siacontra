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
<script type="text/javascript" src="../js/funciones.js" charset="utf-8"></script>
</head>

<body>
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<table width="100%" cellspacing="0" cellpadding="0">
 <tr>
  <td class="titulo">Maestro Tipo de Transacci&oacute;n | Editar Registro</td>
  <td align="right">
   <a class="cerrar" href="framemain.php">[cerrar]</a>
  </td>
 </tr>
</table>
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<hr width="100%" color="#333333" />
<?
 /// Consulto datos de carga
 $sc = "select * from af_tipotransaccion where TipoTransaccion='".$_POST['registro']."'"; //echo $sc;
 $qc =  mysql_query($sc) or die ($sc.mysql_error());
 $rc = mysql_num_rows($qc);
 
 if($rc!=0) $fc = mysql_fetch_array($qc); 
 

?>



<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<form name="fentrada" id="fentrada" action="af_transacciontipoeditar.php" method="POST" onsubmit="return  EditarTipoTransacciones(this);">
<?php echo "<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />"; ?>

<div style="width:800px" class="divFormCaption">Datos</div>
<table width="800" class="tblForm">
  <tr>
    <td width="144" class="tagForm">Transacci&oacute;n C&oacute;digo:</td>
    <td width="276"><input type="text" name="cod_trans"  id="cod_trans" size="4" maxlength="3" value="<?=$fc['TipoTransaccion'];?>" readonly/>*</td>
    <td width="134" class="tagForm">Tipo de Transacci&oacute;n:</td>
    <td width="226"><input type="hidden" id="tipo_transa" name="tipo_trans" value="A"/>
     <? if($fc['FlagAltaBaja']=='A'){?>
          <input type="radio" id="Alta" name="Alta" checked  onclick="tipoTransaccion(this.form);"/>Alta <input type="radio" id="Baja" name="Baja" onclick="tipoTransaccion(this.form);"/>Baja
     <? }else{?>
          <input type="radio" id="Alta" name="Alta" onclick="tipoTransaccion(this.form);"/>Alta <input type="radio" id="Baja" name="Baja" checked onclick="this.checked=true"/>Baja
                    <? }?></td>
  </tr>
  <tr>
    <td class="tagForm">Descripci&oacute;n:</td>
    <td><input type="text" name="descp_tipotrans"  id="descp_tipotrans" size="60" maxlength="100" value="<?=$fc['Descripcion'];?>"/>*</td>
    <td colspan="2">
	<? if($fc['TransaccionesdelSistemaFlag']=='S'){?><input type="checkbox" id="flagTranSistema" name="flagTranSistema" checked/>
    <? }else{ ?><input type="checkbox" id="flagTranSistema" name="flagTranSistema"/><? }?> Transacci&oacute;n del Sistema</td>
  </tr>
  <tr>
   <td class="tagForm">Tipo de Voucher:</td>
   <td><select id="tipo_voucher" name="tipo_voucher" class="selectMed">
       <option value=""></option>
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
 <td><input type="hidden" id="radioEstado" name="radioEstado" value="A"/> 
     <input type="radio" name="radio1" checked="checked" onclick="estadosPosee(this.form);"/>Activo<input type="radio" name="radio2" onclick="estadosPosee(this.form);"/>Inactivo</td>
  </tr>
   
  <tr>
   <td height="5"></td>
  </tr>
  <tr>
   <td colspan="4" align="center">Ultima Modif.: <input type="text" id="usuario" name="usuario" size="25" value="<?=$fc['UltimoUsuario'];?>" readonly/><input type="text" id="ultimafecha" name="ultimafecha" size="20" readonly value="<?=$fc['UltimaFechaModif'];?>"/></td>
  </tr>
</table>
  <center>
    <input name="guardar" type="submit" id="guardar" value="Guardar Registro"/>
    <input name="cancelar" type="button" id="cancelar" value="Cancelar" onClick="cargarPagina(this.form, 'af_transaccionestipotransaccion.php');" />
  </center><br />
</form>
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<form name="frmdetalles" id="frmdetalles">
<input type="hidden" id="seldetalle" />
<input type="hidden" id="candetalle" />
<input type="hidden" id="nrodetalle"/>
<input type="hidden" id="selector"/>
<table width="800" class="tblBotones">
 <tr>
 
	<td align="right">
    <input type="button" id="btCuenta" name="btCuenta" onclick="cargarVentanaLista02(this.form,'listado_cuentas_contables.php?limit=0&campo=5','height=500, width=800, left=200, top=100, resizable=yes');" value="Seleccionar Cuenta"/>
    	<input type="button" class="btLista" id="btInsertar" value="Insertar" onclick="insertarLineaTipoTransaccion();" />
        <input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="quitarLinea(document.getElementById('seldetalle').value);" />
	</td>
 </tr>
</table>

<table align="center" cellpadding="0" cellspacing="0"><tr><td valign="top" style="height:100px; width:800px;">
<table align="center" width="400px"><tr><td align="center"><div style="overflow:scroll; height:200px; width:800px;">
<table width="900px" class="tblLista">
<thead>
    <tr class="trListaHead">
        <th width="50">Categor&iacute;a</th>
        <th width="50">Contabilidad</th>
        <th width="15"> # </th>
        <th width="100">Descripci&oacute;n</th>
        <th>Cuenta</th>
        <th>Signo</th>
    </tr>
 </thead>               
    <tbody id="listaDetalles">
    <?
       $scon = "select * from af_tipotranscuenta where TipoTransaccion='".$_POST['registro']."' order by Contabilidad"; //echo $scon;
	   $qcon = mysql_query($scon) or die ($scon.mysql_error());
	   $rcon = mysql_num_rows($qcon); //echo $rcon;
	   if($rcon!=0)
	   for($i=0; $i<$rcon; $i++){
		   $seldetalle = 1 + $seldetalle;
		   $fcon = mysql_fetch_array($qcon); ?>
	   <tr class="trListaBody" onclick="mClk(this, 'seldetalle');" id="<?=$seldetalle;?>">
	         <td>
             <?
			   $contador = $contador + 1;
			   $valorObtengo = $contador;
			   echo "<input type='hidden' id='contador' name='contador' value='$contador'/>";
			   $scat = "select * from af_categoriadeprec"; //echo $scat;
			   $qcat = mysql_query($scat) or die ($scat.mysql_error());
			   $rcat = mysql_num_rows($qcat);
			   if($rcat!=0)
			      echo"<select id='select2' name='select2' class='selectSma'>";
				  for($b=0; $b<$rcat; $b++){
				     $fcat = mysql_fetch_array($qcat);
					 if($fcon['Categoria']==$fcat['CodCategoria'])
			           echo"<option value='".$fcat['CodCategoria']."' selected>".$fcat['CodCategoria']." - ".$fcat['DescripcionLocal']."</option>";
					 else
					   echo"<option value='".$fcat['CodCategoria']."'>".$fcat['CodCategoria']." - ".$fcat['DescripcionLocal']."</option>";
				  }
			           echo"</select></td>
			 <td>";
			     $scont = "select CodContabilidad, Descripcion from ac_contabilidades";
				 $qcont = mysql_query($scont) or die ($scont.mysql_error);
				 $rcont = mysql_num_rows($qcont);
				 if($rcont!=0)
				   echo"<select id='select1' name='select1' class='selectSma'>";
				   for($a=0; $a<$rcont; $a++){
					   $fcont=mysql_fetch_array($qcont);
			           if($fcont['CodContabilidad']==$fcon['Contabilidad'])
					      echo"<option value='".$fcont['CodContabilidad']."' selected>".$fcont['Descripcion']."</option>";
					   else 
					      echo"<option value='".$fcont['CodContabilidad']."'>".$fcont['Descripcion']."</option>";
				   }
			        echo"</select></td>";?> 
			 <td align='center'><input type="text" id="secuencia" name="secuencia" size="3" style="text-align:center" value="<?=$fcon['Secuencia']?>" readonly/></td> 
			 <td width='100'><input type="text" id="descripcion" name="descripcion" value="<?=$fcon['Descripcion']?>" size="100"/></td> 
			 <td align='center'><input type="text" name="cuenta" id="cuenta_<?=$contador;?>" size="50" value="<?=$fcon['CuentaContable']?>" onclick="asumoInsert(this.id);"/></td>
			  <? if($fcon['SignoFlag']=='-'){?><td align="center"><select id="select3" name="select3"><option value="<?=$fcon['SignoFlag'];?>" selected>-</option>
              <option value="+">+</option></select></td>
		      <? }else{?> <td align="center"><select id="select3" name="select3"><option value="+" selected>+</option>
              <option value="-">-</option></select></td> <? }?>      
	       </tr>
	   <? }	?>
    </tbody>
    <input type="hidden" id="valorObtengo" value="<?=$valorObtengo;?>"/>
</table>
</div></td></tr></table>
</td></tr></table>
</form>
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<div style="width:700px" class="divMsj">Campos Obligatorios *</div>
</body>
</html>
