<? echo "
<form name='frmentrada' action='presupuesto_ajustelistar.php?limit=0' method='POST'>";
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
  <input type='checkbox' name='chkorganismo' id='chkorganismo' value='1' $obj[0] onclick='enabledOrganismo(this.form);' />
  <select name='forganismo' id='forganismo' class='selectBig' $obj[1] onchange='getFOptions_2(this.id, \"fnanteproyecto\", \"chknanteproyecto\");'>
   <option value=''></option>";
		getOrganismos($obj[2], 3, $_SESSION[ORGANISMO_ACTUAL]);
		echo "
   </select>
 </td>
 <td width='80' align='right'>Fecha de Ejercicio:</td>
 <td>
  <input type='checkbox' name='chkejercicio' id='chkejercicio' value='1' $obj[10] onclick='enabledPEjercicio(this.form);' />
  <input type='text' name='fejercicio' id='fejercicio' size='6' maxlength='3' $obj[11] value='$obj[12]' />
  </td>
</tr>
<tr>"; if($_POST[fpreparado]!=''){
          $fpreparado2=$_POST[fpreparado];
        }
    
echo "<td width='90' align='right'>Nro. Presupuesto:</td>
  <td width='150'>
	<input type='checkbox' name='chknpresupuesto' id='chknpresupuesto' value='1' $obj[3] onclick='enabledPNpresupuesto(this.form);' />
	<input type='text' name='fnpresupuesto' id='fnpresupuesto' size='6' maxlength='3' $obj[4] value='$obj[5]' />
  </td>
  <td width='50' align='right'>Estado:</td>
  <td><input type='checkbox' name='chkstatus' id='chkstatus' value='1' $obj[17] onclick='enabledStatus(this.form);' />
	  <select name='fstatus' id='fstatus' class='selectMed' $obj[18]>
				<option value=''></option>";
				getEstadoAntp("", 0);
				echo "
			</select>
  </td>
</tr>
<tr>
  <td width='90' align='right'>Nro. Ajuste:</td>
  <td>
	<input type='checkbox' name='chknajuste' id='chknajuste' value='1' $obj[6] onclick='enabledPNajuste(this.form);' />
	<input type='text' name='fnajuste' id='fnajuste' size='6' maxlength='3' $obj[7] value='$obj[9]' />
  </td>
 <td width='50' align='right'>Fecha Ajuste:</td>
 <td width='78' align='left'>
  <input type='checkbox' name='chkfajuste' id='chkfajuste' value='1' $obj[14] onclick='enabledPFajuste(this.form);' />
  <input type='text' name='fdesde' id='fdesde' size='15' maxlength='10' $obj[15] value='$obj[16]'  onkeyup='getTotalDiasPermisos();' /> - 
  <input type='text' name='fhasta' id='fhasta' size='15' maxlength='10' $obj[15] value='$obj[16]'  onkeyup='getTotalDiasPermisos();' />
 </td>
</tr>
<tr>
  <td width='90' align='right'></td>
  <td></td>
  <td width='50' align='right'>T. Ajuste:</td>
  <td width='180' align='left'>
	<input type='checkbox' name='chktajuste' id='chktajuste' value='1' $obj[20] onclick='enabledTajuste(this.form);' />
	<select name='ftajuste' id='ftajuste' class='selectMed' $obj[21]>
	    <option value=''></option>
		<option value='IN'>Incremento</option>
		<option value='DI'>Disminución</option>
	</select>
 </td>
</tr>
</table>
</div>
<center><input type='submit' name='btBuscar' value='Buscar'></center>
<br /><div class='divDivision'>Listado de Ajustes</div><br />
<form/>"; 
?>
