<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include ("fphp.php");
include ("controlActivoFijo.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('03', $concepto);
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--<link href="css1.css" rel="stylesheet" type="text/css" />-->
<link href="css2.css" rel="stylesheet" type="text/css" />
<link href="../css/estilo.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="../css/prettyPhoto.css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" language="javascript" src="af_fscript.js"></script>
<script type="text/javascript" language="javascript" src="af_fscript_02.js"></script>
<script type="text/javascript" language="javascript" src="af_fscript2.js"></script>
<script type="text/javascript" src="../js/jquery-1.7.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery-ui-1.8.16.custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery.prettyPhoto.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/funciones.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/fscript.js" charset="utf-8"></script>
<style type="text/css">
<!--
UNKNOWN {FONT-SIZE: small}
#header {FONT-SIZE: 93%; BACKGROUND: url(imagenes/bg.gif) #dae0d2 repeat-x 50% bottom; FLOAT: left; WIDTH: 100%; LINE-HEIGHT: normal}
#header UL {PADDING-RIGHT: 10px; PADDING-LEFT: 10px; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 10px; LIST-STYLE-TYPE: none}
#header LI {
        PADDING-RIGHT: 0px; PADDING-LEFT: 9px; BACKGROUND: url(imagenes/left.gif) no-repeat left top; FLOAT: left; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 0px}
#header A {
        PADDING-RIGHT: 15px; DISPLAY: block; PADDING-LEFT: 6px; FONT-WEIGHT: bold; BACKGROUND: url(imagenes/right.gif) no-repeat right top; FLOAT: left; PADDING-BOTTOM: 4px; COLOR: #765; PADDING-TOP: 5px; TEXT-DECORATION: none}
#header A { FLOAT: none}
#header A:hover {  COLOR: #333 }
#header #current { BACKGROUND-IMAGE: url(imagenes/left_on.gif)}
#header #current A { BACKGROUND-IMAGE: url(imagenes/right_on.gif); PADDING-BOTTOM: 5px; COLOR: #333 }
-->
</style>
</head>
<body>
<div id="cajaModal"></div>
<!-- pretty -->
<span class="gallery clearfix"></span>
<?

list($organismo, $nroOrden, $secuencia, $nrosecuencia)=SPLIT('[-]',$_GET['registro']);
//// CONSULTA PRINCIPAL
$sa = "select * from lg_activofijo where CodOrganismo = '$organismo' and NroOrden = '$nroOrden' and Secuencia='$secuencia' and NroSecuencia='$nrosecuencia'";
$qa = mysql_query($sa) or die ($sa.mysql_error());
$ra = mysql_num_rows($qa); 

if($ra!='0')$fa=mysql_fetch_array($qa);
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Baja de Activos | Nueva Transacci&oacute;n</td>
		<td align="right"><a class="cerrar" href="<?=$regresar?>.php" onclick="window.close();">[cerrar]</a></td>
	</tr>
</table>
<hr width="100%" color="#333333" />
<form id="frmentrada" name="frmentrada" action="af_activosmenoresagregar.php?limit=0"  onsubmit="return guardarTransaccionBaja(this);">
<? echo"
       <input type='hidden' id='registro' name='registro' value='".$registro."'/>
	   <input type='hidden' id='fOrganismo' name='fOrganismo' value='".$fOrganismo."'/>
	   <input type='hidden' id='fDependencia' name='fDependencia' value='".$fDependencia."'/>
	   <input type='hidden' id='fContabilidad' name='fContabilidad' value='".$fContabilidad."'/>
	   <input type='hidden' id='fActivo' name='fActivo' value='".$fActivo."'/>
	   <input type='hidden' id='fPeriodo' name='fPeriodo' value='".$fPeriodo."'/>
	   <input type='hidden' id='fFecha' name='fFecha' value='".$fFecha."'/>
	   <input type='hidden' id='fEstado' name='fEstado' value='".$fEstado."'/>

