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
		<td class="titulo">Documentos Enviados | Ver</td>
		<td align="right"><a class="cerrar"; href="" onclick="window.close();">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />
<?
list($cod_tipodocumento, $cod_documentocompleto, $codpersona, $periodo, $codorganismo) = split('[|]',$_GET['registro'] );

$year= date("Y");
 $sql="select 
              * 
         from 
		      cp_documentointerno 
		where 
		      Cod_DocumentoCompleto = '$cod_documentocompleto' and 
			  Periodo = '$periodo' and
			  CodOrganismo = '$codorganismo' and 
			  Cod_TipoDocumento = '$cod_tipodocumento' ";
 $qry=mysql_query($sql) or die ($sql.mysql_error());
 $field=mysql_fetch_array($qry);
// echo $sql;
?>

<form name="frmentrada" id="frmentrada">
<div style="width:795px; height:15px" class="divFormCaption">Datos Documento</div>
<table class="tblForm" width="795px" border="0">
<tr><td height="5"></td></tr>
<tr>
  <td width="156" class="tagForm">Tipo Documento:</td>
  <?
  $stdoc="select 
                Descripcion 
		    from 
			    cp_tipocorrespondencia
		   where
			    Cod_TipoDocumento='".$field['Cod_TipoDocumento']."'";
  $qtdoc=mysql_query($stdoc) or die ($stdoc.mysql_error());
  $ftdoc=mysql_fetch_array($qtdoc);
  ?>
  <td width="240"><input type="text" id="tipodocumento" value="<?=utf8_encode($ftdoc['Descripcion']);?>" readonly/>*</td>
   <td width="114" class="tagForm">Fecha Documento:</td>
 <? 
   if($field['FechaRegistro']!='0000-00-00'){list($a,$m,$d)=SPLIT('[/.-]',$field['FechaRegistro']);$fRegistro=$d.'-'.$m.'-'.$a;}
 ?>
 <td width="265"><input type="text" id="fecha_documento" name="fecha_documento" size="10" value="<?=$fRegistro;?>" readonly/>*(dd-mm-aaaa)</td>
</tr>
<tr style="border-style:double">
 <td class="tagForm">Nro. Documento:</td>
 <td><input type="text" id="n_documento" name="n_documento" size="22" value="<?=$field['Cod_DocumentoCompleto'];?>" readonly/>*</td>
 <td class="tagForm">Fecha Recibido:</td>
  <? 
   if($field['FechaDocumentoExt']!='0000-00-00'){list($a,$m,$d)=SPLIT('[/.-]',$field['FechaDocumentoExt']);$fRegistroExt=$d.'-'.$m.'-'.$a;}
 ?>
 <td><input type="text" id="fecha_recibido" name="fecha_recibido" size="10" maxlength="10" value="<?=$fRegistroExt;?>" readonly/>*(dd-mm-aaaa)</td>
</tr>
<tr><td height="5"></td></tr>
<tr>
 <td colspan="4"><div class="divBorder" style="border-color:#999999;"></div></td>
</tr>
<tr><td height="5"></td></tr>
<tr>
  <td class="tagForm">Organismo:</td>
  <?
   $sqlOrg="select 
                  mo.Organismo,
				  md.Dependencia,
				  mp.NomCompleto,
				  rp.DescripCargo
              from 
			      mastorganismos  mo, 
				  mastdependencias md,
				  mastpersonas mp,
				  rh_puestos rp 
			 where 
			     mo.CodOrganismo='".$field['CodOrganismo']."' and
				 md.CodDependencia='".$field['Cod_Dependencia']."' and
				 mp.CodPersona = '".$field['Cod_Remitente']."' and
				 rp.CodCargo = '".$field['Cod_CargoRemitente']."'";
				// echo $sqlOrg;
   $qryOrg=mysql_query($sqlOrg) or die ($sqlOrg.mysql_error());
   $fconsulta=mysql_fetch_array($qryOrg);
 ?> 
  <td colspan="3"><input type="text" id="organismo" name="organismo" class="selectBig" value="<?=utf8_encode($fconsulta['0']);?>" readonly></td>
