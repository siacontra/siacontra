<?php

session_start();

if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");

$_SESSION['_PROCESO'] = "";

$_SESSION['_NOMINA'] = "";

$_SESSION['_PERIODO'] = "";

//	------------------------------------

include("fphp_nomina.php");

include("funciones_globales_nomina.php");

$ahora=date("Y-m-d H:i:s");

list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('05', $concepto);

//	------------------------------------

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

		<td class="titulo">Asignaci&oacute;n de Conceptos Permanentes a Trabajadores </td>

		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>

	</tr>

</table><hr width="100%" color="#333333" />



<?

$MAXLIMIT=30;

if ($filtrar=="DEFAULT") {

	$flagproceso="N";

	$codproceso="[TODOS]";

	$nomproceso="[TODOS]";

	$forganismo=$_SESSION["FILTRO_ORGANISMO_ACTUAL"]; 

	$ftiponom=$_SESSION["NOMINA_ACTUAL"];

	$filtro="AND (me.CodOrganismo='".$_SESSION["FILTRO_ORGANISMO_ACTUAL"]."')";

} else {

	$sql="SELECT FlagFormula, Formula FROM pr_concepto WHERE CodConcepto='".$codconcepto."'";

	$query_formula=mysql_query($sql) or die ($sql.mysql_error());

	if (mysql_num_rows($query_formula)!=0) $field_formula=mysql_fetch_array($query_formula);

	$filtro.="AND me.CodOrganismo='".$forganismo."'";

	$filtro.="AND me.CodTipoNom='".$ftiponom."'";

	$filtro_pec.="AND (pec.PeriodoDesde>='".$fperiodo."') AND pec.CodConcepto='".$codconcepto."'";

}

if ($flagproceso=="N") $chkflag="checked"; else $dflag="disabled";

//	-------------------------

if ($accion=="GUARDAR-TODO") {

	$sql="SELECT 

				mp.CodPersona, 

				mp.NomCompleto, 

				me.CodEmpleado,

				pec.Secuencia,

				pec.Estado,

				pec.PeriodoDesde,

				pec.PeriodoHasta,

				pec.TipoAplicacion,

				pec.Monto,

				pec.Cantidad,

				pec.FlagTipoProceso

			FROM 

				mastpersonas mp 

				INNER JOIN mastempleado me ON (mp.CodPersona=me.CodPersona ) 
-- AND me.Estado = 'A'

				LEFT JOIN pr_empleadoconcepto pec ON (mp.CodPersona=pec.CodPersona $filtro_pec)

			WHERE 

				mp.CodPersona<>'' $filtro";

	$query_conceptos=mysql_query($sql) or die ($sql.mysql_error());

	while($field_conceptos=mysql_fetch_array($query_conceptos)) {

		$id=$field_conceptos['CodConcepto'].":".$field_conceptos['CodPersona'];		

		$d="d:$id"; $h="h:$id"; $m="m:$id"; $c="c:$id"; $s="s:$id"; $e="e:$id";

		$monto=str_replace(",", ".", $_POST[$m]);

		$cantidad=str_replace(",", ".", $_POST[$c]);

		if ($monto > 0) {

			//	Elimino los conceptos que han sido asignados en este periodo de tiempo...

			$sql="DELETE FROM pr_empleadoconcepto WHERE CodPersona='".$field_conceptos['CodPersona']."' AND CodConcepto='".$codconcepto."'AND PeriodoDesde>='".$_POST[$d]."'";

			$query_update=mysql_query($sql) or die ($sql.mysql_error());

			//	---------------------

			if ($flagproceso == "") $flagproceso = "S";

			$secuencia=getSecuencia2("Secuencia", "CodPersona", "CodConcepto", "pr_empleadoconcepto", $field_conceptos['CodPersona'], $codconcepto);

			//	Inserto el nuevo concepto asignado al trabajador...

			$sql="INSERT INTO pr_empleadoconcepto (CodPersona, CodConcepto, Secuencia, TipoAplicacion, PeriodoDesde, PeriodoHasta, Monto, Cantidad, FlagTipoProceso, Procesos, Estado, UltimoUsuario, UltimaFecha) VALUES ('".$field_conceptos['CodPersona']."', '".$codconcepto."', '".$secuencia."', 'P', '".$_POST[$d]."', '".$_POST[$h]."', '".$monto."', '".$cantidad."', '".$flagproceso."', '".$codproceso."', '".$_POST[$e]."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";

			$query_insert=mysql_query($sql) or die ($sql.mysql_error());

		}

		$monto=0;

		$cantidad=0;

	}

}

?>

<form id="frmentrada" name="frmentrada" action="conceptos_permanentes.php" method="POST" onsubmit="return validarConceptos(this.form);">

