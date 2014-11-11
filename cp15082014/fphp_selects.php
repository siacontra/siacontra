<?php
include("fphp.php");
//-------------- CORRESPONDENCIA
// ---------------------------------------------------------------
if($_POST['accion']=="getDepenInt"){
  if($_POST['tabla']=="dep_interna"){ //// SE OBTIENE LA DEPENDENCIA INTERNA SEGUN ORGANISMO SELECCIONADO PREVIAMENTE
    
	echo"
	<select name='dep_interna' id='dep_interna' class='selectBig' onchange='getObtenerValor(document.getElementById(\"organismo\").value, this.value);'>
	 <option value=''>";
      getDependencias("", $_POST['opcion'], 0);
	 echo"</select>|";
	 
	 list($nombre, $cargo, $codigo_interno, $codigo_persona, $codigo_cargo) = getObtenerValor($_POST['opcion'], "");
	 
	echo "$nombre|$cargo|$codigo_interno|$codigo_persona|$codigo_cargo";
  }
}

if($_POST['accion']=="getObtenerValor"){
	list($nombre, $cargo, $codigo_interno, $codigo_persona, $codigo_cargo) = getObtenerValor($organismo, $dependencia);
	echo utf8_encode("$nombre|$cargo|$codigo_interno|$codigo_persona|$codigo_cargo");
}
// ---------------------------------------------------------------
// ---------------------------------------------------------------
if($_POST['accion']=="getDepenExterna"){
  if($_POST['tabla']=="dep_externa"){ //// SE OBTIENE LA DEPENDENCIA EXTERNASEGUN ORGANISMO SELECCIONADO PREVIAMENTE
    
	echo"
	<select name='dep_externa' id='dep_externa' class='selectBig' onchange='getObtenerValorExterno(document.getElementById(\"organismo\").value, this.value);'>
	 <option value=''>";
      getDependenciasExternas("", $_POST['opcion'], 0);
	 echo"</select>|";
	 
	 list($nombre, $cargo) = getObtenerValorExterno($_POST['opcion'], "");
	 
	echo "$nombre|$cargo";
  }
}

if($_POST['accion']=="getObtenerValorExterno"){
	list($nombre, $cargo) = getObtenerValorExterno($organismo, $dependencia);
	echo "$nombre|$cargo";
}

// ---------------------------------------------------------------
// ---------------------------------------------------------------







