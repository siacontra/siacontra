<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_lg.js"></script>
<script type="text/javascript" language="javascript">
function insertarListaEmpleadoActuacion(persona, detalles) {
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_funciones_pf.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=insertarListaEmpleadoActuacion&persona="+persona+"&detalles="+detalles);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			var datos = resp.split("||");
			if (datos[0].trim() != "") alert(resp);
			else {
				var newTr = document.createElement("tr");
				newTr.className = "trListaBody";
				newTr.setAttribute("onclick", "mClk(this, 'selauditor');");
				newTr.id = persona;
				opener.document.getElementById("listaAuditores").appendChild(newTr);
				opener.document.getElementById(persona).innerHTML = datos[1];
				window.close();
			}
		}
	}
}
</script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Lista de Personas</td>
		<td align="right"><a class="cerrar"; href="javascript:window.close();">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php
include("fphp.php");
connect();
$MAXLIMIT=30;
//	CONSULTO LA TABLA PARA SABER EL TOTAL DE REGISTROS SOLAMENTE............
if ($filtro!="") $filtro = " AND (mp.CodPersona LIKE '%".$filtro."%' OR 
								  mp.Busqueda LIKE '%".$filtro."%' OR 
								  mp.Ndocumento LIKE '%".$filtro."%' OR 
								  mp.DocFiscal LIKE '%".$filtro."%') ";
//	-----------------------------------
if ($flagcliente == "S") $filtro_flag .= " AND mp.EsCliente = 'S' ";
if ($flagproveedor == "S") $filtro_flag .= " AND mp.EsProveedor = 'S' ";
if ($flagempleado == "S") $filtro_flag .= " AND mp.EsEmpleado = 'S' AND me.Estado = 'A' ";
if ($flagotros == "S") $filtro_flag .= " AND mp.EsOtros = 'S' ";

$sql = "SELECT
			mp.CodPersona,
			mp.NomCompleto, 
			mp.EsCliente, 
			mp.EsProveedor, 
			mp.EsEmpleado, 
			mp.EsOtros, 
			mp.Ndocumento, 
			mp.DocFiscal,
			pr.CodFormaPago,
			pr.CodTipoServicio,
			pr.CodTipoDocumento,
			pr.CodTipoPago,
			pr.DiasPago,
			mp.Busqueda,
			ts.Descripcion AS NomTipoServicio,
			i.FactorPorcentaje,
			td.CodRegimenFiscal
		FROM
			mastpersonas mp
			LEFT JOIN mastempleado me ON (mp.CodPersona = me.CodPersona)
			LEFT JOIN mastproveedores pr ON (mp.CodPersona = pr.CodProveedor)
			LEFT JOIN masttiposervicio ts ON (pr.CodTipoServicio = ts.CodTipoServicio)
			LEFT JOIN masttiposervicioimpuesto tsi ON (ts.CodTipoServicio = tsi.CodTipoServicio)
			LEFT JOIN mastimpuestos i ON (tsi.CodImpuesto = i.CodImpuesto)
			LEFT JOIN ap_tipodocumento td ON (td.CodTipoDocumento = pr.CodTipoDocumento)
		WHERE mp.Estado = 'A' $filtro_flag $filtro
		GROUP BY CodPersona";
$query = mysql_query($sql) or die ($sql.mysql_error());
$registros = mysql_num_rows($query);
?>
<form name='frmlista' id='frmlista' method='get' action='listado_personas.php'>
<input type="hidden" name="limit" id="limit" value="<?=$limit?>" />
<input type="hidden" name="cod" id="cod" value="<?=$cod?>" />
<input type="hidden" name="nom" id="nom" value="<?=$nom?>" />
<input type="hidden" name="ventana" id="ventana" value="<?=$ventana?>" />
<input type="hidden" name="codorganismo" id="codorganismo" value="<?=$codorganismo?>" />
<input type="hidden" name="seldetalle" id="seldetalle" value="<?=$seldetalle?>" />
<input type="hidden" name="detalles" id="detalles" value="<?=$detalles?>" />
<table width="700" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td>Buscar: <input type="text" name="filtro" id="filtro" size="40" /></td>
		<td width="250">
			<?php 
			echo "
			<table align='center'>
				<tr>
					<td>
						<input name='btPrimero' type='button' id='btPrimero' value='&lt;&lt;' onclick='setLotes(this.form, \"P\", $registros, ".$limit.", \"".$_GET['ordenar']."\");' />
						<input name='btAtras' type='button' id='btAtras' value='&lt;' onclick='setLotes(this.form, \"A\", $registros, ".$limit.", \"".$_GET['ordenar']."\");' />
					</td>
					<td>Del</td><td><div id='desde'></div></td>
					<td>Al</td><td><div id='hasta'></div></td>
					<td>
						<input name='btSiguiente' type='button' id='btSiguiente' value='&gt;' onclick='setLotes(this.form, \"S\", $registros, ".$limit.", \"".$_GET['ordenar']."\");' />
						<input name='btUltimo' type='button' id='btUltimo' value='&gt;&gt;' onclick='setLotes(this.form, \"U\", $registros, ".$limit.", \"".$_GET['ordenar']."\");' />
					</td>
				</tr>
			</table>";
			?>
		</td>
	</tr>
