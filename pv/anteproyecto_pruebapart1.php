
<table width="900" class="tblBotones">
<tr>
  <td><div id="rows"></div></td>
  <td align="center">
  <?php echo "
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
		<input name="btNuevo" type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'anteproyecto_datosgenerales.php');" />
		<input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" onclick="cargarOpcion(this.form, 'anteproyecto_listareditar.php?accion=EDITAR', 'SELF');"/>
		<input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'anteproyecto_ver2.php', 'BLANK', 'height=500, width=950, left=200, top=200, resizable=no');" /> 
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
	<th width="100" scope="col"># Anteproyecto</th>
	<th scope="col">Elaborado Por</th>
	<th width="135" scope="col">Ejer. Presupuestario</th>
	<th width="100" scope="col">Fecha Inicio</th>	
	<th width="100" scope="col">Fecha Fin</th>
	<th width="85" scope="col">Monto</th>
	<th width="75" scope="col">Estado</th>
  </tr>
<?php
$MAXLIMIT=30;
   $sql="SELECT CodAnteproyecto,PreparadoPor,EjercicioPpto,FechaInicio,FechaFin,MontoPresupuestado,Estado 
	     FROM pv_antepresupuesto 
		WHERE Organismo='".$_SESSION['ORGANISMO_ACTUAL']."' 
		ORDER BY CodAnteproyecto";
   $qry=mysql_query($sql) or die ($sql.mysql_error());
   $registros=mysql_num_rows($qry);
   if($registros!=0){ 
  	for($i=0; $i<$registros; $i++){
  	   $field=mysql_fetch_array($qry);
      echo "
      <tr class='trListaBody' onclick='mClk(this,\"registro\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field['CodAnteproyecto']."'>
	  <td align='center'>".$field['CodAnteproyecto']."</td>
	  <td align='center'>".$field['PreparadoPor']."</td>";
	  list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaInicio']); $fInicio=$d.'-'.$m.'-'.$a;
      list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaFin']); $fFin=$d.'-'.$m.'-'.$a;
      echo"
	  <td align='center'>".$field['EjercicioPpto']."</td>
	  <td align='center'>$fInicio</td>
	  <td align='center'>$fFin</td>
	  <td align='center'>".$field['MontoPresupuestado']."</td>
	  <td align='center'>".$field['Estado']."</td>
      </tr>";
   }}
	$rows=(int)$rows;echo"CantidadRows:".$rows;
	echo "
	<script type='text/javascript' language='javascript'>
		totalAnteproyectos($registros,\"$_INSERT\", \"$_UPDATE\", \"$_DELETE\");
		totalLotes($registros, $rows, ".$limit.");
	</script>
 </table>";	 ?>