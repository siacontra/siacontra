<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp.php");
connect();
//	------------------------------------
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('', $concepto);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" language="javascript" src="cp_script.js"></script>
</head>
<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Atender Documentos Externos</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php

if((!$_POST)or($volver=='0')) $forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"]; else $corganismo = "checked";
if(!$_POST) $cEstado="checked"; else $cEstado="checked";
$filtro = "";

$filtro = "";

if ($forganismo != "") { $filtro .= " AND (CodOrganismo = '".$forganismo."')"; $corganismo = "checked"; } else $dorganismo = "disabled"; // ORGANISMO INTERNO
if ($fcodocumento !="") { $filtro .= "AND (CodTipoDocumento= '".$fcodocumento."')"; $codocumento="checked";} else $documento = "disabled";// CODIGO 
if ($fRecibidoPor !=""){ $filtro .="AND (RecibidoPor= '".$fRecibidoPor."')"; $cRecibido= "checked";}else $dRecibido = "disabled";
if ($fordenardoc !=""){ $filtro .="AND (Cod_TipoDocumento= '".$fordenardoc."')"; $cOrdenarDoc= "checked";}else $dOrdenarDoc = "disabled";


if ($fTdocumento !=""){ $filtro .="AND (Cod_TipoDocumento='".$fTdocumento."')"; $cTDocumento="checked";} else $dTDocumento="disabled"; // TIPO DE DOCUMENTO
if ($fremitente !=""){ $filtro .="AND (Cod_Organismos= '".$fremitente."')"; $cRemitente = "checked";}else $dRemitente = "disabled"; // ORGANISMO EXTERNO
if ($DepRemitente !=""){ $filtro .="AND (Cod_Depedencia='".$DepRemitente."')"; $cDepRemitente="checked";} else $dDepRemitente="disabled";// DEPENDENCIA EXTERNA
if ($fEstado !=""){ $filtro .="AND (Estado='".$fEstado."')"; $cEstado="checked";} else $dEstado="disabled"; // ESTADO DEL DOCUMENTO

if ($fdesde != "" and $fhasta != "") { // FECHA DE REGISTRO DEL DOCUMENTO

  list($d, $m, $a)=SPLIT('[/.-]', $_POST['fdesde']); $fechadesde=$a.'-'.$m.'-'.$d;
  list($d, $m, $a)=SPLIT('[/.-]', $_POST['fhasta']); $fechahasta=$a.'-'.$m.'-'.$d;
  
	if ($fdesde != "") $filtro .= " AND (FechaRegistro >= '$fechadesde')";
	if ($fhasta != "") $filtro .= " AND (FechaRegistro <= '$fechahasta')"; 
	$cFechaRecibido = "checked"; 
	
	list($a, $m, $d)=SPLIT('[/.-]', $fechadesde); $fechadesde=$d.'-'.$m.'-'.$a;
    list($a, $m, $d)=SPLIT('[/.-]', $fechahasta); $fechahasta=$d.'-'.$m.'-'.$a;
	
} else $dFechaRecibido = "disabled";


$MAXLIMIT=30;
//	ELIMINO EL REGISTRO
if ($_GET['accion']=="ELIMINAR") {
	//
	$sql="DELETE FROM mastpersonas WHERE CodPersona='".$_POST['registro']."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$_GET['limit']=0;
}

$d=date("Y-m-d H:i:s"); echo "<input type='hidden' name='limit' value='$d'>" ;
echo "
<form name='frmentrada' action='cpe_atenderext.php?limit=0' method='POST'>
<input type='hidden' name='limit' value='".$limit."'>
<div class='divBorder' style='width:1000px;'>
<table width='1000' class='tblFiltro'>
<tr>
   <td width='125' align='right'>Organismo:</td>
   <td> 
      <input type='checkbox' id='checkorganismos' name='checkorganismos' value='1' $corganismo onclick='this.checked=true'>
	     <select name='forganismo' id='forganismo' class='selectBig' $dorganismo>";
		   getOrganismos(3,$_SESSION['ORGANISMO_ACTUAL']);
		 echo"
		 </select>
   </td>
   <td width='125' align='right' >Fecha Recibido:</td>
   <td>
     <input type='checkbox' id='checkFechaRecibido' name='checkFechaRecibido' value='1' $cFechaRecibido onclick='enabledFechaRecibido(this.form);'/>
	 desde:<input type='text' name='fdesde' id='fdesde' size='8' maxlength='10' $dFechaRecibido value='$fechadesde'/>
	 hasta:<input type='text' name='fhasta' id='fhasta' size='8' maxlength='10' $dFechaRecibido value='$fechahasta'/>
   </td>
</tr>

