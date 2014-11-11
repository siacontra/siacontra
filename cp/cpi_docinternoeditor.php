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
		<td align="right"><a class="cerrar" href="framemain.php"  onclick="cargarPagina(document.getElementById('frmentrada'),'<?=$regresar?>.php?&limit=0')";>[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?
 list($cod_tipocuenta, $cod_documentocompleto)=SPLIT('[|]', $_POST['registro']);
 //echo $cod_tipocuenta;
 
 $s="select * from 
                   cp_documentointerno 
			  where 
			       Cod_DocumentoCompleto='$cod_documentocompleto' and 
				   Cod_TipoDocumento = '$cod_tipocuenta'";
 $q=mysql_query($s) or die ($s.mysql_error());
 if(mysql_num_rows($q)!=0){ 
   $f=mysql_fetch_array($q);
 }
?>
<form name="framemain" action="cpi_docinternosprep.php?limit=0&accion=guardarContenidoInterno&fremitente=<?=$fremitente?>" method="post">
<div style="width:895px; height:15px" class="divFormCaption">Datos Documento</div>
<table class="tblForm" width="895px" border="0">
<tr><td height="5"></td></tr>
<tr> <!-- ///////////  PRIMER ////////// -->
  <td>
  <table class="tblForm" width="880px">
  <tr>
     <td width="123" class="tagForm">Tipo Documento:</td>
     <td width="296">
     <? 
	  echo"<input type='hidden' id='fremitente' name='fremitente' value='".$fremitente."'/>";
	  echo"<input type='hidden' id='regresar' name='regresar' value='".$regresar."'/>";
	  echo"<input type='hidden' id='cod_documento' name='cod_documento' value='".$f['Cod_Documento']."'/>";
	  echo"<input type='hidden' id='periodo' name='periodo' value='".$f['Periodo']."'/>";
	  echo"<input type='hidden' id='CodOrganismo' name='CodOrganismo' value='".$f['CodOrganismo']."'/>";
	  
	  $sql="SELECT * FROM cp_tipocorrespondencia WHERE FlagUsoInterno='1'  AND Cod_TipoDocumento='".$f['Cod_TipoDocumento']."'";
	  $qry=mysql_query($sql) or die ($sql.mysql_error());
	  if(mysql_num_rows($qry)!=0){ $field=mysql_fetch_array($qry);};
	 ?>
      <input type="hidden" id="cod_tipodocumento" name="cod_tipodocumento" value="<?=$field['Cod_TipoDocumento'];?>"/><input name="t_documento" id="t_documento" value="<?=$field['Descripcion']?>" size="25"  readonly/>   </td>
     <td width="104" class="tagForm">Fecha Documento:</td>
     <?  if($f['FechaDocumento']!='0000-00-00'){list($a,$m,$d)=SPLIT('[/.-]',$f['FechaDocumento']);$f_documento=$d.'-'.$m.'-'.$a;} ?>
     <td width="325"><input type="text" id="fecha_documento" name="fecha_documento" size="10" value="<?=$f_documento;?>" style="text-align:right" readonly/>*(dd-mm-aaaa)</td>
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
      <td><input type="text" id="n_documento" name="n_documento" size="22" value="<?=$f['Cod_DocumentoCompleto']?>" style="text-align:right" readonly/></td>
    </tr>
    <tr>
      <td class="tagForm">Asunto:</td>
      <td><input type="text" name="asunto" id="asunto" value="<?=$f['Asunto']?>" size="68" /></td>
    </tr>
    <tr><td height="5"></td></tr>
     <?
           $scon="select
                        mp.NomCompleto,
                        rh.DescripCargo,
						md.Dependencia
                   from
                        mastpersonas mp
                        inner join mastempleado me on (mp.CodPersona=me.CodPersona)
                        inner join rh_puestos rh on (me.CodCargo=rh.CodCargo)
						inner join mastdependencias md on (md.CodDependencia = me.CodDependencia)
                  where 
                        mp.CodPersona='".$f['Cod_Remitente']."'"; 
           $qcon=mysql_query($scon) or die ($scon.mysql_error()); //echo $scon;
        
           if(mysql_num_rows($qcon)!=0) $fcon=mysql_fetch_array($qcon);
    ?>
    <td class="tagForm">Dependencia:</td>
    <td><input name="coddependencia" type="hidden" id="coddependencia" value="<?=$fcon[2]?>"/>
	   <input name="dependencia" id="dependencia" type="text" size="68" value="<?=htmlentities($fcon['Dependencia'])?>" readonly/></td>
  </tr>
    <tr>
     <td class="tagForm">Representante:</td>
     <td><input name="codpersona" type="hidden" id="codpersona" value="<?=$_POST['registro']?>"/>
         <input name="nomempleado" id="nomempleado" type="text" size="68" value="<?=htmlentities($fcon['NomCompleto'])?>" readonly/>
         </td>
    </tr>
    <tr>
     <td class="tagForm">Cargo:</td>
     <td><input type="text" id="cargoremit" name="cargoremit" value="<?=htmlentities($fcon['DescripCargo']);?>"size="68"  readonly/></td>
    </tr>
    <tr>
      <td class="tagForm">Iniciales:</td>
      <td><input type="text" id="iniciales" name="iniciales" size="5" maxlength="5"/></td>
    </tr>
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
          <th>Dependencia</th>
          <th>Representante/Empleado</th>
          <th>Cargo</th>
       </tr>
       <tbody id="listaDetalles">
	   <? 
	   
	   $sdisext="select * from 
	                           cp_documentodistribucion
					     where 
						       Cod_Documento='".$f['Cod_DocumentoCompleto']."' and 
							   Cod_TipoDocumento = '".$f['Cod_TipoDocumento']."'";
	   $qdisext=mysql_query($sdisext) or die ($sdisext.mysql_error()); //echo $sdisext;
	   $risext= mysql_num_rows($qdisext); //echo $risext;
		
		if($risext!=0){
		   
		  for($i=0; $i<$risext; $i++){
		    $fdisext = mysql_fetch_array($qdisext);
			   $sa="select
                          mp.NomCompleto,
                          rh.DescripCargo,
						  md.Dependencia 
                      from
                          mastpersonas mp
                          inner join mastempleado me on (mp.CodPersona=me.CodPersona)
                          inner join rh_puestos rh on (me.CodCargo=rh.CodCargo)
						  inner join mastdependencias md on (md.CodDependencia = me.CodDependencia)
                     where 
                          mp.CodPersona='".$fdisext['CodPersona']."'";
						
			   $qa = mysql_query($sa) or die ($sa.mysql_error());
			   if (mysql_num_rows($qa) != 0) $fa = mysql_fetch_array($qa);  
		  
		?>
         <tr>
             <td><?=utf8_encode($fa['Dependencia'])?></td>
             <td><?=utf8_encode($fa['NomCompleto']);?></td>
             <td><?=utf8_encode($fa['DescripCargo']);?></td>
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
    </td>
    </tr>
    </table>
  </td>
 </tr>
 
<tr>
 <td colspan="4"><div class="divFormCaption" align="center"><b></b></div></td>
</tr>
<tr>
  <td colspan="4"><textarea class="ckeditor" cols="80" id="editor1" name="editor1" rows="10"></textarea>
  </td>
</tr>
</table>
<center>
    <input type="submit" value="Guardar Registro"/><input type="button" value="Cancelar" onClick="cargarPagina(this.form,'cpi_docinternosprep.php?limit=0');" />
</center>
</form>
</body>
</html>
