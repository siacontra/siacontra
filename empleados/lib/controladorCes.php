<?php
/****************************************************************************************
* DEV: CONTRALORIA DEL ESTADO SUCRE - VENEZUELA
* PROYECTO: SIACEDA
* OPERADORES_____________________________________________________________________________________________________________________________
* | # | PROGRAMADOR          |  |   FECHA    |  |   HORA   |   CELULAR  |   VERSION PAG  | DESCRIPCION DEL CAMBIO 
* | 2 | Christian Hernandez   |  | 27/09/2012 |  | 02:16:18 | 04128354891|      0.1.1.A   | creacion del script
* |________________________________________________________|_____________________________________________________________________________
* TIPO: PHP
* DESCRIPCION: 
* UBICACION: VENEZUELA, SUCRE, CUMANA
* VERSION: 0.1.1.A 
* SOPORTE: Christian Hernandez 
* CONTACTO: www.cgesucre.gob.ve, @CESucre, contraloria.estado.sucre@cgesucre.gob.ve
*******************************************************************************************/
	
	session_start();
	set_time_limit(-1);
	ini_set('memory_limit','128M');
	include ("../funciones.php");

    	include_once ("../../clases/MySQL.php");
	include_once("../../comunes/objConexion.php");

     
	foreach($_POST as $nombreCampo => $valor)
	{
		$$nombreCampo = $valor;
	}

	foreach($_GET as $nombreCampo => $valor)
	{
		$$nombreCampo = $valor;
	}


	switch($caso)
	{
            	
            case 'cargarImagenFamiliar':

		$sql1 = "SELECT *
			FROM rh_cargafamiliar
			WHERE
			CodPersona = '".$CodPersona."' AND
			CodSecuencia = '".$CodSecuencia."'";

		$resultado = $objConexion->consultar($sql1,'fila');
		

		if (array_key_exists('HTTP_X_FILE_NAME', $_SERVER) && array_key_exists('CONTENT_LENGTH', $_SERVER)) 
		{
			$fileName = $_SERVER['HTTP_X_FILE_NAME'];
			$contentLength = $_SERVER['CONTENT_LENGTH'];

		} else throw new Exception("Error retrieving headers");

			$path = '../../imagenes/fotos/';
			$path2 = '../../imagenes/tmp/';



		if (!$contentLength > 0) {

		    throw new Exception('No file uploaded!');
		}
		
		$ruta = $path.$resultado['Foto'];
//echo $ruta;
		unlink($ruta);

			file_put_contents(
			    $path . $fileName,
			    file_get_contents("php://input")
			);

			/*file_put_contents(
			    $path2 .'tmp_'.$aleatorio.$fileName,
			    file_get_contents("php://input")
			);*/

		
		//$ran = rand(0, 1000000);
		//$ruta = $path.$_SESSION["_USUARIO"]."_tmp_$ran".".".$partes[1];
		//$im = $_SESSION["_USUARIO"]."_tmp_$ran".".".$partes[1];

		chmod($path.$fileName, 0777);

		//chmod($path2.'tmp_'.$aleatorio.$fileName, 0777);
		
		$nombreNuevo = $path.$aleatorio.$nombreFoto.'.jpg';

		rename($path.$fileName,$nombreNuevo);

		/*rename($path2.$fileName,$path.$_GET['nombreFoto'].'.jpg');*/
		
		if($objConexion->modificar(array("Foto='".$aleatorio.$nombreFoto.'.jpg'."'","CodPersona='".$CodPersona."' and CodSecuencia=".$CodSecuencia),'rh_cargafamiliar') == true)
		{	
			echo $aleatorio.$nombreFoto.'.jpg';// $_GET['nombreFoto'];

		} else {

			echo '0';
		}

            break;

            
            default://para pruebas
                 
	}

?>
