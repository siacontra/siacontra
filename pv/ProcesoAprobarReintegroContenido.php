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
		<input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'ProcesoVerReintegro2.php', 'BLANK', 'height=500, width=950, left=200, top=200, resizable=no');" /> 
		<!--<input name="btPDF" type="button" class="btLista" id="btPDF" value="PDF" onclick="cargarOpcion(this.form, 'anteproyecto_pdf.php', 'BLANK', 'height=800, width=800, left=200, top=200, resizable=yes');" />--> |  
		<input name="btAprobar" type="button" class="btLista" id="btAprobar" value="Aprobar" onclick="cargarAprobarAjuste(this.form,'ProcesoAprobarReintegro3.php');"/>
		<input name="btAnular" type="button" class="btLista" id="btAnular" value="Anular" onclick="ProcesoAnularReintegros(this.form);" />
	</td>
  </tr>
</table>

<input type="hidden" name="registro" id="registro" />
<table align="center">
<tr>
  <td align="center">
  <div style="overflow:scroll; width:920px; height:300px;">
  
<table width="900" class="tblLista">
  <tr class="trListaHead">
   <th width="50" scope="col"># Presupuesto</th>
   <th width="50" scope="col"># Ajuste</th>
   <th width="50" scope="col"># Partida</th>
   <th width="50" scope="col">Fecha Rintegro</th>
   <th width="50" scope="col">Estado</th>
   <th width="50" scope="col">Total</th>
  </tr>