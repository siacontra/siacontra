<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('01', '0004');
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
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Seleccionar Postulantes</td>
		<td align="right"><a class="cerrar"; href="javascript:window.close();">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" method="post" action="lista_postulantes.php?limit=0">
<?php
$MAXLIMIT=30;
//	VALORES POR DEFECTO Y VALORES SELECCIONADOS DEL FILTRO AL CARGAR O RECARGAR LA PAGINA...............
if ($_POST['chksexo']=="1") { $obj[0]="checked"; $obj[1]=""; $obj[2]=$_POST['fsexo']; }
else { $obj[0]=""; $obj[1]="disabled"; $obj[2]=""; }
if ($_POST['chkginstruccion']=="1") { $obj[3]="checked"; $obj[4]=""; $obj[5]=$_POST['fginstruccion']; $varinstruccion=", rh_postulantes_instruccion.CodGradoInstruccion"; $coninstruccion=" AND (rh_postulantes.Postulante=rh_postulantes_instruccion.Postulante)"; $tblinstruccion=", rh_postulantes_instruccion"; }
else { $obj[3]=""; $obj[4]="disabled"; $obj[5]=""; }
if ($_POST['chkcargo']=="1") { $obj[6]="checked"; $obj[7]=""; $obj[8]=$_POST['fcargo']; $varcargo=", rh_postulantes_cargos.CodCargo"; $concargo=" AND (rh_postulantes.Postulante=rh_postulantes_cargos.Postulante)"; $tblcargo=", rh_postulantes_cargos"; }
else { $obj[6]=""; $obj[7]="disabled"; $obj[8]=""; }
if ($_POST['chkedadd']=="1") { $obj[9]="checked"; $obj[11]=""; $obj[10]=$_POST['fedadd']; }
else { $obj[9]=""; $obj[11]="disabled"; $obj[10]=""; }
if ($_POST['chkedadh']=="1") { $obj[12]="checked"; $obj[38]=""; $obj[13]=$_POST['fedadh']; }
else { $obj[12]=""; $obj[38]="disabled"; $obj[13]=""; }
if ($_POST['chkarea']=="1") { $obj[14]="checked"; $obj[15]=""; $obj[16]=$_POST['farea']; $varinstruccion=", rh_postulantes_instruccion.CodGradoInstruccion"; $coninstruccion=" AND (rh_postulantes.Postulante=rh_postulantes_instruccion.Postulante)"; $tblinstruccion=", rh_postulantes_instruccion"; }
else { $obj[14]=""; $obj[15]="disabled"; $obj[16]=""; }
if ($_POST['chkidioma']=="1") { $obj[17]="checked"; $obj[18]=""; $obj[19]=$_POST['fidioma']; $varidioma=", rh_postulantes_idioma.CodIdioma"; $conidioma=" AND (rh_postulantes.Postulante=rh_postulantes_idioma.Postulante)"; $tblidioma=", rh_postulantes_idioma"; }
else { $obj[17]=""; $obj[18]="disabled"; $obj[19]=""; }
if ($_POST['chkapellido']=="1") { $obj[20]="checked"; $obj[22]=""; $obj[21]=$_POST['fapellido']; }
else { $obj[20]=""; $obj[22]="disabled"; $obj[21]=""; }
if ($_POST['chkprofesion']=="1") { $obj[23]="checked"; $obj[24]=""; $obj[25]=$_POST['fprofesion']; $varinstruccion=", rh_postulantes_instruccion.CodGradoInstruccion"; $coninstruccion=" AND (rh_postulantes.Postulante=rh_postulantes_instruccion.Postulante)"; $tblinstruccion=", rh_postulantes_instruccion"; }
else { $obj[23]=""; $obj[24]="disabled"; $obj[25]=""; }
if ($_POST['chkcursos']=="1") { $obj[26]="checked"; $obj[27]=""; $obj[28]=$_POST['fcursos']; $varcursos=", rh_postulantes_cursos.CodCurso"; $concursos=" AND (rh_postulantes.Postulante=rh_postulantes_cursos.Postulante)"; $tblcursos=", rh_postulantes_cursos"; }
else { $obj[26]=""; $obj[27]="disabled"; $obj[28]=""; }
if ($_POST['chkstatus']=="1") { $obj[29]="checked"; $obj[30]=""; $obj[31]=$_POST['fstatus']; }
else { $obj[29]=""; $obj[30]="disabled"; $obj[31]=""; }
if ($_POST['chkcentros']=="1") { $obj[32]="checked"; $obj[33]=""; $obj[34]=$_POST['fcentros']; $varinstruccion=", rh_postulantes_instruccion.CodGradoInstruccion"; $coninstruccion=" AND (rh_postulantes.Postulante=rh_postulantes_instruccion.Postulante)"; $tblinstruccion=", rh_postulantes_instruccion"; }
else { $obj[32]=""; $obj[33]="disabled"; $obj[34]=""; }
if ($_POST['chkanios']=="1") { $obj[35]="checked"; $obj[37]=""; $obj[36]=$_POST['fanios']; $varanios=", rh_postulantes_experiencia.FechaDesde"; $conanios=" AND (rh_postulantes.Postulante=rh_postulantes_experiencia.Postulante)"; $tblanios=", rh_postulantes_experiencia"; }
else { $obj[35]=""; $obj[37]="disabled"; $obj[36]=""; }
//
echo "
<input type='hidden' name='limit' value='".$_GET['limit']."'>
<input type='hidden' name='filtro' value='".$_GET['filtro']."'>
<div class='divBorder' style='width:1000px;'>
<table width='1000' class='tblFiltro'>
	<tr>
		<td align='right'>
			Sexo: <input type='checkbox' name='chksexo' value='1' $obj[0] onclick='enabledSexo(this.form);' />
		</td>
		<td>			
			<select name='fsexo' id='fsexo' class='selectMed' $obj[1]>
				<option value=''></option>";
				getSexo($obj[2], 0);
				echo "
			</select>
		</td>
		<td align='right'>
			G. Instrucci&oacute;n:
			<input type='checkbox' name='chkginstruccion' value='1' $obj[3] onclick='enabledGInstruccion(this.form);' />
		</td>
		<td>
			<select name='fginstruccion' id='fginstruccion' class='selectMed' $obj[4] onchange='getFProfesiones(this.form);'>
				<option value=''></option>";
				getGInstruccion($obj[5], 0);
				echo "
			</select>
		</td>
		<td align='right'>
			Cargo Aplicable:
			<input type='checkbox' name='chkcargo' value='1' $obj[6] onclick='enabledCargo(this.form);' />
		</td>
		<td>
			<select name='fcargo' id='fcargo' class='selectMed' $obj[7]>
				<option value=''></option>";
				getCargos($obj[8], '', 2);
				echo "
			</select>
		</td>
	</tr>
	<tr>
		<td align='right'>
			Edad:
			<input type='checkbox' name='chkedadd' value='1' $obj[9] onclick='enabledEdadD(this.form);' />
		</td>
		<td>
			<em>&gt;=</em>
			<input type='text' name='fedadd' size='4' maxlength='2' value='$obj[10]' $obj[11] />
			&nbsp; &nbsp;
			<input type='checkbox' name='chkedadh' value='1' $obj[12] onclick='enabledEdadH(this.form);' /> <em>&lt;=</em>
			<input type='text' name='fedadh' size='4' maxlength='2' value='$obj[13]' $obj[38] />
		</td>
		<td align='right'>
			Area Profesional:
			<input type='checkbox' name='chkarea' value='1' $obj[14] onclick='enabledArea(this.form);' /> 
		</td>
		<td>
			<select name='farea' id='farea' class='selectMed' $obj[15] onchange='getFProfesiones(this.form);'>
				<option value=''></option>";
				getMiscelaneos($obj[16], 'AREA', 0);
				echo "
			</select>
		</td>
		<td align='right'>
			Idiomas:
			<input type='checkbox' name='chkidioma' value='1' $obj[17] onclick='enabledIdioma(this.form);' />
		</td>
		<td>
			<select name='fidioma' id='fidioma' class='selectMed' $obj[18]>
				<option value=''></option>";
				getIdiomas($obj[19], 0);
				echo "
			</select>
		</td>
	</tr>
	<tr>
		<td align='right'>
			Apellido:
			<input type='checkbox' name='chkapellido' value='1' $obj[20] onclick='enabledApellido(this.form);' />
		</td>
		<td>
			<input type='text' name='fapellido' size='15' maxlength='20' value='$obj[21]' $obj[22] />
		</td>
		<td align='right'>
			Profesi&oacute;n:
			<input type='checkbox' name='chkprofesion' id='chkprofesion' value='1' $obj[23] onclick='enabledProfesion(this.form);' /> 
		</td>
		<td>
			<select name='fprofesion' id='fprofesion' class='selectMed' $obj[24]>
				<option value=''></option>";
				getProfesiones($obj[25], $obj[5], $obj[16], $opt);
				echo "
			</select>
		</td>
		<td align='right'>
			Cursos:
			<input type='checkbox' name='chkcursos' value='1' $obj[26] onclick='enabledCursos(this.form);' />
		</td>
		<td>
			<select name='fcursos' id='fcursos' class='selectMed' $obj[27]>
				<option value=''></option>";
				getCursos($obj[28], 0);
				echo "
			</select>
		</td>
	</tr>
	<tr>
		<td align='right'>
			Estado:
			<input type='checkbox' name='chkstatus' value='1' checked onclick='forzarCheck(this.id);' /> 
		</td>
		<td>
			<select name='fstatus' id='fstatus' class='selectMed'>";
				getStatusPostulante("P", 1);
				echo "
			</select>
		</td>
		
		<td align='right'>
			Centro de Estudios:
			<input type='checkbox' name='chkcentros' value='1' $obj[32] onclick='enabledCentros(this.form);' />
		</td>
		<td>
			<select name='fcentros' id='fcentros' class='selectMed' $obj[33]>
				<option value=''></option>";
				getCentrosEstudios($obj[34], 0);
				echo "
			</select>
		</td>
		<td align='right'>
			A&ntilde;os de Experiencia:
			<input type='checkbox' name='chkanios' value='1' $obj[35] onclick='enabledAnios(this.form);' />
		</td>
		<td>
			<input type='text' name='fanios' size='5' maxlength='2' value='$obj[36]' $obj[37] />
		</td>
	</tr>
