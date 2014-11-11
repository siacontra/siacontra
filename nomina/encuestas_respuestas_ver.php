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
</head>

<body>
<table width="700" class="tblLista">
  <tr class="trListaHead">
		<th scope="col">Pregunta</th>
		<th width="125" scope="col">Valor M&iacute;nimo</th>
		<th width="125" scope="col">Valor M&aacute;ximo</th>
		<th width="125" scope="col">Valor</th>
  </tr>
  
	<?php 
	include("fphp.php");
	connect();
	//	CONSULTO EL TOTAL DE LAS MUESTRAS
	$sql="SELECT Sujeto FROM rh_encuesta_sujeto WHERE Secuencia='".$registro."' GROUP BY Sujeto";
	$query_muestra=mysql_query($sql) or die ($sql.mysql_error());
	$rows_muestra=mysql_num_rows($query_muestra);
	for ($j=1; $j<=$rows_muestra; $j++) {
		$field_muestra=mysql_fetch_array($query_muestra);
		echo "
		<tr class='trListaBody2'><td colspan='4'>Sujeto ".$field_muestra['Sujeto']."</td></tr>
		<tr class='trListaBody' id='".$field_muestra['Sujeto']."'>
			<td colspan='4'>";
				//	CONSULTO LA TABLA
				$sql="SELECT rh_encuesta_sujeto.Sujeto, rh_encuesta_sujeto.Pregunta, rh_encuesta_preguntas.Descripcion, rh_encuesta_preguntas.ValorMinimo, rh_encuesta_preguntas.ValorMaximo, rh_encuesta_sujeto.Valor FROM rh_encuesta_sujeto, rh_encuesta_preguntas WHERE (rh_encuesta_sujeto.Pregunta=rh_encuesta_preguntas.Pregunta) AND (rh_encuesta_sujeto.Secuencia='".$registro."' AND rh_encuesta_sujeto.Sujeto='".$field_muestra['Sujeto']."') ORDER BY rh_encuesta_sujeto.Sujeto, rh_encuesta_sujeto.Pregunta";
				$query=mysql_query($sql) or die ($sql.mysql_error());
				$rows=mysql_num_rows($query);
				//	MUESTRO LA TABLA
				for ($i=0; $i<$rows; $i++) {
					$field=mysql_fetch_array($query);
					echo "
					<table width='692' cellpadding='1'>
						<tr>
							<td>".$field['Descripcion']."</td>
							<td width='125' align='center'>".$field['ValorMinimo']."</td>
							<td width='125' align='center'>".$field['ValorMaximo']."</td>
							<td width='125' align='center'><input type='text' size='5' maxlength='2' value='".$field['Valor']."' name='".$field_muestra['Sujeto'].":".$field['Pregunta']."' readonly /></td>
						</tr>
					</table>";
				}
			echo "
			</td>
		</tr>";
	}
	?>
 </table>
</body>
</html>