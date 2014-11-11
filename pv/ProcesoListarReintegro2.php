<?php echo "
<form name='frmentrada' action='ProcesoListarReintegro.php?limit=0' method='POST'>";
//////////  ORGANISMO //////////
if($_POST['chkorganismo']=="1"){ 
   $obj[0]="checked"; $obj[1]="enabled"; $obj[2]=$_POST['forganismo']; 
}else{ 
   $obj[0]="checked"; $obj[1]="enabled"; $obj[2]=$_POST['forganismo']; 
}
echo" <input type='hidden' name='limit' id='limit' value='".$limit."'/>
      <input type='hidden' name='registros' id='registros' value='".$registros."'/>

<div class='divBorder' style='width:900px;'>
<table width='900' class='tblFiltro'>
<tr>
 <td width='50' align='right'>Organismo:</td>
 <td width='150'>
  <input type='checkbox' name='chkorganismo' id='chkorganismo' value='1' $corganismo onclick='this.checked=true' />
  <select name='forganismo' id='forganismo' class='selectBig' $dorganismo onchange='getFOptions_2(this.id, \"fnanteproyecto\", \"chknanteproyecto\");'>";
		getOrganismos($forganismo, 3, $_SESSION[ORGANISMO_ACTUAL]);
		echo "
   </select>
 </td>
 <td width='80' align='right'>Fecha de Ejercicio:</td>
 <td>
  <input type='checkbox' name='chkejercicio' id='chkejercicio' value='1' $cejercicio onclick='enabledPEjercicio(this.form);' />
  <input type='text' name='fejercicio' id='fejercicio' size='6' maxlength='4' $dejercicio value='$fejercicio' />
  </td>
</tr>
<tr>"; if($_POST[fpreparado]!=''){
          $fpreparado2=$_POST[fpreparado];
        }
    
echo "<td width='90' align='right'>Nro. Presupuesto:</td>
  <td width='150'>
	<input type='checkbox' name='chknpresupuesto' id='chknpresupuesto' value='1' $cnpresupuesto onclick='enabledPNpresupuesto(this.form);' />
	<input type='text' name='fnpresupuesto' id='fnpresupuesto' size='6' maxlength='4' $dnpresupuesto value='$fnpresupuesto' />
  </td>
  <td width='50' align='right'>Estado:</td>
  <td><input type='checkbox' name='chkstatus' id='chkstatus' value='1' $cstatus onclick='enabledStatus(this.form);' />
	  <select name='fstatus' id='fstatus' class='selectMed' $dstatus>
				<option value=''></option>";
				getEstadoAjuste($fstatus, 0);
				echo "
			</select>
  </td>
</tr>
<tr>
  <td width='90' align='right'>Nro. Reintegro:</td>
  <td>
	<input type='checkbox' name='chknajuste' id='chknajuste' value='1' $cnajuste onclick='enabledPNajuste(this.form);' />
	<input type='text' name='fnajuste' id='fnajuste' size='6' maxlength='4' $dnajuste value='$fnajuste' />
  </td>
 <td width='50' align='right'>Fecha Reintegro:</td>
 <td width='78' align='left'>
  <input type='checkbox' name='chkfajuste' id='chkfajuste' value='1' $cajuste onclick='enabledPFajuste(this.form);' />
  <input type='text' name='fdesde' id='fdesde' size='15' maxlength='10' $dajuste value='$fdesde'  onkeyup='getTotalDiasPermisos();' /> - 
  <input type='text' name='fhasta' id='fhasta' size='15' maxlength='10' $dajuste value='$fhasta'  onkeyup='getTotalDiasPermisos();' />
 </td>
</tr>
<tr>
  <td width='90' align='right'></td>
  <td></td>
  <td width='50' align='right'></td>
  <td width='180' align='left'>
	
 </td>
</table>
</div>
<center><input type='submit' name='btBuscar' value='Buscar'></center>
<br /><div class='divDivision'>Listado de Reintegros</div><br />
<form/>"; 
?>
