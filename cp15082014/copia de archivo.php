<div id="tab2" style="display:none;">
<div style="width:800px; height:15px" class="divFormCaption">Detalle</div>
<form name="frmdetalles" id="frmdetalles">
<input type="hidden" id="seldetalle"/>
<table width="800px" class="tblForm">
<tr>
  <td><input type="checkbox" id="infor_escrito" name="infor_escrito" value="1"/></td>
  <td align="left">Informarme por escrito</td>
  <td><input type="checkbox" id="inv_inforver" name="inv_inforver" value="1"/></td>
  <td align="left">Investigar e informar verbalmente</td>
  <td><input type="checkbox" id="pre_contfirm" name="pre_contfirm" value="1"/></td>
  <td align="left">Preparar contestacion para mi firma</td>
  <td><input type="checkbox" id="conocer_opinion" name="conocer_opinion" value="1"/></td>
  <td align="left">Para conocer su opinion</td>
</tr>
<tr>
  <td><input type="checkbox" id="hablar_alrespecto" name="hablar_alrespecto" value="1"/></td>
  <td align="left">Hablar conmigo al respecto</td>
  <td><input type="checkbox" id="tram_conclusion" name="tram_conclusion" value="1"/></td>
  <td align="left">Tramitar hasta su conclusi&oacute;n</td>
  <td><input type="checkbox" id="archivar" name="archivar" value="1"/></td>
  <td align="left">Archivar</td>
  <td><input type="checkbox" id="tram_casoproceden" name="tram_casoproceden" value="1"/></td>
  <td align="left">Tramitar en caso de proceder</td>
</tr>
<tr>
  <td><input type="checkbox" id="coord_con" name="coord_con" value="1"/></td>
  <td align="left">Coordinar con:<input type="text" id="coord_con2" name="coord_con2" size="30"/></td>
  <td><input type="checkbox" id="distribuir" name="distribuir" value="1"/></td>
  <td align="left">Distribuir</td>
  <td><input type="checkbox" id="registro_de" name="registro_de" value="1"/></td>
  <td align="left">Registro de:<input type="text" id="registro_de2" name="registro_de2" size="30"/></td>
  <td><input type="checkbox" id="acusar_recibo" name="acusar_recibo" value="1"/></td>
  <td align="left">Acusa recibo</td>
</tr>
<tr>
  <td><input type="checkbox" id="pre_memo" name="pre_memo" value="1"/></td>
  <td align="left">Prepara memo a:<input type="text" id="pre_memo2" name="pre_memo2" size="30"/></td>
  <td><input type="checkbox" id="pconocimiento_fp" name="pconocimiento_fp" value="1"/></td>
  <td align="left">Para su conocimiento y fines pertinentes</td>
  <td><input type="checkbox" id="prep_oficio" name="prep_oficio" value="1"/></td>
  <td align="left">Preparar oficio a:<input type="text" id="prep_oficio2" name="prep_oficio2" size="30"/></td>
  <td><input type="checkbox" id="tram_dias" name="tram_dias" value="1"/></td>
  <td align="left">Tramitar en <input type="text" id="tram_dias2" name="tram_dias2" size="2"/> dias</td>
</tr>
<tr>
  <td height="3"></td>
</tr>
<tr>
  <td colspan="8"><div class="cellText" align="center"><b>Enviar a:</b></div></td>
</tr>
<tr>
  <td class="tagForm" colspan="2">Enviar a:</td>
</tr>
<tr>
  <td colspan="8">
  <table width="500" class="tblBotones">
   <tr>
 	<td align="right">
    <input type="button" class="btLista" id="btInsertarItem" value="Insertar" onclick="cargarVentana(this.form, 'lista_empleados.php?limit=0&ventana=insertarDestinatario&tabla=item', 'height=600, width=800, left=50, top=50, resizable=yes');" />
    <input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="quitarLineaDestinatario(document.getElementById('seldetalle').value);" />
	</td>
  </tr>
  </table>
  </td>
</tr>
</table>
<!-- @@@@@@@@@@@@@@@@@@@@@@@@ LISTA A MOSTRAR @@@@@@@@@@@@@@@@@@@@@@@@ -->
<table align="center" cellpadding="0" cellspacing="0">
<tr>
  <td valign="top" style="height:300px; width:400px;">
    <table align="center" width="400px">
    <tr>
      <td align="center"><div style="overflow:scroll; height:300px; width:500px;">
      <table width="500px" class="tblLista">
       <tr>
        <th scope="col" align="left"></th>
        </tr>
       <tbody id="listaDetalles"></tbody>
      </table>
      </div></td>
   </tr>
  </table>
</td>
</tr>
</table>   
</form>
</div>