<?php
$nombre_archivo = strtr($nombre_archivo, " ", "_");
$enlace = $nombre_archivo.".txt"; 
//header("Content-Disposition: attachment; filename="+$enlace); 
//header("Content-type: text/plain; charset=iso-8859-1\n");
header ("Content-Disposition: attachment; filename=".$enlace."\n\n");
header ("Content-Type: application/octet-stream; charset=iso-8859-1");
//header ("Content-Length: ".filesize($enlace));
readfile($enlace);
unlink($enlace);
//header ('Content-Type: text/html; charset=iso-8859-1');
?>