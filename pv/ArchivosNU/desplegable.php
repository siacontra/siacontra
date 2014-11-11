<?php
/* Código que muestra los desplegables... 
 * Si recibe del valor de "desplegableInicial", muestra el primer desplegable
 * Si recibe un valor numerico, es para mostrar el segundo desplegable */
include "conexion.php";
if($_GET["show"]=="desplegableInicial")
{   
	      echo "<select name='desplegable1' id='desplegable1' onchange=\"javascript:Solicitud(this.value,'desp2')\">
		       <option value='' selected>Seleccione Sector</option>*";
         $sql="SELECT * FROM pv_sector WHERE 1";// CONEXION //* CONTACTO CON LOS DATOS *//
		 $qry=mysql_query($sql);
		 $cod_sector='';
		 $descp_tc='';
		 while($reg=mysql_fetch_assoc($qry)){
		   $cod_sector=$reg['cod_sector'];// CODIGO SECTOR
		   $descripcion=$reg['descripcion'];// DESCRIPCION SECTOR 
		     echo "<option value=$cod_sector>$cod_sector - $descripcion</option>";
		 }
        echo"</select>*";
}else{
	 if($_GET["show"]!=0){
	   $codsector=$_GET["show"];
	   //if($_GET["show2"]=="desple2"){
	   echo "<select name='desplegable2' id='desplegable2' onchange=\"javascript:(submit(),'desp3')\">
	   <option value='' selected>Seleccione Programa</option>*";
	   $sql=mysql_query("SELECT * FROM pv_programa1 WHERE cod_sector='$codsector'");
	   $codprograma='';
	   $descripcion='';
	   while($reg=mysql_fetch_assoc($sql)){
	      $codprograma=$reg['cod_programa'];
	  	  $descripcion=$reg['descp_programa'];
	      echo"<option value=$codprograma>$codprograma - $descripcion </option>";
	   //}
	 }
	 }else{
	    if($_GET["show"]!=0){
		  $codprograma=$_GET["show"];
		  echo"<select name='desplegable3' id='desplegable3' onchange=\"javascript:(this.value)\">
		       <option value='' selected>Seleccione Subprograma</option>*";
		  $sql=mysql_query("SELECT * FROM pv_subprog1 WHERE cod_programa='$codprograma'");
		  $codsubprog='';
		  $descripcion='';
		  while($reg=mysql_fetch_assoc($sql)){
		     $codsubprog=$reg['cod_subprog'];
			 $descripcion=$reg['descp_subprog'];
			 echo"<option value=$codsubprog>$codsubprog - $descripcion</option>";
		  }
		}
	 }	
	/*switch($_GET["show"])
	{
		case 1:
		      /*if(isset($_POST['desplegable2'])) 
		       $codprograma=$_POST['desplegable2']; 
		      else 
		       $codprograma=-1;*/
		      /*if($_GET["show"]!=0){
			    $codsector=$_GET["show"];
				echo "<select name='desplegable2' id='desplegable2' onchange=\"javascript:openUrl(this.value)\">
			      <option value='' selected>Seleccione Programa</option>";
		        $sql=mysql_query("SELECT * FROM pv_programa1 WHERE cod_sector='$codsector'");
			   $codprograma='';
			$descripcion='';
			while($reg=mysql_fetch_assoc($sql)){
			  $codprograma=$reg['cod_programa'];
			  $descripcion=$reg['descp_programa'];
			  echo"<option value=$codprograma>$codprograma - $descripcion </option>";
			}}
			break;
		/*case 2:
			echo "<select name='desplegable2' id='desplegable2' onchange=\"javascript:openUrl(this.value)\">
				<option value='' selected>Selecciona Opción</option>
				<option value='http://www.lawebdelprogramador.com/codigo/mostrar.php?id=93&texto=Visual+Basic'>Codigo de Visual Basic</option>
				<option value='http://www.lawebdelprogramador.com/codigo/mostrar.php?id=71&texto=PHP'>Codigo de PHP</option>
				<option value='http://www.lawebdelprogramador.com/codigo/mostrar.php?id=45&texto=JavaScript'>Codigo de JavaScript</option>
				</select>";
			break;
		case 3:
			echo "<select name='desplegable2' id='desplegable2' onchange=\"javascript:openUrl(this.value)\">
				<option value='' selected>Selecciona Opción</option>
				<option value='http://www.lawebdelprogramador.com/temas/mostrar.php?id=10&texto=Bases+de+Datos'>Temas de Bases de Datos</option>
				<option value='http://www.lawebdelprogramador.com/temas/mostrar.php?id=192&texto=Visual+Basic.NET'>Temas de Visual Basic .NET</option>
				<option value='http://www.lawebdelprogramador.com/temas/mostrar.php?id=48&texto=Linux'>Temas de Linux</option>
				</select>";
			break;*/
	//}
}
?>
