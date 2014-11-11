<?php 
include ("fphp.php");
//	--------------------------
if ($accion=="FECHA_FIN") getFechaFin($fecha, $dias); 

elseif ($accion=="MOSTRAR-CATEGORIA-SUELDO") {
	connect();
	$sql="SELECT rp.NivelSalarial, mmd.Descripcion AS TipoTrabajador FROM rh_puestos rp INNER JOIN mastmiscelaneosdet mmd ON (rp.CategoriaCargo=mmd.CodDetalle AND mmd.CodMaestro='CATCARGO') WHERE rp.CodCargo='".$codcargo."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query)!=0) {
		$field=mysql_fetch_array($query);
		echo "0|:|".$field["TipoTrabajador"]."|:|".number_format($field['NivelSalarial'], 2, ',', '.');
	}
}

elseif ($accion=="SET-TIPO-CONTRATOS") {
	connect();
	$sql="SELECT rtc.FlagVencimiento, rfc.CodFormato, rfc.Documento FROM rh_tipocontrato rtc INNER JOIN rh_formatocontrato rfc ON (rtc.TipoContrato=rfc.TipoContrato) WHERE rtc.TipoContrato='".$tipo."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query)!=0) {
		$field=mysql_fetch_array($query);
		echo "0|:|".$field["FlagVencimiento"]."|:|".$field["CodFormato"]."|:|".htmlentities($field["Documento"]);
	}
}

elseif ($accion=="GET-TOTAL-DIAS-PERMISOS") {
	connect();
	// Obtengo los dias de permiso...
	$dias_feriado = getDiasFeriados($fdesde, $fhasta);
	$dias_permiso = getDiasPermiso($fdesde, $fhasta); 
	$dias_permiso = $dias_permiso - $dias_feriado;
		
	//if ($dias_permiso == 0) {
		//echo "|:||:||:||:||:|";
	//} else {
		//	Obtengo las horas diarias...
		list($hd, $md) = SPLIT( '[:.:]', $hdesde);
		list($hh, $mh) = SPLIT( '[:.:]', $hhasta);
		$hd = (int) $hd;
		$hh = (int) $hh;
		$md = (int) $md;
		$mh = (int) $mh;
		if ($turnodesde == "PM" && $hd != 12) $hd += 12;
		if ($turnohasta == "PM" && $hh != 12) $hh += 12;
		if ($turnodesde == "AM" && $hd == 12) $hd = 0;
		if ($turnohasta == "AM" && $hh == 12) $hh = 0;
		
		if ($hd <= 12 && $hh >= 13) $restar = -1; else $restar = 0;
		
		$horas = $hh - $hd + $restar;
		
		// Obtengo los minutos diarios...
		if ($mh >= $md) $minutos = ($mh - $md); else $minutos = ($mh - $md) + 60;
		if ($md > $mh) $horas--;
			
		//	Totales...
		if ($dias_permiso == 0) 
			$total_minutos = $minutos * 1; 
		else
			$total_minutos = $minutos * $dias_permiso; 
		
		if ($total_minutos >= 60) $minutos_a_horas = (int) ($total_minutos / 60);
		$total_minutos = $total_minutos - ($minutos_a_horas * 60);
		
		if ($dias_permiso == 0) 
			$total_horas = ($horas * 1) + $minutos_a_horas;
		else
			$total_horas = ($horas * $dias_permiso) + $minutos_a_horas;
		
		$thora = "$horas:$minutos";
		$tfecha = $dias_permiso;
		
		if ($fdesde == $fhasta) { $dias_permiso = ""; $tfecha = ""; }
		if ($horas == 0 && $minutos == 0) { $total_horas = ""; $total_minutos = ""; $thora = ""; }
		
		//	Cambiar esta linea en funcion de las horas diarias de trabajo... 5 o 7 horas dependiendo
		if ($dias_permiso == "" && $horas >= 7) { $dias_permiso = 1; $tfecha = 1; }
		  
		//echo "1|:|2|:|3|:|4|:|5";
		echo "$dias_permiso|:|$total_horas|:|$total_minutos|:|$tfecha|:|$thora";
	//}
}
	
