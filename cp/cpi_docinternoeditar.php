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
		<td class="titulo">Documentos Internos | Editar</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />
<?
 list($cod_tipodocumento, $cod_documentocompleto)=SPLIT( '[|]',$_POST['registro']);
 //echo $cod_tipodocumento;
 //echo $cod_documentocompleto;
 
 $s="select * from cp_documentointerno where Cod_DocumentoCompleto='$cod_documentocompleto' and Cod_TipoDocumento='$cod_tipodocumento'";
 $q=mysql_query($s) or die ($s.mysql_error());
 $f=mysql_fetch_array($q);
?>

<form name="frmentrada" id="frmentrada" action="cpi_docinternoslista.php?limit=0&fremitente=<?=$fremitente?>&fEstado=<?=$fEstado?>" method="post"  onsubmit="return guardarDocumentoInternoEditar(this);" >
<div style="width:925px; height:15px" class="divFormCaption">Datos del Documento</div>
<table class="tblForm" width="925" border="0">
<tr><td height="5"></td></tr>
<tr>
  <td width="127" class="tagForm">Tipo Documento:</td>
  <td width="313">
     <? 
	  echo"<input type='hidden' id='regresar' name='regresar' value='".$regresar."'/>";
	  echo"<input type='hidden' id='fremitente' name='fremitente' value='".$fremitente."'/>";
	  echo"<input type='hidden' id='fEstado' name='fEstado' value='".$fEstado."'/>";
	  echo"<input type='hidden' id='Estado' name='Estado' value='".$f['Estado']."'/>";
	  $sql="SELECT * FROM cp_tipocorrespondencia WHERE FlagUsoInterno='1'";
	  $qry=mysql_query($sql) or die ($sql.mysql_error());
	  $row=mysql_num_rows($qry);?>
      <select name="t_documento" id="t_documento" class="selectMed" disabled="disabled">
        <?
      for($i; $i<$row; $i++){
	    $field=mysql_fetch_array($qry);
		if($field['Cod_TipoDocumento']==$f['Cod_TipoDocumento']){
		?>
        <option value="<?=$field['Cod_TipoDocumento'];?>" selected><?=$field['Descripcion'];?></option>
        <? }else{ ?>
		
         <option value="<?=$field['Cod_TipoDocumento'];?>"><?=$field['Descripcion'];?></option>
		<?} }?>
      </select>*</td>
   <td width="120" class="tagForm">Fecha Documento:</td>
 <? list($a, $m, $d)=SPLIT( '[/.-]', $f['FechaDocumento']); $f_documento=$d.'-'.$m.'-'.$a; ?>
 <td width="345"><input type="text" id="fecha_documento" name="fecha_documento" size="10" value="<?=$f_documento;?>" style="text-align:right" readonly/>*(dd-mm-aaaa)</td>
</tr>
<tr style="border-style:double">
 <td class="tagForm">Nro. Documento:</td>
 <td><input type="text" id="n_documento" name="n_documento" size="24" value="<?=$f['Cod_DocumentoCompleto']?>" style="text-align:right" readonly/>*</td>
 <td class="tagForm">Plazo de Atenci&oacute;n:</td>
 <td><input type="text" id="plazo" name="plazo" size="5" maxlength="3" style="text-align:right" value="<?=$f['PlazoAtencion']?>"/> d&iacute;a(s)</td>
</tr>
<tr>
  <td class="tagForm">Asunto:</td>
  <td colspan="2"><input type="text" id="asunto" name="asunto" size="84" value="<?=$f['Asunto']?>"/>*</td>
  <td>Anexos: Si <input type="hidden" name="anexos" id="anexos"/>
     <? if($f['FlagsAnexo']=='S'){?><input type="radio" id="anexsi1" name="anexsi1" value="S" checked="checked"/>
     <? }else{?><input type="radio" id="anexsi1" name="anexsi1" onclick="asignar1(this.form);"/><? }?>
     No<? if($f['FlagsAnexo']=='N'){?><input type="radio" id="anexsi2" name="anexsi2" value="N" checked="checked"/>
     <? }else{?><input type="radio" id="anexsi2" name="anexsi2" onclick="asignar2(this.form);"/><? }?></td>
</tr>
<tr>
  <td class="tagForm">Descripci&oacute;n:</td>
  <td colspan="2"><textarea name="descrip" id="descrip" rows="2" cols="80"><?=$f['Descripcion']?></textarea></td>
  <td><? if($f['FlagsAnexo']=='S'){?>
           <textarea name="anexDescp" id="anexDescp" rows="2" cols="80" style="visibility:visible"><?=$f['DescripcionAnexo']?></textarea>
      <? }else{?>
      <textarea name="anexDescp" id="anexDescp" rows="2" cols="80" style="visibility:hidden"><?=$f['DescripcionAnexo']?></textarea>
      <? }?>
      </td>
</tr>
<tr><td height="5"></td></tr>
<tr>
 <td colspan="4"><div class="cellText" align="center"><b>Remitente</b></div></td>
 <!--<td colspan="4"><div class="divBorder" style="border-color:#999999;"></div></td>-->
