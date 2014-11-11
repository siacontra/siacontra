<table width="900" class="tblBotones">
<tr>
<td><div id="rows"></div></td>
<td align="center">
<?php 

echo"<input type='hidden' name='regresar' id='regresar' value='anteproyecto_listar'/>";
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
<input name="btNuevo" type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'anteproyecto_datosgenerales.php');" />
<input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" onclick="cargarOpcion(this.form, 'anteproyecto_listareditar.php?accion=EDITAR', 'SELF');"/>
<input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="eliminarAnteproyecto(this.form, 'anteproyecto_listar.php?accion=ELIMINARPROG', '1', 'APLICACIONES');" />
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