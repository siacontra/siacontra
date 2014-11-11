<? 
//include "fphp.php";
$fechaCompleta=date("Y-m-d H:m:s");
//// _____________________________________________________________________
          //// GUARDAR TIPO CORRESPONDENCIA
//// _____________________________________________________________________
if($accion==guardarTipoCorrespondencia){
  $sql="SELECT * FROM cp_tipocorrespondencia WHERE Descripcion='".$_POST['descripcion']."'";
  $qry=mysql_query($sql) or die ($sql.mysql_error());
  $row=mysql_num_rows($qry);
  if($rows!=0){
   echo"<script>";
   echo"alert('¡LOS DATOS HAN SIDO INTRODUCIDOS ANTERIORMENTE¡')";
   echo"</script>";
  }else{
    $sql="SELECT MAX(Cod_TipoDocumento) FROM cp_tipocorrespondencia";
    $qry=mysql_query($sql) or die ($sql.mysql_error());
    $field=mysql_fetch_array($qry);
  
    $codtipodocumento=(int) ($field[0]+1);
    $codtipodocumento=(string) str_repeat("0",4-strlen($codtipodocumento)).$codtipodocumento;
  
    $sql="INSERT INTO cp_tipocorrespondencia (Cod_TipoDocumento,
                                            Descripcion,
											Estado,
											FlagUsoInterno,
											FlagUsoExterno,
											FlagProcedenciaExterna,
											UltimoUsuario,
											UltimaFechaModif,
											DescripCorta) 
                                    VALUES ('$codtipodocumento',
									        '".$_POST['descripcion']."',
											'".$_POST['estado']."',
											'".$_POST['uso_interno']."',
											'".$_POST['uso_externo']."',
											'".$_POST['proc_externa']."',
											'".$_SESSION['USUARIO_ACTUAL']."',
											'$fechaCompleta',
											'".$_POST['descripCorta']."')";
    $qry=mysql_query($sql) or die ($sql.mysql_error());
  }
}
//// _____________________________________________________________________
          //// EDITAR TIPO CORRESPONDENCIA
//// _____________________________________________________________________
if($accion==editarTipoCorrespondencia){  
  $sql="UPDATE cp_tipocorrespondencia SET Descripcion='".$_POST['descripcion']."',
  									      Estado='".$_POST['estado']."',
										  FlagUsoInterno='".$_POST['uso_interno']."',
										  FlagUsoExterno='".$_POST['uso_externo']."',
										  FlagProcedenciaExterna='".$_POST['proc_externa']."',
										  UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."',
										  UltimaFechaModif='$fechaCompleta',
										  DescripCorta='".$_POST['descripCorta']."'
                                    WHERE 
									      Cod_TipoDocumento='".$_POST['cod_tipodocumento']."'";
  $qry=mysql_query($sql) or die ($sql.mysql_error());
}
//// ----------------------------------------------------------------------
//// GUARDAR DATOS PARTICULAR NUEVO
//// ----------------------------------------------------------------------
if($accion==guardarParticular) {

   $scon="select max(CodParticular) from cp_particular";
   $qcon=mysql_query($scon) or die ($scon.mysql_error());
   $fcon=mysql_fetch_array($qcon);
   
   $cod_part=(int) ($fcon[0]+1);
   $cod_part=(string) str_repeat("0",4-strlen($cod_part)).$cod_part;
	   
   $s="insert into cp_particular( CodParticular,
   								  Cedula,
								  Nombre,
								  Direccion,
								  Cargo,
								  Telefono,
								  UltimoUsuario,
								  UltimaFechaModif)
							values
							     ('$cod_part',
								  '".$_POST['cedula']."',
								  '".$_POST['nombre']."',
								  '".$_POST['direccion']."',
								  '".$_POST['cargo']."',
								  '".$_POST['telefono']."',
								  '".$_SESSION['USUARIO_ACTUAL']."',
								  '".date("Y-m-d H:i:s")."')";
	$q=mysql_query($s)or die ($s.mysql_error());
	
}
//// ----------------------------------------------------------------------
//// GUARDAR DATOS PARTICULAR EDITAR
//// ----------------------------------------------------------------------
if($accion==guardarParticularEditar){

   $s="update cp_particular set Cedula='".$_POST['cedula']."', 
                                Nombre= '".$_POST['nombre']."',
								Direccion = '".$_POST['direccion']."',
								Cargo = '".$_POST['cargo']."',
								UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
								UltimaFechaModif = '".date("Y-m-d H:i:s")."'
						  where 
						        CodParticular = '".$_POST['cod_particular']."'";
   $q=mysql_query($s) or die ($s.mysql_error());
}
//// ----------------------------------------------------------------------
//// ----------------------------------------------------------------------
?>