</table>
</div>
<center><input type='button' name='btBuscar' value='Buscar' onclick='filtroPostulantes(this.form, ".$_GET['limit'].");'></center>
<br /><div class='divDivision'>Lista de Postulantes</div><br />";

$_GET['filtro']=strtr($_GET['filtro'], "*", "'");
$_GET['filtro']=strtr($_GET['filtro'], ";", "%");
//	CONSULTO LA TABLA PARA SABER EL TOTAL DE REGISTROS SOLAMENTE............
$sql="SELECT rh_postulantes.* $varinstruccion $varcargo $varidioma $varcursos $varanios FROM rh_postulantes $tblinstruccion $tblcargos $tblidioma $tblcursos $tblanios WHERE (rh_postulantes.Postulante<>'') AND rh_postulantes.Estado='P' $coninstruccion $concargo $conidioma $concursos $conanios ".$_GET['filtro'];
$query=mysql_query($sql) or die ($sql.mysql_error());
$registros=mysql_num_rows($query);
?>

<table width="1000" class="tblBotones">
<tr>
	<td><div id="rows"></div></td>
	<td width="350">
		<?php 
		echo "
		<table align='center'>
			<tr>
				<td>
					<input name='btPrimero' type='button' id='btPrimero' value='&lt;&lt;' onclick='setLotes(this.form, \"P\", $registros, ".$_GET['limit'].", \"".$_GET['ordenar']."\");' />
					<input name='btAtras' type='button' id='btAtras' value='&lt;' onclick='setLotes(this.form, \"A\", $registros, ".$_GET['limit'].", \"".$_GET['ordenar']."\");' />
				</td>
				<td>Del</td><td><div id='desde'></div></td>
				<td>Al</td><td><div id='hasta'></div></td>
				<td>
					<input name='btSiguiente' type='button' id='btSiguiente' value='&gt;' onclick='setLotes(this.form, \"S\", $registros, ".$_GET['limit'].", \"".$_GET['ordenar']."\");' />
					<input name='btUltimo' type='button' id='btUltimo' value='&gt;&gt;' onclick='setLotes(this.form, \"U\", $registros, ".$_GET['limit'].", \"".$_GET['ordenar']."\");' />
				</td>
			</tr>
		</table>";
		?>
		
	</td>
