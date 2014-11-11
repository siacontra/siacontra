<?php
    session_start();
    include('acceso_db.php'); // inclu�mos los datos de acceso a la BD
    // comprobamos que se haya iniciado la sesi�n
    if(isset($_SESSION["USUARIO_ACTUAL"])) {
        session_destroy();
        //REDIRECCIONAR header("Location: acceso.php");
		echo"
 					<script>
   						window.location='acceso.php';
	 				</script>
 					";}
    else {
        echo "Operaci�n incorrecta.";
    }
?> 