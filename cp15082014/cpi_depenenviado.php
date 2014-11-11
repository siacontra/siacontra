<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp.php");
connect();
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
		<td class="titulo">Documentos Enviados</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php

if(!$_POST) $forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
if(!$_POST){$fEstado="EV"; $cEstado="checked";}
if(!$_POST) $cDestinataria="checked"; else $cDestinataria="checked";

$filtro = "";

if ($forganismo != "") { $filtro .= " AND (CodOrganismo = '".$forganismo."')"; $corganismo = "checked"; } else $dorganismo = "disabled";
if ($fechaRecibido !=""){ $filtro .= "AND (FechaRegistro= '".$fechaRecibido."')"; $cFechaRecibido = "checked";} else $dFechaRecibido = "disabled";
if ($f_Remitente !=""){ $filtro .="AND (Cod_Dependencia= '".$fremitente."')"; $cRemitente = "checked";}else $dRemitente = "disabled";
if ($fRecibidoPor !=""){ $filtro .="AND (RecibidoPor= '".$fRecibidoPor."')"; $cRecibido= "checked";}else $dRecibido = "disabled";
if ($fEstado !=""){ $filtro .="AND (Estado='".$fEstado."')"; $cEstado="checked";} else $dEstado="disabled";
if ($fTdocumento !=""){ $filtro .="AND (Cod_TipoDocumento='".$fTdocumento."')"; $cTDocumento="checked";} else $dTDocumento="disabled";

if ($fProcedencia !=""){ $filtro .="AND (Procedencia='".$fProcedencia."')"; $cProcedencia="checked";} else $dProcedencia="disabled";


$MAXLIMIT=30;

echo "
<form name='frmentrada' action='cpi_depenenviado.php?limit=0' method='POST'>
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
   <td width='125' align='right' >Fecha Enviado:</td>
   <td> 
     <input type='checkbox' id='checkFechaEnviado' name='checkFechaEnviado' value='1' $cFechaEnviado onclick='enabledFechaEnviado(this.form);'/>
	 desde:<input type='text' name='fdesde' id='fdesde' size='8' maxlength='10' $dFechaEnviado value='$fdesde'/>
	 hasta:<input type='text' name='fhasta' id='fhasta' size='8' maxlength='10' $dFechaEnviado value='$fhasta'/>
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
<td width='125' align='right'>Procedencia</td>
<td>
  <input type='checkbox' id='checkProcedencia' name='checkProcedencia' value='1' $cProcedencia onclick='enabledProcedencia(this.form);'/>
	<select name='fProcedencia' id='fProcedencia' class='selectMed' $dProcedencia>
		 <option value=''></option>";
		getProcedencia( 0, $fProcedencia);
		echo "
	</select>
</td>
</tr>

<tr>
 <td width='125' align='right'>Dep. Destinataria:</td>
 <td>
	<input type='checkbox' id='checkDestinataria' name='checkDestinataria' value='1' $cDestinataria onclick='this.checked=true'/>
	 <select id='fDestinataria' name='fDestinataria' class='selectBig' $dDestinataria>";
	 // getDepDestinataria(0, $fDestinataria);
	 
	 
	  getDependenciaSeguridad($fDestinataria, $forganismo, 3);
	  
	  
	echo"
	</select>
 </td>
<td width='125' align='right'>Estado:</td>
<td>
	<input type='checkbox' id='checkEstado' name='checkEstado' value='1' $cEstado onclick='enabledEstado(this.form);'/>
	<select name='fEstado' id='fEstado' class='selectMed' $dEstado>
		 <option value=''></option>";
		getEstadoAtender( 1, $fEstado);
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
	  getTdocumento(0, $fTdocumento);
	echo"
	</select>
</td>
<td width='125' align='right'></td>
<td></td>
</tr>
</table>
</div>
<center><input type='submit' name='btBuscar' value='Buscar'></center>
<br /><div class='divDivision'>Listado de Documentos</div><br />
<form/>";
///_________________________________________________________________________________________
///_________________________________________________________________________________________
/// CONSULTA PREVIA PARA OBTENER EL CODPERSONA DEL USUARIO ACTUAL
   $sconsulta="select * from usuarios where Usuario='".$_SESSION['USUARIO_ACTUAL']."'";
   $qconsulta=mysql_query($sconsulta) or die ($sconsulta.mysql_error());
   $fconsulta=mysql_fetch_array($qconsulta);
   
   /*$sdep = "select * from mastdependencias where CodPersona ='".$fconsulta['CodPersona']."'";
   $qdep = mysql_query($sdep) or die ($sdep.mysql_error());
   $fdep = mysql_fetch_array($qdep);*/
   
   $sdep = "select 
                   md.CodInterno as codinterno
              from 
			       mastempleado me
				   inner join mastdependencias md on (me.CodDependencia=md.CodDependencia)
			 where 
			       me.CodPersona ='".$fconsulta['CodPersona']."'"; //echo   $sdep;
   $qdep = mysql_query($sdep) or die ($sdep.mysql_error());
   $fdep = mysql_fetch_array($qdep);
   
   //echo "codigo interno =".$fdep['codinterno'];
///_________________________________________________________________________________________

