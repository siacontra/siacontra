<? 
/// ----------------------------------------------------------------------------------------------
/// FUNCION UTILIZADA EN R_PROYECTOPRESUPUESTO OBTENER QUIEN HA ELABORADO PROYECTOS
function getElaboradoPor($forganismo,$opt){
connect();
switch ($opt) {
case 0:
	$sql="SELECT * FROM pv_antepresupuesto where Organismo = '".$forganismo."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	for ($i=0; $i<$rows; $i++) {
		$field=mysql_fetch_array($query);
		if ($field['PreparadoPor']==$valor) echo "<option value='".$field['PreparadoPor']."' selected>".htmlentities($field['PreparadoPor'])."</option>"; 
		else echo "<option value='".$field['PreparadoPor']."'>".htmlentities($field['PreparadoPor'])."</option>";
	}
	break;
}	 
}
/// ----------------------------------------------------------------------------------------------
/// FUNCION UTILIZADA EN R_PROYECTOPRESUPUESTO 
function getEstadoRp($fEstado, $opt){
connect();
  switch($opt){
	case 0:
	      $status[0] = 'PE'; $d_status[0] = 'Pendiente';
		  $status[1] = 'PA'; $d_status[1] = 'Pagado';
		  $cant = 2;
		  for($i=0;$i<$cant;$i++){
		    if($status[$i]==$fEstado) echo"<option value='$status[$i]' selected>$d_status[$i]</option>";
			else echo"<option value='$status[$i]'> $d_status[$i]</option>";
		  }
  }
}
/// ----------------------------------------------------------------------------------------------
/// FUNCION UTILIZADA EN R_PROYECTOPRESUPUESTO 
function getSectorRP($fSector, $opt){
connect();
 switch($opt){
   case 0: 
          $sql = "select * from pv_sector order by cod_sector";
		  $qry = mysql_query($sql) or die ($sql.mysql_error());
		  $row = mysql_num_rows($qry); 
		  if($row!=0){
		    for($i=0; $i<$row; $i++){
			  $field = mysql_fetch_array($qry);
			  if($fSector == $field['cod_sector'])echo"<option value='".$field['cod_sector']."' selected>".$field['descripcion']."</option>"; 
			  else echo"<option value='".$field['cod_sector']."'>".$field['descripcion']."</option>"; 
			}
		  }
 }
}
function getProgramaRP($fSector, $fPrograma, $opt){
connect();
 switch($opt){
   case 0: 
          $sql = "select * from pv_programa1 where cod_sector='$fSector' order by cod_programa";echo"<option value='".$field['cod_programa']."' selected>".$sql."</option>";
		  $qry = mysql_query($sql) or die ($sql.mysql_error());
		  $row = mysql_num_rows($qry);
		  if($row!=0){
		    for($i=0; $i<$row; $i++){
			  $field = mysql_fetch_array($qry);
			  if($fPrograma == $field['cod_programa'])echo"<option value='".$field['cod_programa']."' selected>".$field['decp_programa']."</option>"; 
			  else echo"<option value='".$field['cod_programa']."'>".$field['decp_programa']."</option>"; 
			}
		  }
 }
}
function getSubProgramaRP($fPrograma, $fSubPrograma, $opt){
connect();
 switch($opt){
   case 0: 
          $sql = "select * from pv_subprog1 where id_programa ='$fPrograma' order by cod_programa";
		  $qry = mysql_query($sql) or die ($sql.mysql_error());
		  $row = mysql_num_rows($qry);
		  if($row!=0){
		    for($i=0; $i<$row; $i++){
			  $field = mysql_fetch_array($qry);
			  if($fPropgrama == $field['cod_programa'])echo"<option value='".$field['cod_programa']."' selected>".$field['decp_programa']."</option>"; 
			  else echo"<option value='".$field['cod_programa']."'>".$field['decp_programa']."</option>"; 
			}
		  }
 }
}
function getProyectoRP($fSubPrograma, $fProyecto, $opt){
connect();
 switch($opt){
   case 0: 
          $sql = "select * from pv_proyecto1 where id_sub='$fSubPrograma' order by cod_proyecto";
		  $qry = mysql_query($sql) or die ($sql.mysql_error());
		  $row = mysql_num_rows($qry);
		  if($row!=0){
		    for($i=0; $i<$row; $i++){
			  $field = mysql_fetch_array($qry);
			  if($fProyecto == $field['cod_proyecto'])echo"<option value='".$field['cod_proyecto']."' selected>".$field['decp_proyecto']."</option>"; 
			  else echo"<option value='".$field['cod_proyecto']."'>".$field['decp_proyecto']."</option>"; 
			}
		  }
 }
}
function getActividadRP($fProyecto, $fActividad, $opt){
connect();
 switch($opt){
   case 0: 
          $sql = "select * from pv_actividad1 where id_proyecto='$fProyecto' order by cod_actividad";
		  $qry = mysql_query($sql) or die ($sql.mysql_error());
		  $row = mysql_num_rows($qry);
		  if($row!=0){
		    for($i=0; $i<$row; $i++){
			  $field = mysql_fetch_array($qry);
			  if($fActividad==$field['cod_actividad'])echo"<option value='".$field['cod_actividad']."' selected>".$field['decp_actividad']."</option>"; 
			  else echo"<option value='".$field['cod_actividad']."'>".$field['decp_actividad']."</option>"; 
			}
		  }
 }
}
function getUnidadEjecutoraRP($fUnidadEjecutora, $opt){
connect();
 switch($opt){
   case 0: 
          $sql = "select * from pv_unidadejecutora where id_unidadejecutora='$fUnidadEjecutora' order by id_unidadejecutora";
		  $qry = mysql_query($sql) or die ($sql.mysql_error());
		  $row = mysql_num_rows($qry);
		  if($row!=0){
		    for($i=0; $i<$row; $i++){
			  $field = mysql_fetch_array($qry);
			  if($fUnidadEjecutora==$field['id_unidadejcutora'])
			     echo"<option value='".$field['id_unidadejecutora']."' selected>".$field['UnidadEjecutora']."</option>"; 
			  else echo"<option value='".$field['id_unidadejecutora']."'>".$field['UnidadEjecutora']."</option>"; 
			}
		  }
 }
}
?>