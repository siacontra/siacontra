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
<script type="text/javascript" language="javascript" src="af_fscript.js"></script>
</head>

<body>
<table width="800" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Lista de Activos de Logistica</td>
		<td align="right"><a class="cerrar"; href="javascript:window.close();">[Cerrar]</a></td>
	</tr>
</table><hr width="800" color="#333333" />
<?php
include("fphp.php");
connect();
$MAXLIMIT=30;
//	CONSULTO LA TABLA PARA SABER EL TOTAL DE REGISTROS SOLAMENTE............
if($_POST['filtro']!="") $sql="select * from lg_activofijo where (NroOrden LIKE '%".$_POST['filtro']."%' OR Descripcion LIKE '%".$_POST['filtro']."%' OR CodOrganismo LIKE '%".$_POST['filtro']."%')"; else $sql="SELECT * FROM lg_activofijo";
$query=mysql_query($sql) or die ($sql.mysql_error());
$registros=mysql_num_rows($query);

?>
<form name="frmentrada" id="frmentrada" method="post" action="af_listactivoslogistica.php?limit=0&amp;campo=<?=$campo?>">
<input type="hidden" name="tabla" id="tabla" value="<?=$tabla?>" />
<table width="800" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td width="250"></td>
		<td align="center">
			<input name="filtro" type="text" id="filtro" size="30" value="<?=$_POST['filtro']?>" /><input type="submit" value="Buscar" />
		</td>
	</tr>
</table>
<input type="hidden" name="registro" id="registro" />
<input type="hidden" name="ventana" id="ventana" value="<?=$_GET['ventana']?>"/>
<table align="center">
  <tr><td align="center"><div style="overflow:scroll; width:800px; height:400px;">
      <table width="800" class="tblLista">
        <tr class="trListaHead">
            <th width="80" scope="col">Nro. Orden</th>
            <th scope="col" width="250">Descripcion</th>
            <th scope="col" width="100">Marca</th>
            <th scope="col" width="250">Centro Costos</th>
        </tr>
	   <?php 
	if ($registros!=0) {
		//	CONSULTO LA TABLA
		if ($_POST['filtro']!="") $sql="SELECT 
		                                       *
										  FROM 
										       lg_activofijo
									     WHERE 
										      (CodOrganismo LIKE '%".$_POST['filtro']."%' OR Descripcion LIKE '%".$_POST['filtro']."%' OR NroOrden LIKE '%".$_POST['filtro']."%')
									  ORDER BY 
									          NroOrden LIMIT ".$_GET['limit'].", $MAXLIMIT";
		else $sql="SELECT
						 *
  					FROM
						lg_activofijo
			   ORDER BY
                        NroOrden LIMIT ".$_GET['limit'].", $MAXLIMIT";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		//	MUESTRO LA TABLA
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			/// ------------------------------------------------------
			list($a,$m,$d) = SPLIT('[-]',$field['FechaIngreso']); $fechaIngreso = $d.'-'.$m.'-'.$a;
			/// ------------------------------------------------------
			
				  $s_consulta = "select 
				                       cc.Descripcion as DescpCC,
									   ma.Descripcion as DescpMarcas
								  from 
								       lg_activofijo lg
									   inner join ac_mastcentrocosto cc on (cc.CodCentroCosto = lg.CodCentroCosto)
									   inner join lg_marcas ma on (ma.CodMarca = lg.CodMarca)
								  where  
									   lg.CodOrganismo= '".$field['CodOrganismo']."' and 
									   lg.NroOrden = '".$field['NroOrden']."' and 
									   lg.Secuencia = '".$field['Secuencia']."' and 
									   lg.NroSecuencia = '".$field['NroSecuencia']."'"; //echo $s_consulta;
				 $q_consulta = mysql_query($s_consulta) or die ($s_consulta.mysql_error()) ;
				 $r_consulta = mysql_num_rows($q_consulta); //echo "r_consulta = ".$r_consulta;
				 $f_consulta = mysql_fetch_array($q_consulta); //echo "Marcas = ".$f_consulta['DescpMarcas'];
				/// ------------------------------------------------------
                   
				/// ------------------------------------------------------
				
				$id = $field['CodOrganismo'].'-'.$field['NroOrden'].'-'.$field['Secuencia'].'-'.$field['NroSecuencia']; //echo"ID= ".$id;
				
			echo "
			<tr class='trListaBody' onclick='mClk(this, \"registro\"); selOperacion(\"".$field['Busqueda']."\", ".$_GET['campo'].", \"".$field['Descripcion']."\");' id='$id'>
			    <td align='center'>".$field['NroOrden']."</td>
				<td align='center'>".$field['Descripcion']."</td>
				<td align='center'>".$f_consulta['DescpMarcas']."</td>
				<td align='left'>".htmlentities($f_consulta['DescpCC'])."</td>
			</tr>";
		 }
	}
	$rows=(int)$rows;
	echo "
	<script type='text/javascript' language='javascript'>
		totalLista($registros);
	</script>";				
	?>
      </table>
  </div></td></tr></table>
</form>
</body>
</html>