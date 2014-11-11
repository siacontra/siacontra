<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--<link href="css1.css" rel="stylesheet" type="text/css" />-->
<link href="../css/estilo.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="../css/custom-theme/jquery-ui-1.8.16.custom.css" charset="utf-8" />
<link type="text/css" rel="stylesheet" href="../css/prettyPhoto.css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<script type="text/javascript" language="javascript" src="af_fscript.js"></script>
<script type="text/javascript" language="javascript" src="af_fscript01.js"></script>
<script type="text/javascript" src="../js/jquery-1.7.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery-ui-1.8.16.custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery.prettyPhoto.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/funciones.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/fscript.js" charset="utf-8"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Lista Clasificaci&oacute;n de Activos</td>
		<td align="right"></td>
        <!--<td align="right"><a class="cerrar"; href="javascript:parent.$.prettyPhoto.close();">[Cerrar]</a></td>-->
	</tr>
</table><hr width="100%" color="#333333" />
<?php
//include("fphp.php");
include("../fphp.php");
connect();
$MAXLIMIT=30;
//	CONSULTO LA TABLA PARA SABER EL TOTAL DE REGISTROS SOLAMENTE............
if($_POST['filtro']!="") $sql="select * from af_clasificacionactivo where (CodClasificacion LIKE '%".$_POST['filtro']."%' OR Descripcion LIKE '%".$_POST['filtro']."%')"; else $sql="SELECT * FROM af_clasificacionactivo";
$query=mysql_query($sql) or die ($sql.mysql_error());
$registros=mysql_num_rows($query);

?>
<form name="frmentrada" id="frmentrada" method="post" action="af_listaclasificacionactivo.php?limit=0&amp;campo=<?=$campo?>&ventana=<?=$ventana;?>">
<input type="hidden" name="tabla" id="tabla" value="<?=$_GET['tabla']?>"/>
<input type="hidden" name="registro" id="registro" />
<?
echo" <input type='hidden' name='ventana' id='ventana' value='".$ventana."'/>
      <input type='hidden' name='campo' id='campo' value='".$campo."'/>
";
?>

<table width="750" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td width="250">
			<?php 
			echo "
			<table align='center'>
				<tr>
					<td>
						<input name='btPrimero' type='button' id='btPrimero' value='&lt;&lt;' onclick='setLotes2(this.form, \"P\", $registros, ".$_GET['limit'].");' />
						<input name='btAtras' type='button' id='btAtras' value='&lt;' onclick='setLotes2(this.form, \"A\", $registros, ".$_GET['limit'].");' />
					</td>
					<td>Del</td><td><div id='desde'></div></td>
					<td>Al</td><td><div id='hasta'></div></td>
					<td>
						<input name='btSiguiente' type='button' id='btSiguiente' value='&gt;' onclick='setLotes2(this.form, \"S\", $registros, ".$_GET['limit'].");' />
						<input name='btUltimo' type='button' id='btUltimo' value='&gt;&gt;' onclick='setLotes2(this.form, \"U\", $registros, ".$_GET['limit'].");' />
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

<table width="750" class="tblLista">
  <thead>
	<tr class="trListaHead">
		<th width="80" scope="col">C&oacute;digo</th>
		<th scope="200">Descripcion</th>
        <th scope="20" align="center">Estado</th>
	</tr>
   </thead>
	<?php 
	if ($registros!=0) {
		//	CONSULTO LA TABLA
		if ($_POST['filtro']!="") $sql="SELECT 
		                                       *
										  FROM 
										       af_clasificacionactivo
									     WHERE 
										      (CodClasificacion LIKE '%".$_POST['filtro']."%' OR Descripcion LIKE '%".$_POST['filtro']."%')
									  ORDER BY 
									          CodClasificacion LIMIT $limit, $MAXLIMIT";
		else $sql="SELECT
						*
  					FROM
						af_clasificacionactivo
			   ORDER BY
                        CodClasificacion LIMIT $limit, $MAXLIMIT";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		//	MUESTRO LA TABLA
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
						
			if($ventana=="insertarDestinatarioDep"){
			 echo "
			<tr class='trListaBody' onclick='mClk(this, \"registro\"); insertarDestinatarioDep(this.id,\"".$ventana."\");' id='".$field['CodPersona']."'>
				<td align='center'>".$field['CodDependencia']."</td>
				<td align='left'>".$field['Dependencia']."</td>
				<td align='left'>".$field['NomCompleto']."</td>
				<td align='left'>".$field['DescripCargo']."</td>
			</tr>";
			
			}elseif($ventana=="insertarClasificacionActivo"){
			        if($field['Estado']=='A') $estado = Activo; else $estado = Inactivo;
					if($field['Nivel']==2){ $v_nivel = substr($field['CodClasificacion'],0,2);}
					elseif($field['Nivel']==3){$v_nivel = substr($field['CodClasificacion'],0,4);}
					elseif($field['Nivel']==1){$v_nivel = substr($field['CodClasificacion'],0,2);}
					
					$scon01 = "select CodClasificacion, Descripcion from af_clasificacionactivo where Codclasificacion='$v_nivel'";
					$qcon01 = mysql_query($scon01) or die ($scon01.mysql_error());
					$fcon01 = mysql_fetch_array($qcon01);
					
			echo "
			<tr class='trListaBody' onclick='mClk(this, \"registro\"); selEmpleado(\"".$field['Busqueda']."\", \"".$_GET['campo']."\", \"".$field['Descripcion']."\",\"".$fcon01['Descripcion']."\",\"".$fcon01['CodClasificacion']."\");' id='".$field['CodClasificacion']."'>
				<td align='center'>".$field['CodClasificacion']."</td>
				<td align='left'>".$field['Descripcion']."</td>
				<td align='center'>$estado</td>
			</tr>";
			}elseif($ventana=="cargarClasificacionActivo"){
			   if($field['Estado']=='A') $estado = Activo; else $estado = Inactivo;
										
			echo "
			<tr class='trListaBody' onclick='mClk(this, \"registro\"); selEmpleado(\"".$field['Busqueda']."\", \"".$_GET['campo']."\", \"".$field['Descripcion']."\",\"".$fcon01['Descripcion']."\",\"".$fcon01['CodClasificacion']."\");' id='".$field['CodClasificacion']."'>
				<td align='center'>".$field['CodClasificacion']."</td>
				<td align='left'>".$field['Descripcion']."</td>
				<td align='center'>$estado</td>
			</tr>";
	             		
			}
			
			}
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