<? echo "
<form name='frmentrada' action='presupuesto_revisar.php?limite=0' method='POST'>";
//////////  ORGANISMO //////////
if($_POST['chkorganismo']=="1"){ 
   $obj[0]="checked"; $obj[1]="enabled"; $obj[2]=$_POST['forganismo']; 
}else{ 
   $obj[0]="checked"; $obj[1]="enabled"; $obj[2]=$_POST['forganismo']; 
}
////////////////////////////////
echo" <input type='hidden' name='limit' id='limit' value='".$limit."'/>
      <input type='hidden' name='registros' id='registros' value='".$registros."'/>

<div class='divBorder' style='width:900px;'>
<table width='900' class='tblFiltro'>
 <tr>
  <td width='125' align='right'>Organismo:</td>
  <td>
	<input type='checkbox' name='chkorganismo' id='chkorganismo' value='1' $obj[0] onclick='enabledOrganismo(this.form);' />
	<select name='forganismo' id='forganismo' class='selectBig' $obj[1] onchange='getFOptions_2(this.id, \"fnanteproyecto\", \"chknanteproyecto\");'>
	  <option value=''></option>";
		getOrganismos($obj[2], 3, $_SESSION[ORGANISMO_ACTUAL]);
		echo "
	</select>
  </td>
  <td width='125' align='right'>Nro. Presupuesto:</td>
  <td>
	<input type='checkbox' name='chknanteproyecto' id='chknanteproyecto' value='1' $obj[3] onclick='enabledNanteproyecto(this.form);' />
	<input type='text' name='fnanteproyecto' size='6' maxlength='3' $obj[4] value='$obj[5]' />
  </td>
 </tr>
 <tr>"; if($_POST[fpreparado]!=''){
          $fpreparado2=$_POST[fpreparado];
        }
    
echo "<td width='125' align='right'>Preparado Por:</td>
  <td><input type='checkbox' name='chkpreparado' value='1' $obj[6] onclick='enabledPreparado(this.form);' />
		<select name='fpreparado' id='fpreparado' class='selectBig' $obj[7]>
		  <option value=''>$fpreparado2</option>";
			getPreparadoPor($obj[9], 0);
		    echo "
		</select>
  </td>
  <td width='125' align='right'>Fecha de Ejercicio:</td>
  <td><input type='checkbox' name='chkejercicio' id='chkejercicio' value='1' $obj[10] onclick='enabledEjercicio(this.form);' />
	  <input type='text' name='fejercicio' id='fejercicio' size='6' maxlength='4' $obj[11] value='$obj[12]' />
  </td>
</tr>
<tr>
		<td width='125' align='right'></td>
		<td>
			
		</td>
		<td width='125' align='right' rowspan='2'>Estado:</td>
		<td>
			<input type='checkbox' name='chkstatus' id='chkstatus' disabled checked value='1' onclick='enabledStatus(this.form);' />
			<select name='fstatus' id='fstatus' class='selectMed' $obj[18]>";
				getStatusAnteproyecto("P", 1);
				echo "
			</select>
		</td>
	</tr>
</table>
</div>
<center><input type='submit' name='btBuscar' value='Buscar'></center>
<br /><div class='divDivision'>Listado de Presupuestos</div><br />
<form/>"; ?>