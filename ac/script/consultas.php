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
  <td>Secuencia</td><td>CodPartida</td><td>DispPresupuestaria</td><td>DispFinanciera</td>
</tr>
<?
 $sql = "select DispPresupuestaria, 
                DispFinanciera, 
				CodPartida, 
				Secuencia 
		   from 
		        pv_ejecucionpresupuestaria 
		  where 
		        CodPresupuesto = '0002' and Periodo = '2012-01'";
 $qry = mysql_query($sql) or die ($sql.mysql_error());
 $row = mysql_num_rows($qry);
 if($row!=0){
   for($i=0; $i<$row; $i++){
     $field = mysql_fetch_array($qry);
	 $sumaDispPresupuestaria = $sumaDispPresupuestaria + $field['0'];
	 $sumaDispDispFinanciera = $sumaDispDispFinanciera + $field['1'];
      echo"<tr><td width='50' align='center'>".$field['3']."</td><td width='50' align='center'>".$field['2']."</td><td width='50' align='right'>".$field['0']."</td><td width='50' align='right'>".$field['1']."</td></tr>";
   }
 }
?>
<tr>
 <td></td><td>TOTAL = </td><td><?=$sumaDispPresupuestaria;?></td><td><?=$sumaDispDispFinanciera;?></td>
</tr>
 </table> </br>
 
 <table>
 <tr>
  <td>CODIGO PART</td><td>MONTO PART</td>
 </tr>
  <?
  $s_compromiso = "select * from lg_distribucioncompromisos where Estado<>'AN' and Periodo='2012-01' ";
  $q_compromiso = mysql_query($s_compromiso) or die ($s_compromiso.mysql_error());
  $r_compromiso = mysql_num_rows($q_compromiso);
  if($r_compromiso!=0){
  for($i=0; $i<$r_compromiso; $i++){

    $f_compromiso = mysql_fetch_array($q_compromiso);
	$montoPart = $f_compromiso['Monto'] + $montoPart;
	echo"<tr><td>".$f_compromiso['cod_partida']."</td><td align='right'>$montoPart</td></tr>";
	$total = $total + $f_compromiso['Monto'];
  }}
  ?>
 <tr><td>Total= =<?=$total;?></td></tr>
 </table>
 
 