$year = date("Y");
/*
$sql="SELECT 
            *
        FROM 
		     cp_documentointerno
	   WHERE 
	         CodOrganismo <>'' AND
			 CodInterno = '".$fdep['codinterno']."'	 $filtro
	ORDER BY 
	          Periodo, Cod_DocumentoCompleto";*/
	$sql="SELECT 
            *
        FROM 
		     cp_documentointerno
	   WHERE 
	         CodOrganismo <>'' AND
			 Cod_Dependencia = '".$fDestinataria."'	 $filtro
	ORDER BY 
	          Periodo, Cod_DocumentoCompleto";           
	          
	          
	          
	           //echo $sql;
$query=mysql_query($sql) or die ($sql.mysql_error());
$registros=mysql_num_rows($query);
$rows=$registros; //echo $rows;

?>
<table width="1000" class="tblBotones">
<tr>
<td><div id="rows"></div></td>
<td width="250">

		</td>
		<td align="right">
<input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'cp_verdocumento01.php', 'BLANK', 'height=500, width=900, left=200, top=200, resizable=yes');" />
<input name="btPDF" type="button" class="btLista" id="btPDF" value="Documento" onclick="cargarOpcion(this.form, 'cpi_depenviadodocumento.php','BLANK', 'height=800, width=800, left=200, top=200, resizable=yes');" />
<!--<input name="btPDF" type="button" class="btLista" id="btPDF" value="Documento" onclick="cargarOpcion(this.form, 'cpi_dependenciaenviado.php','BLANK', 'height=800, width=800, left=200, top=200, resizable=yes');" />-->
		</td>
	</tr>
</table>
<input type="hidden" name="registro" id="registro" />
<table align="center">
<tr>
  <td align="center">
  <div style="overflow:scroll; width:1000px; height:250px;">
<table width="1300" class="tblLista">
	<tr class="trListaHead">
      <th></th>
      <th>Nro.Documento</th>
      <th>A&ntilde;o</th>
      <th>Tipo Documento</th>
      <th>Remitente</th>
      <th>Asunto</th>
      <th>Comentario</th>
      <th>Fecha Registro</th>
      <th>Fecha Documento</th>
      <th>Plazo Atenci&oacute;n</th>
      <th>Estado</th>
   </tr>
<?php 
if ($registros!=0) {
	for ($i=0; $i<$rows; $i++) {
		$field=mysql_fetch_array($query);
		
		//// _________ CONSULTO PARA OBTENER LOS DATOS DE DISTRIBUCION
		$sdist="select
	                  cpt.Descripcion,
					  mp.NomCompleto
					  
				  from
					  cp_tipocorrespondencia cpt,
					  mastpersonas mp
					  
                 where
					  cpt.Cod_TipoDocumento = '".$field['Cod_TipoDocumento']."' and
					  mp.CodPersona = '".$field['Cod_Remitente']."'"; //echo $sdist;
		$qdist=mysql_query($sdist) or die ($sdist.mysql_error());
		$rdist=mysql_num_rows($qdist);
		$fdist=mysql_fetch_array($qdist);
	
		if($field['Estado']=='EV')$estado='Enviado';
		if($field['Estado']=='RE')$estado='Recibido';

		
		//// _______________________________
		//// _____ CAMBIO DE FORMATO DE FECHA PARA MOSTRAR
		if($field['FechaRegistro']=='0000-00-00'){$f_registro='';}else{ list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaRegistro']); $f_registro=$d.'-'.$m.'-'.$a;}
		if($field['FechaDocumento']=='0000-00-00'){$f_documento='';}else{ list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaDocumento']); $f_documento=$d.'-'.$m.'-'.$a;} 
		
		
		//// _______________________________
		$sqldist="select 
		            * 
                from 
		            cp_documentodistribucion 
	           where 
	                Cod_Organismo = '".$field['CodOrganismo']."' and 
					Cod_Documento = '".$field['Cod_DocumentoCompleto']."' and 
					Periodo = '".$field['Periodo']."' and
			        CodPersona='".$fconsulta['CodPersona']."'
	        order by 
	                Cod_Documento"; //echo $sql;
        $qrydist=mysql_query($sqldist) or die ($sqldist.mysql_error());
        $rdist=mysql_num_rows($qrydist);
        
		if($rdist!=0)$fieldist = mysql_fetch_array($qrydist);
		$id = $field['Cod_TipoDocumento'].'|'.$field['Cod_DocumentoCompleto'].'|'.$fconsulta['CodPersona'].'|'.$field['Periodo'].'|'.$field['CodOrganismo'];
		
		echo "
		<tr class='trListaBody' onclick='mClk(this, \"registro\");' id='$id'>
		    <td align='center'><img src='imagenes/activo2.png' style='width:20px;height=10px;' /></td>
			<td align='center'>".$field['Cod_DocumentoCompleto']."</td>
			<td align='center'>".$field['Periodo']."</td>
			<td align='center'>".htmlentities($fdist['Descripcion'])."</td>
		    <td align='center'>".htmlentities($fdist['NomCompleto'])."</td>
			
			<td align='left'>".$field['Asunto']."</td>
			<td align='left'>".$field['Descripcion']."</td>			
			<td align='center'>$f_registro</td>
			<td align='center'>$f_documento</td>
			<td align='center'>'".$field['PlazoAtencion']."' día(s)</td>
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
