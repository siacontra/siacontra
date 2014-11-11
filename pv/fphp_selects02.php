<?php include "fphp02.php";
//	SELECTS DEPENDIENTES (2 SELECTS)
if ($_POST['accion']=="getOptions_2") {
	if ($_POST['tabla']=="programa") {
		echo "
		<select name='programa' id='programa' class='selectMed'>
			<option value=''>";
				getPrograma2("", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="subprograma") {
		echo "
		<select name='subprograma' id='subprograma' class='selectMed'>
			<option value=''>";
				getSubprograma2("", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="proyecto") {
		echo "
		<select name='proyecto' id='proyecto' class='selectMed'>
			<option value=''>";
				getProyecto2("", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="actividad") {
		echo "
		<select name='actividad' id='actividad' class='selectMed'>
			<option value=''>";
				getActividad2("", $_POST['opcion'], 0);
		echo "</select>*";
	}
}
//  SELECTS DEPENDIENTES (3 SELECTS)
elseif($_POST['accion']=="getOptions_3") {
	if($_POST['tabla']=="subprograma") {
	  echo "
	   <select name='subprograma' id='subprograma' class='selectMed' onchange='getOptions_2(this.id, \"proyecto\")'>
			<option value=''>";
				getSubprograma2("", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="proyecto") {
		echo "
		<select name='proyecto' id='proyecto' class='selectMed' onchange='getOptions_2(this.id, \"actividad\")'>
			<option value=''>";
				getProyecto2("", $_POST['opcion'], 0);
		echo "</select>*";
	}
}
//  SELECTS DEPENDIENTES (4 SELECTS)
elseif ($_POST['accion']=="getOptions_4") {
	if ($_POST['tabla']=="programa") {
	echo"
		<select name='programa' id='programa' class='selectMed' onchange='getOptions_3(this.id, \"subprograma\", \"proyecto\")'>
			<option value=''>";
				getPrograma2("", $_POST['opcion'], 0);
		echo "</select>*";
	}
	elseif ($_POST['tabla']=="subprograma") {
		echo "
		<select name='subprograma' id='subprograma' class='selectMed' onchange='getOptions_3(this.id, \"proyecto\", \"actividad\")'>
			<option value=''>";
				getSubprograma2("", $_POST['opcion'], 0);
		echo "</select>*";
}
}
//	SELECTS DEPENDIENTES (5 SELECTS)
elseif($_POST['accion']=="getOptions_5") {
	if($_POST['tabla']=="programa") {
	  echo"
	   <select name='programa' id='programa' class='selectMed' onchange='getOptions_4(this.id, \"subprograma\", \"proyecto\",\"actividad\")'>
			<option value=''>";
				getPrograma2("", $_POST['opcion'], 0);
		echo "</select>*";
	}
}
//////////////////////////////////////////////////////////////////////////////////////////////////////
?>