<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_nomina.js"></script>
</head>
<body>
<form name="frmentrada" id="frmentrada" method="post" action="conceptos_tiponomina.php" onsubmit="return false;">
<?php
include("fphp_nomina.php");
connect();
if ($accion=="NUEVO") $disabled="disabled";
elseif ($accion=="VER") { $disabled="disabled"; $ver="disabled"; }
elseif ($accion=="AGREGAR") { 
	$sql="SELECT * FROM tiponomina ORDER BY CodTipoNom";
	$query_tipos=mysql_query($sql) or die ($sql.mysql_error());
	while($field_tipos=mysql_fetch_array($query_tipos)) {
		$cod=$field_tipos['CodTipoNom'];
		if ($_POST[$cod]) {
			$sql="SELECT * FROM pr_conceptotiponomina WHERE CodConcepto='".$concepto."' AND CodTipoNom='".$_POST[$cod]."'";
			$query_select=mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_select)==0) {
				$sql="INSERT INTO pr_conceptotiponomina (CodConcepto, CodTipoNom) VALUES ('".$concepto."', '".$_POST[$cod]."')";
				$query=mysql_query($sql) or die ($sql.mysql_error());
			}
		}
	}
}
?>

<input type="hidden" name="tiponomina" id="tiponomina" />
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<table width="100%" class="tblBotones">
 <tr>
	<td align="right">
		<input name="btAgregar" type="submit" class="btLista" id="btAgregar" value="Agregar" onclick="window.open('lista_tiposnomina.php?concepto=<?=$concepto?>', 'wLista', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=500, width=600, left=50, top=50, resizable=yes')" <?=$disabled?> />
		<input name="btBorrar" type="button" class="btLista" id="btBorrar" value="Borrar" onclick="optConceptoTipoNomina(this.form, 'ELIMINAR');" <?=$disabled?> />
	</td>
 </tr>
</table>

<table width="100%" class="tblLista">
	<?php
	$sql="SELECT ctn.*, tn.Nomina FROM pr_conceptotiponomina ctn INNER JOIN tiponomina tn ON (ctn.CodTipoNom=tn.CodTipoNom) WHERE CodConcepto='".$concepto."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	while($field=mysql_fetch_array($query)) {
		?>
		<tr class="trListaBody" onclick="mClk(this, 'tiponomina');" id="<?=$field['CodTipoNom']?>">
			<td><?=$field['Nomina']?></td>
		</tr>
		<?
	}
	$rows=(int) $rows;
	?>
</table>

<? if ($accion=="EDITAR") { ?>
<script type="text/javascript" language="javascript">
	totalBotones2(<?=$rows?>);
</script>
<? } ?>

</form>
</body>
</html>