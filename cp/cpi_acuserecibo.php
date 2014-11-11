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
list($cod_tipodocumento, $cod_documento, $codpersona)= split('[|]', $_POST['registro']);
 //echo $codpersona;
 
$sa= "select * 
        from
		    cp_documentodistribucion 
	   where 
	        Cod_Documento = '$cod_documento' and 
			Cod_TipoDocumento = '$cod_tipodocumento' and  
			Cod_Organismo = '".$_SESSION['ORGANISMO_ACTUAL']."'";
$qa= mysql_query($sa) or die ($sa.mysql_error()); 
$fa= mysql_fetch_array($qa);

echo"<input type='hidden' id='regresar' name='regresar' value='".$regresar."'/>";
?>

<div id="tab1" style="display:block;">
<form name="frmentrada" id="frmentrada" action="<?=$regresar?>.php?limit=0&accion=guardarAcuseRecibo&fEstado=fEstado" method="post" >
<div style="width:895px; height:15px" class="divFormCaption">Datos del Documento</div>
<table class="tblForm" width="895px" border="0">
<tr><td height="5"></td></tr>
<?
echo"<input type='hidden' id='cod_documento' name='cod_documento' value='".$fa['Cod_Documento']."'/>
     <input type='hidden' id='cod_tdocumento' name='cod_tdocumento' value='".$fa['Cod_TipoDocumento']."'/>
	 <input type='hidden' id='procedencia' name='procedencia' value='".$fa['Procedencia']."'/>
	 <input type='hidden' id='periodo' name='periodo' value='".$fa['Periodo']."'/>
     <input type='hidden' id='fEstado' name='fEstado' value='".$fEstado."'/> 
	";
?>
<tr>
  <td width="124" class="tagForm">Tipo Documento:</td>
  <td width="300">
     <? 
	  $sql="SELECT * FROM cp_tipocorrespondencia WHERE Cod_TipoDocumento='".$fa['Cod_TipoDocumento']."'";
	  $qry=mysql_query($sql) or die ($sql.mysql_error());
	  $row=mysql_num_rows($qry);
	  $field=mysql_fetch_array($qry);
	 ?>
     <input type="text" name="t_documento" id="t_documento" size="30"  value="<?=$field['Descripcion']?>" readonly/>
     </td>
   <td width="117" class="tagForm">Fecha Enviado:</td>
 <?
  if($fa[Procedencia]=='EXT'){
	  $sb= "select 
	               Asunto,
				   Descripcion 
			  from 
				   cp_documentoextentrada 
			 where 
				   NumeroRegistroInt = '$cod_documento' and 
				   Cod_TipoDocumento = '$cod_tipodocumento' and 
				   CodOrganismo = '".$_SESSION['ORGANISMO_ACTUAL']."'";
	  $qb= mysql_query($sb) or die ($sb.mysql_error()); 
	  $fb= mysql_fetch_array($qb);
  }else{
      $sb= "select 
	               Asunto,
				   Descripcion 
			  from 
				   cp_documentointerno 
			 where 
				   Cod_DocumentoCompleto = '$cod_documento' and 
				   Cod_TipoDocumento = '$cod_tipodocumento' and  
				   CodOrganismo = '".$_SESSION['ORGANISMO_ACTUAL']."'"; //echo $sb;
	  $qb= mysql_query($sb) or die ($sb.mysql_error()); 
	  $fb= mysql_fetch_array($qb);
  }
  
  list($a, $m, $d)=SPLIT( '[/.-]', $fa['FechaDistribucion']); $f_distribucion=$d.'-'.$m.'-'.$a;
 
  $fecha = date("d-m-Y");
 ?>
 <td width="334"><input type="text" id="fecha_documento" name="fecha_documento" size="10" value="<?=$f_distribucion;?>" style="text-align:right" readonly/>*(dd-mm-aaaa)</td>
</tr>
<tr style="border-style:double">
 <td class="tagForm">Nro. Documento:</td>
  <td><input type="text" id="n_documento" name="n_documento" size="20" value="<?=$fa['Cod_Documento']?>" style="text-align:right" readonly/></td>
 <td class="tagForm"></td>
 <td></td>
</tr>
<tr><td height="5"></td></tr>
<tr>
  <td class="tagForm">Asunto:</td>
  <td colspan="1"><input type="text" id="asunto" name="asunto" size="74" value="<?=$fb['Asunto']?>" readonly/></td>