";?>
<table width="908" align="center">
<tr>
  <td>
	<div id="header">
	<ul>
	<!-- CSS Tabs PESTA�AS OPCIONES -->
	<li><a onClick="document.getElementById('tab1').style.display='block'; 
                    document.getElementById('tab2').style.display='none';" href="#">Transacci&oacute;n</a></li>
	<li><a onClick="document.getElementById('tab1').style.display='none'; 
                    document.getElementById('tab2').style.display='block';" href="#">Historia</a></li> 
  
	</ul>
	</div>
  </td>
</tr>
</table>
<? echo" <input type='hidden' id='regresar' name='regresar' value='".$_GET['regresar']."' />
         <input type='hidden' id='activo' name='activo' value='' />";?>
<!-- ****************************************************** COMIENZO TAB1 ************************************************ -->
<div id="tab1" style="display: block;">
<div style="width:900px; height=15px;" class="divFormCaption">Informaci&oacute;n General</div>
<table class="tblForm" width="900">
<tr>
  <td width="109" height="5"></td>
</tr>
<tr>
   <td class="tagForm">Activo #:</td>
   <td width="360" class="gallery clearfix"><input type="text" id="nro_activo" name="nro_activo" size="14" readonly/><input type="text" name="descripcion" id="descripcion" size="50" readonly/>
       <input type="hidden" id="btActivo" name="btActivo" value="..." onclick="cargarVentanaLista(this.form, 'af_selectoractivos.php?limit=0&campo=2&ventana=insertarActivo','height=550, width=900, left=200, top=100, resizable=yes');"/> <a id="selector_activo" href="af_selectoractivos.php?filtrar=default&limit=0&campo=2&ventana=insertarActivo&iframe=true&width=77%&height=100%" rel="prettyPhoto[iframe1]">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;"/>
            </a></td>
   <td width="102" class="tagForm">Organismo:</td>
   <td width="309"><input type="hidden" name="codorganismo" id="codorganismo"/><input type="text" id="organismo" name="organismo" size="60" readonly/></td>
</tr>
<tr>
<input type="hidden" id="seldetalle3" />
<input type="hidden" id="candetalle3" />
<? echo"<input type='hidden' id='inform' name='inform' value=''/>
        <input type='hidden' id='monto' name='monto' value='$monto'/>";?>
  <td class="tagForm">Tipo Baja:</td>
  <td><select id="tipobaja" name="tipobaja" class="selectSma" onchange="CargarInformacion(this.form, this.id, 'insertarDatos_2')|ActivarTable(this.form, this.id);">
       <option value=""></option>
       <?
         $st = "select * from af_tipotransaccion"; //echo $st;
		 $qt = mysql_query($st) or die ($st.mysql_error());
		 $rt = mysql_num_rows($qt);
		 if($rt!=0) 
		 for($i=0; $i<$rt; $i++){
			 $ft= mysql_fetch_array($qt);
			 if($_GET['tipobaja']==$ft['TipoTransaccion'])echo" <option value='".$ft['TipoTransaccion']."' selected>".$ft['Descripcion']."</option>";
			 else echo" <option value='".$ft['TipoTransaccion']."'>".$ft['Descripcion']."</option>";		 
		 }
	   ?>
      </select></td>
  <td class="tagForm">Dependencia:</td>
  <td><input type="text" id="dpendencia" name="dpendencia" size="60" readonly/>
      <input type="hidden" id="coddependencia" name="coddependencia"/></td>
</tr>
<tr>
  <td class="tagForm">Fecha:</td>
  <td><input type="text" id="f_actual" name="f_actual" size="10" value="<?=date("d-m-Y");?>" maxlength="10"/> Nro. Factura:<input type="text" id="nrofactura" name="nrofactura"/></td>
  <td class="tagForm">Centro Costo:</td>
  <td><input type="hidden" id="codcentrocosto" name="codcentrocosto"/><input type="text" id="centrocosto" name="centrocosto" size="60" readonly/></td>
</tr>