</tr>
<tr>
  <td class="tagForm">Dependencia:</td>
  <td colspan="2"><input type="text" id="dependencia" name="dependencia" class="selectBig" value="<?=utf8_encode($fconsulta['1']);?>" readonly></td>
</tr>
<tr>
 <td class="tagForm">Remitente:</td>
 <td><input type="text" id="remitente" name="remitente" size="60" value="<?=utf8_encode($fconsulta['2']);?>" readonly/></td>
 <td class="tagForm">Cargo:</td>
 <td><input type="text" id="cargo" name="cargo" size="40" value="<?=utf8_encode($fconsulta['3']);?>" readonly/></td>
</tr>
<tr><td height="5"></td></tr>
<tr>
  <td class="tagForm">Asunto Tratado:</td>
  <td colspan="2"><input type="text" id="asunto" name="asunto" size="60" value="<?=utf8_encode($field['Asunto']);?>" readonly/>*</td>
</tr>
<tr>
  <td class="tagForm">Descripci&oacute;n:</td>
  <td colspan="3"><textarea name="asunto" id="asunto" rows="2" cols="80" readonly><?=utf8_encode($field['Descripcion']);?></textarea></td>
</tr>
<tr>
  <td class="tagForm"></td>
  <td colspan="3"></td>
</tr>
<tr><td height="5"></td></tr>
<tr>
 <td colspan="4">
  <table align="center" width="400">
  <tr>
     <td width="92" class="tagForm">Ultima Modif.:</td>
     <td width="296" colspan="2"><input type="text" id="ultimousuario" size="20" value="<?=$field['UltimoUsuario']?>" readonly/>
                                 <input type="text" id="ultimafecha" size="20" value="<?=$field['UltimaFechaModif']?>"readonly/></td>
  </tr>
  </table>
 </td>
</tr>
</table> 
</form> 
</div>

<!-- <div id="tab2" style="display:none;">
<div style="width:800px; height:15px" class="divFormCaption">Detalle</div>
<form name="frmdetalles" id="frmdetalles">
<input type="hidden" id="seldetalle"/>
<?
 if($field['FlagInformeEscrito']==1){$a='checked onclick="this.checked=!this.checked"';}else{$a='disabled="disabled"';}
 if($field['FlagHablarConmigo']==1){$b='checked onclick="this.checked=!this.checked"';}else{$b='disabled="disabled"';}
 if($field['FlagCoordinarcon']==1){$c='checked onclick="this.checked=!this.checked"';}else{$c='disabled="disabled"';}
 if($field['FlagPrepararMemo']==1){$d='checked onclick="this.checked=!this.checked"';}else{$d='disabled="disabled"';}
 if($field['FlagInvestigarInformar']==1){$e='checked onclick="this.checked=!this.checked"';}else{$e='disabled="disabled"';}
 if($field['FlagTramitarConclusion']==1){$f='checked onclick="this.checked=!this.checked"';}else{$f='disabled="disabled"';}
 if($field['FlagDistribuir']==1){$g='checked onclick="this.checked=!this.checked"';}else{$g='disabled="disabled"';}
 if($field['FlagConocimiento']==1){$h='checked onclick="this.checked=!this.checked"';}else{$h='disabled="disabled"';}
 if($field['FlagPrepararConstentacion']==1){$i='checked onclick="this.checked=!this.checked"';}else{$i='disabled="disabled"';}
 if($field['FlagArchivar']==1){$j='checked onclick="this.checked=!this.checked"';}else{$j='disabled="disabled"';}
 if($field['FlagRegistrode']==1){$k='checked onclick="this.checked=!this.checked"';}else{$k='disabled="disabled"';}
 
 if($field['FlagPrepararOficio']==1){$l='checked onclick="this.checked=!this.checked"';}else{$l='disabled="disabled"';}
 if($field['FlagConocerOpinion']==1){$m='checked onclick="this.checked=!this.checked"';}else{$m='disabled="disabled"';}
 if($field['FlagTramitarloCaso']==1){$n='checked onclick="this.checked=!this.checked"';}else{$n='disabled="disabled"';}
 if($field['FlagAcusarRecibo']==1){$o='checked onclick="this.checked=!this.checked"';}else{$o='disabled="disabled"';}
 if($field['FlagTramitarEn']==1){$p='checked onclick="this.checked=!this.checked"';}else{$p='disabled="disabled"';}
 