</tr>
<tr>
  <td class="tagForm">Comentario:</td>
  <td colspan="3"></td>
</tr>
<tr>
  <td class="tagForm"></td>
  <td colspan="3"><textarea name="descripcion" id="descripcion" rows="2" cols="75" readonly><?=$fb['Descripcion']?></textarea></td>
</tr>
<tr><td height="5"></td></tr>
<tr>
 <td colspan="4"><div class="cellText" align="center"><b>Remitente</b></div></td>
 <!--<td colspan="4"><div class="divBorder" style="border-color:#999999;"></div></td>-->
</tr>
<tr><td height="5"></td></tr>
<tr>
<?
  if($fa[Procedencia]=='INT'){
    /// Consulto para obtener datos a utilizar para otras consultas
	$sconsulta = "select * from cp_documentointerno where Cod_DocumentoCompleto = '$cod_documento' and  Cod_TipoDocumento = '$cod_tipodocumento'";
	$qconsulta = mysql_query($sconsulta) or die ($sconsulta.mysql_error()); //echo $sconsulta;
	$fconsulta = mysql_fetch_array($qconsulta);
	
	/// Consulta donde obtienen datos a mostrar ralacionados a la consulta
    $sdatos="select
                   mo.Organismo,
				   md.Dependencia,
				   mp.NomCompleto,
				   rp.DescripCargo
			   from
			       mastorganismos mo,
				   mastdependencias md,
				   mastpersonas mp,
				   rh_puestos rp
 		      where
		           mo.CodOrganismo = '".$fconsulta['CodOrganismo']."' and
				   md.CodDependencia = '".$fconsulta['Cod_Dependencia']."' and
				   mp.CodPersona = '".$fconsulta['Cod_Remitente']."' and 
				   rp.CodCargo = '".$fconsulta['Cod_CargoRemitente']."'"; //echo $sdatos;
	$qdatos= mysql_query($sdatos) or die ($sdatos.mysql_error());
	$fdatos= mysql_fetch_array($qdatos);

	echo"<input type='hidden' id='codpRemitente' name='codpRemitente' value='".$fconsulta['Cod_Remitente']."'/>
		 <input type='hidden' id='codDepRemitente' name='codDepRemitente' value='".$fconsulta['Cod_Dependencia']."'/>
		 <input type='hidden' id='codCargoRemitente' name='codCargoRemitente' value='".$fconsulta['Cod_CargoRemitente']."'/>";
  }else{
   if($fa[Procedencia]=='EXT'){
    
	/// Consulto para obtener datos a utilizar para otras consultas
	$sconsulta = "select * from cp_documentoextentrada where NumeroRegistroInt = '$cod_documento'";
	$qconsulta = mysql_query($sconsulta) or die ($sconsulta.mysql_error()); //echo $sconsulta;
	$fconsulta = mysql_fetch_array($qconsulta);
	
	if($fconsulta[FlagEsParticular]=='N'){
	/// Consulta donde obtienen datos a mostrar ralacionados a la consulta
	   if($fconsulta[Cod_Dependencia]!=''){
		  $sdatos="select
					   pfo.Organismo,
					   pfd.Dependencia,
					   pfd.Representante as NomCompleto,
					   pfd.Cargo as DescripCargo
				   from
					   pf_organismosexternos pfo,
					   pf_dependenciasexternas pfd
				  where
					   pfo.CodOrganismo = '".$fconsulta['Cod_Organismos']."' and
					   pfd.CodDependencia = '".$fconsulta['Cod_Dependencia']."'"; //echo $sdatos;
		$qdatos= mysql_query($sdatos) or die ($sdatos.mysql_error());
		$fdatos= mysql_fetch_array($qdatos);
		
		echo"<input type='hidden' id='codpRemitente' name='codpRemitente' value='".$fconsulta['Remitente']."'/>
			 <input type='hidden' id='codDepRemitente' name='codDepRemitente' value='".$fconsulta['Cod_Dependencia']."'/>
			 <input type='hidden' id='codCargoRemitente' name='codCargoRemitente' value='".$fconsulta['Cargo']."'/>";
		}else{
		   $sdatos="select
					   pfo.Organismo,
					   pfo.RepresentLegal as NomCompleto,
					   pfo.Cargo as DescripCargo
					   
				   from
					   pf_organismosexternos pfo
				  where
					   pfo.CodOrganismo = '".$fconsulta['Cod_Organismos']."'"; //echo $sdatos;
		$qdatos= mysql_query($sdatos) or die ($sdatos.mysql_error());
		$fdatos= mysql_fetch_array($qdatos);
		
		echo"<input type='hidden' id='codpRemitente' name='codpRemitente' value='".$fconsulta['Remitente']."'/>
			 <input type='hidden' id='codDepRemitente' name='codDepRemitente' value='".$fconsulta['Cod_Dependencia']."'/>
			 <input type='hidden' id='codCargoRemitente' name='codCargoRemitente' value='".$fconsulta['Cargo']."'/>";
		}
    }else{
	   $sdatos="select
	                   *
			      from
				       cp_particular
				 where
				      CodParticular = '".$fconsulta['CodParticular']."'"; //echo $sdatos;
	   $qdatos= mysql_query($sdatos) or die ($sdatos.mysql_error());
	   $fdatos= mysql_fetch_array($qdatos);

	   echo"<input type='hidden' id='codpRemitente' name='codpRemitente' value='".$fconsulta['Remitente']."'/>
		   <input type='hidden' id='codDepRemitente' name='codDepRemitente' value='".$fconsulta['Cod_Dependencia']."'/>
		   <input type='hidden' id='codCargoRemitente' name='codCargoRemitente' value='".$fconsulta['Cargo']."'/>";
	}
   }
  }  
