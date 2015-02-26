<?php include "fphp.php";
      include "fphp02.php";
//--------------
//	SELECTS DEPENDIENTES (2 SELECTS)
if ($_POST['accion']=="getOptions_2") {
	if ($_POST['tabla']=="programa") {
		echo "
		<select name='programa' id='programa' class='selectBig'>
			<option value=''>";
				getPrograma("", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="subprograma") {
		echo "
		<select name='subprograma' id='subprograma' class='selectBig'>
			<option value=''>";
				getSubprograma("", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="proyecto") {
		echo "
		<select name='proyecto' id='proyecto' class='selectBig'>
			<option value=''>";
				getProyecto("", $_POST['opcion'], 0);
		echo "</select>";
	}
	elseif ($_POST['tabla']=="actividad") {
		echo "
		<select name='actividad' id='actividad' class='selectBig'>
			<option value=''>";
				getActividad("", $_POST['opcion'], 0);
		echo "</select>";
	}
}
//  SELECTS DEPENDIENTES (3 SELECTS)
elseif($_POST['accion']=="getOptions_3") {
	if($_POST['tabla']=="subprograma") {
	  echo "
	   <select name='subprograma' id='subprograma' class='selectBig' onchange='getOptions_2(this.id, \"proyecto\")'>
			<option value=''>";
				getSubprograma("", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="proyecto") {
		echo "
		<select name='proyecto' id='proyecto' class='selectBig' onchange='getOptions_2(this.id, \"actividad\")'>
			<option value=''>";
				getProyecto("", $_POST['opcion'], 0);
		echo "</select>";
	}
}
//  SELECTS DEPENDIENTES (4 SELECTS)
elseif ($_POST['accion']=="getOptions_4") {
	if ($_POST['tabla']=="programa") {
	echo"
		<select name='programa' id='programa' class='selectBig' onchange='getOptions_3(this.id, \"subprograma\", \"proyecto\")'>
			<option value=''>";
				getPrograma("", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="subprograma") {
		echo "
		<select name='subprograma' id='subprograma' class='selectBig' onchange='getOptions_3(this.id, \"proyecto\", \"actividad\")'>
			<option value=''>";
				getSubprograma("", $_POST['opcion'], 0);
		echo "</select>*";
}
}
//	SELECTS DEPENDIENTES (5 SELECTS)
elseif($_POST['accion']=="getOptions_5") {
	if($_POST['tabla']=="programa") {
	  echo"
	   <select name='programa' id='programa' class='selectBig' onchange='getOptions_4(this.id, \"subprograma\", \"proyecto\",\"actividad\")'>
			<option value=''>";
				getPrograma("", $_POST['opcion'], 0);
		echo "</select>*";
	}
}
//////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////// *************** NUEVO SELECT PARA EDITAR **************** /////////////////////  
//////////////////////////////////////////////////////////////////////////////////////////////////////
//	SELECTS DEPENDIENTES (2 SELECTS)
if ($_POST['accion']=="getOptionsEd_2") {
	if ($_POST['tabla']=="programa") {
		echo "
		<select name='programa' id='programa' class='selectBig'>
			<option value=''>";
				getPrograma2("", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="subprograma") {
		echo "
		<select name='subprograma' id='subprograma' class='selectBig'>
			<option value=''>";
				getSubprograma2("", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="proyecto") {
		echo "
		<select name='proyecto' id='proyecto' class='selectBig'>
			<option value=''>";
				getProyecto2("", $_POST['opcion'], 0);
		echo "</select>";
	}
	elseif ($_POST['tabla']=="actividad") {
		echo "
		<select name='actividad' id='actividad' class='selectBig'>
			<option value=''>";
				getActividad2("", $_POST['opcion'], 0);
		echo "</select>";
	}
}
//  SELECTS DEPENDIENTES (3 SELECTS)
elseif($_POST['accion']=="getOptionsEd_3") {
	if($_POST['tabla']=="subprograma") {
	  echo "
	   <select name='subprograma' id='subprograma' class='selectBig' onchange='getOptionsEd_2(this.id, \"proyecto\")'>
			<option value=''>";
				getSubprograma2("", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="proyecto") {
		echo "
		<select name='proyecto' id='proyecto' class='selectBig' onchange='getOptionsEd_2(this.id, \"actividad\")'>
			<option value=''>";
				getProyecto2("", $_POST['opcion'], 0);
		echo "</select>";
	}
}
//  SELECTS DEPENDIENTES (4 SELECTS)
elseif ($_POST['accion']=="getOptionsEd_4") {
	if ($_POST['tabla']=="programa") {
	echo"
		<select name='programa' id='programa' class='selectBig' onchange='getOptionsEd_3(this.id, \"subprograma\", \"proyecto\")'>
			<option value=''>";
				getPrograma2("", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="subprograma") {
		echo "
		<select name='subprograma' id='subprograma' class='selectBig' onchange='getOptionsEd_3(this.id, \"proyecto\", \"actividad\")'>
			<option value=''>";
				getSubprograma2("", $_POST['opcion'], 0);
		echo "</select>*";
}
}
//	SELECTS DEPENDIENTES (5 SELECTS)
elseif($_POST['accion']=="getOptionsEd_5") {
	if($_POST['tabla']=="programa") {
	  echo"
	   <select name='programa' id='programa' class='selectBig' onchange='getOptionsEd_4(this.id, \"subprograma\", \"proyecto\",\"actividad\")'>
			<option value=''>";
				getPrograma2("", $_POST['opcion'], 0);
		echo "</select>*";
	}
}
//////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

//	SELECTS DEPENDIENTES (2 SELECTS)
/*if ($_POST['accion']=="getOptions_2") {
	if ($_POST['tabla']=="estado") {
		echo "
		<select name='estado' id='estado' class='selectBig'>
			<option value=''>";
				getEstados("", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="municipio") {
		echo "
		<select name='municipio' id='municipio' class='selectBig'>
			<option value=''>";
				getMunicipios("", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="ciudad") {
		echo "
		<select name='ciudad' id='ciudad' class='selectBig'>
			<option value=''>";
				getCiudades("", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="ciudad1") {
		echo "
		<select name='ciudad1' id='ciudad1' class='selectBig' onchange='setLNAC(this.form);'>
			<option value=''>";
				getCiudades("", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="ciudad2") {
		echo "
		<select name='ciudad2' id='ciudad2' class='selectBig'>
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
		<select name='serie' id='serie' class='selectBig' onchange='setCargo(this.form);'>
			<option value=''>";
				getSeries("", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="cargo") {
		echo "
		<select name='cargo' id='cargo' class='selectBig'>
			<option value=''>";
				getCargos("", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="cargo_empleado") {
		echo "
		<select name='cargo_empleado' id='cargo_empleado' class='selectBig' onchange='mostrarCategoriaSueldo(this.value);'>
			<option value=''>";
				getCargos("", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="nivelcargo") {
		echo "
		<select name='nivelcargo' id='nivelcargo' class='selectBig' onchange='setCargo(this.form);'>
			<option value=''>";
				getNiveles("", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="nivel") {
		echo "
		<select name='nivel' id='nivel' class='selectBig'>
			<option value=''>";
				getNInstruccion("", $_POST['opcion'], 0);
		echo "</select>";
	}
	elseif ($_POST['tabla']=="profesiones") {
		echo "
		<select name='profesion' id='profesion' class='selectBig'>
			<option value=''>";
				getProfesiones("", $_POST['grado'], $_POST['area'], 0);
		echo "</select>";
	}
	elseif ($_POST['tabla']=="fprofesiones") {
		echo "
		<select name='fprofesion' id='fprofesion' class='selectBig'>
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
		echo "
		<input type='checkbox' name='chkperiodo' id='chkperiodo' value='1' onclick='forzarCheck(\"chkperiodo\");' checked />
		<select name='fperiodo' id='fperiodo' style='width:100px;' onchange='getFOptions_Proceso(this.id, \"ftproceso\", \"chktproceso\", document.getElementById(\"ftiponom\").value, document.getElementById(\"forganismo\").value);'>
				<option value=''></option>";
				getPeriodos("", $_POST['opcion'], $codorganismo, 0);
		echo "</select>";
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
		echo "
		<input type='checkbox' name='chktproceso' id='chktproceso' value='1' onclick='forzarCheck(\"chktproceso\");' checked />
		<select name='ftproceso' id='ftproceso' class='selectBig'>";
				getTipoProcesoNomina("", $_POST['opcion'], $nomina, $codorganismo, 1);
		echo "</select>";
	}
}
//	SELECTS DEPENDIENTES (3 SELECTS)
elseif ($_POST['accion']=="getOptions_3") {
	if ($_POST['tabla']=="estado") {
		echo "
		<select name='estado' id='estado' class='selectBig' onchange='getOptions_2(this.id, \"municipio\")'>
			<option value=''>";
				getEstados("", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="municipio") {
		echo "
		<select name='municipio' id='municipio' class='selectBig' onchange='getOptions_2(this.id, \"ciudad\")'>
			<option value=''>";
				getMunicipios("", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="municipio1") {
		echo "
		<select name='municipio1' id='municipio1' class='selectBig' onchange='getOptions_2(this.id, \"ciudad1\"); setLNAC(this.form);'>
			<option value=''>";
				getMunicipios("", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="municipio2") {
		echo "
		<select name='municipio2' id='municipio2' class='selectBig' onchange='getOptions_2(this.id, \"ciudad2\")'>
			<option value=''>";
				getMunicipios("", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="serie") {
		echo "
		<select name='serie' id='serie' class='selectBig' onchange='getOptions_2(this.id, \"cargo\")'>
			<option value=''>";
				getSeries("", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="serie_empleado") {
		echo "
		<select name='serie_empleado' id='serie_empleado' class='selectBig' onchange='getOptions_2(this.id, \"cargo_empleado\")'>
			<option value=''>";
				getSeries("", $_POST['opcion'], 0);
		echo "</select>*";
	}
}
//	SELECTS DEPENDIENTES (4 SELECTS)
elseif ($_POST['accion']=="getOptions_4") {
	if ($_POST['tabla']=="estado") {
		echo "
		<select name='estado' id='estado' class='selectBig' onchange='getOptions_3(this.id, \"municipio\", \"ciudad\")'>
			<option value=''>";
				getEstados("", $_POST['opcion'], 0);
		echo "</select>*";
	}
	if ($_POST['tabla']=="estado1") {
		echo "
		<select name='estado1' id='estado1' class='selectBig' onchange='getOptions_3(this.id, \"municipio1\", \"ciudad1\"); setLNAC(this.form);'>
			<option value=''>";
				getEstados("", $_POST['opcion'], 0);
		echo "</select>*";
	}
	if ($_POST['tabla']=="estado2") {
		echo "
		<select name='estado2' id='estado2' class='selectBig' onchange='getOptions_3(this.id, \"municipio2\", \"ciudad2\")'>
			<option value=''>";
				getEstados("", $_POST['opcion'], 0);
		echo "</select>*";
	}
}*/
//--------------
?>