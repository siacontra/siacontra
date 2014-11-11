<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp.php");
//include("gmcorrespondencia.php");
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
		<td class="titulo">Documentos Externos | Atender Registro</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />
<table width="900" align="center">
<tr>
  <td>
	<div id="header">
	<ul>
	<!-- CSS Tabs PESTAÑAS OPCIONES DE PRESUPUESTO -->
	<li><a onClick="document.getElementById('tab1').style.display='block'; document.getElementById('tab2').style.display='none';" href="#">Datos Generales</a></li>
	<li><a onClick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='block';" href="#">Detalle Documento</a></li> 
	</ul>
	</div>
  </td>
</tr>
</table>

<?

 echo"<input type='hidden' id='depenOrigen' name='depenOrigen' value='0001'/>";
  echo"<input type='hidden' id='cod_documento' name='cod_documento' value='".$registro."'/>";
  echo"<input type='hidden' id='paginaIr' name='paginaIr' value='cpe_atenderext.php?limit=0'/>";
  
 $sql="select * from cp_documentoextentrada where NumeroRegistroInt='".$_POST['registro']."' "; //echo $sql;
 $qry=mysql_query($sql) or die ($sql.mysql_error());
 $field=mysql_fetch_array($qry);
?>

<div id="tab1" style="display:block;">
<form name="frmentrada" id="frmentrada" action="cpe_atenderext.php?limit=0" method="post"  onsubmit="return VerificarFormulario(this);">
<div style="width:895px; height:15px" class="divFormCaption">Datos Generales</div>
<table class="tblForm" width="895px" border="0">
 <tr>
   <td align="center"><!--//// -------------------------------------------------------------------------------------------------------- //// -->
   <table width="400">
     <tr>
    <td height="5"></td>
  </tr>
  <tr>
    <td width="118" class="tagForm">Tipo Documento:</td>
    <td width="320">
	<? 
	  echo"<input type='hidden' id='regresar' name='regresar' value='".$regresar."'/>
	       <input type='hidden' id='reg_interno' name='reg_interno' value='".$field['NumeroRegistroInt']."'/>
		   <input type='hidden' id='verificador' name='verificador' value=''/>
		   ";
	  //// -------------------  VISTAS ---------------------------------
	  //echo "Cod_TipoDocumento=".$field['Cod_TipoDocumento'];
	  
	  //// CONSULTO PARA OBTENER EL TIPO DE DOCUMENTO 
	  $sdoc="SELECT * FROM cp_tipocorrespondencia WHERE FlagProcedenciaExterna='1'";
	  $qdoc=mysql_query($sdoc) or die ($sdoc.mysql_error());
	  $rdoc=mysql_num_rows($qdoc);
	?>
        <select name="t_documento" id="t_documento" class="selectMed" disabled>
          <option value=""></option>
          <?
           for($i; $i<$rdoc; $i++){
	          $fdoc=mysql_fetch_array($qdoc);
		      if($field['Cod_TipoDocumento']==$fdoc['Cod_TipoDocumento']){
                 echo" <option value='".$fdoc['Cod_TipoDocumento']."' selected>".$fdoc['Descripcion']."</option>";
		      }else{
                 echo" <option value='".$fdoc['Cod_TipoDocumento']."'>".$fdoc['Descripcion']."</option>";
              }  
		   }
		  ?>
        </select>
      *</td>
  </tr>
  <tr style="border-style:double">
    <td class="tagForm">Nro. Documento:</td>
    <td><input type="text" id="n_documento" name="n_documento" size="20" style="text-align:right" value="<?=$field['NumeroDocumentoExt'];?>" readonly disabled/>
      *</td>
  </tr>
  <tr>
    <td height="5"></td>
  </tr>
   </table>
   </td><!--//// -------------------------------------------------------------------------------------------------------- //// -->
   <td align="center"><!--//// -------------------------------------------------------------------------------------------------------- //// -->
   <table width="400">
     <tr>
    <td height="5"></td>
  </tr>
  <tr>
    <td width="110" class="tagForm">Fecha Documento:</td>
    <?
     list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaDocumentoExt']); $f_documentoext=$d.'-'.$m.'-'.$a;
	 list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaRegistro']); $f_registro=$d.'-'.$m.'-'.$a;
   ?>
    <td width="278"><input type="text" id="fecha_documento" name="fecha_documento" size="10" value="<?=$f_documentoext;?>" style="text-align:right" readonly disabled/>
      *(dd-mm-aaaa)</td>
  </tr>
  <tr style="border-style:double">
    <td class="tagForm">Fecha Recibido:</td>
    <td><input type="text" id="fecha_recibido" name="fecha_recibido" size="10" maxlength="10" value="<?=$f_registro;?>" style="text-align:right" readonly disabled/>
      *(dd-mm-aaaa)</td>
  </tr>
  <tr>
    <td height="5"></td>
  </tr>
   </table>
   </td><!--//// -------------------------------------------------------------------------------------------------------- //// -->
 </tr>
  
  <tr></tr>
  
  <tr>
   <td><!--//// -------------------------------------------------------------------------------------------------------- //// -->
    <? if($field['FlagEsParticular']=='N'){ ?>
     <table width="435" border="1" bordercolor="#999999" cellspacing="0" align="center">
     <tr><td>
     <table width="435" align="center">
      <tr><td colspan="3" align="center"><b>Organismo Remitente</b></td></tr>
      <tr>
      <?
	  if($field['Cod_Dependencia']==''){
		  
		      $sa="SELECT 
		                    Organismo as organismo, 
		                    RepresentLegal as r_legalorg,
							Cargo as cargo 
		               FROM 
					        pf_organismosexternos
					  WHERE 
					       CodOrganismo= '".$field['Cod_Organismos']."'";
		}else{
		    $sa="SELECT 
			                pforg.Organismo as organismo, 
		                    pforg.RepresentLegal as r_legalorg, 
							pfdep.Representante as r_legaldep, 
							pfdep.Dependencia as dependencia,
							pforg.Cargo as cargo 
		               FROM 
					        pf_organismosexternos pforg, 
							pf_dependenciasexternas pfdep 
					  WHERE 
					        pfdep.CodDependencia= '".$field['Cod_Dependencia']."' AND
							pforg.CodOrganismo= '".$field['Cod_Organismos']."'";
		 }
		 $qa=mysql_query($sa) or die ($sa.mysql_error());
		 $fa=mysql_fetch_array($qa);
	  ?>
        <td><input type="checkbox" id="activarOrg" name="activarOrg" checked onclick="ActivarOrganismo(this.form)" disabled/></td>
        <td class="tagForm">Organismo:</td>
        <td colspan="1"><select id="organismo" name="organismo" class="selectBig" onchange="getOrganismosExternos(this.id, 'dep_externa');" disabled>
           <option value="<?=$field['Cod_Organismos'];?>"><?=htmlentities($fa['organismo']);?></option>
           </select>*</td>
      </tr>
      <tr>
        <td></td>
        <td class="tagForm" colspan="">Dependencia:</td>
        <td colspan="1"><select id="dep_externa" name="dep_externa" class="selectBig" disabled>
                          <option value="<?=$field['Cod_Dependencia']?>"><?=htmlentities($fa['dependencia']);?></option>
                        </select>*</td>
  </tr>
  <tr>
    <td></td>
    <td class="tagForm">Representante:</td>
    <? if($field['Cod_Dependencia']==''){ ?>
    <td><input type="text" id="remitente_ext" name="remitente_ext" size="68" value="<?=htmlentities($fa['r_legalorg']);?>" readonly disabled/>*</td>
    <? }else{ ?>
    <td><input type="text" id="remitente_ext" name="remitente_ext" size="68" value="<?=htmlentities($fa['r_legaldep']);?>" readonly disabled/>*</td>
    <? }?>
  </tr>
  <tr>
   <td></td>
    <td class="tagForm">Cargo:</td>
    <? if($field['Cod_Dependencia']==''){ ?>
      <td><input type="text" id="cargoremitente_ext2" name="cargoremitente_ext2" size="68" value="<?=$fa['cargo']?>" readonly disabled/>
        <!--<input type="text" id="codigo_interno" name="codigo_interno" value=""/>
        <input type="text" id="codigo_persona" name="codigo_persona" value=""/>
        <input type="text" id="codigo_cargo" name="codigo_cargo" value=""/>--></td>
    <? }else{ ?>
      <td><input type="text" id="cargoremitente_ext" name="cargoremitente_ext" size="68" value="<?=$fa['cargo']?>" readonly disabled/>
    <? }?>  </tr>
  <tr><td height="25"></td></tr>
  </table>
  </td>
  </tr>
  </table>
   <? }else{ ?>
   <table width="435" border="1" bordercolor="#999999" cellspacing="0" align="center">
     <tr><td>
     <table width="435" align="center">
      <tr><td colspan="3" align="center"><b>Organismo Remitente</b></td></tr>
      <tr>
        <td><input type="checkbox" id="activarOrg" name="activarOrg" disabled onclick="ActivarOrganismo(this.form)" readonly/></td>
        <td class="tagForm">Organismo:</td>
        <td colspan="1"><select id="organismo" name="organismo" class="selectBig" onchange="getOrganismosExternos(this.id, 'dep_externa');" disabled>
           <option value=""></option>
             <? getOrganismosExt('', 0); ?></select>*</td>
      </tr>
      <tr>
        <td></td>
        <td class="tagForm" colspan="">Dependencia:</td>
        <td colspan="1"><select id="dep_externa" name="dep_externa" class="selectBig" disabled>
      <option value=""></option>
    </select>*</td>
  </tr>
  <tr>
    <td></td>
    <td class="tagForm">Representante:</td>
    <td><input type="text" id="remitente_ext" name="remitente_ext" size="68" value="" disabled/>*</td>
  </tr>
  <tr>
   <td></td>
    <td class="tagForm">Cargo:</td>
    <td><input type="text" id="cargoremitente_ext" name="cargoremitente_ext" size="68" value="" disabled/>
        <!--<input type="text" id="codigo_interno" name="codigo_interno" value=""/>
        <input type="text" id="codigo_persona" name="codigo_persona" value=""/>
        <input type="text" id="codigo_cargo" name="codigo_cargo" value=""/>--></td>
  </tr>
  <tr><td height="25"></td></tr>
  </table>
  </td>
  </tr>
  </table>
   <? } ?>
  </td><!--//// -------------------------------------------------------------------------------------------------------- //// --> 
  <td><!--//// -------------------------------------------------------------------------------------------------------- //// -->
     <? if($field['FlagEsParticular']=='S'){ ?>
     <table width="400" border="1" bordercolor="#999999" cellspacing="0" align="center">
     <tr><td>
     <table width="400" align="center">
      <tr><td colspan="3" align="center"><b>Particular Remitente</b></td></tr>
      <tr>
      <td><input type="checkbox" id="activar" name="activar"  onclick="ActivarParticular(this.form)" disabled/></td>
    <td class="tagForm">Nombre:</td>
    <td colspan="1">
        <input name="codParticular" type="hidden" id="codParticular" value="<?=$field['Cod_Organismos']?>" /><input name="p_nombre" id="p_nombre" type="hidden" value="" />
        <input name="nombreParticular" id="nombreParticular" type="text" size="52" value="<?=$field['Remitente']?>" readonly disabled="disabled"/>
        <input name="bt_examinar2" id="bt_examinar2" type="button" value="..." onclick="cargarVentana(this.form, 'lista_particulares.php?limit=0&campo=6', 'height=500, width=800, left=200, top=200, resizable=yes');" disabled="disabled" />
      *</td>
  </tr>
  <tr>
  <?
   $sb="select * from cp_particular where CodParticular='".$field['Cod_Organismos']."'";
   $qb=mysql_query($sb) or die ($sb.mysql_error());
   $fb=mysql_fetch_array($qb);
  ?>
  <td></td>
    <td class="tagForm">Nro.Cedula:</td>
    <td><input type="text" id="p_cedula" name="p_cedula" size="15" value="<?=$fb['Cedula']?>" disabled="disabled"/></td>
  </tr>
  <tr>
  <td></td>
    <td class="tagForm">Direcci&oacute;n:</td>
    <td><input type="text" id="p_direccion" name="p_direccion" size="40" value="<?=$fb['Direccion']?>" disabled="disabled"/></td>
  </tr>
   <tr>
   <td></td>
    <td class="tagForm">Tel&eacute;fono:</td>
    <td><input type="text" id="p_telefono" name="p_telefono" size="40" value="<?=$fb['Telefono']?>" disabled="disabled"/></td>
  </tr>
  <tr>
  <td></td>
    <td class="tagForm">Cargo:</td>
    <td><input type="text" id="p_cargo" name="p_cargo" size="40" value="<?=$fb['Cargo']?>" disabled="disabled"/>
        <!--<input type="text" id="codigo_interno" name="codigo_interno" value=""/>
        <input type="text" id="codigo_persona" name="codigo_persona" value=""/>
        <input type="text" id="codigo_cargo" name="codigo_cargo" value=""/>--></td>
  </tr>
  </table>
  </td>
  </tr>
  </table>
  <? }else{ ?>
   <table width="400" border="1" bordercolor="#999999" cellspacing="0" align="center">
     <tr><td>
     <table width="400" align="center">
      <tr><td colspan="3" align="center"><b>Particular Remitente</b></td></tr>
      <tr>
      <td><input type="checkbox" id="activar" name="activar" onclick="ActivarParticular(this.form)" disabled/></td>
    <td class="tagForm">Nombre:</td>
    <td colspan="1">
        <input name="codParticular" type="hidden" id="codParticular" value="" /><input name="p_nombre" id="p_nombre" type="hidden" value="" readonly/>
        <input name="nombreParticular" id="nombreParticular" type="text" size="52" value="" readonly disabled="disabled"/>
        <input name="bt_examinar2" id="bt_examinar2" type="button" value="..." onclick="cargarVentana(this.form, 'lista_particulares.php?limit=0&campo=6', 'height=500, width=800, left=200, top=200, resizable=yes');" disabled="disabled" />
      *</td>
  </tr>
  <tr>
  <td></td>
    <td class="tagForm">Nro.Cedula:</td>
    <td><input type="text" id="p_cedula" name="p_cedula" size="15" value="" disabled="disabled"/></td>
  </tr>
  <tr>
  <td></td>
    <td class="tagForm">Direcci&oacute;n:</td>
    <td><input type="text" id="p_direccion" name="p_direccion" size="40" value="" disabled="disabled"/></td>
  </tr>
   <tr>
   <td></td>
    <td class="tagForm">Tel&eacute;fono:</td>
    <td><input type="text" id="p_telefono" name="p_telefono" size="40" value="" disabled="disabled"/></td>
  </tr>
  <tr>
  <td></td>
    <td class="tagForm">Cargo:</td>
    <td><input type="text" id="p_cargo" name="p_cargo" size="40" value="" disabled="disabled"/>
        <!--<input type="text" id="codigo_interno" name="codigo_interno" value=""/>
        <input type="text" id="codigo_persona" name="codigo_persona" value=""/>
        <input type="text" id="codigo_cargo" name="codigo_cargo" value=""/>--></td>
  </tr>
  </table>
  </td>
  </tr>
  </table>
  <? } ?>
  </td><!--//// -------------------------------------------------------------------------------------------------------- //// -->
 </tr>
    <!-- ////////////////////////////////////////////// -->
 <tr>
   <td><!--//// -------------------------------------------------------------------------------------------------------- //// -->
   <?
	 $sa="select 
	             CodPersona,
				 NomCompleto
	        from 
			    mastpersonas                 
		   where 
		        CodPersona = '".$field['RecibidoPor']."'";
	 $qa=mysql_query($sa) or die ($sa.mysql_error());
	 $fa=mysql_fetch_array($qa);
   ?>
     <table width="448">
     <tr>
    <td class="tagForm">Recibido por:</td>
    <td><input name="codempleado" type="hidden" id="codempleado" value="<?=$fa['0']?>" />
        <input name="nomempleado" id="nomempleado" type="text" size="52" value="<?=$fa['NomCompleto']?>" readonly/>
        <input name="bt_examinar" id="bt_examinar" type="button" value="..." disabled/>
      * </td>
  </tr>
  <tr>
    <td class="tagForm">Asunto Tratado:</td>
    <td colspan="1"><input type="text" id="asunto" name="asunto" size="60" value="<?=$field['Asunto']?>" readonly/>*</td>
  </tr>
  <tr>
    <td class="tagForm">Comentario:</td>
    <td colspan="1"></td>
  </tr>
  <tr>
    <td class="tagForm"></td>
    <td colspan="1"><textarea name="descripcion" id="descripcion" rows="2" cols="65" readonly><?=$field['Descripcion']?></textarea></td>
  </tr>
  </table><!--//// -------------------------------------------------------------------------------------------------------- //// --> 
  </td>
  <td><!--//// -------------------------------------------------------------------------------------------------------- //// -->
     <table width="400">
      <tr>
        <td align="center"  colspan="2"><b>Datos del Mensajero</b></td>
      </tr>
     <tr>
       <td class="tagForm">Mensajero:</td>
       <td><input type="text" id="mensajero" name="mensajero" size="50" value="<?=$field['Mensajero']?>" readonly/>*</td>
     </tr>
    <tr>
      <td class="tagForm">CI Mensajero:</td>
     <td><input type="text" id="ci_mensajero" name="ci_mensajero" size="10" style="text-align:right" value="<?=$field['CedulaMensajero']?>" readonly/>*</td>
    </tr>
  <tr>
   <td height="40"></td>
  </tr>
  </table><!--//// -------------------------------------------------------------------------------------------------------- //// -->
  </td>
 </tr>
  
 <tr>
  <td><!--//// -------------------------------------------------------------------------------------------------------- //// --> 
  <table width="448">
  <tr>
    <td height="5"></td>
  </tr>
  <tr>
    <td class="tagForm">Nro. Folio:</td>
    <td><input type="text" id="folio" name="folio" size="2" maxlength="3" style="text-align:right" value="<?=$field['Folio']?>" readonly/>
      * 
      Nro. Anexo:
        <input type="text" id="anexofolio" name="anexofolio" size="2" maxlength="3" style="text-align:right" value="<?=$field['AnexoFolio']?>" readonly/>
      *
      Nro. Carpeta:
      <input type="text" id="nro_carpeta" name="nro_carpeta" size="2" maxlength="3" style="text-align:right" value="<?=$field['Carpetas']?>" readonly/>
      *</td>
  </tr>
  <tr>
    <td class="tagForm">Descripci&oacute;n:</td>
  </tr>
  <tr>
    <td></td>
    <td><textarea name="descpfolio" id="descpfolio" rows="2" cols="65" readonly><?=$field['DescpFolio']?></textarea></td>
  </tr>
  <tr>
    <td class="tagForm"></td>
    <td colspan="1"></td>
  </tr>
  <tr>
    <td height="5"></td>
  </tr>
  </table>
  </td><!--//// -------------------------------------------------------------------------------------------------------- //// -->
 </tr>
  <tr>
    <td colspan="4"><table align="center" width="400">
      <tr>
        <td width="92" class="tagForm">Ultima Modif.:</td>
        <td width="296" colspan="2"><input type="text" id="ultimousuario" size="20" readonly="readonly" value="<?=$field['UltimoUsuario']?>"/>
              <input type="text" id="ultimafecha" size="20" readonly="readonly" value="<?=$field['UltimaFechaModif']?>"/></td>
      </tr>
    </table></td>
  </tr>
