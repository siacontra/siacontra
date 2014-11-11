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
<script type="text/javascript" src="../js/jquery-1.7.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery-ui-1.8.16.custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery.prettyPhoto.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/funciones.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/fscript.js" charset="utf-8"></script>
</head>

<body>
<div id="cajaModal"></div>
<!-- pretty -->
<span class="gallery clearfix"></span>
<table width="800" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Lista Proveedores</td>
		<td></td>
		<!--<?
        if($_GET['cierre']!=1){?>
        <td align="right"><a class="cerrar"; href="javascript:parent.$.prettyPhoto.close();">[Cerrar]</a></td>
        <? }else{?>
		<td align="right"><a class="cerrar"; href="javascript:window.close();">[Cerrar]</a></td>
        <? }?>-->
	</tr>
</table><hr width="800" color="#333333" />
<?php
include("fphp.php");
connect();
$MAXLIMIT=30;
//	CONSULTO LA TABLA PARA SABER EL TOTAL DE REGISTROS SOLAMENTE............
if($_POST['filtro']!="") $sql="select * from mastproveedores where (CodProveedor LIKE '%".$_POST['filtro']."%' OR RepresentanteLegal LIKE '%".$_POST['filtro']."%' OR ContactoVendedor LIKE '%".$_POST['filtro']."')"; else $sql="SELECT * FROM mastproveedores";
$query=mysql_query($sql) or die ($sql.mysql_error());
$registros=mysql_num_rows($query);

?>
<form name="frmentrada" id="frmentrada" method="post" action="af_listaproveedor.php?limit=0&amp;campo=<?=$campo?>">
<input type="hidden" name="tabla" id="tabla" value="<?=$tabla?>" />
<table width="800" class="tblBotones">
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
<input type="hidden" name="registro" id="registro" />

<?
echo"<input type='hidden' id='campo' name='campo' value='".$campo."'/>
     <input type='hidden' name='ventana' id='ventana' value='".$ventana."'/> ";


?>
<table width="800" class="tblLista">
<thead>
	<tr class="trListaHead">
        <th scope="col" width="20">Cod. Proveedor</th>
		<th scope="col" width="200">Nombre Comercial</th>
        <th scope="col" width="200">Direcci&oacute;n</th>
        <th scope="col" width="50">Tel&eacute;fono</th>
	</tr>
  </thead>
	<?php 
	if ($registros!=0) {
		//	CONSULTO LA TABLA
		if ($_POST['filtro']!="") $sql="SELECT 
		                                       *
										  FROM 
										       mastpersonas
									     WHERE 
										      (CodPersona LIKE '%".$_POST['filtro']."%' OR 
											  Apellido1 LIKE '%".$_POST['filtro']."%' OR 
											  Apellido2 LIKE '%".$_POST['filtro']."%' OR
											  Nombres LIKE '%".$_POST['filtro']."%' OR 
											  Busqueda LIKE '%".$_POST['filtro']."%' OR 
											  NomCompleto LIKE '%".$_POST['filtro']."%') and 
											  EsProveedor = 'S'
									  ORDER BY 
									          CodPersona LIMIT ".$_GET['limit'].", $MAXLIMIT";
		else $sql="SELECT
						 *
  					FROM
						mastpersonas
					WHERE
					     EsProveedor='S'
			    ORDER BY
                        CodPersona LIMIT ".$_GET['limit'].", $MAXLIMIT";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		//	MUESTRO LA TABLA
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			//// ------------------------------------------------------------------
			//// ------------------------------------------------------------------
						
			if($ventana==insertarDestinatarioDep){
			 echo "
			<tr class='trListaBody' onclick='mClk(this, \"registro\"); insertarDestinatarioDep(this.id,\"".$ventana."\");' id='".$field['CodPersona']."'>
				<td align='center'>".$field['CodDependencia']."</td>
				<td align='left'>".$field['Dependencia']."</td>
				<td align='left'>".$field['NomCompleto']."</td>
				<td align='left'>".$field['DescripCargo']."</td>
			</tr>";
			
			}else{
				if($field['Estado']=='A') $estado = Activo; else $estado = Inactivo;
				if($field['TipoActivo']='I') $tActivo = 'Individual'; else $tActivo = 'Principal';
				/// ------------------------------------------------------
				 $s_consulta = "select 
				                       *
								  from 
								       mastpersonas
								  where  
								       CodPersona = '".$field['CodPersona']."'";
				 $q_consulta = mysql_query($s_consulta) or die ($s_consulta.mysql_error()) ;
				 $f_consulta = mysql_fetch_array($q_consulta);
				/// ------------------------------------------------------
			echo "
			<tr class='trListaBody' onclick='mClk(this, \"registro\"); selOperacion(\"".$field['Busqueda']."\", ".$campo.", \"".$f_consulta['NomCompleto']."\");' id='".$field['CodPersona']."'>
			    <td align='center'>".$field['CodPersona']."</td>
				<td align='left'>".$f_consulta['NomCompleto']."</td>
				<td align='left'>".$f_consulta['Direccion']."</td>
				<td align='right'>".$f_consulta['Telefono1']."</td>
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