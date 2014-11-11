<?php
mysql_query("SET NAMES 'utf8'");
if ($lista == "usuarios") {
	$btAutorizaciones = "style='display:none;'";
	$btVerAutorizaciones = "style='display:none;'";
	$btAlterna = "style='display:none;'";
	$btVerAlterna = "style='display:none;'";
	$titulo = "Maestro de Usuarios";
}
elseif ($lista == "autorizaciones") {
	$btNuevo = "style='display:none;'";
	$btModificar = "style='display:none;'";
	$btVer = "style='display:none;'";
	$btAlterna = "style='display:none;'";
	$btVerAlterna = "style='display:none;'";
	$titulo = "Dar Autorizaciones a Usuarios";
}
elseif ($lista == "alterna") {
	$btNuevo = "style='display:none;'";
	$btModificar = "style='display:none;'";
	$btVer = "style='display:none;'";
	$btAutorizaciones = "style='display:none;'";
	$btVerAutorizaciones = "style='display:none;'";
	$titulo = "Dar Autorizaciones a Usuarios";
}

elseif ($lista == "reporte") {
	$btNuevo = "style='display:none;'";
	$btModificar = "style='display:none;'";
	$btVer = "style='display:none;'";
	$btAutorizaciones = "style='display:none;'";
	$btVerAutorizaciones = "style='display:none;'";
	$titulo = "Listado de Usuarios";
}
//	------------------------------------
if ($filtrar == "default") {
/*/	$fordenar = "u.Usuario";
	$fedoreg = "A";
	$maxlimit = $_SESSION["MAXLIMIT"];*/
}

//

//if ($fordenar != "") { $cordenar = "checked"; $orderby = "ORDER BY $fordenar"; } else $dordenar = "disabled";
//if ($fedoreg != "") {     $cedoreg = "checked";     $filtro.=" AND (u.Estado = '".$fedoreg."')"; } else $dedoreg = "disabled";
//if ($fdependencia != "") { $fdependencia = "checked"; $orderby = "AND ( BY $fdependencia"; } else $fdependencia = "disabled";
if ($fbuscar != "") {
/*	$cbuscar = "checked";
	$filtro.=" AND (u.Usuario LIKE '%".$fbuscar."%' OR 
					p.NomCompleto LIKE '%".$fbuscar."%' OR 
					u.FechaExpirar LIKE '%".$fbuscar."%')";*/
} else $dbuscar = "disabled";
//	------------------------------------
?>

