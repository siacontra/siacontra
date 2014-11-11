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
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Salida de Documentos Externos</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php

if((!$_POST)or($volver=='0')) $forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];

$MAXLIMIT=30;
$filtro = "";

if ($forganismo != "") { $filtro .= " AND (CodOrganismo = '".$forganismo."')"; $corganismo = "checked"; } else $dorganismo = "disabled";
if ($fcodocumento !="") { $filtro .= "AND (CodTipoDocumento= '".$fcodocumento."')"; $codocumento="checked";} else $documento = "disabled";
if ($fechaRecibido !=""){ $filtro .= "AND (FechaRegistro= '".$fechaRecibido."')"; $cFechaRecibido = "checked";} else $dFechaRecibido = "disabled";
if ($fremitente !=""){ $filtro .="AND (Remitente= '".$fremitente."')"; $cRemitente = "checked";}else $dRemitente = "disabled";
if ($fRecibidoPor !=""){ $filtro .="AND (RecibidoPor= '".$fRecibidoPor."')"; $cOrdenarDoc= "checked";}else $dOrdenarDoc = "disabled";
if ($fordenardoc !=""){ $filtro .="AND (Cod_TipoDocumento= '".$fordenardoc."')"; $cRecibido= "checked";}else $dRecibido = "disabled";
if ($fEstado !=""){ $filtro .="AND (Estado='".$fEstado."')"; $cEstado="checked";} else $dEstado="disabled";
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
   <td width='125' align='right' >Fecha Enviado:</td>
   <td> 
     <input type='checkbox' id='checkFechaRecibido' name='checkFechaRecibido' value='1' $cFechaRecibido onclick='enabledFechaRecibido(this.form);'/>
	 <input type='text' name='fechaRecibido' id='fechaRecibido' size='6' maxlength='4' $dFechaRecibido value='$fechaRecibido'/>
   </td>
</tr>
<tr>
 <td width='125' align='right'>Destinatario:</td>
 <td>
    <input type='checkbox' id='checkRemitente' name='checkRemitente' value='1' $cRemitente onclick='enabledRemitente(this.form);'/>
	 <select id='fremitente' name='fremitente' class='selectBig' $dRemitente>
	 <option value=''></option>";
	  getRemitente(0, $fremitente);
	echo"
	</select>
 </td>
 <td width='125' align='right'>Enviado por:</td>
 <td><input type='checkbox' id='checkRecibido' name='checkRecibido' value='1' $cRecibido onclick='enabledRecibidoPor(this.form);'/>
     <select id='fRecibidoPor' name='fRecibidoPor' class='selectBig' $dRecibido>
	  <option value=''></option>";
	   getRecibidoPor(0,$fRecobidoPor);
     echo"
	 </select>
  </td>
</tr>
<tr>
<td width='125' align='right'>Ordenar Por:</td>
<td>
	<input type='checkbox' name='chkOrdenarDoc' id='chkOrdenarDoc' value='1' $cOrdenarDoc onclick='enabledOrdenarDocumento(this.form);'/>
	<select name='fordenardoc' id='fordenardoc' class='selectMed' $dOrdenarDoc>
		<option value=''></option>";
		getOrdenarDocumento(0, $fordenardoc);
		echo "
	</select>
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
<center><input type='button' name='btBuscar' value='Buscar' onclick='filtroPersonas(this.form, ".$_GET['limit'].");'></center>
<br /><div class='divDivision'>Listado de Documentos</div><br />";