<tr>
  <td class="tagForm"></td>
  <td><input type="checkbox" id="flagContabilizado" name="flagContabilizado"/>Contabilizado</td>
  <td class="tagForm">Responsable:</td>
  <td><input type="hidden" id="codresponsable" name="codresponsable"/><input type="text" id="responsable" name="responsable" size="60"/></td>
</tr>
<tr>
  <td height="5">
</tr>
<tr>
  <td class="tagForm">Voucher Baja:</td>
  <td><input type="text" name="periodoVoucher" id="periodoVoucher" size="10" disabled value="<?=date("Y-m");?>"/> <input type="text" id="codVoucher" name="codvoucher" size="10" disabled/></td>
  <td class="tagForm">Valor Activo:</td>
  <td><input type="text" id="valor_activo" name="valor_activo" size="15" style="text-align:right" readonly/> <b>BsF.</b></td>
</tr>
<!--<tr>
 <td class="tagForm">Valor Baja:</td>
 <td><input type="text" id="valorBaja" name="valorBaja" /></td>
 <td class="tagForm"></td>
 <td></td>
</tr>-->
</table>

<table class="tblForm" width="900">
<tr>
  <td width="154" height="5"></td>
</tr>
<!--<tr>
   <td class="tagForm">Situaci&oacute;n Activo:</td>
   <td>
       <select id="situacion_activo" name="situacion_activo" class="selectMed">
        
        <? $s_activo = "select * from af_situacionactivo";
           $q_activo = mysql_query($s_activo) or die ($s_activo.mysql_error());
           $r_activo = mysql_num_rows($q_activo);
          
           if($r_activo!='0'){
		    for($i=0;$i<$r_activo;$i++){
               $f_activo = mysql_fetch_array($q_activo);
			   if($f_activo['CodSituActivo']=='OP'){
                echo"<option value='".$f_activo['CodSituActivo']."' selected>".$f_activo['Descripcion']."</option>";
			   }else{
			     echo"<option value='".$f_activo['CodSituActivo']."'>".$f_activo['Descripcion']."</option>";
			   }	
            }
		   }
        ?>         
       </select></td> 
   <td class="tagForm" width="100">Organismo:</td>
   <td><select id="organismo" name="organismo" class="selectBig">
       <?
        $s_org = "select * from mastorganismos where CodOrganismo='".$_SESSION['ORGANISMO_ACTUAL']."'";
        $q_org = mysql_query($s_org) or die ($s_org.mysql_error());
        $r_org = mysql_num_rows($q_org);
        if($r_org!='0'){
            for($i=0;$i<$r_org;$i++){
              $f_org = mysql_fetch_array($q_org);
              echo"<option value='".$f_org['CodOrganismo']."'>".$f_org['Organismo']."</option>";
            }
			
        }
        
       ?>
       </select></td>
</tr>-->
<tr>
  <td class="tagForm">Concep. de Movimiento:</td>
   <td colspan="2">
       <select id="conceptoMovimiento" name="conceptoMovimiento" class="selectBig">
        
        <? $s_cm = "select * from af_tipomovimientos";
           $q_cm = mysql_query($s_cm) or die ($s_cm.mysql_error());
           $r_cm = mysql_num_rows($q_cm);
          
           if($r_cm!='0'){
		    for($i=0;$i<$r_cm;$i++){
               $f_cm = mysql_fetch_array($q_cm);
			   if(($f_cm['CodTipoMovimiento']=='51')and($f_cm['TipoMovimiento']=='DE')){
                echo"<option value='".$f_cm['CodTipoMovimiento']."' selected>".$f_cm['DescpMovimiento']."</option>";
			   }else{
			     echo"<option value='".$f_cm['CodTipoMovimiento']."'>".$f_cm['DescpMovimiento']."</option>";
			   }	
            }
		   }
        ?>         
       </select></td>  
</tr>
<tr>
   <td class="tagForm">C&oacute;digo Interno:</td>
   <td width="321"><input type="text" id="codigo_interno" name="codigo_interno" size="15" style="text-align:right"  maxlength="10" readonly/></td>
   <td class="tagForm">Categor&iacute;a:</td>
   <td><input type="text" id="categoria" name="categoria" size="60" readonly/><input type="hidden" name="codcategoria" id="codcategoria"/></td>
