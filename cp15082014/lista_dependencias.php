<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
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
		<td class="titulo">Lista de Dependencias</td>
		<td align="right"><a class="cerrar"; href="javascript:window.close();">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php
include("fphp.php");
connect();
$MAXLIMIT=30;
//	CONSULTO LA TABLA PARA SABER EL TOTAL DE REGISTROS SOLAMENTE............
if ($_POST['filtro']!="") $sql="SELECT * FROM mastdependencias WHERE (CodDependencia LIKE '%".$_POST['filtro']."%' OR Dependencia LIKE '%".$_POST['filtro']."%')";
else $sql="SELECT * FROM mastdependencias";
$query=mysql_query($sql) or die ($sql.mysql_error());
$registros=mysql_num_rows($query);
?>
<form name="frmlista" id="frmlista" method="post" action="lista_dependencias.php?limit=0&ventana=insertarDestinatarioDep&tabla=item">
<input type="hidden" name="tabla" id="tabla" value="<?=$tabla?>" />
<table width="750" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td width="250">
			<?php 
			echo "
			<table align='center'>
				<tr>
					<td>
						<input name='btPrimero' type='button' id='btPrimero' value='&lt;&lt;' onclick='setLotes(this.form, \"P\", $registros, ".$_GET['limit'].");' />
						<input name='btAtras' type='button' id='btAtras' value='&lt;' onclick='setLotes(this.form, \"A\", $registros, ".$_GET['limit'].");' />
					</td>
					<td>Del</td><td><div id='desde'></div></td>
					<td>Al</td><td><div id='hasta'></div></td>
					<td>
						<input name='btSiguiente' type='button' id='btSiguiente' value='&gt;' onclick='setLotes(this.form, \"S\", $registros, ".$_GET['limit'].");' />
						<input name='btUltimo' type='button' id='btUltimo' value='&gt;&gt;' onclick='setLotes(this.form, \"U\", $registros, ".$_GET['limit'].");' />
					</td>
				</tr>
			</table>";
			?>
		</td>
		<td align="center">
			<input name="filtro" type="text" id="filtro" size="30" value="<?=$_POST['filtro']?>" /><input type="submit" value="Buscar" />
		</td>
	</tr>
