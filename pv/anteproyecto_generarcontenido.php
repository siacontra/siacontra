<table width="900" class="tblBotones">
<tr>
<td><div id="rows"></div></td>
<td align="center">
<?php 
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
	<input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'anteproyecto_ver2.php', 'BLANK', 'height=550, width=850, left=200, top=100, resizable=no');" />
	<input name="btPDF" type="button" class="btLista" id="btPDF" value="PDF" onclick="cargarOpcion(this.form, 'anteproyecto_pdf.php','BLANK', 'height=800, width=800, left=200, top=200, resizable=yes');" />  | 
	<input name="btGenerar" type="button" class="btLista" id="btGenerar" value="Generar" onclick="cargarGenerar(this.form,'anteproyecto_generar3.php');"/>
	<input name="btRechazar" type="button" class="btLista" id="btRechazar" value="Anular" onclick="anularAnteproyectoRevisado(this.form);" />
	</td>
  </tr>
</table>

<input type="hidden" name="registro" id="registro" />
<table width="900" class="tblLista">
  <tr class="trListaHead">
	<th width="100" scope="col"># Anteproyecto</th>
	<th width="col" scope="col">Elaborado Por</th>
	<th width="135" scope="col">Ejer. Presupuestario</th>
	<th width="100" scope="col">Fecha Inicio</th>	
	<th width="100" scope="col">Fecha Fin</th>	
	<th width="85" scope="col">Monto</th>
	<th width="75" scope="col">Estado</th>
  </tr>