</tr>
<tr>
   <td class="tagForm">Estado:</td>
   <td><input type="text" value="Preparaci&oacute;n" id="pr" name="Preparacion" readonly size="15"/></td>
   <td class="tagForm">Ubicaci&oacute;n:</td>
 <td><input type="hidden" name="codubicacion" id="codubicacion" value="" disabled/>
       <input type="text" name="ubicacion" id="ubicacion" size="60" value="" readonly/></td>
</tr>
<tr>
 <td></td>
 <td></td>
 <td class="tagForm"></td>
 <td></td>
</tr>

<tr>
   <td class="tagForm">Aprobado Por:</td>
   <td><input type="text" id="aprobadopor" name="aprobadopor" size="60" disabled/></td>
</tr>
<tr>
 <td class="tagForm">Comentario:</td>
 <td colspan="3"><input type="text" id="comentario" name="comentario" size="100"/></td>
</tr>
<tr>
 <td height="6"></td>
</tr>
<? 
if((!$_GET)or(!$_POST)) $visible = 'style="visibility:hidden"';
else $visible=strtr($visible, "\"", "");

$s_usuario = "select 
				   mp.NomCompleto,
				   mp.CodPersona 
			  from 
				   usuarios u 
				   inner join mastpersonas mp on (mp.CodPersona = u.CodPersona)
			 where 
				   u.Usuario = '".$_SESSION['USUARIO_ACTUAL']."'";
$q_usuario = mysql_query($s_usuario) or die ($s_usuario.mysql_error());
$f_usuario = mysql_fetch_array($q_usuario);
echo"<input='hidden' id='cod_prepor' nanme='cod_prepor' value='".$f_usuario['CodPersona']."'/>";					 
?>
<tr><td align="center" colspan="5">Ultima Modif.:<input type="text" name="ultimo_usuario" value="" size="25" readonly/> <input type="text" name="ultima_fecha" value="" size="20" readonly/></td></tr>
<tr><td class="tagForm">Distribuci&oacute;n Contable</td></tr>
</table>
<table class="tblForm" width="900">
<tr>
<td>
   <table width="800" id="mostrar" name="mostrar" style="visibility:hidden" class="tblLista">
   <thead>
   <tr class="trListaHead">
        <th scope="col" width="15">#</th>
        <th scope="col" width="75">Cuenta</th>
        <th width="200" scope="col">Descripci&oacute;n</th>
        <th scope="col" width="50">Local</th>
   </tr>
   </thead>
   
   <tr><td colspan="4"><div id="scrool" style="display:none;overflow:scroll; width:850px; height:150px;">
   <div id="resultados" name="resultados" style="width:840px">
   </div></div>
   </td></tr>
   </table>
   
</td>
</tr>
</table>
</div>
<!-- ****************************************************** COMIENZO TAB2 ************************************************ -->
<div id="tab2" style="display: none;">
<!--<div style="width:900px; height=15px;" class="divFormCaption">Informaci&oacute;n Adicional</div>
<table class="tblForm" width="900">
<tr>
  <td width="137" class="tagForm"><u>Informaci&oacute;n del Activo</u></td>
  <td></td>
  <td width="33"></td>
  <td class="tagForm"><u>Informaci&oacute;n Documentaria</u></td>
</tr>
<tr><?
$scon2 = "select 
                ma.Descripcion as DescpMarcas
			from
			    lg_marcas ma
		   where
		        ma.CodMarca = '".$fa['CodMarca']."'";
