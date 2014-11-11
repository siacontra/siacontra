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
<?
 echo "<input type='hidden' id='fEstado' name='fEstado' value='".$fEstado."'/>";
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Documentos de Salida | Env&iacute;o</td>
		<td align="right"><a class="cerrar"; href="cpe_salidaenvio.php?limit=0&fEstado=<?=$fEstado?>">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />
<?
  $det = explode(";", $detalles);
  $i=0;

  foreach ($det as $detalle) {
	list($cod_documento, $periodo, $secuencia, $tipo_documento, $CodOrganismo)=SPLIT( '[|]', $detalle);$i++;
    //echo"$cod_documento, $periodo, $secuencia";	
  }
 
 //echo "$secuencia";

 $sql="SELECT 
             cpdsal.Cod_TipoDocumento,
			 cpdsal.FechaDocumento,
			 cpdsal.Cod_DocumentoCompleto,
			 cpdsal.Asunto,
			 md.Dependencia,
			 md.CodDependencia,
			 mp.NomCompleto,
			 rp.DescripCargo,
			 cpdsal.Periodo
        FROM 
		     cp_documentodistribucionext cpdist
		     inner join cp_documentoextsalida cpdsal on ((cpdist.Cod_Documento=cpdsal.Cod_Documento) and 
			                                             (cpdist.Periodo=cpdsal.Periodo) and 
														 (cpdist.Cod_TipoDocumento = cpdsal.Cod_TipoDocumento) )
			 inner join mastdependencias md on (md.CodDependencia = cpdsal.Cod_Dependencia)
			 inner join mastpersonas mp on (mp.CodPersona = md.CodPersona)
			 inner join mastempleado me on (me.CodPersona = mp.CodPersona)
			 inner join rh_puestos rp on (rp.CodCargo = me.CodCargo)
	   WHERE 
	         cpdist.CodOrganismo = '$CodOrganismo' AND 
			 (cpdist.Estado='PE' or cpdist.Estado='DV') and 
			 cpdist.Cod_Documento = '$cod_documento' and
			 cpdist.Periodo = '$periodo' and 
			 cpdist.Cod_TipoDocumento='$tipo_documento'"; //echo $sql;
 $qry= mysql_query($sql)  or die ($sql.mysql_error());
 $field = mysql_fetch_array($qry);
?>

<form name="frmentrada" id="frmentrada" action="cpe_salidaenvio.php?limit=0&accion=guardarEnvio&fEstado=<?=$fEstado?>" method="post" onsubmit="return guardarEnvio(this);">
<div style="width:895px; height:15px" class="divFormCaption">Datos del Documento</div>
<table class="tblForm" width="895px" border="0">

 <input type="hidden" id="detalles" name="detalles" value="<?=$_GET['detalles']?>" />
<tr>
   <td>
   <table class="tblForm" width="880px">
   <tr>
     <td width="123" class="tagForm">Tipo Documento:</td>
     <td width="296">
          <? 
			  echo"<input type='hidden' id='regresar' name='regresar' value='".$regresar."'/>";
			   echo"<input type='hidden' id='periodo' name='periodo' value='".$field[8]."'/>";
			  $stdoc="SELECT * FROM cp_tipocorrespondencia WHERE FlagUsoExterno='1'  AND Cod_TipoDocumento='".$field[0]."'";
			  $qtdoc=mysql_query($stdoc) or die ($stdoc.mysql_error());
			 
			  if(mysql_num_rows($qtdoc)!=0){ $ftdoc=mysql_fetch_array($qtdoc);};
		  ?>
          <input name="t_documento" id="t_documento" value="<?=$ftdoc['Descripcion']?>" size="20"  readonly/></td>
     <td width="122" class="tagForm">Fecha Documento:</td>
         <? 
           list($a,$m,$d)=SPLIT('[/.-]',$field[1]);$f_documento=$d.'-'.$m.'-'.$a; 
         ?>
     <td width="319"><input type="text" id="fecha_documento" name="fecha_documento" size="10" value="<?=$f_documento;?>" style="text-align:right" readonly/>*(dd-mm-aaaa)</td>
   </tr>
   <tr>
    <td colspan="2"></td>
    <td class="tagForm">Fecha Env&iacute;o:</td>
    <td><input type="text" id="fecha_envio" name="fecha_envio" size="10" value="<?=date("d-m-Y")?>" style="text-align:right" readonly/>*(dd-mm-aaaa)</td>
   </tr>
  </table>
   </td>
