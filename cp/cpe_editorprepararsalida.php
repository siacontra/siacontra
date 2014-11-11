<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp.php");
connect();
include "ControlCorrespondencia.php";
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('03', $concepto);
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
<script type="text/javascript" language="javascript" src="ckeditor/sample.js"></script>
<link href="ckeditor/sample.css" rel="stylesheet" type="text/css" />

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
		<td class="titulo">Editor de Documentos</td>
		<td align="right"><a class="cerrar" href="framemain.php"  onclick="cargarPagina(document.getElementById('frmentrada'),'cpe_salidalista.php?&limit=0')";>[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?
 $s="select * from cp_documentoextsalida where Cod_DocumentoCompleto='".$registro."' and Periodo = '".date("Y")."'";
 $q=mysql_query($s) or die ($s.mysql_error());
 if(mysql_num_rows($q)!=0){ 
   $f=mysql_fetch_array($q);
 }
?>

<form name="framemain" action="cpe_salidapreparar.php?limit=0&accion=guardarContenido" method="post">
<div style="width:895px; height:15px" class="divFormCaption">Datos Documento</div>
<table class="tblForm" width="895px" border="0">
<tr><td height="5"></td></tr>
<tr>
  <td>
  <table class="tblForm" width="880px">
  <tr>
     <td width="123" class="tagForm">Tipo Documento:</td>
     <td width="296">
          <? 
			  echo"<input type='hidden' id='cod_documento' name='cod_documento' value='".$f['Cod_Documento']."'/>";
			  echo"<input type='hidden' id='regresar' name='regresar' value='".$regresar."'/>";
			  $sql="SELECT * FROM cp_tipocorrespondencia WHERE FlagUsoExterno='1'  AND Cod_TipoDocumento='".$f['Cod_TipoDocumento']."'";
			  $qry=mysql_query($sql) or die ($sql.mysql_error());
			 
			  if(mysql_num_rows($qry)!=0){ $field=mysql_fetch_array($qry);};
		  ?>
          <input name="t_documento" id="t_documento" value="<?=$field['Descripcion']?>" size="20"  readonly/>   </td>
     <td width="122" class="tagForm">Fecha Documento:</td>
         <? 
            if($f['FechaRegistro']!='0000-00-00'){list($a,$m,$d)=SPLIT('[/.-]',$f['FechaRegistro']);$f_registro=$d.'-'.$m.'-'.$a;} 
         ?>
     <td width="319"><input type="text" id="fecha_documento" name="fecha_documento" size="10" value="<?=$f_registro;?>" style="text-align:right"/>*(dd-mm-aaaa)</td>
  </tr>
  </table>
  </td>
</tr>
<tr>
 <td>
 <table width="880px" border="0">
 <tr>
     <td>
     <table class="tblForm" width="425px">
     <tr>
       <td class="tagForm">Nro. Documento:</td>
       <td><input type="text" id="ndoc_completo" name="ndoc_completo" size="22" value="<?=$f['Cod_DocumentoCompleto']?>" style="text-align:right" readonly/></td>
     </tr>
     <tr>
       <td class="tagForm">Asunto:</td>
       <td><input type="text" name="asunto" id="asunto" value="<?=$f['Asunto']?>" size="68" readonly/></td>
     </tr>
    <tr><td height="5"></td></tr>
 <tr>
   <?
    $scon="select
                mp.NomCompleto,
				rh.DescripCargo,
				me.CodDependencia,
				md.Dependencia				 
           from
		        mastpersonas mp
				inner join mastempleado me on (mp.CodPersona=me.CodPersona)
				inner join rh_puestos rh on (me.CodCargo=rh.CodCargo)
				inner join mastdependencias md on (me.CodDependencia = md.CodDependencia)
		  where 
		        mp.CodPersona='".$f['Remitente']."'"; 
    $qcon=mysql_query($scon) or die ($scon.mysql_error()); //echo $scon;

    if(mysql_num_rows($qcon)!=0){ $fcon=mysql_fetch_array($qcon); }
   ?>
    <td class="tagForm">Dependencia:</td>
    <td><input name="coddependencia" type="hidden" id="coddependencia" value="<?=$fcon[2]?>"/>
	   <input name="dependencia" id="dependencia" type="text" size="68" value="<?=htmlentities($fcon[3])?>" readonly/></td>
  </tr>
  <tr>
    <td class="tagForm">Representante:</td>
    <td><input name="codpersona" type="hidden" id="codpersona" value="<?=$_POST['registro']?>"/>
	 <input name="nomempleado" id="nomempleado" type="text" size="68" value="<?=utf8_encode($fcon[0])?>" readonly/></td>
  </tr>
  <tr>
    <td class="tagForm">Cargo:</td>
    <td><input type="text" id="cargoremit" name="cargoremit" value="<?=$fcon[1]?>"size="68"  readonly/></td>
  </tr>
  <tr>
    <td class="tagForm">Iniciales:</td>
    <td><input type="text" id="iniciales" name="iniciales"  size="5" maxlength="5"/></td>
  </tr>
  <tr><td height="1"></td></tr>
 </table>
    </td>
    <td>
    <table class="tblForm" width="435px">
    <tr>
      <td>
       <table align="center" width="435px">
    <tr>
      <td align="center"><div style="overflow:scroll; height:119px; width:435px;">
      <table width="435px" class="tblLista">
       <tr class="trListaHead">
        <th>Organismo/Dependencia/Particular</th>
        <th>Destinatario</th>
        </tr>
       <tbody id="listaDetalles">
	   <? 
	   
	   $sdisext="select * from cp_documentodistribucionext where Cod_Documento='".$f['Cod_Documento']."' and Periodo ='".date("Y")."'";
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
             <td align="left" width="20">PARTICULAR</td>
             <td align="left" width="70"><?=$fdisext['Representante'];?></td>
		     </tr>
             
			 <? }else{?>
			 
		     <tr>
             <td><?=htmlentities($fc[0]);?></td>
             <td><?=htmlentities($fc[1]);?></td>
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
    </td>
 </tr>
 </table>
 </td>
</tr>
  <tr>
     <td colspan="4"><div class="divFormCaption" align="center"><b></b></div></td>
  </tr>
  <tr>
     <td colspan="4">
		<p>
		  <textarea class="ckeditor" cols="80" id="editor1" name="editor1" rows="10"></textarea>
		</p>
    </td>
  </tr>
</table>
<center>
    <input type="submit" value="Guardar Registro"/><input type="button" value="Cancelar" onClick="cargarPagina(this.form,'cpe_salidapreparar.php?limit=0');" />
</center>
</form>
</body>
</html>
