<? 
$scon = "select * from af_activo";
$qcon = mysql_query($scon) or die ($scon.mysql_error());
$rcon = mysql_num_rows($qcon);

if($rcon!=0) $fcon = mysql_fetch_array($qcon);

?>