<div class="divBorder" style="width:1000px;">

<table width="1000" class="tblFiltro">

    <tr>

        <td align="right">Organismo:</td>

        <td>

        	<input type="checkbox" name="chkorganismo" id="chkorganismo" value="1" onclick="forzarCheck('chkorganismo');" checked="checked" />

			<select name="forganismo" id="forganismo" class="selectBig">

				<?=getOrganismos($forganismo, 3)?>

			</select>

        </td>

        <td align="right">N&oacute;mina:</td>

        <td>

        	<input type="checkbox" name="chktiponom" id="chktiponom" value="1" onclick="forzarCheck('chktiponom');" checked="checked" />

			<select name="ftiponom" id="ftiponom" class="selectBig" onchange="getFOptions_Periodo(this.id, 'fperiodo', 'chkperiodo', document.getElementById('forganismo').value);">

				<?=getTNomina($ftiponom, 0)?>

			</select>

        </td>

    </tr>

    <tr>

        <td align="right">Per&iacute;odo:</td>

        <td>

        	<input type="checkbox" name="chkperiodo" id="chkperiodo" value="1" onclick="forzarCheck('chkperiodo');" checked="checked" />

			<select name="fperiodo" id="fperiodo" style="width:100px;">

				<?=getPeriodos($fperiodo, $ftiponom, $forganismo, 0)?> 

			</select>

		</td>

        <td align="right">Concepto:</td>

        <td>

        	<input name="codconcepto" type="hidden" id="codconcepto" value="<?=$codconcepto?>" />

            <input name="nomconcepto" type="text" id="nomconcepto" size="56" value="<?=$nomconcepto?>" readonly />

            <input name="btConcepto" type="button" id="btConcepto" value="..." onclick="window.open('lista_conceptos_sel.php?limit=0&codtiponom='+document.getElementById('ftiponom').value, 'wLista', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=500, width=800, left=200, top=200, resizable=yes');" />

		</td>

    </tr>

    <tr><td colspan="4"><hr align="center" width="900" size="2px;" color="#323232;" /></td></tr>

    <tr>

        <td class="tagForm">Proceso:</td>

        <td colspan="3">

            <input type="checkbox" name="flagproceso" id="flagproceso" value="N" onclick="setTipoProcesoTodos(this.checked);" <?=$chkflag?> />

            <input name="codproceso" type="hidden" id="codproceso" value="<?=$codproceso?>" />

            <input name="nomproceso" type="text" id="nomproceso" size="50" value="<?=$nomproceso?>" readonly />

            <input name="btProceso" type="button" id="btProceso" value="..." onclick="window.open('lista_tipos_procesos.php?limit=0', 'wLista', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=500, width=800, left=200, top=200, resizable=yes');" <?=$dflag?> />

        </td>

    </tr>

</table>

</div>

<center><input type="submit" name="btBuscar" value="Mostrar Empleados"></center>

<br />



<table width="1000" class="tblBotones">

    <tr>

        <td align="right">

        	<input name="btGuardar" type="button" id="btGuardar" value="Guardar Todo" onclick="guardarConceptosPermanentes(this.form);" />

        </td>

    </tr>

</table>