$qcon2= mysql_query($scon2) or die ($scon2.mysql_error());
$fcon2= mysql_fetch_array($qcon2);
?>
  <td class="tagForm">Fabricante(Marca):</td>
  <td width="183"><input type="hidden" name="fabricante" id="fabricante" size="40" maxlength="30" value="<?=$fa['CodMarca'];?>"/><input type="text" name="fabricante2" id="fabricante2" size="40" value="<?=$fcon2['DescpMarcas'];?>"/></td>
  <td></td>
  <td width="162" class="tagForm">Proveedor:</td><?
                                     $s_proveedor = "select 
									                        p.CodProveedor as codProveedor,
															mp.NomCompleto as NombProveedor
									                   from 
													        mastproveedores p
															inner join mastpersonas mp on (p.CodProveedor = mp.CodPersona)
													   where
													        p.CodProveedor = '".$fa['CodProveedor']."'";
									 $q_proveedor = mysql_query($s_proveedor) or die ($s_proveedor.mysql_error());
									 $f_proveedor = mysql_fetch_array($q_proveedor);
                                     ?>
 <td width="361" colspan="2"><input type="text" id="proveedor" name="proveedor" size="8" value="<?=$f_proveedor['codProveedor'];?>" disabled/><input type="text" id="nomb_proveedor" name="nomb_proveedor" size="48" value="<?=$f_proveedor['NombProveedor'];?>" readonly/>
                  <input type="button" id="btProveedor" name="btProveedor" value="..." onclick="cargarVentanaLista(this.form, 'af_listaproveedor.php?limit=0&campo=1','height=500, width=800, left=200,top=100,resizable=yes');"/></td>
</tr>
<tr>
  <td class="tagForm">Modelo:</td>
  <td><input type="text" name="modelo" id="modelo" size="40" maxlength="20" value="<?=$fa['Modelo'];?>"/></td><td></td>
  <td class="tagForm">Factura:</td>
  <td colspan="2"><select id="factura" name="factura" style="width:70px;">
                  <option value=""></option>
                  <?
                  $s_facnumerodoc = "select * from ap_tipodocumento";
				  $q_facnumerodoc = mysql_query($s_facnumerodoc) or die ($s_facnumerodoc.mysql_error());
				  
				  while($f_facnumerodoc = mysql_fetch_array($q_facnumerodoc)){
				    echo"<option value='".$f_facnumerodoc['CodTipoDocumento']."'>".$f_facnumerodoc['Descripcion']."</option>";
				  }
				  ?>
                  </select>
                  <input type="text" id="num_factura" name="num_factura" size="30"/>
                  <input type="text" id="fecha_factura" name="fecha_factura" size="8" maxlength="10" value="00-00-0000"/></td>
</tr>
<tr>
  <td class="tagForm">N&uacute;mero de Serie:</td>
  <td><input type="text" name="nro_serie" id="nro_serie" size="40" maxlength="20" value="<?=$fa['NroSerie'];?>"/></td><td></td>
  <td class="tagForm">Orden Compra:</td>
  <? 
       list($a,$m,$d)= SPLIT('[-]',$fa['NumeroOrdenFecha']);$fechaOrdenCompra = $d.'-'.$m.'-'.$a;
  ?>
   <td><input type="text" id="orden_compra" name="orden_compra" size="25" value="<?=$fa['NroOrden']?>" maxlength="15"/><input type="text" id="fecha_ordencompra" name="fecha_ordencompra" size="8" maxlength="10" value="00-00-0000"/></td>
</tr>
<tr>
  <td class="tagForm">Color:</td>
  <td><select id="color" class="selectMed">
      <option value=""></option>
       <?
       $s_color = "select * from mastmiscelaneosdet where CodMaestro='COLOR'";
	   $q_color = mysql_query($s_color) or die ($s_color.mysql_error());
	   
	   while($f_color = mysql_fetch_array($q_color)){
		  if ($f_color['CodDetalle']==$fa['Color']) echo"<option value='".$f_color['CodDetalle']."' selected>".$f_color['Descripcion']."</option>";
		  else echo"<option value='".$f_color['CodDetalle']."'>".$f_color['Descripcion']."</option>";
	   }
	  ?>
      </select></td><td></td>
  <td class="tagForm">Gu&iacute;a Remisi&oacute;n #:</td>
  <td><input type="text" id="nro_guiaremision" name="nro_guiaremision" size="25" value="<?=$fa['NumeroGuia'];?>" maxlength="15"/><input type="text" id="fecha_guiaremision" name="fecha_guiaremision" size="8" maxlength="10" value="00-00-0000"/></td>