</tr>
<tr><td height="10"></td></tr>
<tr>
  <td>
 <table width="880px" border="0">
 <tr>
   <td>
   <table class="tblForm" width="425px">
     <tr>
       <td class="tagForm">Nro. Documento:</td>
       <td><input type="text" id="ndoc_completo" name="ndoc_completo" size="22" value="<?=$field[2]?>" style="text-align:right" readonly/></td>
     </tr>
     <tr>
       <td class="tagForm">Asunto:</td>
       <td><input type="text" name="asunto" id="asunto" value="<?=$field['3']?>" size="68" readonly/></td>
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
    <td><input name="coddependencia" type="hidden" id="coddependencia" value="<?=$field[5]?>"/>
	   <input name="dependencia" id="dependencia" type="text" size="68" value="<?=utf8_encode($field[4])?>" readonly/></td>
    </tr>
    <tr>
      <td class="tagForm">Representante:</td>
      <td><input name="codpersona" type="hidden" id="codpersona" value="<?=$_POST['registro']?>"/>
	 <input name="nomb_remitente" id="nomb_remitente" type="text" size="68" value="<?=utf8_encode($field[6])?>" readonly/></td>
    </tr>
    <tr>
      <td class="tagForm">Cargo:</td>
      <td><input type="text" id="cargo_remit" name="cargo_remit" value="<?=$field[7]?>"size="68"  readonly/></td>
    </tr>
    <tr><td height="5"></td></tr>
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
	   
       $det = explode(";", $detalles);
	   $i=0;
	
	   foreach ($det as $detalle) {
		   list($cod_documento, $periodo, $secuencia)=SPLIT( '[|]', $detalle);$i++;
           //echo"$cod_documento, $periodo, $secuencia";	
		   
		   $sdisext="select * 
		               from 
					        cp_documentodistribucionext 
					  where 
					        Cod_Documento='$cod_documento' and 
							Periodo ='$periodo' and 
							Secuencia ='$secuencia'";
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
			 
			 $id = $cod_documento."|".$periodo."|".$secuencia;
			 if($fdisext['FlagEsParticular']=='S'){
				 $contador = $contador + 1; //echo "CONTADOR = ".$contador;
			 ?>
		     <tr>
             <td align="left" width="20"><input type="hidden" class="A" id="<?=$id?>" value="<?=$id?>"/>PARTICULAR</td>
             <td align="left" width="70"><?=$fdisext['Representante'];?></td>
		     </tr>
             
			 <? }else{
				  if($fdisext['FlagEsParticular']=='N'){
			         $contador = $contador + 1; //echo "CONTADOR = ".$contador;
			 ?>
			    
		     <tr>
             <td><input type="hidden" class="A" id="<?=$id?>"  value="<?=$id?>"/><?=htmlentities($fc[0]);?></td>
             <td><?=htmlentities($fc[1]);?></td>
		     </tr>
		     <? }}
		     
		  } }
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
   </td>
 </tr>
 </table>
 </td>
</tr>
<tr><td height="10"></td></tr>
<tr>
 <td>
 <table width="880px">
  <tr>
    <td class="tagForm">Responsable:</td>
    <td colspan="2"><input name="codempleado" type="hidden" id="codempleado" value="" />
        <input name="nomempleado" id="nomempleado" type="text" size="59" readonly/>
        <input name="bt_examinar" id="bt_examinar" type="button" value="..." onclick="cargarVentana(this.form, 'lista_empleados.php?limit=0&campo=4', 'height=500, width=800, left=200, top=200, resizable=yes');"/>*</td>
  </tr>
  <tr>
    <td class="tagForm">Cargo:</td>
    <td><input type="text" id="cargoremit" name="cargoremit" value="" size="68" readonly/>
        <input type="hidden" id="cod_cargoremit" name="cod_cargoremit" value="" readonly/></td>
  </tr>
 </table>
 </td>
</tr>
<tr><td height="10"></td></tr>
</table>
<center>
    <input name="guardar" type="submit" id="guardar" value="Guardar Registro"/>
    <input name="cancelar" type="button" id="cancelar" value="Cancelar" onClick="cargarPagina(this.form,'cpe_salidaenvio.php?limit=0&fEstado=<?=$fEstado?>');" />
</center> 
</form>
</div>
<div class="divMsj" style="width:795px;">Campos Obligatorios *</div>
</body>
</html>