<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp.php");
connect();
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
</head>

<body>
<div id="bloqueo" class="divBloqueo"></div>
<div id="cargando" class="divCargando">
<table>
	<tr>
    	<td valign="middle" style="height:50px;">
			<img src="../imagenes/iconos/cargando.gif" /><br />Cargando datos... 
        </td>
    </tr>
</table>
</div>

<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Control de Eventos</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form action="<?=$PHP_SELF?>" method="post" enctype="multipart/form-data" name="frmentrada" id="frmentrada" onsubmit="enabledCargando('block');">
  <p align="center">Archivo  <input name="archivo" type="file" id="archivo" size="100" /></p>
  <p align="center"> <input name="boton" type="submit" id="boton" value="Procesar Archivo"></p>
</form> 

<?php
if($boton) {
	?>
	
	
	<?
	//if ($_FILES['archivo']['type'] == "text/plain") {
	//	if (is_uploaded_file($_FILES['archivo']['tmp_name'])) {
	//	  copy($_FILES['archivo']['tmp_name'], $_FILES['archivo']['name']);
	//	  $subio = true;
	//	}
	//}

	if ($_FILES['archivo']['type'] == "text/plain") {
		if (is_uploaded_file($_FILES['archivo']['tmp_name'])) 
		{		  
		  echo $_FILES['archivo']['tmp_name'];		  
		  $conexion_ssh = ssh2_connect('192.168.1.9', 22);
                  ssh2_auth_password($conexion_ssh, 'siaceda', 's14c3d4');
                  $envio=ssh2_scp_send($conexion_ssh, $_FILES['archivo']['tmp_name'], '/home/siaceda/biometrico', 0666);
		  echo $envio;			
		  if($envio)
		  {
			//echo $_FILES['archivo']['tmp_name'];
			$subio = true;
		  }
		  
		}
		else
		{
			$subio = false;			
		}
	}
	echo $subio;
	exit;
	
	$data = "/home/siaceda/biometrico/".$_FILES['archivo']['name'];
	$sql = "LOAD DATA INFILE '".$data."' INTO TABLE rh_transfeventasistencia";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	
	unlink($_FILES['archivo']['name']);
	
	if($subio) {
		$sql = "SELECT 
					mp.Busqueda, 
					mp.Ndocumento, 
					me.CodEmpleado, 
					me.CodPersona, 
					me.CodDependencia, 
					me.CodCarnetProv, 
					md.Dependencia, 
					rp.DescripCargo 
				FROM 
					mastempleado me 
					INNER JOIN mastpersonas mp ON (me.CodPersona = mp.CodPersona) 
					INNER JOIN mastdependencias md ON (me.CodDependencia = md.CodDependencia) 
					INNER JOIN rh_puestos rp ON (me.CodCargo = rp.CodCargo) 
				ORDER BY CodDependencia, CodEmpleado";
		$query_empleado = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_empleado = mysql_fetch_array($query_empleado)) {
			if ($field_empleado['CodCarnetProv'] != "") $codempleado = (int) $field_empleado['CodCarnetProv'];
			else $codempleado = (int) $field_empleado['CodEmpleado'];	
			$sql = "SELECT 
						ce.* 
					FROM 
						rh_transfeventasistencia ce 
					WHERE 
						ce.Id_Empleado = '".$codempleado."'";
			$query_eventos = mysql_query($sql) or die ($sql.mysql_error());
			while ($field_eventos = mysql_fetch_array($query_eventos)) {$i++;
				if ($hora != $field_eventos['Hora']) {
					list($dia, $mes, $anio)=SPLIT( '[/.-]', $field_eventos['Fecha']); $fecha = "$anio-$mes-$dia";
					list($hora, $meridiano)=SPLIT( '[ ]', $field_eventos['Hora']);
					list($hora, $minuto, $segundo)=SPLIT( '[:.:]', $hora);
					
					if ($meridiano == "a.m.") $hora_evento = "$hora:$minuto:$segundo";
					elseif ($meridiano == "p.m.") {
						if ($hora <> "12") { $hora = (int) $hora; $hora = $hora + 12; }
						if ($hora == 24) $hora = "00";
						elseif ($hora < 10) $hora = "0$hora";
						$hora_evento = "$hora:$minuto:$segundo";
					}
					else $hora_evento = "00:00:00";
					
					//$codevento = getSecuencia("CodEvento", "CodPersona", "rh_controlasistencia", $field_empleado['CodEmpleado']);
					$sql = "INSERT INTO rh_controlasistencia (CodPersona,
																Fecha,
																Hora,
																FechaFormat,
																HoraFormat,
																Event_Puerta,
																Estado,

																UltimoUsuario,
																UltimaFecha)
														VALUES ('".$field_empleado['CodEmpleado']."',
																'".$field_eventos['Fecha']."',
																'".$field_eventos['Hora']."',
																'".$fecha."',
																'".$hora_evento."',
																'".$field_eventos['Event_Puerta']."',
																'S',
																'".$_SESSION['USUARIO_ACTUAL']."', 
																'".date("Y-m-d H:i:s")."')";
					$query_insert = mysql_query($sql) or die ($sql.mysql_error());
				}
				$hora = $field_eventos['Hora'];
			}
		}
		
		
		?>
		<script language="javascript">
			enabledCargando("none");
			alert("¡EL ARCHIVO SE SUBIO EXITOSAMENTE!");
		</script>
		<?
	} else {
		?>
		<script language="javascript">
			alert("¡ERROR AL SUBIR ARCHIVO!");
		</script>
		<?
	}
}
?>
</body>
</html>
