<?php
// Script Que copia el archivo temporal subido al servidor en un directorio.
echo '<p>Nombre Temporal: '.$_FILES['fileUpload']['tmp_name'].'</p>';
echo '<p>Nombre en el Server: '.$_FILES['fileUpload']['name'].'</p>';
echo '<p>Tipo de Archivo: '.$_FILES['fileUpload']['type'];
$tipo = substr($_FILES['fileUpload']['type'], 0, 4);
// Definimos Directorio donde se guarda el archivo
$dir = 'archs/';
// Intentamos Subir Archivo
// (1) Comprovamos que existe el nombre temporal del archivo
if (isset($_FILES['fileUpload']['tmp_name'])) {
// (2) - Comprovamos que se trata de un archivo de imágen
if ($tipo == 'text') {
// (3) Por ultimo se intenta copiar el archivo al servidor.
if (!copy($_FILES['fileUpload']['tmp_name'], $dir.$_FILES['fileUpload']['name']))
echo '<script> alert("Error al Subir el Archivo");</script>';
}
else echo 'El Archivo que se intenta subir NO ES del tipo Imagen.';
}
else echo 'El Archivo no ha llegado al Servidor.';
?>