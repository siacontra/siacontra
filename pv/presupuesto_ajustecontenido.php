<?php 
$sql="SELECT * 
        FROM pv_presupuesto 
	   WHERE (Organismo='".$_POST['forganismo']."' AND PreparadoPor='".$_POST['fpreparado']."') OR 
             (Organismo='".$_POST['forganismo']."' AND CodPresupuesto='".$_POST['fnpresupuesto']."') OR
             (Organismo='".$_POST['forganismo']."' AND EjercicioPpto='".$_POST['fejercicio']."') OR 
			 (Organismo='".$_POST['forganismo']."' AND Estado='".$_POST['fstatus']."')
    ORDER BY CodPresupuesto";			 
$qry=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($qry);
$registros=$rows;
?>
<table width="900" class="tblBotones">
 <tr>
  <td><div id="rows"></div></td>
  <td align="center">
	<?php 
	echo"<input type='hidden' name='registros' id='registros' value='$registros'/>" ;
	
	echo "
	 <table align='center'>
	 <tr>
	  <td>
		<input name='btPrimero' type='button' id='btPrimero' value='&lt;&lt;' disabled onclick='setLotes(this.form, \"P\", $registros, ".$limit.");' />
		<input name='btAtras' type='button' id='btAtras' value='&lt;' disabled onclick='setLotes(this.form, \"A\", $registros, ".$limit.");' />
	  </td>
	  <td>Del</td><td><div id='desde'></div></td>
	  <td>Al</td><td><div id='hasta'></div></td>
	  <td>
		<input name='btSiguiente' type='button' id='btSiguiente' value='&gt;' disabled onclick='setLotes(this.form, \"S\", $registros, ".$limit.");' />
		<input name='btUltimo' type='button' id='btUltimo' value='&gt;&gt;' disabled onclick='setLotes(this.form, \"U\", $registros, ".$limit.");' />
	  </td>
	 </tr>
	</table>";
   ?> 
  </td>
<td align="right">
		<!--<input name="btNuevo" type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'presupuesto_datosgenerales.php');" />
		<input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" onclick="cargarOpcion(this.form, 'anteproyecto_listareditar.php?accion=EDITAR', 'SELF');"/>-->
		<input name="btNuevo" id="btNuevo" type="button" class="btLista" value="Nuevo" onclick="cargarPagina(this.form,'presupuesto_nuevodatosgenerales.php','SELF');"/>
		<input name="btMostrar" type="button" class="btLista" id="btMostrar" value="Ver" onclick="cargarOpcion(this.form, 'presupuesto_datosgenerales.php', 'SELF');"/>
		<!--<input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'anteproyecto_ver2.php', 'BLANK', 'height=500, width=950, left=200, top=200, resizable=no');" /> -->
		<input name="btPDF" type="button" class="btLista" id="btPDF" value="PDF" onclick="cargarOpcion(this.form, 'anteproyecto_pdf.php', 'BLANK', 'height=800, width=800, left=200, top=200, resizable=yes');" /> |  
		<!--<input name="btImprimir" type="button" class="btLista" id="btImprimir" value="Imprimir" onclick="abrirAnteproyecto(this.form);" />-->
		<input name="btAnular" type="button" class="btLista" id="btAnular" value="Anular" onclick="anularAnteproyecto(this.form);" />
		<!--<input name="btAjustar" type="button" class="btLista" id="btAjustar" value="Ajustar" onclick="cargarOpcion(this.form,'anteproyecto_ajustar.php?accion=EDITAR','SELF');"/>-->
	</td>
  </tr>
</table>

<input type="hidden" name="registro" id="registro" />
<table width="900" class="tblLista">
  <tr class="trListaHead">
   <th width="50" scope="col"># Presupuesto</th>
   <th width="50" scope="col"># Ajuste</th>
   <th width="50" scope="col">T. Ajuste</th>
   <th width="50" scope="col">F. Ajuste</th>
   <th width="50" scope="col">Estado</th>
   <th width="50" scope="col">Total</th>
  </tr>
<?php
for($i=0; $i<$rows; $i++){
  	$field=mysql_fetch_array($qry);	
if($field[Estado]==AP){$estado=Aprobado;}
if(($field[Organismo]==$_POST[forganismo])and($field[PreparadoPor]==$_POST[fpreparado])){include "presupuesto_mostrar.php";
}else{
if(($field[Organismo]==$_POST[forganismo])and($field[EjercicioPpto]==$_POST[fejercicio])){include "presupuesto_mostrar.php";
}else{
if(($field[Organismo]==$_POST[forganismo])and($field[Estado]==$_POST[fstatus])){include "presupuesto_mostrar.php";
}else{
if(($field[Organismo]==$_POST[forganismo])and($field[CodPresupuesto]==$_POST[fnpresupuesto])){include "presupuesto_mostrar.php";}	
}	
}
}}
	$rows=(int)$rows;
	
	echo "
	<script type='text/javascript' language='javascript'>
		totalAnteproyectos($registros, \"$_ADMIN\", \"$_INSERT\", \"$_UPDATE\", \"$_DELETE\");
		totalLotes($registros, $rows, ".$limit.");
	</script>";
	
	?>
</table>