<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="usuarios_reporte_pdf.php" method="post"  target="iReporte">
	
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="registro" id="registro" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<div class="divBorder" style="width:900px;">
<table width="900" class="tblFiltro">
	<!--<tr>
		<td align="right" width="125">Estado Reg.:</td>
		<td>
            <input type="checkbox" <?=$cedoreg?> onclick="chkFiltro(this.checked, 'fedoreg');" />
            <select name="fedoreg" id="fedoreg" style="width:100px;" <?=$dedoreg?>>
            	<?php
				if ($lista == "usuarios") {
					?>
                    <option value="">&nbsp;</option>
                    <?=loadSelectGeneral("ESTADO", $fedoreg, 0)?>
                    <?
				}
				else {
					?>
                    <?=loadSelectGeneral("ESTADO", "A", 1)?>
                    <?
				}
				?>
            </select>
		</td>
		<td align="right" width="125">Buscar:</td>
        <td>
            <input type="checkbox" <?=$cbuscar?> onclick="chkFiltro(this.checked, 'fbuscar');" />
            <input type="text" name="fbuscar" id="fbuscar" style="width:200px;" value="<?=$fbuscar?>" <?=$dbuscar?> />
		</td>
	</tr>
	<tr>
		<td align="right">Ordenar Por:</td>
		<td>
            <input type="checkbox" <?=$cordenar?> onclick="this.checked=!this.checked;" />
            <select name="fordenar" id="fordenar" style="width:100px;" <?=$dordenar?>>
                <?=loadSelectGeneral("ORDENAR-USUARIO", $fordenar, 0)?>
            </select>
		</td>
	</tr>-->
	 <!-------------------DEPENDENCIA-------------------------->
	<tr>
		<td align="right">Dependencia:</td>
		<td>
            <input type="checkbox" <?=$fdepdendencia?> onclick="this.checked=!this.checked;"  checked="true"  />
            <select name="fdependencia" id="fdependencia" style="width:400px;" <?//=$fdependencia?>>
                <option value="0">Seleccionar Dependencia</option>
  
     <?php $s2="SELECT mastdependencias.CodDependencia, mastdependencias.Dependencia 
     
     FROM mastdependencias where mastdependencias.Estado='A' 
     order by mastdependencias.Dependencia 
     "; 
         $q2=mysql_query($s2); 
         while($rw2=mysql_fetch_array($q2)) { 
         ?>
            <option value="<?php echo $rw2['CodDependencia']; ?>"> <?php echo $rw2['Dependencia']; ?> </option><?php
         }  ?>
     
            </select>
		</td>
	<!-------------------APLICACIONES---------------------------->
	<!--	<td align="right">Aplicacion:</td>
		<td>
            <input type="checkbox" <?=$faplicaciones?> onclick="this.checked=!this.checked;" />
            <select name="faplicaciones" id="faplicaciones" style="width:200px;" <?=$faplicaciones?>>
               <option value="0">Seleccionar Modulo</option>
  
     <?php $s2="SELECT mastaplicaciones.CodAplicacion, mastaplicaciones.Descripcion FROM mastaplicaciones "; 
         $q2=mysql_query($s2); 
         while($rw2=mysql_fetch_array($q2)) { 
         ?>
            <option value="<?php echo $rw2['CodAplicacion']; ?>"> <?php echo $rw2['Descripcion']; ?> </option><?php
            
         }  ?>
       <option value="999" > Todos los Modulos </option>
            </select>
		</td> -->
	</tr>
	

</table>
</div>


<center><input type="submit" value="Buscar"></center><br />

	<center>
	<iframe name="iReporte" id="iReporte" style="border:solid 1px #CDCDCD; width:1000px; height:750px;">
	</iframe>
	</center>

<center>
	
<!--	
<table width="900" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">
			<input type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'gehen.php?anz=usuarios_form&opcion=nuevo');" <?=$btNuevo?> />
            
			<input type="button" class="btLista" id="btModificar" value="Modificar" onclick="cargarOpcion2(this.form, 'gehen.php?anz=usuarios_form&opcion=modificar', 'SELF', '', $('#registro').val());" <?=$btModificar?> />
            
			<input type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion2(this.form, 'gehen.php?anz=usuarios_form&opcion=ver', 'BLANK', 'height=300, width=800, left=100, top=0, resizable=no', $('#registro').val());" <?=$btVer?> />
            
			<input type="button" class="btLista" id="btAutorizaciones" value="Listado PDF" <?php //echo $btAutorizaciones; ?> onclick="cargarPDF(this.form, 'usuarios_reporte_pdf.php', );"/>
            
			<input type="button" class="btLista" id="btVerAutorizaciones" value="Ver" onclick="cargarPagina(this.form, 'gehen.php?anz=usuarios_autorizaciones&opcion=ver');" <?=$btVerAutorizaciones?> />
            
			<input type="button" class="btLista" id="btAlterna" value="Agregar" onclick="cargarOpcion2(this.form, 'gehen.php?anz=usuarios_alterna&opcion=agregar', 'SELF', '', $('#registro').val());" <?=$btAlterna?> />
            
			<input type="button" class="btLista" id="btVerAlterna" value="Ver" onclick="cargarOpcion2(this.form, 'gehen.php?anz=usuarios_alterna&opcion=ver', 'BLANK', 'height=700, width=900, left=100, top=0, resizable=no', $('#registro').val());" <?=$btVerAlterna?> />
		</td>
	</tr>
