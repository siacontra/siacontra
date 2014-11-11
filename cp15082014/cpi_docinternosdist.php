<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp.php");
connect();
include "ControlCorrespondencia.php";
//	------------------------------------
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
		<td class="titulo">Documentos Internos | Distribuci&oacute;n</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php

if(!$_POST) $forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"]; else $corganismo = "checked"; 
if(!$_POST){$fEstado="EV"; $cEstado="checked";}
if(!$_POST) $fremitente = $_SESSION["FILTRO_DEPENDENCIA_ACTUAL"]; else{ $fremitente = $_POST['fremitente']; $cRemitente = "checked"; }


$filtro = "";

if ($forganismo != "") { $filtro .= " AND (cpdoc.CodOrganismo = '".$forganismo."')"; $corganismo = "checked"; } else $dorganismo = "disabled";
if ($fRemitente !=""){ $filtro .="AND (cpdoc.Cod_Dependencia= '".$fRemitente."')"; $cRemitente = "checked";}else $dRemitente = "disabled";
if ($fEstado !=""){ $filtro .="AND (cpdist.Estado='".$fEstado."')"; $cEstado="checked";} else $dEstado="disabled";
if ($fTdocumento !=""){ $filtro .="AND (cpdist.Cod_TipoDocumento='".$fTdocumento."')"; $cTDocumento="checked";} else $dTDocumento="disabled";
if ($fDestinatario !=""){ $filtro .="AND (cpdist.CodDependencia='".$fDestinatario."')"; $cDestinatario="checked";} else $dDestinatario="disabled";
if ($NroDocumento !=""){ $filtro .="AND (cpdist.Cod_Documento='".$NroDocumento."')"; $cNroDocumento="checked";} else $dNroDocumento="disabled";

if ($fdesde != "" and $fhasta != "") { // FECHA DE REGISTRO DEL DOCUMENTO

  list($d, $m, $a)=SPLIT('[/.-]', $_POST['fdesde']); $fechadesde=$a.'-'.$m.'-'.$d;
  list($d, $m, $a)=SPLIT('[/.-]', $_POST['fhasta']); $fechahasta=$a.'-'.$m.'-'.$d;
  
	if ($fdesde != "") $filtro .= " AND (FechaDistribucion >= '$fechadesde')";
	if ($fhasta != "") $filtro .= " AND (FechaDistribucion <= '$fechahasta')"; 
	$cFechaRecibido = "checked"; 
	
	list($a, $m, $d)=SPLIT('[/.-]', $fechadesde); $fechadesde=$d.'-'.$m.'-'.$a;
    list($a, $m, $d)=SPLIT('[/.-]', $fechahasta); $fechahasta=$d.'-'.$m.'-'.$a;
	
} else $dFechaRecibido = "disabled";

$MAXLIMIT=30;

$d=date("Y-m-d H:i:s"); echo "<input type='hidden' name='limit' value='$d'>" ;
echo "
<form name='frmentrada' action='cpi_docinternosdist.php?limit=0' method='POST'>
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
   <td width='125' align='right' >Fecha Creado:</td>
   <td> 
     <input type='checkbox' id='checkFechaRecibido' name='checkFechaRecibido' value='1' $cFechaRecibido onclick='enabledFechaRecibido(this.form);'/>
	 desde:<input type='text' name='fdesde' id='fdesde' size='8' maxlength='10' $dFechaRecibido value='$fechadesde'/>
	 hasta:<input type='text' name='fhasta' id='fhasta' size='8' maxlength='10' $dFechaRecibido value='$fechahasta'/>
   </td>
</tr>

<tr>
 <td width='125' align='right'>Dep. Remitente:</td>
 <td>
    <input type='checkbox' id='checkRemitente' name='checkRemitente' value='1' $cRemitente onclick='enabledRemitente(this.form);'/>
	 <select id='f_Remitente' name='f_Remitente' class='selectBig' $dRemitente>
	 <option value=''></option>";
	  getRemitenteDepRecibido(0, $f_Remitente);
	echo"
	</select>
 </td>
 <td width='125' align='right'>Nro. Documento:</td>
 <td><input type='checkbox' id='checkNroDocumento' name='checkNroDocumento' value='1' $cNroDocumento onclick='enabledNroDocumento(this.form);'/>

     <input type='text' id='NroDocumento' name='NroDocumento' size='20' $dNroDocumento value='$NroDocumento'>
	  <option value=''></option>
 </td>
