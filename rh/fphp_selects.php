<?php
include("fphp.php");
//--------------
//	SELECTS DEPENDIENTES (2 SELECTS)
if ($_POST['accion']=="getOptions_2") {
	if ($_POST['tabla']=="estado") {
		echo "
		<select name='estado' id='estado' class='selectMed'>
			<option value=''>&nbsp;</option>";
				loadSelectDependiente("mastestados", "CodEstado", "Estado", "CodPais", "", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="municipio") {
		echo "
		<select name='municipio' id='municipio' class='selectMed'>
			<option value=''>&nbsp;</option>";
				loadSelectDependiente("mastmunicipios", "CodMunicipio", "Municipio", "CodEstado", "", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="ciudad") {
		echo "
		<select name='ciudad' id='ciudad' class='selectMed'>
			<option value=''>&nbsp;</option>";
				loadSelectDependiente("mastciudades", "CodCiudad", "Ciudad", "CodMunicipio", "", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="ciudad1") {
		echo "
		<select name='ciudad1' id='ciudad1' class='selectMed' onchange='setLNAC(this.form);'>
			<option value=''>&nbsp;</option>";
				loadSelectDependiente("mastciudades", "CodCiudad", "Ciudad", "CodMunicipio", "", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="ciudad2") {
		echo "
		<select name='ciudad2' id='ciudad2' class='selectMed'>
			<option value=''>&nbsp;</option>";
				loadSelectDependiente("mastciudades", "CodCiudad", "Ciudad", "CodMunicipio", "", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="dependencia") {
		echo "
		<select name='dependencia' id='dependencia' class='selectBig'>
			<option value=''>&nbsp;</option>";
				getDependencias("", $_POST['opcion'], 0);
		echo "</select>";
	}
	elseif ($_POST['tabla']=="fdependencia") {
		echo "
		<input type='checkbox' name='chkdependencia' id='chkdependencia' value='1' onclick='enabledDependencia(this.form);' />
		<select name='fdependencia' id='fdependencia' class='selectBig' disabled>
				<option value=''></option>";
				getDependencias("", $_POST['opcion'], 3);
		echo "</select>";
	}
	elseif ($_POST['tabla']=="serie") {
		echo "
		<select name='serie' id='serie' class='selectMed' onchange='setCargo(this.form);'>
			<option value=''>&nbsp;</option>";
				getSeries("", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="cargo") {
		echo "
		<select name='cargo' id='cargo' class='selectMed'>
			<option value=''>&nbsp;</option>";
				getCargos("", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="cargo_empleado") {
		echo "
		<select name='cargo_empleado' id='cargo_empleado' class='selectMed' onchange='mostrarCategoriaSueldo(this.value);'>
			<option value=''>&nbsp;</option>";
				getCargos("", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="nivelcargo") {
		echo "
		<select name='nivelcargo' id='nivelcargo' class='selectMed' onchange='setCargo(this.form);'>
			<option value=''>&nbsp;</option>";
				getNiveles("", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="nivel") {
		echo "
		<select name='nivel' id='nivel' class='selectMed'>
			<option value=''>&nbsp;</option>";
				getNInstruccion("", $_POST['opcion'], 0);
		echo "</select>";
	}
	elseif ($_POST['tabla']=="profesiones") {
		echo "
		<select name='profesion' id='profesion' class='selectMed'>
			<option value=''>&nbsp;</option>";
				getProfesiones("", $_POST['grado'], $_POST['area'], 0);
		echo "</select>";
	}
	elseif ($_POST['tabla']=="fprofesiones") {
		echo "
		<select name='fprofesion' id='fprofesion' class='selectMed'>
			<option value=''>&nbsp;</option>";
				getProfesiones("", $_POST['grado'], $_POST['area'], 0);
		echo "</select>";
	}
	elseif ($_POST['tabla']=="fpevaluacion") {
		echo "
		<input type='checkbox' name='chkevaluacion' id='chkevaluacion' value='S' checked onclick='forzarCheck(this.id);' />
		<select name='fpevaluacion' id='fpevaluacion' class='selectBig'>";
				getPeriodosEvaluacion("", $_POST['opcion'], 0);
		echo "</select>";
	}
	elseif ($_POST['tabla']=="fperiodo") {
		if ($ventana == "PRENOMINA") {
			echo "
			<input type='checkbox' name='chkperiodo' id='chkperiodo' value='1' onclick='forzarCheck(\"chkperiodo\");' checked />
			<select name='fperiodo' id='fperiodo' style='width:100px;' onchange='getFOptions_ProcesoPreNomina(this.id, \"ftproceso\", \"chktproceso\", document.getElementById(\"ftiponom\").value, document.getElementById(\"forganismo\").value);'>
					<option value=''></option>";
					getPeriodos("", $_POST['opcion'], $codorganismo, 5);
			echo "</select>";
		} else {
			if ($opt != "6") $opt = "0";
			echo "
			<input type='checkbox' name='chkperiodo' id='chkperiodo' value='1' onclick='forzarCheck(\"chkperiodo\");' checked />
			<select name='fperiodo' id='fperiodo' style='width:100px;' onchange='getFOptions_Proceso(this.id, \"ftproceso\", \"chktproceso\", document.getElementById(\"ftiponom\").value, document.getElementById(\"forganismo\").value, \"$opt\");'>
					<option value=''></option>";
					getPeriodos("", $_POST['opcion'], $codorganismo, $opt);
			echo "</select>";
		}
	}
	elseif ($_POST['tabla']=="periodo") {
		echo "
		<select name='periodo' id='periodo' style='width:100px;'>
				<option value=''></option>";
				getPeriodos("", $_POST['opcion'], "", 1);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="proceso") {
		echo "
		<select name='proceso' id='proceso' style='width:225px;'>
				<option value=''></option>";
				getTipoProcesoNomina("", "", $_POST['opcion'], "", 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="ftproceso") {
		if ($ventana == "PRENOMINA") {
			echo "
			<input type='checkbox' name='chktproceso' id='chktproceso' value='1' onclick='forzarCheck(\"chktproceso\");' checked />
			<select name='ftproceso' id='ftproceso' class='selectBig'>";
					getTipoProcesoNomina("", $_POST['opcion'], $nomina, $codorganismo, 5);
			echo "</select>";
		} else {
			if ($opt != "6") $opt = "1";
			echo "
			<input type='checkbox' name='chktproceso' id='chktproceso' value='1' onclick='forzarCheck(\"chktproceso\");' checked />
			<select name='ftproceso' id='ftproceso' class='selectBig'>";
					getTipoProcesoNomina("", $_POST['opcion'], $nomina, $codorganismo, $opt);
			echo "</select>";
		}
	}
	elseif ($_POST['tabla']=="grados_cargo") {
		echo "
		<select name='gcargo' id='gcargo' onchange='getSueldoCargo(this.form, this.value)'>
			<option value=''>&nbsp;</option>";
			getGCargo($_POST['ttra'], $_POST['gcargo'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="falmacen") {
		echo "
		<input type='checkbox' name='chkalmacen' id='chkalmacen' value='1' onclick='forzarCheck(this.id);' checked />
		<select name='falmacen' id='falmacen' style='width:200px;'>";
			loadSelectDependiente("lg_almacenmast", "CodAlmacen", "Descripcion", "CodOrganismo", "", $_POST['opcion'], 0);
		echo "</select>";
	}
	elseif ($_POST['tabla']=="tservicio") {
		echo "
		<select id='tservicio' style='width:150px;' onchange='tservicio_obligacion(this.value)'>
			<option value=''>:::. Seleccione .:::</option>";
			loadTipoServicio($_POST['opcion']);
		echo "</select>";
	}
	elseif ($_POST['tabla']=="tservicio_obligacion") {
		connect();
		//	obtengo los datos del proveedor
		$sql = "SELECT
					p.CodPersona,
					p.NomCompleto,
					p.DocFiscal,
					p.Busqueda,
					pr.DiasPago,
					pr.CodTipoDocumento,
					pr.CodTipoPago,
					pr.CodTipoServicio,
					td.CodRegimenFiscal
				FROM
					mastpersonas p
					INNER JOIN mastproveedores pr ON (p.CodPersona = pr.CodProveedor)
					INNER JOIN ap_tipodocumento td ON (pr.CodTipoDocumento = td.CodTipoDocumento)
				WHERE pr.CodProveedor = '".$codpersona."'";
		$query_proveedor = mysql_query($sql) or die($sql.mysql_error());
		if (mysql_num_rows($query_proveedor) != 0) $field_proveedor = mysql_fetch_array($query_proveedor);
		$ctadef = getCuentaBancariaDefault($codorganismo, $field_proveedor['CodTipoPago']);
		
		//	imprimo el listado de retenciones y otros impuestos por defecto del proveedor
		$sql = "SELECT 
					tsi.CodImpuesto,
					i.Signo,
					i.FlagImponible,
					i.FactorPorcentaje
				FROM 
					masttiposervicioimpuesto tsi
					INNER JOIN mastimpuestos i ON (tsi.CodImpuesto = i.CodImpuesto)
				WHERE 
					tsi.CodTipoServicio = '".$field_proveedor['CodTipoServicio']."' AND
					i.CodRegimenfiscal = 'R'";
		$query_impuestos = mysql_query($sql) or die($sql.mysql_error());
		
		echo "$field_proveedor[CodPersona]||$field_proveedor[NomCompleto]||$field_proveedor[DocFiscal]||$field_proveedor[Busqueda]||$field_proveedor[DiasPago]||$field_proveedor[CodTipoDocumento]||$field_proveedor[CodTipoPago]||$ctadef||";
		
		echo "
		<select id='tservicio' style='width:150px;' onchange='tservicio_obligacion(this.value)'>
			<option value='' selected='selected'>:::. Seleccione .:::</option>";
			loadTipoServicioObligacion($field_proveedor['CodTipoServicio'], $field_proveedor['CodRegimenFiscal']);
		echo "</select>*||";
		
		$canimpuesto = 0;
		$total = 0;
		while($field_impuestos = mysql_fetch_array($query_impuestos)) {	$canimpuesto++;
			?>
            <tr class="trListaBody" id="imp_<?=$canimpuesto?>" onclick="mClk(this, 'selimpuesto');">
                <td align="center">
                    <input type="hidden" name="nroimpuesto" value="<?=$canimpuesto?>" />
                    <select name="codimpuesto" style="width:95%" onchange="getFactorPorcentaje(this.value, <?=$canimpuesto?>);">
                        <option value=""></option>
                        <?=loadSelectImpuesto($field_impuestos['CodImpuesto'], "R");?>
                    </select>
                    <input type="hidden" name="signo" id="signo_<?=$canimpuesto?>" value="<?=$field_impuestos['Signo']?>" />
                    <input type="hidden" name="imponible" id="imponible_<?=$canimpuesto?>" value="<?=$field_impuestos['FlagImponible']?>" />
                </td>
                <td align="center"><input type="text" name="afecto" id="afecto_<?=$canimpuesto?>" value="0,00" onblur="numeroBlur(this); calcularTotalImpuesto('<?=$canimpuesto?>');" onfocus="numeroFocus(this);" style="width:95%; text-align:right;" /></td>
                <td align="right"><input type="text" name="factor" id="factor_<?=$canimpuesto?>" value="<?=number_format($field_impuestos['FactorPorcentaje'], 2, ',', '.')?>" style="width:95%; text-align:right;" disabled="disabled" /></td>
                <td align="right"><span id="monto_<?=$canimpuesto?>">0,00</span></td>
			</tr>
            <?
		}
		
		echo "||$canimpuesto||";
	}
	elseif ($_POST['tabla']=="fase") {
		echo "
		<select id='fase' style='width:250px;'>
			<option value=''>:::. Seleccione .:::</option>";
			loadSelectDependiente("pf_fases", "CodFase", "Descripcion", "CodProceso", "", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="dependenciaext") {
		echo "
		<select id='dependenciaext' style='width:300px;'>
			<option value=''>:::. Seleccione .:::</option>";
			loadSelectDependiente("pf_dependenciasexternas", "CodDependencia", "Dependencia", "Codorganismo", "", $_POST['opcion'], 0);
		echo "</select>";
	}
	elseif ($_POST['tabla']=="fctabancaria") {
		echo "
		<input type='checkbox'' style='visibility:hidden;' />
		<select name='fctabancaria' id='fctabancaria' style='width:206px;'>
				<option value=''>&nbsp;</option>";
				loadSelectDependiente("ap_ctabancaria", "NroCuenta", "NroCuenta", "CodBanco", "", $_POST['opcion'], 0);
		echo "</select>";
	}
}

//	SELECTS DEPENDIENTES (3 SELECTS)
elseif ($_POST['accion']=="getOptions_3") {
	if ($_POST['tabla']=="estado") {
		echo "
		<select name='estado' id='estado' class='selectMed' onchange='getOptions_2(this.id, \"municipio\")'>
			<option value=''>&nbsp;</option>";
				loadSelectDependiente("mastestados", "CodEstado", "Estado", "CodPais", "", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="municipio") {
		echo "
		<select name='municipio' id='municipio' class='selectMed' onchange='getOptions_2(this.id, \"ciudad\")'>
			<option value=''>&nbsp;</option>";
				loadSelectDependiente("mastmunicipios", "CodMunicipio", "Municipio", "CodEstado", "", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="municipio1") {
		echo "
		<select name='municipio1' id='municipio1' class='selectMed' onchange='getOptions_2(this.id, \"ciudad1\"); setLNAC(this.form);'>
			<option value=''>&nbsp;</option>";
				loadSelectDependiente("mastmunicipios", "CodMunicipio", "Municipio", "CodEstado", "", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="municipio2") {
		echo "
		<select name='municipio2' id='municipio2' class='selectMed' onchange='getOptions_2(this.id, \"ciudad2\")'>
			<option value=''>&nbsp;</option>";
				loadSelectDependiente("mastmunicipios", "CodMunicipio", "Municipio", "CodEstado", "", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="serie") {
		echo "
		<select name='serie' id='serie' class='selectMed' onchange='getOptions_2(this.id, \"cargo\")'>
			<option value=''>&nbsp;</option>";
				getSeries("", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="serie_empleado") {
		echo "
		<select name='serie_empleado' id='serie_empleado' class='selectMed' onchange='getOptions_2(this.id, \"cargo_empleado\")'>
			<option value=''>&nbsp;</option>";
				getSeries("", $_POST['opcion'], 0);
		echo "</select>*";
	}
}

//	SELECTS DEPENDIENTES (4 SELECTS)
elseif ($_POST['accion']=="getOptions_4") {
	if ($_POST['tabla']=="estado") {
		echo "
		<select name='estado' id='estado' class='selectMed' onchange='getOptions_3(this.id, \"municipio\", \"ciudad\")'>
			<option value=''>&nbsp;</option>";
				loadSelectDependiente("mastestados", "CodEstado", "Estado", "CodPais", "", $_POST['opcion'], 0);
		echo "</select>*";
	}
	if ($_POST['tabla']=="estado1") {
		echo "
		<select name='estado1' id='estado1' class='selectMed' onchange='getOptions_3(this.id, \"municipio1\", \"ciudad1\"); setLNAC(this.form);'>
			<option value=''>&nbsp;</option>";
				loadSelectDependiente("mastestados", "CodEstado", "Estado", "CodPais", "", $_POST['opcion'], 0);
		echo "</select>*";
	}
	if ($_POST['tabla']=="estado2") {
		echo "
		<select name='estado2' id='estado2' class='selectMed' onchange='getOptions_3(this.id, \"municipio2\", \"ciudad2\")'>
			<option value=''>&nbsp;</option>";
				loadSelectDependiente("mastestados", "CodEstado", "Estado", "CodPais", "", $_POST['opcion'], 0);
		echo "</select>*";
	}
}
//--------------
?>