</tr>
<tr>
  <td class="tagForm">C&oacute;digo de Barras:</td>
  <td><input type="text" name="codigo_barras" id="codigo_barras" size="40" max="15" value=""/></td><td></td>
  <td class="tagForm"></td>
  <td></td>
</tr>
<tr>
  <td class="tagForm">Medida:</td>
  <td><input type="text" name="medida" id="medida" size="40" value=""/></td><td></td>
  <td class="tagForm"><u>Informaci&oacute;n Monetaria</u></td>
  <td></td>
</tr>
<tr>
  <td class="tagForm">Pa&iacute;s de Fabricaci&oacute;n:</td>
  <td><select id="pais_fabricacion" class="selectMed">
      <?
	     $spaises = "select * from mastpaises";
		 $qpaises = mysql_query($spaises) or die ($spaises.mysql_error());
		 $rpaises = mysql_num_rows($qpaises);
		 
		 for($i=0;$i<$rpaises;$i++){
		   $fpaises = mysql_fetch_array($qpaises);
		   echo"<option value='".$fpaises['CodPais']."'>".$fpaises['Pais']."</option>";
		 }
		 
	   ?>
      </select></td>
  <td></td>
  <td class="tagForm">Monto Local:</td>
  <td><input type="text" id="monto_local" name="monto_local" value="0,00" style="text-align:right" onchange="cambioFormatoCantidad(this.id,this.value);"/>Bs.F</td>
</tr>
<tr>
  <td class="tagForm">A&ntilde;o de Fabricaci&oacute;n:</td>
  <td><input type="text" id="ano_fabricacion" name="ano_fabricacion"  size="8" style="text-align: right;"/></td>
</tr>
<tr>
 <td class="tagForm"></td>
 <td></td>
</tr>
<tr>
 <td class="tagForm">Fecha de Ingreso:</td>
 <td><input type="text" name="fecha_ingreso" id="fecha_ingreso" size="8" onclick="crearPeriodo2(this.form, this.id);" value="<?=date("d-m-Y");?>" readonly/></td>
</tr>
<tr>
 <td class="tagForm">Periodo Registro:</td>
 <td><input type="text" name="periodo_registro" id="periodo_registro" size="8" value="<?=date("Y-m");?>" readonly/></td>
</tr>
</table>-->
</div>
<!-- ****************************************************** FIN TAB ****************************************************** -->
<center><input type="submit" name="btGuardar" id="btGuardar" value="Guardar Registro"/><input type="button" name="btCancelar" id="btCancelar" value="Cancelar" onclick="cargarPagina(this.form,'<?=$regresar?>.php?limit=0');"/></center>
</form>
<!-- ****************************************************** ////////// ************************************************ -->
<!--<form name="frmdetalles" id="frmdetalles">
<input type="hidden" id="seldetalle" />
<input type="hidden" id="candetalle" />

<table width="800" class="tblBotones">
 <tr>
	<td align="right">
    	<input type="button" class="btLista" id="btInsertar" value="Insertar" onclick="insertarLineaTipoTransaccion();" />
        <input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="quitarLinea(document.getElementById('seldetalle').value);" />
	</td>
 </tr>
</table>

<table align="center" cellpadding="0" cellspacing="0"><tr><td valign="top" style="height:100px; width:800px;">
<table align="center" width="400px"><tr><td align="center"><div style="overflow:scroll; height:200px; width:800px;">
<table width="900px" class="tblLista">
    <tr class="trListaHead">
        <th scope="col">Categor&iacute;a</th>
        <th scope="col" width="200">Contabilidad</th>
        <th width="15"> # </th>
        <th width="100">Descripci&oacute;n</th>
        <th>Cuenta</th>
        <th>Signo</th>
        <th>Campo Moneda Local</th>
    </tr>
                
    <tbody id="listaDetalles">
    
    </tbody>
</table>
</div></td></tr></table>
</td></tr></table>
</form>-->