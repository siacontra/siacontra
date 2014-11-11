<?php

  function remplace($nombre)
    {
$vocales = array ( "Á");
$nombre = str_replace ($vocales, "&Aacute;", $nombre);
$vocales = array ( "É");
$nombre = str_replace ($vocales, "&Eacute;", $nombre);
$vocales = array ( "Í","Ì");
$nombre = str_replace ($vocales, "&Iacute;", $nombre);
$vocales = array ( "Ó");
$nombre = str_replace ($vocales, "&Oacute;", $nombre);
$vocales = array ( "Ú");
$nombre = str_replace ($vocales, "&Uacute;", $nombre);
$vocales = array ( "Ñ");
$nombre = str_replace ($vocales, "&Ntilde;", $nombre);
$vocales = array ( "á");
$nombre = str_replace ($vocales, "&aacute;", $nombre);
$vocales = array ( "é");
$nombre = str_replace ($vocales, "&eacute;", $nombre);
$vocales = array ( "í");
$nombre = str_replace ($vocales, "&iacute;", $nombre);
$vocales = array ( "ó");
$nombre = str_replace ($vocales, "&oacute;", $nombre);
$vocales = array ( "ú");
$nombre = str_replace ($vocales, "&uacute;", $nombre);
$vocales = array ( "ñ");
$nombre = str_replace ($vocales, "&ntilde;", $nombre);
echo $nombre;
}
remplace($nombres);
?>