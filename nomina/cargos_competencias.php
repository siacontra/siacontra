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
<form name="frmentrada" id="frmentrada" method="post" action="cargos_competencias.php">
<?php
include("fphp.php");
connect();
$ahora=date("Y-m-d H:i:s");
if ($accion=="VER") { $disabled="disabled"; $ver="disabled"; }
if ($accion=="INSERTAR") {
	$sql="SELECT * FROM  rh_cargocompetencia WHERE Competencia='".$competencia."' AND CodCargo='".$codcargo."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	if ($rows==0) {
		$sql="INSERT INTO rh_cargocompetencia (Competencia, CodCargo, UltimoUsuario, UltimaFecha) values ('".$competencia."', '".$codcargo."', '".$_SESSION['USUARIO_ACTUAL']."', '".$ahora."')";
		$query=mysql_query($sql) or die ($sql.mysql_error());
	}
}
?>

<input type="hidden" name="sec" id="sec" value="" />
<input type="hidden" name="codcargo" id="codcargo" value="<?=$codcargo?>" />
<table width="600" class="tblBotones">
 <tr>
	<td>
		<input name="btInsertar" type="button" class="btLista" id="btInsertar" value="Insertar" onclick="cargarVentana(this.form, 'lista_competencias.php?accion=INSERTAR&limit=0', 'height=500, width=800, left=200, top=200, resizable=yes');" <?=$disabled?> />
		<input name="btBorrar" type="button" class="btLista" id="btBorrar" value="Borrar" onclick="optCargoCompetencia(this.form, 'ELIMINAR');" <?=$ver?> />
	</td>
 </tr>
</table>

<?
$sql="SELECT MIN(PuntajeMin) AS Min, MAX(PuntajeMax) AS Max FROM rh_gradoscompetencia";
$query_puntaje=mysql_query($sql) or die ($sql.mysql_error());
$field_puntaje=mysql_fetch_array($query_puntaje);
?>

<table width="600" class="tblLista">
	<tr style="background-color:#666666">
    	<th>
			<?php
            $sql="SELECT * FROM rh_gradoscompetencia ORDER BY Grado";
            $query_grado=mysql_query($sql) or die ($sql.mysql_error());
            $rows_grado=mysql_num_rows($query_grado);
            for ($k=0; $k<$rows_grado; $k++) {
                $field_grado=mysql_fetch_array($query_grado);
                $min[$k]=$field_grado['PuntajeMin'];
                $max[$k]=$field_grado['PuntajeMax'];
                $col[$k]=$field_grado['PuntajeMax']-$field_grado['PuntajeMin']+1;
                $grado[$k]=$field_grado['Descripcion'];
            }
            echo "<table class='grillaTable'>";
            
            echo "<tr class='grillaTr'>";
            for ($k=0; $k<$rows_grado; $k++) echo "<td align='center' class='grillaTd' colspan='".$col[$k]."' style='font-size:8px;'><em>".$grado[$k]."</em></td>";
            echo "</tr>";
            
            echo "<tr class='grillaTr'>";
            for ($k=0; $k<$rows_grado; $k++) {
                for ($j=$min[$k]; $j<=$max[$k]; $j++) echo "<td align='center' class='grillaTd' width='25' style='font-size:7px;'><b>".$j."</b></td>";
            }
            echo "</tr>";
			
			echo "</table>";
            ?>
    	</th>
    </tr>
	<?php
	$k=0;
	$sql="SELECT rcc.Competencia, ref.Descripcion AS NomCompetencia, ref.ValorRequerido, ref.ValorMinimo, md.Descripcion AS TipoCompetencia FROM rh_cargocompetencia rcc INNER JOIN rh_evaluacionfactores ref ON (rcc.Competencia=ref.Competencia) INNER JOIN mastmiscelaneosdet md ON (ref.TipoCompetencia=md.CodDetalle AND md.CodMaestro='TIPOCOMPE') WHERE CodCargo='".$codcargo."' ORDER BY md.Descripcion, rcc.Competencia";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	while($field=mysql_fetch_array($query)) {
		$l++;
		if ($grupo!=$field['TipoCompetencia']) {
			$grupo=$field['TipoCompetencia'];
			echo "<tr class='trListaBody2'><td>".($field['TipoCompetencia'])."</td></tr>";
		} 
		?>
		<tr class="trListaBody" onclick="mClk(this, 'sec');" id="<?=$field['Competencia']?>">
			<td>
            	<?
				echo "<table class='grillaTable'>";
					echo "<tr class='grillaTr'>";
					echo "<td>".($field['NomCompetencia'])."</td>";
					echo "</tr>";
				echo"</table>";
				
				echo "<table class='grillaTable'>";
					echo "<tr class='grillaTr'>";
					for ($k=0; $k<$rows_grado; $k++) {
						for ($j=$min[$k]; $j<=$max[$k]; $j++) echo "<td align='center' class='grillaTd' width='25' style='font-size:7px;' id='R_".$j."_".$l."'>&nbsp;</td>";
					}
					echo "</tr>";
					
					echo "<tr class='grillaTr'>";
					for ($k=0; $k<$rows_grado; $k++) {
						for ($j=$min[$k]; $j<=$max[$k]; $j++) echo "<td align='center' class='grillaTd' width='25' style='font-size:7px;' id='M_".$j."_".$l."'>&nbsp;</td>";
					}
					echo "</tr>";
				echo"</table>";
				?>
            </td>
		</tr>
        <?
		$sql="SELECT ValorRequerido, ValorMinimo FROM rh_evaluacionfactores WHERE Competencia='".$field['Competencia']."'";
		$query_valor=mysql_query($sql) or die ($sql.mysql_error());
		$rows_valor=mysql_num_rows($query_valor);
		if ($rows_valor!=0) $field_valor=mysql_fetch_array($query_valor);
		?>
        <script language="javascript">
			setRequerido2(<?=$field_puntaje['Min']?>, <?=$field_puntaje['Max']?>, <?=$field_valor['ValorRequerido']?>, <?=$l?>);
		</script>
		<script language="javascript">
			setMinimo2(<?=$field_puntaje['Min']?>, <?=$field_puntaje['Max']?>, <?=$field_valor['ValorMinimo']?>, <?=$l?>);
		</script>
		<?
	}
	$rows=(int) $rows;
	?>
</table>

<? if ($accion!="VER") { ?>
<script type="text/javascript" language="javascript">
	totalCompetencias(<?=$rows?>);
</script>
<? } ?>

</form>
</body>
</html>