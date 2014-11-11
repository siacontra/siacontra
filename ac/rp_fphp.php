<?
//include ("fphp.php");
//// ---------------------------------------------------------- */ 
//// REPORTE LIBRO DIARIO
function getContabilidad( $fContabilidad, $opt){
   //connect();
	switch ($opt) {
		case 0:
			$sql="select CodContabilidad, Descripcion FROM ac_contabilidades ";
			$qry=mysql_query($sql) or die ($sql.mysql_error());
			$row = mysql_num_rows($qry);
			if($row!=0){ 
			  for($i=0; $i<$row; $i++){
				$field=mysql_fetch_array($qry);
				if($field['0']==$fContabilidad)echo "<option value='".$field[0]."' selected>".htmlentities($field[1])."</option>";
				else echo "<option value='".$field[0]."'>".htmlentities($field[1])."</option>";
			  }
			}
			break;
	}
}
//// ---------------------------------------------------------- */ 
//// ---------------------------------------------------------- */ 
//// ---------------------------------------------------------- */ 


?>