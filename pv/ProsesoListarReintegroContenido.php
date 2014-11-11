<table width="900" class="tblBotones">
 <tr>
  <td><div id="rows"></div></td>
  <td align="center">
	<?php 
	echo"<input type='hidden' name='registros' id='registros' value='$registros'/>
	<input type='hidden' name='regresar' id='regresar' value='ProcesoListarReintegro'/>" ;
	
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
<input name="btMostrar" type="button" class="btLista" id="btMostrar" value="Nuevo" onclick="cargarPagina(this.form,'ProcesoNuevoReintegro.php');"/>
<input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" onclick="cargarOpcion(this.form,'ProcesoEditarReintegro.php?accion=EDITAR', 'SELF');" />
<input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="eliminarReintegro(this.form, 'ProcesoListarReintegro.php?accion=ELIMINARPROG', '1', 'APLICACIONES');" />
<input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'ProcesoVerReintegro.php', 'BLANK', 'height=500, width=950, left=200, top=200, resizable=no');" /> 
<!--<input name="btPDF" type="button" class="btLista" id="btPDF" value="PDF" onclick="cargarOpcion(this.form, 'presupuesto_ajustepdf.php', 'BLANK', 'height=800, width=800, left=200, top=200, resizable=yes');" />-->
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
   <th width="30" scope="col"># Presupuesto</th>
   <th width="35" scope="col"># Reintegro</th>
   <th width="50" scope="col">Fecha Reintegro</th>
   <th width="260" scope="col">Descripcion</th>
   <th width="50" scope="col">Estado</th>
   <th width="50" scope="col">Total</th>
  </tr>
