<?php 
include ("fphp_lg.php");
//	--------------------------
if ($accion == "insertarListaEmpleadoActuacion") {
	connect();
	
	$auditores = split(";", $detalles);
	foreach ($auditores as $auditor) {
		if ($auditor == $persona) die("¡ERROR: Empleado ya insertado a la lista de auditores!");
	}
	
	echo "||";
	?>
    <td align="center">
    	<?=$empleado?>
        <input type="hidden" name="persona" value="<?=$persona?>" />
        <input type="hidden" name="empleado" value="<?=$empleado?>" />
        <input type="hidden" name="nombre" value="<?=$nombre?>" />
	</td>
    <td><?=htmlentities($nombre)?></td>
	<?
}

//	--------------------------
elseif ($accion == "insertarListaActividadesActuacion") {
	connect();
	
	$lineas = split(";", $detalles);
	foreach ($lineas as $linea) {
		if ($linea == $actividad) die("¡ERROR: Actividad ya insertada!");
	}
	
	echo "||";
	
	$fecha_inicio = getFechaFinContinuo(formatFechaDMA($fecha), 1);
	$fecha_termino = getFechaFinContinuo($fecha_inicio, $duracion);
	?>
    <td align="center">
    	<?=htmlentities($descripcion)?>
        <input type="hidden" name="actividad" value="<?=$actividad?>" />
	</td>
    <td align="center"><input type="text" name="duracion" style="width:97%;" value="<?=$duracion?>" /></td>
    <td align="center">
    	<?=$fecha_inicio?>
    	<input type="hidden" name="finicio" value="<?=$fecha_inicio?>" />
	</td>
    <td align="center"><input type="text" name="ftermino" style="width:97%;" value="<?=$fecha_termino?>" /></td>
	<?
}
?>