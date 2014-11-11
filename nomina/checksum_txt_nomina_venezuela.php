<?php
session_start();
header('Content-Type: text/html; charset=iso-8859-1');
set_time_limit(-1);
include("fphp_nomina.php");



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
            	
        case 'cargarArchivoCheck':
			if (array_key_exists('HTTP_X_FILE_NAME', $_SERVER) && array_key_exists('CONTENT_LENGTH', $_SERVER)) 
			{
				$fileName = $_SERVER['HTTP_X_FILE_NAME'];
				$contentLength = $_SERVER['CONTENT_LENGTH'];
	
			} else throw new Exception("Error retrieving headers");
	
			$path = '';
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
	
				
	
			chmod($path.$fileName, 0777);
			
			$nombreNuevo = $path.$nombreArchivo.'.txt';
	
			rename($path.$fileName,$nombreNuevo);

		/*rename($path2.$fileName,$path.$_GET['nombreFoto'].'.jpg');*/
		
		
        break;
		default:
                 
				connect();
				$texto="";
				$archivo=fopen($nombre_archivo.".txt", "w+");
				//---------------
				$LF = 0x0A;
				$CR = 0x0D;
				$nl = sprintf("%c%c",$CR,$LF);
				
				$fl = sprintf("%c",$CR);
				
				$fecha=date("d/m/y");
				//---------------
				
				//---------------
				/*$sql = "SELECT
							mp.Ndocumento,
							CONCAT(mp.Apellido1, ' ', mp.Nombres) AS Busqueda,
							ptne.TotalNeto,
							bp.Ncuenta,
							bp.TipoCuenta,
							rbp.CodBeneficiario,
							rbp.NroDocumento,
							rbp.NombreCompleto,
				
						FROM
							pr_tiponominaempleado ptne
							INNER JOIN mastpersonas mp ON (ptne.CodPersona = mp.CodPersona)
							INNER JOIN bancopersona bp ON (ptne.CodPersona = bp.CodPersona)
							LEFT JOIN rh_beneficiariopension rbp ON (mp.CodPersona = rbp.CodPersona)
						WHERE
							ptne.CodTipoProceso = '".$codproceso."' AND
							ptne.Periodo = '".$periodo."' AND
							ptne.CodOrganismo = '".$organismo."' AND
							ptne.TotalNeto > 0
						ORDER BY ptne.CodTipoNom, length(mp.Ndocumento), mp.Ndocumento";*/
				
				$sql = "SELECT mp.Ndocumento, CONCAT( mp.Apellido1, ' ', mp.Nombres ) AS Busqueda, 
					
					ptne.TotalNeto, bp.Ncuenta, 
					bp.TipoCuenta, 
					rbp.CodBeneficiario, 
					rbp.NroDocumento, 
					rbp.NombreCompleto, 
					me.NombreSobreViviente, 
					me.CedulaSobreViviente, 
					me.NombreSobrevivienteOtro, 
					me.CedulaSobrevivienteOtro
				
				FROM pr_tiponominaempleado ptne
				INNER JOIN mastpersonas mp ON ( ptne.CodPersona = mp.CodPersona )
				INNER JOIN mastempleado me ON ( me.CodPersona = mp.CodPersona )
				INNER JOIN bancopersona bp ON ( ptne.CodPersona = bp.CodPersona )
				LEFT JOIN rh_beneficiariopension rbp ON ( mp.CodPersona = rbp.CodPersona )
				
				WHERE
							ptne.CodTipoProceso = '".$codproceso."' AND
							ptne.Periodo = '".$periodo."' AND
							ptne.CodOrganismo = '".$organismo."' AND
							ptne.TotalNeto > 0
						ORDER BY ptne.CodTipoNom, length(mp.Ndocumento), mp.Ndocumento";
				
				$query = mysql_query($sql) or die ($sql.mysql_error());
				
				$cantidadFilas = mysql_num_rows($query);//cantidad de filas que trae la consulta
				$t = 0;
				
				while ($field = mysql_fetch_array($query)) {
					$t++;
				
					if ($field['CodBeneficiario'] != "") {
				
						$nombre = ereg_replace("[^A-Za-z 0-9]", "", $field['NombreCompleto']);
						$cedula = $field['NroDocumento'];
				
					} else if ($field['NombreSobreViviente'] != "") {
				
						$nombre = ereg_replace("[^A-Za-z 0-9]", "", $field['NombreSobreViviente']);
						$cedula = $field['CedulaSobreViviente'];
				
					} else {
						$nombre = ereg_replace("[^A-Za-z 0-9]", "", $field['Busqueda']);
						$cedula = $field['Ndocumento'];
					}
				
					if ($field['NombreSobrevivienteOtro'] != "") {
				
						$nombre = ereg_replace("[^A-Za-z 0-9]", "", $field['NombreSobrevivienteOtro']);
						$cedula = $field['CedulaSobrevivienteOtro'];
				
					}
				
					$sum += $field['TotalNeto'];
					//--
					if ($field['TipoCuenta'] == "CO") $tipo_cuenta = "0"; else $tipo_cuenta = "1";
					//--
					$nrocuenta = (string) str_repeat("0", 20-strlen($field['Ncuenta'])).$field['Ncuenta'];
					//--
					list($int, $dec)=SPLIT( '[.]', $field['TotalNeto']); $field_monto = "$int$dec";
					$monto = (string) str_repeat("0", 11-strlen($field_monto)).$field_monto;
					//--
					$relleno_1 = "0770";
					//--
					$nombre = (string) $nombre.str_repeat(" ", 40-strlen($nombre));
					//--
					$cedula = (string) str_repeat("0", 10-strlen($cedula)).$cedula;
					//--
					$relleno_2 = "003291  ";
					//--
					
					if($t != $cantidadFilas)
					{
						$texto.=$tipo_cuenta.$nrocuenta.$monto.$relleno_1.$nombre.$cedula.$relleno_2."$nl";
				
					} else {
				
						$texto.=$tipo_cuenta.$nrocuenta.$monto.$relleno_1.$nombre.$cedula.$relleno_2."$fl";
				
					}
				}
				
				list($int, $dec)=SPLIT( '[.]', $sum); 
				
				if(strlen($dec) == 1)
				{
					$dec = "".$dec."0";
				}
				
				if(strlen($dec) == 0)
				{
					$dec = "".$dec."00";
				}
				
				$total = "$int$dec";
				
				$total_neto = (string) str_repeat("0", 13-strlen($total)).$total;
					
				$titulo = "HContraloria del Estado                  0102051313000012385101".$fecha.$total_neto."03291 ".$nl;
				
				fwrite($archivo, $titulo.$texto);
				fclose($archivo);
				
				chmod($nombre_archivo.".txt", 0777);
				
				//$recursoArchivo1= fopen($nombre_archivo.".txt", "r");
				
				//$recursoArchivo2 = fopen("archivoAVerificar.txt", "r");
				
				//$contenidoFichero1 = fread($recursoArchivo1, filesize($nombre_archivo.".txt"));
				
				
				//$contenidoFichero2 = fread($recursoArchivo2, filesize("archivoAVerificar.txt"));
				
				
				$contenidoFichero1 = file_get_contents($nombre_archivo.".txt"); 
				
				$contenidoFichero2 = file_get_contents("archivoAVerificar.txt"); 
				
				//echo ($contenidoFichero1)."_".($contenidoFichero2);
				if(crc32(file_get_contents($nombre_archivo.".txt")) == crc32(file_get_contents("archivoAVerificar.txt")))
				{
					echo "1";
				
				} else {
				
					echo "0";
				}
				
				//echo crc32 (file_get_contents($nombre_archivo.".txt"))."_".crc32(file_get_contents("archivoAVerificar.txt"));
				
				//echo crc32($str1)."_".crc32($str2);
				
	}

?>
