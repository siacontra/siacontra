<?php
if ($opcion == "nuevo") {
	$accion = "nuevo";
	$titulo = "Nueva Prorroga";
	$cancelar = "document.getElementById('frmentrada').submit();";
	$CodOrganismo = $_SESSION["ORGANISMO_ACTUAL"];
	$NomOrganismo = $_SESSION["NOMBRE_ORGANISMO_ACTUAL"];
	if ($_SESSION["CONTROL_FISCAL_ACTUAL"] == "S") {
		$CodDependencia = $_SESSION["DEPENDENCIA_ACTUAL"];
		$NomDependencia = $_SESSION["NOMBRE_DEPENDENCIA_ACTUAL"];
	} else {
		
	}
	$Estado = "PR";
	$FechaRegistro = date("Y-m-d");
	$PreparadoPor = $_SESSION["CODPERSONA_ACTUAL"];
	$NomPreparadoPor = $_SESSION["NOMBRE_USUARIO_ACTUAL"];
	$CodProceso = "01";
	$label_submit = "Guardar";
}
elseif ($opcion == "modificar" || $opcion == "ver" || $opcion == "revisar" || $opcion == "aprobar" || $opcion == "anular") {
	//	consulto datos generales
	$sql = "SELECT
				p.*,
				af.FechaInicio,
				af.FechaTerminoReal,
				p1.NomCompleto AS NomPreparadoPor,
				p2.NomCompleto AS NomRevisadoPor,
				p3.NomCompleto AS NomAprobadoPor
			FROM
				pf_prorroga p
				INNER JOIN pf_actuacionfiscal af ON (p.CodActuacion = af.CodActuacion)
				INNER JOIN mastpersonas p1 ON (p.PreparadoPor = p1.CodPersona)
				LEFT JOIN mastpersonas p2 ON (p.RevisadoPor = p2.CodPersona)
				LEFT JOIN mastpersonas p3 ON (p.AprobadoPor = p3.CodPersona)
			WHERE p.CodProrroga = '".$registro."'";
	$query_mast = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query_mast)) $field_mast = mysql_fetch_array($query_mast);
	
	if ($opcion == "modificar") {
		$accion = "modificar";
		$titulo = "Modificar Prorroga";
		$cancelar = "document.getElementById('frmentrada').submit();";
		$display_modificar = "display:none;";
		$disabled_modificar = "disabled";
		$label_submit = "Modificar";
	}
	
	elseif ($opcion == "ver") {
		$disabled_ver = "disabled";
		$titulo = "Ver Prorroga";
		$cancelar = "window.close();";
		$display_submit = "display:none;";
		$display_modificar = "display:none;";
		$disabled_modificar = "disabled";
	}
	
	elseif ($opcion == "revisar") {
		$accion = "revisar";
		$disabled_ver = "disabled";
		$titulo = "Revisar Prorroga";
		$cancelar = "document.getElementById('frmentrada').submit();";
		$display_modificar = "display:none;";
		$disabled_modificar = "disabled";
		$label_submit = "Revisar";
	}
	
	elseif ($opcion == "aprobar") {
		$accion = "aprobar";
		$disabled_ver = "disabled";
		$titulo = "Aprobar Prorroga";
		$cancelar = "document.getElementById('frmentrada').submit();";
		$display_modificar = "display:none;";
		$disabled_modificar = "disabled";
		$label_submit = "Aprobar";
	}
	
	elseif ($opcion == "anular") {
		$accion = "anular";
		$disabled_ver = "disabled";
		$titulo = "Anular Prorroga";
		$cancelar = "document.getElementById('frmentrada').submit();";
		$display_modificar = "display:none;";
		$disabled_modificar = "disabled";
		$label_submit = "Anular";
	}
	
	$CodOrganismo = $field_mast["CodOrganismo"];
	$Estado = $field_mast['Estado'];
  	$CodProceso = $field_mast['CodProceso'];
	$FechaRegistro = $field_mast['FechaRegistro'];
	$PreparadoPor = $field_mast['PreparadoPor'];
	$NomPreparadoPor = $field_mast['NomPreparadoPor'];
}
//	------------------------------------
?>
<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
	$("#FechaTerminoReal").val($("#ffin").val());
});
</script>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="#" onclick="<?=$cancelar?>">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<table width="800" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <div class="header">
            <ul id="tab">
            <!-- CSS Tabs -->
            <li id="li1" onclick="currentTab('tab', this);" class="current"><a href="#" onclick="mostrarTab('tab', 1, 2);">Informaci&oacute;n General</a></li>
            <li id="li2" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 2, 2);">Actividades</a></li>
            </ul>
            </div>
        </td>
    </tr>
