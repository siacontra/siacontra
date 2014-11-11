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
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Distribuci&oacute;n | Documentos</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php

if(!$_POST) $forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"]; else $corganismo = "checked";
if(!$_POST){$fEstado="EV"; $cEstado="checked";}

$filtro = "";

if ($forganismo != "") { $filtro .= " AND (Cod_Organismo = '".$forganismo."')"; $corganismo = "checked"; } else $dorganismo = "disabled";
//if ($fcodocumento !="") { $filtro .= "AND (CodTipoDocumento= '".$fcodocumento."')"; $codocumento="checked";} else $documento = "disabled";
if ($fremitente !=""){ $filtro .="AND (Cod_Organismo= '".$fremitente."')"; $cRemitente = "checked";}else $dRemitente = "disabled";
if ($fRecibidoPor !=""){ $filtro .="AND (CodPersona= '".$fRecibidoPor."')"; $cRecibido= "checked";}else $dRecibido = "disabled";
if ($fEstado !=""){ $filtro .="AND (Estado='".$fEstado."')"; $cEstado="checked";} else $dEstado="disabled";
if ($fTdocumento !=""){ $filtro .="AND (Cod_TipoDocumento='".$fTdocumento."')"; $cTDocumento="checked";} else $dTDocumento="disabled";

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

//// -------------------------------------------------------------------------------------------
echo "
<form name='frmentrada' action='cpe_distribucionext.php?limit=0' method='POST'>
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
     <input type='checkbox' id='checkFechaRecibido' name='checkFechaRecibido' value='1' $cFechaRecibido onclick='enabledFechaRecibido(this.form);'/>
	 desde:<input type='text' name='fdesde' id='fdesde' size='8' maxlength='10' $dFechaRecibido value='$fechadesde'/>
	 hasta:<input type='text' name='fhasta' id='fhasta' size='8' maxlength='10' $dFechaRecibido value='$fechahasta'/>
   </td>
</tr>
<tr>
 <td width='125' align='right'>Dep. Destinataria:</td>
 <td>
    <input type='checkbox' id='checkRemitente' name='checkRemitente' value='1' $cRemitente onclick='enabledRemitente(this.form);'/>
	 <select id='fremitente' name='fremitente' class='selectBig' $dRemitente>
	 <option value=''></option>";
	  getRemitente(4, $fremitente);
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
<td width='125' align='right'>Tipo Documento</td>
<td>
  <input type='checkbox' id='checkTdocumento' name='checkTdocumento' value='1' $cTDocumento onclick='enabledTdocumento(this.form);'/>
	 <select id='fTdocumento' name='fTdocumento' class='selectMed' $dTDocumento>
	 <option value=''></option>";
	  getTdocumento(1, $fTdocumento);
	echo"
	</select>
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
		     cp_documentodistribucion 
	   WHERE 
	         Cod_Organismo = '".$_SESSION['ORGANISMO_ACTUAL']."'  and Procedencia='EXT'
			 $filtro
	ORDER BY 
	         Cod_Documento"; //echo $sql;
$query=mysql_query($sql) or die ($sql.mysql_error());
$registros=mysql_num_rows($query);
$rows=$registros; 
?>
<table width="1000" class="tblBotones">
<tr>
<td><div id="rows"></div></td>
<td align="center">
<?php 
echo "
<input type='hidden' id='regresar' name='regresar' value='cpe_distribucionext'/>
<table align='center'>
   <tr>
     <td>
		<input name='btPrimero' type='button' id='btPrimero' value='&lt;&lt;' onclick='setLotes(this.form, \"P\", $registros,".$limit.");' />
		<input name='btAtras' type='button' id='btAtras' value='&lt;' onclick='setLotes(this.form, \"A\", $registros, ".$limit.");' />
      </td>
      <td>Del</td><td><div id='desde'></div></td>
      <td>Al</td><td><div id='hasta'></div></td>
      <td>
		<input name='btSiguiente' type='button' id='btSiguiente' value='&gt;' onclick='setLotes(this.form, \"S\", $registros,".$limit.");' />
		<input name='btUltimo' type='button' id='btUltimo' value='&gt;&gt;' onclick='setLotes(this.form, \"U\", $registros,".$limit.");' />
      </td>
  </tr>
</table>";
?>
		</td>
		<td align="right">
<!--<input type="button" id="btNuevo" name="btNuevo" class="btLista" value="Nuevo" onclick="cargarPagina(this.form,'cpe_entradaextnuevo.php?regresar=cpe_distribucionext');"/>
<input type="button" id="btEditar" name="btEditar" value="Editar" class="btLista" onclick="CargarPagina(this.form, 'cpe_editarext.php');"/>
<input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'cpe_entradaextver.php', 'BLANK', 'height=500, width=900, left=200, top=200, resizable=yes');" />

