<?php
include("funciones.php");
///////////////////////////////////////////////////////////////////////////////
//	SCRIPTS PARA AJAX
///////////////////////////////////////////////////////////////////////////////
//PRESUPUESTO AJUSTES
if ($_POST['modulo']=="ELIMINAR") {
	connect();
if($_POST['accion']=="ELIMINARAJUSTE"){
 $SQL="SELECT * FROM pv_ajustepresupuestariodet WHERE CodAjuste='".$_POST['CodAjuste']."'";
 $QRY=mysql_query($SQL) or die ($SQL.mysql_error());
 $ROW=mysql_num_rows($QRY);
 if($ROW!=0){
   $SQL="DELETE FROM pv_ajustepresupuestariodet WHERE CodAjuste='".$_POST['CodAjuste']."'";
   $QRY=mysql_query($SQL) or die ($SQL.mysql_error());
 }
}
//// ------------------------------------------------------
//// ------------------------------------------------------
}

if ($_POST['modulo']=="AJUSTES") {
	//codigo	0013|2014-06-17|0001|0001 = $_POST['CodAjuste']
	
		connect();
		list($CodAjuste, $FechaAjuste, $CodOrganismo, $CodPresupuesto ) = SPLIT( '[|]', $_POST['codigo']); 

	
		if($_POST['accion']=="ANULAR"){
			// SE CONSULTA SI ESTA APROBADO. 
			$SQL_D="SELECT * FROM pv_ajustepresupuestario WHERE CodAjuste='".$CodAjuste."' AND Estado = 'AP'";
			$QRY_D=mysql_query($SQL_D) or die ($SQL_D.mysql_error());
			$ROW_D=mysql_num_rows($QRY_D);
                        
			if($ROW_D!=0){
				
				echo "NO PUEDE ANULARLO! YA FUE APROBADO!!";
			} else
			
			{
			
				$SQL="SELECT * FROM pv_ajustepresupuestario WHERE CodAjuste='".$CodAjuste."' AND Estado <> 'AP' AND Estado <> 'AN'  ";
					$QRY=mysql_query($SQL) or die ($SQL.mysql_error());
					$ROW=mysql_num_rows($QRY);
					if($ROW!=0){
					//$SQL="UPDATE FROM pv_ajustepresupuestariodet WHERE CodAjuste='".$_POST['CodAjuste']."'";
				  $SQL="UPDATE pv_ajustepresupuestario  as pva
					SET  
					      pva.Estado ='AN'  ,  
					      pva.UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."' ,
					      pva.UltimaFechaModif = NOW()
					      
					WHERE (pva.Organismo='".$CodOrganismo."') 
					AND   (pva.CodPresupuesto='".$CodPresupuesto."') 
					AND   (pva.CodAjuste='".$CodAjuste."')";

				  $QRY=mysql_query($SQL) or die ($SQL.mysql_error());
                                  
				  $SQL1="UPDATE pv_ajustepresupuestariodet  as pva
					SET  
					      pva.Estado ='AN'  ,  
					      pva.UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."' ,
					      pva.UltimaFechaModif = NOW()
					      
					WHERE (pva.Organismo='".$CodOrganismo."') 
					AND   (pva.CodPresupuesto='".$CodPresupuesto."') 
					AND   (pva.CodAjuste='".$CodAjuste."')";

				  $QRY1=mysql_query($SQL1) or die ($SQL1.mysql_error());   
				       
					}
					}
			}// para validar que no este aprobado
//// ------------------------------------------------------
//// ------------------------------------------------------
}


