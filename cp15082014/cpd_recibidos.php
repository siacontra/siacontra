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
		<td class="titulo">Entrada de Documentos Internos</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php

if((!$_POST)or($volver=='0')) $forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];

$filtro = "";

if ($forganismo != "") { $filtro .= " AND (CodOrganismo = '".$forganismo."')"; $corganismo = "checked"; } else $dorganismo = "disabled";
if ($fcodocumento !="") { $filtro .= "AND (CodTipoDocumento= '".$fcodocumento."')"; $codocumento="checked";} else $documento = "disabled";
if ($fechaRecibido !=""){ $filtro .= "AND (FechaRegistro= '".$fechaRecibido."')"; $cFechaRecibido = "checked";} else $dFechaRecibido = "disabled";
if ($fremitente !=""){ $filtro .="AND (Remitente= '".$fremitente."')"; $cRemitente = "checked";}else $dRemitente = "disabled";
if ($fRecibidoPor !=""){ $filtro .="AND (RecibidoPor= '".$fRecibidoPor."')"; $cRecibido= "checked";}else $dRecibido = "disabled";
if ($fordenardoc !=""){ $filtro .="AND (Cod_TipoDocumento= '".$fordenardoc."')"; $cOrdenarDoc= "checked";}else $dOrdenarDoc = "disabled";
if ($fEstado !=""){ $filtro .="AND (Estado='".$fEstado."')"; $cEstado="checked";} else $dEstado="disabled";
if ($fDependencia !=""){$filtro .="AND (CodDependencia='".$fDependencia."')"; $cDependencia="checked";}else $dDependencia="disabled";
//if (){}

$MAXLIMIT=30;
//	ELIMINO EL REGISTRO
if ($_GET['accion']=="ELIMINAR") {
	//
	$sql="DELETE FROM mastpersonas WHERE CodPersona='".$_POST['registro']."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$_GET['limit']=0;
}

echo "
<form name='frmentrada' action='cp_entrada.php?filtro=".$_GET['filtro']."' method='POST' onsubmit='return false'>
<input type='hidden' name='limit' value='".$_GET['limit']."'>
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
	 <input type='text' name='fechaRecibido' id='fechaRecibido' size='6' maxlength='4' $dFechaRecibido value='$fechaRecibido'/>
   </td>
</tr>
<tr>
 <td width='125' align='right'>Remitente:</td>
 <td>
    <input type='checkbox' id='checkRemitente' name='checkRemitente' value='1' $cRemitente onclick='enabledRemitente(this.form);'/>
	 <select id='fremitente' name='fremitente' class='selectBig' $dRemitente>
	 <option value=''></option>";
	  getRemitente(0, $fremitente);
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
<td width='125' align='right'></td>
<td>
</td>
<td width='125' align='right'>Estado:</td>
<td>
	<input type='checkbox' id='checkEstado' name='checkEstado' value='1' $cEstado onclick='enabledEstado(this.form);'/>
	<select name='fEstado' id='fEstado' class='selectMed' $dEstado>
		<option value=''></option>";
		getEstado(0, $fEstado);
		echo "
	</select>
</td>
</tr>
</table>
</div>
<center><input type='button' name='btBuscar' value='Buscar'></center>
<br /><div class='divDivision'>Listado de Documentos</div><br />";

///_________________________________________________________________________________________
$year = date("Y");
$sql="SELECT * 
        FROM 
		     cp_documentoextentrada 
	   WHERE 
	         CodOrganismo = '".$_SESSION['ORGANISMO_ACTUAL']."' 
			 $filtro
	ORDER BY 
	         Cod_TipoDocumento";
$query=mysql_query($sql) or die ($sql.mysql_error());
$registros=mysql_num_rows($query);
$rows=$registros; 
?>
<table width="1000" class="tblBotones">
<tr>
<td><div id="rows"></div></td>
<td width="250">
<?php 
echo "
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
<!-- <input type="button" id="btNuevo" name="btNuevo" class="btLista" value="Nuevo" onclick="cargarPagina(this.form,'cpe_entradaextnuevo.php');"/> -->
<input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'cpe_entradaver.php', 'BLANK', 'height=625, width=1000, left=0, top=0, resizable=yes');" />
<input name="btPDF" type="button" class="btLista" id="btPDF" value="PDF" onclick="cargarVentana(this.form, 'personas_pdf.php', 'height=800, width=800, left=200, top=200, resizable=yes');" />
<!--<input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="eliminarRegistro(this.form, 'personas.php?accion=ELIMINAR', '1', 'PERSONAS');" />-->
| <input type="button" name="btAtender" id="btAtender" class="btLista" value="Atender" onclick="cargarOpcion(this.form,'cpe_procesar.php', 'BLANK', 'height=625, width=1000, left=0, top=0, resizable=yes');"/>
 
		</td>
	</tr>
</table>
<input type="hidden" name="registro" id="registro" />
<table align="center">
<tr>
  <td align="center">
  <div style="overflow:scroll; width:1000px; height:400px;">

<table width="1300" class="tblLista">
	<tr class="trListaHead">
      <th></th>
      <th>Tipo Documento</th>
      <th>Asunto</th>
      <th>Nro.Registro</th>
      <th>FechaRecibido</th>
      <th>Actividad</th>
      <th>Organismo</th>
      <th>Dependencia</th>
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
		
		//// _________ CONSULTO PARA OBTENER INFORMACION DEL REMITENTE ORGANISMO - DEPENDENCIA
		$sqlorgadep="SELECT pforg.Organismo as organismo, 
		                    pforg.RepresentLegal as r_legalorg, 
							pfdep.RepresentLegal as r_legaldep, 
							pfdep.Dependencia as dependencia 
		               FROM 
					        pf_organismosexternos pforg, 
							pf_dependenciasexternas pfdep 
					  WHERE 
					        pforg.Cod_Organismo= pfdep.CodOrganismo AND 
							pfdep.CodDependencia= '".$field['Cod_Dependencia']."'";
		$qryorgadep=mysql_query($sqlorgadep) or die ($sqlorgadep.mysql_error());
		$fieldorgadep=mysql_fetch_array($qryorgadep);
		
		
		if($field['Estado']=='PE'){
		  $estado='Pendiente';
		}else{
		  if($field['Estado']=='RE'){
		    $estado='Recibido';
		  }else{
		    $estado='Completado';
		  }
	    }
		//// _____ CAMBIO DE FORMATO DE FECHA PARA MOSTRAR
		list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaRegistro']); $f_Registro=$d.'-'.$m.'-'.$a;
		
		echo "
		<tr class='trListaBody' onclick='mClk(this, \"registro\");' id='".$field['Cod_Documento']."'>
		    <td align='center'><img src='imagenes/activo2.png' style='width:20px;height=10px;' /></td>
			<td align='center'>".$fieldtipodoc['Descripcion']."</td>
			<td align='left'>".$field['Asunto']."</td>
			<td align='center'>".$field['Cod_Documento']."</td>
			<td align='center'>$f_Registro</td>
			<td align='left'>".$field['Comentario']."</td>
			<td align='left'>".$fieldorgadep['organismo']."</td>
			<td align='left'>".$fieldorgadep['dependencia']."</td>
			<td align='center'>'2 - días'</td>
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
</div></td></tr></table>
</form>
</body>
</html>