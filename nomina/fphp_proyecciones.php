<?php
session_start();
set_time_limit(-1);
ini_set('memory_limit','128M');  
extract($_POST);
extract($_GET); 
//include ("../funciones.php");


function getProyeccion_py($ftproyeccion, $opt) {
	connect();
	
	switch ($opt) {
		case 3:
			 $sql="SELECT CodProyeccion, Descripcion  FROM py_proyeccion  WHERE CodProyeccion='".$ftproyeccion."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$organismo) echo "<option value='".$field[0]."' selected>".($field[1])."</option>"; 
				else echo "<option value='".$field[0]."'>".($field[1])."</option>";
			}
			break;

	}
}

//	FUNCION PARA CARGAR LOS TIPOS DE NOMINA EN UN SELECT
function getTNomina_py($ftproyeccion,$tnomina, $opt) {
	connect();
	switch ($opt) {
		case 0:

             $sql="SELECT pyp.CodProyeccion, pyp.CodTipoNom, tn.Nomina 	FROM py_proceso AS pyp 	INNER JOIN tiponomina AS tn ON tn.CodTipoNom = pyp.CodTipoNom 	WHERE pyp.CodProyeccion='".$ftproyeccion."'  GROUP BY tn.CodTipoNom ";
           
           
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field['CodTipoNom']==$tnomina) echo "<option value='".$field['CodTipoNom']."' selected>".($field['Nomina'])."</option>"; 
						else echo "<option value='".$field['CodTipoNom']."'>".($field['Nomina'])."</option>";
			}
			break;
	}
}

//	FUNCION PARA CARGAR LOS PERIODOS
function getPeriodos_py($valor, $tiponom, $ftproyeccion, $opt) {
	connect();
	list($anio, $mes)=SPLIT( '[-.-]', $valor);
	switch ($opt) {
		case 0:
		
		//   if ($tiponom=="" || $valor=="" )  
			$sql="SELECT 	pyp.CodProyeccion, 	pyp.CodTipoNom, 	tn.Nomina, 	pyp.Periodo, 	pyp.CodTipoProceso, pr_tipoproceso.Descripcion
			FROM
			py_proceso AS pyp
			INNER JOIN tiponomina AS tn ON tn.CodTipoNom = pyp.CodTipoNom
			INNER JOIN pr_tipoproceso ON pr_tipoproceso.CodTipoProceso = pyp.CodTipoProceso
			WHERE
			pyp.CodProyeccion = '".$ftproyeccion."' AND tn.CodTipoNom = '".$tiponom."' -- AND pyp.Periodo ='".$valor."'
			GROUP BY pyp.Periodo";
			

			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				//$codigo=$field['Periodo'];
				if ($field['Periodo']==$valor) echo "<option value='".$field['Periodo']."' selected>".($field['Periodo'])."</option>"; 
				else echo "<option value='".$field['Periodo']."'>".($field['Periodo'])."</option>";
			}
			break;
	
	}
}




//	FUNCION PARA CARGAR LOS TIPOS DE PROCESO EN UN SELECT
function getTipoProcesoNomina_py($codigo, $Periodo, $CodTipoNom, $CodProyeccion, $opt) {
	connect();
	switch ($opt) {
		case 0:
			$sql="SELECT ptnp.CodTipoProceso AS Codigo, ptp.Descripcion FROM pr_tiponominaproceso ptnp INNER JOIN pr_tipoproceso ptp ON (ptnp.CodTipoProceso=ptp.CodTipoProceso) WHERE (ptnp.CodTipoNom='".$nomina."') ORDER BY ptnp.CodTipoProceso";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field['Codigo']==$codigo) echo "<option value='".$field['Codigo']."' selected>".($field['Descripcion'])."</option>"; 
				else echo "<option value='".$field['Codigo']."'>".($field['Descripcion'])."</option>";
			}
			break;
			
		case 1:			
			
			         if ($Periodo== "" || $Periodo== NULL) 

					{  	$sql= "SELECT
						pyp.CodProyeccion,
						pyp.CodTipoNom,
						tn.Nomina,
						pyp.Periodo,
						pyp.CodTipoProceso,
						pr_tipoproceso.Descripcion
						FROM
						py_proceso AS pyp
						INNER JOIN tiponomina AS tn ON tn.CodTipoNom = pyp.CodTipoNom
						INNER JOIN pr_tipoproceso ON pr_tipoproceso.CodTipoProceso = pyp.CodTipoProceso
						WHERE pyp.CodProyeccion='$CodProyeccion'
						AND  tn.CodTipoNom='$CodTipoNom'
						GROUP BY pyp.CodTipoProceso
						";}
	     else 
	           {	$sql= "SELECT
						pyp.CodProyeccion,
						pyp.CodTipoNom,
						tn.Nomina,
						pyp.Periodo,
						pyp.CodTipoProceso,
						pr_tipoproceso.Descripcion
						FROM
						py_proceso AS pyp
						INNER JOIN tiponomina AS tn ON tn.CodTipoNom = pyp.CodTipoNom
						INNER JOIN pr_tipoproceso ON pr_tipoproceso.CodTipoProceso = pyp.CodTipoProceso
						WHERE pyp.CodProyeccion='".$CodProyeccion."'
						AND  tn.CodTipoNom='".$CodTipoNom."'
						AND pyp.Periodo = '".$Periodo."'
						GROUP BY pyp.CodTipoProceso
						";
					}
			
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
					$field=mysql_fetch_array($query);
					if ($field['CodTipoProceso']==$codigo) echo "<option value='".$field['CodTipoProceso']."' selected>".($field['Descripcion'])."</option>"; 
					else echo "<option value='".$field['CodTipoProceso']."'>".($field['Descripcion'])."</option>";
			}
			break;
		
	}
}
?>