$_GET['filtro']=strtr($_GET['filtro'], "*", "'");
$_GET['filtro']=strtr($_GET['filtro'], ";", "%");
//	CONSULTO LA TABLA PARA SABER EL TOTAL DE REGISTROS SOLAMENTE............
if ($_GET['filtro']!="") $sql="SELECT * FROM mastpersonas WHERE (".$_GET['filtro'].") ".$_GET['ordenar'];
else $sql="SELECT * FROM mastpersonas ".$_GET['ordenar'];
$query=mysql_query($sql) or die ($sql.mysql_error());
$registros=mysql_num_rows($query);
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
						<input name='btPrimero' type='button' id='btPrimero' value='&lt;&lt;' onclick='setLotes(this.form, \"P\", $registros, ".$_GET['limit'].", \"".$_GET['ordenar']."\");' />
						<input name='btAtras' type='button' id='btAtras' value='&lt;' onclick='setLotes(this.form, \"A\", $registros, ".$_GET['limit'].", \"".$_GET['ordenar']."\");' />
					</td>
					<td>Del</td><td><div id='desde'></div></td>
					<td>Al</td><td><div id='hasta'></div></td>
					<td>
						<input name='btSiguiente' type='button' id='btSiguiente' value='&gt;' onclick='setLotes(this.form, \"S\", $registros, ".$_GET['limit'].", \"".$_GET['ordenar']."\");' />
						<input name='btUltimo' type='button' id='btUltimo' value='&gt;&gt;' onclick='setLotes(this.form, \"U\", $registros, ".$_GET['limit'].", \"".$_GET['ordenar']."\");' />
					</td>
				</tr>
			</table>";
			?>
		</td>
		<td align="right">
			<? if ($factualizar == "Proveedor") $bteditar = "personas_proveedor_editar"; else $bteditar = "personas_editar"; ?>
			<input name="btNuevo" type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'personas_nuevo.php');" />
			<input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" onclick="cargarOpcion(this.form, '<?=$bteditar?>.php?accion=EDITAR', 'SELF');" />
			<input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'personas_ver.php', 'BLANK', 'height=625, width=1000, left=0, top=0, resizable=yes');" />
			<input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="eliminarRegistro(this.form, 'personas.php?accion=ELIMINAR', '1', 'PERSONAS');" />
			<input name="btPDF" type="button" class="btLista" id="btPDF" value="PDF" onclick="cargarVentana(this.form, 'personas_pdf.php', 'height=800, width=800, left=200, top=200, resizable=yes');" />
		</td>
	</tr>
</table>
<input type="hidden" name="registro" id="registro" />
<table width="1000" class="tblLista">
	<tr class="trListaHead">
		<th width="70" scope="col">Persona</th>
		<th scope="col">B&uacute;squeda</th>
		<th width="25" scope="col">Cli</th>
		<th width="25" scope="col">Pro</th>
		<th width="25" scope="col">Emp</th>
		<th width="25" scope="col">Otr</th>
		<th width="90" scope="col">Nro. Documento</th>
		<th width="90" scope="col">Documento Fiscal</th>
	</tr>
	<?php 
	if ($registros!=0) {
		//	CONSULTO LA TABLA
		if ($_GET['filtro']!="") $sql="SELECT CodPersona, Busqueda, EsCliente, EsProveedor, EsEmpleado, EsOtros, Ndocumento, DocFiscal FROM mastpersonas WHERE (".$_GET['filtro'].") ".$_GET['ordenar']." LIMIT ".$_GET['limit'].", $MAXLIMIT";
		else $sql="SELECT CodPersona, Busqueda, EsCliente, EsProveedor, EsEmpleado, EsOtros, Ndocumento, DocFiscal FROM mastpersonas ".$_GET['ordenar']." LIMIT ".$_GET['limit'].", $MAXLIMIT";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		//	MUESTRO LA TABLA
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			if ($field['EsCliente']=="S") $escliente="checked"; else $escliente="";
			if ($field['EsProveedor']=="S") $esproveedor="checked"; else $esproveedor="";
			if ($field['EsEmpleado']=="S") $esempleado="checked"; else $esempleado="";
			if ($field['EsOtros']=="S") $esotros="checked"; else $esotros="";
			echo "
			<tr class='trListaBody' onclick='mClk(this, \"registro\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field['CodPersona']."'>
				<td align='center'>".$field['CodPersona']."</td>
				<td align='left'>".htmlentities($field['Busqueda'])."</td>
				<td align='center'><input type='checkbox' $escliente disabled /></td>
				<td align='center'><input type='checkbox' $esproveedor disabled /></td>
				<td align='center'><input type='checkbox' $esempleado disabled /></td>
				<td align='center'><input type='checkbox' $esotros disabled /></td>
				<td align='left'>".$field['Ndocumento']."</td>
				<td align='left'>".$field['DocFiscal']."</td>
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
</form>
</body>
</html>