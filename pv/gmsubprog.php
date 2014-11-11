<?
include "conexion_.php";
if($accion==guardar){
   $ahora=date("Y-m-d H:m:s");
   $codsub=$_POST['codsubprog'];
   $descripcion=$_POST['descripcion'];
   ////  SE COMPARA LOS DATOS PARA VERIFICAR SI YA FUERON INTRODUCIDOS   //////
   $result=mysql_query("SELECT * FROM pv_subprog1 WHERE (cod_programa='".$_POST['sprograma']."' AND cod_subprog='$codsub')", $conexion);
   if (mysql_num_rows($result)!=0){
       echo "<script>";
       echo "alert('Los datos ya han sido registrados con anterioridad')";
       echo "</script>"; 
   }else
	  // INSERTAMOS LOS DATOS EN LA TABLA UNA VEZ COMPROBADOS 
	  $qry=mysql_query("INSERT INTO pv_subprog1(cod_subprog,descp_subprog,cod_programa,
	                                            UltimoUsuario,UltimaFecha,Estado) 
								        VALUES ('".$_POST['codsubprog']."','".$_POST['descripcion']."','".$_POST['sprograma']."',
										       '".$_SESSION['USUARIO_ACTUAL']."','$ahora','".$_POST['status']."')");
}else{//////////////////GARDAR LOS DATOS MODIFICADOS/////////////////////////
     if($accion==editar){
       $codp=$_POST['codprograma'];
	   $codsub=$_POST['codsub'];
       $descripcion=$_POST['descripcion'];
       ////  SE COMPARA LOS DATOS PARA VERIFICAR SI YA FUERON INTRODUCIDOS   //////
       $result=mysql_query("SELECT * FROM pv_subprog1 WHERE cod_programa='$codp'", $conexion);
       if (mysql_num_rows($result)!=0){
		  $qry=mysql_query("UPDATE pv_subprog1 SET descp_subprog='$descripcion',
		                                           UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."',
												   UltimaFecha='$ahora',
												   cod_programa='$codsub'
											   WHERE (cod_programa='$codp' AND cod_subprog='$codsub')");
       }
     }else{
	       if($accion==eliminar){
			 $sql2="SELECT cod_subprog FROM pv_proyecto1 WHERE cod_subprog='".$_POST['cod_subprog']."'";
			 $query2=mysql_query($sql2) or die ($sql2.mysql_error());
			 if(mysql_num_rows($query2)!=0){
				$field2=mysql_fetch_array($query2);
				$sql3="SELECT cod_proyecto FROM pv_actividad1 WHERE cod_proyecto='".$field2['cod_proyecto']."'";
				$query3=mysql_query($sql3) or die ($sql3.mysql_error()); 
				if(mysql_num_rows($query3)!=0){
				   echo "<script>";
                   echo "alert('El Registro que desea eliminar esta enlazado a otras tablas(Proyecto y Actividad)')";
                   echo "</script>"; 
				}
			 }else{
				 $sql="DELETE FROM pv_subprog1 WHERE cod_subprog='".$_POST['registro']."'";
		         $query=mysql_query($sql) or die ($sql.mysql_error());
			 }
		  }
	   }
}