if ($_POST['modulo']=="REINTEGRO") {
	//codigo	0013|2014-06-17|0001|0001 = $_POST['CodAjuste']
	
		connect();
		list($CodAjuste, $FechaAjuste, $CodOrganismo, $CodPresupuesto ) = SPLIT( '[|]', $_POST['codigo']); 

		if($_POST['accion']=="ANULAR"){
			// SE CONSULTA SI ESTA APROBADO. 
			$SQL_D="SELECT * FROM pv_ReintegroPresupuestario WHERE CodReintegro='".$CodAjuste."' AND Estado = 'AP'";
			$QRY_D=mysql_query($SQL_D) or die ($SQL_D.mysql_error());
                        
			$ROW_D=mysql_num_rows($QRY_D);
                        
			if($ROW_D!=0){
				
				die ("NO PUEDE ANULARLO! YA FUE APROBADO!!");
			} else
                             
			{
			
				$SQL="SELECT * FROM pv_ReintegroPresupuestario WHERE CodReintegro='".$CodAjuste."' AND Estado <> 'AP' AND Estado <> 'AN'  ";
                                $QRY=mysql_query($SQL) or die ($SQL.mysql_error());
                                $ROW=mysql_num_rows($QRY);
                                if($ROW!=0){
                                            
				  $SQL="UPDATE pv_ReintegroPresupuestario  as pva
					SET  
					      pva.Estado ='AN'  ,  
					      pva.UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."' ,
					      pva.UltimaFechaModif = NOW()
					      
					WHERE (pva.Organismo='".$CodOrganismo."') 
					AND   (pva.CodPresupuesto='".$CodPresupuesto."') 
					AND   (pva.CodReintegro='".$CodAjuste."')";

				  $QRY=mysql_query($SQL) or die ($SQL.mysql_error());
                                  
				  $SQL1="UPDATE pv_ReintegroPresupuestariodet  as pva
					SET  
					      pva.Estado ='AN'  ,  
					      pva.UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."' ,
					      pva.UltimaFechaModif = NOW()
					      
					WHERE (pva.Organismo='".$CodOrganismo."') 
					AND   (pva.CodPresupuesto='".$CodPresupuesto."') 
					AND   (pva.CodReintegro='".$CodAjuste."')";

				  $QRY1=mysql_query($SQL1) or die ($SQL1.mysql_error());
				       
                                }
                        }
                    }// para validar que no este aprobado
//// ------------------------------------------------------
//// ------------------------------------------------------
}
///////////////////////////////////////////////////////////////////////////////
//	MAESTRO SECTOR
if ($_POST['modulo']=="APLICACIONES") {
    list($CodAjuste, $FechaAjuste, $CodOrganismo, $CodPresupuesto ) = SPLIT( '[|]', $_POST['codigo']); 
	connect();
	$error=0;
	//$ahora=date("Y-m-d H:i:s");
	$codigo=strtoupper($_POST['codigo']);
	$id_programa=strtoupper($_POST['id_programa']);
	$codigo1=strtoupper($_POST['cod_programa']);
	$descripcion=strtoupper(utf8_decode($_POST['descripcion']));
	//$periodo=strtr($_POST['periodo'], "/", "-");
	//$voucher=strtoupper($_POST['voucher']);
	///////////////////////////////////////////////////////////////////////////////////////////
	////// ************** *****  ELIMINAR REGISTRO MAESTRO SECTOR ***** **************** //////
 	if($_POST['accion']=="ELIMINAR"){//	CONSULTO SI EL REGISTRO A ELIMINAR
	   $sql="SELECT * FROM pv_sector WHERE Cod_sector='".$_POST['codigo']."'";
	   $query=mysql_query($sql) or die ($sql.mysql_error());
	   $rows=mysql_num_rows($query);
	   if($rows!=0){
	      $sql2="SELECT cod_sector FROM pv_programa1 WHERE cod_sector='".$_POST['codigo']."'";
		  $query2=mysql_query($sql2) or die ($sql2.mysql_error());
		  $rows2=mysql_num_rows($query2);
	      if($rows2!=0){
		    echo "<script>";
            echo "alert('EL REGISTRO ESTA ENLAZADO A OTRAS TABLAS')";
            echo "</script>"; 
		  }else{
		    $sql="DELETE FROM pv_sector WHERE Cod_sector='".$_POST['codigo']."'";
	        $query= mysql_query($sql) or die ($sql.mysql_error());
	      } 
	      //$error="REGISTRO EXISTENTE";
	   }
	 }
	 /////////////////////////////////////////////////////////////////////////////////////////
	 //// **** ELIMINAR ANTEPROYECTO
	 if($_POST['accion']=="ELIMINARANTEPROYECTO"){
	  	    $sql="DELETE FROM pv_antepresupuesto WHERE CodAnteproyecto='".$_POST['codigo']."'";
		    $query=mysql_query($sql) or die ($sql.mysql_error());
			
			$sql2="DELETE FROM pv_antepresupuestodet WHERE CodAnteproyecto='".$_POST['codigo']."'";
		    $query2=mysql_query($sql2) or die ($sql2.mysql_error());
	 }
	 //// **** ELIMINAR AJUSTE
	  if($_POST['accion']=="ELIMINARAJUSTE"){
	  	    $sql="DELETE FROM pv_ajustepresupuestario WHERE CodAjuste='".$CodAjuste."'";
		    $query=mysql_query($sql) or die ($sql.mysql_error());
			
			$sql2="DELETE FROM pv_ajustepresupuestariodet WHERE CodAjuste='".$CodAjuste."'";
		    $query2=mysql_query($sql2) or die ($sql2.mysql_error());
	 }
         //// **** ELIMINAR AJUSTE
	  if($_POST['accion']=="ELIMINARAREINTEGRO"){
              
	  	    $sql="DELETE FROM pv_ReintegroPresupuestario WHERE CodReintegro='".$CodAjuste."'";
		    $query=mysql_query($sql) or die ($sql.mysql_error());
			
			$sql2="DELETE FROM pv_ReintegroPresupuestariodet WHERE CodReintegro='".$CodAjuste."'";
		    $query2=mysql_query($sql2) or die ($sql2.mysql_error());
                   
	 }
	 //// **** ELIMINAR REFORMULACION
	 if($_POST['accion']=="ELIMINARREFORMULACION"){
	  	    $sql="DELETE FROM pv_reformulacionppto WHERE CodRef='".$_POST['codigo']."' AND Organismo='".$_SESSION['ORGANISMO_ACTUAL']."'";
		    $query=mysql_query($sql) or die ($sql.mysql_error());
			
			$sql2="DELETE FROM pv_reformulacionpptodet WHERE CodRef='".$_POST['codigo']."' AND Organismo='".$_SESSION['ORGANISMO_ACTUAL']."'";
		    $query2=mysql_query($sql2) or die ($sql2.mysql_error());
	 }
	///// ************** *****  ELIMINAR REGISTRO MAESTRO PROGRAMA ***** **************** /////
	if($_POST['accion']=="ELIMINARPROG"){
	   $sql="SELECT * FROM pv_programa1 WHERE id_programa='".$_POST['codigo']."'";
	   $query=mysql_query($sql) or die ($sql.mysql_error());
	   $rows=mysql_num_rows($query);
	   if($rows!=0){
	     $sql2="SELECT id_programa FROM pv_subprog1 WHERE id_programa='".$_POST['codigo']."'";  
		 $query2=mysql_query($sql2) or die ($sql2.mysql_error());
		 $rows2=mysql_num_rows($query2);
		 if($rows2!=0){
		    echo "<script>";
            echo "alert('EL REGISTRO ESTA ENLAZADO A OTRAS TABLAS')";
            echo "</script>"; 
		 }else{
		    $sql="DELETE FROM pv_programa1 WHERE id_programa='".$_POST['codigo']."'";
		    $query=mysql_query($sql) or die ($sql.mysql_error());
	     }
	      //$error="REGISTRO EXISTENTE";
	   }
	}else{///////////////////////////////////////////////////////////////////////////////////////////
	     ////// ************** *****  ELIMINAR REGISTRO MAESTRO SUB-PROGRAMA ***** ********** ///////
	   if($_POST['accion']=="ELIMINARSP"){
	     $sql="SELECT * FROM pv_subprog1 WHERE id_sub='".$_POST['codigo']."'";
		 $query= mysql_query($sql) or die ($sql.mysql_error());
		 $rows=mysql_num_rows($query);
		 if($rows!=0){
		    $sql2="SELECT * FROM pv_proyecto1 WHERE id_sub='".$_POST['codigo']."'";  
		    $query2=mysql_query($sql2) or die ($sql2.mysql_error());
		    $rows2=mysql_num_rows($query2);
		    if($rows2!=0){
			   echo "<script>";
               echo "alert('EL REGISTRO ESTA ENLAZADO A OTRAS TABLAS')";
               echo "</script>"; 
			}else{
			   $sql="DELETE FROM pv_subprog1 WHERE id_sub='".$_POST['codigo']."'";
			   $query=mysql_query($sql) or die ($sql.mysql_error());
		    }
		 }
		   //$error="REGISTRO EXISTENTE";
	   }else{///////////////////////////////////////////////////////////////////////////////////////////
	         ////// ************** *****  ELIMINAR REGISTRO MAESTRO PROYECTO ***** ********** //////////
	        if($_POST['accion']=="ELIMINARPROY"){
		      $sql="SELECT * FROM pv_proyecto1 WHERE id_proyecto='".$_POST['codigo']."'";
		      $query= mysql_query($sql) or die ($sql.mysql_error());
		      $rows= mysql_num_rows($query);
		      if($rows!=0){
			     $sql2="SELECT * FROM pv_actividad1 WHERE id_proyecto='".$_POST['codigo']."'";
				 $query2=mysql_query($sql2) or die ($sql2.mysql_error());
				 $rows2=mysql_num_rows($query2);
				 if($rows2!=0){
				   echo"<script>";
				   echo"alert('EL REGISTRO ESTA ENLAZADO A OTRAS TABLAS')";
				   echo"</script>";
				 }else{
				   $sql="DELETE FROM pv_proyecto1 WHERE id_proyecto='".$_POST['codigo']."'";
			       $query=mysql_query($sql) or die ($sql.mysql_error());
		         }
		        //$error="REGISTRO EXISTENTE";
		     }
	      }else{//////////////////////////////////////////////////////////////////////////////////////
	           ////// ************** *****  ELIMINAR REGISTRO MAESTRO ACTIVIDAD ***** ********** //////
		      if($_POST['accion']=="ELIMINARACT"){
		      $sql="SELECT * FROM pv_actividad1 WHERE id_actividad='".$_POST['codigo']."'";
		      $query= mysql_query($sql) or die ($sql.mysql_error());
		      $rows= mysql_num_rows($query);
		      if($rows!=0){
		        $sql="DELETE FROM pv_actividad1 WHERE id_actividad='".$_POST['codigo']."'";
			    $query=mysql_query($sql) or die ($sql.mysql_error());
		      }else{
		        $error="REGISTRO EXISTENTE";
		      }
		    }else{///////////////////////////////////////////////////////////////////////////////
			      //////////////// ***********  ELIMINAR PARTIDA ************   /////////////////
		      if($_POST['accion']=="ELIMINARPART"){
			    $sql="SELECT * FROM pv_partida WHERE cod_partida='".$_POST['codigo']."'";
				$query= mysql_query($sql) or die ($sql.mysql_error());
				$rows=mysql_num_rows($query);
				if($rows!=0){
				  $sql="DELETE FROM pv_partida WHERE cod_partida='".$_POST['codigo']."'";
				  $query=mysql_query($sql) or die ($sql.mysql_error());
				}else{
				  $error="REGISTRO EXISTENTE";
			    }
			  }else{////////////// ***** ELIMINAR REGISTRO UNIDAD EJECUTORA ***** //////////////////////
			    if($_POST['accion']=="ELIMINARUNIDAD"){
				 $sql="SELECT * FROM pv_unidadejecutora WHERE id_unidadejecutora='".$_POST['codigo']."'";
				 $query=mysql_query($sql) or die ($sql.mysql_error());
				 $rows=mysql_num_rows($query);
				 if($rows!=0){
				   $sql="DELETE FROM pv_unidadejecutora WHERE id_unidadejecutora='".$_POST['codigo']."'";
				 }else{
				    $error="REGISTRO EXISTENTE";
				 }				
				}
			   }	
			}
	      }
	} 
}
}
?>
