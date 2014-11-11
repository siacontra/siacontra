<?php
session_start();
set_time_limit(-1);
ini_set('memory_limit','128M');  

// FUNCION PARA VALIDAR FORMATO FECHA 
function validaFormatoFecha($fecha){
connect();
  $A=$fecha.date("Y");
  $D=$fecha.date("d");
  $M=$fecha.date("m");
}

?>