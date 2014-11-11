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
		<td class="titulo">Acuse de Recibo</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />
<?
//// ----------------------------------------------------------------------------------------------------
$det = explode(";", $registro);
$i=0;
	
foreach ($det as $registros) {
  list($cod_documento, $periodo, $secuencia)=SPLIT( '[|]', $registros);$i++;
      //echo"$cod_documento, $periodo, $secuencia";	
	   
	   //// CONSULTA PARA OBTENER DATOS   
	   $sa="select 
	                * 
			  from 
				   cp_documentodistribucionext 
			 where 
				   Cod_Documento='$cod_documento' and 
				   Periodo ='$periodo' and 
				   Secuencia ='$secuencia'";
	   $qa=mysql_query($sa) or die ($sa.mysql_error()); 
	   $ra= mysql_num_rows($qa);
       $fa= mysql_fetch_array($qa);
}		

//// ------------------------------------------------------------
//// CONSULTA PARA OBTENER EL NOMBRE DEL TIPO DE CORRESPONDENCIA 
//// ------------------------------------------------------------
$sb= "select * from cp_tipocorrespondencia where Cod_TipoDocumento = '".$fa['Cod_TipoDocumento']."'";
$qb=mysql_query($sb) or die ($sb.mysql_error());
$fb=mysql_fetch_array($qb);

//// ----------------------------------------------------------------------------------------------------


echo"<input type='hidden' id='regresar' name='regresar' value='".$regresar."'/>";
?>

<div id="tab1" style="display:block;">
<form name="frmentrada" id="frmentrada" action="cpe_salidadist.php?limit=0&accion=guardarAcuseReciboExt" method="post" >
<div style="width:895px; height:15px" class="divFormCaption">Datos del Documento</div>
<table class="tblForm" width="895px" border="0">
  <tr>
    <td height="5"></td>
  </tr>
  <?
echo"<input type='hidden' id='cod_documento' name='cod_documento' value='".$fa['Cod_Documento']."'/>
     <input type='hidden' id='cod_tdocumento' name='cod_tdocumento' value='".$fa['Cod_TipoDocumento']."'/>
	 <input type='hidden' id='periodo' name='periodo' value='".$periodo."'/>
	 <input type='hidden' id='secuencia' name='secuencia' value='".$secuencia."'/>
	";
?>
  <tr>
    <td width="131" class="tagForm">Tipo Documento:</td>
    <td width="280"><input type="text" name="t_documento" id="t_documento" size="20"  value="<?=$fb['Descripcion']?>" readonly/></td>
    <td width="91" class="tagForm">Fecha Enviado:</td>
    <?
  $sc= "select * 
          from 
		       cp_documentoextsalida 
		 where 
		       Cod_Documento = '".$cod_documento."' and 
			   Periodo = '".$periodo."' and 
			   CodOrganismo = '".$_SESSION['ORGANISMO_ACTUAL']."'";
  $qc= mysql_query($sc) or die ($sc.mysql_error()); 
  $fc= mysql_fetch_array($qc);
  
  list($a, $m, $d)=SPLIT( '[/.-]', $fa['FechaDistribucion']); $f_distribucion=$d.'-'.$m.'-'.$a;
 
  $fecha = date("d-m-Y");
 ?>
    <td width="373"><input type="text" id="fecha_documento" name="fecha_documento" size="10" value="<?=$f_distribucion;?>" readonly/>
      *(dd-mm-aaaa)</td>
  </tr>
  <tr style="border-style:double">
    <td class="tagForm">Nro. Documento:</td>
    <td><input type="text" id="n_documento" name="n_documento" size="22" value="<?=$fc['Cod_DocumentoCompleto']?>" style="text-align:right" readonly/></td>
  </tr>
  <tr>
    <td height="5"></td>
  </tr>
  <tr>
    <td class="tagForm">Asunto:</td>
    <td colspan="1"><input type="text" id="asunto" name="asunto" size="70" value="<?=$fc['Asunto']?>" readonly/></td>
  </tr>
  <tr>
    <td class="tagForm">Comentario:</td>
    <td colspan="3"></td>
  </tr>
  <tr>
    <td class="tagForm"></td>
    <td colspan="3"><textarea name="descripcion" id="descripcion" rows="2" cols="80" readonly><?=$fc['Descripcion']?>
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
    <?
$sdatos="select
               mo.Organismo as organismo,
			   md.Dependencia as dependencia,
			   mp.NomCompleto as nombre,
			   rhp.DescripCargo as cargo
			   
		   from
		       mastorganismos mo,
			   mastdependencias md,
			   mastpersonas mp,
			   rh_puestos rhp
 		  where
		       mo.CodOrganismo = '".$fc['CodOrganismo']."' and
			   md.CodDependencia = '".$fc['Cod_Dependencia']."' and
			   mp.CodPersona = '".$fc['Remitente']."' and
			   rhp.CodCargo = '".$fc['Cargo']."'";
