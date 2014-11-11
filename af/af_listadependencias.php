<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--<link href="css1.css" rel="stylesheet" type="text/css" />-->
<link href="css2.css" rel="stylesheet" type="text/css" />
<link href="../css/estilo.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="../css/prettyPhoto.css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" language="javascript" src="af_fscript.js"></script>
<script type="text/javascript" src="../js/jquery-1.7.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery-ui-1.8.16.custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery.prettyPhoto.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/funciones.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/fscript.js" charset="utf-8"></script>
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
<thead>
<tr class="trListaHead">
    <th width="120" scope="col">Cod.Dependencia</th>
    <th scope="200">Dependencia</th>
</tr>
</thead>
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
											(mastdependencias.Representante=mastempleado.CodPersona) AND
										   (mastpersonas.CodPersona=mastdependencias.Representante) AND
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
						rh_puestos.DescripCargo
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
			 echo "
			<tr class='trListaBody' onclick='mClk(this, \"registro\"); insertarDestinatarioDep(this.id,\"".$ventana."\");' id='".$field['CodPersona']."'>
				<td align='center'>".$field['CodDependencia']."</td>
				<td align='left'>".$field['Dependencia']."</td>
				<td align='left'>".$field['NomCompleto']."</td>
				<td align='left'>".$field['DescripCargo']."</td>
			</tr>";
			
			}else{
			echo "
			<tr class='trListaBody' onclick='mClk(this, \"registro\"); selEmpleado(\"".$field['Busqueda']."\", ".$_GET['campo'].", \"".$field['Dependencia']."\");' id='".$field['CodDependencia']."'>
			    <td align='center'>".$field['CodDependencia']."</td>
				<td align='left'>".$field['Dependencia']."</td>
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