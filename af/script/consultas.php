<?
 $dbhost="localhost";  // host del MySQL (generalmente localhost)
 $dbusuario=$_SESSION["MYSQL_USER"]; // ingresar el nombre de usuario para acceder a la base
 $dbpassword=$_SESSION["MYSQL_USER"]; // password de acceso para el usuario
 $db="saicom";        // Seleccionamos la base con la cual trabajar
 $conexion = mysql_connect($dbhost, $dbusuario, $dbpassword);
mysql_select_db($db, $conexion);
?>
<table>
<tr>
 <td>DispPresupuestaria</td><td>DispFinanciera</td>
</tr>
<tr>
<?
 $sql = "select DispPresupuestaria, DispFinanciera, CodPartida, Secuencia from pv_ejecucionpresupuestaria where CodPresupuesto = '0002' and Periodo = '2012-01'";
 $qry = mysql_query($sql) or die ($sql.mysql_error());
 $row = mysql_num_rows($qry);
 if($row!=0){
   for($i=0; $i<$row; $i++){
     $field = mysql_fetch_array($qry);
      echo"<tr><td width='50'>".$field['3']."</td><td width='50'>".$field['2']."</td><td width='50'>".$field['0']."</td><td width='50'>".$field['1']."</td></tr>";
	  $total1 = $total1 + $field['0'];
	  $total2 = $total2 + $field['1'];
   }
   echo"<tr><td width='50'></td><td width='50'>TOTAL = </td><td width='50'>$total1</td><td width='50'>$total2</td></tr>";
 }
?>
 </tr>
 </table>