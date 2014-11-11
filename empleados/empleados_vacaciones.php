<?php



function ANIO_SERVICIO_OTRA_INSTITUCION($_ARGS)
{

	$sql = "SELECT *
		FROM rh_empleado_experiencia AS ex
		JOIN mastempleado AS me ON me.CodPersona = ex.CodPersona
		WHERE me.CodEmpleado = '".$_ARGS."' and ex.TipoEnte = '02'";

		/*SELECT *
		FROM rh_empleado_experiencia
		WHERE CodPersona = '".$_ARGS['TRABAJADOR']."'
		AND TipoEnte = '02'"*/


	$query = mysql_query($sql) or die ($sql.mysql_error());

	$totalAnio = 0;
	$sumaAnio = 0;//$_ARGS['TRABAJADOR'];
	$sumaMes = 0;
	$totalMese = 0;
	$sumaDias = 0;
	$totalDias = 0;
	$auxAnioMes = 0;
	$auxAnioDia = 0;

	while ($field_a = mysql_fetch_array($query)) {

		$vectorIngreso = split('-',$field_a['FechaDesde']);
		$vectorEgreso = split('-',$field_a['FechaHasta']);

		list($anioIngreso,$mesIngreso,$diaIngreso) = $vectorIngreso;
		list($anioEgreso,$mesEgreso,$diaEgreso) = $vectorEgreso;

		$totalAnio = $anioEgreso - $anioIngreso;
		$totalMese = $mesEgreso - $mesIngreso;
		$totalDias = $diaEgreso - $diaIngreso;

		$sumaMes += $totalMese;
		$sumaDias += $totalDias;
		$sumaAnio+=$totalAnio;


	}

	$auxAnioDia = $sumaDias/30;
	$sumaMes += $auxAnioDia;
	$auxAnioMes = $sumaMes/12;
    $sumaAnio+$auxAnioMes;

	return $sumaAnio+$auxAnioMes;


}

//	------------------------------------
list($AnioActual, $MesActual, $DiaActual) = split("[/.-]", substr($Ahora, 0, 10));
//	------------------------------------
//	datos del empleado
$sql = "SELECT
			p.CodPersona,
			p.NomCompleto,
			e.CodEmpleado,
			e.Fingreso,
			e.CodTipoNom,
			pt.Grado
		FROM
			mastpersonas p
			INNER JOIN mastempleado e ON (e.CodPersona = p.CodPersona)
			INNER JOIN rh_puestos pt ON (pt.CodCargo = pt.CodCargo)
		WHERE p.CodPersona = '".$registro."'";
$query_empleado = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
if (mysql_num_rows($query_empleado)) $field_empleado = mysql_fetch_array($query_empleado);
list($AnioIngreso, $MesIngreso, $DiaIngreso) = split("[/.-]", $field_empleado['Fingreso']);
if ($field_empleado['Grado'] == "99") $DiasPago = $_PARAMETRO["PAGOFINDC"]; else $DiasPago = $_PARAMETRO["PAGOVACA"];
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Vacaciones del Empleado</td>
		<td align="right"><a class="cerrar" href="#" onclick="$('#frmentrada').attr('action', 'gehen.php?anz=empleados_lista'); document.getElementById('frmentrada').submit()">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=empleados_vacaciones" method="POST">
<input type="hidden" name="_APLICACION" id="_APLICACION" value="<?=$_APLICACION?>" />
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />

<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="fCodOrganismo" id="fCodOrganismo" value="<?=$fCodOrganismo?>" />
<input type="hidden" name="fCodDependencia" id="fCodDependencia" value="<?=$fCodDependencia?>" />
<input type="hidden" name="fCodCentroCosto" id="fCodCentroCosto" value="<?=$fCodCentroCosto?>" />
<input type="hidden" name="fCodTipoNom" id="fCodTipoNom" value="<?=$fCodTipoNom?>" />
<input type="hidden" name="fCodTipoTrabajador" id="fCodTipoTrabajador" value="<?=$fCodTipoTrabajador?>" />
<input type="hidden" name="fEdoReg" id="fEdoReg" value="<?=$fEdoReg?>" />
<input type="hidden" name="fSitTra" id="fSitTra" value="<?=$fSitTra?>" />
<input type="hidden" name="fFingresoD" id="fFingresoD" value="<?=$fFingresoD?>" />
<input type="hidden" name="fFingresoH" id="fFingresoH" value="<?=$fFingresoH?>" />
<input type="hidden" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" />
<input type="hidden" name="fOrderBy" id="fOrderBy" value="<?=$fOrderBy?>" />

