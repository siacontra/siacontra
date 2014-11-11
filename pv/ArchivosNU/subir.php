<?
 include "conexion.php";
$fechactual=date("Y-m-d H:m:s");
$row = 1;
$fp = fopen ("datos.csv","r");
while ($data = fgetcsv ($fp, 1000, ";"))
{
$num = count ($data);
print " <br>";
$row++;
echo "$row- ".$data[0].$data[1].$data[2].$data[3].$data[4].$data[5].$data[6].$data[7];
$partidaconc=$data[0].$data[1].$data[2].$data[3];
$sql="INSERT INTO pv_partida 
              (cod_partida, partida1, generica, especifica, subespecifica, denominacion, 
			   cod_tipocuenta, Estado, UltimoUsuario, UltimaFecha, tipo) 
			 VALUES 
			  ('$partidaconc','$data[0]','$data[1]','$data[2]','$data[3]','$data[4]','$data[5]','$data[6]',
			  '".$_SESSION['USUARIO_ACTUAL']"','$fechactual','$data[7]')";
mysql_query($sql);
}
fclose ($fp); 
 ?>