</tr>
<tr><td height="5"></td></tr>
 <tr>
    <td class="tagForm">Organismo:</td>
    <td colspan="3"><select id="organismo" name="organismo" class="selectBig" onchange="getOrganismoInterno(this.id, 'dep_interna');" disabled>
      <option value=""></option>
      <? getOrganismos(0, $_SESSION['ORGANISMO_ACTUAL']); ?>
    </select>
      *</td>
  </tr>
  <tr>
    <td class="tagForm" colspan="">Dependencia:</td>
    <td colspan="3"><select id="dep_interna" name="dep_interna" class="selectBig" disabled>
      <option value=""></option>
      <? getDependencias($f['Cod_Dependencia'], $_SESSION['ORGANISMO_ACTUAL'], 0); ?>
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
			      mp.CodPersona = '".$f['Cod_Remitente']."'  and
				  rp.CodCargo =  '".$f['Cod_CargoRemitente']."' ";
	 $qa = mysql_query($sa) or die ($sa.mysql_error());
	 $fa = mysql_fetch_array($qa);
	?>
    <td class="tagForm">Representante:</td>
    <td><input type="text" id="destinatario_int" name="destinatario_int" size="68" value="<?=htmlentities($fa['Representante'])?>" readonly/></td>
    <td class="tagForm">Cargo:</td>
    <td><input type="text" id="cargodestinatario_int" name="cargodestinatario_int" size="68" value="<?=$fa['Cargo']?>" readonly/>
        <input type="hidden" id="codigo_interno" name="codigo_interno" value="<?=$f['CodInterno']?>"/>
        <input type="hidden" id="codigo_persona" name="codigo_persona" value="<?=$f['Cod_Remitente']?>"/>
        <input type="hidden" id="codigo_cargo" name="codigo_cargo" value="<?=$f['Cod_CargoRemitente']?>"/></td>
  </tr>
<tr><td height="5"></td></tr>
<tr>
 <td colspan="4">
  <table align="center" width="400">
  <tr>
     <td width="92" class="tagForm">Ultima Modif.:</td>
     <td width="296" colspan="2"><input type="text" id="ultimousuario" size="20" value="<?=$f['UltimoUsuario']?>" readonly="readonly"/><input type="text" id="ultimafecha" size="20" value="<?=$f['UltimaFechaModif']?>" readonly="readonly"/></td>
  </tr>
  </table>
 </td>
</tr>
</table> 
<center>
    <input name="guardar" type="submit" id="guardar" value="Guardar Registro"/>
    <input name="cancelar" type="button" id="cancelar" value="Cancelar" onClick="cargarPagina(this.form,'cpi_docinternoslista.php?limit=0');" />
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
  <td colspan="8">
  <table width="800" class="tblBotones">
   <tr>
 	<td align="right">
    <input type="button" class="btLista" id="btInsertarDependencia" value="Ins. Dep." onclick="cargarVentana(this.form,'lista_dependencias.php?limit=0&ventana=insertarDestinatarioDep&tabla=item', 'height=600, width=800, left=50, top=50, resizable=yes');" />
    <input type="button" class="btLista" id="btInsertarEmpleado" value="Ins. Emp." onclick="cargarVentana(this.form,'lista_empleados.php?limit=0&ventana=insertarDestinatarioEmp&tabla=item', 'height=600, width=800, left=50, top=50, resizable=yes');" />
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
       <tr>
         <th scope="col" class="trListaHead">Dependencia</th>
        <th scope="col" class="trListaHead">Representante/Empleado</th>
        <th scope="col" class="trListaHead">Cargo</th>
         <th scope="col" class="trListaHead">CC</th>
        </tr>
       <tbody id="listaDetalles">
     <? 
	   $sdist="select * 
	            from 
				     cp_documentodistribucion 
			   where 
			         Cod_Documento='".$f['Cod_DocumentoCompleto']."' and 
					 Cod_TipoDocumento='$cod_tipodocumento' and 
					 Procedencia = 'INT'";
	   $qdist=mysql_query($sdist) or die ($sdist.mysql_error()); //echo $sdisext;
	   $rdist= mysql_num_rows($qdist); //echo $risext;
		
   	 if($rdist!=0){
	   for($i=0; $i<$rdist; $i++){
	      $fdist = mysql_fetch_array($qdist);
		  $sc = "SELECT
					   md.Dependencia,
					   mp.NomCompleto,
					   rp.DescripCargo,
					   mp.CodPersona,
					   md.CodDependencia,
					   rp.CodCargo
				  FROM 
					   mastdependencias md,
					   mastpersonas mp,
					   rh_puestos rp
				 WHERE 
					   md.CodDependencia ='".$fdist['CodDependencia']."' and 
					   mp.CodPersona = '".$fdist['CodPersona']."' and 
					   rp.CodCargo = '".$fdist['CodCargo']."'";
		 $qc = mysql_query($sc) or die ($sc.mysql_error());
		 if (mysql_num_rows($qc) != 0) $fc = mysql_fetch_array($qc);
		     ?>
		     <tr class="trListaBody" onclick="mClk(this, 'seldetalle');" id="<?=$fc['3']?>">
                <td><input type="hidden" name="codpersona" value="<?=$fc[3]?>"/><?=htmlentities($fc[0]);?></td>
                <td><input type="hidden" name="cod_dependencia" value="<?=$fc[4]?>"/><?=htmlentities($fc[1]);?></td>
                <td><input type="hidden" name="cargo" value="<?=$fc[5]?>"/><?=htmlentities($fc[2]);?></td>
                <td><input type="hidden" name="ccp" value="<?=$fdist['CC']?>"/>
                <input type="checkbox" name="cc" id="cc" value="<?=$fdist['CC']?>" onClick="if(this.value=='N') this.value='S'; else if(this.value=='S') this.value='N'; "> 
                </td>
		     </tr>
             <?
		 }
	   }
      ?>
       </tbody>
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
