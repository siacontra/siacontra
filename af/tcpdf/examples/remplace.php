<?php

  function remplace($nombre)
    {
$vocales = array ( "�");
$nombre = str_replace ($vocales, "&Aacute;", $nombre);
$vocales = array ( "�");
$nombre = str_replace ($vocales, "&Eacute;", $nombre);
$vocales = array ( "�","�");
$nombre = str_replace ($vocales, "&Iacute;", $nombre);
$vocales = array ( "�");
$nombre = str_replace ($vocales, "&Oacute;", $nombre);
$vocales = array ( "�");
$nombre = str_replace ($vocales, "&Uacute;", $nombre);
$vocales = array ( "�");
$nombre = str_replace ($vocales, "&Ntilde;", $nombre);
$vocales = array ( "�");
$nombre = str_replace ($vocales, "&aacute;", $nombre);
$vocales = array ( "�");
$nombre = str_replace ($vocales, "&eacute;", $nombre);
$vocales = array ( "�");
$nombre = str_replace ($vocales, "&iacute;", $nombre);
$vocales = array ( "�");
$nombre = str_replace ($vocales, "&oacute;", $nombre);
$vocales = array ( "�");
$nombre = str_replace ($vocales, "&uacute;", $nombre);
$vocales = array ( "�");
$nombre = str_replace ($vocales, "&ntilde;", $nombre);
echo $nombre;
}
remplace($nombres);
?>