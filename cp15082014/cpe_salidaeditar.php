<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('03', $concepto);
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" language="javascript" src="cp_script.js"></script>
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
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Documentos de Salida | Editar Registro</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />
<?
 list($cod_tipodocumento, $cod_documentocompleto)= SPLIT('[|]', $_POST['registro']);
 
 $year=date("Y");
 $scon="select * from cp_documentoextsalida where Cod_DocumentoCompleto='$cod_documentocompleto' and Periodo='$year' ";
 $qcon=mysql_query($scon) or die ($scon.mysql_error()); //echo $scon;
 $rcon=mysql_num_rows($qcon);
 $fcon=mysql_fetch_array($qcon);
?>

<form name="frmentrada" id="frmentrada" action="cpe_salidalista.php?limit=0&fEstado=<?=$fEstado?>&fElaboradoPor=<?=$fElaboradoPor?>" method="post"  onsubmit="return guardarSalidaExtEditar(this);" >
<div style="width:895px; height:15px" class="divFormCaption">Datos del Documento</div>
<table class="tblForm" width="895px" border="0">
  <tr>
    <td height="5"></td>
  </tr>
  <tr>
    <td width="125" class="tagForm">Tipo Documento:</td>
    <td width="321">
	<? 
	  echo"<input type='hidden' id='fElaboradoPor' name='fElaboradoPor' value='".$fElaboradoPor."'/>";
	  echo"<input type='hidden' id='fEstado' name='fEstado' value='".$fEstado."'/>";
	  
	  echo"<input type='hidden' id='regresar' name='regresar' value='".$regresar."'/>";
	  $sql="SELECT * FROM cp_tipocorrespondencia WHERE FlagUsoExterno='1' AND Cod_TipoDocumento='".$fcon['Cod_TipoDocumento']."'";
	  $qry=mysql_query($sql) or die ($sql.mysql_error());
	  $row=mysql_num_rows($qry);?>
        <select name="t_documento" id="t_documento" class="selectMed">
          <?
      for($i; $i<$row; $i++){
	    $field=mysql_fetch_array($qry);?>
          <option value="<?=$field['Cod_TipoDocumento'];?>">
          <?=$field['Descripcion'];?>
          </option>
          <? }?>
        </select>
      *</td>
    <td width="104" class="tagForm">Fecha Documento:</td>
    <? list($a, $m, $d)=SPLIT( '[/.-]', $fcon['FechaRegistro']); $f_registro=$d.'-'.$m.'-'.$a;?>
    <td width="325"><input type="text" id="fecha_documento" name="fecha_documento" size="10" value="<?=$f_registro;?>" style="text-align:right" readonly/>
      *(dd-mm-aaaa)</td>
  </tr>
  <tr style="border-style:double">
    <td class="tagForm">Nro. Documento:</td>
    <td><input type="text" id="n_documento" name="n_documento" size="20" value="<?=$fcon['Cod_DocumentoCompleto']?>" style="text-align:right" readonly/>*
        <input type="hidden" id="cod_documento" name="cod_documento" value="<?=$fcon['Cod_Documento']?>"/></td>
    <td class="tagForm">Plazo de Atenci&oacute;n:</td>
    <td><input type="text" id="plazo" name="plazo" size="5" maxlength="3" style="text-align:right" value="<?=$fcon['PlazoAtencion']?>"/> d&iacute;a(s)</td>
    <!--<td class="tagForm">Fecha Recibido:</td>
 <td><input type="text" id="fecha_recibido" name="fecha_recibido" size="10" maxlength="10" value="<?=$fecha;?>"/>*(dd-mm-aaaa)</td>
</tr>-->
  </tr>
  <tr>
    <td class="tagForm">Asunto:</td>
    <td colspan="1"><input type="text" id="asunto" name="asunto" size="60" value="<?=$fcon['Asunto']?>"/>
      *</td>
  </tr>
  <tr>
    <td class="tagForm">Descripci&oacute;n:</td>
    <td colspan="3"><textarea name="descrip" id="descrip" rows="2" cols="80"><?=$fcon[Descripcion]?>