</table>
<input type="hidden" name="registro" id="registro" />
<input type="hidden" name="ventana" id="ventana" value="<?=$_GET['ventana']?>"/>
<table width="750" class="tblLista">
	<tr class="trListaHead">
		<th width="80" scope="col">Cod.Dependencia</th>
		<th scope="200">Dependencia</th>
		<th width="200" scope="col">Representante</th>
		<th width="200" scope="col">Cargo</th>
	</tr> 
	<?php  
	if ($registros!=0) {
		//	CONSULTO LA TABLA
		if ($_POST['filtro']!="") $sql="SELECT 
		                                    mastdependencias.CodDependencia,
											mastdependencias.Dependencia,
											mastpersonas.NomCompleto,
											mastempleado.CodEmpleado,
											mastempleado.CodPersona,
											mastpersonas.Busqueda,
											rh_puestos.DescripCargo
										 FROM 
										    mastdependencias,
										    mastempleado, 
										    mastpersonas,
											rh_puestos 
									    WHERE 
										   (mastpersonas.NomCompleto LIKE '%".$_POST['filtro']."%' OR
										    mastdependencias.Dependencia LIKE '%".$_POST['filtro']."%' OR
										    mastdependencias.CodDependencia LIKE '%".$_POST['filtro']."%' OR
											rh_puestos.DescripCargo LIKE '%".$_POST['filtro']."%') AND
											(mastdependencias.CodPersona=mastempleado.CodPersona) AND
										   (mastpersonas.CodPersona=mastdependencias.CodPersona) AND
										   (mastempleado.Estado='A') AND
										   (mastempleado.CodCargo=rh_puestos.CodCargo)
									ORDER BY 
									        mastdependencias.CodDependencia LIMIT ".$_GET['limit'].", $MAXLIMIT";
		else $sql="SELECT
						mastdependencias.CodDependencia,
						mastdependencias.Dependencia,
						mastpersonas.NomCompleto,
						mastempleado.CodEmpleado,
						mastempleado.CodPersona,
						mastpersonas.Busqueda,
						rh_puestos.DescripCargo,
						rh_puestos.CodCargo
  					FROM
						mastdependencias,
						mastempleado,
						mastpersonas,
						rh_puestos
				   WHERE 
				       (mastdependencias.CodPersona=mastempleado.CodPersona) AND
					   (mastpersonas.CodPersona=mastdependencias.CodPersona) AND
					   (mastempleado.Estado='A') AND
					   (mastempleado.CodCargo=rh_puestos.CodCargo)
			   ORDER BY
                       mastdependencias.CodDependencia LIMIT ".$_GET['limit'].", $MAXLIMIT";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query); 
		//	MUESTRO LA TABLA
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			if ($field['EsCliente']=="S") $escliente="checked"; else $escliente="";
			if ($field['EsProveedor']=="S") $esproveedor="checked"; else $esproveedor="";
			if ($field['EsEmpleado']=="S") $esempleado="checked"; else $esempleado="";
			if ($field['EsOtros']=="S") $esotros="checked"; else $esotros="";
			
			if($ventana==insertarDestinatarioDep){
			  // ---------------------------------
			  $scon = "select
			                 max(UltimaFecha)
						 from 
						     rh_empleadonivelacion 
						where
						     CodCargo = '".$field['CodCargo']."' and 
							 CodPersona = '".$field['CodPersona']."'";
			  $qcon = mysql_query($scon) or die ($scon.mysql_error());
			  $fcon = mysql_fetch_array($qcon);
			  // ---------------------------------
			  $con2 = "select 
			                 rhn.CodPersona,
							 mtp.NomCompleto
						 from 
						     rh_empleadonivelacion rhn 
							 inner join mastpersonas mtp on (mtp.CodPersona = rhn.CodPersona) 
					    where 
						     rhn.CodCargo = '".$field['CodCargo']."' and 
							 rhn.UltimaFecha = '".$fcon['0']."' and 
							 rhn.CodPersona = '".$field['CodPersona']."'";
			  $qcon2 = mysql_query($con2) or die ($con2.mysql_error());
			  $fcon2 = mysql_fetch_array($qcon2);
			  // ---------------------------------	
				
			 echo "
			<tr class='trListaBody' onclick='mClk(this, \"registro\"); insertarDestinatarioDep(this.id,\"".$ventana."\");' id='".$fcon2['CodPersona']."'>
				<td align='center'>".$field['CodDependencia']."</td>
				<td align='left'>".htmlentities($field['Dependencia'])."</td>
				<td align='left'>".htmlentities($fcon2['NomCompleto'])."</td>
				<td align='left'>".htmlentities($field['DescripCargo'])."</td>
			</tr>";
			
			}else{
			echo "
			<tr class='trListaBody' onclick='mClk(this, \"registro\"); selEmpleado(\"".$field['Busqueda']."\", ".$_GET['campo'].", \"".$field['DescripCargo']."\");' id='".$field['CodPersona']."'>
				<td align='center'>".$field['CodEmpleado']."</td>
				<td align='left'>".$field['Busqueda']."</td>
				<td align='center'><input type='checkbox' $escliente disabled /></td>
				<td align='center'><input type='checkbox' $esproveedor disabled /></td>
				<td align='center'><input type='checkbox' $esempleado disabled /></td>
				<td align='center'><input type='checkbox' $esotros disabled /></td>
				<td align='left'>".$field['Ndocumento']."</td>
				<td align='left'>".$field['DocFiscal']."</td>
			</tr>";
		}}
	}
	$rows=(int)$rows;
	echo "
	<script type='text/javascript' language='javascript'>
		totalLista($registros);
		totalLotes($registros, $rows, ".$_GET['limit'].");
	</script>";				
	?>
</table>
</form>
</body>
</html>