</table>
<!--
<div style="overflow:scroll; width:1200px; height:300px;">
<table width="100%" class="tblLista">
	<thead>
	<tr> --!>
		<!--<th  width="5">x</th>
		
		<th scope="col" width="50" align="left"></th>
		<th scope="col" width="50" align="left"></th>

		<th scope="col" width="50">Nuevo</th>
		<th scope="col" width="50">Mostrar</th>
		<th scope="col" width="50">Eliminar</th> -->
<!--	</tr>
    </thead>
    
    <tbody>
-->
	<?php
	//	consulto todos
/*	$sql = "SELECT
				u.*,
				p.NomCompleto AS NomEmpleado
			FROM
				usuarios u
				INNER JOIN mastpersonas p ON (u.CodPersona = p.CodPersona)
			WHERE 1 $filtro";
			* 
			* 
			*/
			
$sql= "SELECT
u.Usuario,
mastdependencias.Dependencia,
mastpersonas.NomCompleto
FROM
mastempleado
INNER JOIN usuarios AS u ON mastempleado.CodPersona = u.CodPersona
INNER JOIN mastdependencias ON mastempleado.CodDependencia = mastdependencias.CodDependencia
INNER JOIN mastpersonas ON u.CodPersona = mastpersonas.CodPersona
where
 mastempleado.Estado='A' AND
 mastempleado.CodDependencia='".$_POST["fdependencia"]."'";		
			
	$query_lista = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	$rows_total = mysql_num_rows($query_lista);

	