<input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="eliminarRegistro(this.form, 'personas.php?accion=ELIMINAR', '1', 'PERSONAS');"/>
<input type="button" name="btAtender" id="btAtender" class="btLista" value="Atender" onclick="cargarOpcion(this.form,'cpe_procesar.php', 'BLANK', 'height=625, width=1000, left=0, top=0, resizable=yes');"/>
<input name="btPDF" type="button" class="btLista" id="btPDF" value="PDF" onclick="cargarVentana(this.form, 'personas_pdf.php', 'height=800, width=800, left=200, top=200, resizable=yes');" /> -->
		</td>
       <td width="400"></td> 
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
      <th>A&ntilde;o</th>
      <th>Tipo Documento</th>
      <th>Dependencia</th>
      <th>Empleado</th>
      <th>Cargo</th>
      <th>FechaEnviado</th>
      <th>Plazo Atenci&oacute;n</th>
      <th>Dias Pendientes</th>
      <th>Estado</th>
    
   </tr>
<?php 
if ($registros!=0) {
	for ($i=0; $i<$rows; $i++) {
		$field=mysql_fetch_array($query);
		
		//// _________ CONSULTO PARA OBTENER LOS DATOS DE DISTRIBUCION
		$sdist = "SELECT
						cpc.Descripcion,
						me.CodDependencia,
						mp.NomCompleto,
						c.DescripCargo,
						cpd.FechaDistribucion
					FROM
					    cp_documentodistribucion cpd
						INNER JOIN cp_tipocorrespondencia cpc ON (cpd.Cod_TipoDocumento = cpc.Cod_TipoDocumento)
						INNER JOIN mastpersonas mp ON (mp.CodPersona = cpd.CodPersona)
						INNER JOIN mastempleado me ON (me.CodPersona = mp.CodPersona)
						INNER JOIN rh_puestos c ON (cpd.CodCargo = c.CodCargo)
				  WHERE 
				        cpd.Cod_Organismo = '".$field['Cod_Organismo']."' and 
						cpd.Procedencia='EXT' and
						cpd.Cod_TipoDocumento='".$field['Cod_TipoDocumento']."' and
						cpd.CodPersona='".$field['CodPersona']."' and
						cpd.CodCargo='".$field['CodCargo']."' and 
						cpd.Cod_Documento = '".$field['Cod_Documento']."'";
	  $qdist=mysql_query($sdist) or die ($sdist.mysql_error());
	  $fdist=mysql_fetch_array($qdist);
		
	  $sdep="select Dependencia from mastdependencias where  CodDependencia='".$fdist['CodDependencia']."'";	
	  $qdep=mysql_query($sdep) or die ($sdep.mysql_error());
	  $fdep=mysql_fetch_array($qdep);
		
		
		
		
	//// -----------------------------------------------------------------------
	$fechaCompara= date("Y-m-d");
	$date1=strtotime($field['FechaDistribucion']);
	$date2=strtotime($fechaCompara);
	$s = ($date1)-($date2);
	$d = intval($s/86400);
	$s -= $d*86400;
	$h = intval($s/3600);
	$s -= $h*3600;
	$m = intval($s/60);
	$s -= $m*60;
	
	$dif= (($d*24)+$h).hrs." ".$m."min";
	$dif2= abs($d.$space); 
	$dif2= $field['PlazoAtencion'] - $dif2 ;
	 
	//// ------------------------------------------------------------------------	
	
		if($field['Estado']=='EV') $estado='Enviado';
		if($field['Estado']=='RE') $estado='Recibido';
		
		//echo"confidencial=".$field['FlagConfidencial'];
		//// _______________________________
		if($field['FlagConfidencial']==1){$b='checked onclick="this.checked=!this.checked"';}else{$b='disabled="disabled"';}
		//echo"A=".$b;
		//// _______________________________
		//// _____ CAMBIO DE FORMATO DE FECHA PARA MOSTRAR
		list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaDistribucion']); $f_distribucion=$d.'-'.$m.'-'.$a;
		
		echo "
		<tr class='trListaBody' onclick='mClk(this, \"registro\");'>
		    <td align='center'><img src='imagenes/activo2.png' style='width:20px;height=10px;' /></td>
			<td align='center'>".$field['Cod_Documento']."</td>
			<td align='center'>".$field['Periodo']."</td>
			<td align='center'>".$fdist['Descripcion']."</td>
			<td align='center'>".utf8_encode($fdep['Dependencia'])."</td>
			<td align='center'>".utf8_encode($fdist['NomCompleto'])."</td>
			<td align='center'>".$fdist['DescripCargo']."</td>
			<td align='center'>$f_distribucion</td>
			<td align='center'>'".$field['PlazoAtencion']."' días</td>
			<td align='center'>$dif2</td>
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
