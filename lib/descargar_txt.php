<?php
extract($_POST);
extract($_GET);
$nombre_archivo = strtr($nombre_archivo, " ", "_");
$enlace = $nombre_archivo.".txt";
header ("Content-Disposition: attachment; filename=".$enlace."\n\n");
header ("Content-Type: application/octet-stream; charset=iso-8859-1");
readfile($enlace);
unlink($enlace);
?>