<tr>
 <td width='125' align='right'>Org. Remitente:</td>
 <td>
    <input type='checkbox' id='checkRemitente' name='checkRemitente' value='1' $cRemitente onclick='enabledRemitenteAtenderExt(this.form);'/>
	 <select id='fremitente' name='fremitente' class='selectBig' $dRemitente>
	 <option value=''></option>";
	  getRemitente(2, $fremitente);
	echo"
	</select>
 </td>
 <td width='125' align='right'>Recibido por:</td>
 <td><input type='checkbox' id='checkRecibido' name='checkRecibido' value='1' $cRecibido onclick='enabledRecibidoPor(this.form);'/>
     <select id='fRecibidoPor' name='fRecibidoPor' class='selectBig' $dRecibido>
	  <option value=''></option>";
	   getRecibidoPor(0,$fRecibidoPor);
     echo"
	 </select>
  </td>
</tr>

<tr>
 <td width='125' align='right'>Dep. Remitente:</td>
 <td>
    <input type='checkbox' id='checkDepRemitente' name='checkDepRemitente' value='1' $cDepRemitente onclick='enabledDepRemitente(this.form);'/>
	 <select id='DepRemitente' name='DepRemitente' class='selectBig' $dDepRemitente>
	 <option value=''></option>";
	  getDepRemitente(0, $DepRemitente);
	echo"
	</select>
 </td>
 <td width='125' align='right'>Estado:</td>
<td>
	<input type='checkbox' id='checkEstado' name='checkEstado' value='1' $cEstado onclick='this.checked=true';/>
	<select name='fEstado' id='fEstado' class='selectMed' $dEstado>
		<option value='PE'>Pendiente</option>";
		echo "
	</select>
</td>
</tr>

<tr>
<td width='125' align='right'>Tipo Documento:</td>
<td>
  <input type='checkbox' id='checkTdocumento' name='checkTdocumento' value='1' $cTDocumento onclick='enabledTdocumento(this.form);'/>
	 <select id='fTdocumento' name='fTdocumento' class='selectMed' $dTDocumento>
	 <option value=''></option>";
	  getTdocumento( 1, $fTdocumento);
	echo"
	</select>
</td>
</td> 
<td width='125' align='right'></td>
<td>
</td>
</tr>
</table>
</div>
<center><input type='submit' name='btBuscar' value='Buscar'></center>
<br /><div class='divDivision'>Listado de Documentos</div><br />
<form/>";
///_________________________________________________________________________________________
$year = date("Y");
$sql="SELECT * 
        FROM 
		     cp_documentoextentrada 
	   WHERE 
	         CodOrganismo = '".$_SESSION['ORGANISMO_ACTUAL']."' AND Estado='PE'
			 $filtro
	ORDER BY 
	         Cod_TipoDocumento"; //echo $sql;
$query=mysql_query($sql) or die ($sql.mysql_error());
$registros=mysql_num_rows($query);
$rows=$registros; 
?>
<table width="1000" class="tblBotones">
<tr>
<td><div id="rows"></div></td>
<td width="250">

		</td>
		<td align="right">
  <!--<input type="button" id="btNuevo" name="btNuevo" class="btLista" value="Nuevo" onclick="cargarPagina(this.form,'cpe_entradaextnuevo.php');"/>-->
   <input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'cpe_listaentradaextver.php', 'BLANK', 'height=500, width=1000, left=200, top=100, resizable=yes');" />
<!-- <input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="eliminarRegistro(this.form, 'personas.php?accion=ELIMINAR', '1', 'PERSONAS');" />
 <input name="btPDF" type="button" class="btLista" id="btPDF" value="PDF" onclick="cargarVentana(this.form, 'personas_pdf.php', 'height=800, width=800, left=200, top=200, resizable=yes');" />-->
|<input type="button" name="btAtender" id="btAtender" class="btLista" value="Atender" onclick="cargarOpcion(this.form,'cpe_atenderextrecibido.php', 'SELF');"/>

		</td>
	</tr>
</table>
<input type="hidden" name="registro" id="registro" />
<table align="center">
<tr>
  <td align="center">
  <div style="overflow:scroll; width:1000px; height:300px;">

<table width="1300" class="tblLista">
	<tr class="trListaHead">
      <th></th>
      <th>Nro.Registro Interno</th>
      <th>Nro.Documento</th>
      <th>A&ntilde;o</th>
      <th>Tipo Documento</th>
      <th>Organismo</th>
      <th>Dependencia</th>
      <th>Asunto</th>
      <th>Comentario</th>
      <th>Remitente</th>
      <th>FechaRecibido</th>
      <th>FechaDocumento</th>
      <th>Estado</th>
   </tr>
