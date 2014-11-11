<?php

session_start();

if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");

//	------------------------------------

include("fphp.php");

connect();

//include "ControlCorrespondencia.php";

//include("gmcorrespondencia.php");



list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('03', $concepto);

//	------------------------------------

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link href="css1.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" language="javascript" src="fscript.js"></script>

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





<? 

  $sql="select * from cp_documentoextentrada where NumeroRegistroInt='".$_POST['registro']."' "; //echo $sql;

  $qry=mysql_query($sql) or die ($sql.mysql_error());

  $field=mysql_fetch_array($qry);



if($field['Estado']=='PE'){ 

?>

  <table width="100%" cellspacing="0" cellpadding="0">

  <tr>

	<td class="titulo">Documentos Externos | Editar Registro</td>

	<td align="right"><a class="cerrar" href="framemain.php"  onclick="cargarPagina(document.getElementById('frmentrada'),'<?=$regresar?>.php?&limit=0')";>[Cerrar]</a></td>

  </tr>

  </table><hr width="100%" color="#333333" />

  <table width="900" align="center">

  <tr>

   <td>

	<div id="header">

	<ul>

	<!-- CSS Tabs PESTAÑAS OPCIONES DE PRESUPUESTO -->

	<li><a onClick="document.getElementById('tab1').style.display='block';" href="#">Datos Generales</a></li>

	<li><a  href="#">Detalle de Documento</a></li> 

	</ul>

	</div>

   </td>

 </tr>

 </table>



<form name="frmentrada" id="frmentrada" action="cpe_entrada.php?limit=0&accion=EditarEntradaExterna" method="post">

<div style="width:895px; height:15px" class="divFormCaption">Datos Generales</div>

<? 

  echo"<input type='hidden' name='cod_documento' id='cod_documento' value='".$_POST['registro']."'/>

       <input type='hidden' name='numero_registro' id='numero_registro' value='".$field['NumeroRegistroInt']."'/>  "; 

?>

<table class="tblForm" width="895px" border="0">

 <tr>

   <td align="center"><!--//// ------------------------------------------------------------------------------------------ //// -->

   <table width="400">

     <tr>

    <td height="5"></td>

  </tr>

  <tr>

    <td width="118" class="tagForm">Tipo Documento:</td>

    <td width="320">

	<? 

	  echo"<input type='hidden' id='regresar' name='regresar' value='".$regresar."'/>";

	  //// -------------------  VISTAS ---------------------------------



	  //// CONSULTO PARA OBTENER EL TIPO DE DOCUMENTO 

	  $sdoc="SELECT * FROM cp_tipocorrespondencia WHERE FlagProcedenciaExterna='1'";

	  $qdoc=mysql_query($sdoc) or die ($sdoc.mysql_error());

	  $rdoc=mysql_num_rows($qdoc);

	?>

        <select name="t_documento" id="t_documento" class="selectMed">

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

    <td><input type="text" id="n_documento" name="n_documento" size="20" style="text-align:right" value="<?=$field['NumeroDocumentoExt'];?>"/>

      *</td>

  </tr>

  <tr>

    <td height="5"></td>

  </tr>

   </table>

   </td><!--//// -------------------------------------------------------------------------------------------------------- //// -->

   <td align="center"><!--//// ------------------------------------------------------------------------------------------ //// -->

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

    <td width="278"><input type="text" id="fecha_documento" name="fecha_documento" size="10" value="<?=$f_documentoext;?>" style="text-align:right"/>

      *(dd-mm-aaaa)</td>

  </tr>

  <tr style="border-style:double">

    <td class="tagForm">Fecha Recibido:</td>

    <td><input type="text" id="fecha_recibido" name="fecha_recibido" size="10" maxlength="10" value="<?=$f_registro;?>" style="text-align:right" readonly/>

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

     <tr><input type="hidden" id="flagParticular" name="flagParticular" value="<?=$field['FlagEsParticular'];?>"/><td>

     <table width="435" align="center">

      <tr><td colspan="3" align="center"><b>Organismo Remitente</b></td></tr>

      <tr>

        <td><input type="checkbox" id="activarOrg" name="activarOrg" checked onclick="ActivarOrganismo(this.form)"/></td>

        <td class="tagForm">Organismo:</td>

        <td colspan="1"><select id="organismo" name="organismo" class="selectBig" onchange="getOrganismosExternos(this.id, 'dep_externa');" >

           <option value=""></option>

             <? getOrganismosExt(0, $field['Cod_Organismos']); ?></select>*</td>

      </tr>

      <tr>

        <td></td>

        <td class="tagForm" colspan="">Dependencia:</td>

        <td colspan="1"><select id="dep_externa" name="dep_externa" class="selectBig" >

      <option value=""></option>

         <? getDependenciasExternas($field['Cod_Dependencia'], $field['Cod_Organismos'], 0);?>

    </select>*

    </td>

  </tr>

  <tr>

    <td></td>

    <td class="tagForm">Representante:</td>

    <td><input type="text" id="remitente_ext" name="remitente_ext" size="68" value="<?=htmlentities($field['Remitente'])?>"/>*</td>

  </tr>

  <tr>

   <td></td>

    <td class="tagForm">Cargo:</td>

    <td><input type="text" id="cargoremitente_ext" name="cargoremitente_ext" size="68" value="<?=$field['Cargo']?>"/>

        <!--<input type="text" id="codigo_interno" name="codigo_interno" value=""/>

        <input type="text" id="codigo_persona" name="codigo_persona" value=""/>

        <input type="text" id="codigo_cargo" name="codigo_cargo" value=""/>--></td>

  </tr>

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

        <td><input type="checkbox" id="activarOrg" name="activarOrg" disabled onclick="ActivarOrganismo(this.form)"/></td>

        <td class="tagForm">Organismo:</td>

        <td colspan="1"><select id="organismo" name="organismo" class="selectBig" disabled onchange="getOrganismosExternos(this.id, 'dep_externa');" >

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

  <td><!--//// --------------------------------------------------------------------------------------------------------- //// -->

     <? if($field['FlagEsParticular']=='S'){ ?>

     <table width="400" border="1" bordercolor="#999999" cellspacing="0" align="center">

     <tr><td>

     <table width="400" align="center">

      <tr><td colspan="3" align="center"><b>Particular Remitente</b></td></tr>

      <tr>

      <td><input type="checkbox" id="activar" name="activar"  onclick="ActivarParticular(this.form)"/></td>

    <td class="tagForm">Nombre:</td>

    <td colspan="1">

        <input name="codParticular" type="hidden" id="codParticular" value="" /><input name="p_nombre" id="p_nombre" type="hidden" value="" />

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

      <td><input type="checkbox" id="activar" name="activar" disabled onclick="ActivarParticular(this.form)"/></td>

    <td class="tagForm">Nombre:</td>

    <td colspan="1">

        <input name="codParticular" type="hidden" id="codParticular" value="" /><input name="p_nombre" id="p_nombre" type="hidden" value="" />

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

 <tr>

   <td><!--//// -------------------------------------------------------------------------------------------------------- //// -->

   <?

 	 $sa="select 

	             CodPersona,

				 NomCompleto

	        from 

			    mastpersonas mp                

		   where 

		        CodPersona = '".$field['RecibidoPor']."'";

	 $qa=mysql_query($sa) or die ($sa.mysql_error());

	 $fa=mysql_fetch_array($qa);

   ?>

     <table width="448">

     <tr>

    <td class="tagForm">Recibido por:</td>

    <td>

        <input name="cargoremit" type="text" id="cargoremit" value="" />

        <input name="cod_cargoremit" type="text" id="cod_cargoremit" value="<?=$field['CargoRecibidoPor']?>" />

        <input name="codempleado" type="text" id="codempleado" value="<?=$fa['0']?>" />

        <input name="nomempleado" id="nomempleado" type="text" size="61" value="<?=$fa['NomCompleto']?>" />

        <input name="bt_examinar" id="bt_examinar" type="button" value="..." onclick="cargarVentana(this.form, 'lista_empleados.php?limit=0&campo=4', 'height=500, width=800, left=200, top=200, resizable=yes');" />

      * </td>

  </tr>

  <tr>

    <td class="tagForm">Asunto Tratado:</td>

    <td colspan="1"><input type="text" id="asunto" name="asunto" size="70" value="<?=$field['Asunto']?>"/>*</td>

  </tr>

  <tr>

    <td class="tagForm">Comentario:</td>

    <td colspan="1"></td>

  </tr>

  <tr>

    <td class="tagForm"></td>

    <td colspan="1"><textarea name="descripcion" id="descripcion" rows="2" cols="65"><?=$field['Descripcion']?></textarea></td>

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

       <td><input type="text" id="mensajero" name="mensajero" size="50" value="<?=$field['Mensajero']?>"/>*</td>

     </tr>

    <tr>

      <td class="tagForm">CI Mensajero:</td>

     <td><input type="text" id="ci_mensajero" name="ci_mensajero" size="10" style="text-align:right" value="<?=$field['CedulaMensajero']?>"/>*</td>

    </tr>

  <tr>

   <td height="40"></td>

  </tr>

  </table><!--//// -------------------------------------------------------------------------------------------------------- //// -->

  </td>

 </tr>

  

 <tr>

  <td>

  <table width="448">

  <tr>

    <td height="5"></td>

  </tr>

  <tr>

    <td class="tagForm">Nro. Folio:</td>

    <td><input type="text" id="folio" name="folio" size="2" maxlength="3" style="text-align:right" value="<?=$field['Folio']?>"/>

      * 

      Nro. Anexo:

        <input type="text" id="anexofolio" name="anexofolio" size="2" maxlength="3" style="text-align:right" value="<?=$field['AnexoFolio']?>"/>

      *

      Nro. Carpeta:

      <input type="text" id="nro_carpeta" name="nro_carpeta" size="2" maxlength="3" style="text-align:right" value="<?=$field['Carpetas']?>"/>

      *</td>

  </tr>

  <tr>

    <td class="tagForm">Descripci&oacute;n:</td>

  </tr>

  <tr>

    <td></td>

    <td><textarea name="descpfolio" id="descpfolio" rows="2" cols="65"><?=$field['DescpFolio']?></textarea></td>

  </tr>

  <tr>

    <td class="tagForm"></td>

    <td colspan="1"></td>

  </tr>

  <tr>

    <td height="5"></td>

  </tr>

  </table>

  </td>

 </tr>

  <tr>

    <td colspan="4">

    <table align="center" width="400">

      <tr>

        <td width="92" class="tagForm">Ultima Modif.:</td>

        <td width="296" colspan="2"><input type="text" id="ultimousuario" size="20" readonly="readonly" value="<?=$field['UltimoUsuario']?>"/>

              <input type="text" id="ultimafecha" size="20" readonly="readonly" value="<?=$field['UltimaFechaModif']?>"/></td>

      </tr>

    </table>

  </td>

  </tr>

</table>

<!-- ************************************************************************************************************************** -->

<center>

    <input name="guardar" type="submit" id="guardar" value="Guardar Registro"/>

    <input name="cancelar" type="button" id="cancelar" value="Cancelar" onClick="cargarPagina(this.form,'<?=$regresar?>.php?limit=0');" />

</center> 

</form> 

<div class="divMsj" style="width:795px;">Campos Obligatorios *</div>

<? 

  }else{

     echo"<script>

        alert('NO PUEDE SER EDITADO');

        history.back(-1);

      </script>";

  }

?>

</body>

</html>