//	--------------------------
elseif ($accion == "insertarEvaluacionFunciones") {
	connect();
		
	echo "||";
	/*<tr class="trListaBody" onclick="mClk(this, 'seldetalle_tab3');" id="<?=$codigo?>">*/
	?>
    <td><input type="text" name="txtfuncion_tab3" style="width:99%" /></td>
    <td>
    	<input type="hidden" id="txtcodcalificativo_tab3_<?=$nrodetalles?>" />
    	<input type="text" name="txtcalificacion_tab3" id="txtnomcalificativo_tab3_<?=$nrodetalles?>" style="width:99%; text-align:center;" disabled="disabled" />
	</td>
    <td><input type="text" name="txtpeso_tab3" id="txtpeso_tab3_<?=$nrodetalles?>" style="width:99%; text-align:right;" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" /></td>
	<?
}
	
//	--------------------------
elseif ($accion == "insertarEvaluacionObjetivosMetas") {
	connect();
		
	echo "||";
	?>
    <td><input type="text" name="txtdescripcion_tab5" maxlength="100" style="width:99%" onchange="setDescripcionMeta(this.value, '<?=$nrodetalles?>');" /></td>
    <td><input type="text" name="txtperiodo_tab5" maxlength="7" style="width:99%; text-align:center;" /></td>
    <td><input type="text" name="txtcomentarios_tab5" maxlength="100" style="width:99%" /></td>
    <td><input type="text" name="txtfdesde_tab5" maxlength="10" style="width:99%; text-align:center;" /></td>
    <td><input type="text" name="txtfhasta_tab5" maxlength="10" style="width:99%; text-align:center;" /></td>
    <td>
    	<input type="hidden" id="txtcodcalificativo_tab5_<?=$nrodetalles?>" />
    	<input type="text" name="txtcalificacion_tab5" id="txtnomcalificativo_tab5_<?=$nrodetalles?>" style="width:99%; text-align:center;" disabled="disabled" />
	</td>
    <td><input type="text" name="txtpeso_tab5" id="txtpeso_tab5_<?=$nrodetalles?>" style="width:99%; text-align:right;" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" onchange="setTotalesCalificacionPeso(<?=$nrodetalles?>);" /></td>
    <td align="right"><span id="total_tab5_<?=$nrodetalles?>">0,00</span></td>
	<?
	
	echo "||";
	?>
    <td><span id="meta_tab7_<?=$nrodetalles?>"></span></td>
    <td><input type="text" name="txtfecha1_tab7" maxlength="10" style="width:99%; text-align:center;" /></td>
    <td><input type="text" name="txtporcentaje1_tab7" value="0,00" style="width:99%; text-align:right;" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" /></td>
    <td><input type="text" name="txtobservacion1_tab7" style="width:99%;" /></td>
    <td><input type="text" name="txtfecha2_tab7" maxlength="10" style="width:99%; text-align:center;" /></td>
    <td><input type="text" name="txtporcentaje2_tab7" value="0,00" style="width:99%; text-align:right;" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" /></td>
    <td><input type="text" name="txtobservacion2_tab7" style="width:99%;" /></td>
    <td><input type="text" name="txtfecha3_tab7" maxlength="10" style="width:99%; text-align:center;" /></td>
    <td><input type="text" name="txtporcentaje3_tab7" value="0,00" style="width:99%; text-align:right;" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" /></td>
    <td><input type="text" name="txtobservacion3_tab7" style="width:99%;" /></td>
	<?
}
	
//	--------------------------
elseif ($accion == "insertarEvaluacionFortaleza") {
	connect();
		
	echo "||";
	?>
    <td>
    	<select name="slttipo_tab4" style="width:99%;">
            <option value=""></option>
            <?=getTipoFD("", 0);?>
        </select>
    </td>
    <td><input type="text" name="txtdescripcion_tab4" maxlength="255" style="width:99%" /></td>
	<?
}
	