$sql= "SELECT
u.Usuario,
mastdependencias.Dependencia,
mastpersonas.NomCompleto
FROM
mastempleado
INNER JOIN usuarios AS u ON mastempleado.CodPersona = u.CodPersona
INNER JOIN mastdependencias ON mastempleado.CodDependencia = mastdependencias.CodDependencia
INNER JOIN mastpersonas ON u.CodPersona = mastpersonas.CodPersona
where
 mastempleado.Estado='A' AND
 mastempleado.CodDependencia='".$_POST["fdependencia"]."'";

			
	$query_lista = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	$rows_lista = mysql_num_rows($query_lista);
	while ($field_lista = mysql_fetch_array($query_lista)) {
		
		?>
		<!----------------------------
		<tr>
		<table width="50%" >
			
	
		<tr>
		<td scope="col" width="10" ></td>
		<td scope="col" >Usuario:</td>
		<td scope="col" colspan=6 ><?=$field_lista['Usuario']?></td>
		
		</tr>	
		
		<tr>
		<td scope="col" width="10" ></td>
		<td scope="col" >Nombre:</td>
		<td scope="col" colspan=6 ><?=$field_lista['NomCompleto']?></td>
		
		</tr>	
		
		<tr>
		<td scope="col" width="10" ></td>
		<td scope="col" >Dependencia:</td>
		<td scope="col" colspan=6 ><?=$field_lista['Dependencia']?></td>
		
		</tr>	
			
		<td scope="col" colspan=3 width="10" ></td>
	
		<td scope="col" width="50">Nuevo</td>
		<td scope="col" width="50">Mostrar</td>
		<td scope="col" width="50">Eliminar</td>
		

	
		<?
		
		
		$sql_permiso = "
				
			SELECT
			u.Usuario,
			u.CodPersona,
			p.NomCompleto AS NomEmpleado,
			sa.FlagAdministrador,
			sa.Concepto,
			sa.Grupo,
			seguridad_concepto.Descripcion,
			sa.CodAplicacion
			FROM
			usuarios AS u
			INNER JOIN mastpersonas AS p ON (u.CodPersona = p.CodPersona)
			INNER JOIN mastempleado AS e ON (p.CodPersona = e.CodPersona)
			LEFT JOIN seguridad_autorizaciones AS sa ON (sa.CodAplicacion = '".$_POST["faplicaciones"]."' 
			
			AND  sa.Usuario = '".$field_lista['Usuario']."') AND u.Usuario = sa.Usuario
			INNER JOIN seguridad_concepto ON seguridad_concepto.Concepto = sa.Concepto
			WHERE u.Usuario = '".$field_lista['Usuario']."'
			AND  mastempleado.Estado='A' 
			";
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	

	$sql="SELECT 
	
	   c.Concepto, 
	   c.Descripcion AS NomConcepto, 
	   g.Grupo, 
	   g.Descripcion AS NomGrupo, 
	   a.FlagMostrar, 
	   a.FlagAgregar, 
	   a.FlagModificar, 
	   a.FlagEliminar
	   
	   FROM seguridad_concepto c 
	   
	   INNER JOIN seguridad_grupo g ON (c.CodAplicacion=g.CodAplicacion AND c.Grupo=g.Grupo) 
	   LEFT JOIN seguridad_autorizaciones a ON (
	   
	   c.CodAplicacion=a.CodAplicacion 
	   AND c.Grupo=a.Grupo 
	   AND c.Concepto=a.Concepto 
	   AND a.Usuario='".$field_lista['Usuario']."') 
	  
	   WHERE c.CodAplicacion= '".$_POST["faplicaciones"]."' 
	    AND a.FlagMostrar='S'
	   ORDER BY g.Grupo, c.Concepto";
	   
	$query=mysql_query($sql) or die ($sql.mysql_error());
	while($field=mysql_fetch_array($query)) {
		if ($field['FlagMostrar']=="S") {
			$concepto="checked"; 
			$dagregar="";
			$dmodificar="";
			$deliminar="";
		} else {
			$concepto=""; 
			$dagregar="disabled";
			$dmodificar="disabled";
			$deliminar="disabled";
		}
		if ($field['FlagAgregar']=="S") $chkagregar="checked"; else $chkagregar="";
		if ($field['FlagModificar']=="S") $chkmodificar="checked"; else $chkmodificar="";
		if ($field['FlagEliminar']=="S") $chkeliminar="checked"; else $chkeliminar="";
		
        if ($grupo!=$field['Grupo']) {
			$grupo=$field['Grupo'];
			echo "<tr class='trListaBody2'>
			<td ></td>
			<td colspan='5'>".utf8_decode(htmlentities($field['NomGrupo']))."</td>
			</tr>";
		}
		echo "
		<tr class='trListaBody'>
			<td align='center'>
			 <input type='checkbox' name='".$field['Concepto']."' id='".$field['Concepto']."' value='".$field['Concepto']."' onclick='checkPermisos(this.id, this.checked);' disabled $concepto /></td>
			<td colspan=2>".utf8_decode(htmlentities($field['NomConcepto']))."</td>
			
			<td align='center'><input type='checkbox' name='N_".$field['Concepto']."' id='N_".$field['Concepto']."' value='S' $chkagregar disabled /></td>
			<td align='center'><input type='checkbox' name='M_".$field['Concepto']."' id='M_".$field['Concepto']."' value='S' $chkmodificar disabled /></td>
			<td align='center'><input type='checkbox' name='E_".$field['Concepto']."' id='E_".$field['Concepto']."' value='S' $chkeliminar disabled /></td>
		
		</tr>";
	}
?>		
			
		</tr>
		</table>
<?	
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////					
		/*	$query_permiso = mysql_query($sql_permiso) or die(getErrorSql(mysql_errno(), mysql_error(), $sql_permiso));
			$rows_permiso = mysql_num_rows($query_permiso);
			while ($field_permiso = mysql_fetch_array($query_permiso)) {
				
					?>
					<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$field_lista['Usuario']?>">
					<td><?=($field_permiso['CodAplicacion'])?></td>
					<td><?=$field_permiso['Descripcion']?></td>
					<td align="center"><?=printFlag($field_lista['FlagAdministrador'])?></td>
					
					</tr>
					<?
			}*/
	
		
	}
	?>
    </tbody>
</table>
</div>
<table width="900">
	<tr>
    	<td>
        	Mostrar: 
            <select name="maxlimit" style="width:50px;" onchange="this.form.submit();">
                <?=loadSelectGeneral("MAXLIMIT", $maxlimit, 0)?>
            </select>
        </td>
        <td align="right">
        	<?=paginacion(intval($rows_total), intval($rows_lista), intval($maxlimit), intval($limit));?>
        </td>
    </tr>
</table>
</center>
</form>