</tr>
</table>

<table width="1000" class="tblLista">
<tr class="trListaHead">
	<th width="35" scope="col">#</th>
	<th width="100" scope="col">C&oacute;digo</th>
	<th width="150" scope="col">Expediente</th>
	<th scope="col">Nombre Completo</th>
	<th width="100" scope="col">Fecha de Registro</th>
	<th width="50" scope="col">Sexo</th>
	<th width="100" scope="col">Estado</th>
</tr>
<?php 
if ($registros!=0) {
	//	CONSULTO LA TABLA
	$sql="SELECT rh_postulantes.* $varinstruccion $varcargo $varidioma $varcursos $varanios FROM rh_postulantes $tblinstruccion $tblcargos $tblidioma $tblcursos $tblanios WHERE (rh_postulantes.Postulante<>'') AND rh_postulantes.Estado='P' $coninstruccion $concargo $conidioma $concursos $conanios ".$_GET['filtro']." LIMIT ".$_GET['limit'].", $MAXLIMIT";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	//	MUESTRO LA TABLA
	for ($i=1; $i<=$rows; $i++) {
		$field=mysql_fetch_array($query);
		list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaRegistro']); $fregistro=$d."-".$m."-".$a;
		if ($field['Estado']=="P") $status="Postulante";
		elseif ($field['Estado']=="A") $status="Aceptado";
		elseif ($field['Estado']=="C") $status="Contratado";
		echo "
		<tr class='trListaBody'>
			<td align='center'><input type='checkbox' id='$i' name='$i' value='".$field['Postulante']."'></td>
			<td align='center'>".$field['Postulante']."</td>
			<td align='center'>".$field['Expediente']."</td>
			<td>".($field['Apellido1']." ".$field['Apellido2'].", ".$field['Nombres'])."</td>
			<td align='center'>".$fregistro."</td>
			<td align='center'>".$field['Sexo']."</td>
			<td align='center'>".$status."</td>
		</tr>";
	}
}
$rows=(int)$rows;
echo "
<script type='text/javascript' language='javascript'>
	totalLista($registros);
	totalLotes($registros, $rows, ".$_GET['limit'].");
</script>";		
?>
</table>
<center><input type="button" name="btAceptar" id="btAceptar" value="Agregar Postulantes" onclick="cargarPreguntas('<?=$pagina?>', '<?=$target?>');" /></center>
<input type="hidden" name="filas" id="filas" value="<?=$rows?>" />
<input type="hidden" name="organismo" id="organismo" value="<?=$organismo?>" />
<input type="hidden" name="registro" id="registro" value="<?=$registro?>" />
<input type="hidden" name="cargo" id="cargo" value="<?=$cargo?>" />
<input type="hidden" name="btContratar" id="btContratar" value="<?=$btContratar?>" />
<input type="hidden" name="tipo" id="tipo" value="E" />
</form>
</body>
</html>