</tr>

<tr>
 <td width='125' align='right'>Dep. Destinatario:</td>
 <td>
    <input type='checkbox' id='checkDestinatario' name='checkDestinatario' value='1' $cDestinatario onclick='enabledDestinatarioDocInt(this.form);'/>
	 <select id='fDestinatario' name='fDestinatario' class='selectBig' $dDestinatario>
	 <option value=''></option>";
	  getRemitente(4, $fDestinatario);
	echo"
	</select>
 </td>
 <td width='125' align='right'>Estado:</td>
<td>
	<input type='checkbox' id='checkEstado' name='checkEstado' value='1' $cEstado onclick='this.checked=true'/>
	<select name='fEstado' id='fEstado' class='selectMed' $dEstado>
		 <option value='EV'>Enviado</option>";
		//getEstado( 4, $fEstado);
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
	  getTdocumento( 3, $fTdocumento);
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
/*$sql="SELECT * 
        FROM 
		     cp_documentodistribucion 
	   WHERE 
	         Cod_Organismo = '".$_SESSION['ORGANISMO_ACTUAL']."' AND Procedencia = 'INT'
			 $filtro
	ORDER BY 
	         Cod_TipoDocumento"; echo $sql;
$query=mysql_query($sql) or die ($sql.mysql_error());
$registros=mysql_num_rows($query);
$rows=$registros;*/ 

$sql="SELECT 
             cpdist.Cod_Documento,
			 cpdist.CodPersona,
			 cpdist.Periodo,
			 cpdist.CodDependencia,
			 cpdist.Cod_Organismo,
			 cpdist.CodCargo,
			 cpdist.PlazoAtencion,
			 cpdist.Estado,
			 cpdist.Cod_TipoDocumento
        FROM 
		     cp_documentodistribucion cpdist
			 inner join cp_documentointerno cpdoc on (cpdist.Cod_Documento = cpdoc.Cod_DocumentoCompleto)
	   WHERE 
	         cpdist.Cod_Organismo = '".$_SESSION['ORGANISMO_ACTUAL']."' AND cpdist.Procedencia = 'INT'
			 $filtro
	ORDER BY 
	         cpdist.Cod_TipoDocumento"; //echo $sql;
$query=mysql_query($sql) or die ($sql.mysql_error());
$registros=mysql_num_rows($query);
$rows=$registros;

?>
<table width="1000" class="tblBotones">
<tr>
<td><div id="rows"></div></td>
<td width="250">
<?php 
echo"<input type='hidden' name='regresar' id='regresar' value='cpe_entrada'/>";
?>
		</td>
		<td align="right">
<!--<input type="button" id="btNuevo" name="btNuevo" class="btLista" value="Nuevo" onclick="cargarPagina(this.form,'cpe_entradaextnuevo.php');"/>
<input type="button" id="btEditar" name="btEditar" value="Editar" class="btLista" onclick="cargarOpcion(this.form, 'cpe_editarext.php','SELF');"/>
<input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'cpe_listaentradaextver.php', 'BLANK', 'height=500, width=1000, left=200, top=200, resizable=yes');" /><input name="btPDF" type="button" class="btLista" id="btPDF" value="PDF" onclick="cargarOpcion(this.form, 'cpi_docinternopdf.php', 'BLANK', 'height=600, width=700, left=200, top=200, resizable=yes');"/>--> <!--| <input type="button" id="btEliminar" name="btEliminar" class="btLista" value="Envío" onclick="cargarOpcion(this.form,'cpi_docinternoeditor.php');"/>

<input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="eliminarRegistroEntradaExterna(this.form, 'cpe_entrada.php?limit=0', '1', 'PERSONAS');"/>|<input type="button" id="btPDF" name="btPDF" class="btLista" value="Nuevo" onclick="cargarPagina(this.form,'cpe_entradaextnuevo.php');"/>-->
<!--<input type="button" name="btAtender" id="btAtender" class="btLista" value="Atender" onclick="cargarOpcion(this.form,'cpe_procesar.php', 'BLANK', 'height=625, width=1000, left=0, top=0, resizable=yes');"/> 
-->
<!--<input type="button" id="btAtender" name="btAtender" class="btLista" value="Atender" onclick="cargarPagina(this.form,'cpe_entradaextatender.php');"/>

