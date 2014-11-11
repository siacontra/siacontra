<?php

/* Código que lee un archivo .csv con datos, para luego insertarse en una base de datos, vía MySQL
*  Gracias a JoG
*  http://gualinx.wordpress.com
*/   

function Conectarse() //Función para conectarse a la BD
{
       if (!($link=mysql_connect("localhost",$_SESSION["MYSQL_USER"],$_SESSION["MYSQL_CLAVE"])))  { //Cambia estos datos
           echo "Error conectando a la base de datos.";
           exit();
       }
        if (!mysql_select_db("mibd",$link)) {
            echo "Error seleccionando la base de datos.";
           exit();
       }
       return $link;
}

$row = 1;
$handle = fopen("datos.csv", "r"); //Coloca el nombre de tu archivo .csv que contiene los datos
while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) { //Lee toda una linea completa, e ingresa los datos en el array 'data'
    $num = count($data); //Cuenta cuantos campos contiene la linea (el array 'data')
  

    $row++;
  
    
  
    
                    $CodPersona = $data[0]
					$Apellido1  = $data[1]
					$Apellido2  = $data[2]
					$Nombres  = $data[3]
					$Busqueda  = $data[4]
					$Nacionalidad  = $data[5]
					$NomCompleto  = $data[6]
					$EstadoCivil  = $data[7]
					$Sexo  = $data[8]
					$Fnacimiento  = $data[9]
					$CiudadNacimiento  = $data[10]
					$FedoCivil  = $data[11]
					$Direccion  = $data[12]
					$Telefono1  = $data[13]
					$Telefono2  = $data[14]
					$CiudadDomicilio  = $data[15]
					$Fax  = $data[16]
					$Lnacimiento  = $data[17]
					$NomEmerg1  = $data[18]
					$DirecEmerg1  = $data[19]
					$TelefEmerg1  = $data[20]
					$DocFiscal  = $data[21]
					$EdoReg  = $data[21]
					$Ndocumento  = $data[22]
					$CelEmerg1  = $data[23]
					$ParentEmerg1  = $data[24]
					$NomEmerg2  = $data[25]
					$DirecEmerg2  = $data[26]
					$TelefEmerg2  = $data[27]
					$CelEmerg2  = $data[28]
					$SituacionDomicilio  = $data[29]
					$ParentEmerg2  = $data[30]
					$TipoDocumento  = $data[31]
					$Email  = $data[32]
					$GrupoSanguineo  = $data[33]
					$Observacion  = $data[34]
					$TipoLicencia  = $data[35]
					$Nlicencia  = $data[36]
					$ExpiraLicencia  = $data[37]
					$SiAuto  = $data[38]
					$_SESSION["USUARIO_ACTUAL"]  = 'ADMINISTRADOR';
				  
				  
				  $cadena = "insert into miTabla  set
				  
                    CodPersona = '".$CodPersona."',
					Apellido1 = '".changeUrl($Apellido1)."',
					Apellido2 = '".changeUrl($Apellido2)."',
					Nombres = '".changeUrl($Nombres)."',
					Busqueda = '".changeUrl($Busqueda)."',
					Nacionalidad = '".$Nacionalidad."',
					NomCompleto = '".changeUrl($NomCompleto)."',
					EstadoCivil = '".$EstadoCivil."',
					Sexo = '".$Sexo."',
					Fnacimiento = '".formatFechaAMD($Fnacimiento)."',
					CiudadNacimiento = '".$CiudadNacimiento."',
					FedoCivil = '".formatFechaAMD($FedoCivil)."',
					Direccion = '".changeUrl($Direccion)."',
					Telefono1 = '".$Telefono1."',
					Telefono2 = '".$Telefono2."',
					CiudadDomicilio = '".$CiudadDomicilio."',
					Fax = '".changeUrl($Fax)."',
					Lnacimiento = '".$Lnacimiento."',
					NomEmerg1 = '".changeUrl($NomEmerg1)."',
					DirecEmerg1 = '".changeUrl($DirecEmerg1)."',
					TelefEmerg1 = '".changeUrl($TelefEmerg1)."',
					DocFiscal = '".changeUrl($DocFiscal)."',
					TipoPersona = 'N',
					Estado = '".$EdoReg."',
					Ndocumento = '".$Ndocumento."',
					EsCliente = 'N',
					CelEmerg1 = '".changeUrl($CelEmerg1)."',
					EsProveedor = 'N',
					ParentEmerg1 = '".changeUrl($ParentEmerg1)."',
					NomEmerg2 = '".changeUrl($NomEmerg2)."',
					EsEmpleado = 'S',
					EsOtros = 'N',
					DirecEmerg2 = '".changeUrl($DirecEmerg2)."',
					TelefEmerg2 = '".changeUrl($TelefEmerg2)."',
					CelEmerg2 = '".changeUrl($CelEmerg2)."',
					SituacionDomicilio = '".$SituacionDomicilio."',
					ParentEmerg2 = '".changeUrl($ParentEmerg2)."',
					TipoDocumento = '".$TipoDocumento."',
					Email = '".changeUrl($Email)."',
					GrupoSanguineo = '".$GrupoSanguineo."',
					Observacion = '".changeUrl($Observacion)."',
					TipoLicencia = '".$TipoLicencia."',
					Nlicencia = '".changeUrl($Nlicencia)."',
					ExpiraLicencia = '".formatFechaAMD($ExpiraLicencia)."',
					SiAuto = '".$SiAuto."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
 
    echo $cadena."<br>";  //Muestra la cadena para ejecutarse

     $enlace=Conectarse();
     $result=mysql_query($cadena, $enlace); //Aquí está la clave, se ejecuta con MySQL la cadena del insert formada
     mysql_close($enlace);
}

fclose($handle);

?>
<h2>Se insertaron <?php echo $row ?> Registros en la tabla miTabla</h2>
