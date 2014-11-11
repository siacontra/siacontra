<?
include ("fphp.php");
connect();


 $s_af_activo = "select * from af_activo";
 $q_af_activo = mysql_query($s_af_activo) or die ($s_af_activo.mysql_error());
 $r_af_activo = mysql_fetch_array($q_af_activo); //echo $r_af_activo;
 if($r_af_activo!=0){
   for($i=0; $i<$r_af_activo; $i++){
     $f_af_activo=mysql_fetch_array($q_af_activo);
	 echo"<tr>
	        <td>".$f_af_activo['Activo']."</td>
	      </tr>";
   }
 }
?>