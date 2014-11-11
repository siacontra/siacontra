<?php

include("../fphp.php");
connect();

switch ($accion) {
	
	case 'PERIODO':
	    
		
		 $sql="SELECT pyp.CodProyeccion, pyp.CodTipoNom, tn.Nomina,pyp.Periodo
						FROM
						py_proceso AS pyp
						INNER JOIN tiponomina AS tn ON tn.CodTipoNom = pyp.CodTipoNom
						WHERE pyp.CodProyeccion='$CodProyeccion'
						AND  tn.CodTipoNom='$CodTipoNom'
						GROUP BY 	pyp.Periodo ORDER BY tn.Nomina ASC";

						$query=mysql_query($sql) or die ($sql.mysql_error()); $rows=mysql_num_rows($query);
						
		
		
		echo "
		<input type='checkbox' name='chkperiodo' id='chkperiodo' value='1' onclick='forzarCheck(\"chkperiodo\");' checked />
		<select name='fperiodo' id='fperiodo' style='width:100px;' onchange=\"getFOptions_Proceso_py( 'ftiponom', 'ftproceso', 
																			'chktproceso', 
																			this.value, 
																			'',
																			document.getElementById('ftiponom').value,
																			document.getElementById('ftproyeccion').value,
																			document.getElementById('ftiponom').value,
																			document.getElementById('fperiodo').value);\">
				";
				
				for ($i=0; $i<$rows; $i++) {
						$field=mysql_fetch_array($query);
						if ($field['Periodo']==$codigo) echo "<option value='".$field['Periodo']."' selected>".($field['Periodo'])."</option>"; 
						else echo "<option value='".$field['Periodo']."'>".($field['Periodo'])."</option>";
						}
				//getPeriodos("", $_POST['opcion'], "", 1);
		echo "</select>*";


	break;
	
	
		case 'PROCESO':
	    
         if ($Periodo== "" || $Periodo== NULL) 

					{	$sql= "SELECT
						pyp.CodProyeccion,
						pyp.CodTipoNom,
						tn.Nomina,
						pyp.Periodo,
						pyp.CodTipoProceso,
						pr_tipoproceso.Descripcion
						FROM
						py_proceso AS pyp
						INNER JOIN tiponomina AS tn ON tn.CodTipoNom = pyp.CodTipoNom
						INNER JOIN pr_tipoproceso ON pr_tipoproceso.CodTipoProceso = pyp.CodTipoProceso
						WHERE pyp.CodProyeccion='$CodProyeccion'
						AND  tn.CodTipoNom='$CodTipoNom'
						GROUP BY pyp.CodTipoProceso
						";}
	     else 
	           {		$sql= "SELECT
						pyp.CodProyeccion,
						pyp.CodTipoNom,
						tn.Nomina,
						pyp.Periodo,
						pyp.CodTipoProceso,
						pr_tipoproceso.Descripcion
						FROM
						py_proceso AS pyp
						INNER JOIN tiponomina AS tn ON tn.CodTipoNom = pyp.CodTipoNom
						INNER JOIN pr_tipoproceso ON pr_tipoproceso.CodTipoProceso = pyp.CodTipoProceso
						WHERE pyp.CodProyeccion='$CodProyeccion'
						AND  tn.CodTipoNom='$CodTipoNom'
						AND pyp.Periodo = '$Periodo'
						GROUP BY pyp.CodTipoProceso
						";
					}	
		$query=mysql_query($sql) or die ($sql.mysql_error()); $rows=mysql_num_rows($query);
		
		echo "
		<input type='checkbox' name='chktproceso' id='chktproceso' value='1' onclick='forzarCheck(\"chktproceso\");' checked />
			<select name='ftproceso' id='ftproceso' class='selectBig'> ";
				echo" ";
				
				for ($i=0; $i<$rows; $i++) {
						$field=mysql_fetch_array($query);
						if ($field['CodTipoProceso']==$codigo) echo "<option value='".$field['CodTipoProceso']."' selected>".($field['Descripcion'])."</option>"; 
						else echo "<option value='".$field['CodTipoProceso']."'>".($field['Descripcion'])."</option>";
						}
				//getPeriodos("", $_POST['opcion'], "", 1);
		echo "</select>*";


	break;
	
	

	

}


?>