</table>

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=<?=$action?>" method="POST" onsubmit="return actuacion_fiscal_prorrogas(this, '<?=$accion?>');">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="fedoreg" id="fedoreg" value="<?=$fedoreg?>" />
<input type="hidden" name="fbuscar" id="fbuscar" value="<?=$fbuscar?>" />
<input type="hidden" name="fordenar" id="fordenar" value="<?=$fordenar?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="forganismo" id="forganismo" value="<?=$forganismo?>" />
<input type="hidden" name="fnomorganismo" id="fnomorganismo" value="<?=$fnomorganismo?>" />
<input type="hidden" name="fnomdependencia" id="fnomdependencia" value="<?=$fnomdependencia?>" />
<input type="hidden" name="fdependencia" id="fdependencia" value="<?=$fdependencia?>" />
<input type="hidden" name="fregistrod" id="fregistrod" value="<?=$fregistrod?>" />
<input type="hidden" name="fregistroh" id="fregistroh" value="<?=$fregistroh?>" />
<input type="hidden" name="fedoreg" id="fedoreg" value="<?=$fedoreg?>" />
<input type="hidden" name="forganismoext" id="forganismoext" value="<?=$forganismoext?>" />
<input type="hidden" name="fnomorganismoext" id="fnomorganismoext" value="<?=$fnomorganismoext?>" />
<input type="hidden" name="fdependenciaext" id="fdependenciaext" value="<?=$fdependenciaext?>" />
<input type="hidden" name="fnomdependenciaext" id="fnomdependenciaext" value="<?=$fnomdependenciaext?>" />
<input type="hidden" name="fordenar" id="fordenar" value="<?=$fordenar?>" />
<input type="hidden" name="fanio" id="fanio" value="<?=$fanio?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" id="Prorroga" value="<?=$field_mast['Prorroga']?>" />
<input type="hidden" id="CodActividad" value="<?=$field_mast['CodActividad']?>" />
<input type="hidden" id="FechaInicioReal" value="<?=$field_mast['FechaInicio']?>" />
<input type="hidden" id="FechaTerminoReal" value="<?=$field_mast['FechaTerminoReal']?>" />