</textarea></td>
  </tr>
  <tr>
    <td height="5"></td>
  </tr>
  <tr>
    <td colspan="4"><div class="cellText" align="center"><b>Remitente</b></div></td>
    <!--<td colspan="4"><div class="divBorder" style="border-color:#999999;"></div></td>-->
  </tr>
  <tr>
    <td height="5"></td>
  </tr>
  <tr>
    <td class="tagForm">Organismo:</td>
    <td colspan="3"><select id="organismo" name="organismo" class="selectBig" onchange="getOrganismoInterno(this.id, 'dep_interna');" disabled>
      <option value=""></option>
      <? getOrganismos(0, $_SESSION['ORGANISMO_ACTUAL']); ?>
    </select>
      *</td>
  </tr>
  <tr>
   <?
     $sa = "select 
	              mp.NomCompleto as Representante,
				  rp.DescripCargo as Cargo
			  from
			      mastpersonas mp,
				  rh_puestos rp
			 where
			      mp.CodPersona = '".$fcon['Remitente']."'  and
				  rp.CodCargo =  '".$fcon['Cargo']."' ";
	 $qa = mysql_query($sa) or die ($sa.mysql_error());
	 $fa = mysql_fetch_array($qa);
	?>
    <td class="tagForm" colspan="">Dependencia:</td>
    <td colspan="3"><select id="dep_interna" name="dep_interna" class="selectBig" disabled>
      <option value=""></option>
      <? getDependencias($fcon['Cod_Dependencia'], $_SESSION['ORGANISMO_ACTUAL'], 0); ?>
    </select>
      *</td>
  </tr>
  <tr>
    <td class="tagForm">Representante:</td>
    <td><input type="text" id="destinatario_int" name="destinatario_int" size="68" value="<?=htmlentities($fa['Representante'])?>" readonly/></td>
    <td class="tagForm">Cargo:</td>
    <td><input type="text" id="cargodestinatario_int" name="cargodestinatario_int" size="68" value="<?=$fa['Cargo']?>" readonly/>
        <input type="hidden" id="codigo_interno" name="codigo_interno" value="<?=$f['CodInterno']?>"/>
        <input type="hidden" id="codigo_persona" name="codigo_persona" value="<?=$f['Cod_Remitente']?>"/>
        <input type="hidden" id="codigo_cargo" name="codigo_cargo" value="<?=$f['Cod_CargoRemitente']?>"/></td>
  </tr>
  <tr>
    <td height="5"></td>
  </tr>
  <tr>
    <td colspan="4"><table align="center" width="400">
      <tr>
        <td width="92" class="tagForm">Ultima Modif.:</td>
        <td width="296" colspan="2"><input type="text" id="ultimousuario" size="20" readonly="readonly"/>
              <input type="text" id="ultimafecha" size="20" readonly="readonly"/></td>
      </tr>
    </table></td>
  </tr>
</table>
<center>
    <input name="guardar" type="submit" id="guardar" value="Guardar Registro"/>
    <input name="cancelar" type="button" id="cancelar" value="Cancelar" onClick="cargarPagina(this.form,'<?=$regresar?>.php?limit=0&fEstado=<?=$fEstado?>&fElaboradoPor=<?=$fElaboradoPor?>');" />
</center> 
</form><br/>
<!-- @@@@@@@@@@@@@@@@@@@@@@@@ LISTA A MOSTRAR @@@@@@@@@@@@@@@@@@@@@@@@ -->
<form name="frmdetalles" id="frmdetalles">
<input type="hidden" id="seldetalle"/>
<table align="center" cellpadding="0" cellspacing="0">
<tr>
 <td colspan="4"><div class="cellText" align="center"><b>Destinatario(s)</b></div></td>
</tr>
<tr>
  <td colspan="4">
  <table width="800px" class="tblBotones">
   <tr>
 	<td align="right">
     <input type="button" class="btLista" id="btInsertarEmpleado" value="Ins. Org." onclick="cargarVentana(this.form,'lista_organismosext.php?limit=0&ventana=insertarDestinatarioOrgExt&tabla=item', 'height=600, width=800, left=50, top=50, resizable=yes');" />
    <input type="button" class="btLista" id="btInsertarDependencia" value="Ins. Dep." onclick="cargarVentana(this.form,'lista_dependenciasext.php?limit=0&ventana=insertarDestinatarioDepExt&tabla=item', 'height=600, width=800, left=50, top=50, resizable=yes');" />
     <input type="button" class="btLista" id="btInsertarParticular" value="Ins. Par." onclick="cargarVentana(this.form,'lista_particulares.php?limit=0&ventana=insertarDestinatarioParticularExt&tabla=item', 'height=600, width=800, left=50, top=50, resizable=yes');" />
    <input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="quitarLineaDestinatario(document.getElementById('seldetalle').value);" />
	</td>
  </tr>
  </table>
  </td>
