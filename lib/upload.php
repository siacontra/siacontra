<?php

if (array_key_exists('HTTP_X_FILE_NAME', $_SERVER) && array_key_exists('CONTENT_LENGTH', $_SERVER)) {
    $fileName = $_SERVER['HTTP_X_FILE_NAME'];
    $contentLength = $_SERVER['CONTENT_LENGTH'];
} else throw new Exception("Error retrieving headers");

$path = '../imagenes/tmp/';

if (!$contentLength > 0) {
    throw new Exception('No file uploaded!');
}

file_put_contents(
    $path . $fileName,
    file_get_contents("php://input")
);

chmod($path.$fileName, 0777);