?>
<table width="800px" class="tblForm">
<tr>
  <td><? echo"<input type='checkbox' name='infor_escrito' $a/>";?></td>
  <td align="left">Informarme por escrito</td>
  <td><? echo"<input type='checkbox' name='inv_inforver' $b/>";?></td>
  <td align="left">Investigar e informar verbalmente</td>
  <td><? echo"<input type='checkbox' name='pre_contfirm' $c/>";?></td>
  <td align="left">Preparar contestacion para mi firma</td>
  <td><? echo"<input type='checkbox' name='conocer_opinion' $d/>";?></td>
  <td align="left">Para conocer su opinion</td>
</tr>
<tr>
  <td><? echo"<input type='checkbox' name='hablar_alrespecto' $e/>";?></td>
  <td align="left">Hablar conmigo al respecto</td>
  <td><? echo"<input type='checkbox' name='tram_conclusion' $f/>";?></td>
  <td align="left">Tramitar hasta su conclusi&oacute;n</td>
  <td><? echo"<input type='checkbox' name='archivar' $g/>";?></td>
  <td align="left">Archivar</td>
  <td><? echo"<input type='checkbox' name='tram_casoproceden' $h/>";?></td>
  <td align="left">Tramitar en caso de proceder</td>
</tr>
<tr>
  <td><? echo"<input type='checkbox' name='coord_con' $i/>";?></td>
  <td align="left">Coordinar con:<input type="text" id="coord_con2" name="coord_con2" size="30"/></td>
  <td><? echo"<input type='checkbox' name='distribuir' $j/>";?></td>
  <td align="left">Distribuir</td>
  <td><? echo"<input type='checkbox' name='registro_de' $k/>";?></td>
  <td align="left">Registro de:<input type="text" id="registro_de2" name="registro_de2" size="30"/></td>
  <td><? echo"<input type='checkbox' name='acusar_recibo' $l/>";?></td>
  <td align="left">Acusa recibo</td>
</tr>
<tr>
  <td><? echo"<input type='checkbox' name='pre_memo' $m/>";?></td>
  <td align="left">Prepara memo a:<input type="text" id="pre_memo2" name="pre_memo2" size="30"/></td>
  <td><? echo"<input type='checkbox' name='pconocimiento_fp' $n/>";?></td>
  <td align="left">Para su conocimiento y fines pertinentes</td>
  <td><? echo"<input type='checkbox' name='prep_oficio' $o/>";?></td>
  <td align="left">Preparar oficio a:<input type="text" id="prep_oficio2" name="prep_oficio2" size="30"/></td>
  <td><? echo"<input type='checkbox' name='tram_dias' $p/>";?></td>
  <td align="left">Tramitar en <input type="text" id="tram_dias2" name="tram_dias2" size="2"/> dias</td>
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
  <table width="500" class="tblBotones">
   <tr>
 	<td align="right">
    <input type="button" class="btLista" id="btInsertarItem" value="Insertar" onclick="cargarVentana(this.form, 'lista_empleados.php?limit=0&ventana=insertarDestinatario&tabla=item', 'height=600, width=800, left=50, top=50, resizable=yes');" />
    <input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="quitarLineaDestinatario(document.getElementById('seldetalle').value);" />
	</td>
  </tr>
  </table>
  </td>
</tr>
</table>
@@@@@@@@@@@@@@@@@@@@@@@@ LISTA A MOSTRAR @@@@@@@@@@@@@@@@@@@@@@@@ 
<table align="center" cellpadding="0" cellspacing="0">
<tr>
  <td valign="top" style="height:300px; width:400px;">
    <table align="center" width="400px">
    <tr>
      <td align="center"><div style="overflow:scroll; height:300px; width:500px;">
      <table width="500px" class="tblLista">
       <tr>
        <th scope="col" align="left"></th>
        </tr>
       <tbody id="listaDetalles"></tbody>
      </table>
      </div></td>
   </tr>
  </table>
</td>
</tr>
</table>   
</div>
</form>-->
</body>
</html>