?>  

  <td class="tagForm">Organismo:</td>
  <td colspan="3"><input type="text" id="org_remitente" name="org_remitente"  size="60" value="<?=$fdatos[Organismo]?>" readonly/></td>
</tr>
<tr>
  <td class="tagForm" colspan="">Dependencia:</td>
  <td colspan="3"><input type="text" id="dep_remitente" name="dep_remitente" size="60" value="<?=utf8_encode($fdatos[Dependencia])?>" readonly/></td>
</tr>
<tr>
 <td class="tagForm">Representante:</td>
 <td><input type="text" id="repre_remitente" name="repre_remitente" size="60" value="<?=utf8_encode($fdatos[NomCompleto])?>" readonly/></td>
</tr>
<tr>
 <td class="tagForm">Cargo:</td>
 <td><input type="text" id="cargo_remitente" name="cargo_remitente" size="60" value="<?=utf8_encode($fdatos[DescripCargo])?>" readonly/></td>
</tr>
<tr><td height="10"></td></tr>
<tr>
 <td colspan="4"><div class="cellText" align="center"><b>Recibido Por</b></div></td>
</tr>
<tr><td height="5"></td></tr>
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
 <td class="tagForm">Recibido por:</td>
  <input type="hidden" name="codp_recibido" id="codp_recibido" value="<?=$fu[CodPersona]?>" readonly/>   
 <td><input name="nomempleado" id="nomempleado" type="text" size="52" value="<?=utf8_encode($fu[NomCompleto])?>" readonly/>
 </td>
 <td class="tagForm">Fecha Recibido:</td>
 <td><input type="text" id="fecha_recibido" name="fecha_recibido" size="10" maxlength="10" value="<?=$fecha;?>" style="text-align:right" />
 *(dd-mm-aaaa) Hora:<input type="text" id="hora_recibido" name="hora_recibido" size="8" value="<?=date("H:i:s")?>" style="text-align:right"/>*(hh:mm:ss)</td>
</tr>
<tr>
  <td class="tagForm">Cargo:</td>
  <td><input type="text" id="cargo_recibido" name="cargo_recibido" value="<?=$fu[3]?>" size="52" readonly/>
      <input type="hidden" id="cod_cargorecibido" name="cod_cargorecibido" value="<?=$fu[2]?>" size="68" readonly/>
  </td>
  <td class="tagForm">Observaciones:</td>
  <td><textarea name="observaciones" id="observaciones" rows="2" cols="75"></textarea></td>
</tr>
<tr>
   <td height="10"></td>
   <td></td>
   <td></td>
</tr>
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
<center>
    <input name="guardar" type="submit" id="guardar" value="Acusar Recibo"/>
    <input name="cancelar" type="button" id="cancelar" value="Cancelar" onClick="cargarPagina(this.form, '<?=$regresar?>.php?limit=0');" />
</center>
</form>
</div>
</body>
</html>
