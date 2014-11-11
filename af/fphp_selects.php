<?php
include("fphp.php");
//--------------
//	SELECTS DEPENDIENTES (2 SELECTS)
if ($_POST['accion']=="getOptions_2") {
	if ($_POST['tabla']=="estado") {
		echo "
		<select name='estado' id='estado' class='selectMed'>
			<option value=''>";
				getEstados("", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="municipio") {
		echo "
		<select name='municipio' id='municipio' class='selectMed'>
			<option value=''>";
				getMunicipios("", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="ciudad") {
		echo "
		<select name='ciudad' id='ciudad' class='selectMed'>
			<option value=''>";
				getCiudades("", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="ciudad1") {
		echo "
		<select name='ciudad1' id='ciudad1' class='selectMed' onchange='setLNAC(this.form);'>
			<option value=''>";
				getCiudades("", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="ciudad2") {
		echo "
		<select name='ciudad2' id='ciudad2' class='selectMed'>
			<option value=''>";
				getCiudades("", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="dependencia") {
		echo "
		<select name='dependencia' id='dependencia' class='selectBig'>
			<option value=''>";
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
			<option value=''>";
				getSeries("", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="cargo") {
		echo "
		<select name='cargo' id='cargo' class='selectMed'>
			<option value=''>";
				getCargos("", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="cargo_empleado") {
		echo "
		<select name='cargo_empleado' id='cargo_empleado' class='selectMed' onchange='mostrarCategoriaSueldo(this.value);'>
			<option value=''>";
				getCargos("", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="nivelcargo") {
		echo "
		<select name='nivelcargo' id='nivelcargo' class='selectMed' onchange='setCargo(this.form);'>
			<option value=''>";
				getNiveles("", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="nivel") {
		echo "
		<select name='nivel' id='nivel' class='selectMed'>
			<option value=''>";
				getNInstruccion("", $_POST['opcion'], 0);
		echo "</select>";
	}
	elseif ($_POST['tabla']=="profesiones") {
		echo "
		<select name='profesion' id='profesion' class='selectMed'>
			<option value=''>";
				getProfesiones("", $_POST['grado'], $_POST['area'], 0);
		echo "</select>";
	}
	elseif ($_POST['tabla']=="fprofesiones") {
		echo "
		<select name='fprofesion' id='fprofesion' class='selectMed'>
			<option value=''>";
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
			<option value=''>";
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
}

//	SELECTS DEPENDIENTES (3 SELECTS)
elseif ($_POST['accion']=="getOptions_3") {
	if ($_POST['tabla']=="estado") {
		echo "
		<select name='estado' id='estado' class='selectMed' onchange='getOptions_2(this.id, \"municipio\")'>
			<option value=''>";
				getEstados("", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="municipio") {
		echo "
		<select name='municipio' id='municipio' class='selectMed' onchange='getOptions_2(this.id, \"ciudad\")'>
			<option value=''>";
				getMunicipios("", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="municipio1") {
		echo "
		<select name='municipio1' id='municipio1' class='selectMed' onchange='getOptions_2(this.id, \"ciudad1\"); setLNAC(this.form);'>
			<option value=''>";
				getMunicipios("", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="municipio2") {
		echo "
		<select name='municipio2' id='municipio2' class='selectMed' onchange='getOptions_2(this.id, \"ciudad2\")'>
			<option value=''>";
				getMunicipios("", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="serie") {
		echo "
		<select name='serie' id='serie' class='selectMed' onchange='getOptions_2(this.id, \"cargo\")'>
			<option value=''>";
				getSeries("", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="serie_empleado") {
		echo "
		<select name='serie_empleado' id='serie_empleado' class='selectMed' onchange='getOptions_2(this.id, \"cargo_empleado\")'>
			<option value=''>";
				getSeries("", $_POST['opcion'], 0);
		echo "</select>*";
	}
}

//	SELECTS DEPENDIENTES (4 SELECTS)
elseif ($_POST['accion']=="getOptions_4") {
	if ($_POST['tabla']=="estado") {
		echo "
		<select name='estado' id='estado' class='selectMed' onchange='getOptions_3(this.id, \"municipio\", \"ciudad\")'>
			<option value=''>";
				getEstados("", $_POST['opcion'], 0);
		echo "</select>*";
	}
	if ($_POST['tabla']=="estado1") {
		echo "
		<select name='estado1' id='estado1' class='selectMed' onchange='getOptions_3(this.id, \"municipio1\", \"ciudad1\"); setLNAC(this.form);'>
			<option value=''>";
				getEstados("", $_POST['opcion'], 0);
		echo "</select>*";
	}
	if ($_POST['tabla']=="estado2") {
		echo "
		<select name='estado2' id='estado2' class='selectMed' onchange='getOptions_3(this.id, \"municipio2\", \"ciudad2\")'>
			<option value=''>";
				getEstados("", $_POST['opcion'], 0);
		echo "</select>*";
	}
}
//--------------
?>