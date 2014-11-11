<?php
	$ajuste=$_GET['ajuste'];
	/*$cn = mysql_connect($_SESSION["MYSQL_HOST"],$_SESSION["MYSQL_USER"],$_SESSION["MYSQL_USER"]);
	mysql_select_db("bd_anidados");
	$sql="SELECT codpro,despro FROM provincia WHERE coddep=$dep";
	$rs=mysql_query($sql);*/
	header('Content-Type: text/xml');
	echo "<?xml version='1.0' encoding='ISO-8859-1' standalone='yes'?>\n";
	echo "<provincias>\n";
    if(($FIELD['TipoAjusteR']=CA)and ($FIELD['TipoAjuste']=IN)){$tAjusteR=Credito_Adicional;}
    if(($FIELD['TipoAjusteR']=TI)and ($FIELD['TipoAjuste']=IN)){$tAjusteR=Traslados_Internos;}
    if(($FIELD['TipoAjusteR']=IP)and ($FIELD['TipoAjuste']=DI)){$tAjusteR=Insuficiencia_Presupuestaria;}
    if(($FIELD['TipoAjusteR']=RP)and ($FIELD['TipoAjuste']=DI)){$tAjusteR=Rebaja_Presupuestaria;}
		echo "<provincia>";
		if(($FIELD['TipoAjusteR']=CA)or($FIELD['TipoAjusteR']=TI)){
		echo "<option value='".$FIELD['TipoAjusteR']."'>$tAjusteR</option>
              <option value=''></option>
			  <option value='CA'>Credito_Adicional</option>
			  <option value='TI'>Traslados_Internos</option> ";
		echo "<codigo>".$reg['codpro']."</codigo>";
		echo "<descri>".$reg['despro']."</descri>";
		echo "</provincia>\n";
	    }else{
	     echo "<option value='".$FIELD['TipoAjusteR']."'>$tAjusteR</option>
              <option value=''></option>
			  <option value='IP'>Insuficiencia_Presupuestaria</option>
			  <option value='RP'>Rebaja_Presupuestaria</option> ";
		 echo "<codigo>".$reg['codpro']."</codigo>";
		 echo "<descri>".$reg['despro']."</descri>";
		 echo "</provincia>\n"; 	
	    	
	    }
	    echo "</provincia>\n";
	echo "</provincias>";

?>