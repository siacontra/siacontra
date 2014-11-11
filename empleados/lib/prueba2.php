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
  
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    $row++;
    $cadena = "insert into miTabla(Campo1,Campo2,Campo3,Campo4) values("; //Cambia los valores 'CampoX' por el nombre de tus campos de tu tabla y colócales los necesarios




    for ($c=0; $c < $num; $c++) { //Aquí va colocando los campos en la cadena, si aun no es el último campo, le agrega la coma (,) para separar los datos
        if ($c==($num-1))
              $cadena = $cadena."'".  $data[$c]. "'";
        else
              $cadena = $cadena."'".$data[$c] . "',";
    }

    $cadena = $cadena.");"; 
    
    echo $cadena."<br>";  
     $enlace=Conectarse();
/////////////////////////////////////////////////////////
     $result=mysql_query($cadena, $enlace); 
/////////////////////////////////////////////////////////
     mysql_close($enlace);
}

fclose($handle);

?>
<h2>Se insertaron <?php echo $row ?> Registros en la tabla miTabla</h2>
