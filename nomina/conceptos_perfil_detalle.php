<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");

include("fphp_nomina.php");
connect();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_nomina.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Mantenimiento del Perfil</td>
	</tr>
</table><hr width="100%" color="#333333" />

<?
$sql = "SELECT
			cp.*
		FROM
			pr_conceptoperfil cp
		WHERE cp.CodPerfilConcepto = '".$registro."'";
$query = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
?>
<div class='divBorder' style='width:894px;'>
<table width='894' class='tblFiltro'>
    <tr>
        <td align='right'>Perfil:</td>
        <td><input type='text' id="codperfil" size='10' value="<?=$field['CodPerfilConcepto']?>" disabled="disabled" /></td>
    </tr>
    <tr>
        <td align='right'>Nombre Completo:</td>
        <td><input type='text' size='75' value="<?=($field['Descripcion'])?>" disabled="disabled" /></td>
    </tr>
</table>
</div>
<center><input type="button" value="Guardar Todo" onclick="guardarDetallesConceptosPerfil();" /></center><br />

<?
$sql = "SELECT CodTipoProceso FROM pr_tipoproceso ORDER BY CodTipoProceso LIMIT 0, 1";
$query_proceso = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query_proceso) != 0) $field_proceso = mysql_fetch_array($query_proceso);
?>
            
<form name="frmdetalles" id="frmdetalles" action="conceptos_perfil.php" method="POST">
<input type="hidden" id="selconcepto" />
<input type="hidden" id="selproceso" value="<?=$field_proceso['CodTipoProceso']?>" />

<table width="890" class="tblBotones">
    <tr>
        <td><div id="rows"></div></td>
        <td>
            Proceso: &nbsp; &nbsp; &nbsp;
            <select name="proceso" id="proceso" style="width:250px;" onchange="mostrarListaConceptoProcesoPerfil(this.value);">
                <?=loadSelect("pr_tipoproceso", "CodTipoProceso", "Descripcion", $field_proceso['CodTipoProceso'], 0)?>
            </select>                        
        </td>
        <td align="right">
        	<input type="button" value="Sel. Partida" onclick="listaPartidaConceptoPerfil(this.form);" /> | 
        	<input type="button" value="Sel. Debe" onclick="listaCuentasContables(this.form, 'debe');" />
        	<input type="button" value="Sel. Haber" onclick="listaCuentasContables(this.form, 'haber');" />
		</td>
    </tr>
</table>

<center>

