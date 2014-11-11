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
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" language="javascript" src="af_fscript.js"></script>
<script type="text/javascript" language="javascript" src="af_fscript2.js"></script>
<script type="text/javascript" src="../js/jquery-1.7.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery-ui-1.8.16.custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery.prettyPhoto.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/funciones.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/fscript.js" charset="utf-8"></script>
</head>
<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Clasificaci&oacute;n de Activos - Publicaci&oacute;n 20</td>
		<td align="right"></td>
        <!--<td align="right"><a class="cerrar"; href="javascript:parent.$.prettyPhoto.close();">[Cerrar]</a></td>-->
	</tr>
</table><hr width="100%" color="#333333" />

<?php
include("../fphp.php");
///include("fphp.php");
connect();
$MAXLIMIT=30;

if(!$_POST){ $fBienes = '01'; $cBienes = "checked";} 

$filtro2="";
if ($fBienes!=""){$filtro2 .=" AND (CodClasificacion ='".$fBienes."')"; $cBienes = "checked"; }else $dBienes = "disabled";

//	CONSULTO LA TABLA PARA SABER EL TOTAL DE REGISTROS SOLAMENTE............
if ($_POST['filtro']!="") $sql="SELECT * FROM af_clasificacionactivo20 WHERE (CodClasificacion LIKE '%".$_POST['filtro']."%' OR Descripcion LIKE '%".$_POST['filtro']."%')";
else $sql="SELECT * FROM af_clasificacionactivo20 where CodClasificacion like '$fBienes%'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$registros=mysql_num_rows($query);

?>
<form name="frmlista" id="frmlista" method="post" action="af_listadoclasificacion20.php?limit=0">
<input type="hidden" name="tabla" id="tabla" value="<?=$tabla?>" />
<table id="filtro" width="750" class="tblBotones">
 <tr>
   <td width="20">Bienes:</td>
   <td><input type="checkbox" id="chkbienes" name="chkbienes" <?=$cBienes;?> onclick="enabledBienes(this.form);"/>
      <select id="fBienes" name="fBienes" <?=$dBienes;?> >
       <?
         $v_valor[0]= "01"; $v_descp[0]= "Bienes Inmuebles";
		 $v_valor[1]= "02"; $v_descp[1]= "Bienes Muebles";
		 $valor = 2;
		 for($a=0; $a<$valor; $a++ ){
		   if($v_valor[$a] == $fBienes) echo"<option value='$v_valor[$a]' selected>$v_descp[$a]</option>";
		   else echo"<option value='$v_valor[$a]'>$v_descp[$a]</option>";
		 }
	   ?>
      </select></td>
 </tr>
</table>
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

<? echo"<input type='hidden' name='campo' id='campo' value='".$campo."'/>
        <input type='hidden' name='ventana' id='ventana' value='".$ventana."'/>";
?>
<table width="750" class="tblLista">
<thead>
	<tr class="trListaHead">
		<th width="75" scope="col">C&oacute;digo</th>
		<th scope="col">Descripci&oacute;n</th>
		<th width="50" scope="col">Nivel</th>
		<th width="100" scope="col">Estado</th>
	</tr>
 </thead>
	<?php 
	if ($registros!=0) {
		//	CONSULTO LA TABLA
		if ($_POST['filtro']!="") $sql="SELECT 
		                                       *
										 FROM 
										       af_clasificacionactivo20
									    WHERE 
										      (CodClasificacion LIKE '%".$_POST['filtro']."%' OR Descripcion LIKE '%".$_POST['filtro']."%')
									ORDER BY 
									        CodClasificacion LIMIT ".$_GET['limit'].", $MAXLIMIT"; 
		else $sql="SELECT * FROM af_clasificacionactivo20 where CodClasificacion like '$fBienes%' ORDER BY CodClasificacion LIMIT ".$_GET['limit'].", $MAXLIMIT";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		//	MUESTRO LA TABLA
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			if($field['Estado']=='A') $estado='Activo'; else $estado='Inactivo';
			if($ventana=="insertarClasificacionPub20"){
			 echo "
			  <tr class='trListaBody' onclick='mClk(this, \"registro\"); insertarClasificacionPub20(this.id,\"".$ventana."\", \"".$campo."\",\"".$field['Descripcion']."\");' id='".$field['CodClasificacion']."'>
			    <td align='center'>".$field['CodClasificacion']."</td>
				<td align='left'>".$field['Descripcion']."</td>
				<td align='left'>".$field['Nivel']."</td>
				<td align='left'>$estado</td>
			</tr>";
			}else{
			  echo "
			  <tr class='trListaBody' onclick='mClk(this, \"registro\"); selEmpleado(\"".$field['Busqueda']."\", ".$campo.", \"".$field['Descripcion']."\");' id='".$field['CodClasificacion']."'>
			    <td align='center'>".$field['CodClasificacion']."</td>
				<td align='left'>".$field['Descripcion']."</td>
				<td align='left'>".$field['Nivel']."</td>
				<td align='left'>$estado</td>
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