// ---------------------------------------------------------------
if($_POST['accion2']=="getDestinatarioInt"){ /// REPRESNTANTE LEGAL
  if($_POST['tabla2']=="destinatario_int"){ //// SE OBTIENE EL DESTINATARIO INTERNO SEGUN DEPENDENCIA SELECCIONADA PREVIAMENTE
    /*echo"
	<select name='destinatario_int' id='destinatario_int' class='selectBig' onchange='getDestinatarioInt(this.id,\"cargodestinatario_int\");'>
	 <option value=''>";
      getRepreInt("", $_POST['opcion'], 0);*/
	   getObtenerValor("", $_POST['opcion'], 0);
  }
}
// ---------------------------------------------------------------
if($_POST['accion']=="getCargoDestinatarioInt"){ /// CARGO DEL REPRESENTANTE LEGAL
  if($_POST['tabla']=="cargodestinatario_int"){ //// SE OBTIENE EL CARGO DESTINATARIO INTERNO SEGUN DEPENDENCIA SELECCIONADA PREVIAMENTE
   
	echo"
	<select name='cargodestinatario_int' id='cargodestinatario_int' class='selectBig'>";
      getCargoRepInt("", $_POST['opcion'], 0);
  }
}
// ---------------------------------------------------------------
// ---------------------------------------------------------------
if($_POST['accion']=="getOptionsDep"){
  if($_POST['tabla']=="dependencia"){
    echo"
	<select name='dependencia' id='dependencia' class='selectBig' onchange='getOptionsRepresentanteExt(this.id,\"destinatario\");'>
	  <option value=''>";
	    getDependenciaExt( "", $_POST['opcion'], 0);
	echo" </select>*";
  }
}
// ---------------------------------------------------------------
// OBTENGO DEPENDENCIA PARA ENTRADA DE NUEVO DOCUMENTO EXTERNO
// ---------------------------------------------------------------
if($_POST['accion']=="getOptionsDep2"){ //// SE OBTIENE LA DEPENDENCIA EXTERNA SEGUN EL ORGANISMO SELECCIONADO PREVIAMENTE
  if($_POST['tabla']=="dependencia"){
    echo"
	<select name='dependencia' id='dependencia' class='selectBig' onchange='getOptionsRepresentanteExt2(this.id,\"remitente\");'>
	  <option value=''>";
	    getDependenciaExt( "", $_POST['opcion'], 0);
	echo" </select>*";
  }else{
    if($_POST['tabla']=="dep_int"){ //// SE OBTIENE LA DEPENDENCIA INTERNA SEGUN EL ORGANISMO SELECCIONADO PREVIAMENTE
	echo"
	<select name='dep_int' id='dep_int' class='selectBig' onchange='getOptionRepresentanteDepInt(this.id,\"remitente_int\");'>
	  <option value=''>";
	    getDependencia("", $_POST['opcion'], 0);
	}
  }
}
// ---------------------------------------------------------------
// OBTENGO EL DESTINATARIO O REPRESENTANTE DE DEPENDENCIA PARA 
// ENTRADA DE NUEVO DOCUMENTO EXTERNO
// ---------------------------------------------------------------
if($_POST['accion']=="getOptionsRep"){
  if($_POST['tabla']=="destinatario"){
    echo"
	<select name='destinatario' id='destinatario' class='selectBig' onchange='getOptionsCargoRepExt(this.id,\"cargodestinatario\");'>
	 <option value=''>";
	    getRepreExt( "", $_POST['opcion'], 0, "");
	echo" </select>*";
  }else{
    if($_POST['tabla']=="remitente"){/// SE OBTIENE EL REMITENTE EXTERNO, SELECCIONADO PREVIAMENTE LA DEPENDENCIA 
      echo"
	  <select name='remitente' id='remitente' class='selectBig' onchange='getOptionsCargoRepExt(this.id,\"cargoremitente\");'>
	    <option value=''>";
	    getRepreExt( "", $_POST['opcion'], 0, "");
	    echo" </select>*";
    }else{
	   if($_POST['tabla']=="remitente_int"){
         echo"
	     <select name='remitente_int' id='remitente_int' class='selectBig' onchange='getOptionsCargoRepExt(this.id,\"cargoremitente_int\");'>
	       <option value=''>";
	       getRepreExt( "", $_POST['opcion'], 0, "");
	       echo" </select>*";
	  }
   }
  }
}
// ---------------------------------------------------------------
// OBTENGO EL CARGO DEL REPRESENTANTE DE DEPENDENCIA PARA 
// ENTRADA DE NUEVO DOCUMENTO EXTERNO O SALIDA DE DOCUMENTO EXTERNO
// ---------------------------------------------------------------
if($_POST['accion']=="getOptionsCarg"){
  if($_POST['tabla']=="cargodestinatario"){
    echo"
	<select name='cargodestinatario' id='cargodestinatario' class='selectBig'>";
	    getCargoExt( "", $_POST['opcion'], 0);
	echo" </select>*";
  }else{
     if($_POST['tabla']=="cargoremitente"){
	  echo"
	  <select name='cargoremitente' id='cargoremitente' class='selectBig'>";
	     getCargoExt( "", $_POST['opcion'], 0, $_POST['organismo'], $_POST['dependencia']);
	   echo" </select>*";
	 }else{
	   if($_POST['tabla']=="cargoremitente_int"){ //// SE OBTIENE EL CARGO DEL REPRESENTANTE DEPENDENCIA INTERNA
	     echo"
	     <select name='cargoremitente' id='cargoremitente' class='selectBig'>";
	       getCargoRepreInt( "", $_POST['opcion'], 0, $_POST['organismo'], $_POST['dependencia']);
	     echo" </select>*";
	   }
     }
  }
}
/// -----------------------------------------------------------------------------------------------------------
if($_POST['accion']=="getObtRemit"){
  echo"<script>";
  echo"alert('Paso')";
  echo"</script>";
 connect();
 $sql="SELECT 
            RepresentLegal, 
			CargoRepresentLegal 
	   FROM 
	        pf_dependenciasexternas 
	  WHERE 
	        CodOrganismo='".$organismo."' AND CodDependencia='".$dependencia."'"; //echo $sql;
  $query=mysql_query($sql) or die ($sql.mysql_error());
  $rows=mysql_num_rows($query);
  if($rows!=0){
  $field=mysql_fetch_array($query);
  
   if($_POST['tabla']=="destinatario"){ echo"<input type='text' name='destinatario' id='destinatario' size='60' value='".$field['Representante']."'/>";} 
   if($_POST['tabla2']=="cargodestinatario"){ echo"<input type='text' name='cargodestinatario' id='cargodestinatario' size='60' value='".$field['CargoRepresentLegal']."' />";}

   }  
}
// ---------------------------------------------------------------
// ---------------------------------------------------------------

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