<div id="tab1" style="display:block;">
<table width="800" class="tblForm">
	<tr>
		<td class="tagForm">Prorroga:</td>
		<td>
        	<input type="text" id="CodProrroga" value="<?=$field_mast['CodProrroga']?>" style="width:112px;" class="codigo" disabled="disabled" />
		</td>
		<td class="tagForm">Estado:</td>
		<td>
        	<input type="hidden" id="Estado" value="<?=$Estado?>" />
        	<input type="text" style="width:110px;" class="codigo" value="<?=strtoupper(printValores("ESTADO-ACTUACION-PRORROGA", $Estado))?>" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Actuaci&oacute;n:</td>
		<td class="gallery clearfix">
        	<input type="text" id="CodActuacion" value="<?=$field_mast['CodActuacion']?>" style="width:110px;" disabled="disabled" />
            <a href="../lib/listas/listado_actuacion_fiscal.php?filtrar=default&cod=&nom=&ventana=prorrogas&iframe=true&width=1100&height=525" rel="prettyPhoto[iframe1]" style=" <?=$display_modificar?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
		</td>
		<td class="tagForm">F.Registro:</td>
		<td>
        	<input type="text" id="FechaRegistro" value="<?=formatFechaDMA($FechaRegistro)?>" style="width:78px;" class="disabled" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Motivo:</td>
		<td colspan="3">
        	<textarea id="Motivo" style="width:98%; height:100px;" <?=$disabled_ver?>><?=$field_mast['Motivo']?></textarea>
		</td>
	</tr>
	<tr>
		<td class="tagForm">Preparado Por:</td>
		<td colspan="3">
        	<input type="hidden" value="<?=$PreparadoPor?>" />
        	<input type="text" id="NomPreparadoPor" value="<?=$NomPreparadoPor?>" style="width:295px;" class="disabled" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Revisado Por:</td>
		<td colspan="3">
        	<input type="hidden" value="<?=$field_mast['RevisadoPor']?>" />
        	<input type="text" id="NomRevisadoPor" value="<?=$field_mast['NomRevisadoPor']?>" style="width:295px;" class="disabled" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Aprobado Por:</td>
		<td colspan="3">
        	<input type="hidden" value="<?=$field_mast['AprobadoPor']?>" />
        	<input type="text" id="NomAprobadoPor" value="<?=$field_mast['NomAprobadoPor']?>" style="width:295px;" class="disabled" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td colspan="3">
			<input type="text" value="<?=$field_mast['UltimoUsuario']?>" style="width:200px;" class="disabled" disabled="disabled" />
			<input type="text" value="<?=$field_mast['UltimaFecha']?>" style="width:150px;" class="disabled" disabled="disabled" />
		</td>
	</tr>
</table>

<center>
<input type="submit" value="<?=$label_submit?>" style="width:80px; <?=$display_submit?>" />
<input type="button" value="Cancelar" style="width:80px;" onclick="<?=$cancelar?>" />
</center>
<br />
<div style="width:800px; <?=$display_submit?>" class="divMsj">(*) Campos Obligatorios</div>
</div>
</form>

