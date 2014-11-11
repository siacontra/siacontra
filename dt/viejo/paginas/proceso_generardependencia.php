<?
include('acceso_db.php');
//Validacion
		if ($dependencia=="Seleccione la Dependencia"){
					echo"
 					<script>
   						window.location='../paginas/pantallapordependencia.php';
    					alert('Seleccione la Dependencia');
	 				</script>
 					";
					}else{ 
		//Termina la Validacion		
		echo"
 					<script>
   						window.location='pdfpordependencia.php?dependencia=$dependencia';
   	 				</script>
 					";
	}
?>