<? echo "
<form name='frmentrada' action='anteproyecto_generar.php' method='POST'>";
//////////  ORGANISMO //////////
if($_POST['chkorganismo']=="1"){ 
   $obj[0]="checked"; $obj[1]="enabled"; $obj[2]=$_POST['forganismo']; 
}else{ 
   $obj[0]="checked"; $obj[1]="enabled"; $obj[2]=$_POST['forganismo']; 
}
////////////////////////////////
echo"<input type='hidden' name='limit' id='limit' value='".$limit."'>
     <input type='hidden' name='registros' id='registros' value='".$registros."'/>

<div class='divBorder' style='width:900px;'>
<table width='900' class='tblFiltro'>
	<tr>
		<td width='125' align='right'>Organismo:</td>
		<td>
			<input type='checkbox' name='chkorganismo' id='chkorganismo' value='1' $corganismo onclick='this.checked=true' />
			<select name='forganismo' id='forganismo' class='selectBig' $dorganismo onchange='getFOptions_2(this.id, \"fanteproyecto\", \"chknanteproyecto\");'>";
				getOrganismos($obj[2], 3, $_SESSION[ORGANISMO_ACTUAL]);
				echo "
			</select>
		</td>
		<td width='125' align='right'>Nro. Anteproyecto:</td>
  <td>
	<input type='checkbox' name='chknanteproyecto' id='chknanteproyecto' value='1' $cnproyecto onclick='enabledNanteproyecto(this.form);' />
	<input type='text' name='fnanteproyecto' size='6' maxlength='4' $dnproyecto value='$fnanteproyecto' />
  </td>
	</tr>
	<tr>
		<td width='125' align='right'>Preparado Por:</td>
		<td>
			<input type='checkbox' name='chkpreparado' value='1' $cpreparado onclick='enabledPreparado(this.form);' />
			<select name='fpreparado' id='fpreparado' class='selectBig' $dpreparado>
			    <option value=''></option>";
				getPreparadoPor("$fpreparado", 0);
				echo "
			</select>
		</td>
		<td width='125' align='right'>Fecha de Ejercicio:</td>
		<td>
			<input type='checkbox' name='chkejercicio' value='1' $cejercicio onclick='enabledEjercicio(this.form);' />
			<input type='text' name='fejercicio' id='fejercicio'size='6' maxlength='4' $dejercicio value='$fejercicio'/>
		</td>
	</tr>
	<tr>
		<td width='125' align='right'></td>
		<td>
			
		</td>
		<td width='125' align='right' rowspan='2'>Estado:</td>
		<td>
			<input type='checkbox' name='chkstatus' value='1' checked  onclick='this.checked=true' />
			<select name='fstatus' id='fstatus' class='selectMed'>";
				getStatusAnteproyecto("AP", 1);
				echo "
			</select>
		</td>
	</tr>
</table>
</div>
<center><input type='submit' name='btBuscar' value='Buscar'></center>
<br /><div class='divDivision'>Listado de Anteproyectos</div><br />";
?>