</table>
<center>
    <input name="guardar" type="submit" id="guardar" value="Guardar Registro"/>
    <input name="cancelar" type="button" id="cancelar" value="Cancelar" onClick="cargarPagina(this.form, 'cpe_atenderext.php?limit=0');" />
</center>
</form> 
</div>

<div id="tab2" style="display:none;">
<div style="width:895px; height:15px" class="divFormCaption">Detalle</div>
<form name="frmchecks" id="frmchecks">
<table width="895px" class="tblForm">
<tr>
  <td><input type="checkbox" id="infor_escrito" name="infor_escrito" value="1"/></td>
  <td align="left">Informarme por escrito</td>
  <td><input type="checkbox" id="inv_inforver" name="inv_inforver" value="1"/></td>
  <td align="left">Investigar e informar verbalmente</td>
  <td><input type="checkbox" id="pre_contfirm" name="pre_contfirm" value="1"/></td>
  <td align="left">Preparar contestacion para mi firma</td>
  <td><input type="checkbox" id="conocer_opinion" name="conocer_opinion" value="1"/></td>
  <td align="left">Para conocer su opinion</td>
</tr>
<tr>
  <td><input type="checkbox" id="hablar_alrespecto" name="hablar_alrespecto" value="1"/></td>
  <td align="left">Hablar conmigo al respecto</td>
  <td><input type="checkbox" id="tram_conclusion" name="tram_conclusion" value="1"/></td>
  <td align="left">Tramitar hasta su conclusi&oacute;n</td>
  <td><input type="checkbox" id="archivar" name="archivar" value="1"/></td>
  <td align="left">Archivar</td>
  <td><input type="checkbox" id="tram_casoproceden" name="tram_casoproceden" value="1"/></td>
  <td align="left">Tramitar en caso de proceder</td>
