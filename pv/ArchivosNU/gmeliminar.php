<?
include "conexion.php";
/* //////////////////////   ************** ELIMINAR SECTOR  *************/////////////////////////// */ 
if ($accion==eliminars){
   $result=mysql_query("SELECT * FROM pv_sector WHERE cod_sector='".$_POST['registro']."'", $conexion);
   if (mysql_query(result)!=0){
	  echo "<script>";
	  echo "confirm('Seguro que desea eliminar este registro?')";
	  echo "</script>";
	  $result="DELETE FROM pv_sector WHERE cod_sector='".$_POST['registro']."'";
	  $query= mysql_query(result) or die ($sql.mysql_error());
	  }else{
	    $result="DELETE FROM pv_sector WHERE cod_sector='".$_POST['registro']."'";
	    $query= mysql_query(result) or die ($sql.mysql_error());
	  }
}
?>
<!--

/////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////   ************** ELIMINAR PROGRAMA  *************//////////////////////////////////////
/*	    if ($accion==eliminarprog){
		   $result=mysql_query("SELECT * FROM pv_programa1 WHERE id_programa='".$_POST['registro']."'", $conexion);
		   if (mysql_num_rows($result)!=0){
			  echo '<script type=text/javascript laguage=javascript>
			        function confirmDel(){
                     Var agree=confirm("¿Realmente desea eliminarlo?");
                     if (agree) return true ;
                     else return false ;
                    }
			 
					</script>';
			  if ($confirmar==true){
		         $result="DELETE FROM pv_programa1 WHERE id_programa='".$_POST['registro']."'";
		         $query=mysql_query($result) or die ($result.mysql_error());
			  }//else{
			      //$result="DELETE FROM pv_programa1 WHERE id_programa='".$_POST['registro']."'";
		         // $query=mysql_query($result) or die ($result.mysql_error());
			  //}
			}
    	   }else{
		    ////////////////////// ************** ELIMINAR SUB-PROGRAMA  ************** /////////////////////////
	        if ($accion==eliminarsubprog){
			   $sql="DELETE FROM pv_subprog1 WHERE id_subprog='".$_POST['registro']."'";
			   $query=mysql_query($sql) or die ($sql.mysql_error());
		       }else{///////////////// ELIMINAR ACTIVIDAD ////////////////////////////////////////
				if ($accion==eliminaract){
				   $sql="DELETE FROM pv_actividad1 WHERE id_actividad='".$_POST['registro']."'";
				   $query=mysql_query($sql) or die ($sql.mysql_error());
				}
				}		   
		   }	  
		}
	}
?>*/-->