"cargarOpcion(this.form,'cpi_docinternodocumento.php', 'BLANK', 'height=800, width=800, left=200, top=200, resizable=yes');"

-->
<input type="button" id="btAtender" name="btAtender" class="btLista" value="Documento" onclick="cargarOpcion(this.form,'cpi_docinternopdf_mod.php','BLANK', 'height=800, width=800, left=200, top=200, resizable=yes');"/>
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
      <th>Nro. Documento</th>
      <th>A&ntilde;o</th>
      <th>Tipo Documento</th>
      <th>Asunto</th>
      <th>Dependencia</th>
      <th>Empleado</th>
      <th>Cargo</th>
      <th>Fecha Registro</th>
      <th>Fecha Documento</th>
      <th>Plazo Atenci&oacute;n</th>
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
		
		$sa= "select * from cp_documentointerno where Cod_DocumentoCompleto = '".$field['Cod_Documento']."'";
		$qa= mysql_query($sa) or die ($sa.mysql_error());
		$fa=mysql_fetch_array($qa);
		//// _________ CONSULTO PARA OBTENER INFORMACION DEL REMITENTE ORGANISMO - DEPENDENCIA
		    $sdep="SELECT 
						mo.Organismo as organismo, 
						md.Dependencia as dependencia,
						mp.NomCompleto as NomCompleto,
						rp.DescripCargo as DescripCargo
				   FROM 
						mastorganismos mo, 
						mastdependencias md,
						mastpersonas mp,
						rh_puestos rp
				  WHERE 
						md.CodDependencia= '".$field['CodDependencia']."' AND
						mo.CodOrganismo= '".$field['Cod_Organismo']."' AND 
						mp.CodPersona = '".$field['CodPersona']."' AND 
						rp.CodCargo = '".$field['CodCargo']."'";
			$qdep=mysql_query($sdep) or die ($sdep.mysql_error());
			$fdep=mysql_fetch_array($qdep);
		
		//// ______ ESTADO
		if($field['Estado']=='EV')$estado='Enviado';
		
		//// _____ CAMBIO DE FORMATO DE FECHA PARA MOSTRAR
		//// _____ CAMBIO DE FORMATO DE FECHA PARA MOSTRAR
		list($a, $m, $d)=SPLIT( '[/.-]', $fa['FechaDocumento']); $f_documento=$d.'-'.$m.'-'.$a;
		list($a, $m, $d)=SPLIT( '[/.-]', $fa['FechaRegistro']); $f_registro=$d.'-'.$m.'-'.$a;
		
		$id= $field['Cod_Documento']."|".$field['CodPersona'];
		
		echo "
		<tr class='trListaBody' onclick='mClk(this, \"registro\");' id='$id'>
		    <td align='center'><img src='imagenes/activo2.png' style='width:20px;height=10px;' /></td>
			<td align='center'>".$field['Cod_Documento']."</td>
			<td align='center'>".$field['Periodo']."</td>
			<td align='center'>".utf8_encode($fieldtipodoc['Descripcion'])."</td>
			<td align='left'>".($fa['Asunto'])."</td>
			<td align='left'>".utf8_encode($fdep['dependencia'])."</td>
			<td align='left'>".utf8_encode($fdep['NomCompleto'])."</td>
			<td align='left'>".utf8_encode($fdep['DescripCargo'])."</td>
			<td align='center'>$f_registro</td>
			<td align='center'>$f_documento</td>
			<td align='center'>".$field['PlazoAtencion']."</td>
			<td align='center'>$estado</td>
		</tr>";
	}
	}
$rows=(int)$rows;
echo "
<script type='text/javascript' language='javascript'>
	totalRegistros($registros, \"$_ADMIN\", \"$_INSERT\", \"$_UPDATE\", \"$_DELETE\");
	totalLotes($registros, $rows, ".$_GET['limit'].");
</script>";				
?>
</table>
</div>
</td></tr></table>
</body>
</html>
