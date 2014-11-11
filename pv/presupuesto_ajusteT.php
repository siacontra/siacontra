<?php
    /*$cn = mysql_connect($_SESSION["MYSQL_HOST"],$_SESSION["MYSQL_USER"],$_SESSION["MYSQL_USER"]);
	mysql_select_db("bd_anidados");
	$sql="SELECT * FROM departamento";
	$rs=mysql_query($sql);*/
	header('Content-Type: text/xml');
	echo "<?xml version='1.0' encoding='ISO-8859-1' standalone='yes'?>\n";
	echo "<departamentos>\n";
	if($FIELD['TipoAjuste']=DI){$tAjuste=Disminucion;}else{$tAjuste=Incremento;}
		echo "<departamento>";
		echo "<option value='".$FIELD['TipoAjuste']."'>$tAjuste</option>
              <option value=''></option>
			  <option value='DI'>Disminucion</option>
			  <option value='IN'>Incremento</option> ";
		echo "<codigo>".$reg['coddep']."</codigo>";
		echo "<descri>HOLA</descri>";
		echo "</departamento>\n";
	echo "</departamentos>";
?>