</tr>
<tr>
  <td><input type="checkbox" id="coord_con" name="coord_con" value="1" onclick="enabledenabledcoord_con(this.form)"/></td>
  <td align="left">Coordinar con:<input type="text" id="coord_con2" name="coord_con2" size="30" disabled="disabled"/></td>
  <td><input type="checkbox" id="distribuir" name="distribuir" value="1"/></td>
  <td align="left">Distribuir</td>
  <td><input type="checkbox" id="registro_de" name="registro_de" value="1" onclick="enabledRegistrode(this.form)"/></td>
  <td align="left">Registro de:<input type="text" id="registro_de2" name="registro_de2" size="30" disabled="disabled"/></td>
  <td><input type="checkbox" id="acusar_recibo" name="acusar_recibo" value="1"/></td>
  <td align="left">Acusa recibo</td>
</tr>
<tr>
  <td><input type="checkbox" id="pre_memo" name="pre_memo" value="1" onclick="enabledPrememo(this.form)"/></td>
  <td align="left">Prepara memo a:<input type="text" id="pre_memo2" name="pre_memo2" size="30" disabled="disabled"/></td>
  <td><input type="checkbox" id="pconocimiento_fp" name="pconocimiento_fp" value="1"/></td>
  <td align="left">Para su conocimiento y fines pertinentes</td>
  <td><input type="checkbox" id="prep_oficio" name="prep_oficio" value="1" onclick="enabledPrepoficio(this.form)"/></td>
  <td align="left">Preparar oficio a:<input type="text" id="prep_oficio2" name="prep_oficio2" size="30" disabled="disabled"/></td>
  <td><input type="checkbox" id="tram_dias" name="tram_dias" value="1" onclick="enabledTramdias(this.form)"/></td>
  <td align="left">Tramitar en <input type="text" id="tram_dias2" name="tram_dias2" size="2" style="text-align:right" disabled="disabled"/> dias</td>