$qdatos= mysql_query($sdatos) or die ($sdatos.mysql_error());
$fdatos= mysql_fetch_array($qdatos);

echo"<input type='hidden' id='codpRemitente' name='codpRemitente' value='".$fc['Remitente']."'/>
     <input type='hidden' id='codDepRemitente' name='codDepRemitente' value='".$fc['Cod_Dependencia']."'/>
	 <input type='hidden' id='codCargoRemitente' name='codCargoRemitente' value='".$fc['Cargo']."'/>";
?>
    <td class="tagForm">Organismo:</td>
    <td colspan="3"><input type="text" id="org_remitente" name="org_remitente"  size="60" value="<?=$fdatos['organismo']?>" readonly/></td>
  </tr>
  <tr>
    <td class="tagForm" colspan="">Dependencia:</td>
    <td colspan="3"><input type="text" id="dep_remitente" name="dep_remitente" size="60" value="<?=$fdatos['dependencia']?>" readonly/></td>
  </tr>
  <tr>
    <td class="tagForm">Representante:</td>
    <td><input type="text" id="repre_remitente" name="repre_remitente" size="60" value="<?=$fdatos['nombre']?>" readonly/></td>
  </tr>
  <tr>
    <td class="tagForm">Cargo:</td>
    <td><input type="text" id="cargo_remitente" name="cargo_remitente" size="60" value="<?=$fdatos['cargo']?>" readonly/></td>
  </tr>
  <tr>
    <td height="10"></td>
  </tr>
  <tr>
    <td colspan="4"><div class="cellText" align="center"><b>Recibido Por</b></div></td>
  </tr>
  <tr>
    <td height="5"></td>
  </tr>
  <tr>
    <td class="tagForm">Nro. Cedula:</td>
    <td><input type="text" name="nro_cedula" id="nro_cedula" size="10" style="text-align:right"/></td>
    <td class="tagForm">Fecha Recibido:</td>
    <td><input type="text" name="fecha_recibido" id="fecha_recibido" size="10" value="<?=$fecha;?>" style="text-align:right" />
      *(dd-mm-aaaa)</td>
  </tr>
  <tr>
    <?
  $su="select
              u.CodPersona,
			  mp.NomCompleto,
			  me.CodCargo,
			  rp.DescripCargo 
		 from 
		      usuarios u
			  inner join mastpersonas mp on (mp.CodPersona = u.CodPersona)
			  inner join mastempleado me on (me.CodPersona = mp.CodPersona)
			  inner join rh_puestos rp on (rp.CodCargo = me.CodCargo)
			  
	    where 
		      u.Usuario = '".$_SESSION['USUARIO_ACTUAL']."'";
  $qu= mysql_query($su)  or die ($su.mysql_error());
  $fu= mysql_fetch_array($qu);
 ?>
    <td class="tagForm">Nombre y Apellido:</td>
    <input type="hidden" name="codp_recibido" id="codp_recibido" value="<?=$fu[0]?>" readonly/>
    <td><input name="Nombp_recibido" id="Nombp_recibido" type="text" size="60" />
    </td>
    <td class="tagForm">Hora Recibido:</td>
    <td><input type="text" id="hora" name="hora" size="10" value="<?=date("H:m:s");?>" style="text-align:right"/>
      *(hh:mm:ss)</td>
  </tr>
  <tr>
    <td class="tagForm">Cargo:</td>
    <td><input type="text" id="Cargop_recibido" name="Cargop_recibido" size="60"/>
        <input type="hidden" id="cod_cargorecibido" name="cod_cargorecibido" value="<?=$fu[2]?>" size="68" readonly/>
    </td>
    <td class="tagForm">Observaciones:</td>
    <td></td>
  </tr>
  <tr>
    <td class="tagForm">Lugar:</td>
    <td><input type="text" name="lugar" id="lugar"  size="60"/></td>
    <td></td>
    <td><textarea name="observaciones" id="observaciones" rows="2" cols="80"></textarea></td>
  </tr>
  <tr>
    <td height="10"></td>
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
    <input name="guardar" type="submit" id="guardar" value="Acusar Recibo"/>
    <input name="cancelar" type="button" id="cancelar" value="Cancelar" onClick="cargarPagina(this.form, 'cpe_salidadist.php?limit=0');" />
</center>
</form>
</div>
</body>
</html>