//	--------------------------
elseif ($accion == "insertarEvaluacionNecesidad") {
	connect();
		
	echo "||";
	?>
    <td><input type="text" name="txtnecesidad_tab6" maxlength="100" value="<?=htmlentities($field_necesidad['Necesidad'])?>" style="width:99%" /></td>
    <td>
        <select name="sltprioridad_tab6" style="width:99%;">
            <option value=""></option>
            <?=loadSelectValores("PRIORIDAD", $field_necesidad['Prioridad'], 0);?>
        </select>
    </td>
    <td>
        <input type="hidden" name="txtcodcurso_tab6" id="txtcodcurso_tab6_<?=$nrodetalles?>" value="<?=$field_necesidad['CodCurso']?>" />
        <input type="text" id="txtnomcurso_tab6_<?=$nrodetalles?>" value="<?=htmlentities($field_necesidad['NomCurso'])?>" style="width:99%;" disabled="disabled" />
	</td>
    <td><input type="text" name="txtobjetivo_tab6" maxlength="100" value="<?=htmlentities($field_necesidad['Objetivo'])?>" style="width:99%" /></td>
	<?
}
	
//	--------------------------
elseif ($accion == "mostrarIncidentesCriticos") {
	connect();
		
	echo "||";
	
	$sql = "(SELECT mf.Secuencia, mf.Documento, mf.FechaDoc, mf.Observacion, md.Descripcion AS Clasificacion, 'Mérito' AS Tipo FROM rh_meritosfaltas mf LEFT JOIN mastmiscelaneosdet md ON (mf.Clasificacion=md.CodDetalle AND CodMaestro='MERITO') WHERE mf.CodPersona='".$codpersona."' AND mf.Tipo='M') UNION (SELECT mf.Secuencia, mf.Documento, mf.FechaDoc, mf.Observacion, md.Descripcion AS Clasificacion, 'Demérito' AS Tipo FROM rh_meritosfaltas mf LEFT JOIN mastmiscelaneosdet md ON (mf.Clasificacion=md.CodDetalle AND CodMaestro='DEMERITO') WHERE mf.CodPersona='".$codpersona."' AND mf.Tipo='D') ORDER BY Secuencia";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	while ($field = mysql_fetch_array($query)) {
		
		?>
		<tr class="trListaBody">
            <td><?=htmlentities($field['Tipo'])?></td>
            <td align="center"><?=formatFechaDMA($field['FechaDoc'])?></td>
            <td><?=htmlentities($field["Documento"])?></td>
            <td><?=$field["Clasificacion"]?></td>
            <td><?=htmlentities($field["Observacion"])?></td>
		</tr>
	<? }
		
	echo "||";
	
	$sql = "SELECT me.CodPersona, cf.*
			FROM 
				mastempleado me
				INNER JOIN rh_cargofunciones cf ON (me.CodCargo = cf.CodCargo)
			WHERE me.CodPersona = '".$codpersona."'";
	$query = mysql_query($sql) or die ($sql.mysql_error()); $nrodetalles=0;
	while ($field = mysql_fetch_array($query)) { $nrodetalles++;
		?>
		<tr class="trListaBody">
            <td>
            	<input type="hidden" name="txtfuncion_tab3" value="<?=htmlentities($field['Descripcion'])?>" />
                <input type="hidden" id="txtcodcalificativo_tab3_<?=$nrodetalles?>" />
                <input type="hidden" name="txtcalificacion_tab3" id="txtnomcalificativo_tab3_<?=$nrodetalles?>" value="<?=number_format($field['Calificacion'], 2, ',', '.')?>" />
                <input type="hidden" name="txtpeso_tab3" id="txtpeso_tab3_<?=$nrodetalles?>" value="<?=number_format($field['Peso'], 2, ',', '.')?>" />
				<?=htmlentities($field['Descripcion'])?>
			</td>
		</tr>
	<? }
		
	echo "||";

	$sql = "SELECT
				ef.Descripcion AS Factor,
				ef.TipoCompetencia,
				ef.Area,
				mmd.Descripcion AS NomTipoCompetencia,
				ea.Descripcion AS NomArea,
				fvp.Plantilla,
				fvp.Competencia
			FROM
				mastempleado me
				INNER JOIN rh_puestos rp ON (me.CodCargo = rp.CodCargo)
				INNER JOIN rh_factorvalorplantilla fvp ON (rp.Plantilla = fvp.Plantilla)
				INNER JOIN rh_evaluacionfactores ef ON (fvp.Competencia = ef.Competencia)
				INNER JOIN mastmiscelaneosdet mmd ON (ef.TipoCompetencia = mmd.CodDetalle AND mmd.CodMaestro = 'TIPOCOMPE')
				INNER JOIN rh_evaluacionarea ea ON (ef.Area = ea.Area)
			WHERE
				me.CodPersona = '".$codpersona."'
			ORDER BY TipoCompetencia, Area, Competencia";
	$query_desempeno = mysql_query($sql) or die ($sql.mysql_error()); $nrodetalles=0;
	while ($field_desempeno = mysql_fetch_array($query_desempeno)) { $nrodetalles++;
		$idagrupa = $field_desempeno['TipoCompetencia']."-".$field_desempeno['Area'];
		
		if ($agrupa != $idagrupa) {			
			if ($nrodetalles != 1) {
				?>
				<tr>
					<td align="center">&nbsp;</td>
					<td align="center">&nbsp;</td>
					<td>
						<input type="text" name="calificacion_sub_total_tab2" id="calificacion_<?=$agrupa?>_tab2" value="<?=number_format($sub_total_calificacion, 2, ',', '.')?>" style="width:99%; text-align:right; font-size:11px; font-weight:bold; display:none;" disabled="disabled" />
					</td>
					<td>
						<input type="text" name="peso_sub_total_tab2" id="peso_<?=$agrupa?>_tab2" value="<?=number_format($sub_total_peso, 2, ',', '.')?>" style="width:99%; text-align:right; font-size:11px; font-weight:bold; display:none;" disabled="disabled" />
					</td>
					<td>
						<input type="text" name="subtotal_tab2" id="subtotal_<?=$agrupa?>_tab2" value="<?=number_format($subtotal, 2, ',', '.')?>" style="width:99%; text-align:right; font-size:11px; font-weight:bold;" disabled="disabled" />
					</td>
				</tr>
				<?
			}
			
			?>
			<tr class="trListaBody2">
				<td align="center">&nbsp;</td>
				<td colspan="5"><strong><?=htmlentities($field_desempeno["NomTipoCompetencia"]." - ".$field_desempeno['NomArea'])?></strong></td>
			</tr>
			<?
			$agrupa = $idagrupa;
		}
		?>
		<tr class="trListaBody" onclick="mClk(this, 'seldetalle_tab2');" id="tab2_<?=$nrodetalles?>">
			<td align="center"><?=$nrodetalles?></td>
			<td>
            	<input type="hidden" name="txtplantilla_tab2" value="<?=$field_desempeno['Plantilla']?>" />
            	<input type="hidden" name="txtcompetencia_tab2" value="<?=$field_desempeno['Competencia']?>" />
				<?=htmlentities($field_desempeno["Factor"])?>
			</td>
			<td>
            	<input type="hidden" id="txtcodcalificativo_tab2_<?=$nrodetalles?>" value="<?=htmlentities($field_desempeno["NomCalificacion"])?>" />
				<input type="text" name="txtcalificacion_tab2" id="txtnomcalificativo_tab2_<?=$nrodetalles?>" value="<?=number_format($field_desempeno['Calificacion'], 2, ',', '.')?>" style="width:99%; text-align:right;" class="calificacion_<?=$idagrupa?>_tab2" disabled="disabled" />
			</td>
			<td><input type="text" name="txtpeso_tab2" id="txtpeso_tab2_<?=$nrodetalles?>" value="<?=number_format($field_desempeno['Peso'], 2, ',', '.')?>" style="width:99%; text-align:right;" onchange="setTotalesCalificacionPesoDesempeno(<?=$nrodetalles?>);" class="peso_<?=$agrupa?>_tab2" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" /></td>            
			<td align="right"><span id="total_tab2_<?=$nrodetalles?>"><?=number_format(($field_desempeno['Calificacion']*$field_desempeno['Peso']), 2, ',', '.')?></span></td>
		</tr>
	<? } ?>
	<tr>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
		<td>
			<input type="text" name="calificacion_sub_total_tab2" id="calificacion_<?=$idagrupa?>_tab2" value="<?=number_format($sub_total_calificacion, 2, ',', '.')?>" style="width:99%; text-align:right; font-size:11px; font-weight:bold; display:none;" disabled="disabled" />
		</td>
		<td>
			<input type="text" name="peso_sub_total_tab2" id="peso_<?=$agrupa?>_tab2" value="<?=number_format($sub_total_peso, 2, ',', '.')?>" style="width:99%; text-align:right; font-size:11px; font-weight:bold; display:none;" disabled="disabled" />
		</td>
		<td>
			<input type="text" name="subtotal_tab2" id="subtotal_<?=$agrupa?>_tab2" value="<?=number_format($subtotal, 2, ',', '.')?>" style="width:99%; text-align:right; font-size:11px; font-weight:bold;" disabled="disabled" />
		</td>
	</tr>    
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
		<td>
			<input type="text" id="calificacion_total_tab2" value="<?=number_format($calificacion_total, 2, ',', '.')?>" style="width:99%; text-align:right; font-size:11px; font-weight:bold; display:none;" disabled="disabled" />
		</td>
		<td>
			<input type="text" id="peso_total_tab2" value="<?=number_format($peso_total, 2, ',', '.')?>" style="width:99%; text-align:right; font-size:11px; font-weight:bold; display:none;" disabled="disabled" />
		</td>
		<td>
			<input type="text" id="total_tab2" value="<?=number_format($total, 2, ',', '.')?>" style="width:99%; text-align:right; font-size:11px; font-weight:bold;" disabled="disabled" />
		</td>
	</tr>
	<?
	
	echo "||";
	
	$sql="SELECT MIN(PuntajeMin) AS Min, MAX(PuntajeMax) AS Max FROM rh_gradoscompetencia";
	$query_puntaje=mysql_query($sql) or die ($sql.mysql_error());
	$field_puntaje=mysql_fetch_array($query_puntaje);
	?>
	
	<!--	Imprimo los titulos y la barra de puntutacion	-->
	<tr style="background-color:#666666">
    	<th>
			<?php
            $sql="SELECT * FROM rh_gradoscompetencia ORDER BY Grado";
            $query_grado=mysql_query($sql) or die ($sql.mysql_error());
            $rows_grado=mysql_num_rows($query_grado);
            for ($k=0; $k<$rows_grado; $k++) {
                $field_grado=mysql_fetch_array($query_grado);
                $min[$k]=$field_grado['PuntajeMin'];
                $max[$k]=$field_grado['PuntajeMax'];
                $col[$k]=$field_grado['PuntajeMax']-$field_grado['PuntajeMin']+1;
                $grado[$k]=$field_grado['Descripcion'];
            }
            echo "<table class='grillaTable'>";
            
            echo "<tr class='grillaTr'>";
            for ($k=0; $k<$rows_grado; $k++) echo "<td align='center' class='grillaTd' colspan='".$col[$k]."' style='font-size:8px;'><em>".$grado[$k]."</em></td>";
            echo "</tr>";
            
            echo "<tr class='grillaTr'>";
            for ($k=0; $k<$rows_grado; $k++) {
                for ($j=$min[$k]; $j<=$max[$k]; $j++) echo "<td align='center' class='grillaTd' width='20%' style='font-size:9px;'><b>".$j."</b></td>";
            }
            echo "</tr>";
			
			echo "</table>";
            ?>
    	</th>
    </tr>
    
	<?php
	$k=0;
	$sql = "SELECT
				cc.Competencia,
				ef.Descripcion AS NomCompetencia,
				ef.TipoCompetencia,
				ef.Area,
				ef.Descripcion AS Factor,
				ef.ValorRequerido, 
				ef.ValorMinimo,
				ea.Descripcion AS NomArea,
				mmd.Descripcion AS NomTipoCompetencia
			FROM
				mastempleado me
				INNER JOIN rh_cargocompetencia cc ON (me.CodCargo = cc.CodCargo)
				INNER JOIN rh_evaluacionfactores ef ON (cc.Competencia = ef.Competencia)
				INNER JOIN rh_evaluacionarea ea ON (ef.Area = ea.Area)
				INNER JOIN mastmiscelaneosdet mmd ON (ef.TipoCompetencia = mmd.CodDetalle AND mmd.CodMaestro = 'TIPOCOMPE')
			WHERE
				me.CodPersona = '".$codpersona."'
			ORDER BY TipoCompetencia, Area, Competencia";
	$query_grafico = mysql_query($sql) or die ($sql.mysql_error());
	$rows_grafico = mysql_num_rows($query_grafico);
	while($field_grafico = mysql_fetch_array($query_grafico)) {
		$l++;
		$det_grupo=$field_grafico['TipoCompetencia']." - ".$field_grafico['Area'];
		$nom_grupo=$field_grafico['NomTipoCompetencia']." - ".$field_grafico['NomArea'];
		if ($grupo!=$det_grupo) {
			$grupo=$det_grupo;
			echo "<tr class='trListaBody2'><td>".htmlentities($nom_grupo)."</td></tr>";
		} 
		?>
		<tr>
			<td>
            	<?
				echo "<table class='grillaTable' width='100%' cellpadding='0' cellspacing='0'>";
					echo "<tr class='grillaTr'>";
					echo "<td>".htmlentities($field_grafico['NomCompetencia'])."</td>";
					echo "</tr>";
				echo"</table>";
				
				echo "<table width='100%' cellpadding='0' cellspacing='0'>";
					$sql="SELECT ValorRequerido, ValorMinimo FROM rh_evaluacionfactores WHERE Competencia='".$field_grafico['Competencia']."'";
					$query_valor=mysql_query($sql) or die ($sql.mysql_error());
					$rows_valor=mysql_num_rows($query_valor);
					if ($rows_valor!=0) $field_valor=mysql_fetch_array($query_valor);
				
					echo "<tr>";
					for ($k=0; $k<$rows_grado; $k++) {
						for ($j=$min[$k]; $j<=$max[$k]; $j++) {
							if ($j <= $field_valor['ValorRequerido'])
								echo "<td align='center' width='20%' style='font-size:10px; height:4px; background:#000;' id='R_".$j."_".$l."'>&nbsp;</td>";
							else
								echo "<td align='center' width='20%' style='font-size:10px; height:4px;' id='R_".$j."_".$l."'>&nbsp;</td>";
						}
					}
					echo "</tr>";
					
					echo "<tr>";
					for ($k=0; $k<$rows_grado; $k++) {
						for ($j=$min[$k]; $j<=$max[$k]; $j++) {
							if ($j <= $field_valor['ValorMinimo'])
								echo "<td align='center' width='20%' style='font-size:10px; height:4px; background:#990000;' id='M_".$j."_".$l."'>&nbsp;</td>";
							else
								echo "<td align='center' width='20%' style='font-size:10px; height:4px;' id='M_".$j."_".$l."'>&nbsp;</td>";
						}
					}
					echo "</tr>";
					
					echo "<tr>";
					for ($k=0; $k<$rows_grado; $k++) {
						for ($j=$min[$k]; $j<=$max[$k]; $j++) echo "<td align='center' width='20%' style='font-size:10px; height:4px;' id='P_".$j."_".$l."'>&nbsp;</td>";
					}
					echo "</tr>";
				echo"</table>";
				?>
            </td>
		</tr>
        <?
	}
} 

elseif ($accion == "selConceptoRetencion") {
	connect();
	
	$detalle = split(";", $detalles);
	foreach ($detalle as $registro) {
		if ($codconcepto == $registro) die("¡No se puede insertar dos veces el mismo concepto!");
	}
	
	//	si nosencontraron errores inserta en la tabla los datos del proveedor
	echo "||";
	?>
    <td align="center">
    	<input type="hidden" name="txtcodconcepto" value="<?=$codconcepto?>" />
		<?=$codconcepto?>
	</td>
    <td><?=htmlentities($nomconcepto)?></td>
    <td><input type="text" name="txtporcentaje" value="0,00" style="width:98%; text-align:right;" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" /></td>
    <td><input type="text" name="txtmonto" value="0,00" style="width:98%; text-align:right;" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" /></td>
	<?
	
}



?>