<div id="tab2" style="display:none;">
<form name="frm_actividades" id="frm_actividades" onsubmit="return false;">
<input type="hidden" name="sel_actividades" id="sel_actividades" />
<center>
<div style="overflow:scroll; width:800px; height:400px;" id="tabla_actividades">
<table width="1075" class="tblLista">
	<thead>
	<tr>
        <th scope="col" colspan="2" style="background-color:#FFF;">&nbsp;</th>
        <th scope="col" colspan="3">Planificaci&oacute;n Inicial</th>
        <th scope="col" colspan="4">Planificaci&oacute;n Real</th>
        <th scope="col" colspan="3">Ejecuci&oacute;n</th>
        <th scope="col" colspan="2" style="background-color:#FFF;">&nbsp;</th>
    </tr>
	<tr>
        <th scope="col" width="20">Est.</th>
        <th scope="col" align="left">Actividad</th>
        <th scope="col" width="30">Dias</th>
        <th scope="col" width="60">Inicio</th>
        <th scope="col" width="60">Fin</th>
        <th scope="col" width="30">Prorr. Acu.</th>
        <th scope="col" width="30">Prorr.</th>
        <th scope="col" width="60">Inicio Real</th>
        <th scope="col" width="60">Fin Real</th>
        <th scope="col" width="30">Dias Ejec.</th>
        <th scope="col" width="60">Fecha Cierre</th>
        <th scope="col" width="60">Fecha Registro</th>
        <th scope="col" width="25">Au. Ar.</th>
        <th scope="col" width="25">No Afe.</th>
    </tr>
    </thead>
    
    <tbody id="lista_actividades">
    <?
	$nroactividades = 0;
	if ($opcion != "nuevo") {
		 $sql = "SELECT
					afd.*,
					a.CodFase,
					a.Descripcion AS NomActividad,
					a.FlagNoAfectoPlan,
					a.FlagAutoArchivo,
					f.Descripcion AS NomFase,
					(SELECT SUM(Prorroga)
					 FROM pf_prorroga
					 WHERE
						Estado = 'AP' AND
						CodActividad = afd.CodActividad AND
						CodActuacion = afd.CodActuacion
					) AS ProrrogaAcu
				FROM
					pf_actuacionfiscaldetalle afd
					INNER JOIN pf_actividades a ON (afd.CodActividad = a.CodActividad)
					INNER JOIN pf_fases f ON (a.CodFase = f.CodFase)
				WHERE afd.CodActuacion = '".$field_mast['CodActuacion']."'
				ORDER BY CodActividad";
		$query_actividades = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
				
			
			while ($field_actividades = mysql_fetch_array($query_actividades)) {
			$nroactividades++;
			$FechaInicioReal = formatFechaDMA($field_actividades['FechaInicioReal']);
			$finicio = formatFechaDMA($field_actividades['FechaInicioReal']);
			
			/*if ($field_actividades['Estado'] == "EJ")
				$tiempo = $field_actividades['Prorroga'] + $field_mast['Prorroga'] + $field_actividades['Duracion'];
			else*/
				$tiempo = $field_actividades['Prorroga'] + $field_actividades['Duracion'];
			$FechaTerminoReal = getFechaFinHabiles($FechaInicioReal, $tiempo);
			if ($grupo != $field_actividades['CodFase']) {
				if ($nroactividades > 1)  {
					?>
					<tr>
                        <th colspan="2">&nbsp;</th>
                        <th align="center">
                            <span style="font-weight:bold;"><?=$fase_duracion?></span>
                        </th>
                        <th colspan="2">&nbsp;</th>
                        <th align="center">
                            <span style="font-weight:bold;"><?=$fase_prorroga?></span>
                        </th>
                    </tr>
					<?
					$fase_duracion = 0;
					$fase_prorroga = 0;
				}
				$grupo = $field_actividades['CodFase'];
				?>
				<tr class="trListaBody2">
					<td colspan="2"><?=$field_actividades['CodFase']?> <?=$field_actividades['NomFase']?></td>
				</tr>
				<?
			}
			?>
			<tr class="trListaBody" id="<?=$field_actividades['CodActividad']?>">
				<td align="center"><?=printEstadoActuacion($field_actividades['Estado'])?></td>
                <td>
                    <input type="hidden" name="CodFase" value="<?=$field_actividades['CodFase']?>" />
                    <input type="hidden" name="NomFase" value="<?=($field_actividades['NomFase'])?>" />
                    <input type="hidden" name="CodActividad" value="<?=$field_actividades['CodActividad']?>" />
                    <input type="hidden" name="Descripcion" value="<?=($field_actividades['NomActividad'])?>" />
                    <input type="hidden" name="FlagAutoArchivo" value="<?=$field_actividades['FlagAutoArchivo']?>" />
                    <input type="hidden" name="FlagNoAfectoPlan" value="<?=$field_actividades['FlagNoAfectoPlan']?>" />
                    <input type="hidden" name="Estado" value="<?=$field_actividades['Estado']?>" />
                    <?=($field_actividades['NomActividad'])?>
                </td>
                <td align="center">
					<?=$field_actividades['Duracion']?>
                    <input type="hidden" name="Duracion" value="<?=($field_actividades['Duracion'])?>" />
                </td>
                <td align="center">
                    <?=formatFechaDMA($field_actividades['FechaInicio'])?>
                    <input type="hidden" name="FechaInicio" value="<?=formatFechaDMA($field_actividades['FechaInicio'])?>" />
                </td>
                <td align="center">
                    <?=formatFechaDMA($field_actividades['FechaTermino'])?>
                    <input type="hidden" name="FechaTermino" value="<?=formatFechaDMA($field_actividades['FechaTermino'])?>" />
                </td>
                <td align="center">
                    <?=intval($field_actividades['Prorroga'])?>
                    <input type="hidden" name="ProrrogaAcu" value="<?=($field_actividades['Prorroga'])?>" />
                </td>
                <td align="center">
                    <?php
					/*if ($field_actividades['Estado'] == "EJ") {
						?><input type="text" name="Prorroga" value="<?=$field_mast['Prorroga']?>" class="cell" style="text-align:center;" onchange="setFechaActividadesProrroga(this.value, '<?=$field_actividades['CodActividad']?>');" <?=$disabled_ver?> /><?
					} else {*/
						?><input type="hidden" name="Prorroga" value="<?=$field_actividades['Prorroga']?>" /><?
						echo $field_actividades['Prorroga'];
					//}
					?>
                </td>
                <td align="center">
                     <?=$FechaInicioReal?>
                    <input type="hidden" name="FechaInicioReal" value="<?=$FechaInicioReal?>" />
                </td>
                <td align="center">
                    <?=$FechaTerminoReal?>
                    <input type="hidden" name="FechaTerminoReal" value="<?=$FechaTerminoReal?>" />
                </td>
                <td align="center">
                    <?=($field_actividades['DiasCierre'])?>
                    <input type="hidden" name="DiasCierre" value="<?=($field_actividades['DiasCierre'])?>" />
                </td>
                <td align="center">
                    <?=formatFechaDMA($field_actividades['FechaTerminoCierre'])?>
                    <input type="hidden" name="FechaTerminoCierre" value="<?=formatFechaDMA($field_actividades['FechaTerminoCierre'])?>" />
                </td>
                <td align="center">
                    <?=formatFechaDMA($field_actividades['FechaRegistroCierre'])?>
                    <input type="hidden" name="FechaRegistroCierre" value="<?=formatFechaDMA($field_actividades['FechaRegistroCierre'])?>" />
                    <input type="hidden" name="DiasAdelanto" value="<?=($field_actividades['DiasAdelanto'])?>" />
                </td>
                <td align="center"><?=printFlag($field_actividades['FlagAutoArchivo'])?></td>
                <td align="center"><?=printFlag($field_actividades['FlagNoAfectoPlan'])?></td>
			</tr>
			<?
			$FechaInicioReal = getFechaFinHabiles($FechaTerminoReal, 2);
			if ($field_actividades['FlagNoAfectoPlan'] == "N") {
				$total_duracion += $field_actividades['Duracion'];
				$fase_duracion += $field_actividades['Duracion'];
				$total_prorroga += $field_actividades['Prorroga'];
				$fase_prorroga += $field_actividades['Prorroga'];
				$ffin = $FechaTerminoReal;
			}
		}
		?>
    	<tr>
        	<th colspan="2">&nbsp;</th>
        	<th align="center">
            	<span style="font-weight:bold;"><?=$fase_duracion?></span>
            </th>
        	<th colspan="2">&nbsp;</th>
        	<th align="center">
            	<span style="font-weight:bold;"><?=$fase_prorroga?></span>
            </th>
        </tr>
	<?
	}
	?>
    </tbody>
    
    <tfoot>
    	<tr>
        	<td colspan="13">&nbsp;</td>
        </tr>
    	<tr>
        	<td colspan="2">&nbsp;</td>
        	<td align="center">
            	<span id="total_duracion" style="font-weight:bold;"><?=$total_duracion?></span>
            </td>
        	<td colspan="2">&nbsp;</td>
        	<td align="center">
            	<span id="total_prorroga" style="font-weight:bold;"><?=$total_prorroga?></span>
            </td>
        </tr>
    </tfoot>
</table>
</div>
</center>
<input type="hidden" id="nro_actividades" value="<?=$nroactividades?>" />
<input type="hidden" id="can_actividades" value="<?=$nroactividades?>" />
<input type="hidden" id="ffin" value="<?=($ffin)?>" />
</form>
</div>
