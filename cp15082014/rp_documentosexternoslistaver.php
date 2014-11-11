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
		<td class="titulo">Documentos de Salida | Ver Registro</td>
		<td align="right"><a class="cerrar"; href="" onclick="window.close();">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />
<?

 list($cod_dcompleto, $cod_documento, $cod_tdocumento, $periodo)= SPLIT('[|]',$_GET['registro']);
 
 
 $year=date("Y");
 $scon="select * from cp_documentoextsalida where Cod_DocumentoCompleto='$cod_dcompleto'";
 $qcon=mysql_query($scon) or die ($scon.mysql_error()); //echo $scon;
 $rcon=mysql_num_rows($qcon);
 $fcon=mysql_fetch_array($qcon);
?>

<div id="tab1" style="display:block;">
<form name="frmentrada" id="frmentrada" action="cp_salidaeditar.php" method="post"  onsubmit="return guardarSalidaEditar();" >
<div style="width:895px; height:15px" class="divFormCaption">Datos del Documento</div>
<table class="tblForm" width="895px" border="0">
  <tr>
    <td height="5"></td>
  </tr>
  <tr>
    <td width="125" class="tagForm">Tipo Documento:</td>
    <td width="321"><? 
	  echo"<input type='hidden' id='regresar' name='regresar' value='".$regresar."'/>";
	  $sql="SELECT * FROM cp_tipocorrespondencia WHERE FlagUsoExterno='1' AND Cod_TipoDocumento='".$fcon['Cod_TipoDocumento']."'";
	  $qry=mysql_query($sql) or die ($sql.mysql_error());
	  $row=mysql_num_rows($qry);?>
        <select name="t_documento" id="t_documento" class="selectMed" disabled>
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
    <td width="325"><input type="text" id="fecha_documento" name="fecha_documento" size="10" value="<?=$f_registro;?>" style="text-align:right" disabled/>
      *(dd-mm-aaaa)</td>
  </tr>
  <tr style="border-style:double">
    <td class="tagForm">Nro. Documento:</td>
    <td><input type="text" id="n_documento" name="n_documento" size="22" value="<?=$fcon['Cod_DocumentoCompleto']?>" style="text-align:right" disabled/>
      *</td>
    <!--<td class="tagForm">Fecha Recibido:</td>
 <td><input type="text" id="fecha_recibido" name="fecha_recibido" size="10" maxlength="10" value="<?=$fecha;?>"/>*(dd-mm-aaaa)</td>
</tr>-->
  </tr>
  <tr>
    <td class="tagForm">Asunto:</td>
    <td colspan="1"><input type="text" id="asunto" name="asunto" size="60" value="<?=$fcon['Asunto']?>" disabled/>
      *</td>
  </tr>
  <tr>
    <td class="tagForm">Descripci&oacute;n:</td>
    <td colspan="3"><textarea name="asunto" id="asunto" rows="2" cols="80" disabled><?=$fcon[Descripcion]?></textarea></td>
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
      <? getOrganismos(0, $fcon['CodOrganismo']); ?>
    </select>
      *</td>
  </tr>
  <tr>
    <td class="tagForm" colspan="">Dependencia:</td>
    <td colspan="3"><select id="dep_interna" name="dep_interna" class="selectBig" disabled>
      <option value=""></option>
      <? getDependencia($fcon['Cod_Dependencia'], $fcon['CodOrganismo'], 4); ?>
    </select>
      *</td>
  </tr>
  <tr>
    <?
     $sa = "select 
	              mp.NomCompleto,
				  rp.DescripCargo
			  from
			      mastpersonas mp,
				  rh_puestos rp
			 where
			      mp.CodPersona = '".$fcon['Remitente']."'  and
				  rp.CodCargo =  '".$fcon['Cargo']."' ";
	 $qa = mysql_query($sa) or die ($sa.mysql_error());
	 $fa = mysql_fetch_array($qa);
	?>
    <td class="tagForm">Representante:</td>
    <td><input type="text" id="remitente_int" name="remitente_int" size="60" value="<?=$fa['0']?>" disabled/></td>
    <td class="tagForm">Cargo:</td>
    <td><input type="text" id="cargoremitente_int" name="cargoremitente_int" size="60" value="<?=$fa['1']?>" disabled/></td>
  </tr>
  <tr>
    <td height="5"></td>
  </tr>
  <tr>
    <td colspan="4"><table align="center" width="400">
      <tr>
        <td width="92" class="tagForm">Ultima Modif.:</td>
        <td width="296" colspan="2"><input type="text" id="ultimousuario" size="20" disabled value="<?=$fcon['UltimoUsuario']?>"/>
              <input type="text" id="ultimafecha" size="20" disabled value="<?=$fcon['UltimaFechaModif']?>"/></td>
      </tr>
    </table></td>
  </tr>
</table>
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
    <input type="button" class="btLista" id="btInsertarDependencia" value="Ins. Dep." onclick="cargarVentana(this.form,'lista_dependenciasext.php?limit=0&ventana=insertarDestinatarioDepExt&tabla=item', 'height=600, width=800, left=50, top=50, resizable=yes');" disabled/>
    <input type="button" class="btLista" id="btInsertarEmpleado" value="Ins. Org." onclick="cargarVentana(this.form,'lista_organismosext.php?limit=0&ventana=insertarDestinatarioOrgExt&tabla=item', 'height=600, width=800, left=50, top=50, resizable=yes');" disabled/>
    <input type="button" class="btLista" id="btInsertarParticular" value="Ins. Par." onclick="cargarVentana(this.form,'lista_particulares.php?limit=0&ventana=insertarDestinatarioParticularExt&tabla=item', 'height=600, width=800, left=50, top=50, resizable=yes');" disabled/>
    <input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="quitarLineaDestinatario(document.getElementById('seldetalle').value);" disabled/>
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
	   
	   $sdisext="select * from 
	                          cp_documentodistribucionext 
					     where 
						      Cod_Documento='".$fcon['Cod_Documento']."' and 
							  Periodo ='".$fcon['Periodo']."' and 
							  CodOrganismo = '".$fcon['CodOrganismo']."' and 
							  Cod_TipoDocumento = '".$fcon['Cod_TipoDocumento']."'";
	   $qdisext=mysql_query($sdisext) or die ($sdisext.mysql_error()); //echo $sdisext;
	   $risext= mysql_num_rows($qdisext); //echo $risext;
		
		if($risext!=0){
		   
		  for($i=0; $i<$risext; $i++){
		    $fdisext = mysql_fetch_array($qdisext);
			 
			if($fdisext['FlagEsParticular']=='N'){ 
			 if($fdisext['Cod_Dependencia']==''){ 
			  $sc = "SELECT
						   Organismo,
						   RepresentLegal,
						   Cargo
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
		     <tr>
             <td align="center" width="20">PARTICULAR</td>
             <td align="left" width="70"><?=$fdisext['Representante'];?></td>
             <td align="left" width="70"><?=$fdisext['Cargo'];?></td>
		     </tr>
             
			 <? }else{?>
			 
		     <tr>
             <td><?=htmlentities($fc[0]);?></td>
             <td><?=htmlentities($fdisext['Representante']);?></td>
             <td><?=htmlentities($fdisext['Cargo']);?></td>
		     </tr>
		     <? }
		
		  } }?>

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
</body>
</html>