<table width="1000" class="tblLista">

    <tr class="trListaHead">

        <th scope="col" width="50">C&oacute;digo</th>

        <th scope="col" colspan="2">Nombre</th>

        <th scope="col" width="75">Desde</th>

        <th scope="col" width="75">Hasta</th>

        <th scope="col" width="125">Monto</th>

        <th scope="col" width="50">Cantidad</th>

        <th scope="col">Procesos</th>

        <th scope="col" width="75">Estado</th>

    </tr>

    

    <?

    if ($forganismo!="" && $ftiponom!="" && $fperiodo!="" && $codconcepto!="") {

		//	Variables usadas en la formula....

		$_PARAMETROS = PARAMETROS();

		//	------------------------

		$sql="SELECT 

				mp.CodPersona, 

				mp.NomCompleto, 

				me.CodEmpleado,

				pec.Secuencia,

				pec.Estado,

				pec.PeriodoDesde,

				pec.PeriodoHasta,

				pec.TipoAplicacion,

				pec.Monto,

				pec.Cantidad,

				pec.FlagTipoProceso,

				pec.Procesos

			FROM 

				mastpersonas mp 

				INNER JOIN mastempleado me ON (mp.CodPersona=me.CodPersona)
-- AND me.Estado = 'A') 

				LEFT JOIN pr_empleadoconcepto pec ON (mp.CodPersona=pec.CodPersona $filtro_pec)

			WHERE 

				mp.CodPersona<>'' $filtro";

		$query_conceptos=mysql_query($sql) or die ($sql.mysql_error());

		while($field_conceptos=mysql_fetch_array($query_conceptos)) {

			$id=$field_conceptos['CodConcepto'].":".$field_conceptos['CodPersona'];

			if ($field_conceptos['Estado']=="A") $status="Activo";

			elseif ($field_conceptos['Estado']=="I") $status="Inactivo";

			else $status="";

			//	-------------------

			$r="";

			$monto = 0;

			$cantidad = 0;

			//	-------------------

			$monto=$field_conceptos['Monto'];

			$cantidad=$field_conceptos['Cantidad'];

			//	-------------------

			if ($field_formula['FlagFormula']=="S") {

				$r = "readonly";

				$_MONTO = 0;

				$_CANTIDAD = 0;

				

				$_ARGS['TRABAJADOR'] = $field_conceptos['CodPersona'];

				$_ARGS['NOMINA'] = $ftiponom;

				$_ARGS['PERIODO'] = $fperiodo;

				$_ARGS['PROCESO'] = "[TODOS]";

				$_ARGS['ORGANISMO'] = $forganismo;

				$_ARGS['DESDE'] = $fperiodo."-01";

				$_ARGS['HASTA'] = $fperiodo."-30";

				$_ARGS['DIAS_PROCESO'] = 30;

				$_ARGS['CONCEPTO'] = $codconcepto;

				$_ARGS['FORMULA'] = OBTENER_FORMULA($_ARGS);

				$_ARGS['FECHA_INGRESO'] = FECHA_INGRESO($_ARGS);

				$_ARGS['ASIGNACIONES'] = TOTAL_ASIGNACIONES($_ARGS);

				$_ARGS['DEDUCCIONES'] = 0;

				$_ARGS['PATRONALES'] = 0;

				$_SUELDO_BASICO = SUELDO_BASICO($_ARGS);

				$_SUELDO_NORMAL = $_ARGS['ASIGNACIONES'];

				$_SUELDO_BASICO_DIARIO = $_SUELDO_DIARIO / 30; $_SUELDO_BASICO_DIARIO = REDONDEO($_SUELDO_BASICO_DIARIO, 2);

				$_SUELDO_NORMAL_DIARIO = $_SUELDO_NORMAL / 30; $_SUELDO_NORMAL_DIARIO = REDONDEO($_SUELDO_NORMAL_DIARIO, 2);

				

				if ($_ARGS['FECHA_INGRESO'] <= $_ARGS['HASTA']) {

					//	Ejecuto la formula del concepto...

					eval($_ARGS['FORMULA']);

					$_ARGS['MONTO'] = REDONDEO($_MONTO, 2);

					$_ARGS['CANTIDAD'] = REDONDEO($_CANTIDAD, 2);

				}

				

				$monto = REDONDEO($_MONTO, 2);

				$cantidad = REDONDEO($_CANTIDAD, 2);

			}

			if ($field_conceptos['PeriodoHasta']=="") $phasta=$fperiodo; else $phasta=$field_conceptos['PeriodoHasta'];

			//	-------------------

			$suma+=$monto;

			?>

         	<input type="hidden" name="<?="s:".$id?>" id="<?="s:".$id?>" value="<?=$field_conceptos['Secuencia']?>" />

			<tr class="trListaBody">

				<td align="center"><?=$field_conceptos['CodPersona']?></td>

				<td width="300"><?=($field_conceptos['NomCompleto'])?></td>

				<td align="center" width="20"><?=$field_conceptos['TipoAplicacion']?></td>

				<td align="center">

					<select name="<?="d:".$id?>" id="<?="d:".$id?>" style="width:70px; text-align:center;">

                  <?=getPeriodos($fperiodo, $ftiponom, $forganismo, 3);?>

               </select>

				</td>

				<td align="center">&nbsp;</td>

				<td align="center"><input type="text" name="<?="m:".$id?>" id="<?="m:".$id?>" value="<?=number_format($monto, 2, '.', '')?>" <?=$r?> size="20" maxlength="15" dir="rtl" /></td>

				<td align="center"><input type="text" name="<?="c:".$id?>" id="<?="c:".$id?>" value="<?=number_format($cantidad, 2, '.', '')?>" <?=$r?> size="10" maxlength="5" dir="rtl" /></td>

				<td align="center"><?=$codproceso?></td>

				<td align="center">

					<select name="<?="e:".$id?>" id="<?="e:".$id?>" style="width:70px; text-align:center;">

                        <?=getStatus($field_conceptos['Estado'], 0)?>

                    </select>

                </td>

			</tr>

			<?

		}

	}

	?>

</table>

</form>

</body>

</html>