<?
$sql = "SELECT * FROM pr_tipoproceso ORDER BY CodTipoProceso";
$query_procesos = mysql_query($sql) or die ($sql.mysql_error());
while ($field_procesos = mysql_fetch_array($query_procesos)) {
	if ($field_proceso['CodTipoProceso'] == $field_procesos['CodTipoProceso']) $style=""; else $style = "style='display:none;'";

	$filtro = "INNER JOIN pr_conceptoproceso cp ON (c.CodConcepto = cp.CodConcepto AND cp.CodTipoProceso = '".$field_procesos['CodTipoProceso']."')";
	?>
    <table align="center" id="listaDetalles_<?=$field_procesos['CodTipoProceso']?>" <?=$style?>><tr><td align="center"><div style="overflow:scroll; width:890px; height:400px;">
    <table width="100%" class="tblLista">
        <tr class="trListaHead">
            <th scope="col" colspan="2">
            	<input type="hidden" name="txtproceso" value="<?=$field_procesos['CodTipoProceso']?>" style="display:none;" />
				Concepto
			</th>
            <th scope="col" width="125">Partida Presupuestal</th>
            <th scope="col" width="100">Debe</th>
            <th scope="col" width="25">C.C</th>
            <th scope="col" width="100">Haber</th>
            <th scope="col" width="25">C.C</th>
        </tr>
        <?php
        $sql = "(SELECT c.*, '1' AS Orden, 'Ingresos' AS Tipo, cp.CodTipoProceso, cpd.cod_partida, cpd.CuentaDebe, cpd.CuentaHaber, 'I' AS TipoConcepto
		 		  FROM 
				  	pr_concepto c $filtro
					INNER JOIN pr_conceptotiponomina ctn ON (c.CodConcepto = ctn.CodConcepto AND 
															 ctn.CodTipoNom IN (SELECT CodTipoNom
															 					FROM tiponomina
																				WHERE CodPerfilConcepto = '".$registro."'))
					LEFT JOIN pr_conceptoperfildetalle cpd ON (cp.CodConcepto = cpd.CodConcepto AND 
															   cp.CodTipoProceso = cpd.CodTipoProceso AND 
															   cpd.CodPerfilConcepto = '".$registro."')
				  WHERE c.Tipo = 'I')
				  
				  UNION
                 
				 (SELECT c.*, '2' AS Orden, 'Descuentos' AS Tipo, cp.CodTipoProceso, cpd.cod_partida, cpd.CuentaDebe, cpd.CuentaHaber, 'D' AS TipoConcepto
				  FROM 
				  	pr_concepto c $filtro
					INNER JOIN pr_conceptotiponomina ctn ON (c.CodConcepto = ctn.CodConcepto AND 
															 ctn.CodTipoNom IN (SELECT CodTipoNom
															 					FROM tiponomina
																				WHERE CodPerfilConcepto = '".$registro."'))
					LEFT JOIN pr_conceptoperfildetalle cpd ON (cp.CodConcepto = cpd.CodConcepto AND 
															   cp.CodTipoProceso = cpd.CodTipoProceso AND 
															   cpd.CodPerfilConcepto = '".$registro."')
				  WHERE c.Tipo = 'D')
				  
				  UNION
                 
				 (SELECT c.*, '3' AS Orden, 'Aportes' AS Tipo, cp.CodTipoProceso, cpd.cod_partida, cpd.CuentaDebe, cpd.CuentaHaber, 'A' AS TipoConcepto
				  FROM 
				  	pr_concepto c $filtro
					INNER JOIN pr_conceptotiponomina ctn ON (c.CodConcepto = ctn.CodConcepto AND 
															 ctn.CodTipoNom IN (SELECT CodTipoNom
															 					FROM tiponomina
																				WHERE CodPerfilConcepto = '".$registro."'))
					LEFT JOIN pr_conceptoperfildetalle cpd ON (cp.CodConcepto = cpd.CodConcepto AND 
															   cp.CodTipoProceso = cpd.CodTipoProceso AND 
															   cpd.CodPerfilConcepto = '".$registro."')
				  WHERE c.Tipo = 'A')
				  
				  UNION
				  
                 (SELECT c.*, '4' AS Orden, 'Provisiones' AS Tipo, cp.CodTipoProceso, cpd.cod_partida, cpd.CuentaDebe, cpd.CuentaHaber, 'P' AS TipoConcepto
				  FROM 
				  	pr_concepto c $filtro
					INNER JOIN pr_conceptotiponomina ctn ON (c.CodConcepto = ctn.CodConcepto AND 
															 ctn.CodTipoNom IN (SELECT CodTipoNom
															 					FROM tiponomina
																				WHERE CodPerfilConcepto = '".$registro."'))
					LEFT JOIN pr_conceptoperfildetalle cpd ON (cp.CodConcepto = cpd.CodConcepto AND 
															   cp.CodTipoProceso = cpd.CodTipoProceso AND 
															   cpd.CodPerfilConcepto = '".$registro."')
				  WHERE c.Tipo = 'P')
				  
				  UNION
				  
                 (SELECT c.*, '5' AS Orden, 'Totales' AS Tipo, cp.CodTipoProceso, cpd.cod_partida, cpd.CuentaDebe, cpd.CuentaHaber, 'T' AS TipoConcepto
				  FROM 
				  	pr_concepto c $filtro
					INNER JOIN pr_conceptotiponomina ctn ON (c.CodConcepto = ctn.CodConcepto AND 
															 ctn.CodTipoNom IN (SELECT CodTipoNom
															 					FROM tiponomina
																				WHERE CodPerfilConcepto = '".$registro."'))
					LEFT JOIN pr_conceptoperfildetalle cpd ON (cp.CodConcepto = cpd.CodConcepto AND 
															   cp.CodTipoProceso = cpd.CodTipoProceso AND 
															   cpd.CodPerfilConcepto = '".$registro."')
				  WHERE c.Tipo = 'T')
                  
				  ORDER BY Orden";
        $query_concepto = mysql_query($sql) or die ($sql.mysql_error());	$grupo = "";
        while ($field_concepto = mysql_fetch_array($query_concepto)) { $i++;
            $id_concepto = $field_procesos['CodTipoProceso']."_".$field_concepto['CodConcepto'];
			
			if ($field_concepto['FlagRetencion'] == "S") $style = "style='color:red;'"; else $style = "";
			
			if ($field_concepto['CuentaDebe'] != "" || $field_concepto['CuentaHaber'] != "") {
				if ($field_concepto['CuentaDebe'] != "") $flagdebe = "checked"; else $flagdebe = "";
				if ($field_concepto['CuentaHaber'] != "") $flaghaber = "checked"; else $flaghaber = "";
			} else { $flagdebe = "checked"; $flaghaber = ""; }
			
			if ($grupo != $field_concepto['Tipo']) {
				$grupo = $field_concepto['Tipo'];
				?>
                <tr class="trListaBody2">
                	<td colspan="7"><?=$field_concepto['Tipo']?></td>
                </tr>
                <?php
			}
            ?>
            <tr class="trListaBody" onclick="mClk(this, 'selconcepto');" id="<?=$id_concepto?>">
                <td align="center" width="45">
                    <?=$field_concepto['CodConcepto']?>
                    <input type="hidden" name="txtconcepto" value="<?=$id_concepto?>" style="display:none;" />
                </td>
                <td><span <?=$style?>><?=($field_concepto['Descripcion'])?></span></td>
                <td><input type="text" name="partida" id="partida_<?=$id_concepto?>" value="<?=$field_concepto['cod_partida']?>" style="width:99%; text-align:center;" /></td>
                <td><input type="text" name="debe" id="debe_<?=$id_concepto?>" value="<?=$field_concepto['CuentaDebe']?>" style="width:99%; text-align:center;" /></td>
                <td><input type="checkbox" name="debecc" id="debecc_<?=$id_concepto?>" /></td>
                <td><input type="text" name="haber" id="haber_<?=$id_concepto?>" value="<?=$field_concepto['CuentaHaber']?>" style="width:99%; text-align:center;" /></td>
                <td><input type="checkbox" name="habercc" id="habercc_<?=$id_concepto?>" /></td>
            </tr>
            <?
		}
	?>
	</table>
	</div></td></tr></table>
	<?
}
?>

</center>
</form>

</body>
</html>