</tr>
<tr>
  <td valign="top" style="height:150px; width:800px;">
    <table align="center" width="800px">
    <tr>
      <td align="center"><div style="overflow:scroll; height:180px; width:800px;">
      <table width="800px" class="tblLista">
       <tr class="trListaHead">
         <th>Organismo/Dependencia/Particular</th>
        <th>Destinatario</th>
        <th>Cargo</th>
        </tr>
       <tbody id="listaDetalles">
	   <? 
	   
	   $sdisext="select * from cp_documentodistribucionext where Cod_Documento='".$fcon['Cod_Documento']."' and Periodo ='".date("Y")."'";
	   $qdisext=mysql_query($sdisext) or die ($sdisext.mysql_error()); //echo $sdisext;
	   $risext= mysql_num_rows($qdisext); //echo $risext;
		
		if($risext!=0){
		   
		  for($i=0; $i<$risext; $i++){
		    $fdisext = mysql_fetch_array($qdisext);
			
			//// Pregunto si el documento es particular o no............. 
			if($fdisext['FlagEsParticular']=='N'){ 
			 if($fdisext['Cod_Dependencia']==''){ 
			  $sc = "SELECT
						   Organismo,
						   RepresentLegal,
						   Cargo,
						   CodOrganismo
		              FROM 
				           pf_organismosexternos
				     WHERE 
					  	   CodOrganismo = '".$fdisext['Cod_Organismos']."'";
			 }else{
			   $sc = "SELECT
						   Dependencia,
						   Representante,
						   Cargo
		              FROM 
				           pf_dependenciasexternas
				     WHERE 
					  	   CodDependencia = '".$fdisext['Cod_Dependencia']."' and
						   CodOrganismo = '".$fdisext['Cod_Organismos']."'";
			 }
			  $qc = mysql_query($sc) or die ($sc.mysql_error());
			 if (mysql_num_rows($qc) != 0) $fc = mysql_fetch_array($qc);
			}
 
			
			 
			 if($fdisext['FlagEsParticular']=='S'){
			 ?>
		     <tr class="trListaBody" onclick="mClk(this, 'seldetalle');" id="b|<?=$fdisext['Cod_Organismos']?>">
             <td align="center" width="20">
                 <input type="text" name="ente" id="ente" size="70" value="PARTICULAR" readonly/>
                 
                 <input type="hidden" name="codigo_organismo" value="<?=$fdisext['Cod_Organismos']?>" />
                 <input type="hidden" name="codigo_dependencia" value="" />
                 <input type="hidden" name="EsParticular" value="S"/>
             </td>
             
             <td align="left" width="70"><input type="text" name="representante" id="representante" size="70" value="<?=htmlentities($fdisext['Representante'])?>"/></td>
             
             <td align="left" width="70">
                 <input type="text" name="cargorepresentante"  size="60" id="cargorepresentante" value="<?=htmlentities($fdisext['Cargo'])?>"/>
                 <input type="hidden" id="cargo" name="cargo" value="<?=$fdisext['Cargo']?>"/>
             </td>
             </tr>
             
			 <? }else{
			      if($fdisext['Cod_Dependencia']==''){?>
                   <tr class="trListaBody" onclick="mClk(this, 'seldetalle');" id="<?=$fdisext['Cod_Organismos']?>">
                     <td align="center" width="20"><input type="text" id="ente" name="ente" value="<?=htmlentities($fc[0]);?>" size="70" />
                        <input type="hidden" id="codigo_organismo" name="cod_organismo" value="<?=$fdisext['Cod_Organismos']?>"/>
					 </td>
                     <td align="left" width="70"><input type="text" id="nombRepresentante" name="nombRepresentante" value="<?=htmlentities($fc[1]);?>" size="70"/>
                          <input type="hidden" id="codigo_dependencia" name="codigo_dependencia" value=""/>
                     </td>
                     <td align="left" width="70"><input type="text" name="cargoRepresentanteLegal" value="<?=htmlentities($fc[2]);?>" size="60"/>
                         <input type="hidden" id="EsParticular" name="EsParticular" value="N"/>
                         <input type="hidden" id="representante" name="representante" value="<?=$fdisext['Representante']?>"/>
                         <input type="hidden" id="cargorepresentante" name="cargorepresentante" value="<?=$fdisext['Cargo']?>"/></td>
		             </tr>
                  <? }else{?>
                     <tr class="trListaBody" onclick="mClk(this, 'seldetalle');" id="<?=a|$fdisext['Cod_Organismos']?>">
                       <td align="center" width="20"><input type="text" name="ente" id="ente" value="<?=htmlentities($fc[0]);?>" size="70"/>
                              <input type="hidden" name="codigo_organismo" value="<?=$fdisext['Cod_Organismos']?>"/>
                       </td>
                       <td align="left" width="70"><input type="text" name="repre_dependencia" value="<?=htmlentities($fc[1]);?>" size="70"/>
                              <input type="hidden" name="codigo_dependencia" value="<?=$fdisext['Cod_Dependencia']?>"/>
                       </td>
                       <td align="left" width="70"><input type="text" name="cargo_repredependencia" value="<?=htmlentities($fc[2]);?>" size="60"/>
					         <input type="hidden"  name="EsParticular" value="N"/>
						     <input type="hidden"  name="representante" value="<?=$fdisext['Representante']?>"/>
                             <input type="hidden"  name="cargorepresentante" value="<?=$fdisext['Cargo']?>"/>
                         </td>
		             </tr>
		    <?       } 
			    }
		
		   }} ?>
       </tbody>
      </table>
      </div></td>
   </tr>
  </table>
</td>
</tr>
</table>   
</form> 
<div class="divMsj" style="width:795px;">Campos Obligatorios *</div>
</body>
</html>