<input type="hidden" name="registro" id="registro" value="<?=$registro?>" />
<input type="hidden" id="CodPersona" value="<?=$field_empleado['CodPersona']?>" />
<input type="hidden" id="CodTipoNom" value="<?=$field_empleado['CodTipoNom']?>" />
<div class="divBorder" style="width:1050px;">
<table width="1050" class="tblFiltro">
	<tr>
    	<td colspan="2" class="divFormCaption">Datos del Empleado</td>
    </tr>
	<tr>
		<td align="right" width="125">Empleado:</td>
		<td>
        	<input type="text" id="CodEmpleado" style="width:60px;" class="codigo" value="<?=$field_empleado['CodEmpleado']?>" disabled />
		</td>
	</tr>
	<tr>
		<td align="right">Nombre Completo:</td>
		<td>
        	<input type="text" id="NomCompleto" style="width:500px;" class="codigo" value="<?=$field_empleado['NomCompleto']?>" disabled />
		</td>
	</tr>
</table>
</div>
</form><br />

<table cellpadding="0" cellspacing="0" width="1055" align="center">
	<tr>
    	<td colspan="2" align="center">
        	<form name="frm_periodos" id="frm_periodos" action="gehen.php?anz=empleados_vacaciones" method="post" onsubmit="return empleado_vacaciones();">
            <input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
            <input type="hidden" id="sel_periodos" />
            <table width="100%" class="tblBotones">
                <tr>
                    <td><strong>Fecha de Ingreso:</strong> <?=formatFechaDMA($field_empleado['Fingreso'])?></td>
                    <td><strong>Fecha Actual: <?=formatFechaDMA(substr($Ahora, 0, 10))?></strong></td>
                    <td align="right">
                        <input type="submit" value="Actualizar" style="width:75px;" />
                    </td>
                </tr>
            </table>
            
        	<div style="overflow:scroll; width:100%; height:300px;">
            <table width="100%" class="tblLista">
                <thead>
                    <th scope="col" width="20">#</th>
                    <th scope="col">Periodo</th>
                    <th scope="col" width="45">Mes Prog.</th>
                    <th scope="col" width="75">Derecho</th>
                    <th scope="col" width="75">Pendiente Periodos Ant.</th>
                    <th scope="col" width="75">Dias Solicitud</th>
                    <th scope="col" width="75">Trabaj.</th>
                    <th scope="col" width="75">(Interrump.)</th>
                    <th scope="col" width="75">Total Utiliz.</th>
                    <th scope="col" width="75">Vac. Pendientes</th>
                    <th scope="col" width="75">Cobros</th>
                    <th scope="col" width="75">Pagos Pend.</th>
                </thead>
                
                <tbody>
                <?php
				$NroPeriodo = "";
				$Anio = "";
				$Mes = "";
				$Derecho = "";
				$PendientePeriodo = "";
				$DiasGozados = "";
				$DiasTrabajados = "";
				$DiasInterrumpidos = "";
				$DiasNoGozados = "";
				$TotalUtilizados = "";
				$Pendientes = "";
				$PagosRealizados = "";
				$PendientePago = "";
				//	obtengo los valores almacenados del empleado para el periodo
				$sql = "SELECT
							NroPeriodo,
							Anio,
							Mes,
							Derecho,
							PendientePeriodo,
							DiasGozados,
							DiasTrabajados,
							DiasInterrumpidos,
							DiasNoGozados,
							TotalUtilizados,
							Pendientes,
							PagosRealizados,
							PendientePago
						FROM rh_vacacionperiodo
						WHERE
							CodPersona = '".$field_empleado['CodPersona']."' AND
							CodTipoNom = '".$field_empleado['CodTipoNom']."'";
				$query_periodo = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));	$i=0;
				while ($field_periodo = mysql_fetch_array($query_periodo)) {
					$NroPeriodo[$i] = $field_periodo['NroPeriodo'];
					$Anio[$i] = $field_periodo['Anio'];
					$Mes[$i] = $field_periodo['Mes'];
					$Derecho[$i] = $field_periodo['Derecho'];
					$PendientePeriodo[$i] = $field_periodo['PendientePeriodo'];
					$DiasGozados[$i] = $field_periodo['DiasGozados'];
					$DiasTrabajados[$i] = $field_periodo['DiasTrabajados'];
					$DiasInterrumpidos[$i] = $field_periodo['DiasInterrumpidos'];
					$DiasNoGozados[$i] = $field_periodo['DiasNoGozados'];
					$TotalUtilizados[$i] = $field_periodo['DiasGozados'] - $field_periodo['DiasInterrumpidos'];
					$Pendientes[$i] = $field_periodo['Pendientes'];
					$PagosRealizados[$i] = $field_periodo['PagosRealizados'];
					$PendientePago[$i] = $field_periodo['PendientePago'];
					$i++;
				}
				
				
				//tiempo servicio anterior administracion publica
				
				
				
				//	tiempo de servicio en la contraloria..
				$_FechaInicial = obtenerFechaFin(formatFechaDMA($field_empleado['Fingreso']), -$_PARAMETRO['VACVENDIAS']);
				list($AnioActual, $MesActual, $DiaActual) = split("[/.-]", substr($Ahora, 0, 10));
				list($AnioIngreso, $MesIngreso, $DiaIngreso) = split("[/.-]", $field_empleado['Fingreso']);
				list($Anios, $Meses, $Dias) = getTiempo($_FechaInicial, "$DiaActual-$MesActual-$AnioActual");
				$NroPeriodos = $Anios;
				
				$AniosSOI = ANIO_SERVICIO_OTRA_INSTITUCION($field_empleado['CodPersona']);
				echo "Años en la Contraloria: ".$NroPeriodos."<br>";
			    echo "Años de Servicio en Otra Institucion del Estado:  ". floor(ANIO_SERVICIO_OTRA_INSTITUCION($field_empleado['CodPersona']))."<br>";
				
				//	recorro los periodos y almaceno
				$Quinquenios = 1;
				$Pendiente = 0;
				$Seleccionable = false;
				for($i=0; $i<$NroPeriodos; $i++) {
					$Anio[$i] = $AnioIngreso + $i;
					if ($NroPeriodo[$i] == "") {
						$NroPeriodo[$i] = $i + 1;
						$Mes[$i] = $MesIngreso;
						##	obtengo los dias de derecho
					
						if ( $AniosSOI == 0 && 	$NroPeriodos==0 && $i >= 5)
						{
							
							$_PARAMETRO['DERECHO']+ $Quinquenios++ ;
						}
						
						
							if ( $AniosSOI == 0 && 	$NroPeriodos==0 && $i <= 5)
						{
							
							 $Derecho[$i] = $_PARAMETRO['DERECHO'] ;
						}
						
							if ( $AniosSOI == 0  && $i < 5)
						{
							
							 $Derecho[$i] = $_PARAMETRO['DERECHO'] ;
						}
						
								if ( $AniosSOI == 0  && $i >= 5)
						{
							
								$_PARAMETRO['DERECHO']+ $Quinquenios++ ;
						}
						
											
					//	if ($i >= 5 || $AniosSOI>=5 ) $Derecho[$i] = $_PARAMETRO['DERECHO']+ $Quinquenios++ ;
					//	else  $Derecho[$i] = $_PARAMETRO['DERECHO'] ;
						
						$PendientePeriodo[$i] += $Pendientes[$i-1];
						$DiasGozados[$i] = 0;
						$DiasTrabajados[$i] = 0;
						$DiasInterrumpidos[$i] = 0;
						$TotalUtilizados[$i] = 0;
						$PendientePago[$i] += $DiasPago;
					}
					$Pendientes[$i] = $Derecho[$i] + $PendientePeriodo[$i] - $TotalUtilizados[$i];
					if ($Pendientes[$i] > 0) $FlagUtilizarPeriodo[$i] = "S"; else $FlagUtilizarPeriodo[$i] = "N";
				}
				
				//	imprimo periodos vacacionales
				for($i=$NroPeriodos-1; $i>=0; $i--) {
					$_Mes = intval($Mes[$i]);
					if ($_Mes <= 1 || $_Mes >= 12) $_Mes = "$MesIngreso"; else $_Mes = $Mes[$i];
					if ($Pendientes[$i] > 0) {
						 $style = "font-weight:bold; text-decoration:underline;";
					} else {
						 $style = "font-weight:normal;";
					}
					?>
					<tr class="trListaBody" onclick="mClk(this, 'sel_periodos'); empleado_vacaciones_periodo_sel('<?=$NroPeriodo[$i]?>');" id="periodos_<?=$NroPeriodo[$i]?>">
                    	<th>
                        	<input type="hidden" name="NroPeriodo" value="<?=$NroPeriodo[$i]?>" />
                        	<input type="hidden" name="Anio" value="<?=$Anio[$i]?>" />
							<?=$NroPeriodo[$i]?>
                        </th>
						<td align="center" style=" <?=$style?>">
							<?=$Anio[$i]?> - <?=$Anio[$i]+1?>
                        </td>
                        <td>
                        	<input type="text" name="Mes" style="text-align:center;" class="cell" value="<?=$_Mes?>" />
                        </td>
                        <td>
                        	<input type="text" name="Derecho" style="text-align:right;" class="cell" value="<?=number_format($Derecho[$i], 2, ',', '.')?>" onFocus="numeroFocus(this);" onBlur="numeroBlur(this);" />
                        </td>
                        <td>
                        	<input type="text" name="PendientePeriodo" style="text-align:right;" class="cell2" value="<?=number_format($PendientePeriodo[$i], 2, ',', '.')?>" readonly />
                        </td>
                        <td>
                        	<input type="text" name="DiasGozados" style="text-align:right;" class="cell2" value="<?=number_format($DiasGozados[$i], 2, ',', '.')?>" readonly />
                        </td>
                        <td>
                        	<input type="text" name="DiasTrabajados" style="text-align:right;" class="cell2" value="<?=number_format($DiasTrabajados[$i], 2, ',', '.')?>" readonly />
                        </td>
                        <td>
                        	<input type="text" name="DiasInterrumpidos" style="text-align:right;" class="cell2" value="<?=number_format($DiasInterrumpidos[$i], 2, ',', '.')?>" readonly />
                        </td>
                        <td>
                        	<input type="text" name="TotalUtilizados" style="text-align:right;" class="cell2" value="<?=number_format($TotalUtilizados[$i], 2, ',', '.')?>" readonly />
                        </td>
                        <td>
                        	<input type="text" name="Pendientes" style="text-align:right;" class="cell2" value="<?=number_format($Pendientes[$i], 2, ',', '.')?>" readonly />
                        </td>
                        <td>
                        	<input type="text" name="PagosRealizados" style="text-align:right;" class="cell2" value="<?=number_format($PagosRealizados[$i], 2, ',', '.')?>" readonly />
                        </td>
                        <td>
                        	<input type="text" name="PendientePago" style="text-align:right;" class="cell2" value="<?=number_format($PendientePago[$i], 2, ',', '.')?>" readonly />
                        </td>
					</tr>
					<?
				}
				?>
                </tbody>
            </table>
            </div>
            </form>
        </td>
    </tr>
	<tr>
    	<td width="40%">
        	<form name="frm_utilizacion" id="frm_utilizacion">
            <input type="hidden" id="sel_utilizacion" />
            <input type="hidden" id="nro_utilizacion" />
            <input type="hidden" id="can_utilizacion" />
            <input type="hidden" id="NroPeriodo_utilizacion" />
            <table width="100%" class="tblBotones">
                <tr>
                    <td class="divFormCaption">Utilizaci&oacute;n</td>
                </tr>
                <tr>
                    <td align="right">
                        <input type="button" class="btLista" id="btu_Insertar" value="Insertar" disabled="disabled" onclick="insertarLineaVacacionesUtilizacion(this);" />
                        <input type="button" class="btLista" id="btu_Borrar" value="Borrar" disabled="disabled" onclick="quitarLinea(this, 'utilizacion');" />
                    </td>
                </tr>
            </table>
            
        	<div style="overflow:scroll; width:100%; height:200px;">
            <table width="100%" class="tblLista">
                <thead>
                    <th scope="col" width="20">#</th>
                    <th scope="col">Utilizaci&oacute;n</th>
                    <th scope="col" width="45">Dias</th>
                    <th scope="col" width="75">Inicio</th>
                    <th scope="col" width="75">Fin</th>
                </thead>
                
                <tbody id="lista_utilizacion">
                </tbody>
            </table>
            </div>
            </form>
        </td>
        
    	<td>
        	<form name="frm_pagos" id="frm_pagos">
            <input type="hidden" id="sel_pagos" />
            <input type="hidden" id="nro_pagos" />
            <input type="hidden" id="can_pagos" />
            <table width="100%" class="tblBotones">
                <tr>
                    <td class="divFormCaption">Pagos</td>
                </tr>
                <tr>
                    <td align="right">
                        <input type="button" class="btLista" value="Insertar" disabled="disabled" onclick="insertarLinea2(this, 'empleado_vacaciones_pagos_linea', 'pagos', true);" />
                        <input type="button" class="btLista" value="Borrar" disabled="disabled" onclick="quitarLinea(this, 'pagos');" />
                    </td>
                </tr>
            </table>
            
        	<div style="overflow:scroll; width:100%; height:200px;">
            <table width="100%" class="tblLista">
                <thead>
                    <th scope="col" width="20">#</th>
                    <th scope="col">Utilizaci&oacute;n</th>
                    <th scope="col" width="45">Dias</th>
                    <th scope="col" width="75">Inicio</th>
                    <th scope="col" width="75">Fin</th>
                </thead>
                
                <tbody id="lista_pagos">
                </tbody>
            </table>
            </div>
            </form>
        </td>
    </tr>
</table>