</table>
<input type="hidden" name="registro" id="registro" />
<table width="700" class="tblLista">
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
		$sql="SELECT
					mp.CodPersona,
					mp.NomCompleto, 
					mp.EsCliente, 
					mp.EsProveedor, 
					mp.EsEmpleado, 
					mp.EsOtros, 
					mp.Ndocumento, 
					mp.DocFiscal,
					pr.CodFormaPago,
					pr.CodTipoServicio,
					pr.CodTipoDocumento,
					pr.CodTipoPago,
					pr.DiasPago,
					mp.Busqueda,
					ts.Descripcion AS NomTipoServicio,
					i.FactorPorcentaje,
					td.CodRegimenFiscal
				FROM
					mastpersonas mp
					LEFT JOIN mastempleado me ON (mp.CodPersona = me.CodPersona)
					LEFT JOIN mastproveedores pr ON (mp.CodPersona = pr.CodProveedor)
					LEFT JOIN masttiposervicio ts ON (pr.CodTipoServicio = ts.CodTipoServicio)
					LEFT JOIN masttiposervicioimpuesto tsi ON (ts.CodTipoServicio = tsi.CodTipoServicio)
					LEFT JOIN mastimpuestos i ON (tsi.CodImpuesto = i.CodImpuesto AND i.CodRegimenFiscal = 'I')
					LEFT JOIN ap_tipodocumento td ON (td.CodTipoDocumento = pr.CodTipoDocumento)
				WHERE mp.Estado = 'A' $filtro_flag $filtro
				GROUP BY CodPersona
				ORDER BY CodPersona LIMIT ".$limit.", $MAXLIMIT";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		//	MUESTRO LA TABLA
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			if ($field['EsCliente']=="S") $escliente="checked"; else $escliente="";
			if ($field['EsProveedor']=="S") $esproveedor="checked"; else $esproveedor="";
			if ($field['EsEmpleado']=="S") $esempleado="checked"; else $esempleado="";
			if ($field['EsOtros']=="S") $esotros="checked"; else $esotros="";
			
			if ($ventana == "orden_compras") {
				$fhasta = getFechaFinContinuo(date("d-m-Y"), $field['DiasPago']);
							
				echo "
				<tr class='trListaBody' onclick='mClk(this, \"registro\"); selListadoOrdenCompra(\"".($field['NomCompleto'])."\", \"".$cod."\", \"".$nom."\", \"".$field['CodFormaPago']."\", \"".$field['CodTipoServicio']."\", \"".$field['NomTipoServicio']."\", \"".$field['FactorPorcentaje']."\", \"".$field['CodTipoPago']."\", \"".$field['DiasPago']."\", \"".$fhasta."\");' id='".$field['CodPersona']."'>
					<td align='center'>".$field['CodPersona']."</td>
					<td align='left'>".($field['NomCompleto'])."</td>
					<td align='center'><input type='checkbox' $escliente disabled /></td>
					<td align='center'><input type='checkbox' $esproveedor disabled /></td>
					<td align='center'><input type='checkbox' $esempleado disabled /></td>
					<td align='center'><input type='checkbox' $esotros disabled /></td>
					<td align='left'>".$field['Ndocumento']."</td>
					<td align='left'>".$field['DocFiscal']."</td>
				</tr>";
			}
			elseif ($ventana == "ap_obligaciones") {
				echo "
				<tr class='trListaBody' onclick='mClk(this, \"registro\"); selListadoObligacion(\"$codorganismo\", \"$field[CodPersona]\");' id='".$field['CodPersona']."'>
					<td align='center'>".$field['CodPersona']."</td>
					<td align='left'>".($field['NomCompleto'])."</td>
					<td align='center'><input type='checkbox' $escliente disabled /></td>
					<td align='center'><input type='checkbox' $esproveedor disabled /></td>
					<td align='center'><input type='checkbox' $esempleado disabled /></td>
					<td align='center'><input type='checkbox' $esotros disabled /></td>
					<td align='left'>".$field['Ndocumento']."</td>
					<td align='left'>".$field['DocFiscal']."</td>
				</tr>";
			}
			elseif ($ventana == "listadoPersonas") {
				echo "
				<tr class='trListaBody' onclick='mClk(this, \"registro\"); listadoPersonas(\"".$field['CodPersona']."\", \"".$seldetalle."\");' id='".$field['CodPersona']."'>
					<td align='center'>".$field['CodPersona']."</td>
					<td align='left'>".($field['NomCompleto'])."</td>
					<td align='center'><input type='checkbox' $escliente disabled /></td>
					<td align='center'><input type='checkbox' $esproveedor disabled /></td>
					<td align='center'><input type='checkbox' $esempleado disabled /></td>
					<td align='center'><input type='checkbox' $esotros disabled /></td>
					<td align='left'>".$field['Ndocumento']."</td>
					<td align='left'>".$field['DocFiscal']."</td>
				</tr>";
			} 
			elseif ($ventana == "insertarListaEmpleadoActuacion") {
				echo "
				<tr class='trListaBody' onclick='mClk(this, \"registro\"); insertarListaEmpleadoActuacion(\"".$field['CodPersona']."\", \"".$detalles."\");' id='".$field["CodPersona"]."'>
					<td align='center'>".$field['CodPersona']."</td>
					<td align='left'>".($field['NomCompleto'])."</td>
					<td align='center'><input type='checkbox' $escliente disabled /></td>
					<td align='center'><input type='checkbox' $esproveedor disabled /></td>
					<td align='center'><input type='checkbox' $esempleado disabled /></td>
					<td align='center'><input type='checkbox' $esotros disabled /></td>
					<td align='left'>".$field['Ndocumento']."</td>
					<td align='left'>".$field['DocFiscal']."</td>
				</tr>";
			}
			elseif ($ventana == "ap_caja_chica") {
				echo "
				<tr class='trListaBody' onclick='mClk(this, \"registro\"); selListadoCajaChica(\"$codorganismo\", \"$field[CodPersona]\");' id='".$field['CodPersona']."'>
					<td align='center'>".$field['CodPersona']."</td>
					<td align='left'>".($field['NomCompleto'])."</td>
					<td align='center'><input type='checkbox' $escliente disabled /></td>
					<td align='center'><input type='checkbox' $esproveedor disabled /></td>
					<td align='center'><input type='checkbox' $esempleado disabled /></td>
					<td align='center'><input type='checkbox' $esotros disabled /></td>
					<td align='left'>".$field['Ndocumento']."</td>
					<td align='left'>".$field['DocFiscal']."</td>
				</tr>";
			}
			else {			
				echo "
				<tr class='trListaBody' onclick='mClk(this, \"registro\"); selListado(\"".($field['NomCompleto'])."\", \"".$cod."\", \"".$nom."\");' id='".$field['CodPersona']."'>
					<td align='center'>".$field['CodPersona']."</td>
					<td align='left'>".($field['NomCompleto'])."</td>
					<td align='center'><input type='checkbox' $escliente disabled /></td>
					<td align='center'><input type='checkbox' $esproveedor disabled /></td>
					<td align='center'><input type='checkbox' $esempleado disabled /></td>
					<td align='center'><input type='checkbox' $esotros disabled /></td>
					<td align='left'>".$field['Ndocumento']."</td>
					<td align='left'>".$field['DocFiscal']."</td>
				</tr>";
			}
		}
	}
	$rows=(int)$rows;
	echo "
	<script type='text/javascript' language='javascript'>
		totalLista($registros);
		totalLotes($registros, $rows, ".$limit.");
	</script>";				
	?>
</table>
</form>
</body>
</html>