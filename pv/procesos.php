<?
$sql ="select 
      		CodPartida, 
      		DispPresupuestaria, 
      		DispFinanciera
		from 
      		pv_ejecucionpresupuestaria 
		where 
      		Periodo>='2012-06'";
$qry= mysql_query($sql) or die ($sql.mysql_error());
$row= mysql_num_rows($qry);

$sql01 ="select 
      		CodPartida, 
      		DispPresupuestaria, 
      		DispFinanciera
		from 
      		pv_ejecucionpresupuestaria 
		where 
      		Periodo>='2012-06'";
$qry01= mysql_query($sql01) or die ($sql01.mysql_error());
$row01= mysql_num_rows($qry01);
echo "<table>";
for($i=0; $i<$row; $i++){


}


	 echo" </table>";



?>