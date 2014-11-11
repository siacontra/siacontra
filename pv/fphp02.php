<?php
session_start();
set_time_limit(-1);
ini_set('memory_limit','128M');  
include "conexion_.php";
//  FUNCION PARA CARGAR SECTOR DE PRESUPUESTO EN UN SELECT
function getSector2($sector, $fieldSector, $opt) {
	connect();
	switch ($opt){
		case 0:
			$sql="SELECT cod_sector,descripcion FROM pv_sector ORDER BY cod_sector";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for($i=0; $i<$rows; $i++) {
			  $field=mysql_fetch_array($query);
			  if($field[0]==$fieldSector){
				echo "<option value='".$field[0]."' selected>".$field[0]."-".htmlentities($field[1]); 
			  }else{
			    echo "<option value='".$field[0]."'>".$field[0]."-".htmlentities($field[1])."</option>";
			  }
		    }
			break;
		case 1:
			$sql="SELECT cod_sector,descripcion FROM pv_sector WHERE cod_sector='$sector' ORDER BY cod_sector";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field[0]."'>".$field[0]."-".htmlentities($field[1])."</option>";
			}
			break;
	}
}
//  FUNCION PARA CARGAR PROGRAMA DE PRESUPUESTO EN UN SELECT
function getPrograma2($programa, $sector, $opt) {
	//connect();
	switch ($opt){
		case 0:
			$sql="SELECT id_programa,cod_programa,descp_programa,cod_sector FROM pv_programa1 WHERE cod_sector='".$sector."' ORDER BY cod_sector,cod_programa";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$programa) echo "<option value='".$field[0]."' selected>".$field[1]."-".htmlentities($field[2]); 
				else echo "<option value='".$field[0]."'>".$field[1]."-".htmlentities($field[2])."</option>";
			}
			break;
		case 1:
			$sql="SELECT id_programa,cod_programa,descp_programa FROM pv_programa1 WHERE id_programa='".$programa."' ORDER BY cod_sector,cod_programa";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field[0]."'>".htmlentities($field[2])."</option>";
			}
			break;
	}
}
//	FUNCION PARA CARGAR SUBPROGRAMA DE PRESUPUESTOLOS EN UN SELECT
function getSubprograma2($subprograma, $programa, $opt) {
	//connect();
	switch ($opt) {
		case 0:
			$sql="SELECT id_sub,cod_subprog,descp_subprog,id_programa FROM pv_subprog1 WHERE id_programa='".$programa."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$subprograma) echo "<option value='".$field[0]."' selected>".$field[1]."-".htmlentities($field[2])."</option>"; 
				else echo "<option value='".$field[0]."'>".$field[1]."-".htmlentities($field[2])."</option>";
			}
			break;
		case 1:
			$sql="SELECT id_sub,cod_subprog,descp_subprog,id_programa FROM pv_subprog1 WHERE id_subprog='".$subprograma."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0){
				$field=mysql_fetch_array($query);
				echo "<option value='".$field[0]."'>".htmlentities($field[2])."</option>";
			}
			break;
	}
}
//	FUNCION PARA CARGAR PROYECTO DE PRESUPUESTO EN UN SELECT
function getProyecto2($proyecto, $subprograma, $opt) {
	//connect();
	switch ($opt) {
		case 0:
			$sql="SELECT id_proyecto,cod_proyecto,descp_proyecto,id_sub FROM pv_proyecto1 WHERE id_sub='".$subprograma."' ORDER BY id_sub,id_proyecto";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$proyecto) echo "<option value='".$field[0]."' selected>".$field[1]."-".htmlentities($field[2])."</option>"; 
				else echo "<option value='".$field[0]."'>".$field[1]."-".htmlentities($field[2])."</option>";
			}
			break;
		case 1:
			$sql="SELECT id_proyecto,cod_proyecto,descp_proyecto,id_sub FROM pv_proyecto1 WHERE id_proyecto='".$proyecto."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			}
			break;
	}
}
//	FUNCION PARA CARGAR ACTIVIDAD DE PRESUPUESTO EN UN SELECT
function getActividad2($actividad, $proyecto, $opt) {
	//connect();
	switch ($opt) {
		case 0:
			$sql="SELECT id_actividad,cod_actividad,descp_actividad,id_proyecto FROM pv_actividad1 WHERE id_proyecto='".$proyecto."' ORDER BY id_proyecto,id_actividad";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			for ($i=0; $i<$rows; $i++) {
				$field=mysql_fetch_array($query);
				if ($field[0]==$actividad) echo "<option value='".$field[0]."' selected>".$field[1]."-".htmlentities($field[2])."</option>"; 
				else echo "<option value='".$field[0]."'>".$field[1]."-".htmlentities($field[2])."</option>";
			}
			break;
		case 1:
			$sql="SELECT id_actividad,cod_actividad,descp_actividad,id_protyecto FROM pv_actividad1 WHERE id_actividad='".$actividad."'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				echo "<option value='".$field[0]."'>".htmlentities($field[2])."</option>";
			}
			break;
	}
}
?>