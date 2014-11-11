<br>
<DIV align="center">
	<DIV class='TitulosCampos' style="text-align : center; width:97%;">
		<table width="100%">
			<tbody>
				<tr>
					<td width="62%" valign="middle">
						Buscar
						<INPUT id="LISTADO_BUSCAR_FLP" class='TextoCampoInput' type="text" value="" size="35" onkeyup="LISTA_PERSONA__Buscar();" onkeypress="LISTA_PERSONA__PresionarEnter(event)"><IMG class='BotonesParaCampos' src='img/iconos/limpiar_activo.png' onmouseover="src='img/iconos/limpiar_con_foco.png';" onmouseout="src='img/iconos/limpiar_activo.png'" width='20' height='20' onclick="Form_LISTA_PROVEEDOR__LimpiarInputTextBuscarListado();">
					</td>
					<td width="38%" valign="middle" style="font-size : 12px;">
						<INPUT id="SOMBRA_CHECKBOX_FLP" type="checkbox" checked="true">Sombrear al buscar<br>
						<INPUT id="BUSCAR_CHECKBOX_FLP" type="checkbox">Sólo buscar al presionar enter
					</td>
				</tr>
			</tbody>
		</table>
		<br>
		<table border="0" cellspacing="0" cellpadding="0" width="100%">
			<tbody>
				<tr class="CabeceraTablaEstilo">
					<td width="30%">CÉDULA</td>
					<td width="70%">NOMBRES Y APELLIDOS</td>
					<!--td width="45%">APELLIDO</td-->
				</tr>
			</tbody>
		</table>
		<DIV id="DIV_TABLA_LISTA_FLP" class="AreaTablaListado" style="height : 140px;">
			<table id="TABLA_LISTA_FLP" border="0" cellspacing="0" cellpadding="0" width="99%">
			</table>
		</DIV>
		<br>
		<table width="100%">
			<tbody>
				<tr>
					<td id="MSG_CARGANDO_FLP" width="60%" valign="top">
					</td>
					<td width="40%" valign="top">
						<DIV class='TitulosCampos' style="text-align : center;">
							<!--BUTTON class="BotonesParaCampos" style="font-size : 14px; vertical-align : top;" onmouseover="IMG_ACEPTAR_FLP.src='img/iconos/aceptar_con_foco.png';" onmouseout="IMG_ACEPTAR_FLP.src='img/iconos/aceptar_activo.png'" onclick="LISTA_PERSONA__Aceptar();">
								<IMG id="IMG_ACEPTAR_FLP" src='img/iconos/aceptar_activo.png' width='22' height='22' style="vertical-align : middle;">&nbsp;Aceptar
							</BUTTON-->
							
							<input type="button" value="Aceptar" onClick="LISTA_PERSONA__Aceptar();"></input>	
							&nbsp;&nbsp;&nbsp;&nbsp;
							<!--BUTTON class="BotonesParaCampos" style="font-size : 14px; vertical-align : top;" onmouseover="IMG_CANCELAR_FLP.src='img/iconos/cancelar_con_foco.png';" onmouseout="IMG_CANCELAR_FLP.src='img/iconos/cancelar_activo.png'" onclick="VentanaCerrar('VENTANA_LISTA_PERSONA');">
								<IMG id="IMG_CANCELAR_FLP" src='img/iconos/cancelar_activo.png' width='22' height='22' style="vertical-align : middle;">&nbsp;Cancelar
							</BUTTON-->
							<input type="button" value="Cancelar" onClick="VentanaCerrar('VENTANA_LISTA_PERSONA');"></input>
							
							
						</DIV>
					</td>
				</tr>
			</tbody>
		</table>
	</DIV>
</DIV>