</tr>
<tr>
  <td height="3"></td>
</tr>
<tr>
  <td colspan="8"><div class="cellText" align="center"><b>Enviar a:</b></div></td>
</tr>
<tr>
  <td class="tagForm" colspan="2">Enviar a:</td>
</tr>
<tr>
  <td colspan="8">
  <table width="600" class="tblBotones">
   <tr>
 	<td align="right">
    <input type="button" class="btLista" id="btInsertarDependencia" value="Ins. Dep." onclick="Verificar()|cargarVentana(this.form,'lista_dependencias.php?limit=0&ventana=insertarDestinatarioDep&tabla=item', 'height=600, width=800, left=50, top=50, resizable=yes');" />
    <input type="button" class="btLista" id="btInsertarEmpleado" value="Ins. Emp." onclick="Verificar()|cargarVentana(this.form,'lista_empleados.php?limit=0&ventana=insertarDestinatarioEmp&tabla=item', 'height=600, width=800, left=50, top=50, resizable=yes');" />
    <input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="quitarLineaDestinatario(document.getElementById('seldetalle').value);" />
	</td>
  </tr>
  </table>
  </td>
</tr>
</table>
</form>
<!-- @@@@@@@@@@@@@@@@@@@@@@@@ LISTA A MOSTRAR @@@@@@@@@@@@@@@@@@@@@@@@ -->
<form name="frmdetalles" id="frmdetalles">
<input type="hidden" id="seldetalle"/>
<table align="center" cellpadding="0" cellspacing="0">
<tr>
  <td valign="top" style="height:250px; width:595px;">
    <table align="center" width="595px">
    <tr>
      <td align="center"><div style="overflow:scroll; height:250px; width:595px;">
      <table width="595px" class="tblLista">
       <tr>
        <th scope="col" class="trListaHead">Dependencia</th>
        <th scope="col" class="trListaHead">Representante/Empleado</th>
        <th scope="col" class="trListaHead">Cargo</th>
        </tr>
       <tbody id="listaDetalles"></tbody>
      </table>
      </div></td>
   </tr>
  </table>
</td>
</tr>
</table>   

</form>
</div>
<div class="divMsj" style="width:795px;">Campos Obligatorios *</div>
</body>
</html>
<script language="javascript" type="text/javascript">
function VerificarFormulario(formulario){

	if (formulario.verificador.value.length <1) {
	  alert("Eliga un Destinatario");
	  //formulario.verificador.focus();
	return (false);
	}
	var checkOK = "0123456789";
	var checkStr = formulario.verificador.value;
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
	 alert("Eliga un Destinatario"); 
	 //formulario.verificador.focus(); 
	 return (false); 
	}
 
     return guardarAtenderExt(formulario);
}
</script>