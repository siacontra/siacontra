<?php
session_start();

//	FUNCION PARA CONECTARSE CON EL SERVIDO MYSQL

function connect() {
	mysql_connect($_SESSION["MYSQL_HOST"], $_SESSION["MYSQL_USER"], $_SESSION["MYSQL_CLAVE"]) or die ("NO SE PUDO CONECTAR CON EL SERVIDOR MYSQL!");
     mysql_select_db($_SESSION["MYSQL_BD"]) or die ("¡NO SE PUDO CONECTAR CON LA BASE DE DATOS!");
	mysql_query("SET NAMES 'utf8'");
}
?>
