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
		<td class="titulo">Documentos Externos | Nuevo Registro</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />
<table width="800" align="center">
<tr>
  <td>
	<div id="header">
	<ul>
	<!-- CSS Tabs PESTAÑAS OPCIONES DE PRESUPUESTO -->
	<li><a onClick="document.getElementById('tab1').style.display='block'; document.getElementById('tab2').style.display='none';" href="#">Datos Remitente</a></li>
	<li><a onClick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='block';" href="#">Documento Enviado</a></li> 
	</ul>
	</div>
  </td>
</tr>
</table>

<div id="tab1" style="display:block;">
<form name="frmentrada" id="frmentrada" action="cp_entradaextnuevo.php" method="post"  onsubmit="return guardar();" >
<div style="width:795px; height:15px" class="divFormCaption">Datos Generales</div>
<table class="tblForm" width="795px" border="0">
<tr><td height="5"></td></tr>
<tr>
  <td width="156" class="tagForm">Tipo Documento:</td>
  <td width="240">
     <? 
	  $sql="SELECT * FROM cp_tipocorrespondencia WHERE FlagProcedenciaExterna='1'";
	  $qry=mysql_query($sql) or die ($sql.mysql_error());
	  $row=mysql_num_rows($qry);?>
      <select name="t_documento" id="t_documento" class="selectMed">
        <option value=""></option>
        <?
      for($i; $i<$row; $i++){
	    $field=mysql_fetch_array($qry);?>
        <option value="<?=$field['Cod_TipoDocumento'];?>">
          <?=$field['Descripcion'];?>
          </option>
        <? }?>
      </select>*</td>
   <td width="114" class="tagForm">Fecha Documento:</td>
 <? $fecha=date("d-m-Y");?>
 <td width="265"><input type="text" id="fecha_documento" name="fecha_documento" size="10" value="<?=$fecha;?>"/>*(dd-mm-aaaa)</td>
</tr>
<tr style="border-style:double">
 <td class="tagForm">Nro. Documento:</td>
 <td><input type="text" id="n_documento" name="n_documento" size="20"/>*</td>
 <td class="tagForm">Fecha Recibido:</td>
 <td><input type="text" id="fecha_recibido" name="fecha_recibido" size="10" maxlength="10" value="<?=$fecha;?>"/>*(dd-mm-aaaa)</td>
</tr>
<tr><td height="5"></td></tr>
<tr>
 <td colspan="4"><div class="divBorder" style="border-color:#999999;"></div></td>
</tr>
<tr><td height="5"></td></tr>
<tr>
  <td class="tagForm">Organismo:</td>
  <td colspan="3"><select id="organismo" name="organismo" class="selectMed" >
        <option value=""></option>
      </select>*</td>
</tr>
<tr>
  <td class="tagForm">Dependencia:</td>
  <td><select id="dependencia" name="dependencia" class="selectMed">
      </select>*</td>
</tr>
<tr>
 <td class="tagForm">Remitente:</td>
 <td><input type="text" id="remitente" name="remitente" size="60"/></td>
 <td class="tagForm">Cargo:</td>
 <td><input type="text" id="cargo" name="cargo" size="40"/></td>
</tr>
<tr>
 <td class="tagForm">Dirigido a:</td>
 <td><select id="dirigido" name="dirigido" class="selectMed">
      <option value=""></option>
      <option value="0001">Despacho del Contralor</option>
     </select>*</td>
</tr>
<tr><td height="5"></td></tr>
<tr>
  <td class="tagForm">Asunto Tratado:</td>
  <td colspan="2"><input type="text" id="asunto" name="asunto" size="60"/>*</td>
</tr>
<tr>
 <td class="tagForm">Descripci&oacute;n:</td>
 <td colspan="2"><input type="text" id="descrip" name="descrip" size="60"/>*</td>
</tr>
<tr>
  <td class="tagForm">Comentario:</td>
  <td colspan="3"></td>
</tr>
<tr>
  <td class="tagForm"></td>
  <td colspan="3"><textarea name="asunto" id="asunto" rows="2" cols="80"></textarea></td>
</tr>
<tr><td height="5"></td></tr>
<tr>
 <td colspan="4">
  <table align="center" width="400">
  <tr>
     <td width="92" class="tagForm">Ultima Modif.:</td>
     <td width="296" colspan="2"><input type="text" id="ultimousuario" size="20" readonly="readonly"/><input type="text" id="ultimafecha" size="20" readonly="readonly"/></td>
  </tr>
  </table>
 </td>
</tr>
</table> 
</form> 
</div>

<div id="tab2" style="display:none;">
<div style="width:800px; height:15px" class="divFormCaption">Detalle</div>
<form name="frmdetalles" id="frmdetalles">
<input type="hidden" id="seldetalle"/>
<table width="800px" class="tblForm">
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
  <td><input type="checkbox" id="coord_con" name="coord_con" value="1"/></td>
  <td align="left">Coordinar con:<input type="text" id="coord_con2" name="coord_con2" size="30"/></td>
  <td><input type="checkbox" id="distribuir" name="distribuir" value="1"/></td>
  <td align="left">Distribuir</td>
  <td><input type="checkbox" id="registro_de" name="registro_de" value="1"/></td>
  <td align="left">Registro de:<input type="text" id="registro_de2" name="registro_de2" size="30"/></td>
  <td><input type="checkbox" id="acusar_recibo" name="acusar_recibo" value="1"/></td>
  <td align="left">Acusa recibo</td>
</tr>
<tr>
  <td><input type="checkbox" id="pre_memo" name="pre_memo" value="1"/></td>
  <td align="left">Prepara memo a:<input type="text" id="pre_memo2" name="pre_memo2" size="30"/></td>
  <td><input type="checkbox" id="pconocimiento_fp" name="pconocimiento_fp" value="1"/></td>
  <td align="left">Para su conocimiento y fines pertinentes</td>
  <td><input type="checkbox" id="prep_oficio" name="prep_oficio" value="1"/></td>
  <td align="left">Preparar oficio a:<input type="text" id="prep_oficio2" name="prep_oficio2" size="30"/></td>
  <td><input type="checkbox" id="tram_dias" name="tram_dias" value="1"/></td>
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
<!-- @@@@@@@@@@@@@@@@@@@@@@@@ LISTA A MOSTRAR @@@@@@@@@@@@@@@@@@@@@@@@ -->
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
<center>
    <input name="guardar" type="submit" id="guardar" value="Guardar Registro"/>
    <input name="cancelar" type="button" id="cancelar" value="Cancelar" onClick="cargarPagina(this.form, 'cpe_entrada.php?limit=0');" />
</center>
</form>
<div class="divMsj" style="width:795px;">Campos Obligatorios *</div>
</body>
</html>