<?php 
if ($registros!=0) {
	for ($i=0; $i<$rows; $i++) {
		$field=mysql_fetch_array($query);
		//// _________ CONSULTO PARA OBTENER LA DESCRIPCION DE TIPO DE DOCUMENTO A MOSTRAR
		$sqltipodoc="SELECT * FROM cp_tipocorrespondencia WHERE Cod_TipoDocumento='".$field['Cod_TipoDocumento']."'";
		$qrytipodoc=mysql_query($sqltipodoc) or die ($sqltipodoc.mysql_error());
		$fieldtipodoc=mysql_fetch_array($qrytipodoc);
		
		//// _________ CONSULTO PARA OBTENER INFORMACION DEL REMITENTE ORGANISMO - DEPENDENCIA
		if($field['Cod_Dependencia']==''){
		  $sqlorgadep="SELECT 
		                    Organismo as organismo, 
		                    RepresentLegal as r_legalorg 
		               FROM 
					        pf_organismosexternos
					  WHERE 
					       CodOrganismo= '".$field['Cod_Organismos']."'";
		}else{
		    $sqlorgadep="SELECT 
			                pforg.Organismo as organismo, 
		                    pforg.RepresentLegal as r_legalorg, 
							pfdep.Representante as r_legaldep, 
							pfdep.Dependencia as dependencia 
		               FROM 
					        pf_organismosexternos pforg, 
							pf_dependenciasexternas pfdep 
					  WHERE 
					        pfdep.CodDependencia= '".$field['Cod_Dependencia']."' AND
							pforg.CodOrganismo= '".$field['Cod_Organismos']."'";
		 }
		
		if($field['FlagEsParticular']=='N'){
		   $qryorgadep=mysql_query($sqlorgadep) or die ($sqlorgadep.mysql_error());
		   $fieldorgadep=mysql_fetch_array($qryorgadep);
	    }else{ 
	     $organisPart ='Particular'; $depenPart ='Particular';
	    }
		
		/*$sqlorgadep="SELECT pforg.Organismo as organismo, 
		                    pforg.RepresentLegal as r_legalorg, 
							pfdep.Representante as r_legaldep, 
							pfdep.Dependencia as dependencia 
		               FROM 
					        pf_organismosexternos pforg, 
							pf_dependenciasexternas pfdep 
					  WHERE 
					        pforg.CodOrganismo= pfdep.CodOrganismo AND 
							pfdep.CodDependencia= '".$field['Cod_Dependencia']."'";
		$qryorgadep=mysql_query($sqlorgadep) or die ($sqlorgadep.mysql_error());
		$fieldorgadep=mysql_fetch_array($qryorgadep);*/
		
		
		if($field['Estado']=='PE') $estado='Pendiente';
		if($field['Estado']=='RE') $estado='Recibido';
		if($field['Estado']=='CO') $estado='Completado';
		
		//// _____ CAMBIO DE FORMATO DE FECHA PARA MOSTRAR
		list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaRegistro']); $f_Registro=$d.'-'.$m.'-'.$a;
		list($a, $m, $d)=SPLIT('[/.-]', $field['FechaDocumentoExt']); $f_documento=$d.'-'.$m.'-'.$a;
		echo "
		<tr class='trListaBody' onclick='mClk(this, \"registro\");' id='".$field['NumeroRegistroInt']."'>
		    <td align='center'><img src='imagenes/activo2.png' style='width:20px;height=10px;' /></td>
			<td align='center'>".$field['NumeroRegistroInt']."</td>
			<td align='center'>".$field['NumeroDocumentoExt']."</td>
			<td align='center'>".$field['Periodo']."</td>
			<td align='center'>".$fieldtipodoc['Descripcion']."</td>";
	    if($field['FlagEsParticular']=='N'){
		echo"<td align='left'>".htmlentities($fieldorgadep['organismo'])."</td>
			<td align='left'>".htmlentities($fieldorgadep['dependencia'])."</td>";
		}else{
		    echo"<td align='left'>".htmlentities($organisPart)."</td>
			<td align='left'>".htmlentities($depenPart)."</td>";
		}
			echo"<td align='left'>".$field['Asunto']."</td>
			<td align='left'>".$field['Descripcion']."</td>	
			<td align='left'>".htmlentities($field['Remitente'])."</td>		
			<td align='center'>$f_Registro</td>
			<td align='center'>$f_documento</td>
			<td align='center'>$estado</td>
		</tr>";
	}
	}
$rows=(int)$rows;
/*echo "
<script type='text/javascript' language='javascript'>
	totalRegistros($registros, \"$_ADMIN\", \"$_INSERT\", \"$_UPDATE\", \"$_DELETE\");
	totalLotes($registros, $rows, ".$_GET['limit'].");
</script>";*/				
?>
</table>
</div></td></tr></table>
</form>
<script type="text/javascript" language="javascript">
	totalRegistrosAtender(<?=$registros?>, "<?=$_ADMIN?>", "<?=$_INSERT?>", "<?=$_UPDATE?>", "<?=$_DELETE?>");
</script>
</body>
</html>
